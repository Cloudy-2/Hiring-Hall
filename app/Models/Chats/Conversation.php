<?php

namespace App\Models\Chats;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Conversation extends Model
{
    protected $table = 'chat_conversation';

    protected $fillable = ['type', 'name', 'photo', 'created_by', 'is_public', 'settings', 'meet', 'joined', 'meet_by', 'is_group'];

    protected $casts = [
        'settings' => 'array',
        'is_public' => 'boolean',
    ];

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany|Conversation
    {
        return $this->hasMany(Message::class);
    }

    public function pins(): \Illuminate\Database\Eloquent\Relations\HasMany|Conversation
    {
        return $this->hasMany(MessagePin::class);
    }

    public function topics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DiscussionTopic::class)->orderBy('position');
    }

    // 🔗 Conversation →(has many)→ Message →(has many)→ Attachment
    public function attachments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Attachment::class, // final model
            Message::class, // through model
            'conversation_id', // FK on Message → Conversation
            'message_id', // FK on Attachment → Message
            'id', // local key on Conversation
            'id', // local key on Message
        );
    }

    // Convenience filters (optional)
    public function imageAttachments(): HasManyThrough
    {
        return $this->attachments()->where('mime', 'like', 'image/%');
    }

    public function fileAttachments(): HasManyThrough
    {
        return $this->attachments()->where('mime', 'not like', 'image/%');
    }

    // Scope for conversations visible to a given user (not left/removed)
    public function scopeForUser($query, int $userId)
    {
        return $query->whereHas('participants', function ($q) use ($userId) {
            $q->where('user_id', $userId)->whereNull('left_at');
        });
    }

    public function participants()
    {
        return $this->hasMany(ConversationParticipant::class, 'conversation_id');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany('id');
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'chat_conversation_participants', // your actual pivot table
            'conversation_id',
            'user_id',
        )->withPivot(['role', 'is_pinned', 'is_archived', 'is_trashed', 'is_muted', 'last_read_message_id', 'last_read_at', 'joined_at', 'left_at']);
    }
}
