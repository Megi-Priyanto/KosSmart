<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - KosSmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:ital,wght@0,700;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        :root{--bg:#111827;--surface:#1f2937;--card:#243040;--amber:#f59e0b;--amber2:#fbbf24;--green:#34d399;--purple:#a78bfa;--text:#f3f4f6;--muted:#9ca3af;--border:rgba(255,255,255,0.07);}
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--text);overflow-x:hidden;}
        body::before{content:'';position:fixed;inset:0;pointer-events:none;z-index:0;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");}

        .material-symbols-rounded{font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;vertical-align:middle;line-height:1;}

        /* NAVBAR */
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

        /* SHARED */
        .max-w{max-width:1100px;margin:0 auto;}
        .sec-lbl{font-size:0.74rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--amber);display:block;margin-bottom:0.55rem;}
        .sec-ttl{font-size:clamp(1.8rem,3.5vw,2.65rem);font-weight:800;line-height:1.2;margin-bottom:0.85rem;}
        .sec-ttl em{font-family:'Fraunces',serif;font-style:italic;color:var(--amber);}
        @keyframes fadeUp{from{opacity:0;transform:translateY(22px)}to{opacity:1;transform:translateY(0)}}

        /* BLOBS */
        .blob{position:absolute;border-radius:50%;filter:blur(80px);opacity:0.13;pointer-events:none;}

        /* PAGE HERO */
        .page-hero{min-height:50vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:9rem 1.5rem 5rem;position:relative;overflow:hidden;}
        .hero-badge{display:inline-flex;align-items:center;gap:0.45rem;background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.25);color:var(--amber2);font-size:0.78rem;font-weight:700;padding:0.32rem 0.9rem;border-radius:100px;margin-bottom:1.5rem;animation:fadeUp 0.5s ease both;}
        .page-hero h1{font-size:clamp(2.4rem,5vw,4.2rem);font-weight:800;line-height:1.1;margin-bottom:1.25rem;animation:fadeUp 0.5s 0.08s ease both;}
        .page-hero h1 em{font-style:italic;font-family:'Fraunces',serif;color:var(--amber);}
        .page-hero p{color:var(--muted);font-size:1rem;max-width:510px;margin:0 auto;line-height:1.8;animation:fadeUp 0.5s 0.16s ease both;}

        /* JOURNEY */
        .journey-grid{display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;}
        .stat-row{display:flex;gap:2rem;margin-top:2.25rem;flex-wrap:wrap;}
        .stat-num{font-size:2.1rem;font-weight:800;font-family:'Fraunces',serif;}
        .stat-desc{font-size:0.78rem;color:var(--muted);margin-top:2px;}
        .info-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:1.85rem;box-shadow:0 20px 60px rgba(0,0,0,0.4);}
        .info-row{display:flex;align-items:center;gap:0.75rem;background:var(--surface);border-radius:10px;padding:0.6rem 0.9rem;margin-bottom:0.55rem;}
        .info-row:last-child{margin-bottom:0;}
        .info-row .material-symbols-rounded{font-size:20px!important;flex-shrink:0;}
        .info-row span:last-child{font-size:0.84rem;}

        /* VISI MISI */
        .vm-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;}
        .vm-card{border-radius:20px;padding:2.1rem;}
        .vm-icon{width:50px;height:50px;border-radius:13px;display:flex;align-items:center;justify-content:center;margin-bottom:1.15rem;}
        .vm-icon .material-symbols-rounded{font-size:24px!important;}
        .vm-card h3{font-size:1.1rem;font-weight:700;margin-bottom:0.65rem;}
        .vm-card p{color:var(--muted);font-size:0.88rem;line-height:1.75;}
        .check-item{display:flex;gap:0.55rem;align-items:flex-start;margin-bottom:0.55rem;}
        .check-item:last-child{margin-bottom:0;}
        .check-icon{font-size:18px!important;flex-shrink:0;margin-top:1px;}

        /* STEPS */
        .steps-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.1rem;margin-top:3rem;}
        .step-card{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:1.6rem;position:relative;overflow:hidden;}
        .step-card.hl{background:linear-gradient(135deg,rgba(245,158,11,0.12),rgba(245,158,11,0.04));border-color:rgba(245,158,11,0.3);}
        .step-num{position:absolute;top:-8px;right:10px;font-size:3.5rem;font-weight:900;opacity:0.06;line-height:1;font-family:'Fraunces',serif;}
        .step-icon{width:42px;height:42px;border-radius:11px;background:rgba(255,255,255,0.05);display:flex;align-items:center;justify-content:center;margin-bottom:0.9rem;}
        .step-icon .material-symbols-rounded{font-size:21px!important;color:var(--amber);}
        .step-lbl{font-size:0.67rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--amber);margin-bottom:0.3rem;}
        .step-card h4{font-size:0.95rem;font-weight:700;margin-bottom:0.38rem;}
        .step-card p{font-size:0.81rem;color:var(--muted);line-height:1.65;}

        /* FOOTER */
        footer{background:var(--surface);border-top:1px solid var(--border);padding:2.5rem 2rem 1.5rem;}
        .footer-inner{max-width:1100px;margin:0 auto;display:flex;justify-content:space-between;align-items:flex-start;gap:2rem;flex-wrap:wrap;}
        .footer-brand{max-width:270px;}
        .footer-brand p{color:var(--muted);font-size:0.83rem;line-height:1.7;margin-top:0.65rem;}
        .footer-links{display:flex;gap:3rem;flex-wrap:wrap;}
        .footer-col h4{font-size:0.82rem;font-weight:700;margin-bottom:0.9rem;}
        .footer-col a{display:block;color:var(--muted);font-size:0.82rem;text-decoration:none;margin-bottom:0.4rem;transition:color 0.2s;}
        .footer-col a:hover{color:var(--amber);}
        .footer-bottom{max-width:1100px;margin:1.5rem auto 0;padding-top:1.25rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;font-size:0.76rem;color:var(--muted);flex-wrap:wrap;gap:0.5rem;}

        /* RESPONSIVE */
        @media(max-width:900px){.journey-grid,.vm-grid{grid-template-columns:1fr;gap:2.5rem;}.steps-grid{grid-template-columns:1fr 1fr;}}
        @media(max-width:768px){nav{padding:0.75rem 1rem;}.nav-left .nav-link{display:none;}.footer-links{gap:1.75rem;}}
        @media(max-width:520px){.steps-grid,.footer-inner{grid-template-columns:1fr;flex-direction:column;}.stat-row{gap:1.25rem;}}
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav>
    <div class="nav-left">
        <a href="{{ route('home') }}" class="nav-logo">
            <div class="nav-logo-icon">
                <span class="material-symbols-rounded" style="font-size:18px!important;color:#111;font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 20;">home</span>
            </div>
            KosSmart
        </a>
        <a href="{{ route('home') }}" class="nav-link">Beranda</a>
        <a href="{{ route('tentang') }}" class="nav-link active">Tentang Kami</a>
        <a href="{{ route('public.kos.index') }}" class="nav-link">Cari Kos</a>
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


