{{-- Reactions Picker Modal with Emoji Mart CDN --}}
<div data-chat-modal="reactions" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-10">
    <div class="relative w-full max-w-sm">
        {{-- Close button --}}
        <button type="button" data-chat-modal-close="reactions"
            class="absolute -top-2 -right-2 z-10 rounded-full p-2 bg-gray-100 dark:bg-sidebar-dark text-gray-600 dark:text-white/60 transition hover:bg-gray-200 dark:hover:bg-[#383838] hover:text-gray-800 dark:hover:text-white border border-gray-200 dark:border-white/10 shadow-lg">
            <i class="bi bi-x-lg"></i>
        </button>

        <input type="hidden" id="reaction-message-id" value="">

        {{-- Emoji Picker Container --}}
        <div id="emoji-picker-container" class="flex justify-center"></div>
    </div>
</div>

<style>
/* Override emoji-mart picker styles for dark mode */
html.dark em-emoji-picker {
    --background-rgb: 45, 45, 48 !important;
    --border-color: rgba(255, 255, 255, 0.1) !important;
    --category-button-active-color: #1d9bd1 !important;
    --color-border: rgba(255, 255, 255, 0.1) !important;
    --color-border-over: rgba(255, 255, 255, 0.2) !important;
    --rgb-background: 45, 45, 48 !important;
    --rgb-input: 56, 56, 56 !important;
    border-radius: 16px !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
}
/* Light mode */
html:not(.dark) em-emoji-picker {
    border-radius: 16px !important;
    border: 1px solid rgba(0, 0, 0, 0.1) !important;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
}
</style>

{{-- Emoji Mart CDN --}}
<script type="module">
import { Picker } from 'https://cdn.jsdelivr.net/npm/emoji-mart@5.6.0/+esm';

document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-chat-modal="reactions"]');
    const messageIdInput = document.getElementById('reaction-message-id');
    const pickerContainer = document.getElementById('emoji-picker-container');
    let currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    let picker = null;

    if (!modal || !pickerContainer) return;

    const createPicker = () => {
        // Clear existing picker
        pickerContainer.innerHTML = '';
        
        currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        picker = new Picker({
            onEmojiSelect: (emoji) => addReaction(emoji.native),
            theme: currentTheme,
            set: 'native',
            previewPosition: 'none',
            skinTonePosition: 'none',
            perLine: 8,
            maxFrequentRows: 2,
        });
        pickerContainer.appendChild(picker);
    };

    const addReaction = async (emoji) => {
        const messageId = messageIdInput.value;
        if (!messageId || !emoji) return;

        try {
            const response = await fetch(`/chats/messages/${messageId}/react`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ reaction: emoji }),
            });

            if (response.ok) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('chat-modal-open');
                
                const data = await response.json();
                const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
                if (messageEl) {
                    let reactionsDiv = messageEl.querySelector('.reactions-container');
                    
                    if (data.action === 'removed') {
                        if (reactionsDiv) {
                            const existingBtn = reactionsDiv.querySelector(`[data-reaction="${emoji}"]`);
                            if (existingBtn) {
                                const countEl = existingBtn.querySelector('span:last-child');
                                const count = parseInt(countEl?.textContent || '1');
                                if (count <= 1) {
                                    existingBtn.remove();
                                    if (reactionsDiv.children.length === 0) reactionsDiv.remove();
                                } else {
                                    countEl.textContent = count - 1;
                                }
                            }
                        }
                        return;
                    }
                    
                    if (reactionsDiv) {
                        const myOldReaction = reactionsDiv.querySelector('[data-my-reaction="true"]');
                        if (myOldReaction && myOldReaction.dataset.reaction !== emoji) {
                            const countEl = myOldReaction.querySelector('span:last-child');
                            const count = parseInt(countEl?.textContent || '1');
                            if (count <= 1) {
                                myOldReaction.remove();
                            } else {
                                countEl.textContent = count - 1;
                                myOldReaction.removeAttribute('data-my-reaction');
                            }
                        }
                    }
                    
                    if (!reactionsDiv) {
                        reactionsDiv = document.createElement('div');
                        reactionsDiv.className = 'reactions-container flex flex-wrap gap-1 mt-2';
                        // Insert after attachments if they exist, otherwise after body
                        const attachmentsGrid = messageEl.querySelector('.grid.gap-2');
                        const bodyEl = messageEl.querySelector('.whitespace-pre-line, .chat-message-body');
                        const contentWrapper = messageEl.querySelector('.flex.flex-1.flex-col, .msg-content-wrapper');
                        if (attachmentsGrid) {
                            attachmentsGrid.parentElement?.insertBefore(reactionsDiv, attachmentsGrid.nextSibling);
                        } else if (bodyEl) {
                            bodyEl.parentElement?.insertBefore(reactionsDiv, bodyEl.nextSibling);
                        } else if (contentWrapper) {
                            contentWrapper.appendChild(reactionsDiv);
                        }
                    }
                    
                    let existingBtn = reactionsDiv.querySelector(`[data-reaction="${emoji}"]`);
                    if (existingBtn) {
                        const countEl = existingBtn.querySelector('span:last-child');
                        if (countEl) countEl.textContent = parseInt(countEl.textContent) + 1;
                        existingBtn.dataset.myReaction = 'true';
                    } else {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'reaction-btn inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 transition';
                        btn.dataset.reaction = emoji;
                        btn.dataset.myReaction = 'true';
                        btn.innerHTML = `<span>${emoji}</span><span class="text-gray-600 dark:text-white/70">1</span>`;
                        reactionsDiv.appendChild(btn);
                    }
                }
            }
        } catch (error) {
            console.error('Reaction error:', error);
        }
    };

    // Watch for modal open to create/recreate picker with correct theme
    const modalObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class' && modal.classList.contains('flex')) {
                const newTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
                if (!picker || currentTheme !== newTheme) {
                    createPicker();
                }
            }
        });
    });
    modalObserver.observe(modal, { attributes: true });

    // Watch for theme changes on html element
    const themeObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                const newTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
                if (currentTheme !== newTheme && picker) {
                    createPicker();
                }
            }
        });
    });
    themeObserver.observe(document.documentElement, { attributes: true });
});
</script>
