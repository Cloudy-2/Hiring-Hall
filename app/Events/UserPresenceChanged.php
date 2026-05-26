<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserPresenceChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;

    public string $userName;

    public string $status;

    public ?string $avatar;

    public ?int $conversationId;

    public ?string $topicSlug;

    public function __construct(
        int $userId,
        string $userName,
        string $status,
        ?string $avatar = null,
        ?int $conversationId = null,
        ?string $topicSlug = null
    ) {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->status = $status;
        $this->avatar = $avatar;
        $this->conversationId = $conversationId;
        $this->topicSlug = $topicSlug;
    }

    public function broadcastOn(): array
    {
        // Broadcast to a global presence channel that all chat users can listen to
        return [new PresenceChannel('chat.presence')];
    }

    public function broadcastAs(): string
    {
        return 'presence.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->userId,
            'user_name' => $this->userName,
            'status' => $this->status,
            'avatar' => $this->avatar,
            'conversation_id' => $this->conversationId,
            'topic_slug' => $this->topicSlug,
        ];
    }
}
