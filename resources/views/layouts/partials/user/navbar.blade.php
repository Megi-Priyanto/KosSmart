{{-- resources/views/layouts/partials/user/navbar.blade.php --}}
<nav style="background:#ffffff; border-bottom:1px solid rgba(15,23,42,0.08); box-shadow:0 1px 8px rgba(15,23,42,0.06);" class="sticky top-0 z-50 w-full">
    <div class="w-full px-6 lg:px-10">
        <div class="max-w-6xl mx-auto flex items-center justify-between h-16">

            <!-- Kiri: Logo + Menu -->
            <div class="flex items-center gap-8">

                <!-- Logo -->
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2.5 flex-shrink-0">
                    <img src="{{ app_logo() }}" class="w-9 h-9 rounded-lg object-cover">
                    <span class="text-lg font-bold text-slate-800">{{ app_name() }}</span>
                </a>

                <!-- Menu Desktop -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('user.dashboard') }}"
                        class="text-sm font-medium transition-colors
                              {{ request()->routeIs('user.dashboard') ? 'text-amber-600' : 'text-slate-500 hover:text-slate-800' }}">
                        Beranda
                    </a>

                    @if(auth()->user()->hasActiveRoom())
                    <a href="{{ route('user.billing.index') }}"
                        class="text-sm font-medium transition-colors
                                  {{ request()->routeIs('user.billing.*') ? 'text-amber-600' : 'text-slate-500 hover:text-slate-800' }}">
                        Pembayaran
                    </a>
                    @else
                    <span class="text-sm font-medium text-slate-300 cursor-not-allowed">Pembayaran</span>
                    @endif

                    <a href="{{ route('user.status.index') }}"
                        class="text-sm font-medium transition-colors
                              {{ request()->routeIs('user.status.*') ? 'text-amber-600' : 'text-slate-500 hover:text-slate-800' }}">
                        Status
                    </a>

                    <a href="{{ route('user.profile') }}"
                        class="text-sm font-medium transition-colors
                              {{ request()->routeIs('user.profile') ? 'text-amber-600' : 'text-slate-500 hover:text-slate-800' }}">
                        Profile
                    </a>

                    <a href="{{ route('public.kos.map') }}"
                        class="text-sm font-medium transition-colors
                              {{ request()->routeIs('public.kos.map') ? 'text-amber-600' : 'text-slate-500 hover:text-slate-800' }}">
                        Peta Kos
                    </a>
                </div>
            </div>

            <!-- Kanan: User Dropdown -->
            <div class="flex items-center gap-3" x-data="{ dropdownOpen: false }">
                <div class="relative">
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm text-slate-600 hover:bg-slate-100 hover:text-slate-800 transition-colors">
                        <div class="w-7 h-7 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-xs font-bold">{{ substr(Auth::user()->name, 0, 2) }}</span>
                        </div>
                        <span class="hidden md:block font-medium">{{ Auth::user()->name }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200"
                            :class="dropdownOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
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
                        style="background:#ffffff; border:1px solid rgba(15,23,42,0.1); box-shadow:0 8px 24px rgba(15,23,42,0.1);"
                        class="absolute right-0 mt-2 w-48 rounded-xl shadow-lg py-1 z-50">

                        <a href="{{ route('user.profile') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil Saya
                        </a>

                        <hr style="border-color:rgba(15,23,42,0.08);" class="my-1">

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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