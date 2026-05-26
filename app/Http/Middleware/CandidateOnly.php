<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CandidateOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->role !== 'applicant') {
            // Redirect employers to their dashboard
            if ($request->user() && $request->user()->role === 'employer') {
                return redirect()->route('employer.dashboard')->with('error', 'This page is for candidates only.');
            }
            abort(403, 'Access denied. Candidates only.');
        }

        return $next($request);
    }
}
