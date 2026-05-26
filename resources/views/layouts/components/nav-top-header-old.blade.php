@php
    $user = Auth::user();
@endphp
<script>
    // Keep layout attributes in sync as early as possible.
    // This prevents the V1 sidebar from briefly transforming open while v2/top-header is active.
    (function () {
        try {
            const v = localStorage.getItem('menu-version-style');
            if (!v) return;
            document.documentElement.setAttribute('data-menu-version', v);
            document.documentElement.setAttribute('data-nav-layout', v === 'v2' ? 'horizontal' : 'vertical');
        } catch (e) {
            // Ignore storage/read errors and let default behavior take over.
        }
    })();
</script>
<div id="top-header-menu-desktop" class="hidden">
    <nav class="w-full bg-white dark:bg-bodybg border-b border-defaultborder dark:border-white/10 px-4 py-1.5 md:px-8 shadow-sm relative z-[110]"
        style="overflow: visible;">
        <div class="flex items-center justify-between max-w-[100rem] mx-auto w-full" style="overflow: visible;">

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

            <!-- Center: Navigation Menus -->
            <ul class="flex flex-row flex-wrap items-center gap-2 lg:gap-5 justify-center flex-1">
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

            <!-- Right: Actions Container -->
            <div id="v2-right-actions-container-desktop" class="flex-shrink-0 flex items-center">
                @include('layouts.components.nav-top-actions')
            </div>

        </div>
    </nav>
</div>


<script>
    function syncHeaderActions() {
        const html = document.documentElement;
        const menuVersion = html.getAttribute('data-menu-version') || localStorage.getItem('menu-version-style') || 'v1';
        const isHorizontal = html.getAttribute('data-nav-layout') === 'horizontal' || menuVersion === 'v2';
        const isDesktop = window.innerWidth >= 1024;
        const headerRoot = document.getElementById('top-header-menu-desktop');
        const rightActions = headerRoot ? headerRoot.querySelector('.header-content-right') : null;
        const v2DesktopContainer = headerRoot ? headerRoot.querySelector('#v2-right-actions-container-desktop') : null;

        if (!rightActions || !isHorizontal || !isDesktop) {
            return;
        }

        if (v2DesktopContainer && !v2DesktopContainer.contains(rightActions)) {
            rightActions.classList.add('flex', 'items-center', 'gap-2');
            rightActions.style.display = 'flex';
            v2DesktopContainer.appendChild(rightActions);
        }
    }

    window.syncHeaderActions = syncHeaderActions;

    document.addEventListener('DOMContentLoaded', function () {
        syncHeaderActions();
        window.addEventListener('resize', syncHeaderActions);
    });
</script>




