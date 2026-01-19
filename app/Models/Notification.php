<?php

namespace App\Models;

use App\Traits\ScopesByTempatKos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use ScopesByTempatKos;

    protected $fillable = [
        'tempat_kos_id',
        'type',
        'title',
        'message',
        'user_id',
        'rent_id',
        'room_id',
        'billing_id',
        'admin_billing_id',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function tempatKos(): BelongsTo
    {
        return $this->belongsTo(TempatKos::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rent(): BelongsTo
    {
        return $this->belongsTo(Rent::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi untuk User Billing (penyewa kos)
     */
    public function billing(): BelongsTo
    {
        return $this->belongsTo(Billing::class, 'billing_id');
    }

    /**
     * Relasi untuk Admin Billing (superadmin → admin)
     */
    public function adminBilling(): BelongsTo
    {
        return $this->belongsTo(AdminBilling::class, 'admin_billing_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Helpers
    public function markAsRead(): bool
    {
        return $this->update(['status' => 'read']);
    }

    public function markAsUnread(): bool
    {
        return $this->update(['status' => 'unread']);
    }

    // Accessors
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'booking' => 'Booking',
            'billing' => 'Tagihan',
            'payment' => 'Pembayaran',
            'checkout' => 'Checkout',
            'reminder' => 'Pengingat',
            default => 'Notifikasi',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'booking' => '📋',
            'billing' => '💰',
            'payment' => '💳',
            'checkout' => '🚪',
            'reminder' => '⏰',
            default => '🔔',
        };
    }
}