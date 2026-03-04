@extends('layouts.admin')

@section('title', 'Tambah Informasi Kos')
@section('page-title', 'Tambah Informasi Kos')
@section('page-description', 'Tambahkan data kos baru dengan informasi lengkap')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    /* Fix for Leaflet controls ignoring container border radius */
    .leaflet-container {
        border-radius: 0.75rem;
        z-index: 10;
    }
</style>
@endpush

@section('content')

<div class="w-full mx-auto">

    {{-- Alert --}}
    @if(session('error'))
    <div class="mb-6 bg-red-900/50 border border-red-500 rounded-xl p-4 flex items-center">
        <svg class="w-5 h-5 text-red-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-red-300 font-medium">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.kos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Informasi Umum -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <div class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Informasi Umum</h2>
                        <p class="text-slate-400 text-sm">Data dasar tentang kos Anda</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Nama Kos <span class="text-red-400"></span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                            placeholder="Contoh: KosSmart Residence">
                        @error('name')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Deskripsi Kos
                        </label>
                        <textarea name="description" rows="4"
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                            placeholder="Deskripsikan kos Anda, lokasi strategis, fasilitas yang tersedia...">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Alamat & Lokasi -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <div class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Alamat & Lokasi</h2>
                        <p class="text-slate-400 text-sm">Informasi lokasi kos Anda</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Alamat Lengkap <span class="text-red-400"></span>
                        </label>
                        <textarea name="address" id="address_input" rows="4" required
                            class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                            placeholder="Jl. Contoh No. 123, Kelurahan...">{{ old('address') }}</textarea>
                        @error('address')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Kota <span class="text-red-400"></span>
                            </label>
                            <input type="text" name="city" id="city_input" value="{{ old('city') }}" required
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                placeholder="Bandung">
                            @error('city')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Provinsi <span class="text-red-400"></span>
                            </label>
                            <input type="text" name="province" id="province_input" value="{{ old('province') }}" required
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                placeholder="Jawa Barat">
                            @error('province')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Kode Pos
                            </label>
                            <input type="text" name="postal_code" id="postal_code_input" value="{{ old('postal_code') }}"
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                placeholder="40123">
                            @error('postal_code')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Peta Lokasi -->
                    <div class="mt-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-slate-300">
                                Titik Lokasi Peta (Opsional tapi disarankan)
                            </label>
                            <button type="button" id="btn_search_map" class="text-xs bg-slate-600 hover:bg-slate-500 text-white px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Cari dari Alamat
                            </button>
                        </div>
                        <p class="text-xs text-slate-400 mb-3">Geser penanda biru (marker) atau klik pada peta untuk menentukan lokasi akurat kos Anda. Anda juga bisa mengklik tombol "Cari dari Alamat" untuk otomatis menggeser peta.</p>
                        <div id="map" class="w-full h-80 rounded-xl border-2 border-slate-600 relative">
                            <!-- Loading Overlay for Map -->
                            <div id="map_loading" class="hidden absolute inset-0 bg-slate-800/50 z-[400] flex items-center justify-center backdrop-blur-sm">
                                <span class="text-white font-medium flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memuat...
                                </span>
                            </div>
                        </div>
                        <!-- Nilai default diarahkan ke kota Bandung -->
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', '-6.914744') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', '107.609810') }}">
                    </div>
                </div>
            </div>

            <!-- Kontak -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <div class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Informasi Kontak</h2>
                        <p class="text-slate-400 text-sm">Cara menghubungi pengelola kos</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-slate-400 mb-2">
                                Telepon <span class="text-red-400"></span>
                            </label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                placeholder="022-1234567">
                            @error('phone')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-400 mb-2">
                                WhatsApp
                            </label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp') }}"
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                placeholder="081234567890">
                            @error('whatsapp')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-400 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                placeholder="info@kos.com">
                            @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Foto Kos -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <div class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Foto Kos</h2>
                        <p class="text-slate-400 text-sm">Upload foto untuk menarik calon penghuni</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="border-2 border-dashed border-slate-600 rounded-xl p-8 text-center hover:border-yellow-500 transition-colors bg-slate-700/30">
                        <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden" onchange="previewImages(event)">
                        <label for="images" class="cursor-pointer">
                            <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-white font-semibold mb-1">Klik untuk upload foto</p>
                            <p class="text-slate-400 text-sm">atau drag & drop di sini</p>
                            <p class="text-slate-500 text-xs mt-2">PNG, JPG, JPEG (Maks. 5MB per foto, max 6 foto)</p>
                        </label>
                    </div>
                    <div id="imagePreview" class="grid grid-cols-3 gap-3 mt-4"></div>
                    @error('images.*')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Fasilitas & Peraturan -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <div class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Fasilitas & Peraturan</h2>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Fasilitas Umum -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-400 mb-2">
                            Fasilitas Umum
                        </label>
                        <div id="facilities-wrapper" class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <input type="text" name="general_facilities[]"
                                    class="flex-1 bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                    placeholder="Contoh: WiFi 100 Mbps">
                                <button type="button" onclick="addFacility()"
                                    class="inline-flex items-center gap-2
                                        bg-gradient-to-r from-yellow-500 to-orange-600
                                        text-white font-semibold
                                        px-3 py-2 rounded-lg
                                        hover:from-yellow-600 hover:to-orange-700
                                        transition-all shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">Klik tombol + untuk menambah fasilitas lainnya</p>
                    </div>
                    <!-- Peraturan Kos -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-400 mb-2">
                            Peraturan Kos
                        </label>
                        <div id="rules-wrapper" class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <input type="text" name="rules[]"
                                    class="flex-1 bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
                                    placeholder="Contoh: Jam malam maksimal pukul 22.00 WIB">
                                <button type="button" onclick="addRule()"
                                    class="inline-flex items-center gap-2
                                        bg-gradient-to-r from-yellow-500 to-orange-600
                                        text-white font-semibold
                                        px-3 py-2 rounded-lg
                                        hover:from-yellow-600 hover:to-orange-700
                                        transition-all shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">Klik tombol + untuk menambah peraturan lainnya</p>
                    </div>
                </div>
            </div>

            <!-- Jam Operasional -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <div class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Jam Operasional</h2>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-400 mb-2">
                            Waktu Check-in <span class="text-red-400"></span>
                        </label>
                        <input type="time" name="checkin_time" required
                            class="w-full bg-slate-900 text-slate-100 border border-slate-700 rounded-xl px-4 py-2 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all [color-scheme:dark]">
                        @error('checkin_time')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-400 mb-2">
                            Waktu Check-out <span class="text-red-400"></span>
                        </label>
                        <input type="time" name="checkout_time" required
                            class="w-full bg-slate-900 text-slate-100 border border-slate-700 rounded-xl px-4 py-2 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all [color-scheme:dark]">
                        @error('checkout_time')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Aktif -->
        <div class="mt-6 bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-xl border border-slate-700 overflow-hidden">
            <div class="p-6">
                <label class="flex items-start space-x-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="w-5 h-5
                               accent-amber-500
                               bg-slate-700 border-slate-600
                               rounded
                               focus:ring-2 focus:ring-amber-500
                               focus:ring-offset-0
                               mt-0.5">
                    <div>
                        <span class="text-sm font-semibold text-white block">
                            Jadikan kos ini sebagai kos aktif
                        </span>
                        <p class="text-xs text-slate-400 mt-1">
                            Jika dicentang, kos ini akan langsung ditampilkan ke calon penghuni dan kos lain yang aktif akan otomatis dinonaktifkan.
                        </p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ route('admin.kos.index') }}"
                class="px-6 py-3 bg-slate-700 text-white rounded-xl hover:bg-slate-600 transition-colors font-medium">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2
                    bg-gradient-to-r from-yellow-500 to-orange-600
                    text-white font-semibold
                    px-5 py-2 rounded-lg
                    hover:from-yellow-600 hover:to-orange-700
                    transition-all shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Simpan Informasi Kos</span>
            </button>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Leaflet Map Initialization & Geocoding
    document.addEventListener('DOMContentLoaded', function() {
        let lat = document.getElementById('latitude').value || -6.914744;
        let lng = document.getElementById('longitude').value || 107.609810;

        const map = L.map('map').setView([lat, lng], 14);
        const mapLoading = document.getElementById('map_loading');

        // DOM Elements for inputs
        const addressInput = document.getElementById('address_input');
        const cityInput = document.getElementById('city_input');
        const provinceInput = document.getElementById('province_input');
        const postalCodeInput = document.getElementById('postal_code_input');

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);

        // Reverse Geocoding: Dari Lat, Lng ke Alamat
        async function reverseGeocode(lat, lng) {
            mapLoading.classList.remove('hidden');
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`, {
                    headers: {
                        'Accept-Language': 'id'
                    }
                });
                const data = await response.json();

                if (data && data.address) {
                    const addr = data.address;

                    // Mencoba mendapatkan jalan / nama area
                    let street = addr.road || addr.pedestrian || addr.path || addr.suburb || addr.village || addr.neighbourhood || '';
                    let houseNumber = addr.house_number ? `No. ${addr.house_number}` : '';
                    let fullAddress = street ? `${street} ${houseNumber}`.trim() : data.display_name.split(',')[0];

                    // Mencoba mendapatkan Kota / Kabupaten
                    let city = addr.city || addr.town || addr.municipality || addr.county || '';
                    if (city.toLowerCase().startsWith('kota ')) {
                        city = city.substring(5);
                    } // Hapus awalan 'Kota ' agar lebih rapi

                    let province = addr.state || addr.province || '';
                    let postcode = addr.postcode || '';

                    // Hanya timpa isi form jika kosong ATAU kita secara eksplisit ingin menimpanya
                    // Di fungsi dragend ini, lebih baik di-set agar interaktif dan user tahu apa yg terdeteksi
                    if (fullAddress) addressInput.value = fullAddress + (addr.suburb ? `, ${addr.suburb}` : '');
                    if (city) cityInput.value = city;
                    if (province) provinceInput.value = province;
                    if (postcode) postalCodeInput.value = postcode;
                }
            } catch (error) {
                console.error("Gagal reverse geocode:", error);
            } finally {
                mapLoading.classList.add('hidden');
            }
        }

        // Geocoding: Dari Alamat ke Lat, Lng
        async function geocodeAddress() {
            const queryParts = [addressInput.value, cityInput.value, provinceInput.value].filter(val => val.trim() !== '');
            if (queryParts.length === 0) return;

            let query = queryParts.join(', ') + ', Indonesia'; // Tambahkan Indonesia agar spesifik

            mapLoading.classList.remove('hidden');
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`, {
                    headers: {
                        'Accept-Language': 'id'
                    }
                });
                const data = await response.json();

                if (data && data.length > 0) {
                    const resultLat = parseFloat(data[0].lat);
                    const resultLon = parseFloat(data[0].lon);

                    // Pindah Peta
                    map.setView([resultLat, resultLon], 16);
                    marker.setLatLng([resultLat, resultLon]);

                    // Update Hidden Inputs
                    document.getElementById('latitude').value = resultLat;
                    document.getElementById('longitude').value = resultLon;
                } else {
                    alert('Lokasi dari alamat yang dimasukkan tidak dapat ditemukan di peta. Coba perjelas alamat atau kota.');
                }
            } catch (error) {
                console.error("Gagal geocode:", error);
            } finally {
                mapLoading.classList.add('hidden');
            }
        }

        // Event Listener untuk Tombol Pencarian
        document.getElementById('btn_search_map').addEventListener('click', geocodeAddress);

        // Update form inputs when marker is dragged
        marker.on('dragend', function(e) {
            const position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;

            // Panggil Reverse Geocoding
            reverseGeocode(position.lat, position.lng);
        });

        // Update form inputs & marker position when map is clicked
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;

            // Pindah Map View agar lebih nyaman
            map.flyTo(e.latlng, map.getZoom());

            // Panggil Reverse Geocoding
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });
    });
