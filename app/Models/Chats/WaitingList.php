<?php

namespace App\Models\Chats;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class WaitingList extends Model
{
    protected $table = 'chat_waiting_list';

    protected $fillable = [
        'user_id',
        'assigned_by',
        'conversation_id',
        'status',
        'notes',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    /**
     * Add a user to the waiting list
     */
    public static function addToWaitingList(int $userId): self
    {
        return self::updateOrCreate(
            ['user_id' => $userId, 'status' => 'waiting'],
            ['created_at' => now()]
        );
    }

    /**
     * Assign user to a conversation
     */
    public function assignToConversation(int $conversationId, int $assignedById): bool
    {
        $this->update([
            'conversation_id' => $conversationId,
            'assigned_by' => $assignedById,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        // Add user as participant
        ConversationParticipant::updateOrCreate(
            ['conversation_id' => $conversationId, 'user_id' => $this->user_id],
            ['joined_at' => now(), 'left_at' => null, 'role' => 'member']
        );

        return true;
    }
}
