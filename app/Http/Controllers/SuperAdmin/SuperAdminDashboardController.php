<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TempatKos;
use App\Models\User;
use App\Models\Room;
use App\Models\Rent;
use App\Models\AdminBilling;
use Illuminate\Http\Request;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        // Statistik Global
        $totalTempatKos = TempatKos::count();
        $totalTempatKosAktif = TempatKos::where('status', 'active')->count();

        $totalAdmin = User::where('role', 'admin')->count();
        $totalUser = User::where('role', 'user')->count();

        // Total kamar & occupancy (dari semua tempat kos)
        $totalKamar = Room::withoutTempatKosScope()->count();
        $totalKamarTerisi = Room::withoutTempatKosScope()
            ->where('status', 'occupied')
            ->count();

        $occupancyRate = $totalKamar > 0
            ? round(($totalKamarTerisi / $totalKamar) * 100, 1)
            : 0;

        // FIX: Pendapatan bulan ini dari tagihan admin yang sudah dibayar (status 'paid')
        $monthlyIncome = AdminBilling::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        // List tempat kos dengan statistik
        $tempatKosList = TempatKos::withCount([
            'rooms',
            'admins',
            'rooms as penghuni_count' => function ($q) {
                $q->whereHas('rents', function ($r) {
                    $r->where('status', 'active');
                });
            }
        ])->latest()->paginate(10);

        // Recent activities
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
            'totalTempatKos',
            'totalTempatKosAktif',
            'totalAdmin',
            'totalUser',
            'totalKamar',
            'totalKamarTerisi',
            'occupancyRate',
            'monthlyIncome',
            'tempatKosList',
            'recentUsers',
            'recentAdmins'
        ));
    }
}