<style>
    /* Group Container - Position Reference for Dropdowns */
    #top-header-menu-desktop .group {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    /* Dropdown Menu Base State */
    #top-header-menu-desktop .header-dropdown-menu {
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

    /* Show dropdown on hover, focus, or click (.open class) */
    #top-header-menu-desktop .group:hover .header-dropdown-menu,
    #top-header-menu-desktop .group:focus-within .header-dropdown-menu,
    #top-header-menu-desktop .group.open .header-dropdown-menu,
    #top-header-menu-desktop .group.hover .header-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
    }

    /* Visual feedback for open dropdown (click state) */
    #top-header-menu-desktop .group.open>a {
        background-color: rgba(0, 0, 0, 0.05);
    }

    html.dark #top-header-menu-desktop .group.open>a {
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* Hide standard sidebar when V2 Menu is active */
    html[data-menu-version="v2"] .app-sidebar {
        display: none !important;
    }

    /* Hide standard main top header when V2 Menu is active to merge everything into one row */
    html[data-menu-version="v2"] .app-header {
        display: none !important;
    }

    /* Show Top Header Menu when V2 Menu is active */
    html[data-menu-version="v2"] #top-header-menu-desktop {
        display: block !important;
    }

    @media (max-width: 1023px) {
        html[data-menu-version="v2"] #top-header-menu-desktop {
            display: none !important;
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const html = document.documentElement;

        // Setup dropdown behavior with click and hover support
        const topHeaderMenu = document.getElementById('top-header-menu-desktop');

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

            const closeAllGroups = () => {
                groups.forEach(group => {
                    group.classList.remove('open');
                });
            };

            const actionDropdowns = Array.from(topHeaderMenu.querySelectorAll('.hs-dropdown'));

            const closeActionDropdown = (wrapper) => {
                const trigger = wrapper.querySelector('[data-bs-toggle="dropdown"]');
                const menu = wrapper.querySelector('.main-header-dropdown');
                if (!menu) {
                    return;
                }

                wrapper.classList.remove('hs-dropdown-open');
                menu.classList.add('hidden');
                menu.classList.remove('hs-dropdown-open');
                trigger?.setAttribute('aria-expanded', 'false');
            };

            actionDropdowns.forEach((wrapper) => {
                const trigger = wrapper.querySelector('[data-bs-toggle="dropdown"]');
                const menu = wrapper.querySelector('.main-header-dropdown');

                if (!trigger || !menu || trigger.dataset.desktopDropdownBound === 'true') {
                    return;
                }

                trigger.dataset.desktopDropdownBound = 'true';

                trigger.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const isOpen = wrapper.classList.contains('hs-dropdown-open') || !menu.classList.contains('hidden');

                    actionDropdowns.forEach(otherWrapper => {
                        if (otherWrapper !== wrapper) {
                            closeActionDropdown(otherWrapper);
                        }
                    });

                    if (isOpen) {
                        closeActionDropdown(wrapper);
                    } else {
                        wrapper.classList.add('hs-dropdown-open');
                        menu.classList.remove('hidden');
                        menu.classList.add('hs-dropdown-open');
                        trigger.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            const handleOutsideInteraction = (e) => {
                if (!topHeaderMenu.contains(e.target)) {
                    closeAllGroups();
                    actionDropdowns.forEach(closeActionDropdown);
                    return;
                }

                const clickedInsideActionDropdown = !!e.target.closest('#top-header-menu-desktop .hs-dropdown, #top-header-menu-desktop .main-header-dropdown, #top-header-menu-desktop [data-bs-toggle="dropdown"]');
                if (!clickedInsideActionDropdown) {
                    actionDropdowns.forEach(closeActionDropdown);
                }
            };

            // Capture-phase pointer/touch handlers are more reliable than click bubbling.
            document.addEventListener('pointerdown', handleOutsideInteraction, true);
            document.addEventListener('touchstart', handleOutsideInteraction, true);
            document.addEventListener('click', handleOutsideInteraction);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    closeAllGroups();
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
            const menuVersion = localStorage.getItem('menu-version-style') || 'v1';
            setChecked(document.getElementById(`switcher-menu-${menuVersion}`));

            // Theme Color Mode
            const isDark = html.classList.contains('dark') || localStorage.getItem('xyntradarktheme');
            setChecked(document.getElementById(isDark ? 'switcher-dark-theme' : 'switcher-light-theme'));

            // Layout Width
            const isBoxed = html.getAttribute('data-width') === 'boxed' || localStorage.getItem('xyntraboxed');
            setChecked(document.getElementById(isBoxed ? 'switcher-boxed' : 'switcher-full-width'));

            // Sidemenu Layout Styles
            const verticalStyle = localStorage.getItem('xyntraverticalstyles') || html.getAttribute('data-vertical-style') || 'default';
            setChecked(document.getElementById(`switcher-${verticalStyle}-menu`) || document.getElementById(`switcher-${verticalStyle}`));

            // Navigation Menu Style
            // Do not force a fallback nav style when vertical style is active,
            // otherwise theme sync can clear the current sidemenu layout.
            const navStyle = html.getAttribute('data-nav-style') || localStorage.getItem('xyntranavstyles') || null;
            if (navStyle && !localStorage.getItem('xyntraverticalstyles') && !html.getAttribute('data-vertical-style')) {
                setChecked(document.getElementById(`switcher-${navStyle}`));
            }

            // Page Styles
            const pageStyle = html.getAttribute('data-page-style') || (localStorage.getItem('xyntraclassic') ? 'classic' : (localStorage.getItem('xyntramodern') ? 'modern' : 'regular'));
            setChecked(document.getElementById(`switcher-${pageStyle}`));

            // Positions (Menu & Header)
            const menuPos = html.getAttribute('data-menu-position') === 'scrollable' || localStorage.getItem('xyntramenuscrollable') ? 'scroll' : 'fixed';
            setChecked(document.getElementById(`switcher-menu-${menuPos}`));

            const headerPos = html.getAttribute('data-header-position') === 'scrollable' || localStorage.getItem('xyntraheaderscrollable') ? 'scroll' : 'fixed';
            setChecked(document.getElementById(`switcher-header-${headerPos}`));

            // Header & Menu Styles
            const headerStyle = html.getAttribute('data-header-styles') || localStorage.getItem('xyntraHeader') || 'light';
            setChecked(document.getElementById(`switcher-header-${headerStyle}`));

            const menuStyle = html.getAttribute('data-menu-styles') || localStorage.getItem('xyntraMenu') || 'dark';
            setChecked(document.getElementById(`switcher-menu-${menuStyle}`));

            // Loader
            const loaderEnabled = html.getAttribute('loader') === 'enable' || localStorage.getItem('loaderEnable') === 'true';
            setChecked(document.getElementById(loaderEnabled ? 'switcher-loader-enable' : 'switcher-loader-disable'));

            // Initial visibility of Navigation Menu Style section
            updateNavStyleVisibility();
        }

        // 2. Attach Listeners for All Groups

        // Update visibility of Navigation Menu Style section based on Vertical/Horizontal
        function updateNavStyleVisibility() {
            const version = localStorage.getItem('menu-version-style') || 'v1';
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
                    localStorage.setItem('menu-version-style', v);

                    if (v === 'v2') {
                        html.setAttribute('data-nav-layout', 'horizontal');
                        localStorage.setItem('xyntralayout', 'horizontal');
                    } else {
                        html.setAttribute('data-nav-layout', 'vertical');
                        localStorage.removeItem('xyntralayout');
                        html.removeAttribute('data-toggled'); // Reset sidebar state to expanded
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
                localStorage.setItem('xyntrafullwidth', 'true');
                localStorage.removeItem('xyntraboxed');
            }
        });
        if (boxedEl) boxedEl.addEventListener('change', () => {
            if (boxedEl.checked) {
                html.setAttribute('data-width', 'boxed');
                localStorage.setItem('xyntraboxed', 'true');
                localStorage.removeItem('xyntrafullwidth');
            }
        });

        // Theme Mode
        const lightThemeEl = document.getElementById('switcher-light-theme');
        const darkThemeEl = document.getElementById('switcher-dark-theme');
        if (lightThemeEl) lightThemeEl.addEventListener('change', () => {
            if (lightThemeEl.checked) {
                html.classList.add('light');
                html.classList.remove('dark');
                localStorage.removeItem('xyntradarktheme');
                localStorage.removeItem('layout-theme');
            }
        });
        if (darkThemeEl) darkThemeEl.addEventListener('change', () => {
            if (darkThemeEl.checked) {
                html.classList.add('dark');
                html.classList.remove('light');
                localStorage.setItem('xyntradarktheme', 'true');
                localStorage.setItem('layout-theme', 'dark');
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
                    localStorage.setItem('xyntranavstyles', style);
                    html.removeAttribute('data-vertical-style');
                    localStorage.removeItem('xyntraverticalstyles');
                    localStorage.removeItem('xyntralayout');

                    if (toggledValue) {
                        html.setAttribute('data-toggled', toggledValue);
                    } else {
                        html.removeAttribute('data-toggled');
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
                    localStorage.removeItem('xyntralayout');

                    if (vStyle === 'doublemenu') {
                        const syncedMenuStyle = html.classList.contains('dark') ? 'dark' : 'light';
                        html.setAttribute('data-menu-styles', syncedMenuStyle);
                        localStorage.setItem('xyntraMenu', syncedMenuStyle);
                    }

                    if (toggledValue) {
                        html.setAttribute('data-toggled', toggledValue);
                    } else {
                        html.removeAttribute('data-toggled');
                    }

                    html.removeAttribute('data-nav-style');
                    localStorage.removeItem('xyntranavstyles');

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
                        localStorage.removeItem('xyntraclassic');
                        localStorage.removeItem('xyntramodern');
                    } else {
                        html.setAttribute('data-page-style', style);
                        localStorage.setItem(`xyntra${style}`, 'true');
                        localStorage.removeItem(style === 'classic' ? 'xyntramodern' : 'xyntraclassic');
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
                localStorage.setItem('xyntramenufixed', 'true');
                localStorage.removeItem('xyntramenuscrollable');
            }
        });
        if (menuScroll) menuScroll.addEventListener('change', () => {
            if (menuScroll.checked) {
                html.setAttribute('data-menu-position', 'scrollable');
                localStorage.setItem('xyntramenuscrollable', 'true');
                localStorage.removeItem('xyntramenufixed');
            }
        });

        const headerFixed = document.getElementById('switcher-header-fixed');
        const headerScroll = document.getElementById('switcher-header-scroll');
        if (headerFixed) headerFixed.addEventListener('change', () => {
            if (headerFixed.checked) {
                html.setAttribute('data-header-position', 'fixed');
                localStorage.setItem('xyntraheaderfixed', 'true');
                localStorage.removeItem('xyntraheaderscrollable');
            }
        });
        if (headerScroll) headerScroll.addEventListener('change', () => {
            if (headerScroll.checked) {
                html.setAttribute('data-header-position', 'scrollable');
                localStorage.setItem('xyntraheaderscrollable', 'true');
                localStorage.removeItem('xyntraheaderfixed');
            }
        });

        // Loader
        const loaderEnable = document.getElementById('switcher-loader-enable');
        const loaderDisable = document.getElementById('switcher-loader-disable');
        if (loaderEnable) loaderEnable.addEventListener('change', () => {
            if (loaderEnable.checked) {
                html.setAttribute('loader', 'enable');
                localStorage.setItem('loaderEnable', 'true');
            }
        });
        if (loaderDisable) loaderDisable.addEventListener('change', () => {
            if (loaderDisable.checked) {
                html.setAttribute('loader', 'disable');
                localStorage.setItem('loaderEnable', 'false');
            }
        });

        // Header Colors (Mapping primary to color)
        ['light', 'primary', 'gradient', 'transparent'].forEach(style => {
            const el = document.getElementById(`switcher-header-${style}`);
            if (el) el.addEventListener('change', () => {
                if (el.checked) {
                    const val = style === 'primary' ? 'color' : style;
                    html.setAttribute('data-header-styles', val);
                    localStorage.setItem('xyntraHeader', val);
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
                    localStorage.setItem('xyntraMenu', val);
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
        const resetBtn = document.getElementById('reset-all');
        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                console.log('Resetting all layout keys...');
                const keys = [
                    'menu-version-style', 'xyntralayout', 'layout-theme', 'xyntradarktheme',
                    'xyntranavstyles', 'xyntraverticalstyles', 'xyntraboxed', 'xyntrafullwidth',
                    'xyntraclassic', 'xyntramodern', 'xyntraheaderfixed', 'xyntraheaderscrollable',
                    'xyntramenufixed', 'xyntramenuscrollable', 'loaderEnable'
                ];
                keys.forEach(k => localStorage.removeItem(k));

                // Revert root to defaults
                html.setAttribute('data-menu-version', 'v2');
                html.setAttribute('data-nav-layout', 'vertical');
                html.setAttribute('data-nav-style', 'menu-click');
                html.setAttribute('data-vertical-style', 'default');
                html.setAttribute('data-width', 'fullwidth');
                html.classList.add('light');
                html.classList.remove('dark');
                html.removeAttribute('data-toggled');

                syncSwitcherUI();
                syncHeaderActions();

                setTimeout(() => window.location.reload(), 200);
            });
        }
    });
</script>
