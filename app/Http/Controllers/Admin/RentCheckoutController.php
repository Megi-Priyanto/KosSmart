<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RentCheckoutController extends Controller
{
    public function checkout(Rent $rent)
    {
        // Cegah double checkout
        if ($rent->status === 'completed') {
            return back()->with('error', 'Sewa ini sudah selesai.');
        }

        // OPTIONAL: Cegah checkout jika masih ada tagihan
        if ($rent->billings()->where('status', 'unpaid')->exists()) {
            return back()->with('error', 'Masih ada tagihan yang belum dibayar.');
        }

        DB::transaction(function () use ($rent) {

            // 1. Selesaikan sewa (baik dari admin langsung atau approve user)
            $rent->update([
                'status'   => 'completed',
                'end_date' => now(),
            ]);

            // 2. KAMAR WAJIB JADI MAINTENANCE
            $rent->room->update([
                'status' => 'maintenance',
            ]);

            // 3. PERBAIKAN: Update notifikasi checkout jadi 'read'
            Notification::where('rent_id', $rent->id)
                ->where('type', 'checkout')
                ->where('status', 'pending')
                ->update(['status' => 'read']);

            // 4. PERBAIKAN: Kirim notifikasi ke user bahwa checkout disetujui
            Notification::create([
                'type'    => 'checkout_approved',
                'title'   => 'Checkout Disetujui',
                'message' => 'Permintaan checkout Anda telah disetujui. Terima kasih telah menggunakan layanan kami.',
                'user_id' => $rent->user_id,
                'rent_id' => $rent->id,
                'room_id' => $rent->room_id,
                'status'  => 'pending',
            ]);
        });

        return redirect()
            ->route('admin.rooms.show', $rent->room_id)
            ->with('success', 'Checkout berhasil. Kamar masuk status maintenance.');
    }
}