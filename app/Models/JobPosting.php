<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JobPosting extends Model
{
    use HasFactory, SoftDeletes;

    const MODERATION_PENDING = 'pending';

    const MODERATION_APPROVED = 'approved';

    const MODERATION_REJECTED = 'rejected';

    protected $fillable = [
        'company_id',
        'job_template_id',
        'title',
        'slug',
        'location',
        'category',
        'industry_type',
        'recruiter_type',
        'employment_type',
        'remote_type',
        'vacancies',
        'status',
        'salary_min',
        'salary_max',
        'salary_currency',
        'experience_min_years',
        'experience_max_years',
        'summary',
        'description',
        'requirements',
        'responsibilities',
        'highlight_work_setup',
        'highlight_shift_schedule',
        'highlight_monthly_rate',
        'highlight_benefits',
        'posted_at',
        'closes_at',
        'moderation_status',
        'moderated_by',
        'moderated_at',
        'moderation_notes',
        'is_flagged',
        'flag_reason',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
        'closes_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Smart Accessor for Job Title
     * Ensures technical slugs like 'uiux_designer' are displayed as 'UI/UX Designer'
     */
    public function getTitleAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        // If it contains underscores, it's likely a technical slug
        if (str_contains($value, '_')) {
            // Handle specific case for UI/UX
            $value = str_replace('uiux', 'UI/UX', $value);

            // Handle typical slug to title conversion
            return Str::headline($value);
        }

        // Catch-all for uiux without underscores if needed
        if (strtolower($value) === 'uiux designer') {
            return 'UI/UX Designer';
        }

        return $value;
    }

    /**
     * Get the route key for the model (used in URL generation and route model binding).
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function template()
    {
        return $this->belongsTo(JobTemplate::class, 'job_template_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function moderatedBy()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopePendingModeration($query)
    {
        return $query->where('moderation_status', self::MODERATION_PENDING);
    }

    public function scopeApprovedModeration($query)
    {
        return $query->where('moderation_status', self::MODERATION_APPROVED);
    }

    public function scopeRejectedModeration($query)
    {
        return $query->where('moderation_status', self::MODERATION_REJECTED);
    }

    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    public function isPendingModeration(): bool
    {
        return $this->moderation_status === self::MODERATION_PENDING;
    }

    public function isApprovedModeration(): bool
    {
        return $this->moderation_status === self::MODERATION_APPROVED;
    }

    public function isRejectedModeration(): bool
    {
        return $this->moderation_status === self::MODERATION_REJECTED;
    }
}
