<x-app-layout active="Sticky Notes">
    <div class="grid grid-cols-12 gap-x-6">
        <div class="xxl:col-span-12 col-span-12">
            <div class="box border">
                <div class="box-body">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                        <div>
                            <h6 class="font-bold text-2xl text-gray-700 dark:text-white">
                                <strong>Sticky Notes</strong>
                            </h6>
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                Quick notes that sync with your header dropdown. Edit and organize below (max 2 notes).
                            </span>
                        </div>
                        {{-- Add note button hidden - max 2 notes only --}}
                    </div>
                    <div id="sticky-notes-page-list" class="grid grid-cols-2 gap-6 min-h-[200px]">
                        <div class="col-span-2 text-center text-textmuted text-sm py-8">Loading notes...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const csrfToken = '{{ csrf_token() }}';
            const stickyNoteColors = {
                yellow: 'bg-yellow-100 border-yellow-300 dark:bg-yellow-900/30 dark:border-yellow-600',
                blue: 'bg-blue-100 border-blue-300 dark:bg-blue-900/30 dark:border-blue-600',
                green: 'bg-green-100 border-green-300 dark:bg-green-900/30 dark:border-green-600',
                pink: 'bg-pink-100 border-pink-300 dark:bg-pink-900/30 dark:border-pink-600',
                purple: 'bg-purple-100 border-purple-300 dark:bg-purple-900/30 dark:border-purple-600'
            };

            let stickyNotes = [];
            let container;

            function getCsrfToken() {
                return csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '';
            }

            async function loadStickyNotes() {
                if (!container) return;
                try {
                    const response = await fetch('/sticky-notes', {
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() }
                    });
                    const all = await response.json();
                    stickyNotes = all.slice(0, 2);
                    renderStickyNotes();
                } catch (e) {
                    container.innerHTML = '<div class="col-span-2 text-center text-textmuted text-sm py-8">Failed to load notes. Please refresh the page.</div>';
                }
            }

            function renderStickyNotes() {
                if (!container) return;
                if (stickyNotes.length === 0) {
                    container.innerHTML = '<div class="col-span-2 text-center text-textmuted text-sm py-8"><i class="bi bi-sticky text-2xl block mb-2"></i>No notes yet.</div>';
                    return;
                }
                const notesToShow = stickyNotes.slice(0, 2);
                container.innerHTML = notesToShow.map(note => {
                    const colorClass = stickyNoteColors[note.color] || stickyNoteColors.yellow;
                    const escaped = escapeHtml(note.content);
                    return `<div class="sticky-note p-5 rounded-xl border-2 ${colorClass}" data-id="${note.id}">
                        <div class="flex items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <textarea class="sticky-note-textarea w-full bg-transparent border-0 text-base resize-y focus:outline-none text-gray-800 dark:text-gray-200 min-h-[280px]" rows="12" data-id="${note.id}">${escaped}</textarea>
                            </div>
                            <div class="flex flex-col gap-2 shrink-0">
                                <button type="button" class="sticky-note-color text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 p-1" data-id="${note.id}" title="Change color"><i class="bi bi-palette"></i></button>
                                <button type="button" class="sticky-note-delete text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-1" data-id="${note.id}" title="Delete"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    </div>`;
                }).join('');

                const saveNoteContent = debounce(function (id, content) {
                    updateNote(id, content);
                }, 600);
                container.querySelectorAll('.sticky-note-textarea').forEach(textarea => {
                    const noteId = parseInt(textarea.dataset.id, 10);
                    textarea.addEventListener('change', function () {
                        updateNote(noteId, this.value);
                    });
                    textarea.addEventListener('input', function () {
                        saveNoteContent(noteId, this.value);
                    });
                });
                container.querySelectorAll('.sticky-note-color').forEach(btn => {
                    btn.addEventListener('click', function () {
                        changeNoteColor(parseInt(this.dataset.id, 10));
                    });
                });
                container.querySelectorAll('.sticky-note-delete').forEach(btn => {
                    btn.addEventListener('click', function () {
                        deleteNote(parseInt(this.dataset.id, 10));
                    });
                });
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function debounce(fn, ms) {
                let timeout;
                return function (...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => fn.apply(this, args), ms);
                };
            }

            async function addNewNote() {
                const token = getCsrfToken();
                if (!token) {
                    console.error('CSRF token missing');
                    return;
                }
                try {
                    const response = await fetch('/sticky-notes', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ content: 'New note...', color: 'yellow' }),
                        credentials: 'same-origin'
                    });
                    const data = await response.json();
                    if (!response.ok) {
                        const msg = data.message || data.errors?.content?.[0] || `Request failed (${response.status})`;
                        console.error('Add note failed:', msg);
                        if (typeof alert !== 'undefined') alert('Could not add note: ' + msg);
                        return;
                    }
                    stickyNotes.unshift(data);
                    renderStickyNotes();
                } catch (e) {
                    console.error('Failed to add note:', e);
                    if (typeof alert !== 'undefined') alert('Could not add note. Check the console.');
                }
            }

            async function updateNote(id, content) {
                try {
                    await fetch(`/sticky-notes/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken()
                        },
                        body: JSON.stringify({ content })
                    });
                    const note = stickyNotes.find(n => n.id === id);
                    if (note) note.content = content;
                } catch (e) {
                    console.error('Failed to update note:', e);
                }
            }

            async function changeNoteColor(id) {
                const colors = Object.keys(stickyNoteColors);
                const note = stickyNotes.find(n => n.id === id);
                if (!note) return;
                const currentIndex = colors.indexOf(note.color);
                const newColor = colors[(currentIndex + 1) % colors.length];
                try {
                    await fetch(`/sticky-notes/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken()
                        },
                        body: JSON.stringify({ color: newColor })
                    });
                    note.color = newColor;
                    renderStickyNotes();
                } catch (e) {
                    console.error('Failed to change color:', e);
                }
            }

            async function deleteNote(id) {
                const noteId = typeof id === 'number' ? id : parseInt(id, 10);
                try {
                    const response = await fetch(`/sticky-notes/${noteId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken()
                        },
                        credentials: 'same-origin'
                    });
                    if (!response.ok) {
                        const data = await response.json().catch(() => ({}));
                        const msg = data.error || data.message || `Delete failed (${response.status})`;
                        if (typeof alert !== 'undefined') alert('Could not delete note: ' + msg);
                        return;
                    }
                    stickyNotes = stickyNotes.filter(n => n.id != noteId);
                    renderStickyNotes();
                } catch (e) {
                    console.error('Failed to delete note:', e);
                    if (typeof alert !== 'undefined') alert('Could not delete note. Check the console.');
                }
            }

            function init() {
                document.documentElement.setAttribute('loader', 'disable');
                container = document.getElementById('sticky-notes-page-list');
                loadStickyNotes();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
</x-app-layout>
