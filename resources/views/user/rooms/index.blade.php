@extends('layouts.user')

@section('title', 'Cari Kamar')

@section('content')

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">🏠 Cari Kamar</h1>
    <p class="text-gray-600">Temukan kamar yang sesuai dengan kebutuhan Anda</p>
</div>

<!-- Kos Info Card -->
@if($kosInfo)
<div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-xl shadow-lg p-6 mb-8 text-white">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <h2 class="text-2xl font-bold mb-2">{{ $kosInfo->name }}</h2>
            <p class="text-purple-100 mb-4">{{ $kosInfo->full_address }}</p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <p class="text-purple-200 text-sm">Total Kamar</p>
                    <p class="text-2xl font-bold">{{ $kosInfo->total_rooms }}</p>
                </div>
                <div>
                    <p class="text-purple-200 text-sm">Tersedia</p>
                    <p class="text-2xl font-bold text-green-300">{{ $kosInfo->available_rooms }}</p>
                </div>
                <div>
                    <p class="text-purple-200 text-sm">Telepon</p>
                    <p class="font-semibold">{{ $kosInfo->phone }}</p>
                </div>
                <div>
                    <p class="text-purple-200 text-sm">WhatsApp</p>
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
                        <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs">
                            {{ $facility }}
                        </span>
                    @endforeach
                </div>
            @endif
            
        </div>
    </div>
</div>
@endif

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <form method="GET" action="{{ route('user.rooms.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Filter Tipe -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Kamar</label>
            <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                <option value="">Semua Tipe</option>
                @foreach($types as $type)
                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                    {{ ucfirst($type) }}
                </option>
                @endforeach
            </select>
        </div>
        
        <!-- Filter Lantai -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Lantai</label>
            <select name="floor" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                <option value="">Semua Lantai</option>
                @foreach($floors as $floor)
                <option value="{{ $floor }}" {{ request('floor') == $floor ? 'selected' : '' }}>
                    {{ $floor }}
                </option>
                @endforeach
            </select>
        </div>
        
        <!-- Filter Harga -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Max</label>
            <select name="max_price" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                <option value="">Tanpa Batas</option>
                <option value="1000000" {{ request('max_price') == '1000000' ? 'selected' : '' }}> Rp 1.000.000</option>
                <option value="1500000" {{ request('max_price') == '1500000' ? 'selected' : '' }}> Rp 1.500.000</option>
                <option value="2000000" {{ request('max_price') == '2000000' ? 'selected' : '' }}> Rp 2.000.000</option>
            </select>
        </div>
        
        <!-- Tombol Cari -->
        <div class="flex items-end">
            <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700">
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
        <div class="h-48 bg-gradient-to-br from-purple-400 to-indigo-500 relative">
            <div class="absolute top-4 left-4">
                <span class="bg-white px-3 py-1 rounded-full text-xs font-semibold">{{ ucfirst($room->type) }}</span>
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
                    <span class="px-2 py-1 bg-purple-50 text-purple-600 text-xs rounded-full">
                        {{ $facility }}
                    </span>
                @endforeach
            </div>
            @endif
            
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">/bulan</p>
                </div>
            </div>
            
            <a href="{{ route('user.rooms.show', $room->id) }}" class="block w-full text-center bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700">
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
    <a href="{{ route('user.rooms.index') }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
        Reset Filter
    </a>
</div>
@endif

@endsection