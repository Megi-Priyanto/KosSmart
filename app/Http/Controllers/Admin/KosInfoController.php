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
        $kosInfo = KosInfo::orderBy('id', 'desc')->paginate(10);

        // Jika belum ada data, redirect ke create
        if (!$kosInfo) {
            return redirect()->route('admin.kos.create')
                ->with('info', 'Silakan isi informasi kos terlebih dahulu');
        }

        return view('admin.kos.index', compact('kosInfo'));
    }

    /**
     * Show form to create kos info (first time only)
     */
    public function create()
    {
        // Cek apakah sudah ada data
        if (KosInfo::exists()) {
            return redirect()->route('admin.kos.index')
                ->with('error', 'Data kos sudah ada. Gunakan menu edit untuk mengubah.');
        }

        return view('admin.kos.create');
    }

    /**
     * Store kos info (first time)
     */
    public function store(UpdateKosInfoRequest $request)
    {
        // Cek apakah sudah ada data
        if (KosInfo::exists()) {
            return redirect()->route('admin.kos.index')
                ->with('error', 'Data kos sudah ada.');
        }

        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('images')) {
            $data['images'] = $this->imageService->uploadMultiple(
                $request->file('images'),
                'kos'
            );
        }

        KosInfo::create($data);

        return redirect()->route('admin.kos.index')
            ->with('success', 'Informasi kos berhasil disimpan');
    }

    /**
     * Show form to edit kos info
     */
    public function edit()
    {
        $kosInfo = KosInfo::firstOrFail();

        return view('admin.kos.edit', compact('kosInfo'));
    }

    /**
     * Update kos info
     */
    public function update(UpdateKosInfoRequest $request)
    {
        $kosInfo = KosInfo::firstOrFail();
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

        $kosInfo->update($data);

        return redirect()->route('admin.kos.index')
            ->with('success', 'Informasi kos berhasil diperbarui');
    }

    public function toggleApply($id)
    {
        $info = KosInfo::findOrFail($id);

        // Toggle nilai show_in_detail (true → false, false → true)
        $info->show_in_detail = !$info->show_in_detail;
        $info->save();

        return back()->with('success', 'Status berhasil diperbarui');
    }
}
