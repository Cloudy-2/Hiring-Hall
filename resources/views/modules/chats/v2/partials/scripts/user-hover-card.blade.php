{{-- User Profile Hover Card Script --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Create the hover card element
    const userHoverCard = document.createElement('div');
    userHoverCard.id = 'user-hover-card';
    userHoverCard.className = 'fixed z-[9999] hidden pointer-events-none';
    userHoverCard.innerHTML = `
        <div class="w-72 rounded-xl bg-sidebar-light dark:bg-sidebar-dark border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden">
            <!-- Banner -->
            <div id="user-card-banner" class="h-16 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 relative">
                <div class="absolute inset-0 bg-black/10"></div>
            </div>

            <!-- Profile Section -->
            <div class="px-4 pb-4 -mt-8 relative">
                <!-- Avatar -->
                <div class="relative inline-block">
                    <div id="user-card-avatar" class="size-16 rounded-full border-4 border-white dark:border-sidebar-dark overflow-hidden bg-gray-200 dark:bg-gray-700">
                        <img src="" alt="" class="size-full object-cover">
                    </div>
                    <span id="user-card-status" class="absolute bottom-1 right-1 size-4 rounded-full border-2 border-white dark:border-sidebar-dark bg-gray-400"></span>
                </div>

                <!-- Name & Role -->
                <div class="mt-2">
                    <div class="flex items-center gap-2">
                        <h3 id="user-card-name" class="font-bold text-gray-900 dark:text-white text-base"></h3>
                        <span id="user-card-badge" class="hidden px-1.5 py-0.5 rounded text-[10px] font-bold uppercase"></span>
                    </div>
                    <p id="user-card-role" class="text-xs text-gray-500 dark:text-white/50 mt-0.5"></p>
                </div>

                <!-- Divider -->
                <div class="my-3 border-t border-gray-200 dark:border-white/10"></div>

                <!-- Info Section -->
                <div class="space-y-2">
                    <div id="user-card-about" class="hidden">
                        <p class="text-[10px] font-semibold uppercase text-gray-400 dark:text-white/40 mb-1">About Me</p>
                        <p id="user-card-about-text" class="text-xs text-gray-600 dark:text-white/70"></p>
                    </div>

                    <div id="user-card-member-since">
                        <p class="text-[10px] font-semibold uppercase text-gray-400 dark:text-white/40 mb-1">Member Since</p>
                        <p id="user-card-joined" class="text-xs text-gray-600 dark:text-white/70"></p>
                    </div>

                    <div id="user-card-roles-section" class="hidden">
                        <p class="text-[10px] font-semibold uppercase text-gray-400 dark:text-white/40 mb-1">Roles</p>
                        <div id="user-card-roles" class="flex flex-wrap gap-1"></div>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(userHoverCard);

    let hoverTimeout = null;
    let currentTarget = null;
    let userCache = {};

    // Check if any member action dropdown is currently open
    const isDropdownActive = () => {
        return document.querySelector('.member-action-dropdown:not(.hidden)') !== null;
    };

    const statusColors = {
        online: 'bg-green-500',
        idle: 'bg-yellow-400',
        dnd: 'bg-red-500',
        offline: 'bg-gray-400'
    };

    const roleColors = {
        moderator: { bg: 'bg-purple-100 dark:bg-purple-500/20', text: 'text-purple-600 dark:text-purple-400' },
        admin: { bg: 'bg-blue-100 dark:bg-blue-500/20', text: 'text-blue-600 dark:text-blue-400' },
        candidate: { bg: 'bg-green-100 dark:bg-green-500/20', text: 'text-green-600 dark:text-green-400' },
        member: { bg: 'bg-gray-100 dark:bg-white/10', text: 'text-gray-600 dark:text-white/60' }
    };

    const getDiceBearUrl = (seed) => `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(seed)}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981`;

    const showUserCard = async (target, userId, initialData = {}) => {
        const rect = target.getBoundingClientRect();
        const cardWidth = 288;
        const cardHeight = 280;

        // For sidebar profile (at bottom), show card above it
        const isSidebarProfile = target.closest('.user-profile-trigger');

        let left, top;

        if (isSidebarProfile) {
            // Position above the profile section
            left = rect.left;
            top = rect.top - cardHeight - 8;

            // If card would go off top, position it to the right instead
            if (top < 10) {
                left = rect.right + 8;
                top = rect.top;
            }
        } else {
            // Default: position to the right
            left = rect.right + 8;
            top = rect.top;

            if (left + cardWidth > window.innerWidth - 10) {
                left = rect.left - cardWidth - 8;
            }

            if (top + cardHeight > window.innerHeight - 10) {
                top = window.innerHeight - cardHeight - 10;
            }
        }

        if (top < 10) top = 10;
        if (left < 10) left = 10;

        userHoverCard.style.left = `${left}px`;
        userHoverCard.style.top = `${top}px`;

        // Show card with initial data first
        renderUserCard({
            name: initialData.name || 'User',
            avatar: initialData.avatar || getDiceBearUrl(initialData.name || 'User'),
            status: initialData.status || 'offline',
            system_role: initialData.systemRole || null,
            created_at: null
        });

        userHoverCard.classList.remove('hidden');

        // Check cache first
        if (userCache[userId] && Date.now() - userCache[userId].ts < 60000) {
            renderUserCard(userCache[userId].data);
            return;
        }

        // Fetch real data from API
        try {
            const res = await fetch(`/chats/users/${userId}/profile-card`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            if (res.ok) {
                const data = await res.json();
                userCache[userId] = { data, ts: Date.now() };
                renderUserCard(data);
            }
        } catch (e) {
            // Silently fail - initial data is already shown
        }
    };

    const renderUserCard = (data) => {
        const avatarImg = userHoverCard.querySelector('#user-card-avatar img');
        const nameEl = userHoverCard.querySelector('#user-card-name');
        const roleEl = userHoverCard.querySelector('#user-card-role');
        const statusEl = userHoverCard.querySelector('#user-card-status');
        const badgeEl = userHoverCard.querySelector('#user-card-badge');
        const joinedEl = userHoverCard.querySelector('#user-card-joined');
        const aboutSection = userHoverCard.querySelector('#user-card-about');
        const aboutText = userHoverCard.querySelector('#user-card-about-text');
        const rolesSection = userHoverCard.querySelector('#user-card-roles-section');
        const rolesEl = userHoverCard.querySelector('#user-card-roles');
        const bannerEl = userHoverCard.querySelector('#user-card-banner');

        // Avatar
        avatarImg.src = data.avatar || getDiceBearUrl(data.name || 'User');
        avatarImg.alt = data.name || 'User';

        // Name
        nameEl.textContent = data.name || 'Unknown User';

        // Status
        statusEl.className = `absolute bottom-1 right-1 size-4 rounded-full border-2 border-white dark:border-sidebar-dark ${statusColors[data.status] || statusColors.offline}`;

        // Role badge (system role)
        const systemRole = data.system_role || data.role || '';
        if (systemRole === 'super_admin') {
            badgeEl.textContent = 'SUPER';
            badgeEl.className = 'px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400';
            badgeEl.classList.remove('hidden');
            bannerEl.className = 'h-16 bg-gradient-to-br from-red-500 via-orange-500 to-yellow-500 relative';
            roleEl.textContent = 'Super Admin';
        } else if (systemRole === 'admin') {
            badgeEl.textContent = 'ADMIN';
            badgeEl.className = 'px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400';
            badgeEl.classList.remove('hidden');
            bannerEl.className = 'h-16 bg-gradient-to-br from-blue-500 via-cyan-500 to-teal-500 relative';
            roleEl.textContent = 'Admin';
        } else if (systemRole === 'moderator') {
            badgeEl.textContent = 'MOD';
            badgeEl.className = 'px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-purple-100 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400';
            badgeEl.classList.remove('hidden');
            bannerEl.className = 'h-16 bg-gradient-to-br from-purple-500 via-indigo-500 to-blue-500 relative';
            roleEl.textContent = 'Moderator';
        } else if (systemRole === 'applicant') {
            badgeEl.classList.add('hidden');
            bannerEl.className = 'h-16 bg-gradient-to-br from-green-500 via-emerald-500 to-teal-500 relative';
            roleEl.textContent = 'Applicant';
        } else {
            badgeEl.classList.add('hidden');
            bannerEl.className = 'h-16 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 relative';
            roleEl.textContent = systemRole ? systemRole.charAt(0).toUpperCase() + systemRole.slice(1) : 'Member';
        }

        // Joined date
        if (data.joined_at) {
            const date = new Date(data.joined_at);
            joinedEl.textContent = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        } else if (data.created_at) {
            const date = new Date(data.created_at);
            joinedEl.textContent = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        } else {
            joinedEl.textContent = 'Unknown';
        }

        // About section
        if (data.about || data.bio) {
            aboutText.textContent = data.about || data.bio;
            aboutSection.classList.remove('hidden');
        } else {
            aboutSection.classList.add('hidden');
        }

        // Roles in conversation
        if (data.conversation_roles && data.conversation_roles.length > 0) {
            rolesEl.innerHTML = data.conversation_roles.map(role => {
                const colors = roleColors[role] || roleColors.member;
                return `<span class="px-2 py-0.5 rounded-full text-[10px] font-medium ${colors.bg} ${colors.text}">${role}</span>`;
            }).join('');
            rolesSection.classList.remove('hidden');
        } else {
            rolesSection.classList.add('hidden');
        }
    };

    const hideUserCard = () => {
        userHoverCard.classList.add('hidden');
        currentTarget = null;
    };

    // Event delegation for user avatars in messages
    const messagesContainer = document.getElementById('messages-container');
    if (messagesContainer) {
        messagesContainer.addEventListener('mouseenter', (e) => {
            if (!e.target || typeof e.target.closest !== 'function') return;
            const avatar = e.target.closest('[data-message-id] img.rounded-full[data-user-id]');
            if (!avatar || avatar === currentTarget) return;

            const messageEl = avatar.closest('[data-message-id]');
            if (!messageEl) return;

            clearTimeout(hoverTimeout);
            currentTarget = avatar;

            hoverTimeout = setTimeout(() => {
                const userId = avatar.dataset.userId;
                const userName = messageEl.querySelector('.font-bold')?.textContent?.trim();
                const userAvatar = avatar.src;

                if (userId) {
                    showUserCard(avatar, userId, {
                        name: userName,
                        avatar: userAvatar,
                        status: 'offline'
                    });
                }
            }, 400);
        }, true);

        messagesContainer.addEventListener('mouseleave', (e) => {
            if (!e.target || typeof e.target.closest !== 'function') return;
            if (e.target.closest('[data-message-id] img.rounded-full[data-user-id]')) {
                clearTimeout(hoverTimeout);
                hideUserCard();
            }
        }, true);
    }

    // Event delegation for member list in right sidebar
    const rightSidebar = document.querySelector('.chat-v2-right');
    if (rightSidebar) {
        rightSidebar.addEventListener('mouseenter', (e) => {
            if (!e.target || typeof e.target.closest !== 'function') return;
            // Don't show hover card if a dropdown is active
            if (isDropdownActive()) return;

            const memberItem = e.target.closest('.member-item');
            if (!memberItem || memberItem === currentTarget) return;

            // Don't hover on member with active dropdown
            if (memberItem.querySelector('.member-action-dropdown:not(.hidden)')) return;

            clearTimeout(hoverTimeout);
            currentTarget = memberItem;

            hoverTimeout = setTimeout(() => {
                // Double-check dropdown isn't active before showing
                if (isDropdownActive()) return;

                const userId = memberItem.dataset.userId;
                const userName = memberItem.querySelector('.font-medium')?.textContent?.trim();
                const userAvatar = memberItem.querySelector('img')?.src;
                const statusEl = memberItem.querySelector('[data-user-presence]');
                const statusClass = statusEl?.className || '';

                let status = 'offline';
                if (statusClass.includes('bg-green-500')) status = 'online';
                else if (statusClass.includes('bg-yellow-400')) status = 'idle';
                else if (statusClass.includes('bg-red-500')) status = 'dnd';

                const isMod = memberItem.querySelector('.bi-shield-check') !== null;
                const isAdmin = memberItem.textContent?.includes('Admin');

                if (userId) {
                    showUserCard(memberItem, userId, {
                        name: userName,
                        avatar: userAvatar,
                        status: status,
                        systemRole: isMod ? 'moderator' : (isAdmin ? 'admin' : 'applicant')
                    });
                }
            }, 400);
        }, true);

        rightSidebar.addEventListener('mouseleave', (e) => {
            if (!e.target || typeof e.target.closest !== 'function') return;
            if (e.target.closest('.member-item')) {
                clearTimeout(hoverTimeout);
                // Only hide if no dropdown is active
                if (!isDropdownActive()) {
                    hideUserCard();
                }
            }
        }, true);
    }

    // Hide on scroll or resize
    document.addEventListener('scroll', hideUserCard, true);
    window.addEventListener('resize', hideUserCard);

    // Event delegation for sidebar profile (own profile at bottom)
    // Exclude mic and theme toggle buttons
    document.addEventListener('mouseenter', (e) => {
        if (!e.target || typeof e.target.closest !== 'function') return;

        // Skip if hovering over mic or theme buttons
        if (e.target.closest('[data-open-chat-modal="mic"]') || e.target.closest('[onclick*="toggleChatTheme"]')) return;

        const profileTrigger = e.target.closest('.user-profile-trigger');
        if (!profileTrigger || profileTrigger === currentTarget) return;

        // Make sure we're not on the buttons inside the profile trigger
        if (e.target.closest('button')) return;

        clearTimeout(hoverTimeout);
        currentTarget = profileTrigger;

        hoverTimeout = setTimeout(() => {
            const userId = profileTrigger.dataset.userId;
            const userName = profileTrigger.dataset.userName;
            const userAvatar = profileTrigger.dataset.userAvatar;
            const userRole = profileTrigger.dataset.userRole;

            if (userId) {
                showUserCard(profileTrigger, userId, {
                    name: userName,
                    avatar: userAvatar,
                    status: 'online',
                    systemRole: userRole
                });
            }
        }, 400);
    }, true);

    document.addEventListener('mouseleave', (e) => {
        if (!e.target || typeof e.target.closest !== 'function') return;
        if (e.target.closest('.user-profile-trigger')) {
            clearTimeout(hoverTimeout);
            hideUserCard();
        }
    }, true);
});
</script>
