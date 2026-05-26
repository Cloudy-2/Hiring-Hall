{{-- Quick Add Member Modal (Moderator Only) --}}
<div data-chat-modal="add-member-quick" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-md mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl max-h-[85vh] flex flex-col">
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-4 flex-shrink-0">
            <div class="flex items-center gap-2">
                <i class="bi bi-person-plus-fill text-xl text-primary"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Add Members</h2>
            </div>
            <button type="button" data-chat-modal-close="add-member-quick" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <div class="p-5 space-y-4 overflow-y-auto">
            <div class="flex gap-2">
                <div class="flex-1">
                    <select id="add-member-role-filter" class="w-full rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary">
                        <option value="all">All Roles</option>
                        <option value="applicant">Applicant</option>
                        <option value="employer">Employer</option>
                        <option value="moderator">Moderator</option>
                    </select>
                </div>
                <input type="text" id="add-member-search" placeholder="Search..."
                    class="flex-1 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-3 py-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-primary focus:ring-1 focus:ring-primary">
            </div>

            <div id="add-member-selected-list" class="hidden flex-wrap gap-2 p-2 rounded-lg border border-primary/30 bg-primary/5">
            </div>

            <div id="add-member-results" class="space-y-1 max-h-52 overflow-y-auto">
                <p class="text-sm text-gray-500 dark:text-white/50 text-center py-4">Select a role or search for users</p>
            </div>

            <button type="button" id="add-member-submit" disabled
                class="w-full rounded-lg px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-primary dark:hover:bg-primary/90 transition disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="bi bi-person-plus mr-1"></i> <span id="add-member-btn-text">Add to Group</span>
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-chat-modal="add-member-quick"]');
    const roleFilter = document.getElementById('add-member-role-filter');
    const searchInput = document.getElementById('add-member-search');
    const resultsContainer = document.getElementById('add-member-results');
    const selectedList = document.getElementById('add-member-selected-list');
    const submitBtn = document.getElementById('add-member-submit');
    const btnText = document.getElementById('add-member-btn-text');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    let selectedUsers = new Map();
    let searchTimeout;

    function getConversationId() {
        return new URLSearchParams(window.location.search).get('conversation');
    }

    function updateSelectedUI() {
        if (selectedUsers.size === 0) {
            selectedList.classList.add('hidden');
            selectedList.innerHTML = '';
            submitBtn.disabled = true;
            btnText.textContent = 'Add to Group';
            return;
        }

        selectedList.classList.remove('hidden');
        selectedList.className = 'flex flex-wrap gap-2 p-2 rounded-lg border border-primary/30 bg-primary/5';
        selectedList.innerHTML = Array.from(selectedUsers.values()).map(u => `
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-primary/20 text-sm text-primary">
                <img src="${u.avatar}" class="size-5 rounded-full" alt="">
                ${u.name}
                <button type="button" class="remove-selected hover:text-red-500" data-id="${u.id}">
                    <i class="bi bi-x"></i>
                </button>
            </span>
        `).join('');

        selectedList.querySelectorAll('.remove-selected').forEach(btn => {
            btn.addEventListener('click', () => {
                selectedUsers.delete(btn.dataset.id);
                updateSelectedUI();
                loadUsers();
            });
        });

        submitBtn.disabled = false;
        btnText.textContent = selectedUsers.size === 1 ? 'Add 1 Member' : `Add ${selectedUsers.size} Members`;
    }

    async function loadUsers() {
        const role = roleFilter?.value || 'all';
        const search = searchInput?.value?.trim() || '';
        const conversationId = getConversationId();

        if (!conversationId) {
            resultsContainer.innerHTML = '<p class="text-sm text-red-500 text-center py-4">No conversation selected</p>';
            return;
        }

        resultsContainer.innerHTML = '<div class="flex justify-center py-4"><i class="bi bi-arrow-repeat animate-spin text-xl text-gray-400"></i></div>';

        try {
            const params = new URLSearchParams({ role, conversation_id: conversationId });
            if (search) params.append('q', search);

            const response = await fetch(`/chats/manage/users-by-role?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const users = await response.json();

            const filtered = users.filter(u => !selectedUsers.has(String(u.id)));

            if (filtered.length === 0) {
                resultsContainer.innerHTML = '<p class="text-sm text-gray-500 dark:text-white/50 text-center py-4">No users found</p>';
                return;
            }

            resultsContainer.innerHTML = filtered.map(u => `
                <button type="button" class="add-member-user w-full flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 text-left transition"
                    data-id="${u.id}" data-name="${u.name}" data-role="${u.role}" data-avatar="${u.avatar}">
                    <img src="${u.avatar}" class="size-8 rounded-full" alt="">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${u.name}</p>
                        <p class="text-xs text-gray-500 dark:text-white/50 truncate">${u.email}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase ${getRoleBadge(u.role)}">${u.role}</span>
                </button>
            `).join('');

            resultsContainer.querySelectorAll('.add-member-user').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedUsers.set(btn.dataset.id, {
                        id: btn.dataset.id,
                        name: btn.dataset.name,
                        avatar: btn.dataset.avatar,
                        role: btn.dataset.role
                    });
                    updateSelectedUI();
                    loadUsers();
                });
            });
        } catch (e) {
            resultsContainer.innerHTML = '<p class="text-sm text-red-500 text-center py-4">Failed to load users</p>';
        }
    }

    function getRoleBadge(role) {
        const badges = {
            moderator: 'bg-purple-100 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400',
            employer: 'bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400',
            candidate: 'bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400'
        };
        return badges[role] || 'bg-gray-100 dark:bg-gray-500/20 text-gray-600 dark:text-gray-400';
    }

    roleFilter?.addEventListener('change', loadUsers);
    searchInput?.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadUsers, 300);
    });

    submitBtn?.addEventListener('click', async () => {
        const conversationId = getConversationId();
        if (selectedUsers.size === 0 || !conversationId) return;

        submitBtn.disabled = true;
        btnText.innerHTML = '<i class="bi bi-arrow-repeat animate-spin mr-1"></i> Adding...';

        try {
            const response = await fetch(`/chats/manage/${conversationId}/add-member`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ user_ids: Array.from(selectedUsers.keys()).map(Number) }),
            });

            const data = await response.json();
            if (response.ok) {
                window.Swal?.fire({
                    icon: 'success',
                    title: 'Added!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false,
                });
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                selectedUsers.clear();
                location.reload();
            } else {
                throw new Error(data.message || 'Failed to add members');
            }
        } catch (e) {
            window.Swal?.fire({ icon: 'error', title: 'Error', text: e.message });
            submitBtn.disabled = false;
            btnText.textContent = `Add ${selectedUsers.size} Member${selectedUsers.size > 1 ? 's' : ''}`;
        }
    });

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class' && modal?.classList.contains('flex')) {
                selectedUsers.clear();
                updateSelectedUI();
                searchInput.value = '';
                roleFilter.value = 'all';
                loadUsers();
            }
        });
    });
    if (modal) observer.observe(modal, { attributes: true });
});
</script>
