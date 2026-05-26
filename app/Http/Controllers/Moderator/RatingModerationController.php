<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingModerationController extends Controller
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

        $filter = $request->input('filter', 'all');

        $query = Rating::with(['user', 'rateable', 'moderatedBy']);

        if ($filter === 'flagged') {
            $query->flagged();
        } elseif ($filter === 'hidden') {
            $query->hidden();
        }

        $ratings = $query->latest()->paginate(20)->withQueryString();

        $counts = [
            'all' => Rating::count(),
            'flagged' => Rating::flagged()->count(),
            'hidden' => Rating::hidden()->count(),
        ];

        return view('moderator.ratings.index', compact('ratings', 'filter', 'counts'));
    }

    public function show(Request $request, Rating $rating)
    {
        $this->ensureModerator($request);

        $rating->load(['user', 'rateable', 'moderatedBy']);

        return view('moderator.ratings.show', compact('rating'));
    }

    public function hide(Request $request, Rating $rating)
    {
        $user = $this->ensureModerator($request);

        $rating->update([
            'is_hidden' => true,
            'moderated_by' => $user->id,
            'moderated_at' => now(),
            'moderation_notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('status', 'Rating has been hidden from public view.');
    }

    public function unhide(Request $request, Rating $rating)
    {
        $user = $this->ensureModerator($request);

        $rating->update([
            'is_hidden' => false,
            'moderated_by' => $user->id,
            'moderated_at' => now(),
        ]);

        return redirect()->back()->with('status', 'Rating has been made visible again.');
    }

    public function flag(Request $request, Rating $rating)
    {
        $user = $this->ensureModerator($request);

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $rating->update([
            'is_flagged' => true,
            'flag_reason' => $validated['reason'],
            'moderated_by' => $user->id,
            'moderated_at' => now(),
        ]);

        return redirect()->back()->with('status', 'Rating has been flagged for review.');
    }

    public function unflag(Request $request, Rating $rating)
    {
        $user = $this->ensureModerator($request);

        $rating->update([
            'is_flagged' => false,
            'flag_reason' => null,
            'moderated_by' => $user->id,
            'moderated_at' => now(),
        ]);

        return redirect()->back()->with('status', 'Flag has been removed from the rating.');
    }

    public function destroy(Request $request, Rating $rating)
    {
        $this->ensureModerator($request);

        $rating->delete();

        return redirect()->route('moderator.ratings.index')->with('status', 'Rating has been permanently deleted.');
    }
}
