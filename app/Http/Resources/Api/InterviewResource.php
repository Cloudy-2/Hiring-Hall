<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'job_application_id' => $this->job_application_id,
            'employer_id' => $this->employer_id,
            'applicant_id' => $this->applicant_id,
            'title' => $this->title,
            'description' => $this->description,
            'interview_type' => $this->interview_type,
            'interview_type_label' => $this->getTypeLabel(),
            'scheduled_at' => $this->scheduled_at?->toIso8601String(),
            'duration_minutes' => $this->duration_minutes,
            'end_time' => $this->scheduled_at?->copy()->addMinutes($this->duration_minutes ?? 0)->toIso8601String(),
            'location' => $this->location,
            'meeting_link' => $this->meeting_link,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            'notes' => $this->notes,
            'feedback' => $this->feedback,
            'rating' => $this->rating,
            'is_upcoming' => $this->isUpcoming(),
            'created_at' => $this->created_at?->toIso8601String(),
            // Nested
            'job_application' => $this->whenLoaded(
                'jobApplication',
                fn () => new JobApplicationResource($this->jobApplication)
            ),
        ];
    }
}
