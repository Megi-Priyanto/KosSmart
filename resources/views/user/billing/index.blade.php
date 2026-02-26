@extends('layouts.user')

@section('title', 'Tagihan Saya')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Tagihan Saya</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola tagihan dan pembayaran kos Anda</p>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <div class="p-5 rounded-xl shadow-xl transition-all hover:border-yellow-500" style="background:#1e293b; border:1px solid #334155;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 mb-1">Total Tagihan</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center" style="background:rgba(99,102,241,0.15);">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-5 rounded-xl shadow-xl transition-all hover:border-yellow-500" style="background:#1e293b; border:1px solid #334155;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 mb-1">Belum Dibayar</p>
                    <p class="text-2xl font-bold text-red-400">{{ $stats['unpaid'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center" style="background:rgba(239,68,68,0.12);">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-5 rounded-xl shadow-xl transition-all hover:border-yellow-500" style="background:#1e293b; border:1px solid #334155;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 mb-1">Pending</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center" style="background:rgba(234,179,8,0.12);">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-5 rounded-xl shadow-xl transition-all hover:border-yellow-500" style="background:#1e293b; border:1px solid #334155;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 mb-1">Lunas</p>
                    <p class="text-2xl font-bold text-green-400">{{ $stats['paid'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center" style="background:rgba(34,197,94,0.12);">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- TUNGGAKAN ALERT --}}
    @if($stats['total_unpaid'] > 0)
    <div class="p-4 rounded-xl flex items-center gap-3" style="background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.3);">
        <svg class="w-6 h-6 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold text-red-400">Total Tunggakan</p>
            <p class="text-xl font-bold text-red-400">Rp {{ number_format($stats['total_unpaid'], 0, ',', '.') }}</p>
        </div>
    </div>
    @endif

    {{-- FILTER --}}
    <div class="p-4 rounded-xl" style="background:#1e293b; border:1px solid #334155;">
        <div class="flex flex-col md:flex-row gap-3">
            <form method="GET" class="flex gap-2 flex-1">
                <select name="status" onchange="this.form.submit()"
                        class="px-4 py-2 rounded-lg text-sm text-slate-200 flex-1"
                        style="background:#0f172a; border:1px solid #334155;">
                    <option value="">Semua Status</option>
                    <option value="unpaid"   {{ request('status')==='unpaid'   ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="pending"  {{ request('status')==='pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="overdue"  {{ request('status')==='overdue'  ? 'selected' : '' }}>Terlambat</option>
                    <option value="paid"     {{ request('status')==='paid'     ? 'selected' : '' }}>Lunas</option>
                </select>
                <select name="year" onchange="this.form.submit()"
                        class="px-4 py-2 rounded-lg text-sm text-slate-200"
                        style="background:#0f172a; border:1px solid #334155;">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year')==$year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('user.payment.history') }}"
               class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg text-sm flex items-center justify-center">
                Riwayat Pembayaran
            </a>
        </div>
    </div>

    {{-- BILLING GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($billings as $billing)
        <div class="rounded-xl overflow-hidden shadow-xl transition-all hover:border-yellow-500 flex flex-col"
             style="background:#1e293b; border:1px solid #334155;">

            {{-- Card Header --}}
            <div class="p-4" style="border-bottom:1px solid #334155; background:{{ $billing->status === 'paid' ? 'rgba(34,197,94,0.08)' : ($billing->is_overdue ? 'rgba(239,68,68,0.08)' : '#0f172a') }};">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-base font-bold text-white">{{ $billing->formatted_period }}</h3>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $billing->status_badge }}">
                        {{ $billing->status_label }}
                    </span>
                </div>
                <p class="text-sm text-slate-400">Kamar {{ $billing->room->room_number }}</p>
            </div>

            {{-- Card Body --}}
            <div class="p-5 flex flex-col flex-grow">
                <div class="mb-4">
                    <p class="text-xs text-slate-500 mb-1">Total Tagihan</p>
                    <p class="text-2xl font-bold text-yellow-400">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</p>
                </div>

                <div class="space-y-2 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Jatuh Tempo</span>
                        <span class="font-medium text-slate-200">{{ $billing->due_date->format('d M Y') }}</span>
                    </div>

                    @if($billing->status !== 'paid')
                        @if($billing->is_overdue)
                        <div class="flex items-center gap-1 text-red-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="font-semibold">Terlambat {{ abs($billing->days_until_due) }} hari</span>
                        </div>
                        @else
                        <div class="flex items-center gap-1 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $billing->days_until_due }} hari lagi</span>
                        </div>
                        @endif
                    @else
                    <div class="flex items-center gap-1 text-green-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">Lunas: {{ $billing->paid_date->format('d M Y') }}</span>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 mt-auto">
                    <a href="{{ route('user.billing.show', $billing->id) }}"
                       class="flex-1 py-2 text-sm text-center font-semibold rounded-lg transition-all"
                       style="background:#0f172a; border:1px solid #334155; color:#e2e8f0;"
                       onmouseover="this.style.borderColor='#475569'"
                       onmouseout="this.style.borderColor='#334155'">
                        Lihat Detail
                    </a>
                    @if($billing->status !== 'paid')
                    <a href="{{ route('user.billing.pay', $billing) }}"
                       class="flex-1 py-2 text-sm text-center font-semibold text-white rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 transition-all shadow-lg">
                        Bayar
                    </a>
                    @endif
                </div>
            </div>
        </div>

        @empty
        <div class="col-span-full p-12 text-center rounded-xl" style="background:#1e293b; border:1px solid #334155;">
            <svg class="mx-auto h-14 w-14 text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-base font-semibold text-slate-400 mb-1">Belum Ada Tagihan</h3>
            <p class="text-sm text-slate-600">Tagihan akan muncul di sini ketika admin membuatnya</p>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if($billings->hasPages())
    <div class="p-4 rounded-xl" style="background:#1e293b; border:1px solid #334155;">
        {{ $billings->links() }}
    </div>
    @endif

</div>
@endsection