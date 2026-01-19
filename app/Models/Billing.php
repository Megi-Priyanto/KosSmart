<?php
// app/Models/Billing.php

namespace App\Models;

use App\Traits\ScopesByTempatKos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Billing extends Model
{
    use HasFactory, SoftDeletes, ScopesByTempatKos;

    protected $fillable = [
        'tempat_kos_id',
        'rent_id',
        'user_id',
        'room_id',
        'tipe',
        'jumlah',
        'keterangan',
        'billing_period',
        'billing_year',
        'billing_month',
        'rent_amount',
        'electricity_cost',
        'water_cost',
        'maintenance_cost',
        'other_costs',
        'other_costs_description',
        'subtotal',
        'discount',
        'total_amount',
        'due_date',
        'paid_date',
        'status',
        'admin_notes',
        'user_notes',
    ];

    protected $casts = [
        'rent_amount' => 'decimal:2',
        'electricity_cost' => 'decimal:2',
        'water_cost' => 'decimal:2',
        'maintenance_cost' => 'decimal:2',
        'other_costs' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'datetime',
        'billing_year' => 'integer',
        'billing_month' => 'integer',
    ];

    protected $appends = [
        'status_badge',
        'status_label',
        'is_overdue',
    ];

    /**
     * Relasi: Billing belongs to Tempat Kos
     */
    public function tempatKos(): BelongsTo
    {
        return $this->belongsTo(TempatKos::class);
    }

    /**
     * Relasi: Billing belongs to Rent
     */
    public function rent(): BelongsTo
    {
        return $this->belongsTo(Rent::class);
    }

    /**
     * Relasi: Billing belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Billing belongs to Room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi: Billing has many payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Accessor: Get dynamic status based on payment
     */
    public function getDynamicStatusAttribute()
    {
        // Jika billing sudah paid
        if ($this->attributes['status'] === 'paid') {
            return 'paid';
        }

        // Cek payment terbaru
        $latestPayment = $this->latestPayment;

        if ($latestPayment && $latestPayment->status === 'rejected') {
            return 'rejected';
        }

        if ($latestPayment && $latestPayment->status === 'pending') {
            return 'pending';
        }

        if ($latestPayment && $latestPayment->status === 'approved') {
            return 'paid';
        }

        if ($this->due_date && $this->due_date->isPast() && $this->attributes['status'] !== 'paid') {
            return 'overdue';
        }

        return $this->attributes['status'] ?? 'unpaid';
    }

    /**
     * Relasi: Latest payment
     */
    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    /**
     * Scope: Unpaid billings
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    /**
     * Scope: Overdue billings
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'paid')
            ->whereDate('due_date', '<', now());
    }

    /**
     * Scope: Pending billings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Paid billings
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope: For specific month
     */
    public function scopeForMonth($query, $year, $month)
    {
        return $query->where('billing_year', $year)
            ->where('billing_month', $month);
    }

    /**
     * Scope: For specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Accessor: Get status badge class (Dark UI Friendly)
     */
    public function getStatusBadgeAttribute(): string
    {
        // Gunakan dynamic_status bukan $this->status
        return match ($this->dynamic_status) {
            'paid' =>
            'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30',

            'pending' =>
            'bg-amber-500/15 text-amber-400 border border-amber-500/30',

            'rejected' =>  // ← TAMBAHKAN INI
            'bg-rose-500/15 text-rose-400 border border-rose-500/30',

            'overdue' =>
            'bg-red-500/15 text-red-400 border border-red-500/30',

            'unpaid' =>
            'bg-slate-500/15 text-slate-300 border border-slate-500/30',

            default =>
            'bg-slate-500/15 text-slate-300 border border-slate-500/30',
        };
    }

    /**
     * Accessor: Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        // Gunakan dynamic_status bukan $this->status
        return match ($this->dynamic_status) {
            'paid' => 'Lunas',
            'pending' => 'Menunggu Konfirmasi',
            'rejected' => 'Pembayaran Ditolak',
            'overdue' => 'Terlambat',
            'unpaid' => 'Belum Dibayar',
            default => 'Unknown',
        };
    }

    /**
     * Accessor: Check if overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'paid' && $this->due_date && $this->due_date->isPast();
    }

    /**
     * Accessor: Days until due
     */
    public function getDaysUntilDueAttribute(): int
    {
        return $this->due_date ? now()->diffInDays($this->due_date, false) : 0;
    }

    /**
     * Accessor: Formatted period
     */
    public function getFormattedPeriodAttribute(): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return $months[$this->billing_month] . ' ' . $this->billing_year;
    }

    /**
     * Helper: Calculate total
     */
    public function calculateTotal(): void
    {
        $this->subtotal = $this->rent_amount +
            $this->electricity_cost +
            $this->water_cost +
            $this->maintenance_cost +
            $this->other_costs;

        $this->total_amount = $this->subtotal - $this->discount;
    }

    /**
     * Helper: Mark as paid
     */
    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);
    }

    /**
     * Helper: Mark as pending
     */
    public function markAsPending(): void
    {
        $this->update(['status' => 'pending']);
    }

    /**
     * Boot method: Auto update overdue status
     */
    protected static function booted()
    {
        static::retrieved(function ($billing) {
            $billing->autoUpdateOverdueStatus();
        });
    }

    /**
     * Auto update status to overdue if past due date
     */
    public function autoUpdateOverdueStatus(): void
    {
        if (!$this->due_date) {
            return;
        }

        if (
            $this->status !== 'paid'
            && $this->due_date->isPast()
            && $this->status !== 'overdue'
        ) {
            $this->update(['status' => 'overdue']);
        }
    }
}
