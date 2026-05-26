<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployerOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->role !== 'employer') {
            // Redirect virtual assistants to their dashboard
            if ($request->user() && $request->user()->role === 'applicant') {
                return redirect()->route('applicant.dashboard')->with('error', 'This page is for employers only.');
            }
            abort(403, 'Access denied. Employers only.');
        }

        return $next($request);
    }
}
