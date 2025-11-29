@extends('layouts.user')

@section('title', 'Detail Tagihan')

@section('content')
<div class="space-y-6">

    <!-- Back Button -->
    <a href="{{ route('user.billing.index') }}" 
       class="inline-flex items-center text-sm text-purple-600 hover:text-purple-800">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Daftar Tagihan
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Billing Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200 {{ $billing->status === 'paid' ? 'bg-green-50' : ($billing->is_overdue ? 'bg-red-50' : 'bg-purple-50') }}">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $billing->formatted_period }}</h2>
                        <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $billing->status_badge }}">
                            {{ $billing->status_label }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">Tagihan untuk Kamar {{ $billing->room->room_number }}</p>
                </div>

                <!-- Cost Breakdown -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Rincian Biaya</h3>
                    
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

                        <div class="flex justify-between items-center py-4 border-t-2 border-gray-300 bg-purple-50 -mx-6 px-6">
                            <span class="text-lg font-bold text-gray-900">Total Tagihan</span>
                            <span class="text-3xl font-bold text-purple-600">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="p-6 bg-gray-50 border-t border-gray-200">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tanggal Dibuat</p>
                            <p class="text-sm font-medium text-gray-900">{{ $billing->created_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Jatuh Tempo</p>
                            <p class="text-sm font-medium text-gray-900">{{ $billing->due_date->format('d M Y') }}</p>
                            @if($billing->is_overdue && $billing->status !== 'paid')
                                <p class="text-xs text-red-600 mt-1 font-semibold">⚠️ Terlambat {{ abs($billing->days_until_due) }} hari</p>
                            @elseif($billing->status !== 'paid')
                                <p class="text-xs text-gray-500 mt-1">{{ $billing->days_until_due }} hari lagi</p>
                            @endif
                        </div>
                        @if($billing->paid_date)
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500 mb-1">Tanggal Lunas</p>
                            <p class="text-sm font-medium text-green-600">{{ $billing->paid_date->format('d M Y, H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                @if($billing->admin_notes)
                <div class="p-6 border-t border-gray-200">
                    <p class="text-xs font-semibold text-gray-500 mb-2">CATATAN DARI ADMIN:</p>
                    <p class="text-sm text-gray-700">{{ $billing->admin_notes }}</p>
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
                                        <p class="text-gray-600">Metode</p>
                                        <p class="font-medium text-gray-900">{{ $payment->payment_method_label }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Tanggal Pembayaran</p>
                                        <p class="font-medium text-gray-900">{{ $payment->payment_date->format('d M Y') }}</p>
                                    </div>
                                </div>

                                @if($payment->notes)
                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-600 mb-1">Catatan:</p>
                                    <p class="text-sm text-gray-800">{{ $payment->notes }}</p>
                                </div>
                                @endif

                                @if($payment->rejection_reason)
                                <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-500 rounded">
                                    <p class="text-xs font-semibold text-red-700 mb-1">PEMBAYARAN DITOLAK:</p>
                                    <p class="text-sm text-red-800">{{ $payment->rejection_reason }}</p>
                                </div>
                                @endif

                                @if($payment->status === 'pending')
                                <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                                    <p class="text-sm text-yellow-800">
                                        <span class="font-semibold">⏳ Menunggu Verifikasi</span><br>
                                        Pembayaran Anda sedang diproses oleh admin. Harap tunggu konfirmasi.
                                    </p>
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
                                <p class="text-xs text-gray-500 text-center mt-1">Klik untuk memperbesar</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Belum ada pembayaran</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            
            <!-- Payment Actions -->
            @if($billing->status !== 'paid')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bayar Tagihan</h3>
                
                <div class="space-y-3">
                    @if($billing->is_overdue)
                    <div class="p-3 bg-red-50 border-l-4 border-red-500 rounded mb-4">
                        <p class="text-sm text-red-800">
                            <span class="font-semibold">⚠️ Tagihan Terlambat</span><br>
                            Segera lakukan pembayaran untuk menghindari denda.
                        </p>
                    </div>
                    @endif

                    <a href="{{ route('user.billing.pay', $billing) }}" 
                       class="block w-full px-4 py-3 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 transition-colors font-semibold">
                        💳 Bayar Sekarang
                    </a>

                    <div class="text-xs text-gray-600 text-center">
                        Upload bukti transfer untuk verifikasi pembayaran
                    </div>
                </div>
            </div>
            @else
            <div class="bg-green-50 border-2 border-green-500 rounded-lg p-6">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-green-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-green-800 mb-2">Tagihan Lunas</h3>
                    <p class="text-sm text-green-700">Terima kasih atas pembayaran Anda</p>
                </div>
            </div>
            @endif

            <!-- Payment Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h4 class="text-sm font-semibold text-blue-900 mb-3">ℹ️ Informasi Pembayaran</h4>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Upload bukti transfer yang jelas
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Pastikan nominal sesuai
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Verifikasi dalam 1x24 jam
                    </li>
                </ul>
            </div>

        </div>
    </div>

</div>
@endsection