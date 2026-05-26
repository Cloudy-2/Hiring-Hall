<?php

namespace App\Models\Chats;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscussionTopic extends Model
{
    use SoftDeletes;

    protected $table = 'chat_discussion_topics';

    protected $fillable = [
        'conversation_id',
        'created_by',
        'slug',
        'name',
        'description',
        'position',
        'visibility',
        'slow_mode_seconds',
        'is_archived',
        'is_readonly',
        'is_starred',
        'allowed_roles',
    ];

    protected $casts = [
        'position' => 'integer',
        'slow_mode_seconds' => 'integer',
        'is_archived' => 'boolean',
        'is_readonly' => 'boolean',
        'is_starred' => 'boolean',
        'allowed_roles' => 'array',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subscriptions()
    {
        return $this->hasMany(TopicSubscription::class, 'topic_id');
    }

    public function messages()
    {
        return $this->belongsToMany(Message::class, 'chat_discussion_topic_messages', 'topic_id', 'message_id');
    }
}
