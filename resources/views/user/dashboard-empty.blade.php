@extends('layouts.user')

@section('title', 'Pilih Kamar')

@section('content')

<!-- Notifikasi Booking Pending -->
@if(isset($pendingRent))
<div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="flex-1">
            <h3 class="font-bold text-yellow-800 mb-2">Booking Anda Sedang Diproses</h3>
            <p class="text-sm text-yellow-700 mb-3">
                Booking kamar <strong>{{ $pendingRent->room->room_number }}</strong> sedang ditinjau oleh admin. 
                Anda akan dihubungi dalam 1x24 jam.
            </p>
            <div class="flex gap-3">
                <a href="{{ route('user.booking.status') }}" 
                   class="inline-flex items-center text-sm font-medium text-yellow-700 hover:text-yellow-900">
                    Lihat Status Booking
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Welcome Banner -->
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

    <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
            <img src="{{ asset('images/image1.jpg') }}"
                 class="d-block w-100"
                 style="height:420px; object-fit:cover;">
            <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-center">
                <h1 class="text-4xl font-bold mb-3">
                    Selamat Datang di KosSmart!
                </h1>
                <p class="text-lg text-purple-100">
                    Anda belum menyewa kamar
                </p>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <img src="{{ asset('images/image2.jpg') }}"
                 class="d-block w-100"
                 style="height:420px; object-fit:cover;">
            <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-center">
                <h2 class="text-3xl font-bold mb-2">
                    Pilih Kamar Sesuai Kebutuhan
                </h2>
                <p class="text-purple-100">
                    Kamar nyaman, aman, dan strategis
                </p>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <img src="{{ asset('images/image3.jpg') }}"
                 class="d-block w-100"
                 style="height:420px; object-fit:cover;">
            <div class="carousel-caption d-flex flex-column justify-content-center h-100 text-center">
                <h2 class="text-3xl font-bold mb-2">
                    Booking Mudah & Cepat
                </h2>
                <p class="text-purple-100">
                    Cukup beberapa langkah untuk mulai tinggal
                </p>
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

<!-- Info: Redirect ke halaman pilih kamar -->
<div class="max-w-md mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center mb-12">
    <h3 class="text-xl font-bold text-gray-800 mb-3">
        Mulai Sewa Kamar
    </h3>
    <p class="text-gray-600 mb-6 text-sm">
        Lihat daftar kamar yang tersedia dan ajukan booking sekarang
    </p>
    <a href="{{ route('user.rooms.index') }}"
       class="inline-block bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition">
        Lihat Kamar Tersedia
    </a>
</div>

<!-- Info Box -->
<div class="mt-12 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <h3 class="font-bold text-blue-900 mb-2">Cara Booking Kamar</h3>
            <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                <li>Pilih kamar yang Anda inginkan</li>
                <li>Isi form booking dengan lengkap</li>
                <li>Upload bukti transfer DP (50% dari harga sewa)</li>
                <li>Tunggu konfirmasi dari admin (maksimal 1x24 jam)</li>
                <li>Setelah disetujui, Anda dapat langsung menempati kamar</li>
            </ol>
        </div>
    </div>
</div>

@endsection