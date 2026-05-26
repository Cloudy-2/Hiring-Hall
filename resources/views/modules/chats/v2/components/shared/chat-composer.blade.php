{{-- Shared Chat Composer Component --}}
@php
    $isModerator = auth()->user()->isModerator();
    $currentTopicSlug = request()->query('topic');
    $currentTopicData = collect($topics ?? [])->firstWhere('slug', $currentTopicSlug);
    $isReadonlyChannel = ($currentTopicData['is_readonly'] ?? false) || ($selectedConversation?->settings['channel_admin_only'] ?? false);
    $isConversationLocked = (bool)($selectedConversation?->settings['locked'] ?? false);
    $isBotHistoryChannel = $currentTopicSlug === 'mod-bot-history';
    $canCompose = ($isModerator && !$isBotHistoryChannel) || (!$isModerator && !$isReadonlyChannel && !$isConversationLocked && !$isBotHistoryChannel);
@endphp

@if($isBotHistoryChannel)
{{-- Bot History channel - read-only for everyone --}}
<div class="chat-v2-composer relative z-10 border-t px-3 md:px-4 py-4 backdrop-blur transition-colors duration-200">
    <div class="flex items-center justify-center gap-2 text-gray-500 dark:text-white/50">
        <i class="bi bi-robot text-lg"></i>
        <span class="text-sm">This is a read-only bot history channel.</span>
    </div>
</div>
@elseif(!$canCompose)
{{-- Read-only notice for non-moderators --}}
<div class="chat-v2-composer relative z-10 border-t px-3 md:px-4 py-4 backdrop-blur transition-colors duration-200">
    <div class="flex items-center justify-center gap-2 text-gray-500 dark:text-white/50">
        <i class="bi bi-lock text-lg"></i>
        <span class="text-sm">{{ $isConversationLocked ? 'This chat is locked by a moderator.' : 'This channel is read-only. Only moderators can post here.' }}</span>
    </div>
