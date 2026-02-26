@extends('layouts.user')

@section('title', 'Status Checkout')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-100">
            Status Checkout
        </h1>

        <a href="{{ route('user.status.index') }}"
           class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600
                  text-white font-semibold rounded-lg hover:opacity-90 transition">
            Kembali ke Status
        </a>
    </div>

    {{-- MODE DETAIL CHECKOUT --}}
    @if($selectedCheckout)
        @include('user.checkout.detail', ['rent' => $selectedCheckout])

    @else

        {{-- LIST CHECKOUT --}}
        @forelse($checkoutGroups as $month => $rents)
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-3 text-slate-500">
                    {{ $month }}
                </h3>
                <div class="space-y-4">
                    @foreach($rents as $rent)
                        @include('user.status.partials.checkout-card', ['rent' => $rent])
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-16 rounded-xl" style="background:#1e293b; border:1px solid #334155;">
                <p class="text-slate-500">Belum ada riwayat checkout</p>
            </div>
        @endforelse

    @endif
</div>
@endsection