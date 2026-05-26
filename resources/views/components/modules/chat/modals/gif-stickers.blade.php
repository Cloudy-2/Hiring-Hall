{{-- GIF & Stickers Modal --}}
<div data-chat-modal="gif-stickers" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">GIFs & Stickers</h2>
            <button type="button" data-chat-modal-close="gif-stickers" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="flex border-b border-gray-200 dark:border-white/10">
            <button type="button" data-gif-tab="gif" class="gif-tab active flex-1 py-3 text-sm font-semibold text-primary border-b-2 border-primary transition">
                <i class="bi bi-filetype-gif mr-1"></i> GIFs
            </button>
            <button type="button" data-gif-tab="stickers" class="gif-tab flex-1 py-3 text-sm font-semibold text-gray-500 dark:text-white/60 border-b-2 border-transparent hover:text-gray-700 dark:hover:text-white transition">
                <i class="bi bi-emoji-laughing mr-1"></i> Stickers
            </button>
        </div>

        {{-- Search --}}
        <div class="p-4 border-b border-gray-200 dark:border-white/10">
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-white/40"></i>
                <input type="text" id="gif-search-input" placeholder="Search GIFs..." 
                    class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 py-2.5 pl-10 pr-4 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-white/40 focus:border-primary focus:ring-2 focus:ring-primary/20 transition">
            </div>
        </div>

        {{-- Content --}}
        <div class="h-80 overflow-y-auto p-4">
            {{-- GIF Grid --}}
            <div id="gif-content" class="gif-content">
                {{-- Trending Categories --}}
                <div id="gif-categories" class="mb-4">
                    <p class="text-xs font-semibold text-gray-500 dark:text-white/50 uppercase mb-2">Trending</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" class="gif-category px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">👋 Wave</button>
                        <button type="button" class="gif-category px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">😂 Laugh</button>
                        <button type="button" class="gif-category px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">👍 Thumbs Up</button>
                        <button type="button" class="gif-category px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">❤️ Love</button>
                        <button type="button" class="gif-category px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">🎉 Celebrate</button>
                        <button type="button" class="gif-category px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">😢 Sad</button>
                        <button type="button" class="gif-category px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">🤔 Think</button>
                        <button type="button" class="gif-category px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-white/10 text-gray-700 dark:text-white/80 hover:bg-primary hover:text-white transition">👏 Clap</button>
                    </div>
                </div>

                {{-- GIF Results Grid --}}
                <div id="gif-results" class="grid grid-cols-2 gap-2">
                    {{-- Loading state --}}
                    <div id="gif-loading" class="col-span-2 flex flex-col items-center justify-center py-8 text-gray-400 dark:text-white/40">
                        <i class="bi bi-arrow-repeat animate-spin text-3xl mb-2"></i>
                        <p class="text-sm">Loading trending GIFs...</p>
                    </div>
                </div>
            </div>

            {{-- Stickers Grid --}}
            <div id="stickers-content" class="stickers-content hidden">
                <div id="stickers-results" class="grid grid-cols-3 gap-3">
                    {{-- Loading state --}}
                    <div id="stickers-loading" class="col-span-3 flex flex-col items-center justify-center py-8 text-gray-400 dark:text-white/40">
                        <i class="bi bi-arrow-repeat animate-spin text-3xl mb-2"></i>
                        <p class="text-sm">Loading stickers...</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer with Tenor attribution --}}
        <div class="border-t border-gray-200 dark:border-white/10 px-4 py-2 flex items-center justify-center">
            <span class="text-xs text-gray-400 dark:text-white/40">Powered by</span>
            <img src="https://www.gstatic.com/tenor/web/attribution/PB_tenor_logo_blue_horizontal.svg" alt="Tenor" class="h-4 ml-2 dark:invert dark:opacity-60">
        </div>
    </div>
</div>

