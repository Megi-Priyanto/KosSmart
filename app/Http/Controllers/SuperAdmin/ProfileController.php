<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display profile page
     */
    public function index()
    {
        $user = Auth::user();

        return view('superadmin.profile', compact('user'));
    }

    /**
     * Update profile information
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah digunakan',
            'phone.regex' => 'Format nomor telepon tidak valid',
        ]);

        try {
            $user->update([
                'name' => strip_tags($validated['name']),
                'email' => strtolower($validated['email']),
                'phone' => $validated['phone'] ?? null,
            ]);

            return back()->with('success', 'Profile berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui profile.');
        }
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'password.required' => 'Password baru wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini salah'])
                ->withInput();
        }

        try {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return back()->with('success', 'Password berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui password.');
        }
    }
}
