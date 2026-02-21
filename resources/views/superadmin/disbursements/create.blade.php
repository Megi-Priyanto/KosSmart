@extends('layouts.superadmin')

@section('title', 'Cairkan Dana - ' . $tempatKos->nama_kos)
@section('page-title', 'Cairkan Dana')
@section('page-description', $tempatKos->nama_kos)

@section('content')
<div class="space-y-6">

    {{-- Alerts --}}
    @if(session('error'))
        <div class="bg-red-500/15 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-500/15 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-medium">Terdapat kesalahan:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-sm ml-7">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Banner dari notifikasi --}}
    @if(isset($preSelectedPaymentId) && $preSelectedPaymentId)
    <div class="bg-yellow-500/10 border border-yellow-500/40 rounded-xl px-5 py-3 flex items-center gap-3">
        <svg class="w-5 h-5 text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm text-yellow-300">Payment dari notifikasi telah <strong>otomatis dipilih</strong>. Anda dapat menambah payment lain sebelum memproses pencairan.</p>
    </div>
    @endif

    <form method="POST" action="{{ route('superadmin.disbursements.store') }}" enctype="multipart/form-data" id="disbursementForm">
        @csrf
        <input type="hidden" name="tempat_kos_id" value="{{ $tempatKos->id }}">

        <div class="space-y-6">

            {{-- ============================================================
                 BARIS 1: Stat Bar
            ============================================================ --}}
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-slate-400">Total Dana Holding</p>
                        <p class="text-base font-bold text-amber-400 truncate">Rp {{ number_format($totalHolding, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Jumlah Payment</p>
                        <p class="text-base font-bold text-blue-400">{{ $holdingPayments->count() }} payment</p>
                    </div>
                </div>
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs text-slate-400">Tempat Kos</p>
                        <p class="text-base font-bold text-purple-400 truncate">{{ $tempatKos->nama_kos }}</p>
                    </div>
                </div>
            </div>

            {{-- ============================================================
                 BARIS 2: Tabel Payment (kiri) + Potongan Fee (kanan)
                 Keduanya sama tinggi dengan items-stretch
            ============================================================ --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

                {{-- KIRI: Tabel Payment Holding --}}
                <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden flex flex-col">
                    <div class="px-5 py-4 border-b border-slate-700 flex items-center justify-between flex-shrink-0">
                        <h2 class="text-sm font-semibold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Daftar Payment Holding
                        </h2>
                        <label class="flex items-center gap-2 text-xs text-slate-400 cursor-pointer hover:text-white transition-colors">
                            <input type="checkbox" id="selectAll" class="rounded border-slate-600 bg-slate-700 text-yellow-500 focus:ring-yellow-500 w-3.5 h-3.5">
                            Pilih Semua
                        </label>
                    </div>

                    @if($holdingPayments->count() > 0)
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full">
                            <thead class="bg-slate-900/50">
                                <tr>
                                    <th class="px-4 py-3 w-8"></th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wide">Penghuni</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wide">Kamar</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wide">Tipe</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wide">Jumlah</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wide">Konfirmasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/60">
                                @foreach($holdingPayments as $payment)
                                @php $isHighlighted = isset($preSelectedPaymentId) && $preSelectedPaymentId && $payment->id === (int)$preSelectedPaymentId; @endphp
                                <tr class="transition payment-row cursor-pointer {{ $isHighlighted ? 'bg-yellow-500/10 border-l-2 border-l-yellow-500 hover:bg-yellow-500/15' : 'hover:bg-slate-700/40' }}"
                                    data-amount="{{ $payment->amount }}" onclick="togglePayment(this)">
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" name="payment_ids[]" value="{{ $payment->id }}"
                                               class="payment-checkbox rounded border-slate-600 bg-slate-700 text-yellow-500 focus:ring-yellow-500 w-4 h-4"
                                               {{ $isHighlighted ? 'checked' : '' }}
                                               onclick="event.stopPropagation(); updateTotal();">
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-medium text-white leading-tight">{{ $payment->user->name ?? '-' }}</p>
                                        @if($isHighlighted)
                                            <p class="text-xs text-yellow-400 flex items-center gap-1 mt-0.5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>
                                                Dari notifikasi
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm text-slate-300">{{ $payment->billing->room->room_number ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $isHighlighted ? 'bg-yellow-500/20 text-yellow-400' : 'bg-slate-700 text-slate-300' }}">
                                            {{ ucfirst($payment->billing->tipe ?? '-') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-semibold text-emerald-400">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-xs text-slate-400 leading-tight">
                                        {{ $payment->verified_at?->format('d M Y') ?? '-' }}<br>
                                        <span class="text-slate-500">{{ $payment->verified_at?->format('H:i') ?? '' }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{-- Footer total --}}
                    <div class="border-t border-slate-700 px-5 py-3 bg-slate-900/40 flex-shrink-0 flex items-center justify-between">
                        <span class="text-xs text-slate-400">
                            <span id="selectedCount" class="text-white font-semibold">0</span> payment dipilih
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-400">Total:</span>
                            <span class="text-sm font-bold text-amber-400" id="selectedTotal">Rp 0</span>
                        </div>
                    </div>

                    @else
                    <div class="flex-1 flex flex-col items-center justify-center py-16">
                        <div class="w-14 h-14 bg-slate-700 rounded-full flex items-center justify-center mb-3">
                            <svg class="w-7 h-7 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-slate-400 font-medium text-sm">Tidak ada dana holding</p>
                        <p class="text-slate-500 text-xs mt-1">Semua payment sudah dicairkan.</p>
                    </div>
                    @endif
                </div>

                {{-- KANAN: Potongan Platform --}}
                <div class="bg-slate-800 border border-amber-500/30 rounded-xl overflow-hidden flex flex-col">
                    <div class="px-5 py-4 border-b border-slate-700 flex items-center gap-2.5 flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-white">Potongan Platform</p>
                            <p class="text-xs text-slate-400">Fee yang diambil dari total pembayaran</p>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col gap-5 flex-1">

                        {{-- Input fee besar --}}
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-2 uppercase tracking-wide">Fee Platform (%)</label>
                            <div class="relative">
                                <input type="number" name="fee_percent" id="feePercent"
                                       value="{{ old('fee_percent', $defaultFeePercent) }}"
                                       min="0" max="100" step="0.5"
                                       class="w-full bg-slate-900 text-white text-4xl font-bold rounded-xl px-6 py-5 pr-16 border border-slate-700 focus:outline-none focus:border-yellow-500 transition-colors text-center"
                                       oninput="updateFeeCalc()" required>
                                <span class="absolute right-6 top-1/2 -translate-y-1/2 text-amber-400 font-bold text-3xl">%</span>
                            </div>
                        </div>

                        {{-- Kalkulasi --}}
                        <div class="bg-slate-900/60 rounded-xl p-5 border border-slate-700/50 space-y-4 flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-400">Total Bruto</span>
                                <span class="text-sm font-semibold text-white" id="previewGross">Rp 0</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-400">Fee (<span id="previewFeePercent">{{ $defaultFeePercent }}</span>%)</span>
                                <span class="text-sm font-semibold text-red-400" id="previewFee">- Rp 0</span>
                            </div>
                            <div>
                                <div class="flex rounded-full overflow-hidden h-3">
                                    <div class="bg-emerald-500 transition-all duration-300" id="barAdmin" style="width:{{ 100 - $defaultFeePercent }}%"></div>
                                    <div class="bg-red-500/70 transition-all duration-300" id="barFee" style="width:{{ $defaultFeePercent }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs mt-1.5">
                                    <span class="text-emerald-400">Admin {{ 100 - $defaultFeePercent }}%</span>
                                    <span class="text-red-400">Fee {{ $defaultFeePercent }}%</span>
                                </div>
                            </div>
                            <div class="border-t border-slate-700 pt-4 flex items-center justify-between">
                                <span class="text-base font-semibold text-slate-200">Admin Terima</span>
                                <span class="text-2xl font-bold text-emerald-400" id="previewAdmin">Rp 0</span>
                            </div>
                        </div>

                        {{-- Ringkasan pilihan --}}
                        <div class="bg-slate-900/40 rounded-xl p-4 border border-slate-700/50 space-y-2">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Ringkasan Pilihan</p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-400">Payment dipilih</span>
                                <span class="text-white font-medium"><span id="summaryCount">0</span> payment</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-400">Fee Platform</span>
                                <span class="text-red-400" id="summaryFee">- Rp 0</span>
                            </div>
                            <div class="h-px bg-slate-700"></div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-slate-200">Diterima Admin</span>
                                <span class="text-lg font-bold text-emerald-400" id="summaryAdmin">Rp 0</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ============================================================
                 BARIS 3: Detail Transfer — FULL WIDTH
            ============================================================ --}}
            <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-700 flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    <p class="text-sm font-semibold text-white">Detail Transfer</p>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Baris 1: Kiri (Admin + Metode) | Kanan (Rekening + Bukti) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Kolom Kiri --}}
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wide">Admin Penerima</label>
                                <select name="admin_id" class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500 transition-colors" required>
                                    <option value="">-- Pilih Admin --</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wide">Metode Transfer</label>
                                <input type="text" name="transfer_method" value="{{ old('transfer_method') }}"
                                       placeholder="BCA, Mandiri, GoPay, DANA..."
                                       class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500 transition-colors" required>
                            </div>
                        </div>

                        {{-- Kolom Kanan --}}
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wide">No. Rekening / Akun</label>
                                <input type="text" name="transfer_account" value="{{ old('transfer_account') }}"
                                       placeholder="1234567890 a/n Nama Admin"
                                       class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500 transition-colors" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wide">Bukti Transfer</label>
                                <input type="file" name="transfer_proof" accept="image/jpeg,image/png,image/jpg"
                                       class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-slate-400 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-slate-500/20 file:text-white hover:file:bg-slate-500/30 transition-colors" required>
                                <p class="text-xs text-slate-500 mt-1">JPG, PNG maksimal 5MB</p>
                            </div>
                        </div>
                    </div>

                    {{-- Baris 2: Catatan full width --}}
                    <div class="border-t border-slate-700 pt-5">
                        <label class="block text-xs font-medium text-slate-400 mb-1.5 uppercase tracking-wide">Catatan (opsional)</label>
                        <textarea name="description" rows="2" placeholder="Catatan pencairan untuk admin..."
                                  class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:border-yellow-500 resize-none transition-colors">{{ old('description') }}</textarea>
                    </div>

                    {{-- Tombol full width --}}
                    <div>
                        <button type="button" onclick="openDisbursementModal()"
                                class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold text-sm px-5 py-3 rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all shadow-lg hover:shadow-yellow-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Proses Pencairan Dana
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>

{{-- ============================================================
     MODAL KONFIRMASI PENCAIRAN DANA
============================================================ --}}
<div id="disbursementModal"
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
     style="display:none;"
     onclick="if(event.target===this) closeDisbursementModal()">

    <div class="bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md border border-slate-700 overflow-hidden
                transform transition-all duration-200"
         id="disbursementModalBox">

        {{-- Header --}}
        <div class="px-6 pt-6 pb-4 text-center">
            {{-- Icon --}}
            <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-1">Konfirmasi Pencairan Dana</h3>
            <p class="text-sm text-slate-400">Pastikan semua informasi sudah benar sebelum melanjutkan</p>
        </div>

        {{-- Rincian --}}
        <div class="mx-6 mb-5 bg-slate-900/60 rounded-xl border border-slate-700/60 overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-700/60">
                <span class="text-xs text-slate-400 uppercase tracking-wide">Payment Dipilih</span>
                <span class="text-sm font-semibold text-white" id="modal_count">-</span>
            </div>
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-700/60">
                <span class="text-xs text-slate-400 uppercase tracking-wide">Total Bruto</span>
                <span class="text-sm font-semibold text-white" id="modal_gross">-</span>
            </div>
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-700/60">
                <span class="text-xs text-slate-400 uppercase tracking-wide">
                    Fee Platform (<span id="modal_feepct">-</span>%)
                </span>
                <span class="text-sm font-semibold text-red-400" id="modal_fee">-</span>
            </div>
            <div class="flex items-center justify-between px-4 py-4 bg-emerald-500/5">
                <span class="text-sm font-bold text-slate-200">Diterima Admin</span>
                <span class="text-xl font-bold text-emerald-400" id="modal_admin">-</span>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="px-6 pb-6 flex gap-3">
            <button type="button" onclick="closeDisbursementModal()"
                    class="flex-1 px-4 py-3 bg-slate-700 hover:bg-slate-600 text-slate-100 rounded-xl font-semibold transition-colors text-sm">
                Batal
            </button>
            <button type="button" onclick="submitDisbursement()"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white rounded-xl font-semibold transition-all shadow-lg text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>
                Ya, Cairkan Sekarang
            </button>
        </div>
    </div>
</div>

{{-- Modal: Tidak ada payment dipilih --}}
<div id="emptyModal"
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
     style="display:none;"
     onclick="if(event.target===this) closeEmptyModal()">

    <div class="bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm border border-slate-700 overflow-hidden">
        <div class="px-6 py-6 text-center">
            <div class="w-14 h-14 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-white mb-1">Belum Ada Payment Dipilih</h3>
            <p class="text-sm text-slate-400 mb-5">Pilih minimal satu payment dari daftar sebelum memproses pencairan.</p>
            <button onclick="closeEmptyModal()"
                    class="w-full px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-slate-100 rounded-xl font-semibold transition-colors text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        updateTotal();
        const hl = document.querySelector('.border-l-yellow-500');
        if (hl) hl.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

    document.getElementById('selectAll').addEventListener('change', function () {
        document.querySelectorAll('.payment-checkbox').forEach(cb => cb.checked = this.checked);
        updateTotal();
    });

    function togglePayment(row) {
        const cb = row.querySelector('.payment-checkbox');
        cb.checked = !cb.checked;
        updateTotal();
    }

    function formatRp(num) { return 'Rp ' + Math.round(num).toLocaleString('id-ID'); }

    function updateTotal() {
        let gross = 0, count = 0;
        document.querySelectorAll('.payment-checkbox:checked').forEach(cb => {
            gross += parseFloat(cb.closest('tr').dataset.amount);
            count++;
        });
        document.getElementById('selectedTotal').textContent = formatRp(gross);
        document.getElementById('selectedCount').textContent = count;
        document.getElementById('summaryCount').textContent  = count;
        window._currentGross = gross;
        updateFeeCalc();
    }

    function updateFeeCalc() {
        const gross    = window._currentGross || 0;
        const feePct   = parseFloat(document.getElementById('feePercent').value) || 0;
        const feeAmt   = Math.round(gross * feePct / 100);
        const adminAmt = gross - feeAmt;
        const adminPct = Math.max(100 - feePct, 0);

        document.getElementById('previewGross').textContent      = formatRp(gross);
        document.getElementById('previewFee').textContent        = '- ' + formatRp(feeAmt);
        document.getElementById('previewAdmin').textContent      = formatRp(adminAmt);
        document.getElementById('previewFeePercent').textContent = feePct;
        document.getElementById('summaryFee').textContent        = '- ' + formatRp(feeAmt);
        document.getElementById('summaryAdmin').textContent      = formatRp(adminAmt);
        document.getElementById('barAdmin').style.width          = adminPct + '%';
        document.getElementById('barFee').style.width            = Math.min(feePct, 100) + '%';
    }

    // ===== MODAL =====
    function openDisbursementModal() {
        const count = document.querySelectorAll('.payment-checkbox:checked').length;

        // Tidak ada payment dipilih → modal warning
        if (count === 0) {
            document.getElementById('emptyModal').style.display = 'flex';
            return;
        }

        const gross    = window._currentGross || 0;
        const feePct   = parseFloat(document.getElementById('feePercent').value) || 0;
        const feeAmt   = Math.round(gross * feePct / 100);
        const adminAmt = gross - feeAmt;

        // Isi data ke modal
        document.getElementById('modal_count').textContent  = count + ' payment';
        document.getElementById('modal_gross').textContent  = formatRp(gross);
        document.getElementById('modal_feepct').textContent = feePct;
        document.getElementById('modal_fee').textContent    = '- ' + formatRp(feeAmt);
        document.getElementById('modal_admin').textContent  = formatRp(adminAmt);

        // Tampilkan modal
        document.getElementById('disbursementModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDisbursementModal() {
        document.getElementById('disbursementModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function closeEmptyModal() {
        document.getElementById('emptyModal').style.display = 'none';
    }

    function submitDisbursement() {
        // Submit form setelah user konfirmasi
        document.getElementById('disbursementForm').submit();
    }

    // Tutup modal dengan Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDisbursementModal();
            closeEmptyModal();
        }
    });

    window._currentGross = 0;
    updateFeeCalc();
</script>
@endpush
@endsection