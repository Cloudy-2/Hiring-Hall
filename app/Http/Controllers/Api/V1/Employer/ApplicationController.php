<?php

namespace App\Http\Controllers\Api\V1\Employer;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\Employer\MoveApplicationStageRequest;
use App\Http\Requests\Api\Employer\UpdateApplicationNotesRequest;
use App\Http\Resources\Api\JobApplicationResource;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\PipelineStage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends ApiController
{
    /**
     * GET /api/v1/employer/applications
     *
     * Lists all applications across the authenticated employer's company jobs.
     */
    public function index(Request $request): JsonResponse
    {
        $company = $request->user()->company;
        if (! $company) {
            return $this->notFound('Company profile not found.');
        }

        $paginator = JobApplication::with(['applicantProfile.user', 'jobPosting.company', 'pipelineStage'])
            ->whereHas('jobPosting', fn ($q) => $q->where('company_id', $company->id))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('stage_id'), fn ($q) => $q->where('pipeline_stage_id', $request->stage_id))
            ->latest('applied_at')
            ->paginate($this->perPage($request, 20));

        return $this->paginated(
            $paginator,
            JobApplicationResource::collection($paginator->items())
        );
    }

    /**
     * GET /api/v1/employer/history
     *
     * Lists closed jobs or terminal applicant outcomes for the employer company.
     */
    public function history(Request $request): JsonResponse
    {
        $company = $request->user()->company;
        if (! $company) {
            return $this->notFound('Company profile not found.');
        }

        $type = $request->get('type', 'applicants');
        $search = $request->string('search')->toString();

        if ($type === 'jobs') {
            $query = JobPosting::with('company')
                ->where('company_id', $company->id)
                ->where('status', 'closed');

            if ($search !== '') {
                $query->where('title', 'like', '%'.$search.'%');
            }

            $paginator = $query
                ->latest('closes_at')
                ->paginate($this->perPage($request, 20));

            return $this->paginated(
                $paginator,
                \App\Http\Resources\Api\JobPostingResource::collection($paginator->items())
            );
        }

        $query = JobApplication::with(['applicantProfile.user', 'jobPosting.company', 'pipelineStage'])
            ->whereHas('jobPosting', fn ($q) => $q->where('company_id', $company->id))
            ->whereIn('status', ['not_selected', 'no_longer_under_consideration', 'closed', 'declined', 'rejected']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('applicantProfile', fn ($profile) => $profile->where('display_name', 'like', '%'.$search.'%'))
                    ->orWhereHas('user', fn ($user) => $user->where('name', 'like', '%'.$search.'%'))
                    ->orWhereHas('jobPosting', fn ($job) => $job->where('title', 'like', '%'.$search.'%'));
            });
        }

        $paginator = $query
            ->latest('reviewed_at')
            ->paginate($this->perPage($request, 20));

        return $this->paginated(
            $paginator,
            JobApplicationResource::collection($paginator->items())
        );
    }

    /**
     * GET /api/v1/employer/jobs/{id}/applications
     *
     * Lists all applications for a specific job posting.
     */
    public function indexForJob(Request $request, int $jobId): JsonResponse
    {
        $company = $request->user()->company;

        $job = JobPosting::where('id', $jobId)
            ->where('company_id', $company?->id)
            ->first();

        if (! $job) {
            return $this->notFound('Job posting not found.');
        }

        $paginator = JobApplication::with(['applicantProfile.user', 'jobPosting.company', 'pipelineStage'])
            ->where('job_posting_id', $job->id)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('stage_id'), fn ($q) => $q->where('pipeline_stage_id', $request->stage_id))
            ->latest('applied_at')
            ->paginate($this->perPage($request, 20));

        return $this->paginated(
            $paginator,
            JobApplicationResource::collection($paginator->items())
        );
    }

    /**
     * GET /api/v1/employer/applications/{id}
     *
     * View a single application with full applicant profile.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $company = $request->user()->company;

        $application = JobApplication::with([
            'applicantProfile.user',
            'jobPosting.company',
            'pipelineStage',
        ])
            ->whereHas('jobPosting', fn ($q) => $q->where('company_id', $company?->id))
            ->where('id', $id)
            ->first();

        if (! $application) {
            return $this->notFound('Application not found.');
        }

        return $this->success(new JobApplicationResource($application));
    }

    /**
     * PUT /api/v1/employer/applications/{id}/stage
     *
     * Moves an application to a different pipeline stage.
     */
    public function moveStage(MoveApplicationStageRequest $request, int $id): JsonResponse
    {
        $company = $request->user()->company;

        $application = JobApplication::whereHas(
            'jobPosting', fn ($q) => $q->where('company_id', $company?->id)
        )->where('id', $id)->first();

        if (! $application) {
            return $this->notFound('Application not found.');
        }

        $stage = PipelineStage::where('id', $request->pipeline_stage_id)
            ->where('company_id', $company?->id)
            ->first();

        if (! $stage) {
            return $this->notFound('Pipeline stage not found.');
        }

        $application->update([
            'pipeline_stage_id' => $stage->id,
            'reviewed_at' => now(),
        ]);

        $application->load(['pipelineStage', 'applicantProfile.user', 'jobPosting.company']);

        return $this->success(new JobApplicationResource($application), 'Application moved to new stage.');
    }

    /**
     * PUT /api/v1/employer/applications/{id}/notes
     *
     * Updates the reviewer notes for an application.
     */
    public function updateNotes(UpdateApplicationNotesRequest $request, int $id): JsonResponse
    {
        $company = $request->user()->company;

        $application = JobApplication::whereHas(
            'jobPosting', fn ($q) => $q->where('company_id', $company?->id)
        )->where('id', $id)->first();

        if (! $application) {
            return $this->notFound('Application not found.');
        }

        $application->update(['notes' => $request->notes]);

        return $this->success(new JobApplicationResource($application->fresh()), 'Notes updated.');
    }

    /**
     * GET /api/v1/employer/pipeline-stages
     *
     * Returns available pipeline stages.
     */
    public function pipelineStages(): JsonResponse
    {
        $stages = PipelineStage::orderBy('sort_order')
            ->get(['id', 'name', 'color', 'sort_order'])
            ->map(fn (PipelineStage $stage) => [
                'id' => $stage->id,
                'name' => $stage->name,
                'color' => $stage->color,
                'order' => $stage->sort_order,
            ]);

        return $this->success($stages);
    }
}
