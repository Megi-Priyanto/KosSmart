@extends('layouts.admin')

@section('title', 'Edit Tagihan')
@section('page-title', 'Edit Tagihan')
@section('page-description', 'Perbarui informasi tagihan')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('admin.billing.update', $billing) }}" x-data="billingForm()">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            
            <!-- Info Banner -->
            <div class="p-4 bg-blue-50 border-b border-blue-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">
                            Mengedit tagihan {{ $billing->formatted_period }} untuk {{ $billing->user->name }}
                        </p>
                        <p class="text-xs text-blue-600">Periode dan penyewa tidak dapat diubah</p>
                    </div>
                </div>
            </div>

            <!-- Cost Breakdown -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Rincian Biaya</h3>
                
                <div class="space-y-4">
                
                    <!-- Biaya Sewa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Biaya Sewa *</label>
                        <input type="number" 
                               name="rent_amount" 
                               x-model.number="rentAmount"
                               @input="calculateTotal()"
                               required 
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    </div>
                
                    <!-- Diskon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Diskon</label>
                        <input type="number" 
                               name="discount" 
                               x-model.number="discount"
                               @input="calculateTotal()"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    </div>
                
                    <!-- Total Summary -->
                    <div class="bg-purple-50 border-l-4 border-purple-500 p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-700">Subtotal:</span>
                            <span class="text-lg font-semibold text-gray-900" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-700">Diskon:</span>
                            <span class="text-lg font-semibold text-red-600" x-text="'- ' + formatCurrency(discount)"></span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-purple-200">
                            <span class="text-base font-semibold text-gray-900">Total Tagihan:</span>
                            <span class="text-2xl font-bold text-purple-600" x-text="formatCurrency(total)"></span>
                        </div>
                    </div>
                
                </div>
            </div>

            <!-- Additional Info -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tambahan</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jatuh Tempo *</label>
                        <input type="date" 
                               name="due_date" 
                               value="{{ old('due_date', $billing->due_date->format('Y-m-d')) }}" 
                               required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('due_date') border-red-500 @enderror">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                        <textarea name="admin_notes" 
                                  rows="3" 
                                  placeholder="Catatan tambahan untuk tagihan ini..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('admin_notes', $billing->admin_notes) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-6 bg-gray-50">
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.billing.show', $billing) }}" 
                       class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        Simpan Perubahan
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