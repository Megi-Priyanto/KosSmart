@extends('layouts.superadmin')

@section('title', 'Detail User')
@section('page-title', $user->name)
@section('page-description', 'Informasi lengkap user')

@section('content')
<div class="space-y-6">

    <!-- Back & Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <a href="{{ route('superadmin.users.index') }}" 
               class="inline-flex items-center gap-2
                        bg-gradient-to-r from-yellow-500 to-orange-600
                        text-white font-semibold
                        px-5 py-2 rounded-lg
                        hover:from-yellow-600 hover:to-orange-700
                        transition-all shadow-lg">
                Kembali ke Daftar User
            </a>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('superadmin.users.edit', $user) }}" 
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

    <!-- Header Card -->
    <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <div class="w-32 h-32 rounded-xl flex items-center justify-center text-white font-bold text-5xl
                            {{ $user->role === 'super_admin' ? 'bg-gradient-to-br from-red-500 to-pink-500' : 
                               ($user->role === 'admin' ? 'bg-purple-500' : 'bg-blue-500') }}">
                    {{ substr($user->name, 0, 1) }}
                </div>
            </div>

            <!-- Info -->
            <div class="flex-1">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-100">{{ $user->name }}</h2>
                        <p class="text-slate-400 mt-1">{{ $user->email }}</p>
                    </div>
                    
                    @if($user->role === 'super_admin')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            Super Admin
                        </span>
                    @elseif($user->role === 'admin')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-500/20 text-purple-400 border border-purple-500/30">
                            Admin Kos
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                            User/Penghuni
                        </span>
                    @endif
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    <div class="bg-slate-700 rounded-lg p-3">
                        <div class="text-xs text-slate-400 mb-1">Telepon</div>
                        <div class="text-sm text-slate-200">{{ $user->phone ?? '-' }}</div>
                    </div>
                    <div class="bg-slate-700 rounded-lg p-3">
                        <div class="text-xs text-slate-400 mb-1">Email Status</div>
                        <div class="text-sm">
                            @if($user->email_verified_at)
                                <span class="text-green-400">✓ Verified</span>
                            @else
                                <span class="text-orange-400">Unverified</span>
                            @endif
                        </div>
                    </div>
                    <div class="bg-slate-700 rounded-lg p-3">
                        <div class="text-xs text-slate-400 mb-1">Terdaftar</div>
                        <div class="text-sm text-slate-200">{{ $user->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="bg-slate-700 rounded-lg p-3">
                        <div class="text-xs text-slate-400 mb-1">User ID</div>
                        <div class="text-sm text-slate-200">#{{ $user->id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Informasi Tempat Kos -->
        @if($user->tempatKos)
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Tempat Kos
            </h3>
            
            <div class="space-y-3">
                <div>
                    <div class="text-sm font-medium text-slate-300">{{ $user->tempatKos->nama_kos }}</div>
                    <div class="text-xs text-slate-400 mt-1">{{ $user->tempatKos->alamat }}</div>
                    <div class="text-xs text-slate-500">{{ $user->tempatKos->kota }}, {{ $user->tempatKos->provinsi }}</div>
                </div>
                
                <a href="{{ route('superadmin.tempat-kos.show', $user->tempatKos) }}" 
                   class="inline-flex items-center text-sm text-yellow-400 hover:text-yellow-300">
                    Lihat Detail Tempat Kos →
                </a>
            </div>
        </div>
        @else
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 mb-4">Tempat Kos</h3>
            <p class="text-slate-400 text-sm">User ini tidak terikat dengan tempat kos manapun</p>
        </div>
        @endif

        <!-- Aktivitas -->
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Aktivitas
            </h3>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-slate-700">
                    <span class="text-sm text-slate-400">Total Sewa</span>
                    <span class="text-sm font-medium text-slate-200">{{ $user->rents->count() }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-700">
                    <span class="text-sm text-slate-400">Total Tagihan</span>
                    <span class="text-sm font-medium text-slate-200">{{ $user->billings->count() }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-slate-400">Total Pembayaran</span>
                    <span class="text-sm font-medium text-slate-200">{{ $user->payments->count() }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Riwayat Sewa -->
    @if($user->rents->count() > 0)
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100">Riwayat Sewa ({{ $user->rents->count() }})</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Kamar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Harga</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-slate-300 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($user->rents->take(10) as $rent)
                    <tr class="hover:bg-slate-700/50">
                        <td class="px-6 py-3 text-slate-100">Kamar {{ $rent->room->room_number ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-slate-300">
                            {{ $rent->start_date->format('d M Y') }} - 
                            {{ $rent->end_date ? $rent->end_date->format('d M Y') : 'Sekarang' }}
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-300">
                            Rp {{ number_format($rent->monthly_rent, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $rent->status === 'active' ? 'bg-green-500/20 text-green-400' : 
                                   ($rent->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : 
                                   'bg-slate-500/20 text-slate-400') }}">
                                {{ ucfirst($rent->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection