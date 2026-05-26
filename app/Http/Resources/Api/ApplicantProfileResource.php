<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicantProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
            'display_name' => $this->display_name,
            'job_title' => $this->job_title,
            'title' => $this->title,
            'headline' => $this->headline,
            'about' => $this->about,
            'location' => $this->location,
            'work_mode' => $this->work_mode,
            'degree' => $this->degree,
            'years_experience' => $this->years_experience,
            'availability' => $this->availability,
            'job_type' => $this->job_type,
            'expected_salary_min' => $this->expected_salary_min,
            'expected_salary_max' => $this->expected_salary_max,
            'salary_currency' => $this->salary_currency,
            'skills' => $this->skills,
            'tools_used' => $this->tools_used,
            'languages' => $this->languages,
            'expertise_categories' => $this->expertise_categories,
            'education_details' => $this->education_details,
            'certifications' => $this->certifications,
            'key_achievements' => $this->key_achievements,
            'activities_interests' => $this->activities_interests,
            'experience_overview' => $this->experience_overview,
            'career_objective' => $this->career_objective,
            'references_block' => $this->references_block,
            'cv_path' => $this->cv_path ? asset('storage/'.$this->cv_path) : null,
            'rating' => $this->rating,
            'rating_count' => $this->rating_count,
            'verification_status' => $this->verification_status,
            'verified' => $this->verified,
            'verified_at' => $this->verified_at?->toIso8601String(),
            'onboarding_step' => $this->onboarding_step,
            'onboarding_completed' => $this->isOnboarded(),
            'onboarding_completed_at' => $this->onboarding_completed_at?->toIso8601String(),
        ];
    }
}
