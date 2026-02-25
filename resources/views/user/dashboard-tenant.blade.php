@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')

{{-- ══════════════════════════════════════════════════
     CEK BILLING PELUNASAN/BULANAN YANG PERLU DIULAS
     (Muncul di atas segalanya, paling mencolok)
══════════════════════════════════════════════════ --}}
@php
    // Cek apakah ada billing paid yang belum diulas untuk rent ini
    $sudahReview = \App\Models\Ulasan::where('user_id', auth()->id())
        ->where('rent_id', $activeRent->id)
        ->exists();

    $billingPerluDiulas = null;
    if (!$sudahReview) {
        $billingPerluDiulas = \App\Models\Billing::where('user_id', auth()->id())
            ->where('rent_id', $activeRent->id)
            ->where('status', 'paid')
            ->whereIn('tipe', ['pelunasan', 'bulanan'])
            ->latest('updated_at')
            ->first();
    }
@endphp

@if($billingPerluDiulas)
<div x-data="{ showUlasanModal: false }" class="mb-6">

    {{-- Banner Notifikasi --}}
    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl p-5 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-white text-base">Pembayaran Dikonfirmasi! 🎉</h3>
                <p class="text-yellow-100 text-sm mt-0.5">
                    Bagikan pengalaman menginap Anda di
                    <strong class="text-white">{{ $activeRent->room->kosInfo->tempatKos->nama_kos ?? 'kos ini' }}</strong>
                    — ulasan Anda sangat berarti!
                </p>
            </div>
        </div>
        <button @click="showUlasanModal = true"
                class="flex-shrink-0 ml-4 px-5 py-2.5 bg-white text-yellow-600 font-bold text-sm rounded-xl hover:bg-yellow-50 transition shadow-md whitespace-nowrap">
            ⭐ Tulis Ulasan
        </button>
    </div>

    {{-- ── MODAL FORM ULASAN ── --}}
    <div x-show="showUlasanModal"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         @click.self="showUlasanModal = false">

        <div x-show="showUlasanModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden">

            {{-- Header Modal --}}
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-lg">Berikan Ulasan</h3>
                        <p class="text-yellow-100 text-xs">{{ $activeRent->room->kosInfo->tempatKos->nama_kos ?? 'Tempat Kos' }} • Kamar {{ $activeRent->room->room_number }}</p>
                    </div>
                </div>
                <button @click="showUlasanModal = false" class="text-white/70 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <form action="{{ route('user.ulasan.store', $billingPerluDiulas) }}"
                  method="POST"
                  x-data="{
                      rating: 0,
                      hover: 0,
                      labels: ['', 'Sangat Buruk 😞', 'Buruk 😕', 'Cukup 😐', 'Baik 😊', 'Sangat Baik! 🤩']
                  }"
                  class="p-6 space-y-5">
                @csrf

                {{-- Rating Bintang --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Penilaian Keseluruhan <span class="text-red-500">*</span>
                    </label>

                    <div class="flex items-center gap-2">
                        <template x-for="i in 5" :key="i">
                            <button type="button"
                                    @click="rating = i"
                                    @mouseenter="hover = i"
                                    @mouseleave="hover = 0"
                                    class="focus:outline-none transition-transform hover:scale-110 active:scale-95">
                                <svg class="w-10 h-10 transition-all duration-100 drop-shadow-sm"
                                     :class="(hover || rating) >= i
                                         ? 'text-yellow-400 drop-shadow-md'
                                         : 'text-gray-200'"
                                     fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </button>
                        </template>

                        {{-- Hidden input --}}
                        <input type="hidden" name="rating" :value="rating">

                        {{-- Label --}}
                        <span class="ml-2 text-sm font-semibold text-gray-600 min-w-[120px]"
                              x-text="labels[hover || rating] || 'Pilih rating'"></span>
                    </div>

                    {{-- Error --}}
                    @error('rating')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Komentar --}}
                <div>
                    <label for="komentar" class="block text-sm font-semibold text-gray-700 mb-2">
                        Komentar <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea id="komentar"
                              name="komentar"
                              rows="4"
                              maxlength="1000"
                              placeholder="Ceritakan pengalaman menginap Anda... kondisi kamar, kebersihan, pelayanan, dll."
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent resize-none text-sm text-gray-700 placeholder-gray-400">{{ old('komentar') }}</textarea>
                    <p class="text-xs text-gray-400 text-right mt-1">Maks. 1000 karakter</p>

                    @error('komentar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info --}}
                <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3 flex items-start gap-2">
                    <svg class="w-4 h-4 text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-yellow-700">Ulasan Anda bersifat publik dan akan membantu penghuni lain menemukan kos terbaik.</p>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex gap-3 pt-1">
                    <button type="button"
                            @click="showUlasanModal = false"
                            class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition font-medium text-sm">
                        Lewati
                    </button>
                    <button type="submit"
                            :disabled="rating === 0"
                            :class="rating > 0
                                ? 'bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 shadow-lg shadow-yellow-100 cursor-pointer'
                                : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                            class="flex-1 px-4 py-3 text-white rounded-xl font-bold text-sm transition">
                        <span x-text="rating > 0 ? 'Kirim Ulasan ⭐' : 'Pilih Rating Dulu'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════
     NOTIFIKASI CHECKOUT PENDING
══════════════════════════════════════════════════ --}}
@if($activeRent->status === 'checkout_requested')
<div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="flex-1">
            <h3 class="font-bold text-yellow-800 mb-2">Permintaan checkout Anda sedang diproses admin.</h3>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════
     HERO IMAGE
