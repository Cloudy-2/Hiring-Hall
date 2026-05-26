{{-- Message Actions Script (reactions, pin, forward, moderator actions) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const messagesContainer = document.getElementById('messages-container');
    if (!messagesContainer) return;

    // Initialize pin button states and load pinned messages cache
    setTimeout(() => {
        updateAllPinButtons();
        if (typeof loadPinnedMessages === 'function') loadPinnedMessages();
    }, 100);

    const openModal = (name) => {
        const modal = document.querySelector(`[data-chat-modal="${name}"]`);
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('chat-modal-open');
    };

    messagesContainer.addEventListener('click', (e) => {
        const actionBtn = e.target.closest('.msg-action-btn');
        if (!actionBtn) return;

        const messageEl = actionBtn.closest('[data-message-id]');
        const messageId = messageEl?.dataset.messageId;
        const action = actionBtn.dataset.action;
        const messageBody = messageEl?.querySelector('p.whitespace-pre-line')?.textContent?.trim() || '';
        const messageUser = messageEl?.querySelector('.font-bold')?.textContent?.trim() || '';

        if (!messageId) return;

        switch (action) {
            case 'react':
                document.getElementById('reaction-message-id').value = messageId;
                openModal('reactions');
                break;
            case 'pin':
                togglePinMessage(messageId, e.target.closest('.pin-btn'));
                break;
            case 'forward':
                document.getElementById('forward-message-id').value = messageId;
                document.getElementById('forward-message-body').textContent = messageBody;
                openModal('forward');
                break;
            case 'report':
                document.getElementById('report-message-id').value = messageId;
                document.getElementById('report-message-body').textContent = messageBody;
                document.getElementById('report-message-user').textContent = `From: ${messageUser}`;
                openModal('report');
                break;
            case 'more':
                const dropdown = messageEl.querySelector('.msg-more-dropdown');
                if (dropdown) {
                    document.querySelectorAll('.msg-more-dropdown').forEach(d => {
                        if (d !== dropdown) d.classList.add('hidden');
                    });
                    dropdown.classList.toggle('hidden');
                }
                break;
        }
    });

    document.addEventListener('click', (e) => {
        if (!e.target || typeof e.target.closest !== 'function') return;
        if (!e.target.closest('[data-action="more"]') && !e.target.closest('.msg-more-dropdown')) {
            document.querySelectorAll('.msg-more-dropdown').forEach(d => d.classList.add('hidden'));
        }
    });

    // Moderator actions from message dropdown
    messagesContainer.addEventListener('click', async (e) => {
        const modBtn = e.target.closest('.mod-action-btn');
        if (!modBtn) return;

        const dropdown = modBtn.closest('.msg-more-dropdown');
        const action = modBtn.dataset.modAction;
        const messageId = dropdown?.dataset.messageId;
        const userId = dropdown?.dataset.userId;
        const userName = dropdown?.dataset.userName;
        const conversationId = dropdown?.dataset.conversationId;

        dropdown?.classList.add('hidden');
        if (!messageId || !userId) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        switch (action) {
            case 'mute':
                if (typeof isUserRemoved === 'function' && isUserRemoved(userId)) {
                    showChatToast('This user is no longer a member', 'error');
                    return;
                }
                const muteResult = await window.muteUserModal.show(userName, userId, conversationId);
                if (muteResult?.confirmed) {
                    try {
                        const response = await fetch(`/chats/manage/mute-user`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                            body: JSON.stringify({ user_id: userId, conversation_id: conversationId, duration_hours: muteResult.duration }),
                        });
                        if (response.ok) {
                            showChatToast(muteResult.duration === 0 ? `${userName} has been muted indefinitely` : `${userName} has been muted for ${muteResult.duration} hour(s)`, 'success');
                        } else {
                            throw new Error((await response.json()).message || 'Failed to mute user');
                        }
                    } catch (error) {
                        showChatToast(error.message, 'error');
                    }
                }
                break;

            case 'kick':
                if (typeof isUserRemoved === 'function' && isUserRemoved(userId)) {
                    showChatToast('This user is no longer a member', 'error');
                    return;
                }
                const kickResult = await window.chatModal.show({
                    icon: 'warning',
                    title: `Kick ${userName}?`,
                    message: 'This will remove them from this conversation immediately.',
                    confirmText: 'Kick User',
                    type: 'danger'
                });

                if (kickResult?.confirmed) {
                    try {
                        const response = await fetch(`/chats/manage/kick-user`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                            body: JSON.stringify({ user_id: userId, conversation_id: conversationId }),
                        });
                        if (response.ok) {
                            showChatToast(`${userName} has been removed`, 'success');
                        } else {
                            throw new Error((await response.json()).message || 'Failed to kick user');
                        }
                    } catch (error) {
                        showChatToast(error.message, 'error');
                    }
                }
                break;

            case 'delete':
                const deleteResult = await window.chatModal.show({
                    icon: 'warning',
                    title: 'Delete this message?',
                    message: 'Users will see "A moderator removed this message" instead.',
                    confirmText: 'Delete Message',
                    type: 'danger'
                });

                if (deleteResult?.confirmed) {
                    try {
                        const response = await fetch(`/chats/manage/delete-message`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                            body: JSON.stringify({ message_id: messageId }),
                        });
                        if (response.ok) {
                            const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
                            if (messageEl) {
                                const bodyEl = messageEl.querySelector('.whitespace-pre-line');
                                if (bodyEl) bodyEl.innerHTML = '<span class="italic text-gray-400 dark:text-white/40">A moderator removed this message</span>';
                                messageEl.querySelector('.grid.gap-2')?.remove();
                            }
                            showChatToast('Message deleted', 'success');
                        } else {
                            throw new Error((await response.json()).message || 'Failed to delete message');
                        }
                    } catch (error) {
                        showChatToast(error.message, 'error');
                    }
                }
                break;
        }
    });

    // Own message actions (edit, delete)
    messagesContainer.addEventListener('click', async (e) => {
        const ownBtn = e.target.closest('.own-action-btn');
        if (!ownBtn) return;

        const dropdown = ownBtn.closest('.msg-more-dropdown');
        const action = ownBtn.dataset.ownAction;
        const messageId = dropdown?.dataset.messageId;
        const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);

        dropdown?.classList.add('hidden');
        if (!messageId) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        if (action === 'edit') {
            const bodyEl = messageEl?.querySelector('.chat-message-body, .whitespace-pre-line');
            const currentText = bodyEl?.textContent?.trim() || '';
            
            // Create inline edit UI
            const originalHtml = bodyEl?.innerHTML;
            bodyEl.innerHTML = `
                <div class="edit-message-container">
                    <textarea class="edit-message-input w-full rounded-lg border border-primary bg-white dark:bg-sidebar-dark p-2 text-sm text-gray-900 dark:text-white resize-none focus:ring-2 focus:ring-primary focus:outline-none" rows="2">${escapeHtml(currentText)}</textarea>
                    <div class="flex items-center gap-2 mt-2">
                        <button type="button" class="edit-save-btn px-3 py-1 rounded-lg bg-primary text-white text-xs font-medium hover:bg-primary/90 transition">Save</button>
                        <button type="button" class="edit-cancel-btn px-3 py-1 rounded-lg border border-gray-200 dark:border-white/10 text-gray-600 dark:text-white/70 text-xs font-medium hover:bg-slate-100 dark:hover:bg-white/10 transition">Cancel</button>
                        <span class="text-xs text-gray-400 dark:text-white/40 ml-auto">Esc to cancel, Enter to save</span>
                    </div>
                </div>
            `;
            
            const textarea = bodyEl.querySelector('.edit-message-input');
            const saveBtn = bodyEl.querySelector('.edit-save-btn');
            const cancelBtn = bodyEl.querySelector('.edit-cancel-btn');
            
            textarea?.focus();
            textarea?.setSelectionRange(textarea.value.length, textarea.value.length);
            
            const cancelEdit = () => {
                bodyEl.innerHTML = originalHtml;
            };
            
            const saveEdit = async () => {
                const newText = textarea?.value?.trim();
                if (!newText || newText === currentText) {
                    cancelEdit();
                    return;
                }
                
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i>';
                
                try {
                    const response = await fetch(`/chats/messages/${messageId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ body: newText }),
                    });
                    
                    if (response.ok) {
                        bodyEl.innerHTML = escapeHtml(newText);
                        bodyEl.classList.add('whitespace-pre-line');
                        // Add edited indicator
                        const timeEl = messageEl.querySelector('.text-xs.text-gray-500, .text-sm.text-gray-500');
                        if (timeEl && !timeEl.textContent.includes('(edited)')) {
                            timeEl.textContent += ' (edited)';
                        }
                        showChatToast('Message edited', 'success');
                    } else {
                        throw new Error((await response.json()).message || 'Failed to edit message');
                    }
                } catch (error) {
                    showChatToast(error.message, 'error');
                    cancelEdit();
                }
            };
            
            saveBtn?.addEventListener('click', saveEdit);
            cancelBtn?.addEventListener('click', cancelEdit);
            textarea?.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') cancelEdit();
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    saveEdit();
                }
            });
        } else if (action === 'delete') {
            const deleteResult = await window.chatModal.show({
                icon: 'warning',
                title: 'Delete this message?',
                message: 'This action cannot be undone.',
                confirmText: 'Delete',
                type: 'danger'
            });

            if (deleteResult?.confirmed) {
                try {
                    const response = await fetch(`/chats/messages/${messageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    
                    if (response.ok) {
                        // Remove attachments first
                        messageEl?.querySelector('.grid.gap-2')?.remove();
                        messageEl?.querySelector('.reactions-container')?.remove();
                        
                        // Find or create body element for deleted message text
                        let bodyEl = messageEl?.querySelector('.chat-message-body, .whitespace-pre-line');
                        const contentWrapper = messageEl?.querySelector('.flex.flex-1.flex-col, .msg-content-wrapper');
                        
                        if (!bodyEl && contentWrapper) {
                            // Create body element if it doesn't exist (image-only messages)
                            bodyEl = document.createElement('p');
                            bodyEl.className = 'text-sm md:text-base leading-relaxed chat-message-body';
                            contentWrapper.appendChild(bodyEl);
                        }
                        
                        if (bodyEl) {
                            bodyEl.innerHTML = '<span class="italic text-gray-400 dark:text-white/40">You deleted a message</span>';
                        }
                        
                        // Remove action bar
                        const actionBar = messageEl?.querySelector('.absolute.-top-3');
                        if (actionBar) actionBar.remove();
                        showChatToast('Message deleted', 'success');
                    } else {
                        throw new Error((await response.json()).message || 'Failed to delete message');
                    }
                } catch (error) {
                    showChatToast(error.message, 'error');
                }
            }
        }
    });

    // Reaction clicks
    messagesContainer.addEventListener('click', (e) => {
        const reactionBtn = e.target.closest('.reaction-btn');
        if (!reactionBtn) return;
        const messageEl = reactionBtn.closest('[data-message-id]');
        const messageId = messageEl?.dataset.messageId;
        const emoji = reactionBtn.dataset.reaction;
        if (messageId && emoji) toggleReaction(messageId, emoji);
    });

    async function togglePinMessage(messageId, btnEl) {
        const pinBtn = btnEl || document.querySelector(`[data-message-id="${messageId}"] .pin-btn`);
        const icon = pinBtn?.querySelector('i');
        const originalClass = icon?.className;
        const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
        const isPinned = messageEl?.dataset.isPinned === '1';
        
        // Show loading state
        if (icon) {
            icon.className = 'bi bi-arrow-repeat text-lg animate-spin';
        }
        if (pinBtn) {
            pinBtn.disabled = true;
            pinBtn.style.pointerEvents = 'none';
        }
        
        try {
            const messageBody = messageEl?.querySelector('.whitespace-pre-line, .chat-message-body')?.textContent?.trim() || '';
            const messageUser = messageEl?.querySelector('.font-bold')?.textContent?.trim() || '';
            
            const response = await fetch(`/chats/messages/${messageId}/pin`, {
                method: isPinned ? 'DELETE' : 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (response.ok) {
                if (isPinned) {
                    showChatToast('Message unpinned', 'success');
                    messageEl.dataset.isPinned = '0';
                    // Refresh pinned bar to show next latest pin
                    await refreshPinnedMessageBar();
                } else {
                    showChatToast('Message pinned', 'success');
                    messageEl.dataset.isPinned = '1';
                    // Refresh to get updated cache with new pin
                    await refreshPinnedMessageBar();
                }
                updateAllPinButtons();
            } else {
                if (icon) icon.className = originalClass;
                showChatToast('Failed to update pin', 'error');
            }
        } catch (error) {
            console.error('Pin toggle error:', error);
            if (icon) icon.className = originalClass;
            showChatToast('Failed to update pin', 'error');
        } finally {
            if (pinBtn) {
                pinBtn.disabled = false;
                pinBtn.style.pointerEvents = '';
            }
        }
    }

    async function refreshPinnedMessageBar() {
        const convId = window.currentConversationId;
        if (!convId) return;
        
        try {
            const response = await fetch(`/chats/${convId}/pinned`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            if (response.ok) {
                const data = await response.json();
                const pins = data.data || [];
                pinnedMessagesCache = pins;
                currentPinnedIndex = 0;
                
                if (pins.length > 0) {
                    const latestPin = pins[0];
                    updatePinnedMessageBar(latestPin.id, latestPin.body || '', latestPin.user?.name || 'Unknown');
                    document.getElementById('pinned-message-id').value = latestPin.id;
                } else {
                    document.getElementById('pinned-message-bar')?.classList.add('hidden');
                    document.getElementById('pinned-message-id').value = '';
                }
            }
        } catch (e) {
            console.error('Failed to refresh pinned bar:', e);
        }
    }

    function updateAllPinButtons() {
        document.querySelectorAll('.pin-btn').forEach(btn => {
            const msgEl = btn.closest('[data-message-id]');
            const isPinned = msgEl?.dataset.isPinned === '1';
            const icon = btn.querySelector('i');
            if (isPinned) {
                icon.className = 'bi bi-pin-angle-fill text-lg text-primary';
                btn.title = 'Unpin message';
            } else {
                icon.className = 'bi bi-pin-angle text-lg';
                btn.title = 'Pin message';
            }
        });
    }

    async function pinMessage(messageId) {
        togglePinMessage(messageId, null);
    }

    // Make functions available globally
    window.pinMessage = pinMessage;
    window.updateAllPinButtons = updateAllPinButtons;
    window.refreshPinnedMessageBar = refreshPinnedMessageBar;

    async function toggleReaction(messageId, emoji) {
        const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
        const reactionBtn = messageEl?.querySelector(`.reaction-btn[data-reaction="${emoji}"]`);
        
        try {
            const response = await fetch(`/chats/messages/${messageId}/react`, {
                method: reactionBtn ? 'DELETE' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ reaction: emoji }),
            });

            if (response.ok) {
                if (reactionBtn) {
                    const countEl = reactionBtn.querySelector('span:last-child');
                    const count = parseInt(countEl?.textContent || '1');
                    if (count <= 1) reactionBtn.remove();
                    else countEl.textContent = count - 1;
                } else {
                    let reactionsDiv = messageEl?.querySelector('.reactions-container');
                    if (!reactionsDiv) {
                        reactionsDiv = document.createElement('div');
                        reactionsDiv.className = 'reactions-container flex flex-wrap gap-1 mt-2';
                        // Insert after attachments if they exist, otherwise after body
                        const attachmentsGrid = messageEl?.querySelector('.grid.gap-2');
                        const bodyEl = messageEl?.querySelector('.whitespace-pre-line, .chat-message-body');
                        const contentWrapper = messageEl?.querySelector('.flex.flex-1.flex-col, .msg-content-wrapper');
                        if (attachmentsGrid) {
                            attachmentsGrid.parentElement?.insertBefore(reactionsDiv, attachmentsGrid.nextSibling);
                        } else if (bodyEl) {
                            bodyEl.parentElement?.insertBefore(reactionsDiv, bodyEl.nextSibling);
                        } else if (contentWrapper) {
                            contentWrapper.appendChild(reactionsDiv);
                        }
                    }
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'reaction-btn inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10 transition';
                    btn.dataset.reaction = emoji;
                    btn.innerHTML = `<span>${emoji}</span><span class="text-gray-600 dark:text-white/70">1</span>`;
                    reactionsDiv.appendChild(btn);
                }
            }
        } catch (error) {
            console.error('Reaction error:', error);
        }
    }

    // Own status dropdown toggle (for moderators)
    document.addEventListener('click', (e) => {
        if (!e.target || typeof e.target.closest !== 'function') return;
        const ownStatusBtn = e.target.closest('.own-status-btn');
        if (ownStatusBtn) {
            const memberItem = ownStatusBtn.closest('.member-item');
            const dropdown = memberItem?.querySelector('.own-status-dropdown');
            if (dropdown) {
                // Close other dropdowns
                document.querySelectorAll('.own-status-dropdown, .member-action-dropdown').forEach(d => {
                    if (d !== dropdown) d.classList.add('hidden');
                });
                dropdown.classList.toggle('hidden');
            }
            return;
        }
        
        // Close own status dropdown when clicking outside
        if (!e.target.closest('.own-status-dropdown')) {
            document.querySelectorAll('.own-status-dropdown').forEach(d => d.classList.add('hidden'));
        }
    });

    // Toggle status visibility action
    document.addEventListener('click', async (e) => {
        const toggleBtn = e.target.closest('.toggle-status-visibility');
        if (!toggleBtn) return;
        
        const isCurrentlyHidden = toggleBtn.dataset.hidden === 'true';
        const newHiddenState = !isCurrentlyHidden;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        
        // Get elements
        const icon = toggleBtn.querySelector('i');
        const text = toggleBtn.querySelector('span');
        const memberItem = toggleBtn.closest('.member-item');
        const originalIconClass = icon.className;
        const originalText = text.textContent;
        
        // Show loading state
        icon.className = 'bi bi-arrow-repeat animate-spin text-gray-500 dark:text-white/60';
        text.textContent = 'Updating...';
        toggleBtn.disabled = true;
        toggleBtn.classList.add('opacity-50', 'pointer-events-none');
        
        try {
            const response = await fetch('/chats/user/status-visibility', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ hidden: newHiddenState })
            });
            
            if (response.ok) {
                // Update button state
                toggleBtn.dataset.hidden = newHiddenState ? 'true' : 'false';
                
                if (newHiddenState) {
                    icon.className = 'bi bi-eye text-gray-500 dark:text-white/60';
                    text.textContent = 'Show Status';
                    showChatToast('Your status is now hidden', 'success');
                    // Add hidden indicator next to name
                    addHiddenIndicator(memberItem);
                } else {
                    icon.className = 'bi bi-eye-slash text-gray-500 dark:text-white/60';
                    text.textContent = 'Hide Status';
                    showChatToast('Your status is now visible', 'success');
                    // Remove hidden indicator
                    removeHiddenIndicator(memberItem);
                }
                
                // Close dropdown
                toggleBtn.closest('.own-status-dropdown')?.classList.add('hidden');
            } else {
                throw new Error('Failed to update status visibility');
            }
        } catch (error) {
            // Restore original state on error
            icon.className = originalIconClass;
            text.textContent = originalText;
            showChatToast('Failed to update status visibility', 'error');
        } finally {
            toggleBtn.disabled = false;
            toggleBtn.classList.remove('opacity-50', 'pointer-events-none');
        }
    });
    
    // Helper to add hidden status indicator
    function addHiddenIndicator(memberItem) {
        if (!memberItem) return;
        const nameContainer = memberItem.querySelector('.flex.items-center.gap-1\\.5');
        if (nameContainer && !nameContainer.querySelector('.status-hidden-indicator')) {
            const indicator = document.createElement('span');
            indicator.className = 'status-hidden-indicator inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[9px] font-medium bg-gray-500/20 text-gray-400 dark:text-white/50';
            indicator.innerHTML = '<i class="bi bi-eye-slash text-[8px]"></i> Hidden';
            nameContainer.appendChild(indicator);
        }
    }
    
    // Helper to remove hidden status indicator
    function removeHiddenIndicator(memberItem) {
        if (!memberItem) return;
        const indicator = memberItem.querySelector('.status-hidden-indicator');
        if (indicator) indicator.remove();
    }

    // Sidebar member action dropdown toggle
    document.addEventListener('click', (e) => {
        if (!e.target || typeof e.target.closest !== 'function') return;
        const actionBtn = e.target.closest('.member-action-btn');
        if (actionBtn) {
            // Hide the user hover card when opening dropdown
            const hoverCard = document.getElementById('user-hover-card');
            if (hoverCard) hoverCard.classList.add('hidden');
            
            const memberItem = actionBtn.closest('.member-item');
            const dropdown = memberItem?.querySelector('.member-action-dropdown');
            if (dropdown) {
                // Close other dropdowns and remove focus from other members
                document.querySelectorAll('.member-action-dropdown, .own-status-dropdown').forEach(d => {
                    if (d !== dropdown) {
                        d.classList.add('hidden');
                        d.closest('.member-item')?.classList.remove('dropdown-active', 'ring-2', 'ring-primary/50', 'bg-slate-100', 'dark:bg-white/10');
                    }
                });
                
                // Toggle current dropdown
                const isOpening = dropdown.classList.contains('hidden');
                dropdown.classList.toggle('hidden');
                
                // Add/remove focus state on member item and disable hover on siblings
                const rightSidebar = document.querySelector('.chat-v2-right');
                if (isOpening) {
                    memberItem.classList.add('dropdown-active', 'ring-2', 'ring-primary/50', 'bg-slate-100', 'dark:bg-white/10');
                    // Add class to sidebar to disable hover effects on other members
                    rightSidebar?.classList.add('dropdown-open');
                } else {
                    memberItem.classList.remove('dropdown-active', 'ring-2', 'ring-primary/50', 'bg-slate-100', 'dark:bg-white/10');
                    rightSidebar?.classList.remove('dropdown-open');
                }
            }
            return;
        }
        
        if (!e.target.closest('.member-action-dropdown')) {
            // Close all dropdowns and remove focus states
            document.querySelectorAll('.member-action-dropdown').forEach(d => {
                d.classList.add('hidden');
                d.closest('.member-item')?.classList.remove('dropdown-active', 'ring-2', 'ring-primary/50', 'bg-slate-100', 'dark:bg-white/10');
            });
            // Re-enable hover effects
            document.querySelector('.chat-v2-right')?.classList.remove('dropdown-open');
        }
    });

    // Sidebar member mute/kick actions
    document.addEventListener('click', async (e) => {
        const actionBtn = e.target.closest('.sidebar-mod-action');
        if (!actionBtn) return;

        const dropdown = actionBtn.closest('.member-action-dropdown');
        const action = actionBtn.dataset.action;
        const userId = dropdown?.dataset.userId;
        const userName = dropdown?.dataset.userName;
        const conversationId = dropdown?.dataset.conversationId;
        const memberItem = document.querySelector(`.member-item[data-user-id="${userId}"]`);

        dropdown?.classList.add('hidden');
        document.querySelector('.chat-v2-right')?.classList.remove('dropdown-open');
        memberItem?.classList.remove('dropdown-active', 'ring-2', 'ring-primary/50', 'bg-slate-100', 'dark:bg-white/10');
        
        if (!userId || !conversationId) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        if (action === 'mute') {
            const muteResult = await window.muteUserModal.show(userName, userId, conversationId);
            if (muteResult?.confirmed) {
                if (memberItem) {
                    memberItem.classList.add('opacity-50', 'pointer-events-none');
                    const statusEl = memberItem.querySelector('.member-status');
                    if (statusEl) statusEl.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Muting...';
                }
                
                try {
                    const response = await fetch(`/chats/manage/mute-user`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({ user_id: userId, conversation_id: conversationId, duration_hours: muteResult.duration }),
                    });
                    if (response.ok) {
                        showChatToast(muteResult.duration === 0 ? `${userName} has been muted indefinitely` : `${userName} has been muted for ${muteResult.duration} hour(s)`, 'success');
                        updateMemberMuteState(userId, true);
                    } else {
                        throw new Error((await response.json()).message || 'Failed to mute user');
                    }
                } catch (error) {
                    showChatToast(error.message, 'error');
                } finally {
                    if (memberItem) {
                        memberItem.classList.remove('opacity-50', 'pointer-events-none');
                        const statusEl = memberItem.querySelector('.member-status');
                        if (statusEl) statusEl.textContent = memberItem.closest('#online-members-list') ? 'Online' : 'Offline';
                    }
                }
            }
        } else if (action === 'unmute') {
            if (memberItem) {
                memberItem.classList.add('opacity-50', 'pointer-events-none');
                const statusEl = memberItem.querySelector('.member-status');
                if (statusEl) statusEl.innerHTML = '<i class="bi bi-hourglass-split animate-spin"></i> Unmuting...';
            }
            
            try {
                const response = await fetch(`/chats/manage/${conversationId}/members/${userId}/unmute`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                });
                if (response.ok) {
                    showChatToast(`${userName} has been unmuted`, 'success');
                    updateMemberMuteState(userId, false);
                } else {
                    throw new Error((await response.json()).message || 'Failed to unmute user');
                }
            } catch (error) {
                showChatToast(error.message, 'error');
            } finally {
                if (memberItem) {
                    memberItem.classList.remove('opacity-50', 'pointer-events-none');
                    const statusEl = memberItem.querySelector('.member-status');
                    if (statusEl) statusEl.textContent = memberItem.closest('#online-members-list') ? 'Online' : 'Offline';
                }
            }
        } else if (action === 'kick') {
            const kickResult = await window.chatModal.show({
                icon: 'warning',
                title: `Kick ${userName}?`,
                message: 'This will remove them from this conversation.',
                confirmText: 'Kick',
                type: 'danger'
            });

            if (kickResult?.confirmed) {
                if (memberItem) {
                    memberItem.classList.add('opacity-50', 'pointer-events-none');
                    memberItem.innerHTML = `
                        <div class="flex items-center gap-3 w-full">
                            <div class="size-10 rounded-full bg-gray-200 dark:bg-white/10 animate-pulse"></div>
                            <div class="flex-1">
                                <div class="h-4 bg-gray-200 dark:bg-white/10 rounded animate-pulse w-24"></div>
                                <div class="h-3 bg-gray-100 dark:bg-white/5 rounded animate-pulse w-16 mt-1"></div>
                            </div>
                        </div>
                    `;
                }
                
                try {
                    const response = await fetch(`/chats/manage/kick-user`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({ user_id: userId, conversation_id: conversationId }),
                    });
                    if (response.ok) {
                        memberItem?.remove();
                        showChatToast(`${userName} has been removed`, 'success');
                    } else {
                        throw new Error((await response.json()).message || 'Failed to kick user');
                    }
                } catch (error) {
                    showChatToast(error.message, 'error');
                    window.location.reload();
                }
            }
        }
    });

    // Unmute action from muted members list (right panel)
    document.addEventListener('click', async (e) => {
        const unmuteBtn = e.target.closest('.unmute-btn');
        if (!unmuteBtn) return;

        const userId = unmuteBtn.dataset.userId;
        const userName = unmuteBtn.dataset.userName || 'User';
        const conversationId = window.currentConversationId;
        if (!userId || !conversationId) return;

        const memberItem = unmuteBtn.closest('.muted-member-item');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        if (memberItem) {
            memberItem.classList.add('opacity-50', 'pointer-events-none');
        }

        try {
            const response = await fetch(`/chats/manage/${conversationId}/members/${userId}/unmute`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!response.ok) {
                throw new Error((await response.json()).message || 'Failed to unmute user');
            }

            showChatToast(`${userName} has been unmuted`, 'success');
            updateMemberMuteState(userId, false, null, userName);
        } catch (error) {
            showChatToast(error.message || 'Failed to unmute user', 'error');
            if (memberItem) {
                memberItem.classList.remove('opacity-50', 'pointer-events-none');
            }
        }
    });

    function updateMemberCounts() {
        const onlineCount = document.getElementById('online-count');
        const offlineCount = document.getElementById('offline-count');
        if (onlineCount) onlineCount.textContent = document.querySelectorAll('#online-members-list .member-item').length;
        if (offlineCount) offlineCount.textContent = document.querySelectorAll('#offline-members-list .member-item').length;
    }

    function updateMemberMuteState(userId, isMuted, mutedUntil = null, userName = null) {
        const memberItem = document.querySelector(`.member-item[data-user-id="${userId}"]`);
        const mutedSection = document.getElementById('muted-section');
        const mutedList = document.getElementById('muted-members-list');
        const mutedCountEl = document.getElementById('muted-count');
        const onlineList = document.getElementById('online-members-list');
        const offlineList = document.getElementById('offline-members-list');
        const sidebarMutedSection = document.getElementById('sidebar-muted-section');
        const sidebarMutedCount = document.getElementById('sidebar-muted-count');
        
        if (isMuted) {
            // Move member to muted section
            if (memberItem && mutedList) {
                const name = userName || memberItem.querySelector('.font-medium')?.textContent?.trim() || 'User';
                const avatar = memberItem.querySelector('img')?.src || '';
                const isSystemModerator = memberItem.querySelector('.bi-patch-check-fill') !== null;
                const systemRole = memberItem.dataset.systemRole || null;
                const staffBadgeTitle = systemRole === 'super_admin' ? 'Super Admin' : (systemRole === 'admin' ? 'Admin' : 'Moderator');
                
                // Create muted member item (same style as regular members)
                const mutedItem = document.createElement('div');
                mutedItem.className = 'muted-member-item group relative flex items-center gap-3 p-2 rounded-lg opacity-60 hover:opacity-100 hover:bg-slate-100 dark:hover:bg-white/10 transition';
                mutedItem.dataset.userId = userId;
                if (systemRole) mutedItem.dataset.systemRole = systemRole;
                
                const mutedUntilText = mutedUntil 
                    ? `Until ${new Date(mutedUntil).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}, ${new Date(mutedUntil).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}`
                    : 'Indefinitely';
                
                mutedItem.innerHTML = `
                    <div class="relative">
                        <img src="${avatar}" class="size-10 rounded-full" alt="${escapeHtml(name)}">
                        <span class="absolute -bottom-0.5 -right-0.5 size-3 rounded-full border-2 border-sidebar-light dark:border-sidebar-dark bg-red-500 flex items-center justify-center">
                            <i class="bi bi-mic-mute-fill text-[6px] text-white"></i>
                        </span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5">
                            <p class="text-sm font-medium text-gray-800 dark:text-white truncate">${escapeHtml(name)}</p>
                            ${isSystemModerator ? `<i class="bi bi-patch-check-fill text-[10px] text-blue-500/50" title="${staffBadgeTitle}"></i>` : ''}
                        </div>
                        <p class="text-xs text-red-500 dark:text-red-400">${mutedUntilText}</p>
                    </div>
                    <button type="button" class="unmute-btn hidden group-hover:flex items-center justify-center size-7 rounded-full bg-gray-100 dark:bg-white/5 hover:bg-green-200 dark:hover:bg-green-500/20 text-gray-600 dark:text-white/70 hover:text-green-600 dark:hover:text-green-400 transition" 
                        data-user-id="${userId}" 
                        data-user-name="${escapeHtml(name)}"
                        title="Unmute user">
                        <i class="bi bi-volume-up-fill text-sm"></i>
                    </button>
                `;
                
                // Remove from online/offline list
                memberItem.remove();
                
                // Add to muted list
                const emptyMsg = mutedList.querySelector('p.text-xs');
                if (emptyMsg) emptyMsg.remove();
                mutedList.appendChild(mutedItem);
                
                // Show muted section
                if (mutedSection) mutedSection.classList.remove('hidden');
                
                // Update count
                const currentCount = mutedList.querySelectorAll('.muted-member-item').length;
                if (mutedCountEl) mutedCountEl.textContent = currentCount;
                
                // Update sidebar muted section
                if (sidebarMutedSection) {
                    sidebarMutedSection.classList.remove('hidden');
                    const sidebarContent = sidebarMutedSection.querySelector('.sidebar-section-content');
                    if (sidebarContent) {
                        const emptyP = sidebarContent.querySelector('p.text-xs');
                        if (emptyP) emptyP.remove();
                        
                        const sidebarItem = document.createElement('div');
                        sidebarItem.className = 'sidebar-muted-item flex items-center gap-2 rounded px-2 py-1 text-gray-600 dark:text-white/70 hover:bg-slate-100 dark:hover:bg-white/10 group';
                        sidebarItem.dataset.userId = userId;
                        sidebarItem.innerHTML = `
                            <i class="bi bi-mic-mute text-red-500 text-sm sidebar-mute-icon"></i>
                            <span class="text-sm flex-1 truncate">${escapeHtml(name)}</span>
                            <button type="button" onclick="sidebarUnmuteUser(${userId}, '${escapeHtml(name).replace(/'/g, "\\'")}', this)" 
                                class="sidebar-unmute-btn opacity-0 group-hover:opacity-100 p-1 rounded text-red-500 hover:text-green-500 hover:bg-green-500/10 transition" title="Unmute">
                                <i class="bi bi-volume-up-fill text-xs"></i>
                            </button>
                        `;
                        sidebarContent.appendChild(sidebarItem);
                    }
                }
                if (sidebarMutedCount) {
                    sidebarMutedCount.textContent = currentCount;
                }
            }
        } else {
            // Move member back from muted section
            const mutedItem = document.querySelector(`.muted-member-item[data-user-id="${userId}"]`);
            if (mutedItem && onlineList) {
                const name = userName || mutedItem.querySelector('.font-medium')?.textContent?.trim() || 'User';
                const avatar = mutedItem.querySelector('img')?.src || '';
                const isSystemModerator = mutedItem.querySelector('.bi-patch-check-fill') !== null;
                const systemRole = mutedItem.dataset.systemRole || null;
                const staffBadgeTitle = systemRole === 'super_admin' ? 'Super Admin' : (systemRole === 'admin' ? 'Admin' : 'Moderator');
                
                // Create regular member item (add to offline by default)
                const memberEl = document.createElement('div');
                memberEl.className = 'member-item group relative flex items-center gap-3 p-2 rounded-lg opacity-60 hover:opacity-100 hover:bg-slate-100 dark:hover:bg-white/10 transition';
                memberEl.dataset.userId = userId;
                if (systemRole) memberEl.dataset.systemRole = systemRole;
                memberEl.innerHTML = `
                    <div class="relative">
                        <img src="${avatar}" class="size-10 rounded-full" alt="${escapeHtml(name)}">
                        <span data-user-presence="${userId}" class="absolute -bottom-0.5 -right-0.5 size-3 rounded-full border-2 border-sidebar-light dark:border-sidebar-dark bg-slate-500"></span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5">
                            <p class="text-sm font-medium text-gray-800 dark:text-white truncate">${escapeHtml(name)}</p>
                            ${isSystemModerator ? `<i class="bi bi-patch-check-fill text-[10px] text-blue-500/50" title="${staffBadgeTitle}"></i>` : ''}
                        </div>
                    </div>
                `;
                
                // Remove from muted list
                mutedItem.remove();
                
                // Add to offline list
                const emptyMsg = offlineList?.querySelector('p.text-xs');
                if (emptyMsg) emptyMsg.remove();
                offlineList?.appendChild(memberEl);
                
                // Update muted count and hide section if empty
                if (mutedList) {
                    const currentCount = mutedList.querySelectorAll('.muted-member-item').length;
                    if (mutedCountEl) mutedCountEl.textContent = currentCount;
                    if (currentCount === 0 && mutedSection) {
                        mutedSection.classList.add('hidden');
                    }
                }
                
                // Update sidebar muted section
                const sidebarMutedItem = document.querySelector(`.sidebar-muted-item[data-user-id="${userId}"]`);
                if (sidebarMutedItem) sidebarMutedItem.remove();
                
                if (sidebarMutedSection) {
                    const sidebarContent = sidebarMutedSection.querySelector('.sidebar-section-content');
                    const sidebarItems = sidebarContent?.querySelectorAll('.sidebar-muted-item').length || 0;
                    if (sidebarMutedCount) sidebarMutedCount.textContent = sidebarItems;
                    if (sidebarItems === 0) {
                        sidebarMutedSection.classList.add('hidden');
                    }
                }
                
                // Update member counts
                updateMemberCounts();
            }
        }
    }
    
    window.updateMemberMuteState = updateMemberMuteState;
});

