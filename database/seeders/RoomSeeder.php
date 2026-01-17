<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\KosInfo;
use App\Models\TempatKos;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Room::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // PILIH TEMPAT KOS YANG AKAN DIBERI KAMAR
        // Ganti 'Kos Melati Asri' dengan nama tempat kos yang Anda inginkan
        $tempatKos = TempatKos::where('nama_kos', 'Kos Melati Asri')
            ->where('status', 'active')
            ->first();

        if (!$tempatKos) {
            $this->command->error('Tempat kos "Kos Melati Asri" tidak ditemukan!');
            $this->command->info('Tempat kos yang tersedia:');
            TempatKos::where('status', 'active')->get()->each(function($kos) {
                $this->command->info("  - {$kos->nama_kos} (ID: {$kos->id})");
            });
            return;
        }

        // Cari KosInfo untuk tempat kos ini (tanpa filter is_active untuk seeder)
        $kosInfo = KosInfo::where('tempat_kos_id', $tempatKos->id)->first();

        if (!$kosInfo) {
            $this->command->error("KosInfo untuk {$tempatKos->nama_kos} belum ada!");
            $this->command->info("Jalankan: php artisan db:seed --class=KosInfoSeeder");
            return;
        }

        $this->command->info("Target: {$tempatKos->nama_kos}");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        // Template kamar yang akan dibuat
        $roomTemplates = [
            // Lantai 1 - Putra
            [
                'room_number' => '101',
                'floor' => 'Lantai 1',
                'type' => 'putra',
                'capacity' => 4,
                'size' => 18.00,
                'price' => 1700000,
                'jenis_sewa' => 'bulan',
                'description' => 'Kamar nyaman di lantai 1 dengan fasilitas lengkap',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Dispenser'],
                'status' => 'available',
            ],
            [
                'room_number' => '102',
                'floor' => 'Lantai 1',
                'type' => 'putra',
                'capacity' => 4,
                'size' => 18.00,
                'price' => 1700000,
                'jenis_sewa' => 'bulan',
                'description' => 'Kamar strategis dekat lobby',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur'],
                'status' => 'available',
            ],
            [
                'room_number' => '103',
                'floor' => 'Lantai 1',
                'type' => 'putra',
                'capacity' => 4,
                'size' => 18.00,
                'price' => 1700000,
                'jenis_sewa' => 'bulan',
                'description' => 'Kamar ekonomis dengan fasilitas standar',
                'facilities' => ['WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur'],
                'status' => 'available',
            ],
            // Lantai 2 - Putri
            [
                'room_number' => '201',
                'floor' => 'Lantai 2',
                'type' => 'putri',
                'capacity' => 4,
                'size' => 18.00,
                'price' => 1700000,
                'jenis_sewa' => 'bulan',
                'description' => 'Kamar khusus putri dengan keamanan 24 jam',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Dispenser'],
                'status' => 'available',
            ],
            [
                'room_number' => '202',
                'floor' => 'Lantai 2',
                'type' => 'putri',
                'capacity' => 4,
                'size' => 18.00,
                'price' => 1700000,
                'jenis_sewa' => 'bulan',
                'description' => 'Kamar luas dengan balkon',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Balkon'],
                'status' => 'available',
            ],
            [
                'room_number' => '203',
                'floor' => 'Lantai 2',
                'type' => 'putri',
                'capacity' => 4,
                'size' => 18.00,
                'price' => 1700000,
                'jenis_sewa' => 'bulan',
                'description' => 'Kamar dengan view taman',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur'],
                'status' => 'available',
            ],
            // Lantai 3 - Campur
            [
                'room_number' => '301',
                'floor' => 'Lantai 3',
                'type' => 'campur',
                'capacity' => 4,
                'size' => 18.00,
                'price' => 20300000,
                'jenis_sewa' => 'tahun',
                'description' => 'Kamar premium lantai atas',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Dispenser', 'TV'],
                'status' => 'available',
            ],
            [
                'room_number' => '302',
                'floor' => 'Lantai 3',
                'type' => 'campur',
                'capacity' => 4,
                'size' => 18.00,
                'price' => 20300000,
                'jenis_sewa' => 'tahun',
                'description' => 'Kamar premium dengan AC split',
                'facilities' => ['AC Split', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'TV'],
                'status' => 'available',
            ],
            [
                'room_number' => '303',
                'floor' => 'Lantai 3',
                'type' => 'campur',
                'capacity' => 4,
                'size' => 18.00,
                'price' => 20300000,
                'jenis_sewa' => 'tahun',
                'description' => 'Kamar premium view kota',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Balkon'],
                'status' => 'available',
            ],
        ];

        $totalCreated = 0;

        // Buat SEMUA kamar untuk tempat kos yang dipilih
        foreach ($roomTemplates as $template) {
            $roomData = array_merge($template, [
                'tempat_kos_id' => $tempatKos->id,
                'kos_info_id' => $kosInfo->id,
                'images' => [],
            ]);

            Room::create($roomData);
            
            $this->command->info("✓ Kamar {$template['room_number']} dibuat");
            $totalCreated++;
        }

        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("✓ Seeder selesai!");
        $this->command->info("✓ Total {$totalCreated} kamar berhasil dibuat");
        $this->command->info("✓ Semua kamar ditambahkan ke: {$tempatKos->nama_kos}");
    }
}