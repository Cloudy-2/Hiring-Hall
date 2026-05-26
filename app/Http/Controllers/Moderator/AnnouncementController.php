<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    protected function ensureModerator(Request $request)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['moderator', 'admin', 'super_admin'])) {
            abort(403);
        }

        return $user;
    }

    public function index(Request $request)
    {
        $this->ensureModerator($request);

        $status = $request->input('status', 'all');

        $query = Announcement::with('creator');

        if ($status === 'active') {
            $query->active();
        } elseif ($status === 'expired') {
            $query->where(function ($q) {
                $q->where('is_active', false)
                    ->orWhere('expires_at', '<', now());
            });
        } elseif ($status === 'scheduled') {
            $query->where('is_active', true)
                ->where('published_at', '>', now());
        }

        $announcements = $query->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'all' => Announcement::count(),
            'active' => Announcement::active()->count(),
            'scheduled' => Announcement::where('is_active', true)->where('published_at', '>', now())->count(),
            'expired' => Announcement::where(function ($q) {
                $q->where('is_active', false)
                    ->orWhere('expires_at', '<', now());
            })->count(),
        ];

        return view('moderator.announcements.index', compact('announcements', 'status', 'counts'));
    }

    public function create(Request $request)
    {
        $this->ensureModerator($request);

        return view('moderator.announcements.create');
    }

    public function store(Request $request)
    {
        $user = $this->ensureModerator($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'type' => 'required|in:info,warning,success,danger',
            'target_roles' => 'nullable|array',
            'target_roles.*' => 'in:all,applicant,employer,moderator,admin,super_admin',
            'is_pinned' => 'boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
        ]);

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'target_roles' => $validated['target_roles'] ?? ['all'],
            'is_pinned' => $validated['is_pinned'] ?? false,
            'is_active' => true,
            'published_at' => $validated['published_at'] ?? now(),
            'expires_at' => $validated['expires_at'] ?? null,
            'created_by' => $user->id,
        ]);

        return redirect()->route('moderator.announcements.index')
            ->with('status', 'Announcement "'.$announcement->title.'" created successfully.');
    }

    public function edit(Request $request, Announcement $announcement)
    {
        $this->ensureModerator($request);

        return view('moderator.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $this->ensureModerator($request);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'type' => 'required|in:info,warning,success,danger',
            'target_roles' => 'nullable|array',
            'target_roles.*' => 'in:all,applicant,employer,moderator,admin,super_admin',
            'is_pinned' => 'boolean',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type'],
            'target_roles' => $validated['target_roles'] ?? ['all'],
            'is_pinned' => $validated['is_pinned'] ?? false,
            'is_active' => $validated['is_active'] ?? true,
            'published_at' => $validated['published_at'],
            'expires_at' => $validated['expires_at'],
        ]);

        return redirect()->route('moderator.announcements.index')
            ->with('status', 'Announcement "'.$announcement->title.'" updated successfully.');
    }

    public function destroy(Request $request, Announcement $announcement)
    {
        $this->ensureModerator($request);

        $title = $announcement->title;
        $announcement->delete();

        return redirect()->route('moderator.announcements.index')
            ->with('status', 'Announcement "'.$title.'" deleted successfully.');
    }

    public function togglePin(Request $request, Announcement $announcement)
    {
        $this->ensureModerator($request);

        $announcement->update(['is_pinned' => ! $announcement->is_pinned]);

        $action = $announcement->is_pinned ? 'pinned' : 'unpinned';

        return redirect()->back()->with('status', 'Announcement "'.$announcement->title.'" has been '.$action.'.');
    }

    public function toggleActive(Request $request, Announcement $announcement)
    {
        $this->ensureModerator($request);

        $announcement->update(['is_active' => ! $announcement->is_active]);

        $action = $announcement->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('status', 'Announcement "'.$announcement->title.'" has been '.$action.'.');
    }
}
