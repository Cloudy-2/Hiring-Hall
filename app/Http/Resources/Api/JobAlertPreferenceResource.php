<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobAlertPreferenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'keywords' => $this->keywords,
            'location' => $this->location,
            'category' => $this->category,
            'remote_type' => $this->remote_type,
            'employment_type' => $this->employment_type,
            'frequency' => $this->frequency,
            'email_enabled' => $this->email_enabled,
            'is_active' => $this->is_active,
            'last_sent_at' => $this->last_sent_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
