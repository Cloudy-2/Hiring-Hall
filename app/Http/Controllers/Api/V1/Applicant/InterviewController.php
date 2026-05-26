<?php

namespace App\Http\Controllers\Api\V1\Applicant;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Resources\Api\InterviewResource;
use App\Models\Interview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InterviewController extends ApiController
{
    /**
     * GET /api/v1/applicant/interviews
     *
     * Lists all interviews scheduled for the authenticated applicant.
     */
    public function index(Request $request): JsonResponse
    {
        $paginator = Interview::with('jobApplication.jobPosting')
            ->where('applicant_id', $request->user()->id)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('scheduled_at', 'asc')
            ->paginate($this->perPage($request));

        return $this->paginated(
            $paginator,
            InterviewResource::collection($paginator->items())
        );
    }

    /**
     * GET /api/v1/applicant/interviews/{id}
     *
     * View a single interview (must belong to the applicant).
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $interview = Interview::with('jobApplication.jobPosting')
            ->where('id', $id)
            ->where('applicant_id', $request->user()->id)
            ->first();

        if (! $interview) {
            return $this->notFound('Interview not found.');
        }

        return $this->success(new InterviewResource($interview));
    }
}
