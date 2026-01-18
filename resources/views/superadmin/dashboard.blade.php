@extends('layouts.superadmin')

@section('title', 'Dashboard Super Admin')
@section('page-title', 'Dashboard Super Admin')
@section('page-description', 'Overview sistem management kos secara keseluruhan')

@section('content')
<div class="space-y-6">

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Tempat Kos -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Tempat Kos</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalTempatKos }}</h3>
                    <p class="text-blue-200 text-xs mt-1">
                        {{ $totalTempatKosAktif }} aktif
                    </p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Admin -->
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Admin Kos</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalAdmin }}</h3>
                    <p class="text-purple-200 text-xs mt-1">
                        Mengelola {{ $totalTempatKosAktif }} kos
                    </p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total User -->
        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Penghuni</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalUser }}</h3>
                    <p class="text-green-200 text-xs mt-1">
                        User aktif
                    </p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Occupancy Rate -->
        <div class="bg-gradient-to-br from-orange-600 to-orange-700 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Tingkat Hunian</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $occupancyRate }}%</h3>
                    <p class="text-orange-200 text-xs mt-1">
                        {{ $totalKamarTerisi }}/{{ $totalKamar }} kamar
                    </p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <!-- Pendapatan & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Pendapatan Bulan Ini -->
        <div class="lg:col-span-1 bg-slate-800 rounded-xl p-6 border border-slate-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-100">Pendapatan Bulan Ini</h3>
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-green-400">
                Rp {{ number_format($monthlyIncome, 0, ',', '.') }}
            </p>
            <p class="text-sm text-slate-400 mt-2">
                Total pendapatan dari semua tempat kos
            </p>
        </div>

        <!-- Quick Actions -->
        <div class="lg:col-span-2 bg-slate-800 rounded-xl p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                <a href="{{ route('superadmin.tempat-kos.create') }}" 
                   class="flex flex-col items-center justify-center p-4 bg-slate-700 hover:bg-slate-600 rounded-lg transition">
                    <svg class="w-8 h-8 text-yellow-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span class="text-xs text-slate-300 text-center">Tambah Kos</span>
                </a>

                <a href="{{ route('superadmin.users.create') }}" 
                   class="flex flex-col items-center justify-center p-4 bg-slate-700 hover:bg-slate-600 rounded-lg transition">
                    <svg class="w-8 h-8 text-purple-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span class="text-xs text-slate-300 text-center">Tambah Admin</span>
                </a>

                <a href="#" 
                   class="flex flex-col items-center justify-center p-4 bg-slate-700 hover:bg-slate-600 rounded-lg transition">
                    <svg class="w-8 h-8 text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-xs text-slate-300 text-center">Lihat Laporan</span>
                </a>

                <a href="{{ route('superadmin.settings.index') }}" 
                   class="flex flex-col items-center justify-center p-4 bg-slate-700 hover:bg-slate-600 rounded-lg transition">
                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                    <span class="text-xs text-slate-300 text-center">Pengaturan</span>
                </a>

            </div>
        </div>

    </div>

    <!-- Daftar Tempat Kos -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-700 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-100">Daftar Tempat Kos</h3>
            <a href="{{ route('superadmin.tempat-kos.index') }}" 
               class="text-sm text-yellow-400 hover:text-yellow-300 font-medium">
                Lihat Semua →
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Nama Kos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Lokasi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-slate-300 uppercase">Kamar</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-slate-300 uppercase">Penghuni</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-slate-300 uppercase">Admin</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-slate-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-slate-300 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($tempatKosList as $kos)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-100">{{ $kos->nama_kos }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-400">
                            {{ $kos->kota }}, {{ $kos->provinsi }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-300">
                            {{ $kos->rooms_count }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-300">
                            {{ $kos->penghuni_count }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-slate-300">
                            {{ $kos->admins_count }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($kos->status === 'active')
                                <span class="px-2 py-1 text-xs font-medium bg-green-500/20 text-green-400 rounded-full">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium bg-red-500/20 text-red-400 rounded-full">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('superadmin.tempat-kos.show', $kos) }}" 
                               class="text-yellow-400 hover:text-yellow-300 text-sm font-medium">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                            Belum ada tempat kos terdaftar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($tempatKosList->hasPages())
        <div class="p-4 border-t border-slate-700">
            {{ $tempatKosList->links() }}
        </div>
        @endif
    </div>

</div>
@endsection