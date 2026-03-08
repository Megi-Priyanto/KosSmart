@extends('layouts.public')

@section('title', 'Cari Tempat Kos - KosSmart')

@push('head-scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@push('page-styles')
        /* ── HERO ── */
        .hero-search { padding: 8rem 1.5rem 3.5rem; position: relative; overflow: hidden; text-align: center; }
        .blob { position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.09; pointer-events: none; }
        .search-box { background: rgba(255,255,255,0.97); border: 1px solid rgba(15,23,42,0.08); backdrop-filter: blur(12px); border-radius: 20px; padding: 1.25rem; display: flex; flex-wrap: wrap; gap: 0.75rem; max-width: 720px; margin: 0 auto; box-shadow: 0 24px 60px rgba(15,23,42,0.12); }
        .search-input-wrap { flex: 1; min-width: 200px; position: relative; }
        .search-input-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .search-input { width: 100%; background: rgba(15,23,42,0.04); border: 1px solid rgba(15,23,42,0.08); border-radius: 10px; padding: 0.65rem 0.85rem 0.65rem 2.5rem; color: #1e293b; font-size: 0.87rem; font-family: inherit; outline: none; transition: border-color 0.2s; }
        .search-input::placeholder { color: #94a3b8; }
        .search-input:focus { border-color: rgba(245,158,11,0.5); }
        .search-select { background: rgba(15,23,42,0.04); border: 1px solid rgba(15,23,42,0.08); border-radius: 10px; padding: 0.65rem 0.85rem; color: #1e293b; font-size: 0.87rem; font-family: inherit; outline: none; cursor: pointer; min-width: 150px; }
        .search-select option { background: #ffffff; }
        .btn-search { padding: 0.65rem 1.5rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #ffffff; font-weight: 800; font-size: 0.87rem; border-radius: 10px; border: none; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-family: inherit; transition: all 0.2s; white-space: nowrap; }
        .btn-search:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(245,158,11,0.4); }

        /* ── KOS CARD ── */
        .kos-card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden; transition: all 0.25s; display: flex; flex-direction: column; height: 100%; }
        .kos-card:hover { transform: translateY(-3px); border-color: rgba(245,158,11,0.35); box-shadow: 0 16px 40px rgba(15,23,42,0.1); }
        .card-thumb { height: 144px; position: relative; overflow: hidden; background: linear-gradient(135deg, #e2e8f0, #cbd5e1); flex-shrink: 0; }
        .card-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
        .kos-card:hover .card-thumb img { transform: scale(1.05); }
        .badge-aktif { background: #22c55e; color: #ffffff; font-size: 0.68rem; font-weight: 700; padding: 4px 10px; border-radius: 100px; display: inline-flex; align-items: center; gap: 5px; box-shadow: 0 2px 6px rgba(34,197,94,0.35); }
        .badge-tersedia-img { background: rgba(15,23,42,0.55); backdrop-filter: blur(4px); color: #ffffff; font-size: 0.65rem; font-weight: 700; padding: 3px 9px; border-radius: 100px; }
        .badge-avail { background: rgba(52,211,153,0.12); color: #16a34a; font-size: 0.68rem; font-weight: 700; padding: 3px 9px; border-radius: 100px; }
        .badge-penuh { background: rgba(239,68,68,0.1); color: #ef4444; font-size: 0.68rem; font-weight: 700; padding: 3px 9px; border-radius: 100px; }
        .card-body { padding: 0.85rem 1rem; flex: 1; display: flex; flex-direction: column; }
        .card-title { font-size: 0.92rem; font-weight: 700; color: #1e293b; margin-bottom: 0.2rem; transition: color 0.2s; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .kos-card:hover .card-title { color: #d97706; }
        .card-loc { font-size: 0.75rem; color: #64748b; display: flex; align-items: flex-start; gap: 4px; margin-bottom: 0.6rem; min-height: 2.4rem; }
        .card-meta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem; font-size: 0.78rem; color: #64748b; padding-top: 0.6rem; border-top: 1px solid #f1f5f9; }
        .btn-lihat { display: flex; align-items: center; justify-content: center; gap: 6px; background: linear-gradient(135deg, #f59e0b, #d97706); color: #ffffff; font-weight: 700; font-size: 0.84rem; padding: 0.55rem; border-radius: 8px; text-decoration: none; transition: all 0.2s; margin-top: auto; }
        .btn-lihat:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(245,158,11,0.35); }

        /* ── ULASAN CARD ── */
        .ulasan-card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 1.1rem; transition: border-color 0.2s, box-shadow 0.2s; display: flex; flex-direction: column; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
        .ulasan-card:hover { border-color: #fcd34d; box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .komentar-box { position: relative; overflow: hidden; max-height: 62px; transition: max-height 0.4s ease; flex-shrink: 0; }
        .komentar-box.is-open { max-height: 600px; }
        .komentar-fade-overlay { position: absolute; bottom: 0; left: 0; right: 0; height: 32px; background: linear-gradient(to bottom, transparent, #ffffff); pointer-events: none; transition: opacity 0.3s; }
        .komentar-box.is-open .komentar-fade-overlay { opacity: 0; }
        .komentar-readmore { position: absolute; bottom: 0; right: 0; font-size: 0.72rem; font-weight: 700; color: #d97706; cursor: pointer; background: #ffffff; padding: 0 2px 1px 8px; }
        .komentar-box.is-open .komentar-readmore { display: none; }
        .ulasan-spacer { flex: 1; min-height: 0.5rem; }
        .ulasan-footer { border-top: 1px solid #f3f4f6; padding-top: 0.65rem; margin-top: 0.6rem; display: flex; align-items: center; gap: 0.6rem; flex-shrink: 0; }

        /* ── Hero badge ── */
        .hero-badge-kos { display: inline-flex; align-items: center; gap: 0.45rem; background: rgba(217,119,6,0.08); border: 1px solid rgba(217,119,6,0.22); color: #d97706; font-size: 0.78rem; font-weight: 700; padding: 0.32rem 0.9rem; border-radius: 100px; margin-bottom: 0.75rem; }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp 0.5s ease both; }

        /* ── RESPONSIVE GRID ── */
        .kos-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; align-items: stretch; }
        @media (min-width: 640px)  { .kos-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 768px)  { .kos-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (min-width: 1024px) { .kos-grid { grid-template-columns: repeat(4, 1fr); } }
@endpush

@section('content')

<!-- HERO SEARCH -->
<section class="hero-search">
    <div class="blob" style="width:500px;height:500px;background:#f59e0b;top:-180px;left:-160px;"></div>
    <div class="blob" style="width:380px;height:380px;background:#22c55e;bottom:-100px;right:-120px;"></div>
    <div style="position:relative;z-index:1;">

        {{-- Badge dengan icon sparkle --}}
        <div class="fade-up">
            <span class="hero-badge-kos">
                <span class="material-symbols-rounded" style="font-size:14px!important;color:#d97706;">auto_awesome</span>
                Temukan Hunian Terbaikmu
            </span>
        </div>

        <h1 class="fade-up" style="font-size:clamp(2rem,4.5vw,3.25rem);font-weight:800;line-height:1.15;color:#1e293b;margin-bottom:0.75rem;animation-delay:0.06s;">
            Cari Tempat <em style="font-style:italic;color:#d97706;">Kos Terbaik</em>
        </h1>
        <p class="fade-up" style="color:#64748b;font-size:0.95rem;margin-bottom:2rem;animation-delay:0.12s;">Temukan tempat kos yang sesuai kebutuhanmu</p>

        <form method="GET" action="{{ route('public.kos.index') }}" class="search-box fade-up" style="animation-delay:0.18s;">
            <div class="search-input-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kos, kota, alamat..." class="search-input">
            </div>
            <select name="kota" class="search-select">
                <option value="">Semua Kota</option>
                @foreach($kotaList as $kota)
                    <option value="{{ $kota }}" {{ request('kota') === $kota ? 'selected' : '' }}>{{ $kota }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-search">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari Sekarang
            </button>
        </form>
    </div>
</section>

<!-- LISTING -->
<div style="max-width:1152px;margin:0 auto;padding:0 1.5rem 5rem;position:relative;z-index:1;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <div>
            <h2 style="font-size:1.4rem;font-weight:800;color:#1e293b;">Pilih Tempat Kos</h2>
            <p style="font-size:0.82rem;color:#64748b;margin-top:0.2rem;">{{ $tempatKosList->count() }} tempat kos tersedia</p>
        </div>
        @if(request()->hasAny(['search', 'kota']))
        <a href="{{ route('public.kos.index') }}"
           style="display:flex;align-items:center;gap:6px;font-size:0.82rem;color:#64748b;border:1px solid #e2e8f0;padding:0.4rem 0.85rem;border-radius:8px;text-decoration:none;"
           onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Reset Filter
        </a>
        @endif
    </div>

    @if($tempatKosList->count() > 0)

    <div class="kos-grid">
        @foreach($tempatKosList as $kos)
        <div style="height:100%;">
        <a href="{{ route('public.kos.rooms', $kos) }}" class="kos-card" style="text-decoration:none;">

            {{-- Thumbnail --}}
            <div class="card-thumb">
                @if($kos->logo)
                    <img src="{{ asset('storage/' . $kos->logo) }}" alt="{{ $kos->nama_kos }}">
                @else
                    <div style="width:100%;height:100%;background:linear-gradient(135deg,#fbbf24,#f97316);display:flex;align-items:center;justify-content:center;">
                        <svg width="56" height="56" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                @endif
                <div style="position:absolute;top:10px;right:10px;">
                    <span class="badge-aktif">
                        <span style="width:5px;height:5px;background:#ffffff;border-radius:50%;display:inline-block;animation:pulse 2s infinite;"></span>
                        Aktif
                    </span>
                </div>
                @if($kos->avg_rating)
                <div style="position:absolute;bottom:10px;left:10px;">
                    <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);color:#ffffff;font-size:0.68rem;font-weight:700;padding:3px 8px;border-radius:8px;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="#f59e0b">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        {{ number_format($kos->avg_rating, 1) }}
                    </span>
                </div>
                @endif
            </div>

            {{-- Body --}}
            <div class="card-body">
                <h3 class="card-title">{{ $kos->nama_kos }}</h3>

                @if($kos->avg_rating)
                <div style="display:flex;align-items:center;gap:3px;margin-bottom:0.35rem;">
                    @for($i = 1; $i <= 5; $i++)
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="{{ $i <= round($kos->avg_rating) ? '#f59e0b' : '#e5e7eb' }}">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    @endfor
                    <span style="font-size:0.68rem;color:#64748b;margin-left:3px;">{{ number_format($kos->avg_rating, 1) }} ({{ $kos->total_ulasan }})</span>
                </div>
                @else
                <div style="font-size:0.68rem;color:#94a3b8;margin-bottom:0.35rem;">Belum ada ulasan</div>
                @endif

                <div class="card-loc">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                        {{ $kos->kecamatan ? 'Kec. ' . $kos->kecamatan . ', ' : '' }}{{ $kos->kota }}, {{ $kos->provinsi }}
                    </span>
                </div>

                <div class="card-meta">
                    <span style="display:flex;align-items:center;gap:4px;">
                        <svg width="13" height="13" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span style="font-weight:600;color:#475569;">{{ $kos->total_kamar }} Kamar</span>
                    </span>
                    @if($kos->kamar_tersedia > 0)
                        <span class="badge-avail">{{ $kos->kamar_tersedia }} Tersedia</span>
                    @else
                        <span class="badge-penuh">Penuh</span>
                    @endif
                </div>

                <div class="btn-lihat">
                    Lihat Kamar
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>

        </a>
        </div>
        @endforeach
    </div>

    {{-- ══ ULASAN TERBARU ══ --}}
    @php
        $semuaUlasan = collect();
        foreach($tempatKosList as $kos) {
            foreach($kos->ulasan->take(2) as $u) {
                $u->kos_nama = $kos->nama_kos;
                $semuaUlasan->push($u);
            }
        }
        $semuaUlasan = $semuaUlasan->sortByDesc('created_at')->take(6);
    @endphp

    @if($semuaUlasan->count() > 0)
    <div style="margin-top:4rem;">
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.5rem;">
            <div style="width:38px;height:38px;background:rgba(245,158,11,0.12);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="#f59e0b">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:#1e293b;">Ulasan Terbaru</h2>
                <p style="font-size:0.8rem;color:#64748b;">Pengalaman nyata dari penghuni kos</p>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
            @foreach($semuaUlasan as $ulasan)
            <div x-data="{
                    open: false, overflow: false,
                    init() { this.$nextTick(() => { const el = this.$refs.teks; if (el) this.overflow = el.scrollHeight > 64; }); }
                 }" class="ulasan-card">

                <div style="font-size:0.68rem;font-weight:700;color:#d97706;background:rgba(245,158,11,0.08);display:inline-block;padding:3px 10px;border-radius:100px;margin-bottom:0.65rem;flex-shrink:0;">
                    {{ $ulasan->kos_nama ?? $ulasan->tempatKos?->nama_kos ?? '-' }}
                </div>

                <div style="display:flex;align-items:center;gap:2px;margin-bottom:0.6rem;flex-shrink:0;">
                    @for($i = 1; $i <= 5; $i++)
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ $i <= $ulasan->rating ? '#f59e0b' : '#e5e7eb' }}">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    @endfor
                    <span style="font-size:0.72rem;font-weight:700;color:#d97706;margin-left:4px;">{{ number_format($ulasan->rating, 1) }}</span>
                </div>

                <div class="komentar-box" :class="{ 'is-open': open }">
                    <p x-ref="teks" style="font-size:0.82rem;color:#64748b;line-height:1.6;font-style:italic;">
                        "{{ $ulasan->komentar ?: 'Tidak ada komentar.' }}"
                    </p>
                    <div class="komentar-fade-overlay" x-show="overflow"></div>
                    <span class="komentar-readmore" x-show="overflow" @click="open = true">...selengkapnya</span>
                </div>

                <div x-show="open && overflow" style="margin-top:4px;flex-shrink:0;">
                    <button @click="open = false" style="font-size:0.72rem;font-weight:700;color:#d97706;background:none;border:none;cursor:pointer;padding:0;font-family:inherit;">
                        ↑ Sembunyikan
                    </button>
                </div>

                <div class="ulasan-spacer"></div>

                <div class="ulasan-footer">
                    <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:800;color:#ffffff;flex-shrink:0;">
                        {{ strtoupper(substr($ulasan->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p style="font-size:0.78rem;font-weight:700;color:#1e293b;line-height:1.2;">{{ $ulasan->user?->name ?? 'Anonim' }}</p>
                        <p style="font-size:0.68rem;color:#94a3b8;">{{ $ulasan->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @else
    <div style="text-align:center;padding:5rem 2rem;background:#ffffff;border:1px solid #e2e8f0;border-radius:20px;">
        <svg width="64" height="64" fill="none" stroke="#cbd5e1" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 1rem;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <h3 style="font-size:1.1rem;font-weight:700;color:#475569;margin-bottom:0.5rem;">Tidak Ada Tempat Kos</h3>
        <p style="color:#94a3b8;font-size:0.87rem;">
            @if(request()->hasAny(['search', 'kota'])) Tidak ada hasil untuk pencarian tersebut.
            @else Belum ada tempat kos yang terdaftar. @endif
        </p>
    </div>
    @endif

</div>

@endsection