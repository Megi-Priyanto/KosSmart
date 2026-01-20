@extends('layouts.admin')

@section('title', 'Pembatalan Booking')
@section('page-title', 'Kelola Pembatalan Booking')
@section('page-description', 'Proses pengembalian dana DP untuk pembatalan booking')

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
            <div class="w-12 h-12 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-orange-500 hover:bg-slate-700">
    
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Menunggu Proses</p>
                <p class="text-2xl font-bold text-slate-100">{{ $pendingCount }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-green-500 hover:bg-slate-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Selesai Diproses</p>
                <p class="text-2xl font-bold text-slate-100">
                    {{ $cancelBookings->where('status', 'approved')->count() }}
                </p>
            </div>
            <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="bg-slate-800 rounded-xl border border-slate-700 p-6 mb-6">
    <form method="GET" class="flex gap-3 justify-end">
        <select name="status" 
                class="px-4 py-2 bg-slate-900 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-yellow-500">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
        </select>
        
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
        
        @if(request()->hasAny(['status']))
            <a href="{{ route('admin.cancel-bookings.index') }}" 
               class="px-4 py-2 border border-slate-600 text-slate-300 rounded-lg hover:bg-slate-700 transition">
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
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Kamar</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Rekening</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-slate-800 divide-y divide-slate-700">
                @forelse($cancelBookings as $cancel)
                <tr class="hover:bg-slate-700/30 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="font-semibold text-white">{{ $cancel->user->name }}</p>
                            <p class="text-sm text-slate-400">{{ $cancel->user->email }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="font-semibold text-white">{{ $cancel->rent->room->room_number }}</p>
                            <p class="text-sm text-slate-400">DP: Rp {{ number_format($cancel->rent->deposit_paid, 0, ',', '.') }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="text-sm text-slate-300">{{ $cancel->bank_name }}</p>
                            <p class="text-sm text-slate-400">{{ $cancel->account_number }}</p>
                            <p class="text-xs text-slate-500">a/n {{ $cancel->account_holder_name }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1.5 text-sm font-bold rounded-lg {{ $cancel->status_badge }}">
                            {{ $cancel->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        @if($cancel->status === 'pending')
                        <a href="{{ route('admin.cancel-bookings.refund-form', $cancel) }}"
                           class="inline-block px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                            Proses Refund
                        </a>
                        @elseif($cancel->status === 'approved')
                        <span class="text-sm text-green-400">Selesai</span>
                        @else
                        <span class="text-sm text-red-400">Ditolak</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    
                        <p class="text-lg font-medium mb-2">Tidak Ada Pembatalan</p>
                        <p class="text-sm">Belum ada permintaan pembatalan booking</p>
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