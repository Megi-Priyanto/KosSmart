@extends('layouts.admin')

@section('title', 'Pembatalan Booking')
@section('page-title', 'Kelola Pembatalan Booking')
@section('page-description', 'Terima dan setujui permintaan pembatalan booking dari penghuni')

@section('content')

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-purple-500 hover:bg-slate-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Total Pembatalan</p>
                <p class="text-2xl font-bold text-slate-100">{{ $cancelBookings->total() }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-yellow-500 hover:bg-slate-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Menunggu Persetujuan</p>
                <p class="text-2xl font-bold text-slate-100">{{ $pendingCount }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-blue-500 hover:bg-slate-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Menunggu Refund Superadmin</p>
                <p class="text-2xl font-bold text-slate-100">
                    {{ $cancelBookings->where('status', 'admin_approved')->count() }}
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Alur Info --}}
<div class="bg-slate-800 border border-slate-700 rounded-xl p-5 mb-6">
    <h3 class="text-sm font-semibold text-slate-300 mb-3 flex items-center gap-2">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Alur Pembatalan Booking
    </h3>
    <div class="flex flex-wrap items-center gap-2 text-xs">
        <span class="px-3 py-1.5 bg-yellow-500/20 text-yellow-300 border border-yellow-500/40 rounded-lg font-medium">
            1. User Ajukan Cancel
        </span>
        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="px-3 py-1.5 bg-green-500/20 text-green-300 border border-green-500/40 rounded-lg font-medium">
            2. Admin Setujui
        </span>
        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="px-3 py-1.5 bg-blue-500/20 text-blue-300 border border-blue-500/40 rounded-lg font-medium">
            3. Superadmin Proses Refund DP
        </span>
        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="px-3 py-1.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-lg font-medium">
            4. Selesai
        </span>
    </div>
</div>

<!-- Filter & Search -->
<div class="bg-slate-800 rounded-xl border border-slate-700 p-6 mb-6">
    <form method="GET" class="flex gap-3 items-center">
        {{-- Search --}}
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama penyewa atau nomor kamar..."
                   class="w-full pl-10 pr-4 py-2 bg-slate-900 text-slate-100
                          border border-slate-700 rounded-lg
                          focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500
                          placeholder-slate-500 transition-all duration-200">
        </div>

        <div class="flex gap-3 flex-shrink-0">
            <select name="status"
                    class="px-4 py-2 bg-slate-900 border border-slate-700 text-white rounded-lg
                           focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200">
                <option value="">Semua Status</option>
                <option value="pending"        {{ request('status') === 'pending'        ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="admin_approved" {{ request('status') === 'admin_approved' ? 'selected' : '' }}>Menunggu Refund Superadmin</option>
                <option value="approved"       {{ request('status') === 'approved'       ? 'selected' : '' }}>Selesai</option>
            </select>

            <button type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-yellow-500 to-orange-600
                       text-white font-semibold px-5 py-2 rounded-lg
                       hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>

            @if(request()->hasAny(['status', 'search']))
                <a href="{{ route('admin.cancel-bookings.index') }}"
                   class="px-4 py-2 border border-slate-600 text-slate-300 hover:bg-slate-700 rounded-lg flex-shrink-0">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-800/80 border-b border-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Kamar</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Rekening User</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">DP</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-slate-800 divide-y divide-slate-700">
                @forelse($cancelBookings as $cancel)
                <tr class="hover:bg-slate-700/30 transition-colors
                    {{ $cancel->status === 'pending' ? 'bg-yellow-500/5' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="font-semibold text-white">{{ $cancel->user->name }}</p>
                            <p class="text-sm text-slate-400">{{ $cancel->user->email }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="font-semibold text-white">{{ $cancel->rent->room->room_number ?? '-' }}</p>
                            <p class="text-xs text-slate-400">{{ $cancel->created_at->format('d M Y') }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="text-sm text-slate-300 font-medium">{{ $cancel->bank_name }}</p>
                            <p class="text-sm text-slate-400">{{ $cancel->account_number }}</p>
                            <p class="text-xs text-slate-500">a/n {{ $cancel->account_holder_name }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-bold text-yellow-400">
                            Rp {{ number_format($cancel->rent->deposit_paid ?? 0, 0, ',', '.') }}
                        </p>
                    </td>

                    {{-- Kolom Status: plain text tanpa background/kotak --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($cancel->status === 'pending')
                            <span class="text-sm font-semibold text-yellow-400">Menunggu Persetujuan</span>
                        @elseif($cancel->status === 'admin_approved')
                            <span class="text-sm font-semibold text-blue-400">Menunggu Refund Superadmin</span>
                        @elseif($cancel->status === 'approved')
                            <span class="text-sm font-semibold text-green-400">Selesai</span>
                        @else
                            <span class="text-sm text-slate-400">{{ $cancel->status }}</span>
                        @endif
                    </td>

                    {{-- Kolom Aksi: satu tombol icon show --}}
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('admin.cancel-bookings.show', $cancel) }}"
                           class="inline-flex items-center justify-center
                                  w-10 h-10 rounded-xl
                                  bg-blue-600/20 text-blue-400
                                  hover:bg-blue-600/30 hover:-translate-y-0.5
                                  transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-lg font-medium mb-2 text-slate-400">Tidak Ada Pembatalan</p>
                        <p class="text-sm text-slate-500">Belum ada permintaan pembatalan booking</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($cancelBookings->hasPages())
    <div class="px-6 py-4 bg-slate-900/50 border-t border-slate-700">
        {{ $cancelBookings->links() }}
    </div>
    @endif
</div>

@endsection