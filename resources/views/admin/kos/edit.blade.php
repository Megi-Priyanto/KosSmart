@extends('layouts.admin')

@section('title', 'Edit Informasi Kos')
@section('page-title', 'Edit Informasi Kos')
@section('page-description', 'Perbarui informasi kos Anda')

@section('content')

<div class="w-full mx-auto">

    <form action="{{ route('admin.kos.update', $kos) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Informasi Dasar -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <div class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Informasi Dasar</h2>
                        <p class="text-slate-400 text-sm">Data utama tentang kos Anda</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-5">
                    <!-- Nama Kos -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Nama Kos <span class="text-red-400"></span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $kos->name) }}" required
                               class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                               placeholder="Contoh: KosSmart Residence">
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Deskripsi Kos
                        </label>
                        <textarea name="description" rows="4"
                                  class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                                  placeholder="Deskripsikan kos Anda, lokasi strategis, fasilitas, dll...">{{ old('description', $kos->description) }}</textarea>
                        @error('description')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Foto Existing -->
                    @if($kos->images && count($kos->images) > 0)
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Foto Saat Ini</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach($kos->images as $index => $image)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $image) }}" 
                                     alt="Foto Kos" 
                                     class="w-full h-28 object-cover rounded-lg border-2 border-slate-600">
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-all rounded-lg flex items-center justify-center">
                                    <label class="cursor-pointer flex items-center bg-red-500 text-white px-3 py-1.5 rounded-lg hover:bg-red-600">
                                        <input type="checkbox" name="remove_images[]" value="{{ $index }}" class="mr-2">
                                        <span class="text-sm font-medium">Hapus</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <p class="text-sm text-slate-400 mt-2">Centang foto yang ingin dihapus</p>
                    </div>
                    @endif
                    <!-- Upload Foto Baru -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Upload Foto Baru
                        </label>
                        <div class="border-2 border-dashed border-slate-600 rounded-xl p-6 text-center hover:border-amber-500 transition-colors bg-slate-700/30">
                            <input type="file" name="images[]" id="images" multiple accept="image/*" 
                                   class="hidden" onchange="previewImages(event)">
                            <label for="images" class="cursor-pointer">
                                <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-white font-medium">Klik untuk upload atau drag & drop</p>
                                <p class="text-slate-400 text-sm mt-1">PNG, JPG, JPEG (Maks. 5MB per foto)</p>
                            </label>
                        </div>
                        <div id="imagePreview" class="grid grid-cols-3 gap-3 mt-3"></div>
                        @error('images.*')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Alamat & Kontak -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <div class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Alamat & Kontak</h2>
                        <p class="text-slate-400 text-sm">Informasi lokasi dan cara menghubungi</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <!-- Alamat Lengkap -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Alamat Lengkap <span class="text-red-400"></span>
                        </label>
                        <textarea name="address" rows="4" required
                                  class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                  placeholder="Jl. Contoh No. 123, Kelurahan...">{{ old('address', $kos->address) }}</textarea>
                        @error('address')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <!-- Kota -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Kota <span class="text-red-400"></span>
                            </label>
                            <input type="text" name="city" value="{{ old('city', $kos->city) }}" required
                                   class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                   placeholder="Bandung">
                            @error('city')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Provinsi -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Provinsi <span class="text-red-400"></span>
                            </label>
                            <input type="text" name="province" value="{{ old('province', $kos->province) }}" required
                                   class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                   placeholder="Jawa Barat">
                            @error('province')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Kode Pos -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Kode Pos
                            </label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $kos->postal_code) }}"
                                   class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                   placeholder="40123">
                            @error('postal_code')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <!-- Telepon -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Telepon <span class="text-red-400"></span>
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $kos->phone) }}" required
                                   class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                   placeholder="022-1234567">
                            @error('phone')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- WhatsApp -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                WhatsApp
                            </label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $kos->whatsapp) }}"
                                   class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                   placeholder="081234567890">
                            @error('whatsapp')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" value="{{ old('email', $kos->email) }}"
                                   class="w-full bg-slate-700/50 border border-slate-600 rounded-xl px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                                   placeholder="info@kos.com">
                            @error('email')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fasilitas -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <div class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Fasilitas</h2>
                    </div>
                </div>
                <div class="space-y-4">
                    <!-- Fasilitas Umum -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-400 mb-2">
                            Fasilitas Umum
                        </label>
                        <div id="facilitiesContainer" class="space-y-2">
                            @if($kos->general_facilities && count($kos->general_facilities) > 0)
                                @foreach($kos->general_facilities as $facility)
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="general_facilities[]" value="{{ $facility }}"
                                           class="flex-1 bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="px-3 py-2 bg-red-600 hover:bg-red-700
                                               text-white rounded-lg transition-all duration-200
                                               flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="general_facilities[]" 
                                           class="flex-1 bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                           placeholder="WiFi 100 Mbps">
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addFacility()" 
                            class="mt-2 w-full inline-flex items-center justify-center gap-2
                                bg-gradient-to-r from-yellow-500 to-orange-600
                                text-white font-semibold
                                px-5 py-2 rounded-lg
                                hover:from-yellow-600 hover:to-orange-700
                                transition-all shadow-lg">
                                            
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v16m8-8H4"></path>
                            </svg>
                        
                            <span>Tambah Fasilitas</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Peraturan -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <div class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white">Peraturan</h2>
                    </div>
                </div>
                <div class="space-y-4">
                    <!-- Peraturan Kos -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-400 mb-2">
                            Peraturan Kos
                        </label>
                        <div id="rulesContainer" class="space-y-2">
                            @if($kos->rules && count($kos->rules) > 0)
                                @foreach($kos->rules as $rule)
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="rules[]" value="{{ $rule }}"
                                           class="flex-1 bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="px-3 py-2 bg-red-600 hover:bg-red-700
                                               text-white rounded-lg transition-all duration-200
                                               flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="flex items-center space-x-2">
                                    <input type="text" name="rules[]" 
                                           class="flex-1 bg-slate-700/50 border border-slate-600 rounded-lg px-3 py-2 text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                           placeholder="Jam malam 22.00 WIB">
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addRule()"
                            class="mt-2 w-full inline-flex items-center justify-center gap-2
                                bg-gradient-to-r from-yellow-500 to-orange-600
                                text-white font-semibold
                                px-5 py-2 rounded-lg
                                hover:from-yellow-600 hover:to-orange-700
                                transition-all shadow-lg">
                                            
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v16m8-8H4"></path>
                            </svg>
                        
                            <span>Tambah Peraturan</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mt-6">
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
                    <!-- Check-in Time -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-2">
                            Waktu Check-in <span class="text-red-400"></span>
                        </label>
                        <input type="time" name="checkin_time" 
                               value="{{ old('checkin_time', \Carbon\Carbon::parse($kos->checkin_time)->format('H:i')) }}" 
                               required
                               class="w-full bg-slate-900 text-slate-100 border border-slate-700 rounded-xl px-4 py-2 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all [color-scheme:dark]">
                        @error('checkin_time')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Check-out Time -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-400 mb-2">
                            Waktu Check-out <span class="text-red-400"></span>
                        </label>
                        <input type="time" name="checkout_time" 
                               value="{{ old('checkout_time', \Carbon\Carbon::parse($kos->checkout_time)->format('H:i')) }}" 
                               required
                               class="w-full bg-slate-900 text-slate-100 border border-slate-700 rounded-xl px-4 py-2 focus:ring-2 focus:ring-violet-500 focus:border-transparent transition-all [color-scheme:dark]">
                        @error('checkout_time')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-xl border border-slate-700 overflow-hidden">
                <div class="p-6">
                    <label class="flex items-start space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" 
                               {{ old('is_active', $kos->is_active) ? 'checked' : '' }}
                               class="w-5 h-5
                                    accent-amber-500
                                    bg-slate-700 border-slate-600
                                    rounded
                                    focus:ring-2 focus:ring-amber-500
                                    focus:ring-offset-0
                                    mt-0.5">
                        <div>
                            <span class="text-sm font-semibold text-white block">Aktifkan kos</span>
                            <p class="text-xs text-slate-400 mt-1">Tampilkan ke calon penghuni</p>
                        </div>
                    </label>
                </div>
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
                <span>Update Informasi Kos</span>
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
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                <div class="absolute top-2 right-2 bg-white rounded-full p-1">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            `;
            preview.appendChild(div);
        };
        
        reader.readAsDataURL(file);
    }
}

function addFacility() {
    const container = document.getElementById('facilitiesContainer');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';

    div.innerHTML = `
        <input type="text" name="general_facilities[]"
               class="flex-1 bg-slate-700/50 border border-slate-600
                      rounded-lg px-3 py-2 text-white placeholder-slate-400 text-sm
                      focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
               placeholder="Tambah fasilitas...">

        <button type="button" onclick="this.parentElement.remove()"
                class="px-3 py-2 bg-red-600 hover:bg-red-700
                       text-white rounded-lg transition-all duration-200
                       flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    container.appendChild(div);
}

function addRule() {
    const container = document.getElementById('rulesContainer');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';

    div.innerHTML = `
        <input type="text" name="rules[]"
               class="flex-1 bg-slate-700/50 border border-slate-600
                      rounded-lg px-3 py-2 text-white placeholder-slate-400 text-sm
                      focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
               placeholder="Tambah peraturan...">

        <button type="button" onclick="this.parentElement.remove()"
                class="px-3 py-2 bg-red-600 hover:bg-red-700
                       text-white rounded-lg transition-all duration-200
                       flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    container.appendChild(div);
}

</script>
@endpush