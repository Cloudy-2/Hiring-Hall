<x-app-layout>

    <x-slot name="pageTitle">Hiring Pipeline</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Hiring Pipeline</x-slot>

    @include('employers.partials.employer-styles')

    <style>
        :root {
            --kb-bg: #f9fafb;
            --kb-card-bg: #ffffff;
            --kb-border: #e5e7eb;
            --kb-text-primary: #111827;
            --kb-text-secondary: #6b7280;
            --kb-scroll-track: #f1f1f1;
            --kb-scroll-thumb: #c1c1c1;
        }

        :is(.dark, html.dark, body.dark-theme, [data-theme-mode="dark"]) {
            --kb-bg: rgba(255, 255, 255, 0.03);
            --kb-card-bg: rgba(255, 255, 255, 0.05);
            --kb-border: rgba(255, 255, 255, 0.1);
            --kb-text-primary: #f3f4f6;
            --kb-text-secondary: #9ca3af;
            --kb-scroll-track: rgba(255, 255, 255, 0.05);
            --kb-scroll-thumb: rgba(255, 255, 255, 0.2);
        }

        .kanban-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 1rem;
            -webkit-overflow-scrolling: touch;
            cursor: grab;
            user-select: none;
        }

        .kanban-wrapper.is-dragging-board {
            cursor: grabbing;
            scroll-behavior: auto !important;
        }

        .kanban-wrapper.is-dragging-board .kanban-card {
            pointer-events: none;
        }

        .kanban-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .kanban-wrapper::-webkit-scrollbar-track {
            background: var(--kb-scroll-track);
            border-radius: 4px;
        }

        .kanban-wrapper::-webkit-scrollbar-thumb {
            background: var(--kb-scroll-thumb);
            border-radius: 4px;
        }

        .kanban-wrapper::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3) !important;
        }

        .kanban-board {
            display: inline-flex;
            gap: 1rem;
            padding: 0.5rem;
            min-width: max-content;
        }

        .kanban-column {
            width: 260px;
            min-width: 260px;
            max-width: 260px;
            flex-shrink: 0;
            background: var(--kb-bg);
            border: 1px solid var(--kb-border);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 320px);
            min-height: 400px;
        }

        .kanban-header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--kb-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .kanban-header-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--kb-text-primary);
        }

        .kanban-header-count {
            background: rgba(0, 0, 0, 0.1);
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.7rem;
        }

        :is(.dark, html.dark, body.dark-theme) .kanban-header-count {
            background: rgba(255, 255, 255, 0.1);
            color: #d1d5db;
        }

        .kanban-body {
            flex: 1;
            padding: 0.75rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .kanban-card {
            background: var(--kb-card-bg);
            border: 1px solid var(--kb-border);
            border-radius: 8px;
            padding: 0.75rem;
            cursor: grab;
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .kanban-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .kanban-card:focus-visible {
            outline: 2px solid #4f46e5;
            outline-offset: 2px;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.18);
        }

        .kanban-card.dragging {
            opacity: 0.5;
            transform: rotate(3deg);
        }

        .kanban-column.drag-over .kanban-body {
            background: rgba(99, 102, 241, 0.1);
            border-radius: 8px;
        }

        .stage-color-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .applicant-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .applicant-initials {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #4f46e5;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .kb-hero-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .kb-card-actions {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .kb-icon-btn {
            width: 1.85rem;
            height: 1.85rem;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s ease, transform 0.15s ease;
        }

        .kb-icon-btn:hover {
            transform: translateY(-1px);
            background: rgba(148, 163, 184, 0.16);
        }

        .kb-icon-btn:focus-visible {
            outline: 2px solid #4f46e5;
            outline-offset: 2px;
        }

        @media (max-width: 768px) {
            .kanban-column {
                width: 240px;
                min-width: 240px;
                max-width: 240px;
            }

            .kanban-header {
                padding: 0.6rem 0.75rem;
            }

            .kanban-card {
                padding: 0.6rem;
            }

            .kb-icon-btn {
                width: 1.65rem;
                height: 1.65rem;
            }
        }

        @keyframes kb-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
    </style>

    <x-modern-header chip="Hiring Pipeline" title="Manage Your Hiring Pipeline"
        desc='Review and manage all candidate applications'>
        <x-slot name="actions">
            <div style="display:flex;align-items:center;gap:0.5rem">
                @if($companies->isNotEmpty())
                    <select id="company-selector" class="form-select cd-form-select"
                        aria-label="Select company for pipeline" style="flex:1;min-width:0;height:42px">
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" @selected($selectedCompany && $selectedCompany->id == $company->id)>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                @endif
                <a href="{{ route('employer.applications.index') }}" class="cd-hero-btn cd-hero-btn-ghost" style="flex:1;justify-content:center;white-space:nowrap;height:42px"><i class="ri-table-fill"></i> Table View</a>
                <a href="{{ route('employer.history.index') }}" class="cd-hero-btn cd-hero-btn-ghost" style="flex:1;justify-content:center;white-space:nowrap;height:42px"><i class="ri-history-line"></i> History</a>
            </div>
        </x-slot>
    </x-modern-header>

    <div class="max-w-7xl mx-auto pb-6 sm:px-6 lg:px-8">
        @if($companies->isEmpty())
            <div class="col-span-12">
                <div class="cd-section">
                    <div class="cd-empty">
                        <i class="ri-building-2-line"></i>
                        <p>You need a verified company to use the hiring pipeline.</p>
                        <a href="{{ route('employer.companies.index') }}" class="cd-btn cd-btn-primary mt-4">Go to
                            Companies</a>
                    </div>
                </div>
            </div>
        @else

            {{-- ═══ Kanban Board ═══ --}}
            <div class="col-span-12">
                <div class="kanban-wrapper">
                    <div class="kanban-board" id="kanban-board">
                        @foreach($stages as $stage)
                            <div class="kanban-column" data-stage-id="{{ $stage->id }}">
                                <div class="kanban-header">
                                    <div class="kanban-header-title">
                                        <span class="stage-color-dot" style="background: {{ $stage->color }}"></span>
                                        <span>{{ $stage->name }}</span>
                                        <span
                                            class="kanban-header-count">{{ $applications->get($stage->id)?->count() ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="kanban-body" data-stage-id="{{ $stage->id }}">
                                    @forelse($applications->get($stage->id, collect()) as $application)
                                        @php
                                            $applicant = $application->applicantProfile?->user;
                                            $profile   = $application->applicantProfile;
                                            $name      = $applicant?->name ?? 'Unknown Applicant';
                                            $initials  = strtoupper(substr($name, 0, 2));
                                            $email     = $applicant?->email ?? '';
                                            $jobTitle  = $application->jobPosting?->title ?? 'Unknown Position';
                                            $timeAgo   = $application->applied_at?->diffForHumans() ?? '—';
                                            $avatarBgs = ['#4f46e5','#0891b2','#7c3aed','#0d9488','#ea580c','#db2777'];
                                            $avatarBg  = $avatarBgs[abs(crc32($name)) % count($avatarBgs)];
                                            $isLocked  = in_array($application->status, ['accepted', 'not_selected']);
                                        @endphp
                                        <div class="kanban-card" draggable="{{ $isLocked ? 'false' : 'true' }}" tabindex="0"
                                            data-application-id="{{ $application->id }}"
                                            data-view-url="{{ route('applicants.details', ['applicant' => $profile?->id]) }}"
                                            data-interview-url="{{ route('employer.interviews.create', ['application_id' => $application->id]) }}"
                                            aria-label="{{ $name }} in {{ $stage->name }} stage.{{ $isLocked ? ' (Locked)' : '' }}"
                                            style="padding:0;overflow:hidden;border-radius:12px;position:relative;{{ $isLocked ? 'cursor:default;opacity:0.92' : '' }}">

                                            @if($isLocked)
                                                <div style="position:absolute;top:10px;right:10px;color:var(--kb-text-secondary);opacity:0.5" title="Finalized & Locked">
                                                    <i class="ri-lock-2-line" style="font-size:0.85rem"></i>
                                                </div>
                                            @endif

                                            <div style="padding:0.9rem">
                                                {{-- Top: Avatar + Name --}}
                                                <div style="display:flex;align-items:center;gap:0.65rem;margin-bottom:0.65rem">
                                                    @if($applicant && $applicant->profile_photo_path)
                                                        <img src="{{ Storage::url($applicant->profile_photo_path) }}"
                                                            alt="{{ $name }}"
                                                            style="width:36px;height:36px;border-radius:10px;object-fit:cover;flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,0.12)">
                                                    @else
                                                        <div style="width:36px;height:36px;border-radius:10px;background:{{ $avatarBg }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;font-weight:800;flex-shrink:0;box-shadow:0 4px 10px {{ $avatarBg }}44">
                                                            {{ $initials }}
                                                        </div>
                                                    @endif
                                                    <div style="min-width:0;flex:1">
                                                        <div style="font-size:0.85rem;font-weight:700;color:var(--kb-text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $name }}</div>
                                                        @if($email)
                                                            <div style="font-size:0.68rem;color:var(--kb-text-secondary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $email }}</div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Job chip --}}
                                                <div style="display:inline-flex;align-items:center;gap:0.3rem;background:{{ $stage->color }}12;color:{{ $stage->color }};border-radius:6px;padding:0.25rem 0.55rem;font-size:0.68rem;font-weight:600;margin-bottom:0.65rem;max-width:100%;overflow:hidden;white-space:nowrap;text-overflow:ellipsis">
                                                    <i class="ri-briefcase-line" style="flex-shrink:0"></i>
                                                    <span style="overflow:hidden;text-overflow:ellipsis">{{ $jobTitle }}</span>
                                                </div>

                                                {{-- Footer: time + actions --}}
                                                <div style="display:flex;align-items:center;justify-content:space-between;gap:0.4rem">
                                                    <span style="display:flex;align-items:center;gap:0.3rem;font-size:0.68rem;color:var(--kb-text-secondary);white-space:nowrap;flex-shrink:0">
                                                        <i class="ri-time-line"></i> {{ $timeAgo }}
                                                    </span>
                                                    <div style="display:flex;gap:0.3rem;flex-shrink:0">
                                                        <a href="{{ route('employer.interviews.create', ['application_id' => $application->id]) }}"
                                                            title="Schedule Interview"
                                                            style="width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;background:#d1fae5;color:#059669;text-decoration:none;transition:all 0.2s ease;font-size:0.85rem"
                                                            onmouseover="this.style.background='#059669';this.style.color='#fff'"
                                                            onmouseout="this.style.background='#d1fae5';this.style.color='#059669'">
                                                            <i class="ri-calendar-check-line"></i>
                                                        </a>
                                                        <a href="{{ route('applicants.details', ['applicant' => $profile?->id]) }}"
                                                            title="View Profile"
                                                            target="_blank"
                                                            style="width:28px;height:28px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;background:#e0e7ff;color:#4f46e5;text-decoration:none;transition:all 0.2s ease;font-size:0.85rem"
                                                            onmouseover="this.style.background='#4f46e5';this.style.color='#fff'"
                                                            onmouseout="this.style.background='#e0e7ff';this.style.color='#4f46e5'">
                                                            <i class="ri-user-line"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="kb-empty-state" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem 1rem;text-align:center;flex:1">
                                            <div style="
                                                width:56px;height:56px;border-radius:16px;
                                                background:{{ $stage->color }}18;
                                                border:1.5px solid {{ $stage->color }}33;
                                                display:flex;align-items:center;justify-content:center;
                                                margin:0 auto 0.85rem;
                                                color:{{ $stage->color }};
                                                font-size:1.5rem;
                                                animation:kb-float 3s ease-in-out infinite;
                                                box-shadow:0 4px 16px {{ $stage->color }}22;
                                            ">
                                                <i class="ri-user-search-line"></i>
                                            </div>
                                            <p style="font-size:0.78rem;font-weight:700;color:var(--kb-text-primary);margin:0 0 0.2rem">No candidates yet</p>
                                            <p style="font-size:0.7rem;color:var(--kb-text-secondary);margin:0">Drag cards here or wait for<br>new applications</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Legend --}}
            <div class="col-span-12">
                <div class="flex items-center gap-4 flex-wrap text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium dark:text-gray-300">Pipeline Stages:</span>
                    @foreach($stages as $stage)
                        <span class="flex items-center gap-1">
                            <span class="stage-color-dot"
                                style="background: {{ $stage->color }}; width: 8px; height: 8px;"></span>
                            {{ $stage->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    @if($companies->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const companySelector = document.getElementById('company-selector');
                const kanbanBoard = document.getElementById('kanban-board');
                const kanbanWrapper = document.querySelector('.kanban-wrapper');
                let draggedCard = null;

                // Interview stage ID (resolved server-side)
                const INTERVIEW_STAGE_ID = '{{ $stages->first(fn($s) => strtolower($s->name) === "interview")?->id ?? "" }}';
                const STATUS_UPDATE_BASE  = '/employer/applications/';

                // ─── Click-and-Drag Board Scroll ───
                let isBoardDragging = false;
                let boardStartX = 0;
                let boardScrollLeft = 0;
                let boardVelocity = 0;
                let boardLastX = 0;
                let boardAnimFrame = null;

                if (kanbanWrapper) {
                    kanbanWrapper.addEventListener('mousedown', function (e) {
                        // Only drag on the wrapper background, not on cards or buttons
                        if (e.target.closest('.kanban-card') || e.target.closest('a') || e.target.closest('button')) return;
                        isBoardDragging = true;
                        kanbanWrapper.classList.add('is-dragging-board');
                        boardStartX = e.pageX - kanbanWrapper.offsetLeft;
                        boardScrollLeft = kanbanWrapper.scrollLeft;
                        boardLastX = e.pageX;
                        boardVelocity = 0;
                        cancelAnimationFrame(boardAnimFrame);
                    });

                    window.addEventListener('mousemove', function (e) {
                        if (!isBoardDragging) return;
                        e.preventDefault();
                        const x = e.pageX - kanbanWrapper.offsetLeft;
                        const walk = (x - boardStartX) * 1.2;
                        boardVelocity = e.pageX - boardLastX;
                        boardLastX = e.pageX;
                        kanbanWrapper.scrollLeft = boardScrollLeft - walk;
                    });

                    window.addEventListener('mouseup', function () {
                        if (!isBoardDragging) return;
                        isBoardDragging = false;
                        kanbanWrapper.classList.remove('is-dragging-board');

                        // Momentum glide
                        let velocity = boardVelocity * 0.8;
                        function glide() {
                            if (Math.abs(velocity) < 0.5) return;
                            kanbanWrapper.scrollLeft -= velocity;
                            velocity *= 0.92;
                            boardAnimFrame = requestAnimationFrame(glide);
                        }
                        boardAnimFrame = requestAnimationFrame(glide);
                    });

                    // Touch support
                    let touchStartX = 0;
                    let touchScrollLeft = 0;
                    kanbanWrapper.addEventListener('touchstart', function (e) {
                        touchStartX = e.touches[0].pageX;
                        touchScrollLeft = kanbanWrapper.scrollLeft;
                    }, { passive: true });

                    kanbanWrapper.addEventListener('touchmove', function (e) {
                        const diff = touchStartX - e.touches[0].pageX;
                        kanbanWrapper.scrollLeft = touchScrollLeft + diff;
                    }, { passive: true });
                }
                // ─── End Board Scroll ───

                if (companySelector) {
                    companySelector.addEventListener('change', function () {
                        window.location.href = '{{ route("employer.pipeline.index") }}?company_id=' + this.value;
                    });
                }

                document.querySelectorAll('.kanban-card').forEach(function (card) {
                    card.addEventListener('dragstart', function (e) {
                        draggedCard = this;
                        this.classList.add('dragging');
                        e.dataTransfer.effectAllowed = 'move';
                        e.dataTransfer.setData('text/plain', this.dataset.applicationId);
                    });

                    card.addEventListener('dragend', function () {
                        this.classList.remove('dragging');
                        document.querySelectorAll('.kanban-column').forEach(col => col.classList.remove('drag-over'));
                        draggedCard = null;
                    });

                    card.addEventListener('keydown', function (e) {
                        const currentBody = card.closest('.kanban-body');
                        const currentColumn = currentBody ? currentBody.closest('.kanban-column') : null;
                        const columns = Array.from(document.querySelectorAll('.kanban-column'));
                        const currentIndex = currentColumn ? columns.indexOf(currentColumn) : -1;

                        if (e.key === 'Enter' && card.dataset.viewUrl) {
                            e.preventDefault();
                            window.open(card.dataset.viewUrl, '_blank');
                            return;
                        }

                        if ((e.key === 'i' || e.key === 'I') && card.dataset.interviewUrl) {
                            e.preventDefault();
                            window.location.href = card.dataset.interviewUrl;
                            return;
                        }

                        if (!e.shiftKey || (e.key !== 'ArrowLeft' && e.key !== 'ArrowRight')) {
                            return;
                        }

                        e.preventDefault();
                        if (currentIndex < 0) return;

                        const nextIndex = e.key === 'ArrowRight' ? currentIndex + 1 : currentIndex - 1;
                        const targetColumn = columns[nextIndex];
                        if (!targetColumn) return;
                        const targetBody = targetColumn.querySelector('.kanban-body');
                        if (!targetBody || !currentBody) return;

                        moveCardToStage(card, currentBody, targetBody);
                    });
                });

                document.querySelectorAll('.kanban-body').forEach(function (dropZone) {
                    dropZone.addEventListener('dragover', function (e) {
                        e.preventDefault();
                        e.dataTransfer.dropEffect = 'move';
                        this.closest('.kanban-column').classList.add('drag-over');
                    });

                    dropZone.addEventListener('dragleave', function (e) {
                        if (!this.contains(e.relatedTarget)) {
                            this.closest('.kanban-column').classList.remove('drag-over');
                        }
                    });

                    dropZone.addEventListener('drop', function (e) {
                        e.preventDefault();
                        this.closest('.kanban-column').classList.remove('drag-over');

                        const applicationId = e.dataTransfer.getData('text/plain');
                        const newStageId    = this.dataset.stageId;
                        const dropZoneBody  = this;

                        if (draggedCard && draggedCard.closest('.kanban-body').dataset.stageId !== newStageId) {
                            const previousBody = draggedCard.closest('.kanban-body');
                            const card         = draggedCard;

                            // If dropping onto the Interview column → show schedule modal
                            if (INTERVIEW_STAGE_ID && newStageId === INTERVIEW_STAGE_ID) {
                                Swal.fire({
                                    title: '<span style="font-size:1.1rem;font-weight:700">Schedule Interview</span>',
                                    html: `
                                        <div style="text-align:left">
                                            <label style="font-size:0.72rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:4px">MEETING TITLE</label>
                                            <input id="sw-title" class="swal2-input" style="margin:0 0 14px;width:100%;box-sizing:border-box" placeholder="e.g. Technical Interview">

                                            <div style="display:flex;gap:12px;margin-bottom:14px">
                                                <div style="flex:1">
                                                    <label style="font-size:0.72rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:4px">PLATFORM</label>
                                                    <select id="sw-platform" class="swal2-input" style="margin:0;width:100%;box-sizing:border-box">
                                                        <option value="video">Zoom Meeting</option>
                                                        <option value="google_meet">Google Meet</option>
                                                        <option value="phone">Phone Call</option>
                                                        <option value="in_person">In Person</option>
                                                    </select>
                                                </div>
                                                <div style="flex:1">
                                                    <label style="font-size:0.72rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:4px">SCHEDULED DATE &amp; TIME</label>
                                                    <input id="sw-datetime" type="datetime-local" class="swal2-input" style="margin:0;width:100%;box-sizing:border-box">
                                                </div>
                                            </div>

                                            <label style="font-size:0.72rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:4px">ZOOM / MEETING LINK</label>
                                            <input id="sw-link" class="swal2-input" style="margin:0;width:100%;box-sizing:border-box" placeholder="https://zoom.us/j/...">
                                        </div>`,
                                    showCancelButton: true,
                                    confirmButtonText: 'Schedule &amp; Notify',
                                    cancelButtonText: 'Cancel',
                                    confirmButtonColor: '#4f46e5',
                                    width: '520px',
                                    preConfirm: () => {
                                        const title    = document.getElementById('sw-title').value.trim();
                                        const platform = document.getElementById('sw-platform').value;
                                        const datetime = document.getElementById('sw-datetime').value;
                                        const link     = document.getElementById('sw-link').value.trim();
                                        if (!datetime) {
                                            Swal.showValidationMessage('Please set a scheduled date & time');
                                            return false;
                                        }
                                        return { title, platform, datetime, link };
                                    }
                                }).then(result => {
                                    if (result.isConfirmed) {
                                        const { title, platform, datetime, link } = result.value;

                                        // Visually move the card
                                        moveCardToStage(card, previousBody, dropZoneBody);

                                        // Update status + create interview record
                                        fetch(STATUS_UPDATE_BASE + applicationId + '/status', {
                                            method: 'PATCH',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Accept': 'application/json'
                                            },
                                            body: JSON.stringify({
                                                status: 'in_progress',
                                                interview_title: title || 'Interview',
                                                interview_type: platform,
                                                scheduled_at: datetime,
                                                meeting_link: link
                                            })
                                        })
                                        .then(r => r.json())
                                        .then(data => {
                                            if (data.status === 'ok') {
                                                Swal.fire({ icon: 'success', title: 'Interview Scheduled!', text: 'The applicant has been notified.', timer: 2000, showConfirmButton: false });
                                            } else {
                                                Swal.fire({ icon: 'error', title: 'Something went wrong', text: 'Status could not be updated.' });
                                            }
                                        })
                                        .catch(() => Swal.fire({ icon: 'error', title: 'Network error' }));
                                    }
                                    // If cancelled — card stays in original column (no move)
                                });
                            } else {
                                moveCardToStage(card, previousBody, dropZoneBody);
                            }
                        }
                    });
                });

                function moveCardToStage(card, fromBody, toBody) {
                    if (!card || !fromBody || !toBody) return;
                    const applicationId = card.dataset.applicationId;
                    const newStageId = toBody.dataset.stageId;

                    removeEmptyMessage(toBody);
                    toBody.appendChild(card);
                    ensureEmptyState(fromBody);
                    updateCounts();

                    fetch('/employer/pipeline/applications/' + applicationId + '/move', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ stage_id: newStageId })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.success) {
                                Swal.fire({ icon: 'error', title: 'Failed to move applicant' });
                                window.location.reload();
                            }
                        })
                        .catch(() => {
                            Swal.fire({ icon: 'error', title: 'Failed to move applicant' });
                            window.location.reload();
                        });
                }

                function removeEmptyMessage(body) {
                    const emptyState = body.querySelector('.kb-empty-state');
                    if (emptyState) emptyState.remove();
                }

                function ensureEmptyState(body) {
                    if (body.querySelectorAll('.kanban-card').length === 0) {
                        const col = body.closest('.kanban-column');
                        const dot = col ? col.querySelector('.stage-color-dot') : null;
                        const color = dot ? dot.style.background : '#6366f1';
                        body.innerHTML = `<div class="kb-empty-state" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem 1rem;text-align:center;flex:1">
                            <div style="width:56px;height:56px;border-radius:16px;background:${color}18;border:1.5px solid ${color}33;display:flex;align-items:center;justify-content:center;margin:0 auto 0.85rem;color:${color};font-size:1.5rem;animation:kb-float 3s ease-in-out infinite;box-shadow:0 4px 16px ${color}22">
                                <i class="ri-user-search-line"></i>
                            </div>
                            <p style="font-size:0.78rem;font-weight:700;color:var(--kb-text-primary);margin:0 0 0.2rem">No candidates yet</p>
                            <p style="font-size:0.7rem;color:var(--kb-text-secondary);margin:0">Drag cards here or wait for<br>new applications</p>
                        </div>`;
                    }
                }

                function updateCounts() {
                    document.querySelectorAll('.kanban-column').forEach(function (column) {
                        const body = column.querySelector('.kanban-body');
                        const count = body.querySelectorAll('.kanban-card').length;
                        const countEl = column.querySelector('.kanban-header-count');
                        if (countEl) {
                            countEl.textContent = count;
                        }
                    });
                }
            });
        </script>
    @endif

</x-app-layout>