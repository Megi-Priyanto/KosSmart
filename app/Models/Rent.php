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

    // =========================================================
    // RELASI
    // =========================================================

    public function dpVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dp_verified_by');
    }

    public function tempatKos(): BelongsTo
    {
        return $this->belongsTo(TempatKos::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Relasi: satu cancel booking (HasOne — untuk backward compat)
     */
    public function cancelBooking(): HasOne
    {
        return $this->hasOne(CancelBooking::class);
    }

    /**
     * Relasi: semua cancel bookings (HasMany)
     */
    public function cancelBookings(): HasMany
    {
        return $this->hasMany(CancelBooking::class, 'rent_id');
    }

    /**
     * Cancel booking yang masih aktif (pending / admin_approved)
     */
    public function activeCancelBooking(): HasOne
    {
        return $this->hasOne(CancelBooking::class, 'rent_id')
            ->whereIn('status', ['pending', 'admin_approved'])
            ->latestOfMany();
    }

    // =========================================================
    // HELPERS
    // =========================================================

    /**
     * Cek apakah ada cancel booking yang masih pending
     */
    public function hasPendingCancel(): bool
    {
        return $this->cancelBookings()->where('status', 'pending')->exists();
    }

    /**
     * Cek apakah ada cancel booking yang aktif (pending atau admin_approved)
     */
    public function hasActiveCancel(): bool
    {
        return $this->cancelBookings()
            ->whereIn('status', ['pending', 'admin_approved'])
            ->exists();
    }

    /**
     * Ambil cancel booking aktif (pending / admin_approved)
     */
    public function getActiveCancelBooking(): ?CancelBooking
    {
        return $this->cancelBookings()
            ->whereIn('status', ['pending', 'admin_approved'])
            ->latest()
            ->first();
    }

    /**
     * Ambil cancel booking yang sudah selesai (approved)
     */
    public function getApprovedCancelBooking(): ?CancelBooking
    {
        return $this->cancelBookings()
            ->where('status', 'approved')
            ->latest()
            ->first();
    }

    public function approve(): bool
    {
        return $this->update(['status' => 'active']);
    }

    public function reject(string $reason = null): bool
    {
        return $this->update([
            'status' => 'rejected',
            'notes' => $reason ?? $this->notes,
        ]);
    }

    public function requestCheckout(): bool
    {
        return $this->update(['status' => 'checkout_requested']);
    }

    public function completeCheckout(): bool
    {
        return $this->update([
            'status' => 'completed',
            'end_date' => now(),
        ]);
    }

    // =========================================================
    // ACCESSORS
    // =========================================================

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'manual_transfer' => 'Transfer Bank',
            'e_wallet'        => 'E-Wallet',
            'qris'            => 'QRIS',
            default           => $this->payment_method ?? 'N/A',
        };
    }

    public function getPaymentSubMethodLabelAttribute(): string
    {
        if (!$this->payment_sub_method) return 'N/A';
        return strtoupper($this->payment_sub_method);
    }

    public function getDpStatusBadgeAttribute(): string
    {
        return match ($this->dp_payment_status) {
            'approved' => 'bg-green-100 text-green-800',
            'pending'  => 'bg-yellow-100 text-yellow-800',
            'rejected' => 'bg-red-100 text-red-800',
            default    => 'bg-gray-100 text-gray-800',
        };
    }

    public function getDpStatusLabelAttribute(): string
    {
        return match ($this->dp_payment_status) {
            'approved' => 'Disetujui',
            'pending'  => 'Menunggu Verifikasi',
            'rejected' => 'Ditolak',
            default    => 'Unknown',
        };
    }

    /**
     * Accessor: dp_payment_status_badge (alias untuk view yang pakai nama ini)
     */
    public function getDpPaymentStatusBadgeAttribute(): string
    {
        return $this->getDpStatusBadgeAttribute();
    }

    /**
     * Accessor: dp_payment_status_label (alias untuk view yang pakai nama ini)
     */
    public function getDpPaymentStatusLabelAttribute(): string
    {
        return $this->getDpStatusLabelAttribute();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'active'             => 'bg-green-100 text-green-800',
            'pending'            => 'bg-yellow-100 text-yellow-800',
            'checkout_requested' => 'bg-orange-100 text-orange-800',
            'completed'          => 'bg-blue-100 text-blue-800',
            'cancelled'          => 'bg-red-100 text-red-800',
            'rejected'           => 'bg-red-100 text-red-800',
            default              => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active'             => 'Aktif',
            'pending'            => 'Menunggu Approval',
            'checkout_requested' => 'Request Checkout',
            'completed'          => 'Selesai',
            'cancelled'          => 'Dibatalkan',
            'rejected'           => 'Ditolak',
            default              => 'Unknown',
        };
    }

    public function getDurationHumanAttribute(): string
    {
        $start = Carbon::parse($this->start_date);
        $now   = now();
        $diff  = $start->diff($now);
        $parts = [];

        if ($diff->y > 0) $parts[] = $diff->y . ' tahun';
        if ($diff->m > 0) $parts[] = $diff->m . ' bulan';
        if ($diff->d > 0) $parts[] = $diff->d . ' hari';

        if (empty($parts)) {
            if ($diff->h > 0)      $parts[] = $diff->h . ' jam';
            elseif ($diff->i > 0)  $parts[] = $diff->i . ' menit';
            else                   return 'Baru mulai';
        }

        return implode(' ', $parts);
    }

    public function getDurationAccurateAttribute(): string
    {
        $start      = Carbon::parse($this->start_date);
        $now        = now();
        $totalHours = (int) $start->diffInHours($now);

        if ($totalHours < 24) return $totalHours . ' jam';

        $totalDays = intdiv($totalHours, 24);
        if ($totalDays < 30) return $totalDays . ' hari';

        $months = intdiv($totalDays, 30);
        $days   = $totalDays % 30;

        if ($months < 12) {
            return $days > 0 ? "{$months} bulan {$days} hari" : "{$months} bulan";
        }

        $years  = intdiv($months, 12);
        $months = $months % 12;

        return $months > 0 ? "{$years} tahun {$months} bulan" : "{$years} tahun";
    }

    // =========================================================
    // SCOPES
    // =========================================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCheckoutRequested($query)
    {
        return $query->where('status', 'checkout_requested');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeTenant($query)
    {
        return $query->whereIn('status', ['active', 'checkout_requested']);
    }
}