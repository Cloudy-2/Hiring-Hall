<?php

namespace App\Http\Requests\Api\Employer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'industry' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'website' => ['sometimes', 'nullable', 'url', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'contact_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'contact_position' => ['sometimes', 'nullable', 'string', 'max:255'],
            'contact_person_email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'contact_person_phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'established_year' => ['sometimes', 'nullable', 'integer', 'min:1800', 'max:2100'],
            'employees_count' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'registration_type' => ['sometimes', 'nullable', 'string', 'max:100'],
            'registration_number' => ['sometimes', 'nullable', 'string', 'max:100'],
            'business_address' => ['sometimes', 'nullable', 'string', 'max:500'],
            'city' => ['sometimes', 'nullable', 'string', 'max:100'],
            'province' => ['sometimes', 'nullable', 'string', 'max:100'],
            'postal_code' => ['sometimes', 'nullable', 'string', 'max:20'],
            'country' => ['sometimes', 'nullable', 'string', 'max:100'],
        ];
    }
}
