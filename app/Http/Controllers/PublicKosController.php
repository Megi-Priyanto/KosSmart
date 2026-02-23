<?php

namespace App\Http\Controllers;

use App\Models\TempatKos;
use App\Models\Room;
use App\Models\KosInfo;
use Illuminate\Http\Request;

class PublicKosController extends Controller
{
    /**
     * Daftar semua tempat kos aktif (publik, tanpa login)
     */
    public function index(Request $request)
    {
        $query = TempatKos::where('status', 'active')
            ->with(['kosInfos' => function ($q) {
                $q->where('is_active', true);
            }, 'rooms']);

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

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $query->orderByRaw("FIELD(status, 'available', 'occupied', 'maintenance')")
              ->orderBy('room_number', 'asc');

        $rooms = $query->paginate(9)->withQueryString();

        $types  = Room::whereHas('kosInfo', fn($q) => $q->where('tempat_kos_id', $tempatKos->id))
                      ->distinct()->pluck('type');

        $totalRooms       = Room::whereHas('kosInfo', fn($q) => $q->where('tempat_kos_id', $tempatKos->id))->count();
        $availableRooms   = Room::whereHas('kosInfo', fn($q) => $q->where('tempat_kos_id', $tempatKos->id))->where('status', 'available')->count();
        $occupiedRooms    = Room::whereHas('kosInfo', fn($q) => $q->where('tempat_kos_id', $tempatKos->id))->where('status', 'occupied')->count();
        $maintenanceRooms = Room::whereHas('kosInfo', fn($q) => $q->where('tempat_kos_id', $tempatKos->id))->where('status', 'maintenance')->count();

        return view('public.kos.rooms', compact(
            'tempatKos', 'rooms', 'types',
            'totalRooms', 'availableRooms', 'occupiedRooms', 'maintenanceRooms'
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