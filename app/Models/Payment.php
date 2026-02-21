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
        // === KOLOM BARU UNTUK DISBURSEMENT ===
        'disbursement_status', // 'holding' | 'disbursed'
        'disbursement_id',     // FK ke disbursements (nullable)
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'payment_date'     => 'date',
        'verified_at'      => 'datetime',
        'disbursement_status' => 'string',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    public function tempatKos(): BelongsTo
    {
        return $this->belongsTo(TempatKos::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function billing(): BelongsTo
    {
        return $this->belongsTo(Billing::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Relasi ke disbursement (akan terisi saat dana dicairkan ke admin)
     */
    public function disbursement(): BelongsTo
    {
        return $this->belongsTo(Disbursement::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Dana yang masih di-holding Superadmin
     * (sudah confirmed tapi belum dicairkan ke admin)
     */
    public function scopeHolding($query)
    {
        return $query->where('status', 'confirmed')
                     ->where('disbursement_status', 'holding');
    }

    /**
     * Scope: Dana yang sudah dicairkan ke admin
     */
    public function scopeDisbursed($query)
    {
        return $query->where('status', 'confirmed')
                     ->where('disbursement_status', 'disbursed');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Konfirmasi payment.
     * Dana otomatis masuk ke "holding" Superadmin — TIDAK langsung ke Admin.
     * 
     * @param int    $verifierId  ID admin/superadmin yang memverifikasi
     * @param string|null $notes  Catatan opsional
     */
    public function verify(int $verifierId, ?string $notes = null): bool
    {
        return $this->update([
            'status'               => 'confirmed',
            'verified_by'          => $verifierId,
            'verified_at'          => now(),
            'notes'                => $notes ?? $this->notes,
            // === PERUBAHAN UTAMA ===
            // Dana masuk ke holding Superadmin terlebih dahulu
            'disbursement_status'  => 'holding',
            'disbursement_id'      => null, // Belum ada disbursement
        ]);
    }

    /**
     * Tolak payment.
     */
    public function reject(int $verifierId, string $reason): bool
    {
        return $this->update([
            'status'           => 'rejected',
            'verified_by'      => $verifierId,
            'verified_at'      => now(),
            'rejection_reason' => $reason,
            // Tidak perlu update disbursement_status (tetap default 'holding')
            // karena payment rejected tidak relevan untuk disbursement
        ]);
    }

    /**
     * Tandai payment sudah dicairkan ke admin (dipanggil saat disbursement diproses)
     */
    public function markAsDisbursed(int $disbursementId): bool
    {
        return $this->update([
            'disbursement_status' => 'disbursed',
            'disbursement_id'     => $disbursementId,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30',
            'pending'   => 'bg-amber-500/15 text-amber-400 border border-amber-500/30',
            'rejected'  => 'bg-red-500/15 text-red-400 border border-red-500/30',
            default     => 'bg-slate-500/15 text-slate-300 border border-slate-500/30',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => 'Dikonfirmasi',
            'pending'   => 'Menunggu Verifikasi',
            'rejected'  => 'Ditolak',
            default     => 'Unknown',
        };
    }

    public function getDisbursementStatusLabelAttribute(): string
    {
        if ($this->status !== 'confirmed') {
            return '-';
        }

        return match ($this->disbursement_status) {
            'holding'   => 'Holding (Menunggu Pencairan)',
            'disbursed' => 'Sudah Dicairkan ke Admin',
            default     => '-',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'manual_transfer' => 'Transfer Bank',
            'e_wallet'        => 'E-Wallet',
            'qris'            => 'QRIS',
            'cash'            => 'Tunai',
            default           => '-',
        };
    }

    public function getPaymentFullLabelAttribute(): string
    {
        $type = match ($this->payment_method) {
            'manual_transfer' => 'Transfer Bank',
            'e_wallet'        => 'E-Wallet',
            'qris'            => 'QRIS',
            'cash'            => 'Tunai',
            default           => '-',
        };

        if (in_array($this->payment_method, ['manual_transfer', 'e_wallet']) && $this->payment_sub_method) {
            return "{$type} (" . strtoupper($this->payment_sub_method) . ")";
        }

        return $type;
    }
}