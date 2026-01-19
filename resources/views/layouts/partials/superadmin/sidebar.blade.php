<!-- Sidebar Super Admin -->
<aside 
    :class="sidebarOpen ? 'w-64' : 'w-20'"
    class="bg-gradient-to-b from-gray-900 to-gray-800 text-gray-100 transition-all duration-300 flex-shrink-0 flex flex-col">
    
    <!-- Logo -->
    <div class="p-4 flex items-center justify-between border-b border-gray-700">
        <div class="flex items-center space-x-3" x-show="sidebarOpen">
            <a href="{{ route('superadmin.dashboard') }}"
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
    
    <!-- Info Role -->
    <div class="p-4 border-b border-gray-700" x-show="sidebarOpen">
        <div class="bg-gray-800 rounded-lg p-3">
            <p class="text-xs text-gray-400 mb-1">Role:</p>
            <p class="text-sm font-bold text-yellow-400 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                Super Administrator
            </p>
        </div>
    </div>
    
    <!-- Menu Navigation -->
    <nav class="p-4 space-y-2 flex-1 overflow-y-auto">
        
        <!-- Dashboard -->
        <a href="{{ route('superadmin.dashboard') }}" 
           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors 
                  {{ request()->routeIs('superadmin.dashboard') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span x-show="sidebarOpen">Dashboard</span>
        </a>
        
        <!-- Kelola Tempat Kos -->
        <a href="{{ route('superadmin.tempat-kos.index') }}" 
           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors 
                  {{ request()->routeIs('superadmin.tempat-kos.*') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span x-show="sidebarOpen">Kelola Tempat Kos</span>
        </a>

        <!-- Kelola User -->
        <a href="{{ route('superadmin.users.index') }}" 
           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors 
                  {{ request()->routeIs('superadmin.users.*') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span x-show="sidebarOpen">Kelola User</span>
        </a>

         <!-- Tagihan Admin -->
        <a href="{{ route('superadmin.billing.index') }}" 
           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors 
                  {{ request()->routeIs('superadmin.billing.*') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            
            @php
                $unpaidBilling = \App\Models\AdminBilling::unpaid()->count();
            @endphp
            
            <div class="flex justify-between items-center flex-1" x-show="sidebarOpen">
                <span>Tagihan</span>
                @if($unpaidBilling > 0)
                    <span class="px-2 py-1 bg-yellow-600 text-white rounded-full text-xs">
                        {{ $unpaidBilling }}
                    </span>
                @endif
            </div>
        </a>
        
        <!-- Pengaturan Sistem -->
        <a href="{{ route('superadmin.settings.index') }}" 
           class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-700 transition-colors 
                  {{ request()->routeIs('superadmin.settings.*') ? 'bg-gray-700 text-yellow-400' : 'text-gray-300' }}">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span x-show="sidebarOpen">Pengaturan Sistem</span>
        </a>
        
    </nav>
    
    <!-- Super Admin Profile (Bottom) -->
    <div class="mt-auto border-t border-gray-700">
        <div class="p-4 flex items-center space-x-3">
            
            <!-- Avatar with Crown -->
            <div class="w-10 h-10 bg-yellow-400 bg-opacity-50 rounded-full flex items-center justify-center flex-shrink-0 relative">
                <span class="text-sm font-bold text-white">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </span>
                <!-- Crown Icon -->
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-yellow-400 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3 text-yellow-900" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Info -->
            <div class="flex-1 leading-tight" x-show="sidebarOpen">
                <p class="text-sm font-semibold text-gray-100">
                    <span class="text-yellow-400">{{ Auth::user()->name }}</span>
                </p>
                <p class="text-xs text-gray-400">
                    Super Admin
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