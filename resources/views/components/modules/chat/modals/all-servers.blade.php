{{-- All Servers Modal - View all servers/groups --}}
<div data-chat-modal="all-servers" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-10">
    <div class="relative w-full max-w-2xl bg-white dark:bg-sidebar-dark rounded-2xl shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-white/10">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30">
                    <i class="bi bi-grid-3x3-gap-fill text-xl text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Servers</h3>
                    <p class="text-sm text-gray-500 dark:text-white/50" id="all-servers-count">Loading...</p>
                </div>
            </div>
            <button type="button" data-chat-modal-close="all-servers" class="rounded-full p-2 text-slate-400 dark:text-white/60 transition hover:bg-slate-100 dark:hover:bg-white/10 hover:text-slate-600 dark:hover:text-white">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Search --}}
        <div class="px-6 py-3 border-b border-gray-200 dark:border-white/10">
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-white/40"></i>
                <input type="text" id="all-servers-search" placeholder="Search servers..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-100 dark:bg-[#12121a] border-0 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-white/40 focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        {{-- Servers Grid --}}
        <div class="p-6 max-h-[60vh] overflow-y-auto" id="all-servers-container">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="all-servers-grid">
            </div>
            <div id="all-servers-empty" class="hidden text-center py-8">
                <i class="bi bi-inbox text-4xl text-gray-300 dark:text-white/20"></i>
                <p class="mt-2 text-gray-500 dark:text-white/50">No servers found</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/10 flex justify-end">
            <button type="button" data-chat-modal-close="all-servers"
                class="rounded-lg border border-slate-200 dark:border-white/10 px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white/70 transition hover:border-slate-300 dark:hover:border-white/30 hover:text-slate-900 dark:hover:text-white">
                Close
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.querySelector('[data-chat-modal="all-servers"]');
    const searchInput = document.getElementById('all-servers-search');
    const grid = document.getElementById('all-servers-grid');
    const emptyState = document.getElementById('all-servers-empty');
    const countEl = document.getElementById('all-servers-count');

    function getDiceBearUrl(seed) {
        return `https://api.dicebear.com/7.x/shapes/svg?seed=${encodeURIComponent(seed)}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981&backgroundType=gradientLinear`;
    }

    function getServersData() {
        const serverLinks = document.querySelectorAll('.chat-v2-sidemenu a[data-conversation-id]');
        const servers = [];
        
        serverLinks.forEach((link, index) => {
            const id = link.dataset.conversationId;
            const name = link.dataset.serverName || link.getAttribute('title') || 'Server';
            const avatar = link.dataset.serverAvatar || getDiceBearUrl(name + id);
            const isActive = link.classList.contains('ring-2');
            
            servers.push({ id, name, avatar, isActive });
        });
        
        return servers;
    }

    function renderServers(filter = '') {
        const servers = getServersData();
        const filtered = filter 
            ? servers.filter(s => s.name.toLowerCase().includes(filter.toLowerCase()))
            : servers;
        
        if (countEl) countEl.textContent = `${filtered.length} of ${servers.length} servers`;
        
        if (filtered.length === 0) {
            grid?.classList.add('hidden');
            emptyState?.classList.remove('hidden');
            return;
        }
        
        grid?.classList.remove('hidden');
        emptyState?.classList.add('hidden');
        
        if (grid) {
            grid.innerHTML = filtered.map(server => `
                <a href="/chats/v2?conversation=${server.id}" 
                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100 dark:hover:bg-white/5 transition group ${server.isActive ? 'bg-indigo-50 dark:bg-indigo-900/20 ring-2 ring-indigo-500' : ''}">
                    <img src="${server.avatar}" alt="${server.name}" class="flex-shrink-0 w-12 h-12 rounded-xl object-cover">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 dark:text-white truncate">${server.name}</p>
                        <p class="text-xs text-gray-500 dark:text-white/50">Click to open</p>
                    </div>
                    <i class="bi bi-chevron-right text-gray-400 dark:text-white/30 group-hover:text-indigo-500 transition"></i>
                </a>
            `).join('');
        }
    }

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                if (modal && modal.classList.contains('flex')) {
                    renderServers();
                    if (searchInput) {
                        searchInput.value = '';
                        searchInput.focus();
                    }
                }
            }
        });
    });
    
    if (modal) observer.observe(modal, { attributes: true });

    searchInput?.addEventListener('input', (e) => {
        renderServers(e.target.value);
    });
});
</script>
