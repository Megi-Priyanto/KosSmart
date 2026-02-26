<!-- Top Navbar -->
<nav style="background:#1e293b; border-bottom:1px solid #334155;" class="sticky top-0 z-50 w-full">
    <div class="w-full px-6 lg:px-10">
        <div class="flex items-center justify-between h-16">

            <!-- Kiri: Logo + Menu -->
            <div class="flex items-center gap-8">

                <!-- Logo -->
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2.5 flex-shrink-0">
                    <img src="{{ app_logo() }}" class="w-9 h-9 rounded-full object-cover">
                    <span class="text-lg font-bold text-white">{{ app_name() }}</span>
                </a>

                <!-- Menu Desktop -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('user.dashboard') }}"
                       class="text-sm font-medium transition-colors
                              {{ request()->routeIs('user.dashboard') ? 'text-yellow-500' : 'text-slate-400 hover:text-white' }}">
                        Beranda
                    </a>

                    @if(auth()->user()->hasActiveRoom())
                        <a href="{{ route('user.billing.index') }}"
                           class="text-sm font-medium transition-colors
                                  {{ request()->routeIs('user.billing.*') ? 'text-yellow-500' : 'text-slate-400 hover:text-white' }}">
                            Pembayaran
                        </a>
                    @else
                        <span class="text-sm font-medium text-slate-600 cursor-not-allowed">Pembayaran</span>
                    @endif

                    <a href="{{ route('user.status.index') }}"
                       class="text-sm font-medium transition-colors
                              {{ request()->routeIs('user.status.*') ? 'text-yellow-500' : 'text-slate-400 hover:text-white' }}">
                        Status
                    </a>

                    <a href="{{ route('user.profile') }}"
                       class="text-sm font-medium transition-colors
                              {{ request()->routeIs('user.rooms.*') ? 'text-yellow-500' : 'text-slate-400 hover:text-white' }}">
                        Profile
                    </a>

                </div>
            </div>

            <!-- Kanan: User Dropdown -->
            <div class="flex items-center gap-3" x-data="{ dropdownOpen: false }">

                <!-- Nama User + Dropdown -->
                <div class="relative">
                    <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                        <div class="w-7 h-7 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-xs font-bold">{{ substr(Auth::user()->name, 0, 2) }}</span>
                        </div>
                        <span class="hidden md:block font-medium">{{ Auth::user()->name }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="dropdownOpen"
                         @click.away="dropdownOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         style="background:#1e293b; border:1px solid #334155;"
                         class="absolute right-0 mt-2 w-48 rounded-lg shadow-2xl py-1 z-50">
                        <a href="{{ route('user.profile') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>
                        <hr style="border-color:#334155;" class="my-1">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-400 hover:bg-red-900/20 hover:text-red-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</nav>