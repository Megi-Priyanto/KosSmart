@extends('layouts.admin')

@section('title', 'Edit Tagihan')
@section('page-title', 'Edit Tagihan')
@section('page-description', 'Perbarui informasi tagihan')

@section('content')

<div class="max-w-full">

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.billing.index') }}"
            class="inline-flex items-center text-yellow-400 hover:text-yellow-500 transitions-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Tagihan
        </a>
    </div>

    <form method="POST" action="{{ route('admin.billing.update', $billing) }}" x-data="billingForm()">
        @csrf
        @method('PUT')

        <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 overflow-hidden">
            
            <!-- Info Banner -->
            <div class="p-8 border-b border-slate-700">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Mengedit tagihan {{ $billing->formatted_period }} untuk {{ $billing->user->name }}
                </h3>
                <div class="text-yellow-400">
                    <p>Periode dan penyewa tidak dapat diubah</p>
                </div>
            </div>

            <!-- Cost Breakdown -->
            <div class="p-8 border-b border-slate-700">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    Rincian Biaya
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                    <!-- Biaya Sewa -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-3">Biaya Sewa <span class="text-red-400"></span></label>
                        <input type="number" 
                               name="rent_amount" 
                               x-model.number="rentAmount"
                               @input="calculateTotal()"
                               required 
                               min="0"
                               class="w-full px-5 py-3 bg-slate-900 border-2 border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                               placeholder="0">
                    </div>
                
                    <!-- Diskon -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-3">Diskon</label>
                        <input type="number" 
                               name="discount" 
                               x-model.number="discount"
                               @input="calculateTotal()"
                               min="0"
                               class="w-full px-5 py-3 bg-slate-900 border-2 border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all"
                               placeholder="0">
                    </div>
                
                    <!-- Total Summary -->
                    <div class="md:col-span-2 bg-gradient-to-r from-blue-900/20 to-blue-900/10 border-2 border-blue-600/50 rounded-lg p-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-medium text-slate-300">Subtotal:</span>
                            <span class="text-lg font-bold text-white" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-medium text-slate-300">Diskon:</span>
                            <span class="text-lg font-bold text-red-400" x-text="'- ' + formatCurrency(discount)"></span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-blue-600/30">
                            <span class="text-lg font-bold text-white">Total Tagihan:</span>
                            <span class="text-3xl font-bold text-orange-400" x-text="formatCurrency(total)"></span>
                        </div>
                    </div>
                
                </div>
            </div>

            <!-- Additional Info -->
            <div class="p-8 border-b border-slate-700">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    Informasi Tambahan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-300 mb-3">Jatuh Tempo <span class="text-red-400"></span></label>
                        <input type="date" 
                               name="due_date" 
                               value="{{ old('due_date', $billing->due_date->format('Y-m-d')) }}" 
                               required 
                               class="w-full px-5 py-3 bg-slate-900 border-2 border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all [color-scheme:dark] @error('due_date') border-red-500 @enderror">
                        @error('due_date')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-300 mb-3">Catatan Admin</label>
                        <textarea name="admin_notes" 
                                  rows="4" 
                                  placeholder="Catatan tambahan untuk tagihan ini..."
                                  class="w-full px-5 py-3 bg-slate-900 border-2 border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all placeholder-slate-500">{{ old('admin_notes', $billing->admin_notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-8 bg-slate-900">
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('admin.billing.show', $billing) }}" 
                       class="px-8 py-3 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition-all font-semibold shadow-lg">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-lg hover:from-orange-700 hover:to-red-700 transition-all font-semibold shadow-xl flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
function billingForm() {
    return {
        rentAmount: {{ old('rent_amount', $billing->rent_amount) }},
        discount: {{ old('discount', $billing->discount) }},
        subtotal: 0,
        total: 0,

        init() {
            this.calculateTotal();
        },

        calculateTotal() {
            this.subtotal = this.rentAmount;
            this.total = Math.max(0, this.subtotal - this.discount);
        },

        formatCurrency(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }
    }
}
</script>

@endpush

@endsection