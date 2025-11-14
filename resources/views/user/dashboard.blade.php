@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')

<!-- Welcome Card -->
<div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-8 text-white mb-8">
    <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
    <p class="text-purple-100">Ini adalah dashboard pribadi Anda di KosSmart</p>
</div>

<!-- Info Cards Row -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <!-- Informasi Kamar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Informasi Kamar
            </h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Nomor Kamar</span>
                    <span class="font-bold text-gray-800">{{ $roomInfo->number ?? '101' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Lokasi</span>
                    <span class="font-medium text-gray-800">{{ $roomInfo->location ?? 'Lantai 1' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Status</span>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                        Aktif
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Tanggal Masuk</span>
                    <span class="font-medium text-gray-800">{{ $roomInfo->check_in ?? '01 Jan 2025' }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tagihan Bulan Ini -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Tagihan Bulan Ini
            </h2>
        </div>
        <div class="p-6">
            <div class="text-center mb-6">
                <p class="text-sm text-gray-600 mb-2">Total Tagihan</p>
                <p class="text-4xl font-bold text-gray-800 mb-2">
                    Rp {{ number_format($currentBill->amount ?? 1500000) }}
                </p>
                @if(($currentBill->status ?? 'unpaid') === 'unpaid')
                    <span class="px-3 py-1 bg-orange-100 text-orange-700 text-sm font-medium rounded-full">
                        Belum Bayar
                    </span>
                @else
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                        Lunas
                    </span>
                @endif
            </div>
            
            <div class="space-y-3 mb-6">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Sewa Kamar</span>
                    <span class="font-medium text-gray-800">Rp {{ number_format($currentBill->rent ?? 1200000) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Listrik</span>
                    <span class="font-medium text-gray-800">Rp {{ number_format($currentBill->electricity ?? 200000) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Air</span>
                    <span class="font-medium text-gray-800">Rp {{ number_format($currentBill->water ?? 100000) }}</span>
                </div>
            </div>
            
            @if(($currentBill->status ?? 'unpaid') === 'unpaid')
            <button class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 px-4 rounded-lg font-semibold hover:shadow-lg transition-all">
                Bayar Sekarang
            </button>
            @endif
        </div>
    </div>
    
</div>

<!-- Riwayat Pembayaran -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Riwayat Pembayaran</h2>
            <a href="{{ route('user.payments') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                Lihat Semua →
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            @forelse($paymentHistory ?? [] as $payment)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-{{ $payment->status === 'paid' ? 'green' : 'orange' }}-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-{{ $payment->status === 'paid' ? 'green' : 'orange' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($payment->status === 'paid')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $payment->month ?? 'Januari 2025' }}</p>
                        <p class="text-sm text-gray-600">Rp {{ number_format($payment->amount ?? 1500000) }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-{{ $payment->status === 'paid' ? 'green' : 'orange' }}-100 text-{{ $payment->status === 'paid' ? 'green' : 'orange' }}-700 text-sm font-medium rounded-full">
                        {{ $payment->status === 'paid' ? 'Lunas' : 'Pending' }}
                    </span>
                    <p class="text-xs text-gray-500 mt-1">{{ $payment->date ?? '10 Jan 2025' }}</p>
                </div>
            </div>
            @empty
            <!-- Sample Data -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Januari 2025</p>
                        <p class="text-sm text-gray-600">Rp 1.500.000</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                        Lunas
                    </span>
                    <p class="text-xs text-gray-500 mt-1">10 Jan 2025</p>
                </div>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Desember 2024</p>
                        <p class="text-sm text-gray-600">Rp 1.500.000</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                        Lunas
                    </span>
                    <p class="text-xs text-gray-500 mt-1">08 Des 2024</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Info & Tips -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="font-bold text-blue-900 mb-2">Informasi Pembayaran</h3>
                <p class="text-sm text-blue-800">
                    Pembayaran dapat dilakukan melalui transfer bank atau tunai. Batas pembayaran setiap tanggal 10.
                </p>
            </div>
        </div>
    </div>
    
    <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <div>
                <h3 class="font-bold text-purple-900 mb-2">Butuh Bantuan?</h3>
                <p class="text-sm text-purple-800">
                    Hubungi admin melalui WhatsApp atau email jika ada pertanyaan atau keluhan.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection