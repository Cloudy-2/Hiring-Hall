<style>
    /* ── Design Tokens ── */
    :root {
        --cd-r: 12px;
        --cd-s: 0 1px 3px rgba(0,0,0,0.05);
        --cd-sh: 0 6px 16px rgba(0,0,0,0.08);
        --cd-p: 20px;
        --cd-t: 0.2s ease;
        --cd-accent: #4f46e5;
        --cd-accent-hover: #4338ca;
        --cd-accent-light: #eef2ff;
        --cd-accent-border: #c7d2fe;
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
        padding: 0.75rem 1rem;
        background: var(--cd-bg-alt);
        border-bottom: 1px solid var(--cd-border);
        text-align: left;
    }
    .cd-table tbody td {
        padding: 1rem;
        font-size: var(--cd-font-sm);
        color: var(--cd-text-secondary);
        border-bottom: 1px solid var(--cd-bg-stripe);
    }
    .cd-table tbody tr:last-child td { border-bottom: none; }
    .cd-table tbody tr:hover { background: var(--cd-bg-alt); }

    /* ── Info key-value rows ── */
    .cd-info-row { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid var(--cd-bg-stripe); }
    .cd-info-row:last-child { border-bottom: none; }
    .cd-info-label { font-size: var(--cd-font-xs); font-weight: 600; color: var(--cd-text-muted); }
    .cd-info-value { font-size: var(--cd-font-sm); font-weight: 700; color: var(--cd-text); }

    /* ── Minimal Toolbar ── */
    .cd-toolbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; gap: 1rem; }
    .cd-search { 
        position: relative; 
        flex: 1; 
        max-width: 400px; 
    }
    .cd-search input {
        width: 100%;
        padding: 0.625rem 1rem 0.625rem 2.5rem;
        border-radius: 10px;
        border: 1px solid var(--cd-border);
        background: #fff;
        font-size: var(--cd-font-sm);
        transition: all 0.2s;
    }
    .cd-search input:focus { border-color: var(--cd-accent); box-shadow: 0 0 0 3px rgba(79,70,229,0.1); outline: none; }
    .cd-search i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--cd-text-muted); font-size: 0.9rem; }

    .cd-toolbar-actions { display: flex; align-items: center; gap: 0.5rem; }
    .cd-toolbar-btn {
        padding: 0.625rem 1rem;
        border-radius: 10px;
        border: 1px solid var(--cd-border);
        background: #fff;
        font-size: var(--cd-font-sm);
        font-weight: 600;
        color: var(--cd-text-secondary);
        transition: all 0.2s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .cd-toolbar-btn:hover { background: var(--cd-bg-alt); color: var(--cd-accent); border-color: var(--cd-accent-border); }

    .cd-toolbar-select {
        padding: 0.625rem 2rem 0.625rem 1rem;
        border-radius: 10px;
        border: 1px solid var(--cd-border);
        background: #fff;
        font-size: var(--cd-font-sm);
        font-weight: 600;
        color: var(--cd-text-secondary);
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
    }

    /* ── Buttons ── */
    .cd-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-size: var(--cd-font-sm);
        font-weight: 700;
        transition: all 0.2s;
        cursor: pointer;
        border: none;
        text-decoration: none !important;
    }
    .cd-btn-primary { background: var(--cd-accent); color: #fff; box-shadow: 0 4px 12px rgba(79,70,229,0.25); }
    .cd-btn-primary:hover { background: var(--cd-accent-hover); transform: translateY(-1px); box-shadow: 0 6px 15px rgba(79,70,229,0.3); color: #fff; }
    .cd-btn-ghost { background: var(--cd-bg-alt); color: var(--cd-text-secondary); }
    .cd-btn-ghost:hover { background: var(--cd-bg-stripe); color: var(--cd-text); }

    /* ── Pagination ── */
    .cd-pagination { display: flex; align-items: center; justify-content: center; margin-top: 2rem; }
    .cd-pagination .page-link { border-radius: 8px !important; font-size: var(--cd-font-sm); }

    /* ── Empty state ── */
    .cd-empty { text-align: center; padding: 2.5rem 1rem; }
    .cd-empty i { font-size: 2.5rem; color: #d1d5db; margin-bottom: 0.75rem; display: block; }
    .cd-empty p { font-size: var(--cd-font-base); color: #9ca3af; margin-bottom: 1rem; }

    /* ── Modern Premium Empty State (Elite SaaS) ── */
    .jf-empty-state { 
        padding: 3.5rem 2rem; 
        text-align: center; 
        background: #fff; 
        border-radius: 32px; 
        border: 2px dashed var(--cd-border); 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        gap: 1.25rem; 
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
        margin: 1.5rem 0;
        position: relative;
        overflow: hidden;
    }
    .jf-empty-state::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(79, 70, 229, 0.03) 0%, transparent 60%);
        pointer-events: none;
        z-index: 0;
    }
    .jf-empty-icon { 
        width: 72px; 
        height: 72px; 
        border-radius: 20px; 
        background: linear-gradient(135deg, var(--cd-accent-light) 0%, rgba(79, 70, 229, 0.15) 100%); 
        color: var(--cd-accent); 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 2.25rem; 
        transition: all 0.4s ease; 
        box-shadow: 0 12px 25px -8px rgba(79, 70, 229, 0.25);
        position: relative;
        z-index: 1;
    }
    .jf-empty-icon i { filter: drop-shadow(0 3px 5px rgba(79, 70, 229, 0.2)); }
    
    .jf-empty-state:hover { 
        border-color: var(--cd-accent); 
        transform: translateY(-4px); 
        box-shadow: 0 15px 30px -10px rgba(0,0,0,0.06);
    }
    .jf-empty-state:hover .jf-empty-icon { 
        transform: scale(1.08) rotate(4deg); 
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.2) 0%, rgba(79, 70, 229, 0.25) 100%);
    }

    :is([data-theme-mode="dark"], .dark) .jf-empty-state {
        background: rgba(30, 41, 59, 0.4);
        border-color: rgba(255,255,255,0.08);
        backdrop-filter: blur(12px);
    }
    :is([data-theme-mode="dark"], .dark) .jf-empty-icon {
        background: rgba(79, 70, 229, 0.2);
        box-shadow: 0 12px 25px -8px rgba(0, 0, 0, 0.4);
    }

    /* ── Forms & Inputs ── */
    .jf-form-label {
        font-size: var(--cd-font-xs);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--cd-text-secondary);
        margin-bottom: 0.5rem;
        display: block;
    }
    .jf-input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: #fff;
        border: 1px solid var(--cd-border);
        border-radius: 12px;
        font-size: var(--cd-font-base);
        transition: all 0.2s ease;
        color: var(--cd-text);
    }
    .jf-input:focus {
        border-color: var(--cd-accent);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    :is([data-theme-mode="dark"], .dark) .jf-input {
        background: rgba(255,255,255,0.03);
        border-color: rgba(255,255,255,0.1);
        color: #fff;
    }
    :is([data-theme-mode="dark"], .dark) .jf-input:focus {
        border-color: var(--cd-accent);
        background: rgba(255,255,255,0.05);
    }

    /* Select2 Elite Overrides */
    .select2-container--default .select2-selection--multiple {
        background-color: transparent !important;
        border: 1px solid var(--cd-border) !important;
        border-radius: 12px !important;
        min-height: 46px !important;
        padding: 4px !important;
    }
    :is([data-theme-mode="dark"], .dark) .select2-container--default .select2-selection--multiple { border-color: rgba(255,255,255,0.1) !important; }
    
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: var(--cd-accent) !important;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: var(--cd-accent) !important;
        border: none !important;
        color: #fff !important;
        border-radius: 8px !important;
        padding: 3px 12px !important;
        font-weight: 600 !important;
        font-size: var(--cd-font-xs) !important;
        margin-top: 4px !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff !important;
        margin-right: 5px !important;
        border: none !important;
    }
    .select2-dropdown {
        border-radius: 12px !important;
        border: 1px solid var(--cd-border) !important;
        box-shadow: var(--cd-sh) !important;
        overflow: hidden !important;
    }
    :is([data-theme-mode="dark"], .dark) .select2-dropdown {
        background-color: #1a1c2e !important;
        border-color: rgba(255,255,255,0.1) !important;
    }
    .select2-results__option {
        padding: 8px 12px !important;
        font-size: var(--cd-font-sm) !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: var(--cd-accent) !important; }

    /* ── Dark mode overrides ── */
    :is([data-theme-mode="dark"], .dark) .cd-section { background: var(--bodybg, #1a1c2e); border-color: rgba(255,255,255,0.08); }
    :is([data-theme-mode="dark"], .dark) .cd-section-label { color: #e5e7eb; }
    :is([data-theme-mode="dark"], .dark) .cd-section-head { border-color: rgba(255,255,255,0.06); }
    :is([data-theme-mode="dark"], .dark) .cd-pipe { border-color: rgba(255,255,255,0.08); }
    :is([data-theme-mode="dark"], .dark) .cd-pipe-lbl { color: #d1d5db; }
    :is([data-theme-mode="dark"], .dark) .cd-table thead th { background: rgba(255,255,255,0.04); color: #9ca3af; border-color: rgba(255,255,255,0.06); }
    :is([data-theme-mode="dark"], .dark) .cd-table tbody td { color: #d1d5db; border-bottom-color: rgba(255,255,255,0.04); }
    :is([data-theme-mode="dark"], .dark) .cd-table tbody tr:hover { background: rgba(255,255,255,0.03); }
    :is([data-theme-mode="dark"], .dark) .cd-info-row { border-color: rgba(255,255,255,0.03); }
    :is([data-theme-mode="dark"], .dark) .cd-info-value { color: #e5e7eb; }
    :is([data-theme-mode="dark"], .dark) .cd-search input { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #e5e7eb; }
    :is([data-theme-mode="dark"], .dark) .cd-toolbar-btn, :is([data-theme-mode="dark"], .dark) .cd-toolbar-select { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #e5e7eb; }
    :is([data-theme-mode="dark"], .dark) .cd-page-hero-sub { color: rgba(255,255,255,0.7); }

    /* ── Modern Minimalist Header (Unified SaaS Style) ── */
    .jf-header-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--cd-border);
        margin-bottom: 2rem;
        position: relative;
    }
    .jf-header-content { flex: 1; }
    .jf-context-row { display: flex; align-items: center; gap: 0.625rem; margin-bottom: 0.75rem; }
    .jf-v-bar { width: 4px; height: 20px; background: var(--cd-accent); border-radius: 4px; }
    .jf-context-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; color: var(--cd-accent); background: rgba(79, 70, 229, 0.1); padding: 2px 10px; border-radius: 20px; }
    .jf-header-title { font-size: 2.25rem; font-weight: 800; color: var(--cd-text); letter-spacing: -0.02em; margin-bottom: 0.75rem; line-height: 1.2; }
    .jf-header-desc { font-size: 1rem; color: var(--cd-text-secondary); max-width: 700px; line-height: 1.5; }
    .jf-header-desc b { color: var(--cd-accent); font-weight: 700; }
    .jf-header-actions { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem; }

    @media (max-width: 992px) {
        .jf-header-section { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
        .jf-header-title { font-size: 1.875rem; }
        .jf-header-actions { width: 100%; flex-wrap: wrap; }
    }

    /* ── Robust Dark Mode Overrides (Elite SaaS) ── */
    :is([data-theme-mode="dark"], .dark) .jf-header-section { border-color: rgba(255, 255, 255, 0.08); }
    :is([data-theme-mode="dark"], .dark) .jf-header-actions a,
    :is([data-theme-mode="dark"], .dark) .jf-header-actions button,
    :is([data-theme-mode="dark"], .dark) .cd-page-hero a,
    :is([data-theme-mode="dark"], .dark) .cd-page-hero button {
        background-color: rgba(30, 41, 59, 0.8) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #ffffff !important;
    }
    :is([data-theme-mode="dark"], .dark) .jf-header-actions a:hover,
    :is([data-theme-mode="dark"], .dark) .jf-header-actions button:hover,
    :is([data-theme-mode="dark"], .dark) .cd-page-hero a:hover,
    :is([data-theme-mode="dark"], .dark) .cd-page-hero button:hover {
        background-color: rgba(51, 65, 85, 0.9) !important;
    }
    :is([data-theme-mode="dark"], .dark) .jf-header-title,
    :is([data-theme-mode="dark"], .dark) .cd-page-hero-title { color: #ffffff !important; }
    :is([data-theme-mode="dark"], .dark) .jf-header-desc,
    :is([data-theme-mode="dark"], .dark) .cd-page-hero-sub { color: #94a3b8 !important; }
    :is([data-theme-mode="dark"], .dark) .jf-header-desc b { color: #818cf8; }
</style>
