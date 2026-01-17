<?php

namespace App\Http\Controllers;

use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OtpVerificationController extends Controller
{
    /**
     * Tampilkan halaman input OTP
     */
    public function showOtpForm()
    {
        // PENTING: Jika user sudah login dan verified, redirect ke dashboard
        if (Auth::check() && !is_null(Auth::user()->email_verified_at)) {
            return redirect()->route('user.dashboard');
        }

        // Jika belum ada session email, redirect ke register
        if (!session('verification_email')) {
            return redirect()->route('register')
                ->with('error', 'Silakan daftar terlebih dahulu.');
        }

        return view('auth.verify-otp');
    }

    /**
     * Verifikasi OTP yang diinput user
     * FLOW BARU: OTP Success → REDIRECT ke LOGIN (JANGAN auto login!)
     */
    public function verifyOtp(Request $request)
    {
        // Rate limiting - maksimal 5 percobaan per menit
        $throttleKey = 'verify-otp-' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            throw ValidationException::withMessages([
                'otp' => "Terlalu banyak percobaan. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        // Validasi input OTP
        $request->validate([
            'otp' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi',
            'otp.size' => 'Kode OTP harus 6 digit',
            'otp.regex' => 'Kode OTP harus berupa angka',
        ]);

        $email = session('verification_email');
        
        if (!$email) {
            return back()->withErrors([
                'otp' => 'Session verifikasi tidak ditemukan. Silakan daftar ulang.',
            ]);
        }

        // Verifikasi OTP
        $isValid = EmailVerification::verifyOtp($email, $request->otp);

        if (!$isValid) {
            RateLimiter::hit($throttleKey, 60);
            
            // Cek apakah OTP expired atau tidak ada
            $verification = EmailVerification::where('email', $email)->first();
            
            if (!$verification) {
                throw ValidationException::withMessages([
                    'otp' => 'Kode OTP tidak valid atau sudah digunakan. Silakan kirim ulang OTP.',
                ]);
            }
            
            if ($verification->isExpired()) {
                throw ValidationException::withMessages([
                    'otp' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang OTP baru.',
                ]);
            }
            
            throw ValidationException::withMessages([
                'otp' => 'Kode OTP yang Anda masukkan salah. Silakan coba lagi.',
            ]);
        }

        // OTP valid, update user
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'otp' => 'User tidak ditemukan.',
            ]);
        }

        // Tandai email sebagai terverifikasi
        $user->email_verified_at = now();
        $user->save();

        // Clear rate limiter
        RateLimiter::clear($throttleKey);

        // PENTING: Hapus session verifikasi
        session()->forget(['verification_email', 'verification_name']);

        // CRITICAL CHANGE: JANGAN auto login!
        // OLD: Auth::login($user); 
        // NEW: Redirect ke login dengan pesan sukses
        
        return redirect()->route('login')->with([
            'success' => 'Email berhasil diverifikasi! Silakan login untuk melanjutkan.',
            'verified' => true,
            'email' => $email,
        ]);
    }

    /**
     * Kirim ulang OTP
     */
    public function resendOtp(Request $request)
    {
        // Rate limiting - maksimal 3 kali per 2 menit
        $throttleKey = 'resend-otp-' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            return back()->with('error', "Terlalu banyak permintaan. Silakan tunggu {$seconds} detik.");
        }

        $email = session('verification_email');
        
        if (!$email) {
            return back()->with('error', 'Session verifikasi tidak ditemukan.');
        }

        // Cek apakah user sudah terverifikasi
        $user = User::where('email', $email)->first();
        
        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->route('login')
                ->with('info', 'Email Anda sudah terverifikasi. Silakan login.');
        }

        try {
            // Generate dan kirim OTP baru
            EmailVerification::createOtp($email);
            
            RateLimiter::hit($throttleKey, 120); // 2 menit cooldown
            
            return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim OTP. Silakan coba lagi.');
        }
    }
}