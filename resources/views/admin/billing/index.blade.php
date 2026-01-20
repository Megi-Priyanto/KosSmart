@extends('layouts.admin')

@section('title', 'Kelola Tagihan')
@section('page-title', 'Kelola Tagihan')
@section('page-description', 'Manajemen tagihan dan pembayaran penghuni kos')

@section('content')
<div class="space-y-6">
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

        <!-- Total -->
        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
                    transition-all duration-300 ease-out
                    hover:-translate-y-1 hover:shadow-lg hover:border-slate-600 hover:bg-slate-700">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Total Tagihan</p>
                    <p class="text-2xl font-bold text-slate-100">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Belum Dibayar -->
        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
                    transition-all duration-300 ease-out
                    hover:-translate-y-1 hover:shadow-lg hover:border-slate-600 hover:bg-slate-700">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Belum Dibayar</p>
                    <p class="text-2xl font-bold text-slate-100">{{ $stats['unpaid'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                    <p class="text-2xl font-bold text-slate-100">{{ $stats['overdue'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
                    transition-all duration-300 ease-out
                    hover:-translate-y-1 hover:shadow-lg hover:border-yellow-500 hover:bg-slate-700">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Pending</p>
                    <p class="text-2xl font-bold text-slate-100">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                    <p class="text-2xl font-bold text-slate-100">{{ $stats['paid'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6 mb-6">
            
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col md:flex-row gap-3 flex-1">
                <form method="GET" class="flex flex-col md:flex-row gap-3 flex-1">
    
                    <!-- Search -->
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Cari penghuni atau nomor kamar..." 
                            class="w-full pl-10 pr-4 py-2 bg-slate-900 border border-slate-700 text-gray-100
                                   rounded-lg focus:ring-2 focus:ring-purple-500 placeholder-gray-500">
                    </div>
                
                    <!-- Filters -->
                    <select name="status" class="px-4 py-2 bg-slate-900 text-gray-100 border border-slate-700 rounded-lg">
                        <option value="">Semua Status</option>
                        <option value="unpaid" {{ request('status')=='unpaid'?'selected':'' }}>Belum Dibayar</option>
                        <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                        <option value="overdue" {{ request('status')=='overdue'?'selected':'' }}>Terlambat</option>
                        <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Lunas</option>
                    </select>
                
                    <select name="month" class="px-4 py-2 bg-slate-900 text-gray-100 border border-slate-700 rounded-lg">
                        <option value="">Semua Bulan</option>
                        @for($m=1;$m<=12;$m++)
                            <option value="{{ $m }}" {{ request('month')==$m?'selected':'' }}>
                                {{ DateTime::createFromFormat('!m',$m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                
                    <select name="year" class="px-4 py-2 bg-slate-900 text-gray-100 border border-slate-700 rounded-lg">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year')==$year?'selected':'' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Search Button -->
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

                    @if(request()->hasAny(['search', 'status', 'type', 'floor']))
                    <a href="{{ route('admin.billing.index') }}" 
                       class="px-4 py-2 border border-slate-600 text-slate-300 hover:bg-slate-700 rounded-lg">
                        Reset
                    </a>
                    @endif
                </form>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="button" 
                        onclick="document.getElementById('bulkGenerateModal').classList.remove('hidden')"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-center">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                        </svg>
                    Generate Massal
                </button>
                <a href="{{ route('admin.billing.create') }}"
                    class="inline-flex items-center gap-2
                        bg-gradient-to-r from-yellow-500 to-orange-600
                        text-white font-semibold
                        px-5 py-2 rounded-lg
                        hover:from-yellow-600 hover:to-orange-700
                        transition-all shadow-lg">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Tagihan
                </a>
            </div>
        </div>
    </div>

    <!-- Billings Table -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">

                <thead class="bg-slate-800/80 border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Penghuni</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Kamar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>

                <tbody class="bg-slate-800 divide-y divide-slate-700">
                    @forelse($billings as $billing)
                    <tr class="transition-all duration-300 ease-out hover:bg-slate-700/60 hover:-translate-y-0.5">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-100">{{ $billing->formatted_period }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-200">{{ $billing->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $billing->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-200">{{ $billing->room->room_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-slate-200">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-200">{{ $billing->due_date->format('d M Y') }}</div>
                            @if($billing->is_overdue)
                                <div class="text-xs text-red-600">{{ abs($billing->days_until_due) }} hari terlambat</div>
                            @elseif($billing->status !== 'paid')
                                <div class="text-xs text-slate-200">{{ $billing->days_until_due }} hari lagi</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $billing->status_badge }}">
                                {{ $billing->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center gap-3">
                            
                                {{-- DETAIL --}}
                                <a href="{{ route('admin.billing.show', $billing) }}"
                                       class="inline-flex w-10 h-10 rounded-xl 
                                              bg-blue-600/20 text-blue-400
                                              items-center justify-center
                                              hover:bg-blue-600/30 hover:-translate-y-0.5
                                              transition-all duration-200">
                                    {{-- ICON PLUS / EYE --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                         class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                 c4.478 0 8.268 2.943 9.542 7
                                                 -1.274 4.057-5.064 7-9.542 7
                                                 -4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            
                                {{-- EDIT --}}
                                @if($billing->status !== 'paid')
                                    <a href="{{ route('admin.billing.edit', $billing) }}"
                                           class="inline-flex w-10 h-10 rounded-xl 
                                                  bg-yellow-500/20 text-yellow-400
                                                  items-center justify-center
                                                  hover:bg-yellow-500/30 hover:-translate-y-0.5
                                                  transition-all duration-200">
                                        {{-- ICON PLUS / EDIT --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                             class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M18.586 2.586a2 2 0 112.828 2.828L11 15l-4 1 1-4 10.586-10.414z"/>
                                        </svg>
                                    </a>
                                @endif
                                
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-16 w-16 text-slate-500"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                                             a1 1 0 01.707.293l5.414 5.414
                                             a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-lg font-medium mb-2">Tidak ada Tagihan</p>
                            <p class="text-sm">Belum ada tagihan yang perlu diproses</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($billings->hasPages())
        <div class="px-6 py-4 border-t border-slate-700 bg-slate-800">
            {{ $billings->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Bulk Generate Modal -->
<div id="bulkGenerateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md
            bg-slate-800 rounded-lg border-slate-700
            transform transition-all duration-300 ease-out
            scale-95 opacity-0
            [x-show]:scale-100 [x-show]:opacity-100">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Generate Tagihan Massal</h3>
            <button onclick="document.getElementById('bulkGenerateModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.billing.bulk-generate') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="billing_month" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <input type="number" name="billing_year" value="{{ now()->year }}" required min="2024" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jatuh Tempo</label>
                    <input type="date" name="due_date" required min="{{ now()->format('Y-m-d') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <p class="text-sm text-blue-700">
                        Tagihan akan dibuat untuk semua penyewa aktif dengan biaya sesuai harga kamar masing-masing.
                    </p>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="button" 
                        onclick="document.getElementById('bulkGenerateModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    Generate
                </button>
            </div>
        </form>
    </div>
</div>

@endsection