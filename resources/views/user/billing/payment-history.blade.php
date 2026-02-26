@extends('layouts.user')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Riwayat Pembayaran</h1>
            <p class="text-sm text-slate-500 mt-1">Semua riwayat transaksi pembayaran Anda</p>
        </div>
        <a href="{{ route('user.billing.index') }}"
           class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg">
            Kembali ke Tagihan
        </a>
    </div>

    {{-- PAYMENT LIST --}}
    <div class="rounded-xl overflow-hidden shadow-xl" style="background:#1e293b; border:1px solid #334155;">

        @forelse($payments as $payment)
        <div class="p-6 transition-colors hover:bg-slate-800/50"
             style="border-left:3px solid transparent; border-bottom:1px solid #334155;"
             onmouseover="this.style.borderLeftColor='#f59e0b'"
             onmouseout="this.style.borderLeftColor='transparent'">

            <div class="flex items-start justify-between gap-6">
                <div class="flex-1 min-w-0">

                    {{-- Badge + Tanggal --}}
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $payment->status_badge }}">
                            {{ $payment->status_label }}
                        </span>
                        <span class="text-sm text-slate-500">
                            {{ $payment->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    {{-- Periode & Info Kamar --}}
                    <div class="mb-4">
                        <h3 class="text-base font-bold text-white mb-1">
                            {{ $payment->billing->formatted_period }}
                        </h3>
                        <p class="text-sm text-slate-400">
                            Kamar {{ $payment->billing->room->room_number }} •
                            Tagihan: Rp {{ number_format($payment->billing->total_amount, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Detail Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Jumlah Dibayar</p>
                            <p class="text-sm font-semibold text-slate-200">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Metode</p>
                            <p class="text-sm font-medium text-slate-200">
                                {{ $payment->payment_method_label }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Tanggal Bayar</p>
                            <p class="text-sm font-medium text-slate-200">
                                {{ $payment->payment_date->format('d M Y') }}
                            </p>
                        </div>
                        @if($payment->verified_at)
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Diverifikasi</p>
                            <p class="text-sm font-medium text-slate-200">
                                {{ $payment->verified_at->format('d M Y') }}
                            </p>
                        </div>
                        @endif
                    </div>

                    {{-- Status Message --}}
                    @if($payment->status === 'pending')
                    <div class="p-3 rounded-lg mb-3" style="background:rgba(234,179,8,0.08); border-left:3px solid #eab308;">
                        <p class="text-sm text-yellow-300">
                            <span class="font-semibold">⏳ Menunggu Verifikasi</span><br>
                            Pembayaran Anda sedang diproses oleh admin.
                        </p>
                    </div>
                    @elseif($payment->status === 'confirmed')
                    <div class="p-3 rounded-lg mb-3" style="background:rgba(34,197,94,0.08); border-left:3px solid #22c55e;">
                        <p class="text-sm text-green-400">
                            <span class="font-semibold">✓ Pembayaran Dikonfirmasi</span><br>
                            Terima kasih, pembayaran Anda telah diverifikasi.
                        </p>
                    </div>
                    @elseif($payment->status === 'rejected')
                    <div class="p-3 rounded-lg mb-3" style="background:rgba(239,68,68,0.08); border-left:3px solid #ef4444;">
                        <p class="text-sm text-red-400">
                            <span class="font-semibold">✗ Pembayaran Ditolak</span><br>
                            {{ $payment->rejection_reason }}
                        </p>
                    </div>
                    @endif

                    {{-- Catatan --}}
                    @if($payment->notes)
                    <div class="p-3 rounded-lg mb-4" style="background:#0f172a; border:1px solid #334155;">
                        <p class="text-xs text-slate-500 mb-1">Catatan:</p>
                        <p class="text-sm text-slate-300">{{ $payment->notes }}</p>
                    </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('user.billing.show', $payment->billing) }}"
                           class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white text-sm font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg">
                            Lihat Detail Tagihan
                        </a>
                        @if($payment->payment_proof)
                        <a href="{{ Storage::url($payment->payment_proof) }}"
                           target="_blank"
                           class="px-4 py-2 text-sm font-medium text-slate-300 rounded-lg transition-colors"
                           style="background:#0f172a; border:1px solid #334155;"
                           onmouseover="this.style.background='#1e293b'; this.style.borderColor='#475569';"
                           onmouseout="this.style.background='#0f172a'; this.style.borderColor='#334155';">
                            Lihat Bukti
                        </a>
                        @endif
                    </div>

                </div>

                {{-- Proof Thumbnail --}}
                @if($payment->payment_proof)
                <div class="flex-shrink-0">
                    <a href="{{ Storage::url($payment->payment_proof) }}"
                       target="_blank"
                       class="block w-28 h-28 rounded-lg overflow-hidden transition-all"
                       style="border:2px solid #334155;"
                       onmouseover="this.style.borderColor='#f59e0b'"
                       onmouseout="this.style.borderColor='#334155'">
                        <img src="{{ Storage::url($payment->payment_proof) }}"
                             alt="Bukti Pembayaran"
                             class="w-full h-full object-cover">
                    </a>
                </div>
                @endif
            </div>
        </div>

        @empty
        <div class="p-16 text-center">
            <svg class="mx-auto h-14 w-14 text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-base font-semibold text-slate-400 mb-1">Belum Ada Riwayat</h3>
            <p class="text-sm text-slate-600 mb-4">Anda belum memiliki riwayat pembayaran</p>
            <a href="{{ route('user.billing.index') }}"
               class="inline-block px-5 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white text-sm font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all">
                Lihat Tagihan
            </a>
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($payments->hasPages())
        <div class="p-5" style="border-top:1px solid #334155; background:#0f172a;">
            {{ $payments->links() }}
        </div>
        @endif

    </div>

</div>
@endsection