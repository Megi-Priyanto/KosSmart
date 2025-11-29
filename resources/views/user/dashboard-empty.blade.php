@extends('layouts.user')

@section('title', 'Pilih Kamar')

@section('content')

<!-- Notifikasi Booking Pending -->
@if(isset($pendingRent))
<div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="flex-1">
            <h3 class="font-bold text-yellow-800 mb-2">⏳ Booking Anda Sedang Diproses</h3>
            <p class="text-sm text-yellow-700 mb-3">
                Booking kamar <strong>{{ $pendingRent->room->room_number }}</strong> sedang ditinjau oleh admin. 
                Anda akan dihubungi dalam 1x24 jam.
            </p>
            <div class="flex gap-3">
                <a href="{{ route('user.booking.status') }}" 
                   class="inline-flex items-center text-sm font-medium text-yellow-700 hover:text-yellow-900">
                    Lihat Status Booking
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-12 text-white text-center mb-12">
    <svg class="w-20 h-20 mx-auto mb-6 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
    </svg>
    <h1 class="text-4xl font-bold mb-4">Selamat Datang di KosSmart! 👋</h1>
    <p class="text-xl text-purple-100 mb-2">Anda belum menyewa kamar</p>
    <p class="text-purple-200">Pilih kamar favorit Anda dari daftar di bawah ini</p>
</div>

<!-- Info: Redirect ke halaman pilih kamar -->
<div class="text-center">
    <a href="{{ route('user.rooms.index') }}" class="inline-block bg-white text-purple-600 px-8 py-4 rounded-xl font-bold text-lg hover:shadow-lg transition-all">
        🏠 Lihat Kamar Tersedia
    </a>
</div>

<!-- Info Box -->
<div class="mt-12 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <h3 class="font-bold text-blue-900 mb-2">Cara Booking Kamar</h3>
            <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                <li>Pilih kamar yang Anda inginkan</li>
                <li>Isi form booking dengan lengkap</li>
                <li>Upload bukti transfer DP (50% dari harga sewa)</li>
                <li>Tunggu konfirmasi dari admin (maksimal 1x24 jam)</li>
                <li>Setelah disetujui, Anda dapat langsung menempati kamar</li>
            </ol>
        </div>
    </div>
</div>

@endsection