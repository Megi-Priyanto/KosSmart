@extends('layouts.admin')

@section('title', 'Detail Kamar ' . $room->room_number)
@section('page-title', 'Kamar ' . $room->room_number)
@section('page-description', 'Detail lengkap kamar dan informasi penyewa')

@section('content')

<!-- Action Buttons -->
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.rooms.index') }}" 
       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        <span>Kembali</span>
    </a>
    
    <div class="flex space-x-2">
        <a href="{{ route('admin.rooms.edit', $room) }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            <span>Edit</span>
        </a>
        
        <form action="{{ route('admin.rooms.destroy', $room) }}" 
              method="POST" 
              onsubmit="return confirm('Yakin ingin menghapus kamar {{ $room->room_number }}?')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center space-x-2"
                    {{ $room->currentRent ? 'disabled' : '' }}>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>Hapus</span>
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left: Main Info -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Galeri Foto -->
        @if(!empty($room->images) && is_array($room->images) && count($room->images) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="aspect-video bg-gray-200 relative" x-data="imageGallery()">
                <img :src="'{{ asset('storage') }}/' + images[currentIndex]" 
                     alt="Kamar {{ $room->room_number }}" 
                     class="w-full h-full object-cover">
                
                <!-- Navigation -->
                <template x-if="images.length > 1">
                    <div>
                        <button @click="prev" 
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button @click="next" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        
                        <!-- Counter -->
                        <div class="absolute bottom-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Thumbnails -->
            <div class="p-4 grid grid-cols-6 gap-2" x-data="imageGallery()">
                <template x-for="(image, index) in images" :key="index">
                    <div @click="currentIndex = index" 
                         class="aspect-square rounded-lg overflow-hidden cursor-pointer border-2"
                         :class="currentIndex === index ? 'border-purple-600' : 'border-gray-200'">
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-500">Belum ada foto untuk kamar ini</p>
        </div>
        @endif
        
        <!-- Info Detail -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Kamar</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Nomor Kamar</label>
                    <p class="font-semibold text-gray-800">{{ $room->room_number }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600">Lantai</label>
                    <p class="font-semibold text-gray-800">{{ $room->floor }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600">Tipe</label>
                    <p><span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($room->type == 'putra') bg-blue-100 text-blue-700
                        @elseif($room->type == 'putri') bg-pink-100 text-pink-700
                        @else bg-purple-100 text-purple-700
                        @endif">{{ ucfirst($room->type) }}</span></p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600">Kapasitas</label>
                    <p class="font-semibold text-gray-800">{{ $room->capacity }} orang</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600">Ukuran</label>
                    <p class="font-semibold text-gray-800">{{ $room->size }} m²</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600">Harga/Bulan</label>
                    <p class="font-semibold text-purple-600">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600">Jendela</label>
                    <p class="font-semibold text-gray-800">{{ $room->has_window ? 'Ada' : 'Tidak Ada' }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600">Dilihat</label>
                    <p class="font-semibold text-gray-800">{{ $room->view_count }} kali</p>
                </div>
            </div>
            
            @if($room->description)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <label class="text-sm text-gray-600">Deskripsi</label>
                <p class="text-gray-700 mt-1">{{ $room->description }}</p>
            </div>
            @endif
            
            @if($room->notes)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <label class="text-sm text-gray-600">Catatan Internal</label>
                <p class="text-gray-700 mt-1">{{ $room->notes }}</p>
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
            <h3 class="text-lg font-bold text-gray-800 mb-4">Fasilitas</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($facilities as $facility)
                <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-sm">
                    {{ $facility }}
                </span>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Riwayat Sewa -->
        @if($room->rents->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Sewa (5 Terakhir)</h3>
            <div class="space-y-3">
                @foreach($room->rents as $rent)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $rent->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $rent->start_date->format('d M Y') }} - {{ $rent->end_date ? $rent->end_date->format('d M Y') : 'Sekarang' }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                        @if($rent->status == 'active') bg-green-100 text-green-700
                        @elseif($rent->status == 'expired') bg-gray-100 text-gray-700
                        @else bg-orange-100 text-orange-700
                        @endif">
                        {{ ucfirst($rent->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
    </div>
    
    <!-- Right: Status & Actions -->
    <div class="space-y-6">
        
        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Status Kamar</h3>
            
            <div class="mb-4">
                <span class="px-4 py-2 text-sm font-semibold rounded-full inline-block
                    @if($room->status == 'available') bg-green-100 text-green-700
                    @elseif($room->status == 'occupied') bg-blue-100 text-blue-700
                    @else bg-orange-100 text-orange-700
                    @endif">
                    {{ $room->status_label }}
                </span>
            </div>
            
            <!-- Quick Status Change -->
            <form action="{{ route('admin.rooms.status.update', $room) }}" method="POST" class="space-y-2">
                @csrf
                @method('PUT')
                
                <label class="text-sm font-medium text-gray-700">Ubah Status:</label>
                <select name="status" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        onchange="this.form.submit()">
                    <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Terisi</option>
                    <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </form>
        </div>
        
        <!-- Penyewa Aktif -->
        @if($room->currentRent)
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Penyewa Aktif</h3>
            
            <div class="space-y-3">
                <div>
                    <label class="text-sm text-gray-600">Nama</label>
                    <p class="font-semibold text-gray-800">{{ $room->currentRent->user->name }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600">Email</label>
                    <p class="text-sm text-gray-800">{{ $room->currentRent->user->email }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600">Telepon</label>
                    <p class="text-sm text-gray-800">{{ $room->currentRent->user->phone ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600">Mulai Sewa</label>
                    <p class="text-sm text-gray-800">{{ $room->currentRent->start_date->format('d M Y') }}</p>
                </div>
                
                <div>
                    <label class="text-sm text-gray-600">Harga Sewa</label>
                    <p class="font-semibold text-blue-600">Rp {{ number_format($room->currentRent->monthly_rent, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <a href="{{ route('admin.users.show', $room->currentRent->user_id) }}" 
               class="block w-full mt-4 text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                Lihat Profil Penyewa
            </a>
        </div>
        @endif
        
        <!-- Maintenance Info -->
        @if($room->last_maintenance)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Maintenance</h3>
            
            <div>
                <label class="text-sm text-gray-600">Terakhir Maintenance</label>
                <p class="font-semibold text-gray-800">{{ $room->last_maintenance->format('d M Y') }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $room->last_maintenance->diffForHumans() }}</p>
            </div>
        </div>
        @endif
        
        <!-- Stats -->
        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl border border-purple-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total Dilihat</span>
                    <span class="font-semibold text-gray-800">{{ $room->view_count }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total Penyewa</span>
                    <span class="font-semibold text-gray-800">{{ $room->rents->count() }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Ditambahkan</span>
                    <span class="font-semibold text-gray-800">{{ $room->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        
    </div>
    
</div>

@endsection