══════════════════════════════════════════════════ --}}
<div class="relative mb-8 rounded-2xl overflow-hidden shadow-lg"
     style="height: clamp(220px, 35vw, 450px);">

    <img src="{{ tenant_dashboard_image() }}"
         alt="Dashboard Tenant"
         class="absolute inset-0 w-full h-full object-cover"
         style="filter: brightness(0.6);">

    <div class="relative z-10 flex flex-col items-center justify-center h-full text-white px-4 text-center">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Selamat Datang</h1>
        <p class="text-sm sm:text-base text-gray-200 max-w-2xl">
            Semoga hari Anda menyenangkan di kos pilihan Anda
        </p>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     SOCIAL MEDIA
══════════════════════════════════════════════════ --}}
<div class="flex justify-center gap-6 mt-6 mb-12">

    <a href="https://www.instagram.com/USERNAME_KAMU" target="_blank"
       class="flex flex-col items-center gap-2 text-sm text-gray-500 hover:text-white transition">
        <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:scale-105 transition"
             style="background: radial-gradient(circle at top left, #c4c4c4, #292c30);">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zm0 1.5A4.25 4.25 0 003.5 7.75v8.5A4.25 4.25 0 007.75 20.5h8.5a4.25 4.25 0 004.25-4.25v-8.5A4.25 4.25 0 0016.25 3.5h-8.5z"/>
                <path d="M12 7a5 5 0 100 10 5 5 0 000-10zm0 1.5a3.5 3.5 0 110 7 3.5 3.5 0 010-7z"/>
                <circle cx="17.5" cy="6.5" r="1"/>
            </svg>
        </div>
        <span>Instagram</span>
    </a>

    <a href="https://twitter.com/USERNAME_KAMU" target="_blank"
       class="flex flex-col items-center gap-2 text-sm text-gray-500 hover:text-white transition">
        <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:scale-105 transition"
             style="background: radial-gradient(circle at top left, #c4c4c4, #292c30);">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23 3a10.9 10.9 0 01-3.14 1.53A4.48 4.48 0 0016 3a4.48 4.48 0 00-4.47 4.48c0 .35.04.7.11 1.03A12.94 12.94 0 013 4s-4 9 5 13a13.07 13.07 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
            </svg>
        </div>
        <span>Twitter</span>
    </a>

    <a href="https://www.youtube.com/@USERNAME_KAMU" target="_blank"
       class="flex flex-col items-center gap-2 text-sm text-gray-500 hover:text-white transition">
        <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:scale-105 transition"
             style="background: radial-gradient(circle at top left, #c4c4c4, #292c30);">
            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.498 6.186a2.958 2.958 0 00-2.08-2.093C19.61 3.5 12 3.5 12 3.5s-7.61 0-9.418.593A2.958 2.958 0 00.502 6.186C0 8.002 0 12 0 12s0 3.998.502 5.814a2.958 2.958 0 002.08 2.093C4.39 20.5 12 20.5 12 20.5s7.61 0 9.418-.593a2.958 2.958 0 002.08-2.093C24 15.998 24 12 24 12s0-3.998-.502-5.814zM9.75 15.02V8.98L15.5 12l-5.75 3.02z"/>
            </svg>
        </div>
        <span>Youtube</span>
    </a>

</div>

{{-- ══════════════════════════════════════════════════
     INFORMASI KAMAR
══════════════════════════════════════════════════ --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:border-yellow-400 transition mb-6">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-lg font-bold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Informasi Kamar
        </h2>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Nomor Kamar</span>
                <span class="font-bold text-gray-800">{{ $activeRent->room->room_number ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Lokasi</span>
                <span class="font-medium text-gray-800">{{ $activeRent->room->floor ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Ukuran</span>
                <span class="font-medium text-gray-800">{{ $activeRent->room->size ?? '-' }} m²</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Status</span>
                <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">Aktif</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Tanggal Masuk</span>
                <span class="font-medium text-gray-800">{{ $activeRent->start_date->format('d M Y') ?? '-' }}</span>
            </div>
        </div>

        @if(is_array($activeRent->room->facilities) && count($activeRent->room->facilities) > 0)
        <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600 mb-2">Fasilitas:</p>
            <div class="flex flex-wrap gap-2">
                @foreach($activeRent->room->facilities as $facility)
                    <span class="px-3 py-1 bg-yellow-50 text-yellow-700 text-xs font-medium rounded-full">
                        {{ $facility }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-6 pt-4 border-t border-gray-200">
            <a href="{{ route('user.room.detail') }}"
               class="w-full px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Lihat Detail Kamar
            </a>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     RIWAYAT PEMBAYARAN
══════════════════════════════════════════════════ --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:border-yellow-400 transition">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Riwayat Pembayaran</h2>
            <a href="{{ route('user.billing.index') }}" class="text-sm text-yellow-600 hover:text-yellow-700 font-medium">
                Lihat Semua →
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            @forelse($paymentHistory as $payment)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-{{ $payment->status === 'confirmed' ? 'green' : 'orange' }}-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-{{ $payment->status === 'confirmed' ? 'green' : 'orange' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($payment->status === 'confirmed')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $payment->billing->month ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-{{ $payment->status === 'confirmed' ? 'green' : ($payment->status === 'rejected' ? 'red' : 'orange') }}-100 text-{{ $payment->status === 'confirmed' ? 'green' : ($payment->status === 'rejected' ? 'red' : 'orange') }}-700 text-sm font-medium rounded-full">
                        {{ ucfirst($payment->status) }}
                    </span>
                    <p class="text-xs text-gray-500 mt-1">{{ $payment->payment_date->format('d M Y') }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                Belum ada riwayat pembayaran
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection