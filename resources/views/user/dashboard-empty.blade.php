@extends('layouts.user')

@section('title', 'Pilih Tempat Kos')

@section('content')

{{-- ══════════════════════════════════════════════════
     NOTIF: BILLING PERLU DIULAS (muncul setelah pelunasan dikonfirmasi)
══════════════════════════════════════════════════ --}}
@if(isset($billingPerluDiulas) && $billingPerluDiulas)
<div class="mb-6 bg-yellow-50 border border-yellow-300 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
    <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
    </div>
    <div class="flex-1">
        <h3 class="font-bold text-yellow-800 text-base mb-1">Bagikan Pengalaman Menginap Anda!</h3>
        <p class="text-sm text-yellow-700">
            Pembayaran untuk
            <strong>{{ $billingPerluDiulas->room?->kosInfo?->tempatKos?->nama_kos ?? 'kos' }}</strong>
            sudah dikonfirmasi. Yuk berikan ulasan untuk membantu penghuni lain!
        </p>
    </div>
    <a href="{{ route('user.ulasan.create', $billingPerluDiulas) }}"
       class="flex-shrink-0 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold rounded-xl transition">
        Tulis Ulasan
    </a>
</div>
@endif

{{-- ══════════════════════════════════════════════════
     NOTIFIKASI BOOKING PENDING
══════════════════════════════════════════════════ --}}
@if(isset($pendingRent))
<div x-data="{ showCancelModal: false }">
    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
        <div class="flex items-start justify-between">
            <div class="flex items-start flex-1">
                <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="font-bold text-yellow-800 mb-2">Booking Anda Sedang Diproses</h3>
                    <p class="text-sm text-yellow-700 mb-2">
                        Booking kamar <strong>{{ $pendingRent->room->room_number }}</strong> sedang ditinjau oleh admin.
                        Anda akan dihubungi dalam 1x24 jam.
                    </p>
                </div>
            </div>
            @if(!$pendingRent->hasPendingCancel())
            <form id="cancel-booking-{{ $pendingRent->id }}"
                  action="{{ route('user.booking.cancel.store', $pendingRent) }}"
                  method="POST" style="display: none;">
                @csrf
            </form>
            <button type="button" @click="showCancelModal = true"
                    class="inline-flex items-center gap-2 ml-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batalkan Booking
            </button>
            @else
            <span class="ml-4 px-4 py-2 bg-orange-100 text-orange-700 text-sm font-semibold rounded-lg whitespace-nowrap">
                Pembatalan Sedang Diproses
            </span>
            @endif
        </div>
    </div>

    <!-- Modal Cancel Booking -->
    <div x-show="showCancelModal" x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
         @click.self="showCancelModal = false">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Batalkan Booking</h3>
                <button @click="showCancelModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('user.booking.cancel.store', $pendingRent) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bank</label>
                    <select name="bank_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                        <option value="">Pilih Bank</option>
                        <option value="BCA">BCA</option>
                        <option value="BNI">BNI</option>
                        <option value="BRI">BRI</option>
                        <option value="Mandiri">Mandiri</option>
                        <option value="CIMB Niaga">CIMB Niaga</option>
                        <option value="Bank Lainnya">Bank Lainnya</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                    <input type="text" name="account_number" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500" placeholder="1234567890">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pemilik Rekening</label>
                    <input type="text" name="account_holder_name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500" placeholder="JOHN DOE">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan (Opsional)</label>
                    <textarea name="cancel_reason" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500" placeholder="Jelaskan alasan Anda..."></textarea>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-yellow-800">
                        <strong>Catatan:</strong> Admin akan memproses pengembalian dana DP sebesar
                        <strong>Rp {{ number_format($pendingRent->deposit_paid, 0, ',', '.') }}</strong>.
                    </p>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="showCancelModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">Ajukan Pembatalan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════
     HERO IMAGE
══════════════════════════════════════════════════ --}}
<div class="relative mb-8 rounded-2xl overflow-hidden shadow-lg"
     style="height: clamp(220px, 35vw, 450px);">
    <img src="{{ hero_image_empty() }}" alt="Hero"
         class="absolute inset-0 w-full h-full object-cover" style="filter: brightness(0.6);">
    <div class="relative z-10 flex flex-col items-center justify-center h-full text-white px-4 text-center">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Temukan Kos Impian Anda</h1>
        <p class="text-sm sm:text-base text-gray-200 max-w-2xl">
            Pilih dari {{ $tempatKos->total() }} tempat kos terbaik yang tersedia
        </p>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     FILTER & SEARCH
