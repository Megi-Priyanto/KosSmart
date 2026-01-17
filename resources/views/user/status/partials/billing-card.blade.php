@php
    $isUnpaid   = $billing->status === 'unpaid';
    $isPending  = $billing->status === 'pending';
    $isPaid     = $billing->status === 'paid';
@endphp

<div class="bg-white rounded-xl shadow p-6 border
    {{ $isUnpaid ? 'border-red-400' : '' }}
    {{ $isPending ? 'border-yellow-400' : '' }}
    {{ $isPaid ? 'border-green-500' : '' }}
">

    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold text-gray-800">
                Kamar {{ $billing->room->room_number ?? '-' }}
            </h3>
            <p class="text-sm text-gray-500">
                {{ $billing->formatted_period }}
            </p>
        </div>

        {{-- BADGE --}}
        @if($isUnpaid)
            <span class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded-full">
                Belum Dibayar
            </span>
        @elseif($isPending)
            <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 rounded-full">
                Menunggu Verifikasi
            </span>
        @elseif($isPaid)
            <span class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full">
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
                {{ $isUnpaid ? 'bg-red-100 text-red-700 hover:bg-red-200' : '' }}
                {{ $isPending ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : '' }}
                {{ $isPaid ? 'bg-green-100 text-green-700 hover:bg-green-200' : '' }}">
            Lihat Detail
        </a>
    </div>
</div>
