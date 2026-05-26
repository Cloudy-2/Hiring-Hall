<?php

namespace App\Http\Middleware;

use App\Models\ApplicantProfile;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    /**
     * Routes that should be exempt from onboarding check.
     */
    protected array $except = [
        'applicant/onboarding*',
        'logout',
        'livewire*',
        'tickets*',  // Allow candidates to submit and view support tickets without completing onboarding
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only apply to candidate role
        if (! $user || $user->role !== 'applicant') {
            return $next($request);
        }

        // Skip exempt routes
        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        // Check if onboarding is complete
        $profile = ApplicantProfile::where('user_id', $user->id)->first();

        if (! $profile || ! $profile->isOnboarded()) {
            $step = $profile ? max($profile->onboarding_step, 1) : 1;

            return redirect()->route('applicant.onboarding.show', ['step' => $step]);
        }

        return $next($request);
    }
}
