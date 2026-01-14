<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{
    /**
     * Menampilkan halaman profil user
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('user.profile', compact('user'));
    }

    /**
     * Update informasi pribadi user
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:15', 'regex:/^[0-9]+$/'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan oleh user lain',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka',
            'phone.max' => 'Nomor telepon maksimal 15 digit',
        ]);

        try {
            // Cek apakah email berubah
            $emailChanged = $user->email !== $validated['email'];

            // Update data user
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                // Jika email berubah, set email_verified_at menjadi null
                'email_verified_at' => $emailChanged ? null : $user->email_verified_at,
            ]);

            // Jika email berubah, kirim notifikasi verifikasi ulang
            if ($emailChanged) {
                $user->sendEmailVerificationNotification();
                
                return redirect()->route('user.profile')
                    ->with('success', 'Profil berhasil diperbarui. Silakan verifikasi email baru Anda.')
                    ->with('info', 'Kami telah mengirim link verifikasi ke email baru Anda.');
            }

            return redirect()->route('user.profile')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->route('user.profile')
                ->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Update password user
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers(), 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'password.required' => 'Password baru wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            // Cek apakah password saat ini benar
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->route('user.profile')
                    ->withErrors(['current_password' => 'Password saat ini tidak sesuai'])
                    ->withInput();
            }

            // Cek apakah password baru sama dengan password lama
            if (Hash::check($validated['password'], $user->password)) {
                return redirect()->route('user.profile')
                    ->withErrors(['password' => 'Password baru tidak boleh sama dengan password lama'])
                    ->withInput();
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->route('user.profile')
                ->with('success', 'Password berhasil diubah! Silakan login ulang dengan password baru.');

        } catch (\Exception $e) {
            return redirect()->route('user.profile')
                ->with('error', 'Terjadi kesalahan saat mengubah password: ' . $e->getMessage());
        }
    }

    /**
     * Hapus akun user
     */
    public function destroy(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        try {
            DB::beginTransaction();

            // Cek apakah user masih memiliki sewa aktif
            if ($user->hasActiveRoom()) {
                return redirect()->route('user.profile')
                    ->with('error', 'Tidak dapat menghapus akun. Anda masih memiliki sewa kamar yang aktif. Silakan hubungi admin terlebih dahulu.');
            }

            // Cek apakah ada pembayaran yang belum selesai
            $pendingPayments = $user->payments()
                ->whereIn('status', ['pending', 'processing'])
                ->count();

            if ($pendingPayments > 0) {
                return redirect()->route('user.profile')
                    ->with('error', 'Tidak dapat menghapus akun. Anda masih memiliki pembayaran yang belum selesai.');
            }

            // Logout user
            Auth::logout();

            // Hapus akun
            $user->delete();

            DB::commit();

            // Invalidate session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Akun Anda berhasil dihapus. Terima kasih telah menggunakan layanan kami.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('user.profile')
                ->with('error', 'Terjadi kesalahan saat menghapus akun: ' . $e->getMessage());
        }
    }
}