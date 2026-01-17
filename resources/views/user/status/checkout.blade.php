@extends('layouts.user')

@section('title', 'Status Checkout')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Status Checkout
        </h1>

        <a href="{{ route('user.status.index') }}"
           class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600
                  text-white font-semibold rounded-lg hover:opacity-90 transition">
            Kembali ke Status
        </a>
    </div>

    {{-- =========================
        MODE DETAIL CHECKOUT
    ========================= --}}
    @if($selectedCheckout)

        {{-- DETAIL CHECKOUT (KODE PANJANG KAMU) --}}
        @include('user.checkout.detail', [
            'rent' => $selectedCheckout
        ])

    @else

        {{-- =========================
            MODE LIST CHECKOUT
        ========================= --}}
        @if($selectedCheckout)

            @include('user.checkout.detail', ['rent' => $selectedCheckout])

        @else

            @forelse($checkoutGroups as $month => $rents)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-3 text-gray-600">
                        {{ $month }}
                    </h3>
                
                    <div class="space-y-4">
                        @foreach($rents as $rent)
                            @include('user.checkout.partials.checkout-card', [
                                'rent' => $rent
                            ])
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-xl shadow border">
                    <p class="text-gray-500">
                        Belum ada riwayat checkout
                    </p>
                </div>
            @endforelse
            
        @endif

    @endif
</div>
@endsection
