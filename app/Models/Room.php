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
     */
    public function currentRent()
    {
        return $this->hasOne(Rent::class)
            ->where('status', 'active')
            ->whereNull('end_date');
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

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
