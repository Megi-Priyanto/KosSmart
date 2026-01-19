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
     * Tampilkan daftar kamar - SEMUA STATUS
     * PERUBAHAN: Tidak filter available saja, tampilkan semua
     */
    public function index(Request $request)
    {
        // HAPUS ->available() agar semua kamar muncul
        $query = Room::with(['kosInfo' => function ($q) {
            $q->where('is_active', true);
        }]);

        // FILTER BY TEMPAT KOS
        $tempatKos = null;
        if ($request->filled('tempat_kos_id')) {
            $tempatKosId = $request->tempat_kos_id;

            $tempatKos = TempatKos::where('id', $tempatKosId)
                ->where('status', 'active')
                ->first();

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

        // Sort berdasarkan status (available dulu, baru occupied/maintenance)
        $sortBy = $request->get('sort', 'status');
        $query->orderByRaw("FIELD(status, 'available', 'occupied', 'maintenance')")
            ->orderBy('room_number', 'asc');

        $rooms = $query->paginate(9)->withQueryString();

        $types = Room::select('type')->distinct()->pluck('type');
        $floors = Room::select('floor')->distinct()->orderBy('floor')->pluck('floor');

        $totalRooms = 0;
        $availableRooms = 0;
        $occupiedRooms = 0;
        $maintenanceRooms = 0;

        if ($tempatKos) {
            $baseRoomQuery = Room::whereHas('kosInfo', function ($q) use ($tempatKos) {
                $q->where('tempat_kos_id', $tempatKos->id);
            });

            $totalRooms = (clone $baseRoomQuery)->count();
            $availableRooms = (clone $baseRoomQuery)->where('status', 'available')->count();
            $occupiedRooms = (clone $baseRoomQuery)->where('status', 'occupied')->count();
            $maintenanceRooms = (clone $baseRoomQuery)->where('status', 'maintenance')->count();
        }

        return view('user.rooms.index', compact(
            'rooms',
            'types',
            'floors',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'tempatKos'
        ));
    }

    public function show(Room $room)
    {
        // Tambah view count
        $room->incrementViewCount();

        $room->load([
            'kosInfo' => function ($q) {
                $q->where('is_active', true)->with('tempatKos');
            }
        ]);

        if (!$room->kosInfo) {
            $tempatKosId = $room->tempat_kos_id;

            return redirect()->route('user.rooms.index', ['tempat_kos_id' => $tempatKosId])
                ->with('error', 'Kos belum diaktifkan oleh admin. Silakan pilih kamar lain atau hubungi admin.');
        }

        $kosInfo = $room->kosInfo;
        $tempatKos = $kosInfo->tempatKos;

        // Related rooms juga tampilkan semua status
        $relatedRooms = Room::where('kos_info_id', $room->kos_info_id)
            ->where('id', '!=', $room->id)
            ->orderByRaw("FIELD(status, 'available', 'occupied', 'maintenance')")
            ->limit(3)
            ->get();

        // Hitung statistik kamar untuk ditampilkan di header
        $baseRoomQuery = Room::whereHas('kosInfo', function ($q) use ($tempatKos) {
            $q->where('tempat_kos_id', $tempatKos->id);
        });

        $totalRooms = (clone $baseRoomQuery)->count();
        $availableRooms = (clone $baseRoomQuery)->where('status', 'available')->count();
        $occupiedRooms = (clone $baseRoomQuery)->where('status', 'occupied')->count();
        $maintenanceRooms = (clone $baseRoomQuery)->where('status', 'maintenance')->count();

        return view('user.rooms.show', compact(
            'room',
            'kosInfo',
            'tempatKos',
            'relatedRooms',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms' 
        ));
    }
}
