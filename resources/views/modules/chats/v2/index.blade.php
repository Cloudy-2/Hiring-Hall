<x-chat-v2-layout>
    {{-- Styles --}}
    @include('modules.chats.v2.partials.styles')

    {{-- Mobile overlay backdrop --}}
    <div id="mobile-overlay" class="mobile-overlay md:hidden"></div>

    <div class="chat-v2-wrapper flex h-dvh w-full font-display transition-colors duration-200">
        @include('modules.chats.v2.components.sidemenu', [
            'servers' => $servers,
            'selectedConversation' => $selectedConversation
        ])
        @include('modules.chats.v2.components.list', [
            'user' => $user,
            'selectedConversation' => $selectedConversation,
            'topics' => $topics,
            'friendStats' => $friendStats,
            'frequentFriends' => $frequentFriends,
            'directMessages' => $directMessages,
        ])
        @include('modules.chats.v2.components.main', [
            'user' => $user,
            'selectedConversation' => $selectedConversation,
            'selectedPersonalTag' => $selectedPersonalTag ?? null,
            'messages' => $messages,
            'personalTagMessages' => $personalTagMessages ?? collect(),
            'topics' => $topics,
            'rightColumn' => $rightColumn,
        ])
    </div>

    @include('components.modules.chat.modals.index', [
        'contacts' => $directMessages,
        'groups' => $servers,
    ])

    {{-- Scripts --}}
    @include('modules.chats.v2.partials.scripts.todo')
    @include('modules.chats.v2.partials.scripts.init')
    @include('modules.chats.v2.partials.scripts.mobile')
    @include('modules.chats.v2.partials.scripts.pending-requests')
    @include('modules.chats.v2.partials.scripts.websocket')
    @include('modules.chats.v2.partials.scripts.presence')
    @include('modules.chats.v2.partials.scripts.messaging')
    @include('modules.chats.v2.partials.scripts.message-actions')
    @include('modules.chats.v2.partials.scripts.server-hover-card')
    @include('modules.chats.v2.partials.scripts.user-hover-card')
</x-chat-v2-layout>
