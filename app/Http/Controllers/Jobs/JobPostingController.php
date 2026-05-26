<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobPosting;
use App\Models\JobTemplate;
use App\Models\SavedJob;
use App\Services\DropdownService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobPostingController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'keyword' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'industry_type' => ['nullable', 'array'],
            'industry_type.*' => ['string'],
            'filter_location' => ['nullable', 'array'],
            'filter_location.*' => ['string'],
            'recruiter_type' => ['nullable', 'array'],
            'recruiter_type.*' => ['string'],
            'vacancies' => ['nullable', 'array'],
            'vacancies.*' => ['string'],
            'employment_type' => ['nullable', 'array'],
            'employment_type.*' => ['string'],
            'job_role' => ['nullable', 'array'],
            'job_role.*' => ['string'],
        ]);

        $query = JobPosting::with('company')
            ->where('status', 'open'); // Only show open jobs in search

        $user = $request->user();
        $savedJobIds = [];

        if ($user && $user->role === 'applicant') {
            $savedJobIds = SavedJob::where('user_id', $user->id)
                ->pluck('job_posting_id')
                ->all();
        }

        if (! empty($validated['keyword'])) {
            $keyword = $validated['keyword'];

            $query->where(function ($inner) use ($keyword) {
                $inner->where('title', 'like', "%{$keyword}%")
                    ->orWhere('summary', 'like', "%{$keyword}%")
                    ->orWhereHas('company', function ($companyQuery) use ($keyword) {
                        $companyQuery->where('name', 'like', "%{$keyword}%");
                    });
            });
        }

        if (! empty($validated['location'])) {
            $location = $validated['location'];
            $query->where('location', 'like', "%{$location}%");
        }

        if (! empty($validated['category'])) {
            $query->where('category', $validated['category']);
        }

        // OR logic: show jobs that match ANY of the selected industry types
        if (! empty($validated['industry_type'])) {
            $query->whereIn('industry_type', $validated['industry_type']);
        }

        // OR logic: show jobs that match ANY of the selected locations
        if (! empty($validated['filter_location'])) {
            $query->where(function ($q) use ($validated) {
                foreach ($validated['filter_location'] as $loc) {
                    $q->orWhere('location', 'like', "%{$loc}%");
                }
            });
        }

        // OR logic: show jobs that match ANY of the selected recruiter types
        if (! empty($validated['recruiter_type'])) {
            $query->whereIn('recruiter_type', $validated['recruiter_type']);
        }

        // OR logic: show jobs that match ANY of the selected vacancy ranges
        if (! empty($validated['vacancies'])) {
            $query->where(function ($q) use ($validated) {
                foreach ($validated['vacancies'] as $range) {
                    if ($range === '0-10') {
                        $q->orWhereBetween('vacancies', [0, 10]);
                    } elseif ($range === '11-20') {
                        $q->orWhereBetween('vacancies', [11, 20]);
                    } elseif ($range === '20+') {
                        $q->orWhere('vacancies', '>', 20);
                    }
                }
            });
        }

        // OR logic: show jobs that match ANY of the selected employment types
        if (! empty($validated['employment_type'])) {
            $query->whereIn('employment_type', $validated['employment_type']);
        }

        // OR logic: show jobs that match ANY of the selected job roles
        if (! empty($validated['job_role'])) {
            $query->whereIn('title', $validated['job_role']);
        }

        $query->latest('posted_at');

        $perPage = 8;
        $page = max((int) $request->input('page', 1), 1);

        $total = (clone $query)->count();

        $jobsCollection = $query
            ->forPage($page, $perPage)
            ->get()
            ->map(function (JobPosting $job) use ($savedJobIds) {
                $company = $job->company;

                $vacancies = $job->vacancies ?? 1;
                if ($vacancies <= 10) {
                    $vacanciesRange = '0-10';
                } elseif ($vacancies <= 20) {
                    $vacanciesRange = '11-20';
                } else {
                    $vacanciesRange = '20+';
                }

                return [
                    'id' => $job->id,
                    'slug' => $job->slug,
                    'company_id' => $company?->id,
                    'company' => $company?->name ?? 'Janitorial Company',
                    'role' => $job->title,
                    'logo' => $company?->logo_url,
                    'location' => $job->location ?? 'Remote',
                    'location_key' => Str::slug($job->location ?? 'remote'),
                    'industry' => $job->industry_type ?? $company?->industry ?? 'business_process',
                    'established' => $company?->established_year ?? null,
                    'employees' => $company?->employees_count ?? 0,
                    'vacancies' => $vacancies,
                    'vacancies_range' => $vacanciesRange,
                    'salary_min' => $job->salary_min,
                    'salary_max' => $job->salary_max,
                    'salary_currency' => $job->salary_currency,
                    'rating' => $company?->rating ?? 4.5,
                    'rating_count' => $company?->rating_count ?? 0,
                    'verified' => (bool) ($company?->verified ?? false),
                    'category' => $job->category ?? 'Operations VA',
                    'category_key' => Str::lower($job->category ?? 'operations va'),
                    'recruiter_type' => $job->recruiter_type ?? 'direct',
                    'employment_type' => $job->employment_type ?? 'full_time',
                    'is_saved' => in_array($job->id, $savedJobIds, true),
                    'posted_at' => $job->posted_at,
                ];
            });

        $hasMore = ($page * $perPage) < $total;

        // AJAX infinite-scroll request — return JSON
        if ($request->ajax()) {
            return response()->json([
                'jobs' => $jobsCollection->values(),
                'hasMore' => $hasMore,
                'total' => $total,
                'page' => $page,
            ]);
        }

        $jobs = new LengthAwarePaginator(
            $jobsCollection,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ],
        );

        $filterOptions = DropdownService::getJobSearchFilterOptions();

        return \Inertia\Inertia::render('Jobs/Index', [
            'jobs' => $jobs,
            'filters' => $validated,
            'filterOptions' => $filterOptions,
            'hasMore' => $hasMore,
            'total' => $total,
        ]);
    }

    public function show(Request $request, JobPosting $job)
    {
        $job->load(['company', 'applications.applicantProfile.user']);

        $applications = $job->applications->sortByDesc('applied_at');

        $user = $request->user();
        $isSaved = false;
        $hasApplied = false;
        $isCandidate = false;
        $isEmployer = false;
        $isJobClosed = $job->status === 'closed';

        if ($user) {
            if ($user->role === 'applicant') {
                $isCandidate = true;
            } elseif ($user->role === 'employer') {
                $isEmployer = true;
            }
        }

        // If job is closed and user is a virtual assistant, show job closed page
        if ($isJobClosed && $isCandidate) {
            return \Inertia\Inertia::render('Jobs/Closed', [
                'job' => $job,
                'isCandidate' => $isCandidate,
            ]);
        }

        if ($isCandidate) {
            $isSaved = SavedJob::where('job_posting_id', $job->id)
                ->where('user_id', $user->id)
                ->exists();

            $hasApplied = $job->applications
                ->where('user_id', $user->id)
                ->isNotEmpty();
        }

        $isEmployerPreview = $isEmployer;

        $relatedByCategory = JobPosting::with('company')
            ->where('id', '!=', $job->id)
            ->where('status', 'open')
            ->when($job->category, fn ($q) => $q->where('category', $job->category))
            ->latest('posted_at')
            ->limit(4)
            ->get();

        $relatedJobs = $relatedByCategory->count() >= 4
            ? $relatedByCategory
            : $relatedByCategory->merge(
                JobPosting::with('company')
                    ->where('id', '!=', $job->id)
                    ->whereNotIn('id', $relatedByCategory->pluck('id'))
                    ->where('status', 'open')
                    ->latest('posted_at')
                    ->limit(4 - $relatedByCategory->count())
                    ->get()
            )->take(4);

        $deadlinePassed = false;
        $daysLeft = 0;
        if ($job->closes_at) {
            $deadlinePassed = $job->closes_at->isPast();
            $daysLeft = max(0, now()->diffInDays($job->closes_at, false));
        }

        return \Inertia\Inertia::render('Jobs/Show', [
            'job' => $job,
            'applications' => $applications,
            'relatedJobs' => $relatedJobs,
            'isSaved' => $isSaved,
            'hasApplied' => $hasApplied,
            'isCandidate' => $isCandidate,
            'isEmployer' => $isEmployer,
            'isEmployerPreview' => $isEmployerPreview,
            'isJobClosed' => $isJobClosed,
            'deadlinePassed' => $deadlinePassed,
            'daysLeft' => $daysLeft,
        ]);
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        // Get only approved companies owned by this employer
        $companies = Company::approved()
            ->where('user_id', $user->id)
            ->orderBy('name')
            ->get();

        $canPostJobs = $companies->isNotEmpty();
        $hasAnyCompany = Company::where('user_id', $user->id)->exists();
        $hasPendingCompanies = Company::pending()->where('user_id', $user->id)->exists();
        $hasRejectedCompanies = Company::where('user_id', $user->id)
            ->where('verification_status', Company::STATUS_REJECTED)
            ->exists();

        $dropdownOptions = DropdownService::getJobPostingOptions();

        $templateJob = null;
        $selectedTemplateId = null;
        if ($request->has('template')) {
            $template = JobTemplate::accessibleBy($user)->find($request->get('template'));
            if ($template) {
                // Block if template already has an active job posting
                if ($template->isInUse()) {
                    return redirect()->route('employer.templates.index')
                        ->with('status', 'This template is already in use by an active job posting. You cannot post another job from it until the current one is closed.');
                }

                $selectedTemplateId = $template->id;
                $templateJob = (object) [
                    'company_id' => $template->company_id,
                    'title' => $template->title,
                    'location' => $template->location,
                    'category' => $template->category,
                    'industry_type' => $template->industry_type,
                    'recruiter_type' => $template->recruiter_type,
                    'employment_type' => $template->employment_type,
                    'remote_type' => $template->remote_type,
                    'vacancies' => $template->vacancies,
                    'experience_min_years' => $template->experience_min_years,
                    'experience_max_years' => $template->experience_max_years,
                    'salary_min' => $template->salary_min,
                    'salary_max' => $template->salary_max,
                    'salary_currency' => $template->salary_currency,
                    'description' => $template->description,
                    'responsibilities' => $template->responsibilities,
                    'requirements' => $template->requirements,
                    'highlight_benefits' => $template->benefits,
                    'highlight_work_setup' => $template->highlight_work_setup,
                    'highlight_shift_schedule' => $template->highlight_shift_schedule,
                ];
            }
        }

        return view('modules.jobs.create', [
            'companies' => $companies,
            'templateJob' => $templateJob,
            'selectedTemplateId' => $selectedTemplateId,
            'dropdownOptions' => $dropdownOptions,
            'canPostJobs' => $canPostJobs,
            'hasAnyCompany' => $hasAnyCompany,
            'hasPendingCompanies' => $hasPendingCompanies,
            'hasRejectedCompanies' => $hasRejectedCompanies,
        ]);
    }

    public function duplicate(JobPosting $job)
    {
        $user = auth()->user();

        // Get only approved companies owned by this employer
        $companies = Company::approved()
            ->where('user_id', $user->id)
            ->orderBy('name')
            ->get();

        $canPostJobs = $companies->isNotEmpty();
        $hasAnyCompany = Company::where('user_id', $user->id)->exists();
        $hasPendingCompanies = Company::pending()->where('user_id', $user->id)->exists();
        $hasRejectedCompanies = Company::where('user_id', $user->id)
            ->where('verification_status', Company::STATUS_REJECTED)
            ->exists();

        $job->load('company');
        $dropdownOptions = DropdownService::getJobPostingOptions();

        return view('modules.jobs.create', [
            'companies' => $companies,
            'templateJob' => $job,
            'dropdownOptions' => $dropdownOptions,
            'canPostJobs' => $canPostJobs,
            'hasAnyCompany' => $hasAnyCompany,
            'hasPendingCompanies' => $hasPendingCompanies,
            'hasRejectedCompanies' => $hasRejectedCompanies,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $canPostJobs = Company::approved()
            ->where('user_id', $user->id)
            ->exists();

        if (! $canPostJobs) {
            return redirect()
                ->route('employer.companies.index')
                ->with('status', 'You can’t post a job until at least one company is verified.');
        }

        $data = $request->validate([
            'job_template_id' => ['nullable', 'exists:job_templates,id'],
            'company_id' => ['required', 'exists:companies,id'],
            'title' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'employment_type' => ['nullable', 'string', 'max:50'],
            'remote_type' => ['nullable', 'string', 'max:50'],
            'industry_type' => ['nullable', 'string', 'max:100'],
            'recruiter_type' => ['nullable', 'string', 'max:100'],
            'vacancies' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'string', 'max:50'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'salary_currency' => ['nullable', 'string', 'max:3'],
            'experience_min_years' => ['nullable', 'integer', 'min:0'],
            'experience_max_years' => ['nullable', 'integer', 'min:0'],
            'summary' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'array'],
            'requirements.*' => ['nullable', 'string'],
            'responsibilities' => ['nullable', 'array'],
            'responsibilities.*' => ['nullable', 'string'],
            'highlight_work_setup' => ['nullable', 'string', 'max:255'],
            'highlight_shift_schedule' => ['nullable', 'string', 'max:255'],
            'highlight_monthly_rate_min' => ['nullable', 'string', 'max:50'],
            'highlight_monthly_rate_max' => ['nullable', 'string', 'max:50'],
            'highlight_benefits' => ['nullable', 'array'],
            'highlight_benefits.*' => ['nullable', 'string', 'max:255'],
            'posted_at' => ['nullable', 'date'],
            'closes_at' => ['nullable', 'date', 'after_or_equal:posted_at'],
            'logo' => ['nullable', 'image', 'max:4096'],
        ]);

        // Verify company belongs to user and is approved
        $company = Company::where('id', $data['company_id'])
            ->where('user_id', $user->id)
            ->approved()
            ->first();

        if (! $company) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['company_id' => 'Invalid company selected or company not verified.']);
        }

        $slugBase = Str::slug($data['title']);
        $slug = $slugBase;
        $counter = 1;
        while (JobPosting::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$counter++;
        }

        $requirementsLines = $data['requirements'] ?? [];
        if (is_array($requirementsLines)) {
            $requirementsLines = array_values(array_filter(array_map(function ($v) {
                return trim((string) $v);
            }, $requirementsLines), function ($v) {
                return $v !== '';
            }));
        } else {
            $requirementsLines = [];
        }

        $responsibilityLines = $data['responsibilities'] ?? [];
        if (is_array($responsibilityLines)) {
            $responsibilityLines = array_values(array_filter(array_map(function ($v) {
                return trim((string) $v);
            }, $responsibilityLines), function ($v) {
                return $v !== '';
            }));
        } else {
            $responsibilityLines = [];
        }

        $requirementsText = ! empty($requirementsLines) ? implode("\n", $requirementsLines) : null;
        $responsibilitiesText = ! empty($responsibilityLines) ? implode("\n", $responsibilityLines) : null;

        $highlightBenefitsLines = $data['highlight_benefits'] ?? [];
        if (is_array($highlightBenefitsLines)) {
            $highlightBenefitsLines = array_values(array_filter(array_map(function ($v) {
                return trim((string) $v);
            }, $highlightBenefitsLines), function ($v) {
                return $v !== '';
            }));
        } elseif (is_string($highlightBenefitsLines) && $highlightBenefitsLines !== '') {
            $highlightBenefitsLines = preg_split('/\r?\n/', $highlightBenefitsLines);
            $highlightBenefitsLines = array_values(array_filter(array_map('trim', $highlightBenefitsLines), function ($v) {
                return $v !== '';
            }));
        } else {
            $highlightBenefitsLines = [];
        }

        $highlightBenefitsText = ! empty($highlightBenefitsLines) ? implode("\n", $highlightBenefitsLines) : null;

        $highlightMin = isset($data['highlight_monthly_rate_min']) ? trim((string) $data['highlight_monthly_rate_min']) : '';
        $highlightMax = isset($data['highlight_monthly_rate_max']) ? trim((string) $data['highlight_monthly_rate_max']) : '';

        if ($highlightMin !== '' && $highlightMax !== '') {
            $highlightMonthly = $highlightMin.' - '.$highlightMax;
        } elseif ($highlightMin !== '') {
            $highlightMonthly = $highlightMin;
        } elseif ($highlightMax !== '') {
            $highlightMonthly = $highlightMax;
        } else {
            $highlightMonthly = null;
        }

        // Server-side: block duplicate posting from an in-use template
        $templateId = $data['job_template_id'] ?? null;
        if ($templateId) {
            $usedTemplate = JobTemplate::find($templateId);
            if ($usedTemplate && $usedTemplate->isInUse()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['job_template_id' => 'This template already has an active job posting. Close the existing job first.']);
            }
        }

        $job = JobPosting::create([
            'company_id' => $data['company_id'],
            'job_template_id' => $templateId,
            'title' => Str::headline($data['title']),
            'slug' => $slug,
            'location' => $data['location'] ?? null,
            'category' => $data['category'] ?? null,
            'industry_type' => $data['industry_type'] ?? null,
            'recruiter_type' => $data['recruiter_type'] ?? null,
            'employment_type' => $data['employment_type'] ?? null,
            'remote_type' => $data['remote_type'] ?? null,
            'vacancies' => $data['vacancies'] ?? 1,
            'status' => $data['status'] ?? 'open',
            'salary_min' => $data['salary_min'] ?? null,
            'salary_max' => $data['salary_max'] ?? null,
            'salary_currency' => strtoupper($data['salary_currency'] ?? 'USD'),
            'experience_min_years' => $data['experience_min_years'] ?? null,
            'experience_max_years' => $data['experience_max_years'] ?? null,
            'summary' => null,
            'description' => $data['description'] ?? null,
            'requirements' => $requirementsText,
            'responsibilities' => $responsibilitiesText,
            'highlight_work_setup' => $data['highlight_work_setup'] ?? null,
            'highlight_shift_schedule' => $data['highlight_shift_schedule'] ?? null,
            'highlight_monthly_rate' => $highlightMonthly,
            'highlight_benefits' => $highlightBenefitsText,
            'posted_at' => $data['posted_at'] ?? now(),
            'closes_at' => $data['closes_at'] ?? null,
        ]);

        // Update template status to active
        if ($templateId) {
            $usedTemplate = $usedTemplate ?? JobTemplate::find($templateId);
            if ($usedTemplate) {
                $usedTemplate->update(['status' => JobTemplate::STATUS_ACTIVE]);
            }
        }

        if ($request->hasFile('logo')) {
            $company = Company::find($data['company_id']);
            if ($company) {
                $path = $request->file('logo')->store('company-logos', 'public');
                $company->logo_url = Storage::url($path);
                $company->save();
            }
        }

        return redirect()
            ->route('jobs.show', $job)
            ->with('job_posted', true);
    }
}
