@extends('layouts.admin')

@section('title', 'Detail Tagihan')
@section('page-title', 'Detail Tagihan')
@section('page-description', 'Informasi lengkap tagihan penghuni')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.billing.index') }}" 
           class="px-5 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
            Kembali ke Daftar Tagihan
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Billing Info Card -->
            <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-700 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-white">{{ $billing->formatted_period }}</h3>
                        <p class="text-sm text-slate-400 mt-1">ID: #{{ $billing->id }}</p>
                    </div>
                    <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $billing->status_badge }}">
                        {{ $billing->status_label }}
                    </span>
                </div>

                <!-- Tenant & Room Info -->
                <div class="p-6 border-b border-slate-700 bg-slate-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-bold text-slate-100 mb-4 uppercase tracking-wide">Informasi Penyewa</h4>
                            <div class="space-y-3">
                                <div class="flex items-start bg-slate-800 p-3 rounded-lg border border-slate-700">
                                    <svg class="w-5 h-5 text-slate-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-bold text-white">{{ $billing->user->name }}</p>
                                        <p class="text-xs text-slate-400 mt-1">{{ $billing->user->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center bg-slate-800 p-3 rounded-lg border border-slate-700">
                                    <svg class="w-5 h-5 text-slate-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <p class="text-sm text-slate-300 font-medium">{{ $billing->user->phone ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-slate-100 mb-4 uppercase tracking-wide">Informasi Kamar</h4>
                            <div class="space-y-3">
                                <div class="flex items-center bg-slate-800 p-3 rounded-lg border border-slate-700">
                                    <svg class="w-5 h-5 text-slate-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <p class="text-sm text-white">Kamar <span class="font-bold">{{ $billing->room->room_number }}</span></p>
                                </div>
                                <div class="flex items-center bg-slate-800 p-3 rounded-lg border border-slate-700">
                                    <svg class="w-5 h-5 text-slate-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <p class="text-sm text-slate-300 font-medium">{{ ucfirst($billing->room->type) }} • {{ $billing->room->floor }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cost Breakdown -->
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white flex items-center mb-4">
                        <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        Rincian Biaya
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-3 bg-slate-900 px-4 rounded-lg border border-slate-700">
                            <span class="text-sm text-slate-300 font-medium">Biaya Sewa</span>
                            <span class="text-base font-bold text-white">Rp {{ number_format($billing->rent_amount, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center py-3 border-t-2 border-slate-600 px-4">
                            <span class="text-sm font-bold text-slate-300">Subtotal</span>
                            <span class="text-base font-bold text-white">Rp {{ number_format($billing->subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if($billing->discount > 0)
                        <div class="flex justify-between items-center py-3 bg-red-500/10 px-4 rounded-lg border border-red-500/30">
                            <span class="text-sm text-red-300 font-medium">Diskon</span>
                            <span class="text-base font-bold text-red-400">- Rp {{ number_format($billing->discount, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center py-4 border-t-2 border-yellow-600 bg-gradient-to-r from-yellow-900/20 to-yellow-900/10 px-6 rounded-lg">
                            <span class="text-lg font-bold text-white">Total Tagihan</span>
                            <span class="text-3xl font-bold text-yellow-500">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Dates Info -->
                <div class="p-6 bg-slate-900 border-t border-slate-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-800 p-4 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-2">Tanggal Dibuat</p>
                            <p class="text-sm font-bold text-white">{{ $billing->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="bg-slate-800 p-4 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-2">Jatuh Tempo</p>
                            <p class="text-sm font-bold text-white">{{ $billing->due_date->format('d M Y') }}</p>
                            @if($billing->is_overdue && $billing->status !== 'paid')
                                <p class="text-xs text-red-400 mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Terlambat {{ abs($billing->days_until_due) }} hari
                                </p>
                            @elseif($billing->status !== 'paid')
                                <p class="text-xs text-green-400 mt-2">{{ $billing->days_until_due }} hari lagi</p>
                            @endif
                        </div>
                        @if($billing->paid_date)
                        <div class="bg-green-500/10 p-4 rounded-lg border border-green-500/30 md:col-span-2">
                            <p class="text-xs text-green-400 font-semibold uppercase tracking-wide mb-2">Tanggal Lunas</p>
                            <p class="text-sm font-bold text-green-300">{{ $billing->paid_date->format('d M Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Notes -->
                @if($billing->admin_notes || $billing->user_notes)
                <div class="p-6 border-t border-slate-700 bg-slate-900">
                    @if($billing->admin_notes)
                    <div class="mb-4 bg-blue-500/10 p-4 rounded-lg border border-blue-500/30">
                        <p class="text-xs font-bold text-blue-400 uppercase tracking-wide mb-2">Catatan Admin:</p>
                        <p class="text-sm text-slate-200">{{ $billing->admin_notes }}</p>
                    </div>
                    @endif
                    
                    @if($billing->user_notes)
                    <div class="bg-purple-500/10 p-4 rounded-lg border border-purple-500/30">
                        <p class="text-xs font-bold text-purple-400 uppercase tracking-wide mb-2">Catatan User:</p>
                        <p class="text-sm text-slate-200">{{ $billing->user_notes }}</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Payment History -->
            <div class="bg-slate-800 rounded-lg shadow-xl border border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-700 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                            <svg class="w-5 h-5 text-whitee" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        Riwayat Pembayaran
                    </h3>
            
                    @forelse($billing->payments as $payment)
                    <div class="flex items-center gap-3">
                        <span class="px-4 py-1.5 text-xs font-bold rounded-full {{ $payment->status_badge }}">
                            {{ $payment->status_label }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium">{{ $payment->created_at->format('d M Y, H:i') }}</span>
                    </div>

                </div>
                 
                <div class="divide-y divide-slate-700">
                    <div class="p-6 bg-slate-900">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="bg-slate-800 p-3 rounded-lg border border-slate-700">
                                        <p class="text-slate-400 text-xs font-semibold mb-1">Jumlah Dibayar</p>
                                        <p class="font-bold text-white text-base">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="bg-slate-800 p-3 rounded-lg border border-slate-700">
                                        <p class="text-slate-400 text-xs font-semibold mb-1">Metode Pembayaran</p>
                                        <p class="font-bold text-white">{{ $payment->payment_method_label }}</p>
                                        @if($payment->payment_sub_method)
                                        <p class="text-xs text-slate-400">{{ $payment->payment_sub_method_label }}</p>
                                        @endif
                                    </div>
                                    <div class="bg-slate-800 p-3 rounded-lg border border-slate-700">
                                        <p class="text-slate-400 text-xs font-semibold mb-1">Tanggal Pembayaran</p>
                                        <p class="font-bold text-white">{{ $payment->payment_date->format('d M Y') }}</p>
                                    </div>
                                    @if($payment->verified_by)
                                    <div class="bg-slate-800 p-3 rounded-lg border border-slate-700">
                                        <p class="text-slate-400 text-xs font-semibold mb-1">Diverifikasi oleh</p>
                                        <p class="font-bold text-white">{{ $payment->verifier->name }}</p>
                                    </div>
                                    @endif

                                </div>

                                @if($payment->notes)
                                <div class="mt-4 p-4 bg-slate-800 rounded-lg border border-slate-700">
                                    <p class="text-xs text-slate-400 font-semibold mb-2">Catatan:</p>
                                    <p class="text-sm text-slate-200">{{ $payment->notes }}</p>
                                </div>
                                @endif

                                @if($payment->rejection_reason)
                                <div class="mt-4 p-4 bg-red-500/10 border-l-4 border-red-500 rounded">
                                    <p class="text-xs font-bold text-red-400 uppercase tracking-wide mb-2">Alasan Penolakan:</p>
                                    <p class="text-sm text-red-300">{{ $payment->rejection_reason }}</p>
                                </div>
                                @endif

                            </div>

                            @if($payment->payment_proof)
                            <div class="ml-6">
                                <button onclick="openImageModal('{{ Storage::url($payment->payment_proof) }}')"
                                        class="block w-32 h-32 border-2 border-slate-600 rounded-lg overflow-hidden hover:border-orange-500 transition-all shadow-lg">
                                    <img src="{{ Storage::url($payment->payment_proof) }}" 
                                         alt="Bukti Pembayaran" 
                                         class="w-full h-full object-cover">
                                </button>
                                <p class="text-xs text-slate-400 text-center mt-2 font-medium">Klik untuk zoom</p>
                            </div>
                            @endif
                        </div>

                        <!-- Verification Actions -->
                        @if($payment->status === 'pending')
                        <div class="flex gap-3 mt-6 pt-6 border-t border-slate-700">
                            <form method="POST" action="{{ route('admin.billing.payment.verify', $payment) }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="action" value="confirm">
                                <button type="submit" 
                                        onclick="return confirm('Konfirmasi pembayaran ini?')"
                                        class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all font-bold shadow-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Konfirmasi Pembayaran
                                </button>
                            </form>

                            <button type="button"
                                    onclick="openRejectModal({{ $payment->id }})"
                                    class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-bold shadow-lg flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Tolak Pembayaran
                            </button>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="p-12 text-center bg-slate-900">
                        <svg class="mx-auto h-16 w-16 text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-slate-400 font-medium">Belum ada riwayat pembayaran</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            
            <!-- Quick Actions -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    Aksi Cepat
                </h3>
                
                <div class="space-y-3">
                    @if($billing->status !== 'paid')
                    <a href="{{ route('admin.billing.edit', $billing) }}" 
                       class="block w-full px-5 py-3 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-all font-bold shadow-lg">
                        Edit Tagihan
                    </a>

                    <form method="POST" action="{{ route('admin.billing.mark-paid', $billing) }}">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Tandai tagihan ini sebagai lunas?')"
                                class="w-full px-5 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all font-bold shadow-lg">
                            Tandai Lunas
                        </button>
                    </form>
                    @endif

                    <form method="POST" action="{{ route('admin.billing.destroy', $billing) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Yakin ingin menghapus tagihan ini?')"
                                class="w-full px-5 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-bold shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $billing->status === 'paid' ? 'disabled' : '' }}>
                            Hapus Tagihan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center pb-3 border-b-2 border-slate-700/50">
                    <div class="p-2 bg-gradient-to-br from-slate-700 to-slate-600 rounded-lg border-2 border-slate-500 mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Timeline Status
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-white">Tagihan Dibuat</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $billing->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if($billing->status === 'paid')
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-green-400">Pembayaran Lunas</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $billing->paid_date ? $billing->paid_date->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Image Modal -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-5xl max-h-[90vh] w-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="Bukti Transfer" class="w-full h-auto rounded-lg shadow-2xl">
        <p class="text-center text-white mt-4 text-sm">Klik di luar gambar atau tombol X untuk menutup</p>
    </div>
</div>

<!-- Reject Payment Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-70 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative border w-full max-w-md shadow-2xl rounded-xl bg-slate-800 border-slate-700">
        <div class="flex justify-between items-center p-6 border-b border-slate-700">
            <h3 class="text-xl font-bold text-white">Tolak Pembayaran</h3>
            <button onclick="closeRejectModal()" class="text-slate-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="rejectForm" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="action" value="reject">
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-300 mb-3">Alasan Penolakan <span class="text-red-400"></span></label>
                <textarea name="rejection_reason" 
                          required 
                          rows="4" 
                          placeholder="Jelaskan alasan penolakan pembayaran..."
                          class="w-full px-4 py-3 bg-slate-900 border-2 border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 placeholder-slate-500"></textarea>
            </div>
        
            <div class="flex gap-3">
                <button type="button" 
                        onclick="closeRejectModal()"
                        class="flex-1 px-5 py-3 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition-all font-semibold">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-5 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-bold shadow-lg">
                    Tolak Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openImageModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openRejectModal(paymentId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        form.action = `/admin/billing/payment/${paymentId}/verify`;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImageModal();
            closeRejectModal();
        }
    });
</script>
@endpush

@endsection