// Global functions for pinned message bar
let pinnedMessagesCache = [];
let currentPinnedIndex = 0;

function updatePinnedMessageBar(messageId, body, userName) {
    const bar = document.getElementById('pinned-message-bar');
    const textEl = document.getElementById('pinned-message-text');
    const authorEl = document.getElementById('pinned-message-author');
    const idInput = document.getElementById('pinned-message-id');
    const counterEl = document.getElementById('pinned-message-counter');
    
    if (bar && textEl && authorEl && idInput) {
        const truncatedBody = body.length > 60 ? body.substring(0, 60) + '...' : body;
        textEl.textContent = truncatedBody;
        authorEl.textContent = userName;
        idInput.value = messageId;
        bar.classList.remove('hidden');
        
        if (counterEl && pinnedMessagesCache.length > 1) {
            counterEl.textContent = `(${currentPinnedIndex + 1}/${pinnedMessagesCache.length})`;
        } else if (counterEl) {
            counterEl.textContent = '';
        }
        
        updatePinnedProgressBar();
    }
}

function updatePinnedProgressBar() {
    const progressBar = document.getElementById('pinned-progress-bar');
    if (progressBar && pinnedMessagesCache.length > 0) {
        const progress = ((currentPinnedIndex + 1) / pinnedMessagesCache.length) * 100;
        progressBar.style.height = `${progress}%`;
    }
}