<!-- PAGE HERO -->
<section class="page-hero">
    <div class="blob" style="width:500px;height:500px;background:var(--amber);top:-120px;left:-180px;"></div>
    <div class="blob" style="width:380px;height:380px;background:var(--purple);bottom:-60px;right:-100px;"></div>
    <div style="position:relative;z-index:1;">
        <div class="hero-badge">
            <span class="material-symbols-rounded" style="font-size:14px!important;color:var(--amber);">auto_awesome</span>
            Kenali lebih dekat
        </div>
        <h1>Sebuah platform yang lahir<br>dari <em>pengalaman nyata</em></h1>
        <p>KosSmart hadir karena kami tahu betapa ribetnya urusan kos. Kami membangunnya untuk para penghuni dan pemilik kos di seluruh Indonesia.</p>
    </div>
</section>


<!-- PERJALANAN -->
<section style="padding:5rem 1.5rem;background:var(--surface);border-top:1px solid var(--border);">
    <div class="max-w">
        <div class="journey-grid">
            <div>
                <span class="sec-lbl">Sebuah Perjalanan</span>
                <h2 class="sec-ttl">Lahir dari keresahan<br>anak kos <em>sendiri</em></h2>
                <p style="color:var(--muted);line-height:1.85;margin-bottom:1rem;font-size:0.93rem;">Semuanya bermula dari pengalaman nyata — ribetnya cari kos, transfer tagihan manual lewat chat, sampai salah paham soal status pembayaran antara penghuni dan pemilik kos.</p>
                <p style="color:var(--muted);line-height:1.85;font-size:0.93rem;">Kami percaya pengalaman tinggal di kos seharusnya simpel dan transparan. KosSmart hadir sebagai jembatan antara penghuni dan pemilik kos agar keduanya bisa fokus pada hal yang lebih penting.</p>
                <div class="stat-row">
                    <div><div class="stat-num" style="color:var(--amber);">500+</div><div class="stat-desc">Kamar Terdaftar</div></div>
                    <div><div class="stat-num" style="color:var(--green);">1.200+</div><div class="stat-desc">Penghuni Aktif</div></div>
                    <div><div class="stat-num" style="color:var(--purple);">98%</div><div class="stat-desc">Kepuasan Pengguna</div></div>
                </div>
            </div>
            <div>
                <div class="info-card">
                    <div style="text-align:center;margin-bottom:1.25rem;">
                        <span class="material-symbols-rounded" style="font-size:44px!important;color:var(--amber);font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 48;">home_work</span>
                    </div>
                    <div style="text-align:center;font-size:1rem;font-weight:700;margin-bottom:0.35rem;">Kami ada untuk kamu</div>
                    <div style="text-align:center;color:var(--muted);font-size:0.85rem;line-height:1.65;margin-bottom:1.35rem;">Dari booking pertama sampai checkout, kami temani setiap langkahmu.</div>
                    <div class="info-row">
                        <span class="material-symbols-rounded" style="color:var(--amber);">smartphone</span>
                        <span>Semua bisa diakses dari HP</span>
                    </div>
                    <div class="info-row">
                        <span class="material-symbols-rounded" style="color:var(--green);">receipt_long</span>
                        <span>Tagihan otomatis & transparan</span>
                    </div>
                    <div class="info-row">
                        <span class="material-symbols-rounded" style="color:var(--purple);">notifications_active</span>
                        <span>Notifikasi real-time</span>
                    </div>
                    <div class="info-row">
                        <span class="material-symbols-rounded" style="color:#f87171;">verified_user</span>
                        <span>Akun aman & terverifikasi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- VISI & MISI -->
