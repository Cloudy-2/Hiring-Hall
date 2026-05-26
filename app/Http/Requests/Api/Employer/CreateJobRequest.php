<?php

namespace App\Http\Requests\Api\Employer;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'industry_type' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'string', 'max:100'],
            'remote_type' => ['nullable', 'string', 'max:100'],
            'vacancies' => ['nullable', 'integer', 'min:1'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'salary_currency' => ['nullable', 'string', 'max:10'],
            'experience_min_years' => ['nullable', 'numeric', 'min:0'],
            'experience_max_years' => ['nullable', 'numeric', 'min:0'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'highlight_work_setup' => ['nullable', 'string', 'max:255'],
            'highlight_shift_schedule' => ['nullable', 'string', 'max:255'],
            'highlight_monthly_rate' => ['nullable', 'string', 'max:255'],
            'highlight_benefits' => ['nullable', 'string'],
            'closes_at' => ['nullable', 'date', 'after:today'],
        ];
    }
}
