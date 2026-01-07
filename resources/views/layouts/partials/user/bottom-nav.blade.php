<!-- BOTTOM NAVBAR (Mobile Only) -->
<div class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-gray-300 shadow-lg z-50">
    <div class="flex justify-around py-2 text-gray-600">
    
        <!-- Dashboard -->
        <a href="{{ route('user.dashboard') }}" 
           class="flex flex-col items-center text-xs {{ request()->routeIs('user.dashboard') ? 'text-yellow-500 font-semibold' : '' }}">
           <i class="fa-solid fa-house text-xl"></i>
            <span class="mb-1">Home</span>
        </a>
    
        <!-- Pembayaran -->
        <a href="{{ route('user.payments') }}" 
           class="flex flex-col items-center text-xs {{ request()->routeIs('user.payments') ? 'text-yellow-500 font-semibold' : '' }}">
           <i class="fa-solid fa-money-bill-wave text-xl"></i>
            <span class="mb-1">Pembayaran</span>
        </a>
    
        <!-- Profil -->
        <a href="{{ route('user.profile') }}" 
           class="flex flex-col items-center text-xs {{ request()->routeIs('user.profile') ? 'text-yellow-500 font-semibold' : '' }}">
            <i class="fa-solid fa-user text-xl"></i>
            <span class="mb-1">Profil</span>
        </a>
    
    </div>
</div>

<!-- Extra Space so content is not covered -->
<style>
    @media (max-width: 768px) {
        body {
            padding-bottom: 85px;
        }
        .bottom-nav-active {
            color: #2563eb !important;
            font-weight: 600 !important;
        }
    }
</style>