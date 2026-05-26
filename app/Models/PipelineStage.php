<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PipelineStage extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const DEFAULT_STAGES = [
        ['name' => 'New Applications', 'color' => '#3b82f6', 'sort_order' => 1, 'is_default' => true],
        ['name' => 'Screening', 'color' => '#8b5cf6', 'sort_order' => 2, 'is_default' => false],
        ['name' => 'Interview', 'color' => '#f59e0b', 'sort_order' => 3, 'is_default' => false],
        ['name' => 'Assessment', 'color' => '#06b6d4', 'sort_order' => 4, 'is_default' => false],
        ['name' => 'Offer', 'color' => '#10b981', 'sort_order' => 5, 'is_default' => false],
        ['name' => 'Hired', 'color' => '#22c55e', 'sort_order' => 6, 'is_default' => false],
        ['name' => 'Rejected', 'color' => '#ef4444', 'sort_order' => 7, 'is_default' => false],
    ];

    protected $fillable = [
        'company_id',
        'name',
        'color',
        'sort_order',
        'is_default',
        'is_system',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_system' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'pipeline_stage_id');
    }

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public static function createDefaultStagesForCompany(Company $company): void
    {
        foreach (self::DEFAULT_STAGES as $stage) {
            self::create([
                'company_id' => $company->id,
                'name' => $stage['name'],
                'color' => $stage['color'],
                'sort_order' => $stage['sort_order'],
                'is_default' => $stage['is_default'],
                'is_system' => true,
            ]);
        }
    }
}
