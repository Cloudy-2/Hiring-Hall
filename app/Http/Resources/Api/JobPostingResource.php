<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobPostingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'location' => $this->location,
            'category' => $this->category,
            'industry_type' => $this->industry_type,
            'employment_type' => $this->employment_type,
            'remote_type' => $this->remote_type,
            'vacancies' => $this->vacancies,
            'status' => $this->status,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'salary_currency' => $this->salary_currency,
            'experience_min_years' => $this->experience_min_years,
            'experience_max_years' => $this->experience_max_years,
            'summary' => $this->summary,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'responsibilities' => $this->responsibilities,
            'highlight_work_setup' => $this->highlight_work_setup,
            'highlight_shift_schedule' => $this->highlight_shift_schedule,
            'highlight_monthly_rate' => $this->highlight_monthly_rate,
            'highlight_benefits' => $this->highlight_benefits,
            'moderation_status' => $this->moderation_status,
            'posted_at' => $this->posted_at?->toIso8601String(),
            'closes_at' => $this->closes_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'company' => $this->whenLoaded('company', fn () => new CompanyResource($this->company)),
        ];
    }
}
