<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        return view('superadmin.settings.index', [
            'appName' => AppSetting::get('app_name', 'KosSmart'),
            'appLogo' => AppSetting::get('app_logo', 'images/logo.png'),
            'tenantDashboardImage' => AppSetting::get(
                'tenant_dashboard_image',
                'images/Carousel Tenant.png'
            ),
            'heroImageEmpty' => AppSetting::get('hero_image_empty', 'images/image1.png'),
        ]);
    }

    /**
     * Update application settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:50',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'tenant_dashboard_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'hero_image_empty' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'app_name.required' => 'Nama aplikasi wajib diisi',
            'app_name.max' => 'Nama aplikasi maksimal 50 karakter',
            'app_logo.image' => 'Logo harus berupa gambar',
            'app_logo.max' => 'Ukuran logo maksimal 2MB',
            'carousel_tenant_1.image' => 'Carousel 1 harus berupa gambar',
            'carousel_tenant_1.max' => 'Ukuran carousel 1 maksimal 2MB',
            'carousel_tenant_2.image' => 'Carousel 2 harus berupa gambar',
            'carousel_tenant_2.max' => 'Ukuran carousel 2 maksimal 2MB',
            'carousel_tenant_3.image' => 'Carousel 3 harus berupa gambar',
            'carousel_tenant_3.max' => 'Ukuran carousel 3 maksimal 2MB',
            'hero_image_empty.image' => 'Hero image harus berupa gambar',
            'hero_image_empty.max' => 'Ukuran hero image maksimal 2MB',
        ]);

        try {
            // Update app name
            AppSetting::set('app_name', $validated['app_name']);

            // Handle app logo
            if ($request->hasFile('app_logo')) {
                $oldLogo = AppSetting::where('key', 'app_logo')->first();
                if ($oldLogo) {
                    $oldLogo->deleteOldImage();
                }

                $logoPath = $request->file('app_logo')->store('settings/logos', 'public');
                AppSetting::set('app_logo', $logoPath, 'image');
            }

            // Handle hero image tenant
            if ($request->hasFile('tenant_dashboard_image')) {
                $old = AppSetting::where('key', 'tenant_dashboard_image')->first();
                if ($old) {
                    $old->deleteOldImage();
                }

                $path = $request->file('tenant_dashboard_image')
                    ->store('settings/heroes', 'public');

                AppSetting::set('tenant_dashboard_image', $path, 'image');
            }

            // HANDLE HERO DASHBOARD EMPTY (INI YANG KURANG)
            if ($request->hasFile('hero_image_empty')) {
                $old = AppSetting::where('key', 'hero_image_empty')->first();
                if ($old) {
                    $old->deleteOldImage();
                }

                $path = $request->file('hero_image_empty')
                    ->store('settings/heroes', 'public');

                AppSetting::set('hero_image_empty', $path, 'image');
            }

            // Clear cache
            AppSetting::clearCache();

            return redirect()
                ->route('superadmin.settings.index')
                ->with('success', 'Pengaturan aplikasi berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
