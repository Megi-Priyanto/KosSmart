@extends('layouts.superadmin')

@section('title', 'Profile Super Admin')
@section('page-title', 'Profile Saya')
@section('page-description', 'Kelola informasi akun Super Admin Anda')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Statistik Aktivitas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-4 text-white">
            <div class="text-3xl font-bold">{{ $stats['total_tempat_kos'] }}</div>
            <div class="text-sm text-blue-100 mt-1">Tempat Kos</div>
        </div>
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl p-4 text-white">
            <div class="text-3xl font-bold">{{ $stats['total_admin'] }}</div>
            <div class="text-sm text-purple-100 mt-1">Admin Kos</div>
        </div>
        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl p-4 text-white">
            <div class="text-3xl font-bold">{{ $stats['total_user'] }}</div>
            <div class="text-sm text-green-100 mt-1">Penghuni</div>
        </div>
        <div class="bg-gradient-to-br from-orange-600 to-orange-700 rounded-xl p-4 text-white">
            <div class="text-3xl font-bold">{{ $stats['account_age_days'] }}</div>
            <div class="text-sm text-orange-100 mt-1">Hari Aktif</div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informasi Profile
            </h3>
        </div>

        <form action="{{ route('superadmin.profile.update') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

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
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                           placeholder="0812-3456-7890">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role (Read Only) -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Role
                    </label>
                    <div class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg">
                        <span class="inline-flex items-center text-slate-100">
                            <svg class="w-4 h-4 mr-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            Super Administrator
                        </span>
                    </div>
                </div>
            </div>

            <!-- Info Akun -->
            <div class="mt-6 p-4 bg-slate-700/50 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-slate-400">Email Verified:</span>
                        <span class="text-green-400 ml-2">
                            {{ $user->email_verified_at ? '✓ Terverifikasi' : '✗ Belum Verifikasi' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400">Akun Dibuat:</span>
                        <span class="text-slate-200 ml-2">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
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

    <!-- Change Password -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
                Ubah Password
            </h3>
        </div>

        <form action="{{ route('superadmin.profile.password') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <!-- Current Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Password Saat Ini <span class="text-red-400">*</span>
                    </label>
                    <input type="password" 
                           name="current_password" 
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('current_password') border-red-500 @enderror"
                           placeholder="Masukkan password saat ini"
                           required>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Password Baru <span class="text-red-400">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent @error('password') border-red-500 @enderror"
                           placeholder="Minimal 8 karakter"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-400">
                        Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka
                    </p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Konfirmasi Password Baru <span class="text-red-400">*</span>
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                           placeholder="Ulangi password baru"
                           required>
                </div>
            </div>

            <!-- Warning -->
            <div class="mt-4 p-4 bg-orange-500/10 border border-orange-500/30 rounded-lg flex items-start">
                <svg class="w-5 h-5 text-orange-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-sm text-orange-400 font-medium">Perhatian!</p>
                    <p class="text-xs text-orange-300 mt-1">Setelah mengubah password, Anda akan tetap login di perangkat ini.</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-lg font-medium transition shadow-lg">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection