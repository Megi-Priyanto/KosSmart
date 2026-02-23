@extends('layouts.superadmin')

@section('title', 'Kelola Refund Cancel Booking')
@section('page-title', 'Kelola Refund')
@section('page-description', 'Proses pengembalian dana DP untuk pembatalan booking yang disetujui admin')

@section('content')
<div class="space-y-6">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-yellow-500 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Total Pengajuan</p>
                    <p class="text-2xl font-bold text-slate-100">{{ $cancelBookings->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-orange-500 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Menunggu Refund</p>
                    <p class="text-2xl font-bold text-slate-400">{{ $pendingRefundCount }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-green-500 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Selesai Direfund</p>
                    <p class="text-2xl font-bold text-slate-400">
                        {{ $cancelBookings->where('status', 'approved')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-red-500 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-400">Ditolak Admin</p>
                    <p class="text-2xl font-bold text-slate-400">
                        {{ $cancelBookings->where('status', 'rejected')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-500/10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Banner alert jika ada yang menunggu --}}
    @if($pendingRefundCount > 0)
    <div class="px-6 py-5 bg-slate-800 border border-orange-500/40 rounded-xl shadow-sm">
        <div class="flex items-center gap-4">
            <div class="p-2 bg-orange-500/20 rounded-lg flex-shrink-0">
                <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-orange-300">
                    {{ $pendingRefundCount }} Refund Menunggu Diproses
                </h3>
                <p class="text-sm text-slate-300 mt-0.5">
                    Admin kos telah menyetujui pembatalan booking. Silakan proses pengembalian dana DP ke user.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Search & Filter (seperti contoh kelola booking) -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <!-- Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama user atau nomor rekening..."
                       class="w-full pl-12 pr-4 py-2.5 bg-slate-900 border border-slate-700 text-white rounded-lg
                              focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500
                              placeholder-slate-500 transition text-sm">
            </div>

            <!-- Filter Status -->
            <select name="status"
                    class="px-4 py-2.5 bg-slate-900 border border-slate-700 text-white rounded-lg
                           focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm min-w-40">
                <option value="">Semua Status</option>
                <option value="admin_approved" {{ request('status') === 'admin_approved' ? 'selected' : '' }}>Menunggu Refund</option>
                <option value="approved"       {{ request('status') === 'approved'       ? 'selected' : '' }}>Selesai</option>
                <option value="rejected"       {{ request('status') === 'rejected'       ? 'selected' : '' }}>Ditolak Admin</option>
            </select>

            <!-- Tombol Cari -->
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-yellow-500 to-orange-600
                           text-white font-semibold px-6 py-2.5 rounded-lg
                           hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>

            <!-- Tombol Reset -->
            @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('superadmin.refunds.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 border border-slate-600 text-slate-300
                      rounded-lg hover:bg-slate-700 transition text-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-800/80 border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Kos & Kamar</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Rekening</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Nominal DP</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-800 divide-y divide-slate-700">
                    @forelse($cancelBookings as $cancel)
                    <tr class="hover:bg-slate-700/30 transition-colors
                        {{ $cancel->status === 'admin_approved' ? 'bg-orange-500/5' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="font-semibold text-white">{{ $cancel->user->name }}</p>
                                <p class="text-xs text-slate-400">{{ $cancel->user->email }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $cancel->created_at->format('d M Y') }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="font-medium text-slate-200">{{ $cancel->tempatKos->nama_kos ?? '-' }}</p>
                                <p class="text-sm text-slate-400">Kamar {{ $cancel->rent->room->room_number ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="text-sm font-medium text-slate-200">{{ $cancel->bank_name }}</p>
                                <p class="text-sm text-slate-400">{{ $cancel->account_number }}</p>
                                <p class="text-xs text-slate-500">a/n {{ $cancel->account_holder_name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <p class="font-bold text-slate-400">
                                Rp {{ number_format($cancel->rent->deposit_paid ?? 0, 0, ',', '.') }}
                            </p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg {{ $cancel->status_badge }}">
                                @if($cancel->status === 'admin_approved') Menunggu Refund
                                @elseif($cancel->status === 'approved') Selesai
                                @else Ditolak
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($cancel->status === 'admin_approved')
                            <a href="{{ route('superadmin.refunds.show', $cancel) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600
                                      text-white text-sm font-semibold rounded-lg
                                      hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Proses Refund
                            </a>
                            @else
                            <a href="{{ route('superadmin.refunds.show', $cancel) }}"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-xl
                                      bg-blue-600/20 text-blue-400 hover:bg-blue-600/30 hover:-translate-y-0.5
                                      transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-lg font-medium text-slate-400 mb-1">Tidak Ada Data Refund</p>
                            <p class="text-sm text-slate-500">
                                @if(request()->hasAny(['search', 'status']))
                                    Tidak ada hasil untuk pencarian "{{ request('search') }}"
                                @else
                                    Belum ada permintaan refund dari admin kos
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($cancelBookings->hasPages())
        <div class="px-6 py-4 bg-slate-900/50 border-t border-slate-700">
            {{ $cancelBookings->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection