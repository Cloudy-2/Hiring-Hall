<?php

namespace App\Http\Controllers\Applicants;

use App\Http\Controllers\Controller;
use App\Models\JobAlertPreference;
use App\Models\JobPosting;
use App\Services\DropdownService;
use Illuminate\Http\Request;

class JobAlertController extends Controller
{
    protected function ensureCandidate(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'applicant') {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request)
    {
        $user = $this->ensureCandidate($request);

        $alerts = JobAlertPreference::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $dropdownOptions = $this->getDropdownOptions();

        return view('applicants.job-alerts.index', [
            'alerts' => $alerts,
            'dropdownOptions' => $dropdownOptions,
        ]);
    }

    public function create(Request $request)
    {
        $this->ensureCandidate($request);
        $dropdownOptions = $this->getDropdownOptions();

        return view('applicants.job-alerts.create', [
            'dropdownOptions' => $dropdownOptions,
        ]);
    }

    public function show(Request $request, JobAlertPreference $jobAlert)
    {
        $user = $this->ensureCandidate($request);
        if ($jobAlert->user_id !== $user->id) {
            abort(403);
        }

        $slugs = array_values(array_filter(explode(',', (string) $request->query('slugs', ''))));

        $query = JobPosting::with('company')
            ->where('status', 'open')
            ->whereNotNull('posted_at');

        if (! empty($slugs)) {
            $query->whereIn('slug', $slugs);
        } else {
            if ($jobAlert->keywords) {
                $keywords = array_values(array_filter(array_map('trim', explode(',', $jobAlert->keywords))));
                if (! empty($keywords)) {
                    $query->where(function ($q) use ($keywords) {
                        foreach ($keywords as $kw) {
                            $q->orWhere('title', 'like', '%'.$kw.'%')
                                ->orWhere('summary', 'like', '%'.$kw.'%')
                                ->orWhere('description', 'like', '%'.$kw.'%')
                                ->orWhere('requirements', 'like', '%'.$kw.'%');
                        }
                    });
                }
            }

            if ($jobAlert->location) {
                $query->where('location', 'like', '%'.$jobAlert->location.'%');
            }

            if ($jobAlert->category) {
                $query->where('category', $jobAlert->category);
            }

            if ($jobAlert->remote_type) {
                $query->where('remote_type', $jobAlert->remote_type);
            }

            if ($jobAlert->employment_type) {
                $query->where('employment_type', $jobAlert->employment_type);
            }
        }

        $jobs = $query->latest('posted_at')->paginate(12)->withQueryString();

        return view('applicants.job-alerts.show', [
            'alert' => $jobAlert,
            'jobs' => $jobs,
            'slugs' => $slugs,
        ]);
    }

    public function store(Request $request)
    {
        $user = $this->ensureCandidate($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'keywords' => ['nullable'], // Accepts string or array
            'location' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'remote_type' => ['nullable', 'string', 'max:50'],
            'employment_type' => ['nullable', 'string', 'max:50'],
            'frequency' => ['required', 'in:daily,weekly'],
            'email_enabled' => ['boolean'],
        ]);

        if (array_key_exists('keywords', $validated) && is_array($validated['keywords'])) {
            $validated['keywords'] = implode(', ', array_filter(array_map('trim', $validated['keywords'])));
        } else {
            $validated['keywords'] = null;
        }

        $validated['user_id'] = $user->id;
        $validated['email_enabled'] = $request->boolean('email_enabled');

        JobAlertPreference::create($validated);

        return redirect()
            ->route('applicant.job-alerts.index')
            ->with('status', 'Job alert created successfully.');
    }

    public function edit(Request $request, JobAlertPreference $jobAlert)
    {
        $user = $this->ensureCandidate($request);
        if ($jobAlert->user_id !== $user->id) {
            abort(403);
        }

        $dropdownOptions = $this->getDropdownOptions();

        return view('applicants.job-alerts.edit', [
            'alert' => $jobAlert,
            'dropdownOptions' => $dropdownOptions,
        ]);
    }

    public function update(Request $request, JobAlertPreference $jobAlert)
    {
        $user = $this->ensureCandidate($request);
        if ($jobAlert->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'keywords' => ['nullable'], // Accepts string or array
            'location' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'remote_type' => ['nullable', 'string', 'max:50'],
            'employment_type' => ['nullable', 'string', 'max:50'],
            'frequency' => ['required', 'in:daily,weekly'],
            'email_enabled' => ['boolean'],
            'is_active' => ['boolean'],
        ]);

        if (array_key_exists('keywords', $validated) && is_array($validated['keywords'])) {
            $validated['keywords'] = implode(', ', array_filter(array_map('trim', $validated['keywords'])));
        } else {
            $validated['keywords'] = null;
        }

        $validated['email_enabled'] = $request->boolean('email_enabled');
        $validated['is_active'] = $request->boolean('is_active');

        $jobAlert->update($validated);

        return redirect()
            ->route('applicant.job-alerts.index')
            ->with('status', 'Job alert updated successfully.');
    }

    public function destroy(Request $request, JobAlertPreference $jobAlert)
    {
        $user = $this->ensureCandidate($request);
        if ($jobAlert->user_id !== $user->id) {
            abort(403);
        }

        $jobAlert->delete();

        return redirect()
            ->route('applicant.job-alerts.index')
            ->with('status', 'Job alert deleted.');
    }

    protected function getDropdownOptions(): array
    {
        $filterOptions = DropdownService::getJobSearchFilterOptions();

        return [
            'categories' => $filterOptions['job_category'] ?? collect(),
            'employment_types' => $filterOptions['employment_type'] ?? collect(),
            'locations' => $filterOptions['location'] ?? collect(),
            'remote_types' => \App\Models\DropdownOption::getOptionsCollection(\App\Models\DropdownOption::TYPE_REMOTE_TYPE),
        ];
    }
}