</div>
@else
<div class="chat-v2-composer relative z-10 border-t px-3 md:px-4 pb-4 pt-3 backdrop-blur transition-colors duration-200">
    {{-- Editor Selector (Moderators Only) --}}
    @if($isModerator)
    <div class="flex items-center justify-end gap-2 mb-2">
        <span class="text-xs text-gray-400 dark:text-white/40">Compose Mode:</span>
        <div class="relative">
            <button type="button" id="editor-selector-btn" class="flex items-center gap-1.5 px-2 py-1 rounded-lg bg-gray-100 dark:bg-white/10 text-xs font-medium text-gray-600 dark:text-white/70 hover:bg-gray-200 dark:hover:bg-white/20 transition">
                <span id="current-editor-label">Normal</span>
                <i class="bi bi-chevron-down text-[10px]"></i>
            </button>
            <div id="editor-selector-menu" class="hidden absolute right-0 bottom-full mb-1 w-36 rounded-lg bg-white dark:bg-sidebar-dark border border-gray-200 dark:border-white/10 shadow-xl z-50 py-1">
                <button type="button" class="editor-option w-full flex items-center gap-2 px-3 py-2 text-xs text-gray-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10 transition" data-editor="normal">
                    <i class="bi bi-type text-gray-500"></i>
                    <span>Normal</span>
                    <i class="bi bi-check2 ml-auto text-primary editor-check"></i>
                </button>
                <button type="button" class="editor-option w-full flex items-center gap-2 px-3 py-2 text-xs text-gray-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10 transition" data-editor="quill">
                    <i class="bi bi-pencil-square text-blue-500"></i>
                    <span>React Quill</span>
                    <i class="bi bi-check2 ml-auto text-primary editor-check hidden"></i>
                </button>
                <button type="button" class="editor-option w-full flex items-center gap-2 px-3 py-2 text-xs text-gray-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10 transition" data-editor="summernote">
                    <i class="bi bi-journal-richtext text-purple-500"></i>
                    <span>Summernote</span>
                    <i class="bi bi-check2 ml-auto text-primary editor-check hidden"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Video Preview Area --}}
    <div id="video-preview-container" class="hidden mb-3 p-3 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10">
        <div class="flex items-start gap-3">
            <div class="relative">
                <video id="video-preview" class="w-32 h-20 rounded-lg object-cover bg-black" muted></video>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i class="bi bi-play-circle-fill text-white text-2xl opacity-80"></i>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p id="video-filename" class="text-sm font-medium text-gray-800 dark:text-white truncate"></p>
                <p id="video-filesize" class="text-xs text-gray-500 dark:text-white/50"></p>
                <div id="video-upload-progress" class="hidden mt-2">
                    <div class="w-full bg-gray-200 dark:bg-white/10 rounded-full h-1.5">
                        <div id="video-progress-bar" class="bg-primary h-1.5 rounded-full transition-all" style="width: 0%"></div>
                    </div>
                    <p id="video-progress-text" class="text-xs text-gray-500 dark:text-white/50 mt-1">Uploading...</p>
                </div>
            </div>
            <button type="button" id="remove-video-btn" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>

    {{-- Attachments Preview Area (for pasted/selected images and files) --}}
    <div id="attachments-preview-container" class="hidden mb-3 p-3 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium text-gray-600 dark:text-white/60">Attachments</span>
            <button type="button" id="clear-all-attachments-btn" class="text-xs text-red-500 hover:text-red-600 transition">Clear all</button>
        </div>
        <div id="attachments-preview-list" class="flex flex-wrap gap-2"></div>
    </div>

    {{-- Normal Editor --}}
    <form id="chat-v2-form" class="relative" data-conversation-id="{{ $selectedConversation?->id }}" data-editor="normal" enctype="multipart/form-data">
        @csrf
        <textarea id="chat-v2-message-input" name="body" rows="1"
            placeholder="Message {{ $selectedConversation?->name ?? '#general' }}"
            class="chat-v2-input w-full rounded-xl md:rounded-lg border-none py-3 pl-12 pr-32 md:pr-36 text-base placeholder-gray-400 focus:ring-2 focus:ring-primary transition-colors duration-200 resize-none overflow-hidden"
            autocomplete="off" {{ !$selectedConversation ? 'disabled' : '' }}></textarea>
        
        {{-- Hidden file inputs --}}
        <input type="file" id="video-file-input" name="video" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-matroska,.mkv" class="hidden" />
        <input type="file" id="image-file-input" accept="image/*" multiple class="hidden" />
        <input type="file" id="file-input" multiple class="hidden" />
        
        <button type="button" id="attachment-menu-btn" class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-primary p-1.5 text-white hover:bg-primary/90 transition">
            <i class="bi bi-plus-lg text-lg md:text-xl"></i>
        </button>

        {{-- Attachment Menu Dropdown --}}
        <div id="attachment-menu" class="hidden absolute left-3 bottom-full mb-2 w-48 rounded-xl bg-white dark:bg-sidebar-dark border border-gray-200 dark:border-white/10 shadow-xl z-50 overflow-hidden">
            @if($isModerator)
                <button type="button" id="upload-video-btn" class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-white/5 transition">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center">
                        <i class="bi bi-camera-video-fill text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white">Video</p>
                        <p class="text-xs text-gray-500 dark:text-white/50">Upload video</p>
                    </div>
                </button>
            @endif
            <button type="button" id="upload-image-btn" class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-white/5 transition">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                    <i class="bi bi-image-fill text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-white">Image</p>
                    <p class="text-xs text-gray-500 dark:text-white/50">Upload photo</p>
                </div>
            </button>
            <button type="button" id="upload-file-btn" class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-white/5 transition">
                <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-500/20 flex items-center justify-center">
                    <i class="bi bi-file-earmark-fill text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-white">File</p>
                    <p class="text-xs text-gray-500 dark:text-white/50">Upload document</p>
                </div>
            </button>
        </div>
        
        <div class="absolute right-2 md:right-3 top-1/2 flex -translate-y-1/2 gap-1 md:gap-2 text-gray-500 dark:text-white/60">
            <button type="button" data-open-chat-modal="gif-stickers" class="p-1 hover:text-gray-700 dark:hover:text-white" title="GIF & Stickers">
                <i class="bi bi-filetype-gif text-xl md:text-2xl"></i>
            </button>
            <button type="button" id="composer-emoji-btn" class="p-1 hover:text-gray-700 dark:hover:text-white" title="Emoji">
                <i class="bi bi-emoji-smile text-xl md:text-2xl"></i>
            </button>
            <button type="submit" class="p-1 hover:text-primary" title="Send">
                <i class="bi bi-send-fill text-xl md:text-2xl"></i>
            </button>
        </div>
    </form>

    @if($isModerator)
    {{-- Quill Editor (Hidden by default) --}}
    <div id="quill-editor-container" class="hidden">
        <div id="quill-editor" class="bg-white dark:bg-sidebar-dark rounded-xl border border-gray-200 dark:border-white/10"></div>
        {{-- Attachments Preview for Quill (below editor) --}}
        <div id="quill-attachments-preview" class="hidden mt-3 p-3 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-600 dark:text-white/60">Attachments</span>
                <button type="button" onclick="window.chatAttachments.clear()" class="text-xs text-red-500 hover:text-red-600 transition">Clear all</button>
            </div>
            <div id="quill-attachments-list" class="flex flex-wrap gap-2"></div>
        </div>
        {{-- Video Preview for Quill (below editor) --}}
        <div id="quill-video-preview-container" class="hidden mt-3 p-3 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10">
            <div class="flex items-start gap-3">
                <div class="relative">
                    <video id="quill-video-preview" class="w-32 h-20 rounded-lg object-cover bg-black" muted></video>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="bi bi-play-circle-fill text-white text-2xl opacity-80"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p id="quill-video-filename" class="text-sm font-medium text-gray-800 dark:text-white truncate"></p>
                    <p id="quill-video-filesize" class="text-xs text-gray-500 dark:text-white/50"></p>
                </div>
                <button type="button" id="quill-remove-video-btn" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
        <div class="flex items-center justify-between mt-2">
            <div class="flex items-center gap-2">
                <div class="relative">
                    <button type="button" id="quill-attachment-btn" class="p-2 rounded-lg bg-primary text-white hover:bg-primary/90 transition">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                    {{-- Quill Attachment Menu --}}
                    <div id="quill-attachment-menu" class="hidden absolute left-0 bottom-full mb-2 w-48 rounded-xl bg-white dark:bg-sidebar-dark border border-gray-200 dark:border-white/10 shadow-xl z-50 overflow-hidden">
                        <button type="button" id="quill-upload-video-btn" class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center">
                                <i class="bi bi-camera-video-fill text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">Video</p>
                                <p class="text-xs text-gray-500 dark:text-white/50">Upload video</p>
                            </div>
                        </button>
                        <button type="button" id="quill-upload-image-btn" class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                                <i class="bi bi-image-fill text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">Image</p>
                                <p class="text-xs text-gray-500 dark:text-white/50">Upload photo</p>
                            </div>
                        </button>
                        <button type="button" id="quill-upload-file-btn" class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-500/20 flex items-center justify-center">
                                <i class="bi bi-file-earmark-fill text-green-600 dark:text-green-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">File</p>
                                <p class="text-xs text-gray-500 dark:text-white/50">Upload document</p>
                            </div>
                        </button>
                    </div>
                </div>
                <input type="file" id="quill-video-input" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-matroska,.mkv" class="hidden" />
                <input type="file" id="quill-image-input" accept="image/*" multiple class="hidden" />
                <input type="file" id="quill-file-input" multiple class="hidden" />
                <button type="button" id="quill-gif-btn" class="p-2 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-gray-200 dark:hover:bg-white/20 transition" data-open-chat-modal="gif-stickers">
                    <i class="bi bi-filetype-gif"></i>
                </button>
                <button type="button" id="quill-emoji-btn" class="p-2 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-gray-200 dark:hover:bg-white/20 transition composer-emoji-trigger" title="Emoji">
                    <i class="bi bi-emoji-smile"></i>
                </button>
            </div>
            <button type="button" id="quill-send-btn" class="px-4 py-2 rounded-lg bg-primary text-white font-medium hover:bg-primary/90 transition">
                <i class="bi bi-send-fill mr-1"></i> Send
            </button>
        </div>
    </div>

    {{-- Summernote Editor (Hidden by default) --}}
    <div id="summernote-editor-container" class="hidden">
        <textarea id="summernote-editor" class="hidden"></textarea>
        {{-- Attachments Preview for Summernote (below editor) --}}
        <div id="summernote-attachments-preview" class="hidden mt-3 p-3 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-600 dark:text-white/60">Attachments</span>
                <button type="button" onclick="window.chatAttachments.clear()" class="text-xs text-red-500 hover:text-red-600 transition">Clear all</button>
            </div>
            <div id="summernote-attachments-list" class="flex flex-wrap gap-2"></div>
        </div>
        {{-- Video Preview for Summernote (below editor) --}}
        <div id="summernote-video-preview-container" class="hidden mt-3 p-3 rounded-xl bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10">
            <div class="flex items-start gap-3">
                <div class="relative">
                    <video id="summernote-video-preview" class="w-32 h-20 rounded-lg object-cover bg-black" muted></video>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="bi bi-play-circle-fill text-white text-2xl opacity-80"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p id="summernote-video-filename" class="text-sm font-medium text-gray-800 dark:text-white truncate"></p>
                    <p id="summernote-video-filesize" class="text-xs text-gray-500 dark:text-white/50"></p>
                </div>
                <button type="button" id="summernote-remove-video-btn" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
        <div class="flex items-center justify-between mt-2">
            <div class="flex items-center gap-2">
                <div class="relative">
                    <button type="button" id="summernote-attachment-btn" class="p-2 rounded-lg bg-primary text-white hover:bg-primary/90 transition">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                    {{-- Summernote Attachment Menu --}}
                    <div id="summernote-attachment-menu" class="hidden absolute left-0 bottom-full mb-2 w-48 rounded-xl bg-white dark:bg-sidebar-dark border border-gray-200 dark:border-white/10 shadow-xl z-50 overflow-hidden">
                        <button type="button" id="summernote-upload-video-btn" class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-500/20 flex items-center justify-center">
                                <i class="bi bi-camera-video-fill text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">Video</p>
                                <p class="text-xs text-gray-500 dark:text-white/50">Upload video</p>
                            </div>
                        </button>
                        <button type="button" id="summernote-upload-image-btn" class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center">
                                <i class="bi bi-image-fill text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">Image</p>
                                <p class="text-xs text-gray-500 dark:text-white/50">Upload photo</p>
                            </div>
                        </button>
                        <button type="button" id="summernote-upload-file-btn" class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-500/20 flex items-center justify-center">
                                <i class="bi bi-file-earmark-fill text-green-600 dark:text-green-400"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white">File</p>
                                <p class="text-xs text-gray-500 dark:text-white/50">Upload document</p>
                            </div>
                        </button>
                    </div>
                </div>
                <input type="file" id="summernote-video-input" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-matroska,.mkv" class="hidden" />
                <input type="file" id="summernote-image-input" accept="image/*" multiple class="hidden" />
                <input type="file" id="summernote-file-input" multiple class="hidden" />
                <button type="button" class="p-2 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-gray-200 dark:hover:bg-white/20 transition" data-open-chat-modal="gif-stickers">
                    <i class="bi bi-filetype-gif"></i>
                </button>
                <button type="button" id="summernote-emoji-btn" class="p-2 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-white/70 hover:bg-gray-200 dark:hover:bg-white/20 transition composer-emoji-trigger" title="Emoji">
                    <i class="bi bi-emoji-smile"></i>
                </button>
            </div>
            <button type="button" id="summernote-send-btn" class="px-4 py-2 rounded-lg bg-primary text-white font-medium hover:bg-primary/90 transition">
                <i class="bi bi-send-fill mr-1"></i> Send
            </button>
        </div>
    </div>
    @endif
