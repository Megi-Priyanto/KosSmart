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
        $query = Room::with('kosInfo')->available();

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
        $kosInfo = KosInfo::with('rooms')->first();

        // Hitung total kamar dan tersedia
        $totalRooms = $kosInfo->rooms->count();
        $availableRooms = $kosInfo->rooms->filter(function ($room) {
            return $room->isAvailable();
        })->count();

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
        if (!$room->isAvailable()) {
            return redirect()->route('user.rooms.index')
                ->with('error', 'Kamar tidak tersedia');
        }

        $room->incrementViewCount();
        $room->load('kosInfo');

        $relatedRooms = Room::available()
            ->where('id', '!=', $room->id)
            ->where('type', $room->type)
            ->limit(3)
            ->get();

        $kosInfo = \App\Models\KosInfo::first();

        return view('user.rooms.show', compact('room', 'relatedRooms', 'kosInfo'));
    }
}