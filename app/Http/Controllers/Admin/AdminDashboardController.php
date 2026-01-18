<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Rent;
use App\Models\Billing;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Validasi kepemilikan kos (Admin wajib punya tempat_kos_id)
        if (!$user->isSuperAdmin() && !$user->tempat_kos_id) {
            abort(403, 'Anda tidak memiliki akses ke kos manapun.');
        }

        // ==========================
        // STATISTIK KAMAR (MULTI-TENANT)
        // ==========================
        $roomsQuery = $user->isSuperAdmin()
            ? Room::withoutTempatKosScope()
            : Room::where('tempat_kos_id', $user->tempat_kos_id);

        $totalRooms = (clone $roomsQuery)->count();
        $occupiedRooms = (clone $roomsQuery)->where('status', 'occupied')->count();
        $availableRooms = (clone $roomsQuery)->where('status', 'available')->count();
        $maintenanceRooms = (clone $roomsQuery)->where('status', 'maintenance')->count();

        // ==========================
        // STATISTIK PENDAPATAN BULANAN (MULTI-TENANT)
        // ==========================
        $billingQueryMonthly = $user->isSuperAdmin()
            ? Billing::withoutTempatKosScope()
            : Billing::where('tempat_kos_id', $user->tempat_kos_id);

        $monthlyIncome = (clone $billingQueryMonthly)
            ->where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $lastMonthIncome = (clone $billingQueryMonthly)
            ->where('status', 'paid')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');

        $incomeChangePercent = $lastMonthIncome > 0
            ? round((($monthlyIncome - $lastMonthIncome) / $lastMonthIncome) * 100)
            : 0;

        // ==========================
        // STATISTIK PENDAPATAN TAHUNAN (MULTI-TENANT)
        // ==========================
        $billingQueryYearly = $user->isSuperAdmin()
            ? Billing::withoutTempatKosScope()
            : Billing::where('tempat_kos_id', $user->tempat_kos_id);

        $yearlyIncome = (clone $billingQueryYearly)
            ->where('status', 'paid')
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $lastYearIncome = (clone $billingQueryYearly)
            ->where('status', 'paid')
            ->whereYear('created_at', now()->subYear()->year)
            ->sum('total_amount');

        $yearlyIncomeChangePercent = $lastYearIncome > 0
            ? round((($yearlyIncome - $lastYearIncome) / $lastYearIncome) * 100)
            : 0;

        // ==========================
        // TAGIHAN PENDING (MULTI-TENANT)
        // ==========================
        $billingQueryPending = $user->isSuperAdmin()
            ? Billing::withoutTempatKosScope()
            : Billing::where('tempat_kos_id', $user->tempat_kos_id);

        $pendingBills = (clone $billingQueryPending)->where('status', 'pending')->count();

        $pendingBillsThisMonth = (clone $billingQueryPending)
            ->where('status', 'pending')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $pendingBillsLastMonth = (clone $billingQueryPending)
            ->where('status', 'pending')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $pendingGrowth = $pendingBillsLastMonth > 0
            ? round((($pendingBillsThisMonth - $pendingBillsLastMonth) / $pendingBillsLastMonth) * 100)
            : 0;

        // ==========================
        // INFO KOS (MULTI-TENANT)
        // ==========================
        $kosInfoQuery = $user->isSuperAdmin()
            ? \App\Models\KosInfo::withoutTempatKosScope()
            : \App\Models\KosInfo::where('tempat_kos_id', $user->tempat_kos_id);

        $kosInfo = $kosInfoQuery->first();

        // ==========================
        // BOOKING PENDING (MULTI-TENANT)
        // ==========================
        $pendingBookingsQuery = $user->isSuperAdmin()
            ? Rent::withoutTempatKosScope()
            : Rent::where('tempat_kos_id', $user->tempat_kos_id);

        $pendingBookings = (clone $pendingBookingsQuery)
            ->where('status', 'pending')
            ->with(['user', 'room'])
            ->latest()
            ->take(5)
            ->get();

        $pendingBookingsCount = (clone $pendingBookingsQuery)
            ->where('status', 'pending')
            ->count();

        // ==========================
        // CHECKOUT REQUEST (MULTI-TENANT)
        // ==========================
        $checkoutRequestsQuery = $user->isSuperAdmin()
            ? Rent::withoutTempatKosScope()
            : Rent::where('tempat_kos_id', $user->tempat_kos_id);

        $checkoutRequests = (clone $checkoutRequestsQuery)
            ->where('status', 'checkout_requested')
            ->with(['user', 'room'])
            ->latest()
            ->take(5)
            ->get();

        $checkoutRequestsCount = (clone $checkoutRequestsQuery)
            ->where('status', 'checkout_requested')
            ->count();

        // ==========================
        // NOTIFIKASI BARU (MULTI-TENANT)
        // ==========================
        $activitiesQuery = $user->isSuperAdmin()
            ? Notification::withoutTempatKosScope()
            : Notification::where('tempat_kos_id', $user->tempat_kos_id);

        $activities = (clone $activitiesQuery)
            ->with('user')
            ->latest()
            ->take(7)
            ->get();

        $todayNotifications = (clone $activitiesQuery)
            ->where('status', 'pending')
            ->count();

        $notifications = Notification::where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $overdueNotifications = (clone $activitiesQuery)
            ->where('status', 'pending')
            ->whereNotNull('due_date')
            ->latest()
            ->take(10)
            ->get();

        // ==========================
        // DATA UNTUK CHART PENDAPATAN 6 BULAN (MULTI-TENANT)
        // ==========================
        $monthlyRevenueLabels = [];
        $monthlyRevenueData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            
            $billingQueryChart = $user->isSuperAdmin()
                ? Billing::withoutTempatKosScope()
                : Billing::where('tempat_kos_id', $user->tempat_kos_id);

            $revenue = (clone $billingQueryChart)
                ->where('status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');

            $monthlyRevenueLabels[] = $date->format('M Y');
            $monthlyRevenueData[] = round($revenue / 1000000, 1); // Konversi ke juta
        }

        // ==========================
        // DATA UNTUK CHART STATUS PEMBAYARAN (MULTI-TENANT)
        // ==========================
        $paymentStatusQuery = $user->isSuperAdmin()
            ? Billing::withoutTempatKosScope()
            : Billing::where('tempat_kos_id', $user->tempat_kos_id);

        $paidCount = (clone $paymentStatusQuery)->where('status', 'paid')->count();
        $unpaidCount = (clone $paymentStatusQuery)->where('status', 'unpaid')->count();
        $overdueCount = (clone $paymentStatusQuery)->where('status', 'overdue')->count();
        $pendingCount = (clone $paymentStatusQuery)->where('status', 'pending')->count();

        $paymentStatusData = [$paidCount, $unpaidCount, $overdueCount, $pendingCount];

        return view('admin.dashboard', compact(
            'totalRooms',
            'occupiedRooms',
            'availableRooms',
            'maintenanceRooms',
            'monthlyIncome',
            'lastMonthIncome',
            'incomeChangePercent',
            'yearlyIncome',
            'lastYearIncome',
            'yearlyIncomeChangePercent',
            'pendingBills',
            'pendingBillsThisMonth',
            'pendingBillsLastMonth',
            'pendingGrowth',
            'kosInfo',
            'pendingBookings',
            'pendingBookingsCount',
            'checkoutRequests',
            'checkoutRequestsCount',
            'todayNotifications',
            'activities',
            'notifications',
            'overdueNotifications',
            'monthlyRevenueLabels',
            'monthlyRevenueData',
            'paymentStatusData'
        ));
    }
}