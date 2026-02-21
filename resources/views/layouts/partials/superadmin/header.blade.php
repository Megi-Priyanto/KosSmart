<header class="bg-gradient-to-r from-yellow-500 via-yellow-600 to-orange-500 shadow-md z-10">
    <div class="px-6 py-4 flex items-center justify-between">
        
        <!-- Page Title -->
        <div>
            <h1 class="text-2xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                @yield('page-title', 'Dashboard Super Admin')
            </h1>
            <p class="text-sm text-white">
                @yield('page-description', 'Panel kontrol utama sistem KosSmart')
            </p>
        </div>

        <!-- Right Actions -->
        <div class="flex items-center space-x-4">

            <!-- Notifications Dropdown: Dana Masuk dari Admin -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="relative p-2 text-white hover:bg-white hover:bg-opacity-10 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                                 a6.002 6.002 0 00-4-5.659V5
                                 a2 2 0 10-4 0v.341
                                 C7.67 6.165 6 8.388 6 11
                                 v3.159c0 .538-.214 1.055-.595 1.436L4 17h5
                                 m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>

                    @php
                        $superAdminNotifCount = \App\Models\Notification::where('user_id', auth()->id())
                            ->where('type', 'payment')
                            ->where('status', 'unread')
                            ->count();
                    @endphp

                    @if($superAdminNotifCount > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white
                                     text-xs font-bold rounded-full flex items-center justify-center shadow">
                            {{ $superAdminNotifCount }}
                        </span>
                    @endif
                </button>

                <!-- Dropdown -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     class="absolute right-0 mt-2 w-96 bg-slate-800 border border-slate-700
                            rounded-lg shadow-xl z-50">

                    <!-- Header Dropdown -->
                    <div class="p-4 border-b border-slate-700 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-white">Dana Masuk dari Admin</h3>
                            @if($superAdminNotifCount > 0)
                                <p class="text-xs text-slate-400 mt-0.5">{{ $superAdminNotifCount }} belum dibaca</p>
                            @else
                                <p class="text-xs text-slate-400 mt-0.5">Semua sudah dibaca</p>
                            @endif
                        </div>
                        @if($superAdminNotifCount > 0)
                        <form action="{{ route('superadmin.notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-yellow-400 hover:text-yellow-300 transition">
                                Tandai Semua Dibaca
                            </button>
                        </form>
                        @endif
                    </div>

                    <!-- List Notifikasi -->
                    <div class="max-h-96 overflow-y-auto">
                        @php
                            $superAdminNotifs = \App\Models\Notification::where('user_id', auth()->id())
                                ->where('type', 'payment')
                                ->with(['payment.billing.user', 'payment.billing.room', 'payment.disbursement'])
                                ->latest()
                                ->take(10)
                                ->get();
                        @endphp

                        @forelse($superAdminNotifs as $notif)
                            @php
                                $isDisbursed = $notif->payment && $notif->payment->disbursement_status === 'disbursed';
                                $disbursementId = $notif->payment?->disbursement_id;
                                
                                // Link: jika sudah dicairkan → ke detail disbursement, jika belum → ke halaman disbursements
                                if ($isDisbursed && $disbursementId) {
                                    $notifLink = route('superadmin.disbursements.show', $disbursementId);
                                } elseif ($notif->payment) {
                                    $notifLink = route('superadmin.disbursements.index', [
                                        'tempat_kos_id' => $notif->payment->tempat_kos_id
                                    ]);
                                } else {
                                    $notifLink = route('superadmin.disbursements.index');
                                }
                            @endphp

                            <a href="{{ $notifLink }}"
                               onclick="markRead({{ $notif->id }})"
                               class="block p-4 border-b border-slate-700 transition
                                      {{ $notif->status === 'unread' ? 'bg-yellow-500/10 hover:bg-yellow-500/15' : 'hover:bg-slate-700' }}">

                                <div class="flex items-start gap-3">
                                    <!-- Icon -->
                                    <div class="w-10 h-10 flex-shrink-0 rounded-full flex items-center justify-center
                                                {{ $isDisbursed ? 'bg-emerald-500/20' : ($notif->status === 'unread' ? 'bg-yellow-500/20' : 'bg-slate-600') }}">
                                        @if($isDisbursed)
                                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 {{ $notif->status === 'unread' ? 'text-yellow-400' : 'text-slate-400' }}"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2
                                                         m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1
                                                         m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Konten -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold
                                                  {{ $notif->status === 'unread' ? 'text-white' : 'text-slate-300' }}">
                                            {{ $notif->title }}
                                        </p>
                                        <p class="text-xs text-slate-400 mt-0.5 leading-relaxed">
                                            {{ $notif->message }}
                                        </p>

                                        <!-- Status badge: holding / disbursed -->
                                        <div class="flex items-center gap-2 mt-1.5">
                                            @if($isDisbursed)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs
                                                             bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                                    ✓ Sudah Dicairkan
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs
                                                             bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                                    ⏳ Menunggu Pencairan
                                                </span>
                                            @endif
                                            <span class="text-xs text-slate-500">
                                                {{ $notif->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Dot unread -->
                                    @if($notif->status === 'unread')
                                        <div class="w-2 h-2 bg-yellow-400 rounded-full flex-shrink-0 mt-1"></div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="p-8 text-center">
                                <svg class="w-12 h-12 mx-auto text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                                             a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341
                                             C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5
                                             m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-sm text-slate-400">Belum ada dana masuk</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Footer -->
                    @if($superAdminNotifs->count() > 0)
                    <div class="p-3 border-t border-slate-700 text-center">
                        <a href="{{ route('superadmin.notifications.index') }}"
                           class="text-sm text-yellow-400 hover:text-yellow-300 font-medium transition">
                            Lihat Semua Notifikasi →
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    class="flex items-center space-x-3 bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-2 rounded-lg transition">

                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500
                                flex items-center justify-center font-bold text-white relative">
                        {{ substr(Auth::user()->name, 0, 1) }}
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-white rounded-full flex items-center justify-center">
                            <svg class="w-2 h-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="text-left hidden md:block">
                        <p class="text-sm font-semibold text-white leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-red-100">Super Administrator</p>
                    </div>

                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open"
                     x-transition
                     class="absolute right-0 mt-2 w-48 bg-slate-800 rounded-lg border border-slate-700 shadow-lg overflow-hidden z-50">

                    <a href="{{ route('superadmin.profile') }}"
                       class="flex items-center px-4 py-3 text-sm text-slate-100 hover:bg-slate-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Profile
                    </a>

                    <div class="border border-slate-700"></div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center px-4 py-3 text-sm text-red-400 hover:bg-slate-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
/**
 * Tandai notifikasi sebagai dibaca via AJAX saat diklik
 * agar badge langsung update tanpa perlu reload halaman
 */
function markRead(notifId) {
    fetch(`/superadmin/notifications/${notifId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    }).catch(() => {});
}
</script>