@extends('layouts.admin')

@section('title', 'Edit Kamar')
@section('page-title', 'Edit Kamar ' . $room->room_number)
@section('page-description', 'Perbarui informasi kamar')

@section('content')

<div class="w-full mx-auto">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.rooms.index') }}" 
           class="px-5 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
            Kembali ke Daftar Kamar
        </a>
    </div>
    
    <form action="{{ route('admin.rooms.update', $room) }}" method="POST" enctype="multipart/form-data" x-data="roomEditForm()">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        
            <!-- Informasi Dasar -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Informasi Dasar
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nomor Kamar -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">
                            Nomor Kamar <span class="text-orange-400"></span>
                        </label>
                        <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}"
                               class="w-full bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 placeholder:text-slate-500 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('room_number') border-red-500 @enderror"
                               required>
                        @error('room_number')
                        <p class="text-red-400 text-sm mt-1.5 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    
                    <!-- Lantai -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">
                            Lantai <span class="text-orange-400"></span>
                        </label>
                        <input type="text" name="floor" value="{{ old('floor', $room->floor) }}"
                               class="w-full bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 placeholder:text-slate-500 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('floor') border-red-500 @enderror"
                               required>
                        @error('floor')
                        <p class="text-red-400 text-sm mt-1.5 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    
                    <!-- Tipe -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">
                            Tipe Kamar <span class="text-orange-400"></span>
                        </label>
                        <select name="type" 
                                class="w-full bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('type') border-red-500 @enderror"
                                required>
                            <option value="putra" {{ old('type', $room->type) == 'putra' ? 'selected' : '' }} class="bg-slate-900">Putra</option>
                            <option value="putri" {{ old('type', $room->type) == 'putri' ? 'selected' : '' }} class="bg-slate-900">Putri</option>
                            <option value="campur" {{ old('type', $room->type) == 'campur' ? 'selected' : '' }} class="bg-slate-900">Campur</option>
                        </select>
                        @error('type')
                        <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Kapasitas -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">
                            Kapasitas Orang <span class="text-orange-400"></span>
                        </label>
                        <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" min="1" max="10"
                               class="w-full bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 placeholder:text-slate-500 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('capacity') border-red-500 @enderror"
                               required>
                        @error('capacity')
                        <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Ukuran -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Ukuran (m²)</label>
                        <input type="number" name="size" value="{{ old('size', $room->size) }}" step="0.01" min="0"
                               class="w-full bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 placeholder:text-slate-500 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('size') border-red-500 @enderror">
                        @error('size')
                        <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Harga -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">
                            Harga Sewa (Rp) <span class="text-orange-400"></span>
                        </label>
                        <input type="number" name="price" value="{{ old('price', $room->price) }}" min="0"
                               class="w-full bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 placeholder:text-slate-500 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('price') border-red-500 @enderror"
                               required>
                        @error('price')
                        <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Jenis Sewa-->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-200 mb-2">
                            Jenis Sewa <span class="text-orange-400"></span>
                        </label>
                        <select name="jenis_sewa" 
                                class="w-full bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('jenis_sewa') border-red-500 @enderror"
                                required>
                            <option value="" class="bg-slate-900 text-slate-400">Pilih Jenis Sewa</option>
                            <option value="bulan" {{ old('jenis_sewa', $room->jenis_sewa) == 'bulan' ? 'selected' : '' }} class="bg-slate-900">Per Bulan</option>
                            <option value="tahun" {{ old('jenis_sewa', $room->jenis_sewa) == 'tahun' ? 'selected' : '' }} class="bg-slate-900">Per Tahun</option>
                        </select>
                        @error('jenis_sewa')
                        <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                        <div class="mt-3 p-3 bg-yellow-500/10 border-2 border-yellow-500/40 rounded-lg">
                            <p class="text-sm text-slate-200 items-center">
                                Periode sewa saat ini: <strong class="text-orange-400 font-bold">{{ $room->jenis_sewa_label }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Ada Jendela -->
                <div class="mt-6 pt-6 border-t-2 border-slate-700/50">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="has_window" value="1" 
                               {{ old('has_window', $room->has_window) ? 'checked' : '' }}
                               id="has_window"
                               class="w-5 h-5
                                    accent-amber-500
                                    bg-slate-700 border-slate-600
                                    rounded
                                    focus:ring-2 focus:ring-amber-500
                                    focus:ring-offset-0
                                    mt-0.5">
                        <span class="ml-3 text-base font-semibold text-slate-200">
                            Kamar memiliki jendela
                        </span>
                    </label>
                </div>
            </div>
            
            <!-- Deskripsi -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                    </div>
                    Deskripsi & Catatan
                </h3>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">Deskripsi Kamar</label>
                        <textarea name="description" rows="6"
                                  class="w-full bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 placeholder:text-slate-500 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200 resize-none
                                   @error('description') border-red-500 @enderror"
                                  placeholder="Deskripsikan kondisi dan keunggulan kamar...">{{ old('description', $room->description) }}</textarea>
                        @error('description')
                        <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">
                            Catatan Khusus (Internal)
                            <span class="text-slate-400 text-xs font-normal ml-1">- Hanya admin yang bisa melihat</span>
                        </label>
                        <textarea name="notes" rows="4"
                                  class="w-full bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 placeholder:text-slate-500 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200 resize-none
                                   @error('notes') border-red-500 @enderror"
                                  placeholder="Catatan internal untuk admin...">{{ old('notes', $room->notes) }}</textarea>
                        @error('notes')
                        <p class="text-red-400 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Fasilitas -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    Fasilitas Kamar
                </h3>
                
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-200 mb-2">Tambah Fasilitas</label>
                    <div class="flex gap-2">
                        <input type="text" x-model="newFacility" @keydown.enter.prevent="addFacility"
                               class="flex-1 bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 placeholder:text-slate-500 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200"
                               placeholder="Contoh: AC, WiFi, Lemari">
                        <button type="button" @click="addFacility"
                                class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-bold rounded-lg 
                                hover:from-yellow-600 hover:to-orange-700 transition-all duration-200 shadow-lg hover:shadow-orange-500/25 hover:scale-105
                                flex items-center">
                            <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah
                        </button>
                    </div>
                    <p class="text-sm text-slate-400 mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Tekan Enter atau klik tombol Tambah
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-2.5 min-h-[80px] mb-5 p-4 bg-slate-900/60 rounded-lg border-2 border-slate-700/50">
                    <template x-for="(facility, index) in facilities" :key="index">
                        <div class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-slate-700 to-slate-600 text-white rounded-lg border-2 border-slate-500 hover:border-yellow-400 transition-all duration-200 shadow-lg group">
                            <span class="text-sm font-semibold" x-text="facility"></span>
                            <input type="hidden" name="facilities[]" :value="facility">
                            <button type="button" @click="removeFacility(index)" 
                                    class="text-slate-300 hover:text-white transition-colors ml-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <div x-show="facilities.length === 0" class="text-slate-400 text-sm py-3 w-full text-center font-medium">
                        Belum ada fasilitas ditambahkan
                    </div>
                </div>
                
            </div>

            <!-- Status & Maintenance -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Status & Maintenance
                </h3>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">
                            Status Kamar <span class="text-orange-400"></span>
                        </label>
                        <select name="status" 
                                class="w-full bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('status') border-red-500 @enderror"
                                required>
                            <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }} class="bg-slate-900">Tersedia</option>
                            <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }} class="bg-slate-900">Terisi</option>
                            <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }} class="bg-slate-900">Maintenance</option>
                        </select>
                        
                        @if($room->currentRent)
                        <div class="mt-3 p-3 bg-yellow-500/10 border-2 border-yellow-500/40 rounded-lg">
                            <span class="text-sm font-semibold text-slate-300 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-slate-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm font-semibold text-slate-200">
                                    Disewa oleh: <strong class="text-orange-400 font-bold">{{ $room->currentRent->user->name }}</strong>
                                </p>
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-200 mb-2">
                            Tanggal Terakhir Maintenance
                        </label>
                        <input type="date" name="last_maintenance" 
                               value="{{ old('last_maintenance', $room->last_maintenance?->format('Y-m-d')) }}"
                               class="w-full bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200 [color-scheme:dark]
                                   @error('last_maintenance') border-red-500 @enderror">
                    </div>
                    
                    <div class="p-4 bg-slate-900/60 rounded-lg border-2 border-slate-700/50">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-300 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Total Dilihat:
                            </span>
                            <span class="text-lg font-bold text-orange-400">{{ $room->view_count }} kali</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Foto Kamar - ADVANCED PHOTO MANAGEMENT -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl xl:col-span-2">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    Kelola Foto Kamar
                    <span class="ml-auto px-4 py-1.5 rounded-full text-sm font-bold" :class="existingCount > 0 ? 'bg-green-500/20 text-green-300 border-2 border-green-500/40' : 'bg-red-500/20 text-red=-300 border-2 border-red-500/40'">
                        <span x-text="existingCount"></span> foto saat ini
                    </span>
                </h3>
            
                @php
                    $images = is_array($room->images) ? $room->images : (json_decode($room->images ?? '[]', true) ?? []);
                @endphp

                <!-- Existing Images Management -->
                @if(!empty($images) && count($images) > 0)
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-base font-bold text-slate-200 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                            Foto yang Ada ({{ count($images) }})
                        </label>
                        <button type="button" 
                                @click="clearAllSelection()"
                                x-show="markedForDeletion.length > 0"
                                class="text-sm font-bold text-orange-400 hover:text-orange-300 transition-colors px-4 py-2 bg-orange-500/10 rounded-lg border-2 border-orange-500/30 hover:border-orange-400">
                            Batal Semua Pilihan
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @foreach($images as $index => $image)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="Kamar {{ $room->room_number }}" 
                                 class="w-full aspect-square object-cover rounded-xl border-3 shadow-xl transition-all duration-300 group-hover:scale-105"
                                 :class="markedForDeletion.includes({{ $index }}) ? 'border-red-500 opacity-60' : 'border-slate-600'">
                        
                            <!-- Hover Overlay for Selection -->
                            <div class="absolute inset-0 bg-black transition-all duration-200 rounded-xl flex flex-col items-center justify-center"
                                 :class="markedForDeletion.includes({{ $index }}) ? 'bg-opacity-70' : 'bg-opacity-0 group-hover:bg-opacity-60'">
                                
                                <!-- Checkbox -->
                                <label class="cursor-pointer flex flex-col items-center space-y-2"
                                       :class="markedForDeletion.includes({{ $index }}) || markedForDeletion.length === 0 ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'">
                                    <input type="checkbox" 
                                           name="remove_images[]" 
                                           value="{{ $index }}" 
                                           class="w-7 h-7 rounded-lg bg-slate-700 border-2 border-slate-500 text-orange-500 focus:ring-orange-500 focus:ring-2"
                                           @change="toggleRemove($event, {{ $index }})">
                                    <span class="text-white text-sm font-bold px-3 py-1 bg-slate-900/80 rounded-lg">
                                        <span x-show="!markedForDeletion.includes({{ $index }})">Pilih untuk Hapus</span>
                                        <span x-show="markedForDeletion.includes({{ $index }})">Akan Dihapus</span>
                                    </span>
                                </label>
                            </div>
                        
                            <!-- Number Badge -->
                            <div class="absolute top-3 left-3 bg-gradient-to-br from-orange-500 to-orange-600 text-white text-sm font-black px-3 py-1.5 rounded-lg shadow-lg">
                                #{{ $index + 1 }}
                            </div>
                            
                            <!-- Cover Badge -->
                            @if($index === 0)
                            <div class="absolute top-3 right-3 bg-gradient-to-br from-green-500 to-green-600 text-white text-xs font-black px-3 py-1.5 rounded-lg shadow-lg animate-pulse">
                                ★ COVER
                            </div>
                            @endif
                            
                            <!-- Delete Indicator Overlay -->
                            <div x-show="markedForDeletion.includes({{ $index }})" 
                                 x-transition
                                 class="absolute inset-0 bg-red-600 bg-opacity-70 rounded-xl flex flex-col items-center justify-center">
                                <svg class="w-14 h-14 text-white mb-2 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span class="text-white text-sm font-black bg-red-700 px-4 py-2 rounded-lg">AKAN DIHAPUS</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Deletion Info -->
                    <div x-show="markedForDeletion.length > 0" 
                         x-transition
                         class="mt-5 p-5 bg-red-500/20 border-3 border-red-500 rounded-xl">
                        <div class="flex items-start gap-4">
                            <svg class="w-6 h-6 text-red-300 flex-shrink-0 mt-0.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-base font-black text-red-200">
                                    <span x-text="markedForDeletion.length"></span> foto akan dihapus permanen
                                </p>
                                <p class="text-sm text-red-300 mt-1.5 font-semibold">
                                    Foto yang dihapus tidak dapat dikembalikan. Pastikan Anda telah memilih foto yang benar.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-sm text-slate-400 mt-4 flex items-center font-medium">
                        <svg class="w-5 h-5 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Klik pada foto untuk memilih foto yang ingin dihapus
                    </p>
                </div>
                @else
                <div class="mb-6 p-12 bg-slate-900/60 border-3 border-dashed border-slate-600 rounded-xl text-center">
                    <svg class="w-20 h-20 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-slate-300 font-bold text-lg">Belum ada foto untuk kamar ini</p>
                    <p class="text-sm text-slate-500 mt-2 font-medium">Upload foto baru di bawah</p>
                </div>
                @endif
        
                <!-- Upload New Images Section -->
                <div class="pt-6 border-t-3 border-slate-700/50">
                    <label class="block text-base font-bold text-slate-200 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Upload Foto Baru
                        <span class="ml-3 px-4 py-1 bg-slate-500/20 text-slate-300 rounded-full text-sm font-bold border-2 border-slate-500/40">
                            Tersisa <span x-text="availableSlots" class="text-orange-200"></span> slot
                        </span>
                    </label>
                    
                    <!-- Upload Button -->
                    <div class="mb-5">
                        <label for="new-images-input" 
                               class="cursor-pointer inline-flex items-center px-8 py-4 bg-gradient-to-r from-slate-600 via-slate-700 to-slate-600 text-white rounded-xl hover:from-slate-700 hover:via-slate-800 hover:to-slate-700 transition-all shadow-2xl hover:shadow-slate-500/30 hover:scale-105 font-bold">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span x-text="imagePreviews.length > 0 ? ' ' + imagePreviews.length + ' Foto Baru Dipilih' : 'Pilih Foto Baru'"></span>
                        </label>
                        <input type="file" 
                               id="new-images-input"
                               name="images[]" 
                               multiple 
                               accept="image/jpeg,image/png,image/jpg" 
                               @change="previewImages"
                               class="hidden">
                    </div>
                    
                    <!-- Info Box -->
                    <div class="flex items-start space-x-3 bg-yellow-400/10 border-2 border-yellow-400/40 rounded-xl p-4 mb-5">
                        <svg class="w-6 h-6 text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-black text-slate-200 mb-2">Informasi Upload:</p>
                            <ul class="space-y-1.5 text-slate-200 text-sm font-semibold">
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-slate-400 rounded-full mr-2"></span>
                                    Format: JPG, JPEG, PNG (Max 5MB/file)
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-slate-400 rounded-full mr-2"></span>
                                    Total maksimal: 10 foto per kamar
                                </li>
                                <li class="flex items-center">
                                    <span class="w-2 h-2 bg-slate-400 rounded-full mr-2"></span>
                                    Foto baru akan ditambahkan setelah foto yang ada
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    @error('images')
                    <div class="mb-5 p-4 bg-red-500/20 border-2 border-red-500 rounded-xl text-red-200 font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </div>
                    @enderror
                
                    <!-- New Image Previews -->
                    <div x-show="imagePreviews.length > 0" 
                         x-transition
                         class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <template x-for="(preview, index) in imagePreviews" :key="index">
                            <div class="relative group">
                                <img :src="preview" 
                                     class="w-full aspect-square object-cover rounded-xl border-3 border-green-400 shadow-xl transition-all duration-300 group-hover:scale-105">
                                
                                <!-- New Badge -->
                                <div class="absolute top-3 right-3 bg-gradient-to-br from-green-500 to-green-600 text-white text-xs font-black px-3 py-1.5 rounded-lg shadow-lg animate-pulse">
                                    BARU
                                </div>
                                
                                <!-- Position Number -->
                                <div class="absolute top-3 left-3 bg-gradient-to-br from-blue-400 to-indigo-600 text-white text-sm font-black px-3 py-1.5 rounded-lg shadow-lg">
                                    #<span x-text="existingCount + index + 1"></span>
                                </div>
                                
                                <!-- Delete Button -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-70 transition-all duration-200 rounded-xl flex items-center justify-center">
                                    <button type="button" 
                                            @click="removePreview(index)"
                                            class="opacity-0 group-hover:opacity-100 bg-red-600 text-white p-3 rounded-full hover:bg-red-700 transition-all transform hover:scale-125 shadow-xl">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Update Summary -->
                <div x-show="markedForDeletion.length > 0 || imagePreviews.length > 0" 
                     x-transition
                     class="mt-6 p-6 bg-gradient-to-br bg-yellow-400/10 border-2 border-yellow-400/40 rounded-xl">
                    <div class="flex items-start space-x-4">
                        <svg class="w-8 h-8 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="font-black text-slate-200 mb-4 text-lg">Ringkasan Perubahan Foto:</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-slate-900/60 rounded-xl p-4 border-2 border-slate-700">
                                    <p class="text-slate-400 mb-2 font-bold text-sm">Foto Sekarang</p>
                                    <p class="text-3xl font-black text-slate-200" x-text="existingCount"></p>
                                </div>
                                <div class="bg-slate-900/60 rounded-xl p-4 border-2 border-red-500/40">
                                    <p class="text-red-400 mb-2 font-bold text-sm">Akan Dihapus</p>
                                    <p class="text-3xl font-black text-red-400" x-text="markedForDeletion.length"></p>
                                </div>
                                <div class="bg-slate-900/60 rounded-xl p-4 border-2 border-green-500/40">
                                    <p class="text-green-400 mb-2 font-bold text-sm">Foto Baru</p>
                                    <p class="text-3xl font-black text-green-400" x-text="imagePreviews.length"></p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t-2 border-yellow-500/30">
                                <p class="text-slate-200 font-bold text-lg">
                                    <span class="text-2xl font-black text-slate-300" x-text="totalAfterUpdate"></span> 
                                    <span>foto setelah update</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex justify-end space-x-4 mt-6">
            <!-- Tombol Batal -->
            <a href="{{ route('admin.rooms.index') }}"
               class="w-full sm:w-auto px-8 py-4 border-3 border-slate-600 text-slate-300 bg-slate-800 rounded-xl hover:bg-slate-700 hover:border-slate-500 transition-all font-bold flex items-center justify-center shadow-lg hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Batal
            </a>
         
            <!-- Tombol Simpan -->
            <button type="submit"
                    class="inline-flex items-center gap-2
                    bg-gradient-to-r from-yellow-500 to-orange-600
                    text-white font-semibold
                    px-5 py-3 rounded-lg
                    hover:from-yellow-600 hover:to-orange-700
                    transition-all shadow-lg">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Perubahan
            </button>
        </div>
        
    </form>
    
