<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: User HARUS punya kamar aktif untuk mengakses
 * Digunakan untuk: Dashboard Penghuni, Tagihan, Pembayaran
 */
class CheckHasRoom
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Cek apakah user memiliki rent yang aktif
        $hasActiveRoom = $user->rents()
            ->where('status', 'active')
            ->whereNull('end_date')
            ->exists();
        
        if (!$hasActiveRoom) {
            return redirect()
                ->route('user.rooms.index')
                ->with('info', 'Anda belum menyewa kamar. Silakan pilih kamar terlebih dahulu.');
        }
        
        return $next($request);
    }
}