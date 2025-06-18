<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\ReturnRequest;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ReturnRequestFiled extends Notification
{
    use Queueable;

    protected $returnRequest;

    public function __construct(ReturnRequest $returnRequest)
    {
        $this->returnRequest = $returnRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $order = $this->returnRequest->order;

        return [
            'title' => 'Return Request Submitted',
            'message' => "A return request was filed for Order #{$order->order_code} by {$order->buyer->name}.",
            'link' => route('farmer.orders.show', $order->id),
            'icon' => 'bi-arrow-counterclockwise',
            'type' => 'warning',
        ];
    }
}