</div>

@endsection

@push('scripts')
<script>
function roomEditForm() {
    return {
        facilities: @json(old('facilities', is_array($room->facilities) ? $room->facilities : json_decode($room->facilities ?? '[]', true))),
        newFacility: '',
        imagePreviews: [],
        markedForDeletion: [],
        existingCount: {{ count($images) }},
        
        get availableSlots() {
            return Math.max(0, 10 - (this.existingCount - this.markedForDeletion.length + this.imagePreviews.length));
        },
        
        get totalAfterUpdate() {
            return this.existingCount - this.markedForDeletion.length + this.imagePreviews.length;
        },
        
        // Facilities Management
        addFacility() {
            const facility = this.newFacility.trim();
            if (facility && !this.facilities.includes(facility)) {
                this.facilities.push(facility);
                this.newFacility = '';
            } else if (this.facilities.includes(facility)) {
                alert('Fasilitas "' + facility + '" sudah ada!');
            }
        },
        
        addQuickFacility(facility) {
            if (!this.facilities.includes(facility)) {
                this.facilities.push(facility);
            }
        },
        
        removeFacility(index) {
            this.facilities.splice(index, 1);
        },
        
        // Image Deletion Management
        toggleRemove(event, index) {
            if (event.target.checked) {
                if (!this.markedForDeletion.includes(index)) {
                    this.markedForDeletion.push(index);
                }
            } else {
                const idx = this.markedForDeletion.indexOf(index);
                if (idx > -1) {
                    this.markedForDeletion.splice(idx, 1);
                }
            }
        },
        
        clearAllSelection() {
            this.markedForDeletion = [];
            // Uncheck all checkboxes
            document.querySelectorAll('input[name="remove_images[]"]').forEach(cb => cb.checked = false);
        },
        
        // New Images Preview
        previewImages(event) {
            this.imagePreviews = [];
            const files = event.target.files;
            
            const currentCount = this.existingCount - this.markedForDeletion.length;
            const availableSlots = 10 - currentCount;
            const maxFiles = Math.min(files.length, availableSlots);
            
            if (files.length > availableSlots) {
                alert(`Hanya bisa menambah ${availableSlots} foto lagi!\nTotal maksimal 10 foto per kamar.`);
            }
            
            for (let i = 0; i < maxFiles; i++) {
                const file = files[i];
                
                // Validate file size
                if (file.size > 5120 * 1024) {
                    alert(`File "${file.name}" terlalu besar!\nMaksimal 5MB per file.`);
                    continue;
                }
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreviews.push(e.target.result);
                };
                reader.readAsDataURL(file);
            }
        },
        
        removePreview(index) {
            this.imagePreviews.splice(index, 1);
            
            if (this.imagePreviews.length === 0) {
                document.getElementById('new-images-input').value = '';
            }
        }
    }
}
</script>
@endpush