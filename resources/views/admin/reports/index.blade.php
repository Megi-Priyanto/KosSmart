@extends('layouts.admin')

@section('title', 'Laporan Tagihan')
@section('page-title', 'Laporan Tagihan')
@section('page-description', 'Laporan lengkap semua tagihan dan pembayaran')

@section('content')

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Tagihan -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Tagihan</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_billings'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Lunas -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Lunas</p>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['paid_count'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Rp {{ number_format($stats['paid_amount'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Belum Dibayar -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Belum Dibayar</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['unpaid_count'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Rp {{ number_format($stats['unpaid_amount'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Terlambat -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Terlambat</p>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['overdue_count'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Rp {{ number_format($stats['overdue_amount'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-red-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Export Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <form method="GET" action="{{ route('admin.reports.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
            <!-- Start Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" 
                       name="start_date" 
                       value="{{ request('start_date') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            
            <!-- End Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" 
                       name="end_date" 
                       value="{{ request('end_date') }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            
            <!-- User -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Penyewa</label>
                <select name="user_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Kamar</label>
                <select name="room_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Semua Kamar</option>
                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                        Kamar {{ $room->room_number }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="flex items-center justify-between">
            <div class="flex space-x-2">
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.reports.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                    Reset
                </a>
            </div>
            
            <div class="flex space-x-2">
                <a href="{{ route('admin.reports.export-pdf', request()->all()) }}" 
                   class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.reports.export-excel', request()->all()) }}" 
                   class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    @if($billings->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Penyewa</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Kamar</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Periode</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Jatuh Tempo</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Total</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($billings as $billing)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">#{{ $billing->id }}</td>
                    
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $billing->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $billing->user->email }}</p>
                        </div>
                    </td>
                    
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">
                        Kamar {{ $billing->room->room_number }}
                    </td>
                    
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $billing->formatted_period }}
                    </td>
                    
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $billing->due_date->format('d M Y') }}
                        @if($billing->is_overdue)
                        <span class="block text-xs text-red-600 mt-1">
                            ({{ abs($billing->days_until_due) }} hari terlambat)
                        </span>
                        @endif
                    </td>
                    
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-sm font-bold text-gray-800">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</p>
                            @if($billing->status === 'paid' && $billing->paid_date)
                            <p class="text-xs text-gray-500">Dibayar: {{ $billing->paid_date->format('d M Y') }}</p>
                            @endif
                        </div>
                    </td>
                    
                    <td class="px-6 py-4">
                        @if($billing->status === 'paid')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Lunas
                        </span>
                        @elseif($billing->is_overdue)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Terlambat
                        </span>
                        @elseif($billing->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Pending
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                            Belum Dibayar
                        </span>
                        @endif
                    </td>
                    
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.billing.show', $billing) }}" 
                           class="text-purple-600 hover:text-purple-700 font-medium text-sm">
                            Lihat Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $billings->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="text-center py-16">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Data</h3>
        <p class="text-gray-500 mb-6">Tidak ada tagihan yang sesuai dengan filter</p>
        <a href="{{ route('admin.reports.index') }}" 
           class="inline-block bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700">
            Reset Filter
        </a>
    </div>
    @endif
</div>

@endsection