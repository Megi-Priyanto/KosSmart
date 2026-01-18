<?php
// app/Models/Payment.php

namespace App\Models;

use App\Traits\ScopesByTempatKos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    use ScopesByTempatKos;

    protected $fillable = [
        'tempat_kos_id',
        'user_id',
        'billing_id',
        'amount',
        'payment_method',
        'payment_type',
        'payment_sub_method',
        'payment_proof',
        'status',
        'payment_date',
        'notes',
        'verified_by',
        'verified_at',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'verified_at' => 'datetime',
    ];

    /**
     * Relasi: Payment belongs to Tempat Kos
     */
    public function tempatKos(): BelongsTo
    {
        return $this->belongsTo(TempatKos::class);
    }

    /**
     * Relasi: Payment belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Payment belongs to Billing
     */
    public function billing(): BelongsTo
    {
        return $this->belongsTo(Billing::class);
    }

    /**
     * Relasi: Payment verified by Admin
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope: Pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Confirmed payments
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope: Rejected payments
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Accessor: Get status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'confirmed' =>
            'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30',

            'pending' =>
            'bg-amber-500/15 text-amber-400 border border-amber-500/30',

            'rejected' =>
            'bg-red-500/15 text-red-400 border border-red-500/30',

            default =>
            'bg-slate-500/15 text-slate-300 border border-slate-500/30',
        };
    }

    /**
     * Accessor: Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => 'Dikonfirmasi',
            'pending' => 'Menunggu Verifikasi',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }

    /**
     * Accessor: Get payment method label
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'manual_transfer' => 'Transfer Bank',
            'e_wallet' => 'E-Wallet',
            'qris' => 'QRIS',
            default => '-',
        };
    }

    /**
     * Helper: Verify payment
     */
    public function verify(int $verifierId, ?string $notes = null): bool
    {
        return $this->update([
            'status' => 'confirmed',
            'verified_by' => $verifierId,
            'verified_at' => now(),
            'notes' => $notes ?? $this->notes,
        ]);
    }

    /**
     * Helper: Reject payment
     */
    public function reject(int $verifierId, string $reason): bool
    {
        return $this->update([
            'status' => 'rejected',
            'verified_by' => $verifierId,
            'verified_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Accessor: Get full payment label
     */
    public function getPaymentFullLabelAttribute(): string
    {
        $type = match ($this->payment_method) {
            'manual_transfer' => 'Transfer Bank',
            'e_wallet' => 'E-Wallet',
            'qris' => 'QRIS',
            default => '-',
        };

        if (in_array($this->payment_method, ['manual_transfer', 'e_wallet']) && $this->payment_sub_method) {
            return "{$type} (" . strtoupper($this->payment_sub_method) . ")";
        }

        return $type; // QRIS
    }
}
