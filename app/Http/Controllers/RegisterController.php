<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /**
     * Tampilkan halaman register
     */
    public function showRegister()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            $user = Auth::user();
            
            // PENTING: Jika sudah login tapi belum verify, logout dulu
            if (is_null($user->email_verified_at)) {
                Auth::logout();
                return redirect()->route('verification.otp.form')
                    ->with('info', 'Silakan verifikasi email Anda terlebih dahulu.');
            }
            
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('user.dashboard');
        }
        
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru
     * FLOW: Register → OTP Verification (JANGAN langsung login)
     */
    public function register(Request $request)
    {
        // Rate limiting - mencegah spam registration
        $throttleKey = 'register-' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan pendaftaran. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'terms' => ['accepted'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.min' => 'Nama lengkap minimal 3 karakter',
            'name.max' => 'Nama lengkap maksimal 255 karakter',
            'email.required' => 'Alamat email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        try {
            // Buat user baru (belum verified)
            $user = User::create([
                'name' => strip_tags($validated['name']),
                'email' => strtolower($validated['email']),
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'email_verified_at' => null, // PENTING: Belum verified
            ]);

            // Generate dan kirim OTP
            EmailVerification::createOtp($user->email);

            // Clear rate limiter jika berhasil
            RateLimiter::clear($throttleKey);

            // PENTING: Simpan email di session untuk proses verifikasi
            session([
                'verification_email' => $user->email,
                'verification_name' => $user->name,
            ]);

            // REDIRECT: Register → OTP Verification (JANGAN auto login!)
            return redirect()->route('verification.otp.form')->with([
                'success' => 'Pendaftaran berhasil! Kode OTP telah dikirim ke email Anda.',
                'email' => $user->email,
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Registration failed: ' . $e->getMessage());
            
            // Increment rate limiter
            RateLimiter::hit($throttleKey, 300);
            
            return back()->withInput()->withErrors([
                'email' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.',
            ]);
        }
    }
}