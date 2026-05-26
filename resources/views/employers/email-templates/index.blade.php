<x-app-layout>

    <x-slot name="pageTitle">Email Templates</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Email Templates</x-slot>

    @include('employers.partials.employer-styles')

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Page Hero ═══ --}}
        <div class="col-span-12" id="wt-hero">
            <div class="cd-page-hero">
                <div>
                    <h1 class="cd-page-hero-title"><i class="ri-mail-line me-2"></i>Email Templates</h1>
                    <p class="cd-page-hero-sub">Create reusable email templates for applicant communication</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('employer.email-templates.create') }}" class="cd-hero-btn cd-hero-btn-primary"><i class="ri-add-line"></i> Create Template</a>
                    <a href="{{ route('employer.dashboard') }}" class="cd-hero-btn cd-hero-btn-ghost"><i class="ri-dashboard-line"></i> Dashboard</a>
                </div>
            </div>
        </div>

        {{-- ═══ Templates List ═══ --}}
        <div class="col-span-12" id="wt-templates-list">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-mail-settings-fill"></i> Your Email Templates</span>
                    <span class="text-xs text-gray-400">{{ $templates->total() }} total</span>
                </div>

                @if (session('status'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (window.Swal) Swal.fire({ icon: 'success', title: 'Success', text: @json(session('status')), timer: 2500, showConfirmButton: false });
                        });
                    </script>
                @endif

                @if($templates->isEmpty())
                    <div class="cd-empty">
                        <i class="ri-mail-line"></i>
                        <p>You haven't created any email templates yet.</p>
                        <p class="text-sm text-gray-500 mt-2">Templates with merge fields help you quickly send personalized emails to applicants.</p>
                        <a href="{{ route('employer.email-templates.create') }}" class="cd-btn cd-btn-primary mt-4">Create Your First Template</a>
                    </div>
                @else
                    <div class="grid grid-cols-12 gap-4">
                        @foreach($templates as $template)
                            <div class="xl:col-span-4 md:col-span-6 col-span-12">
                                <div class="flex h-full flex-col rounded-xl border border-gray-200 p-5 transition-shadow hover:shadow-md dark:border-gray-700 dark:bg-gray-800">

                                    {{-- Template name + icon --}}
                                    <div class="mb-3 flex items-center gap-3">
                                        @php
                                            $typeColors = [
                                                'rejection' => '#dc2626',
                                                'interview_invite' => '#16a34a',
                                                'offer' => '#2563eb',
                                                'follow_up' => '#ca8a04',
                                                'custom' => '#6b7280',
                                            ];
                                            $typeIcons = [
                                                'rejection' => 'ri-close-circle-line',
                                                'interview_invite' => 'ri-calendar-check-line',
                                                'offer' => 'ri-gift-line',
                                                'follow_up' => 'ri-send-plane-line',
                                                'custom' => 'ri-mail-line',
                                            ];
                                            $bgColor = $typeColors[$template->type] ?? '#6b7280';
                                            $icon = $typeIcons[$template->type] ?? 'ri-mail-line';
                                        @endphp
                                        <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg text-white" style="background:{{ $bgColor }}">
                                            <i class="{{ $icon }} text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="truncate text-sm font-bold dark:text-white">{{ $template->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $template->getTypeLabel() }}</div>
                                        </div>
                                    </div>

                                    {{-- Subject preview --}}
                                    <div class="mb-2 text-xs text-gray-700 dark:text-gray-300">
                                        <strong>Subject:</strong> {{ Str::limit($template->subject, 50) }}
                                    </div>

                                    {{-- Details --}}
                                    <div class="mb-3 flex flex-col gap-1 text-xs text-gray-500 dark:text-gray-400">
                                        @if($template->company)
                                            <span><i class="ri-building-2-line me-1"></i>{{ $template->company->name }}</span>
                                        @else
                                            <span><i class="ri-user-line me-1"></i>Personal Template</span>
                                        @endif
                                        <span><i class="ri-time-line me-1"></i>Updated {{ $template->updated_at->diffForHumans() }}</span>
                                    </div>

                                    {{-- Default badge --}}
                                    <div class="mb-3">
                                        @if($template->is_default)
                                            <span class="cd-status-pill cd-status-success"><i class="ri-star-fill me-1"></i>Default</span>
                                        @endif
                                    </div>

                                    {{-- Actions --}}
                                    <div class="mt-auto flex gap-1.5 border-t border-gray-100 pt-3 dark:border-gray-700">
                                        <button type="button" class="cd-btn cd-btn-secondary cd-btn-sm template-preview-btn" data-template-id="{{ $template->id }}"><i class="ri-eye-line me-1"></i>Preview</button>
                                        <a href="{{ route('employer.email-templates.edit', $template) }}" class="cd-btn cd-btn-outline cd-btn-sm"><i class="ri-edit-line me-1"></i>Edit</a>
                                        <button type="button" class="cd-btn cd-btn-danger cd-btn-sm template-delete-btn" data-template-id="{{ $template->id }}" data-template-name="{{ $template->name }}" aria-label="Delete email template {{ $template->name }}"><i class="ri-delete-bin-line me-1"></i>Delete</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="cd-pagination mt-4">{{ $templates->links() }}</div>
                @endif
            </div>
        </div>

    </div>

    {{-- Preview Modal --}}
    <div id="preview-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-bold text-lg dark:text-white">Email Preview</h3>
                <button type="button" id="close-preview" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cd-focus-ring" aria-label="Close preview modal">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            <div class="max-h-[calc(80vh-60px)] overflow-y-auto p-4">
                <div class="mb-4">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Subject</label>
                    <div id="preview-subject" class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm dark:text-white"></div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Body</label>
                    <div id="preview-body" class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm whitespace-pre-wrap dark:text-white"></div>
                </div>
                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-xs text-blue-700 dark:text-blue-300">
                    <i class="ri-information-line me-1"></i>
                    This preview uses sample data. Actual emails will use real applicant and job information.
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const previewModal = document.getElementById('preview-modal');
        const closePreview = document.getElementById('close-preview');
        const previewSubject = document.getElementById('preview-subject');
        const previewBody = document.getElementById('preview-body');
        let focusReturnEl = null;

        function rememberFocus(el) {
            focusReturnEl = el instanceof HTMLElement ? el : document.activeElement;
        }

        function restoreFocus() {
            if (focusReturnEl && typeof focusReturnEl.focus === 'function') {
                setTimeout(function () { focusReturnEl.focus(); }, 0);
            }
        }

        document.querySelectorAll('.template-preview-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                rememberFocus(btn);
                const templateId = btn.dataset.templateId;

                fetch('/employer/email-templates/' + templateId + '/preview', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    previewSubject.textContent = data.subject;
                    previewBody.textContent = data.body;
                    previewModal.classList.remove('hidden');
                    previewModal.classList.add('flex');
                })
                .catch(() => {
                    Swal.fire({ icon: 'error', title: 'Failed to load preview' });
                    restoreFocus();
                });
            });
        });

        closePreview.addEventListener('click', function() {
            previewModal.classList.remove('flex');
            previewModal.classList.add('hidden');
            restoreFocus();
        });

        previewModal.addEventListener('click', function(e) {
            if (e.target === previewModal) {
                previewModal.classList.remove('flex');
                previewModal.classList.add('hidden');
                restoreFocus();
            }
        });

        document.querySelectorAll('.template-delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                rememberFocus(btn);
                const templateId = btn.dataset.templateId;
                const templateName = btn.dataset.templateName;

                Swal.fire({
                    icon: 'warning',
                    title: 'Delete Template?',
                    text: 'Are you sure you want to delete "' + templateName + '"? This cannot be undone.',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#dc2626'
                }).then(function(result) {
                    if (!result.isConfirmed) {
                        restoreFocus();
                        return;
                    }

                    fetch('/employer/email-templates/' + templateId, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => window.location.reload());
                    })
                    .catch(() => {
                        Swal.fire({ icon: 'error', title: 'Delete failed' });
                        restoreFocus();
                    });
                });
            });
        });
    });
    </script>

</x-app-layout>
