<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware pengganti 'verified' bawaan Laravel.
 *
 * Laravel default 'verified' redirect ke route('verification.notice') yang
 * tidak ada di project ini (pakai OTP custom). Middleware ini menggantikannya
 * agar redirect ke halaman OTP verification yang benar.
 *
 * CARA PAKAI:
 * 1. Simpan file ini di app/Http/Middleware/EnsureEmailIsVerified.php
 * 2. Daftarkan di app/Http/Kernel.php di $routeMiddleware:
 *      'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
 *    (timpa alias 'verified' yang lama)
 * 3. Tidak perlu ubah web.php — alias 'verified' tetap sama
 */
class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Kalau belum login, biarkan middleware auth yang handle
        if (!$user) {
            return $next($request);
        }

        // Kalau belum verifikasi email → arahkan ke OTP form (bukan verification.notice)
        if (is_null($user->email_verified_at)) {
            session(['verification_email' => $user->email]);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('verification.otp.form')
                ->with('info', 'Silakan verifikasi email Anda terlebih dahulu.');
        }

        return $next($request);
    }
}   