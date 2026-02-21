@extends('layouts.superadmin')

@section('title', 'Kelola Pencairan Dana')
@section('page-title', 'Kelola Pencairan Dana')
@section('page-description', 'Manajemen pencairan dana holding dari pembayaran penghuni kos ke admin')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-500/15 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- ===== BARIS 1: STATISTIK HOLDING ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-amber-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Total Dana Holding</p>
                    <p class="text-2xl font-bold text-white mt-2">Rp {{ number_format($stats['total_holding'], 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ $stats['holding_count'] }} payment belum dicairkan</p>
                </div>
                <div class="w-12 h-12 bg-amber-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-blue-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Masuk Bulan Ini</p>
                    <p class="text-2xl font-bold text-white mt-2">Rp {{ number_format($stats['holding_this_month'], 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ now()->format('F Y') }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-emerald-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Total Dicairkan</p>
                    <p class="text-2xl font-bold text-white mt-2">Rp {{ number_format($stats['total_disbursed'], 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500 mt-1">Semua waktu</p>
                </div>
                <div class="w-12 h-12 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-violet-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Dicairkan Bulan Ini</p>
                    <p class="text-2xl font-bold text-white mt-2">Rp {{ number_format($stats['disbursed_this_month'], 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500 mt-1">{{ now()->format('F Y') }}</p>
                </div>
                <div class="w-12 h-12 bg-violet-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== BARIS 2: PENDAPATAN PLATFORM DARI FEE ===== --}}
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pendapatan Platform (Fee)
                </h2>
                <p class="text-xs text-slate-400 mt-1 ml-10">Potongan yang diambil platform dari setiap pencairan ke admin kos</p>
            </div>
            <span class="bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 text-xs font-medium px-3 py-1 rounded-full">Fee dari disbursement</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-slate-900/60 rounded-xl p-4 border border-slate-700/50">
                <p class="text-slate-400 text-xs mb-2">Fee Bulan Ini</p>
                <p class="text-2xl font-bold text-yellow-400">Rp {{ number_format($stats['platform_fee_this_month'], 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ now()->format('F Y') }}</p>
            </div>
            <div class="bg-slate-900/60 rounded-xl p-4 border border-slate-700/50">
                <p class="text-slate-400 text-xs mb-2">Fee Tahun Ini</p>
                <p class="text-2xl font-bold text-yellow-400">Rp {{ number_format($stats['platform_fee_this_year'], 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ now()->format('Y') }}</p>
            </div>
            <div class="bg-slate-900/60 rounded-xl p-4 border border-slate-700/50">
                <p class="text-slate-400 text-xs mb-2">Total Fee All Time</p>
                <p class="text-2xl font-bold text-yellow-400">Rp {{ number_format($stats['platform_fee_total'], 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 mt-1">Semua waktu</p>
            </div>
        </div>
    </div>

    {{-- ===== HOLDING PER KOS ===== --}}
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-700 flex items-center justify-between">
            <h2 class="text-base font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Dana Menunggu Pencairan per Kos
            </h2>
            <span class="text-xs text-slate-500">{{ $holdingSummary->count() }} kos</span>
        </div>

        @if($holdingSummary->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-800/80 border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Tempat Kos</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase">Total Holding</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-slate-400 uppercase">Jumlah Payment</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-slate-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($holdingSummary as $item)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-white">{{ $item->tempatKos->nama_kos ?? 'Kos #' . $item->tempat_kos_id }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-semibold text-amber-400">Rp {{ number_format($item->total_holding, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-xs font-medium bg-slate-700 text-slate-300 rounded-full">{{ $item->payment_count }} payment</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('superadmin.disbursements.create', ['tempat_kos_id' => $item->tempat_kos_id]) }}"
                               class="inline-flex items-center gap-2
                                      bg-gradient-to-r from-yellow-500 to-orange-600
                                      text-white text-xs font-semibold
                                      px-4 py-2 rounded-lg
                                      hover:from-yellow-600 hover:to-orange-700
                                      transition-all shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Cairkan Dana
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <div class="w-16 h-16 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-slate-400 font-medium">Tidak ada dana yang perlu dicairkan</p>
            <p class="text-slate-500 text-sm mt-1">Semua pembayaran sudah dicairkan ke admin kos.</p>
        </div>
        @endif
    </div>

    {{-- ===== RIWAYAT DISBURSEMENT ===== --}}
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-700">
            <div class="flex flex-col md:flex-row gap-4 items-end justify-between">
                <h2 class="text-base font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Riwayat Pencairan Dana
                </h2>
                <form method="GET" class="flex flex-wrap gap-2">
                    <select name="status" class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Processed</option>
                    </select>
                    <select name="tempat_kos_id" class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500">
                        <option value="">Semua Kos</option>
                        @foreach($tempatKosList as $kos)
                            <option value="{{ $kos->id }}" {{ request('tempat_kos_id') == $kos->id ? 'selected' : '' }}>{{ $kos->nama_kos }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="inline-flex items-center gap-2
                               bg-gradient-to-r from-yellow-500 to-orange-600
                               text-white font-semibold text-sm
                               px-4 py-2 rounded-lg
                               hover:from-yellow-600 hover:to-orange-700
                               transition-all shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>
                </form>
            </div>
        </div>

        @if($disbursements->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-800/80 border-b border-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Tempat Kos</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Admin Penerima</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-400 uppercase">Bruto</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Fee</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-slate-400 uppercase">Diterima Admin</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($disbursements as $disbursement)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-4 py-4 text-sm text-slate-300">{{ $disbursement->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-4 text-sm font-medium text-white">{{ $disbursement->tempatKos->nama_kos ?? '-' }}</td>
                        <td class="px-4 py-4 text-sm text-slate-300">{{ $disbursement->admin->name ?? '-' }}</td>
                        <td class="px-4 py-4 text-right text-sm text-slate-300">
                            Rp {{ number_format($disbursement->gross_amount ?? $disbursement->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="text-xs font-medium text-amber-400">{{ $disbursement->fee_percent ?? 0 }}%</span>
                            <br>
                            <span class="text-xs text-red-400">- Rp {{ number_format($disbursement->fee_amount ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-4 py-4 text-right text-sm font-semibold text-emerald-400">
                            Rp {{ number_format($disbursement->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $disbursement->status_badge }}">
                                {{ $disbursement->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <a href="{{ route('superadmin.disbursements.show', $disbursement) }}"
                               class="inline-flex items-center justify-center
                                      w-9 h-9 rounded-xl
                                      bg-blue-600/20 text-blue-400
                                      hover:bg-blue-600/30 hover:-translate-y-0.5
                                      transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($disbursements->hasPages())
        <div class="p-4 border-t border-slate-700">
            {{ $disbursements->links() }}
        </div>
        @endif
        @else
        <div class="px-6 py-12 text-center">
            <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-slate-400">Belum ada riwayat pencairan dana.</p>
        </div>
        @endif
    </div>

</div>
@endsection