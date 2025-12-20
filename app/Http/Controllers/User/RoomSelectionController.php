<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\KosInfo;
use Illuminate\Http\Request;

class RoomSelectionController extends Controller
{
    /**
     * Tampilkan daftar kamar kosong
     */

    public function index(Request $request)
    {
        $query = Room::with(['kosInfo' => function ($q) {
            $q->where('is_active', true);
        }])->available();

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

        // Ambil kos info beserta relasi rooms
        $kosInfo = KosInfo::where('is_active', true)
            ->with('rooms')
            ->first();

        $totalRooms = 0;
        $availableRooms = 0;

        if ($kosInfo) {
            $totalRooms = $kosInfo->rooms->count();
            $availableRooms = $kosInfo->rooms
                ->filter(fn($room) => $room->isAvailable())
                ->count();
        }

        return view('user.rooms.index', compact(
            'rooms',
            'types',
            'floors',
            'kosInfo',
            'totalRooms',
            'availableRooms'
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
                $q->where('is_active', true);
            }
        ]);

        // kos belum aktif → redirect
        if (!$room->kosInfo) {
            return redirect()->route('user.rooms.index')
                ->with('error', 'Kos belum diaktifkan oleh admin');
        }

        $kosInfo = $room->kosInfo;

        // definisikan relatedRooms
        $relatedRooms = Room::where('kos_info_id', $room->kos_info_id)
            ->where('id', '!=', $room->id)
            ->available()
            ->limit(3)
            ->get();

        // Kirim ke view
        return view('user.rooms.show', compact(
            'room',
            'kosInfo',
            'relatedRooms'
        ));
    }
}
