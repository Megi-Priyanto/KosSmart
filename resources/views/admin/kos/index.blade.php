@extends('layouts.admin')

@section('title', 'Kelola Informasi Kos')

@section('content')

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📋 Kelola Informasi Kos</h1>
            <p class="text-gray-600 mt-1">Kelola informasi yang ditampilkan di halaman detail kamar</p>
        </div>
        <a href="{{ route('admin.kos.create') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Informasi</span>
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" action="{{ route('admin.kos.index') }}" class="flex gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari Informasi..." 
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>
        <div class="w-48">
            <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                <option value="">Semua Tipe</option>
                <option value="tip" {{ request('type') == 'tip' ? 'selected' : '' }}>Tips</option>
                <option value="rule" {{ request('type') == 'rule' ? 'selected' : '' }}>Peraturan</option>
                <option value="facility" {{ request('type') == 'facility' ? 'selected' : '' }}>Fasilitas</option>
                <option value="contact" {{ request('type') == 'contact' ? 'selected' : '' }}>Kontak</option>
                <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>Umum</option>
            </select>
        </div>
        <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
            Cari
        </button>
    </form>
</div>

<!-- Info Cards Grid -->
@if($kosInfo->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($kosInfo as $info)
    <div class="bg-white rounded-xl shadow-sm border-2 {{ $info->show_in_detail ? 'border-green-500' : 'border-gray-200' }} overflow-hidden hover:shadow-lg transition-all">
        
        <!-- Header with Badge -->
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-start mb-2">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-{{ $info->type_badge_color }}-100 text-{{ $info->type_badge_color }}-700">
                    {{ $info->type_label }}
                </span>
                @if($info->show_in_detail)
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Diterapkan
                </span>
                @endif
            </div>
            <h3 class="text-lg font-bold text-gray-800">{{ $info->title }}</h3>
        </div>

        <!-- Content -->
        <div class="p-4">
            <p class="text-gray-700 text-sm line-clamp-3 mb-4">{{ $info->content }}</p>
            
            <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                <span>Urutan: {{ $info->order }}</span>
                <span class="flex items-center">
                    <span class="w-2 h-2 rounded-full mr-1 {{ $info->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                    {{ $info->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-2">
                <!-- Tombol Terapkan -->
                <form action="{{ route('admin.kos-info.toggle-apply', $info->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $info->show_in_detail ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                        @if($info->show_in_detail)
                            ✕ Batalkan
                        @else
                            ✓ Terapkan
                        @endif
                    </button>
                </form>

                <!-- Tombol Detail -->
                <a href="{{ route('admin.kos-info.show', $info->id) }}" 
                   class="w-full px-3 py-2 rounded-lg text-sm font-medium text-purple-700 bg-purple-100 hover:bg-purple-200 transition-colors text-center">
                    Detail
                </a>
            </div>

            <!-- Edit & Delete -->
            <div class="grid grid-cols-2 gap-2 mt-2">
                <a href="{{ route('admin.kos-info.edit', $info->id) }}" 
                   class="w-full px-3 py-2 rounded-lg text-sm font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors text-center">
                    Edit
                </a>
                
                <form action="{{ route('admin.kos-info.destroy', $info->id) }}" method="POST" 
                      onsubmit="return confirm('Yakin ingin menghapus informasi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-3 py-2 rounded-lg text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $kosInfo->links() }}
</div>

@else
<div class="bg-gray-50 rounded-xl p-12 text-center">
    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Informasi</h3>
    <p class="text-gray-600 mb-4">Mulai tambahkan informasi untuk ditampilkan di halaman detail kamar</p>
    <a href="{{ route('admin.kos-info.create') }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
        Tambah Informasi Pertama
    </a>
</div>
@endif

@endsection

@push('styles')
<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush