<!-- BOTTOM NAVBAR (Mobile Only) -->
<div class="md:hidden fixed bottom-0 left-0 w-full z-50"
     style="background:#1e293b; border-top:1px solid #334155; box-shadow:0 -4px 12px rgba(0,0,0,0.4);">
    <div class="flex justify-around py-2">

        <!-- Home -->
        <a href="{{ route('user.dashboard') }}"
           class="flex flex-col items-center text-xs transition-colors
                  {{ request()->routeIs('user.dashboard') ? 'text-yellow-500' : 'text-slate-500 hover:text-slate-300' }}">
            <i class="fa-solid fa-house text-xl mb-0.5"></i>
            <span>Home</span>
        </a>

        <!-- Pembayaran -->
        @if(auth()->user()->hasActiveRoom())
            <a href="{{ route('user.billing.index') }}"
               class="flex flex-col items-center text-xs transition-colors
                      {{ request()->routeIs('user.billing.*') ? 'text-yellow-500' : 'text-slate-500 hover:text-slate-300' }}">
                <i class="fa-solid fa-money-bill-wave text-xl mb-0.5"></i>
                <span>Pembayaran</span>
            </a>
        @else
            <div class="flex flex-col items-center text-xs text-slate-700 cursor-not-allowed">
                <i class="fa-solid fa-money-bill-wave text-xl mb-0.5"></i>
                <span>Pembayaran</span>
            </div>
        @endif

        <!-- Status -->
        <a href="{{ route('user.status.index') }}"
           class="flex flex-col items-center text-xs transition-colors
                  {{ request()->routeIs('user.status.*') ? 'text-yellow-500' : 'text-slate-500 hover:text-slate-300' }}">
            <i class="fa-solid fa-clipboard-list text-xl mb-0.5"></i>
            <span>Status</span>
        </a>

        <!-- Profil -->
        <a href="{{ route('user.profile') }}"
           class="flex flex-col items-center text-xs transition-colors
                  {{ request()->routeIs('user.profile') ? 'text-yellow-500' : 'text-slate-500 hover:text-slate-300' }}">
            <i class="fa-solid fa-user text-xl mb-0.5"></i>
            <span>Profil</span>
        </a>

    </div>
</div>

<style>
    @media (max-width: 768px) {
        body { padding-bottom: 80px; }
    }
</style>
