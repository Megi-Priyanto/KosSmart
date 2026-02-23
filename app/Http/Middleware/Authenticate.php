<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     * Redirect ke halaman login yang sesuai jika belum login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Arahkan ke login page yang sesuai berdasarkan URL
            if ($request->is('superadmin/*')) {
                return redirect()->route('superadmin.login')
                    ->with('error', 'Silakan login terlebih dahulu.');
            }

            if ($request->is('admin/*')) {
                return redirect()->route('admin.login')
                    ->with('error', 'Silakan login terlebih dahulu.');
            }

            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}