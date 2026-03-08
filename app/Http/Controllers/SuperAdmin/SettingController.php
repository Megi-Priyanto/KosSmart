<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('superadmin.settings.index', [
            'appName'              => AppSetting::get('app_name', 'KosSmart'),
            'appLogo'              => AppSetting::get('app_logo', 'images/logo.png'),
            'appFavicon'           => AppSetting::get('app_favicon', 'images/favicon.png'),
            'tenantDashboardImage' => AppSetting::get('tenant_dashboard_image', 'images/Carousel Tenant.png'),
            'heroImageEmpty'       => AppSetting::get('hero_image_empty', 'images/image1.png'),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name'               => 'required|string|max:50',
            'app_logo'               => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'app_favicon'            => 'nullable|image|mimes:png,jpg,jpeg,ico|max:512',
            'tenant_dashboard_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'hero_image_empty'       => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'app_name.required'    => 'Nama aplikasi wajib diisi',
            'app_name.max'         => 'Nama aplikasi maksimal 50 karakter',
            'app_logo.image'       => 'Logo harus berupa gambar',
            'app_logo.max'         => 'Ukuran logo maksimal 2MB',
            'app_favicon.image'    => 'Favicon harus berupa gambar',
            'app_favicon.max'      => 'Ukuran favicon maksimal 512KB',
            'hero_image_empty.image' => 'Hero image harus berupa gambar',
            'hero_image_empty.max'   => 'Ukuran hero image maksimal 2MB',
        ]);

        try {
            AppSetting::set('app_name', $request->app_name);

            if ($request->hasFile('app_logo')) {
                $old = AppSetting::where('key', 'app_logo')->first();
                if ($old) $old->deleteOldImage();
                $path = $request->file('app_logo')->store('settings/logos', 'public');
                AppSetting::set('app_logo', $path, 'image');
            }

            // ✅ Handle favicon baru
            if ($request->hasFile('app_favicon')) {
                $old = AppSetting::where('key', 'app_favicon')->first();
                if ($old) $old->deleteOldImage();
                $path = $request->file('app_favicon')->store('settings/favicons', 'public');
                AppSetting::set('app_favicon', $path, 'image');
            }

            if ($request->hasFile('tenant_dashboard_image')) {
                $old = AppSetting::where('key', 'tenant_dashboard_image')->first();
                if ($old) $old->deleteOldImage();
                $path = $request->file('tenant_dashboard_image')->store('settings/heroes', 'public');
                AppSetting::set('tenant_dashboard_image', $path, 'image');
            }

            if ($request->hasFile('hero_image_empty')) {
                $old = AppSetting::where('key', 'hero_image_empty')->first();
                if ($old) $old->deleteOldImage();
                $path = $request->file('hero_image_empty')->store('settings/heroes', 'public');
                AppSetting::set('hero_image_empty', $path, 'image');
            }

            AppSetting::clearCache();

            return redirect()
                ->route('superadmin.settings.index')
                ->with('success', 'Pengaturan aplikasi berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}