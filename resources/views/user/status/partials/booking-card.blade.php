@php
    // NORMALISASI STATUS (USER SIDE)
    $isPending  = $rent->status === 'pending';
    $isActive   = in_array($rent->status, ['approved', 'active']);
    $isFinished = in_array($rent->status, ['checked_out', 'expired']);
    $isCanceled = $rent->status === 'cancelled';
@endphp

<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6 border
    {{ $isPending ? 'border-yellow-400' : '' }}
    {{ $isActive ? 'border-green-500' : '' }}
    {{ $isFinished ? 'border-gray-400' : '' }}
    {{ $isCanceled ? 'border-red-400' : '' }}
">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                {{ $rent->room->room_number ?? '-' }}
            </h3>
            <p class="text-sm text-gray-500">
                {{ $rent->room->kos->name ?? '-' }}
            </p>
        </div>

        <!-- STATUS BADGE -->
        @if ($isPending)
            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-700">
                Menunggu
            </span>
        @elseif ($isActive)
            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-700">
                Booking Aktif
            </span>
        @elseif ($isFinished)
            <span class="px-3 py-1 text-sm rounded-full bg-gray-200 text-gray-700">
                Booking Selesai
            </span>
        @elseif ($isCanceled)
            <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-700">
                Booking Dibatalkan
            </span>
        @endif
    </div>

    <!-- DETAIL -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300">
        <div>
            <p class="text-gray-500">Tanggal Booking</p>
            <p class="font-medium">
                {{ $rent->created_at->format('d F Y') }}
            </p>
        </div>

        <div>
            <p class="text-gray-500">Periode Sewa</p>
            <p class="font-medium">
                {{ \Carbon\Carbon::parse($rent->start_date)->format('d M Y') }}
                -
                {{ \Carbon\Carbon::parse($rent->end_date)->format('d M Y') }}
            </p>
        </div>
    </div>

    <!-- ACTION -->
    <div class="mt-6 flex justify-end">
        <a href="{{ route('user.status.booking', ['rent' => $rent->id]) }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition
                {{ $isPending ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : '' }}
                {{ $isActive ? 'bg-green-100 text-green-800 hover:bg-green-200' : '' }}
                {{ $isFinished ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : '' }}
                {{ $isCanceled ? 'bg-red-100 text-red-700 hover:bg-red-200' : '' }}">
            Lihat Detail
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

</div>
