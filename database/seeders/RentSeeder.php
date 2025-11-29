<?php

namespace Database\Seeders;

use App\Models\Rent;
use App\Models\User;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RentSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus billings terlebih dahulu
        \App\Models\Billing::query()->delete();

        // Baru hapus rents
        \App\Models\Rent::query()->delete();

        // Reset status kamar
        \App\Models\Room::query()->update(['status' => 'available']);

        $this->command->info('✔ Semua riwayat booking & billing berhasil dihapus.');
    }
}
