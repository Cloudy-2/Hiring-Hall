<div id="hs-overlay-right" class="hs-overlay hidden ti-offcanvas ti-offcanvas-right ai-chat-drawer" tabindex="-1">
    <div class="ti-offcanvas-header ai-chat-header">
        <div class="flex items-center gap-3">
            <div class="ai-brand-avatar ai-brand-icon-avatar">
                <div class="ai-brand-icon-inner">
                    <i class="ti ti-sparkles"></i>
                </div>
                <span class="ai-online-badge"></span>
            </div>
            <div>
                <h5 class="ti-offcanvas-title !text-[1.05rem] !font-semibold mb-0">
                    <strong>Ask Hill AI</strong>
                </h5>
                <p class="mb-0 text-[13px] text-textmuted dark:text-white/50">
                    Quick answers, Clear guidance
                </p>
                @auth
                    @php
                        $hillAiFirst = '';
                        $hillFull = trim((string) Auth::user()->name);
                        if ($hillFull !== '') {
                            $hillParts = preg_split('/\s+/u', $hillFull, 2, PREG_SPLIT_NO_EMPTY);
                            $hillAiFirst = isset($hillParts[0]) ? \Illuminate\Support\Str::limit($hillParts[0], 40, '') : '';
                        }
                    @endphp
                    {{-- @if ($hillAiFirst !== '')
                        <p class="mb-0 text-[10px] text-textmuted dark:text-white/45 mt-0.5">
                            Chatting as <span class="font-semibold text-textmuted/90 dark:text-white/55">{{ $hillAiFirst }}</span>
                        </p>
                    @endif --}}
                @endauth
            </div>
        </div>

        <button type="button"
            id="aiDrawerCloseBtn"
            class="ti-btn btn-wave flex-shrink-0 p-0 transition-none text-gray-500 hover:text-gray-700 focus:ring-gray-400 focus:ring-offset-white text-textmuted dark:text-textmuted/50 dark:hover:text-white/80 dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
            data-hs-overlay="#hs-overlay-right">
            <span class="sr-only">Close modal</span>
            <svg class="w-3.5 h-3.5" width="8" height="8" viewBox="0 0 8 8" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0.258206 1.00652C0.351976 0.912791 0.479126 0.860131 0.611706 0.860131C0.744296 0.860131 0.871447 0.912791 0.965207 1.00652L3.61171 3.65302L6.25822 1.00652C6.30432 0.958771 6.35952 0.920671 6.42052 0.894471C6.48152 0.868271 6.54712 0.854471 6.61352 0.853901C6.67992 0.853321 6.74572 0.865971 6.80722 0.891111C6.86862 0.916251 6.92442 0.953381 6.97142 1.00032C7.01832 1.04727 7.05552 1.1031 7.08062 1.16454C7.10572 1.22599 7.11842 1.29183 7.11782 1.35822C7.11722 1.42461 7.10342 1.49022 7.07722 1.55122C7.05102 1.61222 7.01292 1.6674 6.96522 1.71352L4.31871 4.36002L6.96522 7.00648C7.05632 7.10078 7.10672 7.22708 7.10552 7.35818C7.10442 7.48928 7.05182 7.61468 6.95912 7.70738C6.86642 7.80018 6.74102 7.85268 6.60992 7.85388C6.47882 7.85498 6.35252 7.80458 6.25822 7.71348L3.61171 5.06702L0.965207 7.71348C0.870907 7.80458 0.744606 7.85498 0.613506 7.85388C0.482406 7.85268 0.357007 7.80018 0.264297 7.70738C0.171597 7.61468 0.119017 7.48928 0.117877 7.35818C0.116737 7.22708 0.167126 7.10078 0.258206 7.00648L2.90471 4.36002L0.258206 1.71352C0.164476 1.61976 0.111816 1.4926 0.111816 1.36002C0.111816 1.22744 0.164476 1.10028 0.258206 1.00652Z"
                    fill="currentColor"></path>
            </svg>
        </button>
    </div>

    <div class="ti-offcanvas-body !p-0 ai-chat-body-wrap">
        <div class="ai-chat-layout">

            <div class="ai-tabs-bar">
                <button class="ai-top-chip active" id="tabChatBtn" type="button" onclick="switchAiTab('chat')">
                    Chat
                </button>
                <button class="ai-top-chip" id="tabHistoryBtn" type="button" onclick="switchAiTab('history')">
                    History
                </button>

                <div class="ms-auto flex items-center gap-2">
                    <button class="ai-icon-btn" type="button" title="New Chat" onclick="resetAiConversation()">
                        <i class="ti ti-plus text-[18px]"></i>
                    </button>
                </div>
            </div>

            <div id="aiChatTab" class="ai-tab-panel active">
                <div id="aiChatScrollArea" class="ai-chat-scroll">

                    <div id="aiDefaultState">
                        <div class="ai-welcome-block">
                            <div class="ai-center-logo">
                                <div class="ai-logo-orb">
                                    <i class="bi bi-rocket-takeoff"></i>
                                </div>
                            </div>

                            <h2 class="ai-welcome-title">
                                @auth
                                    <strong>Hey, {{ explode(' ', Auth::user()->name)[0] }}</strong><span> 👋</span>
                                @else
                                    <strong>Hi there</strong><span> 👋</span>
                                @endauth
                            </h2>
                            <p id="aiWelcomeTyping" class="ai-welcome-subtitle"></p>
                        </div>

                        <div class="ai-suggestion-list">
                            <button class="ai-suggestion-item" onclick="fillAiPrompt('How do I apply for a job?')">
                                <span class="ai-suggestion-icon"><i class="ti ti-arrow-up-right"></i></span>
                                <span>How do I apply for a job?</span>
                            </button>

                            <button class="ai-suggestion-item"
                                onclick="fillAiPrompt('How do I post a job as an employer?')">
                                <span class="ai-suggestion-icon"><i class="ti ti-arrow-up-right"></i></span>
                                <span>How do employers post a job?</span>
                            </button>

                            <button class="ai-suggestion-item"
                                onclick="fillAiPrompt('What can you help me with on Hiring Hall?')">
                                <span class="ai-suggestion-icon"><i class="ti ti-arrow-up-right"></i></span>
                                <span>What can you help me with?</span>
                            </button>

                            <button class="ai-suggestion-item" onclick="fillAiPrompt('How do I reset my password?')">
                                <span class="ai-suggestion-icon"><i class="ti ti-arrow-up-right"></i></span>
                                <span>How do I reset my password?</span>
                            </button>

                            <button class="ai-suggestion-item"
                                onclick="fillAiPrompt('How do I contact support or open a ticket?')">
                                <span class="ai-suggestion-icon"><i class="ti ti-arrow-up-right"></i></span>
                                <span>How do I contact support?</span>
                            </button>
                        </div>

                        <div class="ai-category-pills">
                            <button type="button" class="ai-cat-pill active" onclick="window.location.href='{{ url('/FAQ') }}'">Help center</button>
                            <button type="button" class="ai-cat-pill" onclick="fillAiPrompt('Applicants: how do I edit my profile?')">Applicants</button>
                            <button type="button" class="ai-cat-pill" onclick="fillAiPrompt('Employers: how does company verification work?')">Employers</button>
                            <button type="button" class="ai-cat-pill" onclick="fillAiPrompt('How does two-factor authentication work?')">Security</button>
                            <button type="button" class="ai-cat-pill" onclick="fillAiPrompt('Where can I browse all help articles and guides?')">More topics</button>
                        </div>
                    </div>

                    <div id="aiConversationState" class="hidden">
                        <div id="aiMessageList" class="ai-message-list"></div>
                    </div>

                    <div class="ai-disclaimer">
                        I answer from Hill’s official help library—I don’t see your private account or messages. For the full library, open the <a href="{{ url('/FAQ') }}" class="underline decoration-dotted hover:opacity-90">help center</a>.
                        @auth
                            <span class="block mt-2 text-[11px]">
                                <button type="button" class="underline decoration-dotted hover:opacity-90 text-textmuted dark:text-white/55"
                                    onclick="submitAiEscalation()">Escalate to support</button>
                            </span>
                        @endauth
                    </div>
                    <br>
                    <br>
                    <br>
                </div>

                <div class="ai-bottom-bar">
                    <div class="ai-input-meta flex justify-end px-1 mb-1">
                        <span class="text-[10px] text-textmuted dark:text-white/45 tabular-nums" id="aiCharCountWrap" aria-live="polite">
                            <span id="aiCharCount">0</span>/<span id="aiCharMaxLabel">{{ (int) config('faq_chat.max_message_chars', 500) }}</span>
                        </span>
                    </div>
                    <div class="ai-input-shell">
                        <textarea id="aiChatInput" class="ai-chat-input" rows="1" maxlength="{{ (int) config('faq_chat.max_message_chars', 500) }}" placeholder="Ask anything about Hiring Hall…"></textarea>

                        <div class="ai-input-actions">
                            <button type="button" class="ai-round-btn ai-attach-btn" title="Voice / Action">
                                <i class="ti ti-microphone"></i>
                            </button>

                            <button type="button" id="aiSendBtn" class="ai-round-btn ai-send-btn"
                                onclick="sendAiDrawerMessage()" title="Send">
                                <i class="ti ti-arrow-up"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="aiIdleFeedbackOverlay" class="ai-idle-feedback-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="aiIdleFeedbackTitle" aria-hidden="true">
                    <div class="ai-idle-feedback-card">
                        <h4 id="aiIdleFeedbackTitle" class="!text-[1rem] !font-semibold mb-1">How was this chat?</h4>
                        <p class="text-[11px] text-textmuted dark:text-white/55 mb-3 leading-snug">You’ve been quiet for a while, so this session was paused to save resources. A quick rating helps us improve.</p>

                        <p class="text-[10px] uppercase tracking-wide text-textmuted dark:text-white/45 mb-1">Mood</p>
                        <div class="ai-feedback-emoji-row mb-3" role="group" aria-label="Quick mood">
                            <button type="button" class="ai-emoji-btn" data-sentiment="great" title="Great">😀</button>
                            <button type="button" class="ai-emoji-btn" data-sentiment="ok" title="Okay">😐</button>
                            <button type="button" class="ai-emoji-btn" data-sentiment="poor" title="Not great">😞</button>
                        </div>

                        <p class="text-[10px] uppercase tracking-wide text-textmuted dark:text-white/45 mb-1">Stars</p>
                        <div class="ai-feedback-stars mb-3" role="group" aria-label="Star rating">
                            @for ($s = 1; $s <= 5; $s++)
                                <button type="button" class="ai-star-btn" data-star="{{ $s }}" aria-label="{{ $s }} {{ $s === 1 ? 'star' : 'stars' }}">★</button>
                            @endfor
                        </div>

                        <label for="aiFeedbackComment" class="sr-only">Suggestions (optional)</label>
                        <textarea id="aiFeedbackComment" class="ai-feedback-comment" rows="2" maxlength="{{ (int) config('faq_chat.max_feedback_comment_chars', 500) }}" placeholder="Anything we should improve? (optional)"></textarea>

                        <div class="ai-feedback-actions">
                            <button type="button" class="ai-feedback-submit" id="aiFeedbackSubmitBtn" onclick="submitAiIdleFeedback(false)">Submit</button>
                            <button type="button" class="ai-feedback-skip" onclick="submitAiIdleFeedback(true)">Skip</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="aiHistoryTab" class="ai-tab-panel">
                <div class="ai-history-wrap">

                    <div id="aiHistoryListView" class="ai-history-list-view">
                        <div class="ai-history-header">
                            <p class="mb-0">Chats are saved so you can continue later and so we can keep improving responses.</p>
                        </div>

                        <div class="ai-history-list" id="aiHistoryList">
                            <p class="text-sm text-textmuted dark:text-white/50 px-1 mb-0" id="aiHistoryEmpty">Loading conversations…</p>
                        </div>
                    </div>

                    <div id="aiHistoryDetailView" class="ai-history-detail-view hidden">
                        <div class="ai-history-detail-header">
                            <button type="button" class="ai-back-btn" onclick="closeHistoryConversation()">
                                <i class="ti ti-arrow-left"></i>
                            </button>

                            <div>
                                <h4 id="aiHistoryDetailTitle" class="mb-1">Conversation Details</h4>
                                <p id="aiHistoryDetailMeta" class="mb-0">Conversation</p>
                            </div>

                            <button type="button" id="aiHistoryDeleteBtn" class="ai-history-delete-btn" onclick="deleteHistoryConversation()" title="Delete history">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>

                        <div id="aiHistoryConversationMessages" class="ai-history-conversation-messages"></div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@php
    $useRelativeFaqApi = (bool) config('faq_chat.relative_urls', true);
    $faqApiBase = '';
    if (! $useRelativeFaqApi) {
        $faqApiBase = rtrim((string) config('faq_chat.api_origin', ''), '/') ?: rtrim((string) config('app.url'), '/');
    }
    $faqApi = function (string $path) use ($faqApiBase): string {
        $p = str_starts_with($path, '/') ? $path : '/'.$path;

        return $faqApiBase !== '' ? $faqApiBase.$p : $p;
    };
