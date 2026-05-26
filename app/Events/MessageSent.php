<?php

namespace App\Events;

use App\Models\Chats\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public array $users;

    public ?int $topicId;

    public function __construct(Message $message, array $users, ?int $topicId = null)
    {
        // Eager-load what the UI needs
        $this->message = $message->load('user', 'attachments', 'reactions');
        $this->users = $users;
        $this->topicId = $topicId;
    }

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('conversations.'.$this->message->conversation_id),
            new PrivateChannel('chat.monitor'),
        ];

        foreach ($this->users as $id) {
            $channels[] = new PrivateChannel('users.'.$id);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'conversation_id' => $this->message->conversation_id,
                'topic_id' => $this->topicId,
                'type' => $this->message->type,
                'body' => $this->message->body,
                'created_at' => $this->message->created_at->toIso8601String(),
                'edited_at' => optional($this->message->edited_at)->toIso8601String(),
                'user' => [
                    'id' => $this->message->user->id,
                    'name' => $this->message->user->name,
                    'avatar' => $this->message->user->profile_photo_url ?? null,
                    'role' => $this->getUserRole(),
                ],
                'attachments' => $this->message->attachments->map(fn ($a) => [
                    'id' => $a->id,
                    'file_name' => $a->file_name,
                    'original_name' => $a->original_name ?? $a->file_name,
                    'file_path' => $a->path,
                    'path' => $a->path,
                    'mime' => $a->mime,
                    'size' => $a->size ?? null,
                    'url' => $a->url,
                    'download_url' => route('attachments.download', $a->id),
                ])->toArray(),
            ],
        ];
    }

    /**
     * Get the user's system role (moderator, candidate, employer)
     */
    private function getUserRole(): ?string
    {
        return $this->message->user->role;
    }
}
