@extends('layouts.admin')

@section('title', 'Detail Booking')
@section('page-title', 'Detail Booking')
@section('page-description', 'Informasi lengkap booking dan approval')

@section('content')

@php
    $activeCancelBooking   = $booking->getActiveCancelBooking();
    $approvedCancelBooking = $booking->getApprovedCancelBooking();
@endphp

<div class="mb-6 flex justify-between items-center">
    <!-- Status Badge -->
    <div class="flex space-x-3">
        <span class="inline-flex items-center px-5 py-3 rounded-lg text-lg font-bold shadow-lg
            @if($booking->status == 'pending')    bg-yellow-500/20 text-yellow-300 border-2 border-yellow-500/50
            @elseif($booking->status == 'active')     bg-green-500/20  text-green-300  border-2 border-green-500/50
            @elseif($booking->status == 'cancelled')  bg-red-500/20    text-red-300    border-2 border-red-500/50
            @else bg-slate-500/20 text-slate-300 border-2 border-slate-500/50
            @endif">
            @if($booking->status == 'pending')
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Menunggu Persetujuan
            @elseif($booking->status == 'active')
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Booking Aktif
            @elseif($booking->status == 'cancelled')
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Dibatalkan
            @endif
        </span>

        {{-- Badge tambahan jika ada cancel booking aktif --}}
        @if($activeCancelBooking)
            <span class="inline-flex items-center px-4 py-3 rounded-lg text-sm font-bold
                         bg-orange-500/20 text-orange-300 border-2 border-orange-500/50">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                @if($activeCancelBooking->status === 'pending')
                    Ada Pengajuan Cancel
                @else
                    Cancel Disetujui Admin
                @endif
            </span>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- ===================== LEFT COLUMN ===================== -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Info Penyewa -->
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                Informasi Penyewa
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide flex items-center mb-2">
                        Nama Lengkap
                    </label>
                    <p class="font-bold text-white">{{ $booking->user->name }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide flex items-center mb-2">
                        Email
                    </label>
                    <p class="font-semibold text-white">{{ $booking->user->email }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide flex items-center mb-2">
                        Nomor Telepon
                    </label>
                    <p class="font-semibold text-white">{{ $booking->user->phone ?? '-' }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide flex items-center mb-2">
                        Terdaftar Sejak
                    </label>
                    <p class="font-semibold text-white">{{ $booking->user->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <!-- Quick Contact -->
            <div class="mt-4 pt-4 border-t border-slate-700 flex gap-3">
                @if($booking->user->phone)
                <a href="tel:{{ $booking->user->phone }}"
                   class="flex-1 text-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700
                          font-semibold text-sm flex items-center justify-center transition-all shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Telepon
                </a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->user->phone) }}"
                   target="_blank"
                   class="flex-1 text-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700
                          font-semibold text-sm flex items-center justify-center transition-all shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    WhatsApp
                </a>
                @endif
            </div>
        </div>

        <!-- Info Kamar -->
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                Informasi Kamar
            </h3>

            @if(is_array($booking->room->images) && count($booking->room->images) > 0)
                <div class="mb-6">
                    <img src="{{ asset('storage/' . $booking->room->images[0]) }}"
                         alt="Kamar {{ $booking->room->room_number }}"
                         class="w-full h-64 object-cover rounded-lg border-2 border-slate-600 shadow-lg">
                </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-2 block">Nomor Kamar</label>
                    <p class="font-bold text-xl text-white">{{ $booking->room->room_number }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-2 block">Lantai</label>
                    <p class="font-semibold text-white">{{ $booking->room->floor }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-2 block">Tipe</label>
                    <span class="px-3 py-1.5 text-sm font-bold rounded-lg
                        @if($booking->room->type == 'putra')  bg-blue-500/20 text-blue-300 border border-blue-500/50
                        @elseif($booking->room->type == 'putri') bg-pink-500/20 text-pink-300 border border-pink-500/50
                        @else bg-purple-500/20 text-purple-300 border border-purple-500/50
                        @endif">
                        {{ ucfirst($booking->room->type) }}
                    </span>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-2 block">Ukuran</label>
                    <p class="font-semibold text-white">{{ $booking->room->size }} m²</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-700">
                <a href="{{ route('admin.rooms.show', $booking->room->id) }}"
                   class="text-yellow-400 hover:text-yellow-500 text-sm font-semibold inline-flex items-center">
                    Lihat Detail Kamar
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Informasi Pembayaran DP -->
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                Informasi Pembayaran DP
            </h3>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="p-4 bg-slate-900 rounded-lg">
                    <label class="text-xs text-slate-400 uppercase mb-2 block">Metode Pembayaran</label>
                    <p class="font-bold text-white">{{ $booking->payment_method_label }}</p>
                    <p class="text-sm text-slate-300">{{ $booking->payment_sub_method_label }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg">
                    <label class="text-xs text-slate-400 uppercase mb-2 block">Status Pembayaran</label>
                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-bold {{ $booking->dp_payment_status_badge }}">
                        {{ $booking->dp_payment_status_label }}
                    </span>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg">
                    <label class="text-xs text-slate-400 uppercase mb-2 block">Jumlah DP</label>
                    <p class="text-xl font-bold text-yellow-400">Rp {{ number_format($booking->deposit_paid, 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg">
                    <label class="text-xs text-slate-400 uppercase mb-2 block">Sisa Pembayaran</label>
                    <p class="text-xl font-bold text-orange-400">
                        Rp {{ number_format($booking->monthly_rent - $booking->deposit_paid, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Bukti Pembayaran -->
            @if($booking->notes && str_contains($booking->notes, 'Bukti DP:'))
            <div class="border-t border-slate-700 pt-6">
                <h4 class="font-bold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Bukti Pembayaran {{ $booking->payment_method_label }}
                </h4>

                @php $imagePath = str_replace('Bukti DP: ', '', $booking->notes); @endphp

                <div class="bg-slate-900 rounded-lg p-4 border border-slate-700">
                    <img src="{{ asset('storage/' . $imagePath) }}"
                         alt="Bukti Pembayaran"
                         class="w-full max-w-md rounded-lg cursor-pointer"
                         onclick="openImageModal('{{ asset('storage/' . $imagePath) }}')">

                    <div class="mt-4 flex gap-3">
                        <a href="{{ asset('storage/' . $imagePath) }}" target="_blank"
                           class="flex-1 bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700 text-center text-sm">
                            Lihat Full Size
                        </a>
                        <a href="{{ asset('storage/' . $imagePath) }}" download
                           class="flex-1 bg-green-600 text-white px-5 py-3 rounded-lg hover:bg-green-700 text-center text-sm">
                            Download
                        </a>
                    </div>
                </div>

                <div class="mt-4 p-4 rounded-lg
                    @if($booking->payment_method === 'e_wallet') bg-green-500/10 border border-green-500/30
                    @else bg-yellow-500/10 border border-yellow-500/30
                    @endif">
                    <p class="text-sm
                        @if($booking->payment_method === 'e_wallet') text-green-300
                        @else text-yellow-300
                        @endif">
                        <strong>Validasi:</strong> Pastikan bukti pembayaran sesuai dengan metode
                        <strong>{{ $booking->payment_method_label }}</strong>
                        @if($booking->payment_sub_method)
                            via <strong>{{ $booking->payment_sub_method_label }}</strong>
                        @endif
                    </p>
                </div>
            </div>
            @endif

            @if($booking->dp_payment_status === 'rejected' && $booking->dp_rejection_reason)
            <div class="mt-4 p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
                <p class="text-sm text-red-300">
                    <strong>Alasan Penolakan:</strong> {{ $booking->dp_rejection_reason }}
                </p>
                @if($booking->dpVerifier)
                <p class="text-xs text-red-400 mt-2">
                    Ditolak oleh: {{ $booking->dpVerifier->name }}
                    pada {{ $booking->dp_verified_at->format('d M Y, H:i') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <!-- Modal Gambar -->
        <div id="imageModal"
             class="hidden fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center p-4"
             onclick="closeImageModal()">
            <div class="relative max-w-5xl max-h-[90vh] w-full" onclick="event.stopPropagation()">
                <button onclick="closeImageModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img id="modalImage" src="" alt="Bukti Transfer"
                     class="w-full h-auto rounded-lg shadow-2xl">
                <p class="text-center text-white mt-4 text-sm">Klik di luar gambar atau tombol X untuk menutup</p>
            </div>
        </div>

        <!-- Info Tanggal -->
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                Jadwal
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-2 block">Tanggal Booking</label>
                    <p class="font-semibold text-white">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <label class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-2 block">Mulai Sewa</label>
                    <p class="font-semibold text-white">{{ $booking->start_date->format('d M Y') }}</p>
                </div>
                @if($booking->approved_at)
                <div class="p-4 bg-green-500/10 rounded-lg border border-green-500/30 md:col-span-2">
                    <label class="text-xs text-green-400 font-semibold uppercase tracking-wide mb-2 block">
                        Disetujui Pada
                    </label>
                    <p class="font-bold text-green-300">{{ $booking->approved_at->format('d M Y, H:i') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Admin Notes -->
        @if($booking->admin_notes)
        <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-6 shadow-xl">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white">Catatan Admin</h3>
            </div>
            <p class="text-slate-200 leading-relaxed">{{ $booking->admin_notes }}</p>
            @if($booking->approved_by)
            <p class="text-sm text-slate-400 mt-3 pt-3 border-t border-blue-500/20">
                Oleh: <span class="font-semibold text-blue-300">
                    {{ \App\Models\User::find($booking->approved_by)->name ?? 'Admin' }}
                </span>
            </p>
            @endif
        </div>
        @endif

    </div>

    <!-- ===================== RIGHT COLUMN ===================== -->
    <div class="space-y-6">

        {{-- ============================================================
             PANEL UTAMA — kondisi ditentukan oleh status cancel booking
             ============================================================ --}}

        @if($activeCancelBooking)
            {{-- -------------------------------------------------------
                 ADA cancel booking aktif (pending / admin_approved)
                 Sembunyikan tombol Setujui/Tolak booking biasa
                 ------------------------------------------------------- --}}
            <div class="bg-orange-500/10 border-2 border-orange-500/30 rounded-xl p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-orange-300 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    @if($activeCancelBooking->status === 'pending')
                        User Mengajukan Cancel Booking
                    @else
                        Cancel Booking Disetujui Admin
                    @endif
                </h3>

                @if($activeCancelBooking->status === 'pending')
                    <p class="text-sm text-orange-200 mb-1">
                        Penyewa mengajukan pembatalan booking pada
                        <span class="font-semibold text-white">
                            {{ $activeCancelBooking->created_at->translatedFormat('d F Y, H:i') }}
                        </span>.
                    </p>
                    <p class="text-sm text-orange-200 mb-4">
                        Silakan proses persetujuan melalui menu <strong class="text-white">Cancel Booking</strong>.
                    </p>
                @else
                    <p class="text-sm text-orange-200 mb-1">
                        Admin telah menyetujui pembatalan pada
                        <span class="font-semibold text-white">
                            {{ $activeCancelBooking->admin_approved_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                        </span>.
                    </p>
                    <p class="text-sm text-orange-200 mb-4">
                        Menunggu <strong class="text-white">Superadmin</strong> memproses pengembalian dana DP sebesar
                        <span class="text-yellow-400 font-bold">
                            Rp {{ number_format($activeCancelBooking->rent->deposit_paid ?? 0, 0, ',', '.') }}
                        </span>.
                    </p>
                @endif

                <!-- Ringkasan rekening refund -->
                <div class="bg-slate-900/60 rounded-lg p-4 mb-4 border border-orange-500/20 text-sm space-y-1">
                    <p class="text-slate-400">Bank:
                        <span class="text-yellow-400 font-bold">{{ $activeCancelBooking->bank_name }}</span>
                    </p>
                    <p class="text-slate-400">No. Rekening:
                        <span class="text-white font-semibold">{{ $activeCancelBooking->account_number }}</span>
                    </p>
                    <p class="text-slate-400">A/N:
                        <span class="text-white font-semibold">{{ $activeCancelBooking->account_holder_name }}</span>
                    </p>
                </div>

                <a href="{{ route('admin.cancel-bookings.show', $activeCancelBooking) }}"
                   class="inline-flex w-full items-center justify-center gap-2
                          px-4 py-3 bg-orange-500 text-white rounded-lg
                          hover:bg-orange-600 text-sm font-semibold transition shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 12h14"/>
                    </svg>
                    Lihat Detail Cancel Booking
                </a>
            </div>

        @elseif($approvedCancelBooking)
            {{-- -------------------------------------------------------
                 Cancel booking sudah selesai (superadmin sudah refund)
                 ------------------------------------------------------- --}}
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6">
                <h3 class="text-lg font-bold text-red-300 mb-2 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Booking Telah Dibatalkan
                </h3>
                <p class="text-sm text-red-200 mb-3">
                    User telah melakukan cancel booking dan refund DP telah diproses oleh Superadmin.
                </p>
                <div class="bg-slate-900/60 rounded-lg p-3 text-xs text-slate-400 space-y-1">
                    <p>Disetujui Admin:
                        <span class="text-white">
                            {{ $approvedCancelBooking->admin_approved_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                        </span>
                    </p>
                    <p>Refund diproses:
                        <span class="text-white">
                            {{ $approvedCancelBooking->processed_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                        </span>
                    </p>
                    @if($approvedCancelBooking->refund_amount)
                    <p>Jumlah Refund:
                        <span class="text-yellow-400 font-bold">
                            Rp {{ number_format($approvedCancelBooking->refund_amount, 0, ',', '.') }}
                        </span>
                    </p>
                    @endif
                </div>
            </div>

        @elseif($booking->status == 'pending')
            {{-- -------------------------------------------------------
                 Booking masih pending biasa, belum ada cancel booking
                 Tampilkan tombol Setujui / Tolak seperti semula
                 ------------------------------------------------------- --}}
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700 p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    Perlu Persetujuan
                </h3>

                <p class="text-sm text-slate-300 mb-6">
                    Booking ini menunggu persetujuan Anda. Silakan verifikasi data dan bukti pembayaran sebelum menyetujui.
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <!-- Form Setujui -->
                    <form id="approve-booking-{{ $booking->id }}"
                          action="{{ route('admin.bookings.approve', $booking) }}"
                          method="POST" style="display:none;">
                        @csrf
                    </form>

                    <button type="button"
                            @click="$store.modal.open({
                                type: 'success',
                                title: 'Setujui Booking?',
                                message: 'Booking dari {{ $booking->user->name }} untuk Kamar {{ $booking->room->room_number }} akan disetujui. User akan mendapat notifikasi.',
                                confirmText: 'Ya, Setujui',
                                showCancel: true,
                                formId: 'approve-booking-{{ $booking->id }}'
                            })"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3
                                   bg-green-600 hover:bg-green-700 text-white rounded-lg
                                   font-semibold transition shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Setujui Booking
                    </button>

                    <!-- Form Tolak -->
                    <form id="reject-booking-{{ $booking->id }}"
                          action="{{ route('admin.bookings.reject', $booking) }}"
                          method="POST" style="display:none;">
                        @csrf
                        <input type="hidden" name="admin_notes" value="Ditolak oleh admin">
                    </form>

                    <button type="button"
                            @click="$store.modal.confirmDelete(
                                'Booking dari {{ $booking->user->name }} akan DITOLAK. Tindakan ini tidak dapat dibatalkan. User akan mendapat notifikasi penolakan.',
                                'reject-booking-{{ $booking->id }}',
                                'Tolak Booking Ini?'
                            )"
                            class="w-full flex items-center justify-center gap-2 px-4 py-3
                                   bg-red-600 hover:bg-red-700 text-white rounded-lg
                                   font-semibold transition shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Tolak Booking
                    </button>
                </div>
            </div>

        @elseif($booking->status == 'active')
            {{-- Booking aktif, tidak ada cancel --}}
            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-6">
                <h3 class="text-lg font-bold text-green-300 mb-2 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Booking Aktif
                </h3>
                <p class="text-sm text-green-200">
                    Booking ini telah disetujui dan penyewa dapat mulai menempati kamar.
                </p>
                @if($booking->approved_at)
                <p class="text-xs text-green-300 mt-2">
                    Disetujui pada: {{ $booking->approved_at->format('d M Y, H:i') }}
                </p>
                @endif
            </div>

        @elseif($booking->status == 'cancelled')
            <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-6">
                <h3 class="text-lg font-bold text-red-300 mb-2 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Booking Dibatalkan
                </h3>
                <p class="text-sm text-red-200">
                    Booking ini telah dibatalkan dan kamar kembali tersedia.
                </p>
            </div>
        @endif

        <!-- Timeline -->
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                Timeline
            </h3>

            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-2 h-2 bg-slate-500 rounded-full mt-2 mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-white">Booking Dibuat</p>
                        <p class="text-xs text-slate-400">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                @if($booking->approved_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-white">Disetujui</p>
                        <p class="text-xs text-slate-400">{{ $booking->approved_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endif

                @if($activeCancelBooking || $approvedCancelBooking)
                @php $cb = $activeCancelBooking ?? $approvedCancelBooking; @endphp
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-2 h-2 bg-orange-500 rounded-full mt-2 mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-orange-300">Pengajuan Cancel Booking</p>
                        <p class="text-xs text-slate-400">{{ $cb->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                @if($cb->admin_approved_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-2 h-2 bg-amber-500 rounded-full mt-2 mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-amber-300">Cancel Disetujui Admin</p>
                        <p class="text-xs text-slate-400">{{ $cb->admin_approved_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endif

                @if($approvedCancelBooking && $approvedCancelBooking->processed_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full mt-2 mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-red-300">Refund Diproses & Selesai</p>
                        <p class="text-xs text-slate-400">{{ $approvedCancelBooking->processed_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endif
                @endif

                @if($booking->start_date <= now() && $booking->status == 'active')
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-white">Mulai Menempati</p>
                        <p class="text-xs text-slate-400">{{ $booking->start_date->format('d M Y') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/>
                    </svg>
                </div>
                Aksi Cepat
            </h3>

            <div class="space-y-2">
                <a href="{{ route('superadmin.users.show', $booking->user->id) }}"
                   class="inline-flex items-center gap-1 text-yellow-400 hover:text-yellow-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 12h14"/>
                    </svg>
                    Lihat Profile Penyewa
                </a><br>
                <a href="{{ route('admin.rooms.show', $booking->room->id) }}"
                   class="inline-flex items-center gap-1 text-yellow-400 hover:text-yellow-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 12h14"/>
                    </svg>
                    Lihat Detail Kamar
                </a><br>
                @if($activeCancelBooking)
                <a href="{{ route('admin.cancel-bookings.show', $activeCancelBooking) }}"
                   class="inline-flex items-center gap-1 text-orange-400 hover:text-orange-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 12h14"/>
                    </svg>
                    Proses Cancel Booking
                </a><br>
                @endif
                <a href="{{ route('admin.bookings.index') }}"
                   class="inline-flex items-center gap-1 text-yellow-400 hover:text-yellow-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 12h14"/>
                    </svg>
                    Kembali ke Daftar Booking
                </a>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') closeImageModal();
});
</script>
@endpush

@endsection