@endphp

<script>
    const FAQ_API_BASE = @json($faqApiBase);
    const FAQ_FETCH_CREDENTIALS = FAQ_API_BASE ? 'include' : 'same-origin';

    const AI_CHAT_URL = @json(auth()->check() ? $faqApi('/ai/faq-chat') : '');
    const AI_FAQ_CONVERSATIONS_URL = @json(auth()->check() ? $faqApi('/ai/faq-chat/conversations') : '');
    const AI_FAQ_CONVERSATION_BASE = @json(auth()->check() ? $faqApi('/ai/faq-chat/conversations') : '');
    const AI_FAQ_ESCALATE_URL = @json(auth()->check() ? $faqApi('/ai/faq-chat/escalate') : '');
    const AI_FAQ_FEEDBACK_URL = @json(auth()->check() ? $faqApi('/ai/faq-chat/feedback') : '');
    const AI_IDLE_MS = @json(max(60, (int) config('faq_chat.idle_timeout_seconds', 900)) * 1000);

    let aiIdleTimer = null;
    let aiFeedbackStars = null;
    let aiFeedbackSentiment = null;
    let aiFeedbackSubmitting = false;

    function makeConversationUuid() {
        if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
            return crypto.randomUUID();
        }
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16 | 0,
                v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    let aiConversationUuid = makeConversationUuid();

    const aiDrawerInput = document.getElementById('aiChatInput');
    const aiDrawerMessages = document.getElementById('aiMessageList');
    const aiDrawerScroll = document.getElementById('aiChatScrollArea');
    const aiWelcomeTyping = document.getElementById('aiWelcomeTyping');
    const aiSendBtn = document.getElementById('aiSendBtn');

    const aiDefaultState = document.getElementById('aiDefaultState');
    const aiConversationState = document.getElementById('aiConversationState');

    const aiChatTab = document.getElementById('aiChatTab');
    const aiHistoryTab = document.getElementById('aiHistoryTab');
    const tabChatBtn = document.getElementById('tabChatBtn');
    const tabHistoryBtn = document.getElementById('tabHistoryBtn');

    const aiHistoryListView = document.getElementById('aiHistoryListView');
    const aiHistoryDetailView = document.getElementById('aiHistoryDetailView');
    const aiHistoryDetailTitle = document.getElementById('aiHistoryDetailTitle');
    const aiHistoryDetailMeta = document.getElementById('aiHistoryDetailMeta');
    const aiHistoryConversationMessages = document.getElementById('aiHistoryConversationMessages');
    const aiHistoryDeleteBtn = document.getElementById('aiHistoryDeleteBtn');

    let aiTypedStarted = false;
    let hasActiveConversation = false;
    let aiIsSending = false;
    let aiHistoryConversationUuid = null;

    function startAiWelcomeTyping() {
        if (aiTypedStarted || !aiWelcomeTyping) return;
        aiTypedStarted = true;

        const text = "Ask me about jobs, your account, employers, security, or how to get support—I’m here to help.";
        let i = 0;
        aiWelcomeTyping.innerHTML = "";

        const timer = setInterval(() => {
            aiWelcomeTyping.innerHTML += text.charAt(i);
            i++;
            if (i >= text.length) clearInterval(timer);
        }, 40);
    }

    setTimeout(() => {
        startAiWelcomeTyping();
    }, 300);

    function switchAiTab(tab) {
        if (tab === 'chat') {
            aiChatTab.classList.add('active');
            aiHistoryTab.classList.remove('active');
            tabChatBtn.classList.add('active');
            tabHistoryBtn.classList.remove('active');
            registerAiUserActivity();
        } else {
            aiHistoryTab.classList.add('active');
            aiChatTab.classList.remove('active');
            tabHistoryBtn.classList.add('active');
            tabChatBtn.classList.remove('active');
            stopAiIdleTimer();
            loadAiConversationList();
        }
    }

    function fillAiPrompt(text) {
        if (!aiDrawerInput) return;
        var max = parseInt(aiDrawerInput.getAttribute('maxlength'), 10) || 500;
        aiDrawerInput.value = String(text).slice(0, max);
        autoResizeAiDrawer();
        updateAiCharCount();
        aiDrawerInput.focus();
        registerAiUserActivity();
    }

    function ensureConversationMode() {
        var wasActive = hasActiveConversation;
        hasActiveConversation = true;
        aiDefaultState.classList.add('hidden');
        aiConversationState.classList.remove('hidden');
        if (!wasActive && AI_CHAT_URL && AI_FAQ_FEEDBACK_URL) {
            scheduleAiIdleTimer();
        }
    }

    function resetAiConversation() {
        stopAiIdleTimer();
        hideAiIdleFeedbackOverlay();
        hasActiveConversation = false;
        aiConversationUuid = makeConversationUuid();
        aiDrawerMessages.innerHTML = '';
        if (aiDrawerInput) {
            aiDrawerInput.value = '';
            autoResizeAiDrawer();
            updateAiCharCount();
        }
        aiConversationState.classList.add('hidden');
        aiDefaultState.classList.remove('hidden');
        switchAiTab('chat');
        scrollAiToTop();
    }

    function stopAiIdleTimer() {
        if (aiIdleTimer) {
            clearTimeout(aiIdleTimer);
            aiIdleTimer = null;
        }
    }

    function scheduleAiIdleTimer() {
        if (!AI_CHAT_URL || !AI_FAQ_FEEDBACK_URL) return;
        if (!hasActiveConversation) return;
        const overlay = document.getElementById('aiIdleFeedbackOverlay');
        if (overlay && !overlay.classList.contains('hidden')) return;

        stopAiIdleTimer();
        aiIdleTimer = setTimeout(onAiIdleTimeout, AI_IDLE_MS);
    }

    function registerAiUserActivity() {
        if (!document.getElementById('hs-overlay-right') || document.getElementById('hs-overlay-right').classList.contains('hidden')) {
            return;
        }
        scheduleAiIdleTimer();
    }

    function onAiIdleTimeout() {
        aiIdleTimer = null;
        if (!AI_CHAT_URL || !AI_FAQ_FEEDBACK_URL) return;
        if (!hasActiveConversation) return;
        const drawer = document.getElementById('hs-overlay-right');
        if (!drawer || drawer.classList.contains('hidden')) return;
        showAiIdleFeedbackOverlay();
    }

    function showAiIdleFeedbackOverlay() {
        stopAiIdleTimer();
        const el = document.getElementById('aiIdleFeedbackOverlay');
        if (!el) return;
        el.classList.remove('hidden');
        el.setAttribute('aria-hidden', 'false');
        aiFeedbackStars = null;
        aiFeedbackSentiment = null;
        aiFeedbackSubmitting = false;
        const c = document.getElementById('aiFeedbackComment');
        if (c) c.value = '';
        document.querySelectorAll('.ai-star-btn').forEach(function(b) {
            b.classList.remove('is-selected');
        });
        document.querySelectorAll('.ai-emoji-btn').forEach(function(b) {
            b.classList.remove('is-selected');
        });
        const sb = document.getElementById('aiFeedbackSubmitBtn');
        if (sb) sb.disabled = false;
    }

    function hideAiIdleFeedbackOverlay() {
        const el = document.getElementById('aiIdleFeedbackOverlay');
        if (!el) return;
        el.classList.add('hidden');
        el.setAttribute('aria-hidden', 'true');
    }

    function closeAiDrawerProgrammatically() {
        const b = document.getElementById('aiDrawerCloseBtn');
        if (b) b.click();
    }

    async function submitAiIdleFeedback(skipped) {
        if (aiFeedbackSubmitting) return;
        aiFeedbackSubmitting = true;
        const sb = document.getElementById('aiFeedbackSubmitBtn');
        if (sb) sb.disabled = true;

        if (AI_FAQ_FEEDBACK_URL && !skipped) {
            try {
                const commentEl = document.getElementById('aiFeedbackComment');
                await fetch(AI_FAQ_FEEDBACK_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: FAQ_FETCH_CREDENTIALS,
                    body: JSON.stringify({
                        conversation_uuid: aiConversationUuid,
                        context: 'idle_timeout',
                        skipped: false,
                        rating_stars: aiFeedbackStars,
                        sentiment: aiFeedbackSentiment,
                        comment: commentEl ? commentEl.value.trim() : '',
                    }),
                });
            } catch (e) {
                console.warn('Feedback submit failed', e);
            }
        } else if (AI_FAQ_FEEDBACK_URL && skipped) {
            try {
                await fetch(AI_FAQ_FEEDBACK_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: FAQ_FETCH_CREDENTIALS,
                    body: JSON.stringify({
                        conversation_uuid: aiConversationUuid,
                        context: 'idle_timeout',
                        skipped: true,
                    }),
                });
            } catch (e) {
                console.warn('Feedback skip failed', e);
            }
        }

        hideAiIdleFeedbackOverlay();
        resetAiConversation();
        closeAiDrawerProgrammatically();
        aiFeedbackSubmitting = false;
        if (sb) sb.disabled = false;
    }

    function updateAiCharCount() {
        const el = document.getElementById('aiCharCount');
        if (!el || !aiDrawerInput) return;
        el.textContent = String(aiDrawerInput.value.length);
    }

    function setAiSending(state) {
        aiIsSending = state;
        if (!aiSendBtn) return;
        aiSendBtn.disabled = state;
        aiSendBtn.style.opacity = state ? '0.65' : '1';
        aiSendBtn.style.pointerEvents = state ? 'none' : 'auto';
    }

    async function submitAiEscalation() {
        if (!AI_FAQ_ESCALATE_URL) {
            appendBotMessage('Please sign in to escalate to our team, or use the <a href="{{ url('/FAQ') }}" class="underline">help center</a> to reach support.');
            ensureConversationMode();
            scrollAiToBottom();
            return;
        }
        const desc = window.prompt('Briefly describe what you need help with:');
        if (desc === null) return;
        const d = desc.trim();
        if (!d) return;
        try {
            const res = await fetch(AI_FAQ_ESCALATE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: FAQ_FETCH_CREDENTIALS,
                body: JSON.stringify({
                    description: d,
                    conversation_uuid: aiConversationUuid,
                    agent: 'hill',
                }),
            });
            const raw = await res.text();
            let json = {};
            try {
                json = JSON.parse(raw);
            } catch (e) {}
            if (!res.ok) {
                const msg = (json.message || 'Could not submit escalation.').toString();
                window.alert(msg);
                return;
            }
            window.alert((json.message || 'Thanks — our team will follow up.').toString());
        } catch (e) {
            console.error(e);
            window.alert('Network error.');
        }
    }

    function appendUserMessage(text) {
        aiDrawerMessages.insertAdjacentHTML('beforeend', `
            <div class="ai-msg-row user">
                <div class="ai-msg-bubble user">${escapeHtml(text).replace(/\n/g, '<br>')}</div>
            </div>
        `);
        scrollAiToBottom();
    }

    function appendBotMessage(html) {
        aiDrawerMessages.insertAdjacentHTML('beforeend', `
            <div class="ai-msg-row bot">
                <div class="ai-msg-bubble bot">${html}</div>
            </div>
        `);
        scrollAiToBottom();
    }

    function appendTypingMessage() {
        const id = 'thinking-' + Date.now();

        aiDrawerMessages.insertAdjacentHTML('beforeend', `
            <div class="ai-msg-row bot" id="${id}">
                <div class="ai-msg-bubble bot">
                    <span class="ai-typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </div>
            </div>
        `);

        scrollAiToBottom();
        return id;
    }

    async function sendAiDrawerMessage() {
        if (aiIsSending) return;

        const text = aiDrawerInput.value.trim();
        if (!text) return;

        ensureConversationMode();
        registerAiUserActivity();
        appendUserMessage(text);
        aiDrawerInput.value = '';
        autoResizeAiDrawer();

        if (!AI_CHAT_URL) {
            appendBotMessage('Please sign in to chat with Ask Hill AI, or browse the <a href="{{ url('/FAQ') }}" class="underline">help center</a> for guides and answers.');
            scrollAiToBottom();
            return;
        }

        setAiSending(true);

        const typingId = appendTypingMessage();

        try {
            const response = await fetch(AI_CHAT_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: FAQ_FETCH_CREDENTIALS,
                body: JSON.stringify({
                    message: text,
                    conversation_uuid: aiConversationUuid,
                })
            });

            const raw = await response.text();
            const typingEl = document.getElementById(typingId);
            if (typingEl) typingEl.remove();

            if (!response.ok) {
                console.error('AI HTTP Error:', response.status, raw);
                appendBotMessage(`Server error (${response.status}). Please try again.`);
                return;
            }

            let json = {};
            try {
                json = JSON.parse(raw);
            } catch (e) {
                console.error('Invalid JSON response:', raw);
                appendBotMessage('The server returned an invalid response. Please check the backend.');
                return;
            }

            const reply = (json.reply || json.message || 'No reply returned from backend.').toString();
            if (json.conversation_uuid) {
                aiConversationUuid = json.conversation_uuid;
            }
            appendBotMessage(formatBotReply(reply));

        } catch (error) {
            console.error('AI Fetch Error:', error);
            const typingEl = document.getElementById(typingId);
            if (typingEl) typingEl.remove();
            appendBotMessage('Network error. Please check your connection or server logs.');
        } finally {
            setAiSending(false);
            scrollAiToBottom();
            registerAiUserActivity();
        }
    }

    function formatBotReply(text) {
        return escapeHtml(text)
            .replace(/\n/g, '<br>')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    }

    async function loadAiConversationList() {
        const listEl = document.getElementById('aiHistoryList');
        const emptyEl = document.getElementById('aiHistoryEmpty');
        if (!listEl || !AI_FAQ_CONVERSATIONS_URL) {
            if (emptyEl) emptyEl.textContent = 'Sign in to see your conversation history.';
            return;
        }
        if (emptyEl) emptyEl.textContent = 'Loading conversations…';
        try {
            const res = await fetch(AI_FAQ_CONVERSATIONS_URL, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: FAQ_FETCH_CREDENTIALS,
            });
            const raw = await res.text();
            let json = {};
            try {
                json = JSON.parse(raw);
            } catch (e) {}
            listEl.innerHTML = '';
            const rows = json.conversations || [];
            if (rows.length === 0) {
                listEl.innerHTML =
                    '<p class="text-sm text-textmuted dark:text-white/50 px-1 mb-0">No conversations yet — say hello in Chat to get started.</p>';
                return;
            }
            rows.forEach(function(row) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'ai-history-item';
                btn.onclick = function() {
                    openHistoryConversationUuid(row.uuid);
                };
                const t = row.updated_at ? new Date(row.updated_at).toLocaleString() : '';
                btn.innerHTML = '<div class="ai-history-top"><span class="ai-history-title">' +
                    escapeHtml(row.title || 'Conversation') + '</span><span class="ai-history-time">' +
                    escapeHtml(t) + '</span></div><p class="ai-history-preview">' +
                    escapeHtml(row.preview || '') + '</p>';
                listEl.appendChild(btn);
            });
        } catch (e) {
            listEl.innerHTML =
                '<p class="text-sm text-textmuted dark:text-white/50 px-1 mb-0">Could not load history.</p>';
        }
    }

    async function openHistoryConversationUuid(uuid) {
        if (!uuid || !AI_FAQ_CONVERSATION_BASE) return;
        aiHistoryConversationUuid = uuid;
        aiHistoryListView.classList.add('hidden');
        aiHistoryDetailView.classList.remove('hidden');
        aiHistoryDetailTitle.textContent = 'Loading…';
        aiHistoryDetailMeta.textContent = '';
        aiHistoryConversationMessages.innerHTML = '';
        if (aiHistoryDeleteBtn) {
            aiHistoryDeleteBtn.disabled = false;
            aiHistoryDeleteBtn.style.opacity = '1';
            aiHistoryDeleteBtn.style.pointerEvents = 'auto';
        }
        try {
            const res = await fetch(AI_FAQ_CONVERSATION_BASE + '/' + encodeURIComponent(uuid), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: FAQ_FETCH_CREDENTIALS,
            });
            const json = await res.json();
            if (!res.ok) throw new Error('bad');
            aiHistoryDetailTitle.textContent = json.title || 'Conversation';
            aiHistoryDetailMeta.textContent = (json.messages && json.messages.length) ?
                json.messages.length + ' messages' :
                '';
            (json.messages || []).forEach(function(msg) {
                if (msg.role === 'user') {
                    aiHistoryConversationMessages.insertAdjacentHTML('beforeend', `
                    <div class="ai-msg-row user">
                        <div class="ai-msg-bubble user">${escapeHtml(msg.content).replace(/\n/g, '<br>')}</div>
                    </div>
                `);
                } else {
                    aiHistoryConversationMessages.insertAdjacentHTML('beforeend', `
                    <div class="ai-msg-row bot">
                        <div class="ai-msg-bubble bot">${formatBotReply(msg.content)}</div>
                    </div>
                `);
                }
            });
        } catch (e) {
            aiHistoryDetailTitle.textContent = 'Error';
            aiHistoryDetailMeta.textContent = 'Could not load this conversation.';
        }
    }

    async function deleteHistoryConversation() {
        if (!aiHistoryConversationUuid || !AI_FAQ_CONVERSATION_BASE) return;

        const swal = window.Swal;
        if (!swal) {
            if (!window.confirm('Delete this conversation history? This cannot be undone.')) return;
        } else {
            const result = await swal.fire({
                title: 'Delete history?',
                text: 'This conversation will be permanently removed.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
            });

            if (!result.isConfirmed) {
                return;
            }
        }

        if (aiHistoryDeleteBtn) {
            aiHistoryDeleteBtn.disabled = true;
            aiHistoryDeleteBtn.style.opacity = '0.65';
            aiHistoryDeleteBtn.style.pointerEvents = 'none';
        }

        try {
            const res = await fetch(AI_FAQ_CONVERSATION_BASE + '/' + encodeURIComponent(aiHistoryConversationUuid), {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: FAQ_FETCH_CREDENTIALS,
            });

            const raw = await res.text();
            let json = {};
            try {
                json = JSON.parse(raw);
            } catch (e) {}

            if (!res.ok) {
                throw new Error((json.message || 'Could not delete conversation.').toString());
            }

            if (aiHistoryConversationUuid === aiConversationUuid) {
                resetAiConversation();
            }

            aiHistoryConversationUuid = null;
            closeHistoryConversation();
            await loadAiConversationList();

            if (swal) {
                await swal.fire({
                    title: 'Deleted',
                    text: 'Conversation history removed.',
                    icon: 'success',
                    timer: 1400,
                    showConfirmButton: false,
                });
            }
        } catch (e) {
            if (swal) {
                await swal.fire({
                    title: 'Delete failed',
                    text: e.message || 'Could not delete conversation.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                });
            } else {
                window.alert(e.message || 'Could not delete conversation.');
            }
        } finally {
            if (aiHistoryDeleteBtn) {
                aiHistoryDeleteBtn.disabled = false;
                aiHistoryDeleteBtn.style.opacity = '1';
                aiHistoryDeleteBtn.style.pointerEvents = 'auto';
            }
        }
    }

    function closeHistoryConversation() {
        aiHistoryConversationUuid = null;
        aiHistoryDetailView.classList.add('hidden');
        aiHistoryListView.classList.remove('hidden');
        aiHistoryConversationMessages.innerHTML = '';
    }

    function autoResizeAiDrawer() {
        if (!aiDrawerInput) return;
        aiDrawerInput.style.height = 'auto';
        aiDrawerInput.style.height = Math.min(aiDrawerInput.scrollHeight, 160) + 'px';
    }

    function scrollAiToBottom() {
        if (!aiDrawerScroll) return;
        setTimeout(() => {
            aiDrawerScroll.scrollTop = aiDrawerScroll.scrollHeight;
        }, 60);
    }

    function scrollAiToTop() {
        if (!aiDrawerScroll) return;
        aiDrawerScroll.scrollTop = 0;
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    if (aiDrawerInput) {
        aiDrawerInput.addEventListener('input', function() {
            autoResizeAiDrawer();
            updateAiCharCount();
            registerAiUserActivity();
        });
        aiDrawerInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendAiDrawerMessage();
            }
        });
        updateAiCharCount();
    }

    document.querySelectorAll('.ai-star-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            aiFeedbackStars = parseInt(btn.getAttribute('data-star'), 10) || null;
            document.querySelectorAll('.ai-star-btn').forEach(function(b) {
                b.classList.toggle('is-selected', parseInt(b.getAttribute('data-star'), 10) <= aiFeedbackStars);
            });
        });
    });

    document.querySelectorAll('.ai-emoji-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            aiFeedbackSentiment = btn.getAttribute('data-sentiment');
            document.querySelectorAll('.ai-emoji-btn').forEach(function(b) {
                b.classList.toggle('is-selected', b.getAttribute('data-sentiment') === aiFeedbackSentiment);
            });
        });
    });

    (function initAiDrawerIdleObserver() {
        var drawer = document.getElementById('hs-overlay-right');
        if (!drawer || typeof MutationObserver === 'undefined') return;
        var obs = new MutationObserver(function() {
            var open = !drawer.classList.contains('hidden');
            if (open) {
                if (hasActiveConversation && AI_CHAT_URL && AI_FAQ_FEEDBACK_URL) {
                    scheduleAiIdleTimer();
                }
            } else {
                stopAiIdleTimer();
                hideAiIdleFeedbackOverlay();
            }
        });
        obs.observe(drawer, {
            attributes: true,
            attributeFilter: ['class']
        });
    })();
