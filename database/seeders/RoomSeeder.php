<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan foreign key agar bisa truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Room::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rooms = [
            // Lantai 1 - Putra
            [
                'room_number' => '101',
                'floor' => 'Lantai 1',
                'type' => 'putra',
                'size' => 12.00,
                'price' => 1200000,
                'description' => 'Kamar nyaman di lantai 1 dengan fasilitas lengkap',
                'facilities' => json_encode(['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari']),
                'images' => json_encode([]),
                'status' => 'available',
            ],
            [
                'room_number' => '102',
                'floor' => 'Lantai 1',
                'type' => 'putra',
                'size' => 12.00,
                'price' => 1200000,
                'description' => 'Kamar strategis dekat lobby',
                'facilities' => json_encode(['AC', 'WiFi', 'Kamar Mandi Dalam']),
                'images' => json_encode([]),
                'status' => 'available',
            ],
            [
                'room_number' => '103',
                'floor' => 'Lantai 1',
                'type' => 'putra',
                'size' => 9.00,
                'price' => 1000000,
                'description' => 'Kamar ekonomis dengan fasilitas standar',
                'facilities' => json_encode(['WiFi', 'Kamar Mandi Luar']),
                'images' => json_encode([]),
                'status' => 'available',
            ],

            // Lantai 2 - Putri
            [
                'room_number' => '201',
                'floor' => 'Lantai 2',
                'type' => 'putri',
                'size' => 12.00,
                'price' => 1300000,
                'description' => 'Kamar khusus putri dengan keamanan 24 jam',
                'facilities' => json_encode(['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Meja Rias']),
                'images' => json_encode([]),
                'status' => 'available',
            ],
            [
                'room_number' => '202',
                'floor' => 'Lantai 2',
                'type' => 'putri',
                'size' => 15.00,
                'price' => 1500000,
                'description' => 'Kamar luas dengan balkon',
                'facilities' => json_encode(['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Balkon']),
                'images' => json_encode([]),
                'status' => 'available',
            ],
            [
                'room_number' => '203',
                'floor' => 'Lantai 2',
                'type' => 'putri',
                'size' => 12.00,
                'price' => 1300000,
                'description' => 'Kamar dengan view taman',
                'facilities' => json_encode(['AC', 'WiFi', 'Kamar Mandi Dalam']),
                'images' => json_encode([]),
                'status' => 'available',
            ],

            // Lantai 3 - Campur
            [
                'room_number' => '301',
                'floor' => 'Lantai 3',
                'type' => 'campur',
                'size' => 16.00,
                'price' => 1800000,
                'description' => 'Kamar premium dengan fasilitas terlengkap',
                'facilities' => json_encode(['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Balkon', 'Water Heater']),
                'images' => json_encode([]),
                'status' => 'available',
            ],
            [
                'room_number' => '302',
                'floor' => 'Lantai 3',
                'type' => 'campur',
                'size' => 16.00,
                'price' => 1800000,
                'description' => 'Kamar premium lantai atas',
                'facilities' => json_encode(['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari', 'Balkon', 'Water Heater']),
                'images' => json_encode([]),
                'status' => 'available',
            ],
            [
                'room_number' => '303',
                'floor' => 'Lantai 3',
                'type' => 'campur',
                'size' => 12.00,
                'price' => 1400000,
                'description' => 'Kamar standar lantai 3',
                'facilities' => json_encode(['AC', 'WiFi', 'Kamar Mandi Dalam', 'Lemari']),
                'images' => json_encode([]),
                'status' => 'available',
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }

        $this->command->info(' Berhasil membuat 9 data kamar sample.');
    }
}