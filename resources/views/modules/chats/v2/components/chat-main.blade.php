{{-- MAIN CHAT AREA - HARDCODED --}}
<div class="flex-1 flex flex-col bg-discord-light">
    {{-- Chat Header --}}
    <div class="h-16 bg-discord-dark border-b px-6 flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                <i class="bi bi-people-fill"></i>
                Friends
            </h3>
            <p class="text-xs text-discord-muted">There are no friends online at this time. Check back later!</p>
        </div>
        <div class="flex items-center gap-4">
            <button type="button" class="text-discord-muted hover:text-white transition">
                <i class="bi bi-telephone text-lg"></i>
            </button>
            <button type="button" class="text-discord-muted hover:text-white transition">
                <i class="bi bi-camera-video text-lg"></i>
            </button>
            <button type="button" class="text-discord-muted hover:text-white transition">
                <i class="bi bi-question-circle text-lg"></i>
            </button>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="bg-discord-dark border-b px-6 flex gap-6">
        <button class="px-2 py-3 text-sm font-medium text-white border-b-2 border-indigo-500 hover:text-white transition">
            <i class="bi bi-people-fill mr-2"></i>Friends
        </button>
        <button class="px-2 py-3 text-sm font-medium text-discord-muted hover:text-white transition">
            All
        </button>
        <button class="px-2 py-3 text-sm font-medium text-discord-muted hover:text-white transition">
            Pending <span class="badge-unread ml-2">1</span>
        </button>
        <button class="px-2 py-3 text-sm font-medium text-discord-muted hover:text-white transition">
            Add Friend
        </button>
    </div>

    {{-- Messages Container - Empty State with Checkpoint Banner --}}
    <div id="v2-messages-container" class="flex-1 overflow-y-auto px-6 py-4">
        <div class="flex items-start justify-end gap-4 h-full">
            <!-- Main Content -->
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center text-discord-muted">
                    <p class="text-sm">There are no friends online at this time. Check back later!</p>
                </div>
            </div>

            <!-- Checkpoint Banner -->
            <div class="w-80 bg-gradient-to-br from-green-900 to-green-800 rounded-lg p-4 border border-green-500 shadow-lg">
                <div class="flex items-start gap-3">
                    <div class="text-3xl">🎯</div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-green-400">CHECKPOINT</h3>
                        <p class="text-xs text-green-200 mt-1">Look back at your 2025 on Discord</p>
                        <p class="text-[10px] text-green-300 mt-2">Recap</p>
                        <button class="mt-3 px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs font-bold rounded transition">
                            →
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subtitle -->
        <div class="text-center mt-8 text-discord-muted">
            <p class="text-sm font-semibold">It's quiet for now...</p>
            <p class="text-xs mt-2">When a friend starts an activity—like playing a game or hanging out on voice—we'll show it here!</p>
        </div>
    </div>

    {{-- Message Input Area --}}
    <div class="bg-discord-dark border-t px-6 py-4">
        <div class="flex items-end gap-3">
            <button type="button" class="text-discord-muted hover:text-white transition flex-shrink-0">
                <i class="bi bi-plus-circle text-xl"></i>
            </button>
            <div class="flex-1">
                <textarea id="v2-message-input" placeholder="Message @User..."
                    class="w-full bg-discord-darker border border-discord-darker rounded-lg px-4 py-2 text-sm text-white placeholder:text-slate-500 focus:outline-none focus:border-indigo-500 focus:bg-discord-darker resize-none"
                    rows="1"></textarea>
            </div>
            <button type="button" id="v2-send-btn" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition flex-shrink-0">
                <i class="bi bi-send"></i>
            </button>
        </div>
    </div>
</div>

<script>
    const v2MessagesContainer = document.getElementById('v2-messages-container');
    const v2MessageInput = document.getElementById('v2-message-input');
    const v2SendBtn = document.getElementById('v2-send-btn');
    const v2ChatTitle = document.getElementById('v2-chat-title');
    const v2ChatSubtitle = document.getElementById('v2-chat-subtitle');

    let v2CurrentConversation = null;

    v2MessageInput?.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // Handle conversation selection
    document.querySelectorAll('.conversation-item').forEach(item => {
        item.addEventListener('click', () => {
            const convId = item.dataset.conversationId;
            v2CurrentConversation = convId;
            document.querySelectorAll('.conversation-item').forEach(i => i.classList.remove('bg-slate-700'));
            item.classList.add('bg-slate-700');
            const title = item.querySelector('.text-sm').textContent;
            v2ChatTitle.textContent = title;
            v2ChatSubtitle.textContent = 'Direct message';
            v2MessagesContainer.innerHTML = '<div class="text-center text-slate-400 py-8"><p class="text-sm">Messages will load here</p></div>';
        });
    });

    // Handle send message
    v2SendBtn?.addEventListener('click', () => {
        const message = v2MessageInput.value.trim();
        if (!message || !v2CurrentConversation) return;

        const msgEl = document.createElement('div');
        msgEl.className = 'message-animate flex gap-3';
        msgEl.innerHTML = `
            <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <p class="text-xs font-medium text-white">{{ auth()->user()->name ?? 'You' }}</p>
                <p class="text-sm text-slate-200 break-words">${message}</p>
            </div>
        `;

        v2MessagesContainer.appendChild(msgEl);
        v2MessageInput.value = '';
        v2MessageInput.style.height = 'auto';
        v2MessagesContainer.scrollTop = v2MessagesContainer.scrollHeight;
    });

    v2MessageInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            v2SendBtn?.click();
        }
    });
</script>
