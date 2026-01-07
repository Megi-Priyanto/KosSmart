<!-- Top Navbar (DESKTOP) -->
<nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Logo -->
            <a href="{{ route('user.dashboard') }}"
               class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}"
                    class="w-9 h-9 rounded-full object-cover">

                <span class="text-xl font-bold text-gray-800">KosSmart</span>
            </a>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('user.dashboard') }}" 
                   class="text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'text-yellow-500' : 'text-gray-600 hover:text-yellow-500' }}">
                    Home
                </a>
                <a href="{{ route('user.payments') }}" 
                   class="text-sm font-medium {{ request()->routeIs('user.payments') ? 'text-yellow-500' : 'text-gray-600 hover:text-yellow-500' }}">
                    Pembayaran
                </a>
                <a href="{{ route('user.profile') }}" 
                   class="text-sm font-medium {{ request()->routeIs('user.profile') ? 'text-yellow-500' : 'text-gray-600 hover:text-yellow-500' }}">
                    Profil
                </a>
            </div>

            <!-- User Dropdown -->
            <div class="flex items-center space-x-4" x-data="{ dropdownOpen: false }">
                <div class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" 
                            class="flex items-center space-x-2 text-sm text-gray-600 hover:text-gray-800">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
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
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50">
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