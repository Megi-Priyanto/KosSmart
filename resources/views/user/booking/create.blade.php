@extends('layouts.user')

@section('title', 'Booking Kamar ' . $room->room_number)

@section('content')

<div class="space-y-6" x-data="bookingForm()">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Booking Kamar {{ $room->room_number }}</h1>
            <p class="text-sm text-gray-600 mt-1">Lengkapi formulir di bawah ini untuk melakukan booking</p>
        </div>
        <a href="{{ route('user.rooms.show', $room) }}"  
           class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
            Kembali ke Detail Kamar
        </a>
    </div>

    <form action="{{ route('user.booking.store', $room) }}" 
          method="POST" 
          enctype="multipart/form-data"
          @submit="validateForm">
        @csrf
        
        <input type="hidden" name="payment_method" x-model="selectedMethod">
        <input type="hidden" name="payment_sub_method" x-model="selectedSubMethod">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Info Kamar -->
                <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold mb-2">Kamar {{ $room->room_number }}</h3>
                            <p class="text-yellow-100 mb-4">{{ $room->floor }} • {{ $room->size }} m²</p>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-yellow-100 text-sm">Harga Sewa/Bulan</p>
                                    <p class="text-2xl font-bold">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-yellow-100 text-sm">DP (50%)</p>
                                    <p class="text-2xl font-bold">Rp {{ number_format($depositAmount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    
                        @if(is_array($room->images) && count($room->images))
                        <img src="{{ asset('storage/' . $room->images[0]) }}" 
                             alt="Kamar {{ $room->room_number }}"
                             class="w-24 h-24 rounded-lg object-cover border-2 border-white ml-4">
                        @endif
                    </div>
                </div>
                
                <!-- Form Booking - UNIFIED CARD -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    
                    <!-- Form Header -->
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-bold text-gray-900">Form Booking</h2>
                        <p class="text-sm text-gray-600 mt-1">Lengkapi data booking dan upload bukti pembayaran DP</p>
                    </div>

                    <!-- Form Fields -->
                    <div class="p-6 space-y-6">
                        
                        <!-- Tanggal Mulai Sewa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-5 h-5 inline-block mr-1 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tanggal Mulai Sewa
                            </label>
                            <input type="date" 
                                   name="start_date" 
                                   value="{{ old('start_date', now()->addDays(3)->format('Y-m-d')) }}"
                                   min="{{ now()->format('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500"
                                   required>
                            @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-5 h-5 inline-block mr-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Metode Pembayaran DP
                            </label>
                            
                            <!-- Button Pilih Metode -->
                            <button type="button" 
                                    @click="showPaymentModal = true"
                                    class="w-full px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span x-text="selectedMethod ? 'Ubah Metode Pembayaran' : 'Pilih Metode Pembayaran'"></span>
                            </button>
                            
                            @error('payment_method')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                            
                            <!-- Selected Payment Info -->
                            <div x-show="selectedMethod" x-transition class="mt-4">
                                <!-- Manual Transfer / E-Wallet -->
                                <div x-show="selectedMethod !== 'qris'" class="p-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl">
                                    <p class="font-semibold text-gray-800 mb-3">Transfer ke:</p>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600" x-text="selectedMethod === 'manual_transfer' ? 'Bank:' : 'E-Wallet:'"></span>
                                            <span class="font-semibold text-gray-800" x-text="getSubMethodLabel()"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600" x-text="selectedMethod === 'manual_transfer' ? 'No. Rekening:' : 'Nomor:'"></span>
                                            <span class="font-semibold text-gray-800 flex items-center">
                                                <span x-text="getAccountNumber()"></span>
                                                <button type="button" 
                                                        @click="copyToClipboard(getAccountNumber())"
                                                        class="ml-2 text-amber-600 hover:text-amber-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                                    </svg>
                                                </button>
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Atas Nama:</span>
                                            <span class="font-semibold text-gray-800" x-text="getAccountHolder()"></span>
                                        </div>
                                        <div class="flex justify-between pt-2 border-t border-yellow-200">
                                            <span class="text-gray-600">Jumlah DP:</span>
                                            <span class="text-xl font-extrabold text-yellow-600">Rp {{ number_format($depositAmount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- QRIS -->
                                <div x-show="selectedMethod === 'qris'" class="bg-yellow-50 rounded-xl border border-yellow-200 p-6 rounded-xl text-center">
                                    <p class="font-semibold text-gray-800 mb-4">Scan QR Code untuk Pembayaran</p>
                                    <img src="{{ asset('storage/qris/dana.jpeg') }}" 
                                         alt="QRIS" 
                                         class="w-64 h-64 mx-auto border-4 border-white shadow-lg rounded-lg mb-4">
                                    <p class="text-lg font-bold text-yellow-600 mb-2">Rp {{ number_format($depositAmount, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600">Scan dengan aplikasi pembayaran favorit Anda</p>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Bukti Transfer -->
                        <div x-show="selectedMethod" x-transition>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Bukti Pembayaran
                            </label>
                            
                            <input type="file" 
                                   name="deposit_proof" 
                                   accept="image/*"
                                   @change="previewImage"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                   required>
                            @error('deposit_proof')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG (Max. 2MB)</p>
                            
                            <div x-show="imagePreview" class="mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                                <img :src="imagePreview" class="max-w-md rounded-lg border-2 border-gray-200">
                            </div>
                        </div>

                        <!-- Agreement -->
                        <div>
                            <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                                <input type="checkbox" 
                                       name="agreement" 
                                       value="1"
                                       id="agreement"
                                       class="w-5 h-5 rounded mt-0.5"
                                       required>
                                <label for="agreement" class="ml-3 text-sm text-gray-700">
                                    Saya telah membaca dan menyetujui syarat & ketentuan
                                </label>
                            </div>
                        </div>

                    </div>

                    <!-- Form Footer -->
                    <div class="p-6 bg-gray-50 border-t border-gray-200">
                        <button type="submit" 
                                :disabled="!selectedMethod"
                                :class="selectedMethod ? 'bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700' : 'bg-gray-300 cursor-not-allowed'"
                                class="w-full text-white py-3 font-semibold rounded-lg transition-all shadow-lg flex items-center justify-center">
                            Konfirmasi Booking
                        </button>
                        <p class="text-xs text-center text-gray-500 mt-3">
                            Dengan klik tombol di atas, Anda menyetujui syarat & ketentuan yang berlaku
                        </p>
                    </div>

                </div>
                
            </div>
            
            <!-- Right Column: Summary & Help -->
            <div class="space-y-6">
                
                <!-- Summary -->
                <div class="bg-white rounded-xl shadow-lg border-2 border-slate-200 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Booking</h3>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Kamar</span>
                            <span class="font-semibold">{{ $room->room_number }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Harga/Bulan</span>
                            <span class="font-semibold">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm border-t pt-3">
                            <span class="text-gray-600">DP (50%)</span>
                            <span class="font-semibold text-yellow-600">Rp {{ number_format($depositAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Bantuan -->
                <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                           class="flex items-center text-sm text-gray-700 hover:text-yellow-600">
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
    
    <!-- Payment Method Modal -->
    <div x-show="showPaymentModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="showPaymentModal = false"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        
        <div @click.away="showPaymentModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            
            <div class="sticky top-0 bg-white border-b p-6 flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-800">Pilih Metode Pembayaran</h3>
                <button @click="showPaymentModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="p-6 space-y-6">
                @foreach($paymentMethods as $method => $data)
                <div class="border-2 border-gray-200 rounded-xl p-4">
                    <h4 class="font-bold text-lg mb-3">{{ $data['label'] }}</h4>
                    
                    @if($method === 'qris')
                    <button type="button"
                            @click="selectPayment('qris', 'qris')"
                            class="w-full p-4 border-2 rounded-lg hover:border-yellow-500 hover:bg-yellow-50 transition-all text-left"
                            :class="selectedMethod === 'qris' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200'">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z"/>
                                    </svg>
                                </div>
                                <span class="font-semibold">QRIS (All Payment)</span>
                            </div>
                            <svg x-show="selectedMethod === 'qris'" class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </button>
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($data['options'] as $key => $option)
                        <button type="button"
                                @click="selectPayment('{{ $method }}', '{{ $key }}')"
                                class="p-4 border-2 rounded-lg hover:border-amber-500 hover:bg-amber-50 transition-all text-left"
                                :class="selectedSubMethod === '{{ $key }}' ? 'border-amber-500 bg-amber-50' : 'border-gray-200'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold">{{ $option['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $option['account'] }}</p>
                                </div>
                                <svg x-show="selectedSubMethod === '{{ $key }}'" class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
</div>

@endsection

@push('scripts')
<script>
function bookingForm() {
    return {
        showPaymentModal: false,
        selectedMethod: '{{ old("payment_method") }}',
        selectedSubMethod: '{{ old("payment_sub_method") }}',
        imagePreview: null,
        
        paymentData: @json($paymentMethods),
        
        selectPayment(method, subMethod) {
            this.selectedMethod = method;
            this.selectedSubMethod = subMethod;
            this.showPaymentModal = false;
        },
        
        getSubMethodLabel() {
            if (!this.selectedSubMethod) return '';
            return this.selectedSubMethod.toUpperCase();
        },
        
        getAccountNumber() {
            if (!this.selectedMethod || !this.selectedSubMethod) return '';
            const methodData = this.paymentData[this.selectedMethod];
            if (methodData.options && methodData.options[this.selectedSubMethod]) {
                return methodData.options[this.selectedSubMethod].account;
            }
            return '';
        },
        
        getAccountHolder() {
            if (!this.selectedMethod || !this.selectedSubMethod) return '';
            const methodData = this.paymentData[this.selectedMethod];
            if (methodData.options && methodData.options[this.selectedSubMethod]) {
                return methodData.options[this.selectedSubMethod].holder;
            }
            return '';
        },
        
        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('✓ Berhasil disalin!');
            });
        },
        
        previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        validateForm(e) {
            if (!this.selectedMethod) {
                e.preventDefault();
                alert('Silakan pilih metode pembayaran terlebih dahulu');
                return false;
            }
        }
    }
}
</script>
@endpush