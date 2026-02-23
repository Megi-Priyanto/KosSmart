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
     * Tampilkan halaman login USER
     */
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();

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
     * Proses login USER — hanya menerima role 'user'
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

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        if ($user->role !== 'user') {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'email' => 'Akun ini tidak dapat login melalui halaman ini.',
            ]);
        }

        if (is_null($user->email_verified_at)) {
            if (!EmailVerification::hasActiveOtp($user->email)) {
                EmailVerification::createOtp($user->email);
            }
            session([
                'verification_email' => $user->email,
                'verification_name'  => $user->name,
            ]);
            return back()->withInput(['email' => $credentials['email']])->withErrors([
                'email' => 'unverified',
            ])->with([
                'unverified_email'   => $user->email,
                'unverified_name'    => $user->name,
                'show_verify_button' => true,
            ]);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey);
            return $this->redirectBasedOnRole(Auth::user(), true);
        }

        RateLimiter::hit($throttleKey, 60);
        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    // =========================================================================
    // ADMIN LOGIN
    // =========================================================================

    /**
     * Tampilkan halaman login ADMIN
     * [FIX] Tambah Auth::check() — kalau sudah login, redirect ke dashboard
     */
    public function showAdminLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (is_null($user->email_verified_at)) {
                session(['verification_email' => $user->email]);
                Auth::logout();
                return redirect()->route('verification.otp.form')
                    ->with('info', 'Akun belum diverifikasi.');
            }

            return $this->redirectBasedOnRole($user);
        }

        return view('auth.admin-login');
    }

    /**
     * Proses login ADMIN — hanya menerima role 'admin'
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || $user->role !== 'admin') {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        if (is_null($user->email_verified_at)) {
            if (!EmailVerification::hasActiveOtp($user->email)) {
                EmailVerification::createOtp($user->email);
            }
            session([
                'verification_email' => $user->email,
                'verification_name'  => $user->name,
            ]);
            return back()->withInput(['email' => $credentials['email']])->withErrors([
                'email' => 'unverified',
            ])->with([
                'unverified_email'   => $user->email,
                'unverified_name'    => $user->name,
                'show_verify_button' => true,
            ]);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey);
            return $this->redirectBasedOnRole(Auth::user(), true);
        }

        RateLimiter::hit($throttleKey, 60);
        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    // =========================================================================
    // SUPER ADMIN LOGIN
    // =========================================================================

    /**
     * Tampilkan halaman login SUPER ADMIN
     * [FIX] Tambah Auth::check() — kalau sudah login, redirect ke dashboard
     */
    public function showSuperAdminLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (is_null($user->email_verified_at)) {
                session(['verification_email' => $user->email]);
                Auth::logout();
                return redirect()->route('verification.otp.form')
                    ->with('info', 'Akun belum diverifikasi.');
            }

            return $this->redirectBasedOnRole($user);
        }

        return view('auth.superadmin-login');
    }

    /**
     * Proses login SUPER ADMIN — hanya menerima role 'super_admin'
     */
    public function superAdminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || $user->role !== 'super_admin') {
            RateLimiter::hit($throttleKey, 60);
            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        if (is_null($user->email_verified_at)) {
            session([
                'verification_email' => $user->email,
                'verification_name'  => $user->name,
            ]);
            return back()->withInput(['email' => $credentials['email']])->withErrors([
                'email' => 'unverified',
            ])->with([
                'unverified_email'   => $user->email,
                'show_verify_button' => true,
            ]);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            RateLimiter::clear($throttleKey);
            return $this->redirectBasedOnRole(Auth::user(), true);
        }

        RateLimiter::hit($throttleKey, 60);
        throw ValidationException::withMessages([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ]);
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    protected function redirectBasedOnRole(User $user, bool $withMessage = false)
    {
        $message = $withMessage ? $this->getWelcomeMessage($user) : null;

        $redirect = match ($user->role) {
            'super_admin' => redirect()->route('superadmin.dashboard'),
            'admin'       => redirect()->route('admin.dashboard'),
            'user'        => redirect()->route('user.dashboard'),
            default       => redirect()->route('login')
                                ->with('error', 'Role tidak valid. Hubungi administrator.'),
        };

        if ($message && $user->role !== 'default') {
            $redirect->with('success', $message);
        }

        return $redirect;
    }

    private function getWelcomeMessage($user): string
    {
        return match ($user->role) {
            'super_admin' => 'Selamat datang Super Admin!',
            'admin'       => 'Selamat datang Admin ' . ($user->tempatKos->nama_kos ?? ''),
            'user'        => 'Selamat datang di KosSmart!',
            default       => 'Selamat datang!',
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}