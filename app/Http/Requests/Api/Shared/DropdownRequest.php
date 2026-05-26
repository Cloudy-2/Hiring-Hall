<?php

namespace App\Http\Requests\Api\Shared;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DropdownRequest extends FormRequest
{
    public const TYPES = [
        'skills',
        'categories',
        'job_types',
        'employment_types',
        'remote_types',
        'work_modes',
        'industries',
        'languages',
        'degrees',
        'currencies',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(self::TYPES)],
        ];
    }

    public function validationData(): array
    {
        return array_merge(parent::validationData(), [
            'type' => $this->route('type'),
        ]);
    }
}
