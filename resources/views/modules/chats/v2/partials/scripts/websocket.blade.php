{{-- WebSocket / Echo Real-time Scripts --}}
<div id="chat-toast-container" class="chat-toast"></div>
<div id="ws-connection-banner" class="hidden fixed top-0 left-0 right-0 z-50 bg-amber-500 text-white text-center py-2 text-sm">
    <i class="bi bi-wifi-off mr-2"></i>
    <span>Real-time updates experiencing delay. <button onclick="window.location.reload()" class="underline font-medium">Refresh page</button></span>
</div>
<script>
    const authUserId = @json(auth()->id());
    const currentConversationId = @json($selectedConversation?->id);
    const currentTopicSlug = @json(request()->query('topic'));
    window.currentConversationId = currentConversationId; // Make available globally for other scripts
    window.currentTopicSlug = currentTopicSlug; // Make available globally for presence/location tracking
    const isCurrentUserModerator = @json(auth()->user()->isModerator());
    window.isCurrentUserModerator = isCurrentUserModerator; // Make available globally for presence scripts
    const removedUserIds = new Set();
    let currentEchoChannelName = null;
    let typingTimeoutId = null;
    let lastTypingSentAt = 0;
    let wsConnectionHealthy = true;
    let wsReconnectAttempts = 0;

    function getLocallyBlockedUsers() {
        try {
            const raw = localStorage.getItem('chat_blocked_users') || '[]';
            return JSON.parse(raw).map(String);
        } catch {
            return [];
        }
    }

    function isUserLocallyBlocked(userId) {
        if (!userId) return false;
        return getLocallyBlockedUsers().includes(String(userId));
    }

    window.applyLocalChatBlocks = function() {
        const blockedUsers = new Set(getLocallyBlockedUsers());

        document.querySelectorAll('#messages-container [data-message-id]').forEach((messageEl) => {
            const avatarUser = messageEl.querySelector('[data-user-id]')?.getAttribute('data-user-id');
            if (avatarUser && blockedUsers.has(String(avatarUser))) {
                messageEl.classList.add('hidden');
            }
        });

        document.querySelectorAll('.member-item[data-user-id]').forEach((memberEl) => {
            const userId = memberEl.getAttribute('data-user-id');
            if (userId && blockedUsers.has(String(userId))) {
                memberEl.classList.add('hidden');
            }
        });
    };

    function showConnectionBanner(show) {
        const banner = document.getElementById('ws-connection-banner');
        if (banner) {
            banner.classList.toggle('hidden', !show);
        }
    }

    function monitorWebSocketConnection() {
        if (!window.Echo?.connector?.pusher) return;
        
        const pusher = window.Echo.connector.pusher;
        
        pusher.connection.bind('connected', () => {
            wsConnectionHealthy = true;
            wsReconnectAttempts = 0;
            showConnectionBanner(false);
        });
        
        pusher.connection.bind('disconnected', () => {
            wsConnectionHealthy = false;
            wsReconnectAttempts++;
            if (wsReconnectAttempts >= 2) {
                showConnectionBanner(true);
            }
        });
        
        pusher.connection.bind('error', () => {
            wsConnectionHealthy = false;
            showConnectionBanner(true);
        });
        
        pusher.connection.bind('unavailable', () => {
            wsConnectionHealthy = false;
            showConnectionBanner(true);
        });
    }

    function showChatToast(message, type = 'info', duration = 4000) {
        const container = document.getElementById('chat-toast-container');
        if (!container) return;
        
        const icons = {
            error: '<i class="bi bi-x-circle-fill"></i>',
            success: '<i class="bi bi-check-circle-fill"></i>',
            warning: '<i class="bi bi-exclamation-triangle-fill"></i>',
            info: '<i class="bi bi-info-circle-fill"></i>',
            bot: '<i class="bi bi-robot"></i>'
        };
        
        const toast = document.createElement('div');
        toast.className = `chat-toast-item ${type}`;
        toast.innerHTML = `<span class="chat-toast-icon">${icons[type] || icons.info}</span><span>${message}</span>`;
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'toastSlideOut 0.3s ease-in forwards';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    function onEchoReady(callback) {
        if (window.Echo) {
            callback(window.Echo);
            monitorWebSocketConnection();
            return;
        }
        let attempts = 0;
        const interval = setInterval(() => {
            if (window.Echo) {
                clearInterval(interval);
                callback(window.Echo);
                monitorWebSocketConnection();
            } else if (attempts++ > 300) {
                clearInterval(interval);
                showConnectionBanner(true);
            }
        }, 100);
    }

    // Listen for being added to a group
    function joinUserChannel() {
        if (!window.Echo || !authUserId) return;
        
        window.Echo
            .private(`users.${authUserId}`)
            .listen('.added.to.group', (e) => {
                showChatToast(`You've been added to ${e.conversation_name}`, 'success', 5000);
                setTimeout(() => window.location.reload(), 1500);
            })
            .listen('.removed.from.group', (e) => {
                showChatToast(`You've been removed from ${e.conversation_name}`, 'error', 5000);
                setTimeout(() => window.location.reload(), 1500);
            })
            .listen('.user.mentioned', (e) => {
                updateChannelMentionBadge(e.conversation_id, e.topic_slug || 'general', e.unread_count);
                if (e.unread_count > 0 && String(e.conversation_id) !== String(currentConversationId)) {
                    showChatToast(`You were mentioned in ${e.conversation_name}`, 'info', 4000);
                }
            });
    }
    
    function updateChannelMentionBadge(conversationId, topicSlug, count) {
        const badge = document.querySelector(`.channel-mention-badge[data-conversation-id="${conversationId}"][data-topic-id="${topicSlug}"]`);
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 9 ? '@9+' : `@${count}`;
                badge.style.display = 'flex';
                badge.classList.remove('hidden');
            } else {
                badge.style.display = 'none';
                badge.classList.add('hidden');
            }
        }
    }
    
    function updateMentionBadge(conversationId, count) {
        const badges = document.querySelectorAll(`.mention-badge[data-conversation-id="${conversationId}"]`);
        console.log('updateMentionBadge', conversationId, count, 'found badges:', badges.length);
        badges.forEach(badge => {
            if (count > 0) {
                badge.textContent = count > 9 ? '@9+' : `@${count}`;
                badge.style.display = 'flex';
                badge.classList.remove('hidden');
                const rect = badge.getBoundingClientRect();
                console.log('Badge rect:', {
                    top: rect.top,
                    left: rect.left,
                    width: rect.width,
                    height: rect.height,
                    backgroundColor: getComputedStyle(badge).backgroundColor,
                    color: getComputedStyle(badge).color,
                    textContent: badge.textContent
                });
            } else {
                badge.style.display = 'none';
                badge.classList.add('hidden');
            }
        });
    }
    
    function loadMentionCounts() {
        fetch('/chats/mentions')
            .then(r => r.json())
            .then(data => {
                console.log('loadMentionCounts response:', data);
                const mentions = data.mentions || {};
                Object.entries(mentions).forEach(([key, count]) => {
                    const parts = key.split('_');
                    const convId = parts[0];
                    const topicSlug = parts.slice(1).join('_') || 'general';
                    updateChannelMentionBadge(convId, topicSlug, count);
                });
            })
            .catch(err => console.error('loadMentionCounts error:', err));
    }
    
    function markChannelMentionsAsRead(conversationId, topicSlug) {
        const topic = topicSlug || 'general';
        fetch(`/chats/${conversationId}/mentions/read`, { 
            method: 'POST', 
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ topic: topic === 'general' ? null : topic })
        }).catch(() => {});
        updateChannelMentionBadge(conversationId, topic, 0);
    }
    window.markChannelMentionsAsRead = markChannelMentionsAsRead;
    
    function markMentionsAsRead(conversationId) {
        fetch(`/chats/${conversationId}/mentions/read`, { 
            method: 'POST', 
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content } 
        }).catch(() => {});
        updateMentionBadge(conversationId, 0);
    }
    
    onEchoReady(() => {
        joinUserChannel();
        loadMentionCounts();
        window.applyLocalChatBlocks?.();
    });

    document.querySelectorAll('.chat-v2-sidemenu a[data-conversation-id]').forEach(link => {
        link.addEventListener('click', () => {
            const convId = link.dataset.conversationId;
            if (convId) markMentionsAsRead(convId);
        });
    });

    function joinConversationChannel(convId) {
        if (!window.Echo || !convId) return;

        if (currentEchoChannelName) window.Echo.leave(currentEchoChannelName);
        currentEchoChannelName = `conversations.${convId}`;

        window.Echo
            .private(currentEchoChannelName)
            .listen('.message.sent', (e) => {
                const msg = e.message;
                if (msg.user?.id === authUserId) return;
                if (String(msg.conversation_id) !== String(convId)) return;
                
                // Filter by topic if we're viewing a specific topic
                const currentTopic = window.currentTopicId;
                if (currentTopic && msg.topic_id && String(msg.topic_id) !== String(currentTopic)) return;
                if (currentTopic && !msg.topic_id) return;
                if (!currentTopic && msg.topic_id) return;
                
                renderIncomingMessage(msg);
                moveServerToTop(convId);
            })
            .listen('.conversation.typing', (e) => {
                if (!e || String(e.conversation_id) !== String(convId)) return;
                if (Number(e.user_id) === Number(authUserId)) return;
                showTypingIndicator(e.name || 'Someone');
            })
            .listen('.message.reacted', (e) => {
                if (!e || String(e.conversation_id) !== String(convId)) return;
                if (Number(e.user_id) === Number(authUserId)) return;
                handleRealtimeReaction(e.message_id, e.reaction, e.action, e.user_name);
            })
            .listen('.message.deleted', (e) => {
                if (!e || !e.message_id) return;
                const messageEl = document.querySelector(`[data-message-id="${e.message_id}"]`);
                if (!messageEl) return;
                
                if (e.bulk_clear) {
                    messageEl.remove();
                    return;
                }
                
                messageEl.querySelector('.grid.gap-2')?.remove();
                messageEl.querySelector('.reactions-container')?.remove();
                
                let bodyEl = messageEl.querySelector('.whitespace-pre-line, .chat-message-body');
                const contentWrapper = messageEl.querySelector('.flex.flex-1.flex-col, .msg-content-wrapper');
                
                if (!bodyEl && contentWrapper) {
                    bodyEl = document.createElement('p');
                    bodyEl.className = 'text-sm md:text-base leading-relaxed chat-message-body';
                    contentWrapper.appendChild(bodyEl);
                }
                
                const isOwnDeletedMessage = Number(e.deleted_by_user_id) === Number(authUserId);
                
                if (e.deleted_by_moderator) {
                    if (bodyEl) bodyEl.innerHTML = `<span class="italic text-gray-400 dark:text-white/40">A moderator removed this message</span>`;
                } else if (isOwnDeletedMessage) {
                    messageEl.remove();
                    return;
                } else if (isCurrentUserModerator) {
                    if (bodyEl) bodyEl.innerHTML = `<span class="italic text-gray-400 dark:text-white/40">${escapeHtml(e.deleted_by_user_name)} deleted a message</span>`;
                } else {
                    messageEl.remove();
                    return;
                }
                
                const actionBar = messageEl.querySelector('.absolute.-top-3');
                if (actionBar) actionBar.remove();
            })
            .listen('.messages.cleared', (e) => {
                if (!e || !e.message_ids || !Array.isArray(e.message_ids)) return;
                e.message_ids.forEach(id => {
                    const messageEl = document.querySelector(`[data-message-id="${id}"]`);
                    if (messageEl) messageEl.remove();
                });
            })
            .listen('.user.muted', (e) => {
                if (Number(e?.user_id) === Number(authUserId)) {
                    showChatToast('You have been muted' + (e.muted_until ? ` until ${new Date(e.muted_until).toLocaleString()}` : ''), 'warning');
                }
                if (typeof updateMemberMuteState === 'function') {
                    updateMemberMuteState(e.user_id, true, e.muted_until, e.user_name);
                }
            })
            .listen('.user.unmuted', (e) => {
                if (Number(e?.user_id) === Number(authUserId)) {
                    showChatToast('You have been unmuted', 'success');
                }
                if (typeof updateMemberMuteState === 'function') {
                    updateMemberMuteState(e.user_id, false, null, e.user_name);
                }
            })
            .listen('.user.kicked', (e) => {
                if (Number(e?.user_id) === Number(authUserId)) {
                    showChatToast('You have been removed from this conversation', 'error', 5000);
                    setTimeout(() => window.location.href = '/chats/v2', 2000);
                } else {
                    removedUserIds.add(String(e.user_id));
                    hideModActionsForUser(e.user_id);
                    renderSystemMessage(`${e.user_name} was removed by a moderator`);
                    const memberItem = document.querySelector(`.member-item[data-user-id="${e.user_id}"]`);
                    if (memberItem) {
                        memberItem.remove();
                        const onlineCount = document.getElementById('online-count');
                        const offlineCount = document.getElementById('offline-count');
                        if (onlineCount) onlineCount.textContent = document.querySelectorAll('#online-members-list .member-item').length;
                        if (offlineCount) offlineCount.textContent = document.querySelectorAll('#offline-members-list .member-item').length;
                    }
                }
            })
            .listen('.member.added', (e) => {
                if (!e?.added_users?.length) return;
                e.added_users.forEach(u => removedUserIds.delete(String(u.id)));
                const names = e.added_users.map(u => u.name);
                const namesText = names.length > 2 
                    ? names.slice(0, 2).join(', ') + ' and ' + (names.length - 2) + ' others'
                    : names.join(' and ');
                renderSystemMessage(`A moderator added ${namesText} to the group`);
            })
            .listen('.member.left', (e) => {
                if (!e?.user_name) return;
                removedUserIds.add(String(e.user_id));
                hideModActionsForUser(e.user_id);
                renderSystemMessage(`${e.user_name} left the group`);
                const memberItem = document.querySelector(`.member-item[data-user-id="${e.user_id}"]`);
                if (memberItem) {
                    memberItem.remove();
                    const onlineCount = document.getElementById('online-count');
                    const offlineCount = document.getElementById('offline-count');
                    if (onlineCount) onlineCount.textContent = document.querySelectorAll('#online-members-list .member-item').length;
                    if (offlineCount) offlineCount.textContent = document.querySelectorAll('#offline-members-list .member-item').length;
                }
            })
            .listen('.message.pinned', (e) => {
                if (!e || String(e.conversation_id) !== String(convId)) return;
                const msgEl = document.querySelector(`[data-message-id="${e.message_id}"]`);
                if (e.action === 'pinned' && e.message_id) {
                    updatePinnedMessageBar(e.message_id, e.message_body || '', e.user_name || '');
                    if (msgEl) msgEl.dataset.isPinned = '1';
                    if (typeof updateAllPinButtons === 'function') updateAllPinButtons();
                } else if (e.action === 'unpinned') {
                    if (msgEl) msgEl.dataset.isPinned = '0';
                    // Refresh to show next latest pin
                    if (typeof refreshPinnedMessageBar === 'function') refreshPinnedMessageBar();
                    if (typeof updateAllPinButtons === 'function') updateAllPinButtons();
                }
            })
            .listen('.call.started', (e) => {
                if (!e?.room_name || String(e.conversation_id) !== String(convId)) return;
                // Show incoming call modal for others (not the starter)
                if (Number(e.caller_id) !== Number(authUserId)) {
                    // Render system message for others
                    renderCallSystemMessage(e.caller_name, e.call_type, 'started');
                    if (typeof window.handleIncomingCall === 'function') window.handleIncomingCall(e);
                }
            })
            .listen('.call.ended', (e) => {
                if (!e || String(e.conversation_id) !== String(convId)) return;
                // Dismiss incoming call modal if it's showing for this call
                if (typeof window.dismissIncomingCall === 'function') {
                    window.dismissIncomingCall(e.room_name);
                }
                // Only render for users who didn't trigger the end (they'll see it on their action)
                if (Number(e.ended_by_user_id) !== Number(authUserId)) {
                    renderCallSystemMessage(null, null, 'ended', e.duration_text);
                }
            });

        console.log(`[Echo] Joined channel: ${currentEchoChannelName}`);
    }

    function renderIncomingMessage(msg) {
        const messagesContainer = document.getElementById('messages-container');
        if (!messagesContainer) return;
        if (isUserLocallyBlocked(msg.user?.id)) return;

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
                const ext = videoAtt.original_name?.split('.').pop()?.toUpperCase() || 'VIDEO';
                // Use stream route for video playback (route is under /chats prefix)
                const videoUrl = `/chats/attachments/${videoAtt.id}/stream`;
                const downloadUrl = `/chats/attachments/${videoAtt.id}/download`;
                const fileName = videoAtt.original_name || videoAtt.file_name || 'Video';
                const fileSize = videoAtt.size ? (videoAtt.size / 1024 / 1024).toFixed(1) + ' MB' : '';
                
                if (isSupportedFormat) {
                    // Supported format - play inline with controls (same as server-rendered)
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
                    // Unsupported format - show preview card with modal
                    const previewClick = `window.openVideoPreview('${videoUrl}', '${downloadUrl}', '${escapeHtml(fileName)}', '${fileSize}', '${videoAtt.mime || ''}')`;
                    bodyContent = `
                        <div class="max-w-lg">
                            <button type="button" onclick="${previewClick}" class="w-full block rounded-xl overflow-hidden bg-gradient-to-br from-purple-500/20 to-indigo-500/20 border border-purple-500/30 hover:border-purple-500/50 transition text-left">
                                <div class="flex items-center justify-center h-40 bg-black/20">
                                    <div class="text-center">
                                        <i class="bi bi-play-circle text-5xl text-purple-400"></i>
                                        <p class="text-xs text-purple-300 mt-2">${ext} format</p>
                                        <p class="text-[10px] text-white/50 mt-1">Click to preview</p>
                                    </div>
                                </div>
                            </button>
                            <div class="flex items-center gap-2 mt-2 text-xs text-gray-500 dark:text-white/50">
                                <i class="bi bi-camera-video-fill text-purple-500"></i>
                                <span class="truncate">${escapeHtml(fileName)}</span>
                                ${fileSize ? `<span class="text-gray-400 dark:text-white/40">·</span><span>${fileSize}</span>` : ''}
                                <a href="${downloadUrl}" class="ml-auto text-purple-500 hover:text-purple-400"><i class="bi bi-download"></i></a>
                            </div>
                            ${msg.body ? `<p class="text-sm mt-2 text-gray-700 dark:text-white/90">${escapeHtml(msg.body)}</p>` : ''}
                        </div>
                    `;
                }
            }
        } else if (msg.attachments?.length) {
            const attHtml = msg.attachments.map(a => {
                const streamUrl = `/chats/attachments/${a.id}/stream`;
                const downloadUrl = `/chats/attachments/${a.id}/download`;
                if (a.mime?.startsWith('image/')) {
                    return `<div class="img-skeleton rounded-lg"><img src="${streamUrl}" alt="${escapeHtml(a.file_name)}" class="max-w-xs rounded-lg cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src, '_blank')" onload="this.parentElement.classList.add('loaded')"></div>`;
                }
                return `<a href="${downloadUrl}" target="_blank" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/15 transition">
                    <i class="bi bi-file-earmark text-lg"></i>
                    <span class="text-sm truncate">${escapeHtml(a.file_name)}</span>
                </a>`;
            }).join('');
            
            // Check if body is rich text (contains HTML tags)
            const isRichTextBody = msg.body && /<[^>]+>/.test(msg.body);
            const bodyHtml = msg.body 
                ? (isRichTextBody 
                    ? `<div class="text-sm md:text-base text-gray-700 dark:text-white/90 break-words chat-message-body rich-text-content">${sanitizeHtmlForDisplay(msg.body)}</div>`
                    : `<p class="text-sm md:text-base text-gray-700 dark:text-white/90 whitespace-pre-line break-words">${escapeHtml(msg.body)}</p>`)
                : '';
            
            bodyContent = `
                ${bodyHtml}
                <div class="flex flex-col gap-2 ${msg.body ? 'mt-1' : ''}">${attHtml}</div>
            `;
        } else {
            const isRichText = msg.body && /<[^>]+>/.test(msg.body);
            if (isRichText) {
                bodyContent = `<div class="text-sm md:text-base text-gray-700 dark:text-white/90 break-words chat-message-body rich-text-content">${sanitizeHtmlForDisplay(msg.body)}</div>`;
            } else {
                bodyContent = `<div class="text-sm md:text-base text-gray-700 dark:text-white/90 whitespace-pre-line break-words chat-message-body">${highlightMentions(escapeHtml(msg.body || ''))}</div>`;
            }
        }

        const messageEl = document.createElement('div');
        messageEl.className = 'group relative flex gap-3 rounded-lg p-3 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors message-enter';
        messageEl.dataset.messageId = msg.id;
        messageEl.dataset.isPinned = '0';
        
        const nameStyle = isOwnMessage ? 'style="color: #2563eb;"' : 'style="color: #4EC9B0;"';
        const isStaff = ['moderator', 'admin', 'super_admin'].includes(msg.user?.role);
        const staffBadge = isStaff ? `<span class="inline-flex items-center text-[10px] font-medium text-blue-500"><i class="bi bi-patch-check-fill mr-0.5"></i>${msg.user?.role === 'super_admin' ? 'SUPER' : (msg.user?.role === 'admin' ? 'ADMIN' : 'MOD')}</span>` : '';
        
        const moderatorDropdown = (!isOwnMessage && isCurrentUserModerator) ? `
                <div class="msg-more-dropdown hidden absolute top-full right-0 mt-1 w-44 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-xl z-30 py-1"
                     data-message-id="${msg.id}"
                     data-user-id="${msg.user?.id || ''}"
                     data-user-name="${escapeHtml(userName)}"
                     data-conversation-id="${msg.conversation_id}">
                    <button type="button" class="mod-action-btn w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/15 transition" data-mod-action="mute">
                        <i class="bi bi-mic-mute text-amber-500"></i>
                        <span>Mute User</span>
                    </button>
                    <button type="button" class="mod-action-btn w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/15 transition" data-mod-action="kick">
                        <i class="bi bi-box-arrow-right text-orange-500"></i>
                        <span>Kick User</span>
                    </button>
                    <div class="border-t border-gray-200 dark:border-white/10 my-1"></div>
                    <button type="button" class="mod-action-btn w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition" data-mod-action="delete">
                        <i class="bi bi-trash"></i>
                        <span>Delete Message</span>
                    </button>
                </div>
            ` : '';

        const pinButton = isCurrentUserModerator ? `<button type="button" class="msg-action-btn pin-btn p-1.5 rounded hover:bg-gray-100 dark:hover:bg-white/15 text-gray-500 dark:text-white/60 transition" data-action="pin" title="Pin message"><i class="bi bi-pin-angle text-lg"></i></button>` : '';
        
        const ownMessageDropdown = isOwnMessage ? `
            <div class="msg-more-dropdown hidden absolute top-full right-0 mt-1 w-40 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-xl z-30 py-1"
                 data-message-id="${msg.id}"
                 data-conversation-id="${msg.conversation_id}">
                <button type="button" class="own-action-btn w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-white/80 hover:bg-gray-100 dark:hover:bg-white/15 transition" data-own-action="edit">
                    <i class="bi bi-pencil text-blue-500"></i>
                    <span>Edit</span>
                </button>
                <button type="button" class="own-action-btn w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition" data-own-action="delete">
                    <i class="bi bi-trash"></i>
                    <span>Delete</span>
                </button>
            </div>
        ` : '';
        
        messageEl.innerHTML = `
            <div class="absolute -top-3 right-2 hidden group-hover:flex items-center gap-1 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-lg px-1 py-0.5 z-20">
                <button type="button" class="msg-action-btn p-1.5 rounded hover:bg-gray-100 dark:hover:bg-white/15 text-gray-500 dark:text-white/60 transition" data-action="react" title="Add reaction"><i class="bi bi-emoji-smile text-lg"></i></button>
                ${pinButton}
                <button type="button" class="msg-action-btn p-1.5 rounded hover:bg-gray-100 dark:hover:bg-white/15 text-gray-500 dark:text-white/60 transition" data-action="forward" title="Forward"><i class="bi bi-forward text-lg"></i></button>
                ${!isOwnMessage ? `<button type="button" class="msg-action-btn p-1.5 rounded hover:bg-red-100 dark:hover:bg-red-900/20 text-gray-500 dark:text-white/60 hover:text-red-500 transition" data-action="report" title="Report"><i class="bi bi-flag text-lg"></i></button>` : ''}
                <button type="button" class="msg-action-btn p-1.5 rounded hover:bg-gray-100 dark:hover:bg-white/15 text-gray-500 dark:text-white/60 transition relative" data-action="more" title="More"><i class="bi bi-three-dots text-lg"></i></button>
                ${moderatorDropdown}
                ${ownMessageDropdown}
            </div>
            <img src="${avatar}" class="size-10 flex-shrink-0 rounded-full object-cover" alt="${escapeHtml(userName)}" onerror="this.src='https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(userName)}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981'">
            <div class="flex flex-1 flex-col min-w-0">
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                    <p class="text-sm font-medium" ${nameStyle}>${escapeHtml(msg.user?.name || 'Unknown User')}</p>
                    ${staffBadge}
                    <p class="text-xs md:text-sm text-gray-500 dark:text-[#9d9db9] msg-timestamp" data-timestamp="${msg.created_at}">${timestamp}</p>
                </div>
                ${bodyContent}
            </div>
        `;

        messagesContainer.querySelector('.border-dashed')?.remove();
        
        // Insert date separator if needed
        maybeInsertDateSeparator(messagesContainer, msg.created_at);
        
        // Insert unread separator for incoming messages (not own)
        if (!isOwnMessage) {
            insertUnreadSeparator(messagesContainer);
        }
        
        messagesContainer.appendChild(messageEl);
        
        const scrollContainer = document.getElementById('messages-scroll-container');
        if (scrollContainer) {
            const isNearBottom = scrollContainer.scrollHeight - scrollContainer.scrollTop - scrollContainer.clientHeight < 150;
            if (isNearBottom) scrollContainer.scrollTop = scrollContainer.scrollHeight;
            else showNewMessagesIndicator();
        }
    }

    function showTypingIndicator(name) {
        const typingEl = document.getElementById('typing-indicator');
        const typingText = document.getElementById('typing-indicator-text');
        if (!typingEl) return;
        
        if (typingText) typingText.textContent = `${name} is typing...`;
        typingEl.style.display = 'flex';
        typingEl.classList.remove('hidden');

        if (typingTimeoutId) clearTimeout(typingTimeoutId);
        typingTimeoutId = setTimeout(() => {
            typingEl.style.display = 'none';
            typingEl.classList.add('hidden');
        }, 3000);
    }

    function sendTypingPing() {
        if (!currentConversationId) return;
        const now = Date.now();
        if (now - lastTypingSentAt < 1000) return;
        lastTypingSentAt = now;

        fetch(`/chats/${currentConversationId}/typing`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
        }).catch(() => {});
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    let lastMessageDate = null;
    let unreadSeparatorInserted = false;
    
    // Initialize lastMessageDate from existing messages
    (function initLastMessageDate() {
        const lastMsg = document.querySelector('#messages-container > .group:last-child .msg-timestamp[data-timestamp]');
        if (lastMsg?.dataset.timestamp) {
            lastMessageDate = new Date(lastMsg.dataset.timestamp).toDateString();
        }
    })();
    
    function insertUnreadSeparator(container) {
        if (unreadSeparatorInserted || document.getElementById('unread-separator')) return;
        
        const separator = document.createElement('div');
        separator.className = 'flex items-center gap-4 py-2 unread-separator';
        separator.id = 'unread-separator';
        separator.innerHTML = `
            <div class="flex-1 h-px bg-gray-200 dark:bg-white/10"></div>
            <span class="text-xs font-medium text-gray-500 dark:text-white/50">New</span>
            <div class="flex-1 h-px bg-gray-200 dark:bg-white/10"></div>
        `;
        container.appendChild(separator);
        unreadSeparatorInserted = true;
    }
    
    function getDateLabel(date) {
        const msgDate = new Date(date);
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        const msgDateStr = msgDate.toDateString();
        const todayStr = today.toDateString();
        const yesterdayStr = yesterday.toDateString();
        
        if (msgDateStr === todayStr) return 'Today';
        if (msgDateStr === yesterdayStr) return 'Yesterday';
        return msgDate.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
    }
    
    function maybeInsertDateSeparator(container, messageDate) {
        const msgDateStr = new Date(messageDate).toDateString();
        if (lastMessageDate === msgDateStr) return;
        
        lastMessageDate = msgDateStr;
        const dateLabel = getDateLabel(messageDate);
        
        const separator = document.createElement('div');
        separator.className = 'flex items-center gap-4 py-3 date-separator';
        separator.innerHTML = `
            <div class="flex-1 h-px bg-gray-200 dark:bg-white/10"></div>
            <span class="text-xs font-medium text-gray-500 dark:text-white/50">${dateLabel}</span>
            <div class="flex-1 h-px bg-gray-200 dark:bg-white/10"></div>
        `;
        container.appendChild(separator);
    }

    function highlightMentions(text) {
        if (!text) return '';
        text = text.replace(/@everyone\b/g, '<span class="mention mention-everyone font-medium cursor-pointer hover:underline" style="color: #eab308;">@everyone</span>');
        text = text.replace(/@([a-zA-Z0-9_\-]+(?:\s+[a-zA-Z0-9_\-]+)*)/g, '<span class="mention font-medium cursor-pointer hover:underline" style="color: #eab308;">@$1</span>');
        return text;
    }

    function sanitizeHtmlForDisplay(html) {
        if (!html) return '';
        const allowedTags = ['br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'code', 'pre', 'blockquote', 'ul', 'ol', 'li', 'p', 'span', 'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'img'];
        const temp = document.createElement('div');
        temp.innerHTML = html;
        
        temp.querySelectorAll('script, style, iframe, object, embed').forEach(el => el.remove());
        temp.querySelectorAll('*').forEach(el => {
            Array.from(el.attributes).forEach(attr => {
                // Allow src attribute for images
                if (attr.name.startsWith('on') || (attr.name === 'style' && attr.value.includes('expression'))) {
                    el.removeAttribute(attr.name);
                }
            });
            if (!allowedTags.includes(el.tagName.toLowerCase())) {
                el.replaceWith(...el.childNodes);
            }
        });
        
        // Style inline images (GIFs)
        temp.querySelectorAll('img').forEach(img => {
            img.classList.add('inline-gif', 'rounded-lg', 'cursor-pointer');
            img.setAttribute('loading', 'lazy');
        });
        
        temp.querySelectorAll('a').forEach(a => {
            a.setAttribute('target', '_blank');
            a.setAttribute('rel', 'noopener noreferrer');
            a.classList.add('chat-link');
            a.style.color = '#2563eb';
            a.style.textDecoration = 'underline';
        });
        
        return temp.innerHTML;
    }

    function renderSystemMessage(text) {
        const messagesContainer = document.getElementById('messages-container');
        if (!messagesContainer) return;

        const timestamp = new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        const messageEl = document.createElement('div');
        messageEl.className = 'flex flex-col items-center py-2';
        messageEl.innerHTML = `
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-gray-100 dark:bg-white/5 text-xs text-gray-500 dark:text-white/50">
                <i class="bi bi-info-circle"></i>
                <span>${escapeHtml(text)}</span>
            </div>
            <span class="text-[10px] text-gray-400 dark:text-white/40 mt-1">${timestamp}</span>
        `;

        messagesContainer.querySelector('.border-dashed')?.remove();
        messagesContainer.appendChild(messageEl);
        
        const scrollContainer = document.getElementById('messages-scroll-container');
        if (scrollContainer) {
            const isNearBottom = scrollContainer.scrollHeight - scrollContainer.scrollTop - scrollContainer.clientHeight < 150;
            if (isNearBottom) scrollContainer.scrollTop = scrollContainer.scrollHeight;
        }
    }

    function renderCallSystemMessage(callerName, callType, action, durationText = '') {
        const messagesContainer = document.getElementById('messages-container');
        if (!messagesContainer) return;

        const timestamp = new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        let icon, text, bgClass;
        
        if (action === 'started') {
            const callIcon = callType === 'video' ? 'video' : 'phone';
            icon = `<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>`;
            if (callType === 'video') {
                icon = `<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 13 5.223 3.482a.5.5 0 0 0 .777-.416V7.87a.5.5 0 0 0-.752-.432L16 10.5"/><rect x="2" y="6" width="14" height="12" rx="2"/></svg>`;
            }
            text = `${escapeHtml(callerName)} started a ${callType} call`;
            bgClass = 'bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-400';
        } else {
            icon = `<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7 2 2 0 0 1 1.72 2v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.42 19.42 0 0 1-3.33-2.67m-2.67-3.34a19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91"/><line x1="22" x2="2" y1="2" y2="22"/></svg>`;
            text = 'Call ended' + (durationText ? ` • Duration: ${escapeHtml(durationText)}` : '');
            bgClass = 'bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400';
        }

        const messageEl = document.createElement('div');
        messageEl.className = 'flex flex-col items-center py-2';
        messageEl.innerHTML = `
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full ${bgClass} text-xs font-medium">
                ${icon}
                <span>${text}</span>
            </div>
            <span class="text-[10px] text-gray-400 dark:text-white/40 mt-1">${timestamp}</span>
        `;

        messagesContainer.querySelector('.border-dashed')?.remove();
        messagesContainer.appendChild(messageEl);
        
        const scrollContainer = document.getElementById('messages-scroll-container');
        if (scrollContainer) {
            const isNearBottom = scrollContainer.scrollHeight - scrollContainer.scrollTop - scrollContainer.clientHeight < 150;
            if (isNearBottom) scrollContainer.scrollTop = scrollContainer.scrollHeight;
        }
    }

    function hideModActionsForUser(userId) {
        document.querySelectorAll(`.msg-more-dropdown[data-user-id="${userId}"]`).forEach(dropdown => {
            const muteBtn = dropdown.querySelector('[data-mod-action="mute"]');
            const kickBtn = dropdown.querySelector('[data-mod-action="kick"]');
            if (muteBtn) muteBtn.remove();
            if (kickBtn) kickBtn.remove();
        });
    }

    function isUserRemoved(userId) {
        return removedUserIds.has(String(userId));
    }
</script>
