@extends('layouts.superadmin')

@section('title', 'Pengaturan Aplikasi')
@section('page-title', 'Pengaturan Aplikasi')
@section('page-description', 'Kelola tampilan dan logo aplikasi KosSmart')

@section('content')

{{-- Alert Success / Error --}}
@if(session('success'))
<div class="bg-green-500/20 border border-green-500 text-green-400 rounded-xl px-5 py-4 mb-6 flex items-center gap-3">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="bg-red-500/20 border border-red-500 text-red-400 rounded-xl px-5 py-4 mb-6 flex items-center gap-3">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
    </svg>
    {{ session('error') }}
</div>
@endif

<form action="{{ route('superadmin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- ==================== NAMA APLIKASI ==================== --}}
    <div class="bg-slate-800 border border-gray-700 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
            Nama Aplikasi
        </h2>
        <div>
            <label class="block text-gray-300 mb-2">Nama Aplikasi <span class="text-red-500"></span></label>
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

    {{-- ==================== LOGO APLIKASI ==================== --}}
    <div class="bg-slate-800 border border-gray-700 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Logo Aplikasi
        </h2>
        <p class="text-sm text-gray-400 mb-4">Logo akan tampil di pojok kiri atas untuk semua role (Super Admin, Admin, User)</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 mb-2">Logo Saat Ini</label>
                <div class="bg-slate-900 border-2 border-gray-700 rounded-lg p-4 flex items-center justify-center h-44">
                    <img src="{{ str_starts_with($appLogo, 'settings/') ? asset('storage/'.$appLogo) : asset($appLogo) }}"
                         alt="Logo" class="max-h-32 max-w-full object-contain">
                </div>
            </div>
            <div>
                <label class="block text-gray-300 mb-2">Upload Logo Baru</label>
                <div class="bg-slate-900 border-2 border-dashed border-gray-600 rounded-lg h-44 flex items-center justify-center hover:border-yellow-500 transition-colors">
                    <input type="file" name="app_logo" accept="image/png,image/jpg,image/jpeg"
                           class="hidden" id="logoInput" onchange="previewImage(this, 'logoPreview')">
                    <label for="logoInput" class="cursor-pointer w-full h-full flex flex-col items-center justify-center p-4">
                        <div id="logoPreview" class="w-24 h-24 mb-3 rounded-md bg-slate-800 flex items-center justify-center overflow-hidden">
                            <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
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

    {{-- ==================== FAVICON / BROWSER ICON ==================== --}}
    <div class="bg-slate-800 border border-gray-700 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Favicon / Browser Icon
        </h2>
        <p class="text-sm text-gray-400 mb-4">
            Icon kecil yang muncul di tab browser (ukuran ideal: <strong class="text-gray-300">32×32</strong> atau <strong class="text-gray-300">64×64</strong> px)
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Preview Favicon Saat Ini --}}
            <div>
                <label class="block text-gray-300 mb-2">Favicon Saat Ini</label>
                <div class="bg-slate-900 border-2 border-gray-700 rounded-lg p-4 flex flex-col items-center justify-center h-44 gap-4">
                    {{-- Simulasi tampilan tab browser --}}
                    <div class="flex items-center gap-2 bg-slate-700 rounded-md px-3 py-1.5 w-fit">
                        <img id="currentFaviconDisplay"
                             src="{{ str_starts_with($appFavicon, 'settings/') ? asset('storage/'.$appFavicon) : asset($appFavicon) }}"
                             alt="Favicon"
                             class="w-4 h-4 object-contain">
                        <span class="text-xs text-gray-300">{{ $appName }}</span>
                        <span class="text-gray-500 text-xs ml-1">✕</span>
                    </div>
                    <p class="text-xs text-gray-500">Simulasi tampilan di tab browser</p>
                    {{-- Tampilan ukuran asli --}}
                    <img src="{{ str_starts_with($appFavicon, 'settings/') ? asset('storage/'.$appFavicon) : asset($appFavicon) }}"
                         alt="Favicon"
                         class="w-8 h-8 object-contain rounded">
                </div>
            </div>

            {{-- Upload Favicon Baru --}}
            <div>
                <label class="block text-gray-300 mb-2">Upload Favicon Baru</label>
                <div class="bg-slate-900 border-2 border-dashed border-gray-600 rounded-lg h-44 flex items-center justify-center hover:border-yellow-500 transition-colors">
                    <input type="file" name="app_favicon" accept="image/png,image/jpg,image/jpeg,image/x-icon"
                           class="hidden" id="faviconInput" onchange="previewImage(this, 'faviconPreview')">
                    <label for="faviconInput" class="cursor-pointer w-full h-full flex flex-col items-center justify-center p-4">
                        <div id="faviconPreview" class="w-16 h-16 mb-3 rounded-md bg-slate-800 flex items-center justify-center overflow-hidden">
                            <svg class="h-8 w-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-400">Klik untuk upload</p>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, ICO (max. 512KB)</p>
                    </label>
                </div>
                @error('app_favicon')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    {{-- ==================== HERO DASHBOARD TENANT ==================== --}}
    <div class="bg-slate-800 border border-gray-700 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Hero Dashboard Tenant
        </h2>
        <p class="text-sm text-gray-400 mb-4">Gambar banner untuk user yang sudah memiliki kamar</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 mb-2">Hero Image Saat Ini</label>
                <div class="bg-slate-900 border-2 border-gray-700 rounded-lg p-4 flex items-center justify-center h-44">
                    <img src="{{ str_starts_with($tenantDashboardImage,'settings/') ? asset('storage/'.$tenantDashboardImage) : asset($tenantDashboardImage) }}"
                         alt="Hero Tenant" class="max-h-full max-w-full object-contain">
                </div>
            </div>
            <div>
                <label class="block text-gray-300 mb-2">Upload Hero Baru</label>
                <div class="bg-slate-900 border-2 border-dashed border-gray-600 rounded-lg h-44 flex items-center justify-center hover:border-yellow-500 transition-colors">
                    <input type="file" name="tenant_dashboard_image" accept="image/png,image/jpg,image/jpeg"
                           class="hidden" id="tenantHeroInput" onchange="previewImage(this, 'tenantHeroPreview')">
                    <label for="tenantHeroInput" class="cursor-pointer w-full h-full flex flex-col items-center justify-center p-4">
                        <div id="tenantHeroPreview" class="w-24 h-24 mb-3 rounded-md bg-slate-800 flex items-center justify-center overflow-hidden">
                            <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
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

    {{-- ==================== HERO DASHBOARD EMPTY ==================== --}}
    <div class="bg-slate-800 border border-gray-700 rounded-xl p-6 mb-6">
        <h2 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Hero Dashboard Empty
        </h2>
        <p class="text-sm text-gray-400 mb-4">Gambar banner untuk user yang belum memiliki kamar</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 mb-2">Hero Image Saat Ini</label>
                <div class="bg-slate-900 border-2 border-gray-700 rounded-lg p-4 flex items-center justify-center h-44">
                    <img src="{{ str_starts_with($heroImageEmpty, 'settings/') ? asset('storage/'.$heroImageEmpty) : asset($heroImageEmpty) }}"
                         alt="Hero Empty" class="max-h-full max-w-full object-contain">
                </div>
            </div>
            <div>
                <label class="block text-gray-300 mb-2">Upload Hero Baru</label>
                <div class="bg-slate-900 border-2 border-dashed border-gray-600 rounded-lg h-44 flex items-center justify-center hover:border-yellow-500 transition-colors">
                    <input type="file" name="hero_image_empty" accept="image/png,image/jpg,image/jpeg"
                           class="hidden" id="emptyHeroInput" onchange="previewImage(this, 'emptyHeroPreview')">
                    <label for="emptyHeroInput" class="cursor-pointer w-full h-full flex flex-col items-center justify-center p-4">
                        <div id="emptyHeroPreview" class="w-24 h-24 mb-3 rounded-md bg-slate-800 flex items-center justify-center overflow-hidden">
                            <svg class="h-10 w-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
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

    {{-- ==================== SUBMIT BUTTON ==================== --}}
    <div class="flex justify-end gap-3">
        <button type="button"
                onclick="window.location.reload()"
                class="px-6 py-2.5 bg-slate-700 text-white font-semibold rounded-lg hover:bg-slate-600 transition">
            Reset
        </button>
        <button type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-yellow-500 to-orange-600
                       text-white font-semibold px-5 py-3 rounded-lg hover:from-yellow-600 hover:to-orange-700
                       transition-all shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
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
            preview.innerHTML = `<img src="${e.target.result}" class="max-h-full max-w-full object-contain rounded">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@endsection