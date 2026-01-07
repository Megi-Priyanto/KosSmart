<!-- Notifications Booking -->
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
            $notifCount = \App\Models\Rent::where('status', 'pending')->count();
        @endphp

        @if($notifCount > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5
                         bg-red-500 text-white
                         text-xs font-bold rounded-full
                         flex items-center justify-center shadow">
                {{ $notifCount }}
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
            Notifikasi Booking
            @if($notifCount > 0)
                <span class="text-xs text-slate-400">({{ $notifCount }} pending)</span>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @php
                $pendingBookings = \App\Models\Rent::where('status', 'pending')
                    ->with(['user','room'])
                    ->latest()
                    ->take(5)
                    ->get();
            @endphp

            @forelse($pendingBookings as $booking)
                <a href="{{ route('admin.bookings.show', $booking) }}"
                   class="block p-3 border-b border-slate-700
                          hover:bg-slate-700 transition">

                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-yellow-500/20
                                    rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3" />
                            </svg>
                        </div>

                        <div class="ml-3">
                            <p class="text-sm font-semibold text-slate-100">
                                Booking Baru
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ $booking->user->name }} • Kamar {{ $booking->room->room_number }}
                            </p>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $booking->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-4 text-center text-sm text-slate-400">
                    Tidak ada booking pending
                </div>
            @endforelse
        </div>

        @if($pendingBookings->count() > 0)
            <div class="p-3 border-t border-slate-700 text-center">
                <a href="{{ route('admin.bookings.index') }}"
                   class="text-sm text-yellow-400 hover:text-yellow-300 font-medium">
                    Lihat Semua Booking →
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
            $overdueCount = \App\Models\Notification::where('type','billing')
                ->where('status','unread')->count();
        @endphp

        @if($overdueCount > 0)
            <span class="absolute -top-1 -right-1 w-5 h-5
                         bg-red-600 text-white
                         text-xs font-bold rounded-full
                         flex items-center justify-center shadow">
                {{ $overdueCount }}
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
                    text-sm font-semibold text-red-400">
            Notifikasi Tagihan Overdue
        </div>

        <div class="max-h-96 overflow-y-auto">
            @php
                $overdueNotifications = \App\Models\Notification::where('type','billing')
                    ->where('status','unread')
                    ->latest()->take(5)->get();
            @endphp

            @forelse($overdueNotifications as $notif)
                <div class="p-3 border-b border-slate-700 hover:bg-slate-700">
                    <p class="text-sm font-semibold text-red-400">
                        {{ $notif->data['title'] ?? 'Tagihan Overdue' }}
                    </p>
                    <p class="text-xs text-slate-400">
                        {{ $notif->data['message'] ?? '' }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ $notif->created_at->diffForHumans() }}
                    </p>
                </div>
            @empty
                <div class="p-4 text-center text-sm text-slate-400">
                    Tidak ada tagihan overdue
                </div>
            @endforelse
        </div>
    </div>
</div>
