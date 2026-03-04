{{-- resources/views/emails/admin-registration/received.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<style>
  body{margin:0;padding:0;background:#0f172a;font-family:'Segoe UI',sans-serif;color:#f1f5f9;}
  .wrap{max-width:520px;margin:40px auto;background:#1e293b;border-radius:16px;overflow:hidden;border:1px solid rgba(255,255,255,0.08);}
  .header{background:linear-gradient(135deg,#f59e0b,#d97706);padding:32px 32px 24px;text-align:center;}
  .header h1{margin:0;font-size:22px;font-weight:800;color:#111;}
  .header p{margin:6px 0 0;font-size:14px;color:rgba(0,0,0,0.6);}
  .body{padding:32px;}
  .row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.06);font-size:14px;}
  .row:last-child{border-bottom:none;}
  .lbl{color:#94a3b8;}
  .val{color:#f1f5f9;font-weight:600;text-align:right;}
  .info-box{background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:10px;padding:16px;margin:20px 0;font-size:13px;color:#fcd34d;line-height:1.7;}
  .footer{padding:20px 32px;background:#0f172a;text-align:center;font-size:12px;color:#475569;}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>KosSmart</h1>
    <p>Pendaftaran Admin Kos Diterima</p>
  </div>
  <div class="body">
    <p style="font-size:15px;margin:0 0 20px;">Halo, <strong>{{ $registration->nama_lengkap }}</strong>!</p>
    <p style="font-size:14px;color:#94a3b8;line-height:1.7;margin:0 0 20px;">
      Pendaftaran kos Anda telah kami terima dan sedang dalam proses verifikasi oleh tim KosSmart.
    </p>

    <div style="background:#0f172a;border-radius:10px;padding:16px;margin-bottom:20px;">
      <div class="row"><span class="lbl">Nama Kos</span><span class="val">{{ $registration->nama_kos }}</span></div>
      <div class="row"><span class="lbl">Lokasi</span><span class="val">{{ $registration->kota }}, {{ $registration->provinsi }}</span></div>
      <div class="row"><span class="lbl">Email</span><span class="val">{{ $registration->email }}</span></div>
      <div class="row"><span class="lbl">Tanggal Daftar</span><span class="val">{{ $registration->created_at->format('d M Y') }}</span></div>
    </div>

    <div class="info-box">
      ⏱ Proses verifikasi memakan waktu <strong>1–3 hari kerja</strong>. Kami akan mengirim email
      setelah pengajuan Anda diproses.
    </div>
  </div>
  <div class="footer">© {{ date('Y') }} KosSmart · Email ini dikirim otomatis, jangan dibalas.</div>
</div>
</body></html>