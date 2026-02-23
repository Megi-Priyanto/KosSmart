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
        'admin_approval_notes',
        'status',
        'refund_method',
        'refund_sub_method',
        'refund_account_number',
        'refund_amount',
        'refund_proof',
        'admin_notes',
        'processed_by',
        'processed_at',
        'approved_by_admin',
        'admin_approved_at',
    ];

    protected $casts = [
        'refund_amount'      => 'decimal:2',
        'processed_at'       => 'datetime',
        'admin_approved_at'  => 'datetime',
    ];

    // ==================== RELASI ====================

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

    public function approvedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_admin');
    }

    // ==================== SCOPES ====================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAdminApproved($query)
    {
        return $query->where('status', 'admin_approved');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // ==================== ACCESSORS ====================

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'        => 'bg-yellow-500/20 text-yellow-300 border-2 border-yellow-500/50',
            'admin_approved' => 'bg-blue-500/20 text-blue-300 border-2 border-blue-500/50',
            'approved'       => 'bg-green-500/20 text-green-300 border-2 border-green-500/50',
            // PERBAIKAN: Status 'rejected' dihapus dari badge karena admin tidak bisa menolak
            default          => 'bg-slate-500/20 text-slate-300 border-2 border-slate-500/50',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'        => 'Menunggu Persetujuan Admin',
            'admin_approved' => 'Disetujui - Menunggu Refund Superadmin',
            'approved'       => 'Selesai - Dana Dikembalikan',
            // PERBAIKAN: Label 'rejected' dihapus
            default          => 'Unknown',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'pending'        => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'admin_approved' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
            'approved'       => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            // PERBAIKAN: Icon 'rejected' dihapus
            default          => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        };
    }
}