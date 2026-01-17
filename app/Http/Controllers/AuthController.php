<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        // Jika sudah login, arahkan sesuai role
        if (Auth::check()) {
            $user = Auth::user();
            
            // PENTING: Jika belum verify, logout dan ke verifikasi
            if (is_null($user->email_verified_at)) {
                session(['verification_email' => $user->email]);
                Auth::logout();
                
                return redirect()->route('verification.otp.form')
                    ->with('info', 'Akun atau email Anda belum diverifikasi. Silakan verifikasi terlebih dahulu.');
            }
            
            return $this->redirectBasedOnRole($user);
        }

        return view('auth.login');
    }

    /**
     * Proses login
     * FLOW BARU: Cek verifikasi email sebelum login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        // Rate limiting
        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        // CRITICAL: Cek user exist dulu sebelum attempt login
        $user = User::where('email', $credentials['email'])->first();
        
        // Jika user tidak ditemukan
        if (!$user) {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        // FLOW BARU: Cek verifikasi email SEBELUM attempt login
        if (is_null($user->email_verified_at)) {
            // Kirim OTP baru jika belum ada OTP aktif
            if (!EmailVerification::hasActiveOtp($user->email)) {
                EmailVerification::createOtp($user->email);
            }
            
            // Set session untuk verifikasi
            session([
                'verification_email' => $user->email,
                'verification_name' => $user->name,
            ]);

            // TOLAK login dengan pesan dan tombol verifikasi
            return back()->withInput(['email' => $credentials['email']])->withErrors([
                'email' => 'unverified', // Special error key untuk trigger alert
            ])->with([
                'unverified_email' => $user->email,
                'unverified_name' => $user->name,
            ]);
        }

        // Attempt login (user sudah terverifikasi)
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey);

            // Redirect based on role
            return $this->redirectBasedOnRole(Auth::user(), true);
        }

        // Failed login (password salah)
        RateLimiter::hit($throttleKey, 60);

        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Helper: Redirect based on user role
     */
    protected function redirectBasedOnRole(User $user, bool $withMessage = false)
    {
        $message = $withMessage ? $this->getWelcomeMessage($user) : null;

        switch ($user->role) {
            case 'super_admin':
                $redirect = redirect()->route('superadmin.dashboard');
                break;

            case 'admin':
                $redirect = redirect()->route('admin.dashboard');
                break;

            case 'user':
                $redirect = redirect()->route('user.dashboard');
                break;

            default:
                return redirect()->route('login')
                    ->with('error', 'Role tidak valid. Hubungi administrator.');
        }

        if ($message) {
            $redirect->with('success', $message);
        }

        return $redirect;
    }

    /**
     * Helper: Get welcome message
     */
    private function getWelcomeMessage($user): string
    {
        return match ($user->role) {
            'super_admin' => 'Selamat datang Super Admin!',
            'admin' => 'Selamat datang Admin ' . ($user->tempatKos->nama_kos ?? ''),
            'user' => 'Selamat datang di KosSmart!',
            default => 'Selamat datang!',
        };
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}