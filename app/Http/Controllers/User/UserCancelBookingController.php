<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use App\Models\CancelBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class UserCancelBookingController extends Controller
{
    /**
     * Store cancel request
     */
    public function store(Request $request, Rent $rent)
    {
        // Validasi ownership
        if ($rent->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya booking pending yang bisa di-cancel
        if ($rent->status !== 'pending') {
            return back()->with('error', 'Hanya booking dengan status "Menunggu Konfirmasi" yang dapat dibatalkan.');
        }

        // Cek jika sudah ada cancel request
        if ($rent->hasPendingCancel()) {
            return back()->with('error', 'Anda sudah mengajukan pembatalan sebelumnya.');
        }

        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:200',
            'cancel_reason' => 'nullable|string|max:1000',
        ], [
            'bank_name.required' => 'Nama bank wajib diisi',
            'account_number.required' => 'Nomor rekening wajib diisi',
            'account_holder_name.required' => 'Nama pemilik rekening wajib diisi',
        ]);

        try {

            $admin = \App\Models\User::where('tempat_kos_id', $rent->tempat_kos_id)
                ->where('role', 'admin')
                ->first();

            DB::beginTransaction();

            $cancel = CancelBooking::create([
                'tempat_kos_id' => $rent->tempat_kos_id,
                'rent_id' => $rent->id,
                'user_id' => Auth::id(),
                'bank_name' => $validated['bank_name'],
                'account_number' => $validated['account_number'],
                'account_holder_name' => $validated['account_holder_name'],
                'cancel_reason' => $validated['cancel_reason'],
                'status' => 'pending',
            ]);

            // Biarkan status tetap 'pending' sampai admin memproses
            // Admin yang akan mengupdate status menjadi 'cancelled' saat approve
            
            // HAPUS BARIS INI:
            // $rent->update(['status' => 'cancel_booking']);

            // TAMBAHAN: Buat notifikasi untuk admin
            if ($admin) {
                Notification::create([
                    'tempat_kos_id' => $rent->tempat_kos_id,
                    'user_id'       => $admin->id,
                    'rent_id'       => $rent->id,
                    'room_id'       => $rent->room_id,
                    'type'          => 'cancel_booking',
                    'title'         => 'Permintaan Pembatalan Booking',
                    'message'       => 'User ' . Auth::user()->name .
                        ' mengajukan pembatalan booking kamar ' .
                        $rent->room->room_number,
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            return redirect()->route('user.dashboard')
                ->with('success', 'Permintaan pembatalan booking berhasil diajukan. Admin akan memproses pengembalian dana Anda.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengajukan pembatalan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show cancel status
     */
    public function status()
    {
        $cancelBooking = CancelBooking::with(['rent.room'])
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        if (!$cancelBooking) {
            return redirect()->route('user.dashboard')
                ->with('info', 'Tidak ada riwayat pembatalan booking.');
        }

        return view('user.booking.cancel-status', compact('cancelBooking'));
    }
}