<?php

namespace App\Http\Requests\Api\Applicant;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'gender' => ['sometimes', 'nullable', 'in:male,female,non_binary,prefer_not_to_say'],
            'date_of_birth' => ['sometimes', 'nullable', 'date', 'before:today'],
            'marital_status' => ['sometimes', 'nullable', 'string', 'max:50'],
            'address' => ['sometimes', 'nullable', 'string', 'max:500'],
            'social_facebook' => ['sometimes', 'nullable', 'url', 'max:255'],
            'social_twitter' => ['sometimes', 'nullable', 'url', 'max:255'],
            'social_instagram' => ['sometimes', 'nullable', 'url', 'max:255'],
            'social_github' => ['sometimes', 'nullable', 'url', 'max:255'],
            'social_youtube' => ['sometimes', 'nullable', 'url', 'max:255'],
        ];
    }
}
