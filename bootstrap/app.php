<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Tambahkan import middleware custom
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\CheckHasRoom;
use App\Http\Middleware\CheckNoRoom;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Http\Middleware\HandleCors;

use Illuminate\Console\Scheduling\Schedule; // <-- WAJIB DITAMBAHKAN

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->use([
            TrustProxies::class,
            HandleCors::class,
            PreventRequestsDuringMaintenance::class,
            TrimStrings::class,
            ConvertEmptyStringsToNull::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'no.room' => CheckNoRoom::class,
            'has.room' => CheckHasRoom::class,
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
        // Generate tagihan bulanan setiap awal bulan
        $schedule->command(\App\Console\Commands\GenerateMonthlyBilling::class)->monthly();

        // Cek tagihan jatuh tempo setiap hari jam 00:00
        $schedule->command(\App\Console\Commands\CheckOverdueBilling::class)->daily();
    })

    ->create();
