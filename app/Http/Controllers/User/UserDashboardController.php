<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Display user dashboard
     * Otomatis detect apakah user punya kamar atau tidak
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek apakah user punya kamar aktif
        $hasRoom = $user->hasActiveRoom();

        if (!$hasRoom) {
            return view('user.dashboard-empty');
        }

        // Ambil rent aktif
        $activeRent = $user->activeRent();

        // PERBAIKAN: Cek jika status checkout_requested
        if ($activeRent && $activeRent->status === 'checkout_requested') {
            // Tampilkan halaman khusus untuk status menunggu approval
            return view('user.dashboard-checkout-pending', [
                'activeRent' => $activeRent
            ]);
        }

        // Dashboard penghuni normal (status active)
        $paymentHistory = $user->payments()
            ->with('billing')
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard-tenant', [
            'activeRent'     => $activeRent,
            'paymentHistory' => $paymentHistory
        ]);
    }

    /**
     * Display room detail for active tenant
     */
    public function roomDetail()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek apakah user punya kamar aktif
        if (!$user->hasActiveRoom()) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Anda belum memiliki kamar aktif');
        }

        // Ambil data sewa aktif beserta room
        $activeRent = $user->activeRent();
        
        // PERBAIKAN: Redirect jika status checkout_requested
        if ($activeRent->status === 'checkout_requested') {
            return redirect()->route('user.dashboard')
                ->with('info', 'Permintaan checkout Anda sedang diproses admin');
        }

        $room = $activeRent->room;

        return view('user.rooms.room-detail', compact('room', 'activeRent'));
    }
}