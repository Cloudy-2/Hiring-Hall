    <x-app-layout>

    <x-slot name="url_1">{"link": "/applicant/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="title">My Applications</x-slot>
    <x-slot name="active">My Applications</x-slot>

    @include('applicants.partials.candidate-styles')

    <style>
        /* ── Modern Design Tokens ── */
        :is([data-theme-mode="dark"], .dark) .jf-step-dot { border-color: #1e293b !important; }
        :is([data-theme-mode="dark"], .dark) .jf-step-label { color: #64748b !important; }
        :is([data-theme-mode="dark"], .dark) .jf-step-column.active .jf-step-label { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-step-column.completed .jf-step-label { color: #cbd5e1 !important; }

        :is([data-theme-mode="dark"], .dark) .jf-step-column.completed .jf-step-label { color: #cbd5e1 !important; }

        /* ── Missing Variable Definitions ── */
        :root {
            --jf-border: #e2e8f0;
            --jf-text-sub: #64748b;
        }
        :is([data-theme-mode="dark"], .dark) {
            --jf-border: rgba(255, 255, 255, 0.1);
            --jf-text-sub: #94a3b8;
        }

        /* ── Unified Toolbar & Filters ── */
        .jf-filter-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0;
            margin-bottom: 1.5rem;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .jf-filter-container::-webkit-scrollbar { display: none; }

        .jf-filter-item {
            padding: 0.625rem 1.25rem;
            border-radius: 14px;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--cd-text-secondary);
            background: #fff;
            border: 1px solid var(--cd-border);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .jf-filter-item:hover { transform: translateY(-1px); border-color: var(--cd-accent); color: var(--cd-accent); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .jf-filter-item.active { background: var(--cd-accent); color: #fff; border-color: var(--cd-accent); box-shadow: 0 8px 20px -5px rgba(79, 70, 229, 0.4); }
        .jf-filter-count { background: rgba(0,0,0,0.08); padding: 2px 8px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; }
        .jf-filter-item.active .jf-filter-count { background: rgba(255,255,255,0.25); }

        /* ── Modern Premium Search Pill ── */
        .jf-search-pill-container { width: 100%; margin-bottom: 2rem; position: relative; z-index: 100; }
        .jf-search-pill { display: flex; align-items: stretch; background: #fff; border-radius: 300px; padding: 0.5rem; height: 64px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.05); border: 1.5px solid var(--cd-border); transition: all 0.3s; }
        .jf-search-pill:focus-within, .jf-search-pill:hover { box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.01); border-color: var(--cd-accent); transform: translateY(-1px); }
        [data-theme-mode="dark"] .jf-search-pill, .dark .jf-search-pill { background: #1e293b !important; border-color: rgba(255,255,255,0.1) !important; box-shadow: 0 10px 40px rgba(0,0,0,0.3) !important; }

        .jf-pill-field { flex: 1; display: flex; align-items: center; gap: 0.75rem; padding: 0 0.75rem; min-width: 0; position: relative; }
        .jf-pill-field i { font-size: 1.1rem; color: #94a3b8; flex-shrink: 0; }
        .jf-pill-input { width: 100%; border: none !important; background: transparent !important; font-size: 0.875rem; font-weight: 500; color: var(--cd-text); outline: none !important; box-shadow: none !important; cursor: inherit; }
        .jf-pill-input::placeholder { color: #94a3b8; font-weight: 400; }
        [data-theme-mode="dark"] .jf-pill-input, .dark .jf-pill-input { color: #f8fafc !important; }

        .jf-pill-divider { width: 1px; height: 24px; background: #e2e8f0; flex-shrink: 0; }
        [data-theme-mode="dark"] .jf-pill-divider, .dark .jf-pill-divider { background: rgba(255,255,255,0.1) !important; }

        .jf-pill-submit { height: 48px; padding: 0 1.25rem; border-radius: 24px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; font-size: 0.813rem; font-weight: 700; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; box-shadow: 0 4px 12px -2px rgba(79,70,229,0.3); white-space: nowrap; }
        .jf-pill-submit:hover { transform: translateY(-1px); box-shadow: 0 8px 18px -4px rgba(79,70,229,0.4); }

        .jf-p-bulk { width: auto; flex: none; }

        @media (max-width: 768px) {
            .jf-search-pill { height: auto; flex-direction: column; padding: 1rem; border-radius: 20px; gap: 0.5rem; }
            .jf-pill-divider { display: none; }
            .jf-pill-field { width: 100%; border-bottom: 1px solid var(--jf-border); padding: 0.5rem 0; justify-content: flex-start !important; }
            .jf-pill-field:last-of-type { border-bottom: none; }
            .jf-pill-submit { width: 100%; justify-content: center; margin-top: 0.5rem; height: 54px; }
            
            [data-theme-mode="dark"] .jf-pill-field, .dark .jf-pill-field { border-color: rgba(255,255,255,0.08) !important; }
            
            .jf-pill-select-display { justify-content: center !important; }

            /* Equal Status Filters on Mobile */
            .jf-filter-item {
                flex: 1 0 auto !important;
                min-width: 160px !important;
                justify-content: center !important;
                white-space: nowrap !important;
            }

            .jf-count-row {
                justify-content: center !important;
                margin-top: 0.75rem !important;
                margin-bottom: 1.5rem !important;
            }
            #selected-count {
                font-size: 0.65rem !important;
                background: rgba(99, 102, 241, 0.1);
                color: #6366f1 !important;
                padding: 4px 12px !important;
                border-radius: 20px !important;
                display: inline-flex !important;
                align-items: center !important;
                gap: 4px !important;
            }
            :is([data-theme-mode="dark"], .dark) #selected-count {
                background: rgba(99, 102, 241, 0.2) !important;
                color: #818cf8 !important;
            }

            /* Fix Visibility Overrides */
            #selected-count.hidden { display: none !important; }

            /* Compact Mobile Card Footer */
            .jf-app-footer {
                padding-top: 1rem !important;
                margin-top: 0.5rem;
            }
            .jf-app-meta {
                gap: 0.5rem !important;
            }
            .jf-meta-pill {
                padding: 4px 10px !important;
                font-size: 0.7rem !important;
            }
        }

        /* ── Premium Checkbox Standardization (Global) ── */
        .row-checkbox, .master-checkbox {
            appearance: none !important;
            -webkit-appearance: none !important;
            background-color: transparent !important;
            border: 2px solid #6366f1 !important;
            width: 22px !important;
            height: 22px !important;
            border-radius: 6px !important;
            position: relative !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
            display: inline-block;
            vertical-align: middle;
        }
        .row-checkbox:checked, .master-checkbox:checked {
            background-color: #6366f1 !important;
            border-color: #6366f1 !important;
        }
        .row-checkbox:checked::after, .master-checkbox:checked::after {
            content: '\EB7A';
            font-family: 'remixicon' !important;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff !important;
            font-size: 14px;
            font-weight: bold;
        }
        :is([data-theme-mode="dark"], .dark) .row-checkbox:not(:checked), 
        :is([data-theme-mode="dark"], .dark) .master-checkbox:not(:checked) {
            border-color: rgba(255,255,255,0.3) !important;
            background: rgba(255,255,255,0.05) !important;
        }

        .jf-count-row { 
            display: flex; 
            justify-content: flex-end; 
            padding: 0 1.25rem; 
            margin-top: 0.75rem; 
            margin-bottom: 0.5rem; 
        }

        #selected-count {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            padding: 5px 14px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }
        :is([data-theme-mode="dark"], .dark) #selected-count {
            background: rgba(99, 102, 241, 0.15);
            color: #818cf8;
        }
        #selected-count.hidden { display: none !important; }

        /* Custom Dropdowns */
        .jf-pill-select-wrapper { position: relative; width: 100%; cursor: pointer; user-select: none; }
        .jf-pill-select-display { display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; font-size: 0.813rem; font-weight: 500; color: #1e293b; padding: 0.5rem 0.75rem; width: 100%; border-radius: 12px; transition: all 0.2s; }
        .jf-pill-select-wrapper:hover .jf-pill-select-display { background: rgba(99,102,241,0.05); color: #6366f1 !important; }
        .jf-pill-select-wrapper:hover .jf-pill-select-display i { color: #6366f1 !important; }
        [data-theme-mode="dark"] .jf-pill-select-display, .dark .jf-pill-select-display { color: #f8fafc !important; }
        .jf-pill-select-wrapper.active .jf-pill-select-display { background: #6366f1 !important; color: #ffffff !important; }
        .jf-pill-select-wrapper.active .jf-pill-select-display i { color: #ffffff !important; }
        .jf-pill-select-display i.ri-arrow-down-s-line { font-size: 1rem; color: #94a3b8; transition: transform 0.2s; }
        .jf-pill-select-wrapper.active i.ri-arrow-down-s-line { transform: rotate(180deg); }

        .jf-pill-select-options {
            position: absolute; top: 100%; left: -12px; min-width: calc(100% + 24px); background: #fff; border: 1px solid var(--cd-border); border-radius: 12px; padding: 0.5rem; margin-top: 4px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); z-index: 1000; display: none; opacity: 0; transform: translateY(10px); transition: opacity 0.2s, transform 0.2s;
        }
        .jf-pill-select-options::before { content: ''; position: absolute; top: -15px; left: 0; right: 0; height: 15px; background: transparent; }
        .jf-pill-select-wrapper.active .jf-pill-select-options { display: block; opacity: 1; transform: translateY(0); }
        [data-theme-mode="dark"] .jf-pill-select-options, .dark .jf-pill-select-options { background: #1e293b !important; box-shadow: 0 15px 40px rgba(0,0,0,0.4) !important; border-color: rgba(255,255,255,0.1) !important;}

        .jf-pill-option { padding: 0.625rem 0.75rem; border-radius: 8px; display: flex; align-items: center; gap: 0.5rem; cursor: pointer; white-space: nowrap; transition: all 0.2s; color: var(--cd-text);}
        .jf-pill-option.header { color: #64748b; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding-top: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--cd-border); margin-bottom: 0.25rem; cursor: default; justify-content: center; }
        .jf-pill-option.header:hover { background: transparent; color: #64748b; }
        .jf-pill-option:not(.header):hover { background: #6366f1; color: #ffffff !important; padding-left: 1rem; font-weight: 600; }
        [data-theme-mode="dark"] .jf-pill-option:not(.header), .dark .jf-pill-option:not(.header) { color: #cbd5e1 !important; }
        .jf-pill-option.text-danger:hover { background: #ef4444 !important; }

        /* ── Application Board Card ── */
        .jf-app-card {
            background: #fff;
            border: 1.5px solid var(--cd-border);
            border-radius: 24px;
            padding: 1.75rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            position: relative;
            box-shadow: 0 4px 20px -8px rgba(0,0,0,0.05);
        }
        .jf-app-card:hover { transform: translateY(-6px); border-color: var(--cd-accent); box-shadow: 0 20px 40px -12px rgba(0,0,0,0.12); }

        .jf-app-header { display: flex; align-items: center; justify-content: space-between; }
        .jf-app-identity { display: flex; align-items: center; gap: 1rem; }
        .jf-logo { width: 52px; height: 52px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #fff; box-shadow: 0 4px 12px -2px rgba(0,0,0,0.15); font-size: 1.1rem; }
        .jf-app-title { font-size: 1.125rem; font-weight: 800; color: var(--cd-text); line-height: 1.3; }
        .jf-app-company { font-size: 0.875rem; color: var(--cd-text-secondary); font-weight: 600; display: flex; align-items: center; gap: 4px; }

        /* ── Status Stepper ── */
        /* ── Segmented Status Stepper ── */
        .jf-stepper {
            display: flex;
            width: 100%;
            margin: 1.5rem 0;
            padding-bottom: 0.5rem;
        }
        .jf-step-column {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            position: relative;
        }
        .jf-step-node {
            display: flex;
            align-items: center;
            width: 100%;
            height: 32px;
            position: relative;
        }
        .jf-step-line {
            flex: 1;
            height: 3px;
            background: var(--cd-border);
            border-radius: 2px;
            transition: all 0.3s;
        }
        .jf-step-line.completed { background: var(--cd-accent); }
        .jf-step-line.invisible { opacity: 0; pointer-events: none; }

        .jf-step {
            width: 32px; height: 32px; border-radius: 50%; background: #fff; border: 2.5px solid var(--cd-border);
            display: flex; align-items: center; justify-content: center;
            transition: all 0.3s ease;
            position: relative;
            z-index: 3;
            box-sizing: border-box;
            flex-shrink: 0;
        }
        .jf-step i { font-size: 0.85rem; color: var(--cd-text-secondary); opacity: 0; transition: all 0.3s; }

        .jf-step.completed { background: var(--cd-accent); border-color: var(--cd-accent); }
        .jf-step.completed i { opacity: 1; color: #fff; }
        .jf-step.active { background: #fff; border-color: var(--cd-accent); box-shadow: 0 0 0 5px rgba(79,70,229, 0.1); }
        .jf-step.active i { opacity: 1; color: var(--cd-accent); }

        .jf-step-label { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; color: var(--cd-text-secondary); white-space: nowrap; letter-spacing: 0.05em; transition: all 0.3s; text-align: center; }
        .jf-step-column.active .jf-step-label { color: var(--cd-accent); }
        .jf-step-column.completed .jf-step-label { color: var(--cd-text); }

        /* ── Meta & Footer ── */
        .jf-app-meta { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .jf-meta-pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 10px; background: rgba(0,0,0,0.03); font-size: 0.8rem; font-weight: 700; color: var(--cd-text-secondary); border: 1px solid var(--cd-border); }
        [data-theme-mode="dark"] .jf-meta-pill, .dark .jf-meta-pill { background: rgba(255,255,255,0.03); }

        .jf-app-footer { display: flex; align-items: center; justify-content: space-between; border-top: 1.5px dashed var(--cd-border); padding-top: 1.25rem; }
        .jf-status-label { display: flex; align-items: center; gap: 8px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .jf-status-indicator { width: 8px; height: 8px; border-radius: 50%; }

        .jf-action-group { display: flex; gap: 8px; }
        .jf-btn-circle { width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: #f8fafc; border: 1px solid var(--cd-border); color: var(--cd-text-secondary); transition: all 0.2s; cursor: pointer; }
        .jf-btn-circle:hover { background: var(--cd-accent); color: #fff; border-color: var(--cd-accent); transform: scale(1.05); }
        .jf-btn-circle.danger:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

        /* Empty State */
        .jf-empty-state { padding: 5rem 2rem; text-align: center; background: #fff; border-radius: 32px; border: 2px dashed var(--cd-border); display: flex; flex-direction: column; align-items: center; gap: 1.5rem; transition: all 0.3s ease; }
        .jf-empty-icon { width: 80px; height: 80px; border-radius: 24px; background: rgba(99,102,241,0.1); color: var(--cd-accent); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; transition: all 0.3s ease; }
        .jf-empty-state:hover { border-color: var(--cd-accent); transform: translateY(-5px); }
        .jf-empty-state:hover .jf-empty-icon { transform: scale(1.1); background: rgba(99,102,241,0.15); }

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
        .cd-pagination nav > p { display: none !important; }

        /* ── Surgical Dark Mode UI Fix ── */
        :is([data-theme-mode="dark"], .dark) .jf-filter-item:not(.active),
        :is([data-theme-mode="dark"], .dark) .jf-app-card,
        :is([data-theme-mode="dark"], .dark) .jf-empty-state,
        :is([data-theme-mode="dark"], .dark) .jf-btn-circle {
            background: rgba(30, 41, 59, 0.45) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(12px);
        }

        :is([data-theme-mode="dark"], .dark) .jf-app-title,
        :is([data-theme-mode="dark"], .dark) .jf-filter-item.active { color: #ffffff !important; }

        :is([data-theme-mode="dark"], .dark) .jf-app-company,
        :is([data-theme-mode="dark"], .dark) .jf-filter-item:not(.active),
        :is([data-theme-mode="dark"], .dark) .jf-step-label { color: #cbd5e1 !important; }

        :is([data-theme-mode="dark"], .dark) .jf-step {
            background: #0f172a !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
        }
        :is([data-theme-mode="dark"], .dark) .jf-step.active,
        :is([data-theme-mode="dark"], .dark) .jf-step.completed { border-color: #6366f1 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-step.active i { color: #6366f1 !important; }

        :is([data-theme-mode="dark"], .dark) .jf-step-line { background: rgba(255, 255, 255, 0.05) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-step-line.completed { background: #6366f1 !important; }

        :is([data-theme-mode="dark"], .dark) .jf-app-footer { border-top-color: rgba(255, 255, 255, 0.08) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-status-label { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-btn-circle { color: #cbd5e1 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-btn-circle:hover { background: #6366f1 !important; color: #fff !important; }

        :is([data-theme-mode="dark"], .dark) .jf-meta-pill { color: #f8fafc !important; border-color: rgba(255, 255, 255, 0.15) !important; background: rgba(255, 255, 255, 0.05) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-meta-pill i { color: #818cf8 !important; }

        /* ── Dark Mode Header Styling ── */
        :is([data-theme-mode="dark"], .dark) .jf-header-section { border-bottom-color: rgba(255,255,255,0.08) !important; background: rgb(30, 32, 35) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-context-label { color: #ffffff !important; }

        :is([data-theme-mode="dark"], .dark) .jf-pipeline-card {
            background: rgba(30, 41, 59, 0.45) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-pipeline-card:hover {
            background: rgba(30, 41, 59, 0.6) !important;
            border-color: #6366f1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-filter-item:not(.active) {
            background: rgba(30, 41, 59, 0.45) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-filter-item:not(.active):hover {
            background: rgba(30, 41, 59, 0.6) !important;
            border-color: #6366f1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-app-card {
            background: rgba(30, 41, 59, 0.45) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-app-card:hover {
            background: rgba(30, 41, 59, 0.6) !important;
            border-color: #6366f1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-app-card * {
            color: #f8fafc !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-empty-state {
            background: rgba(30, 41, 59, 0.45) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #94a3b8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-btn-circle {
            background: rgba(30, 41, 59, 0.45) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-btn-circle:hover {
            background: #6366f1 !important;
            color: #ffffff !important;
        }
    </style>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        {{-- Modern Minimalist Header (Interactive Board Style) --}}
        <x-modern-header :container="false" chip="Career Journey" headerClass="!mb-4">
            <x-slot name="titleContent"><strong>My Applications</strong></x-slot>
            <x-slot name="description">
                Manage your active job applications within the <b>Career Journey</b> board. track status updates in real-time, and stay on top of your professional milestones.
            </x-slot>
            <x-slot name="actions">
                <a href="{{ route('jobs') }}" class="inline-flex items-center px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 text-sm">
                    <i class="ri-search-line me-2"></i> Find Jobs
                </a>
                <a href="{{ route('applicant.applications.history') }}" class="inline-flex items-center px-6 py-3 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-white font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm hover:shadow-md border border-slate-200 dark:border-slate-700 text-sm">
                    <i class="ri-history-line me-2"></i> View History
                </a>
            </x-slot>
        </x-modern-header>

        {{-- Status Filter Bar --}}
        <div class="jf-filter-container" id="wt-filters">
            @php
                $statusFilters = [
                    ['id' => '', 'label' => 'All Applications', 'icon' => 'ri-layout-grid-fill', 'count' => $totalApplied],
                    ['id' => 'applied', 'label' => 'Applied', 'icon' => 'ri-send-plane-fill', 'count' => $appliedCount ?? 0],
                    ['id' => 'under_review', 'label' => 'Under Review', 'icon' => 'ri-time-fill', 'count' => $underReviewCount],
                    ['id' => 'accepted', 'label' => 'Accept', 'icon' => 'ri-checkbox-circle-fill', 'count' => $acceptedCount],
                    ['id' => 'not_selected', 'label' => 'Declined', 'icon' => 'ri-close-circle-fill', 'count' => $declinedCount],
                ];
                $activeStatus = request('status', '');
            @endphp
            @foreach($statusFilters as $filter)
                <button type="button"
                   class="jf-filter-item jf-status-filter {{ $activeStatus == $filter['id'] ? 'active' : '' }}"
                   data-status="{{ $filter['id'] }}">
                    <i class="{{ $filter['icon'] }}"></i>
                    {{ $filter['label'] }}
                    <span class="jf-filter-count">{{ $filter['count'] }}</span>
                </button>
            @endforeach
        </div>

        {{-- Unified Search & Bulk Pill --}}
        <div class="jf-search-pill-container" id="wt-toolbar">
            <div class="jf-search-pill">
                {{-- Master Checkbox --}}
                <div class="jf-pill-field" style="flex: 0 0 auto; padding-left: 1.25rem; padding-right: 0.5rem; justify-content: center;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; margin: 0;" title="Select all visible applications">
                        <input type="checkbox" id="select-all-checkbox" class="master-checkbox" style="width: 20px; height: 20px; border-radius: 6px; border: 2px solid var(--jf-border); cursor: pointer;">
                        <span style="font-size: 0.813rem; font-weight: 700; color: var(--jf-text-sub);">All</span>
                    </label>
                </div>
                <div class="jf-pill-divider"></div>

                {{-- Search Field --}}
                <div class="jf-pill-field">
                    <i class="ri-search-2-line"></i>
                    <input type="text" id="search-input" class="jf-pill-input" placeholder="Search by job title or company...">
                </div>
                <div class="jf-pill-divider"></div>

                {{-- Bulk Actions Integrated (Custom) --}}
                <div class="jf-pill-field jf-p-bulk">
                    <div class="jf-pill-select-wrapper" id="bulk-select-wrapper">
                        <div class="jf-pill-select-display">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="ri-check-double-line" id="bulk-action-icon"></i>
                                <span id="bulk-display-val">Bulk Actions</span>
                            </div>
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                        <div class="jf-pill-select-options">
                            <div class="jf-pill-option header">Bulk Actions</div>
                            <div class="jf-pill-option" data-value="withdraw">Withdraw Selected</div>
                            <div class="jf-pill-option text-danger" data-value="archive">Archive Selected</div>
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
                <span id="selected-count" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest hidden">
                    <span id="selected-count-num" class="text-indigo-600">0</span> applications selected
                </span>
            </div>
        </div>

        <div class="jf-pipeline-card cd-section p-5" id="wt-applications">
        @if($applications->isEmpty())
            <div class="cd-section-head">
                <span class="cd-section-label dark:text-white" style="font-size:1.15rem;font-weight:800"><i class="ri-timer-line text-indigo-500"></i> My Recent Applications</span>
            </div>
            <div class="jf-empty-state">
                <div class="jf-empty-icon">
                    <i class="ri-file-search-line"></i>
                </div>
                <div class="text-center">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No applications found</h3>
                    <p class="text-slate-500 max-w-sm mx-auto mb-6">We couldn't find any applications matching your current filter. Try adjusting your search or browse new jobs.</p>
                    <a href="{{ route('jobs') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-md">
                        <i class="ri-search-line me-2"></i> Browse Jobs
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6" id="wt-table">
                @foreach($applications as $index => $application)
                    @php
                        $jp = $application->jobPosting;
                        $company = $jp?->company;
                        $status = $application->status;

                        $statusMap = [
                            'applied' => ['label' => 'Applied', 'color' => '#6366f1', 'step' => 1],
                            'submitted' => ['label' => 'Applied', 'color' => '#6366f1', 'step' => 1],
                            'under_review' => ['label' => 'Under Review', 'color' => '#f59e0b', 'step' => 2],
                            'application_viewed' => ['label' => 'Viewed', 'color' => '#0ea5e9', 'step' => 2],
                            'viewed' => ['label' => 'Viewed', 'color' => '#0ea5e9', 'step' => 2],
                            'accepted' => ['label' => 'Accept', 'color' => '#10b981', 'step' => 4],
                            'offered' => ['label' => 'Offered', 'color' => '#10b981', 'step' => 4],
                            'hired' => ['label' => 'Hired', 'color' => '#10b981', 'step' => 4],
                            'not_selected' => ['label' => 'Declined', 'color' => '#ef4444', 'step' => 4],
                            'no_longer_under_consideration' => ['label' => 'Declined', 'color' => '#ef4444', 'step' => 4],
                            'closed' => ['label' => 'Closed', 'color' => '#64748b', 'step' => 4],
                        ];

                        $st = $statusMap[$status] ?? ['label' => Str::headline($status), 'color' => '#64748b', 'step' => 1];
                        $currStep = $st['step'];

                        $logoColors = ['#6366f1', '#14b8a6', '#f43f5e', '#f59e0b', '#0ea5e9', '#8b5cf6'];
                        $lbg = $logoColors[$index % count($logoColors)];
                        $initials = collect(explode(' ', $company->name ?? 'C'))->map(fn($w)=>strtoupper(substr($w,0,1)))->take(2)->join('');
                    @endphp

                    <div class="jf-app-card"
                         data-status="{{ in_array($status, ['submitted', 'applied']) ? 'applied' : (in_array($status, ['application_viewed', 'viewed']) ? 'viewed' : (in_array($status, ['not_selected', 'no_longer_under_consideration', 'declined', 'rejected']) ? 'declined' : (in_array($status, ['accepted', 'hired', 'offered']) ? 'accepted' : $status))) }}"
                         data-search-content="{{ strtolower(($jp->title ?? '') . ' ' . ($company->name ?? '')) }}">

                        <div class="jf-app-header">
                            <div class="jf-app-identity">
                                <div class="jf-logo" style="background:{{ $lbg }}">
                                    @if($company?->logo_url)
                                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ $initials }}
                                    @endif
                                </div>
                                <div class="jf-app-info">
                                    @if($jp)
                                        <a href="{{ route('jobs.show', $jp->slug) }}" class="jf-app-title line-clamp-1">{{ $jp->title }}</a>
                                    @else
                                        <span class="jf-app-title opacity-50">Job no longer active</span>
                                    @endif
                                    <div class="jf-app-company">
                                        {{ $company->name ?? 'Deleted Company' }}
                                        @if($company?->verified)
                                            <i class="ri-verified-badge-fill text-blue-500" title="Verified"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <input type="checkbox" class="row-checkbox" value="{{ $application->id }}" style="width: 20px; height: 20px; border-radius: 6px; border: 2px solid var(--jf-border); cursor: pointer;">
                        </div>

                        {{-- Premium Stepper --}}
                        <div class="jf-stepper">
                            @php
                                $steps = [
                                    ['label' => 'Applied', 'icon' => 'ri-send-plane-line'],
                                    ['label' => 'Under Review', 'icon' => 'ri-search-eye-line'],
                                    ['label' => 'Decision', 'icon' => 'ri-chat-voice-line'],
                                ];
                            @endphp
                            @foreach($steps as $i => $step)
                                @php
                                    $stepNum = $i + 1;
                                    $isCompleted = $currStep > $stepNum || ($currStep == $stepNum && ($status == 'accepted' || $status == 'not_selected' || $status == 'no_longer_under_consideration'));
                                    $isActive = $currStep == $stepNum && !$isCompleted;
                                    $hasLineBefore = $i > 0;
                                    $hasLineAfter = $i < count($steps) - 1;
                                @endphp
                                <div class="jf-step-column {{ $isCompleted ? 'completed' : ($isActive ? 'active' : '') }}">
                                    <div class="jf-step-node">
                                        <div class="jf-step-line {{ $hasLineBefore ? ($currStep >= $stepNum ? 'completed' : '') : 'invisible' }}"></div>

                                        <div class="jf-step {{ $isCompleted ? 'completed' : ($isActive ? 'active' : '') }}">
                                            <i class="{{ $isCompleted ? 'ri-check-line' : $step['icon'] }}"></i>
                                        </div>

                                        <div class="jf-step-line {{ $hasLineAfter ? ($currStep > $stepNum ? 'completed' : '') : 'invisible' }}"></div>
                                    </div>
                                    <span class="jf-step-label">{{ $step['label'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="jf-app-meta">
                            <div class="jf-meta-pill">
                                <i class="ri-calendar-event-line"></i>
                                <span>{{ $application->applied_at ? $application->applied_at->format('M d, Y') : '-' }}</span>
                            </div>
                            @if($jp?->location)
                            <div class="jf-meta-pill">
                                <i class="ri-map-pin-2-line"></i>
                                <span>{{ $jp->location }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="jf-app-footer">
                            <div class="jf-status-label" style="color: {{ $st['color'] }}">
                                <div class="jf-status-indicator" style="background: {{ $st['color'] }}; box-shadow: 0 0 8px {{ $st['color'] }}66"></div>
                                {{ $st['label'] }}
                            </div>

                            <div class="jf-action-group">
                                @if($jp)
                                    <a href="{{ route('jobs.show', $jp->slug) }}" class="jf-btn-circle" title="View Job"><i class="ri-eye-line"></i></a>
                                @endif

                                @php
                                    $canWithdraw = in_array($application->status, ['applied', 'submitted']);
                                @endphp

                                @if($canWithdraw)
                                    <button type="button" class="jf-btn-circle danger application-cancel-btn"
                                            data-cancel-form-id="cancel-application-{{ $application->id }}"
                                            data-job-title="{{ $jp->title ?? 'this job' }}" title="Withdraw Application">
                                        <i class="ri-close-line"></i>
                                    </button>
                                @else
                                    <button type="button" class="jf-btn-circle" style="opacity:0.4; cursor:not-allowed;" title="Withdrawal unavailable for {{ $st['label'] }} applications" disabled>
                                        <i class="ri-close-line"></i>
                                    </button>
                                @endif
                                <form id="cancel-application-{{ $application->id }}" method="POST" action="{{ route('applicant.applications.destroy', $application) }}" class="hidden">@csrf @method('DELETE')</form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 py-4 border-t border-slate-200 dark:border-slate-800">
                <p class="text-sm text-slate-500">Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} of {{ $applications->total() }} applications</p>
                <div class="cd-pagination">
                    {{ $applications->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-input');
            const statusFilters = document.querySelectorAll('.jf-status-filter');
            const appCards = document.querySelectorAll('.jf-app-card');
            const bulkActionSelect = document.getElementById('bulk-action-select');
            const applyBulkBtn = document.getElementById('apply-bulk-action');
            let currentFilter = 'all';

            function filterApps() {
                const query = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                appCards.forEach(card => {
                    const status = card.dataset.status;
                    const content = card.dataset.searchContent || '';
                    const matchesSearch = !query || content.includes(query);
                    const matchesStatus = currentFilter === 'all' || status === currentFilter;

                    if (matchesSearch && matchesStatus) {
                        card.style.display = 'flex';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(10px)';
                    }
                });

                // Handle empty state visibility
                const emptyState = document.querySelector('.jf-empty-state');
                const boardGrid = document.getElementById('wt-table');
                if (emptyState && boardGrid) {
                    if (visibleCount === 0) {
                        emptyState.style.display = 'flex';
                        boardGrid.style.display = 'none';
                    } else {
                        emptyState.style.display = 'none';
                        boardGrid.style.display = 'grid';
                    }
                }
            }

            // Real-time Search
            if (searchInput) {
                searchInput.addEventListener('input', filterApps);
            }

            // Status Filtering
            statusFilters.forEach(btn => {
                btn.addEventListener('click', function() {
                    statusFilters.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.status || 'all';
                    filterApps();
                });
            });

            // Custom Dropdown Logic
            document.querySelectorAll('.jf-pill-select-wrapper').forEach(wrapper => {
                let hideTimeout;
                wrapper.addEventListener('mouseenter', function() {
                    clearTimeout(hideTimeout);
                    document.querySelectorAll('.jf-pill-select-wrapper.active').forEach(w => w.classList.remove('active'));
                    this.classList.add('active');
                });
                wrapper.addEventListener('mouseleave', function() {
                    hideTimeout = setTimeout(() => { this.classList.remove('active'); }, 150);
                });

                const options = wrapper.querySelectorAll('.jf-pill-option:not(.header)');
                const hiddenInput = wrapper.querySelector('input[type="hidden"]');
                const displaySpan = wrapper.querySelector('span[id$="-val"]');

                options.forEach(opt => {
                    opt.addEventListener('click', function(e) {
                        e.stopPropagation();
                        options.forEach(o => o.classList.remove('selected'));
                        this.classList.add('selected');
                        if (hiddenInput) {
                            hiddenInput.value = this.getAttribute('data-value');
                            hiddenInput.dispatchEvent(new Event('change'));
                        }
                        if (displaySpan) displaySpan.textContent = this.textContent.trim();
                        wrapper.classList.remove('active');
                    });
                });
            });

            // Trigger & Count Logic
            const searchTrigger = document.getElementById('search-trigger');
            const triggerText = document.getElementById('trigger-text');
            const triggerIcon = document.getElementById('trigger-icon');
            const selectedCount = document.getElementById('selected-count');
            const selectedCountNum = document.getElementById('selected-count-num');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const selectAllCb = document.getElementById('select-all-checkbox');

            function updateCount() {
                const visibleCards = Array.from(appCards).filter(c => c.style.display !== 'none');
                const visibleCheckboxes = visibleCards.map(c => c.querySelector('.row-checkbox')).filter(Boolean);
                
                // Count ONLY visible checked items
                const c = visibleCards.filter(card => {
                    const cb = card.querySelector('.row-checkbox');
                    return cb && cb.checked;
                }).length;

                if (selectedCount) {
                    if (c > 0) {
                        selectedCountNum.textContent = c;
                        selectedCount.classList.remove('hidden');
                    } else {
                        selectedCount.classList.add('hidden');
                    }
                }

                // Update master checkbox state based on visible items
                if (selectAllCb) {
                    if (visibleCheckboxes.length > 0) {
                        const allVisibleChecked = visibleCheckboxes.every(cb => cb.checked);
                        const anyVisibleChecked = visibleCheckboxes.some(cb => cb.checked);
                        selectAllCb.checked = allVisibleChecked;
                        selectAllCb.indeterminate = anyVisibleChecked && !allVisibleChecked;
                    } else {
                        selectAllCb.checked = false;
                        selectAllCb.indeterminate = false;
                    }
                }

                updateTriggerState();
            }

            rowCheckboxes.forEach(cb => cb.addEventListener('change', updateCount));

            // Re-calc after filtering
            const originalFilterApps = filterApps;
            filterApps = function() {
                originalFilterApps();
                updateCount();
            };
            if (searchInput) {
                searchInput.removeEventListener('input', originalFilterApps);
                searchInput.addEventListener('input', filterApps);
            }
            statusFilters.forEach(btn => {
                btn.addEventListener('click', function() {
                    statusFilters.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.status || 'all';
                    filterApps();
                });
            });

            if (selectAllCb) {
                selectAllCb.addEventListener('change', function() {
                    const isChecked = this.checked;
                    appCards.forEach(card => {
                        if (card.style.display !== 'none') {
                            const cb = card.querySelector('.row-checkbox');
                            if (cb) cb.checked = isChecked;
                        }
                    });
                    updateCount();
                });
            }

            function updateTriggerState() {
                const bulkSelectNew = document.getElementById('bulk-action-select');
                if(!bulkSelectNew) return;
                const hasAction = bulkSelectNew.value !== '';
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

            const bulkSelectNew = document.getElementById('bulk-action-select');
            if (bulkSelectNew) bulkSelectNew.addEventListener('change', updateTriggerState);

            // Bulk Actions Trigger
            if (searchTrigger) {
                searchTrigger.addEventListener('click', function() {
                    const action = bulkSelectNew ? bulkSelectNew.value : '';
                    const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);

                    if (action && ids.length > 0) {
                        let isArchive = action === 'delete' || action === 'archive';
                        let actionWord = isArchive ? 'archive' : 'withdraw';
                        Swal.fire({
                            title: 'Are you sure?',
                            text: `You are about to ${actionWord} ${ids.length} application(s).`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, proceed',
                            confirmButtonColor: isArchive ? '#ef4444' : '#4f46e5',
                            cancelButtonColor: '#64748b'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const url = action === 'withdraw' ? '{{ route("applicant.applications.bulk-withdraw") }}' : '{{ route("applicant.applications.bulk-delete") }}';
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
                                    Swal.fire({ icon: 'success', title: 'Success', text: data.message, timer: 1500, showConfirmButton: false })
                                    .then(() => window.location.reload());
                                })
                                .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' }));
                            }
                        });
                    } else {
                        // Just search focus
                        if (searchInput) searchInput.focus();
                    }
                });
            }

            // Individual Cancel
            document.querySelectorAll('.application-cancel-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const formId = this.dataset.cancelFormId;
                    const jobTitle = this.dataset.jobTitle;

                    Swal.fire({
                        title: 'Withdraw Application?',
                        text: `Are you sure you want to withdraw your application for "${jobTitle}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, withdraw',
                        confirmButtonColor: '#ef4444',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(formId).submit();
                        }
                    });
                });
            });
        });

        function startWalkthrough() {
            if (typeof window.startWalkthrough === 'function') {
                window.startWalkthrough();
            }
        }
    </script>

    @include('applicants.partials.walkthrough', [
        'wtKey' => 'applications',
        'wtSteps' => [
            ['target' => 'wt-hero', 'icon' => 'ri-file-list-3-line', 'title' => 'My Applications', 'body' => 'Welcome to your application hub. Here you can track all the roles you have applied for.', 'position' => 'bottom'],
            ['target' => 'wt-filters', 'icon' => 'ri-filter-3-line', 'title' => 'Quick Filters', 'body' => 'Filter your applications by status to quickly see what is pending, viewed, or accept.', 'position' => 'bottom'],
            ['target' => 'wt-toolbar', 'icon' => 'ri-tools-line', 'title' => 'Search & Actions', 'body' => 'Search for specific jobs and perform bulk actions like withdrawing multiple applications.', 'position' => 'bottom'],
            ['target' => 'wt-table', 'icon' => 'ri-layout-grid-fill', 'title' => 'Application Cards', 'body' => 'Each application is shown as a card with key details and easy access to actions.', 'position' => 'top'],
        ]
    ])

</x-app-layout>
