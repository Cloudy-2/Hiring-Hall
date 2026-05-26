{{-- Shared Sidebar Content - Used by both mobile and desktop --}}
@php
    $isGroup = $selectedConversation?->type === 'group';
    $canCreateChats = auth()->user()->canCreateChats();
    $canCreateGroups = auth()->user()->canCreateGroups();
    $isModerator = auth()->user()->isModerator();
    $isCandidate = auth()->user()->isCandidate();
    $hasAnyGroups = isset($servers) && count($servers) > 0;

    $getInitials = function($name) {
        $words = explode(' ', trim($name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    };

    $getColorFromName = function($name) {
        $colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f97316', '#10b981', '#3b82f6', '#ef4444', '#f59e0b'];
        $hash = 0;
        for ($i = 0; $i < strlen($name); $i++) {
            $hash = ord($name[$i]) + (($hash << 5) - $hash);
        }
        return $colors[abs($hash) % count($colors)];
    };
@endphp

<div class="flex h-full flex-col justify-between p-2">
    <div class="flex flex-col gap-2 overflow-y-auto scrollbar-hide">
        {{-- Header --}}
        <div class="flex items-center gap-3 p-2">
            @if($isGroup)
                {{-- Back button to return to main DM view --}}
                <a href="{{ route('chats.v2', ['view' => 'home']) }}"
                    onclick="window.closeMobileNav?.()"
                    class="size-10 rounded-lg bg-slate-100 dark:bg-white/10 flex items-center justify-center text-gray-600 dark:text-white/70 hover:bg-primary/20 hover:text-primary transition"
                    title="Back to DMs">
                    <i class="bi bi-arrow-left text-lg"></i>
                </a>
                @php
                    $groupInitials = $getInitials($selectedConversation->name);
                    $groupColor = $getColorFromName($selectedConversation->name);
                @endphp
                <div class="size-10 rounded-lg flex items-center justify-center text-white font-semibold text-sm"
                    style="background-color: {{ $groupColor }};">
                    {{ $groupInitials }}
                </div>
            @else
                <div class="size-10 rounded-lg bg-cover bg-center bg-no-repeat"
                    style="background-image:url('{{ $selectedConversation?->photo ? asset('storage/' . ltrim($selectedConversation->photo, '/')) : 'https://api.dicebear.com/7.x/shapes/svg?seed=' . urlencode($selectedConversation->name ?? 'Friends') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981&backgroundType=gradientLinear' }}');">
                </div>
            @endif
            <div>
                <h1 class="text-base font-semibold leading-normal text-gray-900 dark:text-white">
                    {{ $selectedConversation?->name ?? 'Friends' }}
                </h1>
                <p class="text-xs text-gray-500 dark:text-white/50">
                    @if($isGroup)
                        Server · {{ $selectedConversation->participants->whereNull('left_at')->count() ?? 0 }} members
                    @else
                        {{ $friendStats['friends'] ?? 0 }} friends · {{ $friendStats['online'] ?? 0 }} online
                    @endif
                </p>
            </div>
        </div>

        {{-- Candidate Waiting Notice - only show if candidate has no groups assigned --}}
        @if($isCandidate && !$selectedConversation && !$hasAnyGroups)
            <div class="mx-2 rounded-lg border border-amber-300 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-900/20 px-3 py-3">
                <div class="flex items-center gap-2 mb-2">
                    <i class="bi bi-hourglass-split text-amber-600 dark:text-amber-400"></i>
                    <span class="text-sm font-medium text-amber-700 dark:text-amber-400">Waiting for Assignment</span>
                </div>
                <p class="text-xs text-amber-600 dark:text-amber-400/80">
                    A moderator will assign you to a group chat soon. You'll be able to read messages and participate once assigned.
                </p>
            </div>
        @endif

        <div class="flex flex-col gap-0.5 px-1">
            @php
                // Build filter URLs - include conversation ID if inside a group
                $filterParams = $isGroup && $selectedConversation ? ['conversation' => $selectedConversation->id] : [];
            @endphp

            {{-- All Unread --}}
            <a href="{{ route('chats.v2', array_merge($filterParams, ['filter' => 'unread'])) }}"
                onclick="window.closeMobileNav?.()"
                class="flex items-center gap-2 rounded px-2 py-1.5 text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 transition {{ request()->query('filter') === 'unread' ? 'bg-primary/20 !text-gray-900 dark:!text-white' : '' }}">
                <i class="bi bi-list-ul text-base text-gray-500 dark:text-white/50"></i>
                <span class="text-sm font-medium">All unread</span>
            </a>

            {{-- Threads --}}
            <a href="{{ route('chats.v2', array_merge($filterParams, ['filter' => 'threads'])) }}"
                onclick="window.closeMobileNav?.()"
                class="flex items-center gap-2 rounded px-2 py-1.5 text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 transition {{ request()->query('filter') === 'threads' ? 'bg-primary/20 !text-gray-900 dark:!text-white' : '' }}">
                <i class="bi bi-chat-square-text text-base text-gray-500 dark:text-white/50"></i>
                <span class="text-sm font-medium">Threads</span>
            </a>

            {{-- Mentions & Reactions --}}
            <a href="{{ route('chats.v2', array_merge($filterParams, ['filter' => 'mentions'])) }}"
                onclick="window.closeMobileNav?.()"
                class="flex items-center gap-2 rounded px-2 py-1.5 text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 transition {{ request()->query('filter') === 'mentions' ? 'bg-primary/20 !text-gray-900 dark:!text-white' : '' }}">
                <i class="bi bi-at text-base text-gray-500 dark:text-white/50"></i>
                <span class="text-sm font-medium">Mentions & reactions</span>
            </a>

            {{-- Drafts --}}
            <a href="{{ route('chats.v2', array_merge($filterParams, ['filter' => 'drafts'])) }}"
                onclick="window.closeMobileNav?.()"
                class="flex items-center gap-2 rounded px-2 py-1.5 text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 transition {{ request()->query('filter') === 'drafts' ? 'bg-primary/20 !text-gray-900 dark:!text-white' : '' }}">
                <i class="bi bi-pencil-square text-base text-gray-500 dark:text-white/50"></i>
                <span class="text-sm font-medium">Drafts</span>
            </a>
        </div>

        {{-- Divider --}}
        <div class="h-px bg-gray-200 dark:bg-white/10 mx-2 my-1"></div>

        @if($isGroup)
            {{-- Starred Channels Section --}}
            @php
                $currentTopic = request()->query('topic');
                $publicTopics = collect($topics ?? [])->filter(fn($t) => ($t['visibility'] ?? 'public') !== 'moderator');
                $starredTopics = $publicTopics->filter(fn($t) => (($t['is_starred'] ?? false) || strtolower(ltrim($t['name'] ?? '', '#')) === 'rules') && !($t['archived'] ?? false));
                $regularTopics = $publicTopics->filter(fn($t) => !($t['is_starred'] ?? false) && strtolower(ltrim($t['name'] ?? '', '#')) !== 'rules' && !($t['archived'] ?? false));
                $archivedTopics = $publicTopics->filter(fn($t) => $t['archived'] ?? false);
            @endphp

            @if($starredTopics->count() > 0)
            <div class="sidebar-section" data-section="starred-channels">
                <button type="button" class="sidebar-section-header w-full flex items-center justify-between px-2 py-1 rounded hover:bg-slate-100 dark:hover:bg-white/10 transition group" onclick="toggleSidebarSection('starred-channels')">
                    <div class="flex items-center gap-1">
                        <i class="bi bi-chevron-down text-[10px] text-gray-400 dark:text-white/40 transition-transform section-chevron"></i>
                        <i class="bi bi-star-fill text-amber-400 text-[10px]"></i>
                        <span class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50 group-hover:text-gray-700 dark:group-hover:text-white/70">Starred</span>
                    </div>
                </button>
                <div class="sidebar-section-content flex flex-col">
                    @foreach($starredTopics as $topic)
                        @php $isActive = $currentTopic === ($topic['slug'] ?? ''); @endphp
                        <a href="{{ route('chats.v2', ['conversation' => $selectedConversation->id, 'topic' => $topic['slug'] ?? $topic['name']]) }}"
                            onclick="window.closeMobileNav?.(); markChannelMentionsAsRead({{ $selectedConversation->id }}, '{{ $topic['slug'] ?? $topic['name'] }}');"
                            class="flex items-center gap-2 rounded px-2 py-0.5 {{ $isActive ? 'bg-primary/20 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                            <i class="bi bi-hash text-base {{ $isActive ? 'text-amber-500' : 'text-amber-400/70' }}"></i>
                            <span class="text-sm">{{ ltrim($topic['name'] ?? '', '#') }}</span>
                            <span class="channel-mention-badge hidden min-w-[1.1rem] h-[1.1rem] items-center justify-center rounded-full bg-red-500 text-[9px] font-bold leading-none text-white ml-auto" data-conversation-id="{{ $selectedConversation->id }}" data-topic-id="{{ $topic['slug'] ?? $topic['name'] }}"></span>
                            @if($isActive)
                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-white/50" viewBox="0 0 16 16" fill="currentColor">
                                <rect x="2" y="5" width="6" height="6" rx="1" transform="rotate(-45 5 8)"/>
                                <rect x="6" y="5" width="6" height="6" rx="1" transform="rotate(-45 9 8)"/>
                            </svg>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="sidebar-section" data-section="channels">
                <button type="button" class="sidebar-section-header w-full flex items-center justify-between px-2 py-1 rounded hover:bg-slate-100 dark:hover:bg-white/10 transition group" onclick="toggleSidebarSection('channels')">
                    <div class="flex items-center gap-1">
                        <i class="bi bi-chevron-down text-[10px] text-gray-400 dark:text-white/40 transition-transform section-chevron"></i>
                        <span class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50 group-hover:text-gray-700 dark:group-hover:text-white/70">Channels</span>
                    </div>
                    @if($isModerator)
                        <span class="opacity-0 group-hover:opacity-100 rounded p-0.5 text-gray-400 dark:text-white/40 hover:text-primary hover:bg-primary/10 transition"
                            data-open-chat-modal="create-channel" title="Add channel"
                            onclick="event.stopPropagation(); setTimeout(() => window.closeMobileNav?.(), 100)">
                            <i class="bi bi-plus text-sm"></i>
                        </span>
                    @endif
                </button>
                <div class="sidebar-section-content flex flex-col">
                    <a href="{{ route('chats.v2', ['conversation' => $selectedConversation->id]) }}"
                        onclick="window.closeMobileNav?.(); markChannelMentionsAsRead({{ $selectedConversation->id }}, null);"
                        class="flex items-center gap-2 rounded px-2 py-0.5 {{ !$currentTopic ? 'bg-primary/20 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                        <i class="bi bi-hash text-base {{ !$currentTopic ? 'text-gray-700 dark:text-white/80' : 'text-gray-400 dark:text-white/40' }}"></i>
                        <span class="text-sm">general</span>
                        <span class="channel-mention-badge hidden min-w-[1.1rem] h-[1.1rem] items-center justify-center rounded-full bg-red-500 text-[9px] font-bold leading-none text-white ml-auto" data-conversation-id="{{ $selectedConversation->id }}" data-topic-id="general"></span>
                    </a>
                    @forelse($regularTopics as $topic)
                        @php $isActive = $currentTopic === ($topic['slug'] ?? ''); @endphp
                        <a href="{{ route('chats.v2', ['conversation' => $selectedConversation->id, 'topic' => $topic['slug'] ?? $topic['name']]) }}"
                            onclick="window.closeMobileNav?.(); markChannelMentionsAsRead({{ $selectedConversation->id }}, '{{ $topic['slug'] ?? $topic['name'] }}');"
                            class="flex items-center gap-2 rounded px-2 py-0.5 {{ $isActive ? 'bg-primary/20 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                            <i class="bi bi-hash text-base {{ $isActive ? 'text-gray-700 dark:text-white/80' : 'text-gray-400 dark:text-white/40' }}"></i>
                            <span class="text-sm">{{ ltrim($topic['name'] ?? '', '#') }}</span>
                            <span class="channel-mention-badge hidden min-w-[1.1rem] h-[1.1rem] items-center justify-center rounded-full bg-red-500 text-[9px] font-bold leading-none text-white ml-auto" data-conversation-id="{{ $selectedConversation->id }}" data-topic-id="{{ $topic['slug'] ?? $topic['name'] }}"></span>
                        </a>
                    @empty
                    @endforelse
                </div>
            </div>

            {{-- Archived Channels (Moderator Only) --}}
            @if($isModerator && $archivedTopics->count() > 0)
            <div class="sidebar-section" data-section="archived-channels">
                <button type="button" class="sidebar-section-header w-full flex items-center justify-between px-2 py-1 rounded hover:bg-slate-100 dark:hover:bg-white/10 transition group" onclick="toggleSidebarSection('archived-channels')">
                    <div class="flex items-center gap-1">
                        <i class="bi bi-chevron-down text-[10px] text-gray-400 dark:text-white/40 transition-transform section-chevron"></i>
                        <i class="bi bi-archive text-gray-400 dark:text-white/40 text-[10px]"></i>
                        <span class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50 group-hover:text-gray-700 dark:group-hover:text-white/70">Archived</span>
                    </div>
                </button>
                <div class="sidebar-section-content flex flex-col hidden">
                    @foreach($archivedTopics as $topic)
                        @php $isActive = $currentTopic === ($topic['slug'] ?? ''); @endphp
                        <div class="flex items-center gap-1 rounded px-2 py-0.5 group {{ $isActive ? 'bg-primary/20' : 'hover:bg-slate-100 dark:hover:bg-white/10' }}">
                            <a href="{{ route('chats.v2', ['conversation' => $selectedConversation->id, 'topic' => $topic['slug'] ?? $topic['name']]) }}"
                                onclick="window.closeMobileNav?.()"
                                class="flex-1 flex items-center gap-2 {{ $isActive ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-white/50' }}">
                                <i class="bi bi-hash text-base text-gray-400 dark:text-white/30"></i>
                                <span class="text-sm line-through opacity-70">{{ ltrim($topic['name'] ?? '', '#') }}</span>
                            </a>
                            <button type="button" onclick="unarchiveChannel({{ $topic['id'] }}, '{{ $topic['name'] }}')"
                                class="opacity-0 group-hover:opacity-100 p-1 rounded text-gray-400 dark:text-white/40 hover:text-green-500 hover:bg-green-500/10 transition" title="Restore channel">
                                <i class="bi bi-arrow-counterclockwise text-xs"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Muted Users Section (Moderator Only) --}}
            @if($isModerator)
            @php
                $mutedCount = ($rightColumn['muted'] ?? collect())->count();
            @endphp
            <div id="sidebar-muted-section" class="sidebar-section {{ $mutedCount === 0 ? 'hidden' : '' }}" data-section="muted-users">
                <button type="button" class="sidebar-section-header w-full flex items-center justify-between px-2 py-1 rounded hover:bg-slate-100 dark:hover:bg-white/10 transition group" onclick="toggleSidebarSection('muted-users')">
                    <div class="flex items-center gap-1">
                        <i class="bi bi-chevron-down text-[10px] text-gray-400 dark:text-white/40 transition-transform section-chevron"></i>
                        <i class="bi bi-mic-mute-fill text-red-500 text-[10px]"></i>
                        <span class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50 group-hover:text-gray-700 dark:group-hover:text-white/70">Muted</span>
                    </div>
                    <span id="sidebar-muted-count" class="flex min-w-[1.2rem] items-center justify-center rounded-full bg-red-500 px-1 py-0.5 text-[10px] font-bold text-white">{{ $mutedCount }}</span>
                </button>
                <div class="sidebar-section-content flex flex-col">
                    @forelse($rightColumn['muted'] ?? [] as $member)
                        @php
                            $memberId = $member['user_id'] ?? $member['id'] ?? '';
                            $mutedUntil = $member['muted_until'] ?? null;
                        @endphp
                        <div class="sidebar-muted-item flex items-center gap-2 rounded px-2 py-1 text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 group" data-user-id="{{ $memberId }}">
                            <i class="bi bi-mic-mute text-red-500 text-sm sidebar-mute-icon"></i>
                            <span class="text-sm flex-1 truncate">{{ $member['name'] }}</span>
                            <button type="button" onclick="sidebarUnmuteUser({{ $memberId }}, '{{ addslashes($member['name']) }}', this)"
                                class="sidebar-unmute-btn opacity-0 group-hover:opacity-100 p-1 rounded text-red-500 hover:text-green-500 hover:bg-green-500/10 transition" title="Unmute">
                                <i class="bi bi-volume-up-fill text-xs"></i>
                            </button>
                        </div>
                    @empty
                        <p class="px-2 py-1 text-xs text-gray-400 dark:text-white/40">No muted users</p>
                    @endforelse
                </div>
            </div>

            {{-- Moderators Section (Moderator Only) --}}
            @php
                $modTopicsFromDb = collect($topics ?? [])->filter(fn($t) => ($t['visibility'] ?? 'public') === 'moderator');
                $modTopicIcons = [
                    'mod-chat' => ['icon' => 'bi-chat-dots', 'color' => 'text-blue-500'],
                    'mod-bot-history' => ['icon' => 'bi-robot', 'color' => 'text-purple-500'],
                    'mod-task' => ['icon' => 'bi-list-task', 'color' => 'text-green-500'],
                ];
            @endphp
            @if($modTopicsFromDb->count() > 0)
            <div class="sidebar-section" data-section="moderators">
                <button type="button" class="sidebar-section-header w-full flex items-center justify-between px-2 py-1 rounded hover:bg-slate-100 dark:hover:bg-white/10 transition group" onclick="toggleSidebarSection('moderators')">
                    <div class="flex items-center gap-1">
                        <i class="bi bi-chevron-down text-[10px] text-gray-400 dark:text-white/40 transition-transform section-chevron"></i>
                        <i class="bi bi-shield-check text-blue-500 text-[10px]"></i>
                        <span class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50 group-hover:text-gray-700 dark:group-hover:text-white/70">Moderators</span>
                    </div>
                </button>
                <div class="sidebar-section-content flex flex-col">
                    @foreach($modTopicsFromDb as $modTopic)
                        @php
                            $isModActive = $currentTopic === $modTopic['slug'];
                            $iconData = $modTopicIcons[$modTopic['slug']] ?? ['icon' => 'bi-hash', 'color' => 'text-gray-400'];
                        @endphp
                        <a href="{{ route('chats.v2', ['conversation' => $selectedConversation->id, 'topic' => $modTopic['slug']]) }}"
                            onclick="window.closeMobileNav?.()"
                            class="flex items-center gap-2 rounded px-2 py-0.5 {{ $isModActive ? 'bg-primary/20 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                            <i class="bi bi-hash text-base {{ $isModActive ? $iconData['color'] : 'text-gray-400 dark:text-white/40' }}"></i>
                            <span class="text-sm">{{ ltrim($modTopic['name'], '#') }}</span>
                            <i class="{{ $iconData['icon'] }} text-xs {{ $iconData['color'] }} ml-auto opacity-60"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
            @endif

            @if(empty($topics) || count($topics ?? []) === 0)
                @if($isModerator)
                    <div class="px-2 mt-1">
                        <button type="button" data-open-chat-modal="create-channel"
                            onclick="setTimeout(() => window.closeMobileNav?.(), 100)"
                            class="w-full flex items-center justify-center gap-2 rounded-lg border-2 border-dashed border-gray-300 dark:border-white/20 bg-gray-50 dark:bg-white/5 px-3 py-2 text-xs font-medium text-gray-500 dark:text-white/50 hover:border-primary hover:text-primary hover:bg-primary/5 transition">
                            <i class="bi bi-pencil-square"></i>
                            <span>Update Rules</span>
                        </button>
                    </div>
                @endif
            @else
                {{-- Pending Join Requests (only for moderator) --}}
                @if($isModerator)
                    <div id="pending-requests-section" class="hidden px-2 mt-1">
                        <button type="button" data-open-chat-modal="join-requests"
                            onclick="setTimeout(() => window.closeMobileNav?.(), 100)"
                            class="w-full flex items-center justify-between rounded-lg border border-amber-300 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-900/20 px-3 py-2 text-xs font-medium text-amber-700 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/30 transition">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-person-plus-fill"></i>
                                <span>Pending Requests</span>
                            </div>
                            <span id="sidebar-pending-count" class="flex min-w-[1.2rem] items-center justify-center rounded-full bg-amber-500 px-1 py-0.5 text-[10px] font-bold text-white">0</span>
                        </button>
                    </div>
                @endif
            @endif

            {{-- Apps Section --}}
            <div class="sidebar-section" data-section="apps">
                <button type="button" class="sidebar-section-header w-full flex items-center justify-between px-2 py-1.5 rounded hover:bg-slate-100 dark:hover:bg-white/10 transition group" onclick="toggleSidebarSection('apps')">
                    <div class="flex items-center gap-1">
                        <i class="bi bi-chevron-down text-[10px] text-gray-400 dark:text-white/40 transition-transform section-chevron"></i>
                        <span class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50 group-hover:text-gray-700 dark:group-hover:text-white/70">Apps</span>
                    </div>
                </button>
                <div class="sidebar-section-content flex flex-col gap-0.5">
                    {{-- HillBot --}}
                    <button type="button" class="flex items-center gap-2 rounded px-2 py-1.5 text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 transition"
                        onclick="openBotChat()" title="Chat with HillBot">
                        <div class="relative flex-shrink-0">
                            <img src="https://api.dicebear.com/7.x/bottts/svg?seed=HillBot&backgroundColor=6366f1"
                                 alt="HillBot" class="size-6 rounded-lg">
                            <span class="absolute -bottom-0.5 -right-0.5 size-2 rounded-full border border-white dark:border-sidebar-dark bg-green-500"></span>
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <span class="text-sm font-medium">HillBot</span>
                            <span class="text-[10px] text-gray-400 dark:text-white/40 ml-1">AI Assistant</span>
                        </div>
                    </button>

                    {{-- Spin the Wheel --}}
                    <button type="button" class="flex items-center gap-2 rounded px-2 py-1.5 text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 transition"
                        onclick="openSpinWheel()" title="Spin the Wheel">
                        <div class="relative flex-shrink-0">
                            <div class="size-6 rounded-lg bg-gradient-to-br from-red-500 via-yellow-500 to-green-500 flex items-center justify-center">
                                <i class="bi bi-arrow-repeat text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <span class="text-sm font-medium">Spin Wheel</span>
                            <span class="text-[10px] text-gray-400 dark:text-white/40 ml-1">Random Picker</span>
                        </div>
                    </button>

                    {{-- Duck Race - Coming Soon --}}
                    {{-- <button type="button" class="flex items-center gap-2 rounded px-2 py-1.5 text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/5 transition"
                        onclick="showComingSoonToast()" title="Duck Race - Coming Soon">
                        <div class="relative flex-shrink-0">
                            <div class="size-6 rounded-lg bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-sm">
                                🦆
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <span class="text-sm font-medium">Duck Race</span>
                            <span class="text-[10px] text-gray-400 dark:text-white/40 ml-1">Coming Soon</span>
                        </div>
                    </button> --}}
                </div>
            </div>

        @else
            {{-- ===== STARRED SECTION (Collapsible) - My Tags ===== --}}
            <div class="sidebar-section" data-section="starred">
                <button type="button" class="sidebar-section-header w-full flex items-center justify-between px-2 py-1.5 rounded hover:bg-slate-100 dark:hover:bg-white/10 transition group" onclick="toggleSidebarSection('starred')">
                    <div class="flex items-center gap-1">
                        <i class="bi bi-chevron-down text-[10px] text-gray-400 dark:text-white/40 transition-transform section-chevron"></i>
                        <span class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50 group-hover:text-gray-700 dark:group-hover:text-white/70">Starred</span>
                    </div>
                    <button type="button" class="opacity-0 group-hover:opacity-100 rounded p-0.5 text-gray-400 dark:text-white/40 hover:text-primary hover:bg-primary/10 transition"
                        data-open-chat-modal="create-personal-tag" title="Create personal tag"
                        onclick="event.stopPropagation(); setTimeout(() => window.closeMobileNav?.(), 100)">
                        <i class="bi bi-plus text-sm"></i>
                    </button>
                </button>
                <div class="sidebar-section-content flex flex-col gap-0.5">
                    @forelse($personalTags ?? [] as $tag)
                        <a href="{{ route('chats.v2', ['personal_tag' => $tag['id']]) }}"
                            onclick="window.closeMobileNav?.()"
                            class="flex items-center gap-2 rounded px-2 py-1 text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 group">
                            <i class="bi bi-bookmark-fill text-sm" style="color: {{ $tag['color'] ?? '#6366f1' }}"></i>
                            <span class="text-sm">{{ $tag['name'] }}</span>
                            @if($tag['is_private'] ?? true)
                                <i class="bi bi-lock text-[10px] text-gray-400 dark:text-white/30 ml-auto"></i>
                            @endif
                        </a>
                    @empty
                        <p class="px-2 py-1 text-xs text-gray-400 dark:text-white/40">No starred items</p>
                    @endforelse

                    {{-- Frequent Friends in Starred --}}
                    @forelse($frequentFriends as $friend)
                        <button type="button" class="frequent-friend-btn flex items-center gap-2 rounded px-2 py-1 text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10"
                            data-user-id="{{ $friend['user_id'] ?? $friend['id'] ?? '' }}"
                            data-user-name="{{ $friend['name'] }}"
                            data-user-avatar="{{ $friend['avatar'] }}"
                            data-user-email="{{ $friend['email'] ?? '' }}"
                            data-dm-id="{{ $friend['id'] ?? '' }}">
                            <span class="relative">
                                <span class="size-2 rounded-full {{ $friend['status'] === 'online' ? 'bg-green-500' : ($friend['status'] === 'idle' ? 'bg-yellow-400' : 'bg-slate-400') }}"></span>
                            </span>
                            <span class="text-sm">{{ $friend['name'] }}</span>
                        </button>
                    @empty
                    @endforelse
                </div>
            </div>

            {{-- Frequent Friends Click Handler --}}
            <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.frequent-friend-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const dmId = btn.dataset.dmId;
                        if (dmId) {
                            const url = new URL(window.location.origin + window.location.pathname);
                            url.searchParams.set('conversation', dmId);
                            window.location = url.toString();
                        }
                    });
                });
            });
            </script>

            <div class="sidebar-section" data-section="dms">
                <button type="button" class="sidebar-section-header w-full flex items-center justify-between px-2 py-1.5 rounded hover:bg-slate-100 dark:hover:bg-white/10 transition group" onclick="toggleSidebarSection('dms')">
                    <div class="flex items-center gap-1">
                        <i class="bi bi-chevron-down text-[10px] text-gray-400 dark:text-white/40 transition-transform section-chevron"></i>
                        <span class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50 group-hover:text-gray-700 dark:group-hover:text-white/70">Direct messages</span>
                    </div>
                    @if($canCreateChats)
                        <button type="button" class="opacity-0 group-hover:opacity-100 rounded p-0.5 text-gray-400 dark:text-white/40 hover:text-primary hover:bg-primary/10 transition"
                            data-open-chat-modal="new-dm"
                            onclick="event.stopPropagation(); setTimeout(() => window.closeMobileNav?.(), 100)">
                            <i class="bi bi-plus text-sm"></i>
                        </button>
                    @endif
                </button>
                <div class="sidebar-section-content flex flex-col gap-0.5">
                    @forelse($directMessages as $dm)
                        <a href="{{ route('chats.v2', ['conversation' => $dm['id']]) }}"
                            onclick="window.closeMobileNav?.()"
                            data-user-id="{{ $dm['user_id'] ?? '' }}"
                            data-dm-conversation-id="{{ $dm['id'] }}"
                            class="flex items-center gap-2 rounded px-2 py-1 {{ $selectedConversation?->id === $dm['id'] ? 'bg-primary/20 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10' }}">
                            <span class="relative flex-shrink-0">
                                <span data-user-presence="{{ $dm['user_id'] ?? '' }}" class="size-2 rounded-full {{ $dm['status'] === 'online' ? 'bg-green-500' : ($dm['status'] === 'idle' ? 'bg-yellow-400' : ($dm['status'] === 'dnd' ? 'bg-red-500' : 'bg-slate-400')) }}"></span>
                            </span>
                            <span class="text-sm truncate flex-1">{{ $dm['name'] }}</span>
                            @if(($dm['unread'] ?? 0) > 0)
                                <span class="dm-unread-badge flex-shrink-0 flex min-w-[1rem] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold text-white">
                                    {{ $dm['unread'] > 99 ? '99+' : $dm['unread'] }}
                                </span>
                            @endif
                        </a>
                    @empty
                        <p class="px-2 py-1 text-xs text-gray-400 dark:text-white/40">
                            @if($isCandidate)
                                Waiting for assignment...
                            @else
                                No conversations yet
                            @endif
                        </p>
                    @endforelse
                </div>
            </div>

        @endif
    </div>

    {{-- User Footer --}}
    @php
        $userFallbackAvatar = 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($user->name . $user->id) . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981';
    @endphp
    <div class="chat-v2-footer flex items-center gap-3 rounded-lg p-2 transition-colors duration-200 user-profile-trigger cursor-pointer hover:bg-slate-100 dark:hover:bg-white/10 mt-2" 
         data-user-id="{{ $user->id }}"
         data-user-name="{{ $user->name }}"
         data-user-avatar="{{ $user->profile_photo_url ?? $userFallbackAvatar }}"
         data-user-role="{{ $user->role ?? 'applicant' }}">
        <div class="relative">
            <img src="{{ $user->profile_photo_url ?? $userFallbackAvatar }}"
                class="size-9 rounded-full object-cover" alt="{{ $user->name }}"
                onerror="this.onerror=null; this.src='{{ $userFallbackAvatar }}';">
            <span class="absolute -bottom-0.5 -right-0.5 size-3 rounded-full border-2 border-inherit bg-green-500"></span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="chat-v2-text text-sm font-semibold truncate">{{ $user->name }}</p>
            <p class="chat-v2-text-muted text-[11px]">
                @if($isCandidate)
                    Applicant
                @elseif($isModerator)
                    Moderator
                @else
                    Online
                @endif
            </p>
        </div>
        <button class="theme-toggle rounded p-1.5 hover:bg-primary/20" onclick="event.stopPropagation(); toggleChatTheme()" title="Toggle theme" aria-label="Toggle theme">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true"
                width="1.25em"
                height="1.25em"
                fill="currentColor"
                class="theme-toggle__expand"
                viewBox="0 0 32 32"
            >
                <defs>
                    <clipPath id="theme-toggle__expand__cutout">
                        <path d="M0-11h25a1 1 0 0017 13v30H0Z" />
                    </clipPath>
                </defs>
                <g clip-path="url(#theme-toggle__expand__cutout)">
                    <circle cx="16" cy="16" r="8.4" />
                    <path d="M18.3 3.2c0 1.3-1 2.3-2.3 2.3s-2.3-1-2.3-2.3S14.7.9 16 .9s2.3 1 2.3 2.3zm-4.6 25.6c0-1.3 1-2.3 2.3-2.3s2.3 1 2.3 2.3-1 2.3-2.3 2.3-2.3-1-2.3-2.3zm15.1-10.5c-1.3 0-2.3-1-2.3-2.3s1-2.3 2.3-2.3 2.3 1 2.3 2.3-1 2.3-2.3 2.3zM3.2 13.7c1.3 0 2.3 1 2.3 2.3s-1 2.3-2.3 2.3S.9 17.3.9 16s1-2.3 2.3-2.3zm5.8-7C9 7.9 7.9 9 6.7 9S4.4 8 4.4 6.7s1-2.3 2.3-2.3S9 5.4 9 6.7zm16.3 21c-1.3 0-2.3-1-2.3-2.3s1-2.3 2.3-2.3 2.3 1 2.3 2.3-1 2.3-2.3 2.3zm2.4-21c0 1.3-1 2.3-2.3 2.3S23 7.9 23 6.7s1-2.3 2.3-2.3 2.4 1 2.4 2.3zM6.7 23C8 23 9 24 9 25.3s-1 2.3-2.3 2.3-2.3-1-2.3-2.3 1-2.3 2.3-2.3z" />
                </g>
            </svg>
        </button>
        <button class="rounded p-1.5 hover:bg-primary/20 text-gray-500 dark:text-white/60" onclick="event.stopPropagation(); startChatTour()" title="Take a Tour" aria-label="Help Tour">
            <i class="bi bi-question-circle text-base"></i>
        </button>
    </div>
