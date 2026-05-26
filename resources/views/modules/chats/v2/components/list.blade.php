{{-- Sidebar List - Responsive wrapper for shared content --}}
<div class="chat-v2-sidebar flex w-64 flex-col transition-colors duration-200 overflow-y-auto bg-sidebar-light dark:bg-sidebar-dark border-r border-slate-200 dark:border-white/10">
    @include('modules.chats.v2.components.shared.sidebar-content', [
        'user' => $user,
        'selectedConversation' => $selectedConversation,
        'topics' => $topics ?? collect(),
        'friendStats' => $friendStats,
        'frequentFriends' => $frequentFriends,
        'directMessages' => $directMessages,
        'personalTags' => $personalTags ?? [],
    ])
</div>
