<?php

namespace App\Http\Controllers\Applicants;

use App\Http\Controllers\Controller;
use App\Models\ApplicantProfile;
use App\Models\SavedApplicant;
use App\Services\DropdownService;
use Illuminate\Http\Request;

class ApplicantBrowseController extends Controller
{
    public function index(Request $request)
    {
        $availabilityFilter = array_filter((array) $request->input('availability', []));
        $jobTypeFilter = array_filter((array) $request->input('job_type', []));
        $expertiseFilter = array_filter((array) $request->input('expertise', []));
        $languagesFilter = array_filter((array) $request->input('languages', []));

        $salaryMin = $request->input('salary_min');
        $salaryMax = $request->input('salary_max');
        $salaryCurrency = $request->input('salary_currency');

        // Top search bar inputs
        $keyword = $request->input('keyword');
        $location = $request->input('location');
        $experienceRange = $request->input('experience');

        $query = ApplicantProfile::with('user')->where('verification_status', 'verified');

        // OR logic: show candidates that have ANY of the selected availability options
        if (! empty($availabilityFilter)) {
            $query->whereIn('availability', $availabilityFilter);
        }

        // OR logic: show candidates that have ANY of the selected job types
        if (! empty($jobTypeFilter)) {
            $query->whereIn('job_type', $jobTypeFilter);
        }

        // OR logic: show candidates that have ANY of the selected expertise categories
        // Filter now matches ONLY the exact selected values, not broad categories
        if (! empty($expertiseFilter)) {
            $query->where(function ($q) use ($expertiseFilter) {
                foreach ($expertiseFilter as $categoryValue) {
                    // Search for the specific expertise value in the JSON field
                    // When selecting "va_general", only show VAs (not all "administrative" profiles)
                    $q->orWhereJsonContains('expertise_categories', $categoryValue);
                }
            });
        }

        // OR logic: show candidates that speak ANY of the selected languages
        if (! empty($languagesFilter)) {
            $query->where(function ($q) use ($languagesFilter) {
                foreach ($languagesFilter as $lang) {
                    $q->orWhere('languages', 'like', '%'.$lang.'%');
                }
            });
        }

        if ($keyword) {
            $like = '%'.$keyword.'%';

            $query->where(function ($q) use ($like) {
                $q->where('display_name', 'like', $like)
                    ->orWhere('title', 'like', $like)
                    ->orWhere('headline', 'like', $like)
                    ->orWhere('about', 'like', $like)
                    ->orWhereHas('user', function ($userQuery) use ($like) {
                        $userQuery->where('name', 'like', $like)
                            ->orWhere('email', 'like', $like);
                    });
            });
        }

        if ($location) {
            $loc = '%'.$location.'%';

            $query->where(function ($q) use ($loc) {
                $q->where('location', 'like', $loc)
                    ->orWhereHas('user', function ($userQuery) use ($loc) {
                        $userQuery->where('address', 'like', $loc);
                    });
            });
        }

        if ($experienceRange) {
            $minExp = null;
            $maxExp = null;

            if ($experienceRange === '0-1') {
                $minExp = 0;
                $maxExp = 1;
            } elseif ($experienceRange === '1-2') {
                $minExp = 1;
                $maxExp = 2;
            } elseif ($experienceRange === '2-3') {
                $minExp = 2;
                $maxExp = 3;
            } elseif ($experienceRange === '3-5') {
                $minExp = 3;
                $maxExp = 5;
            } elseif ($experienceRange === '5+') {
                $minExp = 5;
                $maxExp = null;
            }

            if (! is_null($minExp)) {
                $query->where('years_experience', '>=', $minExp);
            }

            if (! is_null($maxExp)) {
                $query->where('years_experience', '<=', $maxExp);
            }
        }

        if ($salaryMin !== null && $salaryMin !== '') {
            $min = (float) $salaryMin;
            $query->whereNotNull('expected_salary_min')->where('expected_salary_min', '>=', $min);
        }

        if ($salaryMax !== null && $salaryMax !== '') {
            $max = (float) $salaryMax;
            $query->whereNotNull('expected_salary_max')->where('expected_salary_max', '<=', $max);
        }

        // Filter by salary currency if specified
        if ($salaryCurrency) {
            $query->where('salary_currency', $salaryCurrency);
        }

        $profiles = $query
            ->latest('updated_at')
            ->paginate(6)
            ->withQueryString();

        // Get saved candidate IDs for current employer
        $savedApplicantIds = [];
        if (auth()->check() && auth()->user()->role === 'employer') {
            $savedApplicantIds = SavedApplicant::where('employer_id', auth()->id())
                ->pluck('applicant_profile_id')
                ->toArray();
        }

        // JSON response for infinite scroll AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            $candidates = $profiles->map(fn ($profile) => $this->mapProfileToCandidate($profile, $savedApplicantIds))->values();

            return response()->json([
                'candidates' => $candidates,
                'page' => $profiles->currentPage(),
                'hasMore' => $profiles->hasMorePages(),
                'total' => $profiles->total(),
            ]);
        }

