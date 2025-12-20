<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\RoomController;
use App\Http\Controllers\Api\User\BookingController;
use App\Http\Controllers\Api\User\BillingController;

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

// ==============================
// PROTECTED ROUTES (Auth Required)
// ==============================
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    
    // Auth User
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    // User: Bookings
    Route::prefix('bookings')->group(function () {
        Route::get('/', [BookingController::class, 'index']);
        Route::post('/', [BookingController::class, 'store']);
        Route::get('/{id}', [BookingController::class, 'show']);
    });

    // User: Billings
    Route::prefix('billings')->group(function () {
        Route::get('/', [BillingController::class, 'index']);
        Route::get('/{id}', [BillingController::class, 'show']);
        Route::post('/{id}/payment', [BillingController::class, 'submitPayment']);
    });
});