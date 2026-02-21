<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disbursement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tempat_kos_id',
        'admin_id',
        'gross_amount',   // total dari payment user (sebelum dipotong)
        'fee_percent',    // % potongan superadmin
        'fee_amount',     // nominal potongan = gross * fee_percent / 100
        'total_amount',   // yang diterima admin = gross - fee
        'payment_count',
        'period_from',
        'period_to',
        'description',
        'status',
        'transfer_method',
        'transfer_account',
        'transfer_proof',
        'processed_at',
        'processed_by',
    ];

    protected $casts = [
        'gross_amount'  => 'decimal:2',
        'fee_percent'   => 'decimal:2',
        'fee_amount'    => 'decimal:2',
        'total_amount'  => 'decimal:2',
        'payment_count' => 'integer',
        'processed_at'  => 'datetime',
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

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
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

    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    /*
    |--------------------------------------------------------------------------
    | STATIC HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Hitung rincian fee dari gross amount dan fee percent.
     *
     * @return array ['gross_amount', 'fee_percent', 'fee_amount', 'admin_amount']
     */
    public static function calculateFee(float $grossAmount, float $feePercent): array
    {
        $feeAmount   = round($grossAmount * $feePercent / 100);
        $adminAmount = $grossAmount - $feeAmount;

        return [
            'gross_amount' => $grossAmount,
            'fee_percent'  => $feePercent,
            'fee_amount'   => $feeAmount,
            'admin_amount' => $adminAmount,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu Pencairan',
            'processed' => 'Sudah Dicairkan',
            default     => 'Unknown',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'bg-amber-500/15 text-amber-400 border border-amber-500/30',
            'processed' => 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30',
            default     => 'bg-slate-500/15 text-slate-300 border border-slate-500/30',
        };
    }
}
