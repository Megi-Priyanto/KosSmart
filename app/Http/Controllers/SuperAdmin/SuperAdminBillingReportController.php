<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Disbursement;
use App\Models\TempatKos;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class SuperAdminBillingReportController extends Controller
{
    /**
     * Display billing report page (berbasis Disbursement / Pendapatan Platform Fee)
     */
    public function index(Request $request)
    {
        $query = Disbursement::with(['tempatKos', 'admin', 'processor'])
            ->where('status', 'processed');

        // Filter by date range (berdasarkan processed_at)
        if ($request->filled('date_from')) {
            $query->whereDate('processed_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('processed_at', '<=', $request->date_to);
        }

        // Filter by admin (pemilik kos)
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        // Filter by tempat kos
        if ($request->filled('tempat_kos_id')) {
            $query->where('tempat_kos_id', $request->tempat_kos_id);
        }

        // Get disbursements with pagination
        $disbursements = $query->orderBy('processed_at', 'desc')->paginate(15)->withQueryString();

        // Calculate statistics (Pendapatan Platform dari Fee)
        $baseQuery = Disbursement::where('status', 'processed');

        $stats = [
            'total_disbursement_count'  => (clone $baseQuery)->count(),
            'total_gross_amount'        => (clone $baseQuery)->sum('gross_amount'),
            'total_fee_amount'          => (clone $baseQuery)->sum('fee_amount'),           // pendapatan platform
            'total_admin_amount'        => (clone $baseQuery)->sum('total_amount'),         // yang diterima admin

            'fee_this_month'            => (clone $baseQuery)
                ->whereMonth('processed_at', now()->month)
                ->whereYear('processed_at', now()->year)
                ->sum('fee_amount'),

            'fee_this_year'             => (clone $baseQuery)
                ->whereYear('processed_at', now()->year)
                ->sum('fee_amount'),

            'count_this_month'          => (clone $baseQuery)
                ->whereMonth('processed_at', now()->month)
                ->whereYear('processed_at', now()->year)
                ->count(),

            'gross_this_month'          => (clone $baseQuery)
                ->whereMonth('processed_at', now()->month)
                ->whereYear('processed_at', now()->year)
                ->sum('gross_amount'),
        ];

        // Get filter options
        $tempatKosList = TempatKos::select('id', 'nama_kos')->orderBy('nama_kos')->get();
        $adminsList = User::where('role', 'admin')
            ->select('id', 'name', 'tempat_kos_id')
            ->with('tempatKos:id,nama_kos')
            ->orderBy('name')
            ->get();

        return view('superadmin.billing-report.index', compact(
            'disbursements',
            'stats',
            'tempatKosList',
            'adminsList'
        ));
    }

    /**
     * Export report to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Disbursement::with(['tempatKos', 'admin', 'processor'])
            ->where('status', 'processed');

        if ($request->filled('date_from')) {
            $query->whereDate('processed_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('processed_at', '<=', $request->date_to);
        }
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }
        if ($request->filled('tempat_kos_id')) {
            $query->where('tempat_kos_id', $request->tempat_kos_id);
        }

        $disbursements = $query->orderBy('processed_at', 'desc')->get();

        $stats = [
            'total_disbursement_count' => $disbursements->count(),
            'total_gross_amount'       => $disbursements->sum('gross_amount'),
            'total_fee_amount'         => $disbursements->sum('fee_amount'),
            'total_admin_amount'       => $disbursements->sum('total_amount'),
        ];

        $pdf = Pdf::loadView('superadmin.billing-report.pdf', compact('disbursements', 'stats'));

        $filename = 'laporan-pendapatan-platform-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export report to Excel
     */
    public function exportExcel(Request $request)
    {
        $query = Disbursement::with(['tempatKos', 'admin', 'processor'])
            ->where('status', 'processed');

        if ($request->filled('date_from')) {
            $query->whereDate('processed_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('processed_at', '<=', $request->date_to);
        }
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }
        if ($request->filled('tempat_kos_id')) {
            $query->where('tempat_kos_id', $request->tempat_kos_id);
        }

        $disbursements = $query->orderBy('processed_at', 'desc')->get();

        return Excel::download(
            new \App\Exports\DisbursementReportExport($disbursements),
            'laporan-pendapatan-platform-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}