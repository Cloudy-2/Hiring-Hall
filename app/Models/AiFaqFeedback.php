<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiFaqFeedback extends Model
{
    protected $table = 'ai_faq_feedbacks';

    protected $fillable = [
        'user_id',
        'conversation_uuid',
        'rating_stars',
        'sentiment',
        'context',
        'comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
