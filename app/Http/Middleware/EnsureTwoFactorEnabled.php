<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorEnabled
{
    /**
     * Routes that should be exempt from 2FA check.
     */
    protected array $except = [
        'two-factor-setup',
        'user/profile',
        'user/two-factor*',
        'user/confirmed-password-status',
        'user/confirm-password',
        'logout',
        'livewire*',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->hasEnabledTwoFactorAuthentication()) {
            return $next($request);
        }

        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        return redirect()->route('two-factor.setup')
            ->with('warning', 'Please enable two-factor authentication to secure your account.');
    }
}
