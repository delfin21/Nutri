<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReturnRequestResolved extends Notification
{
    use Queueable;

    public $request;
    public $result;

    public function __construct($request, $result)
    {
        $this->request = $request;
        $this->result = $result;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => $this->result === 'approved' ? 'bi-check-circle' : 'bi-x-circle',
            'message' => "Your return request for Order {$this->request->order->order_code} has been {$this->result}.",
            'type' => 'return-resolution',
            'link' => route('buyer.orders.history', ['status' => 'Return/Refund']),
        ];
    }
}
