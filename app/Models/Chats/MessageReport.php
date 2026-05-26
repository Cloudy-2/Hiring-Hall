<?php

namespace App\Models\Chats;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class MessageReport extends Model
{
    protected $table = 'chat_message_reports';

    protected $fillable = [
        'message_id',
        'reporter_id',
        'conversation_id',
        'reason',
        'context',
        'status',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'context' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
