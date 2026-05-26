@php
    use Illuminate\Support\Str;

    $totalConversations = $stats['total'] ?? 0;
    $totalGroups = $stats['groups'] ?? 0;
    $totalDMs = $stats['dms'] ?? 0;
    $totalMessages = $stats['messages'] ?? 0;
    $totalUnread = $stats['unread'] ?? 0;
@endphp

<x-app-layout>
    <x-slot name="return">{"link": "/chats", "text": "Chats"}</x-slot>
    <x-slot name="title">Chat Moderator</x-slot>
    <x-slot name="url_1">{"link": "/chats", "text": "Chats"}</x-slot>
    <x-slot name="active">Messages</x-slot>
    <x-slot name="buttons"></x-slot>

    <style>
        html.swal2-shown,
        body.swal2-shown {
            overflow-y: scroll !important;
            padding-right: 0 !important;
        }

        .swal2-container {
            overflow-y: auto !important;
        }

        #filter-type {
            border-radius: 9999px !important;
            padding-left: 1rem;
            padding-right: 2rem;
            min-width: 180px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Let the filter bar and select dropdown extend outside the box so the dropdown is not clipped */
        .monitor-filter-bar,
        .monitor-filter-bar .box-body,
        .monitor-filter-bar .flex-wrap {
            overflow: visible !important;
        }

        /* Force hidden class to work */
        .conversation-row.hidden {
            display: none !important;
        }

        /* Badge visibility control */
        .badge.hidden {
            display: none !important;
            visibility: hidden !important;
        }

        /* Dark Mode Overrides for Chat Moderator */
        [data-theme-mode="dark"] .box,
        .dark .box,
        .dark-theme .box {
            background-color: rgba(255, 255, 255, 0.02) !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        [data-theme-mode="dark"] .box.border,
        .dark .box.border,
        .dark-theme .box.border {
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        [data-theme-mode="dark"] .border,
        .dark .border,
        .dark-theme .border {
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        [data-theme-mode="dark"] .border-b,
        .dark .border-b,
        .dark-theme .border-b {
            border-bottom-color: rgba(255, 255, 255, 0.05) !important;
        }

        [data-theme-mode="dark"] .border-t,
        .dark .border-t,
        .dark-theme .border-t {
            border-top-color: rgba(255, 255, 255, 0.05) !important;
        }

        [data-theme-mode="dark"] .bg-light\/30,
        .dark .bg-light\/30,
        .dark-theme .bg-light\/30 {
            background-color: rgba(255, 255, 255, 0.03) !important;
        }

        [data-theme-mode="dark"] .hover\:bg-light\/50:hover,
        .dark .hover\:bg-light\/50:hover,
        .dark-theme .hover\:bg-light\/50:hover {
            background-color: rgba(255, 255, 255, 0.06) !important;
        }

        [data-theme-mode="dark"] .ti-form-input,
        .dark .ti-form-input,
        .dark-theme .ti-form-input,
        [data-theme-mode="dark"] .ti-form-select,
        .dark .ti-form-select,
        .dark-theme .ti-form-select {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: rgba(255, 255, 255, 0.9) !important;
        }

        /* Chat monitor type filter: keep dropdown readable in dark mode */
        [data-theme-mode="dark"] #filter-type,
        .dark #filter-type,
        .dark-theme #filter-type {
            background-color: #171a21 !important;
            border-color: rgba(255, 255, 255, 0.14) !important;
            color: #f3f4f6 !important;
            color-scheme: dark;
            font-size: 0.875rem;
            font-weight: 500;
        }

        [data-theme-mode="dark"] #filter-type option,
        .dark #filter-type option,
        .dark-theme #filter-type option {
            background-color: #12151c !important;
            color: #f9fafb !important;
        }

        [data-theme-mode="dark"] #filter-type option:checked,
        .dark #filter-type option:checked,
        .dark-theme #filter-type option:checked {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }
    </style>

    <x-modern-header class="text-2xl" chip="Chat Moderator"
            title="Monitor and manage all conversations in the system.">
            <x-slot name="actions">
                <span class="text-xs font-medium text-textmuted me-1">Tools:</span>
                        <a href="{{ route('chats.monitor') }}" class="ti-btn ti-btn-sm ti-btn-primary">
                            <i class="bi bi-collection me-1"></i> Monitor
                        </a>
                        <a href="{{ route('moderator.chat-reports.index') }}"
                            class="ti-btn ti-btn-sm ti-btn-soft-warning">
                            <i class="bi bi-flag me-1"></i> Reported messages
                            @if(($pendingReportsCount ?? 0) > 0)
                                <span class="badge bg-warning text-white ms-1">{{ $pendingReportsCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('chats.manage.global-feed') }}" class="ti-btn ti-btn-sm ti-btn-soft-primary">
                            <i class="bi bi-broadcast me-1"></i> Global feed
                        </a>
            </x-slot>
        </x-modern-header>

    <div class="space-y-4 mx-auto mt-4 pb-6 sm:px-6 lg:px-8">

        <div class="grid gap-4 md:grid-cols-4 lg:grid-cols-4 ">
            <div class="box border">
                <div class="box-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-textmuted">Total Conversations</p>
                            <p class="mt-1 text-2xl font-semibold">{{ $totalConversations }}</p>
                            <p class="text-xs text-textmuted mt-1">All GC & Direct Messages</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <i class="bi bi-collection text-primary text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box border">
                <div class="box-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-textmuted">Total Messages</p>
                            <p class="mt-1 text-2xl font-semibold" data-stat="total-messages">
                                {{ number_format($totalMessages, 0) }}</p>
                            <p class="text-xs text-textmuted mt-1">Sum of all messages</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-info/10 flex items-center justify-center">
                            <i class="bi bi-chat-text text-info text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box border">
                <div class="box-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-textmuted">Group Chats</p>
                            <p class="mt-1 text-2xl font-semibold">{{ $totalGroups }}</p>
                            <p class="text-xs text-textmuted mt-1">Multiple members</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-success/10 flex items-center justify-center">
                            <i class="bi bi-people text-success text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box border">
                <div class="box-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-textmuted">Direct Messages</p>
                            <p class="mt-1 text-2xl font-semibold">{{ $totalDMs }}</p>
                            <p class="text-xs text-textmuted mt-1">Unread: <span class="font-semibold text-danger"
                                    data-stat="total-unread">{{ $totalUnread }}</span></p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-danger/10 flex items-center justify-center">
                            <i class="bi bi-person-lines-fill text-danger text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box border monitor-filter-bar">
            <div class="box-body flex flex-col gap-4">
                <div class="flex items-center gap-2 text-sm">
                    <i class="bi bi-funnel me-1 text-textmuted"></i>
                    <span class="font-medium">Conversations</span>
                    <span class="text-xs text-textmuted">Monitor GC/DM usage and activity</span>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                    <div class="flex items-center gap-2">
                        <label for="filter-type" class="text-xs font-medium text-textmuted">Type:</label>
                        <select id="filter-type" class="ti-form-select text-sm font-medium py-2 px-3"
                            aria-label="Filter conversation type">
                            <option value="all">All types</option>
                            <option value="group">Group Chats</option>
                            <option value="dm">Direct Messages</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <input id="conversation-search" type="text" placeholder="Search conversations..."
                            class="ti-form-input text-sm py-2 px-3 w-48 sm:w-64" />
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <button type="button" id="btn-show-unread" onclick="filterHotConversations()"
                            class="ti-btn ti-btn-warning-full ti-btn-sm">
                            <i class="bi bi-fire me-1"></i> Show Unread
                        </button>
                        <a href="{{ route('chats.monitor.export') }}" class="ti-btn ti-btn-success-full ti-btn-sm">
                            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
                        </a>
                        <a href="{{ route('chats.v2') }}" class="ti-btn ti-btn-primary ti-btn-sm">
                            <i class="bi bi-people-fill me-1"></i> Open Chat
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="box border">
            <div class="box-header">
                <div class="box-title">All Conversations</div>
            </div>
            <div class="box-body">
                <div class="space-y-3">
                    @forelse ($conversations as $conv)
                        @php
                            $isGroup = $conv->is_group;
                            $lastAt = $conv->last_message_at ?? $conv->updated_at;
                            $participantsCount = $conv->participants_count ?? $conv->participants->count();
                            $hasUnread = ($conv->total_unread ?? 0) > 0;
                            $uniqueUsers = $conv->participants->pluck('user')->filter()->unique('id')->take(3);
                        @endphp
                        <div class="border rounded-lg dark:border-defaultborder/10 conversation-row"
                            data-conversation-id="{{ $conv->id }}" data-type="{{ $isGroup ? 'group' : 'dm' }}"
                            data-unread="{{ $hasUnread ? '1' : '0' }}">
                            <div onclick="toggleAccordion('conv-{{ $conv->id }}')"
                                class="w-full flex items-center justify-between gap-3 p-4 text-start rounded-lg cursor-pointer transition hover:bg-light/50 {{ $hasUnread ? 'bg-warning/5' : '' }}">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium {{ $isGroup ? 'bg-success/10 text-success' : 'bg-info/10 text-info' }}">
                                        <i class="bi {{ $isGroup ? 'bi-people-fill' : 'bi-person' }}"></i>
                                        {{ $isGroup ? 'GROUP' : 'DM' }}
                                    </span>
                                    @if($isGroup && $conv->photo)
                                        <img src="{{ asset('storage/' . ltrim($conv->photo, '/')) }}"
                                            alt="{{ $conv->display_title }}"
                                            class="w-10 h-10 rounded-full object-cover border-2 border-white dark:border-bodybg">
                                    @else
                                        <div class="flex -space-x-2">
                                            @foreach($uniqueUsers as $chatUser)
                                                <img src="{{ $chatUser->profile_photo_path ? asset('storage/' . ltrim($chatUser->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($chatUser->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}"
                                                    alt="{{ $chatUser->name }}"
                                                    class="w-8 h-8 rounded-full border-2 border-white dark:border-bodybg object-cover">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if($hasUnread)
                                        <span class="badge bg-danger/10 text-danger"
                                            data-role="unread-badge">{{ $conv->total_unread }} unread</span>
                                    @endif
                                    <span class="badge bg-primary/10 text-primary"
                                        data-role="message-count">{{ ($conv->general_messages->count() ?? 0) + collect($conv->messages_by_topic)->flatten(1)->count() }}</span>
                                    <a href="{{ route('conversations.show', $conv->id) }}"
                                        class="ti-btn ti-btn-sm ti-btn-primary-full ms-2" onclick="event.stopPropagation()"
                                        title="View full conversation">
                                        <i class="bi bi-eye me-1"></i> View
                                    </a>
                                    <svg id="icon-conv-{{ $conv->id }}"
                                        class="w-4 h-4 transition-transform text-textmuted ms-1"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            <div id="conv-{{ $conv->id }}" class="hidden">
                                <div class="p-4 pt-0 space-y-3">
                                    <div class="flex items-center gap-2 pb-2 border-b dark:border-defaultborder/10">
                                        <a href="{{ route('conversations.show', $conv->id) }}"
                                            class="ti-btn ti-btn-sm ti-btn-primary-full">
                                            <i class="bi bi-eye me-1"></i> View
                                        </a>
                                        <button type="button"
                                            class="ti-btn ti-btn-sm ti-btn-danger-full btn-delete-conversation"
                                            data-id="{{ $conv->id }}" data-title="{{ $conv->display_title }}">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </button>
                                    </div>

                                    @if($conv->general_messages->count() > 0)
                                        <div class="border rounded-lg dark:border-defaultborder/10 bg-light/30">
                                            <button type="button" onclick="toggleAccordion('general-{{ $conv->id }}')"
                                                class="w-full flex items-center justify-between gap-3 p-3 text-start rounded-lg transition hover:bg-light/50">
                                                <div class="flex items-center gap-2">
                                                    <i class="ri-chat-1-line text-textmuted"></i>
                                                    <span class="font-medium text-sm">General</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="badge bg-secondary/10 text-secondary text-xs">{{ $conv->general_messages->count() }}</span>
                                                    <svg id="icon-general-{{ $conv->id }}" class="w-3 h-3 transition-transform"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                            </button>
                                            <div id="general-{{ $conv->id }}" class="hidden">
                                                <div class="p-3 pt-0 space-y-2 max-h-48 overflow-y-auto">
                                                    @foreach($conv->general_messages->take(10) as $message)
                                                        <div
                                                            class="flex items-start gap-2 p-2 rounded-lg hover:bg-light/50 transition">
                                                            <img src="{{ $message->user && $message->user->profile_photo_path ? asset('storage/' . ltrim($message->user->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($message->user->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}"
                                                                alt="" class="w-6 h-6 rounded-full object-cover mt-0.5">
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center gap-2">
                                                                    <span
                                                                        class="font-medium text-sm">{{ $message->user->name ?? 'Unknown' }}</span>
                                                                    <span
                                                                        class="text-xs text-textmuted">{{ $message->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                <p class="text-sm text-textmuted">
                                                                    {{ Str::limit($message->body, 100) ?: '[Attachment]' }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    @if($conv->general_messages->count() > 10)
                                                        <p class="text-xs text-textmuted text-center py-2">+
                                                            {{ $conv->general_messages->count() - 10 }} more messages</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @foreach($conv->messages_by_topic as $topicId => $topicMessages)
                                        @php $topic = $conv->topics_map[$topicId] ?? null; @endphp
                                        @if($topic)
                                            <div class="border rounded-lg dark:border-defaultborder/10 bg-light/30">
                                                <button type="button"
                                                    onclick="toggleAccordion('topic-{{ $conv->id }}-{{ $topicId }}')"
                                                    class="w-full flex items-center justify-between gap-3 p-3 text-start rounded-lg transition hover:bg-light/50">
                                                    <div class="flex items-center gap-2">
                                                        <i class="ri-hashtag text-primary"></i>
                                                        <span class="font-medium text-sm">{{ $topic->name }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="badge bg-primary/10 text-primary text-xs">{{ $topicMessages->count() }}</span>
                                                        <svg id="icon-topic-{{ $conv->id }}-{{ $topicId }}"
                                                            class="w-3 h-3 transition-transform" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </div>
                                                </button>
                                                <div id="topic-{{ $conv->id }}-{{ $topicId }}" class="hidden">
                                                    <div class="p-3 pt-0 space-y-2 max-h-48 overflow-y-auto">
                                                        @foreach($topicMessages->take(10) as $message)
                                                            <div
                                                                class="flex items-start gap-2 p-2 rounded-lg hover:bg-light/50 transition">
                                                                <img src="{{ $message->user && $message->user->profile_photo_path ? asset('storage/' . ltrim($message->user->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($message->user->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}"
                                                                    alt="" class="w-6 h-6 rounded-full object-cover mt-0.5">
                                                                <div class="flex-1 min-w-0">
                                                                    <div class="flex items-center gap-2">
                                                                        <span
                                                                            class="font-medium text-sm">{{ $message->user->name ?? 'Unknown' }}</span>
                                                                        <span
                                                                            class="text-xs text-textmuted">{{ $message->created_at->diffForHumans() }}</span>
                                                                    </div>
                                                                    <p class="text-sm text-textmuted">
                                                                        {{ Str::limit($message->body, 100) ?: '[Attachment]' }}</p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        @if($topicMessages->count() > 10)
                                                            <p class="text-xs text-textmuted text-center py-2">+
                                                                {{ $topicMessages->count() - 10 }} more messages</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                    @if($conv->general_messages->count() == 0 && count($conv->messages_by_topic) == 0)
                                        <p class="text-textmuted text-sm text-center py-4">No messages yet</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-textmuted text-sm text-center py-4">No conversations found.</p>
                    @endforelse
                </div>

                @if (method_exists($conversations, 'hasPages') && $conversations->hasPages())
                    <div class="mt-4 pt-3 border-t border-defaultborder">
                        {{ $conversations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/app.js'])

    <script>
        const monitorUserId = {{ (int) (auth()->id() ?? 0) }};

        // Only declare csrfToken if not already defined
        if (typeof csrfToken === 'undefined') {
            window.csrfToken = '{{ csrf_token() }}';
        }
        let showingOnlyUnread = false;

        window.toggleAccordion = function (id) {
            const content = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                if (icon) icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                if (icon) icon.classList.remove('rotate-180');
            }
        };

        window.filterHotConversations = function () {
            const typeFilter = document.getElementById('filter-type');
            const searchInput = document.getElementById('conversation-search');
            const rows = document.querySelectorAll('.conversation-row');
            const btn = document.getElementById('btn-show-unread');
            showingOnlyUnread = !showingOnlyUnread;

            if (showingOnlyUnread) {
                btn.innerHTML = '<i class="bi bi-eye me-1"></i> Show All';
            } else {
                btn.innerHTML = '<i class="bi bi-fire me-1"></i> Show Unread';
            }

            // Re-apply all filters after toggling unread state
            const type = typeFilter.value;
            const search = (searchInput.value || '').toLowerCase();
            rows.forEach(row => {
                const rowType = row.getAttribute('data-type');
                const text = row.innerText.toLowerCase();
                const hasUnread = row.dataset.unread === '1';

                const matchType = (type === 'all') || (type === rowType);
                const matchSearch = !search || text.includes(search);
                const matchUnread = !showingOnlyUnread || hasUnread;

                if (matchType && matchSearch && matchUnread) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        };

        document.addEventListener('DOMContentLoaded', () => {
            const typeFilter = document.getElementById('filter-type');
            const searchInput = document.getElementById('conversation-search');
            const rows = document.querySelectorAll('.conversation-row');

            function applyFilters() {
                const type = typeFilter.value;
                const search = (searchInput.value || '').toLowerCase();

                rows.forEach(row => {
                    const rowType = row.getAttribute('data-type');
                    const text = row.innerText.toLowerCase();
                    const hasUnread = row.dataset.unread === '1';

                    const matchType = (type === 'all') || (type === rowType);
                    const matchSearch = !search || text.includes(search);
                    const matchUnread = !showingOnlyUnread || hasUnread;

                    if (matchType && matchSearch && matchUnread) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            }

            // Attach event listeners
            typeFilter.addEventListener('change', applyFilters);
            searchInput.addEventListener('input', applyFilters);

            // Optimistic read-state: clear unread badge when user opens/views a conversation
            document.querySelectorAll('a[href*="/chats/monitor/"]').forEach((link) => {
                link.addEventListener('click', () => {
                    const row = link.closest('.conversation-row');
                    if (!row) return;
                    const conversationId = row.getAttribute('data-conversation-id');
                    if (!conversationId) return;
                    updateConversationUnread(conversationId, 0);
                });
            });

            // Initialize filter on page load
            applyFilters();

            document.querySelectorAll('.btn-delete-conversation').forEach(btn => {
                btn.addEventListener('click', function () {
                    const conversationId = this.dataset.id;
                    const conversationTitle = this.dataset.title || 'this conversation';
                    const row = this.closest('.conversation-row');

                    Swal.fire({
                        title: 'Delete Conversation?',
                        html: `<p class="text-sm">This will permanently delete <strong>"${conversationTitle}"</strong> and all its messages.</p>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="bi bi-trash me-1"></i> Yes, delete it',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Deleting...',
                                text: 'Please wait',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => { Swal.showLoading(); }
                            });

                            fetch(`/chats/${conversationId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': window.csrfToken || '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                                .then(response => {
                                    if (response.ok) {
                                        row.remove();
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: 'The conversation has been deleted.',
                                            icon: 'success',
                                            timer: 2000,
                                            showConfirmButton: false
                                        });
                                    } else {
                                        throw new Error('Failed to delete');
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Could not delete the conversation. Please try again.',
                                        icon: 'error',
                                        confirmButtonColor: '#3085d6'
                                    });
                                });
                        }
                    });
                });
            });
        });

        // Real-time updates: Listen for conversation updates
        if (window.Echo) {
            // Listen on private chat.monitor channel for conversation updates
            window.Echo.private('chat.monitor').listen('conversation.updated', (data) => {
                if (Number(data.for_user_id) !== Number(monitorUserId)) {
                    return;
                }

                const unreadCount = Number(data.unread_count || 0);
                const conversationId = Number(data.conversation_id || 0);
                if (!conversationId) {
                    return;
                }

                updateConversationUnread(conversationId, unreadCount);
            });
        }

        function updateConversationUnread(conversationId, unreadCount) {
            const row = document.querySelector(`[data-conversation-id="${conversationId}"]`);
            if (!row) return;

            // Find the unread badge only (not message-count badge)
            let badge = row.querySelector('[data-role="unread-badge"]');
            const header = row.querySelector('[onclick*="toggleAccordion"]');
            const rightActions = row.querySelector('.flex.items-center.gap-2.shrink-0');

            if (unreadCount > 0) {
                // Has unread messages
                if (badge) {
                    badge.textContent = `${unreadCount} unread`;
                } else {
                    // Create badge if doesn't exist
                    const newBadge = document.createElement('span');
                    newBadge.className = 'badge bg-danger/10 text-danger';
                    newBadge.setAttribute('data-role', 'unread-badge');
                    newBadge.textContent = `${unreadCount} unread`;
                    if (rightActions) {
                        const messageCountBadge = rightActions.querySelector('[data-role="message-count"]');
                        if (messageCountBadge) {
                            rightActions.insertBefore(newBadge, messageCountBadge);
                        } else {
                            rightActions.prepend(newBadge);
                        }
                    }
                    badge = newBadge;
                }
                badge.classList.remove('hidden');

                // Add unread styling to row header
                if (header) {
                    header.classList.add('bg-warning/5');
                }
                row.dataset.unread = '1';
            } else {
                // No unread messages - hide unread badge
                if (badge) {
                    badge.classList.add('hidden');
                }

                // Remove unread styling
                if (header) {
                    header.classList.remove('bg-warning/5');
                }
                row.dataset.unread = '0';
            }

            syncTotalUnreadStat();
        }

        function syncTotalUnreadStat() {
            const totalUnreadElement = document.querySelector('[data-stat="total-unread"]');
            if (!totalUnreadElement) return;

            let total = 0;
            document.querySelectorAll('[data-role="unread-badge"]:not(.hidden)').forEach((badge) => {
                const m = (badge.textContent || '').match(/\d+/);
                total += m ? parseInt(m[0], 10) : 0;
            });

            totalUnreadElement.textContent = total;
        }
    </script>
</x-app-layout>