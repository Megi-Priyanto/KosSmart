@extends('layouts.admin')

@section('title', 'Tagihan Operasional')
@section('page-title', 'Tagihan Operasional')
@section('page-description', 'Kelola pembayaran tagihan operasional aplikasi')

@section('content')
<div class="space-y-6">

    <!-- Notif Overdue -->
    @php
        $overdue = \App\Models\AdminBilling::where('admin_id', Auth::id())
            ->overdue()
            ->first();
    @endphp

    @if($overdue)
    <div class="px-6 py-5 bg-slate-800 border border-red-500/40 rounded-xl shadow-sm">
        <div class="flex items-start gap-4">
            <div class="p-2 bg-red-500/20 rounded-lg">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-red-300 mb-1">
                    Tagihan Terlambat!
                </h3>
                <p class="text-sm text-slate-300 mb-4">
                    Anda memiliki tagihan yang sudah melewati jatuh tempo. Segera lakukan pembayaran untuk menghindari gangguan layanan.
                </p>
                <a href="{{ route('admin.payments.show', $overdue) }}" class="inline-flex items-center text-sm font-medium text-red-400 hover:text-red-300">
                    Bayar Sekarang
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-slate-800 rounded-xl p-6 border border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Total Tagihan</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-xl p-6 border border-yellow-500/40">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Belum Dibayar</p>
                    <p class="text-3xl font-bold text-yellow-400 mt-2">{{ $stats['unpaid'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-xl p-6 border border-red-500/40">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Terlambat</p>
                    <p class="text-3xl font-bold text-red-400 mt-2">{{ $stats['overdue'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-slate-800 rounded-xl p-6 border border-green-500/40">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Lunas</p>
                    <p class="text-3xl font-bold text-green-400 mt-2">{{ $stats['paid'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Billings Table -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-700">
            <h3 class="text-lg font-semibold text-white">Riwayat Tagihan</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase">Periode</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase">Jumlah</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-slate-300 uppercase">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-slate-300 uppercase">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-slate-300 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($billings as $billing)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-white">
                                {{ \Carbon\Carbon::parse($billing->billing_period . '-01')->format('F Y') }}
                            </span>
                            @if($billing->description)
                                <p class="text-xs text-slate-400 mt-1">{{ $billing->description }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-yellow-400">
                                Rp {{ number_format($billing->amount, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm text-slate-300">
                                {{ $billing->due_date->format('d M Y') }}
                            </span>
                            @if($billing->isOverdue())
                                <p class="text-xs text-red-400 mt-1">
                                    Terlambat {{ $billing->due_date->diffForHumans() }}
                                </p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColor = $billing->status_color;
                            @endphp
                            <span class="px-3 py-1 text-xs font-medium bg-{{ $statusColor }}-500/20 text-{{ $statusColor }}-400 rounded-full">
                                {{ $billing->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.payments.show', $billing) }}" 
                               class="text-yellow-400 hover:text-yellow-300 text-sm font-medium">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-slate-400">Tidak ada tagihan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($billings->hasPages())
        <div class="p-4 border-t border-slate-700">
            {{ $billings->links() }}
        </div>
        @endif
    </div>

</div>
@endsection