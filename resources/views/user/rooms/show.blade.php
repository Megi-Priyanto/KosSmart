@extends('layouts.user')

@section('title', 'Detail Kamar ' . $room->room_number)

@section('content')

<!-- Breadcrumb -->
<div class="mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('user.dashboard') }}" class="text-gray-600 hover:text-yellow-600 text-sm">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('user.rooms.index') }}" class="ml-1 text-sm text-gray-600 hover:text-yellow-600">
                        Cari Kamar
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm text-yellow-500 font-medium">Kamar {{ $room->room_number }}</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Detail Kamar -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Image Gallery -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @php
                $images = $room->images;
            @endphp

            @if(!empty($images))
                <div x-data="imageGallery()" class="relative">
                    <!-- Main Image -->
                    <div class="aspect-video bg-gray-200 relative group">
                        <img :src="'{{ asset('storage') }}/' + images[currentIndex]" 
                             alt="Kamar {{ $room->room_number }}" 
                             class="w-full h-full object-cover">
                        
                        <!-- Navigation Arrows -->
                        <template x-if="images.length > 1">
                            <div>
                                <button @click="prev" 
                                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-3 rounded-full hover:bg-opacity-75 transition-all opacity-0 group-hover:opacity-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <button @click="next" 
                                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-3 rounded-full hover:bg-opacity-75 transition-all opacity-0 group-hover:opacity-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        
                        <!-- Image Counter -->
                        <div class="absolute bottom-4 right-4 bg-black bg-opacity-60 text-white px-3 py-1 rounded-full text-sm font-medium">
                            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                                ✓ Tersedia
                            </span>
                        </div>
                    </div>
                    
                    <!-- Thumbnails -->
                    <div class="p-4 bg-gray-50">
                        <div class="grid grid-cols-5 gap-2">
                            <template x-for="(image, index) in images" :key="index">
                                <div @click="currentIndex = index" 
                                     class="aspect-square rounded-lg overflow-hidden cursor-pointer border-3 transition-all hover:scale-105"
                                     :class="currentIndex === index ? 'border-purple-600 ring-2 ring-purple-300' : 'border-gray-300'">
                                    <img :src="'{{ asset('storage') }}/' + image" 
                                         class="w-full h-full object-cover">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                
                <script>
                function imageGallery() {
                    return {
                        images: @json($images),
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
                <!-- No Image Placeholder -->
                <div class="aspect-video bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-24 h-24 mx-auto text-yellow-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-600 font-medium">Foto belum tersedia</p>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Room Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Kamar {{ $room->room_number }}</h1>
                    <p class="text-gray-600">{{ $room->floor }} • {{ $room->size }} m²</p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold text-yellow-600">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">{{ $room->jenis_sewa === 'tahun' ? 'per tahun' : 'per bulan' }}</p>
                </div>
            </div>
            
            <!-- Quick Info -->
            <div class="grid grid-cols-3 gap-4 py-4 border-t border-b border-gray-200 mb-6">
                <div class="text-center">
                    <div class="bg-yellow-100 w-12 h-12 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">Kapasitas</p>
                    <p class="font-semibold text-gray-800">{{ $room->capacity }} Orang</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-blue-100 w-12 h-12 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">Tipe</p>
                    <p class="font-semibold text-gray-800">{{ ucfirst($room->type) }}</p>
                </div>

                <div class="text-center">
                    <div class="bg-indigo-100 w-12 h-12 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">Jenis Sewa</p>
                    <p class="font-semibold text-gray-800">{{ $room->jenis_sewa_label }}</p>
                </div>
                
                <div class="text-center">
                    <div class="bg-green-100 w-12 h-12 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">Jendela</p>
                    <p class="font-semibold text-gray-800">{{ $room->has_window ? 'Ada' : 'Tidak' }}</p>
                </div>
            </div>
            
            <!-- Description -->
            @if($room->description)
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Deskripsi</h3>
                <p class="text-gray-700 leading-relaxed">{{ $room->description }}</p>
            </div>
            @endif
            
            <!-- Facilities -->
            @php
                $facilities = is_array($room->facilities) 
                    ? $room->facilities 
                    : json_decode($room->facilities, true);
            @endphp

            @if(!empty($facilities))
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-3">Fasilitas Kamar</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($facilities as $facility)
                    <div class="flex items-center space-x-2 text-gray-700">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $facility }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        @if($kosInfo && $kosInfo->is_active)
        <div class="bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl shadow-lg p-6 mb-8 text-white">
            <h3 class="text-lg font-bold text-white-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-white-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Tentang Kos
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-2xl font-bold text-white-800 mb-1">{{ $kosInfo->name }}</p>
                    <p class="text-white-600">{{ $kosInfo->full_address }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-3">
                    <div>
                        <p class="text-sm text-white-600">Telepon</p>
                        <p class="font-semibold text-white-800">{{ $kosInfo->phone }}</p>
                    </div>

                    @if($kosInfo->whatsapp)
                    <div>
                        <p class="text-sm text-white-600">WhatsApp</p>
                        <p class="font-semibold text-white-800">{{ $kosInfo->whatsapp }}</p>
                    </div>
                    @endif
                </div>

                @php
                    $generalFacilities = $kosInfo->general_facilities;

                    if (is_string($generalFacilities)) {
                        $decoded = json_decode($generalFacilities, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $generalFacilities = $decoded;
                        } else {
                            $generalFacilities = array_filter(array_map('trim', explode(',', $generalFacilities)));
                        }
                    }
                @endphp

                @if(!empty($generalFacilities))
                    <div class="pt-3 border-t border-yellow-200">
                        <p class="text-sm font-medium text-gray-700 mb-2">Fasilitas Umum:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($generalFacilities as $facility)
                                <span class="px-3 py-1 bg-white text-yellow-700 rounded-full text-sm">{{ $facility }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @php
                    $rules = $kosInfo->rules;

                    if (is_string($rules)) {
                        $decoded = json_decode($rules, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $rules = $decoded;
                        } else {
                            $rules = array_filter(array_map('trim', explode(',', $rules)));
                        }
                    }
                @endphp

                @if(!empty($rules))
                    <div class="pt-3 border-t border-yellow-200">
                        <p class="text-sm font-medium text-gray-700 mb-2">Peraturan Kos:</p>
                        <ul class="space-y-1">
                            @foreach(array_slice($rules, 0, 10) as $rule)
                                <li class="flex items-start text-sm text-gray-700">
                                    <svg class="w-4 h-4 text-orange-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $rule }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Rekomendasi Kamar Lain -->
        @if(isset($relatedRooms) && $relatedRooms->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Kamar Lain yang Tersedia</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($relatedRooms as $relatedRoom)
                <a href="{{ route('user.rooms.show', $relatedRoom->id) }}" 
                   class="block group">
                    <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-lg transition-all">
                        @php
                            $images = $room->images;
                        @endphp

                        @if(!empty($relatedImages))
                        <div class="aspect-video bg-gray-200">
                            <img src="{{ asset('storage/' . $relatedImages[0]) }}" 
                                 alt="Kamar {{ $relatedRoom->room_number }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        </div>
                        @else
                        <div class="aspect-video bg-gradient-to-br from-yellow-400 to-orange-500"></div>
                        @endif
                        
                        <div class="p-3">
                            <p class="font-semibold text-gray-800 group-hover:text-yellw-600">Kamar {{ $relatedRoom->room_number }}</p>
                            <p class="text-sm text-gray-600">{{ $relatedRoom->floor }}</p>
                            <p class="text-lg font-bold text-yellow-600 mt-2">Rp {{ number_format($relatedRoom->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
        
    </div>
    
    <!-- Right Column: Booking & Contact -->
    <div class="space-y-6">
        
        <!-- Booking Card -->
        <div class="bg-white rounded-xl shadow-lg border-2 border-yellow-200 p-6 sticky top-6">
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Harga Sewa</span>
                    <span class="text-sm text-gray-500">{{ $room->jenis_sewa_label }}</span>
                </div>
                <p class="text-4xl font-bold text-yellow-600">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $room->jenis_sewa === 'tahun' ? 'Dibayar per tahun' : 'Dibayar per bulan' }}</p>
            </div>
            
            <div class="space-y-3 mb-6">
                <a href="{{ route('user.booking.create', $room->id) }}" 
                   class="block w-full text-center bg-yellow-500 text-white py-3 rounded-lg hover:bg-yellow-600 font-semibold transition-colors">
                    Booking Sekarang
                </a>
                
                @if($room->kosInfo && $room->kosInfo->is_active && $room->kosInfo->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $room->kosInfo->whatsapp) }}?text=Halo, saya tertarik dengan Kamar {{ $room->room_number }}" 
                   target="_blank"
                   class="block w-full text-center bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-semibold transition-colors flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    <span>Hubungi via WhatsApp</span>
                </a>
                @endif
            </div>
            
            <!-- Info Tambahan -->
            <div class="space-y-2 pt-4 border-t border-gray-200">
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Kamar tersedia untuk booking
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Bisa langsung pindah
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Sistem pembayaran fleksibel
                </div>
            </div>
        </div>
        
        <!-- Contact Info -->
        @if($kosInfo && $kosInfo->is_active)
        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow-lg p-6 text-white">
            <h3 class="text-lg font-bold mb-4">Hubungi Kami</h3>
        
            <div class="space-y-3">
            
                <!-- Alamat -->
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <div>
                        <p class="text-white-100 text-sm">Alamat</p>
                        <p class="font-medium">{{ $kosInfo->address }}</p>
                        <p class="text-sm">{{ $kosInfo->city }}, {{ $kosInfo->province }}</p>
                    </div>
                </div>
            
                <!-- Telepon -->
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <div>
                        <p class="text-white-100 text-sm">Telepon</p>
                        <p class="font-medium">{{ $kosInfo->phone ?? 'Tidak tersedia' }}</p>
                    </div>
                </div>
            
                <!-- Email -->
                @if($kosInfo->email)
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="text-white-100 text-sm">Email</p>
                        <p class="font-medium">{{ $kosInfo->email }}</p>
                    </div>
                </div>
                @endif
            
                <!-- Check-in & Check-out -->
                @if($kosInfo)
                    <div class="pt-3 border-t border-yellow-400">
                        <div class="grid grid-cols-1 gap-3">
                        
                            <!-- Check-in -->
                            <div>
                                <p class="text-white-100 text-sm">Check-in</p>
                                <p class="font-medium">
                                    {{ \Carbon\Carbon::parse($kosInfo->checkin_time)->format('H:i') }} WIB
                                </p>
                            </div>
                        
                            <!-- Check-out -->
                            <div>
                                <p class="text-white-100 text-sm">Check-out</p>
                                <p class="font-medium">
                                    {{ \Carbon\Carbon::parse($kosInfo->checkout_time)->format('H:i') }} WIB
                                </p>
                            </div>
                        
                        </div>
                    </div>
                    @endif
            
            </div>
        </div>
        @endif
                
        <!-- Share & Save -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Bagikan Kamar Ini</h3>
            
            <div class="flex space-x-2">
                <!-- WhatsApp Share -->
                <a href="https://wa.me/?text=Lihat kamar ini di KosSmart: {{ urlencode(route('user.rooms.show', $room->id)) }}" 
                   target="_blank"
                   class="flex-1 flex items-center justify-center space-x-2 bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    <span class="text-sm font-medium">Share</span>
                </a>
                
                <!-- Copy Link -->
                <button onclick="copyLink()" 
                        class="flex-1 flex items-center justify-center space-x-2 bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                    </svg>
                    <span class="text-sm font-medium">Copy Link</span>
                </button>
            </div>
            
            <script>
            function copyLink() {
                const url = "{{ route('user.rooms.show', $room->id) }}";
                navigator.clipboard.writeText(url).then(() => {
                    alert('Link berhasil disalin!');
                }).catch(err => {
                    console.error('Gagal menyalin link:', err);
                });
            }
            </script>
        </div>
        
        <!-- Tips -->
        <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Tips Booking
            </h3>
            
            <ul class="space-y-2 text-sm text-gray-700">
                <li class="flex items-start">
                    <span class="text-yellow-600 mr-2">•</span>
                    <span>Pastikan Anda sudah melihat kondisi kamar secara langsung atau via video call</span>
                </li>
                <li class="flex items-start">
                    <span class="text-yellow-600 mr-2">•</span>
                    <span>Tanyakan detail pembayaran dan deposit yang diperlukan</span>
                </li>
                <li class="flex items-start">
                    <span class="text-yellow-600 mr-2">•</span>
                    <span>Baca peraturan kos dengan teliti sebelum booking</span>
                </li>
                <li class="flex items-start">
                    <span class="text-yellow-600 mr-2">•</span>
                    <span>Simpan bukti pembayaran dan perjanjian sewa</span>
                </li>
            </ul>
        </div>
        
    </div>
    
</div>

<!-- Back to List Button (Mobile) -->
<div class="mt-8 lg:hidden">
    <a href="{{ route('user.rooms.index') }}" 
       class="block w-full text-center px-6 py-3 border border-gray-300 font-bold text-white rounded-lg bg-yellow-500 hover:bg-yellow-600 flex items-center justify-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        <span>Lihat Kamar Lain</span>
    </a>
</div>

@endsection

@push('scripts')
<script>
// Smooth scroll untuk anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Auto hide notification after 3 seconds
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert-auto-hide');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 3000);
</script>
@endpush

@push('styles')
<style>
/* Custom scrollbar untuk gallery */
.gallery-scroll::-webkit-scrollbar {
    height: 6px;
}

.gallery-scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.gallery-scroll::-webkit-scrollbar-thumb {
    background: #9333ea;
    border-radius: 10px;
}

.gallery-scroll::-webkit-scrollbar-thumb:hover {
    background: #7e22ce;
}

/* Smooth image loading */
img {
    image-rendering: -webkit-optimize-contrast;
}

/* Sticky positioning enhancement */
@media (min-width: 1024px) {
    .sticky {
        position: -webkit-sticky;
        position: sticky;
    }
}

/* Hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Loading skeleton animation */
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
</style>
@endpush