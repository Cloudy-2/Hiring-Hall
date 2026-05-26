{{-- Shared Chat Messages Component --}}
<div class="relative z-10 space-y-1 p-2" id="messages-container">
    {{-- Rules Channel Tutorial (only shows in #rules channel) --}}
    @php
        $currentTopicSlug = request()->query('topic');
        $isRulesChannel = $currentTopicSlug === 'rules';
    @endphp
    
    @if($isRulesChannel)
    <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark p-5 mb-4 shadow-sm">
        <div class="flex items-start gap-4">
            <img src="https://api.dicebear.com/7.x/bottts/svg?seed=HillBot&backgroundColor=6366f1" 
                 alt="HillBot" 
                 class="flex-shrink-0 w-12 h-12 rounded-xl">
            <div class="flex-1 min-w-0">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    HillBot Setup Guide
                </h3>
                <p class="text-sm text-gray-600 dark:text-white/60 mt-1 mb-3">
                    Configure the moderation bot using commands below. Only admins can use these commands.
                </p>
                
                <div class="grid gap-3 md:grid-cols-2">
                    {{-- Getting Started --}}
                    <div class="bg-gray-50 dark:bg-white/5 rounded-lg p-3">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-2 flex items-center gap-1">
                            <i class="bi bi-lightning-charge text-amber-500"></i> Quick Start
                        </h4>
                        <div class="space-y-1 text-xs font-mono">
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/enable</span> - Turn on the bot</p>
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/disable</span> - Turn off the bot</p>
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/help</span> - Show all commands</p>
                        </div>
                    </div>
                    
                    {{-- Built-in Filters --}}
                    <div class="bg-gray-50 dark:bg-white/5 rounded-lg p-3">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-2 flex items-center gap-1">
                            <i class="bi bi-shield-check text-green-500"></i> Built-in Filters
                        </h4>
                        <div class="space-y-1 text-xs font-mono">
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/profanity on</span> - Block bad words</p>
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/spam on</span> - Detect spam</p>
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/links on</span> - Block links</p>
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/caps on</span> - Limit CAPS</p>
                        </div>
                    </div>
                    
                    {{-- Custom Rules --}}
                    <div class="bg-gray-50 dark:bg-white/5 rounded-lg p-3">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-2 flex items-center gap-1">
                            <i class="bi bi-plus-circle text-blue-500"></i> Custom Rules
                        </h4>
                        <div class="space-y-1 text-xs font-mono">
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/addrule word</span> - Block a word</p>
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/removerule word</span> - Remove rule</p>
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/listrules</span> - View all rules</p>
                        </div>
                    </div>
                    
                    {{-- Actions --}}
                    <div class="bg-gray-50 dark:bg-white/5 rounded-lg p-3">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-2 flex items-center gap-1">
                            <i class="bi bi-gear text-gray-500"></i> Violation Actions
                        </h4>
                        <div class="space-y-1 text-xs font-mono">
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/setaction warn</span> - Just warn</p>
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/setaction delete</span> - Delete msg</p>
                            <p class="text-gray-600 dark:text-white/70"><span class="text-purple-600 dark:text-purple-400">/setaction mute-5</span> - Mute 5min</p>
                        </div>
                    </div>
                </div>
                
                <p class="text-xs text-gray-500 dark:text-white/50 mt-3 flex items-center gap-1">
                    <i class="bi bi-info-circle"></i>
                    Type <code class="px-1 py-0.5 bg-gray-100 dark:bg-white/10 text-purple-600 dark:text-purple-400 rounded">/help</code> for the full command list
                </p>
            </div>
        </div>
    </div>
    @endif

    @php 
        $lastDate = null; 
        $unreadSeparatorShown = false;
        $lastReadMsgId = $lastReadMessageId ?? null;
    @endphp
    @forelse($messages as $message)
        @php
            $isSystemMessage = $message->type === 'system' && !$message->deleted_by_moderator;
            $messageUser = $message->user;
            $avatar = $messageUser?->profile_photo_path
                ? asset('storage/' . ltrim($messageUser->profile_photo_path, '/'))
                : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($messageUser?->name ?? 'User') . '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981';
            $timestampIso = optional($message->created_at)->toIso8601String();
            $isForwarded = $message->forwarded_from_message_id !== null;
            $isOwnMessage = (int)($messageUser?->id ?? 0) === (int)auth()->id();
            $isPinned = false;
            
            $messageDate = optional($message->created_at)->format('Y-m-d');
            $showDateSeparator = $messageDate !== $lastDate;
            $lastDate = $messageDate;
            
            $today = now()->format('Y-m-d');
            $yesterday = now()->subDay()->format('Y-m-d');
            if ($messageDate === $today) {
                $dateLabel = 'Today';
            } elseif ($messageDate === $yesterday) {
                $dateLabel = 'Yesterday';
            } else {
                $dateLabel = optional($message->created_at)->format('l, F jS');
            }
            
            // Show unread separator before first unread message
            $showUnreadSeparator = false;
            if (!$unreadSeparatorShown && $lastReadMsgId && !$isOwnMessage && $message->id > $lastReadMsgId) {
                $showUnreadSeparator = true;
                $unreadSeparatorShown = true;
            }
        @endphp
        
        @if($showDateSeparator && $messageDate)
        <div class="flex items-center gap-4 py-3">
            <div class="flex-1 h-px bg-gray-200 dark:bg-white/10"></div>
            <span class="text-xs font-medium text-gray-500 dark:text-white/50">{{ $dateLabel }}</span>
            <div class="flex-1 h-px bg-gray-200 dark:bg-white/10"></div>
        </div>
        @endif
        
        @if($showUnreadSeparator)
        <div class="flex items-center gap-4 py-2 unread-separator" id="unread-separator">
            <div class="flex-1 h-px bg-gray-200 dark:bg-white/10"></div>
            <span class="text-xs font-medium text-gray-500 dark:text-white/50">New</span>
            <div class="flex-1 h-px bg-gray-200 dark:bg-white/10"></div>
        </div>
        @endif
        
        @if($isSystemMessage)
            {{-- System Message (centered pill) --}}
            @php
                $meta = $message->meta ?? [];
                $systemType = $meta['system_type'] ?? 'default';
                $isCallStarted = $systemType === 'call_started';
                $isCallEnded = $systemType === 'call_ended';
                $callType = $meta['call_type'] ?? 'video';
                $systemTimestampIso = optional($message->created_at)->toIso8601String();
            @endphp
            <div class="flex flex-col items-center py-2" data-message-id="{{ $message->id }}">
                @if($isCallStarted)
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-green-100 dark:bg-green-500/10 text-xs font-medium text-green-600 dark:text-green-400">
                        @if($callType === 'video')
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 13 5.223 3.482a.5.5 0 0 0 .777-.416V7.87a.5.5 0 0 0-.752-.432L16 10.5"/><rect x="2" y="6" width="14" height="12" rx="2"/></svg>
                        @else
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        @endif
                        <span>{{ $message->body }}</span>
                    </div>
                @elseif($isCallEnded)
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-100 dark:bg-red-500/10 text-xs font-medium text-red-600 dark:text-red-400">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7 2 2 0 0 1 1.72 2v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.42 19.42 0 0 1-3.33-2.67m-2.67-3.34a19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91"/><line x1="22" x2="2" y1="2" y2="22"/></svg>
                        <span>{{ $message->body }}</span>
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gray-100 dark:bg-white/5 text-xs text-gray-500 dark:text-white/50">
                        <i class="bi bi-info-circle"></i>
                        <span>{{ $message->body }}</span>
                    </div>
                @endif
                <span class="text-[10px] text-gray-400 dark:text-white/40 mt-1 msg-timestamp" data-timestamp="{{ $systemTimestampIso }}"></span>
            </div>
        @else
        @php
            $isUserStillMember = $selectedConversation?->participants
                ->where('user_id', $messageUser?->id)
                ->whereNull('left_at')
                ->isNotEmpty() ?? false;
            $isMessagePinned = in_array($message->id, $pinnedMessageIds ?? []);
            $shouldSkipMessage = $message->deleted_by_user && !$isOwnMessage && !auth()->user()->isModerator();
        @endphp
        @if($shouldSkipMessage)
            @continue
        @endif
        <div class="group relative flex gap-3 rounded-lg p-3 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors" data-message-id="{{ $message->id }}" data-is-own="{{ $isOwnMessage ? '1' : '0' }}" data-is-pinned="{{ $isMessagePinned ? '1' : '0' }}">
            <div class="absolute -top-3 right-2 hidden group-hover:flex items-center gap-1 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-lg px-1 py-0.5 z-20">
                <button type="button" class="msg-action-btn p-1.5 rounded hover:bg-gray-100 dark:hover:bg-white/15 text-gray-500 dark:text-white/60 transition" data-action="react" title="Add reaction">
                    <i class="bi bi-emoji-smile text-lg"></i>
                </button>
                @if(auth()->user()->isModerator())
                <button type="button" class="msg-action-btn pin-btn p-1.5 rounded hover:bg-gray-100 dark:hover:bg-white/15 text-gray-500 dark:text-white/60 transition" data-action="pin" title="Pin message">
                    <i class="bi bi-pin-angle text-lg"></i>
                </button>
                @endif
                <button type="button" class="msg-action-btn p-1.5 rounded hover:bg-gray-100 dark:hover:bg-white/15 text-gray-500 dark:text-white/60 transition" data-action="forward" title="Forward">
                    <i class="bi bi-forward text-lg"></i>
                </button>
                @if(!$isOwnMessage)
                <button type="button" class="msg-action-btn p-1.5 rounded hover:bg-red-100 dark:hover:bg-red-900/20 text-gray-500 dark:text-white/60 hover:text-red-500 transition" data-action="report" title="Report">
                    <i class="bi bi-flag text-lg"></i>
                </button>
                @endif
                <button type="button" class="msg-action-btn p-1.5 rounded hover:bg-gray-100 dark:hover:bg-white/15 text-gray-500 dark:text-white/60 transition relative" data-action="more" title="More">
                    <i class="bi bi-three-dots text-lg"></i>
                </button>
                {{-- Moderator Actions Dropdown --}}
                @if(auth()->user()->isModerator() && !$isOwnMessage)
                <div class="msg-more-dropdown hidden absolute top-full right-0 mt-1 w-44 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-xl z-30 py-1"
                     data-message-id="{{ $message->id }}"
                     data-user-id="{{ $messageUser?->id }}"
                     data-user-name="{{ $messageUser?->name ?? 'User' }}"
                     data-conversation-id="{{ $message->conversation_id }}">
                    @if($isUserStillMember)
                    <button type="button" class="mod-action-btn w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/15 transition" data-mod-action="mute">
                        <i class="bi bi-mic-mute text-amber-500"></i>
                        <span>Mute User</span>
                    </button>
                    <button type="button" class="mod-action-btn w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/15 transition" data-mod-action="kick">
                        <i class="bi bi-box-arrow-right text-orange-500"></i>
                        <span>Kick User</span>
                    </button>
                    <div class="border-t border-gray-200 dark:border-white/10 my-1"></div>
                    @endif
                    <button type="button" class="mod-action-btn w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition" data-mod-action="delete">
                        <i class="bi bi-trash"></i>
                        <span>Delete Message</span>
                    </button>
                </div>
                @endif
                {{-- Own Message Actions Dropdown --}}
                @if($isOwnMessage)
                <div class="msg-more-dropdown hidden absolute top-full right-0 mt-1 w-40 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-xl z-30 py-1"
                     data-message-id="{{ $message->id }}"
                     data-conversation-id="{{ $message->conversation_id }}">
                    <button type="button" class="own-action-btn w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/15 transition" data-own-action="edit">
                        <i class="bi bi-pencil text-blue-500"></i>
                        <span>Edit</span>
                    </button>
                    <button type="button" class="own-action-btn w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition" data-own-action="delete">
                        <i class="bi bi-trash"></i>
                        <span>Delete</span>
                    </button>
                </div>
                @endif
            </div>

            <img src="{{ $avatar }}" class="size-10 md:size-10 flex-shrink-0 rounded-full object-cover cursor-pointer hover:ring-2 hover:ring-primary/50 transition" alt="{{ $messageUser?->name }}" data-user-id="{{ $messageUser?->id }}">
            <div class="flex flex-1 flex-col min-w-0">
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                    @if($isOwnMessage)
                        <p class="text-sm font-medium" style="color: #2563eb !important;">{{ $messageUser?->name ?? 'Unknown User' }}</p>
                    @else
                        <p class="text-sm font-medium" style="color: #4EC9B0 !important;">{{ $messageUser?->name ?? 'Unknown User' }}</p>
                    @endif
                    @if($messageUser?->isModerator())
                        @php
                            $badgeLabel = match($messageUser->role) {
                                'super_admin' => 'SUPER',
                                'admin' => 'ADMIN',
                                default => 'MOD'
                            };
                        @endphp
                        <span class="inline-flex items-center text-[10px] font-medium text-blue-500"><i class="bi bi-patch-check-fill mr-0.5"></i>{{ $badgeLabel }}</span>
                    @endif
                    <p class="text-xs md:text-sm text-gray-500 dark:text-[#9d9db9] msg-timestamp" data-timestamp="{{ $timestampIso }}"></p>
                    @if($isForwarded)
                        <span class="text-xs uppercase tracking-wide text-gray-400 dark:text-white/40">Forwarded</span>
                    @endif
                </div>
                @if($message->forwarded_metadata)
                    <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-gray-100 dark:bg-black/20 p-3 text-xs text-gray-500 dark:text-white/60">
                        Forwarded message preview: {{ Str::limit(json_encode($message->forwarded_metadata), 80) }}
                    </div>
                @endif
                @if($message->deleted_by_moderator)
                    <p class="text-sm md:text-base leading-relaxed italic text-gray-400 dark:text-white/40 chat-message-body">
                        A moderator removed this message
                    </p>
                @elseif($message->deleted_by_user)
                    @if($isOwnMessage)
                        <p class="text-sm md:text-base leading-relaxed italic text-gray-400 dark:text-white/40 chat-message-body">
                            You deleted a message
                        </p>
                    @else
                        <p class="text-sm md:text-base leading-relaxed italic text-gray-400 dark:text-white/40 chat-message-body">
                            {{ $messageUser?->name ?? 'User' }} deleted a message
                        </p>
                    @endif
                @elseif($message->type === 'gif' || ($message->body && !str_contains($message->body, '<') && (Str::startsWith($message->body, 'https://media.tenor.com') || Str::contains($message->body, 'tenor.com/') || (Str::startsWith($message->body, 'http') && Str::endsWith($message->body, '.gif')))))
                    {{-- GIF/Sticker Message --}}
                    <img src="{{ $message->body }}" alt="GIF" class="max-w-xs rounded-lg cursor-pointer hover:opacity-90 transition" loading="lazy" onclick="window.open(this.src, '_blank')">
                @else
                    @php
                        // Allow safe HTML tags while preventing XSS
                        $allowedTags = '<br><strong><b><em><i><u><s><strike><code><pre><blockquote><ul><ol><li><p><span><a><h1><h2><h3><h4><h5><h6><img>';
                        $body = $message->body ?? '';
                        $isRichText = false;

                        if (preg_match('/<[^>]+>/', $body)) {
                            $safeBody = strip_tags($body, $allowedTags);
                            $isRichText = true;
                        } else {
                            $safeBody = e($body);
                            // Highlight @mentions
                            $safeBody = preg_replace(
                                '/@everyone\b/',
                                '<span class="mention mention-everyone font-medium cursor-pointer hover:underline" style="color: #eab308;">@everyone</span>',
                                $safeBody
                            );
                            // Supports full names with spaces like @Test User
                            $safeBody = preg_replace(
                                '/@([a-zA-Z0-9_\-]+(?:\s+[a-zA-Z0-9_\-]+)*)/',
                                '<span class="mention font-medium cursor-pointer hover:underline" style="color: #eab308;">@$1</span>',
                                $safeBody
                            );
                            // Convert URLs to links
                            $safeBody = preg_replace(
                                '/(https?:\/\/[^\s<]+)/',
                                '<a href="$1" target="_blank" rel="noopener noreferrer" class="chat-link" style="color: #2563eb !important; text-decoration: underline !important;">$1</a>',
                                $safeBody
                            );
                            $safeBody = nl2br($safeBody);
                        }
                    @endphp
                    <div class="text-sm md:text-base text-gray-700 dark:text-white/90 {{ $isRichText ? 'rich-text-content' : 'whitespace-pre-line' }} break-words chat-message-body">{!! $safeBody !!}</div>
                @endif
                @if(!$message->deleted_by_moderator && !$message->deleted_by_user)
                @if($message->attachments->isNotEmpty())
                    <div class="flex flex-col gap-2 mt-1">
                        @foreach($message->attachments as $attachment)
                            @php
                                $isVideo = Str::startsWith($attachment->mime, 'video/');
                                $isImage = Str::startsWith($attachment->mime, 'image/');
                                $fileUrl = \Storage::disk($attachment->disk)->url($attachment->path);
                                $supportedVideoFormats = ['video/mp4', 'video/webm', 'video/ogg'];
                                $isSupportedVideo = $isVideo && in_array($attachment->mime, $supportedVideoFormats);
                            @endphp
                            
                            @if($isVideo)
                                {{-- Video Attachment --}}
                                @php
                                    $downloadUrl = route('attachments.download', $attachment->id);
                                    // Use stream URL if route exists, otherwise fallback to download
                                    try {
                                        $streamUrl = route('attachments.stream', $attachment->id);
                                    } catch (\Exception $e) {
                                        $streamUrl = $downloadUrl;
                                    }
                                    $sizeFormatted = $attachment->size ? number_format($attachment->size / 1024 / 1024, 1) . ' MB' : '';
                                @endphp
                                <div class="col-span-full max-w-lg">
                                    @if($isSupportedVideo)
                                        {{-- Supported format - play inline --}}
                                        <div class="relative rounded-xl overflow-hidden bg-black">
                                            <video 
                                                src="{{ $streamUrl }}" 
                                                class="w-full max-h-80 object-contain"
                                                controls
                                                preload="metadata"
                                                playsinline>
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @else
                                        {{-- Unsupported format - show preview card with modal --}}
                                        <button type="button" 
                                            onclick="window.openVideoPreview('{{ $streamUrl }}', '{{ $downloadUrl }}', '{{ $attachment->original_name ?? basename($attachment->path) }}', '{{ $sizeFormatted }}', '{{ $attachment->mime }}')"
                                            class="w-full block rounded-xl overflow-hidden bg-gradient-to-br from-purple-500/20 to-indigo-500/20 border border-purple-500/30 hover:border-purple-500/50 transition text-left">
                                            <div class="flex items-center justify-center h-40 bg-black/20">
                                                <div class="text-center">
                                                    <i class="bi bi-play-circle text-5xl text-purple-400"></i>
                                                    <p class="text-xs text-purple-300 mt-2">{{ strtoupper(pathinfo($attachment->original_name ?? $attachment->path, PATHINFO_EXTENSION)) }} format</p>
                                                    <p class="text-[10px] text-white/50 mt-1">Click to preview</p>
                                                </div>
                                            </div>
                                        </button>
                                    @endif
                                    <div class="flex items-center gap-2 mt-2 text-xs text-gray-500 dark:text-white/50">
                                        <i class="bi bi-camera-video-fill text-purple-500"></i>
                                        <span class="truncate">{{ $attachment->original_name ?? basename($attachment->path) }}</span>
                                        @if($attachment->size)
                                            <span class="text-gray-400">·</span>
                                            <span>{{ $sizeFormatted }}</span>
                                        @endif
                                        <a href="{{ $downloadUrl }}" class="ml-auto text-purple-500 hover:text-purple-400">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </div>
                            @elseif($isImage)
                                {{-- Image Attachment --}}
                                @php
                                    $streamUrl = route('attachments.stream', $attachment->id);
                                @endphp
                                <div>
                                    <a href="{{ $streamUrl }}" target="_blank" rel="noopener" class="block">
                                        <div class="img-skeleton rounded-lg">
                                            <img src="{{ $streamUrl }}" alt="Image" class="rounded-lg max-h-60 object-cover hover:opacity-90 transition cursor-pointer" loading="lazy" onload="this.parentElement.classList.add('loaded')">
                                        </div>
                                    </a>
                                </div>
                            @else
                                {{-- File Attachment --}}
                                <a href="{{ $fileUrl }}"
                                    class="flex items-center gap-3 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-100 dark:bg-sidemenu-dark p-3 text-sm text-gray-700 dark:text-white hover:border-primary/50 transition-colors"
                                    target="_blank" rel="noopener">
                                    <i class="bi bi-paperclip text-gray-500 dark:text-white/70"></i>
                                    <span class="truncate">{{ $attachment->original_name ?? basename($attachment->path) }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
                @if($message->reactions->isNotEmpty())
                    @php
                        $groupedReactions = $message->reactions->groupBy('reaction');
                    @endphp
                    <div class="reactions-container flex flex-wrap gap-1 mt-2">
                        @foreach($groupedReactions as $emoji => $reactions)
                            <div class="relative group/reaction">
                                <button type="button" 
                                    class="reaction-btn inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 transition"
                                    data-reaction="{{ $emoji }}"
                                    data-message-id="{{ $message->id }}">
                                    <span>{{ $emoji }}</span>
                                    <span class="reaction-count text-gray-600 dark:text-white/70">{{ $reactions->count() }}</span>
                                </button>
                                <div class="reaction-tooltip absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 rounded-lg bg-gray-900 dark:bg-gray-800 text-white text-xs shadow-lg opacity-0 invisible group-hover/reaction:opacity-100 group-hover/reaction:visible transition-all duration-150 whitespace-nowrap z-30 pointer-events-none">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-lg text-center">{{ $emoji }}</span>
                                        @foreach($reactions as $reaction)
                                            <span class="text-gray-200">{{ $reaction->user->name ?? 'Unknown' }}</span>
                                        @endforeach
                                    </div>
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900 dark:border-t-gray-800"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                @endif {{-- end !deleted check for reactions/attachments --}}
            </div>
        </div>
        @endif
    @empty
        <div class="mt-10 rounded-2xl border border-dashed border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-6 text-center text-gray-500 dark:text-white/60">
            <p class="text-base font-semibold text-gray-700 dark:text-white">It's quiet for now</p>
            <p class="text-sm text-gray-500 dark:text-white/50">Start the conversation by sending the first message.</p>
        </div>
    @endforelse
</div>

<script>
// Format all message timestamps to local time on page load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.msg-timestamp[data-timestamp]').forEach(el => {
        const iso = el.dataset.timestamp;
        if (iso) {
            el.textContent = new Date(iso).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        }
    });
});
</script>
