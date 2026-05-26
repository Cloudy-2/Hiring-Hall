<x-app-layout>

    <x-slot name="pageTitle">Applicants</x-slot>
    <x-slot name="return">{"link": "/users/manage", "text": "back"}</x-slot>
    <x-slot name="url_1">{"link": "/developer/routes", "text": "Manage Applicants"}</x-slot>
    <x-slot name="active">Details</x-slot>

    @include('candidates.partials.candidate-styles')

    <style>
        /* ── Layout ── */
        .cf-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        /* ── Dark Mode Surface Alignment (Match Search Jobs) ── */
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-page-hero,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-sidebar,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-sidebar-card,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-card {
            background: rgb(30, 32, 35) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-sidebar-header {
            background: rgba(255, 255, 255, 0.03) !important;
            border-bottom-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-sidebar-footer {
            background: rgba(0, 0, 0, 0.2) !important;
            border-top-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-sidebar-footer a {
            background: rgba(255, 255, 255, 0.04) !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-sidebar-footer a:hover {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #f1f5f9 !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-action-btn-cv.disabled {
            background: rgba(148, 163, 184, 0.14) !important;
            color: rgba(203, 213, 225, 0.65) !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-search-btn {
            box-shadow: 0 10px 26px rgba(79, 70, 229, 0.38) !important;
        }

        /* ── Modern Minimalist Header (Search Applicants) ── */
        .cf-header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .cf-header-content {
            flex: 1;
        }

        .cf-header-title {
            font-size: 2.1rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.02em;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .cf-header-desc {
            font-size: 1rem;
            color: #64748b;
            max-width: 700px;
            line-height: 1.5;
            margin: 0;
        }

        .cf-header-desc b {
            color: #6366f1;
            font-weight: 700;
        }

        .cf-header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-header-section {
            border-bottom-color: rgba(255, 255, 255, 0.08) !important;
            background: rgb(30, 32, 35) !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-header-title {
            color: #f8fafc !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-header-desc {
            color: #94a3b8 !important;
        }

        @media (max-width: 992px) {
            .cf-header-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.2rem;
            }

            .cf-header-title {
                font-size: 1.75rem;
            }

            .cf-header-actions {
                width: 100%;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 640px) {
            .cf-header-title {
                font-size: 1.45rem;
            }

            .cf-header-desc {
                font-size: 0.92rem;
            }
        }

        @media (max-width: 900px) {
            .cf-layout { grid-template-columns: 1fr; }
            .cf-sidebar { display: none; }
            .cf-sidebar.mobile-open { display: block; }
        }

        .cf-sidebar { position: sticky; top: 80px; }

        .cf-sidebar-card {
            background: #fff;
            border: 1px solid var(--cd-border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        [data-theme-mode="dark"] .cf-sidebar-card, .dark .cf-sidebar-card {
            background: var(--bodybg, #1a1c2e);
            border-color: rgba(255,255,255,0.08);
        }

        .cf-sidebar-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f3f4f6;
            background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
        }
        [data-theme-mode="dark"] .cf-sidebar-header, .dark .cf-sidebar-header {
            background: rgba(99,102,241,0.08);
            border-bottom-color: rgba(255,255,255,0.08);
        }
        .cf-sidebar-title {
            font-size: 1rem; font-weight: 800; color: var(--cd-text);
            display: flex; align-items: center; gap: 8px;
        }
        .cf-sidebar-title i { color: var(--cd-accent); font-size: 1.1rem; }
        [data-theme-mode="dark"] .cf-sidebar-title, .dark .cf-sidebar-title { color: #f1f5f9; }

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

        .cf-sidebar-body { padding: 1rem 1.25rem; }

        .cf-filter-section {
            padding-bottom: 1rem; margin-bottom: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }
        .cf-filter-section:last-of-type { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        [data-theme-mode="dark"] .cf-filter-section, .dark .cf-filter-section { border-bottom-color: rgba(255,255,255,0.06); }

        .cf-group-label {
            font-size: 0.8125rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.04em;
            color: var(--cd-text-muted); margin-bottom: 0.65rem;
            display: flex; align-items: center; gap: 6px;
        }
        .cf-group-label i { color: var(--cd-accent); font-size: 0.9rem; }
        [data-theme-mode="dark"] .cf-group-label, .dark .cf-group-label { color: #9ca3af; }

        .cf-check { display: flex; align-items: center; gap: 10px; margin-bottom: 7px; cursor: pointer; }
        .cf-check input[type="checkbox"] { width: 17px; height: 17px; flex-shrink: 0; accent-color: var(--cd-accent); cursor: pointer; }
        .cf-check span { font-size: 0.9375rem; color: var(--cd-text-secondary); cursor: pointer; line-height: 1.3; }
        [data-theme-mode="dark"] .cf-check span, .dark .cf-check span { color: #cbd5e1; }
        .cf-check:hover span { color: var(--cd-accent); }

        .cf-sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid #f3f4f6;
            display: flex; flex-direction: column; gap: 0.5rem;
            background: #fafafa;
        }
        [data-theme-mode="dark"] .cf-sidebar-footer, .dark .cf-sidebar-footer {
            border-top-color: rgba(255,255,255,0.06);
            background: rgba(255,255,255,0.02);
        }

        .cf-salary-row { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }
        .cf-salary-input {
            width: 80px; height: 36px; padding: 0 10px;
            border: 1.5px solid var(--cd-border); border-radius: 8px;
            font-size: 0.875rem; background: #fff; color: var(--cd-text);
        }
        .cf-salary-select {
            width: 80px; height: 36px; padding: 0 8px;
            border: 1.5px solid var(--cd-border); border-radius: 8px;
            font-size: 0.8125rem; background: #fff; color: var(--cd-text);
        }
        [data-theme-mode="dark"] .cf-salary-input,
        [data-theme-mode="dark"] .cf-salary-select,
        .dark .cf-salary-input, .dark .cf-salary-select {
            background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.12); color: #e5e7eb;
        }

        .cf-mobile-filter-btn {
            display: none; align-items: center; gap: 8px;
            padding: 0.6rem 1rem; border-radius: 10px;
            border: 1.5px solid var(--cd-border); background: #fff;
            color: var(--cd-text); font-size: 0.9375rem; font-weight: 600;
            cursor: pointer; margin-bottom: 1rem;
        }
        @media (max-width: 900px) { .cf-mobile-filter-btn { display: inline-flex; } }
        [data-theme-mode="dark"] .cf-mobile-filter-btn, .dark .cf-mobile-filter-btn {
            background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.12); color: #d1d5db;
        }

        /* ── Search bar ── */
        .cf-search-bar { display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: stretch; margin-bottom: 1.25rem; }

        .cf-search-field { flex: 2; min-width: 160px; position: relative; }
        .cf-search-field i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 1.1rem; pointer-events: none; }
        .cf-search-input {
            width: 100%; height: 48px; padding: 0 16px 0 42px;
            border: 1.5px solid var(--cd-border); border-radius: 12px;
            font-size: 0.9375rem; background: #fff; color: var(--cd-text);
            transition: all 0.2s; outline: none;
        }
        .cf-search-input:focus { border-color: var(--cd-accent); box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .cf-search-input::placeholder { color: #9ca3af; }
        [data-theme-mode="dark"] .cf-search-input, .dark .cf-search-input {
            background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.12); color: #e5e7eb;
        }
        .cf-search-select {
            flex: 1; min-width: 140px; height: 48px; padding: 0 14px;
            border: 1.5px solid var(--cd-border); border-radius: 12px;
            font-size: 0.9375rem; background: #fff; color: var(--cd-text);
            cursor: pointer; outline: none;
        }
        [data-theme-mode="dark"] .cf-search-select, .dark .cf-search-select {
            background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.12); color: #e5e7eb;
        }
        .cf-search-submit:hover { background: var(--cd-accent-hover); }

        .cf-search-btn {
            height: 48px; padding: 0 1.75rem; border-radius: 12px;
            background: var(--cd-accent); color: #fff;
            font-size: 0.9375rem; font-weight: 700; border: none; cursor: pointer;
            display: inline-flex; align-items: center; gap: 8px;
            transition: all 0.2s; white-space: nowrap;
            box-shadow: 0 4px 10px -2px rgba(79,70,229,0.35);
        }
        .cf-search-btn:hover { background: var(--cd-accent-hover); transform: translateY(-1px); }

        /* ── Unified Search Bar ── */
        .cf-search-wrapper {
            display: flex;
            align-items: stretch;
            background: #fff;
            border: 2px solid var(--cd-border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            width: 100%;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-search-wrapper {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.12);
        }

        .cf-search-group {
            flex: 1;
            display: flex;
            align-items: center;
            position: relative;
            border-right: 1px solid var(--cd-border);
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-search-group {
            border-right-color: rgba(255,255,255,0.1);
        }

        .cf-search-group:last-of-type { border-right: none; }

        .cf-search-group i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            pointer-events: none;
        }

        .cf-search-wrapper .cf-search-input,
        .cf-search-wrapper .cf-search-select {
            width: 100%;
            height: 52px;
            border: none !important;
            border-radius: 0 !important;
            background: transparent !important;
            padding: 0 16px 0 42px;
            font-size: 0.9375rem;
            color: var(--cd-text);
            box-shadow: none !important;
        }

        /* Dark mode text color for inputs */
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-search-wrapper .cf-search-input,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-search-wrapper .cf-search-select {
            color: #e2e8f0 !important;
        }

        /* Dark mode placeholder styling */
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-search-wrapper .cf-search-input::placeholder {
            color: #94a3b8 !important;
        }

        .cf-search-wrapper .cf-search-select {
            padding-left: 16px;
        }

        /* Dark mode styles for search select dropdowns */
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-search-wrapper .cf-search-select {
            background: transparent !important;
            color: #e2e8f0 !important;
            border-color: transparent !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-search-wrapper .cf-search-select option {
            background: rgba(30,41,59,0.9);
            color: #e2e8f0;
        }

        .cf-search-wrapper .cf-search-submit {
            height: 52px;
            padding: 0 1.75rem;
            background: var(--cd-accent);
            color: #fff;
            border: none;
            font-weight: 700;
            font-size: 0.9375rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            border-radius: 0;
        }

        .cf-search-wrapper .cf-search-submit:hover {
            background: var(--cd-accent-hover);
        }

        @media (max-width: 992px) {
            .cf-search-wrapper {
                flex-direction: column;
                border-radius: 12px;
                background: transparent;
                border: none;
                box-shadow: none;
            }
            .cf-search-group {
                background: #fff;
                border: 1.5px solid var(--cd-border);
                border-radius: 10px;
                margin-bottom: 0.5rem;
            }
            :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cf-search-group {
                background: rgba(255,255,255,0.05);
            }
            .cf-search-wrapper .cf-search-submit {
                border-radius: 10px;
                width: 100%;
                justify-content: center;
            }
        }

        /* ── Candidate Cards ── */
        .cf-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }
        @media (max-width: 1400px) { .cf-cards-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) { .cf-cards-grid { grid-template-columns: 1fr; } }

        .cf-card {
            background: #fff;
            border: 1.5px solid var(--cd-border);
            border-radius: 16px; padding: 1.25rem;
            display: flex; flex-direction: column; gap: 0.75rem;
            transition: box-shadow 0.2s, border-color 0.2s, transform 0.2s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .cf-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.09); border-color: #c7d2fe; transform: translateY(-2px); }
        [data-theme-mode="dark"] .cf-card, .dark .cf-card {
            background: var(--bodybg, #1a1c2e); border-color: rgba(255,255,255,0.08);
        }
        [data-theme-mode="dark"] .cf-card:hover, .dark .cf-card:hover { border-color: #818cf8; }

        .cf-card-actions { display: flex; justify-content: flex-end; gap: 0.5rem; }

        .cf-action-btn {
            width: 34px; height: 34px; border-radius: 8px; border: none;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s; font-size: 0.9rem; text-decoration: none;
        }
        .cf-action-btn-cv { background: #ecfeff; color: #0891b2; }
        .cf-action-btn-cv:hover { background: #cffafe; }
        .cf-action-btn-cv.disabled { background: #f3f4f6; color: #d1d5db; cursor: not-allowed; }
        .cf-save-btn { background: #eef2ff; color: #4f46e5; }
        .cf-save-btn:hover { background: #e0e7ff; }
        .cf-save-btn[data-saved="1"] { background: #4f46e5; color: #fff; }
        [data-theme-mode="dark"] .cf-action-btn-cv, .dark .cf-action-btn-cv { background: rgba(8,145,178,0.15); }
        [data-theme-mode="dark"] .cf-save-btn, .dark .cf-save-btn { background: rgba(79,70,229,0.15); }
        [data-theme-mode="dark"] .cf-save-btn[data-saved="1"], .dark .cf-save-btn[data-saved="1"] { background: #4f46e5; color: #fff; }

        .cf-card-identity { display: flex; align-items: center; gap: 1rem; }
        .cf-avatar {
            width: 52px; height: 52px; flex-shrink: 0; border-radius: 50%;
            object-fit: cover; border: 2px solid #e0e7ff;
        }
        [data-theme-mode="dark"] .cf-avatar, .dark .cf-avatar { border-color: rgba(99,102,241,0.3); }
        .cf-name {
            font-size: 1rem; font-weight: 800; color: var(--cd-text);
            text-decoration: none; display: flex; align-items: center; gap: 5px;
        }
        .cf-name:hover { color: var(--cd-accent); }
        [data-theme-mode="dark"] .cf-name, .dark .cf-name { color: #f1f5f9; }
        .cf-title { font-size: 0.875rem; color: var(--cd-text-secondary); margin-top: 2px; }
        .cf-location { font-size: 0.8125rem; color: var(--cd-text-muted); display: flex; align-items: center; gap: 4px; margin-top: 2px; }
        [data-theme-mode="dark"] .cf-title, .dark .cf-title { color: #cbd5e1; }
        [data-theme-mode="dark"] .cf-location, .dark .cf-location { color: #9ca3af; }

        .cf-tags { display: flex; flex-wrap: wrap; gap: 6px; }
        .cf-tag {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 10px; border-radius: 20px;
            font-size: 0.8125rem; font-weight: 600;
        }

        .cf-card-footer {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #f3f4f6;
            margin-top: auto;
        }
        @media (min-width: 1600px) {
            .cf-card-footer {
                flex-direction: row;
                align-items: flex-end;
                justify-content: space-between;
            }
        }
        [data-theme-mode="dark"] .cf-card-footer, .dark .cf-card-footer { border-top-color: rgba(255,255,255,0.06); }

        .cf-pay { font-size: 0.875rem; color: var(--cd-text-secondary); line-height: 1.7; }
        .cf-pay strong { color: var(--cd-text); font-weight: 700; }
        [data-theme-mode="dark"] .cf-pay, .dark .cf-pay { color: #9ca3af; }
        [data-theme-mode="dark"] .cf-pay strong, .dark .cf-pay strong { color: #e5e7eb; }

        .cf-view-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            background: var(--cd-accent);
            color: #fff;
            font-size: 0.875rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
            box-shadow: 0 3px 8px -2px rgba(79,70,229,0.3);
            width: 100%;
        }
        .cf-view-btn:hover { background: var(--cd-accent-hover); color: #fff; transform: translateY(-1px); }

        .cf-card-footer-actions {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
            width: 100%;
        }

        .cf-socials {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cf-results-bar {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1rem; gap: 0.5rem; flex-wrap: wrap;
        }
        .cf-results-count { font-size: 1rem; font-weight: 700; color: var(--cd-text); }
        [data-theme-mode="dark"] .cf-results-count, .dark .cf-results-count { color: #e5e7eb; }

        .cf-socials { display: flex; gap: 4px; flex-wrap: wrap; }
        .cf-social-btn {
            width: 28px; height: 28px; border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.875rem; text-decoration: none; transition: opacity 0.2s;
        }
        .cf-social-btn:hover { opacity: 0.8; }

        /* ── Expertise Group Container ── */
        .expertise-group-container {
            margin-bottom: 0.5rem;
            border: 1px solid #f0f0f0;
            border-radius: 6px;
            overflow: hidden;
        }

        [data-theme-mode="dark"] .expertise-group-container,
        [data-bs-theme="dark"] .expertise-group-container,
        .dark .expertise-group-container,
        html.dark .expertise-group-container {
            border-color: rgba(255, 255, 255, 0.12) !important;
        }

        /* ── Expertise Group Toggle (Dark Mode) ── */
        .expertise-group-toggle {
            background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
            color: #6366f1;
            transition: background 0.2s, box-shadow 0.2s;
        }

        .expertise-group-toggle:hover {
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
        }

        /* ── Expertise Group Content (with max-height collapse) ── */
        .expertise-group-content {
            background: #fff;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        [data-theme-mode="dark"] .expertise-group-toggle,
        [data-bs-theme="dark"] .expertise-group-toggle,
        .dark .expertise-group-toggle,
        html.dark .expertise-group-toggle {
            background: linear-gradient(135deg, #312e81 0%, #27244d 100%) !important;
            color: #a5b4fc !important;
        }

        [data-theme-mode="dark"] .expertise-group-toggle:hover,
        [data-bs-theme="dark"] .expertise-group-toggle:hover,
        .dark .expertise-group-toggle:hover,
        html.dark .expertise-group-toggle:hover {
            background: linear-gradient(135deg, #3730a3 0%, #312e81 100%) !important;
            box-shadow: 0 2px 12px rgba(99, 102, 241, 0.25) !important;
        }

        /* ── Expertise Group Content (Dark Mode) ── */
        [data-theme-mode="dark"] .expertise-group-content,
        [data-bs-theme="dark"] .expertise-group-content,
        .dark .expertise-group-content,
        html.dark .expertise-group-content {
            background: #1e293b !important;
            color: #e2e8f0;
        }

        /* ── Expertise Group Border (Dark Mode) ── */
        [data-theme-mode="dark"] .expertise-group-toggle,
        [data-bs-theme="dark"] .expertise-group-toggle,
        .dark .expertise-group-toggle,
        html.dark .expertise-group-toggle {
            border-color: rgba(255, 255, 255, 0.12) !important;
        }

        /* ── Counter Badge (Light & Dark Mode) ── */
        .expertise-group-toggle span span {
            background: #6366f1 !important;
            color: white !important;
        }

        [data-theme-mode="dark"] .expertise-group-toggle span span,
        [data-bs-theme="dark"] .expertise-group-toggle span span,
        .dark .expertise-group-toggle span span,
        html.dark .expertise-group-toggle span span {
            background: #818cf8 !important;
            color: #0f172a !important;
        }

        @keyframes cf-spin { to { transform: rotate(360deg); } }
    </style>

    {{-- ═══ Modern Minimalist Header ═══ --}}
    <x-modern-header
        chip="Applicant Search"
        title="Browse Applicants"
        desc='Find your next <b>virtual assistant</b> from our pool of pre-screened candidates. Use search and filters to quickly narrow down the right fit.'
    />

    {{-- Mobile filter toggle --}}
    @php
        $activeFilterCount = count(array_filter([
            request('expertise'), request('availability'), request('languages'),
            request('job_type'), request('salary_min'), request('salary_max'),
        ]));
    @endphp
    <button type="button" class="cf-mobile-filter-btn" onclick="toggleMobileFilters()">
        <i class="ri-equalizer-line"></i> Filters
        @if($activeFilterCount > 0)
            <span style="background:var(--cd-accent);color:#fff;border-radius:20px;padding:1px 8px;font-size:0.8rem">
                {{ $activeFilterCount }}
            </span>
        @endif
    </button>

    {{-- ═══ Two-column layout ═══ --}}
    <div class="cf-layout max-w-7xl mx-auto pb-4 px-4 sm:px-6 lg:px-8 !pt-0">

        {{-- ══ LEFT SIDEBAR: Filters ══ --}}
        <aside class="cf-sidebar" id="wt-filters">
            <div class="cf-sidebar-card">
                <div class="cf-sidebar-header">
                    <span class="cf-sidebar-title"><i class="ri-equalizer-line"></i> Filters</span>
                    @if($activeFilterCount > 0)
                        <span class="cf-active-chip">{{ $activeFilterCount }} active</span>
                    @endif
                </div>

                <form method="GET" action="{{ route('applicants') }}" id="cf-filter-form">
                    {{-- Preserve search bar values --}}
                    <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                    <input type="hidden" name="location" value="{{ request('location') }}">
                    <input type="hidden" name="role" value="{{ request('role') }}">
                    <input type="hidden" name="experience" value="{{ request('experience') }}">

                    <div class="cf-sidebar-body">

                        {{-- Categories (Collapsible Groups) --}}
                        <div class="cf-filter-section">
                            <div class="cf-group-label"><i class="ri-apps-line"></i> Job roles</div>
                            @php 
                                $expertiseFilter = $expertiseFilter ?? [];
                                // Group expertise categories by prefix for better organization
                                $categoryGroups = [
                                    'Administrative & Operations' => ['admin_asst', 'exec_asst', 'ops_mgr', 'ops_coord', 'office_mgr', 'va_general', 'data_entry', 'doc_controller'],
                                    'Finance & Accounting' => ['accountant', 'bookkeeper', 'financial_analyst', 'ap_specialist', 'ar_specialist', 'payroll_specialist', 'audit_assistant', 'tax_specialist'],
                                    'Customer Support' => ['csr', 'support_specialist', 'tech_support', 'helpdesk', 'client_success', 'call_center'],
                                    'Sales & Marketing' => ['sales_rep', 'sales_exec', 'account_mgr', 'bdr', 'digital_marketing', 'social_media', 'content_writer', 'seo_specialist'],
                                    'Human Resources' => ['hr_assistant', 'hr_generalist', 'recruiter', 'talent_acq', 'training_coord'],
                                    'IT & Technical' => ['software_dev', 'web_dev', 'frontend_dev', 'backend_dev', 'fullstack_dev', 'qa_tester', 'it_support', 'sys_admin'],
                                    'Project & Coordination' => ['project_coord', 'project_mgr', 'scrum_master', 'product_mgr'],
                                    'Creative & Design' => ['graphic_designer', 'uiux_designer', 'video_editor', 'multimedia'],
                                    'E-commerce & Admin' => ['ecommerce', 'shopify_mgr', 'product_listing', 'inventory_coord'],
                                    'Specialized Virtual Roles' => ['re_va', 'medical_va', 'legal_va', 'exec_va', 'marketing_va'],
                                ];
                                $allOptions = collect($dropdownOptions['expertise_category'] ?? []);
                            @endphp

                            <div style="max-height: 500px; overflow-y: auto;">
                                @foreach($categoryGroups as $groupName => $groupValues)
                                    @php
                                        // Count how many in this group are selected
                                        $groupOptions = $allOptions->whereIn('value', $groupValues)->sortBy('sort_order');
                                        $selectedCount = $groupOptions->filter(fn($opt) => in_array($opt->value, $expertiseFilter))->count();
                                        $groupId = 'expertise_' . str_replace(' ', '_', strtolower($groupName));
                                    @endphp
                                    <div class="expertise-group-container">
                                        <button type="button" class="expertise-group-toggle" id="{{ $groupId }}_btn" onclick="toggleExpertiseGroup('{{ $groupId }}')" style="width: 100%; padding: 0.625rem 0.75rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: space-between; font-size: 0.875rem; font-weight: 600; transition: background 0.2s;">
                                            <span style="display: flex; align-items: center; gap: 8px;">
                                                <i class="ri-arrow-right-s-line expertise-toggle-icon" style="transition: transform 0.2s;" id="{{ $groupId }}_icon"></i>
                                                {{ $groupName }}
                                                @if($selectedCount > 0)
                                                    <span style="border-radius: 12px; padding: 2px 6px; font-size: 0.65rem; font-weight: 700;">{{ $selectedCount }}</span>
                                                @endif
                                            </span>
                                        </button>
                                        <div class="expertise-group-content" id="{{ $groupId }}_content" style="max-height: 0; overflow: hidden; padding: 0; transition: max-height 0.3s ease, padding 0.3s ease;" data-collapsed="true">
                                            @foreach($groupOptions as $option)
                                                <label class="cf-check" style="margin-bottom: 0.5rem;">
                                                    <input type="checkbox" name="expertise[]" value="{{ $option->value }}"
                                                        {{ in_array($option->value, $expertiseFilter) ? 'checked' : '' }}>
                                                    <span style="font-size: 0.875rem;">{{ $option->label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <script>
                                function toggleExpertiseGroup(groupId) {
                                    const content = document.getElementById(groupId + '_content');
                                    const icon = document.getElementById(groupId + '_icon');
                                    const isCollapsed = content.getAttribute('data-collapsed') === 'true';
                                    
                                    if (isCollapsed) {
                                        // Expand
                                        content.style.maxHeight = '5000px';
                                        content.style.padding = '0.625rem 0.75rem';
                                        content.setAttribute('data-collapsed', 'false');
                                        icon.style.transform = 'rotate(90deg)';
                                        localStorage.setItem('expertise_group_' + groupId, 'open');
                                    } else {
                                        // Collapse
                                        content.style.maxHeight = '0';
                                        content.style.padding = '0';
                                        content.setAttribute('data-collapsed', 'true');
                                        icon.style.transform = 'rotate(0deg)';
                                        localStorage.setItem('expertise_group_' + groupId, 'closed');
                                    }
                                }

                                // Initialize collapsed state on page load (default: collapsed)
                                document.addEventListener('DOMContentLoaded', function() {
                                    const groups = document.querySelectorAll('.expertise-group-content');
                                    groups.forEach(group => {
                                        const groupId = group.id.replace('_content', '');
                                        const state = localStorage.getItem('expertise_group_' + groupId);
                                        const icon = document.getElementById(groupId + '_icon');
                                        
                                        if (state === 'open') {
                                            // Restore to open state
                                            group.style.maxHeight = '5000px';
                                            group.style.padding = '0.625rem 0.75rem';
                                            group.setAttribute('data-collapsed', 'false');
                                            if (icon) icon.style.transform = 'rotate(90deg)';
                                        } else {
                                            // Default: keep collapsed
                                            group.style.maxHeight = '0';
                                            group.style.padding = '0';
                                            group.setAttribute('data-collapsed', 'true');
                                            if (icon) icon.style.transform = 'rotate(0deg)';
                                        }
                                    });
                                });
                            </script>
                        </div>

                        {{-- Availability --}}
                        <div class="cf-filter-section">
                            <div class="cf-group-label"><i class="ri-calendar-check-line"></i> Availability</div>
                            @php $availabilityFilter = $availabilityFilter ?? []; @endphp
                            @foreach(($dropdownOptions['availability'] ?? []) as $option)
                                <label class="cf-check">
                                    <input type="checkbox" name="availability[]" value="{{ $option->value }}"
                                        {{ in_array($option->value, $availabilityFilter) ? 'checked' : '' }}>
                                    <span>{{ $option->label }}</span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Languages --}}
                        <div class="cf-filter-section">
                            <div class="cf-group-label"><i class="ri-translate-2"></i> Languages</div>
                            @php $languagesFilter = $languagesFilter ?? []; @endphp
                            @foreach(($dropdownOptions['language'] ?? []) as $option)
                                <label class="cf-check">
                                    <input type="checkbox" name="languages[]" value="{{ $option->label }}"
                                        {{ in_array($option->label, $languagesFilter) ? 'checked' : '' }}>
                                    <span>{{ $option->label }}</span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Job Type --}}
                        <div class="cf-filter-section">
                            <div class="cf-group-label"><i class="ri-briefcase-line"></i> Job Type</div>
                            @php $jobTypeFilter = $jobTypeFilter ?? []; @endphp
                            @foreach(($dropdownOptions['job_type'] ?? []) as $option)
                                <label class="cf-check">
                                    <input type="checkbox" name="job_type[]" value="{{ $option->label }}"
                                        {{ in_array($option->label, $jobTypeFilter) ? 'checked' : '' }}>
                                    <span>{{ $option->label }}</span>
                                </label>
                            @endforeach
                        </div>

                        {{-- Expected Salary --}}
                        <div class="cf-filter-section">
                            <div class="cf-group-label"><i class="ri-money-dollar-circle-line"></i> Expected Salary</div>
                            @php
                                $salaryMin = $salaryMin ?? null;
                                $salaryMax = $salaryMax ?? null;
                                $selectedCurrency = request('salary_currency', 'USD');
                            @endphp
                            <div class="cf-salary-row">
                                <input type="number" name="salary_min" class="cf-salary-input" placeholder="Min" value="{{ $salaryMin }}">
                                <span style="color:var(--cd-text-muted);font-size:0.8rem">—</span>
                                <input type="number" name="salary_max" class="cf-salary-input" placeholder="Max" value="{{ $salaryMax }}">
                                <select name="salary_currency" class="cf-salary-select">
                                    @if(!empty($dropdownOptions['currency']))
                                        @foreach(($dropdownOptions['currency']) as $option)
                                            <option value="{{ $option->value }}" {{ $selectedCurrency === $option->value ? 'selected' : '' }}>{{ $option->label }}</option>
                                        @endforeach
                                    @else
                                        <option value="USD" {{ $selectedCurrency === 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="PHP" {{ $selectedCurrency === 'PHP' ? 'selected' : '' }}>PHP</option>
                                        <option value="EUR" {{ $selectedCurrency === 'EUR' ? 'selected' : '' }}>EUR</option>
                                        <option value="GBP" {{ $selectedCurrency === 'GBP' ? 'selected' : '' }}>GBP</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="cf-sidebar-footer">
                        <button type="submit" class="cf-search-btn" style="height:40px;padding:0 1.25rem;font-size:0.9rem;width:100%;justify-content:center">
                            <i class="ri-check-line"></i> Apply Filters
                        </button>
                        <a href="{{ route('applicants', array_filter(['keyword' => request('keyword'), 'location' => request('location'), 'role' => request('role'), 'experience' => request('experience')])) }}"
                            style="height:40px;border-radius:10px;border:1.5px solid var(--cd-border);background:transparent;color:var(--cd-text-secondary);font-size:0.875rem;font-weight:600;display:flex;align-items:center;justify-content:center;gap:6px;text-decoration:none;transition:all 0.2s">
                            <i class="ri-refresh-line"></i> Reset Filters
                        </a>
                    </div>
                </form>
            </div>
        </aside>

        {{-- ══ RIGHT MAIN CONTENT ══ --}}
        <div>

            {{-- ═══ Search Bar ═══ --}}
            <div id="wt-search-bar">
                <form method="GET" action="{{ route('applicants') }}">
                    {{-- Preserve sidebar filters --}}
                    @foreach((array) request()->input('availability', []) as $value)
                        <input type="hidden" name="availability[]" value="{{ $value }}">
                    @endforeach
                    @foreach((array) request()->input('job_type', []) as $value)
                        <input type="hidden" name="job_type[]" value="{{ $value }}">
                    @endforeach
                    @foreach((array) request()->input('expertise', []) as $value)
                        <input type="hidden" name="expertise[]" value="{{ $value }}">
                    @endforeach
                    @foreach((array) request()->input('languages', []) as $value)
                        <input type="hidden" name="languages[]" value="{{ $value }}">
                    @endforeach
                    @if(request()->filled('salary_min'))
                        <input type="hidden" name="salary_min" value="{{ request('salary_min') }}">
                    @endif
                    @if(request()->filled('salary_max'))
                        <input type="hidden" name="salary_max" value="{{ request('salary_max') }}">
                    @endif
                    @if(request()->filled('salary_currency'))
                        <input type="hidden" name="salary_currency" value="{{ request('salary_currency') }}">
                    @endif

                    <div class="cf-search-wrapper">
                        <div class="cf-search-group" style="flex:3">
                            <i class="ri-search-line"></i>
                            <input name="keyword" type="text" class="cf-search-input"
                                placeholder="Search by name, role, skill…"
                                value="{{ request('keyword', $searchKeyword ?? '') }}">
                        </div>
                        <div class="cf-search-group" style="flex:2">
                            <i class="ri-map-pin-line"></i>
                            <input name="location" type="text" class="cf-search-input"
                                placeholder="Location"
                                value="{{ request('location', $searchLocation ?? '') }}">
                        </div>
                        <div class="cf-search-group" style="flex:1.5">
                            <select name="role" class="cf-search-select">
                                @php $selectedRole = request('role'); @endphp
                                <option value="" @if(!$selectedRole) selected @endif>All Roles</option>
                                @foreach(($dropdownOptions['applicant_title'] ?? []) as $option)
                                    <option value="{{ $option->value }}" @if($selectedRole === $option->value) selected @endif>
                                        {{ $option->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cf-search-group" style="flex:1.5">
                            <select name="experience" class="cf-search-select">
                                @php $currentExperience = request('experience', $experienceRange ?? ''); @endphp
                                <option value="" @if(!$currentExperience) selected @endif>Any Experience</option>
                                <option value="0-1" @if($currentExperience === '0-1') selected @endif>0–1 Year</option>
                                <option value="1-2" @if($currentExperience === '1-2') selected @endif>1–2 Years</option>
                                <option value="2-3" @if($currentExperience === '2-3') selected @endif>2–3 Years</option>
                                <option value="3-5" @if($currentExperience === '3-5') selected @endif>3–5 Years</option>
                                <option value="5+" @if($currentExperience === '5+') selected @endif>5+ Years</option>
                            </select>
                        </div>
                        <button type="submit" class="cf-search-submit"><i class="ri-search-line"></i> Search</button>
                    </div>
                </form>
            </div>

            {{-- ═══ Results count ═══ --}}
            @php $totalCandidates = $total ?? (isset($profiles) ? $profiles->total() : 0); @endphp
            <!-- <div class="cf-results-bar">
                <span class="cf-results-count">
                    <i class="ri-user-search-line me-1" style="color:var(--cd-accent)"></i>
                    {{ $totalCandidates }} Applicant{{ $totalCandidates !== 1 ? 's' : '' }}
                </span>
            </div> -->

            {{-- ═══ Candidate Cards Grid ═══ --}}
            @php
                if (isset($profiles)) {
                    $candidates = $profiles->map(function ($profile) use ($savedApplicantIds) {
                        $user = $profile->user;
                        $name = $profile->display_name ?? ($user?->name ?? 'Applicant');
                        $parseJson = function ($value) {
                            if (empty($value)) { return []; }
                            $decoded = json_decode((string) $value, true);
                            return is_array($decoded) ? array_values(array_filter(array_map('trim', $decoded))) : [];
                        };
                        $avatar = ($user && !empty($user->profile_photo_path))
                            ? $user->profile_photo_url
                            : 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($name).'&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981';
                        return [
                            'id'           => $profile->id,
                            'name'         => $name,
                            'avatar'       => $avatar,
                            'title'        => $profile->job_title ?? $profile->title ?? 'Virtual Assistant',
                            'location'     => $profile->location ?? 'Location not set',
                            'degree'       => $profile->degree ?? "Bachelor's Degree",
                            'work_mode'    => $profile->work_mode ?? 'Remote Work',
                            'experience'   => $profile->years_experience ? $profile->years_experience.' Years Experience' : null,
                            'schedule'     => $profile->availability ?? null,
                            'job_type'     => $profile->job_type ?? null,
                            'expertise'    => $parseJson($profile->expertise_categories),
                            'skills'       => $parseJson($profile->skills),
                            'pay_min'      => $profile->expected_salary_min ? ($profile->salary_currency ?? 'USD').' '.number_format((float)$profile->expected_salary_min) : null,
                            'pay_max'      => $profile->expected_salary_max ? ($profile->salary_currency ?? 'USD').' '.number_format((float)$profile->expected_salary_max) : null,
                            'languages'    => $parseJson($profile->languages) ?: ['English'],
                            'rating'       => (float)($profile->rating ?? 0),
                            'rating_count' => (int)($profile->rating_count ?? 0),
                            'verified'     => (bool)($profile->verified ?? false),
                            'cv_path'      => $profile->cv_path ?? null,
                            'is_saved'     => in_array($profile->id, $savedApplicantIds ?? []),
                            'detail_url'   => route('applicants.details', ['applicant' => $profile->id]),
                            'cv_url'       => $profile->cv_path ? route('applicants.download-cv', ['applicant' => $profile->id]) : null,
                            'social' => [
                                'facebook'  => $user?->social_facebook ?? null,
                                'twitter'   => $user?->social_twitter ?? null,
                                'instagram' => $user?->social_instagram ?? null,
                                'github'    => $user?->social_github ?? null,
                                'youtube'   => $user?->social_youtube ?? null,
                            ],
                        ];
                    })->all();
                } else {
                    $candidates = [];
                }
            @endphp

            <div class="cf-cards-grid" id="wt-results">
                @foreach ($candidates as $candidate)
                    @php $isSaved = $candidate['is_saved']; @endphp
                    <div class="cf-card" style="opacity:0;transform:translateY(16px);transition:opacity 0.35s ease,transform 0.35s ease">

                        {{-- Actions --}}
                        <div class="cf-card-actions">
                            <div class="hs-tooltip ti-main-tooltip [--placement:top]">
                                <a href="{{ $candidate['cv_url'] ?? 'javascript:void(0);' }}"
                                    class="cf-action-btn cf-action-btn-cv {{ !$candidate['cv_url'] ? 'disabled' : '' }}"
                                    title="{{ $candidate['cv_url'] ? 'View Resume' : 'No CV uploaded' }}">
                                    <i class="ri-download-cloud-line"></i>
                                </a>
                            </div>
                            <button type="button"
                                class="cf-action-btn cf-save-btn save-candidate-btn"
                                data-saved="{{ $isSaved ? '1' : '0' }}"
                                data-candidate-id="{{ $candidate['id'] }}"
                                title="{{ $isSaved ? 'Saved' : 'Save Applicant' }}">
                                <i class="{{ $isSaved ? 'ri-thumb-up-fill' : 'ri-thumb-up-line' }}"></i>
                            </button>
                        </div>

                        {{-- Identity --}}
                        <div class="cf-card-identity">
                            <img src="{{ $candidate['avatar'] }}" alt="{{ $candidate['name'] }}" class="cf-avatar">
                            <div>
                                <a href="{{ $candidate['detail_url'] }}" class="cf-name">
                                    {{ $candidate['name'] }}
                                    @if($candidate['verified'])
                                        <span style="color:#4f46e5;font-size:0.9rem" title="Verified"><i class="ri-verified-badge-fill"></i></span>
                                    @endif
                                </a>
                                <div class="cf-title">{{ $candidate['title'] }}</div>
                                <div class="cf-location"><i class="ri-map-pin-line"></i>{{ $candidate['location'] }}</div>
                            </div>
                        </div>

                        {{-- Rating --}}
                        <div style="display:flex;align-items:center;flex-wrap:wrap;gap:0.5rem;font-size:0.875rem">
                            <span style="color:var(--cd-text-muted)">Rating:</span>
                            <div class="candidate-rating-stars" style="display:inline-flex;gap:2px;cursor:pointer"
                                data-candidate-id="{{ $candidate['id'] }}"
                                data-rating="{{ $candidate['rating'] }}">
                                @php $avgRating = $candidate['rating']; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($avgRating))
                                        <span class="star-btn" data-star="{{ $i }}" style="color:#f59e0b;font-size:1rem"><i class="bi bi-star-fill"></i></span>
                                    @elseif($i - 0.5 <= $avgRating)
                                        <span class="star-btn" data-star="{{ $i }}" style="color:#f59e0b;font-size:1rem"><i class="bi bi-star-half"></i></span>
                                    @else
                                        <span class="star-btn" data-star="{{ $i }}" style="color:#f59e0b;font-size:1rem"><i class="bi bi-star"></i></span>
                                    @endif
                                @endfor
                            </div>
                            <span class="candidate-rating-count" data-candidate-id="{{ $candidate['id'] }}" style="color:var(--cd-text-muted);font-size:0.8125rem">
                                (<span class="rating-count-value">{{ $candidate['rating_count'] }}</span>)
                            </span>
                        </div>

                        {{-- Tags --}}
                        <div class="cf-tags">
                            @if($candidate['degree'])
                                <span class="cf-tag" style="background:#eef2ff;color:#4f46e5"><i class="ri-file-text-line"></i>{{ $candidate['degree'] }}</span>
                            @endif
                            @if($candidate['work_mode'])
                                <span class="cf-tag" style="background:#ecfdf5;color:#059669"><i class="ri-remote-control-line"></i>{{ $candidate['work_mode'] }}</span>
                            @endif
                            @if($candidate['experience'])
                                <span class="cf-tag" style="background:#fff7ed;color:#d97706"><i class="ri-time-line"></i>{{ $candidate['experience'] }}</span>
                            @endif
                            @if($candidate['schedule'])
                                <span class="cf-tag" style="background:#fdf4ff;color:#9333ea"><i class="ri-time-fill"></i>{{ $candidate['schedule'] }}</span>
                            @endif
                            @if($candidate['job_type'])
                                <span class="cf-tag" style="background:#f0fdf4;color:#16a34a"><i class="ri-briefcase-2-line"></i>{{ $candidate['job_type'] }}</span>
                            @endif
                            @foreach(array_slice($candidate['expertise'], 0, 2) as $exp)
                                <span class="cf-tag" style="background:#fffbeb;color:#b45309"><i class="ri-hashtag"></i>{{ $exp }}</span>
                            @endforeach
                        </div>

                        {{-- Footer --}}
                        <div class="cf-card-footer">
                            <div class="cf-pay">
                                <div>
                                    Salary:
                                    @if($candidate['pay_min'] && $candidate['pay_max'])
                                        <strong>{{ $candidate['pay_min'] }}</strong> – <strong>{{ $candidate['pay_max'] }}</strong>
                                    @elseif($candidate['pay_min'])
                                        <strong>{{ $candidate['pay_min'] }}</strong>
                                    @else
                                        <em style="color:var(--cd-text-muted)">Not specified</em>
                                    @endif
                                </div>
                                <div>Languages: <strong>{{ implode(', ', $candidate['languages']) }}</strong></div>
                            </div>
                            <div class="cf-card-footer-actions">
                                <a href="{{ $candidate['detail_url'] }}" class="cf-view-btn">
                                    View Profile <i class="ri-arrow-right-line"></i>
                                </a>
                                @php $social = $candidate['social']; @endphp
                                @if(count(array_filter($social)) > 0)
                                    <div class="cf-socials">
                                        @if(!empty($social['facebook']))
                                            <a href="{{ $social['facebook'] }}" target="_blank" class="cf-social-btn" style="background:#1877f2;color:#fff"><i class="ri-facebook-line"></i></a>
                                        @endif
                                        @if(!empty($social['twitter']))
                                            <a href="{{ $social['twitter'] }}" target="_blank" class="cf-social-btn" style="background:#14171a;color:#fff"><i class="ri-twitter-x-line"></i></a>
                                        @endif
                                        @if(!empty($social['instagram']))
                                            <a href="{{ $social['instagram'] }}" target="_blank" class="cf-social-btn" style="background:#e1306c;color:#fff"><i class="ri-instagram-line"></i></a>
                                        @endif
                                        @if(!empty($social['github']))
                                            <a href="{{ $social['github'] }}" target="_blank" class="cf-social-btn" style="background:#24292e;color:#fff"><i class="ri-github-line"></i></a>
                                        @endif
                                        @if(!empty($social['youtube']))
                                            <a href="{{ $social['youtube'] }}" target="_blank" class="cf-social-btn" style="background:#ff0000;color:#fff"><i class="ri-youtube-line"></i></a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Empty state --}}
            @if(count($candidates) === 0)
                <div class="cd-section">
                    <div class="cd-empty">
                        <i class="ri-user-search-line"></i>
                        <p>No applicants found matching your search criteria.</p>
                        <a href="{{ route('applicants') }}" class="cd-btn cd-btn-outline" style="font-size:1rem;padding:0.6rem 1.5rem">
                            <i class="ri-refresh-line me-1"></i> Clear Filters
                        </a>
                    </div>
                </div>
            @endif

            {{-- Load More Button --}}
            <div id="cf-load-more-container" style="text-align:center; padding: 2rem 0; display: {{ ($hasMore ?? false) ? 'block' : 'none' }};">
                <button type="button" id="cf-load-more-btn" class="cf-search-btn" style="padding: 0 2rem; height: 48px; border-radius: 12px; font-size: 1rem;">
                    Load More Applicants
                </button>
            </div>

            {{-- Load more spinner --}}
            <div id="cf-loader" style="display:none;text-align:center;padding:2rem 0">
                <div style="display:inline-flex;align-items:center;gap:10px;color:var(--cd-text-muted);font-size:1rem">
                    <svg style="width:22px;height:22px;animation:cf-spin 0.8s linear infinite" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="31.4" stroke-dashoffset="10" stroke-linecap="round"/>
                    </svg>
                    Loading more applicants…
                </div>
            </div>

            {{-- End of results --}}
            <div id="cf-end" style="display:none;text-align:center;padding:1.5rem 0">
                <span style="color:var(--cd-text-muted);font-size:0.9375rem">
                    <i class="ri-checkbox-circle-line me-1" style="color:#22c55e"></i>
                    You've seen all <strong id="cf-total-label">{{ $totalCandidates }}</strong> applicants
                </span>
            </div>

        </div>{{-- end right column --}}
    </div>{{-- end .cf-layout --}}

    {{-- ═══ Scripts ═══ --}}
    <script>
        function toggleMobileFilters() {
            const sidebar = document.getElementById('wt-filters');
            if (sidebar) { sidebar.classList.toggle('mobile-open'); }
        }

        // ── Animate initial cards ──
        document.querySelectorAll('.cf-card').forEach(function (card) {
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    card.style.opacity   = '1';
                    card.style.transform = 'translateY(0)';
                });
            });
        });

        // ── Infinite Scroll ──
        (function () {
            let currentPage = {{ isset($profiles) ? $profiles->currentPage() : 1 }};
            let hasMore     = {{ ($hasMore ?? false) ? 'true' : 'false' }};
            let loading     = false;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const container  = document.getElementById('wt-results');
            const loader     = document.getElementById('cf-loader');
            const endMsg     = document.getElementById('cf-end');
            const totalLabel = document.getElementById('cf-total-label');
            const loadMoreBtnContainer = document.getElementById('cf-load-more-container');
            const loadMoreBtn = document.getElementById('cf-load-more-btn');

            function escHtml(str) {
                return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            }

            function buildStars(avg) {
                let html = '<div style="display:inline-flex;gap:2px;">';
                for (let i = 1; i <= 5; i++) {
                    const cls = i <= Math.floor(avg) ? 'bi-star-fill' : (i - 0.5 <= avg ? 'bi-star-half' : 'bi-star');
                    html += `<span style="color:#f59e0b;font-size:1rem"><i class="bi ${cls}"></i></span>`;
                }
                return html + '</div>';
            }

            function buildTags(c) {
                let html = '';
                if (c.degree)     { html += `<span class="cf-tag" style="background:#eef2ff;color:#4f46e5"><i class="ri-file-text-line"></i>${escHtml(c.degree)}</span>`; }
                if (c.work_mode)  { html += `<span class="cf-tag" style="background:#ecfdf5;color:#059669"><i class="ri-remote-control-line"></i>${escHtml(c.work_mode)}</span>`; }
                if (c.experience) { html += `<span class="cf-tag" style="background:#fff7ed;color:#d97706"><i class="ri-time-line"></i>${escHtml(c.experience)}</span>`; }
                if (c.schedule)   { html += `<span class="cf-tag" style="background:#fdf4ff;color:#9333ea"><i class="ri-time-fill"></i>${escHtml(c.schedule)}</span>`; }
                if (c.job_type)   { html += `<span class="cf-tag" style="background:#f0fdf4;color:#16a34a"><i class="ri-briefcase-2-line"></i>${escHtml(c.job_type)}</span>`; }
                (c.expertise ?? []).slice(0, 2).forEach(exp => {
                    html += `<span class="cf-tag" style="background:#fffbeb;color:#b45309"><i class="ri-hashtag"></i>${escHtml(exp)}</span>`;
                });
                return html;
            }

            function buildSocials(social) {
                const map = [
                    { key: 'facebook',  icon: 'ri-facebook-line',  bg: '#1877f2' },
                    { key: 'twitter',   icon: 'ri-twitter-x-line', bg: '#14171a' },
                    { key: 'instagram', icon: 'ri-instagram-line',  bg: '#e1306c' },
                    { key: 'github',    icon: 'ri-github-line',     bg: '#24292e' },
                    { key: 'youtube',   icon: 'ri-youtube-line',    bg: '#ff0000' },
                ];
                const items = map.filter(s => social?.[s.key]);
                if (!items.length) { return ''; }
                return '<div class="cf-socials">'
                    + items.map(s => `<a href="${escHtml(social[s.key])}" target="_blank" class="cf-social-btn" style="background:${s.bg};color:#fff"><i class="${s.icon}"></i></a>`).join('')
                    + '</div>';
            }

            function buildCard(c) {
                const verifiedBadge = c.verified ? `<span style="color:#4f46e5;font-size:0.9rem" title="Verified"><i class="ri-verified-badge-fill"></i></span>` : '';
                const cvClass = c.cv_url ? 'cf-action-btn cf-action-btn-cv' : 'cf-action-btn cf-action-btn-cv disabled';
                const cvHref  = c.cv_url ? `href="${escHtml(c.cv_url)}"` : `href="javascript:void(0);"`;
                const saveIcon = c.is_saved ? 'ri-thumb-up-fill' : 'ri-thumb-up-line';
                const saveSaved = c.is_saved ? '1' : '0';
                const pay = (c.pay_min && c.pay_max)
                    ? `<strong>${escHtml(c.pay_min)}</strong> – <strong>${escHtml(c.pay_max)}</strong>`
                    : (c.pay_min ? `<strong>${escHtml(c.pay_min)}</strong>` : '<em style="color:var(--cd-text-muted)">Not specified</em>');
                const langs = (c.languages ?? []).join(', ');
                const socials = buildSocials(c.social);

                return `<div class="cf-card" style="opacity:0;transform:translateY(16px);transition:opacity 0.35s ease,transform 0.35s ease">
                    <div class="cf-card-actions">
                        <a ${cvHref} class="${cvClass}" title="${c.cv_url ? 'View Resume' : 'No CV uploaded'}"><i class="ri-download-cloud-line"></i></a>
                        <button type="button" class="cf-action-btn cf-save-btn save-candidate-btn"
                            data-saved="${saveSaved}" data-candidate-id="${c.id}"
                            title="${c.is_saved ? 'Saved' : 'Save Applicant'}">
                            <i class="${saveIcon}"></i>
                        </button>
                    </div>
                    <div class="cf-card-identity">
                        <img src="${escHtml(c.avatar)}" alt="${escHtml(c.name)}" class="cf-avatar">
                        <div>
                            <a href="${escHtml(c.detail_url)}" class="cf-name">${escHtml(c.name)} ${verifiedBadge}</a>
                            <div class="cf-title">${escHtml(c.title)}</div>
                            <div class="cf-location"><i class="ri-map-pin-line"></i>${escHtml(c.location)}</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;flex-wrap:wrap;gap:0.5rem;font-size:0.875rem">
                        <span style="color:var(--cd-text-muted)">Rating:</span>
                        ${buildStars(c.rating ?? 0)}
                        <span class="candidate-rating-count" data-candidate-id="${c.id}" style="color:var(--cd-text-muted);font-size:0.8125rem">
                            (<span class="rating-count-value">${c.rating_count ?? 0}</span>)
                        </span>
                    </div>
                    <div class="cf-tags">${buildTags(c)}</div>
                    <div class="cf-card-footer">
                        <div class="cf-pay">
                            <div>Salary: ${pay}</div>
                            <div>Languages: <strong>${escHtml(langs)}</strong></div>
                        </div>
                        <div class="cf-card-footer-actions">
                            <a href="${escHtml(c.detail_url)}" class="cf-view-btn">View Profile <i class="ri-arrow-right-line"></i></a>
                            ${socials}
                        </div>
                    </div>
                </div>`;
            }

            function bindSaveBtn(btn) {
                btn.addEventListener('click', function () {
                    const id   = this.dataset.candidateId;
                    const icon = this.querySelector('i');
                    this.classList.add('scale-125');
                    setTimeout(() => this.classList.remove('scale-125'), 200);
                    fetch(`/employer/saved-applicants/${id}/toggle`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.dataset.saved = data.saved ? '1' : '0';
                        if (icon) { icon.className = data.saved ? 'ri-thumb-up-fill' : 'ri-thumb-up-line'; }
                    })
                    .catch(err => console.error('Save error:', err));
                });
            }

            function bindRatingStars(container) {
                container.querySelectorAll('.candidate-rating-stars').forEach(function (starsEl) {
                    const candidateId = starsEl.dataset.candidateId;
                    if (!candidateId) { return; }
                    const stars = starsEl.querySelectorAll('.star-btn');

                    stars.forEach(function (star, index) {
                        star.addEventListener('mouseenter', function () {
                            stars.forEach(function (s, i) {
                                s.querySelector('i').className = 'bi ' + (i <= index ? 'bi-star-fill' : 'bi-star');
                            });
                        });
                    });
                    starsEl.addEventListener('mouseleave', function () {
                        const r = parseFloat(starsEl.dataset.rating) || 0;
                        stars.forEach(function (s, i) {
                            const n = i + 1;
                            s.querySelector('i').className = 'bi ' + (n <= Math.floor(r) ? 'bi-star-fill' : n - 0.5 <= r ? 'bi-star-half' : 'bi-star');
                        });
                    });
                    stars.forEach(function (star) {
                        star.addEventListener('click', function () {
                            const rating = parseInt(star.dataset.star, 10);
                            fetch('/applicants/' + candidateId + '/rate', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                                body: JSON.stringify({ rating }),
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    starsEl.dataset.rating = data.average_rating;
                                    const countEl = document.querySelector(`.candidate-rating-count[data-candidate-id="${candidateId}"] .rating-count-value`);
                                    if (countEl) { countEl.textContent = data.rating_count; }
                                    if (window.Swal) {
                                        Swal.fire({ icon: 'success', title: 'Rating Submitted', text: 'You rated this applicant ' + rating + ' star' + (rating > 1 ? 's' : ''), timer: 1500, showConfirmButton: false });
                                    }
                                }
                            });
                        });
                    });
                });
            }

            function appendCard(c) {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = buildCard(c).trim();
                const card = wrapper.firstElementChild;
                container.appendChild(card);
                requestAnimationFrame(() => requestAnimationFrame(() => {
                    card.style.opacity   = '1';
                    card.style.transform = 'translateY(0)';
                }));
                card.querySelectorAll('.save-candidate-btn').forEach(bindSaveBtn);
                bindRatingStars(card);
            }

            function loadMore() {
                if (loading || !hasMore) { return; }
                loading = true;
                if (loadMoreBtnContainer) loadMoreBtnContainer.style.display = 'none';
                loader.style.display = 'block';

                const params = new URLSearchParams(window.location.search);
                params.set('page', currentPage + 1);

                fetch(`${window.location.pathname}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                })
                .then(r => r.json())
                .then(data => {
                    (data.candidates ?? []).forEach(c => appendCard(c));
                    currentPage = data.page;
                    hasMore     = data.hasMore;
                    if (totalLabel) { totalLabel.textContent = data.total; }
                    if (!hasMore) {
                        endMsg.style.display = 'block';
                        if (loadMoreBtnContainer) loadMoreBtnContainer.style.display = 'none';
                    } else {
                        if (loadMoreBtnContainer) loadMoreBtnContainer.style.display = 'block';
                    }
                })
                .catch(() => {})
                .finally(() => {
                    loading = false;
                    loader.style.display = 'none';
                    if (hasMore && loadMoreBtnContainer) loadMoreBtnContainer.style.display = 'block';
                });
            }

            // Show end message immediately when there is only one page
            if (!hasMore && {{ count($candidates ?? []) }} > 0) {
                endMsg.style.display = 'block';
            }

            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', loadMore);
            }

            // Bind existing (server-rendered) cards
            document.querySelectorAll('.save-candidate-btn').forEach(bindSaveBtn);
            bindRatingStars(document);

        })();
    </script>

    @include('candidates.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'wt-hero',       'title' => 'Browse Applicants', 'icon' => 'ri-user-search-line', 'body' => 'Welcome to the applicant search page. Browse pre-screened candidates and find your next virtual assistant.', 'position' => 'bottom'],
            ['target' => 'wt-search-bar', 'title' => 'Search Bar',        'icon' => 'ri-search-line',      'body' => 'Search by keyword, location, role, or experience level. Hit Search to filter results instantly.', 'position' => 'bottom'],
            ['target' => 'wt-filters',    'title' => 'Sidebar Filters',   'icon' => 'ri-filter-3-line',    'body' => 'Narrow down results by category, availability, language, job type, and expected salary. Click "Apply Filters" to update.', 'position' => 'right'],
            ['target' => 'wt-results',    'title' => 'Applicant Cards',   'icon' => 'ri-id-card-line',     'body' => 'Browse applicant profiles here. Scroll down to load more automatically. Click "View Profile" to see full details.', 'position' => 'left'],
        ],
        'wtKey' => 'employer_search_candidates',
    ])

</x-app-layout>
