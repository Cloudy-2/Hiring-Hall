<?php

namespace App\Http\Controllers\Api\V1\Employer;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\Employer\ScheduleInterviewRequest;
use App\Http\Requests\Api\Employer\SubmitFeedbackRequest;
use App\Http\Requests\Api\Employer\UpdateInterviewRequest;
use App\Http\Resources\Api\InterviewResource;
use App\Models\Interview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InterviewController extends ApiController
{
    /**
     * GET /api/v1/employer/interviews
     *
     * Lists all interviews scheduled by this employer.
     */
    public function index(Request $request): JsonResponse
    {
        $paginator = Interview::with(['jobApplication.jobPosting'])
            ->forEmployer($request->user()->id)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('scheduled_at', 'asc')
            ->paginate($this->perPage($request));

        return $this->paginated(
            $paginator,
            InterviewResource::collection($paginator->items())
        );
    }

    /**
     * POST /api/v1/employer/interviews
     *
     * Schedule a new interview for an applicant.
     */
    public function store(ScheduleInterviewRequest $request): JsonResponse
    {
        $interview = Interview::create([
            ...$request->validated(),
            'employer_id' => $request->user()->id,
            'status' => Interview::STATUS_SCHEDULED,
        ]);

        $interview->load('jobApplication.jobPosting');

        return $this->created(new InterviewResource($interview), 'Interview scheduled.');
    }

    /**
     * GET /api/v1/employer/interviews/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $interview = Interview::with('jobApplication.jobPosting')
            ->forEmployer($request->user()->id)
            ->where('id', $id)
            ->first();

        if (! $interview) {
            return $this->notFound('Interview not found.');
        }

        return $this->success(new InterviewResource($interview));
    }

    /**
     * PUT /api/v1/employer/interviews/{id}
     *
     * Update interview details (reschedule, change link, etc.).
     */
    public function update(UpdateInterviewRequest $request, int $id): JsonResponse
    {
        $interview = Interview::forEmployer($request->user()->id)->where('id', $id)->first();

        if (! $interview) {
            return $this->notFound('Interview not found.');
        }

        $data = $request->validated();

        // Auto-mark as rescheduled if the scheduled_at changes
        if (isset($data['scheduled_at']) && ! isset($data['status'])) {
            $data['status'] = Interview::STATUS_RESCHEDULED;
        }

        $interview->update($data);

        return $this->success(new InterviewResource($interview->fresh()->load('jobApplication')), 'Interview updated.');
    }

    /**
     * DELETE /api/v1/employer/interviews/{id}
     *
     * Cancel an interview (soft delete + status update).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $interview = Interview::forEmployer($request->user()->id)->where('id', $id)->first();

        if (! $interview) {
            return $this->notFound('Interview not found.');
        }

        $interview->update(['status' => Interview::STATUS_CANCELLED]);
        $interview->delete();

        return $this->success(null, 'Interview cancelled.');
    }

    /**
     * POST /api/v1/employer/interviews/{id}/feedback
     *
     * Submit post-interview feedback and an optional rating.
     */
    public function feedback(SubmitFeedbackRequest $request, int $id): JsonResponse
    {
        $interview = Interview::forEmployer($request->user()->id)->where('id', $id)->first();

        if (! $interview) {
            return $this->notFound('Interview not found.');
        }

        $interview->update([
            'feedback' => $request->feedback,
            'rating' => $request->rating,
            'status' => Interview::STATUS_COMPLETED,
        ]);

        return $this->success(new InterviewResource($interview->fresh()), 'Feedback submitted.');
    }
}
