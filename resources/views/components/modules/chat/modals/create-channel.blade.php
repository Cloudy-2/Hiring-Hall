{{-- Create Channel - SweetAlert Version --}}
<div id="create-channel-trigger" data-chat-modal="create-channel" class="hidden"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    document.querySelectorAll('[data-open-chat-modal="create-channel"]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();

            const conversationId = document.getElementById('chat-v2-form')?.dataset.conversationId;
            if (!conversationId) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No conversation selected' });
                return;
            }

            const { value: formValues } = await Swal.fire({
                title: '<i class="bi bi-hash text-primary"></i> Create Channel',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Channel Name</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">#</span>
                                <input id="swal-channel-name" class="swal2-input !m-0 !w-full !pl-7" placeholder="announcements" maxlength="50" required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Lowercase letters, numbers, and hyphens only</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description <span class="text-gray-400">(optional)</span></label>
                            <textarea id="swal-channel-desc" class="swal2-textarea !m-0 !w-full" placeholder="What's this channel about?" maxlength="200" rows="2"></textarea>
                        </div>
                        <div class="space-y-2 pt-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Channel Type</label>
                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-primary/50 transition">
                                <input type="radio" name="swal_channel_type" value="normal" checked class="text-primary focus:ring-primary">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Normal Channel</p>
                                    <p class="text-xs text-gray-500">Everyone can read and send messages</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-primary/50 transition">
                                <input type="radio" name="swal_channel_type" value="readonly" class="text-primary focus:ring-primary">
                                <div class="flex items-center gap-2">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Announcement Channel</p>
                                        <p class="text-xs text-gray-500">Only moderators can post</p>
                                    </div>
                                    <i class="bi bi-megaphone text-amber-500"></i>
                                </div>
                            </label>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-plus-lg mr-1"></i> Create Channel',
                confirmButtonColor: '#2b2bee',
                cancelButtonText: 'Cancel',
                customClass: { popup: 'rounded-2xl', htmlContainer: '!px-6' },
                didOpen: () => {
                    const nameInput = Swal.getPopup().querySelector('#swal-channel-name');
                    nameInput.focus();
                    nameInput.addEventListener('input', (e) => {
                        e.target.value = e.target.value.toLowerCase().replace(/[^a-z0-9-]/g, '-').replace(/-+/g, '-');
                    });
                },
                preConfirm: () => {
                    const popup = Swal.getPopup();
                    const name = popup.querySelector('#swal-channel-name').value.trim();
                    const description = popup.querySelector('#swal-channel-desc').value.trim();
                    const isReadonly = popup.querySelector('input[name="swal_channel_type"]:checked')?.value === 'readonly';

                    if (!name) {
                        Swal.showValidationMessage('Please enter a channel name');
                        return false;
                    }
                    return { name, description, is_readonly: isReadonly };
                }
            });

            if (formValues) {
                try {
                    const response = await fetch(`/chats/${conversationId}/topics`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify(formValues),
                    });

                    if (response.ok) {
                        const label = formValues.is_readonly ? 'Announcement channel' : 'Channel';
                        await Swal.fire({
                            icon: 'success',
                            title: `${label} Created!`,
                            html: `<span class="text-primary font-medium">#${formValues.name}</span> is ready to use.`,
                            timer: 1500,
                            showConfirmButton: false,
                        });
                        window.location.reload();
                    } else {
                        const data = await response.json();
                        throw new Error(data.message || 'Failed to create channel');
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: error.message });
                }
            }
        });
    });
});
</script>
