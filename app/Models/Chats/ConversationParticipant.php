<?php

namespace App\Models\Chats;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ConversationParticipant extends Model
{
    protected $table = 'chat_conversation_participants';

    protected $fillable = [
        'conversation_id', 'user_id', 'role', 'is_pinned', 'is_archived', 'is_trashed', 'is_muted', 'muted_until', 'can_send_messages', 'last_read_message_id', 'last_read_at', 'joined_at', 'left_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_archived' => 'boolean',
        'is_trashed' => 'boolean',
        'is_muted' => 'boolean',
        'can_send_messages' => 'boolean',
        'muted_until' => 'datetime',
        'last_read_at' => 'datetime',
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
    ];

    public function conversation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if participant is an admin or owner
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['owner', 'admin']);
    }

    /**
     * Check if participant is the owner
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Get effective role considering system moderator status
     * System moderators are treated as admins in all groups
     */
    public function getEffectiveRoleAttribute(): string
    {
        $user = $this->user;
        if ($user && $user->isModerator()) {
            return 'admin'; // Moderators are silent admins
        }

        return $this->role;
    }

    /**
     * Check if user has admin privileges (including system moderators)
     */
    public function hasAdminPrivileges(): bool
    {
        // Check if system moderator
        $user = $this->user;
        if ($user && $user->isModerator()) {
            return true;
        }

        return $this->isAdmin();
    }
}
