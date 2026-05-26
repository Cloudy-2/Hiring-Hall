    <x-app-layout>

    <x-slot name="url_1">{"link": "/candidate/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="title">My Applications</x-slot>
    <x-slot name="active">My Applications</x-slot>

    @include('candidates.partials.candidate-styles')

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Page Hero ═══ --}}
        <div class="col-span-12" id="wt-hero">
            <div class="cd-page-hero">
                <div>
                    <h1 class="cd-page-hero-title"><i class="ri-file-list-3-line me-2"></i>My Applications</h1>
                    <p class="cd-page-hero-sub">Track and manage all your job applications</p>
                </div>
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
                    <a href="{{ route('jobs') }}" class="cd-hero-btn cd-hero-btn-primary"><i class="ri-search-line"></i> Find Jobs</a>
                    <a href="{{ route('candidate.applications.history') }}" class="cd-hero-btn cd-hero-btn-ghost"><i class="ri-history-line"></i> View History</a>
                    <button type="button" onclick="startWalkthrough()" class="cd-hero-btn cd-tour-btn"><i class="ri-rocket-2-fill"></i> Take a Tour</button>
                </div>
            </div>
        </div>

        {{-- ═══ Stats Cards ═══ --}}
        <div class="col-span-12" id="wt-stats">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-bar-chart-fill"></i> Overview</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3">
                    @php
                        $stats = [
                            ['label'=>'Total Applied','count'=>$totalApplied ?? $applications->total(),'icon'=>'ri-file-list-3-line','color'=>'#4f46e5','bg'=>'#eef2ff'],
                            ['label'=>'Review','count'=>$underReviewCount ?? 0,'icon'=>'ri-time-line','color'=>'#ca8a04','bg'=>'#fefce8'],
                            ['label'=>'Viewed','count'=>$viewedCount ?? 0,'icon'=>'ri-eye-line','color'=>'#0284c7','bg'=>'#e0f2fe'],
                            ['label'=>'Accept','count'=>$acceptedCount ?? 0,'icon'=>'ri-checkbox-circle-line','color'=>'#16a34a','bg'=>'#f0fdf4'],
                            ['label'=>'Declined','count'=>$declinedCount ?? 0,'icon'=>'ri-close-circle-line','color'=>'#dc2626','bg'=>'#fef2f2'],
                            ['label'=>'Closed','count'=>$closedCount ?? 0,'icon'=>'ri-lock-line','color'=>'#6b7280','bg'=>'#f9fafb'],
                        ];
                    @endphp
                    @foreach($stats as $s)
                        <div class="cd-pipe" style="background:{{ $s['bg'] }}">
                            <div class="cd-pipe-icon" style="background:{{ $s['color'] }}18;color:{{ $s['color'] }}"><i class="{{ $s['icon'] }}"></i></div>
                            <div class="cd-pipe-num" style="color:{{ $s['color'] }}">{{ $s['count'] }}</div>
                            <div class="cd-pipe-lbl">{{ $s['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ═══ Applications Table ═══ --}}
        <div class="col-span-12" id="wt-table">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-table-fill"></i> All Applications</span>
                    <span class="text-xs text-gray-400">{{ $applications->total() }} total</span>
                </div>

                @if (session('status'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (window.Swal) {
                                Swal.fire({ icon: 'success', title: 'My Applications', text: @json(session('status')), timer: 2200, showConfirmButton: false });
                            }
                        });
                    </script>
                @endif

                {{-- Toolbar --}}
                <div class="cd-toolbar" id="wt-toolbar">
                    <select id="bulk-action-select" class="cd-toolbar-select">
                        <option value="">Bulk action</option>
                        <option value="withdraw">Withdraw Selected</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="button" id="apply-bulk-action" class="cd-btn cd-btn-primary cd-btn-sm"><i class="ri-check-double-line me-1"></i> Apply</button>
                    <div class="cd-search-wrap">
                        <span class="cd-search-icon"><i class="ri-search-line"></i></span>
                        <input type="text" id="search-input" class="cd-search" placeholder="Search applications...">
                    </div>
                    <span id="selected-count" class="text-xs text-gray-400 hidden" style="margin-left:auto">
                        <span id="selected-count-num">0</span> selected
                    </span>
                </div>

                @if($applications->isEmpty())
                    <div class="cd-empty">
                        <i class="ri-file-list-3-line"></i>
                        <p>You have not applied to any jobs yet.</p>
                        <a href="{{ route('jobs') }}" class="cd-btn cd-btn-primary"><i class="ri-search-line me-1"></i> Browse Jobs</a>
                    </div>
                @else
                    <div class="table-responsive" style="border-radius:8px;overflow:hidden">
                        <table class="cd-table">
                            <thead>
                            <tr>
                                <th style="width:40px"><input type="checkbox" id="select-all" class="form-check-input"></th>
                                <th>Job Title &amp; Company</th>
                                <th>Date Applied</th>
                                <th>Status</th>
                                <th style="text-align:right;padding-right:1rem">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($applications as $application)
                                @php
                                    $status = $application->status;
                                    $statusColors = [
                                        'applied' => ['bg'=>'#eef2ff','text'=>'#4f46e5','label'=>'Applied'],
                                        'submitted' => ['bg'=>'#eef2ff','text'=>'#4f46e5','label'=>'Applied'],
                                        'under_review' => ['bg'=>'#fefce8','text'=>'#ca8a04','label'=>'Review'],
                                        'application_viewed' => ['bg'=>'#e0f2fe','text'=>'#0284c7','label'=>'Viewed'],
                                        'viewed' => ['bg'=>'#e0f2fe','text'=>'#0284c7','label'=>'Viewed'],
                                        'accepted' => ['bg'=>'#f0fdf4','text'=>'#16a34a','label'=>'Accept'],
                                        'not_selected' => ['bg'=>'#fef2f2','text'=>'#dc2626','label'=>'Declined'],
                                        'no_longer_under_consideration' => ['bg'=>'#fef2f2','text'=>'#dc2626','label'=>'Declined'],
                                        'closed' => ['bg'=>'#f9fafb','text'=>'#6b7280','label'=>'Closed'],
                                    ];
                                    $sc = $statusColors[$status] ?? ['bg'=>'#f9fafb','text'=>'#6b7280','label'=> ($status ? Str::headline($status) : 'Unknown')];
                                @endphp
                                <tr class="applications-row">
                                    <td><input type="checkbox" class="form-check-input row-checkbox" value="{{ $application->id }}" data-title="{{ $application->jobPosting->title }}"></td>
                                    <td>
                                        <a href="{{ route('jobs.show', $application->jobPosting->slug) }}" class="text-sm font-semibold dark:text-white" style="text-decoration:none;color:inherit">{{ $application->jobPosting->title }}</a>
                                        <div class="text-xs text-gray-400">{{ $application->jobPosting->company?->name ?? 'Company' }}</div>
                                    </td>
                                    <td>
                                        @if($application->applied_at)
                                            {{ $application->applied_at->format('M d, Y') }}
                                        @else - @endif
                                    </td>
                                    <td><span class="cd-status-pill" style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }}">{{ $sc['label'] }}</span></td>
                                    <td style="text-align:right;padding-right:1rem">
                                        <div style="display:inline-flex;gap:4px">
                                            <a href="{{ route('jobs.show', $application->jobPosting->slug) }}" class="cd-btn cd-btn-outline cd-btn-sm" title="View"><i class="ri-eye-line"></i></a>
                                            <button type="button" class="cd-btn cd-btn-danger cd-btn-sm application-cancel-btn" data-cancel-form-id="cancel-application-{{ $application->id }}" data-job-title="{{ $application->jobPosting->title }}" title="Cancel"><i class="ri-delete-bin-6-line"></i></button>
                                            <form id="cancel-application-{{ $application->id }}" method="POST" action="{{ route('candidate.applications.destroy', $application) }}" class="hidden">@csrf @method('DELETE')</form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs text-gray-400">
                        <div>
                            @if($applications->total() > 0)
                                Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} of {{ $applications->total() }} results
                            @else Showing 0 results @endif
                        </div>
                        <div class="cd-pagination">{{ $applications->onEachSide(1)->links() }}</div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var selectAllCheckbox = document.getElementById('select-all');
            var rowCheckboxes = document.querySelectorAll('.row-checkbox');
            var bulkActionSelect = document.getElementById('bulk-action-select');
            var applyBulkAction = document.getElementById('apply-bulk-action');
            var selectedCountEl = document.getElementById('selected-count');
            var selectedCountNum = document.getElementById('selected-count-num');
            var searchInput = document.getElementById('search-input');
            var tableBody = document.querySelector('table tbody');
            var tableRows = tableBody ? Array.from(tableBody.querySelectorAll('tr')) : [];

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    var s = searchInput.value.toLowerCase();
                    tableRows.forEach(function(r) { r.style.display = r.textContent.toLowerCase().includes(s) ? '' : 'none'; });
                });
            }

            function updateSelectedCount() {
                var c = document.querySelectorAll('.row-checkbox:checked').length;
                if (c > 0) { selectedCountEl.classList.remove('hidden'); selectedCountNum.textContent = c; } else { selectedCountEl.classList.add('hidden'); }
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() { rowCheckboxes.forEach(function(cb) { cb.checked = selectAllCheckbox.checked; }); updateSelectedCount(); });
            }
            rowCheckboxes.forEach(function(cb) { cb.addEventListener('change', function() { if (selectAllCheckbox) selectAllCheckbox.checked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length; updateSelectedCount(); }); });

            if (applyBulkAction) {
                applyBulkAction.addEventListener('click', function() {
                    var action = bulkActionSelect.value;
                    var ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(function(cb) { return cb.value; });
                    if (!action) { Swal.fire({ icon: 'warning', title: 'Select an action', text: 'Please select a bulk action first.' }); return; }
                    if (ids.length === 0) { Swal.fire({ icon: 'warning', title: 'No applications selected', text: 'Please select at least one application.' }); return; }
                    Swal.fire({ icon: 'warning', title: (action==='withdraw'?'Withdraw ':'Delete ')+ids.length+' application(s)?', showCancelButton: true, confirmButtonText: 'Yes, proceed', confirmButtonColor: '#dc2626' }).then(function(result) {
                        if (result.isConfirmed) {
                            var url = action === 'withdraw' ? '{{ route("candidate.applications.bulk-withdraw") }}' : '{{ route("candidate.applications.bulk-delete") }}';
                            fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: JSON.stringify({ ids: ids }) })
                            .then(function(r) { return r.json(); }).then(function() { Swal.fire({ icon: 'success', title: 'Done', timer: 1800, showConfirmButton: false }).then(function() { window.location.reload(); }); })
                            .catch(function() { Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to process applications.' }); });
                        }
                    });
                });
            }

            document.querySelectorAll('.application-cancel-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var formId = btn.getAttribute('data-cancel-form-id'), title = btn.getAttribute('data-job-title') || 'this job';
                    if (window.Swal) {
                        Swal.fire({ icon: 'warning', title: 'Cancel application?', text: 'Cancel your application for "'+title+'"?', showCancelButton: true, confirmButtonText: 'Yes, cancel it', confirmButtonColor: '#dc2626' })
                        .then(function(r) { if (r.isConfirmed && formId) { var f=document.getElementById(formId); if(f) f.submit(); } });
                    } else if (formId) { var f=document.getElementById(formId); if(f) f.submit(); }
                });
            });
        });
    </script>

    @include('candidates.partials.walkthrough', [
        'wtKey' => 'applications',
        'wtSteps' => [
            ['target' => 'wt-hero', 'icon' => 'ri-file-list-3-line', 'title' => 'My Applications', 'body' => 'This page shows all your active job applications. Use the hero buttons to find new jobs or review your application history.', 'position' => 'bottom'],
            ['target' => 'wt-stats', 'icon' => 'ri-bar-chart-fill', 'title' => 'Application Overview', 'body' => 'Get a quick snapshot of your application statuses — total applied, review, viewed, accept, declined, and closed. These update in real time.', 'position' => 'bottom'],
            ['target' => 'wt-toolbar', 'icon' => 'ri-tools-fill', 'title' => 'Toolbar & Bulk Actions', 'body' => 'Use the toolbar to search your applications, select multiple rows, and perform bulk actions like withdrawing or deleting applications at once.', 'position' => 'bottom'],
            ['target' => 'wt-table', 'icon' => 'ri-table-fill', 'title' => 'Applications Table', 'body' => 'Your complete list of applications with job title, company, date applied, and current status. Click the eye icon to view details or the bin icon to cancel an application.', 'position' => 'top'],
        ]
    ])

</x-app-layout>
