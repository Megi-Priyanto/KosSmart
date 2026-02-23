<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        if (is_null($user->email_verified_at)) {
            session(['verification_email' => $user->email]);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('verification.otp.form')
                ->with('info', 'Silakan verifikasi email Anda terlebih dahulu.');
        }

        return match ($user->role) {
            'super_admin' => redirect()->route('superadmin.dashboard'),
            'admin'       => redirect()->route('admin.dashboard'),
            'user'        => redirect()->route('user.dashboard'),
            default       => redirect()->route('home'),
        };
    }
}