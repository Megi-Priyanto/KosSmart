<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckHasRoom
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Cek apakah user memiliki sewa aktif
        $hasRoom = $user->rents()
            ->whereIn('status', ['active', 'checkout_requested'])
            ->exists();

        if (!$user->hasActiveRoom()) {
            return redirect()
                ->route('user.rooms.index')
                ->with('info', 'Anda belum memiliki kamar.');
        }

        return $next($request);
    }
}
