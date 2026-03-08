{{-- resources/views/layouts/partials/public/navbar.blade.php --}}
<nav class="sticky top-0 z-50 w-full backdrop-blur-md"
    style="background:rgba(255,255,255,0.97); border-bottom:1px solid rgba(15,23,42,0.08); box-shadow:0 1px 12px rgba(0,0,0,0.06);">
    <div class="w-full px-6 lg:px-10">
        <div class="max-w-6xl mx-auto flex items-center justify-between h-16">

            {{-- Logo + Menu --}}
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-shrink-0">
                    <img src="{{ app_logo() }}" alt="{{ app_name() }}" class="w-9 h-9 rounded-full object-cover">
                    <span class="text-lg font-bold text-slate-800">{{ app_name() }}</span>
                </a>
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}"
                        class="text-sm font-medium transition-colors
                              {{ request()->routeIs('home') ? 'text-amber-600' : 'text-slate-500 hover:text-slate-800' }}">
                        Beranda
                    </a>
                    <a href="{{ route('tentang') }}"
                        class="text-sm font-medium transition-colors
                              {{ request()->routeIs('tentang') ? 'text-amber-600' : 'text-slate-500 hover:text-slate-800' }}">
                        Tentang Kami
                    </a>
                    <a href="{{ route('public.kos.index') }}"
                        class="text-sm font-medium transition-colors
                              {{ request()->routeIs('public.kos.index') || request()->routeIs('public.kos.rooms') ? 'text-amber-600' : 'text-slate-500 hover:text-slate-800' }}">
                        Cari Kos
                    </a>
                </div>
            </div>

            {{-- Masuk + Daftar --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}"
                    class="px-4 py-1.5 text-sm font-semibold text-slate-600 hover:text-slate-900 border border-slate-200 hover:border-slate-400 rounded-lg transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                    class="px-4 py-1.5 text-sm font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-lg transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-amber-500/30">
                    Daftar
                </a>
            </div>

        </div>
    </div>
</nav>