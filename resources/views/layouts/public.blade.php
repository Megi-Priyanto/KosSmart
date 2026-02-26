<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KosSmart - Rumah Keduamu, Lebih Mudah')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('head-scripts')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:ital,wght@0,700;1,600;1,700&display=swap" rel="stylesheet">
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
        nav{position:fixed;top:0;left:0;right:0;z-index:200;display:flex;align-items:center;justify-content:space-between;padding:0.85rem 2rem;background:rgba(17,24,39,0.9);backdrop-filter:blur(14px);border-bottom:1px solid var(--border);}
        .nav-left{display:flex;align-items:center;gap:1.75rem;}
        .nav-right{display:flex;align-items:center;gap:0.55rem;}
        .nav-logo{display:flex;align-items:center;gap:0.5rem;font-weight:800;font-size:1.15rem;color:var(--text);text-decoration:none;flex-shrink:0;}
        .nav-logo-icon{width:32px;height:32px;background:linear-gradient(135deg,var(--amber),#d97706);border-radius:8px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(245,158,11,0.35);}
        .nav-link{color:var(--muted);font-size:0.86rem;font-weight:600;text-decoration:none;transition:color 0.2s;white-space:nowrap;}
        .nav-link:hover{color:var(--text);}
        .nav-link.active{color:var(--amber);}

        /* Dropdown */
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

        /* ── SHARED ── */
        .max-w{max-width:1100px;margin:0 auto;}
        .sec-lbl{font-size:0.74rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--amber);display:block;margin-bottom:0.55rem;}
        .sec-ttl{font-size:clamp(1.8rem,3.5vw,2.65rem);font-weight:800;line-height:1.2;margin-bottom:0.85rem;}
        .sec-ttl em{font-family:'Fraunces',serif;font-style:italic;color:var(--amber);}
        .blob{position:absolute;border-radius:50%;filter:blur(80px);opacity:0.15;pointer-events:none;}
        @keyframes fadeUp{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:translateY(0)}}

        /* ── FOOTER ── */
        footer{background:var(--surface);border-top:1px solid var(--border);padding:2.5rem 2rem 1.5rem;}
        .footer-inner{max-width:1100px;margin:0 auto;display:flex;justify-content:space-between;align-items:flex-start;gap:2rem;flex-wrap:wrap;}
        .footer-brand{max-width:280px;}
        .footer-brand p{color:var(--muted);font-size:0.84rem;line-height:1.7;margin-top:0.65rem;}
        .footer-links{display:flex;gap:3rem;flex-wrap:wrap;}
        .footer-col h4{font-size:0.82rem;font-weight:700;margin-bottom:0.9rem;color:var(--text);}
        .footer-col a{display:block;color:var(--muted);font-size:0.82rem;text-decoration:none;margin-bottom:0.4rem;transition:color 0.2s;}
        .footer-col a:hover{color:var(--amber);}
        .footer-bottom{max-width:1100px;margin:1.5rem auto 0;padding-top:1.25rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:0.76rem;color:var(--muted);flex-wrap:wrap;gap:0.5rem;}

        /* ── RESPONSIVE ── */
        @media(max-width:768px){nav{padding:0.75rem 1rem;}.nav-left .nav-link{display:none;}.footer-links{gap:1.75rem;}}
        @media(max-width:520px){.footer-inner{flex-direction:column;}.footer-links{gap:1.5rem;}}

        @stack('page-styles')
    </style>
    @stack('head')
</head>
<body>

{{-- ══════════════════════════════════════ NAVBAR --}}
<nav>
    <div class="nav-left">
        <a href="{{ route('home') }}" class="nav-logo">
            <img src="{{ app_logo() }}" alt="{{ app_name() }}"
                 style="width:32px;height:32px;border-radius:50%;object-fit:cover;box-shadow:0 4px 12px rgba(245,158,11,0.35);">
            {{ app_name() }}
        </a>
        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
        <a href="{{ route('tentang') }}" class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}">Tentang Kami</a>
        <a href="{{ route('public.kos.index') }}" class="nav-link {{ request()->routeIs('public.kos.*') ? 'active' : '' }}">Cari Kos</a>
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

{{-- ══════════════════════════════════════ CONTENT --}}
@yield('content')

{{-- ══════════════════════════════════════ FOOTER --}}
<footer>
    <div class="footer-inner">
        <div class="footer-brand">
            <div style="display:flex;align-items:center;gap:0.5rem;font-weight:800;font-size:1.05rem;">
                <img src="{{ app_logo() }}" alt="{{ app_name() }}"
                     style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                {{ app_name() }}
            </div>
            <p>Platform manajemen kos modern untuk penghuni dan pemilik kos Indonesia.</p>
        </div>
        <div class="footer-links">
            <div class="footer-col">
                <h4>Penghuni</h4>
                <a href="{{ route('register') }}">Daftar Akun</a>
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('password.request') }}">Lupa Password</a>
            </div>
            <div class="footer-col">
                <h4>Pengelola</h4>
                <a href="{{ route('admin.login') }}">Login Admin Kos</a>
                <a href="{{ route('superadmin.login') }}">Login Super Admin</a>
            </div>
            <div class="footer-col">
                <h4>Navigasi</h4>
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('tentang') }}">Tentang Kami</a>
                <a href="{{ route('public.kos.index') }}">Cari Kos</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© {{ date('Y') }} KosSmart. Dibuat untuk anak kos Indonesia.</span>
        <span style="display:flex;gap:1rem;">
            <a href="{{ route('tentang') }}" style="color:var(--muted);text-decoration:none;transition:color 0.2s" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color=''">Tentang</a>
            <a href="{{ route('login') }}" style="color:var(--muted);text-decoration:none;transition:color 0.2s" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color=''">Masuk</a>
        </span>
    </div>
</footer>

<script>
    function toggleDD(id) {
        document.querySelectorAll('.dropdown').forEach(d => { if(d.id!==id) d.classList.remove('open'); });
        document.getElementById(id).classList.toggle('open');
    }
    document.addEventListener('click', e => {
        if (!e.target.closest('.dropdown')) document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('open'));
    });
</script>
@stack('scripts')
</body>
</html>