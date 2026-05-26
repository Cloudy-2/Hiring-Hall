<?php

namespace App\Http\Controllers\Api\V1\Applicant;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\Applicant\SubmitApplicationRequest;
use App\Http\Resources\Api\JobApplicationResource;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends ApiController
{
    /**
     * GET /api/v1/applicant/applications
     *
     * Lists all applications for the authenticated applicant.
     */
    public function index(Request $request): JsonResponse
    {
        $profile = $request->user()->applicantProfile;
        if (! $profile) {
            return $this->notFound('Applicant profile not found.');
        }

        $paginator = JobApplication::with(['jobPosting.company', 'pipelineStage'])
            ->where('applicant_profile_id', $profile->id)
            ->latest('applied_at')
            ->paginate($this->perPage($request));

        return $this->paginated(
            $paginator,
            JobApplicationResource::collection($paginator->items())
        );
    }

    /**
     * GET /api/v1/applicant/applications/history
     *
     * Lists withdrawn, rejected, closed, expired, or soft-deleted applications.
     */
    public function history(Request $request): JsonResponse
    {
        $profile = $request->user()->applicantProfile;
        if (! $profile) {
            return $this->notFound('Applicant profile not found.');
        }

        $historyStatuses = ['withdrawn', 'cancelled', 'declined', 'rejected', 'not_selected', 'closed', 'expired'];

        $query = JobApplication::withTrashed()
            ->with(['jobPosting.company', 'pipelineStage'])
            ->where('applicant_profile_id', $profile->id)
            ->where(function ($q) use ($historyStatuses) {
                $q->whereNotNull('deleted_at')
                    ->orWhereIn('status', $historyStatuses);
            });

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->whereHas('jobPosting', function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhereHas('company', fn ($company) => $company->where('name', 'like', '%'.$search.'%'));
            });
        }

        match ($request->get('status')) {
            'withdrawn' => $query->where(function ($q) {
                $q->whereIn('status', ['withdrawn', 'cancelled'])
                    ->orWhereNotNull('deleted_at');
            }),
            'not_selected' => $query->whereIn('status', ['declined', 'rejected', 'not_selected']),
            'closed' => $query->whereIn('status', ['closed', 'expired']),
            default => null,
        };

        $paginator = $query
            ->latest('updated_at')
            ->paginate($this->perPage($request, 20));

        return $this->paginated(
            $paginator,
            JobApplicationResource::collection($paginator->items())
        );
    }

    /**
     * POST /api/v1/applicant/applications
     *
     * Submit an application to a job posting.
     * Prevents duplicate applications to the same job.
     */
    public function store(SubmitApplicationRequest $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->applicantProfile;

        if (! $profile) {
            return $this->notFound('Applicant profile not found.');
        }

        $job = JobPosting::where('id', $request->job_posting_id)
            ->approvedModeration()
            ->where('status', 'active')
            ->first();

        if (! $job) {
            return $this->error('This job posting is not available for applications.', 422);
        }

        // Prevent duplicate applications
        $existing = JobApplication::where('job_posting_id', $job->id)
            ->where('applicant_profile_id', $profile->id)
            ->whereNull('deleted_at')
            ->exists();

        if ($existing) {
            return $this->error('You have already applied for this job.', 409);
        }

        $application = JobApplication::create([
            'job_posting_id' => $job->id,
            'applicant_profile_id' => $profile->id,
            'user_id' => $user->id,
            'cover_letter' => $request->cover_letter,
            'cv_path' => $request->cv_path ?? $profile->cv_path,
            'status' => 'pending',
            'applied_at' => now(),
            'terms_agreed_at' => now(),
        ]);

        $application->load(['jobPosting.company', 'pipelineStage']);

        return $this->created(new JobApplicationResource($application), 'Application submitted successfully.');
    }

    /**
     * GET /api/v1/applicant/applications/{id}
     *
     * View a single application (must belong to the applicant).
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $profile = $request->user()->applicantProfile;

        $application = JobApplication::with(['jobPosting.company', 'pipelineStage'])
            ->where('id', $id)
            ->where('applicant_profile_id', $profile?->id)
            ->first();

        if (! $application) {
            return $this->notFound('Application not found.');
        }

        return $this->success(new JobApplicationResource($application));
    }

    /**
     * DELETE /api/v1/applicant/applications/{id}
     *
     * Withdraw an application. Only allowed if it is still in 'pending' status.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $profile = $request->user()->applicantProfile;

        $application = JobApplication::where('id', $id)
            ->where('applicant_profile_id', $profile?->id)
            ->first();

        if (! $application) {
            return $this->notFound('Application not found.');
        }

        if (! in_array($application->status, ['pending', 'reviewing'])) {
            return $this->error(
                'This application can no longer be withdrawn as it has already been processed.',
                422
            );
        }

        $application->delete();

        return $this->success(null, 'Application withdrawn.');
    }
}
