<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSupportTicketReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public SupportTicket $ticket,
        public SupportTicketReply $reply
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
        $replierName = $this->reply->user->name ?? 'Support';
        $isStaff = $this->reply->is_staff;

        return (new MailMessage)
            ->subject(($isStaff ? 'Support replied' : 'New reply').' on your ticket: '.$this->ticket->subject)
            ->line($replierName.($isStaff ? ' (Support)' : '').' replied to your support ticket.')
            ->line('Subject: '.$this->ticket->subject)
            ->action('View ticket', route('tickets.show', $this->ticket))
            ->line('You can view and reply from your account.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_support_ticket_reply',
            'ticket_id' => $this->ticket->id,
            'reply_id' => $this->reply->id,
            'subject' => $this->ticket->subject,
            'is_staff' => $this->reply->is_staff,
            'from_user_name' => $this->reply->user->name ?? null,
            'message_preview' => \Illuminate\Support\Str::limit($this->reply->message, 80),
            'url' => route('tickets.show', $this->ticket),
        ];
    }
}
