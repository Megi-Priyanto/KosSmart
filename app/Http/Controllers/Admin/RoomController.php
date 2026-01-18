<?php

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
     * 
     * Global Scope otomatis filter berdasarkan tempat_kos_id
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Query builder
        if ($user->isSuperAdmin()) {
            $query = Room::withoutTempatKosScope()->with('currentRent.user');
        } else {
            $query = Room::with('currentRent.user');
        }

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

        // Data for filters (otomatis filtered)
        $typesQuery = $user->isSuperAdmin()
            ? Room::withoutTempatKosScope()
            : Room::query();
        $types = $typesQuery->select('type')->distinct()->pluck('type');

        $floorsQuery = $user->isSuperAdmin()
            ? Room::withoutTempatKosScope()
            : Room::query();
        $floors = $floorsQuery->select('floor')->distinct()->orderBy('floor')->pluck('floor');

        $statuses = ['available', 'occupied', 'maintenance'];

        // Statistics (otomatis filtered)
        $statsQuery = $user->isSuperAdmin()
            ? Room::withoutTempatKosScope()
            : Room::query();

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'available' => (clone $statsQuery)->where('status', 'available')->count(),
            'occupied' => (clone $statsQuery)->where('status', 'occupied')->count(),
            'maintenance' => (clone $statsQuery)->where('status', 'maintenance')->count(),
        ];

        return view('admin.rooms.index', compact('rooms', 'types', 'floors', 'statuses', 'stats'));
    }

    /**
     * Show form to create room
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Cek apakah kos_info sudah ada untuk tempat kos ini
        $hasKosInfo = KosInfo::where('tempat_kos_id', $user->tempat_kos_id)->exists();

        if (!$hasKosInfo) {
            return redirect()
                ->route('admin.kos.edit', $user->tempat_kos_id)
                ->with('error', 'Lengkapi data Informasi Kos terlebih dahulu sebelum menambahkan kamar.');
        }

        return view('admin.rooms.create');
    }

    /**
     * Store a newly created room
     * 
     * tempat_kos_id otomatis terisi via trait
     */
    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();

        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Ambil kos_info SESUAI tempat_kos user
        $kosInfo = KosInfo::where('tempat_kos_id', $user->tempat_kos_id)->first();

        // JIKA BELUM ADA → STOP DENGAN PESAN JELAS
        if (!$kosInfo) {
            return redirect()
                ->route('admin.kos.edit', $user->tempat_kos_id)
                ->with('error', 'Lengkapi data Informasi Kos terlebih dahulu sebelum menambahkan kamar.');
        }

        // Assign kos_info_id
        $data['kos_info_id'] = $kosInfo->id;

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $data['images'] = $this->imageService->uploadMultiple(
                $request->file('images'),
                'rooms'
            );
        } else {
            $data['images'] = [];
        }

        // tempat_kos_id otomatis terisi via trait
        $room = Room::create($data);

        return redirect()->route('admin.rooms.index')
            ->with('success', "Kamar {$room->room_number} berhasil ditambahkan dengan " . count($data['images']) . " foto");
    }

    /**
     * Display the specified room
     * 
     * Model binding otomatis ter-filter
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
     * 
     * Model binding otomatis ter-filter
     */
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Update the specified room
     * 
     * Model binding otomatis ter-filter
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
                    $this->imageService->delete($existingImages[$index]);
                    unset($existingImages[$index]);
                    $deletedCount++;
                }
            }

            $existingImages = array_values($existingImages);
        }

        // Handle new image upload
        if ($request->hasFile('images')) {
            $newImages = $this->imageService->uploadMultiple(
                $request->file('images'),
                'rooms'
            );

            $addedCount = count($newImages);
            $existingImages = array_merge($existingImages, $newImages);

            if (count($existingImages) > 10) {
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

        return redirect()->route('admin.rooms.index')->with('success', $message);
    }

    /**
     * Remove the specified room
     * 
     * Model binding otomatis ter-filter
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
