<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function create(Room $room)
    {
        if (!$room->isAvailable()) {
            return redirect()
                ->route('user.rooms.index')
                ->with('error', 'Maaf, kamar ini sudah tidak tersedia.');
        }

        $room->load('kosInfo');
        $depositAmount = $room->price * 0.5;

        // Payment methods configuration
        $paymentMethods = [
            'manual_transfer' => [
                'label' => 'Transfer Bank',
                'options' => [
                    'bca' => [
                        'name' => 'BCA',
                        'account' => '1234567890',
                        'holder' => 'KosSmart Residence'
                    ],
                    'bni' => [
                        'name' => 'BNI',
                        'account' => '0987654321',
                        'holder' => 'KosSmart Residence'
                    ],
                    'mandiri' => [
                        'name' => 'Mandiri',
                        'account' => '1122334455',
                        'holder' => 'KosSmart Residence'
                    ],
                ]
            ],
            'e_wallet' => [
                'label' => 'E-Wallet',
                'options' => [
                    'dana' => [
                        'name' => 'DANA',
                        'account' => '081234567890',
                        'holder' => 'KosSmart Residence'
                    ],
                    'ovo' => [
                        'name' => 'OVO',
                        'account' => '081234567890',
                        'holder' => 'KosSmart Residence'
                    ],
                    'gopay' => [
                        'name' => 'GoPay',
                        'account' => '081234567890',
                        'holder' => 'KosSmart Residence'
                    ],
                ]
            ],
            'qris' => [
                'label' => 'QRIS',
                'qr_image' => 'qris/sample-qr.png'
            ]
        ];

        return view('user.booking.create', compact('room', 'depositAmount', 'paymentMethods'));
    }

    public function store(Request $request, Room $room)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'payment_method' => 'required|in:manual_transfer,e_wallet,qris',
            'payment_sub_method' => 'required_if:payment_method,manual_transfer,e_wallet|string',
            'deposit_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'agreement' => 'required|accepted',
        ], [
            'start_date.required' => 'Tanggal mulai sewa wajib diisi',
            'start_date.after_or_equal' => 'Tanggal mulai sewa tidak boleh di masa lalu',
            'payment_method.required' => 'Metode pembayaran wajib dipilih',
            'payment_sub_method.required_if' => 'Sub-metode pembayaran wajib dipilih',
            'deposit_proof.required' => 'Bukti transfer DP wajib diunggah',
            'deposit_proof.image' => 'File harus berupa gambar',
            'deposit_proof.max' => 'Ukuran file maksimal 2MB',
            'agreement.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        if (!$room->isAvailable()) {
            return back()
                ->with('error', 'Maaf, kamar sudah disewa orang lain.')
                ->withInput();
        }

        $existingRent = Rent::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($existingRent) {
            return back()
                ->with('error', 'Anda sudah memiliki booking aktif.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Upload bukti pembayaran
            $depositProofPath = $request->file('deposit_proof')
                ->store('deposits', 'public');

            $depositAmount = $room->price * 0.5;

            // Load kosInfo
            $room->load('kosInfo');

            // Validasi kosInfo
            if (!$room->kosInfo) {
                throw new \Exception('Informasi kos tidak ditemukan.');
            }

            if (!$room->kosInfo->tempat_kos_id) {
                throw new \Exception('ID tempat kos tidak valid.');
            }

            // Log untuk debugging
            Log::info('Creating booking', [
                'user_id' => Auth::id(),
                'room_id' => $room->id,
                'tempat_kos_id' => $room->kosInfo->tempat_kos_id,
                'payment_method' => $validated['payment_method'],
                'payment_sub_method' => $validated['payment_sub_method'] ?? 'qris',
            ]);

            // Create rent
            $rent = Rent::create([
                'tempat_kos_id' => $room->kosInfo->tempat_kos_id,
                'user_id' => Auth::id(),
                'room_id' => $room->id,
                'start_date' => $validated['start_date'],
                'end_date' => null,
                'deposit_paid' => $depositAmount,
                'monthly_rent' => $room->price,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_sub_method' => $validated['payment_sub_method'] ?? 'qris',
                'dp_payment_status' => 'pending',
                'notes' => 'Bukti DP: ' . $depositProofPath,
            ]);

            Log::info('Booking created successfully', [
                'rent_id' => $rent->id,
                'tempat_kos_id' => $rent->tempat_kos_id,
            ]);

            DB::commit();

            return redirect()
                ->route('user.dashboard')
                ->with('success', 'Booking berhasil! Menunggu konfirmasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($depositProofPath)) {
                Storage::disk('public')->delete($depositProofPath);
            }

            // Log error
            Log::error('Booking failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'room_id' => $room->id,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses booking: ' . $e->getMessage());
        }
    }

    public function status()
    {
        $rent = Rent::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'active'])
            ->with('room')
            ->latest()
            ->first();

        if (!$rent) {
            return redirect()
                ->route('user.rooms.index')
                ->with('info', 'Anda belum memiliki booking aktif.');
        }

        return view('user.booking.status', compact('rent'));
    }

    public function show(Rent $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke booking ini.');
        }

        $booking->load('room');
        return view('user.booking.show', compact('booking'));
    }
}