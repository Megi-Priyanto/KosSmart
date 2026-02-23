<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\OtpVerificationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\RoomSelectionController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\UserBillingController;
use App\Http\Controllers\User\StatusController;
use App\Http\Controllers\Admin\KosInfoController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\RoomStatusController;
use App\Http\Controllers\Admin\RentCheckoutController as AdminRentCheckoutController;
use App\Http\Controllers\User\RentCheckoutController as UserRentCheckoutController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\SuperAdmin\DisbursementController;
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
// GUEST ROUTES
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
// EMAIL VERIFICATION (REGISTER)
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
// EMAIL CHANGE VERIFICATION (TANPA AUTH)
// ==============================
// CRITICAL: Routes ini HARUS di luar middleware auth
// karena user sudah logout setelah klik "Simpan Email"
Route::prefix('user/profile/email')->name('user.profile.email.')->group(function () {

    // Halaman verifikasi OTP untuk email baru (user sudah logout)
    Route::get('/verify', [UserProfileController::class, 'showEmailVerification'])
        ->name('verify');

    // Proses verifikasi OTP
    Route::post('/verify', [UserProfileController::class, 'verifyEmailChange'])
        ->middleware('throttle:5,1')
        ->name('verify.process');

    // Kirim ulang OTP
    Route::post('/resend', [UserProfileController::class, 'resendEmailOtp'])
        ->middleware('throttle:3,2')
        ->name('resend');

    // Batal perubahan email
    Route::post('/cancel', [UserProfileController::class, 'cancelEmailChange'])
        ->name('cancel');
});

// ==============================
// LOGOUT
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

    // Detail Kamar
    Route::get('/user/room/detail', [UserDashboardController::class, 'roomDetail'])
        ->middleware('has.room')
        ->name('user.room.detail');

    // Checkout User - Request checkout
    Route::post('/user/rents/{rent}/checkout/request', [UserRentCheckoutController::class, 'requestCheckout'])
        ->name('user.checkout.request');

    Route::prefix('status')->name('user.status.')->group(function () {
        Route::get('/', [StatusController::class, 'index'])->name('index');
        Route::get('/booking', [StatusController::class, 'booking'])->name('booking');
        Route::get('/tagihan', [StatusController::class, 'billing'])->name('billing');
        Route::get('/checkout', [StatusController::class, 'checkout'])->name('checkout');
    });

    // User Cancel Booking Routes
    Route::post('/booking/{rent}/cancel', [\App\Http\Controllers\User\UserCancelBookingController::class, 'store'])
        ->name('user.booking.cancel.store');

    Route::get('/booking/cancel/status', [\App\Http\Controllers\User\UserCancelBookingController::class, 'status'])
        ->name('user.booking.cancel.status');

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
        // Billing Management
        Route::controller(\App\Http\Controllers\User\UserBillingController::class)->group(function () {
            Route::get('/billing', 'index')->name('user.billing.index');
            Route::get('/billing/{billing}', 'show')->name('user.billing.show');
            Route::get('/billing/{billing}/pay', 'paymentForm')->name('user.billing.pay');
            Route::post('/billing/{billing}/pay', 'submitPayment')->name('user.billing.submit-payment');
            Route::get('/payment-history', 'paymentHistory')->name('user.payment.history');
        });
    });

    // ==============================
    // PROFIL USER
    // ==============================
    Route::get('/user/profile', [UserProfileController::class, 'index'])
        ->name('user.profile');

    Route::put('/user/profile', [UserProfileController::class, 'update'])
        ->name('user.profile.update');

    Route::put('/user/profile/password', [UserProfileController::class, 'updatePassword'])
        ->name('user.profile.password');

    Route::delete('/user/profile', [UserProfileController::class, 'destroy'])
        ->name('user.profile.delete');

    // Request email change - PERLU AUTH (user masih login saat mengisi form)
    Route::post('/user/profile/email/request', [UserProfileController::class, 'requestEmailChange'])
        ->name('user.profile.email.request');
});

