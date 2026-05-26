<?php

namespace App\Events;

use App\Models\Chats\Conversation;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallStarted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $conversationId;

    public int $callerId;

    public string $callerName;

    public string $callerAvatar;

    public string $roomName;

    public string $callType; // 'video' or 'audio'

    public function __construct(Conversation $conversation, User $caller, string $roomName, string $callType = 'video')
    {
        $this->conversationId = $conversation->id;
        $this->callerId = $caller->id;
        $this->callerName = $caller->name;
        $this->callerAvatar = $caller->profile_photo_url ?? 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($caller->name).'&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981';
        $this->roomName = $roomName;
        $this->callType = $callType;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('conversations.'.$this->conversationId)];
    }

    public function broadcastAs(): string
    {
        return 'call.started';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'caller_id' => $this->callerId,
            'caller_name' => $this->callerName,
            'caller_avatar' => $this->callerAvatar,
            'room_name' => $this->roomName,
            'call_type' => $this->callType,
        ];
    }
}
