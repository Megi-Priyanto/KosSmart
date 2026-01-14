<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = $this->getAllSettings();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'kost_address' => 'nullable|string|max:500',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'timezone' => 'required|string',
            'currency' => 'required|string',
        ]);

        try {
            // Update settings
            $this->updateSetting('app_name', $request->app_name);
            $this->updateSetting('contact_email', $request->contact_email);
            $this->updateSetting('contact_phone', $request->contact_phone);
            $this->updateSetting('kost_address', $request->kost_address);
            $this->updateSetting('timezone', $request->timezone);
            $this->updateSetting('currency', $request->currency);

if ($request->hasFile('app_logo')) {

    // AMBIL LOGO LAMA DULU
    $oldLogo = $this->getSetting('app_logo');

    $logo = $request->file('app_logo');
    $logoName = 'logo_' . time() . '.' . $logo->extension();

    // SIMPAN FILE BARU
    $logo->storeAs('public/images', $logoName);

    // UPDATE DB
    $this->updateSetting('app_logo', $logoName);

    // HAPUS FILE LAMA
    if ($oldLogo && $oldLogo !== $logoName && Storage::exists('public/images/' . $oldLogo)) {
        Storage::delete('public/images/' . $oldLogo);
    }
}

            // Clear cache
            Cache::forget('app_settings');

            return redirect()->route('admin.settings.index')
                ->with('success', 'Pengaturan umum berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update billing settings
     */
    public function updateBilling(Request $request)
    {
        $request->validate([
            'default_due_date' => 'required|integer|min:1|max:28',
            'late_fee_type' => 'required|in:fixed,percentage',
            'late_fee_amount' => 'required|numeric|min:0',
            'grace_period' => 'required|integer|min:0|max:30',
            'auto_generate_billing' => 'nullable|boolean',
            'payment_methods' => 'nullable|array',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_holder' => 'nullable|string|max:100',
        ]);

        try {
            // Update billing settings
            $this->updateSetting('default_due_date', $request->default_due_date);
            $this->updateSetting('late_fee_type', $request->late_fee_type);
            $this->updateSetting('late_fee_amount', $request->late_fee_amount);
            $this->updateSetting('grace_period', $request->grace_period);
            $this->updateSetting('auto_generate_billing', $request->has('auto_generate_billing') ? 1 : 0);
            
            // Payment methods
            $paymentMethods = $request->payment_methods ?? [];
            $this->updateSetting('payment_methods', json_encode($paymentMethods));
            
            // Bank details
            if (in_array('transfer', $paymentMethods)) {
                $this->updateSetting('bank_name', $request->bank_name);
                $this->updateSetting('account_number', $request->account_number);
                $this->updateSetting('account_holder', $request->account_holder);
            }

            Cache::forget('app_settings');

            return redirect()->route('admin.settings.index')
                ->with('success', 'Pengaturan tagihan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update notification settings
     */
    public function updateNotification(Request $request)
    {
        $request->validate([
            'notify_new_booking' => 'nullable|boolean',
            'notify_payment_received' => 'nullable|boolean',
            'notify_overdue' => 'nullable|boolean',
            'reminder_days' => 'required|integer|min:0|max:30',
            'send_reminder_email' => 'nullable|boolean',
        ]);

        try {
            $this->updateSetting('notify_new_booking', $request->has('notify_new_booking') ? 1 : 0);
            $this->updateSetting('notify_payment_received', $request->has('notify_payment_received') ? 1 : 0);
            $this->updateSetting('notify_overdue', $request->has('notify_overdue') ? 1 : 0);
            $this->updateSetting('reminder_days', $request->reminder_days);
            $this->updateSetting('send_reminder_email', $request->has('send_reminder_email') ? 1 : 0);

            Cache::forget('app_settings');

            return redirect()->route('admin.settings.index')
                ->with('success', 'Pengaturan notifikasi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update security settings
     */
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'min_password_length' => 'required|integer|min:6|max:20',
            'require_uppercase' => 'nullable|boolean',
            'require_number' => 'nullable|boolean',
            'require_special' => 'nullable|boolean',
            'session_lifetime' => 'required|integer|min:30|max:1440',
            'remember_me_enabled' => 'nullable|boolean',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:5|max:60',
            'require_email_verification' => 'nullable|boolean',
        ]);

        try {
            $this->updateSetting('min_password_length', $request->min_password_length);
            $this->updateSetting('require_uppercase', $request->has('require_uppercase') ? 1 : 0);
            $this->updateSetting('require_number', $request->has('require_number') ? 1 : 0);
            $this->updateSetting('require_special', $request->has('require_special') ? 1 : 0);
            $this->updateSetting('session_lifetime', $request->session_lifetime);
            $this->updateSetting('remember_me_enabled', $request->has('remember_me_enabled') ? 1 : 0);
            $this->updateSetting('max_login_attempts', $request->max_login_attempts);
            $this->updateSetting('lockout_duration', $request->lockout_duration);
            $this->updateSetting('require_email_verification', $request->has('require_email_verification') ? 1 : 0);

            Cache::forget('app_settings');

            return redirect()->route('admin.settings.index')
                ->with('success', 'Pengaturan keamanan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Backup database
     */
    public function backupDatabase()
    {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $path = storage_path('app/backups/' . $filename);

            // Create backups directory if not exists
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            // Get database configuration
            $database = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');

            // Execute mysqldump command
            $command = sprintf(
                'mysqldump -h %s -u %s -p%s %s > %s',
                escapeshellarg($host),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database),
                escapeshellarg($path)
            );

            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                // Save backup record
                $this->updateSetting('last_backup_date', now());
                $this->updateSetting('last_backup_file', $filename);

                return response()->json([
                    'success' => true,
                    'message' => 'Backup database berhasil dibuat',
                    'filename' => $filename
                ]);
            } else {
                throw new \Exception('Backup gagal dijalankan');
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Backup gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename)
    {
        $path = storage_path('app/backups/' . $filename);

        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File backup tidak ditemukan');
        }

        return response()->download($path);
    }

    /**
     * Clear application cache
     */
    public function clearCache(Request $request)
    {
        $type = $request->input('type', 'all');

        try {
            switch ($type) {
                case 'application':
                    Artisan::call('cache:clear');
                    $message = 'Application cache berhasil dihapus';
                    break;
                
                case 'route':
                    Artisan::call('route:clear');
                    $message = 'Route cache berhasil dihapus';
                    break;
                
                case 'config':
                    Artisan::call('config:clear');
                    $message = 'Config cache berhasil dihapus';
                    break;
                
                case 'view':
                    Artisan::call('view:clear');
                    $message = 'View cache berhasil dihapus';
                    break;
                
                case 'all':
                default:
                    Artisan::call('cache:clear');
                    Artisan::call('route:clear');
                    Artisan::call('config:clear');
                    Artisan::call('view:clear');
                    $message = 'Semua cache berhasil dihapus';
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Get all settings
     */
    private function getAllSettings()
    {
        return Cache::remember('app_settings', 3600, function () {
            $settings = Setting::pluck('value', 'key')->toArray();
            
            // Set defaults if not exists
            $defaults = [
                'app_name' => 'KosSmart',
                'contact_email' => 'admin@kossmart.com',
                'contact_phone' => '08123456789',
                'kost_address' => '',
                'timezone' => 'Asia/Jakarta',
                'currency' => 'IDR',
                'default_due_date' => 5,
                'late_fee_type' => 'fixed',
                'late_fee_amount' => 50000,
                'grace_period' => 3,
                'auto_generate_billing' => 1,
                'payment_methods' => json_encode(['cash', 'transfer']),
                'notify_new_booking' => 1,
                'notify_payment_received' => 1,
                'notify_overdue' => 1,
                'reminder_days' => 3,
                'send_reminder_email' => 1,
                'min_password_length' => 8,
                'require_uppercase' => 1,
                'require_number' => 1,
                'require_special' => 0,
                'session_lifetime' => 120,
                'remember_me_enabled' => 1,
                'max_login_attempts' => 5,
                'lockout_duration' => 15,
                'require_email_verification' => 1,
            ];

            return array_merge($defaults, $settings);
        });
    }

    /**
     * Helper: Get single setting
     */
    private function getSetting($key, $default = null)
    {
        $settings = $this->getAllSettings();
        return $settings[$key] ?? $default;
    }

    /**
     * Helper: Update or create setting
     */
    private function updateSetting($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}