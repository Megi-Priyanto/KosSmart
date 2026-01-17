<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Rent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method bool isSuperAdmin()
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use MustVerifyEmailTrait;
    use HasApiTokens, Notifiable;
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'tempat_kos_id',
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

    /**
     * Relasi: User belongs to Tempat Kos
     */
    public function tempatKos(): BelongsTo
    {
        return $this->belongsTo(TempatKos::class);
    }

    /**
     * Relasi: User memiliki banyak rents
     */
    public function rents(): HasMany
    {
        return $this->hasMany(Rent::class);
    }

    /**
     * Relasi: User memiliki banyak payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relasi: User memiliki banyak billings
     */
    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class);
    }

    /**
     * Helper: Cek role
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Helper: Cek apakah user punya kamar aktif
     */
    public function hasActiveRoom(): bool
    {
        return $this->rents()
            ->whereIn('status', ['active', 'checkout_requested'])
            ->whereNull('end_date')
            ->exists();
    }

    /**
     * Helper: Ambil rent aktif
     */
    public function activeRent(): ?Rent
    {
        return $this->rents()
            ->with('room')
            ->whereIn('status', ['active', 'checkout_requested'])
            ->whereNull('end_date')
            ->latest()
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

    /**
     * Scope: Filter by Tempat Kos
     */
    public function scopeByTempatKos($query, $tempatKosId)
    {
        return $query->where('tempat_kos_id', $tempatKosId);
    }

    /**
     * Scope: Super Admin only
     */
    public function scopeSuperAdmin($query)
    {
        return $query->where('role', 'super_admin');
    }

    /**
     * Scope: Admin only
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope: Regular users only
     */
    public function scopeRegularUser($query)
    {
        return $query->where('role', 'user');
    }

    // Method lama tetap ada
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
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
}
