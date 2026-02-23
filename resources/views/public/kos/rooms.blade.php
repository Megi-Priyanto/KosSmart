<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kamar {{ $tempatKos->nama_kos }} - KosSmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        :root {
            --bg:#111827; --surface:#1f2937; --card:#243040;
            --amber:#f59e0b; --amber2:#fbbf24; --green:#34d399; --purple:#a78bfa;
            --text:#f3f4f6; --muted:#9ca3af; --border:rgba(255,255,255,0.07);
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
        .btn-amber{background:linear-gradient(135deg,var(--amber),#d97706);color:#111;}
        .btn-amber:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(245,158,11,0.4);}

        /* ── ROOM CARD ── */
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

        /* ── FILTER BOX ── */
        .filter-box{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:1rem 1.25rem;}
        .filter-label{font-size:0.72rem;font-weight:700;color:var(--amber);margin-bottom:6px;display:block;}
        .filter-btn{display:flex;align-items:center;justify-content:space-between;gap:8px;background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:9px;padding:0.5rem 0.85rem;font-size:0.82rem;color:var(--text);cursor:pointer;font-family:inherit;min-width:130px;transition:border-color 0.2s;}
        .filter-btn:hover{border-color:rgba(245,158,11,0.4);}
        .filter-dd{background:var(--surface);border:1px solid var(--border);border-radius:10px;overflow:hidden;box-shadow:0 12px 30px rgba(0,0,0,0.5);}
        .filter-dd-item{padding:0.5rem 0.85rem;font-size:0.82rem;color:var(--muted);cursor:pointer;transition:all 0.15s;}
        .filter-dd-item:hover{background:rgba(255,255,255,0.06);color:var(--text);}

        /* ── KOS HEADER ── */
        .kos-header{background:linear-gradient(135deg,rgba(245,158,11,0.15),rgba(217,119,6,0.1));border:1px solid rgba(245,158,11,0.2);border-radius:18px;padding:1.5rem;}
        .stat-box{text-align:center;padding:0.75rem;background:rgba(255,255,255,0.04);border:1px solid var(--border);border-radius:12px;}

        /* ── CTA ── */
        .cta-bar{background:rgba(31,41,55,0.6);border:1px solid var(--border);border-radius:16px;padding:1.75rem;text-align:center;margin-top:2rem;}

        footer{border-top:1px solid var(--border);padding:1.5rem 2rem;margin-top:3rem;}
        footer .inner{max-width:1152px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;font-size:0.82rem;color:var(--muted);}
        footer a{color:var(--muted);text-decoration:none;transition:color 0.2s;}
        footer a:hover{color:var(--text);}

        /* Pagination override */
        .dark-pagination nav span, .dark-pagination nav a{
            background:var(--card) !important;border-color:var(--border) !important;color:var(--muted) !important;
        }
        .dark-pagination nav a:hover, .dark-pagination nav span[aria-current]{
            background:rgba(245,158,11,0.15) !important;color:var(--amber) !important;
        }
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
        <a href="{{ route('public.kos.index') }}" class="nav-link active">Kos</a>
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
        <a href="{{ route('register') }}" class="btn-nav btn-amber">Daftar Gratis</a>
    </div>
</nav>

<div style="max-width:1152px;margin:0 auto;padding:5.5rem 1.5rem 0;position:relative;z-index:1;">

    <!-- Breadcrumb -->
    <nav style="display:flex;align-items:center;gap:8px;font-size:0.8rem;color:var(--muted);margin-bottom:1.25rem;">
        <a href="{{ route('public.kos.index') }}" style="color:var(--muted);text-decoration:none;transition:color 0.2s;"
           onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Daftar Kos</a>
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span style="color:var(--text);font-weight:600;">{{ $tempatKos->nama_kos }}</span>
    </nav>

    <!-- Kos Header -->
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
            <div class="stat-box">
                <div style="font-size:1.4rem;font-weight:800;color:var(--text);">{{ $totalRooms }}</div>
                <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Total Kamar</div>
            </div>
            <div class="stat-box">
                <div style="font-size:1.4rem;font-weight:800;color:var(--green);">{{ $availableRooms }}</div>
                <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Tersedia</div>
            </div>
            <div class="stat-box">
                <div style="font-size:1.4rem;font-weight:800;color:#60a5fa;">{{ $occupiedRooms }}</div>
                <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Terisi</div>
            </div>
            <div class="stat-box">
                <div style="font-size:1.4rem;font-weight:800;color:#fb923c;">{{ $maintenanceRooms }}</div>
                <div style="font-size:0.72rem;color:var(--muted);margin-top:2px;">Maintenance</div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    @php
        $selectedType     = request('type') ? ucfirst(request('type')) : 'Semua Tipe';
        $selectedMaxPrice = request('max_price') ? 'Rp ' . number_format(request('max_price'), 0, ',', '.') : 'Tanpa Batas';
        $selectedMaxValue = request('max_price', '');
    @endphp

    <div class="filter-box" style="margin-bottom:1.5rem;">
        <form method="GET" action="{{ route('public.kos.rooms', $tempatKos) }}"
              style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:flex-end;">

            <!-- Tipe -->
            @php $xDataTipe = json_encode(['open' => false, 'selected' => $selectedType]); @endphp
            <div x-data="{{ $xDataTipe }}" style="position:relative;">
                <label class="filter-label">Tipe Kamar</label>
                <input type="hidden" name="type" :value="selected === 'Semua Tipe' ? '' : selected.toLowerCase()">
                <button type="button" @click="open = !open" class="filter-btn">
                    <span x-text="selected"></span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" @click.outside="open=false" style="position:absolute;z-index:20;width:100%;margin-top:4px;" class="filter-dd">
                    <div @click="selected='Semua Tipe'; open=false" class="filter-dd-item">Semua Tipe</div>
                    @foreach($types as $type)
                    <div @click="selected='{{ ucfirst($type) }}'; open=false" class="filter-dd-item">{{ ucfirst($type) }}</div>
                    @endforeach
                </div>
            </div>

            <!-- Harga Max -->
            @php $xDataHarga = json_encode(['open' => false, 'selected' => $selectedMaxPrice, 'value' => $selectedMaxValue]); @endphp
            <div x-data="{{ $xDataHarga }}" style="position:relative;">
                <label class="filter-label">Harga Maks</label>
                <input type="hidden" name="max_price" :value="value">
                <button type="button" @click="open = !open" class="filter-btn" style="min-width:155px;">
                    <span x-text="selected"></span>
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" @click.outside="open=false" style="position:absolute;z-index:20;width:100%;margin-top:4px;" class="filter-dd">
                    <div @click="selected='Tanpa Batas'; value=''; open=false" class="filter-dd-item">Tanpa Batas</div>
                    <div @click="selected='Rp 1.000.000'; value='1000000'; open=false" class="filter-dd-item">Rp 1.000.000</div>
                    <div @click="selected='Rp 1.500.000'; value='1500000'; open=false" class="filter-dd-item">Rp 1.500.000</div>
                    <div @click="selected='Rp 2.000.000'; value='2000000'; open=false" class="filter-dd-item">Rp 2.000.000</div>
                </div>
            </div>

            <div>
                <label class="filter-label" style="visibility:hidden;">Submit</label>
                <button type="submit"
                        style="padding:0.5rem 1.25rem;background:linear-gradient(135deg,var(--amber),#d97706);color:#111;font-weight:700;border-radius:9px;border:none;cursor:pointer;font-family:inherit;font-size:0.82rem;transition:all 0.2s;">
                    Terapkan
                </button>
            </div>

            @if(request()->hasAny(['type', 'max_price']))
            <div>
                <label class="filter-label" style="visibility:hidden;">Reset</label>
                <a href="{{ route('public.kos.rooms', $tempatKos) }}"
                   style="display:inline-block;padding:0.5rem 1rem;border:1px solid var(--border);color:var(--muted);border-radius:9px;text-decoration:none;font-size:0.82rem;transition:all 0.2s;"
                   onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--muted)'">
                    Reset
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Rooms -->
    @if($rooms->count() > 0)
    <p style="font-size:0.8rem;color:var(--muted);margin-bottom:1rem;">{{ $rooms->total() }} kamar ditemukan</p>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.25rem;margin-bottom:2rem;">
        @foreach($rooms as $room)
        <div class="room-card {{ $room->status !== 'available' ? 'dimmed' : '' }}">
            <div class="room-thumb" style="background:linear-gradient(135deg,#1f2937,#374151);">
                @php
                    $images = is_array($room->images) ? $room->images : json_decode($room->images, true);
                    $firstImage = $images[0] ?? null;
                @endphp
                @if($firstImage)
                    <img src="{{ asset('storage/' . $firstImage) }}" alt="Kamar {{ $room->room_number }}">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                        <span class="material-symbols-rounded" style="font-size:40px;color:rgba(255,255,255,0.2);">bed</span>
                    </div>
                @endif
                <div style="position:absolute;top:8px;left:8px;">
                    <span class="pill pill-type">{{ ucfirst($room->type) }}</span>
                </div>
                <div style="position:absolute;top:8px;right:8px;">
                    @if($room->status === 'available')
                        <span class="pill pill-avail">Tersedia</span>
                    @elseif($room->status === 'occupied')
                        <span class="pill pill-occupied">Terisi</span>
                    @else
                        <span class="pill pill-maint">Maintenance</span>
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
                       style="display:block;text-align:center;font-size:0.8rem;font-weight:700;color:#111;background:linear-gradient(135deg,var(--amber),#d97706);padding:0.5rem;border-radius:8px;text-decoration:none;transition:all 0.2s;"
                       onmouseover="this.style.boxShadow='0 4px 14px rgba(245,158,11,0.4)'" onmouseout="this.style.boxShadow='none'">
                        Lihat Detail
                    </a>
                @elseif($room->status === 'occupied')
                    <button disabled style="display:block;width:100%;text-align:center;font-size:0.8rem;font-weight:600;color:var(--muted);background:rgba(255,255,255,0.05);border:1px solid var(--border);padding:0.5rem;border-radius:8px;cursor:not-allowed;">
                        Sudah Terisi
                    </button>
                @else
                    <button disabled style="display:block;width:100%;text-align:center;font-size:0.8rem;font-weight:600;color:#fb923c;background:rgba(251,146,60,0.1);border:1px solid rgba(251,146,60,0.2);padding:0.5rem;border-radius:8px;cursor:not-allowed;">
                        Maintenance
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="dark-pagination">{{ $rooms->links() }}</div>

    @else
    <div style="text-align:center;padding:4rem 2rem;background:var(--card);border:1px solid var(--border);border-radius:18px;">
        <span class="material-symbols-rounded" style="font-size:56px;color:rgba(255,255,255,0.15);display:block;margin-bottom:1rem;">bed</span>
        <p style="font-weight:600;margin-bottom:0.75rem;">Belum ada kamar yang tersedia</p>
        <a href="{{ route('public.kos.index') }}"
           style="font-size:0.84rem;color:var(--amber);text-decoration:none;font-weight:600;"
           onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
            Cari Tempat Kos Lain →
        </a>
    </div>
    @endif

    <!-- CTA -->
    <div class="cta-bar">
        <p style="font-weight:700;margin-bottom:0.35rem;">Tertarik dengan kamar di sini?</p>
        <p style="font-size:0.84rem;color:var(--muted);margin-bottom:1.25rem;">Login atau daftar untuk bisa langsung booking kamar secara online</p>
        <div style="display:flex;align-items:center;justify-content:center;gap:0.75rem;flex-wrap:wrap;">
            <a href="{{ route('register') }}"
               style="padding:0.65rem 1.5rem;background:linear-gradient(135deg,var(--amber),#d97706);color:#111;font-weight:800;border-radius:10px;text-decoration:none;font-size:0.86rem;box-shadow:0 4px 14px rgba(245,158,11,0.3);">
                Daftar Gratis
            </a>
            <a href="{{ route('login') }}"
               style="padding:0.65rem 1.5rem;border:1px solid var(--border);color:var(--muted);font-weight:600;border-radius:10px;text-decoration:none;font-size:0.86rem;"
               onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--muted)'">
                Sudah Punya Akun
            </a>
        </div>
    </div>
