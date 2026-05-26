<?php

namespace App\Events;

use App\Models\Chats\Message;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReacted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $messageId;

    public int $conversationId;

    public int $userId;

    public string $userName;

    public string $reaction;

    public string $action; // 'added' or 'removed'

    public function __construct(Message $message, User $user, string $reaction, string $action = 'added')
    {
        $this->messageId = $message->id;
        $this->conversationId = $message->conversation_id;
        $this->userId = $user->id;
        $this->userName = $user->name;
        $this->reaction = $reaction;
        $this->action = $action;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('conversations.'.$this->conversationId)];
    }

    public function broadcastAs(): string
    {
        return 'message.reacted';
    }

    public function broadcastWith(): array
    {
        return [
            'message_id' => $this->messageId,
            'conversation_id' => $this->conversationId,
            'user_id' => $this->userId,
            'user_name' => $this->userName,
            'reaction' => $this->reaction,
            'action' => $this->action,
        ];
    }
}
