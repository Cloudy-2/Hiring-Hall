{{-- Search Messages Modal --}}
<div data-chat-modal="search-messages" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl overflow-hidden max-h-[80vh] flex flex-col">
        {{-- Header --}}
        <div class="border-b border-gray-200 dark:border-white/10 px-5 py-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <i class="bi bi-search text-xl text-primary"></i>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Search Messages</h2>
                </div>
                <button type="button" data-chat-modal-close="search-messages" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            {{-- Search input --}}
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-white/40"></i>
                <input type="text" id="search-messages-input" placeholder="Search in this conversation..." 
                    class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 py-2.5 pl-10 pr-4 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-white/40 focus:border-primary focus:ring-2 focus:ring-primary/20 transition"
                    autocomplete="off">
            </div>
        </div>

        {{-- Results --}}
        <div class="flex-1 overflow-y-auto p-4" id="search-results-container">
            {{-- Initial state --}}
            <div id="search-initial" class="flex flex-col items-center justify-center py-8 text-gray-400 dark:text-white/40">
                <i class="bi bi-chat-square-text text-4xl mb-3 opacity-50"></i>
                <p class="text-sm font-medium">Search messages</p>
                <p class="text-xs mt-1">Type to search in this conversation</p>
            </div>
            
            {{-- Loading state --}}
            <div id="search-loading" class="hidden flex-col items-center justify-center py-8 text-gray-400 dark:text-white/40">
                <i class="bi bi-arrow-repeat animate-spin text-3xl mb-2"></i>
                <p class="text-sm">Searching...</p>
            </div>
            
            {{-- No results --}}
            <div id="search-empty" class="hidden flex-col items-center justify-center py-8 text-gray-400 dark:text-white/40">
                <i class="bi bi-emoji-frown text-4xl mb-3 opacity-50"></i>
                <p class="text-sm font-medium">No messages found</p>
                <p class="text-xs mt-1">Try a different search term</p>
            </div>
            
            {{-- Results list --}}
            <div id="search-results" class="hidden space-y-2"></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-chat-modal="search-messages"]');
    const searchInput = document.getElementById('search-messages-input');
    const initialEl = document.getElementById('search-initial');
    const loadingEl = document.getElementById('search-loading');
    const emptyEl = document.getElementById('search-empty');
    const resultsEl = document.getElementById('search-results');
    
    let searchTimeout = null;
    let allMessages = [];

    function showState(state) {
        [initialEl, loadingEl, emptyEl, resultsEl].forEach(el => {
            el?.classList.add('hidden');
            el?.classList.remove('flex');
        });
        
        const el = { initial: initialEl, loading: loadingEl, empty: emptyEl, results: resultsEl }[state];
        if (el) {
            el.classList.remove('hidden');
            if (state !== 'results') el.classList.add('flex');
        }
    }

    function collectMessages() {
        const messagesContainer = document.getElementById('messages-container');
        if (!messagesContainer) return [];
        
        const messages = [];
        messagesContainer.querySelectorAll('[data-message-id]').forEach(msgEl => {
            const id = msgEl.dataset.messageId;
            const userName = msgEl.querySelector('.font-bold')?.textContent?.trim() || 'Unknown';
            const body = msgEl.querySelector('.whitespace-pre-line')?.textContent?.trim() || '';
            const time = msgEl.querySelector('.text-gray-500, .dark\\:text-\\[\\#9d9db9\\]')?.textContent?.trim() || '';
            const avatar = msgEl.querySelector('img')?.src || '';
            
            if (body) {
                messages.push({ id, userName, body, time, avatar });
            }
        });
        
        return messages;
    }

    function searchMessages(query) {
        if (!query.trim()) {
            showState('initial');
            return;
        }

        showState('loading');
        
        // Simulate async search
        setTimeout(() => {
            const lowerQuery = query.toLowerCase();
            const results = allMessages.filter(msg => 
                msg.body.toLowerCase().includes(lowerQuery) ||
                msg.userName.toLowerCase().includes(lowerQuery)
            );
            
            if (results.length === 0) {
                showState('empty');
                return;
            }
            
            renderResults(results, query);
            showState('results');
        }, 200);
    }

    function renderResults(results, query) {
        if (!resultsEl) return;
        
        resultsEl.innerHTML = results.map(msg => {
            // Highlight matching text
            const highlightedBody = msg.body.replace(
                new RegExp(`(${escapeRegex(query)})`, 'gi'),
                '<mark class="bg-yellow-200 dark:bg-yellow-500/30 rounded px-0.5">$1</mark>'
            );
            
            return `
                <button type="button" class="search-result-item w-full text-left p-3 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 transition" data-message-id="${msg.id}">
                    <div class="flex items-start gap-3">
                        <img src="${msg.avatar}" class="w-8 h-8 rounded-full flex-shrink-0" alt="${msg.userName}">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">${escapeHtml(msg.userName)}</span>
                                <span class="text-xs text-gray-500 dark:text-white/50">${msg.time}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-white/70 line-clamp-2">${highlightedBody}</p>
                        </div>
                    </div>
                </button>
            `;
        }).join('');
        
        // Add click handlers to scroll to message
        resultsEl.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', () => {
                const messageId = item.dataset.messageId;
                const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
                
                if (messageEl) {
                    // Close modal
                    modal?.classList.add('hidden');
                    modal?.classList.remove('flex');
                    
                    // Scroll to message and highlight
                    messageEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    messageEl.classList.add('ring-2', 'ring-primary', 'ring-offset-2');
                    setTimeout(() => {
                        messageEl.classList.remove('ring-2', 'ring-primary', 'ring-offset-2');
                    }, 2000);
                }
            });
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Search on input
    searchInput?.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchMessages(e.target.value);
        }, 300);
    });

    // Collect messages when modal opens
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class' && modal.classList.contains('flex')) {
                allMessages = collectMessages();
                searchInput?.focus();
                showState('initial');
            }
        });
    });

    if (modal) {
        observer.observe(modal, { attributes: true });
    }
});
</script>
