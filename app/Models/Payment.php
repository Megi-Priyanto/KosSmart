<?php
// app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'billing_id',
        'amount',
        'payment_method',
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
    
    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
    
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
    
    // Scopes
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
    
    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
    
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'Dikonfirmasi',
            'pending' => 'Menunggu Verifikasi',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }
    
    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'transfer' => 'Transfer Bank',
            'cash' => 'Tunai',
            'e-wallet' => 'E-Wallet',
            default => $this->payment_method,
        };
    }
}