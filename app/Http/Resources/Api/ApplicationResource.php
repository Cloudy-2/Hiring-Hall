<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_id' => $this->job_id,
            'job' => $this->whenLoaded('job', fn () => new JobResource($this->job)),
            'applicant_id' => $this->applicant_id,
            'status' => $this->status,
            'stage_id' => $this->stage_id,
            'cover_letter' => $this->cover_letter,
            'cv_url' => $this->cv_url,
            'submitted_at' => $this->submitted_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
