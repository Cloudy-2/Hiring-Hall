<?php

namespace App\Http\Controllers;

use App\Models\StickyNote;
use Illuminate\Http\Request;

class StickyNoteController extends Controller
{
    private function authorizeRole(Request $request)
    {
        if (! in_array($request->user()->role, ['admin', 'moderator', 'super_admin'])) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $this->authorizeRole($request);

        if (! $request->expectsJson()) {
            return view('sticky-notes.index');
        }

        $userId = $request->user()->id;
        $notes = StickyNote::where('user_id', $userId)
            ->orderBy('position')
            ->get();

        if ($notes->isEmpty()) {
            StickyNote::create([
                'user_id' => $userId,
                'content' => '',
                'color' => 'yellow',
                'position' => 1,
            ]);
            StickyNote::create([
                'user_id' => $userId,
                'content' => '',
                'color' => 'blue',
                'position' => 2,
            ]);
            $notes = StickyNote::where('user_id', $userId)
                ->orderBy('position')
                ->get();
        }

        return response()->json($notes);
    }

    public function store(Request $request)
    {
        $this->authorizeRole($request);

        $request->validate([
            'content' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
        ]);

        $userId = $request->user()->id;
        $count = StickyNote::where('user_id', $userId)->count();
        if ($count >= 2) {
            return response()->json(['message' => 'Maximum 2 notes allowed.'], 422);
        }

        $content = $request->input('content', '');
        $color = $request->input('color', 'yellow');
        $position = StickyNote::where('user_id', $userId)->max('position') + 1;

        $note = StickyNote::create([
            'user_id' => $userId,
            'content' => $content,
            'color' => $color,
            'position' => $position,
        ]);

        return response()->json($note, 201);
    }

    public function update(Request $request, StickyNote $stickyNote)
    {
        $this->authorizeRole($request);

        if ($stickyNote->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
        ]);

        $stickyNote->update($request->only(['content', 'color']));

        return response()->json($stickyNote);
    }

    public function destroy(Request $request, StickyNote $stickyNote)
    {
        $this->authorizeRole($request);

        if ($stickyNote->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stickyNote->delete();

        return response()->json(['success' => true]);
    }
}