<section style="padding:5.5rem 1.5rem;">
    <div class="max-w">
        <div style="text-align:center;margin-bottom:3rem;">
            <span class="sec-lbl">Apa yang kami percaya</span>
            <h2 class="sec-ttl">Visi & <em>Misi</em> Kami</h2>
        </div>
        <div class="vm-grid">
            <!-- Visi -->
            <div class="vm-card" style="background:linear-gradient(135deg,rgba(245,158,11,0.1),rgba(245,158,11,0.03));border:1px solid rgba(245,158,11,0.2);">
                <div class="vm-icon" style="background:linear-gradient(135deg,var(--amber),#d97706);box-shadow:0 8px 20px rgba(245,158,11,0.3);">
                    <span class="material-symbols-rounded" style="color:#111;">visibility</span>
                </div>
                <span style="font-size:0.69rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--amber);display:block;margin-bottom:0.5rem;">Visi</span>
                <h3>Platform kos terpercaya<br>untuk generasi muda Indonesia</h3>
                <p style="margin-top:0.7rem;">Menjadi platform inovatif yang mampu memberikan pengalaman terbaik bagi penghuni kos melalui layanan yang transparan, akurat, dan terpercaya berbasis komunikasi dua arah.</p>
            </div>
            <!-- Misi -->
            <div class="vm-card" style="background:linear-gradient(135deg,rgba(52,211,153,0.08),rgba(52,211,153,0.02));border:1px solid rgba(52,211,153,0.2);">
                <div class="vm-icon" style="background:linear-gradient(135deg,var(--green),#059669);box-shadow:0 8px 20px rgba(52,211,153,0.25);">
                    <span class="material-symbols-rounded" style="color:#111;">target</span>
                </div>
                <span style="font-size:0.69rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--green);display:block;margin-bottom:0.5rem;">Misi</span>
                <h3 style="margin-bottom:0.9rem;">Empat pilar yang kami<br>pegang setiap hari</h3>
                <div class="check-item">
                    <span class="material-symbols-rounded check-icon" style="color:var(--green);">check_circle</span>
                    <span style="color:var(--muted);font-size:0.87rem;line-height:1.65;">Memudahkan penghuni memenuhi kebutuhan akomodasi sesuai preferensi mereka</span>
                </div>
                <div class="check-item">
                    <span class="material-symbols-rounded check-icon" style="color:var(--green);">check_circle</span>
                    <span style="color:var(--muted);font-size:0.87rem;line-height:1.65;">Memberikan layanan pelanggan yang responsif untuk mendukung kehidupan sehari-hari</span>
                </div>
                <div class="check-item">
                    <span class="material-symbols-rounded check-icon" style="color:var(--green);">check_circle</span>
                    <span style="color:var(--muted);font-size:0.87rem;line-height:1.65;">Menyajikan solusi yang objektif dan efisien dalam pemberian layanan</span>
                </div>
                <div class="check-item">
                    <span class="material-symbols-rounded check-icon" style="color:var(--green);">check_circle</span>
                    <span style="color:var(--muted);font-size:0.87rem;line-height:1.65;">Menjamin keamanan transaksi dan kenyamanan bagi semua penghuni kos</span>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- CARA KERJA -->
