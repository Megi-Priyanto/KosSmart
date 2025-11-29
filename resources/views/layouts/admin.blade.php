<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: true }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - KosSmart</title>
    
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
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="bg-gradient-to-b from-purple-700 to-purple-900 text-white transition-all duration-300 flex-shrink-0 flex flex-col"
        >
            
            <!-- Logo -->
            <div class="p-4 flex items-center justify-between border-b border-purple-600">
                <div class="flex items-center space-x-3" x-show="sidebarOpen">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xl font-bold">KosSmart</span>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded hover:bg-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Menu Navigation -->
            <nav class="p-4 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>
                
                <!-- Kelola User -->
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-purple-600' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span x-show="sidebarOpen">Kelola User</span>
                </a>
                
                <!-- Kelola Kos (Info) -->
                <a href="{{ route('admin.kos.index') }}" 
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('admin.kos.*') ? 'bg-purple-600' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span x-show="sidebarOpen">Informasi Kos</span>
                </a>
                
                <!-- Kelola Kamar -->
                <a href="{{ route('admin.rooms.index') }}" 
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('admin.rooms.*') ? 'bg-purple-600' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span x-show="sidebarOpen">Kelola Kamar</span>
                </a>

                <!-- Booking -->
                <a href="{{ route('admin.bookings.index') }}" 
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('admin.bookings.*') ? 'bg-purple-600' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <div class="flex items-center justify-between flex-1" x-show="sidebarOpen">
                        <span>Booking</span>
                        @php
                            $pendingCount = \App\Models\Rent::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $pendingCount }}
                        </span>
                        @endif
                    </div>
                </a>
                
                <!-- Tagihan -->
                <a href="{{ route('admin.billing.index') }}" 
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('admin.billing.*') ? 'bg-purple-600' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span x-show="sidebarOpen">Tagihan</span>
                </a>

                <!-- Laporan -->
                <a href="{{ route('admin.reports.index') }}" 
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-purple-600' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span x-show="sidebarOpen">Laporan</span>
                </a>
                
                <!-- Pengaturan -->
                <a href="{{ route('admin.settings.index') }}" 
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-purple-600 transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-purple-600' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span x-show="sidebarOpen">Pengaturan</span>
                </a>
            </nav>
            
            <!-- User Profile (Bottom New) -->
            <div class="mt-auto border-t border-purple-600">
                <div class="p-4 flex items-center space-x-3">
                
                    <!-- Foto Profil -->
                    <div class="w-10 h-10 bg-purple-600 bg-opacity-20 backdrop-blur-md rounded-full flex items-center justify-center">
                        <span class="text-sm font-bold text-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </span>
                    </div>
                
                    <!-- Nama + Role -->
                    <div class="flex-1" x-show="sidebarOpen">
                        <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-purple-300">Administrator</p>
                    </div>
                
                    <!-- Logout Icon -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button 
                            class="p-2 rounded-lg hover:bg-purple-700 transition"
                            title="Keluar">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m0-8V7a2 2 0 114 0v1" />
                            </svg>
                        </button>
                    </form>
                
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm border-b border-gray-200 z-10">
                <div class="px-6 py-4 flex items-center justify-between">
                    <!-- Page Title -->
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-sm text-gray-600">@yield('page-description', 'Selamat datang di panel admin KosSmart')</p>
                    </div>
                    
                    <!-- Right Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                @php
                                    $notifCount = \App\Models\Rent::where('status', 'pending')->count();
                                @endphp
                                @if($notifCount > 0)
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                                @endif
                            </button>
                        
                            <!-- Dropdown -->
                            <div
                                x-show="open"
                                @click.away="open = false"
                                x-transition
                                class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200 z-50">
                                <div class="p-3 border-b text-sm font-semibold bg-gray-50">
                                    Notifikasi Booking
                                    @if($notifCount > 0)
                                    <span class="text-red-500">({{ $notifCount }} pending)</span>
                                    @endif
                                </div>
                            
                                <div class="max-h-96 overflow-y-auto">
                                    @php
                                        $pendingBookings = \App\Models\Rent::where('status', 'pending')
                                            ->with(['user', 'room'])
                                            ->latest()
                                            ->take(5)
                                            ->get();
                                    @endphp

                                    @forelse($pendingBookings as $booking)
                                    <a href="{{ route('admin.bookings.show', $booking) }}" 
                                       class="block p-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">Booking Baru</p>
                                                <p class="text-xs text-gray-600">{{ $booking->user->name }} - Kamar {{ $booking->room->room_number }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $booking->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                    @empty
                                    <div class="p-4 text-sm text-gray-600 text-center">
                                        Tidak ada booking pending
                                    </div>
                                    @endforelse
                                </div>

                                @if($pendingBookings->count() > 0)
                                <div class="p-3 bg-gray-50 border-t">
                                    <a href="{{ route('admin.bookings.index') }}" 
                                       class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                                        Lihat Semua Booking →
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Logout -->
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm font-medium">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
                @endif
                
                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
                
            </main>
            
        </div>
        
    </div>
    
    @stack('scripts')
    
</body>
</html>