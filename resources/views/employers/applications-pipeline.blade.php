<x-app-layout>

    <x-slot name="pageTitle">Hiring Pipeline</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Main"}</x-slot>
    <x-slot name="active">Hiring Pipeline</x-slot>

    @include('employers.partials.employer-styles')

    @php
        $colMeta = [
            'applied'      => ['icon' => 'ri-send-plane-2-line',      'color' => '#3b82f6', 'bg' => '#eff6ff', 'text' => '#1d4ed8'],
            'under_review' => ['icon' => 'ri-search-eye-line',         'color' => '#eab308', 'bg' => '#fefce8', 'text' => '#a16207'],
            'viewed'       => ['icon' => 'ri-eye-line',                'color' => '#0ea5e9', 'bg' => '#e0f2fe', 'text' => '#0369a1'],
            'in_progress'  => ['icon' => 'ri-chat-4-line',             'color' => '#8b5cf6', 'bg' => '#f5f3ff', 'text' => '#6d28d9'],
            'accepted'     => ['icon' => 'ri-checkbox-circle-line',    'color' => '#10b981', 'bg' => '#ecfdf5', 'text' => '#065f46'],
            'declined'     => ['icon' => 'ri-close-circle-line',       'color' => '#ef4444', 'bg' => '#fef2f2', 'text' => '#b91c1c'],
            'closed'       => ['icon' => 'ri-lock-line',               'color' => '#64748b', 'bg' => '#f8fafc', 'text' => '#475569'],
        ];
        $logoBgs = [
            ['from' => '#4f46e5', 'to' => '#818cf8'],
            ['from' => '#0d9488', 'to' => '#2dd4bf'],
            ['from' => '#dc2626', 'to' => '#f87171'],
            ['from' => '#7c3aed', 'to' => '#c084fc'],
            ['from' => '#ea580c', 'to' => '#fb923c'],
            ['from' => '#0284c7', 'to' => '#38bdf8'],
        ];
        $totalApps = collect($pipeline)->sum(fn($col) => $col['applications']->count());
    @endphp

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Page Hero ═══ --}}
        <div class="col-span-12" id="wt-hero">
            <div class="cd-page-hero">
                <div>
                    <h1 class="cd-page-hero-title">
                        <i class="ri-kanban-view-fill me-2 text-indigo-400"></i>Hiring Pipeline
                    </h1>
                    <p class="cd-page-hero-sub">Track and manage candidates across every stage of your hiring process.</p>
                </div>
                <div class="cd-page-hero-actions" id="wt-hero-nav" style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap">
                    <div style="display:flex;align-items:center;gap:0.5rem;background:rgba(99,102,241,0.1);color:#4f46e5;border-radius:12px;padding:0.5rem 1rem;font-size:0.8rem;font-weight:700;">
                        <i class="ri-team-line"></i> {{ $totalApps }} Applicants
                    </div>
                    <a href="{{ route('employer.applications.index') }}" class="cd-hero-btn cd-hero-btn-ghost"><i class="ri-table-fill"></i> Table View</a>
                    <a href="{{ route('employer.history.index') }}" class="cd-hero-btn cd-hero-btn-ghost"><i class="ri-history-line"></i> History</a>
                </div>
            </div>
        </div>

        {{-- ═══ Pipeline Kanban Board ═══ --}}
        <div class="col-span-12" id="wt-pipeline">
            <div class="cd-kanban-board">
                @foreach($pipeline as $key => $col)
                    @php
                        $meta = $colMeta[$key] ?? ['icon' => 'ri-stack-line', 'color' => '#64748b', 'bg' => '#f8fafc', 'text' => '#475569'];
                        $count = $col['applications']->count();
                        $colClass = 'cd-col-' . $key;
                    @endphp

                    <div class="cd-kanban-column {{ $colClass }}">

                        {{-- Column Header --}}
                        <div class="cd-kanban-header" style="background:{{ $meta['bg'] }};border-radius:20px 20px 0 0;">
                            <div class="cd-kanban-title" style="color:{{ $meta['text'] }}">
                                <i class="{{ $meta['icon'] }}" style="font-size:1rem"></i>
                                {{ $col['label'] }}
                            </div>
                            <span class="cd-kanban-count" style="background:{{ $meta['color'] }}22;color:{{ $meta['color'] }}">
                                {{ $count }}
                            </span>
                        </div>

                        {{-- Cards Scroll Area --}}
                        <div class="cd-kanban-scroll-area">
                            @forelse($col['applications'] as $idx => $app)
                                @php
                                    $bg = $logoBgs[$idx % count($logoBgs)];
                                    $name = $app->applicantProfile?->display_name ?? $app->applicantProfile?->user?->name ?? 'Unknown';
                                    $initials = strtoupper(substr($name, 0, 2));
                                    $email = $app->applicantProfile?->user?->email ?? '';
                                    $jobTitle = $app->jobPosting->title ?? '—';
                                    $appliedAt = $app->applied_at?->diffForHumans() ?? '—';
                                @endphp

                                <div class="cd-kanban-card" style="padding-left:1.5rem">
                                    {{-- Color Accent Bar --}}
                                    <div class="cd-kanban-card-accent" style="background:{{ $meta['color'] }}"></div>

                                    {{-- Candidate Info --}}
                                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:0.75rem">
                                        <div class="cd-kanban-avatar" style="background:linear-gradient(135deg, {{ $bg['from'] }}, {{ $bg['to'] }});flex-shrink:0">
                                            {{ $initials }}
                                        </div>
                                        <div style="min-width:0">
                                            <div class="cd-kanban-applicant-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $name }}</div>
                                            @if($email)
                                                <div style="font-size:0.7rem;color:var(--cd-neutral-400);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $email }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Job Posting --}}
                                    <div class="cd-kanban-job-title" style="margin-bottom:0.5rem">
                                        <i class="ri-briefcase-line"></i>
                                        <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $jobTitle }}</span>
                                    </div>

                                    {{-- Applied At --}}
                                    <div style="display:flex;align-items:center;gap:0.35rem;font-size:0.72rem;color:var(--cd-neutral-400)">
                                        <i class="ri-time-line"></i> {{ $appliedAt }}
                                    </div>

                                    {{-- Card Footer: Actions --}}
                                    <div class="cd-kanban-card-footer">
                                        <div style="display:flex;gap:6px">
                                            @if($key !== 'accepted')
                                                <button type="button"
                                                    class="app-accept-btn"
                                                    data-app-id="{{ $app->id }}"
                                                    title="Accept"
                                                    style="width:32px;height:32px;border-radius:8px;border:1px solid #d1fae5;background:#ecfdf5;color:#059669;display:flex;align-items:center;justify-content:center;font-size:0.95rem;cursor:pointer;transition:all 0.2s ease"
                                                    onmouseover="this.style.background='#059669';this.style.color='#fff'"
                                                    onmouseout="this.style.background='#ecfdf5';this.style.color='#059669'">
                                                    <i class="ri-checkbox-circle-line"></i>
                                                </button>
                                            @endif
                                            @if($key !== 'declined' && $key !== 'closed')
                                                <button type="button"
                                                    class="app-decline-btn"
                                                    data-app-id="{{ $app->id }}"
                                                    title="Decline"
                                                    style="width:32px;height:32px;border-radius:8px;border:1px solid #fecaca;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:0.95rem;cursor:pointer;transition:all 0.2s ease"
                                                    onmouseover="this.style.background='#dc2626';this.style.color='#fff'"
                                                    onmouseout="this.style.background='#fef2f2';this.style.color='#dc2626'">
                                                    <i class="ri-close-circle-line"></i>
                                                </button>
                                            @endif
                                        </div>
                                        <a href="{{ route('jobs.show', $app->jobPosting->slug) }}"
                                            title="View Job"
                                            style="width:32px;height:32px;border-radius:8px;border:1px solid var(--cd-neutral-200);background:transparent;color:var(--cd-neutral-500);display:flex;align-items:center;justify-content:center;font-size:0.9rem;transition:all 0.2s ease;text-decoration:none"
                                            onmouseover="this.style.background='var(--cd-primary)';this.style.color='#fff';this.style.borderColor='var(--cd-primary)'"
                                            onmouseout="this.style.background='transparent';this.style.color='var(--cd-neutral-500)';this.style.borderColor='var(--cd-neutral-200)'">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </div>
                                </div>

                            @empty
                                {{-- Premium Column Empty State --}}
                                <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2.5rem 1rem;text-align:center">
                                    <div style="width:64px;height:64px;border-radius:18px;background:{{ $meta['bg'] }};display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:{{ $meta['color'] }};font-size:1.75rem;animation:cdEmptyFloat 4s ease-in-out infinite">
                                        <i class="{{ $meta['icon'] }}"></i>
                                    </div>
                                    <p style="font-size:0.8rem;font-weight:700;color:var(--cd-neutral-500);margin:0">No applicants here</p>
                                    <p style="font-size:0.72rem;color:var(--cd-neutral-400);margin-top:0.25rem">This stage is empty</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function updateStatus(appId, status) {
                fetch('/employer/applications/' + appId + '/status', {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ status: status })
                }).then(function(r) { return r.json(); }).then(function() {
                    Swal.fire({ icon: 'success', title: 'Done!', timer: 1200, showConfirmButton: false }).then(function() { window.location.reload(); });
                }).catch(function() { Swal.fire({ icon: 'error', title: 'Error updating status' }); });
            }

            document.querySelectorAll('.app-accept-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    Swal.fire({
                        icon: 'question',
                        title: 'Accept this application?',
                        text: 'The applicant will be notified about your decision.',
                        showCancelButton: true,
                        confirmButtonText: 'Accept',
                        confirmButtonColor: '#059669',
                        cancelButtonText: 'Cancel'
                    }).then(function(r) {
                        if (r.isConfirmed) updateStatus(btn.dataset.appId, 'accepted');
                    });
                });
            });

            document.querySelectorAll('.app-decline-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Decline this application?',
                        text: 'The applicant will be notified about your decision.',
                        showCancelButton: true,
                        confirmButtonText: 'Decline',
                        confirmButtonColor: '#dc2626',
                        cancelButtonText: 'Cancel'
                    }).then(function(r) {
                        if (r.isConfirmed) updateStatus(btn.dataset.appId, 'not_selected');
                    });
                });
            });
        });
    </script>

    @include('candidates.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'wt-hero',     'title' => 'Hiring Pipeline',     'icon' => 'ri-kanban-view-fill',    'body' => 'Welcome to your Hiring Pipeline! This Kanban-style board lets you visualize every application organized by its current stage in your hiring process.', 'position' => 'bottom'],
            ['target' => 'wt-hero-nav', 'title' => 'Navigation',          'icon' => 'ri-links-line',           'body' => 'Switch to Table View for a traditional list format, or visit History to see archived applications.', 'position' => 'bottom'],
            ['target' => 'wt-pipeline', 'title' => 'Pipeline Stages',     'icon' => 'ri-layout-column-fill',  'body' => 'Each column represents a hiring stage. Scroll horizontally to see all stages. Candidate cards show key details and quick-action buttons to accept or decline applications.', 'position' => 'top'],
        ],
        'wtKey' => 'employer_pipeline_v2',
    ])

</x-app-layout>
