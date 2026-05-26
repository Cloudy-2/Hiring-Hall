<?php

namespace App\Http\Controllers\Api\V1\Employer;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\Employer\CreateJobRequest;
use App\Http\Requests\Api\Employer\UpdateJobRequest;
use App\Http\Resources\Api\JobPostingResource;
use App\Models\JobPosting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends ApiController
{
    /**
     * GET /api/v1/employer/jobs
     *
     * Lists all job postings belonging to the employer's company.
     */
    public function index(Request $request): JsonResponse
    {
        $company = $request->user()->company;
        if (! $company) {
            return $this->notFound('Company profile not found.');
        }

        $query = JobPosting::where('company_id', $company->id)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest();

        $paginator = $query->paginate($this->perPage($request));

        return $this->paginated(
            $paginator,
            JobPostingResource::collection($paginator->items())
        );
    }

    /**
     * POST /api/v1/employer/jobs
     *
     * Create a new job posting. Submitted as 'pending' moderation.
     */
    public function store(CreateJobRequest $request): JsonResponse
    {
        $company = $request->user()->company;
        if (! $company) {
            return $this->notFound('Company profile not found. Please complete onboarding first.');
        }

        $data = $request->validated();
        $data['company_id'] = $company->id;
        $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);
        $data['status'] = 'draft';
        $data['moderation_status'] = JobPosting::MODERATION_PENDING;

        $job = JobPosting::create($data);
        $job->load('company');

        return $this->created(new JobPostingResource($job), 'Job posting created and submitted for review.');
    }

    /**
     * GET /api/v1/employer/jobs/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $company = $request->user()->company;

        $job = JobPosting::with('company')
            ->where('id', $id)
            ->where('company_id', $company?->id)
            ->first();

        if (! $job) {
            return $this->notFound('Job posting not found.');
        }

        return $this->success(new JobPostingResource($job));
    }

    /**
     * PUT /api/v1/employer/jobs/{id}
     *
     * Update a job posting. Re-triggers moderation if content fields change.
     */
    public function update(UpdateJobRequest $request, int $id): JsonResponse
    {
        $company = $request->user()->company;

        $job = JobPosting::where('id', $id)
            ->where('company_id', $company?->id)
            ->first();

        if (! $job) {
            return $this->notFound('Job posting not found.');
        }

        $data = $request->validated();

        // Re-trigger moderation if key content fields are updated
        $contentFields = ['title', 'description', 'requirements', 'responsibilities'];
        $hasContentChange = ! empty(array_intersect(array_keys($data), $contentFields));

        if ($hasContentChange) {
            $data['moderation_status'] = JobPosting::MODERATION_PENDING;
        }

        $job->update($data);

        return $this->success(new JobPostingResource($job->fresh()->load('company')), 'Job posting updated.');
    }

    /**
     * DELETE /api/v1/employer/jobs/{id}
     *
     * Soft-deletes a job posting.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $company = $request->user()->company;

        $job = JobPosting::where('id', $id)
            ->where('company_id', $company?->id)
            ->first();

        if (! $job) {
            return $this->notFound('Job posting not found.');
        }

        $job->delete();

        return $this->success(null, 'Job posting deleted.');
    }

    /**
     * POST /api/v1/employer/jobs/{id}/publish
     *
     * Sets the job posting status to 'active' (if approved by moderation).
     */
    public function publish(Request $request, int $id): JsonResponse
    {
        $company = $request->user()->company;

        $job = JobPosting::where('id', $id)
            ->where('company_id', $company?->id)
            ->first();

        if (! $job) {
            return $this->notFound('Job posting not found.');
        }

        if (! $job->isApprovedModeration()) {
            return $this->error('This job posting must be approved by a moderator before it can be published.', 422);
        }

        $job->update([
            'status' => 'active',
            'posted_at' => $job->posted_at ?? now(),
        ]);

        return $this->success(new JobPostingResource($job->fresh()), 'Job posting is now live.');
    }

    /**
     * POST /api/v1/employer/jobs/{id}/close
     *
     * Closes a job posting (no more applications).
     */
    public function close(Request $request, int $id): JsonResponse
    {
        $company = $request->user()->company;

        $job = JobPosting::where('id', $id)
            ->where('company_id', $company?->id)
            ->first();

        if (! $job) {
            return $this->notFound('Job posting not found.');
        }

        $job->update(['status' => 'closed', 'closes_at' => now()]);

        return $this->success(new JobPostingResource($job->fresh()), 'Job posting closed.');
    }
}
