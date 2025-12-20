@extends('layouts.user')

@section('title', 'Status Booking')

@section('content')

<div class="max-w-4xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Status Booking Anda</h1>
        <p class="text-gray-600">Pantau status booking kamar Anda di sini</p>
    </div>
    
    <!-- Status Timeline -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Status Saat Ini</h3>
            
            <!-- Status Badge -->
            <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full text-lg font-semibold
                @if($rent->status == 'pending') bg-yellow-200 text-yellow-900 ring-1 ring-yellow-400
                @elseif($rent->status == 'active') bg-green-200 text-green-900 ring-1 ring-green-400
                @elseif($rent->status == 'cancelled') bg-red-200 text-red-900 ring-1 ring-red-400
                @else bg-gray-200 text-gray-800
                @endif">
                
                @if($rent->status == 'pending')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Menunggu Konfirmasi</span>
                @elseif($rent->status == 'active')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Booking Aktif</span>
                @elseif($rent->status == 'cancelled')
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Dibatalkan</span>
                @else
                    <span>{{ ucfirst($rent->status) }}</span>
                @endif
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="relative">
            <div class="absolute left-4 top-0 bottom-0 w-0.5 
                {{ $rent->status == 'pending' ? 'bg-yellow-300' : '' }}
                {{ $rent->status == 'active' ? 'bg-green-300' : '' }}
                {{ $rent->status == 'cancelled' ? 'bg-red-300' : '' }}">
            </div>
            
            <div class="space-y-6">
                <!-- Step 1: Booking Submitted -->
                <div class="relative flex items-start pl-12">
                    <div class="absolute left-0 flex items-center justify-center w-8 h-8 rounded-full 
                        {{ in_array($rent->status, ['pending', 'active', 'cancelled', 'expired']) ? 'bg-green-500' : 'bg-gray-300' }}">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Booking Diterima</h4>
                        <p class="text-sm text-gray-600">{{ $rent->created_at->format('d M Y, H:i') }} WIB</p>
                        <p class="text-xs text-gray-500 mt-1">Booking Anda telah kami terima dan sedang diproses</p>
                    </div>
                </div>
                
                <!-- Step 2: Admin Review -->
                <div class="relative flex items-start pl-12">
                    <div class="absolute left-0 flex items-center justify-center w-8 h-8 rounded-full 
                        {{ $rent->status == 'pending' ? 'bg-yellow-500 ring-4 ring-yellow-200 animate-pulse' : '' }}
                        {{ in_array($rent->status, ['active', 'expired']) ? 'bg-green-500 ring-4 ring-green-200' : '' }}
                        {{ $rent->status == 'cancelled' ? 'bg-red-500 ring-4 ring-red-200' : '' }}">
                        @if($rent->status == 'pending')
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif(in_array($rent->status, ['active', 'expired']))
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @elseif($rent->status == 'cancelled')
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">
                            @if($rent->status == 'pending')
                                Sedang Ditinjau Admin
                            @elseif($rent->status == 'active')
                                Disetujui Admin
                            @elseif($rent->status == 'cancelled')
                                Dibatalkan
                            @else
                                Review Admin
                            @endif
                        </h4>
                        <p class="text-sm text-gray-600">
                            @if($rent->status == 'pending')
                                Estimasi: 1x24 jam
                            @elseif($rent->status == 'active')
                                {{ $rent->updated_at->format('d M Y, H:i') }} WIB
                            @elseif($rent->status == 'cancelled')
                                {{ $rent->updated_at->format('d M Y, H:i') }} WIB
                            @else
                                Menunggu
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($rent->status == 'pending')
                                Admin sedang memverifikasi pembayaran dan ketersediaan kamar
                            @elseif($rent->status == 'active')
                                Pembayaran telah diverifikasi dan booking Anda disetujui
                            @elseif($rent->status == 'cancelled')
                                Booking dibatalkan oleh admin
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Step 3: Payment Complete -->
                <div class="relative flex items-start pl-12">
                    <div class="absolute left-0 flex items-center justify-center w-8 h-8 rounded-full 
                        {{ $rent->status == 'active' ? 'bg-green-500' : 'bg-gray-300' }}">
                        @if($rent->status == 'active')
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Pembayaran Lunas</h4>
                        <p class="text-sm text-gray-600">
                            @if($rent->status == 'active')
                                Selesai
                            @else
                                Menunggu persetujuan
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Pembayaran sisa dan mulai menempati kamar</p>
                    </div>
                </div>
                
                <!-- Step 4: Move In -->
                <div class="relative flex items-start pl-12">
                    <div class="absolute left-0 flex items-center justify-center w-8 h-8 rounded-full 
                        {{ $rent->status == 'active' && $rent->start_date <= now() ? 'bg-green-500' : 'bg-gray-300' }}">
                        @if($rent->status == 'active' && $rent->start_date <= now())
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Mulai Menempati</h4>
                        <p class="text-sm text-gray-600">{{ $rent->start_date->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Anda dapat mulai menempati kamar pada tanggal ini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detail Booking -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        
        <!-- Info Kamar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Kamar</h3>
            
            @if($rent->room->images && count($rent->room->images) > 0)
            <div class="mb-4">
                <img src="{{ asset('storage/' . $rent->room->images[0]) }}" 
                     alt="Kamar {{ $rent->room->room_number }}"
                     class="w-full h-48 object-cover rounded-lg">
            </div>
            @endif
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nomor Kamar</span>
                    <span class="font-semibold text-gray-800">{{ $rent->room->room_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Lantai</span>
                    <span class="font-semibold text-gray-800">{{ $rent->room->floor }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tipe</span>
                    <span class="font-semibold text-gray-800">{{ ucfirst($rent->room->type) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ukuran</span>
                    <span class="font-semibold text-gray-800">{{ $rent->room->size }} m²</span>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('user.rooms.show', $rent->room->id) }}" 
                   class="text-purple-600 hover:text-purple-700 text-sm font-medium flex items-center">
                    Lihat Detail Kamar
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
        
        <!-- Info Pembayaran -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Pembayaran</h3>
            
            <div class="space-y-3 mb-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Harga Sewa/Bulan</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($rent->monthly_rent, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">DP Dibayar</span>
                    <span class="font-semibold text-green-600">Rp {{ number_format($rent->deposit_paid, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-orange-200 bg-orange-50 px-3 py-2 rounded-lg">
                    <span class="text-gray-600">Sisa Pembayaran</span>
                    <span class="font-semibold text-orange-600">Rp {{ number_format($rent->monthly_rent - $rent->deposit_paid, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <!-- Bukti Transfer -->
            @if($rent->notes && str_contains($rent->notes, 'Bukti DP:'))
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-2">Bukti Transfer DP:</p>
                @php
                    $imagePath = str_replace('Bukti DP: ', '', $rent->notes);
                @endphp
                <img src="{{ asset('storage/' . $imagePath) }}" 
                     alt="Bukti Transfer"
                     class="w-full rounded-lg border border-gray-200 cursor-pointer hover:opacity-90"
                     onclick="window.open(this.src, '_blank')">
                <p class="text-xs text-gray-500 mt-1">Klik untuk memperbesar</p>
            </div>
            @endif
            
            <!-- Payment Status Message -->
            @if($rent->status == 'pending')
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    Sisa pembayaran akan diinformasikan setelah booking disetujui
                </p>
            </div>
            @elseif($rent->status == 'active')
            <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">
                    ✓ Pembayaran telah diverifikasi
                </p>
            </div>
            @endif
        </div>
        
    </div>
    
    <!-- Info Tanggal -->
    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl border border-purple-200 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Jadwal</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600 mb-1">Tanggal Booking</p>
                <p class="font-semibold text-gray-800">{{ $rent->created_at->format('d M Y') }}</p>
                <p class="text-xs text-gray-500">{{ $rent->created_at->diffForHumans() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Mulai Sewa</p>
                <p class="font-semibold text-gray-800">{{ $rent->start_date->format('d M Y') }}</p>
                <p class="text-xs text-gray-500">
                    @if($rent->start_date->isFuture())
                        {{ $rent->start_date->diffForHumans() }}
                    @else
                        Sudah dimulai
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Status Sewa</p>
                <p class="font-semibold text-gray-800">
                    @if($rent->end_date)
                        Berakhir {{ $rent->end_date->format('d M Y') }}
                    @else
                        Aktif
                    @endif
                </p>
                <p class="text-xs text-gray-500">
                    @if($rent->end_date)
                        {{ $rent->end_date->diffForHumans() }}
                    @else
                        Tidak ada batas waktu
                    @endif
                </p>
            </div>
        </div>
    </div>
    
    <!-- Status-specific Messages -->
    @if($rent->status == 'pending')
    <div class="bg-yellow-100 border-l-4 border-yellow-500 p-6 rounded-lg mb-6 ring-1 ring-yellow-200">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="font-bold text-yellow-800 mb-2">Menunggu Konfirmasi Admin</h4>
                <p class="text-sm text-yellow-700 mb-2">
                    Booking Anda sedang ditinjau oleh admin. Tim kami akan menghubungi Anda melalui telepon atau WhatsApp dalam waktu 1x24 jam.
                </p>
                <p class="text-sm text-yellow-700">
                    Pastikan nomor telepon Anda aktif: <strong>{{ Auth::user()->phone ?? 'Belum diisi' }}</strong>
                </p>
            </div>
        </div>
    </div>
    @elseif($rent->status == 'active')
    <div class="bg-green-100 border-l-4 border-green-500 p-6 rounded-lg mb-6 ring-1 ring-green-200">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="font-bold text-green-800 mb-2">Booking Aktif</h4>
                <p class="text-sm text-green-700 mb-2">
                    Selamat! Booking Anda telah disetujui. Anda dapat mulai menempati kamar pada tanggal <strong>{{ $rent->start_date->format('d M Y') }}</strong>.
                </p>
                <p class="text-sm text-green-700">
                    Silakan hubungi admin untuk pengambilan kunci dan informasi lebih lanjut.
                </p>
            </div>
        </div>
    </div>
    @elseif($rent->status == 'cancelled')
    <div class="bg-red-100 border-l-4 border-red-500 p-6 rounded-lg mb-6 ring-1 ring-red-200">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="font-bold text-red-800 mb-2">Booking Dibatalkan</h4>
                <p class="text-sm text-red-700 mb-2">
                    Mohon maaf, booking Anda telah dibatalkan. Silakan hubungi admin untuk informasi lebih lanjut.
                </p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Contact Admin -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Hubungi Admin</h3>
        
        @if($rent->room->kosInfo)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="tel:{{ $rent->room->kosInfo->phone }}" 
               class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Telepon</p>
                    <p class="font-semibold text-gray-800">{{ $rent->room->kosInfo->phone }}</p>
                </div>
            </a>
            
            @if($rent->room->kosInfo->whatsapp)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rent->room->kosInfo->whatsapp) }}?text=Halo, saya ingin menanyakan status booking kamar {{ $rent->room->room_number }}" 
               target="_blank"
               class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">WhatsApp</p>
                    <p class="font-semibold text-gray-800">{{ $rent->room->kosInfo->whatsapp }}</p>
                </div>
            </a>
            @endif
            
            @if($rent->room->kosInfo->email)
            <a href="mailto:{{ $rent->room->kosInfo->email }}" 
               class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-semibold text-gray-800">{{ $rent->room->kosInfo->email }}</p>
                </div>
            </a>
            @endif
        </div>
        @endif
    </div>
    
    <!-- Actions -->
    <div class="mt-8 flex justify-center">
        <a href="{{ route('user.dashboard') }}" 
           class="inline-flex items-center space-x-2 px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>
    
</div>

@endsection

@push('styles')
<style>
/* Animation for pending status */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Smooth transitions */
.transition-colors {
    transition: background-color 0.3s ease, color 0.3s ease;
}
</style>
@endpush