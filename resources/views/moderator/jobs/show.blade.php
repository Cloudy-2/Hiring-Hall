<x-app-layout
    page-title="Job Details"
    :breadcrumbs="[
            ['label' => 'Job Moderation', 'url' => route('moderator.jobs.index')],
        ]"
    active="{{ $job->title }}"
>>

    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header flex items-center justify-between">
                    <h2 class="box-title m-0">Job Details</h2>
                    <div class="flex items-center gap-2">
                        @if($job->is_flagged)
                            <span class="badge bg-orange-500/10 text-orange-500" aria-label="Status: Flagged">
                                <i class="ri-flag-fill me-1" aria-hidden="true"></i> Flagged
                            </span>
                        @endif
                        @if($job->moderation_status === 'approved')
                            <span class="badge bg-success/10 text-success" aria-label="Moderation: Approved">
                                <i class="ri-checkbox-circle-line me-1" aria-hidden="true"></i> Approved
                            </span>
                        @elseif($job->moderation_status === 'pending')
                            <span class="badge bg-warning/10 text-warning" aria-label="Moderation: Pending Review">
                                <i class="ri-time-line me-1" aria-hidden="true"></i> Pending Review
                            </span>
                        @else
                            <span class="badge bg-danger/10 text-danger" aria-label="Moderation: Rejected">
                                <i class="ri-close-circle-line me-1" aria-hidden="true"></i> Rejected
                            </span>
                        @endif
                    </div>
                </div>
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="status" aria-live="polite">{{ session('status') }}</div>
                    @endif

                    @if($job->is_flagged && $job->flag_reason)
                        <div class="alert alert-warning mb-4" role="alert">
                            <strong><i class="ri-flag-fill me-1" aria-hidden="true"></i> Flag reason:</strong> {{ $job->flag_reason }}
                        </div>
                    @endif

                    @if($job->moderation_notes && $job->moderation_status === 'rejected')
                        <div class="alert alert-danger mb-4" role="alert">
                            <strong>Rejection reason:</strong> {{ $job->moderation_notes }}
                        </div>
                    @endif

                    <h2 class="text-xl font-bold mb-2">{{ $job->title }}</h2>
                    <p class="text-textmuted mb-4">{{ $job->location ?? 'Remote' }} | {{ $job->employment_type ?? 'Full-time' }}</p>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <label class="text-xs text-textmuted">Category</label>
                            <p class="font-medium">{{ $job->category ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Industry</label>
                            <p class="font-medium">{{ $job->industry_type ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Remote Type</label>
                            <p class="font-medium">{{ $job->remote_type ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Vacancies</label>
                            <p class="font-medium">{{ $job->vacancies ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Salary Range</label>
                            <p class="font-medium">
                                @if($job->salary_min || $job->salary_max)
                                    {{ $job->salary_currency ?? 'PHP' }} {{ number_format($job->salary_min ?? 0) }} - {{ number_format($job->salary_max ?? 0) }}
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Experience</label>
                            <p class="font-medium">
                                @if($job->experience_min_years || $job->experience_max_years)
                                    {{ $job->experience_min_years ?? 0 }} - {{ $job->experience_max_years ?? 0 }} years
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Job Status</label>
                            <p class="font-medium">
                                <span class="badge bg-{{ $job->status === 'active' ? 'success' : 'secondary' }}/10 text-{{ $job->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Posted Date</label>
                            <p class="font-medium">{{ $job->posted_at?->format('M d, Y') ?? $job->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    @if($job->summary)
                    <div class="mb-4">
                        <h4 class="font-semibold mb-2">Summary</h4>
                        <p class="text-textmuted">{{ $job->summary }}</p>
                    </div>
                    @endif

                    @if($job->description)
                    <div class="mb-4">
                        <h4 class="font-semibold mb-2">Description</h4>
                        <div class="prose dark:prose-invert max-w-none text-sm">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>
                    @endif

                    @if($job->requirements)
                    <div class="mb-4">
                        <h4 class="font-semibold mb-2">Requirements</h4>
                        <div class="prose dark:prose-invert max-w-none text-sm">
                            {!! nl2br(e($job->requirements)) !!}
                        </div>
                    </div>
                    @endif

                    @if($job->responsibilities)
                    <div class="mb-4">
                        <h4 class="font-semibold mb-2">Responsibilities</h4>
                        <div class="prose dark:prose-invert max-w-none text-sm">
                            {!! nl2br(e($job->responsibilities)) !!}
                        </div>
                    </div>
                    @endif

                    {{-- Moderation Actions --}}
                    <section class="border-t pt-4 mt-6" aria-labelledby="moderation-actions-heading">
                        <h3 id="moderation-actions-heading" class="font-semibold mb-4">Moderation Actions</h3>
                        <div class="flex flex-wrap gap-2">
                            @if($job->moderation_status === 'pending')
                                <form action="{{ route('moderator.jobs.approve', $job->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="ti-btn ti-btn-success" aria-label="Approve this job">
                                        <i class="ri-check-line me-1" aria-hidden="true"></i> Approve Job
                                    </button>
                                </form>
                                <button type="button" class="ti-btn ti-btn-danger reject-toggle-btn" aria-expanded="false" aria-controls="reject-section" aria-label="Show rejection form" data-section="reject-section">
                                    <i class="ri-close-line me-1" aria-hidden="true"></i> Reject Job
                                </button>
                            @endif

                            @if(!$job->is_flagged)
                                <button type="button" class="ti-btn ti-btn-warning flag-toggle-btn" aria-expanded="false" aria-controls="flag-section" aria-label="Show flag form" data-section="flag-section">
                                    <i class="ri-flag-line me-1" aria-hidden="true"></i> Flag Job
                                </button>
                            @else
                                <form action="{{ route('moderator.jobs.unflag', $job->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="ti-btn ti-btn-secondary" aria-label="Remove flag from this job">
                                        <i class="ri-flag-off-line me-1" aria-hidden="true"></i> Remove Flag
                                    </button>
                                </form>
                            @endif
                        </div>

                        {{-- Reject Form --}}
                        <div id="reject-section" class="hidden mt-4 p-4 border rounded-lg bg-danger/5 border-danger/20" role="region" aria-labelledby="reject-section-heading">
                            <h4 id="reject-section-heading" class="font-semibold mb-3 text-danger">Provide rejection reason</h4>
                            <form action="{{ route('moderator.jobs.reject', $job->id) }}" method="POST" class="job-reject-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="job-reject-notes" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                    <textarea name="notes" id="job-reject-notes" class="form-control" rows="3" required placeholder="e.g. Job description violates guidelines" aria-describedby="job-reject-notes-hint"></textarea>
                                    <p id="job-reject-notes-hint" class="text-sm text-textmuted mt-1">This may be shared with the employer. Max 1000 characters.</p>
                                </div>
                                <button type="submit" class="ti-btn ti-btn-danger job-reject-submit">Confirm Rejection</button>
                            </form>
                        </div>

                        {{-- Flag Form --}}
                        <div id="flag-section" class="hidden mt-4 p-4 border rounded-lg bg-warning/5 border-warning/20" role="region" aria-labelledby="flag-section-heading">
                            <h4 id="flag-section-heading" class="font-semibold mb-3 text-warning">Provide flag reason</h4>
                            <form action="{{ route('moderator.jobs.flag', $job->id) }}" method="POST" class="job-flag-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="job-flag-reason" class="form-label">Flag Reason <span class="text-danger">*</span></label>
                                    <textarea name="reason" id="job-flag-reason" class="form-control" rows="3" required placeholder="e.g. Suspicious content or missing details" aria-describedby="job-flag-reason-hint"></textarea>
                                    <p id="job-flag-reason-hint" class="text-sm text-textmuted mt-1">Max 500 characters. Visible to moderators.</p>
                                </div>
                                <button type="submit" class="ti-btn ti-btn-warning job-flag-submit">Confirm Flag</button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            {{-- Company Information --}}
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Company Information</div>
                </div>
                <div class="box-body">
                    @if($job->company)
                        <div class="flex items-center gap-3 mb-4">
                            <span class="avatar avatar-lg avatar-rounded border">
                                @if($job->company->logo_url)
                                    <img src="{{ $job->company->logo_url }}" alt="{{ $job->company->name }}">
                                @else
                                    <span class="bg-primary/10 text-primary flex items-center justify-center w-full h-full" aria-hidden="true">
                                        {{ strtoupper(substr($job->company->name, 0, 2)) }}
                                    </span>
                                @endif
                            </span>
                            <div>
                                <p class="font-medium">{{ $job->company->name }}</p>
                                <p class="text-xs text-textmuted">{{ $job->company->industry ?? 'Industry not specified' }}</p>
                            </div>
                        </div>
                        @if($job->company->user)
                            <p class="text-sm"><strong>Owner:</strong> {{ $job->company->user->name }}</p>
                            <p class="text-xs text-textmuted">{{ $job->company->user->email }}</p>
                        @endif
                        <div class="mt-3">
                            <a href="{{ route('moderator.companies.show', $job->company) }}" class="ti-btn ti-btn-sm ti-btn-primary w-full" aria-label="View company {{ $job->company->name }}">
                                <i class="ri-building-line me-1" aria-hidden="true"></i> View Company
                            </a>
                        </div>
                    @else
                        <p class="text-textmuted">No company associated</p>
                    @endif
                </div>
            </div>

            {{-- Moderation History --}}
            @if($job->moderated_at)
            <div class="box border mt-4">
                <div class="box-header">
                    <div class="box-title">Moderation History</div>
                </div>
                <div class="box-body">
                    <p class="text-sm"><strong>Last Action:</strong> {{ ucfirst($job->moderation_status) }}</p>
                    <p class="text-sm"><strong>Date:</strong> {{ $job->moderated_at->format('M d, Y H:i') }}</p>
                    @if($job->moderatedBy)
                        <p class="text-sm"><strong>By:</strong> {{ $job->moderatedBy->name }}</p>
                    @endif
                    @if($job->moderation_notes)
                        <p class="text-sm mt-2"><strong>Notes:</strong></p>
                        <p class="text-sm text-textmuted">{{ $job->moderation_notes }}</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Applications Count --}}
            <div class="box border mt-4">
                <div class="box-header">
                    <div class="box-title">Applications</div>
                </div>
                <div class="box-body">
                    <p class="text-2xl font-bold">{{ $job->applications->count() }}</p>
                    <p class="text-xs text-textmuted">Total applications received</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function setupToggle(btnClass, sectionId) {
                var btn = document.querySelector(btnClass);
                var section = document.getElementById(sectionId);
                if (btn && section) {
                    btn.addEventListener('click', function() {
                        var isHidden = section.classList.toggle('hidden');
                        btn.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
                        btn.setAttribute('aria-label', isHidden ? 'Show ' + (sectionId === 'reject-section' ? 'rejection' : 'flag') + ' form' : 'Hide form');
                    });
                }
            }
            setupToggle('.reject-toggle-btn', 'reject-section');
            setupToggle('.flag-toggle-btn', 'flag-section');

            function setSubmitLoading(btn, label) {
                if (!btn) return;
                btn.disabled = true;
                btn.innerHTML = '<span class="inline-block animate-spin shrink-0 size-4 border-2 border-current border-transparent rounded-full me-1.5 align-middle" role="status" aria-hidden="true"></span> ' + label;
            }
            var rejectForm = document.querySelector('.job-reject-form');
            if (rejectForm) {
                rejectForm.addEventListener('submit', function() {
                    setSubmitLoading(document.querySelector('.job-reject-submit'), 'Submitting…');
                });
            }
            var flagForm = document.querySelector('.job-flag-form');
            if (flagForm) {
                flagForm.addEventListener('submit', function() {
                    setSubmitLoading(document.querySelector('.job-flag-submit'), 'Submitting…');
                });
            }
        });
    </script>

</x-app-layout>
