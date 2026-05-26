<?php

namespace App\Events;

use App\Models\Chats\Conversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAddedToGroup implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;

    public int $conversationId;

    public string $conversationName;

    public ?string $conversationPhoto;

    public function __construct(int $userId, Conversation $conversation)
    {
        $this->userId = $userId;
        $this->conversationId = $conversation->id;
        $this->conversationName = $conversation->name ?? 'Group';
        $this->conversationPhoto = $conversation->photo;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users.'.$this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'added.to.group';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'conversation_name' => $this->conversationName,
            'conversation_photo' => $this->conversationPhoto,
        ];
    }
}
