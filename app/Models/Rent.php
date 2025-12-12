<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Room;
use App\Models\Billing;


class Rent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'start_date',
        'end_date',
        'deposit_paid',
        'monthly_rent',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'deposit_paid' => 'decimal:2',
        'monthly_rent' => 'decimal:2',
    ];

    /**
     * Relasi: Rent belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Rent belongs to Room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi: Rent memiliki banyak billings
     */
    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
