<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReleaseNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'version',
        'title',
        'body',
        'released_at',
        'is_published',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'released_at' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
