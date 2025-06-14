<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected array $details;

    public function __construct(array $details)
    {
        $this->details = $details;

        // ✅ Auto-fill sender_id and senderName
        $this->details['sender_id'] = auth()->id(); // 👈 this is MISSING in your crash
        $this->details['senderName'] = auth()->user()->name ?? 'NutriApp';
    }

    public function via($notifiable)
    {
        return ['database'];
    }

public function toArray($notifiable)
{
    return [
        'message' => "<i class='bi bi-chat-dots'></i> New message from <strong>{$this->details['sender']}</strong>",
        'type' => 'message',
        'link' => '/buyer/messages', // ✅ avoid missing conversation route
        'senderName' => $this->details['senderName'],
    ];
}
}
