@php
    $user = Auth::user();
@endphp
<script>
    // Keep layout attributes in sync as early as possible.
    // This prevents the V1 sidebar from briefly transforming open while v2/top-header is active.
    (function () {
        try {
            const layoutSettings = window.databaseLayoutSettings || null;
            if (!layoutSettings?.menu_version) {
                return;
            }

            document.documentElement.setAttribute('data-menu-version', layoutSettings.menu_version);
            document.documentElement.setAttribute('data-nav-layout', layoutSettings.nav_layout || (layoutSettings.menu_version === 'v2' ? 'horizontal' : 'vertical'));
        } catch (e) {
            // Ignore runtime errors and let default behavior take over.
        }
    })();
</script>
<div id="top-header-menu" class="hidden relative" style="z-index: 10;">
    <nav class="w-full bg-white dark:bg-bodybg border-b border-defaultborder dark:border-white/10 px-4 py-1.5 md:px-8 shadow-sm relative"
        style="z-index: 10; overflow: visible;">
        <div class="flex items-center justify-between max-w-[100rem] mx-auto w-full relative"
            style="overflow: visible;">

            <!-- Left: Logo -->
            <!-- Desktop Branding (Locked) -->
            <div class="desktop-branding items-center gap-5 cursor-pointer group" onclick="window.location.href='/'">
                <img src="/assets/raw/hillbcs-logo.png" alt="Logo" class="w-14 h-14 object-contain">
                <div class="flex flex-col leading-none">
                    <span class="text-[16px] font-black tracking-tight text-slate-800 dark:text-white uppercase"
                        style="font-family: 'Montserrat', sans-serif;">Hiring Hall</span>
                    <span
                        class="text-[7px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-0.5 opacity-50">Hillbcs
                        Powered</span>
                </div>
            </div>

            <!-- Mobile Branding (Fixed) -->
            <div class="mobile-branding items-center gap-2" onclick="window.location.href='/'">
                <img src="/assets/raw/hillbcs-logo.png" alt="Logo" class="w-10 h-10 object-contain">
                <div class="flex flex-col leading-none">
                    <span class="text-[12px] font-black tracking-tight text-slate-800 dark:text-white uppercase"
                        style="font-family: 'Montserrat', sans-serif;">Hiring Hall</span>
                    <span
                        class="text-[6px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mt-0.5 opacity-50">Hillbcs
                        Powered</span>
                </div>
            </div>

            <!-- Center: Navigation Menus -->
            <div id="v2-nav-menu-container" style="z-index: 1060;"
                class="hidden lg:flex flex-col lg:flex-row items-stretch lg:items-center justify-center flex-1 absolute lg:static top-full left-0 w-full lg:w-auto bg-white dark:bg-bodybg border-b lg:border-none border-gray-100 dark:border-white/10 py-4 lg:py-0 shadow-md lg:shadow-none">
                <ul
                    class="flex flex-col lg:flex-row flex-wrap items-stretch lg:items-center gap-2 lg:gap-5 px-4 lg:px-0">
                    @if ($user && $user->role === 'applicant')
                        @include('components.navigation.sidemenu-v2.headers.candidate-header')
                    @elseif ($user && $user->role === 'employer')
                        @include('components.navigation.sidemenu-v2.headers.employer-header')
                    @elseif ($user && in_array($user->role, ['moderator', 'admin', 'super_admin']))
                        @include('components.navigation.sidemenu-v2.headers.moderator-header')
                    @else
                        @include('components.navigation.sidemenu-v2.headers.candidate-header')
                    @endif
                </ul>
            </div>

            <!-- Right: Actions Container -->
            <div class="flex items-center gap-2">
                <div id="v2-right-actions-container-desktop" class="hidden lg:flex flex-shrink-0 items-center">
                    @include('layouts.components.nav-top-actions')
                </div>
                <div id="v2-right-actions-container-mobile" class="flex-shrink-0 flex items-center"></div>
                <!-- Mobile Menu Toggle -->
                <button type="button" id="v2-mobile-menu-toggle"
                    class="lg:hidden text-defaulttextcolor hover:text-primary dark:text-gray-200 p-2 ml-1">
                    <i class="fe fe-menu text-xl"></i>
                </button>
            </div>

        </div>
    </nav>
</div>

