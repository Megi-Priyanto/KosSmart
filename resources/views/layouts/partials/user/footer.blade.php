<footer style="background:#ffffff;border-top:1px solid #e5e7eb;padding:2rem 2rem 1.25rem;">
    <div style="max-width:1152px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">

        {{-- Brand --}}
        <div style="display:flex;align-items:center;gap:0.5rem;font-weight:800;font-size:1rem;color:#111827;">
            <img src="{{ app_logo() }}" alt="{{ app_name() }}"
                 style="width:28px;height:28px;border-radius:7px;object-fit:cover;">
            {{ app_name() }}
        </div>

        {{-- Nav links --}}
        <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
            <a href="{{ route('user.dashboard') }}"
               style="font-size:0.82rem;color:#6b7280;text-decoration:none;transition:color 0.2s;"
               onmouseover="this.style.color='#d97706'" onmouseout="this.style.color='#6b7280'">Dashboard</a>

            <a href="{{ route('user.rooms.index') }}"
               style="font-size:0.82rem;color:#6b7280;text-decoration:none;transition:color 0.2s;"
               onmouseover="this.style.color='#d97706'" onmouseout="this.style.color='#6b7280'">Kamar Tersedia</a>

            @if(auth()->user()->hasActiveRoom())
                <a href="{{ route('user.billing.index') }}"
                   style="font-size:0.82rem;color:#6b7280;text-decoration:none;transition:color 0.2s;"
                   onmouseover="this.style.color='#d97706'" onmouseout="this.style.color='#6b7280'">Pembayaran</a>
            @else
                <span style="font-size:0.82rem;color:#d1d5db;cursor:not-allowed;">Pembayaran</span>
            @endif

            <a href="{{ route('user.profile') }}"
               style="font-size:0.82rem;color:#6b7280;text-decoration:none;transition:color 0.2s;"
               onmouseover="this.style.color='#d97706'" onmouseout="this.style.color='#6b7280'">Profil</a>
        </div>

    </div>

    {{-- Bottom --}}
    <div style="max-width:1152px;margin:1rem auto 0;padding-top:1rem;border-top:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;font-size:0.75rem;color:#9ca3af;flex-wrap:wrap;gap:0.5rem;">
        <span>© {{ date('Y') }} <strong style="color:#374151;">{{ app_name() }}</strong>. All rights reserved.</span>
        <span style="display:flex;gap:1rem;">
            <a href="#" style="color:#9ca3af;text-decoration:none;" onmouseover="this.style.color='#d97706'" onmouseout="this.style.color='#9ca3af'">Kebijakan Privasi</a>
            <span style="color:#e5e7eb;">|</span>
            <a href="#" style="color:#9ca3af;text-decoration:none;" onmouseover="this.style.color='#d97706'" onmouseout="this.style.color='#9ca3af'">Syarat & Ketentuan</a>
        </span>
    </div>
</footer>