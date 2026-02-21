<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Notification;
use App\Models\Rent;
use App\Models\Room;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BillingController extends Controller
{
    /**
     * Display billings.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $query = Billing::withoutTempatKosScope()
                ->with(['user', 'room', 'latestPayment'])
                ->whereIn('tipe', ['pelunasan', 'bulanan']);
        } else {
            $query = Billing::with(['user', 'room', 'latestPayment'])
                ->whereIn('tipe', ['pelunasan', 'bulanan']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('room', function ($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('month'))  $query->where('billing_month', $request->month);
        if ($request->filled('year'))   $query->where('billing_year', $request->year);
        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);

        $sortBy    = $request->get('sort', 'due_date');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $billings = $query->paginate(15)->withQueryString();

        if ($user->isSuperAdmin()) {
            $billingBase = Billing::withoutTempatKosScope()->whereIn('tipe', ['pelunasan', 'bulanan']);
        } else {
            $billingBase = Billing::whereIn('tipe', ['pelunasan', 'bulanan']);
        }

        $stats = [
            'total'   => (clone $billingBase)->count(),
            'unpaid'  => (clone $billingBase)->unpaid()->count(),
            'overdue' => (clone $billingBase)->overdue()->count(),
            'pending' => (clone $billingBase)->pending()->count(),
            'paid'    => (clone $billingBase)->paid()->count(),
        ];

        $usersQuery = User::where('role', 'user')
            ->whereHas('rents', fn($q) => $q->where('status', 'active'));

        if (!$user->isSuperAdmin()) {
            $usersQuery->where('tempat_kos_id', $user->tempat_kos_id);
        }

        $users = $usersQuery->orderBy('name')->get();

        $yearsQuery = $user->isSuperAdmin()
            ? Billing::withoutTempatKosScope()
            : Billing::query();

        $years = $yearsQuery->select('billing_year')
            ->distinct()
            ->orderBy('billing_year', 'desc')
            ->pluck('billing_year');

        return view('admin.billing.index', compact('billings', 'stats', 'users', 'years'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $activeRentsQuery = Rent::with(['user', 'room'])->where('status', 'active');

        if ($user->isSuperAdmin()) {
            $activeRentsQuery->withoutTempatKosScope();
        }

        $activeRents = $activeRentsQuery->get();

        return view('admin.billing.create', compact('activeRents'));
    }

    /**
     * Store billing.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rent_id'          => 'required|exists:rents,id',
            'billing_month'    => 'required|integer|between:1,12',
            'billing_year'     => 'required|integer|min:2024',
            'rent_amount'      => 'required|numeric|min:0',
            'electricity_cost' => 'nullable|numeric|min:0',
            'water_cost'       => 'nullable|numeric|min:0',
            'maintenance_cost' => 'nullable|numeric|min:0',
            'discount'         => 'nullable|numeric|min:0',
            'due_date'         => 'required|date|after_or_equal:today',
            'admin_notes'      => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $rent = Rent::with(['user', 'room'])->findOrFail($validated['rent_id']);

            $exists = Billing::where('rent_id', $rent->id)
                ->where('billing_year', $validated['billing_year'])
                ->where('billing_month', $validated['billing_month'])
                ->exists();

            if ($exists) {
                return back()->with('error', 'Tagihan untuk periode ini sudah ada!')->withInput();
            }

            $billing = new Billing();
            $billing->rent_id        = $rent->id;
            $billing->user_id        = $rent->user_id;
            $billing->room_id        = $rent->room_id;
            $billing->billing_month  = $validated['billing_month'];
            $billing->billing_year   = $validated['billing_year'];
            $billing->billing_period = Carbon::create($validated['billing_year'], $validated['billing_month'])->format('Y-m');

            $billing->rent_amount      = $validated['rent_amount'];
            $billing->electricity_cost = $validated['electricity_cost'] ?? 0;
            $billing->water_cost       = $validated['water_cost'] ?? 0;
            $billing->maintenance_cost = $validated['maintenance_cost'] ?? 0;
            $billing->other_costs      = 0;
            $billing->discount         = $validated['discount'] ?? 0;
            $billing->calculateTotal();

            $billing->due_date    = $validated['due_date'];
            $billing->admin_notes = $validated['admin_notes'];
            $billing->status      = 'unpaid';
            $billing->tipe        = 'bulanan';

            $billing->save();

            DB::commit();

            return redirect()->route('admin.billing.index')
                ->with('success', 'Tagihan berhasil dibuat untuk ' . $rent->user->name);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat tagihan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show billing detail.
     */
    public function show(Billing $billing)
    {
        $billing->load(['user', 'room', 'rent', 'payments.verifier', 'payments.disbursement']);
        return view('admin.billing.show', compact('billing'));
    }

    /**
     * Show edit form.
     */
    public function edit(Billing $billing)
    {
        if ($billing->status === 'paid') {
            return back()->with('error', 'Tagihan yang sudah lunas tidak dapat diedit');
        }

        $billing->load(['rent.user', 'rent.room']);
        return view('admin.billing.edit', compact('billing'));
    }

    /**
     * Update billing.
     */
    public function update(Request $request, Billing $billing)
    {
        if ($billing->status === 'paid') {
            return back()->with('error', 'Tagihan yang sudah lunas tidak dapat diedit');
        }

        $validated = $request->validate([
            'rent_amount'      => 'required|numeric|min:0',
            'electricity_cost' => 'nullable|numeric|min:0',
            'water_cost'       => 'nullable|numeric|min:0',
            'maintenance_cost' => 'nullable|numeric|min:0',
            'discount'         => 'nullable|numeric|min:0',
            'due_date'         => 'required|date',
            'admin_notes'      => 'nullable|string|max:1000',
        ]);

        try {
            $billing->rent_amount      = $validated['rent_amount'];
            $billing->electricity_cost = $validated['electricity_cost'] ?? 0;
            $billing->water_cost       = $validated['water_cost'] ?? 0;
            $billing->maintenance_cost = $validated['maintenance_cost'] ?? 0;
            $billing->discount         = $validated['discount'] ?? 0;
            $billing->calculateTotal();
            $billing->due_date         = $validated['due_date'];
            $billing->admin_notes      = $validated['admin_notes'];
            $billing->save();

            return redirect()->route('admin.billing.show', $billing)
                ->with('success', 'Tagihan berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupdate tagihan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Verifikasi pembayaran dari halaman show billing.
     *
     * Saat confirm → dana masuk holding Superadmin → kirim notifikasi ke superadmin.
     * Notifikasi HANYA untuk tipe pelunasan & bulanan (bukan booking/DP).
     */
    public function verifyPayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'action'           => 'required|in:confirm,reject',
            'rejection_reason' => 'required_if:action,reject|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            if ($validated['action'] === 'confirm') {
                $payment->verify(auth()->id());
                $payment->billing->markAsPaid();

                // Kirim notifikasi ke superadmin (hanya pelunasan & bulanan)
                $this->notifySuperAdmin($payment);

                $message = 'Pembayaran berhasil dikonfirmasi. Dana masuk ke platform dan menunggu pencairan ke admin kos.';
            } else {
                $payment->reject(auth()->id(), $validated['rejection_reason']);
                $payment->billing->update(['status' => 'unpaid']);

                $message = 'Pembayaran ditolak. User perlu upload ulang bukti pembayaran.';
            }

            DB::commit();

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Mark billing as paid (pembayaran tunai/cash oleh admin).
     * Dana masuk ke holding Superadmin → kirim notifikasi ke superadmin.
     */
    public function markAsPaid(Billing $billing)
    {
        if ($billing->status === 'paid') {
            return back()->with('error', 'Tagihan sudah lunas');
        }

        try {
            DB::beginTransaction();

            $payment = Payment::create([
                'tempat_kos_id'       => $billing->tempat_kos_id,
                'user_id'             => $billing->user_id,
                'billing_id'          => $billing->id,
                'amount'              => $billing->total_amount,
                'payment_method'      => 'cash',
                'status'              => 'confirmed',
                'payment_date'        => now(),
                'notes'               => 'Pembayaran tunai - dicatat oleh admin',
                'verified_by'         => auth()->id(),
                'verified_at'         => now(),
                'disbursement_status' => 'holding',
                'disbursement_id'     => null,
            ]);

            $billing->markAsPaid();

            // Kirim notifikasi ke superadmin (hanya pelunasan & bulanan)
            $this->notifySuperAdmin($payment);

            DB::commit();

            return back()->with('success', 'Tagihan berhasil ditandai lunas. Dana tercatat di platform menunggu pencairan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menandai tagihan: ' . $e->getMessage());
        }
    }

    /**
     * Konfirmasi pembayaran dari user (status pending).
     * Dana masuk ke holding Superadmin → kirim notifikasi ke superadmin.
     */
    public function confirmPayment(Billing $billing)
    {
        if ($billing->status !== 'pending') {
            return back()->with('error', 'Hanya tagihan dengan status "Menunggu Verifikasi" yang dapat dikonfirmasi.');
        }

        $latestPayment = $billing->latestPayment;

        if (!$latestPayment) {
            return back()->with('error', 'Tidak ada data pembayaran untuk tagihan ini.');
        }

        try {
            DB::beginTransaction();

            $latestPayment->verify(auth()->id());
            $billing->markAsPaid();

            // Kirim notifikasi ke superadmin (hanya pelunasan & bulanan)
            $this->notifySuperAdmin($latestPayment);

            DB::commit();

            return redirect()->route('admin.billing.index')
                ->with('success', "Pembayaran dari {$billing->user->name} berhasil dikonfirmasi sebagai LUNAS. Dana masuk ke platform.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Tolak pembayaran dari user (status pending).
     */
    public function rejectPayment(Billing $billing)
    {
        if ($billing->status !== 'pending') {
            return back()->with('error', 'Hanya tagihan dengan status "Menunggu Verifikasi" yang dapat ditolak.');
        }

        $latestPayment = $billing->latestPayment;

        if (!$latestPayment) {
            return back()->with('error', 'Tidak ada data pembayaran untuk tagihan ini.');
        }

        try {
            DB::beginTransaction();

            $latestPayment->reject(auth()->id(), 'Bukti pembayaran tidak valid atau tidak sesuai.');
            $billing->update(['status' => 'unpaid']);

            DB::commit();

            return redirect()->route('admin.billing.index')
                ->with('success', "Pembayaran dari {$billing->user->name} ditolak. User harus upload bukti pembayaran baru.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Kirim notifikasi ke semua superadmin ketika admin konfirmasi pembayaran.
     *
     * HANYA untuk tipe billing: pelunasan & bulanan.
     * TIDAK untuk DP booking (karena itu alur booking, bukan billing).
     */
    protected function notifySuperAdmin(Payment $payment): void
    {
        // Load relasi billing jika belum
        if (!$payment->relationLoaded('billing')) {
            $payment->load('billing.user', 'billing.room.kosInfo');
        }

        $billing = $payment->billing;

        // Skip jika tidak ada billing atau tipe-nya adalah DP
        if (!$billing || $billing->tipe === 'dp') {
            return;
        }

        // Tentukan label tipe pembayaran
        $tipeLabel = match($billing->tipe) {
            'pelunasan' => 'Pelunasan Booking',
            'bulanan'   => 'Tagihan Bulanan',
            'tahunan'   => 'Tagihan Tahunan',
            default     => 'Pembayaran',
        };

        $userName  = $billing->user?->name ?? 'User';
        $roomNum   = $billing->room?->room_number ?? '-';
        $kosName   = $billing->room?->kosInfo?->nama_kos ?? '-';
        $amount    = 'Rp ' . number_format($payment->amount, 0, ',', '.');

        // Kirim ke semua superadmin
        $superAdmins = User::where('role', 'super_admin')->get();

        foreach ($superAdmins as $superAdmin) {
            Notification::create([
                'user_id'       => $superAdmin->id,
                'type'          => 'payment',
                'title'         => "Dana Masuk: {$tipeLabel}",
                'message'       => "{$userName} • Kamar {$roomNum} ({$kosName}) • {$amount} — Dana masuk holding, siap dicairkan.",
                'payment_id'    => $payment->id,
                'billing_id'    => $billing->id,
                'tempat_kos_id' => $payment->tempat_kos_id,
                'status'        => 'unread',
            ]);
        }
    }

    /**
     * Bulk generate billings.
     */
    public function bulkGenerate(Request $request)
    {
        $validated = $request->validate([
            'billing_month' => 'required|integer|between:1,12',
            'billing_year'  => 'required|integer|min:2024',
            'due_date'      => 'required|date|after_or_equal:today',
        ]);

        try {
            DB::beginTransaction();

            /** @var \App\Models\User $user */
            $user = auth()->user();

            $activeRentsQuery = Rent::with(['user', 'room'])->where('status', 'active');

            if ($user->isSuperAdmin()) {
                $activeRentsQuery->withoutTempatKosScope();
            }

            $activeRents = $activeRentsQuery->get();
            $created     = 0;
            $skipped     = 0;

            foreach ($activeRents as $rent) {
                $exists = Billing::where('rent_id', $rent->id)
                    ->where('billing_year', $validated['billing_year'])
                    ->where('billing_month', $validated['billing_month'])
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                $billing                   = new Billing();
                $billing->rent_id          = $rent->id;
                $billing->user_id          = $rent->user_id;
                $billing->room_id          = $rent->room_id;
                $billing->billing_month    = $validated['billing_month'];
                $billing->billing_year     = $validated['billing_year'];
                $billing->billing_period   = Carbon::create($validated['billing_year'], $validated['billing_month'])->format('Y-m');
                $billing->rent_amount      = $rent->room->price;
                $billing->electricity_cost = 0;
                $billing->water_cost       = 0;
                $billing->maintenance_cost = 0;
                $billing->other_costs      = 0;
                $billing->discount         = 0;
                $billing->calculateTotal();
                $billing->due_date         = $validated['due_date'];
                $billing->status           = 'unpaid';
                $billing->tipe             = 'bulanan';
                $billing->save();

                $created++;
            }

            DB::commit();

            return back()->with('success', "Berhasil membuat {$created} tagihan. {$skipped} dilewati (sudah ada).");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat tagihan massal: ' . $e->getMessage());
        }
    }
}