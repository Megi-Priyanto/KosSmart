<!-- BOTTOM NAVBAR (Mobile Only) -->
<div class="md:hidden fixed bottom-0 left-0 w-full z-50"
    style="background:#ffffff; border-top:1px solid rgba(15,23,42,0.1); box-shadow:0 -4px 16px rgba(15,23,42,0.08);">
    <div class="flex justify-around py-2">

        <!-- Home -->
        <a href="{{ route('user.dashboard') }}"
            class="flex flex-col items-center text-xs transition-colors
                  {{ request()->routeIs('user.dashboard') ? 'text-amber-600' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-house text-xl mb-0.5"></i>
            <span>Home</span>
        </a>

        <!-- Pembayaran -->
        @if(auth()->user()->hasActiveRoom())
        <a href="{{ route('user.billing.index') }}"
            class="flex flex-col items-center text-xs transition-colors
                      {{ request()->routeIs('user.billing.*') ? 'text-amber-600' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-money-bill-wave text-xl mb-0.5"></i>
            <span>Pembayaran</span>
        </a>
        @else
        <div class="flex flex-col items-center text-xs text-slate-300 cursor-not-allowed">
            <i class="fa-solid fa-money-bill-wave text-xl mb-0.5"></i>
            <span>Pembayaran</span>
        </div>
        @endif

        <!-- Status -->
        <a href="{{ route('user.status.index') }}"
            class="flex flex-col items-center text-xs transition-colors
                  {{ request()->routeIs('user.status.*') ? 'text-amber-600' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-clipboard-list text-xl mb-0.5"></i>
            <span>Status</span>
        </a>

        <!-- Profil -->
        <a href="{{ route('user.profile') }}"
            class="flex flex-col items-center text-xs transition-colors
                  {{ request()->routeIs('user.profile') ? 'text-amber-600' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-user text-xl mb-0.5"></i>
            <span>Profil</span>
        </a>

    </div>
</div>

<style>
    @media (max-width: 768px) {
        body {
            padding-bottom: 80px;
        }
    }
</style>