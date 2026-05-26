<x-app-layout>
    <x-slot name="pageTitle">Announcements</x-slot>
    <x-slot name="url_1">{"link": "/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Announcements</x-slot>

    <style>
        .ann-card {
            background: #ffffff;
            border: 1px solid var(--cd-border);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .ann-card:hover {
            transform: translateY(-2px);
            border-color: var(--cd-accent);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .ann-card-title {
            color: var(--cd-text);
        }

        .ann-card-date {
            color: var(--cd-text-muted);
        }

        .ann-card-body {
            color: var(--cd-text-secondary);
        }

        /* Theme switcher uses data-theme-mode, so provide explicit dark selectors. */
        :is([data-theme-mode="dark"], .dark) .ann-card {
            background: rgba(30, 41, 59, 0.4) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(10px);
        }
        :is([data-theme-mode="dark"], .dark) .ann-card-title {
            color: #f8fafc !important;
        }
        :is([data-theme-mode="dark"], .dark) .ann-card-date {
            color: #94a3b8 !important;
        }
        :is([data-theme-mode="dark"], .dark) .ann-card-body {
            color: #cbd5e1 !important;
        }

        .ann-type-badge {
            border: 1px solid transparent;
            font-family: inherit !important;
        }
        :is([data-theme-mode="dark"], .dark) .ann-type-badge {
            color: #e2e8f0 !important;
            background: rgba(148, 163, 184, 0.18) !important;
            border-color: rgba(148, 163, 184, 0.25) !important;
        }
        :is([data-theme-mode="dark"], .dark) .ann-type-badge.ann-type-release {
            color: #c7d2fe !important;
            background: rgba(99, 102, 241, 0.18) !important;
            border-color: rgba(99, 102, 241, 0.3) !important;
        }

        /* ── Modern Minimalist Header (Interactive Board Style) ── */
        .jf-header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 2rem;
            padding: 0.5rem 0 1.5rem 0;
            border-bottom: 2px solid #e2e8f0 !important;
            position: relative;
        }

        .jf-header-section.cd-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 0.75rem 1.5rem 1.5rem;
        }

        .jf-header-content { flex: 1; }

        .jf-context-row {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 0.75rem;
        }

        .jf-v-bar {
            width: 4px;
            height: 20px;
            background: #6366f1;
            border-radius: 4px;
        }

        .jf-context-label {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
            padding: 2px 10px;
            border-radius: 20px;
        }

        .jf-header-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.02em;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .jf-header-desc {
            font-size: 1rem;
            color: #64748b;
            max-width: 700px;
            line-height: 1.5;
        }

        .jf-header-desc b { color: #6366f1; font-weight: 700; }

        .jf-header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
        }

        :is([data-theme-mode="dark"], .dark) .jf-header-section {
            border-bottom: 2px solid #303134 !important;
            background: rgb(30, 32, 35) !important;
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-section.cd-section {
            border-color: #303134 !important;
            background: rgb(30, 32, 35) !important;
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-context-label { color: #ffffff !important; }
        :is([data-theme-mode="dark"], .dark) hr { border-top-color: rgba(255, 255, 255, 0.08) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-actions a,
        :is([data-theme-mode="dark"], .dark) .jf-header-actions button {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-actions a i,
        :is([data-theme-mode="dark"], .dark) .jf-header-actions button i {
            color: #ffffff !important;
        }

        @media (max-width: 992px) {
            .jf-header-section { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
            .jf-header-title { font-size: 1.875rem; }
            .jf-header-actions { width: 100%; flex-wrap: wrap; }
        }
    </style>

    @include('applicants.partials.candidate-styles')

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Modern Minimalist Header (Interactive Board Style) --}}
        <x-modern-header :container="false" chip="System Updates" id="wt-announcements-hero">
            <x-slot name="titleContent"><strong>Announcements</strong></x-slot>
            <x-slot name="description">
                Stay updated with the latest news, feature releases, and community announcements from our team. Keep track of what's new in your <b>Career Hub</b>.
            </x-slot>
            <x-slot name="actions">
                @auth
                    @if(auth()->user()->role === 'applicant')
                        <a href="{{ route('applicant.dashboard') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm" title="Dashboard">
                            <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                        </a>
                    @elseif(auth()->user()->role === 'employer')
                        <a href="{{ route('employer.dashboard') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm" title="Dashboard">
                            <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                        </a>
                    @elseif(in_array(auth()->user()->role, ['admin', 'super_admin', 'moderator']))
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm" title="Dashboard">
                            <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                        </a>
                    @endif
                @endauth
            </x-slot>
        </x-modern-header>

        <div class="grid grid-cols-12 gap-6" id="wt-announcements-list">
            <div class="col-span-12">
                @if($announcements->isEmpty())
                    <div class="jf-empty-state" style="animation: cd-slide-up 0.6s ease-out backwards;">
                        <div class="jf-empty-icon">
                            <i class="ri-megaphone-line"></i>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">All Quiet for Now</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto leading-relaxed">
                                Stay tuned! We'll post important system updates and announcements here as soon as they're available.
                            </p>
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($announcements as $announcement)
                                @php
                                    $typeStyles = [
                                        'info' => [
                                            'bg' => 'bg-blue-50/50 dark:bg-blue-900/10',
                                            'border' => 'border-blue-100 dark:border-blue-900/30',
                                            'accent' => 'bg-blue-500',
                                            'icon' => 'ri-information-line',
                                            'iconColor' => 'text-blue-500',
                                            'titleColor' => 'text-blue-900 dark:text-blue-100',
                                            'badge' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                        ],
                                        'success' => [
                                            'bg' => 'bg-green-50/50 dark:bg-green-900/10',
                                            'border' => 'border-green-100 dark:border-green-900/30',
                                            'accent' => 'bg-green-500',
                                            'icon' => 'ri-checkbox-circle-line',
                                            'iconColor' => 'text-green-500',
                                            'titleColor' => 'text-green-900 dark:text-green-100',
                                            'badge' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                                        ],
                                        'warning' => [
                                            'bg' => 'bg-yellow-50/50 dark:bg-yellow-900/10',
                                            'border' => 'border-yellow-100 dark:border-yellow-900/30',
                                            'accent' => 'bg-yellow-500',
                                            'icon' => 'ri-alert-line',
                                            'iconColor' => 'text-yellow-500',
                                            'titleColor' => 'text-yellow-900 dark:text-yellow-100',
                                            'badge' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                                        ],
                                        'danger' => [
                                            'bg' => 'bg-red-50/50 dark:bg-red-900/10',
                                            'border' => 'border-red-100 dark:border-red-900/30',
                                            'accent' => 'bg-red-500',
                                            'icon' => 'ri-error-warning-line',
                                            'iconColor' => 'text-red-500',
                                            'titleColor' => 'text-red-900 dark:text-red-100',
                                            'badge' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                                        ],
                                        'release' => [
                                            'bg' => 'bg-indigo-50/50 dark:bg-indigo-900/10',
                                            'border' => 'border-indigo-100 dark:border-indigo-900/30',
                                            'accent' => 'bg-indigo-500',
                                            'icon' => 'ri-rocket-line',
                                            'iconColor' => 'text-indigo-500',
                                            'titleColor' => 'text-indigo-900 dark:text-indigo-100',
                                            'badge' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300',
                                        ],
                                    ];

                                    $itemType = $announcement->is_release ? 'release' : $announcement->type;
                                    $style = $typeStyles[$itemType] ?? $typeStyles['info'];
                                @endphp

                                <div class="ann-card group relative rounded-2xl border {{ $style['border'] }} shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                                    <div class="p-5 md:p-6">
                                        <div class="flex flex-col md:flex-row md:items-start gap-5">
                                            {{-- Icon Section --}}
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 rounded-xl {{ $style['bg'] }} flex items-center justify-center border {{ $style['border'] }}">
                                                    <i class="{{ $style['icon'] }} text-2xl {{ $style['iconColor'] }}"></i>
                                                </div>
                                            </div>

                                            {{-- Content Section --}}
                                            <div class="flex-grow min-w-0">
                                                {{-- Top Row: Type & Badges --}}
                                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                                    <span class="ann-type-badge {{ $announcement->is_release ? 'ann-type-release' : '' }} px-2 py-0.5 rounded text-[0.65rem] font-bold tracking-wider uppercase {{ $style['badge'] }}">
                                                        {{ $announcement->is_release ? 'Release' : $announcement->type }}
                                                    </span>

                                                    @if(!$announcement->is_release && $announcement->is_pinned)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.65rem] font-bold tracking-wider uppercase bg-primary/10 text-primary">
                                                            <i class="ri-pushpin-fill me-1"></i> Pinned
                                                        </span>
                                                    @endif

                                                    @if($announcement->is_release && $announcement->version)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.65rem] font-bold bg-indigo-600 text-white uppercase tracking-widest">
                                                            v{{ $announcement->version }}
                                                        </span>
                                                    @endif
                                                </div>

                                                {{-- Title & Date --}}
                                                <div class="mb-4">
                                                    <h4 class="ann-card-title text-xl font-bold leading-tight {{ $style['titleColor'] }} mb-1 group-hover:text-primary transition-colors">
                                                        {{ $announcement->title }}
                                                    </h4>
                                                    <p class="ann-card-date text-[0.7rem] flex items-center gap-1.5 font-medium">
                                                        <i class="ri-calendar-line"></i>
                                                        {{ $announcement->display_date->format('F d, Y') }}
                                                        <span class="opacity-30 mx-1">•</span>
                                                        {{ $announcement->display_date->diffForHumans() }}
                                                    </p>
                                                </div>

                                                {{-- Body text --}}
                                                <div class="ann-card-body text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                                                    @if($announcement->is_release)
                                                        <div class="prose prose-sm dark:prose-invert max-w-none prose-p:mb-4 prose-p:leading-relaxed prose-li:my-2">
                                                            {!! \Illuminate\Support\Str::markdown($announcement->body, [
                                                                'renderer' => [
                                                                    'soft_break' => "<br />\n",
                                                                ],
                                                                'html_input' => 'strip',
                                                            ]) !!}
                                                        </div>
                                                    @else
                                                        <p class="whitespace-pre-wrap">{{ $announcement->content }}</p>
                                                    @endif
                                                </div>

                                                {{-- Expiration (if any) --}}
                                                @if(!$announcement->is_release && $announcement->expires_at)
                                                    <div class="ann-card-divider mt-4 pt-3 border-t border-gray-100 dark:border-gray-800">
                                                        <p class="text-[0.65rem] text-orange-600/80 dark:text-orange-400/80 font-semibold flex items-center gap-1">
                                                            <i class="ri-timer-2-line"></i>
                                                            Expires: {{ $announcement->expires_at->format('M d, Y') }} ({{ $announcement->expires_at->diffForHumans() }})
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div> {{-- Close max-w-7xl mx-auto --}}

    @include('applicants.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'wt-announcements-hero', 'icon' => 'ri-megaphone-line', 'title' => 'Stay Updated', 'body' => 'Welcome to Announcements! This page keeps you informed about important updates, new features, and releases. Check here regularly for critical information.', 'position' => 'bottom'],
            ['target' => 'wt-announcements-list', 'icon' => 'ri-list-check-2', 'title' => 'Browse All Announcements', 'body' => 'Scroll through all announcements sorted by type and date. Look for pinned announcements for high-priority updates and release notes for version information.', 'position' => 'top'],
        ],
        'wtKey' => 'announcements',
    ])
</x-app-layout>
