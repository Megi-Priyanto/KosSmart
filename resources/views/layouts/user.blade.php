<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - KosSmart</title>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* memberi ruang agar konten tidak tertutup bottom navbar */
        @media (max-width: 768px) {
            body {
                padding-bottom: 80px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    
    <!-- Top Navbar (DESKTOP) -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xl font-bold text-gray-800">KosSmart</span>
                </div>

                <!-- Menu Desktop -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('user.dashboard') }}" 
                       class="text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'text-purple-600' : 'text-gray-600 hover:text-purple-600' }}">
                        Home
                    </a>
                    <a href="{{ route('user.payments') }}" 
                       class="text-sm font-medium {{ request()->routeIs('user.payments') ? 'text-purple-600' : 'text-gray-600 hover:text-purple-600' }}">
                        Pembayaran
                    </a>
                    <a href="{{ route('user.profile') }}" 
                       class="text-sm font-medium {{ request()->routeIs('user.profile') ? 'text-purple-600' : 'text-gray-600 hover:text-purple-600' }}">
                        Profil
                    </a>
                </div>

                <!-- User Dropdown -->
                <div class="flex items-center space-x-4" x-data="{ dropdownOpen: false }">
                    <div class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" 
                                class="flex items-center space-x-2 text-sm text-gray-600 hover:text-gray-800">
                            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">{{ substr(Auth::user()->name, 0, 2) }}</span>
                            </div>
                            <span class="hidden md:block font-medium">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="dropdownOpen" @click.away="dropdownOpen=false" x-transition
                             class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg border border-gray-200 py-2">
                            <a href="{{ route('user.profile') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profil Saya
                            </a>
                            <hr class="my-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Mobile Menu Lama (DINONAKTIFKAN) -->
        <div class="hidden md:hidden"></div>
    </nav>


    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Flash Success -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-center">
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Flash Error -->
        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center">
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
        @endif

        <!-- PAGE CONTENT -->
        @yield('content')

    </main>


    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center md:text-left">
            <p class="text-sm text-gray-600">© {{ date('Y') }} KosSmart. Hak Cipta Dilindungi.</p>
        </div>
    </footer>



    <!-- BOTTOM NAVBAR (Mobile Only) -->
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-gray-300 shadow-lg z-50">
        <div class="flex justify-around py-2 text-gray-600">
        
            <!-- Dashboard -->
            <a href="/user/dashboard" 
               class="flex flex-col items-center text-xs {{ Request::is('user/dashboard') ? 'text-blue-600 font-semibold' : '' }}">
               <i class="fa-solid fa-house text-xl"></i>
                <span class="mb-1">Home</span>
            </a>
        
            <!-- Pembayaran -->
            <a href="/user/pembayaran" 
               class="flex flex-col items-center text-xs {{ Request::is('user/pembayaran') ? 'text-blue-600 font-semibold' : '' }}">
               <i class="fa-solid fa-money-bill-wave text-xl"></i>
                <span class="mb-1">Pembayaran</span>
            </a>
        
            <!-- Profil -->
            <a href="/user/profile" 
               class="flex flex-col items-center text-xs {{ Request::is('user/profile') ? 'text-blue-600 font-semibold' : '' }}">
                <i class="fa-solid fa-user text-xl"></i>
                <span class="mb-1">Profil</span>
            </a>
        
        </div>
    </div>
    
    <!-- Extra Space so content is not covered -->
    <style>
        @media (max-width: 768px) {
            body {
                padding-bottom: 85px; /* memberi ruang agar tidak tertutup navbar */
            }
            .bottom-nav-active {
                color: #2563eb !important;
                font-weight: 600 !important;
            }
        }
    </style>

    @stack('scripts')
    
</body>
</html>
