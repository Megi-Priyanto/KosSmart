@extends('layouts.user')

@section('title', 'Kamar - ' . ($kosInfo?->name ?? 'Semua Kamar'))

@section('content')

<!-- Breadcrumb -->
<nav class="mb-4 flex items-center text-xs sm:text-sm text-gray-500">
    <a href="{{ route('user.dashboard') }}" class="hover:text-yellow-600 transition">
        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Daftar Kos
    </a>
    @if(isset($tempatKos))
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-900 font-medium">{{ $tempatKos->nama_kos }}</span>
    @else
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-900 font-medium">Semua Kamar</span>
    @endif
</nav>

<!-- Tempat Kos Header -->
<div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl shadow-lg overflow-hidden mb-8">
    <div class="p-4 sm:p-6 text-white">
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h1 class="text-xl sm:text-2xl font-bold mb-1">
                    {{ $tempatKos?->nama_kos ?? 'Daftar Kamar Kos' }}
                </h1>
                <div class="flex items-center text-white/90 mb-3">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ $tempatKos?->alamat ?? '-' }}</span>
                </div>

                @if($tempatKos && ($tempatKos->phone || $tempatKos->email))
                <div class="flex flex-wrap gap-4 text-sm">
                    @if($tempatKos->telepon)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $tempatKos->telepon }}
                        </div>
                    @endif
                    
                    @if($tempatKos->email)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $tempatKos->email }}
                        </div>
                    @endif
                </div>
            @endif

            </div>

            @if($tempatKos && $tempatKos->logo)
                <div class="hidden md:block ml-6">
                    <img src="{{ asset('storage/' . $tempatKos->logo) }}" 
                         alt="{{ $tempatKos->nama_kos }}"
                         class="w-24 h-24 rounded-lg shadow-lg object-cover border-2 border-white">
                </div>
            @endif
        </div>

        <!-- Stats (HANYA STATISTIK KAMAR) -->
        <div class="grid grid-cols-3 gap-4 pt-4 border-t border-white/20">
            <div class="text-center">
                <div class="text-lg sm:text-xl font-bold">{{ $totalRooms }}</div>
                <div class="text-xs sm:text-sm text-white/80">Total Kamar</div>
            </div>
        
            <div class="text-center">
                <div class="text-2xl font-bold">{{ $availableRooms }}</div>
                <div class="text-sm text-white/80">Tersedia</div>
            </div>
        
            <div class="text-center">
                <div class="text-2xl font-bold">{{ $occupiedRooms }}</div>
                <div class="text-sm text-white/80">Terisi</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-md border border-yellow-200 p-4 sm:p-5 mb-6">
    <form method="GET" action="{{ route('user.rooms.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        
        <!-- Hidden input untuk preserve tempat_kos_id -->
        @if(request('tempat_kos_id'))
            <input type="hidden" name="tempat_kos_id" value="{{ request('tempat_kos_id') }}">
        @endif

        <!-- FILTER TIPE KAMAR -->
        <div x-data="{ open:false, selected:'{{ request('type') ? ucfirst(request('type')) : 'Semua Tipe' }}' }" class="relative">
            <label class="block text-sm font-semibold text-yellow-700 mb-2">
                Tipe Kamar
            </label>

            <input type="hidden" name="type" :value="selected === 'Semua Tipe' ? '' : selected.toLowerCase()">

            <button type="button"
                @click="open = !open"
                class="w-full border border-yellow-300 rounded-lg px-4 py-2
                       flex justify-between items-center
                       focus:outline-none focus:ring-2 focus:ring-orange-400">
                <span x-text="selected"></span>
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <ul x-show="open" @click.outside="open=false"
                class="absolute z-20 w-full mt-1 bg-white border border-orange-300
                       rounded-lg shadow-lg overflow-hidden">
                <li @click="selected='Semua Tipe'; open=false"
                    class="px-4 py-2 hover:bg-orange-100 cursor-pointer">
                    Semua Tipe
                </li>
                @foreach($types as $type)
                    <li @click="selected='{{ ucfirst($type) }}'; open=false"
                        class="px-4 py-2 hover:bg-orange-100 cursor-pointer">
                        {{ ucfirst($type) }}
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- FILTER LANTAI -->
        <div x-data="{ open:false, selected:'{{ request('floor') ?? 'Semua Lantai' }}' }" class="relative">
            <label class="block text-sm font-semibold text-yellow-700 mb-2">
                Lantai
            </label>

            <input type="hidden" name="floor" :value="selected === 'Semua Lantai' ? '' : selected">

            <button type="button"
                @click="open = !open"
                class="w-full border border-yellow-300 rounded-lg px-4 py-2
                       flex justify-between items-center
                       focus:outline-none focus:ring-2 focus:ring-orange-400">
                <span x-text="selected"></span>
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <ul x-show="open" @click.outside="open=false"
                class="absolute z-20 w-full mt-1 bg-white border border-orange-300
                       rounded-lg shadow-lg overflow-hidden">
                <li @click="selected='Semua Lantai'; open=false"
                    class="px-4 py-2 hover:bg-orange-100 cursor-pointer">
                    Semua Lantai
                </li>
                @foreach($floors as $floor)
                    <li @click="selected='{{ $floor }}'; open=false"
                        class="px-4 py-2 hover:bg-orange-100 cursor-pointer">
                        {{ $floor }}
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- FILTER HARGA -->
        <div x-data="{ open:false, selected:'{{ request('max_price') ? 'Rp ' . number_format(request('max_price'),0,',','.') : 'Tanpa Batas' }}', value:'{{ request('max_price') }}' }" class="relative">
            <label class="block text-sm font-semibold text-yellow-700 mb-2">
                Harga Max
            </label>

            <input type="hidden" name="max_price" :value="value">

            <button type="button"
                @click="open = !open"
                class="w-full border border-yellow-300 rounded-lg px-4 py-2
                       flex justify-between items-center
                       focus:outline-none focus:ring-2 focus:ring-orange-400">
                <span x-text="selected"></span>
                <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <ul x-show="open" @click.outside="open=false"
                class="absolute z-20 w-full mt-1 bg-white border border-orange-300
                       rounded-lg shadow-lg overflow-hidden">
                <li @click="selected='Tanpa Batas'; value=''; open=false"
                    class="px-4 py-2 hover:bg-orange-100 cursor-pointer">
                    Tanpa Batas
                </li>
                <li @click="selected='Rp 1.000.000'; value='1000000'; open=false"
                    class="px-4 py-2 hover:bg-orange-100 cursor-pointer">
                    Rp 1.000.000
                </li>
                <li @click="selected='Rp 1.500.000'; value='1500000'; open=false"
                    class="px-4 py-2 hover:bg-orange-100 cursor-pointer">
                    Rp 1.500.000
                </li>
                <li @click="selected='Rp 2.000.000'; value='2000000'; open=false"
                    class="px-4 py-2 hover:bg-orange-100 cursor-pointer">
                    Rp 2.000.000
                </li>
            </ul>
        </div>

        <!-- TOMBOL CARI -->
        <div class="flex items-end">
            <button type="submit"
                class="w-full bg-gradient-to-r from-yellow-500 to-orange-600
                       text-white font-semibold py-2 rounded-lg
                       hover:from-yellow-600 hover:to-orange-700
                       transition shadow-md">
                Cari
            </button>
        </div>

    </form>
