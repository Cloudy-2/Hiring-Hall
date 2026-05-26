{{-- Moderator: Manage Members Modal --}}
<div data-chat-modal="manage-members" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-2xl mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl max-h-[85vh] flex flex-col">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-4 flex-shrink-0">
            <div class="flex items-center gap-2">
                <i class="bi bi-people-fill text-xl text-amber-500"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Manage Members</h2>
            </div>
            <button type="button" data-chat-modal-close="manage-members" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="flex border-b border-gray-200 dark:border-white/10 px-5 flex-shrink-0">
            <button type="button" data-tab="waiting" class="manage-tab active px-4 py-3 text-sm font-medium border-b-2 border-primary text-primary">
                <i class="bi bi-hourglass-split mr-1"></i> Waiting List
            </button>
            <button type="button" data-tab="assign" class="manage-tab px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 dark:text-white/60 hover:text-gray-700 dark:hover:text-white">
                <i class="bi bi-person-plus mr-1"></i> Assign to Group
            </button>
            <button type="button" data-tab="groups" class="manage-tab px-4 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 dark:text-white/60 hover:text-gray-700 dark:hover:text-white">
                <i class="bi bi-collection mr-1"></i> All Groups
            </button>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-5">
            {{-- Waiting List Tab --}}
            <div id="tab-waiting" class="manage-tab-content">
                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-white/60">Applicants waiting to be assigned to a group chat.</p>
                </div>
                <div id="waiting-list-container" class="space-y-2">
                    <div class="flex items-center justify-center py-8">
                        <i class="bi bi-arrow-repeat animate-spin text-2xl text-gray-400"></i>
                    </div>
                </div>
            </div>

            {{-- Assign Tab --}}
            <div id="tab-assign" class="manage-tab-content hidden">
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-white/80">Search Applicant</label>
                        <input type="text" id="candidate-search" placeholder="Search by name or email..."
                            class="w-full rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-3 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:border-primary focus:ring-1 focus:ring-primary">
                    </div>
                    <div id="candidate-search-results" class="space-y-2 max-h-40 overflow-y-auto"></div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-white/80">Select Group</label>
                        <select id="assign-group-select" class="w-full rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-3 py-2.5 text-sm text-gray-900 dark:text-white focus:border-primary focus:ring-1 focus:ring-primary">
                            <option value="">Select a group...</option>
                        </select>
                    </div>

                    <div id="selected-candidate" class="hidden p-3 rounded-lg border border-primary/30 bg-primary/5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img id="selected-candidate-avatar" src="" class="size-10 rounded-full" alt="">
                                <div>
                                    <p id="selected-candidate-name" class="text-sm font-medium text-gray-900 dark:text-white"></p>
                                    <p id="selected-candidate-email" class="text-xs text-gray-500 dark:text-white/50"></p>
                                </div>
                            </div>
                            <button type="button" id="clear-selected-candidate" class="text-gray-400 hover:text-red-500">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>

                    <button type="button" id="assign-candidate-btn" disabled
                        class="w-full rounded-lg px-4 py-2.5 text-sm font-semibold text-white bg-primary hover:bg-primary/90 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="bi bi-person-plus mr-1"></i> Assign to Group
                    </button>
                </div>
            </div>

            {{-- Groups Tab --}}
            <div id="tab-groups" class="manage-tab-content hidden">
                <div class="mb-4">
                    <p class="text-sm text-gray-600 dark:text-white/60">All group conversations. Click to manage members.</p>
                </div>
                <div id="groups-list-container" class="space-y-2">
                    <div class="flex items-center justify-center py-8">
                        <i class="bi bi-arrow-repeat animate-spin text-2xl text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-chat-modal="manage-members"]');
    const tabs = document.querySelectorAll('.manage-tab');
    const tabContents = document.querySelectorAll('.manage-tab-content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    let selectedCandidateId = null;

    // Tab switching
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const tabName = tab.dataset.tab;

            tabs.forEach(t => {
                t.classList.remove('active', 'border-primary', 'text-primary');
                t.classList.add('border-transparent', 'text-gray-500', 'dark:text-white/60');
            });
            tab.classList.add('active', 'border-primary', 'text-primary');
            tab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-white/60');

            tabContents.forEach(content => content.classList.add('hidden'));
            document.getElementById(`tab-${tabName}`)?.classList.remove('hidden');

            // Load data for tab
            if (tabName === 'waiting') loadWaitingList();
            if (tabName === 'groups') loadGroups();
            if (tabName === 'assign') loadGroupsDropdown();
        });
    });

    // Load waiting list
    async function loadWaitingList() {
        const container = document.getElementById('waiting-list-container');
        try {
            const response = await fetch('/chats/manage/waiting-list', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            const list = data.waiting_list?.data || [];

            if (list.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="bi bi-check-circle text-4xl text-green-500 mb-2"></i>
                        <p class="text-sm text-gray-500 dark:text-white/50">No candidates waiting</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = list.map(item => `
                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5">
                    <div class="flex items-center gap-3">
                        <img src="${item.user?.profile_photo_path ? '/storage/' + item.user.profile_photo_path : '/user.png'}" class="size-10 rounded-full" alt="">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">${item.user?.name || 'Unknown'}</p>
                            <p class="text-xs text-gray-500 dark:text-white/50">${item.user?.email || ''}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400">${new Date(item.created_at).toLocaleDateString()}</span>
                        <button type="button" class="quick-assign-btn px-3 py-1.5 rounded-lg bg-primary text-white text-xs font-medium hover:bg-primary/90"
                            data-user-id="${item.user_id}">
                            Assign
                        </button>
                    </div>
                </div>
            `).join('');

            // Quick assign buttons
            container.querySelectorAll('.quick-assign-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedCandidateId = btn.dataset.userId;
                    tabs[1].click(); // Switch to assign tab
                });
            });
        } catch (e) {
            container.innerHTML = '<p class="text-sm text-red-500">Failed to load waiting list</p>';
        }
    }

    // Load groups dropdown
    async function loadGroupsDropdown() {
        const select = document.getElementById('assign-group-select');
        try {
            const response = await fetch('/chats/manage/groups', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const groups = await response.json();

            select.innerHTML = '<option value="">Select a group...</option>' +
                groups.map(g => `<option value="${g.id}">${g.name} (${g.member_count} members)</option>`).join('');
        } catch (e) {
            console.error('Failed to load groups:', e);
        }
    }

    // Load all groups
    async function loadGroups() {
        const container = document.getElementById('groups-list-container');
        try {
            const response = await fetch('/chats/manage/groups', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const groups = await response.json();

            if (groups.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="bi bi-collection text-4xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-500 dark:text-white/50">No groups yet</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = groups.map(g => `
                <a href="/chats/v2?conversation=${g.id}" class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 hover:border-primary/50 transition">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                            ${g.name.substring(0, 2).toUpperCase()}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">${g.name}</p>
                            <p class="text-xs text-gray-500 dark:text-white/50">${g.member_count} members</p>
                        </div>
                    </div>
                    <i class="bi bi-chevron-right text-gray-400"></i>
                </a>
            `).join('');
        } catch (e) {
            container.innerHTML = '<p class="text-sm text-red-500">Failed to load groups</p>';
        }
    }

    // Candidate search
    let searchTimeout;
    const searchInput = document.getElementById('candidate-search');
    const searchResults = document.getElementById('candidate-search-results');

    searchInput?.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(async () => {
            const q = searchInput.value.trim();
            if (q.length < 2) {
                searchResults.innerHTML = '';
                return;
            }

            try {
                const response = await fetch(`/chats/manage/candidates?q=${encodeURIComponent(q)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const candidates = await response.json();

                searchResults.innerHTML = candidates.map(c => `
                    <button type="button" class="candidate-result w-full flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/10 text-left"
                        data-id="${c.id}" data-name="${c.name}" data-email="${c.email}" data-avatar="${c.avatar}">
                        <img src="${c.avatar}" class="size-8 rounded-full" alt="">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">${c.name}</p>
                            <p class="text-xs text-gray-500 dark:text-white/50">${c.email}</p>
                        </div>
                    </button>
                `).join('');

                searchResults.querySelectorAll('.candidate-result').forEach(btn => {
                    btn.addEventListener('click', () => selectCandidate(btn.dataset));
                });
            } catch (e) {
                console.error('Search failed:', e);
            }
        }, 300);
    });

    function selectCandidate(data) {
        selectedCandidateId = data.id;
        document.getElementById('selected-candidate').classList.remove('hidden');
        document.getElementById('selected-candidate-avatar').src = data.avatar;
        document.getElementById('selected-candidate-name').textContent = data.name;
        document.getElementById('selected-candidate-email').textContent = data.email;
        searchResults.innerHTML = '';
        searchInput.value = '';
        updateAssignButton();
    }

    document.getElementById('clear-selected-candidate')?.addEventListener('click', () => {
        selectedCandidateId = null;
        document.getElementById('selected-candidate').classList.add('hidden');
        updateAssignButton();
    });

    document.getElementById('assign-group-select')?.addEventListener('change', updateAssignButton);

    function updateAssignButton() {
        const btn = document.getElementById('assign-candidate-btn');
        const groupId = document.getElementById('assign-group-select')?.value;
        btn.disabled = !selectedCandidateId || !groupId;
    }

    // Assign candidate
    document.getElementById('assign-candidate-btn')?.addEventListener('click', async () => {
        const groupId = document.getElementById('assign-group-select')?.value;
        if (!selectedCandidateId || !groupId) return;

        try {
            const response = await fetch('/chats/manage/assign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    user_id: selectedCandidateId,
                    conversation_id: groupId,
                }),
            });

            if (response.ok) {
                window.Swal?.fire({
                    icon: 'success',
                    title: 'Assigned!',
                    text: 'Applicant has been assigned to the group.',
                    timer: 1500,
                    showConfirmButton: false,
                });
                selectedCandidateId = null;
                document.getElementById('selected-candidate').classList.add('hidden');
                document.getElementById('assign-group-select').value = '';
                updateAssignButton();
            } else {
                const data = await response.json();
                throw new Error(data.message || 'Failed to assign');
            }
        } catch (e) {
            window.Swal?.fire({
                icon: 'error',
                title: 'Error',
                text: e.message,
            });
        }
    });

    // Load data when modal opens
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class' && modal?.classList.contains('flex')) {
                loadWaitingList();
            }
        });
    });
    if (modal) observer.observe(modal, { attributes: true });
});
</script>
