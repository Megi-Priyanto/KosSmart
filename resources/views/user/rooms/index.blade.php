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

        <!-- Stats -->
        <div class="grid grid-cols-4 gap-4 pt-4 border-t border-white/20">
            <div class="text-center">
                <div class="text-lg sm:text-xl font-bold">{{ $totalRooms }}</div>
                <div class="text-xs sm:text-sm text-white/80">Total Kamar</div>
            </div>
        
            <div class="text-center">
                <div class="text-lg sm:text-xl font-bold">{{ $availableRooms }}</div>
                <div class="text-xs sm:text-sm text-white/80">Tersedia</div>
            </div>
        
            <div class="text-center">
                <div class="text-lg sm:text-xl font-bold">{{ $occupiedRooms }}</div>
                <div class="text-xs sm:text-sm text-white/80">Terisi</div>
            </div>
            
            <div class="text-center">
                <div class="text-lg sm:text-xl font-bold">{{ $maintenanceRooms ?? 0 }}</div>
                <div class="text-xs sm:text-sm text-white/80">Maintenance</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-md border border-yellow-200 p-4 sm:p-5 mb-6">
    <form method="GET" action="{{ route('user.rooms.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        
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
@if($rooms && $rooms->count() > 0)
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-800">Daftar Kamar</h2>
    <p class="text-gray-600">{{ $rooms->total() }} kamar ditemukan</p>
</div>

<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
    @foreach($rooms as $room)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all
                    {{ $room->status === 'occupied' ? 'opacity-75' : '' }}
                    {{ $room->status === 'maintenance' ? 'opacity-60' : '' }}">
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
            
                <!-- BADGE STATUS DINAMIS -->
                <div class="absolute top-4 right-4">
                    @if($room->status === 'available')
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            Tersedia
                        </span>
                    @elseif($room->status === 'occupied')
                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            Terisi
                        </span>
                    @elseif($room->status === 'maintenance')
                        <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            Maintenance
                        </span>
                    @endif
                </div>
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
                
                <!-- BUTTON DISESUAIKAN DENGAN STATUS -->
                @if($room->status === 'available')
                    <a href="{{ route('user.rooms.show', $room->id) }}" 
                       class="block w-full text-center bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold py-1.5 rounded-md text-sm hover:from-yellow-600 hover:to-orange-700 transition shadow-md">
                        Lihat Detail
                    </a>
                @elseif($room->status === 'occupied')
                    <button disabled
                       class="block w-full text-center bg-gray-300 text-gray-600 font-semibold py-1.5 rounded-md text-sm cursor-not-allowed">
                        Sudah Terisi
                    </button>
                @elseif($room->status === 'maintenance')
                    <button disabled
                       class="block w-full text-center bg-orange-200 text-orange-700 font-semibold py-1.5 rounded-md text-sm cursor-not-allowed">
                        Sedang Maintenance
                    </button>
                @endif
            </div>
        </div>
    @endforeach
</div>

{{-- Rating --}}
<style>
    .ulasan-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 1.1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .ulasan-card:hover {
        border-color: #fcd34d;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }

    /* Batas teks 3 baris */
    .komentar-box {
        position: relative;
        overflow: hidden;
        max-height: 62px;
        transition: max-height 0.4s ease;
        flex-shrink: 0;
    }
    .komentar-box.is-open { max-height: 600px; }

    /* Gradient fade ujung teks */
    .komentar-fade-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 32px;
        background: linear-gradient(to bottom, transparent, #ffffff);
        pointer-events: none;
        transition: opacity 0.3s;
    }
    .komentar-box.is-open .komentar-fade-overlay { opacity: 0; }

    /* "...selengkapnya" pojok kanan bawah */
    .komentar-readmore {
        position: absolute;
        bottom: 0; right: 0;
        font-size: 0.72rem;
        font-weight: 700;
        color: #d97706;
        cursor: pointer;
        background: #ffffff;
        padding: 0 2px 1px 8px;
    }
    .komentar-box.is-open .komentar-readmore { display: none; }

    /* Spacer dorong user-info ke bawah */
    .ulasan-spacer { flex: 1; min-height: 0.5rem; }

    /* User info selalu di paling bawah */
    .ulasan-footer {
        border-top: 1px solid #f3f4f6;
        padding-top: 0.65rem;
        margin-top: 0.6rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex-shrink: 0;
    }

    /* Rating bar */
    .rating-bar-track {
        flex: 1;
        height: 8px;
        background: #f3f4f6;
        border-radius: 100px;
        overflow: hidden;
    }
    .rating-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #f59e0b, #d97706);
        border-radius: 100px;
    }
</style>