// ==============================
// SUPER ADMIN ROUTES
// ==============================
Route::middleware(['auth', 'verified', 'super.admin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\SuperAdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Profile
        Route::get('/profile', [\App\Http\Controllers\SuperAdmin\ProfileController::class, 'index'])
            ->name('profile');
        Route::put('/profile', [\App\Http\Controllers\SuperAdmin\ProfileController::class, 'update'])
            ->name('profile.update');
        Route::put('/profile/password', [\App\Http\Controllers\SuperAdmin\ProfileController::class, 'updatePassword'])
            ->name('profile.password');

        // Tempat Kos Management
        Route::resource('tempat-kos', \App\Http\Controllers\SuperAdmin\TempatKosController::class)->parameters(['tempat-kos' => 'tempat_kos']);

        // User Management (Super Admin only)
        Route::resource('users', \App\Http\Controllers\SuperAdmin\UserController::class);

        // Billing Report (Laporan Tagihan - tetap dipertahankan)
        Route::prefix('billing-report')->name('billing-report.')->group(function () {
            Route::get('/', [\App\Http\Controllers\SuperAdmin\SuperAdminBillingReportController::class, 'index'])
                ->name('index');
            Route::get('/export-pdf', [\App\Http\Controllers\SuperAdmin\SuperAdminBillingReportController::class, 'exportPdf'])
                ->name('export-pdf');
            Route::get('/export-excel', [\App\Http\Controllers\SuperAdmin\SuperAdminBillingReportController::class, 'exportExcel'])
                ->name('export-excel');
        });

        // Notifications
        Route::get('notifications', [\App\Http\Controllers\SuperAdmin\SuperAdminNotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/mark-all-read', [\App\Http\Controllers\SuperAdmin\SuperAdminNotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

        Route::post('notifications/{notification}/mark-read', function (\App\Models\Notification $notification) {
            if ($notification->user_id === auth()->id()) {
                $notification->markAsRead();
            }
            return response()->json(['success' => true]);
        })->name('notifications.mark-read');

        // System Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'index'])
                ->name('index');
            Route::put('/update', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'update'])
                ->name('update');
        });

        // ============================================================
        // DISBURSEMENT ROUTES
        // Superadmin mencairkan dana holding ke admin kos
        // ============================================================
        Route::prefix('disbursements')->name('disbursements.')->group(function () {

            // GET /superadmin/disbursements
            // Dashboard: ringkasan holding & riwayat disbursement
            Route::get('/', [DisbursementController::class, 'index'])
                ->name('index');

            // GET /superadmin/disbursements/create?tempat_kos_id=X
            // Form: pilih payment holding untuk dicairkan
            Route::get('/create', [DisbursementController::class, 'create'])
                ->name('create');

            // POST /superadmin/disbursements
            // Proses: cairkan dana ke admin
            Route::post('/', [DisbursementController::class, 'store'])
                ->name('store');

            // GET /superadmin/disbursements/{disbursement}
            // Detail satu disbursement
            Route::get('/{disbursement}', [DisbursementController::class, 'show'])
                ->name('show');

            // GET /superadmin/disbursements/holding-summary (AJAX)
            // API endpoint untuk widget dashboard
            Route::get('/api/holding-summary', [DisbursementController::class, 'holdingSummary'])
                ->name('holding-summary');
        });

        // ============================================================
        // REFUND CANCEL BOOKING ROUTES (Superadmin proses refund DP)
        // ============================================================
        Route::prefix('refunds')->name('refunds.')->group(function () {
            // GET /superadmin/refunds - Daftar cancel booking yg butuh direfund
            Route::get('/', [\App\Http\Controllers\SuperAdmin\SuperAdminRefundController::class, 'index'])
                ->name('index');

            // GET /superadmin/refunds/{cancelBooking} - Detail + form refund
            Route::get('/{cancelBooking}', [\App\Http\Controllers\SuperAdmin\SuperAdminRefundController::class, 'show'])
                ->name('show');

            // POST /superadmin/refunds/{cancelBooking}/process - Proses refund
            Route::post('/{cancelBooking}/process', [\App\Http\Controllers\SuperAdmin\SuperAdminRefundController::class, 'processRefund'])
                ->name('process');
        });
    });

