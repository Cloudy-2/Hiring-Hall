{{-- Pinned Messages Modal --}}
<div data-chat-modal="pinned-messages" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl overflow-hidden max-h-[80vh] flex flex-col">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-4">
            <div class="flex items-center gap-2">
                <i class="bi bi-pin-angle-fill text-xl text-primary"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Pinned Messages</h2>
                <span id="pinned-count-badge" class="hidden px-2 py-0.5 rounded-full bg-primary/10 text-xs font-medium text-primary">0</span>
            </div>
            <button type="button" data-chat-modal-close="pinned-messages" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-4" id="pinned-messages-list">
            {{-- Loading state --}}
            <div id="pinned-loading" class="flex flex-col items-center justify-center py-8 text-gray-400 dark:text-white/40">
                <i class="bi bi-arrow-repeat animate-spin text-3xl mb-2"></i>
                <p class="text-sm">Loading pinned messages...</p>
            </div>
            
            {{-- Empty state --}}
            <div id="pinned-empty" class="hidden flex-col items-center justify-center py-8 text-gray-400 dark:text-white/40">
                <i class="bi bi-pin-angle text-4xl mb-3 opacity-50"></i>
                <p class="text-sm font-medium">No pinned messages</p>
                <p class="text-xs mt-1">Pin important messages to find them easily</p>
            </div>
            
            {{-- Messages will be loaded here --}}
            <div id="pinned-messages-content" class="hidden space-y-3"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-chat-modal="pinned-messages"]');
    const loadingEl = document.getElementById('pinned-loading');
    const emptyEl = document.getElementById('pinned-empty');
    const contentEl = document.getElementById('pinned-messages-content');
    const countBadge = document.getElementById('pinned-count-badge');
    const isCurrentUserModerator = @json(auth()->user()->isModerator());

    async function loadPinnedMessages() {
        const conversationId = document.getElementById('chat-v2-form')?.dataset.conversationId || window.currentConversationId;
        if (!conversationId) {
            showEmpty();
            return;
        }

        showLoading();

        try {
            const response = await fetch(`/chats/${conversationId}/pinned`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) throw new Error('Failed to load pinned messages');

            const result = await response.json();
            const messages = result.data || [];

            if (messages.length === 0) {
                showEmpty();
                updateCount(0);
                return;
            }

            renderPinnedMessages(messages);
            updateCount(messages.length);
            showContent();
        } catch (error) {
            console.error('Error loading pinned messages:', error);
            showEmpty();
        }
    }

    function updateCount(count) {
        if (countBadge) {
            countBadge.textContent = count;
            if (count > 0) {
                countBadge.classList.remove('hidden');
            } else {
                countBadge.classList.add('hidden');
            }
        }
    }

    function renderPinnedMessages(messages) {
        if (!contentEl) return;
        
        contentEl.innerHTML = messages.map(msg => `
            <div class="group flex gap-3 p-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:border-primary/30 transition cursor-pointer" 
                 data-pinned-message-id="${msg.id}"
                 onclick="scrollToPinnedMessageFromModal(${msg.id})">
                <img src="${msg.user?.avatar || 'https://api.dicebear.com/7.x/avataaars/svg?seed=User&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981'}" 
                    alt="${msg.user?.name || 'User'}" 
                    class="size-10 rounded-full flex-shrink-0 object-cover">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-semibold text-sm text-gray-900 dark:text-white">${escapeHtml(msg.user?.name || 'Unknown')}</span>
                        <span class="text-xs text-gray-500 dark:text-white/50">${formatDate(msg.created_at)}</span>
                    </div>
                    <p class="text-sm text-gray-700 dark:text-white/80 line-clamp-2">${escapeHtml(msg.body || '')}</p>
                </div>
                ${isCurrentUserModerator ? `
                <button type="button" class="unpin-btn flex-shrink-0 p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition opacity-0 group-hover:opacity-100" 
                    data-message-id="${msg.id}" title="Remove pin" onclick="event.stopPropagation();">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
                ` : ''}
            </div>
        `).join('');

        // Add unpin handlers (only for moderators)
        if (isCurrentUserModerator) {
            contentEl.querySelectorAll('.unpin-btn').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    const messageId = btn.dataset.messageId;
                    await unpinMessageFromModal(messageId);
                });
            });
        }
    }

    async function unpinMessageFromModal(messageId) {
        try {
            const response = await fetch(`/chats/messages/${messageId}/pin`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (response.ok) {
                // Remove from modal UI
                const el = contentEl.querySelector(`[data-pinned-message-id="${messageId}"]`);
                el?.remove();

                // Update count
                const remaining = contentEl.querySelectorAll('[data-pinned-message-id]').length;
                updateCount(remaining);

                // Check if empty
                if (remaining === 0) {
                    showEmpty();
                    // Hide the pinned message bar
                    const bar = document.getElementById('pinned-message-bar');
                    if (bar) bar.classList.add('hidden');
                    const idInput = document.getElementById('pinned-message-id');
                    if (idInput) idInput.value = '';
                } else {
                    // Update the pinned bar to show the next pinned message
                    const firstPinned = contentEl.querySelector('[data-pinned-message-id]');
                    if (firstPinned) {
                        const newId = firstPinned.dataset.pinnedMessageId;
                        const newBody = firstPinned.querySelector('p.text-sm')?.textContent || '';
                        const newUser = firstPinned.querySelector('.font-semibold')?.textContent || '';
                        if (typeof updatePinnedMessageBar === 'function') {
                            updatePinnedMessageBar(newId, newBody, newUser);
                        }
                    }
                }

                // Show toast
                if (typeof showChatToast === 'function') {
                    showChatToast('Message unpinned', 'success');
                }
            }
        } catch (error) {
            console.error('Unpin error:', error);
            if (typeof showChatToast === 'function') {
                showChatToast('Failed to unpin message', 'error');
            }
        }
    }

    // Make function available globally
    window.unpinMessageFromModal = unpinMessageFromModal;

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit'
        });
    }

    function showLoading() {
        loadingEl?.classList.remove('hidden');
        emptyEl?.classList.add('hidden');
        emptyEl?.classList.remove('flex');
        contentEl?.classList.add('hidden');
    }

    function showEmpty() {
        loadingEl?.classList.add('hidden');
        emptyEl?.classList.remove('hidden');
        emptyEl?.classList.add('flex');
        contentEl?.classList.add('hidden');
    }

    function showContent() {
        loadingEl?.classList.add('hidden');
        emptyEl?.classList.add('hidden');
        emptyEl?.classList.remove('flex');
        contentEl?.classList.remove('hidden');
    }

    // Load when modal opens
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class' && modal.classList.contains('flex')) {
                loadPinnedMessages();
            }
        });
    });

    if (modal) {
        observer.observe(modal, { attributes: true });
    }
});

// Scroll to message and close modal
function scrollToPinnedMessageFromModal(messageId) {
    // Close modal
    const modal = document.querySelector('[data-chat-modal="pinned-messages"]');
    if (modal) {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.classList.remove('chat-modal-open');
    }

    // Scroll to message
    setTimeout(() => {
        const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
        if (messageEl) {
            messageEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            messageEl.classList.add('ring-2', 'ring-primary', 'ring-offset-2', 'dark:ring-offset-[#222529]');
            setTimeout(() => {
                messageEl.classList.remove('ring-2', 'ring-primary', 'ring-offset-2', 'dark:ring-offset-[#222529]');
            }, 2000);
        } else {
            if (typeof showChatToast === 'function') {
                showChatToast('Message not in view. Scroll up to find it.', 'info');
            }
        }
    }, 300);
}
</script>
