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
     * Ambil tempat_kos_id admin login
     */
    private function kosId(): int
    {
        return auth()->user()->tempat_kos_id;
    }

    /**
     * Display billing reports (ADMIN KOS ONLY)
     */
    public function index(Request $request)
    {
        $kosId = $this->kosId();

        $query = Billing::where('tempat_kos_id', $kosId)
            ->with(['user', 'room', 'rent', 'latestPayment']);

        $this->applyFilters($query, $request);

        $sortBy = $request->get('sort', 'due_date');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $billings = $query->paginate(20)->withQueryString();

        $stats = $this->calculateStatistics($request);

        $users = User::where('role', 'user')
            ->where('tempat_kos_id', $kosId)
            ->whereHas('billings')
            ->orderBy('name')
            ->get(['id', 'name']);

        $rooms = Room::where('tempat_kos_id', $kosId)
            ->whereHas('billings')
            ->orderBy('room_number')
            ->get(['id', 'room_number']);

        $years = Billing::where('tempat_kos_id', $kosId)
            ->selectRaw('DISTINCT billing_year')
            ->orderByDesc('billing_year')
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
     * Statistik laporan (ADMIN KOS ONLY)
     */
    private function calculateStatistics(Request $request): array
    {
        $query = Billing::where('tempat_kos_id', $this->kosId());
        $this->applyFilters($query, $request);

        return [
            'total_billings' => (clone $query)->count(),
            'total_amount' => (clone $query)->sum('total_amount'),

            'paid_count' => (clone $query)->where('status', 'paid')->count(),
            'paid_amount' => (clone $query)->where('status', 'paid')->sum('total_amount'),

            'unpaid_count' => (clone $query)->where('status', 'unpaid')->count(),
            'unpaid_amount' => (clone $query)->where('status', 'unpaid')->sum('total_amount'),

            'pending_count' => (clone $query)->where('status', 'pending')->count(),
            'pending_amount' => (clone $query)->where('status', 'pending')->sum('total_amount'),

            'overdue_count' => (clone $query)
                ->where('status', '!=', 'paid')
                ->whereDate('due_date', '<', now())
                ->count(),

            'overdue_amount' => (clone $query)
                ->where('status', '!=', 'paid')
                ->whereDate('due_date', '<', now())
                ->sum('total_amount'),
        ];
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Billing::where('tempat_kos_id', $this->kosId())
            ->with(['user', 'room', 'rent']);

        $this->applyFilters($query, $request);

        $billings = $query->orderByDesc('due_date')->get();
        $stats = $this->calculateStatistics($request);
        $filterInfo = $this->getFilterInfo($request);

        $pdf = Pdf::loadView(
            'admin.reports.pdf',
            compact('billings', 'stats', 'filterInfo')
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'Laporan-Tagihan-' . now()->format('Y-m-d-His') . '.pdf'
        );
    }

    /**
     * Export Excel
     */
    public function exportExcel(Request $request)
    {
        $query = Billing::where('tempat_kos_id', $this->kosId())
            ->with(['user', 'room', 'rent']);

        $this->applyFilters($query, $request);

        return Excel::download(
            new BillingReportExport(
                $query->orderByDesc('due_date')->get()
            ),
            'Laporan-Tagihan-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }

    /**
     * Payment Report
     */
    public function paymentReport(Request $request)
    {
        $query = Payment::whereHas('billing', function ($q) {
            $q->where('tempat_kos_id', $this->kosId());
        })->with(['user', 'billing.room']);

        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest('payment_date')
            ->paginate(20)
            ->withQueryString();

        return view('admin.reports.payments', [
            'payments' => $payments,
            'totalPayments' => (clone $query)->count(),
            'totalAmount' => (clone $query)->where('status', 'confirmed')->sum('amount'),
            'confirmedCount' => (clone $query)->where('status', 'confirmed')->count(),
            'pendingCount' => (clone $query)->where('status', 'pending')->count(),
        ]);
    }

    /**
     * Financial Summary
     */
    public function financialSummary(Request $request)
    {
        $year = $request->get('year', now()->year);
        $monthlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $billings = Billing::where('tempat_kos_id', $this->kosId())
                ->where('billing_year', $year)
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

        $years = Billing::where('tempat_kos_id', $this->kosId())
            ->selectRaw('DISTINCT billing_year')
            ->orderByDesc('billing_year')
            ->pluck('billing_year');

        return view('admin.reports.financial', compact(
            'monthlyData',
            'year',
            'years'
        ));
    }

    /**
     * Apply filters (AMAN – selalu kos sendiri)
     */
    private function applyFilters($query, Request $request): void
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
     * Info filter untuk PDF
     */
    private function getFilterInfo(Request $request): array
    {
        $info = [];

        if ($request->filled('start_date')) {
            $info[] = 'Dari: ' . Carbon::parse($request->start_date)->format('d M Y');
        }

        if ($request->filled('end_date')) {
            $info[] = 'Sampai: ' . Carbon::parse($request->end_date)->format('d M Y');
        }

        if ($request->filled('status')) {
            $labels = [
                'paid' => 'Lunas',
                'unpaid' => 'Belum Dibayar',
                'pending' => 'Menunggu Konfirmasi',
                'overdue' => 'Terlambat',
            ];
            $info[] = 'Status: ' . ($labels[$request->status] ?? $request->status);
        }

        return $info;
    }
}
