{{-- Filter View Component (Unread, Threads, Mentions, Drafts) --}}
@php
    $title = $filterData['title'] ?? ucfirst($filter);
    $icon = $filterData['icon'] ?? 'bi-list';
    $items = $filterData['items'] ?? collect();
    $emptyMessage = $filterData['emptyMessage'] ?? 'No items found.';
    $isGroupContext = ($filterData['context'] ?? 'all') === 'group';
    $contextName = $filterData['contextName'] ?? 'all conversations';
    
    // Helper to safely render message body with HTML entities decoded
    $renderBody = function($body, $limit = 150) {
        $decoded = html_entity_decode($body ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $stripped = strip_tags($decoded);
        return Str::limit($stripped, $limit);
    };
@endphp

{{-- Header --}}
<div class="chat-v2-header flex items-center justify-between border-b border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark px-4 py-3 shadow-md transition-colors duration-200">
    <div class="flex items-center gap-3">
        {{-- Mobile menu button --}}
        <button type="button" onclick="window.openMobileNav?.()" class="md:hidden p-2 -ml-2 rounded-lg text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 transition">
            <i class="bi bi-list text-2xl"></i>
        </button>
        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
            <i class="{{ $icon }} text-xl text-primary"></i>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h2>
            <p class="text-xs text-gray-500 dark:text-white/50">
                {{ $items->count() }} {{ Str::plural('item', $items->count()) }}
                @if($isGroupContext)
                    <span class="text-primary">· #{{ $contextName }}</span>
                @endif
            </p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        @if($filter === 'unread' && $items->count() > 0)
            <button type="button" onclick="markAllAsRead()" class="text-xs text-primary hover:text-primary/80 font-medium transition">
                Mark all as read
            </button>
        @endif
    </div>
</div>

<div class="flex flex-1 overflow-hidden">
    <div class="relative flex flex-1 flex-col overflow-hidden">
        <div class="absolute inset-0 chat-bg pointer-events-none opacity-70"></div>
        
        {{-- Filter Content --}}
        <div class="flex-1 overflow-y-auto p-4 relative z-10">
            @if($items->count() > 0)
                <div class="max-w-3xl mx-auto space-y-2">
                    @foreach($items as $item)
                        @if($filter === 'unread')
                            {{-- Unread Message Item --}}
                            <a href="{{ route('chats.v2', ['conversation' => $item['conversation_id']]) }}" 
                                class="block p-4 rounded-xl bg-white dark:bg-sidebar-dark border border-gray-200 dark:border-white/10 hover:border-primary/50 dark:hover:border-primary/30 transition group">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @if($item['user_avatar'])
                                            <img src="{{ $item['user_avatar'] }}" alt="{{ $item['user_name'] }}" class="w-10 h-10 rounded-full">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                                                <span class="text-sm font-semibold text-primary">{{ Str::substr($item['user_name'], 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $item['user_name'] }}</span>
                                            <span class="text-xs text-gray-400 dark:text-white/40">in</span>
                                            <span class="text-xs font-medium text-primary">
                                                {{ $item['conversation_type'] === 'group' ? '#' : '' }}{{ $item['conversation_name'] }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-white/70 line-clamp-2">{{ $renderBody($item['body'], 150) }}</p>
                                        <p class="text-xs text-gray-400 dark:text-white/40 mt-2">
                                            {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="w-2 h-2 rounded-full bg-primary block"></span>
                                    </div>
                                </div>
                            </a>

                        @elseif($filter === 'threads')
                            {{-- Thread Item --}}
                            <a href="{{ route('chats.v2', ['conversation' => $item['conversation_id']]) }}" 
                                class="block p-4 rounded-xl bg-white dark:bg-sidebar-dark border border-gray-200 dark:border-white/10 hover:border-primary/50 dark:hover:border-primary/30 transition">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                                        <i class="bi bi-chat-square-text text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-medium text-gray-500 dark:text-white/50">Thread reply in</span>
                                            <span class="text-xs font-medium text-primary">{{ $item['conversation_name'] }}</span>
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-white/80 line-clamp-2">{{ $renderBody($item['body'], 150) }}</p>
                                        <p class="text-xs text-gray-400 dark:text-white/40 mt-2">
                                            {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>

                        @elseif($filter === 'mentions')
                            {{-- Mention/Reaction Item --}}
                            <a href="{{ route('chats.v2', ['conversation' => $item['conversation_id']]) }}" 
                                class="block p-4 rounded-xl bg-white dark:bg-sidebar-dark border border-gray-200 dark:border-white/10 hover:border-primary/50 dark:hover:border-primary/30 transition">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg {{ ($item['type'] ?? 'mention') === 'reaction' ? 'bg-amber-100 dark:bg-amber-500/20' : 'bg-purple-100 dark:bg-purple-500/20' }} flex items-center justify-center">
                                        @if(($item['type'] ?? 'mention') === 'reaction')
                                            <span class="text-lg">{{ $item['emoji'] ?? '👍' }}</span>
                                        @else
                                            <i class="bi bi-at text-purple-600 dark:text-purple-400 text-xl"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ $item['user_name'] }}</span>
                                            <span class="text-xs text-gray-400 dark:text-white/40">
                                                {{ ($item['type'] ?? 'mention') === 'reaction' ? 'reacted to your message' : 'mentioned you' }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-white/70 line-clamp-2">{{ $renderBody($item['body'], 150) }}</p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-xs font-medium text-primary">{{ $item['conversation_name'] }}</span>
                                            <span class="text-xs text-gray-400 dark:text-white/40">·</span>
                                            <span class="text-xs text-gray-400 dark:text-white/40">
                                                {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>

                        @elseif($filter === 'drafts')
                            {{-- Draft Item --}}
                            <div class="p-4 rounded-xl bg-white dark:bg-sidebar-dark border border-gray-200 dark:border-white/10 group">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-100 dark:bg-white/10 flex items-center justify-center">
                                        <i class="bi bi-pencil-square text-gray-500 dark:text-white/50"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-xs font-medium text-gray-500 dark:text-white/50">Draft for</span>
                                            <span class="text-xs font-medium text-primary">
                                                {{ $item['conversation_type'] === 'group' ? '#' : '' }}{{ $item['conversation_name'] }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-white/80 line-clamp-2">{{ $renderBody($item['body'], 150) }}</p>
                                        <p class="text-xs text-gray-400 dark:text-white/40 mt-2">
                                            Last edited {{ \Carbon\Carbon::parse($item['updated_at'])->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                                        <a href="{{ route('chats.v2', ['conversation' => $item['conversation_id']]) }}" 
                                            class="p-2 rounded-lg text-primary hover:bg-primary/10 transition" title="Continue editing">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" onclick="deleteDraft({{ $item['id'] }})" 
                                            class="p-2 rounded-lg text-red-500 hover:bg-red-500/10 transition" title="Delete draft">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mb-4">
                        <i class="{{ $icon }} text-4xl text-gray-400 dark:text-white/30"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-2">
                        @if($filter === 'unread')
                            All caught up!
                        @elseif($filter === 'threads')
                            No threads yet
                        @elseif($filter === 'mentions')
                            No mentions
                        @elseif($filter === 'drafts')
                            No drafts
                        @endif
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-white/50 max-w-xs">{{ $emptyMessage }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function markAllAsRead() {
    if (!confirm('Mark all messages as read?')) return;
    
    fetch('/chats/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(console.error);
}

function deleteDraft(draftId) {
    if (!confirm('Delete this draft?')) return;
    
    fetch(`/chats/drafts/${draftId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(console.error);
}
</script>
