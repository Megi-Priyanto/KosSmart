<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingManagementController extends Controller
{
    /**
     * Tampilkan daftar booking (pending & active)
     */
    public function index(Request $request)
    {
        $query = Rent::with(['user', 'room']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan pending dan active
            $query->whereIn('status', ['pending', 'active']);
        }

        // Sort by latest
        $bookings = $query->latest()->paginate(15);

        // Count pending bookings
        $pendingCount = Rent::where('status', 'pending')->count();

        return view('admin.bookings.index', compact('bookings', 'pendingCount'));
    }

    /**
     * Tampilkan detail booking
     */
    public function show(Rent $booking)
    {
        $booking->load(['user', 'room.kosInfo']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Tampilkan detail booking
     */
    public function approve(Request $request, Rent $booking)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {

            /**
             * Ubah status booking menjadi active
             *    DP dianggap sudah dibayar
             */
            $booking->update([
                'status' => 'active',
                'admin_notes' => $request->admin_notes,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'dp_paid' => true,
            ]);

            /**
             * Update status room
             */
            $booking->room->update(['status' => 'occupied']);

            /**
             * Hitung DP & Pelunasan
             */
            $harga = $booking->room->price;
            $dp = $harga / 2;
            $sisaPembayaran = $harga - $dp;

            /**
             * Buat Billing DP (dp = dianggap sudah dibayar)
             */
            $billingDp = \App\Models\Billing::create([
                'rent_id'        => $booking->id,
                'user_id'        => $booking->user_id,
                'room_id'        => $booking->room_id,
                'tipe'           => 'dp',
                'jumlah'         => $dp,
                'status'         => 'paid',
                'keterangan'     => 'DP 50% sewa kamar',
                'billing_period' => now()->format('Y-m'),
                'billing_year'   => now()->year,
                'billing_month'  => now()->month,

                // KOMPONEN BIAYA
                'rent_amount'    => $dp,       // DP = 50%
                'subtotal'       => $dp,
                'discount'       => 0,
                'total_amount'   => $dp,
                'due_date'       => now(),
            ]);

            /**
             * Buat Billing Pelunasan (belum dibayar)
             */
            $billingPelunasan = \App\Models\Billing::create([
                'rent_id'        => $booking->id,
                'user_id'        => $booking->user_id,
                'room_id'        => $booking->room_id,
                'tipe'           => 'pelunasan',
                'jumlah'         => $sisaPembayaran,
                'status'         => 'unpaid',
                'keterangan'     => 'Pelunasan 50% sisa pembayaran sewa kamar',
                'billing_period' => now()->format('Y-m'),
                'billing_year'   => now()->year,
                'billing_month'  => now()->month,

                // KOMPONEN BIAYA
                'rent_amount'    => $sisaPembayaran, // 50%
                'subtotal'       => $sisaPembayaran,
                'discount'       => 0,
                'total_amount'   => $sisaPembayaran,
                'due_date'       => now()->addDays(5),
            ]);

            /**
             * 6) Buat NotificationItem untuk pelunasan
             */
            app(\App\Http\Controllers\Admin\NotificationController::class)
                ->createDpNotification($booking);

            DB::commit();

            return redirect()
                ->route('admin.bookings.index')
                ->with('success', "Booking {$booking->room->room_number} berhasil disetujui!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui booking: ' . $e->getMessage());
        }
    }

    /**
     * Reject booking
     */
    public function reject(Request $request, Rent $booking)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ], [
            'admin_notes.required' => 'Alasan penolakan wajib diisi',
        ]);

        // Validasi: hanya booking dengan status pending yang bisa di-reject
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Update booking status ke cancelled
            $booking->update([
                'status' => 'cancelled',
                'admin_notes' => $request->admin_notes,
                'end_date' => now(),
                'approved_by' => Auth::id(),
            ]);

            // Kembalikan room status ke available
            $booking->room->update(['status' => 'available']);

            DB::commit();

            // TODO: Kirim notifikasi email/WA ke user (opsional)

            return redirect()
                ->route('admin.bookings.index')
                ->with('success', "Booking kamar {$booking->room->room_number} oleh {$booking->user->name} berhasil ditolak.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menolak booking: ' . $e->getMessage());
        }
    }

    /**
     * Get pending bookings count (for notification badge)
     */
    public function getPendingCount()
    {
        return Rent::where('status', 'pending')->count();
    }
}
