<?php

namespace App\Events;

use App\Models\Chats\Conversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRemovedFromGroup implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;

    public int $conversationId;

    public string $conversationName;

    public function __construct(int $userId, Conversation $conversation)
    {
        $this->userId = $userId;
        $this->conversationId = $conversation->id;
        $this->conversationName = $conversation->name ?? 'Group';
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users.'.$this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'removed.from.group';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'conversation_name' => $this->conversationName,
        ];
    }
}
