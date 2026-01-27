@extends('layouts.superadmin')

@section('title', 'Pengaturan Aplikasi')
@section('page-title', 'Pengaturan Aplikasi')
@section('page-description', 'Kelola tampilan dan logo aplikasi KosSmart')

@section('content')

<form action="{{ route('superadmin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Nama Aplikasi -->
    <div class="bg-slate-800 border border-gray-700 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            </svg>
            Nama Aplikasi
        </h2>
        
        <div>
            <label class="text-gray-300 mb-2">
                Nama Aplikasi <span class="text-red-500"></span>
            </label>
            <input type="text" 
                   name="app_name" 
                   value="{{ old('app_name', $appName) }}"
                   class="w-full px-4 py-2.5 bg-slate-900 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                   placeholder="Contoh: KosSmart"
                   required>
            @error('app_name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-400">Nama ini akan muncul di header semua halaman (Super Admin, Admin, dan User)</p>
        </div>
    </div>

    <!-- Logo Aplikasi -->
    <div class="bg-slate-800 border border-gray-700 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Logo Aplikasi
        </h2>
        <p class="text-sm text-gray-400 mb-4">Logo akan tampil di pojok kiri atas untuk semua role (Super Admin, Admin, User)</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Preview Logo Saat Ini -->
            <div>
                <label class="block text-gray-300 mb-2">Logo Saat Ini</label>
                <div class="bg-slate-900 border-2 border-gray-700 rounded-lg p-4 flex items-center justify-center h-44">
                    @if(str_starts_with($appLogo, 'settings/'))
                        <img src="{{ asset('storage/' . $appLogo) }}" alt="Logo" class="max-h-32 max-w-full object-contain">
                    @else
                        <img src="{{ asset($appLogo) }}" alt="Logo" class="max-h-32 max-w-full object-contain">
                    @endif
                </div>
            </div>

            <!-- Upload Logo Baru -->
            <div>
                <label class="block text-gray-300 mb-2">Upload Logo Baru</label>
                <div class="bg-slate-900 border-2 border-dashed border-gray-600 rounded-lg h-44 flex items-center justify-center hover:border-yellow-500 transition-colors">
                    <input type="file" 
                           name="app_logo" 
                           accept="image/png,image/jpg,image/jpeg"
                           class="hidden"
                           id="logoInput"
                           onchange="previewImage(this, 'logoPreview')">

                    <label for="logoInput" class="cursor-pointer w-full h-full flex flex-col items-center justify-center p-4">
                        <!-- Preview Container -->
                        <div id="logoPreview" class="w-24 h-24 mb-3 rounded-md bg-slate-800 flex items-center justify-center overflow-hidden">
                            <!-- Icon Default -->
                            <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14
                                         M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-400">Klik untuk upload</p>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG (max. 2MB)</p>
                    </label>
                </div>
                @error('app_logo')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Hero Dashboard Tenant -->
    <div class="bg-slate-800 border border-gray-700 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Hero Dashboard Tenant
        </h2>
        <p class="text-sm text-gray-400 mb-4">Gambar banner untuk user yang sudah memiliki kamar</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Preview Hero Saat Ini -->
            <div>
                <label class="block text-gray-300 mb-2">Hero Image Saat Ini</label>
                <div class="bg-slate-900 border-2 border-gray-700 rounded-lg p-4 flex items-center justify-center h-44">
                    <img src="{{ str_starts_with($tenantDashboardImage,'settings/')
                        ? asset('storage/'.$tenantDashboardImage)
                        : asset($tenantDashboardImage) }}"
                        alt="Hero Tenant"
                        class="max-h-full max-w-full object-contain">
                </div>
            </div>

            <!-- Upload Hero Baru -->
            <div>
                <label class="block text-gray-300 mb-2">Upload Hero Baru</label>
                <div class="bg-slate-900 border-2 border-dashed border-gray-600 rounded-lg h-44 flex items-center justify-center hover:border-yellow-500 transition-colors">
                    <input type="file" 
                           name="tenant_dashboard_image" 
                           accept="image/png,image/jpg,image/jpeg"
                           class="hidden"
                           id="tenantHeroInput"
                           onchange="previewImage(this, 'tenantHeroPreview')">

                    <label for="tenantHeroInput" class="cursor-pointer w-full h-full flex flex-col items-center justify-center p-4">
                        <!-- Preview Container -->
                        <div id="tenantHeroPreview" class="w-24 h-24 mb-3 rounded-md bg-slate-800 flex items-center justify-center overflow-hidden">
                            <!-- Icon Default -->
                            <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14
                                         M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-400">Klik untuk upload</p>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG (max. 2MB)</p>
                    </label>
                </div>
                @error('tenant_dashboard_image')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Hero Image (Dashboard Empty) -->
    <div class="bg-slate-800 border border-gray-700 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Hero Dashboard Empty
        </h2>
        <p class="text-sm text-gray-400 mb-4">Gambar banner untuk user yang belum memiliki kamar</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Preview Hero Saat Ini -->
            <div>
                <label class="block text-gray-300 mb-2">Hero Image Saat Ini</label>
                <div class="bg-slate-900 border-2 border-gray-700 rounded-lg p-4 flex items-center justify-center h-44">
                    @if(str_starts_with($heroImageEmpty, 'settings/'))
                        <img src="{{ asset('storage/' . $heroImageEmpty) }}" alt="Hero Empty" class="max-h-full max-w-full object-contain">
                    @else
                        <img src="{{ asset($heroImageEmpty) }}" alt="Hero Empty" class="max-h-full max-w-full object-contain">
                    @endif
                </div>
            </div>

            <!-- Upload Hero Baru -->
            <div>
                <label class="block text-gray-300 mb-2">Upload Hero Baru</label>
                <div class="bg-slate-900 border-2 border-dashed border-gray-600 rounded-lg h-44 flex items-center justify-center hover:border-yellow-500 transition-colors">
                    <input type="file" 
                           name="hero_image_empty" 
                           accept="image/png,image/jpg,image/jpeg"
                           class="hidden"
                           id="emptyHeroInput"
                           onchange="previewImage(this, 'emptyHeroPreview')">

                    <label for="emptyHeroInput" class="cursor-pointer w-full h-full flex flex-col items-center justify-center p-4">
                        <!-- Preview Container -->
                        <div id="emptyHeroPreview" class="w-24 h-24 mb-3 rounded-md bg-slate-800 flex items-center justify-center overflow-hidden">
                            <!-- Icon Default -->
                            <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14
                                         M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-400">Klik untuk upload</p>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG (max. 2MB)</p>
                    </label>
                </div>
                @error('hero_image_empty')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-end gap-3">
        <button type="button" 
                onclick="window.location.reload()"
                class="px-6 py-2.5 bg-slate-700 text-white font-semibold rounded-lg hover:bg-slate-600 transition">
            Reset
        </button>
        <button type="submit" 
                class="inline-flex items-center gap-2
                        bg-gradient-to-r from-yellow-500 to-orange-600
                        text-white font-semibold
                        px-5 py-3 rounded-lg
                        hover:from-yellow-600 hover:to-orange-700
                        transition-all shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Simpan Perubahan
        </button>
    </div>
</form>

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="max-h-32 max-w-full object-contain">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection