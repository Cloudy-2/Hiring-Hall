<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    protected function ensureAdmin(Request $request): \App\Models\User
    {
        $user = $request->user();
        if (! $user || ! in_array($user->role, ['admin', 'super_admin'])) {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $settings = Setting::query()->orderBy('key')->get()->keyBy('key');

        return view('moderator.settings.index', compact('settings'));
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $settings = $request->validated('settings', []);
        $newKey = $request->validated('new_key');
        if (is_string($newKey) && $newKey !== '') {
            $settings[$newKey] = $request->validated('new_value') ?? '';
        }

        foreach ($settings as $key => $value) {
            $key = is_string($key) ? trim($key) : '';
            if ($key === '' || ! preg_match('/^[a-z0-9_]+$/i', $key)) {
                continue;
            }
            $value = $value === null ? '' : (string) $value;
            if ($value === '') {
                Setting::query()->where('key', $key)->delete();
                \Illuminate\Support\Facades\Cache::forget('setting.'.$key);
            } else {
                Setting::set($key, $value);
            }
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings saved.');
    }
}
