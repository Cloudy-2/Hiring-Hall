<?php

namespace App\Http\Controllers\Chats;

use App\Events\ConversationUpdated;
use App\Events\UserAddedToGroup;
use App\Http\Controllers\Controller;
use App\Models\Chats\Conversation;
use App\Models\Chats\ConversationParticipant;
use App\Models\Chats\GroupJoinRequest;
use App\Models\Chats\Message;
use App\Models\Chats\MessageReport;
use App\Models\Chats\PresenceStatus;
use App\Models\ChatTodo;
use App\Models\PersonalTag;
use App\Models\User;
use App\Services\Chat\ModerationBotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = Conversation::forUser($user->id)
            ->with([
                'participants.user:id,name,profile_photo_path',
                'messages' => function ($q) {
                    $q->latest()->limit(1); // grab last message
                },
            ])
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($conv) use ($user) {
                $isGroup = ($conv->type === 'group');

                if ($isGroup) {
                    $displayTitle = $conv->name ?? 'Group Chat';
                } else {
                    $other = $conv->participants
                        ->firstWhere('user_id', '!=', $user->id);

                    $displayTitle = optional(optional($other)->user)->name
                        ?? 'Direct Message';
                }

                $conv->display_title = $displayTitle;
                $conv->is_group = $isGroup;

                $latest = $conv->messages->first();
                $conv->last_message_body = $latest?->body;
                $conv->last_message_at = $latest?->created_at;

                $pivot = $conv->participants
                    ->where('user_id', $user->id)
                    ->first();

                $conv->unread_count = $pivot->unread_count ?? 0;

                return $conv;
            });

        return view('modules.chats.index', [
            'conversations' => $conversations,
        ]);
    }

    public function v2(Request $request)
    {
        $user = $request->user();
        $conversationId = $request->integer('conversation');
        $personalTagId = $request->integer('personal_tag');
        $targetUserId = $request->integer('user'); // Handle ?user= parameter for Message Now
        $viewHome = $request->query('view') === 'home';
        $filter = $request->query('filter'); // Handle filter parameter (unread, threads, mentions, drafts)

        // Get conversations the user is part of
        $myConversations = Conversation::query()
            ->with([
                'participants.user:id,name,email,profile_photo_path,role',
                'messages' => function ($q) {
                    $q->latest()->limit(1);
                },
            ])
            ->whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereNull('left_at');
            })
            ->orderByDesc('updated_at')
            ->get();

        // For moderators: also get ALL group conversations (servers) they're not part of
        $allServers = collect();
        if ($user->isModerator()) {
            $myGroupIds = $myConversations->where('type', 'group')->pluck('id')->toArray();
            $allServers = Conversation::query()
                ->with([
                    'participants.user:id,name,email,profile_photo_path,role',
                    'messages' => function ($q) {
                        $q->latest()->limit(1);
                    },
                ])
                ->where('type', 'group')
                ->whereNotIn('id', $myGroupIds)
                ->orderByDesc('updated_at')
                ->get();
        }

        // Merge: my conversations + all other servers (for moderators)
        $conversations = $myConversations->merge($allServers);

        // Handle ?user= parameter - find or create DM conversation with target user
        if ($targetUserId && $targetUserId !== $user->id) {
            $targetUser = User::find($targetUserId);
            if ($targetUser) {
                // Find existing DM conversation between current user and target user
                $existingDm = $conversations->first(function ($conv) use ($user, $targetUserId) {
                    if ($conv->type !== 'dm') {
                        return false;
                    }
                    $participantIds = $conv->participants->pluck('user_id')->toArray();

                    return count($participantIds) === 2
                        && in_array($user->id, $participantIds)
                        && in_array($targetUserId, $participantIds);
                });

                if ($existingDm) {
                    // Use existing conversation
                    $conversationId = $existingDm->id;
                } else {
                    // Create new DM conversation
                    $newConv = DB::transaction(function () use ($user, $targetUserId) {
                        $conv = Conversation::create([
                            'type' => 'dm',
                            'name' => null,
                            'created_by' => $user->id,
                        ]);

                        ConversationParticipant::insert([
                            [
                                'conversation_id' => $conv->id,
                                'user_id' => $user->id,
                                'role' => 'member',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ],
                            [
                                'conversation_id' => $conv->id,
                                'user_id' => $targetUserId,
                                'role' => 'member',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ],
                        ]);

                        return $conv;
                    });

                    // Reload conversations to include the new one
                    $myConversations = Conversation::query()
                        ->with([
                            'participants.user:id,name,email,profile_photo_path,role',
                            'messages' => function ($q) {
                                $q->latest()->limit(1);
                            },
                        ])
                        ->whereHas('participants', function ($q) use ($user) {
                            $q->where('user_id', $user->id)->whereNull('left_at');
                        })
                        ->orderByDesc('updated_at')
                        ->get();

                    // For moderators: also get ALL group conversations
                    $allServers = collect();
                    if ($user->isModerator()) {
                        $myGroupIds = $myConversations->where('type', 'group')->pluck('id')->toArray();
                        $allServers = Conversation::query()
                            ->with([
                                'participants.user:id,name,email,profile_photo_path,role',
                                'messages' => function ($q) {
                                    $q->latest()->limit(1);
                                },
                            ])
                            ->where('type', 'group')
                            ->whereNotIn('id', $myGroupIds)
                            ->orderByDesc('updated_at')
                            ->get();
                    }

                    $conversations = $myConversations->merge($allServers);
                    $conversationId = $newConv->id;
                }
            }
        }

        $selectedPersonalTag = null;
        if ($personalTagId) {
            $selectedPersonalTag = PersonalTag::where('id', $personalTagId)
                ->where('user_id', $user->id)
                ->first();
        }

        $selectedConversation = null;
        if (! $selectedPersonalTag) {
            if ($conversationId) {
                $selectedConversation = $conversations->firstWhere('id', $conversationId);
            }

        }

        // Check if user can manage this group (owner, admin, or system moderator)
        $canDeleteGroup = false;
        if ($selectedConversation && $selectedConversation->type === 'group') {
            if ($user->isModerator()) {
                $canDeleteGroup = true;
            } else {
                $canDeleteGroup = (int) $selectedConversation->created_by === (int) $user->id
                    || $user->hasGroupAdminAccess($selectedConversation->id);
            }
        }

        $deleteGroupUrl = $canDeleteGroup
            ? route('conversations.destroy', ['conversation' => $selectedConversation->id])
            : null;

        $participantIds = $conversations->flatMap(function ($conv) {
            return $conv->participants->pluck('user_id');
        })
            ->push($user->id)
            ->unique()
            ->values();

        $presence = DB::table('chat_presence_statuses')
            ->whereIn('user_id', $participantIds)
            ->get()
            ->keyBy('user_id');

        $statusFor = function (?int $userId) use ($presence) {
            if (! $userId) {
                return 'offline';
            }
            $record = $presence->get($userId);
            if (! $record) {
                return 'offline';
            }
            // Check if presence has expired
            if ($record->expires_at && now()->gt($record->expires_at)) {
                return 'offline';
            }

            return $record->status ?? 'offline';
        };

        // Get location data for users (conversation_id and topic_slug)
        $locationFor = function (?int $userId) use ($presence) {
            if (! $userId) {
                return ['conversation_id' => null, 'topic_slug' => null];
            }
            $record = $presence->get($userId);
            if (! $record) {
                return ['conversation_id' => null, 'topic_slug' => null];
            }
            // Check if presence has expired
            if ($record->expires_at && now()->gt($record->expires_at)) {
                return ['conversation_id' => null, 'topic_slug' => null];
            }

            return [
                'conversation_id' => $record->current_conversation_id ?? null,
                'topic_slug' => $record->current_topic_slug ?? null,
            ];
        };

        $avatarFor = function ($model, string $fallbackName = 'NA') {
            $path = $model?->profile_photo_path ?? null;
            if ($path) {
                return asset('storage/'.ltrim($path, '/'));
            }

            $name = $model?->name ?? $fallbackName;

            return 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($name).'&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981';
        };

        $grouped = $conversations->groupBy(function ($conv) {
            return $conv->type;
        });

        $servers = ($grouped['group'] ?? collect())->map(function ($conversation) use ($user) {
            $pivot = $conversation->participants->firstWhere('user_id', $user->id);
            $settings = $conversation->settings ?? [];
            $activeParticipants = $conversation->participants->whereNull('left_at');
            $memberCount = $activeParticipants->count();
            $onlineCount = $activeParticipants->filter(function ($p) {
                return $p->user && PresenceStatus::isOnline($p->user->id);
            })->count();

            return [
                'id' => $conversation->id,
                'name' => $conversation->name ?? 'Group #'.$conversation->id,
                'type' => 'group',
                'avatar' => $conversation->photo ? asset('storage/'.ltrim($conversation->photo, '/')) : null,
                'initials' => Str::of($conversation->name ?? 'Group')->trim()->substr(0, 2)->upper(),
                'unread' => $pivot->unread_count ?? 0,
                'member_count' => $memberCount,
                'online_count' => $onlineCount,
                'description' => $settings['description'] ?? null,
                'is_public' => $conversation->is_public ?? false,
                'created_by' => $conversation->created_by,
            ];
        })->values();

        $directMessages = ($grouped['dm'] ?? collect())->map(function ($conversation) use ($user, $statusFor, $avatarFor) {
            $otherParticipant = $conversation->participants->firstWhere('user_id', '!=', $user->id);
            $otherUser = $otherParticipant?->user;
            $latest = $conversation->messages->first();

            return [
                'id' => $conversation->id,
                'user_id' => $otherUser?->id,
                'name' => $otherUser?->name ?? 'Direct Message',
                'email' => $otherUser?->email ?? '',
                'subtitle' => $latest?->body ? Str::limit(strip_tags($latest->body), 40) : 'Start a conversation',
                'status' => $statusFor($otherUser?->id),
                'avatar' => $avatarFor($otherUser, 'DM'),
                'initials' => Str::of($otherUser?->name ?? 'DM')->trim()->substr(0, 2)->upper(),
                'last_message_at' => $latest?->created_at,
            ];
        })->sortByDesc('last_message_at')->values();

        $frequentFriends = $directMessages->take(4);

        $pendingRequests = DB::table('chat_friendships')
            ->where('addressee_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $friendStats = [
            'friends' => $directMessages->count(),
            'pending' => $pendingRequests,
            'online' => $directMessages->filter(function ($dm) {
                return in_array($dm['status'], ['online', 'idle', 'dnd']);
            })->count(),
        ];

        $messages = collect();
        $topics = collect();
        $participantPayload = collect();
        $personalTagMessages = collect();
        $pinnedMessage = null;

        // Load personal tag messages if viewing a personal tag
        if ($selectedPersonalTag) {
            $personalTagMessages = DB::table('personal_tag_messages')
                ->where('personal_tag_id', $selectedPersonalTag->id)
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->limit(50)
                ->get()
                ->map(function ($msg) {
                    return [
                        'id' => $msg->id,
                        'body' => $msg->body,
                        'created_at' => $msg->created_at,
                        'forwarded_from_message_id' => $msg->forwarded_from_message_id,
                        'forwarded_metadata' => $msg->forwarded_metadata ? json_decode($msg->forwarded_metadata, true) : null,
                    ];
                });
        } elseif ($selectedConversation) {
            $currentTopicSlug = $request->query('topic');
            $currentTopic = null;

            if ($currentTopicSlug) {
                $topicQuery = DB::table('chat_discussion_topics')
                    ->where('conversation_id', $selectedConversation->id)
                    ->where('slug', $currentTopicSlug);

                if (! $user->isModerator()) {
                    $topicQuery->where(function ($q) {
                        $q->where('visibility', 'public')
                            ->orWhereNull('visibility');
                    });
                }

                $currentTopic = $topicQuery->first();
            }

            $messagesQuery = Message::with([
                'user:id,name,profile_photo_path,role',
                'attachments',
                'reactions.user:id,name',
            ])
                ->where('conversation_id', $selectedConversation->id);

            // Filter by topic if one is selected
            if ($currentTopic) {
                $messagesQuery->whereIn('id', function ($query) use ($currentTopic) {
                    $query->select('message_id')
                        ->from('chat_discussion_topic_messages')
                        ->where('topic_id', $currentTopic->id);
                });
            } else {
                // Show only messages NOT in any topic (general channel)
                $messagesQuery->whereNotIn('id', function ($query) use ($selectedConversation) {
                    $query->select('message_id')
                        ->from('chat_discussion_topic_messages')
                        ->whereIn('topic_id', function ($subQuery) use ($selectedConversation) {
                            $subQuery->select('id')
                                ->from('chat_discussion_topics')
                                ->where('conversation_id', $selectedConversation->id);
                        });
                });
            }

            $messages = $messagesQuery
                ->latest('id')
                ->limit(30)
                ->get()
                ->reverse()
                ->values();

            // Get all pinned message IDs for this conversation
            $pinnedMessageIds = $selectedConversation->pins()->pluck('message_id')->toArray();

            // Get the latest pinned message for this conversation
            $latestPin = $selectedConversation->pins()->with('message.user:id,name')->latest()->first();
            if ($latestPin && $latestPin->message) {
                $pinnedMessage = [
                    'id' => $latestPin->message->id,
                    'body' => $latestPin->message->body,
                    'user_name' => $latestPin->message->user?->name ?? 'Unknown',
                    'pinned_at' => $latestPin->created_at,
                ];
            }

            $participantPayload = $selectedConversation->participants
                ->filter(function ($p) {
                    return is_null($p->left_at);
                })
                ->map(function ($participant) use ($statusFor, $locationFor, $avatarFor) {
                    $participantUser = $participant->user;

                    $isSystemModerator = $participantUser && in_array($participantUser->role, ['moderator', 'admin', 'super_admin']);
                    $participantRole = $participant->role ?? 'member';
                    $location = $locationFor($participantUser?->id);

                    return [
                        'id' => $participantUser?->id,
                        'user_id' => $participantUser?->id,
                        'name' => $participantUser?->name ?? 'Unknown',
                        'status' => $statusFor($participantUser?->id),
                        'avatar' => $avatarFor($participantUser, 'U'),
                        'role' => $participantRole,
                        'is_system_moderator' => $isSystemModerator,
                        'system_role' => $participantUser?->role ?? 'applicant',
                        'current_conversation_id' => $location['conversation_id'],
                        'current_topic_slug' => $location['topic_slug'],
                        'is_muted' => (bool) $participant->is_muted,
                        'muted_until' => $participant->muted_until?->toIso8601String(),
                    ];
                });

            if ($user->isModerator()) {
                ModerationBotService::createModeratorChannels($selectedConversation);
            }

            $topicsQuery = DB::table('chat_discussion_topics')
                ->where('conversation_id', $selectedConversation->id);

            if (! $user->isModerator()) {
                $topicsQuery->where(function ($q) {
                    $q->where('visibility', 'public')
                        ->orWhereNull('visibility');
                });
            }

            $topics = $topicsQuery
                ->orderBy('position')
                ->limit(30)
                ->get()
                ->map(function ($topic) {
                    return [
                        'id' => $topic->id,
                        'slug' => $topic->slug,
                        'name' => '#'.ltrim($topic->name, '#'),
                        'archived' => (bool) $topic->is_archived,
                        'is_readonly' => (bool) ($topic->is_readonly ?? false),
                        'is_starred' => (bool) ($topic->is_starred ?? false),
                        'visibility' => $topic->visibility ?? 'public',
                    ];
                });
        }

        // No default topics - let users create their own channels
        // Topics will be empty if none exist for the conversation

        // Get last read message ID for unread separator
        $lastReadMessageId = null;
        if ($selectedConversation) {
            $participant = $selectedConversation->participants->firstWhere('user_id', $user->id);
            $lastReadMessageId = $participant?->last_read_message_id;
        }

        $rightColumn = [
            'online' => $participantPayload->filter(function ($p) {
                return in_array($p['status'], ['online', 'idle', 'dnd', 'invisible']) && ! $p['is_muted'];
            })->values(),
            'offline' => $participantPayload->filter(function ($p) {
                return ! in_array($p['status'], ['online', 'idle', 'dnd', 'invisible']) && ! $p['is_muted'];
            })->values(),
            'muted' => $participantPayload->filter(function ($p) {
                return $p['is_muted'];
            })->values(),
        ];

        // For non-moderators, filter out users with 'invisible' status (except themselves)
        if (! $user->isModerator()) {
            $rightColumn['online'] = $rightColumn['online']->filter(function ($p) use ($user) {
                // Always show the current user
                if ($p['user_id'] === $user->id) {
                    return true;
                }

                // Hide invisible users from non-moderators
                return $p['status'] !== 'invisible';
            })->values();
        }

        $personalTags = PersonalTag::where('user_id', $user->id)
            ->orderBy('position')
            ->get()
            ->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'color' => $tag->color,
                    'icon' => $tag->icon,
                    'is_private' => $tag->is_private,
                ];
            });

        // Load user's todos when no conversation is selected
        $todos = collect();
        if (! $selectedConversation && ! $selectedPersonalTag && ! $filter) {
            $todos = ChatTodo::forUser($user->id)
                ->orderByRaw("FIELD(status, 'in_progress', 'pending', 'completed')")
                ->orderBy('due_date')
                ->limit(20)
                ->get();
        }

        // Handle filter views (unread, threads, mentions, drafts)
        $filterData = null;
        if ($filter) {
            // If a conversation is selected, filter only for that conversation
            $filterConversations = $selectedConversation
                ? collect([$selectedConversation])
                : $conversations;
            $filterData = $this->getFilterData($user, $filter, $filterConversations, $selectedConversation);
        }

        return view('modules.chats.v2.index', [
            'user' => $user,
            'servers' => $servers,
            'directMessages' => $directMessages,
            'frequentFriends' => $frequentFriends,
            'friendStats' => $friendStats,
            'selectedConversation' => $selectedConversation,
            'selectedPersonalTag' => $selectedPersonalTag,
            'messages' => $messages,
            'personalTagMessages' => $personalTagMessages,
            'pinnedMessage' => $pinnedMessage,
            'pinnedMessageIds' => $pinnedMessageIds ?? [],
            'lastReadMessageId' => $lastReadMessageId,
            'topics' => $topics,
            'rightColumn' => $rightColumn,
            'canDeleteGroup' => $canDeleteGroup,
            'deleteGroupUrl' => $deleteGroupUrl,
            'personalTags' => $personalTags,
            'todos' => $todos,
            'filter' => $filter,
            'filterData' => $filterData,
        ]);
    }

    /**
     * Get data for filter views (unread, threads, mentions, drafts)
     *
     * @param  $selectedConversation  - If provided, filter only for this conversation (group context)
     */
    private function getFilterData($user, $filter, $conversations, $selectedConversation = null)
    {
        $isGroupContext = $selectedConversation && $selectedConversation->type === 'group';
        $contextName = $isGroupContext ? $selectedConversation->name : 'all conversations';

        $data = [
            'type' => $filter,
            'items' => collect(),
            'title' => '',
            'icon' => '',
            'emptyMessage' => '',
            'context' => $isGroupContext ? 'group' : 'all',
            'contextName' => $contextName,
        ];

        switch ($filter) {
            case 'unread':
                $data['title'] = 'All Unread';
                $data['icon'] = 'bi-list-ul';
                $data['emptyMessage'] = 'You\'re all caught up! No unread messages.';

                // Get unread messages from all conversations
                $conversationIds = $conversations->pluck('id');
                $unreadMessages = Message::with(['user:id,name,profile_photo_path', 'conversation:id,name,type'])
                    ->whereIn('conversation_id', $conversationIds)
                    ->where('user_id', '!=', $user->id)
                    ->whereNotIn('id', function ($query) use ($user) {
                        $query->select('message_id')
                            ->from('chat_message_reads')
                            ->where('user_id', $user->id);
                    })
                    ->latest()
                    ->limit(50)
                    ->get()
                    ->map(function ($msg) {
                        return [
                            'id' => $msg->id,
                            'body' => $msg->body,
                            'user_name' => $msg->user?->name ?? 'Unknown',
                            'user_avatar' => $msg->user?->profile_photo_url ?? null,
                            'conversation_id' => $msg->conversation_id,
                            'conversation_name' => $msg->conversation?->name ?? 'Direct Message',
                            'conversation_type' => $msg->conversation?->type ?? 'dm',
                            'created_at' => $msg->created_at,
                        ];
                    });
                $data['items'] = $unreadMessages;
                break;

            case 'threads':
                $data['title'] = 'Threads';
                $data['icon'] = 'bi-chat-square-text';
                $data['emptyMessage'] = 'No threads yet. Reply to a message to start a thread.';

                // Get messages that have replies (threads)
                $conversationIds = $conversations->pluck('id');
                $threads = Message::with(['user:id,name,profile_photo_path', 'conversation:id,name,type'])
                    ->whereIn('conversation_id', $conversationIds)
                    ->whereNotNull('reply_to_message_id')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->limit(30)
                    ->get()
                    ->map(function ($msg) {
                        return [
                            'id' => $msg->id,
                            'body' => $msg->body,
                            'user_name' => $msg->user?->name ?? 'Unknown',
                            'user_avatar' => $msg->user?->profile_photo_url ?? null,
                            'conversation_id' => $msg->conversation_id,
                            'conversation_name' => $msg->conversation?->name ?? 'Direct Message',
                            'reply_to_id' => $msg->reply_to_message_id,
                            'created_at' => $msg->created_at,
                        ];
                    });
                $data['items'] = $threads;
                break;

            case 'mentions':
                $data['title'] = 'Mentions & Reactions';
                $data['icon'] = 'bi-at';
                $data['emptyMessage'] = 'No mentions or reactions yet.';

                // Get messages where user is mentioned or reacted to
                $conversationIds = $conversations->pluck('id');

                // Messages mentioning the user (search for @username pattern)
                $mentions = Message::with(['user:id,name,profile_photo_path', 'conversation:id,name,type'])
                    ->whereIn('conversation_id', $conversationIds)
                    ->where('user_id', '!=', $user->id)
                    ->where(function ($q) use ($user) {
                        $q->where('body', 'like', '%@'.$user->name.'%')
                            ->orWhere('body', 'like', '%@everyone%');
                    })
                    ->latest()
                    ->limit(30)
                    ->get()
                    ->map(function ($msg) {
                        return [
                            'id' => $msg->id,
                            'type' => 'mention',
                            'body' => $msg->body,
                            'user_name' => $msg->user?->name ?? 'Unknown',
                            'user_avatar' => $msg->user?->profile_photo_url ?? null,
                            'conversation_id' => $msg->conversation_id,
                            'conversation_name' => $msg->conversation?->name ?? 'Direct Message',
                            'created_at' => $msg->created_at,
                        ];
                    });

                // Get reactions to user's messages
                $reactions = DB::table('chat_message_reactions')
                    ->join('chat_messages', 'chat_message_reactions.message_id', '=', 'chat_messages.id')
                    ->join('users', 'chat_message_reactions.user_id', '=', 'users.id')
                    ->join('chat_conversation', 'chat_messages.conversation_id', '=', 'chat_conversation.id')
                    ->whereIn('chat_messages.conversation_id', $conversationIds)
                    ->where('chat_messages.user_id', $user->id)
                    ->where('chat_message_reactions.user_id', '!=', $user->id)
                    ->select([
                        'chat_message_reactions.id',
                        'chat_message_reactions.reaction as emoji',
                        'chat_messages.id as message_id',
                        'chat_messages.body',
                        'chat_messages.conversation_id',
                        'users.name as reactor_name',
                        'chat_conversation.name as conversation_name',
                        'chat_message_reactions.created_at',
                    ])
                    ->latest('chat_message_reactions.created_at')
                    ->limit(20)
                    ->get()
                    ->map(function ($r) {
                        return [
                            'id' => $r->id,
                            'type' => 'reaction',
                            'emoji' => $r->emoji,
                            'body' => $r->body,
                            'user_name' => $r->reactor_name,
                            'conversation_id' => $r->conversation_id,
                            'conversation_name' => $r->conversation_name ?? 'Direct Message',
                            'created_at' => $r->created_at,
                        ];
                    });

                $data['items'] = $mentions->merge($reactions)->sortByDesc('created_at')->values();
                break;

            case 'drafts':
                $data['title'] = 'Drafts';
                $data['icon'] = 'bi-pencil-square';
                $data['emptyMessage'] = 'No drafts saved. Your unsent messages will appear here.';

                // Get user's draft messages
                $drafts = DB::table('chat_drafts')
                    ->leftJoin('chat_conversation', 'chat_drafts.conversation_id', '=', 'chat_conversation.id')
                    ->where('chat_drafts.user_id', $user->id)
                    ->select([
                        'chat_drafts.id',
                        'chat_drafts.body',
                        'chat_drafts.conversation_id',
                        'chat_conversation.name as conversation_name',
                        'chat_conversation.type as conversation_type',
                        'chat_drafts.created_at',
                        'chat_drafts.updated_at',
                    ])
                    ->latest('chat_drafts.updated_at')
                    ->limit(30)
                    ->get()
                    ->map(function ($d) {
                        return [
                            'id' => $d->id,
                            'body' => $d->body,
                            'conversation_id' => $d->conversation_id,
                            'conversation_name' => $d->conversation_name ?? 'Direct Message',
                            'conversation_type' => $d->conversation_type ?? 'dm',
                            'created_at' => $d->created_at,
                            'updated_at' => $d->updated_at,
                        ];
                    });
                $data['items'] = $drafts;
                break;
        }

        return $data;
    }

    public function monitor(Request $request)
    {
        $currentUserId = (int) $request->user()->id;

        $conversations = Conversation::with([
            'participants.user:id,name,profile_photo_path',
            'messages' => function ($q) {
                $q->latest()->limit(1);
            },
            'topics',
        ])
            ->withCount('messages')
            ->orderByDesc('updated_at')
            ->paginate(20);

        $conversations->getCollection()->transform(function ($conv) use ($currentUserId) {
            $isGroup = ($conv->type === 'group');

            if ($isGroup) {
                $displayTitle = $conv->name ?: 'Group Chat #'.$conv->id;
            } else {
                $names = $conv->participants
                    ->pluck('user.name')
                    ->filter()
                    ->unique()
                    ->values();

                if ($conv->name) {
                    $displayTitle = $conv->name;
                } elseif ($names->count() === 0) {
                    $displayTitle = 'Direct Message #'.$conv->id;
                } elseif ($names->count() === 1) {
                    $displayTitle = $names->first();
                } elseif ($names->count() === 2) {
                    $displayTitle = $names->join(' ↔ ');
                } else {
                    $displayTitle = $names->take(3)->join(' • ');
                    if ($names->count() > 3) {
                        $displayTitle .= ' +'.($names->count() - 3);
                    }
                }
            }

            $conv->display_title = $displayTitle;
            $conv->is_group = $isGroup;
            $conv->participants_count = $conv->participants->count();

            $latest = $conv->messages->first();
            $conv->last_message_body = $latest?->body;
            $conv->last_message_at = $latest?->created_at;

            $pivot = $conv->participants->firstWhere('user_id', $currentUserId);
            $conv->total_unread = (int) ($pivot->unread_count ?? 0);

            $allMessages = Message::where('conversation_id', $conv->id)
                ->with('user:id,name,profile_photo_path')
                ->latest()
                ->take(50)
                ->get();

            $topicMessageIds = DB::table('chat_discussion_topic_messages')
                ->whereIn('message_id', $allMessages->pluck('id'))
                ->get()
                ->groupBy('topic_id');

            $messagesByTopic = [];
            $generalMessages = collect();

            foreach ($allMessages as $message) {
                $foundInTopic = false;
                foreach ($topicMessageIds as $topicId => $topicMsgs) {
                    if ($topicMsgs->pluck('message_id')->contains($message->id)) {
                        if (! isset($messagesByTopic[$topicId])) {
                            $messagesByTopic[$topicId] = collect();
                        }
                        $messagesByTopic[$topicId]->push($message);
                        $foundInTopic = true;
                        break;
                    }
                }
                if (! $foundInTopic) {
                    $generalMessages->push($message);
                }
            }

            $conv->general_messages = $generalMessages;
            $conv->messages_by_topic = $messagesByTopic;
            $conv->topics_map = $conv->topics->keyBy('id');

            return $conv;
        });

        $pendingReportsCount = MessageReport::where('status', 'pending')->count();

        $totalConversations = Conversation::count();
        $totalGroups = Conversation::where('type', 'group')->count();
        $totalDMs = Conversation::where('type', 'dm')->count();
        $totalMessagesCount = Message::count();
        $totalUnreadCount = ConversationParticipant::where('user_id', $currentUserId)
            ->where('unread_count', '>', 0)
            ->sum('unread_count');

        return view('modules.chats.monitor', [
            'conversations' => $conversations,
            'pendingReportsCount' => $pendingReportsCount,
            'stats' => [
                'total' => (int) $totalConversations,
                'groups' => (int) $totalGroups,
                'dms' => (int) $totalDMs,
                'messages' => (int) $totalMessagesCount,
                'unread' => (int) $totalUnreadCount,
            ],
        ]);
    }

    public function exportCsv(Request $request)
    {
        $conversations = Conversation::with([
            'participants.user:id,name',
            'messages' => function ($q) {
                $q->latest()->limit(1);
            },
        ])
            ->withCount('messages')
            ->orderByDesc('updated_at')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="conversations_export_'.date('Y-m-d_His').'.csv"',
        ];

        $callback = function () use ($conversations) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'ID',
                'Type',
                'Title',
                'Participants',
                'Participant Names',
                'Messages Count',
                'Unread Count',
                'Last Message',
                'Last Activity',
                'Created At',
            ]);

            foreach ($conversations as $conv) {
                $isGroup = ($conv->type === 'group');
                $names = $conv->participants->pluck('user.name')->filter()->unique()->values();

                if ($isGroup) {
                    $displayTitle = $conv->name ?: 'Group Chat #'.$conv->id;
                } elseif ($names->count() >= 2) {
                    $displayTitle = $names->take(2)->join(' ↔ ');
                } else {
                    $displayTitle = $names->first() ?? 'Direct Message #'.$conv->id;
                }

                $latest = $conv->messages->first();
                $totalUnread = $conv->participants->sum('unread_count');

                fputcsv($file, [
                    $conv->id,
                    $isGroup ? 'GROUP' : 'DM',
                    $displayTitle,
                    $conv->participants->count(),
                    $names->join(', '),
                    $conv->messages_count ?? 0,
                    $totalUnread,
                    $latest?->body ?? 'No messages',
                    $latest?->created_at ?? $conv->updated_at,
                    $conv->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show($id)
    {
        $currentUserId = (int) Auth::id();

        $conversation = Conversation::with([
            'participants.user:id,name,email,profile_photo_path',
            'messages.user:id,name,email,profile_photo_path',
        ])->findOrFail($id);

        // Persist read state when opening from monitor so unread badge does not return on refresh.
        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $currentUserId)
            ->update([
                'last_read_at' => now(),
                'unread_count' => 0,
            ]);

        $conversation = $conversation->fresh([
            'participants.user:id,name,email,profile_photo_path',
            'messages.user:id,name,email,profile_photo_path',
        ]);

        $this->broadcastConversationUpdated($conversation);

        $isGroup = $conversation->type === 'group';

        // Build display_title similar to monitor
        if ($isGroup) {
            $displayTitle = $conversation->name ?: 'Group Chat #'.$conversation->id;
        } else {
            $names = $conversation->participants
                ->pluck('user.name')
                ->filter()
                ->unique()
                ->values();

            if ($conversation->name) {
                $displayTitle = $conversation->name;
            } elseif ($names->count() === 0) {
                $displayTitle = 'Direct Message #'.$conversation->id;
            } elseif ($names->count() === 1) {
                $displayTitle = $names->first();
            } elseif ($names->count() === 2) {
                $displayTitle = $names->join(' ↔ ');
            } else {
                $displayTitle = $names->take(3)->join(' • ');
                if ($names->count() > 3) {
                    $displayTitle .= ' +'.($names->count() - 3);
                }
            }
        }

        $conversation->display_title = $displayTitle;
        $conversation->is_group = $isGroup;
        $conversation->is_locked = ($conversation->settings ?? [])['locked'] ?? false;

        return view('modules.chats.show', compact('conversation'));
    }

    /**
     * Open or create a DM between auth user and peer.
     */
    public function open(Request $request)
    {
        $request->validate(['user_id' => ['required', 'integer', 'exists:users,id']]);

        $me = (int) $request->user()->id;
        $peer = (int) $request->input('user_id');

        if ($peer === $me) {
            return response()->json(['message' => 'Cannot DM yourself.'], 422);
        }

        $existing = Conversation::query()
            ->where('type', 'dm')
            ->whereHas('participants', function ($q) use ($me, $peer) {
                $q->whereIn('user_id', [$me, $peer])->whereNull('left_at');
            })
            ->with([
                'participants' => function ($q) {
                    $q->whereNull('left_at')->with('user:id,name,profile_photo_path');
                },
                'messages' => function ($q) {
                    $q->latest()->limit(1);
                },
            ])
            ->get()
            ->first(function ($c) use ($me, $peer) {
                if ($c->participants->count() !== 2) {
                    return false;
                }
                $ids = $c->participants->pluck('user_id')->map(function ($v) {
                    return (int) $v;
                })->sort()->values()->all();

                return $ids === [min($me, $peer), max($me, $peer)];
            });

        if ($existing) {
            return response()->json(['conversation' => $existing]);
        }

        $conv = DB::transaction(function () use ($me, $peer) {
            $c = Conversation::create([
                'type' => 'dm',
                'name' => null,
                'created_by' => $me,
            ]);

            ConversationParticipant::insert([
                [
                    'conversation_id' => $c->id,
                    'user_id' => $me,
                    'role' => 'member',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'conversation_id' => $c->id,
                    'user_id' => $peer,
                    'role' => 'member',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            return $c;
        });

        $conv->load([
            'participants' => function ($q) {
                $q->with('user:id,name,profile_photo_path')->whereNull('left_at');
            },
            'messages' => function ($q) {
                $q->latest()->limit(1);
            },
        ]);

        return response()->json(['conversation' => $conv], 201);
    }

    /**
     * Legacy DM creation (still available if used somewhere else).
     */
    public function storeDm(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id', 'different:auth'],
        ]);
        $authId = $request->user()->id;
        $peerId = (int) $data['user_id'];

        return DB::transaction(function () use ($authId, $peerId) {
            $conv = Conversation::where('type', 'dm')
                ->whereHas('participants', function ($q) use ($authId) {
                    $q->where('user_id', $authId);
                })
                ->whereHas('participants', function ($q) use ($peerId) {
                    $q->where('user_id', $peerId);
                })
                ->first();

            if (! $conv) {
                $conv = Conversation::create([
                    'type' => 'dm',
                    'created_by' => $authId,
                ]);

                foreach ([$authId, $peerId] as $uid) {
                    ConversationParticipant::create([
                        'conversation_id' => $conv->id,
                        'user_id' => $uid,
                        'role' => $uid === $authId ? 'owner' : 'member',
                        'joined_at' => now(),
                    ]);
                }
            }

            return response()->json($conv->load('participants.user'));
        });
    }

    /**
     * Legacy group creation (still available if used).
     */
    public function storeGroup(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'member_ids' => ['array', 'min:1'],
            'member_ids.*' => ['integer', 'exists:users,id'],
            'photo' => ['nullable', 'string'],
        ]);
        $authId = $request->user()->id;

        return DB::transaction(function () use ($authId, $data) {
            $conv = Conversation::create([
                'type' => 'group',
                'name' => $data['name'],
                'photo' => $data['photo'] ?? null,
                'created_by' => $authId,
            ]);

            ConversationParticipant::create([
                'conversation_id' => $conv->id,
                'user_id' => $authId,
                'role' => 'owner',
                'joined_at' => now(),
            ]);

            foreach (($data['member_ids'] ?? []) as $uid) {
                if ($uid === $authId) {
                    continue;
                }
                ConversationParticipant::create([
                    'conversation_id' => $conv->id,
                    'user_id' => $uid,
                    'role' => 'member',
                    'joined_at' => now(),
                ]);
            }

            return response()->json($conv->load('participants.user'));
        });
    }

    public function listMembers(Request $request, Conversation $conversation)
    {
        $participants = ConversationParticipant::with(['user:id,name,profile_photo_path,role'])
            ->where('conversation_id', $conversation->id)
            ->whereNull('left_at')
            ->get()
            ->map(function ($p) use ($conversation) {
                $u = $p->user;
                $isSystemModerator = $u && in_array($u->role, ['moderator', 'admin', 'super_admin']);
                $isOwner = $u && (int) $u->id === (int) $conversation->created_by;

                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'avatar' => $u->profile_photo_path
                        ? asset('storage/'.$u->profile_photo_path)
                        : url('/user.png'),
                    'role' => $p->role ?? 'member',
                    'system_role' => $u->role ?? 'applicant',
                    'is_owner' => $isOwner,
                    'is_system_moderator' => $isSystemModerator,
                    'joined_at' => optional($p->joined_at)->toIso8601String(),
                ];
            });

        $conversation->loadCount([
            'messages',
            'attachments as files_count',
            'imageAttachments as images_count',
            'fileAttachments as docs_count',
        ]);

        return response()->json([
            'participants' => $participants,
            'conversation' => [
                'id' => $conversation->id,
                'name' => $conversation->name,
                'photo' => $conversation->photo ? asset('storage/'.$conversation->photo) : url('/user.png'),
                'messages_count' => $conversation->messages_count,
                'files_count' => $conversation->files_count,
                'last_activity' => optional($conversation->updated_at)->diffForHumans(),
            ],
        ]);
    }

    public function add(Request $request, Conversation $conversation)
    {
        if ($request->filled('member_ids')) {
            $data = $request->validate([
                'member_ids' => ['required', 'array', 'min:1'],
                'member_ids.*' => ['integer', 'exists:users,id'],
            ]);

            $userIds = $data['member_ids'];
        } else {
            $data = $request->validate([
                'email' => ['required', 'email', 'exists:users,email'],
            ]);

            $user = User::where('email', $data['email'])->firstOrFail();
            $userIds = [$user->id];
        }

        foreach ($userIds as $uid) {
            ConversationParticipant::updateOrCreate(
                ['conversation_id' => $conversation->id, 'user_id' => $uid],
                ['joined_at' => now(), 'left_at' => null]
            );
        }

        return response()->json(['ok' => true]);
    }

    public function remove(Request $request, Conversation $conversation, User $user)
    {
        $pivot = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $pivot) {
            return response()->json(['ok' => true, 'message' => 'Already removed'], 200);
        }

        $pivot->left_at = now();
        $pivot->save();

        return response()->json(['ok' => true]);
    }

    public function attachments(Conversation $conversation)
    {
        $list = $conversation->attachments()
            ->select('chat_attachments.*')
            ->latest('chat_attachments.id')
            ->limit(50)
            ->get()
            ->map(function ($a) {
                $url = $a->disk
                    ? \Storage::disk($a->disk)->url($a->path)
                    : asset($a->path);

                $thumb = (is_string($a->mime) && str_starts_with($a->mime, 'image/')) ? $url : null;

                return [
                    'id' => $a->id,
                    'url' => $url,
                    'thumb' => $thumb,
                    'name' => basename($a->path),
                    'mime' => $a->mime,
                    'size' => (int) ($a->size ?? 0),
                    'created_at' => optional($a->created_at)->toIso8601String(),
                ];
            });

        return response()->json($list);
    }

    public function rename(Request $request, Conversation $conversation)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $conversation->name = $data['name'];
        $conversation->save();

        return response()->json([
            'ok' => true,
            'conversation' => [
                'id' => $conversation->id,
                'name' => $conversation->name,
                'photo' => $conversation->photo ? asset('storage/'.$conversation->photo) : url('/user.png'),
            ],
        ]);
    }

    public function search(Request $request)
    {
        $q = $request->query('q', '');

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $users = User::query()
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email', 'profile_photo_path']);

        $results = $users->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'avatar' => $u->profile_photo_path
                    ? asset('storage/'.$u->profile_photo_path)
                    : url('/user.png'),
            ];
        });

        return response()->json($results);
    }

    public function addMembers(Request $request, Conversation $conversation)
    {
        $data = $request->validate([
            'member_ids' => ['required', 'array', 'min:1'],
            'member_ids.*' => ['integer', 'exists:users,id'],
        ]);

        foreach ($data['member_ids'] as $uid) {
            ConversationParticipant::updateOrCreate(
                ['conversation_id' => $conversation->id, 'user_id' => $uid],
                ['joined_at' => now(), 'left_at' => null]
            );

            broadcast(new UserAddedToGroup($uid, $conversation));
        }

        return response()->json(['ok' => true]);
    }

    public function leave(Request $request, Conversation $conversation)
    {
        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $request->user()->id)
            ->update(['left_at' => now()]);

        return response()->json(['ok' => true]);
    }

    // Per-user flags
    public function pin(Request $request, Conversation $conversation)
    {
        return $this->setFlag($request, $conversation, 'is_pinned', true);
    }

    public function unpin(Request $request, Conversation $conversation)
    {
        return $this->setFlag($request, $conversation, 'is_pinned', false);
    }

    public function archive(Request $request, Conversation $conversation)
    {
        return $this->setFlag($request, $conversation, 'is_archived', true);
    }

    public function unarchive(Request $request, Conversation $conversation)
    {
        return $this->setFlag($request, $conversation, 'is_archived', false);
    }

    public function trash(Request $request, Conversation $conversation)
    {
        return $this->setFlag($request, $conversation, 'is_trashed', true);
    }

    public function restore(Request $request, Conversation $conversation)
    {
        return $this->setFlag($request, $conversation, 'is_trashed', false);
    }

    protected function setFlag(Request $request, Conversation $conversation, string $flag, bool $value)
    {
        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $request->user()->id)
            ->update([$flag => $value]);

        return response()->json(['ok' => true, $flag => $value]);
    }

    public function markRead(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        $lastMessage = $conversation->messages()->latest('id')->first();
        if ($lastMessage) {
            ConversationParticipant::where('conversation_id', $conversation->id)
                ->where('user_id', $user->id)
                ->update([
                    'last_read_message_id' => $lastMessage->id,
                    'last_read_at' => now(),
                ]);
        }

        return response()->json(['ok' => true]);
    }

    public function info(Request $request, Conversation $conversation)
    {
        return response()->json(['conv' => $conversation]);
    }

    public function joined(Request $request, Conversation $conversation)
    {
        $conversation->increment('joined');

        return response()->json(['conv' => $conversation]);
    }

    public function left(Request $request, Conversation $conversation)
    {
        if ($conversation->joined > 0) {
            $conversation->decrement('joined');
            Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => Auth::user()->id,
                'type' => 'system',
                'body' => Auth::user()->name.' Left the Meeting.',
            ]);
        }

        return response()->json(['conv' => $conversation]);
    }

    public function end(Request $request, Conversation $conversation)
    {
        $conversation->meet = null;
        $conversation->joined = 0;
        $conversation->save();

        return response()->json(['convx' => $conversation]);
    }

    /*
     * NEW: Used by "VERSION 2 CHAT" modal + info modal
     */

    public function store(Request $request)
    {
        $user = $request->user();
        $authId = $user->id;

        // Candidates cannot create groups or DMs - they must wait to be assigned
        if ($user->isApplicant()) {
            return response()->json([
                'message' => 'Candidates cannot create conversations. Please wait to be assigned to a group by a moderator.',
            ], 403);
        }

        $data = $request->validate([
            'type' => ['required', 'in:dm,group'],

            'user_id' => [
                'required_if:type,dm',
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'name' => [
                'required_if:type,group',
                'nullable',
                'string',
                'max:255',
            ],

            'member_ids' => ['nullable', 'array'],
            'member_ids.*' => ['integer', 'exists:users,id'],
            'member_emails' => ['nullable', 'string'],
            'channels' => ['nullable', 'string'],
            'prebuilt_channels' => ['nullable', 'string'],
        ]);

        if ($data['type'] === 'dm') {
            $otherId = (int) $data['user_id'];
            $response = $this->createDirectMessage($authId, $otherId);

            return $this->formatConversationResponse($request, $response, __('Direct message created.'));
        }

        $memberIds = $data['member_ids'] ?? [];

        // Handle member emails
        if (! empty($data['member_emails'])) {
            $emails = array_filter(array_map('trim', explode(',', $data['member_emails'])));
            $userIdsByEmail = \App\Models\User::whereIn('email', $emails)->pluck('id')->toArray();
            $memberIds = array_unique(array_merge($memberIds, $userIdsByEmail));
        }

        // Parse channels from JSON
        $channels = [];
        if (! empty($data['channels'])) {
            $channels = json_decode($data['channels'], true) ?? [];
        }

        // Parse prebuilt channels
        $prebuiltChannels = [];
        if (! empty($data['prebuilt_channels'])) {
            $prebuiltChannels = json_decode($data['prebuilt_channels'], true) ?? [];
        }

        $response = $this->createGroupChat($authId, $data['name'], $memberIds, $channels, $prebuiltChannels);

        return $this->formatConversationResponse($request, $response, __('Group created.'));
    }

    protected function createDirectMessage(int $authId, int $otherId)
    {
        if ($otherId === $authId) {
            return response()->json([
                'message' => 'You cannot create a DM with yourself.',
            ], 422);
        }

        $existing = Conversation::query()
            ->where('type', 'dm')
            ->whereHas('participants', function ($q) use ($authId) {
                $q->where('user_id', $authId)->whereNull('left_at');
            })
            ->whereHas('participants', function ($q) use ($otherId) {
                $q->where('user_id', $otherId)->whereNull('left_at');
            })
            ->with([
                'participants.user:id,name,profile_photo_path',
                'messages' => function ($q) {
                    $q->latest()->limit(1);
                },
            ])
            ->first();

        if ($existing) {
            return response()->json($this->makeSidebarPayload($existing));
        }

        $conversation = DB::transaction(function () use ($authId, $otherId) {
            $conv = Conversation::create([
                'type' => 'dm',
                'name' => null,
                'created_by' => $authId,
            ]);

            $now = now();
            ConversationParticipant::insert([
                [
                    'conversation_id' => $conv->id,
                    'user_id' => $authId,
                    'role' => 'member',
                    'joined_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'conversation_id' => $conv->id,
                    'user_id' => $otherId,
                    'role' => 'member',
                    'joined_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            return $conv;
        });

        $conversation->load([
            'participants.user:id,name,profile_photo_path',
            'messages' => function ($q) {
                $q->latest()->limit(1);
            },
        ]);

        $this->broadcastConversationUpdated($conversation);

        return response()->json($this->makeSidebarPayload($conversation));
    }

    protected function formatConversationResponse(Request $request, JsonResponse $response, string $successMessage)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return $response;
        }

        $payload = $response->getData(true);
        $status = $response->getStatusCode();

        if ($status >= 400) {
            $message = data_get($payload, 'message', 'Unable to process your request right now.');

            return redirect()->back()->with('chat_error', $message);
        }

        $conversationId = $payload['conversation_id'] ?? $payload['id'] ?? null;
        $routeParams = $conversationId ? ['conversation' => $conversationId] : [];

        return redirect()
            ->route('chats.v2', $routeParams)
            ->with('chat_success', $successMessage);
    }

    protected function createGroupChat(int $authId, string $name, array $memberIds, array $channels = [], array $prebuiltChannels = [])
    {
        $memberIds = collect($memberIds)
            ->map(function ($id) {
                return (int) $id;
            })
            ->filter(function ($id) use ($authId) {
                return $id !== $authId;
            })
            ->unique()
            ->prepend($authId)
            ->values()
            ->all();

        $conversation = DB::transaction(function () use ($authId, $name, $memberIds, $channels, $prebuiltChannels) {
            $conv = Conversation::create([
                'type' => 'group',
                'name' => $name,
                'created_by' => $authId,
            ]);

            $now = now();
            $rows = [];
            foreach ($memberIds as $uid) {
                $rows[] = [
                    'conversation_id' => $conv->id,
                    'user_id' => $uid,
                    'role' => $uid === $authId ? 'owner' : 'member',
                    'joined_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            ConversationParticipant::insert($rows);

            // Auto-create #rules channel for bot configuration
            ModerationBotService::createRulesChannel($conv);

            $position = 1;

            // Create prebuilt channels (starred, moderator-only)
            $prebuiltConfig = [
                'announcements' => ['icon' => 'megaphone', 'description' => 'Important updates and announcements'],
                'welcome' => ['icon' => 'hand-wave', 'description' => 'New member greetings'],
                'guidelines' => ['icon' => 'book', 'description' => 'Rules and expectations'],
                'resources' => ['icon' => 'folder', 'description' => 'Helpful links and files'],
            ];

            foreach ($prebuiltChannels as $channelKey) {
                if (! isset($prebuiltConfig[$channelKey])) {
                    continue;
                }

                $config = $prebuiltConfig[$channelKey];
                DB::table('chat_discussion_topics')->insert([
                    'conversation_id' => $conv->id,
                    'name' => $channelKey,
                    'slug' => $channelKey,
                    'description' => $config['description'],
                    'position' => $position++,
                    'is_readonly' => true,
                    'is_starred' => true,
                    'created_by' => $authId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            // Create custom channels/topics
            if (! empty($channels)) {
                foreach ($channels as $channelName) {
                    $channelName = trim($channelName);
                    if (empty($channelName)) {
                        continue;
                    }

                    $slug = Str::slug($channelName);
                    if (empty($slug)) {
                        continue;
                    }

                    DB::table('chat_discussion_topics')->insert([
                        'conversation_id' => $conv->id,
                        'name' => $channelName,
                        'slug' => $slug,
                        'position' => $position++,
                        'created_by' => $authId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            return $conv;
        });

        $conversation->load([
            'participants.user:id,name,profile_photo_path',
            'messages' => function ($q) {
                $q->latest()->limit(1);
            },
        ]);

        $this->broadcastConversationUpdated($conversation);

        return response()->json($this->makeSidebarPayload($conversation));
    }

    protected function makeSidebarPayload(Conversation $conversation): array
    {
        $conversation->loadMissing([
            'participants.user:id,name,profile_photo_path',
            'messages' => function ($q) {
                $q->latest()->limit(1);
            },
        ]);

        $isGroup = $conversation->type === 'group';

        if ($isGroup) {
            $displayTitle = $conversation->name ?? 'Group Chat';
        } else {
            $authId = Auth::id();
            $other = $conversation->participants
                ->firstWhere('user_id', '!=', $authId);

            $displayTitle = optional(optional($other)->user)->name ?? 'Direct Message';
        }

        $latest = $conversation->messages->first();
        $lastAt = $latest?->created_at ?? $conversation->updated_at;

        return [
            'id' => $conversation->id,
            'conversation_id' => $conversation->id,
            'is_group' => $isGroup,
            'title' => $displayTitle,
            'last_message' => $latest?->body_html ?? $latest?->body ?? '',
            'last_at' => $lastAt ? $lastAt->toIso8601String() : null,
            'updated_at' => $conversation->updated_at
                                ? $conversation->updated_at->toIso8601String()
                                : null,
            'unread_count' => 0,
            'avatar_url' => null,
        ];
    }

    protected function broadcastConversationUpdated(Conversation $conversation): void
    {
        $conversation->loadMissing([
            'participants.user',
            'messages' => function ($q) {
                $q->latest()->limit(1);
            },
        ]);

        foreach ($conversation->participants as $participant) {
            $userId = $participant->user_id;
            $unreadCount = $participant->unread_count ?? 0;

            ConversationUpdated::dispatch(
                $conversation,
                $userId,
                $unreadCount
            );
        }
    }

    /**
     * Rename group chat (info modal).
     */
    public function update(Request $request, Conversation $conversation)
    {
        $this->authorizeManage($conversation);

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $conversation->name = $data['name'];
        $conversation->save();

        $this->broadcastConversationUpdated($conversation);

        return response()->json(['ok' => true]);
    }

    /**
     * Delete group chat (info modal "Delete").
     */
    public function destroy(Conversation $conversation)
    {
        $this->authorizeManage($conversation);

        DB::transaction(function () use ($conversation) {
            if (method_exists($conversation, 'messages')) {
                $conversation->messages()->delete();
            }

            ConversationParticipant::where('conversation_id', $conversation->id)->delete();
            $conversation->delete();
        });

        return response()->json(['ok' => true]);
    }

    /**
     * Add member by email (info modal).
     */
    public function addMember(Request $request, Conversation $conversation)
    {
        $this->authorizeManage($conversation);

        $data = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return response()->json([
                'message' => 'User with that email was not found.',
            ], 404);
        }

        ConversationParticipant::updateOrCreate(
            ['conversation_id' => $conversation->id, 'user_id' => $user->id],
            ['joined_at' => now(), 'left_at' => null]
        );

        $this->broadcastConversationUpdated($conversation);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Remove member (info modal).
     * System moderators can remove anyone including group owners (super admin power).
     */
    public function removeMember(Conversation $conversation, User $user)
    {
        $this->authorizeManage($conversation);

        $currentUser = Auth::user();
        $isSystemModerator = $currentUser->isModerator();
        $isTargetOwner = (int) $user->id === (int) $conversation->created_by;
        $isTargetModerator = $user->isModerator();

        // System moderators can remove anyone EXCEPT other system moderators
        if ($isSystemModerator) {
            if ($isTargetModerator && (int) $user->id !== (int) $currentUser->id) {
                return response()->json([
                    'message' => 'You cannot remove another system moderator.',
                ], 422);
            }
            // Moderators can remove group owners and admins
        } else {
            // Regular admins/owners cannot remove the group owner
            if ($isTargetOwner) {
                return response()->json([
                    'message' => 'You cannot remove the group owner.',
                ], 422);
            }

            // Regular admins cannot remove other admins (only owner can)
            $targetParticipant = ConversationParticipant::where('conversation_id', $conversation->id)
                ->where('user_id', $user->id)
                ->whereNull('left_at')
                ->first();

            $currentParticipant = ConversationParticipant::where('conversation_id', $conversation->id)
                ->where('user_id', $currentUser->id)
                ->whereNull('left_at')
                ->first();

            if ($targetParticipant && $targetParticipant->role === 'admin'
                && $currentParticipant && $currentParticipant->role !== 'owner') {
                return response()->json([
                    'message' => 'Only the group owner can remove admins.',
                ], 422);
            }
        }

        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->delete();

        $this->broadcastConversationUpdated($conversation);

        return response()->json(['ok' => true]);
    }

    /**
     * Mark conversation as read for current user (used by JS /chats/{id}/read).
     */
    public function markAsRead(Conversation $conversation)
    {
        $userId = Auth::id();

        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $userId)
            ->update([
                'last_read_at' => now(),
                'unread_count' => 0,
            ]);

        ConversationUpdated::dispatch($conversation->fresh(), $userId, 0);

        return response()->json(['ok' => true]);
    }

    /**
     * Get pinned messages for a conversation.
     */
    public function pinnedMessages(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        // Verify user is participant or moderator
        $isParticipant = $conversation->participants()->where('user_id', $user->id)->whereNull('left_at')->exists();
        abort_unless($isParticipant || $user->isModerator(), 403);

        $pinnedMessages = $conversation->pins()
            ->with(['message.user:id,name,profile_photo_path,role'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($pin) {
                $message = $pin->message;
                if (! $message) {
                    return null;
                }

                return [
                    'id' => $message->id,
                    'body' => $message->body,
                    'user' => [
                        'id' => $message->user?->id,
                        'name' => $message->user?->name ?? 'Unknown',
                        'avatar' => $message->user?->profile_photo_path
                            ? asset('storage/'.$message->user->profile_photo_path)
                            : 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($message->user?->name ?? 'U').'&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981',
                    ],
                    'pinned_at' => $pin->created_at?->toIso8601String(),
                    'created_at' => $message->created_at?->toIso8601String(),
                ];
            })
            ->filter()
            ->values();

        return response()->json(['data' => $pinnedMessages]);
    }

    public function getTopics(Request $request, Conversation $conversation)
    {
        $topics = DB::table('chat_discussion_topics')
            ->where('conversation_id', $conversation->id)
            ->where('is_archived', false)
            ->orderBy('position')
            ->get(['id', 'name', 'slug', 'description', 'is_readonly', 'position']);

        return response()->json(['topics' => $topics]);
    }

    public function membersList(Request $request, Conversation $conversation)
    {
        $members = $conversation->participants()
            ->whereNull('left_at')
            ->with('user:id,name,profile_photo_path')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->user->id,
                    'name' => $p->user->name,
                    'avatar' => $p->user->profile_photo_path
                        ? asset('storage/'.$p->user->profile_photo_path)
                        : null,
                ];
            });

        return response()->json(['members' => $members]);
    }

    public function getForwardDestinations(Request $request)
    {
        $user = $request->user();

        $myConversations = Conversation::query()
            ->with(['participants.user:id,name,profile_photo_path'])
            ->whereHas('participants', function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereNull('left_at');
            })
            ->orderByDesc('updated_at')
            ->get();

        // Get all public groups user can forward to
        $publicGroups = Conversation::query()
            ->with(['participants.user:id,name,profile_photo_path'])
            ->where('type', 'group')
            ->where('is_public', true)
            ->whereNotIn('id', $myConversations->pluck('id'))
            ->orderBy('name')
            ->get();

        $formatConversation = function ($conv) use ($user) {
            $name = $conv->name;
            if (! $name && $conv->type === 'dm') {
                $other = $conv->participants->firstWhere('user_id', '!=', $user->id);
                $name = $other?->user?->name ?? 'Direct Message';
            }
            $name = $name ?: 'Conversation #'.$conv->id;

            return [
                'id' => $conv->id,
                'name' => $name,
                'type' => $conv->type,
                'is_public' => $conv->is_public ?? false,
                'photo' => $conv->photo ? asset('storage/'.ltrim($conv->photo, '/')) : null,
            ];
        };

        $conversations = $myConversations->map($formatConversation)->values();
        $available = $publicGroups->map($formatConversation)->values();

        return response()->json([
            'conversations' => $conversations,
            'public_groups' => $available,
        ]);
    }

    /**
     * Create a new discussion topic/channel for a group.
     */
    public function createTopic(Request $request, Conversation $conversation)
    {
        $this->authorizeManage($conversation);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9-]+$/'],
            'description' => ['nullable', 'string', 'max:200'],
            'is_readonly' => ['nullable', 'boolean'],
        ]);

        // Generate slug from name
        $slug = Str::slug($data['name']);

        // Check if slug already exists for this conversation
        $exists = DB::table('chat_discussion_topics')
            ->where('conversation_id', $conversation->id)
            ->where('slug', $slug)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'A channel with this name already exists.'], 422);
        }

        // Get max position
        $maxPosition = DB::table('chat_discussion_topics')
            ->where('conversation_id', $conversation->id)
            ->max('position') ?? 0;

        $topic = DB::table('chat_discussion_topics')->insertGetId([
            'conversation_id' => $conversation->id,
            'created_by' => Auth::id(),
            'slug' => $slug,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'position' => $maxPosition + 1,
            'visibility' => 'public',
            'is_readonly' => $data['is_readonly'] ?? false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'topic' => [
                'id' => $topic,
                'slug' => $slug,
                'name' => $data['name'],
                'is_readonly' => $data['is_readonly'] ?? false,
            ],
        ]);
    }

    /**
     * Delete a discussion topic/channel.
     */
    public function deleteTopic(Request $request, Conversation $conversation, int $topic)
    {
        $this->authorizeManage($conversation);

        DB::table('chat_discussion_topics')
            ->where('id', $topic)
            ->where('conversation_id', $conversation->id)
            ->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Regenerate group invite link.
     */
    public function regenerateInvite(Request $request, Conversation $conversation)
    {
        $this->authorizeManage($conversation);

        // Generate new invite code
        $inviteCode = Str::random(8);

        // Revoke any existing active invites for this conversation
        DB::table('chat_group_invites')
            ->where('conversation_id', $conversation->id)
            ->where('is_revoked', false)
            ->update(['is_revoked' => true]);

        // Create new invite in chat_group_invites table
        DB::table('chat_group_invites')->insert([
            'conversation_id' => $conversation->id,
            'created_by' => Auth::id(),
            'code' => $inviteCode,
            'max_uses' => null,
            'uses_count' => 0,
            'is_revoked' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'invite_code' => $inviteCode,
            'invite_url' => url('/invite/'.$inviteCode),
        ]);
    }

    /**
     * Get current active invite for a group.
     */
    public function getInvite(Request $request, Conversation $conversation)
    {
        $this->authorizeManage($conversation);

        $invite = DB::table('chat_group_invites')
            ->where('conversation_id', $conversation->id)
            ->where('is_revoked', false)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->orderByDesc('created_at')
            ->first();

        if (! $invite) {
            return response()->json([
                'ok' => true,
                'invite_code' => null,
                'invite_url' => null,
            ]);
        }

        return response()->json([
            'ok' => true,
            'invite_code' => $invite->code,
            'invite_url' => url('/invite/'.$invite->code),
        ]);
    }

    /**
     * Join a group via invite link.
     */
    public function joinViaInvite(string $code)
    {
        // Find the invite in chat_group_invites table
        $invite = DB::table('chat_group_invites')
            ->where('code', $code)
            ->where('is_revoked', false)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (! $invite) {
            return redirect()->route('chats.v2')->with('error', 'Invalid or expired invite link.');
        }

        // Check max uses
        if ($invite->max_uses !== null && $invite->uses_count >= $invite->max_uses) {
            return redirect()->route('chats.v2')->with('error', 'This invite link has reached its maximum uses.');
        }

        $conversation = Conversation::where('id', $invite->conversation_id)
            ->where('type', 'group')
            ->first();

        if (! $conversation) {
            return redirect()->route('chats.v2')->with('error', 'Group not found.');
        }

        // Check if invite is enabled in settings
        $settings = $conversation->settings ?? [];
        if (isset($settings['invite_enabled']) && $settings['invite_enabled'] === false) {
            return redirect()->route('chats.v2')->with('error', 'This group is not accepting new members via invite link.');
        }

        $userId = Auth::id();

        $alreadyMember = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $userId)
            ->whereNull('left_at')
            ->exists();

        if ($alreadyMember) {
            return redirect()->route('chats.v2', ['conversation' => $conversation->id])
                ->with('info', 'You are already a member of this group.');
        }

        // Add user to the group
        ConversationParticipant::create([
            'conversation_id' => $conversation->id,
            'user_id' => $userId,
            'role' => 'member',
            'joined_at' => now(),
        ]);

        // Update invite uses count
        DB::table('chat_group_invites')
            ->where('id', $invite->id)
            ->update([
                'uses_count' => $invite->uses_count + 1,
                'last_used_at' => now(),
            ]);

        return redirect()->route('chats.v2', ['conversation' => $conversation->id])
            ->with('success', 'You have joined the group!');
    }

    /**
     * Save group settings (bot configuration, etc.)
     */
    public function saveSettings(Request $request, Conversation $conversation)
    {
        $this->authorizeManage($conversation);

        $data = $request->validate([
            // Group info
            'description' => ['nullable', 'string', 'max:200'],
            'is_public' => ['boolean'],

            // Invite settings
            'invite_enabled' => ['boolean'],
            'require_approval' => ['boolean'],

            // Channel settings
            'channel_admin_only' => ['boolean'],
            'channel_slow_mode' => ['boolean'],
            'channel_visibility' => ['nullable', 'string', 'in:public,private,admin'],

            // Visibility settings
            'show_shared_files' => ['boolean'],

            // Bot settings
            'bot_enabled' => ['boolean'],
            'bot_name' => ['nullable', 'string', 'max:32'],
            'bot_role' => ['nullable', 'string', 'in:moderator,admin,custom'],
            'permissions' => ['nullable', 'array'],
            'permissions.warn' => ['boolean'],
            'permissions.mute' => ['boolean'],
            'permissions.kick' => ['boolean'],
            'permissions.delete' => ['boolean'],
            'rules' => ['nullable', 'array'],
            'rules.profanity' => ['boolean'],
            'rules.spam' => ['boolean'],
            'rules.links' => ['boolean'],
            'rules.caps' => ['boolean'],
            'violation_action' => ['nullable', 'string'],
        ]);

        // Update is_public on the conversation model directly
        if (isset($data['is_public'])) {
            $conversation->is_public = $data['is_public'];
        }

        // Store description in settings
        $settings = $conversation->settings ?? [];
        $settings['description'] = $data['description'] ?? null;
        $settings['invite_enabled'] = $data['invite_enabled'] ?? false;
        $settings['require_approval'] = $data['require_approval'] ?? false;
        $settings['channel_admin_only'] = $data['channel_admin_only'] ?? false;
        $settings['channel_slow_mode'] = $data['channel_slow_mode'] ?? false;
        $settings['channel_visibility'] = $data['channel_visibility'] ?? 'public';
        $settings['show_shared_files'] = $data['show_shared_files'] ?? ($settings['show_shared_files'] ?? false);
        $settings['bot_enabled'] = $data['bot_enabled'] ?? false;
        $settings['bot_name'] = $data['bot_name'] ?? 'Hillbot';
        $settings['bot_role'] = $data['bot_role'] ?? 'moderator';
        $settings['permissions'] = $data['permissions'] ?? [];
        $settings['rules'] = $data['rules'] ?? [];
        $settings['violation_action'] = $data['violation_action'] ?? 'warn';

        $conversation->settings = $settings;
        $conversation->save();

        return response()->json(['ok' => true, 'settings' => $conversation->settings, 'is_public' => $conversation->is_public]);
    }

    /* ----------------- Join Requests ----------------- */

    /**
     * Request to join a group (when approval is required)
     */
    public function requestJoin(Request $request, Conversation $conversation)
    {
        $userId = Auth::id();

        if ($conversation->type !== 'group') {
            return response()->json(['error' => 'Not a group'], 400);
        }

        // Check if already a member
        $isMember = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $userId)
            ->whereNull('left_at')
            ->exists();

        if ($isMember) {
            return response()->json(['error' => 'Already a member'], 400);
        }

        // Check if already has pending request
        $existingRequest = GroupJoinRequest::where('conversation_id', $conversation->id)
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->exists();

        if ($existingRequest) {
            return response()->json(['error' => 'Request already pending'], 400);
        }

        // Create join request
        $joinRequest = GroupJoinRequest::create([
            'conversation_id' => $conversation->id,
            'user_id' => $userId,
            'message' => $request->input('message'),
            'status' => 'pending',
        ]);

        return response()->json(['ok' => true, 'request_id' => $joinRequest->id]);
    }

    /**
     * Get pending join requests for a group (admin only)
     */
    public function getJoinRequests(Conversation $conversation)
    {
        $this->authorizeManage($conversation);

        $requests = GroupJoinRequest::with('user:id,name,email,profile_photo_path')
            ->where('conversation_id', $conversation->id)
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($req) {
                return [
                    'id' => $req->id,
                    'user_id' => $req->user_id,
                    'name' => $req->user->name ?? 'Unknown',
                    'email' => $req->user->email ?? '',
                    'avatar' => $req->user->profile_photo_path
                        ? asset('storage/'.$req->user->profile_photo_path)
                        : 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($req->user->name ?? 'U').'&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981',
                    'message' => $req->message,
                    'created_at' => $req->created_at->diffForHumans(),
                ];
            });

        return response()->json(['ok' => true, 'requests' => $requests]);
    }

    /**
     * Approve a join request
     */
    public function approveJoinRequest(Request $request, Conversation $conversation, $requestId)
    {
        $this->authorizeManage($conversation);

        $joinRequest = GroupJoinRequest::where('id', $requestId)
            ->where('conversation_id', $conversation->id)
            ->where('status', 'pending')
            ->firstOrFail();

        // Add user to group
        ConversationParticipant::create([
            'conversation_id' => $conversation->id,
            'user_id' => $joinRequest->user_id,
            'role' => 'member',
            'joined_at' => now(),
        ]);

        // Update request status
        $joinRequest->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Reject a join request
     */
    public function rejectJoinRequest(Request $request, Conversation $conversation, $requestId)
    {
        $this->authorizeManage($conversation);

        $joinRequest = GroupJoinRequest::where('id', $requestId)
            ->where('conversation_id', $conversation->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $joinRequest->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Get count of pending join requests for groups user owns
     */
    public function getPendingRequestsCount()
    {
        $userId = Auth::id();

        $count = GroupJoinRequest::whereHas('conversation', function ($q) use ($userId) {
            $q->where('created_by', $userId)->where('type', 'group');
        })->where('status', 'pending')->count();

        return response()->json(['ok' => true, 'count' => $count]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        $conversationIds = ConversationParticipant::where('user_id', $user->id)
            ->whereNull('left_at')
            ->pluck('conversation_id');

        $unreadMessageIds = Message::whereIn('conversation_id', $conversationIds)
            ->where('user_id', '!=', $user->id)
            ->whereNotIn('id', function ($query) use ($user) {
                $query->select('message_id')
                    ->from('chat_message_reads')
                    ->where('user_id', $user->id);
            })
            ->pluck('id');

        $now = now();
        $readRecords = $unreadMessageIds->map(function ($messageId) use ($user, $now) {
            return [
                'user_id' => $user->id,
                'message_id' => $messageId,
                'read_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->toArray();

        foreach (array_chunk($readRecords, 500) as $chunk) {
            DB::table('chat_message_reads')->insertOrIgnore($chunk);
        }

        ConversationParticipant::where('user_id', $user->id)
            ->whereNull('left_at')
            ->update(['unread_count' => 0]);

        return response()->json(['success' => true, 'marked' => count($readRecords)]);
    }

    public function getDrafts(Request $request)
    {
        $user = $request->user();

        $drafts = DB::table('chat_drafts')
            ->leftJoin('conversations', 'chat_drafts.conversation_id', '=', 'conversations.id')
            ->where('chat_drafts.user_id', $user->id)
            ->select([
                'chat_drafts.id',
                'chat_drafts.body',
                'chat_drafts.conversation_id',
                'conversations.name as conversation_name',
                'conversations.type as conversation_type',
                'chat_drafts.created_at',
                'chat_drafts.updated_at',
            ])
            ->latest('chat_drafts.updated_at')
            ->get();

        return response()->json(['success' => true, 'drafts' => $drafts]);
    }

    public function saveDraft(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'body' => 'required|string|max:10000',
        ]);

        $user = $request->user();

        DB::table('chat_drafts')->updateOrInsert(
            [
                'user_id' => $user->id,
                'conversation_id' => $request->conversation_id,
            ],
            [
                'body' => $request->body,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json(['success' => true]);
    }

    public function deleteDraft(Request $request, $draft)
    {
        $user = $request->user();

        $deleted = DB::table('chat_drafts')
            ->where('id', $draft)
            ->where('user_id', $user->id)
            ->delete();

        return response()->json(['success' => $deleted > 0]);
    }

    /* ----------------- helpers ----------------- */

    protected function authorizeManage(Conversation $conversation): void
    {
        $user = Auth::user();
        $userId = $user->id;

        // System moderators can manage ANY group (super admin)
        if ($user->isModerator()) {
            return;
        }

        // Regular users can only manage groups they own or are admin of
        if ($conversation->type !== 'group') {
            abort(403, 'This is not a group conversation.');
        }

        // Check if user is owner
        if ((int) $conversation->created_by === (int) $userId) {
            return;
        }

        // Check if user is admin in this group
        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $userId)
            ->whereNull('left_at')
            ->first();

        if ($participant && $participant->role === 'admin') {
            return;
        }

        abort(403, 'You are not allowed to manage this group.');
    }

    /**
     * Check if current user is a system moderator (super admin)
     */
    protected function isSystemModerator(): bool
    {
        return Auth::user()?->isModerator() ?? false;
    }

    /**
     * Toggle status visibility for moderators (hide/show online status)
     */
    public function toggleStatusVisibility(Request $request)
    {
        $user = $request->user();

        // Only moderators can hide their status
        if (! $user->isModerator()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'hidden' => 'required|boolean',
        ]);

        $hidden = $request->boolean('hidden');

        // Store in presence_statuses table using 'invisible' status
        PresenceStatus::updateOrCreate(
            ['user_id' => $user->id],
            [
                'status' => $hidden ? 'invisible' : 'online',
                'last_active_at' => now(),
                // Invisible status lasts 24 hours, online status lasts 5 minutes
                'expires_at' => $hidden ? now()->addHours(24) : now()->addMinutes(5),
            ]
        );

        // Get user avatar for broadcast
        $avatar = $user->profile_photo_path
            ? asset('storage/'.ltrim($user->profile_photo_path, '/'))
            : 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($user->name).'&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981';

        // Broadcast the status change via websocket
        broadcast(new \App\Events\UserPresenceChanged(
            $user->id,
            $user->name,
            $hidden ? 'invisible' : 'online',
            $avatar
        ))->toOthers();

        return response()->json([
            'success' => true,
            'hidden' => $hidden,
            'user_id' => $user->id,
        ]);
    }
}
