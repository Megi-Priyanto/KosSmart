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
        $user = Auth::user();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek apakah user punya kamar aktif
        $hasRoom = $user->hasActiveRoom();

        if (!$hasRoom) {
            // User belum punya kamar → Dashboard Kosong
            return view('user.dashboard-empty');
        }

        // User sudah punya kamar → Dashboard Penghuni
        $activeRent = $user->activeRent();
        $currentBilling = $user->currentBilling();
        $paymentHistory = $user->payments()
            ->with('billing')
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard-tenant', [
            'activeRent'      => $activeRent,
            'currentBill'     => $currentBilling,
            'paymentHistory'  => $paymentHistory
        ]);
    }
}
