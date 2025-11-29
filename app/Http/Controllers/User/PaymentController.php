<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Tampilkan riwayat pembayaran
     */

    public function index()
    {
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $payments = $user->payments()
            ->with('billing')
            ->latest()
            ->paginate(10);
        
        return view('user.payments.index', compact('payments'));
    }
    
    /**
     * Tampilkan daftar tagihan
     */
    public function billings()
    {
        $/** @var \App\Models\User $user */
        $user = Auth::user();

        $billings = $user->billings()
            ->with('rent.room')
            ->orderBy('due_date', 'desc')
            ->paginate(10);
        
        return view('user.billings.index', compact('billings'));
    }
    
    /**
     * Form pembayaran tagihan
     */
    public function pay(Billing $billing)
    {
        // Pastikan tagihan milik user yang login
        if ($billing->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }
        
        if ($billing->status === 'paid') {
            return redirect()
                ->route('user.billings')
                ->with('info', 'Tagihan ini sudah dibayar.');
        }
        
        return view('user.payments.pay', compact('billing'));
    }
    
    /**
     * Proses pembayaran
     */
    public function processPayment(Request $request, Billing $billing)
    {
        $request->validate([
            'payment_method' => 'required|in:transfer,cash',
            'payment_proof' => 'required_if:payment_method,transfer|image|max:2048',
        ], [
            'payment_method.required' => 'Metode pembayaran wajib dipilih',
            'payment_proof.required_if' => 'Bukti transfer wajib diunggah',
            'payment_proof.image' => 'File harus berupa gambar',
        ]);
        
        if ($billing->user_id !== Auth::id()) {
            abort(403);
        }
        
        DB::beginTransaction();
        try {
            $paymentProofPath = null;
            
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')
                    ->store('payments', 'public');
            }
            
            Payment::create([
                'user_id' => Auth::id(),
                'billing_id' => $billing->id,
                'amount' => $billing->total_amount,
                'payment_method' => $request->payment_method,
                'payment_proof' => $paymentProofPath,
                'status' => 'pending', // Menunggu konfirmasi admin
                'payment_date' => now(),
                'notes' => $request->notes,
            ]);
            
            DB::commit();
            
            return redirect()
                ->route('user.billings')
                ->with('success', 'Pembayaran berhasil diajukan. Menunggu konfirmasi admin.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }
}