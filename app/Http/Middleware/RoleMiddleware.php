<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  string  $role  Role yang diizinkan (super_admin|admin|user)
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Cek apakah user memiliki role yang sesuai
        if ($user->role !== $role) {
            // Redirect sesuai role user
            return match ($user->role) {
                'super_admin' => redirect()->route('superadmin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
                'admin' => redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
                'user' => redirect()->route('user.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
                default => redirect()->route('login')
                    ->with('error', 'Role tidak valid.'),
            };
        }

        return $next($request);
    }
}