<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserMentioned implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;

    public int $conversationId;

    public int $unreadCount;

    public string $conversationName;

    public ?string $topicSlug;

    public function __construct(int $userId, int $conversationId, int $unreadCount, string $conversationName, ?string $topicSlug = null)
    {
        $this->userId = $userId;
        $this->conversationId = $conversationId;
        $this->unreadCount = $unreadCount;
        $this->conversationName = $conversationName;
        $this->topicSlug = $topicSlug;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users.'.$this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.mentioned';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'conversation_name' => $this->conversationName,
            'unread_count' => $this->unreadCount,
            'topic_slug' => $this->topicSlug ?? 'general',
        ];
    }
}
