@extends('layouts.admin')

@section('title', 'Kelola Booking')
@section('page-title', 'Kelola Booking')
@section('page-description', 'Manajemen booking dan persetujuan penyewaan kamar')

@section('content')

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total Booking</p>
                <p class="text-2xl font-bold text-gray-800">{{ $bookings->total() }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Menunggu Approval</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $pendingCount }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Booking Aktif</p>
                <p class="text-2xl font-bold text-green-600">
                    {{ \App\Models\Rent::where('status', 'active')->count() }}
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Dibatalkan</p>
                <p class="text-2xl font-bold text-red-600">
                    {{ \App\Models\Rent::where('status', 'cancelled')->count() }}
                </p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <form method="GET" class="flex gap-3">
        <select name="status" class="border border-gray-300 rounded-lg px-4 py-2">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
        </select>
        
        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
            Filter
        </button>
        
        @if(request()->has('status'))
        <a href="{{ route('admin.bookings.index') }}" 
           class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
            Reset
        </a>
        @endif
    </form>
</div>

<!-- Bookings Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Booking</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penyewa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kamar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mulai Sewa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50 {{ $booking->status == 'pending' ? 'bg-yellow-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $booking->created_at->format('d M Y, H:i') }}
                        <p class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->user->email }}</p>
                            <p class="text-xs text-gray-500">📞 {{ $booking->user->phone ?? '-' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Kamar {{ $booking->room->room_number }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->room->floor }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $booking->start_date->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp {{ number_format($booking->deposit_paid, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            @if($booking->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($booking->status == 'active') bg-green-100 text-green-700
                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('admin.bookings.show', $booking) }}" 
                           class="inline-flex items-center px-3 py-1 bg-purple-600 text-white rounded hover:bg-purple-700">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $bookings->links() }}
    </div>
    @endif
</div>

@endsection