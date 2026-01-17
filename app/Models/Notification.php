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
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Relasi: Notification belongs to Tempat Kos
     */
    public function tempatKos(): BelongsTo
    {
        return $this->belongsTo(TempatKos::class);
    }

    /**
     * Relasi: Notification belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Notification belongs to Rent
     */
    public function rent(): BelongsTo
    {
        return $this->belongsTo(Rent::class);
    }

    /**
     * Relasi: Notification belongs to Room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Scope: Unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    /**
     * Scope: Read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    /**
     * Scope: Filter by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Helper: Mark as read
     */
    public function markAsRead(): bool
    {
        return $this->update(['status' => 'read']);
    }

    /**
     * Helper: Mark as unread
     */
    public function markAsUnread(): bool
    {
        return $this->update(['status' => 'unread']);
    }

    /**
     * Accessor: Get type label
     */
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

    /**
     * Accessor: Get type icon
     */
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