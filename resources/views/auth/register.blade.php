<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun - KosSmart</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .bg-primary {
            background: #1a2332;
        }
        
        .bg-secondary {
            background: #212b3d;
        }
        
        .card-auth {
            background: #2a3441;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .input-auth {
            background: #1a2332;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .input-auth:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
            outline: none;
        }
        
        .input-auth::placeholder {
            color: #6b7280;
        }
        
        .input-auth.error {
            border-color: #ef4444;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .logo-glow {
            box-shadow: 0 0 30px rgba(245, 158, 11, 0.3);
        }
        
        .text-accent {
            color: #f59e0b;
        }
        
        .floating-animation {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .strength-bar {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        
        .strength-weak { width: 33%; background: #ef4444; }
        .strength-medium { width: 66%; background: #f59e0b; }
        .strength-strong { width: 100%; background: #10b981; }
    </style>
</head>
<body class="bg-primary min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md" x-data="registerForm()">
        
        <!-- Logo dan Header -->
        <div class="text-center mb-8 floating-animation">
            <div class="inline-block bg-secondary rounded-full p-4 logo-glow mb-4">
                <svg class="w-12 h-12 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-white mb-2">KosSmart</h1>
            <p class="text-gray-400">Daftar Akun Baru</p>
        </div>
        
        <!-- Card Register -->
        <div class="card-auth rounded-2xl shadow-2xl p-8">
            
            <!-- Pesan Error -->
            @if($errors->any())
            <div class="mb-6 p-4 bg-red-500 bg-opacity-10 border-l-4 border-red-500 rounded-lg">
                <p class="text-red-400 text-sm font-medium mb-1">Terjadi Kesalahan:</p>
                <ul class="text-red-400 text-sm list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <h2 class="text-2xl font-bold text-white mb-2">Buat Akun</h2>
            <p class="text-gray-400 mb-6">Lengkapi data Anda untuk mendaftar</p>
            
            <!-- Form Register -->
            <form action="{{ route('register') }}" method="POST" @submit="handleSubmit" class="space-y-4">
                @csrf
                
                <!-- Input Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            class="input-auth w-full pl-10 pr-4 py-2.5 rounded-lg @error('name') error @enderror"
                            placeholder="Contoh: Budi Santoso"
                            required
                            autofocus
                        >
                    </div>
                    @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            class="input-auth w-full pl-10 pr-4 py-2.5 rounded-lg @error('email') error @enderror"
                            placeholder="nama@email.com"
                            required
                        >
                    </div>
                    @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Input Telepon -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                        Nomor Telepon <span class="text-gray-500 text-xs">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            value="{{ old('phone') }}"
                            class="input-auth w-full pl-10 pr-4 py-2.5 rounded-lg @error('phone') error @enderror"
                            placeholder="08123456789"
                        >
                    </div>
                    @error('phone')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            :type="showPassword ? 'text' : 'password'"
                            id="password" 
                            name="password" 
                            x-model="password"
                            @input="checkPasswordStrength"
                            class="input-auth w-full pl-10 pr-12 py-2.5 rounded-lg @error('password') error @enderror"
                            placeholder="Minimal 8 karakter"
                            required
                        >
                        <button 
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-300"
                        >
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Password Strength -->
                    <div x-show="password.length > 0" class="mt-2">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs text-gray-400">Kekuatan Password:</span>
                            <span class="text-xs font-medium" :class="{
                                'text-red-400': strength === 'weak',
                                'text-amber-400': strength === 'medium',
                                'text-green-400': strength === 'strong'
                            }" x-text="strengthText"></span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-1">
                            <div class="strength-bar" :class="{
                                'strength-weak': strength === 'weak',
                                'strength-medium': strength === 'medium',
                                'strength-strong': strength === 'strong'
                            }"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Gunakan huruf besar, kecil, angka, dan simbol</p>
                    </div>
                    
                    @error('password')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <input 
                            :type="showPasswordConfirm ? 'text' : 'password'"
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="input-auth w-full pl-10 pr-12 py-2.5 rounded-lg"
                            placeholder="Ulangi password Anda"
                            required
                        >
                        <button 
                            type="button"
                            @click="showPasswordConfirm = !showPasswordConfirm"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-300"
                        >
                            <svg x-show="!showPasswordConfirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showPasswordConfirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Checkbox Terms -->
                <div class="flex items-start">
                    <input 
                        type="checkbox" 
                        id="terms" 
                        name="terms" 
                        value="1"
                        class="mt-1 h-4 w-4 text-amber-500 bg-primary border-gray-600 rounded focus:ring-amber-500 @error('terms') border-red-500 @enderror"
                        required
                    >
                    <label for="terms" class="ml-2 block text-sm text-gray-300">
                        Saya setuju dengan 
                        <a href="#" class="text-accent hover:text-amber-400 font-medium">Syarat & Ketentuan</a> 
                        dan 
                        <a href="#" class="text-accent hover:text-amber-400 font-medium">Kebijakan Privasi</a>
                        <span class="text-red-500">*</span>
                    </label>
                </div>
                @error('terms')
                <p class="text-sm text-red-400">{{ $message }}</p>
                @enderror
                
                <!-- Tombol Daftar -->
                <button 
                    type="submit" 
                    class="btn-primary w-full text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-primary flex items-center justify-center"
                    :disabled="isSubmitting"
                >
                    <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="isSubmitting ? 'Mendaftar...' : 'Daftar Sekarang'"></span>
                </button>
            </form>
            
            <!-- Link Login -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-400">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-medium text-accent hover:text-amber-400 transition-colors">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="text-center mt-8">
            <p class="text-gray-500 text-sm">
                © {{ date('Y') }} KosSmart. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
    
    <script>
        function registerForm() {
            return {
                password: '',
                showPassword: false,
                showPasswordConfirm: false,
                strength: 'weak',
                strengthText: 'Lemah',
                isSubmitting: false,
                
                checkPasswordStrength() {
                    const pwd = this.password;
                    let score = 0;
                    
                    if (pwd.length >= 8) score++;
                    if (pwd.length >= 12) score++;
                    if (/[a-z]/.test(pwd)) score++;
                    if (/[A-Z]/.test(pwd)) score++;
                    if (/[0-9]/.test(pwd)) score++;
                    if (/[^a-zA-Z0-9]/.test(pwd)) score++;
                    
                    if (score <= 2) {
                        this.strength = 'weak';
                        this.strengthText = 'Lemah';
                    } else if (score <= 4) {
                        this.strength = 'medium';
                        this.strengthText = 'Sedang';
                    } else {
                        this.strength = 'strong';
                        this.strengthText = 'Kuat';
                    }
                },
                
                handleSubmit(event) {
                    this.isSubmitting = true;
                }
            }
        }
    </script>
    
</body>
</html>