</div>

<script>
// Attachment state - defined globally before DOMContentLoaded
window.chatAttachments = {
    files: [],
    video: null,
    add: function(file) {
        // Check if it's a video
        if (file.type.startsWith('video/')) {
            const maxSize = 3 * 1024 * 1024 * 1024; // 3GB for videos
            if (file.size > maxSize) {
                if (typeof showChatToast === 'function') showChatToast('Video must be less than 3GB', 'error');
                return false;
            }
            this.video = file;
            this.render();
            return true;
        }
        
        // Regular file/image
        const maxSize = 10 * 1024 * 1024; // 10MB
        if (file.size > maxSize) {
            if (typeof showChatToast === 'function') showChatToast('File must be less than 10MB', 'error');
            return false;
        }
        if (this.files.length >= 10) {
            if (typeof showChatToast === 'function') showChatToast('Maximum 10 attachments allowed', 'error');
            return false;
        }
        this.files.push(file);
        this.render();
        return true;
    },
    remove: function(index) {
        this.files.splice(index, 1);
        this.render();
    },
    removeVideo: function() {
        this.video = null;
        this.render();
    },
    clear: function() {
        this.files = [];
        this.video = null;
        this.render();
    },
    render: function() {
        // Get current editor mode
        const currentEditor = window.getCurrentEditor ? window.getCurrentEditor() : 'normal';
        
        // Select the appropriate container based on editor
        let container, list;
        if (currentEditor === 'quill') {
            container = document.getElementById('quill-attachments-preview');
            list = document.getElementById('quill-attachments-list');
        } else if (currentEditor === 'summernote') {
            container = document.getElementById('summernote-attachments-preview');
            list = document.getElementById('summernote-attachments-list');
        } else {
            container = document.getElementById('attachments-preview-container');
            list = document.getElementById('attachments-preview-list');
        }
        
        // Also hide other containers and old video preview
        const allContainers = [
            document.getElementById('attachments-preview-container'),
            document.getElementById('quill-attachments-preview'),
            document.getElementById('summernote-attachments-preview')
        ];
        
        // Hide old separate video preview containers
        document.getElementById('video-preview-container')?.classList.add('hidden');
        document.getElementById('quill-video-preview-container')?.classList.add('hidden');
        document.getElementById('summernote-video-preview-container')?.classList.add('hidden');
        
        allContainers.forEach(c => {
            if (c && c !== container) {
                c.classList.add('hidden');
                const l = c.querySelector('[id$="-list"], #attachments-preview-list');
                if (l) l.innerHTML = '';
            }
        });

        if (!container || !list) return;

        const hasContent = this.files.length > 0 || this.video;
        if (!hasContent) {
            container.classList.add('hidden');
            list.innerHTML = '';
            return;
        }

        container.classList.remove('hidden');
        
        let html = '';
        
        // Render video first if present
        if (this.video) {
            const videoUrl = URL.createObjectURL(this.video);
            const escapedName = this.video.name.replace(/</g, '&lt;').replace(/>/g, '&gt;');
            html += `
                <div class="relative group">
                    <div class="w-28 h-20 rounded-lg overflow-hidden bg-black border border-purple-500/30">
                        <video src="${videoUrl}" class="w-full h-full object-cover" muted></video>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                            <i class="bi bi-play-circle-fill text-white text-xl"></i>
                        </div>
                    </div>
                    <button type="button" onclick="window.chatAttachments.removeVideo()" class="absolute -top-2 -right-2 w-5 h-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                        <i class="bi bi-x"></i>
                    </button>
                    <p class="text-[10px] text-purple-400 truncate max-w-[112px] mt-1">${escapedName}</p>
                </div>
            `;
        }
        
        // Render files/images
        html += this.files.map((file, i) => {
            const isImage = file.type.startsWith('image/');
            const preview = isImage ? URL.createObjectURL(file) : null;
            const size = this.formatSize(file.size);
            
            if (isImage) {
                return `
                    <div class="relative group">
                        <img src="${preview}" class="w-20 h-20 rounded-lg object-cover border border-gray-200 dark:border-white/10">
                        <button type="button" onclick="window.chatAttachments.remove(${i})" class="absolute -top-2 -right-2 w-5 h-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `;
            } else {
                const escapedName = file.name.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                return `
                    <div class="relative group flex items-center gap-2 px-3 py-2 rounded-lg bg-white dark:bg-white/10 border border-gray-200 dark:border-white/10">
                        <i class="bi bi-file-earmark text-lg text-gray-500"></i>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-gray-800 dark:text-white truncate max-w-[120px]">${escapedName}</p>
                            <p class="text-[10px] text-gray-500 dark:text-white/50">${size}</p>
                        </div>
                        <button type="button" onclick="window.chatAttachments.remove(${i})" class="ml-1 text-gray-400 hover:text-red-500 transition">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `;
            }
        }).join('');
        
        list.innerHTML = html;
    },
    formatSize: function(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }
};

