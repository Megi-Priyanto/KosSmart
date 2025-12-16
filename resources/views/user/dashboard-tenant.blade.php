@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')

<div id="dashboardCarousel"
     class="carousel slide mb-8 rounded-2xl overflow-hidden"
     data-bs-ride="carousel"
     data-bs-interval="4000">

    <!-- Indicators -->
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#dashboardCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#dashboardCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#dashboardCarousel" data-bs-slide-to="2"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">

        <!-- Slide 1 -->
        <div class="carousel-item active">
            <img src="{{ asset('images/image1.jpg') }}"
                 class="d-block w-100"
                 style="height:450px; object-fit:cover;">
            <div class="carousel-caption text-start">
                <h2 class="text-3xl font-bold">
                    Selamat Datang Kembali, {{ Auth::user()->name }}!
                </h2>
                <p>
                    Kamar {{ $activeRent->room->room_number ?? 'N/A' }}
                    • {{ $activeRent->room->floor ?? 'N/A' }}
                </p>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <img src="{{ asset('images/image2.jpg') }}"
                 class="d-block w-100"
                 style="height:450px; object-fit:cover;">
            <div class="carousel-caption text-start">
                <h2 class="text-3xl font-bold">Hunian Nyaman & Aman</h2>
                <p>Nikmati fasilitas terbaik KosSmart</p>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <img src="{{ asset('images/image3.jpg') }}"
                 class="d-block w-100"
                 style="height:450px; object-fit:cover;">
            <div class="carousel-caption text-start">
                <h2 class="text-3xl font-bold">Pembayaran Mudah</h2>
                <p>Kelola tagihan langsung dari dashboard</p>
            </div>
        </div>

    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button"
            data-bs-target="#dashboardCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button"
            data-bs-target="#dashboardCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Info Cards Row -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <!-- Informasi Kamar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            
            @if(is_array($activeRent->room->facilities))
                @foreach($activeRent->room->facilities as $facility)
                    <span class="px-3 py-1 bg-purple-50 text-purple-600 text-xs font-medium rounded-full">
                        {{ $facility }}
                    </span>
                @endforeach
            @endif
                
        </div>
    </div>
    
    <!-- Tagihan Bulan Ini -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Tagihan {{ now()->format('F Y') }}
            </h2>
        </div>
        <div class="p-6">
            @if($currentBill)
            <div class="text-center mb-6">
                <p class="text-sm text-gray-600 mb-2">Total Tagihan</p>
                <p class="text-4xl font-bold text-gray-800 mb-2">
                    Rp {{ number_format($currentBill->total_amount, 0, ',', '.') }}
                </p>
                <span class="px-3 py-1 bg-{{ $currentBill->status === 'paid' ? 'green' : 'orange' }}-100 text-{{ $currentBill->status === 'paid' ? 'green' : 'orange' }}-700 text-sm font-medium rounded-full">
                    {{ $currentBill->status === 'paid' ? 'Lunas' : 'Belum Bayar' }}
                </span>
                <p class="text-xs text-gray-500 mt-2">Jatuh tempo: {{ $currentBill->due_date->format('d M Y') }}</p>
            </div>
            
            <div class="space-y-3 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Sewa Kamar</span>
                    <span class="font-medium text-gray-800">Rp {{ number_format($currentBill->rent_amount, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-gray-200 pt-3 flex justify-between font-bold">
                    <span class="text-gray-800">Total</span>
                    <span class="text-purple-600">Rp {{ number_format($currentBill->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
            
            @if($currentBill->status !== 'paid')
            <a href="{{ route('user.billing.pay', $currentBill->id) }}" class="block w-full text-center bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 px-4 rounded-lg font-semibold hover:shadow-lg transition-all">
                Bayar Sekarang
            </a>
            @endif
            @else
            <div class="text-center py-8">
                <p class="text-gray-500">Belum ada tagihan bulan ini</p>
            </div>
            @endif
        </div>
    </div>
    
</div>

<!-- Riwayat Pembayaran -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Riwayat Pembayaran</h2>
            <a href="{{ route('user.payments') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
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