<?php

namespace App\Http\Controllers\Chats;

use App\Events\UserPresenceChanged;
use App\Http\Controllers\Controller;
use App\Models\Chats\ConversationParticipant;
use App\Models\Chats\PresenceStatus;
use Illuminate\Http\Request;

// WEBSOCKET WORKING

class PresenceController extends Controller
{
    public function heartbeat(Request $request)
    {
        $user = $request->user();
        $previousStatus = PresenceStatus::getStatus($user->id);

        PresenceStatus::setOnline($user->id, $request->input('platform', 'web'));

        // Update current location if provided
        $conversationId = $request->input('conversation_id');
        $topicSlug = $request->input('topic_slug');

        // Always update and broadcast location when conversation_id is provided
        if ($conversationId !== null) {
            PresenceStatus::updateLocation($user->id, $conversationId ?: null, $topicSlug);
        }

        // Always broadcast presence with location on heartbeat (so others see updated location)
        broadcast(new UserPresenceChanged(
            $user->id,
            $user->name,
            PresenceStatus::getStatus($user->id),
            $user->profile_photo_url,
            $conversationId ?: null,
            $topicSlug
        ))->toOthers();

        // Return the actual current status (could be invisible)
        $currentStatus = PresenceStatus::getStatus($user->id);

        return response()->json(['ok' => true, 'status' => $currentStatus]);
    }

    //  * Set user status manually (online, idle, dnd, invisible)
    // BUT THIS IS DYNAMIC
    public function setStatus(Request $request)
    {
        $data = $request->validate([
            'status' => ['required', 'in:online,idle,dnd,invisible,offline'],
            'custom_status' => ['nullable', 'string', 'max:128'],
        ]);

        $user = $request->user();

        // Check if user currently has invisible status - preserve it unless explicitly changing to visible statuses
        $currentPresence = PresenceStatus::where('user_id', $user->id)->first();
        if ($currentPresence && $currentPresence->status === 'invisible') {
            // If not expired and trying to set to idle/online/offline (automatic status changes), preserve invisible
            if ((! $currentPresence->expires_at || ! $currentPresence->expires_at->isPast())
                && in_array($data['status'], ['online', 'idle', 'offline'])) {
                // Just extend the expiry, don't change status
                $currentPresence->last_active_at = now();
                $currentPresence->expires_at = now()->addHours(24);
                $currentPresence->save();

                return response()->json(['ok' => true, 'status' => 'invisible']);
            }
        }

        $presence = PresenceStatus::updateOrCreate(
            ['user_id' => $user->id],
            [
                'status' => $data['status'],
                'custom_status' => $data['custom_status'] ?? null,
                'last_active_at' => now(),
                'expires_at' => $data['status'] === 'offline' ? null : ($data['status'] === 'invisible' ? now()->addHours(24) : now()->addMinutes(5)),
            ]
        );

        // Clear location when user goes offline
        $locationConvId = null;
        $locationTopicSlug = null;

        if ($data['status'] === 'offline') {
            // Clear location in database
            PresenceStatus::updateLocation($user->id, null, null);
        } else {
            // Keep current location for non-offline statuses
            $locationConvId = $presence->current_conversation_id;
            $locationTopicSlug = $presence->current_topic_slug;
        }

        broadcast(new UserPresenceChanged(
            $user->id,
            $user->name,
            $data['status'],
            $user->profile_photo_url,
            $locationConvId,
            $locationTopicSlug
        ))->toOthers();

        return response()->json(['ok' => true, 'status' => $presence->status]);
    }

    /**
     * Get online users for a conversation
     */
    public function getOnlineUsers(Request $request, int $conversationId)
    {
        $user = $request->user();
        $participantIds = ConversationParticipant::where('conversation_id', $conversationId)
            ->whereNull('left_at')
            ->pluck('user_id');

        // Get their presence statuses
        $presences = PresenceStatus::whereIn('user_id', $participantIds)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->whereIn('status', ['online', 'idle', 'dnd'])
            ->with('user:id,name,profile_photo_path')
            ->get();

        return response()->json([
            'online' => $presences->map(fn ($p) => [
                'user_id' => $p->user_id,
                'name' => $p->user?->name,
                'avatar' => $p->user?->profile_photo_url,
                'status' => $p->status,
                'custom_status' => $p->custom_status,
            ]),
        ]);
    }
}
