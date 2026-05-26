@php
    $todos = $todos ?? collect();
@endphp

<div data-chat-modal="todo" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-10">
    <div class="w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl flex flex-col">
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-white/10">
            <div>
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">My Tasks</h3>
                <p class="text-xs text-slate-500 dark:text-white/40">Keep track of your to-dos and assignments.</p>
            </div>

        </div>

        {{-- Add Todo Form --}}
        <form id="add-todo-form" class="p-4 border-b border-slate-200 dark:border-white/10">
            <div class="flex gap-3 items-center">
                <input type="text" name="title" id="todo-title" required
                    placeholder="What needs to be done?"
                    class="flex-1 rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 px-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-white/40 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <select name="priority" id="todo-priority"
                    class="rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 px-3 py-2.5 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
            <div class="mt-3 flex flex-wrap gap-3 items-center">
                <input type="date" name="due_date" id="todo-due-date"
                    class="rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 px-3 py-2 text-sm text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <input type="text" name="description" id="todo-description"
                    placeholder="Add description (optional)"
                    class="flex-1 min-w-[200px] rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-white/40 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" />
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 flex-shrink-0">
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Task</span>
                </button>
            </div>
        </form>

        {{-- Filter Tabs --}}
        <div class="flex gap-1 px-4 pt-4 border-b border-slate-200 dark:border-white/10">
            <button type="button" data-todo-filter="all" class="todo-filter-btn active px-4 py-2 text-sm font-medium rounded-t-lg transition">
                All
            </button>
            <button type="button" data-todo-filter="pending" class="todo-filter-btn px-4 py-2 text-sm font-medium rounded-t-lg transition">
                Pending
            </button>
            <button type="button" data-todo-filter="in_progress" class="todo-filter-btn px-4 py-2 text-sm font-medium rounded-t-lg transition">
                In Progress
            </button>
            <button type="button" data-todo-filter="completed" class="todo-filter-btn px-4 py-2 text-sm font-medium rounded-t-lg transition">
                Completed
            </button>
        </div>

        {{-- Todo List --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-2" id="todo-list">
            <div class="todo-empty-state flex flex-col items-center justify-center py-12 text-center">
                <i class="bi bi-check2-circle text-5xl text-slate-300 dark:text-white/20 mb-3"></i>
                <p class="text-base font-semibold text-slate-700 dark:text-white">No tasks yet</p>
                <p class="text-sm text-slate-500 dark:text-white/50">Add your first task above to get started.</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between p-4 border-t border-slate-200 dark:border-white/10 text-xs text-slate-400 dark:text-white/40">
            <span id="todo-count">0 pending, 0 completed</span>
            <button type="button" id="clear-completed-btn"
                class="text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 transition hidden">
                Clear completed
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('add-todo-form');
    const todoList = document.getElementById('todo-list');
    const todoCount = document.getElementById('todo-count');
    const emptyState = todoList?.querySelector('.todo-empty-state');
    const filterBtns = document.querySelectorAll('.todo-filter-btn');
    
    let todos = [];
    let currentFilter = 'all';

    // Load todos on modal open
    const modal = document.querySelector('[data-chat-modal="todo"]');
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class' && modal?.classList.contains('flex')) {
                loadTodos();
            }
        });
    });
    if (modal) observer.observe(modal, { attributes: true });

    async function loadTodos() {
        try {
            const response = await fetch('/chats/todos', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (response.ok) {
                const data = await response.json();
                todos = data.todos || [];
                renderTodos();
            }
        } catch (e) { console.error('Load todos error:', e); }
    }

    function renderTodos() {
        const filtered = currentFilter === 'all' ? todos : todos.filter(t => t.status === currentFilter);
        
        if (filtered.length === 0) {
            emptyState?.classList.remove('hidden');
            todoList.querySelectorAll('.todo-item').forEach(el => el.remove());
        } else {
            emptyState?.classList.add('hidden');
            todoList.querySelectorAll('.todo-item').forEach(el => el.remove());
            filtered.forEach(todo => {
                const el = createTodoElement(todo);
                todoList.appendChild(el);
            });
        }

        const pending = todos.filter(t => t.status !== 'completed').length;
        const completed = todos.filter(t => t.status === 'completed').length;
        todoCount.textContent = `${pending} pending, ${completed} completed`;
    }

    function createTodoElement(todo) {
        const div = document.createElement('div');
        div.className = `todo-item flex items-center gap-3 p-3 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 ${todo.status === 'completed' ? 'completed' : ''}`;
        div.dataset.id = todo.id;
        
        const priorityColors = { low: 'bg-gray-400', medium: 'bg-blue-500', high: 'bg-orange-500', urgent: 'bg-red-500' };
        const statusIcons = { pending: 'bi-circle', in_progress: 'bi-circle-half', completed: 'bi-check-circle-fill text-green-500' };
        
        div.innerHTML = `
            <button type="button" class="todo-status-btn flex-shrink-0" data-id="${todo.id}">
                <i class="bi ${statusIcons[todo.status] || 'bi-circle'} text-xl ${todo.status === 'completed' ? '' : 'text-slate-400 dark:text-white/40 hover:text-indigo-600'}"></i>
            </button>
            <div class="flex-1 min-w-0">
                <p class="todo-title text-sm font-medium text-slate-800 dark:text-white truncate">${todo.title}</p>
                ${todo.description ? `<p class="text-xs text-slate-500 dark:text-white/50 truncate">${todo.description}</p>` : ''}
                ${todo.due_date ? `<p class="text-xs text-slate-400 dark:text-white/40 mt-1"><i class="bi bi-calendar3 mr-1"></i>${new Date(todo.due_date).toLocaleDateString()}</p>` : ''}
            </div>
            <span class="w-2 h-2 rounded-full ${priorityColors[todo.priority] || 'bg-blue-500'}" title="${todo.priority}"></span>
            <button type="button" class="todo-delete-btn text-slate-400 hover:text-red-500 transition" data-id="${todo.id}">
                <i class="bi bi-trash text-sm"></i>
            </button>
        `;
        return div;
    }

    // Add todo
    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        if (!data.title?.trim()) return;

        try {
            const response = await fetch('/chats/todos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });
            if (response.ok) {
                form.reset();
                loadTodos();
                // Dispatch event to update the main page widget
                window.dispatchEvent(new CustomEvent('todos-updated'));
            }
        } catch (e) { console.error('Add todo error:', e); }
    });

    // Filter buttons
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentFilter = btn.dataset.todoFilter;
            renderTodos();
        });
    });

    // Status toggle & delete
    todoList?.addEventListener('click', async (e) => {
        const statusBtn = e.target.closest('.todo-status-btn');
        const deleteBtn = e.target.closest('.todo-delete-btn');
        
        if (statusBtn) {
            const id = statusBtn.dataset.id;
            const todo = todos.find(t => t.id == id);
            if (!todo) return;
            
            const nextStatus = { pending: 'in_progress', in_progress: 'completed', completed: 'pending' };
            try {
                await fetch(`/chats/todos/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({ status: nextStatus[todo.status] }),
                });
                loadTodos();
                window.dispatchEvent(new CustomEvent('todos-updated'));
            } catch (e) { console.error(e); }
        }
        
        if (deleteBtn) {
            const id = deleteBtn.dataset.id;
            try {
                await fetch(`/chats/todos/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                });
                loadTodos();
                window.dispatchEvent(new CustomEvent('todos-updated'));
            } catch (e) { console.error(e); }
        }
    });
});
</script>

<style>
    .todo-filter-btn {
        color: rgb(100 116 139);
        background: transparent;
    }
    .dark .todo-filter-btn {
        color: rgba(255, 255, 255, 0.5);
    }
    .todo-filter-btn:hover {
        background: rgb(241 245 249);
        color: rgb(51 65 85);
    }
    .dark .todo-filter-btn:hover {
        background: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.8);
    }
    .todo-filter-btn.active {
        background: rgb(99 102 241 / 0.1);
        color: rgb(99 102 241);
        border-bottom: 2px solid rgb(99 102 241);
    }
    .dark .todo-filter-btn.active {
        background: rgba(99, 102, 241, 0.2);
        color: rgb(129 140 248);
    }
    .todo-item {
        transition: all 0.2s ease;
    }
    .todo-item.completed .todo-title {
        text-decoration: line-through;
        opacity: 0.6;
    }
</style>
