<?php
/**
 * File: app/Traits/ScopesByTempatKos.php
 * 
 * PERBAIKAN:
 * 1. Tambah pengecekan auth()->check() untuk seeders
 * 2. Tambah pengecekan !$model->tempat_kos_id untuk manual assignment
 * 3. Perbaiki kondisi global scope agar tidak error saat seeder
 */

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Trait untuk membatasi query berdasarkan tempat_kos_id
 * 
 * Digunakan di Model: Room, Rent, Billing, Payment, Notification, KosInfo
 * 
 * Fungsi:
 * - Auto-filter query untuk admin (hanya data kos mereka)
 * - Auto-assign tempat_kos_id saat create data baru
 * - Super admin bisa bypass dengan withoutTempatKosScope()
 * - Seeder-friendly: tidak error saat tidak ada user login
 */
trait ScopesByTempatKos
{
    /**
     * Boot trait
     * Otomatis filter berdasarkan tempat_kos_id untuk admin
     */
    protected static function bootScopesByTempatKos(): void
    {
        // GLOBAL SCOPE: Auto-filter berdasarkan tempat_kos_id untuk admin
        static::addGlobalScope('tempat_kos', function (Builder $builder) {
            // PERBAIKAN: Cek auth()->check() dulu sebelum akses user
            if (!Auth::check()) {
                return; // Jika tidak ada user login (seperti saat seeder), skip filtering
            }

            $user = Auth::user();

            // Hanya filter untuk admin (bukan super_admin atau user)
            if ($user && $user->role === 'admin' && $user->tempat_kos_id) {
                // Otomatis tambahkan WHERE tempat_kos_id = X ke setiap query
                $builder->where($builder->getModel()->getTable() . '.tempat_kos_id', $user->tempat_kos_id);
            }
        });

        // AUTO-ASSIGN: Otomatis isi tempat_kos_id saat create
        static::creating(function ($model) {
            // PERBAIKAN: Cek auth()->check() dan cek apakah tempat_kos_id sudah diisi
            if (!Auth::check()) {
                return; // Jika tidak ada user login (seperti saat seeder), skip auto-assign
            }

            $user = Auth::user();

            // PERBAIKAN: Hanya assign jika tempat_kos_id belum diisi (NULL)
            // Ini memungkinkan seeder atau manual assignment untuk set sendiri
            if ($user && $user->role === 'admin' && $user->tempat_kos_id) {
                if (!$model->tempat_kos_id) { // Jika NULL, baru diisi otomatis
                    $model->tempat_kos_id = $user->tempat_kos_id;
                }
            }
        });
    }

    /**
     * Scope: Manual filter by tempat_kos_id
     * 
     * Usage: Room::byTempatKos(1)->get()
     */
    public function scopeByTempatKos(Builder $query, int $tempatKosId): Builder
    {
        return $query->where('tempat_kos_id', $tempatKosId);
    }

    /**
     * Scope: Untuk super admin atau seeder - tanpa filter
     * 
     * Usage: Room::withoutTempatKosScope()->get()
     * Usage di Seeder: KosInfo::withoutGlobalScope('tempat_kos')->create([...])
     */
    public function scopeWithoutTempatKosScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope('tempat_kos');
    }

    /**
     * Helper: Cek apakah model belongs to tempat kos tertentu
     * 
     * Usage: if ($room->belongsToTempatKos(1)) { ... }
     */
    public function belongsToTempatKos(int $tempatKosId): bool
    {
        return $this->tempat_kos_id === $tempatKosId;
    }

    /**
     * TAMBAHAN: Helper untuk mendapatkan tempat_kos_id dari user yang login
     * 
     * Usage: $tempatKosId = Room::getAuthTempatKosId()
     */
    public static function getAuthTempatKosId(): ?int
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();
        return $user->tempat_kos_id ?? null;
    }

    /**
     * TAMBAHAN: Helper untuk cek apakah user adalah admin dari tempat kos tertentu
     * 
     * Usage: if (Room::isAdminOfTempatKos(1)) { ... }
     */
    public static function isAdminOfTempatKos(int $tempatKosId): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        return $user->role === 'admin' && $user->tempat_kos_id === $tempatKosId;
    }
}