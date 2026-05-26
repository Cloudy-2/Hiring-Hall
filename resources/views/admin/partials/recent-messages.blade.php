<!-- Recent Messages -->
<div class="box border">
    <div class="box-header flex items-center justify-between">
        <div class="box-title">Recent Chat Activity</div>
        <a href="{{ route('chats.monitor') }}" class="ti-btn ti-btn-sm ti-btn-primary">
            <i class="ri-chat-3-line me-1"></i> Open Chat Monitor
        </a>
    </div>
    <div class="box-body">
        <div class="space-y-3">
            @forelse($recentMessages as $conversationId => $data)
            @php
                $messages = $data['messages'];
                $conversation = $messages->first()->conversation;
                $uniqueUsers = $messages->pluck('user')->filter()->unique('id')->take(3);
                $userNames = $uniqueUsers->pluck('name')->join(', ');
                $totalUsers = $messages->pluck('user')->filter()->unique('id')->count();
                if ($totalUsers > 3) {
                    $userNames .= ' +' . ($totalUsers - 3);
                }
            @endphp
            <div class="border rounded-lg dark:border-defaultborder/10">
                <button type="button" onclick="toggleAccordion('messages-{{ $conversationId }}')" class="w-full flex items-center justify-between gap-3 p-4 text-start rounded-lg transition hover:bg-light/50 select-none">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <img src="{{ $conversation->photo ? asset('storage/' . ltrim($conversation->photo, '/')) : 'https://api.dicebear.com/7.x/shapes/svg?seed=' . urlencode($conversation->name ?? 'Chat-' . $conversationId) . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}" alt="{{ $conversation->name }}" class="w-10 h-10 rounded-full object-cover">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium truncate">{{ $conversation->name ?? $userNames ?: 'Direct Message' }}</p>
                            <p class="text-xs text-textmuted">{{ $messages->count() }} messages • Last: {{ $messages->first()->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center -space-x-1.5">
                            @foreach($uniqueUsers->take(3) as $chatUser)
                            <img src="{{ $chatUser->profile_photo_path ? asset('storage/' . ltrim($chatUser->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($chatUser->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}" alt="{{ $chatUser->name }}" class="w-5 h-5 rounded-full border border-white dark:border-bodybg object-cover">
                            @endforeach
                            @if($totalUsers > 3)
                            <span class="w-5 h-5 rounded-full bg-primary/20 text-primary text-[10px] font-medium flex items-center justify-center border border-white dark:border-bodybg">+{{ $totalUsers - 3 }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="badge bg-primary/10 text-primary">{{ $messages->count() }}</span>
                    <svg id="icon-messages-{{ $conversationId }}" class="w-4 h-4 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="messages-{{ $conversationId }}" class="hidden">
                    <div class="p-4 pt-0 space-y-2 max-h-64 overflow-y-auto">
                        @foreach($messages->take(10) as $message)
                        <div class="flex items-start gap-2 p-2 rounded-lg hover:bg-light/50 transition">
                            <img src="{{ $message->user && $message->user->profile_photo_path ? asset('storage/' . ltrim($message->user->profile_photo_path, '/')) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($message->user->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981' }}" alt="" class="w-6 h-6 rounded-full object-cover mt-0.5">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-sm">{{ $message->user->name ?? 'Unknown' }}</span>
                                    <span class="text-xs text-textmuted">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-textmuted">{{ Str::limit($message->body, 100) ?: '[Attachment]' }}</p>
                            </div>
                        </div>
                        @endforeach
                        @if($messages->count() > 10)
                        <p class="text-xs text-textmuted text-center py-2">+ {{ $messages->count() - 10 }} more messages</p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <p class="text-textmuted text-sm text-center py-4">No recent messages</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
</script>
