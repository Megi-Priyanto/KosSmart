<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminBilling extends Model
{
    protected $fillable = [
        'tempat_kos_id',
        'admin_id',
        'billing_period',
        'billing_month',
        'billing_year',
        'amount',
        'description',
        'due_date',
        'status',
        'payment_method',
        'payment_proof',
        'payment_notes',
        'paid_at',
        'verified_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Relasi: AdminBilling belongs to TempatKos
     */
    public function tempatKos(): BelongsTo
    {
        return $this->belongsTo(TempatKos::class);
    }

    /**
     * Relasi: AdminBilling belongs to Admin (User)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relasi: AdminBilling has many notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'billing_id');
    }

    /**
     * Scope: Unpaid billings
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    /**
     * Scope: Paid billings
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope: Overdue billings
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($q) {
                $q->where('status', 'unpaid')
                    ->whereDate('due_date', '<', now());
            });
    }

    /**
     * Scope: Current month billing
     */
    public function scopeCurrentMonth($query)
    {
        return $query->where('billing_month', now()->month)
            ->where('billing_year', now()->year);
    }

    /**
     * Helper: Mark as paid
     */
    public function markAsPaid(string $paymentProof, ?string $notes = null): bool
    {
        return $this->update([
            'status' => 'paid',
            'payment_proof' => $paymentProof,
            'paid_at' => now(),
            'payment_notes' => $notes,
        ]);
    }

    /**
     * Helper: Check if overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'unpaid' && $this->due_date->isPast();
    }

    /**
     * Accessor: Status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'unpaid' => 'Belum Dibayar',
            'pending' => 'Menunggu Verifikasi',
            'paid' => 'Lunas',
            'overdue' => 'Terlambat',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Accessor: Status color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'unpaid' => 'yellow',
            'pending' => 'blue',
            'paid' => 'green',
            'overdue' => 'red',
            default => 'gray',
        };
    }
}
