<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'marital_status' => $this->marital_status,
            'address' => $this->address,
            'profile_photo_url' => $this->profile_photo_url,
            'email_verified_at' => $this->email_verified_at,
            'social' => [
                'facebook' => $this->social_facebook,
                'twitter' => $this->social_twitter,
                'instagram' => $this->social_instagram,
                'github' => $this->social_github,
                'youtube' => $this->social_youtube,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
