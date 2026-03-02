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
                <div class="bg-[rgba(245,158,11,0.15)]/70 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-slate-600 group-hover:text-slate-400 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
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
                <div class="bg-[rgba(52,211,153,0.15)]/70 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-slate-600 group-hover:text-slate-400 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
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
                <div class="bg-[rgba(96,165,250,0.15)]/70 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-slate-600 group-hover:text-slate-400 group-hover:translate-x-1 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
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
