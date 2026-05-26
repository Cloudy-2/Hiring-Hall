<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'logo_url' => $this->logo_url
                ? (str_starts_with($this->logo_url, 'http') ? $this->logo_url : asset('storage/'.$this->logo_url))
                : null,
            'registration_document_url' => $this->registration_document_url
                ? (str_starts_with($this->registration_document_url, 'http') ? $this->registration_document_url : asset('storage/'.$this->registration_document_url))
                : null,
            'location' => $this->location,
            'industry' => $this->industry,
            'description' => $this->description,
            'website' => $this->website,
            'email' => $this->email,
            'phone' => $this->phone,
            'contact_name' => $this->contact_name,
            'contact_position' => $this->contact_position,
            'contact_person_email' => $this->contact_person_email,
            'contact_person_phone' => $this->contact_person_phone,
            'established_year' => $this->established_year,
            'employees_count' => $this->employees_count,
            'business_address' => $this->business_address,
            'city' => $this->city,
            'province' => $this->province,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'verification_status' => $this->verification_status,
            'verified' => $this->verified,
            'verified_at' => $this->verified_at?->toIso8601String(),
            'rating' => $this->rating,
            'rating_count' => $this->rating_count,
            'onboarding_step' => $this->onboarding_step,
            'onboarding_completed' => $this->isOnboarded(),
            'onboarding_completed_at' => $this->onboarding_completed_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
