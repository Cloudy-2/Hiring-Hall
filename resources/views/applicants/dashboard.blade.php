<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="url_1">{"link": "/jobs", "text": "Job Listing"}</x-slot>
    <x-slot name="active"> Dashboard</x-slot>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

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
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) {
            --bg-card: #202124;
            --border-card: #303134;
            --text-main: #f8f9fa;
            --text-sub: #9aa0a6;
            --bg-hover: #2b2c30;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) body {
            background: #1f1f1f !important;
        }

        .cd-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
        }

        .cd-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-card,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-profile-card,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-pipeline-card,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-tracking-card,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .jf-header-section {
            background-color: var(--bg-card) !important;
        }

        .cd-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 0.75rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid var(--border-card);
        }

        .cd-section-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 800;
            font-size: 1rem;
            color: var(--text-main);
            letter-spacing: -0.01em;
        }

        .cd-section-title i {
            color: #6366f1;
            font-size: 1.15rem;
        }

        .cd-accent-btn {
            background-color: var(--cd-accent);
            color: #ffffff;
            transition: none;
        }
        .cd-accent-btn:hover { background-color: #4338ca; color: #ffffff; }

        .cd-ghost-btn {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            transition: none;
        }
        :is([data-theme-mode="dark"], .dark) .cd-ghost-btn {
            background-color: rgba(255, 255, 255, 0.05); color: #f1f5f9; border-color: rgba(255,255,255,0.1);
        }

        /* Pending Status Banner - Dark Mode */
        :is([data-theme-mode="dark"], .dark) [style*="background: #fef3c7"] {
            background: rgba(217, 119, 6, 0.15) !important;
            border-color: rgba(217, 119, 6, 0.3) !important;
        }

        :is([data-theme-mode="dark"], .dark) [style*="color: #92400e"] {
            color: #fbbf24 !important;
        }

        :is([data-theme-mode="dark"], .dark) [style*="color: #b45309"] {
            color: #fcd34d !important;
        }

        /* Dedicated Dashboard Fix for Recommended Jobs Visibility */
        :is([data-theme-mode="dark"], .dark) #recommendedSwiper .text-[var(--text-sub)],
        :is([data-theme-mode="dark"], .dark) #recommendedSwiper [style*="var(--text-sub)"],
        :is([data-theme-mode="dark"], .dark) #recommendedSwiper .text-xs.font-medium,
        :is([data-theme-mode="dark"], .dark) #recommendedSwiper .rj-card-meta,
        :is([data-theme-mode="dark"], .dark) #recommendedSwiper .company-rating-count,
        :is([data-theme-mode="dark"], .dark) #recommendedSwiper span[style*="color:var(--text-sub)"] {
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], .dark) #recommendedSwiper .font-bold.text-[var(--text-main)] {
            color: #ffffff !important;
        }

        /* Swiper Pagination Professional Styling */
        #recommendedSwiperPagination {
            position: relative !important;
            margin-top: 1.5rem !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            pointer-events: auto !important;
        }

        #recommendedSwiperPagination .swiper-pagination-bullet {
            background: #cbd5e1 !important;
            opacity: 0.4 !important;
            width: 7px !important;
            height: 7px !important;
            margin: 0 4px !important;
        }

        #recommendedSwiper .swiper-pagination-bullet-active {
            background: #6366f1 !important;
            opacity: 1 !important;
            width: 20px !important;
            border-radius: 4px !important;
        }

        :is([data-theme-mode="dark"], .dark) #recommendedSwiperPagination .swiper-pagination-bullet {
            background: rgba(255,255,255,0.2) !important;
        }

        /* Card Layout Polish */
        .swiper-slide {
            height: auto !important;
        }

        .jf-header-side {
            position: absolute;
            top: 0.85rem;
            right: 0.85rem;
            min-width: 260px;
            z-index: 2;
        }

        .jf-header-clock {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 0.8rem;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.875rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            color: #6b7280;
            font-size: 0.8125rem;
            font-weight: 600;
            line-height: 1;
            white-space: nowrap;
        }
        .jf-header-clock-date {
            text-transform: capitalize;
            letter-spacing: -0.01em;
        }
        .jf-header-clock-divider {
            opacity: 0.35;
        }
        .jf-header-clock-time {
            display: inline-flex;
            align-items: baseline;
            gap: 0.15rem;
            color: #374151;
            font-weight: 800;
        }
        .jf-header-clock-segment {
            min-width: 1.25ch;
            text-align: center;
        }
        .jf-header-clock-meridiem {
            margin-left: 0.15rem;
            font-size: 0.625rem;
            letter-spacing: 0.08em;
        }
        .jf-header-clock-tz {
            padding: 0.2rem 0.45rem;
            border-radius: 0.45rem;
            background: #eff6ff;
            border: 1px solid #cfe3ff;
            color: #2563eb;
            font-size: 0.625rem;
            font-weight: 800;
            letter-spacing: 0.08em;
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-clock {
            background: rgba(15, 23, 42, 0.72);
            border-color: rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        :is([data-theme-mode="dark"], .dark) .jf-header-clock-time { color: #f8fafc; }
        :is([data-theme-mode="dark"], .dark) .jf-header-clock-tz {
            background: rgba(59, 130, 246, 0.18);
            border-color: rgba(59, 130, 246, 0.28);
            color: #93c5fd;
        }

        /* ── High-Impact Profile Card (Flat Minimal) ── */
        .jf-profile-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 1.25rem;
            padding: 1.5rem;
            transition: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            display: flex;
            flex-direction: column;
            position: relative;
        }
        :is([data-theme-mode="dark"], .dark) .jf-profile-card { background: rgba(15, 23, 42, 0.4); border-color: rgba(255,255,255,0.06); box-shadow: none; }

        .jf-profile-header-row {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 1.25rem;
        }

        .jf-circle-progress {
            width: 86px;
            height: 86px;
            position: relative;
            flex-shrink: 0;
        }

        .jf-circle-svg { transform: rotate(-90deg); }
        .jf-circle-bg { fill: none; stroke: #f1f5f9; stroke-width: 8; }
        :is([data-theme-mode="dark"], .dark) .jf-circle-bg { stroke: rgba(255,255,255,0.05); }
        .jf-circle-bar {
            fill: none;
            stroke: #6366f1;
            stroke-width: 8;
            stroke-linecap: round;
        }

        .jf-circle-val {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            font-weight: 950;
            color: #0f172a;
            letter-spacing: -1px;
            line-height: 1;
        }
        :is([data-theme-mode="dark"], .dark) .jf-circle-val { color: #f8fafc; }
        .jf-circle-val span { font-size: 0.55rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-top: 2px; }

        .jf-status-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .jf-status-msg {
            font-size: 1.05rem;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.01em;
        }
        :is([data-theme-mode="dark"], .dark) .jf-status-msg { color: #f8fafc; }

        .jf-status-desc {
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
        }

        .jf-progress-breakdown {
            background: #f8fafc;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            border: 1px solid #f1f5f9;
        }
        :is([data-theme-mode="dark"], .dark) .jf-progress-breakdown { background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.05); }

        .jf-breakdown-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.65rem;
            font-weight: 900;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        :is([data-theme-mode="dark"], .dark) .jf-breakdown-row { color: #94a3b8; }

        .jf-micro-progress-container { height: 6px; width: 100%; background: #e2e8f0; border-radius: 10px; overflow: hidden; }
        :is([data-theme-mode="dark"], .dark) .jf-micro-progress-container { background: rgba(255,255,255,0.05); }
        .jf-micro-progress-bar { height: 100%; background: #6366f1; border-radius: 10px; transition: none; }

        .jf-profile-cta {
            background: #6366f1;
            color: #ffffff;
            padding: 0.625rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.8125rem;
            font-weight: 900;
            text-align: center;
            transition: none;
            text-decoration: none !important;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
        }
        .jf-profile-cta:hover { background: #4f46e5; color: #ffffff; }

        /* ── Pipeline Card (Flat Minimal) ── */
        .jf-pipeline-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }
        :is([data-theme-mode="dark"], .dark) .jf-pipeline-card { background: rgba(15, 23, 42, 0.4); border-color: rgba(255,255,255,0.06); box-shadow: none; }

        .jf-pipeline-container {
            position: relative;
            display: flex;
            gap: 1rem;
            padding: 0.5rem 0;
            margin-top: 1rem;
        }

        .jf-tracking-card {
            flex: 1;
            min-width: 0;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 1rem;
            padding: 1.25rem;
            position: relative;
            z-index: 1;
            transition: none;
            text-decoration: none !important;
            display: flex;
            flex-direction: column;
        }
        :is([data-theme-mode="dark"], .dark) .jf-tracking-card { background: rgba(255,255,255,0.02) !important; border-color: rgba(255,255,255,0.05); }
        .jf-tracking-card:hover { border-color: #6366f1; background: #fff; }
        :is([data-theme-mode="dark"], .dark) .jf-tracking-card:hover { border-color: #6366f1; background: rgba(255,255,255,0.05) !important; }

        .jf-tracking-lbl {
            font-size: 0.65rem;
            font-weight: 900;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.25rem;
        }
        :is([data-theme-mode="dark"], .dark) .jf-tracking-lbl { color: #94a3b8; }

        .jf-tracking-val {
            font-size: 2rem;
            font-weight: 950;
            color: #0f172a;
            line-height: 1;
            margin-bottom: 0.5rem;
            letter-spacing: -0.05em;
        }
        :is([data-theme-mode="dark"], .dark) .jf-tracking-val { color: #f8fafc; }

        .jf-tracking-insight {
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        :is([data-theme-mode="dark"], .dark) .jf-tracking-insight { color: #94a3b8; }

        .jf-tracking-icon-box {
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
        :is([data-theme-mode="dark"], .dark) .jf-tracking-icon-box { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); }

        .applied .jf-tracking-icon-box { color: #6366f1; }
        .interview .jf-tracking-icon-box { color: #8b5cf6; }
        .offer .jf-tracking-icon-box { color: #f59e0b; }
        .hired .jf-tracking-icon-box { color: #10b981; }

        .applied .jf-tracking-insight { color: #6366f1; }
        .interview .jf-tracking-insight { color: #8b5cf6; }
        .offer .jf-tracking-insight { color: #f59e0b; }
        .hired .jf-tracking-insight { color: #10b981; }

        /* Subtle Gradient Glows */
        .jf-tracking-card::after {
            content: '';
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            background: radial-gradient(circle, var(--stage-color) 0%, transparent 70%);
            opacity: 0.04;
            transition: opacity 0.4s;
        }
        .jf-tracking-card:hover::after { opacity: 0.12; }

        /* Dark Mode for Pipeline */
        :is([data-theme-mode="dark"], .dark) .jf-tracking-card {
            background: rgba(15, 23, 42, 0.6) !important;
            border-color: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(8px);
        }
        :is([data-theme-mode="dark"], .dark) .jf-tracking-card:hover { border-color: var(--stage-color); }
        :is([data-theme-mode="dark"], .dark) .jf-tracking-val { color: #f8fafc; }
        :is([data-theme-mode="dark"], .dark) .jf-tracking-lbl { color: #94a3b8; }
        :is([data-theme-mode="dark"], .dark) .cd-card {
            background: var(--bg-card) !important;
            border-color: var(--border-card) !important;
        }

        :is([data-theme-mode="dark"], .dark) .cd-card:hover {
            background: #26272b !important;
            border-color: #3c4043 !important;
        }

        :is([data-theme-mode="dark"], .dark) .cd-section-header {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .cd-section-title {
            color: #f8fafc !important;
        }

        :is([data-theme-mode="dark"], .dark) .cd-ghost-btn {
            background: rgba(255, 255, 255, 0.05) !important;
            color: #f1f5f9 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        :is([data-theme-mode="dark"], .dark) .cd-ghost-btn:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-profile-card {
            background: var(--bg-card) !important;
            border-color: var(--border-card) !important;
            box-shadow: none;
        }

        :is([data-theme-mode="dark"], .dark) .jf-profile-card:hover {
            background: #26272b !important;
            border-color: #3c4043 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-status-msg {
            color: #f8fafc !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-progress-breakdown {
            background: #26272b !important;
            border-color: #303134 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-breakdown-row {
            color: #94a3b8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-micro-progress-container {
            background: rgba(255, 255, 255, 0.05) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-pipeline-card {
            background: var(--bg-card) !important;
            border-color: var(--border-card) !important;
            box-shadow: none;
        }

        :is([data-theme-mode="dark"], .dark) .jf-pipeline-card:hover {
            background: #26272b !important;
            border-color: #3c4043 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-tracking-card {
            background: #26272b !important;
            border-color: #303134 !important;
            backdrop-filter: blur(8px);
        }

        :is([data-theme-mode="dark"], .dark) .jf-tracking-card:hover {
            background: #2f3136 !important;
            border-color: #3c4043 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-tracking-val {
            color: #f8fafc !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-tracking-lbl {
            color: #94a3b8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-tracking-insight {
            color: #94a3b8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-tracking-icon-box {
            background: #303134 !important;
            border-color: #3c4043 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-tracking-card::after {
            opacity: 0.04;
        }

        :is([data-theme-mode="dark"], .dark) .jf-tracking-card:hover::after {
            opacity: 0.12;
        }

        :is([data-theme-mode="dark"], .dark) .jf-header-section { border-bottom-color: #303134 !important; background: var(--bg-card) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-desc { color: #94a3b8 !important; }

        /* ── Mobile Responsive Overrides ── */
        @media (max-width: 768px) {
            /* Hero: stack content and actions vertically */
            .jf-header-section {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 1.25rem;
                padding-bottom: 1.25rem;
            }

            .jf-header-side {
                position: static;
                width: 100%;
                min-width: 0;
            }

            .jf-header-clock {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
                white-space: normal;
            }

            /* Smaller title on mobile */
            .jf-header-title {
                font-size: 1.5rem !important;
            }

            /* Header actions become a compact row */
            .jf-header-actions {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: wrap !important;
                gap: 0.5rem !important;
                width: 100% !important;
            }

            .jf-header-actions a,
            .jf-header-actions button {
                flex: 1 !important;
                min-width: 0 !important;
                justify-content: center !important;
                padding: 0.5rem 0.75rem !important;
                font-size: 0.8rem !important;
            }

            /* Pipeline: 2×2 grid on mobile */
            .jf-pipeline-container {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 0.625rem !important;
            }

            .jf-tracking-card {
                padding: 0.875rem !important;
            }

            .jf-tracking-val {
                font-size: 1.5rem !important;
            }

            .jf-tracking-lbl {
                font-size: 0.6rem !important;
                letter-spacing: 0.05em !important;
            }

            .jf-tracking-insight {
                font-size: 0.65rem !important;
            }

            .jf-tracking-icon-box {
                width: 28px !important;
                height: 28px !important;
                font-size: 0.9rem !important;
                top: 0.75rem !important;
                right: 0.75rem !important;
            }

            /* Full width container */
            .max-w-7xl.mx-auto {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
        }
    </style>

    @php
        $completionItems = [
            'photo' => !empty($user->profile_photo_path),
            'title' => !empty($profile->job_title ?? $profile->title),
            'experience' => $profile->years_experience !== null,
            'skills' => !empty($profile->expertise_categories),
            'salary' => !empty($profile->expected_salary_min),
        ];
        $completedCount = count(array_filter($completionItems));
        $totalItems = count($completionItems);
        $percentage = round(($completedCount / $totalItems) * 100);
        $pColor = $percentage >= 100 ? 'text-green-600' : 'text-yellow-600';
        $pBgColor = $percentage >= 100 ? 'bg-green-600' : 'bg-yellow-600';

        // Completion logic
        $missingItems = array_filter($completionItems, fn($v) => !$v);
        $firstMissingKey = array_key_first($missingItems);
        $missingLabel = match($firstMissingKey ?? '') {
            'photo' => 'Profile Photo',
            'title' => 'Professional Title',
            'experience' => 'Experience',
            'skills' => 'Key Skills',
            'salary' => 'Expected Salary',
            default => 'Complete Profile'
        };


        $statusStyles = [
            'applied' => ['bg'=>'bg-blue-50 dark:bg-blue-900/30','text'=>'text-blue-700 dark:text-blue-300'],
            'submitted' => ['bg'=>'bg-blue-50 dark:bg-blue-900/30','text'=>'text-blue-700 dark:text-blue-300'],
            'reviewed' => ['bg'=>'bg-indigo-50 dark:bg-indigo-900/30','text'=>'text-indigo-700 dark:text-indigo-300'],
            'shortlisted' => ['bg'=>'bg-yellow-50 dark:bg-yellow-900/30','text'=>'text-yellow-700 dark:text-yellow-300'],
            'under_review' => ['bg'=>'bg-yellow-50 dark:bg-yellow-900/30','text'=>'text-yellow-700 dark:text-yellow-300'],
            'interview_scheduled' => ['bg'=>'bg-purple-50 dark:bg-purple-900/30','text'=>'text-purple-700 dark:text-purple-300'],
            'interviewed' => ['bg'=>'bg-purple-50 dark:bg-purple-900/30','text'=>'text-purple-700 dark:text-purple-300'],
            'offered' => ['bg'=>'bg-green-50 dark:bg-green-900/30','text'=>'text-green-700 dark:text-green-300'],
            'hired' => ['bg'=>'bg-emerald-50 dark:bg-emerald-900/30','text'=>'text-emerald-700 dark:text-emerald-300'],
            'accepted' => ['bg'=>'bg-emerald-50 dark:bg-emerald-900/30','text'=>'text-emerald-700 dark:text-emerald-300'],
            'rejected' => ['bg'=>'bg-red-50 dark:bg-red-900/30','text'=>'text-red-700 dark:text-red-300'],
            'declined' => ['bg'=>'bg-red-50 dark:bg-red-900/30','text'=>'text-red-700 dark:text-red-300'],
            'withdrawn' => ['bg'=>'bg-gray-100 dark:bg-gray-800','text'=>'text-gray-700 dark:text-gray-300'],
        ];

        $logoStyles = [
            'bg-indigo-600', 'bg-teal-600', 'bg-rose-600', 'bg-purple-600', 'bg-orange-600', 'bg-sky-600'
        ];
    @endphp

    @include('applicants.partials.candidate-styles')

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <x-modern-header :container="false" chip="Dashboard Overview">
            <x-slot name="titleContent"><strong>Welcome Back! 👋 {!! $profile->display_name ?? $user->name !!}.</strong></x-slot>
            <x-slot name="description">
                Ready to take the next step in your career? Here is your dashboard overview. Stay updated with your latest <b>Applications</b> and insights.
            </x-slot>
            <x-slot name="actions">
                @if($percentage < 100)
                    <a href="{{ route('applicant.profile.show') }}" class="inline-flex items-center gap-2 rounded-xl border bg-white border-slate-200 dark:bg-slate-800 dark:border-white/10 px-4 py-2.5 text-sm font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm">
                        <i class="ri-user-settings-line text-slate-400"></i>
                        <span>Profile {{ $percentage }}%</span>
                    </a>
                @endif

                <a href="{{ route('jobs') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 transition-all">
                    <i class="ri-search-line"></i>
                    <span>Find Jobs</span>
                </a>
            </x-slot>
        </x-modern-header>

        @if($profile->verification_status !== 'verified')
            <div style="margin-bottom: 1.5rem; padding: 1rem 1.25rem; background: #fef3c7; border: 1.5px solid #fcd34d; border-radius: 12px; display: flex; align-items: center; gap: 12px;">
                <i class="ri-alert-line" style="color: #d97706; font-size: 1.25rem;"></i>
                <div style="flex: 1;">
                    <p style="margin: 0; color: #92400e; font-weight: 600; font-size: 0.95rem;">
                        @if($profile->verification_status === 'rejected')
                            Account Rejected
                        @else
                            Account Pending Verification
                        @endif
                    </p>
                    <p style="margin: 0.25rem 0 0; color: #b45309; font-size: 0.875rem;">
                        @if($profile->verification_status === 'rejected')
                            Your account has been rejected. Please contact support for more information.
                        @else
                            Your account is currently pending. Please wait for approval from our team to apply for jobs and access full features.
                        @endif
                    </p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-12 gap-4 mb-4">

        {{-- Section 2: Important Call to Actions & Pipeline --}}
        @if($percentage < 100)

        <div class="col-span-12 lg:col-span-4" id="wt-profile">
            <div class="jf-profile-card cd-section">
                <div class="jf-profile-header-row">
                    <div class="jf-circle-progress">
                        <svg class="jf-circle-svg" width="86" height="86" viewBox="0 0 100 100">
                            <defs>
                                <linearGradient id="profileGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#316cf4" />
                                    <stop offset="100%" stop-color="#6366f1" />
                                </linearGradient>
                            </defs>
                            <circle class="jf-circle-bg" cx="50" cy="50" r="42" />
                            <circle class="jf-circle-bar" cx="50" cy="50" r="42"
                                    stroke-dasharray="{{ 2 * pi() * 42 }}"
                                    stroke-dashoffset="{{ 2 * pi() * 42 * (1 - $percentage / 100) }}" />
                        </svg>
                        <div class="jf-circle-val">
                            {{ $percentage }}%
                            <span>Done</span>
                        </div>
                    </div>
                    <div class="jf-status-content">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-0.5">Profile Strength</span>
                        <h3 class="jf-status-msg">{{ $percentage >= 100 ? 'Status: Optimized' : 'Status: Incomplete' }}</h3>
                        <p class="jf-status-desc text-balance">
                            {{ $percentage >= 100 ? 'Fully optimized for maximum visibility.' : 'Complete your profile to increase interview chances.' }}
                        </p>
                    </div>
                </div>

                <div class="jf-progress-breakdown">
                    <div class="jf-breakdown-row">
                        <span>Sections Progress</span>
                        <span>{{ $completedCount }}/{{ $totalItems }}</span>
                    </div>
                    <div class="jf-micro-progress-container">
                        <div class="jf-micro-progress-bar" style="width: {{ $percentage }}%"></div>
                    </div>
                    @if($percentage < 100)
                        <div class="flex items-center gap-2 mt-0.5">
                            <i class="ri-error-warning-fill text-rose-500 text-xs"></i>
                            <span class="text-[14px] font-bold text-slate-600">Next: {{ $missingLabel }}</span>
                        </div>
                    @endif
                </div>

                <a href="{{ $percentage >= 100 ? $viewProfileLink : route('applicant.profile.show') }}" class="jf-profile-cta group mt-auto">
                    <span>{{ $percentage >= 100 ? 'View Profile' : 'Complete Profile' }}</span>
                    <i class="ri-arrow-right-line transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-8" id="wt-pipeline">
        @else
        <div class="col-span-12" id="wt-pipeline">
        @endif
            <div class="jf-pipeline-card cd-section h-full">
                <div class="cd-section-header border-0 pb-0 mb-2 mt-1 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-0 text-center sm:text-left">
                    <span class="cd-section-title text-lg font-extrabold flex items-center justify-center sm:justify-start gap-2">
                        <i class="ri-direction-fill text-indigo-500"></i> Application Tracking Pipeline
                    </span>
                    <a href="{{ route('applicant.applications.index') }}" class="text-xs font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition-colors">
                        View All Applications <i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </div>

                <div class="jf-pipeline-container flex flex-wrap lg:flex-nowrap gap-4 w-full">

                    <!-- Applied -->
                    <a href="{{ route('applicant.applications.index') }}?status=applied" class="jf-tracking-card applied group">
                        <div class="jf-tracking-lbl">Applied</div>
                        <div class="jf-tracking-icon-box">
                            <i class="ri-plane-line"></i>
                        </div>
                        <div class="jf-tracking-val">{{ $appliedCount ?? 0 }}</div>
                        <div class="jf-tracking-insight">
                            <i class="ri-time-line"></i> {{ ($appliedCount ?? 0) > 0 ? 'Awaiting Review' : 'Find your role' }}
                        </div>
                    </a>

                    <!-- Interview -->
                    <a href="{{ route('applicant.interviews.index') }}" class="jf-tracking-card interview group">
                        <div class="jf-tracking-lbl">Interview</div>
                        <div class="jf-tracking-icon-box">
                            <i class="ri-discuss-line"></i>
                        </div>
                        <div class="jf-tracking-val">{{ $interviewingCount ?? 0 }}</div>
                        <div class="jf-tracking-insight">
                            <i class="ri-calendar-event-line"></i> {{ ($interviewingCount ?? 0) > 0 ? 'Active Pipeline' : 'No interviews yet' }}
                        </div>
                    </a>

                    <!-- Offer -->
                    <a href="{{ route('applicant.applications.index') }}?status=offered" class="jf-tracking-card offer group">
                        <div class="jf-tracking-lbl">Offer</div>
                        <div class="jf-tracking-icon-box">
                            <i class="ri-medal-2-line"></i>
                        </div>
                        <div class="jf-tracking-val">{{ $offeredCount ?? 0 }}</div>
                        <div class="jf-tracking-insight">
                            <i class="ri-loader-4-line"></i> {{ ($offeredCount ?? 0) > 0 ? 'Decision Pending' : 'Waiting for response' }}
                        </div>
                    </a>

                    <!-- Hired -->
                    <a href="{{ route('applicant.applications.index') }}?status=hired" class="jf-tracking-card hired group">
                        <div class="jf-tracking-lbl">Hired</div>
                        <div class="jf-tracking-icon-box">
                            <i class="ri-sparkling-fill"></i>
                        </div>
                        <div class="jf-tracking-val">{{ $hiredCount ?? 0 }}</div>
                        <div class="jf-tracking-insight">
                            <i class="ri-rocket-line"></i> {{ ($hiredCount ?? 0) > 0 ? 'Career Goal Met' : 'On track' }}
                        </div>
                    </a>

                </div>

                <!-- Tracking Explanation Tip (Ultra-Compact) -->
                <div class="mt-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl p-2.5 flex items-center gap-3 border border-slate-100 dark:border-slate-700/50">
                    <div class="w-7 h-7 rounded-lg bg-white dark:bg-slate-800 text-indigo-600 flex items-center justify-center flex-shrink-0 shadow-sm text-xs">
                        <i class="ri-information-line"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[14px] text-slate-600 dark:text-slate-400 leading-tight">
                            Monitor your <b>active job hunt</b>. This pipeline follows your journey from application to final hire.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: Left Column (Main Content) --}}
        <div class="col-span-12 lg:col-span-8 flex flex-col gap-4 mt-3">

            {{-- Recommended Jobs --}}
            <div class="jf-pipeline-card cd-section p-5" id="wt-recommended">
                <div class="cd-section-header border-0 pb-0 mb-2 mt-1">
                    <span class="cd-section-title text-lg font-extrabold flex items-center gap-2">
                        <i class="ri-focus-3-line text-indigo-500"></i> Recommended Jobs
                    </span>
                    <div class="flex items-center gap-2">
                        <button type="button" class="w-7 h-7 flex items-center justify-center rounded-full border border-[var(--border-card)] bg-[var(--bg-card)] hover:bg-[var(--bg-hover)] transition-colors text-[var(--text-sub)] hover:text-[var(--cd-accent)]" id="prevBtn"><i class="ri-arrow-left-s-line"></i></button>
                        <button type="button" class="w-7 h-7 flex items-center justify-center rounded-full border border-[var(--border-card)] bg-[var(--bg-card)] hover:bg-[var(--bg-hover)] transition-colors text-[var(--text-sub)] hover:text-[var(--cd-accent)]" id="nextBtn"><i class="ri-arrow-right-s-line"></i></button>
                    </div>
                </div>

                @if($recommendedJobs->isEmpty())
                    <div class="text-center py-6">
                        <i class="ri-briefcase-line text-4xl mb-3 text-gray-300 dark:text-gray-600 block"></i>
                        <p class="text-sm mb-4" style="color: var(--text-sub);">No tailored recommendations at the moment.</p>
                        <a href="{{ route('jobs') }}" class="cd-ghost-btn px-5 py-2 rounded-lg text-sm font-semibold inline-flex items-center gap-2">
                            Browse general jobs
                        </a>
                    </div>
                @else
                    <div class="swiper swiper-container" id="recommendedSwiper">
                        <div class="swiper-wrapper">
                            @foreach($recommendedJobs as $idx => $job)
                                @php
                                    $lbg = $logoStyles[$idx % count($logoStyles)];
                                    $company = $job->company;
                                    $initials = collect(explode(' ', $company->name ?? 'C'))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->join('');
                                    $isSaved = in_array($job->id, $savedJobIds ?? []);
                                @endphp
                                <div class="swiper-slide">
                                    <div class="h-full flex flex-col p-4 rounded-xl border border-[var(--border-card)] bg-white dark:bg-gray-800/50 hover:shadow-md hover:border-[rgba(var(--cd-accent-rgb),0.3)] transition-all">

                                        {{-- Header row --}}
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-sm overflow-hidden {{ $lbg }}">
                                                @if($company?->logo_url)
                                                    <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ $initials }}
                                                @endif
                                            </div>
                                            <div class="flex items-center">
                                                <form method="POST" action="{{ route('jobs.save', $job->slug) }}" class="inline job-save-form">
                                                    @csrf
                                                    <button type="submit"
                                                            class="job-save-toggle w-8 h-8 rounded-lg border-0 flex items-center justify-center transition-all {{ $isSaved ? 'bg-red-500 text-white shadow-sm' : 'bg-transparent text-[var(--text-sub)] hover:bg-[var(--bg-hover)]' }}"
                                                            data-saved="{{ $isSaved ? '1' : '0' }}"
                                                            aria-label="{{ $isSaved ? 'Saved job' : 'Save job' }}">
                                                        <i class="{{ $isSaved ? 'ri-heart-fill' : 'ri-heart-line' }} text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        {{-- Job Information --}}
                                        <div class="mb-3">
                                            <a href="{{ route('jobs.show', $job->slug) }}" class="font-bold text-[var(--text-main)] hover:text-[var(--cd-accent)] transition-colors line-clamp-1 leading-tight mb-1">{{ $job->title }}</a>
                                            <div class="text-xs font-medium text-[var(--text-sub)] mb-2 line-clamp-1 flex items-center gap-1.5">
                                                {{ $company?->name ?? 'Company' }}
                                                @if($company?->verified)
                                                    <i class="ri-verified-badge-fill text-blue-500" title="Verified Company"></i>
                                                @endif
                                            </div>

                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-[var(--text-sub)]">
                                                <span class="flex items-center"><i class="ri-map-pin-line mr-1"></i> {{ $job->location ?? 'Remote' }}</span>
                                                <span class="flex items-center"><i class="ri-time-line mr-1"></i> {{ $job->posted_at ? $job->posted_at->diffForHumans() : 'Recent' }}</span>
                                            </div>

                                            {{-- Company Rating --}}
                                            @php
                                                $avgRating = $company?->rating ?? 0;
                                                $ratingCount = $company?->rating_count ?? 0;
                                            @endphp
                                            @if($company?->id)
                                            <div style="display:flex;align-items:center;gap:6px;margin-top:6px;">
                                                <span style="font-size:0.75rem;color:var(--text-sub)">Ratings:</span>
                                                <div class="company-rating-stars" style="display:inline-flex;gap:1px;cursor:{{ auth()->check() ? 'pointer' : 'default' }}"
                                                    data-company-id="{{ $company->id }}"
                                                    data-rating="{{ $avgRating }}"
                                                    data-rate-url="{{ route('companies.rate', ['company' => $company->id]) }}"
                                                    data-can-rate="{{ auth()->check() ? '1' : '0' }}">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= floor($avgRating))
                                                            <span class="star-btn" data-star="{{ $i }}" style="color:#f59e0b;font-size:0.875rem"><i class="bi bi-star-fill"></i></span>
                                                        @elseif($i - 0.5 <= $avgRating)
                                                            <span class="star-btn" data-star="{{ $i }}" style="color:#f59e0b;font-size:0.875rem"><i class="bi bi-star-half"></i></span>
                                                        @else
                                                            <span class="star-btn" data-star="{{ $i }}" style="color:#f59e0b;font-size:0.875rem"><i class="bi bi-star"></i></span>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="company-rating-count" style="font-size:0.75rem;color:var(--text-sub)">(<span class="rating-count-value">{{ $ratingCount }}</span>)</span>
                                            </div>
                                            @endif
                                        </div>

                                        {{-- Tags --}}
                                        <div class="flex flex-wrap gap-1.5 mb-4">
                                            @if($job->employment_type)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold cd-ghost-btn">{{ Str::headline($job->employment_type) }}</span>
                                            @endif
                                            @if($job->remote_type)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800">{{ Str::headline($job->remote_type) }}</span>
                                            @endif
                                        </div>

                                        {{-- Action --}}
                                        <div class="mt-auto pt-4 border-t border-[var(--border-card)]">
                                            <a href="{{ route('jobs.show', $job->slug) }}" class="cd-accent-btn w-full py-2 rounded-lg flex items-center justify-center gap-2 text-xs font-semibold">
                                                View & Apply <i class="ri-arrow-right-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="swiper-pagination" id="recommendedSwiperPagination"></div>
                @endif
            </div>

            {{-- Recent Applications --}}
            <div class="jf-pipeline-card cd-section p-5" id="wt-applications">
                <div class="cd-section-header border-0 pb-0 mb-2 mt-1">
                    <span class="cd-section-title text-lg font-extrabold flex items-center gap-2">
                        <i class="ri-history-fill text-indigo-500"></i> Recent Applications
                    </span>
                    <a href="{{ route('applicant.applications.index') }}" class="text-xs font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 transition-colors">View All <i class="ri-arrow-right-s-line ms-1"></i></a>
                </div>

                @if($applications->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 px-4 text-center">
                        {{-- Illustrated character --}}
                        <div class="mb-4" style="width:110px;height:90px">
                            <svg viewBox="0 0 110 90" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:100%">
                                <!-- Shadow -->
                                <ellipse cx="55" cy="85" rx="28" ry="5" fill="#6366f1" opacity="0.1"/>
                                <!-- Screen body -->
                                <rect x="14" y="18" width="68" height="50" rx="8" fill="#eef2ff" stroke="#6366f1" stroke-width="2"/>
                                <!-- Screen header bar -->
                                <rect x="14" y="18" width="68" height="14" rx="8" fill="#6366f1"/>
                                <rect x="14" y="24" width="68" height="8" fill="#6366f1"/>
                                <!-- Header dots -->
                                <circle cx="24" cy="25" r="2.5" fill="white" opacity="0.6"/>
                                <circle cx="32" cy="25" r="2.5" fill="white" opacity="0.6"/>
                                <circle cx="40" cy="25" r="2.5" fill="white" opacity="0.6"/>
                                <!-- Sad face -->
                                <circle cx="44" cy="51" r="3" fill="#6366f1" opacity="0.7"/>
                                <circle cx="62" cy="51" r="3" fill="#6366f1" opacity="0.7"/>
                                <!-- Sad mouth -->
                                <path d="M42 62 Q53 56 64 62" stroke="#6366f1" stroke-width="2.5" stroke-linecap="round" fill="none" opacity="0.7"/>
                                <!-- Stand -->
                                <rect x="47" y="68" width="12" height="8" rx="2" fill="#c7d2fe"/>
                                <rect x="38" y="75" width="30" height="4" rx="2" fill="#a5b4fc"/>
                                <!-- Question bubble -->
                                <circle cx="80" cy="16" r="12" fill="#6366f1"/>
                                <text x="80" y="21" text-anchor="middle" font-size="14" font-weight="bold" fill="white" font-family="sans-serif">?</text>
                                <!-- Bubble tail -->
                                <polygon points="72,24 68,30 76,26" fill="#6366f1"/>
                                <!-- Arms -->
                                <path d="M14 45 Q6 40 8 52" stroke="#a5b4fc" stroke-width="3" stroke-linecap="round" fill="none"/>
                                <path d="M82 45 Q90 40 88 52" stroke="#a5b4fc" stroke-width="3" stroke-linecap="round" fill="none"/>
                            </svg>
                        </div>
                        <h4 class="font-bold text-base mb-1" style="color:var(--text-main)">No Applications Yet</h4>
                        <p class="text-sm mb-5 max-w-[220px]" style="color:var(--text-sub)">Search for open roles and submit your first application today!</p>
                        <a href="{{ route('jobs') }}" class="cd-accent-btn px-5 py-2.5 rounded-xl text-sm font-bold inline-flex items-center gap-2 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all">
                            <i class="ri-search-line"></i> Search Jobs
                        </a>
                    </div>
                @else
                    <div class="flex flex-col gap-3 mt-1">
                        @foreach($applications->take(4) as $app)
                            @php
                                $st = $statusStyles[$app->status] ?? ['bg'=>'bg-gray-100 dark:bg-gray-800','text'=>'text-gray-700 dark:text-gray-300'];
                                $jp = $app->jobPosting;
                                $appCompany = $jp?->company;
                                $appLbg = $logoStyles[$loop->index % count($logoStyles)];
                                $appInitials = collect(explode(' ', $appCompany->name ?? 'C'))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->join('');

                                $accentColor = match($app->status) {
                                    'applied', 'submitted'               => '#6366f1',
                                    'under_review'                       => '#3b82f6',
                                    'interview_scheduled','interviewed'   => '#8b5cf6',
                                    'offered'                            => '#f59e0b',
                                    'hired', 'accepted'                  => '#10b981',
                                    'declined','rejected','not_selected'  => '#ef4444',
                                    default                              => '#94a3b8',
                                };
                                $badgeBg = match($app->status) {
                                    'applied', 'submitted'               => 'rgba(99,102,241,0.1)',
                                    'under_review'                       => 'rgba(59,130,246,0.1)',
                                    'interview_scheduled','interviewed'   => 'rgba(139,92,246,0.1)',
                                    'offered'                            => 'rgba(245,158,11,0.12)',
                                    'hired', 'accepted'                  => 'rgba(16,185,129,0.1)',
                                    'declined','rejected','not_selected'  => 'rgba(239,68,68,0.1)',
                                    default                              => 'rgba(148,163,184,0.1)',
                                };
                            @endphp

                            @if($jp)
                            <a href="{{ route('jobs.show', $jp->slug) }}"
                               class="group flex items-start gap-3 p-3.5 rounded-2xl border transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md"
                               style="border-color:var(--border-card);background:var(--bg-card)">

                                {{-- Logo --}}
                                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-[14px] flex-shrink-0 flex items-center justify-center text-white font-bold text-sm shadow-sm overflow-hidden {{ $appLbg }}">
                                    @if($appCompany?->logo_url)
                                        <img src="{{ $appCompany->logo_url }}" alt="{{ $appCompany->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ $appInitials }}
                                    @endif
                                </div>

                                {{-- Unified Content Area --}}
                                <div class="flex-1 min-w-0">
                                    {{-- Header: Title + Status --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                        <div class="min-w-0">
                                            <div class="font-bold text-sm truncate group-hover:text-[var(--cd-accent)] transition-colors" style="color:var(--text-main)">
                                                {{ $jp->title }}
                                            </div>
                                            <div class="flex items-center gap-1 text-[11px] mt-0.5" style="color:var(--text-sub)">
                                                <span class="font-medium truncate">{{ $appCompany?->name ?? 'Company' }}</span>
                                                @if($appCompany?->verified)
                                                    <i class="ri-verified-badge-fill text-blue-500 flex-shrink-0" title="Verified"></i>
                                                @endif
                                                <span class="mx-0.5 opacity-30">•</span>
                                                <i class="ri-map-pin-2-line flex-shrink-0"></i>
                                                <span class="truncate">{{ $jp->location ?? 'Remote' }}</span>
                                            </div>
                                        </div>

                                        {{-- Status badge --}}
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-xl text-[9px] font-bold whitespace-nowrap"
                                                  style="background:{{ $badgeBg }};color:{{ $accentColor }};border:1px solid {{ $accentColor }}22">
                                                <span class="w-1 h-1 rounded-full" style="background:{{ $accentColor }}"></span>
                                                {{ Str::headline($app->status) }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Footer: Tags + Time --}}
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if($jp->employment_type)
                                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-lg border border-slate-100 dark:border-slate-800" style="background:var(--bg-hover);color:var(--text-sub)">{{ Str::headline($jp->employment_type) }}</span>
                                        @endif
                                        @if($jp->remote_type)
                                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-lg border border-slate-100 dark:border-slate-800" style="background:var(--bg-hover);color:var(--text-sub)">{{ Str::headline($jp->remote_type) }}</span>
                                        @endif
                                        <span class="text-[9px] font-medium flex items-center gap-1 ml-auto" style="color:var(--text-sub)">
                                            <i class="ri-time-line"></i>{{ $app->applied_at ? $app->applied_at->diffForHumans() : 'Recently' }}
                                        </span>
                                    </div>
                                </div>
                            </a>

                            @else
                            <div class="flex items-center gap-3 p-3.5 rounded-2xl border border-amber-200 dark:border-amber-900/50" style="background:rgba(245,158,11,0.04)">
                                <div class="w-12 h-12 rounded-[14px] flex-shrink-0 flex items-center justify-center text-white text-lg shadow-sm bg-gradient-to-br from-amber-400 to-orange-500">
                                    <i class="ri-error-warning-fill"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-sm mb-0.5" style="color:var(--text-main)">Job Post Unavailable</div>
                                    <div class="text-xs" style="color:var(--text-sub)">{{ $appCompany?->name ?? 'Unknown Company' }}</div>
                                    <div class="text-[10px] mt-1 text-amber-600 dark:text-amber-400">This post has expired or been removed</div>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-bold whitespace-nowrap flex-shrink-0"
                                      style="background:rgba(245,158,11,0.1);color:#d97706;border:1px solid rgba(245,158,11,0.2)">
                                    {{ Str::headline($app->status) }}
                                </span>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- Section 4: Right Column (Sidebar) --}}
        <div class="col-span-12 lg:col-span-4 flex flex-col gap-5 mt-3">

            {{-- Quick Stats --}}
            <div class="jf-pipeline-card cd-section p-5" id="wt-stats">
                <div class="cd-section-header">
                    <span class="cd-section-title"><i class="ri-pie-chart-fill"></i> Quick Stats</span>
                </div>
                <div class="flex flex-col items-center justify-center py-4 px-2">
                    <div class="relative w-[180px] h-[180px] mb-6">
                        <canvas id="quickStatsChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-3xl font-black text-slate-800 dark:text-white leading-none" id="chartCenterValue">{{ $applications->count() + ($savedJobs ?? 0) + ($underReviewCount ?? 0) + ($declinedCount ?? 0) }}</span>
                            <span class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mt-1" id="chartCenterLabel">Activities</span>
                        </div>
                    </div>

                    {{-- Custom Legend --}}
                    <div class="grid grid-cols-2 gap-x-8 gap-y-4 w-full px-2">
                        <div class="flex items-center justify-between group cursor-pointer" onclick="updateChartFocus(0)">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full flex-shrink-0 shadow-sm" style="background-color: #64748b;"></div>
                                <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 group-hover:text-[var(--cd-accent)] transition-colors">Applied</span>
                            </div>
                            <span class="text-sm font-black text-slate-400">{{ $applications->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between group cursor-pointer" onclick="updateChartFocus(1)">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full flex-shrink-0 shadow-sm" style="background-color: #ef4444;"></div>
                                <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 group-hover:text-red-500 transition-colors">Declined</span>
                            </div>
                            <span class="text-sm font-black text-slate-400">{{ $declinedCount ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between group cursor-pointer" onclick="updateChartFocus(2)">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full flex-shrink-0 shadow-sm" style="background-color: #f59e0b;"></div>
                                <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 group-hover:text-amber-500 transition-colors">In Review</span>
                            </div>
                            <span class="text-sm font-black text-slate-400">{{ $underReviewCount ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between group cursor-pointer" onclick="updateChartFocus(3)">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full flex-shrink-0 shadow-sm" style="background-color: #10b981;"></div>
                                <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 group-hover:text-emerald-500 transition-colors">Saved</span>
                            </div>
                            <span class="text-sm font-black text-slate-400">{{ $savedJobs ?? 0 }}</span>
                        </div>
                    </div>
                </div>



                <a href="{{ route('jobs') }}" class="p-4 rounded-xl border border-[var(--border-card)] flex items-center justify-between group cursor-pointer hover:-translate-y-1 hover:shadow-md transition-all duration-300" style="background-color: var(--bg-hover);">
                    <div>
                        <h6 class="font-semibold text-md mb-1 transition-colors" style="color: var(--text-main);">More opportunities</h6>
                        <p class="text-sm text-balance transition-colors" style="color: var(--text-sub);">Explore newly posted open positions.</p>
                    </div>
                    <div class="w-10 h-10 rounded-full transition-all duration-300 flex items-center justify-center flex-shrink-0 group-hover:scale-110" style="background-color: var(--border-card); color: var(--text-main);">
                        <i class="ri-arrow-right-line text-lg group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </a>
            </div>

            {{-- Skill Insights --}}
            <div class="jf-pipeline-card cd-section p-5" id="wt-skills">
                <div class="cd-section-header">
                    <span class="cd-section-title"><i class="ri-lightbulb-flash-fill"></i> Skill Insights</span>
                </div>
                <div class="flex flex-col gap-4 mb-4">
                    @php
                        $trendingSkills = [
                            ['name'=>'Data Analysis', 'growth'=>'+20%', 'color'=>'text-blue-600', 'bg'=>'bg-blue-600', 'fill'=>'bg-blue-50 dark:bg-blue-900/30', 'pct'=>78, 'icon'=>'ri-bar-chart-2-line'],
                            ['name'=>'Communication', 'growth'=>'+15%', 'color'=>'text-indigo-600', 'bg'=>'bg-indigo-600', 'fill'=>'bg-indigo-50 dark:bg-indigo-900/30', 'pct'=>65, 'icon'=>'ri-chat-voice-line'],
                            ['name'=>'Project Management', 'growth'=>'+10%', 'color'=>'text-emerald-600', 'bg'=>'bg-emerald-600', 'fill'=>'bg-emerald-50 dark:bg-emerald-900/30', 'pct'=>55, 'icon'=>'ri-clipboard-line'],
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
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-lg {{ $skill['color'] }} {{ $skill['fill'] }} flex-shrink-0">
                                <i class="{{ $skill['icon'] }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-sm font-semibold truncate text-[var(--text-main)]">{{ ucfirst($skill['name']) }}</span>
                                    <span class="text-[10px] font-bold {{ $skill['color'] }}">↑ {{ $skill['growth'] }}</span>
                                </div>
                                <div class="h-1.5 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $skill['bg'] }}" style="width: {{ $skill['pct'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('jobs') }}?q=skills" class="cd-ghost-btn w-full py-2 rounded text-sm font-semibold flex items-center justify-center gap-2">
                    <i class="ri-book-read-line"></i> Upskill & Explore
                </a>
            </div>

        </div>
    </div>
    </div> {{-- Close max-w-7xl mx-auto --}}

    @include('applicants.partials.walkthrough', [
        'wtKey' => 'dashboard',
        'wtSteps' => [
            ['target' => 'wt-hero', 'icon' => 'ri-home-smile-line', 'title' => 'Welcome to Your Dashboard', 'body' => 'This is your command center. From here you can search for jobs, view your applications, and complete your profile.', 'position' => 'bottom'],
            ['target' => 'wt-profile', 'icon' => 'ri-user-3-fill', 'title' => 'Profile Status', 'body' => 'A complete profile significantly boosts your visibility to recruiters. Keep an eye on your completion progress here.', 'position' => 'bottom'],
            ['target' => 'wt-pipeline', 'icon' => 'ri-bar-chart-fill', 'title' => 'Application Pipeline', 'body' => 'Track your overall job-hunting momentum at a glance from Applied to Hired.', 'position' => 'bottom'],
            ['target' => 'wt-applications', 'icon' => 'ri-history-fill', 'title' => 'Recent Applications', 'body' => 'Your latest job applications appear here with real-time status updates.', 'position' => 'right'],
            ['target' => 'wt-recommended', 'icon' => 'ri-focus-3-line', 'title' => 'Recommended Jobs', 'body' => 'Hand-picked roles tailored for you based on your profile and skills.', 'position' => 'top'],
            ['target' => 'wt-stats', 'icon' => 'ri-pie-chart-fill', 'title' => 'Quick Stats', 'body' => 'Your job search analytics summarizing your saved jobs and overall interactions.', 'position' => 'left'],
            ['target' => 'wt-skills', 'icon' => 'ri-lightbulb-flash-fill', 'title' => 'Skill Insights', 'body' => 'See which skills are trending to guide your upskilling journey.', 'position' => 'left'],
        ]
    ])

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiperConfig = {
                slidesPerView: 1,
                spaceBetween: 16,
                grabCursor: true,
                autoplay: { delay: 4000, disableOnInteraction: false, pauseOnMouseEnter: true },
                navigation: { nextEl: '#nextBtn', prevEl: '#prevBtn' },
                pagination: { el: '#recommendedSwiperPagination', clickable: true, dynamicBullets: true },
                breakpoints: {
                    640: { slidesPerView: 2, spaceBetween: 16 },
                    1024: { slidesPerView: 2, spaceBetween: 20 },
                    1280: { slidesPerView: 2, spaceBetween: 20 },
                },
            };

            if(document.getElementById('recommendedSwiper')){
                new Swiper('#recommendedSwiper', swiperConfig);
            }

            // Job Save Toggle Script
            function setSavedStyles(btn, saved) {
                const icon = btn.querySelector('i');
                if (saved) {
                    btn.dataset.saved = '1';
                    btn.classList.replace('bg-white', 'bg-red-500');
                    btn.classList.replace('border-gray-200', 'border-red-500');
                    btn.classList.replace('text-red-500', 'text-white');
                    btn.classList.replace('dark:bg-gray-800', 'dark:bg-red-600');
                    btn.classList.replace('dark:border-gray-700', 'dark:border-red-600');
                    if (icon) icon.className = 'ri-heart-fill text-sm';
                } else {
                    btn.dataset.saved = '0';
                    btn.classList.replace('bg-red-500', 'bg-white');
                    btn.classList.replace('border-red-500', 'border-gray-200');
                    btn.classList.replace('text-white', 'text-red-500');
                    btn.classList.replace('dark:bg-red-600', 'dark:bg-gray-800');
                    btn.classList.replace('dark:border-red-600', 'dark:border-gray-700');
                    if (icon) icon.className = 'ri-heart-line text-sm';
                }
            }

            document.querySelectorAll('form.job-save-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = form.querySelector('.job-save-toggle');
                    if (!btn) { form.submit(); return; }

                    const url = form.getAttribute('action');
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    btn.disabled = true;

                    fetch(url, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => { if(!res.ok) throw new Error('Failed'); return res.json(); })
                    .then(data => {
                        if (data.status === 'saved') {
                            setSavedStyles(btn, true);
                            if (window.syncQuickStats) window.syncQuickStats(3, 1);
                        } else if (data.status === 'removed') {
                            setSavedStyles(btn, false);
                            if (window.syncQuickStats) window.syncQuickStats(3, -1);
                        }
                    })
                    .catch(err => console.error(err))
                    .finally(() => btn.disabled = false);
                });
            });

            // Quick Stats Chart
            const quickStatsCtx = document.getElementById('quickStatsChart')?.getContext('2d');
            if (quickStatsCtx) {
                // Function to create vertical gradients
                const createGradient = (color1, color2) => {
                    const gr = quickStatsCtx.createLinearGradient(0, 0, 0, 180);
                    gr.addColorStop(0, color1);
                    gr.addColorStop(1, color2);
                    return gr;
                };

                const appliedGrad = createGradient('#94a3b8', '#64748b');
                const declinedGrad = createGradient('#f87171', '#dc2626');
                const reviewGrad = createGradient('#fbbf24', '#d97706');
                const savedGrad = createGradient('#34d399', '#059669');

                window.chartData = [
                    {{ $applications->count() }},
                    {{ $declinedCount ?? 0 }},
                    {{ $underReviewCount ?? 0 }},
                    {{ $savedJobs ?? 0 }}
                ];
                window.chartLabels = ['Applied', 'Declined', 'In Review', 'Saved'];
                window.chartColors = ['#94a3b8', '#ef4444', '#f59e0b', '#10b981'];

                window.quickStatsChart = new Chart(quickStatsCtx, {
                    type: 'doughnut',
                    data: {
                        labels: window.chartLabels,
                        datasets: [{
                            data: window.chartData,
                            backgroundColor: [appliedGrad, declinedGrad, reviewGrad, savedGrad],
                            hoverBackgroundColor: [appliedGrad, declinedGrad, reviewGrad, savedGrad],
                            borderWidth: 0,
                            borderRadius: 10,
                            spacing: 4,
                            hoverOffset: 12
                        }]
                    },
                    options: {
                        cutout: '78%',
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 2000,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: false }
                        },
                        onHover: (event, elements) => {
                            const centerValue = document.getElementById('chartCenterValue');
                            const centerLabel = document.getElementById('chartCenterLabel');
                            if (elements.length > 0) {
                                const index = elements[0].index;
                                centerValue.innerText = window.chartData[index];
                                centerLabel.innerText = window.chartLabels[index];
                                centerValue.style.color = window.chartColors[index];
                            } else {
                                centerValue.innerText = window.chartData.reduce((a, b) => a + b, 0);
                                centerLabel.innerText = 'Activities';
                                centerValue.style.color = '';
                            }
                        }
                    }
                });

                window.syncQuickStats = (index, delta) => {
                    if (!window.quickStatsChart) return;
                    window.chartData[index] += delta;
                    window.quickStatsChart.data.datasets[0].data = window.chartData;
                    window.quickStatsChart.update();

                    // Update center total
                    const centerValue = document.getElementById('chartCenterValue');
                    if (centerValue) {
                        centerValue.innerText = window.chartData.reduce((a, b) => a + b, 0);
                    }

                    // Update legend count
                    const legendItems = document.querySelectorAll('.flex.items-center.justify-between.group span.text-xs.font-black.text-slate-400');
                    if (legendItems[index]) {
                        legendItems[index].innerText = window.chartData[index];
                    }
                };

                window.updateChartFocus = (index) => {
                    const centerValue = document.getElementById('chartCenterValue');
                    const centerLabel = document.getElementById('chartCenterLabel');
                    centerValue.innerText = window.chartData[index];
                    centerLabel.innerText = window.chartLabels[index];
                    centerValue.style.color = window.chartColors[index];

                    window.quickStatsChart.setActiveElements([{ datasetIndex: 0, index: index }]);
                    window.quickStatsChart.update();
                };

                // Periodic Sync to keep all stats updated without refresh
                window.syncAllStats = () => {
                    fetch('{{ route('applicant.stats') }}', {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => res.json())
                    .then(data => {
                        window.chartData = [
                            data.applied,
                            data.declined,
                            data.under_review,
                            data.saved
                        ];
                        if (window.quickStatsChart) {
                            window.quickStatsChart.data.datasets[0].data = window.chartData;
                            window.quickStatsChart.update();
                        }

                        // Update legend counts
                        const legendItems = document.querySelectorAll('.flex.items-center.justify-between.group span.text-xs.font-black.text-slate-400');
                        if (legendItems.length >= 4) {
                            legendItems[0].innerText = data.applied;
                            legendItems[1].innerText = data.declined;
                            legendItems[2].innerText = data.under_review;
                            legendItems[3].innerText = data.saved;
                        }

                        // Update center total (only if not hovering)
                        const centerValue = document.getElementById('chartCenterValue');
                        if (centerValue && (!window.quickStatsChart || !window.quickStatsChart.getActiveElements().length)) {
                            centerValue.innerText = window.chartData.reduce((a, b) => a + b, 0);
                        }
                    })
                    .catch(err => console.error('Stats sync failed:', err));
                };

                // Initial poll and set interval (30s)
                setInterval(window.syncAllStats, 30000);
            }
        });
    </script>
</x-app-layout>
