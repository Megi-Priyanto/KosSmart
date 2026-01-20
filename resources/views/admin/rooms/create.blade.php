@extends('layouts.admin')

@section('title', 'Tambah Kamar ' . ($room->room_number ?? 'Baru'))
@section('page-title', 'Tambah Kamar ' . ($room->room_number ?? ''))
@section('page-description', 'Kelola Informasi Kamar')

@section('content')

<div class="w-full mx-auto">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.rooms.index') }}" 
           class="px-5 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
            Kembali ke Daftar Kamar
        </a>
    </div>
    
    <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data" x-data="roomForm()">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
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
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Nomor Kamar <span class="text-red-400"></span>
                        </label>
                        <input type="text" name="room_number" value="{{ old('room_number') }}"
                               class="w-full bg-slate-900/50 text-white border border-slate-600 rounded-lg
                                   px-4 py-2.5 placeholder:text-slate-500
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('room_number') border-red-500 @enderror"
                               placeholder="101" required>
                        @error('room_number')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lantai -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Lantai <span class="text-red-400"></span>
                        </label>
                        <input type="text" name="floor" value="{{ old('floor') }}"
                               class="w-full bg-slate-900/50 text-white border border-slate-600 rounded-lg
                                   px-4 py-2.5 placeholder:text-slate-500
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('floor') border-red-500 @enderror"
                               placeholder="Lantai 1" required>
                        @error('floor')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Tipe Kamar <span class="text-red-400"></span>
                        </label>
                        <select name="type" 
                                class="w-full bg-slate-900/50 text-white border border-slate-600 rounded-lg
                                   px-4 py-2.5 placeholder:text-slate-500
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('type') border-red-500 @enderror"
                                required>
                            <option value="" class="bg-slate-900 text-slate-400">Pilih Tipe Kamar</option>
                            <option value="putra" {{ old('type') == 'putra' ? 'selected' : '' }} class="bg-slate-900">Putra</option>
                            <option value="putri" {{ old('type') == 'putri' ? 'selected' : '' }} class="bg-slate-900">Putri</option>
                            <option value="campur" {{ old('type') == 'campur' ? 'selected' : '' }} class="bg-slate-900">Campur</option>
                        </select>
                        @error('type')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kapasitas -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Kapasitas Orang <span class="text-red-400"></span>
                        </label>
                        <input type="number" name="capacity" value="{{ old('capacity', 1) }}" min="1" max="10"
                               class="w-full bg-slate-900/50 text-white border border-slate-600 rounded-lg
                                   px-4 py-2.5 placeholder:text-slate-500
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('capacity') border-red-500 @enderror"
                               required>
                        @error('capacity')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ukuran -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Ukuran (m²) <span class="text-red-400"></span>
                        </label>
                        <input type="number" name="size" value="{{ old('size') }}" step="0.01" min="0"
                               class="w-full bg-slate-900/50 text-white border border-slate-600 rounded-lg
                                   px-4 py-2.5 placeholder:text-slate-500
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('size') border-red-500 @enderror"
                               placeholder="18.00" required>
                        @error('size')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Sewa -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Harga Sewa (Rp) <span class="text-red-400"></span>
                        </label>
                        <input type="number" name="price" value="{{ old('price') }}" min="0"
                               class="w-full bg-slate-900/50 text-white border border-slate-600 rounded-lg
                                   px-4 py-2.5 placeholder:text-slate-500
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('price') border-red-500 @enderror"
                               placeholder="1700000.00" required>
                        @error('price')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Sewa -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Jenis Sewa <span class="text-red-400"></span>
                        </label>
                        <select name="jenis_sewa" 
                                class="w-full bg-slate-900/50 text-white border border-slate-600 rounded-lg
                                   px-4 py-2.5 placeholder:text-slate-500
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('jenis_sewa') border-red-500 @enderror"
                                required>
                            <option value="" class="bg-slate-900 text-slate-400">Pilih Jenis Sewa</option>
                            <option value="bulan" {{ old('jenis_sewa', 'bulan') == 'bulan' ? 'selected' : '' }} class="bg-slate-900">Per Bulan</option>
                            <option value="tahun" {{ old('jenis_sewa') == 'tahun' ? 'selected' : '' }} class="bg-slate-900">Per Tahun</option>
                        </select>
                        @error('jenis_sewa')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div class="mt-2 p-3 bg-slate-500/10 border-2 border-slate-500/30 rounded-lg">
                            <p class="text-xs text-slate-400 mt-1.5">Periode sewa saat ini: ...</p>
                        </div>
                    </div>
                </div>

                <!-- Checkbox Jendela -->
                <div class="mt-6 pt-6 border-t-2 border-slate-700/50">
                    <label class="flex items-center cursor-pointer group">

                        <input type="checkbox" name="has_window" value="1" 
                               {{ old('has_window', true) ? 'checked' : '' }}
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

            <!-- Deskripsi & Catatan -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                    </div>
                    Deskripsi & Catatan
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Deskripsi Kamar</label>
                        <textarea name="description" rows="6"
                                  class="w-full bg-slate-900/50 text-white border border-slate-600 rounded-lg
                                   px-4 py-2.5 placeholder:text-slate-500
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('description') border-red-500 @enderror"
                                  placeholder="Kamar nyaman di lantai 1 dengan fasilitas lengkap">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Catatan Khusus (Internal)</label>
                        <p class="text-xs text-slate-400 mb-2">Hanya admin yang bisa melihat catatan ini</p>
                        <textarea name="notes" rows="5"
                                  class="w-full bg-slate-900/50 text-white border border-slate-600 rounded-lg
                                   px-4 py-2.5 placeholder:text-slate-500
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('notes') border-red-500 @enderror"
                                  placeholder="Catatan internal untuk admin...">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Fasilitas Kamar -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    Fasilitas Kamar
                </h3>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Tambah Fasilitas</label>
                    <div class="flex gap-2">
                        <input type="text" x-model="newFacility" @keydown.enter.prevent="addFacility"
                               class="flex-1 bg-slate-900/50 text-white border border-slate-600 rounded-lg
                                   px-4 py-2.5 placeholder:text-slate-500
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
                    <p class="text-sm text-slate-400 mt-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Tekan Enter atau klik tombol Tambah
                    </p>
                </div>

                <!-- Facilities List -->
                <div class="min-h-[120px] mb-4 p-4 bg-slate-900/30 rounded-lg border border-slate-700">
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(facility, index) in facilities" :key="index">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-700 text-white rounded-md border border-slate-600 hover:bg-slate-600 transition-all duration-200">
                                <span class="text-sm" x-text="facility"></span>
                                <input type="hidden" name="facilities[]" :value="facility">
                                <button type="button" @click="removeFacility(index)" 
                                        class="text-slate-400 hover:text-red-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    <div x-show="facilities.length === 0" class="text-slate-400 text-sm py-4 text-center">
                        Belum ada fasilitas ditambahkan
                    </div>
                </div>

                <!-- Quick Add -->
                <div class="pt-4 border-t border-slate-700">
                    <p class="text-sm font-medium text-slate-300 mb-3">+ Tambah Cepat:</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="addQuickFacility('AC')" 
                                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-slate-700 to-slate-600 text-white rounded-lg border-2 border-slate-500 hover:border-yellow-400 transition-all duration-200 shadow-lg group">
                            AC
                        </button>
                        <button type="button" @click="addQuickFacility('WiFi')" 
                                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-slate-700 to-slate-600 text-white rounded-lg border-2 border-slate-500 hover:border-yellow-400 transition-all duration-200 shadow-lg group">
                            WiFi
                        </button>
                        <button type="button" @click="addQuickFacility('Kamar Mandi Dalam')" 
                                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-slate-700 to-slate-600 text-white rounded-lg border-2 border-slate-500 hover:border-yellow-400 transition-all duration-200 shadow-lg group">
                            Kamar Mandi Dalam
                        </button>
                        <button type="button" @click="addQuickFacility('Lemari')" 
                                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-slate-700 to-slate-600 text-white rounded-lg border-2 border-slate-500 hover:border-yellow-400 transition-all duration-200 shadow-lg group">
                            Lemari
                        </button>
                        <button type="button" @click="addQuickFacility('Kasur')" 
                                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-slate-700 to-slate-600 text-white rounded-lg border-2 border-slate-500 hover:border-yellow-400 transition-all duration-200 shadow-lg group">
                            Kasur
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status & Maintenance -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Status & Maintenance
                </h3>

                <div class="space-y-4">
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Status Kamar <span class="text-red-400"></span>
                        </label>
                        <select name="status" 
                                class="w-full bg-slate-900/50 text-white border border-slate-600 rounded-lg
                                   px-4 py-2.5 placeholder:text-slate-500
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200
                                   @error('status') border-red-500 @enderror"
                                required>
                            <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }} class="bg-slate-900">Tersedia</option>
                            <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }} class="bg-slate-900">Terisi</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }} class="bg-slate-900">Maintenance</option>
                        </select>
                        @error('status')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Maintenance -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Tanggal Terakhir Maintenance
                        </label>
                        <input type="date" name="last_maintenance" value="{{ old('last_maintenance') }}"
                               class="w-full bg-slate-900/80 text-white border-2 border-slate-600 rounded-lg
                                   px-4 py-3 font-medium
                                   focus:ring-2 focus:ring-orange-500 focus:border-orange-500
                                   transition-all duration-200 [color-scheme:dark]
                                   @error('last_maintenance') border-red-500 @enderror">
                        @error('last_maintenance')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Dilihat (Read Only) -->
                    <div class="pt-4 border-t border-slate-700">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-400">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Total Dilihat:
                            </span>
                            <span class="font-semibold text-orange-400"> ..... </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Foto Kamar -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl xl:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                        <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        Kelola Foto Kamar
                    </h3>
                    <button type="button" class="text-orange-400 text-sm hover:text-orange-300">
                        <span x-text="'0 foto saat ini'"></span>
                    </button>
                </div>

                <div class="mb-4 p-4 bg-slate-900/30 border border-slate-700 rounded-lg">
                    <div class="flex items-center justify-center py-8">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-slate-400 mb-4">Belum ada foto untuk kamar ini</p>
                            <label for="images-input" 
                                   class="cursor-pointer inline-flex items-center px-8 py-4 bg-gradient-to-r from-slate-600 via-slate-700 to-slate-600 text-white rounded-xl hover:from-slate-700 hover:via-slate-800 hover:to-slate-700 transition-all shadow-2xl hover:shadow-slate-500/30 hover:scale-105 font-bold">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span x-text="imagePreviews.length > 0 ? imagePreviews.length + ' Foto Dipilih' : 'Upload Foto Baru'"></span>
                            </label>
                            <input type="file" 
                                   id="images-input"
                                   name="images[]" 
                                   multiple 
                                   accept="image/jpeg,image/png,image/jpg" 
                                   @change="previewImages"
                                   class="hidden">
                        </div>
                    </div>
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
                <div class="mt-4 p-3 bg-red-900/30 border border-red-500 rounded-lg text-red-400 text-sm">
                    {{ $message }}
                </div>
                @enderror

                @error('images.*')
                <div class="mt-4 p-3 bg-red-900/30 border border-red-500 rounded-lg text-red-400 text-sm">
                    {{ $message }}
                </div>
                @enderror

                <!-- Image Previews Grid -->
                <div x-show="imagePreviews.length > 0" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-3">
                    <template x-for="(preview, index) in imagePreviews" :key="index">
                        <div class="relative group">
                            <img :src="preview" 
                                 class="w-full aspect-square object-cover rounded-lg border-2 border-slate-600 shadow-lg">

                            <!-- Badge Number -->
                            <div class="absolute top-2 right-2 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg" 
                                 x-text="'#' + (index + 1)">
                            </div>

                            <!-- Cover Badge -->
                            <div x-show="index === 0" 
                                 class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                COVER
                            </div>

                            <!-- Delete Button Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-200 rounded-lg flex items-center justify-center">
                                <button type="button" 
                                        @click="removePreview(index)"
                                        class="opacity-0 group-hover:opacity-100 bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-all transform hover:scale-110 shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Upload Summary -->
                <div x-show="imagePreviews.length > 0" 
                     class="mt-4 p-3 bg-slate-900/30 border border-slate-700 rounded-lg text-sm text-slate-300 flex items-center justify-between">
                    <span>
                        <span class="font-semibold text-white" x-text="imagePreviews.length"></span> foto siap diupload
                    </span>
                    <span class="text-slate-400" x-text="'Sisa slot: ' + (10 - imagePreviews.length)"></span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ route('admin.rooms.index') }}" 
               class="w-full sm:w-auto px-6 py-4 border-3 border-slate-600 text-slate-300 bg-slate-800 rounded-xl hover:bg-slate-700 hover:border-slate-500 transition-all font-bold flex items-center justify-center shadow-lg hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Batal
            </a>
            <button type="submit" 
                    class="inline-flex items-center gap-2
                    bg-gradient-to-r from-yellow-500 to-orange-600
                    text-white font-semibold
                    px-5 py-3 rounded-lg
                    hover:from-yellow-600 hover:to-orange-700
                    transition-all shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Kamar
            </button>
        </div>
        
    </form>
    
