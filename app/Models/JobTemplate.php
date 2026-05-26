<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_ACTIVE = 'active';

    protected $fillable = [
        'user_id',
        'company_id',
        'name',
        'title',
        'description',
        'responsibilities',
        'requirements',
        'benefits',
        'category',
        'industry_type',
        'recruiter_type',
        'employment_type',
        'remote_type',
        'vacancies',
        'experience_min_years',
        'experience_max_years',
        'salary_min',
        'salary_max',
        'salary_currency',
        'location',
        'highlight_work_setup',
        'highlight_shift_schedule',
        'is_default',
        'status',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'vacancies' => 'integer',
        'experience_min_years' => 'integer',
        'experience_max_years' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function jobPostings(): HasMany
    {
        return $this->hasMany(JobPosting::class);
    }

    public function activeJobPostings(): HasMany
    {
        return $this->jobPostings()->where('status', 'open');
    }

    /**
     * True if this template has at least one open job posting.
     */
    public function isInUse(): bool
    {
        return $this->activeJobPostings()->exists();
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeAccessibleBy($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
                ->orWhereIn('company_id', $user->companies()->pluck('id'));
        });
    }
}
