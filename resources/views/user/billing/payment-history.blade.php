@extends('layouts.user')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Pembayaran</h1>
            <p class="text-sm text-gray-600 mt-1">Semua riwayat transaksi pembayaran Anda</p>
        </div>
        <a href="{{ route('user.billing.index') }}" 
           class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium">
            Kembali ke Tagihan
        </a>
    </div>

    <!-- Payment History List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:border-yellow-400 transition overflow-hidden">
        <div class="divide-y divide-gray-200">
            @forelse($payments as $payment)
            <div class="p-6 hover:bg-gray-50 transition border-l-4 border-transparent hover:border-yellow-400">
                <div class="flex items-start justify-between gap-6">
                    <div class="flex-1">
                        <!-- Payment Header -->
                        <div class="flex items-center gap-3 mb-3">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $payment->status_badge }}">
                                {{ $payment->status_label }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $payment->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        <!-- Billing Info -->
                        <div class="mb-3">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">
                                {{ $payment->billing->formatted_period }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                Kamar {{ $payment->billing->room->room_number }} • 
                                Tagihan: Rp {{ number_format($payment->billing->total_amount, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Payment Details Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Jumlah Dibayar</p>
                                <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Metode</p>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->payment_method_label }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Tanggal Bayar</p>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->payment_date->format('d M Y') }}</p>
                            </div>
                            @if($payment->verified_at)
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Diverifikasi</p>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->verified_at->format('d M Y') }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Status Messages -->
                        @if($payment->status === 'pending')
                        <div class="p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                            <p class="text-sm text-yellow-800">
                                <span class="font-semibold">Menunggu Verifikasi</span><br>
                                Pembayaran Anda sedang diproses oleh admin.
                            </p>
                        </div>
                        @elseif($payment->status === 'confirmed')
                        <div class="p-3 bg-green-50 border-l-4 border-green-500 rounded">
                            <p class="text-sm text-green-800">
                                <span class="font-semibold">✓ Pembayaran Dikonfirmasi</span><br>
                                Terima kasih, pembayaran Anda telah diverifikasi.
                            </p>
                        </div>
                        @elseif($payment->status === 'rejected')
                        <div class="p-3 bg-red-50 border-l-4 border-red-500 rounded">
                            <p class="text-sm text-red-800">
                                <span class="font-semibold">✗ Pembayaran Ditolak</span><br>
                                {{ $payment->rejection_reason }}
                            </p>
                        </div>
                        @endif

                        <!-- Notes -->
                        @if($payment->notes)
                        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Catatan:</p>
                            <p class="text-sm text-gray-800">{{ $payment->notes }}</p>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('user.billing.show', $payment->billing) }}" 
                               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium">
                                Lihat Tagihan
                            </a>
                            @if($payment->payment_proof)
                            <a href="{{ Storage::url($payment->payment_proof) }}" 
                               target="_blank"
                               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium">
                                Lihat Bukti
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Proof Thumbnail -->
                    @if($payment->payment_proof)
                    <div class="flex-shrink-0">
                        <a href="{{ Storage::url($payment->payment_proof) }}" 
                           target="_blank"
                           class="block w-32 h-32 border-2 border-gray-300 rounded-lg overflow-hidden hover:border-yellow-400 transition">
                            <img src="{{ Storage::url($payment->payment_proof) }}" 
                                 alt="Bukti Pembayaran" 
                                 class="w-full h-full object-cover">
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-12 text-center border border-gray-200 rounded-lg hover:border-yellow-400 transition">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat</h3>
                <p class="text-sm text-gray-600 mb-4">Anda belum memiliki riwayat pembayaran</p>
                <a href="{{ route('user.billing.index') }}" 
                   class="inline-block px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    Lihat Tagihan
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div class="p-6 border-t border-gray-200 bg-gray-50 hover:border-yellow-400 transition">
            {{ $payments->links() }}
        </div>
        @endif
    </div>

</div>
@endsection