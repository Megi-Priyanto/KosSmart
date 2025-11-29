<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: User TIDAK BOLEH punya kamar aktif untuk mengakses
 * Digunakan untuk: Halaman Pilih Kamar, Booking
 */
class CheckNoRoom
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Cek apakah user sudah memiliki rent yang aktif
        $hasActiveRoom = $user->rents()
            ->where('status', 'active')
            ->whereNull('end_date')
            ->exists();
        
        if ($hasActiveRoom) {
            return redirect()
                ->route('user.dashboard')
                ->with('info', 'Anda sudah menyewa kamar.');
        }
        
        return $next($request);
    }
}