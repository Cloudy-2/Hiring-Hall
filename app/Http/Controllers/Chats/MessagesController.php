<?php

namespace App\Http\Controllers\Chats;

use App\Events\ConversationTyping;
use App\Events\ConversationUpdated;
use App\Events\MessagePinned;
use App\Events\MessageReacted;
use App\Events\MessageSent;
use App\Events\UserMentioned;
use App\Http\Controllers\Controller;
use App\Models\Chats\Attachment;
use App\Models\Chats\Conversation;
use App\Models\Chats\ConversationParticipant;
use App\Models\Chats\DiscussionTopic;
use App\Models\Chats\Mention;
use App\Models\Chats\Message;
use App\Models\Chats\MessageReport;
use App\Models\Chats\User;
use App\Services\Chat\ModerationBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MessagesController extends Controller
{
    /**
     * Check if user can access conversation (member OR moderator)
     */
    private function canAccessConversation($user, Conversation $conversation): bool
    {
        // Moderators can access any conversation as silent members
        if ($user->isModerator()) {
            return true;
        }

        return $conversation->participants()->where('user_id', $user->id)->whereNull('left_at')->exists();
    }

    public function index(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        abort_unless($this->canAccessConversation($user, $conversation), 403);

        $limit = min((int) $request->query('limit', 10), 50);
        $beforeId = $request->query('before_id');

        $q = $conversation->messages()->with('user')->orderByDesc('id');

        if ($beforeId) {
            $q->where('id', '<', $beforeId);
        }

        $messages = $q->take($limit)->get()->sortBy('id')->values();

        return response()->json([
            'messages' => $messages,
            'data' => $messages,
            'next_page' => $messages->count() === $limit ? $messages->first()->id : null,
        ]);
    }

    // POST /chats/{conversation}/messages
    public function store(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        // Check if user is a participant OR a moderator (silent member)
        $participant = $conversation->participants()
            ->where('user_id', $user->id)
            ->whereNull('left_at')
            ->first();

        // Moderators can send messages even if not a participant
        if (! $participant && ! $user->isModerator()) {
            abort(403, 'You are not a member of this conversation.');
        }

        // Check if user is muted (unless they're a moderator)
        if ($participant && ! $user->isModerator()) {
            if ($participant->is_muted) {
                // Check if mute has expired
                if ($participant->muted_until && now()->gt($participant->muted_until)) {
                    // Unmute automatically
                    $participant->update([
                        'is_muted' => false,
                        'muted_until' => null,
                        'can_send_messages' => true,
                    ]);
                } else {
                    $muteMessage = $participant->muted_until
                        ? 'You are muted until '.$participant->muted_until->format('M d, Y H:i')
                        : 'You are muted in this conversation.';

                    return response()->json(['message' => $muteMessage], 403);
                }
            }

            if (! $participant->can_send_messages) {
                return response()->json(['message' => 'You cannot send messages in this conversation.'], 403);
            }
        }

        $topicId = $request->input('topic_id');
        if ($topicId) {
            $topic = DiscussionTopic::find($topicId);
            if ($topic) {
                if ($topic->visibility === 'moderator' && ! $user->isModerator()) {
                    return response()->json(['message' => 'This channel is for moderators only.'], 403);
                }
                if ($topic->is_readonly && ! $user->isModerator()) {
                    $isAdmin = ModerationBotService::isAdmin($conversation, $user->id);
                    if (! $isAdmin) {
                        return response()->json(['message' => 'This channel is read-only. Only moderators can post.'], 403);
                    }
                }
            }
        }

        if ($request->hasFile('video') && ! $user->isModerator()) {
            return response()->json(['message' => 'Only moderators can upload videos.'], 403);
        }

        $data = $request->validate([
            'body' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'in:text,gif,image,video,file'],
            'topic_id' => ['nullable', 'integer', 'exists:chat_discussion_topics,id'],
            'video' => ['nullable', 'file', 'mimes:mp4,webm,ogg,mov,quicktime,mkv,x-matroska', 'max:3145728'], // 3GB max
            'attachments' => ['nullable', 'array', 'max:10'],
            'attachments.*' => ['file', 'max:10240'], // 10MB per file
        ]);

        // Strip @everyone from non-moderator messages
        if (! empty($data['body']) && ! $user->isModerator()) {
            $data['body'] = preg_replace('/@everyone\b/', '', $data['body']);
            $data['body'] = preg_replace('/@([a-zA-Z0-9_\-]+(?:\s+[a-zA-Z0-9_\-]+)*)/', '$1', $data['body']);
        }

        $topicId = $data['topic_id'] ?? $request->input('topic_id');
        if (! empty($data['body']) && str_starts_with(trim($data['body']), '/')) {
            $commandText = trim($data['body']);
            $botResponse = ModerationBotService::processCommand(
                $conversation,
                $user->id,
                $commandText,
                $topicId ? (int) $topicId : null
            );

            if ($botResponse) {
                $message = $botResponse['success'] ?? $botResponse['error'] ?? $botResponse['content'] ?? null;

                if (isset($botResponse['success']) && ! isset($botResponse['bot_message'])) {
                    $channelName = '#general';
                    if ($topicId) {
                        $topic = DiscussionTopic::find($topicId);
                        $channelName = $topic ? '#'.$topic->name : '#general';
                    }
                    ModerationBotService::logCommandToHistory($conversation, $user->id, $commandText, $botResponse['success'], $channelName);
                }

                return response()->json([
                    'bot_response' => true,
                    'message' => $message,
                    'is_error' => isset($botResponse['error']),
                    'content' => $botResponse['content'] ?? null,
                    'title' => $botResponse['title'] ?? null,
                    'is_rich' => isset($botResponse['bot_message']),
                ]);
            }
        }

        // Check message against moderation rules (only for non-admin users)
        if (! empty($data['body']) && $conversation->type === 'group') {
            $violation = ModerationBotService::checkMessage(
                $conversation,
                $data['body'],
                $user->id
            );

            if ($violation) {
                $violationMessage = ModerationBotService::getViolationMessage($violation);

                return response()->json([
                    'blocked' => true,
                    'message' => $violationMessage,
                    'reason' => $violation['reason'],
                ], 403);
            }

            // Apply force uppercase if enabled (for non-admins)
            $settings = $conversation->settings ?? [];
            $rules = $settings['rules'] ?? [];
            if (($rules['forcecaps'] ?? false) && ! ModerationBotService::isAdmin($conversation, $user->id)) {
                $data['body'] = mb_strtoupper($data['body']);
            }
        }

        return DB::transaction(function () use ($conversation, $user, $data, $request, $topicId) {
            // Determine message type
            $messageType = 'text';

            // Check if type is explicitly set (e.g., 'gif' from frontend)
            if (isset($data['type']) && $data['type'] === 'gif') {
                $messageType = 'gif';
            } elseif ($request->hasFile('video')) {
                $messageType = 'video';
            } elseif ($request->hasFile('attachments')) {
                $firstFile = $request->file('attachments')[0] ?? null;
                if ($firstFile && str_starts_with($firstFile->getClientMimeType(), 'image/')) {
                    $messageType = 'image';
                } else {
                    $messageType = 'file';
                }
            }

            // 1) Create message
            $msg = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'type' => $messageType,
                'body' => $data['body'] ?? null,
            ]);

            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $path = $video->store('chat/videos', 'public');

                Attachment::create([
                    'message_id' => $msg->id,
                    'user_id' => $user->id,
                    'disk' => 'public',
                    'path' => $path,
                    'mime' => $video->getClientMimeType(),
                    'size' => $video->getSize(),
                    'original_name' => $video->getClientOriginalName(),
                ]);
            }

            // Handle image/file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $isImage = str_starts_with($file->getClientMimeType(), 'image/');
                    $folder = $isImage ? 'chat/images' : 'chat/files';
                    $path = $file->store($folder, 'public');

                    Attachment::create([
                        'message_id' => $msg->id,
                        'user_id' => $user->id,
                        'disk' => 'public',
                        'path' => $path,
                        'mime' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            if ($topicId) {
                DB::table('chat_discussion_topic_messages')->insert([
                    'topic_id' => $topicId,
                    'message_id' => $msg->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 2) Mark sender as read + reset unread_count
            ConversationParticipant::where('conversation_id', $conversation->id)
                ->where('user_id', $user->id)
                ->update([
                    'last_read_message_id' => $msg->id,
                    'last_read_at' => now(),
                    'unread_count' => 0,
                ]);

            // 3) Participant IDs
            $participantIds = $conversation->participants()->pluck('user_id')->toArray();

            // 3.5) Track mentions and broadcast to mentioned users
            $this->processMentions($msg, $conversation, $user, $participantIds, $topicId);

            // 4) Broadcast with retry (outside transaction to not block on failure)
            $this->broadcastWithRetry(function () use ($msg, $participantIds, $topicId) {
                broadcast(new MessageSent($msg->load('user', 'attachments', 'reactions'), $participantIds, $topicId ? (int) $topicId : null))->toOthers();
            });

            // 5) Broadcast ConversationUpdated for sidebar (each user gets own unread)
            foreach ($participantIds as $pid) {
                if ((int) $pid === (int) $user->id) {
                    $unread = 0;
                } else {
                    ConversationParticipant::where('conversation_id', $conversation->id)->where('user_id', $pid)->increment('unread_count');

                    $unread = ConversationParticipant::where('conversation_id', $conversation->id)->where('user_id', $pid)->value('unread_count') ?? 0;
                }

                $this->broadcastWithRetry(function () use ($conversation, $pid, $unread) {
                    broadcast(new ConversationUpdated($conversation->fresh(), $pid, $unread));
                });
            }

            return response()->json(['message' => $msg->load('user', 'attachments')]);
        });
    }

    /**
     * Broadcast with retry logic
     */
    private function broadcastWithRetry(callable $callback, int $maxRetries = 3, int $delayMs = 500): void
    {
        $attempts = 0;
        $lastException = null;

        while ($attempts < $maxRetries) {
            try {
                $callback();

                return;
            } catch (\Exception $e) {
                $lastException = $e;
                $attempts++;
                if ($attempts < $maxRetries) {
                    usleep($delayMs * 1000); // Convert to microseconds
                }
            }
        }

        // Log the failure but don't throw - message is already saved
        \Log::warning('Broadcast failed after '.$maxRetries.' attempts', [
            'error' => $lastException?->getMessage(),
        ]);
    }

    // POST /chats/{conversation}/typing
    public function typing(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        abort_unless($this->canAccessConversation($user, $conversation), 403);

        // Moderators typing silently - don't broadcast their typing indicator
        if ($user->isModerator() && ! $conversation->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['ok' => true]);
        }

        $this->broadcastWithRetry(function () use ($conversation, $user) {
            broadcast(new ConversationTyping($conversation, $user))->toOthers();
        }, 2, 200); // Fewer retries for typing since it's not critical

        return response()->json(['ok' => true]);
    }

    public function createConversation(Request $request)
    {
        $type = $request->input('type', 'dm');
        $authId = (int) Auth::id();

        // ───────────────────────────
        // DIRECT MESSAGE
        // ───────────────────────────
        if ($type === 'dm') {
            $data = $request->validate([
                'user_id' => ['required', 'integer', 'exists:users,id'],
            ]);

            $peerId = (int) $data['user_id'];

            if ($peerId === $authId) {
                return response()->json(
                    [
                        'message' => 'You cannot start a direct message with yourself.',
                    ],
                    422,
                );
            }

            // Reuse existing DM where both are active participants
            $conv = Conversation::where('type', 'dm')
                ->whereHas('participants', function ($q) use ($authId) {
                    $q->where('user_id', $authId)->whereNull('left_at');
                })
                ->whereHas('participants', function ($q) use ($peerId) {
                    $q->where('user_id', $peerId)->whereNull('left_at');
                })
                ->with(['participants.user:id,name,profile_photo_path', 'lastMessage'])
                ->first();

            if (! $conv) {
                // Create a new DM
                $conv = DB::transaction(function () use ($authId, $peerId) {
                    $c = Conversation::create([
                        'type' => 'dm',
                        'name' => null,
                        'is_group' => false,
                        'created_by' => $authId,
                    ]);

                    $now = now();

                    ConversationParticipant::insert([
                        [
                            'conversation_id' => $c->id,
                            'user_id' => $authId,
                            'role' => 'member',
                            'joined_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                        [
                            'conversation_id' => $c->id,
                            'user_id' => $peerId,
                            'role' => 'member',
                            'joined_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ],
                    ]);

                    return $c->fresh(['participants.user:id,name,profile_photo_path', 'lastMessage']);
                });
            }

            // Build a nice display title + avatar from the "other" person
            $other = $conv->participants->firstWhere('user_id', '!=', $authId);
            $otherUser = optional($other)->user;

            $title = $otherUser?->name ?? 'Direct Message';
            $avatarUrl = $otherUser?->profile_photo_url ?? null;

            return response()->json([
                'id' => $conv->id,
                'is_group' => false,
                'title' => $title,
                'name' => $conv->name,
                'last_message' => optional($conv->lastMessage)->body,
                'last_at' => optional($conv->lastMessage)->created_at,
                'updated_at' => $conv->updated_at,
                'avatar_url' => $avatarUrl, // 👈 for DM sidebar + message bubbles
            ]);
        }

        // ───────────────────────────
        // GROUP CHAT
        // ───────────────────────────
        if ($type === 'group') {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'user_ids' => ['required', 'array', 'min:1'],
                'user_ids.*' => ['integer', 'exists:users,id'],
            ]);

            // Clean member IDs (no duplicates, remove self)
            $memberIds = array_unique(array_map('intval', $data['user_ids']));
            $memberIds = array_values(array_filter($memberIds, fn ($id) => $id !== $authId));

            if (empty($memberIds)) {
                return response()->json(
                    [
                        'message' => 'Please select at least one other member for the group.',
                    ],
                    422,
                );
            }

            $conv = DB::transaction(function () use ($authId, $data, $memberIds) {
                $c = Conversation::create([
                    'type' => 'group',
                    'name' => $data['name'],
                    'is_group' => true,
                    'created_by' => $authId,
                ]);

                $now = now();
                $rows = [
                    [
                        'conversation_id' => $c->id,
                        'user_id' => $authId,
                        'role' => 'owner',
                        'joined_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                ];

                foreach ($memberIds as $uid) {
                    $rows[] = [
                        'conversation_id' => $c->id,
                        'user_id' => $uid,
                        'role' => 'member',
                        'joined_at' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                ConversationParticipant::insert($rows);

                return $c->fresh(['participants.user:id,name,profile_photo_path', 'lastMessage']);
            });

            return response()->json([
                'id' => $conv->id,
                'is_group' => true,
                'title' => $conv->name ?: 'Group Chat',
                'name' => $conv->name,
                'last_message' => optional($conv->lastMessage)->body,
                'last_at' => optional($conv->lastMessage)->created_at,
                'updated_at' => $conv->updated_at,
                'avatar_url' => null, // 👈 groups still use the icon on frontend
            ]);
        }

        return response()->json(['message' => 'Invalid chat type'], 422);
    }

    // Fetch 10 messages (initial + older)
    // public function index(Request $request, Conversation $conversation)
    // {
    //     abort_unless(
    //         $conversation->participants()->where('user_id', $request->user()->id)->exists(),
    //         403
    //     );

    //     $limit    = 10;
    //     $beforeId = $request->query('before_id');

    //     $query = $conversation->messages()
    //         ->with('user')
    //         ->orderByDesc('id');

    //     if ($beforeId) {
    //         $query->where('id', '<', $beforeId);
    //     }

    //     $messages = $query->take($limit)->get()->sortBy('id')->values();

    //     return response()->json([
    //         'data'      => $messages,
    //         'next_page' => $messages->count() === $limit ? $messages->first()->id : null,
    //     ]);
    // }

    // // Store + broadcast
    // public function store(Request $request, Conversation $conversation)
    // {
    //     abort_unless(
    //         $conversation->participants()->where('user_id', $request->user()->id)->exists(),
    //         403
    //     );

    //     $request->validate([
    //         'body' => 'nullable|string',
    //     ]);

    //     $message = $conversation->messages()->create([
    //         'user_id' => Auth::id(),
    //         'body'    => $request->body,
    //     ]);

    //     // load for response
    //     $message->load('user');

    //     // participants
    //     $users = $conversation->participants()->pluck('user_id')->toArray();

    //     // Broadcast real-time
    //     broadcast(new MessageSent($message, $users))->toOthers();

    //     // (Optional) update side menu
    //     foreach ($users as $userId) {
    //         $unread = ConversationParticipant::where('conversation_id', $conversation->id)
    //             ->where('user_id', $userId)
    //             ->value('unread_count') ?? 0;

    //         broadcast(new ConversationUpdated($conversation->fresh(), $userId, $unread))->toOthers();
    //     }

    //     return response()->json($message);
    // }

    public function list(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        // Ensure user is participant OR moderator
        abort_unless($this->canAccessConversation($user, $conversation), 403);

        $messages = $conversation
            ->messages()
            ->with(['user', 'attachments', 'reactions'])
            ->latest('id')
            ->paginate(40);

        // Mirror conversation meet to each message for the poller
        $meet = (int) ($conversation->meet ?? 0);
        $messages->getCollection()->transform(function ($m) use ($meet) {
            $m->setAttribute('meet', $meet);

            return $m;
        });

        // (Optional) also expose it at the top-level if you like:
        // $messages->additional(['meet' => $meet]);

        return response()->json($messages);
    }

    public function markAsRead(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        abort_unless($this->canAccessConversation($user, $conversation), 403);

        // Only update read state if user is an actual participant
        $isParticipant = $conversation->participants()->where('user_id', $user->id)->exists();

        if ($isParticipant) {
            // Latest message in this conversation
            $latest = $conversation->messages()->latest()->first();

            // Update participant row
            ConversationParticipant::where('conversation_id', $conversation->id)
                ->where('user_id', $user->id)
                ->update([
                    'last_read_message_id' => $latest?->id,
                    'last_read_at' => now(),
                    'unread_count' => 0,
                ]);

            // Broadcast updated sidebar state with unread_count = 0 for this user
            $this->broadcastWithRetry(function () use ($conversation, $user) {
                broadcast(new ConversationUpdated($conversation->fresh(), $user->id, 0));
            });
        }

        return response()->json(['ok' => true]);
    }

    public function markMentionsAsRead(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        $topicSlug = $request->input('topic');

        abort_unless($this->canAccessConversation($user, $conversation), 403);

        $query = Mention::where('user_id', $user->id)
            ->where('conversation_id', $conversation->id)
            ->where('is_read', false);

        if ($topicSlug && $topicSlug !== 'general') {
            $topic = DiscussionTopic::where('conversation_id', $conversation->id)
                ->where('slug', $topicSlug)
                ->first();
            if ($topic) {
                $query->where('topic_id', $topic->id);
            }
        } elseif ($topicSlug === 'general' || $topicSlug === null) {
            $query->whereNull('topic_id');
        }

        $query->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        $this->broadcastWithRetry(function () use ($user, $conversation, $topicSlug) {
            broadcast(new UserMentioned($user->id, $conversation->id, 0, $conversation->name ?? 'Chat', $topicSlug));
        });

        return response()->json(['ok' => true]);
    }

    public function getMentionCounts(Request $request)
    {
        $user = $request->user();

        $mentions = Mention::where('user_id', $user->id)
            ->where('is_read', false)
            ->join('chat_discussion_topics', 'chat_mentions.topic_id', '=', 'chat_discussion_topics.id', 'left')
            ->selectRaw('chat_mentions.conversation_id, COALESCE(chat_discussion_topics.slug, "general") as topic_slug, COUNT(*) as count')
            ->groupBy('chat_mentions.conversation_id', 'topic_slug')
            ->get();

        $result = [];
        foreach ($mentions as $m) {
            $key = $m->conversation_id.'_'.$m->topic_slug;
            $result[$key] = $m->count;
        }

        return response()->json(['mentions' => $result]);
    }

    // public function store(Request $request, Conversation $conversation)
    // {
    //     abort_unless($conversation->participants()->where('user_id', $request->user()->id)->exists(), 403);

    //     $data = $request->validate([
    //         'type' => ['nullable', 'in:text,image,file,system,call'],
    //         'body' => ['nullable', 'string'],
    //         'reply_to_message_id' => ['nullable', 'integer', 'exists:t_chats_messages,id'],
    //         'attachments.*' => ['file', 'max:10240'],
    //         'meet' => ['nullable', 'in:0,1'],
    //     ]);

    //     return DB::transaction(function () use ($request, $conversation, $data) {
    //         $msg = Message::create([
    //             'conversation_id' => $conversation->id,
    //             'user_id' => $request->user()->id,
    //             'type' => $data['type'] ?? 'text',
    //             'body' => $data['body'] ?? null,
    //             'reply_to_message_id' => $data['reply_to_message_id'] ?? null,
    //         ]);

    //         if ($request->hasFile('attachments')) {
    //             foreach ($request->file('attachments') as $file) {
    //                 $path = '/storage/' . $file->store('chat', 'public');
    //                 Attachment::create([
    //                     'message_id' => $msg->id,
    //                     'user_id' => $request->user()->id,
    //                     'disk' => 'public',
    //                     'path' => $path,
    //                     'mime' => $file->getClientMimeType(),
    //                     'size' => $file->getSize(),
    //                 ]);
    //             }
    //         }
    //         // 3) OPTIONAL: if request asked to flip meeting → update conversation
    //         //    e.g. your “Send as System” button posts meet=1 with the system message
    //         if (isset($data['meet'])) {
    //             $conversation->meet = (int) $data['meet'];
    //             $conversation->save();
    //         }

    //         // Update sender read state
    //         ConversationParticipant::where('conversation_id', $conversation->id)
    //             ->where('user_id', $request->user()->id)
    //             ->update(['last_read_message_id' => $msg->id, 'last_read_at' => now()]);

    //         // broadcast(new MessageSent($msg->load('user','attachments')))->toOthers();
    //         $msg->load('user', 'attachments');
    //         $msg->setAttribute('meet', (int) ($conversation->meet ?? 0)); // <- key line

    //         return response()->json($msg);
    //     });
    // }

    public function update(Request $request, int $messageId)
    {
        $message = Message::findOrFail($messageId);
        abort_unless($message->user_id === $request->user()->id, 403);
        $data = $request->validate(['body' => ['required', 'string']]);
        $message->update(['body' => $data['body'], 'edited_at' => now()]);

        return response()->json($message);
    }

    public function destroy(Request $request, int $messageId)
    {
        $message = Message::findOrFail($messageId);
        abort_unless($message->user_id === $request->user()->id, 403);

        // Mark as deleted by user (soft delete with flag)
        $message->update([
            'deleted_by_user' => true,
            'original_body' => $message->body,
            'body' => null,
        ]);

        // Broadcast the deletion to other users
        $this->broadcastWithRetry(function () use ($message) {
            broadcast(new \App\Events\MessageDeleted($message))->toOthers();
        });

        return response()->json([
            'ok' => true,
            'deleted_by' => $request->user()->name,
        ]);
    }

    public function react(Request $request, int $messageId)
    {
        $message = Message::findOrFail($messageId);
        $user = $request->user();
        $data = $request->validate(['reaction' => ['required', 'string', 'max:32']]);

        // Get existing reaction by this user on this message
        $existingReaction = $message->reactions()
            ->where('user_id', $user->id)
            ->first();
        if ($existingReaction) {
            if ($existingReaction->reaction === $data['reaction']) {
                $existingReaction->delete();
                $this->broadcastWithRetry(function () use ($message, $user, $data) {
                    broadcast(new MessageReacted($message, $user, $data['reaction'], 'removed'))->toOthers();
                });

                return response()->json(['ok' => true, 'action' => 'removed']);
            }

            $oldReaction = $existingReaction->reaction;
            $existingReaction->delete();
            $this->broadcastWithRetry(function () use ($message, $user, $oldReaction) {
                broadcast(new MessageReacted($message, $user, $oldReaction, 'removed'))->toOthers();
            });
        }

        // Add the new reaction (one per user per message)
        $message->reactions()->create([
            'user_id' => $user->id,
            'reaction' => $data['reaction'],
        ]);

        $this->broadcastWithRetry(function () use ($message, $user, $data) {
            broadcast(new MessageReacted($message, $user, $data['reaction'], 'added'))->toOthers();
        });

        return response()->json(['ok' => true, 'action' => 'added']);
    }

    public function unreact(Request $request, int $messageId)
    {
        $message = Message::findOrFail($messageId);
        $user = $request->user();
        $data = $request->validate(['reaction' => ['required', 'string', 'max:32']]);

        $deleted = $message
            ->reactions()
            ->where('user_id', $user->id)
            ->where('reaction', $data['reaction'])
            ->delete();

        if ($deleted) {
            $this->broadcastWithRetry(function () use ($message, $user, $data) {
                broadcast(new MessageReacted($message, $user, $data['reaction'], 'removed'))->toOthers();
            });
        }

        return response()->json(['ok' => true]);
    }

    public function pinMessage(Request $request, int $messageId)
    {
        $message = Message::with('user:id,name')->findOrFail($messageId);
        $conversation = $message->conversation;
        $user = $request->user();

        abort_unless($this->canAccessConversation($user, $conversation), 403);
        abort_unless($user->isModerator(), 403, 'Only moderators can pin messages');

        $conversation->pins()->firstOrCreate([
            'message_id' => $message->id,
            'user_id' => $user->id,
        ]);

        $this->broadcastWithRetry(function () use ($conversation, $message) {
            broadcast(new MessagePinned($conversation->id, $message, 'pinned'))->toOthers();
        });

        return response()->json(['ok' => true]);
    }

    public function unpinMessage(Request $request, int $messageId)
    {
        $message = Message::findOrFail($messageId);
        $conversation = $message->conversation;
        $user = $request->user();

        abort_unless($this->canAccessConversation($user, $conversation), 403);
        abort_unless($user->isModerator(), 403, 'Only moderators can unpin messages');

        $conversation->pins()->where('message_id', $message->id)->delete();

        $this->broadcastWithRetry(function () use ($conversation) {
            broadcast(new MessagePinned($conversation->id, null, 'unpinned'))->toOthers();
        });

        return response()->json(['ok' => true]);
    }

    public function forward(Request $request, int $messageId)
    {
        $message = Message::findOrFail($messageId);
        $user = $request->user();

        // Verify user can see the original message (member or moderator)
        abort_unless($this->canAccessConversation($user, $message->conversation), 403);

        $data = $request->validate([
            'conversation_ids' => ['required', 'array', 'min:1'],
            'conversation_ids.*' => ['integer', 'exists:chat_conversation,id'],
            'topic_id' => ['nullable', 'integer', 'exists:chat_discussion_topics,id'],
        ]);

        $forwarded = [];
        $topicId = $data['topic_id'] ?? null;

        foreach ($data['conversation_ids'] as $convId) {
            $targetConv = Conversation::find($convId);

            // Check if user can access target conversation (member or moderator)
            if (! $targetConv || ! $this->canAccessConversation($user, $targetConv)) {
                continue;
            }

            $newMsg = Message::create([
                'conversation_id' => $convId,
                'user_id' => $user->id,
                'type' => 'text',
                'body' => $message->body,
                'forwarded_from_message_id' => $message->id,
                'forwarded_metadata' => [
                    'original_user_name' => $message->user->name,
                    'original_conversation_id' => $message->conversation_id,
                    'forwarded_at' => now()->toIso8601String(),
                ],
            ]);

            // Link to topic if specified
            if ($topicId) {
                \DB::table('chat_discussion_topic_messages')->insert([
                    'topic_id' => $topicId,
                    'message_id' => $newMsg->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Broadcast to target conversation
            $participantIds = $targetConv->participants()->pluck('user_id')->toArray();
            $this->broadcastWithRetry(function () use ($newMsg, $participantIds) {
                broadcast(new MessageSent($newMsg->load('user', 'attachments', 'reactions'), $participantIds))->toOthers();
            });

            $forwarded[] = $newMsg->id;
        }

        return response()->json([
            'ok' => true,
            'forwarded_count' => count($forwarded),
            'message_ids' => $forwarded,
        ]);
    }

    public function report(Request $request, int $messageId)
    {
        $message = Message::findOrFail($messageId);
        $user = $request->user();

        abort_unless($this->canAccessConversation($user, $message->conversation), 403);

        // Can't report your own message
        if ($message->user_id === $user->id) {
            return response()->json(['message' => 'You cannot report your own message.'], 422);
        }

        $data = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $existing = MessageReport::where('message_id', $message->id)
            ->where('reporter_id', $user->id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'You have already reported this message.'], 422);
        }

        MessageReport::create([
            'message_id' => $message->id,
            'reporter_id' => $user->id,
            'conversation_id' => $message->conversation_id,
            'reason' => $data['reason'],
            'context' => [
                'message_body' => $message->body,
                'message_user' => $message->user->name ?? 'Unknown',
            ],
        ]);

        return response()->json(['ok' => true, 'message' => 'Report submitted successfully.']);
    }

    /**
    /**
     * Download attachment (serves file through PHP for better compatibility)
     */
    public function downloadAttachment(Attachment $attachment)
    {
        $user = Auth::user();

        // Check if user can access the conversation this attachment belongs to
        $message = $attachment->message;
        if (! $message) {
            abort(404);
        }

        $conversation = $message->conversation;
        if (! $this->canAccessConversation($user, $conversation)) {
            abort(403);
        }

        $path = Storage::disk($attachment->disk)->path($attachment->path);

        if (! file_exists($path)) {
            abort(404);
        }

        $filename = $attachment->original_name ?? basename($attachment->path);
        $mime = $attachment->mime ?? 'application/octet-stream';

        return response()->download($path, $filename, [
            'Content-Type' => $mime,
        ]);
    }

    /**
     * Stream attachment (for video/audio playback with range support)
     */
    public function streamAttachment(Attachment $attachment)
    {
        $user = Auth::user();

        // Check if user can access the conversation this attachment belongs to
        $message = $attachment->message;
        if (! $message) {
            abort(404);
        }

        $conversation = $message->conversation;
        if (! $this->canAccessConversation($user, $conversation)) {
            abort(403);
        }

        $path = Storage::disk($attachment->disk)->path($attachment->path);

        if (! file_exists($path)) {
            abort(404);
        }

        $mime = $attachment->mime ?? 'application/octet-stream';
        $size = filesize($path);
        $start = 0;
        $end = $size - 1;

        $headers = [
            'Content-Type' => $mime,
            'Accept-Ranges' => 'bytes',
        ];

        // Handle range requests for video seeking
        if (request()->hasHeader('Range')) {
            $range = request()->header('Range');
            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = intval($matches[1]);
                if (! empty($matches[2])) {
                    $end = intval($matches[2]);
                }
            }

            $headers['Content-Range'] = "bytes $start-$end/$size";
            $headers['Content-Length'] = $end - $start + 1;

            return response()->stream(function () use ($path, $start, $end) {
                $stream = fopen($path, 'rb');
                fseek($stream, $start);
                $remaining = $end - $start + 1;
                $bufferSize = 8192;

                while ($remaining > 0 && ! feof($stream)) {
                    $read = min($bufferSize, $remaining);
                    echo fread($stream, $read);
                    $remaining -= $read;
                    flush();
                }

                fclose($stream);
            }, 206, $headers);
        }

        $headers['Content-Length'] = $size;

        return response()->stream(function () use ($path) {
            readfile($path);
        }, 200, $headers);
    }

    private function processMentions(Message $message, Conversation $conversation, $sender, array $participantIds, $topicId = null): void
    {
        $body = $message->body ?? '';
        if (empty($body)) {
            return;
        }

        $mentionedUserIds = [];
        $isEveryone = false;

        if (preg_match('/@everyone\b/', $body)) {
            $isEveryone = true;
            $mentionedUserIds = array_filter($participantIds, fn ($id) => (int) $id !== (int) $sender->id);
        }

        preg_match_all('/@([a-zA-Z0-9_\-]+(?:\s+[a-zA-Z0-9_\-]+)*)/', $body, $matches);
        if (! empty($matches[1])) {
            $mentionedNames = $matches[1];
            $users = \App\Models\User::whereIn('name', $mentionedNames)
                ->whereIn('id', $participantIds)
                ->where('id', '!=', $sender->id)
                ->pluck('id')
                ->toArray();
            $mentionedUserIds = array_unique(array_merge($mentionedUserIds, $users));
        }

        if (empty($mentionedUserIds)) {
            return;
        }

        $topicSlug = null;
        if ($topicId) {
            $topic = DiscussionTopic::find($topicId);
            $topicSlug = $topic?->slug;
        }

        foreach ($mentionedUserIds as $userId) {
            Mention::create([
                'user_id' => $userId,
                'message_id' => $message->id,
                'conversation_id' => $conversation->id,
                'mentioned_by' => $sender->id,
                'is_everyone' => $isEveryone,
                'is_read' => false,
                'topic_id' => $topicId,
            ]);

            $unreadCount = Mention::where('user_id', $userId)
                ->where('conversation_id', $conversation->id)
                ->where('is_read', false)
                ->count();

            $this->broadcastWithRetry(function () use ($userId, $conversation, $unreadCount, $topicSlug) {
                broadcast(new UserMentioned($userId, $conversation->id, $unreadCount, $conversation->name ?? 'Chat', $topicSlug));
            });
        }
    }
}
