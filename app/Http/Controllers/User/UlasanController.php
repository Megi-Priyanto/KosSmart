<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    /**
     * Tampilkan form ulasan setelah pelunasan dikonfirmasi.
     * Dipanggil dari notifikasi atau billing show.
     */
    public function create(Billing $billing)
    {
        $user = auth()->user();

        // Pastikan billing milik user ini
        if ($billing->user_id !== $user->id) {
            abort(403);
        }

        // Hanya bisa review jika billing sudah paid
        if ($billing->status !== 'paid') {
            return redirect()->route('user.billing.show', $billing)
                ->with('error', 'Ulasan hanya bisa diberikan setelah pembayaran dikonfirmasi.');
        }

        // Cek apakah billing ini adalah pelunasan (tipe pelunasan/bulanan)
        if (!in_array($billing->tipe, ['pelunasan', 'bulanan'])) {
            return redirect()->route('user.billing.show', $billing)
                ->with('error', 'Ulasan tidak tersedia untuk tipe tagihan ini.');
        }

        // Cek apakah sudah pernah review rent ini
        $sudahReview = Ulasan::where('user_id', $user->id)
            ->where('rent_id', $billing->rent_id)
            ->exists();

        if ($sudahReview) {
            return redirect()->route('user.billing.show', $billing)
                ->with('info', 'Anda sudah memberikan ulasan untuk kos ini.');
        }

        $billing->load(['room.kosInfo.tempatKos', 'rent']);

        return view('user.ulasan.create', compact('billing'));
    }

    /**
     * Simpan ulasan.
     */
    public function store(Request $request, Billing $billing)
    {
        $user = auth()->user();

        if ($billing->user_id !== $user->id) {
            abort(403);
        }

        if ($billing->status !== 'paid') {
            return back()->with('error', 'Ulasan hanya bisa diberikan setelah pembayaran dikonfirmasi.');
        }

        // Cek duplikat
        $sudahReview = Ulasan::where('user_id', $user->id)
            ->where('rent_id', $billing->rent_id)
            ->exists();

        if ($sudahReview) {
            return redirect()->route('user.dashboard')
                ->with('info', 'Anda sudah memberikan ulasan untuk kos ini.');
        }

        $validated = $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $billing->load('room.kosInfo.tempatKos');
        $tempatKosId = $billing->room?->kosInfo?->tempatKos?->id
            ?? $billing->rent?->room?->kosInfo?->tempatKos?->id;

        Ulasan::create([
            'user_id'       => $user->id,
            'tempat_kos_id' => $tempatKosId,
            'rent_id'       => $billing->rent_id,
            'billing_id'    => $billing->id,
            'rating'        => $validated['rating'],
            'komentar'      => $validated['komentar'],
            'is_visible'    => true,
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}
