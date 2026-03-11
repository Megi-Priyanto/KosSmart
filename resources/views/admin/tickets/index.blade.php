@extends('layouts.admin')

@section('title', 'Manajemen Komplain')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Komplain Penghuni</h1>
        <p class="text-sm text-gray-600 mt-1">Kelola dan pantau semua laporan kerusakan fasilitas dari penghuni kos Anda.</p>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
    <form action="{{ route('admin.tickets.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="w-full sm:w-64">
            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status Laporan</label>
            <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-gray-700 bg-gray-50 hover:bg-white transition-colors">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Respon</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Selesai</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak / Batal</option>
            </select>
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center justify-center px-6 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                Terapkan Filter
            </button>
            @if(request()->has('status') && request()->status != '')
            <a href="{{ route('admin.tickets.index') }}" class="text-sm font-medium text-gray-500 hover:text-red-500 transition-colors">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Data Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50/80 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 tracking-wider uppercase">Waktu</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 tracking-wider uppercase">Penghuni / Kamar</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 tracking-wider uppercase">Judul Kerusakan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 tracking-wider uppercase">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 tracking-wider uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($tickets as $ticket)
                <tr class="hover:bg-yellow-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-gray-800">{{ $ticket->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $ticket->created_at->format('H:i') }} &bull; {{ $ticket->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-800">{{ $ticket->user->name }}</div>
                        <div class="text-xs font-medium text-yellow-600 bg-yellow-100/50 inline-block px-2 py-0.5 rounded mt-1">
                            Kamar {{ $ticket->room->room_number }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-gray-800">{{ Str::limit($ticket->title, 40) }}</span>
                            @if($ticket->photo_path)
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Ada Lampiran Foto">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($ticket->status == 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">Menunggu Respon</span>
                        @elseif($ticket->status == 'in_progress')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">Sedang Diproses</span>
                        @elseif($ticket->status == 'resolved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">Selesai</span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">Ditolak / Batal</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" title="Lihat Detail & Tindak Lanjuti"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 text-gray-600 hover:bg-yellow-100 hover:text-yellow-700 border border-gray-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 mb-1">Tidak Ada Laporan</h3>
                        <p class="text-xs text-gray-500">Belum ada komplain atau laporan kerusakan saat ini.</p>
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