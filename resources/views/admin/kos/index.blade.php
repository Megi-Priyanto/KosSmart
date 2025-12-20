@extends('layouts.admin')

@section('title', 'Informasi Kos')
@section('page-title', 'Informasi Kos')
@section('page-description', 'Kelola informasi dan profil kos Anda')

@section('content')

{{-- ================= QUICK STATS ================= --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium mb-1">Total Kamar</p>
                <p class="text-3xl font-bold">{{ $activeKos?->total_rooms ?? 0 }}</p>
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
                <p class="text-3xl font-bold">{{ $activeKos?->available_rooms ?? 0 }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Kamar Terisi</p>
                <p class="text-3xl font-bold">{{ $activeKos?->occupied_rooms ?? 0 }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br {{ $activeKos ? 'from-yellow-500 to-yellow-600' : 'from-gray-400 to-gray-500' }} rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="{{ $activeKos ? 'text-yellow-100' : 'text-gray-100' }} text-sm font-medium mb-1">Status Kos</p>
                <p class="text-3xl font-bold">{{ $activeKos ? 'Aktif' : 'Belum Ada' }}</p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if($activeKos)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    @endif
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- ================= KOS AKTIF ================= --}}
@if($activeKos)
<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8">

    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-5 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <div class="bg-white bg-opacity-20 rounded-lg p-2">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">{{ $activeKos->name }}</h2>
                <p class="text-purple-100 text-sm">Kos yang sedang aktif</p>
            </div>
        </div>

        <a href="{{ route('admin.kos.edit', $activeKos->id) }}"
           class="bg-white text-purple-600 px-5 py-2.5 rounded-lg font-medium hover:bg-purple-50 transition-colors flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <span>Edit Informasi</span>
        </a>
    </div>

    <div class="p-6">
        <div class="grid md:grid-cols-2 gap-8">
            {{-- Kolom Kiri --}}
            <div class="space-y-6">
                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-800">Deskripsi</h3>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $activeKos->description ?: 'Tidak ada deskripsi' }}</p>
                </div>

                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-800">Alamat</h3>
                    </div>
                    <p class="text-sm text-gray-600">
                        {{ $activeKos->address }}, {{ $activeKos->city }}, {{ $activeKos->province }}
                        @if($activeKos->postal_code)
                            <span class="text-gray-500">({{ $activeKos->postal_code }})</span>
                        @endif
                    </p>
                </div>

                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-800">Kontak</h3>
                    </div>
                    <div class="space-y-1 text-sm text-gray-600">
                        <p>📞 {{ $activeKos->phone }}</p>
                        @if($activeKos->whatsapp)
                            <p>💬 {{ $activeKos->whatsapp }}</p>
                        @endif
                        @if($activeKos->email)
                            <p>📧 {{ $activeKos->email }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="space-y-6">
                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-800">Fasilitas Umum</h3>
                    </div>
                    @if($activeKos->general_facilities && count($activeKos->general_facilities) > 0)
                        <ul class="space-y-2">
                            @foreach($activeKos->general_facilities as $f)
                                <li class="flex items-center text-sm text-gray-600">
                                    <span class="w-2 h-2 bg-orange-500 rounded-full mr-3"></span>
                                    {{ $f }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 italic">Belum ada fasilitas yang ditambahkan</p>
                    @endif
                </div>

                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-800">Peraturan Kos</h3>
                    </div>
                    @if($activeKos->rules && count($activeKos->rules) > 0)
                        <ol class="space-y-2 list-decimal list-inside text-sm text-gray-600">
                            @foreach($activeKos->rules as $r)
                                <li>{{ $r }}</li>
                            @endforeach
                        </ol>
                    @else
                        <p class="text-sm text-gray-500 italic">Belum ada peraturan yang ditambahkan</p>
                    @endif
                </div>

                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="font-semibold text-gray-800">Jam Operasional</h3>
                    </div>
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        <div class="flex items-center space-x-2">
                            <span class="font-medium">Check-in:</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg font-medium">
                                {{ \Carbon\Carbon::parse($activeKos->checkin_time)->format('H:i') }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="font-medium">Check-out:</span>
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg font-medium">
                                {{ \Carbon\Carbon::parse($activeKos->checkout_time)->format('H:i') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Foto Gallery --}}
        @if($activeKos->images && count($activeKos->images) > 0)
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex items-center space-x-2 mb-4">
                <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="font-semibold text-gray-800">Galeri Foto</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($activeKos->images as $image)
                <div class="relative group overflow-hidden rounded-lg shadow-md">
                    <img src="{{ asset('storage/' . $image) }}" 
                         alt="Foto Kos" 
                         class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-110">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all"></div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@else
<div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 rounded-lg p-6 mb-8 flex items-start">
    <svg class="w-6 h-6 text-yellow-600 mr-4 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
    </svg>
    <div>
        <h3 class="text-yellow-800 font-semibold mb-1">Belum Ada Kos Aktif</h3>
        <p class="text-yellow-700 text-sm mb-3">Anda belum memiliki informasi kos yang aktif. Silakan tambah atau aktifkan salah satu kos di bawah.</p>
        <a href="{{ route('admin.kos.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Kos Sekarang
        </a>
    </div>
</div>
@endif

{{-- ================= DAFTAR SEMUA DATA KOS ================= --}}
<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-5 flex justify-between items-center border-b border-gray-200">
        <div>
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <svg class="w-6 h-6 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                Daftar Semua Kos
            </h3>
            <p class="text-sm text-gray-500 mt-1">Kelola semua informasi kos yang tersimpan</p>
        </div>
        <a href="{{ route('admin.kos.create') }}"
           class="bg-purple-600 text-white px-5 py-2.5 rounded-lg hover:bg-purple-700 transition-colors font-medium flex items-center space-x-2 shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Kos Baru</span>
        </a>
    </div>

    <div class="p-6">
        @if($kosInfos->count() > 0)
            <div class="space-y-4">
                @foreach($kosInfos as $kos)
                <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow {{ $kos->is_active ? 'bg-purple-50 border-purple-300' : 'bg-white' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h4 class="text-lg font-bold text-gray-800">{{ $kos->name }}</h4>
                                @if($kos->is_active)
                                    <span class="px-3 py-1 bg-purple-600 text-white text-xs font-semibold rounded-full flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        AKTIF
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-200 text-gray-600 text-xs font-semibold rounded-full">
                                        NON-AKTIF
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mb-2">
                                {{ $kos->city }}, {{ $kos->province }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $kos->phone }}
                            </p>
                        </div>

                        <div class="flex gap-2 ml-4">
                            <a href="{{ route('admin.kos.edit', $kos->id) }}"
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-1 text-sm font-medium shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Edit</span>
                            </a>

                            <a href="{{ route('admin.kos.show', $kos->id) }}"
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-1 text-sm font-medium shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Show</span>
                            </a>

                            @if(!$kos->is_active)
                            <form method="POST" action="{{ route('admin.kos.activate', $kos->id) }}">
                                @csrf
                                <button class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-1 text-sm font-medium shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Aktifkan</span>
                                </button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.kos.deactivate', $kos->id) }}">
                                @csrf
                                <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center space-x-1 text-sm font-medium shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span>Nonaktifkan</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-500 mb-4">Belum ada data kos tersimpan</p>
                <a href="{{ route('admin.kos.create') }}" 
                   class="inline-flex items-center px-5 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Kos Pertama
                </a>
            </div>
        @endif
    </div>
</div>

@endsection