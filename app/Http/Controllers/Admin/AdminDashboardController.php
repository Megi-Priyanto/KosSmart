<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Room;
use App\Models\Rent;
use App\Models\Billing;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ==========================
        // STATISTIK USER
        // ==========================
        $totalUsers = User::where('role', 'user')->count();

        $newUsersThisMonth = User::where('role', 'user')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        // ==========================
        // STATISTIK KAMAR
        // ==========================
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $availableRooms = Room::where('status', 'available')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();

        // ==========================
        // STATISTIK PENDAPATAN
        // ==========================
        $monthlyIncome = Billing::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $lastMonthIncome = Billing::where('status', 'paid')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');

        $incomeChangePercent = $lastMonthIncome > 0
            ? round((($monthlyIncome - $lastMonthIncome) / $lastMonthIncome) * 100)
            : 0;

        // ==========================
        // TAGIHAN PENDING
        // ==========================
        $pendingBills = Billing::where('status', 'pending')->count();

        $pendingBillsThisMonth = Billing::where('status', 'pending')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $pendingBillsLastMonth = Billing::where('status', 'pending')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $pendingGrowth = $pendingBillsLastMonth > 0
            ? round((($pendingBillsThisMonth - $pendingBillsLastMonth) / $pendingBillsLastMonth) * 100)
            : 0;

        // ==========================
        // INFO KOS
        // ==========================
        $kosInfo = \App\Models\KosInfo::first();

        // ==========================
        // BOOKING PENDING
        // ==========================
        $pendingBookings = Rent::where('status', 'pending')
            ->with(['user', 'room'])
            ->latest()
            ->take(5)
            ->get();

        $pendingBookingsCount = Rent::where('status', 'pending')->count();

        // ==========================
        // NOTIFIKASI BARU
        // ==========================
        $activities = Notification::with('user')
            ->latest()
            ->take(7)
            ->get();

        $todayNotifications = Notification::where('status', 'pending')->count();

        $notifications = Notification::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // ==========================
        // FIX UTAMA (UNTUK BLADE)
        // ==========================
        $overdueNotifications = Notification::where('status', 'pending')
            ->whereNotNull('due_date')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersThisMonth',
            'totalRooms',
            'occupiedRooms',
            'availableRooms',
            'maintenanceRooms',
            'monthlyIncome',
            'lastMonthIncome',
            'incomeChangePercent',
            'pendingBills',
            'pendingBillsThisMonth',
            'pendingBillsLastMonth',
            'pendingGrowth',
            'recentUsers',
            'kosInfo',
            'pendingBookings',
            'pendingBookingsCount',
            'todayNotifications',
            'activities',
            'notifications',
            'overdueNotifications'
        ));
    }
}
