<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalTagMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_tag_id',
        'user_id',
        'body',
        'forwarded_from_message_id',
        'forwarded_metadata',
    ];

    protected $casts = [
        'forwarded_metadata' => 'array',
    ];

    public function tag(): BelongsTo
    {
        return $this->belongsTo(PersonalTag::class, 'personal_tag_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
