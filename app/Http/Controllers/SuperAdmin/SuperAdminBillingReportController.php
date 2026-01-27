<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AdminBilling;
use App\Models\TempatKos;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class SuperAdminBillingReportController extends Controller
{
    /**
     * Display billing report page
     */
    public function index(Request $request)
    {
        $query = AdminBilling::with(['tempatKos', 'admin']);

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('due_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'overdue') {
                $query->overdue();
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter by penyewa (admin/tempat kos)
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        // Filter by room (kamar) - via tempat_kos
        if ($request->filled('tempat_kos_id')) {
            $query->where('tempat_kos_id', $request->tempat_kos_id);
        }

        // Get billings with pagination
        $billings = $query->orderBy('due_date', 'desc')->paginate(15)->withQueryString();

        // Calculate statistics
        $stats = [
            'total_count' => AdminBilling::count(),
            'total_amount' => AdminBilling::sum('amount'),
            'paid_count' => AdminBilling::paid()->count(),
            'paid_amount' => AdminBilling::paid()->sum('amount'),
            'unpaid_count' => AdminBilling::unpaid()->count(),
            'unpaid_amount' => AdminBilling::unpaid()->sum('amount'),
            'overdue_count' => AdminBilling::overdue()->count(),
            'overdue_amount' => AdminBilling::overdue()->sum('amount'),
        ];

        // Get filter options
        $tempatKosList = TempatKos::select('id', 'nama_kos')->orderBy('nama_kos')->get();
        $adminsList = User::where('role', 'admin')
            ->select('id', 'name', 'tempat_kos_id')
            ->with('tempatKos:id,nama_kos')
            ->orderBy('name')
            ->get();

        return view('superadmin.billing-report.index', compact(
            'billings',
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
        $query = AdminBilling::with(['tempatKos', 'admin']);

        // Apply same filters as index
        if ($request->filled('date_from')) {
            $query->whereDate('due_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'overdue') {
                $query->overdue();
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        if ($request->filled('tempat_kos_id')) {
            $query->where('tempat_kos_id', $request->tempat_kos_id);
        }

        $billings = $query->orderBy('due_date', 'desc')->get();

        $stats = [
            'total_count' => $billings->count(),
            'total_amount' => $billings->sum('amount'),
            'paid_count' => $billings->where('status', 'paid')->count(),
            'paid_amount' => $billings->where('status', 'paid')->sum('amount'),
            'unpaid_count' => $billings->whereIn('status', ['unpaid', 'pending'])->count(),
            'unpaid_amount' => $billings->whereIn('status', ['unpaid', 'pending'])->sum('amount'),
        ];

        // Generate PDF using DomPDF
        $pdf = Pdf::loadView('superadmin.billing-report.pdf', compact('billings', 'stats'));
        
        $filename = 'laporan-tagihan-operasional-' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export report to Excel
     */
    public function exportExcel(Request $request)
    {
        $query = AdminBilling::with(['tempatKos', 'admin']);

        // Apply same filters
        if ($request->filled('date_from')) {
            $query->whereDate('due_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('due_date', '<=', $request->date_to);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'overdue') {
                $query->overdue();
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        if ($request->filled('tempat_kos_id')) {
            $query->where('tempat_kos_id', $request->tempat_kos_id);
        }

        $billings = $query->orderBy('due_date', 'desc')->get();

        // Create Excel export using AdminBillingReportExport (for SuperAdmin → Admin)
        return Excel::download(
            new \App\Exports\AdminBillingReportExport($billings),
            'laporan-tagihan-operasional-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}