async function loadPinnedMessages() {
    const convId = window.currentConversationId;
    if (!convId) return [];
    
    try {
        const response = await fetch(`/chats/${convId}/pinned`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        if (response.ok) {
            const data = await response.json();
            pinnedMessagesCache = data.data || [];
            return pinnedMessagesCache;
        }
    } catch (e) {
        console.error('Failed to load pinned messages:', e);
    }
    return [];
}

async function cyclePinnedMessage() {
    if (pinnedMessagesCache.length === 0) {
        await loadPinnedMessages();
    }
    
    if (pinnedMessagesCache.length === 0) return;
    
    // Scroll to current pinned message
    const currentPin = pinnedMessagesCache[currentPinnedIndex];
    if (currentPin) {
        scrollToMessage(currentPin.id);
    }
    
    // Move to next pin for next click
    currentPinnedIndex = (currentPinnedIndex + 1) % pinnedMessagesCache.length;
    
    // Update display to show next pin
    const nextPin = pinnedMessagesCache[currentPinnedIndex];
    if (nextPin) {
        updatePinnedMessageBar(nextPin.id, nextPin.body || '', nextPin.user?.name || 'Unknown');
    }
}

function scrollToMessage(messageId) {
    const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
    if (messageEl) {
        messageEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        messageEl.classList.add('ring-2', 'ring-primary', 'ring-offset-2', 'dark:ring-offset-sidebar-dark');
        setTimeout(() => {
            messageEl.classList.remove('ring-2', 'ring-primary', 'ring-offset-2', 'dark:ring-offset-sidebar-dark');
        }, 2000);
    } else {
        if (typeof showChatToast === 'function') {
            showChatToast('Message not in view. Scroll up to find it.', 'info');
        }
    }
}

function scrollToPinnedMessage() {
    const messageId = document.getElementById('pinned-message-id')?.value;
    if (!messageId) return;
    scrollToMessage(messageId);
}

async function openPinnedMessagesPanel() {
    const panel = document.getElementById('pinned-messages-panel');
    const list = document.getElementById('pinned-messages-list');
    const countEl = document.getElementById('pinned-panel-count');
    
    if (!panel || !list) return;
    
    // Show panel with loading state
    panel.classList.remove('hidden');
    if (countEl) countEl.textContent = '';
    list.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="flex items-center gap-3 text-gray-500 dark:text-white/50">
                <i class="bi bi-arrow-repeat animate-spin text-lg"></i>
                <span class="text-sm">Loading pinned messages...</span>
            </div>
        </div>
    `;
    
    // Load fresh data
    await loadPinnedMessages();
    
    if (pinnedMessagesCache.length === 0) {
        panel.classList.add('hidden');
        if (typeof showChatToast === 'function') {
            showChatToast('No pinned messages', 'info');
        }
        return;
    }
    
    if (countEl) countEl.textContent = `(${pinnedMessagesCache.length})`;
    
    const getDiceBearAvatar = (name) => `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(name)}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981`;
    
    list.innerHTML = pinnedMessagesCache.map((pin, index) => {
        const avatar = pin.user?.avatar || getDiceBearAvatar(pin.user?.name || 'User');
        return `
        <button type="button" onclick="selectPinnedMessage(${index})" class="w-full flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-white/5 transition text-left ${index === currentPinnedIndex ? 'bg-primary/5 dark:bg-primary/10' : ''}">
            <img src="${avatar}" alt="${escapeHtml(pin.user?.name || 'User')}" class="flex-shrink-0 w-8 h-8 rounded-full object-cover" onerror="this.src='${getDiceBearAvatar(pin.user?.name || 'User')}'">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-medium text-gray-500 dark:text-white/50">${escapeHtml(pin.user?.name || 'Unknown')}</p>
                <p class="text-sm text-gray-700 dark:text-white/80 line-clamp-2">${escapeHtml(pin.body || '')}</p>
            </div>
            <i class="bi bi-chevron-right text-gray-300 dark:text-white/20 flex-shrink-0 mt-2"></i>
        </button>
    `}).join('');
}

function closePinnedMessagesPanel() {
    document.getElementById('pinned-messages-panel')?.classList.add('hidden');
}

function selectPinnedMessage(index) {
    if (index < 0 || index >= pinnedMessagesCache.length) return;
    
    currentPinnedIndex = index;
    const pin = pinnedMessagesCache[index];
    
    updatePinnedMessageBar(pin.id, pin.body || '', pin.user?.name || 'Unknown');
    scrollToMessage(pin.id);
    closePinnedMessagesPanel();
    
    // Move to next for subsequent clicks
    currentPinnedIndex = (currentPinnedIndex + 1) % pinnedMessagesCache.length;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
}

async function unpinCurrentMessage() {
    const messageId = document.getElementById('pinned-message-id')?.value;
    if (!messageId) return;
    
    try {
        const response = await fetch(`/chats/messages/${messageId}/pin`, {
            method: 'DELETE',
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '', 
                'X-Requested-With': 'XMLHttpRequest' 
            },
        });
        if (response.ok) {
            const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
            if (messageEl) messageEl.dataset.isPinned = '0';
            if (typeof window.updateAllPinButtons === 'function') window.updateAllPinButtons();
            if (typeof showChatToast === 'function') showChatToast('Message unpinned', 'success');
            
            // Remove from cache and refresh
            pinnedMessagesCache = pinnedMessagesCache.filter(p => p.id != messageId);
            currentPinnedIndex = 0;
            
            if (typeof window.refreshPinnedMessageBar === 'function') {
                await window.refreshPinnedMessageBar();
            } else {
                document.getElementById('pinned-message-bar')?.classList.add('hidden');
                document.getElementById('pinned-message-id').value = '';
            }
        }
    } catch (error) {
        console.error('Unpin error:', error);
    }
}

// Close pinned panel when clicking outside
document.addEventListener('click', (e) => {
    if (!e.target || typeof e.target.closest !== 'function') return;
    const panel = document.getElementById('pinned-messages-panel');
    const bar = document.getElementById('pinned-message-bar');
    if (panel && !panel.classList.contains('hidden')) {
        if (!panel.contains(e.target) && !bar?.contains(e.target)) {
            closePinnedMessagesPanel();
        }
    }
});
</script>
