<?php

namespace App\Http\Controllers;

use App\Models\TempatKos;
use App\Models\Room;
use App\Models\KosInfo;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class PublicKosController extends Controller
{
    /**
     * Daftar semua tempat kos aktif (publik, tanpa login)
     */
    public function index(Request $request)
    {
        $query = TempatKos::where('status', 'active')
            ->with([
                'kosInfos' => function ($q) {
                    $q->where('is_active', true);
                },
                'rooms',
                'ulasan' => function ($q) {
                    $q->where('is_visible', true);
                }
            ]);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_kos', 'like', '%' . $request->search . '%')
                  ->orWhere('kota', 'like', '%' . $request->search . '%')
                  ->orWhere('alamat', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kota')) {
            $query->where('kota', $request->kota);
        }

        $tempatKosList = $query->latest()->get();

        // Hitung rata-rata rating untuk setiap kos
        $tempatKosList->each(function ($kos) {
            $ulasan = $kos->ulasan->where('is_visible', true);
            $kos->avg_rating = $ulasan->count() > 0
                ? round($ulasan->avg('rating'), 1)
                : null;
            $kos->total_ulasan = $ulasan->count();
        });

        $kotaList = TempatKos::where('status', 'active')
            ->whereNotNull('kota')
            ->distinct()
            ->pluck('kota');

        return view('public.kos.index', compact('tempatKosList', 'kotaList'));
    }

    /**
     * Daftar kamar dalam satu tempat kos (publik)
     */
    public function rooms(Request $request, TempatKos $tempatKos)
    {
        if ($tempatKos->status !== 'active') {
            return redirect()->route('public.kos.index')
                ->with('error', 'Tempat kos ini tidak tersedia.');
        }

        $query = Room::whereHas('kosInfo', function ($q) use ($tempatKos) {
            $q->where('tempat_kos_id', $tempatKos->id)->where('is_active', true);
        })->with('kosInfo');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // ── PERUBAHAN 1: tambah filter floor ──────────────────────
        if ($request->filled('floor')) {
            $query->where('floor', $request->floor);
        }
        // ──────────────────────────────────────────────────────────

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $query->orderByRaw("FIELD(status, 'available', 'occupied', 'maintenance')")
              ->orderBy('room_number', 'asc');

        $rooms = $query->paginate(9)->withQueryString();

        $types  = Room::whereHas('kosInfo', fn($q) => $q->where('tempat_kos_id', $tempatKos->id))
                      ->distinct()->pluck('type');

        // ── PERUBAHAN 2: tambah variabel $floors ──────────────────
        $floors = Room::whereHas('kosInfo', fn($q) => $q->where('tempat_kos_id', $tempatKos->id))
                      ->distinct()->orderBy('floor')->pluck('floor');
        // ──────────────────────────────────────────────────────────

        $totalRooms       = Room::whereHas('kosInfo', fn($q) => $q->where('tempat_kos_id', $tempatKos->id))->count();
        $availableRooms   = Room::whereHas('kosInfo', fn($q) => $q->where('tempat_kos_id', $tempatKos->id))->where('status', 'available')->count();
        $occupiedRooms    = Room::whereHas('kosInfo', fn($q) => $q->where('tempat_kos_id', $tempatKos->id))->where('status', 'occupied')->count();
        $maintenanceRooms = Room::whereHas('kosInfo', fn($q) => $q->where('tempat_kos_id', $tempatKos->id))->where('status', 'maintenance')->count();

        // Rating & ulasan untuk tempat kos ini
        $ulasanList = Ulasan::where('tempat_kos_id', $tempatKos->id)
            ->where('is_visible', true)
            ->with('user')
            ->latest()
            ->get();

        $avgRating   = $ulasanList->count() > 0 ? round($ulasanList->avg('rating'), 1) : null;
        $totalUlasan = $ulasanList->count();

        // Distribusi rating (berapa banyak tiap bintang)
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $ulasanList->where('rating', $i)->count();
            $ratingDistribution[$i] = [
                'count'   => $count,
                'percent' => $totalUlasan > 0 ? round(($count / $totalUlasan) * 100) : 0,
            ];
        }

        return view('public.kos.rooms', compact(
            'tempatKos', 'rooms', 'types',
            'floors', // ── PERUBAHAN 3: tambah 'floors' ke compact ──
            'totalRooms', 'availableRooms', 'occupiedRooms', 'maintenanceRooms',
            'ulasanList', 'avgRating', 'totalUlasan', 'ratingDistribution'
        ));
    }

    /**
     * Detail kamar (publik) — tombol booking redirect ke login
     */
    public function roomDetail(Room $room)
    {
        $room->load(['kosInfo' => function ($q) {
            $q->where('is_active', true)->with('tempatKos');
        }]);

        if (!$room->kosInfo) {
            return redirect()->route('public.kos.index')
                ->with('error', 'Kamar ini tidak tersedia saat ini.');
        }

        $kosInfo   = $room->kosInfo;
        $tempatKos = $kosInfo->tempatKos;

        $relatedRooms = Room::where('kos_info_id', $room->kos_info_id)
            ->where('id', '!=', $room->id)
            ->where('status', 'available')
            ->limit(3)
            ->get();

        return view('public.kos.room-detail', compact(
            'room', 'kosInfo', 'tempatKos', 'relatedRooms'
        ));
    }
}