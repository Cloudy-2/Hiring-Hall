{{-- Report Message Modal --}}
<div data-chat-modal="report" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-10">
    <div class="w-full max-w-md rounded-2xl bg-white dark:bg-sidebar-dark p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-[11px] uppercase tracking-[0.3em] text-red-400">Report</p>
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white">Message</h3>
            </div>
            <button type="button" data-chat-modal-close="report"
                class="rounded-full p-2 text-slate-400 dark:text-white/60 transition hover:bg-slate-100 dark:hover:bg-white/10">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <input type="hidden" id="report-message-id" value="">

        {{-- Message Preview --}}
        <div class="rounded-lg border border-red-200 dark:border-red-900/30 bg-red-50 dark:bg-red-900/10 p-3 mb-4">
            <p class="text-xs text-red-500 dark:text-red-400 mb-1">Message being reported:</p>
            <p id="report-message-body" class="text-sm text-slate-700 dark:text-white/80 line-clamp-3"></p>
            <p id="report-message-user" class="text-xs text-slate-500 dark:text-white/50 mt-1"></p>
        </div>

        {{-- Report Reason --}}
        <div class="space-y-3 mb-4">
            <label class="text-sm font-medium text-slate-700 dark:text-white/80">Why are you reporting this message?</label>
            
            <div class="space-y-2">
                @foreach([
                    'spam' => ['Spam', 'Unwanted promotional content or repetitive messages'],
                    'harassment' => ['Harassment', 'Bullying, threats, or targeted abuse'],
                    'inappropriate' => ['Inappropriate Content', 'Offensive, explicit, or harmful content'],
                    'misinformation' => ['Misinformation', 'False or misleading information'],
                    'other' => ['Other', 'Something else not listed above'],
                ] as $value => [$label, $desc])
                    <label class="flex items-start gap-3 p-3 rounded-lg border border-slate-200 dark:border-white/10 cursor-pointer hover:bg-slate-50 dark:hover:bg-white/5 transition has-[:checked]:border-red-400 has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/10">
                        <input type="radio" name="report_reason" value="{{ $value }}"
                            class="mt-0.5 text-red-600 focus:ring-red-500 border-slate-300 dark:border-white/30">
                        <div>
                            <p class="text-sm font-medium text-slate-700 dark:text-white/80">{{ $label }}</p>
                            <p class="text-xs text-slate-500 dark:text-white/50">{{ $desc }}</p>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Additional Details --}}
        <div class="mb-4" id="report-details-section" style="display: none;">
            <label class="text-sm font-medium text-slate-700 dark:text-white/80 mb-2 block">Additional details (optional)</label>
            <textarea id="report-details" rows="3" placeholder="Provide more context..."
                class="w-full rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 px-3 py-2 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-white/40 focus:border-red-500 focus:ring-1 focus:ring-red-500 resize-none"></textarea>
        </div>

        {{-- Warning --}}
        <div class="rounded-lg bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-900/30 p-3 mb-4">
            <p class="text-xs text-amber-700 dark:text-amber-400">
                <span class="material-symbols-outlined text-sm align-middle mr-1">info</span>
                Reports are reviewed by moderators. False reports may result in action against your account.
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-2 border-t border-slate-200 dark:border-white/10">
            <button type="button" data-chat-modal-close="report"
                class="rounded-lg border border-slate-200 dark:border-white/10 px-4 py-2 text-sm font-semibold text-slate-600 dark:text-white/70 transition hover:border-slate-300 dark:hover:border-white/30">
                Cancel
            </button>
            <button type="button" id="report-submit-btn" disabled
                class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="material-symbols-outlined text-base">flag</span>
                <span>Submit Report</span>
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.querySelector('[data-chat-modal="report"]');
    const messageIdInput = document.getElementById('report-message-id');
    const detailsSection = document.getElementById('report-details-section');
    const detailsInput = document.getElementById('report-details');
    const submitBtn = document.getElementById('report-submit-btn');
    const reasonInputs = modal?.querySelectorAll('input[name="report_reason"]');

    if (!modal) return;

    // Show details for "other" reason
    reasonInputs?.forEach(input => {
        input.addEventListener('change', () => {
            submitBtn.disabled = false;
            detailsSection.style.display = input.value === 'other' ? '' : 'none';
        });
    });

    // Submit report
    submitBtn?.addEventListener('click', async () => {
        const messageId = messageIdInput.value;
        const selectedReason = modal.querySelector('input[name="report_reason"]:checked');
        
        if (!messageId || !selectedReason) return;

        let reason = selectedReason.value;
        if (reason === 'other' && detailsInput.value.trim()) {
            reason = detailsInput.value.trim();
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="material-symbols-outlined text-base animate-spin">progress_activity</span><span>Submitting...</span>';

        try {
            const response = await fetch(`/chats/messages/${messageId}/report`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ reason }),
            });

            const data = await response.json();

            if (response.ok) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                
                window.Swal?.fire({
                    icon: 'success',
                    title: 'Report submitted',
                    text: 'Thank you for helping keep our community safe.',
                    timer: 3000,
                    showConfirmButton: false,
                }) ?? alert('Report submitted!');
            } else {
                throw new Error(data.message || 'Report failed');
            }
        } catch (error) {
            console.error('Report error:', error);
            window.Swal?.fire({
                icon: 'error',
                title: 'Report failed',
                text: error.message,
            }) ?? alert(error.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span class="material-symbols-outlined text-base">flag</span><span>Submit Report</span>';
        }
    });

    // Reset on close
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class' && modal.classList.contains('hidden')) {
                reasonInputs?.forEach(input => input.checked = false);
                detailsInput.value = '';
                detailsSection.style.display = 'none';
                submitBtn.disabled = true;
            }
        });
    });
    observer.observe(modal, { attributes: true });
});
</script>
