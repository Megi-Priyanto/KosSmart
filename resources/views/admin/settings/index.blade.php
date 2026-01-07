@extends('layouts.admin')

@section('title', 'Pengaturan Admin')
@section('page-title', 'Pengaturan')
@section('page-description', 'Konfigurasi sistem dan preferensi aplikasi KosSmart')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'general' }">

    <!-- Settings Navigation Tabs -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="flex border-b border-slate-700 overflow-x-auto">
            <button @click="activeTab = 'general'" 
                    :class="activeTab === 'general' ? 'border-b-2 border-yellow-400 text-yellow-400' : 'text-gray-400 hover:text-gray-300'"
                    class="px-6 py-4 font-medium whitespace-nowrap transition">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Umum</span>
                </div>
            </button>

            <button @click="activeTab = 'billing'" 
                    :class="activeTab === 'billing' ? 'border-b-2 border-yellow-400 text-yellow-400' : 'text-gray-400 hover:text-gray-300'"
                    class="px-6 py-4 font-medium whitespace-nowrap transition">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Tagihan</span>
                </div>
            </button>

            <button @click="activeTab = 'notification'" 
                    :class="activeTab === 'notification' ? 'border-b-2 border-yellow-400 text-yellow-400' : 'text-gray-400 hover:text-gray-300'"
                    class="px-6 py-4 font-medium whitespace-nowrap transition">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span>Notifikasi</span>
                </div>
            </button>

            <button @click="activeTab = 'maintenance'" 
                    :class="activeTab === 'maintenance' ? 'border-b-2 border-yellow-400 text-yellow-400' : 'text-gray-400 hover:text-gray-300'"
                    class="px-6 py-4 font-medium whitespace-nowrap transition">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    <span>Maintenance</span>
                </div>
            </button>

            <button @click="activeTab = 'security'" 
                    :class="activeTab === 'security' ? 'border-b-2 border-yellow-400 text-yellow-400' : 'text-gray-400 hover:text-gray-300'"
                    class="px-6 py-4 font-medium whitespace-nowrap transition">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span>Keamanan</span>
                </div>
            </button>
        </div>

        <!-- Tab Content -->
        <div class="p-6">

            <!-- GENERAL SETTINGS -->
            <div x-show="activeTab === 'general'" x-transition>
                <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-bold text-white mb-4">Informasi Aplikasi</h3>
                            
                            <!-- Nama Aplikasi -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Nama Aplikasi
                                </label>
                                <input type="text" 
                                       name="app_name" 
                                       value="{{ setting('app_name', 'KosSmart') }}"
                                       class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                            </div>

                            <!-- Email Kontak -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Email Kontak
                                </label>
                                <input type="email" 
                                       name="contact_email" 
                                       value="{{ setting('contact_email', 'admin@kossmart.com') }}"
                                       class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                            </div>

                            <!-- Nomor Telepon -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Nomor Telepon
                                </label>
                                <input type="text" 
                                       name="contact_phone" 
                                       value="{{ setting('contact_phone', '08123456789') }}"
                                       class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                            </div>

                            <!-- Alamat Kost -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Alamat Kost
                                </label>
                                <textarea name="kost_address" 
                                          rows="3"
                                          class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">{{ setting('kost_address', 'Jl. Contoh No. 123, Kota') }}</textarea>
                            </div>

                            <!-- Logo Upload -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Logo Aplikasi
                                </label>
                                <div class="flex items-center space-x-4">
                                    <img src="{{ setting('app_logo') ? asset('storage/images/' . setting('app_logo')) : asset('images/logo.png') }}" 
                                         class="w-16 h-16 rounded-lg object-cover border border-slate-600">
                                    <input type="file" 
                                           name="app_logo"
                                           accept="image/*"
                                           class="block w-full text-sm text-gray-400
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-lg file:border-0
                                                  file:text-sm file:font-medium
                                                  file:bg-yellow-600 file:text-white
                                                  hover:file:bg-yellow-700 cursor-pointer">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Format: PNG, JPG, JPEG. Max: 2MB</p>
                            </div>
                        </div>

                        <!-- Timezone & Currency -->
                        <div class="border-t border-slate-700 pt-6">
                            <h3 class="text-lg font-bold text-white mb-4">Preferensi Regional</h3>
                            
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">
                                        Zona Waktu
                                    </label>
                                    <select name="timezone" 
                                            class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                                        <option value="Asia/Jakarta" {{ setting('timezone', 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>WIB (UTC+7)</option>
                                        <option value="Asia/Makassar" {{ setting('timezone', 'Asia/Jakarta') == 'Asia/Makassar' ? 'selected' : '' }}>WITA (UTC+8)</option>
                                        <option value="Asia/Jayapura" {{ setting('timezone', 'Asia/Jakarta') == 'Asia/Jayapura' ? 'selected' : '' }}>WIT (UTC+9)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">
                                        Mata Uang
                                    </label>
                                    <select name="currency" 
                                            class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                                        <option value="IDR" {{ setting('currency', 'IDR') == 'IDR' ? 'selected' : '' }}>Rupiah (IDR)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-slate-700">
                            <button type="reset"
                                    class="px-5 py-2.5 border border-slate-600 text-gray-300 font-medium rounded-lg hover:bg-slate-700 transition">
                                Reset
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- BILLING SETTINGS -->
            <div x-show="activeTab === 'billing'" x-transition>
                <form action="{{ route('admin.settings.billing.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-bold text-white mb-4">Konfigurasi Tagihan</h3>
                            
                            <!-- Tanggal Jatuh Tempo Default -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Tanggal Jatuh Tempo (setiap bulan)
                                </label>
                                <select name="default_due_date" 
                                        class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                                    @for($i = 1; $i <= 28; $i++)
                                        <option value="{{ $i }}" {{ $i == setting('default_due_date', 5) ? 'selected' : '' }}>
                                            Tanggal {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Tagihan akan jatuh tempo setiap tanggal yang dipilih</p>
                            </div>

                            <!-- Denda Keterlambatan -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Denda Keterlambatan
                                </label>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-400 mb-1">Tipe Denda</label>
                                        <select name="late_fee_type" 
                                                class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                                            <option value="fixed" {{ setting('late_fee_type', 'fixed') == 'fixed' ? 'selected' : '' }}>Nominal Tetap</option>
                                            <option value="percentage" {{ setting('late_fee_type', 'fixed') == 'percentage' ? 'selected' : '' }}>Persentase</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-400 mb-1">Jumlah Denda</label>
                                        <input type="number" 
                                               name="late_fee_amount" 
                                               value="{{ setting('late_fee_amount', 50000) }}"
                                               class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                                    </div>
                                </div>
                            </div>

                            <!-- Grace Period -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Masa Tenggang (hari)
                                </label>
                                <input type="number" 
                                       name="grace_period" 
                                       value="{{ setting('grace_period', 3) }}"
                                       min="0"
                                       max="30"
                                       class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                                <p class="text-xs text-gray-500 mt-1">Jumlah hari setelah jatuh tempo sebelum denda dikenakan</p>
                            </div>

                            <!-- Auto Generate -->
                            <div class="mb-4">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox" 
                                           name="auto_generate_billing" 
                                           {{ setting('auto_generate_billing', 1) == 1 ? 'checked' : '' }}
                                           class="w-5 h-5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500 focus:ring-offset-slate-900">
                                    <div>
                                        <span class="text-sm font-medium text-gray-300">Generate Tagihan Otomatis</span>
                                        <p class="text-xs text-gray-500">Sistem akan membuat tagihan bulanan secara otomatis</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="border-t border-slate-700 pt-6">
                            <h3 class="text-lg font-bold text-white mb-4">Metode Pembayaran</h3>
                            
                            @php
                                $paymentMethods = json_decode(setting('payment_methods', '["cash","transfer"]'), true) ?? [];
                            @endphp

                            <div class="space-y-3">
                                <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600 cursor-pointer hover:border-yellow-500 transition">
                                    <input type="checkbox" name="payment_methods[]" value="cash" 
                                           {{ in_array('cash', $paymentMethods) ? 'checked' : '' }}
                                           class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500 focus:ring-offset-slate-900">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-white">Tunai</p>
                                        <p class="text-xs text-gray-400">Pembayaran langsung ke admin</p>
                                    </div>
                                </label>

                                <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600 cursor-pointer hover:border-yellow-500 transition">
                                    <input type="checkbox" name="payment_methods[]" value="transfer" 
                                           {{ in_array('transfer', $paymentMethods) ? 'checked' : '' }}
                                           class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500 focus:ring-offset-slate-900">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-white">Transfer Bank</p>
                                        <p class="text-xs text-gray-400">Pembayaran via transfer rekening</p>
                                        <div class="mt-2 space-y-2">
                                            <input type="text" name="bank_name" placeholder="Nama Bank" 
                                                   value="{{ setting('bank_name', 'Bank BCA') }}"
                                                   class="w-full px-3 py-2 text-sm bg-slate-800 border border-slate-600 rounded text-white">
                                            <input type="text" name="account_number" placeholder="No. Rekening" 
                                                   value="{{ setting('account_number', '1234567890') }}"
                                                   class="w-full px-3 py-2 text-sm bg-slate-800 border border-slate-600 rounded text-white">
                                            <input type="text" name="account_holder" placeholder="Atas Nama" 
                                                   value="{{ setting('account_holder', 'KosSmart') }}"
                                                   class="w-full px-3 py-2 text-sm bg-slate-800 border border-slate-600 rounded text-white">
                                        </div>
                                    </div>
                                </label>

                                <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600 cursor-pointer hover:border-yellow-500 transition">
                                    <input type="checkbox" name="payment_methods[]" value="ewallet"
                                           {{ in_array('ewallet', $paymentMethods) ? 'checked' : '' }}
                                           class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500 focus:ring-offset-slate-900">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-white">E-Wallet</p>
                                        <p class="text-xs text-gray-400">GoPay, OVO, Dana, dll</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-slate-700">
                            <button type="reset"
                                    class="px-5 py-2.5 border border-slate-600 text-gray-300 font-medium rounded-lg hover:bg-slate-700 transition">
                                Reset
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- NOTIFICATION SETTINGS -->
            <div x-show="activeTab === 'notification'" x-transition>
                <form action="{{ route('admin.settings.notification.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-bold text-white mb-4">Notifikasi Email</h3>
                            
                            <div class="space-y-4">
                                <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600">
                                    <input type="checkbox" name="notify_new_booking" 
                                           {{ setting('notify_new_booking', 1) == 1 ? 'checked' : '' }}
                                           class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500">
                                    <div>
                                        <p class="text-sm font-medium text-white">Booking Baru</p>
                                        <p class="text-xs text-gray-400">Kirim notifikasi saat ada booking baru</p>
                                    </div>
                                </label>

                                <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600">
                                    <input type="checkbox" name="notify_payment_received" 
                                           {{ setting('notify_payment_received', 1) == 1 ? 'checked' : '' }}
                                           class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500">
                                    <div>
                                        <p class="text-sm font-medium text-white">Pembayaran Diterima</p>
                                        <p class="text-xs text-gray-400">Kirim notifikasi saat pembayaran diterima</p>
                                    </div>
                                </label>

                                <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600">
                                    <input type="checkbox" name="notify_overdue" 
                                           {{ setting('notify_overdue', 1) == 1 ? 'checked' : '' }}
                                           class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500">
                                    <div>
                                        <p class="text-sm font-medium text-white">Tagihan Overdue</p>
                                        <p class="text-xs text-gray-400">Kirim notifikasi untuk tagihan yang terlambat</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Reminder Settings -->
                        <div class="border-t border-slate-700 pt-6">
                            <h3 class="text-lg font-bold text-white mb-4">Pengingat Otomatis</h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Kirim Pengingat Pembayaran
                                </label>
                                <select name="reminder_days" 
                                        class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                                    <option value="0" {{ setting('reminder_days', 3) == 0 ? 'selected' : '' }}>Tidak Ada Pengingat</option>
                                    <option value="3" {{ setting('reminder_days', 3) == 3 ? 'selected' : '' }}>3 hari sebelum jatuh tempo</option>
                                    <option value="5" {{ setting('reminder_days', 3) == 5 ? 'selected' : '' }}>5 hari sebelum jatuh tempo</option>
                                    <option value="7" {{ setting('reminder_days', 3) == 7 ? 'selected' : '' }}>7 hari sebelum jatuh tempo</option>
                                </select>
                            </div>

                            <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600">
                                <input type="checkbox" name="send_reminder_email" 
                                       {{ setting('send_reminder_email', 1) == 1 ? 'checked' : '' }}
                                       class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500">
                                <div>
                                    <p class="text-sm font-medium text-white">Kirim via Email</p>
                                    <p class="text-xs text-gray-400">Pengingat akan dikirim ke email penghuni</p>
                                </div>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-slate-700">
                            <button type="reset"
                                    class="px-5 py-2.5 border border-slate-600 text-gray-300 font-medium rounded-lg hover:bg-slate-700 transition">
                                Reset
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- MAINTENANCE SETTINGS -->
            <div x-show="activeTab === 'maintenance'" x-transition>
                <div class="space-y-6">
                    
                    <!-- Database Backup -->
                    <div>
                        <h3 class="text-lg font-bold text-white mb-4">Backup Database</h3>
                        
                        <div class="p-4 bg-slate-900 rounded-lg border border-slate-600">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <p class="text-sm font-medium text-white">Backup Terakhir</p>
                                    <p class="text-xs text-gray-400">25 Desember 2024 - 10:30 WIB</p>
                                </div>
                                <span class="px-3 py-1 bg-green-500/20 text-green-400 text-xs font-medium rounded-full">
                                    Berhasil
                                </span>
                            </div>
                            
                            <div class="flex space-x-3">
                                <button onclick="backupDatabase()"
                                        class="px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition">
                                    Backup Sekarang
                                </button>
                                @if(setting('last_backup_file'))
                                <a href="{{ route('admin.settings.backup.download', setting('last_backup_file')) }}"
                                   class="px-4 py-2 border border-slate-600 text-gray-300 text-sm font-medium rounded-lg hover:bg-slate-700 transition">
                                    Download Backup
                                </a>
                                @endif
                            </div>
                        </div>

                        <!-- Auto Backup -->
                        <div class="mt-4">
                            <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600">
                                <input type="checkbox" checked
                                       class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500">
                                <div>
                                    <p class="text-sm font-medium text-white">Backup Otomatis</p>
                                    <p class="text-xs text-gray-400">Backup database setiap minggu secara otomatis</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Clear Cache -->
                    <div class="border-t border-slate-700 pt-6">
                        <h3 class="text-lg font-bold text-white mb-4">Pembersihan Cache</h3>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <button onclick="clearCache('application')"
                                    class="p-4 bg-slate-900 border border-slate-600 rounded-lg text-left hover:border-yellow-500 transition group">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-white group-hover:text-yellow-400">Application Cache</p>
                                        <p class="text-xs text-gray-400 mt-1">Hapus cache aplikasi Laravel</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                            </button>

                            <button onclick="clearCache('route')"
                                    class="p-4 bg-slate-900 border border-slate-600 rounded-lg text-left hover:border-yellow-500 transition group">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-white group-hover:text-yellow-400">Route Cache</p>
                                        <p class="text-xs text-gray-400 mt-1">Hapus cache routing</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                            </button>

                            <button onclick="clearCache('config')"
                                    class="p-4 bg-slate-900 border border-slate-600 rounded-lg text-left hover:border-yellow-500 transition group">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-white group-hover:text-yellow-400">Config Cache</p>
                                        <p class="text-xs text-gray-400 mt-1">Hapus cache konfigurasi</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                            </button>

                            <button onclick="clearCache('view')"
                                    class="p-4 bg-slate-900 border border-slate-600 rounded-lg text-left hover:border-yellow-500 transition group">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-white group-hover:text-yellow-400">View Cache</p>
                                        <p class="text-xs text-gray-400 mt-1">Hapus cache tampilan Blade</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </div>
                            </button>
                        </div>

                        <div class="mt-4">
                            <button onclick="clearCache('all')"
                                    class="w-full p-4 bg-red-500/10 border border-red-500/30 text-red-400 rounded-lg hover:bg-red-500/20 transition font-medium">
                                Hapus Semua Cache
                            </button>
                        </div>
                    </div>

                    <!-- System Info -->
                    <div class="border-t border-slate-700 pt-6">
                        <h3 class="text-lg font-bold text-white mb-4">Informasi Sistem</h3>
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-900 rounded-lg border border-slate-600">
                                <p class="text-xs text-gray-400 mb-1">Versi PHP</p>
                                <p class="text-sm font-semibold text-white">{{ PHP_VERSION }}</p>
                            </div>
                            <div class="p-4 bg-slate-900 rounded-lg border border-slate-600">
                                <p class="text-xs text-gray-400 mb-1">Versi Laravel</p>
                                <p class="text-sm font-semibold text-white">{{ app()->version() }}</p>
                            </div>
                            <div class="p-4 bg-slate-900 rounded-lg border border-slate-600">
                                <p class="text-xs text-gray-400 mb-1">Environment</p>
                                <p class="text-sm font-semibold text-white">{{ app()->environment() }}</p>
                            </div>
                            <div class="p-4 bg-slate-900 rounded-lg border border-slate-600">
                                <p class="text-xs text-gray-400 mb-1">Database</p>
                                <p class="text-sm font-semibold text-white">MySQL</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECURITY SETTINGS -->
            <div x-show="activeTab === 'security'" x-transition>
                <form action="{{ route('admin.settings.security.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Password Policy -->
                        <div>
                            <h3 class="text-lg font-bold text-white mb-4">Kebijakan Password</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">
                                        Minimal Panjang Password
                                    </label>
                                    <input type="number" name="min_password_length" value="8" min="6" max="20"
                                           class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                                </div>

                                <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600">
                                    <input type="checkbox" name="require_uppercase" checked
                                           class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500">
                                    <div>
                                        <p class="text-sm font-medium text-white">Wajib Huruf Besar</p>
                                        <p class="text-xs text-gray-400">Password harus mengandung minimal 1 huruf kapital</p>
                                    </div>
                                </label>
 
                                <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600">
                                    <input type="checkbox" name="require_number" checked
                                           class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500">
                                    <div>
                                        <p class="text-sm font-medium text-white">Wajib Angka</p>
                                        <p class="text-xs text-gray-400">Password harus mengandung minimal 1 angka</p>
                                    </div>
                                </label>

                                <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600">
                                    <input type="checkbox" name="require_special"
                                           class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500">
                                    <div>
                                        <p class="text-sm font-medium text-white">Wajib Karakter Khusus</p>
                                        <p class="text-xs text-gray-400">Password harus mengandung karakter khusus (!@#$%^&*)</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Session Settings -->
                        <div class="border-t border-slate-700 pt-6">
                            <h3 class="text-lg font-bold text-white mb-4">Pengaturan Sesi</h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Durasi Sesi Login (menit)
                                </label>
                                <input type="number" name="session_lifetime" value="120" min="30" max="1440"
                                       class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                                <p class="text-xs text-gray-400 mt-1">User akan logout otomatis setelah durasi ini</p>
                            </div>

                            <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600">
                                <input type="checkbox" name="remember_me_enabled" checked
                                       class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500">
                                <div>
                                    <p class="text-sm font-medium text-white">Aktifkan "Ingat Saya"</p>
                                    <p class="text-xs text-gray-400">User dapat memilih untuk tetap login</p>
                                </div>
                            </label>
                        </div>

                        <!-- Login Attempts -->
                        <div class="border-t border-slate-700 pt-6">
                            <h3 class="text-lg font-bold text-white mb-4">Keamanan Login</h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Maksimal Percobaan Login
                                </label>
                                <input type="number" name="max_login_attempts" value="5" min="3" max="10"
                                       class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Durasi Lockout (menit)
                                </label>
                                <input type="number" name="lockout_duration" value="15" min="5" max="60"
                                       class="w-full px-4 py-2.5 bg-slate-900 border border-slate-600 rounded-lg text-white focus:border-yellow-500 focus:ring focus:ring-yellow-500/20 transition">
                                <p class="text-xs text-gray-400 mt-1">Akun akan dikunci sementara setelah gagal login berulang</p>
                            </div>

                            <label class="flex items-start space-x-3 p-4 bg-slate-900 rounded-lg border border-slate-600">
                                <input type="checkbox" name="require_email_verification" checked
                                       class="w-5 h-5 mt-0.5 rounded border-slate-600 text-yellow-600 focus:ring-yellow-500">
                                <div>
                                    <p class="text-sm font-medium text-white">Wajib Verifikasi Email</p>
                                    <p class="text-xs text-gray-400">User baru harus verifikasi email sebelum login</p>
                                </div>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-slate-700">
                            <button type="reset"
                                    class="px-5 py-2.5 border border-slate-600 text-gray-300 font-medium rounded-lg hover:bg-slate-700 transition">
                                Reset
                            </button>
                            <button type="submit"
                                    class="px-5 py-2.5 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Backup Database Function
function backupDatabase() {
    if (!confirm('Backup database sekarang? Proses ini mungkin memakan waktu beberapa menit.')) {
        return;
    }

    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';

    fetch('{{ route("admin.settings.backup") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        button.disabled = false;
        button.innerHTML = originalText;
        
        if (data.success) {
            alert('' + data.message);
            location.reload();
        } else {
            alert('' + data.message);
        }
    })
    .catch(error => {
        button.disabled = false;
        button.innerHTML = originalText;
        alert('Terjadi kesalahan: ' + error.message);
    });
}

// Clear Cache Function
function clearCache(type) {
    const messages = {
        'application': 'Application Cache',
        'route': 'Route Cache',
        'config': 'Config Cache',
        'view': 'View Cache',
        'all': 'SEMUA Cache'
    };

    if (!confirm(`Hapus ${messages[type]}?`)) {
        return;
    }

    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.disabled = true;
    
    if (type === 'all') {
        button.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
    }

    fetch('{{ route("admin.settings.cache.clear") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        button.disabled = false;
        button.innerHTML = originalHTML;
        
        if (data.success) {
            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'fixed top-4 right-4 z-50 px-6 py-4 bg-slate-800 border border-green-500/40 rounded-xl flex items-center gap-3 shadow-lg';
            alertDiv.innerHTML = `
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-300 font-medium text-sm">${data.message}</p>
            `;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        } else {
            alert('' + data.message);
        }
    })
    .catch(error => {
        button.disabled = false;
        button.innerHTML = originalHTML;
        alert('Terjadi kesalahan: ' + error.message);
    });
}

// Auto-hide success messages
document.addEventListener('DOMContentLoaded', function() {
    const successAlert = document.querySelector('.bg-slate-800.border-green-500\\/40');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.transition = 'opacity 0.5s';
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 500);
        }, 5000);
    }
});
</script>
@endpush