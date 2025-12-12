<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cek batas waktu sewa
Schedule::command('rent:check-due')->daily();

// Cek billing jatuh tempo
Schedule::command('billing:check-overdue')->daily();