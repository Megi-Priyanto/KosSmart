<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempatKos;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userMessage = $request->input('message');

        // 1. Ambil data Kos dari Database untuk dijadikan Context
        $kosData = TempatKos::with(['kosInfos', 'rooms' => function ($query) {
            $query->where('status', 'available');
        }])->where('status', 'active')->get();

        $contextString = "Daftar Kos yang tersedia di platform KosSmart:\n";
        foreach ($kosData as $kos) {
            $contextString .= "- Nama Kos: {$kos->nama_kos}\n";
            $contextString .= "  Alamat: {$kos->alamat}\n";
            $contextString .= "  Kota: {$kos->kota}\n";

            if ($kos->kosInfos->count() > 0) {
                $info = $kos->kosInfos->first();
                if ($info->general_facilities) {
                    $fasilitas = is_array($info->general_facilities) ? implode(', ', $info->general_facilities) : $info->general_facilities;
                    $contextString .= "  Fasilitas Umum: {$fasilitas}\n";
                }
            }

            if ($kos->rooms->count() > 0) {
                $contextString .= "  Kamar Tersedia:\n";
                foreach ($kos->rooms as $room) {
                    $contextString .= "    * [Tipe: {$room->type}] Harga: Rp " . number_format($room->price, 0, ',', '.') . " " . ($room->jenis_sewa == 'tahun' ? '/tahun' : '/bulan') . "\n";
                }
            } else {
                $contextString .= "  Kamar Tersedia: Penuh\n";
            }
        }

        // 2. Siapkan Prompt untuk Gemini
        $systemInstruction = "Kamu adalah Asisten AI Virtual untuk KosSmart. Tugasmu adalah membantu pengguna mencari dan mendapatkan informasi mengenai kamar kos. Gunakan HANYA informasi dari daftar kos berikut untuk menjawab pertanyaan pengguna. Jika informasi yang ditanyakan tidak ada di daftar ini, katakan dengan sopan bahwa kamu belum memiliki informasinya atau kos tersebut tidak tersedia.\n\nData Kos:\n" . $contextString;

        $prompt = $systemInstruction . "\n\nPertanyaan Pengguna: " . $userMessage;

        // 3. Panggil Gemini API
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return response()->json([
                'reply' => 'Sistem AI belum dikonfigurasi. Pastikan GEMINI_API_KEY sudah diisi di .env dan **jangan lupa merestart server (php artisan serve)** Anda.'
            ]);
        }

        try {
            // endpoint gemini terbaru v1beta atau v1pro
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey;

            $response = Http::post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $replyMessage = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak dapat memproses jawaban saat ini.';

                return response()->json([
                    'reply' => $replyMessage
                ]);
            } else {
                Log::error('Gemini API Error: ' . $response->body());
                return response()->json([
                    'reply' => 'Maaf, terjadi kesalahan saat menghubungi AI. Silakan periksa kunci API Anda.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('AIChat Exception: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Maaf, terjadi gangguan pada sistem chat kami.'
            ]);
        }
    }
}
