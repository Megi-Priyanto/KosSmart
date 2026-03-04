{{-- resources/views/emails/admin-registration/rejected.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<style>
  body{margin:0;padding:0;background:#0f172a;font-family:'Segoe UI',sans-serif;color:#f1f5f9;}
  .wrap{max-width:520px;margin:40px auto;background:#1e293b;border-radius:16px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);}
  .header{background:linear-gradient(135deg,#475569,#334155);padding:32px 32px 24px;text-align:center;}
  .header h1{margin:0;font-size:22px;font-weight:800;color:#fff;}
  .header p{margin:6px 0 0;font-size:14px;color:rgba(255,255,255,0.6);}
  .body{padding:32px;}
  .reason-box{background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.25);border-radius:10px;padding:16px;margin:20px 0;font-size:14px;color:#fca5a5;line-height:1.75;}
  .info-box{background:rgba(148,163,184,0.06);border:1px solid rgba(148,163,184,0.1);border-radius:10px;padding:14px;font-size:13px;color:#94a3b8;line-height:1.7;}
  .footer{padding:20px 32px;background:#0f172a;text-align:center;font-size:12px;color:#475569;}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>KosSmart</h1>
    <p>Update Pengajuan Pendaftaran Kos</p>
  </div>
  <div class="body">
    <p style="font-size:15px;margin:0 0 16px;">Halo, <strong>{{ $registration->nama_lengkap }}</strong>.</p>
    <p style="font-size:14px;color:#94a3b8;line-height:1.7;margin:0 0 4px;">
      Setelah melakukan review, kami belum dapat menyetujui pengajuan pendaftaran kos
      <strong style="color:#f1f5f9;">{{ $registration->nama_kos }}</strong> Anda saat ini.
    </p>

    <div class="reason-box">
      <strong style="display:block;margin-bottom:6px;">Alasan:</strong>
      {{ $registration->catatan }}
    </div>

    <div class="info-box">
      💡 Anda dapat mendaftar kembali setelah memperbaiki dokumen atau informasi yang diminta.
      Kunjungi halaman pendaftaran di website KosSmart untuk mengajukan ulang.
    </div>
  </div>
  <div class="footer">© {{ date('Y') }} KosSmart · Email ini dikirim otomatis, jangan dibalas.</div>
</div>
</body></html>