<?php
// app/Http/Controllers/User/UserBillingController.php

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
        $query = Billing::with(['room', 'latestPayment'])
            ->where('user_id', auth()->id());

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'unpaid') {
                // tampilkan unpaid, pending, overdue
                $query->whereIn('status', ['unpaid', 'pending', 'overdue']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->where('billing_year', $request->year);
        }

        // Sort
        $sortBy = $request->get('sort', 'due_date');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // GET BILLINGS
        $billings = $query->paginate(12)->withQueryString();

        // Auto-update overdue
        foreach ($billings as $billing) {
            if (now()->greaterThan($billing->due_date) && $billing->status !== 'paid') {
            
                if ($billing->status !== 'overdue') {
                    $billing->status = 'overdue';
                    $billing->save();
                }
            
                $billing->is_overdue = true;
            } else {
                $billing->is_overdue = false;
            }
        }

        // Statistics
        $stats = [
            'total' => Billing::where('user_id', auth()->id())->count(),
            'unpaid' => Billing::where('user_id', auth()->id())->unpaid()->count(),
            'overdue' => Billing::where('user_id', auth()->id())->overdue()->count(),
            'pending' => Billing::where('user_id', auth()->id())->pending()->count(),
            'paid' => Billing::where('user_id', auth()->id())->paid()->count(),
            'total_unpaid' => Billing::where('user_id', auth()->id())
                ->whereIn('status', ['unpaid', 'overdue'])
                ->sum('total_amount'),
        ];

        // Years for filter
        $years = Billing::where('user_id', auth()->id())
            ->select('billing_year')
            ->distinct()
            ->orderBy('billing_year', 'desc')
            ->pluck('billing_year');

        return view('user.billing.index', compact('billings', 'stats', 'years'));
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

        return view('user.billing.payment', compact('billing', 'pendingPayment'));
    }

    /**
     * Store payment proof
     */
    public function submitPayment(Request $request, Billing $billing)
    {
        // Authorization
        if ($billing->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if already paid
        if ($billing->status === 'paid') {
            return back()->with('error', 'Tagihan ini sudah lunas');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:transfer,cash,e-wallet',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'payment_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Upload proof
            $proofPath = $request->file('payment_proof')
                ->store('payment-proofs', 'public');

            // Create payment
            Payment::create([
                'user_id' => auth()->id(),
                'billing_id' => $billing->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_proof' => $proofPath,
                'payment_date' => $validated['payment_date'],
                'notes' => $validated['notes'],
                'status' => 'pending',
            ]);

            // Update billing status to pending
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