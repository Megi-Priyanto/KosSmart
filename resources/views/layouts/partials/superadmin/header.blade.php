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

            @php
                $refundPendingCount = \App\Models\CancelBooking::withoutTempatKosScope()
                    ->where('status', 'admin_approved')
                    ->count();

                // Notifikasi payment tetap dari tabel notifications
                $paymentNotifCount = \App\Models\Notification::where('user_id', auth()->id())
                    ->where('type', 'payment')
                    ->where('status', 'unread')
                    ->count();

                // Total badge gabungan
                $totalNotifCount = $refundPendingCount + $paymentNotifCount;
            @endphp

            <!-- Bell Notification -->
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
                    @if($totalNotifCount > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white
                                     text-xs font-bold rounded-full flex items-center justify-center shadow animate-pulse">
                            {{ $totalNotifCount > 9 ? '9+' : $totalNotifCount }}
                        </span>
                    @endif
                </button>

                <!-- Dropdown Notifikasi Gabungan -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     class="absolute right-0 mt-2 w-96 bg-slate-800 border border-slate-700
                            rounded-lg shadow-xl z-50">

                    <!-- Header Dropdown -->
                    <div class="p-4 border-b border-slate-700 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-white">Notifikasi</h3>
                            @if($totalNotifCount > 0)
                                <p class="text-xs text-slate-400 mt-0.5">{{ $totalNotifCount }} belum dibaca</p>
                            @else
                                <p class="text-xs text-slate-400 mt-0.5">Semua sudah dibaca</p>
                            @endif
                        </div>
                        @if($paymentNotifCount > 0)
                        <form action="{{ route('superadmin.notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-yellow-400 hover:text-yellow-300 transition">
                                Tandai Semua Dibaca
                            </button>
                        </form>
                        @endif
                    </div>

                    <div class="max-h-[28rem] overflow-y-auto">

                        {{-- ===========================
                             BAGIAN 1: REFUND CANCEL BOOKING
                             Langsung query CancelBooking, pasti akurat
                        =========================== --}}
                        @php
                            $refundItems = \App\Models\CancelBooking::withoutTempatKosScope()
                                ->where('status', 'admin_approved')
                                ->with(['rent.room', 'user', 'tempatKos'])
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp

                        @if($refundItems->count() > 0)
                        <div class="px-4 py-2 bg-orange-500/10 border-b border-slate-700">
                            <p class="text-xs font-bold text-orange-400 uppercase tracking-wide flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Refund Menunggu Diproses ({{ $refundPendingCount }})
                            </p>
                        </div>
                        @foreach($refundItems as $cancel)
                        <a href="{{ route('superadmin.refunds.show', $cancel) }}"
                           class="block p-4 border-b border-slate-700 bg-orange-500/5 hover:bg-orange-500/10 transition">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 flex-shrink-0 rounded-full bg-orange-500/20
                                            flex items-center justify-center">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-white">
                                        Refund DP - {{ $cancel->user->name ?? '-' }}
                                    </p>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ $cancel->tempatKos->nama_kos ?? '-' }} · Kamar {{ $cancel->rent->room->room_number ?? '-' }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs
                                                     bg-orange-500/15 text-orange-400 border border-orange-500/30">
                                            Rp {{ number_format($cancel->rent->deposit_paid ?? 0, 0, ',', '.') }}
                                        </span>
                                        <span class="text-xs text-slate-500">
                                            {{ $cancel->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="w-2 h-2 bg-orange-400 rounded-full flex-shrink-0 mt-1"></div>
                            </div>
                        </a>
                        @endforeach
                        @if($refundPendingCount > 5)
                        <a href="{{ route('superadmin.refunds.index') }}"
                           class="block px-4 py-2.5 text-center text-xs text-orange-400 hover:text-orange-300
                                  bg-orange-500/5 border-b border-slate-700 transition">
                            Lihat {{ $refundPendingCount - 5 }} refund lainnya →
                        </a>
                        @endif
                        @endif

                        {{-- ===========================
                             BAGIAN 2: NOTIFIKASI DANA MASUK (PAYMENT)
                        =========================== --}}
                        @php
                            $paymentNotifs = \App\Models\Notification::where('user_id', auth()->id())
                                ->where('type', 'payment')
                                ->with(['payment.billing.user', 'payment.billing.room', 'payment.disbursement'])
                                ->latest()
                                ->take(10)
                                ->get();
                        @endphp

                        @if($paymentNotifs->count() > 0)
                        <div class="px-4 py-2 bg-slate-700/50 border-b border-slate-700">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Dana Masuk dari Admin
                            </p>
                        </div>
                        @endif

                        @forelse($paymentNotifs as $notif)
                            @php
                                $isDisbursed = $notif->payment && $notif->payment->disbursement_status === 'disbursed';
                                $disbursementId = $notif->payment?->disbursement_id;

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
                                    <div class="w-9 h-9 flex-shrink-0 rounded-full flex items-center justify-center
                                                {{ $isDisbursed ? 'bg-emerald-500/20' : ($notif->status === 'unread' ? 'bg-yellow-500/20' : 'bg-slate-600') }}">
                                        @if($isDisbursed)
                                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 {{ $notif->status === 'unread' ? 'text-yellow-400' : 'text-slate-400' }}"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2
                                                         m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1
                                                         m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold {{ $notif->status === 'unread' ? 'text-white' : 'text-slate-300' }}">
                                            {{ $notif->title }}
                                        </p>
                                        <p class="text-xs text-slate-400 mt-0.5 leading-relaxed line-clamp-2">
                                            {{ $notif->message }}
                                        </p>
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
                                            <span class="text-xs text-slate-500">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    @if($notif->status === 'unread')
                                        <div class="w-2 h-2 bg-yellow-400 rounded-full flex-shrink-0 mt-1"></div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            @if($refundItems->count() === 0)
                            <div class="p-8 text-center">
                                <svg class="w-12 h-12 mx-auto text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                                             a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341
                                             C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5
                                             m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-sm text-slate-400">Belum ada notifikasi</p>
                            </div>
                            @endif
                        @endforelse
                    </div>

                    <!-- Footer -->
                    <div class="p-3 border-t border-slate-700 text-center">
                        <a href="{{ route('superadmin.notifications.index') }}"
                           class="text-sm text-yellow-400 hover:text-yellow-300 font-medium transition">
                            Lihat Semua Notifikasi →
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button
                    @click="open = !open"
                    @click.outside="open = false"
                    class="flex items-center space-x-3 bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-2 rounded-lg transition">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500
                                flex items-center justify-center font-bold text-white">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-yellow-100">Super Admin</p>
                    </div>
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open"
                     x-transition
                     class="absolute right-0 mt-2 w-56 bg-slate-800 border border-slate-700 rounded-lg shadow-xl z-50">
                    <div class="p-3 border-b border-slate-700">
                        <p class="text-sm font-semibold text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="py-1">
                        <a href="{{ route('superadmin.profile') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-slate-300 hover:bg-slate-700 hover:text-white transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>
                        <a href="{{ route('superadmin.settings.index') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-slate-300 hover:bg-slate-700 hover:text-white transition text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Pengaturan
                        </a>
                    </div>
                    <div class="border-t border-slate-700 py-1">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-3 w-full px-4 py-2.5 text-red-400 hover:bg-slate-700 hover:text-red-300 transition text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

<script>
function markRead(notifId) {
    fetch(`/superadmin/notifications/${notifId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Content-Type': 'application/json',
        }
    }).catch(() => {});
}
</script>