@extends('layouts.superadmin')

@section('title', 'Review Pendaftaran')
@section('page-title', 'Review Pendaftaran Admin Kos')
@section('page-description', $adminRegistration->nama_lengkap . ' · ' . $adminRegistration->nama_kos)

@section('content')
<div class="space-y-6">

    {{-- Back --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('superadmin.admin-registrations.index') }}"
           class="flex items-center gap-2 text-slate-400 hover:text-slate-200 text-sm font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar
        </a>

        {{-- Status badge --}}
        @if($adminRegistration->status === 'pending')
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-yellow-500/15 text-yellow-400 border border-yellow-500/25">
                <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2 animate-pulse"></span>
                Menunggu Review
            </span>
        @elseif($adminRegistration->status === 'approved')
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-500/15 text-green-400 border border-green-500/25">
                <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                Disetujui · {{ $adminRegistration->reviewed_at?->format('d M Y H:i') }}
            </span>
        @else
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-red-500/15 text-red-400 border border-red-500/25">
                <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                Ditolak · {{ $adminRegistration->reviewed_at?->format('d M Y H:i') }}
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Data --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Data Diri --}}
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h3 class="text-sm font-bold text-yellow-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Data Diri
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Nama Lengkap</div>
                        <div class="text-sm font-semibold text-slate-100">{{ $adminRegistration->nama_lengkap }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">NIK</div>
                        <div class="text-sm font-mono text-slate-100">{{ $adminRegistration->nik }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Email</div>
                        <div class="text-sm text-slate-100">{{ $adminRegistration->email }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">No. HP</div>
                        <div class="text-sm text-slate-100">{{ $adminRegistration->no_hp }}</div>
                    </div>
                </div>
            </div>

            {{-- Data Kos --}}
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h3 class="text-sm font-bold text-yellow-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Data Kos
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <div class="text-xs text-slate-500 mb-1">Nama Kos</div>
                        <div class="text-sm font-semibold text-slate-100">{{ $adminRegistration->nama_kos }}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-xs text-slate-500 mb-1">Alamat</div>
                        <div class="text-sm text-slate-100">{{ $adminRegistration->alamat_lengkap }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Telepon Kos</div>
                        <div class="text-sm text-slate-100">{{ $adminRegistration->telepon_kos ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Email Kos</div>
                        <div class="text-sm text-slate-100">{{ $adminRegistration->email_kos ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Status Kepemilikan</div>
                        <div class="text-sm text-slate-100">{{ $adminRegistration->tipe_kepemilikan_label }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 mb-1">Tanggal Daftar</div>
                        <div class="text-sm text-slate-100">{{ $adminRegistration->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            {{-- Dokumen --}}
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h3 class="text-sm font-bold text-yellow-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Dokumen
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    {{-- KTP --}}
                    <div>
                        <div class="text-xs text-slate-500 mb-2">Foto KTP</div>
                        @php $ext = pathinfo($adminRegistration->ktp_foto, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                            <a href="{{ Storage::url($adminRegistration->ktp_foto) }}" target="_blank">
                                <img src="{{ Storage::url($adminRegistration->ktp_foto) }}" class="w-full h-32 object-cover rounded-lg border border-slate-600 hover:border-yellow-500 transition cursor-pointer">
                            </a>
                        @else
                            <a href="{{ Storage::url($adminRegistration->ktp_foto) }}" target="_blank"
                               class="flex items-center gap-2 p-3 bg-slate-700 rounded-lg border border-slate-600 hover:border-yellow-500 transition text-sm text-slate-300">
                                <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Lihat PDF KTP
                            </a>
                        @endif
                    </div>

                    {{-- Selfie KTP --}}
                    <div>
                        <div class="text-xs text-slate-500 mb-2">Selfie + KTP</div>
                        <a href="{{ Storage::url($adminRegistration->selfie_ktp) }}" target="_blank">
                            <img src="{{ Storage::url($adminRegistration->selfie_ktp) }}" class="w-full h-32 object-cover rounded-lg border border-slate-600 hover:border-yellow-500 transition cursor-pointer">
                        </a>
                    </div>

                    {{-- Bukti Kepemilikan --}}
                    <div>
                        <div class="text-xs text-slate-500 mb-2">
                            {{ $adminRegistration->tipe_kepemilikan === 'pemilik' ? 'Sertifikat/SHM' : 'Perjanjian Sewa' }}
                        </div>
                        @php $extB = pathinfo($adminRegistration->bukti_kepemilikan, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($extB), ['jpg','jpeg','png']))
                            <a href="{{ Storage::url($adminRegistration->bukti_kepemilikan) }}" target="_blank">
                                <img src="{{ Storage::url($adminRegistration->bukti_kepemilikan) }}" class="w-full h-32 object-cover rounded-lg border border-slate-600 hover:border-yellow-500 transition cursor-pointer">
                            </a>
                        @else
                            <a href="{{ Storage::url($adminRegistration->bukti_kepemilikan) }}" target="_blank"
                               class="flex items-center gap-2 p-3 bg-slate-700 rounded-lg border border-slate-600 hover:border-yellow-500 transition text-sm text-slate-300">
                                <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Lihat Dokumen
                            </a>
                        @endif
                    </div>

                    {{-- NPWP --}}
                    <div>
                        <div class="text-xs text-slate-500 mb-2">NPWP</div>
                        @if($adminRegistration->npwp)
                            @php $extN = pathinfo($adminRegistration->npwp, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($extN), ['jpg','jpeg','png']))
                                <a href="{{ Storage::url($adminRegistration->npwp) }}" target="_blank">
                                    <img src="{{ Storage::url($adminRegistration->npwp) }}" class="w-full h-32 object-cover rounded-lg border border-slate-600 hover:border-yellow-500 transition cursor-pointer">
                                </a>
                            @else
                                <a href="{{ Storage::url($adminRegistration->npwp) }}" target="_blank"
                                   class="flex items-center gap-2 p-3 bg-slate-700 rounded-lg border border-slate-600 hover:border-yellow-500 transition text-sm text-slate-300">
                                    <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Lihat NPWP
                                </a>
                            @endif
                        @else
                            <div class="flex items-center justify-center h-32 bg-slate-700/50 rounded-lg border border-slate-600 border-dashed">
                                <span class="text-xs text-slate-500">Tidak diupload</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Catatan reviewer (jika sudah diproses) --}}
            @if(!$adminRegistration->isPending() && $adminRegistration->catatan)
            <div class="bg-slate-800 rounded-xl border {{ $adminRegistration->isApproved() ? 'border-green-500/30' : 'border-red-500/30' }} p-5">
                <h3 class="text-sm font-bold {{ $adminRegistration->isApproved() ? 'text-green-400' : 'text-red-400' }} uppercase tracking-wider mb-2">
                    {{ $adminRegistration->isApproved() ? 'Catatan Persetujuan' : 'Alasan Penolakan' }}
                </h3>
                <p class="text-sm text-slate-300 leading-relaxed">{{ $adminRegistration->catatan }}</p>
                @if($adminRegistration->reviewer)
                <p class="text-xs text-slate-500 mt-2">— Diproses oleh {{ $adminRegistration->reviewer->name }}</p>
                @endif
            </div>
            @endif

        </div>

        {{-- RIGHT: Actions --}}
        <div class="space-y-4">

            @if($adminRegistration->isPending())
            {{-- APPROVE --}}
            <div class="bg-slate-800 rounded-xl border border-green-500/25 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-green-500/15 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-green-400">Setujui Pengajuan</h3>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">
                    Sistem akan otomatis membuat akun admin dan data kos berdasarkan informasi pendaftaran ini.
                </p>
                <button onclick="document.getElementById('approveModal').classList.remove('hidden')"
                        class="w-full py-2.5 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-bold rounded-lg transition text-sm">
                    Setujui Pengajuan
                </button>
            </div>

            {{-- REJECT --}}
            <div class="bg-slate-800 rounded-xl border border-red-500/25 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-red-500/15 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-red-400">Tolak Pengajuan</h3>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed mb-4">
                    Calon admin akan mendapat email notifikasi beserta alasan penolakan yang Anda tulis.
                </p>
                <button onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                        class="w-full py-2.5 bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800 text-white font-bold rounded-lg transition text-sm">
                    Tolak Pengajuan
                </button>
            </div>

            @else
            {{-- Sudah diproses --}}
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-5 text-center">
                <div class="w-12 h-12 {{ $adminRegistration->isApproved() ? 'bg-green-500/15' : 'bg-red-500/15' }} rounded-full flex items-center justify-center mx-auto mb-3">
                    @if($adminRegistration->isApproved())
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    @else
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    @endif
                </div>
                <p class="text-sm font-semibold {{ $adminRegistration->isApproved() ? 'text-green-400' : 'text-red-400' }}">
                    {{ $adminRegistration->isApproved() ? 'Pengajuan Disetujui' : 'Pengajuan Ditolak' }}
                </p>
                <p class="text-xs text-slate-500 mt-1">{{ $adminRegistration->reviewed_at?->format('d M Y, H:i') }}</p>
            </div>
            @endif

            {{-- Info ringkas --}}
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-5 space-y-3">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ringkasan</h3>
                <div class="space-y-2.5">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">NIK</span>
                        <span class="text-slate-200 font-mono text-xs">{{ $adminRegistration->nik }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Kepemilikan</span>
                        <span class="text-slate-200 text-xs">{{ $adminRegistration->tipe_kepemilikan_label }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">NPWP</span>
                        <span class="{{ $adminRegistration->npwp ? 'text-green-400' : 'text-slate-500' }} text-xs">
                            {{ $adminRegistration->npwp ? '✓ Ada' : 'Tidak ada' }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Kota</span>
                        <span class="text-slate-200 text-xs">{{ $adminRegistration->kota }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- APPROVE MODAL --}}
<div id="approveModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-slate-800 rounded-2xl w-full max-w-md border border-slate-700 shadow-2xl">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-green-500/15 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-100">Setujui Pengajuan?</h3>
                    <p class="text-xs text-slate-400">{{ $adminRegistration->nama_lengkap }} — {{ $adminRegistration->nama_kos }}</p>
                </div>
            </div>

            <div class="bg-slate-700/50 rounded-xl p-4 mb-4 text-xs text-slate-300 leading-relaxed space-y-1">
                <p>✓ Akun admin baru akan dibuat dengan email <strong class="text-slate-100">{{ $adminRegistration->email }}</strong></p>
                <p>✓ Data kos <strong class="text-slate-100">{{ $adminRegistration->nama_kos }}</strong> akan ditambahkan ke sistem</p>
                <p>✓ Email notifikasi persetujuan akan dikirim</p>
            </div>

            <form action="{{ route('superadmin.admin-registrations.approve', $adminRegistration) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">Catatan (Opsional)</label>
                    <textarea name="catatan" rows="3" placeholder="Pesan tambahan untuk admin baru..."
                              class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 text-sm placeholder-slate-500 focus:outline-none focus:border-green-500 resize-none"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')"
                            class="flex-1 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-200 font-semibold rounded-lg transition text-sm">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-bold rounded-lg transition text-sm">
                        Ya, Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- REJECT MODAL --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-slate-800 rounded-2xl w-full max-w-md border border-slate-700 shadow-2xl">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-500/15 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-100">Tolak Pengajuan?</h3>
                    <p class="text-xs text-slate-400">{{ $adminRegistration->nama_lengkap }} — {{ $adminRegistration->nama_kos }}</p>
                </div>
            </div>

            <form action="{{ route('superadmin.admin-registrations.reject', $adminRegistration) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-400 mb-1.5">
                        Alasan Penolakan <span class="text-red-400">*</span>
                    </label>
                    <textarea name="catatan" rows="4" required
                              placeholder="Jelaskan alasan penolakan agar calon admin dapat memperbaiki pengajuannya..."
                              class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded-lg text-slate-100 text-sm placeholder-slate-500 focus:outline-none focus:border-red-500 resize-none"></textarea>
                    @error('catatan')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                            class="flex-1 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-200 font-semibold rounded-lg transition text-sm">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800 text-white font-bold rounded-lg transition text-sm">
                        Ya, Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection