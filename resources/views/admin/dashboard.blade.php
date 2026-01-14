@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview statistik dan aktivitas sistem KosSmart')

@section('content')

<!-- Alert Booking Pending -->
@if(isset($pendingBookingsCount) && $pendingBookingsCount > 0)
<div class="mb-6 px-6 py-5
            bg-slate-800 border border-yellow-500/40
            rounded-xl shadow-sm">

    <div class="flex items-start gap-4">
        <!-- Icon -->
        <div class="p-2 bg-yellow-500/20 rounded-lg">
            <svg class="w-6 h-6 text-yellow-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856
                         c1.54 0 2.502-1.667 1.732-3
                         L13.732 4c-.77-1.333-2.694-1.333-3.464 0
                         L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <div class="flex-1">
            <!-- Title -->
            <h3 class="font-semibold text-yellow-300 mb-1">
                {{ $pendingBookingsCount }} Booking Menunggu Persetujuan
            </h3>

            <!-- Description -->
            <p class="text-sm text-slate-300 mb-4">
                Segera proses booking berikut untuk memberikan konfirmasi kepada calon penyewa.
            </p>

            <!-- Booking List -->
            @if(isset($pendingBookings) && $pendingBookings->count() > 0)
            <div class="space-y-2 mb-4">
                @foreach($pendingBookings as $booking)
                <div class="flex items-center justify-between
                            px-3 py-2 bg-slate-900
                            border border-slate-700
                            rounded-lg hover:border-yellow-400/50 transition">

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-yellow-500/20 rounded-full
                                    flex items-center justify-center">
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

                    <a href="{{ route('admin.bookings.show', $booking) }}"
                       class="text-sm font-medium text-yellow-400 hover:text-yellow-300">
                        Proses →
                    </a>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Footer Link -->
            <a href="{{ route('admin.bookings.index') }}"
               class="inline-flex items-center text-sm font-medium
                      text-yellow-400 hover:text-yellow-300">
                Lihat Semua Booking Pending
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endif

@if(isset($checkoutRequestsCount) && $checkoutRequestsCount > 0)
<div class="mb-6 px-6 py-5
            bg-slate-800 border border-orange-500/40
            rounded-xl shadow-sm">

    <div class="flex items-start gap-4">

        <!-- Icon -->
        <div class="p-2 bg-orange-500/20 rounded-lg">
            <svg class="w-6 h-6 text-orange-400"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-6h13M9 7h13M5 12h.01"/>
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
                <div class="flex items-center justify-between
                            px-3 py-2 bg-slate-900
                            border border-slate-700
                            rounded-lg hover:border-orange-400/50 transition">

                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-orange-500/20 rounded-full
                                    flex items-center justify-center">
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

                    <a href="{{ route('admin.rooms.show', $rent) }}"
                       class="text-sm font-medium text-orange-400 hover:text-orange-300">
                        Proses →
                    </a>
                </div>
                @endforeach
            </div>

            <a href="{{ route('admin.rooms.index') }}"
               class="inline-flex items-center text-sm font-medium
                      text-orange-400 hover:text-orange-300">
                Lihat Semua Checkout
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endif

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Total User -->
    <a href="{{ route('admin.users.index') }}" 
       class="bg-slate-800 p-6 rounded-lg border border-slate-700
            transition-all duration-300 ease-out
            hover:-translate-y-1 hover:shadow-lg hover:border-blue-600 hover:bg-slate-700">

        <div class="flex items-center justify-between">

            <div>
                <!-- Judul -->
                <p class="text-gray-300 text-sm font-medium">
                    Total User
                </p>

                <!-- Angka utama -->
                <p class="text-3xl font-bold text-white mt-2">
                    {{ $totalUsers ?? 24 }}
                </p>

                <!-- Info tambahan -->
                <p class="text-sm text-green-400 mt-1">
                    ↑ +{{ $newUsersThisMonth }} bulan ini
                </p>
            </div>

            <!-- Icon -->
            <div class="bg-blue-500/20 p-3 rounded-lg">
                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>

        </div>
    </a>

    <!-- Total Kamar -->
    <a href="{{ route('admin.kos.index') }}" 
       class="bg-slate-800 p-6 rounded-lg border border-slate-700
            transition-all duration-300 ease-out
            hover:-translate-y-1 hover:shadow-lg hover:border-purple-600 hover:bg-slate-700">
        
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-300 text-sm font-medium">
                    Total Kamar
                </p>
                <p class="text-3xl font-bold text-white mt-2">
                    {{ $totalRooms ?? 30 }}
                </p>
                <p class="text-sm text-gray-400 mt-1">
                    {{ $occupiedRooms ?? 24 }} terisi
                </p>
            </div>
        
            <div class="bg-purple-500/20 p-3 rounded-lg">
                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
    </a>
    
    <!-- Pendapatan -->
    <a href="{{ route('admin.reports.index') }}" 
       class="bg-slate-800 p-6 rounded-lg border border-slate-700
            transition-all duration-300 ease-out
            hover:-translate-y-1 hover:shadow-lg hover:border-green-600 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-300 text-sm font-medium">
                    Pendapatan
                </p>
                <p class="text-3xl font-bold text-white mt-2">
                    Rp {{ number_format($monthlyIncome ?? 36000000) }}
                </p>
                <p class="text-sm text-green-400 mt-1">
                    ↑ {{ $incomeChangePercent }}% dari bulan lalu
                </p>
            </div>

            <div class="bg-green-500/20 p-3 rounded-lg">
                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </a>

    <!-- Tagihan Pending -->
    <a href="{{ route('admin.billing.index') }}" 
       class="bg-slate-800 p-6 rounded-lg border border-slate-700
            transition-all duration-300 ease-out
            hover:-translate-y-1 hover:shadow-lg hover:border-orange-600 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-300 text-sm font-medium">
                    Tagihan Pending
                </p>

                <p class="text-3xl font-bold text-white mt-2">
                    {{ $pendingBills }}
                </p>

                @if ($pendingGrowth > 0)
                    <p class="text-sm text-red-400 mt-1">
                        ↑ {{ $pendingGrowth }}% lebih banyak dari bulan lalu
                    </p>
                @elseif ($pendingGrowth < 0)
                    <p class="text-sm text-green-400 mt-1">
                        ↓ {{ abs($pendingGrowth) }}% lebih sedikit dari bulan lalu
                    </p>
                @else
                    <p class="text-sm text-gray-400 mt-1">
                        Tidak ada perubahan dari bulan lalu
                    </p>
                @endif

                <p class="text-xs text-gray-500 mt-1">
                    Bulan ini: {{ $pendingBillsThisMonth }} |
                    Bulan lalu: {{ $pendingBillsLastMonth }}
                </p>
            </div>

            <div class="bg-orange-500/20 p-3 rounded-lg">
                <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </a>

