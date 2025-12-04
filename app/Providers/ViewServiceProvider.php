<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('admin.*', function ($view) {
            $todayNotifications = Notification::where('status', 'pending')->count();
            $view->with('todayNotifications', $todayNotifications);
        });
    }
}
