<?php

namespace App\Http\Controllers\Chats;

use App\Events\CallEnded;
use App\Events\CallStarted;
use App\Http\Controllers\Controller;
use App\Models\Chats\Conversation;
use App\Models\Chats\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

// JITSI

class CallController extends Controller
{
    //  Start a call in a conversation (only moderators can start)
    public function start(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        // Only moderators can start calls
        abort_unless($user->isModerator(), 403, 'Only moderators can start calls');

        $data = $request->validate([
            'type' => ['required', 'in:video,audio'],
        ]);

        // Generate unique room name
        $roomName = 'hillhire-'.$conversation->id.'-'.Str::random(8);

        // Store call start time in cache (expires in 24 hours)
        Cache::put("call_start_{$roomName}", [
            'started_at' => now(),
            'started_by' => $user->id,
            'started_by_name' => $user->name,
            'call_type' => $data['type'],
        ], now()->addHours(24));

        // Track participants in the call (start with the moderator)
        Cache::put("call_participants_{$roomName}", [$user->id], now()->addHours(24));

        // Create system message for call started
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'type' => 'system',
            'body' => $user->name.' started a '.$data['type'].' call',
            'meta' => [
                'system_type' => 'call_started',
                'call_type' => $data['type'],
                'room_name' => $roomName,
            ],
        ]);

        // Broadcast call started to all participants
        broadcast(new CallStarted($conversation, $user, $roomName, $data['type']))->toOthers();

        return response()->json([
            'room_name' => $roomName,
            'call_type' => $data['type'],
            'jitsi_domain' => config('services.jitsi.domain', 'meet.hillbcs.com'),
        ]);
    }

    //  End a call in a conversation (called when a user leaves)
    public function end(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        // Verify user is participant or moderator
        $isParticipant = $conversation->participants()->where('user_id', $user->id)->exists();
        abort_unless($isParticipant || $user->isModerator(), 403);

        $data = $request->validate([
            'room_name' => ['required', 'string'],
        ]);

        $roomName = $data['room_name'];

        // Get current participants
        $participants = Cache::get("call_participants_{$roomName}", []);

        // Remove this user from participants
        $participants = array_values(array_diff($participants, [$user->id]));

        // If there are still participants, just update the cache and return
        if (count($participants) > 0) {
            Cache::put("call_participants_{$roomName}", $participants, now()->addHours(24));

            return response()->json(['ok' => true, 'call_ongoing' => true]);
        }

        // Last person left - end the call
        Cache::forget("call_participants_{$roomName}");

        // Get call start info
        $callInfo = Cache::pull("call_start_{$roomName}");

        // If no call info, the call was already fully ended
        if (! $callInfo) {
            return response()->json(['ok' => true, 'already_ended' => true]);
        }

        $duration = null;
        $durationText = '';

        if (isset($callInfo['started_at'])) {
            $startedAt = \Carbon\Carbon::parse($callInfo['started_at']);
            $duration = $startedAt->diffInSeconds(now());
            $durationText = $this->formatDuration($duration);
        }

        // Create system message for call ended
        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $callInfo['started_by'] ?? $user->id,
            'type' => 'system',
            'body' => 'Call ended'.($durationText ? " • Duration: {$durationText}" : ''),
            'meta' => [
                'system_type' => 'call_ended',
                'room_name' => $roomName,
                'duration' => $duration,
                'duration_text' => $durationText,
            ],
        ]);

        // Broadcast call ended with duration
        broadcast(new CallEnded($conversation, $roomName, $duration, $durationText, $user->id));

        return response()->json(['ok' => true, 'duration_text' => $durationText]);
    }

    /**
     * Format duration in seconds to human readable string
     */
    private function formatDuration(int $seconds): string
    {
        if ($seconds < 60) {
            return $seconds.'s';
        }

        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        if ($minutes < 60) {
            return $remainingSeconds > 0
                ? "{$minutes}m {$remainingSeconds}s"
                : "{$minutes}m";
        }

        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        return $remainingMinutes > 0
            ? "{$hours}h {$remainingMinutes}m"
            : "{$hours}h";
    }

    // Join an existing call
    public function join(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        // Verify user is participant or moderator
        $isParticipant = $conversation->participants()->where('user_id', $user->id)->exists();
        abort_unless($isParticipant || $user->isModerator(), 403);

        $data = $request->validate([
            'room_name' => ['required', 'string'],
        ]);

        $roomName = $data['room_name'];

        // Add user to call participants
        $participants = Cache::get("call_participants_{$roomName}", []);
        if (! in_array($user->id, $participants)) {
            $participants[] = $user->id;
            Cache::put("call_participants_{$roomName}", $participants, now()->addHours(24));
        }

        return response()->json([
            'room_name' => $roomName,
            'jitsi_domain' => config('services.jitsi.domain', 'meet.hillbcs.com'),
            'user_name' => $user->name,
            'user_avatar' => $user->profile_photo_url ?? null,
        ]);
    }
}
