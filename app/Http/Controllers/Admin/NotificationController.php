<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display notifications
     * 
     * Global Scope otomatis filter berdasarkan tempat_kos_id
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Update status notifications
        if ($user->isSuperAdmin()) {
            Notification::withoutTempatKosScope()
                ->where('status', 'pending')
                ->update(['status' => 'read']);
        } else {
            Notification::where('status', 'pending')
                ->update(['status' => 'read']);
        }

        // Ambil notifikasi (otomatis filtered)
        $notificationsQuery = $user->isSuperAdmin()
            ? Notification::withoutTempatKosScope()
            : Notification::query();

        $notifications = $notificationsQuery->orderBy('created_at', 'desc')->get();

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Show notification detail
     * 
     * Model binding otomatis ter-filter
     */
    public function detail(Notification $notification)
    {
        return view('admin.notifications.detail', compact('notification'));
    }

    /**
     * Process notification
     * 
     * Model binding otomatis ter-filter
     */
    public function process(Notification $notification)
    {
        DB::transaction(function () use ($notification) {

            // Cek apakah tagihan sudah ada untuk bulan ini
            $exists = Billing::where('rent_id', $notification->rent_id)
                ->where('billing_month', now()->month)
                ->where('billing_year', now()->year)
                ->exists();

            if (!$exists) {
                // tempat_kos_id otomatis terisi via trait
                Billing::create([
                    'rent_id' => $notification->rent_id,
                    'user_id' => $notification->user_id,
                    'room_id' => $notification->room_id,
                    'billing_month' => now()->month,
                    'billing_year' => now()->year,
                    'billing_period' => now()->format('Y-m'),
                    'rent_amount' => $notification->rent->monthly_rent,
                    'subtotal' => $notification->rent->monthly_rent,
                    'total_amount' => $notification->rent->monthly_rent,
                    'due_date' => now()->addDays(3),
                    'status' => 'unpaid',
                    'tipe' => 'bulanan',
                ]);
            }

            // Update status notifikasi
            $notification->update(['status' => 'processed']);
        });

        return back()->with('success', 'Tagihan berhasil diproses');
    }

    /**
     * Create DP notification
     * 
     * tempat_kos_id otomatis terisi via trait
     */
    public function createDpNotification($booking)
    {
        Notification::create([
            'type' => 'billing',
            'title' => 'Tagihan Pelunasan',
            'message' => 'Silakan lakukan pelunasan pembayaran DP Anda.',
            'user_id' => $booking->user_id,
            'rent_id' => $booking->id,
            'room_id' => $booking->room_id,
            'due_date' => now()->addDays(5),
            'status' => 'pending',
        ]);

        return true;
    }
}