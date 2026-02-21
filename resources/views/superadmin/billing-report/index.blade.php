@extends('layouts.superadmin')

@section('title', 'Laporan Pendapatan Platform')
@section('page-title', 'Laporan Pendapatan Platform')
@section('page-description', 'Laporan pendapatan platform dari fee pencairan dana')

@section('content')

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <!-- Total Pencairan -->
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-blue-500 hover:bg-slate-700">
        
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Total Pencairan</p>
                <p class="text-2xl font-bold text-slate-100">{{ $stats['total_disbursement_count'] }}</p>
                <p class="text-sm text-slate-100">Rp {{ number_format($stats['total_gross_amount'], 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Fee Platform -->
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-green-500 hover:bg-slate-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Total Fee Platform</p>
                <p class="text-2xl font-bold text-slate-100">Rp {{ number_format($stats['total_fee_amount'], 0, ',', '.') }}</p>
                <p class="text-sm text-slate-100">Pendapatan keseluruhan</p>
            </div>
            <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Fee Bulan Ini -->
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-orange-500 hover:bg-slate-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Fee Bulan Ini</p>
                <p class="text-2xl font-bold text-slate-100">Rp {{ number_format($stats['fee_this_month'], 0, ',', '.') }}</p>
                <p class="text-sm text-slate-100">{{ $stats['count_this_month'] }} pencairan</p>
            </div>
            <div class="w-12 h-12 bg-orange-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Fee Tahun Ini -->
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-yellow-500 hover:bg-slate-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Fee Tahun Ini</p>
                <p class="text-2xl font-bold text-slate-100">Rp {{ number_format($stats['fee_this_year'], 0, ',', '.') }}</p>
                <p class="text-sm text-slate-100">{{ now()->format('Y') }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
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

            <!-- Admin (Pemilik Kos) -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Admin Kos
                </label>
                <select name="admin_id" 
                        class="w-full h-[46px]
                           bg-slate-900 text-slate-100
                           border border-slate-700 rounded-lg px-4
                           focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Semua Admin</option>
                    @foreach($adminsList as $admin)
                        <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                            {{ $admin->name }} - {{ $admin->tempatKos->nama_kos ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Tempat Kos -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Tempat Kos
                </label>
                <select name="tempat_kos_id" 
                        class="w-full h-[46px]
                           bg-slate-900 text-slate-100
                           border border-slate-700 rounded-lg px-4
                           focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Semua Tempat Kos</option>
                    @foreach($tempatKosList as $tempatKos)
                        <option value="{{ $tempatKos->id }}" {{ request('tempat_kos_id') == $tempatKos->id ? 'selected' : '' }}>
                            {{ $tempatKos->nama_kos }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Placeholder kolom ke-5 kosong agar grid tetap -->
            <div class="hidden md:block"></div>

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
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Admin Kos</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Tempat Kos</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Jml Payment</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Gross Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Fee Platform</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Diterima Admin</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">Tanggal Cairkan</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-slate-800 divide-y divide-slate-700">
                @forelse($disbursements as $disbursement)
                <tr class="hover:bg-gray-700/50 transition">
                    <td class="px-4 py-3 text-sm text-gray-300">#{{ $disbursement->id }}</td>
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-white">{{ $disbursement->admin->name }}</div>
                        <div class="text-xs text-gray-400">{{ $disbursement->admin->email }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">
                        {{ $disbursement->tempatKos->nama_kos ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300 text-center">
                        {{ $disbursement->payment_count }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">
                        Rp {{ number_format($disbursement->gross_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm font-bold text-green-400">
                            Rp {{ number_format($disbursement->fee_amount, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-slate-400">{{ $disbursement->fee_percent }}%</div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">
                        Rp {{ number_format($disbursement->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">
                        {{ $disbursement->processed_at ? $disbursement->processed_at->format('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <a href="{{ route('superadmin.disbursements.show', $disbursement) }}" 
                           class="inline-flex items-center justify-center
                                  w-10 h-10 rounded-xl
                                  bg-blue-600/20 text-blue-400
                                  hover:bg-blue-600/30 hover:-translate-y-0.5
                                  transition-all duration-200">
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
                    <td colspan="9" class="px-4 py-8 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p>Tidak ada data pencairan yang ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($disbursements->hasPages())
    <div class="px-4 py-3 border-t border-gray-700">
        {{ $disbursements->links() }}
    </div>
    @endif

</div>

@endsection