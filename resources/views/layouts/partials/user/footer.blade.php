<footer style="background:#1e293b; border-top:1px solid #334155; padding:2rem 2rem 1.25rem;">
    <div style="max-width:1152px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">

        {{-- Brand --}}
        <div style="display:flex;align-items:center;gap:0.5rem;font-weight:800;font-size:1rem;color:#f1f5f9;">
            <img src="{{ app_logo() }}" alt="{{ app_name() }}"
                 style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
            {{ app_name() }}
        </div>

        {{-- Nav links --}}
        <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
            <a href="{{ route('user.dashboard') }}"
               style="font-size:0.82rem;color:#94a3b8;text-decoration:none;transition:color 0.2s;"
               onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#94a3b8'">Dashboard</a>

            <a href="{{ route('user.rooms.index') }}"
               style="font-size:0.82rem;color:#94a3b8;text-decoration:none;transition:color 0.2s;"
               onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#94a3b8'">Kamar Tersedia</a>

            @if(auth()->user()->hasActiveRoom())
                <a href="{{ route('user.billing.index') }}"
                   style="font-size:0.82rem;color:#94a3b8;text-decoration:none;transition:color 0.2s;"
                   onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#94a3b8'">Pembayaran</a>
            @else
                <span style="font-size:0.82rem;color:#475569;cursor:not-allowed;">Pembayaran</span>
            @endif

            <a href="{{ route('user.profile') }}"
               style="font-size:0.82rem;color:#94a3b8;text-decoration:none;transition:color 0.2s;"
               onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#94a3b8'">Profil</a>
        </div>

    </div>

    {{-- Bottom --}}
    <div style="max-width:1152px;margin:1rem auto 0;padding-top:1rem;border-top:1px solid #334155;display:flex;justify-content:space-between;align-items:center;font-size:0.75rem;color:#64748b;flex-wrap:wrap;gap:0.5rem;">
        <span>© {{ date('Y') }} <strong style="color:#cbd5e1;">{{ app_name() }}</strong>. All rights reserved.</span>
        <span style="display:flex;gap:1rem;">
            <a href="#" style="color:#64748b;text-decoration:none;" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#64748b'">Kebijakan Privasi</a>
            <span style="color:#334155;">|</span>
            <a href="#" style="color:#64748b;text-decoration:none;" onmouseover="this.style.color='#f59e0b'" onmouseout="this.style.color='#64748b'">Syarat & Ketentuan</a>
        </span>
    </div>
</footer>