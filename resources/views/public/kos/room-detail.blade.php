@extends('layouts.public')

@section('title', 'Detail Kamar {{ $room->room_number }} - {{ $tempatKos->nama_kos }} - KosSmart')

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

        /* ── NAVBAR ── */
        nav.topnav{position:fixed;top:0;left:0;right:0;z-index:200;display:flex;align-items:center;justify-content:space-between;padding:0.85rem 2rem;background:rgba(17,24,39,0.9);backdrop-filter:blur(14px);border-bottom:1px solid var(--border);}
        .nav-left{display:flex;align-items:center;gap:1.75rem;}
        .nav-right{display:flex;align-items:center;gap:0.55rem;}
        .nav-logo{display:flex;align-items:center;gap:0.5rem;font-weight:800;font-size:1.15rem;color:var(--text);text-decoration:none;flex-shrink:0;}
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
        .btn-amber-nav{background:linear-gradient(135deg,var(--amber),#d97706);color:#111;}
        .btn-amber-nav:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(245,158,11,0.4);}

        /* ── PANELS ── */
        .panel{background:var(--card);border:1px solid var(--border);border-radius:16px;overflow:hidden;}
        .panel-body{padding:1.5rem;}

        /* ── STAT PILLS ── */
        .quick-stat{text-align:center;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:12px;padding:0.85rem 0.5rem;}
        .qs-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 0.4rem;}

        /* ── BOOKING CARD ── */
        .booking-card{background:var(--card);border:2px solid rgba(245,158,11,0.2);border-radius:18px;padding:1.5rem;position:sticky;top:5.5rem;}
        .dp-box{background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:12px;padding:0.85rem;}
        .btn-booking{display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,var(--amber),#d97706);color:#111;font-weight:800;font-size:0.9rem;padding:0.85rem;border-radius:12px;text-decoration:none;transition:all 0.25s;box-shadow:0 6px 20px rgba(245,158,11,0.3);}
        .btn-booking:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(245,158,11,0.45);}
        .check-row{display:flex;align-items:center;gap:8px;font-size:0.78rem;color:var(--muted);}
        .check-row svg{flex-shrink:0;color:var(--green);}

        /* ── RELATED ── */
        .related-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;text-decoration:none;transition:all 0.2s;display:block;}
        .related-card:hover{border-color:rgba(245,158,11,0.3);transform:translateY(-2px);}

        /* ── FACILITY TAG ── */
        .fac-tag{display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--muted);}
        .fac-tag svg{color:var(--green);flex-shrink:0;}

        footer{border-top:1px solid var(--border);padding:1.5rem 2rem;margin-top:3rem;}
        footer .inner{max-width:1152px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;font-size:0.82rem;color:var(--muted);}
        footer a{color:var(--muted);text-decoration:none;transition:color 0.2s;}
        footer a:hover{color:var(--text);}
    </style>
</head>
<body>

<!-- ══ NAVBAR ══ -->
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
                Masuk
                <span class="material-symbols-rounded dd-chevron">expand_more</span>
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('login') }}" class="dropdown-item">
                    <div class="di-icon" style="background:rgba(245,158,11,0.12);">
                        <span class="material-symbols-rounded" style="color:var(--amber);">person</span>
                    </div>
                    <div>
                        <div style="color:var(--text);font-size:0.83rem;">Sebagai Penghuni</div>
                        <div style="color:var(--muted);font-size:0.71rem;font-weight:400;">Login akun user kos</div>
                    </div>
                </a>
                <div class="dd-divider"></div>
                <a href="{{ route('admin.login') }}" class="dropdown-item">
                    <div class="di-icon" style="background:rgba(52,211,153,0.12);">
                        <span class="material-symbols-rounded" style="color:var(--green);">apartment</span>
                    </div>
                    <div>
                        <div style="color:var(--text);font-size:0.83rem;">Sebagai Admin Kos</div>
                        <div style="color:var(--muted);font-size:0.71rem;font-weight:400;">Kelola tempat kos</div>
                    </div>
                </a>
                <a href="{{ route('superadmin.login') }}" class="dropdown-item">
                    <div class="di-icon" style="background:rgba(167,139,250,0.12);">
                        <span class="material-symbols-rounded" style="color:var(--purple);">shield_person</span>
                    </div>
                    <div>
                        <div style="color:var(--text);font-size:0.83rem;">Sebagai Super Admin</div>
                        <div style="color:var(--muted);font-size:0.71rem;font-weight:400;">Administrator sistem</div>
                    </div>
                </a>
            </div>
        </div>
