@extends('layouts.user')

@section('title', 'Booking Kamar ' . $room->room_number)

@section('content')

<div class="max-w-5xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('user.rooms.show', $room) }}" 
           class="inline-flex items-center text-purple-600 hover:text-purple-700 mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Detail Kamar
        </a>
        
        <h1 class="text-3xl font-bold text-gray-800 mb-2">🏠 Booking Kamar {{ $room->room_number }}</h1>
        <p class="text-gray-600">Lengkapi formulir di bawah ini untuk melakukan booking</p>
    </div>

    <form action="{{ route('user.booking.store', $room) }}" 
          method="POST" 
          enctype="multipart/form-data"
          x-data="bookingForm()">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Informasi Kamar -->
                <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-2">Kamar {{ $room->room_number }}</h3>
                            <p class="text-purple-100 mb-4">{{ $room->floor }} • {{ $room->size }} m²</p>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-purple-200 text-sm">Harga Sewa/Bulan</p>
                                    <p class="text-2xl font-bold">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-200 text-sm">DP (50%)</p>
                                    <p class="text-2xl font-bold">Rp {{ number_format($depositAmount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        @php
                            $images = is_string($room->images)
                                ? json_decode($room->images, true)
                                : ($room->images ?? []);
                        @endphp

                        @if(count($images) > 0)
                        <div class="ml-4">
                            <img src="{{ asset('storage/' . $room->images[0]) }}" 
                                 alt="Kamar {{ $room->room_number }}"
                                 class="w-24 h-24 rounded-lg object-cover border-2 border-white">
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Form Data Penyewa -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Data Penyewa
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" 
                                   value="{{ Auth::user()->name }}" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50 cursor-not-allowed"
                                   readonly>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" 
                                   value="{{ Auth::user()->email }}" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50 cursor-not-allowed"
                                   readonly>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="text" 
                                   value="{{ Auth::user()->phone ?? '-' }}" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50 cursor-not-allowed"
                                   readonly>
                        </div>
                        
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong>Info:</strong> Data di atas diambil dari profil Anda. 
                                <a href="{{ route('user.profile') }}" class="text-blue-600 underline hover:text-blue-700">
                                    Edit profil
                                </a> jika ada yang perlu diubah.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Tanggal Mulai Sewa -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Tanggal Mulai Sewa
                    </h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="start_date" 
                               value="{{ old('start_date', now()->addDays(3)->format('Y-m-d')) }}"
                               min="{{ now()->format('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               required>
                        @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-2">
                            📅 Anda dapat mulai menempati kamar pada tanggal yang dipilih setelah pembayaran disetujui
                        </p>
                    </div>
                </div>
                
                <!-- Upload Bukti Transfer DP -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Pembayaran DP
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Informasi Rekening -->
                        <div class="p-4 bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-lg">
                            <p class="font-semibold text-gray-800 mb-3">Transfer ke rekening:</p>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Bank:</span>
                                    <span class="font-semibold text-gray-800">BCA</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">No. Rekening:</span>
                                    <span class="font-semibold text-gray-800 flex items-center">
                                        <span id="accountNumber">1234567890</span>
                                        <button type="button" 
                                                onclick="copyAccountNumber()"
                                                class="ml-2 text-purple-600 hover:text-purple-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                            </svg>
                                        </button>
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Atas Nama:</span>
                                    <span class="font-semibold text-gray-800">KosSmart Residence</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-purple-200">
                                    <span class="text-gray-600">Jumlah DP:</span>
                                    <span class="text-xl font-bold text-purple-600">Rp {{ number_format($depositAmount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upload Bukti -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Bukti Transfer <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                   name="deposit_proof" 
                                   accept="image/*"
                                   @change="previewImage"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   required>
                            @error('deposit_proof')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG (Max. 2MB)</p>
                            
                            <!-- Preview -->
                            <div x-show="imagePreview" class="mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                                <img :src="imagePreview" 
                                     class="max-w-md rounded-lg border-2 border-gray-200">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Syarat & Ketentuan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Syarat & Ketentuan
                    </h3>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-700">Pembayaran DP sebesar 50% dari harga sewa wajib dilakukan</p>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-700">Sisa pembayaran dilakukan setelah booking disetujui</p>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-700">Admin akan menghubungi Anda dalam 1x24 jam setelah booking</p>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-700">DP tidak dapat dikembalikan jika membatalkan booking</p>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-700">Wajib mematuhi peraturan kos yang berlaku</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                        <input type="checkbox" 
                               name="agreement" 
                               value="1"
                               id="agreement"
                               class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500 mt-0.5"
                               required>
                        <label for="agreement" class="ml-3 text-sm text-gray-700">
                            Saya telah membaca dan menyetujui syarat & ketentuan di atas <span class="text-red-500">*</span>
                        </label>
                    </div>
                    @error('agreement')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
            </div>
            
            <!-- Right Column: Summary -->
            <div class="space-y-6">
                
                <!-- Ringkasan Booking -->
                <div class="bg-white rounded-xl shadow-lg border-2 border-purple-200 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Booking</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Kamar</span>
                            <span class="font-semibold text-gray-800">{{ $room->room_number }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Lantai</span>
                            <span class="font-semibold text-gray-800">{{ $room->floor }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tipe</span>
                            <span class="font-semibold text-gray-800">{{ ucfirst($room->type) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Ukuran</span>
                            <span class="font-semibold text-gray-800">{{ $room->size }} m²</span>
                        </div>
                    </div>
                    
                    <div class="space-y-3 pt-4 border-t border-gray-200 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga Sewa/Bulan</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">DP (50%)</span>
                            <span class="font-semibold text-orange-600">Rp {{ number_format($depositAmount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-3 border-t border-gray-200">
                            <span class="text-gray-600">Sisa Pembayaran</span>
                            <span class="font-semibold text-gray-800">Rp {{ number_format($room->price - $depositAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg mb-6">
                        <p class="text-xs text-yellow-800">
                            <strong>Catatan:</strong> Sisa pembayaran dibayarkan setelah booking Anda disetujui admin
                        </p>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 font-semibold transition-colors flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Konfirmasi Booking</span>
                    </button>
                    
                    <p class="text-xs text-center text-gray-500 mt-3">
                        Dengan klik tombol di atas, Anda menyetujui syarat & ketentuan yang berlaku
                    </p>
                </div>
                
                <!-- Bantuan -->
                <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Butuh Bantuan?
                    </h3>
                    <p class="text-sm text-gray-700 mb-3">
                        Jika ada pertanyaan seputar booking, hubungi kami:
                    </p>
                    @if($room->kosInfo)
                    <div class="space-y-2">
                        <a href="tel:{{ $room->kosInfo->phone }}" 
                           class="flex items-center text-sm text-gray-700 hover:text-purple-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $room->kosInfo->phone }}
                        </a>
                        @if($room->kosInfo->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $room->kosInfo->whatsapp) }}" 
                           target="_blank"
                           class="flex items-center text-sm text-gray-700 hover:text-green-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            WhatsApp: {{ $room->kosInfo->whatsapp }}
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
                
            </div>
            
        </div>
        
    </form>
    
</div>

@endsection

@push('scripts')
<script>
function bookingForm() {
    return {
        imagePreview: null,
        
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
}

function copyAccountNumber() {
    const accountNumber = document.getElementById('accountNumber').textContent;
    navigator.clipboard.writeText(accountNumber).then(() => {
        alert('✓ Nomor rekening berhasil disalin!');
    }).catch(err => {
        console.error('Gagal menyalin:', err);
    });
}
</script>
@endpush