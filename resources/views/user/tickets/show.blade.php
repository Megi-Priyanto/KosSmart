@extends('layouts.user')

@section('title', 'Detail Komplain')

@section('content')

<!-- Header Detail -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Detail Laporan Komplain</h1>
        <p class="text-sm text-gray-600 mt-1">Pantau status laporan kerusakan fasilitas Anda</p>
    </div>
    <a href="{{ route('user.tickets.index') }}"
        class="px-4 py-2 bg-white text-gray-700 font-medium rounded-lg hover:bg-gray-50 border border-gray-200 transition-all shadow-sm flex items-center justify-center">
        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar
    </a>
</div>

<div class="max-w-4xl mx-auto">
    <!-- Main Card Laporan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <!-- Header Card (Warna status) -->
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-start justify-between gap-4 
        @if($ticket->status == 'pending') bg-yellow-50 
        @elseif($ticket->status == 'in_progress') bg-blue-50
        @elseif($ticket->status == 'resolved') bg-green-50
        @else bg-red-50 @endif">

            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $ticket->title }}</h2>
                <p class="text-sm font-medium text-gray-500">Dilaporkan pada {{ $ticket->created_at->format('d M Y, H:i') }} ({{ $ticket->created_at->diffForHumans() }})</p>
            </div>

            <div class="flex-shrink-0">
                @if($ticket->status == 'pending')
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">
                    <span class="w-2.5 h-2.5 mr-2.5 rounded-full bg-yellow-500 animate-pulse"></span> Menunggu Respon
                </span>
                @elseif($ticket->status == 'in_progress')
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-blue-100 text-blue-800 border border-blue-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2.5 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Sedang Diproses Admin
                </span>
                @elseif($ticket->status == 'resolved')
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800 border border-green-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg> Selesai Berhasil Diperbaiki
                </span>
                @else
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-800 border border-red-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg> Ditolak / Dibatalkan
                </span>
                @endif
            </div>
        </div>

        <!-- Info Kamar Singkat -->
        <div class="px-6 py-4 bg-gray-50 flex flex-wrap gap-x-8 gap-y-3 border-b border-gray-100">
            <div class="flex items-center text-sm font-medium text-gray-700">
                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Gedung: <span class="ml-1 text-gray-900">{{ $ticket->tempatKos->nama_kos }}</span>
            </div>
            <div class="flex items-center text-sm font-medium text-gray-700">
                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Lokasi: <span class="ml-1 px-2 py-0.5 bg-gray-200 text-gray-800 rounded text-xs ml-2">Kamar {{ $ticket->room->room_number }}</span>
            </div>
        </div>

        <!-- Body Laporan -->
        <div class="p-6">
            <!-- Deskripsi -->
            <div class="mb-8">
                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    Rincian Kerusakan
                </h4>
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-5 shadow-inner">
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed text-[15px]">{{ $ticket->description }}</p>
                </div>
            </div>

            <!-- Foto Lampiran -->
            @if($ticket->photo_path)
            <div>
                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Foto Lampiran
                </h4>
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 text-center inline-block max-w-full">
                    <a href="{{ Storage::url($ticket->photo_path) }}" target="_blank" class="block relative group rounded overflow-hidden">
                        <img src="{{ Storage::url($ticket->photo_path) }}"
                            alt="Foto Lampiran"
                            class="max-h-96 w-auto object-contain shadow-sm border border-gray-200 transition-all duration-300 group-hover:scale-105">

                        <!-- Overlay Hover -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg font-semibold border border-white/30 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                </svg> Lihat Penuh
                            </span>
                        </div>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection