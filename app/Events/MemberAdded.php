<?php

// MemberAdded Event - Broadcasts when a moderator adds member(s) to a conversation

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $conversationId;

    public array $addedUsers;

    public string $addedByName;

    public function __construct(int $conversationId, array $addedUsers, string $addedByName)
    {
        $this->conversationId = $conversationId;
        $this->addedUsers = $addedUsers;
        $this->addedByName = $addedByName;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('conversations.'.$this->conversationId)];
    }

    public function broadcastAs(): string
    {
        return 'member.added';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'added_users' => $this->addedUsers,
            'added_by_name' => $this->addedByName,
        ];
    }
}
