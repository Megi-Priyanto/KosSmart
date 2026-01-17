@extends('layouts.user')

@section('title', 'Pilih Tempat Kos')

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
            <p class="text-sm text-yellow-700 mb-2">
                Booking kamar <strong>{{ $pendingRent->room->room_number }}</strong> sedang ditinjau oleh admin. 
                Anda akan dihubungi dalam 1x24 jam.
            </p>
        </div>
    </div>
</div>
@endif

<!-- Hero Section -->
<div class="relative mb-6 rounded-2xl overflow-hidden shadow-lg" style="height: 240px;">
    <img src="{{ asset('images/image1.png') }}" 
         alt="Hero" 
         class="absolute inset-0 w-full h-full object-cover"
         style="filter: brightness(0.6);">
    
    <div class="relative z-10 flex flex-col items-center justify-center h-full text-white px-4">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2 font-bold mb-3 text-center">
            Temukan Kos Impian Anda
        </h1>
        <p class="text-sm sm:text-base text-gray-200 text-center max-w-2xl">
            Pilih dari {{ $tempatKos->total() }} tempat kos terbaik yang tersedia
        </p>
    </div>
</div>

<!-- Filter & Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 mb-8">
    <form method="GET" action="{{ route('user.dashboard') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        <!-- Search -->
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Cari nama kos, kota, alamat..." 
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
        </div>

        <!-- Filter Kota -->
        <select name="kota" 
                class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            <option value="">Semua Kota</option>
            @foreach($kotaList as $kota)
                <option value="{{ $kota }}" {{ request('kota') === $kota ? 'selected' : '' }}>
                    {{ $kota }}
                </option>
            @endforeach
        </select>

        <!-- Button -->
        <div class="flex gap-2">
            <button type="submit" 
                    class="w-full px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari
            </button>
            
            @if(request()->hasAny(['search', 'kota']))
                <a href="{{ route('user.dashboard') }}" 
                   class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Daftar Tempat Kos -->
@if($tempatKos->count() > 0)
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Pilih Tempat Kos</h2>
        <p class="text-gray-600">{{ $tempatKos->total() }} tempat kos tersedia</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
        @foreach($tempatKos as $kos)
            <a href="{{ route('user.rooms.index', ['tempat_kos_id' => $kos->id]) }}" 
               class="group bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg hover:border-yellow-400 transition-all duration-300">
                
                <!-- Gambar -->
                <div class="relative h-36 sm:h-40 bg-gray-200 overflow-hidden">
                    @if($kos->logo)
                        <img src="{{ asset('storage/' . $kos->logo) }}" 
                             alt="{{ $kos->nama_kos }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="flex items-center justify-center w-full h-full
                                    bg-gradient-to-br from-yellow-400 to-orange-500">
                            <svg class="w-16 h-16 text-white opacity-50"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Badge Status -->
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white shadow-lg">
                            <span class="w-2 h-2 bg-white rounded-full mr-1.5 animate-pulse"></span>
                            Aktif
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-3 sm:p-4">
                    <!-- Nama Kos -->
                    <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-1 group-hover:text-yellow-600 transition line-clamp-1">
                        {{ $kos->nama_kos }}
                    </h3>

                    <!-- Lokasi -->
                    <div class="flex items-start text-sm text-gray-600 mb-3">
                        <svg class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="line-clamp-2">{{ $kos->kota }}, {{ $kos->provinsi }}</span>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-yellow-500 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="font-medium text-gray-700">{{ $kos->total_kamar }} Kamar</span>
                        </div>

                        @if($kos->kamar_tersedia > 0)
                            <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                                {{ $kos->kamar_tersedia }} Tersedia
                            </span>
                        @else
                            <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">
                                Penuh
                            </span>
                        @endif
                    </div>

                    <!-- Button -->
                    <button class="mt-3 w-full py-2 text-sm bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
                        Lihat Kamar
                        <svg class="inline-block w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $tempatKos->links() }}
    </div>

@else
    <!-- Empty State -->
    <div class="text-center py-10 sm:py-12">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-1">Tidak Ada Tempat Kos</h3>
        <p class="text-sm text-gray-500 mb-4">
            @if(request()->hasAny(['search', 'kota']))
                Tidak ditemukan hasil untuk pencarian Anda. Coba kata kunci lain.
            @else
                Belum ada tempat kos yang tersedia saat ini.
            @endif
        </p>
        @if(request()->hasAny(['search', 'kota']))
            <a href="{{ route('user.dashboard') }}" 
               class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-4 py-2 rounded-md transition">
                Reset Pencarian
            </a>
        @endif
    </div>
@endif

<!-- Social Media Section -->
<div class="flex justify-center gap-6 mt-12 mb-12">

    <!-- Instagram -->
    <a href="https://www.instagram.com/USERNAME_KAMU"
       target="_blank"
       class="flex flex-col items-center gap-2 text-sm text-gray-500 hover:text-white transition">
    
        <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:scale-105 transition"
             style="background: radial-gradient(circle at top left, #c4c4c4, #292c30);">
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
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.498 6.186a2.958 2.958 0 00-2.08-2.093C19.61 3.5 12 3.5 12 3.5s-7.61 0-9.418.593A2.958 2.958 0 00.502 6.186C0 8.002 0 12 0 12s0 3.998.502 5.814a2.958 2.958 0 002.08 2.093C4.39 20.5 12 20.5 12 20.5s7.61 0 9.418-.593a2.958 2.958 0 002.08-2.093C24 15.998 24 12 24 12s0-3.998-.502-5.814zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/>
            </svg>
        </div>
    
        <span>Youtube</span>
    </a>

</div>

<!-- Info Box -->
<div class="mt-12 bg-yellow-50 border border-yellow-200 rounded-xl p-6">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <h3 class="font-bold text-yellow-900 mb-2">Cara Booking Kamar</h3>
            <ol class="text-sm text-yellow-800 space-y-1 list-decimal list-inside">
                <li>Pilih tempat kos yang Anda inginkan</li>
                <li>Lihat daftar kamar yang tersedia</li>
                <li>Isi form booking dengan lengkap</li>
                <li>Upload bukti transfer DP (50% dari harga sewa)</li>
                <li>Tunggu konfirmasi dari admin (maksimal 1x24 jam)</li>
                <li>Setelah disetujui, Anda dapat langsung menempati kamar</li>
            </ol>
        </div>
    </div>
</div>

@endsection