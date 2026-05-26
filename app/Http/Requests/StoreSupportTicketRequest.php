<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupportTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpeg,jpg,png,gif,webp,pdf|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required' => 'Please enter a subject.',
            'message.required' => 'Please enter your message.',
            'attachments.*.mimes' => 'Each attachment must be an image (JPEG, PNG, GIF, WebP) or PDF.',
            'attachments.*.max' => 'Each attachment may not be greater than 5 MB.',
        ];
    }
}
