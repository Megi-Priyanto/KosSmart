<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Rent;
use App\Models\Billing;
use App\Models\Payment;
use App\Models\Disbursement;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user->isSuperAdmin() && !$user->tempat_kos_id) {
            abort(403, 'Anda tidak memiliki akses ke kos manapun.');
        }

        // ==========================
        // STATISTIK KAMAR
        // ==========================
        $roomsQuery = $user->isSuperAdmin()
            ? Room::withoutTempatKosScope()
            : Room::where('tempat_kos_id', $user->tempat_kos_id);

        $totalRooms       = (clone $roomsQuery)->count();
        $occupiedRooms    = (clone $roomsQuery)->where('status', 'occupied')->count();
        $availableRooms   = (clone $roomsQuery)->where('status', 'available')->count();
        $maintenanceRooms = (clone $roomsQuery)->where('status', 'maintenance')->count();

        // ==========================
        // PENDAPATAN BULANAN
        // ==========================
        $disbQueryMonthly = $user->isSuperAdmin()
            ? Disbursement::query()
            : Disbursement::where('tempat_kos_id', $user->tempat_kos_id);

        $monthlyIncome = (clone $disbQueryMonthly)
            ->where('status', 'processed')
            ->whereMonth('processed_at', now()->month)
            ->whereYear('processed_at', now()->year)
            ->sum('total_amount');

        $lastMonthIncome = (clone $disbQueryMonthly)
            ->where('status', 'processed')
            ->whereMonth('processed_at', now()->subMonth()->month)
            ->whereYear('processed_at', now()->subMonth()->year)
            ->sum('total_amount');

        $incomeChangePercent = $lastMonthIncome > 0
            ? round((($monthlyIncome - $lastMonthIncome) / $lastMonthIncome) * 100)
            : 0;

        // ==========================
        // PENDAPATAN TAHUNAN
        // ==========================
        $disbQueryYearly = $user->isSuperAdmin()
            ? Disbursement::query()
            : Disbursement::where('tempat_kos_id', $user->tempat_kos_id);

        $yearlyIncome = (clone $disbQueryYearly)
            ->where('status', 'processed')
            ->whereYear('processed_at', now()->year)
            ->sum('total_amount');

        $lastYearIncome = (clone $disbQueryYearly)
            ->where('status', 'processed')
            ->whereYear('processed_at', now()->subYear()->year)
            ->sum('total_amount');

        $yearlyIncomeChangePercent = $lastYearIncome > 0
            ? round((($yearlyIncome - $lastYearIncome) / $lastYearIncome) * 100)
            : 0;

        // ==========================
        // DANA MENUNGGU PENCAIRAN
        // ==========================
        $holdingAmount = $user->isSuperAdmin()
            ? Payment::withoutTempatKosScope()
                ->where('status', 'confirmed')->where('disbursement_status', 'holding')->sum('amount')
            : Payment::where('tempat_kos_id', $user->tempat_kos_id)
                ->where('status', 'confirmed')->where('disbursement_status', 'holding')->sum('amount');

        // ==========================
        // TAGIHAN PENDING
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
        // INFO KOS
        // ==========================
        $kosInfoQuery = $user->isSuperAdmin()
            ? \App\Models\KosInfo::withoutTempatKosScope()
            : \App\Models\KosInfo::where('tempat_kos_id', $user->tempat_kos_id);
        $kosInfo = $kosInfoQuery->first();

        // ==========================
        // BOOKING PENDING
        // PERBAIKAN: Keluarkan rent yang sudah ada cancel booking pending-nya
        // Sehingga jika user mengajukan cancel booking (sebelum admin approve booking),
        // notifikasi booking akan hilang dan digantikan notifikasi cancel booking
        // ==========================
        $pendingBookingsQuery = $user->isSuperAdmin()
            ? Rent::withoutTempatKosScope()
            : Rent::where('tempat_kos_id', $user->tempat_kos_id);

        // Sub-query: ambil rent_id yang sudah ada pengajuan cancel booking pending
        $cancelPendingRentIds = \App\Models\CancelBooking::where('status', 'pending')
            ->pluck('rent_id')
            ->toArray();

        // PERBAIKAN: Exclude rent yang sudah ada cancel booking pending
        $pendingBookings = (clone $pendingBookingsQuery)
            ->where('status', 'pending')
            ->whereNotIn('id', $cancelPendingRentIds)
            ->with(['user', 'room'])
            ->latest()
            ->take(5)
            ->get()
            ->filter(fn($item) => $item->user && $item->room);

        // PERBAIKAN: Count juga exclude rent yang sudah ada cancel booking pending
        $pendingBookingsCount = (clone $pendingBookingsQuery)
            ->where('status', 'pending')
            ->whereNotIn('id', $cancelPendingRentIds)
            ->count();

        // ==========================
        // CANCEL BOOKING
        // Tampilkan yang masih pending (menunggu approval admin)
        // ==========================
        $cancelBookingsPending = $user->isSuperAdmin()
            ? \App\Models\CancelBooking::withoutTempatKosScope()
                ->where('status', 'pending')
                ->with(['rent.room', 'user'])
                ->latest()->take(5)->get()
                ->filter(fn($item) => $item->user && $item->rent && $item->rent->room)
            : \App\Models\CancelBooking::where('tempat_kos_id', $user->tempat_kos_id)
                ->where('status', 'pending')
                ->with(['rent.room', 'user'])
                ->latest()->take(5)->get()
                ->filter(fn($item) => $item->user && $item->rent && $item->rent->room);

        $cancelBookingsCount = $user->isSuperAdmin()
            ? \App\Models\CancelBooking::withoutTempatKosScope()->where('status', 'pending')->count()
            : \App\Models\CancelBooking::where('tempat_kos_id', $user->tempat_kos_id)->where('status', 'pending')->count();

        // ==========================
        // CHECKOUT REQUEST
        // ==========================
        $checkoutRequestsQuery = $user->isSuperAdmin()
            ? Rent::withoutTempatKosScope()
            : Rent::where('tempat_kos_id', $user->tempat_kos_id);

        $checkoutRequests = (clone $checkoutRequestsQuery)
            ->where('status', 'checkout_requested')
            ->with(['user', 'room'])
            ->latest()->take(5)->get()
            ->filter(fn($item) => $item->user && $item->room);

        $checkoutRequestsCount = (clone $checkoutRequestsQuery)->where('status', 'checkout_requested')->count();

        // ==========================
        // PENDING PAYMENTS
        // ==========================
        $pendingPaymentsQuery = $user->isSuperAdmin()
            ? Payment::withoutTempatKosScope()
            : Payment::where('tempat_kos_id', $user->tempat_kos_id);

        $pendingPayments = (clone $pendingPaymentsQuery)
            ->where('status', 'pending')
            ->with(['user', 'billing.room'])
            ->latest()->take(5)->get()
            ->filter(fn($item) => $item->user && $item->billing && $item->billing->room);

        $pendingPaymentsCount = (clone $pendingPaymentsQuery)->where('status', 'pending')->count();

        // ==========================
        // NOTIFIKASI
        // ==========================
        $activitiesQuery = $user->isSuperAdmin()
            ? Notification::withoutTempatKosScope()
            : Notification::where('tempat_kos_id', $user->tempat_kos_id);

        $activities         = (clone $activitiesQuery)->with('user')->latest()->take(7)->get();
        $todayNotifications = (clone $activitiesQuery)->where('status', 'unread')->count();

        $notifications = Notification::where('user_id', $user->id)
            ->where('status', 'unread')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // ==========================
        // CHART PENDAPATAN 6 BULAN
        // ==========================
        $monthlyRevenueLabels = [];
        $monthlyRevenueData   = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $disbQueryChart = $user->isSuperAdmin()
                ? Disbursement::query()
                : Disbursement::where('tempat_kos_id', $user->tempat_kos_id);

            $revenue = (clone $disbQueryChart)
                ->where('status', 'processed')
                ->whereYear('processed_at', $date->year)
                ->whereMonth('processed_at', $date->month)
                ->sum('total_amount');

            $monthlyRevenueLabels[] = $date->format('M Y');
            $monthlyRevenueData[]   = round($revenue / 1000000, 1);
        }

        // ==========================
        // CHART STATUS PEMBAYARAN
        // ==========================
        $paymentStatusQuery = $user->isSuperAdmin()
            ? Billing::withoutTempatKosScope()
            : Billing::where('tempat_kos_id', $user->tempat_kos_id);

        $paidCount    = (clone $paymentStatusQuery)->where('status', 'paid')->count();
        $unpaidCount  = (clone $paymentStatusQuery)->where('status', 'unpaid')->count();
        $overdueCount = (clone $paymentStatusQuery)->where('status', 'overdue')->count();
        $pendingCount = (clone $paymentStatusQuery)->where('status', 'pending')->count();

        $paymentStatusData = [$paidCount, $unpaidCount, $overdueCount, $pendingCount];

        return view('admin.dashboard', compact(
            'totalRooms', 'occupiedRooms', 'availableRooms', 'maintenanceRooms',
            'monthlyIncome', 'lastMonthIncome', 'incomeChangePercent',
            'yearlyIncome', 'lastYearIncome', 'yearlyIncomeChangePercent',
            'holdingAmount',
            'pendingBills', 'pendingBillsThisMonth', 'pendingBillsLastMonth', 'pendingGrowth',
            'kosInfo',
            'pendingBookings', 'pendingBookingsCount',
            'cancelBookingsPending', 'cancelBookingsCount',
            'checkoutRequests', 'checkoutRequestsCount',
            'pendingPayments', 'pendingPaymentsCount',
            'todayNotifications', 'activities', 'notifications',
            'monthlyRevenueLabels', 'monthlyRevenueData', 'paymentStatusData'
        ));
    }
}