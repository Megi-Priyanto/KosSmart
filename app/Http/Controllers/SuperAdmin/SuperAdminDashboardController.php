<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TempatKos;
use App\Models\User;
use App\Models\Room;
use App\Models\Payment;
use App\Models\Disbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        // ============================================================
        // STATISTIK GLOBAL TEMPAT KOS & USER
        // ============================================================
        $totalTempatKos      = TempatKos::count();
        $totalTempatKosAktif = TempatKos::where('status', 'active')->count();

        $totalAdmin = User::where('role', 'admin')->count();
        $totalUser  = User::where('role', 'user')->count();

        // ============================================================
        // STATISTIK KAMAR & OCCUPANCY
        // ============================================================
        $totalKamar       = Room::withoutTempatKosScope()->count();
        $totalKamarTerisi = Room::withoutTempatKosScope()->where('status', 'occupied')->count();

        $occupancyRate = $totalKamar > 0
            ? round(($totalKamarTerisi / $totalKamar) * 100, 1)
            : 0;

        // ============================================================
        // STATISTIK DANA HOLDING
        // ============================================================
        $totalHoldingAmount = Payment::withoutTempatKosScope()
            ->where('status', 'confirmed')
            ->where('disbursement_status', 'holding')
            ->sum('amount');

        $totalHoldingCount = Payment::withoutTempatKosScope()
            ->where('status', 'confirmed')
            ->where('disbursement_status', 'holding')
            ->count();

        $holdingThisMonth = Payment::withoutTempatKosScope()
            ->where('status', 'confirmed')
            ->where('disbursement_status', 'holding')
            ->whereMonth('verified_at', now()->month)
            ->whereYear('verified_at', now()->year)
            ->sum('amount');

        $totalDisbursedAmount = Payment::withoutTempatKosScope()
            ->where('status', 'confirmed')
            ->where('disbursement_status', 'disbursed')
            ->sum('amount');

        $disbursedThisMonth = Disbursement::where('status', 'processed')
            ->whereMonth('processed_at', now()->month)
            ->whereYear('processed_at', now()->year)
            ->sum('total_amount');

        // ============================================================
        // STATISTIK PENDAPATAN PLATFORM DARI FEE
        // ============================================================
        $platformFeeTotal = Disbursement::where('status', 'processed')
            ->sum('fee_amount');

        $platformFeeThisMonth = Disbursement::where('status', 'processed')
            ->whereMonth('processed_at', now()->month)
            ->whereYear('processed_at', now()->year)
            ->sum('fee_amount');

        $platformFeeLastMonth = Disbursement::where('status', 'processed')
            ->whereMonth('processed_at', now()->subMonth()->month)
            ->whereYear('processed_at', now()->subMonth()->year)
            ->sum('fee_amount');

        $platformFeeChangePercent = $platformFeeLastMonth > 0
            ? round((($platformFeeThisMonth - $platformFeeLastMonth) / $platformFeeLastMonth) * 100)
            : 0;

        $platformFeeThisYear = Disbursement::where('status', 'processed')
            ->whereYear('processed_at', now()->year)
            ->sum('fee_amount');

        // ============================================================
        // HOLDING PER TEMPAT KOS (prioritas pencairan)
        // ============================================================
        $holdingPerKos = Payment::withoutTempatKosScope()
            ->with('tempatKos:id,nama_kos')
            ->where('status', 'confirmed')
            ->where('disbursement_status', 'holding')
            ->select(
                'tempat_kos_id',
                DB::raw('SUM(amount) as total_holding'),
                DB::raw('COUNT(*) as payment_count'),
                DB::raw('MIN(verified_at) as oldest_holding')
            )
            ->groupBy('tempat_kos_id')
            ->orderByDesc('total_holding')
            ->take(5)
            ->get();

        // ============================================================
        // DISBURSEMENT TERBARU
        // ============================================================
        $recentDisbursements = Disbursement::with(['tempatKos', 'admin'])
            ->latest()
            ->take(5)
            ->get();

        // ============================================================
        // LIST TEMPAT KOS
        // ============================================================
        $tempatKosList = TempatKos::withCount([
            'rooms',
            'admins',
            'rooms as penghuni_count' => function ($q) {
                $q->whereHas('rents', fn($r) => $r->where('status', 'active'));
            }
        ])->latest()->paginate(10);

        // ============================================================
        // RECENT ACTIVITIES
        // ============================================================
        $recentUsers = User::where('role', 'user')
            ->with('tempatKos')
            ->latest()
            ->take(5)
            ->get();

        $recentAdmins = User::where('role', 'admin')
            ->with('tempatKos')
            ->latest()
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact(
            // Statistik umum
            'totalTempatKos',
            'totalTempatKosAktif',
            'totalAdmin',
            'totalUser',
            'totalKamar',
            'totalKamarTerisi',
            'occupancyRate',
            // Dana holding
            'totalHoldingAmount',
            'totalHoldingCount',
            'holdingThisMonth',
            'totalDisbursedAmount',
            'disbursedThisMonth',
            // Pendapatan platform dari fee
            'platformFeeTotal',
            'platformFeeThisMonth',
            'platformFeeLastMonth',
            'platformFeeChangePercent',
            'platformFeeThisYear',
            // List & activities
            'holdingPerKos',
            'recentDisbursements',
            'tempatKosList',
            'recentUsers',
            'recentAdmins'
        ));
    }
}