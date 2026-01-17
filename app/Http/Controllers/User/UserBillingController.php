<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserBillingController extends Controller
{
    /**
     * Display user's billings
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        // =========================
        // QUERY UTAMA BILLINGS
        // =========================
        $query = Billing::with(['room', 'latestPayment'])
            ->where('user_id', $userId);

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'unpaid') {
                // unpaid mencakup unpaid, pending, overdue
                $query->whereIn('status', ['unpaid', 'pending', 'overdue']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('billing_year', $request->year);
        }

        // Sorting
        $sortBy    = $request->get('sort', 'due_date');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // =========================
        // GET BILLINGS (PAGINATED)
        // =========================
        $billings = $query->paginate(12)->withQueryString();

        // =========================
        // AUTO UPDATE OVERDUE
        // =========================
        foreach ($billings as $billing) {
            if (now()->greaterThan($billing->due_date) && $billing->status !== 'paid') {

                if ($billing->status !== 'overdue') {
                    $billing->update(['status' => 'overdue']);
                }

                $billing->is_overdue = true;
            } else {
                $billing->is_overdue = false;
            }
        }

        // =========================
        // CURRENT / ACTIVE BILLING
        // =========================
        $currentBilling = Billing::where('user_id', $userId)
            ->whereIn('status', ['unpaid', 'pending', 'overdue'])
            ->orderBy('due_date', 'asc')
            ->first();

        // =========================
        // STATISTICS
        // =========================
        $stats = [
            'total'        => Billing::where('user_id', $userId)->count(),
            'unpaid'       => Billing::where('user_id', $userId)->unpaid()->count(),
            'overdue'      => Billing::where('user_id', $userId)->overdue()->count(),
            'pending'      => Billing::where('user_id', $userId)->pending()->count(),
            'paid'         => Billing::where('user_id', $userId)->paid()->count(),
            'total_unpaid' => Billing::where('user_id', $userId)
                ->whereIn('status', ['unpaid', 'overdue'])
                ->sum('total_amount'),
        ];

        // =========================
        // YEARS FOR FILTER
        // =========================
        $years = Billing::where('user_id', $userId)
            ->select('billing_year')
            ->distinct()
            ->orderBy('billing_year', 'desc')
            ->pluck('billing_year');

        // =========================
        // RETURN VIEW
        // =========================
        return view('user.billing.index', compact(
            'billings',
            'currentBilling',
            'stats',
            'years'
        ));
    }

    /**
     * Display the specified billing
     */
    public function show(Billing $billing)
    {
        // Authorization
        if ($billing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $billing->load(['room', 'rent', 'payments']);

        return view('user.billing.show', compact('billing'));
    }

    /**
     * Show payment form
     */
    public function paymentForm(Billing $billing)
    {
        // Authorization
        if ($billing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if already paid
        if ($billing->status === 'paid') {
            return redirect()->route('user.billing.show', $billing)
                ->with('error', 'Tagihan ini sudah lunas');
        }

        // Check if already has pending payment
        $pendingPayment = $billing->payments()
            ->where('status', 'pending')
            ->latest()
            ->first();

        // Payment methods configuration (sama seperti booking)
        $paymentMethods = [
            'manual_transfer' => [
                'label' => 'Transfer Bank',
                'options' => [
                    'bca' => [
                        'name' => 'BCA',
                        'account' => '1234567890',
                        'holder' => 'KosSmart Residence'
                    ],
                    'bni' => [
                        'name' => 'BNI',
                        'account' => '0987654321',
                        'holder' => 'KosSmart Residence'
                    ],
                    'mandiri' => [
                        'name' => 'Mandiri',
                        'account' => '1122334455',
                        'holder' => 'KosSmart Residence'
                    ],
                ]
            ],
            'e_wallet' => [
                'label' => 'E-Wallet',
                'options' => [
                    'dana' => [
                        'name' => 'DANA',
                        'account' => '081234567890',
                        'holder' => 'KosSmart Residence'
                    ],
                    'ovo' => [
                        'name' => 'OVO',
                        'account' => '081234567890',
                        'holder' => 'KosSmart Residence'
                    ],
                    'gopay' => [
                        'name' => 'GoPay',
                        'account' => '081234567890',
                        'holder' => 'KosSmart Residence'
                    ],
                ]
            ],
            'qris' => [
                'label' => 'QRIS',
                'qr_image' => 'qris/sample-qr.png'
            ]
        ];

        return view('user.billing.payment', compact('billing', 'pendingPayment', 'paymentMethods'));
    }

    /**
     * Store payment proof
     */
    public function submitPayment(Request $request, Billing $billing)
    {
        if ($billing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($billing->status === 'paid') {
            return back()->with('error', 'Tagihan ini sudah lunas');
        }

        $validated = $request->validate([
            'payment_type' => 'required|in:manual_transfer,e_wallet,qris',
            'payment_sub_method' => 'required_if:payment_type,manual_transfer,e_wallet|string',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'payment_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $proofPath = $request->file('payment_proof')
                ->store('payment-proofs', 'public');

            // SIMPAN DENGAN FORMAT BARU
            Payment::create([
                'tempat_kos_id' => $billing->tempat_kos_id,
                'user_id' => auth()->id(),
                'billing_id' => $billing->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_type'],
                'payment_type' => $validated['payment_type'],
                'payment_sub_method' => $validated['payment_sub_method'] ?? 'qris',
                'payment_proof' => $proofPath,
                'payment_date' => $validated['payment_date'],
                'notes' => $validated['notes'],
                'status' => 'pending',
            ]);

            $billing->markAsPending();

            DB::commit();

            return redirect()->route('user.billing.show', $billing)
                ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($proofPath)) {
                Storage::disk('public')->delete($proofPath);
            }

            return back()->with('error', 'Gagal mengirim pembayaran: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Payment history
     */
    public function paymentHistory()
    {
        $payments = Payment::with(['billing.room'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.billing.payment-history', compact('payments'));
    }
}
