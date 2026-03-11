@extends('layouts.user')

@section('title', 'Buat Komplain Baru')

@section('content')

<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Ajukan Komplain</h1>
        <p class="text-sm text-gray-600 mt-1">Sampaikan kendala fasilitas di kamar atau gedung kos Anda</p>
    </div>
    <a href="{{ route('user.tickets.index') }}"
        class="px-4 py-2 bg-white text-gray-700 font-medium rounded-lg hover:bg-gray-50 border border-gray-200 transition-all shadow-sm flex items-center justify-center">
        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>
</div>

<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <!-- Header Info Kamar -->
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-lg">Informasi Kamar</h3>
                    <p class="text-yellow-100 text-sm mt-0.5">Kamar No. {{ $activeRent->room->room_number ?? 'Unknown' }} &bull; {{ $activeRent->room->kosInfo->tempatKos->nama_kos ?? 'KosSmart' }}</p>
                </div>
            </div>
            <div>
                <span class="bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider backdrop-blur-sm">
                    {{ $activeRent->room->type ?? 'Umum' }}
                </span>
            </div>
        </div>

        <!-- Form Area -->
        <div class="p-6 sm:p-8">
            <form action="{{ route('user.tickets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <!-- Judul Komplain -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Komplain <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required
                            placeholder="Contoh: AC Bocor, Lampu Kamar Mandi Mati, Keran Air Rusak"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-gray-700 placeholder-gray-400 transition-colors bg-gray-50 hover:bg-white focus:bg-white"
                            value="{{ old('title') }}">
                        @error('title')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Detail Kerusakan -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Detail Kerusakan <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="5" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-gray-700 placeholder-gray-400 transition-colors bg-gray-50 hover:bg-white focus:bg-white resize-none"
                            placeholder="Jelaskan secara rinci kerusakan yang terjadi, misalnya sejak kapan, bagian mana yang rusak, dll...">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <!-- Upload Foto -->
                    <div x-data="imageUploader()" class="pb-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Kerusakan <span class="text-gray-400 font-normal">(Opsional namun sangat membantu)</span></label>

                        <!-- Drag & Drop Area -->
                        <div class="mt-2 flex justify-center rounded-xl border-2 border-dashed px-6 pt-5 pb-6 transition-all duration-200"
                            :class="isDragging ? 'border-yellow-400 bg-yellow-50' : 'border-gray-300 bg-gray-50 hover:bg-gray-100'"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop($event)">
                            <div class="space-y-2 text-center">

                                <!-- File Preview (If any) -->
                                <template x-if="imageUrl">
                                    <div class="relative w-full max-w-sm mx-auto mb-4 group">
                                        <div class="aspect-[4/3] rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100 flex items-center justify-center">
                                            <img :src="imageUrl" alt="Preview Gambar" class="object-contain w-full h-full" />
                                        </div>
                                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                            <button type="button" @click.prevent="clearImage()" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors shadow-lg">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <!-- Icon and Text (If no file attached) -->
                                <template x-if="!imageUrl">
                                    <div>
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex flex-col sm:flex-row items-center justify-center text-sm text-gray-600 gap-1">
                                            <label for="photo" class="relative cursor-pointer rounded-md bg-white font-semibold text-yellow-600 hover:text-yellow-500 px-3 py-1 border border-yellow-200 shadow-sm transition-colors focus-within:outline-none focus-within:ring-2 focus-within:ring-yellow-500 focus-within:ring-offset-2">
                                                <span>Pilih Foto</span>
                                                <input id="photo" x-ref="photoInput" name="photo" type="file" class="sr-only" accept="image/*" @change="handleFileChange">
                                            </label>
                                            <p class="mt-2 sm:mt-0">atau seret & jatuhkan di sini</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">Format PNG, JPG, GIF hingga 2MB</p>
                                    </div>
                                </template>

                            </div>
                        </div>
                        @error('photo')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror

                        <script>
                            function imageUploader() {
                                return {
                                    imageUrl: null,
                                    isDragging: false,
                                    handleFileChange(event) {
                                        const file = event.target.files[0];
                                        this.previewFile(file);
                                    },
                                    handleDrop(event) {
                                        this.isDragging = false;
                                        const file = event.dataTransfer.files[0];
                                        if (file && file.type.startsWith('image/')) {
                                            this.$refs.photoInput.files = event.dataTransfer.files;
                                            this.previewFile(file);
                                        }
                                    },
                                    previewFile(file) {
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = (e) => {
                                                this.imageUrl = e.target.result;
                                            };
                                            reader.readAsDataURL(file);
                                        }
                                    },
                                    clearImage() {
                                        this.imageUrl = null;
                                        this.$refs.photoInput.value = '';
                                    }
                                }
                            }
                        </script>
                    </div>

                </div>

                <!-- Footer Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <a href="{{ route('user.tickets.index') }}"
                        class="w-full sm:w-auto px-6 py-3 text-center text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-yellow-500 to-orange-500 border border-transparent rounded-lg shadow-md hover:from-yellow-600 hover:to-orange-600 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Kirim Laporan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection