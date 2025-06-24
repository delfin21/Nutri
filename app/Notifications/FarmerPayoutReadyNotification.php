<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FarmerPayoutReadyNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Payout Ready',
            'message' => "Order {$this->order->order_code} has been completed. Your payout is now being processed.",
            'link' => route('farmer.orders.show', $this->order->id),
        ];
    }
}
