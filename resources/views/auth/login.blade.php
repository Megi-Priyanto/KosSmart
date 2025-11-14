<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - KosSmart</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .input-focus:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .floating-animation {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    
    <!-- Container Utama -->
    <div class="w-full max-w-md">
        
        <!-- Logo dan Header -->
        <div class="text-center mb-8 floating-animation">
            <div class="inline-block bg-white rounded-full p-4 shadow-lg mb-4">
                <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">KosSmart</h1>
            <p class="text-white text-opacity-90">Sistem Manajemen Kos Modern</p>
        </div>
        
        <!-- Card Login -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8">
            
            <!-- Pesan Success -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                <p class="text-green-700 text-sm">{{ session('success') }}</p>
            </div>
            @endif
            
            <!-- Pesan Error -->
            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <p class="text-red-700 text-sm font-medium mb-1">Terjadi Kesalahan:</p>
                <ul class="text-red-600 text-sm list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
            <p class="text-gray-600 mb-6">Silakan masuk ke akun Anda</p>
            
            <!-- Form Login -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all"
                            placeholder="nama@email.com"
                            required
                            autofocus
                        >
                    </div>
                </div>
                
                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none transition-all"
                            placeholder="Masukkan password Anda"
                            required
                        >
                    </div>
                </div>
                
                <!-- Remember Me & Lupa Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember" 
                            class="h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Ingat saya
                        </label>
                    </div>
                    
                    <a href="{{ route('password.request') }}" 
                       class="text-sm font-medium text-purple-600 hover:text-purple-700 transition-colors">
                       Lupa Password?
                    </a>
                    
                </div>
                
                <!-- Tombol Login -->
                <button 
                    type="submit" 
                    class="btn-primary w-full text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                >
                    Masuk ke Dashboard
                </button>
            </form>
            
            <!-- Footer -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-medium text-purple-600 hover:text-purple-700 transition-colors">
                        Daftar Sekarang
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="text-center mt-8">
            <p class="text-white text-sm text-opacity-80">
                © {{ date('Y') }} KosSmart. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
    
</body>
</html>