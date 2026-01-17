@extends('layouts.user')

@section('title', 'Menunggu Persetujuan Checkout')

@section('content')

<!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Status Booking Anda</h1>
            <p class="text-sm text-gray-600 mt-1">Pantau status booking kamar Anda di sini</p>
        </div>
        <a href="{{ route('user.billing.index') }}" 
           class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
            Kembali ke Status
        </a>
    </div>

<!-- Alert Status -->
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

<!-- Informasi Kamar -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Informasi Kamar Saat Ini
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

            <div class="flex justify-between items-center">
                <span class="text-gray-600">Lama Sewa</span>
                <span class="font-medium text-gray-800">
                    {{ \Carbon\Carbon::parse($rent->start_date)->diffInMonths(now()) }} Bulan
                </span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-gray-600">Status</span>
                <span class="px-3 py-1.5 bg-yellow-100 text-yellow-700 text-sm font-bold rounded-lg border border-yellow-300">
                    Menunggu Checkout
                </span>
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
            Proses Checkout
        </h2>
    </div>

    <div class="p-6 space-y-6">

        <!-- Step 1 -->
        <div class="flex items-start">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-semibold text-gray-800">Permintaan Checkout Dikirim</p>
                <p class="text-sm text-gray-600">
                    {{ $rent->updated_at->format('d M Y, H:i') }}
                </p>
            </div>
        </div>

        <div class="ml-5 border-l-2 border-gray-300 h-8"></div>

        <!-- Step 2 -->
        <div class="flex items-start">
            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center animate-pulse">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-semibold text-gray-800">Menunggu Persetujuan Admin</p>
                <p class="text-sm text-gray-600">Sedang ditinjau</p>
            </div>
        </div>

        <div class="ml-5 border-l-2 border-gray-300 h-8"></div>

        <!-- Step 3 -->
        <div class="flex items-start opacity-60">
            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="font-semibold text-gray-500">Checkout Disetujui</p>
                <p class="text-sm text-gray-400">Menunggu</p>
            </div>
        </div>

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
                Jika Anda memiliki pertanyaan atau ingin membatalkan permintaan checkout,
                silakan hubungi admin melalui kontak yang tersedia.
            </p>
        </div>
    </div>
</div>

@endsection
