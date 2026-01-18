@extends('layouts.admin')

@section('title', 'Buat Tagihan Baru')
@section('page-title', 'Buat Tagihan Baru')
@section('page-description', 'Form untuk membuat tagihan penghuni')

@section('content')

<div class="max-w-full">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.billing.index') }}" 
           class="px-5 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
            Kembali ke Daftar Tagihan
        </a>
    </div>

    <form method="POST" action="{{ route('admin.billing.store') }}" x-data="billingForm()">
        @csrf

        <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 overflow-hidden">

            <!-- Tenant Selection -->
            <div class="p-8 border-b border-slate-700">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    Pilih Penyewa
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-slate-300 mb-3">Penyewa Aktif <span class="text-red-400"></span></label>
                        <select name="rent_id" 
                                x-model="rentId"
                                @change="selectRent()"
                                required 
                                class="w-full px-5 py-3 bg-slate-900 border-2 border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all @error('rent_id') border-red-500 @enderror">
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
                            <p class="mt-2 text-sm text-red-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-3">Bulan <span class="text-red-400"></span></label>
                        <select name="billing_month" x-model="billingMonth" required class="w-full px-5 py-3 bg-slate-900 border-2 border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all @error('billing_month') border-red-500 @enderror">
                            <option value="">Pilih Bulan</option>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ old('billing_month', now()->month) == $m ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                        @error('billing_month')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-3">Tahun <span class="text-red-400"></span></label>
                        <input type="number" 
                               name="billing_year" 
                               x-model="billingYear"
                               value="{{ old('billing_year', now()->year) }}" 
                               required 
                               min="2024" 
                               class="w-full px-5 py-3 bg-slate-900 border-2 border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all @error('billing_year') border-red-500 @enderror">
                        @error('billing_year')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-300 mb-3">Jatuh Tempo <span class="text-red-400"></span></label>
                        <input type="date" 
                               name="due_date" 
                               value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" 
                               required 
                               min="{{ now()->format('Y-m-d') }}"
                               class="w-full px-5 py-3 bg-slate-900 border-2 border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all [color-scheme:dark] @error('due_date') border-red-500 @enderror">
                        @error('due_date')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Cost Breakdown -->
            <div class="p-8 border-b border-slate-700">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="md:col-span-2 bg-gradient-to-r from-yellow-900/20 to-yellow-900/10 border-2 border-yellow-600/50 rounded-lg p-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-medium text-slate-300">Subtotal:</span>
                            <span class="text-lg font-bold text-white" x-text="formatCurrency(subtotal)"></span>
                        </div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-medium text-slate-300">Diskon:</span>
                            <span class="text-lg font-bold text-red-400" x-text="'- ' + formatCurrency(discount)"></span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-yellow-600/30">
                            <span class="text-lg font-bold text-white">Total Tagihan:</span>
                            <span class="text-3xl font-bold text-yellow-500" x-text="formatCurrency(total)"></span>
                        </div>
                    </div>
                
                </div>
            </div>

            <!-- Additional Info -->
            <div class="p-8 border-b border-slate-700">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    Informasi Tambahan
                </h3>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-300 mb-3">Catatan Admin</label>
                    <textarea name="admin_notes" 
                              rows="4" 
                              placeholder="Catatan tambahan untuk tagihan ini..."
                              class="w-full px-5 py-3 bg-slate-900 border-2 border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all placeholder-slate-500">{{ old('admin_notes') }}</textarea>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-8 bg-slate-900">
                <div class="flex gap-4 justify-end">
                    <a href="{{ route('admin.billing.index') }}" 
                       class="px-8 py-3 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition-all font-semibold shadow-lg">
                        Batal
                    </a>
                    <button type="submit" 
                           class="inline-flex items-center gap-2
                                bg-gradient-to-r from-yellow-500 to-orange-600
                                text-white font-semibold
                                px-5 py-3 rounded-lg
                                hover:from-yellow-600 hover:to-orange-700
                                transition-all shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Simpan Tagihan</span>
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