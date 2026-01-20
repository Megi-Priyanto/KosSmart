<!-- Notifications Booking, Cancel, Checkout, Payment -->
<div x-data="{ open: false }" class="relative">
    <button 
        @click="open = !open"
        class="p-2 rounded-lg relative transition
               text-gray-300 hover:bg-white hover:bg-opacity-10">

        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                     a6.002 6.002 0 00-4-5.659V5
                     a2 2 0 10-4 0v.341
                     C7.67 6.165 6 8.388 6 11
                     v3.159c0 .538-.214 1.055-.595 1.436L4 17h5" />
        </svg>

        @php
            $user = auth()->user();
            
            $pendingBookingsQuery = $user->isSuperAdmin()
                ? \App\Models\Rent::withoutTempatKosScope()
                : \App\Models\Rent::where('tempat_kos_id', $user->tempat_kos_id);
            
            $cancelBookingsQuery = $user->isSuperAdmin()
                ? \App\Models\Rent::withoutTempatKosScope()
                : \App\Models\Rent::where('tempat_kos_id', $user->tempat_kos_id);
            
            $checkoutRequestsQuery = $user->isSuperAdmin()
                ? \App\Models\Rent::withoutTempatKosScope()
                : \App\Models\Rent::where('tempat_kos_id', $user->tempat_kos_id);
          
            $pendingPaymentsQuery = $user->isSuperAdmin()
                ? \App\Models\Payment::withoutTempatKosScope()
                : \App\Models\Payment::where('tempat_kos_id', $user->tempat_kos_id);
            
            $pendingBookingsCount = (clone $pendingBookingsQuery)->where('status', 'pending')->count();
            $cancelBookingsCount = (clone $cancelBookingsQuery)->where('status', 'cancel_booking')->count();
            $checkoutRequestsCount = (clone $checkoutRequestsQuery)->where('status', 'checkout_requested')->count();
            $pendingPaymentsCount = (clone $pendingPaymentsQuery)->where('status', 'pending')->count();
            
            // Total
            $totalNotifCount = $pendingBookingsCount + $cancelBookingsCount + $checkoutRequestsCount + $pendingPaymentsCount;
        @endphp

        @if($totalNotifCount > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5
                         bg-red-500 text-white
                         text-xs font-bold rounded-full
                         flex items-center justify-center shadow">
                {{ $totalNotifCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open"
         @click.away="open = false"
         x-transition
         class="absolute right-0 mt-2 w-80
                bg-slate-800 border border-slate-700
                rounded-lg shadow-xl z-50">

        <div class="p-3 border-b border-slate-700
                    text-sm font-semibold text-yellow-400">
            Semua Notifikasi
            @if($totalNotifCount > 0)
                <span class="text-xs text-slate-400">({{ $totalNotifCount }} total)</span>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">

        @php
            // Get data
            $pendingBookings = (clone $pendingBookingsQuery)
                ->where('status', 'pending')
                ->with(['user','room'])
                ->latest()
                ->take(5)
                ->get()
                ->filter(fn($item) => $item->user && $item->room);

            $cancelBookings = (clone $cancelBookingsQuery)
                ->where('status', 'cancel_booking')
                ->with(['user','room'])
                ->latest()
                ->take(5)
                ->get()
                ->filter(fn($item) => $item->user && $item->room);

            $checkoutRequests = (clone $checkoutRequestsQuery)
                ->where('status', 'checkout_requested')
                ->with(['user','room'])
                ->latest()
                ->take(5)
                ->get()
                ->filter(fn($item) => $item->user && $item->room);

            $pendingPayments = (clone $pendingPaymentsQuery)
                ->where('status', 'pending')
                ->with(['user', 'billing.room'])
                ->latest()
                ->take(5)
                ->get()
                ->filter(fn($item) => $item->user && $item->billing && $item->billing->room);

            $allNotifications = collect()
                ->merge($pendingBookings->map(fn($item) => ['type' => 'booking', 'data' => $item]))
                ->merge($cancelBookings->map(fn($item) => ['type' => 'cancel', 'data' => $item]))
                ->merge($checkoutRequests->map(fn($item) => ['type' => 'checkout', 'data' => $item]))
                ->merge($pendingPayments->map(fn($item) => ['type' => 'payment', 'data' => $item])) 
                ->sortByDesc(fn($item) => $item['data']->created_at)
                ->take(10);
        @endphp

            @forelse($allNotifications as $notification)
                @php
                    $item = $notification['data'];
                    $type = $notification['type'];

                    // Pastikan relasi di-load berdasarkan tipe notifikasi
                    if ($type === 'payment') {
                        // Payment tidak punya relasi 'room', tapi punya 'billing.room'
                        if (!$item->relationLoaded('user')) {
                            $item->load('user');
                        }
                        if (!$item->relationLoaded('billing')) {
                            $item->load('billing.room');
                        }
                    } else {
                        // Untuk booking, cancel, checkout (tipe Rent)
                        if (!$item->relationLoaded('room')) {
                            $item->load('room');
                        }
                        if (!$item->relationLoaded('user')) {
                            $item->load('user');
                        }
                    }

                    $config = [
                        'booking' => [
                            'title' => 'Booking Baru',
                            'color' => 'yellow',
                            'icon' => 'M12 8v4l3 3',
                            'route' => route('admin.bookings.show', $item),
                            'info' => ($item->user ? $item->user->name : 'User tidak ditemukan') . 
                                      ' • Kamar ' . 
                                      ($item->room ? $item->room->room_number : 'N/A')
                        ],
                        'cancel' => [
                            'title' => 'Pembatalan Booking',
                            'color' => 'red',
                            'icon' => 'M6 18L18 6M6 6l12 12',
                            'route' => route('admin.bookings.show', $item),
                            'info' => ($item->user ? $item->user->name : 'User tidak ditemukan') . 
                                      ' • Kamar ' . 
                                      ($item->room ? $item->room->room_number : 'N/A')
                        ],
                        'checkout' => [
                            'title' => 'Permintaan Checkout',
                            'color' => 'orange',
                            'icon' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
                            'route' => route('admin.rooms.show', $item),
                            'info' => ($item->user ? $item->user->name : 'User tidak ditemukan') . 
                                      ' • Kamar ' . 
                                      ($item->room ? $item->room->room_number : 'N/A')
                        ],
                        'payment' => [
                        'title' => 'Pembayaran Baru',
                        'color' => 'blue',
                        'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
                        'route' => $item->billing_id ? route('admin.billing.show', $item->billing_id) : '#',
                        'info' => ($item->user ? $item->user->name : 'User tidak ditemukan') . 
                                  ' • Rp ' . number_format($item->amount, 0, ',', '.')
                    ]
                    ][$type];
                @endphp

                <a href="{{ $config['route'] }}"
                   class="block p-3 border-b border-slate-700
                          hover:bg-slate-700 transition">

                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-{{ $config['color'] }}-500/20
                                    rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-{{ $config['color'] }}-400" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="{{ $config['icon'] }}" />
                            </svg>
                        </div>

                        <div class="ml-3">
                            <p class="text-sm font-semibold text-slate-100">
                                {{ $config['title'] }}
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ $config['info'] }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $item->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-4 text-center text-sm text-slate-400">
                    Tidak ada notifikasi
                </div>
            @endforelse
        </div>

        @if($allNotifications->count() > 0)
            <div class="p-3 border-t border-slate-700 text-center">
                <a href="{{ route('admin.bookings.index') }}"
                   class="text-sm text-yellow-400 hover:text-yellow-300 font-medium">
                    Lihat Semua Notifikasi →
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Billing Notifications -->
<div x-data="{ open: false }" class="relative">
    <button 
        @click="open = !open"
        class="p-2 rounded-lg relative transition
               text-gray-300 hover:bg-white hover:bg-opacity-10">

        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12
                     a2 2 0 002 2h10
                     a2 2 0 002-2V7
                     a2 2 0 00-2-2h-2" />
        </svg>

        @php
            // Hitung semua notifikasi billing yang unread
            $billingNotifCount = \App\Models\Notification::where('user_id', auth()->id())
                ->where('type', 'billing')
                ->where('status', 'unread')
                ->whereNotNull('admin_billing_id')
                ->count();
        @endphp

        @if($billingNotifCount > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5
                         bg-red-600 text-white
                         text-xs font-bold rounded-full
                         flex items-center justify-center shadow">
                {{ $billingNotifCount }}
            </span>
        @endif
    </button>

    <div x-show="open"
         @click.away="open = false"
         x-transition
         class="absolute right-0 mt-2 w-80
                bg-slate-800 border border-slate-700
                rounded-lg shadow-xl z-50">

        <div class="p-3 border-b border-slate-700
                    text-sm font-semibold text-yellow-400">
            Notifikasi Tagihan Operasional
            @if($billingNotifCount > 0)
                <span class="text-xs text-slate-400">({{ $billingNotifCount }} tagihan)</span>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @php
                $billingNotifications = \App\Models\Notification::where('user_id', auth()->id())
                    ->where('type', 'billing')
                    ->where('status', 'unread')
                    ->whereNotNull('admin_billing_id')
                    ->with('adminBilling.tempatKos')
                    ->latest()
                    ->take(10)
                    ->get();
            @endphp

            @forelse($billingNotifications as $notif)
                @php
                    $isOverdue = $notif->due_date && $notif->due_date->isPast();
                    $borderColor = $isOverdue ? 'border-red-500/50' : 'border-yellow-500/50';
                    $bgColor = $isOverdue ? 'bg-red-500/20' : 'bg-yellow-500/20';
                    $textColor = $isOverdue ? 'text-red-400' : 'text-yellow-400';
                @endphp
                
                <a href="{{ route('admin.payments.show', $notif->admin_billing_id) }}" 
                   class="block p-3 border-b border-slate-700 hover:bg-slate-700 transition">
                    <div class="flex items-start">
                        <div class="w-10 h-10 {{ $bgColor }}
                                    rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $textColor }}" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-semibold {{ $textColor }}">
                                {{ $notif->title }}
                            </p>
                            <p class="text-xs text-slate-300 mt-1">
                                {{ $notif->message }}
                            </p>
                            @if($notif->adminBilling)
                            <p class="text-xs text-yellow-400 mt-1">
                                Rp {{ number_format($notif->adminBilling->amount, 0, ',', '.') }}
                            </p>
                            @endif
                            @if($notif->due_date)
                            <p class="text-xs {{ $isOverdue ? 'text-red-400' : 'text-slate-500' }} mt-1">
                                Jatuh tempo: {{ $notif->due_date->format('d M Y') }}
                                @if($isOverdue)
                                    <span class="font-semibold">(TERLAMBAT)</span>
                                @endif
                            </p>
                            @endif
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $notif->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-4 text-center text-sm text-slate-400">
                    Tidak ada notifikasi tagihan
                </div>
            @endforelse
        </div>

        @if($billingNotifications->count() > 0)
            <div class="p-3 border-t border-slate-700 text-center">
                <a href="{{ route('admin.payments.index') }}"
                   class="text-sm text-yellow-400 hover:text-yellow-300 font-medium">
                    Lihat Semua Tagihan →
                </a>
            </div>
        @endif
    </div>
</div>