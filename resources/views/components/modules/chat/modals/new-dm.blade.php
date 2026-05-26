<div id="new-dm-trigger" data-chat-modal="new-dm" class="hidden"></div>

@php
    $currentUserId = auth()->id();
    $contactCollection = collect($contacts ?? [])
        ->filter(fn ($contact) => !empty($contact['user_id']) && $contact['user_id'] != $currentUserId)
        ->take(20);
@endphp

<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const currentUserId = '{{ auth()->id() }}';
    const contacts = @json($contactCollection->values());

    document.querySelectorAll('[data-open-chat-modal="new-dm"]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            e.stopPropagation();

            let selectedUser = null;
            let searchTimeout = null;

            const contactsHtml = contacts.length > 0 ? `
                <div id="swal-recent-contacts" class="mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Recent contacts</label>
                        <span class="text-xs text-gray-400">click to select</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto">
                        ${contacts.map(c => `
                            <button type="button" class="swal-contact-btn flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-left transition hover:border-indigo-400 hover:bg-indigo-50"
                                data-user-id="${c.user_id}" data-name="${c.name}" data-email="${c.email || ''}" data-avatar="${c.avatar}">
                                <img src="${c.avatar}" class="w-8 h-8 rounded-full" alt="${c.name}">
                                <span class="text-xs font-medium text-gray-700 truncate">${c.name}</span>
                            </button>
                        `).join('')}
                    </div>
                </div>
            ` : '';

            const { value: formValues } = await Swal.fire({
                title: '<i class="bi bi-chat-dots text-primary"></i> Start Direct Message',
                html: `
                    <div class="text-left space-y-4">
                        <p class="text-sm text-gray-500">Start a private conversation with someone.</p>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search users</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input id="swal-dm-search" class="swal2-input !m-0 !w-full !pl-10" placeholder="Search by name or email..." autocomplete="off">
                            </div>
                        </div>

                        <div id="swal-search-results" class="hidden max-h-48 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-2 space-y-1"></div>

                        <div id="swal-selected-user" class="hidden">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Selected</label>
                            <div class="flex items-center gap-3 rounded-lg border border-indigo-300 bg-indigo-50 p-3">
                                <img id="swal-selected-avatar" src="" class="w-10 h-10 rounded-full" alt="">
                                <div class="flex-1 min-w-0">
                                    <p id="swal-selected-name" class="text-sm font-medium text-gray-900 truncate"></p>
                                    <p id="swal-selected-email" class="text-xs text-gray-500 truncate"></p>
                                </div>
                                <button type="button" id="swal-clear-selection" class="rounded-full p-1.5 text-gray-400 hover:bg-gray-200 transition">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>

                        ${contactsHtml}
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-chat-dots mr-1"></i> Start Chat',
                confirmButtonColor: '#2b2bee',
                cancelButtonText: 'Cancel',
                customClass: { popup: 'rounded-2xl', htmlContainer: '!px-6' },
                didOpen: () => {
                    const popup = Swal.getPopup();
                    const searchInput = popup.querySelector('#swal-dm-search');
                    const searchResults = popup.querySelector('#swal-search-results');
                    const selectedDiv = popup.querySelector('#swal-selected-user');
                    const recentContacts = popup.querySelector('#swal-recent-contacts');
                    const confirmBtn = Swal.getConfirmButton();
                    
                    confirmBtn.disabled = true;
                    searchInput.focus();

                    const selectUser = (userId, name, email, avatar) => {
                        selectedUser = { id: userId, name, email, avatar };
                        popup.querySelector('#swal-selected-avatar').src = avatar;
                        popup.querySelector('#swal-selected-name').textContent = name;
                        popup.querySelector('#swal-selected-email').textContent = email || 'No email';
                        selectedDiv.classList.remove('hidden');
                        searchResults.classList.add('hidden');
                        if (recentContacts) recentContacts.classList.add('hidden');
                        searchInput.value = '';
                        confirmBtn.disabled = false;
                    };

                    const clearSelection = () => {
                        selectedUser = null;
                        selectedDiv.classList.add('hidden');
                        if (recentContacts) recentContacts.classList.remove('hidden');
                        confirmBtn.disabled = true;
                    };

                    // Search functionality
                    searchInput.addEventListener('input', () => {
                        clearTimeout(searchTimeout);
                        const query = searchInput.value.trim();

                        if (query.length < 2) {
                            searchResults.classList.add('hidden');
                            return;
                        }

                        searchTimeout = setTimeout(async () => {
                            try {
                                const response = await fetch(`/chats/conversations/search?q=${encodeURIComponent(query)}`, {
                                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                                });
                                const allUsers = await response.json();
                                const users = allUsers.filter(u => String(u.id) !== String(currentUserId));

                                if (users.length === 0) {
                                    searchResults.innerHTML = '<p class="text-xs text-gray-500 p-2">No users found</p>';
                                } else {
                                    searchResults.innerHTML = users.map(u => `
                                        <button type="button" class="swal-search-result w-full flex items-center gap-3 rounded-lg p-2 text-left transition hover:bg-indigo-50"
                                            data-user-id="${u.id}" data-name="${u.name}" data-email="${u.email || ''}" data-avatar="${u.avatar}">
                                            <img src="${u.avatar}" class="w-8 h-8 rounded-full" alt="${u.name}">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">${u.name}</p>
                                                <p class="text-xs text-gray-500 truncate">${u.email || ''}</p>
                                            </div>
                                        </button>
                                    `).join('');
                                }
                                searchResults.classList.remove('hidden');
                            } catch (error) {
                                searchResults.innerHTML = '<p class="text-xs text-red-500 p-2">Search failed</p>';
                                searchResults.classList.remove('hidden');
                            }
                        }, 300);
                    });

                    searchResults.addEventListener('click', (e) => {
                        const btn = e.target.closest('.swal-search-result');
                        if (btn) selectUser(btn.dataset.userId, btn.dataset.name, btn.dataset.email, btn.dataset.avatar);
                    });

                    if (recentContacts) {
                        recentContacts.addEventListener('click', (e) => {
                            const btn = e.target.closest('.swal-contact-btn');
                            if (btn) selectUser(btn.dataset.userId, btn.dataset.name, btn.dataset.email, btn.dataset.avatar);
                        });
                    }

                    popup.querySelector('#swal
                },
                preConfirm: () => {
                    if (!selectedUser) {
                        Swal.showValidationMessage('Please select a user to message');
                        return false;
                    }
                    return selectedUser;
                }
            });

            if (formValues) {
                Swal.fire({ title: 'Starting chat...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                try {
                    const formData = new FormData();
                    formData.append('_token', csrfToken);
                    formData.append('type', 'dm');
                    formData.append('user_id', formValues.id);

                    const response = await fetch('{{ route("conversations.store") }}', {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                        body: formData,
                    });

                    const data = await response.json();

                    if (!response.ok) throw new Error(data.message || 'Failed to create conversation');

                    Swal.close();
                    const conversationId = data.conversation_id || data.id;
                    if (conversationId) {
                        const url = new URL(window.location.href);
                        url.searchParams.set('conversation', conversationId);
                        window.location = url.toString();
                    } else {
                        window.location.reload();
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: error.message });
                }
            }
        });
    });
});
</script>
