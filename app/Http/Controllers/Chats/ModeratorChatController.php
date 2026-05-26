<?php

namespace App\Http\Controllers\Chats;

use App\Events\MemberAdded;
use App\Events\MemberLeft;
use App\Events\MessageDeleted;
use App\Events\UserAddedToGroup;
use App\Events\UserKicked;
use App\Events\UserMuted;
use App\Events\UserRemovedFromGroup;
use App\Http\Controllers\Controller;
use App\Models\Chats\Conversation;
use App\Models\Chats\ConversationParticipant;
use App\Models\Chats\DiscussionTopic;
use App\Models\Chats\Message;
use App\Models\Chats\WaitingList;
use App\Models\User;
use Illuminate\Http\Request;

class ModeratorChatController extends Controller
{
    /**
     * Global Feed - All messages from all conversations in one view
     */
    public function globalFeed(Request $request)
    {
        $perPage = $request->integer('per_page', 50);
        $beforeId = $request->integer('before_id');
        $conversationFilter = $request->input('conversation');
        $userFilter = $request->input('user');
        $search = $request->input('search');

        $query = Message::with([
            'user:id,name,email,profile_photo_path,role',
            'conversation:id,name,type',
            'attachments',
        ])
            ->orderByDesc('created_at');

        if ($beforeId) {
            $query->where('id', '<', $beforeId);
        }

        if ($conversationFilter) {
            $query->where('conversation_id', $conversationFilter);
        }

        if ($userFilter) {
            $query->where('user_id', $userFilter);
        }

        if ($search) {
            $query->where('body', 'like', "%{$search}%");
        }

        $messages = $query->paginate($perPage);

        // Get conversation stats
        $stats = [
            'total_messages' => Message::count(),
            'total_conversations' => Conversation::count(),
            'messages_today' => Message::whereDate('created_at', today())->count(),
            'active_users' => Message::whereDate('created_at', today())->distinct('user_id')->count('user_id'),
        ];

        // Get conversations for filter dropdown
        $conversations = Conversation::select('id', 'name', 'type')
            ->withCount('messages')
            ->orderByDesc('messages_count')
            ->limit(50)
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'messages' => $messages,
                'stats' => $stats,
            ]);
        }

        return view('modules.chats.moderator.global-feed', [
            'messages' => $messages,
            'stats' => $stats,
            'conversations' => $conversations,
            'filters' => [
                'conversation' => $conversationFilter,
                'user' => $userFilter,
                'search' => $search,
            ],
        ]);
    }

    /**
     * Get waiting list of candidates
     */
    public function waitingList(Request $request)
    {
        $waitingUsers = WaitingList::with(['user:id,name,email,profile_photo_path'])
            ->waiting()
            ->orderBy('created_at')
            ->paginate(20);

        return response()->json([
            'waiting_list' => $waitingUsers,
        ]);
    }

    /**
     * Assign a candidate to a conversation
     */
    public function assignToConversation(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'conversation_id' => ['required', 'integer', 'exists:chat_conversation,id'],
        ]);

        $waitingEntry = WaitingList::where('user_id', $data['user_id'])
            ->waiting()
            ->first();

        if (! $waitingEntry) {
            // Create entry if not exists
            $waitingEntry = WaitingList::create([
                'user_id' => $data['user_id'],
                'status' => 'waiting',
            ]);
        }

        $waitingEntry->assignToConversation($data['conversation_id'], $request->user()->id);

        return response()->json([
            'ok' => true,
            'message' => 'User assigned to conversation successfully.',
        ]);
    }

    /**
     * Direct add member(s) to conversation (bypass waiting list)
     */
    public function directAddMember(Request $request, Conversation $conversation)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'role' => ['nullable', 'in:member,admin'],
        ]);

        $userIds = $data['user_ids'] ?? ($data['user_id'] ? [$data['user_id']] : []);

        if (empty($userIds)) {
            return response()->json(['message' => 'No users specified.'], 422);
        }

        $addedUsers = [];
        foreach ($userIds as $userId) {
            ConversationParticipant::updateOrCreate(
                ['conversation_id' => $conversation->id, 'user_id' => $userId],
                [
                    'joined_at' => now(),
                    'left_at' => null,
                    'role' => $data['role'] ?? 'member',
                ]
            );

            WaitingList::where('user_id', $userId)
                ->waiting()
                ->update([
                    'conversation_id' => $conversation->id,
                    'assigned_by' => $request->user()->id,
                    'status' => 'assigned',
                    'assigned_at' => now(),
                ]);

            $user = User::find($userId);
            if ($user) {
                $addedUsers[] = ['id' => $userId, 'name' => $user->name];
            }

            // Notify the added user on their private channel
            broadcast(new UserAddedToGroup($userId, $conversation));
        }

        if (! empty($addedUsers)) {
            $names = array_column($addedUsers, 'name');
            $namesText = count($names) > 2
                ? implode(', ', array_slice($names, 0, 2)).' and '.(count($names) - 2).' others'
                : implode(' and ', $names);

            Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => $request->user()->id,
                'type' => 'system',
                'body' => "A moderator added {$namesText} to the group",
            ]);

            broadcast(new MemberAdded(
                $conversation->id,
                $addedUsers,
                $request->user()->name
            ))->toOthers();
        }

        $added = count($addedUsers);

        return response()->json([
            'ok' => true,
            'message' => $added === 1 ? 'Member added successfully.' : "{$added} members added successfully.",
            'added_count' => $added,
        ]);
    }

    /**
     * Mute a member in conversation
     */
    public function muteMember(Request $request, Conversation $conversation, User $user)
    {
        $data = $request->validate([
            'duration' => ['nullable', 'integer', 'min:1'], // minutes
            'permanent' => ['nullable', 'boolean'],
        ]);

        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $participant) {
            return response()->json(['message' => 'User is not a member of this conversation.'], 404);
        }

        $mutedUntil = null;
        if (! ($data['permanent'] ?? false)) {
            $duration = $data['duration'] ?? 60; // default 1 hour
            $mutedUntil = now()->addMinutes($duration);
        }

        $participant->update([
            'is_muted' => true,
            'muted_until' => $mutedUntil,
            'can_send_messages' => false,
        ]);

        return response()->json([
            'ok' => true,
            'message' => $mutedUntil
                ? "User muted until {$mutedUntil->format('M d, Y H:i')}"
                : 'User muted permanently.',
        ]);
    }

    /**
     * Unmute a member
     */
    public function unmuteMember(Request $request, Conversation $conversation, User $user)
    {
        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->update([
                'is_muted' => false,
                'muted_until' => null,
                'can_send_messages' => true,
            ]);

        broadcast(new \App\Events\UserUnmuted(
            $user->id,
            $conversation->id,
            $user->name ?? 'User'
        ))->toOthers();

        return response()->json([
            'ok' => true,
            'message' => 'User unmuted successfully.',
        ]);
    }

    /**
     * Kick member from conversation
     */
    public function kickMember(Request $request, Conversation $conversation, User $user)
    {
        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->update(['left_at' => now()]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $request->user()->id,
            'type' => 'system',
            'body' => "{$user->name} was removed by a moderator",
        ]);

        broadcast(new UserKicked($user->id, $conversation->id, $user->name ?? 'User'))->toOthers();
        broadcast(new UserRemovedFromGroup($user->id, $conversation));

        return response()->json([
            'ok' => true,
            'message' => 'User removed from conversation.',
        ]);
    }

    /**
     * Mute notifications for the current user in this conversation
     */
    public function muteSelf(Request $request, Conversation $conversation)
    {
        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $request->user()->id)
            ->whereNull('left_at')
            ->first();

        if (! $participant) {
            return response()->json(['message' => 'You are not a member of this conversation.'], 404);
        }

        $participant->update([
            'is_muted' => true,
            'muted_until' => null,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'You will no longer receive notifications for this conversation.',
        ]);
    }

    /**
     * Unmute notifications for the current user in this conversation
     */
    public function unmuteSelf(Request $request, Conversation $conversation)
    {
        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $request->user()->id)
            ->whereNull('left_at')
            ->update([
                'is_muted' => false,
                'muted_until' => null,
            ]);

        return response()->json([
            'ok' => true,
            'message' => 'Notifications re-enabled for this conversation.',
        ]);
    }

    /**
     * Lock conversation so members cannot send messages
     */
    public function lockConversation(Request $request, Conversation $conversation)
    {
        $settings = $conversation->settings ?? [];
        $settings['locked'] = true;
        $conversation->update(['settings' => $settings]);

        return response()->json([
            'ok' => true,
            'message' => 'Conversation has been locked.',
        ]);
    }

    /**
     * Unlock conversation
     */
    public function unlockConversation(Request $request, Conversation $conversation)
    {
        $settings = $conversation->settings ?? [];
        $settings['locked'] = false;
        $conversation->update(['settings' => $settings]);

        return response()->json([
            'ok' => true,
            'message' => 'Conversation has been unlocked.',
        ]);
    }

    /**
     * Set channel visibility for a member
     */
    public function setMemberChannels(Request $request, Conversation $conversation, User $user)
    {
        $data = $request->validate([
            'channel_ids' => ['nullable', 'array'],
            'channel_ids.*' => ['integer'],
        ]);

        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $participant) {
            return response()->json(['message' => 'User is not a member.'], 404);
        }

        $participant->update([
            'can_view_channels' => $data['channel_ids'] ?? null, // null = all channels
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Channel visibility updated.',
        ]);
    }

    /**
     * Create a channel with permissions
     */
    public function createChannel(Request $request, Conversation $conversation)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:200'],
            'is_readonly' => ['nullable', 'boolean'],
            'visibility' => ['nullable', 'in:public,private,admin'],
            'allowed_roles' => ['nullable', 'array'],
        ]);

        $slug = strtolower(preg_replace('/[^a-z0-9-]/', '-', $data['name']));
        $slug = preg_replace('/-+/', '-', trim($slug, '-'));

        // Check uniqueness
        $exists = DiscussionTopic::where('conversation_id', $conversation->id)
            ->where('slug', $slug)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Channel name already exists.'], 422);
        }

        $maxPosition = DiscussionTopic::where('conversation_id', $conversation->id)->max('position') ?? 0;

        $topic = DiscussionTopic::create([
            'conversation_id' => $conversation->id,
            'created_by' => $request->user()->id,
            'slug' => $slug,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'position' => $maxPosition + 1,
            'visibility' => $data['visibility'] ?? 'public',
            'is_readonly' => $data['is_readonly'] ?? false,
            'allowed_roles' => $data['allowed_roles'] ?? null,
        ]);

        return response()->json([
            'ok' => true,
            'topic' => [
                'id' => $topic->id,
                'slug' => $topic->slug,
                'name' => '#'.$topic->name,
                'is_readonly' => $topic->is_readonly,
            ],
        ]);
    }

    /**
     * Update channel settings
     */
    public function updateChannel(Request $request, Conversation $conversation, DiscussionTopic $topic)
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:200'],
            'is_readonly' => ['nullable', 'boolean'],
            'visibility' => ['nullable', 'in:public,private,admin'],
            'allowed_roles' => ['nullable', 'array'],
            'is_archived' => ['nullable', 'boolean'],
        ]);

        $topic->update(array_filter($data, fn ($v) => ! is_null($v)));

        return response()->json([
            'ok' => true,
            'message' => 'Channel updated.',
        ]);
    }

    /**
     * Get all candidates for assignment dropdown
     */
    public function getCandidates(Request $request)
    {
        $search = $request->query('q', '');

        $query = User::where('role', 'applicant')
            ->select('id', 'name', 'email', 'profile_photo_path');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $candidates = $query->limit(20)->get()->map(fn ($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'avatar' => $u->profile_photo_path
                ? asset('storage/'.$u->profile_photo_path)
                : url('/user.png'),
        ]);

        return response()->json($candidates);
    }

    /**
     * Get users by role for adding to conversation
     */
    public function getUsersByRole(Request $request)
    {
        $search = $request->query('q', '');
        $role = $request->query('role', '');
        $conversationId = $request->query('conversation_id');

        $existingUserIds = [];
        if ($conversationId) {
            $existingUserIds = ConversationParticipant::where('conversation_id', $conversationId)
                ->whereNull('left_at')
                ->pluck('user_id')
                ->toArray();
        }

        $query = User::select('id', 'name', 'email', 'profile_photo_path', 'role');

        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (! empty($existingUserIds)) {
            $query->whereNotIn('id', $existingUserIds);
        }

        $users = $query->limit(30)->get()->map(fn ($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'role' => $u->role,
            'avatar' => $u->profile_photo_path
                ? asset('storage/'.$u->profile_photo_path)
                : 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($u->name).'&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981',
        ]);

        return response()->json($users);
    }

    /**
     * Get all groups for assignment
     */
    public function getGroups(Request $request)
    {
        $groups = Conversation::where('type', 'group')
            ->select('id', 'name', 'photo')
            ->withCount('participants')
            ->orderBy('name')
            ->get()
            ->map(fn ($g) => [
                'id' => $g->id,
                'name' => $g->name ?? 'Group #'.$g->id,
                'photo' => $g->photo ? asset('storage/'.$g->photo) : null,
                'member_count' => $g->participants_count,
            ]);

        return response()->json($groups);
    }

    /**
     * Mute user in conversation (from message context)
     */
    public function muteUser(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'conversation_id' => ['required', 'integer', 'exists:chat_conversation,id'],
            'duration_hours' => ['required', 'integer', 'min:0'],
        ]);

        $participant = ConversationParticipant::where('conversation_id', $data['conversation_id'])
            ->where('user_id', $data['user_id'])
            ->whereNull('left_at')
            ->first();

        if (! $participant) {
            return response()->json(['message' => 'User is no longer a member of this conversation.'], 400);
        }

        $user = User::find($data['user_id']);
        $mutedUntil = null;
        if ($data['duration_hours'] > 0) {
            $mutedUntil = now()->addHours($data['duration_hours']);
        }

        $participant->update([
            'is_muted' => true,
            'muted_until' => $mutedUntil,
            'can_send_messages' => false,
        ]);

        broadcast(new UserMuted(
            $data['user_id'],
            $data['conversation_id'],
            $mutedUntil?->toIso8601String(),
            $user->name ?? 'User'
        ))->toOthers();

        return response()->json([
            'ok' => true,
            'message' => $mutedUntil
                ? "User muted until {$mutedUntil->format('M d, Y H:i')}"
                : 'User muted indefinitely.',
        ]);
    }

    /**
     * Kick user from conversation (from message context)
     */
    public function kickUser(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'conversation_id' => ['required', 'integer', 'exists:chat_conversation,id'],
        ]);

        $participant = ConversationParticipant::where('conversation_id', $data['conversation_id'])
            ->where('user_id', $data['user_id'])
            ->whereNull('left_at')
            ->first();

        if (! $participant) {
            return response()->json(['message' => 'User is no longer a member of this conversation.'], 400);
        }

        $user = User::find($data['user_id']);
        $conversation = Conversation::find($data['conversation_id']);
        $participant->update(['left_at' => now()]);

        Message::create([
            'conversation_id' => $data['conversation_id'],
            'user_id' => $request->user()->id,
            'type' => 'system',
            'body' => "{$user->name} was removed by a moderator",
        ]);

        broadcast(new UserKicked(
            $data['user_id'],
            $data['conversation_id'],
            $user->name ?? 'User'
        ))->toOthers();

        // Notify the kicked user on their private channel
        broadcast(new UserRemovedFromGroup($data['user_id'], $conversation));

        return response()->json([
            'ok' => true,
            'message' => 'User removed from conversation.',
        ]);
    }

    /**
     * Get muted users in a conversation (moderator only)
     */
    public function getMutedUsers(Request $request)
    {
        $conversationId = $request->query('conversation_id');

        if (! $conversationId) {
            return response()->json(['muted_users' => []]);
        }

        $mutedUsers = ConversationParticipant::where('conversation_id', $conversationId)
            ->where('is_muted', true)
            ->whereNull('left_at')
            ->with('user:id,name,email,profile_photo_path')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->user_id,
                'name' => $p->user->name ?? 'Unknown',
                'email' => $p->user->email ?? '',
                'avatar' => $p->user->profile_photo_path
                    ? asset('storage/'.$p->user->profile_photo_path)
                    : 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($p->user->name ?? 'U').'&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981',
                'muted_until' => $p->muted_until?->toIso8601String(),
                'is_permanent' => is_null($p->muted_until),
            ]);

        return response()->json(['muted_users' => $mutedUsers]);
    }

    /**
     * Delete message (moderator action - soft delete with replacement text)
     */
    public function deleteMessage(Request $request)
    {
        $data = $request->validate([
            'message_id' => ['required', 'integer', 'exists:chat_messages,id'],
        ]);

        $message = Message::findOrFail($data['message_id']);

        // Store original body for audit log if needed
        $originalBody = $message->body;

        // Update message to show it was removed by moderator
        $message->update([
            'body' => null,
            'type' => 'system',
            'deleted_by_moderator' => true,
            'original_body' => $originalBody, // Keep for audit
        ]);

        // Broadcast to all users in the conversation
        broadcast(new MessageDeleted($message))->toOthers();

        return response()->json([
            'ok' => true,
            'message' => 'Message deleted.',
        ]);
    }

    /**
     * User leaves conversation voluntarily
     */
    public function leaveConversation(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->whereNull('left_at')
            ->first();

        if (! $participant) {
            return response()->json(['message' => 'You are not a member of this conversation.'], 404);
        }

        $participant->update(['left_at' => now()]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'type' => 'system',
            'body' => "{$user->name} left the group",
        ]);

        broadcast(new MemberLeft(
            $user->id,
            $conversation->id,
            $user->name
        ))->toOthers();

        return response()->json([
            'ok' => true,
            'message' => 'You have left the conversation.',
        ]);
    }
}
