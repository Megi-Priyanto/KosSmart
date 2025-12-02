<?php
// app/Http/Controllers/User/BookingController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    /**
     * Tampilkan form booking
     */
    public function create(Room $room)
    {
        // Cek apakah kamar tersedia
        if (!$room->isAvailable()) {
            return redirect()
                ->route('user.rooms.index')
                ->with('error', 'Maaf, kamar ini sudah tidak tersedia.');
        }

        // Load relasi kosInfo
        $room->load('kosInfo');

        // Hitung DP (50% dari harga sewa)
        $depositAmount = $room->price * 0.5;

        return view('user.booking.create', compact('room', 'depositAmount'));
    }

    /**
     * Proses booking
     */
    public function store(Request $request, Room $room)
    {
        // Validasi
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'deposit_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'agreement' => 'required|accepted',
        ], [
            'start_date.required' => 'Tanggal mulai sewa wajib diisi',
            'start_date.after_or_equal' => 'Tanggal mulai sewa tidak boleh di masa lalu',
            'deposit_proof.required' => 'Bukti transfer DP wajib diunggah',
            'deposit_proof.image' => 'File harus berupa gambar',
            'deposit_proof.max' => 'Ukuran file maksimal 2MB',
            'agreement.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        // Cek double booking
        if (!$room->isAvailable()) {
            return back()
                ->with('error', 'Maaf, kamar sudah disewa orang lain.')
                ->withInput();
        }

        // Cek apakah user sudah punya booking pending/active
        $existingRent = Rent::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($existingRent) {
            return back()
                ->with('error', 'Anda sudah memiliki booking aktif. Selesaikan booking tersebut terlebih dahulu.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Upload bukti transfer
            $depositProofPath = $request->file('deposit_proof')
                ->store('deposits', 'public');

            // Hitung DP
            $depositAmount = $room->price * 0.5;

            // Buat data rent (status: pending menunggu approval admin)
            $rent = Rent::create([
                'user_id' => Auth::id(),
                'room_id' => $room->id,
                'start_date' => $validated['start_date'],
                'end_date' => null,
                'deposit_paid' => $depositAmount,
                'monthly_rent' => $room->price,
                'status' => 'pending',
                'notes' => 'Bukti DP: ' . $depositProofPath,
            ]);

            DB::commit();

            // Redirect ke halaman success
            return redirect()
                ->route('user.dashboard')
                ->with('success', 'Booking berhasil! Menunggu konfirmasi admin. Anda akan dihubungi dalam 1x24 jam.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file yang sudah diupload jika ada error
            if (isset($depositProofPath)) {
                Storage::disk('public')->delete($depositProofPath);
            }

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses booking. Silakan coba lagi.');
        }
    }

    /**
     * Lihat status booking
     */
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

    /**
     * Tampilkan detail booking untuk user
     */
    public function show(Rent $booking)
    {
        // Pastikan booking ini milik user yang sedang login
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke booking ini.');
        }

        // Load relasi room
        $booking->load('room');

        return view('user.booking.show', compact('booking'));
    }
}
