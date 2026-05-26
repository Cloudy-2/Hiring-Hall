<x-app-layout page-title="Job Moderation">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="active">Job Moderation</x-slot>

    <style>
        /* Dark Mode Overrides */
        [data-theme-mode="dark"] .box, .dark .box,
        [data-theme-mode="dark"] .bg-white, .dark .bg-white {
            background-color: rgba(255,255,255,0.02) !important;
            border-color: rgba(255,255,255,0.05) !important;
        }
        [data-theme-mode="dark"] .box-header, .dark .box-header {
            border-bottom-color: rgba(255,255,255,0.05) !important;
            background-color: rgba(255,255,255,0.01) !important;
        }
        [data-theme-mode="dark"] thead, .dark thead,
        [data-theme-mode="dark"] .bg-gray-50\/50, .dark .bg-gray-50\/50 {
            background-color: rgba(255,255,255,0.02) !important;
        }
        [data-theme-mode="dark"] td, .dark td,
        [data-theme-mode="dark"] th, .dark th {
            border-color: rgba(255,255,255,0.05) !important;
        }
        [data-theme-mode="dark"] .bg-orange-50, .dark .bg-orange-50 {
            background-color: rgba(249,115,22,0.1) !important;
        }
    </style>

    <x-modern-header chip="Job Moderation" title="Job Moderation" desc="Review and moderate job postings. Use the filters below to switch between all, pending, approved, rejected, or flagged jobs.">
    </x-modern-header>

    <div class="grid grid-cols-12 gap-6 mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="xl:col-span-12 col-span-12">

            @php $filterParams = array_filter(['status' => $status, 'flagged' => $flagged ? 1 : null, 'search' => $search ?? null]); @endphp
            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-4" role="tablist" aria-label="Job status filters">
                <a href="{{ route('moderator.jobs.index', array_merge($filterParams, ['status' => 'all', 'flagged' => null])) }}"
                   class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $status === 'all' && !$flagged ? 'border-l-4 border-l-primary' : '' }} hover:bg-primary/5 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-primary"
                   aria-current="{{ $status === 'all' && !$flagged ? 'true' : 'false' }}">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">All Jobs</p>
                    <p class="mt-1 text-2xl font-extrabold text-primary" aria-hidden="true">{{ $counts['all'] }}</p>
                </a>
                <a href="{{ route('moderator.jobs.index', array_merge($filterParams, ['status' => 'pending', 'flagged' => null])) }}"
                   class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $status === 'pending' ? 'border-l-4 border-l-warning' : '' }} hover:bg-warning/5 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-primary"
                   aria-current="{{ $status === 'pending' ? 'true' : 'false' }}">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Pending</p>
                    <p class="mt-1 text-2xl font-extrabold text-warning" aria-hidden="true">{{ $counts['pending'] }}</p>
                </a>
                <a href="{{ route('moderator.jobs.index', array_merge($filterParams, ['status' => 'approved', 'flagged' => null])) }}"
                   class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $status === 'approved' ? 'border-l-4 border-l-success' : '' }} hover:bg-success/5 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-primary"
                   aria-current="{{ $status === 'approved' ? 'true' : 'false' }}">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Approved</p>
                    <p class="mt-1 text-2xl font-extrabold text-success" aria-hidden="true">{{ $counts['approved'] }}</p>
                </a>
                <a href="{{ route('moderator.jobs.index', array_merge($filterParams, ['status' => 'rejected', 'flagged' => null])) }}"
                   class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $status === 'rejected' ? 'border-l-4 border-l-danger' : '' }} hover:bg-danger/5 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-primary"
                   aria-current="{{ $status === 'rejected' ? 'true' : 'false' }}">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Rejected</p>
                    <p class="mt-1 text-2xl font-extrabold text-danger" aria-hidden="true">{{ $counts['rejected'] }}</p>
                </a>
                <a href="{{ route('moderator.jobs.index', array_merge($filterParams, ['status' => 'all', 'flagged' => 1])) }}"
                   class="bg-white dark:bg-slate-900 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-slate-800 {{ $flagged ? 'border-l-4 border-l-orange-500' : '' }} hover:bg-orange-500/5 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-primary"
                   aria-current="{{ $flagged ? 'true' : 'false' }}">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">Flagged</p>
                    <p class="mt-1 text-2xl font-extrabold text-orange-500" aria-hidden="true">{{ $counts['flagged'] }}</p>
                </a>
            </div>

            <div class="box border">
                <div class="box-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/30">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h2 class="box-title m-0">
                            @if($flagged)
                                Flagged Jobs
                            @else
                                {{ ucfirst($status) }} Jobs
                            @endif
                        </h2>
                        @if($jobs->total() > 0)
                            <span class="text-sm text-gray-500 dark:text-gray-400" aria-live="polite">
                                ({{ $jobs->firstItem() }}–{{ $jobs->lastItem() }} of {{ number_format($jobs->total()) }})
                            </span>
                        @endif
                        @if($jobs->isNotEmpty() && ($status === 'pending' || $flagged))
                            <div class="flex gap-2">
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-success" id="bulk-approve-btn" disabled aria-label="Approve selected jobs">
                                    <i class="ri-check-double-line me-1" aria-hidden="true"></i> Approve Selected
                                </button>
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-danger" id="bulk-reject-btn" disabled aria-label="Reject selected jobs">
                                    <i class="ri-close-circle-line me-1" aria-hidden="true"></i> Reject Selected
                                </button>
                            </div>
                        @endif
                    </div>
                    <form action="{{ route('moderator.jobs.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-1 sm:flex-initial max-w-md sm:max-w-none" role="search" aria-label="Search jobs">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="hidden" name="flagged" value="{{ $flagged ? '1' : '' }}">
                        <label for="job-search" class="sr-only">Search by job title or company</label>
                        <div class="relative flex-1">
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                                <i class="ri-search-line" aria-hidden="true"></i>
                            </span>
                            <input type="search" id="job-search" name="search" value="{{ old('search', $search ?? '') }}"
                                   placeholder="Job title or company..."
                                   class="form-control form-control-sm w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors"
                                   autocomplete="off" aria-describedby="job-search-hint">
                        </div>
                        <div class="flex gap-2 flex-shrink-0">
                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-primary rounded-lg px-4">
                                <i class="ri-search-line me-1.5 sm:me-1" aria-hidden="true"></i>
                                <span>Search</span>
                            </button>
                            @if(!empty($search))
                                <a href="{{ route('moderator.jobs.index', array_filter(['status' => $status, 'flagged' => $flagged ? 1 : null])) }}" class="ti-btn ti-btn-sm ti-btn-light rounded-lg px-4" aria-label="Clear search">
                                    <i class="ri-close-line me-1" aria-hidden="true"></i>
                                    <span>Clear</span>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                <p id="job-search-hint" class="sr-only">Results update when you submit. Search by job title or company name.</p>
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="status" aria-live="polite">{{ session('status') }}</div>
                    @endif

                    @if($jobs->isEmpty())
                        <div class="text-center py-8">
                            <i class="ri-briefcase-line text-4xl text-textmuted mb-3" aria-hidden="true"></i>
                            @if(!empty($search))
                                <p class="text-textmuted">No {{ $flagged ? 'flagged' : $status }} jobs match your search.</p>
                                <p class="text-sm text-textmuted mt-1">Try different keywords or <a href="{{ route('moderator.jobs.index', array_filter(['status' => $status, 'flagged' => $flagged ? 1 : null])) }}" class="text-primary hover:underline">clear the search</a>.</p>
                            @else
                                <p class="text-textmuted">No {{ $flagged ? 'flagged' : $status }} jobs found.</p>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table whitespace-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        @if($status === 'pending' || $flagged)
                                        <th class="w-10">
                                            <label for="select-all" class="sr-only">Select all jobs on this page</label>
                                            <input type="checkbox" class="form-check-input" id="select-all" aria-label="Select all jobs on this page">
                                        </th>
                                        @endif
                                        <th>Job Title</th>
                                        <th>Company</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Posted</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobs as $job)
                                        <tr class="{{ $job->is_flagged ? 'bg-orange-50 dark:bg-orange-900/10' : '' }}">
                                            @if($status === 'pending' || $flagged)
                                            <td>
                                                <input type="checkbox" class="form-check-input job-checkbox" value="{{ $job->id }}">
                                            </td>
                                            @endif
                                            <td>
                                                <div class="flex items-center gap-2">
                                                    @if($job->is_flagged)
                                                        <i class="ri-flag-fill text-orange-500" title="Flagged: {{ $job->flag_reason }}" aria-hidden="true"></i>
                                                    @endif
                                                    <div>
                                                        <span class="font-medium">{{ $job->title }}</span>
                                                        <br><span class="text-xs text-textmuted">{{ $job->location ?? 'Remote' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($job->company)
                                                    <div class="flex items-center gap-2">
                                                        <span class="avatar avatar-sm avatar-rounded border">
                                                            @if($job->company->logo_url)
                                                                <img src="{{ $job->company->logo_url }}" alt="{{ $job->company->name }}">
                                                            @else
                                                                <span class="bg-primary/10 text-primary flex items-center justify-center w-full h-full text-xs">
                                                                    {{ strtoupper(substr($job->company->name, 0, 2)) }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                        <span>{{ $job->company->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-textmuted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $job->category ?? '-' }}</td>
                                            <td>
                                                @if($job->moderation_status === 'approved')
                                                    <span class="badge bg-success/10 text-success">Approved</span>
                                                @elseif($job->moderation_status === 'pending')
                                                    <span class="badge bg-warning/10 text-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-danger/10 text-danger">Rejected</span>
                                                @endif
                                                <br>
                                                <span class="badge bg-{{ $job->status === 'active' ? 'success' : 'secondary' }}/10 text-{{ $job->status === 'active' ? 'success' : 'secondary' }} text-xs">
                                                    {{ ucfirst($job->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $job->created_at->format('M d, Y') }}</td>
                                            <td class="text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <a href="{{ route('moderator.jobs.show', $job->id) }}" class="ti-btn ti-btn-sm ti-btn-info" aria-label="View {{ $job->title }}">
                                                        <i class="ri-eye-line" aria-hidden="true"></i>
                                                    </a>
                                                    @if($job->moderation_status === 'pending')
                                                        <form action="{{ route('moderator.jobs.approve', $job->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-success" aria-label="Approve {{ $job->title }}">
                                                                <i class="ri-check-line" aria-hidden="true"></i>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="ti-btn ti-btn-sm ti-btn-danger reject-btn"
                                                                data-job-id="{{ $job->id }}"
                                                                data-job-title="{{ $job->title }}"
                                                                aria-label="Reject {{ $job->title }}">
                                                            <i class="ri-close-line" aria-hidden="true"></i>
                                                        </button>
                                                    @endif
                                                    @if(!$job->is_flagged)
                                                        <button type="button" class="ti-btn ti-btn-sm ti-btn-warning flag-btn"
                                                                data-job-id="{{ $job->id }}"
                                                                data-job-title="{{ $job->title }}"
                                                                aria-label="Flag {{ $job->title }}">
                                                            <i class="ri-flag-line" aria-hidden="true"></i>
                                                        </button>
                                                    @else
                                                        <form action="{{ route('moderator.jobs.unflag', $job->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-secondary" aria-label="Remove flag from {{ $job->title }}">
                                                                <i class="ri-flag-off-line" aria-hidden="true"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Rejection Modal --}}
    <div id="reject-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="reject-modal-title" aria-describedby="reject-modal-desc">
        <div class="fixed inset-0 bg-black/50" onclick="closeRejectModal()" aria-hidden="true"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-lg shadow-2xl border-2 border-gray-200 dark:border-slate-600 ring-4 ring-black/10 dark:ring-white/10 max-w-md w-full p-6 relative">
                <h3 id="reject-modal-title" class="text-lg font-semibold mb-2">Reject Job Posting</h3>
                <p id="reject-modal-desc" class="text-textmuted mb-4">Rejecting: <strong id="reject-job-title"></strong>. Provide a reason so the employer can address it.</p>
                <form id="reject-form" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="reject-notes" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="notes" id="reject-notes" class="form-control" rows="3" required placeholder="e.g. Job description violates guidelines"
                                  aria-describedby="reject-notes-hint"></textarea>
                        <p id="reject-notes-hint" class="text-sm text-textmuted mt-1">This may be shared with the employer. Max 1000 characters.</p>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="ti-btn ti-btn-light" onclick="closeRejectModal()">Cancel</button>
                        <button type="submit" class="ti-btn ti-btn-danger" id="reject-submit-btn">Reject Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Flag Modal --}}
    <div id="flag-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="flag-modal-title" aria-describedby="flag-modal-desc">
        <div class="fixed inset-0 bg-black/50" onclick="closeFlagModal()" aria-hidden="true"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-lg shadow-2xl border-2 border-gray-200 dark:border-slate-600 ring-4 ring-black/10 dark:ring-white/10 max-w-md w-full p-6 relative">
                <h3 id="flag-modal-title" class="text-lg font-semibold mb-2">Flag Job Posting</h3>
                <p id="flag-modal-desc" class="text-textmuted mb-4">Flagging: <strong id="flag-job-title"></strong>. Explain why this job needs review.</p>
                <form id="flag-form" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="flag-reason" class="form-label">Flag Reason <span class="text-danger">*</span></label>
                        <textarea name="reason" id="flag-reason" class="form-control" rows="3" required placeholder="e.g. Suspicious content or missing details"
                                  aria-describedby="flag-reason-hint"></textarea>
                        <p id="flag-reason-hint" class="text-sm text-textmuted mt-1">Max 500 characters. Visible to moderators.</p>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="ti-btn ti-btn-light" onclick="closeFlagModal()">Cancel</button>
                        <button type="submit" class="ti-btn ti-btn-warning" id="flag-submit-btn">Flag Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bulk Reject Modal --}}
    <div id="bulk-reject-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="bulk-reject-modal-title" aria-describedby="bulk-reject-modal-desc">
        <div class="fixed inset-0 bg-black/50" onclick="closeBulkRejectModal()" aria-hidden="true"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-slate-900 rounded-lg shadow-2xl border-2 border-gray-200 dark:border-slate-600 ring-4 ring-black/10 dark:ring-white/10 max-w-md w-full p-6 relative">
                <h3 id="bulk-reject-modal-title" class="text-lg font-semibold mb-2">Bulk Reject Jobs</h3>
                <p id="bulk-reject-modal-desc" class="text-textmuted mb-4">You are about to reject <strong id="bulk-reject-count">0</strong> jobs. Provide a reason (applies to all).</p>
                <form id="bulk-reject-form" method="POST" action="{{ route('moderator.jobs.bulk-reject') }}">
                    @csrf
                    <div id="bulk-reject-ids"></div>
                    <div class="mb-4">
                        <label for="bulk-reject-notes" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="notes" id="bulk-reject-notes" class="form-control" rows="3" required placeholder="Reason for rejecting these jobs..."
                                  aria-describedby="bulk-reject-notes-hint"></textarea>
                        <p id="bulk-reject-notes-hint" class="text-sm text-textmuted mt-1">This may be shared with employers.</p>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="ti-btn ti-btn-light" onclick="closeBulkRejectModal()">Cancel</button>
                        <button type="submit" class="ti-btn ti-btn-danger" id="bulk-reject-submit-btn">Reject All</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(jobId, jobTitle) {
            document.getElementById('reject-job-title').textContent = jobTitle;
            document.getElementById('reject-form').action = '/moderator/jobs/' + jobId + '/reject';
            document.getElementById('reject-modal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }

        function openFlagModal(jobId, jobTitle) {
            document.getElementById('flag-job-title').textContent = jobTitle;
            document.getElementById('flag-form').action = '/moderator/jobs/' + jobId + '/flag';
            document.getElementById('flag-modal').classList.remove('hidden');
        }

        function closeFlagModal() {
            document.getElementById('flag-modal').classList.add('hidden');
        }

        function closeBulkRejectModal() {
            document.getElementById('bulk-reject-modal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.reject-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    openRejectModal(this.dataset.jobId, this.dataset.jobTitle);
                });
            });

            document.querySelectorAll('.flag-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    openFlagModal(this.dataset.jobId, this.dataset.jobTitle);
                });
            });

            // Bulk selection
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.job-checkbox');
            const bulkApproveBtn = document.getElementById('bulk-approve-btn');
            const bulkRejectBtn = document.getElementById('bulk-reject-btn');

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateBulkButtons();
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkButtons);
            });

            function getSelectedIds() {
                return Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
            }

            function updateBulkButtons() {
                const selected = getSelectedIds();
                if (bulkApproveBtn) bulkApproveBtn.disabled = selected.length === 0;
                if (bulkRejectBtn) bulkRejectBtn.disabled = selected.length === 0;
            }

            if (bulkApproveBtn) {
                bulkApproveBtn.addEventListener('click', function() {
                    const ids = getSelectedIds();
                    if (ids.length === 0) return;

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('moderator.jobs.bulk-approve') }}';
                    form.innerHTML = '@csrf';
                    ids.forEach(id => {
                        form.innerHTML += '<input type="hidden" name="job_ids[]" value="' + id + '">';
                    });
                    document.body.appendChild(form);
                    form.submit();
                });
            }

            if (bulkRejectBtn) {
                bulkRejectBtn.addEventListener('click', function() {
                    const ids = getSelectedIds();
                    if (ids.length === 0) return;

                    document.getElementById('bulk-reject-count').textContent = ids.length;
                    const container = document.getElementById('bulk-reject-ids');
                    container.innerHTML = '';
                    ids.forEach(id => {
                        container.innerHTML += '<input type="hidden" name="job_ids[]" value="' + id + '">';
                    });
                    document.getElementById('bulk-reject-modal').classList.remove('hidden');
                });
            }

            function setSubmitLoading(btn, label) {
                if (!btn) return;
                btn.disabled = true;
                btn.dataset.originalText = btn.textContent;
                btn.innerHTML = '<span class="inline-block animate-spin shrink-0 size-4 border-2 border-current border-transparent rounded-full me-1.5 align-middle" role="status" aria-hidden="true"></span> ' + label;
            }

            var rejectForm = document.getElementById('reject-form');
            if (rejectForm) {
                rejectForm.addEventListener('submit', function() {
                    setSubmitLoading(document.getElementById('reject-submit-btn'), 'Rejecting…');
                });
            }
            var flagForm = document.getElementById('flag-form');
            if (flagForm) {
                flagForm.addEventListener('submit', function() {
                    setSubmitLoading(document.getElementById('flag-submit-btn'), 'Flagging…');
                });
            }
            var bulkRejectForm = document.getElementById('bulk-reject-form');
            if (bulkRejectForm) {
                bulkRejectForm.addEventListener('submit', function() {
                    setSubmitLoading(document.getElementById('bulk-reject-submit-btn'), 'Rejecting…');
                });
            }
        });
    </script>

</x-app-layout>
