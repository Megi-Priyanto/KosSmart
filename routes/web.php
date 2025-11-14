<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KosController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\User\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - KosSmart
|--------------------------------------------------------------------------
*/

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// ==============================
// ROUTE UNTUK TAMU (BELUM LOGIN)
// ==============================
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register Routes
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Lupa Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// ==============================
// VERIFIKASI EMAIL
// ==============================
Route::get('/email/verify', [RegisterController::class, 'verificationNotice'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [RegisterController::class, 'resendVerification'])
    ->middleware(['auth', 'throttle:3,1'])
    ->name('verification.resend');

// ==============================
// ROUTE UNTUK USER LOGIN & VERIFIED
// ==============================
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard umum
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==============================
// HALAMAN PENDING VERIFIKASI
// ==============================
Route::middleware('auth')->group(function () {
    Route::get('/email/verify/pending', function () {
        return view('auth.verify-email');
    })->name('verification.pending');
});

// ==============================
// ROUTE BERDASARKAN ROLE
// ==============================

// Hanya untuk admin
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); // resources/views/admin/dashboard.blade.php
        })->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('kos', KosController::class);
        Route::resource('billing', BillingController::class);
        Route::resource('reports', \App\Http\Controllers\Admin\ReportController::class);
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    });

// Hanya untuk user biasa
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    // Dashboard user
    Route::get('/user/dashboard', function () {
        return view('user.dashboard'); // buat file resources/views/user/dashboard.blade.php
    })->name('user.dashboard');

    Route::get('/payments', function () {
        return view('user.payments'); // buat file resources/views/user/payments.blade.php
    })->name('payments');

    // Profil user
    Route::get('/user/profile', function () {
        return view('user.profile'); // buat file resources/views/user/profile.blade.php
    })->name('user.profile');

    Route::get('/user/payments', [PaymentController::class, 'index'])->name('user.payments');
});
