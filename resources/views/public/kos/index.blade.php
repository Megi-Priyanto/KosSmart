@extends('layouts.public')

@section('title', 'Cari Tempat Kos - KosSmart')

@push('head-scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@push('page-styles')
        }
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);overflow-x:hidden;}
        body::before{content:'';position:fixed;inset:0;pointer-events:none;z-index:0;
            background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");}
        .material-symbols-rounded{font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;vertical-align:middle;line-height:1;}
        nav.topnav{position:fixed;top:0;left:0;right:0;z-index:200;display:flex;align-items:center;justify-content:space-between;padding:0.85rem 2rem;background:rgba(17,24,39,0.9);backdrop-filter:blur(14px);border-bottom:1px solid var(--border);}
        .nav-left{display:flex;align-items:center;gap:1.75rem;}
        .nav-right{display:flex;align-items:center;gap:0.55rem;}
        .nav-logo{display:flex;align-items:center;gap:0.5rem;font-weight:800;font-size:1.15rem;color:var(--text);text-decoration:none;}
        .nav-logo-icon{width:32px;height:32px;background:linear-gradient(135deg,var(--amber),#d97706);border-radius:8px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(245,158,11,0.35);}
        .nav-link{color:var(--muted);font-size:0.86rem;font-weight:600;text-decoration:none;transition:color 0.2s;white-space:nowrap;}
        .nav-link:hover{color:var(--text);}
        .nav-link.active{color:var(--amber);}
        .dropdown{position:relative;}
        .dropdown-toggle{display:flex;align-items:center;gap:0.25rem;color:var(--text);font-size:0.84rem;font-weight:700;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:8px;cursor:pointer;padding:0.42rem 0.9rem;font-family:inherit;transition:all 0.2s;}
        .dropdown-toggle:hover{background:rgba(255,255,255,0.08);}
        .dd-chevron{font-size:18px!important;transition:transform 0.2s;}
        .dropdown.open .dd-chevron{transform:rotate(180deg);}
        .dropdown-menu{display:none;position:absolute;top:calc(100% + 10px);right:0;background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:0.45rem;min-width:215px;box-shadow:0 20px 50px rgba(0,0,0,0.55);}
        .dropdown.open .dropdown-menu{display:block;animation:ddFade 0.15s ease;}
        @keyframes ddFade{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
        .dropdown-item{display:flex;align-items:center;gap:0.7rem;padding:0.6rem 0.85rem;border-radius:9px;color:var(--muted);font-size:0.84rem;font-weight:600;text-decoration:none;transition:all 0.15s;}
        .dropdown-item:hover{background:rgba(255,255,255,0.06);color:var(--text);}
        .di-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .di-icon .material-symbols-rounded{font-size:17px!important;}
        .dd-divider{height:1px;background:var(--border);margin:0.3rem 0;}
        .btn-nav{padding:0.42rem 1rem;border-radius:8px;font-size:0.84rem;font-weight:700;text-decoration:none;transition:all 0.2s;}
        .btn-amber{background:linear-gradient(135deg,var(--amber),#d97706);color:#111;}
        .btn-amber:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(245,158,11,0.4);}
        .hero-search{padding:8rem 1.5rem 3.5rem;position:relative;overflow:hidden;text-align:center;}
        .blob{position:absolute;border-radius:50%;filter:blur(80px);opacity:0.12;pointer-events:none;}
        .search-box{background:rgba(31,41,55,0.85);border:1px solid var(--border);backdrop-filter:blur(12px);border-radius:20px;padding:1.25rem;display:flex;flex-wrap:wrap;gap:0.75rem;max-width:720px;margin:0 auto;box-shadow:0 24px 60px rgba(0,0,0,0.4);}
        .search-input-wrap{flex:1;min-width:200px;position:relative;}
        .search-input-wrap svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);}
        .search-input{width:100%;background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:10px;padding:0.65rem 0.85rem 0.65rem 2.5rem;color:var(--text);font-size:0.87rem;font-family:inherit;outline:none;transition:border-color 0.2s;}
        .search-input::placeholder{color:var(--muted);}
        .search-input:focus{border-color:rgba(245,158,11,0.5);}
        .search-select{background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:10px;padding:0.65rem 0.85rem;color:var(--text);font-size:0.87rem;font-family:inherit;outline:none;cursor:pointer;min-width:150px;}
        .search-select option{background:var(--surface);}
        .btn-search{padding:0.65rem 1.5rem;background:linear-gradient(135deg,var(--amber),#d97706);color:#111;font-weight:800;font-size:0.87rem;border-radius:10px;border:none;cursor:pointer;display:flex;align-items:center;gap:0.5rem;font-family:inherit;transition:all 0.2s;white-space:nowrap;}
        .btn-search:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(245,158,11,0.4);}
        .kos-card{background:var(--card);border:1px solid var(--border);border-radius:18px;overflow:hidden;transition:all 0.25s;display:flex;flex-direction:column;}
        .kos-card:hover{transform:translateY(-4px);border-color:rgba(245,158,11,0.3);box-shadow:0 20px 50px rgba(0,0,0,0.4);}
        .card-thumb{height:168px;position:relative;overflow:hidden;}
        .card-thumb img{width:100%;height:100%;object-fit:cover;transition:transform 0.35s;}
        .kos-card:hover .card-thumb img{transform:scale(1.05);}
        .badge-aktif{background:rgba(52,211,153,0.15);color:var(--green);border:1px solid rgba(52,211,153,0.3);font-size:0.68rem;font-weight:700;padding:3px 9px;border-radius:100px;display:inline-flex;align-items:center;gap:4px;}
        .badge-tersedia{background:rgba(255,255,255,0.1);backdrop-filter:blur(6px);color:var(--text);font-size:0.68rem;font-weight:700;padding:3px 9px;border-radius:100px;}
        .card-body{padding:1.1rem;flex:1;display:flex;flex-direction:column;}
        .card-title{font-size:1rem;font-weight:700;color:var(--text);margin-bottom:0.3rem;transition:color 0.2s;}
        .kos-card:hover .card-title{color:var(--amber);}
        .card-loc{font-size:0.78rem;color:var(--muted);display:flex;align-items:flex-start;gap:4px;margin-bottom:0.8rem;}
        .card-meta{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.9rem;font-size:0.78rem;color:var(--muted);}
        .badge-penuh{background:rgba(239,68,68,0.12);color:#f87171;font-size:0.68rem;font-weight:700;padding:3px 9px;border-radius:100px;}
        .badge-avail{background:rgba(52,211,153,0.12);color:var(--green);font-size:0.68rem;font-weight:700;padding:3px 9px;border-radius:100px;}
        .btn-lihat{display:flex;align-items:center;justify-content:center;gap:6px;background:linear-gradient(135deg,var(--amber),#d97706);color:#111;font-weight:700;font-size:0.84rem;padding:0.6rem;border-radius:10px;text-decoration:none;transition:all 0.2s;margin-top:auto;}
        .btn-lihat:hover{transform:translateY(-1px);box-shadow:0 6px 16px rgba(245,158,11,0.35);}
        .cta-section{background:rgba(31,41,55,0.6);border:1px solid var(--border);border-radius:20px;padding:2.5rem;text-align:center;margin-top:4rem;}
        @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp 0.5s ease both;}

        /* ══════════════════════════════════════════════
           ULASAN CARD — FIXED HEIGHT + FADE + EXPAND
        ══════════════════════════════════════════════ */
        .ulasan-card {
            background: rgba(31,41,55,0.7);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.1rem;
            transition: border-color 0.2s;
            display: flex;
            flex-direction: column;
        }
        .ulasan-card:hover { border-color: rgba(245,158,11,0.25); }

        /* Batas teks = 3 baris */
        .komentar-box {
            position: relative;
            overflow: hidden;
            /* 3 baris × line-height 1.6 × ~13px font ≈ 62px */
            max-height: 62px;
            transition: max-height 0.4s ease;
            flex-shrink: 0;
        }
        .komentar-box.is-open {
            max-height: 600px; /* cukup besar untuk teks apapun */
        }

        /* Gradient fade di ujung teks */
        .komentar-fade-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 32px;
            background: linear-gradient(to bottom, transparent, #1e2a3a);
            pointer-events: none;
            transition: opacity 0.3s;
        }
        .komentar-box.is-open .komentar-fade-overlay {
            opacity: 0;
        }

        /* "...selengkapnya" melekat di pojok kanan bawah fade */
        .komentar-readmore {
            position: absolute;
            bottom: 0; right: 0;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--amber);
            cursor: pointer;
            background: #1e2a3a;
            padding: 0 2px 1px 8px;
            transition: opacity 0.3s;
        }
        .komentar-box.is-open .komentar-readmore {
            display: none;
        }

        /* Spacer dorong user-info ke bawah */
        .ulasan-spacer { flex: 1; min-height: 0.5rem; }

        /* User info — tidak bergerak, selalu di bawah */
        .ulasan-footer {
            border-top: 1px solid var(--border);
            padding-top: 0.65rem;
            margin-top: 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-shrink: 0;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="topnav">
    <div class="nav-left">
        <a href="{{ route('home') }}" class="nav-logo">
            <div class="nav-logo-icon">
                <span class="material-symbols-rounded" style="font-size:18px!important;color:#111;font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 20;">home</span>
            </div>
            KosSmart
        </a>
        <a href="{{ route('home') }}" class="nav-link">Beranda</a>
        <a href="{{ route('tentang') }}" class="nav-link">Tentang Kami</a>
        <a href="{{ route('public.kos.index') }}" class="nav-link active">Cari Kos</a>
    </div>
    <div class="nav-right">
        <div class="dropdown" id="dd-masuk">
            <button class="dropdown-toggle" onclick="toggleDD('dd-masuk')">
                Masuk <span class="material-symbols-rounded dd-chevron">expand_more</span>
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('login') }}" class="dropdown-item">
                    <div class="di-icon" style="background:rgba(245,158,11,0.12);"><span class="material-symbols-rounded" style="color:var(--amber);">person</span></div>
                    <div><div style="color:var(--text);font-size:0.83rem;">Sebagai Penghuni</div><div style="color:var(--muted);font-size:0.71rem;font-weight:400;">Login akun user kos</div></div>
                </a>
                <div class="dd-divider"></div>
                <a href="{{ route('admin.login') }}" class="dropdown-item">
                    <div class="di-icon" style="background:rgba(52,211,153,0.12);"><span class="material-symbols-rounded" style="color:var(--green);">apartment</span></div>
                    <div><div style="color:var(--text);font-size:0.83rem;">Sebagai Admin Kos</div><div style="color:var(--muted);font-size:0.71rem;font-weight:400;">Kelola tempat kos</div></div>
                </a>
                <a href="{{ route('superadmin.login') }}" class="dropdown-item">
                    <div class="di-icon" style="background:rgba(167,139,250,0.12);"><span class="material-symbols-rounded" style="color:var(--purple);">shield_person</span></div>
                    <div><div style="color:var(--text);font-size:0.83rem;">Sebagai Super Admin</div><div style="color:var(--muted);font-size:0.71rem;font-weight:400;">Administrator sistem</div></div>
                </a>
            </div>
        </div>
        <a href="{{ route('register') }}" class="btn-nav btn-amber">Daftar Gratis</a>
    </div>
</nav>
@endpush

@section('content')
<!-- HERO SEARCH -->
<section class="hero-search">
    <div class="blob" style="width:500px;height:500px;background:var(--amber);top:-180px;left:-160px;"></div>
    <div class="blob" style="width:380px;height:380px;background:var(--green);bottom:-100px;right:-120px;"></div>
    <div style="position:relative;z-index:1;">
        <p class="fade-up" style="font-size:0.78rem;font-weight:700;color:var(--amber);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:0.75rem;">Temukan Hunian Terbaikmu</p>
        <h1 class="fade-up" style="font-size:clamp(2rem,4.5vw,3.25rem);font-weight:800;line-height:1.15;margin-bottom:0.75rem;animation-delay:0.06s;">
            Cari Tempat <em style="font-style:italic;font-family:'Fraunces',serif;color:var(--amber);">Kos Terbaik</em>
        </h1>
        <p class="fade-up" style="color:var(--muted);font-size:0.95rem;margin-bottom:2rem;animation-delay:0.12s;">Temukan tempat kos yang sesuai kebutuhanmu</p>
        <form method="GET" action="{{ route('public.kos.index') }}" class="search-box fade-up" style="animation-delay:0.18s;">
            <div class="search-input-wrap">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kos, kota, alamat..." class="search-input">
            </div>
            <select name="kota" class="search-select">
                <option value="">Semua Kota</option>
                @foreach($kotaList as $kota)
                    <option value="{{ $kota }}" {{ request('kota') === $kota ? 'selected' : '' }}>{{ $kota }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-search">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari Sekarang
            </button>
        </form>
    </div>
</section>

<!-- LISTING -->
<div style="max-width:1152px;margin:0 auto;padding:0 1.5rem 5rem;position:relative;z-index:1;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;">
        <div>
            <h2 style="font-size:1.4rem;font-weight:800;color:var(--text);">Pilih Tempat Kos</h2>
            <p style="font-size:0.82rem;color:var(--muted);margin-top:0.25rem;">{{ $tempatKosList->count() }} tempat kos tersedia</p>
        </div>
        @if(request()->hasAny(['search', 'kota']))
        <a href="{{ route('public.kos.index') }}" style="display:flex;align-items:center;gap:6px;font-size:0.82rem;color:var(--muted);border:1px solid var(--border);padding:0.4rem 0.85rem;border-radius:8px;text-decoration:none;"
           onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--muted)'">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Reset Filter
        </a>
        @endif
    </div>

    @if($tempatKosList->count() > 0)
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(290px,1fr));gap:1.5rem;">
        @foreach($tempatKosList as $kos)
        <div class="kos-card">
            <div class="card-thumb" style="background:linear-gradient(135deg,#1f2937,#374151);">
                @if($kos->logo)
                    <img src="{{ asset('storage/' . $kos->logo) }}" alt="{{ $kos->nama_kos }}">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                        <span class="material-symbols-rounded" style="font-size:56px;color:rgba(255,255,255,0.2);">home</span>
                    </div>
                @endif
                <div style="position:absolute;top:10px;left:10px;">
                    <span class="badge-aktif"><span style="width:5px;height:5px;background:var(--green);border-radius:50%;display:inline-block;"></span>Aktif</span>
                </div>
                <div style="position:absolute;top:10px;right:10px;">
                    <span class="badge-tersedia">{{ $kos->kamar_tersedia }} Tersedia</span>
                </div>
                @if($kos->avg_rating)
                <div style="position:absolute;bottom:10px;left:10px;">
                    <span style="display:inline-flex;align-items:center;gap:4px;background:rgba(0,0,0,0.65);backdrop-filter:blur(4px);color:#fff;font-size:0.7rem;font-weight:700;padding:3px 8px;border-radius:8px;">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        {{ number_format($kos->avg_rating, 1) }}
                    </span>
                </div>
                @endif
            </div>
            <div class="card-body">
                <h3 class="card-title">{{ $kos->nama_kos }}</h3>
                @if($kos->avg_rating)
                <div style="display:flex;align-items:center;gap:3px;margin-bottom:0.45rem;">
                    @for($i = 1; $i <= 5; $i++)
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="{{ $i <= round($kos->avg_rating) ? '#f59e0b' : 'rgba(255,255,255,0.15)' }}"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                    <span style="font-size:0.72rem;color:var(--muted);margin-left:4px;">{{ number_format($kos->avg_rating, 1) }} ({{ $kos->total_ulasan }} ulasan)</span>
                </div>
                @else
                <div style="font-size:0.72rem;color:var(--muted);margin-bottom:0.45rem;">Belum ada ulasan</div>
                @endif
                <div class="card-loc">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $kos->kecamatan ? $kos->kecamatan . ', ' : '' }}{{ $kos->kota }}, {{ $kos->provinsi }}
                </div>
                <div class="card-meta">
                    <span style="display:flex;align-items:center;gap:4px;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        {{ $kos->total_kamar }} Kamar
                    </span>
                    @if($kos->kamar_tersedia > 0)
                        <span class="badge-avail">{{ $kos->kamar_tersedia }} Tersedia</span>
                    @else
                        <span class="badge-penuh">Penuh</span>
                    @endif
                </div>
                <a href="{{ route('public.kos.rooms', $kos) }}" class="btn-lihat">
                    Lihat Kamar
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
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
                <svg width="18" height="18" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <div>
                <h2 style="font-size:1.25rem;font-weight:800;color:var(--text);">Ulasan Terbaru</h2>
                <p style="font-size:0.8rem;color:var(--muted);">Pengalaman nyata dari penghuni kos</p>
            </div>
        </div>

        {{-- GRID ULASAN --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
            @foreach($semuaUlasan as $ulasan)
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
                <div style="font-size:0.7rem;font-weight:700;color:var(--amber);background:rgba(245,158,11,0.08);display:inline-block;padding:3px 10px;border-radius:100px;margin-bottom:0.65rem;flex-shrink:0;">
                    {{ $ulasan->kos_nama ?? $ulasan->tempatKos?->nama_kos ?? '-' }}
                </div>

                {{-- Bintang --}}
                <div style="display:flex;align-items:center;gap:2px;margin-bottom:0.6rem;flex-shrink:0;">
                    @for($i = 1; $i <= 5; $i++)
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="{{ $i <= $ulasan->rating ? '#f59e0b' : 'rgba(255,255,255,0.12)' }}"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                    <span style="font-size:0.72rem;font-weight:700;color:var(--amber);margin-left:4px;">{{ number_format($ulasan->rating, 1) }}</span>
                </div>

                {{-- ── KOMENTAR: fixed height + fade + ...selengkapnya ── --}}
                <div class="komentar-box" :class="{ 'is-open': open }">
                    <p x-ref="teks"
                       style="font-size:0.82rem;color:var(--muted);line-height:1.6;font-style:italic;">
                        "{{ $ulasan->komentar ?: 'Tidak ada komentar.' }}"
                    </p>
                    {{-- fade overlay --}}
                    <div class="komentar-fade-overlay" x-show="overflow"></div>
                    {{-- ...selengkapnya --}}
                    <span class="komentar-readmore"
                          x-show="overflow"
                          @click="open = true">
                        ...selengkapnya
                    </span>
                </div>

                {{-- Sembunyikan (muncul setelah open) --}}
                <div x-show="open && overflow" style="margin-top:4px;flex-shrink:0;">
                    <button @click="open = false"
                            style="font-size:0.72rem;font-weight:700;color:var(--amber);background:none;border:none;cursor:pointer;padding:0;font-family:inherit;">
                        ↑ Sembunyikan
                    </button>
                </div>

                {{-- Spacer — dorong user info ke bawah --}}
                <div class="ulasan-spacer"></div>

                {{-- User info — SELALU di paling bawah card --}}
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
    </div>
    @endif

    @else
    <div style="text-align:center;padding:5rem 2rem;background:var(--card);border:1px solid var(--border);border-radius:20px;">
        <span class="material-symbols-rounded" style="font-size:64px;color:rgba(255,255,255,0.15);display:block;margin-bottom:1rem;">home</span>
        <h3 style="font-size:1.15rem;font-weight:700;margin-bottom:0.5rem;">Tidak Ada Tempat Kos</h3>
        <p style="color:var(--muted);font-size:0.87rem;">
            @if(request()->hasAny(['search', 'kota'])) Tidak ada hasil untuk pencarian tersebut.
            @else Belum ada tempat kos yang terdaftar. @endif
        </p>
    </div>
    @endif

    <!-- CTA -->
    <div class="cta-section">
        <h3 style="font-size:1.25rem;font-weight:800;margin-bottom:0.5rem;">Suka dengan pilihan yang ada?</h3>
        <p style="color:var(--muted);font-size:0.87rem;margin-bottom:1.75rem;">Daftar atau masuk untuk bisa melakukan booking kamar secara online</p>
        <div style="display:flex;align-items:center;justify-content:center;gap:0.75rem;flex-wrap:wrap;">
            <a href="{{ route('register') }}" style="padding:0.75rem 1.75rem;background:linear-gradient(135deg,var(--amber),#d97706);color:#111;font-weight:800;border-radius:12px;text-decoration:none;font-size:0.9rem;box-shadow:0 6px 20px rgba(245,158,11,0.3);">Daftar Gratis Sekarang</a>
            <a href="{{ route('login') }}" style="padding:0.75rem 1.75rem;border:1px solid var(--border);color:var(--muted);font-weight:600;border-radius:12px;text-decoration:none;font-size:0.9rem;"
               onmouseover="this.style.color='var(--text)';this.style.borderColor='rgba(255,255,255,0.2)'"
               onmouseout="this.style.color='var(--muted)';this.style.borderColor='var(--border)'">Sudah Punya Akun</a>
        </div>
    </div>
</div>

@endsection