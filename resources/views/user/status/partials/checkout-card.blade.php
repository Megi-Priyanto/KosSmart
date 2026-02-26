@php
    $isPending  = $rent->status === 'checkout_requested';
    $isApproved = $rent->status === 'completed';
    $isRejected = $rent->status === 'checkout_rejected';
@endphp

<div class="bg-[#1e293b] rounded-xl shadow p-6 border
    {{ $isPending ? 'border-yellow-400' : '' }}
    {{ $isApproved ? 'border-green-500' : '' }}
    {{ $isRejected ? 'border-red-400' : '' }}
">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="font-semibold text-gray-100">
                Kamar {{ $rent->room->room_number ?? '-' }}
            </h3>
            <p class="text-sm text-gray-500">
                {{ $rent->updated_at->format('d M Y, H:i') }}
            </p>
        </div>

        @if($isPending)
            <span class="px-3 py-1 text-sm bg-yellow-900/30 text-yellow-400 rounded-full font-medium">
                Pending Checkout
            </span>
        @elseif($isApproved)
            <span class="px-3 py-1 text-sm bg-green-900/40 text-green-400 rounded-full font-medium">
                ✓ Checkout Berhasil
            </span>
        @elseif($isRejected)
            <span class="px-3 py-1 text-sm bg-red-900/40 text-red-400 rounded-full font-medium">
                ✗ Checkout Ditolak
            </span>
        @endif
    </div>

    {{-- Info Tambahan --}}
    <div class="mb-4 text-sm text-gray-600">
        @if($isApproved && $rent->end_date)
            <p class="flex items-center">
                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Checkout disetujui pada {{ $rent->end_date->format('d M Y, H:i') }}
            </p>
        @elseif($isPending)
            <p class="flex items-center">
                <svg class="w-4 h-4 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Menunggu persetujuan admin
            </p>
        @endif
    </div>

    <div class="flex justify-end">
        <a href="{{ route('user.status.checkout', ['rent' => $rent->id]) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
           {{ $isPending ? 'bg-yellow-900/30 text-yellow-400 hover:bg-yellow-200' : '' }}
           {{ $isApproved ? 'bg-green-900/40 text-green-400 hover:bg-green-200' : '' }}
           {{ $isRejected ? 'bg-red-900/40 text-red-400 hover:bg-red-900/50' : '' }}">
            Lihat Detail
        </a>
    </div>
</div>