@extends('layouts.public')

@section('title', 'Tentang Kami - KosSmart')

@push('page-styles')
        .blob{position:absolute;border-radius:50%;filter:blur(80px);opacity:0.13;pointer-events:none;}
        .page-hero{min-height:50vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:9rem 1.5rem 5rem;position:relative;overflow:hidden;}
        .hero-badge{display:inline-flex;align-items:center;gap:0.45rem;background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.25);color:var(--amber2);font-size:0.78rem;font-weight:700;padding:0.32rem 0.9rem;border-radius:100px;margin-bottom:1.5rem;animation:fadeUp 0.5s ease both;}
        .page-hero h1{font-size:clamp(2.4rem,5vw,4.2rem);font-weight:800;line-height:1.1;margin-bottom:1.25rem;animation:fadeUp 0.5s 0.08s ease both;}
        .page-hero h1 em{font-style:italic;font-family:'Fraunces',serif;color:var(--amber);}
        .page-hero p{color:var(--muted);font-size:1rem;max-width:510px;margin:0 auto;line-height:1.8;animation:fadeUp 0.5s 0.16s ease both;}
        .journey-grid{display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;}
        .stat-row{display:flex;gap:2rem;margin-top:2.25rem;flex-wrap:wrap;}
        .stat-num{font-size:2.1rem;font-weight:800;font-family:'Fraunces',serif;}
        .stat-desc{font-size:0.78rem;color:var(--muted);margin-top:2px;}
        .info-card{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:1.85rem;box-shadow:0 20px 60px rgba(0,0,0,0.4);}
        .info-row{display:flex;align-items:center;gap:0.75rem;background:var(--surface);border-radius:10px;padding:0.6rem 0.9rem;margin-bottom:0.55rem;}
        .info-row:last-child{margin-bottom:0;}
        .info-row .material-symbols-rounded{font-size:20px!important;flex-shrink:0;}
        .info-row span:last-child{font-size:0.84rem;}
        .vm-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;}
        .vm-card{border-radius:20px;padding:2.1rem;}
        .vm-icon{width:50px;height:50px;border-radius:13px;display:flex;align-items:center;justify-content:center;margin-bottom:1.15rem;}
        .vm-icon .material-symbols-rounded{font-size:24px!important;}
        .vm-card h3{font-size:1.1rem;font-weight:700;margin-bottom:0.65rem;}
        .vm-card p{color:var(--muted);font-size:0.88rem;line-height:1.75;}
        .check-item{display:flex;gap:0.55rem;align-items:flex-start;margin-bottom:0.55rem;}
        .check-item:last-child{margin-bottom:0;}
        .check-icon{font-size:18px!important;flex-shrink:0;margin-top:1px;}
        .steps-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.1rem;margin-top:3rem;}
        .step-card{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:1.6rem;position:relative;overflow:hidden;}
        .step-card.hl{background:linear-gradient(135deg,rgba(245,158,11,0.12),rgba(245,158,11,0.04));border-color:rgba(245,158,11,0.3);}
        .step-num{position:absolute;top:-8px;right:10px;font-size:3.5rem;font-weight:900;opacity:0.06;line-height:1;font-family:'Fraunces',serif;}
        .step-icon{width:42px;height:42px;border-radius:11px;background:rgba(255,255,255,0.05);display:flex;align-items:center;justify-content:center;margin-bottom:0.9rem;}
        .step-icon .material-symbols-rounded{font-size:21px!important;color:var(--amber);}
        .step-lbl{font-size:0.67rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--amber);margin-bottom:0.3rem;}
        .step-card h4{font-size:0.95rem;font-weight:700;margin-bottom:0.38rem;}
        .step-card p{font-size:0.81rem;color:var(--muted);line-height:1.65;}
        @media(max-width:900px){.journey-grid,.vm-grid{grid-template-columns:1fr;gap:2.5rem;}.steps-grid{grid-template-columns:1fr 1fr;}}
        @media(max-width:520px){.steps-grid{grid-template-columns:1fr;}.stat-row{gap:1.25rem;}}
@endpush

@section('content')

{{-- PAGE HERO --}}
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

{{-- PERJALANAN --}}
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

{{-- VISI & MISI --}}
<section style="padding:5.5rem 1.5rem;">
    <div class="max-w">
        <div style="text-align:center;margin-bottom:3rem;">
            <span class="sec-lbl">Apa yang kami percaya</span>
            <h2 class="sec-ttl">Visi & <em>Misi</em> Kami</h2>
        </div>
        <div class="vm-grid">
            <div class="vm-card" style="background:linear-gradient(135deg,rgba(245,158,11,0.1),rgba(245,158,11,0.03));border:1px solid rgba(245,158,11,0.2);">
                <div class="vm-icon" style="background:linear-gradient(135deg,var(--amber),#d97706);box-shadow:0 8px 20px rgba(245,158,11,0.3);">
                    <span class="material-symbols-rounded" style="color:#111;">visibility</span>
                </div>
                <span style="font-size:0.69rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--amber);display:block;margin-bottom:0.5rem;">Visi</span>
                <h3>Platform kos terpercaya<br>untuk generasi muda Indonesia</h3>
                <p style="margin-top:0.7rem;">Menjadi platform inovatif yang mampu memberikan pengalaman terbaik bagi penghuni kos melalui layanan yang transparan, akurat, dan terpercaya berbasis komunikasi dua arah.</p>
            </div>
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

{{-- CARA KERJA --}}
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

@endsection