</div>

<footer style="background:var(--surface);border-top:1px solid var(--border);padding:2.5rem 2rem 1.5rem;margin-top:0;">
    <div style="max-width:1152px;margin:0 auto;display:flex;justify-content:space-between;align-items:flex-start;gap:2rem;flex-wrap:wrap;">
        <div style="max-width:260px;">
            <div style="display:flex;align-items:center;gap:0.5rem;font-weight:800;font-size:1.05rem;margin-bottom:0.65rem;">
                <div style="width:28px;height:28px;background:linear-gradient(135deg,var(--amber),#d97706);border-radius:7px;display:flex;align-items:center;justify-content:center;">
                    <span class="material-symbols-rounded" style="font-size:15px!important;color:#111;font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 20;">home</span>
                </div>
                KosSmart
            </div>
            <p style="color:var(--muted);font-size:0.82rem;line-height:1.7;">Platform manajemen kos modern untuk penghuni dan pemilik kos Indonesia.</p>
        </div>
        <div style="display:flex;gap:3rem;flex-wrap:wrap;">
            <div>
                <h4 style="font-size:0.82rem;font-weight:700;margin-bottom:0.9rem;">Penghuni</h4>
                <a href="{{ route('register') }}" style="display:block;color:var(--muted);font-size:0.82rem;text-decoration:none;margin-bottom:0.4rem;transition:color 0.2s;" onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Daftar Akun</a>
                <a href="{{ route('login') }}" style="display:block;color:var(--muted);font-size:0.82rem;text-decoration:none;margin-bottom:0.4rem;transition:color 0.2s;" onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Masuk</a>
                <a href="{{ route('password.request') }}" style="display:block;color:var(--muted);font-size:0.82rem;text-decoration:none;margin-bottom:0.4rem;transition:color 0.2s;" onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Lupa Password</a>
            </div>
            <div>
                <h4 style="font-size:0.82rem;font-weight:700;margin-bottom:0.9rem;">Pengelola</h4>
                <a href="{{ route('admin.login') }}" style="display:block;color:var(--muted);font-size:0.82rem;text-decoration:none;margin-bottom:0.4rem;transition:color 0.2s;" onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Login Admin Kos</a>
                <a href="{{ route('superadmin.login') }}" style="display:block;color:var(--muted);font-size:0.82rem;text-decoration:none;margin-bottom:0.4rem;transition:color 0.2s;" onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Login Super Admin</a>
            </div>
            <div>
                <h4 style="font-size:0.82rem;font-weight:700;margin-bottom:0.9rem;">Navigasi</h4>
                <a href="{{ route('home') }}" style="display:block;color:var(--muted);font-size:0.82rem;text-decoration:none;margin-bottom:0.4rem;transition:color 0.2s;" onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Beranda</a>
                <a href="{{ route('tentang') }}" style="display:block;color:var(--muted);font-size:0.82rem;text-decoration:none;margin-bottom:0.4rem;transition:color 0.2s;" onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Tentang Kami</a>
                <a href="{{ route('public.kos.index') }}" style="display:block;color:var(--muted);font-size:0.82rem;text-decoration:none;margin-bottom:0.4rem;transition:color 0.2s;" onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Cari Kos</a>
            </div>
        </div>
    </div>
    <div style="max-width:1152px;margin:1.5rem auto 0;padding-top:1.25rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:0.76rem;color:var(--muted);flex-wrap:wrap;gap:0.5rem;">
        <span>© {{ date('Y') }} KosSmart. Dibuat untuk anak kos Indonesia.</span>
        <span style="display:flex;gap:1rem;">
            <a href="{{ route('home') }}" style="color:var(--muted);text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Beranda</a>
            <a href="{{ route('tentang') }}" style="color:var(--muted);text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Tentang Kami</a>
            <a href="{{ route('login') }}" style="color:var(--muted);text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='var(--amber)'" onmouseout="this.style.color='var(--muted)'">Masuk</a>
        </span>
    </div>
</footer>

<script>
function toggleDD(id) {
    document.querySelectorAll('.dropdown').forEach(d => { if (d.id !== id) d.classList.remove('open'); });
    document.getElementById(id).classList.toggle('open');
}
document.addEventListener('click', e => {
    if (!e.target.closest('.dropdown')) document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('open'));
});
</script>
</body>
</html>