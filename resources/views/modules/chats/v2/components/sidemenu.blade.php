{{-- Sidemenu - Includes both desktop and mobile versions --}}

{{-- Desktop Version --}}
@include('modules.chats.v2.components.desktop.sidemenu', [
    'servers' => $servers,
    'selectedConversation' => $selectedConversation,
])

{{-- Mobile Version (Slide-out) --}}
@include('modules.chats.v2.components.mobile.sidemenu', [
    'servers' => $servers,
    'selectedConversation' => $selectedConversation,
])
