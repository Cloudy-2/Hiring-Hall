<x-app-layout>

    <x-slot name="url_1">{"link": "/candidate/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Application History</x-slot>

    @include('candidates.partials.candidate-styles')

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Page Hero ═══ --}}
        <div class="col-span-12" id="wt-hero">
        <div class="cd-page-hero">
                <div>
                    <h1 class="cd-page-hero-title"><i class="ri-history-line me-2"></i>Application History</h1>
                    <p class="cd-page-hero-sub">View your archived and past job applications</p>
                </div>
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
                    <a href="{{ route('candidate.applications.index') }}" class="cd-hero-btn cd-hero-btn-primary"><i class="ri-arrow-left-line"></i> Active Applications</a>
                    <button type="button" onclick="startWalkthrough()" class="cd-hero-btn cd-tour-btn"><i class="ri-rocket-2-fill"></i> Take a Tour</button>
                </div>
            </div>
        </div>

        {{-- ═══ Stats ═══ --}}
        <div class="col-span-12" id="wt-stats">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-bar-chart-fill"></i> Overview</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                        $hStats = [
                            ['label'=>'Total History','count'=>$applications->total(),'icon'=>'ri-history-line','color'=>'#4f46e5','bg'=>'#eef2ff'],
                            ['label'=>'Withdrawn','count'=>$withdrawnCount ?? 0,'icon'=>'ri-logout-circle-line','color'=>'#ca8a04','bg'=>'#fefce8'],
                            ['label'=>'Not Selected','count'=>$notSelectedCount ?? 0,'icon'=>'ri-close-circle-line','color'=>'#dc2626','bg'=>'#fef2f2'],
                            ['label'=>'Closed','count'=>$closedCount ?? 0,'icon'=>'ri-lock-line','color'=>'#6b7280','bg'=>'#f9fafb'],
                        ];
                    @endphp
                    @foreach($hStats as $s)
                        <div class="cd-pipe" style="background:{{ $s['bg'] }}">
                            <div class="cd-pipe-icon" style="background:{{ $s['color'] }}18;color:{{ $s['color'] }}"><i class="{{ $s['icon'] }}"></i></div>
                            <div class="cd-pipe-num" style="color:{{ $s['color'] }}">{{ $s['count'] }}</div>
                            <div class="cd-pipe-lbl">{{ $s['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ═══ History Table ═══ --}}
        <div class="col-span-12" id="wt-table">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-table-fill"></i> Application History</span>
                    <span class="text-xs text-gray-400">{{ $applications->total() }} total</span>
                </div>

                <div class="cd-toolbar" id="wt-toolbar">
                    <select id="bulk-action-select" class="cd-toolbar-select">
                        <option value="">Bulk action</option>
                        <option value="delete">Delete Selected</option>
                        <option value="restore">Restore Selected</option>
                    </select>
                    <button type="button" id="apply-bulk-action" class="cd-btn cd-btn-primary cd-btn-sm"><i class="ri-check-double-line me-1"></i> Apply</button>
                    <div class="cd-search-wrap">
                        <span class="cd-search-icon"><i class="ri-search-line"></i></span>
                        <input type="text" id="search-input" class="cd-search" placeholder="Search history...">
                    </div>
                    <span id="selected-count" class="text-xs text-gray-400 hidden" style="margin-left:auto">
                        <span id="selected-count-num">0</span> selected
                    </span>
                </div>

                @if($applications->isEmpty())
                    <div class="cd-empty">
                        <i class="ri-history-line"></i>
                        <p>You have no applications in your history yet.</p>
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
                                    $job = $application->jobPosting;
                                    $status = $application->status;
                                    $statusColors = [
                                        'withdrawn' => ['bg'=>'#fefce8','text'=>'#ca8a04','label'=>'Withdrawn'],
                                        'cancelled' => ['bg'=>'#fefce8','text'=>'#ca8a04','label'=>'Withdrawn'],
                                        'declined' => ['bg'=>'#fef2f2','text'=>'#dc2626','label'=>'Not Selected'],
                                        'rejected' => ['bg'=>'#fef2f2','text'=>'#dc2626','label'=>'Not Selected'],
                                        'not_selected' => ['bg'=>'#fef2f2','text'=>'#dc2626','label'=>'Not Selected'],
                                        'closed' => ['bg'=>'#f9fafb','text'=>'#6b7280','label'=>'Closed'],
                                        'expired' => ['bg'=>'#f9fafb','text'=>'#6b7280','label'=>'Closed'],
                                    ];
                                    $sc = $statusColors[$status] ?? ['bg'=>'#f9fafb','text'=>'#6b7280','label'=>Str::headline($status)];
                                @endphp
                                <tr class="applications-row">
                                    <td><input type="checkbox" class="form-check-input row-checkbox" value="{{ $application->id }}" data-title="{{ $job?->title ?? 'Application' }}"></td>
                                    <td>
                                        @if($job)
                                            <a href="{{ route('jobs.show', $job->slug) }}" class="text-sm font-semibold dark:text-white" style="text-decoration:none;color:inherit">{{ $job->title }}</a>
                                            <div class="text-xs text-gray-400">{{ $job->company?->name ?? 'Company' }}</div>
                                        @else
                                            <span class="text-sm text-gray-400">(Job no longer available)</span>
                                        @endif
                                    </td>
                                    <td>@if($application->applied_at) {{ $application->applied_at->format('M d, Y') }} @else - @endif</td>
                                    <td><span class="cd-status-pill" style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }}">{{ $sc['label'] }}</span></td>
                                    <td style="text-align:right;padding-right:1rem">
                                        <div style="display:inline-flex;gap:4px">
                                            @if($job)<a href="{{ route('jobs.show', $job->slug) }}" class="cd-btn cd-btn-outline cd-btn-sm" title="View"><i class="ri-eye-line"></i></a>@endif
                                            <button type="button" class="cd-btn cd-btn-danger cd-btn-sm history-delete-btn" data-delete-form-id="delete-history-{{ $application->id }}" data-job-title="{{ $job?->title ?? 'this application' }}" title="Delete"><i class="ri-delete-bin-6-line"></i></button>
                                            <form id="delete-history-{{ $application->id }}" method="POST" action="{{ route('candidate.applications.history.destroy', $application->id) }}" class="hidden">@csrf @method('DELETE')</form>
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
                searchInput.addEventListener('input', function() { var s=searchInput.value.toLowerCase(); tableRows.forEach(function(r){r.style.display=r.textContent.toLowerCase().includes(s)?'':'none';}); });
            }
            function updateSelectedCount() { var c=document.querySelectorAll('.row-checkbox:checked').length; if(c>0){selectedCountEl.classList.remove('hidden');selectedCountNum.textContent=c;}else{selectedCountEl.classList.add('hidden');} }
            if (selectAllCheckbox) { selectAllCheckbox.addEventListener('change', function(){rowCheckboxes.forEach(function(cb){cb.checked=selectAllCheckbox.checked;});updateSelectedCount();}); }
            rowCheckboxes.forEach(function(cb){cb.addEventListener('change',function(){if(selectAllCheckbox)selectAllCheckbox.checked=document.querySelectorAll('.row-checkbox:checked').length===rowCheckboxes.length;updateSelectedCount();});});

            if (applyBulkAction) {
                applyBulkAction.addEventListener('click', function() {
                    var action = bulkActionSelect.value;
                    var ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(function(cb){return cb.value;});
                    if (!action) { Swal.fire({icon:'warning',title:'Select an action'}); return; }
                    if (ids.length===0) { Swal.fire({icon:'warning',title:'No applications selected'}); return; }
                    Swal.fire({icon:'warning',title:(action==='delete'?'Delete ':'Restore ')+ids.length+' application(s)?',showCancelButton:true,confirmButtonText:'Yes, proceed',confirmButtonColor:action==='delete'?'#dc2626':'#3b82f6'}).then(function(result){
                        if(result.isConfirmed){
                            var url = action==='delete' ? '{{ route("candidate.applications.history.bulk-delete") }}' : '{{ route("candidate.applications.history.bulk-restore") }}';
                            fetch(url,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body:JSON.stringify({ids:ids})})
                            .then(function(r){return r.json();}).then(function(){Swal.fire({icon:'success',title:'Done',timer:1800,showConfirmButton:false}).then(function(){window.location.reload();});})
                            .catch(function(){Swal.fire({icon:'error',title:'Error',text:'Failed to process.'});});
                        }
                    });
                });
            }

            document.querySelectorAll('.history-delete-btn').forEach(function(btn){
                btn.addEventListener('click',function(){
                    var formId=btn.getAttribute('data-delete-form-id'),title=btn.getAttribute('data-job-title')||'this application';
                    if(window.Swal){Swal.fire({icon:'warning',title:'Delete application?',text:'Delete "'+title+'" from history? You can restore it later.',showCancelButton:true,confirmButtonText:'Yes, delete it',confirmButtonColor:'#dc2626'}).then(function(r){if(r.isConfirmed&&formId){var f=document.getElementById(formId);if(f)f.submit();}});}
                    else if(formId){var f=document.getElementById(formId);if(f)f.submit();}
                });
            });
        });
    </script>

    @include('candidates.partials.walkthrough', [
        'wtKey' => 'app_history',
        'wtSteps' => [
            ['target' => 'wt-hero', 'icon' => 'ri-history-line', 'title' => 'Application History', 'body' => 'This page stores all your past, withdrawn, and closed applications. Use "Active Applications" to go back to your current ones.', 'position' => 'bottom'],
            ['target' => 'wt-stats', 'icon' => 'ri-bar-chart-fill', 'title' => 'History Overview', 'body' => 'A quick breakdown of your archived applications — total history, withdrawn, not selected, and closed positions.', 'position' => 'bottom'],
            ['target' => 'wt-toolbar', 'icon' => 'ri-tools-fill', 'title' => 'Search & Bulk Actions', 'body' => 'Search through your history or use bulk actions to delete or restore multiple applications at once.', 'position' => 'bottom'],
            ['target' => 'wt-table', 'icon' => 'ri-table-fill', 'title' => 'History Table', 'body' => 'Your complete application history with timestamps and final statuses. You can view job details, delete entries, or restore them later.', 'position' => 'top'],
        ]
    ])

</x-app-layout>
