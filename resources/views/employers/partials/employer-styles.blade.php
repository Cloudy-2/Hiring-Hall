{{-- Shared employer page styles --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        /* Typography */
        --cd-font: 'Poppins', system-ui, -apple-system, Segoe UI, sans-serif;
        --cd-text-xs: 0.75rem;
        --cd-text-sm: 0.8125rem;
        --cd-text-base: 0.9375rem;
        --cd-text-md: 1rem;
        --cd-text-lg: 1.125rem;
        --cd-text-xl: 1.375rem;

        /* Colors */
        --cd-primary: #1d4ed8;
        --cd-primary-hover: #1e40af;
        --cd-primary-soft: #eff6ff;
        --cd-secondary: #0f766e;
        --cd-secondary-soft: #ecfeff;
        --cd-neutral-900: #0f172a;
        --cd-neutral-700: #334155;
        --cd-neutral-500: #64748b;
        --cd-neutral-300: #cbd5e1;
        --cd-neutral-200: #e2e8f0;
        --cd-neutral-100: #f1f5f9;
        --cd-surface: #ffffff;
        --cd-surface-muted: #f8fafc;
        --cd-success: #15803d;
        --cd-success-soft: #f0fdf4;
        --cd-warning: #a16207;
        --cd-warning-soft: #fffbeb;
        --cd-danger: #b91c1c;
        --cd-danger-soft: #fef2f2;

        /* Radius, spacing, shadows */
        --cd-radius-sm: 8px;
        --cd-radius-md: 12px;
        --cd-radius-lg: 16px;
        --cd-space-1: 0.25rem;
        --cd-space-2: 0.5rem;
        --cd-space-3: 0.75rem;
        --cd-space-4: 1rem;
        --cd-space-5: 1.25rem;
        --cd-space-6: 1.5rem;
        --cd-shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.06);
        --cd-shadow-md: 0 8px 24px rgba(15, 23, 42, 0.08);
        --cd-transition: 160ms ease;
        --cd-focus: 0 0 0 3px rgba(29, 78, 216, 0.22);
    }

    .main-content.app-content,
    .main-content.app-content * {
        font-family: var(--cd-font);
    }

    .main-content.app-content i[class^="ri-"],
    .main-content.app-content i[class*=" ri-"] {
        font-family: remixicon !important;
    }

    .main-content.app-content .fa,
    .main-content.app-content .fas,
    .main-content.app-content .far,
    .main-content.app-content .fab {
        font-family: 'Font Awesome 6 Free', 'Font Awesome 6 Brands' !important;
    }

    .cd-section {
        background: var(--cd-surface);
        border: 1px solid var(--cd-neutral-200);
        border-radius: var(--cd-radius-md);
        padding: var(--cd-space-5);
        box-shadow: var(--cd-shadow-sm);
        transition: box-shadow var(--cd-transition), border-color var(--cd-transition);
    }

    .cd-section:hover {
        box-shadow: var(--cd-shadow-md);
        border-color: var(--cd-neutral-300);
    }

    .cd-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--cd-space-3);
        margin-bottom: var(--cd-space-4);
        padding-bottom: var(--cd-space-3);
        border-bottom: 1px solid var(--cd-neutral-100);
    }

    .cd-section-label {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: var(--cd-text-md);
        font-weight: 700;
        color: var(--cd-neutral-900);
    }

    .cd-section-label i {
        font-size: 1.1rem;
        color: var(--cd-primary);
    }

    .cd-section-link {
        display: inline-flex;
        align-items: center;
        gap: 2px;
        font-size: var(--cd-text-xs);
        font-weight: 600;
        color: var(--cd-primary);
        text-decoration: none;
    }

    .cd-section-link:hover {
        color: var(--cd-primary-hover);
        text-decoration: underline;
    }

    .cd-page-hero {
        background: linear-gradient(125deg, #1e3a8a 0%, #1d4ed8 50%, #0ea5e9 100%);
        border-radius: var(--cd-radius-md);
        padding: var(--cd-space-5) var(--cd-space-6);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: var(--cd-space-3);
        margin-bottom: var(--cd-space-2);
        box-shadow: var(--cd-shadow-sm);
    }

    .cd-page-hero-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .cd-page-hero-title {
        margin: 0;
        font-size: var(--cd-text-xl);
        font-weight: 700;
        color: #fff;
        line-height: 1.3;
    }

    .cd-page-hero-sub {
        margin: 0.2rem 0 0;
        font-size: var(--cd-text-sm);
        color: rgba(255, 255, 255, 0.86);
    }

    .cd-hero-btn,
    .cd-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        border: 1px solid transparent;
        border-radius: var(--cd-radius-sm);
        padding: 0.48rem 0.95rem;
        font-size: var(--cd-text-sm);
        font-weight: 600;
        line-height: 1.25;
        text-decoration: none;
        cursor: pointer;
        transition: background-color var(--cd-transition), color var(--cd-transition), box-shadow var(--cd-transition), border-color var(--cd-transition);
    }

    .cd-btn-sm {
        font-size: var(--cd-text-xs);
        padding: 0.34rem 0.7rem;
    }

    .cd-hero-btn-primary,
    .cd-btn-primary {
        background: var(--cd-primary);
        border-color: var(--cd-primary);
        color: #fff;
    }

    .cd-hero-btn-primary:hover,
    .cd-btn-primary:hover {
        background: var(--cd-primary-hover);
        border-color: var(--cd-primary-hover);
        color: #fff;
    }

    .cd-hero-btn-ghost,
    .cd-btn-outline {
        background: #fff;
        border-color: var(--cd-neutral-200);
        color: var(--cd-primary);
    }

    .cd-hero-btn-ghost:hover,
    .cd-btn-outline:hover {
        background: var(--cd-primary-soft);
        border-color: #bfdbfe;
        color: var(--cd-primary-hover);
    }

    .cd-btn-success {
        background: var(--cd-success-soft);
        border-color: #bbf7d0;
        color: var(--cd-success);
    }

    .cd-btn-success:hover {
        background: #dcfce7;
    }

    .cd-btn-warning {
        background: var(--cd-warning-soft);
        border-color: #fde68a;
        color: var(--cd-warning);
    }

    .cd-btn-warning:hover {
        background: #fef3c7;
    }

    .cd-btn-danger {
        background: var(--cd-danger-soft);
        border-color: #fecaca;
        color: var(--cd-danger);
    }

    .cd-btn-danger:hover {
        background: #fee2e2;
    }

    .cd-btn:focus-visible,
    .cd-hero-btn:focus-visible,
    .cd-toolbar-select:focus-visible,
    .cd-search:focus-visible,
    .cd-form-input:focus-visible,
    .cd-form-textarea:focus-visible,
    .cd-form-select:focus-visible,
    .cd-table input[type='checkbox']:focus-visible,
    .cd-link-focus:focus-visible {
        outline: 2px solid transparent;
        box-shadow: var(--cd-focus);
    }

    .cd-toolbar {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
        padding: 1.25rem;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(10px);
        border: 1px solid var(--cd-neutral-200);
        border-radius: var(--cd-radius-lg);
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-toolbar {
        background: rgba(255, 255, 255, 0.02);
        border-color: rgba(255, 255, 255, 0.05);
    }

    .cd-toolbar-tier {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .cd-toolbar-main,
    .cd-toolbar-actions {
        display: flex;
        align-items: center;
        gap: var(--cd-space-2);
        flex-wrap: wrap;
    }

    .cd-toolbar-main {
        flex: 1 1 0;
        min-width: 0;
    }

    .cd-toolbar-actions {
        margin-left: auto;
        justify-content: flex-end;
    }

    /* ── Pill Tabs System ── */
    .cd-tabs-pilled {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: var(--cd-neutral-100);
        padding: 0.35rem;
        border-radius: 12px;
        border: 1px solid var(--cd-neutral-200);
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-tabs-pilled {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.05);
    }

    .cd-tab-pill {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--cd-neutral-600);
        text-decoration: none !important;
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .cd-tab-pill:hover {
        color: var(--cd-primary);
        background: rgba(59, 130, 246, 0.05);
    }

    .cd-tab-pill.active {
        background: #fff;
        color: var(--cd-primary);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-tab-pill.active {
        background: rgba(59, 130, 246, 0.15);
        color: #60a5fa;
        box-shadow: none;
    }

    .cd-tab-pill.active.success { color: #10b981; }
    .cd-tab-pill.active.warning { color: #f59e0b; }
    .cd-tab-pill.active.danger { color: #ef4444; }

    .cd-search-wrap {
        position: relative;
        width: 100%;
        max-width: 100%;
    }

    .cd-search-wrap .cd-search {
        padding-left: 2.75rem;
        height: 48px;
        font-size: 0.95rem;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-search-wrap .cd-search {
        background: rgba(255, 255, 255, 0.03);
    }

    .cd-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1.1rem;
        pointer-events: none;
        z-index: 10;
    }

    .cd-search,
    .cd-toolbar-select,
    .cd-form-input,
    .cd-form-textarea,
    .cd-form-select {
        width: 100%;
        border: 1px solid var(--cd-neutral-200);
        border-radius: var(--cd-radius-sm);
        background: #fff;
        color: var(--cd-neutral-900);
        font-size: var(--cd-text-sm);
        transition: border-color var(--cd-transition), box-shadow var(--cd-transition), background-color var(--cd-transition);
    }

    .cd-search {
        padding: 0.52rem 0.8rem 0.52rem 2rem;
        background: var(--cd-surface-muted);
    }

    .cd-toolbar-select {
        width: auto;
        min-width: 140px;
        padding: 0.48rem 2.2rem 0.48rem 0.72rem;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2364748b'%3E%3Cpath d='M12 16L6 10H18L12 16Z'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.7rem center;
        background-size: 1.2rem;
    }

    .cd-form-input,
    .cd-form-textarea,
    .cd-form-select {
        width: 100%;
        border: 1px solid var(--cd-neutral-200);
        border-radius: var(--cd-radius-sm);
        background: #fff;
        color: var(--cd-neutral-900);
        font-size: var(--cd-text-sm);
        padding: 0.62rem 0.85rem;
        transition: all var(--cd-transition);
        line-height: 1.5;
    }

    .cd-form-select {
        padding-right: 2.5rem;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2364748b'%3E%3Cpath d='M12 16L6 10H18L12 16Z'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.85rem center;
        background-size: 1.2rem;
    }

    .cd-form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .cd-search:focus,
    .cd-toolbar-select:focus,
    .cd-form-input:focus,
    .cd-form-textarea:focus,
    .cd-form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        outline: none;
        background-color: #fff;
    }

    .cd-form-checkbox {
        width: 1.1rem;
        height: 1.1rem;
        border-radius: 4px;
        border: 1px solid var(--cd-neutral-300);
        cursor: pointer;
        accent-color: var(--cd-primary);
    }

    .cd-form-label {
        display: block;
        margin-bottom: 0.35rem;
        font-size: var(--cd-text-sm);
        font-weight: 600;
        color: var(--cd-neutral-900);
    }

    .cd-form-error {
        margin-top: 0.2rem;
        font-size: var(--cd-text-xs);
        color: var(--cd-danger);
    }

    textarea.cd-form-input,
    textarea.cd-form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .cd-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .cd-table thead th {
        background: var(--cd-surface-muted);
        color: var(--cd-neutral-500);
        border-bottom: 1px solid var(--cd-neutral-200);
        font-size: var(--cd-text-xs);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        text-align: left;
        padding: 0.7rem 0.8rem;
    }

    .cd-table thead th:first-child {
        border-top-left-radius: var(--cd-radius-sm);
        text-align: center;
        width: 40px;
    }

    .cd-table thead th:last-child {
        border-top-right-radius: var(--cd-radius-sm);
        text-align: right;
    }

    .cd-table tbody td {
        border-bottom: 1px solid var(--cd-neutral-100);
        color: var(--cd-neutral-700);
        font-size: var(--cd-text-sm);
        padding: 0.7rem 0.8rem;
        vertical-align: middle;
    }

    .cd-table tbody tr {
        transition: background-color var(--cd-transition);
    }

    .cd-table tbody tr:hover {
        background: var(--cd-surface-muted);
    }

    .cd-status-pill {
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        border-radius: 9999px;
        font-size: var(--cd-text-xs);
        font-weight: 600;
        line-height: 1;
        padding: 0.32rem 0.62rem;
    }

    .cd-company-card {
        border: 1px solid var(--cd-neutral-200);
        border-radius: var(--cd-radius-md);
        padding: var(--cd-space-5);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: box-shadow var(--cd-transition), border-color var(--cd-transition), transform var(--cd-transition);
        overflow: hidden;
    }

    .cd-company-card:hover {
        border-color: var(--cd-neutral-300);
        box-shadow: var(--cd-shadow-md);
        transform: translateY(-1px);
    }

    .cd-company-card-head {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: var(--cd-space-3);
        min-width: 0;
    }

    .cd-company-logo {
        width: 44px !important;
        height: 44px !important;
        min-width: 44px !important;
        min-height: 44px !important;
        max-width: 44px !important;
        max-height: 44px !important;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
        border: 1px solid var(--cd-neutral-200);
        display: block;
    }

    .cd-company-logo-fallback {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #4f46e5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 0.875rem;
        flex-shrink: 0;
        text-transform: uppercase;
    }

    .cd-company-name-wrap {
        min-width: 0;
    }

    .cd-company-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: var(--cd-neutral-900);
        font-size: var(--cd-text-sm);
        font-weight: 700;
        line-height: 1.3;
    }

    .cd-company-meta {
        margin-top: 2px;
        color: var(--cd-neutral-500);
        font-size: var(--cd-text-xs);
        overflow-wrap: anywhere;
    }

    .cd-company-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: var(--cd-space-3);
        color: var(--cd-neutral-500);
        font-size: var(--cd-text-sm);
        overflow-wrap: anywhere;
    }

    .cd-company-link {
        color: #4f46e5;
        text-decoration: none;
    }

    .cd-company-link:hover {
        text-decoration: underline;
    }

    .cd-company-status {
        margin-bottom: var(--cd-space-3);
    }

    .cd-company-actions {
        display: flex;
        gap: 6px;
        margin-top: auto;
        padding-top: var(--cd-space-3);
        border-top: 1px solid var(--cd-neutral-100);
    }

    .cd-status-info {
        background: rgba(96, 165, 250, 0.18);
        color: #93c5fd;
    }

    .cd-status-success {
        background: rgba(74, 222, 128, 0.18);
        color: #86efac;
    }

    .cd-status-warning {
        background: rgba(245, 158, 11, 0.20);
        color: #fcd34d;
    }

    .cd-status-danger {
        background: rgba(251, 113, 133, 0.18);
        color: #fda4af;
    }

    .cd-status-gray {
        background: rgba(148, 163, 184, 0.20);
        color: #cbd5e1;
    }

    .cd-action-dropdown {
        position: relative;
        display: inline-flex;
        justify-content: flex-end;
    }

    .cd-action-dropdown>summary {
        list-style: none;
    }

    .cd-action-dropdown>summary::-webkit-details-marker {
        display: none;
    }

    .cd-action-toggle {
        width: 2rem;
        height: 2rem;
        border-radius: var(--cd-radius-sm);
        border: 1px solid var(--cd-neutral-200);
        background: #fff;
        color: var(--cd-neutral-500);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: var(--cd-shadow-sm);
    }

    .cd-action-toggle:hover {
        border-color: #bfdbfe;
        color: var(--cd-primary);
        background: var(--cd-primary-soft);
    }

    .cd-action-menu {
        position: absolute;
        top: calc(100% + 0.35rem);
        right: 0;
        min-width: 11rem;
        background: #fff;
        border: 1px solid var(--cd-neutral-200);
        border-radius: var(--cd-radius-md);
        box-shadow: var(--cd-shadow-md);
        padding: 0.35rem;
        z-index: 20;
        display: none;
    }

    .cd-action-dropdown[open] .cd-action-menu {
        display: block;
    }

    .cd-action-item {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.45rem;
        border: 0;
        background: transparent;
        border-radius: 10px;
        padding: 0.55rem 0.7rem;
        color: var(--cd-neutral-700);
        font-size: var(--cd-text-sm);
        font-weight: 600;
        text-align: left;
        cursor: pointer;
        text-decoration: none;
    }

    .cd-action-item:hover,
    .cd-action-item:focus-visible {
        background: var(--cd-surface-muted);
        color: var(--cd-neutral-900);
        outline: none;
    }

    .cd-action-item-danger {
        color: var(--cd-danger);
    }

    .cd-action-item-danger:hover,
    .cd-action-item-danger:focus-visible {
        background: var(--cd-danger-soft);
        color: var(--cd-danger);
    }

    .cd-empty {
        text-align: center;
        padding: 2.2rem 1rem;
    }

    .cd-empty i {
        display: block;
        margin-bottom: 0.7rem;
        color: #cbd5e1;
        font-size: 2.4rem;
    }

    .cd-empty p {
        margin-bottom: 0.9rem;
        color: var(--cd-neutral-500);
        font-size: var(--cd-text-base);
    }

    .cd-pagination {
        display: flex;
        justify-content: center;
        gap: 0.25rem;
        margin-top: var(--cd-space-4);
    }

    .cd-pagination .page-link {
        border-radius: var(--cd-radius-sm) !important;
        font-size: var(--cd-text-sm);
    }

    .cd-pipe {
        text-align: center;
        border: 1px solid var(--cd-neutral-100);
        border-radius: var(--cd-radius-sm);
        padding: 0.9rem 0.6rem;
        transition: transform var(--cd-transition), box-shadow var(--cd-transition);
    }

    .cd-pipe:hover {
        transform: translateY(-2px);
        box-shadow: var(--cd-shadow-md);
    }

    .cd-pipe-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .cd-pipe-num {
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1;
    }

    .cd-pipe-lbl {
        margin-top: 0.15rem;
        color: var(--cd-neutral-700);
        font-size: var(--cd-text-xs);
        font-weight: 600;
    }

    .cd-stat {
        text-align: center;
        padding: 0.65rem;
        border-radius: var(--cd-radius-sm);
    }

    .cd-stat-num {
        font-size: 1.1rem;
        font-weight: 800;
        line-height: 1;
    }

    .cd-stat-lbl {
        color: var(--cd-neutral-500);
        font-size: var(--cd-text-xs);
        font-weight: 500;
        margin-top: 0.2rem;
    }

    .em-pipeline {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: var(--cd-space-4);
        align-items: start;
    }

    .em-col {
        background: var(--cd-surface-muted);
        border: 1px solid var(--cd-neutral-200);
        border-radius: var(--cd-radius-md);
        padding: var(--cd-space-3);
    }

    .em-col-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: var(--cd-space-2);
    }

    .em-col-title {
        font-size: var(--cd-text-sm);
        font-weight: 700;
        color: var(--cd-neutral-900);
    }

    .em-col-count {
        border-radius: 9999px;
        padding: 0.14rem 0.48rem;
        font-size: var(--cd-text-xs);
        font-weight: 600;
    }

    .em-card {
        background: #fff;
        border: 1px solid var(--cd-neutral-200);
        border-radius: var(--cd-radius-sm);
        padding: var(--cd-space-3);
        margin-bottom: var(--cd-space-2);
        transition: transform var(--cd-transition), box-shadow var(--cd-transition);
    }

    .em-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--cd-shadow-md);
    }

    .em-card:last-child {
        margin-bottom: 0;
    }

    .swal2-popup {
        border-radius: var(--cd-radius-md) !important;
        font-family: var(--cd-font) !important;
    }

    .swal2-title {
        color: var(--cd-neutral-900) !important;
        font-size: 1.1rem !important;
    }

    .swal2-html-container {
        color: var(--cd-neutral-700) !important;
        font-size: var(--cd-text-sm) !important;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    [data-theme-mode="dark"] .cd-section,
    .dark .cd-section {
        background: var(--bodybg, #1a1c2e);
        border-color: rgba(255, 255, 255, 0.08);
    }

    [data-theme-mode="dark"] .cd-section-label,
    .dark .cd-section-label,
    [data-theme-mode="dark"] .em-col-title,
    .dark .em-col-title,
    [data-theme-mode="dark"] .cd-form-label,
    .dark .cd-form-label {
        color: #e2e8f0;
    }

    [data-theme-mode="dark"] .cd-section-head,
    .dark .cd-section-head,
    [data-theme-mode="dark"] .cd-table thead th,
    .dark .cd-table thead th,
    [data-theme-mode="dark"] .cd-table tbody td,
    .dark .cd-table tbody td {
        border-color: rgba(255, 255, 255, 0.08);
    }

    [data-theme-mode="dark"] .cd-table thead th,
    .dark .cd-table thead th,
    [data-theme-mode="dark"] .em-col,
    .dark .em-col,
    [data-theme-mode="dark"] .cd-search,
    .dark .cd-search {
        background: rgba(255, 255, 255, 0.04);
        color: #cbd5e1;
    }

    [data-theme-mode="dark"] .cd-table tbody tr:hover,
    .dark .cd-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    [data-theme-mode="dark"] .cd-toolbar-select,
    .dark .cd-toolbar-select,
    [data-theme-mode="dark"] .cd-form-input,
    .dark .cd-form-input,
    [data-theme-mode="dark"] .cd-form-textarea,
    .dark .cd-form-textarea,
    [data-theme-mode="dark"] .cd-form-select,
    .dark .cd-form-select,
    [data-theme-mode="dark"] .em-card,
    .dark .em-card,
    [data-theme-mode="dark"] .cd-btn-outline,
    .dark .cd-btn-outline,
    [data-theme-mode="dark"] .cd-hero-btn-ghost,
    .dark .cd-hero-btn-ghost {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
        color: #e2e8f0;
    }

    /* Dashboard cards often use inline light backgrounds; force dark surfaces for consistency */
    [data-theme-mode="dark"] .cd-pipe,
    .dark .cd-pipe,
    [data-theme-mode="dark"] .cd-stat,
    .dark .cd-stat {
        background: rgba(255, 255, 255, 0.06) !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
    }

    [data-theme-mode="dark"] .cd-pipe-icon,
    .dark .cd-pipe-icon {
        background: rgba(255, 255, 255, 0.12) !important;
        color: #93c5fd !important;
    }

    [data-theme-mode="dark"] .cd-pipe-num,
    .dark .cd-pipe-num,
    [data-theme-mode="dark"] .cd-stat-num,
    .dark .cd-stat-num {
        color: #e2e8f0 !important;
    }

    [data-theme-mode="dark"] .cd-pipe-lbl,
    .dark .cd-pipe-lbl,
    [data-theme-mode="dark"] .cd-stat-lbl,
    .dark .cd-stat-lbl {
        color: #cbd5e1 !important;
    }

    /* Inline status-pill colors are light-only; normalize them for dark mode */
    [data-theme-mode="dark"] .cd-status-pill,
    .dark .cd-status-pill {
        background: rgba(255, 255, 255, 0.10) !important;
        color: #e2e8f0 !important;
        border: 1px solid rgba(255, 255, 255, 0.16);
    }

    [data-theme-mode="dark"] .cd-company-card,
    .dark .cd-company-card {
        border-color: rgba(255, 255, 255, 0.1);
    }

    [data-theme-mode="dark"] .cd-company-logo,
    .dark .cd-company-logo,
    [data-theme-mode="dark"] .cd-company-actions,
    .dark .cd-company-actions {
        border-color: rgba(255, 255, 255, 0.12);
    }

    [data-theme-mode="dark"] .cd-company-name,
    .dark .cd-company-name {
        color: #e2e8f0;
    }

    [data-theme-mode="dark"] .cd-company-meta,
    .dark .cd-company-meta,
    [data-theme-mode="dark"] .cd-company-details,
    .dark .cd-company-details,
    [data-theme-mode="dark"] .cd-company-link,
    .dark .cd-company-link {
        color: #cbd5e1;
    }

    [data-theme-mode="dark"] #wt-overview,
    .dark #wt-overview {
        background: transparent !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        box-shadow: none !important;
    }

    [data-theme-mode="dark"] #wt-recent-jobs [style*="border-bottom"],
    .dark #wt-recent-jobs [style*="border-bottom"] {
        border-bottom-color: rgba(255, 255, 255, 0.10) !important;
    }

    @media (max-width: 1024px) {
        .cd-section {
            padding: var(--cd-space-4);
        }

        .cd-page-hero {
            padding: var(--cd-space-4);
        }

        .cd-page-hero-title {
            font-size: 1.2rem;
        }

        .cd-toolbar {
            gap: 0.4rem;
        }
    }

    @media (max-width: 768px) {
        .cd-page-hero {
            flex-direction: column;
            align-items: flex-start;
        }

        .cd-toolbar {
            flex-direction: column;
            align-items: stretch;
        }

        .cd-toolbar .cd-search-wrap,
        .cd-toolbar .cd-toolbar-select,
        .cd-toolbar .cd-btn {
            max-width: 100%;
            width: 100%;
        }

        .cd-table thead th,
        .cd-table tbody td {
            padding: 0.62rem;
        }
    }

    /* ── Badge Styles ── */
    .cd-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.7rem;
        border-radius: 6px;
        font-size: var(--cd-font-xs);
        font-weight: 600;
        white-space: nowrap;
    }

    .cd-badge-success {
        background: var(--cd-success-light);
        color: var(--cd-success);
    }

    .cd-badge-danger {
        background: var(--cd-danger-light);
        color: var(--cd-danger);
    }

    .cd-badge-warning {
        background: var(--cd-warning-light);
        color: var(--cd-warning);
    }

    .cd-badge-info {
        background: var(--cd-info-light);
        color: var(--cd-info);
    }

    .cd-badge-gray {
        background: var(--cd-bg-stripe);
        color: var(--cd-text-muted);
    }

    /* ── Alert Styles ── */
    .cd-alert {
        padding: 1rem var(--cd-p-md);
        border-radius: var(--cd-r-md);
        border: 1px solid;
        display: flex;
        gap: var(--cd-p-sm);
        align-items: flex-start;
    }

    .cd-alert i {
        font-size: 1.1rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .cd-alert-success {
        background: var(--cd-success-light);
        border-color: var(--cd-success-border);
        color: var(--cd-success);
    }

    .cd-alert-danger {
        background: var(--cd-danger-light);
        border-color: var(--cd-danger-border);
        color: var(--cd-danger);
    }

    .cd-alert-warning {
        background: var(--cd-warning-light);
        border-color: var(--cd-warning-border);
        color: var(--cd-warning);
    }

    .cd-alert-info {
        background: var(--cd-info-light);
        border-color: var(--cd-info-border);
        color: var(--cd-info);
    }

    .cd-alert-close {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 1.25rem;
        color: inherit;
        cursor: pointer;
        padding: 0;
        flex-shrink: 0;
    }

    /* ── Link Styles ── */
    .cd-link {
        color: var(--cd-accent);
        text-decoration: none;
        transition: all var(--cd-t-fast);
        font-weight: 500;
    }

    .cd-link:hover {
        color: var(--cd-accent-hover);
        text-decoration: underline;
    }

    /* ── Breadcrumb Styles ── */
    .cd-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: var(--cd-font-sm);
        margin-bottom: var(--cd-p-md);
    }

    .cd-breadcrumb-item {
        color: var(--cd-text-muted);
    }

    .cd-breadcrumb-item a {
        color: var(--cd-accent);
        text-decoration: none;
    }

    .cd-breadcrumb-item a:hover {
        color: var(--cd-accent-hover);
        text-decoration: underline;
    }

    .cd-breadcrumb-item.active {
        color: var(--cd-text);
        font-weight: 600;
    }

    .cd-breadcrumb-separator {
        color: var(--cd-border);
    }

    /* ── Tab Styles ── */
    .cd-tabs {
        display: flex;
        gap: 0;
        border-bottom: 1.5px solid var(--cd-border);
        margin-bottom: var(--cd-p-md);
    }

    .cd-tab {
        padding: 1rem var(--cd-p-md);
        background: none;
        border: none;
        color: var(--cd-text-muted);
        font-weight: 600;
        cursor: pointer;
        transition: all var(--cd-t-fast);
        position: relative;
        font-size: var(--cd-font-base);
    }

    .cd-tab:hover {
        color: var(--cd-text);
    }

    .cd-tab.active {
        color: var(--cd-accent);
    }

    .cd-tab.active::after {
        content: '';
        position: absolute;
        bottom: -1.5px;
        left: 0;
        right: 0;
        height: 1.5px;
        background: var(--cd-accent);
    }

    /* ── Loading skeleton ── */
    @keyframes cd-skeleton-loading {
        0% {
            background-position: -1000px 0;
        }

        100% {
            background-position: 1000px 0;
        }
    }

    .cd-skeleton {
        background: linear-gradient(90deg, var(--cd-bg-alt) 25%, var(--cd-bg-stripe) 50%, var(--cd-bg-alt) 75%);
        background-size: 1000px 100%;
        animation: cd-skeleton-loading 2s infinite;
        border-radius: var(--cd-r-md);
    }

    .cd-skeleton-text {
        height: 1rem;
        margin-bottom: 0.5rem;
    }

    .cd-skeleton-line {
        height: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .cd-skeleton-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
    }

    @media (max-width: 1024px) {
        .cd-btn-sm {
            padding: 0.3rem 0.55rem;
        }

        .cd-btn-icon {
            width: 32px;
            height: 32px;
            padding: 0.25rem;
        }

        .cd-toolbar-select,
        .cd-search {
            font-size: var(--cd-font-xs);
        }
    }

    @media (max-width: 768px) {
        .cd-toolbar {
            gap: 0.5rem;
            padding: 0.6rem 0.65rem;
        }

        .cd-search-wrap {
            order: 3;
            max-width: 100%;
            width: 100%;
        }

        .cd-table thead th,
        .cd-table tbody td {
            padding: 0.65rem 0.55rem;
        }

        .cd-action-cluster {
            gap: 0.2rem;
        }

        .cd-compact-select {
            min-width: 6.25rem;
        }
    }

    /* ═══════════════════════════════════════════════════════════════════════════════ */
    /* ── DASHBOARD STYLES (edb-*) — Phase 3 Consolidation ──                        */
    /* These styles power the employer dashboard and should migrate to cd- naming     */
    /* in future refactoring. Currently kept separate for dashboard-specific layouts. */
    /* ═════════════════════════════════════════════════════════════════════════════  */

    .edb-shell {
        --edb-bg: #f4f7ff;
        --edb-panel: #ffffff;
        --edb-panel-soft: #f8faff;
        --edb-border: #dbe4f2;
        --edb-text: #0f172a;
        --edb-subtext: #334155;
        --edb-muted: #64748b;
        --edb-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
        --edb-table-head: #eef3fb;
        padding: 1.25rem;
        border-radius: 1rem;
        background: var(--edb-bg);
        color: var(--edb-text);
        border: 1px solid var(--edb-border);
        box-shadow: var(--edb-shadow);
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-shell {
        --edb-bg: #0f172a;
        --edb-panel: #16223a;
        --edb-panel-soft: #101b30;
        --edb-border: rgba(148, 163, 184, 0.24);
        --edb-text: #f8fafc;
        --edb-subtext: #c7d2fe;
        --edb-muted: #9fb0cc;
        --edb-shadow: 0 14px 30px rgba(2, 6, 23, 0.35);
        --edb-table-head: #24324e;
    }

    .edb-hero {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 1rem;
        margin-bottom: 1.1rem;
    }

    .edb-title {
        font-size: clamp(1.55rem, 2.8vw, 2.2rem);
        line-height: 1.1;
        font-weight: 800;
        letter-spacing: 0.2px;
        margin: 0;
    }

    .edb-subtitle {
        margin: 0.4rem 0 0;
        color: var(--edb-subtext);
        font-size: 1.06rem;
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

    .edb-hero-actions {
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }

    .edb-post-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.72rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.96rem;
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

    .edb-stat-card {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 1rem;
        border-radius: 0.95rem;
        background: var(--edb-panel);
        border: 1px solid var(--edb-border);
        box-shadow: var(--edb-shadow);
    }

    .edb-stat-icon {
        width: 3.2rem;
        height: 3.2rem;
        border-radius: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        border: 1px solid rgba(148, 163, 184, 0.2);
    }

    .edb-chip-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .edb-chip-purple {
        background: #ede9fe;
        color: #6d28d9;
    }

    .edb-chip-green {
        background: #dcfce7;
        color: #15803d;
    }

    .edb-chip-amber {
        background: #ffedd5;
        color: #c2410c;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-chip-blue {
        background: rgba(59, 130, 246, 0.22);
        color: #93c5fd;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-chip-purple {
        background: rgba(124, 58, 237, 0.22);
        color: #c4b5fd;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-chip-green {
        background: rgba(34, 197, 94, 0.22);
        color: #86efac;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .edb-chip-amber {
        background: rgba(249, 115, 22, 0.22);
        color: #fdba74;
    }

    .edb-stat-label {
        margin: 0;
        color: var(--edb-subtext);
        font-size: 1rem;
    }

    .edb-stat-value {
        margin: 0.25rem 0 0;
        font-size: 2rem;
        line-height: 1;
        font-weight: 800;
        color: var(--edb-text);
    }

    .edb-main-grid {
        display: grid;
        grid-template-columns: minmax(0, 2.35fr) minmax(0, 1fr);
        gap: 1rem;
    }

    .edb-panel {
        background: var(--edb-panel);
        border: 1px solid var(--edb-border);
        border-radius: 1rem;
        box-shadow: var(--edb-shadow);
        padding: 1rem 1rem 1.1rem;
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
    }

    #wt-recent-jobs {
        display: flex;
        flex-direction: column;
    }

    .edb-charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.8rem;
    }

    .edb-chart-box {
        border-radius: 0.85rem;
        padding: 0.7rem;
        border: 1px solid var(--edb-border);
        background: var(--edb-panel-soft);
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
        grid-template-columns: auto 1fr auto;
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
        .edb-shell {
            padding: 1rem;
            border-radius: 0.8rem;
        }

        .edb-hero {
            flex-direction: column;
            align-items: flex-start;
        }

        .edb-post-panel {
            flex-direction: column;
            align-items: flex-start;
        }

        .edb-hero-actions {
            width: 100%;
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
            min-width: 680px;
        }
    }

    /* ═══════════════════════════════════════════════════════════════════════════════ */
    /* ── MODERN JOB DASHBOARD (cd-job-*) ──                                         */
    /* ═════════════════════════════════════════════════════════════════════════════  */

    .cd-stat-ribbon {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .cd-stat-pill {
        background: var(--cd-surface);
        border: 1px solid var(--cd-neutral-200);
        border-radius: var(--cd-radius-md);
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all var(--cd-transition);
        box-shadow: var(--cd-shadow-sm);
        text-decoration: none !important;
    }

    .cd-stat-pill:hover {
        border-color: var(--cd-primary);
        transform: translateY(-2px);
        box-shadow: var(--cd-shadow-md);
    }

    .cd-stat-icon-box {
        width: 3rem;
        height: 3rem;
        border-radius: var(--cd-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: var(--cd-neutral-100);
        color: var(--cd-neutral-500);
    }

    .cd-stat-pill.active .cd-stat-icon-box {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .cd-stat-pill.open .cd-stat-icon-box {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    /* ── Content Transitions ── */
    @keyframes cdFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes cdFadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-8px); }
    }

    .cd-animate-fade-in {
        animation: cdFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .cd-animate-fade-out {
        animation: cdFadeOut 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .cd-loading-overlay {
        position: relative;
        pointer-events: none;
    }

    .cd-loading-overlay::after {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(2px);
        z-index: 20;
        border-radius: inherit;
        animation: cdPulse 1.5s ease-in-out infinite;
    }

    @keyframes cdPulse {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.6; }
    }

    .cd-stat-pill.closed .cd-stat-icon-box {
        background: rgba(244, 63, 94, 0.1);
        color: #f43f5e;
    }

    .cd-stat-info .label {
        font-size: 0.75rem;
        color: var(--cd-neutral-500);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .cd-stat-info .value {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--cd-neutral-900);
        line-height: 1;
        margin-top: 0.25rem;
    }

    .cd-job-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
    }

    /* ── High-Engagement Card Enhancements ── */
    @keyframes cd-slide-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .cd-job-card {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 20px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        animation: cd-slide-up 0.5s ease-out backwards;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
    }

    .cd-job-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: rgba(59, 130, 246, 0.3);
        background: rgba(255, 255, 255, 0.8);
    }

    .cd-job-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .cd-job-avatar {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        position: relative;
        overflow: hidden;
        background: var(--cd-primary);
    }

    .cd-job-card:hover .cd-job-avatar {
        transform: scale(1.05) rotate(-3deg);
    }

    .cd-job-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--cd-neutral-900);
        margin-bottom: 0.15rem;
        display: block;
        text-decoration: none !important;
        line-height: 1.4;
        transition: color 0.2s ease;
    }

    .cd-job-title:hover {
        color: var(--cd-primary);
    }

    .cd-company-name-mini {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--cd-primary);
        display: flex;
        align-items: center;
        gap: 0.35rem;
        margin-bottom: 0.25rem;
    }

    .cd-job-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .cd-job-meta span {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.72rem;
        color: var(--cd-neutral-500);
        font-weight: 500;
    }

    .cd-job-meta span i {
        font-size: 0.85rem;
        color: var(--cd-neutral-400);
    }

    .cd-info-chip-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    .cd-info-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.85rem;
        border-radius: 12px;
        background: rgba(241, 245, 249, 0.8);
        color: var(--cd-neutral-700);
        font-size: 0.75rem;
        font-weight: 700;
        transition: all 0.2s ease;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .cd-info-chip i {
        font-size: 0.9rem;
        color: var(--cd-neutral-500);
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-info-chip {
        background: rgba(255, 255, 255, 0.05);
        color: var(--cd-neutral-400);
        border-color: rgba(255, 255, 255, 0.1);
    }

    .cd-info-chip.success {
        background: rgba(20, 184, 166, 0.08);
        color: #0d9488;
        border-color: rgba(20, 184, 166, 0.15);
    }

    .cd-info-chip.success i { color: #0d9488; }

    .cd-job-card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .cd-job-stats-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding: 0.75rem;
        background: rgba(248, 250, 252, 0.6);
        border-radius: 12px;
        border: 1px solid rgba(226, 232, 240, 0.5);
    }

    .cd-job-apps-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--cd-primary);
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none !important;
        transition: transform 0.2s ease;
    }

    .cd-job-apps-link:hover {
        transform: translateX(3px);
    }

    .cd-status-pill-mini {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        letter-spacing: 0.02em;
    }

    .cd-status-pill-mini.open {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .cd-status-pill-mini.closed {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .cd-job-card-footer {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-top: auto;
    }

    .cd-view-role-btn {
        flex: 1;
        background: var(--cd-primary);
        color: white !important;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 0.7rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none !important;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .cd-view-role-btn:hover {
        background: var(--cd-primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
    }

    .cd-more-btn {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: white;
        border: 1px solid var(--cd-neutral-200);
        color: var(--cd-neutral-600);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .cd-more-btn:hover {
        background: var(--cd-neutral-50);
        border-color: var(--cd-neutral-300);
        color: var(--cd-neutral-900);
    }

    .cd-status-pulse-mini {
        width: 6.5px;
        height: 6.5px;
        border-radius: 50%;
        background: #10b981;
        display: inline-block;
        position: relative;
        vertical-align: middle;
        margin-left: 2px;
    }

    .cd-status-pulse-mini::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: inherit;
        animation: cd-pulse 2s infinite;
    }

    @keyframes cd-pulse {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(3.5); opacity: 0; }
    }

    /* Dark Mode Adjustments */
    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-job-card {
        background: rgba(30, 41, 59, 0.4);
        border-color: rgba(255, 255, 255, 0.05);
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-job-card:hover {
        background: rgba(30, 41, 59, 0.6);
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-job-title {
        color: #f8fafc;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-job-stats-row {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.05);
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-more-btn {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
        color: #94a3b8;
    }

    @media (max-width: 768px) {
        .cd-stat-ribbon {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
    }

    /* Modern Celestial Buttons */
    .cd-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 0.85rem;
        font-size: 0.94rem;
        font-weight: 600;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-decoration: none !important;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .cd-btn:hover {
        transform: translateY(-2px);
    }

    .cd-btn:active {
        transform: translateY(0);
    }

    .cd-btn-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white !important;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.25);
    }

    .cd-btn-primary:hover {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
    }

    .cd-btn-secondary {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-color: rgba(226, 232, 240, 0.8);
        color: #475569 !important;
    }

    :is(.dark, [data-theme-mode="dark"]) .cd-btn-secondary {
        background: rgba(30, 41, 59, 0.6);
        border-color: rgba(51, 65, 85, 0.8);
        color: #cbd5e1 !important;
    }

    .cd-btn-secondary:hover {
        background: #fff;
        border-color: #cbd5e1;
    }

    :is(.dark, [data-theme-mode="dark"]) .cd-btn-secondary:hover {
        background: rgba(51, 65, 85, 0.8);
        border-color: #475569;
    }

    .cd-btn i {
        font-size: 1.1rem;
    }
    /* ── Empty State ── */
    .cd-empty-state {
        padding: 5rem 2rem;
        text-align: center;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(8px);
        border-radius: 20px;
        border: 2px dashed var(--cd-neutral-200);
        margin: 2rem 0;
        transition: all 0.3s ease;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-empty-state {
        background: rgba(255, 255, 255, 0.02);
        border-color: rgba(255, 255, 255, 0.08);
    }

    .cd-empty-icon-container {
        width: 110px;
        height: 110px;
        background: var(--cd-primary-soft);
        color: var(--cd-primary);
        border-radius: 35% 65% 65% 35% / 35% 35% 65% 65%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 3rem;
        position: relative;
        animation: cdEmptyFloat 4s ease-in-out infinite;
    }

    @keyframes cdEmptyFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); border-radius: 35% 65% 65% 35% / 35% 35% 65% 65%; }
        50% { transform: translateY(-15px) rotate(5deg); border-radius: 50%; }
    }

    .cd-empty-icon-container::after {
        content: "";
        position: absolute;
        inset: -15px;
        border: 2px solid var(--cd-primary-soft);
        border-radius: 50%;
        opacity: 0.3;
        animation: cdEmptyPulse 2.5s ease-in-out infinite;
    }

    @keyframes cdEmptyPulse {
        0% { transform: scale(0.9); opacity: 0; }
        50% { opacity: 0.3; }
        100% { transform: scale(1.4); opacity: 0; }
    }

    .cd-empty-title {
        font-size: 1.5rem;
        font-weight: 850;
        color: var(--cd-neutral-900);
        margin-bottom: 0.75rem;
        letter-spacing: -0.025em;
    }

    .cd-empty-desc {
        font-size: 1rem;
        color: var(--cd-neutral-500);
        max-width: 440px;
        margin: 0 auto 0;
        line-height: 1.6;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .cd-empty-title {
        color: #fff;
    }
    /* ── Hiring Pipeline (Kanban) System ── */
    .cd-kanban-board {
        display: flex;
        gap: 1.5rem;
        overflow-x: auto;
        padding: 0.5rem 0.5rem 2rem;
        min-height: calc(100vh - 350px);
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    .cd-kanban-board::-webkit-scrollbar {
        height: 8px;
    }

    .cd-kanban-board::-webkit-scrollbar-track {
        background: transparent;
    }

    .cd-kanban-board::-webkit-scrollbar-thumb {
        background: var(--cd-neutral-200);
        border-radius: 10px;
    }

    :is([data-theme-mode="dark"], .dark) .cd-kanban-board::-webkit-scrollbar-thumb {
        background: var(--cd-neutral-700);
    }

    .cd-kanban-column {
        flex: 0 0 320px;
        background: rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 350px);
        transition: all 0.3s ease;
        box-shadow: var(--cd-shadow-sm);
    }

    :is([data-theme-mode="dark"], .dark) .cd-kanban-column {
        background: rgba(30, 41, 59, 0.4);
        border-color: rgba(255, 255, 255, 0.05);
    }

    .cd-kanban-header {
        padding: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .cd-kanban-title {
        font-size: 0.85rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--cd-neutral-700);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    :is([data-theme-mode="dark"], .dark) .cd-kanban-title {
        color: var(--cd-neutral-300);
    }

    .cd-kanban-count {
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-weight: 700;
        background: rgba(0, 0, 0, 0.05);
    }

    .cd-kanban-scroll-area {
        padding: 1rem;
        overflow-y: auto;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .cd-kanban-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem;
        border: 1px solid var(--cd-neutral-200);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: grab;
        position: relative;
        overflow: hidden;
    }

    :is([data-theme-mode="dark"], .dark) .cd-kanban-card {
        background: #1e293b;
        border-color: rgba(255, 255, 255, 0.08);
    }

    .cd-kanban-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: var(--cd-shadow-lg);
        border-color: var(--cd-primary-soft);
    }

    .cd-kanban-card-accent {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
    }

    .cd-kanban-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.75rem;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .cd-kanban-applicant-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--cd-neutral-900);
        margin-bottom: 0.15rem;
    }

    :is([data-theme-mode="dark"], .dark) .cd-kanban-applicant-name {
        color: #fff;
    }

    .cd-kanban-job-title {
        font-size: 0.75rem;
        color: var(--cd-neutral-500);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .cd-kanban-card-footer {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px dashed var(--cd-neutral-100);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    :is([data-theme-mode="dark"], .dark) .cd-kanban-card-footer {
        border-top-color: rgba(255, 255, 255, 0.05);
    }

    /* Column Themes */
    .cd-col-applied .cd-kanban-card-accent { background: #3b82f6; }
    .cd-col-under-review .cd-kanban-card-accent { background: #eab308; }
    .cd-col-viewed .cd-kanban-card-accent { background: #0ea5e9; }
    .cd-col-in_progress .cd-kanban-card-accent { background: #8b5cf6; }
    .cd-col-accepted .cd-kanban-card-accent { background: #10b981; }
    .cd-col-declined .cd-kanban-card-accent { background: #ef4444; }
    .cd-col-closed .cd-kanban-card-accent { background: #64748b; }
    /* ── Quick Interview Modal Polish ── */
    .cd-form-group label {
        letter-spacing: 0.05em;
        opacity: 0.8;
    }

    .cd-form-group input::placeholder {
        color: var(--cd-neutral-400);
        font-weight: 400;
    }

    /* Override SweetAlert2 for Celestial look */
    .swal2-popup.swal2-modal {
        border-radius: 24px !important;
        padding: 2rem !important;
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
        backdrop-filter: blur(20px) !important;
        background: rgba(255, 255, 255, 0.95) !important;
    }

    :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .swal2-popup.swal2-modal {
        background: rgba(30, 41, 59, 0.95) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
</style>

<script>
    document.addEventListener('click', function (event) {
        var clickedMenu = event.target.closest('.cd-action-dropdown');

        document.querySelectorAll('.cd-action-dropdown[open]').forEach(function (menu) {
            if (clickedMenu && menu === clickedMenu) return;
            menu.removeAttribute('open');
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;
        document.querySelectorAll('.cd-action-dropdown[open]').forEach(function (menu) {
            menu.removeAttribute('open');
        });
    });
</script>