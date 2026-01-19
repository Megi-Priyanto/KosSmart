@extends('layouts.superadmin')

@section('title', 'Detail Tagihan')
@section('page-title', 'Detail Tagihan Admin')
@section('page-description', 'Informasi lengkap tagihan operasional admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button -->
    <a href="{{ route('superadmin.billing.index') }}" 
       class="inline-flex items-center text-sm text-slate-400 hover:text-white transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Tagihan
    </a>

    <!-- Billing Info Card -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-700 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white">Tagihan Operasional Admin</h3>
                <p class="text-sm text-slate-400 mt-1">
                    Periode: {{ \Carbon\Carbon::parse($billing->billing_period . '-01')->format('F Y') }}
                </p>
            </div>
            @php
                $statusColor = $billing->status_color;
            @endphp
            <span class="px-4 py-2 text-sm font-medium bg-{{ $statusColor }}-500/20 text-{{ $statusColor }}-400 rounded-full">
                {{ $billing->status_label }}
            </span>
        </div>

        <div class="p-6 space-y-4">
            <!-- Tempat Kos & Admin Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-4 border-b border-slate-700">
                <div>
                    <span class="text-sm text-slate-400 block mb-2">Tempat Kos</span>
                    <p class="text-white font-medium">{{ $billing->tempatKos->nama_kos }}</p>
                    <p class="text-xs text-slate-400 mt-1">
                        {{ $billing->tempatKos->alamat }}, {{ $billing->tempatKos->kota }}
                    </p>
                </div>
                <div>
                    <span class="text-sm text-slate-400 block mb-2">Admin</span>
                    <p class="text-white font-medium">{{ $billing->admin->name }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $billing->admin->email }}</p>
                    <p class="text-xs text-slate-400">{{ $billing->admin->phone }}</p>
                </div>
            </div>

            <!-- Amount -->
            <div class="flex items-center justify-between py-3 border-b border-slate-700">
                <span class="text-sm text-slate-400">Jumlah Tagihan</span>
                <span class="text-2xl font-bold text-yellow-400">
                    Rp {{ number_format($billing->amount, 0, ',', '.') }}
                </span>
            </div>

            <!-- Due Date -->
            <div class="flex items-center justify-between py-3 border-b border-slate-700">
                <span class="text-sm text-slate-400">Jatuh Tempo</span>
                <div class="text-right">
                    <span class="text-sm text-white">{{ $billing->due_date->format('d F Y') }}</span>
                    @if($billing->isOverdue())
                        <p class="text-xs text-red-400 mt-1">
                            Terlambat {{ $billing->due_date->diffForHumans() }}
                        </p>
                    @elseif($billing->status === 'unpaid')
                        <p class="text-xs text-slate-400 mt-1">
                            {{ $billing->due_date->diffForHumans() }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Description -->
            @if($billing->description)
            <div class="flex items-start justify-between py-3 border-b border-slate-700">
                <span class="text-sm text-slate-400">Keterangan</span>
                <span class="text-sm text-white text-right max-w-md">{{ $billing->description }}</span>
            </div>
            @endif

            <!-- Created At -->
            <div class="flex items-center justify-between py-3 border-b border-slate-700">
                <span class="text-sm text-slate-400">Dibuat Pada</span>
                <span class="text-sm text-white">{{ $billing->created_at->format('d F Y, H:i') }}</span>
            </div>

            <!-- Paid At -->
            @if($billing->paid_at)
            <div class="flex items-center justify-between py-3 border-b border-slate-700">
                <span class="text-sm text-slate-400">Dibayar Pada</span>
                <span class="text-sm text-white">{{ $billing->paid_at->format('d F Y, H:i') }}</span>
            </div>
            @endif

            <!-- Payment Proof -->
            @if($billing->payment_proof)
            <div class="py-3">
                <span class="block text-sm text-slate-400 mb-3">Bukti Pembayaran</span>
                <a href="{{ Storage::url($billing->payment_proof) }}" target="_blank">
                    <img src="{{ Storage::url($billing->payment_proof) }}" 
                         alt="Bukti Pembayaran"
                         class="w-full max-w-md rounded-lg border border-slate-700 hover:border-yellow-500 transition cursor-pointer">
                </a>
                <p class="text-xs text-slate-400 mt-2">Klik gambar untuk memperbesar</p>
            </div>
            @endif

            <!-- Payment Notes -->
            @if($billing->payment_notes)
            <div class="flex items-start justify-between py-3">
                <span class="text-sm text-slate-400">Catatan Pembayaran</span>
                <span class="text-sm text-white text-right max-w-md">{{ $billing->payment_notes }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    @if($billing->status === 'pending')
    <div class="bg-slate-800 rounded-xl border border-green-500/40 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-white font-medium">Pembayaran Diterima</h4>
                    <p class="text-sm text-slate-400">Admin telah mengirim bukti pembayaran</p>
                </div>
            </div>
            <form action="{{ route('superadmin.billing.verify', $billing) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Verifikasi Pembayaran
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Billing -->
    <div class="flex justify-between items-center">
        <form action="{{ route('superadmin.billing.destroy', $billing) }}" method="POST" 
              onsubmit="return confirm('Yakin ingin menghapus tagihan ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="px-6 py-3 bg-red-600/20 hover:bg-red-600/30 text-red-400 rounded-lg font-medium transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Hapus Tagihan
            </button>
        </form>
    </div>

</div>
@endsection