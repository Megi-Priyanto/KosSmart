@extends('layouts.admin')

@section('title', 'Informasi Kos')
@section('page-title', 'Informasi Kos')
@section('page-description', 'Kelola informasi dan detail kos Anda')

@section('content')

<!-- Header Actions -->
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">{{ $kosInfo->name }}</h2>
        <p class="text-sm text-gray-600 mt-1">Data informasi kos yang ditampilkan ke calon penyewa</p>
    </div>
    <a href="{{ route('admin.kos.edit') }}" 
       class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
        </svg>
        <span>Edit Informasi</span>
    </a>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Details -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Informasi Dasar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Informasi Dasar
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Nama Kos</label>
                    <p class="text-gray-800 mt-1">{{ $kosInfo->name }}</p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-600">Alamat Lengkap</label>
                    <p class="text-gray-800 mt-1">{{ $kosInfo->full_address }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Telepon</label>
                        <p class="text-gray-800 mt-1">{{ $kosInfo->phone }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">WhatsApp</label>
                        <p class="text-gray-800 mt-1">{{ $kosInfo->whatsapp ?? '-' }}</p>
                    </div>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <p class="text-gray-800 mt-1">{{ $kosInfo->email ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-600">Deskripsi</label>
                    <p class="text-gray-700 mt-1">{{ $kosInfo->description ?? '-' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Fasilitas Umum -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Fasilitas Umum
            </h3>
            
            @if($kosInfo->general_facilities && count($kosInfo->general_facilities) > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($kosInfo->general_facilities as $facility)
                <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-sm">
                    {{ $facility }}
                </span>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">Belum ada fasilitas umum yang ditambahkan</p>
            @endif
        </div>
        
        <!-- Peraturan Kos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                Peraturan Kos
            </h3>
            
            @if($kosInfo->rules && count($kosInfo->rules) > 0)
            <ul class="space-y-2">
                @foreach($kosInfo->rules as $rule)
                <li class="flex items-start space-x-2 text-gray-700">
                    <svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span>{{ $rule }}</span>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-gray-500 text-sm">Belum ada peraturan yang ditambahkan</p>
            @endif
        </div>
        
        <!-- Galeri Foto -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Galeri Foto Kos
            </h3>
            
            @if($kosInfo->images && count($kosInfo->images) > 0)
            <div class="grid grid-cols-3 gap-4">
                @foreach($kosInfo->images as $image)
                <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                    <img src="{{ asset('storage/' . $image) }}" 
                         alt="Foto Kos" 
                         class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 bg-gray-50 rounded-lg">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-500 text-sm">Belum ada foto yang diupload</p>
            </div>
            @endif
        </div>
        
    </div>
    
    <!-- Right Column: Statistics & Settings -->
    <div class="space-y-6">
        
        <!-- Status Kos -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Status Kos</h3>
            
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm text-gray-600">Status Operasional</span>
                @if($kosInfo->is_active)
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Aktif</span>
                @else
                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Nonaktif</span>
                @endif
            </div>
            
            <div class="space-y-3 pt-3 border-t border-gray-200">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Check-in</span>
                    <span class="text-sm font-medium text-gray-800">{{ $kosInfo->checkin_time->format('H:i') }} WIB</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Check-out</span>
                    <span class="text-sm font-medium text-gray-800">{{ $kosInfo->checkout_time->format('H:i') }} WIB</span>
                </div>
            </div>
        </div>
        
        <!-- Statistik Kamar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Kamar</h3>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-600">Total Kamar</span>
                        <span class="text-2xl font-bold text-gray-800">{{ $kosInfo->total_rooms }}</span>
                    </div>
                </div>
                
                <div class="pt-3 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Tersedia</span>
                        <span class="text-lg font-semibold text-green-600">{{ $kosInfo->available_rooms }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Terisi</span>
                        <span class="text-lg font-semibold text-blue-600">{{ $kosInfo->occupied_rooms }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Maintenance</span>
                        <span class="text-lg font-semibold text-orange-600">
                            {{ $kosInfo->total_rooms - $kosInfo->available_rooms - $kosInfo->occupied_rooms }}
                        </span>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="pt-3 border-t border-gray-200">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>Tingkat Hunian</span>
                        <span>{{ $kosInfo->total_rooms > 0 ? round(($kosInfo->occupied_rooms / $kosInfo->total_rooms) * 100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" 
                             style="width: {{ $kosInfo->total_rooms > 0 ? ($kosInfo->occupied_rooms / $kosInfo->total_rooms) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('admin.rooms.index') }}" 
               class="block w-full text-center mt-4 px-4 py-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition-colors text-sm font-medium">
                Kelola Kamar →
            </a>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl border border-purple-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h3>
            
            <div class="space-y-2">
                <a href="{{ route('admin.rooms.create') }}" 
                   class="flex items-center space-x-3 p-3 bg-white rounded-lg hover:shadow-md transition-all">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Tambah Kamar Baru</span>
                </a>
                
                <a href="{{ route('admin.rooms.index') }}" 
                   class="flex items-center space-x-3 p-3 bg-white rounded-lg hover:shadow-md transition-all">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Lihat Semua Kamar</span>
                </a>
            </div>
        </div>
        
    </div>
    
</div>

@endsection