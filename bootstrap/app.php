<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Tambahkan import middleware custom kamu di sini
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        /**
         * 🌍 Global middleware — dijalankan pada setiap request.
         * (pindahan dari $middleware di Kernel.php)
         */
        $middleware->use([
            TrustProxies::class,
            HandleCors::class,
            PreventRequestsDuringMaintenance::class,
            TrimStrings::class,
            ConvertEmptyStringsToNull::class,
        ]);

        /**
         * 🧩 Middleware alias — digunakan di route.
         * Contoh: Route::get('/admin', ...)->middleware('role:admin');
         */
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        // Di sini bisa ditambah custom handler error kalau diperlukan
    })

    ->create();
