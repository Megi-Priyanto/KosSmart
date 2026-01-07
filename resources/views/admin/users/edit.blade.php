@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User: ' . $user->name)
@section('page-description', 'Perbarui informasi user')

@section('content')

<div class="w-full mx-auto">

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center text-yellow-400 hover:text-yellow-500 transition">
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

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Info Banner -->
            <div class="bg-yellow-700/20 border-l-4 border-yellow-500 p-4 rounded">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0" />
                    </svg>
                    <p class="text-sm text-yellow-300">
                        <strong>Info:</strong> Kosongkan password jika tidak ingin mengubahnya.
                    </p>
                </div>
            </div>

            <!-- Nama -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="w-full bg-slate-900 text-slate-100
                              border border-slate-700 rounded-lg px-4 py-3
                              focus:ring-2 focus:ring-purple-500 focus:border-transparent
                              @error('name') border-red-500 @enderror"
                       required autofocus>
                @error('name')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Alamat Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       class="w-full bg-slate-900 text-slate-100
                              border border-slate-700 rounded-lg px-4 py-3
                              focus:ring-2 focus:ring-purple-500 focus:border-transparent
                              @error('email') border-red-500 @enderror"
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
                <input type="tel" name="phone"
                       value="{{ old('phone', $user->phone) }}"
                       class="w-full bg-slate-900 text-slate-100
                              border border-slate-700 rounded-lg px-4 py-3
                              focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Password Baru <span class="text-slate-400 text-xs">(Opsional)</span>
                </label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'"
                           name="password"
                           class="w-full bg-slate-900 text-slate-100
                                  border border-slate-700 rounded-lg px-4 py-3 pr-12
                                  focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Minimal 8 karakter">
                    <button type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2
                                   text-slate-400 hover:text-slate-200">
                        👁
                    </button>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Konfirmasi Password Baru
                </label>
                <div class="relative">
                    <input :type="showPasswordConfirm ? 'text' : 'password'"
                           name="password_confirmation"
                           class="w-full bg-slate-900 text-slate-100
                                  border border-slate-700 rounded-lg px-4 py-3 pr-12
                                  focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <button type="button"
                            @click="showPasswordConfirm = !showPasswordConfirm"
                            class="absolute right-3 top-1/2 -translate-y-1/2
                                   text-slate-400 hover:text-slate-200">
                        👁
                    </button>
                </div>
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-3">
                    Role <span class="text-red-500">*</span>
                </label>

                <div class="space-y-3">
                    <label
                        class="flex items-center p-4 border rounded-lg cursor-pointer
                               {{ old('role', $user->role) === 'admin'
                                   ? 'border-yellow-500 bg-yellow-800/30'
                                   : 'border-slate-700 hover:border-yellow-500' }}">
                        <input type="radio" name="role" value="admin"
                               {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}
                               class="text-yellow-600 focus:ring-yellow-500">
                        <div class="ml-3">
                            <p class="font-medium text-slate-100">Administrator</p>
                            <p class="text-sm text-slate-400">Akses penuh sistem</p>
                        </div>
                    </label>

                    <label
                        class="flex items-center p-4 border rounded-lg cursor-pointer
                               {{ old('role', $user->role) === 'user'
                                   ? 'border-yellow-500 bg-yellow-800/30'
                                   : 'border-slate-700 hover:border-yellow-500' }}">
                        <input type="radio" name="role" value="user"
                               {{ old('role', $user->role) === 'user' ? 'checked' : '' }}
                               class="text-yellow-600 focus:ring-yellow-500">
                        <div class="ml-3">
                            <p class="font-medium text-slate-100">User</p>
                            <p class="text-sm text-slate-400">Akses terbatas</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Warning edit diri sendiri -->
            @if($user->id === Auth::id())
            <div class="bg-yellow-900/30 border-l-4 border-yellow-500 p-4 rounded">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-sm text-yellow-300">
                        <strong>Perhatian:</strong> Anda sedang mengedit akun Anda sendiri.
                    </p>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex gap-4 pt-6 border-t border-slate-700">
                <a href="{{ route('admin.users.index') }}"
                   class="flex-1 py-3 text-center bg-slate-700 text-slate-200
                          rounded-lg hover:bg-slate-600 transition">
                    Batal
                </a>

                <button type="submit"
                        class="flex-1 py-3 text-center bg-orange-500 text-slate-200
                          rounded-lg hover:bg-orange-600 transition">
                    Perbarui User
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
