<?php

namespace App\Http\Requests\Api\Applicant;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfessionalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'display_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'job_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'headline' => ['sometimes', 'nullable', 'string', 'max:255'],
            'about' => ['sometimes', 'nullable', 'string'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'work_mode' => ['sometimes', 'nullable', 'string', 'max:50'],
            'degree' => ['sometimes', 'nullable', 'string', 'max:255'],
            'years_experience' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:50'],
            'availability' => ['sometimes', 'nullable', 'string', 'max:100'],
            'job_type' => ['sometimes', 'nullable', 'string', 'max:50'],
            'expected_salary_min' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'expected_salary_max' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'salary_currency' => ['sometimes', 'nullable', 'string', 'max:10'],
            'skills' => ['sometimes', 'nullable'],
            'tools_used' => ['sometimes', 'nullable'],
            'languages' => ['sometimes', 'nullable'],
            'expertise_categories' => ['sometimes', 'nullable'],
            'education_details' => ['sometimes', 'nullable'],
            'certifications' => ['sometimes', 'nullable'],
            'key_achievements' => ['sometimes', 'nullable'],
            'activities_interests' => ['sometimes', 'nullable'],
            'experience_overview' => ['sometimes', 'nullable'],
            'career_objective' => ['sometimes', 'nullable', 'string'],
            'references_block' => ['sometimes', 'nullable'],
        ];
    }
}
