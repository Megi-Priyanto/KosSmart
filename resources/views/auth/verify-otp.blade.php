<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi OTP - KosSmart</title>
    
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

        /* OTP Input Style */
        .otp-input {
            width: 50px;
            height: 60px;
            font-size: 24px;
            text-align: center;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .otp-input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .countdown {
            font-size: 14px;
            color: #6b7280;
        }

        .countdown.expired {
            color: #ef4444;
            font-weight: 600;
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
            <h1 class="text-3xl font-bold text-white mb-2">Verifikasi Email</h1>
            <p class="text-purple-100">Masukkan kode OTP yang telah dikirim</p>
        </div>
        
        <!-- Card -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8">
            
            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-700 text-sm">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-red-700 text-sm">{{ session('error') }}</p>
                </div>
            </div>
            @endif
            
            <!-- Email Info -->
            <div class="mb-6 p-4 bg-purple-50 rounded-lg border border-purple-200">
                <p class="text-sm text-gray-600 mb-1">Kode OTP telah dikirim ke:</p>
                <p class="font-semibold text-gray-800">{{ session('email') ?? 'email@example.com' }}</p>
            </div>
            
            <!-- OTP Form -->
            <form action="{{ route('verification.otp.verify') }}" method="POST" id="otpForm">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3 text-center">
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
                    
                    <!-- Hidden input untuk kirim ke server -->
                    <input type="hidden" name="otp" id="otpValue">
                    
                    @error('otp')
                    <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Countdown Timer -->
                <div class="text-center mb-6">
                    <p class="countdown" id="countdown">
                        Kode berlaku: <span id="timer" class="font-semibold">60</span> detik
                    </p>
                </div>
                
                <!-- Verify Button -->
                <button 
                    type="submit" 
                    id="verifyBtn"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-all mb-4 disabled:opacity-50 disabled:cursor-not-allowed"
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
            <form action="{{ route('verification.otp.resend') }}" method="POST" id="resendForm">
                @csrf
                <button 
                    type="submit" 
                    id="resendBtn"
                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled
                >
                    <span id="resendText">Kirim Ulang OTP</span>
                    <span id="resendWait" class="hidden">Tunggu <span id="resendTimer">60</span>s</span>
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                    ← Kembali ke Login
                </a>
            </div>
        </div>
        
        <!-- Tips -->
        <div class="mt-6 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl p-4">
            <p class="text-white text-sm">
                <strong>💡 Tips:</strong> Pastikan memeriksa folder spam jika email tidak masuk dalam beberapa menit.
            </p>
        </div>
        
    </div>

    <script>
        // OTP Input Handler
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpValue = document.getElementById('otpValue');
        const verifyBtn = document.getElementById('verifyBtn');

        otpInputs.forEach((input, index) => {
            // Auto focus next input
            input.addEventListener('input', (e) => {
                const value = e.target.value;
                
                // Only allow numbers
                if (!/^\d$/.test(value)) {
                    e.target.value = '';
                    return;
                }

                // Update hidden input
                updateOtpValue();

                // Move to next input
                if (value && index < 5) {
                    otpInputs[index + 1].focus();
                }
            });

            // Handle backspace
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });

            // Paste handler
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
            
            // Enable/disable verify button
            verifyBtn.disabled = otp.length !== 6;
        }

        // Countdown Timer (60 seconds)
        let timeLeft = 60;
        const timerElement = document.getElementById('timer');
        const countdownElement = document.getElementById('countdown');
        const resendBtn = document.getElementById('resendBtn');
        const resendText = document.getElementById('resendText');
        const resendWait = document.getElementById('resendWait');
        const resendTimer = document.getElementById('resendTimer');

        const countdown = setInterval(() => {
            timeLeft--;
            timerElement.textContent = timeLeft;
            resendTimer.textContent = timeLeft;

            if (timeLeft <= 10) {
                countdownElement.classList.add('expired');
            }

            if (timeLeft <= 0) {
                clearInterval(countdown);
                countdownElement.innerHTML = '<span class="text-red-500 font-semibold">⚠️ Kode OTP telah kadaluarsa</span>';
                
                // Enable resend button
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

        // Auto focus first input on load
        otpInputs[0].focus();
    </script>
    
</body>
</html>