</div>

{{-- Collapsible Section Script --}}
<script>
function toggleSidebarSection(sectionName) {
    const section = document.querySelector(`.sidebar-section[data-section="${sectionName}"]`);
    if (!section) return;

    const content = section.querySelector('.sidebar-section-content');
    const chevron = section.querySelector('.section-chevron');

    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        chevron.style.transform = 'rotate(0deg)';
        localStorage.setItem(`sidebar-section-${sectionName}`, 'open');
    } else {
        content.classList.add('hidden');
        chevron.style.transform = 'rotate(-90deg)';
        localStorage.setItem(`sidebar-section-${sectionName}`, 'closed');
    }
}

// Restore section states on load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.sidebar-section').forEach(section => {
        const sectionName = section.dataset.section;
        const state = localStorage.getItem(`sidebar-section-${sectionName}`);
        const content = section.querySelector('.sidebar-section-content');
        const chevron = section.querySelector('.section-chevron');

        if (state === 'closed') {
            content?.classList.add('hidden');
            if (chevron) chevron.style.transform = 'rotate(-90deg)';
        }
    });
});

// Open Bot Chat
function openBotChat() {
    // Show toast that bot chat is coming soon
    if (typeof showChatToast === 'function') {
        showChatToast('HillBot is coming soon!', 'info');
    } else {
        alert('HillBot is coming soon!');
    }
}

