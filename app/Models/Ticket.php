<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'tempat_kos_id',
        'room_id',
        'user_id',
        'title',
        'description',
        'photo_path',
        'status',
    ];

    public function tempatKos()
    {
        return $this->belongsTo(TempatKos::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
