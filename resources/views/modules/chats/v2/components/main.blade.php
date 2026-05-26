{{-- Chat background pattern --}}
<style>
    .chat-bg {
        background-color: #ffffff;
        background-image:
            radial-gradient(circle, rgba(0, 0, 0, 0.04) 1px, transparent 1px),
            radial-gradient(circle, rgba(0, 0, 0, 0.02) 1.2px, transparent 1px),
            url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'><g fill='none' stroke='%23e5e5e5' stroke-width='1' opacity='0.5' stroke-linecap='round' stroke-linejoin='round'><g transform='rotate(-15 40 40)'><rect x='18' y='18' width='46' height='26' rx='8'/><path d='M24 44l-5 7'/></g><g transform='rotate(12 116 46)'><path d='M120 32c6 6 10 14 10 20 0 3-1 6-3 8l-3 3c-2 2-5 3-8 3-6 0-12-5-18-10'/><path d='M114 30l5-3'/><path d='M126 60l-3 5'/></g><g transform='rotate(-10 60 120)'><rect x='34' y='104' width='48' height='24' rx='8'/><path d='M42 126l-4 6'/></g></g></svg>");
        background-size: 26px 26px, 32px 32px, 220px 220px;
        background-repeat: repeat;
        background-position: center center;
        opacity: 1;
    }
    .dark .chat-bg {
        background-color: #222529;
        background-image:
            radial-gradient(circle, rgba(255, 255, 255, 0.02) 1px, transparent 1px),
            radial-gradient(circle, rgba(255, 255, 255, 0.01) 1.2px, transparent 1px),
            url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'><g fill='none' stroke='%23383838' stroke-width='1' opacity='0.4' stroke-linecap='round' stroke-linejoin='round'><g transform='rotate(-15 40 40)'><rect x='18' y='18' width='46' height='26' rx='8'/><path d='M24 44l-5 7'/></g><g transform='rotate(12 116 46)'><path d='M120 32c6 6 10 14 10 20 0 3-1 6-3 8l-3 3c-2 2-5 3-8 3-6 0-12-5-18-10'/><path d='M114 30l5-3'/><path d='M126 60l-3 5'/></g><g transform='rotate(-10 60 120)'><rect x='34' y='104' width='48' height='24' rx='8'/><path d='M42 126l-4 6'/></g></g></svg>");
        background-size: 26px 26px, 32px 32px, 220px 220px;
        background-repeat: repeat;
        background-position: center center;
    }
    
    /* Disable hover effects on other members when dropdown is open */
    .chat-v2-right.dropdown-open .member-item:not(.dropdown-active) {
        pointer-events: none;
        opacity: 0.5;
    }
    .chat-v2-right.dropdown-open .member-item.dropdown-active {
        pointer-events: auto;
        opacity: 1;
    }
</style>

