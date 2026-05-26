{{-- Chat V2 Initialization Scripts --}}
<script>
    const initChatV2 = () => {
        const modalContainers = document.querySelectorAll('[data-chat-modal]');
        const chatsV2Url = @json(route('chats.v2'));
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        const openModal = (name) => {
            const modal = document.querySelector(`[data-chat-modal="${name}"]`);
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('chat-modal-open');
        };

        const closeModal = (name) => {
            const modal = document.querySelector(`[data-chat-modal="${name}"]`);
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (!document.querySelector('[data-chat-modal].flex')) {
                document.body.classList.remove('chat-modal-open');
            }
        };

        document.querySelectorAll('[data-open-chat-modal]').forEach(btn => {
            btn.addEventListener('click', () => {
                const name = btn.getAttribute('data-open-chat-modal');
                if (!name) return;
                openModal(name);
            });
        });

        modalContainers.forEach(modal => {
            const name = modal.getAttribute('data-chat-modal');
            modal.addEventListener('click', (e) => {
                if (e.target === modal || e.target.closest(`[data-chat-modal-close="${name}"]`)) {
                    closeModal(name);
                }
            });
        });

        const handleCreateGroupSubmit = async (event) => {
            const form = event.target;
            if (!form.matches('#create-group-form')) return;
            // Skip - handled by the modal's own script
            return;
        };

        document.addEventListener('submit', handleCreateGroupSubmit, true);

        document.addEventListener('click', async (event) => {
            if (!event.target || typeof event.target.closest !== 'function') return;
            const trigger = event.target.closest('[data-delete-group]');
            if (!trigger) return;

            const deleteUrl = trigger.getAttribute('data-delete-url');
            const groupName = trigger.getAttribute('data-group-name') || 'this group';

            const confirmed = await (window.Swal?.fire({
                icon: 'warning',
                title: 'Delete group?',
                text: `This will permanently delete "${groupName}" and all of its messages.`,
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Delete group',
            }) ?? Promise.resolve(confirm(`Delete ${groupName}?`)));

            if (!confirmed || (confirmed.isConfirmed === false && typeof confirmed !== 'boolean')) return;

            try {
                const response = await fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken ?? '',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) throw new Error('Unable to delete group right now.');

                await (window.Swal?.fire({
                    icon: 'success',
                    title: 'Group deleted',
                    text: `"${groupName}" has been removed.`,
                    timer: 1800,
                    showConfirmButton: false,
                }) ?? Promise.resolve());

                window.location.href = chatsV2Url;
            } catch (error) {
                window.Swal?.fire({
                    icon: 'error',
                    title: 'Could not delete group',
                    text: error?.message ?? 'Unexpected error. Please try again.',
                }) ?? alert(error?.message);
            }
        });

        // Initialize todo modal
        initTodoModal();
    };

    window.toggleConversationLock = async (conversationId, isLocked) => {
        if (!conversationId) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const endpoint = isLocked ? 'unlock' : 'lock';
        const actionLabel = isLocked ? 'unlock' : 'lock';

        try {
            const response = await fetch(`/chats/manage/${conversationId}/${endpoint}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                const payload = await response.json().catch(() => ({}));
                throw new Error(payload.message || `Failed to ${actionLabel} chat`);
            }

            if (typeof showChatToast === 'function') {
                showChatToast(`Chat ${isLocked ? 'unlocked' : 'locked'}`, 'success');
            }

            setTimeout(() => window.location.reload(), 250);
        } catch (error) {
            if (typeof showChatToast === 'function') {
                showChatToast(error.message || `Failed to ${actionLabel} chat`, 'error');
            }
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChatV2);
    } else {
        initChatV2();
    }
</script>
