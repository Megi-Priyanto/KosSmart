@extends('layouts.user')

@section('title', 'Berikan Ulasan')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">

    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Berikan Ulasan Anda</h1>
        <p class="text-gray-500 mt-1">Bagikan pengalaman menginap Anda di</p>
        <p class="text-lg font-semibold text-yellow-600">
            {{ $billing->room?->kosInfo?->tempatKos?->nama_kos ?? 'Tempat Kos' }}
        </p>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

        <!-- Kos Info -->
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-5 text-white">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-yellow-100">Anda menghuni kamar</p>
                    <p class="text-xl font-bold">{{ $billing->room->room_number ?? '-' }}</p>
                    <p class="text-sm text-yellow-100">
                        {{ $billing->room?->kosInfo?->tempatKos?->kota ?? '' }}
                        • Tagihan {{ $billing->formatted_period }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('user.ulasan.store', $billing) }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Rating Bintang -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Penilaian Keseluruhan <span class="text-red-500">*</span>
                </label>

                <div x-data="{ rating: 0, hover: 0 }" class="flex items-center gap-1">
                    <template x-for="i in 5" :key="i">
                        <button type="button"
                                @click="rating = i"
                                @mouseenter="hover = i"
                                @mouseleave="hover = 0"
                                class="focus:outline-none transition-transform hover:scale-110">
                            <svg class="w-10 h-10 transition-colors duration-150"
                                 :class="(hover || rating) >= i ? 'text-yellow-400' : 'text-gray-200'"
                                 fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </button>
                    </template>

                    <!-- Hidden input yang diisi via Alpine -->
                    <input type="hidden" name="rating" :value="rating" required>

                    <!-- Label rating -->
                    <span class="ml-3 text-sm font-medium text-gray-600" x-show="rating > 0">
                        <span x-show="rating == 1">😞 Sangat Buruk</span>
                        <span x-show="rating == 2">😕 Buruk</span>
                        <span x-show="rating == 3">😐 Cukup</span>
                        <span x-show="rating == 4">😊 Baik</span>
                        <span x-show="rating == 5">🤩 Sangat Baik!</span>
                    </span>
                </div>

                @error('rating')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Komentar -->
            <div>
                <label for="komentar" class="block text-sm font-semibold text-gray-700 mb-2">
                    Komentar <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea id="komentar"
                          name="komentar"
                          rows="4"
                          maxlength="1000"
                          placeholder="Ceritakan pengalaman Anda menginap di sini... Bagaimana kondisi kamar, kebersihan, pelayanan, dll."
                          class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-transparent resize-none text-sm text-gray-700 placeholder-gray-400">{{ old('komentar') }}</textarea>
                <p class="text-xs text-gray-400 text-right mt-1">Maks. 1000 karakter</p>

                @error('komentar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-2">
                <a href="{{ route('user.billing.show', $billing) }}"
                   class="flex-1 px-5 py-3 text-center border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition font-medium text-sm">
                    Lewati
                </a>
                <button type="submit"
                        class="flex-1 px-5 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl font-bold text-sm hover:from-yellow-600 hover:to-orange-600 transition shadow-lg shadow-yellow-200">
                    Kirim Ulasan
                </button>
            </div>
        </form>
    </div>

    <!-- Info -->
    <p class="text-center text-xs text-gray-400 mt-4">
        Ulasan Anda akan membantu penghuni lain menemukan tempat kos terbaik 🏠
    </p>
</div>
@endsection