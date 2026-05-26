<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModeratorOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, ['moderator', 'admin', 'super_admin'])) {
            abort(404, 'Not Found');
        }

        return $next($request);
    }
}
