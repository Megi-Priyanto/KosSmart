<?php
// app/Models/Billing.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Billing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rent_id',
        'user_id',
        'room_id',
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
        'paid_date' => 'date',
        'billing_year' => 'integer',
        'billing_month' => 'integer',
    ];

    // Relasi
    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    // Scopes
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'paid')
            ->whereDate('due_date', '<', now());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->where('billing_year', $year)
            ->where('billing_month', $month);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'overdue' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'Lunas',
            'pending' => 'Menunggu Konfirmasi',
            'overdue' => 'Terlambat',
            'unpaid' => 'Belum Dibayar',
            default => 'Unknown',
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'paid' && $this->due_date->isPast();
    }

    public function getDaysUntilDueAttribute(): int
    {
        return now()->diffInDays($this->due_date, false);
    }

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

    // Methods
    public function calculateTotal(): void
    {
        $this->subtotal = $this->rent_amount +
            $this->electricity_cost +
            $this->water_cost +
            $this->maintenance_cost +
            $this->other_costs;

        $this->total_amount = $this->subtotal - $this->discount;
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);
    }

    public function markAsPending(): void
    {
        $this->update(['status' => 'pending']);
    }

    public function checkOverdue(): void
    {
        if ($this->status !== 'paid' && $this->due_date->isPast()) {
            $this->update(['status' => 'overdue']);
        }
    }

    protected static function booted()
    {
        static::retrieved(function ($billing) {
            $billing->autoUpdateOverdueStatus();
        });
    }

    public function autoUpdateOverdueStatus()
    {
        // Jika due_date NULL -> jangan apa-apa
        if (empty($this->due_date)) {
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
