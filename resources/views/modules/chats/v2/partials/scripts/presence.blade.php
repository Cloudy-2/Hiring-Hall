{{-- Presence / Online Status Scripts --}}
<script>
    let newMessagesCount = 0;
    let isLoadingMore = false;
    let hasMoreMessages = true;
    
    function showNewMessagesIndicator() {
        const indicator = document.getElementById('new-messages-indicator');
        const textEl = document.getElementById('new-messages-text');
        if (!indicator) return;
        
        newMessagesCount++;
        if (textEl) textEl.textContent = newMessagesCount === 1 ? 'New message' : `${newMessagesCount} new messages`;
        indicator.classList.remove('hidden');
    }
    
    function hideNewMessagesIndicator() {
        document.getElementById('new-messages-indicator')?.classList.add('hidden');
        newMessagesCount = 0;
    }
    
    function scrollToBottom() {
        const scrollContainer = document.getElementById('messages-scroll-container');
        if (scrollContainer) scrollContainer.scrollTo({ top: scrollContainer.scrollHeight, behavior: 'smooth' });
        hideNewMessagesIndicator();
    }
    
    // Load more messages when scrolling to top
    async function loadMoreMessages() {
        if (isLoadingMore || !hasMoreMessages || !currentConversationId) return;
        
        isLoadingMore = true;
        const scrollContainer = document.getElementById('messages-scroll-container');
        const messagesContainer = document.getElementById('messages-container');
        if (!scrollContainer || !messagesContainer) {
            isLoadingMore = false;
            return;
        }
        
        // Get the oldest message ID currently displayed
        const firstMessage = messagesContainer.querySelector('[data-message-id]');
        const beforeId = firstMessage?.dataset.messageId;
        
        if (!beforeId) {
            isLoadingMore = false;
            hasMoreMessages = false;
            return;
        }
        
        // Show loading indicator at top
        const loadingEl = document.createElement('div');
        loadingEl.id = 'load-more-spinner';
        loadingEl.className = 'flex justify-center py-4';
        loadingEl.innerHTML = `
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-white/50">
                <div class="w-5 h-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                <span>Loading older messages...</span>
            </div>
        `;
        messagesContainer.insertBefore(loadingEl, messagesContainer.firstChild);
        
        // Remember scroll position
        const previousScrollHeight = scrollContainer.scrollHeight;
        
        try {
            const topicId = window.currentTopicId || null;
            let url = `/chats/${currentConversationId}/messages?before_id=${beforeId}&limit=20`;
            if (topicId) url += `&topic_id=${topicId}`;
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });
            
            if (!response.ok) throw new Error('Failed to load messages');
            
            const data = await response.json();
            const messages = data.data || data.messages || [];
            
            // Remove loading spinner
            loadingEl.remove();
            
            if (messages.length === 0) {
                hasMoreMessages = false;
                // Show "beginning of conversation" message
                const beginningEl = document.createElement('div');
                beginningEl.className = 'flex flex-col items-center py-6 text-center';
                beginningEl.innerHTML = `
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mb-3">
                        <i class="bi bi-chat-dots text-2xl text-primary"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-white/60">This is the beginning of the conversation</p>
                `;
                messagesContainer.insertBefore(beginningEl, messagesContainer.firstChild);
            } else {
                hasMoreMessages = data.next_page !== null;
                
                // Render messages (they come sorted by id ascending)
                const fragment = document.createDocumentFragment();
                messages.forEach(msg => {
                    const messageEl = createMessageElement(msg);
                    if (messageEl) fragment.appendChild(messageEl);
                });
                
                // Insert after loading spinner position (at top)
                const firstExistingMessage = messagesContainer.querySelector('[data-message-id]');
                if (firstExistingMessage) {
                    messagesContainer.insertBefore(fragment, firstExistingMessage);
                } else {
                    messagesContainer.appendChild(fragment);
                }
                
                // Maintain scroll position
                const newScrollHeight = scrollContainer.scrollHeight;
                scrollContainer.scrollTop = newScrollHeight - previousScrollHeight;
            }
        } catch (error) {
            console.error('[LoadMore] Error:', error);
            loadingEl.remove();
        }
        
        isLoadingMore = false;
    }
    
    // Create message element from data (similar to renderIncomingMessage but for historical messages)
    function createMessageElement(msg) {
        if (!msg) return null;
        
        // Skip system messages for now (they have different structure)
        if (msg.type === 'system') {
            return createSystemMessageElement(msg);
        }
        
        const isOwnMessage = msg.user?.id === authUserId;
        const userName = msg.user?.name || 'User';
        const avatar = msg.user?.avatar || (msg.user?.profile_photo_path
            ? `/storage/${msg.user.profile_photo_path}`
            : `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(userName)}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981`);
        
        const timestamp = new Date(msg.created_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        // Check if this is a GIF - must be type 'gif' OR a valid GIF URL (not HTML content)
        const isGifUrl = msg.body && !msg.body.includes('<') && (msg.body.startsWith('https://media.tenor.com') || msg.body.includes('tenor.com/') || (msg.body.startsWith('http') && msg.body.endsWith('.gif')));
        const isGif = msg.type === 'gif' || isGifUrl;
        const isVideo = msg.type === 'video' || (msg.attachments && msg.attachments.some(a => a.mime?.startsWith('video/')));
        const supportedVideoFormats = ['video/mp4', 'video/webm', 'video/ogg'];
        
        let bodyContent = '';
        
        if (isGif) {
            bodyContent = `<img src="${escapeHtml(msg.body || '')}" alt="GIF" class="max-w-xs rounded-lg cursor-pointer hover:opacity-90 transition" loading="lazy" onclick="window.open(this.src, '_blank')">`;
        } else if (isVideo && msg.attachments?.length) {
            const videoAtt = msg.attachments.find(a => a.mime?.startsWith('video/'));
            if (videoAtt) {
                const isSupportedFormat = supportedVideoFormats.includes(videoAtt.mime);
                const videoUrl = `/chats/attachments/${videoAtt.id}/stream`;
                const downloadUrl = `/chats/attachments/${videoAtt.id}/download`;
                const fileName = videoAtt.original_name || 'Video';
                const fileSize = videoAtt.size ? (videoAtt.size / 1024 / 1024).toFixed(1) + ' MB' : '';
                
                if (isSupportedFormat) {
                    bodyContent = `
                        <div class="max-w-lg">
                            <div class="relative rounded-xl overflow-hidden bg-black">
                                <video src="${videoUrl}" class="w-full max-h-80 object-contain" controls preload="metadata" playsinline></video>
                            </div>
                            <div class="flex items-center gap-2 mt-2 text-xs text-gray-500 dark:text-white/50">
                                <i class="bi bi-camera-video-fill text-purple-500"></i>
                                <span class="truncate">${escapeHtml(fileName)}</span>
                                ${fileSize ? `<span class="text-gray-400 dark:text-white/40">·</span><span>${fileSize}</span>` : ''}
                                <a href="${downloadUrl}" class="ml-auto text-purple-500 hover:text-purple-400"><i class="bi bi-download"></i></a>
                            </div>
                            ${msg.body ? `<p class="text-sm mt-2 text-gray-700 dark:text-white/90">${escapeHtml(msg.body)}</p>` : ''}
                        </div>
                    `;
                } else {
                    const ext = fileName.split('.').pop()?.toUpperCase() || 'VIDEO';
                    bodyContent = `
                        <div class="max-w-lg">
                            <button type="button" onclick="window.openVideoPreview('${videoUrl}', '${downloadUrl}', '${escapeHtml(fileName)}', '${fileSize}', '${videoAtt.mime}')" class="w-full block rounded-xl overflow-hidden bg-gradient-to-br from-purple-500/20 to-indigo-500/20 border border-purple-500/30 hover:border-purple-500/50 transition text-left">
                                <div class="flex items-center justify-center h-40 bg-black/20">
                                    <div class="text-center">
                                        <i class="bi bi-play-circle text-5xl text-purple-400"></i>
                                        <p class="text-xs text-purple-300 mt-2">${ext} format</p>
                                    </div>
                                </div>
                            </button>
                            <div class="flex items-center gap-2 mt-2 text-xs text-gray-500 dark:text-white/50">
                                <i class="bi bi-camera-video-fill text-purple-500"></i>
                                <span class="truncate">${escapeHtml(fileName)}</span>
                                ${fileSize ? `<span class="text-gray-400 dark:text-white/40">·</span><span>${fileSize}</span>` : ''}
                                <a href="${downloadUrl}" class="ml-auto text-purple-500 hover:text-purple-400"><i class="bi bi-download"></i></a>
                            </div>
                        </div>
                    `;
                }
            }
        } else if (msg.attachments?.length) {
            const attHtml = msg.attachments.map(a => {
                if (a.mime?.startsWith('image/')) {
                    const imgUrl = `/storage/${a.path}`;
                    return `<img src="${imgUrl}" alt="${escapeHtml(a.original_name || 'Image')}" class="max-w-xs rounded-lg cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src, '_blank')">`;
                }
                const fileUrl = `/chats/attachments/${a.id}/download`;
                return `<a href="${fileUrl}" target="_blank" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/15 transition">
                    <i class="bi bi-file-earmark text-lg"></i>
                    <span class="text-sm truncate">${escapeHtml(a.original_name || 'File')}</span>
                </a>`;
            }).join('');
            bodyContent = `
                <div class="grid gap-2">${attHtml}</div>
                ${msg.body ? `<p class="text-sm md:text-base leading-relaxed text-gray-700 dark:text-white/90 whitespace-pre-line break-words mt-2">${escapeHtml(msg.body)}</p>` : ''}
            `;
        } else {
            const isRichText = msg.body && /<[^>]+>/.test(msg.body);
            if (isRichText) {
                bodyContent = `<div class="text-sm md:text-base leading-relaxed text-gray-700 dark:text-white/90 break-words chat-message-body rich-text-content">${typeof sanitizeHtmlForDisplay === 'function' ? sanitizeHtmlForDisplay(msg.body) : msg.body}</div>`;
            } else {
                bodyContent = `<p class="text-sm md:text-base leading-relaxed text-gray-700 dark:text-white/90 whitespace-pre-line break-words">${escapeHtml(msg.body || '')}</p>`;
            }
        }
        
        const messageEl = document.createElement('div');
        messageEl.className = 'group relative flex gap-3 rounded-lg p-3 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors';
        messageEl.dataset.messageId = msg.id;
        messageEl.dataset.isPinned = msg.is_pinned ? '1' : '0';
        
        const nameStyle = isOwnMessage ? 'style="color: #2563eb;"' : '';
        const isStaff = ['moderator', 'admin', 'super_admin'].includes(msg.user?.role);
        const staffBadge = isStaff ? `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-500/20 text-blue-400"><i class="bi bi-patch-check-fill mr-0.5"></i>${msg.user?.role === 'super_admin' ? 'Super Admin' : (msg.user?.role === 'admin' ? 'Admin' : 'Moderator')}</span>` : '';
        
        messageEl.innerHTML = `
            <img src="${avatar}" class="size-10 flex-shrink-0 rounded-full object-cover" alt="${escapeHtml(userName)}" onerror="this.src='https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(userName)}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981'">
            <div class="flex flex-1 flex-col gap-1 min-w-0">
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                    <p class="text-sm md:text-base font-bold" ${nameStyle}>${escapeHtml(userName)}</p>
                    ${staffBadge}
                    <p class="text-xs md:text-sm text-gray-500 dark:text-[#9d9db9]">${timestamp}</p>
                </div>
                ${bodyContent}
            </div>
        `;
        
        return messageEl;
    }
    
    function createSystemMessageElement(msg) {
        const timestamp = new Date(msg.created_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        const messageEl = document.createElement('div');
        messageEl.className = 'flex flex-col items-center py-2';
        messageEl.innerHTML = `
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gray-100 dark:bg-white/5 text-xs text-gray-500 dark:text-white/50">
                <i class="bi bi-info-circle"></i>
                <span>${escapeHtml(msg.body || '')}</span>
            </div>
            <span class="text-[10px] text-gray-400 dark:text-white/40 mt-1">${timestamp}</span>
        `;
        return messageEl;
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        const scrollContainer = document.getElementById('messages-scroll-container');
        document.getElementById('scroll-to-bottom-btn')?.addEventListener('click', scrollToBottom);
        
        scrollContainer?.addEventListener('scroll', () => {
            const isNearBottom = scrollContainer.scrollHeight - scrollContainer.scrollTop - scrollContainer.clientHeight < 150;
            if (isNearBottom && newMessagesCount > 0) hideNewMessagesIndicator();
            
            // Load more when scrolled to top
            if (scrollContainer.scrollTop < 100 && hasMoreMessages && !isLoadingMore) {
                loadMoreMessages();
            }
        });
        
        if (scrollContainer) {
            scrollContainer.scrollTop = scrollContainer.scrollHeight;
            setTimeout(() => scrollContainer.scrollTop = scrollContainer.scrollHeight, 100);
            setTimeout(() => scrollContainer.scrollTop = scrollContainer.scrollHeight, 500);
        }
    });

    function handleRealtimeReaction(messageId, emoji, action, userName) {
        const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
        if (!messageEl) return;

        let reactionsDiv = messageEl.querySelector('.reactions-container');
        
        if (action === 'added') {
            if (!reactionsDiv) {
                reactionsDiv = document.createElement('div');
                reactionsDiv.className = 'reactions-container flex flex-wrap gap-1 mt-1';
                const bodyEl = messageEl.querySelector('.whitespace-pre-line');
                bodyEl?.parentElement?.insertBefore(reactionsDiv, bodyEl.nextSibling);
            }
            
            let existingBtn = reactionsDiv.querySelector(`[data-reaction="${emoji}"]`);
            if (existingBtn) {
                const countEl = existingBtn.querySelector('span:last-child');
                if (countEl) countEl.textContent = parseInt(countEl.textContent) + 1;
            } else {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'reaction-btn inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 transition';
                btn.dataset.reaction = emoji;
                btn.title = userName;
                btn.innerHTML = `<span>${emoji}</span><span class="text-gray-600 dark:text-white/70">1</span>`;
                reactionsDiv.appendChild(btn);
            }
        } else if (action === 'removed' && reactionsDiv) {
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
    }

    function markConversationAsRead(conversationId) {
        if (!conversationId) return;
        fetch(`/chats/${conversationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        }).then(response => {
            if (response.ok) {
                console.log('[Read] Marked conversation as read:', conversationId);
                updateUnreadBadge(conversationId, 0);
            }
        }).catch(err => console.error('[Read] Error:', err));
    }

    onEchoReady((Echo) => {
        console.log('[Echo] Ready');

        if (currentConversationId) {
            joinConversationChannel(currentConversationId);
            markConversationAsRead(currentConversationId);
        }

        Echo.private(`users.${authUserId}`)
            .listen('.conversation.updated', (e) => {
                if (e.conversation_id && typeof e.unread_count !== 'undefined') updateUnreadBadge(e.conversation_id, e.unread_count);
                if (e.conversation_id && e.last_message) updateDmListItem(e.conversation_id, e.last_message, e.title);
            });

        Echo.join('chat.presence')
            .here((users) => users.forEach(user => updateUserPresenceUI(user.id, 'online')))
            .joining((user) => updateUserPresenceUI(user.id, 'online'))
            .leaving((user) => updateUserPresenceUI(user.id, 'offline'))
            .listen('.presence.changed', (e) => updateUserPresenceUI(e.user_id, e.status, e.conversation_id, e.topic_slug));
    });

    function updateUnreadBadge(conversationId, unreadCount) {
        const serverItem = document.querySelector(`[data-conversation-id="${conversationId}"]`);
        if (serverItem) {
            let badge = serverItem.querySelector('.unread-badge');
            if (unreadCount > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'unread-badge absolute -bottom-1 -right-1 flex min-w-[1.4rem] items-center justify-center rounded-full border border-sidemenu-light dark:border-sidemenu-dark bg-red-500 px-1 text-[11px] font-semibold leading-none text-white';
                    serverItem.appendChild(badge);
                }
                badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            } else if (badge) {
                badge.remove();
            }
            moveServerToTop(conversationId);
        }

        const dmItem = document.querySelector(`[data-dm-conversation-id="${conversationId}"]`);
        if (dmItem) {
            let dmBadge = dmItem.querySelector('.dm-unread-badge');
            if (unreadCount > 0) {
                if (!dmBadge) {
                    dmBadge = document.createElement('span');
                    dmBadge.className = 'dm-unread-badge ml-auto flex min-w-[1.2rem] items-center justify-center rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] font-semibold text-white';
                    dmItem.appendChild(dmBadge);
                }
                dmBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                dmBadge.style.display = 'flex';
            } else if (dmBadge) {
                dmBadge.remove();
            }
        }
    }

    function moveServerToTop(conversationId) {
        const serverItem = document.querySelector(`.chat-v2-sidemenu [data-conversation-id="${conversationId}"]`);
        if (!serverItem) return;
        
        const serversContainer = serverItem.parentElement;
        if (!serversContainer) return;
        
        const serverLinks = serversContainer.querySelectorAll('a[data-conversation-id]');
        if (serverLinks.length === 0 || serverLinks[0] === serverItem) return;
        
        serverItem.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
        serverItem.style.transform = 'scale(1.1)';
        serverItem.style.opacity = '0.7';
        
        setTimeout(() => {
            serversContainer.insertBefore(serverItem, serverLinks[0]);
            serverItem.style.transform = 'scale(1)';
            serverItem.style.opacity = '1';
            serverItem.classList.add('animate-pulse');
            setTimeout(() => {
                serverItem.classList.remove('animate-pulse');
                serverItem.style.transition = '';
            }, 1000);
        }, 150);
    }

    function updateDmListItem(conversationId, lastMessage, title) {
        const dmItem = document.querySelector(`[data-dm-conversation-id="${conversationId}"]`);
        if (!dmItem) return;

        const subtitleEl = dmItem.querySelector('.truncate.text-xs');
        if (subtitleEl) subtitleEl.textContent = lastMessage.length > 30 ? lastMessage.substring(0, 30) + '...' : lastMessage;

        const dmList = dmItem.parentElement;
        if (dmList && dmList.firstElementChild !== dmItem) {
            dmList.insertBefore(dmItem, dmList.firstElementChild);
            dmItem.classList.add('bg-primary/10');
            setTimeout(() => dmItem.classList.remove('bg-primary/10'), 2000);
        }
    }

    function updateUserPresenceUI(userId, status, conversationId = null, topicSlug = null) {
        const onlineList = document.getElementById('online-members-list');
        const offlineList = document.getElementById('offline-members-list');
        const memberItem = document.querySelector(`.chat-v2-right .member-item[data-user-id="${userId}"]`);
        
        // Handle invisible status (hidden moderators)
        if (status === 'invisible') {
            // Check if current user is a moderator
            const isCurrentUserModerator = window.isCurrentUserModerator || false;
            
            if (isCurrentUserModerator) {
                // Moderators can still see the hidden user, but show indicator
                if (memberItem) {
                    // Add hidden indicator if not already present
                    const nameContainer = memberItem.querySelector('.flex.items-center.gap-1\\.5');
                    if (nameContainer && !nameContainer.querySelector('.status-hidden-indicator')) {
                        const indicator = document.createElement('span');
                        indicator.className = 'status-hidden-indicator inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[9px] font-medium bg-gray-500/20 text-gray-400 dark:text-white/50';
                        indicator.innerHTML = '<i class="bi bi-eye-slash text-[8px]"></i> Hidden';
                        nameContainer.appendChild(indicator);
                    }
                    
                    // Update presence dot to grey
                    document.querySelectorAll(`[data-user-presence="${userId}"]`).forEach(dot => {
                        dot.className = dot.className.replace(/bg-(green|yellow|red|slate)-\d+/g, '');
                        dot.classList.add('bg-slate-500');
                    });
                    
                    // Move to offline list
                    if (onlineList && offlineList && onlineList.contains(memberItem)) {
                        memberItem.classList.add('opacity-60');
                        offlineList.appendChild(memberItem);
                    }
                    
                    const statusText = memberItem.querySelector('.member-status');
                    if (statusText) statusText.textContent = 'Hidden';
                }
            } else {
                // Non-moderators: hide the member completely
                if (memberItem) {
                    memberItem.style.display = 'none';
                    memberItem.dataset.hiddenByInvisible = 'true';
                }
            }
            
            // Update counts
            updateMemberCounts();
            return;
        }
        
        // Handle coming back online from invisible
        if (memberItem && memberItem.dataset.hiddenByInvisible === 'true') {
            memberItem.style.display = '';
            delete memberItem.dataset.hiddenByInvisible;
        }
        
        // Remove hidden indicator if present (user is now visible)
        if (memberItem) {
            const hiddenIndicator = memberItem.querySelector('.status-hidden-indicator');
            if (hiddenIndicator) hiddenIndicator.remove();
        }
        
        // Update presence dots
        document.querySelectorAll(`[data-user-presence="${userId}"]`).forEach(dot => {
            dot.className = dot.className.replace(/bg-(green|yellow|red|slate)-\d+/g, '');
            dot.classList.add(status === 'online' ? 'bg-green-500' : status === 'idle' ? 'bg-yellow-400' : status === 'dnd' ? 'bg-red-500' : 'bg-slate-500');
        });

        if (!onlineList || !offlineList || !memberItem) return;

        const isOnline = ['online', 'idle', 'dnd'].includes(status);
        const currentlyInOnline = onlineList.contains(memberItem);
        const currentlyInOffline = offlineList.contains(memberItem);

        const statusText = memberItem.querySelector('.member-status');
        if (statusText) statusText.textContent = status.charAt(0).toUpperCase() + status.slice(1);

        // Update location display for moderators
        if (window.isCurrentUserModerator && memberItem) {
            updateMemberLocation(memberItem, userId, conversationId, topicSlug);
        }

        if (isOnline && currentlyInOffline) {
            memberItem.classList.remove('opacity-60');
            onlineList.appendChild(memberItem);
        } else if (!isOnline && currentlyInOnline) {
            memberItem.classList.add('opacity-60');
            offlineList.appendChild(memberItem);
        }
        
        updateMemberCounts();
    }
    
    // Update member's current location display (moderator only)
    function updateMemberLocation(memberItem, userId, conversationId, topicSlug) {
        // If conversationId is undefined (not provided in event), keep existing badge
        // Only remove/update if conversationId is explicitly null or a valid value
        if (conversationId === undefined) return;
        
        // Remove existing location badge
        const existingBadge = memberItem.querySelector('.member-location-badge');
        if (existingBadge) existingBadge.remove();
        
        // Don't show location if explicitly null (user cleared location or offline)
        if (!conversationId) return;
        
        const currentConvId = window.currentConversationId;
        const currentTopic = window.currentTopicSlug || null;
        
        // Check if user is in the same channel/topic as current view
        const isInCurrentChannel = conversationId === currentConvId && topicSlug === currentTopic;
        
        // Create location badge
        const badge = document.createElement('div');
        badge.className = 'member-location-badge mt-0.5';
        
        if (isInCurrentChannel) {
            badge.innerHTML = `
                <span class="inline-flex items-center gap-1 text-[10px] text-green-500 dark:text-green-400">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>Active here</span>
                </span>
            `;
        } else {
            // Show which channel they're in (clickable)
            const channelName = topicSlug ? `#${topicSlug}` : '#general';
            badge.innerHTML = `
                <a href="/chats/v2?conversation=${conversationId}${topicSlug ? '&topic=' + topicSlug : ''}" 
                   class="inline-flex items-center gap-1 text-[10px] text-primary hover:underline cursor-pointer"
                   title="Go to ${channelName}">
                    <i class="bi bi-hash"></i>
                    <span>${escapeHtml(topicSlug || 'general')}</span>
                </a>
            `;
        }
        
        // Insert after the name container
        const nameContainer = memberItem.querySelector('.min-w-0');
        if (nameContainer) {
            nameContainer.appendChild(badge);
        }
    }
    
    function updateMemberCounts() {
        const onlineList = document.getElementById('online-members-list');
        const offlineList = document.getElementById('offline-members-list');
        const onlineCount = document.getElementById('online-count');
        const offlineCount = document.getElementById('offline-count');
        
        if (onlineCount && onlineList) {
            // Count only visible members
            onlineCount.textContent = onlineList.querySelectorAll('.member-item:not([style*="display: none"])').length;
        }
        if (offlineCount && offlineList) {
            offlineCount.textContent = offlineList.querySelectorAll('.member-item:not([style*="display: none"])').length;
        }
    }

    // Heartbeat
    let heartbeatInterval = null;

    function sendHeartbeat() {
        const conversationId = window.currentConversationId || null;
        const topicSlug = window.currentTopicSlug || null;
        
        fetch('/chats/presence/heartbeat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ 
                platform: 'web',
                conversation_id: conversationId,
                topic_slug: topicSlug
            }),
        }).catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', () => {
        sendHeartbeat();
        heartbeatInterval = setInterval(sendHeartbeat, 2 * 60 * 1000);
    });

    window.addEventListener('beforeunload', () => {
        if (heartbeatInterval) clearInterval(heartbeatInterval);
        navigator.sendBeacon('/chats/presence/status', JSON.stringify({
            status: 'offline',
            _token: document.querySelector('meta[name="csrf-token"]')?.content || '',
        }));
    });

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            fetch('/chats/presence/status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ status: 'idle' }),
            }).catch(() => {});
        } else {
            sendHeartbeat();
        }
    });
</script>
