@extends('layouts.user')

@section('title', 'Status Booking')

@section('content')
<div class="space-y-6">
    
    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Status Booking
        </h1>

        <a href="{{ route('user.status.index') }}"
           class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600
                  text-white font-semibold rounded-lg hover:opacity-90 transition">
            Kembali ke Status
        </a>
    </div>

    {{-- =========================
        MODE DETAIL BOOKING
    ========================= --}}
    @if($selectedBooking)

        {{-- INCLUDE VIEW DETAIL PENUH --}}
        @include('user.booking.status', [
            'rent' => $selectedBooking
        ])

    @else

        {{-- =========================
            MODE LIST BOOKING
        ========================= --}}

        {{-- BOOKING PENDING --}}
        @if($currentBooking)
            <div class="mb-10">
                <h2 class="text-xl font-bold mb-4 text-gray-800">
                    Status Booking Saat Ini
                </h2>

                @include('user.status.partials.booking-card', [
                    'rent' => $currentBooking
                ])
            </div>
        @endif

        {{-- HISTORY BOOKING --}}
        <div>
        
            @forelse($historyBookings as $month => $rents)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-3 text-gray-600">
                        {{ $month }}
                    </h3>
                
                    <div class="space-y-4">
                        @foreach($rents as $rent)
                            @include('user.status.partials.booking-card', [
                                'rent' => $rent
                            ])
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-xl shadow border">
                    <p class="text-gray-500">
                        Belum ada riwayat booking
                    </p>
                </div>
            @endforelse
        </div>

    @endif
</div>
@endsection
