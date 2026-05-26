<?php

namespace App\Http\Controllers\Applicants;

use App\Http\Controllers\Controller;
use App\Models\ApplicantProfile;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\SavedJob;
use App\Services\DropdownService;
use Illuminate\Http\Request;

class ApplicantPanelController extends Controller
{
    protected function ensureCandidate(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'applicant') {
            abort(403);
        }

        return $user;
    }

    public function dashboard(Request $request)
    {
        $user = $this->ensureCandidate($request);

        $profile = ApplicantProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['display_name' => $user->name],
        );

        $applications = JobApplication::with('jobPosting.company')
            ->where('user_id', $user->id)
            ->latest('updated_at')
            ->take(5)
            ->get();

        $recommendedJobs = $this->getRecommendedJobsQueryForUser($user)->take(4)->get();

        // Count stats for dashboard cards
        $allApplications = JobApplication::where('user_id', $user->id);
        $appliedCount = (clone $allApplications)->whereIn('status', ['applied', 'submitted', 'under_review', 'viewed', 'application_viewed', 'shortlisted'])->count();
        $underReviewCount = (clone $allApplications)->where('status', 'under_review')->count();
        $interviewingCount = \App\Models\Interview::where('applicant_id', $user->id)->upcoming()->count();
        $offeredCount = (clone $allApplications)->whereIn('status', ['offered'])->count();
        $hiredCount = (clone $allApplications)->whereIn('status', ['hired', 'accepted'])->count();
        $declinedCount = (clone $allApplications)->whereIn('status', ['declined', 'rejected', 'not_selected'])->count();
        $savedJobIds = SavedJob::where('user_id', $user->id)->pluck('job_posting_id')->all();
        $savedJobsCount = count($savedJobIds);

        return \Inertia\Inertia::render('Applicant/Dashboard', [
            'user' => $user,
            'profile' => $profile,
            'applications' => $applications,
            'recommendedJobs' => $recommendedJobs,
            'appliedCount' => $appliedCount,
            'underReviewCount' => $underReviewCount,
            'interviewingCount' => $interviewingCount,
            'offeredCount' => $offeredCount,
            'hiredCount' => $hiredCount,
            'declinedCount' => $declinedCount,
            'savedJobs' => $savedJobsCount,
            'savedJobIds' => $savedJobIds,
        ]);
    }

    public function getQuickStats(Request $request)
    {
        $user = $request->user();
        if (! $user || $user->role !== 'applicant') {
            return response()->json([], 403);
        }

        $allApplications = JobApplication::where('user_id', $user->id);

        return response()->json([
            'applied' => (clone $allApplications)->whereIn('status', ['applied', 'submitted'])->count(),
            'declined' => (clone $allApplications)->whereIn('status', ['declined', 'rejected', 'not_selected'])->count(),
            'under_review' => (clone $allApplications)->where('status', 'under_review')->count(),
            'saved' => SavedJob::where('user_id', $user->id)->count(),
        ]);
    }

    public function recommendedJobs(Request $request)
    {
        $user = $this->ensureCandidate($request);

        $profile = ApplicantProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['display_name' => $user->name],
        );

        $savedJobs = SavedJob::with(['jobPosting.company'])
            ->where('user_id', $user->id)
            ->latest('saved_at')
            ->paginate(5, ['*'], 'saved_page');

        $jobIds = $savedJobs->pluck('job_posting_id')->filter()->all();
        $applicationsByJobId = [];
        if (! empty($jobIds)) {
            $applicationsByJobId = JobApplication::where('user_id', $user->id)
                ->whereIn('job_posting_id', $jobIds)
                ->pluck('status', 'job_posting_id')
                ->all();
        }

        $recommendedQuery = $this->getRecommendedJobsQueryForUser($user);

        if ($search = $request->input('search')) {
            $recommendedQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('company', fn ($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        $recommendedJobs = $recommendedQuery->take(20)->get();

        if ($recommendedJobs->count() < 20 && ! $search) {
            $appliedJobIds = JobApplication::where('user_id', $user->id)->pluck('job_posting_id')->all();
            $existingIds = $recommendedJobs->pluck('id')->merge($appliedJobIds)->all();
            $additionalJobs = JobPosting::with('company')
                ->when(! empty($existingIds), fn ($q) => $q->whereNotIn('id', $existingIds))
                ->latest('posted_at')
                ->take(20 - $recommendedJobs->count())
                ->get();
            $recommendedJobs = $recommendedJobs->merge($additionalJobs);
        }
        $savedJobIds = SavedJob::where('user_id', $user->id)->pluck('job_posting_id')->all();

        return \Inertia\Inertia::render('Applicant/RecommendedJobs', [
            'user' => $user,
            'profile' => $profile,
            'savedJobs' => $savedJobs,
            'applicationsByJobId' => $applicationsByJobId,
            'recommendedJobs' => $recommendedJobs,
            'savedJobIds' => $savedJobIds,
            'allLocations' => $allLocations,
            'allJobTypes' => $allJobTypes,
        ]);
    }

    protected function getRecommendedJobsQueryForUser($user)
    {
        // Fetch user's profile data for intelligent recommendations
        $profile = ApplicantProfile::where('user_id', $user->id)->first();

        // Get applied jobs to exclude them and extract history patterns
        $applications = JobApplication::with('jobPosting.company')
            ->where('user_id', $user->id)
            ->latest('applied_at')
            ->take(20)
            ->get();

        $appliedJobIds = $applications->pluck('job_posting_id')->filter()->all();

        // Extract history-based criteria
        $categories = [];
        $locations = [];
        $remoteTypes = [];
        $companyIds = [];

        foreach ($applications as $app) {
            if ($app->jobPosting) {
                if ($app->jobPosting->category) {
                    $categories[] = $app->jobPosting->category;
                }
                if ($app->jobPosting->location) {
                    $locations[] = $app->jobPosting->location;
                }
                if ($app->jobPosting->remote_type) {
                    $remoteTypes[] = $app->jobPosting->remote_type;
                }
                if ($app->jobPosting->company_id) {
                    $companyIds[] = $app->jobPosting->company_id;
                }
            }
        }

        $categories = array_unique($categories);
        $locations = array_unique($locations);
        $remoteTypes = array_unique($remoteTypes);
        $companyIds = array_unique($companyIds);

        // Extract profile-based criteria
        $profileWorkMode = $profile?->work_mode;
        $profileJobType = $profile?->job_type;
        $profileYearsExp = $profile?->years_experience;
        $profileAvailability = $profile?->availability;
        $profileSalaryMin = $profile?->expected_salary_min;
        $profileSalaryMax = $profile?->expected_salary_max;
        $profileSalaryCurrency = $profile?->salary_currency ?? 'PHP';

        // Parse profile location to extract region info
        $profileLocation = null;
        $profileRegion = null;
        if ($profile?->location) {
            $profileLocation = $profile->location;
            // Location format: "Barangay, City, Province, Region, Philippines"
            $parts = array_map('trim', explode(',', $profileLocation));
            if (count($parts) >= 4) {
                $profileRegion = $parts[count($parts) - 2]; // Region is second to last
            }
        }

        // Parse specializations, skills, and tools for text matching
        $specializations = [];
        $skills = [];
        $tools = [];

        if ($profile?->expertise_categories) {
            $parsed = is_string($profile->expertise_categories) ?
                json_decode($profile->expertise_categories, true) :
                $profile->expertise_categories;
            $specializations = is_array($parsed) ? $parsed : [];
        }

        if ($profile?->skills) {
            $parsed = is_string($profile->skills) ?
                json_decode($profile->skills, true) :
                $profile->skills;
            $skills = is_array($parsed) ? $parsed : [];
        }

        if ($profile?->tools_used) {
            $parsed = is_string($profile->tools_used) ?
                json_decode($profile->tools_used, true) :
                $profile->tools_used;
            $tools = is_array($parsed) ? $parsed : [];
        }

        // Combine all searchable terms for skills matching
        $searchTerms = array_merge($specializations, $skills, $tools);
        $searchTerms = array_filter(array_map('trim', $searchTerms));

        $recommendedQuery = JobPosting::with('company')
            ->withCount('applications')
            ->when(! empty($appliedJobIds), fn ($q) => $q->whereNotIn('id', $appliedJobIds));

        // Build comprehensive WHERE clause combining history and profile data
        $recommendedQuery->where(function ($q) use (
            $categories,
            $locations,
            $remoteTypes,
            $companyIds,
            $profileWorkMode,
            $profileJobType,
            $profileYearsExp,
            $profileSalaryMin,
            $profileSalaryMax,
            $profileRegion,
            $searchTerms
        ) {
            // HISTORY-BASED MATCHING
            if (! empty($categories)) {
                $q->orWhereIn('category', $categories);
            }
            if (! empty($companyIds)) {
                $q->orWhereIn('company_id', $companyIds);
            }
            if (! empty($locations)) {
                $q->orWhere(function ($locQ) use ($locations) {
                    foreach ($locations as $loc) {
                        $locQ->orWhere('location', 'like', '%'.$loc.'%');
                    }
                });
            }
            if (! empty($remoteTypes)) {
                $q->orWhereIn('remote_type', $remoteTypes);
            }

            // PROFILE-BASED MATCHING
            // Match work mode preference
            if ($profileWorkMode) {
                $q->orWhere('remote_type', $profileWorkMode);
            }

            // Match job type preference
            if ($profileJobType) {
                $q->orWhere('employment_type', $profileJobType);
            }

            // Match preferred location/region
            if ($profileRegion) {
                $q->orWhere('location', 'like', '%'.$profileRegion.'%');
            }

            // Match salary range (job salary range should overlap with user's expectations)
            if ($profileSalaryMin !== null || $profileSalaryMax !== null) {
                $q->orWhere(function ($salQ) use ($profileSalaryMin, $profileSalaryMax) {
                    // Include jobs without salary specified OR within user's range
                    $salQ->whereNull('salary_min')
                        ->orWhereNull('salary_max');

                    // Also include jobs where there's salary overlap
                    if ($profileSalaryMin !== null && $profileSalaryMax !== null) {
                        $salQ->orWhereBetween('salary_min', [$profileSalaryMin * 0.8, $profileSalaryMax * 1.2]);
                    } elseif ($profileSalaryMax !== null) {
                        $salQ->orWhere('salary_min', '<=', $profileSalaryMax * 1.2);
                    } elseif ($profileSalaryMin !== null) {
                        $salQ->orWhere('salary_max', '>=', $profileSalaryMin * 0.8);
                    }
                });
            }

            // Match experience level
            if ($profileYearsExp !== null) {
                $q->orWhere(function ($expQ) use ($profileYearsExp) {
                    // Include jobs with no experience requirement
                    $expQ->whereNull('experience_min_years')
                        ->orWhereNull('experience_max_years');

                    // Include jobs where user's experience fits the range
                    $expQ->orWhere(function ($fitQ) use ($profileYearsExp) {
                        $fitQ->where(function ($minQ) use ($profileYearsExp) {
                            $minQ->whereNull('experience_min_years')
                                ->orWhere('experience_min_years', '<=', $profileYearsExp);
                        })->where(function ($maxQ) use ($profileYearsExp) {
                            $maxQ->whereNull('experience_max_years')
                                ->orWhere('experience_max_years', '>=', $profileYearsExp);
                        });
                    });
                });
            }

            // Match skills/specializations against job description and requirements
            if (! empty($searchTerms)) {
                $q->orWhere(function ($skillQ) use ($searchTerms) {
                    foreach ($searchTerms as $term) {
                        $searchPattern = '%'.str_replace('%', '\\%', $term).'%';
                        $skillQ->orWhere('description', 'like', $searchPattern)
                            ->orWhere('requirements', 'like', $searchPattern)
                            ->orWhere('title', 'like', $searchPattern);
                    }
                });
            }
        });

        return $recommendedQuery;
    }

    public function applications(Request $request)
    {
        $user = $this->ensureCandidate($request);

        $statusFilter = $request->input('status');
        $search = $request->input('q');

        $baseQuery = JobApplication::with('jobPosting.company')
            ->where('user_id', $user->id);

        if ($statusFilter) {
            if ($statusFilter === 'applied') {
                $baseQuery->whereIn('status', ['applied', 'submitted']);
            } elseif ($statusFilter === 'application_viewed') {
                $baseQuery->whereIn('status', ['application_viewed', 'viewed']);
            } elseif ($statusFilter === 'declined') {
                $baseQuery->whereIn('status', ['not_selected', 'no_longer_under_consideration', 'declined', 'rejected']);
            } elseif ($statusFilter === 'accepted') {
                $baseQuery->whereIn('status', ['accepted', 'hired', 'offered']);
            } else {
                $baseQuery->where('status', $statusFilter);
            }
        }

        if ($search) {
            $baseQuery->whereHas('jobPosting', function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhereHas('company', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        $applications = (clone $baseQuery)
            ->latest('updated_at')
            ->paginate(10)
            ->withQueryString();

        $allQuery = JobApplication::where('user_id', $user->id);

        $totalApplied = (clone $allQuery)->count();

        $activeCount = (clone $allQuery)
            ->whereIn('status', ['applied', 'submitted'])
            ->count();

        $underReviewCount = (clone $allQuery)
            ->where('status', 'under_review')
            ->count();

        $viewedCount = (clone $allQuery)
            ->whereIn('status', ['application_viewed', 'viewed'])
            ->count();

        $declinedCount = (clone $allQuery)
            ->whereIn('status', ['not_selected', 'no_longer_under_consideration', 'declined', 'rejected'])
            ->count();

        $closedCount = (clone $allQuery)
            ->where('status', 'closed')
            ->count();

        $acceptedCount = (clone $allQuery)
            ->whereIn('status', ['accepted', 'hired', 'offered'])
            ->count();

        $now = now();
        $thisMonthStart = $now->copy()->startOfMonth();
        $lastMonthStart = $thisMonthStart->copy()->subMonth();
        $lastMonthEnd = $thisMonthStart->copy()->subSecond();

        $lastMonthBase = (clone $allQuery)
            ->whereNotNull('applied_at')
            ->whereBetween('applied_at', [$lastMonthStart, $lastMonthEnd]);

        $thisMonthBase = (clone $allQuery)
            ->whereNotNull('applied_at')
            ->where('applied_at', '>=', $thisMonthStart);

        $totalAppliedLast = (clone $lastMonthBase)->count();
        $totalAppliedThis = (clone $thisMonthBase)->count();
        $totalAppliedTrend = $totalAppliedThis <=> $totalAppliedLast;
        $totalAppliedDelta = max($totalAppliedThis - $totalAppliedLast, 0);

        $underReviewLast = (clone $lastMonthBase)
            ->where('status', 'under_review')
            ->count();
        $underReviewThis = (clone $thisMonthBase)
            ->where('status', 'under_review')
            ->count();
        $underReviewTrend = $underReviewThis <=> $underReviewLast;
        $underReviewDelta = max($underReviewThis - $underReviewLast, 0);

        $viewedLast = (clone $lastMonthBase)
            ->whereIn('status', ['application_viewed', 'viewed'])
            ->count();
        $viewedThis = (clone $thisMonthBase)
            ->whereIn('status', ['application_viewed', 'viewed'])
            ->count();
        $viewedTrend = $viewedThis <=> $viewedLast;
        $viewedDelta = max($viewedThis - $viewedLast, 0);

        $declinedLast = (clone $lastMonthBase)
            ->whereIn('status', ['not_selected', 'no_longer_under_consideration'])
            ->count();
        $declinedThis = (clone $thisMonthBase)
            ->whereIn('status', ['not_selected', 'no_longer_under_consideration'])
            ->count();
        $declinedTrend = $declinedThis <=> $declinedLast;
        $declinedDelta = max($declinedThis - $declinedLast, 0);

        $closedLast = (clone $lastMonthBase)
            ->where('status', 'closed')
            ->count();
        $closedThis = (clone $thisMonthBase)
            ->where('status', 'closed')
            ->count();
        $closedTrend = $closedThis <=> $closedLast;
        $closedDelta = max($closedThis - $closedLast, 0);

        $acceptedLast = (clone $lastMonthBase)
            ->where('status', 'accepted')
            ->count();
        $acceptedThis = (clone $thisMonthBase)
            ->where('status', 'accepted')
            ->count();
        $acceptedTrend = $acceptedThis <=> $acceptedLast;
        $acceptedDelta = max($acceptedThis - $acceptedLast, 0);

        return \Inertia\Inertia::render('Applicant/Applications', [
            'user' => $user,
            'applications' => $applications,
            'totalApplied' => $totalApplied,
            'appliedCount' => $activeCount,
            'underReviewCount' => $underReviewCount,
            'viewedCount' => $viewedCount,
            'acceptedCount' => $acceptedCount,
            'declinedCount' => $declinedCount,
            'closedCount' => $closedCount,
            'totalAppliedTrend' => $totalAppliedTrend,
            'underReviewTrend' => $underReviewTrend,
            'viewedTrend' => $viewedTrend,
            'acceptedTrend' => $acceptedTrend,
            'declinedTrend' => $declinedTrend,
            'closedTrend' => $closedTrend,
            'totalAppliedDelta' => $totalAppliedDelta,
            'underReviewDelta' => $underReviewDelta,
            'viewedDelta' => $viewedDelta,
            'acceptedDelta' => $acceptedDelta,
            'declinedDelta' => $declinedDelta,
            'closedDelta' => $closedDelta,
            'statusFilter' => $statusFilter,
            'search' => $search,
        ]);
    }

    public function applicationHistory(Request $request)
    {
        $user = $this->ensureCandidate($request);

        $search = $request->input('q');
        $statusFilter = $request->input('status');

        $historyStatuses = ['withdrawn', 'cancelled', 'declined', 'rejected', 'not_selected', 'closed', 'expired'];

        $query = JobApplication::withTrashed()
            ->with('jobPosting.company')
            ->where('user_id', $user->id)
            ->where(function ($q) use ($historyStatuses) {
                $q->whereNotNull('deleted_at')
                    ->orWhereIn('status', $historyStatuses);
            });

        if ($search) {
            $query->whereHas('jobPosting', function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhereHas('company', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%'.$search.'%');
                    });
            });
        }

        if ($statusFilter === 'withdrawn') {
            $query->whereIn('status', ['withdrawn', 'cancelled'])->orWhereNotNull('deleted_at');
        } elseif ($statusFilter === 'not_selected') {
            $query->whereIn('status', ['declined', 'rejected', 'not_selected']);
        } elseif ($statusFilter === 'closed') {
            $query->whereIn('status', ['closed', 'expired']);
        }

        $applications = $query
            ->latest('updated_at')
            ->paginate(5)
            ->withQueryString();

        $baseQuery = JobApplication::withTrashed()
            ->where('user_id', $user->id)
            ->where(function ($q) use ($historyStatuses) {
                $q->whereNotNull('deleted_at')
                    ->orWhereIn('status', $historyStatuses);
            });

        $withdrawnCount = (clone $baseQuery)->whereIn('status', ['withdrawn', 'cancelled'])->count();
        $notSelectedCount = (clone $baseQuery)->whereIn('status', ['declined', 'rejected', 'not_selected'])->count();

        // Everything else in history that is not explicitly withdrawn or rejected is considered a closed position outcome
        $closedCount = $applications->total() - $withdrawnCount - $notSelectedCount;
        if ($closedCount < 0) {
            $closedCount = 0;
        }

        return \Inertia\Inertia::render('Applicant/ApplicationsHistory', [
            'user' => $user,
            'applications' => $applications,
            'search' => $search,
            'withdrawnCount' => $withdrawnCount,
            'notSelectedCount' => $notSelectedCount,
            'closedCount' => $closedCount,
        ]);
    }

    public function destroyHistory(Request $request, $applicationId)
    {
        $user = $this->ensureCandidate($request);

        $application = JobApplication::withTrashed()
            ->where('user_id', $user->id)
            ->where('id', $applicationId)
            ->first();

        if (! $application) {
            return redirect()->route('applicant.applications.history')
                ->with('error', 'Application not found.');
        }

        $application->delete();

        return redirect()->route('candidate.applications.history')
            ->with('status', 'Application history deleted.');
    }

    public function showProfile(Request $request)
    {
        $user = $this->ensureCandidate($request);

        $profile = ApplicantProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['display_name' => $user->name],
        );

        return \Inertia\Inertia::render('Applicant/Profile', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function editProfile(Request $request)
    {
        $user = $this->ensureCandidate($request);

        $profile = ApplicantProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['display_name' => $user->name],
        );

        $dropdownOptions = DropdownService::getApplicantProfileOptions();

        return \Inertia\Inertia::render('Applicant/ProfileEdit', [
            'user' => $user,
            'profile' => $profile,
            'dropdownOptions' => $dropdownOptions,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $this->ensureCandidate($request);

        $profile = ApplicantProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['display_name' => $user->name],
        );

        $data = $request->validate([
            'display_name' => ['nullable', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'work_mode' => ['nullable', 'string', 'max:100'],
            'degree' => ['nullable', 'string', 'max:255'],
            'years_experience' => ['nullable', 'integer', 'min:0'],
            'availability' => ['nullable', 'string', 'max:100'],
            'job_type' => ['nullable', 'string', 'max:100'],
            'expected_salary_min' => ['nullable', 'numeric', 'min:0'],
            'expected_salary_max' => ['nullable', 'numeric', 'min:0'],
            'salary_currency' => ['nullable', 'string', 'max:3'],
            'headline' => ['nullable', 'string'],
            'about' => ['nullable', 'string'],
            'career_objective' => ['nullable', 'string'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'remove_cv' => ['nullable', 'string'],

            'education' => ['nullable', 'array'],
            'education.*.course' => ['nullable', 'string', 'max:255'],
            'education.*.school' => ['nullable', 'string', 'max:255'],
            'education.*.location' => ['nullable', 'string', 'max:255'],
            'education.*.dates' => ['nullable', 'string', 'max:255'],

            'certifications' => ['nullable', 'array'],
            'certifications.*.title' => ['nullable', 'string', 'max:255'],
            'certifications.*.provider' => ['nullable', 'string', 'max:255'],

            'achievements' => ['nullable', 'array'],
            'achievements.*' => ['nullable', 'string'],

            'activities' => ['nullable', 'array'],
            'activities.*' => ['nullable', 'string'],

            'references' => ['nullable', 'array'],
            'references.*.name' => ['nullable', 'string', 'max:255'],
            'references.*.designation' => ['nullable', 'string', 'max:255'],
            'references.*.company' => ['nullable', 'string', 'max:255'],
            'references.*.mobile' => ['nullable', 'string', 'max:100'],
            'references.*.email' => ['nullable', 'string', 'max:255'],
            'references.*.location' => ['nullable', 'string', 'max:255'],

            'experience_position' => ['nullable', 'string', 'max:255'],
            'experience_company' => ['nullable', 'string', 'max:255'],
            'experience_location' => ['nullable', 'string', 'max:255'],
            'experience_start' => ['nullable', 'date'],
            'experience_end' => ['nullable', 'date'],
            'experience_current' => ['nullable', 'boolean'],
            'experience_responsibilities' => ['nullable', 'array'],
            'experience_responsibilities.*' => ['nullable', 'string'],

            'skills' => ['nullable', 'array'],
            'skills.*' => ['nullable', 'string'],
            'tools_used' => ['nullable', 'array'],
            'tools_used.*' => ['nullable', 'string'],
            'languages' => ['nullable', 'array'],
            'languages.*' => ['nullable', 'string'],

            'expertise_categories' => ['nullable', 'array'],
            'expertise_categories.*' => ['nullable', 'string', 'max:100'],
        ]);

        $profileData = $data;
        unset(
            $profileData['education'],
            $profileData['certifications'],
            $profileData['achievements'],
            $profileData['activities'],
            $profileData['references'],
            $profileData['experience_position'],
            $profileData['experience_company'],
            $profileData['experience_location'],
            $profileData['experience_start'],
            $profileData['experience_end'],
            $profileData['experience_responsibilities'],
            $profileData['skills'],
            $profileData['tools_used'],
            $profileData['languages'],
            $profileData['expertise_categories'],
        );

        $profile->fill($profileData);

        $educationEntries = $data['education'] ?? [];
        if (is_array($educationEntries)) {
            $educationEntries = array_values(array_filter($educationEntries, function ($row) {
                if (! is_array($row)) {
                    return false;
                }
                $joined = trim(implode('', array_map(function ($v) {
                    return (string) $v;
                }, $row)));

                return $joined !== '';
            }));
        }
        $profile->education_details = ! empty($educationEntries) ? json_encode($educationEntries) : null;

        $certEntries = $data['certifications'] ?? [];
        if (is_array($certEntries)) {
            $certEntries = array_values(array_filter($certEntries, function ($row) {
                if (! is_array($row)) {
                    return false;
                }
                $joined = trim(implode('', array_map(function ($v) {
                    return (string) $v;
                }, $row)));

                return $joined !== '';
            }));
        }
        $profile->certifications = ! empty($certEntries) ? json_encode($certEntries) : null;

        $achievementLines = $data['achievements'] ?? [];
        if (is_array($achievementLines)) {
            $achievementLines = array_values(array_filter(array_map(function ($v) {
                return trim((string) $v);
            }, $achievementLines), function ($v) {
                return $v !== '';
            }));
        }
        $profile->key_achievements = ! empty($achievementLines) ? json_encode($achievementLines) : null;

        $activityLines = $data['activities'] ?? [];
        if (is_array($activityLines)) {
            $activityLines = array_values(array_filter(array_map(function ($v) {
                return trim((string) $v);
            }, $activityLines), function ($v) {
                return $v !== '';
            }));
        }
        $profile->activities_interests = ! empty($activityLines) ? json_encode($activityLines) : null;

        $referenceEntries = $data['references'] ?? [];
        if (is_array($referenceEntries)) {
            $referenceEntries = array_values(array_filter($referenceEntries, function ($row) {
                if (! is_array($row)) {
                    return false;
                }
                $joined = trim(implode('', array_map(function ($v) {
                    return (string) $v;
                }, $row)));

                return $joined !== '';
            }));
        }
        $profile->references_block = ! empty($referenceEntries) ? json_encode($referenceEntries) : null;

        $skillLines = $data['skills'] ?? [];
        if (is_array($skillLines)) {
            $skillLines = array_values(array_filter(array_map(function ($v) {
                return trim((string) $v);
            }, $skillLines), function ($v) {
                return $v !== '';
            }));
        }
        $profile->skills = ! empty($skillLines) ? json_encode($skillLines) : null;

        $toolsUsedLines = $data['tools_used'] ?? [];
        if (is_array($toolsUsedLines)) {
            $toolsUsedLines = array_values(array_filter(array_map(function ($v) {
                return trim((string) $v);
            }, $toolsUsedLines), function ($v) {
                return $v !== '';
            }));
        }
        $profile->tools_used = ! empty($toolsUsedLines) ? json_encode($toolsUsedLines) : null;

        $languageLines = $data['languages'] ?? [];
        if (is_array($languageLines)) {
            $languageLines = array_values(array_filter(array_map(function ($v) {
                return trim((string) $v);
            }, $languageLines), function ($v) {
                return $v !== '';
            }));
        }
        $profile->languages = ! empty($languageLines) ? json_encode($languageLines) : null;

        $expertiseCategories = $data['expertise_categories'] ?? [];
        if (is_array($expertiseCategories)) {
            $expertiseCategories = array_values(array_filter(array_map(function ($v) {
                return trim((string) $v);
            }, $expertiseCategories), function ($v) {
                return $v !== '';
            }));
        }
        $profile->expertise_categories = ! empty($expertiseCategories) ? json_encode($expertiseCategories) : null;

        $expResponsibilities = $data['experience_responsibilities'] ?? [];
        if (is_array($expResponsibilities)) {
            $expResponsibilities = array_values(array_filter(array_map(function ($v) {
                return trim((string) $v);
            }, $expResponsibilities), function ($v) {
                return $v !== '';
            }));
        }

        $hasExperienceHeader = ! empty($data['experience_position'])
            || ! empty($data['experience_company'])
            || ! empty($data['experience_location'])
            || ! empty($data['experience_start'])
            || ! empty($data['experience_end']);

        if ($hasExperienceHeader || ! empty($expResponsibilities)) {
            $endDate = $data['experience_end'] ?? null;
            if (! empty($data['experience_current'])) {
                $endDate = null;
            }
            $experience = [
                'position' => $data['experience_position'] ?? null,
                'company' => $data['experience_company'] ?? null,
                'location' => $data['experience_location'] ?? null,
                'start_date' => $data['experience_start'] ?? null,
                'end_date' => $endDate,
                'responsibilities' => $expResponsibilities,
            ];
            $profile->experience_overview = json_encode($experience);
        } else {
            $profile->experience_overview = null;
        }

        if ($request->hasFile('cv_file')) {
            if ($profile->cv_path && \Storage::exists($profile->cv_path)) {
                \Storage::delete($profile->cv_path);
            }
            $cvPath = $request->file('cv_file')->store('candidate-cvs', 'public');
            $profile->cv_path = $cvPath;
        }

        if ($request->input('remove_cv') === '1' && $profile->cv_path) {
            if (\Storage::exists($profile->cv_path)) {
                \Storage::delete($profile->cv_path);
            }
            $profile->cv_path = null;
        }

        $profile->save();

        $redirectRoute = request()->input('_redirect', 'applicant.profile.edit');
        $validRoutes = ['applicant.profile.edit', 'applicant.profile.show'];

        return redirect()->route(in_array($redirectRoute, $validRoutes) ? $redirectRoute : 'applicant.profile.edit')
            ->with('status', 'Profile updated.');
    }

    public function savedJobs(Request $request)
    {
        $user = $this->ensureCandidate($request);

        $savedJobs = SavedJob::with(['jobPosting.company'])
            ->where('user_id', $user->id)
            ->latest('saved_at')
            ->paginate(5);

        $jobIds = $savedJobs->pluck('job_posting_id')->filter()->all();

        $applicationsByJobId = [];

        if (! empty($jobIds)) {
            $applicationsByJobId = JobApplication::where('user_id', $user->id)
                ->whereIn('job_posting_id', $jobIds)
                ->pluck('status', 'job_posting_id')
                ->all();
        }

        return \Inertia\Inertia::render('Applicant/SavedJobs', [
            'user' => $user,
            'savedJobs' => $savedJobs,
            'applicationsByJobId' => $applicationsByJobId,
        ]);
    }

    public function viewCV(Request $request)
    {
        $user = $this->ensureCandidate($request);

        $profile = ApplicantProfile::where('user_id', $user->id)->first();

        if (! $profile || empty($profile->cv_path)) {
            abort(404, 'CV not found');
        }

        if (! \Illuminate\Support\Facades\Storage::disk('public')->exists($profile->cv_path)) {
            abort(404, 'CV file not found');
        }

        $extension = pathinfo($profile->cv_path, PATHINFO_EXTENSION);
        $displayName = $profile->display_name ?? $user->name;
        $cleanName = preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $displayName);
        $filename = $cleanName.'_CV.'.$extension;

        return \Illuminate\Support\Facades\Storage::disk('public')->response($profile->cv_path, $filename, [
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }

    public function bulkWithdrawApplications(Request $request)
    {
        $user = $this->ensureCandidate($request);
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'No applications selected'], 400);
        }

        JobApplication::where('user_id', $user->id)
            ->whereIn('id', $ids)
            ->whereIn('status', ['applied', 'submitted'])
            ->update(['status' => 'withdrawn']);

        return response()->json(['message' => 'Applications withdrawn successfully']);
    }

    public function bulkDeleteApplications(Request $request)
    {
        $user = $this->ensureCandidate($request);
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'No applications selected'], 400);
        }

        JobApplication::where('user_id', $user->id)
            ->whereIn('id', $ids)
            ->delete();

        return response()->json(['message' => 'Applications deleted successfully']);
    }

    public function bulkDeleteHistory(Request $request)
    {
        $user = $this->ensureCandidate($request);
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'No applications selected'], 400);
        }

        JobApplication::withTrashed()
            ->where('user_id', $user->id)
            ->whereIn('id', $ids)
            ->delete();

        return response()->json(['message' => 'Applications deleted']);
    }

    public function bulkRestoreHistory(Request $request)
    {
        $user = $this->ensureCandidate($request);
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['message' => 'No applications selected'], 400);
        }

        JobApplication::withTrashed()
            ->where('user_id', $user->id)
            ->whereIn('id', $ids)
            ->restore();

        JobApplication::where('user_id', $user->id)
            ->whereIn('id', $ids)
            ->update(['status' => 'applied']);

        return response()->json(['message' => 'Applications restored successfully']);
    }

    public function interviews(Request $request)
    {
        $user = $this->ensureCandidate($request);

        $filter = $request->get('filter', 'upcoming');

        $query = \App\Models\Interview::where('applicant_id', $user->id)
            ->with(['employer', 'jobApplication.jobPosting.company']);

        if ($filter === 'upcoming') {
            $query->upcoming()->orderBy('scheduled_at');
        } elseif ($filter === 'past') {
            $query->where('scheduled_at', '<', now())->orderBy('scheduled_at', 'desc');
        } elseif ($filter === 'today') {
            $query->today()->orderBy('scheduled_at');
        } else {
            $query->orderBy('scheduled_at', 'desc');
        }

        $interviews = $query->paginate(15);

        $upcomingCount = \App\Models\Interview::where('applicant_id', $user->id)->upcoming()->count();
        $todayCount = \App\Models\Interview::where('applicant_id', $user->id)->today()->count();
        $totalCount = \App\Models\Interview::where('applicant_id', $user->id)->count();

        return \Inertia\Inertia::render('Applicant/Interviews', [
            'interviews' => $interviews,
            'filter' => $filter,
            'upcomingCount' => $upcomingCount,
            'todayCount' => $todayCount,
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * Download iCalendar (.ics) file for an interview
     */
    public function downloadCalendar(\App\Models\Interview $interview)
    {
        // Security check: Ensure this interview belongs to the authenticated applicant
        if ($interview->applicant_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $title = $interview->title;
        $company = $interview->jobApplication?->jobPosting?->company?->name ?? 'Unknown Company';
        $location = $interview->location ?: ($interview->meeting_link ?: 'Online/Video Call');
        $description = ($interview->description ? $interview->description.'\\n\\n' : '').
                       'Join meeting here: '.($interview->meeting_link ?: 'Contact employer for link');

        $start = $interview->scheduled_at->format('Ymd\THis\Z');
        $end = $interview->getEndTime()->format('Ymd\THis\Z');

        $ical = "BEGIN:VCALENDAR\n".
                "VERSION:2.0\n".
                "PRODID:-//HillBCS//Hiring Hall//EN\n".
                "BEGIN:VEVENT\n".
                'UID:'.uniqid()."@hillbcs.com\n".
                'DTSTAMP:'.now()->format('Ymd\THis\Z')."\n".
                'DTSTART:'.$start."\n".
                'DTEND:'.$end."\n".
                'SUMMARY:'.$title.' with '.$company."\n".
                'DESCRIPTION:'.$description."\n".
                'LOCATION:'.$location."\n".
                "END:VEVENT\n".
                'END:VCALENDAR';

        return response($ical)
            ->header('Content-Type', 'text/calendar')
            ->header('Content-Disposition', 'attachment; filename="interview-'.$interview->id.'.ics"');
    }
}