{{-- Main chat area --}}
<div class="chat-v2-main flex flex-1 flex-col transition-colors duration-200 bg-chat-light dark:bg-chat-dark">
    @if($filter ?? null)
        {{-- Filter View (Unread, Threads, Mentions, Drafts) --}}
        @include('modules.chats.v2.components.shared.filter-view', [
            'filter' => $filter,
            'filterData' => $filterData,
        ])
    @elseif($selectedPersonalTag ?? null)
        {{-- Personal Tag View --}}
        <div class="chat-v2-header flex items-center justify-between border-b px-4 py-3 shadow-md transition-colors duration-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background-color: {{ $selectedPersonalTag->color }}20;">
                    <i class="bi bi-bookmark-fill text-xl" style="color: {{ $selectedPersonalTag->color }};"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $selectedPersonalTag->name }}</h2>
                    <p class="text-xs text-gray-500 dark:text-white/50">
                        Personal Tag · {{ ($personalTagMessages ?? collect())->count() }} notes
                        @if($selectedPersonalTag->is_private)
                            <i class="bi bi-lock ml-1"></i>
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <button class="rounded-lg p-2 text-gray-600 dark:text-white/80 hover:bg-primary/20 transition" title="Add note">
                    <i class="bi bi-plus-lg text-xl"></i>
                </button>
                <button class="rounded-lg p-2 text-red-400 hover:bg-red-500/20 transition" title="Delete tag">
                    <i class="bi bi-trash text-xl"></i>
                </button>
            </div>
        </div>

        <div class="flex flex-1 overflow-hidden">
            <div class="relative flex flex-1 flex-col overflow-hidden">
                <div class="absolute inset-0 chat-bg pointer-events-none opacity-70"></div>
                
                {{-- Personal Tag Messages --}}
                <div class="flex-1 overflow-y-auto p-4 relative z-10">
                    @forelse($personalTagMessages ?? [] as $note)
                        <div class="mb-3 p-4 rounded-xl bg-white dark:bg-sidebar-dark border border-gray-200 dark:border-white/10 shadow-sm">
                            @if($note['forwarded_metadata'])
                                @php $meta = is_array($note['forwarded_metadata']) ? $note['forwarded_metadata'] : json_decode($note['forwarded_metadata'], true); @endphp
                                <div class="flex items-center gap-2 mb-2 pb-2 border-b border-gray-100 dark:border-white/10">
                                    <i class="bi bi-forward-fill text-primary text-sm"></i>
                                    <span class="text-xs font-medium text-gray-600 dark:text-white/60">
                                        Forwarded from <span class="text-gray-800 dark:text-white">{{ $meta['original_sender'] ?? 'Unknown' }}</span>
                                    </span>
                                </div>
                            @endif
                            <p class="text-sm text-gray-700 dark:text-white/90 whitespace-pre-line">{{ $note['body'] }}</p>
                            <p class="text-xs text-gray-400 dark:text-white/40 mt-2">
                                {{ \Carbon\Carbon::parse($note['created_at'])->diffForHumans() }}
                            </p>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-center">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background-color: {{ $selectedPersonalTag->color }}20;">
                                <i class="bi bi-bookmark text-3xl" style="color: {{ $selectedPersonalTag->color }};"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-1">No notes yet</h3>
                            <p class="text-sm text-gray-500 dark:text-white/50 max-w-xs">
                                Forward messages here or add notes to keep track of important information.
                            </p>
                        </div>
                    @endforelse
                </div>

                {{-- Note Composer --}}
                <div class="chat-v2-composer relative z-10 border-t px-3 md:px-4 pb-4 pt-3 backdrop-blur transition-colors duration-200">
                    <form id="personal-tag-note-form" class="relative" data-tag-id="{{ $selectedPersonalTag->id }}">
                        @csrf
                        <input type="text" id="personal-tag-note-input" name="body" 
                            placeholder="Add a note to {{ $selectedPersonalTag->name }}..."
                            class="chat-v2-input w-full rounded-xl md:rounded-lg border-none py-3 pl-4 pr-24 text-base placeholder-gray-400 focus:ring-2 focus:ring-primary transition-colors duration-200"
                            autocomplete="off" />
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-primary transition">
                            <i class="bi bi-send-fill text-xl"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @elseif(!$selectedConversation)
        {{-- No Conversation Selected - Show Welcome/Todo View --}}
        <div class="flex flex-1 flex-col">
            {{-- Header --}}
            <div class="chat-v2-header flex items-center justify-between border-b px-4 py-3 shadow-md transition-colors duration-200">
                <div class="flex items-center gap-2">
                    {{-- Mobile menu button --}}
                    <button type="button" onclick="window.openMobileNav?.()" class="md:hidden p-2 -ml-2 rounded-lg text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 transition">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <i class="bi bi-chat-square-text text-2xl text-primary"></i>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Welcome to Chat</h2>
                        <p class="text-xs text-gray-500 dark:text-white/50 hidden sm:block">Select a conversation to start messaging</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-1 overflow-hidden">
                <div class="relative flex flex-1 flex-col overflow-hidden">
                    <div class="absolute inset-0 chat-bg pointer-events-none opacity-70"></div>
                    
                    <div class="flex-1 overflow-y-auto p-6 relative z-10 flex items-center justify-center">
                        {{-- Welcome Section --}}
                        @php
                            $userRole = auth()->user()->role ?? 'applicant';
                            $isRegularUser = in_array($userRole, ['applicant', 'employer']);
                        @endphp
                        <div class="max-w-2xl mx-auto">
                            <div class="text-center">
                                <div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-chat-dots text-4xl text-primary"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">No chat selected</h3>
                                <p class="text-gray-500 dark:text-white/60">Choose a conversation from the sidebar or start a new one</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Regular Conversation View --}}
        {{-- Mobile Header --}}
        @include('modules.chats.v2.components.mobile.header', [
            'selectedConversation' => $selectedConversation,
            'canDeleteGroup' => $canDeleteGroup ?? false,
            'deleteGroupUrl' => $deleteGroupUrl ?? '',
        ])

        {{-- Desktop Header --}}
        @include('modules.chats.v2.components.desktop.header', [
            'selectedConversation' => $selectedConversation,
            'canDeleteGroup' => $canDeleteGroup ?? false,
            'deleteGroupUrl' => $deleteGroupUrl ?? '',
        ])

        <div class="flex flex-1 overflow-hidden">
            {{-- Messages + Composer --}}
            <div class="relative flex flex-1 flex-col overflow-hidden">
                {{-- Background pattern --}}
                <div class="absolute inset-0 chat-bg pointer-events-none opacity-70"></div>
                
                {{-- Pinned Message Bar (Telegram-style with navigation) --}}
                <div id="pinned-message-bar" class="relative z-20 {{ ($pinnedMessage ?? null) ? '' : 'hidden' }}">
                    <div class="w-full flex items-center gap-3 px-4 py-2.5 bg-white/95 dark:bg-sidebar-dark/95 backdrop-blur-sm border-b border-gray-200 dark:border-white/10">
                        <button type="button" onclick="cyclePinnedMessage()" class="flex-1 flex items-center gap-3 hover:bg-gray-50 dark:hover:bg-white/10 -m-2 p-2 rounded-lg transition group text-left">
                            <div class="flex-shrink-0 w-1 h-8 rounded-full bg-primary relative overflow-hidden">
                                <div id="pinned-progress-bar" class="absolute bottom-0 left-0 right-0 bg-primary/40 transition-all" style="height: 100%;"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-primary flex items-center gap-1">
                                    <i class="bi bi-pin-angle-fill"></i>
                                    <span id="pinned-message-label">Pinned Message</span>
                                    <span id="pinned-message-counter" class="text-gray-400 dark:text-white/40 font-normal ml-1"></span>
                                </p>
                                <p id="pinned-message-text" class="text-sm text-gray-700 dark:text-white truncate">
                                    {{ ($pinnedMessage['body'] ?? '') ? \Illuminate\Support\Str::limit(strip_tags($pinnedMessage['body']), 60) : '' }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 text-xs text-gray-400 dark:text-white/40 group-hover:text-gray-600 dark:group-hover:text-white/60">
                                <span id="pinned-message-author">{{ $pinnedMessage['user_name'] ?? '' }}</span>
                            </div>
                        </button>
                        <button type="button" onclick="openPinnedMessagesPanel()" class="flex-shrink-0 p-2 rounded-lg text-gray-400 hover:text-primary hover:bg-slate-100 dark:hover:bg-white/10 transition" title="View all pinned">
                            <i class="bi bi-list-ul text-lg"></i>
                        </button>
                    </div>
                </div>
                <input type="hidden" id="pinned-message-id" value="{{ $pinnedMessage['id'] ?? '' }}">
                
                {{-- Pinned Messages Panel (slide-down list) --}}
                <div id="pinned-messages-panel" class="hidden absolute top-0 left-0 right-0 z-30 bg-white dark:bg-sidebar-dark border-b border-gray-200 dark:border-white/10 shadow-lg max-h-80 overflow-y-auto">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-white/10 sticky top-0 bg-white dark:bg-sidebar-dark">
                        <h3 class="font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="bi bi-pin-angle-fill text-primary"></i>
                            Pinned Messages
                            <span id="pinned-panel-count" class="text-xs font-normal text-gray-400 dark:text-white/40"></span>
                        </h3>
                        <button type="button" onclick="closePinnedMessagesPanel()" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div id="pinned-messages-list" class="divide-y divide-gray-100 dark:divide-white/5"></div>
                </div>
                
                {{-- Messages (scrollable) --}}
                <div id="messages-scroll-container" class="flex-1 overflow-y-auto relative">
                    {{-- Skeleton Loader --}}
                    <div id="messages-skeleton" class="p-4 space-y-6">
                        @for($i = 0; $i < 8; $i++)
                        <div class="flex gap-3 {{ $i % 3 === 0 ? 'pl-0' : '' }}">
                            <div class="w-10 h-10 rounded-full flex-shrink-0 bg-white/10 animate-pulse"></div>
                            <div class="flex-1 space-y-2 pt-1">
                                <div class="flex items-center gap-3">
                                    <div class="h-3.5 rounded bg-white/10 animate-pulse" style="width: {{ rand(60, 100) }}px;"></div>
                                    <div class="h-3 w-12 rounded bg-white/5 animate-pulse"></div>
                                </div>
                                <div class="space-y-1.5">
                                    <div class="h-3.5 rounded bg-white/10 animate-pulse" style="width: {{ rand(40, 85) }}%;"></div>
                                    @if($i % 2 === 0)
                                    <div class="h-3.5 rounded bg-white/10 animate-pulse" style="width: {{ rand(20, 50) }}%;"></div>
                                    @endif
                                    @if($i % 4 === 0)
                                    <div class="h-32 w-48 rounded-lg bg-white/10 animate-pulse mt-2"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                    
                    {{-- Actual messages (hidden until loaded) --}}
                    <div id="messages-content" class="hidden">
                        @include('modules.chats.v2.components.shared.chat-messages', [
                            'messages' => $messages,
                            'lastReadMessageId' => $lastReadMessageId ?? null,
                        ])
                    </div>
                </div>
                
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const skeleton = document.getElementById('messages-skeleton');
                    const content = document.getElementById('messages-content');
                    const scrollContainer = document.getElementById('messages-scroll-container');
                    
                    if (skeleton && content && scrollContainer) {
                        setTimeout(() => {
                            skeleton.classList.add('hidden');
                            content.classList.remove('hidden');
                            scrollContainer.scrollTop = scrollContainer.scrollHeight;
                        }, 150);
                    }
                });
                </script>

                {{-- New Messages Indicator (Messenger-style) --}}
                <style>
                    @keyframes bounce-subtle {
                        0%, 100% { transform: translateX(-50%) translateY(0); }
                        50% { transform: translateX(-50%) translateY(-4px); }
                    }
                    .animate-bounce-subtle {
                        animation: bounce-subtle 1.5s ease-in-out infinite;
                    }
                </style>
                <div id="new-messages-indicator" class="absolute left-1/2 -translate-x-1/2 bottom-24 z-30 hidden">
                    <button type="button" id="scroll-to-bottom-btn" 
                        class="flex items-center gap-2 px-4 py-2.5 rounded-full bg-primary text-white text-sm font-medium shadow-lg hover:bg-primary/90 transition-all animate-bounce-subtle cursor-pointer">
                        <i class="bi bi-arrow-down"></i>
                        <span id="new-messages-text">New messages</span>
                    </button>
                </div>

                {{-- Typing Indicator --}}
                <div id="typing-indicator" class="hidden items-center gap-2 px-4 py-2 text-sm text-gray-500 dark:text-white/50 relative z-10" style="display: none;">
                    <div class="flex gap-1">
                        <span class="w-2 h-2 bg-indigo-400 dark:bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-2 h-2 bg-indigo-400 dark:bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-2 h-2 bg-indigo-400 dark:bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                    <span id="typing-indicator-text" class="font-medium text-gray-600 dark:text-white/70">Someone is typing...</span>
                </div>

                {{-- Composer (fixed at bottom) --}}
                @include('modules.chats.v2.components.shared.chat-composer', [
                    'selectedConversation' => $selectedConversation,
                ])
            </div>

            {{-- Right column (online list) - Desktop only --}}
            @include('modules.chats.v2.components.shared.right-sidebar')
            <div class="chat-v2-right hidden md:flex w-64 flex-shrink-0 flex-col p-4 transition-colors duration-200 overflow-y-auto border-l border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark">
            {{-- Bot Section (if enabled) --}}
            @if($selectedConversation?->settings['bot_enabled'] ?? false)
                <h3 class="mb-3 text-xs font-bold uppercase text-gray-500 dark:text-white/50">
                    <i class="bi bi-cpu mr-1"></i> Bot
                </h3>
                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-200 dark:border-white/10">
                    <div class="relative">
                        <img src="https://api.dicebear.com/7.x/bottts/svg?seed={{ $selectedConversation->settings['bot_name'] ?? 'HillBot' }}&backgroundColor=6366f1" 
                             alt="{{ $selectedConversation->settings['bot_name'] ?? 'HillBot' }}" 
                             class="size-10 rounded-full">
                        <span class="absolute -bottom-0.5 -right-0.5 size-3 rounded-full border-2 border-sidebar-light dark:border-sidebar-dark bg-green-500"></span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $selectedConversation->settings['bot_name'] ?? 'HillBot' }}</p>
                        <p class="text-xs text-gray-500 dark:text-white/50">{{ ucfirst($selectedConversation->settings['bot_role'] ?? 'Moderator') }}</p>
                    </div>
                </div>
            @endif

            <h3 id="tour-online-members" class="mb-4 text-xs font-bold uppercase text-gray-500 dark:text-white/50 flex items-center justify-between">
                <span>Online - <span id="online-count">{{ $rightColumn['online']->count() }}</span></span>
                @if(auth()->user()->isModerator())
                    <button type="button" data-open-chat-modal="add-member-quick" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-white/10 transition" title="Add member">
                        <i class="bi bi-plus-lg text-primary"></i>
                    </button>
                @endif
            </h3>
            <div id="online-members-list" class="flex flex-col gap-1">
                @forelse($rightColumn['online'] as $member)
                    @php
                        $isSystemModerator = $member['is_system_moderator'] ?? false;
                        $memberSystemRole = $member['system_role'] ?? null;
                        $isStaff = $isSystemModerator || in_array($memberSystemRole, ['admin', 'super_admin']);
                        $staffBadgeTitle = match($memberSystemRole) {
                            'super_admin' => 'Super Admin',
                            'admin' => 'Admin',
                            default => 'Moderator'
                        };
                        $participantRole = $member['role'] ?? 'member';
                        $memberId = $member['user_id'] ?? $member['id'] ?? '';
                        $isOwnUser = (int)$memberId === (int)auth()->id();
                        $isStatusHidden = ($member['status'] ?? '') === 'invisible';
                    @endphp
                    <div class="member-item group relative flex items-center gap-3 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-white/10 transition" data-user-id="{{ $memberId }}" data-conversation-id="{{ $member['current_conversation_id'] ?? '' }}" data-topic-slug="{{ $member['current_topic_slug'] ?? '' }}" data-system-role="{{ $memberSystemRole }}">
                        <div class="relative">
                            <img src="{{ $member['avatar'] }}" class="size-10 rounded-full" alt="{{ $member['name'] }}">
                            <span data-user-presence="{{ $memberId }}" class="absolute -bottom-0.5 -right-0.5 size-3 rounded-full border-2 border-sidebar-light dark:border-sidebar-dark {{ $member['status'] === 'online' ? 'bg-green-500' : ($member['status'] === 'idle' ? 'bg-yellow-400' : 'bg-red-500') }}"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <p class="text-sm font-medium text-gray-800 dark:text-white truncate">{{ $member['name'] }}</p>
                                @if($isStaff)
                                    <i class="bi bi-patch-check-fill text-[10px] text-blue-500" title="{{ $staffBadgeTitle }}"></i>
                                @elseif($participantRole === 'admin')
                                    <i class="bi bi-shield-fill text-[10px] text-amber-500" title="Admin"></i>
                                @endif
                                @if($isOwnUser && $isStatusHidden)
                                    <span class="status-hidden-indicator inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[9px] font-medium bg-gray-500/20 text-gray-400 dark:text-white/50">
                                        <i class="bi bi-eye-slash text-[8px]"></i> Hidden
                                    </span>
                                @endif
                            </div>
                            <p class="member-status text-xs text-gray-500 dark:text-white/60">{{ ucfirst($member['status'] === 'invisible' ? 'online' : $member['status']) }}</p>
                            @if(auth()->user()->isModerator() && !$isOwnUser && ($member['current_conversation_id'] ?? null))
                                @php
                                    $memberConvId = $member['current_conversation_id'];
                                    $memberTopicSlug = $member['current_topic_slug'] ?? null;
                                    $isInCurrentChannel = $memberConvId == $selectedConversation->id && $memberTopicSlug == request()->query('topic');
                                @endphp
                                <div class="member-location-badge mt-0.5">
                                    @if($isInCurrentChannel)
                                        <span class="inline-flex items-center gap-1 text-[10px] text-green-500 dark:text-green-400">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <span>Active here</span>
                                        </span>
                                    @else
                                        <a href="{{ route('chats.v2', ['conversation' => $memberConvId, 'topic' => $memberTopicSlug]) }}" 
                                           class="inline-flex items-center gap-1 text-[10px] text-primary hover:underline cursor-pointer"
                                           title="Go to #{{ $memberTopicSlug ?: 'general' }}">
                                            <i class="bi bi-hash"></i>
                                            <span>{{ $memberTopicSlug ?: 'general' }}</span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        {{-- Own user dropdown (moderators only) --}}
                        @if(auth()->user()->isModerator() && $isOwnUser)
                            <button type="button" class="own-status-btn hidden group-hover:flex items-center justify-center size-7 rounded-full bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-600 dark:text-white/70 transition" title="Status options">
                                <i class="bi bi-three-dots-vertical text-sm"></i>
                            </button>
                            <div class="own-status-dropdown hidden absolute right-2 top-full mt-1 w-40 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-xl z-[10000] py-1">
                                <button type="button" class="toggle-status-visibility w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition" data-hidden="{{ $isStatusHidden ? 'true' : 'false' }}">
                                    <i class="bi {{ $isStatusHidden ? 'bi-eye' : 'bi-eye-slash' }} text-gray-500 dark:text-white/60"></i>
                                    <span>{{ $isStatusHidden ? 'Show Status' : 'Hide Status' }}</span>
                                </button>
                            </div>
                        @endif
                        
                        {{-- Other user dropdown (moderators only) --}}
                        @if(auth()->user()->isModerator() && !$isOwnUser && !$isSystemModerator)
                            <button type="button" class="member-action-btn hidden group-hover:flex items-center justify-center size-7 rounded-full bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-600 dark:text-white/70 transition" title="Actions">
                                <i class="bi bi-three-dots-vertical text-sm"></i>
                            </button>
                               <div class="member-action-dropdown hidden absolute right-2 top-full mt-1 w-36 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-xl z-[10000] py-1"
                                 data-user-id="{{ $memberId }}"
                                 data-user-name="{{ $member['name'] }}"
                                 data-conversation-id="{{ $selectedConversation->id }}">
                                <button type="button" class="sidebar-mod-action w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition" data-action="mute">
                                    <i class="bi bi-mic-mute text-amber-500"></i>
                                    <span>Mute</span>
                                </button>
                                <button type="button" class="sidebar-mod-action w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition" data-action="kick">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Kick</span>
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-gray-500 dark:text-white/50 px-2">No one is online right now.</p>
                @endforelse
            </div>

            <h3 id="offline-count-header" class="mb-4 mt-6 text-xs font-bold uppercase text-gray-500 dark:text-white/50">
                Offline - <span id="offline-count">{{ $rightColumn['offline']->count() }}</span>
            </h3>
            <div id="offline-members-list" class="flex flex-col gap-1">
                @forelse($rightColumn['offline'] as $member)
                    @php
                        $isSystemModerator = $member['is_system_moderator'] ?? false;
                        $memberSystemRole = $member['system_role'] ?? null;
                        $isStaff = $isSystemModerator || in_array($memberSystemRole, ['admin', 'super_admin']);
                        $staffBadgeTitle = match($memberSystemRole) {
                            'super_admin' => 'Super Admin',
                            'admin' => 'Admin',
                            default => 'Moderator'
                        };
                        $participantRole = $member['role'] ?? 'member';
                        $memberId = $member['user_id'] ?? $member['id'] ?? '';
                        $isOwnUser = (int)$memberId === (int)auth()->id();
                    @endphp
                    <div class="member-item group relative flex items-center gap-3 p-2 rounded-lg opacity-60 hover:opacity-100 hover:bg-slate-100 dark:hover:bg-white/10 transition" data-user-id="{{ $memberId }}" data-system-role="{{ $memberSystemRole }}">
                        <div class="relative">
                            <img src="{{ $member['avatar'] }}" class="size-10 rounded-full" alt="{{ $member['name'] }}">
                            <span data-user-presence="{{ $memberId }}" class="absolute -bottom-0.5 -right-0.5 size-3 rounded-full border-2 border-sidebar-light dark:border-sidebar-dark bg-slate-500"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5">
                                <p class="text-sm font-medium text-gray-800 dark:text-white truncate">{{ $member['name'] }}</p>
                                @if($isStaff)
                                    <i class="bi bi-patch-check-fill text-[10px] text-blue-500/50" title="{{ $staffBadgeTitle }}"></i>
                                @elseif($participantRole === 'admin')
                                    <i class="bi bi-shield-fill text-[10px] text-amber-500/50" title="Admin"></i>
                                @endif
                            </div>
                        </div>
                        @if(auth()->user()->isModerator() && !$isOwnUser && !$isStaff)
                            <button type="button" class="member-action-btn hidden group-hover:flex items-center justify-center size-7 rounded-full bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-600 dark:text-white/70 transition" title="Actions">
                                <i class="bi bi-three-dots-vertical text-sm"></i>
                            </button>
                            <div class="member-action-dropdown hidden absolute right-2 top-full mt-1 w-36 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-xl z-[10000] py-1"
                                 data-user-id="{{ $memberId }}"
                                 data-user-name="{{ $member['name'] }}"
                                 data-conversation-id="{{ $selectedConversation->id }}">
                                <button type="button" class="sidebar-mod-action w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition" data-action="mute">
                                    <i class="bi bi-mic-mute text-amber-500"></i>
                                    <span>Mute</span>
                                </button>
                                <button type="button" class="sidebar-mod-action w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition" data-action="kick">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Kick</span>
                                </button>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-gray-500 dark:text-white/50 px-2">Everyone seems to be online!</p>
                @endforelse
            </div>

            {{-- Muted Users Section (Moderator Only) --}}
            @if(auth()->user()->isModerator())
            @php
                $mutedMembers = $rightColumn['muted'] ?? collect();
            @endphp
            <div id="muted-section" class="{{ $mutedMembers->count() === 0 ? 'hidden' : '' }}">
                <h3 id="muted-count-header" class="mb-4 mt-6 text-xs font-bold uppercase text-red-500 flex items-center gap-2">
                    <i class="bi bi-mic-mute-fill"></i>
                    <span>Muted - <span id="muted-count">{{ $mutedMembers->count() }}</span></span>
                </h3>
                <div id="muted-members-list" class="flex flex-col gap-1">
                    @forelse($mutedMembers as $member)
                        @php
                            $memberId = $member['user_id'] ?? $member['id'] ?? '';
                            $mutedUntil = $member['muted_until'] ?? null;
                            $isSystemModerator = $member['is_system_moderator'] ?? false;
                            $memberSystemRole = $member['system_role'] ?? null;
                            $isStaff = $isSystemModerator || in_array($memberSystemRole, ['admin', 'super_admin']);
                            $staffBadgeTitle = match($memberSystemRole) {
                                'super_admin' => 'Super Admin',
                                'admin' => 'Admin',
                                default => 'Moderator'
                            };
                        @endphp
                        <div class="muted-member-item group relative flex items-center gap-3 p-2 rounded-lg opacity-60 hover:opacity-100 hover:bg-slate-100 dark:hover:bg-white/10 transition" data-user-id="{{ $memberId }}" data-system-role="{{ $memberSystemRole }}">
                            <div class="relative">
                                <img src="{{ $member['avatar'] }}" class="size-10 rounded-full" alt="{{ $member['name'] }}">
                                <span class="absolute -bottom-0.5 -right-0.5 size-3 rounded-full border-2 border-sidebar-light dark:border-sidebar-dark bg-red-500 flex items-center justify-center">
                                    <i class="bi bi-mic-mute-fill text-[6px] text-white"></i>
                                </span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white truncate">{{ $member['name'] }}</p>
                                    @if($isStaff)
                                        <i class="bi bi-patch-check-fill text-[10px] text-blue-500/50" title="{{ $staffBadgeTitle }}"></i>
                                    @endif
                                </div>
                                <p class="text-xs text-red-500 dark:text-red-400">
                                    @if($mutedUntil)
                                        Until {{ \Carbon\Carbon::parse($mutedUntil)->format('M d, H:i') }}
                                    @else
                                        Indefinitely
                                    @endif
                                </p>
                            </div>
                            <button type="button" class="unmute-btn hidden group-hover:flex items-center justify-center size-7 rounded-full bg-gray-100 dark:bg-white/5 hover:bg-green-200 dark:hover:bg-green-500/20 text-gray-600 dark:text-white/70 hover:text-green-600 dark:hover:text-green-400 transition" 
                                data-user-id="{{ $memberId }}" 
                                data-user-name="{{ $member['name'] }}"
                                title="Unmute user">
                                <i class="bi bi-volume-up-fill text-sm"></i>
                            </button>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 dark:text-white/50 px-2">No muted users</p>
                    @endforelse
                </div>
            </div>
            @endif
        </div>
        </div>
    @endif
