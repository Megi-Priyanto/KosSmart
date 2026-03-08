@extends('layouts.superadmin')

@section('title', 'Pendaftaran Admin Kos')
@section('page-title', 'Pendaftaran Admin Kos')
@section('page-description', 'Review dan verifikasi pengajuan pendaftaran admin kos baru')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
        <a href="{{ route('superadmin.admin-registrations.index', ['status' => 'pending']) }}"
           class="bg-slate-800 p-6 rounded-lg border {{ request('status') === 'pending' ? 'border-yellow-500/50' : 'border-slate-700' }}
                  transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-yellow-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-300 text-sm font-medium">Menunggu Review</p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $counts['pending'] }}</h3>
                    <p class="text-sm text-gray-400 mt-1">Pengajuan pending</p>
                </div>
                <div class="bg-yellow-500/20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </a>
    
        <a href="{{ route('superadmin.admin-registrations.index', ['status' => 'approved']) }}"
           class="bg-slate-800 p-6 rounded-lg border {{ request('status') === 'approved' ? 'border-green-500/50' : 'border-slate-700' }}
                  transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-green-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-300 text-sm font-medium">Disetujui</p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $counts['approved'] }}</h3>
                    <p class="text-sm text-gray-400 mt-1">Akun admin aktif</p>
                </div>
                <div class="bg-green-500/20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </a>
    
        <a href="{{ route('superadmin.admin-registrations.index', ['status' => 'rejected']) }}"
           class="bg-slate-800 p-6 rounded-lg border {{ request('status') === 'rejected' ? 'border-red-500/50' : 'border-slate-700' }}
                  transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-red-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-300 text-sm font-medium">Ditolak</p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $counts['rejected'] }}</h3>
                    <p class="text-sm text-gray-400 mt-1">Pengajuan ditolak</p>
                </div>
                <div class="bg-red-500/20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </a>
    
    </div>

    {{-- Filter & Search --}}
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
        <form method="GET" action="{{ route('superadmin.admin-registrations.index') }}" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, email, atau nama kos..."
                   class="flex-1 min-w-[200px] px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500">

            <select name="status" class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500">
                <option value="">Semua Status</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Menunggu Review</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>

            <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold px-5 py-2 rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari
            </button>

            @if(request()->hasAny(['search','status']))
            <a href="{{ route('superadmin.admin-registrations.index') }}" class="px-5 py-2 bg-slate-600 hover:bg-slate-500 text-white rounded-lg font-medium transition text-sm">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-slate-700">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Pemohon</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kos</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Lokasi</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($registrations as $reg)
                    <tr class="hover:bg-slate-700/40 transition">
                        <td class="px-5 py-4">
                            <div class="font-semibold text-slate-100 text-sm">{{ $reg->nama_lengkap }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $reg->email }}</div>
                            <div class="text-xs text-slate-500">{{ $reg->no_hp }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-sm text-slate-200">{{ $reg->nama_kos }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $reg->tipe_kepemilikan_label }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-sm text-slate-300">{{ $reg->kota }}</div>
                            <div class="text-xs text-slate-500">{{ $reg->provinsi }}</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($reg->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-500/15 text-yellow-400 border border-yellow-500/25">
                                    <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-1.5 animate-pulse"></span>
                                    Pending
                                </span>
                            @elseif($reg->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-500/15 text-green-400 border border-green-500/25">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></span>
                                    Disetujui
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/15 text-red-400 border border-red-500/25">
                                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full mr-1.5"></span>
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-sm text-slate-300">{{ $reg->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $reg->created_at->format('H:i') }}</div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <a href="{{ route('superadmin.admin-registrations.show', $reg) }}"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg
                                    bg-blue-600/20 text-blue-400 hover:bg-blue-500/30 hover:-translate-y-0.5
                                    transition-all duration-200"
                               title="Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <svg class="w-14 h-14 mx-auto text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-slate-400 font-medium">Belum ada pengajuan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($registrations->hasPages())
        <div class="px-5 py-4 border-t border-slate-700">
            {{ $registrations->links() }}
        </div>
        @endif
    </div>

</div>
@endsection