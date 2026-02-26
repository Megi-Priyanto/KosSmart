<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Email Baru - KosSmart</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
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

        /* OTP Input Style */
        .otp-input {
            width: 50px;
            height: 60px;
            font-size: 24px;
            text-align: center;
            background: #1a2332;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #e5e7eb;
            transition: all 0.3s;
        }

        .otp-input:focus {
            border-color: #f59e0b;
            outline: none;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .countdown {
            font-size: 14px;
            color: #9ca3af;
        }

        .countdown.expired {
            color: #ef4444;
            font-weight: 600;
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
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-secondary {
            background: #212b3d;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover:not(:disabled) {
            background: #2a3441;
        }
        
        .btn-secondary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-danger {
            background: #dc2626;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="bg-primary min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        
        <!-- Logo -->
        <div class="text-center mb-8 floating-animation">
            <div class="inline-block bg-secondary rounded-full p-4 logo-glow mb-4">
                <svg class="w-12 h-12 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Verifikasi Email Baru</h1>
            <p class="text-gray-600">Masukkan kode OTP yang telah dikirim</p>
        </div>
        
        <!-- Card -->
        <div class="card-auth rounded-2xl shadow-2xl p-8">
            
            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-500 bg-opacity-10 border-l-4 border-green-500 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-400 text-sm">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-500 bg-opacity-10 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-red-400 text-sm">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <!-- Email Info -->
            <div class="mb-6 space-y-3">
                <!-- Old Email -->
                <div class="p-4 bg-opacity-5 bg-[#1e293b] rounded-lg border border-gray-600">
                    <p class="text-xs text-gray-600 mb-1">Email Lama</p>
                    <p class="font-medium text-gray-600">{{ $oldEmail }}</p>
                </div>
                
                <!-- New Email (Highlight) -->
                <div class="p-4 bg-amber-500 bg-opacity-10 rounded-lg border border-amber-500 border-opacity-20">
                    <p class="text-xs text-amber-400 mb-1">Kode OTP telah dikirim ke:</p>
                    <p class="font-semibold text-white">{{ $newEmail }}</p>
                </div>
            </div>
            
            <!-- OTP Form -->
            <form action="{{ route('user.profile.email.verify.process') }}" method="POST" id="otpForm">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-600 mb-3 text-center">
                        Masukkan Kode OTP (6 Digit)
                    </label>
                    
                    <!-- OTP Input Fields -->
                    <div class="flex justify-center gap-2 mb-2">
                        <input type="text" maxlength="1" class="otp-input" data-index="0" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" data-index="1" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" data-index="2" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" data-index="3" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" data-index="4" autocomplete="off">
                        <input type="text" maxlength="1" class="otp-input" data-index="5" autocomplete="off">
                    </div>
                    
                    <!-- Hidden input -->
                    <input type="hidden" name="otp" id="otpValue">
                    
                    @error('otp')
                    <p class="text-red-400 text-sm mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Countdown Timer -->
                <div class="text-center mb-6">
                    <p class="countdown" id="countdown">
                        Kode berlaku: <span id="timer" class="font-semibold text-accent">60</span> detik
                    </p>
                </div>
                
                <!-- Verify Button -->
                <button 
                    type="submit" 
                    id="verifyBtn"
                    class="btn-primary w-full text-white font-semibold py-3 px-4 rounded-lg mb-4"
                >
                    <span id="btnText">Verifikasi Sekarang</span>
                    <span id="btnLoading" class="hidden">
                        <svg class="animate-spin inline w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memverifikasi...
                    </span>
                </button>
            </form>
            
            <!-- Resend OTP Form -->
            <form action="{{ route('user.profile.email.resend') }}" method="POST" id="resendForm" class="mb-4">
                @csrf
                <button 
                    type="submit" 
                    id="resendBtn"
                    class="btn-secondary w-full text-gray-600 font-semibold py-3 px-4 rounded-lg"
                    disabled
                >
                    <span id="resendText">Kirim Ulang OTP</span>
                    <span id="resendWait" class="hidden">Tunggu <span id="resendTimer">60</span>s</span>
                </button>
            </form>

            <!-- Cancel & Logout -->
            <div class="pt-4 border-t border-gray-700">
                <button 
                    onclick="confirmCancel()" 
                    class="w-full text-gray-600 hover:text-gray-600 font-medium py-2"
                >
                    Batal & Logout
                </button>
            </div>
        </div>
        
        <!-- Warning Info -->
        <div class="mt-6 card-auth rounded-xl p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Informasi Penting</p>
                    <p class="text-gray-600 text-sm">
                        Jika Anda membatalkan proses ini, email akan tetap berubah menjadi email baru dalam status belum terverifikasi. Anda akan di-logout dan harus verifikasi saat login kembali.
                    </p>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="mt-4 card-auth rounded-xl p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-gray-600 text-sm">
                    <strong class="text-gray-600">Tips: </strong>Pastikan memeriksa folder spam jika email tidak masuk dalam beberapa menit.
                </p>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="text-center mt-8">
            <p class="text-gray-500 text-sm">
                © {{ date('Y') }} KosSmart. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
        <div class="card-auth rounded-2xl max-w-md w-full p-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-500 bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Batalkan Perubahan Email?</h3>
                <p class="text-sm text-gray-600 mb-6">
                    Jika Anda membatalkan, email akan tetap berubah menjadi <strong class="text-white">{{ $newEmail }}</strong> 
                    dalam status <strong class="text-amber-400">belum terverifikasi</strong>. Anda akan di-logout dan harus verifikasi saat login kembali.
                </p>
                
                <div class="flex gap-3">
                    <button 
                        onclick="closeCancelModal()" 
                        class="flex-1 px-4 py-2 btn-secondary text-gray-600 rounded-lg hover:bg-opacity-80"
                    >
                        Lanjutkan Verifikasi
                    </button>
                    <form action="{{ route('user.profile.email.cancel') }}" method="POST" class="flex-1">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full px-4 py-2 btn-danger text-white rounded-lg"
                        >
                            Ya, Batalkan & Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // OTP Input Handler
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpValue = document.getElementById('otpValue');
        const verifyBtn = document.getElementById('verifyBtn');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const value = e.target.value;
                
                if (!/^\d$/.test(value)) {
                    e.target.value = '';
                    return;
                }

                updateOtpValue();

                if (value && index < 5) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').slice(0, 6);
                
                if (!/^\d+$/.test(pasteData)) return;

                pasteData.split('').forEach((char, i) => {
                    if (otpInputs[i]) {
                        otpInputs[i].value = char;
                    }
                });

                updateOtpValue();
                otpInputs[Math.min(pasteData.length, 5)].focus();
            });
        });

        function updateOtpValue() {
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            otpValue.value = otp;
            verifyBtn.disabled = otp.length !== 6;
        }

        // Countdown Timer
        let timeLeft = 60;
        const timerElement = document.getElementById('timer');
        const countdownElement = document.getElementById('countdown');
        const resendBtn = document.getElementById('resendBtn');
        const resendText = document.getElementById('resendText');
        const resendWait = document.getElementById('resendWait');
        const resendTimer = document.getElementById('resendTimer');

        // Show wait message initially
        resendText.classList.add('hidden');
        resendWait.classList.remove('hidden');

        const countdown = setInterval(() => {
            timeLeft--;
            timerElement.textContent = timeLeft;
            resendTimer.textContent = timeLeft;

            if (timeLeft <= 10) {
                countdownElement.classList.add('expired');
            }

            if (timeLeft <= 0) {
                clearInterval(countdown);
                countdownElement.innerHTML = '<span class="text-red-400 font-semibold">Kode OTP telah kadaluarsa</span>';
                
                resendBtn.disabled = false;
                resendText.classList.remove('hidden');
                resendWait.classList.add('hidden');
            }
        }, 1000);

        // Form Submit Handler
        document.getElementById('otpForm').addEventListener('submit', (e) => {
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            verifyBtn.disabled = true;
        });

        // Resend Form Handler
        document.getElementById('resendForm').addEventListener('submit', (e) => {
            resendBtn.disabled = true;
            resendText.textContent = 'Mengirim...';
        });

        // Cancel Modal Functions
        function confirmCancel() {
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }

        // Close modal on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeCancelModal();
            }
        });

        // Auto focus first input
        otpInputs[0].focus();
    </script>
    
</body>
</html>