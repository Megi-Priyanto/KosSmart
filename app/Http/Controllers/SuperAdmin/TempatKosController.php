<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TempatKos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TempatKosController extends Controller
{
    /**
     * Display a listing of tempat kos
     */
    public function index(Request $request)
    {
        $query = TempatKos::withCount([
            'rooms',
            'admins',
            'rooms as penghuni_count' => function ($q) {
                $q->whereHas('rents', function ($r) {
                    $r->where('status', 'active');
                });
            }
        ]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kos', 'like', "%{$search}%")
                    ->orWhere('kota', 'like', "%{$search}%")
                    ->orWhere('kecamatan', 'like', "%{$search}%") // ✅ TAMBAHKAN
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tempatKos = $query->latest()->paginate(15)->withQueryString();

        return view('superadmin.tempat-kos.index', compact('tempatKos'));
    }

    /**
     * Show the form for creating a new tempat kos
     */
    public function create()
    {
        return view('superadmin.tempat-kos.create');
    }

    /**
     * Store a newly created tempat kos
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kos' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'kota' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'], // ✅ TAMBAHKAN
            'provinsi' => ['required', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
        ], [
            'nama_kos.required' => 'Nama kos wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'kota.required' => 'Kota wajib diisi',
            'kecamatan.required' => 'Kecamatan wajib diisi', // ✅ TAMBAHKAN
            'provinsi.required' => 'Provinsi wajib diisi',
            'logo.image' => 'File harus berupa gambar',
            'logo.max' => 'Ukuran logo maksimal 2MB',
        ]);

        try {
            // Upload logo if exists
            if ($request->hasFile('logo')) {
                $validated['logo'] = $request->file('logo')->store('logos', 'public');
            }

            $tempatKos = TempatKos::create($validated);

            return redirect()
                ->route('superadmin.tempat-kos.show', $tempatKos)
                ->with('success', "Tempat kos {$tempatKos->nama_kos} berhasil dibuat!");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat tempat kos: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified tempat kos
     */
    public function show(TempatKos $tempatKos)
    {
        $tempatKos->load([
            'admins',
            'rooms' => function ($q) {
                $q->withCount([
                    'rents as rents_active_count' => function ($r) {
                        $r->where('status', 'active');
                    }
                ]);
            }
        ]);

        $penghuniAktif = \App\Models\User::whereHas('rents', function ($r) use ($tempatKos) {
            $r->where('status', 'active')
                ->whereHas('room', function ($room) use ($tempatKos) {
                    $room->where('tempat_kos_id', $tempatKos->id);
                });
        })->get();

        $stats = [
            'total_kamar' => $tempatKos->rooms->count(),
            'kamar_terisi' => $tempatKos->rooms
                ->where('rents_active_count', '>', 0)
                ->count(),
            'kamar_tersedia' => $tempatKos->rooms
                ->where('rents_active_count', 0)
                ->count(),
            'total_penghuni' => $penghuniAktif->count(),
            'total_admin' => $tempatKos->admins->count(),
        ];

        return view('superadmin.tempat-kos.show', compact('tempatKos', 'stats'));
    }

    /**
     * Show the form for editing tempat kos
     */
    public function edit(TempatKos $tempatKos)
    {
        return view('superadmin.tempat-kos.edit', compact('tempatKos'));
    }

    /**
     * Update the specified tempat kos
     */
    public function update(Request $request, TempatKos $tempatKos)
    {
        $validated = $request->validate([
            'nama_kos' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'kota' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'provinsi' => ['required', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        try {
            // Upload new logo if exists
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($tempatKos->logo) {
                    Storage::disk('public')->delete($tempatKos->logo);
                }
                $validated['logo'] = $request->file('logo')->store('logos', 'public');
            }

            $tempatKos->update($validated);

            return redirect()
                ->route('superadmin.tempat-kos.show', $tempatKos)
                ->with('success', "Tempat kos {$tempatKos->nama_kos} berhasil diperbarui!");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified tempat kos
     */
    public function destroy(TempatKos $tempatKos)
    {
        try {
            $nama = $tempatKos->nama_kos;

            // Cek apakah masih ada data terkait
            if ($tempatKos->rooms()->count() > 0) {
                return back()->with('error', 'Tidak dapat menghapus tempat kos yang masih memiliki kamar.');
            }

            $hasActiveRent = \App\Models\Rent::whereHas('room', function ($q) use ($tempatKos) {
                $q->where('tempat_kos_id', $tempatKos->id);
            })->where('status', 'active')->exists();

            if ($hasActiveRent) {
                return back()->with('error', 'Tidak dapat menghapus tempat kos yang masih memiliki penyewa aktif.');
            }

            // Delete logo
            if ($tempatKos->logo) {
                Storage::disk('public')->delete($tempatKos->logo);
            }

            $tempatKos->delete();

            return redirect()
                ->route('superadmin.tempat-kos.index')
                ->with('success', "Tempat kos {$nama} berhasil dihapus!");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}