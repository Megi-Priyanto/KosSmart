<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billing;
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
     * Display billings
     * 
     * Global Scope otomatis filter berdasarkan tempat_kos_id
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Query builder
        if ($user->isSuperAdmin()) {
            // Super Admin: Lihat semua billing
            $query = Billing::withoutTempatKosScope()
                ->with(['user', 'room', 'latestPayment'])
                ->whereIn('tipe', ['pelunasan', 'bulanan']);
        } else {
            // Admin: Hanya billing di kos miliknya (auto-filtered)
            $query = Billing::with(['user', 'room', 'latestPayment'])
                ->whereIn('tipe', ['pelunasan', 'bulanan']);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('room', function ($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->where('billing_month', $request->month);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('billing_year', $request->year);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Sort
        $sortBy = $request->get('sort', 'due_date');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $billings = $query->paginate(15)->withQueryString();

        // Statistics (otomatis filtered)
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

        // Data for filters (hanya user di kos yang sama)
        $usersQuery = User::where('role', 'user')
            ->whereHas('rents', function ($q) {
                $q->where('status', 'active');
            });

        if (!$user->isSuperAdmin()) {
            $usersQuery->where('tempat_kos_id', $user->tempat_kos_id);
        }

        $users = $usersQuery->orderBy('name')->get();

        // Years (otomatis filtered)
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
     * Show create form
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Get active rents (otomatis filtered)
        $activeRentsQuery = Rent::with(['user', 'room'])
            ->where('status', 'active');

        if (!$user->isSuperAdmin()) {
            // Sudah ter-filter via Global Scope
        } else {
            $activeRentsQuery->withoutTempatKosScope();
        }

        $activeRents = $activeRentsQuery->get();

        return view('admin.billing.create', compact('activeRents'));
    }

    /**
     * Store billing
     * 
     * tempat_kos_id otomatis terisi via trait
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rent_id' => 'required|exists:rents,id',
            'billing_month' => 'required|integer|between:1,12',
            'billing_year' => 'required|integer|min:2024',
            'rent_amount' => 'required|numeric|min:0',
            'electricity_cost' => 'nullable|numeric|min:0',
            'water_cost' => 'nullable|numeric|min:0',
            'maintenance_cost' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date|after_or_equal:today',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $rent = Rent::with(['user', 'room'])->findOrFail($validated['rent_id']);

            // Check duplicate
            $exists = Billing::where('rent_id', $rent->id)
                ->where('billing_year', $validated['billing_year'])
                ->where('billing_month', $validated['billing_month'])
                ->exists();

            if ($exists) {
                return back()->with('error', 'Tagihan untuk periode ini sudah ada!')->withInput();
            }

            // Create billing (tempat_kos_id auto-filled via trait)
            $billing = new Billing();
            $billing->rent_id = $rent->id;
            $billing->user_id = $rent->user_id;
            $billing->room_id = $rent->room_id;
            $billing->billing_month = $validated['billing_month'];
            $billing->billing_year = $validated['billing_year'];
            $billing->billing_period = Carbon::create($validated['billing_year'], $validated['billing_month'])->format('Y-m');

            $billing->rent_amount = $validated['rent_amount'];
            $billing->electricity_cost = $validated['electricity_cost'] ?? 0;
            $billing->water_cost = $validated['water_cost'] ?? 0;
            $billing->maintenance_cost = $validated['maintenance_cost'] ?? 0;
            $billing->other_costs = 0;
            $billing->discount = $validated['discount'] ?? 0;

            $billing->calculateTotal();

            $billing->due_date = $validated['due_date'];
            $billing->admin_notes = $validated['admin_notes'];
            $billing->status = 'unpaid';
            $billing->tipe = 'bulanan';
                    
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
     * Show billing detail
     * 
     * Model binding otomatis ter-filter
     */
    public function show(Billing $billing)
    {
        $billing->load(['user', 'room', 'rent', 'payments.verifier']);
        return view('admin.billing.show', compact('billing'));
    }

    /**
     * Show edit form
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
     * Update billing
     */
    public function update(Request $request, Billing $billing)
    {
        if ($billing->status === 'paid') {
            return back()->with('error', 'Tagihan yang sudah lunas tidak dapat diedit');
        }

        $validated = $request->validate([
            'rent_amount' => 'required|numeric|min:0',
            'electricity_cost' => 'nullable|numeric|min:0',
            'water_cost' => 'nullable|numeric|min:0',
            'maintenance_cost' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $billing->rent_amount = $validated['rent_amount'];
            $billing->electricity_cost = $validated['electricity_cost'] ?? 0;
            $billing->water_cost = $validated['water_cost'] ?? 0;
            $billing->maintenance_cost = $validated['maintenance_cost'] ?? 0;
            $billing->discount = $validated['discount'] ?? 0;
            $billing->due_date = $validated['due_date'];
            $billing->admin_notes = $validated['admin_notes'];

            $billing->calculateTotal();

            if ($billing->status !== 'paid') {
                if ($billing->due_date->isFuture()) {
                    $billing->status = 'unpaid';
                } elseif ($billing->due_date->isPast()) {
                    $billing->status = 'overdue';
                }
            }

            $billing->save();

            DB::commit();

            return redirect()->route('admin.billing.show', $billing)
                ->with('success', 'Tagihan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui tagihan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete billing
     */
    public function destroy(Billing $billing)
    {
        if ($billing->status === 'paid') {
            return back()->with('error', 'Tagihan yang sudah lunas tidak dapat dihapus');
        }

        if ($billing->payments()->exists()) {
            return back()->with('error', 'Tagihan dengan riwayat pembayaran tidak dapat dihapus');
        }

        try {
            $billingPeriod = $billing->formatted_period;
            $userName = $billing->user->name;

            $billing->delete();

            return redirect()->route('admin.billing.index')
                ->with('success', "Tagihan {$billingPeriod} untuk {$userName} berhasil dihapus");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus tagihan: ' . $e->getMessage());
        }
    }

    /**
     * Verify payment
     */
    public function verifyPayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'action' => 'required|in:confirm,reject',
            'rejection_reason' => 'required_if:action,reject|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            if ($validated['action'] === 'confirm') {
                $payment->update([
                    'status' => 'confirmed',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);

                $payment->billing->markAsPaid();

                $message = 'Pembayaran berhasil dikonfirmasi';
            } else {
                $payment->update([
                    'status' => 'rejected',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'rejection_reason' => $validated['rejection_reason'],
                ]);

                $payment->billing->update(['status' => 'unpaid']);

                $message = 'Pembayaran ditolak';
            }

            DB::commit();

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(Billing $billing)
    {
        if ($billing->status === 'paid') {
            return back()->with('error', 'Tagihan sudah lunas');
        }

        try {
            DB::beginTransaction();

            Payment::create([
                'user_id' => $billing->user_id,
                'billing_id' => $billing->id,
                'amount' => $billing->total_amount,
                'payment_method' => 'cash',
                'status' => 'confirmed',
                'payment_date' => now(),
                'notes' => 'Pembayaran tunai - dicatat oleh admin',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $billing->markAsPaid();

            DB::commit();

            return back()->with('success', 'Tagihan berhasil ditandai lunas');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menandai tagihan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk generate billings
     * 
     * tempat_kos_id otomatis terisi untuk setiap billing
     */
    public function bulkGenerate(Request $request)
    {
        $validated = $request->validate([
            'billing_month' => 'required|integer|between:1,12',
            'billing_year' => 'required|integer|min:2024',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        try {
            DB::beginTransaction();

            /** @var \App\Models\User $user */
            $user = auth()->user();

            // Get active rents (otomatis filtered)
            $activeRentsQuery = Rent::with(['user', 'room'])
                ->where('status', 'active');

            if (!$user->isSuperAdmin()) {
                // Sudah ter-filter via Global Scope
            } else {
                $activeRentsQuery->withoutTempatKosScope();
            }

            $activeRents = $activeRentsQuery->get();

            $created = 0;
            $skipped = 0;

            foreach ($activeRents as $rent) {
                // Check if already exists
                $exists = Billing::where('rent_id', $rent->id)
                    ->where('billing_year', $validated['billing_year'])
                    ->where('billing_month', $validated['billing_month'])
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                // Create billing (tempat_kos_id auto-filled)
                $billing = new Billing();
                $billing->rent_id = $rent->id;
                $billing->user_id = $rent->user_id;
                $billing->room_id = $rent->room_id;
                $billing->billing_month = $validated['billing_month'];
                $billing->billing_year = $validated['billing_year'];
                $billing->billing_period = Carbon::create($validated['billing_year'], $validated['billing_month'])->format('Y-m');

                $billing->rent_amount = $rent->room->price;
                $billing->electricity_cost = 0;
                $billing->water_cost = 0;
                $billing->maintenance_cost = 0;
                $billing->other_costs = 0;
                $billing->discount = 0;

                $billing->calculateTotal();

                $billing->due_date = $validated['due_date'];
                $billing->status = 'unpaid';
                $billing->tipe = 'bulanan';

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