@extends('layouts.superadmin')

@section('title', 'Kelola Tagihan Admin')
@section('page-title', 'Kelola Tagihan')
@section('page-description', 'Manajemen tagihan operasional untuk admin kos')

@section('content')
<div class="space-y-6">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-blue-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Total Tagihan</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-yellow-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Belum Dibayar</p>
                    <p class="text-3xl font-bold text-yellow-400 mt-2">{{ $stats['unpaid'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-red-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Terlambat</p>
                    <p class="text-3xl font-bold text-red-400 mt-2">{{ $stats['overdue'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-blue-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Lunas</p>
                    <p class="text-3xl font-bold text-green-400 mt-2">{{ $stats['paid'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <div class="flex flex-col md:flex-row gap-4 items-end justify-between">
            <form method="GET" class="flex-1 flex flex-wrap gap-3">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari tempat kos atau admin..."
                       class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500 flex-1 min-w-[200px]">
                
                <select name="status" 
                        class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500">
                    <option value="">Semua Status</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Terlambat</option>
                </select>

                <select name="month" 
                        class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endfor
                </select>

                <select name="year" 
                        class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500">
                    <option value="">Semua Tahun</option>
                    @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <button type="submit" 
                    class="inline-flex items-center gap-2
                        bg-gradient-to-r from-yellow-500 to-orange-600
                        text-white font-semibold
                        px-5 py-2 rounded-lg
                        hover:from-yellow-600 hover:to-orange-700
                        transition-all shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>
            </form>

            <!-- Action Bar -->
            <div class="flex gap-3">
                <a href="{{ route('superadmin.billing.create') }}" 
                   class="inline-flex items-center gap-2
                        bg-gradient-to-r from-yellow-500 to-orange-600
                        text-white font-semibold
                        px-5 py-2 rounded-lg
                        hover:from-yellow-600 hover:to-orange-700
                        transition-all shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Generate Massal
                </a>
            </div>
        </div>
    </div>

    <!-- Billing Table -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">

                <thead class="bg-slate-800/80 border-b border-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Periode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Admin</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Tempat Kos</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Jatuh Tempo</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    {{-- ERBAIKAN: Gunakan $billings dan beri alias $item untuk item loop --}}
                    @forelse($billings as $item)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-white">
                                {{ \Carbon\Carbon::parse($item->billing_period . '-01')->format('F Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="text-sm font-medium text-white">{{ $item->admin->name }}</p>
                                <p class="text-xs text-slate-400">{{ $item->admin->email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-300">{{ $item->tempatKos->nama_kos }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-yellow-400">
                                Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-300">
                            {{ $item->due_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColor = $item->status_color;
                            @endphp
                            <span class="px-3 py-1 text-xs font-medium bg-{{ $statusColor }}-500/20 text-{{ $statusColor }}-400 rounded-full">
                                {{ $item->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <a href="{{ route('superadmin.billing.show', $item) }}"
                               class="inline-flex items-center justify-center
                                      w-10 h-10 rounded-xl
                                      bg-blue-600/20 text-blue-400
                                      hover:bg-blue-600/30 hover:-translate-y-0.5
                                      transition-all duration-200">

                                {{-- ICON DETAIL / EYE --}}
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5
                                             c4.478 0 8.268 2.943 9.542 7
                                             -1.274 4.057-5.064 7-9.542 7
                                             -4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-slate-400">Tidak ada tagihan ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PERBAIKAN: Gunakan $billings --}}
        @if($billings->hasPages())
        <div class="p-4 border-t border-slate-700">
            {{ $billings->links() }}
        </div>
        @endif
    </div>

</div>
@endsection