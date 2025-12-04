@extends('layouts.admin')

@section('title', 'Detail Informasi Kos')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.kos-info.index') }}" class="text-purple-600 hover:text-purple-700 flex items-center mb-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar Informasi
    </a>
    <h1 class="text-3xl font-bold text-gray-800">📋 Detail Informasi Kos</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Main Content -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            
            <!-- Header with Badge -->
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    @if($kosInfo->icon)
                    <div class="text-4xl mb-2">{{ $kosInfo->icon }}</div>
                    @endif
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $kosInfo->title }}</h2>
                    <div class="flex items-center space-x-2">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-{{ $kosInfo->type_badge_color }}-100 text-{{ $kosInfo->type_badge_color }}-700">
                            {{ $kosInfo->type_label }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $kosInfo->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $kosInfo->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        @if($kosInfo->show_in_detail)
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Diterapkan
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Konten Informasi</h3>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-gray-700 whitespace-pre-line">{{ $kosInfo->content }}</p>
                </div>
            </div>

            <!-- Metadata -->
            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-gray-200">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Urutan Tampil</p>
                    <p class="font-semibold text-gray-800">{{ $kosInfo->order }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Terakhir Diupdate</p>
                    <p class="font-semibold text-gray-800">{{ $kosInfo->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        @if($kosInfo->show_in_detail)
        <div class="mt-6 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl border-2 border-purple-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Preview Tampilan di User
            </h3>
            <div class="bg-white rounded-lg border border-purple-300 p-6">
                <div class="flex items-start">
                    @if($kosInfo->icon)
                    <div class="text-3xl mr-3">{{ $kosInfo->icon }}</div>
                    @endif
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 mb-2">{{ $kosInfo->title }}</h4>
                        <p class="text-gray-700 text-sm">{{ $kosInfo->content }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar Actions -->
    <div class="space-y-6">
        
        <!-- Action Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Aksi</h3>
            
            <div class="space-y-3">
                <!-- Terapkan/Batalkan -->
                <form action="{{ route('admin.kos-info.toggle-apply', $kosInfo->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full px-4 py-3 rounded-lg font-medium transition-colors {{ $kosInfo->show_in_detail ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} flex items-center justify-center space-x-2">
                        @if($kosInfo->show_in_detail)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Batalkan Penerapan</span>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Terapkan ke Detail Kamar</span>
                        @endif
                    </button>
                </form>

                <!-- Edit -->
                <a href="{{ route('admin.kos-info.edit', $kosInfo->id) }}" 
                   class="w-full px-4 py-3 rounded-lg font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit Informasi</span>
                </a>

                <!-- Delete -->
                <form action="{{ route('admin.kos-info.destroy', $kosInfo->id) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus informasi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-3 rounded-lg font-medium bg-red-100 text-red-700 hover:bg-red-200 transition-colors flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span>Hapus Informasi</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Informasi
            </h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li class="flex items-start">
                    <span class="text-blue-600 mr-2">•</span>
                    <span>Informasi yang diterapkan akan muncul di halaman detail kamar</span>
                </li>
                <li class="flex items-start">
                    <span class="text-blue-600 mr-2">•</span>
                    <span>Urutan tampil menentukan posisi informasi</span>
                </li>
                <li class="flex items-start">
                    <span class="text-blue-600 mr-2">•</span>
                    <span>Hanya informasi aktif yang bisa ditampilkan</span>
                </li>
            </ul>
        </div>
    </div>
</div>

@endsection