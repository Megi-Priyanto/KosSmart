@extends('layouts.user')

@section('title', 'Bayar Tagihan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <a href="{{ route('user.payments') }}" 
       class="inline-flex items-center text-sm text-yellow-400 hover:text-yellow-500">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Daftar Tagihan
    </a>
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
                            <p class="font-semibold">{{ $pendingPayment->payment_method_label }}</p>
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
          x-data="paymentForm()">
        @csrf

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:border-yellow-400 transition overflow-hidden">
            
            <!-- Form Header -->
            <div class="p-6 border-b border-gray-200 bg-gray-50 hover:border-yellow-400 transition">
                <h2 class="text-xl font-bold text-gray-900">Form Pembayaran</h2>
                <p class="text-sm text-gray-600 mt-1">Isi data pembayaran dan upload bukti transfer</p>
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
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-lg font-semibold @error('amount') border-red-500 @enderror">
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
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 @error('payment_date') border-red-500 @enderror">
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @endif
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Metode Pembayaran
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-yellow-500 transition-colors"
                               :class="paymentMethod === 'transfer' ? 'border-yellow-500 bg-yellow-50' : ''">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="transfer" 
                                   x-model="paymentMethod"
                                   required 
                                   class="sr-only">
                            <svg class="w-8 h-8 mb-2" :class="paymentMethod === 'transfer' ? 'text-yellow-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span class="text-sm font-medium" :class="paymentMethod === 'transfer' ? 'text-yellow-600' : 'text-gray-700'">Transfer Bank</span>
                        </label>

                        <label class="relative flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-yellow-500 transition-colors"
                               :class="paymentMethod === 'e-wallet' ? 'border-yellow-500 bg-yellow-50' : ''">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="e-wallet" 
                                   x-model="paymentMethod"
                                   required 
                                   class="sr-only">
                            <svg class="w-8 h-8 mb-2" :class="paymentMethod === 'e-wallet' ? 'text-yellow-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium" :class="paymentMethod === 'e-wallet' ? 'text-yellow-600' : 'text-gray-700'">E-Wallet</span>
                        </label>

                        <label class="relative flex flex-col items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-yellow-500 transition-colors"
                               :class="paymentMethod === 'cash' ? 'border-yellow-500 bg-yellow-50' : ''">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="cash" 
                                   x-model="paymentMethod"
                                   required 
                                   class="sr-only">
                            <svg class="w-8 h-8 mb-2" :class="paymentMethod === 'cash' ? 'text-yellow-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="text-sm font-medium" :class="paymentMethod === 'cash' ? 'text-yellow-600' : 'text-gray-700'">Tunai</span>
                        </label>
                    </div>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload Proof -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Pembayaran<span class="text-xs text-gray-500">(JPG, PNG max 5MB)</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-yellow-500 transition-colors"
                         @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="handleDrop($event)"
                         :class="isDragging ? 'border-yellow-500 bg-yellow-50' : ''">
                        
                        <input type="file" 
                               name="payment_proof" 
                               id="payment_proof"
                               @change="handleFileSelect($event)"
                               accept="image/jpeg,image/png,image/jpg" 
                               required 
                               class="hidden">
                        
                        <div x-show="!preview">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <label for="payment_proof" class="cursor-pointer">
                                <span class="text-yellow-600 hover:text-yellow-700 font-medium">Klik untuk upload</span>
                                <span class="text-gray-600"> atau drag & drop</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG (Max 5MB)</p>
                        </div>

                        <div x-show="preview" class="relative">
                            <img :src="preview" alt="Preview" class="mx-auto max-h-64 rounded-lg">
                            <button type="button" 
                                    @click="removeFile()"
                                    class="absolute top-2 right-2 p-2 bg-red-600 text-white rounded-full hover:bg-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                            <p class="text-sm text-gray-600 mt-2" x-text="fileName"></p>
                        </div>
                    </div>
                    @error('payment_proof')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                            class="flex-1 px-6 py-3 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500 transition-colors font-semibold">
                        Kirim Pembayaran
                    </button>
                </div>
            </div>

        </div>
    </form>

    <!-- Instructions -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 hover:border-slate-400 transition">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-yellow-900 mb-2">Panduan Pembayaran</h3>
                <ol class="space-y-2 text-sm text-yellow-800">
                    <li class="flex items-start">
                        <span class="font-bold mr-2">1.</span>
                        <span>Lakukan pembayaran sesuai nominal tagihan ke rekening yang disediakan</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">2.</span>
                        <span>Upload bukti transfer yang jelas (pastikan nominal dan tanggal terlihat)</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">3.</span>
                        <span>Tunggu verifikasi dari admin (maksimal 1x24 jam)</span>
                    </li>
                    <li class="flex items-start">
                        <span class="font-bold mr-2">4.</span>
                        <span>Status pembayaran akan diupdate setelah diverifikasi</span>
                    </li>
                </ol>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function paymentForm() {
    return {
        paymentMethod: '{{ old('payment_method', 'transfer') }}',
        amount: {{ old('amount', $billing->total_amount) }},
        preview: null,
        fileName: '',
        isDragging: false,

        handleFileSelect(event) {
            const file = event.target.files[0];
            this.processFile(file);
        },

        handleDrop(event) {
            this.isDragging = false;
            const file = event.dataTransfer.files[0];
            
            // Set file to input
            const input = document.getElementById('payment_proof');
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            
            this.processFile(file);
        },

        processFile(file) {
            if (!file) return;

            // Validate file type
            if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                alert('Hanya file JPG atau PNG yang diperbolehkan');
                return;
            }

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file maksimal 5MB');
                return;
            }

            this.fileName = file.name;
            
            // Create preview
            const reader = new FileReader();
            reader.onload = (e) => {
                this.preview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        removeFile() {
            this.preview = null;
            this.fileName = '';
            document.getElementById('payment_proof').value = '';
        }
    }
}
</script>
@endpush

@endsection