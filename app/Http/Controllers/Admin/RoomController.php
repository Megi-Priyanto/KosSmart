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
     * Display a listing of rooms
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

        // Data for filters
        $types = Room::select('type')->distinct()->pluck('type');
        $floors = Room::select('floor')->distinct()->orderBy('floor')->pluck('floor');
        $statuses = ['available', 'occupied', 'maintenance'];

        // Statistics
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
        return view('admin.rooms.create');
    }

    /**
     * Store a newly created room
     */
    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();

        // Assign kos_info_id from first KosInfo
        $data['kos_info_id'] = KosInfo::first()->id;

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $data['images'] = $this->imageService->uploadMultiple(
                $request->file('images'),
                'rooms'
            );
        } else {
            $data['images'] = [];
        }

        $room = Room::create($data);

        return redirect()->route('admin.rooms.index')
            ->with('success', "Kamar {$room->room_number} berhasil ditambahkan dengan " . count($data['images']) . " foto");
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

        // Get existing images
        $existingImages = is_array($room->images) 
            ? $room->images 
            : (json_decode($room->images ?? '[]', true) ?? []);

        $deletedCount = 0;
        $addedCount = 0;

        // Handle image removal first
        if ($request->has('remove_images')) {
            $removeIndices = $request->input('remove_images', []);
            
            foreach ($removeIndices as $index) {
                if (isset($existingImages[$index])) {
                    // Delete from storage
                    $this->imageService->delete($existingImages[$index]);
                    unset($existingImages[$index]);
                    $deletedCount++;
                }
            }
            
            // Reindex array
            $existingImages = array_values($existingImages);
        }

        // Handle new image upload
        if ($request->hasFile('images')) {
            $newImages = $this->imageService->uploadMultiple(
                $request->file('images'),
                'rooms'
            );
            
            $addedCount = count($newImages);
            
            // Merge with existing images
            $existingImages = array_merge($existingImages, $newImages);
            
            // Limit to 10 images max
            if (count($existingImages) > 10) {
                // Remove excess images and delete from storage
                $excessImages = array_slice($existingImages, 10);
                $this->imageService->deleteMultiple($excessImages);
                $existingImages = array_slice($existingImages, 0, 10);
            }
        }

        $data['images'] = $existingImages;

        $room->update($data);

        $message = "Data kamar {$room->room_number} berhasil diperbarui";
        if ($deletedCount > 0) {
            $message .= " ({$deletedCount} foto dihapus";
            if ($addedCount > 0) {
                $message .= ", {$addedCount} foto ditambahkan)";
            } else {
                $message .= ")";
            }
        } elseif ($addedCount > 0) {
            $message .= " ({$addedCount} foto ditambahkan)";
        }

        return redirect()->route('admin.rooms.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified room
     */
    public function destroy(Room $room)
    {
        // Check if room is currently rented
        if ($room->currentRent()->exists()) {
            return back()->with('error', 'Kamar tidak dapat dihapus karena sedang disewa oleh ' . $room->currentRent->user->name);
        }

        // Delete all images
        if (!empty($room->images)) {
            $images = is_array($room->images) 
                ? $room->images 
                : json_decode($room->images ?? '[]', true);
            
            $this->imageService->deleteMultiple($images);
        }

        $roomNumber = $room->room_number;
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', "Kamar {$roomNumber} dan semua foto terkait berhasil dihapus");
    }
}