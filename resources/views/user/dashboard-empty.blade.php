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

<!-- Carousel Banner -->
<div id="dashboardCarousel"
     class="carousel slide mb-8 rounded-2xl overflow-hidden shadow-sm"
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
            <div class="relative w-100 overflow-hidden rounded-2xl"
                 style="height:clamp(220px, 35vw, 450px);">

                <!-- Background blur -->
                <img src="{{ asset('images/image1.png') }}"
                     class="absolute inset-0 w-full h-full"
                     style="object-fit:cover; filter:blur(18px) brightness(0.6);">

                <!-- Gambar utama (utuh, tidak terpotong) -->
                <img src="{{ asset('images/image1.png') }}"
                     class="relative z-10 mx-auto h-full"
                     style="object-fit:contain;">
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item">
            <div class="relative w-100 overflow-hidden rounded-2xl"
                 style="height:clamp(220px, 35vw, 450px);">

                <!-- Background blur -->
                <img src="{{ asset('images/image1.png') }}"
                     class="absolute inset-0 w-full h-full"
                     style="object-fit:cover; filter:blur(18px) brightness(0.6);">

                <!-- Gambar utama (utuh, tidak terpotong) -->
                <img src="{{ asset('images/image1.png') }}"
                     class="relative z-10 mx-auto h-full"
                     style="object-fit:contain;">
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item">
            <div class="relative w-100 overflow-hidden rounded-2xl"
                 style="height:clamp(220px, 35vw, 450px);">

                <!-- Background blur -->
                <img src="{{ asset('images/image1.png') }}"
                     class="absolute inset-0 w-full h-full"
                     style="object-fit:cover; filter:blur(18px) brightness(0.6);">

                <!-- Gambar utama (utuh, tidak terpotong) -->
                <img src="{{ asset('images/image1.png') }}"
                     class="relative z-10 mx-auto h-full"
                     style="object-fit:contain;">
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
            <!-- Icon Instagram -->
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
            <!-- Icon Instagram -->
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.498 6.186a2.958 2.958 0 00-2.08-2.093C19.61 3.5 12 3.5 12 3.5s-7.61 0-9.418.593A2.958 2.958 0 00.502 6.186C0 8.002 0 12 0 12s0 3.998.502 5.814a2.958 2.958 0 002.08 2.093C4.39 20.5 12 20.5 12 20.5s7.61 0 9.418-.593a2.958 2.958 0 002.08-2.093C24 15.998 24 12 24 12s0-3.998-.502-5.814zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/>
            </svg>
        </div>
    
        <span>Youtube</span>
    </a>

</div>

<!-- Info: Redirect ke halaman pilih kamar -->
<div class="max-w-md mx-auto bg-white rounded-xl shadow-sm border border-gray-200 hover:border-yellow-400 p-8 text-center mb-12 transition">
    <h3 class="text-xl font-bold text-gray-800 mb-3">
        Mulai Sewa Kamar
    </h3>
    <p class="text-gray-600 mb-6 text-sm">
        Lihat daftar kamar yang tersedia dan ajukan booking sekarang
    </p>
    <a href="{{ route('user.rooms.index') }}"
       class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition">
        Lihat Kamar Tersedia
    </a>
</div>

<!-- Info Box -->
<div class="mt-12 bg-yellow-50 border border-yellow-200 rounded-xl p-6">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <h3 class="font-bold text-yellow-900 mb-2">Cara Booking Kamar</h3>
            <ol class="text-sm text-yellow-800 space-y-1 list-decimal list-inside">
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