{{-- resources/views/layouts/partials/public/footer.blade.php --}}
<footer style="background:#f1f5f9; border-top:1px solid rgba(15,23,42,0.08);" class="mt-auto">
    <div class="w-full px-6 lg:px-10">
        <div class="max-w-6xl mx-auto">

            {{-- Main Footer --}}
            <div class="py-10 flex flex-col md:flex-row justify-between gap-8">

                {{-- Brand --}}
                <div class="max-w-xs">
                    <div class="flex items-center gap-2.5 mb-3">
                        <img src="{{ app_logo() }}" alt="{{ app_name() }}"
                             class="w-8 h-8 rounded-full object-cover">
                        <span class="text-base font-bold text-slate-800">{{ app_name() }}</span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Platform manajemen kos modern untuk penghuni dan pemilik kos Indonesia.
                    </p>
                </div>

                {{-- Links --}}
                <div class="flex gap-12 flex-wrap">
                    <div>
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest mb-3">Penghuni</h4>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('register') }}" class="text-sm text-slate-500 hover:text-amber-600 transition-colors">Daftar Akun</a>
                            <a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-amber-600 transition-colors">Masuk</a>
                            <a href="{{ route('password.request') }}" class="text-sm text-slate-500 hover:text-amber-600 transition-colors">Lupa Password</a>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-widest mb-3">Navigasi</h4>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-amber-600 transition-colors">Beranda</a>
                            <a href="{{ route('tentang') }}" class="text-sm text-slate-500 hover:text-amber-600 transition-colors">Tentang Kami</a>
                            <a href="{{ route('public.kos.index') }}" class="text-sm text-slate-500 hover:text-amber-600 transition-colors">Cari Kos</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div style="border-top:1px solid rgba(15,23,42,0.08);" class="py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <span class="text-xs text-slate-400">© {{ date('Y') }} {{ app_name() }}. Dibuat untuk anak kos Indonesia.</span>
                <div class="flex items-center gap-4">
                    <a href="{{ route('tentang') }}" class="text-xs text-slate-400 hover:text-amber-600 transition-colors">Tentang</a>
                    <a href="{{ route('login') }}" class="text-xs text-slate-400 hover:text-amber-600 transition-colors">Masuk</a>
                </div>
            </div>

        </div>
    </div>
</footer>