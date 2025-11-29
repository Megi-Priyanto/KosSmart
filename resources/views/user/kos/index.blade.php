@extends('layouts.user')

@section('title', 'Cari Kos')

@section('content')

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">🏠 Cari Kos</h1>
    <p class="text-gray-600">Temukan kos yang sesuai dengan kebutuhan Anda</p>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <form method="GET" action="{{ route('user.kos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Filter Kota -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
            <select name="city" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <option value="">Semua Kota</option>
                @foreach($cities as $city)
                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Filter Gender -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Kos</label>
            <select name="gender" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <option value="">Semua Tipe</option>
                <option value="putra" {{ request('gender') == 'putra' ? 'selected' : '' }}>Khusus Putra</option>
                <option value="putri" {{ request('gender') == 'putri' ? 'selected' : '' }}>Khusus Putri</option>
                <option value="campur" {{ request('gender') == 'campur' ? 'selected' : '' }}>Campur</option>
            </select>
        </div>
        
        <!-- Filter Harga Max -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Maksimal</label>
            <select name="max_price" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <option value="">Tanpa Batas</option>
                <option value="1000000" {{ request('max_price') == '1000000' ? 'selected' : '' }}>< Rp 1.000.000</option>
                <option value="1500000" {{ request('max_price') == '1500000' ? 'selected' : '' }}>< Rp 1.500.000</option>
                <option value="2000000" {{ request('max_price') == '2000000' ? 'selected' : '' }}>< Rp 2.000.000</option>
                <option value="3000000" {{ request('max_price') == '3000000' ? 'selected' : '' }}>< Rp 3.000.000</option>
            </select>
        </div>
        
        <!-- Tombol Filter -->
        <div class="flex items-end">
            <button type="submit" class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari
            </button>
        </div>
    </form>
</div>

<!-- Sorting -->
<div class="flex justify-between items-center mb-6">
    <p class="text-gray-600">Menampilkan {{ $kosList->total() }} kos</p>
    <div class="flex items-center space-x-2">
        <label class="text-sm text-gray-600">Urutkan:</label>
        <select onchange="window.location.href='?sort=' + this.value + '&{{ http_build_query(request()->except('sort')) }}'" class="border border-gray-300 rounded-lg px-3 py-1 text-sm">
            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama (A-Z)</option>
            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
        </select>
    </div>
</div>

<!-- Daftar Kos -->
@if($kosList->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    @foreach($kosList as $kos)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all">
        <!-- Gambar Kos -->
        <div class="h-48 bg-gradient-to-br from-purple-400 to-indigo-500 relative">
            @if($kos->images && count($kos->images) > 0)
            <img src="{{ asset('storage/' . $kos->images[0]) }}" alt="{{ $kos->name }}" class="w-full h-full object-cover">
            @endif
            <div class="absolute top-4 left-4">
                <span class="bg-white px-3 py-1 rounded-full text-xs font-semibold text-gray-700">
                    {{ ucfirst($kos->gender) }}
                </span>
            </div>
            <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                {{ $kos->available_rooms }} Tersedia
            </div>
        </div>
        
        <!-- Info Kos -->
        <div class="p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $kos->name }}</h3>
            <p class="text-sm text-gray-600 mb-3 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $kos->city }}, {{ $kos->province }}
            </p>
            
            <!-- Fasilitas -->
            @if($kos->facilities && count($kos->facilities) > 0)
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach(array_slice($kos->facilities, 0, 3) as $facility)
                <span class="px-2 py-1 bg-purple-50 text-purple-600 text-xs rounded-full">{{ $facility }}</span>
                @endforeach
                @if(count($kos->facilities) > 3)
                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">+{{ count($kos->facilities) - 3 }}</span>
                @endif
            </div>
            @endif
            
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs text-gray-500">Mulai dari</p>
                    <p class="text-xl font-bold text-purple-600">Rp {{ number_format($kos->min_price, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">/bulan</p>
                </div>
            </div>
            
            <a href="{{ route('user.kos.show', $kos->id) }}" class="block w-full text-center bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition-colors">
                Lihat Detail
            </a>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $kosList->links() }}
</div>

@else
<div class="bg-gray-50 rounded-xl p-12 text-center">
    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
    </svg>
    <h3 class="text-xl font-semibold text-gray-700 mb-2">Kos Tidak Ditemukan</h3>
    <p class="text-gray-600 mb-4">Coba ubah filter pencarian Anda</p>
    <a href="{{ route('user.kos.index') }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
        Reset Filter
    </a>
</div>
@endif

@endsection