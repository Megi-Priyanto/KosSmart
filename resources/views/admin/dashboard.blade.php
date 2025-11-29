@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview statistik dan aktivitas sistem KosSmart')

@section('content')

<!-- Alert Booking Pending -->
@if(isset($pendingBookingsCount) && $pendingBookingsCount > 0)
<div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <div class="flex-1">
            <h3 class="font-bold text-yellow-800 mb-2">⚠️ Ada {{ $pendingBookingsCount }} Booking Menunggu Persetujuan!</h3>
            <p class="text-sm text-yellow-700 mb-3">
                Segera proses booking berikut untuk memberikan konfirmasi kepada calon penyewa.
            </p>
            
            @if(isset($pendingBookings) && $pendingBookings->count() > 0)
            <div class="space-y-2 mb-3">
                @foreach($pendingBookings as $booking)
                <div class="flex items-center justify-between p-2 bg-white rounded">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <span class="text-xs font-bold text-yellow-700">{{ $booking->room->room_number }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $booking->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                       class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                        Proses →
                    </a>
                </div>
                @endforeach
            </div>
            @endif
            
            <a href="{{ route('admin.bookings.index') }}" 
               class="inline-flex items-center text-sm font-medium text-yellow-700 hover:text-yellow-900">
                Lihat Semua Booking Pending
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
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
       class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 block hover:bg-gray-50 transition cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total User</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalUsers ?? 24 }}</p>
                <p class="text-sm text-green-600 mt-1">↑ +3 bulan ini</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
        </div>
    </a>

    <!-- Total Kamar -->
    <a href="{{ route('admin.kos.index') }}" 
       class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 block hover:bg-gray-50 transition cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Kamar</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalRooms ?? 30 }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $occupiedRooms ?? 24 }} terisi</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
        </div>
    </a>

    <!-- Pendapatan -->
    <a href="{{ route('admin.reports.index') }}" 
       class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 block hover:bg-gray-50 transition cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Pendapatan</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">Rp {{ number_format($monthlyIncome ?? 36000000) }}</p>
                <p class="text-sm text-green-600 mt-1">↑ +12% dari bulan lalu</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
        </div>
    </a>

    <!-- Tagihan Pending -->
    <a href="{{ route('admin.billing.index') }}" 
       class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 block hover:bg-gray-50 transition cursor-pointer">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tagihan Pending</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pendingBills ?? 5 }}</p>
                <p class="text-sm text-orange-600 mt-1">⚠ Perlu follow up</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
        </div>
    </a>

</div>

<!-- Charts & Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <!-- Recent Users -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">User Terbaru</h2>
                <a href="#" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                    Lihat Semua →
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($recentUsers ?? [] as $user)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-600 font-semibold">{{ substr($user->name, 0, 2) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="text-gray-500">Belum ada user terbaru</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">Aktivitas Terbaru</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <!-- Sample Activities -->
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800"><span class="font-semibold">John Doe</span> telah membayar tagihan bulan Januari</p>
                        <p class="text-xs text-gray-500 mt-1">2 jam yang lalu</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">User baru <span class="font-semibold">Jane Smith</span> bergabung</p>
                        <p class="text-xs text-gray-500 mt-1">5 jam yang lalu</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-800">Kamar 101 berhasil ditambahkan</p>
                        <p class="text-xs text-gray-500 mt-1">1 hari yang lalu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-6">Aksi Cepat</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.users.create') }}" 
           class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-all group">
            <svg class="w-10 h-10 text-gray-600 group-hover:text-purple-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
            <p class="text-sm font-medium text-gray-700 text-center">Tambah User</p>
        </a>
        
        <a href="{{ route('admin.kos.create') }}" 
           class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-all group">
            <svg class="w-10 h-10 text-gray-600 group-hover:text-purple-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <p class="text-sm font-medium text-gray-700 text-center">Tambah Kamar</p>
        </a>
        
        <a href="{{ route('admin.billing.create') }}" 
           class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-all group">
            <svg class="w-10 h-10 text-gray-600 group-hover:text-purple-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <p class="text-sm font-medium text-gray-700 text-center">Buat Tagihan</p>
        </a>
        
        <a href="{{ route('admin.reports.index') }}" 
           class="flex flex-col items-center p-6 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-all group">
            <svg class="w-10 h-10 text-gray-600 group-hover:text-purple-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-sm font-medium text-gray-700 text-center">Lihat Laporan</p>
        </a>
    </div>
</div>

@endsection