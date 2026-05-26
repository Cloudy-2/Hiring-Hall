<?php

namespace App\Http\Controllers\Api\V1\Applicant;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\Applicant\ListJobsRequest;
use App\Http\Resources\Api\JobPostingResource;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\SavedJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends ApiController
{
    /**
     * GET /api/v1/applicant/jobs
     *
     * Browse approved, active job postings with filters and pagination.
     */
    public function index(ListJobsRequest $request): JsonResponse
    {
        $query = JobPosting::with('company')
            ->approvedModeration()
            ->where('status', 'active');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }
        if ($request->filled('remote_type')) {
            $query->where('remote_type', $request->remote_type);
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', '%'.$request->location.'%');
        }
        if ($request->filled('salary_min')) {
            $query->where('salary_max', '>=', $request->salary_min);
        }
        if ($request->filled('salary_max')) {
            $query->where('salary_min', '<=', $request->salary_max);
        }

        // Sorting
        match ($request->get('sort', 'newest')) {
            'salary_asc' => $query->orderBy('salary_min', 'asc'),
            'salary_desc' => $query->orderBy('salary_max', 'desc'),
            default => $query->latest('posted_at'),
        };

        $paginator = $query->paginate($this->perPage($request));

        return $this->paginated(
            $paginator,
            JobPostingResource::collection($paginator->items())
        );
    }

    /**
     * GET /api/v1/applicant/jobs/recommended
     *
     * Lists active jobs matched against profile and application history.
     */
    public function recommended(Request $request): JsonResponse
    {
        $profile = $request->user()->applicantProfile;
        if (! $profile) {
            return $this->notFound('Applicant profile not found.');
        }

        $appliedJobIds = JobApplication::where('user_id', $request->user()->id)
            ->pluck('job_posting_id')
            ->filter()
            ->all();

        $history = JobApplication::with('jobPosting')
            ->where('user_id', $request->user()->id)
            ->latest('applied_at')
            ->take(20)
            ->get();

        $criteria = $this->recommendationCriteria($profile, $history);

        $query = JobPosting::with('company')
            ->approvedModeration()
            ->where('status', 'active')
            ->when(! empty($appliedJobIds), fn ($q) => $q->whereNotIn('id', $appliedJobIds));

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('company', fn ($company) => $company->where('name', 'like', "%{$search}%"));
            });
        }

        $this->applyRecommendationCriteria($query, $criteria);

        $paginator = $query
            ->latest('posted_at')
            ->paginate($this->perPage($request, 20));

        return $this->paginated(
            $paginator,
            JobPostingResource::collection($paginator->items())
        );
    }

    /**
     * GET /api/v1/applicant/jobs/{slug}
     *
     * View a single approved job posting detail.
     */
    public function show(string $slug): JsonResponse
    {
        $job = JobPosting::with('company')
            ->approvedModeration()
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->success(new JobPostingResource($job));
    }

    /**
     * GET /api/v1/applicant/jobs/saved
     *
     * Lists jobs saved by the authenticated applicant.
     */
    public function saved(Request $request): JsonResponse
    {
        $profile = $request->user()->applicantProfile;

        if (! $profile) {
            return $this->notFound('Applicant profile not found.');
        }

        $paginator = SavedJob::with('jobPosting.company')
            ->where('applicant_profile_id', $profile->id)
            ->latest('saved_at')
            ->paginate($this->perPage($request));

        $jobs = $paginator->getCollection()->map(
            fn ($saved) => new JobPostingResource($saved->jobPosting)
        );

        return $this->paginated($paginator, $jobs);
    }

    /**
     * POST /api/v1/applicant/jobs/{slug}/save
     *
     * Toggle save/unsave a job posting.
     */
    public function toggleSave(Request $request, string $slug): JsonResponse
    {
        $job = JobPosting::where('slug', $slug)->firstOrFail();

        $profile = $request->user()->applicantProfile;
        if (! $profile) {
            return $this->notFound('Applicant profile not found.');
        }

        $existing = SavedJob::where('job_posting_id', $job->id)
            ->where('applicant_profile_id', $profile->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return $this->success(['saved' => false], 'Job removed from saved list.');
        }

        SavedJob::create([
            'job_posting_id' => $job->id,
            'applicant_profile_id' => $profile->id,
            'user_id' => $request->user()->id,
            'saved_at' => now(),
        ]);

        return $this->success(['saved' => true], 'Job saved.');
    }

    private function recommendationCriteria($profile, $history): array
    {
        $categories = [];
        $locations = [];
        $remoteTypes = [];
        $employmentTypes = [];

        foreach ($history as $application) {
            $job = $application->jobPosting;
            if (! $job) {
                continue;
            }

            if ($job->category) {
                $categories[] = $job->category;
            }
            if ($job->location) {
                $locations[] = $job->location;
            }
            if ($job->remote_type) {
                $remoteTypes[] = $job->remote_type;
            }
            if ($job->employment_type) {
                $employmentTypes[] = $job->employment_type;
            }
        }

        if ($profile->job_type) {
            $employmentTypes[] = $profile->job_type;
        }
        if ($profile->work_mode) {
            $remoteTypes[] = $profile->work_mode;
        }
        if ($profile->location) {
            $locations[] = $profile->location;
        }

        return [
            'categories' => array_values(array_unique(array_filter($categories))),
            'locations' => array_values(array_unique(array_filter($locations))),
            'remote_types' => array_values(array_unique(array_filter($remoteTypes))),
            'employment_types' => array_values(array_unique(array_filter($employmentTypes))),
            'salary_min' => $profile->expected_salary_min,
            'salary_max' => $profile->expected_salary_max,
            'experience_years' => $profile->years_experience,
            'terms' => $this->profileSearchTerms($profile),
        ];
    }

    private function profileSearchTerms($profile): array
    {
        $terms = [];
        foreach (['expertise_categories', 'skills', 'tools_used'] as $field) {
            $value = $profile->{$field};
            if (! $value) {
                continue;
            }

            $parsed = is_string($value) ? json_decode($value, true) : $value;
            if (is_array($parsed)) {
                $terms = array_merge($terms, $parsed);
            }
        }

        return array_values(array_unique(array_filter(array_map('trim', $terms))));
    }

    private function applyRecommendationCriteria($query, array $criteria): void
    {
        $hasCriteria = ! empty($criteria['categories'])
            || ! empty($criteria['locations'])
            || ! empty($criteria['remote_types'])
            || ! empty($criteria['employment_types'])
            || ! empty($criteria['terms'])
            || $criteria['salary_min']
            || $criteria['salary_max']
            || $criteria['experience_years'];

        if (! $hasCriteria) {
            return;
        }

        $query->where(function ($q) use ($criteria) {
            if (! empty($criteria['categories'])) {
                $q->orWhereIn('category', $criteria['categories']);
            }
            if (! empty($criteria['remote_types'])) {
                $q->orWhereIn('remote_type', $criteria['remote_types']);
            }
            if (! empty($criteria['employment_types'])) {
                $q->orWhereIn('employment_type', $criteria['employment_types']);
            }
            foreach ($criteria['locations'] as $location) {
                $q->orWhere('location', 'like', '%'.$location.'%');
            }
            foreach ($criteria['terms'] as $term) {
                $q->orWhere('title', 'like', '%'.$term.'%')
                    ->orWhere('summary', 'like', '%'.$term.'%')
                    ->orWhere('description', 'like', '%'.$term.'%')
                    ->orWhere('requirements', 'like', '%'.$term.'%');
            }
            if ($criteria['salary_min']) {
                $q->orWhere('salary_max', '>=', $criteria['salary_min']);
            }
            if ($criteria['salary_max']) {
                $q->orWhere('salary_min', '<=', $criteria['salary_max']);
            }
            if ($criteria['experience_years']) {
                $q->orWhere(function ($experience) use ($criteria) {
                    $experience->whereNull('experience_min_years')
                        ->orWhere('experience_min_years', '<=', $criteria['experience_years']);
                });
            }
        });
    }
}
