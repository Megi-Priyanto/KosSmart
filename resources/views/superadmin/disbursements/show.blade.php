@extends('layouts.superadmin')

@section('title', 'Detail Pencairan #' . $disbursement->id)
@section('page-title', 'Detail Pencairan #' . $disbursement->id)
@section('page-description', 'Rincian lengkap transaksi pencairan dana ke admin')

@section('content')
<div class="space-y-6">

    {{-- Header: Status + Back --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm px-3 py-1.5 rounded-full font-medium {{ $disbursement->status_badge }}">
                {{ $disbursement->status_label }}
            </span>
            <span class="text-slate-400 text-sm">{{ $disbursement->created_at->format('d F Y, H:i') }} WIB</span>
        </div>
    </div>

    {{-- ============================================================
         BARIS 1: Stat Bar (sama seperti create)
    ============================================================ --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-400">Total Diterima Admin</p>
                <p class="text-base font-bold text-amber-400 truncate">Rp {{ number_format($disbursement->total_amount, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400">Jumlah Payment</p>
                <p class="text-base font-bold text-blue-400">{{ $disbursement->payment_count }} payment</p>
            </div>
        </div>
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-400">Tempat Kos</p>
                <p class="text-base font-bold text-purple-400 truncate">{{ $disbursement->tempatKos->nama_kos ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- ============================================================
         BARIS 2: Rincian Keuangan (kiri) + Informasi Umum & Transfer (kanan)
    ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

        {{-- KIRI: Rincian Keuangan --}}
        <div class="bg-slate-800 border border-amber-500/40 rounded-xl overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-slate-700 flex items-center gap-2.5 flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                <p class="text-sm font-semibold text-white">Potongan Platform</p>
            </div>

            <div class="p-6 flex flex-col gap-5 flex-1">
                {{-- Tampilan fee besar --}}
                <div>
                    <label class="block text-xs font-medium text-slate-400 mb-2 uppercase tracking-wide">Fee Platform</label>
                    <div class="relative">
                        <div class="w-full bg-slate-900 text-white text-4xl font-bold rounded-xl px-6 py-5 border border-slate-700 text-center">
                            {{ number_format($disbursement->fee_percent ?? 0, 0) }}
                            <span class="text-amber-400">%</span>
                        </div>
                    </div>
                </div>

                {{-- Kalkulasi --}}
                <div class="bg-slate-900/60 rounded-xl p-5 border border-slate-700/50 space-y-4 flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">Total Bruto</span>
                        <span class="text-sm font-semibold text-white">Rp {{ number_format($disbursement->gross_amount ?? $disbursement->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">Fee ({{ number_format($disbursement->fee_percent ?? 0, 0) }}%)</span>
                        <span class="text-sm font-semibold text-red-400">- Rp {{ number_format($disbursement->fee_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @php
                        $feePercent   = $disbursement->fee_percent ?? 0;
                        $adminPercent = 100 - $feePercent;
                    @endphp
                    <div>
                        <div class="flex rounded-full overflow-hidden h-3">
                            <div class="bg-emerald-500 transition-all duration-300" style="width: {{ $adminPercent }}%"></div>
                            <div class="bg-red-500/70 transition-all duration-300" style="width: {{ $feePercent }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs mt-1.5">
                            <span class="text-emerald-400">Admin {{ $adminPercent }}%</span>
                            <span class="text-red-400">Fee {{ $feePercent }}%</span>
                        </div>
                    </div>
                    <div class="border-t border-slate-700 pt-4 flex items-center justify-between">
                        <span class="text-base font-semibold text-slate-200">Diterima Admin</span>
                        <span class="text-2xl font-bold text-emerald-400">Rp {{ number_format($disbursement->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Ringkasan --}}
                <div class="bg-slate-900/40 rounded-xl p-4 border border-slate-700/50 space-y-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Ringkasan Pencairan</p>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-400">Payment dicairkan</span>
                        <span class="text-white font-medium">{{ $disbursement->payment_count }} payment</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-400">Fee Platform</span>
                        <span class="text-red-400">- Rp {{ number_format($disbursement->fee_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="h-px bg-slate-700"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-200">Diterima Admin</span>
                        <span class="text-lg font-bold text-emerald-400">Rp {{ number_format($disbursement->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: Informasi Umum + Detail Transfer (ditumpuk vertikal) --}}
        <div class="flex flex-col gap-6">

            {{-- Informasi Umum --}}
            <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-700 flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-semibold text-white">Informasi Umum</p>
                </div>
                <div class="p-5 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tempat Kos</span>
                        <span class="text-white font-medium">{{ $disbursement->tempatKos->nama_kos ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Admin Penerima</span>
                        <span class="text-white font-medium">{{ $disbursement->admin->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Jumlah Payment</span>
                        <span class="text-white">
                            <span class="px-2 py-0.5 text-xs bg-slate-700 text-slate-300 rounded-full">{{ $disbursement->payment_count }} payment</span>
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Diproses Oleh</span>
                        <span class="text-white">{{ $disbursement->processor->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Waktu Proses</span>
                        <span class="text-white text-xs">{{ $disbursement->processed_at?->format('d M Y H:i') ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Detail Transfer --}}
            <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden flex-1">
                <div class="px-5 py-4 border-b border-slate-700 flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <p class="text-sm font-semibold text-white">Detail Transfer</p>
                </div>
                <div class="p-5 space-y-3 text-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-900/50 rounded-lg p-3">
                            <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Metode</p>
                            <p class="text-white font-medium">{{ $disbursement->transfer_method ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-900/50 rounded-lg p-3">
                            <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">No. Rekening</p>
                            <p class="text-white font-mono text-xs">{{ $disbursement->transfer_account ?? '-' }}</p>
                        </div>
                    </div>

                    @if($disbursement->transfer_proof)
                    <div class="pt-2">
                        <p class="text-slate-400 text-xs uppercase tracking-wide mb-2">Bukti Transfer</p>
                        <a href="{{ Storage::url($disbursement->transfer_proof) }}" target="_blank"
                           class="block rounded-xl overflow-hidden border border-slate-700 hover:border-yellow-500 transition-colors">
                            <img src="{{ Storage::url($disbursement->transfer_proof) }}" alt="Bukti Transfer"
                                 class="w-full object-cover max-h-48">
                        </a>
                    </div>
                    @endif

                    @if($disbursement->description)
                    <div class="pt-2 border-t border-slate-700">
                        <p class="text-slate-400 text-xs uppercase tracking-wide mb-1.5">Catatan</p>
                        <p class="text-slate-300 text-sm bg-slate-900/60 rounded-lg p-3">{{ $disbursement->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- ============================================================
         BARIS 3: Daftar Payment — FULL WIDTH
    ============================================================ --}}
    <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-700 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm font-semibold text-white">Payment yang Dicairkan</p>
            </div>
            <span class="text-xs text-slate-500 bg-slate-700 px-2.5 py-1 rounded-full">{{ $disbursement->payments->count() }} payment</span>
        </div>

        @if($disbursement->payments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-900/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wide">Penghuni</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wide">Kamar</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wide">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wide">Metode Bayar</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wide">Jumlah</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wide">Tgl Konfirmasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/60">
                    @foreach($disbursement->payments as $payment)
                    <tr class="hover:bg-slate-700/40 transition">
                        <td class="px-4 py-4">
                            <p class="text-sm font-medium text-white leading-tight">{{ $payment->user->name ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-4 text-center text-sm text-slate-300">{{ $payment->billing->room->room_number ?? '-' }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2 py-0.5 text-xs font-medium bg-slate-700 text-slate-300 rounded-full">
                                {{ ucfirst($payment->billing->tipe ?? '-') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-300">{{ $payment->payment_full_label }}</td>
                        <td class="px-4 py-4 text-right text-sm font-semibold text-emerald-400">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4 text-center text-xs text-slate-400 leading-tight">
                            {{ $payment->verified_at?->format('d M Y') ?? '-' }}<br>
                            <span class="text-slate-500">{{ $payment->verified_at?->format('H:i') ?? '' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-slate-600 bg-slate-900/40">
                        <td colspan="4" class="px-4 py-3 text-slate-400 text-sm">Total Bruto</td>
                        <td class="px-4 py-3 text-right text-slate-300 font-medium text-sm">
                            Rp {{ number_format($disbursement->gross_amount ?? $disbursement->total_amount, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                    <tr class="bg-slate-900/40">
                        <td colspan="4" class="px-4 py-2 text-slate-400 text-sm">
                            Fee Platform ({{ number_format($disbursement->fee_percent ?? 0, 0) }}%)
                        </td>
                        <td class="px-4 py-2 text-right text-red-400 text-sm">
                            - Rp {{ number_format($disbursement->fee_amount ?? 0, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                    <tr class="border-t border-slate-600 bg-slate-900/40">
                        <td colspan="4" class="px-4 py-3 text-white font-bold">Diterima Admin</td>
                        <td class="px-4 py-3 text-right text-emerald-400 font-bold text-base">
                            Rp {{ number_format($disbursement->total_amount, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="px-6 py-16 text-center">
            <div class="w-14 h-14 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-slate-400 font-medium text-sm">Tidak ada payment terkait.</p>
        </div>
        @endif
    </div>

</div>
@endsection