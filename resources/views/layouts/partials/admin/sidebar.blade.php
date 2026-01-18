<!-- Sidebar Admin Kos -->
<aside 
    :class="sidebarOpen ? 'w-64' : 'w-20'"
    class="bg-gradient-to-b from-gray-900 to-gray-800 text-gray-100 transition-all duration-300 flex-shrink-0 flex flex-col">
    
    <!-- Logo -->
    <div class="p-4 flex items-center justify-between border-b border-gray-700">
        <div class="flex items-center space-x-3" x-show="sidebarOpen">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center space-x-3">
                <img src="{{ asset('images/logo.png') }}"
                    class="w-9 h-9 rounded-full object-cover">
                <span class="text-xl font-bold text-yellow-400">
                    KosSmart
                </span>
            </a>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded hover:bg-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
    
    <!-- Info Tempat Kos -->
    <div class="p-4 border-b border-gray-700" x-show="sidebarOpen">
        <div class="bg-gray-800 rounded-lg p-3">
            <p class="text-xs text-gray-400 mb-1">Mengelola:</p>
            <p class="text-sm font-bold text-yellow-400 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                {{ Auth::user()->tempatKos->nama_kos ?? 'Tempat Kos' }}
            </p>
        </div>
    </div>
    
    <!-- Menu Navigation -->
    <nav class="p-4 space-y-2 flex-1 overflow-y-auto">
        
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors 
                  {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span x-show="sidebarOpen">Dashboard</span>
        </a>
        
        <!-- Informasi Kos -->
        <a href="{{ route('admin.kos.index') }}" 
           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors 
                  {{ request()->routeIs('admin.kos.*') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span x-show="sidebarOpen">Informasi Kos</span>
        </a>
        
        <!-- Kelola Kamar -->
        <a href="{{ route('admin.rooms.index') }}" 
           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors 
                  {{ request()->routeIs('admin.rooms.*') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span x-show="sidebarOpen">Kelola Kamar</span>
        </a>

        <!-- Booking -->
        <a href="{{ route('admin.bookings.index') }}" 
           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors 
                  {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                         M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span x-show="sidebarOpen">Booking</span>
        </a>
        
        <!-- Tagihan -->
        <a href="{{ route('admin.billing.index') }}" 
           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors 
                  {{ request()->routeIs('admin.billing.*') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            
            @php
                $unreadBilling = \App\Models\Notification::where('type', 'billing')
                    ->where('status', 'unread')
                    ->where('tempat_kos_id', Auth::user()->tempat_kos_id)
                    ->count();
            @endphp
            
            <div class="flex justify-between items-center flex-1" x-show="sidebarOpen">
                <span>Tagihan</span>
                @if($unreadBilling > 0)
                    <span class="px-2 py-1 bg-red-600 text-white rounded-full text-xs">
                        {{ $unreadBilling }}
                    </span>
                @endif
            </div>
        </a>

        <!-- Laporan -->
        <a href="{{ route('admin.reports.index') }}" 
           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors 
                  {{ request()->routeIs('admin.reports.*') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span x-show="sidebarOpen">Laporan</span>
        </a>
        
    </nav>
    
    <!-- Admin Profile (Bottom) -->
    <div class="mt-auto border-t border-gray-700">
        <div class="p-4 flex items-center space-x-3">
            
            <!-- Avatar -->
            <div class="w-10 h-10 bg-yellow-500 bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-bold text-white">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </span>
            </div>
            
            <!-- Info -->
            <div class="flex-1 leading-tight" x-show="sidebarOpen">
                <p class="text-sm font-semibold text-gray-100">
                    <span class="text-yellow-400">{{ Auth::user()->name }}</span>
                </p>
                <p class="text-xs text-gray-400">
                    Admin Kos
                </p>
            </div>
            
            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="p-2 rounded-lg hover:bg-gray-700 transition" title="Keluar">
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m0-8V7a2 2 0 114 0v1" />
                    </svg>
                </button>
            </form>
            
        </div>
    </div>
</aside>