</script>
<script>
    function previewImages(event) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        const files = event.target.files;

        if (files.length > 6) {
            alert('Maksimal 6 foto');
            event.target.value = '';
            return;
        }

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center">
                    <div class="absolute top-2 right-2 bg-green-500 text-white rounded-full p-1 shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            `;
                preview.appendChild(div);
            };

            reader.readAsDataURL(file);
        }
    }

    function addFacility() {
        const container = document.getElementById('facilities-wrapper');
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2';

        div.innerHTML = `
        <input type="text" name="general_facilities[]"
            class="flex-1 bg-slate-700/50 border border-slate-600 rounded-lg
                   px-3 py-2 text-white placeholder-slate-400 text-sm
                   focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
            placeholder="Tambah fasilitas...">

        <button type="button"
            onclick="this.parentElement.remove()"
            class="inline-flex items-center justify-center
                   bg-red-500/20 text-red-400
                   hover:bg-red-500 hover:text-white
                   rounded-lg
                   px-3 py-2
                   transition-all shadow-sm"
            title="Hapus fasilitas">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
        container.appendChild(div);
    }

    function addRule() {
        const container = document.getElementById('rules-wrapper');

        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';

        div.innerHTML = `
        <input type="text" name="rules[]"
            class="flex-1 bg-slate-700/50 border border-slate-600 rounded-lg
                   px-3 py-2 text-white placeholder-slate-400 text-sm
                   focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all"
            placeholder="Tambah peraturan...">

        <button type="button"
            onclick="this.parentElement.remove()"
            class="inline-flex items-center justify-center
                   bg-red-500/20 text-red-400
                   hover:bg-red-500 hover:text-white
                   rounded-lg
                   px-3 py-2
                   transition-all shadow-sm"
            title="Hapus peraturan">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;

        container.appendChild(div);
    }
</script>
@endpush