<style>
    .gif-tab.active {
        color: #2b2bee;
        border-color: #2b2bee;
    }
    .dark .gif-tab.active {
        color: #6366f1;
        border-color: #6366f1;
    }
    .gif-item {
        cursor: pointer;
        border-radius: 0.5rem;
        overflow: hidden;
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .gif-item:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .gif-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        background: #f3f4f6;
    }
    .dark .gif-item img {
        background: rgba(255,255,255,0.05);
    }
    .sticker-item {
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 0.75rem;
        transition: transform 0.15s, background 0.15s;
    }
    .sticker-item:hover {
        transform: scale(1.1);
        background: rgba(0,0,0,0.05);
    }
    .dark .sticker-item:hover {
        background: rgba(255,255,255,0.1);
    }
    .sticker-item img {
        width: 100%;
        height: 80px;
        object-fit: contain;
    }
    /* Inline GIF in editors - show as block */
    .ql-editor img,
    .note-editable img {
        max-width: 120px !important;
        max-height: 80px !important;
        border-radius: 6px;
        display: block;
        margin: 4px 0;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tenor API Key (free tier)
    const TENOR_API_KEY = 'AIzaSyAyimkuYQYF_FXVALexPuGQctUWRURdCYQ';
    const TENOR_CLIENT_KEY = 'hillhire_chat';
    
    let currentTab = 'gif';
    let searchTimeout = null;

    const gifContent = document.getElementById('gif-content');
    const stickersContent = document.getElementById('stickers-content');
    const gifResults = document.getElementById('gif-results');
    const stickersResults = document.getElementById('stickers-results');
    const searchInput = document.getElementById('gif-search-input');
    const gifLoading = document.getElementById('gif-loading');
    const stickersLoading = document.getElementById('stickers-loading');
    const categoriesEl = document.getElementById('gif-categories');

    // Tab switching
    document.querySelectorAll('[data-gif-tab]').forEach(tab => {
        tab.addEventListener('click', () => {
            const tabName = tab.dataset.gifTab;
            currentTab = tabName;

            // Update tab styles
            document.querySelectorAll('.gif-tab').forEach(t => {
                t.classList.remove('active', 'text-primary', 'border-primary');
                t.classList.add('text-gray-500', 'dark:text-white/60', 'border-transparent');
            });
            tab.classList.add('active', 'text-primary', 'border-primary');
            tab.classList.remove('text-gray-500', 'dark:text-white/60', 'border-transparent');

            // Show/hide content
            if (tabName === 'gif') {
                gifContent.classList.remove('hidden');
                stickersContent.classList.add('hidden');
                searchInput.placeholder = 'Search GIFs...';
                loadTrendingGifs();
            } else {
                gifContent.classList.add('hidden');
                stickersContent.classList.remove('hidden');
                searchInput.placeholder = 'Search stickers...';
                loadTrendingStickers();
            }
        });
    });

    // Search input
    searchInput?.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();
        
        searchTimeout = setTimeout(() => {
            if (currentTab === 'gif') {
                if (query) {
                    categoriesEl?.classList.add('hidden');
                    searchGifs(query);
                } else {
                    categoriesEl?.classList.remove('hidden');
                    loadTrendingGifs();
                }
            } else {
                if (query) {
                    searchStickers(query);
                } else {
                    loadTrendingStickers();
                }
            }
        }, 300);
    });

    // Category click
    document.querySelectorAll('.gif-category').forEach(btn => {
        btn.addEventListener('click', () => {
            const text = btn.textContent.trim().split(' ').pop();
            searchInput.value = text;
            categoriesEl?.classList.add('hidden');
            searchGifs(text);
        });
    });

    // Load trending GIFs
    async function loadTrendingGifs() {
        showLoading('gif');
        try {
            const response = await fetch(`https://tenor.googleapis.com/v2/featured?key=${TENOR_API_KEY}&client_key=${TENOR_CLIENT_KEY}&limit=20&media_filter=gif,tinygif`);
            const data = await response.json();
            renderGifs(data.results || []);
        } catch (error) {
            console.error('Error loading GIFs:', error);
            showError('gif', 'Failed to load GIFs');
        }
    }

    // Search GIFs
    async function searchGifs(query) {
        showLoading('gif');
        try {
            const response = await fetch(`https://tenor.googleapis.com/v2/search?key=${TENOR_API_KEY}&client_key=${TENOR_CLIENT_KEY}&q=${encodeURIComponent(query)}&limit=20&media_filter=gif,tinygif`);
            const data = await response.json();
            renderGifs(data.results || []);
        } catch (error) {
            console.error('Error searching GIFs:', error);
            showError('gif', 'Failed to search GIFs');
        }
    }

    // Load trending stickers
    async function loadTrendingStickers() {
        showLoading('stickers');
        try {
            const response = await fetch(`https://tenor.googleapis.com/v2/featured?key=${TENOR_API_KEY}&client_key=${TENOR_CLIENT_KEY}&limit=30&searchfilter=sticker&media_filter=gif,tinygif`);
            const data = await response.json();
            renderStickers(data.results || []);
        } catch (error) {
            console.error('Error loading stickers:', error);
            showError('stickers', 'Failed to load stickers');
        }
    }

    // Search stickers
    async function searchStickers(query) {
        showLoading('stickers');
        try {
            const response = await fetch(`https://tenor.googleapis.com/v2/search?key=${TENOR_API_KEY}&client_key=${TENOR_CLIENT_KEY}&q=${encodeURIComponent(query)}&limit=30&searchfilter=sticker&media_filter=gif,tinygif`);
            const data = await response.json();
            renderStickers(data.results || []);
        } catch (error) {
            console.error('Error searching stickers:', error);
            showError('stickers', 'Failed to search stickers');
        }
    }

    // Render GIFs
    function renderGifs(gifs) {
        if (!gifResults) return;
        gifResults.innerHTML = '';

        if (gifs.length === 0) {
            gifResults.innerHTML = `
                <div class="col-span-2 flex flex-col items-center justify-center py-8 text-gray-400 dark:text-white/40">
                    <i class="bi bi-emoji-frown text-3xl mb-2"></i>
                    <p class="text-sm">No GIFs found</p>
                </div>
            `;
            return;
        }

        gifs.forEach(gif => {
            const url = gif.media_formats?.tinygif?.url || gif.media_formats?.gif?.url;
            const fullUrl = gif.media_formats?.gif?.url || url;
            if (!url) return;

            const div = document.createElement('div');
            div.className = 'gif-item';
            div.innerHTML = `<img src="${url}" alt="${gif.content_description || 'GIF'}" loading="lazy">`;
            div.addEventListener('click', () => selectGif(fullUrl));
            gifResults.appendChild(div);
        });
    }

    // Render stickers
    function renderStickers(stickers) {
        if (!stickersResults) return;
        stickersResults.innerHTML = '';

        if (stickers.length === 0) {
            stickersResults.innerHTML = `
                <div class="col-span-3 flex flex-col items-center justify-center py-8 text-gray-400 dark:text-white/40">
                    <i class="bi bi-emoji-frown text-3xl mb-2"></i>
                    <p class="text-sm">No stickers found</p>
                </div>
            `;
            return;
        }

        stickers.forEach(sticker => {
            const url = sticker.media_formats?.tinygif?.url || sticker.media_formats?.gif?.url;
            const fullUrl = sticker.media_formats?.gif?.url || url;
            if (!url) return;

            const div = document.createElement('div');
            div.className = 'sticker-item';
            div.innerHTML = `<img src="${url}" alt="${sticker.content_description || 'Sticker'}" loading="lazy">`;
            div.addEventListener('click', () => selectGif(fullUrl));
            stickersResults.appendChild(div);
        });
    }

    // Show loading
    function showLoading(type) {
        if (type === 'gif' && gifResults) {
            gifResults.innerHTML = `
                <div id="gif-loading" class="col-span-2 flex flex-col items-center justify-center py-8 text-gray-400 dark:text-white/40">
                    <i class="bi bi-arrow-repeat animate-spin text-3xl mb-2"></i>
                    <p class="text-sm">Loading...</p>
                </div>
            `;
        } else if (type === 'stickers' && stickersResults) {
            stickersResults.innerHTML = `
                <div id="stickers-loading" class="col-span-3 flex flex-col items-center justify-center py-8 text-gray-400 dark:text-white/40">
                    <i class="bi bi-arrow-repeat animate-spin text-3xl mb-2"></i>
                    <p class="text-sm">Loading...</p>
                </div>
            `;
        }
    }

    // Show error
    function showError(type, message) {
        const container = type === 'gif' ? gifResults : stickersResults;
        const cols = type === 'gif' ? 2 : 3;
        if (container) {
            container.innerHTML = `
                <div class="col-span-${cols} flex flex-col items-center justify-center py-8 text-red-400">
                    <i class="bi bi-exclamation-triangle text-3xl mb-2"></i>
                    <p class="text-sm">${message}</p>
                </div>
            `;
        }
    }

    // Select GIF/Sticker - insert inline or send directly
    function selectGif(url) {
        // Close modal
        const modal = document.querySelector('[data-chat-modal="gif-stickers"]');
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');

        const conversationId = document.getElementById('chat-v2-form')?.dataset.conversationId;
        if (!conversationId || !url) return;

        // Check current editor and get its content
        const currentEditor = window.getCurrentEditor ? window.getCurrentEditor() : 'normal';
        let hasText = false;

        if (currentEditor === 'quill' && window.quillInstance) {
            const text = window.quillInstance.getText().trim();
            hasText = text.length > 0 && text !== '\n';
        } else if (currentEditor === 'summernote' && typeof jQuery !== 'undefined' && jQuery('#summernote-editor').length) {
            const html = jQuery('#summernote-editor').summernote('code');
            const text = html.replace(/<[^>]*>/g, '').trim();
            hasText = text.length > 0 && text !== '<p><br></p>';
        } else {
            const messageInput = document.getElementById('chat-v2-message-input');
            hasText = messageInput && messageInput.value.trim().length > 0;
        }

        // If composer has text, insert GIF inline
        if (hasText) {
            insertGifInline(url, currentEditor);
            return;
        }

        // Composer is empty - send GIF directly
        sendGifDirectly(url, conversationId);
    }

    // Insert GIF inline into the composer
    function insertGifInline(url, editor) {
        if (editor === 'quill' && window.quillInstance) {
            // For Quill, insert image on a new line at the end
            const length = window.quillInstance.getLength();
            window.quillInstance.insertText(length - 1, '\n');
            window.quillInstance.insertEmbed(length, 'image', url);
            window.quillInstance.setSelection(length + 1);
        } else if (editor === 'summernote' && typeof jQuery !== 'undefined') {
            // For Summernote, insert on new line
            jQuery('#summernote-editor').summernote('editor.insertNode', document.createElement('br'));
            jQuery('#summernote-editor').summernote('editor.insertImage', url);
        } else {
            // For normal textarea, append the URL on new line
            const messageInput = document.getElementById('chat-v2-message-input');
            if (messageInput) {
                const currentText = messageInput.value.trim();
                messageInput.value = currentText + '\n' + url;
                messageInput.focus();
                messageInput.dispatchEvent(new Event('input'));
            }
        }

        if (typeof showChatToast === 'function') {
            showChatToast('GIF added to message', 'success');
        }
    }

    // Send GIF directly as a message
    function sendGifDirectly(url, conversationId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const topicId = window.currentTopicId || null;

        // Optimistic UI - show GIF immediately
        if (typeof window.renderOutgoingMessage === 'function') {
            window.renderOutgoingMessage(url, null, 'gif');
        }

        const payload = {
            body: url,
            type: 'gif'
        };
        if (topicId) payload.topic_id = topicId;

        fetch(`/chats/${conversationId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                console.log('[GIF] Sent successfully:', data.message.id);
            }
        })
        .catch(error => {
            console.error('Error sending GIF:', error);
            window.Swal?.fire({
                icon: 'error',
                title: 'Failed to send',
                text: 'Could not send the GIF. Please try again.',
            });
        });
    }

    // Load trending on modal open
    const gifModal = document.querySelector('[data-chat-modal="gif-stickers"]');
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                if (gifModal.classList.contains('flex')) {
                    // Modal opened
                    if (currentTab === 'gif') {
                        loadTrendingGifs();
                    } else {
                        loadTrendingStickers();
                    }
                }
            }
        });
    });

    if (gifModal) {
        observer.observe(gifModal, { attributes: true });
    }
});
</script>
