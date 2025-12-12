<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BillingOverdueNotification extends Notification
{
    use Queueable;

    public $billing;

    public function __construct($billing)
    {
        $this->billing = $billing;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Tagihan Jatuh Tempo',
            'message' => 'Tagihan kamar ' . $this->billing->room->room_number . ' telah melewati jatuh tempo.',
            'billing_id' => $this->billing->id,
        ];
    }
}
