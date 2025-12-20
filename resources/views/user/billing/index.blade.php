@extends('layouts.user')

@section('title', 'Tagihan Saya')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tagihan Saya</h1>
            <p class="text-sm text-gray-600 mt-1">Kelola tagihan dan pembayaran kos Anda</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:border-yellow-400 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Tagihan</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:border-yellow-400 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Belum Dibayar</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['unpaid'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div> 
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:border-yellow-400 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:border-yellow-400 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Lunas</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['paid'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Outstanding Amount Alert -->
    @if($stats['total_unpaid'] > 0)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <p class="font-semibold text-red-800">Total Tunggakan</p>
                <p class="text-2xl font-bold text-red-600">Rp {{ number_format($stats['total_unpaid'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 hover:border-yellow-400 transition">
        <div class="flex flex-col md:flex-row gap-3">
            <form method="GET" class="flex gap-2 flex-1">
                <select name="status" onchange="this.form.submit()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Status</option>
                    <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Terlambat</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>

                <select name="year" onchange="this.form.submit()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('user.payment.history') }}" 
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium text-center">
                Riwayat Pembayaran
            </a>
        </div>
    </div>

    <!-- Billings Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($billings as $billing)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:border-yellow-400 overflow-hidden hover:shadow-md transition">
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 {{ $billing->status === 'paid' ? 'bg-green-50' : ($billing->is_overdue ? 'bg-red-50' : 'bg-gray-50') }}">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-bold text-gray-900">{{ $billing->formatted_period }}</h3>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $billing->status_badge }}">
                        {{ $billing->status_label }}
                    </span>
                </div>
                <p class="text-sm text-gray-600">Kamar {{ $billing->room->room_number }}</p>
            </div>

            <!-- Amount -->
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-1">Total Tagihan</p>
                    <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</p>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Jatuh Tempo:</span>
                        <span class="font-medium text-gray-900">{{ $billing->due_date->format('d M Y') }}</span>
                    </div>
                    
                    @if($billing->status !== 'paid')
                        @if($billing->is_overdue)
                        <div class="flex items-center text-sm text-red-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="font-semibold">Terlambat {{ abs($billing->days_until_due) }} hari</span>
                        </div>
                        @else
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $billing->days_until_due }} hari lagi</span>
                        </div>
                        @endif
                    @else
                        <div class="flex items-center text-sm text-green-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-semibold">Lunas: {{ $billing->paid_date->format('d M Y') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <a href="{{ route('user.billing.show', $billing) }}" 
                       class="flex-1 px-4 py-2 bg-purple-600 text-white text-center rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium">
                        Lihat Detail
                    </a>
                    
                    @if($billing->status !== 'paid')
                    <a href="{{ route('user.billing.pay', $billing) }}" 
                       class="flex-1 px-4 py-2 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                        Bayar
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-lg shadow-sm border border-gray-200 hover:border-yellow-400 transition p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Tagihan</h3>
            <p class="text-sm text-gray-600">Tagihan akan muncul di sini ketika admin membuatnya</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($billings->hasPages())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:border-yellow-400 transition p-4">
        {{ $billings->links() }}
    </div>
    @endif

</div>
@endsection