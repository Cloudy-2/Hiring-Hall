{{-- Right Sidebar - Channel Details & Members --}}
@php
    $isModerator = auth()->user()->isModerator();
    $currentTopicData = collect($topics ?? [])->firstWhere('slug', request()->query('topic'));
    $isStarredChannel = $currentTopicData['is_starred'] ?? false;
    $isReadonly = $currentTopicData['is_readonly'] ?? false;
    $settings = $selectedConversation?->settings ?? [];
@endphp

@if($isModerator)
{{-- Collapse Toggle Button (shown when collapsed) --}}
<button type="button" id="right-panel-expand-btn" onclick="toggleRightPanel()" 
    class="right-panel-toggle-btn hidden flex-shrink-0 w-8 border-l border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark hover:bg-gray-50 dark:hover:bg-white/10 transition-colors items-center justify-center cursor-pointer"
    title="Expand panel">
    <i class="bi bi-chevron-left text-gray-400 dark:text-white/40"></i>
</button>

<div id="right-panel-content" class="chat-v2-right hidden md:flex w-64 flex-shrink-0 flex-col transition-all duration-200 overflow-y-auto border-l border-gray-200 dark:border-white/10 bg-white dark:bg-sidebar-dark">
    
    {{-- Channel Header with Collapse Button --}}
    <div id="tour-channel-header" class="p-4 border-b border-gray-200 dark:border-white/10">
        <div class="flex items-center gap-2 mb-2">
            {{-- Collapse Button --}}
            <button type="button" onclick="toggleRightPanel()" 
                class="p-1 -ml-1 rounded hover:bg-slate-100 dark:hover:bg-white/10 transition-colors"
                title="Collapse panel">
                <i class="bi bi-chevron-right text-sm text-gray-400 dark:text-white/40"></i>
            </button>
            <i class="bi bi-hash text-lg text-gray-500 dark:text-white/70"></i>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white truncate flex-1">
                {{ request()->query('topic') ? request()->query('topic') : 'general' }}
            </h2>
            {{-- Help Tour Button --}}
            <button type="button" onclick="startRightSidebarTour()" 
                class="p-1 rounded hover:bg-slate-100 dark:hover:bg-white/10 transition-colors text-gray-400 dark:text-white/40 hover:text-primary"
                title="Take a Tour">
                <i class="bi bi-question-circle text-sm"></i>
            </button>
        </div>
        @if($isStarredChannel || $isReadonly)
            <div class="flex items-center gap-2 text-xs">
                @if($isStarredChannel)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400">
                        <i class="bi bi-star-fill text-[10px]"></i> Starred
                    </span>
                @endif
            </div>
        @endif
    </div>

    {{-- Tabs (Info / Moderator Area) --}}
    <div id="tour-right-tabs" class="flex border-b border-gray-200 dark:border-white/10">
        <button type="button" onclick="switchRightTab('info')" id="tab-info" class="flex-1 py-2 text-xs font-medium text-center border-b-2 border-primary text-primary">
            Info
        </button>
        <button type="button" onclick="switchRightTab('settings')" id="tab-settings" class="flex-1 py-2 text-xs font-medium text-center border-b-2 border-transparent text-gray-500 dark:text-white/50 hover:text-gray-700 dark:hover:text-white/70">
            <i class="bi bi-shield-lock mr-1"></i>Moderator Area
        </button>
    </div>

    {{-- Info Tab Content --}}
    <div id="tab-content-info" class="flex-1 flex flex-col overflow-y-auto">
        {{-- About Section --}}
        <div id="tour-about-section" class="p-4 border-b border-gray-200 dark:border-white/10">
            <button type="button" class="w-full flex items-center justify-between text-left group" onclick="toggleRightSection('about')">
                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50">About</span>
                <i class="bi bi-chevron-down text-[10px] text-gray-400 dark:text-white/40 transition-transform" id="about-chevron"></i>
            </button>
            <div id="about-content" class="mt-3 space-y-3">
                @if($currentTopicData['description'] ?? null)
                    <p class="text-sm text-gray-600 dark:text-white/70">{{ $currentTopicData['description'] }}</p>
                @else
                    <p class="text-sm text-gray-500 dark:text-white/50 italic">No description</p>
                @endif
                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-white/50">
                    <i class="bi bi-calendar3"></i>
                    <span>Created {{ $selectedConversation->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Bot Section --}}
        @if($settings['bot_enabled'] ?? false)
        <div id="tour-bot-section" class="p-4 border-b border-gray-200 dark:border-white/10">
            <button type="button" class="w-full flex items-center justify-between text-left group" onclick="toggleRightSection('bot')">
                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50">
                    <i class="bi bi-cpu mr-1"></i> Bot
                </span>
                <i class="bi bi-chevron-down text-[10px] text-gray-400 dark:text-white/40 transition-transform" id="bot-chevron"></i>
            </button>
            <div id="bot-content" class="mt-3">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <img src="https://api.dicebear.com/7.x/bottts/svg?seed={{ $settings['bot_name'] ?? 'HillBot' }}&backgroundColor=6366f1" 
                             alt="{{ $settings['bot_name'] ?? 'HillBot' }}" class="size-9 rounded-lg">
                        <span class="absolute -bottom-0.5 -right-0.5 size-2.5 rounded-full border-2 border-white dark:border-sidebar-dark bg-green-500"></span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $settings['bot_name'] ?? 'HillBot' }}</p>
                        <p class="text-xs text-gray-500 dark:text-white/50">{{ ucfirst($settings['bot_role'] ?? 'Moderator') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Shared Files Section --}}
        @if($settings['show_shared_files'] ?? false)
        <div class="p-4 border-b border-gray-200 dark:border-white/10">
            <button type="button" class="w-full flex items-center justify-between text-left group" onclick="toggleRightSection('files'); loadSharedFiles();">
                <span class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50">
                    <i class="bi bi-folder2 mr-1"></i> Shared Files
                </span>
                <i class="bi bi-chevron-down text-[10px] text-gray-400 dark:text-white/40 transition-transform" id="files-chevron"></i>
            </button>
            <div id="files-content" class="mt-3 hidden">
                <div id="files-loading" class="flex items-center justify-center py-4">
                    <i class="bi bi-arrow-repeat animate-spin text-gray-400"></i>
                </div>
                <div id="files-list" class="space-y-2 max-h-64 overflow-y-auto"></div>
                <div id="files-empty" class="hidden text-center py-4">
                    <i class="bi bi-folder2-open text-2xl text-gray-400 dark:text-white/30"></i>
                    <p class="text-xs text-gray-500 dark:text-white/50 mt-1">No files shared yet</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Settings Tab Content (Moderator Only) --}}
    <div id="tab-content-settings" class="hidden flex-1 flex flex-col overflow-y-auto">
        <div class="p-4 space-y-4">
            {{-- Channel Permissions --}}
            <div class="space-y-3">
                <p class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50">Channel Permissions</p>

                <label class="flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-lock text-sm text-gray-500 dark:text-white/50"></i>
                        <span class="text-sm text-gray-700 dark:text-white/80">Read Only</span>
                    </div>
                    <input type="checkbox" class="channel-setting-toggle" data-setting="channel_admin_only" data-label="Read Only" {{ ($settings['channel_admin_only'] ?? false) ? 'checked' : '' }}>
                </label>

                <label class="flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-hourglass-split text-sm text-gray-500 dark:text-white/50"></i>
                        <span class="text-sm text-gray-700 dark:text-white/80">Slow Mode</span>
                        <span class="text-[9px] px-1 py-0.5 rounded bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400">Soon</span>
                    </div>
                    <input type="checkbox" class="channel-setting-toggle" data-setting="channel_slow_mode" data-label="Slow Mode" data-tbi="true" {{ ($settings['channel_slow_mode'] ?? false) ? 'checked' : '' }}>
                </label>
            </div>

            {{-- Content Moderation --}}
            <div class="space-y-3 pt-3 border-t border-gray-200 dark:border-white/10">
                <p class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50">Content Moderation</p>
                
                <label class="flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-link-45deg text-sm text-gray-500 dark:text-white/50"></i>
                        <span class="text-sm text-gray-700 dark:text-white/80">Block Links</span>
                        <span class="text-[9px] px-1 py-0.5 rounded bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400">Soon</span>
                    </div>
                    <input type="checkbox" class="channel-setting-toggle" data-setting="rules.links" data-label="Block Links" data-tbi="true" {{ ($settings['rules']['links'] ?? false) ? 'checked' : '' }}>
                </label>

                <label class="flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-chat-text text-sm text-gray-500 dark:text-white/50"></i>
                        <span class="text-sm text-gray-700 dark:text-white/80">Profanity Filter</span>
                        <span class="text-[9px] px-1 py-0.5 rounded bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400">Soon</span>
                    </div>
                    <input type="checkbox" class="channel-setting-toggle" data-setting="rules.profanity" data-label="Profanity Filter" data-tbi="true" {{ ($settings['rules']['profanity'] ?? false) ? 'checked' : '' }}>
                </label>

                <label class="flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-shield-exclamation text-sm text-gray-500 dark:text-white/50"></i>
                        <span class="text-sm text-gray-700 dark:text-white/80">Anti-Spam</span>
                        <span class="text-[9px] px-1 py-0.5 rounded bg-amber-100 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400">Soon</span>
                    </div>
                    <input type="checkbox" class="channel-setting-toggle" data-setting="rules.spam" data-label="Anti-Spam" data-tbi="true" {{ ($settings['rules']['spam'] ?? false) ? 'checked' : '' }}>
                </label>
            </div>

            {{-- Visibility Settings --}}
            <div class="space-y-3 pt-3 border-t border-gray-200 dark:border-white/10">
                <p class="text-xs font-semibold uppercase text-gray-500 dark:text-white/50">Visibility</p>
                
                <label class="flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-folder2 text-sm text-gray-500 dark:text-white/50"></i>
                        <span class="text-sm text-gray-700 dark:text-white/80">Show Shared Files</span>
                    </div>
                    <input type="checkbox" class="channel-setting-toggle" data-setting="show_shared_files" data-label="Show Shared Files" {{ ($settings['show_shared_files'] ?? false) ? 'checked' : '' }}>
                </label>
            </div>

            {{-- Danger Zone (only show if viewing a topic, not general) --}}
            @if(request()->query('topic'))
            <div class="space-y-3 pt-3 border-t border-red-200 dark:border-red-900/30">
                <p class="text-xs font-semibold uppercase text-red-500">Danger Zone</p>
                
                <button type="button" onclick="archiveChannel()" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/30 transition">
                    <i class="bi bi-archive"></i>
                    <span>Archive #{{ request()->query('topic') }}</span>
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
@endif {{-- End of isModerator --}}

