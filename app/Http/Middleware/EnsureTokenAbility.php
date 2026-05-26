<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenAbility
{
    /**
     * Handle an incoming request.
     *
     * Checks that the current Sanctum token has ALL of the specified abilities.
     * Returns a clean JSON 403 if not, instead of an uncaught exception page.
     *
     * Usage in routes:
     *   ->middleware('ability:applicant')
     *   ->middleware('ability:employer')
     */
    public function handle(Request $request, Closure $next, string ...$abilities): Response
    {
        $user = $request->user();

        if (! $user || ! $user->currentAccessToken()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        foreach ($abilities as $ability) {
            if (! $user->tokenCan($ability)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to perform this action.',
                    'required_ability' => $ability,
                ], 403);
            }
        }

        return $next($request);
    }
}
