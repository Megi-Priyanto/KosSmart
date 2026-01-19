<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class SuperAdminNotificationController extends Controller
{
    /**
     * Display notifications for super admin
     */
    public function index()
    {
        $notifications = Notification::whereIn('type', ['payment', 'billing'])
            ->with(['user', 'adminBilling']) // ✅ Ganti 'billing' → 'adminBilling'
            ->latest()
            ->paginate(20);

        $unreadCount = Notification::where('type', 'payment')
            ->where('status', 'unread')
            ->count();

        return view('superadmin.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead()
    {
        Notification::where('type', 'payment')
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca');
    }
}
