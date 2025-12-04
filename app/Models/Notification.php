<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['title', 'notification_date', 'status'];

    public function items()
    {
        return $this->hasMany(NotificationItem::class);
    }
}

