<?php

namespace App\Http\Controllers\Applicants;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobPosting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployerBrowseController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->query('keyword', ''));

        $industryOptions = Company::query()
            ->approved()
            ->whereNotNull('industry')
            ->where('industry', '!=', '')
            ->select('industry')
            ->distinct()
            ->orderBy('industry')
            ->pluck('industry')
            ->values();

        $locationOptions = Company::query()
            ->approved()
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->select('location')
            ->distinct()
            ->orderBy('location')
            ->pluck('location')
            ->values();

        return view('modules.employers.index', [
            'keyword' => $keyword,
            'industryOptions' => $industryOptions,
            'locationOptions' => $locationOptions,
        ]);
    }

    public function api(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'keyword' => ['nullable', 'string', 'max:100'],
            'industry' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'],
            'verified' => ['nullable', 'in:all,yes,no'],
            'website' => ['nullable', 'in:all,yes,no'],
            'has_open_jobs' => ['nullable', 'in:all,yes,no'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $keyword = trim((string) ($validated['keyword'] ?? ''));
        $industry = trim((string) ($validated['industry'] ?? ''));
        $location = trim((string) ($validated['location'] ?? ''));
        $verified = (string) ($validated['verified'] ?? 'all');
        $website = (string) ($validated['website'] ?? 'all');
        $hasOpenJobs = (string) ($validated['has_open_jobs'] ?? 'all');

        $companies = Company::query()
            ->approved()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->when($industry !== '', function ($query) use ($industry) {
                $query->where('industry', $industry);
            })
            ->when($location !== '', function ($query) use ($location) {
                $query->where('location', $location);
            })
            ->when($verified === 'yes', function ($query) {
                $query->where('verified', true);
            })
            ->when($verified === 'no', function ($query) {
                $query->where('verified', false);
            })
            ->when($website === 'yes', function ($query) {
                $query->whereNotNull('website')->where('website', '!=', '');
            })
            ->when($website === 'no', function ($query) {
                $query->where(function ($inner) {
                    $inner->whereNull('website')->orWhere('website', '');
                });
            })
            ->when($hasOpenJobs === 'yes', function ($query) {
                $query->whereHas('jobPostings', function ($jobs) {
                    $jobs->where('status', 'open');
                });
            })
            ->when($hasOpenJobs === 'no', function ($query) {
                $query->whereDoesntHave('jobPostings', function ($jobs) {
                    $jobs->where('status', 'open');
                });
            })
            ->orderByDesc('verified')
            ->orderByDesc('rating')
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        $employers = $companies->getCollection()
            ->map(fn (Company $company) => $this->mapCompany($company))
            ->values();

        $cards = $employers
            ->map(fn (array $employer) => view('components.employers.employer-card', ['employer' => $employer])->render())
            ->values();

        return response()->json([
            'data' => $employers,
            'cards' => $cards,
            'keyword' => $keyword,
            'meta' => [
                'page' => $companies->currentPage(),
                'has_more' => $companies->hasMorePages(),
                'total' => $companies->total(),
                'per_page' => $companies->perPage(),
            ],
        ]);
    }

    public function show(Company $company)
    {
        if (! $company->isApproved()) {
            abort(404);
        }

        $jobs = JobPosting::query()
            ->where('company_id', $company->id)
            ->where('status', 'open')
            ->latest('posted_at')
            ->get()
            ->map(function (JobPosting $job) {
                return [
                    'title' => $job->title,
                    'slug' => $job->slug,
                    'location' => $job->location,
                    'employment_type' => $job->employment_type,
                    'salary_min' => $job->salary_min,
                    'salary_max' => $job->salary_max,
                    'salary_currency' => $job->salary_currency,
                    'posted_at' => $job->posted_at,
                    'summary' => $job->summary,
                    'url' => route('jobs.show', $job->slug),
                ];
            })
            ->values();

        return view('modules.employers.show', [
            'employer' => $this->mapCompany($company),
            'jobs' => $jobs,
        ]);
    }

    private function mapCompany(Company $company): array
    {
        $website = $company->website;
        if (! empty($website) && ! preg_match('/^https?:\/\//i', $website)) {
            $website = 'https://'.$website;
        }

        $logo = $company->logo_url;
        if (! empty($logo) && ! preg_match('/^https?:\/\//i', $logo)) {
            $logo = str_replace('\\', '/', ltrim((string) $logo, '/'));

            // Support both modern (/storage/...) and older raw paths (company-logos/...)
            if (Str::startsWith($logo, 'storage/')) {
                $logo = asset($logo);
            } else {
                $logo = asset('storage/'.$logo);
            }
        }

        $openJobsBase = $company->jobPostings()->where('status', 'open');
        $openJobsCount = (clone $openJobsBase)->count();
        $salaryMin = (clone $openJobsBase)->whereNotNull('salary_min')->min('salary_min');
        $salaryMax = (clone $openJobsBase)->whereNotNull('salary_max')->max('salary_max');
        $salaryCurrency = (clone $openJobsBase)
            ->whereNotNull('salary_currency')
            ->orderByDesc('posted_at')
            ->value('salary_currency');
        $featuredRole = (clone $openJobsBase)
            ->orderByDesc('posted_at')
            ->value('title');

        $salaryDisplay = null;
        if (! is_null($salaryMin) || ! is_null($salaryMax)) {
            $currency = $salaryCurrency ?: 'PHP';
            $minText = ! is_null($salaryMin) ? number_format((float) $salaryMin) : null;
            $maxText = ! is_null($salaryMax) ? number_format((float) $salaryMax) : null;

            if ($minText && $maxText) {
                $salaryDisplay = "{$currency} {$minText} - {$maxText}";
            } elseif ($minText) {
                $salaryDisplay = "{$currency} {$minText}";
            } else {
                $salaryDisplay = "{$currency} {$maxText}";
            }
        }

        return [
            'id' => $company->id,
            'slug' => $company->slug,
            'company_name' => $company->name,
            'logo' => $logo,
            'industry' => $company->industry ?: 'General',
            'location' => $company->location ?: 'Location not specified',
            'description' => $company->description ?: 'No company description available yet.',
            'website' => $website,
            'verified' => (bool) $company->verified,
            'rating' => (float) ($company->rating ?? 0),
            'rating_count' => (int) ($company->rating_count ?? 0),
            'featured_role' => $featuredRole,
            'open_jobs_count' => $openJobsCount,
            'salary_display' => $salaryDisplay,
            'detail_url' => route('employers.show', $company->slug),
        ];
    }
}