══════════════════════════════════════════════════ --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 md:p-6 mb-8">
    <form method="GET" action="{{ route('user.dashboard') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama kos, kota, alamat..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
        </div>
        <select name="kota" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            <option value="">Semua Kota</option>
            @foreach($kotaList as $kota)
                <option value="{{ $kota }}" {{ request('kota') === $kota ? 'selected' : '' }}>{{ $kota }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <button type="submit"
                    class="w-full px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
                <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari
            </button>
            @if(request()->hasAny(['search', 'kota']))
                <a href="{{ route('user.dashboard') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Reset</a>
            @endif
        </div>
    </form>
</div>

{{-- ══════════════════════════════════════════════════
     DAFTAR TEMPAT KOS (dengan rating)
══════════════════════════════════════════════════ --}}
@if($tempatKos->count() > 0)
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Pilih Tempat Kos</h2>
        <p class="text-gray-600">{{ $tempatKos->total() }} tempat kos tersedia</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
        @foreach($tempatKos as $kos)
            <a href="{{ route('user.rooms.index', ['tempat_kos_id' => $kos->id]) }}"
               class="group bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg hover:border-yellow-400 transition-all duration-300 flex flex-col">

                <!-- Gambar -->
                <div class="relative h-36 sm:h-40 bg-gray-200 overflow-hidden flex-shrink-0">
                    @if($kos->logo)
                        <img src="{{ asset('storage/' . $kos->logo) }}" alt="{{ $kos->nama_kos }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-yellow-400 to-orange-500">
                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-500 text-white shadow-lg">
                            <span class="w-2 h-2 bg-white rounded-full mr-1.5 animate-pulse"></span>Aktif
                        </span>
                    </div>

                    {{-- Rating badge di pojok kiri bawah gambar --}}
                    @if($kos->avg_rating)
                    <div class="absolute bottom-3 left-3">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-black/60 backdrop-blur-sm text-white text-xs font-bold rounded-lg">
                            <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            {{ number_format($kos->avg_rating, 1) }}
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-3 sm:p-4 flex flex-col flex-grow">
                    <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-1 group-hover:text-yellow-600 transition line-clamp-1">
                        {{ $kos->nama_kos }}
                    </h3>

                    <!-- Rating bintang kecil -->
                    @if($kos->avg_rating)
                    <div class="flex items-center gap-1 mb-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= round($kos->avg_rating) ? 'text-yellow-400' : 'text-gray-200' }}"
                                 fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        @endfor
                        <span class="text-xs text-gray-500 ml-1">
                            {{ number_format($kos->avg_rating, 1) }}
                            ({{ $kos->total_ulasan }})
                        </span>
                    </div>
                    @endif

                    <div class="flex items-start text-xs sm:text-sm text-gray-600 mb-3 min-h-[2.5rem]">
                        <svg class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="line-clamp-2">
                            @if($kos->kecamatan) Kec. {{ $kos->kecamatan }}, @endif
                            {{ $kos->kota }}, {{ $kos->provinsi }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 mb-3">
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-yellow-500 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="font-medium text-gray-700">{{ $kos->total_kamar }} Kamar</span>
                        </div>
                        @if($kos->kamar_tersedia > 0)
                            <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">{{ $kos->kamar_tersedia }} Tersedia</span>
                        @else
                            <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">Penuh</span>
                        @endif
                    </div>

                    <button class="mt-auto w-full py-2 text-sm bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700 transition-all shadow-lg flex items-center justify-center">
                        Lihat Kamar
                        <svg class="inline-block w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $tempatKos->links() }}
    </div>

@else
    <div class="text-center py-10 sm:py-12">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-1">Tidak Ada Tempat Kos</h3>
        <p class="text-sm text-gray-500 mb-4">
            @if(request()->hasAny(['search', 'kota']))
                Tidak ditemukan hasil untuk pencarian Anda.
            @else
                Belum ada tempat kos yang tersedia saat ini.
            @endif
        </p>
    </div>
@endif

{{-- ══════════════════════════════════════════════════
     ULASAN TERBARU
══════════════════════════════════════════════════ --}}
@if(isset($ulasanTerbaru) && $ulasanTerbaru->count() > 0)
<div class="mt-12">

    <style>
        .ulasan-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .ulasan-card:hover {
            border-color: #fcd34d;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        /* Batas teks 3 baris */
        .komentar-box {
            position: relative;
            overflow: hidden;
            max-height: 62px;
            transition: max-height 0.4s ease;
            flex-shrink: 0;
        }
        .komentar-box.is-open { max-height: 600px; }

        /* Gradient fade ujung teks */
        .komentar-fade-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 32px;
            background: linear-gradient(to bottom, transparent, #ffffff);
            pointer-events: none;
            transition: opacity 0.3s;
        }
        .komentar-box.is-open .komentar-fade-overlay { opacity: 0; }

        /* "...selengkapnya" pojok kanan bawah */
        .komentar-readmore {
            position: absolute;
            bottom: 0; right: 0;
            font-size: 0.72rem;
            font-weight: 700;
            color: #d97706;
            cursor: pointer;
            background: #ffffff;
            padding: 0 2px 1px 8px;
        }
        .komentar-box.is-open .komentar-readmore { display: none; }

        /* Spacer dorong user-info ke bawah */
        .ulasan-spacer { flex: 1; min-height: 0.5rem; }

        /* User info selalu di paling bawah */
        .ulasan-footer {
            border-top: 1px solid #f3f4f6;
            padding-top: 0.65rem;
            margin-top: 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-shrink: 0;
        }
    </style>

    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-800">Ulasan Terbaru</h2>
            <p class="text-sm text-gray-500">Pengalaman nyata dari penghuni kos</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($ulasanTerbaru as $ulasan)
        <div x-data="{
                open: false,
                overflow: false,
                init() {
                    this.$nextTick(() => {
                        const el = this.$refs.teks;
                        if (el) this.overflow = el.scrollHeight > 64;
                    });
                }
             }"
             class="ulasan-card">

            {{-- Badge nama kos --}}
            <div class="text-xs font-semibold text-yellow-600 bg-yellow-50 inline-block px-2 py-1 rounded-lg mb-3" style="flex-shrink:0;">
                {{ $ulasan->tempatKos?->nama_kos ?? '-' }}
            </div>

            {{-- Bintang --}}
            <div style="display:flex;align-items:center;gap:2px;margin-bottom:0.6rem;flex-shrink:0;">
                @for($i = 1; $i <= 5; $i++)
                    <svg width="14" height="14" viewBox="0 0 24 24"
                         fill="{{ $i <= $ulasan->rating ? '#f59e0b' : '#e5e7eb' }}">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                @endfor
                <span style="font-size:0.72rem;font-weight:700;color:#d97706;margin-left:4px;">
                    {{ number_format($ulasan->rating, 1) }}
                </span>
            </div>

            {{-- Komentar: fixed 3 baris + fade + ...selengkapnya --}}
            <div class="komentar-box" :class="{ 'is-open': open }">
                <p x-ref="teks"
                   class="text-sm text-gray-600"
                   style="line-height:1.6;font-style:italic;">
                    "{{ $ulasan->komentar ?: 'Tidak ada komentar.' }}"
                </p>
                <div class="komentar-fade-overlay" x-show="overflow"></div>
                <span class="komentar-readmore"
                      x-show="overflow"
                      @click="open = true">
                    ...selengkapnya
                </span>
            </div>

            {{-- Sembunyikan --}}
            <div x-show="open && overflow" style="margin-top:4px;flex-shrink:0;">
                <button @click="open = false"
                        class="text-xs font-bold text-yellow-600"
                        style="background:none;border:none;cursor:pointer;padding:0;font-family:inherit;">
                    ↑ Sembunyikan
                </button>
            </div>

            {{-- Spacer --}}
            <div class="ulasan-spacer"></div>

            {{-- User info — SELALU di paling bawah --}}
            <div class="ulasan-footer">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-yellow-400 to-orange-400 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($ulasan->user?->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-700" style="line-height:1.2;">
                        {{ $ulasan->user?->name ?? 'Anonim' }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $ulasan->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════
     INFO CARA BOOKING
══════════════════════════════════════════════════ --}}
<div class="mt-12 bg-yellow-50 border border-yellow-200 rounded-xl p-6">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <h3 class="font-bold text-yellow-900 mb-2">Cara Booking Kamar</h3>
            <ol class="text-sm text-yellow-800 space-y-1 list-decimal list-inside">
                <li>Pilih tempat kos yang Anda inginkan</li>
                <li>Lihat daftar kamar yang tersedia</li>
                <li>Isi form booking dengan lengkap</li>
                <li>Upload bukti transfer DP (50% dari harga sewa)</li>
                <li>Tunggu konfirmasi dari admin (maksimal 1x24 jam)</li>
                <li>Setelah disetujui, Anda dapat langsung menempati kamar</li>
                <li>Berikan ulasan setelah pembayaran dikonfirmasi ⭐</li>
            </ol>
        </div>
    </div>
</div>

@endsection