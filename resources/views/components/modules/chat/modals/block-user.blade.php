{{-- Block User Modal --}}
<div data-chat-modal="block-user" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-sm mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-4">
            <div class="flex items-center gap-2">
                <i class="bi bi-person-slash text-xl text-red-500"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Block User</h2>
            </div>
            <button type="button" data-chat-modal-close="block-user" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Content --}}
        <div class="p-5">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-4">
                    <i class="bi bi-person-slash text-3xl text-red-500"></i>
                </div>
                <p class="text-sm text-gray-600 dark:text-white/70">
                    Are you sure you want to block this user? They won't be able to send you messages or see when you're online.
                </p>
            </div>

            {{-- What happens when blocked --}}
            <div class="space-y-3 mb-6">
                <div class="flex items-start gap-3 text-sm">
                    <i class="bi bi-x-circle text-red-500 mt-0.5"></i>
                    <span class="text-gray-600 dark:text-white/70">They can't send you messages</span>
                </div>
                <div class="flex items-start gap-3 text-sm">
                    <i class="bi bi-x-circle text-red-500 mt-0.5"></i>
                    <span class="text-gray-600 dark:text-white/70">They can't see your online status</span>
                </div>
                <div class="flex items-start gap-3 text-sm">
                    <i class="bi bi-x-circle text-red-500 mt-0.5"></i>
                    <span class="text-gray-600 dark:text-white/70">They can't call you</span>
                </div>
                <div class="flex items-start gap-3 text-sm">
                    <i class="bi bi-check-circle text-green-500 mt-0.5"></i>
                    <span class="text-gray-600 dark:text-white/70">You can unblock them anytime</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 border-t border-gray-200 dark:border-white/10 px-5 py-4">
            <button type="button" data-chat-modal-close="block-user" class="flex-1 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 text-gray-700 dark:text-white/80 font-medium hover:bg-gray-50 dark:hover:bg-white/5 transition">
                Cancel
            </button>
            <button type="button" id="confirm-block-btn" class="flex-1 py-2.5 rounded-xl bg-red-600 text-white font-medium hover:bg-red-700 transition">
                Block User
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-chat-modal="block-user"]');
    const confirmBtn = document.getElementById('confirm-block-btn');

    const getBlockedUsers = () => {
        try {
            return JSON.parse(localStorage.getItem('chat_blocked_users') || '[]');
        } catch {
            return [];
        }
    };

    const setBlockedUsers = (users) => {
        localStorage.setItem('chat_blocked_users', JSON.stringify(users));
    };

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-open-chat-modal="block-user"]');
        if (!trigger || !modal) return;

        const targetUserId = trigger.getAttribute('data-block-user-id');
        const targetUserName = trigger.getAttribute('data-block-user-name') || 'this user';
        modal.dataset.targetUserId = targetUserId || '';
        modal.dataset.targetUserName = targetUserName;
    });

    confirmBtn?.addEventListener('click', async () => {
        const conversationId = document.getElementById('chat-v2-form')?.dataset.conversationId;
        const targetUserId = modal?.dataset.targetUserId;
        const targetUserName = modal?.dataset.targetUserName || 'User';
        if (!conversationId || !targetUserId) {
            window.Swal?.fire({
                icon: 'error',
                title: 'Unable to block user',
                text: 'No target user was found for this conversation.',
            });
            return;
        }

        try {
            const blocked = getBlockedUsers();
            if (!blocked.includes(String(targetUserId))) {
                blocked.push(String(targetUserId));
                setBlockedUsers(blocked);
            }

            // Also mute conversation notifications for immediate effect.
            await fetch(`/chats/manage/${conversationId}/mute-self`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            }).catch(() => {});

            window.Swal?.fire({
                icon: 'success',
                title: 'User Blocked',
                text: `${targetUserName} has been blocked for your current chat view.`,
                confirmButtonColor: '#2b2bee',
            });

            modal?.classList.add('hidden');
            modal?.classList.remove('flex');

            if (typeof window.applyLocalChatBlocks === 'function') {
                window.applyLocalChatBlocks();
            }
        } catch (error) {
            console.error('Block error:', error);
            window.Swal?.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not block user. Please try again.',
            });
        }
    });
});
</script>
