<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RentCheckoutController extends Controller
{
    public function requestCheckout(Rent $rent)
    {
        // Pastikan rent milik user login
        abort_if($rent->user_id !== Auth::id(), 403);

        // Status harus aktif
        if ($rent->status !== 'active') {
            return back()->with('error', 'Checkout tidak dapat diajukan.');
        }

        // CEK TAGIHAN BELUM LUNAS
        $hasUnpaidBill = $rent->billings()
            ->where('status', '!=', 'paid')
            ->exists();

        if ($hasUnpaidBill) {
            return back()->with(
                'error',
                'Anda harus melunasi seluruh tagihan sebelum mengajukan checkout.'
            );
        }

        DB::transaction(function () use ($rent) {

            $rent->update([
                'status' => 'checkout_requested',
            ]);

            Notification::create([
                'type'    => 'checkout',
                'title'   => 'Permintaan Checkout',
                'message' => 'User mengajukan permintaan checkout kamar.',
                'user_id' => 1, // ADMIN
                'rent_id' => $rent->id,
                'room_id' => $rent->room_id,
                'status'  => 'pending',
            ]);
        });

        return back()->with(
            'success',
            'Permintaan checkout berhasil dikirim dan menunggu persetujuan admin.'
        );
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
