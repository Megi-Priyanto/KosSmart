<?php

namespace App\Models;

use App\Traits\ScopesByTempatKos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    use HasFactory;
    use ScopesByTempatKos;

    protected $fillable = [
        'tempat_kos_id',
        'kos_info_id',
        'room_number',
        'floor',
        'type',
        'capacity',
        'size',
        'has_window',
        'price',
        'jenis_sewa',
        'description',
        'notes',
        'facilities',
        'images',
        'status',
        'last_maintenance',
        'view_count',
    ];

    protected $casts = [
        'facilities' => 'array',
        'images' => 'array',
        'price' => 'decimal:2',
        'size' => 'decimal:2',
        'has_window' => 'boolean',
        'last_maintenance' => 'date',
        'view_count' => 'integer',
    ];

    /**
     * Relasi: Room belongs to Tempat Kos
     */
    public function tempatKos(): BelongsTo
    {
        return $this->belongsTo(TempatKos::class);
    }

    /**
     * Relasi: Room belongs to Kos Info
     * (Untuk backward compatibility dengan sistem lama)
     */
    public function kosInfo(): BelongsTo
    {
        return $this->belongsTo(KosInfo::class, 'kos_info_id');
    }

    /**
     * Relasi: Room memiliki banyak rents
     */
    public function rents(): HasMany
    {
        return $this->hasMany(Rent::class);
    }

    /**
     * Relasi: Room memiliki satu rent aktif
     * Include 'checkout_requested' agar tombol approve muncul
     */
    public function currentRent(): HasOne
    {
        return $this->hasOne(Rent::class)
            ->whereIn('status', ['active', 'checkout_requested'])
            ->whereNull('end_date')
            ->latest();
    }

    /**
     * Relasi: Room memiliki banyak billings
     */
    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class, 'room_id');
    }

    /**
     * Helper: Cek apakah kamar tersedia
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available' && !$this->currentRent()->exists();
    }

    /**
     * Helper: Cek apakah kamar terisi
     */
    public function isOccupied(): bool
    {
        return $this->status === 'occupied';
    }

    /**
     * Helper: Cek apakah kamar sedang maintenance
     */
    public function isMaintenance(): bool
    {
        return $this->status === 'maintenance';
    }

    /**
     * Helper: Get current tenant (penghuni saat ini)
     */
    public function currentTenant()
    {
        return $this->currentRent?->user;
    }

    /**
     * Helper: Get status badge color
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'available' => 'green',
            'occupied' => 'blue',
            'maintenance' => 'orange',
            default => 'gray',
        };
    }

    /**
     * Helper: Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Tersedia',
            'occupied' => 'Terisi',
            'maintenance' => 'Maintenance',
            default => 'Unknown',
        };
    }

    /**
     * Helper: Get formatted jenis sewa label
     */
    public function getJenisSewaLabelAttribute(): string
    {
        return match ($this->jenis_sewa) {
            'bulan' => 'Per Bulan',
            'tahun' => 'Per Tahun',
            default => 'Per Bulan',
        };
    }

    /**
     * Helper: Get formatted price with period
     */
    public function getFormattedPriceAttribute(): string
    {
        $price = 'Rp ' . number_format($this->price, 0, ',', '.');
        $period = $this->jenis_sewa === 'tahun' ? '/tahun' : '/bulan';
        return $price . ' ' . $period;
    }

    /**
     * Helper: Increment view count
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Scope: Kamar yang tersedia saja
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
            ->whereDoesntHave('currentRent');
    }

    /**
     * Scope: Kamar yang terisi
     */
    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    /**
     * Scope: Kamar yang sedang maintenance
     */
    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    /**
     * Scope: Filter by type
     */
    public function scopeOfType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }

    /**
     * Scope: Filter by floor
     */
    public function scopeOnFloor($query, $floor)
    {
        if ($floor) {
            return $query->where('floor', $floor);
        }
        return $query;
    }

    /**
     * Scope: Filter by status
     */
    public function scopeWithStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope: Filter by price range
     */
    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min) {
            $query->where('price', '>=', $min);
        }
        if ($max) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    /**
     * Scope: Filter by jenis sewa
     */
    public function scopeByJenisSewa($query, $jenisSewa)
    {
        if ($jenisSewa) {
            return $query->where('jenis_sewa', $jenisSewa);
        }
        return $query;
    }

    /**
     * Scope: Popular rooms (berdasarkan view count)
     */
    public function scopePopular($query, $limit = 5)
    {
        return $query->orderBy('view_count', 'desc')->limit($limit);
    }

    /**
     * Helper: Check if room needs maintenance
     * (jika last_maintenance lebih dari 6 bulan yang lalu)
     */
    public function needsMaintenance(): bool
    {
        if (!$this->last_maintenance) {
            return true;
        }
        
        return $this->last_maintenance->addMonths(6)->isPast();
    }

    /**
     * Helper: Get maintenance status
     */
    public function getMaintenanceStatusAttribute(): string
    {
        if (!$this->last_maintenance) {
            return 'Belum pernah maintenance';
        }
        
        $monthsSince = now()->diffInMonths($this->last_maintenance);
        
        if ($monthsSince >= 6) {
            return 'Perlu maintenance';
        } elseif ($monthsSince >= 4) {
            return 'Maintenance dalam waktu dekat';
        } else {
            return 'Maintenance baik';
        }
    }
}