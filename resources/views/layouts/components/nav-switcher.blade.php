<div id="hs-overlay-switcher" class="hs-overlay hidden ti-offcanvas ti-offcanvas-right flex flex-col" tabindex="-1">
    <div class="ti-offcanvas-header z-10 relative flex-shrink-0">
        <h5 class="ti-offcanvas-title">
            Theme Settings
        </h5>
        <button type="button"
            class="ti-btn flex-shrink-0 p-0 !mb-0 transition-none text-defaulttextcolor dark:text-defaulttextcolor/80 hover:text-gray-700 focus:ring-gray-400 focus:ring-offset-white dark:hover:text-white/80 dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
            data-hs-overlay="#hs-overlay-switcher">
            <span class="sr-only">Close modal</span>
            <i class="ri-close-circle-line leading-none text-lg"></i>
        </button>
    </div>

    <div class="relative flex-1 min-h-0 overflow-hidden flex flex-col">

        <div class="ti-offcanvas-body !p-0 !overflow-y-auto !min-h-0 flex-1 relative flex flex-col" id="switcher-body">

            <div class="flex flex-col pb-4">
                {{-- Hidden legacy radios kept for JS compatibility --}}
                <div class="hidden" aria-hidden="true">
                    <input type="radio" name="direction" id="switcher-ltr" checked>
                    <input type="radio" name="direction" id="switcher-rtl">
                    <input type="radio" name="navigation-style" id="switcher-vertical" checked>
                    <input type="radio" name="navigation-style" id="switcher-horizontal">
                    <input type="radio" name="layout-width" id="switcher-full-width" checked>
                    <input type="radio" name="layout-width" id="switcher-boxed">
                    <input type="radio" name="data-menu-positions" id="switcher-menu-fixed" checked>
                    <input type="radio" name="data-menu-positions" id="switcher-menu-scroll">
                    <input type="radio" name="data-header-positions" id="switcher-header-fixed" checked>
                    <input type="radio" name="data-header-positions" id="switcher-header-scroll">
                    <input type="radio" name="navigation-data-menu-styles" id="switcher-menu-click" checked>
                    <input type="radio" name="navigation-data-menu-styles" id="switcher-menu-hover">
                    <input type="radio" name="navigation-data-menu-styles" id="switcher-icon-click">
                    <input type="radio" name="navigation-data-menu-styles" id="switcher-icon-hover">
                </div>



                {{-- Theme Style --}}
                <div class="sw-section border-b border-slate-100 dark:border-white/5">
                    <p class="sw-section-head">THEME COLORS</p>
                    <div class="grid grid-cols-2 gap-2">
                        <label for="switcher-light-theme" class="sw-card">
                            <input type="radio" name="theme-style" class="ti-form-radio sr-only"
                                id="switcher-light-theme" checked>
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="4" fill="#f1f5f9" />
                                    <rect x="0" y="0" width="60" height="8" rx="3" fill="#e2e8f0" />
                                    <circle cx="30" cy="26" r="5" fill="#94a3b8" />
                                    <path
                                        d="M30 16v2m0 16v2m-9.9-9h2m15.8 0h2m-13.9-7.1l1.4 1.4m9.9 9.9l1.4 1.4m-12.7 0l1.4-1.4m9.9-9.9l1.4-1.4"
                                        stroke="#94a3b8" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Light</span>
                        </label>

                        <label for="switcher-dark-theme" class="sw-card">
                            <input type="radio" name="theme-style" class="ti-form-radio sr-only"
                                id="switcher-dark-theme">
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="4" fill="#1e293b" />
                                    <rect x="0" y="0" width="60" height="8" rx="3" fill="#0f172a" />
                                    <path d="M30 18 A 8 8 0 1 0 35 32 A 9 9 0 0 1 30 18 Z" fill="#64748b" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Dark</span>
                        </label>
                    </div>
                </div>

                {{-- 1. Navigation Styles --}}
                <div class="sw-section border-b border-slate-100 dark:border-white/5">
                    <p class="sw-section-head">Navigation Styles</p>
                    <div class="grid grid-cols-2 gap-2">

                        <label for="switcher-menu-v1" class="sw-card">
                            <input type="radio" name="menu-version-style" class="ti-form-radio sr-only"
                                id="switcher-menu-v1" checked>
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 40 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="40" height="20" rx="4" class="sw-t-bg" />
                                    <rect x="0" y="0" width="13" height="44" rx="3" class="sw-t-sidebar" />
                                    <rect x="15" y="4" width="41" height="6" rx="2" class="sw-t-bar" />
                                    <rect x="15" y="14" width="31" height="4" rx="2" class="sw-t-content" />
                                    <rect x="15" y="22" width="23" height="4" rx="2" class="sw-t-content" />
                                    <rect x="15" y="30" width="28" height="4" rx="2" class="sw-t-content" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Vertical</span>
                        </label>

                        <label for="switcher-menu-v2" class="sw-card">
                            <input type="radio" name="menu-version-style" class="ti-form-radio sr-only"
                                id="switcher-menu-v2">
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 40 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="40" height="44" rx="4" class="sw-t-bg" />
                                    <rect x="0" y="0" width="13" height="44" rx="3" class="sw-t-sidebar" />
                                    <rect x="15" y="4" width="41" height="6" rx="2" class="sw-t-bar" />
                                    <rect x="15" y="14" width="31" height="4" rx="2" class="sw-t-content" />
                                    <rect x="15" y="22" width="23" height="4" rx="2" class="sw-t-content" />
                                    <rect x="15" y="30" width="28" height="4" rx="2" class="sw-t-content" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Horizontal</span>
                        </label>

                    </div>
                </div>

                {{-- 2. Sidemenu Layout Styles --}}
                <div class="sw-section border-b border-slate-100 dark:border-white/5 sidemenu-layout-styles">
                    <p class="sw-section-head">Sidemenu Layout Styles</p>
                    <div class="grid grid-cols-3 gap-2">

                        <label for="switcher-default-menu" class="sw-card">
                            <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio sr-only"
                                id="switcher-default-menu" checked>
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="4" class="sw-t-bg" />
                                    <rect x="0" y="0" width="14" height="44" rx="3" class="sw-t-sidebar" />
                                    <rect x="2" y="5" width="10" height="3" rx="1" fill="white" opacity="0.5" />
                                    <rect x="2" y="12" width="10" height="3" rx="1" fill="white" opacity="0.35" />
                                    <rect x="2" y="19" width="10" height="3" rx="1" fill="white" opacity="0.35" />
                                    <rect x="16" y="4" width="40" height="5" rx="2" class="sw-t-bar" />
                                    <rect x="16" y="13" width="30" height="4" rx="2" class="sw-t-content" />
                                    <rect x="16" y="21" width="22" height="4" rx="2" class="sw-t-content" />
                                    <rect x="16" y="30" width="28" height="4" rx="2" class="sw-t-content" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Default Menu</span>
                        </label>

                        <label for="switcher-closed-menu" class="sw-card">
                            <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio sr-only"
                                id="switcher-closed-menu">
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="4" class="sw-t-bg" />
                                    <rect x="0" y="0" width="5" height="44" rx="2" class="sw-t-sidebar" />
                                    <rect x="7" y="4" width="49" height="5" rx="2" class="sw-t-bar" />
                                    <rect x="7" y="13" width="37" height="4" rx="2" class="sw-t-content" />
                                    <rect x="7" y="21" width="27" height="4" rx="2" class="sw-t-content" />
                                    <rect x="7" y="30" width="32" height="4" rx="2" class="sw-t-content" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Closed Menu</span>
                        </label>

                        <label for="switcher-icontext-menu" class="sw-card">
                            <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio sr-only"
                                id="switcher-icontext-menu">
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="4" class="sw-t-bg" />
                                    <rect x="0" y="0" width="9" height="44" rx="2" class="sw-t-sidebar" />
                                    <circle cx="4.5" cy="8" r="2" fill="white" opacity="0.55" />
                                    <circle cx="4.5" cy="16" r="2" fill="white" opacity="0.35" />
                                    <circle cx="4.5" cy="24" r="2" fill="white" opacity="0.35" />
                                    <rect x="11" y="4" width="45" height="5" rx="2" class="sw-t-bar" />
                                    <rect x="11" y="13" width="34" height="4" rx="2" class="sw-t-content" />
                                    <rect x="11" y="21" width="25" height="4" rx="2" class="sw-t-content" />
                                    <rect x="11" y="30" width="30" height="4" rx="2" class="sw-t-content" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Icon Only</span>
                        </label>

                        <label for="switcher-detached" class="sw-card opacity-60 cursor-not-allowed"
                            aria-disabled="true">
                            <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio sr-only"
                                id="switcher-detached" disabled>
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="4" class="sw-t-bg" />
                                    <rect x="2" y="2" width="56" height="6" rx="2" class="sw-t-bar" />
                                    <rect x="2" y="10" width="13" height="32" rx="3" class="sw-t-sidebar" />
                                    <rect x="17" y="12" width="39" height="4" rx="2" class="sw-t-content" />
                                    <rect x="17" y="20" width="28" height="4" rx="2" class="sw-t-content" />
                                    <rect x="17" y="28" width="33" height="4" rx="2" class="sw-t-content" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Detached</span>
                            <span class="sw-card-status">Not yet working</span>
                        </label>

                        <label for="switcher-doublemenu" class="sw-card">
                            <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio sr-only"
                                id="switcher-doublemenu">
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="4" class="sw-t-bg" />
                                    <rect x="0" y="0" width="9" height="44" rx="2" class="sw-t-sidebar" />
                                    <rect x="10" y="0" width="13" height="44" rx="2" class="sw-t-bar" />
                                    <rect x="25" y="4" width="31" height="5" rx="2" class="sw-t-content" />
                                    <rect x="25" y="13" width="23" height="4" rx="2" class="sw-t-content" />
                                    <rect x="25" y="21" width="28" height="4" rx="2" class="sw-t-content" />
                                    <rect x="25" y="30" width="19" height="4" rx="2" class="sw-t-content" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Double</span>
                        </label>

                        <label for="switcher-overlay" class="sw-card opacity-60 cursor-not-allowed"
                            aria-disabled="true">
                            <input type="radio" name="sidemenu-layout-styles" class="ti-form-radio sr-only"
                                id="switcher-overlay" disabled>
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="4" class="sw-t-bg" />
                                    <rect x="0" y="4" width="60" height="5" rx="2" class="sw-t-bar" />
                                    <rect x="0" y="13" width="60" height="4" rx="2" class="sw-t-content" />
                                    <rect x="0" y="21" width="60" height="4" rx="2" class="sw-t-content" />
                                    <rect x="0" y="0" width="18" height="44" rx="3" class="sw-t-sidebar"
                                        opacity="0.88" />
                                    <circle cx="9" cy="9" r="2" fill="white" opacity="0.6" />
                                    <circle cx="9" cy="17" r="2" fill="white" opacity="0.4" />
                                    <circle cx="9" cy="25" r="2" fill="white" opacity="0.4" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Overlay</span>
                            <span class="sw-card-status">Not yet working</span>
                        </label>

                    </div>
                </div>

                {{-- 3. Page Styles --}}
                <div class="sw-section border-b border-slate-100 dark:border-white/5">
                    <p class="sw-section-head">Page Styles</p>
                    <div class="grid grid-cols-3 gap-2">

                        <label for="switcher-regular" class="sw-card">
                            <input type="radio" name="data-page-styles" class="ti-form-radio sr-only"
                                id="switcher-regular" checked>
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="4" class="sw-t-bg" />
                                    <rect x="0" y="0" width="60" height="8" rx="3" class="sw-t-sidebar" />
                                    <rect x="4" y="12" width="52" height="5" rx="2" class="sw-t-bar" />
                                    <rect x="4" y="21" width="38" height="4" rx="2" class="sw-t-content" />
                                    <rect x="4" y="29" width="28" height="4" rx="2" class="sw-t-content" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Regular</span>
                        </label>

                        <label for="switcher-classic" class="sw-card">
                            <input type="radio" name="data-page-styles" class="ti-form-radio sr-only"
                                id="switcher-classic">
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="0" class="sw-t-bg" />
                                    <rect x="0" y="0" width="60" height="9" rx="0" class="sw-t-sidebar" />
                                    <rect x="4" y="13" width="52" height="5" rx="1" class="sw-t-bar" />
                                    <rect x="4" y="22" width="38" height="4" rx="1" class="sw-t-content" />
                                    <rect x="4" y="30" width="28" height="4" rx="1" class="sw-t-content" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Classic</span>
                        </label>

                        <label for="switcher-modern" class="sw-card">
                            <input type="radio" name="data-page-styles" class="ti-form-radio sr-only"
                                id="switcher-modern">
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="8" class="sw-t-bg" />
                                    <rect x="2" y="2" width="56" height="8" rx="5" class="sw-t-sidebar" />
                                    <rect x="6" y="14" width="48" height="5" rx="3" class="sw-t-bar" />
                                    <rect x="6" y="23" width="34" height="4" rx="2" class="sw-t-content" />
                                    <rect x="6" y="31" width="24" height="4" rx="2" class="sw-t-content" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Modern</span>
                        </label>

                    </div>
                </div>

                {{-- 4. Loader --}}
                <div class="sw-section">
                    <p class="sw-section-head">Loader</p>
                    <div class="grid grid-cols-2 gap-2">

                        <label for="switcher-loader-enable" class="sw-card">
                            <input type="radio" name="page-loader" class="ti-form-radio sr-only"
                                id="switcher-loader-enable" checked>
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="4" class="sw-t-bg" />
                                    <circle cx="30" cy="22" r="10" class="sw-t-bar" stroke-width="0" fill="none" />
                                    <circle cx="30" cy="22" r="9" stroke-width="2.5" class="sw-t-track" fill="none" />
                                    <path d="M30 13 A9 9 0 0 1 39 22" stroke-width="2.5" stroke-linecap="round"
                                        fill="none" class="sw-t-spinner" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Enable</span>
                        </label>

                        <label for="switcher-loader-disable" class="sw-card">
                            <input type="radio" name="page-loader" class="ti-form-radio sr-only"
                                id="switcher-loader-disable">
                            <div class="sw-card-thumb">
                                <svg viewBox="0 0 60 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="60" height="44" rx="4" class="sw-t-bg" />
                                    <circle cx="30" cy="22" r="9" stroke-width="2.5" class="sw-t-track" fill="none" />
                                    <line x1="24" y1="16" x2="36" y2="28" stroke-width="2.5" stroke-linecap="round"
                                        class="sw-t-track" />
                                </svg>
                            </div>
                            <span class="sw-card-label">Disable</span>
                        </label>

                    </div>
                </div>

            </div>
        </div>

        {{-- Reset Button --}}
        <div
            class="z-20 border-t border-slate-100 dark:border-white/10 bg-white/95 dark:bg-bodybg/95 backdrop-blur px-4 py-3 flex-shrink-0">
            <button type="button" class="w-full ti-btn ti-btn-danger !border !border-slate-200 dark:!border-white/10"
                data-reset-all="true">
                Reset Theme Settings
            </button>
        </div>

    </div>

    <style>
        /* ── Switcher Card System ── */
        .sw-section {
            padding: 1rem 1rem 0.875rem;
        }

        .sw-section-head {
            font-size: 0.6875rem;
            font-weight: 800;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 0.625rem;
            margin-top: 0;
        }

        html.dark .sw-section-head {
            color: #64748b;
        }

        .sw-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.375rem 0.5rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            cursor: pointer;
            background: #ffffff;
            transition: border-color 0.15s ease, background 0.15s ease;
            user-select: none;
        }

        html.dark .sw-card {
            background: rgb(var(--body-bg, 30 41 59));
            border-color: rgba(255, 255, 255, 0.08);
        }

        .sw-card:hover {
            border-color: #a5b4fc;
            background: #f5f3ff;
        }

        html.dark .sw-card:hover {
            border-color: rgba(99, 102, 241, 0.4);
            background: rgba(99, 102, 241, 0.07);
        }

        .sw-card.sw-active {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.05);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        html.dark .sw-card.sw-active {
            border-color: #818cf8;
            background: rgba(99, 102, 241, 0.12);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18);
        }

        .sw-card-thumb {
            width: 100%;
            height: 46px;
            border-radius: 6px;
            overflow: hidden;
        }

        .sw-card-thumb svg {
            width: 100%;
            height: 100%;
        }

        .sw-card-label {
            font-size: 0.6rem;
            font-weight: 700;
            color: #94a3b8;
            text-align: center;
            line-height: 1.2;
            letter-spacing: 0.02em;
            white-space: nowrap;
        }

        .sw-card-status {
            font-size: 0.55rem;
            font-weight: 700;
            color: #b45309;
            background: #fef3c7;
            border-radius: 9999px;
            padding: 0.1rem 0.4rem;
            line-height: 1.1;
        }

        html.dark .sw-card-status {
            color: #fcd34d;
            background: rgba(146, 64, 14, 0.45);
        }

        .sw-card.sw-active .sw-card-label {
            color: #4f46e5;
        }

        html.dark .sw-card.sw-active .sw-card-label {
            color: #818cf8;
        }

        /* SVG theme element classes */
        .sw-t-bg {
            fill: #f1f5f9;
        }

        .sw-t-sidebar {
            fill: #c7d2fe;
        }

        .sw-t-bar {
            fill: #e2e8f0;
        }

        .sw-t-content {
            fill: #e9edf2;
        }

        html.dark .sw-t-bg {
            fill: #1e293b;
        }

        html.dark .sw-t-sidebar {
            fill: #4338ca;
        }

        html.dark .sw-t-bar {
            fill: #334155;
        }

        html.dark .sw-t-content {
            fill: #2d3a4a;
        }

        /* Active card: make sidebar pop with primary indigo */
        .sw-card.sw-active .sw-t-sidebar {
            fill: #6366f1;
        }

        .sw-card.sw-active .sw-t-bar {
            fill: #c7d2fe;
        }

        html.dark .sw-card.sw-active .sw-t-sidebar {
            fill: #818cf8;
        }

        html.dark .sw-card.sw-active .sw-t-bar {
            fill: #4338ca;
        }

        /* Loader icon strokes */
        .sw-t-track {
            stroke: #e2e8f0;
        }

        .sw-t-spinner {
            stroke: #818cf8;
        }

        html.dark .sw-t-track {
            stroke: #334155;
        }

        .sw-card.sw-active .sw-t-track {
            stroke: #c7d2fe;
        }

        .sw-card.sw-active .sw-t-spinner {
            stroke: #6366f1;
        }

        html.dark .sw-card.sw-active .sw-t-track {
            stroke: #4338ca;
        }

        html.dark .sw-card.sw-active .sw-t-spinner {
            stroke: #818cf8;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function getCurrentLayoutSettings() {
                return {
                    menu_version: document.documentElement.getAttribute('data-menu-version'),
                    nav_layout: document.documentElement.getAttribute('data-nav-layout'),
                    nav_style: document.documentElement.getAttribute('data-nav-style'),
                    vertical_style: document.documentElement.getAttribute('data-vertical-style'),
                    width: document.documentElement.getAttribute('data-width'),
                    page_style: document.documentElement.getAttribute('data-page-style'),
                    menu_position: document.documentElement.getAttribute('data-menu-position'),
                    header_position: document.documentElement.getAttribute('data-header-position'),
                    loader: document.documentElement.getAttribute('loader'),
                };
            }

            function persistCurrentLayoutSettings() {
                if (typeof window.persistLayoutSettings !== 'function') {
                    return;
                }

                window.persistLayoutSettings(getCurrentLayoutSettings());
            }

            function restoreSavedSidemenuLayoutStyle() {
                var savedVerticalStyle = (window.databaseLayoutSettings && window.databaseLayoutSettings.vertical_style) ||
                    localStorage.getItem('xyntraverticalstyles');
                var currentNavLayout = document.documentElement.getAttribute('data-nav-layout');

                if (savedVerticalStyle === 'detached' || savedVerticalStyle === 'overlay') {
                    savedVerticalStyle = 'default';
                }

                if (!savedVerticalStyle || currentNavLayout === 'horizontal') {
                    return;
                }

                var styleToInputId = {
                    'default': '#switcher-default-menu',
                    'closed': '#switcher-closed-menu',
                    'icontext': '#switcher-icontext-menu',
                    'detached': '#switcher-detached',
                    'overlay': '#switcher-overlay',
                    'doublemenu': '#switcher-doublemenu'
                };

                var styleInput = document.querySelector(styleToInputId[savedVerticalStyle]);

                if (!styleInput) {
                    return;
                }

                styleInput.checked = true;
                styleInput.dispatchEvent(new Event('click', {
                    bubbles: true
                }));
                styleInput.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            }

            restoreSavedSidemenuLayoutStyle();

            // ── Switcher Card Init ──
            // For each radio group, mark the checked radio's parent label as .sw-active
            function initSwCards() {
                document.querySelectorAll('#switcher-body .sw-card input[type="radio"]').forEach(function (radio) {
                    var card = radio.closest('.sw-card');
                    if (!card) return;
                    if (radio.checked) card.classList.add('sw-active');
                    radio.addEventListener('change', function () {
                        // Remove active from siblings in same radio group
                        document.querySelectorAll('input[name="' + radio.name + '"]').forEach(function (r) {
                            var c = r.closest('.sw-card');
                            if (c) c.classList.remove('sw-active');
                        });
                        if (this.checked) card.classList.add('sw-active');
                    });
                });
            }
            initSwCards();

            // Handle horizontal/vertical layout toggle logic and legacy proxy
            function handleNavigationStyleToggle() {
                var horizontalRadio = document.querySelector('#switcher-menu-v2');
                var overlayRadio = document.querySelector('#switcher-overlay');
                var sidemenuLayoutStyles = document.querySelector('.sidemenu-layout-styles');

                function toggleSidemenuStyles() {
                    var isHorizontal = horizontalRadio && horizontalRadio.checked;

                    if (sidemenuLayoutStyles) {
                        if (isHorizontal) {
                            sidemenuLayoutStyles.style.display = 'none';
                            sidemenuLayoutStyles.querySelectorAll('input').forEach(i => i.disabled = true);

                            // If horizontal is selected, ensure overlay is unselected visually
                            if (overlayRadio && overlayRadio.checked) {
                                var defaultMenuRadio = document.querySelector('#switcher-default-menu');
                                if (defaultMenuRadio) {
                                    defaultMenuRadio.checked = true;
                                    defaultMenuRadio.dispatchEvent(new Event('change'));
                                }
                            }
                        } else {
                            sidemenuLayoutStyles.style.display = '';
                            sidemenuLayoutStyles.querySelectorAll('input').forEach(i => i.disabled = false);
                        }
                    }

                    setTimeout(persistCurrentLayoutSettings, 0);
                }

                // Sync visual radios with legacy JS inputs
                if (horizontalRadio) {
                    horizontalRadio.addEventListener('change', function () {
                        if (this.checked) {
                            var legacyBtn = document.querySelector('#switcher-horizontal');
                            if (legacyBtn) legacyBtn.click();
                            toggleSidemenuStyles();
                        }
                    });
                }

                var verticalRadio = document.querySelector('#switcher-menu-v1');
                if (verticalRadio) {
                    verticalRadio.addEventListener('change', function () {
                        if (this.checked) {
                            var legacyBtn = document.querySelector('#switcher-vertical');
                            if (legacyBtn) legacyBtn.click();
                            toggleSidemenuStyles();
                        }
                    });
                }

                document.querySelectorAll('input[name="sidemenu-layout-styles"], input[name="menu-version-style"]').forEach(function (radio) {
                    radio.addEventListener('change', function () {
                        setTimeout(persistCurrentLayoutSettings, 0);
                    });
                });

                // Also cover legacy changes if triggered manually
                document.querySelectorAll('input[name="navigation-style"]').forEach(function (radio) {
                    radio.addEventListener('change', toggleSidemenuStyles);
                });

                // Run on initial load
                toggleSidemenuStyles();
            }
            handleNavigationStyleToggle();

            function forceVerticalAsResetDefault() {
                var html = document.documentElement;
                var v1Radio = document.querySelector('#switcher-menu-v1');
                var defaultMenuRadio = document.querySelector('#switcher-default-menu');
                var legacyVerticalBtn = document.querySelector('#switcher-vertical');

                html.setAttribute('data-menu-version', 'v1');
                html.setAttribute('data-nav-layout', 'vertical');
                html.setAttribute('data-vertical-style', 'default');

                if (v1Radio) {
                    v1Radio.checked = true;
                    v1Radio.dispatchEvent(new Event('change', { bubbles: true }));
                }

                if (defaultMenuRadio) {
                    defaultMenuRadio.checked = true;
                    defaultMenuRadio.dispatchEvent(new Event('change', { bubbles: true }));
                }

                if (legacyVerticalBtn) {
                    legacyVerticalBtn.click();
                }

                try {
                    localStorage.removeItem('xyntranavstyles');
                    localStorage.setItem('menu-version-style', 'v1');
                    localStorage.setItem('xyntraverticalstyles', 'default');
                } catch (error) {
                    // Ignore storage errors.
                }

                setTimeout(persistCurrentLayoutSettings, 0);
            }

            function bindResetDefaults() {
                document.querySelectorAll('[data-reset-all="true"]').forEach(function (button) {
                    button.addEventListener('click', function () {
                        // Let existing reset handlers run first, then enforce vertical defaults.
                        setTimeout(forceVerticalAsResetDefault, 150);
                    });
                });
            }

            bindResetDefaults();

            // Sync dark-mode toggle with theme on external change
            window.addEventListener('xyntra-theme-changed', function () {
                // Re-evaluate SVG colours handled by CSS; nothing extra needed.
            });
        });
    </script>