</div>

{{-- Track current topic for bot commands --}}
@php
    $currentTopicSlug = request()->query('topic');
    $currentTopicData = null;
    if ($currentTopicSlug && isset($topics)) {
        $currentTopicData = collect($topics)->firstWhere('slug', $currentTopicSlug);
    }
@endphp
<script>
    // Set current topic ID for messaging
    window.currentTopicId = {{ $currentTopicData['id'] ?? 'null' }};
    window.currentTopicSlug = '{{ $currentTopicSlug ?? '' }}';
    window.isRulesChannel = {{ ($currentTopicSlug === 'rules') ? 'true' : 'false' }};
    
    // Unmute button click handler for right sidebar
    document.addEventListener('click', async function(e) {
        const unmuteBtn = e.target.closest('.unmute-btn');
        if (!unmuteBtn) return;
        
        const userId = unmuteBtn.dataset.userId;
        const userName = unmuteBtn.dataset.userName || 'User';
        const conversationId = {{ $selectedConversation->id ?? 'null' }};
        
        if (!conversationId || !userId) return;
        
        unmuteBtn.disabled = true;
        unmuteBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin text-sm"></i>';
        
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
            unmuteBtn.disabled = false;
            unmuteBtn.innerHTML = '<i class="bi bi-volume-up-fill text-sm"></i>';
        }
    });
</script>
