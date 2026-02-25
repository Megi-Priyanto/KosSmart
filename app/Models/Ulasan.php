<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    protected $fillable = [
        'user_id',
        'tempat_kos_id',
        'rent_id',
        'billing_id',
        'rating',
        'komentar',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'rating' => 'integer',
    ];

    // ─── Relationships ───────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tempatKos()
    {
        return $this->belongsTo(TempatKos::class, 'tempat_kos_id');
    }

    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    // ─── Scopes ──────────────────────────────────────────────
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}
