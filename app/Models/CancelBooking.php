<?php

namespace App\Models;

use App\Traits\ScopesByTempatKos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CancelBooking extends Model
{
    use HasFactory, SoftDeletes, ScopesByTempatKos;

    protected $fillable = [
        'tempat_kos_id',
        'rent_id',
        'user_id',
        'bank_name',
        'account_number',
        'account_holder_name',
        'cancel_reason',
        'status',
        'refund_method',
        'refund_sub_method',
        'refund_account_number',
        'refund_amount',
        'refund_proof',
        'admin_notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    // Relasi
    public function tempatKos(): BelongsTo
    {
        return $this->belongsTo(TempatKos::class);
    }

    public function rent(): BelongsTo
    {
        return $this->belongsTo(Rent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scope
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Accessor
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-500/20 text-yellow-300 border-2 border-yellow-500/50',
            'approved' => 'bg-green-500/20 text-green-300 border-2 border-green-500/50',
            'rejected' => 'bg-red-500/20 text-red-300 border-2 border-red-500/50',
            default => 'bg-slate-500/20 text-slate-300 border-2 border-slate-500/50',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Approval',
            'approved' => 'Disetujui - Dana Dikembalikan',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }
}