<?php

namespace App\Models;

use App\Traits\ScopesByTempatKos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Rent extends Model
{
    use HasFactory;
    use ScopesByTempatKos;

    protected $fillable = [
        'tempat_kos_id',
        'user_id',
        'room_id',
        'start_date',
        'end_date',
        'deposit_paid',
        'monthly_rent',
        'status',
        'notes',
        'payment_method',
        'payment_sub_method',
        'dp_payment_status',
        'dp_paid',
        'dp_verified_at',
        'dp_verified_by',
        'dp_rejection_reason',
        'admin_notes',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'date',
        'deposit_paid' => 'decimal:2',
        'monthly_rent' => 'decimal:2',
        'dp_paid' => 'boolean',
        'dp_verified_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Relasi: DP Verifier
     */
    public function dpVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dp_verified_by');
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
            default => $this->payment_method ?? 'N/A',
        };
    }

    /**
     * Accessor: Get payment sub method label
     */
    public function getPaymentSubMethodLabelAttribute(): string
    {
        if (!$this->payment_sub_method) return 'N/A';
        return strtoupper($this->payment_sub_method);
    }

    /**
     * Accessor: Get DP status badge
     */
    public function getDpStatusBadgeAttribute(): string
    {
        return match ($this->dp_payment_status) {
            'approved' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Accessor: Get DP status label
     */
    public function getDpStatusLabelAttribute(): string
    {
        return match ($this->dp_payment_status) {
            'approved' => 'Disetujui',
            'pending' => 'Menunggu Verifikasi',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }

    /**
     * Relasi: Rent belongs to Tempat Kos
     */
    public function tempatKos(): BelongsTo
    {
        return $this->belongsTo(TempatKos::class);
    }

    /**
     * Relasi: Rent belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Rent belongs to Room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi: Rent memiliki banyak billings
     */
    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class);
    }

    /**
     * Relasi: Rent memiliki banyak notifications
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Accessor: Get duration in human readable format
     */
    public function getDurationHumanAttribute(): string
    {
        $start = Carbon::parse($this->start_date);
        $now = now();

        $diff = $start->diff($now);

        $parts = [];

        if ($diff->y > 0) {
            $parts[] = $diff->y . ' tahun';
        }

        if ($diff->m > 0) {
            $parts[] = $diff->m . ' bulan';
        }

        if ($diff->d > 0) {
            $parts[] = $diff->d . ' hari';
        }

        if (empty($parts)) {
            if ($diff->h > 0) {
                $parts[] = $diff->h . ' jam';
            } elseif ($diff->i > 0) {
                $parts[] = $diff->i . ' menit';
            } else {
                return 'Baru mulai';
            }
        }

        return implode(' ', $parts);
    }

    /**
     * Accessor: Get status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'checkout_requested' => 'bg-orange-100 text-orange-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Accessor: Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Aktif',
            'pending' => 'Menunggu Approval',
            'checkout_requested' => 'Request Checkout',
            'completed' => 'Selesai',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }

    /**
     * Scope: Active rents
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Pending rents
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Checkout requested
     */
    public function scopeCheckoutRequested($query)
    {
        return $query->where('status', 'checkout_requested');
    }

    /**
     * Scope: Completed rents
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Tenant aktif
     * User masih dianggap menempati kamar
     */
    public function scopeTenant($query)
    {
        return $query->whereIn('status', [
            'active',
            'checkout_requested',
        ]);
    }

    /**
     * Helper: Approve rent
     */
    public function approve(): bool
    {
        return $this->update(['status' => 'active']);
    }

    /**
     * Helper: Reject rent
     */
    public function reject(string $reason = null): bool
    {
        return $this->update([
            'status' => 'rejected',
            'notes' => $reason ?? $this->notes,
        ]);
    }

    /**
     * Helper: Request checkout
     */
    public function requestCheckout(): bool
    {
        return $this->update(['status' => 'checkout_requested']);
    }

    /**
     * Helper: Complete checkout
     */
    public function completeCheckout(): bool
    {
        return $this->update([
            'status' => 'completed',
            'end_date' => now(),
        ]);
    }

    /**
     * Accessor: Durasi sewa akurat (jam → hari → bulan → tahun)
     */
    public function getDurationAccurateAttribute(): string
    {
        $start = Carbon::parse($this->start_date);
        $now   = now();

        // PAKSA INTEGER (BUKAN FLOAT)
        $totalHours = (int) $start->diffInHours($now);

        // < 24 jam → tampil JAM
        if ($totalHours < 24) {
            return $totalHours . ' jam';
        }

        // Hari
        $totalDays = intdiv($totalHours, 24);

        if ($totalDays < 30) {
            return $totalDays . ' hari';
        }

        // Bulan (30 hari)
        $months = intdiv($totalDays, 30);
        $days   = $totalDays % 30;

        if ($months < 12) {
            return $days > 0
                ? "{$months} bulan {$days} hari"
                : "{$months} bulan";
        }

        // Tahun
        $years  = intdiv($months, 12);
        $months = $months % 12;

        return $months > 0
            ? "{$years} tahun {$months} bulan"
            : "{$years} tahun";
    }

    public function cancelBooking(): HasOne
    {
        return $this->hasOne(CancelBooking::class);
    }

    public function hasPendingCancel(): bool
    {
        return $this->cancelBooking()->where('status', 'pending')->exists();
    }
}
