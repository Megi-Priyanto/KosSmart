@extends('layouts.user')

@section('title', 'Detail Tagihan')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Detail Pembayaran</h1>
            <p class="text-sm text-slate-500 mt-1">Riwayat transaksi pembayaran Anda</p>
        </div>
        @if(request('from') === 'status')
            <a href="{{ route('user.status.billing') }}"
               class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg">
                Kembali ke History Tagihan
            </a>
        @else
            <a href="{{ route('user.billing.index') }}"
               class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg">
                Kembali ke Tagihan
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- MAIN CONTENT --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Billing Info Card --}}
            <div class="rounded-xl overflow-hidden shadow-xl" style="background:#1e293b; border:1px solid #334155;">

                {{-- Card Header --}}
                <div class="p-6" style="border-bottom:1px solid #334155; background:{{ $billing->status === 'paid' ? 'rgba(34,197,94,0.08)' : ($billing->is_overdue ? 'rgba(239,68,68,0.08)' : 'rgba(234,179,8,0.06)') }};">
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-xl font-bold text-white">{{ $billing->formatted_period }}</h2>
                        <span class="px-4 py-1.5 text-sm font-semibold rounded-full {{ $billing->status_badge }}">
                            {{ $billing->status_label }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-400">Tagihan untuk Kamar {{ $billing->room->room_number }}</p>
                </div>

                {{-- Rincian Biaya --}}
                <div class="p-6" style="border-bottom:1px solid #334155;">
                    <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wide mb-4">Rincian Biaya</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Biaya Sewa</span>
                            <span class="font-medium text-slate-200">Rp {{ number_format($billing->rent_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm pt-2" style="border-top:1px solid #334155;">
                            <span class="text-slate-400">Subtotal</span>
                            <span class="font-medium text-slate-200">Rp {{ number_format($billing->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($billing->discount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Diskon</span>
                            <span class="font-semibold text-red-400">- Rp {{ number_format($billing->discount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center pt-3 px-4 py-3 rounded-lg -mx-2" style="border-top:2px solid #334155; background:rgba(234,179,8,0.06);">
                            <span class="font-bold text-white">Total Tagihan</span>
                            <span class="text-2xl font-bold text-yellow-400">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Info Tanggal --}}
                <div class="p-6 grid grid-cols-2 gap-4" style="background:#0f172a; border-bottom:1px solid #334155;">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Tanggal Dibuat</p>
                        <p class="text-sm font-medium text-slate-200">{{ $billing->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Jatuh Tempo</p>
                        <p class="text-sm font-medium text-slate-200">{{ $billing->due_date->format('d M Y') }}</p>
                        @if($billing->is_overdue && $billing->status !== 'paid')
                            <p class="text-xs text-red-400 mt-1 font-semibold">Terlambat {{ abs($billing->days_until_due) }} hari</p>
                        @elseif($billing->status !== 'paid')
                            <p class="text-xs text-slate-500 mt-1">{{ $billing->days_until_due }} hari lagi</p>
                        @endif
                    </div>
                    @if($billing->paid_date)
                    <div class="col-span-2">
                        <p class="text-xs text-slate-500 mb-1">Tanggal Lunas</p>
                        <p class="text-sm font-medium text-green-400">{{ $billing->paid_date->format('d M Y, H:i') }}</p>
                    </div>
                    @endif
                </div>

                @if($billing->admin_notes)
                <div class="p-5" style="border-top:1px solid #334155;">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Catatan Admin</p>
                    <p class="text-sm text-slate-300">{{ $billing->admin_notes }}</p>
                </div>
                @endif
            </div>

            {{-- Riwayat Pembayaran --}}
            <div class="rounded-xl overflow-hidden shadow-xl" style="background:#1e293b; border:1px solid #334155;">
                <div class="p-5" style="border-bottom:1px solid #334155;">
                    <h3 class="font-semibold text-white">Riwayat Pembayaran</h3>
                </div>

                @forelse($billing->payments as $payment)
                <div class="p-5" style="border-bottom:1px solid #334155;">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            {{-- Badge + waktu --}}
                            <div class="flex items-center gap-2 mb-3">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $payment->status_badge }}">
                                    {{ $payment->status_label }}
                                </span>
                                <span class="text-xs text-slate-500">{{ $payment->created_at->format('d M Y, H:i') }}</span>
                            </div>

                            {{-- Detail Grid --}}
                            <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                                <div>
                                    <p class="text-xs text-slate-500 mb-0.5">Jumlah Dibayar</p>
                                    <p class="font-semibold text-slate-200">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 mb-0.5">Metode</p>
                                    <p class="font-medium text-slate-200">{{ $payment->payment_method_label }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 mb-0.5">Tanggal Pembayaran</p>
                                    <p class="font-medium text-slate-200">{{ $payment->payment_date->format('d M Y') }}</p>
                                </div>
                            </div>

                            {{-- Status messages --}}
                            @if($payment->status === 'pending')
                            <div class="p-3 rounded-lg" style="background:rgba(234,179,8,0.08); border-left:3px solid #eab308;">
                                <p class="text-sm text-yellow-300"><span class="font-semibold">⏳ Menunggu Verifikasi</span><br>Pembayaran Anda sedang diproses oleh admin.</p>
                            </div>
                            @elseif($payment->rejection_reason)
                            <div class="p-3 rounded-lg" style="background:rgba(239,68,68,0.08); border-left:3px solid #ef4444;">
                                <p class="text-xs font-semibold text-red-400 mb-1">PEMBAYARAN DITOLAK</p>
                                <p class="text-sm text-red-300">{{ $payment->rejection_reason }}</p>
                            </div>
                            @endif

                            @if($payment->notes)
                            <div class="mt-3 p-3 rounded-lg" style="background:#0f172a; border:1px solid #334155;">
                                <p class="text-xs text-slate-500 mb-1">Catatan:</p>
                                <p class="text-sm text-slate-300">{{ $payment->notes }}</p>
                            </div>
                            @endif
                        </div>

                        {{-- Proof thumbnail --}}
                        @if($payment->payment_proof)
                        <div class="flex-shrink-0">
                            <a href="{{ Storage::url($payment->payment_proof) }}" target="_blank"
                               class="block w-28 h-28 rounded-lg overflow-hidden transition-all"
                               style="border:2px solid #334155;"
                               onmouseover="this.style.borderColor='#f59e0b'"
                               onmouseout="this.style.borderColor='#334155'">
                                <img src="{{ Storage::url($payment->payment_proof) }}" alt="Bukti" class="w-full h-full object-cover">
                            </a>
                            <p class="text-xs text-slate-600 text-center mt-1">Klik untuk besar</p>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-10 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-slate-500 text-sm">Belum ada pembayaran</p>
                </div>
                @endforelse
            </div>

        </div>

        {{-- SIDEBAR --}}
        <div class="space-y-5">

            {{-- Status Action Box --}}
            @if($billing->status === 'paid')
            <div class="p-6 rounded-xl text-center" style="background:rgba(34,197,94,0.08); border:2px solid rgba(34,197,94,0.4);">
                <svg class="mx-auto h-12 w-12 text-green-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-base font-bold text-green-300 mb-1">Tagihan Lunas</h3>
                <p class="text-sm text-green-500">Terima kasih atas pembayaran Anda</p>
            </div>

            @elseif($billing->status === 'pending')
            <div class="p-4 rounded-xl" style="background:rgba(234,179,8,0.08); border:1px solid rgba(234,179,8,0.3);">
                <p class="text-sm text-yellow-300">Pembayaran Anda sedang <strong>menunggu verifikasi admin</strong>. Mohon menunggu, status akan diperbarui setelah diverifikasi.</p>
            </div>

            @else
            <div class="p-5 rounded-xl" style="background:#1e293b; border:1px solid #334155;">
                <h3 class="font-semibold text-white mb-1">Bayar Tagihan</h3>
                <p class="text-sm text-slate-400 mb-4">Silakan lakukan pembayaran untuk melanjutkan proses sewa.</p>
                <a href="{{ route('user.billing.pay', $billing) }}"
                   class="block w-full py-2.5 text-center text-white font-semibold rounded-lg bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg">
                    Bayar Sekarang
                </a>
            </div>
            @endif

            {{-- Info Box --}}
            <div class="p-5 rounded-xl" style="background:#1e293b; border:1px solid #334155;">
                <h4 class="text-sm font-semibold text-slate-300 mb-3">Informasi Pembayaran</h4>
                <ul class="space-y-2.5 text-sm text-slate-400">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Upload bukti transfer yang jelas
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Pastikan nominal sesuai tagihan
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Verifikasi dalam 1×24 jam
                    </li>
                </ul>
            </div>

        </div>
    </div>

</div>
@endsection