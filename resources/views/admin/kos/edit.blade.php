@extends('layouts.admin')

@section('title', 'Edit Informasi Kos')
@section('page-title', 'Edit Informasi Kos')
@section('page-description', 'Perbarui data dan informasi kos Anda')

@section('content')

<div class="max-w-4xl mx-auto">
    
    <form action="{{ route('admin.kos.update') }}" method="POST" enctype="multipart/form-data" x-data="kosForm()">
        @csrf
        @method('PUT')
        
        <!-- Informasi Dasar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Dasar</h3>
            
            <div class="space-y-4">
                <!-- Nama Kos -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kos <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $kosInfo->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           required>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                              required>{{ old('address', $kosInfo->address) }}</textarea>
                    @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Kota & Provinsi -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kota <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" value="{{ old('city', $kosInfo->city) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               required>
                        @error('city')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="province" value="{{ old('province', $kosInfo->province) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               required>
                        @error('province')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Kode Pos -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                    <input type="text" name="postal_code" value="{{ old('postal_code', $kosInfo->postal_code) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           maxlength="10">
                    @error('postal_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Kontak -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', $kosInfo->phone) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               required>
                        @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $kosInfo->whatsapp) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('whatsapp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $kosInfo->email) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kos</label>
                    <textarea name="description" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                              placeholder="Jelaskan keunggulan dan deskripsi kos Anda...">{{ old('description', $kosInfo->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Fasilitas Umum -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Fasilitas Umum</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Fasilitas</label>
                <div class="flex gap-2">
                    <input type="text" x-model="newFacility" @keydown.enter.prevent="addFacility"
                           class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Contoh: Parkir Motor, WiFi 100 Mbps">
                    <button type="button" @click="addFacility"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Tambah
                    </button>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-2">
                <template x-for="(facility, index) in facilities" :key="index">
                    <div class="flex items-center space-x-2 px-3 py-1 bg-purple-50 text-purple-700 rounded-full">
                        <span class="text-sm" x-text="facility"></span>
                        <input type="hidden" name="general_facilities[]" :value="facility">
                        <button type="button" @click="removeFacility(index)" 
                                class="text-purple-900 hover:text-purple-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Peraturan Kos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Peraturan Kos</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Peraturan</label>
                <div class="flex gap-2">
                    <input type="text" x-model="newRule" @keydown.enter.prevent="addRule"
                           class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Contoh: Jam malam maksimal pukul 22.00 WIB">
                    <button type="button" @click="addRule"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Tambah
                    </button>
                </div>
            </div>
            
            <div class="space-y-2">
                <template x-for="(rule, index) in rules" :key="index">
                    <div class="flex items-center space-x-2 p-3 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="flex-1 text-sm text-gray-700" x-text="rule"></span>
                        <input type="hidden" name="rules[]" :value="rule">
                        <button type="button" @click="removeRule(index)" 
                                class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Foto Kos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Foto Kos</h3>
            
            <!-- Existing Images -->
            @if($kosInfo->images && count($kosInfo->images) > 0)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</label>
                <div class="grid grid-cols-4 gap-4">
                    @foreach($kosInfo->images as $index => $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image) }}" alt="Foto Kos" 
                             class="w-full aspect-square object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                            <label class="cursor-pointer">
                                <input type="checkbox" name="remove_images[]" value="{{ $index }}" 
                                       class="w-5 h-5">
                                <span class="ml-2 text-white text-sm">Hapus</span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-sm text-gray-500 mt-2">Centang foto yang ingin dihapus</p>
            </div>
            @endif
            
            <!-- Upload New Images -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Baru</label>
                <input type="file" name="images[]" multiple accept="image/*" @change="previewImages"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG (Maks. 5MB per file)</p>
                
                <!-- Image Previews -->
                <div x-show="imagePreviews.length > 0" class="grid grid-cols-4 gap-4 mt-4">
                    <template x-for="(preview, index) in imagePreviews" :key="index">
                        <div class="relative">
                            <img :src="preview" class="w-full aspect-square object-cover rounded-lg">
                        </div>
                    </template>
                </div>
            </div>
        </div>
        
        <!-- Pengaturan Lainnya -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Pengaturan Lainnya</h3>
            
            <div class="space-y-4">
                <!-- Check-in/Check-out Time -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Check-in <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="checkin_time" value="{{ old('checkin_time', $kosInfo->checkin_time->format('H:i')) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               required>
                        @error('checkin_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jam Check-out <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="checkout_time" value="{{ old('checkout_time', $kosInfo->checkout_time->format('H:i')) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               required>
                        @error('checkout_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Status Aktif -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" 
                           {{ old('is_active', $kosInfo->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                    <label class="ml-2 text-sm font-medium text-gray-700">Kos Aktif (Tampil di pencarian user)</label>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.kos.index') }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>
        
    </form>
    
</div>

@endsection

@push('scripts')
<script>
function kosForm() {
    return {
        // Facilities
        facilities: @json(old('general_facilities', $kosInfo->general_facilities ?? [])),
        newFacility: '',
        
        // Rules
        rules: @json(old('rules', $kosInfo->rules ?? [])),
        newRule: '',
        
        // Image previews
        imagePreviews: [],
        
        addFacility() {
            if (this.newFacility.trim()) {
                this.facilities.push(this.newFacility.trim());
                this.newFacility = '';
            }
        },
        
        removeFacility(index) {
            this.facilities.splice(index, 1);
        },
        
        addRule() {
            if (this.newRule.trim()) {
                this.rules.push(this.newRule.trim());
                this.newRule = '';
            }
        },
        
        removeRule(index) {
            this.rules.splice(index, 1);
        },
        
        previewImages(event) {
            this.imagePreviews = [];
            const files = event.target.files;
            
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreviews.push(e.target.result);
                };
                reader.readAsDataURL(files[i]);
            }
        }
    }
}
</script>
@endpush