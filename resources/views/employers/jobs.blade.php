<x-app-layout>

    <x-slot name="pageTitle">Job Postings</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Job Postings</x-slot>

    @include('employers.partials.employer-styles')

    <x-modern-header chip="Job Postings" title="Manage Your Job Postings"
        desc='Create, publish, and manage active hiring roles from one place.'>
        <x-slot name="actions">
            @if(($canPostJobs ?? false) === true)
                <a href="{{ route('jobs.create') }}" class="cd-btn cd-btn-primary">
                    <i class="ri-add-line"></i>
                    <span>Add Job</span>
                </a>
                <a href="{{ route('employer.companies.index') }}" class="cd-btn cd-btn-secondary">
                    <i class="ri-building-2-line"></i>
                    <span>Manage Companies</span>
                </a>
            @else
                <a href="{{ route('employer.companies.index') }}" class="cd-btn cd-btn-secondary">
                    <i class="ri-shield-check-line"></i>
                    <span>Company verification required</span>
                </a>
            @endif
        </x-slot>
    </x-modern-header>

    <div class="max-w-7xl mx-auto pb-6 sm:px-6 lg:px-8">

        <div class="cd-stat-ribbon fade-up">
            <button type="button" data-status-filter="all" class="cd-stat-pill active">
                <div class="cd-stat-icon-box"><i class="ri-briefcase-line"></i></div>
                <div class="cd-stat-info">
                    <div class="label">Total Jobs</div>
                    <div class="value">{{ $totalJobsCount ?? 0 }}</div>
                </div>
            </button>
            <button type="button" data-status-filter="open" class="cd-stat-pill open">
                <div class="cd-stat-icon-box"><i class="ri-door-open-line"></i></div>
                <div class="cd-stat-info">
                    <div class="label">Active Roles</div>
                    <div class="value">{{ $openCount ?? 0 }}</div>
                </div>
            </button>
            <button type="button" data-status-filter="closed" class="cd-stat-pill closed">
                <div class="cd-stat-icon-box"><i class="ri-lock-line"></i></div>
                <div class="cd-stat-info">
                    <div class="label">Closed / History</div>
                    <div class="value">{{ $closedCount ?? 0 }}</div>
                </div>
            </button>
        </div>

        @if(($canPostJobs ?? false) === false)
            <div class="col-span-12 cd-section mb-6"
                style="border-color:#fde68a;background:rgba(255, 251, 235, 0.5); backdrop-filter: blur(10px);">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="flex items-start gap-2">
                        <i class="ri-alert-line mt-0.5" style="color:#a16207"></i>
                        <div>
                            <div class="font-semibold" style="color:#854d0e">Verification Required</div>
                            <div class="mt-1 text-sm" style="color:#a16207">
                                @if(($hasAnyCompany ?? false) === false)
                                    Register a company profile first to start posting jobs.
                                @elseif(($hasPendingCompanies ?? false) === true)
                                    Your company verification is currently in review.
                                @else
                                    You need at least one verified company to post jobs.
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('employer.companies.index') }}" class="cd-btn cd-btn-primary">
                        <i class="ri-building-2-line"></i> Complete Setup
                    </a>
                </div>
            </div>
        @endif

        {{-- Control Center --}}
        <div class="cd-section mb-6" id="wt-jobs-toolbar">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="cd-search-wrap" style="min-width: 300px;">
                        <span class="cd-search-icon"><i class="ri-search-line"></i></span>
                        <input type="text" id="search-input" class="cd-search"
                            placeholder="Search by role or company...">
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <div id="bulk-controls"
                        class="hidden flex items-center gap-2 pr-3 border-r border-gray-200 dark:border-gray-700">
                        <select id="bulk-action-select" class="cd-toolbar-select">
                            <option value="">Bulk Action</option>
                            <option value="open">Set Open</option>
                            <option value="closed">Set Closed</option>
                            <option value="archive">Archive</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="button" id="apply-bulk-action" class="cd-btn cd-btn-primary cd-btn-sm"><i
                                class="ri-check-line"></i></button>
                    </div>

                    <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800/50 p-1 rounded-lg">
                        <button type="button" data-status-filter="all" class="cd-filter-btn cd-btn cd-btn-sm px-4 cd-btn-primary">All</button>
                        <button type="button" data-status-filter="open" class="cd-filter-btn cd-btn cd-btn-sm px-4 cd-btn-ghost border-0">Open</button>
                        <button type="button" data-status-filter="closed" class="cd-filter-btn cd-btn cd-btn-sm px-4 cd-btn-ghost border-0">Closed</button>
                    </div>
                </div>
            </div>

            <div class="mt-3 flex items-center justify-between">
                <div class="text-xs text-gray-400">
                    <span id="selected-count" class="hidden font-medium text-primary"><span
                            id="selected-count-num">0</span> items selected</span>
                    <span id="total-view-count">Showing {{ $jobs->total() }} total results</span>
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <span><span class="cd-kbd">/</span> to search</span>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="select-all" class="form-check-input" style="width:14px;height:14px;">
                        <span>Select All</span>
                    </label>
                </div>
            </div>
        </div>

        @if (session('status'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (window.Swal) Swal.fire({ icon: 'success', title: 'Done!', text: @json(session('status')), timer: 2000, showConfirmButton: false });
                });
            </script>
        @endif

        {{-- Job Card Grid --}}
        @if($jobs->isEmpty())
            <div class="cd-empty cd-section py-20">
                <div
                    class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4 mx-auto">
                    <i class="ri-briefcase-line text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">No job postings found</h3>
                <p class="text-gray-500 mb-6 max-w-sm mx-auto">Adjust your filters or create a new job posting to get
                    started.</p>
                @if(($canPostJobs ?? false) === true)
                    <a href="{{ route('jobs.create') }}" class="cd-btn cd-btn-primary"><i class="ri-add-line me-1"></i> Create
                        New Job</a>
                @endif
            </div>
        @else
            <div class="cd-job-grid" id="wt-jobs-grid">
                @foreach($jobs as $job)
                    @php
                        $jobTitle = $job->title;
                        $companyName = $job->company?->name ?? 'Private';
                        $avatarBgs = ['#4f46e5','#0891b2','#7c3aed','#0d9488','#ea580c','#db2777','#1e40af','#0369a1'];
                        $avatarBg  = $avatarBgs[abs(crc32($jobTitle)) % count($avatarBgs)];
                        $initial   = strtoupper(substr($jobTitle, 0, 1));
                    @endphp
                    <div class="cd-job-card job-row fade-up" data-status="{{ $job->status }}"
                        style="animation-delay: {{ $loop->index * 0.05 }}s">
                        
                        <div class="cd-job-card-top">
                            <div style="display:flex;align-items:center;gap:0.75rem;min-width:0;flex:1">
                                <div class="cd-job-avatar" style="background: {{ $avatarBg }}; box-shadow: 0 8px 16px {{ $avatarBg }}44">
                                    {{ $initial }}
                                </div>
                                <div style="min-width:0;flex:1">
                                    <div class="cd-company-name-mini" style="color: {{ $avatarBg }}">
                                        {{ $companyName }}
                                        @if($job->status === 'open')
                                            <span class="cd-status-pulse-mini"></span>
                                        @endif
                                    </div>
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="cd-job-title" title="{{ $jobTitle }}">{{ $jobTitle }}</a>
                                </div>
                            </div>
                            <input type="checkbox" class="row-checkbox form-check-input" value="{{ $job->id }}"
                                data-title="{{ $jobTitle }}" style="margin-left: 0.5rem">
                        </div>

                        <div class="cd-job-card-body">
                            <div class="cd-job-meta">
                                @if($job->employment_type)
                                    <span><i class="ri-time-line"></i> {{ Str::headline($job->employment_type) }}</span>
                                @endif
                                @if($job->location)
                                    <span><i class="ri-map-pin-line"></i> {{ Str::headline($job->location) }}</span>
                                @endif
                                <span><i class="ri-calendar-line"></i> {{ $job->posted_at?->diffForHumans() ?? '—' }}</span>
                            </div>

                            <div class="cd-job-stats-row">
                                <a href="{{ route('employer.applications.index', ['job' => $job->id]) }}" class="cd-job-apps-link">
                                    <i class="ri-group-line"></i>
                                    <span>{{ $job->applications_count ?? 0 }} Applicants</span>
                                </a>
                                <span class="cd-status-pill-mini {{ $job->status }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="cd-job-card-footer">
                            <a href="{{ route('jobs.show', $job->slug) }}" class="cd-view-role-btn">
                                <i class="ri-eye-line"></i> <span>View Role</span>
                            </a>
                            <details class="cd-action-dropdown">
                                <summary class="cd-more-btn">
                                    <i class="ri-more-2-line"></i>
                                </summary>
                                <div class="cd-action-menu" style="bottom: 100%; top: auto; margin-bottom: 8px;">
                                    @if($job->status === 'open')
                                        <button type="button" class="cd-action-item job-status-btn" data-job-slug="{{ $job->slug }}"
                                            data-new-status="closed" data-job-title="{{ $jobTitle }}">
                                            <i class="ri-lock-line"></i> <span>Close Role</span>
                                        </button>
                                    @else
                                        <button type="button" class="cd-action-item job-status-btn" data-job-slug="{{ $job->slug }}"
                                            data-new-status="open" data-job-title="{{ $jobTitle }}">
                                            <i class="ri-door-open-line"></i> <span>Reopen Role</span>
                                        </button>
                                    @endif
                                    <div class="h-px bg-gray-100 dark:bg-gray-800 my-1"></div>
                                    <button type="button" class="cd-action-item cd-action-item-danger job-delete-btn"
                                        data-job-id="{{ $job->id }}" data-job-title="{{ $jobTitle }}">
                                        <i class="ri-delete-bin-6-line"></i> <span>Delete</span>
                                    </button>
                                </div>
                            </details>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- No results empty state --}}
            <div id="cd-search-empty" class="cd-empty cd-section py-20 hidden">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4 mx-auto">
                    <i class="ri-search-2-line text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">No matching jobs found</h3>
                <p class="text-gray-500 mt-1 max-w-xs mx-auto">Try adjusting your filters or search terms to find what you're looking for.</p>
                <button type="button" id="clear-filters-btn" class="cd-btn cd-btn-primary mt-6">Clear All Filters</button>
            </div>

            <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-gray-500">
                    Showing <strong>{{ $jobs->firstItem() ?? 0 }}</strong> - <strong>{{ $jobs->lastItem() ?? 0 }}</strong>
                    of <strong>{{ $jobs->total() }}</strong> jobs
                </div>
                <div class="cd-pagination">{{ $jobs->onEachSide(1)->links() }}</div>
            </div>
        @endif
    </div>
    </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const selectAll = document.getElementById('select-all');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const bulkSelect = document.getElementById('bulk-action-select');
            const applyBulk = document.getElementById('apply-bulk-action');
            const bulkControls = document.getElementById('bulk-controls');
            const selectedCount = document.getElementById('selected-count');
            const selectedCountNum = document.getElementById('selected-count-num');
            const searchInput = document.getElementById('search-input');
            const jobCards = Array.from(document.querySelectorAll('.job-row'));
            let focusReturnEl = null;

            function rememberFocus(el) {
                focusReturnEl = el instanceof HTMLElement ? el : document.activeElement;
            }

            function restoreFocus() {
                if (focusReturnEl && typeof focusReturnEl.focus === 'function') {
                    setTimeout(function () { focusReturnEl.focus(); }, 0);
                }
            }

            let currentStatus = 'all';

            function filterJobs() {
                const searchTerm = (searchInput.value || '').toLowerCase();
                let visibleCount = 0;
                const grid = document.getElementById('wt-jobs-grid');
                const emptyState = document.getElementById('cd-search-empty');

                jobCards.forEach(function (card) {
                    const cardStatus = card.dataset.status;
                    const cardText = card.textContent.toLowerCase();

                    const matchesStatus = (currentStatus === 'all' || cardStatus === currentStatus);
                    const matchesSearch = cardText.includes(searchTerm);

                    if (matchesStatus && matchesSearch) {
                        card.classList.remove('cd-card-hidden');
                        visibleCount++;
                    } else {
                        card.classList.add('cd-card-hidden');
                    }
                });

                // Update counters
                const totalCountEl = document.getElementById('total-view-count');
                if (totalCountEl) totalCountEl.textContent = `Showing ${visibleCount} results`;

                // Handle empty state
                if (visibleCount === 0) {
                    emptyState?.classList.remove('hidden');
                    grid?.classList.add('hidden');
                } else {
                    emptyState?.classList.add('hidden');
                    grid?.classList.remove('hidden');
                }

                // Update button states
                updateFilterButtonStates();
            }

            function updateFilterButtonStates() {
                // Toolbar buttons
                document.querySelectorAll('[data-status-filter]').forEach(btn => {
                    const filterVal = btn.dataset.statusFilter;
                    const isActive = filterVal === currentStatus;

                    if (btn.classList.contains('cd-stat-pill')) {
                        // Stats ribbon pills
                        btn.classList.toggle('active', isActive);
                    } else {
                        // Toolbar small buttons
                        if (isActive) {
                            btn.classList.remove('cd-btn-ghost', 'border-0');
                            btn.classList.add('cd-btn-primary');
                        } else {
                            btn.classList.add('cd-btn-ghost', 'border-0');
                            btn.classList.remove('cd-btn-primary');
                        }
                    }
                });
            }

            /* =============================
               FILTERS & SEARCH LISTENERS
            ============================== */
            if (searchInput) {
                searchInput.addEventListener('input', filterJobs);
            }

            document.querySelectorAll('[data-status-filter]').forEach(btn => {
                btn.addEventListener('click', () => {
                    currentStatus = btn.dataset.statusFilter;
                    filterJobs();
                });
            });

            const clearBtn = document.getElementById('clear-filters-btn');
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    if (searchInput) searchInput.value = '';
                    currentStatus = 'all';
                    filterJobs();
                });
            }

            document.addEventListener('keydown', function (event) {
                const activeEl = document.activeElement;
                const isTypingTarget = activeEl && (
                    activeEl.tagName === 'INPUT' ||
                    activeEl.tagName === 'TEXTAREA' ||
                    activeEl.tagName === 'SELECT' ||
                    activeEl.isContentEditable
                );

                if (event.key === '/' && !isTypingTarget) {
                    event.preventDefault();
                    searchInput && searchInput.focus();
                }

                if (event.key === 'Escape' && activeEl === searchInput) {
                    searchInput.blur();
                }
            });

            /* =============================
               SELECT LOGIC
            ============================== */
            function updateSelectionUI() {
                const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                const checkedCount = checkedBoxes.length;

                if (checkedCount > 0) {
                    selectedCount.classList.remove('hidden');
                    selectedCountNum.textContent = checkedCount;
                    bulkControls.classList.remove('hidden');
                    bulkControls.classList.add('flex');
                } else {
                    selectedCount.classList.add('hidden');
                    bulkControls.classList.add('hidden');
                    bulkControls.classList.remove('flex');
                }

                // Card highlight
                document.querySelectorAll('.job-row').forEach(card => {
                    const cb = card.querySelector('.row-checkbox');
                    if (cb && cb.checked) {
                        card.style.borderColor = 'var(--cd-primary)';
                        card.style.background = 'rgba(29, 78, 216, 0.03)';
                    } else {
                        card.style.borderColor = '';
                        card.style.background = '';
                    }
                });
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
                    updateSelectionUI();
                });
            }

            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateSelectionUI);
            });

            jobCards.forEach(function (card) {
                card.addEventListener('click', function (event) {
                    // Don't toggle if clicking a button, link, or the checkbox itself
                    if (event.target.closest('button, a, select, details, input[type="checkbox"]')) return;

                    const checkbox = card.querySelector('.row-checkbox');
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                        updateSelectionUI();
                    }
                });
            });

            /* =============================
               BULK ACTION
            ============================== */
            if (applyBulk) {
                applyBulk.addEventListener('click', function () {
                    rememberFocus(applyBulk);

                    const action = bulkSelect.value;
                    const ids = Array.from(document.querySelectorAll('.row-checkbox:checked'))
                        .map(cb => cb.value);

                    if (!action) {
                        Swal.fire({ icon: 'warning', title: 'Select an action first' });
                        return;
                    }

                    if (!ids.length) {
                        Swal.fire({ icon: 'warning', title: 'No jobs selected' });
                        return;
                    }

                    const labels = {
                        open: 'Reopen',
                        closed: 'Close',
                        archive: 'Archive',
                        delete: 'Move to trash'
                    };

                    Swal.fire({
                        icon: 'question',
                        title: (labels[action] || 'Update') + ' ' + ids.length + ' job(s)?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, proceed',
                        confirmButtonColor: (action === 'delete') ? '#dc2626' : '#4f46e5'
                    }).then(function (result) {
                        if (!result.isConfirmed) {
                            restoreFocus();
                            return;
                        }

                        let url;
                        let method = 'POST';
                        let body = { ids: ids };

                        if (action === 'delete') {
                            url = '{{ route("employer.jobs.bulk-delete") }}';
                            method = 'DELETE';
                        } else if (action === 'archive') {
                            url = '{{ route("employer.jobs.bulk-archive") }}';
                        } else {
                            url = '{{ route("employer.jobs.bulk-status") }}';
                            body.status = action;
                        }

                        fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(body)
                        })
                            .then(res => res.json())
                            .then(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => window.location.reload());
                            })
                            .catch(() => {
                                Swal.fire({ icon: 'error', title: 'Request failed' });
                                restoreFocus();
                            });
                    });
                });
            }

            /* =============================
               INDIVIDUAL ACTIONS
            ============================== */
            // Reusing logic for status/delete buttons
            document.querySelectorAll('.job-status-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    const jobSlug = btn.dataset.jobSlug;
                    const newStatus = btn.dataset.newStatus;
                    const title = btn.dataset.jobTitle;

                    Swal.fire({
                        title: `${newStatus === 'open' ? 'Reopen' : 'Close'} "${title}"?`,
                        icon: 'question',
                        showCancelButton: true
                    }).then(res => {
                        if (res.isConfirmed) {
                            fetch(`/employer/jobs/${jobSlug}/status`, {
                                method: 'PATCH',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: JSON.stringify({ status: newStatus })
                            }).then(() => window.location.reload());
                        }
                    });
                });
            });

            document.querySelectorAll('.job-delete-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const jobId = btn.dataset.jobId;
                    const title = btn.dataset.jobTitle;
                    Swal.fire({
                        title: `Delete "${title}"?`,
                        text: "This will move the job to history. You can restore it later.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626'
                    }).then(res => {
                        if (res.isConfirmed) {
                            fetch(`/employer/jobs/bulk-delete`, {
                                method: 'DELETE',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: JSON.stringify({ ids: [jobId] })
                            }).then(() => window.location.reload());
                        }
                    });
                });
            });
        });
    </script>

    @include('candidates.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'wt-hero', 'title' => 'Job Postings', 'icon' => 'ri-briefcase-line', 'body' => 'Manage all your job listings from here — create new postings, track applicants, and adjust role statuses.', 'position' => 'bottom'],
            ['target' => 'cd-stat-ribbon', 'title' => 'Stats Overview', 'icon' => 'ri-bar-chart-line', 'body' => 'Quickly see how many jobs are active or closed across all your companies.', 'position' => 'bottom'],
            ['target' => 'wt-jobs-toolbar', 'title' => 'Control Center', 'icon' => 'ri-tools-line', 'body' => 'Search roles, filter by status, or use bulk actions to manage multiple listings at once.', 'position' => 'bottom'],
            ['target' => 'wt-jobs-grid', 'title' => 'Job Cards', 'icon' => 'ri-grid-fill', 'body' => 'Each card gives you a clear overview of the role, applicant count, and quick management links.', 'position' => 'top'],
        ],
        'wtKey' => 'employer_jobs_v3',
    ])

</x-app-layout>
