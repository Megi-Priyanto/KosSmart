<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Email - KosSmart</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-block bg-white rounded-full p-4 shadow-lg mb-4">
                <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Verifikasi Email Anda</h1>
        </div>
        
        <!-- Card -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8 text-center">
            
            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                <p class="text-green-700 text-sm">{{ session('success') }}</p>
            </div>
            @endif
            
            <!-- Icon Email -->
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-purple-100 rounded-full">
                    <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-3">Cek Email Anda</h2>
            
            <p class="text-gray-600 mb-6">
                Kami telah mengirimkan link verifikasi ke email Anda:
                <br>
                <strong class="text-gray-800">{{ session('email') ?? auth()->user()->email ?? 'alamat email Anda' }}</strong>
            </p>
            
            <p class="text-sm text-gray-500 mb-6">
                Silakan cek inbox atau folder spam Anda dan klik link verifikasi untuk mengaktifkan akun.
            </p>
            
            <!-- Resend Button -->
            <form action="{{ route('verification.resend') }}" method="POST" class="mb-4">
                @csrf
                <button 
                    type="submit" 
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-all"
                >
                    Kirim Ulang Email Verifikasi
                </button>
            </form>
            
            <!-- Back to Login -->
            <a 
                href="{{ route('login') }}" 
                class="text-sm text-purple-600 hover:text-purple-700 font-medium"
            >
                Kembali ke Halaman Login
            </a>
        </div>
        
        <!-- Tips -->
        <div class="mt-6 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-4">
            <p class="text-white text-sm">
                <strong>💡 Tips:</strong> Jika email tidak masuk dalam 5 menit, cek folder spam atau klik tombol kirim ulang di atas.
            </p>
        </div>
        
    </div>
    
</body>
</html>