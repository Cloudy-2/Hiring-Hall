<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BulkNotification extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_SENDING = 'sending';

    public const STATUS_SENT = 'sent';

    public const STATUS_FAILED = 'failed';

    public const TYPE_EMAIL = 'email';

    public const TYPE_DATABASE = 'database';

    public const TYPE_BOTH = 'both';

    protected $fillable = [
        'subject',
        'message',
        'notification_type',
        'target_roles',
        'status',
        'sent_at',
        'sent_by',
        'recipient_count',
        'sent_count',
        'failed_count',
    ];

    protected $casts = [
        'target_roles' => 'array',
        'sent_at' => 'datetime',
        'recipient_count' => 'integer',
        'sent_count' => 'integer',
        'failed_count' => 'integer',
    ];

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeSent($query)
    {
        return $query->where('status', self::STATUS_SENT);
    }

    public function scopeSending($query)
    {
        return $query->where('status', self::STATUS_SENDING);
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isSending(): bool
    {
        return $this->status === self::STATUS_SENDING;
    }

    public function isSent(): bool
    {
        return $this->status === self::STATUS_SENT;
    }

    public function getTargetUsersQuery()
    {
        $query = User::query();

        if ($this->target_roles && ! in_array('all', $this->target_roles)) {
            $query->whereIn('role', $this->target_roles);
        }

        return $query;
    }
}
