@extends('layouts.user')

@section('title', 'Bayar Tagihan')

@section('content')
<div class="space-y-6"
     x-data="paymentForm()"
     x-init="if(!selectedMethod) selectedMethod = null; if(!selectedSubMethod) selectedSubMethod = null;">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-bold text-white">Pembayaran Kamar {{ $billing->room->room_number }}</h1>
            <p class="text-sm text-slate-500 mt-1">Lengkapi formulir di bawah ini untuk melakukan pembayaran</p>
        </div>
        <a href="{{ route('user.billing.index') }}"
           class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg">
            Kembali ke Daftar Tagihan
        </a>
    </div>

    {{-- BILLING SUMMARY BANNER --}}
    <div class="rounded-xl p-6 text-white bg-gradient-to-br from-yellow-500 to-orange-600 shadow-xl">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold mb-1">{{ $billing->formatted_period }}</h2>
                <p class="text-yellow-100 text-sm">Kamar {{ $billing->room->room_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-yellow-200 mb-1">Total Tagihan</p>
                <p class="text-3xl font-bold">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4 pt-4" style="border-top:1px solid rgba(255,255,255,0.3);">
            <div>
                <p class="text-xs text-yellow-200 mb-0.5">Status</p>
                <p class="font-semibold text-sm">{{ $billing->status_label }}</p>
            </div>
            <div>
                <p class="text-xs text-yellow-200 mb-0.5">Jatuh Tempo</p>
                <p class="font-semibold text-sm">{{ $billing->due_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-yellow-200 mb-0.5">{{ $billing->is_overdue ? 'Keterlambatan' : 'Sisa Waktu' }}</p>
                <p class="font-semibold text-sm {{ $billing->is_overdue ? 'text-red-200' : '' }}">{{ abs($billing->days_until_due) }} hari</p>
            </div>
        </div>
    </div>

    {{-- PENDING PAYMENT ALERT --}}
    @if($pendingPayment)
    <div class="p-5 rounded-xl" style="background:rgba(234,179,8,0.08); border-left:4px solid #eab308; border-top:1px solid rgba(234,179,8,0.2); border-right:1px solid rgba(234,179,8,0.2); border-bottom:1px solid rgba(234,179,8,0.2);">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <h3 class="font-semibold text-yellow-300 mb-1">Pembayaran Sedang Diproses</h3>
                <p class="text-sm text-yellow-400 mb-3">
                    Anda sudah mengirim bukti pembayaran pada {{ $pendingPayment->created_at->format('d M Y, H:i') }}.
                    Pembayaran sedang menunggu verifikasi dari admin.
                </p>
                <div class="grid grid-cols-2 gap-3 text-sm p-3 rounded-lg" style="background:#0f172a; border:1px solid #334155;">
                    <div>
                        <p class="text-slate-500 text-xs mb-0.5">Jumlah</p>
                        <p class="font-semibold text-slate-200">Rp {{ number_format($pendingPayment->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs mb-0.5">Metode</p>
                        <p class="font-semibold text-slate-200">{{ $pendingPayment->payment_full_label }}</p>
                    </div>
                </div>
                <p class="text-xs text-yellow-600 mt-2">Anda dapat mengirim bukti pembayaran baru jika diperlukan</p>
            </div>
        </div>
    </div>
    @endif

    {{-- PAYMENT FORM --}}
    <form method="POST"
          action="{{ route('user.billing.submit-payment', $billing) }}"
          enctype="multipart/form-data"
          @submit="validateForm($event)">
        @csrf
        <input type="hidden" name="payment_type" x-model="selectedMethod">
        <input type="hidden" name="payment_sub_method" x-model="selectedSubMethod">

        <div class="rounded-xl overflow-hidden shadow-xl" style="background:#1e293b; border:1px solid #334155;">

            {{-- Form Header --}}
            <div class="p-6" style="border-bottom:1px solid #334155; background:#0f172a;">
                <h2 class="text-lg font-bold text-white">Form Pembayaran</h2>
                <p class="text-sm text-slate-500 mt-0.5">Pilih metode pembayaran dan upload bukti transfer</p>
            </div>

            <div class="p-6 space-y-6">

                {{-- Jumlah Pembayaran --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Jumlah Pembayaran</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-medium">Rp</span>
                        <input type="number" name="amount" x-model="amount"
                               value="{{ old('amount', $billing->total_amount) }}"
                               required min="0" step="0.01"
                               class="w-full pl-12 pr-4 py-3 rounded-lg text-lg font-semibold text-white"
                               style="background:#0f172a; border:1px solid #334155;">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-xs text-slate-500">Total tagihan: Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</p>
                    @enderror
                </div>

                {{-- Tanggal Pembayaran --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Tanggal Pembayaran</label>
                    <input type="date" name="payment_date"
                           value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                           required max="{{ now()->format('Y-m-d') }}"
                           class="w-full px-4 py-3 rounded-lg text-slate-200"
                           style="background:#0f172a; border:1px solid #334155; color-scheme:dark;">
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Metode Pembayaran --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Metode Pembayaran</label>
                    <button type="button" @click="showPaymentModal = true"
                            class="w-full px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span x-text="selectedMethod ? 'Ubah Metode Pembayaran' : 'Pilih Metode Pembayaran'"></span>
                    </button>
                    @error('payment_type')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror

                    {{-- Selected Payment Info --}}
                    <div x-show="selectedMethod" x-transition class="mt-4">
                        {{-- Manual / E-Wallet --}}
                        <div x-show="selectedMethod !== 'qris'" class="p-4 rounded-xl" style="background:#0f172a; border:1px solid #334155;">
                            <p class="font-semibold text-slate-200 mb-3 text-sm">Transfer ke:</p>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-500" x-text="selectedMethod === 'manual_transfer' ? 'Bank:' : 'E-Wallet:'"></span>
                                    <span class="font-semibold text-slate-200" x-text="getSubMethodLabel()"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500" x-text="selectedMethod === 'manual_transfer' ? 'No. Rekening:' : 'Nomor:'"></span>
                                    <span class="font-semibold text-slate-200 flex items-center gap-1">
                                        <span x-text="getAccountNumber()"></span>
                                        <button type="button" @click="copyToClipboard(getAccountNumber())" class="text-yellow-500 hover:text-yellow-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                            </svg>
                                        </button>
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Atas Nama:</span>
                                    <span class="font-semibold text-slate-200" x-text="getAccountHolder()"></span>
                                </div>
                                <div class="flex justify-between pt-2" style="border-top:1px solid #334155;">
                                    <span class="text-slate-500">Jumlah:</span>
                                    <span class="text-lg font-bold text-yellow-400">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- QRIS --}}
                        <div x-show="selectedMethod === 'qris'" class="p-5 rounded-xl text-center" style="background:#0f172a; border:1px solid #334155;">
                            <p class="font-semibold text-slate-200 mb-4 text-sm">Scan QR Code untuk Pembayaran</p>
                            <img src="{{ asset('storage/qris/dana.jpeg') }}" alt="QRIS"
                                 class="w-56 h-56 mx-auto rounded-lg mb-3" style="border:3px solid #334155;">
                            <p class="text-lg font-bold text-yellow-400 mb-1">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</p>
                            <p class="text-xs text-slate-500">Scan dengan aplikasi pembayaran favorit Anda</p>
                        </div>
                    </div>
                </div>

                {{-- Upload Bukti --}}
                <div x-show="selectedMethod" x-transition>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Upload Bukti Pembayaran</label>
                    <input type="file" name="payment_proof" accept="image/*"
                           @change="previewImage"
                           class="w-full px-4 py-2 rounded-lg text-slate-300 text-sm"
                           style="background:#0f172a; border:1px solid #334155;"
                           required>
                    @error('payment_proof')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG (Maks. 2MB)</p>
                    <div x-show="imagePreview" class="mt-3">
                        <p class="text-sm text-slate-400 mb-2">Preview:</p>
                        <img :src="imagePreview" class="max-w-xs rounded-lg" style="border:2px solid #334155;">
                    </div>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-2">Catatan <span class="text-slate-500 font-normal">(Opsional)</span></label>
                    <textarea name="notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..."
                              class="w-full px-4 py-3 rounded-lg text-slate-200 placeholder-slate-600 text-sm"
                              style="background:#0f172a; border:1px solid #334155;">{{ old('notes') }}</textarea>
                </div>

            </div>

            {{-- Form Footer --}}
            <div class="p-5 flex gap-3" style="background:#0f172a; border-top:1px solid #334155;">
                <a href="{{ route('user.billing.show', $billing) }}"
                   class="flex-1 py-3 text-center text-sm font-medium text-slate-300 rounded-lg transition-colors"
                   style="background:#1e293b; border:1px solid #334155;"
                   onmouseover="this.style.borderColor='#475569'"
                   onmouseout="this.style.borderColor='#334155'">
                    Batal
                </a>
                <button type="submit"
                        :disabled="!selectedMethod"
                        :class="selectedMethod
                            ? 'bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 cursor-pointer'
                            : 'bg-slate-700 text-slate-500 cursor-not-allowed'"
                        class="flex-1 py-3 text-sm font-semibold text-white rounded-lg transition-all shadow-lg">
                    Kirim Pembayaran
                </button>
            </div>

        </div>
    </form>

    {{-- PAYMENT METHOD MODAL --}}
    <div x-show="showPaymentModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         @click.self="showPaymentModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background:rgba(0,0,0,0.7);">

        <div @click.away="showPaymentModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
             style="background:#1e293b; border:1px solid #334155;">

            <div class="sticky top-0 p-5 flex justify-between items-center" style="background:#1e293b; border-bottom:1px solid #334155;">
                <h3 class="text-lg font-bold text-white">Pilih Metode Pembayaran</h3>
                <button @click="showPaymentModal = false" class="text-slate-500 hover:text-slate-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-5 space-y-5">
                @foreach($paymentMethods as $method => $data)
                <div class="rounded-xl p-4" style="border:2px solid #334155;">
                    <h4 class="font-bold text-slate-200 mb-3 text-sm">{{ $data['label'] }}</h4>

                    @if($method === 'qris')
                    <button type="button" @click="selectPayment('qris', 'qris')"
                            class="w-full p-4 rounded-lg transition-all text-left"
                            :style="selectedMethod === 'qris'
                                ? 'border:2px solid #f59e0b; background:rgba(245,158,11,0.08);'
                                : 'border:2px solid #334155; background:transparent;'">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background:rgba(234,179,8,0.15);">
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z"/>
                                    </svg>
                                </div>
                                <span class="font-semibold text-slate-200">QRIS (All Payment)</span>
                            </div>
                            <svg x-show="selectedMethod === 'qris'" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </button>
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($data['options'] as $key => $option)
                        <button type="button" @click="selectPayment('{{ $method }}', '{{ $key }}')"
                                class="p-4 rounded-lg transition-all text-left"
                                :style="selectedSubMethod === '{{ $key }}'
                                    ? 'border:2px solid #f59e0b; background:rgba(245,158,11,0.08);'
                                    : 'border:2px solid #334155; background:transparent;'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-200 text-sm">{{ $option['name'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $option['account'] }}</p>
                                </div>
                                <svg x-show="selectedSubMethod === '{{ $key }}'" class="w-5 h-5 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
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

    {{-- PANDUAN --}}
    <div class="p-5 rounded-xl" style="background:rgba(234,179,8,0.06); border:1px solid rgba(234,179,8,0.2);">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="font-semibold text-yellow-400 mb-2">Panduan Pembayaran</h3>
                <ol class="space-y-1 text-sm text-yellow-300/80">
                    <li><span class="font-bold mr-1">1.</span> Pilih metode pembayaran yang tersedia</li>
                    <li><span class="font-bold mr-1">2.</span> Lakukan pembayaran sesuai nominal tagihan</li>
                    <li><span class="font-bold mr-1">3.</span> Upload bukti transfer yang jelas</li>
                    <li><span class="font-bold mr-1">4.</span> Tunggu verifikasi admin (maksimal 1×24 jam)</li>
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
        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => alert('Disalin: ' + text));
        },
        previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;
            if (!['image/jpeg','image/png','image/jpg'].includes(file.type)) { alert('Hanya JPG / PNG'); return; }
            if (file.size > 2 * 1024 * 1024) { alert('Maksimal 2MB'); return; }
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
            if (!this.selectedMethod) { e.preventDefault(); alert('Pilih metode pembayaran dulu'); }
        }
    }
}
</script>

@endsection