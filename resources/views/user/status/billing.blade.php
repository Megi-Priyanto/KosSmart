@extends('layouts.user')

@section('title', 'Status Tagihan')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-100">
            Status Tagihan
        </h1>

        <a href="{{ route('user.status.index') }}"
           class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600
                  text-white font-semibold rounded-lg hover:opacity-90 transition">
            Kembali ke Status
        </a>
    </div>

    {{-- EMPTY STATE --}}
    @if($billings->isEmpty())
        <div class="text-center py-16 rounded-xl" style="background:#1e293b; border:1px solid #334155;">
            <p class="text-slate-500">Belum ada riwayat tagihan</p>
        </div>
    @endif

    {{-- LIST TAGIHAN --}}
    @foreach($billings as $month => $items)
        <h3 class="text-lg font-semibold mb-4 mt-10 text-gray-200">
            Tagihan {{ $month }}
        </h3>
        <div class="space-y-4">
            @foreach($items as $billing)
                @include('user.status.partials.billing-card', ['billing' => $billing])
            @endforeach
        </div>
    @endforeach

</div>
@endsection