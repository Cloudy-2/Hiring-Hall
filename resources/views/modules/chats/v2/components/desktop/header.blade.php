{{-- Desktop Chat Header --}}
@php
    $currentTopic = request()->query('topic');
    $topicName = $currentTopic ? '#' . $currentTopic : '#general';
    $headerActionBtnClass = 'rounded-lg p-2 text-gray-500 dark:text-white/60 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-gray-700 dark:hover:text-white transition';
@endphp
<div class="chat-v2-header hidden md:flex items-center justify-between border-b border-gray-200 dark:border-white/10 bg-white/80 dark:bg-sidebar-dark/70 backdrop-blur-sm px-4 py-2 transition-colors duration-200">
    <div class="flex items-center gap-2 flex-shrink-0 w-40">
        <i class="bi bi-hash text-xl text-gray-500 dark:text-white/50"></i>
        <div>
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                @if($selectedConversation?->type === 'group')
                    {{ $topicName }}
                @else
                    {{ $selectedConversation?->name ?? 'Friend Activity' }}
                @endif
            </h2>
        </div>
    </div>
    
    {{-- Search Bar - shifted left --}}
    <button data-open-chat-modal="search-messages" class="flex-1 max-w-2xl mr-auto ml-8 flex items-center justify-center gap-2 px-6 py-1.5 rounded-md border border-slate-300 dark:border-white/15 bg-sidebar-light dark:bg-sidebar-dark text-gray-500 dark:text-white/55 hover:bg-slate-50 dark:hover:bg-white/10 hover:border-slate-400 dark:hover:border-white/25 transition cursor-pointer">
        <i class="bi bi-search text-sm"></i>
        <span class="text-sm">Search {{ $selectedConversation?->name ?? 'messages' }}</span>
    </button>
    
    <div class="flex gap-1 flex-shrink-0">
        @if($selectedConversation && auth()->user()->isModerator())
        <button onclick="startCall({{ $selectedConversation->id }}, 'audio')" 
            class="{{ $headerActionBtnClass }}" 
            title="Start voice call">
            <i class="bi bi-telephone text-lg"></i>
        </button>
        <button onclick="startCall({{ $selectedConversation->id }}, 'video')" 
            class="{{ $headerActionBtnClass }}" 
            title="Start video call">
            <i class="bi bi-camera-video text-lg"></i>
        </button>
        <button data-open-chat-modal="pinned-messages" class="{{ $headerActionBtnClass }}" title="Pinned Messages">
            <i class="bi bi-pin-angle text-lg"></i>
        </button>
        {{-- Add Members --}}
        <button type="button" id="header-add-members-btn" class="relative {{ $headerActionBtnClass }}"
            data-open-chat-modal="add-member-quick" title="Add Members">
            <i class="bi bi-person-plus-fill text-lg"></i>
        </button>
        @endif
        {{-- My Tasks (not for candidates) --}}
        @if(!auth()->user()->isCandidate())
        <button type="button" class="{{ $headerActionBtnClass }}"
            data-open-chat-modal="todo" title="My Tasks">
            <i class="bi bi-check2-square text-lg"></i>
        </button>
        @endif
        <button data-open-chat-modal="notifications-settings" class="{{ $headerActionBtnClass }}" title="Notifications">
            <i class="bi bi-bell text-lg"></i>
        </button>
        @if($selectedConversation && auth()->user()->isModerator())
            <button type="button"
                onclick="toggleConversationLock({{ $selectedConversation->id }}, {{ $isConversationLocked ? 'true' : 'false' }})"
                class="rounded-lg p-2 text-gray-500 dark:text-white/60 hover:bg-gray-100 dark:hover:bg-[#383838] hover:text-gray-700 dark:hover:text-white transition"
                title="{{ $isConversationLocked ? 'Unlock Chat' : 'Lock Chat' }}">
                <i class="bi {{ $isConversationLocked ? 'bi-unlock' : 'bi-lock' }} text-lg"></i>
            </button>
        @endif
        @if($selectedConversation?->type === 'group')
            <button data-open-chat-modal="members" class="{{ $headerActionBtnClass }}" title="Members">
                <i class="bi bi-people text-lg"></i>
            </button>
        @else
            <button data-open-chat-modal="block-user" class="{{ $headerActionBtnClass }}" title="Block User">
                <i class="bi bi-person-slash text-lg"></i>
            </button>
        @endif
        @if($canDeleteGroup ?? false)
            <button data-open-chat-modal="group-settings" 
                class="{{ $headerActionBtnClass }}"
                title="Group Settings">
                <i class="bi bi-gear text-lg"></i>
            </button>
        @endif
    </div>
</div>
