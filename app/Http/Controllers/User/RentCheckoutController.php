<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RentCheckoutController extends Controller
{
    public function requestCheckout(Rent $rent)
    {
        Log::info('Checkout request started', [
            'rent_id' => $rent->id,
            'user_id' => Auth::id(),
            'rent_status' => $rent->status
        ]);

        // Pastikan rent milik user login
        if ($rent->user_id !== Auth::id()) {
            Log::warning('Unauthorized checkout attempt', [
                'rent_user_id' => $rent->user_id,
                'auth_user_id' => Auth::id()
            ]);
            abort(403);
        }

        // Status harus aktif
        if ($rent->status !== 'active') {
            Log::info('Checkout rejected - status not active', ['status' => $rent->status]);
            return back()->with('error', 'Checkout tidak dapat diajukan. Status sewa: ' . $rent->status);
        }

        // CEK TAGIHAN BELUM LUNAS
        $hasUnpaidBill = $rent->billings()
            ->where('status', '!=', 'paid')
            ->exists();

        if ($hasUnpaidBill) {
            Log::info('Checkout rejected - unpaid bills');
            return back()->with(
                'error',
                'Anda harus melunasi seluruh tagihan sebelum mengajukan checkout.'
            );
        }

        try {
            DB::transaction(function () use ($rent) {
                Log::info('Updating rent status to checkout_requested');

                // Update status rent
                $rent->update([
                    'status' => 'checkout_requested',
                ]);

                // ===== PERBAIKAN: Tambahkan user_id =====
                // Cari admin kos untuk tempat_kos_id ini
                $adminKos = \App\Models\User::where('tempat_kos_id', $rent->tempat_kos_id)
                    ->where('role', 'admin_kos')
                    ->first();

                // Jika tidak ada admin_kos, gunakan super_admin
                if (!$adminKos) {
                    $adminKos = \App\Models\User::where('role', 'super_admin')->first();
                }

                Notification::create([
                    'type'           => 'checkout',
                    'title'          => 'Permintaan Checkout',
                    'message'        => "User {$rent->user->name} mengajukan permintaan checkout dari Kamar {$rent->room->room_number}.",
                    'user_id'        => $adminKos ? $adminKos->id : 1,
                    'tempat_kos_id'  => $rent->tempat_kos_id,
                    'rent_id'        => $rent->id,
                    'room_id'        => $rent->room_id,
                    'status'         => 'pending',
                ]);

                Log::info('Checkout request completed successfully', ['rent_id' => $rent->id]);
            });

            return back()->with(
                'success',
                'Permintaan checkout berhasil dikirim dan menunggu persetujuan admin.'
            );

        } catch (\Exception $e) {
            Log::error('Checkout request failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function pending()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $rent = $user->rents()
            ->where('status', 'checkout_requested')
            ->with('room')
            ->latest()
            ->firstOrFail();

        return view('user.checkout.pending', compact('rent'));
    }
}