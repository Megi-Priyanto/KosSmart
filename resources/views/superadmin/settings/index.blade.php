@extends('layouts.superadmin')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')
@section('page-description', 'Konfigurasi dan maintenance sistem KosSmart')

@section('content')
<div class="space-y-6">

    <!-- System Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                    </svg>
                </div>
                <span class="text-xs bg-white/20 px-2 py-1 rounded">System</span>
            </div>
            <div class="text-sm text-blue-100">PHP Version</div>
            <div class="text-xl font-bold">{{ $systemInfo['php_version'] }}</div>
        </div>

        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                    </svg>
                </div>
                <span class="text-xs bg-white/20 px-2 py-1 rounded">Database</span>
            </div>
            <div class="text-sm text-green-100">Total Records</div>
            <div class="text-xl font-bold">{{ number_format(array_sum(array_values($dbStats))) }}</div>
        </div>

        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <span class="text-xs bg-white/20 px-2 py-1 rounded">Storage</span>
            </div>
            <div class="text-sm text-purple-100">Free Space</div>
            <div class="text-xl font-bold">{{ number_format($storageInfo['free_space'] / 1024 / 1024 / 1024, 1) }} GB</div>
        </div>

        <div class="bg-gradient-to-br from-orange-600 to-orange-700 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between mb-3">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-xs bg-white/20 px-2 py-1 rounded">Cache</span>
            </div>
            <div class="text-sm text-orange-100">Driver</div>
            <div class="text-xl font-bold uppercase">{{ $cacheInfo['driver'] }}</div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-slate-800 rounded-xl border border-slate-700" x-data="{ activeTab: 'system' }">
        <!-- Tab Headers -->
        <div class="border-b border-slate-700">
            <nav class="flex space-x-2 p-2">
                <button @click="activeTab = 'system'" 
                        :class="activeTab === 'system' ? 'bg-slate-700 text-yellow-400' : 'text-slate-400 hover:text-slate-200'"
                        class="px-6 py-3 rounded-lg font-medium transition">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                    Informasi Sistem
                </button>
                
                <button @click="activeTab = 'database'" 
                        :class="activeTab === 'database' ? 'bg-slate-700 text-yellow-400' : 'text-slate-400 hover:text-slate-200'"
                        class="px-6 py-3 rounded-lg font-medium transition">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                    </svg>
                    Database
                </button>
                
                <button @click="activeTab = 'backup'" 
                        :class="activeTab === 'backup' ? 'bg-slate-700 text-yellow-400' : 'text-slate-400 hover:text-slate-200'"
                        class="px-6 py-3 rounded-lg font-medium transition">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Backup & Restore
                </button>
                
                <button @click="activeTab = 'maintenance'" 
                        :class="activeTab === 'maintenance' ? 'bg-slate-700 text-yellow-400' : 'text-slate-400 hover:text-slate-200'"
                        class="px-6 py-3 rounded-lg font-medium transition">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                    Maintenance
                </button>
            </nav>
        </div>

        <!-- Tab Contents -->
        <div class="p-6">
            
            <!-- System Info Tab -->
            <div x-show="activeTab === 'system'" class="space-y-6">
                <h3 class="text-xl font-semibold text-slate-100 mb-4">Informasi Sistem</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="text-sm text-slate-400 mb-2">PHP Version</div>
                        <div class="text-lg font-semibold text-slate-100">{{ $systemInfo['php_version'] }}</div>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="text-sm text-slate-400 mb-2">Laravel Version</div>
                        <div class="text-lg font-semibold text-slate-100">{{ $systemInfo['laravel_version'] }}</div>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="text-sm text-slate-400 mb-2">Database</div>
                        <div class="text-lg font-semibold text-slate-100">{{ $systemInfo['database'] }}</div>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="text-sm text-slate-400 mb-2">Cache Driver</div>
                        <div class="text-lg font-semibold text-slate-100">{{ $systemInfo['cache_driver'] }}</div>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="text-sm text-slate-400 mb-2">Queue Driver</div>
                        <div class="text-lg font-semibold text-slate-100">{{ $systemInfo['queue_driver'] }}</div>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="text-sm text-slate-400 mb-2">Server</div>
                        <div class="text-sm font-semibold text-slate-100">{{ $systemInfo['server'] }}</div>
                    </div>
                </div>

                <!-- Storage Info -->
                <div class="bg-slate-700/50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-slate-100 mb-4">Storage Information</h4>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-slate-400">Total Space</span>
                                <span class="text-slate-200 font-medium">
                                    {{ number_format($storageInfo['total_space'] / 1024 / 1024 / 1024, 2) }} GB
                                </span>
                            </div>
                            <div class="w-full bg-slate-600 rounded-full h-2">
                                @php
                                $usedPercent = ($storageInfo['used_space'] / $storageInfo['total_space']) * 100;
                                @endphp
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" 
                                     style="width: {{ $usedPercent }}%"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-slate-400">Used:</span>
                                <span class="text-slate-200 font-medium ml-2">
                                    {{ number_format($storageInfo['used_space'] / 1024 / 1024 / 1024, 2) }} GB
                                </span>
                            </div>
                            <div>
                                <span class="text-slate-400">Free:</span>
                                <span class="text-green-400 font-medium ml-2">
                                    {{ number_format($storageInfo['free_space'] / 1024 / 1024 / 1024, 2) }} GB
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Database Tab -->
            <div x-show="activeTab === 'database'" class="space-y-6">
                <h3 class="text-xl font-semibold text-slate-100 mb-4">Statistik Database</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="text-sm text-slate-400 mb-2">Total Users</div>
                        <div class="text-2xl font-bold text-blue-400">{{ number_format($dbStats['total_users']) }}</div>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="text-sm text-slate-400 mb-2">Total Tempat Kos</div>
                        <div class="text-2xl font-bold text-purple-400">{{ number_format($dbStats['total_tempat_kos']) }}</div>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="text-sm text-slate-400 mb-2">Total Rooms</div>
                        <div class="text-2xl font-bold text-green-400">{{ number_format($dbStats['total_rooms']) }}</div>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="text-sm text-slate-400 mb-2">Total Rents</div>
                        <div class="text-2xl font-bold text-orange-400">{{ number_format($dbStats['total_rents']) }}</div>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="text-sm text-slate-400 mb-2">Total Billings</div>
                        <div class="text-2xl font-bold text-yellow-400">{{ number_format($dbStats['total_billings']) }}</div>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-lg p-4">
                        <div class="text-sm text-slate-400 mb-2">Total Payments</div>
                        <div class="text-2xl font-bold text-pink-400">{{ number_format($dbStats['total_payments']) }}</div>
                    </div>
                </div>
            </div>

            <!-- Backup Tab -->
            <div x-show="activeTab === 'backup'" class="space-y-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-slate-100">Database Backup</h3>
                    
                    <form action="{{ route('superadmin.settings.backup') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium transition shadow-lg">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Buat Backup Baru
                        </button>
                    </form>
                </div>

                @if(count($backups) > 0)
                <div class="bg-slate-700/50 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">File Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Size</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase">Date</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-slate-300 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($backups as $backup)
                            <tr class="hover:bg-slate-700/30">
                                <td class="px-6 py-4 text-sm text-slate-200">{{ $backup['name'] }}</td>
                                <td class="px-6 py-4 text-sm text-slate-400">{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                                <td class="px-6 py-4 text-sm text-slate-400">{{ date('d M Y H:i', $backup['date']) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('superadmin.settings.backup.download', $backup['name']) }}" 
                                           class="p-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded-lg transition"
                                           title="Download">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                        
                                        <form action="{{ route('superadmin.settings.backup.delete', $backup['name']) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Hapus backup ini?')"
                                                    class="p-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition"
                                                    title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12 bg-slate-700/30 rounded-lg">
                    <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-slate-400">Belum ada backup tersedia</p>
                </div>
                @endif
            </div>

            <!-- Maintenance Tab -->
            <div x-show="activeTab === 'maintenance'" class="space-y-6">
                <h3 class="text-xl font-semibold text-slate-100 mb-4">System Maintenance</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Clear Cache -->
                    <div class="bg-slate-700/50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-orange-500/20 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-slate-100">Clear Cache</h4>
                                <p class="text-sm text-slate-400">Hapus semua cache aplikasi</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('superadmin.settings.cache.clear') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition">
                                Clear All Cache
                            </button>
                        </form>
                    </div>

                    <!-- Optimize -->
                    <div class="bg-slate-700/50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-slate-100">Optimize</h4>
                                <p class="text-sm text-slate-400">Optimasi performa aplikasi</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('superadmin.settings.optimize') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                Optimize System
                            </button>
                        </form>
                    </div>

                    <!-- Run Migrations -->
                    <div class="bg-slate-700/50 rounded-lg p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-slate-100">Run Migrations</h4>
                                <p class="text-sm text-slate-400">Jalankan migrasi database</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('superadmin.settings.migrate') }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menjalankan migrasi? Pastikan Anda sudah backup database!')">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                Run Migrations
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection