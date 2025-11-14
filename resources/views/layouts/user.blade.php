<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - KosSmart</title>
    
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
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    
    <!-- Top Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xl font-bold text-gray-800">KosSmart</span>
                </div>
                
                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('user.dashboard') }}" 
                       class="text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'text-purple-600' : 'text-gray-600 hover:text-purple-600' }} transition-colors">
                        Dashboard
                    </a>
                    <a href="{{ route('user.payments') }}" 
                       class="text-sm font-medium {{ request()->routeIs('user.payments') ? 'text-purple-600' : 'text-gray-600 hover:text-purple-600' }} transition-colors">
                        Pembayaran
                    </a>
                    <a href="{{ route('user.profile') }}" 
                       class="text-sm font-medium {{ request()->routeIs('user.profile') ? 'text-purple-600' : 'text-gray-600 hover:text-purple-600' }} transition-colors">
                        Profil
                    </a>
                </div>
                
                <!-- User Info & Logout -->
                <div class="flex items-center space-x-4" x-data="{ dropdownOpen: false }">
                    <!-- User Dropdown -->
                    <div class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" 
                                class="flex items-center space-x-2 text-sm text-gray-600 hover:text-gray-800 focus:outline-none">
                            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-xs font-bold">{{ substr(Auth::user()->name, 0, 2) }}</span>
                            </div>
                            <span class="font-medium hidden md:block">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="dropdownOpen" 
                             @click.away="dropdownOpen = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                            <a href="{{ route('user.profile') }}" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profil Saya
                            </a>
                            <hr class="my-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="md:hidden px-4 pb-4 border-t border-gray-200 mt-2 pt-2 space-y-2">
            <a href="{{ route('user.dashboard') }}" 
               class="block text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'text-purple-600' : 'text-gray-600' }}">
                Dashboard
            </a>
            <a href="{{ route('user.payments') }}" 
               class="block text-sm font-medium {{ request()->routeIs('user.payments') ? 'text-purple-600' : 'text-gray-600' }}">
                Pembayaran
            </a>
            <a href="{{ route('user.profile') }}" 
               class="block text-sm font-medium {{ request()->routeIs('user.profile') ? 'text-purple-600' : 'text-gray-600' }}">
                Profil
            </a>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
        @endif
        
        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
        @endif
        
        <!-- Page Content -->
        @yield('content')
        
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-600">
                    © {{ date('Y') }} KosSmart. Hak Cipta Dilindungi.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-sm text-gray-600 hover:text-purple-600">Bantuan</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-purple-600">Syarat & Ketentuan</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-purple-600">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>
    
    @stack('scripts')
    
</body>
</html>