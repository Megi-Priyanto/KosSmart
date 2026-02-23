@extends('layouts.superadmin')

@section('title', 'Dashboard Super Admin')
@section('page-title', 'Dashboard Super Admin')
@section('page-description', 'Overview sistem management kos secara keseluruhan')

@section('content')
<div class="space-y-6">

    {{-- ============================================================
         CARD NOTIFIKASI 1: REFUND CANCEL BOOKING MENUNGGU DIPROSES
         PERBAIKAN: Tambahkan card ini di dashboard superadmin agar
         refund yang perlu ditindaklanjuti terlihat langsung di dashboard,
         sama seperti card notifikasi dana masuk.
    ============================================================ --}}
    @php
        $refundPendingNotifs = \App\Models\CancelBooking::withoutTempatKosScope()
            ->where('status', 'admin_approved')
            ->with(['rent.room', 'user', 'tempatKos'])
            ->latest()
            ->take(5)
            ->get();

        $refundPendingCount = \App\Models\CancelBooking::withoutTempatKosScope()
            ->where('status', 'admin_approved')
            ->count();
    @endphp

    @if($refundPendingCount > 0)
    <div class="px-6 py-5 bg-slate-800 border border-orange-500/40 rounded-xl shadow-sm">
        <div class="flex items-start gap-4">
            <!-- Icon -->
            <div class="p-2 bg-orange-500/20 rounded-lg flex-shrink-0">
                <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
            </div>

            <div class="flex-1">
                <h3 class="font-semibold text-orange-300 mb-1">
                    {{ $refundPendingCount }} Refund Cancel Booking Menunggu Diproses
                </h3>
                <p class="text-sm text-slate-300 mb-4">
                    Admin kos telah menyetujui pembatalan booking. Silakan proses pengembalian dana DP ke rekening user.
                </p>

                <!-- List item refund -->
                <div class="space-y-2 mb-4">
                    @foreach($refundPendingNotifs as $cancel)
                    <div class="flex items-center justify-between px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-lg hover:border-orange-400/50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-orange-300">
                                    {{ $cancel->rent->room->room_number ?? '-' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-white">
                                    {{ $cancel->user->name }}
                                    <span class="text-xs text-slate-400 ml-1">• {{ $cancel->tempatKos->nama_kos ?? '-' }}</span>
                                </p>
                                <p class="text-xs text-slate-400">
                                    DP: <span class="text-orange-400 font-semibold">Rp {{ number_format($cancel->rent->deposit_paid ?? 0, 0, ',', '.') }}</span>
                                    • {{ $cancel->bank_name }} {{ $cancel->account_number }}
                                    • {{ $cancel->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('superadmin.refunds.show', $cancel) }}"
                           class="flex-shrink-0 text-sm font-medium text-orange-400 hover:text-orange-300 transition">
                            Proses →
                        </a>
                    </div>
                    @endforeach
                </div>

                <a href="{{ route('superadmin.refunds.index') }}"
                   class="inline-flex items-center text-sm font-medium text-orange-400 hover:text-orange-300 transition">
                    Lihat Semua Refund Pending
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================
         CARD NOTIFIKASI 2: DANA MASUK DARI ADMIN (Pembayaran Dikonfirmasi)
         Muncul hanya jika ada notifikasi yang belum diproses (holding)
    ============================================================ --}}
    @php
        $dashboardNotifs = \App\Models\Notification::where('user_id', auth()->id())
            ->where('type', 'payment')
            ->where('status', 'unread')
            ->with(['payment.billing.user', 'payment.billing.room', 'payment.tempatKos'])
            ->latest()
            ->take(5)
            ->get()
            ->filter(fn($n) => $n->payment && $n->payment->disbursement_status === 'holding');

        $dashboardNotifCount = \App\Models\Notification::where('user_id', auth()->id())
            ->where('type', 'payment')
            ->where('status', 'unread')
            ->whereHas('payment', fn($q) => $q->where('disbursement_status', 'holding'))
            ->count();
    @endphp

    @if($dashboardNotifCount > 0)
    <div class="px-6 py-5 bg-slate-800 border border-yellow-500/40 rounded-xl shadow-sm">
        <div class="flex items-start gap-4">
            <!-- Icon -->
            <div class="p-2 bg-yellow-500/20 rounded-lg flex-shrink-0">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2
                             m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1
                             m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <div class="flex-1">
                <h3 class="font-semibold text-yellow-300 mb-1">
                    {{ $dashboardNotifCount }} Dana Masuk Menunggu Pencairan
                </h3>
                <p class="text-sm text-slate-300 mb-4">
                    Admin kos telah mengkonfirmasi pembayaran user. Dana masuk ke holding platform dan siap dicairkan.
                </p>

                <!-- List item notifikasi -->
                <div class="space-y-2 mb-4">
                    @foreach($dashboardNotifs as $notif)
                    @php
                        $billing   = $notif->payment?->billing;
                        $user      = $billing?->user;
                        $room      = $billing?->room;
                        $kosName   = $notif->payment?->tempatKos?->nama_kos ?? '-';
                        $tipeLabel = match($billing?->tipe) {
                            'pelunasan' => 'Pelunasan',
                            'bulanan'   => 'Tagihan Bulanan',
                            'tahunan'   => 'Tagihan Tahunan',
                            default     => 'Pembayaran',
                        };
                        $amount = 'Rp ' . number_format($notif->payment?->amount ?? 0, 0, ',', '.');
                    @endphp
                    <div class="flex items-center justify-between px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-lg hover:border-yellow-400/50 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2
                                             m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-white">
                                    {{ $user?->name ?? '-' }}
                                    <span class="text-xs text-slate-400 ml-1">• Kamar {{ $room?->room_number ?? '-' }}</span>
                                </p>
                                <p class="text-xs text-slate-400">
                                    {{ $kosName }} • {{ $tipeLabel }} • <span class="text-yellow-400">{{ $amount }}</span>
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('superadmin.disbursements.create', [
                                'tempat_kos_id' => $notif->payment?->tempat_kos_id,
                                'payment_id'    => $notif->payment?->id,
                           ]) }}"
                           class="flex-shrink-0 text-sm font-medium text-yellow-400 hover:text-yellow-300 transition">
                            Proses →
                        </a>
                    </div>
                    @endforeach
                </div>

                <a href="{{ route('superadmin.notifications.index') }}"
                   class="inline-flex items-center text-sm font-medium text-yellow-400 hover:text-yellow-300 transition">
                    Lihat Semua Notifikasi Dana Masuk
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================
         STATISTIK CARDS
    ============================================================ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Tempat Kos -->
        <a href="{{ route('superadmin.tempat-kos.index') }}" class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-blue-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-300 text-sm font-medium">Total Tempat Kos</p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $totalTempatKos }}</h3>
                    <p class="text-sm text-gray-400 mt-1">{{ $totalTempatKosAktif }} aktif</p>
                </div>
                <div class="bg-blue-500/20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Total Admin -->
        <a href="{{ route('superadmin.users.index') }}" class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-purple-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-300 text-sm font-medium">Total Admin Kos</p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $totalAdmin }}</h3>
                    <p class="text-sm text-gray-400 mt-1">Mengelola {{ $totalTempatKosAktif }} kos</p>
                </div>
                <div class="bg-purple-500/20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Total User -->
        <a href="{{ route('superadmin.users.index') }}" class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-green-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-300 text-sm font-medium">Total Penghuni</p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $totalUser }}</h3>
                    <p class="text-sm text-gray-400 mt-1">User aktif</p>
                </div>
                <div class="bg-blue-500/20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Occupancy Rate -->
        <a href="{{ route('superadmin.users.index') }}" class="bg-slate-800 p-6 rounded-lg border border-slate-700 transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-lg hover:border-orange-600 hover:bg-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-300 text-sm font-medium">Tingkat Hunian</p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $occupancyRate }}%</h3>
                    <p class="text-sm text-gray-400 mt-1">{{ $totalKamarTerisi }}/{{ $totalKamar }} kamar</p>
                </div>
                <div class="bg-orange-500/20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </a>
    </div>

    {{-- ============================================================
         PENDAPATAN & QUICK ACTIONS
    ============================================================ --}}
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
                Rp {{ number_format($platformFeeThisMonth, 0, ',', '.') }}
            </p>
            <p class="text-sm text-slate-400 mt-2">
                Fee platform dari pencairan dana {{ now()->format('F Y') }}
            </p>
            @if($platformFeeChangePercent > 0)
                <p class="text-xs text-emerald-400 mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $platformFeeChangePercent }}% dari bulan lalu
                </p>
            @elseif($platformFeeChangePercent < 0)
                <p class="text-xs text-red-400 mt-2 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                    {{ abs($platformFeeChangePercent) }}% dari bulan lalu
                </p>
            @else
                <p class="text-xs text-slate-500 mt-2">Tidak ada perubahan dari bulan lalu</p>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="lg:col-span-2 bg-slate-800 rounded-xl p-6 border border-slate-700">
            <h3 class="text-lg font-semibold text-slate-100 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                <a href="{{ route('superadmin.tempat-kos.create') }}" 
                   class="flex flex-col items-center justify-center p-4 bg-slate-700 hover:bg-slate-600 rounded-lg transition">
                    <svg class="w-8 h-8 text-yellow-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
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

                <a href="{{ route('superadmin.billing-report.index') }}" 
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-xs text-slate-300 text-center">Pengaturan</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ============================================================
         DAFTAR TEMPAT KOS
    ============================================================ --}}
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
                                <span class="px-2 py-1 text-xs font-medium bg-green-500/20 text-green-400 rounded-full">Aktif</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium bg-red-500/20 text-red-400 rounded-full">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('superadmin.tempat-kos.show', $kos) }}"
                               class="inline-flex items-center justify-center w-9 h-9 rounded-xl
                                      bg-blue-600/20 text-blue-400 hover:bg-blue-600/30 hover:-translate-y-0.5
                                      transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
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