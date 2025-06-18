<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FarmerRebuttalSubmitted extends Notification
{
    use Queueable;

    public $returnRequest;

    public function __construct($returnRequest)
    {
        $this->returnRequest = $returnRequest;
    }

    public function via($notifiable)
    {
        return ['database']; // Or add 'mail' if needed
    }

    public function toArray($notifiable)
    {
        return [
            'icon' => 'bi-shield-exclamation',
            'message' => 'Farmer submitted a rebuttal for Return Request #' . $this->returnRequest->id,
            'type' => 'rebuttal',
            'link' => route('admin.returns.show', $this->returnRequest->id),
        ];
    }
}
