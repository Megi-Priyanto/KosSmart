@extends('layouts.superadmin')

@section('title', 'Notifikasi Dana Masuk')
@section('page-title', 'Notifikasi Dana Masuk')
@section('page-description', 'Riwayat pembayaran yang dikonfirmasi admin — siap dicairkan ke admin kos')

@section('content')

<!-- Header Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    @php
        $totalNotif   = $notifications->total();
        $unreadNotif  = $unreadCount;
        $holdingCount = \App\Models\Notification::where('user_id', auth()->id())
            ->where('type', 'payment')
            ->whereHas('payment', fn($q) => $q->where('disbursement_status', 'holding'))
            ->count();
    @endphp

    <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center gap-4">
        <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                         a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341
                         C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-white">{{ $unreadNotif }}</p>
            <p class="text-xs text-slate-400">Belum Dibaca</p>
        </div>
    </div>

    <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-white">{{ $holdingCount }}</p>
            <p class="text-xs text-slate-400">Menunggu Pencairan</p>
        </div>
    </div>

    <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                         M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-white">{{ $totalNotif }}</p>
            <p class="text-xs text-slate-400">Total Notifikasi</p>
        </div>
    </div>
</div>

<!-- Toolbar -->
<div class="bg-slate-800 border border-slate-700 rounded-xl p-4 mb-4 flex items-center justify-between">
    <h2 class="text-white font-semibold">Riwayat Dana Masuk</h2>

    @if($unreadCount > 0)
    <form action="{{ route('superadmin.notifications.mark-all-read') }}" method="POST">
        @csrf
        <button type="submit"
                class="flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-400
                       text-slate-900 text-sm font-semibold rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>
            Tandai Semua Dibaca
        </button>
    </form>
    @endif
</div>

<!-- List Notifikasi -->
<div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">

    @forelse($notifications as $notif)
        @php
            $isDisbursed    = $notif->payment && $notif->payment->disbursement_status === 'disbursed';
            $disbursementId = $notif->payment?->disbursement_id;
            $billing        = $notif->payment?->billing;
            $room           = $billing?->room;
            $user           = $billing?->user;
            $kosName        = $room?->kosInfo?->nama_kos ?? '-';

            if ($isDisbursed && $disbursementId) {
                $actionLink  = route('superadmin.disbursements.show', $disbursementId);
                $actionLabel = 'Lihat Pencairan';
                $actionColor = 'text-emerald-400 hover:text-emerald-300';
            } else {
                $actionLink  = route('superadmin.disbursements.index', [
                    'tempat_kos_id' => $notif->payment?->tempat_kos_id
                ]);
                $actionLabel = 'Cairkan Dana →';
                $actionColor = 'text-yellow-400 hover:text-yellow-300';
            }
        @endphp

        <div class="p-5 border-b border-slate-700 transition
                    {{ $notif->status === 'unread' ? 'bg-yellow-500/5 border-l-4 border-l-yellow-500' : '' }}">
            <div class="flex items-start gap-4">

                <!-- Icon -->
                <div class="w-12 h-12 flex-shrink-0 rounded-xl flex items-center justify-center
                            {{ $isDisbursed ? 'bg-emerald-500/20' : 'bg-yellow-500/20' }}">
                    @if($isDisbursed)
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2
                                     m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1
                                     m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                </div>

                <!-- Konten -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="font-semibold {{ $notif->status === 'unread' ? 'text-white' : 'text-slate-200' }}">
                                {{ $notif->title }}
                                @if($notif->status === 'unread')
                                    <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs
                                                 bg-yellow-500/20 text-yellow-400 font-medium">Baru</span>
                                @endif
                            </p>
                            <p class="text-sm text-slate-400 mt-0.5">{{ $notif->message }}</p>
                        </div>

                        <!-- Action -->
                        <a href="{{ $actionLink }}"
                           onclick="markRead({{ $notif->id }})"
                           class="flex-shrink-0 text-sm font-medium {{ $actionColor }} transition">
                            {{ $actionLabel }}
                        </a>
                    </div>

                    <!-- Detail Row -->
                    <div class="flex flex-wrap items-center gap-3 mt-3">
                        <!-- Kos -->
                        @if($kosName !== '-')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg
                                     bg-slate-700 text-slate-300 text-xs">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                            </svg>
                            {{ $kosName }}
                        </span>
                        @endif

                        <!-- Status Disbursement -->
                        @if($isDisbursed)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs
                                         bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                ✓ Sudah Dicairkan
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs
                                         bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                ⏳ Holding — Belum Dicairkan
                            </span>
                        @endif

                        <!-- Waktu -->
                        <span class="text-xs text-slate-500">
                            {{ $notif->created_at->diffForHumans() }} •
                            {{ $notif->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="p-16 text-center">
            <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11
                         a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341
                         C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5
                         m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="text-slate-400 font-medium">Belum ada notifikasi</p>
            <p class="text-slate-500 text-sm mt-1">Notifikasi akan muncul ketika admin mengkonfirmasi pembayaran user.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($notifications->hasPages())
<div class="mt-4">
    {{ $notifications->links() }}
</div>
@endif

@endsection

@push('scripts')
<script>
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
@endpush