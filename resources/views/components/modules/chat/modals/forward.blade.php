{{-- Forward Message Modal --}}
@php
    $userPersonalTags = \App\Models\PersonalTag::where('user_id', auth()->id())->orderBy('position')->get();
@endphp

<div data-chat-modal="forward" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-10 overflow-y-auto">
    <div class="w-full max-w-md rounded-2xl bg-white dark:bg-sidebar-dark p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Forward Message</h3>
            </div>
            <button type="button" data-chat-modal-close="forward"
                class="rounded-full p-2 text-slate-400 dark:text-white/60 transition hover:bg-slate-100 dark:hover:bg-white/10">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <input type="hidden" id="forward-message-id" value="">

        {{-- Message Preview --}}
        <div id="forward-message-preview" class="rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 p-3 mb-4">
            <p class="text-xs text-slate-500 dark:text-white/50 mb-1">Message to forward:</p>
            <p id="forward-message-body" class="text-sm text-slate-700 dark:text-white/80 line-clamp-3"></p>
        </div>

        {{-- Search --}}
        <div class="mb-4">
            <label class="text-sm font-medium text-slate-700 dark:text-white/80 mb-2 block">Select destinations</label>
            <input type="text" id="forward-search" placeholder="Search..."
                class="w-full rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-white/40 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
        </div>

        {{-- Loading State --}}
        <div id="forward-loading" class="py-8 text-center">
            <i class="bi bi-arrow-repeat animate-spin text-2xl text-slate-400"></i>
            <p class="text-sm text-slate-500 dark:text-white/50 mt-2">Loading destinations...</p>
        </div>

        {{-- Destinations List --}}
        <div id="forward-conversations-list" class="max-h-60 overflow-y-auto space-y-1 mb-4 hidden">
            {{-- Topics Section --}}
            <div id="forward-topics-section" class="hidden">
                <p class="text-xs font-bold uppercase text-slate-400 dark:text-white/40 px-2 py-1">Channels</p>
                <div id="forward-topics-list"></div>
                <div class="border-t border-slate-200 dark:border-white/10 my-2"></div>
            </div>

            {{-- Personal Tags Section --}}
            @if($userPersonalTags->isNotEmpty())
                <p class="text-xs font-bold uppercase text-slate-400 dark:text-white/40 px-2 py-1">My Tags</p>
                @foreach($userPersonalTags as $tag)
                    <label class="forward-conv-item flex items-center gap-3 p-2 rounded-lg cursor-pointer hover:bg-slate-100 dark:hover:bg-white/10 transition"
                        data-conv-name="{{ strtolower($tag->name) }}" data-type="tag">
                        <input type="checkbox" name="forward_tags[]" value="{{ $tag->id }}"
                            class="rounded border-slate-300 dark:border-white/30 text-indigo-600 focus:ring-indigo-500">
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            <i class="bi bi-bookmark-fill" style="color: {{ $tag->color }};"></i>
                            <span class="text-sm text-slate-700 dark:text-white/80 truncate">{{ $tag->name }}</span>
                            @if($tag->is_private)
                                <i class="bi bi-lock text-xs text-slate-400 dark:text-white/30"></i>
                            @endif
                        </div>
                    </label>
                @endforeach
                <div class="border-t border-slate-200 dark:border-white/10 my-2"></div>
            @endif

            {{-- My Conversations Section --}}
            <p class="text-xs font-bold uppercase text-slate-400 dark:text-white/40 px-2 py-1">My Conversations</p>
            <div id="forward-my-conversations"></div>

            {{-- Public Groups Section --}}
            <div id="forward-public-section" class="hidden">
                <div class="border-t border-slate-200 dark:border-white/10 my-2"></div>
                <p class="text-xs font-bold uppercase text-slate-400 dark:text-white/40 px-2 py-1">Public Groups</p>
                <div id="forward-public-groups"></div>
            </div>
        </div>

        {{-- Selected Count --}}
        <p id="forward-selected-count" class="text-xs text-slate-500 dark:text-white/50 mb-4">0 destinations selected</p>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-2 border-t border-slate-200 dark:border-white/10">
            <button type="button" data-chat-modal-close="forward"
                class="rounded-lg border border-slate-200 dark:border-white/10 px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white/70 transition hover:border-slate-300 dark:hover:border-white/30">
                Cancel
            </button>
            <button type="button" id="forward-submit-btn" disabled
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed" style="background-color: #2b2bee;">
                <i class="bi bi-forward-fill"></i>
                <span>Forward</span>
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-chat-modal="forward"]');
    const messageIdInput = document.getElementById('forward-message-id');
    const messageBody = document.getElementById('forward-message-body');
    const searchInput = document.getElementById('forward-search');
    const convList = document.getElementById('forward-conversations-list');
    const loadingEl = document.getElementById('forward-loading');
    const selectedCount = document.getElementById('forward-selected-count');
    const submitBtn = document.getElementById('forward-submit-btn');
    const topicsSection = document.getElementById('forward-topics-section');
    const topicsList = document.getElementById('forward-topics-list');
    const myConversations = document.getElementById('forward-my-conversations');
    const publicSection = document.getElementById('forward-public-section');
    const publicGroups = document.getElementById('forward-public-groups');

    if (!modal) return;

    const updateSelectedCount = () => {
        const checked = convList.querySelectorAll('input[type="checkbox"]:checked');
        selectedCount.textContent = `${checked.length} destination${checked.length !== 1 ? 's' : ''} selected`;
        submitBtn.disabled = checked.length === 0;
    };

    const renderConversation = (conv) => `
        <label class="forward-conv-item flex items-center gap-3 p-2 rounded-lg cursor-pointer hover:bg-slate-100 dark:hover:bg-white/10 transition"
            data-conv-name="${(conv.name || '').toLowerCase()}" data-type="conversation">
            <input type="checkbox" name="forward_conversations[]" value="${conv.id}"
                class="rounded border-slate-300 dark:border-white/30 text-indigo-600 focus:ring-indigo-500">
            <div class="flex items-center gap-2 flex-1 min-w-0">
                ${conv.photo 
                    ? `<img src="${conv.photo}" class="w-6 h-6 rounded-full object-cover" alt="">`
                    : conv.type === 'group' 
                        ? '<i class="bi bi-people-fill text-slate-500 dark:text-white/60"></i>'
                        : '<i class="bi bi-person-fill text-slate-500 dark:text-white/60"></i>'
                }
                <span class="text-sm text-slate-700 dark:text-white/80 truncate">${conv.name}</span>
                ${conv.is_public ? '<i class="bi bi-globe text-xs text-green-500"></i>' : ''}
            </div>
        </label>
    `;

    const loadDestinations = async () => {
        loadingEl.classList.remove('hidden');
        convList.classList.add('hidden');

        try {
            // Load conversations
            const convResponse = await fetch('/chats/forward-destinations', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            
            if (convResponse.ok) {
                const data = await convResponse.json();
                const conversations = data.conversations || [];
                const publicGroupsList = data.public_groups || [];

                // Render my conversations
                if (conversations.length > 0) {
                    myConversations.innerHTML = conversations.map(renderConversation).join('');
                } else {
                    myConversations.innerHTML = '<p class="text-xs text-slate-400 dark:text-white/30 px-2 py-2">No conversations yet</p>';
                }

                // Render public groups
                if (publicGroupsList.length > 0) {
                    publicSection.classList.remove('hidden');
                    publicGroups.innerHTML = publicGroupsList.map(renderConversation).join('');
                } else {
                    publicSection.classList.add('hidden');
                }
            }

            // Load topics for current conversation
            const convId = window.currentConversationId;
            if (convId) {
                const topicsResponse = await fetch(`/chats/${convId}/topics`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                
                if (topicsResponse.ok) {
                    const topicsData = await topicsResponse.json();
                    const topics = topicsData.topics || topicsData || [];
                    
                    if (topics.length > 0) {
                        topicsSection.classList.remove('hidden');
                        topicsList.innerHTML = topics.map(topic => `
                            <label class="forward-conv-item flex items-center gap-3 p-2 rounded-lg cursor-pointer hover:bg-slate-100 dark:hover:bg-white/10 transition"
                                data-conv-name="${(topic.name || '').toLowerCase()}" data-type="topic">
                                <input type="checkbox" name="forward_topics[]" value="${topic.id}"
                                    class="rounded border-slate-300 dark:border-white/30 text-indigo-600 focus:ring-indigo-500">
                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                    <i class="bi bi-hash text-slate-500 dark:text-white/60"></i>
                                    <span class="text-sm text-slate-700 dark:text-white/80 truncate">${topic.name || topic.slug}</span>
                                    ${topic.is_readonly ? '<i class="bi bi-lock text-xs text-slate-400 dark:text-white/30"></i>' : ''}
                                </div>
                            </label>
                        `).join('');
                    } else {
                        topicsSection.classList.add('hidden');
                    }
                }
            } else {
                topicsSection.classList.add('hidden');
            }
        } catch (e) {
            console.error('Failed to load destinations:', e);
            myConversations.innerHTML = '<p class="text-xs text-red-500 px-2 py-2">Failed to load conversations</p>';
        } finally {
            loadingEl.classList.add('hidden');
            convList.classList.remove('hidden');
        }
    };

    // Load when modal opens
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                if (!modal.classList.contains('hidden')) {
                    loadDestinations();
                } else {
                    convList.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                    searchInput.value = '';
                    convList.querySelectorAll('.forward-conv-item').forEach(item => item.style.display = '');
                    updateSelectedCount();
                }
            }
        });
    });
    observer.observe(modal, { attributes: true });

    // Search filter
    searchInput?.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();
        convList.querySelectorAll('.forward-conv-item').forEach(item => {
            const name = item.dataset.convName || '';
            item.style.display = name.includes(query) ? '' : 'none';
        });
    });

    // Checkbox change
    convList?.addEventListener('change', updateSelectedCount);

    // Submit forward
    submitBtn?.addEventListener('click', async () => {
        const messageId = messageIdInput.value;
        const checkedConvs = convList.querySelectorAll('input[name="forward_conversations[]"]:checked');
        const checkedTags = convList.querySelectorAll('input[name="forward_tags[]"]:checked');
        const checkedTopics = convList.querySelectorAll('input[name="forward_topics[]"]:checked');
        const conversationIds = Array.from(checkedConvs).map(cb => cb.value);
        const tagIds = Array.from(checkedTags).map(cb => cb.value);
        const topicIds = Array.from(checkedTopics).map(cb => cb.value);

        if (!messageId || (conversationIds.length === 0 && tagIds.length === 0 && topicIds.length === 0)) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i><span>Forwarding...</span>';

        let forwardedCount = 0;
        const errors = [];

        try {
            // Forward to conversations
            if (conversationIds.length > 0) {
                const response = await fetch(`/chats/messages/${messageId}/forward`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ conversation_ids: conversationIds }),
                });

                const data = await response.json();
                if (response.ok) {
                    forwardedCount += data.forwarded_count || conversationIds.length;
                } else {
                    errors.push(data.message || 'Failed to forward to conversations');
                }
            }

            // Forward to topics
            if (topicIds.length > 0) {
                const convId = window.currentConversationId;
                for (const topicId of topicIds) {
                    const response = await fetch(`/chats/messages/${messageId}/forward`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ conversation_ids: [convId], topic_id: topicId }),
                    });

                    if (response.ok) forwardedCount++;
                }
            }

            // Forward to personal tags
            if (tagIds.length > 0) {
                const msgBodyText = messageBody?.textContent || '';
                for (const tagId of tagIds) {
                    const response = await fetch(`/personal-tags/${tagId}/messages`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({ body: msgBodyText, forwarded_from_message_id: messageId }),
                    });

                    if (response.ok) forwardedCount++;
                }
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            
            if (forwardedCount > 0) {
                if (typeof showChatToast === 'function') {
                    showChatToast(`Forwarded to ${forwardedCount} destination${forwardedCount !== 1 ? 's' : ''}`, 'success');
                }
            } else if (errors.length > 0) {
                throw new Error(errors[0]);
            }
        } catch (error) {
            console.error('Forward error:', error);
            if (typeof showChatToast === 'function') {
                showChatToast(error.message || 'Forward failed', 'error');
            }
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-forward-fill"></i><span>Forward</span>';
        }
    });
});
</script>