// ==============================
// ADMIN KOS ROUTES
// ==============================
Route::middleware(['auth', 'verified', 'admin.kos'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Profile
        Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Kos Info
        Route::prefix('kos')->name('kos.')->group(function () {

            Route::get('/', [\App\Http\Controllers\Admin\KosInfoController::class, 'index'])
                ->name('index');

            Route::get('/create', [\App\Http\Controllers\Admin\KosInfoController::class, 'create'])
                ->name('create');

            Route::post('/', [\App\Http\Controllers\Admin\KosInfoController::class, 'store'])
                ->name('store');

            Route::get('/{kos}', [\App\Http\Controllers\Admin\KosInfoController::class, 'show'])
                ->name('show');

            Route::get('/{kos}/edit', [\App\Http\Controllers\Admin\KosInfoController::class, 'edit'])
                ->name('edit');

            Route::put('/{kos}', [\App\Http\Controllers\Admin\KosInfoController::class, 'update'])
                ->name('update');

            Route::post('/{kos}/activate', [\App\Http\Controllers\Admin\KosInfoController::class, 'activate'])
                ->name('activate');

            Route::post('/{kos}/deactivate', [\App\Http\Controllers\Admin\KosInfoController::class, 'deactivate'])
                ->name('deactivate');
        });

        // Room
        Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);

        Route::put('/rooms/{room}/status', [\App\Http\Controllers\Admin\RoomStatusController::class, 'update'])
            ->name('rooms.status.update');

        Route::post('/rooms/bulk-status', [\App\Http\Controllers\Admin\RoomStatusController::class, 'bulkUpdate'])
            ->name('rooms.status.bulk');

        // Billing
        Route::resource('billing', \App\Http\Controllers\Admin\BillingController::class);

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

        // Checkout Routes
        Route::post('/rooms/{room}/checkout/force', [AdminRoomController::class, 'forceCheckout'])
            ->name('rooms.checkout.force');

        Route::post('/rooms/{room}/checkout/{checkoutRequest}/approve', [AdminRoomController::class, 'approveCheckout'])
            ->name('rooms.checkout.approve');

        // Update Status Kamar
        Route::put('/rooms/{room}/status', [RoomStatusController::class, 'update'])
            ->name('rooms.status.update');

        Route::post('/rooms/bulk-status', [RoomStatusController::class, 'bulkUpdate'])
            ->name('rooms.status.bulk');

        Route::resource('billing', \App\Http\Controllers\Admin\BillingController::class);

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

            // Payment Actions
            Route::post('/billing/{billing}/confirm-payment', 'confirmPayment')->name('billing.confirm-payment');
            Route::post('/billing/{billing}/reject-payment', 'rejectPayment')->name('billing.reject-payment');
            Route::post('/billing/{billing}/mark-paid', 'markAsPaid')->name('billing.mark-paid');

            // Payment verification
            Route::post('/billing/payment/{payment}/verify', 'verifyPayment')->name('billing.payment.verify');

            // Generate tagihan
            Route::post('/billing/bulk-generate', 'bulkGenerate')->name('billing.bulk-generate');

            // Checkout User
            Route::put('/rents/{rent}/checkout', [AdminRentCheckoutController::class, 'checkout'])
                ->name('rents.checkout');
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

        Route::get('/cancel-bookings', [\App\Http\Controllers\Admin\AdminCancelBookingController::class, 'index'])
            ->name('cancel-bookings.index');

        // Detail + form approve/reject untuk admin
        Route::get('/cancel-bookings/{cancelBooking}', [\App\Http\Controllers\Admin\AdminCancelBookingController::class, 'show'])
            ->name('cancel-bookings.show');

        // Admin MENYETUJUI → forward ke superadmin
        Route::post('/cancel-bookings/{cancelBooking}/approve', [\App\Http\Controllers\Admin\AdminCancelBookingController::class, 'approve'])
            ->name('cancel-bookings.approve');

        // Admin MENOLAK
        Route::post('/cancel-bookings/{cancelBooking}/reject', [\App\Http\Controllers\Admin\AdminCancelBookingController::class, 'reject'])
            ->name('cancel-bookings.reject');
    });
