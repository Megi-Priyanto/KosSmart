@extends('layouts.public')

@section('title', 'Kamar ' . $tempatKos->nama_kos . ' - KosSmart')

@push('head-scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@push('page-styles')
        .room-card{background:var(--card);border:1px solid var(--border);border-radius:16px;overflow:hidden;transition:all 0.25s;}
        .room-card:hover{transform:translateY(-3px);border-color:rgba(245,158,11,0.25);box-shadow:0 16px 40px rgba(0,0,0,0.4);}
        .room-card.dimmed{opacity:0.6;}
        .room-thumb{height:140px;position:relative;overflow:hidden;}
        .room-thumb img{width:100%;height:100%;object-fit:cover;transition:transform 0.3s;}
        .room-card:hover .room-thumb img{transform:scale(1.05);}
        .pill{font-size:0.65rem;font-weight:700;padding:3px 8px;border-radius:100px;}
        .pill-type{background:rgba(255,255,255,0.15);backdrop-filter:blur(6px);color:white;}
        .pill-avail{background:rgba(52,211,153,0.85);color:#064e3b;}
        .pill-occupied{background:rgba(96,165,250,0.85);color:#1e3a5f;}
        .pill-maint{background:rgba(251,146,60,0.85);color:#431407;}

        /* Filter */
        .filter-box{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:1rem 1.25rem;}
        .filter-label{font-size:0.72rem;font-weight:700;color:var(--amber);margin-bottom:6px;display:block;}
        .filter-btn{display:flex;align-items:center;justify-content:space-between;gap:8px;background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:9px;padding:0.6rem 1rem;font-size:0.875rem;color:var(--text);cursor:pointer;font-family:inherit;width:100%;transition:border-color 0.2s;}
        .filter-btn:hover{border-color:rgba(245,158,11,0.4);}
        .filter-dd{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden;box-shadow:0 12px 30px rgba(0,0,0,0.5);}
        .filter-dd-item{padding:0.5rem 0.85rem;font-size:0.82rem;color:var(--muted);cursor:pointer;transition:all 0.15s;}
        .filter-dd-item:hover{background:rgba(255,255,255,0.06);color:var(--text);}
        .filter-col{flex:1;min-width:140px;position:relative;}
        .filter-cari{flex:1;min-width:120px;}

        /* Kos header */
        .kos-header{background:linear-gradient(135deg,rgba(245,158,11,0.15),rgba(217,119,6,0.1));border:1px solid rgba(245,158,11,0.2);border-radius:18px;padding:1.5rem;}
        .stat-box{text-align:center;padding:0.75rem;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:12px;}
        .cta-bar{background:rgba(31,41,55,0.6);border:1px solid var(--border);border-radius:16px;padding:1.75rem;text-align:center;margin-top:2rem;}
        .dark-pagination nav span,.dark-pagination nav a{background:var(--card) !important;border-color:var(--border) !important;color:var(--muted) !important;}
        .dark-pagination nav a:hover,.dark-pagination nav span[aria-current]{background:rgba(245,158,11,0.15) !important;color:var(--amber) !important;}

        /* Ulasan card */
        .ulasan-card{background:rgba(31,41,55,0.7);border:1px solid var(--border);border-radius:14px;padding:1.1rem;transition:border-color 0.2s;display:flex;flex-direction:column;}
        .ulasan-card:hover{border-color:rgba(245,158,11,0.25);}
        .komentar-box{position:relative;overflow:hidden;max-height:62px;transition:max-height 0.4s ease;flex-shrink:0;}
        .komentar-box.is-open{max-height:600px;}
        .komentar-fade-overlay{position:absolute;bottom:0;left:0;right:0;height:32px;background:linear-gradient(to bottom,transparent,#1e2a3a);pointer-events:none;transition:opacity 0.3s;}
        .komentar-box.is-open .komentar-fade-overlay{opacity:0;}
        .komentar-readmore{position:absolute;bottom:0;right:0;font-size:0.72rem;font-weight:700;color:var(--amber);cursor:pointer;background:#1e2a3a;padding:0 2px 1px 8px;}
        .komentar-box.is-open .komentar-readmore{display:none;}
        .ulasan-spacer{flex:1;min-height:0.5rem;}
        .ulasan-footer{border-top:1px solid var(--border);padding-top:0.65rem;margin-top:0.6rem;display:flex;align-items:center;gap:0.6rem;flex-shrink:0;}
@endpush

@section('content')

<div style="max-width:1152px;margin:0 auto;padding:5.5rem 1.5rem 3rem;position:relative;z-index:1;">

    {{-- Breadcrumb --}}
    <nav style="display:flex;align-items:center;gap:8px;font-size:0.8rem;color:var(--muted);margin-bottom:1.25rem;">
        <a href="{{ route('public.kos.index') }}" style="color:var(--muted);text-decoration:none;"
           onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Daftar Kos</a>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span style="color:var(--text);font-weight:600;">{{ $tempatKos->nama_kos }}</span>
    </nav>

    {{-- Kos Header --}}
    <div class="kos-header" style="margin-bottom:1.5rem;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h1 style="font-size:1.6rem;font-weight:800;margin-bottom:0.35rem;">{{ $tempatKos->nama_kos }}</h1>
                <div style="display:flex;align-items:center;gap:6px;font-size:0.84rem;color:var(--muted);">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $tempatKos->alamat }}, {{ $tempatKos->kota }}, {{ $tempatKos->provinsi }}
                </div>
            </div>
            @if($tempatKos->logo)
            <img src="{{ asset('storage/' . $tempatKos->logo) }}" alt="{{ $tempatKos->nama_kos }}"
                 style="width:64px;height:64px;border-radius:12px;object-fit:cover;border:2px solid rgba(245,158,11,0.3);">
            @endif
        </div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.75rem;border-top:1px solid var(--border);padding-top:1rem;">
            <div class="stat-box"><div style="font-size:1.4rem;font-weight:800;color:var(--text);">{{ $totalRooms }}</div><div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Total Kamar</div></div>
            <div class="stat-box"><div style="font-size:1.4rem;font-weight:800;color:var(--green);">{{ $availableRooms }}</div><div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Tersedia</div></div>
            <div class="stat-box"><div style="font-size:1.4rem;font-weight:800;color:#60a5fa;">{{ $occupiedRooms }}</div><div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Terisi</div></div>
            <div class="stat-box"><div style="font-size:1.4rem;font-weight:800;color:#fb923c;">{{ $maintenanceRooms }}</div><div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Maintenance</div></div>
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
              style="display:flex;flex-wrap:nowrap;gap:0.75rem;align-items:flex-end;">

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
                        style="width:100%;padding:0.6rem 1rem;background:linear-gradient(135deg,var(--amber),#d97706);color:#111;font-weight:700;border-radius:9px;border:none;cursor:pointer;font-family:inherit;font-size:0.875rem;">
                    Cari
                </button>
            </div>

            {{-- Reset --}}
            @if(request()->hasAny(['type', 'floor', 'max_price']))
            <div style="align-self:flex-end;">
                <a href="{{ route('public.kos.rooms', $tempatKos) }}"
                   style="display:inline-flex;align-items:center;gap:6px;padding:0.6rem 1rem;border:1px solid var(--border);color:var(--muted);border-radius:9px;text-decoration:none;font-size:0.82rem;"
                   onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--muted)'">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>
            </div>
            @endif

        </form>
    </div>

    {{-- Daftar Kamar --}}
    @if($rooms->count() > 0)
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
        <h2 style="font-size:1.4rem;font-weight:800;color:var(--text);">Daftar Kamar</h2>
        <p style="font-size:0.82rem;color:var(--muted);">{{ $rooms->total() }} kamar ditemukan</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.25rem;margin-bottom:2rem;">
        @foreach($rooms as $room)
        <div class="room-card {{ $room->status !== 'available' ? 'dimmed' : '' }}">
            <div class="room-thumb" style="background:linear-gradient(135deg,#1f2937,#374151);">
                @php $images = is_array($room->images) ? $room->images : json_decode($room->images, true); $firstImage = $images[0] ?? null; @endphp
                @if($firstImage)
                    <img src="{{ asset('storage/' . $firstImage) }}" alt="Kamar {{ $room->room_number }}">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                        <span class="material-symbols-rounded" style="font-size:40px;color:rgba(255,255,255,0.2);">bed</span>
                    </div>
                @endif
                <div style="position:absolute;top:8px;left:8px;"><span class="pill pill-type">{{ ucfirst($room->type) }}</span></div>
                <div style="position:absolute;top:8px;right:8px;">
                    @if($room->status === 'available') <span class="pill pill-avail">Tersedia</span>
                    @elseif($room->status === 'occupied') <span class="pill pill-occupied">Terisi</span>
                    @else <span class="pill pill-maint">Maintenance</span>
                    @endif
                </div>
            </div>
            <div style="padding:0.9rem;">
                <h3 style="font-size:0.92rem;font-weight:700;color:var(--text);margin-bottom:0.2rem;">Kamar {{ $room->room_number }}</h3>
                <p style="font-size:0.72rem;color:var(--muted);margin-bottom:0.5rem;">{{ $room->floor }} • {{ $room->size }} m²</p>
                <p style="font-size:1.05rem;font-weight:800;color:var(--amber);margin-bottom:0.75rem;">
                    Rp {{ number_format($room->price, 0, ',', '.') }}
                    <span style="font-size:0.68rem;font-weight:400;color:var(--muted);">/{{ $room->jenis_sewa === 'tahun' ? 'thn' : 'bln' }}</span>
                </p>
                @if($room->status === 'available')
                    <a href="{{ route('public.kos.room-detail', $room) }}"
                       style="display:block;text-align:center;font-size:0.8rem;font-weight:700;color:#111;background:linear-gradient(135deg,var(--amber),#d97706);padding:0.5rem;border-radius:8px;text-decoration:none;"
                       onmouseover="this.style.boxShadow='0 4px 14px rgba(245,158,11,0.4)'" onmouseout="this.style.boxShadow='none'">Lihat Detail</a>
                @elseif($room->status === 'occupied')
                    <button disabled style="display:block;width:100%;text-align:center;font-size:0.8rem;font-weight:600;color:var(--muted);background:rgba(255,255,255,0.05);border:1px solid var(--border);padding:0.5rem;border-radius:8px;cursor:not-allowed;">Sudah Terisi</button>
                @else
                    <button disabled style="display:block;width:100%;text-align:center;font-size:0.8rem;font-weight:600;color:#fb923c;background:rgba(251,146,60,0.1);border:1px solid rgba(251,146,60,0.2);padding:0.5rem;border-radius:8px;cursor:not-allowed;">Maintenance</button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    <div class="dark-pagination">{{ $rooms->links() }}</div>

    @else
    <div style="text-align:center;padding:4rem 2rem;background:var(--card);border:1px solid var(--border);border-radius:18px;">
        <span class="material-symbols-rounded" style="font-size:56px;color:rgba(255,255,255,0.15);display:block;margin-bottom:1rem;">bed</span>
        <p style="font-weight:600;margin-bottom:0.75rem;">Belum ada kamar yang tersedia</p>
        <a href="{{ route('public.kos.index') }}" style="font-size:0.84rem;color:var(--amber);text-decoration:none;font-weight:600;">Cari Tempat Kos Lain →</a>
    </div>
    @endif

    {{-- Rating & Ulasan --}}
    @if($totalUlasan > 0)
    <section style="margin-top:3.5rem;padding-top:3rem;border-top:1px solid var(--border);">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:2rem;">
            <div style="width:38px;height:38px;background:rgba(245,158,11,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:var(--text);">Rating & Ulasan</h2>
                <p style="font-size:0.8rem;color:var(--muted);">{{ $totalUlasan }} ulasan dari penghuni</p>
            </div>
        </div>

        {{-- Ringkasan --}}
        <div style="display:grid;grid-template-columns:auto 1fr;gap:2.5rem;background:rgba(31,41,55,0.7);border:1px solid var(--border);border-radius:18px;padding:1.75rem;margin-bottom:2rem;">
            <div style="text-align:center;min-width:130px;">
                <div style="font-size:4rem;font-weight:900;color:var(--amber);line-height:1;">{{ number_format($avgRating, 1) }}</div>
                <div style="display:flex;justify-content:center;gap:3px;margin:0.5rem 0;">
                    @for($i = 1; $i <= 5; $i++)
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="{{ $i <= round($avgRating) ? '#f59e0b' : 'rgba(255,255,255,0.15)' }}"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                </div>
                <div style="font-size:0.78rem;color:var(--muted);">dari {{ $totalUlasan }} ulasan</div>
            </div>
            <div style="display:flex;flex-direction:column;justify-content:center;gap:0.55rem;">
                @for($i = 5; $i >= 1; $i--)
                <div style="display:flex;align-items:center;gap:0.65rem;">
                    <span style="font-size:0.75rem;color:var(--muted);width:14px;text-align:right;flex-shrink:0;">{{ $i }}</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="#f59e0b" style="flex-shrink:0;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <div style="flex:1;height:8px;background:rgba(255,255,255,0.06);border-radius:100px;overflow:hidden;">
                        <div style="height:100%;width:{{ $ratingDistribution[$i]['percent'] }}%;background:linear-gradient(90deg,#f59e0b,#d97706);border-radius:100px;"></div>
                    </div>
                    <span style="font-size:0.72rem;color:var(--muted);width:28px;text-align:right;flex-shrink:0;">{{ $ratingDistribution[$i]['count'] }}</span>
                </div>
                @endfor
            </div>
        </div>

        {{-- Kartu Ulasan --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
            @foreach($ulasanList->take(6) as $ulasan)
            <div x-data="{
                    open: false, overflow: false,
                    init() { this.$nextTick(() => { const el = this.$refs.teks; if (el) this.overflow = el.scrollHeight > 64; }); }
                 }" class="ulasan-card">
                <div style="display:flex;align-items:center;gap:2px;margin-bottom:0.6rem;flex-shrink:0;">
                    @for($i = 1; $i <= 5; $i++)
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ $i <= $ulasan->rating ? '#f59e0b' : 'rgba(255,255,255,0.12)' }}"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                    <span style="font-size:0.72rem;font-weight:700;color:var(--amber);margin-left:4px;">{{ number_format($ulasan->rating, 1) }}</span>
                </div>
                <div class="komentar-box" :class="{ 'is-open': open }">
                    <p x-ref="teks" style="font-size:0.82rem;color:var(--muted);line-height:1.6;font-style:italic;">"{{ $ulasan->komentar ?: 'Tidak ada komentar.' }}"</p>
                    <div class="komentar-fade-overlay" x-show="overflow"></div>
                    <span class="komentar-readmore" x-show="overflow" @click="open = true">...selengkapnya</span>
                </div>
                <div x-show="open && overflow" style="margin-top:4px;flex-shrink:0;">
                    <button @click="open = false" style="font-size:0.72rem;font-weight:700;color:var(--amber);background:none;border:none;cursor:pointer;padding:0;font-family:inherit;">↑ Sembunyikan</button>
                </div>
                <div class="ulasan-spacer"></div>
                <div class="ulasan-footer">
                    <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:800;color:#111;flex-shrink:0;">
                        {{ strtoupper(substr($ulasan->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p style="font-size:0.78rem;font-weight:700;color:var(--text);line-height:1.2;">{{ $ulasan->user?->name ?? 'Anonim' }}</p>
                        <p style="font-size:0.68rem;color:var(--muted);">{{ $ulasan->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($ulasanList->count() > 6)
        <p style="text-align:center;font-size:0.82rem;color:var(--muted);margin-top:1.25rem;">Dan {{ $ulasanList->count() - 6 }} ulasan lainnya...</p>
        @endif
    </section>
    @else
    <section style="margin-top:3rem;padding:2rem;background:rgba(31,41,55,0.5);border:1px solid var(--border);border-radius:16px;text-align:center;">
        <svg width="44" height="44" viewBox="0 0 24 24" fill="rgba(255,255,255,0.1)" style="margin:0 auto 0.75rem;"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        <p style="color:var(--muted);font-size:0.87rem;">Belum ada ulasan untuk kos ini.</p>
    </section>
    @endif

    {{-- CTA --}}
    <div class="cta-bar mb-6">
        <p style="font-weight:700;margin-bottom:0.35rem;">Tertarik dengan kamar di sini?</p>
        <p style="font-size:0.84rem;color:var(--muted);margin-bottom:1.25rem;">Login atau daftar untuk bisa langsung booking kamar secara online</p>
        <div style="display:flex;align-items:center;justify-content:center;gap:0.75rem;flex-wrap:wrap;">
            <a href="{{ route('register') }}" style="padding:0.65rem 1.5rem;background:linear-gradient(135deg,var(--amber),#d97706);color:#111;font-weight:800;border-radius:10px;text-decoration:none;font-size:0.86rem;box-shadow:0 4px 14px rgba(245,158,11,0.3);">Daftar Gratis</a>
            <a href="{{ route('login') }}" style="padding:0.65rem 1.5rem;border:1px solid var(--border);color:var(--muted);font-weight:600;border-radius:10px;text-decoration:none;font-size:0.86rem;"
               onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--muted)'">Sudah Punya Akun</a>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function toggleDD(id) {
    document.querySelectorAll('.dropdown').forEach(d => { if (d.id !== id) d.classList.remove('open'); });
    document.getElementById(id).classList.toggle('open');
}
document.addEventListener('click', e => {
    if (!e.target.closest('.dropdown')) document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('open'));
});
</script>
@endpush