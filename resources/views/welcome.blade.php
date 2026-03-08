@extends('layouts.public')

@section('title', 'KosSmart - Rumah Keduamu, Lebih Mudah')

@push('page-styles')
        /* ── HERO ── */
        .hero{min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:8rem 1.5rem 5rem;position:relative;overflow:hidden;}
        .hero-badge{display:inline-flex;align-items:center;gap:0.45rem;background:rgba(217,119,6,0.08);border:1px solid rgba(217,119,6,0.22);color:var(--amber);font-size:0.78rem;font-weight:700;padding:0.32rem 0.9rem;border-radius:100px;margin-bottom:1.75rem;animation:fadeUp 0.5s ease both;}
        .badge-dot{width:7px;height:7px;background:var(--amber);border-radius:50%;animation:pulse 2s infinite;flex-shrink:0;}
        @keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:0.5;transform:scale(0.8)}}
        .hero h1{font-size:clamp(2.8rem,6vw,5.2rem);line-height:1.08;font-weight:800;margin-bottom:1.5rem;animation:fadeUp 0.5s 0.08s ease both;color:var(--text);}
        .hero h1 em{font-style:italic;font-family:'Fraunces',serif;color:var(--amber);}
        .hero-sub{font-size:clamp(0.97rem,1.8vw,1.15rem);color:var(--muted);max-width:520px;margin:0 auto 2.25rem;line-height:1.8;animation:fadeUp 0.5s 0.16s ease both;}
        .hero-cta{display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;animation:fadeUp 0.5s 0.24s ease both;}
        .btn-cta-p{padding:0.85rem 2rem;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;font-weight:800;font-size:0.97rem;border-radius:12px;text-decoration:none;box-shadow:0 8px 24px rgba(217,119,6,0.25);transition:all 0.25s;}
        .btn-cta-p:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(217,119,6,0.4);}
        .btn-cta-s{padding:0.85rem 2rem;border:1px solid rgba(15,23,42,0.15);color:var(--muted);font-weight:600;font-size:0.97rem;border-radius:12px;text-decoration:none;background:transparent;transition:all 0.25s;}
        .btn-cta-s:hover{color:var(--text);border-color:rgba(15,23,42,0.3);background:rgba(15,23,42,0.03);}

        /* Hero mockup */
        .hero-mockup-wrap{position:relative;max-width:680px;margin:3.5rem auto 0;animation:fadeUp 0.6s 0.32s ease both;}
        .hero-mockup{background:#ffffff;border:1px solid rgba(15,23,42,0.1);border-radius:20px;padding:1.5rem;box-shadow:0 30px 80px rgba(15,23,42,0.12);}
        .mockup-topbar{display:flex;align-items:center;gap:0.5rem;margin-bottom:1rem;}
        .mockup-dot{width:10px;height:10px;border-radius:50%;}
        .room-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:0.7rem;}
        .room-card{background:var(--card);border-radius:12px;overflow:hidden;border:1px solid rgba(15,23,42,0.08);}
        .room-img{height:66px;display:flex;align-items:center;justify-content:center;}
        .room-img .material-symbols-rounded{font-size:32px!important;color:var(--muted);opacity:0.6;}
        .room-info{padding:0.6rem;}
        .room-name{font-size:0.72rem;font-weight:700;color:var(--text);}
        .room-price{font-size:0.65rem;color:var(--amber);font-weight:600;margin-top:2px;}
        .room-status{display:inline-block;font-size:0.58rem;font-weight:700;padding:2px 6px;border-radius:4px;margin-top:4px;}
        .avail{background:rgba(5,150,105,0.1);color:var(--green);}
        .full{background:rgba(220,38,38,0.1);color:#dc2626;}
        .float-badge{position:absolute;background:#ffffff;border:1px solid rgba(15,23,42,0.1);border-radius:12px;padding:0.6rem 0.9rem;display:flex;align-items:center;gap:0.6rem;font-size:0.78rem;font-weight:600;box-shadow:0 8px 24px rgba(15,23,42,0.1);white-space:nowrap;}
        .float-badge .material-symbols-rounded{font-size:18px!important;}
        .fb-l{left:-80px;top:18%;animation:floatY 4s ease-in-out infinite;}
        .fb-r{right:-52px;top:60%;animation:floatY 4s 2s ease-in-out infinite;}
        @keyframes floatY{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}

        /* ── FEATURES ── */
        .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.2rem;margin-top:3rem;}
        .feat-card{background:#ffffff;border:1px solid rgba(15,23,42,0.08);border-radius:18px;padding:1.75rem;transition:transform 0.25s,border-color 0.25s,box-shadow 0.25s;}
        .feat-card:hover{transform:translateY(-4px);border-color:rgba(217,119,6,0.2);box-shadow:0 12px 32px rgba(217,119,6,0.08);}
        .feat-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;}
        .feat-icon .material-symbols-rounded{font-size:22px!important;}
        .feat-card h3{font-size:0.97rem;font-weight:700;margin-bottom:0.45rem;color:var(--text);}
        .feat-card p{font-size:0.84rem;color:var(--muted);line-height:1.7;}

        /* ── STEPS ── */
        .steps-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.1rem;margin-top:3rem;}
        .step-card{background:#ffffff;border:1px solid rgba(15,23,42,0.08);border-radius:16px;padding:1.75rem;position:relative;overflow:hidden;}
        .step-card.hl{background:linear-gradient(135deg,rgba(245,158,11,0.07),rgba(245,158,11,0.02));border-color:rgba(217,119,6,0.2);}
        .step-num{position:absolute;top:-8px;right:10px;font-size:3.5rem;font-weight:900;opacity:0.04;line-height:1;font-family:'Fraunces',serif;color:var(--text);}
        .step-icon{width:44px;height:44px;border-radius:12px;background:rgba(15,23,42,0.04);display:flex;align-items:center;justify-content:center;margin-bottom:1rem;}
        .step-icon .material-symbols-rounded{font-size:22px!important;color:var(--amber);}
        .step-lbl{font-size:0.67rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--amber);margin-bottom:0.35rem;}
        .step-card h4{font-size:0.97rem;font-weight:700;margin-bottom:0.4rem;color:var(--text);}
        .step-card p{font-size:0.82rem;color:var(--muted);line-height:1.65;}

        /* ── ADMIN CTA ── */
        .admin-cta-wrap{background:linear-gradient(135deg,rgba(245,158,11,0.05),rgba(5,150,105,0.03));border-top:1px solid rgba(15,23,42,0.08);border-bottom:1px solid rgba(15,23,42,0.08);}
        .admin-cta-inner{display:grid;grid-template-columns:1fr auto;gap:3rem;align-items:center;max-width:1100px;margin:0 auto;padding:4.5rem 1.5rem;}
        .admin-cta-inner h2{font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;margin-bottom:0.7rem;color:var(--text);}
        .admin-cta-inner h2 em{font-family:'Fraunces',serif;font-style:italic;color:var(--amber);}
        .admin-cta-inner p{color:var(--muted);line-height:1.75;max-width:490px;}
        .admin-cta-actions{display:flex;gap:1rem;margin-top:1.6rem;flex-wrap:wrap;align-items:center;}
        .btn-amber{display:inline-block;padding:0.85rem 1.85rem;white-space:nowrap;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;font-weight:700;font-size:0.93rem;border-radius:12px;text-decoration:none;box-shadow:0 8px 24px rgba(217,119,6,0.25);transition:all 0.25s;}
        .btn-amber:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(217,119,6,0.4);}

        /* Revenue card highlight row */
        .rev-row-highlight{background:rgba(217,119,6,0.06);border:1px solid rgba(217,119,6,0.18);border-radius:8px;padding:0.5rem 0.8rem;}

        @media(max-width:960px){.features-grid{grid-template-columns:1fr 1fr;}.steps-grid{grid-template-columns:1fr 1fr;}.admin-cta-inner{grid-template-columns:1fr;}.admin-cta-inner p{max-width:100%;}.admin-cta-actions{justify-content:flex-start;}}
        @media(max-width:768px){.fb-l,.fb-r{display:none;}.room-grid{grid-template-columns:1fr 1fr;}}
        @media(max-width:520px){.features-grid,.steps-grid{grid-template-columns:1fr;}.room-grid{grid-template-columns:1fr;}}