</div>

<!-- Charts & Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <!-- Recent Users -->
    <div class="bg-slate-800 rounded-xl border border-slate-700">
    
        <!-- Header -->
        <div class="p-6 border-b border-slate-700">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-white">
                    User Terbaru
                </h2>
                <a href="{{ route('admin.users.index') }}"
                   class="text-sm text-yellow-400 hover:text-yellow-300 font-medium">
                    Lihat Semua →
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6">
            <div class="space-y-4">
                @forelse($recentUsers ?? [] as $user)
                    <div class="flex items-center justify-between 
                                hover:bg-slate-700/50 p-2 rounded-lg transition">

                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-slate-700 rounded-full 
                                        flex items-center justify-center">
                                <span class="text-yellow-400 font-semibold">
                                    {{ substr($user->name, 0, 2) }}
                                </span>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-white">
                                    {{ $user->name }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $user->email }}
                                </p>
                            </div>
                        </div>

                        <span class="text-xs text-gray-500">
                            {{ $user->created_at->diffForHumans() }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p class="text-gray-400">
                            Belum ada user terbaru
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="bg-slate-800 rounded-xl border border-slate-700">
    
        <!-- Header -->
        <div class="p-6 border-b border-slate-700">
            <h2 class="text-lg font-bold text-white">
                Aktivitas Terbaru
            </h2>
        </div>

        <!-- Body -->
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

                    <div class="flex items-start space-x-3 
                                hover:bg-slate-700/50 p-2 rounded-lg transition">

                        <div class="w-8 h-8 bg-{{ $iconColor }}-500/20 rounded-full 
                                    flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-{{ $iconColor }}-400"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                        <div class="flex-1">
                            <p class="text-sm text-white">
                                {!! $activity->message !!}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                @empty
                    <p class="text-gray-400 text-center py-4">
                        Belum ada aktivitas terbaru
                    </p>
                @endforelse

            </div>
        </div>
    </div>

</div>

<!-- Quick Actions -->
<div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
    <h2 class="text-lg font-bold text-white mb-6">
        Aksi Cepat
    </h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <!-- Tambah User -->
        <a href="{{ route('admin.users.create') }}"
           class="flex flex-col items-center p-6 bg-slate-900
                  border border-slate-700 rounded-lg
                  hover:bg-slate-700 hover:border-yellow-400
                  transition-all group">
            <svg class="w-10 h-10 text-gray-400 group-hover:text-yellow-400 transition mb-3"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            <p class="text-sm font-medium text-white text-center">Tambah User</p>
        </a>

        <!-- Tambah Kamar -->
        <a href="{{ route('admin.kos.create') }}"
           class="flex flex-col items-center p-6 bg-slate-900
                  border border-slate-700 rounded-lg
                  hover:bg-slate-700 hover:border-yellow-400
                  transition-all group">
            <svg class="w-10 h-10 text-gray-400 group-hover:text-yellow-400 transition mb-3"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            <p class="text-sm font-medium text-white text-center">Tambah Kamar</p>
        </a>

        <!-- Buat Tagihan -->
        <a href="{{ route('admin.billing.create') }}"
           class="flex flex-col items-center p-6 bg-slate-900
                  border border-slate-700 rounded-lg
                  hover:bg-slate-700 hover:border-yellow-400
                  transition-all group">
            <svg class="w-10 h-10 text-gray-400 group-hover:text-yellow-400 transition mb-3"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
            </svg>
            <p class="text-sm font-medium text-white text-center">Buat Tagihan</p>
        </a>

        <!-- Lihat Laporan -->
        <a href="{{ route('admin.reports.index') }}"
           class="flex flex-col items-center p-6 bg-slate-900
                  border border-slate-700 rounded-lg
                  hover:bg-slate-700 hover:border-yellow-400
                  transition-all group">
            <svg class="w-10 h-10 text-gray-400 group-hover:text-yellow-400 transition mb-3"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5"/>
            </svg>
            <p class="text-sm font-medium text-white text-center">Lihat Laporan</p>
        </a>

    </div>
</div>

@endsection