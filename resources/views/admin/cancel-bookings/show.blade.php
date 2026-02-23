@extends('layouts.admin')

@section('title', 'Detail Pembatalan Booking')
@section('page-title', 'Detail Pembatalan Booking')
@section('page-description', 'Review dan proses permintaan pembatalan dari penghuni')

@section('content')

<div class="mb-6 flex items-center gap-3">
    <span class="px-4 py-2 text-sm font-bold rounded-lg {{ $cancelBooking->status_badge }}">
        {{ $cancelBooking->status_label }}
    </span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Info Detail -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Info User -->
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-5 flex items-center pb-3 border-b border-slate-700">
                <div class="p-2 bg-slate-700 rounded-lg border border-slate-600 mr-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                Informasi Penghuni
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Nama Lengkap</p>
                    <p class="font-bold text-white">{{ $cancelBooking->user->name }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Email</p>
                    <p class="font-semibold text-white">{{ $cancelBooking->user->email }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Nomor Kamar</p>
                    <p class="font-bold text-white">{{ $cancelBooking->rent->room->room_number ?? '-' }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Tanggal Pengajuan</p>
                    <p class="font-semibold text-white">{{ $cancelBooking->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Rekening Refund User -->
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-5 flex items-center pb-3 border-b border-slate-700">
                <div class="p-2 bg-slate-700 rounded-lg border border-slate-600 mr-3">
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                Data Rekening Pengembalian Dana
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Bank</p>
                    <p class="font-bold text-yellow-400 text-lg">{{ $cancelBooking->bank_name }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Nomor Rekening</p>
                    <p class="font-bold text-white text-lg">{{ $cancelBooking->account_number }}</p>
                </div>
                <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                    <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Atas Nama</p>
                    <p class="font-semibold text-white">{{ $cancelBooking->account_holder_name }}</p>
                </div>
            </div>

            <!-- DP Amount -->
            <div class="mt-4 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-300 font-medium">Jumlah DP yang harus dikembalikan</p>
                        <p class="text-xs text-slate-400 mt-1">Berdasarkan DP yang dibayarkan user saat booking</p>
                    </div>
                    <p class="text-2xl font-bold text-yellow-400">
                        Rp {{ number_format($cancelBooking->rent->deposit_paid ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Alasan Cancel -->
        @if($cancelBooking->cancel_reason)
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center pb-3 border-b border-slate-700">
                <div class="p-2 bg-slate-700 rounded-lg border border-slate-600 mr-3">
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                Alasan Pembatalan dari User
            </h3>
            <p class="text-slate-300 bg-slate-900 p-4 rounded-lg border border-slate-700 leading-relaxed">
                {{ $cancelBooking->cancel_reason }}
            </p>
        </div>
        @endif

        <!-- Form Approve ONLY (hanya jika masih pending) -->
        @if($cancelBooking->status === 'pending')
        
        <div x-data="{
                submitApprove() {
                    $store.modal.open({
                        type: 'success',
                        title: 'Setujui Pembatalan?',
                        message: 'Permintaan pembatalan akan diteruskan ke Superadmin untuk proses pengembalian dana DP.',
                        confirmText: 'Ya, Setujui',
                        showCancel: true,
                        onConfirm: () => {
                            document.getElementById('approve-form').submit();
                        }
                    });
                }
             }"
             class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 overflow-hidden shadow-2xl">

            <!-- Header -->
            <div class="p-6 bg-gradient-to-r from-green-500/20 to-emerald-500/20 border-b-2 border-slate-700">
                <h3 class="text-lg font-bold text-white">Proses Permintaan Pembatalan</h3>
                <p class="text-sm text-slate-300 mt-1">
                    Setujui permintaan pembatalan untuk diteruskan ke Superadmin guna proses pengembalian dana DP.
                </p>
            </div>

            <div class="p-6">
                <div class="p-5 bg-green-500/5 border-2 border-green-500/20 rounded-xl">
                    {{-- id="approve-form" diperlukan agar Alpine modal bisa trigger submit --}}
                    <form id="approve-form"
                          action="{{ route('admin.cancel-bookings.approve', $cancelBooking) }}"
                          method="POST">
                        @csrf
                        <h4 class="font-bold text-green-400 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Setujui Pembatalan
                        </h4>
                        <p class="text-xs text-slate-400 mb-4">
                            Permintaan akan diteruskan ke Superadmin untuk proses pengembalian dana DP sebesar
                            <span class="text-yellow-400 font-bold">
                                Rp {{ number_format($cancelBooking->rent->deposit_paid ?? 0, 0, ',', '.') }}
                            </span>
                            ke rekening
                            <span class="text-white font-semibold">{{ $cancelBooking->bank_name }} - {{ $cancelBooking->account_number }}</span>
                            a/n <span class="text-white font-semibold">{{ $cancelBooking->account_holder_name }}</span>.
                        </p>
                        <div class="mb-4">
                            <label class="block text-sm text-slate-300 mb-2">Catatan untuk Superadmin (opsional)</label>
                            <textarea name="admin_approval_notes" rows="3"
                                      placeholder="Tambahkan catatan untuk superadmin jika diperlukan..."
                                      class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 text-white rounded-lg
                                             focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm placeholder-slate-500"></textarea>
                        </div>

                        {{--
                            type="button" — mencegah submit langsung.
                            @click="submitApprove()" — buka Alpine modal dulu,
                            baru submit form saat user klik konfirmasi di modal.
                        --}}
                        <button type="button"
                                @click="submitApprove()"
                                class="w-full py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold
                                       rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg text-base">
                            ✓ Setujui & Teruskan ke Superadmin
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Riwayat proses jika sudah approved -->
        @if(in_array($cancelBooking->status, ['admin_approved', 'approved']))
        <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-white mb-4">Riwayat Proses</h3>
            <div class="space-y-3">
                @if($cancelBooking->admin_approved_at)
                <div class="flex items-start gap-3 p-3 bg-slate-900 rounded-lg border border-slate-700">
                    <div class="w-8 h-8 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-green-400">Disetujui oleh Admin</p>
                        <p class="text-xs text-slate-400">{{ $cancelBooking->admin_approved_at->translatedFormat('d F Y, H:i') }}</p>
                        @if($cancelBooking->admin_approval_notes)
                        <p class="text-xs text-slate-300 mt-1 italic">"{{ $cancelBooking->admin_approval_notes }}"</p>
                        @endif
                    </div>
                </div>
                @endif

                @if($cancelBooking->status === 'approved' && $cancelBooking->processed_at)
                <div class="flex items-start gap-3 p-3 bg-slate-900 rounded-lg border border-slate-700">
                    <div class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-emerald-400">Refund Diproses Superadmin</p>
                        <p class="text-xs text-slate-400">{{ $cancelBooking->processed_at->translatedFormat('d F Y, H:i') }}</p>
                        <p class="text-xs text-slate-300 mt-1">
                            Rp {{ number_format($cancelBooking->refund_amount ?? 0, 0, ',', '.') }}
                            via {{ strtoupper($cancelBooking->refund_sub_method ?? '-') }}
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>

    <!-- Right: Summary Sidebar -->
    <div class="space-y-6">

        <!-- Status Card -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
            <h4 class="font-semibold text-slate-300 mb-4 text-sm uppercase tracking-wide">Ringkasan</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-slate-700">
                    <span class="text-sm text-slate-400">Status</span>
                    <span class="text-xs font-bold px-2 py-1 rounded {{ $cancelBooking->status_badge }}">
                        @if($cancelBooking->status === 'pending') Menunggu
                        @elseif($cancelBooking->status === 'admin_approved') Disetujui
                        @elseif($cancelBooking->status === 'approved') Selesai
                        @else -
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-700">
                    <span class="text-sm text-slate-400">DP User</span>
                    <span class="text-sm font-bold text-yellow-400">
                        Rp {{ number_format($cancelBooking->rent->deposit_paid ?? 0, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-700">
                    <span class="text-sm text-slate-400">Bank</span>
                    <span class="text-sm text-white font-medium">{{ $cancelBooking->bank_name }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-700">
                    <span class="text-sm text-slate-400">No. Rek</span>
                    <span class="text-sm text-white">{{ $cancelBooking->account_number }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-slate-400">Pengajuan</span>
                    <span class="text-sm text-white">{{ $cancelBooking->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Alur proses -->
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
            <h4 class="font-semibold text-slate-300 mb-4 text-sm uppercase tracking-wide">Alur Proses</h4>
            <div class="space-y-3">
                @php
                    $steps = [
                        ['label' => 'User Ajukan Cancel', 'done' => true],
                        ['label' => 'Admin Setujui',      'done' => in_array($cancelBooking->status, ['admin_approved', 'approved'])],
                        ['label' => 'Superadmin Refund',  'done' => $cancelBooking->status === 'approved'],
                        ['label' => 'Selesai',            'done' => $cancelBooking->status === 'approved'],
                    ];
                @endphp
                @foreach($steps as $i => $step)
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0
                                {{ $step['done'] ? 'bg-green-500/30 border-2 border-green-500' : 'bg-slate-700 border-2 border-slate-600' }}">
                        @if($step['done'])
                        <svg class="w-3.5 h-3.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                        @else
                        <span class="text-xs text-slate-500 font-bold">{{ $i + 1 }}</span>
                        @endif
                    </div>
                    <span class="text-sm {{ $step['done'] ? 'text-green-400 font-medium' : 'text-slate-500' }}">
                        {{ $step['label'] }}
                    </span>
                </div>
                @if(!$loop->last)
                <div class="ml-3.5 w-px h-3 bg-slate-700"></div>
                @endif
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection