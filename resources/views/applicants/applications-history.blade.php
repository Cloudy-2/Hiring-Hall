<x-app-layout>

    <x-slot name="url_1">{"link": "/applicant/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="title">Application History</x-slot>
    <x-slot name="active">Application History</x-slot>

    @include('applicants.partials.candidate-styles')

    <style>
        /* ── Dark Mode Overrides for Application History ── */
        /* ── Dark Mode Overrides for Application History ── */
        :is([data-theme-mode="dark"], .dark) .cd-pipe { background: rgba(255,255,255,0.05) !important; border-color: rgba(255,255,255,0.1) !important; }
        :is([data-theme-mode="dark"], .dark) .cd-status-pill { background: rgba(255,255,255,0.1) !important; color: #d1d5db !important; }

        :is([data-theme-mode="dark"], .dark) .cd-status-pill { background: rgba(255,255,255,0.1) !important; color: #d1d5db !important; }

        /* ── Modern Elite Archive ── */
        .jf-archive-card {
            background: #ffffff;
            border: 1px solid var(--cd-border);
            border-radius: 1rem;
            padding: 1rem;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            height: 100%;
        }
        .jf-archive-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.08);
            border-color: #6366f1;
        }

        .jf-archive-card-header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        :is([data-theme-mode="dark"], .dark) .jf-archive-card { background: #1e293b !important; border-color: rgba(255,255,255,0.05) !important; }

        .jf-archive-card.total { background: linear-gradient(145deg, #ffffff 0%, #f0f7ff 100%); }
        .jf-archive-card.withdrawn { background: linear-gradient(145deg, #ffffff 0%, #fffbeb 100%); }
        .jf-archive-card.not-selected { background: linear-gradient(145deg, #ffffff 0%, #fef2f2 100%); }
        .jf-archive-card.closed { background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%); }

        .jf-archive-icon {
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

        .jf-archive-lbl {
            font-size: 0.75rem;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.25rem;
            position: relative;
            z-index: 2;
        }

        .jf-archive-val {
            font-size: 1.75rem;
            font-weight: 900;
            color: #1e293b;
            line-height: 1.1;
            margin: 0.15rem 0;
            letter-spacing: -0.04em;
            position: relative;
            z-index: 2;
        }
        :is([data-theme-mode="dark"], .dark) .jf-archive-val { color: #f8fafc !important; }

        .jf-archive-trend {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.65rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 2;
        }
        .jf-archive-trend.none { color: var(--cd-text-muted); }
        .jf-archive-trend.up { color: #059669; }
        .jf-archive-trend.down { color: #dc2626; }

        .jf-archive-desc {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--cd-text-secondary);
            line-height: 1.4;
            position: relative;
            z-index: 2;
        }
        :is([data-theme-mode="dark"], .dark) .jf-archive-desc { color: #94a3b8 !important; }

        /* Elevated Icon Styles with Tinted Glow */
        .jf-archive-card.total .jf-archive-icon {
            background: rgba(99, 102, 241, 0.1); color: #6366f1; border: 1px solid rgba(99, 102, 241, 0.2);
        }
        .jf-archive-card.withdrawn .jf-archive-icon {
            background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2);
        }
        .jf-archive-card.not-selected .jf-archive-icon {
            background: rgba(220, 38, 38, 0.1); color: #dc2626; border: 1px solid rgba(220, 38, 38, 0.2);
        }
        .jf-archive-card.closed .jf-archive-icon {
            background: rgba(100, 116, 139, 0.1); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.2);
        }

        .jf-archive-card:hover .jf-archive-icon { transform: scale(1.05); }

        /* ── Modern Elite Table ── */
        .jf-table-container {
            background: #ffffff;
            border: 1px solid var(--cd-border);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        :is([data-theme-mode="dark"], .dark) .jf-table-container { background: #1e293b !important; border-color: rgba(255,255,255,0.05) !important; }

        /* ── Premium Archive Search Pill ── */
        .jf-search-pill-container {
            padding: 0.5rem 0 1.5rem 0;
            padding-bottom: 10px;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .jf-search-pill {
            display: flex;
            align-items: center;
            height: 64px;
            background: #ffffff;
            border: 1.5px solid var(--cd-border);
            border-radius: 32px;
            padding: 0 0.5rem 0 1.25rem;
            box-shadow: 0 10px 30px -8px rgba(0,0,0,0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
        }
        .jf-search-pill:focus-within {
            border-color: var(--cd-accent);
            box-shadow: 0 15px 35px -10px rgba(99,102,241,0.12);
        }
        [data-theme-mode="dark"] .jf-search-pill, .dark .jf-search-pill {
            background: #1e293b !important;
            border-color: rgba(255,255,255,0.1) !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3) !important;
        }

        .jf-pill-field { flex: 1; display: flex; align-items: center; gap: 0.75rem; padding: 0 0.75rem; min-width: 0; position: relative; }
        .jf-pill-field i { font-size: 1.1rem; color: #94a3b8; flex-shrink: 0; }
        .jf-pill-input { width: 100%; border: none !important; background: transparent !important; font-size: 0.875rem; font-weight: 500; color: var(--cd-text); outline: none !important; box-shadow: none !important; cursor: inherit; }
        .jf-pill-input::placeholder { color: #94a3b8; font-weight: 400; }
        [data-theme-mode="dark"] .jf-pill-input, .dark .jf-pill-input { color: #f8fafc !important; }

        .jf-pill-divider { width: 1px; height: 24px; background: var(--cd-border); flex-shrink: 0; }
        [data-theme-mode="dark"] .jf-pill-divider, .dark .jf-pill-divider { background: rgba(255,255,255,0.1) !important; }

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

        /* Status & Bulk Dropdowns inside Pill */
        .jf-p-status { width: auto; flex: none; }
        .jf-p-bulk { width: auto; flex: none; }

        @media (max-width: 1100px) {
            .jf-p-status, .jf-p-bulk { width: auto; flex: 1; }
        }

        /* ── Mobile Toolbar Optimizations ── */
        @media (max-width: 768px) {
            .jf-search-pill {
                height: auto;
                flex-direction: column;
                padding: 1rem;
                border-radius: 24px;
                gap: 0.25rem;
                background: rgba(var(--cd-bg-rgb), 0.8) !important;
                backdrop-filter: blur(10px);
            }
            .jf-pill-divider { display: none; }
            .jf-pill-field {
                width: 100% !important;
                border-bottom: 1px solid rgba(241, 245, 249, 0.5);
                padding: 0.75rem 0.5rem !important;
                height: 54px !important;
            }
            .jf-pill-field:last-of-type { border-bottom: none; }
            .jf-pill-submit {
                width: 100%;
                justify-content: center;
                margin-top: 0.75rem;
                height: 54px !important;
                border-radius: 27px !important;
            }
            [data-theme-mode="dark"] .jf-pill-field, .dark .jf-pill-field { border-color: rgba(255,255,255,0.05) !important; }

            .jf-p-status, .jf-p-bulk { flex: 1 1 100% !important; display: block !important; }
            .jf-pill-select-display { justify-content: center !important; height: 100%; }
        }

        /* ── Mobile KPI Card Optimizations (Isolated) ── */
        @media (max-width: 768px) {
            .jf-archive-card { padding: 1.25rem !important; height: auto !important; }
            .jf-archive-icon {
                position: relative !important;
                top: 0 !important;
                right: 0 !important;
                margin-bottom: 0.75rem !important;
                width: 42px !important;
                height: 42px !important;
                font-size: 1.25rem !important;
            }
            .jf-archive-lbl {
                font-size: 0.7rem !important;
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: clip !important;
                line-height: 1.2 !important;
                margin-bottom: 0.5rem !important;
            }
            .jf-archive-val { font-size: 1.5rem !important; }
            .jf-archive-desc { font-size: 0.7rem !important; }
        }

        /* Selected count row */
        .jf-count-row {
            display: flex;
            justify-content: flex-start;
            padding: 0 1rem;
            margin-top: -0.5rem;
            margin-bottom: 0.5rem;
        }

        /* Custom Pill Dropdowns */
        .jf-pill-select-wrapper { position: relative; width: 100%; cursor: pointer; user-select: none; }
        .jf-pill-select-display { display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; font-size: 0.813rem; font-weight: 500; color: var(--cd-text); padding: 0.5rem 0.75rem; width: 100%; border-radius: 12px; transition: all 0.2s; }
        .jf-pill-select-wrapper:hover .jf-pill-select-display { background: rgba(99,102,241,0.05); color: #6366f1; }
        .jf-pill-select-wrapper.active .jf-pill-select-display { background: #6366f1 !important; color: #ffffff !important; }
        .jf-pill-select-wrapper.active .jf-pill-select-display i { color: #ffffff !important; }
        [data-theme-mode="dark"] .jf-pill-select-display, .dark .jf-pill-select-display { color: #f8fafc !important; }
        [data-theme-mode="dark"] .jf-pill-select-wrapper:hover .jf-pill-select-display, .dark .jf-pill-select-wrapper:hover .jf-pill-select-display { background: rgba(255,255,255,0.05) !important; }
        .jf-pill-select-display i.ri-arrow-down-s-line { font-size: 1rem; color: #94a3b8; transition: transform 0.2s; }
        .jf-pill-select-wrapper.active i.ri-arrow-down-s-line { transform: rotate(180deg); }

        .jf-pill-select-options {
            position: absolute;
            top: 100%;
            left: -12px;
            min-width: calc(100% + 24px);
            background: #ffffff;
            border: 1px solid var(--cd-border);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 4px; /* Small margin for visual gap, pseudo-bridge will handle the space */
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.2s, transform 0.2s;
        }
        /* Create a pseudo-bridge to fill the gap for hover continuity */
        .jf-pill-select-options::before {
            content: '';
            position: absolute;
            top: -15px; /* Oversized to be 100% sure */
            left: 0;
            right: 0;
            height: 15px;
            background: transparent;
        }
        .jf-pill-select-wrapper.active .jf-pill-select-options { display: block; opacity: 1; transform: translateY(0); }
        [data-theme-mode="dark"] .jf-pill-select-options, .dark .jf-pill-select-options { background: #1e293b !important; border-color: rgba(255,255,255,0.1) !important; box-shadow: 0 15px 40px rgba(0,0,0,0.4) !important; }

        .jf-pill-option {
            padding: 0.625rem 0.75rem;
            border-radius: 8px;
            font-size: 0.813rem;
            font-weight: 600;
            color: var(--cd-text-secondary);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer; /* Added */
            white-space: nowrap; /* Added */
        }
        .jf-pill-option.header { color: #64748b; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding-top: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--cd-border); margin-bottom: 0.25rem; cursor: default; justify-content: center; }
        .jf-pill-option.header:hover { background: transparent; color: #64748b; }
        .jf-pill-option:hover { background: #6366f1 !important; color: #ffffff !important; padding-left: 1.25rem !important; font-weight: 700 !important; }
        .jf-pill-option.selected { background: rgba(99,102,241,0.05) !important; color: #4f46e5 !important; }
        [data-theme-mode="dark"] .jf-pill-option.header, .dark .jf-pill-option.header { color: #64748b !important; }
        [data-theme-mode="dark"] .jf-pill-option, .dark .jf-pill-option { color: #cbd5e1 !important; }
        [data-theme-mode="dark"] .jf-pill-option:hover, .dark .jf-pill-option:hover { background: #4f46e5 !important; color: #ffffff !important; padding-left: 1.25rem !important; }
        [data-theme-mode="dark"] .jf-pill-option.selected, .dark .jf-pill-option.selected { background: rgba(99,102,241,0.2) !important; color: #818cf8 !important; }

        .jf-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .jf-table th {
            text-align: left;
            padding: 1rem;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: var(--cd-text);
            background: var(--cd-bg-alt);
            border-bottom: 2px solid var(--cd-border);
        }
        :is([data-theme-mode="dark"], .dark) .jf-table th { color: #94a3b8 !important; background: rgba(255,255,255,0.02) !important; border-bottom: 2px solid rgba(255,255,255,0.05) !important; }

        .jf-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--cd-bg-alt);
            vertical-align: middle;
            color: var(--cd-text-secondary);
        }
        :is([data-theme-mode="dark"], .dark) .jf-table td { border-color: rgba(255,255,255,0.02) !important; color: #cbd5e1 !important; }

        .jf-table tr:last-child td { border-bottom: none; }

        .jf-table tr:hover td { background: var(--cd-bg-alt); }
        :is([data-theme-mode="dark"], .dark) .jf-table tr:hover td { background: rgba(255,255,255,0.02) !important; }

        .jf-job-title {
            font-weight: 700;
            color: var(--cd-text);
            display: block;
            margin-bottom: 2px;
            transition: color 0.2s;
        }
        .jf-job-title:hover { color: var(--cd-accent); }
        :is([data-theme-mode="dark"], .dark) .jf-job-title { color: #f1f5f9 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-job-title:hover { color: #818cf8 !important; }

        .jf-company-sub {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
        }

        /* ── Status Dot Indicators ── */
        .jf-status-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            min-width: 110px;
        }

        .jf-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        /* ── 3-Dots Dropdown ── */
        .jf-action-dropdown {
            position: relative;
            display: inline-flex;
        }

        .jf-action-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: #94a3b8;
            background: transparent;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        .jf-action-btn:hover {
            color: #6366f1;
            background: #f1f5f9;
        }

        .jf-dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            width: 180px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            z-index: 50;
            display: none;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .jf-dropdown-menu.active {
            display: block;
            animation: dropdownFade 0.2s ease-out;
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
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
            color: #475569;
            text-align: left;
            transition: all 0.2s;
            border: none;
            background: transparent;
            cursor: pointer;
            text-decoration: none !important;
        }

        .jf-dropdown-item:hover {
            background: #f8fafc;
            color: #6366f1;
        }

        .jf-dropdown-item.text-danger:hover {
            background: #fef2f2;
            color: #ef4444;
        }

        /* ── Z-Shield Teleportation Guard for Dropdowns ── */
        .jf-z-shield-menu {
            position: fixed;
            background: #ffffff;
            border: 1px solid var(--cd-border);
            border-radius: 12px;
            box-shadow: 0 15px 35px -10px rgba(0,0,0,0.15);
            z-index: 9999;
            padding: 0.5rem;
            min-width: 190px;
            display: none;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.2s, transform 0.2s;
            pointer-events: none;
        }
        .jf-z-shield-menu.active { display: block; opacity: 1; transform: translateY(0); pointer-events: auto; }
        :is([data-theme-mode="dark"], .dark) .jf-z-shield-menu { background: #1e293b !important; border-color: rgba(255,255,255,0.1) !important; box-shadow: 0 20px 50px rgba(0,0,0,0.5) !important; }

        .jf-shield-item {
            width: 100%; display: flex; align-items: center; gap: 0.75rem; padding: 0.625rem 0.875rem;
            border-radius: 8px; font-size: 0.813rem; font-weight: 700; color: #475569;
            transition: all 0.2s; border: none; background: transparent; cursor: pointer; text-align: left;
        }
        .jf-shield-item:hover { background: #f1f5f9; color: #6366f1; }
        :is([data-theme-mode="dark"], .dark) .jf-shield-item { color: #cbd5e1 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-shield-item:hover { background: rgba(255,255,255,0.05) !important; color: #818cf8 !important; }

        .jf-shield-click-guard { position: fixed; inset: 0; z-index: 9998; background: transparent; display: none; }
        .jf-shield-click-guard.active { display: block; }

        /* ── Modern SaaS Pagination ── */
        .cd-pagination .hidden.sm\:flex-1 > div:first-child { display: none !important; }
        .cd-pagination .relative.z-0.inline-flex { box-shadow: none !important; gap: 0.25rem; background: transparent !important; }
        .cd-pagination .relative.inline-flex.items-center, .cd-pagination span.relative.inline-flex.items-center {
            border: none !important;
            border-radius: 12px !important;
            background: transparent !important;
            color: #64748b !important;
            font-weight: 700 !important;
            margin: 0 !important;
            padding: 0.5rem 0.875rem !important;
            transition: all 0.2s ease !important;
        }
        .cd-pagination a.relative.inline-flex.items-center:hover { background: #f1f5f9 !important; color: #6366f1 !important; }
        .cd-pagination span[aria-current="page"] > span { background: #6366f1 !important; color: #ffffff !important; box-shadow: 0 4px 12px -2px rgba(99,102,241,0.4) !important; }
        [data-theme-mode="dark"] .cd-pagination a.relative.inline-flex.items-center:hover, .dark .cd-pagination a.relative.inline-flex.items-center:hover { background: rgba(255,255,255,0.05) !important; color: #f8fafc !important; }
        [data-theme-mode="dark"] .cd-pagination span.relative.inline-flex.items-center, .dark .cd-pagination span.relative.inline-flex.items-center { color: #94a3b8 !important; }
        .cd-pagination nav > p { display: none !important; }

        @media (max-width: 992px) {
            .jf-header-section { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
            .jf-header-title { font-size: 1.875rem; }
            .jf-header-actions { width: 100%; flex-wrap: wrap; }
        }

        /* ── Dark Mode Header Styling ── */
        :is([data-theme-mode="dark"], .dark) .jf-header-section { border-bottom-color: rgba(255,255,255,0.08) !important; background: rgb(30, 32, 35) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-context-label { color: #ffffff !important; }

        :is([data-theme-mode="dark"], .dark) .jf-archive-card {
            background: #1e293b !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-archive-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-archive-trend { color: #64748b !important; }

        :is([data-theme-mode="dark"], .dark) .jf-archive-card:hover {
            background: #1e293b !important;
            border-color: #6366f1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .cd-pipe {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        :is([data-theme-mode="dark"], .dark) .cd-status-pill {
            background: rgba(255, 255, 255, 0.1) !important;
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-table-container {
            background: rgba(30, 41, 59, 0.45) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-pipeline-card {
            background: rgba(30, 41, 59, 0.45) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-table th,
        :is([data-theme-mode="dark"], .dark) .jf-table td {
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #f8fafc !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-table thead th {
            background: rgba(15, 23, 42, 0.5) !important;
            color: #cbd5e1 !important;
        }
    </style>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Modern Minimalist Header --}}
        <x-modern-header :container="false" chip="Career Archives">
            <x-slot name="titleContent"><strong>Application Archive History</strong></x-slot>
            <x-slot name="description">
                Review your completed job applications, withdrawn positions, and past recruitment outcomes within the <b>Applicant Portal</b>.
            </x-slot>
            <x-slot name="actions">
                <a href="{{ route('applicant.applications.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm">
                    <i class="ri-arrow-left-line me-2 text-indigo-500"></i> Active Applications
                </a>
            </x-slot>
        </x-modern-header>

        <div class="grid grid-cols-12 gap-x-5 gap-y-4">
            {{-- ═══ Stats Archive ═══ --}}
            <div class="col-span-12" id="wt-stats">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-2">
                    @php
                        $hStats = [
                            ['key'=>'total', 'label'=>'Total History','count'=>$applications->total(),'icon'=>'ri-history-line','desc'=>'Your complete record of past applications.', 'trend'=>'Total Archived'],
                            ['key'=>'withdrawn', 'label'=>'Withdrawn','count'=>$withdrawnCount ?? 0,'icon'=>'ri-logout-circle-line', 'desc'=>'Applications you chose to retract.', 'trend'=>'No recent changes'],
                            ['key'=>'not-selected', 'label'=>'Not Selected','count'=>$notSelectedCount ?? 0,'icon'=>'ri-close-circle-line', 'desc'=>'Positions where the process ended.', 'trend'=>'No recent changes'],
                            ['key'=>'closed', 'label'=>'Positions Closed','count'=>$closedCount ?? 0,'icon'=>'ri-lock-line', 'desc'=>'Archived listings that are no longer active.', 'trend'=>'Finalized'],
                        ];
                    @endphp
                    @foreach($hStats as $s)
                        <div class="jf-archive-card {{ $s['key'] }}">
                            <div class="jf-archive-icon mb-3">
                                <i class="{{ $s['icon'] }}"></i>
                            </div>
                            <div class="jf-archive-lbl">{{ $s['label'] }}</div>
                            <div class="jf-archive-val">{{ $s['count'] }}</div>
                            <div class="jf-archive-trend none">{{ $s['trend'] }}</div>
                            <div class="jf-archive-desc">{{ $s['desc'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Unified Search & Bulk Pill --}}
            <div class="col-span-12" id="wt-table">
                <div class="jf-search-pill-container" id="wt-toolbar">
                    <div class="jf-search-pill">
                        {{-- Search Field --}}
                        <div class="jf-pill-field">
                            <i class="ri-search-2-line"></i>
                            <input type="text" id="search-input" class="jf-pill-input" placeholder="Search archives...">
                        </div>
                        <div class="jf-pill-divider"></div>

                        {{-- Status Filter (Custom) --}}
                        <div class="jf-pill-field jf-p-status">
                            <div class="jf-pill-select-wrapper" id="status-select-wrapper">
                                <div class="jf-pill-select-display">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="ri-filter-3-line"></i>
                                        <span id="status-display-val">All Statuses</span>
                                    </div>
                                    <i class="ri-arrow-down-s-line"></i>
                                </div>
                                <div class="jf-pill-select-options">
                                    <div class="jf-pill-option selected" data-value="">All Statuses</div>
                                    <div class="jf-pill-option" data-value="withdrawn">Withdrawn</div>
                                    <div class="jf-pill-option" data-value="not selected">Not Selected</div>
                                    <div class="jf-pill-option" data-value="closed">Closed</div>
                                </div>
                                <input type="hidden" id="status-filter" value="">
                            </div>
                        </div>
                        <div class="jf-pill-divider"></div>

                        {{-- Bulk Actions Integrated (Custom) --}}
                        <div class="jf-pill-field jf-p-bulk">
                            <div class="jf-pill-select-wrapper" id="bulk-select-wrapper">
                                <div class="jf-pill-select-display">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="ri-check-double-line"></i>
                                        <span id="bulk-display-val">Bulk Actions</span>
                                    </div>
                                    <i class="ri-arrow-down-s-line"></i>
                                </div>
                                <div class="jf-pill-select-options">
                                    <div class="jf-pill-option header">Bulk Actions</div>
                                    <div class="jf-pill-option" data-value="restore">Restore Active</div>
                                    <div class="jf-pill-option" data-value="delete">Perm. Delete</div>
                                </div>
                                <input type="hidden" id="bulk-action-select" value="">
                            </div>
                        </div>

                        <button type="button" id="search-trigger" class="jf-pill-submit">
                            <i id="trigger-icon" class="ri-search-line"></i>
                            <span id="trigger-text">Search</span>
                        </button>
                    </div>

                    <div class="jf-count-row">
                        <span id="selected-count" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest hidden"
                              style="background: rgba(99, 102, 241, 0.08); padding: 0.4rem 1.25rem; border-radius: 20px; border: 1px solid rgba(99, 102, 241, 0.15);">
                            <span id="selected-count-num" class="text-indigo-600">0</span> applications selected
                        </span>
                    </div>
                </div>

                <div class="jf-table-container jf-pipeline-card cd-section p-5">

                    @if($applications->isEmpty())
                        <div class="jf-empty-state">
                            <div class="jf-empty-icon">
                                <i class="ri-archive-drawer-line"></i>
                            </div>
                            <div class="max-w-md">
                                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">
                                    Your Archive is Ready
                                </h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-6">
                                    Once you withdraw an application or it reaches a final decision, it will move here automatically. Keep track of your entire career journey in one place.
                                </p>
                                <a href="{{ route('applicant.applications.index') }}" class="cd-btn cd-btn-primary">
                                    <i class="ri-briefcase-line me-2"></i> Active Applications
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="jf-table">
                                <thead>
                                    <tr>
                                        <th style="width:50px; text-align:center"><input type="checkbox" id="select-all" class="form-check-input" style="border-radius:4px"></th>
                                        <th>Role Details</th>
                                        <th>Timeline</th>
                                        <th>Archive Status</th>
                                        <th style="width:100px; text-align:center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                        @php
                                            $job = $application->jobPosting;
                                            $status = $application->status;
                                            $statusMeta = [
                                                'applied' => ['color'=>'#6366f1', 'label'=>'Applied', 'bg'=>'rgba(99,102,241,0.1)'],
                                                'submitted' => ['color'=>'#6366f1', 'label'=>'Applied', 'bg'=>'rgba(99,102,241,0.1)'],
                                                'under_review' => ['color'=>'#f59e0b', 'label'=>'Under Review', 'bg'=>'rgba(245,158,11,0.1)'],
                                                'application_viewed' => ['color'=>'#0ea5e9', 'label'=>'Viewed', 'bg'=>'rgba(14,165,233,0.1)'],
                                                'viewed' => ['color'=>'#0ea5e9', 'label'=>'Viewed', 'bg'=>'rgba(14,165,233,0.1)'],
                                                'accepted' => ['color'=>'#10b981', 'label'=>'Accepted', 'bg'=>'rgba(16,185,129,0.1)'],
                                                'withdrawn' => ['color'=>'#eab308', 'label'=>'Withdrawn', 'bg'=>'rgba(234,179,8,0.1)'],
                                                'cancelled' => ['color'=>'#eab308', 'label'=>'Withdrawn', 'bg'=>'rgba(234,179,8,0.1)'],
                                                'declined' => ['color'=>'#ef4444', 'label'=>'Not Selected', 'bg'=>'rgba(239,68,68,0.1)'],
                                                'rejected' => ['color'=>'#ef4444', 'label'=>'Not Selected', 'bg'=>'rgba(239,68,68,0.1)'],
                                                'not_selected' => ['color'=>'#ef4444', 'label'=>'Not Selected', 'bg'=>'rgba(239,68,68,0.1)'],
                                                'no_longer_under_consideration' => ['color'=>'#ef4444', 'label'=>'Not Selected', 'bg'=>'rgba(239,68,68,0.1)'],
                                                'closed' => ['color'=>'#64748b', 'label'=>'Closed', 'bg'=>'rgba(100,116,139,0.1)'],
                                                'expired' => ['color'=>'#64748b', 'label'=>'Closed', 'bg'=>'rgba(100,116,139,0.1)'],
                                            ];
                                            $meta = $statusMeta[$status] ?? ['color'=>'#64748b', 'label'=>Str::headline($status), 'bg'=>'rgba(100,116,139,0.1)'];
                                        @endphp
                                        <tr class="applications-row">
                                            <td style="text-align:center">
                                                <input type="checkbox" class="form-check-input row-checkbox" value="{{ $application->id }}" data-title="{{ $job?->title ?? 'Application' }}" style="border-radius:4px">
                                            </td>
                                            <td>
                                                @if($job)
                                                    <a href="{{ route('jobs.show', $job->slug) }}" class="jf-job-title">{{ $job->title }}</a>
                                                    <div class="jf-company-sub">{{ $job->company?->name ?? 'Company' }}</div>
                                                @else
                                                    <span class="text-sm text-slate-400 italic">Job no longer available</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-sm font-semibold text-slate-700">@if($application->applied_at) {{ $application->applied_at->format('M d, Y') }} @else - @endif</div>
                                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Applied Date</div>
                                            </td>
                                            <td>
                                                <div class="jf-status-wrap" style="background:{{ $meta['bg'] }}; color:{{ $meta['color'] }}">
                                                    <div class="jf-status-dot" style="background:{{ $meta['color'] }}"></div>
                                                    {{ $meta['label'] }}
                                                </div>
                                            </td>
                                            <td style="text-align:center">
                                                <div class="jf-action-dropdown">
                                                    <button type="button" class="jf-action-btn dropdown-toggle-shield"
                                                            onclick="teleportActionMenu(event, '{{ $application->id }}', '{{ $job?->title ?? 'Application' }}', '{{ $job?->slug ?? '' }}')">
                                                        <i class="ri-more-2-fill"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="p-4 border-t border-slate-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                                Rendering {{ $applications->firstItem() }} - {{ $applications->lastItem() }} of {{ $applications->total() }} archived records
                            </div>
                            <div class="cd-pagination">{{ $applications->onEachSide(1)->links() }}</div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- ═══ Action Menu Shield Teleport Root (Moved to absolute bottom) ═══ --}}

        <script>
            function teleportActionMenu(event, id, title, slug) {
                event.preventDefault();
                event.stopPropagation();

                const btn = event.currentTarget;
                const guard = document.getElementById('shield-click-guard');
                const menu = document.getElementById('z-shield-menu');
                const container = document.getElementById('shield-menu-content');

                if (!guard || !menu || !container) return;

                // Populate content
                let html = ``;
                if (slug) {
                    html += `<a href="/jobs/${slug}" class="jf-shield-item"><i class="ri-eye-line text-indigo-500"></i> View Job Profile</a>`;
                }
                html += `<button type="button" class="jf-shield-item" onclick="handleSingleRestore('${id}')"><i class="ri-refresh-line text-emerald-500"></i> Restore to Active</button>`;
                html += `<div style="height:1px; background:rgba(0,0,0,0.05); margin:4px 0;"></div>`;
                html += `<button type="button" class="jf-shield-item text-danger" onclick="handleSingleDelete('${id}', '${title.replace(/'/g, "\\'")}')"><i class="ri-delete-bin-line"></i> Permanent Delete</button>`;

                container.innerHTML = html;

                // Position (Fixed positioning doesn't need scroll offset)
                const rect = btn.getBoundingClientRect();
                const menuWidth = 190;
                let left = rect.right - menuWidth;
                let top = rect.bottom + 8; // Removed window.scrollY because of position:fixed

                // Boundary checks for the viewport
                if (left < 10) left = 10;
                if (left + menuWidth > window.innerWidth - 10) left = window.innerWidth - menuWidth - 10;
                if (top + 150 > window.innerHeight) top = rect.top - 150 - 8; // Open upwards if near bottom

                menu.style.left = `${left}px`;
                menu.style.top = `${top}px`;

                guard.classList.add('active');
                menu.classList.add('active');

                guard.onclick = (e) => {
                    e.stopPropagation();
                    closeShield();
                };
            }

            function handleSingleRestore(id) {
                const row = document.querySelector(`.row-checkbox[value="${id}"]`).closest('tr');
                const restoreBtn = row.querySelector('.bulk-restore-single');
                if (restoreBtn) {
                     restoreBtn.click();
                } else {
                     // If hidden in DOM, construct a form or use bulk logic
                     handleBulkActionRequest('restore', [id]);
                }
                closeShield();
            }

            function handleSingleDelete(id, title) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to permanently delete "${title}". This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/applicant/applications/history/${id}`;
                        form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
                closeShield();
            }

            function closeShield() {
                document.getElementById('shield-click-guard').classList.remove('active');
                document.getElementById('z-shield-menu').classList.remove('active');
            }

            document.addEventListener('DOMContentLoaded', function () {
                // ... rest of shared logic ...
                // Dropdown Logic
                document.addEventListener('click', function(e) {
                    const isDropdownBtn = e.target.closest('.dropdown-toggle-btn');
                    if (!isDropdownBtn) {
                        document.querySelectorAll('.jf-dropdown-menu.active').forEach(m => m.classList.remove('active'));
                        return;
                    }
                    const menu = isDropdownBtn.nextElementSibling;
                    const isActive = menu.classList.contains('active');
                    document.querySelectorAll('.jf-dropdown-menu.active').forEach(m => m.classList.remove('active'));
                    if (!isActive) menu.classList.add('active');
                });

                // Bulk Logic
                const selectAll = document.getElementById('select-all');
                const checkboxes = document.querySelectorAll('.row-checkbox');
                const selectedCount = document.getElementById('selected-count');
                const selectedCountNum = document.getElementById('selected-count-num');

                function updateCount() {
                    const c = document.querySelectorAll('.row-checkbox:checked').length;
                    if (selectedCount) {
                        if (c > 0) {
                            selectedCount.classList.remove('hidden');
                            selectedCountNum.textContent = c;
                        } else {
                            selectedCount.classList.add('hidden');
                        }
                    }
                    // Trigger button text update if function exists
                    if (typeof updateTriggerState === 'function') updateTriggerState();
                }

                if (selectAll) {
                    selectAll.addEventListener('change', () => {
                        checkboxes.forEach(cb => cb.checked = selectAll.checked);
                        updateCount();
                    });
                }

                checkboxes.forEach(cb => {
                    cb.addEventListener('change', () => {
                        if (selectAll) selectAll.checked = document.querySelectorAll('.row-checkbox:checked').length === checkboxes.length;
                        updateCount();
                    });
                });

                // Unified Search & Status Filter
                const searchInput = document.getElementById('search-input');
                const statusFilter = document.getElementById('status-filter');
                const searchTrigger = document.getElementById('search-trigger');
                const triggerText = document.getElementById('trigger-text');
                const bulkSelect = document.getElementById('bulk-action-select');
                const rows = document.querySelectorAll('.applications-row');

                // Custom Dropdown Handling
                document.querySelectorAll('.jf-pill-select-wrapper').forEach(wrapper => {
                    const display = wrapper.querySelector('.jf-pill-select-display');
                    const options = wrapper.querySelectorAll('.jf-pill-option');
                    const hiddenInput = wrapper.querySelector('input[type="hidden"]');
                    const displayVal = wrapper.querySelector('span[id$="-display-val"]');
                    let closeTimer;

                    display.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const isActive = wrapper.classList.contains('active');
                        document.querySelectorAll('.jf-pill-select-wrapper.active').forEach(w => w.classList.remove('active'));
                        if (!isActive) wrapper.classList.add('active');
                    });

                    // Hover to open/show for better discoverability with delay to prevent flicker closure
                    wrapper.addEventListener('mouseenter', () => {
                        if (closeTimer) clearTimeout(closeTimer);
                        document.querySelectorAll('.jf-pill-select-wrapper.active').forEach(w => {
                            if (w !== wrapper) w.classList.remove('active');
                        });
                        wrapper.classList.add('active');
                    });
                    wrapper.addEventListener('mouseleave', () => {
                        closeTimer = setTimeout(() => {
                            wrapper.classList.remove('active');
                        }, 150); // Small delay to bridge the transition gap
                    });

                    options.forEach(opt => {
                        opt.addEventListener('click', (e) => {
                            e.stopPropagation();
                            if (opt.classList.contains('header')) return;

                            const val = opt.getAttribute('data-value');
                            const label = opt.textContent;

                            hiddenInput.value = val;
                            displayVal.textContent = label;

                            options.forEach(o => o.classList.remove('selected'));
                            opt.classList.add('selected');
                            wrapper.classList.remove('active');

                            // Trigger filter or state update
                            if (hiddenInput.id === 'status-filter') runFilter();
                            if (hiddenInput.id === 'bulk-action-select') updateTriggerState();
                        });
                    });
                });

                document.addEventListener('click', () => {
                    document.querySelectorAll('.jf-pill-select-wrapper.active').forEach(w => w.classList.remove('active'));
                });

                function updateTriggerState() {
                    const hasAction = bulkSelect && bulkSelect.value !== '';
                    const hasSelection = document.querySelectorAll('.row-checkbox:checked').length > 0;

                    if (triggerText) {
                        if (hasAction && hasSelection) {
                            triggerText.textContent = 'Apply Action';
                            if (triggerIcon) triggerIcon.className = 'ri-check-line';
                        } else {
                            triggerText.textContent = 'Search';
                            if (triggerIcon) triggerIcon.className = 'ri-search-line';
                        }
                    }
                }

                function runFilter() {
                    if (!searchInput || !rows.length) return;

                    const term = searchInput.value.toLowerCase();
                    const status = statusFilter ? statusFilter.value.toLowerCase() : '';

                    rows.forEach(r => {
                        const text = r.textContent.toLowerCase();
                        const statusBadge = r.querySelector('.jf-status-wrap');
                        const rowStatus = statusBadge ? statusBadge.textContent.toLowerCase().trim() : '';

                        const termMatch = text.includes(term);
                        const statusMatch = !status || rowStatus === status;

                        r.style.display = (termMatch && statusMatch) ? '' : 'none';
                    });

                    updateCount();
                }

                if (searchInput) searchInput.addEventListener('input', runFilter);
                if (statusFilter) statusFilter.addEventListener('change', runFilter);
                if (bulkSelect) {
                    bulkSelect.addEventListener('change', updateTriggerState);
                }

                if (searchTrigger) {
                    searchTrigger.addEventListener('click', () => {
                        const action = bulkSelect ? bulkSelect.value : '';
                        const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);

                        if (action && ids.length > 0) {
                            Swal.fire({
                                title: 'Confirm ' + (action === 'delete' ? 'Deletion' : 'Restoration'),
                                text: `Apply this to ${ids.length} applications?`,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: action === 'delete' ? '#ef4444' : '#6366f1',
                                confirmButtonText: 'Yes, proceed'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    executeAction(action, ids);
                                }
                            });
                        } else {
                            runFilter();
                        }
                    });
                }

                // Action Logic

                document.querySelectorAll('.bulk-restore-single').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.getAttribute('data-id');
                        executeAction('restore', [id]);
                    });
                });

                function executeAction(action, ids) {
                    const url = action === 'delete' ? '{{ route("applicant.applications.history.bulk-delete") }}' : '{{ route("applicant.applications.history.bulk-restore") }}';
                    fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ ids: ids })
                    })
                    .then(r => r.json())
                    .then(() => {
                        Swal.fire({ icon: 'success', title: 'Done', timer: 1500, showConfirmButton: false }).then(() => window.location.reload());
                    })
                    .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Action failed.' }));
                }

                document.querySelectorAll('.history-delete-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const formId = btn.getAttribute('data-delete-form-id');
                        const title = btn.getAttribute('data-job-title');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Permanent Delete?',
                            text: `This will permanently remove "${title}" from your archive.`,
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            confirmButtonText: 'Yes, delete permanently'
                        }).then(r => {
                            if (r.isConfirmed && formId) document.getElementById(formId).submit();
                        });
                    });
                });
            });
        </script>

        @include('applicants.partials.walkthrough', [
            'wtKey' => 'app_history',
            'wtSteps' => [
                ['target' => 'wt-hero', 'icon' => 'ri-history-line', 'title' => 'Career Archives', 'body' => 'This is your secure archive for past, withdrawn, and closed applications. Keeping your records organized.', 'position' => 'bottom'],
                ['target' => 'wt-stats', 'icon' => 'ri-bar-chart-fill', 'title' => 'Overview at a Glance', 'body' => 'Quickly see your total archive stats including withdrawn and closed positions.', 'position' => 'bottom'],
                ['target' => 'wt-toolbar', 'icon' => 'ri-tools-fill', 'title' => 'High-Density Control', 'body' => 'Efficiently search through records or use bulk actions to maintain your personal archives.', 'position' => 'bottom'],
                ['target' => 'wt-table', 'icon' => 'ri-table-fill', 'title' => 'Arc Data Table', 'body' => 'Your history records in a high-density, interactive format. Use the 3-dots menu for per-row actions.', 'position' => 'top'],
            ]
        ])

        </div>

        <div id="action-menu-root">
            <div id="shield-click-guard" class="jf-shield-click-guard"></div>
            <div id="z-shield-menu" class="jf-z-shield-menu">
                <div id="shield-menu-content">
                    {{-- Dynamically populated --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
