<?php
// app/Http/Controllers/Admin/KosInfoController.php

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
     */
    public function index()
    {
        return view('admin.kos.index', [
            'kosInfos'  => KosInfo::latest()->get(),
            'activeKos' => KosInfo::where('is_active', true)->first(),
        ]);
    }

    /**
     * Show form to create kos info (first time only)
     */
    public function create()
    {
        return view('admin.kos.create');
    }

    /**
     * Store kos info (first time)
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

        KosInfo::create($data);

        return redirect()
            ->route('admin.kos.index')
            ->with('success', 'Informasi kos berhasil ditambahkan (belum aktif)');
    }

    public function show(KosInfo $kos)
    {
        return view('admin.kos.show', compact('kos'));
    }

    /**
     * Show form to edit kos info
     */
    public function edit($id)
    {
        $kosInfo = KosInfo::findOrFail($id);
        return view('admin.kos.edit', [
            'kos' => $kosInfo
        ]);
    }

    /**
     * Update kos info
     */
    public function update(UpdateKosInfoRequest $request, $id)
    {
        $kosInfo = KosInfo::findOrFail($id);
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('images')) {
            $newImages = $this->imageService->uploadMultiple(
                $request->file('images'),
                'kos'
            );

            // Merge dengan gambar lama (jika tidak dihapus)
            $existingImages = $kosInfo->images ?? [];
            $data['images'] = array_merge($existingImages, $newImages);
        }

        // Handle image removal
        if ($request->has('remove_images')) {
            $removeIndices = $request->input('remove_images');
            $existingImages = $kosInfo->images ?? [];

            foreach ($removeIndices as $index) {
                if (isset($existingImages[$index])) {
                    $this->imageService->delete($existingImages[$index]);
                    unset($existingImages[$index]);
                }
            }

            $data['images'] = array_values($existingImages);
        }

        $data['is_active'] = $request->boolean('is_active');

        if ($data['is_active']) {
            KosInfo::where('id', '!=', $kosInfo->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $kosInfo->update($data);

        return redirect()->route('admin.kos.index')
            ->with('success', 'Informasi kos berhasil diperbarui');
    }

    public function activate(KosInfo $kos)
    {
        KosInfo::where('is_active', true)->update(['is_active' => false]);
        $kos->update(['is_active' => true]);

        return back()->with('success', 'Informasi kos berhasil diterapkan');
    }

    public function deactivate(KosInfo $kos)
    {
        $kos->update(['is_active' => false]);

        return back()->with('success', 'Informasi kos dinonaktifkan');
    }
}
