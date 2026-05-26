<?php

namespace App\Http\Requests\Api\Employer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Same fields as CreateJobRequest but all optional (sometimes)
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'category' => ['sometimes', 'nullable', 'string', 'max:255'],
            'industry_type' => ['sometimes', 'nullable', 'string', 'max:255'],
            'employment_type' => ['sometimes', 'nullable', 'string', 'max:100'],
            'remote_type' => ['sometimes', 'nullable', 'string', 'max:100'],
            'vacancies' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'salary_min' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'salary_max' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'salary_currency' => ['sometimes', 'nullable', 'string', 'max:10'],
            'experience_min_years' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'experience_max_years' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'summary' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'description' => ['sometimes', 'nullable', 'string'],
            'requirements' => ['sometimes', 'nullable', 'string'],
            'responsibilities' => ['sometimes', 'nullable', 'string'],
            'highlight_work_setup' => ['sometimes', 'nullable', 'string', 'max:255'],
            'highlight_shift_schedule' => ['sometimes', 'nullable', 'string', 'max:255'],
            'highlight_monthly_rate' => ['sometimes', 'nullable', 'string', 'max:255'],
            'highlight_benefits' => ['sometimes', 'nullable', 'string'],
            'closes_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
