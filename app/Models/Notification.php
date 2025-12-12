<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'user_id',
        'rent_id',
        'room_id',
        'due_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
