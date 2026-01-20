@extends('layouts.superadmin')

@section('title', 'Tambah Tempat Kos')
@section('page-title', 'Tambah Tempat Kos')
@section('page-description', 'Buat tempat kos baru dalam sistem')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('superadmin.tempat-kos.index') }}" 
           class="inline-flex items-center gap-2
                    bg-gradient-to-r from-yellow-500 to-orange-600
                    text-white font-semibold
                    px-5 py-2 rounded-lg
                    hover:from-yellow-600 hover:to-orange-700
                    transition-all shadow-lg">
            Kembali ke Daftar User
        </a>
    </div>

    <form action="{{ route('superadmin.tempat-kos.store') }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf

        <!-- Informasi Dasar -->
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Informasi Dasar
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kos -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Nama Tempat Kos <span class="text-red-400"></span>
                    </label>
                    <input type="text" 
                           name="nama_kos" 
                           value="{{ old('nama_kos') }}"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('nama_kos') border-red-500 @enderror"
                           placeholder="Contoh: Kos Mawar Indah"
                           required>
                    @error('nama_kos')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Email Kontak
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('email') border-red-500 @enderror"
                           placeholder="contoh@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telepon -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Nomor Telepon
                    </label>
                    <input type="tel" 
                           name="telepon" 
                           value="{{ old('telepon') }}"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('telepon') border-red-500 @enderror"
                           placeholder="0812-3456-7890">
                    @error('telepon')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Status <span class="text-red-400"></span>
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" 
                                   name="status" 
                                   value="active" 
                                   {{ old('status', 'active') === 'active' ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-500 bg-slate-700 border-slate-600 focus:ring-green-500">
                            <span class="ml-2 text-slate-300">Aktif</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" 
                                   name="status" 
                                   value="inactive" 
                                   {{ old('status') === 'inactive' ? 'checked' : '' }}
                                   class="w-4 h-4 text-red-500 bg-slate-700 border-slate-600 focus:ring-red-500">
                            <span class="ml-2 text-slate-300">Tidak Aktif</span>
                        </label>
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Alamat -->
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Alamat Lengkap
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Alamat <span class="text-red-400"></span>
                    </label>
                    <textarea name="alamat" 
                              rows="3"
                              class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('alamat') border-red-500 @enderror"
                              placeholder="Jl. Contoh No. 123"
                              required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kota -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Kota <span class="text-red-400"></span>
                    </label>
                    <input type="text" 
                           name="kota" 
                           value="{{ old('kota') }}"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('kota') border-red-500 @enderror"
                           placeholder="Bandung"
                           required>
                    @error('kota')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Provinsi -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Provinsi <span class="text-red-400"></span>
                    </label>
                    <input type="text" 
                           name="provinsi" 
                           value="{{ old('provinsi') }}"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('provinsi') border-red-500 @enderror"
                           placeholder="Jawa Barat"
                           required>
                    @error('provinsi')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kode Pos -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Kode Pos
                    </label>
                    <input type="text" 
                           name="kode_pos" 
                           value="{{ old('kode_pos') }}"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('kode_pos') border-red-500 @enderror"
                           placeholder="40123"
                           maxlength="10">
                    @error('kode_pos')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Logo Tempat Kos -->
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Logo Tempat Kos
            </h3>

            <div class="space-y-6">
                <!-- Logo -->
                <div>
                    <input type="file" 
                           name="logo" 
                           accept="image/*"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-slate-500 file:text-white hover:file:bg-slate-600 @error('logo') border-red-500 @enderror"
                           onchange="previewLogo(event)">
                    <p class="mt-1 text-xs text-slate-400">Format: JPG, PNG. Maksimal 2MB</p>
                    @error('logo')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    
                    <!-- Preview -->
                    <div id="logoPreview" class="mt-3 hidden">
                        <img src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-slate-600">
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('superadmin.tempat-kos.index') }}" 
               class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-slate-100 rounded-lg font-medium transition">
                Batal
            </a>
            <button type="submit" 
                    class="inline-flex items-center gap-2
                                bg-gradient-to-r from-yellow-500 to-orange-600
                                text-white font-semibold
                                px-5 py-3 rounded-lg
                                hover:from-yellow-600 hover:to-orange-700
                                transition-all shadow-lg">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Tempat Kos
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
function previewLogo(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('logoPreview');
    const img = preview.querySelector('img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
}
</script>
@endpush
@endsection