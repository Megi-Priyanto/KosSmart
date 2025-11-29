@extends('layouts.admin')

@section('title', 'Detail Tagihan')
@section('page-title', 'Detail Tagihan')
@section('page-description', 'Informasi lengkap tagihan penghuni')

@section('content')
<div class="space-y-6">

    <!-- Back Button -->
    <a href="{{ route('admin.billing.index') }}" 
       class="inline-flex items-center text-sm text-purple-600 hover:text-purple-800">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Daftar Tagihan
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Billing Info Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">{{ $billing->formatted_period }}</h3>
                        <p class="text-sm text-gray-600 mt-1">ID: #{{ $billing->id }}</p>
                    </div>
                    <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $billing->status_badge }}">
                        {{ $billing->status_label }}
                    </span>
                </div>

                <!-- Tenant & Room Info -->
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Informasi Penyewa</h4>
                            <div class="space-y-2">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $billing->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $billing->user->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">{{ $billing->user->phone ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Informasi Kamar</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <p class="text-sm text-gray-900">Kamar <span class="font-semibold">{{ $billing->room->room_number }}</span></p>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">{{ ucfirst($billing->room->type) }} • {{ $billing->room->floor }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cost Breakdown -->
                <div class="p-6">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Rincian Biaya</h4>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-600">Biaya Sewa</span>
                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($billing->rent_amount, 0, ',', '.') }}</span>
                        </div>

                        @if($billing->electricity_cost > 0)
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-600">Listrik</span>
                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($billing->electricity_cost, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        @if($billing->water_cost > 0)
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-600">Air</span>
                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($billing->water_cost, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        @if($billing->maintenance_cost > 0)
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-600">Maintenance</span>
                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($billing->maintenance_cost, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        @if($billing->other_costs > 0)
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-600">
                                Biaya Lain-lain
                                @if($billing->other_costs_description)
                                    <span class="block text-xs text-gray-500">{{ $billing->other_costs_description }}</span>
                                @endif
                            </span>
                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($billing->other_costs, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center py-2 border-t border-gray-200">
                            <span class="text-sm font-medium text-gray-700">Subtotal</span>
                            <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($billing->subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if($billing->discount > 0)
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-600">Diskon</span>
                            <span class="text-sm font-semibold text-red-600">- Rp {{ number_format($billing->discount, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center py-3 border-t-2 border-gray-300 bg-purple-50 -mx-6 px-6">
                            <span class="text-base font-bold text-gray-900">Total Tagihan</span>
                            <span class="text-2xl font-bold text-purple-600">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Dates Info -->
                <div class="p-6 bg-gray-50 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tanggal Dibuat</p>
                            <p class="text-sm font-medium text-gray-900">{{ $billing->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Jatuh Tempo</p>
                            <p class="text-sm font-medium text-gray-900">{{ $billing->due_date->format('d M Y') }}</p>
                            @if($billing->is_overdue && $billing->status !== 'paid')
                                <p class="text-xs text-red-600 mt-1">⚠️ Terlambat {{ abs($billing->days_until_due) }} hari</p>
                            @elseif($billing->status !== 'paid')
                                <p class="text-xs text-gray-500 mt-1">{{ $billing->days_until_due }} hari lagi</p>
                            @endif
                        </div>
                        @if($billing->paid_date)
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tanggal Lunas</p>
                            <p class="text-sm font-medium text-green-600">{{ $billing->paid_date->format('d M Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Notes -->
                @if($billing->admin_notes || $billing->user_notes)
                <div class="p-6 border-t border-gray-200">
                    @if($billing->admin_notes)
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-500 mb-2">CATATAN ADMIN:</p>
                        <p class="text-sm text-gray-700">{{ $billing->admin_notes }}</p>
                    </div>
                    @endif
                    
                    @if($billing->user_notes)
                    <div>
                        <p class="text-xs font-semibold text-gray-500 mb-2">CATATAN USER:</p>
                        <p class="text-sm text-gray-700">{{ $billing->user_notes }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Payment History -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Riwayat Pembayaran</h3>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @forelse($billing->payments as $payment)
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $payment->status_badge }}">
                                        {{ $payment->status_label }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $payment->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-600">Jumlah Dibayar</p>
                                        <p class="font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Metode Pembayaran</p>
                                        <p class="font-medium text-gray-900">{{ $payment->payment_method_label }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Tanggal Pembayaran</p>
                                        <p class="font-medium text-gray-900">{{ $payment->payment_date->format('d M Y') }}</p>
                                    </div>
                                    @if($payment->verified_by)
                                    <div>
                                        <p class="text-gray-600">Diverifikasi oleh</p>
                                        <p class="font-medium text-gray-900">{{ $payment->verifier->name }}</p>
                                    </div>
                                    @endif
                                </div>

                                @if($payment->notes)
                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-600 mb-1">Catatan:</p>
                                    <p class="text-sm text-gray-800">{{ $payment->notes }}</p>
                                </div>
                                @endif

                                @if($payment->rejection_reason)
                                <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-500 rounded">
                                    <p class="text-xs font-semibold text-red-700 mb-1">ALASAN PENOLAKAN:</p>
                                    <p class="text-sm text-red-800">{{ $payment->rejection_reason }}</p>
                                </div>
                                @endif
                            </div>

                            @if($payment->payment_proof)
                            <div class="ml-4">
                                <a href="{{ Storage::url($payment->payment_proof) }}" 
                                   target="_blank"
                                   class="block w-32 h-32 border-2 border-gray-300 rounded-lg overflow-hidden hover:border-purple-500 transition-colors">
                                    <img src="{{ Storage::url($payment->payment_proof) }}" 
                                         alt="Bukti Pembayaran" 
                                         class="w-full h-full object-cover">
                                </a>
                                <p class="text-xs text-gray-500 text-center mt-1">Bukti Transfer</p>
                            </div>
                            @endif
                        </div>

                        <!-- Verification Actions -->
                        @if($payment->status === 'pending')
                        <div class="flex gap-2 mt-4 pt-4 border-t border-gray-200">
                            <form method="POST" action="{{ route('admin.billing.payment.verify', $payment) }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="action" value="confirm">
                                <button type="submit" 
                                        onclick="return confirm('Konfirmasi pembayaran ini?')"
                                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                    ✓ Konfirmasi Pembayaran
                                </button>
                            </form>

                            <button type="button"
                                    onclick="openRejectModal({{ $payment->id }})"
                                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                ✗ Tolak Pembayaran
                            </button>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Belum ada riwayat pembayaran</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                
                <div class="space-y-3">
                    @if($billing->status !== 'paid')
                    <a href="{{ route('admin.billing.edit', $billing) }}" 
                       class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        Edit Tagihan
                    </a>

                    <form method="POST" action="{{ route('admin.billing.mark-paid', $billing) }}">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Tandai tagihan ini sebagai lunas?')"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            Tandai Lunas
                        </button>
                    </form>
                    @endif

                    <form method="POST" action="{{ route('admin.billing.destroy', $billing) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Yakin ingin menghapus tagihan ini?')"
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium"
                                {{ $billing->status === 'paid' ? 'disabled' : '' }}>
                            Hapus Tagihan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline Status</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Tagihan Dibuat</p>
                            <p class="text-xs text-gray-500">{{ $billing->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if($billing->status === 'paid')
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-600">Pembayaran Lunas</p>
                            <p class="text-xs text-gray-500">{{ $billing->paid_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Reject Payment Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Tolak Pembayaran</h3>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="rejectForm" method="POST">
            @csrf
            <input type="hidden" name="action" value="reject">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
                <textarea name="rejection_reason" 
                          required 
                          rows="4" 
                          placeholder="Jelaskan alasan penolakan pembayaran..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" 
                        onclick="closeRejectModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Tolak Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openRejectModal(paymentId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = `/admin/billing/payment/${paymentId}/verify`;
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endpush

@endsection