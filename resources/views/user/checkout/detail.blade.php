@extends('layouts.user')

@section('title', 'Status Checkout')

@section('content')

<!-- Page Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Status Checkout Anda</h1>
        <p class="text-sm text-gray-600 mt-1">Detail permintaan checkout kamar</p>
    </div>
    <a href="{{ route('user.status.checkout') }}" 
       class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
        Kembali ke Riwayat
    </a>
</div>

<!-- KONDISI BERDASARKAN STATUS -->

@if($rent->status === 'checkout_requested')
    {{-- ========================================
         STATUS: PENDING (Menunggu Approval)
        ======================================== --}}
    
    <!-- Alert Status Pending -->
    <div class="mb-8 bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg shadow-sm">
        <div class="flex items-start">
            <svg class="w-8 h-8 text-yellow-500 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <h3 class="font-bold text-yellow-800 text-lg mb-2">
                    Permintaan Checkout Sedang Diproses
                </h3>
                <p class="text-yellow-700 mb-4">
                    Permintaan checkout Anda untuk kamar
                    <strong>{{ $rent->room->room_number ?? '-' }}</strong>
                    sedang ditinjau oleh admin.
                    Harap menunggu konfirmasi dalam 1x24 jam.
                </p>
                <div class="bg-yellow-100 rounded-lg p-4 mt-4">
                    <p class="text-sm text-yellow-800 font-medium mb-2">Informasi:</p>
                    <ul class="text-sm text-yellow-700 space-y-1 list-disc list-inside">
                        <li>Anda masih dapat menggunakan kamar hingga checkout disetujui</li>
                        <li>Admin akan menghubungi Anda untuk koordinasi lebih lanjut</li>
                        <li>Setelah disetujui, Anda akan diarahkan ke halaman pemilihan kamar baru</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@elseif($rent->status === 'completed')
    {{-- ========================================
         STATUS: BERHASIL (Checkout Approved)
        ======================================== --}}
    
    <!-- Alert Status Berhasil -->
    <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-6 rounded-lg shadow-sm">
        <div class="flex items-start">
            <svg class="w-8 h-8 text-green-500 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <h3 class="font-bold text-green-800 text-lg mb-2">
                    Checkout Berhasil Disetujui!
                </h3>
                <p class="text-green-700 mb-4">
                    Checkout untuk kamar <strong>{{ $rent->room->room_number ?? '-' }}</strong> telah disetujui oleh admin.
                    Terima kasih telah menggunakan layanan kami.
                </p>
                <div class="bg-green-100 rounded-lg p-4 mt-4">
                    <p class="text-sm text-green-800 font-medium mb-2">Detail Checkout:</p>
                    <ul class="text-sm text-green-700 space-y-1">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Disetujui pada: <strong class="ml-1">{{ $rent->end_date->format('d M Y, H:i') }} WIB</strong>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Status kamar: <strong class="ml-1">Sedang dalam perawatan (maintenance)</strong>
                        </li>
                    </ul>
                </div>
                
                <div class="mt-4 p-4 bg-white rounded-lg border border-green-200">
                    <p class="text-sm font-medium text-green-800 mb-2">Apa Selanjutnya?</p>
                    <ul class="text-sm text-green-700 space-y-1 list-disc list-inside">
                        <li>Anda sudah dapat memilih kamar baru di halaman utama</li>
                        <li>Proses booking kamar baru dapat dilakukan segera</li>
                        <li>Silakan hubungi admin jika ada keperluan lebih lanjut</li>
                    </ul>
                </div>

                <div class="mt-4">
                    <a href="{{ route('user.dashboard') }}" 
                       class="inline-block px-5 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg">
                        Cari Kamar Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

