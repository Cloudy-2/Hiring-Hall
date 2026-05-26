<x-app-layout>

    <x-slot name="pageTitle">My Interviews</x-slot>
    <x-slot name="url_1">{"link": "/applicant/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">My Interviews</x-slot>

    @include('applicants.partials.candidate-styles')

    <style>
        /* ── Page Background ── */
        :is([data-theme-mode="dark"], .dark, [data-bs-theme="dark"], html.dark) body {
            background: #1f1f1f !important;
        }

        /* ── Modern Elite Interview Cards (Redesigned) ── */
        .jf-interview-card {
            background: #ffffff;
            border: 1px solid var(--cd-border);
            border-radius: 24px;
            padding: 1.5rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            display: flex;
            gap: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        }
        .jf-interview-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            border-color: var(--cd-accent);
        }

        :is([data-theme-mode="dark"], .dark) .jf-interview-card {
            background: #202124;
            border-color: #303134;
            backdrop-filter: none;
        }

        /* Premium Calendar Block */
        .jf-calendar-block {
            width: 80px;
            height: 90px;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid var(--cd-border);
            flex-shrink: 0;
        }
        :is([data-theme-mode="dark"], .dark) .jf-calendar-block { background: #1e293b; border-color: rgba(255,255,255,0.1); }

        .jf-calendar-top {
            height: 24px;
            background: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .jf-calendar-top.today { background: #f59e0b; }
        .jf-calendar-top.past { background: #94a3b8; }

        .jf-calendar-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .jf-calendar-day {
            font-size: 1.75rem;
            font-weight: 900;
            color: var(--cd-text);
            line-height: 1;
        }
        :is([data-theme-mode="dark"], .dark) .jf-calendar-day { color: #f8fafc; }

        .jf-calendar-month {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--cd-text-secondary);
            text-transform: uppercase;
            margin-top: 2px;
        }
        :is([data-theme-mode="dark"], .dark) .jf-calendar-month { color: #94a3b8; }

        /* Metadata Chips */
        .jf-meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--cd-bg-alt);
            border: 1px solid var(--cd-border);
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--cd-text-secondary);
            transition: all 0.2s;
        }
        :is([data-theme-mode="dark"], .dark) .jf-meta-chip { background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.05); color: #94a3b8; }

        .jf-meta-chip i { font-size: 0.9rem; color: #94a3b8; }
        .jf-interview-card:hover .jf-meta-chip { background: #ffffff; border-color: var(--cd-border); }

        /* Join Button Pulse Animation */
        @keyframes jf-pulse-indigo {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
            70% { box-shadow: 0 0 0 12px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }
        .jf-btn-pulse {
            animation: jf-pulse-indigo 2s infinite;
        }

        .jf-company-link {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--cd-accent);
            text-decoration: none;
            transition: color 0.2s;
        }
        .jf-company-link:hover { color: #4338ca; text-decoration: underline; }

        @media (max-width: 768px) {
            .jf-interview-card {
                flex-direction: row !important; /* Force row for premium look */
                gap: 0.875rem !important;
                align-items: flex-start !important;
                text-align: left !important;
                padding: 1rem !important;
                border-radius: 20px !important;
            }
            .jf-calendar-block {
                width: 54px !important;
                height: 64px !important;
                border-radius: 12px !important;
                flex-shrink: 0 !important;
            }
            .jf-calendar-top {
                height: 18px !important;
                font-size: 0.55rem !important;
            }
            .jf-calendar-day {
                font-size: 1.25rem !important;
            }
            .jf-calendar-month {
                font-size: 0.55rem !important;
            }

            .jf-interview-card h4 {
                font-size: 1rem !important;
                margin-bottom: 0.25rem !important;
            }
            .jf-company-link {
                font-size: 0.75rem !important;
            }

            .jf-status-pill {
                padding: 4px 10px !important;
                font-size: 0.6rem !important;
                letter-spacing: 0.03em !important;
                margin-top: 4px !important;
            }

            .jf-meta-chip {
                padding: 4px 8px !important;
                font-size: 0.65rem !important;
                gap: 4px !important;
            }
            .jf-meta-chip i { font-size: 0.75rem !important; }

            /* Action Buttons full width */
            .jf-interview-card .pt-5 {
                border-top-style: dashed !important;
                padding-top: 0.875rem !important;
                margin-top: 0.875rem !important;
                width: 100% !important;
            }
            .jf-btn-pulse {
                width: 100% !important;
                justify-content: center !important;
                font-size: 0.75rem !important;
                padding: 0.75rem !important;
                border-radius: 12px !important;
            }

            /* Stats KPIs on Mobile */
            .jf-stat-card { padding: 1.25rem 1rem !important; }
            .jf-stat-val { font-size: 1.5rem !important; }
            .jf-stat-lbl { font-size: 0.55rem !important; }
            .jf-stat-desc { font-size: 0.7rem !important; line-height: 1.3 !important; }
        }


        /* ── Elite Status Pills (Interview Specific) ── */
        .jf-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid transparent;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            cursor: default;
        }
        .jf-status-pill i { font-size: 0.95rem; }
        .jf-status-pill:hover {
            transform: scale(1.05) translateY(-1px);
            box-shadow: 0 8px 20px -5px rgba(0,0,0,0.1);
        }

        .jf-status-pill.scheduled { background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); color: #4f46e5; border-color: rgba(79, 70, 229, 0.2); }
        .jf-status-pill.completed { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); color: #16a34a; border-color: rgba(22, 163, 74, 0.2); }
        .jf-status-pill.cancelled { background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); color: #dc2626; border-color: rgba(220, 38, 38, 0.2); }
        .jf-status-pill.rescheduled { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); color: #d97706; border-color: rgba(217, 119, 6, 0.2); }
        .jf-status-pill.no_show { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); color: #475569; border-color: rgba(71, 85, 105, 0.2); }

        :is([data-theme-mode="dark"], .dark) .jf-status-pill { border-color: rgba(255,255,255,0.08) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-status-pill.scheduled { background: rgba(79, 70, 229, 0.15); color: #818cf8; }
        :is([data-theme-mode="dark"], .dark) .jf-status-pill.completed { background: rgba(22, 163, 74, 0.15); color: #4ade80; }
        :is([data-theme-mode="dark"], .dark) .jf-status-pill.cancelled { background: rgba(220, 38, 38, 0.15); color: #f87171; }
        :is([data-theme-mode="dark"], .dark) .jf-status-pill.rescheduled { background: rgba(217, 119, 6, 0.15); color: #fbbf24; }
        :is([data-theme-mode="dark"], .dark) .jf-status-pill.no_show { background: rgba(255,255,255,0.05); color: #94a3b8; }
        /* ── Modern Elite Interview Stats ── */
        .jf-stat-card {
            background: linear-gradient(145deg, #ffffff 0%, #f9fafb 100%);
            border: 1px solid var(--cd-border);
            border-radius: 1rem;
            padding: 1.25rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04), 0 5px 15px rgba(0, 0, 0, 0.02);
            height: 100%;
        }
        .jf-stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.1);
            background: #ffffff;
        }

        :is([data-theme-mode="dark"], .dark) .jf-stat-card {
            background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%);
            border-color: rgba(255, 255, 255, 0.05);
        }

        .jf-stat-card.upcoming { background: linear-gradient(145deg, #ffffff 0%, #f0f7ff 100%); }
        .jf-stat-card.today { background: linear-gradient(145deg, #ffffff 0%, #fff7ed 100%); }
        .jf-stat-card.total { background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%); }

        /* Dark Mode Themed Gradients */
        :is([data-theme-mode="dark"], .dark) .jf-stat-card.upcoming { background: linear-gradient(145deg, #1e293b 0%, #1e1b4b 100%); }
        :is([data-theme-mode="dark"], .dark) .jf-stat-card.today { background: linear-gradient(145deg, #1e293b 0%, #431407 100%); }
        :is([data-theme-mode="dark"], .dark) .jf-stat-card.total { background: linear-gradient(145deg, #1e293b 0%, #0f172a 100%); }

        .jf-stat-lbl {
            font-size: 0.625rem;
            font-weight: 800;
            color: var(--cd-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-bottom: 0.25rem;
            position: relative;
            z-index: 2;
        }

        .jf-stat-icon-container {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 1.2rem;
            transition: all 0.3s;
            z-index: 2;
        }

        .jf-stat-val {
            font-size: 2rem;
            font-weight: 900;
            color: var(--cd-text);
            line-height: 1.2;
            margin: 0.25rem 0;
            letter-spacing: -0.04em;
            position: relative;
            z-index: 2;
        }

        .jf-stat-trend {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.65rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 2;
        }
        .jf-stat-trend.none { color: var(--cd-text-muted); }
        .jf-stat-trend.up { color: #059669; }
        .jf-stat-trend.down { color: #dc2626; }

        .jf-stat-desc {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--cd-text-secondary);
            line-height: 1.4;
            position: relative;
            z-index: 2;
        }

        .jf-stat-card.upcoming .jf-stat-icon-container {
            background: #ffffff; color: #316cf4; border: 1px solid rgba(49, 108, 244, 0.1);
            box-shadow: 0 4px 10px -2px rgba(49, 108, 244, 0.15);
        }
        .jf-stat-card.today .jf-stat-icon-container {
            background: #ffffff; color: #f97316; border: 1px solid rgba(249, 115, 22, 0.1);
            box-shadow: 0 4px 10px -2px rgba(249, 115, 22, 0.15);
        }
        .jf-stat-card.total .jf-stat-icon-container {
            background: #ffffff; color: #475569; border: 1px solid rgba(71, 85, 105, 0.1);
            box-shadow: 0 4px 10px -2px rgba(71, 85, 105, 0.15);
        }

        /* Dark Mode Icon Containers */
        :is([data-theme-mode="dark"], .dark) .jf-stat-card.upcoming .jf-stat-icon-container { background: rgba(49, 108, 244, 0.1) !important; color: #818cf8; border-color: rgba(49, 108, 244, 0.2); }
        :is([data-theme-mode="dark"], .dark) .jf-stat-card.today .jf-stat-icon-container { background: rgba(249, 115, 22, 0.1) !important; color: #fb923c; border-color: rgba(249, 115, 22, 0.2); }
        :is([data-theme-mode="dark"], .dark) .jf-stat-card.total .jf-stat-icon-container { background: rgba(255, 255, 255, 0.03) !important; color: #94a3b8; border-color: rgba(255, 255, 255, 0.05); }

        .jf-stat-card:hover .jf-stat-icon-container { transform: scale(1.05); }

        :is([data-theme-mode="dark"], .dark) .jf-stat-card {
            background: rgba(30, 41, 59, 0.45) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-stat-card:hover {
            border-color: #6366f1 !important;
            background: rgba(30, 41, 59, 0.6) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-stat-val { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-stat-lbl { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-stat-desc { color: #cbd5e1 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-stat-icon-container { background: #1e293b !important; }

        :is([data-theme-mode="dark"], .dark) .jf-stat-icon-container { background: #1e293b !important; }

        :is([data-theme-mode="dark"], .dark) .jf-interview-card {
            background: #202124 !important;
            border-color: #303134 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-interview-card:hover {
            border-color: #818cf8 !important;
            background: #26272b !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-calendar-block {
            background: rgba(99, 102, 241, 0.08) !important;
            border-color: rgba(99, 102, 241, 0.2) !important;
            color: #a5b4fc !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-calendar-day {
            color: #f8fafc !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-calendar-time {
            color: #94a3b8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-meta-chip {
            background: rgba(148, 163, 184, 0.1) !important;
            color: #cbd5e1 !important;
            border-color: rgba(148, 163, 184, 0.2) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-interview-card:hover .jf-meta-chip {
            background: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-header-section { border-bottom-color: rgba(255,255,255,0.08) !important; background: rgb(30, 32, 35) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-context-label { color: #ffffff !important; }
    </style>

    @php
        $statusColors = [
            'scheduled' => ['bg' => '#eef2ff', 'text' => '#4f46e5', 'icon' => 'ri-calendar-check-line'],
            'completed' => ['bg' => '#f0fdf4', 'text' => '#16a34a', 'icon' => 'ri-checkbox-circle-line'],
            'cancelled' => ['bg' => '#fef2f2', 'text' => '#dc2626', 'icon' => 'ri-close-circle-line'],
            'rescheduled' => ['bg' => '#fefce8', 'text' => '#ca8a04', 'icon' => 'ri-refresh-line'],
            'no_show' => ['bg' => '#f9fafb', 'text' => '#6b7280', 'icon' => 'ri-user-unfollow-line'],
        ];
        $typeIcons = [
            'phone' => 'ri-phone-line',
            'video' => 'ri-video-chat-line',
            'in_person' => 'ri-map-pin-user-line',
        ];
    @endphp

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Modern Minimalist Header (Interactive Board Style) --}}
        <x-modern-header :container="false" chip="Scheduled Meetings">
            <x-slot name="titleContent"><strong>My Interviews</strong></x-slot>
            <x-slot name="description">
                View and manage your scheduled interviews within the <b>Applicant Portal</b>. We're rooting for you - <b>Good luck with your interviews! 🚀</b>
            </x-slot>
            <x-slot name="actions">
                <a href="{{ route('applicant.dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm dark:bg-slate-800 dark:border-white/10 dark:text-white dark:hover:bg-slate-700">
                    <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                </a>
            </x-slot>
        </x-modern-header>

        <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Stats Cards ═══ --}}
        {{-- ═══ Stats Cards (High-End KPI Dashboard) ═══ --}}
        <div class="col-span-12 mb-3 lg:mb-10">
            <div class="grid grid-cols-12 gap-3 lg:gap-8">
                {{-- Upcoming Card --}}
                <div class="col-span-6 lg:col-span-4">
                    <div class="jf-stat-card upcoming group">
                        <div class="jf-stat-icon-container">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                        <div class="jf-stat-lbl" style="font-size: 12px;">Upcoming</div>
                        <div class="jf-stat-val">{{ $upcomingCount }}</div>
                        <div class="jf-stat-trend none" style="font-size: 12px;">No change</div>
                        <div class="jf-stat-desc line-clamp-2" style="font-size: 14px;">Next session scheduled soon.</div>
                    </div>
                </div>

                {{-- Today Card --}}
                <div class="col-span-6 lg:col-span-4">
                    <div class="jf-stat-card today group">
                        <div class="jf-stat-icon-container">
                            <i class="ri-calendar-todo-line"></i>
                        </div>
                        <div class="jf-stat-lbl" style="font-size: 12px;">Today</div>
                        <div class="jf-stat-val">{{ $todayCount }}</div>
                        <div class="jf-stat-trend down">
                            <i class="ri-arrow-down-line"></i> 100%
                        </div>
                        <div class="jf-stat-desc line-clamp-2" style="font-size: 14px;">Activities for today.</div>
                    </div>
                </div>

                {{-- Total Card --}}
                <div class="col-span-12 lg:col-span-4">
                    <div class="jf-stat-card total group">
                        <div class="jf-stat-icon-container">
                            <i class="ri-calendar-line"></i>
                        </div>
                        <div class="jf-stat-lbl" style="font-size: 12px;">Total</div>
                        <div class="jf-stat-val">{{ $totalCount }}</div>
                        <div class="jf-stat-trend none" style="font-size: 12px;">Stable Volume</div>
                        <div class="jf-stat-desc" style="font-size: 14px;">Your complete meeting history recorded.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ Interviews List ═══ --}}
        <div class="col-span-12">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-calendar-event-fill"></i> Scheduled Interviews</span>
                    <span class="text-xs text-gray-400">{{ $interviews->total() }} total</span>
                </div>

                {{-- Filter Tabs (SaaS Elite Style) --}}
                <div class="flex flex-wrap gap-2 mb-6">
                    <a href="{{ route('applicant.interviews.index') }}"
                       class="cd-btn cd-btn-sm rounded-xl px-4 py-2 font-bold transition-all {{ !$filter || $filter === 'upcoming' ? 'shadow-md' : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600' }}"
                       style="{{ !$filter || $filter === 'upcoming' ? 'background-color: #4f46e5 !important; color: white !important;' : '' }}">
                        <i class="ri-calendar-check-line me-1"></i> Upcoming
                    </a>
                    <a href="{{ route('applicant.interviews.index', ['filter' => 'today']) }}"
                       class="cd-btn cd-btn-sm rounded-xl px-4 py-2 font-bold transition-all {{ $filter === 'today' ? 'shadow-md' : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600' }}"
                       style="{{ $filter === 'today' ? 'background-color: #059669 !important; color: white !important;' : '' }}">
                        <i class="ri-calendar-todo-line me-1"></i> Today
                    </a>
                    <a href="{{ route('applicant.interviews.index', ['filter' => 'past']) }}"
                       class="cd-btn cd-btn-sm rounded-xl px-4 py-2 font-bold transition-all {{ $filter === 'past' ? 'shadow-md' : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600' }}"
                       style="{{ $filter === 'past' ? 'background-color: #475569 !important; color: white !important;' : '' }}">
                        <i class="ri-history-line me-1"></i> Past
                    </a>
                    <a href="{{ route('applicant.interviews.index', ['filter' => 'all']) }}"
                       class="cd-btn cd-btn-sm rounded-xl px-4 py-2 font-bold transition-all {{ $filter === 'all' ? 'shadow-md' : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600' }}"
                       style="{{ $filter === 'all' ? 'background-color: #2563eb !important; color: white !important;' : '' }}">
                        <i class="ri-apps-line me-1"></i> All
                    </a>
                </div>

                @if($interviews->isEmpty())
                    <div class="jf-empty-state">
                        <div class="jf-empty-icon">
                            <i class="ri-calendar-event-line"></i>
                        </div>
                        <div class="max-w-md">
                            <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">
                                @if($filter === 'upcoming')
                                    No upcoming interviews scheduled
                                @elseif($filter === 'today')
                                    No interviews scheduled for today
                                @else
                                    No interviews found
                                @endif
                            </h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6">
                                When an employer schedules an interview with you, it will appear here. Keep your profile updated to increase your chances!
                            </p>
                            <a href="{{ route('applicant.dashboard') }}" class="cd-btn cd-btn-primary">
                                <i class="ri-dashboard-line me-2"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($interviews as $interview)
                            @php
                                $sc = $statusColors[$interview->status] ?? ['bg' => '#f9fafb', 'text' => '#6b7280', 'icon' => 'ri-question-line'];
                                $typeIcon = $typeIcons[$interview->interview_type] ?? 'ri-question-line';
                                $isUpcoming = $interview->isUpcoming();
                                $isPast = $interview->isPast();
                            @endphp
                            <div class="jf-interview-card group {{ $isUpcoming ? 'upcoming' : '' }}">
                                {{-- Calendar Block Design --}}
                                <div class="jf-calendar-block">
                                    <div class="jf-calendar-top {{ $interview->scheduled_at->isToday() ? 'today' : ($isPast ? 'past' : '') }}">
                                        {{ $interview->scheduled_at->format('D') }}
                                    </div>
                                    <div class="jf-calendar-body">
                                        <div class="jf-calendar-day">{{ $interview->scheduled_at->format('d') }}</div>
                                        <div class="jf-calendar-month">{{ $interview->scheduled_at->format('M') }}</div>
                                    </div>
                                </div>

                                {{-- Details Section --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 sm:gap-4 mb-3">
                                        <div>
                                            <h4 class="text-xl font-extrabold text-slate-900 dark:text-white leading-tight mb-1">
                                                {{ $interview->title }}
                                            </h4>
                                            <div class="flex items-center gap-2 text-sm">
                                                <span class="font-bold text-slate-600 dark:text-slate-300">
                                                    {{ $interview->jobApplication?->jobPosting?->title ?? 'Unknown Position' }}
                                                </span>
                                                <span class="text-slate-400">@</span>
                                                <a href="#" class="jf-company-link">
                                                    {{ $interview->jobApplication?->jobPosting?->company?->name ?? 'Unknown Company' }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="flex flex-col sm:items-end">

                                            <span class="jf-status-pill {{ $interview->status }} shadow-sm">
                                                <i class="{{ $sc['icon'] }} me-1.5"></i>{{ $interview->getStatusLabel() }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Metadata Grid --}}
                                    <div class="flex flex-wrap gap-2.5 mb-5 mt-4">
                                        <div class="jf-meta-chip">
                                            <i class="ri-time-line"></i>
                                            <span>{{ $interview->scheduled_at->format('g:i A') }}</span>
                                        </div>
                                        <div class="jf-meta-chip">
                                            <i class="ri-refresh-line"></i>
                                            <span>{{ $interview->duration_minutes }} min Session</span>
                                        </div>
                                        <div class="jf-meta-chip">
                                            <i class="{{ $typeIcon }}"></i>
                                            <span>{{ $interview->getTypeLabel() }}</span>
                                        </div>
                                        @if($interview->location)
                                            <div class="jf-meta-chip">
                                                <i class="ri-map-pin-line"></i>
                                                <span>{{ $interview->location }}</span>
                                            </div>
                                        @endif
                                        @if($interview->employer)
                                            <div class="jf-meta-chip">
                                                <i class="ri-user-star-line"></i>
                                                <span>With {{ explode(' ', $interview->employer->name)[0] }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($isUpcoming)
                                        <div class="flex flex-wrap items-center gap-3 pt-5 border-t border-slate-50 dark:border-slate-800/50 mt-5">
                                            {{-- Primary Action: Join --}}
                                            @if($interview->meeting_link)
                                                <a href="{{ $interview->meeting_link }}" target="_blank" class="cd-btn cd-btn-primary px-8 jf-btn-pulse shadow-xl shadow-indigo-200/50 dark:shadow-none bg-gradient-to-r from-indigo-600 to-indigo-500 border-none hover:scale-105 active:scale-95 transition-all">
                                                    <i class="ri-video-chat-line me-2"></i> Join Meeting Room
                                                </a>
                                            @endif

                                            {{-- Small Info Label --}}
                                            @if($interview->scheduled_at->isToday())
                                                <div class="ms-auto hidden lg:flex items-center gap-2 text-[10px] font-black uppercase text-amber-500 bg-amber-50 dark:bg-amber-500/10 px-3 py-1.5 rounded-full border border-amber-100 dark:border-amber-500/20">
                                                    <i class="ri-flashlight-fill animate-pulse"></i> Session is Today
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @if($interview->description)
                                        <div class="mt-5 text-sm text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-900/50 rounded-2xl p-4 border border-slate-100 dark:border-slate-800/50 relative">
                                            <div class="absolute -top-2.5 left-4 bg-white dark:bg-slate-800 px-2 py-0.5 rounded text-[10px] font-black uppercase text-slate-400 border border-slate-100 dark:border-slate-800">Employer Notes</div>
                                            {{ $interview->description }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs text-gray-400">
                        <div>
                            @if($interviews->total() > 0)
                                Showing {{ $interviews->firstItem() }} to {{ $interviews->lastItem() }} of {{ $interviews->total() }} results
                            @else
                                Showing 0 results
                            @endif
                        </div>
                        <div class="cd-pagination">{{ $interviews->appends(['filter' => $filter])->links() }}</div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    </div> {{-- Close max-w-7xl mx-auto --}}

</x-app-layout>
