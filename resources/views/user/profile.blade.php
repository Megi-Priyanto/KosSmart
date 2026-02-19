@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-1 flex items-center gap-2">
            <i class="fa-solid fa-user text-yellow-400"></i>
            Profil Saya
        </h1>
        <p class="text-sm text-gray-600">Kelola informasi profil dan keamanan akun Anda</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        
        <!-- Left Column: Profile Card -->
        <div class="space-y-6">
            
            <!-- Profile Summary Card -->
            <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg shadow-md p-4 text-white">
                <div class="text-center">
                    <!-- Avatar -->
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                        <span class="text-xl font-bold text-yellow-600">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </span>
                    </div>
                    
                    <h2 class="text-base sm:text-lg font-semibold mb-0.5">{{ Auth::user()->name }}</h2>
                    <p class="text-yellow-200 text-xs mb-3">{{ Auth::user()->email }}</p>
                    
                    <!-- Status Badge -->
                    @if(Auth::user()->hasActiveRoom())
                        <span class="inline-flex items-center px-2.5 py-0.5 bg-green-500 text-white text-xs font-semibold rounded-full">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Penyewa Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 bg-yellow-500 text-white text-xs font-semibold rounded-full">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Belum Menyewa
                        </span>
                    @endif
                </div>
                
                <div class="mt-4 pt-4 border-t border-yellow-400 space-y-2">
                    <div class="flex items-center text-xs">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Bergabung {{ Auth::user()->created_at->diffForHumans() }}</span>
                    </div>
                    
                    @if(Auth::user()->email_verified_at)
                    <div class="flex items-center text-xs">
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
                <h3 class="text-sm font-semibold text-gray-800 mb-3">Statistik Saya</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-600">Kamar</span>
                        <span class="text-sm font-medium text-gray-800">{{ $activeRent->room->room_number }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-600">Lama Menyewa</span>
                        <span class="text-sm font-medium text-gray-800">{{ $activeRent->created_at->diffForHumans(null, true) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-600">Total Pembayaran</span>
                        <span class="text-sm font-medium text-gray-800">{{ $totalPayments }}x</span>
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
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">Informasi Pribadi</h3>
                    <button onclick="toggleEdit('personal')" 
                            id="btn-edit-personal"
                            class="text-sm text-yellow-400 hover:text-yellow-500 font-medium">
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
                                Nama Lengkap <span class="text-red-500"></span>
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
                                    class="px-4 py-2 text-center bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-md text-sm hover:from-yellow-600 hover:to-orange-700 transition shadow-md">
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
            
            <!-- Ubah Email -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">Ubah Email</h3>
                    <button onclick="toggleEdit('email')" 
                            id="btn-edit-email"
                            class="text-sm text-yellow-400 hover:text-yellow-500 font-medium">
                        Ubah Email
                    </button>
                </div>
                
                <!-- Display Current Email -->
                <div id="form-email-display">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm text-gray-700">{{ Auth::user()->email }}</span>
                        </div>
                        
                        @if(Auth::user()->email_verified_at)
                            <span class="px-2.5 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                Terverifikasi
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                Belum Terverifikasi
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Form Change Email -->
                <form id="form-email" action="{{ route('user.profile.email.request') }}" method="POST" class="hidden">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Current Email (Read Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Saat Ini
                            </label>
                            <input type="email" 
                                   value="{{ Auth::user()->email }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50"
                                   readonly>
                        </div>
                        
                        <!-- New Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Baru <span class="text-red-500"></span>
                            </label>
                            <input type="email" 
                                   name="new_email" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 @error('new_email') border-red-500 @enderror"
                                   placeholder="email_baru@example.com"
                                   required>
                            @error('new_email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Kode OTP akan dikirim ke email baru ini</p>
                        </div>
                        
                        <!-- Warning -->
                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-yellow-700 font-medium">Perhatian</p>
                                    <p class="text-xs text-yellow-600 mt-1">
                                        Email Anda akan langsung berubah menjadi email baru. 
                                        Anda harus memverifikasi dengan kode OTP yang dikirim ke email baru tersebut.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="pt-4 border-t border-gray-200 flex gap-3">
                            <button type="submit" 
                                    class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-md text-sm hover:from-yellow-600 hover:to-orange-700 transition shadow-md">
                                Simpan Email
                            </button>
                            <button type="button" 
                                    onclick="cancelEdit('email')"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                                Batal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Ubah Password -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">Keamanan Akun</h3>
                    <button onclick="toggleEdit('password')" 
                            id="btn-edit-password"
                            class="text-sm text-yellow-400 hover:text-yellow-500 font-medium">
                        Ubah Password
                    </button>
                </div>
                
                <div id="form-password-display">
                    <div class="flex items-center p-3 bg-gray-50 rounded-md text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span class="text-xs text-gray-600">••••••••••••</span>
                    </div>
                </div>
                
                <form id="form-password" action="{{ route('user.profile.password') }}" method="POST" class="hidden">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password Saat Ini <span class="text-red-500"></span>
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
                                Password Baru <span class="text-red-500"></span>
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
                                Konfirmasi Password Baru <span class="text-red-500"></span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                   required>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="pt-4 border-t border-gray-200 flex gap-3">
                            <button type="submit" 
                                    class="px-4 py-2 text-center bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-md text-sm hover:from-yellow-600 hover:to-orange-700 transition shadow-md">
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
            <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-4">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Informasi Sewa Saya
                </h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600">Kamar</span>
                        <span class="text-sm font-medium text-gray-800">{{ $activeRent->room->room_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600">Lantai</span>
                        <span class="text-sm font-medium text-gray-800">{{ $activeRent->room->floor }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600">Harga Sewa/Bulan</span>
                        <span class="text-sm font-medium text-gray-800">Rp {{ number_format($activeRent->monthly_rent, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600">Mulai Sewa</span>
                        <span class="text-sm font-medium text-gray-800">{{ $activeRent->start_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-yellow-200">
                        <span class="text-xs text-gray-600">Status</span>
                        <span class="px-2.5 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                            Aktif
                        </span>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-yellow-200">
                    <a href="{{ route('user.dashboard') }}" 
                       class="text-sm text-yellow-400 hover:text-yellow-500 font-medium">
                        Lihat Detail Kamar →
                    </a>
                </div>
            </div>
            @endif
            
            <!-- Danger Zone -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-red-800 mb-2">Zona Berbahaya</h3>
                <p class="text-sm text-red-700 mb-4">
                    Tindakan di bawah ini bersifat permanen dan tidak dapat dibatalkan.
                </p>
            
                <!-- Hidden Form -->
                <form id="delete-account-form" 
                      action="{{ route('user.profile.delete') }}" 
                      method="POST" 
                      style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            
                <!-- Delete Button with Alpine Modal -->
                <button type="button"
                        x-data
                        @click.prevent="handleDeleteAccount()"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold text-sm transition-colors cursor-pointer">
                    Hapus Akun
                </button>
            </div>
            
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Toggle edit mode
function toggleEdit(formType) {
    if (formType === 'personal') {
        document.querySelectorAll('#form-personal input').forEach(input => {
            input.removeAttribute('readonly');
            input.classList.remove('bg-gray-50');
            input.classList.add('bg-white');
        });
        document.getElementById('actions-personal').classList.remove('hidden');
        document.getElementById('btn-edit-personal').classList.add('hidden');
    } else if (formType === 'password') {
        document.getElementById('form-password-display').classList.add('hidden');
        document.getElementById('form-password').classList.remove('hidden');
        document.getElementById('btn-edit-password').classList.add('hidden');
    } else if (formType === 'email') {
        document.getElementById('form-email-display').classList.add('hidden');
        document.getElementById('form-email').classList.remove('hidden');
        document.getElementById('btn-edit-email').classList.add('hidden');
    }
}

function cancelEdit(formType) {
    if (formType === 'personal') {
        document.querySelectorAll('#form-personal input').forEach(input => {
            input.setAttribute('readonly', true);
            input.classList.add('bg-gray-50');
            input.classList.remove('bg-white');
        });
        document.getElementById('actions-personal').classList.add('hidden');
        document.getElementById('btn-edit-personal').classList.remove('hidden');
        document.getElementById('form-personal').reset();
    } else if (formType === 'password') {
        document.getElementById('form-password-display').classList.remove('hidden');
        document.getElementById('form-password').classList.add('hidden');
        document.getElementById('btn-edit-password').classList.remove('hidden');
        document.getElementById('form-password').reset();
    } else if (formType === 'email') {
        document.getElementById('form-email-display').classList.remove('hidden');
        document.getElementById('form-email').classList.add('hidden');
        document.getElementById('btn-edit-email').classList.remove('hidden');
        document.getElementById('form-email').reset();
    }
}

// Delete Account Handler
function handleDeleteAccount() {
    // Fallback jika Alpine belum ready
    if (typeof Alpine === 'undefined' || !Alpine.store('modal')) {
        console.error('Alpine.js not ready');
        if (confirm('Apakah Anda yakin ingin menghapus akun? Semua data Anda akan dihapus secara permanen dan tidak dapat dipulihkan.')) {
            document.getElementById('delete-account-form').submit();
        }
        return;
    }
    
    // Gunakan Alpine modal
    Alpine.store('modal').confirmDelete(
        'Apakah Anda yakin ingin menghapus akun? Semua data Anda akan dihapus secara permanen dan tidak dapat dipulihkan.',
        'delete-account-form',
        'Konfirmasi Hapus Akun'
    );
}

// Expose function to window
window.handleDeleteAccount = handleDeleteAccount;

// Debug: Check if Alpine is loaded
document.addEventListener('alpine:init', () => {
    console.log('Alpine initialized');
    console.log('Modal store:', Alpine.store('modal'));
});
</script>
@endpush