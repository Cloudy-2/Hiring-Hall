{{-- Join Requests Modal --}}
<div data-chat-modal="join-requests" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-lg mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-4">
            <div class="flex items-center gap-2">
                <i class="bi bi-person-plus-fill text-xl text-primary"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Join Requests</h2>
                <span id="join-requests-count" class="hidden px-2 py-0.5 text-xs font-semibold bg-red-500 text-white rounded-full">0</span>
            </div>
            <button type="button" data-chat-modal-close="join-requests" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Content --}}
        <div class="p-5" style="max-height: 60vh; overflow-y: auto;">
            <div id="join-requests-list" class="space-y-3">
                {{-- Loading state --}}
                <div id="join-requests-loading" class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto"></div>
                    <p class="text-sm text-gray-500 dark:text-white/50 mt-2">Loading requests...</p>
                </div>
                
                {{-- Empty state --}}
                <div id="join-requests-empty" class="hidden text-center py-8">
                    <i class="bi bi-inbox text-4xl text-gray-300 dark:text-white/20"></i>
                    <p class="text-sm text-gray-500 dark:text-white/50 mt-2">No pending requests</p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-3 border-t border-gray-200 dark:border-white/10 px-5 py-4 bg-gray-50 dark:bg-white/5 rounded-b-2xl">
            <button type="button" data-chat-modal-close="join-requests"
                class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-white/70 hover:bg-gray-200 dark:hover:bg-white/10 transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-chat-modal="join-requests"]');
    const listContainer = document.getElementById('join-requests-list');
    const loadingEl = document.getElementById('join-requests-loading');
    const emptyEl = document.getElementById('join-requests-empty');
    const countBadge = document.getElementById('join-requests-count');

    // Load requests when modal opens
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.target.classList.contains('flex') && !mutation.target.classList.contains('hidden')) {
                loadJoinRequests();
            }
        });
    });

    if (modal) {
        observer.observe(modal, { attributes: true, attributeFilter: ['class'] });
    }

    async function loadJoinRequests() {
        const conversationId = document.getElementById('chat-v2-form')?.dataset.conversationId;
        if (!conversationId) return;

        loadingEl?.classList.remove('hidden');
        emptyEl?.classList.add('hidden');

        // Remove existing request items
        listContainer?.querySelectorAll('.join-request-item').forEach(el => el.remove());

        try {
            const response = await fetch(`/chats/${conversationId}/join-requests`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                const data = await response.json();
                loadingEl?.classList.add('hidden');

                if (data.requests.length === 0) {
                    emptyEl?.classList.remove('hidden');
                    countBadge?.classList.add('hidden');
                } else {
                    countBadge?.classList.remove('hidden');
                    countBadge.textContent = data.requests.length;
                    
                    data.requests.forEach(req => {
                        const item = createRequestItem(req);
                        listContainer?.appendChild(item);
                    });
                }
            }
        } catch (error) {
            console.error('Load requests error:', error);
            loadingEl?.classList.add('hidden');
        }
    }

    function createRequestItem(req) {
        const div = document.createElement('div');
        div.className = 'join-request-item flex items-start gap-3 p-3 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5';
        div.dataset.requestId = req.id;

        div.innerHTML = `
            <img src="${req.avatar}" alt="${req.name}" class="size-10 rounded-full object-cover">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">${req.name}</p>
                <p class="text-xs text-gray-500 dark:text-white/50">${req.email}</p>
                ${req.message ? `<p class="text-sm text-gray-600 dark:text-white/70 mt-1 italic">"${req.message}"</p>` : ''}
                <p class="text-xs text-gray-400 dark:text-white/40 mt-1">${req.created_at}</p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" class="approve-btn rounded-lg bg-green-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-green-600 transition">
                    <i class="bi bi-check-lg"></i> Approve
                </button>
                <button type="button" class="reject-btn rounded-lg bg-red-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-600 transition">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        `;

        // Approve handler
        div.querySelector('.approve-btn')?.addEventListener('click', () => handleAction(req.id, 'approve', div));
        
        // Reject handler
        div.querySelector('.reject-btn')?.addEventListener('click', () => handleAction(req.id, 'reject', div));

        return div;
    }

    async function handleAction(requestId, action, element) {
        const conversationId = document.getElementById('chat-v2-form')?.dataset.conversationId;
        if (!conversationId) return;

        try {
            const response = await fetch(`/chats/${conversationId}/join-requests/${requestId}/${action}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                element.remove();
                
                // Update count
                const remaining = listContainer?.querySelectorAll('.join-request-item').length || 0;
                if (remaining === 0) {
                    emptyEl?.classList.remove('hidden');
                    countBadge?.classList.add('hidden');
                } else {
                    countBadge.textContent = remaining;
                }

                // Update sidemenu badge and sidebar section
                updateSidemenuBadge();
                window.refreshPendingRequests?.();

                window.Swal?.fire({
                    icon: 'success',
                    title: action === 'approve' ? 'Request approved' : 'Request rejected',
                    timer: 1500,
                    showConfirmButton: false,
                });
            }
        } catch (error) {
            console.error(`${action} error:`, error);
        }
    }

    // Update sidemenu badge
    async function updateSidemenuBadge() {
        try {
            const response = await fetch('/chats/pending-requests-count', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (response.ok) {
                const data = await response.json();
                const badge = document.getElementById('header-requests-badge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            }
        } catch (e) {}
    }

    // Initial load of badge count
    updateSidemenuBadge();
});
</script>
