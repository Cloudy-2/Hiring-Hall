{{-- Members Modal --}}
@php
    $isModerator = auth()->user()?->isModerator();
@endphp
<div data-chat-modal="members" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl overflow-hidden max-h-[80vh] flex flex-col">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-4">
            <div class="flex items-center gap-2">
                <i class="bi bi-people-fill text-xl text-primary"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Members</h2>
                <span id="members-count" class="px-2 py-0.5 rounded-full bg-gray-100 dark:bg-white/10 text-xs font-medium text-gray-600 dark:text-white/60">0</span>
            </div>
            <button type="button" data-chat-modal-close="members" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Search --}}
        <div class="px-4 py-3 border-b border-gray-200 dark:border-white/10">
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-white/40"></i>
                <input type="text" id="members-search-input" placeholder="Search members..." 
                    class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 py-2 pl-10 pr-4 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-white/40 focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
            </div>
        </div>

        {{-- Members List --}}
        <div class="flex-1 overflow-y-auto p-4" id="members-list-container">
            @if($isModerator)
            {{-- Muted Section (Moderator Only) --}}
            <div id="members-muted-section" class="mb-4 hidden">
                <h3 class="text-xs font-bold uppercase text-amber-500 dark:text-amber-400 mb-2 flex items-center gap-1">
                    <i class="bi bi-volume-mute-fill"></i>
                    Muted — <span id="muted-count">0</span>
                </h3>
                <div id="members-muted-list" class="space-y-1"></div>
            </div>
            @endif

            {{-- Online Section --}}
            <div id="members-online-section" class="mb-4">
                <h3 class="text-xs font-bold uppercase text-gray-500 dark:text-white/50 mb-2">
                    Online — <span id="online-count">0</span>
                </h3>
                <div id="members-online-list" class="space-y-1"></div>
            </div>
            
            {{-- Offline Section --}}
            <div id="members-offline-section">
                <h3 class="text-xs font-bold uppercase text-gray-500 dark:text-white/50 mb-2">
                    Offline — <span id="offline-count">0</span>
                </h3>
                <div id="members-offline-list" class="space-y-1"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-chat-modal="members"]');
    const searchInput = document.getElementById('members-search-input');
    const membersCount = document.getElementById('members-count');
    const onlineCount = document.getElementById('online-count');
    const offlineCount = document.getElementById('offline-count');
    const mutedCount = document.getElementById('muted-count');
    const onlineList = document.getElementById('members-online-list');
    const offlineList = document.getElementById('members-offline-list');
    const mutedList = document.getElementById('members-muted-list');
    const mutedSection = document.getElementById('members-muted-section');
    
    const isModerator = @json(auth()->user()?->isModerator());
    let allMembers = [];
    let mutedUsers = [];

    function collectMembers() {
        // Collect from right column (online users)
        const rightColumn = document.querySelector('.chat-v2-right');
        const members = [];
        
        if (rightColumn) {
            // Online members
            rightColumn.querySelectorAll('.flex.items-center.gap-3:not(.opacity-60)').forEach(el => {
                const name = el.querySelector('.text-sm.font-medium')?.textContent?.trim();
                const avatar = el.querySelector('img')?.src;
                const status = el.querySelector('.text-xs')?.textContent?.trim()?.toLowerCase() || 'online';
                if (name) {
                    members.push({ name, avatar, status, online: true });
                }
            });
            
            // Offline members
            rightColumn.querySelectorAll('.flex.items-center.gap-3.opacity-60').forEach(el => {
                const name = el.querySelector('.text-sm.font-medium')?.textContent?.trim();
                const avatar = el.querySelector('img')?.src;
                if (name) {
                    members.push({ name, avatar, status: 'offline', online: false });
                }
            });
        }
        
        return members;
    }

    async function loadMutedUsers() {
        if (!isModerator) return [];
        
        // Get current conversation ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const conversationId = urlParams.get('conversation');
        
        if (!conversationId) return [];
        
        try {
            const response = await fetch(`/chats/manage/muted-users?conversation_id=${conversationId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            return data.muted_users || [];
        } catch (e) {
            console.error('Failed to load muted users:', e);
            return [];
        }
    }

    function renderMembers(members, filter = '') {
        const filtered = filter 
            ? members.filter(m => m.name.toLowerCase().includes(filter.toLowerCase()))
            : members;
        
        const online = filtered.filter(m => m.online);
        const offline = filtered.filter(m => !m.online);
        
        // Filter muted users by search too
        const filteredMuted = filter
            ? mutedUsers.filter(m => m.name.toLowerCase().includes(filter.toLowerCase()))
            : mutedUsers;
        
        membersCount.textContent = filtered.length;
        onlineCount.textContent = online.length;
        offlineCount.textContent = offline.length;
        
        onlineList.innerHTML = online.map(m => renderMemberItem(m)).join('') || 
            '<p class="text-xs text-gray-400 dark:text-white/40 py-2">No online members</p>';
        
        offlineList.innerHTML = offline.map(m => renderMemberItem(m)).join('') ||
            '<p class="text-xs text-gray-400 dark:text-white/40 py-2">No offline members</p>';
        
        // Render muted users (moderator only)
        if (isModerator && mutedSection && mutedList && mutedCount) {
            if (filteredMuted.length > 0) {
                mutedSection.classList.remove('hidden');
                mutedCount.textContent = filteredMuted.length;
                mutedList.innerHTML = filteredMuted.map(m => renderMutedMemberItem(m)).join('');
            } else {
                mutedSection.classList.add('hidden');
            }
        }
    }

    function renderMemberItem(member) {
        const statusColor = {
            online: 'bg-green-500',
            idle: 'bg-yellow-400',
            dnd: 'bg-red-500',
            offline: 'bg-gray-400'
        }[member.status] || 'bg-gray-400';
        
        return `
            <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5 transition ${member.online ? '' : 'opacity-60'}">
                <div class="relative">
                    <img src="${member.avatar || 'https://api.dicebear.com/7.x/avataaars/svg?seed=' + encodeURIComponent(member.name) + '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981'}" 
                        class="w-10 h-10 rounded-full object-cover" alt="${member.name}">
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white dark:border-sidebar-dark ${statusColor}"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${member.name}</p>
                    <p class="text-xs text-gray-500 dark:text-white/50 capitalize">${member.status}</p>
                </div>
                <button type="button" class="member-dm-btn p-1.5 rounded-lg text-gray-400 hover:text-primary hover:bg-primary/10 transition" 
                    data-member-name="${member.name}" title="Send message">
                    <i class="bi bi-chat-dots"></i>
                </button>
            </div>
        `;
    }

    function renderMutedMemberItem(member) {
        const muteInfo = member.is_permanent 
            ? 'Muted indefinitely' 
            : `Until ${new Date(member.muted_until).toLocaleString()}`;
        
        return `
            <div class="flex items-center gap-3 p-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/30">
                <div class="relative">
                    <img src="${member.avatar}" class="w-10 h-10 rounded-full object-cover" alt="${member.name}">
                    <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full bg-amber-500 flex items-center justify-center">
                        <i class="bi bi-volume-mute-fill text-white text-[8px]"></i>
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${member.name}</p>
                    <p class="text-xs text-amber-600 dark:text-amber-400">${muteInfo}</p>
                </div>
                <button type="button" class="unmute-btn p-1.5 rounded-lg text-amber-500 hover:text-green-500 hover:bg-green-50 dark:hover:bg-green-900/20 transition" 
                    data-user-id="${member.id}" title="Unmute user">
                    <i class="bi bi-volume-up-fill"></i>
                </button>
            </div>
        `;
    }

    // Handle unmute button click
    document.getElementById('members-list-container')?.addEventListener('click', async (e) => {
        const unmuteBtn = e.target.closest('.unmute-btn');
        if (unmuteBtn) {
            const userId = unmuteBtn.dataset.userId;
            const urlParams = new URLSearchParams(window.location.search);
            const conversationId = urlParams.get('conversation');
            
            if (!userId || !conversationId) return;
            
            try {
                const response = await fetch(`/chats/manage/${conversationId}/members/${userId}/unmute`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                
                if (response.ok) {
                    if (typeof showChatToast === 'function') showChatToast('User unmuted', 'success');
                    mutedUsers = await loadMutedUsers();
                    renderMembers(allMembers, searchInput?.value || '');
                } else {
                    throw new Error('Failed to unmute');
                }
            } catch (e) {
                if (typeof showChatToast === 'function') showChatToast(e.message, 'error');
            }
            return;
        }

        const dmBtn = e.target.closest('.member-dm-btn');
        if (!dmBtn) return;

        const memberName = dmBtn.dataset.memberName;
        if (!memberName) return;

        // Close modal
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');

        // Open new DM modal with the member pre-selected
        const newDmModal = document.querySelector('[data-chat-modal="new-dm"]');
        if (newDmModal) {
            newDmModal.classList.remove('hidden');
            newDmModal.classList.add('flex');
            
            // Try to find and select the user in the DM modal
            setTimeout(() => {
                const searchInput = newDmModal.querySelector('input[type="text"]');
                if (searchInput) {
                    searchInput.value = memberName;
                    searchInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }, 100);
        }
    });

    // Search filter
    searchInput?.addEventListener('input', (e) => {
        renderMembers(allMembers, e.target.value);
    });

    // Load members when modal opens
    const observer = new MutationObserver(async (mutations) => {
        mutations.forEach(async (mutation) => {
            if (mutation.attributeName === 'class' && modal.classList.contains('flex')) {
                allMembers = collectMembers();
                
                // Load muted users for moderators
                if (isModerator) {
                    mutedUsers = await loadMutedUsers();
                }
                
                renderMembers(allMembers);
                searchInput.value = '';
            }
        });
    });

    if (modal) {
        observer.observe(modal, { attributes: true });
    }
});
</script>
