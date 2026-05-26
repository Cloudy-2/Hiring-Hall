<x-app-layout>

    <x-slot name="pageTitle">Interviews</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Interviews</x-slot>

    @include('employers.partials.employer-styles')

    <x-modern-header chip="Interviews" title="Manage Your Interviews"
        desc='Schedule and manage interviews with applicants'>
        <x-slot name="actions">
            <a href="{{ route('employer.interviews.create') }}" class="cd-hero-btn cd-hero-btn-primary"><i class="ri-add-line"></i> Schedule Interview</a>  
            <a href="{{ route('employer.dashboard') }}" class="cd-hero-btn cd-hero-btn-ghost"><i
                    class="ri-dashboard-line"></i> Dashboard</a>
        </x-slot>
    </x-modern-header>

    <style>
        /* Compact Stats Cards */
        .cd-pipe {
            padding: 0.85rem 1.25rem;
            border-radius: 16px;
            background: #fff;
            border: 1px solid rgba(226, 232, 240, 0.7);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .cd-pipe:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            border-color: #4f46e533;
        }

        .cd-pipe-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .cd-pipe-icon-today { background: #4f46e512; color: #4f46e5; }
        .cd-pipe-icon-upcoming { background: #10b98112; color: #10b981; }
        
        .cd-pipe-info { min-width: 0; }

        .cd-pipe-num {
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.15rem;
            color: #1e293b;
        }
        
        .cd-pipe-lbl {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Tabs Restyling */
        .cd-tabs-pilled {
            display: inline-flex;
            background: #f1f5f9;
            padding: 0.35rem;
            border-radius: 14px;
            gap: 4px;
        }
        .cd-tab-pill {
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .cd-tab-pill:hover { color: #1e293b; background: rgba(255,255,255,0.5); }
        .cd-tab-pill.active {
            background: #fff;
            color: #4f46e5;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }

        [data-theme-mode="dark"] .cd-pipe, .dark .cd-pipe {
            background: rgba(30, 41, 59, 0.7);
            border-color: rgba(255,255,255,0.05);
        }
        [data-theme-mode="dark"] .cd-pipe-num, .dark .cd-pipe-num { color: #f8fafc; }
        [data-theme-mode="dark"] .cd-tabs-pilled, .dark .cd-tabs-pilled { background: rgba(15, 23, 42, 0.5); }
        [data-theme-mode="dark"] .cd-tab-pill.active, .dark .cd-tab-pill.active { background: #4f46e5; color: #fff; }
    </style>

    <div class="max-w-7xl mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-12 gap-x-6 gap-y-6">
            {{-- ═══ Stats ═══ --}}
            <div class="col-span-12 md:col-span-6 lg:col-span-3 cd-animate-fade-in-up" style="min-width:0">
                <div class="cd-pipe">
                    <div class="cd-pipe-icon cd-pipe-icon-today">
                        <i class="ri-calendar-check-line"></i>
                    </div>
                    <div class="cd-pipe-info">
                        <div class="cd-pipe-num cd-pipe-num-today">{{ $todayCount }}</div>
                        <div class="cd-pipe-lbl">Today's Interviews</div>
                    </div>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 lg:col-span-3 cd-animate-fade-in-up" style="animation-delay: 0.1s; min-width:0">
                <div class="cd-pipe">
                    <div class="cd-pipe-icon cd-pipe-icon-upcoming">
                        <i class="ri-time-line"></i>
                    </div>
                    <div class="cd-pipe-info">
                        <div class="cd-pipe-num cd-pipe-num-upcoming">{{ $upcomingCount }}</div>
                        <div class="cd-pipe-lbl">Upcoming Interviews</div>
                    </div>
                </div>
            </div>

            {{-- ═══ Filter Tabs ═══ --}}
            <div class="col-span-12 mt-2">
                <div class="cd-tabs-pilled">
                    <a href="{{ route('employer.interviews.index', ['filter' => 'upcoming']) }}"
                       class="cd-tab-pill {{ $filter === 'upcoming' ? 'active' : '' }}">
                        <i class="ri-time-line"></i> Upcoming
                    </a>
                    <a href="{{ route('employer.interviews.index', ['filter' => 'today']) }}"
                       class="cd-tab-pill {{ $filter === 'today' ? 'active' : '' }}">
                        <i class="ri-calendar-2-line"></i> Today
                    </a>
                    <a href="{{ route('employer.interviews.index', ['filter' => 'past']) }}"
                       class="cd-tab-pill {{ $filter === 'past' ? 'active' : '' }}">
                        <i class="ri-history-line"></i> Past
                    </a>
                    <a href="{{ route('employer.interviews.index', ['filter' => 'all']) }}"
                       class="cd-tab-pill {{ $filter === 'all' ? 'active' : '' }}">
                        <i class="ri-list-check"></i> All
                    </a>
                </div>
            </div>

            {{-- ═══ Calendar View ═══ --}}
            <div class="col-span-12 lg:col-span-8" style="min-width:0">
                <div class="cd-section">
                    <div class="cd-section-head">
                        <span class="cd-section-label"><i class="ri-calendar-line"></i> Calendar</span>
                    </div>
                    <div id="interview-calendar" class="min-h-[500px]" style="overflow-x: auto;"></div>
                </div>
            </div>

            {{-- ═══ Interviews List ═══ --}}
            <div class="col-span-12 lg:col-span-4" style="min-width:0">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-list-check"></i> {{ ucfirst($filter) }} Interviews</span>
                    <span class="text-xs text-gray-400">{{ $interviews->total() }} total</span>
                </div>

                @if (session('status'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            if (window.Swal) Swal.fire({ icon: 'success', title: 'Success', text: @json(session('status')), timer: 2500, showConfirmButton: false });
                        });
                    </script>
                @endif

                @if($interviews->isEmpty())
                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:3rem 1rem;text-align:center;">
                        <div style="width:64px;height:64px;border-radius:20px;background:#4f46e512;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;color:#4f46e5;font-size:1.75rem;animation:kb-float 3s ease-in-out infinite;box-shadow:0 8px 16px rgba(79,70,229,0.12)">
                            <i class="ri-calendar-line"></i>
                        </div>
                        <p style="font-size:1rem;font-weight:800;color:var(--kb-text-primary);margin-bottom:0.35rem">No {{ $filter }} interviews</p>
                        <p style="font-size:0.85rem;color:var(--kb-text-secondary);margin-bottom:1.5rem">Schedule new interviews to see them here.</p>
                        <a href="{{ route('employer.interviews.create') }}" class="cd-btn cd-btn-primary cd-btn-sm">
                            <i class="ri-add-line"></i> Schedule Interview
                        </a>
                    </div>
                @else
                    <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($interviews as $interview)
                            @php
                                $statusMeta = [
                                    'scheduled'   => ['bg'=>'#e0e7ff','text'=>'#4338ca','icon'=>'ri-calendar-check-line'],
                                    'completed'   => ['bg'=>'#dcfce7','text'=>'#15803d','icon'=>'ri-checkbox-circle-line'],
                                    'cancelled'   => ['bg'=>'#fee2e2','text'=>'#b91c1c','icon'=>'ri-close-circle-line'],
                                    'rescheduled' => ['bg'=>'#fef3c7','text'=>'#b45309','icon'=>'ri-refresh-line'],
                                    'no_show'     => ['bg'=>'#f3f4f6','text'=>'#4b5563','icon'=>'ri-user-unfollow-line'],
                                ];
                                $sm = $statusMeta[$interview->status] ?? ['bg'=>'#f3f4f6','text'=>'#4b5563','icon'=>'ri-question-line'];
                                
                                $name = $interview->applicant?->name ?? 'Unknown';
                                $initials = strtoupper(substr($name, 0, 2));
                                $avatarBgs = ['#4f46e5','#0891b2','#7c3aed','#0d9488','#ea580c','#db2777'];
                                $avatarBg = $avatarBgs[abs(crc32($name)) % count($avatarBgs)];
                            @endphp
                            <div class="kanban-card" style="padding:0; margin-bottom: 0px !important;">
                                <div style="padding:1rem">
                                    {{-- Header: Avatar + Status --}}
                                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem">
                                        <div style="display:flex;align-items:center;gap:0.75rem">
                                            <div style="width:34px;height:34px;border-radius:10px;background:{{ $avatarBg }};display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;font-weight:800;box-shadow:0 4px 10px {{ $avatarBg }}44">
                                                {{ $initials }}
                                            </div>
                                            <div style="min-width:0">
                                                <div style="font-size:0.85rem;font-weight:700;color:var(--kb-text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $name }}</div>
                                                <div style="font-size:0.7rem;color:var(--kb-text-secondary);display:flex;align-items:center;gap:4px">
                                                    <i class="ri-calendar-line"></i> {{ $interview->scheduled_at->format('M j, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                        <span style="font-size:0.65rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:6px;background:{{ $sm['bg'] }};color:{{ $sm['text'] }};display:flex;align-items:center;gap:3px">
                                            <i class="{{ $sm['icon'] }}"></i> {{ strtoupper($interview->status) }}
                                        </span>
                                    </div>

                                    {{-- Title & Details --}}
                                    <div style="margin-bottom:0.75rem">
                                        <div style="font-size:0.85rem;font-weight:600;color:var(--kb-text-primary);line-height:1.4;margin-bottom:0.4rem">{{ $interview->title }}</div>
                                        <div style="display:flex;align-items:center;gap:8px;font-size:0.7rem;color:var(--kb-text-secondary)">
                                            <span style="display:inline-flex;align-items:center;gap:3px"><i class="ri-time-line"></i> {{ $interview->scheduled_at->format('g:i A') }}</span>
                                            @if($interview->meeting_link)
                                                <span style="display:inline-flex;align-items:center;gap:3px"><i class="ri-vidicon-line"></i> Video</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:0.4rem;padding-top:0.75rem;border-top:1px solid rgba(0,0,0,0.03)">
                                        @if($interview->status === 'scheduled')
                                            <button type="button" class="notify-btn" data-interview-id="{{ $interview->id }}"
                                                style="width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#ecfdf5;color:#059669;transition:all 0.2s"
                                                onmouseover="this.style.background='#059669';this.style.color='#fff'"
                                                onmouseout="this.style.background='#ecfdf5';this.style.color='#059669'"
                                                title="Send Reminder">
                                                <i class="ri-notification-3-line"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('employer.interviews.edit', $interview) }}"
                                            style="width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#eef2ff;color:#4f46e5;transition:all 0.2s"
                                            onmouseover="this.style.background='#4f46e5';this.style.color='#fff'"
                                            onmouseout="this.style.background='#eef2ff';this.style.color='#4f46e5'"
                                            title="Edit Interview">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        @if($interview->status === 'scheduled')
                                            <button type="button" class="cancel-btn" data-interview-id="{{ $interview->id }}"
                                                style="width:28px;height:28px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:#fef2f2;color:#dc2626;transition:all 0.2s"
                                                onmouseover="this.style.background='#dc2626';this.style.color='#fff'"
                                                onmouseout="this.style.background='#fef2f2';this.style.color='#dc2626'"
                                                title="Cancel">
                                                <i class="ri-close-circle-line"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-2">{{ $interviews->links() }}</div>
                @endif
            </div>
        </div>

    </div>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <style>
    <style>
        /* FullCalendar Premium Overrides */
        .fc { --fc-border-color: rgba(0,0,0,0.04); --fc-button-bg-color: #f1f5f9; --fc-button-border-color: transparent; --fc-button-text-color: #475569; --fc-button-hover-bg-color: #e2e8f0; --fc-button-active-bg-color: #4f46e5; --fc-button-active-border-color: transparent; --fc-today-bg-color: rgba(79,70,229,0.03); }
        .fc .fc-toolbar-title { font-size: 1.25rem !important; font-weight: 800; color: #1e293b; }
        .fc .fc-button { font-size: 0.8rem; font-weight: 700; text-transform: capitalize; padding: 0.4rem 0.85rem; border-radius: 10px; transition: all 0.2s; }
        .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active { background-color: #4f46e5 !important; color: #fff !important; }
        
        .fc .fc-col-header-cell { padding: 12px 0; background: #f8fafc; border: none; }
        .fc .fc-col-header-cell-cushion { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
        
        .fc-theme-standard td, .fc-theme-standard th { border: 1px solid rgba(0,0,0,0.04) !important; }
        .fc .fc-daygrid-day-number { font-size: 0.85rem; font-weight: 600; color: #64748b; padding: 8px 12px; }
        .fc .fc-day-today .fc-daygrid-day-number { color: #4f46e5; font-weight: 800; }
        
        .fc-event { border: none !important; padding: 2px 4px !important; border-radius: 6px !important; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .fc-event-title { font-size: 0.7rem !important; font-weight: 700 !important; }
        
        .fc .fc-daygrid-more-link { font-size: 0.7rem; font-weight: 700; color: #4f46e5; background: #4f46e510; padding: 2px 6px; border-radius: 4px; }

        [data-theme-mode="dark"] .fc { --fc-border-color: rgba(255,255,255,0.05); --fc-button-bg-color: #1e293b; --fc-button-text-color: #94a3b8; --fc-today-bg-color: rgba(79,70,229,0.1); }
        [data-theme-mode="dark"] .fc .fc-toolbar-title { color: #f1f5f9; }
        [data-theme-mode="dark"] .fc .fc-col-header-cell { background: #0f172a; }
        
        /* Day modal event cards - Redesigned */
        .day-modal-event {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            margin-bottom: 8px;
            transition: all 0.2s;
        }
        .day-modal-event:hover { transform: translateX(4px); border-color: #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .dme-avatar { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 0.7rem; font-weight: 800; flex-shrink: 0; }
        .dme-body { flex: 1; min-width: 0; text-align: left; }
        .dme-title { font-weight: 700; font-size: 0.85rem; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .dme-meta { font-size: 0.7rem; color: #64748b; margin-top: 2px; }
        .dme-action-btn { width: 28px; height: 28px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: #64748b; transition: all 0.2s; text-decoration: none; flex-shrink: 0; }
        .dme-action-btn:hover { background: #4f46e5; color: #fff; }

        [data-theme-mode="dark"] .day-modal-event { background: #1e293b; border-color: #334155; }
        [data-theme-mode="dark"] .dme-title { color: #f1f5f9; }
        [data-theme-mode="dark"] .dme-action-btn { background: #334155; color: #94a3b8; }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('interview-calendar');
        const calendarEvents = @json($calendarEvents);
        let focusReturnEl = null;

        const avatarBgs = ['#4f46e5','#0891b2','#7c3aed','#0d9488','#ea580c','#db2777'];
        function getAvatarBg(name) {
            let hash = 0;
            for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
            return avatarBgs[Math.abs(hash) % avatarBgs.length];
        }

        const statusColors = {
            'scheduled': '#4f46e5',
            'completed': '#10b981',
            'cancelled': '#ef4444',
            'rescheduled': '#f59e0b',
            'no_show': '#64748b',
        };

        function showDayModal(dateStr, events) {
            if (!events.length) return;

            const dateLabel = new Date(dateStr + 'T00:00:00').toLocaleDateString('en-US', {
                weekday: 'long', month: 'long', day: 'numeric'
            });

            let html = '<div class="space-y-2 mt-2">';
            events.forEach(function(ev) {
                const props = ev.extendedProps || {};
                const name = props.applicant || 'Unknown';
                const initials = name.substring(0, 2).toUpperCase();
                const bg = getAvatarBg(name);
                const time = ev.start ? new Date(ev.start).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' }) : '';
                
                html += `
                    <div class="day-modal-event">
                        <div class="dme-avatar" style="background:${bg}; box-shadow: 0 4px 10px ${bg}44">${initials}</div>
                        <div class="dme-body">
                            <div class="dme-title">${ev.title}</div>
                            <div class="dme-meta"><i class="ri-time-line"></i> ${time} · ${name}</div>
                        </div>
                        <a href="/employer/interviews/${ev.id}/edit" class="dme-action-btn" title="Edit Interview">
                            <i class="ri-edit-line"></i>
                        </a>
                    </div>
                `;
            });
            html += '</div>';

            Swal.fire({
                title: `<span style="font-size:1.1rem; font-weight:800">${dateLabel}</span>`,
                html: html,
                width: 440,
                showConfirmButton: false,
                showCloseButton: true,
                customClass: { popup: 'premium-swal-popup' }
            });
        }

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: calendarEvents,
            dayMaxEvents: 2,
            moreLinkClick: function(info) {
                const dateStr = info.date.toISOString().split('T')[0];
                const dayEvents = calendarEvents.filter(function(e) {
                    const evDate = (e.start || '').substring(0, 10);
                    return evDate === dateStr;
                });
                showDayModal(dateStr, dayEvents);
                return 'none';
            },
            dateClick: function(info) {
                const dayEvents = calendarEvents.filter(function(e) {
                    const evDate = (e.start || '').substring(0, 10);
                    return evDate === info.dateStr;
                });
                if (dayEvents.length > 0) {
                    showDayModal(info.dateStr, dayEvents);
                }
            },
            eventClick: function(info) {
                info.jsEvent.stopPropagation();
                const props = info.event.extendedProps;
                Swal.fire({
                    title: info.event.title,
                    html: `
                        <div class="text-left text-sm">
                            <p><strong>Applicant:</strong> ${props.applicant || 'Unknown'}</p>
                            <p><strong>Type:</strong> ${props.type}</p>
                            <p><strong>Status:</strong> ${props.status}</p>
                            <p><strong>Time:</strong> ${info.event.start.toLocaleString()}</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Edit',
                    cancelButtonText: 'Close'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/employer/interviews/' + info.event.id + '/edit';
                    }
                });
            },
            height: 'auto',
        });

        calendar.render();

        document.querySelectorAll('.cancel-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                rememberFocus(btn);
                const interviewId = btn.dataset.interviewId;

                Swal.fire({
                    icon: 'warning',
                    title: 'Cancel Interview?',
                    text: 'Are you sure you want to cancel this interview?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Cancel',
                    confirmButtonColor: '#dc2626'
                }).then(function(result) {
                    if (!result.isConfirmed) {
                        restoreFocus();
                        return;
                    }

                    fetch('/employer/interviews/' + interviewId + '/cancel', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cancelled',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => window.location.reload());
                    })
                    .catch(() => {
                        Swal.fire({ icon: 'error', title: 'Failed to cancel' });
                        restoreFocus();
                    });
                });
            });
        });

        document.querySelectorAll('.notify-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                rememberFocus(btn);
                const interviewId = btn.dataset.interviewId;

                Swal.fire({
                    icon: 'question',
                    title: 'Send Reminder?',
                    text: 'Send an interview reminder notification to the applicant?',
                    showCancelButton: true,
                    confirmButtonText: 'Send Reminder',
                    confirmButtonColor: '#16a34a'
                }).then(function(result) {
                    if (!result.isConfirmed) {
                        restoreFocus();
                        return;
                    }

                    btn.disabled = true;
                    btn.innerHTML = '<i class="ri-loader-4-line animate-spin me-1"></i>Sending...';

                    fetch('/employer/interviews/' + interviewId + '/notify', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Notification Sent',
                            text: data.message || 'The applicant has been notified about the interview.',
                            timer: 2500,
                            showConfirmButton: false
                        });
                        btn.disabled = false;
                        btn.innerHTML = '<i class="ri-notification-3-line me-1"></i>Notify';
                        restoreFocus();
                    })
                    .catch(() => {
                        Swal.fire({ icon: 'error', title: 'Failed to send notification' });
                        btn.disabled = false;
                        btn.innerHTML = '<i class="ri-notification-3-line me-1"></i>Notify';
                        restoreFocus();
                    });
                });
            });
        });
    });
    </script>

</x-app-layout>
