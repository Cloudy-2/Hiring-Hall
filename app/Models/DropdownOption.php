<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DropdownOption extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'type',
        'value',
        'label',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Dropdown types constants
    const TYPE_JOB_CATEGORY = 'job_category';

    const TYPE_EMPLOYMENT_TYPE = 'employment_type';

    const TYPE_REMOTE_TYPE = 'remote_type';

    const TYPE_CURRENCY = 'currency';

    const TYPE_WORK_SETUP = 'work_setup';

    const TYPE_SHIFT_SCHEDULE = 'shift_schedule';

    // Applicant profile types
    const TYPE_APPLICANT_TITLE = 'applicant_title';

    const TYPE_WORK_MODE = 'work_mode';

    const TYPE_AVAILABILITY = 'availability';

    const TYPE_JOB_TYPE = 'job_type';

    const TYPE_EXPERTISE_CATEGORY = 'expertise_category';

    const TYPE_LANGUAGE = 'language';

    // Search/Filter types (for Post a Job and Search Jobs)
    const TYPE_INDUSTRY_TYPE = 'industry_type';

    const TYPE_RECRUITER_TYPE = 'recruiter_type';

    const TYPE_LOCATION = 'location';

    /**
     * Scope to get active options only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get options by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to order by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('label');
    }

    /**
     * Get options for a specific type as key-value pairs for dropdowns
     */
    public static function getOptions(string $type): array
    {
        return static::active()
            ->ofType($type)
            ->ordered()
            ->pluck('label', 'value')
            ->toArray();
    }

    /**
     * Get options for a specific type as collection
     */
    public static function getOptionsCollection(string $type)
    {
        return static::active()
            ->ofType($type)
            ->ordered()
            ->get();
    }
}
