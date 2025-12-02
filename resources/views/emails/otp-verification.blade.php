<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Email</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #667eea;
            margin: 0;
            font-size: 28px;
        }
        .otp-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 30px;
            border-radius: 10px;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 48px;
            font-weight: bold;
            letter-spacing: 10px;
            margin: 10px 0;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>🏠 KosSmart</h1>
            <p>Sistem Manajemen Kost Modern</p>
        </div>

        <h2>Verifikasi Email Anda</h2>
        
        <p>Halo,</p>
        
        <p>Terima kasih telah mendaftar di KosSmart! Gunakan kode OTP berikut untuk memverifikasi alamat email Anda:</p>

        <div class="otp-box">
            <div style="font-size: 16px; margin-bottom: 10px;">Kode Verifikasi Anda</div>
            <div class="otp-code">{{ $otp }}</div>
            <div style="font-size: 14px; margin-top: 10px;">Berlaku selama 60 detik</div>
        </div>

        <div class="warning">
            <strong>⚠️ Penting:</strong>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Kode ini hanya berlaku selama <strong>60 detik</strong></li>
                <li>Jangan bagikan kode ini kepada siapa pun</li>
                <li>Jika Anda tidak merasa mendaftar, abaikan email ini</li>
            </ul>
        </div>

        <p>Jika kode sudah kadaluarsa, Anda dapat meminta kode baru melalui tombol "Kirim Ulang OTP" di halaman verifikasi.</p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon tidak membalas.</p>
            <p>&copy; {{ date('Y') }} KosSmart. All rights reserved.</p>
        </div>
    </div>
</body>
</html>