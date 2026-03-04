<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - KosSmart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        :root {
            --bg: #f8f9fb;
            --surface: #ffffff;
            --card: #f3f4f6;
            --amber: #d97706;
            --amber2: #f59e0b;
            --green: #059669;
            --purple: #7c3aed;
            --text: #0f172a;
            --muted: #6b7280;
            --border: rgba(15,23,42,0.09);
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .material-symbols-rounded {
            font-variation-settings: 'FILL' 1,'wght' 400,'GRAD' 0,'opsz' 24;
            vertical-align: middle;
            line-height: 1;
        }
        @keyframes popIn {
            from { transform: scale(0.5); opacity: 0; }
            to   { transform: scale(1);   opacity: 1; }
        }
        .pop-in { animation: popIn 0.4s cubic-bezier(0.175,0.885,0.32,1.275) both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s 0.1s ease both; }

        /* Decorative blob */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body>
    <!-- Background blobs matching welcome page -->
    <div class="blob" style="width:480px;height:480px;background:#f59e0b;top:-160px;left:-140px;opacity:0.06;"></div>
    <div class="blob" style="width:380px;height:380px;background:#059669;bottom:-100px;right:-100px;opacity:0.05;"></div>
    <div class="blob" style="width:260px;height:260px;background:#7c3aed;top:40%;left:55%;opacity:0.04;"></div>

    <div style="position:relative;z-index:1;max-width:500px;width:100%;text-align:center;">

        <!-- Success Icon -->
        <div class="pop-in" style="width:84px;height:84px;background:linear-gradient(135deg,#34d399,#059669);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.75rem;box-shadow:0 16px 40px rgba(5,150,105,0.2);">
            <span class="material-symbols-rounded" style="font-size:42px!important;color:#fff;">check</span>
        </div>

        <!-- Title -->
        <div class="fade-up">
            <div style="display:inline-flex;align-items:center;gap:0.4rem;background:rgba(5,150,105,0.08);border:1px solid rgba(5,150,105,0.2);color:var(--green);font-size:0.76rem;font-weight:700;padding:0.3rem 0.85rem;border-radius:100px;margin-bottom:1rem;">
                <span class="material-symbols-rounded" style="font-size:14px!important;">verified</span>
                Pengajuan Diterima
            </div>
            <h1 style="font-size:2rem;font-weight:800;line-height:1.2;margin-bottom:0.6rem;color:var(--text);">Pendaftaran Berhasil!</h1>
            <p style="color:var(--muted);line-height:1.8;margin-bottom:0.5rem;font-size:0.93rem;">
                Pengajuan Anda telah kami terima dan sedang dalam proses verifikasi oleh tim KosSmart.
            </p>

            @if(session('registration_email'))
            <p style="color:var(--muted);line-height:1.8;margin-bottom:2rem;font-size:0.93rem;">
                Email konfirmasi telah dikirim ke
                <strong style="color:var(--text);">{{ session('registration_email') }}</strong>.
            </p>
            @else
            <div style="margin-bottom:2rem;"></div>
            @endif
        </div>

        <!-- Next Steps Card -->
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:18px;padding:1.75rem;margin-bottom:2rem;text-align:left;box-shadow:0 8px 24px rgba(15,23,42,0.06);">
            <div style="font-size:0.72rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--amber);margin-bottom:1.1rem;">Langkah Selanjutnya</div>
            <div style="display:flex;flex-direction:column;gap:1rem;">

                <!-- Step 1 -->
                <div style="display:flex;gap:0.85rem;align-items:flex-start;">
                    <div style="width:32px;height:32px;background:rgba(217,119,6,0.08);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(217,119,6,0.15);">
                        <span class="material-symbols-rounded" style="font-size:15px!important;color:var(--amber);">search</span>
                    </div>
                    <div>
                        <div style="font-size:0.87rem;font-weight:700;color:var(--text);">Tim kami akan mereview dokumen Anda</div>
                        <div style="font-size:0.79rem;color:var(--muted);margin-top:2px;line-height:1.6;">Proses verifikasi memakan waktu 1–3 hari kerja</div>
                    </div>
                </div>

                <div style="height:1px;background:var(--border);margin-left:2.8rem;"></div>

                <!-- Step 2 -->
                <div style="display:flex;gap:0.85rem;align-items:flex-start;">
                    <div style="width:32px;height:32px;background:rgba(5,150,105,0.08);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(5,150,105,0.15);">
                        <span class="material-symbols-rounded" style="font-size:15px!important;color:var(--green);">mail</span>
                    </div>
                    <div>
                        <div style="font-size:0.87rem;font-weight:700;color:var(--text);">Notifikasi via Email</div>
                        <div style="font-size:0.79rem;color:var(--muted);margin-top:2px;line-height:1.6;">Kami akan mengirim email saat pengajuan disetujui atau ditolak</div>
                    </div>
                </div>

                <div style="height:1px;background:var(--border);margin-left:2.8rem;"></div>

                <!-- Step 3 -->
                <div style="display:flex;gap:0.85rem;align-items:flex-start;">
                    <div style="width:32px;height:32px;background:rgba(124,58,237,0.07);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid rgba(124,58,237,0.15);">
                        <span class="material-symbols-rounded" style="font-size:15px!important;color:var(--purple);">login</span>
                    </div>
                    <div>
                        <div style="font-size:0.87rem;font-weight:700;color:var(--text);">Akun Langsung Aktif</div>
                        <div style="font-size:0.79rem;color:var(--muted);margin-top:2px;line-height:1.6;">Jika disetujui, Anda langsung bisa login menggunakan kredensial yang didaftarkan</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- CTA Button -->
        <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.85rem 2.25rem;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;font-weight:700;border-radius:12px;text-decoration:none;font-size:0.93rem;box-shadow:0 8px 24px rgba(217,119,6,0.22);transition:all 0.25s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 32px rgba(217,119,6,0.35)';" onmouseout="this.style.transform='';this.style.boxShadow='0 8px 24px rgba(217,119,6,0.22)';">
            <span class="material-symbols-rounded" style="font-size:18px!important;">home</span>
            Kembali ke Beranda
        </a>

        <!-- Footer note -->
        <p style="font-size:0.76rem;color:var(--muted);margin-top:1.5rem;">
            Ada pertanyaan? Hubungi kami di <a href="mailto:support@kossmart.id" style="color:var(--amber);font-weight:600;text-decoration:none;">support@kossmart.id</a>
        </p>

    </div>
</body>
</html>