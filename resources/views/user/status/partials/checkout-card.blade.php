@php
    $isPending  = $rent->status === 'checkout_requested';
    $isApproved = $rent->status === 'checkout_approved';
    $isRejected = $rent->status === 'checkout_rejected';
@endphp

<div class="bg-white rounded-xl shadow p-6 border
    {{ $isPending ? 'border-yellow-400' : '' }}
    {{ $isApproved ? 'border-green-500' : '' }}
    {{ $isRejected ? 'border-red-400' : '' }}
">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold text-gray-800">
                Kamar {{ $rent->room->room_number ?? '-' }}
            </h3>
            <p class="text-sm text-gray-500">
                {{ $rent->updated_at->format('d M Y') }}
            </p>
        </div>

        @if($isPending)
            <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-700 rounded-full">
                Pending Checkout
            </span>
        @elseif($isApproved)
            <span class="px-3 py-1 text-sm bg-green-100 text-green-700 rounded-full">
                Checkout Berhasil
            </span>
        @elseif($isRejected)
            <span class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded-full">
                Checkout Ditolak
            </span>
        @endif
    </div>

    <div class="flex justify-end">
        <a href="{{ route('user.status.pending', ['rent' => $rent->id]) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium
           {{ $isPending ? 'bg-yellow-100 text-yellow-700' : '' }}
           {{ $isApproved ? 'bg-green-100 text-green-700' : '' }}
           {{ $isRejected ? 'bg-red-100 text-red-700' : '' }}">
            Lihat Detail
        </a>
    </div>
</div>
