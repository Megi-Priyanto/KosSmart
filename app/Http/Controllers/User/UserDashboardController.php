<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\TempatKos;
use App\Models\Payment;
use App\Models\Rent;
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
        // User dengan status:
        // - active
        // - checkout_requested
        // tetap dianggap tenant (masih bisa akses dashboard)
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

            // Riwayat pembayaran (5 terakhir)
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
        // USER BELUM/TIDAK PUNYA KAMAR LAGI
        // (Termasuk setelah checkout disetujui)
        // ==========================

        // Cek booking pending
        $pendingRent = $user->rents()
            ->where('status', 'pending')
            ->with('room')
            ->latest()
            ->first();

        // Query tempat kos aktif
        $query = TempatKos::withCount([
            'rooms as total_kamar',
            'rooms as kamar_tersedia' => function ($q) {
                $q->where('status', 'available');
            }
        ])->where('status', 'active');

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

        // Daftar kota untuk dropdown filter
        $kotaList = TempatKos::where('status', 'active')
            ->select('kota')
            ->distinct()
            ->orderBy('kota')
            ->pluck('kota');

        // PENTING: Pastikan menggunakan view yang benar
        return view(
            'user.dashboard-empty',
            compact('tempatKos', 'kotaList', 'pendingRent')
        );
    }

    /**
     * Display room detail for active tenant
     */
    public function roomDetail()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil sewa aktif / checkout_requested
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

        // GUNAKAN VIEW YANG SESUAI DENGAN FILE DI PROJECT ANDA
        return view(
            'user.rooms.room-detail', // ← SESUAIKAN dengan nama file view Anda
            [
                'room'       => $activeRent->room,
                'activeRent' => $activeRent,
            ]
        );
    }
}