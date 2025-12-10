<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Notification extends Model
{
    protected $fillable = [
        'title',
        'notification_date',
        'status',
        'user_id',
    ];

    public function items()
    {
        return $this->hasMany(NotificationItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
