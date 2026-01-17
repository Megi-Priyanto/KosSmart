<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\KosInfo;
use App\Models\TempatKos;
use Illuminate\Http\Request;

class RoomSelectionController extends Controller
{
    /**
     * Tampilkan daftar kamar kosong
     * MODIFIED: Support filter by tempat_kos_id
     */
    public function index(Request $request)
    {
        $query = Room::with(['kosInfo' => function ($q) {
            $q->where('is_active', true);
        }])->available();

        // FILTER BY TEMPAT KOS (Parameter baru dari dashboard)
        $tempatKos = null;
        if ($request->filled('tempat_kos_id')) {
            $tempatKosId = $request->tempat_kos_id;

            // Load tempat kos untuk ditampilkan di header
            $tempatKos = TempatKos::where('id', $tempatKosId)
                ->where('status', 'active')
                ->first();

            // Filter rooms berdasarkan tempat_kos_id
            if ($tempatKos) {
                $query->whereHas('kosInfo', function ($q) use ($tempatKosId) {
                    $q->where('tempat_kos_id', $tempatKosId);
                });
            }
        }

        // Filter existing
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        if ($request->filled('floor')) {
            $query->onFloor($request->floor);
        }

        if ($request->filled('max_price')) {
            $query->priceRange(null, $request->max_price);
        }

        $sortBy = $request->get('sort', 'room_number');
        $query->orderBy($sortBy, 'asc');

        $rooms = $query->paginate(9)->withQueryString();

        $types = Room::select('type')->distinct()->pluck('type');
        $floors = Room::select('floor')->distinct()->orderBy('floor')->pluck('floor');

        $totalRooms = 0;
        $availableRooms = 0;
        $occupiedRooms = 0;

        if ($tempatKos) {
            $baseRoomQuery = Room::whereHas('kosInfo', function ($q) use ($tempatKos) {
                $q->where('tempat_kos_id', $tempatKos->id);
            });

            // TOTAL SEMUA KAMAR
            $totalRooms = (clone $baseRoomQuery)->count();

            // KAMAR TERSEDIA
            $availableRooms = (clone $baseRoomQuery)
                ->where('status', 'available')
                ->count();

            // KAMAR TERISI
            $occupiedRooms = (clone $baseRoomQuery)
                ->where('status', 'occupied')
                ->count();
        }

        return view('user.rooms.index', compact(
            'rooms',
            'types',
            'floors',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'tempatKos'
        ));
    }

    public function show(Room $room)
    {
        // Cek ketersediaan kamar
        if (!$room->isAvailable()) {
            return redirect()->route('user.rooms.index')
                ->with('error', 'Kamar tidak tersedia');
        }

        // Tambah view count
        $room->incrementViewCount();

        // Load kosInfo yang AKTIF saja
        $room->load([
            'kosInfo' => function ($q) {
                $q->where('is_active', true)->with('tempatKos');
            }
        ]);

        // kos belum aktif → redirect
        if (!$room->kosInfo) {
            return redirect()->route('user.rooms.index')
                ->with('error', 'Kos belum diaktifkan oleh admin');
        }

        $kosInfo = $room->kosInfo;
        $tempatKos = $kosInfo->tempatKos;

        // Definisikan relatedRooms (kamar lain dari tempat kos yang sama)
        $relatedRooms = Room::where('kos_info_id', $room->kos_info_id)
            ->where('id', '!=', $room->id)
            ->available()
            ->limit(3)
            ->get();

        // Kirim ke view
        return view('user.rooms.show', compact(
            'room',
            'kosInfo',
            'tempatKos',
            'relatedRooms'
        ));
    }
}
