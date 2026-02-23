@extends('layouts.superadmin')

@section('title', 'Detail & Proses Refund')
@section('page-title', 'Proses Refund Cancel Booking')
@section('page-description', 'Transfer dana DP kembali ke user sesuai nominal yang dibayarkan')

@section('content')
<div class="space-y-6">

    <div class="flex items-center gap-3">
        <span class="px-4 py-2 text-sm font-bold rounded-lg {{ $cancelBooking->status_badge }}">
            {{ $cancelBooking->status_label }}
        </span>
    </div>

    {{-- PERBAIKAN: Hapus items-start agar kedua kolom bisa scroll bersamaan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Info + Form -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Data User & Booking -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-white mb-5 flex items-center pb-3 border-b border-slate-700">
                    <div class="p-2 bg-slate-700 rounded-lg border border-slate-600 mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    Informasi Penghuni & Booking
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Nama User</p>
                        <p class="font-bold text-white">{{ $cancelBooking->user->name }}</p>
                    </div>
                    <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Email</p>
                        <p class="font-semibold text-white text-sm">{{ $cancelBooking->user->email }}</p>
                    </div>
                    <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Tempat Kos</p>
                        <p class="font-semibold text-white">{{ $cancelBooking->tempatKos->nama_kos ?? '-' }}</p>
                    </div>
                    <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Nomor Kamar</p>
                        <p class="font-bold text-white">{{ $cancelBooking->rent->room->room_number ?? '-' }}</p>
                    </div>
                    <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Mulai Sewa</p>
                        <p class="font-semibold text-white">{{ $cancelBooking->rent->start_date?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Tanggal Pengajuan</p>
                        <p class="font-semibold text-white">{{ $cancelBooking->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Rekening User untuk Refund -->
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-yellow-500/30 p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-white mb-5 flex items-center pb-3 border-b border-slate-700">
                    <div class="p-2 bg-slate-700 rounded-lg border border-slate-600 mr-3">
                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    Rekening Tujuan Refund (dari User)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                    <div class="p-4 bg-yellow-500/10 rounded-lg border border-yellow-500/30">
                        <p class="text-xs text-yellow-300 uppercase tracking-wide mb-1.5">Bank Tujuan</p>
                        <p class="font-bold text-yellow-400 text-xl">{{ $cancelBooking->bank_name }}</p>
                    </div>
                    <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Nomor Rekening</p>
                        <p class="font-bold text-white text-lg tracking-wider">{{ $cancelBooking->account_number }}</p>
                    </div>
                    <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-1.5">Atas Nama</p>
                        <p class="font-bold text-white">{{ $cancelBooking->account_holder_name }}</p>
                    </div>
                </div>

                <!-- Nominal DP -->
                <div class="p-5 bg-gradient-to-r from-yellow-500/10 to-orange-500/10 border-2 border-yellow-500/40 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-base font-semibold text-yellow-300">Nominal DP yang Harus Dikembalikan</p>
                            <p class="text-sm text-slate-400 mt-1">Sesuai DP yang dibayarkan user saat booking</p>
                            @if($cancelBooking->cancel_reason)
                            <p class="text-xs text-slate-400 mt-2 italic">"{{ $cancelBooking->cancel_reason }}"</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-yellow-400">
                                Rp {{ number_format($defaultRefundAmount, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-slate-400 mt-1">50% dari harga kamar</p>
                        </div>
                    </div>
                </div>

                @if($cancelBooking->admin_approval_notes)
                <div class="mt-4 p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                    <p class="text-xs text-blue-300 font-semibold mb-1">Catatan dari Admin Kos:</p>
                    <p class="text-sm text-slate-300">{{ $cancelBooking->admin_approval_notes }}</p>
                </div>
                @endif
            </div>

            {{-- Form Refund (hanya jika status admin_approved) --}}
            @if($cancelBooking->status === 'admin_approved')
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-slate-700/50 overflow-hidden shadow-2xl"
                 x-data="{
                    ...refundForm(),
                    submitRefund() {
                        $store.modal.open({
                            type: 'warning',
                            title: 'Konfirmasi Proses Refund',
                            message: 'Pastikan dana sudah ditransfer ke rekening user sebelum mengkonfirmasi. Proses ini tidak dapat dibatalkan.',
                            confirmText: 'Ya, Konfirmasi Refund',
                            showCancel: true,
                            onConfirm: () => {
                                document.getElementById('refund-form').submit();
                            }
                        });
                    }
                 }">

                <!-- Header Form -->
                <div class="p-6 bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border-b-2 border-slate-700">
                    <h2 class="text-xl font-bold text-white">Form Pengembalian Dana</h2>
                    <p class="text-sm text-slate-300 mt-1">Transfer dana DP ke rekening user di atas, lalu upload bukti transfer</p>
                </div>

                <form id="refund-form"
                      action="{{ route('superadmin.refunds.process', $cancelBooking) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="p-6 space-y-6">

                        <!-- Metode Transfer -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Metode Pengembalian <span class="text-red-400">*</span>
                            </label>
                            <button type="button" @click="showMethodModal = true"
                                    class="w-full px-5 py-3 bg-gradient-to-r from-yellow-500 to-orange-600
                                           text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-700
                                           transition-all shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <span x-text="selectedMethod ? 'Ubah Metode Transfer' : 'Pilih Metode Transfer'"></span>
                            </button>

                            <input type="hidden" name="refund_method" x-model="selectedMethod">
                            <input type="hidden" name="refund_sub_method" x-model="selectedSubMethod">

                            <div x-show="selectedMethod" class="mt-3 p-3 bg-slate-900 border border-slate-700 rounded-lg flex items-center gap-3">
                                <div class="w-9 h-9 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-300">Metode dipilih:</p>
                                    <p class="text-base font-bold text-white" x-text="selectedSubMethodName"></p>
                                </div>
                            </div>
                            @error('refund_method')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Rekening Sumber -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Nomor Rekening Sumber Transfer <span class="text-red-400">*</span>
                            </label>
                            <input type="text"
                                   name="refund_account_number"
                                   value="{{ old('refund_account_number') }}"
                                   placeholder="Nomor rekening yang digunakan untuk transfer"
                                   class="w-full px-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-lg
                                          focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500
                                          placeholder-slate-500 transition">
                            @error('refund_account_number')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nominal Refund -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Jumlah yang Dikembalikan <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">Rp</span>
                                <input type="number"
                                       name="refund_amount"
                                       value="{{ old('refund_amount', $defaultRefundAmount) }}"
                                       min="0"
                                       class="w-full pl-12 pr-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-lg
                                              focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                            </div>
                            <p class="text-xs text-slate-400 mt-1.5">
                                Default nominal DP: <span class="text-yellow-400 font-bold">Rp {{ number_format($defaultRefundAmount, 0, ',', '.') }}</span>
                            </p>
                            @error('refund_amount')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Bukti Transfer -->
                        <div x-data="{ fileName: '', preview: '' }">
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Upload Bukti Transfer <span class="text-red-400">*</span>
                            </label>
                            <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed
                                          border-slate-600 rounded-xl cursor-pointer bg-slate-900
                                          hover:border-yellow-500 hover:bg-slate-900/80 transition-all"
                                   :class="{ 'border-yellow-500': fileName }">
                                <template x-if="!preview">
                                    <div class="text-center">
                                        <svg class="w-10 h-10 mx-auto text-slate-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm text-slate-400">Klik atau drag & drop bukti transfer</p>
                                        <p class="text-xs text-slate-500 mt-1">PNG, JPG maksimal 5MB</p>
                                    </div>
                                </template>
                                <template x-if="preview">
                                    <div class="relative w-full h-full p-2">
                                        <img :src="preview" class="h-full mx-auto object-contain rounded-lg">
                                        <p class="text-xs text-yellow-400 text-center mt-1" x-text="fileName"></p>
                                    </div>
                                </template>
                                <input type="file" name="refund_proof" accept="image/*" class="hidden"
                                       @change="fileName = $event.target.files[0]?.name; const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL($event.target.files[0])">
                            </label>
                            @error('refund_proof')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Catatan (opsional)</label>
                            <textarea name="admin_notes" rows="3"
                                      placeholder="Catatan tambahan mengenai proses refund..."
                                      class="w-full px-4 py-3 bg-slate-900 border border-slate-700 text-white rounded-lg
                                             focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500
                                             placeholder-slate-500 transition resize-none">{{ old('admin_notes') }}</textarea>
                        </div>

                        <button type="button"
                                @click="submitRefund()"
                                class="w-full py-4 bg-gradient-to-r from-yellow-500 to-orange-600
                                       text-white font-bold text-lg rounded-xl
                                       hover:from-yellow-600 hover:to-orange-700
                                       transition-all shadow-2xl hover:shadow-yellow-500/20
                                       flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Konfirmasi Refund Berhasil
                        </button>
                    </div>
                </form>

                <!-- Modal Pilih Metode -->
                <div x-show="showMethodModal"
                     x-cloak
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
                    <div class="bg-slate-800 border-2 border-slate-700 rounded-2xl shadow-2xl w-full max-w-md"
                         @click.outside="showMethodModal = false">
                        <div class="p-5 border-b border-slate-700 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-white">Pilih Metode Transfer</h3>
                            <button @click="showMethodModal = false" class="text-slate-400 hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="p-5 space-y-5">
                            @foreach($refundMethods as $methodKey => $method)
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">{{ $method['label'] }}</p>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach($method['options'] as $subKey => $sub)
                                    <button type="button"
                                            @click="selectMethod('{{ $methodKey }}', '{{ $subKey }}', '{{ $sub['name'] }}')"
                                            class="py-3 px-2 bg-slate-900 border-2 rounded-lg text-center transition text-sm font-semibold
                                                   hover:border-yellow-500 hover:text-yellow-400"
                                            :class="selectedSubMethod === '{{ $subKey }}'
                                                ? 'border-yellow-500 text-yellow-400 bg-yellow-500/10'
                                                : 'border-slate-700 text-slate-300'">
                                        {{ $sub['name'] }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="p-5 border-t border-slate-700">
                            <button @click="showMethodModal = false"
                                    class="w-full py-2.5 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-bold rounded-lg
                                           hover:from-yellow-600 hover:to-orange-700 transition">
                                Konfirmasi Pilihan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sudah diproses --}}
            @elseif($cancelBooking->status === 'approved')
            <div class="bg-slate-800/90 backdrop-blur rounded-xl border-2 border-green-500/30 p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-green-400 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Refund Telah Diproses
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 uppercase mb-1.5">Metode Transfer</p>
                        <p class="font-bold text-white">{{ strtoupper($cancelBooking->refund_sub_method ?? '-') }}</p>
                    </div>
                    <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 uppercase mb-1.5">Nominal Refund</p>
                        <p class="font-bold text-green-400">Rp {{ number_format($cancelBooking->refund_amount ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 uppercase mb-1.5">Tanggal Proses</p>
                        <p class="font-semibold text-white">{{ $cancelBooking->processed_at?->translatedFormat('d F Y, H:i') }}</p>
                    </div>
                    <div class="p-4 bg-slate-900 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 uppercase mb-1.5">Rekening Sumber</p>
                        <p class="font-semibold text-white">{{ $cancelBooking->refund_account_number ?? '-' }}</p>
                    </div>
                </div>
                @if($cancelBooking->refund_proof)
                <div class="mt-4">
                    <p class="text-sm text-slate-400 mb-2">Bukti Transfer:</p>
                    <img src="{{ Storage::url($cancelBooking->refund_proof) }}"
                         alt="Bukti Refund"
                         class="max-w-xs rounded-lg border border-slate-700 cursor-pointer hover:scale-105 transition"
                         onclick="window.open(this.src)">
                </div>
                @endif
            </div>
            @endif

        </div>

        {{-- PERBAIKAN: Hapus sticky top-0 dan self-start agar sidebar kanan
             ikut scroll bersama konten kiri, sama seperti halaman cancel booking admin --}}
        <div class="space-y-6">

            <!-- Ringkasan Refund -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
                <h4 class="font-semibold text-slate-300 mb-4 text-sm uppercase tracking-wide">Ringkasan Refund</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2.5 border-b border-slate-700">
                        <span class="text-sm text-slate-400">Status</span>
                        <span class="text-xs font-bold px-2 py-1 rounded {{ $cancelBooking->status_badge }}">
                            @if($cancelBooking->status === 'admin_approved') Menunggu Refund
                            @elseif($cancelBooking->status === 'approved') Selesai
                            @else -
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-start py-2.5 border-b border-slate-700">
                        <span class="text-sm text-slate-400">User</span>
                        <span class="text-sm text-white font-medium text-right max-w-32">{{ $cancelBooking->user->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2.5 border-b border-slate-700">
                        <span class="text-sm text-slate-400">Bank Tujuan</span>
                        <span class="text-sm text-yellow-400 font-bold">{{ $cancelBooking->bank_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2.5 border-b border-slate-700">
                        <span class="text-sm text-slate-400">No. Rekening</span>
                        <span class="text-sm text-white font-mono">{{ $cancelBooking->account_number }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2.5 border-b border-slate-700">
                        <span class="text-sm text-slate-400">A/N</span>
                        <span class="text-sm text-white">{{ $cancelBooking->account_holder_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2.5">
                        <span class="text-sm text-slate-400">Nominal DP</span>
                        <span class="text-lg font-bold text-yellow-400">
                            Rp {{ number_format($cancelBooking->rent->deposit_paid ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                @if($cancelBooking->status === 'admin_approved')
                <div class="mt-4 p-3 bg-orange-500/10 border border-orange-500/30 rounded-lg">
                    <p class="text-xs text-orange-300 font-medium">
                        ⚡ Transfer ke rekening di atas, lalu upload bukti transfer di form kiri.
                    </p>
                </div>
                @endif
            </div>

            <!-- Alur Status -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
                <h4 class="font-semibold text-slate-300 mb-4 text-sm uppercase tracking-wide">Status Alur</h4>
                <div class="space-y-2">
                    @php
                        $steps = [
                            ['label' => 'User Ajukan Cancel',       'done' => true,                                                              'date' => $cancelBooking->created_at->format('d M Y')],
                            ['label' => 'Admin Setujui',            'done' => in_array($cancelBooking->status, ['admin_approved', 'approved']),  'date' => $cancelBooking->admin_approved_at?->format('d M Y')],
                            ['label' => 'Superadmin Proses Refund', 'done' => $cancelBooking->status === 'approved',                             'date' => $cancelBooking->processed_at?->format('d M Y')],
                            ['label' => 'Dana Kembali ke User',     'done' => $cancelBooking->status === 'approved',                             'date' => null],
                        ];
                    @endphp
                    @foreach($steps as $i => $step)
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0
                                    {{ $step['done'] ? 'bg-green-500/30 border-2 border-green-500' : 'bg-slate-700 border-2 border-slate-600' }}">
                            @if($step['done'])
                            <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                            @else
                            <span class="text-xs text-slate-500">{{ $i+1 }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-xs {{ $step['done'] ? 'text-green-400 font-medium' : 'text-slate-500' }}">
                                {{ $step['label'] }}
                            </p>
                            @if($step['date'])
                            <p class="text-xs text-slate-600">{{ $step['date'] }}</p>
                            @endif
                        </div>
                    </div>
                    @if(!$loop->last)
                    <div class="ml-3 w-px h-2.5 bg-slate-700"></div>
                    @endif
                    @endforeach
                </div>
            </div>

        </div>

    </div>
</div>

<script>
function refundForm() {
    return {
        showMethodModal: false,
        selectedMethod: '{{ old('refund_method', '') }}',
        selectedSubMethod: '{{ old('refund_sub_method', '') }}',
        selectedSubMethodName: '{{ old('refund_sub_method', '') }}',
        selectMethod(method, subMethod, name) {
            this.selectedMethod = method;
            this.selectedSubMethod = subMethod;
            this.selectedSubMethodName = name;
            this.showMethodModal = false;
        }
    }
}
</script>

@endsection