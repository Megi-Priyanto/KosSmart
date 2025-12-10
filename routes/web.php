<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\OtpVerificationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\RoomSelectionController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\Admin\KosInfoController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\RoomStatusController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\Admin\NotificationController;
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
// VERIFIKASI EMAIL DENGAN OTP
// ==============================
Route::prefix('verification')->name('verification.')->group(function () {
    // Halaman input OTP (tidak perlu auth, karena user baru register)
    Route::get('/otp', [OtpVerificationController::class, 'showOtpForm'])
        ->name('otp.form');

    // Proses verifikasi OTP
    Route::post('/otp/verify', [OtpVerificationController::class, 'verifyOtp'])
        ->middleware('throttle:5,1') // Max 5 attempts per minute
        ->name('otp.verify');

    // Kirim ulang OTP
    Route::post('/otp/resend', [OtpVerificationController::class, 'resendOtp'])
        ->middleware('throttle:3,2') // Max 3 attempts per 2 minutes
        ->name('otp.resend');
});

// ==============================
// LOGOUT – HARUS DI LUAR ROLE
// ==============================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==============================
// ROUTE UNTUK USER LOGIN & VERIFIED
// ==============================
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {

    // Dashboard User (cek otomatis apakah punya kamar atau tidak)
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard');

    // ==============================
    // UNTUK USER YANG BELUM PUNYA KAMAR
    // ==============================
    Route::middleware('no.room')->group(function () {
        Route::get('/user/rooms', [RoomSelectionController::class, 'index'])
            ->name('user.rooms.index');

        Route::get('/user/rooms/{room}', [RoomSelectionController::class, 'show'])
            ->name('user.rooms.show');

        Route::get('/user/rooms/{room}/booking', [BookingController::class, 'create'])
            ->name('user.booking.create');

        Route::post('/user/rooms/{room}/booking', [BookingController::class, 'store'])
            ->name('user.booking.store');

        Route::get('/user/booking/status', [BookingController::class, 'status'])
            ->name('user.booking.status');
    });

    // ==============================
    // UNTUK USER YANG SUDAH PUNYA KAMAR
    // ==============================
    Route::middleware('has.room')->group(function () {
        Route::get('/user/payments', [PaymentController::class, 'index'])
            ->name('user.payments');

        // Billing Management
        Route::controller(\App\Http\Controllers\User\UserBillingController::class)->group(function () {
            Route::get('/billing', 'index')->name('user.billing.index');
            Route::get('/billing/{billing}', 'show')->name('user.billing.show');
            Route::get('/billing/{billing}/pay', 'paymentForm')->name('user.billing.pay');
            Route::post('/billing/{billing}/pay', 'submitPayment')->name('user.billing.submit-payment');
            Route::get('/payment-history', 'paymentHistory')->name('user.payment.history');
        });
    });

    // Profil User
    Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');
    Route::put('/user/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::put('/user/profile/password', [UserProfileController::class, 'updatePassword'])->name('user.profile.password');
    Route::delete('/user/profile', [UserProfileController::class, 'destroy'])->name('user.profile.delete');
});

// ==============================
// ROUTE UNTUK ADMIN
// ==============================
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Kelola Info Kos
        Route::controller(KosInfoController::class)->group(function () {
            Route::get('/kos', 'index')->name('kos.index');
            Route::get('/kos/create', 'create')->name('kos.create');
            Route::post('/kos', 'store')->name('kos.store');
            Route::get('/kos/edit', 'edit')->name('kos.edit');
            Route::put('/kos', 'update')->name('kos.update');
        });

        Route::patch('/kos/{id}/toggle-apply', [KosInfoController::class, 'toggleApply'])
    ->name('kos-info.toggle-apply');


        // Kelola Kamar
        Route::resource('rooms', AdminRoomController::class)->names([
            'index' => 'rooms.index',
            'create' => 'rooms.create',
            'store' => 'rooms.store',
            'show' => 'rooms.show',
            'edit' => 'rooms.edit',
            'update' => 'rooms.update',
            'destroy' => 'rooms.destroy',
        ]);

        // Update Status Kamar
        Route::put('/rooms/{room}/status', [RoomStatusController::class, 'update'])
            ->name('rooms.status.update');
        Route::post('/rooms/bulk-status', [RoomStatusController::class, 'bulkUpdate'])
            ->name('rooms.status.bulk');

        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('billing', \App\Http\Controllers\Admin\BillingController::class);
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])
            ->name('settings.index');

        // Booking Management
        Route::controller(BookingManagementController::class)->group(function () {
            Route::get('/bookings', 'index')->name('bookings.index');
            Route::get('/bookings/{booking}', 'show')->name('bookings.show');
            Route::post('/bookings/{booking}/approve', 'approve')->name('bookings.approve');
            Route::post('/bookings/{booking}/reject', 'reject')->name('bookings.reject');
        });

        // Billing Management
        Route::controller(\App\Http\Controllers\Admin\BillingController::class)->group(function () {
            Route::get('/billing', 'index')->name('billing.index');
            Route::get('/billing/create', 'create')->name('billing.create');
            Route::post('/billing', 'store')->name('billing.store');
            Route::get('/billing/{billing}', 'show')->name('billing.show');
            Route::get('/billing/{billing}/edit', 'edit')->name('billing.edit');
            Route::put('/billing/{billing}', 'update')->name('billing.update');
            Route::delete('/billing/{billing}', 'destroy')->name('billing.destroy');

            // Payment verification
            Route::post('/billing/payment/{payment}/verify', 'verifyPayment')->name('billing.payment.verify');
            Route::post('/billing/{billing}/mark-paid', 'markAsPaid')->name('billing.mark-paid');

            // Bulk generate tagihan
            Route::post('/billing/bulk-generate', 'bulkGenerate')->name('billing.bulk-generate');
        });

        // Laporan
        Route::controller(\App\Http\Controllers\Admin\ReportController::class)->group(function () {
            Route::get('/reports', 'index')->name('reports.index');
            Route::get('/reports/export-pdf', 'exportPdf')->name('reports.export-pdf');
            Route::get('/reports/export-excel', 'exportExcel')->name('reports.export-excel');
            Route::get('/reports/payments', 'paymentReport')->name('reports.payments');
            Route::get('/reports/financial', 'financialSummary')->name('reports.financial');
        });

        // Notification Admin
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notifications', 'index')->name('notifications');
            Route::get('/notifications/{notification}', 'detail')->name('notifications.detail');
            Route::post('/notifications/item/{item}/process', 'processItem')->name('notifications.process');
        });
    });