<script>
    function syncHeaderActions() {
        const html = document.documentElement;
        const menuVersion = html.getAttribute('data-menu-version') || window.databaseLayoutSettings?.menu_version || 'v1';
        const isHorizontal = html.getAttribute('data-nav-layout') === 'horizontal' || menuVersion === 'v2';
        const isDesktop = window.innerWidth >= 1024;
        const headerRoot = document.getElementById('top-header-menu');
        const rightActions = headerRoot ? headerRoot.querySelector('.header-content-right') : null;
        const v2DesktopContainer = headerRoot ? headerRoot.querySelector('#v2-right-actions-container-desktop') : null;
        const v2MobileContainer = headerRoot ? headerRoot.querySelector('#v2-right-actions-container-mobile') : null;

        if (!rightActions || !isHorizontal) return;

        const targetContainer = isDesktop
            ? (v2DesktopContainer || v2MobileContainer)
            : (v2MobileContainer || v2DesktopContainer);

        if (targetContainer && !targetContainer.contains(rightActions)) {
            rightActions.classList.add('flex', 'items-center', 'gap-2');
            targetContainer.appendChild(rightActions);
        }
    }

    window.syncHeaderActions = syncHeaderActions;

    document.addEventListener('DOMContentLoaded', () => {
        syncHeaderActions();

        // Ensure actions move between desktop/mobile wrappers on resize
        window.addEventListener('resize', syncHeaderActions);

        // Mobile Menu Toggle Logic
        const topHeaderMenu = document.getElementById('top-header-menu');
        const topHeaderMenuToggle = document.getElementById('v2-mobile-menu-toggle');
        const v2NavContainer = document.getElementById('v2-nav-menu-container');

        const closeMobileNavMenu = () => {
            if (!v2NavContainer) {
                return;
            }

            if (window.innerWidth >= 1024) {
                return;
            }

            v2NavContainer.classList.add('hidden');
            v2NavContainer.classList.remove('flex');
            delete v2NavContainer.dataset.mobileOpen;
        };

        if (topHeaderMenuToggle && v2NavContainer) {
            topHeaderMenuToggle.addEventListener('click', function () {
                this.blur();
                v2NavContainer.classList.toggle('hidden');
                v2NavContainer.classList.toggle('flex');

                // Track mobile open state to survive window resizes
                if (v2NavContainer.classList.contains('hidden')) {
                    delete v2NavContainer.dataset.mobileOpen;
                } else {
                    v2NavContainer.dataset.mobileOpen = 'true';
                }
            });
        }

        // Scoped dropdown toggles for V2 header actions (notification/profile)
        const v2Dropdowns = topHeaderMenu
            ? Array.from(topHeaderMenu.querySelectorAll('.hs-dropdown'))
            : [];

        const openDropdown = (wrapper, menu, trigger) => {
            wrapper.classList.add('hs-dropdown-open');
            menu.classList.remove('hidden');
            menu.classList.add('hs-dropdown-open');
            trigger?.setAttribute('aria-expanded', 'true');
        };

        const closeDropdown = (wrapper, menu, trigger) => {
            wrapper.classList.remove('hs-dropdown-open');
            menu.classList.add('hidden');
            menu.classList.remove('hs-dropdown-open');
            trigger?.setAttribute('aria-expanded', 'false');
        };

        v2Dropdowns.forEach((wrapper) => {
            const trigger = wrapper.querySelector('[data-bs-toggle="dropdown"]');
            const menu = wrapper.querySelector('.main-header-dropdown');

            if (!trigger || !menu || trigger.dataset.v2DropdownBound === 'true') {
                return;
            }

            trigger.dataset.v2DropdownBound = 'true';
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const isOpen = wrapper.classList.contains('hs-dropdown-open') || !menu.classList.contains('hidden');

                v2Dropdowns.forEach(otherWrapper => {
                    if (otherWrapper === wrapper) {
                        return;
                    }

                    const otherTrigger = otherWrapper.querySelector('[data-bs-toggle="dropdown"]');
                    const otherMenu = otherWrapper.querySelector('.main-header-dropdown');
                    if (otherMenu) {
                        closeDropdown(otherWrapper, otherMenu, otherTrigger);
                    }
                });

                if (isOpen) {
                    closeDropdown(wrapper, menu, trigger);
                } else {
                    openDropdown(wrapper, menu, trigger);
                }
            });
        });

        const handleOutsideInteraction = (e) => {
            if (topHeaderMenuToggle && v2NavContainer) {
                const clickedInsideNav = v2NavContainer.contains(e.target);
                const clickedToggle = topHeaderMenuToggle.contains(e.target);
                if (!clickedInsideNav && !clickedToggle) {
                    closeMobileNavMenu();
                }
            }

            const clickedInsideDropdownArea = !!e.target.closest('#top-header-menu .hs-dropdown, #top-header-menu .main-header-dropdown, #top-header-menu [data-bs-toggle="dropdown"]');

            if (!clickedInsideDropdownArea && topHeaderMenu) {
                topHeaderMenu.querySelectorAll('.hs-dropdown').forEach((wrapper) => {
                    const trigger = wrapper.querySelector('[data-bs-toggle="dropdown"]');
                    const menu = wrapper.querySelector('.main-header-dropdown');
                    if (menu) {
                        closeDropdown(wrapper, menu, trigger);
                    }
                });
            }
        };

        // Capture-phase pointer handlers are more reliable on mobile than click bubbling.
        document.addEventListener('pointerdown', handleOutsideInteraction, true);
        document.addEventListener('touchstart', handleOutsideInteraction, true);
        document.addEventListener('click', handleOutsideInteraction);

        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') {
                return;
            }

            closeMobileNavMenu();
            v2Dropdowns.forEach((wrapper) => {
                const trigger = wrapper.querySelector('[data-bs-toggle="dropdown"]');
                const menu = wrapper.querySelector('.main-header-dropdown');
                if (menu) {
                    closeDropdown(wrapper, menu, trigger);
                }
            });
        });
    });
