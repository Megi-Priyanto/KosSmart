@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview statistik dan aktivitas sistem KosSmart')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')

{{--
    =====================================================================
    PERBAIKAN UTAMA:
    1. Alert Booking Pending: Hanya tampil jika TIDAK ada cancel booking pending
       untuk rent yang sama. Ini sudah dihandle di controller dengan whereNotIn,
       sehingga $pendingBookingsCount dan $pendingBookings sudah bersih.
    2. Alert Cancel Booking: Menggunakan variable dari controller ($cancelBookingsPending
       dan $cancelBookingsCount) — TIDAK lagi query ulang di blade untuk menghindari
       duplikasi dan inkonsistensi data (terutama untuk SuperAdmin).
    =====================================================================
--}}

<!-- Alert Booking Pending -->
{{-- PERBAIKAN: Variable $pendingBookingsCount dan $pendingBookings sudah difilter
     di controller, sehingga rent yang sudah ada cancel booking pending-nya
     tidak akan muncul di sini --}}
@if(isset($pendingBookingsCount) && $pendingBookingsCount > 0)
<div class="mb-6 px-6 py-5 bg-slate-800 border border-yellow-500/40 rounded-xl shadow-sm">
    <div class="flex items-start gap-4">
        <div class="p-2 bg-yellow-500/20 rounded-lg">
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <h3 class="font-semibold text-yellow-300 mb-1">
                {{ $pendingBookingsCount }} Booking Menunggu Persetujuan
            </h3>
            <p class="text-sm text-slate-300 mb-4">
                Segera proses booking berikut untuk memberikan konfirmasi kepada calon penyewa.
            </p>
            @if(isset($pendingBookings) && $pendingBookings->count() > 0)
            <div class="space-y-2 mb-4">
                @foreach($pendingBookings as $booking)
                <div class="flex items-center justify-between px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg hover:border-yellow-400/50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-yellow-500/20 rounded-full flex items-center justify-center">
                            <span class="text-xs font-bold text-yellow-300">
                                {{ $booking->room->room_number }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">
                                {{ $booking->user->name }}
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ $booking->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-sm font-medium text-yellow-400 hover:text-yellow-300">
                        Proses →
                    </a>
                </div>
                @endforeach
            </div>
            @endif
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center text-sm font-medium text-yellow-400 hover:text-yellow-300">
                Lihat Semua Booking Pending
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endif

<!-- Alert Cancel Booking -->
@if(isset($cancelBookingsCount) && $cancelBookingsCount > 0)
<div class="mb-6 px-6 py-5 bg-slate-800 border border-red-500/40 rounded-xl shadow-sm">
    <div class="flex items-start gap-4">
        <div class="p-2 bg-red-500/20 rounded-lg">
            <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div class="flex-1">
            <h3 class="font-semibold text-red-300 mb-1">
                {{ $cancelBookingsCount }} Permintaan Pembatalan Booking
            </h3>
            <p class="text-sm text-slate-300 mb-4">
                User mengajukan pembatalan booking dan menunggu persetujuan Anda untuk proses pengembalian dana DP.
            </p>
            @if(isset($cancelBookingsPending) && $cancelBookingsPending->count() > 0)
            <div class="space-y-2 mb-4">
                @foreach($cancelBookingsPending as $cancel)
                <div class="flex items-center justify-between px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg hover:border-red-400/50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-red-500/20 rounded-full flex items-center justify-center">
                            <span class="text-xs font-bold text-red-300">
                                {{ $cancel->rent->room->room_number ?? '-' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">{{ $cancel->user->name }}</p>
                            <p class="text-xs text-slate-400">
                                DP: Rp {{ number_format($cancel->rent->deposit_paid ?? 0, 0, ',', '.') }}
                                • {{ $cancel->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.cancel-bookings.show', $cancel) }}"
                       class="text-sm font-medium text-red-400 hover:text-red-300">
                        Proses →
                    </a>
                </div>
                @endforeach
            </div>
            @endif
            <a href="{{ route('admin.cancel-bookings.index') }}"
               class="inline-flex items-center text-sm font-medium text-red-400 hover:text-red-300">
                Lihat Semua Pembatalan
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endif

<!-- Alert Checkout Request -->
@if(isset($checkoutRequestsCount) && $checkoutRequestsCount > 0)
<div class="mb-6 px-6 py-5 bg-slate-800 border border-orange-500/40 rounded-xl shadow-sm">
    <div class="flex items-start gap-4">
        <div class="p-2 bg-orange-500/20 rounded-lg">
            <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
        </div>
        <div class="flex-1">
            <h3 class="font-semibold text-orange-300 mb-1">
                {{ $checkoutRequestsCount }} Permintaan Checkout
            </h3>
            <p class="text-sm text-slate-300 mb-4">
                User mengajukan checkout dan menunggu persetujuan Anda.
            </p>
            <div class="space-y-2 mb-4">
                @foreach($checkoutRequests as $rent)
                <div class="flex items-center justify-between px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg hover:border-orange-400/50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-orange-500/20 rounded-full flex items-center justify-center">
                            <span class="text-xs font-bold text-orange-300">
                                {{ $rent->room->room_number }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">
                                {{ $rent->user->name }}
                            </p>
                            <p class="text-xs text-slate-400">
                                Checkout diajukan {{ $rent->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.rooms.show', $rent->room->id) }}" class="text-sm font-medium text-orange-400 hover:text-orange-300">
                        Proses →
                    </a>
                </div>
                @endforeach
            </div>
            <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center text-sm font-medium text-orange-400 hover:text-orange-300">
                Lihat Semua Checkout
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endif

<!-- Alert Pembayaran Pending -->
@if(isset($pendingPaymentsCount) && $pendingPaymentsCount > 0)
<div class="mb-6 px-6 py-5 bg-slate-800 border border-blue-500/40 rounded-xl shadow-sm">
    <div class="flex items-start gap-4">
        <div class="p-2 bg-blue-500/20 rounded-lg">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <h3 class="font-semibold text-blue-300 mb-1">
                {{ $pendingPaymentsCount }} Pembayaran Menunggu Verifikasi
            </h3>
            <p class="text-sm text-slate-300 mb-4">
                Terdapat pembayaran dari penyewa yang menunggu verifikasi Anda.
            </p>
            <div class="space-y-2 mb-4">
                @foreach($pendingPayments as $payment)
                <div class="flex items-center justify-between px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg hover:border-blue-400/50 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <span class="text-xs font-bold text-blue-300">
                                {{ $payment->billing->room->room_number }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">
                                {{ $payment->user->name }}
                            </p>
                            <p class="text-xs text-slate-400">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }} • {{ $payment->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.billing.show', $payment->billing_id) }}" class="text-sm font-medium text-blue-400 hover:text-blue-300">
                        Proses →
                    </a>
                </div>
                @endforeach
            </div>
            <a href="{{ route('admin.billing.index') }}" class="inline-flex items-center text-sm font-medium text-blue-400 hover:text-blue-300">
                Lihat Semua Pembayaran
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endif

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

    <!-- Total Kamar -->
    <a href="{{ route('admin.rooms.index') }}" class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-purple-600 hover:bg-slate-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-300 text-sm font-medium">Total Kamar</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $totalRooms ?? 0 }}</p>
                <p class="text-sm text-gray-400 mt-1">{{ $occupiedRooms ?? 0 }} terisi · {{ $availableRooms ?? 0 }} tersedia</p>
            </div>
            <div class="bg-purple-500/20 p-3 rounded-lg">
                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
    </a>

    <!-- Pendapatan Bulanan -->
    <a href="{{ route('admin.reports.index') }}" class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-green-600 hover:bg-slate-700">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <p class="text-gray-300 text-sm font-medium">Pendapatan Bulanan</p>
                <p class="text-3xl font-bold text-white mt-2">Rp {{ number_format($monthlyIncome ?? 0, 0, ',', '.') }}</p>

                @if($incomeChangePercent > 0)
                    <p class="text-sm text-green-400 mt-1">↑ {{ $incomeChangePercent }}% dari bulan lalu</p>
                @elseif($incomeChangePercent < 0)
                    <p class="text-sm text-red-400 mt-1">↓ {{ abs($incomeChangePercent) }}% dari bulan lalu</p>
                @else
                    <p class="text-sm text-gray-400 mt-1">Tidak ada perubahan</p>
                @endif

                @if(($holdingAmount ?? 0) > 0)
                <p class="text-xs text-amber-400 mt-1.5">
                    🕐 Rp {{ number_format($holdingAmount, 0, ',', '.') }} menunggu pencairan
                </p>
                @endif
            </div>
            <div class="bg-green-500/20 p-3 rounded-lg flex-shrink-0 ml-2">
                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </a>

    <!-- Pendapatan Tahunan -->
    <a href="{{ route('admin.reports.index') }}" class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-blue-600 hover:bg-slate-700">
        <div class="flex items-center justify-between">
            <div class="flex-1 min-w-0">
                <p class="text-gray-300 text-sm font-medium">Pendapatan Tahunan</p>
                <p class="text-3xl font-bold text-white mt-2">Rp {{ number_format($yearlyIncome ?? 0, 0, ',', '.') }}</p>

                @if($yearlyIncomeChangePercent > 0)
                    <p class="text-sm text-green-400 mt-1">↑ {{ $yearlyIncomeChangePercent }}% dari tahun lalu</p>
                @elseif($yearlyIncomeChangePercent < 0)
                    <p class="text-sm text-red-400 mt-1">↓ {{ abs($yearlyIncomeChangePercent) }}% dari tahun lalu</p>
                @else
                    <p class="text-sm text-gray-400 mt-1">Tidak ada perubahan</p>
                @endif

                <p class="text-xs text-slate-500 mt-1">Dana setelah dipotong fee platform</p>
            </div>
            <div class="bg-blue-500/20 p-3 rounded-lg flex-shrink-0 ml-2">
                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
    </a>

    <!-- Tagihan Pending -->
    <a href="{{ route('admin.billing.index') }}" class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-orange-600 hover:bg-slate-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-300 text-sm font-medium">Tagihan Pending</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $pendingBills ?? 0 }}</p>
                @if ($pendingGrowth > 0)
                    <p class="text-sm text-red-400 mt-1">↑ {{ $pendingGrowth }}% lebih banyak</p>
                @elseif ($pendingGrowth < 0)
                    <p class="text-sm text-green-400 mt-1">↓ {{ abs($pendingGrowth) }}% lebih sedikit</p>
                @else
                    <p class="text-sm text-gray-400 mt-1">Tidak ada perubahan</p>
                @endif
            </div>
            <div class="bg-orange-500/20 p-3 rounded-lg">
                <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </a>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Revenue Trend Chart -->
    <div class="bg-slate-800 rounded-xl border border-slate-700">
        <div class="p-6 border-b border-slate-700">
            <h2 class="text-lg font-bold text-white">Tren Pendapatan 6 Bulan Terakhir</h2>
        </div>
        <div class="p-6">
            <canvas id="revenueChart" height="250"></canvas>
        </div>
    </div>

    <!-- Room Occupancy Chart -->
    <div class="bg-slate-800 rounded-xl border border-slate-700">
        <div class="p-6 border-b border-slate-700">
            <h2 class="text-lg font-bold text-white">Status Hunian Kamar</h2>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-center">
                <canvas id="roomChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Payment Status & Activities Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Payment Status Chart -->
    <div class="bg-slate-800 rounded-xl border border-slate-700">
        <div class="p-6 border-b border-slate-700">
            <h2 class="text-lg font-bold text-white">Status Pembayaran</h2>
        </div>
        <div class="p-6">
            <canvas id="paymentChart" height="250"></canvas>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-slate-800 rounded-xl border border-slate-700">
        <div class="p-6 border-b border-slate-700">
            <h2 class="text-lg font-bold text-white">Aktivitas Terbaru</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($activities as $activity)
                    @php
                        $iconColor = [
                            'payment' => 'green',
                            'user' => 'blue',
                            'room' => 'purple',
                            'booking' => 'yellow'
                        ][$activity->type] ?? 'gray';
                    @endphp
                    <div class="flex items-start space-x-3 hover:bg-slate-700/50 p-2 rounded-lg transition">
                        <div class="w-8 h-8 bg-{{ $iconColor }}-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-{{ $iconColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-white">{!! $activity->message !!}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-400">Belum ada aktivitas terbaru</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
    <h2 class="text-lg font-bold text-white mb-6">Aksi Cepat</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Tambah Kamar -->
        <a href="{{ route('admin.rooms.create') }}" class="flex flex-col items-center p-6 bg-slate-900 border border-slate-700 rounded-lg hover:bg-slate-700 hover:border-yellow-400 transition-all group">
            <svg class="w-10 h-10 text-gray-400 group-hover:text-yellow-400 transition mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <p class="text-sm font-medium text-white text-center">Tambah Kamar</p>
        </a>

        <!-- Kelola Kamar -->
        <a href="{{ route('admin.rooms.index') }}" class="flex flex-col items-center p-6 bg-slate-900 border border-slate-700 rounded-lg hover:bg-slate-700 hover:border-yellow-400 transition-all group">
            <svg class="w-10 h-10 text-gray-400 group-hover:text-yellow-400 transition mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <p class="text-sm font-medium text-white text-center">Kelola Kamar</p>
        </a>

        <!-- Buat Tagihan -->
        <a href="{{ route('admin.billing.create') }}" class="flex flex-col items-center p-6 bg-slate-900 border border-slate-700 rounded-lg hover:bg-slate-700 hover:border-yellow-400 transition-all group">
            <svg class="w-10 h-10 text-gray-400 group-hover:text-yellow-400 transition mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
            </svg>
            <p class="text-sm font-medium text-white text-center">Buat Tagihan</p>
        </a>

        <!-- Lihat Laporan -->
        <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center p-6 bg-slate-900 border border-slate-700 rounded-lg hover:bg-slate-700 hover:border-yellow-400 transition-all group">
            <svg class="w-10 h-10 text-gray-400 group-hover:text-yellow-400 transition mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5"/>
            </svg>
            <p class="text-sm font-medium text-white text-center">Lihat Laporan</p>
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Trend Chart - Line Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');

    const monthlyRevenueLabels = {!! json_encode($monthlyRevenueLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!};
    const monthlyRevenueData = {!! json_encode($monthlyRevenueData ?? [15, 22, 18, 28, 32, 36]) !!};
    const paymentStatusData = {!! json_encode($paymentStatusData ?? [45, 12, 8, 5]) !!};

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: monthlyRevenueLabels,
            datasets: [{
                label: 'Pendapatan (Juta Rupiah)',
                data: monthlyRevenueData,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3,
                pointRadius: 5,
                pointBackgroundColor: 'rgb(34, 197, 94)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#fff',
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148, 163, 184, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11 }
                    }
                }
            }
        }
    });

    // Room Occupancy Chart - Doughnut Chart
    const roomCtx = document.getElementById('roomChart').getContext('2d');
    new Chart(roomCtx, {
        type: 'doughnut',
        data: {
            labels: ['Terisi', 'Tersedia', 'Maintenance'],
            datasets: [{
                data: [
                    {{ $occupiedRooms ?? 24 }},
                    {{ $availableRooms ?? 5 }},
                    {{ $maintenanceRooms ?? 1 }}
                ],
                backgroundColor: [
                    'rgb(168, 85, 247)',
                    'rgb(59, 130, 246)',
                    'rgb(251, 146, 60)'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#fff',
                        padding: 15,
                        font: { size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderWidth: 1
                }
            },
            cutout: '65%'
        }
    });

    // Payment Status Chart - Bar Chart
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    new Chart(paymentCtx, {
        type: 'bar',
        data: {
            labels: ['Lunas', 'Belum Bayar', 'Terlambat', 'Pending'],
            datasets: [{
                label: 'Jumlah Tagihan',
                data: paymentStatusData,
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(251, 146, 60, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(234, 179, 8, 0.8)'
                ],
                borderColor: [
                    'rgb(34, 197, 94)',
                    'rgb(251, 146, 60)',
                    'rgb(239, 68, 68)',
                    'rgb(234, 179, 8)'
                ],
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148, 163, 184, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11 },
                        stepSize: 5
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11 }
                    }
                }
            }
        }
    });
});
</script>
@endpush