{{-- Server Hover Card Script --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Only enable hover card on desktop (768px+)
    const isMobile = () => window.innerWidth < 768;
    
    const hoverCard = document.createElement('div');
    hoverCard.id = 'server-hover-card';
    hoverCard.className = 'fixed z-[9999] hidden pointer-events-none';
    hoverCard.innerHTML = `
        <div class="ml-2 w-72 rounded-xl bg-white dark:bg-sidebar-dark border border-gray-200 dark:border-white/10 shadow-xl overflow-hidden">
            <div id="hover-card-banner" class="h-14 bg-gradient-to-br from-indigo-500 to-purple-600"></div>
            <div class="p-3 -mt-5">
                <div id="hover-card-avatar" class="size-10 rounded-xl overflow-hidden border-3 border-white dark:border-sidebar-dark flex items-center justify-center"></div>
                <h3 id="hover-card-name" class="mt-1.5 font-bold text-gray-900 dark:text-white truncate text-sm"></h3>
                <div class="flex items-center gap-3 mt-1 text-[11px] text-gray-500 dark:text-white/50">
                    <span id="hover-card-members" class="flex items-center gap-1"><i class="bi bi-people-fill"></i><span></span></span>
                    <span id="hover-card-online" class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span><span></span></span>
                </div>
                <div id="hover-card-messages" class="mt-2 pt-2 border-t border-gray-100 dark:border-white/10 space-y-1.5">
                    <div class="text-[10px] text-gray-400 dark:text-white/40 uppercase font-semibold">Recent Messages</div>
                    <div id="hover-card-messages-list" class="space-y-1"></div>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(hoverCard);

    let hoverTimeout = null;
    let currentTarget = null;
    let messageCache = {};
    let savedTitle = '';

    const getInitials = (name) => {
        const words = name.trim().split(' ');
        if (words.length >= 2) {
            return (words[0][0] + words[1][0]).toUpperCase();
        }
        return name.substring(0, 2).toUpperCase();
    };

    const getColorFromName = (name) => {
        const colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f97316', '#10b981', '#3b82f6', '#ef4444', '#f59e0b'];
        let hash = 0;
        for (let i = 0; i < name.length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        return colors[Math.abs(hash) % colors.length];
    };

    const showCard = async (target, data) => {
        const rect = target.getBoundingClientRect();
        hoverCard.style.left = `${rect.right}px`;
        hoverCard.style.top = `${Math.max(10, rect.top - 30)}px`;

        document.getElementById('hover-card-name').textContent = data.name || 'Unknown';
        document.getElementById('hover-card-members').querySelector('span:last-child').textContent = `${data.members || 0} members`;
        document.getElementById('hover-card-online').querySelector('span:last-child').textContent = `${data.online || 0} online`;

        const avatarEl = document.getElementById('hover-card-avatar');
        const bannerEl = document.getElementById('hover-card-banner');
        const initials = data.initials || getInitials(data.name);
        const bgColor = getColorFromName(data.name);
        
        bannerEl.style.background = `linear-gradient(135deg, ${bgColor}, ${bgColor}dd)`;
        bannerEl.className = 'h-14';
        avatarEl.style.backgroundColor = bgColor;
        avatarEl.innerHTML = `<span class="text-white font-bold text-sm">${initials}</span>`;

        const msgList = document.getElementById('hover-card-messages-list');
        msgList.innerHTML = '<div class="text-[10px] text-gray-400 animate-pulse">Loading...</div>';
        
        hoverCard.classList.remove('hidden');

        const convId = data.id;
        if (messageCache[convId] && Date.now() - messageCache[convId].ts < 30000) {
            renderMessages(messageCache[convId].data);
        } else {
            try {
                const res = await fetch(`/chats/${convId}/messages?limit=3`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const json = await res.json();
                    const msgs = json.messages || json.data || [];
                    messageCache[convId] = { data: msgs, ts: Date.now() };
                    renderMessages(msgs);
                } else {
                    msgList.innerHTML = '<div class="text-[10px] text-gray-400">No messages</div>';
                }
            } catch (e) {
                msgList.innerHTML = '<div class="text-[10px] text-gray-400">No messages</div>';
            }
        }
    };

    const renderMessages = (messages) => {
        const msgList = document.getElementById('hover-card-messages-list');
        if (!messages || messages.length === 0) {
            msgList.innerHTML = '<div class="text-[10px] text-gray-400 dark:text-white/40">No messages yet</div>';
            return;
        }
        msgList.innerHTML = messages.slice(0, 3).reverse().map(msg => {
            const name = msg.user?.name || 'User';
            let body = msg.body || '';
            const isGifUrl = body && !body.includes('<') && (body.startsWith('https://media.tenor.com') || body.includes('tenor.com/') || (body.startsWith('http') && body.endsWith('.gif')));
            const isGif = msg.type === 'gif' || isGifUrl;
            
            // Strip HTML tags but preserve text, convert br to space
            const stripHtml = (html) => {
                const tmp = document.createElement('div');
                tmp.innerHTML = html.replace(/<br\s*\/?>/gi, ' ').replace(/<\/p>/gi, ' ');
                return tmp.textContent || tmp.innerText || '';
            };
            
            const plainText = stripHtml(body).substring(0, 50) + (stripHtml(body).length > 50 ? '...' : '');
            
            return `
                <div class="flex items-start gap-1.5">
                    <span class="text-[10px] font-semibold text-gray-700 dark:text-white/80 shrink-0">${escapeHtml(name.split(' ')[0])}:</span>
                    <span class="text-[10px] text-gray-500 dark:text-white/50 truncate">${isGif ? '[GIF]' : escapeHtml(plainText) || '[attachment]'}</span>
                </div>
            `;
        }).join('');
    };

    const hideCard = () => {
        hoverCard.classList.add('hidden');
        if (currentTarget && savedTitle) {
            currentTarget.setAttribute('title', savedTitle);
        }
        currentTarget = null;
        savedTitle = '';
    };

    const escapeHtml = (text) => {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    };

    document.querySelector('.chat-v2-sidemenu')?.addEventListener('mouseenter', (e) => {
        // Skip on mobile
        if (isMobile()) return;
        
        if (!e.target || typeof e.target.closest !== 'function') return;
        const serverLink = e.target.closest('a[data-conversation-id]');
        if (!serverLink || serverLink === currentTarget) return;
        clearTimeout(hoverTimeout);
        
        savedTitle = serverLink.getAttribute('title') || '';
        serverLink.removeAttribute('title');
        
        currentTarget = serverLink;
        hoverTimeout = setTimeout(() => {
            const data = {
                id: serverLink.dataset.conversationId,
                name: serverLink.dataset.serverName || savedTitle,
                members: serverLink.dataset.serverMembers || '0',
                online: serverLink.dataset.serverOnline || '0',
                avatar: serverLink.dataset.serverAvatar || '',
                initials: serverLink.dataset.serverInitials || ''
            };
            showCard(serverLink, data);
        }, 400);
    }, true);

    document.querySelector('.chat-v2-sidemenu')?.addEventListener('mouseleave', (e) => {
        if (!e.target || typeof e.target.closest !== 'function') return;
        if (e.target.closest('a[data-conversation-id]')) {
            clearTimeout(hoverTimeout);
            hideCard();
        }
    }, true);

    document.addEventListener('scroll', hideCard, true);
    window.addEventListener('resize', hideCard);
});
</script>
