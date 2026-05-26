<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_posting_id' => $this->job_posting_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'pipeline_stage_id' => $this->pipeline_stage_id,
            'cover_letter' => $this->cover_letter,
            'cv_path' => $this->cv_path ? asset('storage/'.$this->cv_path) : null,
            'notes' => $this->notes,
            'applied_at' => $this->applied_at?->toIso8601String(),
            'reviewed_at' => $this->reviewed_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            // Nested relationships (loaded on demand)
            'job_posting' => $this->whenLoaded('jobPosting', fn () => new JobPostingResource($this->jobPosting)),
            'pipeline_stage' => $this->whenLoaded('pipelineStage', fn () => [
                'id' => $this->pipelineStage->id,
                'name' => $this->pipelineStage->name,
            ]),
            'applicant_profile' => $this->whenLoaded(
                'applicantProfile',
                fn () => new ApplicantProfileResource($this->applicantProfile)
            ),
        ];
    }
}