@elseif($rent->status === 'checkout_rejected')
    {{-- ========================================
         STATUS: DITOLAK (Checkout Rejected)
        ======================================== --}}
    
    <!-- Alert Status Ditolak -->
    <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-6 rounded-lg shadow-sm">
        <div class="flex items-start">
            <svg class="w-8 h-8 text-red-500 mr-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <h3 class="font-bold text-red-800 text-lg mb-2">
                    ✗ Permintaan Checkout Ditolak
                </h3>
                <p class="text-red-700 mb-4">
                    Maaf, permintaan checkout untuk kamar <strong>{{ $rent->room->room_number ?? '-' }}</strong> tidak dapat disetujui.
                    Silakan hubungi admin untuk informasi lebih lanjut.
                </p>
                <div class="bg-red-100 rounded-lg p-4 mt-4">
                    <p class="text-sm text-red-800 font-medium mb-2">Detail:</p>
                    <p class="text-sm text-red-700">
                        Ditolak pada: <strong>{{ $rent->updated_at->format('d M Y, H:i') }} WIB</strong>
                    </p>
                </div>
                
                <div class="mt-4 p-4 bg-white rounded-lg border border-red-200">
                    <p class="text-sm font-medium text-red-800 mb-2">Informasi:</p>
                    <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                        <li>Anda masih dapat menggunakan kamar seperti biasa</li>
                        <li>Hubungi admin untuk mengetahui alasan penolakan</li>
                        <li>Anda dapat mengajukan checkout kembali setelah menyelesaikan masalah</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Informasi Kamar -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Informasi Kamar
        </h2>
    </div>

    <div class="p-6">
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Nomor Kamar</span>
                <span class="font-bold text-gray-800">
                    {{ $rent->room->room_number ?? '-' }}
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-gray-600">Lokasi / Lantai</span>
                <span class="font-medium text-gray-800">
                    {{ $rent->room->floor ?? '-' }}
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-gray-600">Tanggal Masuk</span>
                <span class="font-medium text-gray-800">
                    {{ \Carbon\Carbon::parse($rent->start_date)->format('d M Y') }}
                </span>
            </div>

            @if($rent->end_date)
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Tanggal Keluar</span>
                <span class="font-medium text-gray-800">
                    {{ $rent->end_date->format('d M Y') }}
                </span>
            </div>
            @endif

            <div class="flex justify-between items-center">
                <span class="text-gray-600">Lama Sewa</span>

                @php
                    $start = \Carbon\Carbon::parse($rent->start_date);
                    $end = $rent->end_date
                        ? \Carbon\Carbon::parse($rent->end_date)
                        : now();

                    // Pastikan tidak minus
                    if ($end->lessThan($start)) {
                        $end = $start;
                    }
                
                    $totalSeconds = $start->diffInSeconds($end);
                
                    $months = intdiv($totalSeconds, 2592000);
                    $totalSeconds %= 2592000;
                
                    $days = intdiv($totalSeconds, 86400);
                    $totalSeconds %= 86400;
                
                    $hours = intdiv($totalSeconds, 3600);
                @endphp

                <span class="font-medium text-gray-800">
                    @if($months > 0)
                        {{ $months }} Bulan
                    @endif
                
                    @if($days > 0)
                        {{ $days }} Hari
                    @endif
                
                    {{ $hours }} Jam
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-gray-600">Status</span>
                @if($rent->status === 'checkout_requested')
                    <span class="px-3 py-1.5 bg-yellow-100 text-yellow-700 text-sm font-bold rounded-lg border border-yellow-300">
                        Menunggu Checkout
                    </span>
                @elseif($rent->status === 'completed')
                    <span class="px-3 py-1.5 bg-green-100 text-green-700 text-sm font-bold rounded-lg border border-green-300">
                        ✓ Checkout Berhasil
                    </span>
                @elseif($rent->status === 'checkout_rejected')
                    <span class="px-3 py-1.5 bg-red-100 text-red-700 text-sm font-bold rounded-lg border border-red-300">
                        ✗ Checkout Ditolak
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Timeline Proses -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path>
            </svg>
            Timeline Proses Checkout
        </h2>
    </div>

    <div class="p-6 space-y-6">

        <!-- Step 1: Request Sent -->
        <div class="flex items-start">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-semibold text-gray-800">Permintaan Checkout Dikirim</p>
                <p class="text-sm text-gray-600">
                    {{ $rent->updated_at->format('d M Y, H:i') }} WIB
                </p>
            </div>
        </div>

        <div class="ml-5 border-l-2 border-gray-300 h-8"></div>

        <!-- Step 2: Admin Review -->
        @if($rent->status === 'checkout_requested')
            <!-- Pending -->
            <div class="flex items-start">
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center animate-pulse">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Menunggu Persetujuan Admin</p>
                    <p class="text-sm text-gray-600">Sedang ditinjau...</p>
                </div>
            </div>

            <div class="ml-5 border-l-2 border-gray-300 h-8"></div>

            <!-- Step 3: Future -->
            <div class="flex items-start opacity-60">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-500">Checkout Disetujui</p>
                    <p class="text-sm text-gray-400">Menunggu</p>
                </div>
            </div>

        @elseif($rent->status === 'completed')
            <!-- Approved -->
            <div class="flex items-start">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">Ditinjau oleh Admin</p>
                    <p class="text-sm text-gray-600">Selesai</p>
                </div>
            </div>

            <div class="ml-5 border-l-2 border-green-500 h-8"></div>

            <!-- Step 3: Completed -->
            <div class="flex items-start">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">✓ Checkout Disetujui</p>
                    <p class="text-sm text-gray-600">
                        {{ $rent->end_date->format('d M Y, H:i') }} WIB
                    </p>
                </div>
            </div>

        @elseif($rent->status === 'checkout_rejected')
            <!-- Rejected -->
            <div class="flex items-start">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="font-semibold text-gray-800">✗ Checkout Ditolak</p>
                    <p class="text-sm text-gray-600">
                        {{ $rent->updated_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
            </div>
        @endif

    </div>
</div>

<!-- Info Box -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <h3 class="font-bold text-blue-900 mb-2">Butuh Bantuan?</h3>
            <p class="text-sm text-blue-800">
                @if($rent->status === 'checkout_requested')
                    Jika Anda memiliki pertanyaan atau ingin membatalkan permintaan checkout,
                    silakan hubungi admin melalui kontak yang tersedia.
                @elseif($rent->status === 'completed')
                    Jika Anda membutuhkan bantuan untuk memilih kamar baru atau memiliki pertanyaan,
                    jangan ragu untuk menghubungi admin.
                @elseif($rent->status === 'checkout_rejected')
                    Silakan hubungi admin untuk mengetahui alasan penolakan dan solusi yang dapat dilakukan.
                @endif
            </p>
        </div>
    </div>
</div>

@endsection