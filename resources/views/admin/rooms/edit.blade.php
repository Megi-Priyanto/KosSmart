@extends('layouts.admin')

@section('title', 'Edit Kamar')
@section('page-title', 'Edit Kamar ' . $room->room_number)
@section('page-description', 'Perbarui informasi kamar')

@section('content')

<div class="max-w-4xl mx-auto">
    
    <form action="{{ route('admin.rooms.update', $room) }}" method="POST" enctype="multipart/form-data" x-data="roomEditForm()">
        @csrf
        @method('PUT')
        
        <!-- Informasi Dasar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Informasi Dasar
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nomor Kamar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Kamar <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('room_number') border-red-500 @enderror"
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
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('floor') border-red-500 @enderror"
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
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('type') border-red-500 @enderror"
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
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('capacity') border-red-500 @enderror"
                           required>
                    @error('capacity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Ukuran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran (m²)</label>
                    <input type="number" name="size" value="{{ old('size', $room->size) }}" step="0.01" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('size') border-red-500 @enderror">
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
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('price') border-red-500 @enderror"
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
                       id="has_window"
                       class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                <label for="has_window" class="ml-2 text-sm font-medium text-gray-700 cursor-pointer">
                    Kamar memiliki jendela
                </label>
            </div>
        </div>
        
        <!-- Deskripsi -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
                Deskripsi & Catatan
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Kamar</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $room->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Khusus (Internal)</label>
                    <textarea name="notes" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $room->notes) }}</textarea>
                    @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Fasilitas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Fasilitas Kamar
            </h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Fasilitas</label>
                <div class="flex gap-2">
                    <input type="text" x-model="newFacility" @keydown.enter.prevent="addFacility"
                           class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Contoh: AC, WiFi, Lemari">
                    <button type="button" @click="addFacility"
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah
                    </button>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-2 min-h-[40px] mb-4">
                <template x-for="(facility, index) in facilities" :key="index">
                    <div class="flex items-center space-x-2 px-3 py-2 bg-purple-50 text-purple-700 rounded-lg border border-purple-200 hover:bg-purple-100 transition-colors">
                        <span class="text-sm font-medium" x-text="facility"></span>
                        <input type="hidden" name="facilities[]" :value="facility">
                        <button type="button" @click="removeFacility(index)" 
                                class="text-purple-900 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
            
            <!-- Quick Add -->
            <div class="pt-4 border-t border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-2">⚡ Tambah Cepat:</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="addQuickFacility('AC')" class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-purple-100 hover:text-purple-700 transition-colors">+ AC</button>
                    <button type="button" @click="addQuickFacility('WiFi')" class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-purple-100 hover:text-purple-700 transition-colors">+ WiFi</button>
                    <button type="button" @click="addQuickFacility('Kamar Mandi Dalam')" class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-purple-100 hover:text-purple-700 transition-colors">+ Kamar Mandi Dalam</button>
                    <button type="button" @click="addQuickFacility('Lemari')" class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-purple-100 hover:text-purple-700 transition-colors">+ Lemari</button>
                    <button type="button" @click="addQuickFacility('Meja Belajar')" class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-purple-100 hover:text-purple-700 transition-colors">+ Meja Belajar</button>
                </div>
            </div>
        </div>

        <!-- Foto Kamar - ADVANCED PHOTO MANAGEMENT -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Kelola Foto Kamar
                <span class="ml-auto text-sm font-normal" :class="existingCount > 0 ? 'text-gray-500' : 'text-red-500'">
                    <span x-text="existingCount"></span> foto saat ini
                </span>
            </h3>
        
            @php
                $images = is_array($room->images) ? $room->images : (json_decode($room->images ?? '[]', true) ?? []);
            @endphp

            <!-- Existing Images Management -->
            @if(!empty($images) && count($images) > 0)
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="block text-sm font-medium text-gray-700">
                        Foto yang Ada ({{ count($images) }})
                    </label>
                    <button type="button" 
                            @click="clearAllSelection()"
                            x-show="markedForDeletion.length > 0"
                            class="text-sm text-purple-600 hover:text-purple-800 transition-colors">
                        Batal Semua Pilihan
                    </button>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    @foreach($images as $index => $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image) }}" 
                             alt="Kamar {{ $room->room_number }}" 
                             class="w-full aspect-square object-cover rounded-lg border-2 border-gray-200 shadow-sm">
                    
                        <!-- Hover Overlay for Selection -->
                        <div class="absolute inset-0 bg-black transition-all duration-200 rounded-lg flex flex-col items-center justify-center"
                             :class="markedForDeletion.includes({{ $index }}) ? 'bg-opacity-70' : 'bg-opacity-0 group-hover:bg-opacity-50'">
                            
                            <!-- Checkbox -->
                            <label class="cursor-pointer flex flex-col items-center space-y-2"
                                   :class="markedForDeletion.includes({{ $index }}) || markedForDeletion.length === 0 ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'">
                                <input type="checkbox" 
                                       name="remove_images[]" 
                                       value="{{ $index }}" 
                                       class="w-6 h-6 rounded"
                                       @change="toggleRemove($event, {{ $index }})">
                                <span class="text-white text-xs font-medium">
                                    <span x-show="!markedForDeletion.includes({{ $index }})">Pilih untuk Hapus</span>
                                    <span x-show="markedForDeletion.includes({{ $index }})">Akan Dihapus</span>
                                </span>
                            </label>
                        </div>
                    
                        <!-- Number Badge -->
                        <div class="absolute top-2 left-2 bg-purple-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                            #{{ $index + 1 }}
                        </div>
                        
                        <!-- Cover Badge -->
                        @if($index === 0)
                        <div class="absolute top-2 right-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                            COVER
                        </div>
                        @endif
                        
                        <!-- Delete Indicator Overlay -->
                        <div x-show="markedForDeletion.includes({{ $index }})" 
                             x-transition
                             class="absolute inset-0 bg-red-600 bg-opacity-60 rounded-lg flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span class="text-white text-sm font-bold">AKAN DIHAPUS</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Deletion Info -->
                <div x-show="markedForDeletion.length > 0" 
                     x-transition
                     class="mt-4 p-3 bg-red-50 border-2 border-red-300 rounded-lg">
                    <div class="flex items-start space-x-2">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-red-800">
                                <span x-text="markedForDeletion.length"></span> foto akan dihapus permanen
                            </p>
                            <p class="text-xs text-red-700 mt-1">
                                Foto yang dihapus tidak dapat dikembalikan. Pastikan Anda telah memilih foto yang benar.
                            </p>
                        </div>
                    </div>
                </div>
                
                <p class="text-sm text-gray-500 mt-3 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Klik pada foto untuk memilih foto yang ingin dihapus
                </p>
            </div>
            @else
            <div class="mb-6 p-8 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-500 font-medium">Belum ada foto untuk kamar ini</p>
                <p class="text-sm text-gray-400 mt-1">Upload foto baru di bawah</p>
            </div>
            @endif
        
            <!-- Upload New Images Section -->
            <div class="pt-6 border-t-2 border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Upload Foto Baru
                    <span class="ml-2 text-gray-500 font-normal">
                        (Tersisa <span x-text="availableSlots"></span> slot)
                    </span>
                </label>
                
                <!-- Upload Button -->
                <div class="mb-4">
                    <label for="new-images-input" 
                           class="cursor-pointer inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <span x-text="imagePreviews.length > 0 ? imagePreviews.length + ' Foto Baru Dipilih' : 'Pilih Foto Baru'"></span>
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
                <div class="flex items-start space-x-2 text-sm text-gray-600 bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-blue-800 mb-1">Informasi Upload:</p>
                        <ul class="space-y-1 text-blue-700">
                            <li>• Format: JPG, JPEG, PNG (Max 5MB/file)</li>
                            <li>• Total maksimal: 10 foto per kamar</li>
                            <li>• Foto baru akan ditambahkan setelah foto yang ada</li>
                        </ul>
                    </div>
                </div>
                
                @error('images')
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
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
                                 class="w-full aspect-square object-cover rounded-lg border-2 border-green-300 shadow-sm">
                            
                            <!-- New Badge -->
                            <div class="absolute top-2 right-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg animate-pulse">
                                BARU
                            </div>
                            
                            <!-- Position Number -->
                            <div class="absolute top-2 left-2 bg-purple-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                #<span x-text="existingCount + index + 1"></span>
                            </div>
                            
                            <!-- Delete Button -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-200 rounded-lg flex items-center justify-center">
                                <button type="button" 
                                        @click="removePreview(index)"
                                        class="opacity-0 group-hover:opacity-100 bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition-all transform hover:scale-110">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
                 class="mt-6 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-lg">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="font-bold text-purple-900 mb-2">Ringkasan Perubahan Foto:</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                            <div class="bg-white rounded-lg p-3 border border-purple-200">
                                <p class="text-gray-600 mb-1">Foto Sekarang</p>
                                <p class="text-2xl font-bold text-gray-800" x-text="existingCount"></p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-purple-200">
                                <p class="text-gray-600 mb-1">Akan Dihapus</p>
                                <p class="text-2xl font-bold text-red-600" x-text="markedForDeletion.length"></p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-purple-200">
                                <p class="text-gray-600 mb-1">Foto Baru</p>
                                <p class="text-2xl font-bold text-green-600" x-text="imagePreviews.length"></p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-purple-200">
                            <p class="text-purple-900">
                                <span class="font-bold text-lg" x-text="totalAfterUpdate"></span> 
                                <span class="text-sm">foto setelah update</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Maintenance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Status & Maintenance
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Kamar <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('status') border-red-500 @enderror"
                            required>
                        <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>✅ Tersedia</option>
                        <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>🔒 Terisi</option>
                        <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
                    </select>
                    
                    @if($room->currentRent)
                    <div class="mt-2 p-2 bg-orange-50 border border-orange-200 rounded text-sm text-orange-800">
                        ⚠️ Disewa oleh: <strong>{{ $room->currentRent->user->name }}</strong>
                    </div>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Terakhir Maintenance
                    </label>
                    <input type="date" name="last_maintenance" 
                           value="{{ old('last_maintenance', $room->last_maintenance?->format('Y-m-d')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('last_maintenance') border-red-500 @enderror">
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Total Dilihat:</span>
                    <span class="font-semibold text-gray-800">{{ $room->view_count }} kali</span>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
        
            <!-- Tombol Batal -->
            <a href="{{ route('admin.rooms.index') }}"
               class="w-full sm:w-auto px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Batal
            </a>
        
            <!-- Tombol Simpan -->
            <button type="submit"
                    class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"></path>
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
                alert(`⚠️ Hanya bisa menambah ${availableSlots} foto lagi!\nTotal maksimal 10 foto per kamar.`);
            }
            
            for (let i = 0; i < maxFiles; i++) {
                const file = files[i];
                
                // Validate file size
                if (file.size > 5120 * 1024) {
                    alert(`❌ File "${file.name}" terlalu besar!\nMaksimal 5MB per file.`);
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