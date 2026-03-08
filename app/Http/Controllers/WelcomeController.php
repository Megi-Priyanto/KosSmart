<?php

namespace App\Http\Controllers;

use App\Models\Room;

class WelcomeController
{
    public function index()
    {
        // Ambil 3 kamar secara acak dari semua kos (bypass global scope)
        $heroRooms = Room::withoutTempatKosScope()
            ->with('currentRent')
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('welcome', compact('heroRooms'));
    }
}