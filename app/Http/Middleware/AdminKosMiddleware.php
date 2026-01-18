<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk Admin Tempat Kos
 * 
 * Fungsi:
 * - Memastikan user adalah admin (bukan super_admin atau user biasa)
 * - Memastikan admin terikat dengan tempat kos
 * - Memastikan tempat kos masih aktif
 * - Membatasi akses admin hanya ke data kos mereka
 */
class AdminKosMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * Middleware ini memastikan:
     * 1. User adalah admin
     * 2. Admin memiliki tempat_kos_id
     * 3. Admin hanya bisa akses data kos mereka sendiri
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Cek apakah user adalah admin
        if ($user->role !== 'admin') {
            // Redirect sesuai role
            if ($user->role === 'super_admin') {
                return redirect()->route('superadmin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman Admin Kos.');
            } else {
                return redirect()->route('user.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman Admin.');
            }
        }

        // Cek apakah admin memiliki tempat kos
        if (is_null($user->tempat_kos_id)) {
            return redirect()->route('login')
                ->with('error', 'Akun admin Anda belum terikat dengan tempat kos. Hubungi Super Admin.');
        }

        // Cek apakah tempat kos masih aktif
        if (!$user->tempatKos || !$user->tempatKos->isActive()) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Tempat kos Anda sudah tidak aktif. Hubungi Super Admin.');
        }

        return $next($request);
    }
}