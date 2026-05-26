<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobAlertPreference extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'keywords',
        'location',
        'category',
        'remote_type',
        'employment_type',
        'frequency',
        'email_enabled',
        'is_active',
        'last_sent_at',
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'is_active' => 'boolean',
        'last_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shouldSendToday(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();

        if ($this->frequency === 'daily') {
            return $this->last_sent_at === null || $this->last_sent_at->toDateString() !== $now->toDateString();
        }

        if ($this->frequency === 'weekly') {
            if ($this->last_sent_at === null) {
                return true;
            }
            $daysSinceLast = $this->last_sent_at->diffInDays($now);

            return $daysSinceLast >= 7;
        }

        return false;
    }
}
