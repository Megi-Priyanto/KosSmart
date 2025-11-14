<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Ambil statistik
        $totalUsers = User::where('role', 'user')->count();
        $totalRooms = 30; // Nanti dari model Room
        $occupiedRooms = 24; // Nanti dari model Room
        $monthlyIncome = 36000000; // Nanti dari model Payment
        $pendingBills = 5; // Nanti dari model Bill
        
        // Ambil user terbaru
        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRooms',
            'occupiedRooms',
            'monthlyIncome',
            'pendingBills',
            'recentUsers'
        ));
    }
}