<?php

namespace App\Events;

use App\Models\Chats\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessagePinned implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $conversationId;

    public ?int $messageId;

    public ?string $messageBody;

    public ?string $userName;

    public string $action;

    public function __construct(int $conversationId, ?Message $message = null, string $action = 'pinned')
    {
        $this->conversationId = $conversationId;
        $this->action = $action;

        if ($message) {
            $this->messageId = $message->id;
            $this->messageBody = $message->body;
            $this->userName = $message->user?->name ?? 'Unknown';
        } else {
            $this->messageId = null;
            $this->messageBody = null;
            $this->userName = null;
        }
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('conversations.'.$this->conversationId)];
    }

    public function broadcastAs(): string
    {
        return 'message.pinned';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'message_id' => $this->messageId,
            'message_body' => $this->messageBody,
            'user_name' => $this->userName,
            'action' => $this->action,
        ];
    }
}
