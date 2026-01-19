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
        // Validasi kamar HANYA tersedia untuk booking jika statusnya 'available'
        if ($room->status !== 'available') {
            $message = match($room->status) {
                'occupied' => 'Maaf, kamar ini sudah terisi.',
                'maintenance' => 'Maaf, kamar sedang dalam perbaikan.',
                default => 'Maaf, kamar ini tidak tersedia.'
            };
            
            return redirect()
                ->route('user.rooms.index')
                ->with('error', $message);
        }

        $room->load('kosInfo');
        $depositAmount = $room->price * 0.5;

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

        // Validasi status room HARUS available
        if ($room->status !== 'available') {
            return back()
                ->with('error', 'Maaf, kamar sudah tidak tersedia untuk booking.')
                ->withInput();
        }

        // Double check race condition
        if ($room->currentRent()->exists()) {
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
            $room->load('kosInfo');

            if (!$room->kosInfo || !$room->kosInfo->tempat_kos_id) {
                throw new \Exception('Informasi kos tidak valid.');
            }

            Log::info('Creating booking', [
                'user_id' => Auth::id(),
                'room_id' => $room->id,
                'tempat_kos_id' => $room->kosInfo->tempat_kos_id,
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

            // KUNCI UTAMA: AUTO UPDATE STATUS ROOM → 'occupied'
            $room->update(['status' => 'occupied']);

            Log::info('Booking created successfully', [
                'rent_id' => $rent->id,
                'room_status_updated' => 'occupied',
            ]);

            DB::commit();

            return redirect()
                ->route('user.dashboard')
                ->with('success', 'Booking berhasil! Kamar telah direservasi. Menunggu konfirmasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($depositProofPath)) {
                Storage::disk('public')->delete($depositProofPath);
            }

            Log::error('Booking failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'room_id' => $room->id,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

    /**
     * CANCEL BOOKING - Kembalikan status room ke 'available'
     */
    public function cancel(Rent $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke booking ini.');
        }

        if ($booking->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Booking tidak dapat dibatalkan karena sudah dikonfirmasi.');
        }

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'cancelled',
                'end_date' => now()
            ]);

            // Kembalikan status room ke 'available'
            $booking->room->update(['status' => 'available']);

            Log::info('Booking cancelled', [
                'rent_id' => $booking->id,
                'room_status_updated' => 'available',
            ]);

            DB::commit();

            return redirect()->route('user.dashboard')
                ->with('success', 'Booking berhasil dibatalkan. Kamar kembali tersedia.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Booking cancellation failed', [
                'error' => $e->getMessage(),
                'rent_id' => $booking->id,
            ]);

            return redirect()->back()
                ->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
        }
    }

    /**
     * REQUEST CHECKOUT - Status room tetap 'occupied' sampai admin approve
     */
    public function requestCheckout(Rent $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke booking ini.');
        }

        if ($booking->status !== 'active') {
            return redirect()->back()
                ->with('error', 'Hanya booking aktif yang dapat checkout.');
        }

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'checkout_requested',
                'checkout_request_date' => now()
            ]);

            // Status room TETAP 'occupied' sampai admin approve
            // Setelah admin approve, baru room->update(['status' => 'available'])

            Log::info('Checkout requested', [
                'rent_id' => $booking->id,
            ]);

            DB::commit();

            return redirect()->route('user.dashboard')
                ->with('success', 'Permintaan checkout dikirim. Menunggu persetujuan admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Checkout request failed', [
                'error' => $e->getMessage(),
                'rent_id' => $booking->id,
            ]);

            return redirect()->back()
                ->with('error', 'Gagal request checkout: ' . $e->getMessage());
        }
    }
}