<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rent;
use App\Models\Notification;
use App\Models\NotificationItem;
use Carbon\Carbon;

class CheckRentDueCommand extends Command
{
    protected $signature = 'rent:check-due';
    protected $description = 'Cek penyewa yang jatuh tempo hari ini lalu buat notifikasi';

    public function handle()
    {
        $today = Carbon::today();

        // Cek apakah sudah ada notifikasi hari ini
        $notification = Notification::firstOrCreate(
            ['notification_date' => $today],
            ['status' => 'pending']
        );

        // Ambil penyewa aktif
        $rents = Rent::where('status', 'active')->get();

        foreach ($rents as $rent) {
            // Jatuh tempo = start_date + n bulan
            $months = $rent->billings()->count(); 
            $dueDate = $rent->start_date->copy()->addMonths($months);

            if ($dueDate->isSameDay($today)) {
                NotificationItem::firstOrCreate([
                    'notification_id' => $notification->id,
                    'rent_id' => $rent->id,
                    'user_id' => $rent->user_id,
                    'room_id' => $rent->room_id,
                    'due_date' => $dueDate,
                ]);
            }
        }

        return Command::SUCCESS;
    }
}
