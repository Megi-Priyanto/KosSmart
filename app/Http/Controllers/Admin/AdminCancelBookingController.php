<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancelBooking;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminCancelBookingController extends Controller
{
    /**
     * Display all cancel requests (untuk admin kos)
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $query = CancelBooking::withoutTempatKosScope()->with(['rent.room', 'user']);
        } else {
            $query = CancelBooking::with(['rent.room', 'user']);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'admin_approved', 'approved']);
        }

        // Filter search — nama user, email, atau nomor kamar
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                )->orWhereHas('rent.room', fn($r) =>
                    $r->where('room_number', 'like', "%{$search}%")
                );
            });
        }

        // withQueryString() agar parameter search/status ikut terbawa saat pindah halaman
        $cancelBookings = $query->latest()->paginate(15)->withQueryString();

        $pendingCount = $user->isSuperAdmin()
            ? CancelBooking::withoutTempatKosScope()->where('status', 'pending')->count()
            : CancelBooking::where('status', 'pending')->count();

        return view('admin.cancel-bookings.index', compact('cancelBookings', 'pendingCount'));
    }

    /**
     * Show detail + form approve untuk admin
     */
    public function show(CancelBooking $cancelBooking)
    {
        $cancelBooking->load(['rent.room', 'user']);
        return view('admin.cancel-bookings.show', compact('cancelBooking'));
    }

    /**
     * Admin MENYETUJUI cancel booking → forward ke superadmin untuk proses refund
     *
     * - Update status rent  → 'cancelled'
     * - Update status room  → 'available'
     */
    public function approve(Request $request, CancelBooking $cancelBooking)
    {
        if ($cancelBooking->status !== 'pending') {
            return back()->with('error', 'Pembatalan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'admin_approval_notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // 1. Update status cancel booking → admin_approved
            $cancelBooking->update([
                'status'               => 'admin_approved',
                'admin_approval_notes' => $validated['admin_approval_notes'],
                'approved_by_admin'    => Auth::id(),
                'admin_approved_at'    => now(),
            ]);

            // 2. Update status rent → cancelled & room → available
            $rent = $cancelBooking->rent;
            if ($rent) {
                $rent->update([
                    'status'   => 'cancelled',
                    'end_date' => now(),
                ]);

                if ($rent->room) {
                    $rent->room->update(['status' => 'available']);
                }
            }

            // 3. Kirim notifikasi ke SEMUA superadmin
            $superAdmins = User::where('role', 'superadmin')->get();
            foreach ($superAdmins as $superAdmin) {
                Notification::create([
                    'tempat_kos_id' => $cancelBooking->tempat_kos_id,
                    'user_id'       => $superAdmin->id,
                    'rent_id'       => $cancelBooking->rent_id,
                    'room_id'       => $cancelBooking->rent->room_id ?? null,
                    'type'          => 'cancel_refund',
                    'title'         => 'Permintaan Refund Cancel Booking',
                    'message'       => 'Admin ' . Auth::user()->name .
                        ' telah menyetujui pembatalan booking user ' .
                        $cancelBooking->user->name .
                        '. Silakan proses pengembalian dana DP sebesar Rp ' .
                        number_format($cancelBooking->rent->deposit_paid ?? 0, 0, ',', '.'),
                    'status'        => 'unread',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.cancel-bookings.index')
                ->with('success', 'Pembatalan booking disetujui. Status booking diubah ke Cancelled. Notifikasi refund telah dikirim ke Superadmin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui pembatalan: ' . $e->getMessage());
        }
    }
}