<x-app-layout>

    <x-slot name="pageTitle">History</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">History</x-slot>

    @include('employers.partials.employer-styles')

    @php
        $statusStyles = [
            'applied'       => ['bg'=>'#eef2ff','text'=>'#4f46e5','label'=>'Applied'],
            'submitted'     => ['bg'=>'#eef2ff','text'=>'#4f46e5','label'=>'Applied'],
            'under_review'  => ['bg'=>'#fefce8','text'=>'#ca8a04','label'=>'Under Review'],
            'application_viewed' => ['bg'=>'#e0f2fe','text'=>'#0284c7','label'=>'Viewed'],
            'viewed'        => ['bg'=>'#e0f2fe','text'=>'#0284c7','label'=>'Viewed'],
            'in_progress'   => ['bg'=>'#f5f3ff','text'=>'#7c3aed','label'=>'Interviewing'],
            'accepted'      => ['bg'=>'#f0fdf4','text'=>'#16a34a','label'=>'Accepted'],
            'not_selected'  => ['bg'=>'#fef2f2','text'=>'#dc2626','label'=>'Declined'],
            'no_longer_under_consideration' => ['bg'=>'#fef2f2','text'=>'#dc2626','label'=>'Declined'],
            'closed'        => ['bg'=>'#f9fafb','text'=>'#6b7280','label'=>'Closed'],
        ];
        $logoBgs = ['#4f46e5','#0d9488','#dc2626','#7c3aed','#ea580c','#0284c7'];
    @endphp

    <x-modern-header chip="History" title="Manage Your History" desc='Manage your history'>
        <x-slot name="actions">
            <a href="{{ route('employer.applications.index') }}" class="cd-hero-btn cd-hero-btn-primary"><i class="ri-team-line"></i> Applications</a>
            <a href="{{ route('employer.jobs.index') }}" class="cd-hero-btn cd-hero-btn-ghost"><i class="ri-briefcase-line"></i> Jobs</a>
        </x-slot>
    </x-modern-header>  

    <div class="grid grid-cols-12 gap-x-5 gap-y-4 mx-auto pb-6 sm:px-6 lg:px-8">

        {{-- ═══ Type Tabs ═══ --}}
        <div class="col-span-12" id="wt-type-tabs">
            <div class="cd-tabs-pilled">
                <a href="{{ route('employer.history.index') }}" 
                   class="cd-tab-pill {{ $type !== 'jobs' ? 'active' : '' }}">
                    <i class="ri-file-list-3-line"></i> Candidate Applications
                </a>
                <a href="{{ route('employer.history.index', ['type'=>'jobs']) }}" 
                   class="cd-tab-pill {{ $type === 'jobs' ? 'active' : '' }}">
                    <i class="ri-briefcase-line"></i> Job Postings
                </a>
            </div>
        </div>

        {{-- ═══ Applications History ═══ --}}
        @if($type !== 'jobs')
        <div class="col-span-12" id="wt-app-history">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-file-list-3-fill"></i> Application History</span>
                    @if($candidateHistory && $candidateHistory->total() > 0)
                        <span class="text-xs text-gray-400">{{ $candidateHistory->total() }} records</span>
                    @endif
                </div>

                @if (session('status'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (window.Swal) Swal.fire({ icon: 'success', title: 'History', text: @json(session('status')), timer: 2200, showConfirmButton: false });
                        });
                    </script>
                @endif

                @if(!$candidateHistory || $candidateHistory->isEmpty())
                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:5rem 1rem;text-align:center;">
                        <div style="width:80px;height:80px;border-radius:24px;background:#4f46e512;display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem;color:#4f46e5;font-size:2rem;animation:kb-float 3s ease-in-out infinite;box-shadow:0 8px 20px rgba(79,70,229,0.12)">
                            <i class="ri-history-line"></i>
                        </div>
                        <h3 style="font-size:1.15rem;font-weight:800;color:var(--kb-text-primary);margin-bottom:0.5rem">No application history</h3>
                        <p style="font-size:0.9rem;color:var(--kb-text-secondary);max-width:320px;margin-bottom:2rem">Archived candidate applications will appear here for your records.</p>
                        <a href="{{ route('employer.applications.index') }}" class="cd-btn cd-btn-primary">
                            <i class="ri-team-line"></i> View Active Applications
                        </a>
                    </div>
                @else
                    {{-- Toolbar --}}
                    <div class="cd-toolbar" style="border-bottom: 1px solid rgba(0,0,0,0.03); margin-bottom: 0; padding-bottom: 1.25rem;">
                        <div style="display:flex;align-items:center;gap:0.75rem">
                            <select id="app-bulk-select" class="cd-toolbar-select" style="min-width:140px">
                                <option value="">Bulk action</option>
                                <option value="restore">Restore Selected</option>
                                <option value="delete">Delete Permanent</option>
                            </select>
                            <button type="button" id="app-bulk-apply" class="cd-btn cd-btn-primary cd-btn-sm" style="height:38px"><i class="ri-check-double-line"></i> Apply</button>
                        </div>
                        <div class="cd-search-wrap" style="max-width:350px">
                            <span class="cd-search-icon"><i class="ri-search-line"></i></span>
                            <input type="text" id="app-search" class="cd-search" placeholder="Search by candidate, role, or company...">
                        </div>
                        <span id="app-selected-count" class="text-xs font-bold text-indigo-600 hidden" aria-live="polite"><i class="ri-checkbox-circle-line"></i> <span id="app-selected-num">0</span> selected</span>
                    </div>

                    <div class="table-responsive" style="overflow-x:auto">
                        <table class="cd-table">
                            <thead>
                            <tr>
                                <th style="width:40px; padding-left: 1.25rem"><input type="checkbox" id="app-select-all" class="form-check-input"></th>
                                <th>Candidate</th>
                                <th>Archive Destination</th>
                                <th>History Date</th>
                                <th>Status</th>
                                <th style="text-align:right;padding-right:1.25rem">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($candidateHistory as $idx => $app)
                                @php 
                                    $sc = $statusStyles[$app->status] ?? ['bg'=>'#f9fafb','text'=>'#6b7280','label'=>Str::headline($app->status)];
                                    $name = $app->applicantProfile?->display_name ?? $app->applicantProfile?->user?->name ?? 'Unknown';
                                    $initials = strtoupper(substr($name, 0, 2));
                                    $avatarBgs = ['#4f46e5','#0891b2','#7c3aed','#0d9488','#ea580c','#db2777'];
                                    $avatarBg = $avatarBgs[abs(crc32($name)) % count($avatarBgs)];
                                @endphp
                                <tr class="app-hist-row" style="transition: all 0.2s">
                                    <td style="padding-left: 1.25rem"><input type="checkbox" class="form-check-input app-row-checkbox" value="{{ $app->id }}"></td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:12px">
                                            <div style="width:36px;height:36px;border-radius:10px;background:{{ $avatarBg }};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:0.75rem;box-shadow:0 4px 10px {{ $avatarBg }}33; flex-shrink:0">
                                                {{ $initials }}
                                            </div>
                                            <div style="min-width:0">
                                                <div style="font-size:0.85rem;font-weight:700;color:var(--kb-text-primary);letter-spacing:-0.01em">{{ $name }}</div>
                                                <div style="font-size:0.7rem;color:var(--kb-text-secondary);display:flex;align-items:center;gap:4px">
                                                    <i class="ri-mail-line"></i> {{ $app->applicantProfile?->user?->email ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size:0.825rem;font-weight:700;color:var(--kb-text-primary)">{{ $app->jobPosting->title }}</div>
                                        <div style="font-size:0.7rem;color:var(--kb-text-secondary);display:flex;align-items:center;gap:4px">
                                            <i class="ri-building-line"></i> {{ $app->jobPosting->company?->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="text-xs font-semibold text-gray-400">
                                        <i class="ri-time-line"></i> {{ $app->applied_at?->format('M d, Y') ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="cd-status-pill" style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }}; font-size: 0.65rem; font-weight: 800; padding: 0.2rem 0.6rem">
                                            {{ $sc['label'] }}
                                        </span>
                                    </td>
                                    <td style="text-align:right;padding-right:1.25rem">
                                        <div style="display:flex;justify-content:flex-end;gap:0.5rem">
                                            <button type="button" class="app-restore-btn" data-app-id="{{ $app->id }}" 
                                                style="width:32px;height:32px;border-radius:8px;background:#f0fdf4;color:#16a34a;display:flex;align-items:center;justify-content:center;transition:all 0.2s"
                                                onmouseover="this.style.background='#16a34a';this.style.color='#fff'"
                                                onmouseout="this.style.background='#f0fdf4';this.style.color='#16a34a'"
                                                title="Restore Application">
                                                <i class="ri-refresh-line"></i>
                                            </button>
                                            <button type="button" class="app-perm-delete-btn" data-app-id="{{ $app->id }}"
                                                style="width:32px;height:32px;border-radius:8px;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;transition:all 0.2s"
                                                onmouseover="this.style.background='#dc2626';this.style.color='#fff'"
                                                onmouseout="this.style.background='#fef2f2';this.style.color='#dc2626'"
                                                title="Delete Permanently">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 pt-4 border-top border-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs text-gray-400">
                        <div class="font-semibold">Showing <span class="text-gray-900">{{ $candidateHistory->firstItem() }}</span> to <span class="text-gray-900">{{ $candidateHistory->lastItem() }}</span> of <span class="text-gray-900">{{ $candidateHistory->total() }}</span> results</div>
                        <div class="cd-pagination mt-2 sm:mt-0">{{ $candidateHistory->onEachSide(1)->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- ═══ Jobs History ═══ --}}
        @if($type === 'jobs')
        <div class="col-span-12" id="wt-job-history">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-briefcase-fill"></i> Job History</span>
                    @if($jobHistory && $jobHistory->total() > 0)
                        <span class="text-xs text-gray-400">{{ $jobHistory->total() }} records</span>
                    @endif
                </div>

                @if(!$jobHistory || $jobHistory->isEmpty())
                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:5rem 1rem;text-align:center;">
                        <div style="width:80px;height:80px;border-radius:24px;background:#10b98112;display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem;color:#10b981;font-size:2rem;animation:kb-float 3s ease-in-out infinite;box-shadow:0 8px 20px rgba(16,185,129,0.12)">
                            <i class="ri-briefcase-line"></i>
                        </div>
                        <h3 style="font-size:1.15rem;font-weight:800;color:var(--kb-text-primary);margin-bottom:0.5rem">No job history</h3>
                        <p style="font-size:0.9rem;color:var(--kb-text-secondary);max-width:320px;margin-bottom:2rem">Closed or archived job postings will appear here. Reopen them any time to start hiring again.</p>
                        <a href="{{ route('employer.jobs.index') }}" class="cd-btn cd-btn-primary">
                            <i class="ri-add-line"></i> View Active Jobs
                        </a>
                    </div>
                @else
                    {{-- Toolbar --}}
                    <div class="cd-toolbar" style="border-bottom: 1px solid rgba(0,0,0,0.03); margin-bottom: 0; padding-bottom: 1.25rem;">
                        <div style="display:flex;align-items:center;gap:0.75rem">
                            <select id="job-bulk-select" class="cd-toolbar-select" style="min-width:140px">
                                <option value="">Bulk action</option>
                                <option value="restore">Restore / Reopen</option>
                                <option value="delete">Delete Permanent</option>
                            </select>
                            <button type="button" id="job-bulk-apply" class="cd-btn cd-btn-primary cd-btn-sm" style="height:38px"><i class="ri-check-double-line"></i> Apply</button>
                        </div>
                        <div class="cd-search-wrap" style="max-width:350px">
                            <span class="cd-search-icon"><i class="ri-search-line"></i></span>
                            <input type="text" id="job-search" class="cd-search" placeholder="Search by job title or company...">
                        </div>
                        <span id="job-selected-count" class="text-xs font-bold text-indigo-600 hidden" aria-live="polite"><i class="ri-checkbox-circle-line"></i> <span id="job-selected-num">0</span> selected</span>
                    </div>

                    <div class="table-responsive" style="overflow-x:auto">
                        <table class="cd-table">
                            <thead>
                            <tr>
                                <th style="width:40px; padding-left: 1.25rem"><input type="checkbox" id="job-select-all" class="form-check-input"></th>
                                <th>Job Details</th>
                                <th>Company</th>
                                <th>Archive Date</th>
                                <th>Status</th>
                                <th style="text-align:right;padding-right:1.25rem">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($jobHistory as $job)
                                @php
                                    $jobInitials = strtoupper(substr($job->title, 0, 2));
                                    $jobBgs = ['#4f46e5','#0d9488','#dc2626','#7c3aed','#ea580c','#0284c7'];
                                    $jobBg = $jobBgs[abs(crc32($job->title)) % count($jobBgs)];
                                @endphp
                                <tr class="job-hist-row" style="transition: all 0.2s">
                                    <td style="padding-left: 1.25rem"><input type="checkbox" class="form-check-input job-row-checkbox" value="{{ $job->id }}"></td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:12px">
                                            <div style="width:36px;height:36px;border-radius:10px;background:{{ $jobBg }};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:0.75rem;box-shadow:0 4px 10px {{ $jobBg }}33; flex-shrink:0">
                                                {{ $jobInitials }}
                                            </div>
                                            <div style="min-width:0">
                                                <div style="font-size:0.85rem;font-weight:700;color:var(--kb-text-primary);letter-spacing:-0.01em">{{ $job->title }}</div>
                                                <div style="font-size:0.7rem;color:var(--kb-text-secondary);display:flex;align-items:center;gap:4px">
                                                    <i class="ri-map-pin-line"></i> {{ $job->location ?? 'Remote' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-sm font-semibold dark:text-white">{{ $job->company?->name ?? '—' }}</div>
                                    </td>
                                    <td class="text-xs font-semibold text-gray-400">
                                        <i class="ri-calendar-line"></i> {{ $job->posted_at?->format('M d, Y') ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="cd-status-pill" style="background:#f9fafb;color:#6b7280; font-size: 0.65rem; font-weight: 800; padding: 0.2rem 0.6rem">
                                            ARCHIVED
                                        </span>
                                    </td>
                                    <td style="text-align:right;padding-right:1.25rem">
                                        <div style="display:flex;justify-content:flex-end;gap:0.5rem">
                                            <button type="button" class="job-restore-btn" data-job-id="{{ $job->id }}" 
                                                style="width:32px;height:32px;border-radius:8px;background:#eef2ff;color:#4f46e5;display:flex;align-items:center;justify-content:center;transition:all 0.2s"
                                                onmouseover="this.style.background='#4f46e5';this.style.color='#fff'"
                                                onmouseout="this.style.background='#eef2ff';this.style.color='#4f46e5'"
                                                title="Reopen Job">
                                                <i class="ri-restart-line"></i>
                                            </button>
                                            <button type="button" class="job-perm-delete-btn" data-job-id="{{ $job->id }}"
                                                style="width:32px;height:32px;border-radius:8px;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;transition:all 0.2s"
                                                onmouseover="this.style.background='#dc2626';this.style.color='#fff'"
                                                onmouseout="this.style.background='#fef2f2';this.style.color='#dc2626'"
                                                title="Delete Permanently">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 pt-4 border-top border-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs text-gray-400">
                        <div class="font-semibold">Showing <span class="text-gray-900">{{ $jobHistory->firstItem() }}</span> to <span class="text-gray-900">{{ $jobHistory->lastItem() }}</span> of <span class="text-gray-900">{{ $jobHistory->total() }}</span> results</div>
                        <div class="cd-pagination mt-2 sm:mt-0">{{ $jobHistory->onEachSide(1)->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ── Application history ──
            var appSelectAll = document.getElementById('app-select-all');
            var appCheckboxes = document.querySelectorAll('.app-row-checkbox');
            var appBulkSelect = document.getElementById('app-bulk-select');
            var appBulkApply = document.getElementById('app-bulk-apply');
            var appSelectedCount = document.getElementById('app-selected-count');
            var appSelectedNum = document.getElementById('app-selected-num');
            var appSearch = document.getElementById('app-search');
            var appRows = Array.from(document.querySelectorAll('.app-hist-row'));

            if (appSearch) {
                appSearch.addEventListener('input', function() {
                    var s = appSearch.value.toLowerCase();
                    appRows.forEach(function(r) { r.style.display = r.textContent.toLowerCase().includes(s) ? '' : 'none'; });
                });
            }
            function updateAppCount() {
                var c = document.querySelectorAll('.app-row-checkbox:checked').length;
                if (c > 0) { appSelectedCount.classList.remove('hidden'); appSelectedNum.textContent = c; }
                else { appSelectedCount.classList.add('hidden'); }
            }
            if (appSelectAll) {
                appSelectAll.addEventListener('change', function() { appCheckboxes.forEach(function(cb) { cb.checked = appSelectAll.checked; }); updateAppCount(); });
            }
            appCheckboxes.forEach(function(cb) { cb.addEventListener('change', updateAppCount); });

            if (appBulkApply) {
                appBulkApply.addEventListener('click', function() {
                    var action = appBulkSelect.value;
                    var ids = Array.from(document.querySelectorAll('.app-row-checkbox:checked')).map(function(cb) { return cb.value; });
                    if (!action) { Swal.fire({ icon: 'warning', title: 'Select an action' }); return; }
                    if (!ids.length) { Swal.fire({ icon: 'warning', title: 'No items selected' }); return; }
                    var label = action === 'restore' ? 'Restore' : 'Delete';
                    Swal.fire({ icon: 'warning', title: label + ' ' + ids.length + ' application(s)?', showCancelButton: true, confirmButtonText: 'Yes', confirmButtonColor: action === 'delete' ? '#dc2626' : '#16a34a' }).then(function(r) {
                        if (!r.isConfirmed) return;
                        var url = action === 'restore' ? '{{ route("employer.applications.bulk-restore") }}' : '{{ route("employer.applications.bulk-delete") }}';
                        var method = action === 'delete' ? 'DELETE' : 'POST';
                        fetch(url, { method: method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: JSON.stringify({ ids: ids }) })
                        .then(function(res) { return res.json(); }).then(function() { Swal.fire({ icon: 'success', title: 'Done', timer: 1800, showConfirmButton: false }).then(function() { window.location.reload(); }); })
                        .catch(function() { Swal.fire({ icon: 'error', title: 'Error' }); });
                    });
                });
            }

            document.querySelectorAll('.app-restore-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    Swal.fire({ icon: 'question', title: 'Restore this application?', showCancelButton: true, confirmButtonText: 'Restore', confirmButtonColor: '#16a34a' }).then(function(r) {
                        if (!r.isConfirmed) return;
                        fetch('/employer/applications/' + btn.dataset.appId + '/restore', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
                        .then(function(res) { return res.json(); }).then(function() { window.location.reload(); })
                        .catch(function() { Swal.fire({ icon: 'error', title: 'Error' }); });
                    });
                });
            });

            document.querySelectorAll('.app-perm-delete-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    Swal.fire({ icon: 'warning', title: 'Delete application?', text: 'You can restore deleted items from history.', showCancelButton: true, confirmButtonText: 'Delete', confirmButtonColor: '#dc2626' }).then(function(r) {
                        if (!r.isConfirmed) return;
                        fetch('/employer/applications/' + btn.dataset.appId + '/delete', { method: 'DELETE', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
                        .then(function(res) { return res.json(); }).then(function() { window.location.reload(); })
                        .catch(function() { Swal.fire({ icon: 'error', title: 'Error' }); });
                    });
                });
            });

            // ── Job history ──
            var jobSelectAll = document.getElementById('job-select-all');
            var jobCheckboxes = document.querySelectorAll('.job-row-checkbox');
            var jobBulkSelect = document.getElementById('job-bulk-select');
            var jobBulkApply = document.getElementById('job-bulk-apply');
            var jobSelectedCount = document.getElementById('job-selected-count');
            var jobSelectedNum = document.getElementById('job-selected-num');
            var jobSearch = document.getElementById('job-search');
            var jobRows = Array.from(document.querySelectorAll('.job-hist-row'));

            if (jobSearch) {
                jobSearch.addEventListener('input', function() {
                    var s = jobSearch.value.toLowerCase();
                    jobRows.forEach(function(r) { r.style.display = r.textContent.toLowerCase().includes(s) ? '' : 'none'; });
                });
            }
            function updateJobCount() {
                var c = document.querySelectorAll('.job-row-checkbox:checked').length;
                if (c > 0) { jobSelectedCount.classList.remove('hidden'); jobSelectedNum.textContent = c; }
                else { jobSelectedCount.classList.add('hidden'); }
            }
            if (jobSelectAll) {
                jobSelectAll.addEventListener('change', function() { jobCheckboxes.forEach(function(cb) { cb.checked = jobSelectAll.checked; }); updateJobCount(); });
            }
            jobCheckboxes.forEach(function(cb) { cb.addEventListener('change', updateJobCount); });

            if (jobBulkApply) {
                jobBulkApply.addEventListener('click', function() {
                    var action = jobBulkSelect.value;
                    var ids = Array.from(document.querySelectorAll('.job-row-checkbox:checked')).map(function(cb) { return cb.value; });
                    if (!action) { Swal.fire({ icon: 'warning', title: 'Select an action' }); return; }
                    if (!ids.length) { Swal.fire({ icon: 'warning', title: 'No items selected' }); return; }
                    var label = action === 'restore' ? 'Restore' : 'Delete';
                    Swal.fire({ icon: 'warning', title: label + ' ' + ids.length + ' job(s)?', showCancelButton: true, confirmButtonText: 'Yes', confirmButtonColor: action === 'delete' ? '#dc2626' : '#16a34a' }).then(function(r) {
                        if (!r.isConfirmed) return;
                        var url = action === 'restore' ? '{{ route("employer.jobs.bulk-restore") }}' : '{{ route("employer.jobs.bulk-delete") }}';
                        var method = action === 'delete' ? 'DELETE' : 'POST';
                        fetch(url, { method: method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: JSON.stringify({ ids: ids }) })
                        .then(function(res) { return res.json(); }).then(function() { Swal.fire({ icon: 'success', title: 'Done', timer: 1800, showConfirmButton: false }).then(function() { window.location.reload(); }); })
                        .catch(function() { Swal.fire({ icon: 'error', title: 'Error' }); });
                    });
                });
            }

            document.querySelectorAll('.job-restore-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    Swal.fire({ icon: 'question', title: 'Reopen this job?', showCancelButton: true, confirmButtonText: 'Reopen', confirmButtonColor: '#16a34a' }).then(function(r) {
                        if (!r.isConfirmed) return;
                        fetch('/employer/jobs/' + btn.dataset.jobId + '/restore', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
                        .then(function(res) { return res.json(); }).then(function() { window.location.reload(); })
                        .catch(function() { Swal.fire({ icon: 'error', title: 'Error' }); });
                    });
                });
            });

            document.querySelectorAll('.job-perm-delete-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    Swal.fire({ icon: 'warning', title: 'Delete job?', text: 'You can restore deleted items from history.', showCancelButton: true, confirmButtonText: 'Delete', confirmButtonColor: '#dc2626' }).then(function(r) {
                        if (!r.isConfirmed) return;
                        fetch('/employer/jobs/' + btn.dataset.jobId + '/delete', { method: 'DELETE', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } })
                        .then(function(res) { return res.json(); }).then(function() { window.location.reload(); })
                        .catch(function() { Swal.fire({ icon: 'error', title: 'Error' }); });
                    });
                });
            });
        });
    </script>

    @include('candidates.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'wt-hero',        'title' => 'History Archive',         'icon' => 'ri-history-line',      'body' => 'Welcome to your History page! This is where archived applications and closed job postings are stored for your records.', 'position' => 'bottom'],
            ['target' => 'wt-type-tabs',   'title' => 'Toggle History Type',     'icon' => 'ri-toggle-line',       'body' => 'Switch between Candidate Applications history and Job Postings history using these tabs. Each has its own table and actions.', 'position' => 'bottom'],
            ['target' => 'wt-app-history', 'title' => 'Application Records',     'icon' => 'ri-file-list-3-fill',  'body' => 'View all archived candidate applications here. You can search, select with checkboxes, restore applications to active, or delete them.', 'position' => 'top'],
            ['target' => 'wt-job-history', 'title' => 'Job Posting Records',     'icon' => 'ri-briefcase-fill',    'body' => 'Closed and archived job postings appear here. Reopen a job to start accepting applications again, or delete it from your records.', 'position' => 'top'],
        ],
        'wtKey' => 'employer_history',
    ])

</x-app-layout>
