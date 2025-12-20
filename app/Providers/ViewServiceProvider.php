<?php
// app/Providers/ViewServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share data to all admin views
        View::composer('layouts.admin', function ($view) {
            // Get overdue billing notifications
            $overdueNotifications = Notification::where('type', 'billing')
                ->where('status', 'unread')
                ->latest()
                ->take(5)
                ->get();
            
            $view->with('overdueNotifications', $overdueNotifications);
        });

        // You can also keep your existing composer
        View::composer('admin.*', function ($view) {
            $todayNotifications = Notification::where('status', 'pending')->count();
            $view->with('todayNotifications', $todayNotifications);
        });
    }
}