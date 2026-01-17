<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rent;
use App\Models\Billing;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    public function index()
    {
        return view('user.status.index');
    }

    // =========================
    // STATUS CHECKOUT (PENDING)
    // =========================
    public function checkout(Request $request)
    {
        $userId = auth()->id();

        // SEMUA RIWAYAT CHECKOUT USER
        $checkouts = Rent::where('user_id', $userId)
            ->whereIn('status', [
                'checkout_requested',
                'checkout_approved',
                'checkout_rejected'
            ])
            ->with('room')
            ->latest()
            ->get()
            ->groupBy(fn($rent) => $rent->updated_at->format('F Y'));

        // DETAIL CHECKOUT
        $selectedCheckout = null;
        if ($request->filled('rent')) {
            $selectedCheckout = Rent::where('id', $request->rent)
                ->where('user_id', $userId)
                ->with('room')
                ->first();
        }

        return view('user.status.checkout', [
            'checkoutGroups'   => $checkouts,
            'selectedCheckout' => $selectedCheckout,
        ]);
    }

    public function booking(Request $request)
    {
        $userId = auth()->id();

        // booking pending (jika ada)
        $currentBooking = Rent::where('user_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->first();

        // history booking
        $historyBookings = Rent::withoutGlobalScope('active_only')
            ->where('user_id', $userId)
            ->whereIn('status', [
                'active',
                'completed',
                'expired',
                'cancelled',
            ])
            ->latest()
            ->get()
            ->groupBy(fn($rent) => $rent->created_at->format('F Y'));


        // DETAIL BOOKING (dari klik "Lihat Detail")
        $selectedBooking = null;

        if ($request->filled('rent')) {
            $selectedBooking = Rent::where('id', $request->rent)
                ->where('user_id', $userId)
                ->first();
        }

        return view('user.status.booking', compact(
            'currentBooking',
            'historyBookings',
            'selectedBooking'
        ));
    }

    public function billing()
    {
        $userId = auth()->id();

        $billings = Billing::with(['room', 'latestPayment'])
            ->where('user_id', $userId)
            ->orderBy('billing_year', 'desc')
            ->orderBy('billing_month', 'desc')
            ->get()
            ->groupBy(
                fn($billing) =>
                \Carbon\Carbon::create(
                    $billing->billing_year,
                    $billing->billing_month,
                    1
                )->format('F Y')
            );

        return view('user.status.billing', compact('billings'));
    }
}
