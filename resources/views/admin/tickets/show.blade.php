@extends('layouts.admin')

@section('title', 'Detail Komplain')

@section('content')

<!-- Header Detail -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Detail Laporan Komplain</h1>
        <p class="text-sm text-gray-600 mt-1">Tindak lanjuti & penuhi laporan kerusakan fasilitas</p>
    </div>
    <a href="{{ route('admin.tickets.index') }}"
        class="px-4 py-2 bg-white text-gray-700 font-medium rounded-lg hover:bg-gray-50 border border-gray-200 transition-all shadow-sm flex items-center justify-center">
        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar
    </a>
</div>

@if (session('success'))
<div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
    <div class="flex items-center">
        <svg class="h-6 w-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Kolom Kiri: Detail Laporan -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Main Card Laporan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header Card (Warna status) -->
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-start justify-between gap-4 
            @if($ticket->status == 'pending') bg-yellow-50 
            @elseif($ticket->status == 'in_progress') bg-blue-50
            @elseif($ticket->status == 'resolved') bg-green-50
            @else bg-red-50 @endif">

                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $ticket->title }}</h2>
                    <p class="text-sm font-medium text-gray-500">Dilaporkan {{ $ticket->created_at->format('d M Y, H:i') }} ({{ $ticket->created_at->diffForHumans() }})</p>
                </div>

                <div class="flex-shrink-0">
                    @if($ticket->status == 'pending')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                        <span class="w-2 h-2 mr-2 rounded-full bg-yellow-500 animate-pulse"></span> Menunggu Respon
                    </span>
                    @elseif($ticket->status == 'in_progress')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                        <svg class="w-3 h-3 mr-2 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Sedang Diproses
                    </span>
                    @elseif($ticket->status == 'resolved')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg> Selesai/Diperbaiki
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg> Ditolak / Batal
                    </span>
                    @endif
                </div>
            </div>

            <!-- Body Laporan -->
            <div class="p-6">
                <!-- Deskripsi -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        Rincian Kerusakan
                    </h4>
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-5">
                        <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $ticket->description }}</p>
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
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 text-center">
                        <a href="{{ Storage::url($ticket->photo_path) }}" target="_blank" class="inline-block relative group">
                            <img src="{{ Storage::url($ticket->photo_path) }}"
                                alt="Foto Lampiran"
                                class="max-h-96 rounded object-contain shadow-sm border border-gray-200 transition-all duration-300 group-hover:opacity-90 group-hover:shadow-md">

                            <!-- Overlay Hover -->
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded flex items-center justify-center">
                                <span class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg font-semibold border border-white/30 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg> Perbesar
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Status Update & Informasi -->
    <div class="space-y-6">

        <!-- Form Update Status -->
        <div class="bg-gradient-to-b from-gray-800 to-gray-900 rounded-xl shadow-lg border border-gray-700 overflow-hidden text-white">
            <div class="p-5 border-b border-gray-700/50 flex items-center bg-gray-900/50">
                <div class="p-2 bg-yellow-500/20 text-yellow-400 rounded-lg mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-lg">Update Status</h3>
            </div>

            <div class="p-5">
                <p class="text-sm text-gray-400 mb-4">Ubah status laporan komplain untuk memberikan notifikasi otomatis ke penghuni.</p>

                <form action="{{ route('admin.tickets.status.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <select name="status" class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-white shadow-inner font-medium">
                            <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>⏳ Menunggu Respon</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>🔧 Sedang Diproses</option>
                            <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>✅ Selesai (Selesai Diperbaiki)</option>
                            <option value="rejected" {{ $ticket->status == 'rejected' ? 'selected' : '' }}>❌ Ditolak / Batal</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 font-bold text-white bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg shadow-lg hover:from-yellow-600 hover:to-orange-600 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 focus:ring-offset-gray-900">
                        Simpan Perubahan Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Penghuni & Kamar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-5 border-b border-gray-100 flex items-center bg-gray-50/50">
                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 text-lg">Informasi Penghuni</h3>
            </div>

            <div class="p-5 space-y-4">
                <!-- Kontak -->
                <div class="flex items-start">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600 mr-3 flex-shrink-0">
                        {{ substr($ticket->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $ticket->user->name }}</p>
                        <a href="tel:{{ $ticket->user->phone }}" class="text-xs text-blue-600 flex items-center hover:underline mt-1">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $ticket->user->phone }}
                        </a>
                    </div>
                </div>

                <hr class="border-gray-100 border-dashed">

                <!-- Kamar Info -->
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Lokasi Kamar</p>
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-gray-900">Kamar {{ $ticket->room->room_number }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">Lantai {{ $ticket->room->floor }} &bull; {{ ucfirst($ticket->room->type) }}</p>
                        </div>
                        <a href="{{ route('admin.rooms.show', $ticket->room->id) }}" class="text-xs bg-white border border-gray-200 shadow-sm px-3 py-1.5 rounded-md font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            Lihat Unit
                        </a>
                    </div>
                </div>

                <!-- Gedung Kos -->
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Bangunan</p>
                    <p class="text-sm font-medium text-gray-800"><span class="w-2 h-2 rounded-full bg-yellow-400 inline-block mr-1"></span> {{ $ticket->tempatKos->nama_kos }}</p>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection