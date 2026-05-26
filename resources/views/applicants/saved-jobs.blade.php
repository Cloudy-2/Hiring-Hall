<x-app-layout>

    <x-slot name="url_1">{"link": "/applicant/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="title">Bookmarked Jobs</x-slot>
    <x-slot name="active">Saved Jobs</x-slot>

    @include('applicants.partials.candidate-styles')

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Page Hero ═══ --}}
        <div class="col-span-12">
            <div class="cd-page-hero">
                <div>
                    <h1 class="cd-page-hero-title"><i class="ri-bookmark-fill me-2"></i>Saved Jobs</h1>
                    <p class="cd-page-hero-sub">Jobs you've bookmarked for later review</p>
                </div>
                <a href="{{ route('jobs') }}" class="cd-hero-btn cd-hero-btn-primary"><i class="ri-search-line"></i> Browse Jobs</a>
            </div>
        </div>

        @if (session('status'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (window.Swal) {
                        const msg = @json(session('status'));
                        const isDel = msg.toLowerCase().includes('removed') || msg.toLowerCase().includes('deleted');
                        Swal.fire({
                            icon: 'success',
                            title: isDel ? 'Deleted Job' : 'Saved Job',
                            text: msg,
                            timer: 2200,
                            showConfirmButton: false
                        });
                    }
                });
            </script>
        @endif

        {{-- ═══ Saved Jobs List ═══ --}}
        <div class="col-span-12">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-bookmark-fill"></i> Your Saved Jobs</span>
                    <span class="text-xs text-gray-400">{{ $savedJobs->total() }} saved</span>
                </div>

                @if($savedJobs->isEmpty())
                    <div class="cd-empty">
                        <i class="ri-bookmark-line"></i>
                        <p>You haven't saved any jobs yet.</p>
                        <a href="{{ route('jobs') }}" class="cd-btn cd-btn-primary"><i class="ri-search-line me-1"></i> Find Jobs</a>
                    </div>
                @else
                    {{-- Toolbar --}}
                    <div class="cd-toolbar">
                        <input type="checkbox" id="select-all" class="form-check-input" style="margin-right:4px">
                        <select id="bulk-action-select" class="cd-toolbar-select">
                            <option value="">Bulk action</option>
                            <option value="remove">Remove Selected</option>
                            <option value="apply">Apply to Selected</option>
                        </select>
                        <button type="button" id="apply-bulk-action" class="cd-btn cd-btn-primary cd-btn-sm"><i class="ri-check-double-line me-1"></i> Apply</button>
                        <div class="cd-search-wrap">
                            <span class="cd-search-icon"><i class="ri-search-line"></i></span>
                            <input type="text" id="search-input" class="cd-search" placeholder="Search saved jobs...">
                        </div>
                        <span id="selected-count" class="text-xs text-gray-400 hidden" style="margin-left:auto"><span id="selected-count-num">0</span> selected</span>
                    </div>

                    <div id="saved-jobs-list" class="space-y-2">
                        @php $logoBgs = ['#4f46e5','#0d9488','#dc2626','#7c3aed','#ea580c','#0284c7']; @endphp
                        @foreach($savedJobs as $saved)
                            @php($job = $saved->jobPosting)
                            @if(!$job) @continue @endif
                            @php($status = $applicationsByJobId[$job->id] ?? null)
                            <div class="saved-job-item flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:border-indigo-100 transition-all"
                                 data-title="{{ Str::lower($job->title) }}"
                                 data-company="{{ Str::lower($job->company?->name ?? '') }}"
                                 data-date="{{ optional($saved->saved_at)->timestamp ?? 0 }}"
                                 style="transition:all 0.15s"
                                 onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">

                                <input type="checkbox" class="form-check-input row-checkbox" value="{{ $job->id }}" data-title="{{ $job->title }}" data-applied="{{ $status ? '1' : '0' }}">

                                <div style="width:40px;height:40px;border-radius:10px;background:{{ $logoBgs[$loop->index % count($logoBgs)] }};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.8rem;flex-shrink:0;overflow:hidden">
                                    @if($job->company?->logo_url)
                                        <img src="{{ $job->company->logo_url }}" alt="{{ $job->company->name }}" style="width:100%;height:100%;object-fit:cover">
                                    @else
                                        {{ strtoupper(substr($job->company?->name ?? 'C', 0, 2)) }}
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="text-sm font-semibold dark:text-white truncate block" style="text-decoration:none;color:inherit">{{ $job->title }}</a>
                                    <div class="text-xs text-gray-400">
                                        {{ $job->company?->name ?? 'Company' }}
                                        @if($job->location) · {{ $job->location }} @endif
                                        @if($saved->saved_at) · Saved {{ $saved->saved_at->diffForHumans() }} @endif
                                    </div>
                                </div>

                                @if($status)
                                    <span class="cd-status-pill" style="background:#f0fdf4;color:#16a34a">{{ Str::headline($status) }}</span>
                                @endif

                                <div style="display:flex;gap:4px;flex-shrink:0">
                                    @if($status)
                                        <a href="{{ route('applicant.applications.index') }}" class="cd-btn cd-btn-outline cd-btn-sm" title="View application"><i class="ri-eye-line"></i></a>
                                    @else
                                        <a href="{{ route('jobs.show', $job->slug) }}" class="cd-btn cd-btn-primary cd-btn-sm" title="Apply now"><i class="ri-briefcase-line"></i></a>
                                    @endif
                                    <button type="button" class="cd-btn cd-btn-danger cd-btn-sm saved-job-remove-btn" data-remove-form-id="remove-saved-job-{{ $job->id }}" data-job-title="{{ $job->title }}" title="Remove"><i class="ri-delete-bin-6-line"></i></button>
                                    <form id="remove-saved-job-{{ $job->id }}" method="POST" action="{{ route('jobs.save', $job->slug) }}" class="hidden">@csrf <input type="hidden" name="redirect" value="saved-jobs"></form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="cd-pagination mt-4">{{ $savedJobs->links() }}</div>
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
            var items = document.querySelectorAll('#saved-jobs-list .saved-job-item');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    var q = searchInput.value.toLowerCase();
                    items.forEach(function(el) {
                        var t = el.getAttribute('data-title')||'', c = el.getAttribute('data-company')||'';
                        el.style.display = (!q || t.indexOf(q)!==-1 || c.indexOf(q)!==-1) ? '' : 'none';
                    });
                });
            }
            function updateSelectedCount() { var c=document.querySelectorAll('.row-checkbox:checked').length; if(c>0){selectedCountEl.classList.remove('hidden');selectedCountNum.textContent=c;}else{selectedCountEl.classList.add('hidden');} }
            if (selectAllCheckbox) { selectAllCheckbox.addEventListener('change', function(){rowCheckboxes.forEach(function(cb){cb.checked=selectAllCheckbox.checked;});updateSelectedCount();}); }
            rowCheckboxes.forEach(function(cb){cb.addEventListener('change',function(){if(selectAllCheckbox)selectAllCheckbox.checked=document.querySelectorAll('.row-checkbox:checked').length===rowCheckboxes.length;updateSelectedCount();});});

            if (applyBulkAction) {
                applyBulkAction.addEventListener('click', function() {
                    var action = bulkActionSelect.value;
                    var checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                    var ids = Array.from(checkedBoxes).map(function(cb){return cb.value;});
                    if (!action) { Swal.fire({icon:'warning',title:'Select an action'}); return; }
                    if (ids.length===0) { Swal.fire({icon:'warning',title:'No jobs selected'}); return; }

                    if (action === 'remove') {
                        Swal.fire({icon:'warning',title:'Remove '+ids.length+' saved job(s)?',showCancelButton:true,confirmButtonText:'Yes, remove',confirmButtonColor:'#dc2626'}).then(function(result){
                            if(result.isConfirmed){
                                fetch('{{ route("applicant.saved-jobs.bulk-remove") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body:JSON.stringify({ids:ids})})
                                .then(function(r){return r.json();}).then(function(){Swal.fire({icon:'success',title:'Removed',timer:1800,showConfirmButton:false}).then(function(){window.location.reload();});})
                                .catch(function(){Swal.fire({icon:'error',title:'Error',text:'Failed to remove jobs.'});});
                            }
                        });
                    } else if (action === 'apply') {
                        var applyableIds = Array.from(checkedBoxes).filter(function(cb){return cb.getAttribute('data-applied')!=='1';}).map(function(cb){return cb.value;});
                        if (applyableIds.length===0) { Swal.fire({icon:'info',title:'Already Applied'}); return; }
                        Swal.fire({icon:'question',title:'Apply to '+applyableIds.length+' job(s)?',showCancelButton:true,confirmButtonText:'Yes, apply',confirmButtonColor:'#3b82f6'}).then(function(result){
                            if(result.isConfirmed){
                                fetch('{{ route("applicant.saved-jobs.bulk-apply") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body:JSON.stringify({ids:applyableIds})})
                                .then(function(r){return r.json();}).then(function(){Swal.fire({icon:'success',title:'Applied',timer:1800,showConfirmButton:false}).then(function(){window.location.reload();});})
                                .catch(function(){Swal.fire({icon:'error',title:'Error',text:'Failed to submit.'});});
                            }
                        });
                    }
                });
            }

            document.querySelectorAll('.saved-job-remove-btn').forEach(function(btn){
                btn.addEventListener('click',function(){
                    var formId=btn.getAttribute('data-remove-form-id'),title=btn.getAttribute('data-job-title')||'this job';
                    if(window.Swal){Swal.fire({icon:'warning',title:'Remove saved job?',text:'Remove "'+title+'" from saved?',showCancelButton:true,confirmButtonText:'Yes, remove it',confirmButtonColor:'#dc2626'}).then(function(r){if(r.isConfirmed&&formId){var f=document.getElementById(formId);if(f)f.submit();}});}
                    else if(formId){var f=document.getElementById(formId);if(f)f.submit();}
                });
            });
        });
    </script>

</x-app-layout>
