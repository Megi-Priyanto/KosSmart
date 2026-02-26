@extends('layouts.user')

@section('title', 'Detail Kamar ' . $room->room_number)

@section('content')

<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-100">Detail Kamar {{ $room->room_number }}</h1>
        <p class="text-sm text-gray-600 mt-1">Informasi lengkap tentang kamar yang Anda tempati</p>
    </div>
    <a href="{{ route('user.dashboard') }}" 
       class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
        Kembali ke Dashboard
    </a>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column - Main Info -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Galeri Foto -->
        @if(!empty($room->images) && is_array($room->images) && count($room->images) > 0)
        <div class="bg-[#1e293b] rounded-xl shadow-none border border-[#334155] overflow-hidden" x-data="imageGallery()">

            <div class="aspect-video bg-[#0f172a] relative">
                <img :src="'{{ asset('storage') }}/' + images[currentIndex]" 
                     alt="Kamar {{ $room->room_number }}" 
                     class="w-full h-full object-cover">
                
                <!-- Navigation -->
                <template x-if="images.length > 1">
                    <div>
                        <button @click="prev" 
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-[#1e293b]/90 backdrop-blur-sm text-gray-100 p-3 rounded-full hover:bg-[#1e293b] transition-all shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button @click="next" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-[#1e293b]/90 backdrop-blur-sm text-gray-100 p-3 rounded-full hover:bg-[#1e293b] transition-all shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        <!-- Counter -->
                        <div class="absolute bottom-4 right-4 bg-gray-900/80 backdrop-blur-sm text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-lg">
                            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Thumbnails -->
            <div class="p-4 bg-[#0f172a] grid grid-cols-6 gap-2">
                <template x-for="(image, index) in images" :key="index">
                    <div @click="currentIndex = index">
                        <img :src="'{{ asset('storage') }}/' + image">
                    </div>
                </template>
            </div>
        </div>
        
        <script>
        function imageGallery() {
            return {
                images: @json($room->images),
                currentIndex: 0,
                next() {
                    this.currentIndex = (this.currentIndex + 1) % this.images.length;
                },
                prev() {
                    this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                }
            }
        }
        </script>
        @else
        <div class="bg-[#1e293b] rounded-xl shadow-none border border-[#334155] p-16 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-500 text-lg">Belum ada foto untuk kamar ini</p>
        </div>
        @endif
        
        <!-- Info Detail Kamar -->
        <div class="bg-[#1e293b] rounded-xl shadow-none border border-[#334155] p-6">
            <h3 class="text-xl font-bold text-gray-100 mb-6 flex items-center pb-4 border-b border-[#334155]">
                <div class="p-2 bg-yellow-900/30 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Informasi Kamar
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        Nomor Kamar
                    </label>
                    <p class="font-bold text-xl text-gray-100">{{ $room->room_number }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        Lantai
                    </label>
                    <p class="font-semibold text-gray-100">{{ $room->floor }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        Tipe
                    </label>
                    <p><span class="px-3 py-1.5 text-sm font-bold rounded-lg
                        @if($room->type == 'putra') bg-blue-100 text-blue-700 border border-blue-300
                        @elseif($room->type == 'putri') bg-pink-100 text-pink-700 border border-pink-300
                        @else bg-purple-100 text-purple-700 border border-purple-300
                        @endif">{{ ucfirst($room->type) }}</span></p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        Kapasitas
                    </label>
                    <p class="font-semibold text-gray-100">{{ $room->capacity }} orang</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        Ukuran
                    </label>
                    <p class="font-semibold text-gray-100">{{ $room->size }} m²</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        Harga Sewa
                    </label>
                    <p class="font-bold text-xl text-yellow-600">{{ $room->formatted_price }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        Jenis Sewa
                    </label>
                    <p class="font-semibold text-gray-100">
                        <span class="px-3 py-1.5 bg-indigo-100 text-indigo-700 border border-indigo-300 rounded-lg text-sm font-bold">
                            {{ $room->jenis_sewa_label }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        Jendela
                    </label>
                    <p class="font-semibold text-gray-100">{{ $room->has_window ? '✓ Ada' : '✗ Tidak Ada' }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        Status
                    </label>
                    <p><span class="px-3 py-1.5 text-sm font-bold rounded-lg
                        @if($room->status == 'available') bg-green-900/40 text-green-400 border border-green-300
                        @elseif($room->status == 'occupied') bg-blue-100 text-blue-700 border border-blue-300
                        @else bg-orange-900/30 text-orange-400 border border-orange-300
                        @endif">{{ $room->status_label }}</span></p>
                </div>
            </div>
            
            @if($room->description)
            <div class="mt-6 pt-6 border-t border-[#334155]">
                <label class="text-sm text-gray-600 font-semibold mb-2 block">Deskripsi</label>
                <p class="text-gray-200 leading-relaxed">{{ $room->description }}</p>
            </div>
            @endif
        </div>
        
        <!-- Fasilitas -->
        @php 
            $facilities = is_array($room->facilities) 
                ? $room->facilities 
                : json_decode($room->facilities ?? '[]', true); 
        @endphp
            
        @if(!empty($facilities))
        <div class="bg-[#1e293b] rounded-xl shadow-none border border-[#334155] p-6">
            <h3 class="text-xl font-bold text-gray-100 mb-6 flex items-center pb-4 border-b border-[#334155]">
                <div class="p-2 bg-yellow-900/30 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                Fasilitas Kamar
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($facilities as $facility)
                <div class="flex items-center gap-2 px-4 py-3 bg-yellow-900/20 text-gray-200 rounded-lg border border-yellow-200 hover:border-yellow-400 transition-all duration-200">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ $facility }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
    </div>
    
    <!-- Right Column - Info Sewa & Stats -->
    <div class="space-y-6">
        
        @if($activeRent && in_array($activeRent->status, ['active', 'checkout_requested']))
        <!-- Info Sewa Aktif -->
        <div class="bg-[#1e293b] rounded-xl shadow-none border border-[#334155] p-6">
            <h3 class="text-xl font-bold text-gray-100 mb-6 flex items-center pb-4 border-b border-[#334155]">
                <div class="p-2 bg-yellow-900/30 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                Informasi Sewa
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Tanggal Masuk</label>
                    <p class="font-bold text-gray-100 mt-1 text-lg">{{ $activeRent->start_date->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activeRent->start_date->diffForHumans() }}</p>
                </div>
                
                @if($activeRent->end_date)
                <div>
                    <label class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Tanggal Keluar</label>
                    <p class="font-semibold text-gray-100 mt-1">{{ $activeRent->end_date->format('d M Y') }}</p>
                </div>
                @endif

                <div class="pt-4 border-t border-[#334155]">
                    <label class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Lama Sewa</label>
                    <p class="font-bold text-gray-100 mt-1 text-lg">
                        {{ $activeRent->duration_accurate }}
                    </p>
                </div>
                
                <div class="pt-4 border-t border-[#334155]">
                    <label class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Harga Sewa Bulanan</label>
                    <p class="font-bold text-2xl text-yellow-600 mt-1">
                        Rp {{ number_format($activeRent->monthly_rent, 0, ',', '.') }}
                    </p>
                </div>

                <div class="pt-4 border-t border-[#334155]">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 font-semibold">Status Sewa</span>
                                        
                        @if($activeRent->status === 'active')
                            <span class="px-3 py-1.5 bg-green-900/40 text-green-400 text-sm font-bold rounded-lg border border-green-300">
                                Aktif
                            </span>
                        @elseif($activeRent->status === 'checkout_requested')
                            <span class="px-3 py-1.5 bg-yellow-900/30 text-yellow-400 text-sm font-bold rounded-lg border border-yellow-300">
                                Menunggu Checkout
                            </span>
                        @endif
                    </div>
                </div>

                @if($activeRent->status === 'checkout_requested')
                    <div class="pt-4">
                        <div class="flex items-center justify-center px-4 py-3
                                    bg-yellow-900/30 text-yellow-300
                                    rounded-xl font-semibold border border-yellow-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3"/>
                            </svg>
                            Menunggu persetujuan admin
                        </div>
                    </div>
                @endif

                @php
                    $hasUnpaidBill = $activeRent->billings
                        ->where('status', '!=', 'paid')
                        ->count() > 0;
                @endphp

                @if($hasUnpaidBill)
                    <div class="pt-4 border-t border-[#334155]">
                        <div class="flex items-center gap-2 px-3 py-2
                                    bg-red-900/40 text-red-300
                                    rounded-lg text-sm font-medium
                                    border border-red-300">
                    
                            <svg class="w-5 h-5 flex-shrink-0 text-red-400"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2.2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856
                                         c1.54 0 2.502-1.667 1.732-3
                                         L13.732 4c-.77-1.333-2.694-1.333-3.464 0
                                         L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        
                            <span>
                                Anda harus melunasi seluruh tagihan sebelum checkout
                            </span>
                        </div>
                    </div>
                @endif
                
                {{-- Tombol Checkout --}}
                @if(!$hasUnpaidBill && $activeRent->status === 'active')
                    <div class="pt-6 border-t border-[#334155]">
                        <form id="checkout-request-form" 
                              action="{{ route('user.checkout.request', $activeRent) }}"
                              method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                        
                        <button type="button"
                                onclick="handleCheckout()"
                                class="inline-flex items-center gap-2 w-full px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-semibold transition justify-center shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Ajukan Checkout
                        </button>
                    
                        <p class="text-xs text-gray-500 mt-3 text-center">
                            Checkout hanya bisa dilakukan setelah semua tagihan lunas
                        </p>
                    </div>
                    
                    <script>
                    function handleCheckout() {
                        // Cek apakah Alpine.js store tersedia
                        if (window.Alpine && window.Alpine.store('modal')) {
                            // Gunakan Alpine.js modal
                            window.Alpine.store('modal').open({
                                type: 'warning',
                                title: 'Ajukan Checkout?',
                                message: 'Anda akan mengajukan permintaan checkout dari Kamar {{ $activeRent->room->room_number }}. Admin akan meninjau permintaan Anda. Pastikan Anda sudah koordinasi dengan admin terkait jadwal checkout.',
                                confirmText: 'Ya, Ajukan',
                                showCancel: true,
                                formId: 'checkout-request-form'
                            });
                        } else {
                            // Fallback ke native confirm jika Alpine.js tidak tersedia
                            if (confirm('Anda akan mengajukan permintaan checkout dari Kamar {{ $activeRent->room->room_number }}. Admin akan meninjau permintaan Anda. Pastikan Anda sudah koordinasi dengan admin terkait jadwal checkout.\n\nLanjutkan?')) {
                                document.getElementById('checkout-request-form').submit();
                            }
                        }
                    }
                    </script>
                @endif

            </div>
        </div>
        @endif
        
        <!-- Statistik Kamar -->
        <div class="bg-[#1e293b] rounded-xl shadow-none border border-[#334155] p-6">
            <h3 class="text-xl font-bold text-gray-100 mb-6 flex items-center pb-4 border-b border-[#334155]">
                <div class="p-2 bg-yellow-900/30 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                Informasi Tambahan
            </h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-[#0f172a] rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">Total Dilihat</span>
                    </div>
                    <span class="font-bold text-lg text-gray-100">{{ $room->view_count }}</span>
                </div>
                
                <div class="pt-4 border-t border-[#334155]">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">Kamar Ditambahkan</span>
                    </div>
                    <p class="font-semibold text-gray-100">{{ $room->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $room->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        @if($room->last_maintenance)
        <!-- Info Maintenance -->
        <div class="bg-[#1e293b] rounded-xl shadow-none border border-[#334155] p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-orange-900/30 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-100">Maintenance Terakhir</h3>
            </div>
            
            <div>
                <p class="font-bold text-gray-100 text-lg">{{ $room->last_maintenance->format('d M Y') }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $room->last_maintenance->diffForHumans() }}</p>
            </div>
        </div>
        @endif
        
    </div>
    
</div>

@endsection