</script>

<style>
    .ai-chat-drawer {
        --ai-primary: #6d4aff;
        --ai-primary-2: #8b5cf6;
        --ai-border: rgba(100, 116, 139, 0.14);
        --ai-text-soft: #6b7280;
        --ai-bg-soft: #f8fafc;
        --ai-bg-card: #ffffff;
        --ai-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
    }

    .dark .ai-chat-drawer {
        --ai-border: rgba(255, 255, 255, 0.08);
        --ai-text-soft: rgba(255, 255, 255, 0.58);
        --ai-bg-soft: #0f172a;
        --ai-bg-card: #111827;
        --ai-shadow: 0 18px 45px rgba(0, 0, 0, 0.35);
    }

    .ai-chat-header {
        border-bottom: 1px solid var(--ai-border);
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        min-height: 64px;
    }

    .dark .ai-chat-header {
        background: rgba(17, 24, 39, 0.9);
    }

    .ai-brand-avatar {
        position: relative;
        width: 40px;
        height: 40px;
        flex-shrink: 0;
    }

    .ai-brand-icon-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ai-brand-icon-inner {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #6d4aff, #8b5cf6);
        color: #fff;
        border: 2px solid rgba(109, 74, 255, 0.15);
        box-shadow: 0 10px 24px rgba(109, 74, 255, 0.22);
        font-size: 20px;
        font-weight: 700;
    }

    .ai-brand-icon-inner i {
        font-size: 20px;
        line-height: 1;
    }

    .ai-online-badge {
        position: absolute;
        right: 1px;
        bottom: 1px;
        width: 10px;
        height: 10px;
        border-radius: 999px;
        background: #22c55e;
        border: 2px solid #fff;
    }

    .dark .ai-online-badge {
        border-color: #111827;
    }

    .ai-chat-body-wrap {
        height: calc(100vh - 64px);
        overflow: hidden;
        background: #fff;
    }

    .dark .ai-chat-body-wrap {
        background: #0b1220;
    }

    .ai-chat-layout {
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .ai-tabs-bar {
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        border-bottom: 1px solid var(--ai-border);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }

    .dark .ai-tabs-bar {
        background: rgba(11, 18, 32, 0.92);
    }

    .ai-top-chip {
        border: 1px solid var(--ai-border);
        background: #f3f4f6;
        color: #4b5563;
        border-radius: 999px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        transition: .2s ease;
    }

    .ai-top-chip.active {
        background: #fff;
        color: #111827;
        box-shadow: inset 0 0 0 1px rgba(0, 0, 0, .04), 0 4px 14px rgba(0, 0, 0, .06);
    }

    .dark .ai-top-chip {
        background: rgba(255, 255, 255, .06);
        color: rgba(255, 255, 255, .72);
    }

    .dark .ai-top-chip.active {
        background: rgba(255, 255, 255, .12);
        color: #fff;
    }

    .ai-icon-btn {
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: 1px solid transparent;
        background: transparent;
        color: #111827;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: .2s ease;
    }

    .ai-icon-btn:hover {
        background: rgba(109, 74, 255, 0.08);
        color: var(--ai-primary);
    }

    .dark .ai-icon-btn {
        color: #fff;
    }

    .ai-tab-panel {
        display: none;
        position: relative;
        flex: 1 1 auto;
        min-height: 0;
        overflow: hidden;
    }

    .ai-tab-panel.active {
        display: block;
    }

    .ai-chat-scroll {
        height: 100%;
        overflow-y: auto;
        padding: 16px 16px 170px;
        background:
            radial-gradient(circle at top center, rgba(109, 74, 255, 0.04), transparent 28%),
            linear-gradient(180deg, #ffffff 0%, #fbfbfd 100%);
    }

    .dark .ai-chat-scroll {
        background:
            radial-gradient(circle at top center, rgba(109, 74, 255, 0.09), transparent 28%),
            linear-gradient(180deg, #0b1220 0%, #0f172a 100%);
    }

    .ai-welcome-block {
        text-align: center;
        padding: 56px 0 28px;
    }

    .ai-center-logo {
        display: flex;
        justify-content: center;
        margin-bottom: 18px;
    }

    .ai-logo-orb {
        width: 68px;
        height: 68px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: #fff;
        background: linear-gradient(135deg, var(--ai-primary), var(--ai-primary-2));
        box-shadow: 0 18px 40px rgba(109, 74, 255, 0.26);
        animation: aiPulseFloat 3s ease-in-out infinite;
    }

    .ai-welcome-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }

    .dark .ai-welcome-title {
        color: #fff;
    }

    .ai-welcome-subtitle {
        font-size: 1.05rem;
        color: var(--ai-text-soft);
        min-height: 32px;
    }

    .ai-suggestion-list {
        margin-top: 8px;
    }

    .ai-suggestion-item {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 4px;
        background: transparent;
        border: 0;
        border-bottom: 1px solid var(--ai-border);
        font-size: 16px;
        color: #111827;
        text-align: left;
        transition: .2s ease;
    }

    .ai-suggestion-item:hover {
        color: var(--ai-primary);
        transform: translateX(2px);
    }

    .dark .ai-suggestion-item {
        color: #fff;
    }

    .ai-suggestion-icon {
        width: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #111827;
        font-size: 18px;
    }

    .dark .ai-suggestion-icon {
        color: #fff;
    }

    .ai-category-pills {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding: 18px 0 10px;
        margin-bottom: 8px;
    }

    .ai-cat-pill {
        white-space: nowrap;
        padding: 8px 14px;
        border-radius: 999px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #4b5563;
        font-size: 14px;
        transition: .2s ease;
    }

    .ai-cat-pill.active {
        color: var(--ai-primary);
        border-color: #b8abff;
        background: #f6f3ff;
        box-shadow: 0 4px 14px rgba(109, 74, 255, 0.08);
    }

    .dark .ai-cat-pill {
        background: rgba(255, 255, 255, .04);
        border-color: rgba(255, 255, 255, .08);
        color: rgba(255, 255, 255, .75);
    }

    .dark .ai-cat-pill.active {
        background: rgba(109, 74, 255, 0.15);
        color: #c4b5fd;
        border-color: rgba(109, 74, 255, 0.35);
    }

    .ai-message-list,
    .ai-history-conversation-messages {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding-top: 10px;
    }

    .ai-msg-row {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }

    .ai-msg-row.user {
        justify-content: flex-end;
    }

    .ai-msg-row.bot {
        justify-content: flex-start;
    }

    .ai-msg-bubble {
        max-width: 82%;
        padding: 12px 14px;
        border-radius: 20px;
        font-size: 14px;
        line-height: 1.6;
        word-wrap: break-word;
    }

    .ai-msg-bubble.user {
        background: linear-gradient(135deg, var(--ai-primary), var(--ai-primary-2));
        color: #fff;
        border-bottom-right-radius: 6px;
        box-shadow: 0 12px 30px rgba(109, 74, 255, 0.20);
    }

    .ai-msg-bubble.bot {
        background: #f3f4f6;
        color: #111827;
        border-bottom-left-radius: 6px;
    }

    .dark .ai-msg-bubble.bot {
        background: rgba(255, 255, 255, .08);
        color: #fff;
    }

    .ai-disclaimer {
        text-align: center;
        font-size: 12px;
        color: var(--ai-text-soft);
        padding: 16px 0 6px;
    }

    .ai-bottom-bar {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 70px;
        padding: 14px 16px 18px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, .86) 20%, #fff 100%);
        border-top: 1px solid rgba(0, 0, 0, 0.03);
        z-index: 5;
    }

    .dark .ai-bottom-bar {
        background: linear-gradient(180deg, rgba(11, 18, 32, 0) 0%, rgba(11, 18, 32, .85) 20%, #0b1220 100%);
        border-top: 1px solid rgba(255, 255, 255, .05);
    }

    .ai-input-shell {
        border: 1px solid #d1d5db;
        border-radius: 24px;
        background: #fff;
        padding: 14px 14px 12px;
        box-shadow: var(--ai-shadow);
    }

    .dark .ai-input-shell {
        background: #111827;
        border-color: rgba(255, 255, 255, .08);
    }

    .ai-chat-input {
        width: 100%;
        min-height: 28px;
        max-height: 68px;
        resize: none;
        border: 0;
        outline: none;
        background: transparent;
        font-size: 16px;
        color: #111827;
        padding: 0 4px;
    }

    .dark .ai-chat-input {
        color: #fff;
    }

    .ai-chat-input::placeholder {
        color: #8b8b95;
    }

    .ai-input-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 10px;
    }

    .ai-round-btn {
        width: 44px;
        height: 44px;
        border-radius: 999px;
        border: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: .2s ease;
        font-size: 18px;
    }

    .ai-attach-btn {
        background: #f3f4f6;
        color: #111827;
    }

    .ai-send-btn {
        background: linear-gradient(135deg, #60a5fa, #a855f7);
        color: #fff;
        box-shadow: 0 10px 24px rgba(168, 85, 247, 0.28);
    }

    .ai-round-btn:hover {
        transform: translateY(-1px) scale(1.03);
    }

    .ai-history-wrap {
        height: 100%;
        overflow: hidden;
        background:
            radial-gradient(circle at top center, rgba(109, 74, 255, 0.04), transparent 28%),
            linear-gradient(180deg, #ffffff 0%, #fbfbfd 100%);
    }

    .dark .ai-history-wrap {
        background:
            radial-gradient(circle at top center, rgba(109, 74, 255, 0.09), transparent 28%),
            linear-gradient(180deg, #0b1220 0%, #0f172a 100%);
    }

    .ai-history-list-view,
    .ai-history-detail-view {
        height: 100%;
        overflow-y: auto;
        padding: 16px;
    }

    .ai-history-header,
    .ai-history-detail-header {
        margin-bottom: 18px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .ai-history-header h4,
    .ai-history-detail-header h4 {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }

    .ai-history-header p,
    .ai-history-detail-header p {
        font-size: 13px;
        color: var(--ai-text-soft);
    }

    .dark .ai-history-header h4,
    .dark .ai-history-detail-header h4 {
        color: #fff;
    }

    .ai-history-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .ai-history-item {
        width: 100%;
        text-align: left;
        border: 1px solid var(--ai-border);
        border-radius: 18px;
        padding: 14px 16px;
        background: rgba(255, 255, 255, .75);
        transition: .2s ease;
    }

    .ai-history-item:hover {
        transform: translateY(-1px);
        border-color: rgba(109, 74, 255, 0.25);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .dark .ai-history-item {
        background: rgba(255, 255, 255, .04);
    }

    .ai-history-top {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        margin-bottom: 6px;
    }

    .ai-history-title {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
    }

    .ai-history-time {
        font-size: 12px;
        color: var(--ai-text-soft);
    }

    .ai-history-preview {
        font-size: 13px;
        color: var(--ai-text-soft);
        margin: 0;
        line-height: 1.55;
    }

    .dark .ai-history-title {
        color: #fff;
    }

    .ai-back-btn {
        width: 36px;
        height: 36px;
        border-radius: 999px;
        border: 1px solid var(--ai-border);
        background: #fff;
        color: #111827;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .dark .ai-back-btn {
        background: rgba(255, 255, 255, .05);
        color: #fff;
    }

    .ai-history-delete-btn {
        width: 36px;
        height: 36px;
        border-radius: 999px;
        border: 1px solid rgba(239, 68, 68, 0.24);
        background: rgba(239, 68, 68, 0.08);
        color: #dc2626;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: .2s ease;
    }

    .ai-history-delete-btn:hover {
        background: rgba(239, 68, 68, 0.14);
        color: #b91c1c;
    }

    .dark .ai-history-delete-btn {
        border-color: rgba(248, 113, 113, 0.24);
        background: rgba(248, 113, 113, 0.08);
        color: #fca5a5;
    }

    .dark .ai-history-delete-btn:hover {
        background: rgba(248, 113, 113, 0.16);
        color: #fecaca;
    }

    .ai-typing-dots {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .ai-typing-dots span {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #9ca3af;
        animation: aiTypingBounce 1.2s infinite ease-in-out;
    }

    .ai-typing-dots span:nth-child(2) {
        animation-delay: .15s;
    }

    .ai-typing-dots span:nth-child(3) {
        animation-delay: .30s;
    }

    .ai-idle-feedback-overlay {
        position: absolute;
        inset: 0;
        z-index: 70;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: rgba(15, 23, 42, 0.48);
        backdrop-filter: blur(6px);
    }

    .dark .ai-idle-feedback-overlay {
        background: rgba(0, 0, 0, 0.55);
    }

    .ai-idle-feedback-card {
        width: 100%;
        max-width: 320px;
        border-radius: 16px;
        padding: 18px 16px 16px;
        background: var(--ai-bg-card);
        border: 1px solid var(--ai-border);
        box-shadow: var(--ai-shadow);
    }

    .ai-feedback-emoji-row {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .ai-emoji-btn {
        font-size: 1.75rem;
        line-height: 1;
        padding: 8px 12px;
        border-radius: 12px;
        border: 1px solid var(--ai-border);
        background: var(--ai-bg-soft);
        cursor: pointer;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .ai-emoji-btn:hover {
        transform: scale(1.06);
    }

    .ai-emoji-btn.is-selected {
        box-shadow: 0 0 0 2px var(--ai-primary);
        border-color: var(--ai-primary);
    }

    .ai-feedback-stars {
        display: flex;
        gap: 4px;
        justify-content: center;
    }

    .ai-star-btn {
        font-size: 1.35rem;
        line-height: 1;
        padding: 4px 6px;
        border: 0;
        background: transparent;
        color: #d1d5db;
        cursor: pointer;
        transition: color .15s ease, transform .15s ease;
    }

    .dark .ai-star-btn {
        color: rgba(255, 255, 255, .25);
    }

    .ai-star-btn.is-selected {
        color: #f59e0b;
        transform: scale(1.08);
    }

    .ai-feedback-comment {
        width: 100%;
        border-radius: 10px;
        border: 1px solid var(--ai-border);
        padding: 10px 12px;
        font-size: 12px;
        resize: none;
        background: var(--ai-bg-soft);
        color: #111827;
        margin-bottom: 12px;
    }

    .dark .ai-feedback-comment {
        color: #fff;
        background: rgba(255, 255, 255, .04);
    }

    .ai-feedback-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .ai-feedback-submit {
        flex: 1;
        min-width: 100px;
        border-radius: 10px;
        border: 0;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 600;
        background: linear-gradient(135deg, var(--ai-primary), var(--ai-primary-2));
        color: #fff;
        cursor: pointer;
    }

    .ai-feedback-skip {
        border-radius: 10px;
        border: 1px solid var(--ai-border);
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 600;
        background: transparent;
        color: var(--ai-text-soft);
        cursor: pointer;
    }

    .ai-input-meta {
        padding: 0 4px;
    }

    .hidden {
        display: none !important;
    }

    @keyframes aiPulseFloat {

        0%,
        100% {
            transform: translateY(0);
            box-shadow: 0 18px 40px rgba(109, 74, 255, 0.26);
        }

        50% {
            transform: translateY(-6px);
            box-shadow: 0 26px 50px rgba(109, 74, 255, 0.32);
        }
    }

    @keyframes aiTypingBounce {

        0%,
        80%,
        100% {
            transform: translateY(0);
            opacity: .45;
        }

        40% {
            transform: translateY(-4px);
            opacity: 1;
        }
    }
</style>
