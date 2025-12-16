@extends('layouts.admin')

@section('title', 'Tambah Kamar')
@section('page-title', 'Tambah Kamar Baru')
@section('page-description', 'Tambahkan kamar baru ke dalam sistem')

@section('content')

<div class="max-w-4xl mx-auto">
    
    <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data" x-data="roomForm()">
        @csrf
        
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
                    <input type="text" name="room_number" value="{{ old('room_number') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('room_number') border-red-500 @enderror"
                           placeholder="Contoh: 101" required>
                    @error('room_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Lantai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Lantai <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="floor" value="{{ old('floor') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('floor') border-red-500 @enderror"
                           placeholder="Contoh: Lantai 1" required>
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
                        <option value="">Pilih Tipe</option>
                        <option value="putra" {{ old('type') == 'putra' ? 'selected' : '' }}>Putra</option>
                        <option value="putri" {{ old('type') == 'putri' ? 'selected' : '' }}>Putri</option>
                        <option value="campur" {{ old('type') == 'campur' ? 'selected' : '' }}>Campur</option>
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
                    <input type="number" name="capacity" value="{{ old('capacity', 1) }}" min="1" max="10"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('capacity') border-red-500 @enderror"
                           required>
                    @error('capacity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Ukuran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ukuran (m²)
                    </label>
                    <input type="number" name="size" value="{{ old('size') }}" step="0.01" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('size') border-red-500 @enderror"
                           placeholder="Contoh: 12.00">
                    @error('size')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Harga -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Harga Sewa (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('price') border-red-500 @enderror"
                           placeholder="Contoh: 1500000" required>
                    @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Jenis Sewa -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Sewa <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_sewa" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('jenis_sewa') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Jenis Sewa</option>
                        <option value="bulan" {{ old('jenis_sewa', 'bulan') == 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                        <option value="tahun" {{ old('jenis_sewa') == 'tahun' ? 'selected' : '' }}>Per Tahun</option>
                    </select>
                    @error('jenis_sewa')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Pilih periode sewa untuk kamar ini</p>
                </div>
            </div>
            
            <!-- Ada Jendela -->
            <div class="mt-4 flex items-center">
                <input type="checkbox" name="has_window" value="1" 
                       {{ old('has_window', true) ? 'checked' : '' }}
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
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Kamar nyaman dengan pemandangan taman...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Khusus (Internal)</label>
                    <textarea name="notes" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                              placeholder="Catatan internal untuk admin...">{{ old('notes') }}</textarea>
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
                <p class="text-sm text-gray-500 mt-1">💡 Tekan Enter atau klik tombol Tambah</p>
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
                <div x-show="facilities.length === 0" class="text-gray-400 text-sm py-2">
                    Belum ada fasilitas ditambahkan
                </div>
            </div>
            
            <!-- Quick Add -->
            <div class="pt-4 border-t border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-2">⚡ Tambah Cepat:</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="addQuickFacility('AC')" 
                            class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-purple-100 hover:text-purple-700 transition-colors">
                        + AC
                    </button>
                    <button type="button" @click="addQuickFacility('WiFi')" 
                            class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-purple-100 hover:text-purple-700 transition-colors">
                        + WiFi
                    </button>
                    <button type="button" @click="addQuickFacility('Kamar Mandi Dalam')" 
                            class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-purple-100 hover:text-purple-700 transition-colors">
                        + Kamar Mandi Dalam
                    </button>
                    <button type="button" @click="addQuickFacility('Lemari')" 
                            class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-purple-100 hover:text-purple-700 transition-colors">
                        + Lemari
                    </button>
                    <button type="button" @click="addQuickFacility('Meja Belajar')" 
                            class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-purple-100 hover:text-purple-700 transition-colors">
                        + Meja Belajar
                    </button>
                    <button type="button" @click="addQuickFacility('Kasur')" 
                            class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-purple-100 hover:text-purple-700 transition-colors">
                        + Kasur
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Foto Kamar - ENHANCED MULTI IMAGE UPLOAD -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Foto Kamar
                <span class="ml-auto text-sm font-normal text-gray-500">Maksimal 10 foto</span>
            </h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Upload Foto Kamar
                </label>
                
                <!-- Custom File Upload Button -->
                <div class="mb-4">
                    <label for="images-input" 
                           class="cursor-pointer inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <span x-text="imagePreviews.length > 0 ? 'Upload ' + imagePreviews.length + ' Foto Dipilih' : 'Pilih Foto'"></span>
                    </label>
                    <input type="file" 
                           id="images-input"
                           name="images[]" 
                           multiple 
                           accept="image/jpeg,image/png,image/jpg" 
                           @change="previewImages"
                           class="hidden">
                </div>
                
                <div class="flex items-start space-x-2 text-sm text-gray-600 bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-blue-800 mb-1">Tips Upload Foto:</p>
                        <ul class="space-y-1 text-blue-700">
                            <li>• Format: JPG, JPEG, PNG</li>
                            <li>• Ukuran maksimal: 5MB per foto</li>
                            <li>• Maksimal 10 foto per kamar</li>
                            <li>• Foto pertama akan menjadi cover kamar</li>
                        </ul>
                    </div>
                </div>
                
                @error('images')
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                    {{ $message }}
                </div>
                @enderror
                
                @error('images.*')
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                    {{ $message }}
                </div>
                @enderror
                
                <!-- Image Previews Grid -->
                <div x-show="imagePreviews.length > 0" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <template x-for="(preview, index) in imagePreviews" :key="index">
                        <div class="relative group">
                            <img :src="preview" 
                                 class="w-full aspect-square object-cover rounded-lg border-2 border-purple-200 shadow-sm">
                            
                            <!-- Badge Number -->
                            <div class="absolute top-2 right-2 bg-purple-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg" 
                                 x-text="'#' + (index + 1)">
                            </div>
                            
                            <!-- Cover Badge -->
                            <div x-show="index === 0" 
                                 class="absolute top-2 left-2 bg-green-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                COVER
                            </div>
                            
                            <!-- Delete Button Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all duration-200 rounded-lg flex items-center justify-center">
                                <button type="button" 
                                        @click="removePreview(index)"
                                        class="opacity-0 group-hover:opacity-100 bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition-all transform hover:scale-110">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Upload Summary -->
                <div x-show="imagePreviews.length > 0" 
                     class="mt-4 p-3 bg-purple-50 border border-purple-200 rounded-lg text-sm text-purple-800">
                    <span class="font-semibold" x-text="imagePreviews.length"></span> foto siap diupload
                    <span class="mx-2">•</span>
                    <span x-text="'Sisa slot: ' + (10 - imagePreviews.length)"></span>
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
                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status Kamar <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('status') border-red-500 @enderror"
                            required>
                        <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>✅ Tersedia</option>
                        <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>🔒 Terisi</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
                    </select>
                    @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Last Maintenance -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Terakhir Maintenance
                    </label>
                    <input type="date" name="last_maintenance" value="{{ old('last_maintenance') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('last_maintenance') border-red-500 @enderror">
                    @error('last_maintenance')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
            <a href="{{ route('admin.rooms.index') }}" 
               class="w-full sm:w-auto px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Batal
            </a>
            <button type="submit" 
                    class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg flex items-center justify-center">
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
            const files = event.target.files;
            this.selectedFiles = files;
            
            const maxFiles = Math.min(files.length, 10);
            
            if (files.length > 10) {
                alert('Maksimal 10 gambar!\nHanya 10 gambar pertama yang akan diupload.');
            }
            
            for (let i = 0; i < maxFiles; i++) {
                const file = files[i];
                
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
                document.getElementById('images-input').value = '';
            }
        }
    }
}
</script>
@endpush