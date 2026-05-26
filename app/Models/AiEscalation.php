<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiEscalation extends Model
{
    protected $fillable = [
        'user_id',
        'agent',
        'name',
        'email',
        'description',
        'conversation_json',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'conversation_json' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
