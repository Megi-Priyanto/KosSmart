@extends('layouts.user')

@section('title', 'Status & Riwayat')

@section('content')
<div class="space-y-6">

    {{-- JUDUL --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">
            Status & Riwayat Anda
        </h1>
        <p class="text-gray-500 mt-1">
            Pantau booking, tagihan, dan proses check-out kos Anda
        </p>
    </div>

    {{-- GRID STATUS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- STATUS BOOKING --}}
        <a href="{{ route('user.status.booking') }}"
           class="group bg-white rounded-2xl p-6 border border-gray-200
                  shadow-sm hover:shadow-md transition">

            <div class="flex items-center justify-between mb-6">
                <div class="bg-yellow-100/70 p-3 rounded-xl">
                    <i class="fa-solid fa-bed text-yellow-600 text-xl"></i>
                </div>
                <i class="fa-solid fa-arrow-right text-gray-300 group-hover:text-gray-500 transition"></i>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-1">
                Status Booking
            </h3>
            <p class="text-sm text-gray-500">
                Lihat status kamar yang Anda tempati
            </p>
        </a>

        {{-- STATUS TAGIHAN --}}
        <a href="{{ route('user.status.billing') }}"
           class="group bg-white rounded-2xl p-6 border border-gray-200
                  shadow-sm hover:shadow-md transition">

            <div class="flex items-center justify-between mb-6">
                <div class="bg-green-100/70 p-3 rounded-xl">
                    <i class="fa-solid fa-file-invoice-dollar text-green-600 text-xl"></i>
                </div>
                <i class="fa-solid fa-arrow-right text-gray-300 group-hover:text-gray-500 transition"></i>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-1">
                Status Tagihan
            </h3>
            <p class="text-sm text-gray-500">
                Pantau pembayaran dan riwayat tagihan
            </p>
        </a>

        {{-- STATUS CHECKOUT --}}
        <a href="{{ route('user.status.checkout') }}"
           class="group bg-white rounded-2xl p-6 border border-gray-200
                  shadow-sm hover:shadow-md transition">

            <div class="flex items-center justify-between mb-6">
                <div class="bg-blue-100/70 p-3 rounded-xl">
                    <i class="fa-solid fa-right-from-bracket text-blue-600 text-xl"></i>
                </div>
                <i class="fa-solid fa-arrow-right text-gray-300 group-hover:text-gray-500 transition"></i>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-1">
                Status Check-out
            </h3>
            <p class="text-sm text-gray-500">
                Lihat proses dan riwayat check-out
            </p>
        </a>

    </div>
</div>
@endsection
