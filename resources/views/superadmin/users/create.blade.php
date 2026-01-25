@extends('layouts.superadmin')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User')
@section('page-description', 'Buat user baru dalam sistem')

@section('content')
<div class="space-y-6">

    <form action="{{ route('superadmin.users.store') }}" 
          method="POST"
          class="space-y-6">
        @csrf

        <!-- Informasi Akun -->
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informasi Akun
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Nama Lengkap <span class="text-red-400"></span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="John Doe"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Email <span class="text-red-400"></span>
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('email') border-red-500 @enderror"
                           placeholder="user@example.com"
                           required>
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
                           name="phone" 
                           value="{{ old('phone') }}"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                           placeholder="0812-3456-7890">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Role <span class="text-red-400"></span>
                    </label>
                    <select name="role" 
                            id="roleSelect"
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('role') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Role</option>
                        <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin Kos</option>
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User/Penghuni</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Password <span class="text-red-400"></span>
                    </label>
                    <input type="password" 
                           name="password" 
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('password') border-red-500 @enderror"
                           placeholder="Minimal 8 karakter"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Konfirmasi Password <span class="text-red-400"></span>
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                           placeholder="Ulangi password"
                           required>
                </div>
            </div>

            <!-- Password Info -->
            <div class="mt-4 p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                <p class="text-xs text-yellow-300">
                    <strong>Info:</strong> Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka
                </p>
            </div>
        </div>

        <!-- Tempat Kos Assignment -->
        <div id="tempatKosSection" class="bg-slate-800 rounded-xl p-6 border border-slate-700 hidden">
            <h3 class="text-lg font-semibold text-slate-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Tempat Kos
            </h3>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Pilih Tempat Kos <span class="text-red-400" id="requiredStar"></span>
                </label>
                <select name="tempat_kos_id" 
                        id="tempatKosSelect"
                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('tempat_kos_id') border-red-500 @enderror">
                    <option value="">Pilih Tempat Kos</option>
                    @foreach($tempatKosList as $kos)
                    <option value="{{ $kos->id }}" {{ old('tempat_kos_id') == $kos->id ? 'selected' : '' }}>
                        {{ $kos->nama_kos }} - {{ $kos->kota }}
                    </option>
                    @endforeach
                </select>
                @error('tempat_kos_id')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror

                <p class="mt-2 text-xs text-slate-400" id="kosInfo">
                    Admin dan User harus terikat dengan tempat kos tertentu
                </p>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('superadmin.users.index') }}" 
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
                Simpan User
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    const role = this.value;
    const tempatKosSection = document.getElementById('tempatKosSection');
    const tempatKosSelect = document.getElementById('tempatKosSelect');
    const kosInfo = document.getElementById('kosInfo');
    const requiredStar = document.getElementById('requiredStar');
    
    if (role === 'admin' || role === 'user') {
        tempatKosSection.classList.remove('hidden');
        tempatKosSelect.required = true;
        requiredStar.classList.remove('hidden');
        
        if (role === 'admin') {
            kosInfo.textContent = 'Admin harus ditugaskan ke satu tempat kos tertentu';
        } else {
            kosInfo.textContent = 'User akan menjadi penghuni di tempat kos yang dipilih';
        }
    } else {
        tempatKosSection.classList.add('hidden');
        tempatKosSelect.required = false;
        tempatKosSelect.value = '';
    }
});

// Trigger on page load if there's old input
if (document.getElementById('roleSelect').value) {
    document.getElementById('roleSelect').dispatchEvent(new Event('change'));
}
</script>
@endpush
@endsection