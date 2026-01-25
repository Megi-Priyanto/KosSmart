@extends('layouts.superadmin')

@section('title', 'Kelola Tempat Kos')
@section('page-title', 'Kelola Tempat Kos')
@section('page-description', 'Manajemen seluruh tempat kos dalam sistem')

@section('content')
<div class="space-y-6">

    <!-- Filter & Search -->
   <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <div class="flex flex-col md:flex-row gap-4 items-end justify-between">

            <form method="GET" action="{{ route('superadmin.tempat-kos.index') }}" class="flex-1 flex flex-wrap gap-3">

                <!-- Search -->
                <div class="flex-1">
                    <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari nama kos, kota, atau alamat..."
                       class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500 flex-1 min-w-[200px]">
                </div>

                <!-- Filter Status -->
                <select name="status" 
                        class="px-4 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <button type="submit" 
                        class="inline-flex items-center gap-2
                            bg-gradient-to-r from-yellow-500 to-orange-600
                            text-white font-semibold
                            px-5 py-2 rounded-lg
                            hover:from-yellow-600 hover:to-orange-700
                            transition-all shadow-lg">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>

                    @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('superadmin.tempat-kos.index') }}" 
                       class="px-6 py-2 bg-slate-600 hover:bg-slate-500 text-white rounded-lg font-medium transition">
                        Reset
                    </a>
                    @endif
                </div>
            </form>

            <!-- Action Bar -->
            <div class="flex gap-3">
                <a href="{{ route('superadmin.tempat-kos.create') }}" 
                    class="inline-flex items-center gap-2
                        bg-gradient-to-r from-yellow-500 to-orange-600
                        text-white font-semibold
                        px-5 py-2 rounded-lg
                        hover:from-yellow-600 hover:to-orange-700
                        transition-all shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Tempat Kos
                </a>
            </div>
            
        </div>
    </div>

    <!-- Table -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                            Tempat Kos
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">
                            Lokasi
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-slate-300 uppercase tracking-wider">
                            Statistik
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-slate-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-slate-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($tempatKos as $kos)
                    <tr class="hover:bg-slate-700/50 transition">
                        <!-- Tempat Kos -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($kos->logo)
                                <img src="{{ Storage::url($kos->logo) }}" 
                                     alt="{{ $kos->nama_kos }}"
                                     class="w-12 h-12 rounded-lg object-cover mr-3">
                                @else
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-white font-bold text-lg">{{ substr($kos->nama_kos, 0, 1) }}</span>
                                </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-slate-100">{{ $kos->nama_kos }}</div>
                                    <div class="text-xs text-slate-400">{{ $kos->email ?? 'Tidak ada email' }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Lokasi -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-slate-300">{{ $kos->kota }}</div>
                            <div class="text-xs text-slate-500">{{ $kos->provinsi }}</div>
                        </td>

                        <!-- Statistik -->
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-4">
                                <div class="text-center">
                                    <div class="text-lg font-bold text-blue-400">{{ $kos->rooms_count }}</div>
                                    <div class="text-xs text-slate-400">Kamar</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-green-400">{{ $kos->penghuni_count }}</div>
                                    <div class="text-xs text-slate-400">Penghuni</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold text-purple-400">{{ $kos->admins_count }}</div>
                                    <div class="text-xs text-slate-400">Admin</div>
                                </div>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 text-center">
                            @if($kos->status === 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/30">
                                    <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('superadmin.tempat-kos.show', $kos) }}" 
                                   class="p-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded-lg transition"
                                   title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                
                                <a href="{{ route('superadmin.tempat-kos.edit', $kos) }}" 
                                   class="p-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded-lg transition"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                                                
                                <form id="delete-kos-{{ $kos->id }}" 
                                      action="{{ route('superadmin.tempat-kos.destroy', $kos) }}"
                                      method="POST"
                                      style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                
                                {{-- Delete Button dengan Alpine Modal --}}
                                <button type="button"
                                        @click="$store.modal.confirmDelete(
                                            'Tempat kos {{ $kos->nama_kos }} akan dihapus beserta semua data terkait (kamar, admin, penghuni). Tindakan ini tidak dapat dibatalkan.',
                                            'delete-kos-{{ $kos->id }}',
                                            'Hapus {{ $kos->nama_kos }}?'
                                        )"
                                        class="p-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition"
                                        title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="text-slate-400 text-lg font-medium">Belum ada tempat kos</p>
                            <p class="text-slate-500 text-sm mt-1">Klik tombol "Tambah Tempat Kos" untuk membuat yang pertama</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($tempatKos->hasPages())
        <div class="px-6 py-4 border-t border-slate-700">
            {{ $tempatKos->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-slate-800 rounded-xl max-w-md w-full p-6 border border-slate-700">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-slate-100 mb-2">Hapus Tempat Kos?</h3>
            <p class="text-slate-400 mb-6">Tindakan ini tidak dapat dibatalkan. Semua data terkait akan terhapus.</p>
            
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-100 rounded-lg transition">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection