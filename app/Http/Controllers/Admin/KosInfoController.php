<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateKosInfoRequest;
use App\Models\KosInfo;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class KosInfoController extends Controller
{
    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display kos info (admin view)
     * 
     * Global Scope otomatis filter berdasarkan tempat_kos_id
     * Super Admin bisa lihat semua dengan withoutTempatKosScope()
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Super Admin: Lihat semua kos
        if ($user->isSuperAdmin()) {
            $kosInfos = KosInfo::withoutTempatKosScope()->latest()->get();
            $activeKos = KosInfo::withoutTempatKosScope()
                ->where('is_active', true)
                ->first();
        } 
        // Admin: Hanya lihat kos miliknya (otomatis filtered oleh Global Scope)
        else {
            $kosInfos = KosInfo::latest()->get();
            $activeKos = KosInfo::where('is_active', true)->first();
        }

        return view('admin.kos.index', compact('kosInfos', 'activeKos'));
    }

    /**
     * Show form to create kos info
     * 
     * tempat_kos_id otomatis terisi saat create (via trait)
     */
    public function create()
    {
        return view('admin.kos.create');
    }

    /**
     * Store kos info
     * 
     * tempat_kos_id otomatis terisi via ScopesByTempatKos::creating()
     */
    public function store(UpdateKosInfoRequest $request)
    {
        $data = $request->validated();

        // PAKSA NONAKTIF saat pertama dibuat
        $data['is_active'] = false;

        if ($request->hasFile('images')) {
            $data['images'] = $this->imageService->uploadMultiple(
                $request->file('images'),
                'kos'
            );
        }

        // tempat_kos_id otomatis terisi oleh trait
        KosInfo::create($data);

        return redirect()
            ->route('admin.kos.index')
            ->with('success', 'Informasi kos berhasil ditambahkan (belum aktif)');
    }

    /**
     * Show specific kos info
     * 
     * Global Scope otomatis cek ownership
     */
    public function show(KosInfo $kos)
    {
        // Model binding sudah terfilter oleh Global Scope
        // Jika bukan milik admin, akan 404 otomatis
        return view('admin.kos.show', compact('kos'));
    }

    /**
     * Show form to edit kos info
     * 
     * Global Scope otomatis validasi ownership
     */
    public function edit(KosInfo $kos)
    {
        // Model binding sudah terfilter oleh Global Scope
        return view('admin.kos.edit', compact('kos'));
    }

    /**
     * Update kos info
     * 
     * Global Scope mencegah update kos milik orang lain
     */
    public function update(UpdateKosInfoRequest $request, KosInfo $kos)
    {
        // Model binding sudah terfilter, tidak perlu cek manual
        
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('images')) {
            $newImages = $this->imageService->uploadMultiple(
                $request->file('images'),
                'kos'
            );

            $existingImages = $kos->images ?? [];
            $data['images'] = array_merge($existingImages, $newImages);
        }

        // Handle image removal
        if ($request->has('remove_images')) {
            $removeIndices = $request->input('remove_images');
            $existingImages = $kos->images ?? [];

            foreach ($removeIndices as $index) {
                if (isset($existingImages[$index])) {
                    $this->imageService->delete($existingImages[$index]);
                    unset($existingImages[$index]);
                }
            }

            $data['images'] = array_values($existingImages);
        }

        $data['is_active'] = $request->boolean('is_active');

        // Jika diaktifkan, nonaktifkan kos lain di tempat_kos_id yang sama
        if ($data['is_active']) {
            KosInfo::where('id', '!=', $kos->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $kos->update($data);

        return redirect()->route('admin.kos.index')
            ->with('success', 'Informasi kos berhasil diperbarui');
    }

    /**
     * Activate specific kos info
     * 
     * Hanya bisa activate kos milik sendiri
     */
    public function activate(KosInfo $kos)
    {
        // Nonaktifkan kos lain di tempat_kos_id yang sama
        KosInfo::where('is_active', true)
            ->where('id', '!=', $kos->id)
            ->update(['is_active' => false]);

        $kos->update(['is_active' => true]);

        return back()->with('success', 'Informasi kos berhasil diterapkan');
    }

    /**
     * Deactivate specific kos info
     */
    public function deactivate(KosInfo $kos)
    {
        $kos->update(['is_active' => false]);

        return back()->with('success', 'Informasi kos dinonaktifkan');
    }
}