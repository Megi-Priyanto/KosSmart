@extends('layouts.admin')

@section('title', 'Edit Kamar')
@section('page-title', 'Edit Kamar ' . $room->room_number)
@section('page-description', 'Perbarui informasi kamar')

@section('content')

<div class="max-w-4xl mx-auto">
    
    <form action="{{ route('admin.rooms.update', $room) }}" method="POST" enctype="multipart/form-data" x-data="roomForm()">
        @csrf
        @method('PUT')
        
        <!-- Informasi Dasar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Dasar</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nomor Kamar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Kamar <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           required>
                    @error('room_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Lantai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lantai <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="floor" value="{{ old('floor', $room->floor) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           required>
                    @error('floor')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Tipe -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Kamar <span class="text-red-500">*</span>
                    </label>
                    <select name="type" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            required>
                        <option value="putra" {{ old('type', $room->type) == 'putra' ? 'selected' : '' }}>Putra</option>
                        <option value="putri" {{ old('type', $room->type) == 'putri' ? 'selected' : '' }}>Putri</option>
                        <option value="campur" {{ old('type', $room->type) == 'campur' ? 'selected' : '' }}>Campur</option>
                    </select>
                    @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Kapasitas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kapasitas Orang <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" min="1" max="10"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           required>
                    @error('capacity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Ukuran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran (m²)</label>
                    <input type="number" name="size" value="{{ old('size', $room->size) }}" step="0.01" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('size')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Harga -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Harga Sewa/Bulan (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price', $room->price) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           required>
                    @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Ada Jendela -->
            <div class="mt-4 flex items-center">
                <input type="checkbox" name="has_window" value="1" 
                       {{ old('has_window', $room->has_window) ? 'checked' : '' }}
                       class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                <label class="ml-2 text-sm font-medium text-gray-700">Kamar memiliki jendela</label>
            </div>
        </div>
        
        <!-- Deskripsi -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Deskripsi & Catatan</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kamar</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('description', $room->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Khusus</label>
                    <textarea name="notes" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('notes', $room->notes) }}</textarea>
                    @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Fasilitas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Fasilitas Kamar</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Fasilitas</label>
                <div class="flex gap-2">
                    <input type="text" x-model="newFacility" @keydown.enter.prevent="addFacility"
                           class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Contoh: AC, WiFi, Lemari">
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
                        <input type="hidden" name="facilities[]" :value="facility">
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
        
        <!-- Foto Kamar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Foto Kamar</h3>
        
            @php
                // Pastikan images selalu array
                $images = is_array($room->images)
                    ? $room->images
                    : json_decode($room->images ?? '[]', true);
            @endphp

            <!-- Existing Images -->
            @if(!empty($images) && count($images) > 0)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</label>
                <div class="grid grid-cols-4 gap-4">
                    @foreach($images as $index => $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image) }}" alt="Kamar {{ $room->room_number }}" 
                             class="w-full aspect-square object-cover rounded-lg border-2 border-gray-200">
                    
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 
                                    transition-opacity rounded-lg flex items-center justify-center">
                            <label class="cursor-pointer text-white text-center">
                                <input type="checkbox" name="remove_images[]" value="{{ $index }}" 
                                       class="w-5 h-5">
                                <span class="block mt-1 text-sm">Hapus</span>
                            </label>
                        </div>
                    
                        <div class="absolute top-2 left-2 bg-purple-600 text-white text-xs px-2 py-1 rounded-full">
                            #{{ $index + 1 }}
                        </div>
                    </div>
                    @endforeach
                </div>
                <p class="text-sm text-gray-500 mt-2">Hover dan centang foto yang ingin dihapus</p>
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
                            <img :src="preview" class="w-full aspect-square object-cover rounded-lg border-2 border-green-300">
                            <div class="absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-1 rounded-full">
                                Baru
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        
        <!-- Status & Maintenance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Status & Maintenance</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Kamar <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            required>
                        <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Terisi</option>
                        <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    
                    @if($room->currentRent)
                    <p class="text-sm text-orange-600 mt-2">
                        ⚠️ Kamar saat ini disewa oleh {{ $room->currentRent->user->name }}
                    </p>
                    @endif
                </div>
                
                <!-- Last Maintenance -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Terakhir Maintenance
                    </label>
                    <input type="date" name="last_maintenance" 
                           value="{{ old('last_maintenance', $room->last_maintenance?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('last_maintenance')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- View Count Info -->
            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Dilihat:</span>
                    <span class="font-semibold text-gray-800">{{ $room->view_count }} kali</span>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.rooms.index') }}" 
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
function roomForm() {
    return {
        facilities: @json(old('facilities', is_array($room->facilities) ? $room->facilities : json_decode($room->facilities, true))),
        newFacility: '',
        imagePreviews: [],
        
        addFacility() {
            if (this.newFacility.trim() && !this.facilities.includes(this.newFacility.trim())) {
                this.facilities.push(this.newFacility.trim());
                this.newFacility = '';
            }
        },
        
        removeFacility(index) {
            this.facilities.splice(index, 1);
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