<style>
.channel-setting-toggle {
    appearance: none;
    width: 36px;
    height: 20px;
    background: #d1d5db;
    border-radius: 10px;
    position: relative;
    cursor: pointer;
    transition: background 0.2s;
}
.channel-setting-toggle:checked {
    background: #6366f1;
}
.channel-setting-toggle::before {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    background: white;
    border-radius: 50%;
    top: 2px;
    left: 2px;
    transition: transform 0.2s;
}
.channel-setting-toggle:checked::before {
    transform: translateX(16px);
}
.dark .channel-setting-toggle {
    background: #374151;
}
.dark .channel-setting-toggle:checked {
    background: #6366f1;
}

/* Right panel collapse styles */
#right-panel-content {
    transition: width 0.2s ease-in-out;
}
#right-panel-content.collapsed {
    width: 0 !important;
    min-width: 0 !important;
    padding: 0 !important;
    border: none !important;
}
#right-panel-content.collapsed > *:not(button) {
    display: none !important;
}
.right-panel-toggle-btn {
    transition: all 0.2s ease-in-out;
}
</style>

<script>
// Right panel collapse/expand
function toggleRightPanel() {
    const panel = document.getElementById('right-panel-content');
    const expandBtn = document.getElementById('right-panel-expand-btn');
    const isCollapsed = panel.classList.contains('collapsed');
    
    if (isCollapsed) {
        // Expand
        panel.classList.remove('collapsed');
        expandBtn.classList.add('hidden');
        expandBtn.classList.remove('flex');
        localStorage.setItem('right-panel-collapsed', 'false');
    } else {
        // Collapse
        panel.classList.add('collapsed');
        expandBtn.classList.remove('hidden');
        expandBtn.classList.add('flex');
        localStorage.setItem('right-panel-collapsed', 'true');
    }
}

