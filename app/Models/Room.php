<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Rent;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
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
     * Relasi: Room memiliki banyak rents
     */
    public function rents()
    {
        return $this->hasMany(Rent::class);
    }

    /**
     * Relasi: Room memiliki satu rent aktif
     * PERBAIKAN: Tambahkan 'checkout_requested' agar tombol approve muncul
     */
    public function currentRent()
    {
        return $this->hasOne(Rent::class)
            ->whereIn('status', ['active', 'checkout_requested']) // ← UBAH INI
            ->whereNull('end_date')
            ->latest(); // Tambahkan latest() untuk ambil yang terbaru
    }

    /**
     * Helper: Cek apakah kamar tersedia
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available' && !$this->currentRent()->exists();
    }

    /**
     * Scope: Kamar yang tersedia saja
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
            ->whereDoesntHave('currentRent');
    }

    public function kosInfo()
    {
        return $this->belongsTo(KosInfo::class, 'kos_info_id');
    }

    public function scopeOfType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }

    public function scopeOnFloor($query, $floor)
    {
        if ($floor) {
            return $query->where('floor', $floor);
        }
        return $query;
    }

    public function scopeWithStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

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

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'available' => 'green',
            'occupied' => 'blue',
            'maintenance' => 'orange',
            default => 'gray',
        };
    }

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

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function billings()
    {
        return $this->hasMany(Billing::class, 'room_id');
    }
}