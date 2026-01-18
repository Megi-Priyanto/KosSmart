<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingManagementController extends Controller
{
    /**
     * Display bookings
     * 
     * Global Scope otomatis filter berdasarkan tempat_kos_id
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Query builder
        if ($user->isSuperAdmin()) {
            // Super Admin: Lihat semua booking
            $query = Rent::withoutTempatKosScope()->with(['user', 'room']);
        } else {
            // Admin: Hanya booking di kos miliknya (auto-filtered)
            $query = Rent::with(['user', 'room']);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'active']);
        }

        $bookings = $query->latest()->paginate(15);

        // Count pending (otomatis filtered)
        $pendingCount = $user->isSuperAdmin() 
            ? Rent::withoutTempatKosScope()->where('status', 'pending')->count()
            : Rent::where('status', 'pending')->count();

        return view('admin.bookings.index', compact('bookings', 'pendingCount'));
    }

    /**
     * Show booking detail
     * 
     * Model binding sudah ter-filter otomatis
     */
    public function show(Rent $booking)
    {
        // Model binding otomatis cek ownership via Global Scope
        $booking->load(['user', 'room.kosInfo', 'dpVerifier']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Approve booking
     * 
     * Model binding otomatis validasi ownership
     */
    public function approve(Request $request, Rent $booking)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        // Validasi metode pembayaran
        if (!$booking->payment_method || !$booking->payment_sub_method) {
            return back()->with('error', 'Metode pembayaran tidak valid.');
        }

        // Validasi bukti pembayaran
        if (!$booking->notes || !str_contains($booking->notes, 'Bukti DP:')) {
            return back()->with('error', 'Bukti pembayaran tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            $year = now()->year;
            $month = now()->month;
            $period = now()->format('Y-m');

            // Update booking & payment status
            $booking->update([
                'status' => 'active',
                'admin_notes' => $request->admin_notes,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'dp_paid' => true,
                'dp_payment_status' => 'approved',
                'dp_verified_at' => now(),
                'dp_verified_by' => Auth::id(),
            ]);

            // Update room status
            $booking->room->update(['status' => 'occupied']);

            // Hitung DP & Pelunasan
            $harga = $booking->room->price;
            $dp = $harga / 2;
            $sisaPembayaran = $harga - $dp;

            // Buat Billing DP (sudah dibayar)
            // tempat_kos_id otomatis terisi via trait
            \App\Models\Billing::firstOrCreate(
                [
                    'rent_id' => $booking->id,
                    'billing_year' => $year,
                    'billing_month' => $month,
                    'tipe' => 'dp',
                ],
                [
                    'user_id' => $booking->user_id,
                    'room_id' => $booking->room_id,
                    'jumlah' => $dp,
                    'status' => 'paid',
                    'keterangan' => 'DP 50% sewa kamar via ' . $booking->payment_method_label . ' (' . $booking->payment_sub_method_label . ')',
                    'billing_period' => $period,
                    'rent_amount' => $dp,
                    'subtotal' => $dp,
                    'discount' => 0,
                    'total_amount' => $dp,
                    'due_date' => now(),
                    'paid_date' => now(),
                ]
            );

            // Buat Billing Pelunasan (belum dibayar)
            \App\Models\Billing::firstOrCreate(
                [
                    'rent_id' => $booking->id,
                    'billing_year' => $year,
                    'billing_month' => $month,
                    'tipe' => 'pelunasan',
                ],
                [
                    'user_id' => $booking->user_id,
                    'room_id' => $booking->room_id,
                    'jumlah' => $sisaPembayaran,
                    'status' => 'unpaid',
                    'keterangan' => 'Pelunasan 50% sisa pembayaran sewa kamar',
                    'billing_period' => $period,
                    'rent_amount' => $sisaPembayaran,
                    'subtotal' => $sisaPembayaran,
                    'discount' => 0,
                    'total_amount' => $sisaPembayaran,
                    'due_date' => now()->addDays(5),
                ]
            );

            // Buat notifikasi
            app(\App\Http\Controllers\Admin\NotificationController::class)
                ->createDpNotification($booking);

            DB::commit();

            return redirect()
                ->route('admin.bookings.index')
                ->with('success', "Pembayaran disetujui! Booking kamar {$booking->room->room_number} aktif.");
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

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Update payment rejection
            $booking->update([
                'dp_payment_status' => 'rejected',
                'dp_rejection_reason' => $request->admin_notes,
                'dp_verified_at' => now(),
                'dp_verified_by' => Auth::id(),
                'status' => 'cancelled',
                'admin_notes' => $request->admin_notes,
                'end_date' => now(),
                'approved_by' => Auth::id(),
            ]);

            // Kembalikan room status
            $booking->room->update(['status' => 'available']);

            DB::commit();

            return redirect()
                ->route('admin.bookings.index')
                ->with('success', "Pembayaran ditolak. Booking dibatalkan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Get pending count
     */
    public function getPendingCount()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        return $user->isSuperAdmin()
            ? Rent::withoutTempatKosScope()->where('status', 'pending')->count()
            : Rent::where('status', 'pending')->count();
    }
}