// Restore panel state on load
document.addEventListener('DOMContentLoaded', function() {
    const isCollapsed = localStorage.getItem('right-panel-collapsed') === 'true';
    if (isCollapsed) {
        const panel = document.getElementById('right-panel-content');
        const expandBtn = document.getElementById('right-panel-expand-btn');
        if (panel && expandBtn) {
            panel.classList.add('collapsed');
            expandBtn.classList.remove('hidden');
            expandBtn.classList.add('flex');
        }
    }
});

function toggleRightSection(section) {
    const content = document.getElementById(section + '-content');
    const chevron = document.getElementById(section + '-chevron');
    if (content && chevron) {
        const isHidden = content.classList.contains('hidden');
        content.classList.toggle('hidden');
        chevron.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(-90deg)';
    }
}

function switchRightTab(tab) {
    const tabs = ['info', 'settings'];
    tabs.forEach(t => {
        const tabBtn = document.getElementById('tab-' + t);
        const content = document.getElementById('tab-content-' + t);
        if (t === tab) {
            tabBtn?.classList.add('border-primary', 'text-primary');
            tabBtn?.classList.remove('border-transparent', 'text-gray-500', 'dark:text-white/50');
            content?.classList.remove('hidden');
        } else {
            tabBtn?.classList.remove('border-primary', 'text-primary');
            tabBtn?.classList.add('border-transparent', 'text-gray-500', 'dark:text-white/50');
            content?.classList.add('hidden');
        }
    });
}

document.querySelectorAll('.channel-setting-toggle').forEach(toggle => {
    toggle.addEventListener('change', async function() {
        const setting = this.dataset.setting;
        const label = this.dataset.label || 'Setting';
        const invert = this.dataset.invert === 'true';
        const isTbi = this.dataset.tbi === 'true';
        let value = this.checked;
        if (invert) value = !value;

        const conversationId = {{ $selectedConversation->id }};
        const data = {};
        
        if (setting.includes('.')) {
            const [parent, child] = setting.split('.');
            data[parent] = { [child]: value };
        } else {
            data[setting] = value;
        }

        try {
            const response = await fetch(`/chats/${conversationId}/settings`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            if (!response.ok) throw new Error('Failed to save');
            
            if (isTbi) {
                if (typeof showChatToast === 'function') showChatToast(`${label} saved. Feature coming soon!`, 'info');
            } else {
                if (typeof showChatToast === 'function') showChatToast(`${label} ${value ? 'enabled' : 'disabled'}`, 'success');
            }
        } catch (e) {
            this.checked = !this.checked;
            if (typeof showChatToast === 'function') showChatToast(`Failed to update ${label}`, 'error');
        }
    });
});

function archiveChannel() {
    const topicSlug = @json(request()->query('topic'));
    const conversationId = {{ $selectedConversation->id }};
    const currentTopic = @json($currentTopicData);
    
    Swal.fire({
        title: 'Archive #' + topicSlug + '?',
        text: 'This channel will be hidden but can be restored later by moderators.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        confirmButtonText: 'Archive'
    }).then(result => {
        if (result.isConfirmed && currentTopic?.id) {
            fetch(`/chats/manage/${conversationId}/channels/${currentTopic.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ is_archived: true })
            }).then(r => {
                if (r.ok) {
                    window.location.href = `/chats/v2?conversation=${conversationId}`;
                } else {
                    window.showToast?.('Failed to archive channel', 'error');
                }
            });
        }
    });
}

let filesLoaded = false;
async function loadSharedFiles() {
    if (filesLoaded) return;
    
    const conversationId = {{ $selectedConversation->id }};
    const loading = document.getElementById('files-loading');
    const list = document.getElementById('files-list');
    const empty = document.getElementById('files-empty');
    
    try {
        const res = await fetch(`/chats/${conversationId}/attachments`, {
            headers: { 'Accept': 'application/json' }
        });
        const files = await res.json();
        
        loading?.classList.add('hidden');
        
        if (!files.length) {
            empty?.classList.remove('hidden');
            return;
        }
        
        list.innerHTML = files.map(f => `
            <a href="/chats/attachments/${f.id}/stream" target="_blank" 
               class="flex items-center gap-2 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-white/10 transition group">
                <div class="size-8 rounded flex items-center justify-center ${getFileIconBg(f.mime)}">
                    <i class="bi ${getFileIcon(f.mime)} text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-700 dark:text-white/80 truncate">${escapeHtml(f.name)}</p>
                    <p class="text-[10px] text-gray-500 dark:text-white/40">${formatFileSize(f.size)}</p>
                </div>
                <i class="bi bi-download text-xs text-gray-400 opacity-0 group-hover:opacity-100 transition"></i>
            </a>
        `).join('');
        
        filesLoaded = true;
    } catch (e) {
        loading?.classList.add('hidden');
        empty?.classList.remove('hidden');
    }
}

function getFileIcon(mime) {
    if (!mime) return 'bi-file-earmark';
    if (mime.startsWith('image/')) return 'bi-file-earmark-image';
    if (mime.startsWith('video/')) return 'bi-file-earmark-play';
    if (mime.startsWith('audio/')) return 'bi-file-earmark-music';
    if (mime.includes('pdf')) return 'bi-file-earmark-pdf';
    if (mime.includes('word') || mime.includes('document')) return 'bi-file-earmark-word';
    if (mime.includes('sheet') || mime.includes('excel')) return 'bi-file-earmark-excel';
    if (mime.includes('zip') || mime.includes('rar') || mime.includes('archive')) return 'bi-file-earmark-zip';
    return 'bi-file-earmark';
}

function getFileIconBg(mime) {
    if (!mime) return 'bg-gray-100 dark:bg-white/10 text-gray-500';
    if (mime.startsWith('image/')) return 'bg-purple-100 dark:bg-purple-500/20 text-purple-500';
    if (mime.startsWith('video/')) return 'bg-pink-100 dark:bg-pink-500/20 text-pink-500';
    if (mime.startsWith('audio/')) return 'bg-amber-100 dark:bg-amber-500/20 text-amber-500';
    if (mime.includes('pdf')) return 'bg-red-100 dark:bg-red-500/20 text-red-500';
    if (mime.includes('word') || mime.includes('document')) return 'bg-blue-100 dark:bg-blue-500/20 text-blue-500';
    if (mime.includes('sheet') || mime.includes('excel')) return 'bg-green-100 dark:bg-green-500/20 text-green-500';
    return 'bg-gray-100 dark:bg-white/10 text-gray-500';
}

function formatFileSize(bytes) {
    if (!bytes) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
</script>
