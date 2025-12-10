<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationItem;
use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('status', 'pending')
            ->orderBy('notification_date', 'desc')
            ->get();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function detail(Notification $notification)
    {
        $items = $notification->items()->where('status', 'pending')->get();

        return view('admin.notifications.detail', compact('notification', 'items'));
    }

    public function processItem(NotificationItem $item)
    {
        DB::transaction(function () use ($item) {

            // Cek apakah tagihan sudah ada
            $exists = Billing::where('rent_id', $item->rent_id)
                ->where('billing_month', now()->month)
                ->where('billing_year', now()->year)
                ->exists();

            if (!$exists) {
                Billing::create([
                    'rent_id' => $item->rent_id,
                    'user_id' => $item->user_id,
                    'room_id' => $item->room_id,
                    'billing_month' => now()->month,
                    'billing_year' => now()->year,
                    'billing_period' => now()->format('Y-m'),
                    'rent_amount' => $item->rent->monthly_rent,
                    'due_date' => now()->addDays(3),
                    'status' => 'unpaid',
                    'total_amount' => $item->rent->monthly_rent,
                ]);
            }

            // Update status item
            $item->update(['status' => 'processed']);

            // Jika semua item selesai → notifikasi selesai
            $remaining = NotificationItem::where('notification_id', $item->notification_id)
                ->where('status', 'pending')
                ->count();

            if ($remaining == 0) {
                $item->notification->update(['status' => 'processed']);
            }
        });

        return back()->with('success', 'Tagihan berhasil diproses');
    }

    public function createDpNotification($booking)
    {
        // Buat kepala notifikasi
        $notification = \App\Models\Notification::create([
            'title' => 'Tagihan Pelunasan',
            'description' => 'Anda memiliki tagihan pelunasan sisa pembayaran kamar.',
            'notification_date' => now(), // WAJIB! untuk fix error
            'status' => 'pending',
        ]);

        // Tambahkan item detail
        \App\Models\NotificationItem::create([
            'notification_id' => $notification->id,
            'rent_id' => $booking->id,
            'user_id' => $booking->user_id,
            'room_id' => $booking->room_id,
            'due_date' => now()->addDays(5),  // WAJIB! tidak boleh null
            'status' => 'pending',
        ]);

        return true;
    }
}
