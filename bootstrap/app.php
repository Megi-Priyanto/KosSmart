<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

// Middleware
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\AdminKosMiddleware;
use App\Http\Middleware\CheckHasRoom;
use App\Http\Middleware\CheckNoRoom;

use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        
        // Global middleware
        $middleware->use([
            TrustProxies::class,
            HandleCors::class,
            PreventRequestsDuringMaintenance::class,
            TrimStrings::class,
            ConvertEmptyStringsToNull::class,
        ]);

        // Middleware alias
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'super.admin' => SuperAdminMiddleware::class,
            'admin.kos' => AdminKosMiddleware::class,
            'no.room' => CheckNoRoom::class,
            'has.room' => CheckHasRoom::class,
        ]);

        // Middleware group API
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->withProviders([
        App\Providers\ViewServiceProvider::class,
    ])

    ->withCommands([
        App\Console\Commands\CheckRentDueCommand::class,
        App\Console\Commands\CheckOverdueBilling::class,
        App\Console\Commands\GenerateMonthlyBilling::class,
    ])

    ->withSchedule(function (Schedule $schedule) {
        $schedule->command(\App\Console\Commands\GenerateMonthlyBilling::class)->monthly();
        $schedule->command(\App\Console\Commands\CheckOverdueBilling::class)->daily();
    })

    ->create();