// Show Coming Soon Toast
function showComingSoonToast() {
    if (typeof showChatToast === 'function') {
        showChatToast('🦆 Duck Race is coming soon!', 'info');
    } else {
        alert('Duck Race is coming soon!');
    }
}

@if($isGroup && $isModerator)
function unarchiveChannel(topicId, topicName) {
    Swal.fire({
        title: 'Restore #' + topicName.replace('#', '') + '?',
        text: 'This channel will be visible to all members again.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        confirmButtonText: 'Restore'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/chats/manage/{{ $selectedConversation->id }}/channels/${topicId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ is_archived: false })
            }).then(r => {
                if (r.ok) {
                    window.location.reload();
                } else {
                    window.showToast?.('Failed to restore channel', 'error');
                }
            });
        }
    });
}
@endif

// Sidebar unmute user function
async function sidebarUnmuteUser(userId, userName, btnEl = null) {
    const urlParams = new URLSearchParams(window.location.search);
    const conversationId = urlParams.get('conversation');

    if (!conversationId) {
        if (typeof showChatToast === 'function') showChatToast('No conversation selected', 'error');
        return;
    }

    // Show loading state
    const itemEl = btnEl?.closest('.sidebar-muted-item');
    const iconEl = itemEl?.querySelector('.sidebar-mute-icon');
    const originalIcon = iconEl?.className;

    if (btnEl) {
        btnEl.disabled = true;
        btnEl.innerHTML = '<i class="bi bi-arrow-repeat animate-spin text-xs"></i>';
        btnEl.classList.add('opacity-100');
    }
    if (iconEl) {
        iconEl.className = 'bi bi-arrow-repeat animate-spin text-gray-400 text-sm sidebar-mute-icon';
    }
    if (itemEl) {
        itemEl.classList.add('opacity-50', 'pointer-events-none');
    }

    try {
        const response = await fetch(`/chats/manage/${conversationId}/members/${userId}/unmute`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (response.ok) {
            if (typeof showChatToast === 'function') showChatToast(`${userName} has been unmuted`, 'success');
            if (typeof updateMemberMuteState === 'function') {
                updateMemberMuteState(userId, false, null, userName);
            }
        } else {
            const data = await response.json();
            throw new Error(data.message || 'Failed to unmute user');
        }
    } catch (error) {
        console.error('Unmute error:', error);
        if (typeof showChatToast === 'function') showChatToast(error.message || 'Failed to unmute user', 'error');

        // Restore original state on error
        if (btnEl) {
            btnEl.disabled = false;
            btnEl.innerHTML = '<i class="bi bi-volume-up-fill text-xs"></i>';
            btnEl.classList.remove('opacity-100');
        }
        if (iconEl && originalIcon) {
            iconEl.className = originalIcon;
        }
        if (itemEl) {
            itemEl.classList.remove('opacity-50', 'pointer-events-none');
        }
    }
}

window.sidebarUnmuteUser = sidebarUnmuteUser;
</script>
