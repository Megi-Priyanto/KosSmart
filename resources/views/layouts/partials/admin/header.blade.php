<header class="bg-gradient-to-r from-yellow-500 via-yellow-600 to-orange-500 shadow-md z-10">
    <div class="px-6 py-4 flex items-center justify-between">
        
        <!-- Page Title -->
        <div>
            <h1 class="text-2xl font-bold text-slate-100">
                @yield('page-title', 'Dashboard')
            </h1>
            <p class="text-sm text-slate-800">
                @yield('page-description', 'Selamat datang di panel admin KosSmart')
            </p>
        </div>

        <!-- Right Actions -->
        <div class="flex items-center space-x-4">
            @include('layouts.partials.admin.notifications')

            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">

                <!-- Trigger -->
                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    class="flex items-center space-x-3 bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-2 rounded-lg transition">

                    <!-- Avatar -->
                    <div class="w-9 h-9 rounded-full bg-yellow-400 flex items-center justify-center font-bold text-white">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>

                    <!-- Name + Role -->
                    <div class="text-left hidden md:block">
                        <p class="text-sm font-semibold text-white leading-tight">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-slate-800">
                            Administrator
                        </p>
                    </div>

                    <!-- Arrow -->
                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div
                    x-show="open"
                    x-transition
                    class="absolute right-0 mt-2 w-48 bg-slate-800 rounded-lg border border-slate-700 shadow-lg overflow-hidden z-50">

                    <!-- Profile -->
                    <a href="{{ route('admin.profile') }}"
                       class="flex items-center px-4 py-3 text-sm text-slate-100 hover:bg-slate-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Profile
                    </a>

                    <!-- Divider -->
                    <div class="border border-slate-700"></div>

                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center px-4 py-3 text-sm text-red-400 hover:bg-slate-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</header>
