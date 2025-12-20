<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

// API
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * ==========================
         * RATE LIMITER UNTUK API
         * ==========================
         */
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );
        });

        /**
         * ==========================
         * VIEW COMPOSER ADMIN
         * ==========================
         */
        View::composer('layouts.admin', function ($view) {

            $overdueNotifications = Notification::where('type', 'overdue')
                ->where('status', 'pending')
                ->latest()
                ->get();

            $view->with('overdueNotifications', $overdueNotifications);
        });
    }
}