<?php

namespace App\Models\Chats;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MessageReaction extends Model
{
    protected $table = 'chat_message_reactions';

    protected $fillable = ['message_id', 'user_id', 'reaction'];

    public $timestamps = true;

    public function message(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
