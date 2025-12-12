<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Billing;
use App\Models\Notification;

class CheckOverdueBilling extends Command
{
    protected $signature = 'billing:check-overdue';
    protected $description = 'Cek tagihan yang jatuh tempo dan buat notifikasi single-table';

    public function handle()
    {
        $today = now()->toDateString();

        // Ambil billing yang jatuh tempo dan belum overdue
        $overdues = Billing::whereIn('status', ['unpaid', 'pending'])
            ->whereDate('due_date', '<', $today)
            ->get();

        if ($overdues->isEmpty()) {
            $this->info("Tidak ada tagihan jatuh tempo.");
            return;
        }

        $count = 0;

        foreach ($overdues as $bill) {

            // Update status tagihan menjadi overdue
            $bill->update(['status' => 'overdue']);

            // Buat notifikasi single-table
            Notification::create([
                'title' => 'Tagihan Jatuh Tempo',
                'notification_date' => now(),
                'status' => 'pending',

                // kolom baru dari single-table
                'rent_id' => $bill->rent_id,
                'user_id' => $bill->user_id,
                'room_id' => $bill->room_id,
                'due_date' => $bill->due_date,
            ]);

            $count++;
        }

        $this->info("Total tagihan overdue diproses: $count");
    }
}