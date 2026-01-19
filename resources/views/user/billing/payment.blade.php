@extends('layouts.user')

@section('title', 'Bayar Tagihan')

@section('content')
<div class="space-y-6"
     x-data="paymentForm()"
     x-init="
        if(!selectedMethod) selectedMethod = null;
        if(!selectedSubMethod) selectedSubMethod = null;
     ">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pembayaran Kamar {{ $billing->room->room_number }}</h1>
            <p class="text-sm text-gray-600 mt-1">Lengkapi formulir di bawah ini untuk melakukan pembayaran</p>
        </div>
        <a href="{{ route('user.billing.index') }}"  
           class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
            Kembali ke Daftar Tagihan
        </a>
    </div>

    <!-- Billing Summary -->
    <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg shadow-lg p-8 text-white">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $billing->formatted_period }}</h1>
                <p class="text-yellow-200">Kamar {{ $billing->room->room_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-yellow-200 mb-1">Total Tagihan</p>
                <p class="text-4xl font-bold">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 pt-6 border-t border-yellow-500">
            <div>
                <p class="text-sm text-yellow-200 mb-1">Status</p>
                <p class="font-semibold">{{ $billing->status_label }}</p>
            </div>
            <div>
                <p class="text-sm text-yellow-200 mb-1">Jatuh Tempo</p>
                <p class="font-semibold">{{ $billing->due_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-yellow-200 mb-1">
                    @if($billing->is_overdue)
                        Keterlambatan
                    @else
                        Sisa Waktu
                    @endif
                </p>
                <p class="font-semibold {{ $billing->is_overdue ? 'text-red-300' : '' }}">
                    {{ abs($billing->days_until_due) }} hari
                </p>
            </div>
        </div>
    </div>

    <!-- Pending Payment Alert -->
    @if($pendingPayment)
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <h3 class="font-semibold text-yellow-800 mb-2">Pembayaran Sedang Diproses</h3>
                <p class="text-sm text-yellow-700 mb-3">
                    Anda sudah mengirim bukti pembayaran pada {{ $pendingPayment->created_at->format('d M Y, H:i') }}. 
                    Pembayaran sedang menunggu verifikasi dari admin.
                </p>
                <div class="bg-white rounded p-3 border border-yellow-200">
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-gray-600">Jumlah</p>
                            <p class="font-semibold">Rp {{ number_format($pendingPayment->amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Metode</p>
                            <p class="font-semibold">{{ $pendingPayment->payment_full_label }}</p>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-yellow-600 mt-3">
                    Anda dapat mengirim bukti pembayaran baru jika diperlukan
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Payment Form -->
    <form method="POST" 
          action="{{ route('user.billing.submit-payment', $billing) }}" 
          enctype="multipart/form-data"
          @submit="validateForm($event)">
        @csrf

        <input type="hidden" name="payment_type" x-model="selectedMethod">
        <input type="hidden" name="payment_sub_method" x-model="selectedSubMethod">

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:border-yellow-400 transition overflow-hidden">
            
            <!-- Form Header -->
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Form Pembayaran</h2>
                <p class="text-sm text-gray-600 mt-1">Pilih metode pembayaran dan upload bukti transfer</p>
            </div>

            <!-- Form Fields -->
            <div class="p-6 space-y-6">
                
                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah Pembayaran
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="number" 
                               name="amount" 
                               x-model="amount"
                               value="{{ old('amount', $billing->total_amount) }}" 
                               required 
                               min="0" 
                               step="0.01"
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-lg font-semibold">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-xs text-gray-500">
                            Total tagihan: Rp {{ number_format($billing->total_amount, 0, ',', '.') }}
                        </p>
                    @enderror
                </div>

                <!-- Payment Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Pembayaran
                    </label>
                    <input type="date" 
                           name="payment_date" 
                           value="{{ old('payment_date', now()->format('Y-m-d')) }}" 
                           required 
                           max="{{ now()->format('Y-m-d') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Metode Pembayaran
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
                    
                    @error('payment_type')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    
                    <!-- Selected Payment Info -->
                    <div x-show="selectedMethod" x-transition class="mt-6">
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
                                    <span class="text-gray-600">Jumlah:</span>
                                    <span class="text-xl font-extrabold text-yellow-600">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- QRIS -->
                        <div x-show="selectedMethod === 'qris'" class="bg-yellow-50 rounded-xl border border-yellow-200 p-6 rounded-xl text-center">
                            <p class="font-semibold text-gray-800 mb-4">Scan QR Code untuk Pembayaran</p>
                            <img src="{{ asset('storage/qris/dana.jpeg') }}" 
                                 alt="QRIS" 
                                 class="w-64 h-64 mx-auto border-4 border-white shadow-lg rounded-lg mb-4">
                            <p class="text-lg font-bold text-yellow-600 mb-2">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</p>
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
                           name="payment_proof"
                           accept="image/*"
                           @change="previewImage"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2"
                           required>

                    @error('payment_proof')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG (Max. 2MB)</p>
                    
                    <div x-show="imagePreview" class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                        <img :src="imagePreview" class="max-w-md rounded-lg border-2 border-gray-200">
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea name="notes" 
                              rows="3" 
                              placeholder="Tambahkan catatan jika diperlukan..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">{{ old('notes') }}</textarea>
                </div>

            </div>

            <!-- Form Footer -->
            <div class="p-6 bg-gray-50 border-t border-gray-200">
                <div class="flex gap-3">
                    <a href="{{ route('user.billing.show', $billing) }}" 
                       class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 text-center rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Batal
                    </a>
                    <button type="submit" 
                            :disabled="!selectedMethod"
                            :class="selectedMethod ? 'bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700' : 'bg-gray-300 cursor-not-allowed'"
                            class="flex-1 px-6 py-3 text-white rounded-lg transition-all shadow-lg flex items-center justify-center">
                        Kirim Pembayaran
                    </button>
                </div>
            </div>

        </div>
    </form>

    <!-- Payment Method Modal -->
    <div x-show="showPaymentModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
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

    <!-- Instructions -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-yellow-900 mb-2">Panduan Pembayaran</h3>
                <ol class="space-y-2 text-sm text-yellow-800">
                    <li class="flex items-start">
                        <span class="font-bold mr-2">1.</span>
                        <span>Pilih metode pembayaran yang tersedia</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">2.</span>
                        <span>Lakukan pembayaran sesuai nominal tagihan</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">3.</span>
                        <span>Upload bukti transfer yang jelas</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">4.</span>
                        <span>Tunggu verifikasi admin (maksimal 1x24 jam)</span>
                    </li>
                </ol>
            </div>
        </div>
    </div>

</div>

<script>
function paymentForm() {
    return {
        showPaymentModal: false,
        selectedMethod: '{{ old("payment_type") }}' || null,
        selectedSubMethod: '{{ old("payment_sub_method") }}' || null,
        amount: {{ old('amount', $billing->total_amount) }},

        imagePreview: null,

        paymentData: @json($paymentMethods),

        /* ===============================
           WAJIB ADA (INI YANG ERROR)
        =============================== */

        getSubMethodLabel() {
            if (!this.selectedMethod || !this.selectedSubMethod) return '-';
                
            const method = this.paymentData[this.selectedMethod];
            if (!method || !method.options) return '-';
                
            return method.options[this.selectedSubMethod]?.name ?? '-';
        },

        getAccountNumber() {
            if (!this.selectedMethod || !this.selectedSubMethod) return '-';

            const method = this.paymentData[this.selectedMethod];
            if (!method || !method.options) return '-';

            return method.options[this.selectedSubMethod]?.account ?? '-';
        },

        getAccountHolder() {
            if (!this.selectedMethod || !this.selectedSubMethod) return '-';

            const method = this.paymentData[this.selectedMethod];
            if (!method || !method.options) return '-';

            return method.options[this.selectedSubMethod]?.holder ?? '-';
        },

        /* =============================== */

        previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                alert('Hanya JPG / PNG');
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Maksimal 2MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = e => this.imagePreview = e.target.result;
            reader.readAsDataURL(file);
        },

        selectPayment(method, subMethod) {
            this.selectedMethod = method;
            this.selectedSubMethod = subMethod;
            this.showPaymentModal = false;
        },

        validateForm(e) {
            if (!this.selectedMethod) {
                e.preventDefault();
                alert('Pilih metode pembayaran dulu');
            }
        }
    }
}
</script>

@endsection