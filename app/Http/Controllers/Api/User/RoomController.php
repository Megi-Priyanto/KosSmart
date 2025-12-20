<?php
// app/Http/Controllers/Api/User/RoomController.php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Room;
use App\Models\KosInfo;
use Illuminate\Http\Request;

class RoomController extends BaseApiController
{
    /**
     * List Kamar Tersedia
     */
    public function index(Request $request)
    {
        $query = Room::with('kosInfo')->available();

        // Filter type
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        // Filter floor
        if ($request->filled('floor')) {
            $query->onFloor($request->floor);
        }

        // Filter max price
        if ($request->filled('max_price')) {
            $query->priceRange(null, $request->max_price);
        }

        // Sort
        $sortBy = $request->get('sort', 'room_number');
        $query->orderBy($sortBy, 'asc');

        // Pagination
        $perPage = $request->get('per_page', 10);
        $rooms = $query->paginate($perPage);

        // Transform data
        $data = $rooms->map(function ($room) {
            return [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'type' => $room->type,
                'floor' => $room->floor,
                'price' => $room->price,
                'size' => $room->size,
                'capacity' => $room->capacity,
                'description' => $room->description,
                'facilities' => $room->facilities,
                'images' => $room->images,
                'status' => $room->status,
                'view_count' => $room->view_count,
            ];
        });

        return $this->successResponse([
            'rooms' => $data,
            'pagination' => [
                'current_page' => $rooms->currentPage(),
                'last_page' => $rooms->lastPage(),
                'per_page' => $rooms->perPage(),
                'total' => $rooms->total(),
            ],
        ]);
    }

    /**
     * Detail Kamar
     */
    public function show($id)
    {
        $room = Room::with('kosInfo')->find($id);

        if (!$room) {
            return $this->notFoundResponse('Kamar tidak ditemukan');
        }

        if (!$room->isAvailable()) {
            return $this->errorResponse('Kamar tidak tersedia', null, 400);
        }

        // Increment view count
        $room->incrementViewCount();

        // Related rooms
        $relatedRooms = Room::available()
            ->where('id', '!=', $room->id)
            ->where('type', $room->type)
            ->limit(3)
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'room_number' => $r->room_number,
                    'price' => $r->price,
                    'images' => $r->images,
                ];
            });

        return $this->successResponse([
            'room' => [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'type' => $room->type,
                'floor' => $room->floor,
                'price' => $room->price,
                'size' => $room->size,
                'capacity' => $room->capacity,
                'description' => $room->description,
                'facilities' => $room->facilities,
                'images' => $room->images,
                'status' => $room->status,
                'view_count' => $room->view_count,
                'kos_info' => [
                    'name' => $room->kosInfo->name ?? null,
                    'address' => $room->kosInfo->address ?? null,
                ],
            ],
            'related_rooms' => $relatedRooms,
        ]);
    }

    /**
     * Get Kos Info
     */
    public function kosInfo()
    {
        $kosInfo = KosInfo::with('rooms')->first();

        if (!$kosInfo) {
            return $this->notFoundResponse('Info kos tidak ditemukan');
        }

        $totalRooms = $kosInfo->rooms->count();
        $availableRooms = $kosInfo->rooms->filter(fn($room) => $room->isAvailable())->count();

        return $this->successResponse([
            'name' => $kosInfo->name,
            'address' => $kosInfo->address,
            'description' => $kosInfo->description,
            'facilities' => $kosInfo->facilities,
            'rules' => $kosInfo->rules,
            'contact_phone' => $kosInfo->contact_phone,
            'contact_email' => $kosInfo->contact_email,
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
        ]);
    }
}