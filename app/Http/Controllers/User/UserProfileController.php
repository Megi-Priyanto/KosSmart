<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    /**
     * Menampilkan halaman profil user
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        return view('user.profile', compact('user'));
    }

    /**
     * Update informasi pribadi user (NAMA & TELEPON saja)
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:15', 'regex:/^[0-9]+$/'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'phone.regex' => 'Nomor telepon hanya boleh berisi angka',
            'phone.max' => 'Nomor telepon maksimal 15 digit',
        ]);

        try {
            $user->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
            ]);

            return redirect()->route('user.profile')
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->route('user.profile')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * REQUEST PERUBAHAN EMAIL (Step 1)
     * - Langsung ubah email di database
     * - Set email_verified_at = NULL
     * - Kirim OTP ke email baru
     * - Redirect ke halaman verifikasi OTP
     */
    public function requestEmailChange(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'new_email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                'unique:users,email,' . $user->id
            ],
        ], [
            'new_email.required' => 'Email baru wajib diisi',
            'new_email.email' => 'Format email tidak valid',
            'new_email.unique' => 'Email sudah digunakan oleh user lain',
        ]);

        $newEmail = strtolower($validated['new_email']);
        $oldEmail = $user->email;

        // Cek apakah email sama
        if ($newEmail === $oldEmail) {
            return back()->withErrors([
                'new_email' => 'Email baru tidak boleh sama dengan email lama'
            ]);
        }

        try {
            DB::beginTransaction();

            // STEP 1: Ubah email di database & set belum terverifikasi
            // Gunakan DB::table untuk update langsung ke database
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'email' => $newEmail,
                    'email_verified_at' => null, // CRITICAL: Set NULL
                    'updated_at' => now(),
                ]);

            Log::info('Email changed and set to unverified', [
                'user_id' => $user->id,
                'old_email' => $oldEmail,
                'new_email' => $newEmail,
                'email_verified_at' => null,
            ]);

            // STEP 2: Generate dan kirim OTP ke EMAIL BARU
            EmailVerification::createOtp($newEmail);

            // STEP 3: Simpan info ke session untuk halaman verifikasi
            session([
                'email_change_old' => $oldEmail,
                'email_change_new' => $newEmail,
                'email_change_user_id' => $user->id,
            ]);

            DB::commit();

            // STEP 4: CRITICAL - Logout user untuk force refresh
            // Ini penting agar SuperAdmin bisa langsung lihat status "Unverified"
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Set ulang session untuk halaman verifikasi
            session([
                'email_change_old' => $oldEmail,
                'email_change_new' => $newEmail,
                'email_change_user_id' => $user->id,
            ]);

            Log::info('User logged out after email change', [
                'user_id' => $user->id,
                'new_email' => $newEmail,
            ]);

            // STEP 5: Redirect ke halaman verifikasi OTP
            return redirect()->route('user.profile.email.verify')
                ->with('success', 'Kode OTP telah dikirim ke email baru Anda. Silakan verifikasi untuk melanjutkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error changing email', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            
            return back()->withErrors([
                'new_email' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * TAMPILKAN HALAMAN VERIFIKASI OTP
     */
    public function showEmailVerification()
    {
        // Cek session
        if (!session()->has('email_change_new')) {
            return redirect()->route('login')
                ->with('error', 'Session verifikasi tidak ditemukan. Silakan login kembali.');
        }

        $oldEmail = session('email_change_old');
        $newEmail = session('email_change_new');

        return view('user.email-verification', compact('oldEmail', 'newEmail'));
    }

    /**
     * VERIFIKASI OTP (Step 2)
     * - Cek OTP valid
     * - Set email_verified_at = NOW
     * - Clear session
     * - Login user kembali
     * - Redirect ke profil
     */
    public function verifyEmailChange(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi',
            'otp.size' => 'Kode OTP harus 6 digit',
            'otp.regex' => 'Kode OTP harus berupa angka',
        ]);

        $newEmail = session('email_change_new');
        $userId = session('email_change_user_id');
        
        if (!$newEmail || !$userId) {
            return back()->withErrors([
                'otp' => 'Session verifikasi tidak ditemukan.'
            ]);
        }

        // Verifikasi OTP
        $isValid = EmailVerification::verifyOtp($newEmail, $request->otp);

        if (!$isValid) {
            $verification = EmailVerification::where('email', $newEmail)->first();
            
            if (!$verification) {
                throw ValidationException::withMessages([
                    'otp' => 'Kode OTP tidak valid atau sudah digunakan.',
                ]);
            }
            
            if ($verification->isExpired()) {
                throw ValidationException::withMessages([
                    'otp' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.',
                ]);
            }
            
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP yang Anda masukkan salah.',
            ]);
        }

        try {
            DB::beginTransaction();
            
            // Update email_verified_at menggunakan DB facade
            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'email_verified_at' => now(),
                    'updated_at' => now(),
                ]);

            Log::info('Email verified successfully', [
                'user_id' => $userId,
                'email' => $newEmail,
                'email_verified_at' => now(),
            ]);

            // Clear cache
            Cache::forget("user.{$userId}");

            // Clear session verifikasi
            session()->forget([
                'email_change_old',
                'email_change_new',
                'email_change_user_id',
            ]);

            DB::commit();

            // Login user kembali dengan ID
            Auth::loginUsingId($userId);
            $request->session()->regenerate();

            return redirect()->route('user.profile')
                ->with('success', 'Email berhasil diubah dan diverifikasi!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error verifikasi email', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
            
            return back()->withErrors([
                'otp' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * KIRIM ULANG OTP
     */
    public function resendEmailOtp(Request $request)
    {
        $newEmail = session('email_change_new');
        
        if (!$newEmail) {
            return back()->with('error', 'Session verifikasi tidak ditemukan.');
        }

        try {
            EmailVerification::createOtp($newEmail);
            
            return back()->with('success', 'Kode OTP baru telah dikirim!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim OTP: ' . $e->getMessage());
        }
    }

    /**
     * BATAL PERUBAHAN EMAIL
     * - Email tetap EMAIL BARU (belum terverifikasi)
     * - Clear session
     * - Redirect ke login
     */
    public function cancelEmailChange(Request $request)
    {
        // Clear session verifikasi
        session()->forget([
            'email_change_old',
            'email_change_new',
            'email_change_user_id',
        ]);

        return redirect()->route('login')->with([
            'info' => 'Perubahan email dibatalkan. Silakan login dengan email baru Anda dan verifikasi untuk melanjutkan.',
        ]);
    }

    /**
     * Update password user
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

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
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->route('user.profile')
                    ->withErrors(['current_password' => 'Password saat ini tidak sesuai'])
                    ->withInput();
            }

            if (Hash::check($validated['password'], $user->password)) {
                return redirect()->route('user.profile')
                    ->withErrors(['password' => 'Password baru tidak boleh sama dengan password lama'])
                    ->withInput();
            }

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->route('user.profile')
                ->with('success', 'Password berhasil diubah!');

        } catch (\Exception $e) {
            return redirect()->route('user.profile')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

            if ($user->hasActiveRoom()) {
                return redirect()->route('user.profile')
                    ->with('error', 'Tidak dapat menghapus akun. Anda masih memiliki sewa kamar aktif.');
            }

            $pendingPayments = $user->payments()
                ->whereIn('status', ['pending', 'processing'])
                ->count();

            if ($pendingPayments > 0) {
                return redirect()->route('user.profile')
                    ->with('error', 'Tidak dapat menghapus akun. Anda masih memiliki pembayaran pending.');
            }

            Auth::logout();
            $user->delete();

            DB::commit();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('success', 'Akun berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('user.profile')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}