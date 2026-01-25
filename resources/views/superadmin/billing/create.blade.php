@extends('layouts.superadmin')

@section('title', 'Generate Tagihan Massal')
@section('page-title', 'Buat Tagihan')
@section('page-description', 'Generate tagihan operasional untuk admin kos')

@section('content')
<div class="space-y-6">

    <div class="bg-slate-800 rounded-xl border border-slate-700 p-8">
        
        <form action="{{ route('superadmin.billing.store') }}" method="POST" x-data="billingForm()">
            @csrf

            <!-- Billing Type -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-300 mb-3">Tipe Penagihan</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border-2 border-slate-700 rounded-lg cursor-pointer hover:border-yellow-500 transition"
                           :class="billingType === 'all' ? 'border-yellow-500 bg-yellow-500/10' : ''">
                        <input type="radio" 
                               name="billing_type" 
                               value="all" 
                               x-model="billingType"
                               class="sr-only">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-white">Tagih Semua Admin</p>
                            <p class="text-xs text-slate-400 mt-1">Generate tagihan untuk seluruh admin kos aktif</p>
                        </div>
                        <svg x-show="billingType === 'all'" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                    </label>

                    <label class="relative flex items-center p-4 border-2 border-slate-700 rounded-lg cursor-pointer hover:border-yellow-500 transition"
                           :class="billingType === 'selected' ? 'border-yellow-500 bg-yellow-500/10' : ''">
                        <input type="radio" 
                               name="billing_type" 
                               value="selected" 
                               x-model="billingType"
                               class="sr-only">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-white">Pilih Admin Tertentu</p>
                            <p class="text-xs text-slate-400 mt-1">Generate tagihan untuk admin yang dipilih</p>
                        </div>
                        <svg x-show="billingType === 'selected'" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                    </label>
                </div>
                @error('billing_type')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Select Tempat Kos (if selected type) -->
            <div x-show="billingType === 'selected'" x-transition class="mb-6">
                <label class="block text-sm font-medium text-slate-300 mb-3">
                    Pilih Tempat Kos <span class="text-red-400"></span>
                </label>
                <div class="space-y-2 max-h-64 overflow-y-auto border border-slate-700 rounded-lg p-4 bg-slate-900">
                    @foreach($tempatKosList as $kos)
                    <label class="flex items-center p-3 hover:bg-slate-800 rounded-lg cursor-pointer transition">
                        <input type="checkbox" 
                               name="tempat_kos_ids[]" 
                               value="{{ $kos->id }}"
                               class="w-4 h-4 text-yellow-500 bg-slate-700 border-slate-600 rounded focus:ring-yellow-500">
                        <span class="ml-3 text-sm text-white flex-1">
                            {{ $kos->nama_kos }}
                            <span class="text-xs text-slate-400 ml-2">(Admin: {{ $kos->admins->first()->name ?? 'Belum ada' }})</span>
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('tempat_kos_ids')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Billing Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Jumlah Tagihan <span class="text-red-400"></span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">Rp</span>
                        <input type="number" 
                               name="amount" 
                               value="{{ old('amount', 850000) }}"
                               required
                               min="0"
                               step="1000"
                               class="w-full pl-12 pr-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-yellow-500">
                    </div>
                    @error('amount')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Jatuh Tempo <span class="text-red-400"></span>
                    </label>
                    <input type="date" 
                           name="due_date" 
                           value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}"
                           required
                           min="{{ now()->addDay()->format('Y-m-d') }}"
                           class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-yellow-500 [color-scheme:dark]">
                    @error('due_date')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Billing Month -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Bulan Tagihan <span class="text-red-400"></span>
                    </label>
                    <select name="billing_month" 
                            required
                            class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-yellow-500">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('billing_month', now()->month) == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                    @error('billing_month')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Billing Year -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        Tahun Tagihan <span class="text-red-400"></span>
                    </label>
                    <select name="billing_year" 
                            required
                            class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-yellow-500">
                        @for($y = date('Y'); $y <= date('Y') + 2; $y++)
                            <option value="{{ $y }}" {{ old('billing_year', now()->year) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                    @error('billing_year')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-300 mb-2">
                    Keterangan
                </label>
                <textarea name="description" 
                          rows="3"
                          placeholder="Tagihan operasional aplikasi bulan..."
                          class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg text-white focus:outline-none focus:border-yellow-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('superadmin.billing.index') }}" 
                   class="px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-medium transition">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Generate Tagihan
                </button>
            </div>

        </form>

    </div>
</div>

@push('scripts')
<script>
function billingForm() {
    return {
        billingType: '{{ old('billing_type', 'all') }}'
    }
}
</script>
@endpush
@endsection