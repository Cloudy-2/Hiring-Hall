<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployerOnboardingComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'employer') {
            // If not an employer, this middleware shouldn't block (or should 403 if route is employer-only)
            // Assuming this middleware is applied to 'employer' routes which already have 'role:employer' check or similar.
            // But if used alone, we should probably redirect or abort.
            // For safety, let's just proceed if not employer (handled by other middleware) or abort if stricter.
            return $next($request);
        }

        $company = $user->company;

        // If no company record exists, redirect to step 1 to create it
        if (! $company) {
            return redirect()->route('employer.onboarding.show', ['step' => 1]);
        }

        // If onboarding is not complete, redirect to current step
        if (! $company->isOnboarded()) {
            $step = $company->onboarding_step ?? 1;

            return redirect()->route('employer.onboarding.show', ['step' => $step]);
        }

        return $next($request);
    }
}
