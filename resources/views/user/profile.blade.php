@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')

<div class="max-w-5xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center gap-2">
            <i class="fa-solid fa-user text-purple-600"></i>
            Profil Saya
        </h1>
        <p class="text-gray-600">Kelola informasi profil dan keamanan akun Anda</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Profile Card -->
        <div class="space-y-6">
            
            <!-- Profile Summary Card -->
            <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
                <div class="text-center">
                    <!-- Avatar -->
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-4xl font-bold text-purple-600">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </span>
                    </div>
                    
                    <h2 class="text-2xl font-bold mb-1">{{ Auth::user()->name }}</h2>
                    <p class="text-purple-200 text-sm mb-4">{{ Auth::user()->email }}</p>
                    
                    <!-- Status Badge -->
                    @if(Auth::user()->hasActiveRoom())
                        <span class="inline-flex items-center px-3 py-1 bg-green-500 text-white text-sm font-semibold rounded-full">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Penyewa Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 bg-yellow-500 text-white text-sm font-semibold rounded-full">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Belum Menyewa
                        </span>
                    @endif
                </div>
                
                <div class="mt-6 pt-6 border-t border-purple-400 space-y-2">
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Bergabung {{ Auth::user()->created_at->diffForHumans() }}</span>
                    </div>
                    
                    @if(Auth::user()->email_verified_at)
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Email Terverifikasi</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Quick Stats (if has active room) -->
            @if(Auth::user()->hasActiveRoom())
            @php
                $activeRent = Auth::user()->activeRent();
                $totalPayments = Auth::user()->payments()->where('status', 'confirmed')->count();
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Saya</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Kamar</span>
                        <span class="font-semibold text-gray-800">{{ $activeRent->room->room_number }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Lama Menyewa</span>
                        <span class="font-semibold text-gray-800">
                            {{ $activeRent->duration_human }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Pembayaran</span>
                        <span class="font-semibold text-gray-800">{{ $totalPayments }}x</span>
                    </div>
                </div>
            </div>
            @endif
            
        </div>
        
        <!-- Right Column: Forms -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Informasi Pribadi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Informasi Pribadi</h3>
                    <button onclick="toggleEdit('personal')" 
                            id="btn-edit-personal"
                            class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                        Edit
                    </button>
                </div>
                
                <form id="form-personal" action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <!-- Nama -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', Auth::user()->name) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50"
                                   readonly>
                            @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email', Auth::user()->email) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50"
                                   readonly>
                            @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            
                            @if(!Auth::user()->email_verified_at)
                            <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-700">
                                Email belum diverifikasi. 
                                <a href="{{ route('verification.notice') }}" class="underline font-medium">Verifikasi sekarang</a>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Nomor Telepon -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon
                            </label>
                            <input type="text" 
                                   name="phone" 
                                   value="{{ old('phone', Auth::user()->phone) }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50"
                                   placeholder="Contoh: 081234567890"
                                   readonly>
                            @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Nomor telepon untuk dihubungi admin</p>
                        </div>
                        
                        <!-- Action Buttons (Hidden by default) -->
                        <div id="actions-personal" class="hidden pt-4 border-t border-gray-200 flex gap-3">
                            <button type="submit" 
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">
                                Simpan Perubahan
                            </button>
                            <button type="button" 
                                    onclick="cancelEdit('personal')"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Ubah Password -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Keamanan Akun</h3>
                    <button onclick="toggleEdit('password')" 
                            id="btn-edit-password"
                            class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                        Ubah Password
                    </button>
                </div>
                
                <div id="form-password-display">
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span class="text-gray-600">••••••••••••</span>
                    </div>
                </div>
                
                <form id="form-password" action="{{ route('user.profile.password') }}" method="POST" class="hidden">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password Saat Ini <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="current_password" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                   required>
                            @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- New Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                   required>
                            @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                        </div>
                        
                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                   required>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="pt-4 border-t border-gray-200 flex gap-3">
                            <button type="submit" 
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">
                                Simpan Password
                            </button>
                            <button type="button" 
                                    onclick="cancelEdit('password')"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Info Sewa (if has active room) -->
            @if(Auth::user()->hasActiveRoom())
            @php
                $activeRent = Auth::user()->activeRent();
            @endphp
            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl border border-purple-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Informasi Sewa Saya
                </h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kamar</span>
                        <span class="font-semibold text-gray-800">{{ $activeRent->room->room_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Lantai</span>
                        <span class="font-semibold text-gray-800">{{ $activeRent->room->floor }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga Sewa/Bulan</span>
                        <span class="font-semibold text-gray-800">Rp {{ number_format($activeRent->monthly_rent, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mulai Sewa</span>
                        <span class="font-semibold text-gray-800">{{ $activeRent->start_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-purple-200">
                        <span class="text-gray-600">Status</span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                            Aktif
                        </span>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-purple-200">
                    <a href="{{ route('user.dashboard') }}" 
                       class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                        Lihat Detail Kamar →
                    </a>
                </div>
            </div>
            @endif
            
            <!-- Danger Zone -->
            <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                <h3 class="text-lg font-bold text-red-800 mb-2">Zona Berbahaya</h3>
                <p class="text-sm text-red-700 mb-4">
                    Tindakan di bawah ini bersifat permanen dan tidak dapat dibatalkan.
                </p>
                
                <button onclick="confirmDelete()" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium text-sm">
                    Hapus Akun
                </button>
            </div>
            
        </div>
        
    </div>
    
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-red-600 mb-4">Konfirmasi Hapus Akun</h3>
        <p class="text-gray-700 mb-6">
            Apakah Anda yakin ingin menghapus akun? Semua data Anda akan dihapus secara permanen dan tidak dapat dipulihkan.
        </p>
        
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" 
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                Batal
            </button>
            <form action="{{ route('user.profile.delete') }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                    Ya, Hapus Akun
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Toggle edit mode
function toggleEdit(formType) {
    if (formType === 'personal') {
        // Enable inputs
        document.querySelectorAll('#form-personal input').forEach(input => {
            input.removeAttribute('readonly');
            input.classList.remove('bg-gray-50');
            input.classList.add('bg-white');
        });
        
        // Show action buttons
        document.getElementById('actions-personal').classList.remove('hidden');
        
        // Hide edit button
        document.getElementById('btn-edit-personal').classList.add('hidden');
    } else if (formType === 'password') {
        // Hide display
        document.getElementById('form-password-display').classList.add('hidden');
        
        // Show form
        document.getElementById('form-password').classList.remove('hidden');
        
        // Hide edit button
        document.getElementById('btn-edit-password').classList.add('hidden');
    }
}

// Cancel edit
function cancelEdit(formType) {
    if (formType === 'personal') {
        // Disable inputs
        document.querySelectorAll('#form-personal input').forEach(input => {
            input.setAttribute('readonly', true);
            input.classList.add('bg-gray-50');
            input.classList.remove('bg-white');
        });
        
        // Hide action buttons
        document.getElementById('actions-personal').classList.add('hidden');
        
        // Show edit button
        document.getElementById('btn-edit-personal').classList.remove('hidden');
        
        // Reset form
        document.getElementById('form-personal').reset();
    } else if (formType === 'password') {
        // Show display
        document.getElementById('form-password-display').classList.remove('hidden');
        
        // Hide form
        document.getElementById('form-password').classList.add('hidden');
        
        // Show edit button
        document.getElementById('btn-edit-password').classList.remove('hidden');
        
        // Reset form
        document.getElementById('form-password').reset();
    }
}

// Delete modal
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush