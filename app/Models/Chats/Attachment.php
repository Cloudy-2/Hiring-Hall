<?php

namespace App\Models\Chats;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $table = 'chat_attachments';

    protected $fillable = [
        'message_id', 'user_id', 'disk', 'path', 'mime', 'size', 'width', 'height', 'meta', 'original_name',
    ];

    protected $casts = ['meta' => 'array'];

    protected $appends = ['url', 'file_name'];

    public function getUrlAttribute(): ?string
    {
        if (! $this->path) {
            return null;
        }

        return Storage::disk($this->disk ?? 'public')->url($this->path);
    }

    public function getFileNameAttribute(): ?string
    {
        return $this->original_name ?? basename($this->path);
    }

    public function message(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
