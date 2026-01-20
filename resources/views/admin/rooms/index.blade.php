@extends('layouts.admin')

@section('title', 'Kelola Kamar')
@section('page-title', 'Kelola Kamar')
@section('page-description', 'Manajemen kamar kos dan status hunian')

@section('content')

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-purple-500 hover:bg-slate-700">
        
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Total Kamar</p>
                <p class="text-2xl font-bold text-slate-100">{{ $stats['total'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-green-500 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Tersedia</p>
                <p class="text-2xl font-bold text-slate-100">{{ $stats['available'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-blue-500 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Terisi</p>
                <p class="text-2xl font-bold text-slate-100">{{ $stats['occupied'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700
        transition-all duration-300 ease-out
        hover:-translate-y-1 hover:shadow-lg hover:border-orange-500 hover:bg-slate-700">

        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-400">Maintenance</p>
                <p class="text-2xl font-bold text-slate-100">{{ $stats['maintenance'] }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Actions -->
<div class="bg-slate-800 rounded-xl border border-slate-700 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Search & Filters -->
        <form method="GET" class="flex-1 flex flex-wrap gap-3">
            <!-- Search -->
            
            <div class="flex-1">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>

                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari nomor kamar..." 
                        class="w-full pl-10 pr-4 py-2 bg-slate-900 border border-slate-700 text-gray-100
                            rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent
                            placeholder-gray-500"
                    >
                </div>
            </div>
            
            <!-- Filter Status -->
            <select name="status" class="px-4 py-2 bg-slate-900 text-gray-100
                border border-slate-700 rounded-lg
                focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                transition-all duration-200">

                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ [
                            'available' => 'Tersedia',
                            'occupied' => 'Terisi',
                            'maintenance' => 'Maintenance'
                        ][$status] }}
                    </option>
                @endforeach
            </select>
            
            <!-- Filter Type -->
            <select name="type" class="px-4 py-2 bg-slate-900 text-gray-100
                border border-slate-700 rounded-lg
                focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                transition-all duration-200">

                <option value="">Semua Tipe</option>
                @foreach($types as $type)
                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                    {{ ucfirst($type) }}
                </option>
                @endforeach
            </select>
            
            <!-- Filter Floor -->
            <select name="floor" class="px-4 py-2 bg-slate-900 text-gray-100
                border border-slate-700 rounded-lg
                focus:ring-2 focus:ring-purple-500 focus:border-purple-500
                transition-all duration-200">
                
                <option value="">Semua Lantai</option>
                @foreach($floors as $floor)
                <option value="{{ $floor }}" {{ request('floor') == $floor ? 'selected' : '' }}>
                    {{ $floor }}
                </option>
                @endforeach
            </select>
            
            <!-- Buttons -->
            <button type="submit"
                class="inline-flex items-center gap-2
                    bg-gradient-to-r from-yellow-500 to-orange-600
                    text-white font-semibold
                    px-5 py-2 rounded-lg
                    hover:from-yellow-600 hover:to-orange-700
                    transition-all shadow-lg">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>
            
            @if(request()->hasAny(['search', 'status', 'type', 'floor']))
            <a href="{{ route('admin.rooms.index') }}" 
               class="px-4 py-2 border border-slate-600 text-slate-300 hover:bg-slate-700 rounded-lg">
                Reset
            </a>
            @endif
        </form>
        
        <!-- Add Room Button -->
        <a href="{{ route('admin.rooms.create') }}" 
           class="inline-flex items-center gap-2
              bg-gradient-to-r from-yellow-500 to-orange-600
              text-white font-semibold
              px-5 py-2 rounded-lg
              hover:from-yellow-600 hover:to-orange-700
              transition-all shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Kamar</span>
        </a>
    </div>
</div>

<!-- Rooms Table -->
<div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">

            <thead class="bg-slate-800/80 border-b border-slate-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Kamar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Lantai</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">Penyewa</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-400 uppercase">Aksi</th>
                </tr>
            </thead>

            <tbody class="bg-slate-800 divide-y divide-slate-700">
                @forelse($rooms as $room)
                <tr class="hover:bg-slate-700/40 transition">
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if(is_array($room->images) && count($room->images) > 0)
                                <img src="{{ asset('storage/' . $room->images[0]) }}" 
                                     alt="Kamar {{ $room->room_number }}" 
                                     class="h-10 w-10 rounded-lg object-cover">
                                @else
                                <div class="h-10 w-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"></path>
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-slate-100">
                                    Kamar {{ $room->room_number }}
                                </div>
                                <div class="text-sm text-slate-400">
                                    {{ $room->size }} m²
                                </div>
                            </div>
                        </div>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">
                        {{ $room->floor }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($room->type == 'putra') bg-blue-500/15 text-blue-400
                            @elseif($room->type == 'putri') bg-pink-500/15 text-pink-400
                            @else bg-purple-500/15 text-purple-400
                            @endif">
                            {{ ucfirst($room->type) }}
                        </span>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-slate-100">
                            Rp {{ number_format($room->price, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-slate-400">
                            {{ $room->jenis_sewa_label }}
                        </div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            @if($room->status == 'available') bg-emerald-500/15 text-emerald-400
                            @elseif($room->status == 'occupied') bg-blue-500/15 text-blue-400
                            @else bg-orange-500/15 text-orange-400
                            @endif">
                            {{ $room->status_label }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">
                        @if($room->currentRent)
                            <div class="text-sm text-slate-100">{{ $room->currentRent->user->name }}</div>
                            <div class="text-xs text-slate-400">{{ $room->currentRent->user->email }}</div>
                        @else
                            -
                        @endif
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center items-center gap-2">
                        
                            {{-- DETAIL --}}
                            <a href="{{ route('admin.rooms.show', $room) }}"
                               class="inline-flex items-center justify-center
                                      w-10 h-10 rounded-xl
                                      bg-blue-600/20 text-blue-400
                                      hover:bg-blue-600/30 hover:-translate-y-0.5
                                      transition-all duration-200"
                               title="Lihat Detail">
                        
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5
                                             c4.478 0 8.268 2.943 9.542 7
                                             -1.274 4.057-5.064 7-9.542 7
                                             -4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        
                            {{-- EDIT --}}
                            <a href="{{ route('admin.rooms.edit', $room) }}"
                               class="inline-flex items-center justify-center
                                      w-10 h-10 rounded-xl
                                      bg-yellow-500/20 text-yellow-400
                                      hover:bg-yellow-500/30 hover:-translate-y-0.5
                                      transition-all duration-200"
                               title="Edit">
                        
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                             m-1.414-9.414a2 2 0 112.828 2.828
                                             L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                        
                            {{-- DELETE --}}
                            <form action="{{ route('admin.rooms.destroy', $room) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus kamar {{ $room->room_number }}?')">
                                @csrf
                                @method('DELETE')
                        
                                <button type="submit"
                                        class="inline-flex items-center justify-center
                                               w-10 h-10 rounded-xl
                                               bg-red-600/20 text-red-400
                                               hover:bg-red-600/30 hover:-translate-y-0.5
                                               transition-all duration-200"
                                        title="Hapus">
                        
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                 a2 2 0 01-1.995-1.858L5 7
                                                 m5 4v6m4-6v6
                                                 M15 4a1 1 0 00-1-1h-4
                                                 a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <p class="text-lg font-medium mb-2">Tidak ada kamar</p>
                        <p class="text-sm">Belum ada kamar yang perlu tersedia</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($rooms->hasPages())
    <div class="px-6 py-4 border-t border-slate-700 bg-slate-800">
        {{ $rooms->links() }}
    </div>
    @endif
</div>

@endsection