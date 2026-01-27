@extends('layouts.superadmin')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')
@section('page-description', 'Manajemen seluruh user dalam sistem')

@section('content')
<div class="space-y-6">

    <!-- Filter & Search -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <div class="flex flex-col md:flex-row gap-4 items-end justify-between">
            <form method="GET" action="{{ route('superadmin.users.index') }}" class="flex-1 flex flex-wrap gap-3">

                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari nama atau email user..."
                          class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500 flex-1 min-w-[200px]">
                </div>

                <select name="role" 
                        class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500">
                    <option value="">Semua Role</option>
                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin Kos</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User/Penghuni</option>
                </select>

                <select name="tempat_kos_id" 
                        class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500">
                    <option value="">Semua Tempat Kos</option>
                    @foreach($tempatKosList as $kos)
                    <option value="{{ $kos->id }}" {{ request('tempat_kos_id') == $kos->id ? 'selected' : '' }}>
                        {{ $kos->nama_kos }}
                    </option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button type="submit" 
                        class="inline-flex items-center gap-2
                            bg-gradient-to-r from-yellow-500 to-orange-600
                            text-white font-semibold
                            px-5 py-2 rounded-lg
                            hover:from-yellow-600 hover:to-orange-700
                            transition-all shadow-lg">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>
                    @if(request()->hasAny(['search', 'role', 'tempat_kos_id']))
                    <a href="{{ route('superadmin.users.index') }}" 
                       class="px-6 py-2 bg-slate-600 hover:bg-slate-500 text-white rounded-lg font-medium transition">
                        Reset
                    </a>
                    @endif
                </div>
            </form>

            <div class="flex gap-3">
                <a href="{{ route('superadmin.users.create') }}" 
                    class="inline-flex items-center gap-2
                        bg-gradient-to-r from-yellow-500 to-orange-600
                        text-white font-semibold
                        px-5 py-2 rounded-lg
                        hover:from-yellow-600 hover:to-orange-700
                        transition-all shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Tambah User
                </a>
            </div>

        </div>
    </div>

    <!-- Table -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">

                <thead class="bg-slate-800/80 border-b border-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Kontak</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase">Tempat Kos</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 
                                            {{ $user->role === 'super_admin' ? 'bg-gradient-to-br from-red-500 to-pink-500' : 
                                               ($user->role === 'admin' ? 'bg-purple-500' : 'bg-blue-500') }}">
                                    <span class="text-white font-bold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-100">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-400">ID: #{{ $user->id }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-300">{{ $user->email }}</div>
                            <div class="text-xs text-slate-500">{{ $user->phone ?? '-' }}</div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($user->role === 'super_admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                    </svg>
                                    Super Admin
                                </span>
                            @elseif($user->role === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-500/20 text-purple-400 border border-purple-500/30">
                                    Admin Kos
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                    User
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if($user->tempatKos)
                                <div class="text-sm text-slate-300">{{ $user->tempatKos->nama_kos }}</div>
                                <div class="text-xs text-slate-500">{{ $user->tempatKos->kota }}</div>
                            @else
                                <span class="text-slate-500 text-sm">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-orange-500/20 text-orange-400">
                                    Unverified
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('superadmin.users.show', $user) }}" 
                                   class="p-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded-lg transition"
                                   title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                
                                <a href="{{ route('superadmin.users.edit', $user) }}" 
                                   class="p-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded-lg transition"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                
                                @if($user->id !== auth()->id() && $user->role !== 'super_admin')
                                <!-- Hidden Form -->
                                <form id="delete-user-{{ $user->id }}" 
                                      action="{{ route('superadmin.users.destroy', $user) }}" 
                                      method="POST" 
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                
                                <!-- Delete Button with Alpine Modal -->
                                <button type="button"
                                        @click="$store.modal.confirmDelete(
                                            'Tindakan ini tidak dapat dibatalkan. Semua data user akan terhapus.',
                                            'delete-user-{{ $user->id }}',
                                            'Hapus User?'
                                        )"
                                        class="p-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition"
                                        title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-slate-400 text-lg font-medium">Tidak ada user ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-700">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>

@endsection