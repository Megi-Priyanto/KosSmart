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
        // Pastikan rent milik user yang login
        abort_if($rent->user_id !== Auth::id(), 403);

        // Cegah request tidak valid
        if ($rent->status !== 'active') {
            return back()->with('error', 'Checkout tidak dapat diajukan.');
        }

        DB::transaction(function () use ($rent) {
            $rent->update([
                'status' => 'checkout_requested',
            ]);

            // Notifikasi ke ADMIN
            Notification::create([
                'type'    => 'checkout',
                'title'   => 'Permintaan Checkout',
                'message' => 'User mengajukan permintaan checkout kamar.',
                'user_id' => 1, // ADMIN ID (atau loop semua admin)
                'rent_id' => $rent->id,
                'room_id' => $rent->room_id,
                'status'  => 'pending',
            ]);
        });

        return back()->with('success', 'Permintaan checkout berhasil dikirim dan menunggu persetujuan admin.');
    }
}
