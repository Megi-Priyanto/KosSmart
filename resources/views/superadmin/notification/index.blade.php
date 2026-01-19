<header class="bg-gradient-to-r from-yellow-500 via-yellow-600 to-orange-500 shadow-md z-10">
    <div class="px-6 py-4 flex items-center justify-between">
        
        <!-- Page Title -->
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                @yield('page-title', 'Dashboard Super Admin')
            </h1>
            <p class="text-sm text-white">
                @yield('page-description', 'Panel kontrol utama sistem KosSmart')
            </p>
        </div>

        <!-- Right Actions -->
        <div class="flex items-center space-x-4">

            <!-- Notifications Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button 
                    @click="open = !open"
                    class="p-2 rounded-lg relative transition text-white hover:bg-white hover:bg-opacity-10">

                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                                 a6.002 6.002 0 00-4-5.659V5
                                 a2 2 0 10-4 0v.341
                                 C7.67 6.165 6 8.388 6 11
                                 v3.159c0 .538-.214 1.055-.595 1.436L4 17h5" />
                    </svg>

                    @php
                        $notifCount = \App\Models\Notification::where('type', 'payment')
                            ->where('status', 'unread')
                            ->count();
                    @endphp

                    @if($notifCount > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5
                                     bg-red-500 text-white
                                     text-xs font-bold rounded-full
                                     flex items-center justify-center shadow">
                            {{ $notifCount }}
                        </span>
                    @endif
                </button>

                <!-- Dropdown -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     class="absolute right-0 mt-2 w-96
                            bg-slate-800 border border-slate-700
                            rounded-lg shadow-xl z-50">

                    <div class="p-4 border-b border-slate-700 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-white">Notifikasi</h3>
                            @if($notifCount > 0)
                                <p class="text-xs text-slate-400">{{ $notifCount }} notifikasi baru</p>
                            @endif
                        </div>
                        @if($notifCount > 0)
                        <form action="{{ route('superadmin.notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-yellow-400 hover:text-yellow-300">
                                Tandai semua dibaca
                            </button>
                        </form>
                        @endif
                    </div>

                    <div class="max-h-96 overflow-y-auto">
                        @php
                            $notifications = \App\Models\Notification::where('type', 'payment')
                                ->latest()
                                ->take(10)
                                ->get();
                        @endphp

                        @forelse($notifications as $notif)
                            <a href="{{ route('superadmin.billing.show', $notif->billing_id) }}"
                               class="block p-4 border-b border-slate-700
                                      {{ $notif->status === 'unread' ? 'bg-yellow-500/10' : '' }}
                                      hover:bg-slate-700 transition">

                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 
                                                {{ $notif->status === 'unread' ? 'bg-yellow-500/20' : 'bg-slate-600' }}
                                                rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 
                                                    {{ $notif->status === 'unread' ? 'text-yellow-400' : 'text-slate-400' }}" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold 
                                                  {{ $notif->status === 'unread' ? 'text-white' : 'text-slate-300' }}">
                                            {{ $notif->title }}
                                        </p>
                                        <p class="text-xs text-slate-400 mt-1">
                                            {{ $notif->message }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-2">
                                            {{ $notif->created_at->diffForHumans() }}
                                        </p>
                                    </div>

                                    @if($notif->status === 'unread')
                                    <div class="w-2 h-2 bg-yellow-400 rounded-full flex-shrink-0"></div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="p-8 text-center">
                                <svg class="w-12 h-12 mx-auto text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-sm text-slate-400">Tidak ada notifikasi</p>
                            </div>
                        @endforelse
                    </div>

                    @if($notifications->count() > 0)
                    <div class="p-3 border-t border-slate-700 text-center">
                        <a href="{{ route('superadmin.notifications.index') }}"
                           class="text-sm text-yellow-400 hover:text-yellow-300 font-medium">
                            Lihat Semua Notifikasi →
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">

                <!-- Trigger -->
                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    class="flex items-center space-x-3 bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-2 rounded-lg transition">

                    <!-- Avatar with Crown -->
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center font-bold text-white relative">
                        {{ substr(Auth::user()->name, 0, 1) }}
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-white rounded-full flex items-center justify-center">
                            <svg class="w-2 h-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Name + Role -->
                    <div class="text-left hidden md:block">
                        <p class="text-sm font-semibold text-white leading-tight">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-xs text-red-100">
                            Super Administrator
                        </p>
                    </div>

                    <!-- Arrow -->
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <a href="{{ route('superadmin.profile') }}"
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