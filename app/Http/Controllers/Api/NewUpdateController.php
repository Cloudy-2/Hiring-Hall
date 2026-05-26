<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewUpdate;
use Illuminate\Http\Request;

class NewUpdateController extends Controller
{
    public function index(Request $request)
    {
        $url = $request->input('url', null);
        $category = $request->input('category', null);

        $query = NewUpdate::active()->orderBy('created_at', 'desc');

        if ($url) {
            $query->forUrl($url);
        }

        if ($category) {
            $query->where('category', $category);
        }

        $updates = $query->get();

        return response()->json([
            'updates' => $updates,
            'total' => $updates->count(),
        ]);
    }

    public function store(Request $request)
    {
        if (! $this->canManageUpdates()) {
            return response()->json(['error' => 'Only moderators and admins can create badges'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'selector' => 'nullable|string|max:500',
            'url' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'category' => 'nullable|string|in:feature,improvement,fix,bug',
            'priority' => 'nullable|string|in:low,medium,high,critical',
            'status' => 'nullable|string|in:open,in_progress,resolved',
            'expires_at' => 'nullable|date',
        ]);

        $update = NewUpdate::create([
            ...$validated,
            'created_by' => auth()->id() ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'New update marker created',
            'update' => $update,
        ]);
    }

    private function canManageUpdates()
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        return in_array($user->role, ['moderator', 'admin', 'super_admin']);
    }

    public function destroy($id)
    {
        if (! $this->canManageUpdates()) {
            return response()->json(['error' => 'Only moderators and admins can delete badges'], 403);
        }

        $update = NewUpdate::findOrFail($id);
        $update->delete();

        return response()->json([
            'success' => true,
            'message' => 'Update marker removed',
        ]);
    }

    public function toggle($id)
    {
        if (! $this->canManageUpdates()) {
            return response()->json(['error' => 'Only moderators and admins can toggle badges'], 403);
        }

        $update = NewUpdate::findOrFail($id);
        $update->is_active = ! $update->is_active;
        $update->save();

        return response()->json([
            'success' => true,
            'is_active' => $update->is_active,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (! $this->canManageUpdates()) {
            return response()->json(['error' => 'Only moderators and admins can edit badges'], 403);
        }

        $update = NewUpdate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'selector' => 'nullable|string|max:500',
            'url' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'category' => 'nullable|string|in:feature,improvement,fix,bug',
            'priority' => 'nullable|string|in:low,medium,high,critical',
            'status' => 'nullable|string|in:open,in_progress,resolved',
            'expires_at' => 'nullable|date',
            'is_active' => 'sometimes|boolean',
        ]);

        $update->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Update marker updated',
            'update' => $update->fresh(),
        ]);
    }

    public function getSelectors(Request $request)
    {
        $url = $request->input('url', '/');

        $updates = NewUpdate::active()
            ->forUrl($url)
            ->whereNotNull('selector')
            ->get(['id', 'name', 'selector', 'description', 'category', 'priority', 'status']);

        return response()->json([
            'selectors' => $updates->pluck('selector'),
            'updates' => $updates,
        ]);
    }
}
