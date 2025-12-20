@extends('layouts.admin')

@section('title', 'Edit Informasi Kos')
@section('page-title', 'Edit Informasi Kos')
@section('page-description', 'Perbarui informasi kos Anda')

@section('content')

<form action="{{ route('admin.kos.update', $kos) }}" 
      method="POST" 
      enctype="multipart/form-data" 
      class="max-w-5xl">

    @csrf
    @method('PUT')
    
    <!-- Informasi Dasar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Informasi Dasar</h2>
            <p class="text-purple-100 text-sm mt-1">Data utama tentang kos Anda</p>
        </div>
        
        <div class="p-6 space-y-4">
            <!-- Nama Kos -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kos <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $kos->name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                       placeholder="Contoh: KosSmart Residence">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Kos
                </label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                          placeholder="Deskripsikan kos Anda, lokasi strategis, fasilitas, dll...">{{ old('description', $kos->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto Existing -->
            @if($kos->images && count($kos->images) > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Foto Saat Ini</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($kos->images as $index => $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image) }}" 
                             alt="Foto Kos" 
                             class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all rounded-lg flex items-center justify-center">
                            <label class="opacity-0 group-hover:opacity-100 cursor-pointer flex items-center bg-red-500 text-white px-3 py-1 rounded">
                                <input type="checkbox" name="remove_images[]" value="{{ $index }}" 
                                       class="mr-2">
                                <span class="text-sm">Hapus</span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-sm text-gray-500 mt-2">Centang foto yang ingin dihapus</p>
            </div>
            @endif

            <!-- Upload Foto Baru -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Upload Foto Baru
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-500 transition-colors">
                    <input type="file" name="images[]" id="images" multiple accept="image/*" 
                           class="hidden" onchange="previewImages(event)">
                    <label for="images" class="cursor-pointer">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-600">Klik untuk upload atau drag & drop</p>
                        <p class="text-gray-400 text-sm mt-1">PNG, JPG, JPEG (Maks. 5MB per foto)</p>
                    </label>
                </div>
                <div id="imagePreview" class="grid grid-cols-3 gap-4 mt-4"></div>
                @error('images.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Alamat & Kontak -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Alamat & Kontak</h2>
            <p class="text-blue-100 text-sm mt-1">Informasi lokasi dan cara menghubungi</p>
        </div>
        
        <div class="p-6 space-y-4">
            <!-- Alamat Lengkap -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Alamat Lengkap <span class="text-red-500">*</span>
                </label>
                <textarea name="address" rows="2" required
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                          placeholder="Jl. Contoh No. 123, Kelurahan...">{{ old('address', $kos->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Kota -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kota <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="city" value="{{ old('city', $kos->city) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Bandung">
                    @error('city')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Provinsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Provinsi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="province" value="{{ old('province', $kos->province) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Jawa Barat">
                    @error('province')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kode Pos -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Pos
                    </label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $kos->postal_code) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="40123">
                    @error('postal_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Telepon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $kos->phone) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="022-1234567">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- WhatsApp -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        WhatsApp
                    </label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $kos->whatsapp) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="081234567890">
                    @error('whatsapp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email', $kos->email) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="info@kos.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Fasilitas & Peraturan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Fasilitas & Peraturan</h2>
            <p class="text-green-100 text-sm mt-1">Fasilitas umum dan aturan yang berlaku</p>
        </div>
        
        <div class="p-6 space-y-6">
            <!-- Fasilitas Umum -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Fasilitas Umum
                </label>
                <div id="facilitiesContainer" class="space-y-2">
                    @if($kos->general_facilities && count($kos->general_facilities) > 0)
                        @foreach($kos->general_facilities as $facility)
                        <div class="flex items-center space-x-2">
                            <input type="text" name="general_facilities[]" value="{{ $facility }}"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <button type="button" onclick="this.parentElement.remove()" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <div class="flex items-center space-x-2">
                            <input type="text" name="general_facilities[]" 
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Contoh: WiFi 100 Mbps">
                            <button type="button" onclick="addFacility()" 
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
                <button type="button" onclick="addFacility()" 
                        class="mt-2 px-4 py-2 text-sm bg-green-100 text-green-700 rounded-lg hover:bg-green-200">
                    + Tambah Fasilitas
                </button>
            </div>

            <!-- Peraturan Kos -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Peraturan Kos
                </label>
                <div id="rulesContainer" class="space-y-2">
                    @if($kos->rules && count($kos->rules) > 0)
                        @foreach($kos->rules as $rule)
                        <div class="flex items-center space-x-2">
                            <input type="text" name="rules[]" value="{{ $rule }}"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <button type="button" onclick="this.parentElement.remove()" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <div class="flex items-center space-x-2">
                            <input type="text" name="rules[]" 
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   placeholder="Contoh: Jam malam maksimal pukul 22.00 WIB">
                            <button type="button" onclick="addRule()" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
                <button type="button" onclick="addRule()" 
                        class="mt-2 px-4 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                    + Tambah Peraturan
                </button>
            </div>
        </div>
    </div>

    <!-- Jam Operasional -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Jam Operasional</h2>
            <p class="text-orange-100 text-sm mt-1">Waktu check-in dan check-out</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Check-in Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Check-in <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="checkin_time" 
                           value="{{ old('checkin_time', \Carbon\Carbon::parse($kos->checkin_time)->format('H:i')) }}" 
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('checkin_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Check-out Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Waktu Check-out <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="checkout_time" 
                           value="{{ old('checkout_time', \Carbon\Carbon::parse($kos->checkout_time)->format('H:i')) }}" 
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('checkout_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Status -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="p-6">
            <label class="flex items-center space-x-3">
                <input type="checkbox" name="is_active" value="1" 
                       {{ old('is_active', $kos->is_active) ? 'checked' : '' }}
                       class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-2 focus:ring-purple-500">
                <span class="text-sm font-medium text-gray-700">Aktifkan kos (tampilkan ke calon penghuni)</span>
            </label>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('admin.kos.index') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
            Batal
        </a>
        <button type="submit" 
                class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>Update Informasi Kos</span>
        </button>
    </div>
</form>

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
               class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
               placeholder="Tambah fasilitas...">
        <button type="button" onclick="this.parentElement.remove()" 
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
               class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
               placeholder="Tambah peraturan...">
        <button type="button" onclick="this.parentElement.remove()" 
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
}
</script>
@endpush