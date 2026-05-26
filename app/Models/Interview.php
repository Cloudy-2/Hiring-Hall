<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interview extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const TYPE_PHONE = 'phone';

    public const TYPE_VIDEO = 'video';

    public const TYPE_IN_PERSON = 'in_person';

    public const TYPES = [
        self::TYPE_PHONE => 'Phone Call',
        self::TYPE_VIDEO => 'Video Call',
        self::TYPE_IN_PERSON => 'In Person',
    ];

    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_RESCHEDULED = 'rescheduled';

    public const STATUS_NO_SHOW = 'no_show';

    public const STATUSES = [
        self::STATUS_SCHEDULED => 'Scheduled',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_RESCHEDULED => 'Rescheduled',
        self::STATUS_NO_SHOW => 'No Show',
    ];

    protected $fillable = [
        'job_application_id',
        'employer_id',
        'applicant_id',
        'title',
        'description',
        'interview_type',
        'scheduled_at',
        'duration_minutes',
        'location',
        'meeting_link',
        'status',
        'notes',
        'feedback',
        'rating',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'duration_minutes' => 'integer',
        'rating' => 'integer',
    ];

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function scopeForEmployer($query, int $employerId)
    {
        return $query->where('employer_id', $employerId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now())
            ->where('status', self::STATUS_SCHEDULED);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function getTypeLabel(): string
    {
        return self::TYPES[$this->interview_type] ?? 'Unknown';
    }

    public function getStatusLabel(): string
    {
        return self::STATUSES[$this->status] ?? 'Unknown';
    }

    public function getEndTime()
    {
        return $this->scheduled_at->copy()->addMinutes($this->duration_minutes);
    }

    public function isUpcoming(): bool
    {
        return $this->scheduled_at->isFuture() && $this->status === self::STATUS_SCHEDULED;
    }

    public function isPast(): bool
    {
        return $this->scheduled_at->isPast();
    }
}