</script>


<style>
    /* Branding visibility - scoped to v2 header only */
    #top-header-menu .desktop-branding {
        display: none;
    }

    #top-header-menu .mobile-branding {
        display: flex;
        align-items: center;
    }

    @media (min-width: 640px) {
        #top-header-menu .desktop-branding {
            display: flex;
            align-items: center;
        }

        #top-header-menu .mobile-branding {
            display: none;
        }
    }

    /* Group Container - Position Reference for Dropdowns */
    #top-header-menu .group {
        position: relative;
    }

    /* Dropdown Menu Base State */
    #top-header-menu .header-dropdown-menu {
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.2s ease-in-out;
        pointer-events: none;
        z-index: 1000;
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 200px;
    }

    /* Mobile responsive accordion mode */
    @media (max-width: 1023px) {
        #top-header-menu .header-dropdown-menu {
            position: static;
            width: 100%;
            box-shadow: none;
            border: none;
            padding-left: 1rem;
            padding-right: 1rem;
            transform: translateY(0);
            display: none;
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            margin-top: 0.5rem;
        }

        #top-header-menu .group.open .header-dropdown-menu {
            display: block;
        }
    }

    /* Show dropdown on hover, focus, or click (.open class) */
    #top-header-menu .group:hover .header-dropdown-menu,
    #top-header-menu .group:focus-within .header-dropdown-menu,
    #top-header-menu .group.open .header-dropdown-menu,
    #top-header-menu .group.hover .header-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
    }

    /* Visual feedback for open dropdown (click state) */
    #top-header-menu .group.open>a {
        background-color: rgba(0, 0, 0, 0.05);
    }

    html.dark #top-header-menu .group.open>a {
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* Hide standard sidebar when V2 Menu is active */
    html[data-menu-version="v2"] .app-sidebar,
    html[data-nav-layout="horizontal"] .app-sidebar {
        display: none !important;
    }

    /* Hide standard main top header when V2 Menu is active to merge everything into one row */
    html[data-menu-version="v2"] .app-header,
    html[data-nav-layout="horizontal"] .app-header {
        display: none !important;
    }

    /* Show Top Header Menu only on mobile when V2 Menu is active */
    html[data-menu-version="v2"] #top-header-menu,
    html[data-nav-layout="horizontal"] #top-header-menu {
        display: none !important;
    }

    @media (max-width: 1023px) {

        html[data-menu-version="v2"] #top-header-menu,
        html[data-nav-layout="horizontal"] #top-header-menu {
            display: block !important;
        }
    }

    /* Adjust main content margin when Top Header Menu is active */
    html[data-menu-version="v2"] .app-content,
    html[data-menu-version="v2"] .main-content {
        margin-left: 0 !important;
        margin-inline-start: 0 !important;
    }

    /* Adjust header width when Top Header Menu is active */
    html[data-menu-version="v2"] .app-header {
        margin-left: 0 !important;
        margin-inline-start: 0 !important;
        width: 100% !important;
    }

    /* Target mobile menu specifically to ensure 100% width accordion items */
    #top-header-menu .group {
        width: 100%;
    }

    #top-header-menu .group>a {
        justify-content: space-between;
        width: 100%;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const html = document.documentElement;

        // Setup dropdown behavior with click and hover support
        const topHeaderMenu = document.getElementById('top-header-menu');

        if (topHeaderMenu) {
            const groups = topHeaderMenu.querySelectorAll('.group');

            groups.forEach(group => {
                const link = group.querySelector('a');

                // Click handler: toggle the dropdown
                link?.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Toggle open class
                    const isOpen = group.classList.toggle('open');

                    // Close other dropdowns
                    groups.forEach(otherGroup => {
                        if (otherGroup !== group) {
                            otherGroup.classList.remove('open');
                        }
                    });
                });

                // Mouse enter: show dropdown (unless open was set by click)
                group.addEventListener('mouseenter', function () {
                    if (!group.classList.contains('open')) {
                        group.classList.add('hover');
                    }
                });

                // Mouse leave: hide hover state
                group.addEventListener('mouseleave', function () {
                    group.classList.remove('hover');
                    // Only close if it wasn't opened by click
                    if (!group.classList.contains('open')) {
                        // dropdown is already hidden by CSS
                    }
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function (e) {
                if (!topHeaderMenu.contains(e.target)) {
                    groups.forEach(group => {
                        group.classList.remove('open');
                    });
                }
            });
        }

        // 1. Unified Sync Function
        function syncSwitcherUI() {
            const setChecked = (el) => {
                if (el && !el.checked) {
                    el.checked = true;
                }
            };

            // Theme Version (V1/V2)
            const menuVersion = html.getAttribute('data-menu-version') || window.databaseLayoutSettings?.menu_version || 'v1';
            setChecked(document.getElementById(`switcher-menu-${menuVersion}`));

            // Theme Color Mode
            const isDark = html.classList.contains('dark');
            setChecked(document.getElementById(isDark ? 'switcher-dark-theme' : 'switcher-light-theme'));

            // Layout Width
            const isBoxed = html.getAttribute('data-width') === 'boxed';
            setChecked(document.getElementById(isBoxed ? 'switcher-boxed' : 'switcher-full-width'));

            // Sidemenu Layout Styles
            const verticalStyle = localStorage.getItem('xyntraverticalstyles') || html.getAttribute('data-vertical-style') || window.databaseLayoutSettings?.vertical_style || 'default';
            setChecked(document.getElementById(`switcher-${verticalStyle}-menu`) || document.getElementById(`switcher-${verticalStyle}`));

            // Navigation Menu Style
            // Do not force a fallback nav style when vertical style is active,
            // otherwise theme sync can clear the current sidemenu layout.
            const navStyle = localStorage.getItem('xyntranavstyles') || html.getAttribute('data-nav-style') || window.databaseLayoutSettings?.nav_style || null;
            if (navStyle && !localStorage.getItem('xyntraverticalstyles') && !html.getAttribute('data-vertical-style')) {
                setChecked(document.getElementById(`switcher-${navStyle}`));
            }

            // Page Styles
            const pageStyle = html.getAttribute('data-page-style') || window.databaseLayoutSettings?.page_style || 'regular';
            setChecked(document.getElementById(`switcher-${pageStyle}`));

            // Positions (Menu & Header)
            const menuPos = html.getAttribute('data-menu-position') === 'scrollable' ? 'scroll' : 'fixed';
            setChecked(document.getElementById(`switcher-menu-${menuPos}`));

            const headerPos = html.getAttribute('data-header-position') === 'scrollable' ? 'scroll' : 'fixed';
            setChecked(document.getElementById(`switcher-header-${headerPos}`));

            // Header & Menu Styles
            const headerStyle = html.getAttribute('data-header-styles') || 'light';
            setChecked(document.getElementById(`switcher-header-${headerStyle}`));

            const menuStyle = html.getAttribute('data-menu-styles') || 'dark';
            setChecked(document.getElementById(`switcher-menu-${menuStyle}`));

            // Loader
            const loaderEnabled = html.getAttribute('loader') === 'enable';
            setChecked(document.getElementById(loaderEnabled ? 'switcher-loader-enable' : 'switcher-loader-disable'));

            // Initial visibility of Navigation Menu Style section
            updateNavStyleVisibility();
        }

        // 2. Attach Listeners for All Groups

        // Update visibility of Navigation Menu Style section based on Vertical/Horizontal
        function updateNavStyleVisibility() {
            const version = html.getAttribute('data-menu-version') || window.databaseLayoutSettings?.menu_version || 'v1';
            const navSection = document.getElementById('navigation-menu-style-section');
            if (navSection) {
                navSection.style.display = (version === 'v2') ? 'none' : 'block';
            }
        }

        // Version
        ['v1', 'v2'].forEach(v => {
            const el = document.getElementById(`switcher-menu-${v}`);
            if (el) el.addEventListener('change', () => {
                if (el.checked) {
                    html.setAttribute('data-menu-version', v);

                    if (v === 'v2') {
                        html.setAttribute('data-nav-layout', 'horizontal');
                    } else {
                        html.setAttribute('data-nav-layout', 'vertical');
                        html.removeAttribute('data-toggled'); // Reset sidebar state to expanded
                    }

                    if (typeof window.persistLayoutSettings === 'function') {
                        window.persistLayoutSettings({
                            menu_version: v,
                            nav_layout: v === 'v2' ? 'horizontal' : 'vertical',
                        });
                    }

                    updateNavStyleVisibility(); // Toggle visibility
                    syncHeaderActions();
                }
            });
        });

        // Width
        const fullWidthEl = document.getElementById('switcher-full-width');
        const boxedEl = document.getElementById('switcher-boxed');
        if (fullWidthEl) fullWidthEl.addEventListener('change', () => {
            if (fullWidthEl.checked) {
                html.setAttribute('data-width', 'fullwidth');
                if (typeof window.persistLayoutSettings === 'function') {
                    window.persistLayoutSettings({ width: 'fullwidth' });
                }
            }
        });
        if (boxedEl) boxedEl.addEventListener('change', () => {
            if (boxedEl.checked) {
                html.setAttribute('data-width', 'boxed');
                if (typeof window.persistLayoutSettings === 'function') {
                    window.persistLayoutSettings({ width: 'boxed' });
                }
            }
        });

        // Theme Mode
        const lightThemeEl = document.getElementById('switcher-light-theme');
        const darkThemeEl = document.getElementById('switcher-dark-theme');
        if (lightThemeEl) lightThemeEl.addEventListener('change', () => {
            if (lightThemeEl.checked) {
                html.classList.add('light');
                html.classList.remove('dark');
                html.setAttribute('data-header-styles', 'light');
                html.setAttribute('data-menu-styles', 'light');

                if (typeof window.persistThemeModePreference === 'function') {
                    window.persistThemeModePreference('light');
                }
            }
        });
        if (darkThemeEl) darkThemeEl.addEventListener('change', () => {
            if (darkThemeEl.checked) {
                html.classList.add('dark');
                html.classList.remove('light');
                html.setAttribute('data-header-styles', 'dark');
                html.setAttribute('data-menu-styles', 'dark');

                if (typeof window.persistThemeModePreference === 'function') {
                    window.persistThemeModePreference('dark');
                }
            }
        });

        // Navigation styles
        const navStyleToggles = {
            'menu-click': '',
            'menu-hover': 'menu-hover-closed',
            'icon-click': 'icon-click-closed',
            'icon-hover': 'icon-hover-closed'
        };

        Object.keys(navStyleToggles).forEach(style => {
            const el = document.getElementById(`switcher-${style}`);
            const toggledValue = navStyleToggles[style];

            if (el) el.addEventListener('change', () => {
                if (el.checked) {
                    html.setAttribute('data-nav-layout', 'vertical');
                    html.setAttribute('data-nav-style', style);
                    html.removeAttribute('data-vertical-style');
                    localStorage.setItem('xyntranavstyles', style);
                    localStorage.removeItem('xyntraverticalstyles');

                    if (toggledValue) {
                        html.setAttribute('data-toggled', toggledValue);
                    } else {
                        html.removeAttribute('data-toggled');
                    }

                    if (typeof window.persistLayoutSettings === 'function') {
                        window.persistLayoutSettings({
                            nav_layout: 'vertical',
                            nav_style: style,
                        });
                    }

                    // Deselect vertical style radios
                    Object.keys(verticalStyleToggles).forEach(vKey => {
                        const vEl = document.getElementById(`switcher-${vKey}-menu`) || document.getElementById(`switcher-${vKey}`);
                        if (vEl) vEl.checked = false;
                    });
                    const defaultMenu = document.getElementById('switcher-default-menu');
                    if (defaultMenu) defaultMenu.checked = true;
                }
            });
        });

        // Sidemenu Layout styles
        const verticalStyleToggles = {
            'default': '',
            'closed': 'close-menu-close',
            'icontext': 'icon-text-close',
            'overlay': 'icon-overlay-close',
            'detached': 'detached-close',
            'doublemenu': 'double-menu-open'
        };

        Object.keys(verticalStyleToggles).forEach(vStyle => {
            const el = document.getElementById(`switcher-${vStyle}-menu`) || document.getElementById(`switcher-${vStyle}`);
            const toggledValue = verticalStyleToggles[vStyle];

            if (el) el.addEventListener('change', () => {
                if (el.checked) {
                    html.setAttribute('data-nav-layout', 'vertical');
                    html.setAttribute('data-vertical-style', vStyle);
                    localStorage.setItem('xyntraverticalstyles', vStyle);
                    localStorage.removeItem('xyntranavstyles');

                    if (vStyle === 'doublemenu') {
                        html.setAttribute('data-menu-styles', html.classList.contains('dark') ? 'dark' : 'light');
                    }

                    if (toggledValue) {
                        html.setAttribute('data-toggled', toggledValue);
                    } else {
                        html.removeAttribute('data-toggled');
                    }

                    html.removeAttribute('data-nav-style');

                    if (typeof window.persistLayoutSettings === 'function') {
                        window.persistLayoutSettings({
                            nav_layout: 'vertical',
                            vertical_style: vStyle,
                        });
                    }

                    if (vStyle === 'doublemenu' && typeof window.persistThemePreference === 'function') {
                        window.persistThemePreference({
                            theme_mode: html.classList.contains('dark') ? 'dark' : 'light',
                            theme_styles: {
                                menu_style: html.classList.contains('dark') ? 'dark' : 'light',
                            },
                        });
                    }

                    // Deselect nav style radios
                    ['menu-click', 'menu-hover', 'icon-click', 'icon-hover'].forEach(nStyle => {
                        const nEl = document.getElementById(`switcher-${nStyle}`);
                        if (nEl) nEl.checked = false;
                    });

                    // Double menu needs specific checkHoriMenu to initialize tooltips
                    if (vStyle === 'doublemenu' && typeof checkHoriMenu === 'function') {
                        checkHoriMenu();
                    }
                }
            });
        });

        // Page Styles
        ['regular', 'classic', 'modern'].forEach(style => {
            const el = document.getElementById(`switcher-${style}`);
            if (el) el.addEventListener('change', () => {
                if (el.checked) {
                    if (style === 'regular') {
                        html.removeAttribute('data-page-style');
                    } else {
                        html.setAttribute('data-page-style', style);
                    }

                    if (typeof window.persistLayoutSettings === 'function') {
                        window.persistLayoutSettings({ page_style: style });
                    }
                }
            });
        });

        // Positions
        const menuFixed = document.getElementById('switcher-menu-fixed');
        const menuScroll = document.getElementById('switcher-menu-scroll');
        if (menuFixed) menuFixed.addEventListener('change', () => {
            if (menuFixed.checked) {
                html.setAttribute('data-menu-position', 'fixed');
                if (typeof window.persistLayoutSettings === 'function') {
                    window.persistLayoutSettings({ menu_position: 'fixed' });
                }
            }
        });
        if (menuScroll) menuScroll.addEventListener('change', () => {
            if (menuScroll.checked) {
                html.setAttribute('data-menu-position', 'scrollable');
                if (typeof window.persistLayoutSettings === 'function') {
                    window.persistLayoutSettings({ menu_position: 'scrollable' });
                }
            }
        });

        const headerFixed = document.getElementById('switcher-header-fixed');
        const headerScroll = document.getElementById('switcher-header-scroll');
        if (headerFixed) headerFixed.addEventListener('change', () => {
            if (headerFixed.checked) {
                html.setAttribute('data-header-position', 'fixed');
                if (typeof window.persistLayoutSettings === 'function') {
                    window.persistLayoutSettings({ header_position: 'fixed' });
                }
            }
        });
        if (headerScroll) headerScroll.addEventListener('change', () => {
            if (headerScroll.checked) {
                html.setAttribute('data-header-position', 'scrollable');
                if (typeof window.persistLayoutSettings === 'function') {
                    window.persistLayoutSettings({ header_position: 'scrollable' });
                }
            }
        });

        // Loader
        const loaderEnable = document.getElementById('switcher-loader-enable');
        const loaderDisable = document.getElementById('switcher-loader-disable');
        if (loaderEnable) loaderEnable.addEventListener('change', () => {
            if (loaderEnable.checked) {
                html.setAttribute('loader', 'enable');
                if (typeof window.persistLayoutSettings === 'function') {
                    window.persistLayoutSettings({ loader: 'enable' });
                }
            }
        });
        if (loaderDisable) loaderDisable.addEventListener('change', () => {
            if (loaderDisable.checked) {
                html.setAttribute('loader', 'disable');
                if (typeof window.persistLayoutSettings === 'function') {
                    window.persistLayoutSettings({ loader: 'disable' });
                }
            }
        });

        // Header Colors (Mapping primary to color)
        ['light', 'primary', 'gradient', 'transparent'].forEach(style => {
            const el = document.getElementById(`switcher-header-${style}`);
            if (el) el.addEventListener('change', () => {
                if (el.checked) {
                    const val = style === 'primary' ? 'color' : style;
                    html.setAttribute('data-header-styles', val);

                    if (typeof window.persistLayoutSettings === 'function') {
                        window.persistLayoutSettings({ header_style: val });
                    }

                    if (typeof window.persistThemePreference === 'function') {
                        window.persistThemePreference({
                            theme_mode: typeof window.resolveCurrentThemeMode === 'function' ? window.resolveCurrentThemeMode() : (html.classList.contains('dark') ? 'dark' : 'light'),
                            theme_styles: {
                                header_style: val,
                            },
                        });
                    }
                }
            });
        });

        // Menu Colors (Mapping primary to color)
        ['light', 'primary', 'gradient', 'transparent'].forEach(style => {
            const el = document.getElementById(`switcher-menu-${style}`);
            if (el) el.addEventListener('change', () => {
                if (el.checked) {
                    const val = style === 'primary' ? 'color' : style;
                    html.setAttribute('data-menu-styles', val);

                    if (typeof window.persistLayoutSettings === 'function') {
                        window.persistLayoutSettings({ menu_style: val });
                    }

                    if (typeof window.persistThemePreference === 'function') {
                        window.persistThemePreference({
                            theme_mode: typeof window.resolveCurrentThemeMode === 'function' ? window.resolveCurrentThemeMode() : (html.classList.contains('dark') ? 'dark' : 'light'),
                            theme_styles: {
                                menu_style: val,
                            },
                        });
                    }
                }
            });
        });

        // Initial UI sync
        syncSwitcherUI();

        // Listen for theme changes from other toggles (e.g. nav-top layout)
        window.addEventListener('xyntra-theme-changed', () => {
            syncSwitcherUI();
        });

        // Handle Reset All
        const resetButtons = [
            ...document.querySelectorAll('[data-reset-all="true"]'),
            ...document.querySelectorAll('#reset-all'),
        ];

        resetButtons.forEach((resetBtn) => {
            resetBtn.addEventListener('click', async function () {
                console.log('Resetting all layout keys...');
                // Revert root to defaults
                html.setAttribute('data-menu-version', 'v2');
                html.setAttribute('data-nav-layout', 'vertical');
                html.setAttribute('data-nav-style', 'menu-click');
                html.setAttribute('data-vertical-style', 'default');
                html.setAttribute('data-width', 'fullwidth');
                html.classList.add('light');
                html.classList.remove('dark');
                html.removeAttribute('data-toggled');
                html.setAttribute('data-header-styles', 'light');
                html.setAttribute('data-menu-styles', 'light');

                if (typeof window.resetUserThemePreference === 'function') {
                    try {
                        await window.resetUserThemePreference();
                    } catch (error) {
                        console.warn('Failed to reset theme preference.', error);
                    }
                }

                syncSwitcherUI();
                syncHeaderActions();

                setTimeout(() => window.location.reload(), 200);
            });
        });
    });
</script>