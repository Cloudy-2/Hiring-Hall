<x-app-layout>

    <x-slot name="url_1">{"link": "/jobs", "text": "Jobs"}</x-slot>
    <x-slot name="title">Job Search</x-slot>
    <x-slot name="active">Job Listing</x-slot>

    @include('candidates.partials.candidate-styles')

    <style>
        /* ── Layout ── */
        .jf-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        /* ── Dark Mode Elite Refinements (Search Jobs) ── */
        :is([data-theme-mode="dark"], .dark) .jf-header-section {
            border-bottom-color: rgba(255, 255, 255, 0.08) !important;
            background: rgb(30, 32, 35) !important;
        }

        :is([data-theme-mode="dark"], .dark) hr {
            border-top-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-header-title {
            color: #f8fafc !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-header-desc {
            color: #94a3b8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-v-bar {
            background: #818cf8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-context-label {
            background: rgba(129, 140, 248, 0.15) !important;
            color: #ffffff !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-search-pill {
            background: rgb(30, 32, 35) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-pill-divider {
            background: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-pill-input {
            color: #f1f5f9 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-pill-select {
            color: #f1f5f9 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-select-trigger {
            color: #f1f5f9 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-pill-field i {
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-select-options {
            background: rgb(30, 32, 35) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-option {
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-option:hover {
            background: rgba(255, 255, 255, 0.05) !important;
            color: #818cf8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-card {
            background: rgb(30, 32, 35) !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-card:hover {
            border-color: #818cf8 !important;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.3) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-card-title {
            color: #f8fafc !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-company-name {
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-tag {
            background: rgba(255, 255, 255, 0.05) !important;
            color: #cbd5e1 !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-tag i {
            color: #818cf8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-sidebar {
            background: rgb(30, 32, 35) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-sidebar-card {
            background: rgb(30, 32, 35) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-sidebar-header {
            background: rgba(255, 255, 255, 0.03) !important;
            border-bottom-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-sidebar-footer {
            background: rgba(0, 0, 0, 0.2) !important;
            border-top-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-results-count {
            color: #f1f5f9 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-pay {
            color: #94a3b8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-pay strong {
            color: #f1f5f9 !important;
        }

        /* ── Robust Dark Mode Overrides (Elite SaaS) ── */
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

        :is([data-theme-mode="dark"], .dark) .jf-header-actions a:hover,
        :is([data-theme-mode="dark"], .dark) .jf-header-actions button:hover {
            background-color: rgba(51, 65, 85, 0.9) !important;
        }

        @media (max-width: 900px) {
            .jf-layout {
                grid-template-columns: 1fr;
            }

            .jf-sidebar {
                display: none;
            }

            .jf-sidebar.mobile-open {
                display: block;
            }
        }

        /* ── Left sidebar filter ── */
        .jf-sidebar {
            position: sticky;
            top: 80px;
            background: #fff;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 0;
            box-shadow: 0 10px 30px -8px rgba(0, 0, 0, 0.06), 0 4px 12px -4px rgba(0, 0, 0, 0.04);
            transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .jf-sidebar-card {
            background: #fff;
            border: 1px solid var(--cd-border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        [data-theme-mode="dark"] .jf-sidebar-card,
        .dark .jf-sidebar-card {
            background: rgb(30, 32, 35);
            border-color: rgba(255, 255, 255, 0.08);
        }

        .jf-sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f3f4f6;
            background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
        }

        [data-theme-mode="dark"] .jf-sidebar-header,
        .dark .jf-sidebar-header {
            background: rgba(99, 102, 241, 0.08);
            border-bottom-color: rgba(255, 255, 255, 0.08);
        }

        .jf-sidebar-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--cd-text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .jf-sidebar-title i {
            color: var(--cd-accent);
            font-size: 1.1rem;
        }

        [data-theme-mode="dark"] .jf-sidebar-title,
        .dark .jf-sidebar-title {
            color: #f1f5f9;
        }

        .jf-sidebar-body {
            padding: 1rem 1.25rem;
        }

        .jf-filter-section {
            padding-bottom: 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .jf-filter-section:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        [data-theme-mode="dark"] .jf-filter-section,
        .dark .jf-filter-section {
            border-bottom-color: rgba(255, 255, 255, 0.06);
        }

        .jf-group-label {
            font-size: 0.8125rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--cd-text-muted);
            margin-bottom: 0.65rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .jf-group-label i {
            color: var(--cd-accent);
            font-size: 0.9rem;
        }

        [data-theme-mode="dark"] .jf-group-label,
        .dark .jf-group-label {
            color: #9ca3af;
        }

        /* Checkboxes */
        .jf-check {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 7px;
            cursor: pointer;
        }

        .jf-check input[type="checkbox"] {
            width: 17px;
            height: 17px;
            flex-shrink: 0;
            accent-color: var(--cd-accent);
            cursor: pointer;
        }

        .jf-header-desc {
            font-size: 0.9375rem;
            color: var(--cd-text-muted);
            max-width: 600px;
            line-height: 1.5;
            margin-bottom: 0;
        }

        .jf-check span {
            font-size: 0.9375rem;
            color: var(--cd-text-secondary);
            cursor: pointer;
            line-height: 1.3;
        }

        [data-theme-mode="dark"] .jf-check span,
        .dark .jf-check span {
            color: #cbd5e1;
        }

        .jf-check:hover span {
            color: var(--cd-accent);
        }

        /* Job Roles Filter - Collapsible Groups */
        .jf-jobrole-group-toggle {
            width: 100%;
            padding: 0.625rem 0.75rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
            transition: background 0.2s, border-color 0.2s;
        }

        .jf-jobrole-group-toggle:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .jf-jobrole-toggle-icon {
            transition: transform 0.2s;
        }

        .jf-jobrole-group-content {
            max-height: 0;
            overflow: hidden;
            padding: 0;
            background: #fff;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        :is([data-theme-mode="dark"], .dark) .jf-jobrole-group-toggle {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
        }

        :is([data-theme-mode="dark"], .dark) .jf-jobrole-group-toggle:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.12);
        }

        :is([data-theme-mode="dark"], .dark) .jf-jobrole-group-content {
            background: rgb(30, 32, 35) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Job Role group wrapper - dark mode */
        :is([data-theme-mode="dark"], .dark) .jf-jobrole-group-wrapper {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-jobrole-group-toggle span {
            color: #cbd5e1;
        }

        :is([data-theme-mode="dark"], .dark) .jf-jobrole-group-toggle span span {
            background: rgba(245, 158, 11, 0.8) !important;
            color: white;
        }

        .jf-sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid #f3f4f6;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            background: #fafafa;
        }

        [data-theme-mode="dark"] .jf-sidebar-footer,
        .dark .jf-sidebar-footer {
            border-top-color: rgba(255, 255, 255, 0.06);
            background: rgba(255, 255, 255, 0.02);
        }

        /* Active chips in sidebar header */
        .jf-active-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 600;
            background: var(--cd-accent-light);
            color: var(--cd-accent);
            border: 1px solid var(--cd-accent-border);
        }

        /* Mobile filter toggle */
        .jf-mobile-filter-btn {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 0.6rem 1rem;
            border-radius: 10px;
            border: 1.5px solid var(--cd-border);
            background: #fff;
            color: var(--cd-text);
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 1rem;
        }

        @media (max-width: 900px) {
            .jf-mobile-filter-btn {
                display: inline-flex;
            }
        }

        [data-theme-mode="dark"] .jf-mobile-filter-btn,
        .dark .jf-mobile-filter-btn {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.12);
            color: #d1d5db;
        }

        /* ══ Premium SaaS Elite Search Pill ══ */
        .jf-search-pill {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            height: 68px;
            background: #fff;
            border-radius: 34px;
            border: 1.5px solid #e2e8f0;
            padding: 0 0.75rem 0 1.5rem;
            box-shadow: 0 10px 30px -8px rgba(0, 0, 0, 0.06), 0 4px 12px -4px rgba(0, 0, 0, 0.04);
            transition: all 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            width: 100%;
            margin-bottom: 1.25rem;
            gap: 0;
        }

        .jf-search-pill:focus-within {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.10), 0 15px 35px -10px rgba(99, 102, 241, 0.12);
            transform: translateY(-2px);
        }

        [data-theme-mode="dark"] .jf-search-pill,
        .dark .jf-search-pill {
            background: var(--bg-hover, #1e293b);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .jf-pill-divider {
            width: 1px;
            height: 28px;
            background: #e2e8f0;
            flex-shrink: 0;
            margin: 0 0.5rem;
        }

        [data-theme-mode="dark"] .jf-pill-divider,
        .dark .jf-pill-divider {
            background: rgba(255, 255, 255, 0.08);
        }

        .jf-pill-field {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0 0.5rem;
            min-width: 0;
            position: relative;
        }

        .jf-pill-field i {
            font-size: 1.1rem;
            color: #94a3b8;
            flex-shrink: 0;
            transition: color 0.2s;
        }

        .jf-pill-field:focus-within i {
            color: #6366f1;
        }

        .jf-pill-input {
            width: 100%;
            border: none !important;
            background: transparent !important;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--cd-text, #1e293b);
            outline: none !important;
            box-shadow: none !important;
        }

        .jf-pill-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        [data-theme-mode="dark"] .jf-pill-input,
        .dark .jf-pill-input {
            color: #e5e7eb;
        }

        .jf-pill-select {
            width: 100%;
            border: none !important;
            background: transparent !important;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--cd-text, #1e293b);
            outline: none !important;
            cursor: pointer;
            padding: 0;
            min-width: 0;
        }

        [data-theme-mode="dark"] .jf-pill-select,
        .dark .jf-pill-select {
            color: #e5e7eb;
        }

        .jf-pill-submit {
            flex-shrink: 0;
            height: 48px;
            padding: 0 1.5rem;
            border-radius: 24px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #fff;
            font-size: 0.9375rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s;
            white-space: nowrap;
            box-shadow: 0 4px 12px -2px rgba(79, 70, 229, 0.4);
            letter-spacing: 0.01em;
        }

        .jf-pill-submit:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 18px -4px rgba(79, 70, 229, 0.45);
        }

        .jf-pill-submit:active {
            transform: scale(0.97);
        }

        /* Clear button */
        .jf-pill-clear {
            flex-shrink: 0;
            display: none;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #94a3b8;
            border: none;
            cursor: pointer;
            margin-right: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .jf-pill-clear:hover {
            background: #fee2e2;
            color: #ef4444;
        }

        .jf-pill-clear.visible {
            display: inline-flex;
        }

        /* Custom Dropdown */
        .jf-custom-select {
            position: relative;
            width: 100%;
            height: 48px;
            display: flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
        }

        .jf-select-trigger {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--cd-text, #1e293b);
        }

        .jf-select-options {
            position: absolute;
            top: calc(100% + 12px);
            left: -12px;
            right: -12px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.12), 0 5px 15px -5px rgba(0, 0, 0, 0.06);
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s ease;
            max-height: 180px;
            /* Approximately 4-5 items */
            overflow-y: auto;
            padding: 6px;
        }

        /* Premium Scrollbar for the dropdown */
        .jf-select-options::-webkit-scrollbar {
            width: 5px;
        }

        .jf-select-options::-webkit-scrollbar-track {
            background: transparent;
        }

        .jf-select-options::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .jf-select-options::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        .jf-custom-select.open .jf-select-options {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        [data-theme-mode="dark"] .jf-select-options,
        .dark .jf-select-options {
            background: #1e293b;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .jf-option {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.875rem;
            color: var(--cd-text-secondary);
            transition: all 0.15s;
        }

        .jf-option:hover {
            background: #f1f5f9;
            color: #6366f1;
        }

        .jf-option.selected {
            background: rgba(99, 102, 241, 0.08);
            color: #6366f1;
            font-weight: 700;
        }

        [data-theme-mode="dark"] .jf-option:hover,
        .dark .jf-option:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .jf-select-trigger i.ri-arrow-down-s-line {
            transition: transform 0.2s;
            color: #94a3b8;
        }

        .jf-custom-select.open i.ri-arrow-down-s-line {
            transform: rotate(180deg);
        }

        @media (max-width: 768px) {
            .jf-search-pill {
                height: auto;
                flex-direction: column;
                padding: 0.875rem;
                border-radius: 20px;
                gap: 0.75rem;
            }

            .jf-pill-field {
                width: 100%;
                padding: 0;
            }

            .jf-pill-divider {
                width: 100%;
                height: 1px;
                margin: 0;
            }

            .jf-pill-submit {
                width: 100%;
                justify-content: center;
                height: 44px;
            }

            .jf-pill-clear {
                align-self: flex-end;
            }
        }

        /* ── Modern Minimalist Header (Interactive Board Style) ── */
        .jf-header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .jf-header-content {
            flex: 1;
        }

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

        .jf-header-desc b {
            color: #6366f1;
            font-weight: 700;
        }

        .jf-header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
        }

        @media (max-width: 992px) {
            .jf-header-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.5rem;
            }

            .jf-header-title {
                font-size: 1.875rem;
            }

            .jf-header-actions {
                width: 100%;
                flex-wrap: wrap;
            }
        }

        /* ── Search bar (Old style - hidden) ── */
        .jf-search-bar {
            display: none !important;
        }

        .jf-search-field {
            flex: 2;
            min-width: 180px;
            position: relative;
        }

        .jf-search-field i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.1rem;
            pointer-events: none;
        }

        .jf-search-input {
            width: 100%;
            height: 48px;
            padding: 0 16px 0 42px;
            border: 1.5px solid var(--cd-border);
            border-radius: 12px;
            font-size: 0.9375rem;
            background: #fff;
            color: var(--cd-text);
            transition: all 0.2s;
            outline: none;
        }

        .jf-search-input:focus {
            border-color: var(--cd-accent);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background: #fff;
        }

        .jf-search-input::placeholder {
            color: #9ca3af;
        }

        [data-theme-mode="dark"] .jf-search-input,
        .dark .jf-search-input {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.12);
            color: #e5e7eb;
        }

        .jf-search-select {
            flex: 1;
            min-width: 140px;
            height: 48px;
            padding: 0 14px;
            border: 1.5px solid var(--cd-border);
            border-radius: 12px;
            font-size: 0.9375rem;
            background: #fff;
            color: var(--cd-text);
            cursor: pointer;
        }

        [data-theme-mode="dark"] .jf-search-select,
        .dark .jf-search-select {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.12);
            color: #e5e7eb;
        }

        .jf-search-btn {
            height: 48px;
            padding: 0 1.75rem;
            border-radius: 12px;
            background: var(--cd-accent);
            color: #fff;
            font-size: 0.9375rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            white-space: nowrap;
            box-shadow: 0 4px 10px -2px rgba(79, 70, 229, 0.35);
        }

        .jf-search-btn:hover {
            background: var(--cd-accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 8px 15px -3px rgba(79, 70, 229, 0.4);
        }

        /* ── Job Cards ── */
        .jf-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }

        @media (max-width: 1300px) {
            .jf-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .jf-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        .jf-card {
            background: #fff;
            border: 1px solid var(--cd-border);
            border-radius: 1.25rem;
            padding: 1.5rem;
            margin-bottom: 0;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px -4px rgba(0, 0, 0, 0.05);
        }

        .jf-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09);
            border-color: #c7d2fe;
            transform: translateY(-2px);
        }

        [data-theme-mode="dark"] .jf-card,
        .dark .jf-card {
            background: var(--bodybg, #1a1c2e);
            border-color: rgba(255, 255, 255, 0.08);
        }

        [data-theme-mode="dark"] .jf-card:hover,
        .dark .jf-card:hover {
            border-color: #818cf8;
        }

        .jf-card-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .jf-action-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .jf-save-btn {
            background: #fef2f2;
            color: #ef4444;
        }

        .jf-save-btn:hover {
            background: #fee2e2;
        }

        .jf-save-btn[data-saved="1"] {
            background: #ef4444;
            color: #fff;
        }

        [data-theme-mode="dark"] .jf-save-btn,
        .dark .jf-save-btn {
            background: rgba(255, 255, 255, 0.05);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2) !important;
        }

        [data-theme-mode="dark"] .jf-save-btn[data-saved="1"],
        .dark .jf-save-btn[data-saved="1"] {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444 !important;
        }

        .jf-card-identity {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .jf-logo {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            color: #fff;
            overflow: hidden;
            border: 2px solid #e0e7ff;
        }

        [data-theme-mode="dark"] .jf-logo,
        .dark .jf-logo {
            border-color: rgba(99, 102, 241, 0.3);
        }

        .jf-card-info {
            flex: 1;
            min-width: 0;
        }

        .jf-company-name {
            font-size: 1rem;
            font-weight: 800;
            color: var(--cd-text);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 2px;
        }

        .jf-company-name:hover {
            color: var(--cd-accent);
        }

        [data-theme-mode="dark"] .jf-company-name,
        .dark .jf-company-name {
            color: #f1f5f9;
        }

        .jf-role {
            font-size: 0.875rem;
            color: var(--cd-text-secondary);
            margin-top: 2px;
        }

        .jf-category {
            font-size: 0.8125rem;
            color: var(--cd-text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 2px;
        }

        [data-theme-mode="dark"] .jf-role,
        .dark .jf-role {
            color: #cbd5e1;
        }

        [data-theme-mode="dark"] .jf-category,
        .dark .jf-category {
            color: #9ca3af;
        }

        .jf-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .jf-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .jf-card-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #f3f4f6;
            margin-top: auto;
        }

        [data-theme-mode="dark"] .jf-card-footer,
        .dark .jf-card-footer {
            border-top-color: rgba(255, 255, 255, 0.06);
        }

        .jf-pay {
            font-size: 0.875rem;
            color: var(--cd-text-secondary);
            line-height: 1.7;
        }

        .jf-pay strong {
            color: var(--cd-text);
            font-weight: 700;
        }

        [data-theme-mode="dark"] .jf-pay,
        html[data-theme-mode="dark"] .jf-pay,
        body[data-theme-mode="dark"] .jf-pay,
        [data-bs-theme="dark"] .jf-pay,
        .dark .jf-pay,
        html.dark .jf-pay {
            color: #d1d5db !important;
        }

        [data-theme-mode="dark"] .jf-pay strong,
        html[data-theme-mode="dark"] .jf-pay strong,
        body[data-theme-mode="dark"] .jf-pay strong,
        [data-bs-theme="dark"] .jf-pay strong,
        .dark .jf-pay strong,
        html.dark .jf-pay strong {
            color: #e5e7eb !important;
        }

        .jf-view-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            background: var(--cd-accent);
            color: #fff;
            font-size: 0.9rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
            box-shadow: 0 3px 8px -2px rgba(79, 70, 229, 0.3);
        }

        .jf-view-btn:hover {
            background: var(--cd-accent-hover);
            color: #fff;
            transform: translateY(-1px);
        }



        /* Results bar */
        .jf-results-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .jf-results-count {
            font-size: 1rem;
            font-weight: 700;
            color: var(--cd-text);
        }

        [data-theme-mode="dark"] .jf-results-count,
        .dark .jf-results-count {
            color: #e5e7eb;
        }
    </style>

    {{-- Modern Minimalist Header (Interactive Board Style) --}}
    <x-modern-header chip="Job Listing">
        <x-slot name="titleContent">Search Jobs</x-slot>
        <x-slot name="description">
            Browse and apply to available job opportunities within the <b>Applicant Portal</b>. filter by role, location, or industry to find your next move.
        </x-slot>
        <x-slot name="actions">
            @auth
            <a href="{{ route('applicant.dashboard') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white text-slate-700 font-bold hover:bg-slate-50 transition-all shadow-sm hover:shadow-md border border-slate-200 text-sm dark:bg-slate-800 dark:border-white/10 dark:text-white dark:hover:bg-slate-700">
                <i class="ri-dashboard-line me-2 text-indigo-500"></i> Dashboard
            </a>
            @else
            <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all shadow-sm hover:shadow-md text-sm">
                <i class="ri-login-box-line me-2"></i> Login to Apply
            </a>
            @endauth
        </x-slot>
    </x-modern-header>

    {{-- Mobile filter toggle --}}
    <button type="button" class="jf-mobile-filter-btn" onclick="toggleMobileFilters()">
        <i class="ri-equalizer-line"></i> Filters
        @php $activeFilterCount = count(array_filter([request('industry_type'), request('filter_location'), request('recruiter_type'), request('vacancies'), request('employment_type'), request('job_role')])); @endphp
        @if($activeFilterCount > 0)
        <span style="background:var(--cd-accent);color:#fff;border-radius:20px;padding:1px 8px;font-size:0.8rem">{{ $activeFilterCount }}</span>
        @endif
    </button>

    {{-- ═══ Two-column layout ═══ --}}
    <div class="max-w-7xl mx-auto  pb-4 px-4 sm:px-6 lg:px-8 !pt-0" id="wt-jobs">
        <div class="jf-layout">

            {{-- ══ LEFT SIDEBAR: Filters ══ --}}
            <aside class="jf-sidebar cd-card" id="jf-sidebar" id="wt-filters">
                <div class="jf-sidebar-card">
                    {{-- Header --}}
                    <div class="jf-sidebar-header">
                        <span class="jf-sidebar-title"><i class="ri-equalizer-line"></i> Filters</span>
                        @if($activeFilterCount > 0)
                        <span class="jf-active-chip">{{ $activeFilterCount }} active</span>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('jobs') }}" id="job-filter-form">
                        {{-- Preserve search bar values --}}
                        <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                        <input type="hidden" name="location" value="{{ request('location') }}">
                        <input type="hidden" name="category" value="{{ request('category') }}">

                        <div class="jf-sidebar-body">

                            {{-- Industry Type --}}
                            <div class="jf-filter-section">
                                <div class="jf-group-label"><i class="ri-building-line"></i> Industry</div>
                                @foreach(($filterOptions['industry_type'] ?? collect())->take(6) as $option)
                                <label class="jf-check">
                                    <input type="checkbox" name="industry_type[]" value="{{ $option->value }}"
                                        {{ in_array($option->value, (array) request('industry_type', [])) ? 'checked' : '' }}>
                                    <span>{{ $option->label }}</span>
                                </label>
                                @endforeach
                            </div>

                            {{-- Location --}}
                            <div class="jf-filter-section">
                                <div class="jf-group-label"><i class="ri-map-pin-line"></i> Location</div>
                                @foreach(($filterOptions['location'] ?? collect())->take(6) as $option)
                                <label class="jf-check">
                                    <input type="checkbox" name="filter_location[]" value="{{ $option->value }}"
                                        {{ in_array($option->value, (array) request('filter_location', [])) ? 'checked' : '' }}>
                                    <span>{{ $option->label }}</span>
                                </label>
                                @endforeach
                            </div>

                            {{-- Employment Type --}}
                            <div class="jf-filter-section">
                                <div class="jf-group-label"><i class="ri-briefcase-line"></i> Employment Type</div>
                                @foreach(($filterOptions['employment_type'] ?? collect()) as $option)
                                <label class="jf-check">
                                    <input type="checkbox" name="employment_type[]" value="{{ $option->value }}"
                                        {{ in_array($option->value, (array) request('employment_type', [])) ? 'checked' : '' }}>
                                    <span>{{ $option->label }}</span>
                                </label>
                                @endforeach
                            </div>

                            {{-- Job Roles --}}
                            <div class="jf-filter-section">
                                <div class="jf-group-label"><i class="ri-apps-line"></i> Job Roles</div>
                                @php 
                                    $jobRoleFilter = (array) request('job_role', []);
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
                                    $allOptions = collect($filterOptions['expertise_category'] ?? []);
                                @endphp

                                <div style="max-height: 500px; overflow-y: auto;">
                                    @foreach($categoryGroups as $groupName => $groupValues)
                                        @php
                                            // Count how many in this group are selected
                                            $groupOptions = $allOptions->whereIn('value', $groupValues)->sortBy('sort_order');
                                            $selectedCount = $groupOptions->filter(fn($opt) => in_array($opt->value, $jobRoleFilter))->count();
                                            $groupId = 'jobrole_' . str_replace(' ', '_', strtolower($groupName));
                                        @endphp
                                        <div class="jf-jobrole-group-wrapper" style="margin-bottom: 0.5rem; border: 1px solid #f0f0f0; border-radius: 6px; overflow: hidden;">
                                            <button type="button" class="jf-jobrole-group-toggle" id="{{ $groupId }}_btn" onclick="toggleJobRoleGroup('{{ $groupId }}')" style="width: 100%;">
                                                <span style="display: flex; align-items: center; gap: 8px;">
                                                    <i class="ri-arrow-right-s-line jf-jobrole-toggle-icon" style="transition: transform 0.2s;" id="{{ $groupId }}_icon"></i>
                                                    {{ $groupName }}
                                                    @if($selectedCount > 0)
                                                        <span style="background: #f59e0b; color: white; border-radius: 12px; padding: 2px 6px; font-size: 0.65rem; font-weight: 700;">{{ $selectedCount }}</span>
                                                    @endif
                                                </span>
                                            </button>
                                            <div class="jf-jobrole-group-content" id="{{ $groupId }}_content" style="max-height: 0; overflow: hidden; padding: 0; background: #fff; transition: max-height 0.3s ease, padding 0.3s ease;" data-collapsed="true">
                                                @foreach($groupOptions as $option)
                                                    <label class="jf-check" style="margin-bottom: 0.5rem;">
                                                        <input type="checkbox" name="job_role[]" value="{{ $option->value }}"
                                                            {{ in_array($option->value, $jobRoleFilter) ? 'checked' : '' }}>
                                                        <span style="font-size: 0.875rem;">{{ $option->label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <script>
                                    function toggleJobRoleGroup(groupId) {
                                        const content = document.getElementById(groupId + '_content');
                                        const icon = document.getElementById(groupId + '_icon');
                                        const isCollapsed = content.getAttribute('data-collapsed') === 'true';
                                        
                                        if (isCollapsed) {
                                            // Expand
                                            content.style.maxHeight = '5000px';
                                            content.style.padding = '0.625rem 0.75rem';
                                            content.setAttribute('data-collapsed', 'false');
                                            icon.style.transform = 'rotate(90deg)';
                                            localStorage.setItem('jobrole_group_' + groupId, 'open');
                                        } else {
                                            // Collapse
                                            content.style.maxHeight = '0';
                                            content.style.padding = '0';
                                            content.setAttribute('data-collapsed', 'true');
                                            icon.style.transform = 'rotate(0deg)';
                                            localStorage.setItem('jobrole_group_' + groupId, 'closed');
                                        }
                                    }

                                    // Initialize collapsed state on page load (default: collapsed)
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const groups = document.querySelectorAll('.jf-jobrole-group-content');
                                        groups.forEach(group => {
                                            const groupId = group.id.replace('_content', '');
                                            const state = localStorage.getItem('jobrole_group_' + groupId);
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

                            {{-- Recruiter Type --}}
                            <div class="jf-filter-section">
                                <div class="jf-group-label"><i class="ri-user-follow-line"></i> Recruiter Type</div>
                                @foreach(($filterOptions['recruiter_type'] ?? collect()) as $option)
                                <label class="jf-check">
                                    <input type="checkbox" name="recruiter_type[]" value="{{ $option->value }}"
                                        {{ in_array($option->value, (array) request('recruiter_type', [])) ? 'checked' : '' }}>
                                    <span>{{ $option->label }}</span>
                                </label>
                                @endforeach
                            </div>

                            {{-- Job Vacancies --}}
                            <div class="jf-filter-section">
                                <div class="jf-group-label"><i class="ri-group-line"></i> Vacancies</div>
                                @foreach(['0-10' => '0 – 10', '11-20' => '11 – 20', '20+' => '20 and above'] as $val => $lbl)
                                <label class="jf-check">
                                    <input type="checkbox" name="vacancies[]" value="{{ $val }}"
                                        {{ in_array($val, (array) request('vacancies', [])) ? 'checked' : '' }}>
                                    <span>{{ $lbl }}</span>
                                </label>
                                @endforeach
                            </div>

                        </div>

                        <div class="jf-sidebar-footer">
                            <button type="submit" class="jf-search-btn" style="height:40px;padding:0 1.25rem;font-size:0.9rem;width:100%;justify-content:center">
                                <i class="ri-check-line"></i> Apply Filters
                            </button>
                            <a href="{{ route('jobs', array_filter(['keyword' => request('keyword'), 'location' => request('location'), 'category' => request('category')])) }}"
                                style="height:40px;padding:0;border-radius:10px;border:1.5px solid var(--cd-border);background:transparent;color:var(--cd-text-secondary);font-size:0.875rem;font-weight:600;display:flex;align-items:center;justify-content:center;gap:6px;text-decoration:none;transition:all 0.2s">
                                <i class="ri-refresh-line"></i> Reset Filters
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- ══ RIGHT MAIN CONTENT ══ --}}
            <div>

                {{-- ═══ Premium Search Pill ═══ --}}
                <div id="wt-searchbar">
                    <form method="GET" action="{{ route('jobs') }}" id="jobs-search-form" class="" onsubmit="patchHiddenFields()">
                        {{-- Preserve sidebar filter values --}}
                        @foreach((array) request('industry_type', []) as $v)
                        <input type="hidden" name="industry_type[]" value="{{ $v }}" class="sf-preserve">
                        @endforeach
                        @foreach((array) request('filter_location', []) as $v)
                        <input type="hidden" name="filter_location[]" value="{{ $v }}" class="sf-preserve">
                        @endforeach
                        @foreach((array) request('recruiter_type', []) as $v)
                        <input type="hidden" name="recruiter_type[]" value="{{ $v }}" class="sf-preserve">
                        @endforeach
                        @foreach((array) request('vacancies', []) as $v)
                        <input type="hidden" name="vacancies[]" value="{{ $v }}" class="sf-preserve">
                        @endforeach
                        @foreach((array) request('employment_type', []) as $v)
                        <input type="hidden" name="employment_type[]" value="{{ $v }}" class="sf-preserve">
                        @endforeach
                        @foreach((array) request('job_role', []) as $v)
                        <input type="hidden" name="job_role[]" value="{{ $v }}" class="sf-preserve">
                        @endforeach

                        <div class="jf-search-pill" id="jf-search-pill">
                            {{-- Keyword --}}
                            <div class="jf-pill-field" style="flex:3">
                                <i class="ri-search-2-line"></i>
                                <input
                                    id="jf-keyword"
                                    name="keyword"
                                    type="text"
                                    class="jf-pill-input"
                                    placeholder="Search roles, companies, skills…"
                                    value="{{ request('keyword') }}"
                                    autocomplete="off"
                                    oninput="liveFilterJobs()">
                            </div>

                            <div class="jf-pill-divider"></div>

                            {{-- Location (Custom Dropdown) --}}
                            <div class="jf-pill-field" style="flex:2">
                                <i class="ri-map-pin-line"></i>
                                <div class="jf-custom-select" id="jf-location-select">
                                    <div class="jf-select-trigger">
                                        <span id="jf-location-label">{{ request('location') ?: 'Location' }}</span>
                                        <i class="ri-arrow-down-s-line"></i>
                                    </div>
                                    <div class="jf-select-options">
                                        <div class="jf-option {{ !request('location') ? 'selected' : '' }}" data-value="">Any Location</div>
                                        @foreach(($filterOptions['location'] ?? collect()) as $opt)
                                        <div class="jf-option {{ request('location') === $opt->label ? 'selected' : '' }}"
                                            data-value="{{ $opt->label }}">
                                            {{ $opt->label }}
                                        </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="location" id="jf-location" value="{{ request('location') }}">
                                </div>
                            </div>

                            <div class="jf-pill-divider"></div>

                            {{-- Category (Custom Dropdown) --}}
                            <div class="jf-pill-field" style="flex:2">
                                <i class="ri-briefcase-line"></i>
                                <div class="jf-custom-select" id="jf-category-select">
                                    <div class="jf-select-trigger">
                                        <span id="jf-category-label">All Categories</span>
                                        <i class="ri-arrow-down-s-line"></i>
                                    </div>
                                    <div class="jf-select-options">
                                        <div class="jf-option selected" data-value="">All Categories</div>
                                        @foreach(($filterOptions['job_category'] ?? collect()) as $option)
                                        <div class="jf-option {{ request('category') === $option->value ? 'selected' : '' }}"
                                            data-value="{{ $option->value }}">
                                            {{ $option->label }}
                                        </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="category" id="jf-category" value="{{ request('category') }}">
                                </div>
                            </div>

                            {{-- Clear button --}}
                            <button type="button" class="jf-pill-clear !p-0" id="jf-pill-clear" onclick="clearPillSearch()" title="Clear search">
                                <i class="ri-close-line"></i>
                            </button>

                            {{-- Submit --}}
                            <button type="submit" class="jf-pill-submit">
                                <i class="ri-search-line"></i> Search
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Results count logic was moved here, but currently hidden/removed per request --}}

                {{-- ═══ Results count ═══ --}}
                @php
                $totalJobs = isset($jobs) && $jobs instanceof \Illuminate\Pagination\LengthAwarePaginator
                ? $jobs->total()
                : count($jobs);
                $hasJobSearch = request()->filled('keyword') || request()->filled('location') || request()->filled('category')
                || request()->filled('industry_type') || request()->filled('filter_location')
                || request()->filled('recruiter_type') || request()->filled('vacancies') || request()->filled('employment_type');
                @endphp
                <!-- <div class="jf-results-bar">
                    <span class="jf-results-count">
                        <i class="ri-briefcase-line me-1" style="color:var(--cd-accent)"></i>
                        {{ $totalJobs }} Job{{ $totalJobs !== 1 ? 's' : '' }}
                        @if($hasJobSearch) <span style="font-weight:400;color:var(--cd-text-muted)"> found</span> @endif
                    </span>
                </div> -->

                {{-- ═══ Job Cards ═══ --}}
                <div class="jf-cards-grid" id="wt-results">
                    @foreach ($jobs as $index => $job)
                    @php
                    $colors = ['#4f46e5', '#0891b2', '#059669', '#d97706', '#dc2626', '#7c3aed', '#db2777'];
                    $color = $colors[$index % count($colors)];
                    $initials = collect(explode(' ', $job['company']))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
                    @endphp
                    <div class="jf-card job-card"
                        data-index="{{ $index }}"
                        data-company="{{ strtolower($job['company']) }}"
                        data-role="{{ strtolower($job['role']) }}"
                        data-location-text="{{ strtolower($job['location']) }}"
                        data-location-key="{{ strtolower($job['location_key']) }}"
                        data-industry="{{ strtolower($job['industry']) }}"
                        data-recruiter="{{ strtolower($job['recruiter_type']) }}"
                        data-vacancies-range="{{ strtolower($job['vacancies_range']) }}"
                        data-employment="{{ strtolower($job['employment_type']) }}"
                        data-category="{{ strtolower($job['category_key']) }}">

                        {{-- Actions --}}
                        <div class="jf-card-actions">
                            <form method="POST" action="{{ route('jobs.save', $job['slug']) }}" class="inline job-save-form" style="flex-shrink:0">
                                @csrf
                                <button type="submit" class="jf-action-btn jf-save-btn job-save-toggle"
                                    data-saved="{{ $job['is_saved'] ? '1' : '0' }}"
                                    aria-label="{{ $job['is_saved'] ? 'Saved job' : 'Save job' }}" title="{{ $job['is_saved'] ? 'Saved' : 'Save Job' }}">
                                    <i class="{{ $job['is_saved'] ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                                </button>
                            </form>
                        </div>

                        {{-- Identity --}}
                        <div class="jf-card-identity">
                            <div class="jf-logo" style="background:{{ $color }}">
                                @if($job['logo'])
                                <img src="{{ $job['logo'] }}" alt="{{ $job['company'] }}" style="width:100%;height:100%;object-fit:cover">
                                @else
                                {{ $initials }}
                                @endif
                            </div>
                            <div class="jf-card-info">
                                @guest
                                <span class="jf-company-name" style="filter:blur(4px);-webkit-filter:blur(4px)">{{ $job['company'] }}
                                    @if($job['verified'])
                                    <span style="color:#4f46e5;font-size:0.9rem" title="Verified"><i class="ri-verified-badge-fill"></i></span>
                                    @endif
                                </span>
                                @else
                                <a href="{{ route('jobs.show', $job['slug']) }}" class="jf-company-name">{{ $job['company'] }}
                                    @if($job['verified'])
                                    <span style="color:#4f46e5;font-size:0.9rem" title="Verified"><i class="ri-verified-badge-fill"></i></span>
                                    @endif
                                </a>
                                @endguest
                                <div class="jf-role">{{ $job['role'] }}</div>
                                <div class="jf-category"><i class="ri-map-pin-line"></i>{{ $job['location'] }}</div>
                            </div>
                        </div>

                        {{-- Rating --}}
                        <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem">
                            <span style="color:var(--cd-text-muted)">Rating:</span>
                            @if(isset($job['company_id']) && $job['company_id'])
                            <div class="company-rating-stars" style="display:inline-flex;gap:2px;{{ auth()->check() ? 'cursor:pointer' : '' }}"
                                data-company-id="{{ $job['company_id'] }}"
                                data-rating="{{ $job['rating'] ?? 0 }}"
                                data-rate-url="{{ route('companies.rate', ['company' => $job['company_id']]) }}"
                                data-can-rate="{{ auth()->check() ? '1' : '0' }}">
                                @php $avgRating = $job['rating'] ?? 0; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=floor($avgRating))
                                    <span class="star-btn" data-star="{{ $i }}" style="color:#f59e0b;font-size:1rem"><i class="bi bi-star-fill"></i></span>
                                    @elseif($i - 0.5 <= $avgRating)
                                        <span class="star-btn" data-star="{{ $i }}" style="color:#f59e0b;font-size:1rem"><i class="bi bi-star-half"></i></span>
                                        @else
                                        <span class="star-btn" data-star="{{ $i }}" style="color:#f59e0b;font-size:1rem"><i class="bi bi-star"></i></span>
                                        @endif
                                        @endfor
                            </div>
                            <span class="company-rating-count" style="color:var(--cd-text-muted);font-size:0.8125rem">
                                (<span class="rating-count-value">{{ $job['rating_count'] ?? 0 }}</span>)
                            </span>
                            @else
                            <span style="color:var(--cd-text-muted)">—</span>
                            @endif
                        </div>

                        {{-- Tags --}}
                        <div class="jf-tags">
                            @if($job['established'])
                            <span class="jf-tag" style="background:#eef2ff;color:#4f46e5"><i class="ri-calendar-line"></i>Since {{ $job['established'] }}</span>
                            @endif
                            @if($job['posted_at'])
                            <span class="jf-tag" style="background:#ecfdf5;color:#059669"><i class="ri-time-line"></i>{{ \Carbon\Carbon::parse($job['posted_at'])->diffForHumans() }}</span>
                            @endif
                            <span class="jf-tag" style="background:#fff7ed;color:#d97706"><i class="ri-team-line"></i>Staff: {{ $job['employees'] }}</span>
                            <span class="jf-tag" style="background:#fdf4ff;color:#9333ea"><i class="ri-briefcase-line"></i>Vacancies: {{ $job['vacancies'] }}</span>
                        </div>

                        {{-- Footer --}}
                        <div class="jf-card-footer">
                            <div class="jf-pay">
                                @php
                                $salaryText = 'Competitive';
                                if (!empty($job['salary_min']) && !empty($job['salary_max'])) {
                                $salaryText = ($job['salary_currency'] ?? 'PHP') . ' ' . number_format($job['salary_min']) . ' – ' . ($job['salary_currency'] ?? 'PHP') . ' ' . number_format($job['salary_max']);
                                } elseif (!empty($job['salary_min'])) {
                                $salaryText = ($job['salary_currency'] ?? 'PHP') . ' ' . number_format($job['salary_min']);
                                }
                                @endphp
                                Salary: <strong>{{ $salaryText }}</strong><br>
                                Category: <strong>{{ Str::headline($job['category'] ?? '') }}</strong>
                            </div>
                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px">
                                <a href="{{ route('jobs.show', $job['slug']) }}" class="jf-view-btn">
                                    View Details <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Empty state --}}
                @if(count($jobs) === 0)
                <div class="cd-section">
                    <div class="cd-empty">
                        <i class="ri-briefcase-line"></i>
                        <p>No jobs found matching your search criteria.</p>
                        <a href="{{ route('jobs') }}" class="flex text-center items-center justify-center rounded-full !p-3 !py-0" style="font-size:1rem;padding: 0">
                            <i class="ri-refresh-line me-1"></i> Clear Filters
                        </a>
                    </div>
                </div>
                @endif

                {{-- Load More Button --}}
                <div id="jf-load-more-container" style="text-align:center; padding: 2rem 0; display: {{ ($hasMore ?? false) ? 'block' : 'none' }};">
                    <button type="button" id="jf-load-more-btn" class="jf-search-btn" style="padding: 0 2rem; height: 48px; border-radius: 12px; font-size: 1rem;">
                        Load More Jobs
                    </button>
                </div>

                {{-- Load more spinner --}}
                <div id="jf-loader" style="display:none;text-align:center;padding:2rem 0">
                    <div style="display:inline-flex;align-items:center;gap:10px;color:var(--cd-text-muted);font-size:1rem">
                        <svg style="width:22px;height:22px;animation:jf-spin 0.8s linear infinite" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="31.4" stroke-dashoffset="10" stroke-linecap="round" />
                        </svg>
                        Loading more jobs…
                    </div>
                </div>

                {{-- End of results message --}}
                <div id="jf-end" style="display:none;text-align:center;padding:1.5rem 0">
                    <span style="color:var(--cd-text-muted);font-size:0.9375rem">
                        <i class="ri-checkbox-circle-line me-1" style="color:#22c55e"></i>
                        You've seen all <strong id="jf-total-label">{{ $total }}</strong> jobs
                    </span>
                </div>

            </div>{{-- end right column --}}
        </div>
        {{-- end .jf-layout --}}
    </div>
    <style>
        @keyframes jf-spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    {{-- ═══ Scripts ═══ --}}
    <script>
        // ── Filter panel toggle ──
        function toggleFilters() {
            const panel = document.getElementById('jf-panel');
            const arrow = document.getElementById('jf-toggle-arrow');
            const btn = document.getElementById('jf-toggle');
            const isOpen = panel.classList.contains('open');
            panel.classList.toggle('open', !isOpen);
            btn.classList.toggle('active', !isOpen);
            if (arrow) arrow.style.transform = isOpen ? '' : 'rotate(180deg)';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const panel = document.getElementById('jf-panel');
            const arrow = document.getElementById('jf-toggle-arrow');
            if (panel && panel.classList.contains('open') && arrow) {
                arrow.style.transform = 'rotate(180deg)';
            }
        });

        // ── Infinite Scroll ──
        (function() {
            let currentPage = {
                {
                    $jobs - > currentPage()
                }
            };
            let hasMore = {
                {
                    $hasMore ? 'true' : 'false'
                }
            };
            let loading = false;
            const perPage = {
                {
                    $jobs - > perPage()
                }
            };
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const container = document.getElementById('wt-results');
            const loader = document.getElementById('jf-loader');
            const endMsg = document.getElementById('jf-end');
            const totalLabel = document.getElementById('jf-total-label');
            const loadMoreBtnContainer = document.getElementById('jf-load-more-container');
            const loadMoreBtn = document.getElementById('jf-load-more-btn');

            // Colour palette for logos (matches PHP side)
            const colours = ['#4f46e5', '#0891b2', '#059669', '#d97706', '#dc2626', '#7c3aed', '#db2777'];

            // ── Build a card HTML string from a JSON job object ──
            function buildCard(job, index) {
                const color = colours[index % colours.length];
                const initials = job.company.split(' ').map(w => w[0]?.toUpperCase() ?? '').slice(0, 2).join('');
                const logoHtml = job.logo ?
                    `<img src="${job.logo}" alt="${escHtml(job.company)}" style="width:100%;height:100%;object-fit:cover">` :
                    escHtml(initials);

                const saveIcon = job.is_saved ? 'ri-heart-fill' : 'ri-heart-line';
                const saveBg = job.is_saved ? 'background:#ef4444;color:#fff;' : '';

                const metaEstablished = job.established ?
                    `<span class="jf-tag" style="background:#eef2ff;color:#4f46e5"><i class="ri-calendar-line"></i>Since ${escHtml(String(job.established))}</span>` :
                    '';

                const metaPosted = job.posted_at ?
                    `<span class="jf-tag" style="background:#ecfdf5;color:#059669"><i class="ri-time-line"></i>${timeAgo(job.posted_at)}</span>` :
                    '';

                const verifiedBadge = job.verified ?
                    `<span style="color:#4f46e5;font-size:0.9rem" title="Verified"><i class="ri-verified-badge-fill"></i></span>` :
                    '';

                const stars = buildStars(job.rating ?? 0);
                const detailUrl = `/jobs/${encodeURIComponent(job.slug)}`;
                const saveUrl = `/jobs/${encodeURIComponent(job.slug)}/save`;

                let salaryText = 'Competitive';
                if (job.salary_min && job.salary_max) {
                    salaryText = `${job.salary_currency || 'PHP'} ${Number(job.salary_min).toLocaleString()} – ${job.salary_currency || 'PHP'} ${Number(job.salary_max).toLocaleString()}`;
                } else if (job.salary_min) {
                    salaryText = `${job.salary_currency || 'PHP'} ${Number(job.salary_min).toLocaleString()}`;
                }

                return `
                <div class="jf-card job-card"
                    data-company="${escAttr(job.company)}"
                    data-role="${escAttr(job.role)}"
                    data-location-text="${escAttr(job.location)}"
                    data-employment="${escAttr(job.employment_type)}">
                    <div class="jf-card-actions">
                        <form method="POST" action="${saveUrl}" class="inline job-save-form" style="flex-shrink:0">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <button type="submit" class="jf-action-btn jf-save-btn job-save-toggle" data-saved="${job.is_saved ? '1' : '0'}" style="${saveBg}" aria-label="${job.is_saved ? 'Saved' : 'Save job'}" title="${job.is_saved ? 'Saved' : 'Save Job'}">
                                <i class="${saveIcon}"></i>
                            </button>
                        </form>
                    </div>
                    <div class="jf-card-identity">
                        <div class="jf-logo" style="background:${color}">${logoHtml}</div>
                        <div class="jf-card-info">
                            <a href="${detailUrl}" class="jf-company-name">${escHtml(job.company)} ${verifiedBadge}</a>
                            <div class="jf-role">${escHtml(job.role)}</div>
                            <div class="jf-category"><i class="ri-map-pin-line"></i>${escHtml(job.location)}</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem">
                        <span style="color:var(--cd-text-muted)">Rating:</span>
                        ${stars}
                        <span class="company-rating-count" style="color:var(--cd-text-muted);font-size:0.8125rem">(<span class="rating-count-value">${job.rating_count ?? 0}</span>)</span>
                    </div>
                    <div class="jf-tags">
                        ${metaEstablished}
                        ${metaPosted}
                        <span class="jf-tag" style="background:#fff7ed;color:#d97706"><i class="ri-team-line"></i>Staff: ${job.employees}</span>
                        <span class="jf-tag" style="background:#fdf4ff;color:#9333ea"><i class="ri-briefcase-line"></i>Vacancies: ${job.vacancies}</span>
                    </div>
                    <div class="jf-card-footer">
                        <div class="jf-pay">
                            Salary: <strong>${escHtml(salaryText)}</strong><br>
                            Category: <strong>${formatLabel(job.category)}</strong>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px">
                            <a href="${detailUrl}" class="jf-view-btn">View Details <i class="ri-arrow-right-line"></i></a>
                        </div>
                    </div>
                </div>`;
            }

            function buildStars(avg) {
                let html = '<div style="display:inline-flex;gap:2px;">';
                for (let i = 1; i <= 5; i++) {
                    let cls = i <= Math.floor(avg) ? 'bi-star-fill' : (i - 0.5 <= avg ? 'bi-star-half' : 'bi-star');
                    html += `<span style="color:#f59e0b;font-size:1rem"><i class="bi ${cls}"></i></span>`;
                }
                return html + '</div>';
            }

            function escHtml(str) {
                return String(str ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }

            function formatLabel(str) {
                // Converts snake_case / kebab-case to Title Case e.g. admin_support → Admin Support
                return String(str ?? '')
                    .replace(/[_\-]+/g, ' ')
                    .replace(/\b\w/g, c => c.toUpperCase());
            }

            function escAttr(str) {
                return String(str ?? '').toLowerCase().replace(/"/g, '&quot;');
            }

            function timeAgo(dateStr) {
                const diff = Date.now() - new Date(dateStr).getTime();
                const mins = Math.floor(diff / 60000);
                if (mins < 60) return mins + 'm ago';
                const hrs = Math.floor(mins / 60);
                if (hrs < 24) return hrs + 'h ago';
                const days = Math.floor(hrs / 24);
                if (days < 30) return days + 'd ago';
                return Math.floor(days / 30) + 'mo ago';
            }

            // ── Attach save-form handler to newly added cards ──
            function bindSaveForm(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = form.querySelector('.job-save-toggle');
                    const icon = btn?.querySelector('i');
                    const url = form.action;
                    const token = form.querySelector('[name="_token"]')?.value;
                    if (!btn) {
                        form.submit();
                        return;
                    }
                    btn.disabled = true;
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                    }).then(r => r.json()).then(data => {
                        const saved = data.status === 'saved';
                        btn.dataset.saved = saved ? '1' : '0';
                        btn.style.background = saved ? '#ef4444' : '';
                        btn.style.color = saved ? '#fff' : '#ef4444';
                        btn.style.borderColor = saved ? '#ef4444' : '#fecaca';
                        if (icon) icon.className = saved ? 'ri-heart-fill' : 'ri-heart-line';
                    }).catch(() => {}).finally(() => {
                        btn.disabled = false;
                    });
                });
            }

            // ── Fetch next page ──
            function loadMore() {
                if (loading || !hasMore) return;
                loading = true;
                if (loadMoreBtnContainer) loadMoreBtnContainer.style.display = 'none';
                loader.style.display = 'block';

                const params = new URLSearchParams(window.location.search);
                params.set('page', currentPage + 1);

                fetch(`${window.location.pathname}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        const startIndex = currentPage * perPage;
                        data.jobs.forEach((job, i) => {
                            const wrapper = document.createElement('div');
                            wrapper.innerHTML = buildCard(job, startIndex + i).trim();
                            const card = wrapper.firstElementChild;
                            container.appendChild(card);
                            // Animate in
                            requestAnimationFrame(() => requestAnimationFrame(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                            }));
                            // Bind save forms
                            card.querySelectorAll('form.job-save-form').forEach(bindSaveForm);
                        });

                        currentPage = data.page;
                        hasMore = data.hasMore;

                        if (totalLabel) totalLabel.textContent = data.total;

                        if (!hasMore) {
                            endMsg.style.display = 'block';
                            loader.style.display = 'none';
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

            // ── Show end message immediately if no more pages ──
            if (!hasMore && {
                    {
                        count($jobs)
                    }
                } > 0) {
                endMsg.style.display = 'block';
            }

            // ── Button Click to trigger load ──
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', loadMore);
            }

        })();
    </script>

    {{-- Save/Unsave JS --}}
    <script>
        (function() {
            function setSavedStyles(btn, saved) {
                var icon = btn.querySelector('i');
                if (saved) {
                    btn.dataset.saved = '1';
                    btn.style.background = '#ef4444';
                    btn.style.color = '#fff';
                    if (icon) {
                        icon.className = 'ri-heart-fill';
                    }
                } else {
                    btn.dataset.saved = '0';
                    btn.style.background = '';
                    btn.style.color = '';
                    if (icon) {
                        icon.className = 'ri-heart-line';
                    }
                }
            }

            function handleJobSaveSubmit(event) {
                event.preventDefault();
                var form = event.target;
                var btn = form.querySelector('.job-save-toggle');
                if (!btn) {
                    form.submit();
                    return;
                }
                var url = form.getAttribute('action');
                var tokenTag = document.querySelector('meta[name="csrf-token"]');
                var token = tokenTag ? tokenTag.getAttribute('content') : null;
                btn.disabled = true;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                    },
                    body: ''
                }).then(function(resp) {
                    if (!resp.ok) throw new Error('Request failed');
                    return resp.json();
                }).then(function(data) {
                    if (data.status === 'saved') {
                        setSavedStyles(btn, true);
                    } else if (data.status === 'removed') {
                        setSavedStyles(btn, false);
                    }
                }).catch(function() {
                    console.log('Something went wrong.');
                }).finally(function() {
                    btn.disabled = false;
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('form.job-save-form').forEach(function(form) {
                    form.addEventListener('submit', handleJobSaveSubmit);
                });
            });
        })();
    </script>

    {{-- Company Rating Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            document.querySelectorAll('.company-rating-stars').forEach(function(container) {
                const rateUrl = container.dataset.rateUrl;
                const canRate = container.dataset.canRate === '1';
                if (!rateUrl) return;
                const stars = container.querySelectorAll('.star-btn');
                const countEl = container.nextElementSibling?.querySelector('.rating-count-value');

                function setStarsDisplay(avg) {
                    const r = parseFloat(avg) || 0;
                    stars.forEach(function(s, i) {
                        const icon = s.querySelector('i');
                        const starNum = i + 1;
                        if (starNum <= Math.floor(r)) icon.className = 'bi bi-star-fill';
                        else if (starNum - 0.5 <= r) icon.className = 'bi bi-star-half';
                        else icon.className = 'bi bi-star';
                    });
                }

                if (canRate) {
                    stars.forEach(function(star, index) {
                        star.addEventListener('mouseenter', function() {
                            stars.forEach(function(s, i) {
                                const icon = s.querySelector('i');
                                icon.className = i <= index ? 'bi bi-star-fill' : 'bi bi-star';
                            });
                        });
                    });
                    container.addEventListener('mouseleave', function() {
                        setStarsDisplay(container.dataset.rating);
                    });
                    stars.forEach(function(star) {
                        star.addEventListener('click', function() {
                            const rating = parseInt(star.dataset.star, 10);
                            fetch(rateUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        rating: rating
                                    })
                                })
                                .then(function(response) {
                                    return response.json();
                                })
                                .then(function(data) {
                                    if (data.success) {
                                        container.dataset.rating = data.average_rating;
                                        setStarsDisplay(data.average_rating);
                                        if (countEl) countEl.textContent = data.rating_count;
                                        if (window.Swal) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Rating Submitted',
                                                text: 'You rated this company ' + rating + ' star' + (rating > 1 ? 's' : ''),
                                                timer: 1500,
                                                showConfirmButton: false
                                            });
                                        }
                                    }
                                })
                                .catch(function(err) {
                                    console.error('Rating error:', err);
                                });
                        });
                    });
                }
            });
        });
    </script>

    {{-- Premium Search & Live Filter JS --}}
    <script>
        (function() {
            const searchPill = document.getElementById('jf-search-pill');
            const clearBtn = document.getElementById('jf-pill-clear');
            const keywordInput = document.getElementById('jf-keyword');
            const locationInput = document.getElementById('jf-location');
            const categoryInput = document.getElementById('jf-category');

            // Custom dropdown components setup
            const setupDropdown = (selectId, labelId, inputId) => {
                const selectEl = document.getElementById(selectId);
                const labelEl = document.getElementById(labelId);
                const inputEl = document.getElementById(inputId);
                const options = selectEl.querySelectorAll('.jf-option');

                selectEl.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // Close others
                    document.querySelectorAll('.jf-custom-select').forEach(s => {
                        if (s !== selectEl) s.classList.remove('open');
                    });
                    this.classList.toggle('open');
                });

                options.forEach(opt => {
                    opt.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const val = this.dataset.value;
                        const txt = this.textContent;

                        inputEl.value = val;
                        labelEl.textContent = txt || 'Location';

                        options.forEach(o => o.classList.remove('selected'));
                        this.classList.add('selected');

                        selectEl.classList.remove('open');
                        liveFilterJobs();
                    });
                });
            };

            setupDropdown('jf-category-select', 'jf-category-label', 'jf-category');
            setupDropdown('jf-location-select', 'jf-location-label', 'jf-location');

            document.addEventListener('click', () => {
                document.querySelectorAll('.jf-custom-select').forEach(s => s.classList.remove('open'));
            });

            const jobCards = document.querySelectorAll('.job-card');

            // ── Live Filtering ──
            window.liveFilterJobs = function() {
                const k = keywordInput.value.toLowerCase().trim();
                const l = locationInput.value.toLowerCase().trim();
                const c = categoryInput.value.toLowerCase().trim();

                jobCards.forEach(card => {
                    const company = card.dataset.company || '';
                    const role = card.dataset.role || '';
                    const locText = card.dataset.locationText || '';
                    const locKey = card.dataset.locationKey || '';
                    const cat = card.dataset.category || '';

                    const matchK = !k || company.includes(k) || role.includes(k);
                    const matchL = !l || locText.includes(l) || locKey.includes(l);
                    const matchC = !c || cat === c;

                    if (matchK && matchL && matchC) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Toggle clear button
                if (clearBtn) {
                    if (k || l || c) clearBtn.classList.add('visible');
                    else clearBtn.classList.remove('visible');
                }
            };

            // ── Clear Search ──
            window.clearPillSearch = function() {
                keywordInput.value = '';

                // Reset location
                locationInput.value = '';
                document.getElementById('jf-location-label').textContent = 'Location';
                const locOpts = document.querySelectorAll('#jf-location-select .jf-option');
                locOpts.forEach(o => o.classList.remove('selected'));
                document.querySelector('#jf-location-select [data-value=""]').classList.add('selected');

                // Reset category
                categoryInput.value = '';
                document.getElementById('jf-category-label').textContent = 'All Categories';
                const catOpts = document.querySelectorAll('#jf-category-select .jf-option');
                catOpts.forEach(o => o.classList.remove('selected'));
                document.querySelector('#jf-category-select [data-value=""]').classList.add('selected');

                liveFilterJobs();
                keywordInput.focus();
            };

            // ── Patch form before submit (sidebar sync) ──
            window.patchHiddenFields = function() {
                // Ensure sidebar hidden fields in this form match the pill values
            };

            // Init state
            window.liveFilterJobs();
        })();
    </script>

    @include('candidates.partials.walkthrough', [
    'wtKey' => 'search_jobs',
    'wtSteps' => [
    ['target' => 'wt-hero', 'icon' => 'ri-search-2-line', 'title' => 'Search Jobs', 'body' => 'Welcome to the job search page! Browse all available positions, filter by your preferences, and find your perfect opportunity.', 'position' => 'bottom'],
    ['target' => 'wt-searchbar', 'icon' => 'ri-search-line', 'title' => 'Search Bar', 'body' => 'Search by company name or role, enter a location, and pick a category. Hit "Search" to find matching jobs instantly.', 'position' => 'bottom'],
    ['target' => 'wt-filters', 'icon' => 'ri-filter-3-fill', 'title' => 'Top Filters', 'body' => 'Click "Filters" to expand the filter panel. Narrow down results by industry, location, employment type, and more.', 'position' => 'bottom'],
    ['target' => 'wt-results', 'icon' => 'ri-briefcase-fill', 'title' => 'Job Results', 'body' => 'Browse job cards with company info, ratings, and badges. Click the heart icon to save jobs for later, or "View Details" to see the full posting and apply.', 'position' => 'top'],
    ]
    ])

</x-app-layout>
