@extends('layouts.user')

@section('title', 'Detail Kamar ' . $room->room_number)

@section('content')

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('user.dashboard') }}" 
       class="inline-flex items-center text-gray-600 hover:text-yellow-600 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Dashboard
    </a>
</div>

<!-- Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Detail Kamar {{ $room->room_number }}</h1>
    <p class="text-gray-600">Informasi lengkap tentang kamar yang Anda tempati</p>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column - Main Info -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Galeri Foto -->
        @if(!empty($room->images) && is_array($room->images) && count($room->images) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="aspect-video bg-gray-100 relative" x-data="imageGallery()">
                <img :src="'{{ asset('storage') }}/' + images[currentIndex]" 
                     alt="Kamar {{ $room->room_number }}" 
                     class="w-full h-full object-cover">
                
                <!-- Navigation -->
                <template x-if="images.length > 1">
                    <div>
                        <button @click="prev" 
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm text-gray-800 p-3 rounded-full hover:bg-white transition-all shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button @click="next" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm text-gray-800 p-3 rounded-full hover:bg-white transition-all shadow-lg">
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
            <div class="p-4 bg-gray-50 grid grid-cols-6 gap-2" x-data="imageGallery()">
                <template x-for="(image, index) in images" :key="index">
                    <div @click="currentIndex = index" 
                         class="aspect-square rounded-lg overflow-hidden cursor-pointer border-2 transition-all hover:scale-105"
                         :class="currentIndex === index ? 'border-yellow-500 ring-2 ring-yellow-500/50' : 'border-gray-300 hover:border-gray-400'">
                        <img :src="'{{ asset('storage') }}/' + image" 
                             class="w-full h-full object-cover">
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-16 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-500 text-lg">Belum ada foto untuk kamar ini</p>
        </div>
        @endif
        
        <!-- Info Detail Kamar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center pb-4 border-b border-gray-200">
                <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Informasi Kamar
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                        Nomor Kamar
                    </label>
                    <p class="font-bold text-xl text-gray-800">{{ $room->room_number }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Lantai
                    </label>
                    <p class="font-semibold text-gray-800">{{ $room->floor }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
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
                        <svg class="w-4 h-4 mr-1.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Kapasitas
                    </label>
                    <p class="font-semibold text-gray-800">{{ $room->capacity }} orang</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                        </svg>
                        Ukuran
                    </label>
                    <p class="font-semibold text-gray-800">{{ $room->size }} m²</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Harga Sewa
                    </label>
                    <p class="font-bold text-xl text-yellow-600">{{ $room->formatted_price }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Jenis Sewa
                    </label>
                    <p class="font-semibold text-gray-800">
                        <span class="px-3 py-1.5 bg-indigo-100 text-indigo-700 border border-indigo-300 rounded-lg text-sm font-bold">
                            {{ $room->jenis_sewa_label }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Jendela
                    </label>
                    <p class="font-semibold text-gray-800">{{ $room->has_window ? '✓ Ada' : '✗ Tidak Ada' }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600 flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Status
                    </label>
                    <p><span class="px-3 py-1.5 text-sm font-bold rounded-lg
                        @if($room->status == 'available') bg-green-100 text-green-700 border border-green-300
                        @elseif($room->status == 'occupied') bg-blue-100 text-blue-700 border border-blue-300
                        @else bg-orange-100 text-orange-700 border border-orange-300
                        @endif">{{ $room->status_label }}</span></p>
                </div>
            </div>
            
            @if($room->description)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <label class="text-sm text-gray-600 font-semibold mb-2 block">Deskripsi</label>
                <p class="text-gray-700 leading-relaxed">{{ $room->description }}</p>
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center pb-4 border-b border-gray-200">
                <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                Fasilitas Kamar
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($facilities as $facility)
                <div class="flex items-center gap-2 px-4 py-3 bg-yellow-50 text-gray-700 rounded-lg border border-yellow-200 hover:border-yellow-400 transition-all duration-200">
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center pb-4 border-b border-gray-200">
                <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                Informasi Sewa
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Tanggal Masuk</label>
                    <p class="font-bold text-gray-800 mt-1 text-lg">{{ $activeRent->start_date->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activeRent->start_date->diffForHumans() }}</p>
                </div>
                
                @if($activeRent->end_date)
                <div>
                    <label class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Tanggal Keluar</label>
                    <p class="font-semibold text-gray-800 mt-1">{{ $activeRent->end_date->format('d M Y') }}</p>
                </div>
                @endif

                <div class="pt-4 border-t border-gray-200">
                    <label class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Lama Sewa</label>
                    <p class="font-bold text-gray-800 mt-1 text-lg">
                        {{ $activeRent->start_date->diffInMonths(now()) }} Bulan
                    </p>
                </div>
                
                <div class="pt-4 border-t border-gray-200">
                    <label class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Harga Sewa Bulanan</label>
                    <p class="font-bold text-2xl text-yellow-600 mt-1">
                        Rp {{ number_format($activeRent->monthly_rent, 0, ',', '.') }}
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 font-semibold">Status Sewa</span>
                                        
                        @if($activeRent->status === 'active')
                            <span class="px-3 py-1.5 bg-green-100 text-green-700 text-sm font-bold rounded-lg border border-green-300">
                                Aktif
                            </span>
                        @elseif($activeRent->status === 'checkout_requested')
                            <span class="px-3 py-1.5 bg-yellow-100 text-yellow-700 text-sm font-bold rounded-lg border border-yellow-300">
                                Menunggu Checkout
                            </span>
                        @endif
                    </div>
                </div>

                @if($activeRent->status === 'checkout_requested')
                    <div class="pt-4">
                        <div class="flex items-center justify-center px-4 py-3
                                    bg-yellow-100 text-yellow-800
                                    rounded-xl font-semibold border border-yellow-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3"/>
                            </svg>
                            Menunggu persetujuan admin
                        </div>
                    </div>
                @endif

                {{-- Tombol Checkout --}}
                @if($activeRent->status === 'active')
                    <div class="pt-6 border-t border-gray-200">
                        <form action="{{ route('user.rents.checkout.request', $activeRent->id) }}" 
                              method="POST"
                              onsubmit="return confirm('Yakin ingin mengajukan checkout? Permintaan ini harus disetujui admin.')">
                            @csrf
                            @method('PUT')
                        
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-3
                                       bg-red-600 hover:bg-red-700 text-white font-semibold
                                       rounded-xl transition-all shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                                </svg>
                                Ajukan Checkout
                            </button>
                        </form>
                    
                        <p class="text-xs text-gray-500 mt-3 text-center">
                            Checkout harus disetujui oleh admin
                        </p>
                    </div>
                @endif

            </div>
        </div>
        @endif
        
        <!-- Statistik Kamar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center pb-4 border-b border-gray-200">
                <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                Informasi Tambahan
            </h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">Total Dilihat</span>
                    </div>
                    <span class="font-bold text-lg text-gray-800">{{ $room->view_count }}</span>
                </div>
                
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">Kamar Ditambahkan</span>
                    </div>
                    <p class="font-semibold text-gray-800">{{ $room->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $room->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        @if($room->last_maintenance)
        <!-- Info Maintenance -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-orange-100 rounded-lg mr-3">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Maintenance Terakhir</h3>
            </div>
            
            <div>
                <p class="font-bold text-gray-800 text-lg">{{ $room->last_maintenance->format('d M Y') }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $room->last_maintenance->diffForHumans() }}</p>
            </div>
        </div>
        @endif
        
    </div>
    
</div>

@endsection