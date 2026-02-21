@extends('layouts.admin')

@section('title', 'Kelola Booking')
@section('page-title', 'Kelola Booking')
@section('page-description', 'Manajemen booking dan persetujuan penyewaan kamar')

@section('content')

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-purple-500 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Total Booking</p>
                <p class="text-2xl font-bold text-slate-100">{{ $bookings->total() }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-yellow-500 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Menunggu Approval</p>
                <p class="text-2xl font-bold text-slate-100">{{ $pendingCount }}</p>
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
        hover:-translate-y-1 hover:shadow-lg hover:border-green-500 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Booking Aktif</p>
                <p class="text-2xl font-bold text-slate-100">
                    {{ \App\Models\Rent::where('status', 'active')->count() }}
                </p>
            </div>
            <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-red-500 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Dibatalkan</p>
                <p class="text-2xl font-bold text-slate-100">
                    {{ \App\Models\Rent::where('status', 'cancelled')->count() }}
                </p>
            </div>
            <div class="w-12 h-12 bg-red-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
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
                          focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                          placeholder-slate-500 transition-all duration-200">
        </div>

        <div class="flex gap-3 flex-shrink-0">
            <select name="status" class="px-4 py-2 bg-slate-900 text-gray-100
                    border border-slate-700 rounded-lg
                    focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                    transition-all duration-200">

            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
        </select>

            <button type="submit"
                class="inline-flex items-center gap-2
                    bg-gradient-to-r from-yellow-500 to-orange-600
                    text-white font-semibold
                    px-5 py-2 rounded-lg
                    hover:from-yellow-600 hover:to-orange-700
                    transition-all shadow-lg flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>

            @if(request()->hasAny(['status', 'search']))
            <a href="{{ route('admin.bookings.index') }}"
               class="px-4 py-2 border border-slate-600 text-slate-300 hover:bg-slate-700 rounded-lg flex-shrink-0">
                Reset
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Bookings Table -->
<div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">

            <thead class="bg-slate-800/80 border-b border-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Tanggal Booking</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Penyewa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Kamar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Mulai Sewa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">DP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-slate-800 divide-y divide-slate-700">
                @forelse($bookings as $booking)
                <tr class="hover:bg-slate-700/40 {{ $booking->status == 'pending' ? 'bg-yellow-500/10' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">

                        {{ $booking->created_at->translatedFormat('d F Y, H:i') }}
                        <p class="text-xs text-slate-400">{{ $booking->created_at->diffForHumans() }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="text-sm font-medium text-slate-200">{{ $booking->user->name }}</p>
                            <p class="text-xs text-slate-400">{{ $booking->user->email }}</p>
                            <p class="text-xs text-slate-400">{{ $booking->user->phone ?? '-' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="text-sm font-medium text-slate-200">Kamar {{ $booking->room->room_number }}</p>
                            <p class="text-xs text-slate-400">{{ $booking->room->floor }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">
                        {{ $booking->start_date->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">
                        Rp {{ number_format($booking->deposit_paid, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            @if($booking->status === 'pending') bg-yellow-500/10 text-yellow-400
                            @elseif($booking->status === 'active') bg-green-500/10 text-green-400
                            @elseif($booking->status === 'cancelled') bg-red-500/10 text-red-400
                            @else bg-slate-500/10 text-slate-400
                            @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <a href="{{ route('admin.bookings.show', $booking) }}"
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
                        <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-lg font-medium mb-2">Tidak ada booking</p>
                        <p class="text-sm">Belum ada booking yang perlu diproses</p>
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
    
    @if($bookings->hasPages())
    <div class="px-6 py-4 border-t border-slate-700 bg-slate-800">
        {{ $bookings->links() }}
    </div>
    @endif
</div>

@endsection