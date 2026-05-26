<x-app-layout :pageTitle="$pageTitle ?? 'User Profile'" :active="$active ?? null" :breadcrumbs="[['label' => 'Users', 'url' => route('moderator.users.index')]]">

    @if($applicantProfile)
    {{-- ═══════════════ APPLICANT PROFILE (Modern UI) ═══════════════ --}}
    <style>
        :root { --pf-primary: #1e293b; --pf-accent: #4f46e5; --pf-muted: #64748b; --pf-border: #e2e8f0; --pf-bg: #f8fafc; }

        .pf-card {
            background: #fff; border-radius: 12px;
            border: 1px solid var(--pf-border);
            overflow: hidden;
        }
        .pf-card-body { padding: 1.25rem; }

        /* ── Hero Banner ── */
        .pf-hero {
            --pf-hero-rgb: var(--primary-rgb, 79, 70, 229);
            --pf-hero-start: rgba(var(--pf-hero-rgb), 0.88);
            --pf-hero-mid: rgba(var(--pf-hero-rgb), 0.78);
            --pf-hero-end: rgba(var(--pf-hero-rgb), 0.68);
            background: linear-gradient(135deg, var(--pf-hero-start) 0%, var(--pf-hero-mid) 50%, var(--pf-hero-end) 100%);
            border: 1px solid rgba(var(--pf-hero-rgb), 0.28);
            border-radius: 12px;
            padding: 1.15rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .pf-hero-title { font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 0; }
        .pf-hero-sub { font-size: 0.8125rem; color: rgba(255,255,255,0.7); margin-bottom: 0; }
        .pf-hero-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0.4rem 0.85rem; border-radius: 12px;
            font-size: 0.75rem; font-weight: 600; text-decoration: none;
            transition: all 0.2s; cursor: pointer; border: none; white-space: nowrap;
            background: rgba(255,255,255,0.15); color: #fff; backdrop-filter: blur(4px);
        }
        .pf-hero-btn:hover { background: rgba(255,255,255,0.25); color: #fff; }

        /* ── Sidebar ── */
        .pf-sidebar-body { padding: 1.5rem 1.25rem 1.25rem; text-align: center; }
        .pf-avatar-wrap { position: relative; display: inline-block; margin-bottom: 0.85rem; }
        .pf-avatar {
            width: 100px; height: 100px; border-radius: 50%;
            overflow: hidden; border: 3px solid #fff;
            box-shadow: 0 2px 12px rgba(0,0,0,.1);
        }
        .pf-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .pf-online-dot {
            position: absolute; bottom: 6px; left: 6px;
            width: 14px; height: 14px; border-radius: 50%;
            border: 3px solid #fff;
        }
        .pf-name { font-size: 1.125rem; font-weight: 800; color: var(--pf-primary); margin-bottom: 2px; }
        .pf-role { font-size: 0.8125rem; color: var(--pf-muted); margin-bottom: 2px; }
        .pf-location { font-size: 0.78rem; color: var(--pf-accent); display: flex; align-items: center; justify-content: center; gap: 4px; margin-bottom: 1rem; }

        .pf-info-table { width: 100%; border-collapse: collapse; }
        .pf-info-table td {
            padding: 0.5rem 0; font-size: 0.8rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .pf-info-table tr:last-child td { border-bottom: none; }
        .pf-info-label { color: var(--pf-muted); font-weight: 500; text-align: left; }
        .pf-info-value { color: var(--pf-primary); font-weight: 700; text-align: right; }
        .pf-info-value .pf-badge-green {
            background: #dcfce7; color: #16a34a; padding: 2px 8px;
            border-radius: 4px; font-size: 0.72rem; font-weight: 700;
        }

        .pf-btn-primary {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%; padding: 0.55rem; border-radius: 8px;
            background: var(--pf-primary); color: #fff;
            border: none; font-weight: 700; font-size: 0.8125rem;
            cursor: pointer; transition: all 0.2s; text-decoration: none;
            margin-bottom: 0.4rem;
        }
        .pf-btn-primary:hover { background: #334155; color: #fff; }
        .pf-btn-secondary {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%; padding: 0.55rem; border-radius: 8px;
            background: #fff; color: var(--pf-primary);
            border: 1px solid var(--pf-border); font-weight: 600; font-size: 0.8125rem;
            cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .pf-btn-secondary:hover { background: var(--pf-bg); color: var(--pf-primary); }

        .pf-social-label {
            font-size: 0.7rem; font-weight: 700; color: var(--pf-muted);
            text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 0.6rem; padding-bottom: 0.4rem;
            border-bottom: 1px solid var(--pf-border);
            text-align: left;
        }
        .pf-social-link {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.8125rem; color: var(--pf-primary);
            text-decoration: none; padding: 0.35rem 0;
            transition: color 0.2s;
        }
        .pf-social-link:hover { color: var(--pf-accent); }
        .pf-social-link i { color: var(--pf-muted); font-size: 0.9rem; width: 18px; text-align: center; }

        /* ── Section Headers ── */
        .pf-sh {
            display: flex; align-items: center; gap: 8px;
            font-size: 1rem; font-weight: 800; color: var(--pf-primary);
            margin-bottom: 0.85rem;
        }
        .pf-sh i { font-size: 1.1rem; color: var(--pf-muted); }

        .pf-text { font-size: 0.8125rem; color: var(--pf-muted); line-height: 1.7; }
        .pf-empty { text-align: center; padding: 1rem 0; color: #94a3b8; font-size: 0.8125rem; font-style: italic; }

        /* ── Experience Timeline ── */
        .pf-exp-timeline { position: relative; }
        .pf-exp-center-line {
            position: absolute; left: 50%; top: 0; bottom: 0;
            width: 2px; background: var(--pf-border);
            transform: translateX(-50%);
        }
        .pf-exp-item {
            display: flex; align-items: flex-start;
            position: relative; margin-bottom: 2rem;
        }
        .pf-exp-item:last-child { margin-bottom: 0; }
        .pf-exp-dot {
            position: absolute; left: 50%; top: 8px;
            width: 12px; height: 12px; border-radius: 50%;
            background: var(--pf-accent); border: 3px solid #e0e7ff;
            transform: translateX(-50%); z-index: 2;
        }
        .pf-exp-left { width: 47%; padding-right: 2rem; text-align: right; }
        .pf-exp-right { width: 47%; padding-left: 2rem; margin-left: auto; }

        .pf-exp-role { font-weight: 800; font-size: 0.875rem; color: var(--pf-primary); }
        .pf-exp-date {
            display: inline-block; background: var(--pf-bg);
            border: 1px solid var(--pf-border); border-radius: 4px;
            padding: 2px 8px; font-size: 0.72rem; font-weight: 700;
            color: var(--pf-muted); margin-bottom: 4px;
        }
        .pf-exp-company { font-size: 0.8125rem; color: var(--pf-accent); font-weight: 600; margin-bottom: 4px; }
        .pf-exp-resp {
            list-style: disc; padding-left: 1rem; margin-top: 0.4rem;
            font-size: 0.78rem; color: var(--pf-muted); line-height: 1.6;
        }
        .pf-exp-resp li { margin-bottom: 3px; }

        @media (max-width: 768px) {
            .pf-exp-center-line { left: 8px; }
            .pf-exp-dot { left: 8px; }
            .pf-exp-left, .pf-exp-right {
                width: 100%; padding-left: 2rem; padding-right: 0;
                text-align: left; margin-left: 0;
            }
        }

        /* ── Education ── */
        .pf-edu-item {
            display: flex; align-items: flex-start; gap: 0.6rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .pf-edu-item:last-child { border-bottom: none; }
        .pf-edu-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--pf-primary); margin-top: 6px; flex-shrink: 0;
        }
        .pf-edu-title { font-weight: 700; font-size: 0.8125rem; color: var(--pf-primary); }
        .pf-edu-sub { font-size: 0.75rem; color: var(--pf-muted); }

        /* ── Skills Pills ── */
        .pf-pill {
            display: inline-block; padding: 0.3rem 0.7rem;
            border: 1px solid var(--pf-border); border-radius: 6px;
            font-size: 0.78rem; font-weight: 600; color: var(--pf-primary);
            background: #fff; transition: all 0.2s;
        }
        .pf-pill:hover { background: var(--pf-bg); border-color: var(--pf-accent); color: var(--pf-accent); }

        /* ── Languages ── */
        .pf-lang-row {
            display: flex; align-items: center; gap: 0.75rem;
            margin-bottom: 0.6rem;
        }
        .pf-lang-name { font-size: 0.8125rem; font-weight: 700; color: var(--pf-primary); min-width: 70px; }
        .pf-lang-bar-bg {
            flex: 1; height: 6px; background: #e2e8f0;
            border-radius: 3px; overflow: hidden;
        }
        .pf-lang-bar {
            height: 100%; background: var(--pf-primary); border-radius: 3px;
            transition: width 0.5s ease;
        }
        .pf-lang-level { font-size: 0.72rem; color: var(--pf-muted); font-weight: 600; min-width: 90px; text-align: right; }

        /* ── Achievements ── */
        .pf-ach-item {
            display: flex; align-items: flex-start; gap: 0.5rem;
            padding: 0.4rem 0; font-size: 0.8125rem; color: var(--pf-muted); line-height: 1.5;
        }
        .pf-ach-item i { color: #f59e0b; margin-top: 3px; font-size: 0.85rem; flex-shrink: 0; }

        /* ── Certifications ── */
        .pf-cert-item {
            display: flex; align-items: flex-start; gap: 0.6rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .pf-cert-item:last-child { border-bottom: none; }
        .pf-cert-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #f59e0b; margin-top: 6px; flex-shrink: 0;
        }
        .pf-cert-title { font-weight: 700; font-size: 0.8125rem; color: var(--pf-primary); }
        .pf-cert-sub { font-size: 0.75rem; color: var(--pf-muted); }

        /* ── References ── */
        .pf-ref-card {
            padding: 0.85rem; border-radius: 8px;
            border: 1px solid var(--pf-border); transition: all 0.2s;
        }
        .pf-ref-card:hover { border-color: #c7d2fe; background: #fafaff; }
        .pf-ref-name { font-weight: 800; font-size: 0.8125rem; color: var(--pf-primary); }
        .pf-ref-title { font-size: 0.75rem; color: var(--pf-accent); font-weight: 600; margin-bottom: 6px; }
        .pf-ref-meta {
            display: flex; align-items: center; gap: 4px;
            font-size: 0.75rem; color: var(--pf-muted); padding: 1px 0;
        }
        .pf-ref-meta i { font-size: 0.8rem; color: var(--pf-muted); width: 14px; }

        /* ── Dark Mode Overrides ── */
        [data-theme-mode="dark"], .dark {
            --pf-primary: #f8fafc;
            --pf-accent: #818cf8;
            --pf-muted: #94a3b8;
            --pf-border: rgba(255,255,255,0.08);
            --pf-bg: rgba(255,255,255,0.03);
        }
        [data-theme-mode="dark"] .pf-card, .dark .pf-card { background: var(--bodybg, #1a1c2e); }
        [data-theme-mode="dark"] .pf-avatar, .dark .pf-avatar { border-color: var(--bodybg, #1a1c2e); }
        [data-theme-mode="dark"] .pf-online-dot, .dark .pf-online-dot { border-color: var(--bodybg, #1a1c2e); }
        [data-theme-mode="dark"] .pf-info-table td, .dark .pf-info-table td { border-bottom-color: rgba(255,255,255,0.05); }
        [data-theme-mode="dark"] .pf-edu-item, .dark .pf-edu-item, [data-theme-mode="dark"] .pf-cert-item, .dark .pf-cert-item { border-bottom-color: rgba(255,255,255,0.05); }
        [data-theme-mode="dark"] .pf-btn-secondary, .dark .pf-btn-secondary { background: rgba(255,255,255,0.05); color: #e5e7eb; }
        [data-theme-mode="dark"] .pf-btn-secondary:hover, .dark .pf-btn-secondary:hover { background: rgba(255,255,255,0.1); }
        [data-theme-mode="dark"] .pf-btn-primary, .dark .pf-btn-primary { background: var(--pf-accent); color: #fff; }
        [data-theme-mode="dark"] .pf-btn-primary:hover, .dark .pf-btn-primary:hover { background: #6366f1; color: #fff; }
        [data-theme-mode="dark"] .pf-social-label, .dark .pf-social-label { border-bottom-color: rgba(255,255,255,0.05); }
        [data-theme-mode="dark"] .pf-pill, .dark .pf-pill { background: rgba(255,255,255,0.05); color: #e5e7eb; border-color: rgba(255,255,255,0.1); }
        [data-theme-mode="dark"] .pf-pill:hover, .dark .pf-pill:hover { background: rgba(255,255,255,0.1); border-color: var(--pf-accent); color: var(--pf-accent); }
        [data-theme-mode="dark"] .pf-ref-card:hover, .dark .pf-ref-card:hover { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.15); }
        [data-theme-mode="dark"] .pf-exp-dot, .dark .pf-exp-dot { border-color: var(--bodybg, #1a1c2e); }
        [data-theme-mode="dark"] .pf-lang-bar-bg, .dark .pf-lang-bar-bg { background: rgba(255,255,255,0.1); }
        [data-theme-mode="dark"] .pf-badge-green, .dark .pf-badge-green { background: rgba(34,197,94,0.15) !important; color: #4ade80 !important; }
        [data-theme-mode="dark"] .pf-hero,
        [data-bs-theme="dark"] .pf-hero,
        .dark .pf-hero,
        html.dark .pf-hero {
            --pf-hero-start: rgba(var(--pf-hero-rgb), 0.6);
            --pf-hero-mid: rgba(var(--pf-hero-rgb), 0.52);
            --pf-hero-end: rgba(var(--pf-hero-rgb), 0.44);
            border-color: rgba(var(--pf-hero-rgb), 0.36);
        }
    </style>

    {{-- Hero Banner --}}
    <div class="pf-hero">
        <div>
            <h1 class="pf-hero-title"><i class="ri-user-3-line me-1"></i> {{ $applicantProfile->display_name ?? $user->name }}</h1>
            <p class="pf-hero-sub">Applicant Profile</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('moderator.users.index') }}" class="pf-hero-btn">
                <i class="ri-arrow-left-line"></i> Back to Users
            </a>
            @if(auth()->id() !== $user->id)
            <a href="{{ route('chats.v2', ['user' => $user->id]) }}" class="pf-hero-btn" style="background: rgba(255,255,255,0.25);">
                <i class="ri-chat-3-line"></i> Send Message
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══════════════ SIDEBAR ═══════════════ --}}
        <div class="col-span-12 xl:col-span-3">
            <div class="pf-card">
                <div class="pf-sidebar-body">
                    {{-- Avatar --}}
                    <div class="pf-avatar-wrap">
                        <div class="pf-avatar">
                            <img src="{{ $avatar }}" alt="{{ $user->name }}">
                        </div>
                        <div class="pf-online-dot" style="background: {{ $status === 'online' ? '#22c55e' : ($status === 'idle' ? '#f59e0b' : ($status === 'dnd' ? '#ef4444' : '#9ca3af')) }};"></div>
                    </div>

                    <h2 class="pf-name">{{ $applicantProfile->display_name ?? $user->name }}</h2>
                    <p class="pf-role">{{ $applicantProfile->job_title ?? $applicantProfile->title ?? 'Title not set' }}</p>
                    <p class="pf-location" style="{{ $applicantProfile->rating ? 'margin-bottom: 0.25rem;' : '' }}"><i class="ri-map-pin-2-fill"></i> {{ $applicantProfile->location ?? 'Location not set' }}</p>

                    @if($applicantProfile->rating)
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px; margin-bottom: 1rem;">
                            <i class="ri-star-fill" style="color: #f59e0b; font-size: 1rem;"></i>
                            <span style="font-weight: 700; color: var(--pf-primary); font-size: 0.9rem;">{{ number_format($applicantProfile->rating, 1) }}</span>
                            @if($applicantProfile->rating_count)
                                <span style="font-size: 0.75rem; color: var(--pf-muted);">({{ $applicantProfile->rating_count }})</span>
                            @endif
                        </div>
                    @endif

                    {{-- CV Button --}}
                    @if($applicantProfile->cv_path)
                    <div style="margin-bottom:0.75rem">
                        <a href="{{ Storage::url($applicantProfile->cv_path) }}" target="_blank" rel="noopener noreferrer" class="pf-btn-primary">
                            <i class="ri-file-text-line"></i> View Resume
                        </a>
                    </div>
                    @endif

                    {{-- Info Table --}}
                    <table class="pf-info-table">
                        <tr>
                            <td class="pf-info-label"><i class="ri-computer-line me-1" style="font-size:0.75rem"></i> Work Mode</td>
                            <td class="pf-info-value">{{ $applicantProfile->work_mode ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="pf-info-label"><i class="ri-time-line me-1" style="font-size:0.75rem"></i> Experience</td>
                            <td class="pf-info-value">{{ $applicantProfile->years_experience ? $applicantProfile->years_experience . ' Years' : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="pf-info-label"><i class="ri-calendar-check-line me-1" style="font-size:0.75rem"></i> Availability</td>
                            <td class="pf-info-value">
                                @if($applicantProfile->availability)
                                    <span class="pf-badge-green">{{ $applicantProfile->availability }}</span>
                                @else - @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="pf-info-label"><i class="ri-briefcase-line me-1" style="font-size:0.75rem"></i> Job Type</td>
                            <td class="pf-info-value">{{ $applicantProfile->job_type ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="pf-info-label"><i class="ri-money-dollar-circle-line me-1" style="font-size:0.75rem"></i> Expected Salary</td>
                            <td class="pf-info-value">
                                @if($applicantProfile->expected_salary_min || $applicantProfile->expected_salary_max)
                                    {{ $applicantProfile->salary_currency ?? 'PHP' }} {{ number_format($applicantProfile->expected_salary_min ?? 0) }} - {{ number_format($applicantProfile->expected_salary_max ?? 0) }}
                                @else - @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Portfolio & Social --}}
            @if($applicantProfile->social_links)
            <div class="pf-card" style="margin-top:0.75rem">
                <div class="pf-card-body">
                    <div class="pf-social-label">Portfolio & Social</div>
                    <div>
                        @php $socials = is_array($applicantProfile->social_links) ? $applicantProfile->social_links : json_decode($applicantProfile->social_links, true); @endphp
                        @if(is_array($socials))
                            @foreach($socials as $key => $url)
                                @if(!empty($url))
                                    <a href="{{ $url }}" target="_blank" class="pf-social-link">
                                        @if(str_contains(strtolower($key), 'linkedin'))
                                            <i class="ri-linkedin-fill"></i>
                                        @elseif(str_contains(strtolower($key), 'github'))
                                            <i class="ri-github-fill"></i>
                                        @elseif(str_contains(strtolower($key), 'twitter') || str_contains(strtolower($key), 'x'))
                                            <i class="ri-twitter-x-fill"></i>
                                        @elseif(str_contains(strtolower($key), 'dribbble'))
                                            <i class="ri-dribbble-fill"></i>
                                        @elseif(str_contains(strtolower($key), 'website') || str_contains(strtolower($key), 'portfolio'))
                                            <i class="ri-global-fill"></i>
                                        @else
                                            <i class="ri-link"></i>
                                        @endif
                                        {{ is_string($key) ? ucfirst($key) : $url }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Account Info --}}
            <div class="pf-card" style="margin-top:0.75rem">
                <div class="pf-card-body">
                    <div class="pf-social-label">Account Information</div>
                    <table class="pf-info-table">
                        <tr>
                            <td class="pf-info-label">Email</td>
                            <td class="pf-info-value" style="font-size:0.72rem">{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td class="pf-info-label">Member Since</td>
                            <td class="pf-info-value">{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="pf-info-label">Account Age</td>
                            <td class="pf-info-value">{{ $user->created_at->diffForHumans(null, true) }}</td>
                        </tr>
                        <tr>
                            <td class="pf-info-label">Status</td>
                            <td class="pf-info-value">
                                <span style="display:inline-flex;align-items:center;gap:4px;">
                                    <span style="width:8px;height:8px;border-radius:50%;background:{{ $status === 'online' ? '#22c55e' : ($status === 'idle' ? '#f59e0b' : ($status === 'dnd' ? '#ef4444' : '#9ca3af')) }}"></span>
                                    {{ $status === 'online' ? 'Online' : ($status === 'idle' ? 'Away' : ($status === 'dnd' ? 'Do Not Disturb' : 'Offline')) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- ═══════════════ MAIN CONTENT ═══════════════ --}}
        <div class="col-span-12 xl:col-span-9">

            {{-- ── About Me & Career Objective (side by side) ── --}}
            <div class="grid grid-cols-12 gap-4" style="margin-bottom:1rem">
                <div class="col-span-12 md:col-span-6">
                    <div class="pf-card" style="height:100%">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-user-heart-line"></i> About Me</div>
                            <p class="pf-text">{{ $applicantProfile->about ?? 'No information provided.' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6">
                    <div class="pf-card" style="height:100%">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-focus-3-line"></i> Career Objective</div>
                            <p class="pf-text">{{ $applicantProfile->career_objective ?? 'No information provided.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Experience (Zigzag Timeline) ── --}}
            <div class="pf-card" style="margin-bottom:1rem">
                <div class="pf-card-body">
                    <div class="pf-sh"><i class="ri-briefcase-line"></i> Experience</div>
                    @php
                        $experience = $applicantProfile->experience_overview ? json_decode($applicantProfile->experience_overview, true) : null;
                    @endphp
                    @if($experience && !empty($experience['position']))
                        <div class="pf-exp-timeline">
                            <div class="pf-exp-center-line"></div>
                            <div class="pf-exp-item left">
                                <div class="pf-exp-dot"></div>
                                <div class="pf-exp-left">
                                    <div class="pf-exp-content">
                                        <div class="pf-exp-role">{{ $experience['position'] }}</div>
                                        <div class="pf-exp-company">{{ $experience['company'] ?? '' }}</div>
                                        @if(!empty($experience['responsibilities']))
                                            <ul class="pf-exp-resp">
                                                @foreach($experience['responsibilities'] as $resp)
                                                    @if(!empty($resp))
                                                        <li>{{ $resp }}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                                <div class="pf-exp-right" style="display:flex;align-items:flex-start;justify-content:flex-start">
                                    <div>
                                        <span class="pf-exp-date">{{ $experience['start_date'] ?? '' }} - {{ $experience['end_date'] ?? 'Present' }}</span>
                                        @if(!empty($experience['location']))
                                            <div style="font-size:0.75rem;color:var(--pf-muted);margin-top:4px">
                                                <i class="ri-map-pin-line me-1"></i>{{ $experience['location'] }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="pf-empty">No experience added yet.</div>
                    @endif
                </div>
            </div>

            {{-- ── Education & Certs + Skills & Tools (side by side) ── --}}
            <div class="grid grid-cols-12 gap-4" style="margin-bottom:1rem">
                {{-- Education & Certs --}}
                <div class="col-span-12 md:col-span-6">
                    <div class="pf-card" style="height:100%">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-graduation-cap-line"></i> Education & Certs</div>

                            @php
                                $education = $applicantProfile->education_details ? json_decode($applicantProfile->education_details, true) : [];
                                $certifications = $applicantProfile->certifications ? json_decode($applicantProfile->certifications, true) : [];
                            @endphp

                            @if(is_array($education) && count($education) > 0)
                                @foreach($education as $edu)
                                    <div class="pf-edu-item">
                                        <div class="pf-edu-dot"></div>
                                        <div>
                                            <div class="pf-edu-title">{{ $edu['course'] ?? '' }}</div>
                                            <div class="pf-edu-sub">{{ $edu['school'] ?? '' }}@if(!empty($edu['dates'])) • {{ $edu['dates'] }}@endif</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="pf-empty" style="padding:0.5rem 0">No education details added.</div>
                            @endif

                            @if(is_array($certifications) && count($certifications) > 0)
                                <div style="margin-top:0.75rem;padding-top:0.5rem;border-top:1px solid var(--pf-border)">
                                @foreach($certifications as $cert)
                                    <div class="pf-cert-item">
                                        <div class="pf-cert-dot"></div>
                                        <div>
                                            <div class="pf-cert-title">{{ $cert['title'] ?? '' }}</div>
                                            <div class="pf-cert-sub">{{ $cert['provider'] ?? '' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Skills & Tools --}}
                <div class="col-span-12 md:col-span-6">
                    <div class="pf-card" style="height:100%">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-tools-line"></i> Skills & Tools</div>
                            @php
                                $skills = $applicantProfile->skills ? json_decode($applicantProfile->skills, true) : [];
                                $tools = $applicantProfile->tools_used ? json_decode($applicantProfile->tools_used, true) : [];
                                $allSkills = array_merge(
                                    is_array($skills) ? array_filter($skills, fn($s) => !empty($s)) : [],
                                    is_array($tools) ? array_filter($tools, fn($t) => !empty($t)) : []
                                );
                            @endphp
                            @if(count($allSkills) > 0)
                                <div style="display:flex;flex-wrap:wrap;gap:6px">
                                    @foreach($allSkills as $item)
                                        <span class="pf-pill">{{ $item }}</span>
                                    @endforeach
                                </div>
                            @else
                                <div class="pf-empty">No skills or tools added yet.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Key Achievements & Languages (side by side) ── --}}
            <div class="grid grid-cols-12 gap-4" style="margin-bottom:1rem">
                {{-- Key Achievements --}}
                <div class="col-span-12 md:col-span-6">
                    <div class="pf-card" style="height:100%">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-trophy-line"></i> Key Achievements</div>
                            @php $achievements = $applicantProfile->key_achievements ? json_decode($applicantProfile->key_achievements, true) : []; @endphp
                            @if(is_array($achievements) && count($achievements) > 0)
                                @foreach($achievements as $ach)
                                    @if(!empty($ach))
                                        <div class="pf-ach-item">
                                            <i class="ri-star-fill"></i>
                                            <span>{{ $ach }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="pf-empty">No achievements added.</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Languages --}}
                <div class="col-span-12 md:col-span-6">
                    <div class="pf-card" style="height:100%">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-translate-2"></i> Languages</div>
                            @php $languages = $applicantProfile->languages ? json_decode($applicantProfile->languages, true) : []; @endphp
                            @if(is_array($languages) && count($languages) > 0)
                                @foreach($languages as $idx => $lang)
                                    @if(!empty($lang))
                                        @php
                                            $barWidths = [100, 75, 55, 40, 30];
                                            $barLabels = ['Native', 'Professional', 'Conversational', 'Basic', 'Beginner'];
                                            $bIdx = min($idx, count($barWidths) - 1);
                                        @endphp
                                        <div class="pf-lang-row">
                                            <span class="pf-lang-name">{{ $lang }}</span>
                                            <div class="pf-lang-bar-bg">
                                                <div class="pf-lang-bar" style="width:{{ $barWidths[$bIdx] }}%"></div>
                                            </div>
                                            <span class="pf-lang-level">{{ $barLabels[$bIdx] }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="pf-empty">No languages added.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── References ── --}}
            <div class="pf-card">
                <div class="pf-card-body">
                    <div class="pf-sh" style="justify-content:center"><i class="ri-contacts-book-line"></i> References</div>
                    @php
                        $references = $applicantProfile->references_block ? json_decode($applicantProfile->references_block, true) : [];
                    @endphp
                    @if(is_array($references) && count($references) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($references as $ref)
                            <div class="pf-ref-card">
                                <div class="pf-ref-name">{{ $ref['name'] ?? '' }}</div>
                                <div class="pf-ref-title">{{ $ref['designation'] ?? '' }}@if(!empty($ref['company'])) at {{ $ref['company'] }}@endif</div>
                                @if(!empty($ref['email']))
                                    <div class="pf-ref-meta"><i class="ri-mail-line"></i> {{ $ref['email'] }}</div>
                                @endif
                                @if(!empty($ref['mobile']))
                                    <div class="pf-ref-meta"><i class="ri-phone-line"></i> {{ $ref['mobile'] }}</div>
                                @endif
                                @if(!empty($ref['location']))
                                    <div class="pf-ref-meta"><i class="ri-map-pin-line"></i> {{ $ref['location'] }}</div>
                                @endif
                            </div>
                        @endforeach
                        </div>
                    @else
                        <div class="pf-empty">No references added.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    @elseif($employerProfile)
    {{-- ═══════════════ EMPLOYER PROFILE (Modern UI) ═══════════════ --}}
    <style>
        :root { --pf-primary: #1e293b; --pf-accent: #4f46e5; --pf-muted: #64748b; --pf-border: #e2e8f0; --pf-bg: #f8fafc; }

        .pf-card {
            background: #fff; border-radius: 12px;
            border: 1px solid var(--pf-border);
            overflow: hidden;
        }
        .pf-card-body { padding: 1.25rem; }

        /* ── Hero Banner ── */
        .pf-hero {
            --pf-hero-rgb: var(--primary-rgb, 79, 70, 229);
            --pf-hero-start: rgba(var(--pf-hero-rgb), 0.88);
            --pf-hero-mid: rgba(var(--pf-hero-rgb), 0.78);
            --pf-hero-end: rgba(var(--pf-hero-rgb), 0.68);
            background: linear-gradient(135deg, var(--pf-hero-start) 0%, var(--pf-hero-mid) 50%, var(--pf-hero-end) 100%);
            border: 1px solid rgba(var(--pf-hero-rgb), 0.28);
            border-radius: 12px;
            padding: 1.15rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .pf-hero-title { font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 0; }
        .pf-hero-sub { font-size: 0.8125rem; color: rgba(255,255,255,0.7); margin-bottom: 0; }
        .pf-hero-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0.4rem 0.85rem; border-radius: 12px;
            font-size: 0.75rem; font-weight: 600; text-decoration: none;
            transition: all 0.2s; cursor: pointer; border: none; white-space: nowrap;
            background: rgba(255,255,255,0.15); color: #fff; backdrop-filter: blur(4px);
        }
        .pf-hero-btn:hover { background: rgba(255,255,255,0.25); color: #fff; }

        /* ── Sidebar ── */
        .pf-sidebar-body { padding: 1.5rem 1.25rem 1.25rem; text-align: center; }
        .pf-avatar-wrap { position: relative; display: inline-block; margin-bottom: 0.85rem; }
        .pf-avatar {
            width: 100px; height: 100px; border-radius: 16px;
            overflow: hidden; border: 3px solid #fff;
            box-shadow: 0 2px 12px rgba(0,0,0,.1);
            background: #fff;
        }
        .pf-avatar img { width: 100%; height: 100%; object-fit: contain; padding: 4px; }
        .pf-online-dot {
            position: absolute; bottom: 6px; left: 6px;
            width: 14px; height: 14px; border-radius: 50%;
            border: 3px solid #fff;
        }
        .pf-name { font-size: 1.125rem; font-weight: 800; color: var(--pf-primary); margin-bottom: 2px; }
        .pf-role { font-size: 0.8125rem; color: var(--pf-muted); margin-bottom: 2px; }
        .pf-location { font-size: 0.78rem; color: var(--pf-accent); display: flex; align-items: center; justify-content: center; gap: 4px; margin-bottom: 1rem; }

        .pf-info-table { width: 100%; border-collapse: collapse; }
        .pf-info-table td {
            padding: 0.5rem 0; font-size: 0.8rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .pf-info-table tr:last-child td { border-bottom: none; }
        .pf-info-label { color: var(--pf-muted); font-weight: 500; text-align: left; }
        .pf-info-value { color: var(--pf-primary); font-weight: 700; text-align: right; }
        .pf-info-value .pf-badge-green {
            background: #dcfce7; color: #16a34a; padding: 2px 8px;
            border-radius: 4px; font-size: 0.72rem; font-weight: 700;
        }
        .pf-badge-blue {
            background: #dbeafe; color: #2563eb; padding: 2px 8px;
            border-radius: 4px; font-size: 0.72rem; font-weight: 700;
        }

        .pf-btn-primary {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%; padding: 0.55rem; border-radius: 8px;
            background: var(--pf-primary); color: #fff;
            border: none; font-weight: 700; font-size: 0.8125rem;
            cursor: pointer; transition: all 0.2s; text-decoration: none;
            margin-bottom: 0.4rem;
        }
        .pf-btn-primary:hover { background: #334155; color: #fff; }

        .pf-social-label {
            font-size: 0.7rem; font-weight: 700; color: var(--pf-muted);
            text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 0.6rem; padding-bottom: 0.4rem;
            border-bottom: 1px solid var(--pf-border);
            text-align: left;
        }

        .pf-sh {
            display: flex; align-items: center; gap: 8px;
            font-size: 1rem; font-weight: 800; color: var(--pf-primary);
            margin-bottom: 0.85rem;
        }
        .pf-sh i { font-size: 1.1rem; color: var(--pf-muted); }

        .pf-text { font-size: 0.8125rem; color: var(--pf-muted); line-height: 1.7; }
        .pf-empty { text-align: center; padding: 1rem 0; color: #94a3b8; font-size: 0.8125rem; font-style: italic; }

        /* ── Dark Mode Overrides ── */
        [data-theme-mode="dark"], .dark {
            --pf-primary: #f8fafc;
            --pf-accent: #818cf8;
            --pf-muted: #94a3b8;
            --pf-border: rgba(255,255,255,0.08);
            --pf-bg: rgba(255,255,255,0.03);
        }
        [data-theme-mode="dark"] .pf-card, .dark .pf-card { background: var(--bodybg, #1a1c2e); }
        [data-theme-mode="dark"] .pf-avatar, .dark .pf-avatar { border-color: var(--bodybg, #1a1c2e); }
        [data-theme-mode="dark"] .pf-info-table td, .dark .pf-info-table td { border-bottom-color: rgba(255,255,255,0.05); }
        [data-theme-mode="dark"] .pf-btn-primary, .dark .pf-btn-primary { background: var(--pf-accent); color: #fff; }
        [data-theme-mode="dark"] .pf-social-label, .dark .pf-social-label { border-bottom-color: rgba(255,255,255,0.05); }
        [data-theme-mode="dark"] .pf-badge-green, .dark .pf-badge-green { background: rgba(34,197,94,0.15) !important; color: #4ade80 !important; }
        [data-theme-mode="dark"] .pf-badge-blue, .dark .pf-badge-blue { background: rgba(37,99,235,0.15) !important; color: #60a5fa !important; }
        [data-theme-mode="dark"] .pf-hero,
        [data-bs-theme="dark"] .pf-hero,
        .dark .pf-hero,
        html.dark .pf-hero {
            --pf-hero-start: rgba(var(--pf-hero-rgb), 0.6);
            --pf-hero-mid: rgba(var(--pf-hero-rgb), 0.52);
            --pf-hero-end: rgba(var(--pf-hero-rgb), 0.44);
            border-color: rgba(var(--pf-hero-rgb), 0.36);
        }
    </style>

    {{-- Hero Banner --}}
    <div class="pf-hero">
        <div>
            <h1 class="pf-hero-title"><i class="ri-building-2-line me-1"></i> {{ $employerProfile->name ?? $user->name }}</h1>
            <p class="pf-hero-sub">Employer / Company Profile</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('moderator.users.index') }}" class="pf-hero-btn">
                <i class="ri-arrow-left-line"></i> Back to Users
            </a>
            @if(auth()->id() !== $user->id)
            <a href="{{ route('chats.v2', ['user' => $user->id]) }}" class="pf-hero-btn" style="background: rgba(255,255,255,0.25);">
                <i class="ri-chat-3-line"></i> Send Message
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">
        {{-- Sidebar --}}
        <div class="col-span-12 xl:col-span-3">
            <div class="pf-card">
                <div class="pf-sidebar-body">
                    <div class="pf-avatar-wrap">
                        <div class="pf-avatar">
                            <img src="{{ $employerProfile->logo_url ? Storage::url($employerProfile->logo_url) : $avatar }}" alt="{{ $employerProfile->name ?? $user->name }}">
                        </div>
                        <div class="pf-online-dot" style="background: {{ $status === 'online' ? '#22c55e' : ($status === 'idle' ? '#f59e0b' : ($status === 'dnd' ? '#ef4444' : '#9ca3af')) }};"></div>
                    </div>

                    <h2 class="pf-name">{{ $employerProfile->name ?? $user->name }}</h2>
                    <p class="pf-role">{{ $employerProfile->industry ?? 'Industry not set' }}</p>
                    <p class="pf-location"><i class="ri-map-pin-2-fill"></i> {{ $employerProfile->location ?? 'Location not set' }}</p>

                    @if($employerProfile->rating)
                        <div style="display:flex; align-items:center; justify-content:center; gap:4px; margin-bottom: 1rem;">
                            <i class="ri-star-fill" style="color: #f59e0b; font-size: 1rem;"></i>
                            <span style="font-weight: 700; color: var(--pf-primary); font-size: 0.9rem;">{{ number_format($employerProfile->rating, 1) }}</span>
                            @if($employerProfile->rating_count)
                                <span style="font-size: 0.75rem; color: var(--pf-muted);">({{ $employerProfile->rating_count }})</span>
                            @endif
                        </div>
                    @endif

                    @if($employerProfile->website)
                    <div style="margin-bottom:1rem">
                        <a href="{{ $employerProfile->website }}" target="_blank" rel="noopener noreferrer" class="pf-btn-primary">
                            <i class="ri-global-line"></i> Visit Website
                        </a>
                    </div>
                    @endif

                    <table class="pf-info-table">
                        <tr>
                            <td class="pf-info-label">Status</td>
                            <td class="pf-info-value">
                                @if($employerProfile->verified)
                                    <span class="pf-badge-green"><i class="ri-checkbox-circle-line me-1"></i>Verified</span>
                                @else
                                    <span class="pf-badge-blue">Not Verified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="pf-info-label">Industry</td>
                            <td class="pf-info-value">{{ $employerProfile->industry ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="pf-info-label">Employees</td>
                            <td class="pf-info-value">{{ $employerProfile->employees_count ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="pf-info-label">Established</td>
                            <td class="pf-info-value">{{ $employerProfile->established_year ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Company Contact --}}
            <div class="pf-card" style="margin-top:0.75rem">
                <div class="pf-card-body">
                    <div class="pf-social-label">Company Contact</div>
                    <table class="pf-info-table">
                        @if($employerProfile->email)
                        <tr>
                            <td class="pf-info-label">Email</td>
                            <td class="pf-info-value" style="font-size:0.72rem">{{ $employerProfile->email }}</td>
                        </tr>
                        @endif
                        @if($employerProfile->phone)
                        <tr>
                            <td class="pf-info-label">Phone</td>
                            <td class="pf-info-value">{{ $employerProfile->phone }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="pf-info-label">Location</td>
                            <td class="pf-info-value">{{ $employerProfile->location ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-span-12 xl:col-span-9">
            <div class="grid grid-cols-12 gap-4">
                {{-- About Company --}}
                <div class="col-span-12">
                    <div class="pf-card">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-information-line"></i> About Company</div>
                            <p class="pf-text">{{ $employerProfile->description ?? 'No description provided.' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Contact Person --}}
                <div class="col-span-12 md:col-span-6">
                    <div class="pf-card" style="height:100%">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-user-settings-line"></i> Contact Person</div>
                            <table class="pf-info-table">
                                <tr>
                                    <td class="pf-info-label">Name</td>
                                    <td class="pf-info-value">{{ $employerProfile->contact_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="pf-info-label">Position</td>
                                    <td class="pf-info-value">{{ $employerProfile->contact_position ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="pf-info-label">Email</td>
                                    <td class="pf-info-value" style="font-size:0.72rem">{{ $employerProfile->contact_person_email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="pf-info-label">Phone</td>
                                    <td class="pf-info-value">{{ $employerProfile->contact_person_phone ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Registration Details --}}
                <div class="col-span-12 md:col-span-6">
                    <div class="pf-card" style="height:100%">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-file-list-3-line"></i> Business Registration</div>
                            <table class="pf-info-table">
                                <tr>
                                    <td class="pf-info-label">Reg. Type</td>
                                    <td class="pf-info-value">{{ $employerProfile->registration_type ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="pf-info-label">Reg. Number</td>
                                    <td class="pf-info-value">{{ $employerProfile->registration_number ?? '-' }}</td>
                                </tr>
                                @if($employerProfile->registration_document_url)
                                <tr>
                                    <td class="pf-info-label">Documents</td>
                                    <td class="pf-info-value">
                                        <a href="{{ Storage::url($employerProfile->registration_document_url) }}" target="_blank" class="text-indigo-600 hover:underline">
                                            View Document <i class="ri-external-link-line"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="pf-info-label">Verified At</td>
                                    <td class="pf-info-value">{{ $employerProfile->verified_at ? $employerProfile->verified_at->format('M d, Y') : 'Not Verified' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Business Address --}}
                <div class="col-span-12">
                    <div class="pf-card">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-map-pin-line"></i> Registered Business Address</div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                <p class="pf-text font-medium text-gray-900 dark:text-white">
                                    {{ $employerProfile->business_address ?? 'No address provided' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $employerProfile->city ?? '' }}, {{ $employerProfile->province ?? '' }} {{ $employerProfile->postal_code ?? '' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $employerProfile->country ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    {{-- ═══════════════ NON-APPLICANT PROFILE (Original UI) ═══════════════ --}}
    <div class="max-w-5xl mx-auto">
        <div class="bg-white dark:bg-[#1a1a24] rounded-2xl shadow-xl border border-gray-200 dark:border-gray-800">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-start gap-6">
                    <div class="relative flex-shrink-0 mx-auto md:mx-0">
                        <img src="{{ $avatar }}" alt="{{ $user->name }}"
                            class="w-36 h-36 md:w-44 md:h-44 rounded-2xl border-4 border-gray-100 dark:border-gray-800 bg-white dark:bg-[#222] object-cover shadow-lg">
                        <div class="absolute -bottom-1 -right-1 p-1 bg-white dark:bg-[#1a1a24] rounded-full">
                            <span class="block w-5 h-5 rounded-full border-2 border-white dark:border-[#1a1a24]
                                {{ $status === 'online' ? 'bg-emerald-500' : ($status === 'idle' ? 'bg-amber-400' : ($status === 'dnd' ? 'bg-rose-500' : 'bg-gray-400')) }}">
                            </span>
                        </div>
                    </div>

                    <div class="flex-1 text-center md:text-left">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-2">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium
                                        {{ $user->role === 'moderator' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' :
                                           ($user->role === 'employer' ? 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300' :
                                           'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300') }}">
                                        @if($user->role === 'moderator')
                                            <i class="bi bi-shield-check"></i>
                                        @elseif($user->role === 'employer')
                                            <i class="bi bi-building"></i>
                                        @else
                                            <i class="bi bi-person"></i>
                                        @endif
                                        {{ ucfirst($user->role ?? 'Applicant') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 text-sm
                                        {{ $status === 'online' ? 'text-emerald-600 dark:text-emerald-400' :
                                           ($status === 'idle' ? 'text-amber-600 dark:text-amber-400' :
                                           ($status === 'dnd' ? 'text-rose-600 dark:text-rose-400' : 'text-gray-500 dark:text-gray-400')) }}">
                                        <span class="w-2 h-2 rounded-full
                                            {{ $status === 'online' ? 'bg-emerald-500' : ($status === 'idle' ? 'bg-amber-400' : ($status === 'dnd' ? 'bg-rose-500' : 'bg-gray-400')) }}"></span>
                                        {{ $status === 'online' ? 'Online' : ($status === 'idle' ? 'Away' : ($status === 'dnd' ? 'Do Not Disturb' : 'Offline')) }}
                                    </span>
                                </div>
                            </div>

                            @auth
                                @if(auth()->id() !== $user->id)
                                    <a href="{{ route('chats.v2', ['user' => $user->id]) }}"
                                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 transition-all duration-200">
                                        <i class="bi bi-chat-dots-fill"></i>
                                        Send Message
                                    </a>
                                @else
                                    <a href="{{ route('profile.show') }}"
                                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition-all duration-200">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit Profile
                                    </a>
                                @endif
                            @endauth
                        </div>

                        @if($user->bio)
                        <p class="mt-4 text-gray-600 dark:text-gray-400 max-w-2xl">{{ $user->bio }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-800">
                <div class="grid md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100 dark:divide-gray-800">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                <i class="bi bi-info-circle text-indigo-600 dark:text-indigo-400"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Information</h3>
                        </div>
                        <dl class="space-y-3">
                            @if($user->email && auth()->user()->isModerator())
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-500 uppercase tracking-wider">Email</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white flex items-center gap-2">
                                    <i class="bi bi-envelope text-gray-400 text-sm"></i>
                                    {{ $user->email }}
                                </dd>
                            </div>
                            @endif
                            @if($user->phone && auth()->user()->isModerator())
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-500 uppercase tracking-wider">Phone</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white flex items-center gap-2">
                                    <i class="bi bi-telephone text-gray-400 text-sm"></i>
                                    {{ $user->phone }}
                                </dd>
                            </div>
                            @endif
                            @if($user->address)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-500 uppercase tracking-wider">Location</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white flex items-center gap-2">
                                    <i class="bi bi-geo-alt text-gray-400 text-sm"></i>
                                    {{ $user->address }}
                                </dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-500 uppercase tracking-wider">Member Since</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white flex items-center gap-2">
                                    <i class="bi bi-calendar3 text-gray-400 text-sm"></i>
                                    {{ $user->created_at->format('F j, Y') }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center">
                                <i class="bi bi-share text-violet-600 dark:text-violet-400"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Social Links</h3>
                        </div>
                        @if($user->social_facebook || $user->social_twitter || $user->social_instagram || $user->social_github || $user->social_youtube)
                        <div class="flex flex-wrap gap-2">
                            @if($user->social_facebook)
                            <a href="{{ $user->social_facebook }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition text-sm font-medium">
                                <i class="bi bi-facebook"></i> Facebook
                            </a>
                            @endif
                            @if($user->social_twitter)
                            <a href="{{ $user->social_twitter }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-sky-50 dark:bg-sky-900/20 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-900/40 transition text-sm font-medium">
                                <i class="bi bi-twitter-x"></i> Twitter
                            </a>
                            @endif
                            @if($user->social_instagram)
                            <a href="{{ $user->social_instagram }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-pink-50 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400 hover:bg-pink-100 dark:hover:bg-pink-900/40 transition text-sm font-medium">
                                <i class="bi bi-instagram"></i> Instagram
                            </a>
                            @endif
                            @if($user->social_github)
                            <a href="{{ $user->social_github }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-sm font-medium">
                                <i class="bi bi-github"></i> GitHub
                            </a>
                            @endif
                            @if($user->social_youtube)
                            <a href="{{ $user->social_youtube }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition text-sm font-medium">
                                <i class="bi bi-youtube"></i> YouTube
                            </a>
                            @endif
                        </div>
                        @else
                        <p class="text-gray-500 dark:text-gray-500 text-sm">No social links added yet.</p>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                <i class="bi bi-bar-chart text-emerald-600 dark:text-emerald-400"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Activity</h3>
                        </div>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-500 uppercase tracking-wider">Account Age</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $user->created_at->diffForHumans(null, true) }}</dd>
                            </div>
                            @if($user->gender)
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-500 uppercase tracking-wider">Gender</dt>
                                <dd class="mt-1 text-gray-900 dark:text-white">{{ $user->gender }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</x-app-layout>
