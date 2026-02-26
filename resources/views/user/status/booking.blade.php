@extends('layouts.user')

@section('title', 'Status Booking')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-100">
            Status Booking
        </h1>

        <a href="{{ route('user.status.index') }}"
           class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600
                  text-white font-semibold rounded-lg hover:opacity-90 transition">
            Kembali ke Status
        </a>
    </div>

    {{-- MODE DETAIL BOOKING --}}
    @if($selectedBooking)
        @include('user.booking.status', ['rent' => $selectedBooking])

    @else

        {{-- BOOKING PENDING / AKTIF --}}
        @if($currentBooking)
            <div class="mb-10">
                <h2 class="text-xl font-bold mb-4 text-gray-100">
                    Status Booking Saat Ini
                </h2>
                @include('user.status.partials.booking-card', ['rent' => $currentBooking])
            </div>
        @endif

        {{-- HISTORY BOOKING --}}
        <div>
            @if($historyBookings->isNotEmpty())
                @foreach($historyBookings as $month => $rents)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-3 text-slate-500">
                            {{ $month }}
                        </h3>
                        <div class="space-y-4">
                            @foreach($rents as $rent)
                                @include('user.status.partials.booking-card', ['rent' => $rent])
                            @endforeach
                        </div>
                    </div>
                @endforeach

            @elseif(!$currentBooking)
                <div class="text-center py-16 rounded-xl" style="background:#1e293b; border:1px solid #334155;">
                    <p class="text-slate-500">Belum ada riwayat booking</p>
                </div>
            @endif
        </div>

    @endif
</div>
@endsection