<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Display user dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Sample data - nanti ambil dari database
        $roomInfo = (object)[
            'number' => '101',
            'location' => 'Lantai 1',
            'status' => 'active',
            'check_in' => '01 Jan 2025',
        ];
        
        $currentBill = (object)[
            'amount' => 1500000,
            'rent' => 1200000,
            'electricity' => 200000,
            'water' => 100000,
            'status' => 'unpaid',
        ];
        
        $paymentHistory = [];
        
        return view('user.dashboard', compact(
            'roomInfo',
            'currentBill',
            'paymentHistory'
        ));
    }
}