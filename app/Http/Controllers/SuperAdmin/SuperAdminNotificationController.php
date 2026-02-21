<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminNotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi pembayaran untuk superadmin.
     * 
     * Notifikasi ini muncul ketika admin kos mengkonfirmasi pembayaran
     * user (pelunasan / tagihan bulanan / tahunan).
     * Dana otomatis masuk ke holding → superadmin perlu cairkan via disbursement.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->where('type', 'payment')
            ->with([
                'payment.billing.user',
                'payment.billing.room.kosInfo',
                'payment.disbursement',
            ])
            ->latest()
            ->paginate(20);

        $unreadCount = Notification::where('user_id', auth()->id())
            ->where('type', 'payment')
            ->where('status', 'unread')
            ->count();

        return view('superadmin.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('type', 'payment')
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    /**
     * Tandai satu notifikasi sebagai dibaca dan redirect ke disbursement.
     */
    public function markReadAndRedirect(Notification $notification)
    {
        // Pastikan notifikasi milik superadmin yang login
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        // Arahkan ke halaman disbursement untuk kos yang bersangkutan
        if ($notification->payment && $notification->payment->tempat_kos_id) {
            return redirect()->route('superadmin.disbursements.index', [
                'tempat_kos_id' => $notification->payment->tempat_kos_id,
            ]);
        }

        return redirect()->route('superadmin.disbursements.index');
    }
}