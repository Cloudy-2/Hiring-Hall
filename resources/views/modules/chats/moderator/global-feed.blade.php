<x-app-layout>
    <style>
        /* Keep hover states dark in dark mode (avoid white flash). */
        [data-theme-mode="dark"] .gf-card,
        .dark .gf-card {
            background-color: #111827 !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        [data-theme-mode="dark"] .gf-card select,
        .dark .gf-card select {
            background-color: #1f2937 !important;
            color: #f3f4f6 !important;
            border-color: rgba(255, 255, 255, 0.14) !important;
            color-scheme: dark;
        }

        [data-theme-mode="dark"] .gf-card select option,
        .dark .gf-card select option {
            background-color: #111827 !important;
            color: #f9fafb !important;
        }

        [data-theme-mode="dark"] .gf-hover-row:hover,
        .dark .gf-hover-row:hover {
            background-color: rgba(31, 41, 55, 0.85) !important;
        }

        [data-theme-mode="dark"] .gf-soft-btn:hover,
        .dark .gf-soft-btn:hover {
            background-color: rgba(55, 65, 81, 0.9) !important;
            color: #f3f4f6 !important;
        }

        [data-theme-mode="dark"] .gf-pagination nav a:hover,
        [data-theme-mode="dark"] .gf-pagination nav span[aria-current="page"],
        .dark .gf-pagination nav a:hover,
        .dark .gf-pagination nav span[aria-current="page"] {
            background-color: rgba(55, 65, 81, 0.9) !important;
            color: #f3f4f6 !important;
            border-color: rgba(255, 255, 255, 0.14) !important;
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center">
                    <i class="bi bi-broadcast text-xl text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Global Chat Feed</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Monitor all conversations in real-time</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('chats.monitor') }}" class="gf-soft-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="bi bi-chat-square-dots"></i>
                    Chat monitor
                </a>
                <a href="{{ route('moderator.chat-reports.index') }}" class="gf-soft-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="bi bi-flag"></i>
                    Reported messages
                </a>
                <a href="{{ route('chats.v2') }}" class="gf-soft-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="bi bi-arrow-left"></i>
                    Back to Chat
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                            <i class="bi bi-chat-dots text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_messages']) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total Messages</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-500/20 flex items-center justify-center">
                            <i class="bi bi-people text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_conversations']) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Conversations</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center">
                            <i class="bi bi-lightning text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['messages_today']) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Today</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center">
                            <i class="bi bi-person-check text-amber-600 dark:text-amber-400"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['active_users']) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Active Today</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="gf-card bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 mb-6">
                <form method="GET" action="{{ route('chats.manage.global-feed') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Search Messages</label>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                                placeholder="Search message content..."
                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="w-48">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Conversation</label>
                        <select name="conversation" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                            <option value="">All Conversations</option>
                            @foreach($conversations as $conv)
                                <option value="{{ $conv->id }}" {{ ($filters['conversation'] ?? '') == $conv->id ? 'selected' : '' }}>
                                    {{ $conv->type === 'group' ? '👥' : '💬' }} {{ $conv->name ?? 'DM #'.$conv->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition">
                            <i class="bi bi-funnel mr-1"></i> Filter
                        </button>
                        <a href="{{ route('chats.manage.global-feed') }}" class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            {{-- Messages Feed --}}
            <div class="gf-card bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900 dark:text-white">
                        <i class="bi bi-clock-history mr-2 text-gray-400"></i>
                        Live Message Feed
                    </h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        Showing {{ $messages->count() }} of {{ $messages->total() }} messages
                    </span>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700 max-h-[600px] overflow-y-auto" id="messages-feed">
                    @forelse($messages as $message)
                        @php
                            $msgUser = $message->user;
                            $avatar = $msgUser?->profile_photo_path
                                ? asset('storage/' . ltrim($msgUser->profile_photo_path, '/'))
                                : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($msgUser?->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981';
                            $convName = $message->conversation?->name ?? ($message->conversation?->type === 'dm' ? 'Direct Message' : 'Unknown');
                            $isVideo = $message->attachments->contains(fn($a) => str_starts_with($a->mime ?? '', 'video/'));
                            $isImage = $message->attachments->contains(fn($a) => str_starts_with($a->mime ?? '', 'image/'));
                        @endphp
                        <div class="gf-hover-row p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div class="flex gap-3">
                                <img src="{{ $avatar }}" class="w-10 h-10 rounded-full flex-shrink-0" alt="{{ $msgUser?->name }}">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $msgUser?->name ?? 'Unknown' }}</span>
                                        <span class="text-xs px-2 py-0.5 rounded-full {{ in_array($msgUser?->role, ['moderator', 'admin', 'super_admin']) ? 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400' : ($msgUser?->role === 'applicant' ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-600 dark:text-gray-300') }}">
                                            {{ $msgUser?->role === 'applicant' ? 'Applicant' : ($msgUser?->role === 'super_admin' ? 'Super Admin' : ucfirst($msgUser?->role ?? 'user')) }}
                                        </span>
                                        <span class="text-gray-400 dark:text-gray-500">→</span>
                                        <a href="{{ route('chats.v2', ['conversation' => $message->conversation_id]) }}"
                                            class="text-xs px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400 hover:bg-indigo-200 dark:hover:bg-indigo-500/30 transition">
                                            {{ $message->conversation?->type === 'group' ? '👥' : '💬' }} {{ Str::limit($convName, 20) }}
                                        </a>
                                        <span class="text-xs text-gray-400 dark:text-gray-500 ml-auto">
                                            {{ $message->created_at->format('M d, g:i A') }}
                                        </span>
                                    </div>

                                    @if($message->body)
                                        <p class="text-gray-700 dark:text-gray-300 text-sm whitespace-pre-line break-words">{{ Str::limit($message->body, 500) }}</p>
                                    @endif

                                    @if($message->attachments->isNotEmpty())
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @foreach($message->attachments as $attachment)
                                                @if(str_starts_with($attachment->mime ?? '', 'video/'))
                                                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 text-xs">
                                                        <i class="bi bi-camera-video-fill"></i>
                                                        <span>{{ $attachment->original_name ?? 'Video' }}</span>
                                                    </div>
                                                @elseif(str_starts_with($attachment->mime ?? '', 'image/'))
                                                    <img src="{{ Storage::disk($attachment->disk)->url($attachment->path) }}"
                                                        class="h-16 rounded-lg object-cover cursor-pointer hover:opacity-80 transition"
                                                        onclick="window.open(this.src, '_blank')"
                                                        alt="Image">
                                                @else
                                                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs">
                                                        <i class="bi bi-paperclip"></i>
                                                        <span>{{ $attachment->original_name ?? basename($attachment->path) }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-chat-dots text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">No messages found</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Try adjusting your filters</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($messages->hasPages())
                    <div class="gf-pagination px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $messages->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
