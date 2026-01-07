@extends('layouts.admin')

@section('title', 'Detail Kamar ' . $room->room_number)
@section('page-title', 'Kamar ' . $room->room_number)
@section('page-description', 'Detail lengkap kamar dan informasi penyewa')

@section('content')

<!-- Action Buttons -->
<div class="mb-6 flex justify-between items-center">
    <!-- Back Button -->
    <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center text-yellow-400 hover:text-yellow-500 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar Kamar
    </a>
    
    <div class="flex space-x-3">
        <a href="{{ route('admin.rooms.edit', $room) }}" 
           class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center space-x-2 transition-all shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <span>Edit</span>
        </a>
        
        <form action="{{ route('admin.rooms.destroy', $room) }}" 
              method="POST" 
              onsubmit="return confirm('Yakin ingin menghapus kamar {{ $room->room_number }}?')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center space-x-2 transition-all shadow-lg"
                    {{ $room->currentRent ? 'disabled' : '' }}>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>Hapus</span>
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left: Main Info -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Galeri Foto -->
        @if(!empty($room->images) && is_array($room->images) && count($room->images) > 0)
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-lg border border-slate-700/50 overflow-hidden shadow-xl">
            <div class="aspect-video bg-slate-900 relative" x-data="imageGallery()">
                <img :src="'{{ asset('storage') }}/' + images[currentIndex]" 
                     alt="Kamar {{ $room->room_number }}" 
                     class="w-full h-full object-cover">
                
                <!-- Navigation -->
                <template x-if="images.length > 1">
                    <div>
                        <button @click="prev" 
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-slate-900/80 backdrop-blur-sm text-white p-3 rounded-full hover:bg-slate-800 transition-all shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button @click="next" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-slate-900/80 backdrop-blur-sm text-white p-3 rounded-full hover:bg-slate-800 transition-all shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        <!-- Counter -->
                        <div class="absolute bottom-4 right-4 bg-slate-900/80 backdrop-blur-sm text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-lg">
                            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Thumbnails -->
            <div class="p-4 bg-slate-900/30 grid grid-cols-6 gap-2" x-data="imageGallery()">
                <template x-for="(image, index) in images" :key="index">
                    <div @click="currentIndex = index" 
                         class="aspect-square rounded-lg overflow-hidden cursor-pointer border-2 transition-all hover:scale-105"
                         :class="currentIndex === index ? 'border-orange-500 ring-2 ring-orange-500/50' : 'border-slate-600 hover:border-slate-500'">
                        <img :src="'{{ asset('storage') }}/' + image" 
                             class="w-full h-full object-cover">
                    </div>
                </template>
            </div>
        </div>
        
        <script>
        function imageGallery() {
            return {
                images: @json($room->images),
                currentIndex: 0,
                next() {
                    this.currentIndex = (this.currentIndex + 1) % this.images.length;
                },
                prev() {
                    this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                }
            }
        }
        </script>
        @else
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-lg border border-slate-700/50 p-16 text-center shadow-xl">
            <svg class="w-24 h-24 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-slate-400 text-lg">Belum ada foto untuk kamar ini</p>
        </div>
        @endif
        
        <!-- Info Detail -->
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Informasi Kamar
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm text-slate-400 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                        Nomor Kamar
                    </label>
                    <p class="font-bold text-xl text-white">{{ $room->room_number }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-slate-400 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Lantai
                    </label>
                    <p class="font-semibold text-white">{{ $room->floor }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-slate-400 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        Tipe
                    </label>
                    <p><span class="px-3 py-1.5 text-sm font-bold rounded-lg
                        @if($room->type == 'putra') bg-blue-500/20 text-blue-300 border border-blue-500/50
                        @elseif($room->type == 'putri') bg-pink-500/20 text-pink-300 border border-pink-500/50
                        @else bg-purple-500/20 text-purple-300 border border-purple-500/50
                        @endif">{{ ucfirst($room->type) }}</span></p>
                </div>
                
                <div>
                    <label class="text-sm text-slate-400 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Kapasitas
                    </label>
                    <p class="font-semibold text-white">{{ $room->capacity }} orang</p>
                </div>
                
                <div>
                    <label class="text-sm text-slate-400 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                        </svg>
                        Ukuran
                    </label>
                    <p class="font-semibold text-white">{{ $room->size }} m²</p>
                </div>
                
                <div>
                    <label class="text-sm text-slate-400 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Harga
                    </label>
                    <p class="font-bold text-xl text-yellow-500">{{ $room->formatted_price }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-slate-400 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Jenis Sewa
                    </label>
                    <p class="font-semibold text-white">
                        <span class="px-3 py-1.5 bg-indigo-500/20 text-indigo-300 border border-indigo-500/50 rounded-lg text-sm font-bold">
                            {{ $room->jenis_sewa_label }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <label class="text-sm text-slate-400 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Jendela
                    </label>
                    <p class="font-semibold text-white">{{ $room->has_window ? '✓ Ada' : '✗ Tidak Ada' }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-slate-400 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Dilihat
                    </label>
                    <p class="font-semibold text-white">{{ $room->view_count }} kali</p>
                </div>
            </div>
            
            @if($room->description)
            <div class="mt-6 pt-6 border-t border-slate-700">
                <label class="text-sm text-slate-400 font-semibold mb-2 block">Deskripsi</label>
                <p class="text-slate-300 leading-relaxed">{{ $room->description }}</p>
            </div>
            @endif
            
            @if($room->notes)
            <div class="mt-4 pt-4 border-t border-slate-700">
                <label class="text-sm text-slate-400 font-semibold mb-2 block">Catatan Internal</label>
                <p class="text-slate-300 leading-relaxed italic">{{ $room->notes }}</p>
            </div>
            @endif
        </div>
        
        <!-- Fasilitas -->
        @php 
            $facilities = is_array($room->facilities) 
                ? $room->facilities 
                : json_decode($room->facilities ?? '[]', true); 
        @endphp
            
        @if(!empty($facilities))
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                Fasilitas
            </h3>
            <div class="flex flex-wrap gap-2">
                @foreach($facilities as $facility)
                <span class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-slate-700 to-slate-600 text-white rounded-lg border-2 border-slate-500 hover:border-yellow-400 transition-all duration-200 shadow-lg group">
                    <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ $facility }}
                </span>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Riwayat Sewa -->
        @if($room->rents->count() > 0)
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Riwayat Sewa (5 Terakhir)
            </h3>
            <div class="space-y-3">
                @foreach($room->rents as $rent)
                <div class="flex items-center justify-between p-4 bg-slate-900/30 rounded-lg border border-slate-700 hover:border-slate-600 transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr($rent->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ $rent->user->name }}</p>
                            <p class="text-sm text-slate-400">{{ $rent->start_date->format('d M Y') }} - {{ $rent->end_date ? $rent->end_date->format('d M Y') : 'Sekarang' }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1.5 text-xs font-bold rounded-lg
                        @if($rent->status == 'active') bg-green-500/20 text-green-300 border border-green-500/50
                        @elseif($rent->status == 'expired') bg-slate-500/20 text-slate-300 border border-slate-500/50
                        @else bg-orange-500/20 text-orange-300 border border-orange-500/50
                        @endif">
                        {{ ucfirst($rent->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
    </div>
    
    <!-- Right: Status & Actions -->
    <div class="space-y-6">
        
        <!-- Status Card -->
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Status Kamar
            </h3>
            
            <div class="mb-6">
                <span class="px-5 py-2.5 text-sm font-bold rounded-lg inline-block shadow-lg
                    @if($room->status == 'available') bg-green-500/20 text-green-300 border-2 border-green-500/50
                    @elseif($room->status == 'occupied') bg-blue-500/20 text-blue-300 border-2 border-blue-500/50
                    @else bg-orange-500/20 text-orange-300 border-2 border-orange-500/50
                    @endif">
                    {{ $room->status_label }}
                </span>
            </div>
            
            <!-- Quick Status Change -->
            <form action="{{ route('admin.rooms.status.update', $room) }}" method="POST" class="space-y-3">
                @csrf
                @method('PUT')
                
                <label class="text-sm font-semibold text-slate-300 block">Ubah Status:</label>
                <select name="status" 
                        class="w-full bg-slate-900/50 border border-slate-600 rounded-lg px-4 py-3 text-white text-sm font-medium focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                        onchange="this.form.submit()">
                    <option value="available" {{ $room->status == 'available' ? 'selected' : '' }} class="bg-slate-900">Tersedia</option>
                    <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }} class="bg-slate-900">Terisi</option>
                    <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }} class="bg-slate-900">Maintenance</option>
                </select>
            </form>
        </div>
        
        <!-- Penyewa Aktif -->
        @if($room->currentRent)
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                Penyewa Aktif
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Nama</label>
                    <p class="font-bold text-white mt-1">{{ $room->currentRent->user->name }}</p>
                </div>
                
                <div>
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Email</label>
                    <p class="text-sm text-slate-300 mt-1">{{ $room->currentRent->user->email }}</p>
                </div>
                
                <div>
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Telepon</label>
                    <p class="text-sm text-slate-300 mt-1">{{ $room->currentRent->user->phone ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Mulai Sewa</label>
                    <p class="text-sm text-slate-300 mt-1">{{ $room->currentRent->start_date->format('d M Y') }}</p>
                </div>
                
                <div class="pt-4 border-t border-blue-500/20">
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Harga Sewa</label>
                    <p class="font-bold text-xl text-yellow-500 mt-1">Rp {{ number_format($room->currentRent->monthly_rent, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <a href="{{ route('admin.users.show', $room->currentRent->user_id) }}" 
               class="block w-full mt-6 text-center px-5 py-3 bg-gradient-to-r from-yellow-500 via-orange-600 to-orange-700 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all shadow-lg text-sm">
                Lihat Profil Penyewa
                <svg class="w-4 h-4 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        @endif
        
        <!-- Maintenance Info -->
        @if($room->last_maintenance)
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-lg border border-slate-700/50 p-6 shadow-xl">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white">Maintenance</h3>
            </div>
            
            <div>
                <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Terakhir Maintenance</label>
                <p class="font-bold text-white mt-2 text-lg">{{ $room->last_maintenance->format('d M Y') }}</p>
                <p class="text-xs text-slate-400 mt-1">{{ $room->last_maintenance->diffForHumans() }}</p>
            </div>
        </div>
        @endif
        
        <!-- Stats -->
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                Statistik
            </h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-slate-900/30 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="text-sm text-slate-300">Total Dilihat</span>
                    </div>
                    <span class="font-bold text-lg text-white">{{ $room->view_count }}</span>
                </div>
                
                <div class="flex justify-between items-center p-3 bg-slate-900/30 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-sm text-slate-300">Total Penyewa</span>
                    </div>
                    <span class="font-bold text-lg text-white">{{ $room->rents->count() }}</span>
                </div>
                
                <div class="pt-4 border-t border-slate-500/20">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm text-slate-300">Ditambahkan</span>
                    </div>
                    <p class="font-semibold text-white mt-2">{{ $room->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $room->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        
    </div>
    
</div>

@endsection