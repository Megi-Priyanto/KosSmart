<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationItem extends Model
{
    protected $fillable = [
        'notification_id',
        'rent_id',
        'user_id',
        'room_id',
        'due_date',
        'status',
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

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
}
