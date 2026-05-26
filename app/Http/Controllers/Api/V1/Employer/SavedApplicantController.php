<?php

namespace App\Http\Controllers\Api\V1\Employer;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Resources\Api\ApplicantProfileResource;
use App\Models\ApplicantProfile;
use App\Models\JobApplication;
use App\Models\SavedApplicant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SavedApplicantController extends ApiController
{
    /**
     * GET /api/v1/employer/saved-applicants
     *
     * Lists applicants bookmarked by the employer.
     */
    public function index(Request $request): JsonResponse
    {
        $paginator = SavedApplicant::with('applicantProfile.user')
            ->where('employer_id', $request->user()->id)
            ->latest()
            ->paginate($this->perPage($request, 20));

        $profiles = $paginator->getCollection()->map(
            fn ($saved) => new ApplicantProfileResource($saved->applicantProfile)
        );

        return $this->paginated($paginator, $profiles);
    }

    /**
     * POST /api/v1/employer/saved-applicants/{applicantId}
     *
     * Toggle save/unsave an applicant profile.
     */
    public function toggle(Request $request, int $applicantId): JsonResponse
    {
        $profile = ApplicantProfile::where('id', $applicantId)->first();

        if (! $profile) {
            return $this->notFound('Applicant profile not found.');
        }

        $company = $request->user()->company;
        $hasCompanyApplication = JobApplication::where('applicant_profile_id', $applicantId)
            ->whereHas('jobPosting', fn ($query) => $query->where('company_id', $company?->id))
            ->exists();

        if (! $hasCompanyApplication) {
            return $this->forbidden('Applicant is not available to this employer.');
        }

        $existing = SavedApplicant::where('employer_id', $request->user()->id)
            ->where('applicant_profile_id', $applicantId)
            ->first();

        if ($existing) {
            $existing->delete();

            return $this->success(['saved' => false], 'Applicant removed from saved list.');
        }

        SavedApplicant::create([
            'employer_id' => $request->user()->id,
            'applicant_profile_id' => $applicantId,
        ]);

        return $this->success(['saved' => true], 'Applicant saved.');
    }
}
