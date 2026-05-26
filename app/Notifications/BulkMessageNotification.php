<?php

namespace App\Notifications;

use App\Models\BulkNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BulkMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $subject,
        public string $message,
        public string $notificationType = 'both'
    ) {}

    public function via(object $notifiable): array
    {
        $channels = [];

        if (in_array($this->notificationType, [BulkNotification::TYPE_DATABASE, BulkNotification::TYPE_BOTH])) {
            $channels[] = 'database';
        }

        if (in_array($this->notificationType, [BulkNotification::TYPE_EMAIL, BulkNotification::TYPE_BOTH])) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->line($this->message)
            ->line('Thank you for using our platform!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->subject,
            'subject' => $this->subject,
            'message' => $this->message,
            'type' => 'bulk_notification',
            'url' => route('notifications.index'),
        ];
    }
}
