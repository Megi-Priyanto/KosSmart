@extends('layouts.admin')

@section('title', 'Profile Admin')
@section('page-title', 'Profile Admin')
@section('page-description', 'Kelola informasi akun administrator')

@section('content')
<div class="w-full space-y-6">
    
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
    <!-- Profile Information Card -->
    <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 overflow-hidden">
        <!-- Header Card -->
        <div class="bg-slate-800 px-6 py-5 border-b border-slate-700">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 text-2xl font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">{{ Auth::user()->name }}</h2>
                    <p class="text-sm text-slate-400">{{ Auth::user()->email }}</p>
                    <span class="inline-block mt-1 px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                        Administrator
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Edit Profile -->
        <div class="p-6">
            <h3 class="text-lg font-bold text-white mb-5 flex items-center">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                Informasi Personal
            </h3>

            <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">
                            Nama Lengkap <span class="text-red-500"></span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name', Auth::user()->name) }}"
                            class="w-full px-4 py-2.5 bg-slate-900 text-white border border-gray-300 rounded-lg focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition"
                            required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">
                            Email <span class="text-red-500"></span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email', Auth::user()->email) }}"
                            class="w-full px-4 py-2.5 bg-slate-900 text-white border border-gray-300 rounded-lg focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition"
                            required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">
                            Username
                        </label>
                        <input 
                            type="text" 
                            name="username" 
                            value="{{ old('username', Auth::user()->username) }}"
                            class="w-full px-4 py-2.5 bg-slate-900 text-white border border-gray-300 rounded-lg focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition"
                            placeholder="Opsional">
                        @error('username')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No. Telepon -->
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">
                            No. Telepon
                        </label>
                        <input 
                            type="text" 
                            name="phone" 
                            value="{{ old('phone', Auth::user()->phone) }}"
                            class="w-full px-5 py-3 bg-slate-900 text-white border border-gray-300 rounded-lg focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition"
                            placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button 
                        type="reset"
                        class="px-5 py-2 border border-gray-300 text-white font-medium rounded-lg hover:bg-gray-500 transition">
                        Reset
                    </button>
                    <button 
                        type="submit"
                        class="inline-flex items-center gap-2
                                bg-gradient-to-r from-yellow-500 to-orange-600
                                text-white font-semibold
                                px-5 py-3 rounded-lg
                                hover:from-yellow-600 hover:to-orange-700
                                transition-all shadow-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Card -->
    <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 overflow-hidden">
        <div class="bg-slate-800 px-6 py-4 border-b border-slate-700">
            <h3 class="text-lg font-bold text-white flex items-center">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                Ubah Password
            </h3>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Password Saat Ini -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">
                        Password Saat Ini <span class="text-red-500"></span>
                    </label>
                    <input 
                        type="password" 
                        name="current_password" 
                        class="w-full px-4 py-2.5 bg-slate-900 text-white border border-gray-300 rounded-lg focus:border-red-500 focus:ring focus:ring-red-200 transition"
                        required>
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Baru -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">
                        Password Baru <span class="text-red-500"></span>
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        class="w-full px-4 py-2.5 bg-slate-900 text-white border border-gray-300 rounded-lg focus:border-red-500 focus:ring focus:ring-red-200 transition"
                        required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-400 mt-1">Minimal 8 karakter</p>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-2">
                        Konfirmasi Password Baru <span class="text-red-500"></span>
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        class="w-full px-4 py-2.5 bg-slate-900 text-white border border-gray-300 rounded-lg focus:border-red-500 focus:ring focus:ring-red-200 transition"
                        required>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button 
                        type="reset"
                        class="px-5 py-2 border border-gray-300 text-white font-medium rounded-lg hover:bg-gray-500 transition">
                        Reset
                    </button>
                    <button 
                        type="submit"
                        class="px-5 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <!-- Account Info -->
    <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 overflow-hidden">
        <div class="bg-slate-800 px-6 py-4 border-b border-slate-700">
            <h3 class="text-lg font-bold text-white flex items-center">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Informasi Akun
            </h3>
        </div>

        <div class="p-6">
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div class="p-4 bg-slate-900 rounded-lg border border-gray-300">
                    <p class="text-white font-medium">Terdaftar Sejak</p>
                    <p class="text-slate-400 font-semibold mt-1">{{ Auth::user()->created_at->format('d F Y') }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-gray-300">
                    <p class="text-white font-medium">Terakhir Diperbarui</p>
                    <p class="text-slate-400 font-semibold mt-1">{{ Auth::user()->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection