@php
    $isUnpaid   = $billing->dynamic_status === 'unpaid';
    $isPending  = $billing->dynamic_status === 'pending';
    $isPaid     = $billing->dynamic_status === 'paid';
    $isRejected = $billing->dynamic_status === 'rejected';
    $isOverdue  = $billing->dynamic_status === 'overdue';
@endphp

<div class="bg-[#1e293b] rounded-xl shadow p-6 border
    {{ $isUnpaid ? 'border-red-400' : '' }}
    {{ $isPending ? 'border-yellow-400' : '' }}
    {{ $isPaid ? 'border-green-500' : '' }}
    {{ $isRejected ? 'border-red-600' : '' }}
">

    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold text-gray-100">
                Kamar {{ $billing->room->room_number ?? '-' }}
            </h3>
            <p class="text-sm text-gray-500">
                {{ $billing->formatted_period }}
            </p>
        </div>

        {{-- BADGE --}}
        @if($isUnpaid)
            <span class="px-3 py-1 text-sm bg-red-900/40 text-red-400 rounded-full">
                Belum Dibayar
            </span>
        @elseif($isPending)
            <span class="px-3 py-1 text-sm bg-yellow-900/30 text-yellow-400 rounded-full">
                Menunggu Verifikasi
            </span>
        @elseif($isRejected)
            <span class="px-3 py-1 text-sm bg-red-900/50 text-red-300 rounded-full font-semibold">
                Pembayaran Ditolak
            </span>
        @elseif($isPaid)
            <span class="px-3 py-1 text-sm bg-green-900/40 text-green-400 rounded-full">
                Pembayaran Berhasil
            </span>
        @endif
    </div>

    <div class="text-sm text-gray-600 mb-4">
        Total: <strong>Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</strong>
    </div>

    <div class="flex justify-end">
        <a href="{{ route('user.billing.show', [
                'billing' => $billing->id,
                'from' => 'status'
            ]) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium
                {{ $isUnpaid ? 'bg-red-900/40 text-red-400 hover:bg-red-900/50' : '' }}
                {{ $isPending ? 'bg-yellow-900/30 text-yellow-400 hover:bg-yellow-200' : '' }}
                {{ $isRejected ? 'bg-red-900/50 text-red-300 hover:bg-red-300' : '' }}
                {{ $isPaid ? 'bg-green-900/40 text-green-400 hover:bg-green-200' : '' }}">
            Lihat Detail
        </a>
    </div>
</div>