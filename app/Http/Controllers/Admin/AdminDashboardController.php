<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Room;
use App\Models\Rent;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $availableRooms = Room::where('status', 'available')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();
        $monthlyIncome = 36000000;
        $pendingBills = 5;

        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        $kosInfo = \App\Models\KosInfo::first();
        
        // TAMBAHKAN INI - Pending bookings untuk notifikasi
        $pendingBookings = Rent::where('status', 'pending')
            ->with(['user', 'room'])
            ->latest()
            ->take(5)
            ->get();
            
        $pendingBookingsCount = Rent::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRooms',
            'occupiedRooms',
            'availableRooms',
            'maintenanceRooms',
            'monthlyIncome',
            'pendingBills',
            'recentUsers',
            'kosInfo',
            'pendingBookings',
            'pendingBookingsCount'
        ));
    }
}