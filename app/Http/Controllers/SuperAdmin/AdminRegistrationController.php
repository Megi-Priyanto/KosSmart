<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AdminRegistration;
use App\Models\KosInfo;
use App\Models\TempatKos;
use App\Models\User;
use App\Mail\AdminRegistrationApproved;
use App\Mail\AdminRegistrationRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminRegistration::with('reviewer')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nama_kos', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }

        $registrations = $query->paginate(15)->withQueryString();

        $counts = [
            'pending'  => AdminRegistration::pending()->count(),
            'approved' => AdminRegistration::approved()->count(),
            'rejected' => AdminRegistration::rejected()->count(),
        ];

        return view('superadmin.admin-registrations.index', compact('registrations', 'counts'));
    }

    public function show(AdminRegistration $adminRegistration)
    {
        return view('superadmin.admin-registrations.show', compact('adminRegistration'));
    }

    public function approve(Request $request, AdminRegistration $adminRegistration)
    {
        if (!$adminRegistration->isPending()) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $adminRegistration) {

            // 1. Buat TempatKos (induk / entitas bisnis)
            $tempatKos = TempatKos::create([
                'nama_kos'  => $adminRegistration->nama_kos,
                'alamat'    => $adminRegistration->alamat,
                'kecamatan' => $adminRegistration->kecamatan,
                'kota'      => $adminRegistration->kota,
                'provinsi'  => $adminRegistration->provinsi,
                'kode_pos'  => $adminRegistration->kode_pos,
                'telepon'   => $adminRegistration->telepon_kos,
                'email'     => $adminRegistration->email_kos,
                'status'    => 'active',
            ]);

            // 2. Buat KosInfo (detail operasional yang dikelola oleh admin)
            //    Kolom kos_info: name, address, city, province (tidak ada kecamatan)
            //    Kecamatan digabung ke address. phone WAJIB (tidak nullable).
            $alamatLengkap = $adminRegistration->kecamatan
                ? $adminRegistration->alamat . ', Kec. ' . $adminRegistration->kecamatan
                : $adminRegistration->alamat;

            KosInfo::create([
                'tempat_kos_id' => $tempatKos->id,
                'name'          => $adminRegistration->nama_kos,
                'address'       => $alamatLengkap,
                'city'          => $adminRegistration->kota,
                'province'      => $adminRegistration->provinsi,
                'postal_code'   => $adminRegistration->kode_pos,
                'phone'         => $adminRegistration->telepon_kos ?? $adminRegistration->no_hp, // wajib
                'whatsapp'      => $adminRegistration->no_hp,
                'email'         => $adminRegistration->email_kos ?? $adminRegistration->email,
                'is_active'     => true,
            ]);

            // 3. Buat User dengan role admin, terikat ke TempatKos
            User::create([
                'name'              => $adminRegistration->nama_lengkap,
                'email'             => $adminRegistration->email,
                'phone'             => $adminRegistration->no_hp,
                'password'          => $adminRegistration->password, // sudah hashed sejak submit
                'role'              => 'admin',
                'tempat_kos_id'     => $tempatKos->id,
                'email_verified_at' => now(),
            ]);

            // 4. Update status pengajuan
            $adminRegistration->update([
                'status'      => 'approved',
                'catatan'     => $request->catatan,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
        });

        try {
            Mail::to($adminRegistration->email)
                ->send(new AdminRegistrationApproved($adminRegistration));
        } catch (\Exception $e) {
            // Gagal kirim email tidak membatalkan approve
        }

        return redirect()
            ->route('superadmin.admin-registrations.index')
            ->with('success', "Pengajuan {$adminRegistration->nama_lengkap} disetujui. Akun admin & data kos telah dibuat.");
    }

    public function reject(Request $request, AdminRegistration $adminRegistration)
    {
        if (!$adminRegistration->isPending()) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $adminRegistration->update([
            'status'      => 'rejected',
            'catatan'     => $request->catatan,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        try {
            Mail::to($adminRegistration->email)
                ->send(new AdminRegistrationRejected($adminRegistration));
        } catch (\Exception $e) {
            //
        }

        return redirect()
            ->route('superadmin.admin-registrations.index')
            ->with('success', "Pengajuan {$adminRegistration->nama_lengkap} telah ditolak.");
    }
}