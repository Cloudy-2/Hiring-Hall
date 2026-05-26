<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AnnouncementPageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user) {
            User::withoutEvents(function () use ($user) {
                $user->forceFill([
                    'announcements_last_read_at' => now(),
                ])->save();
            });
        }

        // Fetch standard announcements
        $announcements = \App\Models\Announcement::getActiveForUser($user);

        // Fetch published release notes
        $releaseNotes = \App\Models\ReleaseNote::published()
            ->where('released_at', '<=', now())
            ->orderBy('released_at', 'desc')
            ->get();

        // Convert and merge into a unified collection
        $items = $announcements->map(function ($a) {
            $a->display_date = $a->published_at ?? $a->created_at;
            $a->is_release = false;
            $a->priority_pinned = $a->is_pinned ? 1 : 0;

            return $a;
        })->concat($releaseNotes->map(function ($r) {
            $r->display_date = $r->released_at;
            $r->is_release = true;
            $r->priority_pinned = 0;

            return $r;
        }))->sort(function ($a, $b) {
            // Level 1: Pinned first
            if ($a->priority_pinned !== $b->priority_pinned) {
                return $b->priority_pinned <=> $a->priority_pinned;
            }

            // Level 2: Date descending
            return $b->display_date <=> $a->display_date;
        });

        return view('announcements.index', [
            'announcements' => $items,
        ]);
    }
}