@if(isset($totalUlasan) && $totalUlasan > 0)
<section class="mt-12 pt-10 border-t border-gray-200">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Rating & Ulasan</h2>
            <p class="text-sm text-gray-500">{{ $totalUlasan }} ulasan dari penghuni</p>
        </div>
    </div>

    {{-- Ringkasan rating --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 mb-6"
         style="display:grid;grid-template-columns:auto 1fr;gap:2.5rem;align-items:center;">

        {{-- Angka rata-rata --}}
        <div class="text-center" style="min-width:130px;">
            <div style="font-size:4rem;font-weight:900;color:#f59e0b;line-height:1;">
                {{ number_format($avgRating, 1) }}
            </div>
            <div style="display:flex;justify-content:center;gap:3px;margin:0.5rem 0;">
                @for($i = 1; $i <= 5; $i++)
                    <svg width="18" height="18" viewBox="0 0 24 24"
                         fill="{{ $i <= round($avgRating) ? '#f59e0b' : '#e5e7eb' }}">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                @endfor
            </div>
            <div class="text-sm text-gray-400">dari {{ $totalUlasan }} ulasan</div>
        </div>

        {{-- Bar distribusi 5 → 1 --}}
        <div style="display:flex;flex-direction:column;gap:0.55rem;">
            @for($i = 5; $i >= 1; $i--)
            <div style="display:flex;align-items:center;gap:0.65rem;">
                <span class="text-xs text-gray-400" style="width:14px;text-align:right;flex-shrink:0;">{{ $i }}</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="#f59e0b" style="flex-shrink:0;">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                <div class="rating-bar-track">
                    <div class="rating-bar-fill" style="width:{{ $ratingDistribution[$i]['percent'] ?? 0 }}%;"></div>
                </div>
                <span class="text-xs text-gray-400" style="width:28px;text-align:right;flex-shrink:0;">
                    {{ $ratingDistribution[$i]['count'] ?? 0 }}
                </span>
            </div>
            @endfor
        </div>
    </div>

    {{-- Kartu-kartu ulasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach(($ulasanList ?? collect())->take(6) as $ulasan)
        <div x-data="{
                open: false,
                overflow: false,
                init() {
                    this.$nextTick(() => {
                        const el = this.$refs.teks;
                        if (el) this.overflow = el.scrollHeight > 64;
                    });
                }
             }"
             class="ulasan-card">

            {{-- Bintang --}}
            <div style="display:flex;align-items:center;gap:2px;margin-bottom:0.6rem;flex-shrink:0;">
                @for($i = 1; $i <= 5; $i++)
                    <svg width="14" height="14" viewBox="0 0 24 24"
                         fill="{{ $i <= $ulasan->rating ? '#f59e0b' : '#e5e7eb' }}">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                @endfor
                <span style="font-size:0.72rem;font-weight:700;color:#d97706;margin-left:4px;">
                    {{ number_format($ulasan->rating, 1) }}
                </span>
            </div>

            {{-- Komentar: fixed 3 baris + fade + expand --}}
            <div class="komentar-box" :class="{ 'is-open': open }">
                <p x-ref="teks"
                   class="text-sm text-gray-600"
                   style="line-height:1.6;font-style:italic;">
                    "{{ $ulasan->komentar ?: 'Tidak ada komentar.' }}"
                </p>
                <div class="komentar-fade-overlay" x-show="overflow"></div>
                <span class="komentar-readmore"
                      x-show="overflow"
                      @click="open = true">
                    ...selengkapnya
                </span>
            </div>

            {{-- Sembunyikan --}}
            <div x-show="open && overflow" style="margin-top:4px;flex-shrink:0;">
                <button @click="open = false"
                        class="text-xs font-bold text-yellow-600"
                        style="background:none;border:none;cursor:pointer;padding:0;font-family:inherit;">
                    ↑ Sembunyikan
                </button>
            </div>

            {{-- Spacer --}}
            <div class="ulasan-spacer"></div>

            {{-- User info — SELALU di paling bawah --}}
            <div class="ulasan-footer">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-yellow-400 to-orange-400 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($ulasan->user?->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-700" style="line-height:1.2;">
                        {{ $ulasan->user?->name ?? 'Anonim' }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $ulasan->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if(isset($ulasanList) && $ulasanList->count() > 6)
    <p class="text-center text-sm text-gray-400 mt-4">
        Dan {{ $ulasanList->count() - 6 }} ulasan lainnya...
    </p>
    @endif

</section>

@else
{{-- Belum ada ulasan --}}
@if(isset($tempatKos))
<section class="mt-10 p-6 bg-gray-50 border border-gray-200 rounded-2xl text-center">
    <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
    </svg>
    <p class="text-sm text-gray-500">Belum ada ulasan untuk kos ini.</p>
</section>
@endif
@endif

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
        <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Kamar</h3>
        <p class="text-gray-500 mb-6">
            Saat ini tidak ada kamar yang terdaftar di tempat kos ini.
        </p>
        <a href="{{ route('user.dashboard') }}" 
           class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-medium px-6 py-3 rounded-lg transition">
            Cari Tempat Kos Lain
        </a>
    </div>
@endif

@endsection