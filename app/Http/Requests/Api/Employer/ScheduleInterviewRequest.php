<?php

namespace App\Http\Requests\Api\Employer;

use App\Models\Interview;
use App\Models\JobApplication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScheduleInterviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! $this->filled(['job_application_id', 'applicant_id'])) {
            return true;
        }

        $company = $this->user()?->company;

        if (! $company) {
            return false;
        }

        return JobApplication::whereKey($this->integer('job_application_id'))
            ->where('user_id', $this->integer('applicant_id'))
            ->whereHas('jobPosting', fn ($query) => $query->where('company_id', $company->id))
            ->exists();
    }

    public function rules(): array
    {
        return [
            'job_application_id' => ['required', 'integer', 'exists:job_applications,id'],
            'applicant_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'interview_type' => ['required', Rule::in(array_keys(Interview::TYPES))],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'duration_minutes' => ['required', 'integer', 'min:5', 'max:480'],
            'location' => ['nullable', 'string', 'max:255'],
            'meeting_link' => ['nullable', 'url', 'max:500'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
