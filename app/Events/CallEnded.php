<?php

namespace App\Events;

use App\Models\Chats\Conversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallEnded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $conversationId;

    public string $roomName;

    public ?int $duration;

    public string $durationText;

    public ?int $endedByUserId;

    public function __construct(Conversation $conversation, string $roomName, ?int $duration = null, string $durationText = '', ?int $endedByUserId = null)
    {
        $this->conversationId = $conversation->id;
        $this->roomName = $roomName;
        $this->duration = $duration;
        $this->durationText = $durationText;
        $this->endedByUserId = $endedByUserId;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('conversations.'.$this->conversationId)];
    }

    public function broadcastAs(): string
    {
        return 'call.ended';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'room_name' => $this->roomName,
            'duration' => $this->duration,
            'duration_text' => $this->durationText,
            'ended_by_user_id' => $this->endedByUserId,
        ];
    }
}
