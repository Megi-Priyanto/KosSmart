<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'account_number',
        'account_name',
        'instructions',
        'qr_code_path', // ✅ Tambahkan ini
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope: Active payment methods
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    /**
     * Scope: By type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get icon class for payment method
     */
    public function getIconAttribute(): string
    {
        return match(strtolower($this->name)) {
            'bca' => 'bg-blue-500',
            'bni' => 'bg-orange-500',
            'mandiri' => 'bg-yellow-500',
            'dana' => 'bg-blue-600',
            'ovo' => 'bg-purple-500',
            'gopay' => 'bg-green-500',
            'qris' => 'bg-red-500',
            default => 'bg-gray-500',
        };
    }

    /**
     * Get QR Code URL
     */
    public function getQrCodeUrlAttribute(): ?string
    {
        return $this->qr_code_path ? asset('storage/' . $this->qr_code_path) : null;
    }
}