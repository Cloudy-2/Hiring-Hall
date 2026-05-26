<x-app-layout>

    <x-slot name="pageTitle">Employer Dashboard</x-slot>
    <x-slot name="active">Dashboard</x-slot>

    @include('employers.partials.employer-styles')

    @php
        $logoBgs = ['#4f46e5', '#0d9488', '#dc2626', '#7c3aed', '#ea580c', '#0284c7'];
        $statusStyles = [
            'applied' => ['bg' => 'rgba(96, 165, 250, 0.18)', 'text' => '#93c5fd', 'label' => 'Applied'],
            'submitted' => ['bg' => 'rgba(96, 165, 250, 0.18)', 'text' => '#93c5fd', 'label' => 'Applied'],
            'under_review' => ['bg' => 'rgba(245, 158, 11, 0.20)', 'text' => '#fcd34d', 'label' => 'Under Review'],
            'application_viewed' => ['bg' => 'rgba(56, 189, 248, 0.18)', 'text' => '#7dd3fc', 'label' => 'Viewed'],
            'viewed' => ['bg' => 'rgba(56, 189, 248, 0.18)', 'text' => '#7dd3fc', 'label' => 'Viewed'],
            'in_progress' => ['bg' => 'rgba(167, 139, 250, 0.18)', 'text' => '#c4b5fd', 'label' => 'Interviewing'],
            'accepted' => ['bg' => 'rgba(74, 222, 128, 0.18)', 'text' => '#86efac', 'label' => 'Accepted'],
            'not_selected' => ['bg' => 'rgba(251, 113, 133, 0.18)', 'text' => '#fda4af', 'label' => 'Declined'],
            'no_longer_under_consideration' => ['bg' => 'rgba(251, 113, 133, 0.18)', 'text' => '#fda4af', 'label' => 'Declined'],
            'closed' => ['bg' => 'rgba(148, 163, 184, 0.20)', 'text' => '#cbd5e1', 'label' => 'Closed'],
        ];

        $totalSourceCount = max(1, collect($sourceSegments)->sum('count'));
        $ringSteps = [];
        $ringCursor = 0;
        foreach ($sourceSegments as $segment) {
            $portion = round(($segment['count'] / $totalSourceCount) * 100, 2);
            $nextCursor = min(100, $ringCursor + $portion);
            $ringSteps[] = $segment['color'] . ' ' . $ringCursor . '% ' . $nextCursor . '%';
            $ringCursor = $nextCursor;
        }
        if ($ringCursor < 100) {
            $ringSteps[] = '#64748b ' . $ringCursor . '% 100%';
        }
        $ringStyle = 'background: conic-gradient(' . implode(', ', $ringSteps) . ' );';

        $maxStatusCount = max(1, collect($statusBreakdown)->max('count'));
    @endphp

    <x-modern-header chip="Dashboard Overview" title="Welcome Back! 👋 {{ Auth::user()->name }}."
        desc='Manage your job postings, monitor applications, and move candidates through your pipeline faster.'>
        <x-slot name="actions">
            <a href="{{ route('jobs.create') }}" class="edb-post-btn">
                <i class="ri-add-line"></i>
                <span>Post a Job</span>
            </a>
            <a href="{{ route('employer.applications.index') }}" class="edb-post-btn edb-post-btn-ghost">
                <i class="ri-team-line"></i>
                <span>Manage Applications</span>
            </a>
        </x-slot>
    </x-modern-header>

    <div class="max-w-7xl mx-auto pb-6 sm:px-6 lg:px-8">
        <div class="edb-stat-grid" id="wt-overview">
            @foreach($dashboardStats as $stat)
                <a href="{{ $stat['route'] ?? '#' }}" class="edb-tracking-card {{ $stat['chip'] }} group" title="View details for {{ $stat['label'] }}">
                    <div class="edb-tracking-lbl">{{ $stat['label'] }}</div>
                    <div class="edb-tracking-icon-box">
                        <i class="{{ $stat['icon'] }}"></i>
                    </div>
                    <div class="edb-tracking-val">{{ $stat['value'] }}</div>
                    @if(!empty($stat['trend']))
                        <div class="edb-tracking-insight">
                            {!! $stat['trend'] !!}
                        </div>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="edb-main-grid">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="edb-panel edb-panel-large" id="wt-recent-apps">
                <div class="edb-panel-head">
                    <div class="flex items-center gap-3">
                        <h2>Recent Applications</h2>
                        @if($totalApplications > 0)
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase bg-blue-50 text-blue-700 border border-blue-100 dark:bg-blue-900/40 dark:text-blue-400 dark:border-blue-800/50">
                                {{ $totalApplications }} Applications
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('employer.applications.index') }}" class="edb-link">View All <i
                            class="ri-arrow-right-s-line"></i></a>
                </div>

                @if($recentApplications->isEmpty())
                    <div class="edb-empty" style="padding: 3rem 1rem; text-align: center; background: rgba(59, 130, 246, 0.03); border-radius: 1rem; border: 1px dashed rgba(59, 130, 246, 0.2);">
                        <div style="background: white; width: 64px; height: 64px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 2rem; color: #3b82f6; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1); margin-bottom: 1rem;">
                            <i class="ri-inbox-archive-line"></i>
                        </div>
                        <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem;">No Applications Received Yet</h3>
                        <p style="color: var(--text-sub); font-size: 0.9rem; max-width: 400px; margin: 0 auto 1.5rem;">When candidates apply to your job postings, they will appear here. To start building your talent pipeline, you need active job listings.</p>
                        <a href="{{ route('jobs.create') }}" class="edb-post-btn" style="display: inline-flex;">
                            <i class="ri-add-line mr-1"></i> Post Your First Job
                        </a>
                    </div>
                @else
                    <div class="flex flex-col gap-3 mt-2">
                        @foreach($recentApplications as $idx => $app)
                            @php 
                                $sc = $statusStyles[$app->status] ?? ['bg' => 'rgba(148, 163, 184, 0.1)', 'text' => '#94a3b8', 'label' => \Illuminate\Support\Str::headline($app->status)]; 
                                $candidateName = $app->applicantProfile?->display_name ?? $app->applicantProfile?->user?->name ?? 'Unknown Candidate';
                                $initials = collect(explode(' ', $candidateName))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->join('');
                                $lbgColors = ['bg-indigo-600', 'bg-teal-600', 'bg-rose-600', 'bg-purple-600', 'bg-sky-600'];
                                $lbg = $lbgColors[$idx % count($lbgColors)];
                            @endphp
                            <a href="{{ route('employer.applications.index') }}"
                               class="group flex items-center gap-4 p-4 rounded-xl border transition-all duration-300 hover:-translate-y-1 hover:shadow-md"
                               onmouseover="this.style.borderColor='#3b82f6'" 
                               onmouseout="this.style.borderColor='var(--border-card)'"
                               style="border-color: var(--border-card); background: var(--bg-card);">
                                
                                {{-- Avatar Initials / Profile Picture --}}
                                @php
                                    $appUser = $app->applicantProfile?->user;
                                    $hasPhoto = $appUser && $appUser->profile_photo_path;
                                @endphp
                                <div class="w-11 h-11 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-sm shadow-sm overflow-hidden {{ $hasPhoto ? 'bg-gray-100' : $lbg }}">
                                    @if($hasPhoto)
                                        <img src="{{ $appUser->profile_photo_url }}" class="w-full h-full object-cover" alt="{{ $candidateName }}">
                                    @else
                                        {{ $initials }}
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="min-w-0">
                                            <div class="font-bold text-[15px] truncate transition-colors" style="color:var(--text-main)" 
                                                 onmouseover="this.style.color='#3b82f6'" 
                                                 onmouseout="this.style.color='var(--text-main)'">
                                                {{ $candidateName }}
                                            </div>
                                            <div class="flex items-center flex-wrap gap-2 text-[11.5px] mt-0.5" style="color:var(--text-sub)">
                                                <span class="flex items-center font-medium truncate"><i class="ri-briefcase-line mr-1 opacity-70"></i> {{ $app->jobPosting->title }}</span>
                                                <span class="opacity-40">•</span>
                                                <span class="flex items-center"><i class="ri-time-line mr-1 opacity-70"></i> {{ $app->applied_at?->diffForHumans() ?? 'Recently' }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex-shrink-0 ml-2">
                                            <span class="inline-flex items-center px-2.5 h-6 rounded-full border text-[10px] font-bold tracking-wide whitespace-nowrap"
                                                  style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }}; border-color: {{ $sc['text'] }}33;">
                                                <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; margin-right: 6px; background:{{ $sc['text'] }}"></span>
                                                {{ $sc['label'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="edb-panel" id="wt-recent-jobs">
                <div class="edb-panel-head">
                    <div class="flex items-center gap-3">
                        <h2>Recent Jobs</h2>
                        @if($totalJobs > 0)
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wide uppercase bg-indigo-50 text-indigo-700 border border-indigo-100 dark:bg-indigo-900/40 dark:text-indigo-400 dark:border-indigo-800/50">
                                {{ $totalJobs }} Total Jobs
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('employer.jobs.index') }}" class="edb-link">View All <i
                            class="ri-arrow-right-s-line"></i></a>
                </div>

                <div style="max-height: 500px; overflow-y: auto; padding-right: 0.5rem;" class="edb-custom-scrollbar">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; margin-top: 0.5rem; padding-bottom: 0.5rem;">
                        @forelse($recentJobs as $idx => $job)
                            @php
                                $jc = ['hex' => '#3b82f6', 'rgb' => '59, 130, 246']; // Solid Blue
                            @endphp
                        <a href="{{ route('jobs.show', $job->slug) }}"
                           class="group flex flex-col p-4 rounded-xl border transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                           onmouseover="this.style.borderColor='{{ $jc['hex'] }}'" 
                           onmouseout="this.style.borderColor='var(--border-card)'"
                           style="border-color: var(--border-card); background: var(--bg-card);">
                            
                            <div class="flex items-start justify-between mb-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shadow-sm" style="background-color: rgba({{ $jc['rgb'] }}, 0.12); color: {{ $jc['hex'] }};">
                                    <i class="ri-briefcase-4-line"></i>
                                </div>
                                @php
                                    $statusStyle = $job->status === 'open' 
                                        ? 'background-color: rgba(16, 185, 129, 0.1); color: #059669; border-color: rgba(16, 185, 129, 0.3); border-radius: 9999px;' 
                                        : 'background-color: rgba(100, 116, 139, 0.1); color: #475569; border-color: rgba(100, 116, 139, 0.3); border-radius: 9999px;';
                                @endphp
                                <span class="inline-flex items-center px-2 h-4 border font-bold uppercase tracking-wide" style="font-size: 9px; {{ $statusStyle }} line-height: 1;">
                                    {{ $job->status === 'open' ? 'Active' : 'Closed' }}
                                </span>
                            </div>

                            <div class="font-bold text-[15px] mb-1 transition-colors line-clamp-1" style="color:var(--text-main)" 
                                 onmouseover="this.style.color='{{ $jc['hex'] }}'" 
                                 onmouseout="this.style.color='var(--text-main)'">
                                {{ $job->title }}
                            </div>
                            
                            <div class="flex flex-wrap gap-2 text-[11px] font-medium mt-1 mb-3" style="color:var(--text-sub)">
                                <span class="flex items-center"><i class="ri-map-pin-line mr-1"></i> {{ $job->location ?? 'Remote' }}</span>
                                <span class="flex items-center"><i class="ri-time-line mr-1"></i> Posted {{ $job->posted_at?->diffForHumans() ?? 'Draft' }}</span>
                            </div>
                            
                            <div class="mt-auto pt-3 border-t border-[var(--border-card)] text-xs font-bold transition-colors flex items-center gap-1" style="color: {{ $jc['hex'] }};">
                                Manage Job <i class="ri-arrow-right-line transition-transform group-hover:translate-x-1"></i>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full text-center py-10" style="background: rgba(99, 102, 241, 0.03); border-radius: 1rem; border: 1px dashed rgba(99, 102, 241, 0.2);">
                            <div style="width: 64px; height: 64px; background: white; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; color: #6366f1; font-size: 2rem; box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.1); margin-bottom: 1rem;">
                                <i class="ri-briefcase-add-line"></i>
                            </div>
                            <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.5rem;">Build Your Team</h3>
                            <p style="color: var(--text-sub); font-size: 0.9rem; max-width: 400px; margin: 0 auto 1.5rem;">You haven't posted any jobs yet. Create detailed job listings to attract your ideal candidates directly to your platform.</p>
                            <a href="{{ route('jobs.create') }}" class="edb-post-btn edb-post-btn-ghost" style="display: inline-flex; background: white; border: 1px solid #e2e8f0; color: #4f46e5;">
                                <i class="ri-pencil-ruler-2-line mr-1"></i> Create Job Listing
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

            <div class="edb-side-col">
                <div class="edb-panel" id="wt-quick-stats">
                    <div class="edb-panel-head">
                        <h2>Quick Stats</h2>
                    </div>

                    <div class="edb-charts-grid">
                        <div class="edb-chart-box" style="padding: 1rem;">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-bold cursor-default" style="font-size: 16px; color:var(--text-main)">Application Trends</h3>
                                <span class="px-2 py-1 font-bold uppercase tracking-wide flex items-center gap-1" style="font-size: 10px; border-radius: 4px; background: rgba(148, 163, 184, 0.1); color: var(--text-sub); border: 1px solid rgba(148, 163, 184, 0.2);">
                                    <i class="ri-calendar-line"></i> Last 7 Days
                                </span>
                            </div>

                            @php
                                $weeklyTotal = array_sum($trendSeries);
                                $conversionRate = $totalApplications > 0 ? round((($inProgressCount + $acceptedCount) / $totalApplications) * 100) : 0;
                            @endphp

                            @if($weeklyTotal === 0 && $totalApplications === 0)
                                <!-- Empty State -->
                                <div class="flex flex-col items-center justify-center py-10 text-center bg-gray-50 dark:bg-gray-800/20 rounded-xl border border-dashed border-gray-200 dark:border-gray-700/50 mt-2">
                                    <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-sm mb-3" style="color: var(--edb-muted);">
                                        <i class="ri-bar-chart-2-line text-xl"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 dark:text-gray-100 mb-1" style="font-size: 14px;">No applications this week</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-[200px]">Data will appear once candidates start applying.</p>
                                </div>
                            @else
                                <!-- Top Summary -->
                                <div class="flex items-baseline gap-2 mb-1">
                                    <span class="font-black leading-none tracking-tight" style="font-size: 1.85rem; color:var(--text-main)">{{ $totalApplications }}</span>
                                    <span class="font-medium" style="font-size: 13px; color:var(--text-sub)">Total Applications</span>
                                </div>
                                <div class="mb-3 flex items-center gap-2">
                                    <span class="inline-flex items-center gap-0.5 font-bold" style="font-size: 11px; color: #10b981;">
                                        <i class="ri-arrow-up-line"></i> 8% from last week
                                    </span>
                                </div>

                                <!-- Separator -->
                                <div style="border-top: 1px dashed var(--edb-border); margin: 0.85rem 0; opacity: 0.7;"></div>

                                <!-- Conversion Insight -->
                                <div class="mb-3 p-2 rounded-lg flex items-start gap-2" style="background: rgba(139, 92, 246, 0.04); border: 1px solid rgba(139, 92, 246, 0.15);">
                                    <i class="ri-lightbulb-flash-line mt-[1px]" style="color: #8b5cf6; font-size: 14px;"></i>
                                    <div style="font-size: 12px; color: var(--text-sub); line-height: 1.3;">
                                        <strong style="color:var(--text-main)">{{ $conversionRate }}%</strong> of applicants successfully moved past the initial review stage.
                                    </div>
                                </div>

                                <!-- Status Breakdown (Centered Bars) -->
                                <style>
                                    @keyframes edbSlideInBar {
                                        0% { width: 0%; }
                                        100% { width: var(--final-width); }
                                    }
                                </style>
                                <div style="display: flex; flex-direction: column;">
                                    @foreach($statusBreakdown as $bucket)
                                        @php $bucketPercent = round(($bucket['count'] / max($totalApplications, 1)) * 100); @endphp
                                        <div class="group flex items-center justify-between gap-3 p-1 rounded-lg transition-colors cursor-default" 
                                             onmouseover="this.style.backgroundColor='var(--edb-panel-soft)'" 
                                             onmouseout="this.style.backgroundColor='transparent'"
                                             title="{{ $bucket['count'] }} candidates in {{ strtolower($bucket['label']) }} stage">
                                            
                                            <!-- Label -->
                                            <span class="font-medium" style="font-size: 12px; color: var(--text-sub); width: 85px;">{{ $bucket['label'] }}</span>
                                            
                                            <!-- Progress Bar (Center) -->
                                            <div style="flex: 1; height: 6px; background: rgba(148, 163, 184, 0.15); border-radius: 99px; overflow: hidden;">
                                                <div style="--final-width: {{ $bucketPercent }}%; width: {{ $bucketPercent }}%; height: 100%; background: {{ $bucket['color'] }}; border-radius: 99px; animation: edbSlideInBar 1.2s cubic-bezier(0.22, 1, 0.36, 1) forwards;"></div>
                                            </div>

                                            <!-- Count -->
                                            <span class="font-bold text-right" style="font-size: 14px; color: var(--text-main); width: 25px;">{{ $bucket['count'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="edb-chart-box flex flex-col" style="padding: 1.25rem;">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="font-bold cursor-default" style="font-size: 15px; color:var(--text-main)">Source of Hires</h3>
                                <span class="px-2 py-0.5 font-bold uppercase tracking-wide flex items-center gap-1" style="font-size: 9px; border-radius: 4px; background: rgba(148, 163, 184, 0.1); color: var(--text-sub); border: 1px solid rgba(148, 163, 184, 0.2);">
                                    ALL TIME
                                </span>
                            </div>

                            <!-- Donut Chart -->
                            <style>
                                @keyframes edbDonutPop {
                                    0% { transform: scale(0.8) rotate(-10deg); opacity: 0; }
                                    100% { transform: scale(1) rotate(0deg); opacity: 1; }
                                }
                            </style>
                            <div class="flex justify-center mb-6">
                                <div class="relative flex items-center justify-center rounded-full shadow-sm" style="width: 140px; height: 140px; {{ $ringStyle }} animation: edbDonutPop 1.2s cubic-bezier(0.22, 1, 0.36, 1) backwards;">
                                    <!-- Inner mask to create the 'Donut' effect -->
                                    <div class="rounded-full flex flex-col items-center justify-center transition-colors duration-300" style="width: 95px; height: 95px; background: var(--bg-card); box-shadow: inset 0 2px 5px rgba(0,0,0,0.06);">
                                        <span class="font-black leading-none tracking-tight" style="font-size: 1.4rem; color:var(--text-main)">{{ $acceptanceRate }}%</span>
                                        <span class="font-bold tracking-widest uppercase mt-0.5 opacity-60" style="font-size: 9px; color:var(--text-sub)">Accept Rate</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Legend -->
                            <div class="flex flex-wrap justify-center mb-6" style="gap: 1rem 1.25rem;">
                                @foreach($sourceSegments as $segment)
                                    <div class="flex items-center cursor-default" style="gap: 6px; font-size: 11px; color: var(--text-sub); font-weight: 600;" title="{{ $segment['count'] }} candidates">
                                        <span class="shadow-sm" style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: {{ $segment['color'] }}; box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);"></span>
                                        {{ $segment['label'] }}
                                    </div>
                                @endforeach
                            </div>

                            <!-- Bottom Metrics Grid -->
                            <div class="grid grid-cols-3 gap-2 mt-auto">
                                <div class="flex flex-col items-center justify-center py-2.5 rounded-xl cursor-default transition-all duration-300 hover:-translate-y-0.5" style="background: rgba(16, 185, 129, 0.06); border: 1px solid rgba(16, 185, 129, 0.15);">
                                    <strong style="font-size: 1.15rem; color: #059669; line-height: 1.1;">{{ $acceptedCount }}</strong>
                                    <span class="font-bold opacity-80" style="font-size: 9.5px; color: #059669; text-transform: uppercase; margin-top: 3px; letter-spacing: 0.5px;">Accepted</span>
                                </div>
                                <div class="flex flex-col items-center justify-center py-2.5 rounded-xl cursor-default transition-all duration-300 hover:-translate-y-0.5" style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.12);">
                                    <strong style="font-size: 1.15rem; color: #dc2626; line-height: 1.1;">{{ $declinedCount }}</strong>
                                    <span class="font-bold opacity-80" style="font-size: 9.5px; color: #dc2626; text-transform: uppercase; margin-top: 3px; letter-spacing: 0.5px;">Declined</span>
                                </div>
                                <div class="flex flex-col items-center justify-center py-2.5 rounded-xl cursor-default transition-all duration-300 hover:-translate-y-0.5" style="background: rgba(99, 102, 241, 0.05); border: 1px solid rgba(99, 102, 241, 0.15);">
                                    <strong style="font-size: 1.15rem; color: #4f46e5; line-height: 1.1;">{{ $avgResponseDays !== null ? $avgResponseDays . 'd' : '-' }}</strong>
                                    <span class="font-bold opacity-80 text-center" style="font-size: 8px; color: #4f46e5; text-transform: uppercase; margin-top: 3px; line-height: 1.1; letter-spacing: 0.5px;">Avg.<br>Response</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --cd-accent-rgb: var(--primary-rgb, 79, 70, 229);
            --cd-accent: rgb(var(--cd-accent-rgb));
            --cd-accent-light: rgba(var(--cd-accent-rgb), 0.1);
            --bg-card: #ffffff;
            --border-card: #e5e7eb;
            --text-main: #111827;
            --text-sub: #6b7280;
            --bg-hover: #f9fafb;

            --edb-bg: #f4f7ff;
            --edb-panel: var(--bg-card);
            --edb-panel-soft: var(--bg-hover);
            --edb-border: var(--border-card);
            --edb-text: var(--text-main);
            --edb-subtext: var(--text-sub);
            --edb-muted: #64748b;
            --edb-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
            --edb-table-head: #eef3fb;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) {
            --bg-card: #202124;
            --border-card: #303134;
            --text-main: #f8f9fa;
            --text-sub: #9aa0a6;
            --bg-hover: #2b2c30;

            --edb-bg: #1f1f1f;
            --edb-panel: var(--bg-card);
            --edb-panel-soft: var(--bg-hover);
            --edb-border: rgba(255, 255, 255, 0.08);
            --edb-text: var(--text-main);
            --edb-subtext: var(--text-sub);
            --edb-muted: #9ca3af;
            --edb-shadow: none;
            --edb-table-head: rgba(255, 255, 255, 0.03);
        }

        body {
            background: #f9fafb !important;
        }

        .main-content.app-content {
            background: #f9fafb !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) body {
            background: rgba(255, 255, 255, 0.03) !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .main-content.app-content {
            background: #1f1f1f !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-header-section,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-panel,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-stat-card,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-chart-box,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-table-wrap,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-table tr {
            background: rgb(30, 32, 35) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-panel-head,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-table th,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-table td,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-job-item,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-hire-metrics div {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-table thead {
            background: rgba(255, 255, 255, 0.03) !important;
        }

        .cf-header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 1.5rem;
            position: relative;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-header-section {
            border-bottom-color: rgba(255, 255, 255, 0.08) !important;
            background: rgb(30, 32, 35) !important;
        }

        .cf-active-chip {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
            padding: 2px 10px;
            border-radius: 20px;
        }

        .cf-header-content {
            flex: 1;
        }

        .cf-header-title {
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.02em;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .cf-header-desc {
            font-size: 1rem;
            color: var(--text-sub);
            max-width: 700px;
            line-height: 1.5;
            margin: 0;
        }

        .cf-header-desc b {
            color: #6366f1;
            font-weight: 700;
        }

        .edb-action-row {
            margin-bottom: 1rem;
        }

        .edb-post-panel {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            background: var(--edb-panel-soft);
        }

        .edb-post-kicker {
            margin: 0 0 0.35rem;
            font-size: 0.75rem;
            letter-spacing: 0.35px;
            text-transform: uppercase;
            font-weight: 700;
            color: #3b82f6;
        }

        .edb-post-panel h2 {
            margin: 0;
            font-size: 1.3rem;
            line-height: 1.2;
            color: var(--edb-text);
        }

        .edb-post-copy {
            margin: 0.35rem 0 0;
            color: var(--edb-muted);
            font-size: 0.92rem;
        }

        .cf-header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
            flex-wrap: wrap;
        }

        .edb-post-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.85rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            background: #2563eb;
            border: 1px solid #2563eb;
            text-decoration: none;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .edb-post-btn:hover,
        .edb-post-btn:focus-visible {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            color: #fff;
        }

        .edb-post-btn-ghost {
            color: #1e3a8a;
            background: #ffffff;
            border-color: rgba(191, 219, 254, 0.92);
        }

        .edb-post-btn-ghost:hover,
        .edb-post-btn-ghost:focus-visible {
            color: #1e40af;
            background: #eff6ff;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.22);
        }

        .edb-post-btn-sm {
            padding: 0.55rem 0.9rem;
            font-size: 0.9rem;
        }

        .edb-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .edb-tracking-card {
            flex: 1;
            min-width: 0;
            background: #ffffff; /* pure white for contrast against page background */
            border: 1px solid #e2e8f0; /* darker, visible border on light mode */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); /* subtle shadow for separation */
            border-radius: 1rem;
            padding: 1.25rem;
            position: relative;
            z-index: 1;
            transition: all 0.2s ease;
            text-decoration: none !important;
            display: flex;
            flex-direction: column;
        }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-tracking-card { background: rgba(255,255,255,0.02) !important; border-color: rgba(255,255,255,0.05); }
        .edb-tracking-card:hover { border-color: #6366f1; background: #fff; }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-tracking-card:hover { border-color: #6366f1; background: rgba(255,255,255,0.05) !important; }

        .edb-tracking-lbl {
            font-size: 0.65rem;
            font-weight: 900;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.25rem;
        }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-tracking-lbl { color: #94a3b8; }

        .edb-tracking-val {
            font-size: 2.25rem;
            font-weight: 950;
            color: #0f172a;
            line-height: 1;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-tracking-val { color: #f8fafc; }

        .edb-tracking-insight {
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-tracking-insight { color: #94a3b8; }
        
        .edb-tracking-insight i {
            display: inline-flex;
            font-size: 0.8rem;
            margin-right: 2px;
        }

        .edb-tracking-icon-box {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            background: #fff;
            border: 1px solid #f1f5f9;
            transition: none;
        }
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-tracking-icon-box { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); }

        .edb-chip-blue .edb-tracking-icon-box, .edb-chip-blue .edb-tracking-insight { color: #6366f1; }
        .edb-chip-purple .edb-tracking-icon-box, .edb-chip-purple .edb-tracking-insight { color: #8b5cf6; }
        .edb-chip-green .edb-tracking-icon-box, .edb-chip-green .edb-tracking-insight { color: #10b981; }
        .edb-chip-amber .edb-tracking-icon-box, .edb-chip-amber .edb-tracking-insight { color: #f59e0b; }

        .edb-main-grid {
            display: grid;
            grid-template-columns: minmax(0, 2.35fr) minmax(0, 1fr);
            gap: 1rem;
            min-width: 0;
        }

        .edb-panel {
            background: var(--edb-panel);
            border: 1px solid var(--edb-border);
            border-radius: 1rem;
            box-shadow: var(--edb-shadow);
            padding: 1rem 1rem 1.1rem;
            min-width: 0;
        }

        .edb-panel-large {
            min-height: 23rem;
        }

        .edb-panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
            margin-bottom: 0.85rem;
        }

        .edb-panel-head h2 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.1px;
            color: var(--edb-text);
        }

        .edb-link {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.96rem;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-link {
            color: #9cc6ff;
        }

        .edb-link:hover,
        .edb-link:focus-visible {
            color: #1d4ed8;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-link:hover,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-link:focus-visible {
            color: #bfdbfe;
        }

        .edb-table-wrap {
            border-radius: 0.85rem;
            border: 1px solid rgba(148, 163, 184, 0.22);
            overflow: hidden;
        }

        .edb-table {
            width: 100%;
            border-collapse: collapse;
        }

        .edb-table thead {
            background: var(--edb-table-head);
        }

        .edb-table th,
        .edb-table td {
            padding: 0.82rem 0.95rem;
            font-size: 0.92rem;
            text-align: left;
            color: var(--edb-text);
            border-bottom: 1px solid var(--edb-border);
        }

        .edb-table th {
            font-size: 0.84rem;
            letter-spacing: 0.3px;
            color: var(--edb-subtext);
            text-transform: uppercase;
        }

        .edb-table tr:last-child td {
            border-bottom: none;
        }

        .edb-user-cell {
            display: flex;
            align-items: center;
            gap: 0.62rem;
            font-weight: 600;
        }

        .edb-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .edb-status {
            padding: 0.34rem 0.62rem;
            border-radius: 0.55rem;
            font-size: 0.79rem;
            font-weight: 700;
            display: inline-flex;
            line-height: 1;
        }

        .edb-action-btn {
            padding: 0.44rem 0.74rem;
            border-radius: 0.58rem;
            border: 1px solid #bfdbfe;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-action-btn {
            border: 1px solid rgba(147, 197, 253, 0.35);
            background: rgba(59, 130, 246, 0.12);
            color: #93c5fd;
        }

        .edb-action-btn:hover,
        .edb-action-btn:focus-visible {
            color: #1e40af;
            background: #dbeafe;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-action-btn:hover,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-action-btn:focus-visible {
            color: #dbeafe;
            background: rgba(59, 130, 246, 0.2);
        }

        .edb-empty {
            margin-top: 0.8rem;
            min-height: 12rem;
            border: 1px dashed rgba(148, 163, 184, 0.35);
            border-radius: 0.85rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            color: var(--edb-muted);
            background: var(--edb-panel-soft);
            text-align: center;
        }

        .edb-empty-icon {
            width: 3.8rem;
            height: 3.8rem;
            border-radius: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #93c5fd;
            font-size: 2rem;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-empty-icon {
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(147, 197, 253, 0.3);
        }

        .edb-side-col {
            display: grid;
            gap: 1rem;
            align-content: start;
            min-width: 0;
        }

        #wt-recent-jobs {
            display: flex;
            flex-direction: column;
        }

        .edb-custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .edb-custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .edb-custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
            border-radius: 10px;
        }
        .edb-custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.5);
        }

        .edb-charts-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            min-width: 0;
        }

        .edb-chart-box {
            border-radius: 0.85rem;
            padding: 0.7rem;
            border: 1px solid var(--edb-border);
            background: var(--edb-panel-soft);
            min-width: 0;
        }

        .edb-chart-title {
            margin: 0 0 0.45rem;
            font-size: 0.83rem;
            color: var(--edb-subtext);
            font-weight: 600;
        }

        .edb-line-chart {
            width: 100%;
            height: 5.7rem;
        }

        .edb-chart-caption {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: var(--edb-muted);
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .edb-donut {
            width: 5.9rem;
            height: 5.9rem;
            border-radius: 999px;
            margin: 0.2rem auto 0.35rem;
            display: grid;
            place-items: center;
            position: relative;
        }

        .edb-donut::after {
            content: '';
            position: absolute;
            width: 3.1rem;
            height: 3.1rem;
            border-radius: 999px;
            background: var(--edb-panel);
            border: 1px solid var(--edb-border);
        }

        .edb-donut span {
            position: relative;
            z-index: 1;
            font-size: 0.78rem;
            font-weight: 800;
            color: var(--edb-text);
        }

        .edb-legend {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            flex-wrap: wrap;
            font-size: 0.72rem;
            color: var(--edb-muted);
        }

        .edb-dot {
            width: 0.52rem;
            height: 0.52rem;
            border-radius: 999px;
            display: inline-block;
            margin-right: 0.2rem;
        }

        .edb-bar-list {
            margin-top: 0.5rem;
            display: grid;
            gap: 0.38rem;
        }

        .edb-bar-row {
            display: grid;
            grid-template-columns: 5.3rem 1fr auto;
            align-items: center;
            gap: 0.45rem;
        }

        .edb-bar-label,
        .edb-bar-count {
            font-size: 0.7rem;
            color: var(--edb-muted);
            white-space: nowrap;
        }

        .edb-bar-track {
            height: 0.38rem;
            border-radius: 999px;
            background: #dbe4f2;
            overflow: hidden;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-bar-track {
            background: rgba(148, 163, 184, 0.22);
        }

        .edb-bar-fill {
            display: block;
            height: 100%;
            border-radius: inherit;
        }

        .edb-hire-metrics {
            margin-top: 0.56rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.35rem;
        }

        .edb-hire-metrics div {
            border-radius: 0.6rem;
            border: 1px solid var(--edb-border);
            background: var(--edb-panel);
            text-align: center;
            padding: 0.38rem;
        }

        .edb-hire-metrics strong {
            display: block;
            color: var(--edb-text);
            font-size: 0.78rem;
            line-height: 1.1;
        }

        .edb-hire-metrics span {
            color: var(--edb-muted);
            font-size: 0.67rem;
            line-height: 1.1;
        }

        .edb-jobs-list {
            display: grid;
            gap: 0.66rem;
            overflow-y: auto;
            padding-right: 0.2rem;
            max-height: calc(19.5rem - 4.25rem);
        }

        .edb-jobs-list::-webkit-scrollbar {
            width: 6px;
        }

        .edb-jobs-list::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 999px;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-jobs-list::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.55);
        }

        .edb-job-item {
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            gap: 0.62rem;
            align-items: center;
            padding-bottom: 0.62rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.16);
        }

        .edb-job-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .edb-job-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 0.6rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #eaf2ff;
            border: 1px solid #bfdbfe;
            color: #2563eb;
            font-size: 1rem;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-job-icon {
            background: rgba(96, 165, 250, 0.16);
            border: 1px solid rgba(147, 197, 253, 0.28);
            color: #93c5fd;
        }

        .edb-job-meta a {
            color: var(--edb-text);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .edb-job-meta a:hover,
        .edb-job-meta a:focus-visible {
            color: #2563eb;
        }

        .edb-job-meta p {
            margin: 0.1rem 0 0;
            color: var(--edb-muted);
            font-size: 0.82rem;
        }

        .edb-pill {
            border-radius: 0.5rem;
            padding: 0.33rem 0.56rem;
            font-size: 0.76rem;
            font-weight: 700;
            line-height: 1;
        }

        .edb-pill-active {
            background: rgba(74, 222, 128, 0.18);
            color: #86efac;
            border: 1px solid rgba(134, 239, 172, 0.28);
        }

        .edb-pill-closed {
            background: rgba(148, 163, 184, 0.2);
            color: #cbd5e1;
            border: 1px solid rgba(148, 163, 184, 0.32);
        }

        .edb-no-jobs {
            margin: 0;
            font-size: 0.9rem;
            color: var(--edb-muted);
            text-align: center;
            padding: 0.6rem 0;
        }

        @media (max-width: 1279px) {
            .edb-main-grid {
                grid-template-columns: 1fr;
            }

            .edb-side-col {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

        }

        @media (max-width: 992px) {
            .edb-stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .edb-charts-grid {
                grid-template-columns: 1fr;
            }

            .edb-panel-head h2 {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 768px) {
            .cf-header-section {
                flex-direction: column;
                align-items: flex-start;
                padding-top: 2.5rem;
            }

            .edb-post-panel {
                flex-direction: column;
                align-items: flex-start;
            }

            .cf-header-actions {
                width: 100%;
                margin-left: 0;
            }

            .edb-post-btn {
                width: 100%;
                justify-content: center;
            }

            .edb-stat-grid,
            .edb-side-col {
                grid-template-columns: 1fr;
            }

            #wt-recent-jobs,
            .edb-jobs-list {
                height: auto;
                max-height: none;
            }

            .edb-table-wrap {
                overflow-x: auto;
            }

            .edb-table {
                min-width: 560px;
            }

            .edb-stat-card {
                padding: 0.82rem;
            }

            .edb-stat-icon {
                width: 2.7rem;
                height: 2.7rem;
                font-size: 1.25rem;
            }

            .edb-stat-value {
                font-size: 1.6rem;
            }

            .edb-bar-row {
                grid-template-columns: 4.3rem 1fr auto;
            }
        }

        @media (max-width: 576px) {
            .cf-header-title {
                font-size: 1.35rem;
            }

            .cf-header-desc {
                font-size: 0.92rem;
            }

            .edb-panel {
                padding: 0.82rem;
            }

            .edb-panel-head h2 {
                font-size: 1rem;
            }

            .edb-link {
                font-size: 0.84rem;
            }

            .edb-table th,
            .edb-table td {
                padding: 0.62rem 0.68rem;
                font-size: 0.82rem;
            }

            .edb-table {
                min-width: 520px;
            }

            .edb-user-cell {
                gap: 0.46rem;
            }

            .edb-avatar {
                width: 1.72rem;
                height: 1.72rem;
                font-size: 0.72rem;
            }

            .edb-hire-metrics {
                grid-template-columns: 1fr;
            }

            .edb-job-item {
                grid-template-columns: auto minmax(0, 1fr);
            }

            .edb-pill {
                justify-self: start;
            }
        }

        @media (max-width: 640px) {
            .edb-table-wrap {
                overflow: visible;
                border: 0;
                background: transparent;
            }

            .edb-table,
            .edb-table tbody,
            .edb-table tr,
            .edb-table td {
                display: block;
                width: 100%;
            }

            .edb-table {
                min-width: 0;
            }

            .edb-table thead {
                display: none;
            }

            .edb-table tr {
                border: 1px solid var(--edb-border);
                border-radius: 0.78rem;
                padding: 0.7rem 0.78rem;
                margin-bottom: 0.62rem;
                background: var(--edb-panel);
                box-shadow: var(--edb-shadow);
            }

            .edb-table tr:last-child {
                margin-bottom: 0;
            }

            .edb-table td {
                border: 0;
                padding: 0.34rem 0;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 0.65rem;
                text-align: left;
            }

            .edb-table td::before {
                content: attr(data-label);
                color: var(--edb-muted);
                font-size: 0.72rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.26px;
                flex: 0 0 auto;
            }

            .edb-table td[data-label="Candidate Name"] {
                display: block;
                padding-top: 0;
            }

            .edb-table td[data-label="Candidate Name"]::before {
                display: block;
                margin-bottom: 0.34rem;
            }

            .edb-table td[data-label="Action"] {
                padding-bottom: 0;
            }

            .edb-table td[data-label="Action"] .edb-action-btn {
                margin-inline-start: auto;
            }
        }
    </style>

    @include('candidates.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'wt-hero', 'title' => 'Welcome!', 'icon' => 'ri-building-2-line', 'body' => 'This is your Employer Dashboard, your command center for jobs and applications.', 'position' => 'bottom'],
            ['target' => 'wt-overview', 'title' => 'Overview Stats', 'icon' => 'ri-bar-chart-fill', 'body' => 'Track job, application, and interview activity in one glance.', 'position' => 'bottom'],
            ['target' => 'wt-recent-apps', 'title' => 'Recent Applications', 'icon' => 'ri-history-fill', 'body' => 'Review the latest applicants and jump to full application management.', 'position' => 'top'],
            ['target' => 'wt-quick-stats', 'title' => 'Quick Stats', 'icon' => 'ri-pie-chart-fill', 'body' => 'Use visual summaries to understand trend and hiring conversion quickly.', 'position' => 'left'],
            ['target' => 'wt-recent-jobs', 'title' => 'Recent Jobs', 'icon' => 'ri-briefcase-fill', 'body' => 'See your latest job posts and current status instantly.', 'position' => 'left'],
        ],
        'wtKey' => 'employer_dashboard',
    ])

</x-app-layout>
