{{-- resources/views/emails/admin-registration/approved.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<style>
  body{margin:0;padding:0;background:#0f172a;font-family:'Segoe UI',sans-serif;color:#f1f5f9;}
  .wrap{max-width:520px;margin:40px auto;background:#1e293b;border-radius:16px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);}
  .header{background:linear-gradient(135deg,#34d399,#059669);padding:32px 32px 24px;text-align:center;}
  .header h1{margin:0;font-size:22px;font-weight:800;color:#fff;}
  .header p{margin:6px 0 0;font-size:14px;color:rgba(255,255,255,0.75);}
  .body{padding:32px;}
  .row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.06);font-size:14px;}
  .row:last-child{border-bottom:none;}
  .lbl{color:#94a3b8;}
  .val{color:#f1f5f9;font-weight:600;text-align:right;}
  .btn{display:block;width:fit-content;margin:24px auto 0;padding:14px 36px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#111;font-weight:800;border-radius:10px;text-decoration:none;font-size:14px;}
  .note-box{background:rgba(52,211,153,0.08);border:1px solid rgba(52,211,153,0.2);border-radius:10px;padding:16px;margin:20px 0;font-size:13px;color:#6ee7b7;line-height:1.7;}
  .footer{padding:20px 32px;background:#0f172a;text-align:center;font-size:12px;color:#475569;}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>🎉 Selamat!</h1>
    <p>Pendaftaran Kos Anda Disetujui</p>
  </div>
  <div class="body">
    <p style="font-size:15px;margin:0 0 20px;">Halo, <strong>{{ $registration->nama_lengkap }}</strong>!</p>
    <p style="font-size:14px;color:#94a3b8;line-height:1.7;margin:0 0 20px;">
      Pengajuan pendaftaran kos Anda telah <strong style="color:#34d399;">disetujui</strong> oleh tim KosSmart.
      Akun admin Anda sudah aktif dan siap digunakan.
    </p>

    <div style="background:#0f172a;border-radius:10px;padding:16px;margin-bottom:16px;">
      <p style="font-size:12px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#f59e0b;margin:0 0 10px;">Kredensial Login</p>
      <div class="row"><span class="lbl">Email</span><span class="val">{{ $registration->email }}</span></div>
      <div class="row"><span class="lbl">Password</span><span class="val">Password yang Anda daftarkan</span></div>
      <div class="row"><span class="lbl">Kos</span><span class="val">{{ $registration->nama_kos }}</span></div>
    </div>

    @if($registration->catatan)
    <div class="note-box">
      <strong>Catatan dari tim KosSmart:</strong><br>
      {{ $registration->catatan }}
    </div>
    @endif

    <div style="background:rgba(245,158,11,0.07);border:1px solid rgba(245,158,11,0.2);border-radius:10px;padding:14px;font-size:13px;color:#fcd34d;margin-bottom:8px;">
      🔒 Jaga kerahasiaan password Anda. Segera ubah password setelah login pertama.
    </div>

    <a href="{{ url('/admin/login') }}" class="btn">Login Sekarang →</a>
  </div>
  <div class="footer">© {{ date('Y') }} KosSmart · Email ini dikirim otomatis, jangan dibalas.</div>
</div>
</body></html>