<?php

namespace App\Models\Chats;

use App\Models\User;
use App\Traits\HasEncryptedRouteKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasEncryptedRouteKey, HasFactory, SoftDeletes;

    protected $table = 'chat_messages';

    protected $fillable = [
        'conversation_id', 'user_id', 'type', 'body', 'reply_to_message_id', 'meta', 'edited_at',
        'forwarded_from_message_id', 'forwarded_metadata',
        'deleted_by_moderator', 'deleted_by_user', 'original_body',
    ];

    protected $casts = [
        'meta' => 'array',
        'forwarded_metadata' => 'array',
        'edited_at' => 'datetime',
    ];

    public function conversation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): Message|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function reactions(): Message|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MessageReaction::class);
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to_message_id');
    }
}
