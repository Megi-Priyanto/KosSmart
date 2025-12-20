@extends('layouts.user')

@section('title', 'Cari Kamar')

@section('content')

<!-- Page Header -->
<div class="mb-8">
    <!-- Judul -->
    <div class="flex items-center gap-3 mb-1">
        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
            </path>
        </svg>

        <span class="text-xl font-bold text-gray-800">
            Cari Kamar
        </span>
    </div>

    <!-- Deskripsi -->
    <p class="text-gray-600">
        Temukan kamar yang sesuai dengan kebutuhan Anda
    </p>
</div>

<!-- Kos Info Card -->
@if($kosInfo)
<div class="bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl shadow-lg p-6 mb-8 text-white">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <h2 class="text-2xl font-bold mb-2">{{ $kosInfo->name }}</h2>
            <p class="text-orange-100 mb-4">{{ $kosInfo->full_address }}</p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <p class="text-white-200 text-sm">Total Kamar</p>
                    <p class="text-2xl font-bold">{{ $kosInfo->total_rooms }}</p>
                </div>
                <div>
                    <p class="text-white-200 text-sm">Tersedia</p>
                    <p class="text-2xl font-bold text-green-300">{{ $kosInfo->available_rooms }}</p>
                </div>
                <div>
                    <p class="text-white-200 text-sm">Telepon</p>
                    <p class="font-semibold">{{ $kosInfo->phone }}</p>
                </div>
                <div>
                    <p class="text-white-200 text-sm">WhatsApp</p>
                    <p class="font-semibold">{{ $kosInfo->whatsapp }}</p>
                </div>
            </div>
            
            @if($kosInfo->general_facilities)
                <div class="flex flex-wrap gap-2">
                    @php
                        // Jika value JSON, decode
                        if (is_string($kosInfo->general_facilities) && str_starts_with(trim($kosInfo->general_facilities), '[')) {
                            $facilities = json_decode($kosInfo->general_facilities, true);
                        } 
                        // Jika CSV, pisahkan dengan koma
                        elseif (is_string($kosInfo->general_facilities)) {
                            $facilities = array_map('trim', explode(',', $kosInfo->general_facilities));
                        } 
                        // Jika array langsung
                        else {
                            $facilities = $kosInfo->general_facilities;
                        }
                    @endphp

                    @foreach(array_slice($facilities, 0, 5) as $facility)
                        <span class="px-3 py-1 bg-white text-orange-600 font-semibold rounded-full text-xs shadow">
                    @endforeach
                </div>
            @endif
            
        </div>
    </div>
</div>
@endif

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-md border border-yellow-200 p-6 mb-8">
    <form method="GET" action="{{ route('user.rooms.index') }}"
          class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <!-- FILTER TIPE KAMAR -->
        <div x-data="{ open:false, selected:'{{ request('type') ? ucfirst(request('type')) : 'Semua Tipe' }}' }" class="relative">
            <label class="block text-sm font-semibold text-yellow-700 mb-2">
                Tipe Kamar
            </label>

            <!-- hidden input (PENTING untuk GET Laravel) -->
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

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    @foreach($rooms as $room)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all">
            <div class="h-48 w-full relative overflow-hidden">
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
                    <!-- Jika tidak ada gambar -->
                    <div class="w-full h-full bg-gradient-to-br from-yellow-400 to-orange-500"></div>
                @endif
            
                <div class="absolute top-4 left-4">
                    <span class="bg-white px-3 py-1 rounded-full text-xs font-semibold">
                        {{ ucfirst($room->type) }}
                    </span>
                </div>
            
                <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                    Tersedia
                </div>
            </div>
        
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Kamar {{ $room->room_number }}</h3>
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
                        <p class="text-2xl font-bold text-yellow-600">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">{{ $room->jenis_sewa === 'tahun' ? '/tahun' : '/bulan' }}</p>
                    </div>
                </div>
                
                <a href="{{ route('user.rooms.show', $room->id) }}" class="block w-full text-center bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold py-2 rounded-lg hover:from-yellow-600 hover:to-orange-700 transition shadow-md">
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
<div class="bg-gray-50 rounded-xl p-12 text-center">
    <h3 class="text-xl font-semibold text-gray-700 mb-2">Kamar Tidak Ditemukan</h3>
    <p class="text-gray-600 mb-4">Coba ubah filter pencarian Anda</p>
    <a href="{{ route('user.rooms.index') }}" class="inline-block bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition">
        Reset Filter
    </a>
</div>
@endif

@endsection