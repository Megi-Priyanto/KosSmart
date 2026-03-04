<?php

namespace App\Http\Controllers;

use App\Models\AdminRegistration;
use App\Mail\AdminRegistrationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AdminRegistrationController extends Controller
{
    public function create()
    {
        return view('admin-registration.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Step 1 - Data Diri
            'nama_lengkap'  => ['required', 'string', 'max:255'],
            'nik'           => ['required', 'string', 'size:16', 'unique:admin_registrations,nik'],
            'email'         => ['required', 'email', 'unique:admin_registrations,email', 'unique:users,email'],
            'no_hp'         => ['required', 'string', 'max:20'],
            'password'      => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],

            // Step 2 - Data Kos
            'nama_kos'      => ['required', 'string', 'max:255'],
            'alamat'        => ['required', 'string'],
            'kecamatan'     => ['required', 'string', 'max:100'],
            'kota'          => ['required', 'string', 'max:100'],
            'provinsi'      => ['required', 'string', 'max:100'],
            'kode_pos'      => ['nullable', 'string', 'max:10'],
            'telepon_kos'   => ['nullable', 'string', 'max:20'],
            'email_kos'     => ['nullable', 'email', 'max:100'],

            // Step 3 - Dokumen
            'ktp_foto'          => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'selfie_ktp'        => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'tipe_kepemilikan'  => ['required', 'in:pemilik,penyewa'],
            'bukti_kepemilikan' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            'npwp'              => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'nik.size'              => 'NIK harus 16 digit',
            'nik.unique'            => 'NIK ini sudah pernah mendaftar',
            'email.unique'          => 'Email ini sudah terdaftar',
            'password.confirmed'    => 'Konfirmasi password tidak cocok',
            'ktp_foto.required'     => 'Foto KTP wajib diupload',
            'selfie_ktp.required'   => 'Selfie dengan KTP wajib diupload',
            'bukti_kepemilikan.required' => 'Bukti kepemilikan/sewa wajib diupload',
            'ktp_foto.max'          => 'Ukuran file KTP maksimal 5MB',
            'bukti_kepemilikan.max' => 'Ukuran file bukti kepemilikan maksimal 10MB',
        ]);

        // Upload dokumen ke folder terpisah per pengajuan (sementara pakai timestamp)
        $folder = 'admin-registrations/' . time() . '-' . Str::slug($validated['nama_lengkap']);

        $validated['ktp_foto']          = $request->file('ktp_foto')->store($folder, 'public');
        $validated['selfie_ktp']        = $request->file('selfie_ktp')->store($folder, 'public');
        $validated['bukti_kepemilikan'] = $request->file('bukti_kepemilikan')->store($folder, 'public');

        if ($request->hasFile('npwp')) {
            $validated['npwp'] = $request->file('npwp')->store($folder, 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        $registration = AdminRegistration::create($validated);

        // Kirim email konfirmasi ke calon admin
        try {
            Mail::to($registration->email)->send(new AdminRegistrationReceived($registration));
        } catch (\Exception $e) {
            // Gagal kirim email tidak menghentikan proses
        }

        return redirect()->route('admin.registration.success')
            ->with('registration_email', $registration->email);
    }

    public function success()
    {
        return view('admin-registration.success');
    }
}