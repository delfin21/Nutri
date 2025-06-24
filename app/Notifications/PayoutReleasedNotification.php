<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class PayoutReleasedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    /**
     * Delivery channels: in-app notification + (optional) email
     */
    public function via($notifiable): array
    {
        return ['database'];
        // return ['database', 'mail']; // Uncomment if you want email too
    }

    /**
     * Optional: Email format (not used unless 'mail' is enabled)
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Payout Has Been Released')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your payout for recent orders has been verified and released by the admin.')
            ->line('Payout Method: ' . ($this->payment->method ?? 'N/A'))
            ->line('Amount: ₱' . number_format($this->payment->amount, 2))
            ->action('View My Payouts', route('farmer.payouts.index'))
            ->line('Thank you for using NutriApp!');
    }

    /**
     * For database notification payload
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Payout Released',
            'message' => 'Your payout of ₱' . number_format($this->payment->amount, 2) . ' has been released by admin.',
            'link' => route('farmer.payouts.index'),
        ];
    }
}