@endpush

@section('content')
{{-- ══════════════════════════════════════ HERO --}}
<section class="hero">
    <div class="blob" style="width:520px;height:520px;background:var(--amber);top:-120px;left:-160px;opacity:0.07;"></div>
    <div class="blob" style="width:420px;height:420px;background:var(--green);bottom:-100px;right:-120px;opacity:0.06;"></div>
    <div class="blob" style="width:280px;height:280px;background:var(--purple);top:38%;left:52%;opacity:0.05;"></div>

    <div style="position:relative;z-index:1;width:100%;">
        <div class="hero-badge">
            <span class="material-symbols-rounded" style="font-size:14px!important;color:var(--amber);">auto_awesome</span>
            Platform manajemen kos untuk mahasiswa Indonesia
        </div>

        <h1>
            Cari kos, bayar tagihan,<br>semua dari <em>satu tempat</em>
        </h1>

        <p class="hero-sub">
            Booking kamar online, pantau status sewamu, dan bayar tagihan bulanan —
            tanpa ribet, langsung dari HP kamu.
        </p>



        <!-- Mockup -->
        <div class="hero-mockup-wrap">
            <div class="float-badge fb-l">
                <span class="material-symbols-rounded" style="color:var(--green);">check_circle</span>
                <div>
                    <div style="color:var(--text);">Pembayaran Diterima</div>
                    <div style="font-size:0.68rem;color:var(--muted);font-weight:400;">Tagihan Mei · barusan</div>
                </div>
            </div>
            <div class="float-badge fb-r">
                <span class="material-symbols-rounded" style="color:var(--amber);">notifications</span>
                <div>
                    <div style="color:var(--text);">Tagihan baru masuk</div>
                    <div style="font-size:0.68rem;color:var(--muted);font-weight:400;">Jatuh tempo 5 hari lagi</div>
                </div>
            </div>
            <div class="hero-mockup">
                <div class="mockup-topbar">
                    <div class="mockup-dot" style="background:#ef4444"></div>
                    <div class="mockup-dot" style="background:#f59e0b"></div>
                    <div class="mockup-dot" style="background:#34d399"></div>
                    <span style="margin-left:0.5rem;font-size:0.7rem;color:var(--muted)">KosSmart · Pilih Kamarmu</span>
                </div>
                <div class="room-grid">
                    @php
                        $bgColors = [
                            ['background:linear-gradient(135deg,#f0f9ff,#e0f2fe)'],
                            ['background:linear-gradient(135deg,#fefce8,#fef9c3)'],
                            ['background:linear-gradient(135deg,#f0fdf4,#dcfce7)'],
                        ];
                    @endphp

                    @forelse($heroRooms as $i => $room)
                    <div class="room-card">
                        <div class="room-img" style="{{ $bgColors[$i % 3][0] }}">
                            @if(is_array($room->images) && count($room->images) > 0)
                                <img src="{{ asset('storage/' . $room->images[0]) }}"
                                     alt="Kamar {{ $room->room_number }}"
                                     style="width:100%;height:100%;object-fit:cover;border-radius:0;">
                            @else
                                <span class="material-symbols-rounded">bed</span>
                            @endif
                        </div>
                        <div class="room-info">
                            <div class="room-name">Kamar {{ $room->room_number }}</div>
                            <div class="room-price">Rp {{ number_format($room->price, 0, ',', '.') }}/bln</div>
                            @if($room->status === 'available')
                                <span class="room-status avail">● Tersedia</span>
                            @elseif($room->status === 'occupied')
                                <span class="room-status full">● Terisi</span>
                            @else
                                <span class="room-status" style="background:rgba(245,158,11,0.1);color:#d97706;">● Maintenance</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    {{-- Fallback jika belum ada kamar di database --}}
                    <div class="room-card">
                        <div class="room-img" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe)">
                            <span class="material-symbols-rounded">bed</span>
                        </div>
                        <div class="room-info">
                            <div class="room-name">Kamar A-01</div>
                            <div class="room-price">Rp 800.000/bln</div>
                            <span class="room-status avail">● Tersedia</span>
                        </div>
                    </div>
                    <div class="room-card">
                        <div class="room-img" style="background:linear-gradient(135deg,#fefce8,#fef9c3)">
                            <span class="material-symbols-rounded">bed</span>
                        </div>
                        <div class="room-info">
                            <div class="room-name">Kamar B-03</div>
                            <div class="room-price">Rp 950.000/bln</div>
                            <span class="room-status avail">● Tersedia</span>
                        </div>
                    </div>
                    <div class="room-card">
                        <div class="room-img" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7)">
                            <span class="material-symbols-rounded">bed</span>
                        </div>
                        <div class="room-info">
                            <div class="room-name">Kamar C-02</div>
                            <div class="room-price">Rp 750.000/bln</div>
                            <span class="room-status full">● Penuh</span>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════ FITUR UNGGULAN --}}
