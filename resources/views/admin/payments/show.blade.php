@extends('layouts.admin')

@section('title', 'Detail Tagihan')
@section('page-title', 'Detail Tagihan')
@section('page-description', 'Informasi lengkap tagihan operasional')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Billing Info Card -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-700 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white">Tagihan Operasional</h3>
                <p class="text-sm text-slate-400 mt-1">
                    Periode: {{ \Carbon\Carbon::parse($billing->billing_period . '-01')->format('F Y') }}
                </p>
            </div>
            @php
                $statusColor = $billing->status_color;
            @endphp
            <span class="px-4 py-2 text-sm font-medium bg-{{ $statusColor }}-500/20 text-{{ $statusColor }}-400 rounded-full">
                {{ $billing->status_label }}
            </span>
        </div>

        <div class="p-6 space-y-4">
            <!-- Amount -->
            <div class="flex items-center justify-between py-3 border-b border-slate-700">
                <span class="text-sm text-slate-400">Jumlah Tagihan</span>
                <span class="text-2xl font-bold text-yellow-400">
                    Rp {{ number_format($billing->amount, 0, ',', '.') }}
                </span>
            </div>

            <!-- Due Date -->
            <div class="flex items-center justify-between py-3 border-b border-slate-700">
                <span class="text-sm text-slate-400">Jatuh Tempo</span>
                <div class="text-right">
                    <span class="text-sm text-white">{{ $billing->due_date->format('d F Y') }}</span>
                    @if($billing->isOverdue())
                        <p class="text-xs text-red-400 mt-1">
                            Terlambat {{ $billing->due_date->diffForHumans() }}
                        </p>
                    @elseif($billing->status === 'unpaid')
                        <p class="text-xs text-slate-400 mt-1">
                            {{ $billing->due_date->diffForHumans() }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Description -->
            @if($billing->description)
            <div class="flex items-start justify-between py-3 border-b border-slate-700">
                <span class="text-sm text-slate-400">Keterangan</span>
                <span class="text-sm text-white text-right max-w-md">{{ $billing->description }}</span>
            </div>
            @endif

            <!-- Payment Method (if paid) -->
            @if($billing->payment_method)
            <div class="flex items-center justify-between py-3 border-b border-slate-700">
                <span class="text-sm text-slate-400">Metode Pembayaran</span>
                <span class="text-sm text-white">{{ $billing->payment_method }}</span>
            </div>
            @endif

            <!-- Paid At -->
            @if($billing->paid_at)
            <div class="flex items-center justify-between py-3 border-b border-slate-700">
                <span class="text-sm text-slate-400">Dibayar Pada</span>
                <span class="text-sm text-white">{{ $billing->paid_at->format('d F Y, H:i') }}</span>
            </div>
            @endif

            <!-- Payment Proof -->
            @if($billing->payment_proof)
            <div class="py-3">
                <span class="block text-sm text-slate-400 mb-3">Bukti Pembayaran</span>
                <img src="{{ Storage::url($billing->payment_proof) }}" 
                     alt="Bukti Pembayaran"
                     class="w-full max-w-md rounded-lg border border-slate-700">
            </div>
            @endif

            <!-- Payment Notes -->
            @if($billing->payment_notes)
            <div class="flex items-start justify-between py-3">
                <span class="text-sm text-slate-400">Catatan Pembayaran</span>
                <span class="text-sm text-white text-right max-w-md">{{ $billing->payment_notes }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Payment Form (if unpaid) -->
    @if($billing->status === 'unpaid')
    <div class="bg-slate-800 rounded-xl border border-yellow-500/40 overflow-hidden" 
         x-data="{ 
             selectedMethod: null, 
             showModal: false,
             isQris: false,
             amount: {{ $billing->amount }}
         }">
        <div class="p-6 border-b border-slate-700">
            <h3 class="text-lg font-semibold text-yellow-400">Lakukan Pembayaran</h3>
            <p class="text-sm text-slate-400 mt-1">Pilih metode pembayaran dan upload bukti transfer</p>
        </div>

        <form action="{{ route('admin.payments.pay', $billing) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Payment Method Selection -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Metode Pembayaran <span class="text-red-400">*</span>
                </label>
                
                <button type="button" 
                        @click="showModal = true"
                        class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-left focus:outline-none focus:border-yellow-500 flex items-center justify-between hover:border-yellow-500 transition">
                    <span x-text="selectedMethod ? selectedMethod.name : 'Pilih Metode Pembayaran'" 
                          class="text-white"></span>
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <input type="hidden" name="payment_method" x-model="selectedMethod ? selectedMethod.id : ''">
                
                @error('payment_method')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror

                <!-- Selected Method Info -->
                <div x-show="selectedMethod" class="mt-3">
                    <!-- QRIS Display -->
                    <div x-show="isQris" class="p-6 bg-yellow-50 rounded-lg border-2 border-yellow-300">
                        <h4 class="text-center text-gray-800 font-semibold mb-3">Scan QR Code untuk Pembayaran</h4>
                        
                        <div class="flex justify-center mb-4">
                            <img :src="selectedMethod?.qr_code_url" 
                                 alt="QRIS QR Code"
                                 class="w-64 h-64 border-4 border-blue-500 rounded-lg shadow-lg">
                        </div>

                        <div class="text-center">
                            <p class="text-2xl font-bold text-yellow-600 mb-2" x-text="'Rp ' + amount.toLocaleString('id-ID')"></p>
                            <p class="text-sm text-gray-600">Scan dengan aplikasi pembayaran favorit Anda</p>
                        </div>
                    </div>

                    <!-- Bank/E-Wallet Display -->
                    <div x-show="!isQris" class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <div class="flex items-center gap-3 mb-3">
                            <div :class="selectedMethod?.icon || 'bg-gray-500'" class="w-10 h-10 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold" x-text="selectedMethod?.name.charAt(0)"></span>
                            </div>
                            <div>
                                <p class="text-white font-medium" x-text="selectedMethod?.name"></p>
                                <p class="text-xs text-slate-400" x-text="selectedMethod?.type === 'bank' ? 'Transfer Bank' : 'E-Wallet'"></p>
                            </div>
                        </div>
                        <div x-show="selectedMethod?.account_number" class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-400">Nomor Rekening:</span>
                                <span class="font-mono font-semibold text-white" x-text="selectedMethod?.account_number"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-400">Atas Nama:</span>
                                <span class="text-white" x-text="selectedMethod?.account_name"></span>
                            </div>
                            <div class="flex justify-between text-sm pt-2 border-t border-slate-700">
                                <span class="text-slate-400">Jumlah Transfer:</span>
                                <span class="font-bold text-yellow-400" x-text="'Rp ' + amount.toLocaleString('id-ID')"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Proof -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Bukti Pembayaran <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <input type="file" 
                           name="payment_proof" 
                           accept="image/*"
                           required
                           class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-yellow-500"
                           onchange="previewImage(event)">
                </div>
                <p class="text-xs text-slate-400 mt-2">Format: JPG, PNG (Max 2MB)</p>
                @error('payment_proof')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror

                <!-- Image Preview -->
                <div id="imagePreview" class="mt-4 hidden">
                    <img src="" alt="Preview" class="max-w-md rounded-lg border border-slate-700">
                </div>
            </div>

            <!-- Payment Notes -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Catatan (Opsional)
                </label>
                <textarea name="payment_notes" 
                          rows="3"
                          placeholder="Contoh: Transfer dari rekening BCA a.n. John Doe"
                          class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-yellow-500">{{ old('payment_notes') }}</textarea>
                @error('payment_notes')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4 border-t border-slate-700">
                <a href="{{ route('admin.payments.index') }}" 
                   class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-medium transition">
                    Kembali
                </a>
                <button type="submit" 
                        :disabled="!selectedMethod"
                        :class="selectedMethod ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-gray-600 cursor-not-allowed'"
                        class="px-6 py-3 text-white rounded-lg font-medium transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Kirim Pembayaran
                </button>
            </div>
        </form>

        <!-- Modal Payment Methods -->
        <div x-show="showModal" 
             x-cloak
             @click.self="showModal = false"
             class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-slate-800 rounded-xl max-w-3xl w-full max-h-[90vh] overflow-hidden shadow-2xl border border-slate-700">
                <!-- Modal Header -->
                <div class="p-6 border-b border-slate-700 flex items-center justify-between sticky top-0 bg-slate-800 z-10">
                    <h3 class="text-xl font-semibold text-white">Pilih Metode Pembayaran</h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)] space-y-6">
                    @foreach($paymentMethods as $type => $methods)
                        <div>
                            <h4 class="text-sm font-semibold text-slate-300 uppercase mb-3">
                                {{ $type === 'bank' ? 'Transfer Bank' : ($type === 'ewallet' ? 'E-Wallet' : 'QRIS') }}
                            </h4>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($methods as $method)
                                <button type="button"
                                        @click="selectedMethod = {{ $method->toJson() }}; isQris = ({{ $method->toJson() }}.type === 'qris'); showModal = false"
                                        class="p-4 bg-slate-900 hover:bg-slate-700 border border-slate-700 hover:border-yellow-500 rounded-lg text-left transition group">
                                    <div class="flex items-center gap-3">
                                        <div class="{{ $method->icon }} w-12 h-12 rounded-lg flex items-center justify-center">
                                            <span class="text-white font-bold text-lg">{{ substr($method->name, 0, 1) }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-white font-medium group-hover:text-yellow-400 transition">{{ $method->name }}</p>
                                            @if($method->account_number)
                                                <p class="text-xs text-slate-400 font-mono">{{ $method->account_number }}</p>
                                            @elseif($method->type === 'qris')
                                                <p class="text-xs text-slate-400">Scan QR untuk bayar</p>
                                            @endif
                                        </div>
                                        <svg class="w-5 h-5 text-slate-600 group-hover:text-yellow-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="flex justify-center">
        <a href="{{ route('admin.payments.index') }}" 
           class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-medium transition">
            Kembali ke Daftar Tagihan
        </a>
    </div>
    @endif

</div>

@push('scripts')
<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const img = preview.querySelector('img');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection