<?php
// database/seeders/BillingSeeder.php

namespace Database\Seeders;

use App\Models\Billing;
use App\Models\Rent;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    public function run(): void
    {
        $activeRents = Rent::where('status', 'active')->get();

        if ($activeRents->isEmpty()) {
            $this->command->warn('Tidak ada rent aktif. Jalankan RentSeeder terlebih dahulu.');
            return;
        }

        $currentMonth = now()->month;
        $currentYear = now()->year;

        foreach ($activeRents as $rent) {
            // Buat tagihan bulan ini
            $this->createBilling($rent, $currentYear, $currentMonth, 'unpaid');

            // Buat tagihan bulan lalu (lunas)
            $lastMonth = $currentMonth - 1;
            $lastYear = $lastMonth < 1 ? $currentYear - 1 : $currentYear;
            $lastMonth = $lastMonth < 1 ? 12 : $lastMonth;
            $this->createBilling($rent, $lastYear, $lastMonth, 'paid', true);

            // Buat tagihan 2 bulan lalu (lunas)
            $twoMonthsAgo = $currentMonth - 2;
            $twoMonthsYear = $twoMonthsAgo < 1 ? $currentYear - 1 : $currentYear;
            $twoMonthsAgo = $twoMonthsAgo < 1 ? 12 + $twoMonthsAgo : $twoMonthsAgo;
            $this->createBilling($rent, $twoMonthsYear, $twoMonthsAgo, 'paid', true);
        }

        $this->command->info('✓ Billing seeder berhasil!');
    }

    private function createBilling($rent, $year, $month, $status, $paid = false)
    {
        $billing = Billing::create([
            'rent_id' => $rent->id,
            'user_id' => $rent->user_id,
            'room_id' => $rent->room_id,
            'billing_month' => $month,
            'billing_year' => $year,
            'billing_period' => Carbon::create($year, $month)->format('Y-m'),
            'rent_amount' => $rent->room->price,
            'electricity_cost' => rand(50000, 200000),
            'water_cost' => rand(20000, 50000),
            'maintenance_cost' => 0,
            'other_costs' => 0,
            'subtotal' => 0,
            'discount' => 0,
            'total_amount' => 0,
            'due_date' => Carbon::create($year, $month)->addDays(10),
            'status' => $status,
            'paid_date' => $paid ? Carbon::create($year, $month)->addDays(5) : null,
            'admin_notes' => $paid ? 'Pembayaran tepat waktu' : null,
        ]);

        $billing->calculateTotal();
        $billing->save();

        return $billing;
    }
}