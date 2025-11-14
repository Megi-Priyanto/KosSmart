<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
            return redirect()->route('dashboard');
        }
        
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru
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
            // Custom error messages dalam Bahasa Indonesia
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
            // Buat user baru
            $user = User::create([
                'name' => strip_tags($validated['name']), // Sanitasi input
                'email' => strtolower($validated['email']),
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                // email_verified_at akan null jika verifikasi email diaktifkan
            ]);

            // Clear rate limiter jika berhasil
            RateLimiter::clear($throttleKey);

            // Opsi 1: DENGAN Email Verification (Recommended untuk Production)
            if (config('auth.verify_email', false)) {
                // Trigger event Registered untuk mengirim email verifikasi
                event(new Registered($user));
                
                return redirect()->route('verification.notice')->with([
                    'success' => 'Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi akun.',
                    'email' => $user->email,
                ]);
            }
            
            // Opsi 2: TANPA Email Verification (Auto Login - untuk Development)
            // Set email sebagai verified
            $user->email_verified_at = now();
            $user->save();
            
            // Auto login user
            Auth::login($user);
            
            // Regenerasi session untuk keamanan
            $request->session()->regenerate();
            
            // Redirect ke dashboard dengan pesan sukses
            return redirect()->route('dashboard')->with([
                'success' => 'Selamat datang di KosSmart! Akun Anda berhasil dibuat.',
                'first_login' => true, // Flag untuk menampilkan tour/onboarding
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Registration failed: ' . $e->getMessage());
            
            // Increment rate limiter
            RateLimiter::hit($throttleKey, 300); // 5 minutes cooldown
            
            return back()->withInput()->withErrors([
                'email' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.',
            ]);
        }
    }

    /**
     * Tampilkan halaman notice verifikasi email
     */
    public function verificationNotice()
    {
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.verify-email');
    }

    /**
     * Verifikasi email user
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Cek apakah hash valid
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw ValidationException::withMessages([
                'email' => 'Link verifikasi tidak valid.',
            ]);
        }

        // Jika sudah terverifikasi
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Email sudah terverifikasi sebelumnya.');
        }

        // Mark email sebagai verified
        $user->markEmailAsVerified();

        return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
    }

    /**
     * Kirim ulang email verifikasi
     */
    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi telah dikirim ulang ke email Anda.');
    }
}