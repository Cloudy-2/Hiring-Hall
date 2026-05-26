
    <style>
        .rn-timeline {
            position: relative;
            padding: 2rem 0;
        }
        /* Mobile-first timeline line */
        .rn-timeline::before {
            content: '';
            position: absolute;
            left: 1.5rem;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, rgba(var(--primary-rgb), 0), rgba(var(--primary-rgb), 0.3) 10%, rgba(var(--primary-rgb), 0.3) 90%, rgba(var(--primary-rgb), 0));
            border-radius: 4px;
        }
        @media (min-width: 1024px) {
            .rn-timeline::before {
                left: 50%;
                margin-left: -1.5px;
            }
        }
        .rn-item {
            position: relative;
            margin-bottom: 4rem;
        }
        .rn-marker {
            position: absolute;
            left: 1.5rem;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            background: #4f46e5;
            border: 4px solid #fff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
            z-index: 10;
            margin-left: -0.625rem;
            top: 1.75rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .rn-item:hover .rn-marker {
            transform: scale(1.3);
            box-shadow: 0 0 0 8px rgba(79, 70, 229, 0.25);
            background: #6366f1;
        }
        @media (min-width: 1024px) {
            .rn-marker {
                left: 50%;
            }
        }
        .rn-card {
            position: relative;
            width: calc(100% - 3.5rem);
            margin-left: 3.5rem;
            background: #fff;
            border-radius: 1.25rem;
            padding: 2rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.03), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .rn-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 25px 30px -5px rgba(0, 0, 0, 0.08), 0 15px 15px -5px rgba(0, 0, 0, 0.03);
            border-color: rgba(79, 70, 229, 0.4);
        }
        @media (min-width: 1024px) {
            .rn-card {
                width: calc(50% - 4rem);
                margin-left: 0;
            }
            .rn-item:nth-child(even) .rn-card {
                margin-left: auto;
            }
            /* Right-facing triangle for left card */
            .rn-item:nth-child(odd) .rn-card::after {
                content: '';
                position: absolute;
                top: 1.75rem;
                right: -10px;
                border-width: 10px 0 10px 10px;
                border-style: solid;
                border-color: transparent transparent transparent #fff;
                display: block;
            }
            /* Left-facing triangle for right card */
            .rn-item:nth-child(even) .rn-card::after {
                content: '';
                position: absolute;
                top: 1.75rem;
                left: -10px;
                border-width: 10px 10px 10px 0;
                border-style: solid;
                border-color: transparent #fff transparent transparent;
                display: block;
            }
        }

        /* Dark mode support */
        :is([data-theme-mode="dark"], .dark) .rn-card {
            background: rgba(30, 41, 59, 0.4);
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(12px);
        }
        :is([data-theme-mode="dark"], .dark) .rn-marker {
            border-color: #0f172a;
        }
        :is([data-theme-mode="dark"], .dark) .rn-item:nth-child(odd) .rn-card::after {
            border-color: transparent transparent transparent rgba(30, 41, 59, 0.8);
        }
        :is([data-theme-mode="dark"], .dark) .rn-item:nth-child(even) .rn-card::after {
            border-color: transparent rgba(30, 41, 59, 0.8) transparent transparent;
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-section {
            border-bottom-color: rgba(255, 255, 255, 0.08) !important;
            background: rgb(30, 32, 35) !important;
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-context-label { color: #ffffff !important; }
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
        :is([data-theme-mode="dark"], .dark) hr { border-top-color: rgba(255, 255, 255, 0.08) !important; }

        .prose ul {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        .rn-version-tag {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(99, 102, 241, 0.1));
            color: #4f46e5;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 700;
            border: 1px solid rgba(79, 70, 229, 0.2);
            font-size: 0.75rem;
        }
        html.dark .rn-version-tag, .dark-theme .rn-version-tag {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(129, 140, 248, 0.2));
            color: #a5b4fc;
            border-color: rgba(99, 102, 241, 0.3);
        }

        /* ── Modern Minimalist Header (Interactive Board Style) ── */
        .jf-header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 2rem;
            padding: 0.5rem 0 1.5rem 0;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
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

        @media (max-width: 992px) {
            .jf-header-section { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
            .jf-header-title { font-size: 1.875rem; }
            .jf-header-actions { width: 100%; flex-wrap: wrap; }
        }
    </style>

<x-app-layout>
    <x-slot name="title">Release Notes</x-slot>
    <x-slot name="url_1">{"link": "{{ route('release-notes.index') }}", "text": "Release Notes"}</x-slot>
    <x-slot name="active">Release Notes</x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Unified Premium Styles --}}
        @include('applicants.partials.candidate-styles')

        {{-- Modern Minimalist Header (Interactive Board Style) --}}
        <x-modern-header :container="false" chip="Version History" id="wt-release-hero">
            <x-slot name="titleContent"><strong>Release Notes</strong></x-slot>
            <x-slot name="description">
                Documenting our journey. Follow the latest improvements, new features, and bug fixes on the <b>HillBCS Hire</b> platform. Stay updated with our continuous growth.
            </x-slot>
            <x-slot name="actions">
                @auth
                    @if(auth()->user()->role === 'applicant')
                        <a href="{{ route('applicant.dashboard') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm">
                            <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                        </a>
                    @elseif(auth()->user()->role === 'employer')
                        <a href="{{ route('employer.dashboard') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm">
                            <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                        </a>
                    @elseif(in_array(auth()->user()->role, ['admin', 'super_admin', 'moderator']))
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm">
                            <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                        </a>
                    @endif
                @endauth
            </x-slot>
        </x-modern-header>

    <div class="px-4 pb-20">
        <div class="max-w-6xl mx-auto">
            @if($releaseNotes->isEmpty())
                <div class="box border dark:border-white/10 rounded-3xl overflow-hidden">
                    <div class="box-body text-center py-24 bg-gradient-to-br from-white to-gray-50/50 dark:from-white/5 dark:to-white/0">
                        <div class="w-24 h-24 bg-light dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                            <i class="ri-chat-history-line text-5xl text-textmuted/30"></i>
                        </div>
                        <h3 class="text-2xl font-black text-gray-800 dark:text-white mb-3">No release notes yet</h3>
                        <p class="text-textmuted dark:text-textmuted/60 max-w-sm mx-auto">We're constantly improving the platform. Check back soon for our first update log!</p>
                    </div>
                </div>
            @else
                <div class="rn-timeline" id="wt-release-timeline">
                    @foreach($releaseNotes as $note)
                        <div class="rn-item group">
                            <div class="rn-marker"></div>
                            <div class="rn-card">
                                <div class="flex flex-wrap items-center justify-between gap-4 mb-6 flex-nowrap">
                                    <div class="flex items-center gap-3 flex-nowrap">
                                        @if($note->version)
                                            <span class="rn-version-tag whitespace-nowrap">
                                                v{{ $note->version }}
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-textmuted uppercase tracking-widest opacity-80 whitespace-nowrap">
                                            <i class="ri-calendar-line"></i>
                                            {{ $note->released_at->format('M j, Y') }}
                                        </span>
                                    </div>
                                    @if($note->version && $note->version === $appVersion)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-success/15 text-success border border-success/30 uppercase tracking-tighter shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-success me-1.5 animate-ping"></span>
                                            Current Version
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-5 leading-tight group-hover:text-primary transition-colors">
                                    {{ $note->title }}
                                </h3>

                                <div class="text-gray-600 dark:text-gray-300 prose dark:prose-invert max-w-none prose-headings:text-gray-900 dark:prose-headings:text-white prose-a:text-primary prose-strong:text-gray-900 dark:prose-strong:text-white prose-blockquote:border-primary prose-li:marker:text-primary text-[15px] leading-relaxed">
                                    {!! \Illuminate\Support\Str::markdown($note->body, [
                                        'renderer' => [
                                            'soft_break' => "<br />\n",
                                        ],
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($releaseNotes->hasPages())
                    <div class="mt-16 flex justify-center">
                        <div class="bg-white dark:bg-white/5 p-2 rounded-2xl border dark:border-white/10 shadow-sm">
                            {{ $releaseNotes->links() }}
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
    </div> {{-- Close max-w-7xl mx-auto --}}

    @include('applicants.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'wt-release-hero', 'icon' => 'ri-history-line', 'title' => 'Track Our Progress', 'body' => 'Welcome to Release Notes! This page shows all the updates, new features, and bug fixes we\'ve made to HillBCS Hire. Each release is timestamped so you can see when improvements were rolled out.', 'position' => 'bottom'],
            ['target' => 'wt-release-timeline', 'icon' => 'ri-calendar-timeline-line', 'title' => 'Browse Version History', 'body' => 'Scroll through the timeline to explore different versions. Each card shows what was improved or added in that release. Look for the "Current Version" badge to see which version you\'re using right now.', 'position' => 'top'],
        ],
        'wtKey' => 'release-notes',
    ])
</x-app-layout>
