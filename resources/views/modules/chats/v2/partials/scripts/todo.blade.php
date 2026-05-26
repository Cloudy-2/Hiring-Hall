{{-- Todo Modal Script --}}
<script>
    const initTodoModal = () => {
        const STORAGE_KEY = 'chat_todos';
        let todos = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
        let currentFilter = 'all';

        const todoList = document.getElementById('todo-list');
        const todoForm = document.getElementById('add-todo-form');
        const todoCount = document.getElementById('todo-count');
        const clearCompletedBtn = document.getElementById('clear-completed-btn');
        const emptyState = todoList?.querySelector('.todo-empty-state');

        const saveTodos = () => localStorage.setItem(STORAGE_KEY, JSON.stringify(todos));

        const getPriorityColor = (priority) => {
            const colors = {
                low: 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                medium: 'bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-300',
                high: 'bg-orange-100 text-orange-600 dark:bg-orange-900/50 dark:text-orange-300',
                urgent: 'bg-red-100 text-red-600 dark:bg-red-900/50 dark:text-red-300'
            };
            return colors[priority] || colors.medium;
        };

        const formatDate = (dateStr) => {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);

            if (date.toDateString() === today.toDateString()) return 'Today';
            if (date.toDateString() === tomorrow.toDateString()) return 'Tomorrow';
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        };

        const renderTodos = () => {
            if (!todoList) return;

            const filtered = todos.filter(todo => currentFilter === 'all' || todo.status === currentFilter);

            if (filtered.length === 0) {
                if (emptyState) emptyState.classList.remove('hidden');
                todoList.querySelectorAll('.todo-item').forEach(el => el.remove());
            } else {
                if (emptyState) emptyState.classList.add('hidden');
                todoList.querySelectorAll('.todo-item').forEach(el => el.remove());

                filtered.forEach(todo => {
                    const isCompleted = todo.status === 'completed';
                    const div = document.createElement('div');
                    div.className = `todo-item flex items-start gap-3 p-3 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 hover:border-primary/30 ${isCompleted ? 'completed opacity-60' : ''}`;
                    div.dataset.todoId = todo.id;

                    div.innerHTML = `
                        <button type="button" class="todo-toggle mt-0.5 flex-shrink-0 size-5 rounded-full border-2 ${isCompleted ? 'border-green-500 bg-green-500' : 'border-slate-300 dark:border-white/30'} flex items-center justify-center transition hover:border-primary">
                            ${isCompleted ? '<i class="bi bi-check text-white text-sm"></i>' : ''}
                        </button>
                        <div class="flex-1 min-w-0">
                            <p class="todo-title text-sm font-medium text-slate-900 dark:text-white ${isCompleted ? 'line-through' : ''}">${todo.title}</p>
                            ${todo.description ? `<p class="text-xs text-slate-500 dark:text-white/50 mt-1">${todo.description}</p>` : ''}
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase ${getPriorityColor(todo.priority)}">${todo.priority}</span>
                                ${todo.due_date ? `<span class="inline-flex items-center gap-1 text-xs text-slate-500 dark:text-white/50"><i class="bi bi-clock text-sm"></i>${formatDate(todo.due_date)}</span>` : ''}
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <select class="todo-status-select text-xs rounded border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 text-slate-700 dark:text-white/80 px-2 py-1">
                                <option value="pending" ${todo.status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="in_progress" ${todo.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                                <option value="completed" ${todo.status === 'completed' ? 'selected' : ''}>Completed</option>
                            </select>
                            <button type="button" class="todo-delete p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                <i class="bi bi-trash text-lg"></i>
                            </button>
                        </div>
                    `;
                    todoList.appendChild(div);
                });
            }

            const pendingCount = todos.filter(t => t.status !== 'completed').length;
            const completedCount = todos.filter(t => t.status === 'completed').length;
            if (todoCount) todoCount.textContent = `${pendingCount} pending, ${completedCount} completed`;
            if (clearCompletedBtn) clearCompletedBtn.classList.toggle('hidden', completedCount === 0);
        };

        todoForm?.addEventListener('submit', (e) => {
            e.preventDefault();
            const title = document.getElementById('todo-title')?.value.trim();
            if (!title) return;

            todos.unshift({
                id: Date.now().toString(),
                title,
                description: document.getElementById('todo-description')?.value.trim() || '',
                priority: document.getElementById('todo-priority')?.value || 'medium',
                status: 'pending',
                due_date: document.getElementById('todo-due-date')?.value || null,
                created_at: new Date().toISOString()
            });
            saveTodos();
            renderTodos();
            todoForm.reset();
        });

        document.querySelectorAll('[data-todo-filter]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('[data-todo-filter]').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentFilter = btn.dataset.todoFilter;
                renderTodos();
            });
        });

        todoList?.addEventListener('click', (e) => {
            const todoItem = e.target.closest('.todo-item');
            if (!todoItem) return;
            const todoId = todoItem.dataset.todoId;

            if (e.target.closest('.todo-toggle')) {
                const todo = todos.find(t => t.id === todoId);
                if (todo) {
                    todo.status = todo.status === 'completed' ? 'pending' : 'completed';
                    todo.completed_at = todo.status === 'completed' ? new Date().toISOString() : null;
                    saveTodos();
                    renderTodos();
                }
            }

            if (e.target.closest('.todo-delete')) {
                todos = todos.filter(t => t.id !== todoId);
                saveTodos();
                renderTodos();
            }
        });

        todoList?.addEventListener('change', (e) => {
            if (e.target.classList.contains('todo-status-select')) {
                const todoItem = e.target.closest('.todo-item');
                const todo = todos.find(t => t.id === todoItem?.dataset.todoId);
                if (todo) {
                    todo.status = e.target.value;
                    todo.completed_at = todo.status === 'completed' ? new Date().toISOString() : null;
                    saveTodos();
                    renderTodos();
                }
            }
        });

        clearCompletedBtn?.addEventListener('click', () => {
            todos = todos.filter(t => t.status !== 'completed');
            saveTodos();
            renderTodos();
        });

        renderTodos();
    };
</script>
