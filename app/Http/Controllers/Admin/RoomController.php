<?php
// app/Http/Controllers/Admin/RoomController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\KosInfo;
use App\Models\Room;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of rooms (admin)
     */
    public function index(Request $request)
    {
        $query = Room::with('currentRent.user');

        // Filter by status
        if ($request->filled('status')) {
            $query->withStatus($request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        // Filter by floor
        if ($request->filled('floor')) {
            $query->onFloor($request->floor);
        }

        // Search by room number
        if ($request->filled('search')) {
            $query->where('room_number', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sortBy = $request->get('sort', 'room_number');
        $sortOrder = $request->get('order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $rooms = $query->paginate(15)->withQueryString();

        // Data untuk filter
        $types = Room::select('type')->distinct()->pluck('type');
        $floors = Room::select('floor')->distinct()->orderBy('floor')->pluck('floor');
        $statuses = ['available', 'occupied', 'maintenance'];

        // Statistik
        $stats = [
            'total' => Room::count(),
            'available' => Room::where('status', 'available')->count(),
            'occupied' => Room::where('status', 'occupied')->count(),
            'maintenance' => Room::where('status', 'maintenance')->count(),
        ];

        return view('admin.rooms.index', compact('rooms', 'types', 'floors', 'statuses', 'stats'));
    }

    /**
     * Show form to create room
     */
    public function create()
    {
        $kosInfo = KosInfo::first();

        return view('admin.rooms.create', compact('kosInfo'));
    }

    /**
     * Store a newly created room
     */
    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();

        // Set kos_info_id
        $kosInfo = KosInfo::first();
        if ($kosInfo) {
            $data['kos_info_id'] = $kosInfo->id;
        }

        // Handle image upload
        if ($request->hasFile('images')) {
            $data['images'] = $this->imageService->uploadMultiple(
                $request->file('images'),
                'rooms'
            );
        }

        Room::create($data);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan');
    }

    /**
     * Display the specified room
     */
    public function show(Room $room)
    {
        $room->load([
            'currentRent.user',
            'rents' => function ($query) {
                $query->latest()->limit(5);
            }
        ]);

        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show form to edit room
     */
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Update the specified room
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $data = $request->validated();

        // Handle new image upload
        if ($request->hasFile('images')) {
            $newImages = $this->imageService->uploadMultiple(
                $request->file('images'),
                'rooms'
            );

            // Merge dengan gambar lama
            $existingImages = $room->images ?? [];
            $data['images'] = array_merge($existingImages, $newImages);
        }

        // Handle image removal
        if ($request->has('remove_images')) {
            $removeIndices = $request->input('remove_images', []);
            $existingImages = $room->images ?? [];

            foreach ($removeIndices as $index) {
                if (isset($existingImages[$index])) {
                    $this->imageService->delete($existingImages[$index]);
                    unset($existingImages[$index]);
                }
            }

            $data['images'] = array_values($existingImages);
        }

        $room->update($data);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Data kamar berhasil diperbarui');
    }

    /**
     * Remove the specified room
     */
    public function destroy(Room $room)
    {
        // Cek apakah kamar sedang disewa
        if ($room->currentRent()->exists()) {
            return back()->with('error', 'Kamar tidak dapat dihapus karena sedang disewa');
        }

        // Delete images
        if ($room->images) {
            $this->imageService->deleteMultiple($room->images);
        }

        $roomNumber = $room->room_number;
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', "Kamar {$roomNumber} berhasil dihapus");
    }
}
