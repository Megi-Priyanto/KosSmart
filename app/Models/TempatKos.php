<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class TempatKos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tempat_kos';

    protected $fillable = [
        'nama_kos',
        'alamat',
        'kota',
        'kecamatan',
        'provinsi',
        'kode_pos',
        'telepon',
        'email',
        'status',
        'logo',
    ];

    protected $casts = [
        'fasilitas' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relasi: Tempat Kos memiliki banyak User
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi: Tempat Kos memiliki banyak Admin
     */
    public function admins(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }

    /**
     * Relasi: Tempat Kos memiliki banyak Penghuni
     */
    public function penghuni(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'user');
    }

    /**
     * Relasi: Tempat Kos memiliki banyak Kamar
     */
    public function rooms(): HasManyThrough
    {
        return $this->hasManyThrough(
            Room::class,
            KosInfo::class,
            'tempat_kos_id', // FK di kos_info
            'kos_info_id',   // FK di rooms
            'id',            // PK tempat_kos
            'id'             // PK kos_info
        );
    }

    /**
     * Relasi: Tempat Kos memiliki banyak Rent
     */
    public function rents(): HasMany
    {
        return $this->hasMany(Rent::class);
    }

    /**
     * Relasi: Tempat Kos memiliki banyak Billing
     */
    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class);
    }

    /**
     * Relasi: Tempat Kos memiliki banyak Payment
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relasi: Tempat Kos memiliki banyak Notification
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Helper: Cek apakah kos masih aktif
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Alamat Lengkap
     */
    public function getAlamatLengkapAttribute(): string
    {
        $parts = array_filter([
            $this->alamat,
            $this->kecamatan,
            $this->kota,
            $this->provinsi,
            $this->kode_pos
        ]);
        
        return implode(', ', $parts);
    }

    /**
     * Helper: Hitung total kamar
     */
    public function getTotalKamarAttribute(): int
    {
        return $this->rooms()->count();
    }

    /**
     * Helper: Hitung kamar terisi
     */
    public function getKamarTerisiAttribute(): int
    {
        return $this->rooms()->where('status', 'occupied')->count();
    }

    /**
     * Helper: Hitung kamar tersedia
     */
    public function getKamarTersediaAttribute(): int
    {
        return $this->rooms()->where('status', 'available')->count();
    }

    /**
     * Scope: Hanya kos aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function kosInfos(): HasMany
    {
        return $this->hasMany(KosInfo::class);
    }
}