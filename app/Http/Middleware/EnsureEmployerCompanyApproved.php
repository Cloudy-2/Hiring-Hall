<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployerCompanyApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'employer') {
            abort(403, 'Access denied.');
        }

        $company = $user->company;

        if (! $company) {
            return redirect()->route('employer.companies.create')
                ->with('error', 'Please create a company profile first before viewing applicants.');
        }

        if (! $company->isApproved()) {
            $message = $company->isPending()
                ? 'Your company is pending verification. You can view applicants once approved by an administrator.'
                : 'Your company was not approved. Please update your company details and resubmit for verification.';

            return redirect()->route('employer.companies.edit', $company)
                ->with('error', $message);
        }

        return $next($request);
    }
}
