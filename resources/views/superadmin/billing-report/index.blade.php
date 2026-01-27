@extends('layouts.superadmin')

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
                <p class="text-2xl font-bold text-slate-100">{{ $stats['total_count'] }}</p>
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
    <form method="GET" action="{{ route('superadmin.billing-report.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">

            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Dari Tanggal
                </label>
                <input type="date" 
                   name="date_from" 
                   value="{{ request('date_from') }}"
                   class="w-full bg-slate-900 text-slate-100
                        border border-slate-700 rounded-lg px-4 py-2
                        focus:ring-2 focus:ring-purple-500 focus:border-transparent
                        [color-scheme:dark]">
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Sampai Tanggal
                </label>
                <input type="date" 
                       name="date_to" 
                       value="{{ request('date_to') }}"
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
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Terlambat</option>
                </select>
            </div>

            <!-- Penyewa (Admin) -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Penyewa
                </label>
                <select name="admin_id" 
                        class="w-full h-[46px]
                           bg-slate-900 text-slate-100
                           border border-slate-700 rounded-lg px-4
                           focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Semua Penyewa</option>
                    @foreach($adminsList as $admin)
                        <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                            {{ $admin->name }} - {{ $admin->tempatKos->nama_kos ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kamar (Tempat Kos) -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Kamar
                </label>
                <select name="tempat_kos_id" 
                        class="w-full h-[46px]
                           bg-slate-900 text-slate-100
                           border border-slate-700 rounded-lg px-4
                           focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Semua Kamar</option>
                    @foreach($tempatKosList as $tempatKos)
                        <option value="{{ $tempatKos->id }}" {{ request('tempat_kos_id') == $tempatKos->id ? 'selected' : '' }}>
                            {{ $tempatKos->nama_kos }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <!-- Buttons -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
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

                <a href="{{ route('superadmin.billing-report.index') }}"
                   class="px-6 py-2 rounded-lg border border-slate-600
                          text-slate-300 hover:bg-slate-700 transition">
                    Reset
                </a>
            </div>

            <!-- Export -->
            <div class="flex space-x-2">
                <a href="{{ route('superadmin.billing-report.export-pdf', request()->query()) }}"
                   class="bg-red-600 text-white px-6 py-2 rounded-lg
                          hover:bg-red-700 transition">
                    Export PDF
                </a>

                <a href="{{ route('superadmin.billing-report.export-excel', request()->query()) }}"
                   class="bg-green-600 text-white px-6 py-2 rounded-lg
                          hover:bg-green-700 transition">
                    Export Excel
                </a>
            </div>

        </div>
    </form>
</div>

<!-- Data Table -->
<div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">

            <thead class="bg-slate-800/80 border-b border-slate-700">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Penyewa</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Kamar</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Periode</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-slate-800 divide-y divide-slate-700">
                @forelse($billings as $billing)
                <tr class="hover:bg-gray-700/50 transition">
                    <td class="px-4 py-3 text-sm text-gray-300">#{{ $billing->id }}</td>
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-white">{{ $billing->admin->name }}</div>
                        <div class="text-xs text-gray-400">{{ $billing->admin->email }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">
                        {{ $billing->tempatKos->nama_kos ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">
                        {{ \Carbon\Carbon::parse($billing->billing_period . '-01')->format('F Y') }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="text-gray-300">{{ $billing->due_date->format('d M Y') }}</div>
                        @if($billing->isOverdue())
                            <div class="text-xs text-red-400">({{ $billing->due_date->diffForHumans() }})</div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-white">Rp {{ number_format($billing->amount, 0, ',', '.') }}</div>
                        @if($billing->paid_at)
                            <div class="text-xs text-green-400">Dibayar: {{ $billing->paid_at->format('d M Y') }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($billing->status === 'paid')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-900/50 text-green-400 border border-green-700">
                                Lunas
                            </span>
                        @elseif($billing->status === 'pending')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-900/50 text-blue-400 border border-blue-700">
                                Pending
                            </span>
                        @elseif($billing->isOverdue())
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-900/50 text-red-400 border border-red-700">
                                Terlambat
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-900/50 text-yellow-400 border border-yellow-700">
                                Belum Dibayar
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <a href="{{ route('superadmin.billing.show', $billing) }}" 
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
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p>Tidak ada data tagihan yang ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($billings->hasPages())
    <div class="px-4 py-3 border-t border-gray-700">
        {{ $billings->links() }}
    </div>
    @endif

</div>

@endsection