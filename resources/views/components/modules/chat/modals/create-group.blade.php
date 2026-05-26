{{-- Create Group Modal --}}
@php
    $contactCollection = collect($contacts ?? [])->filter(fn ($contact) => !empty($contact['user_id']))->take(12);
@endphp

<div data-chat-modal="create-group" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-10">
    <div class="modal-content relative w-full max-w-lg rounded-2xl bg-white dark:bg-[#1a1a2e] shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="modal-header flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                    <i class="bi bi-people-fill text-primary text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Create New Group</h2>
                    <p class="text-xs text-gray-500 dark:text-white/50">Group chats keep squads organized</p>
                </div>
            </div>
            <button type="button" data-chat-modal-close="create-group"
                class="rounded-lg p-2 text-gray-400 hover:text-gray-600 dark:text-white/40 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        {{-- Body --}}
        <form id="create-group-form" onsubmit="return false;" class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
            {{-- Group Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-white/70 mb-1.5">Group name</label>
                <input type="text" id="group-name-input" name="name" 
                    class="w-full rounded-lg border border-gray-300 dark:border-white/20 bg-white dark:bg-[#252540] px-4 py-2.5 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-white/40 focus:border-primary focus:ring-1 focus:ring-primary transition"
                    placeholder="Design Sprint Squad" maxlength="255" required>
            </div>

            {{-- Quick Invites --}}
            @if($contactCollection->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-white/70">Quick invites</label>
                    <span class="text-xs text-gray-400 dark:text-white/40">select up to 12</span>
                </div>
                <div class="flex flex-wrap gap-2" id="contacts-grid">
                    @foreach($contactCollection as $contact)
                    <button type="button" class="contact-chip flex items-center gap-2 rounded-full border border-gray-200 dark:border-white/20 bg-gray-50 dark:bg-white/5 px-3 py-1.5 text-xs text-gray-600 dark:text-white/70 transition hover:border-primary"
                        data-user-id="{{ $contact['user_id'] }}" data-name="{{ $contact['name'] }}">
                        <img src="{{ $contact['avatar'] }}" class="w-6 h-6 rounded-full" alt="{{ $contact['name'] }}">
                        <span class="max-w-[90px] truncate">{{ $contact['name'] }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            @else
            <p class="text-xs text-gray-400 dark:text-white/40">Your frequent contacts will appear here once you start chatting.</p>
            @endif


            {{-- Prebuilt Channels (Starred) --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <i class="bi bi-star-fill text-amber-400 text-sm"></i>
                    <label class="text-sm font-medium text-gray-700 dark:text-white/70">Prebuilt Channels</label>
                    <span class="text-xs text-gray-400 dark:text-white/40">(moderator-only posting)</span>
                </div>
                <div class="prebuilt-section space-y-2 rounded-lg border border-gray-200 dark:border-white/20 bg-gray-50 dark:bg-white/5 p-3">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="prebuilt_channels[]" value="announcements" 
                            class="w-4 h-4 rounded border-gray-300 dark:border-white/30 text-primary focus:ring-primary dark:bg-[#252540]">
                        <div class="flex items-center gap-2 flex-1">
                            <i class="bi bi-megaphone text-amber-500"></i>
                            <span class="text-sm text-gray-700 dark:text-white/80">#announcements</span>
                        </div>
                        <span class="text-xs text-gray-400 dark:text-white/40">Important updates</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="prebuilt_channels[]" value="welcome" 
                            class="w-4 h-4 rounded border-gray-300 dark:border-white/30 text-primary focus:ring-primary dark:bg-[#252540]">
                        <div class="flex items-center gap-2 flex-1">
                            <i class="bi bi-hand-wave text-green-500"></i>
                            <span class="text-sm text-gray-700 dark:text-white/80">#welcome</span>
                        </div>
                        <span class="text-xs text-gray-400 dark:text-white/40">New member greetings</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="prebuilt_channels[]" value="guidelines" 
                            class="w-4 h-4 rounded border-gray-300 dark:border-white/30 text-primary focus:ring-primary dark:bg-[#252540]">
                        <div class="flex items-center gap-2 flex-1">
                            <i class="bi bi-book text-blue-500"></i>
                            <span class="text-sm text-gray-700 dark:text-white/80">#guidelines</span>
                        </div>
                        <span class="text-xs text-gray-400 dark:text-white/40">Rules & expectations</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="prebuilt_channels[]" value="resources" 
                            class="w-4 h-4 rounded border-gray-300 dark:border-white/30 text-primary focus:ring-primary dark:bg-[#252540]">
                        <div class="flex items-center gap-2 flex-1">
                            <i class="bi bi-folder text-purple-500"></i>
                            <span class="text-sm text-gray-700 dark:text-white/80">#resources</span>
                        </div>
                        <span class="text-xs text-gray-400 dark:text-white/40">Helpful links & files</span>
                    </label>
                </div>
                <p class="text-xs text-gray-400 dark:text-white/40 mt-1.5">
                    <i class="bi bi-info-circle mr-1"></i>
                    These channels appear in the Starred section. Only moderators can post.
                </p>
            </div>

            {{-- Custom Channels --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-white/70">Custom Channels</label>
                    <span class="text-xs text-gray-400 dark:text-white/40">press enter to add</span>
                </div>
                <div class="flex flex-wrap gap-2 mb-2 min-h-[28px]" id="channel-tags-container"></div>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-white/40">#</span>
                        <input type="text" id="channel-input"
                            class="w-full rounded-lg border border-gray-300 dark:border-white/20 bg-white dark:bg-[#252540] pl-7 pr-4 py-2.5 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-white/40 focus:border-primary focus:ring-1 focus:ring-primary transition"
                            placeholder="general, random...">
                    </div>
                    <button type="button" id="add-channel-btn"
                        class="px-4 py-2.5 rounded-lg border border-gray-200 dark:border-white/20 bg-gray-50 dark:bg-white/5 text-gray-600 dark:text-white/70 hover:border-primary hover:text-primary transition">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-400 dark:text-white/40 mt-1">Add custom channels for your group. Everyone can post here.</p>
            </div>
        </form>

        {{-- Footer --}}
        <div class="modal-footer flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5">
            <button type="button" data-chat-modal-close="create-group"
                class="px-4 py-2 rounded-lg text-gray-600 dark:text-white/70 hover:bg-gray-100 dark:hover:bg-white/10 transition font-medium">
                Cancel
            </button>
            <button type="submit" form="create-group-form" id="create-group-submit"
                class="create-group-btn px-5 py-2 rounded-lg font-medium transition flex items-center gap-2"
                style="background-color: #2b2bee !important; color: #ffffff !important;">
                <i class="bi bi-people-fill"></i>
                <span>Create Group</span>
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-chat-modal="create-group"]');
    const form = document.getElementById('create-group-form');
    const nameInput = document.getElementById('group-name-input');
    const channelInput = document.getElementById('channel-input');
    const addChannelBtn = document.getElementById('add-channel-btn');
    const tagsContainer = document.getElementById('channel-tags-container');
    const contactsGrid = document.getElementById('contacts-grid');
    const submitBtn = document.getElementById('create-group-submit');

    let selectedMembers = [];
    let channelTags = [];

    // Contact chip selection
    contactsGrid?.addEventListener('click', (e) => {
        const chip = e.target.closest('.contact-chip');
        if (!chip) return;

        const userId = chip.dataset.userId;
        const isSelected = chip.classList.contains('selected');

        if (isSelected) {
            selectedMembers = selectedMembers.filter(id => id !== userId);
            chip.classList.remove('selected', 'border-primary', 'bg-primary/10', 'text-primary', 'dark:bg-primary/20');
            chip.classList.add('border-gray-200', 'dark:border-white/20', 'bg-gray-50', 'dark:bg-white/5', 'text-gray-600', 'dark:text-white/70');
        } else {
            selectedMembers.push(userId);
            chip.classList.add('selected', 'border-primary', 'bg-primary/10', 'text-primary', 'dark:bg-primary/20');
            chip.classList.remove('border-gray-200', 'dark:border-white/20', 'bg-gray-50', 'dark:bg-white/5', 'text-gray-600', 'dark:text-white/70');
        }
    });

    // Render channel tags
    const renderTags = () => {
        tagsContainer.innerHTML = channelTags.map((tag, i) => `
            <span class="inline-flex items-center gap-1 rounded-full border border-primary/30 bg-primary/10 px-2.5 py-1 text-xs font-medium text-primary">
                <span class="text-primary/60">#</span>${tag}
                <button type="button" data-remove="${i}" class="ml-1 hover:text-primary/80">&times;</button>
            </span>
        `).join('');
    };

    // Add channel tag
    const addTag = () => {
        const values = channelInput.value.split(',').map(v =>
            v.trim().toLowerCase().replace(/[^a-z0-9-]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '')
        ).filter(v => v && !channelTags.includes(v));

        values.forEach(v => { if (channelTags.length < 10) channelTags.push(v); });
        if (values.length > 0) { renderTags(); channelInput.value = ''; }
    };

    addChannelBtn?.addEventListener('click', addTag);
    channelInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ',') { e.preventDefault(); addTag(); }
    });

    tagsContainer?.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-remove]');
        if (btn) {
            channelTags.splice(parseInt(btn.dataset.remove), 1);
            renderTags();
        }
    });

    // Form submit
    form?.addEventListener('submit', async (e) => {
        e.preventDefault();

        const name = nameInput.value.trim();
        if (!name) {
            nameInput.focus();
            return;
        }

        // Get prebuilt channels
        const prebuiltChannels = Array.from(form.querySelectorAll('input[name="prebuilt_channels[]"]:checked'))
            .map(cb => cb.value);

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Creating...';

        try {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
            formData.append('type', 'group');
            formData.append('name', name);
            formData.append('channels', JSON.stringify(channelTags));
            formData.append('prebuilt_channels', JSON.stringify(prebuiltChannels));
            selectedMembers.forEach(id => formData.append('member_ids[]', id));

            const response = await fetch('{{ route("conversations.store") }}', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: formData,
            });

            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Failed to create group');

            // Close modal
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // Show success toast
            if (typeof showChatToast === 'function') {
                showChatToast(`Group "${name}" created!`, 'success');
            }

            // Redirect to new conversation
            const conversationId = data.conversation_id || data.id;
            if (conversationId) {
                const url = new URL(window.location.href);
                url.searchParams.set('conversation', conversationId);
                window.location = url.toString();
            } else {
                window.location.reload();
            }
        } catch (error) {
            console.error('Create group error:', error);
            if (typeof showChatToast === 'function') {
                showChatToast(error.message || 'Failed to create group', 'error');
            }
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-people-fill"></i> <span>Create Group</span>';
        }
    });

    // Reset form when modal opens
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class' && modal.classList.contains('flex')) {
                // Reset form
                form.reset();
                selectedMembers = [];
                channelTags = [];
                renderTags();
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-people-fill"></i> <span>Create Group</span>';
                
                // Reset contact chips
                document.querySelectorAll('.contact-chip.selected').forEach(chip => {
                    chip.classList.remove('selected', 'border-primary', 'bg-primary/10', 'text-primary', 'dark:bg-primary/20');
                    chip.classList.add('border-gray-200', 'dark:border-white/20', 'bg-gray-50', 'dark:bg-white/5', 'text-gray-600', 'dark:text-white/70');
                });
                
                // Focus name input
                setTimeout(() => nameInput?.focus(), 100);
            }
        });
    });
    observer.observe(modal, { attributes: true });
});
</script>
