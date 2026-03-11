@extends('layouts.user')

@section('title', 'Daftar Komplain')

@section('content')

<!-- Header Detail -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Komplain Anda</h1>
        <p class="text-sm text-gray-600 mt-1">Daftar semua laporan kerusakan fasilitas yang telah Anda ajukan</p>
    </div>
    <a href="{{ route('user.tickets.create') }}"
        class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg shadow-md hover:from-yellow-600 hover:to-orange-600 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Buat Laporan Baru
    </a>
</div>

@if (session('success'))
<div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
    <div class="flex items-center">
        <svg class="h-6 w-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
    </div>
</div>
@endif

<!-- Daftar Komplain -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50/80 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 tracking-wider uppercase w-1/2">Judul & Detail</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 tracking-wider uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 tracking-wider uppercase hidden sm:table-cell">Tanggal Pengajuan</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 tracking-wider uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($tickets as $ticket)
                <tr class="hover:bg-yellow-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-900 mb-1">{{ $ticket->title }}</div>
                        <div class="text-xs text-gray-500 whitespace-normal line-clamp-2 max-w-md">{{ $ticket->description }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($ticket->status == 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">Menunggu</span>
                        @elseif($ticket->status == 'in_progress')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">Diproses</span>
                        @elseif($ticket->status == 'resolved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">Selesai</span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">Ditolak</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 hidden sm:table-cell">
                        <div class="text-sm font-medium text-gray-800">{{ $ticket->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $ticket->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('user.tickets.show', $ticket->id) }}" title="Lihat Detail Pesan"
                            class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 hover:border-yellow-200 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-16 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 mb-1">Riwayat Kosong</h3>
                        <p class="text-xs text-gray-500 mb-4">Anda belum pernah mengajukan laporan komplain sejauh ini.</p>
                        <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-yellow-700 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors">
                            Buat Komplain Pertama
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($tickets->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $tickets->links() }}
    </div>
    @endif
</div>

@endsection