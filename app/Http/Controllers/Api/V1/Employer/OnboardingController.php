<?php

namespace App\Http\Controllers\Api\V1\Employer;

use App\Actions\Onboarding\EmployerOnboard;
use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Requests\Api\Shared\AdvanceOnboardingRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingController extends ApiController
{
    /**
     * GET /api/v1/employer/onboarding
     */
    public function show(Request $request): JsonResponse
    {
        $company = $request->user()->company;

        if (! $company) {
            return $this->notFound('Company profile not found.');
        }

        return $this->success([
            'onboarding_step' => $company->onboarding_step,
            'onboarding_completed' => $company->isOnboarded(),
            'onboarding_completed_at' => $company->onboarding_completed_at?->toIso8601String(),
            'verification_status' => $company->verification_status,
        ]);
    }

    /**
     * POST /api/v1/employer/onboarding
     *
     * Advances or updates the onboarding step.
     * When step >= 4, marks onboarding as complete.
     */
    public function advance(AdvanceOnboardingRequest $request): JsonResponse
    {
        $action = new EmployerOnboard;
        $company = $action->handle($request->user(), $request->all());

        return $this->success([
            'onboarding_step' => $company->onboarding_step,
            'onboarding_completed' => $company->isOnboarded(),
        ], 'Onboarding step updated.');
    }
}