<section style="padding:5.5rem 1.5rem;background:var(--card);border-top:1px solid var(--border);">
    <div class="max-w">
        <div style="text-align:center;">
            <span class="sec-lbl">Kenapa KosSmart?</span>
            <h2 class="sec-ttl">Semua yang kamu butuhkan,<br>sudah <em>ada di sini</em></h2>
            <p style="color:var(--muted);max-width:440px;margin:0 auto;line-height:1.75;font-size:0.93rem;">Dari cari kamar sampai bayar tagihan, kami buat semuanya semudah mungkin.</p>
        </div>
        <div class="features-grid">
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(217,119,6,0.1);">
                    <span class="material-symbols-rounded" style="color:var(--amber);">smartphone</span>
                </div>
                <h3>Booking Online</h3>
                <p>Pilih kamar, ajukan booking, dan tunggu konfirmasi — semua dari HP tanpa perlu datang langsung.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(5,150,105,0.1);">
                    <span class="material-symbols-rounded" style="color:var(--green);">receipt_long</span>
                </div>
                <h3>Tagihan Otomatis</h3>
                <p>Tagihan bulanan dibuat otomatis dan langsung masuk ke akunmu. Bayar kapan saja, bukti tersimpan rapi.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(124,58,237,0.1);">
                    <span class="material-symbols-rounded" style="color:var(--purple);">notifications_active</span>
                </div>
                <h3>Notifikasi Real-time</h3>
                <p>Dapat notifikasi saat tagihan masuk, pembayaran dikonfirmasi, atau ada info penting dari admin kos.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(245,158,11,0.1);">
                    <span class="material-symbols-rounded" style="color:var(--amber2);">history</span>
                </div>
                <h3>Riwayat Transparan</h3>
                <p>Lihat semua riwayat pembayaran dan sewamu kapan saja. Semua tercatat, tidak ada yang tersembunyi.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(220,38,38,0.08);">
                    <span class="material-symbols-rounded" style="color:#dc2626;">verified_user</span>
                </div>
                <h3>Akun Aman & Terverifikasi</h3>
                <p>Verifikasi email dua langkah memastikan hanya kamu yang bisa akses akunmu. Datamu terlindungi.</p>
            </div>
            <div class="feat-card">
                <div class="feat-icon" style="background:rgba(6,182,212,0.08);">
                    <span class="material-symbols-rounded" style="color:#0891b2;">logout</span>
                </div>
                <h3>Checkout Mudah</h3>
                <p>Mau pindah atau selesai masa sewa? Ajukan checkout langsung dari aplikasi. Simpel dan cepat.</p>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════ CARA KERJA --}}
