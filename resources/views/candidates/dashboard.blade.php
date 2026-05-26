<x-app-layout>

    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="url_1">{"link": "/jobs", "text": "Job Listing"}</x-slot>
    <x-slot name="active"> Dashboard</x-slot>

    {{-- ════════════════════════════════════════════ --}}
    {{--  SCOPED STYLES                              --}}
    {{-- ════════════════════════════════════════════ --}}
    {{-- Premium Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ── Tokens ── */
        :root {
            --cd-r: 12px;
            --cd-s: 0 1px 3px rgba(0,0,0,0.05);
            --cd-sh: 0 6px 16px rgba(0,0,0,0.08);
            --cd-p: 20px;
            --cd-t: 0.2s ease;
            --cd-font: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        /* ── Global Font ── */
        .cd-hero, .cd-section, .cd-pipe, .cd-job, .cd-app-row, .cd-stat {
            font-family: var(--cd-font);
        }

        /* ── Section wrapper ── */
        .cd-section {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: var(--cd-r);
            padding: var(--cd-p);
            box-shadow: var(--cd-s);
            transition: box-shadow var(--cd-t);
        }
        .cd-section:hover { box-shadow: var(--cd-sh); }

        .cd-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 0.65rem;
            border-bottom: 1px solid #f3f4f6;
        }
        .cd-section-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            font-weight: 700;
            color: #1f2937;
        }
        .cd-section-label i {
            font-size: 1.1rem;
            color: #6366f1;
        }
        .cd-section-link {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6366f1;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 2px;
        }
        .cd-section-link:hover { color: #4338ca; text-decoration: underline; }

        /* ── Hero ── */
        .cd-hero {
            background: linear-gradient(135deg, #312e81 0%, #4338ca 50%, #6366f1 100%);
            border-radius: var(--cd-r);
            padding: 1.35rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .cd-hero-title { font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 0.1rem; }
        .cd-hero-sub { font-size: 0.8rem; color: rgba(255,255,255,0.65); }
        .cd-hero-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .cd-hero-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0.45rem 0.9rem; border-radius: var(--cd-r);
            font-size: 0.78rem; font-weight: 600; text-decoration: none;
            transition: var(--cd-t); cursor: pointer; border: none; white-space: nowrap;
        }
        .cd-hero-btn-primary { background: #fff; color: #4338ca; }
        .cd-hero-btn-primary:hover { background: #eef2ff; color: #4338ca; box-shadow: 0 2px 8px rgba(0,0,0,0.12); }
        .cd-hero-btn-ghost { background: rgba(255,255,255,0.15); color: #fff; backdrop-filter: blur(4px); }
        .cd-hero-btn-ghost:hover { background: rgba(255,255,255,0.25); color: #fff; }

        /* ── Dark mode overrides for buttons ── */
        [data-theme-mode="dark"] .cd-hero-btn-primary, .dark .cd-hero-btn-primary {
            background: rgba(255,255,255,0.95);
            color: #312e81;
        }
        [data-theme-mode="dark"] .cd-hero-btn-primary:hover, .dark .cd-hero-btn-primary:hover {
            background: #fff;
            box-shadow: 0 0 15px rgba(255,255,255,0.2);
        }

        /* ── Pipeline ── */
        .cd-pipeline { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.85rem; }
        @media (max-width: 768px) { .cd-pipeline { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px) { .cd-pipeline { grid-template-columns: 1fr; } }
        .cd-pipe {
            text-align: center;
            padding: 1.25rem 0.75rem 1rem;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            background: var(--pipe-bg, #fff);
        }
        .cd-pipe::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 14px 14px 0 0;
            background: var(--pipe-accent, #4f46e5);
            opacity: 0.6;
            transition: opacity 0.25s ease, height 0.25s ease;
        }
        .cd-pipe:hover::before { opacity: 1; height: 4px; }
        .cd-pipe:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.1); }
        .cd-pipe-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 0.65rem;
            transition: transform 0.25s ease;
        }
        .cd-pipe:hover .cd-pipe-icon { transform: scale(1.1); }
        .cd-pipe-num {
            font-family: var(--cd-font);
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.5px;
        }
        .cd-pipe-lbl { font-size: 0.8125rem; font-weight: 600; color: #374151; margin-top: 0.25rem; letter-spacing: 0.2px; }
        .cd-pipe-micro { font-size: 0.75rem; color: #9ca3af; margin-top: 0.15rem; }
        .cd-pipe-dots { display: flex; gap: 4px; margin-top: 0.6rem; justify-content: center; }
        .cd-pipe-dots span { height: 4px; width: 14px; border-radius: 4px; transition: width 0.3s ease; background: var(--dot-bg, rgba(0,0,0,0.06)); }
        .cd-pipe:hover .cd-pipe-dots span:first-child { width: 20px; }

        /* ── KPI Tags ── */
        .cd-job-tag { font-size: 0.75rem; font-weight: 600; padding: 2px 7px; border-radius: 6px; background: var(--tag-bg, #eef2ff); color: var(--tag-color, #4f46e5); }
        [data-theme-mode="dark"] .cd-job-tag, .dark .cd-job-tag { background: rgba(255,255,255,0.1) !important; color: #e5e7eb !important; }

        /* ── Job Cards ── */
        .cd-jobs-scroll { display: flex; gap: 0.75rem; overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none; padding-bottom: 4px; }
        .cd-jobs-scroll::-webkit-scrollbar { display: none; }
        .cd-job {
            min-width: 220px; max-width: 240px; flex-shrink: 0;
            border: 1px solid #e5e7eb; border-radius: var(--cd-r); padding: 1rem;
            background: #fff; transition: transform var(--cd-t), box-shadow var(--cd-t);
        }
        .cd-job:hover { transform: translateY(-4px); box-shadow: var(--cd-sh); }
        .cd-job-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 0.6rem; }
        .cd-job-logo { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; color: #fff; flex-shrink: 0; }
        .cd-job-save { width: 28px; height: 28px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; display: flex; align-items: center; justify-content: center; color: #9ca3af; cursor: pointer; transition: var(--cd-t); font-size: 0.85rem; }
        .cd-job-save:hover { color: #ef4444; border-color: #fecaca; background: #fef2f2; }
        .cd-job-title { font-size: 0.85rem; font-weight: 600; color: #1f2937; margin-bottom: 0.15rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3; }
        .cd-job-company { font-size: 0.75rem; color: #6b7280; margin-bottom: 0.4rem; }
        .cd-job-meta { font-size: 0.75rem; color: #6b7280; margin-bottom: 0.55rem; }
        .cd-job-meta span { display: block; margin-bottom: 1px; }
        .cd-job-tags { display: flex; gap: 4px; flex-wrap: wrap; margin-bottom: 0.6rem; }
        .cd-apply-btn {
            display: flex; align-items: center; justify-content: center; gap: 4px;
            width: 100%; padding: 0.4rem 1rem; border-radius: var(--cd-r); border: none;
            background: #4f46e5; color: #fff; font-weight: 600; font-size: 0.78rem;
            cursor: pointer; text-decoration: none; transition: var(--cd-t);
        }
        .cd-apply-btn:hover { background: #4338ca; box-shadow: 0 2px 8px rgba(79,70,229,0.3); color: #fff; }
        .cd-scroll-btn { width: 30px; height: 30px; border-radius: 50%; border: 1px solid #e5e7eb; background: #fff; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: var(--cd-t); font-size: 0.8rem; color: #374151; }
        .cd-scroll-btn:hover { background: #f9fafb; box-shadow: var(--cd-s); }

        /* ── Skill & Profile items ── */
        .cd-skill-item { display: flex; align-items: center; gap: 0.65rem; padding: 0.5rem 0; }
        .cd-skill-item + .cd-skill-item { border-top: 1px solid #f3f4f6; }
        .cd-skill-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0; }
        .cd-skill-bar { height: 4px; border-radius: 2px; background: #f3f4f6; margin-top: 3px; }
        [data-theme-mode="dark"] .cd-skill-bar, .dark .cd-skill-bar { background: rgba(255,255,255,0.1); }
        .cd-skill-fill { height: 100%; border-radius: 2px; transition: width 0.6s ease; }
        .cd-improve-btn {
            display: block; width: 100%; margin-top: 0.65rem; padding: 0.4rem 1rem;
            border-radius: var(--cd-r); border: 1px solid #e5e7eb; background: #fff;
            font-weight: 600; font-size: 0.78rem; color: #4f46e5; text-align: center; text-decoration: none; transition: var(--cd-t);
        }
        .cd-improve-btn:hover { background: #eef2ff; border-color: #c7d2fe; color: #4338ca; }
        [data-theme-mode="dark"] .cd-improve-btn, .dark .cd-improve-btn { background: rgba(255,255,255,0.05); color: #fff; border-color: rgba(255,255,255,0.1); }
        [data-theme-mode="dark"] .cd-improve-btn:hover, .dark .cd-improve-btn:hover { background: rgba(255,255,255,0.1); }

        /* ── Application row ── */
        .cd-app-row {
            display: flex; align-items: center; gap: 0.65rem; padding: 0.55rem 0.25rem;
            border-radius: 8px; transition: background 0.15s;
        }
        .cd-app-row:hover { background: #f9fafb; }
        .cd-app-row + .cd-app-row { border-top: 1px solid #f3f4f6; }
        [data-theme-mode="dark"] .cd-app-row + .cd-app-row, .dark .cd-app-row + .cd-app-row { border-color: rgba(255,255,255,0.06); }
        .cd-app-logo { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; color: #fff; flex-shrink: 0; }
        .cd-status-pill { font-size: 0.75rem; font-weight: 600; padding: 2px 8px; border-radius: 20px; white-space: nowrap; }
        [data-theme-mode="dark"] .cd-status-pill, .dark .cd-status-pill { background: rgba(255,255,255,0.1) !important; color: #d1d5db !important; border: 1px solid rgba(255,255,255,0.05); }

        /* ── Stat cells ── */
        .cd-stat { text-align: center; padding: 0.65rem; border-radius: 10px; background: var(--stat-bg, #f3f4f6); }
        .cd-stat-num { font-size: 1.15rem; font-weight: 800; line-height: 1; }
        .cd-stat-lbl { font-size: 0.75rem; font-weight: 500; color: #6b7280; margin-top: 0.25rem; }

        /* ── Dark mode ── */
        [data-theme-mode="dark"] .cd-section, .dark .cd-section { background: var(--bodybg, #1a1c2e); border-color: rgba(255,255,255,0.08); }
        [data-theme-mode="dark"] .cd-pipe, .dark .cd-pipe { border-color: rgba(255,255,255,0.08); }
        [data-theme-mode="dark"] .cd-job, .dark .cd-job { background: var(--bodybg, #1a1c2e); border-color: rgba(255,255,255,0.08); }
        [data-theme-mode="dark"] .cd-section-label, .dark .cd-section-label { color: #e5e7eb; }
        [data-theme-mode="dark"] .cd-pipe-lbl, .dark .cd-pipe-lbl { color: #d1d5db; }
        [data-theme-mode="dark"] .cd-job-title, .dark .cd-job-title { color: #e5e7eb; }
        [data-theme-mode="dark"] .cd-section-head, .dark .cd-section-head { border-color: rgba(255,255,255,0.06); }
        [data-theme-mode="dark"] .cd-scroll-btn, [data-theme-mode="dark"] .cd-job-save, .dark .cd-scroll-btn, .dark .cd-job-save { background: var(--bodybg, #1a1c2e); border-color: rgba(255,255,255,0.1); color: #d1d5db; }
        [data-theme-mode="dark"] .cd-app-row:hover, .dark .cd-app-row:hover { background: rgba(255,255,255,0.04); }

        /* Dark mode KPI cards and stats */
        [data-theme-mode="dark"] .cd-pipe, .dark .cd-pipe { background: rgba(255,255,255,0.04) !important; border-color: rgba(255,255,255,0.06); }
        [data-theme-mode="dark"] .cd-stat, .dark .cd-stat { background: rgba(255,255,255,0.04) !important; border-color: rgba(255,255,255,0.06); }
        [data-theme-mode="dark"] .cd-stat-lbl, .dark .cd-stat-lbl { color: #9ca3af; }
        [data-theme-mode="dark"] .cd-pipe-dots span, .dark .cd-pipe-dots span { background: rgba(255,255,255,0.1); }
    </style>

    {{-- ════════════════════════════════════════════ --}}
    {{--  SHARED DATA                                --}}
    {{-- ════════════════════════════════════════════ --}}
    @php
        $logoBgs = ['#4f46e5','#0d9488','#dc2626','#7c3aed','#ea580c','#0284c7'];

        $completionItems = [
            'photo' => !empty($user->profile_photo_path),
            'title' => !empty($profile->job_title ?? $profile->title),
            'experience' => !empty($profile->years_experience),
            'skills' => !empty($profile->expertise_categories),
            'salary' => !empty($profile->expected_salary_min),
        ];
        $completedCount = count(array_filter($completionItems));
        $totalItems = count($completionItems);
        $percentage = round(($completedCount / $totalItems) * 100);
        $pColor = $percentage >= 80 ? '#16a34a' : ($percentage >= 50 ? '#ca8a04' : '#dc2626');

        // Completion logic
        $missingItems = array_filter($completionItems, fn($v) => !$v);
        $firstMissingKey = array_key_first($missingItems);
        $profileLink = ($firstMissingKey === 'photo') ? route('profile.show') : route('applicant.profile.edit');
        $viewProfileLink = route('applicant.profile.show');

        $statusStyles = [
            'applied' => ['bg'=>'#eef2ff','text'=>'#4f46e5'],
            'submitted' => ['bg'=>'#eef2ff','text'=>'#4f46e5'],
            'reviewed' => ['bg'=>'#f0fdf4','text'=>'#16a34a'],
            'shortlisted' => ['bg'=>'#fefce8','text'=>'#ca8a04'],
            'under_review' => ['bg'=>'#fefce8','text'=>'#ca8a04'],
            'interview_scheduled' => ['bg'=>'#f5f3ff','text'=>'#7c3aed'],
            'interviewed' => ['bg'=>'#f5f3ff','text'=>'#7c3aed'],
            'offered' => ['bg'=>'#fdf4ff','text'=>'#a21caf'],
            'hired' => ['bg'=>'#f0fdf4','text'=>'#16a34a'],
            'accepted' => ['bg'=>'#f0fdf4','text'=>'#16a34a'],
            'rejected' => ['bg'=>'#fef2f2','text'=>'#dc2626'],
            'declined' => ['bg'=>'#fef2f2','text'=>'#dc2626'],
            'withdrawn' => ['bg'=>'#f9fafb','text'=>'#6b7280'],
        ];
    @endphp

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══════════════ SECTION 1: HERO ═══════════════ --}}
        <div class="col-span-12" id="wt-hero">
            <div class="cd-hero">
                <div style="flex:1;min-width:200px">
                    <h1 class="cd-hero-title">Welcome back, {{ $profile->display_name ?? $user->name }}!</h1>
                    <p class="cd-hero-sub">Here's what's happening with your job search</p>
                </div>
                <div class="cd-hero-actions">
                    <a href="{{ route('jobs') }}" class="cd-hero-btn cd-hero-btn-primary">
                        <i class="ri-search-line"></i> Search Jobs
                    </a>
                    <a href="{{ route('candidate.applications.index') }}" class="cd-hero-btn cd-hero-btn-ghost">
                        <i class="ri-file-list-3-line"></i> Applications
                    </a>
                    @if($percentage < 100)
                        <a href="{{ $profileLink }}" class="cd-hero-btn cd-hero-btn-ghost">
                            <i class="ri-user-settings-line"></i> Complete Profile
                        </a>
                    @endif
                    <button type="button" onclick="startWalkthrough()" class="cd-hero-btn cd-tour-btn" title="Take a guided tour of your dashboard">
                        <i class="ri-rocket-2-fill"></i> Take a Tour
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══════════════ SECTION 2: APPLICATION TRACKING ═══════════════ --}}
        <div class="col-span-12" id="wt-pipeline">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-bar-chart-fill"></i> Application Tracking</span>
                    <a href="{{ route('candidate.applications.index') }}" class="cd-section-link">View All <i class="ri-arrow-right-s-line"></i></a>
                </div>
                <div class="cd-pipeline">
                    @php
                        $pipes = [
                            ['label'=>'Applied','count'=>$appliedCount??0,'icon'=>'ri-file-list-3-line','color'=>'#4f46e5','bg'=>'#eef2ff','micro'=>'submitted'],
                            ['label'=>'Interviewing','count'=>$interviewingCount??0,'icon'=>'ri-team-line','color'=>'#ca8a04','bg'=>'#fefce8','micro'=>'in progress'],
                            ['label'=>'Offer Received','count'=>$offeredCount??0,'icon'=>'ri-mail-check-line','color'=>'#7c3aed','bg'=>'#f5f3ff','micro'=>'pending'],
                            ['label'=>'Hired','count'=>$hiredCount??0,'icon'=>'ri-trophy-line','color'=>'#16a34a','bg'=>'#f0fdf4','micro'=>'secured'],
                        ];
                    @endphp
                    @foreach($pipes as $idx => $p)
                        <div class="cd-pipe" style="--pipe-bg:{{ $p['bg'] }};--pipe-accent:{{ $p['color'] }}">
                            <div class="cd-pipe-icon" style="background:{{ $p['color'] }}20;color:{{ $p['color'] }}">
                                <i class="{{ $p['icon'] }}"></i>
                            </div>
                            <div class="cd-pipe-num" style="color:{{ $p['color'] }}">{{ $p['count'] }}</div>
                            <div class="cd-pipe-lbl">{{ $p['label'] }}</div>
                            <div class="cd-pipe-micro">{{ $p['count'] }} {{ $p['micro'] }}</div>
                            <div class="cd-pipe-dots">
                                @for($i = 0; $i < (4-$idx); $i++)<span style="background:{{ $p['color'] }};--dot-bg:{{ $p['color'] }}"></span>@endfor
                                @for($i = (4-$idx); $i < 4; $i++)<span style="--dot-bg:rgba(0,0,0,0.06)"></span>@endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ═══════════════ SECTION 3: RECOMMENDED JOBS ═══════════════ --}}
        <div class="col-span-12 xl:col-span-8" id="wt-recommended">
            <div class="cd-section h-full">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-briefcase-fill"></i> Recommended Jobs</span>
                    <div class="flex items-center gap-2">
                        <button type="button" class="cd-scroll-btn" onclick="document.getElementById('cdScroll').scrollBy({left:-250,behavior:'smooth'})"><i class="ri-arrow-left-s-line"></i></button>
                        <button type="button" class="cd-scroll-btn" onclick="document.getElementById('cdScroll').scrollBy({left:250,behavior:'smooth'})"><i class="ri-arrow-right-s-line"></i></button>
                    </div>
                </div>

                @if($recommendedJobs->isEmpty())
                    <div class="text-center py-6">
                        <i class="ri-briefcase-line text-gray-300 text-3xl mb-2 block"></i>
                        <p class="text-gray-400 text-sm mb-3">No recommended jobs right now</p>
                        <a href="{{ route('jobs') }}" class="cd-apply-btn" style="width:auto;display:inline-flex;padding:0.4rem 1.2rem">Find Jobs</a>
                    </div>
                @else
                    <div class="cd-jobs-scroll" id="cdScroll">
                        @foreach($recommendedJobs as $idx => $job)
                            @php $lbg = $logoBgs[$idx % count($logoBgs)]; @endphp
                            <div class="cd-job">
                                <div class="cd-job-top">
                                    <div class="cd-job-logo" style="background:{{ $lbg }}">
                                        @if($job->company?->logo_url)
                                            <img src="{{ $job->company->logo_url }}" alt="" style="width:28px;height:28px;border-radius:6px;object-fit:cover">
                                        @else
                                            {{ strtoupper(substr($job->company?->name ?? 'C', 0, 2)) }}
                                        @endif
                                    </div>
                                    <button type="button" class="cd-job-save" title="Save"><i class="ri-bookmark-line"></i></button>
                                </div>
                                <div class="cd-job-title">{{ $job->title }}</div>
                                <div class="cd-job-company">{{ $job->company?->name ?? 'Company' }}</div>
                                <div class="cd-job-meta">
                                    <span><i class="ri-map-pin-2-line me-1"></i>{{ $job->location ?? 'Remote' }}</span>
                                    @if($job->salary_min || $job->salary_max)
                                        <span><i class="ri-money-dollar-circle-line me-1"></i>${{ number_format($job->salary_min ?? 0) }} – ${{ number_format($job->salary_max ?? 0) }}</span>
                                    @endif
                                </div>
                                 <div class="cd-job-tags">
                                    @if($job->employment_type)<span class="cd-job-tag" style="--tag-bg:#eef2ff;--tag-color:#4f46e5">{{ Str::headline($job->employment_type) }}</span>@endif
                                    @if($job->remote_type)<span class="cd-job-tag" style="--tag-bg:#f0fdf4;--tag-color:#16a34a">{{ Str::headline($job->remote_type) }}</span>@endif
                                </div>
                                <a href="{{ route('jobs.show', $job->slug) }}" class="cd-apply-btn">Quick Apply <i class="ri-arrow-right-line"></i></a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══════════════ SECTION 4: INSIGHTS SIDEBAR ═══════════════ --}}
        <div class="col-span-12 xl:col-span-4 flex flex-col gap-4" id="wt-sidebar">

            {{-- 4a. Skill Insights --}}
            <div class="cd-section" id="wt-skills">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-lightbulb-flash-fill"></i> Skill Insights</span>
                </div>
                @php
                    $trendingSkills = [
                        ['name'=>'Data Analysis','growth'=>'+20%','color'=>'#4f46e5','pct'=>78,'icon'=>'ri-bar-chart-2-line','dir'=>'↑'],
                        ['name'=>'Python','growth'=>'+15%','color'=>'#7c3aed','pct'=>65,'icon'=>'ri-code-s-slash-line','dir'=>'↑'],
                        ['name'=>'Cloud Computing','growth'=>'+10%','color'=>'#0d9488','pct'=>55,'icon'=>'ri-cloud-line','dir'=>'↑'],
                    ];
                    $userSkills = [];
                    if (!empty($profile->expertise_categories)) {
                        $cats = json_decode($profile->expertise_categories, true) ?? [];
                        foreach (array_slice($cats, 0, 3) as $i => $cat) {
                            $ref = $trendingSkills[$i % count($trendingSkills)];
                            $userSkills[] = array_merge($ref, ['name' => $cat]);
                        }
                    }
                    $displaySkills = !empty($userSkills) ? $userSkills : $trendingSkills;
                @endphp
                @foreach($displaySkills as $skill)
                    <div class="cd-skill-item">
                        <div class="cd-skill-icon" style="background:{{ $skill['color'] }}12;color:{{ $skill['color'] }}"><i class="{{ $skill['icon'] }}"></i></div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold dark:text-white truncate">{{ $skill['name'] }}</span>
                                <span class="text-xs font-semibold" style="color:{{ $skill['color'] }}">{{ $skill['dir'] }} {{ $skill['growth'] }}</span>
                            </div>
                            <div class="cd-skill-bar"><div class="cd-skill-fill" style="width:{{ $skill['pct'] }}%;background:{{ $skill['color'] }}"></div></div>
                        </div>
                    </div>
                @endforeach
                <a href="{{ route('jobs') }}?q=skills" class="cd-improve-btn"><i class="ri-lightbulb-line me-1"></i> Improve Skills</a>
            </div>

            {{-- 4b. Profile Completion --}}
            <div class="cd-section" id="wt-profile">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-user-3-fill"></i> Profile Completion</span>
                    <span class="text-xs font-bold" style="color:{{ $pColor }}">{{ $percentage }}%</span>
                </div>
                <div class="cd-skill-item" style="padding-top:0">
                    <div class="cd-skill-icon" style="background:{{ $pColor }}12;color:{{ $pColor }}"><i class="ri-user-3-line"></i></div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold dark:text-white">Profile</span>
                            <span class="text-xs font-bold" style="color:{{ $pColor }}">{{ $completedCount }}/{{ $totalItems }}</span>
                        </div>
                        <div class="cd-skill-bar"><div class="cd-skill-fill" style="width:{{ $percentage }}%;background:{{ $pColor }}"></div></div>
                    </div>
                </div>
                @if($percentage < 100)
                    <a href="{{ $profileLink }}" class="cd-improve-btn" style="margin-top:0.5rem"><i class="ri-user-settings-line me-1"></i> Complete Profile</a>
                @else
                    <a href="{{ $viewProfileLink }}" class="cd-improve-btn" style="margin-top:0.5rem"><i class="ri-user-3-line me-1"></i> View Profile</a>
                @endif
            </div>
        </div>

        {{-- ═══════════════ SECTION 5: RECENT APPLICATIONS ═══════════════ --}}
        <div class="col-span-12 xl:col-span-8" id="wt-applications">
            <div class="cd-section h-full">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-history-fill"></i> Recent Applications</span>
                    <a href="{{ route('candidate.applications.index') }}" class="cd-section-link">View All <i class="ri-arrow-right-s-line"></i></a>
                </div>

                @if($applications->isEmpty())
                    <div class="text-center py-5">
                        <i class="ri-file-list-3-line text-gray-300 text-3xl mb-2 block"></i>
                        <p class="text-gray-400 text-sm mb-3">You haven't applied to any jobs yet.</p>
                        <a href="{{ route('jobs') }}" class="cd-apply-btn" style="width:auto;display:inline-flex;padding:0.4rem 1.2rem"><i class="ri-search-line me-1"></i> Find Jobs</a>
                    </div>
                @else
                    @foreach($applications->take(5) as $app)
                        @php $st = $statusStyles[$app->status] ?? ['bg'=>'#f9fafb','text'=>'#6b7280']; @endphp
                        <div class="cd-app-row">
                            <div class="cd-app-logo" style="background:{{ $logoBgs[$loop->index % count($logoBgs)] }}">
                                @if($app->jobPosting->company?->logo_url)
                                    <img src="{{ $app->jobPosting->company->logo_url }}" alt="" style="width:22px;height:22px;border-radius:4px;object-fit:cover">
                                @else
                                    {{ strtoupper(substr($app->jobPosting->company?->name ?? 'C', 0, 2)) }}
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('jobs.show', $app->jobPosting->slug) }}" class="text-sm font-semibold hover:text-primary truncate block dark:text-white" style="text-decoration:none;color:inherit">{{ $app->jobPosting->title }}</a>
                                <span class="text-xs text-gray-400">{{ $app->jobPosting->company?->name ?? 'Company' }}@if($app->applied_at) · {{ $app->applied_at->diffForHumans() }}@endif</span>
                            </div>
                            <span class="cd-status-pill" style="background:{{ $st['bg'] }};color:{{ $st['text'] }}">{{ Str::headline($app->status) }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- ═══════════════ SECTION 6: QUICK STATS ═══════════════ --}}
        <div class="col-span-12 xl:col-span-4" id="wt-stats">
            <div class="cd-section h-full">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-pie-chart-fill"></i> Quick Stats</span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="cd-stat" style="--stat-bg:#eef2ff">
                        <div class="cd-stat-num" style="color:#4f46e5">{{ $applications->count() }}</div>
                        <div class="cd-stat-lbl">Total Applied</div>
                    </div>
                    <div class="cd-stat" style="--stat-bg:#fef2f2">
                        <div class="cd-stat-num" style="color:#dc2626">{{ $declinedCount ?? 0 }}</div>
                        <div class="cd-stat-lbl">Declined</div>
                    </div>
                    <div class="cd-stat" style="--stat-bg:#fefce8">
                        <div class="cd-stat-num" style="color:#ca8a04">{{ $underReviewCount ?? 0 }}</div>
                        <div class="cd-stat-lbl">Under Review</div>
                    </div>
                    <div class="cd-stat" style="--stat-bg:#f0fdf4">
                        <div class="cd-stat-num" style="color:#16a34a">{{ $savedJobs ?? 0 }}</div>
                        <div class="cd-stat-lbl">Saved Jobs</div>
                    </div>
                </div>

                {{-- Browse CTA --}}
                <div class="cd-hero" style="padding:1rem;border-radius:10px;margin-top:0.75rem;flex-direction:column;align-items:flex-start">
                    <h6 class="text-white font-semibold text-sm mb-0.5">Looking for more?</h6>
                    <p class="text-white/60 text-xs mb-2">Explore all open positions</p>
                    <a href="{{ route('jobs') }}" class="cd-hero-btn cd-hero-btn-primary" style="font-size:0.75rem;padding:0.35rem 0.8rem"><i class="ri-search-line"></i> Browse All Jobs</a>
                </div>
            </div>
        </div>

    </div>

    @include('candidates.partials.walkthrough', [
        'wtKey' => 'dashboard',
        'wtSteps' => [
            ['target' => 'wt-hero', 'icon' => 'ri-home-smile-line', 'title' => 'Welcome to Your Dashboard', 'body' => 'This is your command center. From here you can search for jobs, view your applications, and complete your profile — all in one place.', 'position' => 'bottom'],
            ['target' => 'wt-pipeline', 'icon' => 'ri-bar-chart-fill', 'title' => 'Application Pipeline', 'body' => 'Track your applications at a glance. See how many jobs you\'ve applied to, how many are in the interview stage, offers received, and successful hires.', 'position' => 'bottom'],
            ['target' => 'wt-recommended', 'icon' => 'ri-briefcase-fill', 'title' => 'Recommended Jobs', 'body' => 'These jobs are hand-picked for you based on your profile, skills, and past applications. Scroll left/right to explore, and click "Quick Apply" to apply instantly.', 'position' => 'bottom'],
            ['target' => 'wt-skills', 'icon' => 'ri-lightbulb-flash-fill', 'title' => 'Skill Insights', 'body' => 'See which skills are trending in your industry. The growth indicators show demand changes. Use this to guide your learning and upskilling journey.', 'position' => 'left'],
            ['target' => 'wt-profile', 'icon' => 'ri-user-3-fill', 'title' => 'Profile Completion', 'body' => 'A complete profile gets 3× more recruiter views. Check your completion percentage and fill in missing sections to stand out to employers.', 'position' => 'left'],
            ['target' => 'wt-applications', 'icon' => 'ri-history-fill', 'title' => 'Recent Applications', 'body' => 'Your latest job applications appear here with real-time status updates. Click any application to view its details and track your progress.', 'position' => 'top'],
            ['target' => 'wt-stats', 'icon' => 'ri-pie-chart-fill', 'title' => 'Quick Stats', 'body' => 'Your job search overview at a glance — total applications, interviews, rejections, and saved jobs. Use the "Browse All Jobs" button to discover new opportunities.', 'position' => 'left'],
        ]
    ])

</x-app-layout>
