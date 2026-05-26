<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReleaseNoteRequest;
use App\Http\Requests\UpdateReleaseNoteRequest;
use App\Models\ReleaseNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReleaseNoteController extends Controller
{
    protected function ensureModerator(Request $request): \App\Models\User
    {
        $user = $request->user();
        if (! $user || ! in_array($user->role, ['moderator', 'admin', 'super_admin'])) {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request): View
    {
        $this->ensureModerator($request);

        $filter = $request->input('filter', 'all');
        $query = ReleaseNote::with('creator')->orderByDesc('released_at');

        if ($filter === 'published') {
            $query->published();
        } elseif ($filter === 'draft') {
            $query->where('is_published', false);
        }

        $releaseNotes = $query->paginate(20)->withQueryString();

        $counts = [
            'all' => ReleaseNote::count(),
            'published' => ReleaseNote::published()->count(),
            'draft' => ReleaseNote::where('is_published', false)->count(),
        ];

        return view('moderator.release-notes.index', compact('releaseNotes', 'filter', 'counts'));
    }

    public function create(Request $request): View
    {
        $this->ensureModerator($request);

        return view('moderator.release-notes.create');
    }

    public function store(StoreReleaseNoteRequest $request): RedirectResponse
    {
        $user = $this->ensureModerator($request);

        $releaseNote = ReleaseNote::create([
            'version' => $request->validated('version') ?: null,
            'title' => $request->validated('title'),
            'body' => $request->validated('body'),
            'released_at' => $request->validated('released_at'),
            'is_published' => $request->boolean('is_published'),
            'created_by' => $user->id,
        ]);

        if ($request->boolean('set_as_system_version')) {
            $v = $request->input('version');
            if ($v) {
                \App\Models\Setting::set('system_version', $v);
            }
        }

        return redirect()->route('moderator.release-notes.index')
            ->with('status', 'Release note created successfully.');
    }

    public function edit(Request $request, ReleaseNote $releaseNote): View
    {
        $this->ensureModerator($request);

        return view('moderator.release-notes.edit', compact('releaseNote'));
    }

    public function update(UpdateReleaseNoteRequest $request, ReleaseNote $releaseNote): RedirectResponse
    {
        $this->ensureModerator($request);

        $releaseNote->update([
            'version' => $request->validated('version') ?: null,
            'title' => $request->validated('title'),
            'body' => $request->validated('body'),
            'released_at' => $request->validated('released_at'),
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->boolean('set_as_system_version')) {
            $version = $request->input('version');
            if ($version) {
                \App\Models\Setting::set('system_version', $version);
            }
        }

        return redirect()->route('moderator.release-notes.index')
            ->with('status', 'Release note updated successfully.');
    }

    public function destroy(Request $request, ReleaseNote $releaseNote): RedirectResponse
    {
        $this->ensureModerator($request);

        $releaseNote->delete();

        return redirect()->route('moderator.release-notes.index')
            ->with('status', 'Release note deleted successfully.');
    }

    public function togglePublished(Request $request, ReleaseNote $releaseNote): RedirectResponse
    {
        $this->ensureModerator($request);

        $releaseNote->update(['is_published' => ! $releaseNote->is_published]);
        $releaseNote->refresh();

        if ($request->boolean('set_as_system_version') && $releaseNote->is_published && $releaseNote->version) {
            \App\Models\Setting::set('system_version', $releaseNote->version);
        }

        $status = $releaseNote->is_published ? 'published' : 'unpublished';
        $message = "Release note {$status}.";

        if ($request->boolean('set_as_system_version') && $releaseNote->is_published) {
            $message .= " System version updated to {$releaseNote->version}.";
        }

        return redirect()->back()->with('status', $message);
    }

    public function updateVersion(Request $request): RedirectResponse
    {
        $this->ensureModerator($request);

        $request->validate([
            'system_version' => 'required|string|max:20',
        ]);

        \App\Models\Setting::set('system_version', $request->input('system_version'));

        return redirect()->back()->with('status', 'System version updated successfully.');
    }
}
