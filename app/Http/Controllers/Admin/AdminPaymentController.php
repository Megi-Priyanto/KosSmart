<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminBilling;
use App\Models\Notification;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminPaymentController extends Controller
{
    /**
     * Display admin's billings
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $billings = AdminBilling::where('tempat_kos_id', $user->tempat_kos_id)
            ->where('admin_id', $user->id)
            ->with('tempatKos')
            ->latest()
            ->paginate(15);

        // Statistics
        $stats = [
            'total' => AdminBilling::where('admin_id', $user->id)->count(),
            'unpaid' => AdminBilling::where('admin_id', $user->id)->unpaid()->count(),
            'overdue' => AdminBilling::where('admin_id', $user->id)->overdue()->count(),
            'paid' => AdminBilling::where('admin_id', $user->id)->paid()->count(),
        ];

        return view('admin.payments.index', compact('billings', 'stats'));
    }

    /**
     * Show payment detail
     */
    public function show(AdminBilling $billing)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($billing->admin_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }

        // ✅ Ambil payment methods yang aktif
        $paymentMethods = PaymentMethod::active()->get()->groupBy('type');

        return view('admin.payments.show', compact('billing', 'paymentMethods'));
    }

    /**
     * Process payment
     */
    public function pay(Request $request, AdminBilling $billing)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($billing->admin_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }

        if ($billing->status === 'paid') {
            return back()->with('error', 'Tagihan sudah dibayar.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|exists:payment_methods,id',
            'payment_proof' => 'required|image|max:2048|mimes:jpg,jpeg,png',
            'payment_notes' => 'nullable|string|max:500',
        ], [
            'payment_method.required' => 'Metode pembayaran wajib dipilih',
            'payment_proof.required' => 'Bukti pembayaran wajib diunggah',
            'payment_proof.image' => 'File harus berupa gambar',
            'payment_proof.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('payment_proof');
            $filename = 'payment_' . $billing->id . '_' . time() . '.' . $file->extension();
            $path = $file->storeAs('payment_proofs', $filename, 'public');

            // ✅ Ambil nama metode pembayaran
            $paymentMethod = PaymentMethod::find($validated['payment_method']);

            $billing->update([
                'status' => 'pending',
                'payment_proof' => $path,
                'payment_method' => $paymentMethod->name, // Simpan nama metode
                'paid_at' => now(),
                'payment_notes' => $validated['payment_notes'] ?? null,
            ]);

            $superAdmins = User::where('role', 'super_admin')->get();

            foreach ($superAdmins as $superAdmin) {
                Notification::create([
                    'type' => 'payment',
                    'title' => 'Pembayaran Tagihan Baru',
                    'message' => "Admin {$user->name} dari {$billing->tempatKos->nama_kos} telah melakukan pembayaran tagihan periode " . date('F Y', strtotime($billing->billing_period . '-01')) . " sebesar Rp " . number_format($billing->amount, 0, ',', '.') . " via {$paymentMethod->name}",
                    'user_id' => $superAdmin->id,
                    'admin_billing_id' => $billing->id,
                    'status' => 'unread',
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.payments.show', $billing)
                ->with('success', 'Pembayaran berhasil dikirim! Menunggu verifikasi Super Admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}