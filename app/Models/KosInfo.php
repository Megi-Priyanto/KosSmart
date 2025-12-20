<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KosInfo extends Model
{
    use HasFactory;

    protected $table = 'kos_info';

    protected $fillable = [
        'name',
        'address',
        'city',
        'province',
        'postal_code',
        'phone',
        'whatsapp',
        'email',
        'description',
        'general_facilities',
        'rules',
        'images',
        'checkin_time',
        'checkout_time',
        'is_active',
    ];

    protected $casts = [
        'general_facilities' => 'array',
        'rules' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
        'checkin_time' => 'datetime:H:i',
        'checkout_time' => 'datetime:H:i',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'kos_info_id');
    }

    public function getTotalRoomsAttribute(): int
    {
        return $this->rooms()->count();
    }

    public function getAvailableRoomsAttribute(): int
    {
        return $this->rooms()->where('status', 'available')->count();
    }

    public function getOccupiedRoomsAttribute(): int
    {
        return $this->rooms()->where('status', 'occupied')->count();
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->city}, {$this->province} {$this->postal_code}";
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
