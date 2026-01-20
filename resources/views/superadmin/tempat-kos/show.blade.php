@extends('layouts.superadmin')

@section('title', 'Detail Tempat Kos')
@section('page-title', $tempatKos->nama_kos)
@section('page-description', 'Informasi lengkap tempat kos')

@section('content')
<div class="space-y-6">

    <!-- Back & Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <a href="{{ route('superadmin.tempat-kos.index') }}" 
           class="inline-flex items-center gap-2
                    bg-gradient-to-r from-yellow-500 to-orange-600
                    text-white font-semibold
                    px-5 py-2 rounded-lg
                    hover:from-yellow-600 hover:to-orange-700
                    transition-all shadow-lg">
            Kembali ke Daftar Kos
        </a>

        <div class="flex gap-2">
            <a href="{{ route('superadmin.tempat-kos.edit', $tempatKos) }}" 
               class="inline-flex items-center gap-2
                    bg-gradient-to-r from-yellow-500 to-orange-600
                    text-white font-semibold
                    px-5 py-2 rounded-lg
                    hover:from-yellow-600 hover:to-orange-700
                    transition-all shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <!-- Header Info -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Logo -->
            <div class="flex-shrink-0">
                @if($tempatKos->logo)
                <img src="{{ Storage::url($tempatKos->logo) }}" 
                     alt="{{ $tempatKos->nama_kos }}"
                     class="w-32 h-32 rounded-xl object-cover border-2 border-slate-600">
                @else
                <div class="w-32 h-32 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-5xl">{{ substr($tempatKos->nama_kos, 0, 1) }}</span>
                </div>
                @endif
            </div>

            <!-- Info -->
            <div class="flex-1">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-100">{{ $tempatKos->nama_kos }}</h2>
                        <p class="text-slate-400 mt-1">{{ $tempatKos->kota }}, {{ $tempatKos->provinsi }}</p>
                    </div>
                    @if($tempatKos->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-500/20 text-red-400 border border-red-500/30">
                            <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                            Tidak Aktif
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div class="bg-slate-700 rounded-lg p-3">
                        <div class="text-2xl font-bold text-blue-400">{{ $stats['total_kamar'] }}</div>
                        <div class="text-xs text-slate-400">Total Kamar</div>
                    </div>
                    <div class="bg-slate-700 rounded-lg p-3">
                        <div class="text-2xl font-bold text-green-400">{{ $stats['kamar_terisi'] }}</div>
                        <div class="text-xs text-slate-400">Terisi</div>
                    </div>
                    <div class="bg-slate-700 rounded-lg p-3">
                        <div class="text-2xl font-bold text-orange-400">{{ $stats['kamar_tersedia'] }}</div>
                        <div class="text-xs text-slate-400">Tersedia</div>
                    </div>
                    <div class="bg-slate-700 rounded-lg p-3">
                        <div class="text-2xl font-bold text-purple-400">{{ $stats['total_penghuni'] }}</div>
                        <div class="text-xs text-slate-400">Penghuni</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Informasi Kontak -->
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Kontak
            </h3>
            <div class="space-y-3">
                <div>
                    <div class="text-xs text-slate-400 mb-1">Email</div>
                    <div class="text-sm text-slate-200">{{ $tempatKos->email ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs text-slate-400 mb-1">Telepon</div>
                    <div class="text-sm text-slate-200">{{ $tempatKos->telepon ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Alamat -->
        <div class="lg:col-span-2 bg-slate-800 rounded-xl p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Alamat
            </h3>
            <div class="space-y-2">
                <p class="text-slate-200">{{ $tempatKos->alamat }}</p>
                <p class="text-sm text-slate-400">
                    {{ $tempatKos->kota }}, {{ $tempatKos->provinsi }} 
                    @if($tempatKos->kode_pos)
                        {{ $tempatKos->kode_pos }}
                    @endif
                </p>
            </div>
        </div>

    </div>

    <!-- Daftar Admin -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-700 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-100">Daftar Admin ({{ $stats['total_admin'] }})</h3>
            <a href="{{ route('superadmin.users.create') }}?tempat_kos_id={{ $tempatKos->id }}" 
               class="text-sm text-yellow-400 hover:text-yellow-300 font-medium">
                + Tambah Admin
            </a>
        </div>
        
        @if($tempatKos->admins->count() > 0)
        <div class="divide-y divide-slate-700">
            @foreach($tempatKos->admins as $admin)
            <div class="p-4 hover:bg-slate-700/50 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                        <span class="text-white font-bold text-sm">{{ substr($admin->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <div class="font-medium text-slate-100">{{ $admin->name }}</div>
                        <div class="text-xs text-slate-400">{{ $admin->email }}</div>
                    </div>
                </div>
                <a href="{{ route('superadmin.users.show', $admin) }}" 
                   class="text-sm text-yellow-400 hover:text-yellow-300">
                    Detail →
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-8 text-center text-slate-400">
            Belum ada admin untuk tempat kos ini
        </div>
        @endif
    </div>

    <!-- Daftar Kamar -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100">Daftar Kamar ({{ $stats['total_kamar'] }})</h3>
        </div>
        
        @if($tempatKos->rooms->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">No. Kamar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Lantai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Harga</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-slate-300 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($tempatKos->rooms->take(10) as $room)
                    <tr class="hover:bg-slate-700/50">
                        <td class="px-6 py-3 text-slate-100">{{ $room->room_number }}</td>
                        <td class="px-6 py-3 text-slate-300">Lantai {{ $room->floor }}</td>
                        <td class="px-6 py-3 text-slate-300">Rp {{ number_format($room->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($room->status === 'available')
                                <span class="px-2 py-1 text-xs bg-green-500/20 text-green-400 rounded-full">Tersedia</span>
                            @elseif($room->status === 'occupied')
                                <span class="px-2 py-1 text-xs bg-blue-500/20 text-blue-400 rounded-full">Terisi</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-orange-500/20 text-orange-400 rounded-full">Maintenance</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($tempatKos->rooms->count() > 10)
        <div class="p-4 border-t border-slate-700 text-center">
            <p class="text-sm text-slate-400">Menampilkan 10 dari {{ $tempatKos->rooms->count() }} kamar</p>
        </div>
        @endif
        @else
        <div class="p-8 text-center text-slate-400">
            Belum ada kamar untuk tempat kos ini
        </div>
        @endif
    </div>

</div>
@endsection