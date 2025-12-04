@extends('layouts.admin')

@section('title', 'Tambah Informasi Kos')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.kos-info.index') }}" class="text-purple-600 hover:text-purple-700 flex items-center mb-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar Informasi
    </a>
    <h1 class="text-3xl font-bold text-gray-800">✏️ Tambah Informasi Kos</h1>
    <p class="text-gray-600 mt-1">Buat informasi baru untuk ditampilkan di halaman detail kamar</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <form action="{{ route('admin.kos-info.store') }}" method="POST">
        @csrf

        <!-- Judul -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Judul Informasi <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title') }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('title') border-red-500 @enderror"
                   placeholder="Contoh: Tips Booking Kamar"
                   required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tipe Informasi -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Tipe Informasi <span class="text-red-500">*</span>
            </label>
            <select name="type" 
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('type') border-red-500 @enderror"
                    required>
                <option value="">Pilih Tipe</option>
                <option value="tip" {{ old('type') == 'tip' ? 'selected' : '' }}>Tips</option>
                <option value="rule" {{ old('type') == 'rule' ? 'selected' : '' }}>Peraturan</option>
                <option value="facility" {{ old('type') == 'facility' ? 'selected' : '' }}>Fasilitas</option>
                <option value="contact" {{ old('type') == 'contact' ? 'selected' : '' }}>Kontak</option>
                <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>Umum</option>
            </select>
            @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Konten -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Konten Informasi <span class="text-red-500">*</span>
            </label>
            <textarea name="content" rows="6" 
                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('content') border-red-500 @enderror"
                      placeholder="Tulis konten informasi di sini..."
                      required>{{ old('content') }}</textarea>
            @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-gray-500 text-sm mt-1">Jelaskan informasi secara detail</p>
        </div>

        <!-- Icon (Optional) -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Icon (Opsional)
            </label>
            <input type="text" name="icon" value="{{ old('icon') }}" 
                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                   placeholder="Contoh: 🏠, 📋, ⚠️">
            <p class="text-gray-500 text-sm mt-1">Gunakan emoji atau kode icon</p>
        </div>

        <!-- Urutan -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Urutan Tampil
            </label>
            <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <p class="text-gray-500 text-sm mt-1">Semakin kecil angka, semakin di atas posisinya</p>
        </div>

        <!-- Status -->
        <div class="mb-6">
            <div class="flex items-start space-x-4">
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">
                        Aktif
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="show_in_detail" id="show_in_detail" value="1"
                           {{ old('show_in_detail') ? 'checked' : '' }}
                           class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                    <label for="show_in_detail" class="ml-2 text-sm text-gray-700">
                        Tampilkan di Detail Kamar
                    </label>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.kos-info.index') }}" 
               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Simpan Informasi</span>
            </button>
        </div>
    </form>
</div>

@endsection