@extends('layouts.admin')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')
@section('page-description', 'Buat akun user baru untuk sistem KosSmart')

@section('content')

<div class="w-full mx-auto">

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-yellow-400 hover:text-yellow-500 transition-colors font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar User
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-8"
         x-data="{ showPassword: false, showPasswordConfirm: false }">

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Nama Lengkap <span class="text-red-500"></span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full bg-slate-900 text-slate-100
                              border border-slate-700 rounded-lg px-4 py-3
                              focus:ring-2 focus:ring-purple-500 focus:border-transparent
                              @error('name') border-red-500 @enderror"
                       placeholder="Contoh: John Doe" required>
                @error('name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Alamat Email <span class="text-red-500"></span>
                </label>
                <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   autocomplete="new-email"
                   class="w-full bg-slate-900 text-slate-100
                          border border-slate-700 rounded-lg px-4 py-3
                          focus:ring-2 focus:ring-purple-500 focus:border-transparent
                          @error('email') border-red-500 @enderror"
                   placeholder="nama@email.com"
                   required>
                        
                @error('email')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Nomor Telepon <span class="text-slate-400 text-xs">(Opsional)</span>
                </label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                       class="w-full bg-slate-900 text-slate-100
                              border border-slate-700 rounded-lg px-4 py-3
                              focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Password <span class="text-red-500"></span>
                </label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'"
                       name="password"
                       autocomplete="new-password"
                       class="w-full bg-slate-900 text-slate-100
                              border border-slate-700 rounded-lg px-4 py-3 pr-12
                              focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                       placeholder="Minimal 8 karakter"
                       required>
                </div>
                <p class="text-xs text-slate-400 mt-1">
                    Minimal 8 karakter, kombinasi huruf dan angka
                </p>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Konfirmasi Password <span class="text-red-500"></span>
                </label>
                <div class="relative">
                    <input :type="showPasswordConfirm ? 'text' : 'password'"
                       name="password_confirmation"
                       autocomplete="new-password"
                       class="w-full bg-slate-900 text-slate-100
                              border border-slate-700 rounded-lg px-4 py-3 pr-12
                              focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                       required>
                </div>
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-3">
                    Role <span class="text-red-500"></span>
                </label>

                <div class="space-y-3">
                    <label class="flex items-center p-4 border border-slate-700 rounded-lg
                                  hover:border-blue-500 cursor-pointer">
                        <input type="radio" name="role" value="admin"
                               class="text-blue-600 focus:ring-blue-500">
                        <div class="ml-3">
                            <p class="font-medium text-slate-100">Administrator</p>
                            <p class="text-sm text-slate-400">Akses penuh sistem</p>
                        </div>
                    </label>

                    <label class="flex items-center p-4 border border-slate-700 rounded-lg
                                  hover:border-blue-500 cursor-pointer">
                        <input type="radio" name="role" value="user" checked
                               class="text-blue-600 focus:ring-blue-500">
                        <div class="ml-3">
                            <p class="font-medium text-slate-100">User</p>
                            <p class="text-sm text-slate-400">Akses terbatas</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-6 border-t border-slate-700">
                <a href="{{ route('admin.users.index') }}"
                   class="flex-1 py-3 text-center bg-slate-700 text-slate-200
                          rounded-lg hover:bg-slate-600 transition">
                    Batal
                </a>

                <button type="submit"
                        class="flex-1 py-3 bg-gradient-to-r from-yellow-500 via-orange-600 to-orange-700 text-white rounded-xl hover:from-yellow-600 hover:via-orange-700 hover:to-orange-600 transition-all">
                    Simpan User
                </button>
            </div>

        </form>
    </div>
</div>

@endsection