<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\ImpersonationLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class ImpersonateController extends Controller
{
    public function impersonate(Request $request)
    {
        $current = Auth::user();

        if (! $current || ! in_array($current->role, ['moderator', 'admin', 'super_admin'])) {
            abort(403, 'Only staff members can impersonate users.');
        }

        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $target = User::findOrFail($request->user_id);

        if ($target->id === $current->id) {
            return back()->with('error', 'You cannot impersonate yourself.');
        }

        if (in_array($target->role, ['admin', 'moderator', 'super_admin']) && $current->role !== 'super_admin') {
            return back()->with('error', 'You cannot impersonate administrators or other moderators.');
        }

        session(['impersonator_email' => $current->email]);
        Cookie::queue(Cookie::make('impersonator_email', $current->email, 60 * 24, null, null, true, true));

        Auth::guard('web')->login($target, false);

        $request->session()->regenerate();

        $hash = $target->getAuthPassword();
        $request->session()->put('password_hash_web', $hash);
        $request->session()->put('password_hash_sanctum', $hash);
        $request->session()->put('password_hash_'.Auth::getDefaultDriver(), $hash);

        $log = ImpersonationLog::create([
            'impersonator_id' => $current->id,
            'target_user_id' => $target->id,
            'started_at' => now(),
        ]);
        $request->session()->put('impersonation_log_id', $log->id);

        return redirect()->route('dashboard')->with('status', 'Now logged in as: '.$target->name);
    }

    public function stopImpersonating(Request $request)
    {
        $prevEmail = session('impersonator_email') ?? $request->cookie('impersonator_email');
        $logId = session('impersonation_log_id');

        if (! $prevEmail) {
            return redirect()->route('dashboard')->with('error', 'No impersonation session found.');
        }

        if ($logId) {
            ImpersonationLog::where('id', $logId)->whereNull('ended_at')->update(['ended_at' => now()]);
        }
        session()->forget(['impersonator_email', 'impersonation_log_id']);
        Cookie::queue(Cookie::forget('impersonator_email'));

        $original = User::where('email', $prevEmail)->first();

        if (! $original) {
            Auth::guard('web')->logout();

            return redirect()->route('login')->with('error', 'Original user not found.');
        }

        Auth::guard('web')->login($original, false);

        $request->session()->regenerate();

        $hash = $original->getAuthPassword();
        $request->session()->put('password_hash_web', $hash);
        $request->session()->put('password_hash_sanctum', $hash);
        $request->session()->put('password_hash_'.Auth::getDefaultDriver(), $hash);

        return redirect()->route('moderator.users.index')->with('status', 'Returned to your account.');
    }
}
