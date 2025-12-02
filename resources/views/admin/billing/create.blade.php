@extends('layouts.admin')

@section('title', 'Buat Tagihan Baru')
@section('page-title', 'Buat Tagihan Baru')
@section('page-description', 'Form untuk membuat tagihan penghuni')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('admin.billing.store') }}" x-data="billingForm()">
        @csrf

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            
            <!-- Tenant Selection -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pilih Penyewa</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penyewa Aktif *</label>
                        <select name="rent_id" 
                                x-model="rentId"
                                @change="selectRent()"
                                required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('rent_id') border-red-500 @enderror">
                            <option value="">-- Pilih Penyewa --</option>
                            @foreach($activeRents as $rent)
                                <option value="{{ $rent->id }}" 
                                        data-user="{{ $rent->user->name }}"
                                        data-room="{{ $rent->room->room_number }}"
                                        data-price="{{ $rent->room->price }}">
                                    {{ $rent->user->name }} - Kamar {{ $rent->room->room_number }} (Rp {{ number_format($rent->room->price, 0, ',', '.') }}/bulan)
                                </option>
                            @endforeach
                        </select>
                        @error('rent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bulan *</label>
                        <select name="billing_month" x-model="billingMonth" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('billing_month') border-red-500 @enderror">
                            <option value="">Pilih Bulan</option>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ old('billing_month', now()->month) == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                        @error('billing_month')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun *</label>
                        <input type="number" 
                               name="billing_year" 
                               x-model="billingYear"
                               value="{{ old('billing_year', now()->year) }}" 
                               required 
                               min="2024" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('billing_year') border-red-500 @enderror">
                        @error('billing_year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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
                               value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" 
                               required 
                               min="{{ now()->format('Y-m-d') }}"
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
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('admin_notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-6 bg-gray-50">
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.billing.index') }}" 
                       class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        Simpan Tagihan
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
        rentId: '{{ old('rent_id') }}',
        billingMonth: {{ old('billing_month', now()->month) }},
        billingYear: {{ old('billing_year', now()->year) }},
        rentAmount: {{ old('rent_amount', 0) }},
        discount: {{ old('discount', 0) }},
        subtotal: 0,
        total: 0,

        init() {
            this.calculateTotal();
        },

        selectRent() {
            const select = document.querySelector('select[name="rent_id"]');
            const option = select.options[select.selectedIndex];
            if (option.value) {
                this.rentAmount = parseFloat(option.dataset.price) || 0;
                this.calculateTotal();
            }
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