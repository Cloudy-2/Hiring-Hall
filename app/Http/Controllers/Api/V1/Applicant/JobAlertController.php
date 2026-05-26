<?php

namespace App\Http\Controllers\Api\V1\Applicant;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Resources\Api\JobAlertPreferenceResource;
use App\Http\Resources\Api\JobPostingResource;
use App\Models\JobAlertPreference;
use App\Models\JobPosting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobAlertController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $paginator = JobAlertPreference::where('user_id', $request->user()->id)
            ->latest()
            ->paginate($this->perPage($request, 20));

        return $this->paginated(
            $paginator,
            JobAlertPreferenceResource::collection($paginator->items())
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validatedPayload($request);
        $validated['user_id'] = $request->user()->id;
        $validated['keywords'] = $this->normalizeKeywords($validated['keywords'] ?? null);
        $validated['email_enabled'] = $request->boolean('email_enabled');
        $validated['is_active'] = $request->boolean('is_active', true);

        $alert = JobAlertPreference::create($validated);

        return $this->created(new JobAlertPreferenceResource($alert), 'Job alert created.');
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $alert = $this->findOwnedAlert($request, $id);
        if (! $alert) {
            return $this->notFound('Job alert not found.');
        }

        return $this->success(new JobAlertPreferenceResource($alert));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $alert = $this->findOwnedAlert($request, $id);
        if (! $alert) {
            return $this->notFound('Job alert not found.');
        }

        $validated = $this->validatedPayload($request);
        $validated['keywords'] = $this->normalizeKeywords($validated['keywords'] ?? null);
        $validated['email_enabled'] = $request->boolean('email_enabled');
        $validated['is_active'] = $request->boolean('is_active');

        $alert->update($validated);

        return $this->success(new JobAlertPreferenceResource($alert->fresh()), 'Job alert updated.');
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $alert = $this->findOwnedAlert($request, $id);
        if (! $alert) {
            return $this->notFound('Job alert not found.');
        }

        $alert->delete();

        return $this->success(null, 'Job alert deleted.');
    }

    public function jobs(Request $request, int $id): JsonResponse
    {
        $alert = $this->findOwnedAlert($request, $id);
        if (! $alert) {
            return $this->notFound('Job alert not found.');
        }

        $query = JobPosting::with('company')
            ->approvedModeration()
            ->where('status', 'active');

        $slugs = array_values(array_filter(explode(',', (string) $request->query('slugs', ''))));
        if (! empty($slugs)) {
            $query->whereIn('slug', $slugs);
        } else {
            $this->applyAlertFilters($query, $alert);
        }

        $paginator = $query
            ->latest('posted_at')
            ->paginate($this->perPage($request, 20));

        return $this->paginated(
            $paginator,
            JobPostingResource::collection($paginator->items())
        );
    }

    private function validatedPayload(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'keywords' => ['nullable'],
            'location' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'remote_type' => ['nullable', 'string', 'max:50'],
            'employment_type' => ['nullable', 'string', 'max:50'],
            'frequency' => ['required', 'in:daily,weekly'],
            'email_enabled' => ['boolean'],
            'is_active' => ['boolean'],
        ]);
    }

    private function normalizeKeywords(mixed $keywords): ?string
    {
        if (is_array($keywords)) {
            $keywords = implode(', ', array_filter(array_map('trim', $keywords)));
        }

        $keywords = trim((string) $keywords);

        return $keywords === '' ? null : $keywords;
    }

    private function findOwnedAlert(Request $request, int $id): ?JobAlertPreference
    {
        return JobAlertPreference::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();
    }

    private function applyAlertFilters($query, JobAlertPreference $alert): void
    {
        $keywords = array_values(array_filter(array_map('trim', explode(',', (string) $alert->keywords))));
        if (! empty($keywords)) {
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('title', 'like', '%'.$keyword.'%')
                        ->orWhere('summary', 'like', '%'.$keyword.'%')
                        ->orWhere('description', 'like', '%'.$keyword.'%')
                        ->orWhere('requirements', 'like', '%'.$keyword.'%');
                }
            });
        }

        if ($alert->location) {
            $query->where('location', 'like', '%'.$alert->location.'%');
        }
        if ($alert->category) {
            $query->where('category', $alert->category);
        }
        if ($alert->remote_type) {
            $query->where('remote_type', $alert->remote_type);
        }
        if ($alert->employment_type) {
            $query->where('employment_type', $alert->employment_type);
        }
    }
}
