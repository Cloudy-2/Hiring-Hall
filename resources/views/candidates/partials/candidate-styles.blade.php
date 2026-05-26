<style>
    /* ── Design Tokens ── */
    :root {
        --cd-r: 12px;
        --cd-s: 0 1px 3px rgba(0,0,0,0.05);
        --cd-sh: 0 6px 16px rgba(0,0,0,0.08);
        --cd-p: 20px;
        --cd-t: 0.2s ease;
        --cd-accent-rgb: var(--primary-rgb, 79, 70, 229);
        --cd-accent: rgb(var(--cd-accent-rgb));
        --cd-accent-hover: rgb(var(--cd-accent-rgb));
        --cd-accent-light: rgba(var(--cd-accent-rgb), 0.14);
        --cd-accent-border: rgba(var(--cd-accent-rgb), 0.34);
        --cd-text: #1f2937;
        --cd-text-secondary: #4b5563;
        --cd-text-muted: #6b7280;
        --cd-border: #e5e7eb;
        --cd-bg-alt: #f9fafb;
        --cd-bg-stripe: #f3f4f6;
        --cd-font-base: 0.875rem;      /* 14px — default body */
        --cd-font-sm: 0.8125rem;       /* 13px */
        --cd-font-xs: 0.75rem;         /* 12px — minimum */
        --cd-font-lg: 1rem;            /* 16px */
    }

    /* ── Section card ── */
    .cd-section {
        background: #fff;
        border: 1px solid var(--cd-border);
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
        border-bottom: 1px solid var(--cd-bg-stripe);
    }
    .cd-section-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: var(--cd-font-base);
        font-weight: 700;
        color: var(--cd-text);
    }
    .cd-section-label i {
        font-size: 1.1rem;
        color: var(--cd-accent);
    }
    .cd-section-link {
        font-size: var(--cd-font-xs);
        font-weight: 600;
        color: var(--cd-accent);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 2px;
    }
    .cd-section-link:hover { color: var(--cd-accent-hover); text-decoration: underline; }

    /* ── Stat pipe cards ── */
    .cd-pipe {
        text-align: center;
        padding: 1rem 0.5rem;
        border-radius: 10px;
        border: 1px solid var(--cd-bg-stripe);
        transition: transform var(--cd-t), box-shadow var(--cd-t);
    }
    .cd-pipe:hover { transform: translateY(-3px); box-shadow: var(--cd-sh); }
    .cd-pipe-icon { width: 40px; height: 40px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; margin-bottom: 0.5rem; }
    .cd-pipe-num { font-size: 1.75rem; font-weight: 800; line-height: 1; }
    .cd-pipe-lbl { font-size: var(--cd-font-xs); font-weight: 600; color: var(--cd-text-secondary); margin-top: 0.15rem; }
    .cd-pipe-micro { font-size: var(--cd-font-xs); color: var(--cd-text-muted); margin-top: 0.15rem; }

    /* ── Stat cells (small 2×2) ── */
    .cd-stat { text-align: center; padding: 0.65rem; border-radius: 10px; }
    .cd-stat-num { font-size: 1.15rem; font-weight: 800; line-height: 1; }
    .cd-stat-lbl { font-size: var(--cd-font-xs); font-weight: 500; color: var(--cd-text-muted); margin-top: 0.25rem; }

    /* ── Clean tables ── */
    .cd-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .cd-table thead th {
        font-size: var(--cd-font-xs);
        font-weight: 600;
        color: var(--cd-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.65rem 0.75rem;
        background: var(--cd-bg-alt);
        border-bottom: 1px solid var(--cd-border);
    }
    .cd-table thead th:first-child { border-radius: 8px 0 0 0; }
    .cd-table thead th:last-child { border-radius: 0 8px 0 0; }
    .cd-table tbody td {
        padding: 0.65rem 0.75rem;
        font-size: var(--cd-font-sm);
        color: #374151;
        border-bottom: 1px solid var(--cd-bg-stripe);
        vertical-align: middle;
    }
    .cd-table tbody tr { transition: background 0.15s; }
    .cd-table tbody tr:hover { background: var(--cd-bg-alt); }
    .cd-table tbody tr:last-child td { border-bottom: none; }

    /* ── Status pill ── */
    .cd-status-pill { font-size: var(--cd-font-xs); font-weight: 600; padding: 3px 10px; border-radius: 20px; white-space: nowrap; display: inline-block; }

    /* ── Page hero (compact) ── */
    .cd-page-hero {
        --cd-hero-rgb: var(--primary-rgb, 79, 70, 229);
        --cd-hero-bg-start: rgba(var(--cd-hero-rgb), 0.88);
        --cd-hero-bg-mid: rgba(var(--cd-hero-rgb), 0.78);
        --cd-hero-bg-end: rgba(var(--cd-hero-rgb), 0.68);
        background: linear-gradient(135deg, var(--cd-hero-bg-start) 0%, var(--cd-hero-bg-mid) 52%, var(--cd-hero-bg-end) 100%);
        border: 1px solid rgba(var(--cd-hero-rgb), 0.28);
        border-radius: var(--cd-r);
        padding: 1.15rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    [data-theme-mode="dark"] .cd-page-hero,
    [data-bs-theme="dark"] .cd-page-hero,
    .dark .cd-page-hero,
    html.dark .cd-page-hero {
        --cd-hero-bg-start: rgba(var(--cd-hero-rgb), 0.6);
        --cd-hero-bg-mid: rgba(var(--cd-hero-rgb), 0.52);
        --cd-hero-bg-end: rgba(var(--cd-hero-rgb), 0.44);
        border-color: rgba(var(--cd-hero-rgb), 0.36);
    }
    .cd-page-hero-title { font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 0; }
    .cd-page-hero-sub { font-size: var(--cd-font-sm); color: rgba(255,255,255,0.7); margin-bottom: 0; }
    .cd-hero-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 0.5rem 1.15rem; border-radius: var(--cd-r);
        font-size: var(--cd-font-sm); font-weight: 700; text-decoration: none;
        transition: all var(--cd-t); cursor: pointer; border: none; white-space: nowrap;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .cd-hero-btn:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
    .cd-hero-btn:active { transform: translateY(0) scale(0.98); }

    .cd-hero-btn-primary { background: #fff; color: var(--cd-accent-hover); }
    .cd-hero-btn-primary:hover { background: #fff; color: var(--cd-accent-hover); }

    .cd-hero-btn-outline { 
        background: rgba(255,255,255,0.1); 
        color: #fff; 
        border: 1px solid rgba(255,255,255,0.3); 
        backdrop-filter: blur(8px); 
    }
    .cd-hero-btn-outline:hover { background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.5); color: #fff; }

    .cd-hero-btn-ghost { background: rgba(255,255,255,0.15); color: #fff; backdrop-filter: blur(4px); }
    .cd-hero-btn-ghost:hover { background: rgba(255,255,255,0.25); color: #fff; }

    /* ── Search / toolbar bar ── */
    .cd-toolbar {
        display: flex; flex-wrap: wrap; align-items: center; gap: 8px;
        margin-bottom: 0.75rem;
    }
    .cd-search-wrap { position: relative; min-width: 200px; flex: 1; max-width: 320px; }
    .cd-search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 14px; pointer-events: none; }
    .cd-search {
        width: 100%; padding: 8px 12px 8px 34px; border: 1px solid var(--cd-border); border-radius: 8px;
        font-size: var(--cd-font-sm); background: var(--cd-bg-alt); transition: var(--cd-t); outline: none;
    }
    .cd-search:focus { border-color: var(--cd-accent); background: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.08); }
    .cd-toolbar-btn {
        padding: 7px 14px; border: 1px solid var(--cd-border); border-radius: 8px;
        font-size: var(--cd-font-sm); font-weight: 500; background: #fff; cursor: pointer; transition: var(--cd-t);
    }
    .cd-toolbar-btn:hover { background: var(--cd-bg-alt); border-color: #d1d5db; }
    .cd-toolbar-select {
        padding: 7px 12px; border: 1px solid var(--cd-border); border-radius: 8px;
        font-size: var(--cd-font-sm); background: #fff; cursor: pointer;
    }

    /* ── Profile info rows ── */
    .cd-info-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.55rem 0.75rem; border-radius: 8px; transition: background 0.15s;
    }
    .cd-info-row:nth-child(odd) { background: var(--cd-bg-alt); }
    .cd-info-row:hover { background: var(--cd-bg-stripe); }
    .cd-info-label { font-size: var(--cd-font-sm); color: var(--cd-text-muted); }
    .cd-info-value { font-size: var(--cd-font-sm); font-weight: 600; color: var(--cd-text); }

    /* ── Action buttons ── */
    .cd-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 0.5rem 1.25rem; border-radius: var(--cd-r); font-size: var(--cd-font-sm); font-weight: 700;
        text-decoration: none; transition: all var(--cd-t); cursor: pointer; border: none;
        letter-spacing: 0.2px;
    }
    .cd-btn:hover { transform: translateY(-1px) scale(1.01); }
    .cd-btn:active { transform: translateY(0) scale(0.98); }

    .cd-btn-primary { background: var(--cd-accent); color: #fff; box-shadow: 0 4px 6px -1px rgba(79,70,229,0.2); }
    .cd-btn-primary:hover { background: var(--cd-accent-hover); color: #fff; box-shadow: 0 10px 15px -3px rgba(79,70,229,0.3); }
    
    .cd-btn-outline { background: #fff; color: var(--cd-accent); border: 1px solid var(--cd-border); }
    .cd-btn-outline:hover { background: var(--cd-accent-light); border-color: var(--cd-accent-border); color: var(--cd-accent-hover); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    
    .cd-btn-danger { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .cd-btn-danger:hover { background: #fee2e2; color: #b91c1c; box-shadow: 0 4px 6px -1px rgba(220,38,38,0.1); }
    
    .cd-btn-sm { padding: 0.35rem 0.85rem; font-size: var(--cd-font-xs); border-radius: 8px; }

    /* ── Pagination ── */
    .cd-pagination { display: flex; justify-content: center; gap: 4px; margin-top: 1rem; }
    .cd-pagination .page-link { border-radius: 8px !important; font-size: var(--cd-font-sm); }

    /* ── Empty state ── */
    .cd-empty { text-align: center; padding: 2.5rem 1rem; }
    .cd-empty i { font-size: 2.5rem; color: #d1d5db; margin-bottom: 0.75rem; display: block; }
    .cd-empty p { font-size: var(--cd-font-base); color: #9ca3af; margin-bottom: 1rem; }

    /* ── Dark mode overrides ── */
    [data-theme-mode="dark"] .cd-section, .dark .cd-section { background: var(--bodybg, #1a1c2e); border-color: rgba(255,255,255,0.08); }
    [data-theme-mode="dark"] .cd-section-label, .dark .cd-section-label { color: #e5e7eb; }
    [data-theme-mode="dark"] .cd-section-head, .dark .cd-section-head { border-color: rgba(255,255,255,0.06); }
    [data-theme-mode="dark"] .cd-pipe, .dark .cd-pipe { border-color: rgba(255,255,255,0.08); }
    [data-theme-mode="dark"] .cd-pipe-lbl, .dark .cd-pipe-lbl { color: #d1d5db; }
    [data-theme-mode="dark"] .cd-table thead th, .dark .cd-table thead th { background: rgba(255,255,255,0.04); color: #9ca3af; border-color: rgba(255,255,255,0.06); }
    [data-theme-mode="dark"] .cd-table tbody td, .dark .cd-table tbody td { color: #d1d5db; border-color: rgba(255,255,255,0.04); }
    [data-theme-mode="dark"] .cd-table tbody tr:hover, .dark .cd-table tbody tr:hover { background: rgba(255,255,255,0.03); }
    [data-theme-mode="dark"] .cd-info-row:nth-child(odd), .dark .cd-info-row:nth-child(odd) { background: rgba(255,255,255,0.03); }
    [data-theme-mode="dark"] .cd-info-row:hover, .dark .cd-info-row:hover { background: rgba(255,255,255,0.06); }
    [data-theme-mode="dark"] .cd-info-value, .dark .cd-info-value { color: #e5e7eb; }
    [data-theme-mode="dark"] .cd-search, .dark .cd-search { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #e5e7eb; }
    [data-theme-mode="dark"] .cd-toolbar-btn, [data-theme-mode="dark"] .cd-toolbar-select, .dark .cd-toolbar-btn, .dark .cd-toolbar-select { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #e5e7eb; }
    [data-theme-mode="dark"] .cd-page-hero-sub, .dark .cd-page-hero-sub { color: rgba(255,255,255,0.7); }
</style>
