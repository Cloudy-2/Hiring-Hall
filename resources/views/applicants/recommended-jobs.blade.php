<x-app-layout page-title="Recommended Jobs">

    <x-slot name="url_1">{"link": "/applicant/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Recommended Jobs</x-slot>



    @include('applicants.partials.candidate-styles')

    {{-- ═══ Page-specific Styles ═══ --}}
    <style>
        /* ── Modern Elite Design Tokens ── */
        :root {
            --jf-primary: #6366f1;
            --jf-primary-hover: #4f46e5;
            --jf-bg-card: #ffffff;
            --jf-border: #e2e8f0;
            --jf-text-main: #1e293b;
            --jf-text-sub: #64748b;
        }

        :is([data-theme-mode="dark"], .dark) {
            --jf-bg-card: #1e293b;
            --jf-border: rgba(255, 255, 255, 0.08);
            --jf-text-main: #f8fafc;
            --jf-text-sub: #94a3b8;
        }

        :is([data-theme-mode="dark"], .dark) {
            --jf-bg-card: #1e293b;
            --jf-border: rgba(255, 255, 255, 0.08);
            --jf-text-main: #f8fafc;
            --jf-text-sub: #94a3b8;
        }

        /* ── Premium Search Pill ── */
        .jf-search-pill-container {
            padding: 0.5rem 0 1.5rem 0;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .jf-search-pill {
            display: flex;
            align-items: center;
            height: 64px;
            background: var(--jf-bg-card);
            border: 1.5px solid var(--jf-border);
            border-radius: 32px;
            padding: 0 0.5rem 0 1.25rem;
            box-shadow: 0 10px 30px -8px rgba(0,0,0,0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
        }
        .jf-search-pill:focus-within {
            border-color: var(--jf-primary);
            box-shadow: 0 15px 35px -10px rgba(99,102,241,0.12);
        }

        .jf-pill-field { flex: 1; display: flex; align-items: center; gap: 0.75rem; padding: 0 0.75rem; min-width: 0; position: relative; }
        .jf-pill-field i { font-size: 1.1rem; color: var(--jf-text-sub); flex-shrink: 0; }
        .jf-pill-input { width: 100%; border: none !important; background: transparent !important; font-size: 0.875rem; font-weight: 500; color: var(--jf-text-main); outline: none !important; box-shadow: none !important; cursor: inherit; }
        .jf-pill-input::placeholder { color: var(--jf-text-sub); font-weight: 400; }

        .jf-pill-divider { width: 1px; height: 24px; background: var(--jf-border); flex-shrink: 0; }

        .jf-pill-submit {
            height: 48px;
            padding: 0 1.25rem;
            border-radius: 24px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            font-size: 0.813rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            box-shadow: 0 4px 12px -2px rgba(79,70,229,0.3);
            white-space: nowrap;
        }
        .jf-pill-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 18px -4px rgba(79,70,229,0.4); }

        /* Custom Pill Dropdowns */
        .jf-pill-select-wrapper { position: relative; width: 100%; cursor: pointer; user-select: none; }
        .jf-pill-select-display { display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; font-size: 0.813rem; font-weight: 500; color: var(--jf-text-main); padding: 0.5rem 0.75rem; width: 100%; border-radius: 12px; transition: all 0.2s; }
        .jf-pill-select-wrapper:hover .jf-pill-select-display { background: rgba(99, 102, 241, 0.05); color: var(--jf-primary); }
        .jf-pill-select-wrapper.active .jf-pill-select-display { background: var(--jf-primary) !important; color: #ffffff !important; }
        .jf-pill-select-wrapper.active .jf-pill-select-display i { color: #ffffff !important; }

        .jf-pill-select-display i.ri-arrow-down-s-line { font-size: 1rem; color: var(--jf-text-sub); transition: transform 0.2s; }
        .jf-pill-select-wrapper.active i.ri-arrow-down-s-line { transform: rotate(180deg); }

        .jf-pill-select-options {
            position: absolute;
            top: 100%;
            left: -12px;
            min-width: calc(100% + 24px);
            background: var(--jf-bg-card);
            border: 1px solid var(--jf-border);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 4px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.2s, transform 0.2s;
        }
        .jf-pill-select-options::before { content: ''; position: absolute; top: -15px; left: 0; right: 0; height: 15px; background: transparent; }
        .jf-pill-select-wrapper.active .jf-pill-select-options { display: block; opacity: 1; transform: translateY(0); }

        .jf-pill-option {
            padding: 0.625rem 0.75rem;
            border-radius: 8px;
            font-size: 0.813rem;
            font-weight: 600;
            color: var(--jf-text-sub);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            white-space: nowrap;
        }
        .jf-pill-option.header { color: var(--jf-text-sub); font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding-top: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--jf-border); margin-bottom: 0.25rem; cursor: default; justify-content: center; }
        .jf-pill-option:hover { background: var(--jf-primary) !important; color: #ffffff !important; padding-left: 1.25rem !important; }
        .jf-pill-option.selected { background: rgba(99, 102, 241, 0.1) !important; color: var(--jf-primary) !important; }

        /* ── Modern Elite Table ── */
        .jf-table-container {
            background: var(--jf-bg-card);
            border: 1px solid var(--jf-border);
            border-radius: 1rem;
            position: relative;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }

        .jf-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.875rem;
        }

        .jf-table tr:first-child th:first-child { border-top-left-radius: 1rem; }
        .jf-table tr:first-child th:last-child { border-top-right-radius: 1rem; }
        .jf-table tr:last-child td:first-child { border-bottom-left-radius: 1rem; }
        .jf-table tr:last-child td:last-child { border-bottom-right-radius: 1rem; }

        .jf-table th {
            text-align: left;
            padding: 1rem;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: var(--jf-text-sub);
            background: rgba(248, 250, 252, 0.5);
            border-bottom: 2px solid var(--jf-border);
        }
        :is([data-theme-mode="dark"], .dark) .jf-table th { background: rgba(255, 255, 255, 0.02); }

        .jf-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--jf-border);
            vertical-align: middle;
            color: var(--jf-text-main);
        }

        .jf-table tr:last-child td { border-bottom: none; }
        .jf-table tr:hover td { background: rgba(99, 102, 241, 0.02); }

        .jf-job-title {
            font-weight: 700;
            color: var(--jf-text-main);
            display: block;
            margin-bottom: 2px;
            transition: color 0.2s;
            text-decoration: none;
        }
        .jf-job-title:hover { color: var(--jf-primary); }

        .jf-company-sub {
            font-size: 0.75rem;
            color: var(--jf-text-sub);
            font-weight: 500;
        }

        /* ── 3-Dots Dropdown ── */
        .jf-action-dropdown {
            position: relative;
            display: inline-flex;
            z-index: 50;
        }

        .jf-action-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: var(--jf-text-sub);
            background: transparent;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
            position: relative;
            z-index: 2;
        }
        .jf-action-btn:hover {
            color: var(--jf-primary);
            background: rgba(99, 102, 241, 0.1);
        }

        .jf-dropdown-menu {
            position: fixed !important;
            width: 200px;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 14px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5) !important;
            z-index: 200000 !important;
            display: none;
            padding: 0.75rem;
            pointer-events: auto;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        :is([data-theme-mode="dark"], .dark) .jf-dropdown-menu {
            background: #2d3748 !important; /* Lighter slate for better contrast */
            border: 1px solid rgba(255, 255, 255, 0.4) !important; /* Ultra-visible border */
            box-shadow: 0 25px 60px -12px rgba(0,0,0,1) !important;
        }

        .jf-dropdown-menu.active {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .jf-dropdown-menu.drop-up {
            top: auto !important;
            bottom: 100% !important;
            margin-top: 0 !important;
            margin-bottom: 0.5rem !important;
            box-shadow: 0 -10px 15px -3px rgba(0,0,0,0.1) !important;
            animation: dropdownFadeUp 0.2s ease-out !important;
        }

        @keyframes dropdownFadeDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes dropdownFadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .saved-job-item {
            transition: all 0.4s ease;
        }

        .new-row-animate {
            animation: rowEntry 0.5s ease-out forwards;
            background: rgba(99, 102, 241, 0.05) !important;
        }

        @keyframes rowEntry {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .jf-dropdown-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 0.813rem;
            font-weight: 600;
            color: var(--jf-text-sub);
            text-align: left;
            transition: all 0.2s;
            border: none;
            background: transparent;
            cursor: pointer;
            text-decoration: none !important;
        }

        .jf-dropdown-item:hover {
            background: rgba(99, 102, 241, 0.05);
            color: var(--jf-primary);
        }

        .jf-dropdown-item.text-danger:hover {
            background: #fef2f2;
            color: #ef4444;
        }

        /* Job cards (Original) */
        .rj-card {
            background: var(--jf-bg-card); border: 1px solid var(--jf-border);
            border-radius: 20px; padding: 1.5rem;
            display: flex; flex-direction: column; height: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .rj-card:hover { transform: translateY(-6px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); border-color: var(--jf-primary); }
        .rj-card-squircle { width: 52px; height: 52px; background: rgba(241, 245, 249, 0.5); clip-path: url('#squircleClip'); display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; }
        .rj-card-squircle img { width: 100%; height: 100%; object-fit: cover; }

        .save-btn-toggle { position: absolute; top: 0.875rem; right: 0.875rem; width: 32px; height: 32px; border-radius: 8px; background: var(--jf-bg-card); border: 1px solid var(--jf-border); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.25s; z-index: 10; color: #94a3b8; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .save-btn-toggle:hover { transform: scale(1.1); color: #ef4444; border-color: #fca5a5; background: #fff1f2; }
        .save-btn-toggle.active { background: #fee2e2; border-color: #fecaca; color: #ef4444; }

        .rj-badge-ribbon { position: absolute; top: 0.875rem; left: 0.875rem; font-size: 0.625rem; font-weight: 800; padding: 3px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; z-index: 5; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .rj-badge-hot { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .rj-badge-new { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .rj-badge-match { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }

        .rj-card-title a { font-weight: 700; font-size: 0.9375rem; color: var(--jf-text-main); text-decoration: none; transition: color 0.2s; }
        .rj-card-title a:hover { color: var(--jf-primary); }

        .rj-card-meta { font-size: 0.75rem; color: var(--jf-text-sub); display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; }

        .rj-pill { font-size: 0.6875rem; font-weight: 600; padding: 3px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 3px; }
        .rj-pill-type { background: rgba(99, 102, 241, 0.1); color: var(--jf-primary); }
        .rj-pill-category { background: rgba(5, 150, 105, 0.1); color: #059669; }

        .rj-card-cta { display: inline-flex; align-items: center; justify-content: center; gap: 6px; width: 100%; padding: 0.5rem; border-radius: 8px; font-weight: 600; font-size: 0.8125rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.25s; color: #fff; background: linear-gradient(135deg, #4f46e5, #6366f1); }
        .rj-card-cta:hover { background: linear-gradient(135deg, #4338ca, #4f46e5); box-shadow: 0 4px 12px rgba(79,70,229,.3); }

        /* ── Dark Mode Elite Refinements ── */
        :is([data-theme-mode="dark"], .dark) .jf-header-section { border-bottom-color: rgba(255,255,255,0.08) !important; background: rgb(30, 32, 35) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-context-label { background: rgba(129, 140, 248, 0.15) !important; color: #ffffff !important; }
        :is([data-theme-mode="dark"], .dark) .rj-card { background: rgb(30, 32, 35) !important; border-color: rgba(255,255,255,0.06) !important; }
        :is([data-theme-mode="dark"], .dark) .rj-card:hover { border-color: #818cf8 !important; box-shadow: 0 20px 40px -12px rgba(0,0,0,0.3) !important; }
        :is([data-theme-mode="dark"], .dark) .rj-card-title a { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .rj-card-meta { color: #cbd5e1 !important; }
        :is([data-theme-mode="dark"], .dark) .rj-pill-category { background: rgba(5, 150, 105, 0.1) !important; color: #10b981 !important; }
        :is([data-theme-mode="dark"], .dark) .save-btn-toggle { background: rgba(255,255,255,0.05) !important; border-color: rgba(239,68,68,0.2) !important; color: #ef4444 !important; }
        :is([data-theme-mode="dark"], .dark) .save-btn-toggle:hover { background: rgba(239, 68, 68, 0.15) !important; border-color: rgba(239, 68, 68, 0.3) !important; color: #ef4444 !important; }
        :is([data-theme-mode="dark"], .dark) .save-btn-toggle.active { background: rgba(239, 68, 68, 0.2) !important; border-color: rgba(239, 68, 68, 0.4) !important; color: #ef4444 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-table { background: rgb(30, 32, 35) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-table th { background: rgba(255, 255, 255, 0.02) !important; border-bottom-color: rgba(255, 255, 255, 0.08) !important; color: #cbd5e1 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-table td { border-bottom-color: rgba(255, 255, 255, 0.08) !important; color: #f1f5f9 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-table tr:hover td { background: rgba(99, 102, 241, 0.05) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-table-container { background: rgb(30, 32, 35) !important; border-color: rgba(255,255,255,0.08) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-search-pill { background: rgb(30, 32, 35) !important; border-color: rgba(255,255,255,0.1) !important; box-shadow: 0 10px 40px rgba(0,0,0,0.3) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-pill-divider { background: rgba(255,255,255,0.08) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-pill-input { color: #f1f5f9 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-pill-field i { color: #cbd5e1 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-pill-select-options { background: rgb(30, 32, 35) !important; border-color: rgba(255,255,255,0.1) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-option { color: #cbd5e1 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-option:hover { background: rgba(255,255,255,0.05) !important; color: #818cf8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-dropdown-menu { background: rgb(30, 32, 35) !important; border-color: rgba(255,255,255,0.1) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-dropdown-item { color: #cbd5e1 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-dropdown-item:hover { background: rgba(99, 102, 241, 0.1) !important; color: #a5b4fc !important; }
        :is([data-theme-mode="dark"], .dark) .cd-section { background: rgb(30, 32, 35) !important; }
        :is([data-theme-mode="dark"], .dark) .rj-saved-section { background: rgb(30, 32, 35) !important; border-color: rgba(255,255,255,0.08) !important; }

        @media (max-width: 992px) {
            .jf-header-section { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
            .jf-header-title { font-size: 1.875rem; }
            .jf-header-actions {
                display: flex !important;
                flex-direction: row !important;
                align-items: stretch !important;
                flex-wrap: wrap !important;
                gap: 0.5rem !important;
                width: 100% !important;
            }
            .jf-header-actions a {
                flex: 1 !important;
                min-width: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 0.625rem 0.75rem !important;
                font-size: 0.8rem !important;
                text-align: center !important;
                height: auto !important;
            }
            .jf-search-pill-container {
                padding: 0.5rem 0 1rem 0;
            }
            .jf-search-pill {
                height: auto !important;
                flex-direction: column !important;
                border-radius: 1.5rem !important;
                padding: 1rem !important;
                gap: 0.75rem !important;
                align-items: stretch !important;
                box-shadow: 0 5px 20px rgba(0,0,0,0.05) !important;
            }
            .jf-pill-field {
                padding: 0.875rem 1rem !important;
                background: rgba(var(--jf-primary-rgb, 99, 102, 241), 0.03);
                border-radius: 1rem !important;
                width: 100% !important;
                max-width: none !important;
            }
            .jf-pill-divider { display: none !important; }
            .jf-pill-submit {
                width: 100% !important;
                justify-content: center !important;
                height: 52px !important;
                border-radius: 1rem !important;
            }
            .jf-pill-select-display {
                justify-content: space-between !important;
                padding: 0 0.5rem 0 0 !important;
                width: 100% !important;
            }
            .jf-pill-select-display i:first-child {
                margin-right: auto !important;
            }
            .jf-pill-select-display span {
                margin-left: auto !important;
                margin-right: 0.5rem !important;
            }
            .jf-pill-select-options {
                width: 100% !important;
                left: 0 !important;
            }
            .jf-table-container {
                border-radius: 0.75rem;
                margin-top: 0.5rem;
                padding-bottom: 120px !important; /* Fixed clipping */
                overflow-x: auto !important;
            }
            .jf-table {
                min-width: 750px;
                margin-bottom: -110px !important; /* Balance background */
            }
        }

        /* ── Dark Mode Empty State Overrides ── */
        :is([data-theme-mode="dark"], .dark) .rj-saved-section div[style*="text-align:center"] i { color: #64748b !important; }
        :is([data-theme-mode="dark"], .dark) .rj-saved-section div[style*="text-align:center"] p { color: #e5e7eb !important; }
        :is([data-theme-mode="dark"], .dark) .rj-saved-section div[style*="text-align:center"] span { color: #a0aec0 !important; }
        :is([data-theme-mode="dark"], .dark) .rj-saved-section .cd-btn-outline { background: rgba(99, 102, 241, 0.1) !important; color: #a5b4fc !important; border-color: rgba(99, 102, 241, 0.3) !important; }
    </style>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 grid grid-cols-12 gap-x-5 gap-y-5">

        {{-- Modern Minimalist Header --}}
        <div class="col-span-12" id="wt-hero">
            <x-modern-header :container="false" :id="null" chip="Personalized Picks">
                <x-slot name="titleContent"><strong>Recommended Jobs</strong></x-slot>
                <x-slot name="description">
                    Explore high-relevance job opportunities curated for your profile. Browse, filter, and apply to available roles within the <b>Applicant Portal</b>.
                </x-slot>
                <x-slot name="actions">
                    <a href="{{ route('applicant.dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm dark:bg-slate-800 dark:border-white/10 dark:text-white dark:hover:bg-slate-700">
                        <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
                    </a>
                    <a href="{{ route('applicant.applications.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm dark:bg-slate-800 dark:border-white/10 dark:text-white dark:hover:bg-slate-700">
                        <i class="ri-briefcase-line me-2 text-pink-500"></i> My Applications
                    </a>
                </x-slot>
            </x-modern-header>
        </div>

        @if (session('status'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (window.Swal) {
                        const statusMessage = @json(session('status'));
                        const isRemoval = statusMessage.toLowerCase().includes('removed') || statusMessage.toLowerCase().includes('deleted');
                        Swal.fire({
                            icon: 'success',
                            title: isRemoval ? 'Deleted Job' : 'Saved Job',
                            text: statusMessage,
                            timer: 2200,
                            showConfirmButton: false
                        });
                    }
                });
            </script>
        @endif



        {{-- ═══ Job Cards Grid ═══ --}}
        <div class="col-span-12" id="wt-grid">
            {{-- SVG Mask for Squircles --}}
            <svg width="0" height="0" style="position:absolute;">
                <defs>
                    <clipPath id="squircleClip" clipPathUnits="objectBoundingBox">
                        <path d="M 0,0.5 C 0,0.05 0.05,0 0.5,0 S 1,0.05 1,0.5 0.95,1 0.5,1 0,0.95 0,0.5" />
                    </clipPath>
                </defs>
            </svg>
            @if($recommendedJobs->isEmpty())
                <div class="cd-section">
                    <div class="cd-empty">
                        <i class="bi bi-stars"></i>
                        <p>No recommended jobs available at the moment.</p>
                        <span style="font-size:0.8125rem;color:#9ca3af;margin-bottom:0.75rem;display:block">Apply to some jobs to get personalized recommendations.</span>
                        <a href="{{ route('jobs') }}" class="cd-btn" style="background:linear-gradient(135deg, #6366f1 0%, #4338ca 100%); color:#fff; box-shadow: 0 4px 15px rgba(99,102,241,0.3);"><i class="ri-search-line me-2"></i> Browse Opportunities</a>
                    </div>
                </div>
            @else
                <div id="rj-grid" class="grid grid-cols-12 gap-5">
                    @foreach($recommendedJobs as $index => $job)
                        @php
                            $colors = ['#4f46e5','#0891b2','#059669','#d97706','#dc2626','#7c3aed','#db2777'];
                            $avatarBg = $colors[$index % count($colors)];
                            $words = explode(' ', $job->company?->name ?? 'C');
                            $initials = strtoupper(substr($words[0] ?? 'C', 0, 1)) . strtoupper(substr($words[1] ?? '', 0, 1));
                            $applicantCount = $job->applications_count ?? 0;
                            $postedAt = $job->posted_at;
                            $isNew = $postedAt && $postedAt->gt(now()->subDays(3));
                            $isHot = $index < 3;
                            $isHighMatch = !$isHot && !$isNew;
                        @endphp
                        <div
                            class="xl:col-span-3 md:col-span-6 col-span-12 rj-card-wrap"
                            data-job-id="{{ $job->id }}"
                            data-slug="{{ $job->slug }}"
                            data-title="{{ Str::lower($job->title) }}"
                            data-display-title="{{ $job->title }}"
                            data-company-name="{{ $job->company?->name ?? 'Company' }}"
                            data-logo="{{ $job->company?->logo_url ?? '' }}"
                            data-initials="{{ $initials }}"
                            data-avatar-bg="{{ $avatarBg }}"
                            data-location="{{ Str::lower($job->location ?? '') }}"
                            data-display-location="{{ $job->location ?? 'Remote' }}"
                            data-type="{{ Str::lower($job->employment_type ?? '') }}"
                            data-display-type="{{ Str::headline($job->employment_type) }}"
                            data-posted="{{ $postedAt ? $postedAt->timestamp : 0 }}"
                            data-applicants="{{ $applicantCount }}"
                        >
                            <div class="cd-section rj-card">

                                {{-- Interactive Save Toggle --}}
                                @php
                                    $isSaved = in_array($job->id, $savedJobIds ?? []);
                                @endphp
                                <div class="save-btn-toggle {{ $isSaved ? 'active' : '' }}"
                                     onclick="toggleJobSave(this, '{{ $job->slug }}', '{{ $job->id }}')"
                                     title="{{ $isSaved ? 'Remove from saved' : 'Save for later' }}">
                                    <i class="{{ $isSaved ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                                </div>

                                {{-- Badge ribbon --}}
                                @if($isHot)
                                    <span class="rj-badge-ribbon rj-badge-hot">🔥 Recommended</span>
                                @elseif($isNew)
                                    <span class="rj-badge-ribbon rj-badge-new">✨ New</span>
                                @elseif($isHighMatch)
                                    <span class="rj-badge-ribbon rj-badge-match">⚡ High Match</span>
                                @endif

                                {{-- Avatar + Company --}}
                                <div style="display:flex;align-items:flex-start;gap:1rem;margin-bottom:1rem;margin-top:1.5rem">
                                    <div class="rj-card-squircle" style="background:{{ $avatarBg }}22; border: 1px solid {{ $avatarBg }}44;">
                                        @if($job->company?->logo_url)
                                            <img src="{{ $job->company->logo_url }}" alt="">
                                        @else
                                            <span style="color:{{ $avatarBg }}; font-weight: 800; font-size: 1rem;">{{ $initials }}</span>
                                        @endif
                                    </div>
                                    <div style="min-width:0;flex:1">
                                        <p style="font-size:0.875rem;font-weight:700;color:var(--jf-text-main);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                            {{ $job->company?->name ?? 'Company' }}
                                        </p>
                                        <div class="rj-card-meta">
                                            @php
                                                $rating = $job->company?->rating ?? 4.5;
                                                $fullStars = floor($rating);
                                            @endphp
                                            <div style="display:flex; align-items:center; gap:3px; color:#f59e0b;">
                                                @for($i=0; $i<$fullStars; $i++) <i class="ri-star-fill" style="font-size:0.8rem"></i> @endfor
                                                @if($rating > $fullStars) <i class="ri-star-half-fill" style="font-size:0.8rem"></i> @endif
                                                <span style="color:#64748b; margin-left:2px; font-weight:700; font-size:0.75rem">{{ number_format($rating, 1) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Title --}}
                                <h5 class="rj-card-title" style="margin-bottom:0.75rem;line-height:1.4">
                                    <a href="{{ route('jobs.show', $job->slug) }}" style="font-size:1.0625rem; font-weight: 800; letter-spacing: -0.01em;">{{ $job->title }}</a>
                                </h5>

                                {{-- Social proof / Meta --}}
                                <div class="rj-card-meta" style="margin-bottom:1rem; gap:0.75rem">
                                    <span><i class="ri-map-pin-2-fill me-1" style="color:#6366f1"></i>{{ $job->location ?? 'Remote' }}</span>
                                    <span>·</span>
                                    @if($postedAt)
                                        <span>{{ $postedAt->diffForHumans() }}</span>
                                    @endif
                                </div>

                                {{-- Pills --}}
                                <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:1.25rem">
                                    @if($job->employment_type)
                                        <span class="rj-pill rj-pill-type" style="padding:4px 12px; font-weight: 700;">{{ Str::headline($job->employment_type) }}</span>
                                    @endif
                                    @if($job->category)
                                        <span class="rj-pill rj-pill-category" style="padding:4px 12px; font-weight: 700; background:#f0fdf4; color:#166534;">{{ Str::headline($job->category) }}</span>
                                    @endif
                                </div>

                                {{-- CTA --}}
                                <div style="margin-top:auto;padding-top:1rem;border-top:1px solid #f1f5f9">
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="rj-card-cta" style="padding:0.625rem">
                                        View Details <i class="ri-arrow-right-line arrow"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- No results message --}}
                <div id="rj-no-results" style="display:none;text-align:center;padding:3rem 1rem">
                    <i class="ri-search-line" style="font-size:2rem;color:#d1d5db;display:block;margin-bottom:0.5rem"></i>
                    <p style="font-weight:600;color:#6b7280">No jobs match your filters.</p>
                    <button onclick="document.getElementById('rj-search').value='';document.getElementById('rj-filter-location').value='';document.getElementById('rj-filter-type').value='';filterCards();"
                            style="margin-top:0.5rem;background:none;border:none;color:#4f46e5;font-weight:600;cursor:pointer;font-size:0.875rem">
                        Clear all filters
                    </button>
                </div>
            @endif
        </div>

        {{-- ═══ Section Divider ═══ --}}
        <div class="col-span-12" style="margin-top:1rem">
            <div style="display:flex;align-items:center;gap:1rem">
                <div style="flex:1;height:2px;background:linear-gradient(90deg,#e5e7eb,transparent)"></div>
                <span style="font-size:0.75rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:1.5px;white-space:nowrap">Your Saved Collection</span>
                <div style="flex:1;height:2px;background:linear-gradient(270deg,#e5e7eb,transparent)"></div>
            </div>
        </div>

        {{-- ═══ Saved Jobs Section ═══ --}}
        <div class="col-span-12" id="wt-saved">
            <div class="rj-saved-section cd-section ">
                <div class="jf-header-section !mb-3">

                <span class="ribbon-4 ribbon-danger top-right">
                    <span><i class="ri-bookmark-fill"></i></span>
                </span>
                    <div class="jf-header-content">
                        {{-- <div class="jf-context-row">
                            <div class="jf-v-bar" style="background:#d97706"></div>
                            <span class="jf-context-label" style="background:rgba(217, 119, 6, 0.1); color:#d97706">Saved collection</span>
                        </div> --}}
                        <h3 class="jf-header-title !text-[12px] !mb-0"></i>Bookmarked Jobs</h3>
                        <p class="jf-header-desc" style="font-size:0.875rem;">Jobs you've bookmarked for later — don't let them slip away.</p>
                    </div>
                </div>

                @if($savedJobs->isEmpty())
                    <div style="text-align:center;padding:3rem 1rem">
                        <i class="ri-bookmark-line" style="font-size:3rem;color:#d1d5db;display:block;margin-bottom:1rem"></i>
                        <p style="font-weight:600;color:#6b7280;margin-bottom:0.5rem">Your collection is empty</p>
                        <span style="font-size:0.875rem;color:#9ca3af;display:block;margin-bottom:1.5rem">Browse recommended jobs and click the heart icon to save them here.</span>
                        <a href="{{ route('jobs') }}" class="cd-btn cd-btn-outline" style="display:inline-flex"><i class="ri-search-line me-1"></i> Browse All Jobs</a>
                    </div>
                @else
                    {{-- Premium Search Pill --}}
                    <div class="jf-search-pill-container">
                        <div class="jf-search-pill">
                            {{-- Selection Status (Hidden unless checked) --}}
                            <div id="selection-status" style="display:none; align-items:center; gap:0.75rem; padding-left:0.5rem;">
                                <div style="width:32px; height:32px; border-radius:16px; background:linear-gradient(135deg, #6366f1, #4338ca); color:#fff; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:800; box-shadow:0 4px 10px rgba(99,102,241,0.3);" id="selection-count-badge">0</div>
                                <span style="font-size:0.813rem; font-weight:800; color:#6366f1;">Selection Active</span>
                            </div>

                            {{-- Main Search Field --}}
                            <div class="jf-pill-field" id="search-field-pill">
                                <i class="ri-search-2-line" style="color:#6366f1;"></i>
                                <input type="text" id="search-input" class="jf-pill-input" placeholder="Search your saved jobs...">
                            </div>

                            <div class="jf-pill-divider"></div>

                            {{-- Bulk Action Dropdown --}}
                            <div class="jf-pill-field" style="max-width:200px">
                                <div class="jf-pill-select-wrapper" id="bulk-action-wrapper">
                                    <div class="jf-pill-select-display">
                                        <i class="ri-stack-line"></i>
                                        <span>Bulk Action</span>
                                        <i class="ri-arrow-down-s-line"></i>
                                    </div>
                                    <div class="jf-pill-select-options">
                                        <div class="jf-pill-option header">Select Action</div>
                                        <div class="jf-pill-option" onclick="setBulkAction('apply')"><i class="ri-briefcase-line"></i> Apply to Selected</div>
                                        <div class="jf-pill-option" onclick="setBulkAction('remove')"><i class="ri-delete-bin-line"></i> Remove Selected</div>
                                    </div>
                                    <input type="hidden" id="bulk-action-val" value="">
                                </div>
                            </div>

                            <button type="button" id="apply-bulk-action" class="jf-pill-submit">
                                <i class="ri-check-double-line"></i>
                                <span>Execute</span>
                            </button>
                        </div>
                    </div>

                    {{-- Modern Elite Table --}}
                    <div class="jf-table-container">
                        <table class="jf-table" id="saved-jobs-table">
                            <thead>
                                <tr>
                                    <th style="width:50px; text-align:center;">
                                        <input type="checkbox" id="select-all" class="form-check-input" style="width:18px; height:18px; border-radius:6px; cursor:pointer;">
                                    </th>
                                    <th>Job Opportunity</th>
                                    <th>Location & Type</th>
                                    <th>Status</th>
                                    <th>Saved On</th>
                                    <th style="width:80px; text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="saved-jobs-list">
                                @foreach($savedJobs as $saved)
                                    @php
                                        $job = $saved->jobPosting;
                                        if (!$job) { continue; }
                                        $status = $applicationsByJobId[$job->id] ?? null;
                                        $savedWords = explode(' ', $job->company?->name ?? 'C');
                                        $savedInitials = strtoupper(substr($savedWords[0] ?? 'C', 0, 1)) . strtoupper(substr($savedWords[1] ?? '', 0, 1));
                                    @endphp
                                    <tr class="saved-job-item"
                                        data-title="{{ Str::lower($job->title) }}"
                                        data-company="{{ Str::lower($job->company?->name ?? '') }}">
                                        <td style="text-align:center;">
                                            <input type="checkbox" class="form-check-input row-checkbox" value="{{ $job->id }}" data-applied="{{ $status ? '1' : '0' }}" style="width:18px; height:18px; border-radius:6px; cursor:pointer;">
                                        </td>
                                        <td>
                                            <div style="display:flex; align-items:center; gap:12px;">
                                                <div style="width:40px; height:40px; border-radius:10px; background:rgba(99,102,241,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0; overflow:hidden;">
                                                    @if($job->company?->logo_url)
                                                        <img src="{{ $job->company->logo_url }}" alt="" style="width:100%; height:100%; object-fit:cover;">
                                                    @else
                                                        <span style="font-weight:800; font-size:0.75rem; color:var(--jf-primary);">{{ $savedInitials }}</span>
                                                    @endif
                                                </div>
                                                <div style="min-width:0;">
                                                    <a href="{{ route('jobs.show', $job->slug) }}" class="jf-job-title">{{ $job->title }}</a>
                                                    <span class="jf-company-sub">{{ $job->company?->name ?? 'Company' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="display:flex; flex-direction:column; gap:6px;">
                                                <div style="display:flex; align-items:center; gap:6px;">
                                                    <i class="ri-map-pin-line" style="font-size:1rem; color:#6366f1;"></i>
                                                    <span style="font-size:0.813rem; font-weight:600;">{{ $job->location ?? 'Remote' }}</span>
                                                </div>
                                                <div style="display:flex;">
                                                    <span style="font-size:0.6875rem; color:#475569; background:#f1f5f9; padding:2px 8px; border-radius:6px; font-weight:700; text-transform:uppercase; letter-spacing:0.02em;">{{ Str::headline($job->employment_type) }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($status)
                                                <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:8px; background:rgba(16, 185, 129, 0.1); color:#059669; border:1px solid rgba(16, 185, 129, 0.2); font-size:0.6875rem; font-weight:800; text-transform:uppercase; letter-spacing:0.04em;">
                                                    <i class="ri-checkbox-circle-fill" style="font-size:0.85rem;"></i> {{ Str::headline($status) }}
                                                </span>
                                            @else
                                                <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:8px; background:rgba(245, 158, 11, 0.1); color:#d97706; border:1px solid rgba(245, 158, 11, 0.2); font-size:0.6875rem; font-weight:800; text-transform:uppercase; letter-spacing:0.04em;">
                                                    <i class="ri-time-line" style="font-size:0.85rem;"></i> Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span style="font-size:0.813rem; color:var(--jf-text-sub); font-weight:500;">
                                                {{ $saved->saved_at ? $saved->saved_at->diffForHumans() : 'Recently' }}
                                            </span>
                                        </td>
                                        <td style="text-align:right;">
                                            <div class="jf-action-dropdown">
                                                <button type="button" class="jf-action-btn" onclick="toggleActionMenu(event, this)">
                                                    <i class="ri-more-2-fill"></i>
                                                </button>
                                                <div class="jf-dropdown-menu">
                                                    @if(!$status)
                                                        <a href="{{ route('jobs.show', $job->slug) }}" class="jf-dropdown-item">
                                                            <i class="ri-briefcase-line"></i> Apply Now
                                                        </a>
                                                    @else
                                                        <a href="{{ route('applicant.applications.index') }}" class="jf-dropdown-item">
                                                            <i class="ri-eye-line"></i> View Application
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('jobs.show', $job->slug) }}" class="jf-dropdown-item">
                                                        <i class="ri-external-link-line"></i> Job Details
                                                    </a>
                                                    <div style="height:1px; background:var(--jf-border); margin:4px 0;"></div>
                                                    <button type="button" class="jf-dropdown-item text-danger" onclick="confirmRemoveSaved('{{ $job->id }}', '{{ $job->slug }}', '{{ addslashes($job->title) }}')">
                                                        <i class="ri-delete-bin-line"></i> Remove from Saved
                                                    </button>
                                                    <form id="remove-form-{{ $job->id }}" action="{{ route('jobs.save', $job->slug) }}" method="POST" style="display:none;">
                                                        @csrf
                                                        <input type="hidden" name="redirect" value="recommended-jobs">
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top:1.5rem">
                        {{ $savedJobs->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>

    <script>
        // ── Standard Toggle Job Save ──
        window.toggleJobSave = function(btn, slug, jobId) {
            const isActive = btn.classList.contains('active');
            const icon = btn.querySelector('i');
            const cardWrap = btn.closest('.rj-card-wrap');

            if (event) {
                event.stopPropagation();
                event.preventDefault();
            }

            btn.classList.toggle('active');
            if (icon) {
                icon.className = isActive ? 'ri-heart-line' : 'ri-heart-fill';
            }

            fetch(`/jobs/${slug}/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ redirect: 'none' })
            })
            .then(res => res.json())
            .then(data => {
                const isRemoval = data.status === 'removed' || isActive;

                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: isRemoval ? 'Deleted Job' : 'Saved Job',
                        text: isRemoval ? 'Job removed from saved.' : 'Job saved successfully!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }

                const savedList = document.getElementById('saved-jobs-list');

                if (isRemoval) {
                    // Remove from table
                    const savedRow = document.querySelector(`.row-checkbox[value="${jobId}"]`)?.closest('tr');
                    if (savedRow) {
                        savedRow.style.opacity = '0';
                        savedRow.style.transform = 'translateX(20px)';
                        setTimeout(() => {
                            savedRow.remove();
                            if (typeof updateSelectionCount === 'function') updateSelectionCount();
                            // If table is now empty, we might want to reload to show the empty state
                            if (savedList && savedList.children.length === 0) window.location.reload();
                        }, 400);
                    }
                } else {
                    // Add to table
                    if (!savedList) {
                        // If collection was empty, page needs reload to show the table shell
                        window.location.reload();
                        return;
                    }

                    if (cardWrap) {
                        const jobData = {
                            id: jobId,
                            slug: slug,
                            title: cardWrap.dataset.title,
                            displayTitle: cardWrap.dataset.displayTitle,
                            company: cardWrap.dataset.companyName,
                            logo: cardWrap.dataset.logo,
                            initials: cardWrap.dataset.initials,
                            avatarBg: cardWrap.dataset.avatarBg,
                            location: cardWrap.dataset.displayLocation,
                            type: cardWrap.dataset.displayType
                        };

                        const newRow = createSavedJobRow(jobData);
                        savedList.insertBefore(newRow, savedList.firstChild);

                        setTimeout(() => newRow.classList.remove('new-row-animate'), 2000);
                    }
                }
            })
            .catch(err => {
                console.error(err);
                btn.classList.toggle('active');
                if (icon) icon.className = isActive ? 'ri-heart-fill' : 'ri-heart-line';
            });
        };

        function createSavedJobRow(jobData) {
            const row = document.createElement('tr');
            row.className = 'saved-job-item new-row-animate';
            row.dataset.title = jobData.title;
            row.dataset.company = jobData.company.toLowerCase();

            row.innerHTML = `
                <td style="text-align:center;">
                    <input type="checkbox" class="form-check-input row-checkbox" value="${jobData.id}" data-applied="0" style="width:18px; height:18px; border-radius:6px; cursor:pointer;">
                </td>
                <td>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="rj-card-squircle" style="width:40px; height:40px; background:${jobData.avatarBg}22; border: 1px solid ${jobData.avatarBg}44;">
                            ${jobData.logo ? `<img src="${jobData.logo}" alt="" style="width:100%; height:100%; object-fit:contain; border-radius:8px;">` : `<span style="color:${jobData.avatarBg}; font-weight: 800; font-size: 0.85rem;">${jobData.initials}</span>`}
                        </div>
                        <div style="min-width:0;">
                            <a href="/jobs/${jobData.slug}" class="jf-job-title">${jobData.displayTitle}</a>
                            <span class="jf-company-sub">${jobData.company}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="display:flex; flex-direction:column; gap:6px;">
                        <div style="display:flex; align-items:center; gap:6px;">
                            <i class="ri-map-pin-line" style="font-size:1rem; color:#6366f1;"></i>
                            <span style="font-size:0.813rem; font-weight:600;">${jobData.location}</span>
                        </div>
                        <div style="display:flex;">
                            <span style="font-size:0.6875rem; color:#475569; background:#f1f5f9; padding:2px 8px; border-radius:6px; font-weight:700; text-transform:uppercase; letter-spacing:0.02em;">${jobData.type}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:8px; background:rgba(245, 158, 11, 0.1); color:#d97706; border:1px solid rgba(245, 158, 11, 0.2); font-size:0.6875rem; font-weight:800; text-transform:uppercase; letter-spacing:0.04em;">
                        <i class="ri-time-line" style="font-size:0.85rem;"></i> Pending
                    </span>
                </td>
                <td>
                    <span style="font-size:0.813rem; color:var(--jf-text-sub); font-weight:500;">Recently</span>
                </td>
                <td style="text-align:right;">
                    <div class="jf-action-dropdown">
                        <button type="button" class="jf-action-btn" onclick="toggleActionMenu(this)">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <div class="jf-dropdown-menu">
                            <a href="/jobs/${jobData.slug}" class="jf-dropdown-item">
                                <i class="ri-briefcase-line"></i> Apply Now
                            </a>
                            <a href="/jobs/${jobData.slug}" class="jf-dropdown-item">
                                <i class="ri-external-link-line"></i> Job Details
                            </a>
                            <div style="height:1px; background:var(--jf-border); margin:4px 0;"></div>
                            <button type="button" class="jf-dropdown-item text-danger" onclick="confirmRemoveSaved('${jobData.id}', '${jobData.slug}', '${jobData.displayTitle.replace(/'/g, "\\'")}')">
                                <i class="ri-delete-bin-line"></i> Remove from Saved
                            </button>
                        </div>
                    </div>
                </td>
            `;
            return row;
        }

        window.toggleActionMenu = (event, btn) => {
            event.stopPropagation();
            const originalMenu = btn.nextElementSibling;
            if (!originalMenu) return;

            const isActive = originalMenu.classList.contains('active');

            // Close all other menus
            document.querySelectorAll('.jf-dropdown-menu.active').forEach(m => {
                if (m !== originalMenu) {
                    m.classList.remove('active');
                    m.style.display = 'none';
                }
            });

            if (!isActive) {
                if (originalMenu.parentElement !== document.body) {
                    document.body.appendChild(originalMenu);
                }

                const rect = btn.getBoundingClientRect();
                originalMenu.style.display = 'block';
                originalMenu.classList.add('active');
                originalMenu.style.position = 'fixed';
                originalMenu.style.top = (rect.bottom + 5) + 'px';

                // Smart Horizontal Bounds
                let leftPos = rect.right - 200;
                if (leftPos < 10) leftPos = 10;
                if (leftPos + 200 > window.innerWidth - 10) leftPos = window.innerWidth - 210;

                originalMenu.style.left = leftPos + 'px';
                originalMenu.style.zIndex = '2147483647';
            } else {
                originalMenu.classList.remove('active');
                originalMenu.style.display = 'none';
            }
        };

        // Improved Click listener (Prevents self-closing while allowing child clicks)
        document.addEventListener('click', (e) => {
            document.querySelectorAll('.jf-dropdown-menu.active').forEach(m => {
                if (!m.contains(e.target)) {
                    m.classList.remove('active');
                    m.style.display = 'none';
                }
            });

            // Bulk Action logic
            const bulkWrapper = document.getElementById('bulk-action-wrapper');
            if (bulkWrapper && !bulkWrapper.contains(e.target)) {
                bulkWrapper.classList.remove('active');
            }
        });

        // Close on scroll
        window.addEventListener('scroll', () => {
             document.querySelectorAll('.jf-dropdown-menu.active').forEach(m => {
                 m.classList.remove('active');
                 m.style.display = 'none';
             });
        }, { passive: true });

        // ── Search Pill Selection Logic ──
        const bulkWrapper = document.getElementById('bulk-action-wrapper');
        if (bulkWrapper) {
            bulkWrapper.addEventListener('click', (e) => {
                e.stopPropagation();
                bulkWrapper.classList.toggle('active');
            });
        }

        window.setBulkAction = function(action) {
            const valInput = document.getElementById('bulk-action-val');
            const displaySpan = document.querySelector('#bulk-action-wrapper .jf-pill-select-display span');

            valInput.value = action;
            displaySpan.textContent = action === 'apply' ? 'Apply Selected' : (action === 'remove' ? 'Remove Selected' : 'Bulk Action');

            document.querySelectorAll('.jf-pill-option').forEach(opt => opt.classList.remove('selected'));
            const opt = event.currentTarget.closest('.jf-pill-option') || event.target;
            if (opt && opt.classList) opt.classList.add('selected');
        };

        // ── Table Selection Logic ──
        const selectAll = document.getElementById('select-all');
        const selectionStatus = document.getElementById('selection-status');
        const selectionBadge = document.getElementById('selection-count-badge');
        const searchField = document.getElementById('search-field-pill');

        function updateSelectionCount() {
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            if (checked > 0) {
                if (selectionStatus) selectionStatus.style.display = 'flex';
                if (selectionBadge) selectionBadge.textContent = checked;
                if (searchField) searchField.style.display = 'none';
            } else {
                if (selectionStatus) selectionStatus.style.display = 'none';
                if (searchField) searchField.style.display = 'flex';
                if (selectAll) selectAll.checked = false;
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                const rowCbs = document.querySelectorAll('.row-checkbox');
                rowCbs.forEach(cb => {
                    if (cb.closest('tr').style.display !== 'none') {
                        cb.checked = selectAll.checked;
                    }
                });
                updateSelectionCount();
            });
        }

        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('row-checkbox')) {
                updateSelectionCount();
            }
        });

        // ── Saved Jobs Searching ──
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.saved-job-item').forEach(row => {
                    const title = row.getAttribute('data-title') || '';
                    const company = row.getAttribute('data-company') || '';
                    row.style.display = (title.includes(q) || company.includes(q)) ? '' : 'none';
                });
            });
        }

        // ── Bulk Execution ──
        const executeBtn = document.getElementById('apply-bulk-action');
        if (executeBtn) {
            executeBtn.addEventListener('click', () => {
                const action = document.getElementById('bulk-action-val').value;
                const checked = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);

                if (!action) {
                    Swal.fire({ icon: 'warning', title: 'Action Required', text: 'Please select a bulk action from the dropdown.' });
                    return;
                }
                if (checked.length === 0) {
                    Swal.fire({ icon: 'warning', title: 'Selection Empty', text: 'Please select at least one job from the table.' });
                    return;
                }

                if (action === 'remove') {
                    confirmBulkRemove(checked);
                } else if (action === 'apply') {
                    confirmBulkApply(checked);
                }
            });
        }

        function confirmBulkRemove(ids) {
            Swal.fire({
                title: `Remove ${ids.length} jobs?`,
                text: "They will be removed from your saved collection.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, remove them'
            }).then((result) => {
                if (result.isConfirmed) {
                    performBulkAction('{{ route("applicant.saved-jobs.bulk-remove") }}', ids);
                }
            });
        }

        function confirmBulkApply(ids) {
            Swal.fire({
                title: `Apply to ${ids.length} jobs?`,
                text: "This will submit applications for the selected roles.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                confirmButtonText: 'Yes, apply now'
            }).then((result) => {
                if (result.isConfirmed) {
                    performBulkAction('{{ route("applicant.saved-jobs.bulk-apply") }}', ids);
                }
            });
        }

        function performBulkAction(url, ids) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids: ids })
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({ icon: 'success', title: 'Success', text: 'Action completed successfully.', timer: 1500, showConfirmButton: false })
                .then(() => window.location.reload());
            })
            .catch(err => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' }));
        }

        // ── Single Remove Function ──
        window.confirmRemoveSaved = function(id, slug, title) {
            Swal.fire({
                title: 'Remove from Saved?',
                text: `Are you sure you want to remove "${title}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Remove'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Try AJAX removal first for immediate feedback
                    fetch(`/jobs/${slug}/save`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ redirect: 'none' })
                    })
                    .then(res => res.json())
                    .then(data => {
                        // Success toast
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Deleted Job',
                            text: 'Job removed from saved collection.',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // 1. Remove from table
                        const savedRow = document.querySelector(`.row-checkbox[value="${id}"]`)?.closest('tr');
                        const savedList = document.getElementById('saved-jobs-list');
                        if (savedRow) {
                            savedRow.style.opacity = '0';
                            savedRow.style.transform = 'translateX(20px)';
                            setTimeout(() => {
                                savedRow.remove();
                                if (typeof updateSelectionCount === 'function') updateSelectionCount();
                                // If list becomes empty, reload to show empty state shell
                                if (savedList && savedList.children.length === 0) window.location.reload();
                            }, 400);
                        }

                        // 2. Untoggle heart icon in grid if it exists
                        const heartBtn = document.querySelector(`.rj-card-wrap[data-job-id="${id}"] .save-btn-toggle`);
                        if (heartBtn) {
                            heartBtn.classList.remove('active');
                            const icon = heartBtn.querySelector('i');
                            if (icon) icon.className = 'ri-heart-line';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Could not remove job.' });
                    });
                }
            });
        };

        // ── Floating helper scroll ──
        window.addEventListener('load', () => {
            const helper = document.getElementById('rj-float-helper');
            if (helper) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (!entry.isIntersecting) helper.style.display = 'flex';
                    });
                }, { threshold: 0 });
                const grid = document.getElementById('rj-grid');
                if (grid && grid.children.length > 8) observer.observe(grid.children[7]);
            }
        });
    </script>

    @include('applicants.partials.walkthrough', [
        'wtKey' => 'recommended_jobs',
        'wtSteps' => [
            ['target' => 'wt-hero', 'icon' => 'bi bi-stars', 'title' => 'Recommended For You', 'body' => 'Jobs are recommended based on your profile, skills, and past applications. The more you apply, the smarter your recommendations get.', 'position' => 'bottom'],
            ['target' => 'wt-filters', 'icon' => 'ri-filter-3-line', 'title' => 'Filter & Sort', 'body' => 'Use the search bar to find specific jobs, filter by location or type, and sort by newest or most popular. Filters work instantly.', 'position' => 'bottom'],
            ['target' => 'wt-grid', 'icon' => 'ri-briefcase-fill', 'title' => 'Job Cards', 'body' => 'Browse recommended positions. Look for badges like \"Recommended\", \"New\", and \"High Match\" to prioritize your applications. Click \"View & Apply\" to get started.', 'position' => 'top'],
            ['target' => 'wt-saved', 'icon' => 'ri-bookmark-fill', 'title' => 'Saved Jobs', 'body' => 'Jobs you have bookmarked appear here. You can quickly apply to them or remove them from your saved collection.', 'position' => 'top'],
        ]
    ])

</x-app-layout>
