<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Rent;
use App\Models\Billing;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Payment[] $payments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Billing[] $billings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Rent[] $rents
 *
 * @method bool hasActiveRoom()
 * @method \App\Models\Rent|null activeRent()
 * @method \App\Models\Billing|null currentBilling()
 */

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========================================
    // TAMBAHKAN KODE DI BAWAH INI (RELASI BARU)
    // ========================================
    
    /**
     * Relasi: User memiliki banyak rents (riwayat sewa)
     */
    public function rents(): HasMany
    {
        return $this->hasMany(Rent::class);
    }
    
    /**
     * Relasi: User memiliki banyak pembayaran
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    
    /**
     * Relasi: User memiliki banyak tagihan
     */
    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class);
    }
    
    /**
     * Helper: Cek apakah user sedang menyewa kamar
     */
    public function hasActiveRoom(): bool
    {
        return $this->rents()
            ->where('status', 'active')
            ->whereNull('end_date')
            ->exists();
    }
    
    /**
     * Helper: Ambil rent aktif user saat ini
     */
    public function activeRent(): ?Rent
    {
        return $this->rents()
            ->with('room')
            ->where('status', 'active')
            ->whereNull('end_date')
            ->first();
    }
    
    /**
     * Helper: Ambil tagihan bulan ini
     */
    public function currentBilling()
    {
        return $this->billings()
            ->where('billing_year', now()->year)
            ->where('billing_month', now()->month)
            ->first();
    }

    // Method lama tetap ada (hasVerifiedEmail, markEmailAsVerified, dll)
    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function getEmailForVerification()
    {
        return $this->email;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
}