</div>

@endsection

@push('scripts')
<script>
function roomForm() {
    return {
        facilities: @json(old('facilities', [])),
        newFacility: '',
        imagePreviews: [],
        selectedFiles: null,
        
        addFacility() {
            const facility = this.newFacility.trim();
            if (facility && !this.facilities.includes(facility)) {
                this.facilities.push(facility);
                this.newFacility = '';
            } else if (this.facilities.includes(facility)) {
                alert('Fasilitas "' + facility + '" sudah ditambahkan!');
            }
        },
        
        addQuickFacility(facility) {
            if (!this.facilities.includes(facility)) {
                this.facilities.push(facility);
            } else {
                alert('Fasilitas "' + facility + '" sudah ditambahkan!');
            }
        },
        
        removeFacility(index) {
            this.facilities.splice(index, 1);
        },

        previewImages(event) {
            this.imagePreviews = [];
            const input = event.target;
            const files = Array.from(input.files);

            const dataTransfer = new DataTransfer();

            files.slice(0, 10).forEach(file => {
                if (file.size > 5120 * 1024) {
                    alert(`File "${file.name}" terlalu besar!`);
                    return;
                }
            
                dataTransfer.items.add(file);
            
                const reader = new FileReader();
                reader.onload = e => {
                    this.imagePreviews.push(e.target.result);
                };
                reader.readAsDataURL(file);
            });
        
            input.files = dataTransfer.files;
        },
        
        removePreview(index) {
            const input = document.getElementById('images-input');
            const files = Array.from(input.files);
            const dataTransfer = new DataTransfer();
                
            files.forEach((file, i) => {
                if (i !== index) {
                    dataTransfer.items.add(file);
                }
            });
        
            input.files = dataTransfer.files;
            this.imagePreviews.splice(index, 1);
        }
    }
}
</script>
@endpush