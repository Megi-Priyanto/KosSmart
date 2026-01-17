<?php

namespace Database\Seeders;

use App\Models\Rent;
use App\Models\Room;
use App\Models\KosInfo;
use App\Models\TempatKos;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KosInfoSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clean up existing data
        Rent::truncate();
        Room::truncate();
        KosInfo::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil SEMUA tempat kos aktif
        $tempatKosList = TempatKos::where('status', 'active')->get();

        if ($tempatKosList->isEmpty()) {
            $this->command->error('Tidak ada TempatKos aktif!');
            return;
        }

        $this->command->info("Ditemukan {$tempatKosList->count()} tempat kos aktif");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        // Buat KosInfo untuk SETIAP tempat kos
        foreach ($tempatKosList as $tempatKos) {
            // PENTING: Gunakan withoutGlobalScope atau buat langsung dengan insert
            KosInfo::withoutGlobalScope('tempat_kos')->create([
                'tempat_kos_id' => $tempatKos->id, // Explicitly set
                'name' => $tempatKos->nama_kos,
                'address' => $tempatKos->alamat ?? 'Alamat belum diisi',
                'city' => $tempatKos->kota ?? 'Kota belum diisi',
                'province' => $tempatKos->provinsi ?? 'Provinsi belum diisi',
                'postal_code' => '40291',
                'phone' => '081324084335',
                'whatsapp' => '081324084335',
                'email' => strtolower(str_replace(' ', '', $tempatKos->nama_kos)) . '@gmail.com',
                'description' => "Kos modern dan nyaman di {$tempatKos->kota} dengan fasilitas lengkap dan keamanan 24 jam.",
                'general_facilities' => [
                    'Parkir Motor & Mobil',
                    'WiFi 1000 Mbps',
                    'CCTV 24 Jam',
                    'Ruang Santai',
                    'Toilet L/P',
                    'Security',
                    'Taman',
                    'Cafe'
                ],
                'rules' => [
                    'Jam malam maksimal pukul 22.00 WIB',
                    'Dilarang membawa tamu menginap',
                    'Dilarang membawa binatang peliharaan',
                    'Dilarang merokok di dalam kamar',
                    'Wajib menjaga kebersihan bersama'
                ],
                'images' => [],
                'checkin_time' => '08:00:00',
                'checkout_time' => '12:00:00',
                'is_active' => false, // Semua inactive by default
            ]);

            $this->command->info("✓ KosInfo dibuat untuk: {$tempatKos->nama_kos}");
        }

        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("✓ {$tempatKosList->count()} KosInfo berhasil dibuat");
        $this->command->info("⚠ Semua KosInfo masih NONAKTIF");
        $this->command->info("💡 Aktifkan melalui menu Admin → Informasi Kos");
    }
}