<?php

namespace App\Http\Controllers\Api\V1\Applicant;

use App\Actions\Onboarding\ApplicantOnboard;
use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\Shared\AdvanceOnboardingRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingController extends ApiController
{
    /**
     * GET /api/v1/applicant/onboarding
     *
     * Returns the current onboarding step and completion status.
     */
    public function show(Request $request): JsonResponse
    {
        $profile = $request->user()->applicantProfile;

        if (! $profile) {
            return $this->notFound('Applicant profile not found.');
        }

        return $this->success([
            'onboarding_step' => $profile->onboarding_step,
            'onboarding_completed' => $profile->isOnboarded(),
            'onboarding_completed_at' => $profile->onboarding_completed_at?->toIso8601String(),
        ]);
    }

    /**
     * POST /api/v1/applicant/onboarding
     *
     * Advances (or updates) the onboarding step.
     * When step >= 5, marks onboarding as complete.
     */
    public function advance(AdvanceOnboardingRequest $request): JsonResponse
    {
        $action = new ApplicantOnboard;
        $profile = $action->handle($request->user(), $request->all());

        return $this->success([
            'onboarding_step' => $profile->onboarding_step,
            'onboarding_completed' => $profile->isOnboarded(),
        ], 'Onboarding step updated.');
    }
}
