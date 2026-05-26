{{-- Mobile Chat Header --}}
@php
    $currentTopic = request()->query('topic');
    $topicName = $currentTopic ? '#' . $currentTopic : '#general';
    $isConversationLocked = (bool)($selectedConversation?->settings['locked'] ?? false);
    $dmTargetUser = null;
    if (($selectedConversation?->type ?? null) === 'dm') {
        $dmTargetUser = $selectedConversation->participants->firstWhere('user_id', '!=', auth()->id())?->user;
    }
@endphp
<div class="chat-v2-header md:hidden flex items-center justify-between border-b border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark px-3 py-2.5 shadow-sm transition-colors duration-200">
    <div class="flex items-center gap-2 min-w-0">
        {{-- Menu button --}}
        <button id="mobile-menu-btn" type="button" class="rounded-lg p-2 -ml-1 text-gray-600 dark:text-white/80 hover:bg-primary/20 hover:text-primary">
            <i class="bi bi-list text-2xl"></i>
        </button>
        
        <div class="min-w-0">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white truncate">
                @if($selectedConversation?->type === 'group')
                    {{ $topicName }}
                @else
                    {{ $selectedConversation?->name ?? 'Chat' }}
                @endif
            </h2>
            <p class="text-[11px] text-gray-500 dark:text-white/50 truncate">
                @if($selectedConversation?->type === 'group')
                    {{ $selectedConversation->name }} · {{ $selectedConversation->participants->count() }} members
                @else
                    Direct Message
                @endif
            </p>
        </div>
    </div>
    
    <div class="flex items-center gap-1">
        @if($selectedConversation && auth()->user()->isModerator())
        {{-- Voice call button --}}
        <button onclick="startCall({{ $selectedConversation->id }}, 'audio')" 
            class="rounded-lg p-2 text-green-500 dark:text-green-400 hover:bg-green-500/20 transition" 
            title="Voice call">
            <i class="bi bi-telephone-fill text-lg"></i>
        </button>
        {{-- Video call button --}}
        <button onclick="startCall({{ $selectedConversation->id }}, 'video')" 
            class="rounded-lg p-2 text-blue-500 dark:text-blue-400 hover:bg-blue-500/20 transition" 
            title="Video call">
            <i class="bi bi-camera-video-fill text-lg"></i>
        </button>
        @endif
        
        {{-- More options dropdown --}}
        <div class="relative" id="mobile-more-dropdown">
            <button type="button" id="mobile-more-btn" class="rounded-lg p-2 text-gray-600 dark:text-white/80 hover:bg-primary/20 dark:hover:bg-white/10 hover:text-primary transition">
                <i class="bi bi-three-dots-vertical text-xl"></i>
            </button>
            
            {{-- Dropdown menu --}}
            <div id="mobile-more-menu" class="hidden absolute right-0 top-full mt-1 w-48 rounded-lg bg-white dark:bg-sidebar-dark shadow-lg border border-gray-200 dark:border-white/10 py-1 z-50">
                @if(auth()->user()->isModerator())
                <button type="button" data-open-chat-modal="add-member-quick" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10">
                    <i class="bi bi-person-plus-fill text-xl text-green-400"></i>
                    Add Members
                </button>
                <button type="button" data-open-chat-modal="pinned-messages" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10">
                    <i class="bi bi-pin-angle text-xl text-amber-400"></i>
                    Pinned Messages
                </button>
                @endif
                <button type="button" data-open-chat-modal="notifications-settings" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10">
                    <i class="bi bi-bell text-xl text-yellow-400"></i>
                    Notifications
                </button>
                <button type="button" data-open-chat-modal="search-messages" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10">
                    <i class="bi bi-search text-xl text-purple-400"></i>
                    Search
                </button>
                @if($selectedConversation?->type === 'group')
                    <button type="button" data-open-chat-modal="members" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10">
                        <i class="bi bi-people text-xl text-cyan-400"></i>
                        Members
                    </button>
                @else
                    <button type="button" data-open-chat-modal="block-user" data-block-user-id="{{ $dmTargetUser?->id }}" data-block-user-name="{{ $dmTargetUser?->name ?? 'User' }}" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <i class="bi bi-person-slash text-xl"></i>
                        Block User
                    </button>
                @endif
                @if($canDeleteGroup ?? false)
                    <div class="border-t border-gray-200 dark:border-white/10 my-1"></div>
                    <button type="button" data-open-chat-modal="group-settings" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10">
                        <i class="bi bi-gear text-xl text-slate-400"></i>
                        Group Settings
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