<section style="padding:5.5rem 1.5rem;background:var(--bg);">
    <div class="max-w">
        <div style="text-align:center;">
            <span class="sec-lbl">Gampang banget</span>
            <h2 class="sec-ttl">Mulai dalam <em>4 langkah</em></h2>
            <p style="color:var(--muted);max-width:400px;margin:0 auto;line-height:1.75;font-size:0.93rem;">Dari daftar sampai tidur nyenyak di kos baru, prosesnya nggak sampai 5 menit.</p>
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
                <div class="step-icon" style="background:rgba(217,119,6,0.1);"><span class="material-symbols-rounded">home</span></div>
                <div class="step-lbl">Langkah 4</div>
                <h4>Bayar & Tinggal</h4>
                <p>Bayar tagihan dari aplikasi, terima notifikasi otomatis. Selesai!</p>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════ ADMIN CTA --}}
<div class="admin-cta-wrap">
    <div class="admin-cta-inner">
        <div>
            <span class="sec-lbl">Punya kos?</span>
            <h2>Kelola kos kamu jadi<br>lebih <em>profesional</em></h2>
            <p>Daftarkan kos kamu di KosSmart dan nikmati kemudahan manajemen kamar, tagihan, dan penghuni — semua dalam satu dashboard yang rapi.</p>
            <div class="admin-cta-actions">
                <a href="{{ route('admin.registration.form') }}" class="btn-amber">Daftarkan Kos Saya</a>
            </div>
        </div>
        <div style="flex-shrink:0;">
            <div style="background:#ffffff;border:1px solid rgba(217,119,6,0.15);border-radius:18px;padding:1.6rem;min-width:248px;box-shadow:0 20px 50px rgba(15,23,42,0.08);">
                <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:1.1rem;">
                    <span class="material-symbols-rounded" style="font-size:28px!important;color:var(--amber);font-variation-settings:'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 28;">payments</span>
                    <div>
                        <div style="font-size:0.82rem;font-weight:700;color:var(--text);">Model Bagi Hasil</div>
                        <div style="font-size:0.7rem;color:var(--muted);">Transparan & adil</div>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:0.5rem;">
                    {{-- Komisi platform --}}
                    <div style="display:flex;justify-content:space-between;align-items:center;background:var(--card);border-radius:8px;padding:0.5rem 0.8rem;">
                        <span style="font-size:0.78rem;color:var(--muted);">Komisi platform</span>
                        <span style="font-size:0.78rem;font-weight:700;color:#dc2626;">10% / transaksi</span>
                    </div>
                    {{-- Pendapatan bersih --}}
                    <div style="display:flex;justify-content:space-between;align-items:center;background:rgba(5,150,105,0.06);border-radius:8px;padding:0.5rem 0.8rem;border:1px solid rgba(5,150,105,0.15);">
                        <span style="font-size:0.78rem;color:var(--muted);">Pendapatan bersihmu</span>
                        <span style="font-size:0.78rem;font-weight:700;color:var(--green);">90% untukmu</span>
                    </div>
                    {{-- Pencairan --}}
                    <div style="display:flex;justify-content:space-between;align-items:center;background:var(--card);border-radius:8px;padding:0.5rem 0.8rem;">
                        <span style="font-size:0.78rem;color:var(--muted);">Pencairan otomatis</span>
                        <span style="font-size:0.78rem;font-weight:700;color:var(--text);">Tiap transaksi masuk</span>
                    </div>
                    {{-- No biaya langganan --}}
                    <div style="display:flex;justify-content:space-between;align-items:center;background:var(--card);border-radius:8px;padding:0.5rem 0.8rem;">
                        <span style="font-size:0.78rem;color:var(--muted);">Biaya langganan</span>
                        <span style="font-size:0.78rem;font-weight:700;color:var(--text);">Tidak ada</span>
                    </div>
                    {{-- Daftar gratis highlight --}}
                    <div class="rev-row-highlight" style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:0.78rem;color:var(--muted);">Pendaftaran kos</span>
                        <span style="font-size:0.78rem;font-weight:800;color:var(--amber);">✓ Gratis!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection