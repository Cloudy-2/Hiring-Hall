<?php

namespace App\Http\Requests\Api\Applicant;

use Illuminate\Foundation\Http\FormRequest;

class SubmitApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_posting_id' => ['required', 'integer', 'exists:job_postings,id'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            // cv_path is handled separately via file upload endpoint;
            // allow an override path if already uploaded
            'cv_path' => ['nullable', 'string', 'max:500'],
        ];
    }
}