        // Get dynamic dropdown options for filters (uses collections for checkbox rendering)
        $dropdownOptions = DropdownService::getApplicantFilterOptions();

        return view('modules.jobs.candidates', [
            'profiles' => $profiles,
            'savedApplicantIds' => $savedApplicantIds,
            'availabilityFilter' => $availabilityFilter,
            'jobTypeFilter' => $jobTypeFilter,
            'expertiseFilter' => $expertiseFilter,
            'languagesFilter' => $languagesFilter,
            'salaryMin' => $salaryMin,
            'salaryMax' => $salaryMax,
            'salaryCurrency' => $salaryCurrency,
            'searchKeyword' => $keyword,
            'searchLocation' => $location,
            'experienceRange' => $experienceRange,
            'dropdownOptions' => $dropdownOptions,
            'hasMore' => $profiles->hasMorePages(),
            'total' => $profiles->total(),
        ]);
    }

    /** @param array<int> $savedApplicantIds */
    private function mapProfileToCandidate(ApplicantProfile $profile, array $savedApplicantIds): array
    {
        $user = $profile->user;
        $name = $profile->display_name ?? ($user?->name ?? 'Applicant');

        $parseJson = function (mixed $value): array {
            if (empty($value)) {
                return [];
            }
            $decoded = json_decode((string) $value, true);

            return is_array($decoded)
                ? array_values(array_filter(array_map('trim', $decoded)))
                : [];
        };

        $avatar = ($user && ! empty($user->profile_photo_path))
            ? $user->profile_photo_url
            : 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($name).'&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981';

        $languages = $parseJson($profile->languages) ?: ['English'];

        return [
            'id' => $profile->id,
            'name' => $name,
            'avatar' => $avatar,
            'title' => $profile->job_title ?? $profile->title ?? 'Virtual Assistant',
            'location' => $profile->location ?? 'Location not set',
            'degree' => $profile->degree ?? "Bachelor's Degree",
            'work_mode' => $profile->work_mode ?? 'Remote Work',
            'experience' => $profile->years_experience !== null ? $profile->years_experience.' Years Experience' : 'Experience not set',
            'schedule' => $profile->availability ?? null,
            'job_type' => $profile->job_type ?? null,
            'expertise' => $parseJson($profile->expertise_categories),
            'skills' => $parseJson($profile->skills),
            'tools_used' => $parseJson($profile->tools_used),
            'pay_currency' => $profile->salary_currency ?? 'USD',
            'pay_min' => $profile->expected_salary_min ? ($profile->salary_currency ?? 'USD').' '.number_format((float) $profile->expected_salary_min) : null,
            'pay_max' => $profile->expected_salary_max ? ($profile->salary_currency ?? 'USD').' '.number_format((float) $profile->expected_salary_max) : null,
            'languages' => $languages,
            'rating' => (float) ($profile->rating ?? 0),
            'rating_count' => (int) ($profile->rating_count ?? 0),
            'verified' => (bool) ($profile->verified ?? false),
            'cv_path' => $profile->cv_path ?? null,
            'is_saved' => in_array($profile->id, $savedApplicantIds),
            'detail_url' => route('applicants.details', ['applicant' => $profile->id]),
            'cv_url' => $profile->cv_path ? route('applicants.download-cv', ['applicant' => $profile->id]) : null,
            'social' => [
                'facebook' => $user?->social_facebook ?? null,
                'twitter' => $user?->social_twitter ?? null,
                'instagram' => $user?->social_instagram ?? null,
                'github' => $user?->social_github ?? null,
                'youtube' => $user?->social_youtube ?? null,
            ],
        ];
    }
}
