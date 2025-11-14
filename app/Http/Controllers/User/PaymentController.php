<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        // Ambil data pembayaran user yang sedang login
        $payments = Payment::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.payments.index', compact('payments'));
    }
}
