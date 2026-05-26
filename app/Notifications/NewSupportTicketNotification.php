<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSupportTicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public SupportTicket $ticket
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $fromName = $this->ticket->user->name ?? 'A user';

        return (new MailMessage)
            ->subject('New support ticket: '.$this->ticket->subject)
            ->line($fromName.' submitted a new support ticket.')
            ->line('Subject: '.$this->ticket->subject)
            ->action('View ticket', route('moderator.tickets.show', $this->ticket))
            ->line('Please log in to respond.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_support_ticket',
            'ticket_id' => $this->ticket->id,
            'subject' => $this->ticket->subject,
            'from_user_id' => $this->ticket->user_id,
            'from_user_name' => $this->ticket->user->name ?? null,
            'url' => route('moderator.tickets.show', $this->ticket),
        ];
    }
}
