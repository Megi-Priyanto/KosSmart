@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')

<!-- Notifikasi Check-out Pending -->
@if($activeRent->status === 'checkout_requested')
<div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="flex-1">
            <h3 class="font-bold text-yellow-800 mb-2">Permintaan checkout Anda sedang diproses admin.</h3>
        </div>
    </div>
</div>
@endif

<!-- Hero Image Tenant -->
<div class="relative mb-8 rounded-2xl overflow-hidden shadow-lg"
     style="height: clamp(220px, 35vw, 450px);">

    <img src="{{ tenant_dashboard_image() }}"
         alt="Dashboard Tenant"
         class="absolute inset-0 w-full h-full object-cover"
         style="filter: brightness(0.6);">

    <div class="relative z-10 flex flex-col items-center justify-center h-full text-white px-4 text-center">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">
            Selamat Datang
        </h1>
        <p class="text-sm sm:text-base text-gray-200 max-w-2xl">
            Semoga hari Anda menyenangkan di kos pilihan Anda
        </p>
    </div>
</div>

<!-- Social Media Section -->
<div class="flex justify-center gap-6 mt-6 mb-12">

    <!-- Instagram -->
    <a href="https://www.instagram.com/USERNAME_KAMU"
       target="_blank"
       class="flex flex-col items-center gap-2 text-sm text-gray-500 hover:text-white transition">
    
        <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:scale-105 transition"
             style="background: radial-gradient(circle at top left, #c4c4c4, #292c30);">
            <!-- Icon Instagram -->
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zm0 1.5A4.25 4.25 0 003.5 7.75v8.5A4.25 4.25 0 007.75 20.5h8.5a4.25 4.25 0 004.25-4.25v-8.5A4.25 4.25 0 0016.25 3.5h-8.5z"/>
                <path d="M12 7a5 5 0 100 10 5 5 0 000-10zm0 1.5a3.5 3.5 0 110 7 3.5 3.5 0 010-7z"/>
                <circle cx="17.5" cy="6.5" r="1"/>
            </svg>
        </div>
    
        <span>Instagram</span>
    </a>

    <!-- Twitter -->
    <a href="https://twitter.com/USERNAME_KAMU"
       target="_blank"
       class="flex flex-col items-center gap-2 text-sm text-gray-500 hover:text-white transition">
    
        <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:scale-105 transition"
             style="background: radial-gradient(circle at top left, #c4c4c4, #292c30);">
            <!-- Icon Twitter -->
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23 3a10.9 10.9 0 01-3.14 1.53A4.48 4.48 0 0016 3a4.48 4.48 0 00-4.47 4.48c0 .35.04.7.11 1.03A12.94 12.94 0 013 4s-4 9 5 13a13.07 13.07 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
            </svg>
        </div>
    
        <span>Twitter</span>
    </a>

    <!-- YouTube -->
    <a href="https://www.youtube.com/@USERNAME_KAMU"
       target="_blank"
       class="flex flex-col items-center gap-2 text-sm text-gray-500 hover:text-white transition">
    
        <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:scale-105 transition"
             style="background: radial-gradient(circle at top left, #c4c4c4, #292c30);">
            <!-- Icon YouTube -->
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.498 6.186a2.958 2.958 0 00-2.08-2.093C19.61 3.5 12 3.5 12 3.5s-7.61 0-9.418.593A2.958 2.958 0 00.502 6.186C0 8.002 0 12 0 12s0 3.998.502 5.814a2.958 2.958 0 002.08 2.093C4.39 20.5 12 20.5 12 20.5s7.61 0 9.418-.593a2.958 2.958 0 002.08-2.093C24 15.998 24 12 24 12s0-3.998-.502-5.814zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/>
            </svg>
        </div>
    
        <span>Youtube</span>
    </a>

</div>

<!-- Informasi Kamar -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:border-yellow-400 transition mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Informasi Kamar
        </h2>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Nomor Kamar</span>
                <span class="font-bold text-gray-800">{{ $activeRent->room->room_number ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Lokasi</span>
                <span class="font-medium text-gray-800">{{ $activeRent->room->floor ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Ukuran</span>
                <span class="font-medium text-gray-800">{{ $activeRent->room->size ?? '-' }} m²</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Status</span>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                    Aktif
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Tanggal Masuk</span>
                <span class="font-medium text-gray-800">{{ $activeRent->start_date->format('d M Y') ?? '-' }}</span>
            </div>
        </div>
        
        @if(is_array($activeRent->room->facilities) && count($activeRent->room->facilities) > 0)
        <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600 mb-2">Fasilitas:</p>
            <div class="flex flex-wrap gap-2">
                @foreach($activeRent->room->facilities as $facility)
                    <span class="px-3 py-1 bg-yellow-50 text-yellow-700 text-xs font-medium rounded-full">
                        {{ $facility }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Tombol Show Detail -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <a href="{{ route('user.room.detail') }}" 
               class="w-full px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Lihat Detail Kamar
            </a>
        </div>
            
    </div>
</div>

<!-- Riwayat Pembayaran -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:border-yellow-400 transition">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Riwayat Pembayaran</h2>
            <a href="{{ route('user.billing.index') }}" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium">
                Lihat Semua →
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            @forelse($paymentHistory as $payment)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-{{ $payment->status === 'confirmed' ? 'green' : 'orange' }}-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-{{ $payment->status === 'confirmed' ? 'green' : 'orange' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($payment->status === 'confirmed')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $payment->billing->month ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-{{ $payment->status === 'confirmed' ? 'green' : ($payment->status === 'rejected' ? 'red' : 'orange') }}-100 text-{{ $payment->status === 'confirmed' ? 'green' : ($payment->status === 'rejected' ? 'red' : 'orange') }}-700 text-sm font-medium rounded-full">
                        {{ ucfirst($payment->status) }}
                    </span>
                    <p class="text-xs text-gray-500 mt-1">{{ $payment->payment_date->format('d M Y') }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                Belum ada riwayat pembayaran
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection