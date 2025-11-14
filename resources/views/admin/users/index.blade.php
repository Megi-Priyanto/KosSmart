@extends('layouts.admin')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')
@section('page-description', 'Manajemen user dan akses sistem KosSmart')

@section('content')

<!-- Search & Filter Bar -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4">
        <!-- Search Box -->
        <div class="flex-1">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari nama atau email..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                >
            </div>
        </div>
        
        <!-- Filter Role -->
        <div class="w-full md:w-48">
            <select 
                name="role" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                onchange="this.form.submit()"
            >
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>
        
        <!-- Search Button -->
        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            Cari
        </button>
        
        <!-- Add User Button -->
        <a href="{{ route('admin.users.create') }}" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-center">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah User
        </a>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Telepon</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Terdaftar</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors" x-data="{ showDeleteModal: false }">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $user->id }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-purple-600 font-semibold">{{ substr($user->name, 0, 2) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->phone ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $user->email_verified_at ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $user->email_verified_at ? '✓ Aktif' : 'Belum Verifikasi' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center space-x-2">
                            <!-- View Button -->
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                               title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            
                            <!-- Edit Button -->
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                               title="Edit User">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            
                            <!-- Delete Button (disabled untuk diri sendiri) -->
                            @if($user->id !== Auth::id())
                            <button 
                                @click="showDeleteModal = true"
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                title="Hapus User">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                            @else
                            <button 
                                disabled
                                class="p-2 text-gray-400 cursor-not-allowed rounded-lg"
                                title="Tidak dapat menghapus diri sendiri">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            @endif
                            
                            <!-- Delete Confirmation Modal -->
                            <div 
                                x-show="showDeleteModal" 
                                x-cloak
                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                                @click.self="showDeleteModal = false">
                                <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi Hapus User</h3>
                                    <p class="text-gray-600 mb-6">
                                        Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>? 
                                        Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                    <div class="flex space-x-3">
                                        <button 
                                            @click="showDeleteModal = false"
                                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                                            Batal
                                        </button>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit"
                                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                                Ya, Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">Tidak ada user ditemukan</p>
                        @if(request('search') || request('role'))
                        <a href="{{ route('admin.users.index') }}" class="text-purple-600 hover:text-purple-700 font-medium mt-2 inline-block">
                            Reset Filter
                        </a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush