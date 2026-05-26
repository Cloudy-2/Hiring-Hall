{{-- Group Settings Modal --}}
<div data-chat-modal="group-settings" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl" style="overflow: visible !important;">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-4">
            <div class="flex items-center gap-2">
                <i class="bi bi-gear-fill text-xl text-primary"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Group Settings</h2>
            </div>
            <button type="button" data-chat-modal-close="group-settings" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Content --}}
        <div class="p-5 space-y-6" style="max-height: 60vh; overflow-y: auto;">
            {{-- Group Info Section --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <i class="bi bi-info-circle text-xl text-cyan-500"></i>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Group Info</h3>
                </div>
                
                <div class="space-y-3 pl-4 border-l-2 border-cyan-500/30">
                    {{-- Group Description --}}
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-white/80">Description</label>
                        <textarea id="group-description" rows="2" maxlength="200"
                            placeholder="What's this group about?"
                            class="w-full rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-primary focus:ring-1 focus:ring-primary resize-none"></textarea>
                        <p class="text-xs text-gray-400 dark:text-white/40">Shown in the explore modal and group info.</p>
                    </div>
                    
                    {{-- Public Group Toggle --}}
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <span class="text-sm font-medium text-gray-700 dark:text-white/80">Public Group</span>
                            <p class="text-xs text-gray-500 dark:text-white/50">Anyone can discover and join this group</p>
                        </div>
                        <div class="relative inline-flex items-center">
                            <input type="checkbox" id="group-is-public" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 dark:peer-focus:ring-primary rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-500"></div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Group Invite Link Section --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-link-45deg text-xl text-blue-500"></i>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Invite Link</h3>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="invite-enabled" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 dark:peer-focus:ring-primary rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                    </label>
                </div>
                <p class="text-xs text-gray-500 dark:text-white/50">Allow people to join this group using an invite link.</p>
                
                <div id="invite-link-section" class="space-y-3 pl-4 border-l-2 border-blue-500/30">
                    <div class="flex items-center gap-2">
                        <input type="text" id="invite-link" readonly
                            value=""
                            placeholder="Loading invite link..."
                            class="flex-1 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-3 py-2 text-sm text-gray-700 dark:text-white/80 focus:border-primary focus:ring-1 focus:ring-primary">
                        <button type="button" id="copy-invite-btn"
                            class="rounded-lg bg-blue-500 px-3 py-2 text-sm font-medium text-white hover:bg-blue-600 transition">
                            <i class="bi bi-clipboard"></i>
                        </button>
                        <button type="button" id="regenerate-invite-btn"
                            class="rounded-lg border border-gray-300 dark:border-white/20 px-3 py-2 text-sm font-medium text-gray-600 dark:text-white/70 hover:bg-gray-100 dark:hover:bg-white/10 transition"
                            title="Generate new link">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-white/40">Share this link to invite people to your group.</p>
                    
                    {{-- Require Approval Toggle --}}
                    <label class="flex items-center justify-between cursor-pointer mt-3 pt-3 border-t border-gray-200 dark:border-white/10">
                        <div>
                            <span class="text-sm font-medium text-gray-700 dark:text-white/80">Require Approval</span>
                            <p class="text-xs text-gray-500 dark:text-white/50">New members must be approved by an admin</p>
                        </div>
                        <div class="relative inline-flex items-center">
                            <input type="checkbox" id="require-approval" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 dark:peer-focus:ring-primary rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Channel Settings Section --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <i class="bi bi-hash text-xl text-purple-500"></i>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Channel Settings</h3>
                </div>
                <p class="text-xs text-gray-500 dark:text-white/50">Control who can send messages in channels.</p>
                
                <div class="space-y-3 pl-4 border-l-2 border-purple-500/30">
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <span class="text-sm font-medium text-gray-700 dark:text-white/80">Admin-only mode</span>
                            <p class="text-xs text-gray-500 dark:text-white/50">Only admins can send messages (muted for members)</p>
                        </div>
                        <input type="checkbox" id="channel-admin-only" class="rounded border-gray-300 dark:border-white/20 text-primary focus:ring-primary">
                    </label>
                    
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <span class="text-sm font-medium text-gray-700 dark:text-white/80">Slow mode</span>
                            <p class="text-xs text-gray-500 dark:text-white/50">Members can only send one message every 30 seconds</p>
                        </div>
                        <input type="checkbox" id="channel-slow-mode" class="rounded border-gray-300 dark:border-white/20 text-primary focus:ring-primary">
                    </label>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-white/80">Default Channel Visibility</label>
                        <select id="channel-visibility" class="w-full rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="public">Public - Everyone can see</option>
                            <option value="private">Private - Invite only</option>
                            <option value="admin">Admin Only</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Moderation Bot Section --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="https://api.dicebear.com/7.x/bottts/svg?seed=HillBot&backgroundColor=6366f1" 
                             alt="HillBot" 
                             class="size-8 rounded-full">
                        <h3 class="font-semibold text-gray-900 dark:text-white">HillBot</h3>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="bot-enabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50 dark:peer-focus:ring-primary rounded-full peer dark:bg-white/10 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                    </label>
                </div>
                <p class="text-xs text-gray-500 dark:text-white/50">Enable HillBot to automatically enforce rules and manage members.</p>
                
                {{-- Bot Settings (shown when enabled) --}}
                <div id="bot-settings" class="hidden space-y-4 pl-4 border-l-2 border-primary/30">
                    {{-- Bot Name --}}
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-white/80">Bot Name</label>
                        <input type="text" id="bot-name" value="HillBot" maxlength="32"
                            class="w-full rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>

                    {{-- Bot Role --}}
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-white/80">Bot Role</label>
                        <select id="bot-role" class="w-full rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="moderator">Moderator - Can warn & mute</option>
                            <option value="admin">Admin - Full moderation powers</option>
                            <option value="custom">Custom Role</option>
                        </select>
                    </div>

                    {{-- Bot Permissions --}}
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-white/80">Permissions</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="perm-warn" checked class="rounded border-gray-300 dark:border-white/20 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-white/80">Warn members</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="perm-mute" checked class="rounded border-gray-300 dark:border-white/20 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-white/80">Mute members</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="perm-kick" class="rounded border-gray-300 dark:border-white/20 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-white/80">Kick members</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="perm-delete" checked class="rounded border-gray-300 dark:border-white/20 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-white/80">Delete messages</span>
                            </label>
                        </div>
                    </div>

                    {{-- Auto-moderation Rules --}}
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-white/80">Auto-moderation Rules</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="rule-profanity" checked class="rounded border-gray-300 dark:border-white/20 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-white/80">Filter profanity</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="rule-spam" checked class="rounded border-gray-300 dark:border-white/20 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-white/80">Detect spam</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="rule-links" class="rounded border-gray-300 dark:border-white/20 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-white/80">Block external links</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="rule-caps" class="rounded border-gray-300 dark:border-white/20 text-primary focus:ring-primary">
                                <span class="text-sm text-gray-700 dark:text-white/80">Limit excessive caps</span>
                            </label>
                        </div>
                    </div>

                    {{-- Punishment Settings --}}
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-white/80">Violation Action</label>
                        <select id="violation-action" class="w-full rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="warn">Warn only</option>
                            <option value="delete">Delete message</option>
                            <option value="mute-5">Mute for 5 minutes</option>
                            <option value="mute-30">Mute for 30 minutes</option>
                            <option value="mute-60">Mute for 1 hour</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="space-y-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <h3 class="font-semibold text-red-500">Danger Zone</h3>
                <button type="button" id="delete-group-btn"
                    class="w-full flex items-center justify-center gap-2 rounded-lg border border-red-300 dark:border-red-500/30 bg-red-50 dark:bg-red-900/20 px-4 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                    <i class="bi bi-trash"></i>
                    Delete Group
                </button>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between gap-3 border-t border-gray-200 dark:border-white/10 px-5 py-4 bg-gray-50 dark:bg-white/5 rounded-b-2xl">
            <button type="button" data-chat-modal-close="group-settings"
                class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-white/70 hover:bg-gray-200 dark:hover:bg-white/10 transition">
                Cancel
            </button>
            <button type="button" id="save-group-settings"
                class="rounded-lg px-6 py-2.5 text-sm font-semibold text-white hover:opacity-90 transition" style="background-color: #2b2bee;">
                Save Settings
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-chat-modal="group-settings"]');
    const botToggle = document.getElementById('bot-enabled');
    const botSettings = document.getElementById('bot-settings');
    const inviteToggle = document.getElementById('invite-enabled');
    const inviteSection = document.getElementById('invite-link-section');
    const copyInviteBtn = document.getElementById('copy-invite-btn');
    const regenerateInviteBtn = document.getElementById('regenerate-invite-btn');
    const deleteBtn = document.getElementById('delete-group-btn');
    const saveBtn = document.getElementById('save-group-settings');
    const inviteLinkInput = document.getElementById('invite-link');

    // Load invite link when modal opens
    async function loadInviteLink() {
        const conversationId = document.getElementById('chat-v2-form')?.dataset.conversationId;
        if (!conversationId || !inviteLinkInput) return;

        inviteLinkInput.value = 'Loading...';

        try {
            const response = await fetch(`/chats/${conversationId}/invite`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (response.ok) {
                const data = await response.json();
                if (data.invite_url) {
                    inviteLinkInput.value = data.invite_url;
                } else {
                    inviteLinkInput.value = 'No invite link - click regenerate to create one';
                }
            } else {
                inviteLinkInput.value = 'Failed to load invite link';
            }
        } catch (error) {
            console.error('Load invite error:', error);
            inviteLinkInput.value = 'Error loading invite link';
        }
    }

    // Watch for modal open
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class' && modal?.classList.contains('flex')) {
                loadInviteLink();
            }
        });
    });

    if (modal) {
        observer.observe(modal, { attributes: true });
    }

    // Toggle bot settings visibility
    botToggle?.addEventListener('change', () => {
        if (botToggle.checked) {
            botSettings?.classList.remove('hidden');
        } else {
            botSettings?.classList.add('hidden');
        }
    });

    // Toggle invite link visibility
    inviteToggle?.addEventListener('change', () => {
        if (inviteToggle.checked) {
            inviteSection?.classList.remove('hidden');
        } else {
            inviteSection?.classList.add('hidden');
        }
    });

    // Copy invite link
    copyInviteBtn?.addEventListener('click', async () => {
        const inviteLink = document.getElementById('invite-link');
        if (inviteLink && inviteLink.value && !inviteLink.value.includes('Loading') && !inviteLink.value.includes('Error') && !inviteLink.value.includes('No invite')) {
            try {
                await navigator.clipboard.writeText(inviteLink.value);
                window.Swal?.fire({
                    icon: 'success',
                    title: 'Copied!',
                    text: 'Invite link copied to clipboard',
                    timer: 1500,
                    showConfirmButton: false,
                });
            } catch (err) {
                inviteLink.select();
                document.execCommand('copy');
            }
        } else {
            window.Swal?.fire({
                icon: 'warning',
                title: 'No link to copy',
                text: 'Please regenerate the invite link first.',
                timer: 2000,
                showConfirmButton: false,
            });
        }
    });

    // Regenerate invite link
    regenerateInviteBtn?.addEventListener('click', async () => {
        const conversationId = document.getElementById('chat-v2-form')?.dataset.conversationId;
        if (!conversationId) return;

        inviteLinkInput.value = 'Generating...';

        try {
            const response = await fetch(`/chats/${conversationId}/regenerate-invite`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                const data = await response.json();
                document.getElementById('invite-link').value = data.invite_url;
                window.Swal?.fire({
                    icon: 'success',
                    title: 'Link regenerated',
                    timer: 1500,
                    showConfirmButton: false,
                });
            } else {
                inviteLinkInput.value = 'Failed to generate link';
            }
        } catch (error) {
            console.error('Regenerate error:', error);
        }
    });

    // Delete group
    deleteBtn?.addEventListener('click', async () => {
        const confirmed = await window.Swal?.fire({
            icon: 'warning',
            title: 'Delete this group?',
            text: 'This action cannot be undone. All messages will be permanently deleted.',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it',
        });

        if (confirmed?.isConfirmed) {
            const conversationId = document.getElementById('chat-v2-form')?.dataset.conversationId;
            if (!conversationId) return;

            try {
                const response = await fetch(`/chats/${conversationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (response.ok) {
                    window.Swal?.fire({
                        icon: 'success',
                        title: 'Group deleted',
                        timer: 1500,
                        showConfirmButton: false,
                    });
                    setTimeout(() => window.location.href = '/chats/v2', 1500);
                }
            } catch (error) {
                console.error('Delete error:', error);
            }
        }
    });

    // Save settings
    saveBtn?.addEventListener('click', async () => {
        const conversationId = document.getElementById('chat-v2-form')?.dataset.conversationId;
        if (!conversationId) return;

        const settings = {
            // Group info
            description: document.getElementById('group-description')?.value || '',
            is_public: document.getElementById('group-is-public')?.checked || false,
            
            // Invite settings
            invite_enabled: inviteToggle?.checked || false,
            require_approval: document.getElementById('require-approval')?.checked || false,
            
            // Channel settings
            channel_admin_only: document.getElementById('channel-admin-only')?.checked || false,
            channel_slow_mode: document.getElementById('channel-slow-mode')?.checked || false,
            channel_visibility: document.getElementById('channel-visibility')?.value || 'public',
            
            // Bot settings
            bot_enabled: botToggle?.checked || false,
            bot_name: document.getElementById('bot-name')?.value || 'HillBot',
            bot_role: document.getElementById('bot-role')?.value || 'moderator',
            permissions: {
                warn: document.getElementById('perm-warn')?.checked || false,
                mute: document.getElementById('perm-mute')?.checked || false,
                kick: document.getElementById('perm-kick')?.checked || false,
                delete: document.getElementById('perm-delete')?.checked || false,
            },
            rules: {
                profanity: document.getElementById('rule-profanity')?.checked || false,
                spam: document.getElementById('rule-spam')?.checked || false,
                links: document.getElementById('rule-links')?.checked || false,
                caps: document.getElementById('rule-caps')?.checked || false,
            },
            violation_action: document.getElementById('violation-action')?.value || 'warn',
        };

        try {
            const response = await fetch(`/chats/${conversationId}/settings`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(settings),
            });

            if (response.ok) {
                window.Swal?.fire({
                    icon: 'success',
                    title: 'Settings saved',
                    timer: 1500,
                    showConfirmButton: false,
                });
                
                // Close modal
                document.querySelector('[data-chat-modal="group-settings"]')?.classList.add('hidden');
                document.querySelector('[data-chat-modal="group-settings"]')?.classList.remove('flex');
            }
        } catch (error) {
            console.error('Save error:', error);
            window.Swal?.fire({
                icon: 'error',
                title: 'Failed to save',
                text: 'Please try again.',
            });
        }
    });
});
</script>