// Global accessors for messaging script
window.getSelectedAttachments = () => window.chatAttachments.files;
window.getSelectedVideoFile = () => window.chatAttachments.video;
window.clearSelectedAttachments = () => { window.chatAttachments.files = []; window.chatAttachments.render(); };
window.clearSelectedVideo = () => { window.chatAttachments.video = null; window.chatAttachments.render(); };

document.addEventListener('DOMContentLoaded', () => {
    const attachmentBtn = document.getElementById('attachment-menu-btn');
    const attachmentMenu = document.getElementById('attachment-menu');
    const videoInput = document.getElementById('video-file-input');
    const imageInput = document.getElementById('image-file-input');
    const fileInput = document.getElementById('file-input');
    const uploadVideoBtn = document.getElementById('upload-video-btn');
    const uploadImageBtn = document.getElementById('upload-image-btn');
    const uploadFileBtn = document.getElementById('upload-file-btn');
    const previewContainer = document.getElementById('video-preview-container');
    const videoPreview = document.getElementById('video-preview');
    const videoFilename = document.getElementById('video-filename');
    const videoFilesize = document.getElementById('video-filesize');
    const removeVideoBtn = document.getElementById('remove-video-btn');
    const messageInput = document.getElementById('chat-v2-message-input');
    const clearAllAttachmentsBtn = document.getElementById('clear-all-attachments-btn');

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Local sanitizeHtml for rich text content
    function sanitizeHtmlLocal(html) {
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
    
    function formatFileSize(bytes) {
        return window.chatAttachments.formatSize(bytes);
    }

    // Auto-resize textarea
    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 150) + 'px';
    }
    
    if (messageInput) {
        messageInput.addEventListener('input', () => autoResize(messageInput));
        window.resetComposerHeight = () => {
            messageInput.style.height = 'auto';
        };
    }

    @if($isModerator)
    const botCommands = [
        { cmd: '/help', desc: 'Show all commands' },
        { cmd: '/status', desc: 'Quick view of active settings' },
        { cmd: '/clear', desc: 'Delete last N messages' },
        { cmd: '/clear-all', desc: 'Delete all messages in channel' },
        { cmd: '/mute', desc: 'Mute a user' },
        { cmd: '/unmute', desc: 'Unmute a user' },
        { cmd: '/kick', desc: 'Remove user from group' },
        { cmd: '/warn', desc: 'Warn a user' },
        { cmd: '/stats', desc: 'Show channel statistics' },
        { cmd: '/listrules', desc: 'Show all rules in detail' },
        { cmd: '/lock', desc: 'Make channel read-only' },
        { cmd: '/unlock', desc: 'Allow everyone to post' },
        { cmd: '/slowmode', desc: 'Set slowmode (seconds)' },
        { cmd: '/enable', desc: 'Enable moderation bot' },
        { cmd: '/disable', desc: 'Disable moderation bot' },
        { cmd: '/addrule', desc: 'Block messages with text' },
        { cmd: '/removerule', desc: 'Remove a rule' },
        { cmd: '/setaction', desc: 'Set violation action' },
        { cmd: '/profanity', desc: 'Toggle profanity filter' },
        { cmd: '/spam', desc: 'Toggle spam detection' },
        { cmd: '/links', desc: 'Toggle link blocking' },
        { cmd: '/caps', desc: 'Block excessive caps' },
        { cmd: '/forcecaps', desc: 'Force messages to UPPERCASE' },
    ];

    let commandDropdown = null;
    let selectedCommandIndex = 0;

    function createCommandDropdown() {
        if (commandDropdown) return commandDropdown;
        commandDropdown = document.createElement('div');
        commandDropdown.id = 'command-autocomplete';
        commandDropdown.className = 'absolute left-0 right-0 bottom-full mb-2 max-h-64 overflow-y-auto rounded-xl bg-white dark:bg-sidebar-dark border border-gray-200 dark:border-white/10 shadow-xl z-50 hidden';
        messageInput.parentElement.appendChild(commandDropdown);
        return commandDropdown;
    }

    function showCommandSuggestions(filter = '') {
        const dropdown = createCommandDropdown();
        const filtered = filter 
            ? botCommands.filter(c => c.cmd.toLowerCase().startsWith(filter.toLowerCase()))
            : botCommands;
        
        if (filtered.length === 0) {
            dropdown.classList.add('hidden');
            return;
        }

        selectedCommandIndex = 0;
        dropdown.innerHTML = filtered.map((c, i) => `
            <button type="button" class="command-item w-full flex items-center gap-3 px-4 py-2.5 text-left hover:bg-slate-100 dark:hover:bg-white/10 transition ${i === 0 ? 'bg-slate-100 dark:bg-white/10' : ''}" data-cmd="${c.cmd}" data-index="${i}">
                <span class="text-sm font-mono font-medium text-primary">${c.cmd}</span>
                <span class="text-xs text-gray-500 dark:text-white/50">${c.desc}</span>
            </button>
        `).join('');
        
        dropdown.classList.remove('hidden');
        
        dropdown.querySelectorAll('.command-item').forEach(item => {
            item.addEventListener('click', () => {
                insertCommand(item.dataset.cmd);
            });
        });
    }

    function hideCommandSuggestions() {
        if (commandDropdown) commandDropdown.classList.add('hidden');
    }

    function insertCommand(cmd) {
        const cursorPos = messageInput.selectionStart;
        const text = messageInput.value;
        const beforeSlash = text.lastIndexOf('/', cursorPos);
        
        if (beforeSlash !== -1) {
            messageInput.value = text.substring(0, beforeSlash) + cmd + ' ';
        } else {
            messageInput.value = cmd + ' ';
        }
        
        messageInput.focus();
        hideCommandSuggestions();
    }

    function updateSelectedCommand(direction) {
        const items = commandDropdown?.querySelectorAll('.command-item');
        if (!items || items.length === 0) return;
        
        items[selectedCommandIndex]?.classList.remove('bg-gray-100', 'dark:bg-white/10');
        
        if (direction === 'up') {
            selectedCommandIndex = selectedCommandIndex > 0 ? selectedCommandIndex - 1 : items.length - 1;
        } else {
            selectedCommandIndex = selectedCommandIndex < items.length - 1 ? selectedCommandIndex + 1 : 0;
        }
        
        items[selectedCommandIndex]?.classList.add('bg-gray-100', 'dark:bg-white/10');
        items[selectedCommandIndex]?.scrollIntoView({ block: 'nearest' });
    }

    messageInput?.addEventListener('input', (e) => {
        const text = e.target.value;
        const cursorPos = e.target.selectionStart;
        
        if (text.startsWith('/')) {
            const cmdPart = text.split(' ')[0];
            showCommandSuggestions(cmdPart);
        } else {
            hideCommandSuggestions();
        }
    });

    messageInput?.addEventListener('keydown', (e) => {
        if (!commandDropdown || commandDropdown.classList.contains('hidden')) return;
        
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            updateSelectedCommand('up');
        } else if (e.key === 'ArrowDown') {
            e.preventDefault();
            updateSelectedCommand('down');
        } else if (e.key === 'Tab' || e.key === 'Enter') {
            const items = commandDropdown.querySelectorAll('.command-item');
            if (items.length > 0 && !commandDropdown.classList.contains('hidden')) {
                e.preventDefault();
                insertCommand(items[selectedCommandIndex].dataset.cmd);
            }
        } else if (e.key === 'Escape') {
            hideCommandSuggestions();
        }
    });

    messageInput?.addEventListener('blur', () => {
        setTimeout(hideCommandSuggestions, 150);
    });
    @endif

    // Paste handler for images and files
    messageInput?.addEventListener('paste', (e) => {
        const items = e.clipboardData?.items;
        if (!items) return;

        for (const item of items) {
            if (item.kind === 'file') {
                e.preventDefault();
                const file = item.getAsFile();
                if (file) window.chatAttachments.add(file);
            }
        }
    });

    clearAllAttachmentsBtn?.addEventListener('click', () => window.chatAttachments.clear());

    // Image file input handler
    uploadImageBtn?.addEventListener('click', () => {
        imageInput?.click();
        attachmentMenu?.classList.add('hidden');
    });

    imageInput?.addEventListener('change', (e) => {
        const files = e.target.files;
        if (!files) return;
        for (const file of files) {
            window.chatAttachments.add(file);
        }
        imageInput.value = '';
    });

    // File input handler
    uploadFileBtn?.addEventListener('click', () => {
        fileInput?.click();
        attachmentMenu?.classList.add('hidden');
    });

    fileInput?.addEventListener('change', (e) => {
        const files = e.target.files;
        if (!files) return;
        for (const file of files) {
            window.chatAttachments.add(file);
        }
        fileInput.value = '';
    });

    attachmentBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        attachmentMenu?.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!e.target) return;
        if (!attachmentMenu?.contains(e.target) && e.target !== attachmentBtn) {
            attachmentMenu?.classList.add('hidden');
        }
    });

    uploadVideoBtn?.addEventListener('click', () => {
        videoInput?.click();
        attachmentMenu?.classList.add('hidden');
    });

    videoInput?.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        window.chatAttachments.add(file);
        videoInput.value = '';
    });

    removeVideoBtn?.addEventListener('click', () => {
        window.chatAttachments.removeVideo();
    });

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    const editorSelectorBtn = document.getElementById('editor-selector-btn');
    const editorSelectorMenu = document.getElementById('editor-selector-menu');
    const currentEditorLabel = document.getElementById('current-editor-label');
    const normalForm = document.getElementById('chat-v2-form');
    const quillContainer = document.getElementById('quill-editor-container');
    const summernoteContainer = document.getElementById('summernote-editor-container');
    
    let currentEditor = localStorage.getItem('chat-editor') || 'normal';
    let quillInstance = null;
    let summernoteInitialized = false;

    if (editorSelectorBtn) {
        editorSelectorBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            editorSelectorMenu?.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!e.target) return;
            if (!editorSelectorMenu?.contains(e.target) && e.target !== editorSelectorBtn) {
                editorSelectorMenu?.classList.add('hidden');
            }
        });

        document.querySelectorAll('.editor-option').forEach(btn => {
            btn.addEventListener('click', () => {
                const editor = btn.dataset.editor;
                switchEditor(editor);
                editorSelectorMenu?.classList.add('hidden');
            });
        });

        switchEditor(currentEditor, true);
    }

    function switchEditor(editor, isInit = false) {
        currentEditor = editor;
        localStorage.setItem('chat-editor', editor);

        const labels = { normal: 'Normal', quill: 'React Quill', summernote: 'Summernote' };
        if (currentEditorLabel) currentEditorLabel.textContent = labels[editor] || 'Normal';

        document.querySelectorAll('.editor-option').forEach(btn => {
            const check = btn.querySelector('.editor-check');
            if (btn.dataset.editor === editor) {
                check?.classList.remove('hidden');
            } else {
                check?.classList.add('hidden');
            }
        });

        if (normalForm) normalForm.classList.toggle('hidden', editor !== 'normal');
        if (quillContainer) quillContainer.classList.toggle('hidden', editor !== 'quill');
        if (summernoteContainer) summernoteContainer.classList.toggle('hidden', editor !== 'summernote');

        if (editor === 'quill' && !quillInstance) {
            initQuill();
        }
        if (editor === 'summernote' && !summernoteInitialized) {
            initSummernote();
        }
    }

    function initQuill() {
        if (typeof Quill === 'undefined') {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdn.quilljs.com/1.3.7/quill.snow.css';
            document.head.appendChild(link);

            const script = document.createElement('script');
            script.src = 'https://cdn.quilljs.com/1.3.7/quill.min.js';
            script.onload = () => createQuillInstance();
            document.head.appendChild(script);
        } else {
            createQuillInstance();
        }
    }

    function createQuillInstance() {
        const quillEl = document.getElementById('quill-editor');
        if (!quillEl || quillInstance) return;

        quillInstance = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Type your message...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'code-block'],
                    ['clean']
                ]
            }
        });

        // Expose quill instance globally for GIF modal
        window.quillInstance = quillInstance;

        document.getElementById('quill-send-btn')?.addEventListener('click', () => {
            const html = quillInstance.root.innerHTML;
            if (html && html !== '<p><br></p>') {
                sendRichMessage(html);
                quillInstance.setText('');
            }
        });
    }

    function initSummernote() {
        if (typeof jQuery === 'undefined') {
            const jqueryScript = document.createElement('script');
            jqueryScript.src = 'https://code.jquery.com/jquery-3.7.1.min.js';
            jqueryScript.onload = () => loadSummernoteAssets();
            document.head.appendChild(jqueryScript);
        } else if (typeof $.fn.summernote === 'undefined') {
            loadSummernoteAssets();
        } else {
            createSummernoteInstance();
        }
    }

    function loadSummernoteAssets() {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css';
        document.head.appendChild(link);

        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js';
        script.onload = () => createSummernoteInstance();
        document.head.appendChild(script);
    }

    function createSummernoteInstance() {
        const summernoteEl = jQuery('#summernote-editor');
        if (!summernoteEl.length || summernoteInitialized) return;

        const isDark = document.documentElement.classList.contains('dark');

        summernoteEl.summernote({
            placeholder: 'Type your message...',
            height: 120,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'strikethrough']],
                ['para', ['ul', 'ol']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });

        if (isDark) {
            jQuery('.note-editor').addClass('dark-mode');
        }

        summernoteInitialized = true;

        document.getElementById('summernote-send-btn')?.addEventListener('click', () => {
            const html = summernoteEl.summernote('code');
            if (html && html !== '<p><br></p>' && html.trim()) {
                sendRichMessage(html);
                summernoteEl.summernote('code', '');
            }
        });
    }

    async function sendRichMessage(html) {
        const conversationId = normalForm?.dataset.conversationId;
        if (!conversationId) return;

        // Get current topic ID
        const topicId = window.currentTopicId || null;

        // Get attachments
        const attachments = window.getSelectedAttachments ? window.getSelectedAttachments().slice() : [];
        const videoFile = window.getSelectedVideoFile ? window.getSelectedVideoFile() : null;

        // Create optimistic attachment previews before clearing
        let optimisticAttachmentHtml = '';
        if (videoFile) {
            const videoPreviewUrl = URL.createObjectURL(videoFile);
            optimisticAttachmentHtml = `
                <div class="max-w-lg mt-2">
                    <div class="relative rounded-xl overflow-hidden bg-black">
                        <video src="${videoPreviewUrl}" class="w-full max-h-80 object-contain" muted></video>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                            <div class="animate-pulse text-white text-center">
                                <i class="bi bi-cloud-upload text-3xl"></i>
                                <p class="text-xs mt-1">Uploading...</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-2 text-xs text-gray-500 dark:text-white/50">
                        <i class="bi bi-camera-video-fill text-purple-500"></i>
                        <span class="truncate">${escapeHtml(videoFile.name)}</span>
                    </div>
                </div>
            `;
        } else if (attachments.length > 0) {
            optimisticAttachmentHtml = '<div class="flex flex-col gap-2 mt-1">' + attachments.map(file => {
                if (file.type.startsWith('image/')) {
                    const previewUrl = URL.createObjectURL(file);
                    return `<div class="relative img-skeleton rounded-lg">
                        <img src="${previewUrl}" alt="${escapeHtml(file.name)}" class="max-w-xs rounded-lg opacity-70">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="animate-pulse text-white bg-black/50 rounded-full p-2">
                                <i class="bi bi-cloud-upload"></i>
                            </div>
                        </div>
                    </div>`;
                }
                return `<div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 dark:bg-white/10 opacity-70">
                    <i class="bi bi-file-earmark text-lg"></i>
                    <span class="text-sm truncate">${escapeHtml(file.name)}</span>
                    <i class="bi bi-cloud-upload animate-pulse ml-auto"></i>
                </div>`;
            }).join('') + '</div>';
        }

        // Render optimistic message
        let tempEl = null;
        if (typeof renderOutgoingMessage === 'function') {
            tempEl = renderOutgoingMessage(html, null, 'rich');
            
            // Append optimistic attachment preview to the message
            if (tempEl && optimisticAttachmentHtml) {
                const contentWrapper = tempEl.querySelector('.msg-content-wrapper');
                if (contentWrapper) {
                    contentWrapper.insertAdjacentHTML('beforeend', optimisticAttachmentHtml);
                }
            }
        }

        // Clear attachments immediately
        window.chatAttachments?.clear();

        try {
            let response;
            
            // If we have attachments or video, use FormData
            if (attachments.length > 0 || videoFile) {
                const formData = new FormData();
                formData.append('body', html);
                formData.append('type', 'text');
                formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
                
                // Add topic ID if present
                if (topicId) {
                    formData.append('topic_id', topicId);
                }
                
                // Add video if present
                if (videoFile) {
                    formData.append('video', videoFile);
                }
                
                // Add attachments
                attachments.forEach((file, index) => {
                    formData.append(`attachments[${index}]`, file);
                });

                response = await fetch(`/chats/${conversationId}/messages`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });
            } else {
                // No attachments, use JSON
                const payload = { body: html, type: 'text' };
                if (topicId) payload.topic_id = topicId;
                
                response = await fetch(`/chats/${conversationId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify(payload),
                });
            }

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                showChatToast('Failed to send message', 'error');
                if (tempEl && typeof updateMessageStatus === 'function') {
                    updateMessageStatus(tempEl, 'failed');
                }
            } else {
                // Update message with server response including attachments
                if (tempEl && data.message) {
                    const msg = data.message;
                    tempEl.dataset.messageId = msg.id;
                    const dropdown = tempEl.querySelector('.msg-more-dropdown');
                    if (dropdown) dropdown.dataset.messageId = msg.id;
                    
                    // Handle video attachment
                    if (videoFile && msg.attachments?.length > 0) {
                        const attachment = msg.attachments[0];
                        const videoUrl = `/chats/attachments/${attachment.id}/stream`;
                        const downloadUrl = `/chats/attachments/${attachment.id}/download`;
                        const userIsModerator = @json(auth()->user()->isModerator());
                        const userRoleComposer = @json(auth()->user()->role);
                        const badgeLabelComposer = userRoleComposer === 'super_admin' ? 'Super Admin' : (userRoleComposer === 'admin' ? 'Admin' : 'Moderator');
                        const modBadge = userIsModerator ? `<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-500/20 text-blue-400"><i class="bi bi-patch-check-fill mr-0.5"></i>${badgeLabelComposer}</span>` : '';
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
                            // Use local sanitizeHtml for rich text
                            const sanitizedBody = sanitizeHtmlLocal(html);
                            contentEl.innerHTML = `
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <p class="text-sm font-medium own-message-name" style="color: #2563eb !important;">${escapeHtml(msg.user?.name || 'You')}</p>
                                    ${modBadge}
                                    <p class="text-xs md:text-sm text-gray-500 dark:text-[#9d9db9]">${new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })}</p>
                                </div>
                                ${html ? `<div class="text-sm md:text-base text-gray-700 dark:text-white/90 break-words chat-message-body rich-text-content">${sanitizedBody}</div>` : ''}
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
                        // Handle image/file attachments
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
                            const userRoleComposer2 = @json(auth()->user()->role);
                            const badgeLabelComposer2 = userRoleComposer2 === 'super_admin' ? 'SUPER' : (userRoleComposer2 === 'admin' ? 'ADMIN' : 'MOD');
                            const modBadge = userIsModerator ? `<span class="inline-flex items-center text-[10px] font-medium text-blue-500"><i class="bi bi-patch-check-fill mr-0.5"></i>${badgeLabelComposer2}</span>` : '';
                            // Use local sanitizeHtml for rich text
                            const sanitizedBody = sanitizeHtmlLocal(html);
                            contentEl.innerHTML = `
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <p class="text-sm font-medium own-message-name" style="color: #2563eb !important;">${escapeHtml(msg.user?.name || 'You')}</p>
                                    ${modBadge}
                                    <p class="text-xs md:text-sm text-gray-500 dark:text-[#9d9db9]">${new Date().toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })}</p>
                                </div>
                                ${html ? `<div class="text-sm md:text-base text-gray-700 dark:text-white/90 break-words chat-message-body rich-text-content">${sanitizedBody}</div>` : ''}
                                <div class="flex flex-col gap-2 mt-1">${attHtml}</div>
                            `;
                        }
                    }
                    
                    if (typeof updateMessageStatus === 'function') {
                        updateMessageStatus(tempEl, 'sent');
                    }
                }
            }
        } catch (error) {
            console.error('Send error:', error);
            showChatToast('Failed to send message', 'error');
            if (tempEl && typeof updateMessageStatus === 'function') {
                updateMessageStatus(tempEl, 'failed');
            }
        }
    }

    // Quill attachment handlers
    const quillAttachmentBtn = document.getElementById('quill-attachment-btn');
    const quillAttachmentMenu = document.getElementById('quill-attachment-menu');
    const quillImageInput = document.getElementById('quill-image-input');
    const quillFileInput = document.getElementById('quill-file-input');
    const quillVideoInput = document.getElementById('quill-video-input');
    const quillUploadImageBtn = document.getElementById('quill-upload-image-btn');
    const quillUploadFileBtn = document.getElementById('quill-upload-file-btn');
    const quillUploadVideoBtn = document.getElementById('quill-upload-video-btn');

    quillAttachmentBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        quillAttachmentMenu?.classList.toggle('hidden');
    });

    quillUploadImageBtn?.addEventListener('click', () => {
        quillImageInput?.click();
        quillAttachmentMenu?.classList.add('hidden');
    });

    quillUploadFileBtn?.addEventListener('click', () => {
        quillFileInput?.click();
        quillAttachmentMenu?.classList.add('hidden');
    });

    quillUploadVideoBtn?.addEventListener('click', () => {
        quillVideoInput?.click();
        quillAttachmentMenu?.classList.add('hidden');
    });

    quillImageInput?.addEventListener('change', (e) => {
        const files = e.target.files;
        if (!files) return;
        for (const file of files) {
            window.chatAttachments.add(file);
        }
        quillImageInput.value = '';
    });

    quillFileInput?.addEventListener('change', (e) => {
        const files = e.target.files;
        if (!files) return;
        for (const file of files) {
            window.chatAttachments.add(file);
        }
        quillFileInput.value = '';
    });

    quillVideoInput?.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        window.chatAttachments.add(file);
        quillVideoInput.value = '';
    });

    // Summernote attachment handlers
    const summernoteAttachmentBtn = document.getElementById('summernote-attachment-btn');
    const summernoteAttachmentMenu = document.getElementById('summernote-attachment-menu');
    const summernoteImageInput = document.getElementById('summernote-image-input');
    const summernoteFileInput = document.getElementById('summernote-file-input');
    const summernoteVideoInput = document.getElementById('summernote-video-input');
    const summernoteUploadImageBtn = document.getElementById('summernote-upload-image-btn');
    const summernoteUploadFileBtn = document.getElementById('summernote-upload-file-btn');
    const summernoteUploadVideoBtn = document.getElementById('summernote-upload-video-btn');

    summernoteAttachmentBtn?.addEventListener('click', (e) => {
        e.stopPropagation();
        summernoteAttachmentMenu?.classList.toggle('hidden');
    });

    summernoteUploadImageBtn?.addEventListener('click', () => {
        summernoteImageInput?.click();
        summernoteAttachmentMenu?.classList.add('hidden');
    });

    summernoteUploadFileBtn?.addEventListener('click', () => {
        summernoteFileInput?.click();
        summernoteAttachmentMenu?.classList.add('hidden');
    });

    summernoteUploadVideoBtn?.addEventListener('click', () => {
        summernoteVideoInput?.click();
        summernoteAttachmentMenu?.classList.add('hidden');
    });

    summernoteImageInput?.addEventListener('change', (e) => {
        const files = e.target.files;
        if (!files) return;
        for (const file of files) {
            window.chatAttachments.add(file);
        }
        summernoteImageInput.value = '';
    });

    summernoteFileInput?.addEventListener('change', (e) => {
        const files = e.target.files;
        if (!files) return;
        for (const file of files) {
            window.chatAttachments.add(file);
        }
        summernoteFileInput.value = '';
    });

    summernoteVideoInput?.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        window.chatAttachments.add(file);
        summernoteVideoInput.value = '';
    });

    // Close attachment menus on outside click
    document.addEventListener('click', (e) => {
        if (!e.target) return;
        if (quillAttachmentMenu && !quillAttachmentMenu.contains(e.target) && e.target !== quillAttachmentBtn && !quillAttachmentBtn?.contains(e.target)) {
            quillAttachmentMenu.classList.add('hidden');
        }
        if (summernoteAttachmentMenu && !summernoteAttachmentMenu.contains(e.target) && e.target !== summernoteAttachmentBtn && !summernoteAttachmentBtn?.contains(e.target)) {
            summernoteAttachmentMenu.classList.add('hidden');
        }
    });

    window.getCurrentEditor = () => currentEditor;
});
</script>

{{-- Summernote dark mode styles --}}
<style>
.note-editor.dark-mode {
    background: #2d2d30;
    border-color: rgba(255,255,255,0.1);
}
.note-editor.dark-mode .note-toolbar {
    background: #2d2d30;
    border-color: rgba(255,255,255,0.1);
}
.note-editor.dark-mode .note-editing-area .note-editable {
    background: #2d2d30;
    color: rgba(255,255,255,0.9);
}
.note-editor.dark-mode .note-btn {
    background: transparent;
    color: rgba(255,255,255,0.7);
    border-color: rgba(255,255,255,0.1);
}
.note-editor.dark-mode .note-btn:hover {
    background: rgba(255,255,255,0.1);
}
#quill-editor {
    min-height: 100px;
}
.dark #quill-editor {
    background: #2d2d30;
    color: rgba(255,255,255,0.9);
}
.dark .ql-toolbar {
    background: #2d2d30;
    border-color: rgba(255,255,255,0.1) !important;
}
.dark .ql-container {
    border-color: rgba(255,255,255,0.1) !important;
}
.dark .ql-stroke {
    stroke: rgba(255,255,255,0.7) !important;
}
.dark .ql-fill {
    fill: rgba(255,255,255,0.7) !important;
}
.dark .ql-picker-label {
    color: rgba(255,255,255,0.7) !important;
}
</style>
@endif

{{-- Composer Emoji Picker --}}
<div id="composer-emoji-picker-popup" class="hidden" style="position:fixed;z-index:9999;bottom:80px;right:24px;">
</div>

<style>
#composer-emoji-picker-popup em-emoji-picker {
    border-radius: 16px !important;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important;
}
html.dark #composer-emoji-picker-popup em-emoji-picker {
    --background-rgb: 45,45,48 !important;
    --border-color: rgba(255,255,255,0.1) !important;
    --rgb-background: 45,45,48 !important;
    --rgb-input: 56,56,56 !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5) !important;
}
html:not(.dark) #composer-emoji-picker-popup em-emoji-picker {
    border: 1px solid rgba(0,0,0,0.1) !important;
}
</style>

<script type="module">
import { Picker } from 'https://cdn.jsdelivr.net/npm/emoji-mart@5.6.0/+esm';

document.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('composer-emoji-picker-popup');
    if (!popup) return;

    let picker = null;
    let currentTheme = null;

    function getTheme() {
        return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    }

    function insertEmojiAtCursor(emoji) {
        const currentEditor = window.getCurrentEditor ? window.getCurrentEditor() : 'normal';

        if (currentEditor === 'normal') {
            const input = document.getElementById('chat-v2-message-input');
            if (!input) return;
            const start = input.selectionStart;
            const end = input.selectionEnd;
            const text = input.value;
            input.value = text.substring(0, start) + emoji + text.substring(end);
            const newPos = start + emoji.length;
            input.selectionStart = newPos;
            input.selectionEnd = newPos;
            input.focus();
            input.dispatchEvent(new Event('input', { bubbles: true }));
        } else if (currentEditor === 'quill' && window.quillInstance) {
            const range = window.quillInstance.getSelection(true);
            window.quillInstance.insertText(range ? range.index : window.quillInstance.getLength() - 1, emoji);
        } else if (currentEditor === 'summernote') {
            const el = document.getElementById('summernote-editor');
            if (el && typeof $ !== 'undefined') {
                $(el).summernote('editor.pasteHTML', emoji);
            }
        }
    }

    function createPicker() {
        popup.innerHTML = '';
        currentTheme = getTheme();
        picker = new Picker({
            onEmojiSelect: (emoji) => {
                insertEmojiAtCursor(emoji.native);
            },
            theme: currentTheme,
            set: 'native',
            previewPosition: 'none',
            skinTonePosition: 'none',
            perLine: 8,
            maxFrequentRows: 2,
        });
        popup.appendChild(picker);
    }

    function togglePicker() {
        if (popup.classList.contains('hidden')) {
            const newTheme = getTheme();
            if (!picker || currentTheme !== newTheme) {
                createPicker();
            }
            popup.classList.remove('hidden');
        } else {
            popup.classList.add('hidden');
        }
    }

    // Wire up all emoji buttons
    document.getElementById('composer-emoji-btn')?.addEventListener('click', (e) => {
        e.stopPropagation();
        togglePicker();
    });
    document.getElementById('quill-emoji-btn')?.addEventListener('click', (e) => {
        e.stopPropagation();
        togglePicker();
    });
    document.getElementById('summernote-emoji-btn')?.addEventListener('click', (e) => {
        e.stopPropagation();
        togglePicker();
    });

    // Close picker on outside click
    document.addEventListener('click', (e) => {
        if (!popup.contains(e.target) &&
            e.target.id !== 'composer-emoji-btn' &&
            e.target.id !== 'quill-emoji-btn' &&
            e.target.id !== 'summernote-emoji-btn' &&
            !e.target.closest('#composer-emoji-btn') &&
            !e.target.closest('#quill-emoji-btn') &&
            !e.target.closest('#summernote-emoji-btn')) {
            popup.classList.add('hidden');
        }
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !popup.classList.contains('hidden')) {
            popup.classList.add('hidden');
        }
    });
});
</script>
