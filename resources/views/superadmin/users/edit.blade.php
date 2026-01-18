@extends('layouts.superadmin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-description', 'Perbarui informasi user')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('superadmin.users.show', $user) }}" 
           class="inline-flex items-center text-slate-400 hover:text-slate-200 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Detail
        </a>
    </div>

    <form action="{{ route('superadmin.users.update', $user) }}" 
          method="POST"
          class="space-y-6">
        @csrf
        @method('PUT')

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
                        Nama Lengkap <span class="text-red-400">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('email') border-red-500 @enderror"
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
                           value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Role <span class="text-red-400">*</span>
                    </label>
                    <select name="role" 
                            id="roleSelect"
                            class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('role') border-red-500 @enderror"
                            required>
                        <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin Kos</option>
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User/Penghuni</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tempat Kos Assignment -->
        <div id="tempatKosSection" class="bg-slate-800 rounded-xl p-6 border border-slate-700 {{ in_array($user->role, ['admin', 'user']) ? '' : 'hidden' }}">
            <h3 class="text-lg font-semibold text-slate-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Tempat Kos
            </h3>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Pilih Tempat Kos <span class="text-red-400" id="requiredStar">*</span>
                </label>
                <select name="tempat_kos_id" 
                        id="tempatKosSelect"
                        class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('tempat_kos_id') border-red-500 @enderror">
                    <option value="">Pilih Tempat Kos</option>
                    @foreach($tempatKosList as $kos)
                    <option value="{{ $kos->id }}" {{ old('tempat_kos_id', $user->tempat_kos_id) == $kos->id ? 'selected' : '' }}>
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

        <!-- Change Password (Optional) -->
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                Ubah Password (Opsional)
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- New Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Password Baru
                    </label>
                    <input type="password" 
                           name="password" 
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('password') border-red-500 @enderror"
                           placeholder="Kosongkan jika tidak ingin mengubah">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Konfirmasi Password
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                           placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="mt-4 p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                <p class="text-xs text-blue-300">
                    Kosongkan field password jika tidak ingin mengubahnya. Password baru harus minimal 8 karakter.
                </p>
            </div>
        </div>

        <!-- Info Akun -->
        <div class="bg-slate-700/50 rounded-xl p-4 border border-slate-600">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-slate-400">User ID:</span>
                    <span class="text-slate-200 ml-2">#{{ $user->id }}</span>
                </div>
                <div>
                    <span class="text-slate-400">Terdaftar:</span>
                    <span class="text-slate-200 ml-2">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="text-slate-400">Email Verified:</span>
                    <span class="{{ $user->email_verified_at ? 'text-green-400' : 'text-orange-400' }} ml-2">
                        {{ $user->email_verified_at ? '✓ Terverifikasi' : '✗ Belum Verifikasi' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('superadmin.users.show', $user) }}" 
               class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-slate-100 rounded-lg font-medium transition">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white rounded-lg font-medium transition shadow-lg">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
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

// Trigger on page load
document.getElementById('roleSelect').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection