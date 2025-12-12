<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

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
        // Kirim overdue notifications otomatis ke layout admin
        View::composer('layouts.admin', function ($view) {

            $overdueNotifications = Notification::where('type', 'overdue')
                ->where('status', 'pending')
                ->latest()
                ->get();

            $view->with('overdueNotifications', $overdueNotifications);
        });
    }
}