<section style="padding:2rem 1.5rem 5.5rem;background:var(--surface);border-top:1px solid var(--border);">
    <div class="max-w" style="padding-top:3.5rem;">
        <div style="text-align:center;">
            <span class="sec-lbl">Mudah banget</span>
            <h2 class="sec-ttl">Bagaimana cara<br><em>KosSmart</em> bekerja?</h2>
            <p style="color:var(--muted);max-width:400px;margin:0 auto;line-height:1.75;font-size:0.93rem;">Dari daftar sampai tidur nyenyak di kos baru, semuanya mudah dan cepat.</p>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-num">1</div>
                <div class="step-icon"><span class="material-symbols-rounded">edit_note</span></div>
                <div class="step-lbl">Langkah 1</div>
                <h4>Buat Akun</h4>
                <p>Daftar gratis dalam 30 detik pakai email, verifikasi OTP, langsung siap.</p>
            </div>
            <div class="step-card">
                <div class="step-num">2</div>
                <div class="step-icon"><span class="material-symbols-rounded">search</span></div>
                <div class="step-lbl">Langkah 2</div>
                <h4>Pilih Kamar</h4>
                <p>Lihat kamar tersedia, cek fasilitas dan harga, pilih yang cocok buat kamu.</p>
            </div>
            <div class="step-card">
                <div class="step-num">3</div>
                <div class="step-icon"><span class="material-symbols-rounded">task_alt</span></div>
                <div class="step-lbl">Langkah 3</div>
                <h4>Booking & Tunggu</h4>
                <p>Ajukan booking dan tunggu konfirmasi dari admin kos.</p>
            </div>
            <div class="step-card hl">
                <div class="step-num">4</div>
                <div class="step-icon" style="background:rgba(245,158,11,0.12);"><span class="material-symbols-rounded">home</span></div>
                <div class="step-lbl">Langkah 4</div>
                <h4>Bayar & Tinggal</h4>
                <p>Bayar tagihan dari aplikasi, terima notifikasi otomatis. Selesai!</p>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-inner">
        <div class="footer-brand">
            <div style="display:flex;align-items:center;gap:0.5rem;font-weight:800;font-size:1.05rem;">
                <div class="nav-logo-icon" style="width:28px;height:28px;border-radius:7px;">
                    <span class="material-symbols-rounded" style="font-size:15px!important;color:#111;font-variation-settings:'FILL' 1,'wght' 600,'GRAD' 0,'opsz' 20;">home</span>
                </div>
                KosSmart
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
                <a href="mailto:admin@kossmart.id">Daftarkan Kos</a>
            </div>
            <div class="footer-col">
                <h4>Navigasi</h4>
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ route('tentang') }}">Tentang Kami</a>
                <a href="{{ route('public.kos.index') }}">Cari Kos</a>
                <a href="#">Kebijakan Privasi</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© {{ date('Y') }} KosSmart. Dibuat untuk anak kos Indonesia.</span>
        <span style="display:flex;gap:1rem;">
            <a href="{{ route('home') }}" style="color:var(--muted);text-decoration:none;transition:color 0.2s" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color=''">Beranda</a>
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
</body>
</html>