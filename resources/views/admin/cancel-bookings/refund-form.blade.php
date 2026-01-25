@extends('layouts.admin')

@section('title', 'Form Pengembalian Dana')
@section('page-title', 'Proses Pengembalian Dana')
@section('page-description', 'Transfer dana DP kembali ke user')

@section('content')

<div class="space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.cancel-bookings.refund.process', $cancelBooking) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  x-data="refundForm()">
                @csrf

                <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 overflow-hidden shadow-2xl">
                    
                    <!-- Header -->
                    <div class="p-6 bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border-b-2 border-slate-700">
                        <h2 class="text-xl font-bold text-white">Form Pengembalian Dana</h2>
                        <p class="text-sm text-slate-300 mt-1">Lengkapi form untuk mengembalikan dana DP ke user</p>
                    </div>

                    <!-- Form Fields -->
                    <div class="p-6 space-y-6">
                        
                        <!-- Metode Pengembalian -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Metode Pengembalian <span class="text-red-400"></span>
                            </label>
                            <button type="button" @click="showMethodModal = true"
                                    class="w-full px-5 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span x-text="selectedMethod ? 'Ubah Metode' : 'Pilih Metode Pengembalian'"></span>
                            </button>
                            
                            <input type="hidden" name="refund_method" x-model="selectedMethod">
                            <input type="hidden" name="refund_sub_method" x-model="selectedSubMethod">
                            
                            @error('refund_method')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            
                            <!-- Selected Info -->
                            <div x-show="selectedMethod" x-transition class="mt-4 p-4 bg-slate-900 border border-slate-700 rounded-lg">
                                <p class="text-sm text-slate-400 mb-2">Metode dipilih:</p>
                                <p class="font-semibold text-white" x-text="getMethodLabel()"></p>
                            </div>
                        </div>

                        <!-- Nomor Rekening Tujuan -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Nomor Rekening/E-Wallet Tujuan <span class="text-red-400"></span>
                            </label>
                            <input type="text" name="refund_account_number" required
                                   class="w-full px-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-yellow-500"
                                   placeholder="Masukkan nomor rekening/e-wallet untuk transfer">
                            @error('refund_account_number')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah Pengembalian -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Jumlah Dana yang Dikembalikan <span class="text-red-400"></span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">Rp</span>
                                <input type="number" name="refund_amount" required
                                       value="{{ $defaultRefundAmount }}"
                                       class="w-full pl-12 pr-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-yellow-500"
                                       placeholder="0">
                            </div>
                            @error('refund_amount')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-400 mt-1">Default: Rp {{ number_format($defaultRefundAmount, 0, ',', '.') }} (DP yang dibayar)</p>
                        </div>

                        <!-- Upload Bukti Transfer -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Upload Bukti Transfer <span class="text-red-400"></span>
                            </label>
                            <input type="file" name="refund_proof" accept="image/*" required
                                   @change="previewImage"
                                   class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-slate-500 file:text-white hover:file:bg-slate-600 @error('logo') border-red-500 @enderror"
                           onchange="previewLogo(event)">
                            <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG (Max. 5MB)</p>
                            
                            <!-- Preview -->
                            <div x-show="imagePreview" class="mt-4">
                                <p class="text-sm text-slate-300 mb-2">Preview:</p>
                                <img :src="imagePreview" class="max-w-md rounded-lg border-2 border-slate-600">
                            </div>
                        </div>

                        <!-- Catatan Admin -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Catatan Admin (Opsional)
                            </label>
                            <textarea name="admin_notes" rows="3"
                                      class="w-full px-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-yellow-500"
                                      placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="p-6 bg-slate-900/50 border-t-2 border-slate-700">
                        <button type="button"
                                :disabled="!selectedMethod"
                                @click="handleSubmit()"
                                :class="selectedMethod ? 'bg-gradient-to-r from-yellow-500 to-orange-600 font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700' : 'bg-slate-600 cursor-not-allowed'"
                                class="w-full px-5 py-3 text-white font-semibold rounded-lg transition-all shadow-lg">
                            Kembalikan Dana
                        </button>
                    </div>

                </div>

                <!-- Payment Method Modal -->
                <div x-show="showMethodModal" 
                     x-transition
                     @click.self="showMethodModal = false"
                     class="fixed inset-0 bg-black bg-opacity-70 z-50 flex items-center justify-center p-4">
                    
                    <div @click.away="showMethodModal = false"
                         class="bg-slate-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border-2 border-slate-700">
                        
                        <div class="sticky top-0 bg-slate-800 border-b-2 border-slate-700 p-6 flex justify-between items-center z-10">
                            <h3 class="text-2xl font-bold text-white">Pilih Metode Pengembalian</h3>
                            <button @click="showMethodModal = false" class="text-slate-400 hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            @foreach($refundMethods as $method => $data)
                            <div class="border-2 border-slate-700 rounded-xl p-4 bg-slate-900">
                                <h4 class="font-bold text-lg text-white mb-3">{{ $data['label'] }}</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($data['options'] as $key => $option)
                                    <button type="button"
                                            @click="selectMethod('{{ $method }}', '{{ $key }}')"
                                            class="p-4 border-2 rounded-lg text-left transition-all"
                                            :class="selectedSubMethod === '{{ $key }}' ? 'border-yellow-500 bg-yellow-500/10' : 'border-slate-700 hover:border-yellow-500/50'">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-semibold text-white">{{ $option['name'] }}</p>
                                            </div>
                                            <svg x-show="selectedSubMethod === '{{ $key }}'" class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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

            </form>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            
            <!-- User Info -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-white mb-4 pb-3 border-b border-slate-700">Informasi User</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Nama</p>
                        <p class="font-semibold text-white">{{ $cancelBooking->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Email</p>
                        <p class="text-sm text-slate-300">{{ $cancelBooking->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Telepon</p>
                        <p class="text-sm text-slate-300">{{ $cancelBooking->user->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Cancel Info -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-white mb-4 pb-3 border-b border-slate-700">Detail Pembatalan</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Kamar</p>
                        <p class="font-semibold text-white">{{ $cancelBooking->rent->room->room_number }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-1">DP Dibayar</p>
                        <p class="text-xl font-bold text-yellow-400">Rp {{ number_format($cancelBooking->rent->deposit_paid, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Rekening User</p>
                        <div class="mt-2 p-3 bg-slate-900 rounded-lg border border-slate-700">
                            <p class="text-sm text-slate-300">{{ $cancelBooking->bank_name }}</p>
                            <p class="text-sm text-slate-300">{{ $cancelBooking->account_number }}</p>
                            <p class="text-xs text-slate-400">a/n {{ $cancelBooking->account_holder_name }}</p>
                        </div>
                    </div>
                    @if($cancelBooking->cancel_reason)
                    <div>
                        <p class="text-xs text-slate-400 mb-1">Alasan</p>
                        <p class="text-sm text-slate-300 italic">{{ $cancelBooking->cancel_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
function refundForm() {
    return {
        showMethodModal: false,
        selectedMethod: '{{ old("refund_method") }}',
        selectedSubMethod: '{{ old("refund_sub_method") }}',
        imagePreview: null,
        
        selectMethod(method, subMethod) {
            this.selectedMethod = method;
            this.selectedSubMethod = subMethod;
            this.showMethodModal = false;
        },
        
        getMethodLabel() {
            if (!this.selectedMethod) return '';
            
            const labels = {
                'manual_transfer': 'Transfer Bank',
                'e_wallet': 'E-Wallet'
            };
            
            return (labels[this.selectedMethod] || '') + 
                   (this.selectedSubMethod ? ' - ' + this.selectedSubMethod.toUpperCase() : '');
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
        
        handleSubmit() {
            // Validasi
            const form = this.$el.closest('form');
            const amount = form.querySelector('[name=refund_amount]').value;
            const account = form.querySelector('[name=refund_account_number]').value;
            const proof = form.querySelector('[name=refund_proof]').files[0];
            
            // Cek field wajib
            if (!this.selectedMethod) {
                Alpine.store('modal').alert(
                    'Silakan pilih metode pengembalian terlebih dahulu',
                    'Metode Belum Dipilih',
                    'warning'
                );
                return;
            }
            
            if (!account || !amount || !proof) {
                Alpine.store('modal').alert(
                    'Mohon lengkapi semua field yang wajib diisi',
                    'Data Tidak Lengkap',
                    'warning'
                );
                return;
            }
            
            // Show confirmation modal
            Alpine.store('modal').open({
                type: 'warning',
                title: 'Konfirmasi Pengembalian Dana',
                message: `Yakin proses refund sebesar Rp ${parseInt(amount).toLocaleString('id-ID')} via ${this.getMethodLabel()}? Pastikan semua data sudah benar.`,
                confirmText: 'Ya, Kembalikan Dana',
                showCancel: true,
                onConfirm: () => {
                    form.submit();
                }
            });
        }
    }
}
</script>
@endpush