<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\User;
use App\Models\Room;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\BillingReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display billing reports
     * 
     * Global Scope otomatis filter berdasarkan tempat_kos_id
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Query builder
        if ($user->isSuperAdmin()) {
            $query = Billing::withoutTempatKosScope()->with(['user', 'room', 'rent', 'latestPayment']);
        } else {
            $query = Billing::with(['user', 'room', 'rent', 'latestPayment']);
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('due_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('due_date', '<=', $request->end_date);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->where('status', '!=', 'paid')
                    ->whereDate('due_date', '<', now());
            } else {
                $query->where('status', $request->status);
            }
        }
        
        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filter by room
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        
        // Filter by month & year
        if ($request->filled('month') && $request->filled('year')) {
            $query->where('billing_month', $request->month)
                  ->where('billing_year', $request->year);
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'due_date');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $billings = $query->paginate(20)->withQueryString();
        
        // Statistics
        $stats = $this->calculateStatistics($request);
        
        // Data for filters (hanya user di kos yang sama)
        $usersQuery = User::where('role', 'user')
            ->whereHas('billings');

        if (!$user->isSuperAdmin()) {
            $usersQuery->where('tempat_kos_id', $user->tempat_kos_id);
        }

        $users = $usersQuery->orderBy('name')->get(['id', 'name']);
        
        // Rooms (otomatis filtered)
        $roomsQuery = $user->isSuperAdmin()
            ? Room::withoutTempatKosScope()->whereHas('billings')
            : Room::whereHas('billings');

        $rooms = $roomsQuery->orderBy('room_number')->get(['id', 'room_number']);
        
        // Years (otomatis filtered)
        $yearsQuery = $user->isSuperAdmin()
            ? Billing::withoutTempatKosScope()
            : Billing::query();

        $years = $yearsQuery->selectRaw('DISTINCT billing_year')
            ->orderBy('billing_year', 'desc')
            ->pluck('billing_year');
        
        return view('admin.reports.index', compact(
            'billings',
            'stats',
            'users',
            'rooms',
            'years'
        ));
    }
    
    /**
     * Calculate statistics based on filters
     * 
     * Otomatis filtered berdasarkan tempat_kos_id
     */
    private function calculateStatistics(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $query = $user->isSuperAdmin()
            ? Billing::withoutTempatKosScope()
            : Billing::query();
        
        // Apply same filters as main query
        if ($request->filled('start_date')) {
            $query->whereDate('due_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('due_date', '<=', $request->end_date);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->where('status', '!=', 'paid')
                    ->whereDate('due_date', '<', now());
            } else {
                $query->where('status', $request->status);
            }
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        
        return [
            'total_billings' => (clone $query)->count(),
            'total_amount' => (clone $query)->sum('total_amount'),
            'paid_count' => (clone $query)->where('status', 'paid')->count(),
            'paid_amount' => (clone $query)->where('status', 'paid')->sum('total_amount'),
            'unpaid_count' => (clone $query)->where('status', 'unpaid')->count(),
            'unpaid_amount' => (clone $query)->where('status', 'unpaid')->sum('total_amount'),
            'overdue_count' => (clone $query)->where('status', '!=', 'paid')
                ->whereDate('due_date', '<', now())->count(),
            'overdue_amount' => (clone $query)->where('status', '!=', 'paid')
                ->whereDate('due_date', '<', now())->sum('total_amount'),
            'pending_count' => (clone $query)->where('status', 'pending')->count(),
            'pending_amount' => (clone $query)->where('status', 'pending')->sum('total_amount'),
        ];
    }
    
    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $query = $user->isSuperAdmin()
            ? Billing::withoutTempatKosScope()->with(['user', 'room', 'rent'])
            : Billing::with(['user', 'room', 'rent']);
        
        // Apply filters
        $this->applyFilters($query, $request);
        
        $billings = $query->orderBy('due_date', 'desc')->get();
        $stats = $this->calculateStatistics($request);
        
        // Filter info for PDF header
        $filterInfo = $this->getFilterInfo($request);
        
        $pdf = Pdf::loadView('admin.reports.pdf', compact('billings', 'stats', 'filterInfo'))
            ->setPaper('a4', 'landscape');
        
        $filename = 'Laporan-Tagihan-' . now()->format('Y-m-d-His') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $query = $user->isSuperAdmin()
            ? Billing::withoutTempatKosScope()->with(['user', 'room', 'rent'])
            : Billing::with(['user', 'room', 'rent']);
        
        // Apply filters
        $this->applyFilters($query, $request);
        
        $billings = $query->orderBy('due_date', 'desc')->get();
        
        $filename = 'Laporan-Tagihan-' . now()->format('Y-m-d-His') . '.xlsx';
        
        return Excel::download(
            new BillingReportExport($billings), 
            $filename
        );
    }
    
    /**
     * Apply filters to query
     */
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('start_date')) {
            $query->whereDate('due_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('due_date', '<=', $request->end_date);
        }
        
        if ($request->filled('status')) {
            if ($request->status === 'overdue') {
                $query->where('status', '!=', 'paid')
                    ->whereDate('due_date', '<', now());
            } else {
                $query->where('status', $request->status);
            }
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        
        if ($request->filled('month') && $request->filled('year')) {
            $query->where('billing_month', $request->month)
                  ->where('billing_year', $request->year);
        }
    }
    
    /**
     * Get filter info for display
     */
    private function getFilterInfo(Request $request)
    {
        $info = [];
        
        if ($request->filled('start_date')) {
            $info[] = 'Dari: ' . Carbon::parse($request->start_date)->format('d M Y');
        }
        
        if ($request->filled('end_date')) {
            $info[] = 'Sampai: ' . Carbon::parse($request->end_date)->format('d M Y');
        }
        
        if ($request->filled('status')) {
            $statusLabels = [
                'paid' => 'Lunas',
                'unpaid' => 'Belum Dibayar',
                'overdue' => 'Terlambat',
                'pending' => 'Menunggu Konfirmasi',
            ];
            $info[] = 'Status: ' . ($statusLabels[$request->status] ?? $request->status);
        }
        
        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
            if ($user) {
                $info[] = 'Penyewa: ' . $user->name;
            }
        }
        
        if ($request->filled('room_id')) {
            $room = Room::find($request->room_id);
            if ($room) {
                $info[] = 'Kamar: ' . $room->room_number;
            }
        }
        
        return $info;
    }
    
    /**
     * Payment report
     * 
     * Otomatis filtered
     */
    public function paymentReport(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $query = $user->isSuperAdmin()
            ? Payment::withoutTempatKosScope()->with(['user', 'billing.room'])
            : Payment::with(['user', 'billing.room']);
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $payments = $query->latest('payment_date')->paginate(20)->withQueryString();
        
        // Statistics
        $totalPayments = (clone $query)->count();
        $totalAmount = (clone $query)->where('status', 'confirmed')->sum('amount');
        $confirmedCount = (clone $query)->where('status', 'confirmed')->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        
        return view('admin.reports.payments', compact(
            'payments',
            'totalPayments',
            'totalAmount',
            'confirmedCount',
            'pendingCount'
        ));
    }
    
    /**
     * Financial summary
     * 
     * Otomatis filtered
     */
    public function financialSummary(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $year = $request->get('year', now()->year);
        
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $billingsQuery = $user->isSuperAdmin()
                ? Billing::withoutTempatKosScope()
                : Billing::query();

            $billings = $billingsQuery->where('billing_year', $year)
                ->where('billing_month', $month)
                ->get();
            
            $monthlyData[] = [
                'month' => $month,
                'month_name' => Carbon::create($year, $month)->format('F'),
                'total_billings' => $billings->count(),
                'total_amount' => $billings->sum('total_amount'),
                'paid_amount' => $billings->where('status', 'paid')->sum('total_amount'),
                'unpaid_amount' => $billings->where('status', 'unpaid')->sum('total_amount'),
            ];
        }
        
        $yearsQuery = $user->isSuperAdmin()
            ? Billing::withoutTempatKosScope()
            : Billing::query();

        $years = $yearsQuery->selectRaw('DISTINCT billing_year')
            ->orderBy('billing_year', 'desc')
            ->pluck('billing_year');
        
        return view('admin.reports.financial', compact('monthlyData', 'year', 'years'));
    }
}