</div>

<!-- Daftar Kamar -->
@if($rooms->count() > 0)
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Kamar Tersedia</h2>
    <p class="text-gray-600">{{ $rooms->total() }} kamar ditemukan</p>
</div>

<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
    @foreach($rooms as $room)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all">
            <div class="h-36 sm:h-40 w-full relative overflow-hidden">
                @php
                    $images = is_array($room->images) ? $room->images : json_decode($room->images, true);
                    $firstImage = $images[0] ?? null;
                @endphp

                @if($firstImage)
                    <img 
                        src="{{ asset('storage/' . $firstImage) }}" 
                        class="w-full h-full object-cover object-center"
                        alt="Foto Kamar {{ $room->room_number }}">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-yellow-400 to-orange-500"></div>
                @endif
            
                <div class="absolute top-4 left-4">
                    <span class="bg-white px-3 py-1 rounded-full text-xs font-semibold">
                        {{ ucfirst($room->type) }}
                    </span>
                </div>
            
                @if($room->status === 'available')
                    <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                        Tersedia
                    </div>
                @endif
            </div>
        
            <div class="p-3 sm:p-4">
                <h3 class="text-sm sm:text-base font-semibold text-gray-800 mb-2">Kamar {{ $room->room_number }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ $room->floor }} • {{ $room->size }} m²</p>
                
                @if($room->facilities)
                <div class="flex flex-wrap gap-2 mb-4">
                    @php
                        $facilities = is_array($room->facilities)
                            ? $room->facilities
                            : json_decode($room->facilities, true);
                    @endphp
    
                    @foreach(array_slice($facilities ?? [], 0, 3) as $facility)
                        <span class="px-2 py-1 bg-yellow-50 text-yellow-600 text-xs rounded-full">
                            {{ $facility }}
                        </span>
                    @endforeach
                </div>
                @endif
                
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-lg sm:text-xl font-bold text-yellow-600">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">{{ $room->jenis_sewa === 'tahun' ? '/tahun' : '/bulan' }}</p>
                    </div>
                </div>
                
                <a href="{{ route('user.rooms.show', $room->id) }}" class="block w-full text-center bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold py-1.5 rounded-md text-sm hover:from-yellow-600 hover:to-orange-700 transition shadow-md">
                    Lihat Detail
                </a>
            </div>
        </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $rooms->links() }}
</div>

@else
    <!-- Empty State -->
    <div class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-200">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Kamar Tersedia</h3>
        <p class="text-gray-500 mb-6">
            Saat ini tidak ada kamar yang tersedia di tempat kos ini.
        </p>
        <a href="{{ route('user.dashboard') }}" 
           class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-6 py-3 rounded-lg transition">
            Cari Tempat Kos Lain
        </a>
    </div>
@endif

@endsection