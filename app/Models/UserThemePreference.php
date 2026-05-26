<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserThemePreference extends Model
{
    protected $fillable = [
        'user_id',
        'theme_mode',
        'theme_styles',
        'layout_settings',
    ];

    protected function casts(): array
    {
        return [
            'theme_styles' => 'array',
            'layout_settings' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
