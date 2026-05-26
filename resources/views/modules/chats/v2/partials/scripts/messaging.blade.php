{{-- Message Sending Scripts --}}
<script>
    function sanitizeHtml(html) {
        if (!html) return '';
        const allowedTags = ['br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'code', 'pre', 'blockquote', 'ul', 'ol', 'li', 'p', 'span', 'a', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'img'];
        const temp = document.createElement('div');
        temp.innerHTML = html;
        
        temp.querySelectorAll('script, style, iframe, object, embed').forEach(el => el.remove());
        temp.querySelectorAll('*').forEach(el => {
            Array.from(el.attributes).forEach(attr => {
                if (attr.name.startsWith('on') || attr.name === 'style' && attr.value.includes('expression')) {
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

    function highlightMentions(text) {
        if (!text) return '';
        // Highlight @everyone
        text = text.replace(/@everyone\b/g, '<span class="mention mention-everyone font-medium cursor-pointer hover:underline" style="color: #eab308;">@everyone</span>');
        // Highlight @username (supports full names with spaces like @Test User)
        text = text.replace(/@([a-zA-Z0-9_\-]+(?:\s+[a-zA-Z0-9_\-]+)*)/g, '<span class="mention font-medium cursor-pointer hover:underline" style="color: #eab308;">@$1</span>');
        return text;
    }

    async function sendMessage(conversationId, body, videoFile = null, attachments = []) {
        if (!conversationId || (!body?.trim() && !videoFile && attachments.length === 0)) return null;

        // Get current topic ID if in a channel
        const topicId = window.currentTopicId || null;

        try {
            const formData = new FormData();
            formData.append('body', body || '');
            if (videoFile) formData.append('video', videoFile);
            if (topicId) formData.append('topic_id', topicId);
            
            // Add attachments
            attachments.forEach((file, i) => {
                formData.append(`attachments[${i}]`, file);
            });

            const response = await fetch(`/chats/${conversationId}/messages`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json().catch(() => ({}));

            // Handle bot response (command executed in #rules channel)
            if (data.bot_response) {
                if (data.is_error) {
                    if (typeof showChatToast === 'function') showChatToast(data.message, 'error');
                } else {
                    if (typeof showChatToast === 'function') showChatToast(data.message, 'bot');
                    if (data.content) {
                        window.showBotMessage?.(data.title || 'HillBot', data.content);
                    }
                }
                return { bot_response: true };
            }

            // Handle blocked message (moderation)
            if (data.blocked) {
                if (typeof showChatToast === 'function') showChatToast(data.message, 'error');
                return { blocked: true };
            }

            if (!response.ok) {
                throw new Error(data.message || 'Failed to send message');
            }
            return data;
        } catch (error) {
            console.error('[Chat] Send error:', error);
            let userMessage = 'Failed to send message. Please try again.';
            if (error.message?.includes('Pusher') || error.message?.includes('cURL') || error.message?.includes('timeout')) {
                userMessage = 'The server is experiencing some issues. Please try again in a moment.';
            }
            if (typeof showChatToast === 'function') showChatToast(userMessage, 'error');
            return null;
        }
    }

    // Function to display bot messages in chat
    window.showBotMessage = function(title, content) {
        const messagesContainer = document.getElementById('messages-container');
        if (!messagesContainer) return;

        const messageEl = document.createElement('div');
        messageEl.className = 'group relative flex gap-3 rounded-lg p-3 bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 my-2';
        
        messageEl.innerHTML = `
            <img src="https://api.dicebear.com/7.x/bottts/svg?seed=HillBot&backgroundColor=6366f1" 
                 alt="HillBot" 
                 class="size-10 flex-shrink-0 rounded-full">
            <div class="flex flex-1 flex-col gap-1 min-w-0">
                <div class="flex items-center gap-2">
                    <p class="text-sm font-bold text-gray-900 dark:text-white">${escapeHtml(title)}</p>
                    <span class="px-1.5 py-0.5 text-xs bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/60 rounded">BOT</span>
                </div>
                <div class="text-sm text-gray-700 dark:text-white/80 whitespace-pre-line">${content.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')}</div>
            </div>
        `;

        messagesContainer.appendChild(messageEl);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    };

    window.renderOutgoingMessage = function(body, messageId = null, type = 'text') {
        const messagesContainer = document.getElementById('messages-container');
        if (!messagesContainer) return;

        const user = @json(auth()->user());
        const userName = user?.name || 'You';
        const avatar = user?.profile_photo_path
            ? `/storage/${user.profile_photo_path}`
            : `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(userName)}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981`;

        const timestamp = new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        // Check if this is a GIF - must be type 'gif' OR a valid GIF URL (not HTML content)
        const isGifUrl = body && !body.includes('<') && (body.startsWith('https://media.tenor.com') || body.includes('tenor.com/') || (body.startsWith('http') && body.endsWith('.gif')));
        const isGif = type === 'gif' || isGifUrl;
        const isRichText = type === 'rich' || (body && /<[^>]+>/.test(body));
        const convId = window.currentConversationId;
        const isModerator = @json(auth()->user()->isModerator());
        
        let bodyContent;
        if (isGif) {
            bodyContent = `<img src="${escapeHtml(body)}" alt="GIF" class="max-w-xs rounded-lg cursor-pointer hover:opacity-90 transition" loading="lazy" onclick="window.open(this.src, '_blank')">`;
        } else if (isRichText) {
            bodyContent = `<div class="text-sm md:text-base text-gray-700 dark:text-white/90 break-words chat-message-body rich-text-content">${sanitizeHtml(body)}</div>`;
        } else {
            bodyContent = `<div class="text-sm md:text-base text-gray-700 dark:text-white/90 whitespace-pre-line break-words chat-message-body">${highlightMentions(escapeHtml(body))}</div>`;
        }

        const messageEl = document.createElement('div');
        messageEl.className = 'group relative flex gap-3 rounded-lg p-3 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors message-enter';
        if (messageId) messageEl.dataset.messageId = messageId;
        messageEl.dataset.isOwn = '1';
        messageEl.dataset.isPinned = '0';
        
        const ownDropdown = `
            <div class="msg-more-dropdown hidden absolute top-full right-0 mt-1 w-40 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-xl z-30 py-1"
                 data-message-id="${messageId || ''}"
                 data-conversation-id="${convId}">
                <button type="button" class="own-action-btn w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10 transition" data-own-action="edit">
                    <i class="bi bi-pencil text-blue-500"></i>
                    <span>Edit</span>
                </button>
                <button type="button" class="own-action-btn w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition" data-own-action="delete">
                    <i class="bi bi-trash"></i>
                    <span>Delete</span>
                </button>
            </div>
        `;
        
        const pinButton = isModerator ? `<button type="button" class="msg-action-btn pin-btn p-1.5 rounded hover:bg-slate-100 dark:hover:bg-white/10 text-gray-500 dark:text-white/60 transition" data-action="pin" title="Pin message"><i class="bi bi-pin-angle text-lg"></i></button>` : '';
        
        const sendingIndicator = `<span class="msg-status text-xs text-gray-400 dark:text-white/40 flex items-center gap-1"><i class="bi bi-clock"></i> Sending</span>`;
        
        const userRole = @json(auth()->user()->role);
        const badgeLabel = userRole === 'super_admin' ? 'SUPER' : (userRole === 'admin' ? 'ADMIN' : 'MOD');
        const moderatorBadge = isModerator ? `<span class="inline-flex items-center text-[10px] font-medium text-blue-500"><i class="bi bi-patch-check-fill mr-0.5"></i>${badgeLabel}</span>` : '';
        
        messageEl.innerHTML = `
            <div class="absolute -top-3 right-2 hidden group-hover:flex items-center gap-1 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark shadow-lg px-1 py-0.5 z-20">
                <button type="button" class="msg-action-btn p-1.5 rounded hover:bg-slate-100 dark:hover:bg-white/10 text-gray-500 dark:text-white/60 transition" data-action="react" title="Add reaction"><i class="bi bi-emoji-smile text-lg"></i></button>
                ${pinButton}
                <button type="button" class="msg-action-btn p-1.5 rounded hover:bg-slate-100 dark:hover:bg-white/10 text-gray-500 dark:text-white/60 transition" data-action="forward" title="Forward"><i class="bi bi-forward text-lg"></i></button>
                <button type="button" class="msg-action-btn p-1.5 rounded hover:bg-slate-100 dark:hover:bg-white/10 text-gray-500 dark:text-white/60 transition relative" data-action="more" title="More"><i class="bi bi-three-dots text-lg"></i></button>
                ${ownDropdown}
            </div>
            <img src="${avatar}" class="size-10 flex-shrink-0 rounded-full object-cover" alt="${escapeHtml(userName)}" onerror="this.src='https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(userName)}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981'">
            <div class="msg-content-wrapper flex flex-1 flex-col min-w-0">
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                    <p class="text-sm font-medium own-message-name" style="color: #2563eb !important;">${escapeHtml(user?.name || 'You')}</p>
                    ${moderatorBadge}
                    <p class="text-xs md:text-sm text-gray-500 dark:text-[#9d9db9]">${timestamp}</p>
                    ${sendingIndicator}
                </div>
                ${bodyContent}
            </div>
        `;

        messagesContainer.querySelector('.border-dashed')?.remove();
        messagesContainer.appendChild(messageEl);
        
        const scrollContainer = document.getElementById('messages-scroll-container');
        if (scrollContainer) scrollContainer.scrollTop = scrollContainer.scrollHeight;
        
        return messageEl;
    }
    
    window.updateMessageStatus = function(messageEl, status) {
        const statusEl = messageEl?.querySelector('.msg-status');
        if (!statusEl) return;
        
        if (status === 'sent') {
            statusEl.innerHTML = '<i class="bi bi-check2"></i> Sent';
            statusEl.classList.remove('text-gray-400', 'dark:text-white/40');
            statusEl.classList.add('text-green-500', 'dark:text-green-400');
            setTimeout(() => statusEl.remove(), 3000);
        } else if (status === 'failed') {
            statusEl.innerHTML = '<i class="bi bi-x-circle"></i> Failed';
            statusEl.classList.remove('text-gray-400', 'dark:text-white/40');
            statusEl.classList.add('text-red-500', 'dark:text-red-400');
        }
    }

    // Mention autocomplete
    const mentionState = {
        isOpen: false,
        query: '',
        startPos: 0,
        selectedIndex: 0,
        members: []
    };

    function createMentionDropdown() {
        let dropdown = document.getElementById('mention-autocomplete');
        if (!dropdown) {
            dropdown = document.createElement('div');
            dropdown.id = 'mention-autocomplete';
            dropdown.className = 'hidden absolute bottom-full left-0 mb-2 w-64 max-h-48 overflow-y-auto rounded-lg border border-gray-200 dark:border-white/10 shadow-xl z-50';
            dropdown.style.backgroundColor = document.documentElement.classList.contains('dark') ? '#2d2d30' : '#ffffff';
            document.querySelector('.chat-v2-composer')?.appendChild(dropdown);
        }
        return dropdown;
    }

    function loadConversationMembers() {
        const convId = window.currentConversationId;
        if (!convId) return;
        
        fetch(`/chats/${convId}/members-list`)
            .then(r => r.json())
            .then(data => {
                mentionState.members = data.members || [];
            })
            .catch(() => {});
    }

    function showMentionDropdown(input) {
        const dropdown = createMentionDropdown();
        const isModerator = @json(auth()->user()->isModerator());
        const currentUserId = @json(auth()->id());
        
        let filtered = mentionState.members.filter(m => 
            m.id !== currentUserId && m.name.toLowerCase().includes(mentionState.query.toLowerCase())
        ).slice(0, 8);

        // Add @everyone option for moderators
        if (isModerator && 'everyone'.includes(mentionState.query.toLowerCase())) {
            filtered.unshift({ id: 'everyone', name: 'everyone', isSpecial: true });
        }

        if (filtered.length === 0) {
            dropdown.classList.add('hidden');
            mentionState.isOpen = false;
            return;
        }

        mentionState.selectedIndex = 0;
        
        dropdown.innerHTML = filtered.map((m, i) => `
            <button type="button" class="mention-item w-full flex items-center gap-2 px-3 py-2 text-sm text-left hover:bg-gray-100 dark:hover:bg-white/15 transition ${i === 0 ? 'bg-gray-100 dark:bg-white/15' : ''}" data-index="${i}" data-name="${m.name}">
                ${m.isSpecial 
                    ? '<div class="w-6 h-6 rounded-full bg-amber-500/20 flex items-center justify-center"><i class="bi bi-megaphone-fill text-amber-500 text-xs"></i></div>'
                    : `<img src="${m.avatar || 'https://api.dicebear.com/7.x/avataaars/svg?seed=' + encodeURIComponent(m.name)}" class="w-6 h-6 rounded-full object-cover">`
                }
                <span class="text-gray-800 dark:text-white">${m.isSpecial ? '@everyone' : m.name}</span>
                ${m.isSpecial ? '<span class="ml-auto text-xs text-amber-500">Notify all</span>' : ''}
            </button>
        `).join('');

        dropdown.classList.remove('hidden');
        mentionState.isOpen = true;

        dropdown.querySelectorAll('.mention-item').forEach(item => {
            item.addEventListener('click', () => selectMention(input, item.dataset.name));
        });
    }

    function selectMention(input, name) {
        const before = input.value.substring(0, mentionState.startPos);
        const after = input.value.substring(input.selectionStart);
        input.value = before + name + ' ' + after;
        input.focus();
        const newPos = mentionState.startPos + name.length + 1;
        input.setSelectionRange(newPos, newPos);
        closeMentionDropdown();
    }

    function closeMentionDropdown() {
        const dropdown = document.getElementById('mention-autocomplete');
        dropdown?.classList.add('hidden');
        mentionState.isOpen = false;
    }

    function handleMentionKeydown(e, input) {
        if (!mentionState.isOpen) return false;
        
        const dropdown = document.getElementById('mention-autocomplete');
        const items = dropdown?.querySelectorAll('.mention-item');
        if (!items?.length) return false;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            mentionState.selectedIndex = Math.min(mentionState.selectedIndex + 1, items.length - 1);
            updateMentionSelection(items);
            return true;
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            mentionState.selectedIndex = Math.max(mentionState.selectedIndex - 1, 0);
            updateMentionSelection(items);
            return true;
        }
        if (e.key === 'Enter' || e.key === 'Tab') {
            e.preventDefault();
            const selected = items[mentionState.selectedIndex];
            if (selected) selectMention(input, selected.dataset.name);
            return true;
        }
        if (e.key === 'Escape') {
            closeMentionDropdown();
            return true;
        }
        return false;
    }

    function updateMentionSelection(items) {
        items.forEach((item, i) => {
            item.classList.toggle('bg-gray-100', i === mentionState.selectedIndex);
            item.classList.toggle('dark:bg-white/15', i === mentionState.selectedIndex);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const composerInput = document.querySelector('#chat-v2-message-input');
        const chatForm = document.querySelector('#chat-v2-form');

        // Load members for mention autocomplete
        loadConversationMembers();

        if (composerInput) {
            const isModerator = @json(auth()->user()->isModerator());
            
            composerInput.addEventListener('input', (e) => {
                sendTypingPing();
                
                if (!isModerator) return;
                
                const val = e.target.value;
                const pos = e.target.selectionStart;
                const textBefore = val.substring(0, pos);
                const atMatch = textBefore.match(/@(\w*)$/);
                
                if (atMatch) {
                    mentionState.startPos = pos - atMatch[1].length;
                    mentionState.query = atMatch[1];
                    showMentionDropdown(e.target);
                } else {
                    closeMentionDropdown();
                }
            });

            composerInput.addEventListener('keydown', (e) => {
                if (isModerator && handleMentionKeydown(e, composerInput)) return;
                
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    chatForm?.dispatchEvent(new Event('submit'));
                }
            });

            composerInput.addEventListener('blur', () => {
                setTimeout(closeMentionDropdown, 150);
            });
        }

        if (chatForm) {
            chatForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const input = chatForm.querySelector('#chat-v2-message-input');
                const body = input?.value?.trim();
                const conversationId = chatForm.dataset.conversationId;
                const videoFile = window.getSelectedVideoFile ? window.getSelectedVideoFile() : null;
                const attachments = window.getSelectedAttachments ? window.getSelectedAttachments().slice() : []; // Copy array

                if ((!body && !videoFile && attachments.length === 0) || !conversationId) return;

                // Clear inputs immediately
                input.value = '';
                if (window.resetComposerHeight) window.resetComposerHeight();
                if (window.clearSelectedVideo) window.clearSelectedVideo();
                if (window.clearSelectedAttachments) window.clearSelectedAttachments();

                let tempEl = null;
                if (attachments.length > 0) {
                    const hasImages = attachments.some(f => f.type.startsWith('image/'));
                    tempEl = renderOutgoingMessage(body || (hasImages ? 'Uploading image...' : 'Uploading file...'), null, hasImages ? 'image' : 'file');
                } else if (body && !videoFile) {
                    tempEl = renderOutgoingMessage(body);
                } else if (videoFile) {
                    tempEl = renderOutgoingMessage(body || 'Uploading video...', null, 'video');
                }

                const result = await sendMessage(conversationId, body, videoFile, attachments);

                if (result && result.message) {
                    const msg = result.message;
                    if (tempEl) {
                        tempEl.dataset.messageId = msg.id;
                        const dropdown = tempEl.querySelector('.msg-more-dropdown');
                        if (dropdown) dropdown.dataset.messageId = msg.id;
                        if (videoFile && msg.attachments?.length > 0) {
                            const attachment = msg.attachments[0];
                            // Use stream route for video playback (route is under /chats prefix)
                            const videoUrl = `/chats/attachments/${attachment.id}/stream`;
                            const downloadUrl = `/chats/attachments/${attachment.id}/download`;
                            const userIsModerator = @json(auth()->user()->isModerator());
                            const userRoleForBadge = @json(auth()->user()->role);
                            const badgeLabelForVideo = userRoleForBadge === 'super_admin' ? 'Super Admin' : (userRoleForBadge === 'admin' ? 'Admin' : 'Moderator');
                            const modBadge = userIsModerator ? `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-500/20 text-blue-400"><i class="bi bi-patch-check-fill mr-0.5"></i>${badgeLabelForVideo}</span>` : '';
                            const supportedFormats = ['video/mp4', 'video/webm', 'video/ogg'];
                            const isSupportedFormat = supportedFormats.includes(attachment.mime);
                            const fileSize = attachment.size ? (attachment.size / 1024 / 1024).toFixed(1) + ' MB' : '';
                            
                            let videoHtml;
                            if (isSupportedFormat) {
                                videoHtml = `
                                    <div class="relative rounded-xl overflow-hidden bg-black">
                                        <video src="${videoUrl}" class="w-full max-h-80 object-contain" controls preload="metadata" playsinline></video>
                                    </div>
                                `;
                            } else {
                                const ext = attachment.original_name?.split('.').pop()?.toUpperCase() || 'VIDEO';
                                videoHtml = `
                                    <button type="button" onclick="window.openVideoPreview('${videoUrl}', '${downloadUrl}', '${escapeHtml(attachment.original_name || 'Video')}', '${fileSize}', '${attachment.mime}')" class="w-full block rounded-xl overflow-hidden bg-gradient-to-br from-purple-500/20 to-indigo-500/20 border border-purple-500/30 hover:border-purple-500/50 transition text-left">
                                        <div class="flex items-center justify-center h-40 bg-black/20">
                                            <div class="text-center">
                                                <i class="bi bi-play-circle text-5xl text-purple-400"></i>
                                                <p class="text-xs text-purple-300 mt-2">${ext} format</p>
                                                <p class="text-[10px] text-white/50 mt-1">Click to preview</p>
                                            </div>
                                        </div>
                                    </button>
                                `;
                            }
                            
                            const contentEl = tempEl.querySelector('.msg-content-wrapper');
                            if (contentEl) {
                                contentEl.innerHTML = `
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                        <p class="text-sm font-medium own-message-name" style="color: #2563eb !important;">${escapeHtml(msg.user?.name || 'You')}</p>
                                        ${modBadge}
                                        <p class="text-xs md:text-sm text-gray-500 dark:text-[#9d9db9]">${new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })}</p>
                                    </div>
                                    ${body ? `<p class="text-sm md:text-base leading-relaxed text-gray-700 dark:text-white/90 whitespace-pre-line break-words">${escapeHtml(body)}</p>` : ''}
                                    <div class="max-w-lg mt-2">
                                        ${videoHtml}
                                        <div class="flex items-center gap-2 mt-2 text-xs text-gray-500 dark:text-white/50">
                                            <i class="bi bi-camera-video-fill text-purple-500"></i>
                                            <span class="truncate">${escapeHtml(attachment.original_name || 'Video')}</span>
                                            ${fileSize ? `<span class="text-gray-400 dark:text-white/40">·</span><span>${fileSize}</span>` : ''}
                                            <a href="${downloadUrl}" class="ml-auto text-purple-500 hover:text-purple-400"><i class="bi bi-download"></i></a>
                                        </div>
                                    </div>
                                `;
                            }
                        } else if (msg.attachments?.length > 0) {
                            // Handle image/file attachments (use route-based URLs like videos)
                            const attHtml = msg.attachments.map(a => {
                                const streamUrl = `/chats/attachments/${a.id}/stream`;
                                const downloadUrl = `/chats/attachments/${a.id}/download`;
                                if (a.mime?.startsWith('image/')) {
                                    return `<div class="img-skeleton rounded-lg"><img src="${streamUrl}" alt="${escapeHtml(a.file_name || 'Image')}" class="max-w-xs rounded-lg cursor-pointer hover:opacity-90 transition" loading="lazy" onclick="window.open(this.src, '_blank')" onload="this.parentElement.classList.add('loaded')"></div>`;
                                }
                                return `<a href="${downloadUrl}" target="_blank" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/15 transition">
                                    <i class="bi bi-file-earmark text-lg"></i>
                                    <span class="text-sm truncate">${escapeHtml(a.file_name || 'File')}</span>
                                </a>`;
                            }).join('');
                            
                            const contentEl = tempEl.querySelector('.msg-content-wrapper');
                            if (contentEl) {
                                const userIsModerator = @json(auth()->user()->isModerator());
                                const userRoleForAttach = @json(auth()->user()->role);
                                const badgeLabelForAttach = userRoleForAttach === 'super_admin' ? 'SUPER' : (userRoleForAttach === 'admin' ? 'ADMIN' : 'MOD');
                                const modBadge = userIsModerator ? `<span class="inline-flex items-center text-[10px] font-medium text-blue-500"><i class="bi bi-patch-check-fill mr-0.5"></i>${badgeLabelForAttach}</span>` : '';
                                contentEl.innerHTML = `
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                        <p class="text-sm font-medium own-message-name" style="color: #2563eb !important;">${escapeHtml(msg.user?.name || 'You')}</p>
                                        ${modBadge}
                                        <p class="text-xs md:text-sm text-gray-500 dark:text-[#9d9db9]">${new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })}</p>
                                    </div>
                                    ${body ? `<p class="text-sm md:text-base text-gray-700 dark:text-white/90 whitespace-pre-line break-words">${highlightMentions(escapeHtml(body))}</p>` : ''}
                                    <div class="flex flex-col gap-2 mt-1">${attHtml}</div>
                                `;
                            }
                        }
                        updateMessageStatus(tempEl, 'sent');
                    }
                } else if (!result) {
                    if (tempEl) updateMessageStatus(tempEl, 'failed');
                    tempEl?.classList.add('opacity-50');
                } else if (result.bot_response || result.blocked) {
                    if (tempEl) tempEl.querySelector('.msg-status')?.remove();
                }
            });
        }
    });
</script>
