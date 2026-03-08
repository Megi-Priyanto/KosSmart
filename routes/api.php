<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\RoomController;

/*
|--------------------------------------------------------------------------
| API Routes - KosSmart
|--------------------------------------------------------------------------
| Prefix: /api
| Middleware: api (throttle, CORS, etc.)
*/

// ==============================
// PUBLIC ROUTES (No Auth Required)
// ==============================
Route::prefix('v1')->group(function () {
    
    // Auth Routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
    });

    // Public Kos Info
    Route::get('/kos-info', [RoomController::class, 'kosInfo']);
    
    // Public Rooms List (untuk preview tanpa login)
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::get('/rooms/{id}', [RoomController::class, 'show']);
});
