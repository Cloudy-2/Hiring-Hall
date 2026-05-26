<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Models\Chats\Message;
use App\Models\PersonalTag;
use App\Models\PersonalTagMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalTagController extends Controller
{
    public function index()
    {
        $tags = PersonalTag::where('user_id', Auth::id())
            ->orderBy('position')
            ->get();

        return response()->json($tags);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:30',
            'is_private' => 'nullable|boolean',
        ]);

        $slug = PersonalTag::generateSlug($validated['name']);

        // Check for duplicate slug for this user
        $existingCount = PersonalTag::where('user_id', Auth::id())
            ->where('slug', 'like', $slug.'%')
            ->count();

        if ($existingCount > 0) {
            $slug = $slug.'-'.($existingCount + 1);
        }

        $maxPosition = PersonalTag::where('user_id', Auth::id())->max('position') ?? 0;

        $tag = PersonalTag::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'slug' => $slug,
            'color' => $validated['color'] ?? '#6366f1',
            'icon' => $validated['icon'] ?? 'bookmark',
            'is_private' => $request->has('is_private'),
            'position' => $maxPosition + 1,
        ]);

        return response()->json($tag, 201);
    }

    public function show(PersonalTag $personalTag)
    {
        if ($personalTag->user_id !== Auth::id()) {
            abort(403);
        }

        $messages = $personalTag->messages()
            ->with('user:id,name,profile_photo_path')
            ->latest()
            ->paginate(50);

        return response()->json([
            'tag' => $personalTag,
            'messages' => $messages,
        ]);
    }

    public function update(Request $request, PersonalTag $personalTag)
    {
        if ($personalTag->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:50',
            'color' => 'sometimes|string|max:20',
            'icon' => 'sometimes|string|max:30',
            'is_private' => 'sometimes|boolean',
            'position' => 'sometimes|integer|min:0',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = PersonalTag::generateSlug($validated['name']);
        }

        $personalTag->update($validated);

        return response()->json($personalTag);
    }

    public function destroy(PersonalTag $personalTag)
    {
        if ($personalTag->user_id !== Auth::id()) {
            abort(403);
        }

        $personalTag->delete();

        return response()->json(['message' => 'Tag deleted']);
    }

    public function storeMessage(Request $request, PersonalTag $personalTag)
    {
        if ($personalTag->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => 'nullable|string|max:5000',
            'forwarded_from_message_id' => 'nullable|integer',
        ]);

        $forwardedMetadata = null;
        if (! empty($validated['forwarded_from_message_id'])) {
            $originalMessage = Message::with('user:id,name')->find($validated['forwarded_from_message_id']);
            if ($originalMessage) {
                $forwardedMetadata = [
                    'original_sender' => $originalMessage->user?->name ?? 'Unknown',
                    'original_body' => $originalMessage->body,
                    'original_created_at' => $originalMessage->created_at?->toIso8601String(),
                ];
            }
        }

        $message = PersonalTagMessage::create([
            'personal_tag_id' => $personalTag->id,
            'user_id' => Auth::id(),
            'body' => $validated['body'] ?? $forwardedMetadata['original_body'] ?? '',
            'forwarded_from_message_id' => $validated['forwarded_from_message_id'] ?? null,
            'forwarded_metadata' => $forwardedMetadata,
        ]);

        return response()->json($message, 201);
    }

    public function destroyMessage(PersonalTag $personalTag, PersonalTagMessage $message)
    {
        if ($personalTag->user_id !== Auth::id() || $message->personal_tag_id !== $personalTag->id) {
            abort(403);
        }

        $message->delete();

        return response()->json(['message' => 'Message deleted']);
    }
}
