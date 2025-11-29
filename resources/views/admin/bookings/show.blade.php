@extends('layouts.admin')

@section('title', 'Detail Booking')
@section('page-title', 'Detail Booking')
@section('page-description', 'Informasi lengkap booking dan approval')

@section('content')

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.bookings.index') }}" 
       class="inline-flex items-center text-purple-600 hover:text-purple-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar Booking
    </a>
</div>

<!-- Status Badge -->
<div class="mb-6">
    <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold
        @if($booking->status == 'pending') bg-yellow-100 text-yellow-700
        @elseif($booking->status == 'active') bg-green-100 text-green-700
        @elseif($booking->status == 'cancelled') bg-red-100 text-red-700
        @else bg-gray-100 text-gray-700
        @endif">
        @if($booking->status == 'pending')
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Menunggu Persetujuan
        @elseif($booking->status == 'active')
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Booking Aktif
        @elseif($booking->status == 'cancelled')
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Dibatalkan
        @endif
    </span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Detail Info -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Info Penyewa -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Informasi Penyewa
            </h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama Lengkap</span>
                    <span class="font-semibold text-gray-800">{{ $booking->user->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Email</span>
                    <span class="font-semibold text-gray-800">{{ $booking->user->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Nomor Telepon</span>
                    <span class="font-semibold text-gray-800">{{ $booking->user->phone ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Terdaftar Sejak</span>
                    <span class="font-semibold text-gray-800">{{ $booking->user->created_at->format('d M Y') }}</span>
                </div>
            </div>
            
            <!-- Quick Contact -->
            <div class="mt-4 pt-4 border-t border-gray-200 flex gap-2">
                @if($booking->user->phone)
                <a href="tel:{{ $booking->user->phone }}" 
                   class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                    📞 Telepon
                </a>
                @endif
                @if($booking->user->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->user->phone) }}" 
                   target="_blank"
                   class="flex-1 text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                    💬 WhatsApp
                </a>
                @endif
            </div>
        </div>
        
        <!-- Info Kamar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Informasi Kamar
            </h3>
            
            @if(is_array($booking->room->images) && count($booking->room->images) > 0)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $booking->room->images[0]) }}"
                         alt="Kamar {{ $booking->room->room_number }}"
                         class="w-full h-48 object-cover rounded-lg">
                </div>
            @endif
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nomor Kamar</span>
                    <span class="font-semibold text-gray-800">{{ $booking->room->room_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Lantai</span>
                    <span class="font-semibold text-gray-800">{{ $booking->room->floor }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tipe</span>
                    <span class="font-semibold text-gray-800">{{ ucfirst($booking->room->type) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ukuran</span>
                    <span class="font-semibold text-gray-800">{{ $booking->room->size }} m²</span>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.rooms.show', $booking->room->id) }}" 
                   class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                    Lihat Detail Kamar →
                </a>
            </div>
        </div>
        
        <!-- Info Pembayaran -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Informasi Pembayaran
            </h3>
            
            <div class="space-y-3 mb-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Harga Sewa/Bulan</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($booking->monthly_rent, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">DP Dibayar (50%)</span>
                    <span class="font-semibold text-green-600">Rp {{ number_format($booking->deposit_paid, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-gray-200">
                    <span class="text-gray-600">Sisa Pembayaran</span>
                    <span class="font-semibold text-orange-600">Rp {{ number_format($booking->monthly_rent - $booking->deposit_paid, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <!-- Bukti Transfer -->
            @if($booking->notes && str_contains($booking->notes, 'Bukti DP:'))
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-sm font-medium text-gray-700 mb-2">Bukti Transfer DP:</p>
                @php
                    $imagePath = str_replace('Bukti DP: ', '', $booking->notes);
                @endphp
                <img src="{{ asset('storage/' . $imagePath) }}" 
                     alt="Bukti Transfer"
                     class="w-full rounded-lg border border-gray-200 cursor-pointer hover:opacity-90"
                     onclick="window.open(this.src, '_blank')">
                <p class="text-xs text-gray-500 mt-1">Klik untuk memperbesar</p>
            </div>
            @endif
        </div>
        
        <!-- Info Tanggal -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Jadwal
            </h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Booking</span>
                    <span class="font-semibold text-gray-800">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Mulai Sewa</span>
                    <span class="font-semibold text-gray-800">{{ $booking->start_date->format('d M Y') }}</span>
                </div>
                @if($booking->approved_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Disetujui Pada</span>
                    <span class="font-semibold text-gray-800">{{ $booking->approved_at->format('d M Y, H:i') }}</span>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Admin Notes (if exists) -->
        @if($booking->admin_notes)
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                Catatan Admin
            </h3>
            <p class="text-gray-700">{{ $booking->admin_notes }}</p>
            @if($booking->approved_by)
            <p class="text-sm text-gray-500 mt-2">
                Oleh: {{ \App\Models\User::find($booking->approved_by)->name ?? 'Admin' }}
            </p>
            @endif
        </div>
        @endif
        
    </div>
    
    <!-- Right Column: Actions -->
    <div class="space-y-6">
        
        <!-- Approval Actions (Only for pending) -->
        @if($booking->status == 'pending')
        <div class="bg-white rounded-xl shadow-lg border-2 border-yellow-300 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">⚠️ Perlu Persetujuan</h3>
            
            <p class="text-sm text-gray-600 mb-4">
                Booking ini menunggu persetujuan Anda. Silakan verifikasi data dan bukti pembayaran sebelum menyetujui.
            </p>
            
            <!-- Approve Form -->
            <form action="{{ route('admin.bookings.approve', $booking) }}" method="POST" class="mb-3">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="admin_notes" rows="3" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                              placeholder="Contoh: Pembayaran telah diverifikasi, silakan hubungi kami untuk pengambilan kunci."></textarea>
                </div>
                
                <button type="submit" 
                        onclick="return confirm('Yakin ingin menyetujui booking ini?')"
                        class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-semibold flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Setujui Booking
                </button>
            </form>
            
            <!-- Reject Form -->
            <div x-data="{ showReject: false }">
                <button @click="showReject = !showReject" 
                        class="w-full bg-red-100 text-red-700 py-2 rounded-lg hover:bg-red-200 font-medium text-sm">
                    Tolak Booking
                </button>
                
                <div x-show="showReject" x-transition class="mt-3">
                    <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="admin_notes" rows="3" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                      placeholder="Jelaskan alasan penolakan..."
                                      required></textarea>
                        </div>
                        
                        <button type="submit" 
                                onclick="return confirm('Yakin ingin menolak booking ini? Tindakan ini tidak dapat dibatalkan.')"
                                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 font-semibold">
                            Konfirmasi Penolakan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Status Info -->
        @if($booking->status == 'active')
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <h3 class="text-lg font-bold text-green-800 mb-2">✓ Booking Aktif</h3>
            <p class="text-sm text-green-700">
                Booking ini telah disetujui dan penyewa dapat mulai menempati kamar.
            </p>
            @if($booking->approved_at)
            <p class="text-xs text-green-600 mt-2">
                Disetujui pada: {{ $booking->approved_at->format('d M Y, H:i') }}
            </p>
            @endif
        </div>
        @endif
        
        @if($booking->status == 'cancelled')
        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
            <h3 class="text-lg font-bold text-red-800 mb-2">✗ Booking Dibatalkan</h3>
            <p class="text-sm text-red-700">
                Booking ini telah dibatalkan dan kamar kembali tersedia.
            </p>
        </div>
        @endif
        
        <!-- Timeline -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Timeline</h3>
            
            <div class="space-y-4">
                <!-- Created -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-2 h-2 bg-purple-600 rounded-full mt-2 mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Booking Dibuat</p>
                        <p class="text-xs text-gray-500">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                
                @if($booking->approved_at)
                <!-- Approved -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-2 h-2 bg-green-600 rounded-full mt-2 mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Disetujui</p>
                        <p class="text-xs text-gray-500">{{ $booking->approved_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @endif
                
                @if($booking->start_date <= now() && $booking->status == 'active')
                <!-- Move In -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Mulai Menempati</p>
                        <p class="text-xs text-gray-500">{{ $booking->start_date->format('d M Y') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl border border-purple-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-3">Aksi Cepat</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.users.show', $booking->user->id) }}" 
                   class="block text-sm text-purple-600 hover:text-purple-700">
                    → Lihat Profil Penyewa
                </a>
                <a href="{{ route('admin.rooms.show', $booking->room->id) }}" 
                   class="block text-sm text-purple-600 hover:text-purple-700">
                    → Lihat Detail Kamar
                </a>
                <a href="{{ route('admin.bookings.index') }}" 
                   class="block text-sm text-purple-600 hover:text-purple-700">
                    → Kembali ke Daftar Booking
                </a>
            </div>
        </div>
        
    </div>
    
</div>

@endsection