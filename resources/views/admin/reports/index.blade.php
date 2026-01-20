@extends('layouts.admin')

@section('title', 'Laporan Tagihan')
@section('page-title', 'Laporan Tagihan')
@section('page-description', 'Laporan lengkap semua tagihan dan pembayaran')

@section('content')

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <!-- Total Tagihan -->
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-blue-500 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Total Tagihan</p>
                <p class="text-2xl font-bold text-slate-100">{{ $stats['total_billings'] }}</p>
                <p class="text-sm text-slate-100">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Lunas -->
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-green-500 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Lunas</p>
                <p class="text-2xl font-bold text-slate-100">{{ $stats['paid_count'] }}</p>
                <p class="text-sm text-slate-100">Rp {{ number_format($stats['paid_amount'], 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Belum Dibayar -->
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-orange-500 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Belum Dibayar</p>
                <p class="text-2xl font-bold text-slate-100">{{ $stats['unpaid_count'] }}</p>
                <p class="text-sm text-slate-100">Rp {{ number_format($stats['unpaid_amount'], 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Terlambat -->
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-red-500 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Terlambat</p>
                <p class="text-2xl font-bold text-slate-100">{{ $stats['overdue_count'] }}</p>
                <p class="text-sm text-slate-100">Rp {{ number_format($stats['overdue_amount'], 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-red-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Export Section -->
<div class="bg-slate-800 rounded-xl border border-slate-700 p-6 mb-6">
    <form method="GET" action="{{ route('admin.reports.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">

            <!-- Start Date -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Dari Tanggal
                </label>
                <input type="date" 
                       name="start_date" 
                       value="{{ request('start_date') }}"
                       class="w-full bg-slate-900 text-slate-100
                              border border-slate-700 rounded-lg px-4 py-2
                              focus:ring-2 focus:ring-purple-500 focus:border-transparent
                              [color-scheme:dark]">
            </div>
            
            <!-- End Date -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Sampai Tanggal
                </label>
                <input type="date" 
                       name="end_date" 
                       value="{{ request('end_date') }}"
                       class="w-full bg-slate-900 text-slate-100
                              border border-slate-700 rounded-lg px-4 py-2
                              focus:ring-2 focus:ring-purple-500 focus:border-transparent
                              [color-scheme:dark]">
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Status
                </label>
                <select name="status"
                    class="w-full h-[46px]
                           bg-slate-900 text-slate-100
                           border border-slate-700 rounded-lg px-4
                           focus:ring-2 focus:ring-purple-500 focus:border-transparent">

                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>

            <!-- User -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Penyewa
                </label>
                <select name="user_id"
                    class="w-full h-[46px]
                           bg-slate-900 text-slate-100
                           border border-slate-700 rounded-lg px-4
                           focus:ring-2 focus:ring-purple-500 focus:border-transparent">

                    <option value="">Semua Penyewa</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Room -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Kamar
                </label>
                <select name="room_id"
                    class="w-full h-[46px]
                           bg-slate-900 text-slate-100
                           border border-slate-700 rounded-lg px-4
                           focus:ring-2 focus:ring-purple-500 focus:border-transparent">

                    <option value="">Semua Kamar</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                            Kamar {{ $room->room_number }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <!-- Buttons -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <!-- Filter / Reset -->
            <div class="flex space-x-2">
                <button type="submit"
                    class="inline-flex items-center gap-2
                        bg-gradient-to-r from-yellow-500 to-orange-600
                        text-white font-semibold
                        px-5 py-2 rounded-lg
                        hover:from-yellow-600 hover:to-orange-700
                        transition-all shadow-lg">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </button>

                <a href="{{ route('admin.reports.index') }}"
                   class="px-6 py-2 rounded-lg border border-slate-600
                          text-slate-300 hover:bg-slate-700 transition">
                    Reset
                </a>
            </div>

            <!-- Export -->
            <div class="flex space-x-2">
                <a href="{{ route('admin.reports.export-pdf', request()->all()) }}"
                   class="bg-red-600 text-white px-6 py-2 rounded-lg
                          hover:bg-red-700 transition">
                    Export PDF
                </a>

                <a href="{{ route('admin.reports.export-excel', request()->all()) }}"
                   class="bg-green-600 text-white px-6 py-2 rounded-lg
                          hover:bg-green-700 transition">
                    Export Excel
                </a>
            </div>

        </div>
    </form>
</div>


<!-- Table -->
<div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">

            <thead class="bg-slate-800/80 border-b border-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase">Penyewa</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase">Kamar</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase">Periode</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase">Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-400 uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-slate-800 divide-y divide-slate-700">
                @forelse($billings as $billing)
                <tr class="odd:bg-slate-800 even:bg-slate-800/70 hover:bg-slate-700/60 transition">
                
                    <!-- ID -->
                    <td class="px-6 py-4 text-sm font-medium text-slate-100">
                        #{{ $billing->id }}
                    </td>
                
                    <!-- User -->
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm font-medium text-slate-100">
                                {{ $billing->user->name }}
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ $billing->user->email }}
                            </p>
                        </div>
                    </td>
                
                    <!-- Room -->
                    <td class="px-6 py-4 text-sm font-medium text-slate-100">
                        Kamar {{ $billing->room->room_number }}
                    </td>
                
                    <!-- Period -->
                    <td class="px-6 py-4 text-sm text-slate-300">
                        {{ $billing->formatted_period }}
                    </td>
                
                    <!-- Due Date -->
                    <td class="px-6 py-4 text-sm text-slate-300">
                        {{ $billing->due_date->translatedFormat('d F Y') }}
                        @if($billing->is_overdue)
                        <span class="block text-xs text-red-400 mt-1">
                            ({{ abs($billing->days_until_due) }} hari terlambat)
                        </span>
                        @endif
                    </td>
                
                    <!-- Amount -->
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm font-bold text-slate-100">
                                Rp {{ number_format($billing->total_amount, 0, ',', '.') }}
                            </p>
                            @if($billing->status === 'paid' && $billing->paid_date)
                            <p class="text-xs text-slate-400">
                                Dibayar: {{ $billing->paid_date->translatedFormat('d F Y') }}
                            </p>
                            @endif
                        </div>
                    </td>
                
                    <!-- Status -->
                    <td class="px-6 py-4">
                        @if($billing->status === 'paid')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                     bg-green-500/15 text-green-400 border border-green-500/30">
                            Lunas
                        </span>
                    
                        @elseif($billing->is_overdue)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                     bg-red-500/15 text-red-400 border border-red-500/30">
                            Terlambat
                        </span>
                    
                        @elseif($billing->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                     bg-yellow-500/15 text-yellow-400 border border-yellow-500/30">
                            Pending
                        </span>
                    
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                     bg-orange-500/15 text-orange-400 border border-orange-500/30">
                            Belum Dibayar
                        </span>
                        @endif
                    </td>
                
                    <!-- Action -->
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <a href="{{ route('admin.billing.show', $billing) }}"
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
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-slate-600 mb-4"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-lg font-medium mb-2">Tidak ada data</p>
                        <p class="text-sm">Belum ada tagihan yang sesuai dengan filter</p>
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
    
    @if($billings->hasPages())
    <div class="px-6 py-4 border-t border-slate-700 bg-slate-800">
        {{ $billings->links() }}
    </div>
    @endif
    
</div>

@endsection