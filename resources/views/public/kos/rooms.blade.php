@extends('layouts.public')

@section('title', 'Kamar ' . $tempatKos->nama_kos . ' - KosSmart')

@push('head-scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@push('page-styles')
        /* ── Card Kamar ── */
        .room-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.25s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .room-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(15,23,42,0.1);
            border-color: rgba(245,158,11,0.3);
        }
        .room-card.dimmed { opacity: 0.65; }

        /* Thumbnail fixed height */
        .room-thumb {
            height: 144px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }
        .room-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .room-card:hover .room-thumb img { transform: scale(1.05); }

        /* Pill badge */
        .pill { font-size: 0.65rem; font-weight: 700; padding: 3px 10px; border-radius: 100px; }
        .pill-white  { background: #ffffff; color: #1e293b; }
        .pill-avail  { background: #22c55e; color: #ffffff; }
        .pill-occupied { background: #3b82f6; color: #ffffff; }
        .pill-maint  { background: #f97316; color: #ffffff; }

        /* Card body — flex col flex-1 so button always at bottom */
        .room-body {
            padding: 0.85rem 1rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .room-price {
            font-size: 1.1rem;
            font-weight: 800;
            color: #d97706;
            flex: 1; /* push button to bottom */
        }

        /* ── Filter ── */
        .filter-box { background: #ffffff; border: 1px solid #fde68a; border-radius: 14px; padding: 1rem 1.25rem; }
        .filter-label { font-size: 0.72rem; font-weight: 700; color: #d97706; margin-bottom: 6px; display: block; }
        .filter-btn {
            display: flex; align-items: center; justify-content: space-between; gap: 8px;
            background: #ffffff; border: 1px solid #fde68a; border-radius: 9px;
            padding: 0.6rem 1rem; font-size: 0.875rem; color: #1e293b;
            cursor: pointer; font-family: inherit; width: 100%; transition: border-color 0.2s;
        }
        .filter-btn:hover { border-color: #f59e0b; }
        .filter-dd {
            background: #ffffff; border: 1px solid #fed7aa; border-radius: 10px;
            overflow: hidden; box-shadow: 0 12px 30px rgba(15,23,42,0.1);
        }
        .filter-dd-item { padding: 0.5rem 0.85rem; font-size: 0.82rem; color: #64748b; cursor: pointer; transition: all 0.15s; }
        .filter-dd-item:hover { background: rgba(217,119,6,0.08); color: #1e293b; }
        .filter-col { flex: 1; min-width: 140px; position: relative; }
        .filter-cari { flex: 1; min-width: 120px; }

        /* ── Kos Header ── */
        .kos-header {
            background: linear-gradient(135deg, rgba(245,158,11,0.12), rgba(217,119,6,0.08));
            border: 1px solid rgba(245,158,11,0.2);
            border-radius: 18px;
            padding: 1.5rem;
        }
        .stat-box {
            text-align: center; padding: 0.75rem;
            background: rgba(255,255,255,0.7); border: 1px solid rgba(15,23,42,0.08);
            border-radius: 12px;
        }

        /* ── Ulasan ── */
        .ulasan-card {
            background: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px;
            padding: 1.1rem; transition: border-color 0.2s, box-shadow 0.2s;
            display: flex; flex-direction: column; box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .ulasan-card:hover { border-color: #fcd34d; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .komentar-box { position: relative; overflow: hidden; max-height: 62px; transition: max-height 0.4s ease; flex-shrink: 0; }
        .komentar-box.is-open { max-height: 600px; }
        .komentar-fade-overlay { position: absolute; bottom: 0; left: 0; right: 0; height: 32px; background: linear-gradient(to bottom, transparent, #ffffff); pointer-events: none; transition: opacity 0.3s; }
        .komentar-box.is-open .komentar-fade-overlay { opacity: 0; }
        .komentar-readmore { position: absolute; bottom: 0; right: 0; font-size: 0.72rem; font-weight: 700; color: #d97706; cursor: pointer; background: #ffffff; padding: 0 2px 1px 8px; }
        .komentar-box.is-open .komentar-readmore { display: none; }
        .ulasan-spacer { flex: 1; min-height: 0.5rem; }
        .ulasan-footer { border-top: 1px solid #f3f4f6; padding-top: 0.65rem; margin-top: 0.6rem; display: flex; align-items: center; gap: 0.6rem; flex-shrink: 0; }
        .rating-bar-track { flex: 1; height: 8px; background: #f3f4f6; border-radius: 100px; overflow: hidden; }
        .rating-bar-fill { height: 100%; background: linear-gradient(90deg, #f59e0b, #d97706); border-radius: 100px; }

@endpush

@section('content')

<div style="max-width:1152px;margin:0 auto;padding:5.5rem 1.5rem 3rem;position:relative;z-index:1;">

    {{-- Breadcrumb --}}
    <nav style="display:flex;align-items:center;gap:8px;font-size:0.8rem;color:#64748b;margin-bottom:1.25rem;">
        <a href="{{ route('public.kos.index') }}"
           style="color:#64748b;text-decoration:none;"
           onmouseover="this.style.color='#d97706'" onmouseout="this.style.color='#64748b'">Daftar Kos</a>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span style="color:#1e293b;font-weight:600;">{{ $tempatKos->nama_kos }}</span>
    </nav>

    {{-- Kos Header --}}
    <div class="kos-header" style="margin-bottom:1.5rem;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:1.6rem;font-weight:800;color:#1e293b;margin-bottom:0.35rem;">{{ $tempatKos->nama_kos }}</h1>
                <div style="display:flex;align-items:center;gap:6px;font-size:0.84rem;color:#64748b;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $tempatKos->alamat }}{{ $tempatKos->kota ? ', ' . $tempatKos->kota : '' }}{{ $tempatKos->provinsi ? ', ' . $tempatKos->provinsi : '' }}
                </div>

                @if($tempatKos->telepon || $tempatKos->email)
                <div style="display:flex;flex-wrap:wrap;gap:1rem;margin-top:0.5rem;font-size:0.82rem;color:#64748b;">
                    @if($tempatKos->telepon)
                    <div style="display:flex;align-items:center;gap:4px;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $tempatKos->telepon }}
                    </div>
                    @endif
                    @if($tempatKos->email)
                    <div style="display:flex;align-items:center;gap:4px;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $tempatKos->email }}
                    </div>
                    @endif
                </div>
                @endif
            </div>

            @if($tempatKos->logo)
            <img src="{{ asset('storage/' . $tempatKos->logo) }}" alt="{{ $tempatKos->nama_kos }}"
                 style="width:72px;height:72px;border-radius:12px;object-fit:cover;border:2px solid rgba(245,158,11,0.3);">
            @endif
        </div>

        {{-- Stats --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.75rem;border-top:1px solid rgba(15,23,42,0.08);padding-top:1rem;">
            <div class="stat-box">
                <div style="font-size:1.4rem;font-weight:800;color:#1e293b;">{{ $totalRooms }}</div>
                <div style="font-size:0.72rem;color:#64748b;margin-top:2px;">Total Kamar</div>
            </div>
            <div class="stat-box">
                <div style="font-size:1.4rem;font-weight:800;color:#16a34a;">{{ $availableRooms }}</div>
                <div style="font-size:0.72rem;color:#64748b;margin-top:2px;">Tersedia</div>
            </div>
            <div class="stat-box">
                <div style="font-size:1.4rem;font-weight:800;color:#3b82f6;">{{ $occupiedRooms }}</div>
                <div style="font-size:0.72rem;color:#64748b;margin-top:2px;">Terisi</div>
            </div>
            <div class="stat-box">
                <div style="font-size:1.4rem;font-weight:800;color:#f97316;">{{ $maintenanceRooms }}</div>
                <div style="font-size:0.72rem;color:#64748b;margin-top:2px;">Maintenance</div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    @php
        $selectedType     = request('type') ? ucfirst(request('type')) : 'Semua Tipe';
        $selectedFloor    = request('floor') ?? 'Semua Lantai';
        $selectedMaxPrice = request('max_price') ? 'Rp ' . number_format(request('max_price'), 0, ',', '.') : 'Tanpa Batas';
        $selectedMaxValue = request('max_price', '');
    @endphp

    <div class="filter-box" style="margin-bottom:1.5rem;">
        <form method="GET" action="{{ route('public.kos.rooms', $tempatKos) }}"
              style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:flex-end;">

            {{-- Tipe Kamar --}}
            @php $xDataTipe = json_encode(['open' => false, 'selected' => $selectedType]); @endphp
            <div x-data="{{ $xDataTipe }}" class="filter-col">
                <label class="filter-label">Tipe Kamar</label>
                <input type="hidden" name="type" :value="selected === 'Semua Tipe' ? '' : selected.toLowerCase()">
                <button type="button" @click="open = !open" class="filter-btn">
                    <span x-text="selected"></span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" @click.outside="open=false"
                     style="position:absolute;z-index:20;width:100%;margin-top:4px;" class="filter-dd">
                    <div @click="selected='Semua Tipe'; open=false" class="filter-dd-item">Semua Tipe</div>
                    @foreach($types as $type)
                    <div @click="selected='{{ ucfirst($type) }}'; open=false" class="filter-dd-item">{{ ucfirst($type) }}</div>
                    @endforeach
                </div>
            </div>

            {{-- Lantai --}}
            @php $xDataLantai = json_encode(['open' => false, 'selected' => $selectedFloor]); @endphp
            <div x-data="{{ $xDataLantai }}" class="filter-col">
                <label class="filter-label">Lantai</label>
                <input type="hidden" name="floor" :value="selected === 'Semua Lantai' ? '' : selected">
                <button type="button" @click="open = !open" class="filter-btn">
                    <span x-text="selected"></span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" @click.outside="open=false"
                     style="position:absolute;z-index:20;width:100%;margin-top:4px;" class="filter-dd">
                    <div @click="selected='Semua Lantai'; open=false" class="filter-dd-item">Semua Lantai</div>
                    @foreach($floors as $floor)
                    <div @click="selected='{{ $floor }}'; open=false" class="filter-dd-item">{{ $floor }}</div>
                    @endforeach
                </div>
            </div>

            {{-- Harga Maks --}}
            @php $xDataHarga = json_encode(['open' => false, 'selected' => $selectedMaxPrice, 'value' => $selectedMaxValue]); @endphp
            <div x-data="{{ $xDataHarga }}" class="filter-col">
                <label class="filter-label">Harga Maks</label>
                <input type="hidden" name="max_price" :value="value">
                <button type="button" @click="open = !open" class="filter-btn">
                    <span x-text="selected"></span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" @click.outside="open=false"
                     style="position:absolute;z-index:20;width:100%;margin-top:4px;" class="filter-dd">
                    <div @click="selected='Tanpa Batas'; value=''; open=false" class="filter-dd-item">Tanpa Batas</div>
                    <div @click="selected='Rp 1.000.000'; value='1000000'; open=false" class="filter-dd-item">Rp 1.000.000</div>
                    <div @click="selected='Rp 1.500.000'; value='1500000'; open=false" class="filter-dd-item">Rp 1.500.000</div>
                    <div @click="selected='Rp 2.000.000'; value='2000000'; open=false" class="filter-dd-item">Rp 2.000.000</div>
                </div>
            </div>

            {{-- Tombol Cari --}}
            <div class="filter-cari">
                <label class="filter-label" style="visibility:hidden;">Cari</label>
                <button type="submit"
                        style="width:100%;padding:0.6rem 1rem;background:linear-gradient(135deg,#f59e0b,#d97706);color:#ffffff;font-weight:700;border-radius:9px;border:none;cursor:pointer;font-family:inherit;font-size:0.875rem;">
                    Cari
                </button>
            </div>

            {{-- Reset --}}
            @if(request()->hasAny(['type', 'floor', 'max_price']))
            <div style="align-self:flex-end;">
                <a href="{{ route('public.kos.rooms', $tempatKos) }}"
                   style="display:inline-flex;align-items:center;gap:6px;padding:0.6rem 1rem;border:1px solid #e2e8f0;color:#64748b;border-radius:9px;text-decoration:none;font-size:0.82rem;"
                   onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>
            </div>
            @endif
        </form>
    </div>

    {{-- ── Daftar Kamar ── --}}
    @if($rooms->count() > 0)
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <h2 style="font-size:1.4rem;font-weight:800;color:#1e293b;">Daftar Kamar</h2>
        <p style="font-size:0.82rem;color:#64748b;">{{ $rooms->total() }} kamar ditemukan</p>
    </div>

    {{-- Grid — items-stretch agar semua card sejajar --}}
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin-bottom:2rem;"
         class="room-grid">
        <style>
            @media (min-width: 640px) { .room-grid { grid-template-columns: repeat(2, 1fr) !important; } }
            @media (min-width: 768px) { .room-grid { grid-template-columns: repeat(3, 1fr) !important; } }
            @media (min-width: 1024px) { .room-grid { grid-template-columns: repeat(4, 1fr) !important; } }
        </style>

        @foreach($rooms as $room)
        {{-- flex flex-col h-full: card stretch sesuai tinggi baris --}}
        <div class="room-card {{ $room->status !== 'available' ? 'dimmed' : '' }}">

            {{-- Thumbnail --}}
            <div class="room-thumb" style="background:linear-gradient(135deg,#fef3c7,#fde68a);">
                @php
                    $images = is_array($room->images) ? $room->images : json_decode($room->images, true);
                    $firstImage = $images[0] ?? null;
                @endphp
                @if($firstImage)
                    <img src="{{ asset('storage/' . $firstImage) }}" alt="Kamar {{ $room->room_number }}">
                @else
                    <div style="width:100%;height:100%;background:linear-gradient(135deg,#fbbf24,#f97316);"></div>
                @endif

                {{-- Badge Tipe --}}
                <div style="position:absolute;top:10px;left:10px;">
                    <span class="pill pill-white">{{ ucfirst($room->type) }}</span>
                </div>

                {{-- Badge Status --}}
                <div style="position:absolute;top:10px;right:10px;">
                    @if($room->status === 'available')
                        <span class="pill pill-avail">Tersedia</span>
                    @elseif($room->status === 'occupied')
                        <span class="pill pill-occupied">Terisi</span>
                    @else
                        <span class="pill pill-maint">Maintenance</span>
                    @endif
                </div>
            </div>

            {{-- Body — flex-col flex-1 agar tombol di-push ke bawah --}}
            <div class="room-body">
                <h3 style="font-size:0.9rem;font-weight:700;color:#1e293b;margin-bottom:0.2rem;">
                    Kamar {{ $room->room_number }}
                </h3>
                <p style="font-size:0.75rem;color:#64748b;margin-bottom:0.5rem;">
                    {{ $room->floor }} • {{ $room->size }} m²
                </p>

                {{-- Fasilitas --}}
                @if($room->facilities)
                @php
                    $facilities = is_array($room->facilities) ? $room->facilities : json_decode($room->facilities, true);
                @endphp
                <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:0.6rem;">
                    @foreach(array_slice($facilities ?? [], 0, 3) as $facility)
                        <span style="font-size:0.65rem;padding:2px 8px;background:#fef3c7;color:#b45309;border-radius:100px;font-weight:600;">
                            {{ $facility }}
                        </span>
                    @endforeach
                </div>
                @endif

                {{-- Harga — flex:1 mendorong tombol ke bawah --}}
                <div class="room-price" style="margin-bottom:0.75rem;">
                    Rp {{ number_format($room->price, 0, ',', '.') }}
                    <span style="font-size:0.68rem;font-weight:400;color:#94a3b8;">
                        /{{ $room->jenis_sewa === 'tahun' ? 'tahun' : 'bulan' }}
                    </span>
                </div>

                {{-- mt-auto: tombol selalu nempel di bawah --}}
                <div style="margin-top:auto;">
                    @if($room->status === 'available')
                        <a href="{{ route('public.kos.room-detail', $room) }}"
                           style="display:block;text-align:center;font-size:0.82rem;font-weight:700;color:#ffffff;background:linear-gradient(135deg,#f59e0b,#d97706);padding:0.5rem 0.75rem;border-radius:8px;text-decoration:none;transition:box-shadow 0.2s;"
                           onmouseover="this.style.boxShadow='0 4px 14px rgba(245,158,11,0.4)'"
                           onmouseout="this.style.boxShadow='none'">
                            Lihat Detail
                        </a>
                    @elseif($room->status === 'occupied')
                        <button disabled
                           style="display:block;width:100%;text-align:center;font-size:0.82rem;font-weight:600;color:#94a3b8;background:#f1f5f9;border:1px solid #e2e8f0;padding:0.5rem 0.75rem;border-radius:8px;cursor:not-allowed;">
                            Sudah Terisi
                        </button>
                    @else
                        <button disabled
                           style="display:block;width:100%;text-align:center;font-size:0.82rem;font-weight:600;color:#f97316;background:#fff7ed;border:1px solid #fed7aa;padding:0.5rem 0.75rem;border-radius:8px;cursor:not-allowed;">
                            Sedang Maintenance
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div>{{ $rooms->links() }}</div>

    @else
    {{-- Empty state --}}
    <div style="text-align:center;padding:4rem 2rem;background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;">
        <svg width="56" height="56" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 1rem;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <p style="font-weight:700;color:#475569;margin-bottom:0.5rem;">Belum ada kamar yang tersedia</p>
        <a href="{{ route('public.kos.index') }}"
           style="font-size:0.84rem;color:#d97706;text-decoration:none;font-weight:700;">
            Cari Tempat Kos Lain →
        </a>
    </div>
    @endif

    {{-- ── Rating & Ulasan ── --}}
    @if(isset($totalUlasan) && $totalUlasan > 0)
    <section style="margin-top:3.5rem;padding-top:3rem;border-top:1px solid #e2e8f0;">

        {{-- Header --}}
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:2rem;">
            <div style="width:40px;height:40px;background:rgba(245,158,11,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="#f59e0b">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#1e293b;">Rating & Ulasan</h2>
                <p style="font-size:0.8rem;color:#64748b;">{{ $totalUlasan }} ulasan dari penghuni</p>
            </div>
        </div>

        {{-- Ringkasan Rating --}}
        <div style="display:grid;grid-template-columns:auto 1fr;gap:2.5rem;background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:1.75rem;margin-bottom:2rem;">
            <div style="text-align:center;min-width:130px;">
                <div style="font-size:4rem;font-weight:900;color:#f59e0b;line-height:1;">
                    {{ number_format($avgRating, 1) }}
                </div>
                <div style="display:flex;justify-content:center;gap:3px;margin:0.5rem 0;">
                    @for($i = 1; $i <= 5; $i++)
                        <svg width="18" height="18" viewBox="0 0 24 24"
                             fill="{{ $i <= round($avgRating) ? '#f59e0b' : '#e5e7eb' }}">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    @endfor
                </div>
                <div style="font-size:0.78rem;color:#64748b;">dari {{ $totalUlasan }} ulasan</div>
            </div>

            <div style="display:flex;flex-direction:column;justify-content:center;gap:0.55rem;">
                @for($i = 5; $i >= 1; $i--)
                <div style="display:flex;align-items:center;gap:0.65rem;">
                    <span style="font-size:0.75rem;color:#64748b;width:14px;text-align:right;flex-shrink:0;">{{ $i }}</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="#f59e0b" style="flex-shrink:0;">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <div class="rating-bar-track">
                        <div class="rating-bar-fill" style="width:{{ $ratingDistribution[$i]['percent'] ?? 0 }}%;"></div>
                    </div>
                    <span style="font-size:0.72rem;color:#64748b;width:28px;text-align:right;flex-shrink:0;">
                        {{ $ratingDistribution[$i]['count'] ?? 0 }}
                    </span>
                </div>
                @endfor
            </div>
        </div>

        {{-- Kartu Ulasan --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
            @foreach(($ulasanList ?? collect())->take(6) as $ulasan)
            <div x-data="{
                    open: false, overflow: false,
                    init() { this.$nextTick(() => { const el = this.$refs.teks; if (el) this.overflow = el.scrollHeight > 64; }); }
                 }" class="ulasan-card">

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

                {{-- Komentar --}}
                <div class="komentar-box" :class="{ 'is-open': open }">
                    <p x-ref="teks" style="font-size:0.82rem;color:#64748b;line-height:1.6;font-style:italic;">
                        "{{ $ulasan->komentar ?: 'Tidak ada komentar.' }}"
                    </p>
                    <div class="komentar-fade-overlay" x-show="overflow"></div>
                    <span class="komentar-readmore" x-show="overflow" @click="open = true">...selengkapnya</span>
                </div>

                <div x-show="open && overflow" style="margin-top:4px;flex-shrink:0;">
                    <button @click="open = false"
                            style="font-size:0.72rem;font-weight:700;color:#d97706;background:none;border:none;cursor:pointer;padding:0;font-family:inherit;">
                        ↑ Sembunyikan
                    </button>
                </div>

                <div class="ulasan-spacer"></div>

                {{-- User Info --}}
                <div class="ulasan-footer">
                    <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:800;color:#ffffff;flex-shrink:0;">
                        {{ strtoupper(substr($ulasan->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p style="font-size:0.78rem;font-weight:700;color:#1e293b;line-height:1.2;">
                            {{ $ulasan->user?->name ?? 'Anonim' }}
                        </p>
                        <p style="font-size:0.68rem;color:#94a3b8;">{{ $ulasan->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if(isset($ulasanList) && $ulasanList->count() > 6)
        <p style="text-align:center;font-size:0.82rem;color:#94a3b8;margin-top:1.25rem;">
            Dan {{ $ulasanList->count() - 6 }} ulasan lainnya...
        </p>
        @endif

    </section>

    @else
    @if(isset($tempatKos))
    <section style="margin-top:3rem;padding:2rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;text-align:center;">
        <svg width="44" height="44" viewBox="0 0 24 24" fill="#e2e8f0" style="margin:0 auto 0.75rem;">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </svg>
        <p style="color:#94a3b8;font-size:0.87rem;">Belum ada ulasan untuk kos ini.</p>
    </section>
    @endif
    @endif

</div>

@endsection