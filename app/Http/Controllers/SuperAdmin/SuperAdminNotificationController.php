<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminNotificationController extends Controller
{
    /**
     * Display notifications for super admin
     */
    public function index()
    {
        $superAdminIds = User::where('role', 'super_admin')->pluck('id')->toArray();

        // Filter hanya notifikasi dengan billing status pending
        $notifications = Notification::where('type', 'payment')
            ->whereIn('user_id', $superAdminIds)
            ->where('title', 'Pembayaran Tagihan Baru')
            ->whereNotNull('admin_billing_id')
            ->whereHas('adminBilling', function($q) {
                $q->where('status', 'pending');
            })
            ->with(['adminBilling.tempatKos', 'adminBilling.admin'])
            ->latest()
            ->paginate(20);

        $unreadCount = Notification::where('type', 'payment')
            ->where('status', 'unread')
            ->whereIn('user_id', $superAdminIds)
            ->where('title', 'Pembayaran Tagihan Baru')
            ->whereNotNull('admin_billing_id')
            ->whereHas('adminBilling', function($q) {
                $q->where('status', 'pending');
            })
            ->count();

        return view('superadmin.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead()
    {
        $superAdminIds = User::where('role', 'super_admin')->pluck('id')->toArray();

        Notification::where('type', 'payment')
            ->where('status', 'unread')
            ->whereIn('user_id', $superAdminIds)
            ->where('title', 'Pembayaran Tagihan Baru')
            ->whereNotNull('admin_billing_id')
            ->whereHas('adminBilling', function($q) {
                $q->where('status', 'pending');
            })
            ->update(['status' => 'read']);

        return back()->with('success', 'Semua notifikasi pembayaran telah ditandai dibaca');
    }
}