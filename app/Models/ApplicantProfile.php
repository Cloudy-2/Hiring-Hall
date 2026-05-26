<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicantProfile extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    public const STATUS_PENDING = 'pending';

    public const STATUS_VERIFIED = 'verified';

    public const STATUS_REJECTED = 'rejected';

    protected $table = 'applicant_profiles';

    protected $fillable = [
        'user_id',
        'onboarding_step',
        'onboarding_completed_at',
        'display_name',
        'job_title',
        'title',
        'location',
        'work_mode',
        'degree',
        'years_experience',
        'availability',
        'job_type',
        'expected_salary_min',
        'expected_salary_max',
        'salary_currency',
        'verified',
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_notes',
        'rating',
        'rating_count',
        'headline',
        'about',
        'cv_path',
        'career_objective',
        'education_details',
        'certifications',
        'key_achievements',
        'activities_interests',
        'references_block',
        'experience_overview',
        'expertise_categories',
        'skills',
        'tools_used',
        'languages',
        'social_links',
    ];

    protected $casts = [
        'onboarding_completed_at' => 'datetime',
        'verified_at' => 'datetime',
        'verified' => 'boolean',
    ];

    /**
     * Check if the applicant has completed onboarding.
     */
    public function isOnboarded(): bool
    {
        return ! is_null($this->onboarding_completed_at);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'applicant_profile_id');
    }

    public function verifiedByUser()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', self::STATUS_PENDING);
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', self::STATUS_VERIFIED);
    }

    public function scopeRejected($query)
    {
        return $query->where('verification_status', self::STATUS_REJECTED);
    }

    public function isPending(): bool
    {
        return $this->verification_status === self::STATUS_PENDING;
    }

    public function isVerified(): bool
    {
        return $this->verification_status === self::STATUS_VERIFIED;
    }

    public function isRejected(): bool
    {
        return $this->verification_status === self::STATUS_REJECTED;
    }
}