@endpush

@section('content')
    </div>
</nav>

<div style="max-width:1152px;margin:0 auto;padding:5.5rem 1.5rem 0;position:relative;z-index:1;">

    <!-- Breadcrumb -->
    <nav style="display:flex;align-items:center;gap:8px;font-size:0.8rem;color:var(--muted);margin-bottom:1.5rem;flex-wrap:wrap;">
        <a href="{{ route('public.kos.index') }}" style="color:var(--muted);text-decoration:none;"
           onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Daftar Kos</a>
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('public.kos.rooms', $tempatKos) }}" style="color:var(--muted);text-decoration:none;"
           onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">{{ $tempatKos->nama_kos }}</a>
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span style="color:var(--text);font-weight:600;">Kamar {{ $room->room_number }}</span>
    </nav>

    <!-- Grid Layout -->
    <div style="display:grid;grid-template-columns:1fr 320px;gap:1.5rem;align-items:start;">

        <!-- LEFT COLUMN -->
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            <!-- Galeri Foto -->
            <div class="panel">
                @php
                    $images = is_array($room->images) ? $room->images : json_decode($room->images, true);
                @endphp

                @if(!empty($images))
                <div x-data="{ images: {{ json_encode($images) }}, current: 0 }">
                    <div style="aspect-ratio:16/9;background:#1f2937;position:relative;" class="group">
                        <img :src="'{{ asset('storage') }}/' + images[current]"
                             alt="Kamar {{ $room->room_number }}"
                             style="width:100%;height:100%;object-fit:cover;">
                        <template x-if="images.length > 1">
                            <div>
                                <button @click="current = (current - 1 + images.length) % images.length"
                                        style="position:absolute;left:12px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,0.6);color:white;padding:8px;border-radius:50%;border:none;cursor:pointer;backdrop-filter:blur(4px);">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button @click="current = (current + 1) % images.length"
                                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,0.6);color:white;padding:8px;border-radius:50%;border:none;cursor:pointer;backdrop-filter:blur(4px);">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                                <div style="position:absolute;bottom:12px;right:12px;background:rgba(0,0,0,0.65);color:white;font-size:0.72rem;padding:4px 10px;border-radius:100px;backdrop-filter:blur(4px);">
                                    <span x-text="current + 1"></span> / <span x-text="images.length"></span>
                                </div>
                            </div>
                        </template>
                        <div style="position:absolute;top:12px;left:12px;">
                            <span style="background:rgba(52,211,153,0.85);color:#064e3b;font-size:0.68rem;font-weight:700;padding:4px 10px;border-radius:100px;">✓ Tersedia</span>
                        </div>
                    </div>
                    <!-- Thumbnails -->
                    <div style="padding:0.75rem;background:rgba(17,24,39,0.6);display:flex;gap:8px;overflow-x:auto;">
                        <template x-for="(img, i) in images" :key="i">
                            <div @click="current = i"
                                 style="width:60px;height:60px;flex-shrink:0;border-radius:8px;overflow:hidden;cursor:pointer;transition:all 0.2s;"
                                 :style="current === i ? 'border:2px solid var(--amber)' : 'border:2px solid transparent;opacity:0.6'">
                                <img :src="'{{ asset('storage') }}/' + img" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </template>
                    </div>
                </div>
                @else
                <div style="aspect-ratio:16/9;background:linear-gradient(135deg,#1f2937,#374151);display:flex;align-items:center;justify-content:center;">
                    <span class="material-symbols-rounded" style="font-size:72px;color:rgba(255,255,255,0.15);">image</span>
                </div>
                @endif
            </div>

            <!-- Info Kamar -->
            <div class="panel">
                <div class="panel-body">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.75rem;">
                        <div>
                            <h1 style="font-size:1.5rem;font-weight:800;margin-bottom:0.25rem;">Kamar {{ $room->room_number }}</h1>
                            <p style="font-size:0.82rem;color:var(--muted);">{{ $room->floor }} • {{ $room->size }} m²</p>
                        </div>
                        <div style="text-align:right;">
                            <p style="font-size:1.6rem;font-weight:800;color:var(--amber);">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                            <p style="font-size:0.72rem;color:var(--muted);">{{ $room->jenis_sewa === 'tahun' ? 'per tahun' : 'per bulan' }}</p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.75rem;padding:1rem 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);margin-bottom:1.25rem;">
                        <div class="quick-stat">
                            <div class="qs-icon" style="background:rgba(245,158,11,0.1);">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--amber);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <p style="font-size:0.68rem;color:var(--muted);">Kapasitas</p>
                            <p style="font-size:0.85rem;font-weight:700;margin-top:2px;">{{ $room->capacity }} Orang</p>
                        </div>
                        <div class="quick-stat">
                            <div class="qs-icon" style="background:rgba(96,165,250,0.1);">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#60a5fa;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"/></svg>
                            </div>
                            <p style="font-size:0.68rem;color:var(--muted);">Tipe</p>
                            <p style="font-size:0.85rem;font-weight:700;margin-top:2px;">{{ ucfirst($room->type) }}</p>
                        </div>
                        <div class="quick-stat">
                            <div class="qs-icon" style="background:rgba(167,139,250,0.1);">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--purple);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p style="font-size:0.68rem;color:var(--muted);">Sewa</p>
                            <p style="font-size:0.85rem;font-weight:700;margin-top:2px;">{{ $room->jenis_sewa_label }}</p>
                        </div>
                        <div class="quick-stat">
                            <div class="qs-icon" style="background:rgba(52,211,153,0.1);">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--green);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                            <p style="font-size:0.68rem;color:var(--muted);">Jendela</p>
                            <p style="font-size:0.85rem;font-weight:700;margin-top:2px;">{{ $room->has_window ? 'Ada' : 'Tidak' }}</p>
                        </div>
                    </div>

                    @if($room->description)
                    <div style="margin-bottom:1.25rem;">
                        <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:0.5rem;">Deskripsi</h3>
                        <p style="font-size:0.84rem;color:var(--muted);line-height:1.75;">{{ $room->description }}</p>
                    </div>
                    @endif

                    @php
                        $facilities = is_array($room->facilities) ? $room->facilities : json_decode($room->facilities, true);
                    @endphp
                    @if(!empty($facilities))
                    <div>
                        <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:0.75rem;">Fasilitas Kamar</h3>
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:0.6rem;">
                            @foreach($facilities as $f)
                            <div class="fac-tag">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $f }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Info Kos -->
            @if($kosInfo)
            <div style="background:linear-gradient(135deg,rgba(245,158,11,0.12),rgba(217,119,6,0.08));border:1px solid rgba(245,158,11,0.2);border-radius:16px;padding:1.25rem;">
                <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:0.6rem;display:flex;align-items:center;gap:6px;color:var(--amber2);">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                    Tentang {{ $tempatKos->nama_kos }}
                </h3>
                <p style="font-size:0.84rem;color:var(--muted);margin-bottom:{{ $tempatKos->telepon ? '0.4rem' : '0' }};">{{ $tempatKos->alamat }}, {{ $tempatKos->kota }}, {{ $tempatKos->provinsi }}</p>
                @if($tempatKos->telepon)
                <p style="font-size:0.84rem;color:var(--muted);">📞 {{ $tempatKos->telepon }}</p>
                @endif
            </div>
            @endif

            <!-- Kamar Lain -->
            @if($relatedRooms->count() > 0)
            <div class="panel mb-6">
                <div class="panel-body">
                    <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:1rem;">Kamar Lain yang Tersedia</h3>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:0.75rem;">
                        @foreach($relatedRooms as $r)
                        <a href="{{ route('public.kos.room-detail', $r) }}" class="related-card">
                            @php $ri = is_array($r->images) ? $r->images : json_decode($r->images, true); @endphp
                            @if(!empty($ri))
                                <div style="height:76px;overflow:hidden;">
                                    <img src="{{ asset('storage/' . $ri[0]) }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s;">
                                </div>
                            @else
                                <div style="height:76px;background:linear-gradient(135deg,#1f2937,#374151);display:flex;align-items:center;justify-content:center;">
                                    <span class="material-symbols-rounded" style="font-size:28px;color:rgba(255,255,255,0.2);">bed</span>
                                </div>
                            @endif
                            <div style="padding:0.6rem;">
                                <p style="font-size:0.8rem;font-weight:700;color:var(--text);">Kamar {{ $r->room_number }}</p>
                                <p style="font-size:0.72rem;color:var(--amber);font-weight:700;">Rp {{ number_format($r->price, 0, ',', '.') }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- RIGHT COLUMN: Booking Card -->
        <div>
            <div class="booking-card">
                <div style="margin-bottom:1.25rem;">
                    <p style="font-size:0.78rem;color:var(--muted);margin-bottom:0.25rem;">Harga Sewa</p>
                    <p style="font-size:2rem;font-weight:800;color:var(--amber);">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                    <p style="font-size:0.72rem;color:var(--muted);margin-top:2px;">{{ $room->jenis_sewa === 'tahun' ? 'per tahun' : 'per bulan' }}</p>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <div class="dp-box" style="margin-bottom:0.85rem;">
                        <p style="font-size:0.72rem;font-weight:700;color:var(--amber2);margin-bottom:4px;">DP yang dibutuhkan (50%)</p>
                        <p style="font-size:1.25rem;font-weight:800;color:var(--amber);">Rp {{ number_format($room->price * 0.5, 0, ',', '.') }}</p>
                    </div>

                    <a href="{{ route('login') }}?intended={{ urlencode(route('user.rooms.show', $room->id)) }}"
                       class="btn-booking" style="margin-bottom:0.7rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Booking Sekarang
                    </a>

                    <p style="font-size:0.72rem;text-align:center;color:var(--muted);">
                        Perlu login untuk booking.
                        <a href="{{ route('register') }}" style="color:var(--amber);font-weight:600;text-decoration:none;">Daftar gratis →</a>
                    </p>
                </div>

                <div style="display:flex;flex-direction:column;gap:0.5rem;padding-top:1rem;border-top:1px solid var(--border);">
                    <div class="check-row">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Kamar tersedia untuk booking
                    </div>
                    <div class="check-row">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Booking mudah & cepat
                    </div>
                    <div class="check-row">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Pembayaran fleksibel
                    </div>
                </div>
            </div>

            <!-- Register Prompt -->
            <div style="background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.15);border-radius:14px;padding:1.1rem;margin-top:0.85rem;">
                <h4 style="font-size:0.86rem;font-weight:700;margin-bottom:0.35rem;">Belum punya akun?</h4>
                <p style="font-size:0.75rem;color:var(--muted);margin-bottom:0.85rem;">Daftar gratis dan langsung bisa booking kamar favoritmu</p>
                <a href="{{ route('register') }}"
                   style="display:block;text-align:center;font-size:0.84rem;font-weight:700;color:#111;background:linear-gradient(135deg,var(--amber),#d97706);padding:0.6rem;border-radius:10px;text-decoration:none;">
                    Daftar Sekarang →
                </a>
            </div>
        </div>
    </div>
</div>

@endsection