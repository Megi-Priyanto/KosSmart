@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User: ' . $user->name)
@section('page-description', 'Perbarui informasi user')

@section('content')

<div class="max-w-3xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-gray-600 hover:text-purple-600 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar User
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8" x-data="{ showPassword: false, showPasswordConfirm: false }">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Info Banner -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-blue-700">
                        <strong>Info:</strong> Kosongkan password jika tidak ingin mengubahnya.
                    </p>
                </div>
            </div>
            
            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    required
                    autofocus
                >
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Alamat Email <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror"
                    required
                >
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Nomor Telepon -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Telepon <span class="text-gray-400 text-xs">(Opsional)</span>
                </label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                >
                @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password Baru -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Baru <span class="text-gray-400 text-xs">(Kosongkan jika tidak diubah)</span>
                </label>
                <div class="relative">
                    <input 
                        :type="showPassword ? 'text' : 'password'"
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('password') border-red-500 @enderror"
                        placeholder="Minimal 8 karakter"
                    >
                    <button 
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
                @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Konfirmasi Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password Baru
                </label>
                <div class="relative">
                    <input 
                        :type="showPasswordConfirm ? 'text' : 'password'"
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Ulangi password baru"
                    >
                    <button 
                        type="button"
                        @click="showPasswordConfirm = !showPasswordConfirm"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg x-show="!showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg x-show="showPasswordConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Role <span class="text-red-500">*</span>
                </label>
                <div class="space-y-3">
                    <label class="flex items-center p-4 border-2 {{ old('role', $user->role) === 'admin' ? 'border-purple-500 bg-purple-50' : 'border-gray-200' }} rounded-lg cursor-pointer hover:border-purple-500 transition-colors">
                        <input 
                            type="radio" 
                            name="role" 
                            value="admin" 
                            {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}
                            class="w-4 h-4 text-purple-600 focus:ring-purple-500"
                        >
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">Administrator</p>
                            <p class="text-sm text-gray-500">Akses penuh ke semua fitur sistem</p>
                        </div>
                    </label>
                    
                    <label class="flex items-center p-4 border-2 {{ old('role', $user->role) === 'user' ? 'border-purple-500 bg-purple-50' : 'border-gray-200' }} rounded-lg cursor-pointer hover:border-purple-500 transition-colors">
                        <input 
                            type="radio" 
                            name="role" 
                            value="user" 
                            {{ old('role', $user->role) === 'user' ? 'checked' : '' }}
                            class="w-4 h-4 text-purple-600 focus:ring-purple-500"
                        >
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">User Biasa</p>
                            <p class="text-sm text-gray-500">Akses terbatas, hanya data pribadi</p>
                        </div>
                    </label>
                </div>
                @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Warning jika mengedit diri sendiri -->
            @if($user->id === Auth::id())
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="text-sm text-yellow-700">
                        <strong>Perhatian:</strong> Anda sedang mengedit akun Anda sendiri. Berhati-hatilah saat mengubah role atau password.
                    </p>
                </div>
            </div>
            @endif
            
            <!-- Action Buttons -->
            <div class="flex space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" 
                   class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 text-center rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition-all font-medium">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Perbarui User
                </button>
            </div>
        </form>
    </div>
</div>

@endsection