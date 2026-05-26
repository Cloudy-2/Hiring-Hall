<?php

namespace App\Events;

use App\Models\Chats\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $messageId;

    public int $conversationId;

    public int $deletedByUserId;

    public string $deletedByUserName;

    public bool $deletedByModerator;

    public function __construct(Message $message, bool $byModerator = false)
    {
        $this->messageId = $message->id;
        $this->conversationId = $message->conversation_id;
        $this->deletedByUserId = $message->user_id;
        $this->deletedByUserName = $message->user?->name ?? 'Unknown';
        $this->deletedByModerator = $byModerator;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversations.'.$this->conversationId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.deleted';
    }

    public function broadcastWith(): array
    {
        return [
            'message_id' => $this->messageId,
            'conversation_id' => $this->conversationId,
            'deleted_by_user_id' => $this->deletedByUserId,
            'deleted_by_user_name' => $this->deletedByUserName,
            'deleted_by_moderator' => $this->deletedByModerator,
        ];
    }
}
