<?php

namespace App\Events;

use App\Models\Chats\Conversation;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationTyping implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Conversation $conversation;

    public int $userId;

    public string $userName;

    public ?string $avatarUrl;

    public function __construct(Conversation $conversation, User $user)
    {
        // Ensure participants are eager-loaded only as needed
        $this->conversation = $conversation;
        $this->userId = $user->id;
        $this->userName = $user->name;
        $this->avatarUrl = method_exists($user, 'getAttribute')
            ? ($user->profile_photo_url ?? null)
            : null;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('conversations.'.$this->conversation->id)];
    }

    public function broadcastAs(): string
    {
        return 'conversation.typing';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversation->id,
            'user_id' => $this->userId,
            'name' => $this->userName,
            'avatar' => $this->avatarUrl,
        ];
    }
}
