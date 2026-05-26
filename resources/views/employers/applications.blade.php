<x-app-layout>

    <x-slot name="pageTitle">Applications</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Applications</x-slot>

    @include('employers.partials.employer-styles')

    <style>
        .cd-applications-page {
            min-height: calc(100vh - 260px);
        }

        .cd-applications-page .cd-applications-panel,
        .cd-applications-page .cd-applications-panel .cd-section {
            height: 100%;
        }

        @media (max-width: 1024px) {
            .cd-applications-page {
                min-height: 0;
            }

            .cd-applications-page .cd-applications-panel,
            .cd-applications-page .cd-applications-panel .cd-section {
                height: auto;
            }
        }
    </style>

    @php
        $statusStyles = [
            'applied'       => ['bg'=>'#eef2ff','text'=>'#4f46e5','label'=>'Applied'],
            'submitted'     => ['bg'=>'#eef2ff','text'=>'#4f46e5','label'=>'Applied'],
            'under_review'  => ['bg'=>'#fefce8','text'=>'#ca8a04','label'=>'Under Review'],
            'application_viewed' => ['bg'=>'#fefce8','text'=>'#ca8a04','label'=>'Under Review'],
            'viewed'        => ['bg'=>'#fefce8','text'=>'#ca8a04','label'=>'Under Review'],
            'in_progress'   => ['bg'=>'#f5f3ff','text'=>'#7c3aed','label'=>'Interviewing'],
            'accepted'      => ['bg'=>'#f0fdf4','text'=>'#16a34a','label'=>'Accept'],
            'not_selected'  => ['bg'=>'#fef2f2','text'=>'#dc2626','label'=>'Declined'],
            'no_longer_under_consideration' => ['bg'=>'#fef2f2','text'=>'#dc2626','label'=>'Declined'],
            'closed'        => ['bg'=>'#f9fafb','text'=>'#6b7280','label'=>'Closed'],
        ];
        $logoBgs = ['#4f46e5','#0d9488','#dc2626','#7c3aed','#ea580c','#0284c7'];
    @endphp

    <x-modern-header chip="Applicants" title="Manage Your Candidate Pool"
        desc='Review and manage all candidate applications across your job postings.'>
        <x-slot name="actions">
            <a href="{{ route('employer.applications.pipeline') }}" class="cd-btn cd-btn-primary">
                <i class="ri-kanban-view"></i>
                <span>Pipeline View</span>
            </a>
            <a href="{{ route('employer.history.index') }}" class="cd-btn cd-btn-secondary">
                <i class="ri-history-line"></i>
                <span>History</span>
            </a>
        </x-slot>
    </x-modern-header>

    <div class="grid grid-cols-12 gap-x-5 gap-y-4 cd-applications-page">

        {{-- ═══ Applications Table ═══ --}}
        <div class="col-span-12 cd-applications-panel" id="wt-apps-section">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-table-fill"></i> All Applications</span>
                    <span class="text-xs text-gray-400">{{ $applications->total() }} total</span>
                </div>

                @if (session('status'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (window.Swal) Swal.fire({ icon: 'success', title: 'Applications', text: @json(session('status')), timer: 2200, showConfirmButton: false });
                        });
                    </script>
                @endif

                {{-- Toolbar --}}
                <div class="cd-toolbar" id="wt-toolbar">
                    {{-- Tier 1: Search Focus --}}
                    <div class="cd-toolbar-tier">
                        <div class="cd-search-wrap">
                            <label for="search-input" class="sr-only">Search applications</label>
                            <span class="cd-search-icon"><i class="ri-search-2-line"></i></span>
                            <input type="text" id="search-input" class="cd-search" placeholder="Search by candidate, job title, or company...">
                        </div>
                    </div>

                    {{-- Tier 2: Actions & Navigation --}}
                    <div class="cd-toolbar-tier">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2">
                                <label for="bulk-action-select" class="sr-only">Bulk action</label>
                                <select id="bulk-action-select" class="cd-toolbar-select !min-w-[160px]">
                                    <option value="">Bulk Action</option>
                                    <option value="delete">Delete Selected</option>
                                </select>
                                <button type="button" id="apply-bulk-action" class="cd-btn cd-btn-primary cd-btn-sm">
                                    Apply
                                </button>
                            </div>
                            <span id="selected-count" class="text-xs font-bold text-blue-600 dark:text-blue-400 hidden bg-blue-50 dark:bg-blue-500/10 px-2 py-1 rounded-full border border-blue-100 dark:border-blue-500/20" aria-live="polite">
                                <span id="selected-count-num">0</span> selected
                            </span>
                        </div>

                        {{-- Status Pill Tabs --}}
                        <div class="cd-tabs-pilled" id="wt-status-filters">
                            <a href="{{ route('employer.applications.index') }}" 
                               class="cd-tab-pill {{ !$statusFilter ? 'active' : '' }}">
                                All
                            </a>
                            <a href="{{ route('employer.applications.index', ['status'=>'applied']) }}" 
                               class="cd-tab-pill {{ $statusFilter === 'applied' ? 'active' : '' }}">
                                Applied
                            </a>
                            <a href="{{ route('employer.applications.index', ['status'=>'under_review']) }}" 
                               class="cd-tab-pill {{ $statusFilter === 'under_review' ? 'active warning' : '' }}">
                                Under Review
                            </a>
                            <a href="{{ route('employer.applications.index', ['status'=>'in_progress']) }}" 
                               class="cd-tab-pill {{ $statusFilter === 'in_progress' ? 'active !text-purple-600 !bg-purple-50 dark:!bg-purple-500/10' : '' }}">
                                Interview
                            </a>
                            <a href="{{ route('employer.applications.index', ['status'=>'accepted']) }}" 
                               class="cd-tab-pill {{ $statusFilter === 'accepted' ? 'active success' : '' }}">
                                Accepted
                            </a>
                            <a href="{{ route('employer.applications.index', ['status'=>'declined']) }}" 
                               class="cd-tab-pill {{ $statusFilter === 'declined' ? 'active danger' : '' }}">
                                Declined
                            </a>
                        </div>
                    </div>
                </div>

                {{-- AJAX Target Container --}}
                <div id="applications-container" class="cd-animate-fade-in">
                    @if($applications->isEmpty())
                        <div class="cd-empty-state cd-animate-fade-in">
                            <div class="cd-empty-icon-container">
                                <i class="ri-team-line"></i>
                            </div>
                            <h3 class="cd-empty-title">No Candidates Yet</h3>
                            <p class="cd-empty-desc">
                                When applicants apply to your job postings, they will appear here for your review and management.
                            </p>
                        </div>
                    @else
                        <div class="table-responsive" style="border-radius:8px;overflow:visible" id="wt-apps-table">
                            <table class="cd-table">
                                <thead>
                                <tr>
                                    <th style="width:40px"><input type="checkbox" id="select-all" class="form-check-input" aria-label="Select all applications"></th>
                                    <th>Candidate</th>
                                    <th>Job</th>
                                    <th>Applied</th>
                                    <th>Status</th>
                                    <th style="text-align:right;padding-right:1rem">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($applications as $idx => $app)
                                    @php $sc = $statusStyles[$app->status] ?? ['bg'=>'#f9fafb','text'=>'#6b7280','label'=>Str::headline($app->status)]; @endphp
                                    <tr class="app-row">
                                        <td><input type="checkbox" class="form-check-input row-checkbox" value="{{ $app->id }}" data-name="{{ $app->applicantProfile?->display_name ?? 'Candidate' }}" aria-label="Select application {{ $app->id }}"></td>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:8px">
                                                <div style="width:32px;height:32px;border-radius:8px;background:{{ $logoBgs[$idx % count($logoBgs)] }};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.75rem;flex-shrink:0">
                                                    {{ strtoupper(substr($app->applicantProfile?->display_name ?? $app->applicantProfile?->user?->name ?? 'C', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold dark:text-white">{{ $app->applicantProfile?->display_name ?? $app->applicantProfile?->user?->name ?? 'Unknown' }}</div>
                                                    <div class="text-xs text-gray-400">{{ $app->applicantProfile?->user?->email ?? '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('jobs.show', $app->jobPosting->slug) }}" class="text-sm font-semibold dark:text-white" style="text-decoration:none;color:inherit">{{ $app->jobPosting->title }}</a>
                                            <div class="text-xs text-gray-400">{{ $app->jobPosting->company?->name ?? '' }}</div>
                                        </td>
                                        <td class="text-xs text-gray-400">{{ $app->applied_at?->format('M d, Y') ?? '—' }}</td>
                                        <td><span class="cd-status-pill" style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }}">{{ $sc['label'] }}</span></td>
                                        <td style="text-align:right;padding-right:1rem">
                                            @if(!in_array($app->status, ['accepted', 'not_selected']))
                                                <details class="cd-action-dropdown">
                                                    <summary class="cd-action-toggle" aria-label="Application actions {{ $app->id }}"><i class="ri-more-2-fill"></i></summary>
                                                    <div class="cd-action-menu">
                                                        <button type="button" class="cd-action-item app-status-action" data-app-id="{{ $app->id }}" data-status="applied"><i class="ri-check-line"></i> Applied</button>
                                                        <button type="button" class="cd-action-item app-status-action" data-app-id="{{ $app->id }}" data-status="under_review"><i class="ri-search-eye-line"></i> Under Review</button>
                                                        <button type="button" class="cd-action-item app-status-action" data-app-id="{{ $app->id }}" data-status="in_progress"><i class="ri-chat-4-line"></i> Interview</button>
                                                        <button type="button" class="cd-action-item app-status-action" data-app-id="{{ $app->id }}" data-status="accepted"><i class="ri-checkbox-circle-line"></i> Accept</button>
                                                        <button type="button" class="cd-action-item cd-action-item-danger app-status-action" data-app-id="{{ $app->id }}" data-status="not_selected"><i class="ri-close-circle-line"></i> Decline</button>
                                                    </div>
                                                </details>
                                            @else
                                                <div style="display:flex;align-items:center;justify-content:flex-end;gap:4px;color:var(--kb-text-secondary);font-size:0.75rem;font-weight:600;opacity:0.7" title="This application is finalized and locked.">
                                                    <i class="ri-lock-2-line"></i> <span>Locked</span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs text-gray-400">
                            <div>
                                @if($applications->total() > 0)
                                    Showing <span class="font-bold text-gray-700 dark:text-gray-300">{{ $applications->firstItem() }}</span> to <span class="font-bold text-gray-700 dark:text-gray-300">{{ $applications->lastItem() }}</span> of {{ $applications->total() }} results
                                @else Showing 0 results @endif
                            </div>
                            <div class="cd-pagination">{{ $applications->onEachSide(1)->links() }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('applications-container');
            const toolbar = document.getElementById('wt-toolbar');

            function initializeListeners() {
                var selectAll = document.getElementById('select-all');
                var rowCheckboxes = document.querySelectorAll('.row-checkbox');
                var bulkSelect = document.getElementById('bulk-action-select');
                var applyBulk = document.getElementById('apply-bulk-action');
                var selectedCount = document.getElementById('selected-count');
                var selectedCountNum = document.getElementById('selected-count-num');
                var searchInput = document.getElementById('search-input');
                var tableRows = Array.from(document.querySelectorAll('.app-row'));

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        var s = searchInput.value.toLowerCase();
                        tableRows.forEach(function(r) { r.style.display = r.textContent.toLowerCase().includes(s) ? '' : 'none'; });
                    });
                }

                function updateCount() {
                    var c = document.querySelectorAll('.row-checkbox:checked').length;
                    if (c > 0) { selectedCount.classList.remove('hidden'); selectedCountNum.textContent = c; }
                    else { selectedCount.classList.add('hidden'); }
                }

                if (selectAll) {
                    selectAll.addEventListener('change', function() { rowCheckboxes.forEach(function(cb) { cb.checked = selectAll.checked; }); updateCount(); });
                }
                rowCheckboxes.forEach(function(cb) { cb.addEventListener('change', function() { updateCount(); }); });

                // Bulk delete
                if (applyBulk) {
                    applyBulk.onclick = function() {
                        var action = bulkSelect.value;
                        var ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(function(cb) { return cb.value; });
                        if (!action) { Swal.fire({ icon: 'warning', title: 'Select an action' }); return; }
                        if (!ids.length) { Swal.fire({ icon: 'warning', title: 'No applications selected' }); return; }
                        Swal.fire({ icon: 'warning', title: 'Delete ' + ids.length + ' application(s)?', text: 'You can restore deleted items from history.', showCancelButton: true, confirmButtonText: 'Delete', confirmButtonColor: '#dc2626' }).then(function(r) {
                            if (!r.isConfirmed) return;
                            fetch('{{ route("employer.applications.bulk-delete") }}', { method: 'DELETE', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: JSON.stringify({ ids: ids }) })
                            .then(function(res) { return res.json(); }).then(function() { Swal.fire({ icon: 'success', title: 'Done', timer: 1800, showConfirmButton: false }).then(function() { window.location.reload(); }); })
                            .catch(function() { Swal.fire({ icon: 'error', title: 'Error' }); });
                        });
                    };
                }

                // Status update
                document.querySelectorAll('.app-status-action').forEach(function(btn) {
                    btn.onclick = function() {
                        var appId = btn.dataset.appId;
                        var newStatus = btn.dataset.status;

                        if (newStatus === 'in_progress') {
                            Swal.fire({
                                title: 'Schedule Interview',
                                html: `
                                    <div class="text-left space-y-4 pt-4">
                                        <div class="cd-form-group">
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Meeting Title</label>
                                            <input type="text" id="swal-title" class="cd-search !bg-gray-50 !border-gray-200" placeholder="e.g. Technical Interview">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="cd-form-group">
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Platform</label>
                                                <select id="swal-type" class="cd-toolbar-select !w-full !bg-gray-50 !border-gray-200">
                                                    <option value="video">Zoom Meeting</option>
                                                    <option value="video">Google Meet</option>
                                                    <option value="video">Microsoft Teams</option>
                                                    <option value="phone">Phone Call</option>
                                                    <option value="in_person">In Person</option>
                                                </select>
                                            </div>
                                            <div class="cd-form-group">
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Scheduled Date & Time</label>
                                                <input type="datetime-local" id="swal-time" class="cd-search !bg-gray-50 !border-gray-200">
                                            </div>
                                        </div>
                                        <div class="cd-form-group">
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Zoom / Meeting Link</label>
                                            <input type="url" id="swal-link" class="cd-search !bg-gray-50 !border-gray-200" placeholder="https://zoom.us/j/...">
                                        </div>
                                    </div>
                                `,
                                showCancelButton: true,
                                confirmButtonText: 'Schedule & Notify',
                                confirmButtonColor: 'var(--cd-primary)',
                                focusConfirm: false,
                                preConfirm: () => {
                                    const title = Swal.getPopup().querySelector('#swal-title').value;
                                    const itype = Swal.getPopup().querySelector('#swal-type').value;
                                    const time = Swal.getPopup().querySelector('#swal-time').value;
                                    const link = Swal.getPopup().querySelector('#swal-link').value;
                                    if (!time) {
                                        Swal.showValidationMessage(`Please select a time`)
                                    }
                                    return { interview_title: title, interview_type: itype, scheduled_at: time, meeting_link: link }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    updateStatus(appId, newStatus, result.value);
                                }
                            });
                        } else {
                            updateStatus(appId, newStatus);
                        }
                    };
                });

                function updateStatus(appId, status, extraData = {}) {
                    fetch('/employer/applications/' + appId + '/status', { 
                        method: 'PATCH', 
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                            'Accept': 'application/json' 
                        }, 
                        body: JSON.stringify({ status: status, ...extraData }) 
                    })
                    .then(function(res) { return res.json(); }).then(function() { 
                        const activePill = document.querySelector('.cd-tab-pill.active');
                        if (activePill) loadApplications(activePill.href);
                        else window.location.reload();
                    })
                    .catch(function() { Swal.fire({ icon: 'error', title: 'Error' }); });
                }
            }

            async function loadApplications(url) {
                container.classList.remove('cd-animate-fade-in');
                container.classList.add('cd-animate-fade-out', 'cd-loading-overlay');

                try {
                    const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('applications-container');
                    const newToolbar = doc.getElementById('wt-status-filters');

                    if (newContent && newToolbar) {
                        setTimeout(() => {
                            container.innerHTML = newContent.innerHTML;
                            document.getElementById('wt-status-filters').innerHTML = newToolbar.innerHTML;
                            
                            container.classList.remove('cd-animate-fade-out', 'cd-loading-overlay');
                            container.classList.add('cd-animate-fade-in');
                            
                            initializeListeners();
                            attachTabListeners();
                            
                            window.history.pushState({}, '', url);
                        }, 300);
                    }
                } catch (error) {
                    console.error('Error loading applications:', error);
                    window.location.href = url; // Fallback
                }
            }

            function attachTabListeners() {
                document.querySelectorAll('.cd-tab-pill').forEach(tab => {
                    tab.onclick = function(e) {
                        e.preventDefault();
                        if (this.classList.contains('active')) return;
                        loadApplications(this.href);
                    };
                });

                // Handle pagination links
                document.querySelectorAll('.cd-pagination a').forEach(link => {
                    link.onclick = function(e) {
                        e.preventDefault();
                        loadApplications(this.href);
                    };
                });
            }

            // Initial Init
            initializeListeners();
            attachTabListeners();

            // Handle back/forward buttons
            window.onpopstate = function() {
                loadApplications(window.location.href);
            };
        });
    </script>

    @include('candidates.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'wt-hero',           'title' => 'Applications Hub',      'icon' => 'ri-team-line',          'body' => 'Welcome to your Applications page! Here you can review, manage, and take action on all candidate applications across your job postings.', 'position' => 'bottom'],
            ['target' => 'wt-hero-actions',   'title' => 'Quick Navigation',      'icon' => 'ri-links-line',         'body' => 'Switch to Pipeline View for a Kanban-style board, or visit History to see archived and past applications.', 'position' => 'bottom'],
            ['target' => 'wt-toolbar',        'title' => 'Search & Bulk Actions', 'icon' => 'ri-tools-line',         'body' => 'Use the search bar to find specific candidates, or select multiple applications and apply bulk actions like deleting them.', 'position' => 'bottom'],
            ['target' => 'wt-status-filters', 'title' => 'Filter by Status',     'icon' => 'ri-filter-3-line',      'body' => 'Quickly filter applications by their current status — Applied, Under Review, Accept, or Declined. Click any tab to narrow down the list.', 'position' => 'bottom'],
            ['target' => 'wt-apps-table',     'title' => 'Applications Table',    'icon' => 'ri-table-fill',         'body' => 'Each row shows the candidate, the job they applied to, when they applied, and their current status. Use the dropdown in Actions to change status or decline.', 'position' => 'top'],
        ],
        'wtKey' => 'employer_applications_v2',
    ])

</x-app-layout>
