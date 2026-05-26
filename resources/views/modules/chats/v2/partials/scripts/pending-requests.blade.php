{{-- Pending Join Requests Script --}}
<script>
    const initPendingRequests = () => {
        const conversationId = @json($selectedConversation?->id);
        const isOwner = @json(($selectedConversation?->created_by ?? null) == auth()->id());
        const isGroup = @json($selectedConversation?->type === 'group');
        
        const headerBtn = document.getElementById('header-join-requests-btn');
        const sidemenuBtnMobile = document.getElementById('sidemenu-join-requests-btn-mobile');
        
        if (!isGroup || !isOwner) {
            headerBtn?.classList.add('hidden');
            sidemenuBtnMobile?.classList.add('hidden');
            return;
        }
        
        headerBtn?.classList.remove('hidden');
        sidemenuBtnMobile?.classList.remove('hidden');

        const loadPendingCount = async () => {
            try {
                const response = await fetch(`/chats/${conversationId}/join-requests`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const count = data.requests?.length || 0;
                    
                    const section = document.getElementById('pending-requests-section');
                    const countEl = document.getElementById('sidebar-pending-count');
                    const headerBadge = document.getElementById('header-requests-badge');
                    const sidemenuBadgeMobile = document.getElementById('sidemenu-requests-badge-mobile');
                    
                    if (count > 0) {
                        section?.classList.remove('hidden');
                        if (countEl) countEl.textContent = count;
                        if (headerBadge) {
                            headerBadge.textContent = count;
                            headerBadge.classList.remove('hidden');
                        }
                        if (sidemenuBadgeMobile) {
                            sidemenuBadgeMobile.textContent = count;
                            sidemenuBadgeMobile.classList.remove('hidden');
                        }
                    } else {
                        section?.classList.add('hidden');
                        headerBadge?.classList.add('hidden');
                        sidemenuBadgeMobile?.classList.add('hidden');
                    }
                }
            } catch (e) {
                console.error('Failed to load pending requests:', e);
            }
        };

        loadPendingCount();
        window.refreshPendingRequests = loadPendingCount;
    };

    document.addEventListener('DOMContentLoaded', initPendingRequests);
</script>
