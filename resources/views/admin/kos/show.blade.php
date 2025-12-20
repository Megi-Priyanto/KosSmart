@extends('layouts.admin')

@section('title', 'Detail Kos - ' . $kos->name)
@section('page-title', 'Detail Informasi Kos')
@section('page-description', 'Lihat detail lengkap informasi kos')

@section('content')
    
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('admin.kos.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Kos
        </a>
    </div>

    {{-- Header Card --}}
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-6">
            <div class="flex justify-between items-start">
                <div class="flex items-start space-x-4">
                    <div class="bg-white bg-opacity-20 rounded-xl p-3">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $kos->name }}</h1>
                        <div class="flex items-center space-x-3">
                            @if($kos->is_active)
                                <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    STATUS AKTIF
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-400 text-white text-xs font-semibold rounded-full">
                                    NON-AKTIF
                                </span>
                            @endif
                            <span class="text-purple-100 text-sm">
                                📍 {{ $kos->city }}, {{ $kos->province }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('admin.kos.edit', $kos->id) }}"
                       class="bg-white text-purple-600 px-5 py-2.5 rounded-lg font-medium hover:bg-purple-50 transition-colors flex items-center space-x-2 shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit</span>
                    </a>

                    @if(!$kos->is_active)
                    <form method="POST" action="{{ route('admin.kos.activate', $kos->id) }}" class="inline-block">
                        @csrf
                        <button class="bg-green-500 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-green-600 transition-colors flex items-center space-x-2 shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Aktifkan</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Kamar</p>
                    <p class="text-3xl font-bold">{{ $kos->total_rooms ?? 0 }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Kamar Tersedia</p>
                    <p class="text-3xl font-bold">{{ $kos->available_rooms ?? 0 }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium mb-1">Kamar Terisi</p>
                    <p class="text-3xl font-bold">{{ $kos->occupied_rooms ?? 0 }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Tingkat Hunian</p>
                    <p class="text-3xl font-bold">
                        {{ $kos->total_rooms > 0 ? round(($kos->occupied_rooms / $kos->total_rooms) * 100) : 0 }}%
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Gallery Section --}}
    @if($kos->images && count($kos->images) > 0)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-pink-600 to-pink-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Galeri Foto Kos
            </h2>
            <p class="text-pink-100 text-sm mt-1">Foto-foto properti kos</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($kos->images as $image)
                <div class="relative group overflow-hidden rounded-lg shadow-md cursor-pointer">
                    <img src="{{ asset('storage/' . $image) }}" 
                         alt="Foto Kos {{ $kos->name }}" 
                         class="w-full h-56 object-cover transition-transform duration-300 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all flex items-center justify-center">
                        <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Detail Content --}}
    <div class="grid md:grid-cols-2 gap-6 mb-6">
        
        {{-- Informasi Umum --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Umum
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Deskripsi</label>
                    <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-lg">
                        {{ $kos->description ?: 'Tidak ada deskripsi' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Alamat & Lokasi --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Alamat & Lokasi
                </h2>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-indigo-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">Alamat Lengkap</p>
                        <p class="text-sm text-gray-700">{{ $kos->address }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs font-medium text-gray-500 mb-1">Kota</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $kos->city }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs font-medium text-gray-500 mb-1">Provinsi</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $kos->province }}</p>
                    </div>
                </div>

                @if($kos->postal_code)
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs font-medium text-gray-500 mb-1">Kode Pos</p>
                    <p class="text-sm font-semibold text-gray-700">{{ $kos->postal_code }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Kontak & Jam Operasional --}}
    <div class="grid md:grid-cols-2 gap-6 mb-6">
        
        {{-- Informasi Kontak --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Informasi Kontak
                </h2>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex items-center space-x-3 bg-gray-50 p-4 rounded-lg">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Telepon</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $kos->phone }}</p>
                    </div>
                </div>

                @if($kos->whatsapp)
                <div class="flex items-center space-x-3 bg-gray-50 p-4 rounded-lg">
                    <div class="bg-green-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">WhatsApp</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $kos->whatsapp }}</p>
                    </div>
                </div>
                @endif

                @if($kos->email)
                <div class="flex items-center space-x-3 bg-gray-50 p-4 rounded-lg">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Email</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $kos->email }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Jam Operasional --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Jam Operasional
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-green-500 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-green-700 mb-1">Waktu Check-in</p>
                                <p class="text-2xl font-bold text-green-900">
                                    {{ \Carbon\Carbon::parse($kos->checkin_time)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-red-50 to-red-100 p-4 rounded-lg border-l-4 border-red-500">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-red-500 p-2 rounded-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-red-700 mb-1">Waktu Check-out</p>
                                <p class="text-2xl font-bold text-red-900">
                                    {{ \Carbon\Carbon::parse($kos->checkout_time)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Fasilitas Umum --}}
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-cyan-600 to-cyan-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                Fasilitas Umum
            </h2>
            <p class="text-cyan-100 text-sm mt-1">Fasilitas yang tersedia di kos</p>
        </div>
        <div class="p-6">
            @if($kos->general_facilities && count($kos->general_facilities) > 0)
                <ul class="space-y-3">
                    @foreach($kos->general_facilities as $facility)
                    <li class="flex items-center space-x-3 bg-gray-50 p-3 rounded-lg">
                        <div class="bg-cyan-100 p-2 rounded-lg">
                            <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-700 font-medium">{{ $facility }}</span>
                    </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">Belum ada fasilitas yang ditambahkan</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Peraturan Kos --}}
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Peraturan Kos
            </h2>
            <p class="text-red-100 text-sm mt-1">Aturan yang harus dipatuhi penghuni</p>
        </div>
        <div class="p-6">
            @if($kos->rules && count($kos->rules) > 0)
                <ol class="space-y-3">
                    @foreach($kos->rules as $index => $rule)
                    <li class="flex items-start space-x-3 bg-gray-50 p-3 rounded-lg">
                        <div class="bg-red-100 text-red-600 font-bold text-sm w-7 h-7 flex items-center justify-center rounded-lg flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <span class="text-sm text-gray-700 leading-relaxed pt-0.5">{{ $rule }}</span>
                    </li>
                    @endforeach
                </ol>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">Belum ada peraturan yang ditambahkan</p>
                </div>
            @endif
        </div>
    </div>

    @endsection