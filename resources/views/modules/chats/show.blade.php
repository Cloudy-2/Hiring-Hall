@php
    use Illuminate\Support\Str;

    $isGroup = $conversation->is_group ?? $conversation->type === 'group';
    $participants = ($conversation->participants ?? collect())->whereNull('left_at');
    $messages = $conversation->messages ?? collect();
    $isLocked = $conversation->is_locked ?? false;
    $selfParticipant = $participants->firstWhere('user_id', auth()->id());
    $isSelfMuted = (bool) ($selfParticipant?->is_muted) && (is_null($selfParticipant?->muted_until) || optional($selfParticipant->muted_until)->isFuture());

    $participantsCount = $participants->count();
    $messagesCount = $messages->count();
@endphp

<x-app-layout>
    <x-slot name="return">{"link": "/chats/monitor", "text": "Back to Monitor"}</x-slot>
    <x-slot name="title">{{ $conversation->display_title }}</x-slot>
    <x-slot name="url_1">{"link": "/chats/monitor", "text": "Chat Monitor"}</x-slot>
    <x-slot name="active">Messages</x-slot>

    <div id="chat-moderator-page" class="space-y-6" data-conversation-id="{{ $conversation->id }}" data-conversation-title="{{ e($conversation->display_title) }}" data-csrf-token="{{ csrf_token() }}">
        <div class="box border">
            <div class="box-body flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3">
                    @if($isGroup && $conversation->photo)
                    <img src="{{ asset('storage/' . ltrim($conversation->photo, '/')) }}" alt="{{ $conversation->display_title }}" class="w-12 h-12 rounded-full object-cover">
                    @elseif($isGroup)
                    <img src="https://api.dicebear.com/7.x/identicon/svg?seed={{ urlencode($conversation->display_title) }}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981" alt="{{ $conversation->display_title }}" class="w-12 h-12 rounded-full object-cover">
                    @else
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($conversation->display_title) }}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981" alt="{{ $conversation->display_title }}" class="w-12 h-12 rounded-full object-cover">
                    @endif
                    <div>
                        <h1 class="text-xl font-semibold flex items-center gap-2">
                            {{ $conversation->display_title }}
                            <span class="badge {{ $isGroup ? 'bg-success/10 text-success' : 'bg-info/10 text-info' }}">
                                {{ $isGroup ? 'Group Chat' : 'DM' }}
                            </span>
                        </h1>
                        <p class="text-xs text-textmuted">ID: #{{ date_format($conversation->created_at, 'dmy') . $conversation->id }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-center">
                        <p class="text-2xl font-semibold">{{ $messagesCount }}</p>
                        <p class="text-xs text-textmuted">Messages</p>
                    </div>
                    <div class="h-8 w-px bg-defaultborder"></div>
                    <div class="text-center">
                        <p class="text-2xl font-semibold">{{ $participantsCount }}</p>
                        <p class="text-xs text-textmuted">Members</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-4">
                <div class="box border h-full">
                    <div class="box-header">
                        <div class="box-title flex items-center gap-2">
                            <i class="bi bi-info-circle text-textmuted"></i>
                            Conversation Info
                        </div>
                        @if($isLocked)
                        <span class="badge bg-danger/10 text-danger">
                            <i class="bi bi-shield-lock me-1"></i> Locked
                        </span>
                        @else
                        <span class="badge bg-success/10 text-success">
                            <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse inline-block me-1"></span>
                            Active
                        </span>
                        @endif
                    </div>
                    <div class="box-body space-y-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-textmuted text-xs">Created At</p>
                                <p class="font-medium">{{ optional($conversation->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-textmuted text-xs">Last Activity</p>
                                <p class="font-medium">{{ optional($conversation->updated_at)->diffForHumans() ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-textmuted text-xs">Total Messages</p>
                                <p class="font-medium">{{ $messagesCount }}</p>
                            </div>
                            <div>
                                <p class="text-textmuted text-xs">Members</p>
                                <p class="font-medium">{{ $participantsCount }}</p>
                            </div>
                        </div>

                        <hr class="border-defaultborder">

                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold flex items-center gap-2">
                                    <i class="bi bi-people text-textmuted"></i>
                                    Members
                                </h3>
                                <span class="text-xs text-textmuted">{{ $participantsCount }} member{{ $participantsCount === 1 ? '' : 's' }}</span>
                            </div>
                            <div class="border rounded-lg dark:border-defaultborder/10 max-h-64 overflow-y-auto">
                                @forelse ($participants as $p)
                                @php
                                    $member = $p->user;
                                    $name = $member->name ?? 'User';
                                    $email = $member->email ?? null;
                                    $role = $p->role ?? 'Member';
                                    $isMemberMuted = (bool) ($p->is_muted) && (is_null($p->muted_until) || optional($p->muted_until)->isFuture());
                                @endphp
                                <div class="flex items-center gap-3 p-3 border-b dark:border-defaultborder/10 last:border-b-0 hover:bg-light/50 transition">
                                    <img src="{{ $member->profile_photo_path ? asset('storage/' . ltrim($member->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($name) . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}" alt="{{ $name }}" class="w-10 h-10 rounded-full object-cover">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-sm truncate">{{ $name }}</p>
                                        @if($email)
                                        <p class="text-xs text-textmuted truncate">{{ $email }}</p>
                                        @endif
                                        <p class="text-xs text-textmuted">Role: <span class="font-medium">{{ Str::title($role) }}</span></p>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        @if($isMemberMuted)
                                        <button type="button" title="Unmute member" class="btn-unmute-member ti-btn ti-btn-sm ti-btn-success-full rounded-full !p-1.5" data-member-id="{{ $member->id ?? '' }}" data-member-name="{{ $name }}">
                                            <i class="bi bi-bell-slash"></i>
                                        </button>
                                        @else
                                        <button type="button" title="Mute member" class="btn-mute-member ti-btn ti-btn-sm ti-btn-warning-full rounded-full !p-1.5" data-member-id="{{ $member->id ?? '' }}" data-member-name="{{ $name }}">
                                            <i class="bi bi-bell"></i>
                                        </button>
                                        @endif
                                        <button type="button" title="Remove member" class="btn-remove-member ti-btn ti-btn-sm ti-btn-danger-full rounded-full !p-1.5" data-member-id="{{ $member->id ?? '' }}" data-member-name="{{ $name }}">
                                            <i class="bi bi-person-dash"></i>
                                        </button>
                                    </div>
                                </div>
                                @empty
                                <div class="p-4 text-center text-textmuted text-sm">No members found.</div>
                                @endforelse
                            </div>
                        </div>

                        <hr class="border-defaultborder">

                        <div class="flex flex-wrap gap-2">
                            @if($isGroup)
                            <button type="button" id="btn-add-member" class="ti-btn ti-btn-sm ti-btn-light">
                                <i class="bi bi-person-plus-fill me-1"></i> Add Member
                            </button>
                            @endif
                            @if($isSelfMuted)
                            <button type="button" id="btn-unmute-chat" class="ti-btn ti-btn-sm ti-btn-success-full" title="Enable notifications for this conversation">
                                <i class="bi bi-bell-fill me-1"></i> Unmute notifications
                            </button>
                            @else
                            <button type="button" id="btn-mute-chat" class="ti-btn ti-btn-sm ti-btn-warning-full" title="Stop notifications for this conversation">
                                <i class="bi bi-bell-slash-fill me-1"></i> Mute notifications
                            </button>
                            @endif
                            @if($isLocked)
                            <button type="button" id="btn-unlock-chat" class="ti-btn ti-btn-sm ti-btn-success-full">
                                <i class="bi bi-shield-unlock-fill me-1"></i> Unlock Chat
                            </button>
                            @else
                            <button type="button" id="btn-lock-chat" class="ti-btn ti-btn-sm ti-btn-danger-full">
                                <i class="bi bi-shield-lock-fill me-1"></i> Lock Chat
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-8">
                <div class="box border flex flex-col" style="height: calc(100vh - 280px); min-height: 500px;">
                    <div class="box-header flex items-center justify-between">
                        <div>
                            <div class="box-title flex items-center gap-2">
                                <i class="bi bi-chat-dots text-textmuted"></i>
                                Messages
                                <span class="text-sm font-normal text-textmuted">({{ $messagesCount }} total)</span>
                            </div>
                            <p class="text-xs text-textmuted">Scroll to view full conversation history.</p>
                        </div>
                        <span class="text-xs text-textmuted">
                            <i class="bi bi-arrow-down-circle me-1"></i> Oldest at top, newest at bottom
                        </span>
                    </div>

                    @php $currentDate = null; @endphp
                    <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-3">
                        @forelse ($messages->sortBy('created_at') as $msg)
                        @php
                            $sender = $msg->user;
                            $senderName = $sender->name ?? 'System';
                            $isSystem = ($msg->type ?? null) === 'system';
                            $dateLabel = optional($msg->created_at)->format('M d, Y');
                        @endphp

                        @if ($dateLabel && $dateLabel !== $currentDate)
                        @php $currentDate = $dateLabel; @endphp
                        <div class="flex items-center justify-center my-3">
                            <div class="flex-1 border-t border-defaultborder"></div>
                            <span class="px-3 py-1 mx-3 rounded-full bg-light text-textmuted text-xs border border-defaultborder">
                                <i class="bi bi-calendar3 me-1"></i> {{ $dateLabel }}
                            </span>
                            <div class="flex-1 border-t border-defaultborder"></div>
                        </div>
                        @endif

                        @if ($isSystem)
                        <div class="flex items-center justify-center">
                            <span class="px-3 py-1 rounded-full bg-light/50 text-textmuted text-xs border border-dashed border-defaultborder">
                                <i class="bi bi-gear-wide-connected me-1"></i> {{ $msg->body }}
                            </span>
                        </div>
                        @else
                        <div class="flex items-start gap-2">
                            <img src="{{ $sender && $sender->profile_photo_path ? asset('storage/' . ltrim($sender->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($senderName) . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}" alt="{{ $senderName }}" class="w-8 h-8 rounded-full object-cover mt-0.5">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-medium text-sm">{{ $senderName }}</span>
                                    <span class="text-xs text-textmuted">{{ optional($msg->created_at)->format('H:i') }}</span>
                                    @if($msg->created_at && $msg->created_at->diffInDays(now()) > 0)
                                    <span class="text-xs text-textmuted">({{ $msg->created_at->diffForHumans() }})</span>
                                    @endif
                                </div>
                                <div class="mt-1 inline-block rounded-lg px-3 py-2 text-sm bg-light/50 border border-defaultborder/50">
                                    {{ $msg->body }}
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <div class="h-full flex items-center justify-center text-textmuted text-sm">
                            No messages in this conversation yet.
                        </div>
                        @endforelse
                    </div>

                    <div class="box-footer flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 text-textmuted">
                            <i class="bi bi-shield-check"></i>
                            <span>Moderator view only - no messages can be sent here.</span>
                        </div>
                        <a href="{{ route('chats.v2', ['conversation' => $conversation->id]) }}" class="ti-btn ti-btn-sm ti-btn-primary-full">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Open in Chat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.modules.chat.add-member-modal', ['conversationId' => $conversation->id])

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/chat-moderator-show.js') }}"></script>
</x-app-layout>
