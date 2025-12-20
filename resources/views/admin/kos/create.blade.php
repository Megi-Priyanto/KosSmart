@extends('layouts.admin')

@section('title', 'Tambah Informasi Kos')
@section('page-title', 'Tambah Informasi Kos')
@section('page-description', 'Tambahkan data kos baru dengan informasi lengkap')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Alert --}}
    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.kos.store') }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf

        {{-- Informasi Umum --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Umum
                </h2>
                <p class="text-purple-100 text-sm mt-1">Data dasar tentang kos Anda</p>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kos <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                           placeholder="Contoh: KosSmart Residence">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi Kos
                    </label>
                    <textarea name="description" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                              placeholder="Deskripsikan kos Anda, lokasi strategis, fasilitas yang tersedia...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Alamat & Lokasi --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Alamat & Lokasi
                </h2>
                <p class="text-blue-100 text-sm mt-1">Informasi lokasi kos Anda</p>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" rows="2" required
                              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                              placeholder="Jl. Contoh No. 123, Kelurahan...">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kota <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" value="{{ old('city') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                               placeholder="Bandung">
                        @error('city')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="province" value="{{ old('province') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                               placeholder="Jawa Barat">
                        @error('province')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Pos
                        </label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                               placeholder="40123">
                        @error('postal_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Kontak --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Informasi Kontak
                </h2>
                <p class="text-green-100 text-sm mt-1">Cara menghubungi pengelola kos</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                               placeholder="022-1234567">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            WhatsApp
                        </label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                               placeholder="081234567890">
                        @error('whatsapp')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                               placeholder="info@kos.com">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Fasilitas & Peraturan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Fasilitas & Peraturan
                </h2>
                <p class="text-orange-100 text-sm mt-1">Fasilitas umum dan aturan yang berlaku</p>
            </div>

            <div class="p-6 space-y-6">
                {{-- Fasilitas Umum --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Fasilitas Umum
                    </label>
                    <div id="facilities-wrapper" class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="text" name="general_facilities[]"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                   placeholder="Contoh: WiFi 100 Mbps">
                            <button type="button" onclick="addFacility()" 
                                    class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Klik tombol + untuk menambah fasilitas lainnya</p>
                </div>

                {{-- Peraturan Kos --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Peraturan Kos
                    </label>
                    <div id="rules-wrapper" class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="text" name="rules[]"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                   placeholder="Contoh: Jam malam maksimal pukul 22.00 WIB">
                            <button type="button" onclick="addRule()" 
                                    class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Klik tombol + untuk menambah peraturan lainnya</p>
                </div>
            </div>
        </div>

        {{-- Jam Operasional --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Jam Operasional
                </h2>
                <p class="text-indigo-100 text-sm mt-1">Waktu check-in dan check-out</p>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Check-in <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="checkin_time" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        @error('checkin_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Check-out <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="checkout_time" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        @error('checkout_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Foto Kos --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-pink-600 to-pink-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Foto Kos
                </h2>
                <p class="text-pink-100 text-sm mt-1">Upload foto untuk menarik calon penghuni</p>
            </div>

            <div class="p-6">
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-500 transition-colors">
                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden" onchange="previewImages(event)">
                    <label for="images" class="cursor-pointer">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-gray-700 font-medium mb-1">Klik untuk upload foto</p>
                        <p class="text-gray-500 text-sm">atau drag & drop di sini</p>
                        <p class="text-gray-400 text-xs mt-2">PNG, JPG, JPEG (Maks. 5MB per foto, max 6 foto)</p>
                    </label>
                </div>
                <div id="imagePreview" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4"></div>
                @error('images.*')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Status Aktif --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <label class="flex items-start space-x-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-2 focus:ring-purple-500 mt-0.5">
                    <div>
                        <span class="text-sm font-medium text-gray-700 block">
                            Jadikan kos ini sebagai kos aktif
                        </span>
                        <p class="text-xs text-gray-500 mt-1">
                            Jika dicentang, kos ini akan langsung ditampilkan ke calon penghuni dan kos lain yang aktif akan otomatis dinonaktifkan.
                        </p>
                    </div>
                </label>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('admin.kos.index') }}" 
               class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium flex items-center space-x-2">
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
               class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
               placeholder="Tambah fasilitas...">
        <button type="button" onclick="this.parentElement.remove()" 
                class="px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
}

function addRule() {
    const container = document.getElementById('rules-wrapper');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';
    div.innerHTML = `
        <input type="text" name="rules[]" 
               class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
               placeholder="Tambah peraturan...">
        <button type="button" onclick="this.parentElement.remove()" 
                class="px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
}
</script>
@endpush