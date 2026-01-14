<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua user
        User::query()->delete();

        // Reset auto increment users
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

        // Admin Utama
        User::create([
            'name' => 'Admin KosSmart',
            'email' => 'kossmartinaja@gmail.com',
            'phone' => '0813-2408-4335',
            'password' => Hash::make('kossmartinaja@gmail.com'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info(' User demo berhasil dibuat!');
        $this->command->info('');
        $this->command->info(' Kredensial Login:');
        $this->command->info('-----------------------------------');
        $this->command->info('Admin:');
        $this->command->info('  Email: kossmartinaja@gmail.com');
        $this->command->info('  Password: kossmartinaja@gmail.com');
        $this->command->info('');
        $this->command->info('-----------------------------------');
        $this->command->info('');
        $this->command->info(' Total: 1 users admin)');
    }
}
