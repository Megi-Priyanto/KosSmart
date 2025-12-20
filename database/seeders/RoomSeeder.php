<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\KosInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Room::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil ID kos yang benar (pasti 1)
        $kosId = KosInfo::first()->id;

        $rooms = [
            // Lantai 1 - Putra
            [
                'room_number' => '101',
                'floor' => 'Lantai 1',
                'type' => 'putra',
                'size' => 18.00,
                'price' => 1700000,
                'jenis_sewa' => 'bulan', 
                'description' => 'Kamar nyaman di lantai 1 dengan fasilitas lengkap',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Dispenser', 'Mesin Cuci', 'Listrik', 'Dapur'],
                'images' => [],
                'status' => 'available',
                'kos_info_id' => $kosId,
            ],
            [
                'room_number' => '102',
                'floor' => 'Lantai 1',
                'type' => 'putra',
                'size' => 18.00,
                'price' => 1700000,
                'jenis_sewa' => 'bulan',
                'description' => 'Kamar strategis dekat lobby',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Dispenser', 'Mesin Cuci', 'Listrik', 'Dapur'],
                'images' => [],
                'status' => 'available',
                'kos_info_id' => $kosId,
            ],
            [
                'room_number' => '103',
                'floor' => 'Lantai 1',
                'type' => 'putra',
                'size' => 18.00,
                'price' => 1700000,
                'jenis_sewa' => 'bulan',
                'description' => 'Kamar ekonomis dengan fasilitas standar',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Dispenser', 'Mesin Cuci', 'Listrik', 'Dapur'],
                'images' => [],
                'status' => 'available',
                'kos_info_id' => $kosId,
            ],

            // Lantai 2 - Putri
            [
                'room_number' => '201',
                'floor' => 'Lantai 2',
                'type' => 'putri',
                'size' => 18.00,
                'price' => 1700000,
                'jenis_sewa' => 'bulan', 
                'description' => 'Kamar khusus putri dengan keamanan 24 jam',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Dispenser', 'Mesin Cuci', 'Listrik', 'Dapur'],
                'images' => [],
                'status' => 'available',
                'kos_info_id' => $kosId,
            ],
            [
                'room_number' => '202',
                'floor' => 'Lantai 2',
                'type' => 'putri',
                'size' => 18.00,
                'price' => 1700000,
                'jenis_sewa' => 'bulan',
                'description' => 'Kamar luas dengan balkon',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Dispenser', 'Mesin Cuci', 'Listrik', 'Dapur'],
                'images' => [],
                'status' => 'available',
                'kos_info_id' => $kosId,
            ],
            [
                'room_number' => '203',
                'floor' => 'Lantai 2',
                'type' => 'putri',
                'size' => 18.00,
                'price' => 1700000,
                'jenis_sewa' => 'bulan',
                'description' => 'Kamar dengan view taman',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Dispenser', 'Mesin Cuci', 'Listrik', 'Dapur'],
                'images' => [],
                'status' => 'available',
                'kos_info_id' => $kosId,
            ],

            // Lantai 3 - Campur
            [
                'room_number' => '301',
                'floor' => 'Lantai 3',
                'type' => 'campur',
                'size' => 18.00,
                'price' => 20300000,
                'jenis_sewa' => 'tahun',
                'description' => 'Kamar premium lantai atas',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Dispenser', 'Mesin Cuci', 'Listrik', 'Dapur'],
                'images' => [],
                'status' => 'available',
                'kos_info_id' => $kosId,
            ],
            [
                'room_number' => '302',
                'floor' => 'Lantai 3',
                'type' => 'campur',
                'size' => 18.00,
                'price' => 20300000,
                'jenis_sewa' => 'tahun',
                'description' => 'Kamar premium lantai atas',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Dispenser', 'Mesin Cuci', 'Listrik', 'Dapur'],
                'images' => [],
                'status' => 'available',
                'kos_info_id' => $kosId,
            ],
            [
                'room_number' => '303',
                'floor' => 'Lantai 3',
                'type' => 'campur',
                'size' => 18.00,
                'price' => 20300000,
                'jenis_sewa' => 'tahun',
                'description' => 'Kamar premium lantai atas',
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Kasur', 'Dispenser', 'Mesin Cuci', 'Listrik', 'Dapur'],
                'images' => [],
                'status' => 'available',
                'kos_info_id' => $kosId,
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        $this->command->info(' Berhasil membuat 9 data kamar sample dengan jenis sewa.');
    }
}