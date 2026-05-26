{{-- Mute User Modal --}}
<div id="mute-user-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative w-full max-w-sm mx-4 rounded-2xl bg-white dark:bg-sidebar-dark shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-white/10 px-5 py-4">
            <div class="flex items-center gap-2">
                <i class="bi bi-mic-mute-fill text-xl text-amber-500"></i>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Mute <span id="mute-user-name"></span></h2>
            </div>
            <button type="button" id="mute-modal-close" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-white/60 dark:hover:bg-white/10 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <div class="px-5 py-4 space-y-2">
            <p class="text-sm text-gray-500 dark:text-white/50 mb-3">Select mute duration:</p>
            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-white/10 cursor-pointer hover:border-amber-300 dark:hover:border-amber-500/50 transition">
                <input type="radio" name="mute_duration" value="1" class="text-amber-500 focus:ring-amber-500">
                <span class="text-sm text-gray-700 dark:text-white/80">1 Hour</span>
            </label>
            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-white/10 cursor-pointer hover:border-amber-300 dark:hover:border-amber-500/50 transition">
                <input type="radio" name="mute_duration" value="24" class="text-amber-500 focus:ring-amber-500">
                <span class="text-sm text-gray-700 dark:text-white/80">24 Hours</span>
            </label>
            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-white/10 cursor-pointer hover:border-amber-300 dark:hover:border-amber-500/50 transition">
                <input type="radio" name="mute_duration" value="168" class="text-amber-500 focus:ring-amber-500">
                <span class="text-sm text-gray-700 dark:text-white/80">7 Days</span>
            </label>
            <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-white/10 cursor-pointer hover:border-amber-300 dark:hover:border-amber-500/50 transition">
                <input type="radio" name="mute_duration" value="0" class="text-amber-500 focus:ring-amber-500" checked>
                <span class="text-sm text-gray-700 dark:text-white/80">Until manually unmuted</span>
            </label>
        </div>
        <div class="flex items-center justify-end gap-2 border-t border-gray-200 dark:border-white/10 px-5 py-3">
            <button type="button" id="mute-modal-cancel" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-700 dark:text-white/70 hover:bg-gray-100 dark:hover:bg-white/10 transition">Cancel</button>
            <button type="button" id="mute-modal-confirm" class="px-4 py-2 rounded-xl text-sm font-medium text-white bg-amber-500 hover:bg-amber-600 transition">
                <i class="bi bi-mic-mute mr-1"></i>Mute User
            </button>
        </div>
    </div>
</div>

<script>
window.muteUserModal = {
    modal: null,
    resolvePromise: null,
    userId: null,
    conversationId: null,
    
    init() {
        this.modal = document.getElementById('mute-user-modal');
        document.getElementById('mute-modal-close')?.addEventListener('click', () => this.close(null));
        document.getElementById('mute-modal-cancel')?.addEventListener('click', () => this.close(null));
        document.getElementById('mute-modal-confirm')?.addEventListener('click', () => this.confirm());
        this.modal?.addEventListener('click', (e) => { if (e.target === this.modal) this.close(null); });
    },
    
    show(userName, userId, conversationId) {
        return new Promise((resolve) => {
            this.resolvePromise = resolve;
            this.userId = userId;
            this.conversationId = conversationId;
            document.getElementById('mute-user-name').textContent = userName;
            this.modal.querySelectorAll('input[name="mute_duration"]').forEach(r => r.checked = r.value === '0');
            this.modal.classList.remove('hidden');
            this.modal.classList.add('flex');
        });
    },
    
    confirm() {
        const duration = this.modal.querySelector('input[name="mute_duration"]:checked')?.value;
        this.close({ confirmed: true, duration: parseInt(duration) || 0, userId: this.userId, conversationId: this.conversationId });
    },
    
    close(result) {
        this.modal.classList.add('hidden');
        this.modal.classList.remove('flex');
        if (this.resolvePromise) { this.resolvePromise(result); this.resolvePromise = null; }
    }
};
document.addEventListener('DOMContentLoaded', () => window.muteUserModal.init());
</script>
