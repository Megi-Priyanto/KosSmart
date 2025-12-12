<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rent;
use App\Models\Billing;
use Illuminate\Console\Scheduling\Schedule;

class GenerateMonthlyBilling extends Command
{
    protected $signature = 'billing:generate-monthly';
    protected $description = 'Generate monthly billing for all active rents';

    public function handle()
    {
        $rents = Rent::where('status', 'active')->get();

        foreach ($rents as $rent) {

            $billingMonth = now()->month;
            $billingYear  = now()->year;

            $exists = Billing::where('rent_id', $rent->id)
                ->where('billing_month', $billingMonth)
                ->where('billing_year', $billingYear)
                ->where('tipe', 'bulanan')
                ->exists();

            if (!$exists) {

                Billing::create([
                    'rent_id' => $rent->id,
                    'user_id' => $rent->user_id,
                    'room_id' => $rent->room_id,
                    'tipe' => 'bulanan',
                    'rent_amount' => $rent->amount,

                    'billing_period' => now()->format('F Y'),
                    'billing_year'   => $billingYear,
                    'billing_month'  => $billingMonth,
                    'due_date'       => now()->startOfMonth()->addDays(3),

                    'subtotal'      => $rent->amount,
                    'total_amount'  => $rent->amount,
                ]);
            }
        }

        $this->info('Monthly billing generated.');
    }

    // Laravel 11–12 scheduling
    public function schedule(Schedule $schedule)
    {
        $schedule->command(static::class)->monthly();
    }
}