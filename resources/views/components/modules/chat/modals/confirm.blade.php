{{-- Generic Confirm/Alert Modal for Chat V2 --}}
<div id="chat-confirm-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-sm mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-4">
            <div class="flex items-center gap-2">
                <span id="chat-confirm-icon" class="text-xl"></span>
                <h2 id="chat-confirm-title" class="text-lg font-bold text-gray-900 dark:text-white">Confirm</h2>
            </div>
            <button type="button" id="chat-confirm-close" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-5 py-4">
            <p id="chat-confirm-message" class="text-sm text-gray-600 dark:text-white/70"></p>
            <div id="chat-confirm-input-container" class="hidden mt-4"></div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-2 border-t border-gray-200 dark:border-white/10 px-5 py-3">
            <button type="button" id="chat-confirm-cancel" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-700 dark:text-white/70 hover:bg-gray-100 dark:hover:bg-white/10 transition">
                Cancel
            </button>
            <button type="button" id="chat-confirm-ok" class="px-4 py-2 rounded-xl text-sm font-medium text-white bg-primary hover:bg-primary/90 transition">
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
window.chatModal = {
    modal: null,
    resolvePromise: null,
    
    init() {
        this.modal = document.getElementById('chat-confirm-modal');
        if (!this.modal) return;
        
        document.getElementById('chat-confirm-close')?.addEventListener('click', () => this.close(null));
        document.getElementById('chat-confirm-cancel')?.addEventListener('click', () => this.close(null));
        document.getElementById('chat-confirm-ok')?.addEventListener('click', () => this.handleConfirm());
        
        this.modal?.addEventListener('click', (e) => {
            if (e.target === this.modal) this.close(null);
        });
    },
    
    show(options = {}) {
        if (!this.modal) {
            console.error('Chat confirm modal not initialized');
            return Promise.resolve(null);
        }
        
        return new Promise((resolve) => {
            this.resolvePromise = resolve;
            
            const iconEl = document.getElementById('chat-confirm-icon');
            const titleEl = document.getElementById('chat-confirm-title');
            const messageEl = document.getElementById('chat-confirm-message');
            const inputContainer = document.getElementById('chat-confirm-input-container');
            const cancelBtn = document.getElementById('chat-confirm-cancel');
            const okBtn = document.getElementById('chat-confirm-ok');
            
            const icons = {
                warning: '<i class="bi bi-exclamation-triangle-fill text-amber-500"></i>',
                error: '<i class="bi bi-x-circle-fill text-red-500"></i>',
                success: '<i class="bi bi-check-circle-fill text-green-500"></i>',
                info: '<i class="bi bi-info-circle-fill text-blue-500"></i>',
                question: '<i class="bi bi-question-circle-fill text-primary"></i>'
            };
            
            iconEl.innerHTML = icons[options.icon] || icons.question;
            titleEl.textContent = options.title || 'Confirm';
            messageEl.innerHTML = options.message || options.text || '';
            
            if (options.input) {
                inputContainer.classList.remove('hidden');
                inputContainer.innerHTML = options.inputHtml || `
                    <input type="${options.inputType || 'text'}" id="chat-confirm-input" 
                        class="w-full rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-white/40 focus:border-primary focus:ring-2 focus:ring-primary/20 transition"
                        placeholder="${options.inputPlaceholder || ''}"
                        value="${options.inputValue || ''}">
                `;
            } else {
                inputContainer.classList.add('hidden');
                inputContainer.innerHTML = '';
            }
            
            cancelBtn.textContent = options.cancelText || 'Cancel';
            okBtn.textContent = options.confirmText || 'Confirm';
            
            if (options.showCancelButton === false) {
                cancelBtn.classList.add('hidden');
            } else {
                cancelBtn.classList.remove('hidden');
            }
            
            if (options.confirmButtonColor === 'red' || options.type === 'danger') {
                okBtn.className = 'px-4 py-2 rounded-xl text-sm font-medium text-white bg-red-500 hover:bg-red-600 transition';
            } else if (options.confirmButtonColor === 'green' || options.type === 'success') {
                okBtn.className = 'px-4 py-2 rounded-xl text-sm font-medium text-white bg-green-500 hover:bg-green-600 transition';
            } else {
                okBtn.className = 'px-4 py-2 rounded-xl text-sm font-medium text-white bg-primary hover:bg-primary/90 transition';
            }
            
            this.modal.classList.remove('hidden');
            this.modal.classList.add('flex');
            
            const input = document.getElementById('chat-confirm-input');
            if (input) setTimeout(() => input.focus(), 100);
        });
    },
    
    handleConfirm() {
        const input = document.getElementById('chat-confirm-input');
        const value = input ? input.value : true;
        this.close({ confirmed: true, value });
    },
    
    close(result) {
        if (!this.modal) return;
        this.modal.classList.add('hidden');
        this.modal.classList.remove('flex');
        if (this.resolvePromise) {
            this.resolvePromise(result);
            this.resolvePromise = null;
        }
    },
    
    async alert(options) {
        return this.show({ ...options, showCancelButton: false, confirmText: 'OK' });
    }
};

document.addEventListener('DOMContentLoaded', () => window.chatModal.init());
</script>
