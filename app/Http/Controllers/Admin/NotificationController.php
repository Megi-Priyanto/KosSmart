<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        // ➤ Tambahkan ini agar pending berubah jadi read
        \App\Models\Notification::where('status', 'pending')
            ->update(['status' => 'read']);

        // Ambil notifikasi yang masih pending atau read
        $notifications = Notification::orderBy('created_at', 'desc')
            ->get();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function detail(Notification $notification)
    {
        return view('admin.notifications.detail', compact('notification'));
    }

    public function process(Notification $notification)
    {
        DB::transaction(function () use ($notification) {

            // Cek apakah tagihan sudah ada untuk bulan ini
            $exists = Billing::where('rent_id', $notification->rent_id)
                ->where('billing_month', now()->month)
                ->where('billing_year', now()->year)
                ->exists();

            if (!$exists) {
                Billing::create([
                    'rent_id' => $notification->rent_id,
                    'user_id' => $notification->user_id,
                    'room_id' => $notification->room_id,
                    'billing_month' => now()->month,
                    'billing_year' => now()->year,
                    'billing_period' => now()->format('Y-m'),
                    'rent_amount' => $notification->rent->monthly_rent,
                    'due_date' => now()->addDays(3),
                    'status' => 'unpaid',
                    'total_amount' => $notification->rent->monthly_rent,
                ]);
            }

            // Update status notifikasi
            $notification->update(['status' => 'processed']);
        });

        return back()->with('success', 'Tagihan berhasil diproses');
    }

    public function createDpNotification($booking)
    {
        Notification::create([
            'title' => 'Tagihan Pelunasan',
            'notification_date' => now(),
            'status' => 'pending',

            // kolom baru pada single-table
            'rent_id' => $booking->id,
            'user_id' => $booking->user_id,
            'room_id' => $booking->room_id,
            'due_date' => now()->addDays(5),
        ]);

        return true;
    }
}
