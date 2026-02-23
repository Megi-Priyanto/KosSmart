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
     * Display bookings.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $query = Rent::withoutTempatKosScope()->with(['user', 'room', 'cancelBookings']);
        } else {
            $query = Rent::with(['user', 'room', 'cancelBookings']);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'active', 'cancelled']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"))
                    ->orWhereHas('room', fn($r) => $r->where('room_number', 'like', "%{$search}%"));
            });
        }

        $bookings = $query->latest()->paginate(15);

        $pendingCount = $user->isSuperAdmin()
            ? Rent::withoutTempatKosScope()->where('status', 'pending')->count()
            : Rent::where('status', 'pending')->count();

        return view('admin.bookings.index', compact('bookings', 'pendingCount'));
    }

    /**
     * Show booking detail.
     */
    public function show(Rent $booking)
    {
        $booking->load(['user', 'room.kosInfo', 'dpVerifier', 'cancelBookings']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Approve booking.
     */
    public function approve(Request $request, Rent $booking)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini sudah diproses sebelumnya.');
        }

        if (!$booking->payment_method || !$booking->payment_sub_method) {
            return back()->with('error', 'Metode pembayaran tidak valid.');
        }

        if (!$booking->notes || !str_contains($booking->notes, 'Bukti DP:')) {
            return back()->with('error', 'Bukti pembayaran tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            $year   = now()->year;
            $month  = now()->month;
            $period = now()->format('Y-m');

            $booking->update([
                'status'            => 'active',
                'admin_notes'       => $request->admin_notes,
                'approved_at'       => now(),
                'approved_by'       => Auth::id(),
                'dp_paid'           => true,
                'dp_payment_status' => 'approved',
                'dp_verified_at'    => now(),
                'dp_verified_by'    => Auth::id(),
            ]);

            $booking->room->update(['status' => 'occupied']);

            $harga          = $booking->room->price;
            $dp             = $harga / 2;
            $sisaPembayaran = $harga - $dp;

            $billingDp = \App\Models\Billing::firstOrCreate(
                [
                    'rent_id'       => $booking->id,
                    'billing_year'  => $year,
                    'billing_month' => $month,
                    'tipe'          => 'dp',
                ],
                [
                    'user_id'        => $booking->user_id,
                    'room_id'        => $booking->room_id,
                    'jumlah'         => $dp,
                    'status'         => 'paid',
                    'keterangan'     => 'DP 50% sewa kamar via ' . $booking->payment_method_label . ' (' . $booking->payment_sub_method_label . ')',
                    'billing_period' => $period,
                    'rent_amount'    => $dp,
                    'subtotal'       => $dp,
                    'discount'       => 0,
                    'total_amount'   => $dp,
                    'due_date'       => now(),
                    'paid_date'      => now(),
                ]
            );

            \App\Models\Payment::firstOrCreate(
                [
                    'billing_id' => $billingDp->id,
                    'status'     => 'confirmed',
                ],
                [
                    'tempat_kos_id'       => $booking->tempat_kos_id,
                    'user_id'             => $booking->user_id,
                    'amount'              => $dp,
                    'payment_method'      => $booking->payment_method,
                    'payment_type'        => $booking->payment_method,
                    'payment_sub_method'  => $booking->payment_sub_method,
                    'status'              => 'confirmed',
                    'payment_date'        => now(),
                    'notes'               => 'DP Booking - diverifikasi admin saat approve',
                    'verified_by'         => Auth::id(),
                    'verified_at'         => now(),
                    'disbursement_status' => 'holding',
                    'disbursement_id'     => null,
                ]
            );

            \App\Models\Billing::firstOrCreate(
                [
                    'rent_id'       => $booking->id,
                    'billing_year'  => $year,
                    'billing_month' => $month,
                    'tipe'          => 'pelunasan',
                ],
                [
                    'user_id'        => $booking->user_id,
                    'room_id'        => $booking->room_id,
                    'jumlah'         => $sisaPembayaran,
                    'status'         => 'unpaid',
                    'keterangan'     => 'Pelunasan 50% sisa pembayaran sewa kamar',
                    'billing_period' => $period,
                    'rent_amount'    => $sisaPembayaran,
                    'subtotal'       => $sisaPembayaran,
                    'discount'       => 0,
                    'total_amount'   => $sisaPembayaran,
                    'due_date'       => now()->addDays(5),
                ]
            );

            app(\App\Http\Controllers\Admin\NotificationController::class)
                ->createDpNotification($booking);

            DB::commit();

            return redirect()
                ->route('admin.bookings.index')
                ->with('success', "Booking kamar {$booking->room->room_number} disetujui! Dana DP tercatat di platform.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui booking: ' . $e->getMessage());
        }
    }

    /**
     * Reject booking.
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
            $booking->update([
                'dp_payment_status'   => 'rejected',
                'dp_rejection_reason' => $request->admin_notes,
                'dp_verified_at'      => now(),
                'dp_verified_by'      => Auth::id(),
                'status'              => 'cancelled',
                'admin_notes'         => $request->admin_notes,
                'end_date'            => now(),
                'approved_by'         => Auth::id(),
            ]);

            $booking->room->update(['status' => 'available']);

            DB::commit();

            return redirect()
                ->route('admin.bookings.index')
                ->with('success', 'Booking ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak booking: ' . $e->getMessage());
        }
    }

    /**
     * Get pending count.
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