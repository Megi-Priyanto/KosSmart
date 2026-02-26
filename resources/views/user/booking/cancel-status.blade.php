@extends('layouts.user')

@section('title', 'Status Pembatalan')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-100">Status Pembatalan Booking</h1>
            <p class="text-sm text-gray-600 mt-1">Pantau proses pengembalian dana DP Anda</p>
        </div>
        <a href="{{ route('user.dashboard') }}" 
           class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg">
            Kembali
        </a>
    </div>

    <!-- Status Card -->
    <div class="bg-[#1e293b] rounded-xl shadow-lg border border-[#334155] p-6">
        
        <!-- Status Badge -->
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-100 mb-3">Status Saat Ini</h3>
            <span class="inline-flex items-center space-x-2 px-4 py-2 rounded-full text-lg font-semibold
                @if($cancelBooking->status == 'pending') bg-yellow-200 text-yellow-900 ring-1 ring-yellow-400
                @elseif($cancelBooking->status == 'approved') bg-green-200 text-green-900 ring-1 ring-green-400
                @elseif($cancelBooking->status == 'rejected') bg-red-900/50 text-red-900 ring-1 ring-red-400
                @endif">
                
                @if($cancelBooking->status == 'pending')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Menunggu Proses Admin</span>
                @elseif($cancelBooking->status == 'approved')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Dana Dikembalikan</span>
                @elseif($cancelBooking->status == 'rejected')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Ditolak</span>
                @endif
            </span>
        </div>

        <!-- Detail Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            
            <div class="space-y-4">
                <h4 class="font-bold text-gray-100 pb-2 border-b">Informasi Booking</h4>
                <div>
                    <p class="text-sm text-gray-600">Kamar</p>
                    <p class="font-semibold text-gray-100">{{ $cancelBooking->rent->room->room_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">DP yang Dibayar</p>
                    <p class="text-xl font-bold text-yellow-600">Rp {{ number_format($cancelBooking->rent->deposit_paid, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                    <p class="font-semibold text-gray-100">{{ $cancelBooking->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <h4 class="font-bold text-gray-100 pb-2 border-b">Rekening Pengembalian</h4>
                <div>
                    <p class="text-sm text-gray-600">Bank</p>
                    <p class="font-semibold text-gray-100">{{ $cancelBooking->bank_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Nomor Rekening</p>
                    <p class="font-semibold text-gray-100">{{ $cancelBooking->account_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Atas Nama</p>
                    <p class="font-semibold text-gray-100">{{ $cancelBooking->account_holder_name }}</p>
                </div>
            </div>

        </div>

        <!-- Refund Info (if approved) -->
        @if($cancelBooking->status === 'approved')
        <div class="mt-6 p-6 bg-green-900/30 border-l-4 border-green-500 rounded-lg">
            <h4 class="font-bold text-green-300 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Dana Berhasil Dikembalikan
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-green-400">Metode Pengembalian</p>
                    <p class="font-semibold text-green-900">
                        {{ $cancelBooking->refund_method === 'manual_transfer' ? 'Transfer Bank' : 'E-Wallet' }}
                        - {{ strtoupper($cancelBooking->refund_sub_method) }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-green-400">Jumlah Dikembalikan</p>
                    <p class="text-xl font-bold text-green-900">Rp {{ number_format($cancelBooking->refund_amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-green-400">Tanggal Diproses</p>
                    <p class="font-semibold text-green-900">{{ $cancelBooking->processed_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-green-400">Rekening Tujuan</p>
                    <p class="font-semibold text-green-900">{{ $cancelBooking->refund_account_number }}</p>
                </div>
            </div>

            @if($cancelBooking->refund_proof)
            <div class="mt-4">
                <p class="text-sm font-semibold text-green-300 mb-2">Bukti Transfer dari Admin:</p>
                <img src="{{ asset('storage/' . $cancelBooking->refund_proof) }}" 
                     alt="Bukti Transfer"
                     class="max-w-md rounded-lg border-2 border-green-300 cursor-pointer hover:opacity-90"
                     onclick="window.open(this.src, '_blank')">
                <p class="text-xs text-green-600 mt-1">Klik untuk memperbesar</p>
            </div>
            @endif

            @if($cancelBooking->admin_notes)
            <div class="mt-4 p-3 bg-green-900/40 rounded-lg">
                <p class="text-sm text-green-300">
                    <strong>Catatan Admin:</strong> {{ $cancelBooking->admin_notes }}
                </p>
            </div>
            @endif
        </div>
        @endif

        <!-- Pending Info -->
        @if($cancelBooking->status === 'pending')
        <div class="mt-6 p-6 bg-yellow-900/20 border-l-4 border-yellow-500 rounded-lg">
            <h4 class="font-bold text-yellow-300 mb-2 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Menunggu Proses Admin
            </h4>
            <p class="text-sm text-yellow-400">
                Permintaan pembatalan Anda sedang ditinjau oleh admin. Dana DP akan dikembalikan ke rekening yang Anda cantumkan dalam 1-3 hari kerja setelah disetujui.
            </p>
        </div>
        @endif

        <!-- Rejected Info -->
        @if($cancelBooking->status === 'rejected')
        <div class="mt-6 p-6 bg-red-900/30 border-l-4 border-red-500 rounded-lg">
            <h4 class="font-bold text-red-300 mb-2 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Pembatalan Ditolak
            </h4>
            @if($cancelBooking->admin_notes)
            <p class="text-sm text-red-400 mb-3">
                <strong>Alasan:</strong> {{ $cancelBooking->admin_notes }}
            </p>
            @endif
            <p class="text-sm text-red-400">
                Mohon hubungi admin untuk informasi lebih lanjut.
            </p>
        </div>
        @endif

    </div>

</div>

@endsection