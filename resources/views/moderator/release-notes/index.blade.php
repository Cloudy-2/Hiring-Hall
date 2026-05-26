<x-app-layout page-title="Release Notes">
    <x-slot name="url_1">{"link": "/moderator/dashboard", "text": "Moderator"}</x-slot>
    <x-slot name="active">Version Management</x-slot>

    <style>
        .page-header-container {
            display: none;
        }

        /* Enhanced hero header */
        .vm-hero {
            background: linear-gradient(135deg, rgb(var(--primary)) 0%, rgba(var(--primary-rgb), 0.78) 100%);
            border-radius: 1.25rem;
            padding: 2rem 2.5rem;
            margin-bottom: 1.75rem;
            position: relative;
            overflow: hidden;
        }

        .vm-hero::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
        }

        .vm-hero::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: 30%;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
        }

        .vm-hero-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 1.75rem;
            position: relative;
            z-index: 1;
        }

        .vm-hero-icon {
            width: 3rem;
            height: 3rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #fff;
            flex-shrink: 0;
        }

        .vm-hero h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.03em;
            margin: 0 0 0.3rem;
            line-height: 1.1;
        }

        .vm-hero-sub {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.65);
            margin: 0;
        }

        .vm-hero-create {
            background: rgba(255, 255, 255, 0.18);
            color: #fff !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 0.75rem;
            padding: 0.6rem 1.25rem;
            font-size: 0.8125rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .vm-hero-create:hover {
            background: rgba(255, 255, 255, 0.28);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-1px);
        }

        .vm-hero-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            position: relative;
            z-index: 1;
            flex-wrap: wrap;
        }

        .vm-stats {
            display: flex;
            gap: 1.5rem;
        }

        .vm-stat {
            display: flex;
            flex-direction: column;
        }

        .vm-stat-val {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        .vm-stat-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: rgba(255, 255, 255, 0.55);
            margin-top: 0.2rem;
        }

        .vm-stat-divider {
            width: 1px;
            background: rgba(255, 255, 255, 0.15);
            align-self: stretch;
        }

        .vm-version-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875rem;
            font-weight: 700;
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 0.35rem 0.875rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .vm-filters {
            display: flex;
            gap: 0.4rem;
        }

        .vm-filter-btn {
            padding: 0.35rem 0.875rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: rgba(255, 255, 255, 0.7);
            background: rgba(255, 255, 255, 0.08);
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .vm-filter-btn:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
        }

        .vm-filter-btn.active {
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.4);
        }

        .vm-filter-badge {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            padding: 0 0.4rem;
            font-size: 0.65rem;
        }

        .platform-settings-card {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .setting-info {
            display: flex;
            flex-direction: column;
        }

        .setting-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            margin-bottom: 0.5rem;
        }

        .version-badge-big {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.125rem;
            font-weight: 700;
            color: rgb(var(--primary-rgb));
            background: rgba(var(--primary-rgb), 0.08);
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid rgba(var(--primary-rgb), 0.2);
        }

        .rn-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .rn-table th {
            background: #f8fafc;
            padding: 1rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .rn-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }

        .rn-table tr:last-child td {
            border-bottom: none;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .status-published {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .status-draft {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .action-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-icon {
            width: 2.25rem;
            height: 2.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.625rem;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #64748b;
        }

        .btn-icon:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
            color: #1e293b;
            transform: translateY(-1px);
        }

        .btn-icon-success:hover {
            color: #10b981;
            border-color: rgba(16, 185, 129, 0.3);
            background: rgba(16, 185, 129, 0.05);
        }

        .btn-icon-danger:hover {
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.3);
            background: rgba(239, 68, 68, 0.05);
        }

        .btn-icon-primary:hover {
            color: rgb(var(--primary-rgb));
            border-color: rgba(var(--primary-rgb), 0.3);
            background: rgba(var(--primary-rgb), 0.08);
        }

        [data-theme-mode="dark"] .vm-hero,
        .dark .vm-hero,
        html.dark .vm-hero {
            background: linear-gradient(135deg, rgb(var(--light)) 0%, rgba(var(--primary-rgb), 0.55) 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        [data-theme-mode="dark"] .header-title-section h1,
        .dark .header-title-section h1,
        html.dark .header-title-section h1 {
            color: #f1f5f9;
        }

        [data-theme-mode="dark"] .header-subtitle,
        .dark .header-subtitle,
        html.dark .header-subtitle {
            color: #94a3b8;
        }

        [data-theme-mode="dark"] .platform-settings-card,
        .dark .platform-settings-card,
        html.dark .platform-settings-card {
            background: rgba(30, 41, 59, 0.5);
            border-color: rgba(255, 255, 255, 0.05);
        }

        [data-theme-mode="dark"] .rn-table th,
        .dark .rn-table th,
        html.dark .rn-table th {
            background: rgba(15, 23, 42, 0.75);
            border-color: rgba(255, 255, 255, 0.08);
            color: #94a3b8;
        }

        [data-theme-mode="dark"] .rn-table td,
        .dark .rn-table td,
        html.dark .rn-table td {
            border-color: rgba(255, 255, 255, 0.06);
        }

        [data-theme-mode="dark"] .rn-table tbody tr,
        .dark .rn-table tbody tr,
        html.dark .rn-table tbody tr {
            background: rgba(2, 6, 23, 0.25);
        }

        [data-theme-mode="dark"] .rn-table tbody tr:hover,
        .dark .rn-table tbody tr:hover,
        html.dark .rn-table tbody tr:hover {
            background: rgba(148, 163, 184, 0.12);
        }

        [data-theme-mode="dark"] .rn-table h4,
        .dark .rn-table h4,
        html.dark .rn-table h4 {
            color: #f1f5f9;
        }

        [data-theme-mode="dark"] .rn-table .font-mono,
        .dark .rn-table .font-mono,
        html.dark .rn-table .font-mono {
            color: #cbd5e1;
        }

        [data-theme-mode="dark"] .rn-summary,
        .dark .rn-summary,
        html.dark .rn-summary {
            color: #94a3b8 !important;
        }

        [data-theme-mode="dark"] .status-published,
        .dark .status-published,
        html.dark .status-published {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }

        [data-theme-mode="dark"] .status-draft,
        .dark .status-draft,
        html.dark .status-draft {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
        }

        [data-theme-mode="dark"] .btn-icon,
        .dark .btn-icon,
        html.dark .btn-icon {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #94a3b8;
        }

        [data-theme-mode="dark"] .btn-icon:hover,
        .dark .btn-icon:hover,
        html.dark .btn-icon:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            color: #f1f5f9;
        }

        [data-theme-mode="dark"] .btn-icon-success:hover,
        .dark .btn-icon-success:hover,
        html.dark .btn-icon-success:hover {
            color: #34d399;
            border-color: rgba(52, 211, 153, 0.3);
            background: rgba(52, 211, 153, 0.08);
        }

        [data-theme-mode="dark"] .btn-icon-danger:hover,
        .dark .btn-icon-danger:hover,
        html.dark .btn-icon-danger:hover {
            color: #f87171;
            border-color: rgba(248, 113, 113, 0.3);
            background: rgba(248, 113, 113, 0.08);
        }

        [data-theme-mode="dark"] .btn-icon-primary:hover,
        .dark .btn-icon-primary:hover,
        html.dark .btn-icon-primary:hover {
            color: rgba(var(--primary-rgb), 0.95);
            border-color: rgba(var(--primary-rgb), 0.35);
            background: rgba(var(--primary-rgb), 0.14);
        }

        [data-theme-mode="dark"] .box,
        .dark .box,
        html.dark .box {
            background: rgba(15, 23, 42, 0.65) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        [data-theme-mode="dark"] .rn-date,
        .dark .rn-date,
        html.dark .rn-date {
            color: #e2e8f0 !important;
        }

        [data-theme-mode="dark"] .rn-ago,
        .dark .rn-ago,
        html.dark .rn-ago {
            color: #94a3b8 !important;
        }

        /* 3-dot dropdown */
        .rn-action-wrap {
            position: relative;
            display: inline-block;
        }

        .rn-dot-btn {
            width: 2.25rem;
            height: 2.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.625rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
        }

        .rn-dot-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        [data-theme-mode="dark"] .rn-dot-btn,
        .dark .rn-dot-btn,
        html.dark .rn-dot-btn {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #94a3b8;
        }

        [data-theme-mode="dark"] .rn-dot-btn:hover,
        .dark .rn-dot-btn:hover,
        html.dark .rn-dot-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            color: #f1f5f9;
        }

        .rn-dropdown {
            display: none;
            position: absolute;
            right: 0;
            min-width: 210px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            z-index: 9999;
            overflow: hidden;
        }

        .rn-dropdown.open {
            display: block;
        }

        .rn-dropdown.drop-down {
            top: calc(100% + 6px);
            bottom: auto;
        }

        .rn-dropdown.drop-up {
            bottom: calc(100% + 6px);
            top: auto;
        }

        [data-theme-mode="dark"] .rn-dropdown,
        .dark .rn-dropdown,
        html.dark .rn-dropdown {
            background: #1e293b;
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .rn-dd-item {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.625rem 1rem;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.15s;
        }

        .rn-dd-item:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .rn-dd-item i {
            font-size: 1rem;
            width: 1.1rem;
            text-align: center;
        }

        .rn-dd-item.danger {
            color: #ef4444;
        }

        .rn-dd-item.danger:hover {
            background: rgba(239, 68, 68, 0.07);
            color: #dc2626;
        }

        .rn-dd-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 0.25rem 0;
        }

        [data-theme-mode="dark"] .rn-dd-item,
        .dark .rn-dd-item,
        html.dark .rn-dd-item {
            color: #cbd5e1;
        }

        [data-theme-mode="dark"] .rn-dd-item:hover,
        .dark .rn-dd-item:hover,
        html.dark .rn-dd-item:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #f1f5f9;
        }

        [data-theme-mode="dark"] .rn-dd-item.danger,
        .dark .rn-dd-item.danger,
        html.dark .rn-dd-item.danger {
            color: #f87171;
        }

        [data-theme-mode="dark"] .rn-dd-item.danger:hover,
        .dark .rn-dd-item.danger:hover,
        html.dark .rn-dd-item.danger:hover {
            background: rgba(248, 113, 113, 0.08);
        }

        [data-theme-mode="dark"] .rn-dd-divider,
        .dark .rn-dd-divider,
        html.dark .rn-dd-divider {
            background: rgba(255, 255, 255, 0.06);
        }
    </style>

    {{-- Enhanced hero header --}}
    <x-modern-header chip="Version Management" title="Release Notes"
        desc="Manage version history and public announcements for the Hiring Hall platform.">
        <x-slot:actions>
            <a href="{{ route('moderator.release-notes.create') }}" class="ti-btn ti-btn-primary">
                <i class="ri-add-line me-1"></i> New Version
            </a>
        </x-slot:actions>
        <x-slot:footer>
            <div class="flex flex-wrap items-center justify-between gap-4 mt-4 w-full">
                <div class="flex gap-6 items-center">
                    <div class="flex flex-col">
                        <span class="text-2xl font-extrabold text-white leading-none">{{ $counts['all'] }}</span>
                        <span class="text-[0.65rem] font-bold uppercase tracking-wider text-white/60 mt-1">Total</span>
                    </div>
                    <div class="w-px h-8 bg-white/15"></div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-extrabold text-white leading-none">{{ $counts['published'] }}</span>
                        <span
                            class="text-[0.65rem] font-bold uppercase tracking-wider text-white/60 mt-1">Published</span>
                    </div>
                    <div class="w-px h-8 bg-white/15"></div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-extrabold text-white leading-none">{{ $counts['draft'] }}</span>
                        <span class="text-[0.65rem] font-bold uppercase tracking-wider text-white/60 mt-1">Drafts</span>
                    </div>
                    <div class="w-px h-8 bg-white/15"></div>
                    <div class="flex flex-col">
                        <div
                            class="font-mono text-sm font-bold text-white bg-white/10 border border-white/20 px-3 py-1 rounded-lg flex items-center gap-1">
                            <i class="ri-rocket-2-line"></i> v{{ $appVersion }}
                        </div>
                        <span class="text-[0.65rem] font-bold uppercase tracking-wider text-white/60 mt-1">Current
                            Release</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('moderator.release-notes.index') }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider no-underline border transition-all inline-flex items-center gap-1.5 {{ $filter === 'all' ? 'bg-white/20 text-white border-white/40' : 'border-white/25 text-white/70 bg-white/10 hover:bg-white/15 hover:text-white' }}">All
                        <span
                            class="bg-white/20 rounded-full px-1.5 py-0.5 text-[0.65rem]">{{ $counts['all'] }}</span></a>
                    <a href="{{ route('moderator.release-notes.index', ['filter' => 'published']) }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider no-underline border transition-all inline-flex items-center gap-1.5 {{ $filter === 'published' ? 'bg-white/20 text-white border-white/40' : 'border-white/25 text-white/70 bg-white/10 hover:bg-white/15 hover:text-white' }}">Published
                        <span
                            class="bg-white/20 rounded-full px-1.5 py-0.5 text-[0.65rem]">{{ $counts['published'] }}</span></a>
                    <a href="{{ route('moderator.release-notes.index', ['filter' => 'draft']) }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider no-underline border transition-all inline-flex items-center gap-1.5 {{ $filter === 'draft' ? 'bg-white/20 text-white border-white/40' : 'border-white/25 text-white/70 bg-white/10 hover:bg-white/15 hover:text-white' }}">Drafts
                        <span
                            class="bg-white/20 rounded-full px-1.5 py-0.5 text-[0.65rem]">{{ $counts['draft'] }}</span></a>
                </div>
            </div>
        </x-slot:footer>
    </x-modern-header>

    <div class="mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="box border-0 shadow-sm overflow-hidden rounded-2xl">
            <div class="box-body !p-0">
                @if($releaseNotes->isEmpty())
                    <div class="text-center py-20 bg-white dark:bg-transparent">
                        <div
                            class="w-20 h-20 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="ri-history-line text-4xl text-slate-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No release history found</h3>
                        <p class="text-slate-500 max-w-sm mx-auto">Start documenting your platform improvements by creating
                            your first release note.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="rn-table">
                            <thead>
                                <tr>
                                    <th class="w-32">Version</th>
                                    <th>Details</th>
                                    <th class="w-48">Release Date</th>
                                    <th class="w-32">Status</th>
                                    <th class="text-center w-48">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($releaseNotes as $note)
                                    <tr class="{{ !$note->is_published ? 'bg-slate-50/30' : '' }}">
                                        <td>
                                            @if($note->version)
                                                <span
                                                    class="font-mono font-bold text-slate-700 dark:text-slate-300">v{{ $note->version }}</span>
                                            @else
                                                <span class="text-slate-300">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <h4 class="font-bold text-slate-900 dark:text-white mb-1.5 leading-none">
                                                {{ $note->title }}</h4>
                                            <p class="rn-summary text-xs text-slate-500 line-clamp-1 italic">
                                                {{ Str::limit($note->body, 120) }}</p>
                                        </td>
                                        <td>
                                            <div class="flex flex-col">
                                                <span
                                                    class="rn-date text-sm font-semibold text-slate-700">{{ $note->released_at->format('F j, Y') }}</span>
                                                <span
                                                    class="rn-ago text-[10px] uppercase font-bold text-slate-400">{{ $note->released_at->diffForHumans() }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($note->is_published)
                                                <span class="status-pill status-published">Published</span>
                                            @else
                                                <span class="status-pill status-draft">Draft</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="rn-action-wrap">
                                                <button class="rn-dot-btn" onclick="toggleRnDropdown(this)" type="button">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <div class="rn-dropdown">
                                                    <a href="{{ route('moderator.release-notes.edit', $note) }}"
                                                        class="rn-dd-item">
                                                        <i class="ri-edit-2-line"></i> Edit
                                                    </a>

                                                    @if(!$note->is_published)
                                                        <form
                                                            action="{{ route('moderator.release-notes.toggle-published', $note) }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="set_as_system_version" value="1">
                                                            <button type="submit" class="rn-dd-item" style="color:#10b981">
                                                                <i class="ri-rocket-fill"></i> Publish
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form
                                                            action="{{ route('moderator.release-notes.toggle-published', $note) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit" class="rn-dd-item">
                                                                <i class="ri-eye-off-line"></i> Revert to Draft
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($note->is_published && $note->version && $note->version !== $appVersion)
                                                        <form action="{{ route('moderator.release-notes.update-version') }}"
                                                            method="POST">
                                                            @csrf
                                                            <input type="hidden" name="system_version" value="{{ $note->version }}">
                                                            <button type="submit" class="rn-dd-item" style="color:#6366f1">
                                                                <i class="ri-arrow-up-circle-line"></i> Promote to Production
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <div class="rn-dd-divider"></div>

                                                    <form action="{{ route('moderator.release-notes.destroy', $note) }}"
                                                        method="POST" onsubmit="return confirmDelete(this)">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="rn-dd-item danger">
                                                            <i class="ri-delete-bin-line"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($releaseNotes->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                            {{ $releaseNotes->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <script>
            function toggleRnDropdown(btn) {
                const wrap = btn.closest('.rn-action-wrap');
                const dd = wrap.querySelector('.rn-dropdown');
                const isOpen = dd.classList.contains('open');
                // close all others
                document.querySelectorAll('.rn-dropdown.open').forEach(d => d.classList.remove('open'));
                if (!isOpen) {
                    // Determine if dropdown fits below or should flip up
                    dd.classList.remove('drop-down', 'drop-up');
                    dd.classList.add('drop-down');
                    dd.classList.add('open');
                    const rect = dd.getBoundingClientRect();
                    if (rect.bottom > window.innerHeight - 12) {
                        dd.classList.remove('drop-down');
                        dd.classList.add('drop-up');
                    }
                }
            }
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.rn-action-wrap')) {
                    document.querySelectorAll('.rn-dropdown.open').forEach(d => d.classList.remove('open'));
                }
            });

            function confirmDelete(form) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Once deleted, this release note cannot be recovered and will be removed from the public timeline.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'rounded-2xl border-0',
                        confirmButton: 'ti-btn ti-btn-danger px-6 py-2.5 rounded-xl font-bold uppercase text-xs',
                        cancelButton: 'ti-btn ti-btn-light px-6 py-2.5 rounded-xl font-bold uppercase text-xs'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
                return false;
            }
        </script>
</x-app-layout>