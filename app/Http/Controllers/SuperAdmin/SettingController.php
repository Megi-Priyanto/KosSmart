<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        // System Info
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database' => DB::connection()->getDatabaseName(),
            'server' => request()->server('SERVER_SOFTWARE'),
            'storage_path' => storage_path(),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
        ];

        // Database Statistics
        $dbStats = [
            'total_users' => \App\Models\User::count(),
            'total_tempat_kos' => \App\Models\TempatKos::count(),
            'total_rooms' => \App\Models\Room::withoutTempatKosScope()->count(),
            'total_rents' => \App\Models\Rent::withoutTempatKosScope()->count(),
            'total_billings' => \App\Models\Billing::withoutTempatKosScope()->count(),
            'total_payments' => \App\Models\Payment::withoutTempatKosScope()->count(),
        ];

        // Storage Info
        $storageInfo = [
            'total_space' => disk_total_space(storage_path()),
            'free_space' => disk_free_space(storage_path()),
            'used_space' => disk_total_space(storage_path()) - disk_free_space(storage_path()),
        ];

        // Cache Info
        $cacheInfo = [
            'driver' => config('cache.default'),
            'has_cache' => Cache::has('test_key'),
        ];

        // Get backup files
        $backups = $this->getBackupFiles();

        return view('superadmin.settings.index', compact(
            'systemInfo',
            'dbStats',
            'storageInfo',
            'cacheInfo',
            'backups'
        ));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_timezone' => ['required', 'string'],
            'app_locale' => ['required', 'string'],
        ]);

        try {
            // Update .env file
            $this->updateEnvFile([
                'APP_NAME' => $validated['app_name'],
                'APP_TIMEZONE' => $validated['app_timezone'],
                'APP_LOCALE' => $validated['app_locale'],
            ]);

            // Clear config cache
            Artisan::call('config:clear');

            return back()->with('success', 'Pengaturan umum berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage());
        }
    }

    /**
     * Clear all caches
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return back()->with('success', 'Semua cache berhasil dibersihkan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    }

    /**
     * Optimize application
     */
    public function optimize()
    {
        try {
            Artisan::call('optimize');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            return back()->with('success', 'Aplikasi berhasil dioptimasi!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengoptimasi aplikasi: ' . $e->getMessage());
        }
    }

    /**
     * Create database backup
     */
    public function backupDatabase()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $path = storage_path('app/backups');

            // Create backups directory if not exists
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            // Get database config
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            // Create backup command
            $command = sprintf(
                'mysqldump -h %s -u %s -p%s %s > %s',
                escapeshellarg($host),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($path . '/' . $filename)
            );

            // Execute backup
            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                return back()->with('success', 'Backup database berhasil dibuat: ' . $filename);
            } else {
                throw new \Exception('Gagal membuat backup database');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename)
    {
        $path = storage_path('app/backups/' . $filename);

        if (!file_exists($path)) {
            return back()->with('error', 'File backup tidak ditemukan!');
        }

        return response()->download($path);
    }

    /**
     * Delete backup file
     */
    public function deleteBackup($filename)
    {
        try {
            $path = storage_path('app/backups/' . $filename);

            if (file_exists($path)) {
                unlink($path);
                return back()->with('success', 'Backup berhasil dihapus!');
            }

            return back()->with('error', 'File backup tidak ditemukan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus backup: ' . $e->getMessage());
        }
    }

    /**
     * Run database migrations
     */
    public function runMigrations()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);

            return back()->with('success', 'Migrasi database berhasil dijalankan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menjalankan migrasi: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Update .env file
     */
    private function updateEnvFile(array $data)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            throw new \Exception('.env file not found');
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            // Escape special characters in value
            $value = str_replace('"', '\"', $value);
            
            // Check if key exists
            if (preg_match("/^{$key}=/m", $envContent)) {
                // Update existing key
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $envContent
                );
            } else {
                // Add new key
                $envContent .= "\n{$key}=\"{$value}\"";
            }
        }

        file_put_contents($envPath, $envContent);
    }

    /**
     * Helper: Get backup files
     */
    private function getBackupFiles()
    {
        $path = storage_path('app/backups');

        if (!file_exists($path)) {
            return [];
        }

        $files = array_diff(scandir($path), ['.', '..']);
        $backups = [];

        foreach ($files as $file) {
            $filePath = $path . '/' . $file;
            
            if (is_file($filePath)) {
                $backups[] = [
                    'name' => $file,
                    'size' => filesize($filePath),
                    'date' => filemtime($filePath),
                ];
            }
        }

        // Sort by date descending
        usort($backups, function($a, $b) {
            return $b['date'] - $a['date'];
        });

        return $backups;
    }

    /**
     * Get system health check
     */
    public function healthCheck()
    {
        $health = [
            'database' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
            'cache' => $this->checkCache(),
            'queue' => $this->checkQueue(),
        ];

        return response()->json($health);
    }

    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Database connection OK'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => $e->getMessage()];
        }
    }

    private function checkStorage()
    {
        $writable = is_writable(storage_path());
        return [
            'status' => $writable ? 'healthy' : 'unhealthy',
            'message' => $writable ? 'Storage is writable' : 'Storage is not writable'
        ];
    }

    private function checkCache()
    {
        try {
            Cache::put('health_check', 'test', 60);
            $result = Cache::get('health_check') === 'test';
            return [
                'status' => $result ? 'healthy' : 'unhealthy',
                'message' => $result ? 'Cache is working' : 'Cache is not working'
            ];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => $e->getMessage()];
        }
    }

    private function checkQueue()
    {
        // Basic check - you can enhance this
        return ['status' => 'healthy', 'message' => 'Queue driver: ' . config('queue.default')];
    }
}