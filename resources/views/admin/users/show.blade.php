@extends('layouts.admin')

@section('title', 'Detail User')
@section('page-title', 'Detail User')
@section('page-description', 'Informasi lengkap user ' . $user->name)

@section('content')

<div class="max-w-4xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-yellow-400 hover:text-yellow-500 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar User
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 p-6">
                <!-- Avatar -->
                <div class="text-center mb-6">
                    <div class="w-32 h-32 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl font-bold text-white">{{ substr($user->name, 0, 2) }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-100">{{ $user->name }}</h3>
                    <p class="text-slate-400">{{ $user->email }}</p>
                    
                    <!-- Role Badge -->
                    <div class="mt-4">
                        <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-900/40 text-purple-300' : 'bg-blue-900/40 text-blue-300' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="mt-3">
                        <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $user->email_verified_at ? 'bg-green-900/40 text-green-300' : 'bg-orange-900/40 text-orange-300' }}">
                            {{ $user->email_verified_at ? '✓ Akun Aktif' : 'Belum Verifikasi' }}
                        </span>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="space-y-2">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="flex items-center justify-center w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit User
                    </a>
                    
                    @if($user->id !== Auth::id())
                    <button 
                        @click="$store.modal.deleteUser = true"
                        class="flex items-center justify-center w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus User
                    </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Details Card -->
        <div class="lg:col-span-2 space-y-6" x-data="{ showDeleteModal: false }">
            
            <!-- Informasi Pribadi -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl">
                <div class="p-6 border-b border-slate-700">
                    <h3 class="text-lg font-bold text-slate-100">Informasi Pribadi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-slate-700">
                        
                        <span class="text-slate-400">Nama Lengkap</span>
                        <span class="font-medium text-slate-100">{{ $user->name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-700">
                        <span class="text-slate-400">Email</span>
                        <span class="font-medium text-slate-100">{{ $user->email }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-700">
                        <span class="text-slate-400">Nomor Telepon</span>
                        <span class="font-medium text-slate-100">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-700">
                        <span class="text-slate-400">Role</span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-900/40 text-purple-300' : 'bg-blue-900/40 text-blue-300' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <span class="text-slate-400">Status Akun</span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user->email_verified_at ? 'bg-green-900/40 text-green-300' : 'bg-orange-900/40 text-orange-300' }}">
                            {{ $user->email_verified_at ? '✓ Terverifikasi' : 'Belum Verifikasi' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Informasi Akun -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl">
                <div class="p-6 border-b border-slate-700">
                    <h3 class="text-lg font-bold text-slate-100">Informasi Akun</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-slate-700">
                        <span class="text-slate-400">ID User</span>
                        <span class="font-medium text-slate-100">#{{ $user->id }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-700">
                        <span class="text-slate-400">Tanggal Daftar</span>
                        <span class="font-medium text-slate-100">{{ $user->created_at->format('d F Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-700">
                        <span class="text-slate-400">Terakhir Update</span>
                        <span class="font-medium text-slate-100">{{ $user->updated_at->format('d F Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <span class="text-slate-400">Email Verified At</span>
                        <span class="font-medium text-slate-100">
                            {{ $user->email_verified_at ? $user->email_verified_at->format('d F Y, H:i') : 'Belum verifikasi' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Aktivitas (Placeholder) -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl">
                <div class="p-6 border-b border-slate-700">
                    <h3 class="text-lg font-bold text-slate-100">Aktivitas Terbaru</h3>
                </div>
                <div class="p-6">
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-slate-400">Belum ada aktivitas tercatat</p>
                        <p class="text-sm text-slate-500 mt-1">Fitur ini akan segera hadir</p>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div 
                x-show="$store.modal.deleteUser"
                x-cloak
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                @click.self="$store.modal.deleteUser = false">

                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 w-full max-w-md">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-100 mb-2 text-center">Konfirmasi Hapus User</h3>
                        <p class="text-slate-400 text-center mb-6">
                            Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>? 
                            Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                    <div class="flex gap-3 items-stretch">
                        <button 
                            type="button"
                            @click="$store.modal.deleteUser = false"
                            class="flex-1 h-12 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                            Batal
                        </button>
                    
                        <form 
                            action="{{ route('admin.users.destroy', $user) }}" 
                            method="POST" 
                            class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit"
                                class="w-full h-12 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
</div>

@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush