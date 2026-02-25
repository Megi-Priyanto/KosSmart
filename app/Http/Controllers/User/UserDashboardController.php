<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TempatKos;
use App\Models\Payment;
use App\Models\Rent;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Display user dashboard
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ==========================
        // CEK STATUS SEWA USER
        // ==========================
        $activeRent = Rent::tenant()
            ->where('user_id', $user->id)
            ->whereHas(
                'room',
                fn($q) =>
                $q->where('status', 'occupied')
            )
            ->with(['room', 'room.kosInfo', 'billings'])
            ->latest()
            ->first();

        // Jika user masih punya kamar AKTIF
        if ($activeRent) {
            $paymentHistory = Payment::where('user_id', $user->id)
                ->with('billing')
                ->latest()
                ->take(5)
                ->get();

            return view(
                'user.dashboard-tenant',
                compact('activeRent', 'paymentHistory')
            );
        }

        // ==========================
        // USER BELUM/TIDAK PUNYA KAMAR
        // ==========================

        // Cek booking pending
        $pendingRent = $user->rents()
            ->where('status', 'pending')
            ->with('room')
            ->latest()
            ->first();

        // ==========================
        // CEK APAKAH ADA BILLING YANG PERLU DIULAS
        // (billing paid, tipe pelunasan/bulanan, belum pernah review rent-nya)
        // ==========================
        $billingPerluDiulas = null;
        $reviewedRentIds = Ulasan::where('user_id', $user->id)->pluck('rent_id')->toArray();

        $billingPerluDiulas = \App\Models\Billing::where('user_id', $user->id)
            ->where('status', 'paid')
            ->whereIn('tipe', ['pelunasan', 'bulanan'])
            ->whereNotIn('rent_id', $reviewedRentIds)
            ->with(['room.kosInfo.tempatKos'])
            ->latest('updated_at')
            ->first();

        // Ambil ulasan terbaru untuk semua kos (untuk tampil di dashboard)
        $ulasanTerbaru = Ulasan::where('is_visible', true)
            ->with(['user', 'tempatKos'])
            ->latest()
            ->take(6)
            ->get();

        // Query tempat kos aktif
        $query = TempatKos::withCount([
            'rooms as total_kamar',
            'rooms as kamar_tersedia' => function ($q) {
                $q->where('status', 'available');
            }
        ])
        ->with(['ulasan' => function ($q) {
            $q->where('is_visible', true);
        }])
        ->where('status', 'active');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kos', 'like', "%{$search}%")
                    ->orWhere('kota', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Filter kota
        if ($request->filled('kota')) {
            $query->where('kota', $request->kota);
        }

        $tempatKos = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Hitung avg rating per kos
        $tempatKos->each(function ($kos) {
            $ulasan = $kos->ulasan->where('is_visible', true);
            $kos->avg_rating   = $ulasan->count() > 0 ? round($ulasan->avg('rating'), 1) : null;
            $kos->total_ulasan = $ulasan->count();
        });

        // Daftar kota untuk dropdown filter
        $kotaList = TempatKos::where('status', 'active')
            ->select('kota')
            ->distinct()
            ->orderBy('kota')
            ->pluck('kota');

        return view(
            'user.dashboard-empty',
            compact('tempatKos', 'kotaList', 'pendingRent', 'billingPerluDiulas', 'ulasanTerbaru')
        );
    }

    /**
     * Display room detail for active tenant
     */
    public function roomDetail()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $activeRent = Rent::tenant()
            ->where('user_id', $user->id)
            ->whereHas(
                'room',
                fn($q) => $q->where('status', 'occupied')
            )
            ->with(['room', 'room.kosInfo', 'billings'])
            ->latest()
            ->first();

        if (!$activeRent) {
            return redirect()
                ->route('user.dashboard')
                ->with('error', 'Anda tidak memiliki kamar aktif');
        }

        return view(
            'user.rooms.room-detail',
            [
                'room'       => $activeRent->room,
                'activeRent' => $activeRent,
            ]
        );
    }
}
