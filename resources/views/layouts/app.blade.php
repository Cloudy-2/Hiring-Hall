<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="default" data-width="fullwidth"
    data-header-position="fixed" data-menu-position="fixed" loader="enable">
<script>
    (function () {
        var isAuthenticated = @json(auth()->check());
        var h = document.documentElement;
        var databaseThemeMode = @json(auth()->check() ? optional(auth()->user()->themePreference)->theme_mode : null);
        var databaseThemeStyles = @json(auth()->check() ? optional(auth()->user()->themePreference)->theme_styles : null);
        var databaseLayoutSettings = @json(auth()->check() ? optional(auth()->user()->themePreference)->layout_settings : null);

        if (isAuthenticated) {
            try {
                [
                    'dark-mode',
                    'layout-theme',
                    'xyntradarktheme',
                    'xyntraHeader',
                    'xyntraMenu',
                    'xyntralayout',
                    'menu-version-style',
                    'xyntraboxed',
                    'xyntrafullwidth',
                    'xyntraclassic',
                    'xyntramodern',
                    'xyntraheaderfixed',
                    'xyntraheaderscrollable',
                    'xyntramenufixed',
                    'xyntramenuscrollable',
                    'loaderEnable',
                ].forEach(function (key) {
                    localStorage.removeItem(key);
                });
            } catch (error) {
                // Ignore storage access errors.
            }
        }

        var preferredThemeMode = databaseThemeMode || 'light';
        var d = preferredThemeMode === 'system'
            ? window.matchMedia('(prefers-color-scheme: dark)').matches
            : preferredThemeMode === 'dark';

        if (databaseLayoutSettings) {
            if (databaseLayoutSettings.menu_version) {
                h.setAttribute('data-menu-version', databaseLayoutSettings.menu_version);
            }
            if (databaseLayoutSettings.nav_layout) {
                h.setAttribute('data-nav-layout', databaseLayoutSettings.nav_layout);
            }
            if (databaseLayoutSettings.nav_style) {
                h.setAttribute('data-nav-style', databaseLayoutSettings.nav_style);
            }
            if (databaseLayoutSettings.vertical_style) {
                h.setAttribute('data-vertical-style', databaseLayoutSettings.vertical_style);
            }
            if (databaseLayoutSettings.width) {
                h.setAttribute('data-width', databaseLayoutSettings.width);
            }
            if (databaseLayoutSettings.page_style && databaseLayoutSettings.page_style !== 'regular') {
                h.setAttribute('data-page-style', databaseLayoutSettings.page_style);
            }
            if (databaseLayoutSettings.menu_position) {
                h.setAttribute('data-menu-position', databaseLayoutSettings.menu_position);
            }
            if (databaseLayoutSettings.header_position) {
                h.setAttribute('data-header-position', databaseLayoutSettings.header_position);
            }
            if (databaseLayoutSettings.loader) {
                h.setAttribute('loader', databaseLayoutSettings.loader);
            }
        }

        if (databaseThemeStyles) {
            if (databaseThemeStyles.header_style) {
                h.setAttribute('data-header-styles', databaseThemeStyles.header_style);
            }

            if (databaseThemeStyles.menu_style) {
                h.setAttribute('data-menu-styles', databaseThemeStyles.menu_style);
            }
        }

        if (d) {
            h.classList.remove('light');
            h.classList.add('dark');
            h.setAttribute('data-theme-mode', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
            document.documentElement.setAttribute('data-theme-mode', 'light');
        }

        var preferredHeaderStyle = databaseThemeStyles && databaseThemeStyles.header_style
            ? databaseThemeStyles.header_style
            : (d ? 'dark' : 'light');
        var preferredMenuStyle = databaseThemeStyles && databaseThemeStyles.menu_style
            ? databaseThemeStyles.menu_style
            : (d ? 'dark' : 'light');

        h.setAttribute('data-header-styles', preferredHeaderStyle);
        h.setAttribute('data-menu-styles', preferredMenuStyle);

        if (!databaseLayoutSettings || !databaseLayoutSettings.loader) {
            document.documentElement.setAttribute('loader', 'enable');
        }
    })();
</script>

<head>

    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/assets/raw/hillbcs-logo.png" type="image/png">
    <title>
        @php
            $t = (isset($title) && is_object($title) && method_exists($title, 'isNotEmpty') && $title->isNotEmpty()) ? (string) $title : ($pageTitle ?? '');
            $suffix = 'Hill Business Consulting Services';
        @endphp
        {{ $t ? strip_tags($t) . ' — ' : '' }}{{ $suffix }}
    </title>
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#0ea5e9">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="HillBCS">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/assets/pwa/ios/180.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/pwa/ios/152.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/assets/pwa/ios/167.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/pwa/ios/180.png">

    @include('layouts.components.nav-link')
    @vite(['resources/js/app.js'])
    @stack('styles')

</head>

<body>
    <script>
        const isAuthenticated = @json(auth()->check());
        const databaseThemeMode = @json(auth()->check() ? optional(auth()->user()->themePreference)->theme_mode : null);
        const databaseThemeStyles = @json(auth()->check() ? optional(auth()->user()->themePreference)->theme_styles : null);
        const databaseLayoutSettings = @json(auth()->check() ? optional(auth()->user()->themePreference)->layout_settings : null);
        const themePersistenceUrl = @json(auth()->check() ? route('user.theme.store') : null);

        const resolvedDarkMode = databaseThemeMode === 'system'
            ? window.matchMedia('(prefers-color-scheme: dark)').matches
            : databaseThemeMode === 'dark';

        if (resolvedDarkMode) {
            document.body.classList.add('dark-theme');
            document.documentElement.classList.remove('light');
            document.documentElement.classList.add('dark');
            document.documentElement.setAttribute('data-theme-mode', 'dark');
        } else {
            document.body.classList.remove('dark-theme');
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light');
            document.documentElement.setAttribute('data-theme-mode', 'light');
        }

        const currentVerticalStyle = document.documentElement.getAttribute('data-vertical-style')
            || databaseLayoutSettings?.vertical_style
            || null;

        const preferredHeaderStyle = databaseThemeStyles?.header_style
            || document.documentElement.getAttribute('data-header-styles')
            || (resolvedDarkMode ? 'dark' : 'light');
        let preferredMenuStyle = databaseThemeStyles?.menu_style
            || document.documentElement.getAttribute('data-menu-styles')
            || (resolvedDarkMode ? 'dark' : 'light');

        if (currentVerticalStyle === 'doublemenu') {
            preferredMenuStyle = resolvedDarkMode ? 'dark' : 'light';
        }

        document.documentElement.setAttribute('data-header-styles', preferredHeaderStyle);
        document.documentElement.setAttribute('data-menu-styles', preferredMenuStyle);

        async function persistThemePreference(payload) {
            if (!isAuthenticated || !themePersistenceUrl) {
                return;
            }

            try {
                await fetch(themePersistenceUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                    credentials: 'same-origin',
                });
            } catch (error) {
                console.warn('Failed to persist theme preference.', error);
            }
        }

        async function persistThemeModePreference(themeMode) {
            await persistThemePreference({ theme_mode: themeMode });
        }

        async function persistLayoutSettings(layoutSettings) {
            await persistThemePreference({
                theme_mode: resolveCurrentThemeMode(),
                layout_settings: layoutSettings,
            });
        }

        async function resetUserThemePreference() {
            await persistThemePreference({ reset: true });
        }

        function resolveCurrentThemeMode() {
            return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        }

        window.databaseLayoutSettings = databaseLayoutSettings;
        window.resolveCurrentThemeMode = resolveCurrentThemeMode;
        window.persistThemePreference = persistThemePreference;
        window.persistThemeModePreference = persistThemeModePreference;
        window.persistLayoutSettings = persistLayoutSettings;
        window.resetUserThemePreference = resetUserThemePreference;

        function toggleDarkMode() {
            const isDark = document.body.classList.toggle('dark-theme');
            document.documentElement.classList.toggle('dark', isDark);
            document.documentElement.classList.toggle('light', !isDark);
            document.documentElement.setAttribute('data-theme-mode', isDark ? 'dark' : 'light');

            const currentVerticalStyle = document.documentElement.getAttribute('data-vertical-style');
            if (currentVerticalStyle === 'doublemenu') {
                document.documentElement.setAttribute('data-menu-styles', isDark ? 'dark' : 'light');
            }

            // Dispatch event for UI synchronization (e.g. switcher radios)
            window.dispatchEvent(new CustomEvent('xyntra-theme-changed', { detail: { isDark } }));

            if (currentVerticalStyle === 'doublemenu' && typeof window.persistThemePreference === 'function') {
                persistThemePreference({
                    theme_mode: isDark ? 'dark' : 'light',
                    theme_styles: {
                        menu_style: isDark ? 'dark' : 'light',
                    },
                });
            } else {
                persistThemeModePreference(isDark ? 'dark' : 'light');
            }
        }
    </script>
    <div id="loader" class="desktop-response">
        <center class="flex flex-col items-center space-y-4">
            <div class="relative w-24 h-24 flex items-center justify-center">
                <div class="absolute w-full h-full border-2 border-t-transparent rounded-full animate-spin"
                    style="border-color: rgb(var(--primary)); border-top-color: transparent;">
                </div>
                <img src="/assets/raw/hillbcs-logo.png" alt="Logo" class="h-16 z-10">
            </div>

            <div class="text-center text-gray-700 text-sm">
                Please wait a moment... <br />
                We are securely processing your request. <br />
                Retrieving large files may take a few minutes.
            </div>
        </center>
    </div>

    <div class="page">

        @include('components.permission.impersonate-banner')
        @include('layouts.components.nav-top')
        @include('layouts.components.nav-top-header-old')
        @include('layouts.components.nav-top-header')
        @auth
            @include('components.navigation.sidemenu-v2.index')
        @else
            <style>
                .app-content {
                    margin-inline-start: 0 !important;
                }

                .main-header-container {
                    padding-left: 0 !important;
                }
            </style>
        @endauth

        <div class="main-content app-content">
            <div class="container-fluid">
                @include('layouts.components.breadcrumbs', ['active' => $active ?? null, 'breadcrumbs' => $breadcrumbs ?? []])
                @if (session('success') || session('status'))
                    @include('layouts.partials.success')
                @endif
                @if ($errors->any())
                    <div
                        class="alert alert-danger alert-dismissible fade show custom-alert-icon shadow-sm flex items-center mx-3">
                        <div>
                            <strong class="text-danger">Whoops! Something went wrong:</strong>
                            <ul class="list-disc list-inside mt-2 mx-4">
                                @foreach ($errors->all() as $error)
                                    <li><i>{{ $error }}</i></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                {{ $slot }}



                @include('ai.hill')
                @include('partials.float-ask-hill-ai')
                @include('partials.wt-float-btn')

                <div id="job-toast"
                    class="fixed inset-x-0 top-4 flex justify-center z-50 pointer-events-none hidden opacity-0 transition-opacity duration-300">
                    <div id="job-toast-inner"
                        class="bg-slate-900 text-white text-xs sm:text-sm px-4 py-2 rounded-full shadow-md">
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.components.nav-footer')
    </div>

    {{-- <div class="page">

        @include('components.permission.impersonate-banner')
        @include('layouts.components.nav-top')
        @include('layouts.components.nav-top-header')
        @auth
        @include('components.navigation.sidemenu-v2.index')
        @endauth

        @guest
        <style>
            .app-content {
                margin-inline-start: 0 !important;
            }
        </style>
        @endguest

        <div class="">
            <div class="container-fluid">

                @include('layouts.components.breadcrumbs', ['active' => $active ?? null, 'breadcrumbs' => $breadcrumbs
                ?? []]) --}}



                {{-- <div class="row">
                    <div class="col-xl-12">
                        <div class="box border border-gray dark:border-defaultborder/10">
                            <div class="box-body">

                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- </div>
        </div>

        @include('layouts.components.nav-footer')

    </div> --}}

    <style>
        /* .box {
            box-shadow: none;
        } */

        /* FIX: Override theme's .page flex centering that causes squeeze on scroll */
        .page {
            display: block !important;
            justify-content: flex-start !important;
        }

        /* Root padding for vertical layout to prevent header overlap */
        html[data-nav-layout="vertical"] .app-content {
            padding-top: 3rem !important;
        }

        /* Force fixed header and sidebar - override .sticky class only for vertical layout */
        html[data-nav-layout="vertical"] .app-header,
        html[data-nav-layout="vertical"] .app-header.sticky,
        html[data-nav-layout="vertical"] .app-header.sticky-pin,
        html[data-nav-layout="vertical"] .app-header.sticky.sticky-pin {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 200 !important;
            padding-left: 0 !important;
            padding-inline-start: 0 !important;
        }

        /* On mobile, header is full-width (sidebar is hidden/overlay) */
        @media (max-width: 1024px) {
            html[data-nav-layout="vertical"] .app-header {
                left: 0 !important;
                width: 100% !important;
                padding-left: 0 !important;
            }

            /* Content fills full width on mobile - ensure 64px (h-16) space for fixed header */
            html[data-nav-layout="vertical"] .app-content,
            html[data-nav-layout="vertical"] .main-content.app-content {
                margin-inline-start: 0 !important;
            }
        }

        html[data-nav-layout="vertical"] .main-header-container {
            padding-left: 1rem !important;
            margin-left: 0 !important;
            max-width: 100% !important;
        }

        html[data-nav-layout="vertical"] .app-sidebar,
        html[data-nav-layout="vertical"] .app-sidebar.sticky,
        html[data-nav-layout="vertical"] .app-sidebar.sticky-pin,
        html[data-nav-layout="vertical"] .app-sidebar.sticky.sticky-pin {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            height: 100vh !important;
            z-index: 201 !important;
        }

        /* Sidebar layout so user settings row is truly at the bottom */
        .app-sidebar .main-sidebar {
            display: flex;
            flex-direction: column;
        }

        .app-sidebar .main-sidebar .main-menu-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding-bottom: 0;
        }

        .app-sidebar .main-sidebar .main-menu {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding-bottom: 0;
        }

        #sidebar-user-settings {
            margin-top: auto;
        }

        .job-toast-popup {
            padding: 0.5rem 0.75rem !important;
            min-width: 10rem !important;
            max-width: 16rem !important;
        }

        .job-toast-popup .swal2-title {
            font-size: 0.8rem !important;
            font-weight: 500 !important;
        }


        /* ALIGNMENT: Match header dropdowns with the active theme background */
        html.dark .main-header-dropdown,
        body.dark-theme .main-header-dropdown,
        html.dark .ti-dropdown-menu,
        body.dark-theme .ti-dropdown-menu {
            background-color: rgb(var(--body-bg)) !important;
            background: rgb(var(--body-bg)) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5) !important;
        }

        html.dark .main-header-dropdown li a:hover,
        body.dark-theme .main-header-dropdown li a:hover,
        html.dark .ti-dropdown-item:hover,
        body.dark-theme .ti-dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        .dark .app-sidebar .side-menu__item {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .dark .app-sidebar .side-menu__item .side-menu__label,
        .dark .app-sidebar .side-menu__item .side-menu__icon {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .dark .app-sidebar .slide__category .category-name {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        /* Subtle Indigo Highlighting (Restored) */
        .app-sidebar .side-menu__item:hover {
            background: transparent !important;
            color: var(--sb-text-active) !important;
        }

        .app-sidebar .side-menu__item.active-menu {
            background: transparent !important;
            color: var(--sb-text-active) !important;
            border-right: 3px solid var(--sb-accent) !important;
        }

        /* Force Label Transparency (No Box) */
        .app-sidebar .side-menu__label,
        .app-sidebar .side-menu__item span {
            background: transparent !important;
        }

        .dark .app-sidebar .slide__category .category-name {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        /* Dark mode HEADER - force dark background when html has dark class */
        html.dark .app-header,
        html.dark[data-header-styles="light"] .app-header,
        html.dark[data-header-styles="transparent"] .app-header {
            background-color: rgb(var(--body-bg)) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        html.dark .app-header .header-content-right,
        html.dark .app-header .header-content-right li,
        html.dark .app-header .header-content-right>*,
        html.dark .app-header .header-element,
        html.dark .app-header .ti-dropdown,
        html.dark .app-header .hs-dropdown,
        html.dark .app-header .header-link,
        html.dark .app-header [class*="header-"],
        html.dark .app-header ul,
        html.dark .app-header li {
            background-color: transparent !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        html.dark .app-header .header-link-icon,
        html.dark .app-header .header-link,
        html.dark .app-header .header-link svg,
        html.dark .app-header .header-link i,
        html.dark .app-header .ti-dropdown-toggle,
        html.dark .app-header .ti-dropdown-toggle svg,
        html.dark .app-header .switcher-icon,
        html.dark .app-header .switcher-icon svg {
            color: rgba(255, 255, 255, 0.7) !important;
            stroke: rgba(255, 255, 255, 0.7) !important;
        }

        html.dark .app-header .header-link:hover,
        html.dark .app-header .header-link:hover svg,
        html.dark .app-header .header-link:hover i {
            color: #fff !important;
            stroke: #fff !important;
        }

        /* Dark mode header mini buttons (Dark/Theme/Notification/Profile) */
        html.dark .app-header .ti-btn.ti-btn-outline-light,
        html.dark .app-header button.ti-btn-outline-light,
        html.dark .app-header .header-element .ti-btn-outline-light {
            background: rgba(255, 255, 255, 0.06) !important;
            border-color: rgba(255, 255, 255, 0.14) !important;
            color: rgba(255, 255, 255, 0.85) !important;
            box-shadow: none !important;
        }

        html.dark .app-header .ti-btn.ti-btn-outline-light:hover,
        html.dark .app-header button.ti-btn-outline-light:hover,
        html.dark .app-header .header-element .ti-btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: rgba(255, 255, 255, 0.22) !important;
            color: #ffffff !important;
        }

        html.dark .app-header .ti-btn.ti-btn-outline-light i,
        html.dark .app-header .ti-btn.ti-btn-outline-light svg,
        html.dark .app-header .ti-btn.ti-btn-outline-light .switcher-icon {
            color: rgba(255, 255, 255, 0.75) !important;
            stroke: rgba(255, 255, 255, 0.75) !important;
        }

        html.dark .app-header .ti-btn.ti-btn-outline-light:hover i,
        html.dark .app-header .ti-btn.ti-btn-outline-light:hover svg,
        html.dark .app-header .ti-btn.ti-btn-outline-light:hover .switcher-icon {
            color: #ffffff !important;
            stroke: #ffffff !important;
        }

        html.dark .app-header .ti-btn-outline-light .avatar,
        html.dark .app-header .ti-btn-outline-light .avatar img {
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        /* Guard against utility classes forcing bright surfaces in dark header */
        html.dark .app-header .ti-btn-outline-light.border-gray-200 {
            border-color: rgba(255, 255, 255, 0.14) !important;
        }

        html.dark .app-header .ti-btn-outline-light.dark\:bg-white\/5 {
            background: rgba(255, 255, 255, 0.06) !important;
        }

        /* Dark mode header search - FORCE with highest specificity */
        html.dark #header-search,
        body.dark-theme #header-search,
        html.dark input#header-search,
        body.dark-theme input#header-search,
        html.dark input.header-search-bar,
        body.dark-theme input.header-search-bar {
            background-color: rgba(0, 0, 0, 0.2) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }

        /* Dark mode backgrounds for boxes and containers */
        html.dark body,
        body.dark-theme {
            background-color: rgb(26, 28, 30) !important;
        }

        html.dark .main-content,
        body.dark-theme .main-content,
        html.dark .app-content,
        body.dark-theme .app-content {
            background-color: rgb(26, 28, 30) !important;
        }

        html.dark .bg-white,
        body.dark-theme .bg-white {
            background-color: rgb(30, 32, 35) !important;
        }

        html.dark .bg-gray-50,
        body.dark-theme .bg-gray-50,
        html.dark .bg-gray-100,
        body.dark-theme .bg-gray-100 {
            background-color: rgb(40, 42, 45) !important;
        }

        html.dark .shadow,
        body.dark-theme .shadow,
        html.dark .shadow-sm,
        body.dark-theme .shadow-sm {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2) !important;
        }

        html.dark .border,
        body.dark-theme .border,
        html.dark .border-gray-100,
        body.dark-theme .border-gray-100,
        html.dark .border-gray-200,
        body.dark-theme .border-gray-200,
        html.dark .border-gray-300,
        body.dark-theme .border-gray-300 {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* Force switcher panel to be ABOVE the backdrop (1001 vs 1000) */
        .hs-overlay,
        .ti-offcanvas {
            z-index: 1001 !important;
        }

        /* Force backdrop/container to be above head/sidebar (200/201) but BELOW switcher (1001) */
        .hs-overlay-backdrop,
        [data-hs-overlay-backdrop-container] {
            z-index: 1000 !important;
        }

        /* Custom Draggable Overlay Sidebar */
        html[data-vertical-style="overlay"] .app-sidebar,
        html[data-vertical-style="overlay"] .app-sidebar.sticky {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: auto !important;
            right: auto !important;
            height: max-content !important;
            max-height: 80vh !important;
            border-radius: 12px !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(0, 0, 0, 0.05) !important;
            margin: 0 !important;
            transition: none !important;
            /* Disable transition so drag is 1:1 smooth */
            z-index: 2000 !important;
            /* Make it float above everything */
            display: flex !important;
            flex-direction: column !important;
        }

        html[data-vertical-style="overlay"] .app-sidebar .main-sidebar {
            height: auto !important;
            max-height: calc(80vh - 70px) !important;
            overflow-y: auto !important;
            flex: 1 !important;
            padding-bottom: 10px !important;
        }

        /* Make the header draggable */
        html[data-vertical-style="overlay"] .app-sidebar .main-sidebar-header,
        html[data-vertical-style="overlay"] .app-sidebar .sb-profile {
            cursor: grab !important;
            user-select: none !important;
            touch-action: none;
            /* Prevent browser scroll interference on touch devices */
        }

        html[data-vertical-style="overlay"] .app-sidebar .main-sidebar-header:active,
        html[data-vertical-style="overlay"] .app-sidebar .sb-profile:active {
            cursor: grabbing !important;
        }

        /* Overlay hidden state */
        html[data-vertical-style="overlay"][data-toggled="icon-overlay-close"] .app-sidebar {
            transform: translate(-100%, 0) !important;
            visibility: hidden;
            pointer-events: none;
        }

        /* Backdrop animation adjustments */
        #sidemenu-v2-backdrop.opacity-100 {
            opacity: 1 !important;
        }

        /* Sidemenu - Closed Menu Fullscreen Overrides */
        [data-vertical-style="closed"][data-toggled="close-menu-close"] .app-content {
            margin-inline-start: 0 !important;
        }

        [data-vertical-style="closed"][data-toggled="close-menu-close"] .app-header {
            padding-inline-start: 0 !important;
            margin-inline-start: 0 !important;
            width: 100% !important;
        }

        [data-vertical-style="closed"][data-toggled="close-menu-close"] .app-header .sidemenu-toggle {
            display: none !important;
        }

        [data-vertical-style="closed"][data-toggled="close-menu-close"] .main-header-container {
            padding-inline-start: 1rem !important;
            /* Visual compensation for toggle icon */
            margin-inline-start: 0 !important;
            width: 100% !important;
        }

        [data-vertical-style="closed"][data-toggled="close-menu-close"] .app-sidebar {
            display: none !important;
        }
    </style>
    <script>
        // Global image error handler - fallback to DiceBear avatar
        document.addEventListener('DOMContentLoaded', function () {
            // Handle all images that fail to load
            document.addEventListener('error', function (e) {
                if (e.target.tagName === 'IMG' && !e.target.dataset.fallbackApplied) {
                    e.target.dataset.fallbackApplied = 'true';

                    // Generate a seed from the alt text or a random string
                    const seed = e.target.alt || e.target.dataset.userName || Math.random().toString(36).substring(7);

                    // Check if it's likely a profile/avatar image
                    const isAvatar = e.target.classList.contains('avatar') ||
                        e.target.closest('.avatar') ||
                        e.target.src.includes('profile') ||
                        e.target.src.includes('storage') ||
                        e.target.src.includes('photo');

                    if (isAvatar) {
                        e.target.src = 'https://api.dicebear.com/7.x/avataaars/svg?seed=' + encodeURIComponent(seed) + '&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981';
                    }
                }
            }, true);
        });

        window.JobToast = {
            show: function (message, type) {
                if (window.Swal && typeof window.Swal.fire === 'function') {
                    var lower = (message || '').toLowerCase();
                    var icon = type || 'success';

                    if (!type) {
                        if (lower.includes('removed')) {
                            icon = 'info';
                        } else if (lower.includes('error') || lower.includes('wrong') || lower.includes('failed')) {
                            icon = 'error';
                        }
                    }

                    window.Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: icon,
                        title: message,
                        showConfirmButton: false,
                        timer: 1800,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'job-toast-popup',
                        },
                    });
                } else {
                    console.log(message);
                }
            },
        };
    </script>

    <style id="double-menu-morph-styles">
        /* Center category icons in the first rail when double menu is active */
        html[data-vertical-style="doublemenu"] .app-sidebar .main-menu>.double-menu-morphed {
            width: 70px !important;
            /* min-width: 80px !important; */
            /* max-width: 80px !important; */
            align-self: flex-start !important;
        }

        html[data-vertical-style="doublemenu"] .app-sidebar .main-menu>.double-menu-morphed>.side-menu__item {
            /* width: 80px !important;
            min-width: 80px !important;
            max-width: 80px !important; */
            justify-content: center !important;
            align-items: center !important;
            padding-inline: 0 !important;
        }

        html[data-vertical-style="doublemenu"] .app-sidebar .main-menu>.double-menu-morphed>.side-menu__item .side-menu__label,
        html[data-vertical-style="doublemenu"] .app-sidebar .main-menu>.double-menu-morphed>.side-menu__item .side-menu__angle {
            display: none !important;
        }

        html[data-vertical-style="doublemenu"] .app-sidebar .main-menu>.double-menu-morphed>.side-menu__item .side-menu__icon {
            margin-inline: 0 !important;
            margin: 0 auto !important;
        }

        /* Force visibility and correct coloring of morphed submenu items in Double Menu mode */
        html[data-vertical-style="doublemenu"] .app-sidebar .slide-menu .child-item-morphed .side-menu__icon {
            display: inline-block !important;
            opacity: 1 !important;
            visibility: visible !important;
            color: inherit !important;
            flex-shrink: 0 !important;
        }

        html[data-vertical-style="doublemenu"] .app-sidebar .slide-menu .child-item-morphed .side-menu__label {
            display: inline-block !important;
            opacity: 1 !important;
            visibility: visible !important;
            color: inherit !important;
            white-space: normal !important;
            word-break: break-word !important;
            line-height: 1.4 !important;
            height: auto !important;
        }

        html[data-vertical-style="doublemenu"] .app-sidebar .slide-menu .child-item-morphed .side-menu__icon,
        html[data-vertical-style="doublemenu"] .app-sidebar .slide-menu .child-item-morphed .side-menu__label {
            pointer-events: none !important;
        }

        html[data-vertical-style="doublemenu"] .app-sidebar .slide-menu .child-item-morphed .side-menu__item {
            display: flex !important;
            align-items: center !important;
            color: #4b5563 !important;
            height: auto !important;
            min-height: 40px !important;
            padding-top: 8px !important;
            padding-bottom: 8px !important;
        }

        html[data-vertical-style="doublemenu"] .app-sidebar .slide-menu .child-item-morphed:hover .side-menu__item,
        html[data-vertical-style="doublemenu"] .app-sidebar .slide-menu .child-item-morphed.active .side-menu__item,
        html[data-vertical-style="doublemenu"] .app-sidebar .slide-menu .child-item-morphed .side-menu__item.active-menu {
            color: var(--primary) !important;
        }

        /* Dark mode overrides */
        html.dark[data-vertical-style="doublemenu"] .app-sidebar .slide-menu .child-item-morphed .side-menu__item {
            color: #9ca3af !important;
        }
    </style>

    <script>
        // --- Double Menu Dynamic Morpher ---
        // Dynamically restructures the flat sidemenu-v2 into a nested group layout required by Xyntra's advanced "doublemenu".
        document.addEventListener('DOMContentLoaded', () => {
            function ensureDefaultDoubleMenuOpen(mainMenu) {
                if (!mainMenu) {
                    return;
                }

                const hasOpenCategory = mainMenu.querySelector(':scope > .double-menu-morphed.double-menu-active');
                if (hasOpenCategory) {
                    return;
                }

                const activeParent = mainMenu
                    .querySelector(':scope > .double-menu-morphed .child-item-morphed .side-menu__item.active-menu')
                    ?.closest('.double-menu-morphed');
                const firstParent = mainMenu.querySelector(':scope > .double-menu-morphed');
                const targetParent = activeParent || firstParent;

                if (!targetParent) {
                    return;
                }

                const trigger = targetParent.querySelector(':scope > .side-menu__item');
                if (trigger) {
                    trigger.dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true }));
                }

                targetParent.classList.add('double-menu-active', 'active', 'show');
                targetParent.querySelector(':scope > .slide-menu')?.classList.add('show');
            }

            function applyDoubleMenuMorph() {
                const mainMenu = document.querySelector('.main-menu');
                if (!mainMenu) {
                    return;
                }

                if (mainMenu.hasAttribute('data-morphed')) {
                    ensureDefaultDoubleMenuOpen(mainMenu);
                    return;
                }

                const children = Array.from(mainMenu.children);
                let currentSlideMenu = null;

                children.forEach(child => {
                    if (child.classList.contains('slide__category') && child.hasAttribute('data-category-name')) {
                        const catName = child.getAttribute('data-category-name');
                        const catIcon = child.getAttribute('data-category-icon') || 'ri-folder-fill';

                        const li = document.createElement('li');
                        li.className = 'slide has-sub double-menu-morphed';

                        const a = document.createElement('a');
                        a.href = 'javascript:void(0);';
                        a.className = 'side-menu__item';

                        const icon = document.createElement('i');
                        icon.className = `${catIcon} side-menu__icon !text-xl`;

                        const label = document.createElement('span');
                        label.className = 'side-menu__label';
                        label.innerText = catName;

                        const angle = document.createElement('i');
                        angle.className = 'fe fe-chevron-right side-menu__angle';

                        a.append(icon, label, angle);
                        li.appendChild(a);

                        const ul = document.createElement('ul');
                        ul.className = 'slide-menu child1';

                        const label1 = document.createElement('li');
                        label1.className = 'slide side-menu__label1';
                        const aLabel = document.createElement('a');
                        aLabel.href = 'javascript:void(0);';
                        aLabel.innerText = catName;
                        label1.appendChild(aLabel);
                        ul.appendChild(label1);

                        li.appendChild(ul);
                        mainMenu.insertBefore(li, child);

                        child.style.display = 'none';
                        child.classList.add('old-category-morphed');

                        currentSlideMenu = ul;

                    } else if (currentSlideMenu && child.classList.contains('slide') && !child.classList.contains('double-menu-morphed')) {
                        currentSlideMenu.appendChild(child);
                        child.classList.add('child-item-morphed');
                    }
                });

                mainMenu.setAttribute('data-morphed', 'true');

                // Initialize Xyntra native listener
                if (typeof doublemenu === 'function') {
                    doublemenu();
                }

                requestAnimationFrame(() => ensureDefaultDoubleMenuOpen(mainMenu));
            }

            function removeDoubleMenuMorph() {
                const mainMenu = document.querySelector('.main-menu');
                if (!mainMenu || !mainMenu.hasAttribute('data-morphed')) return;

                document.querySelectorAll('.double-menu-morphed').forEach(parent => {
                    const slideMenu = parent.querySelector('.slide-menu');
                    const oldCat = parent.nextElementSibling;

                    if (slideMenu) {
                        const kids = Array.from(slideMenu.querySelectorAll('.child-item-morphed'));
                        let insertBeforeNode = oldCat.nextElementSibling;
                        kids.forEach(kid => {
                            mainMenu.insertBefore(kid, insertBeforeNode);
                            kid.classList.remove('child-item-morphed');
                        });
                    }

                    if (oldCat && oldCat.classList.contains('old-category-morphed')) {
                        oldCat.style.display = '';
                        oldCat.classList.remove('old-category-morphed');
                    }

                    parent.remove();
                });

                mainMenu.removeAttribute('data-morphed');
                document.querySelectorAll('.double-menu-active').forEach(e => e.classList.remove('double-menu-active'));
            }

            const observer = new MutationObserver((mutations) => {
                mutations.forEach(mutation => {
                    if (mutation.attributeName === 'data-vertical-style') {
                        if (document.documentElement.getAttribute('data-vertical-style') === 'doublemenu') {
                            applyDoubleMenuMorph();
                        } else {
                            removeDoubleMenuMorph();
                        }
                    }
                });
            });

            observer.observe(document.documentElement, { attributes: true });

            if (document.documentElement.getAttribute('data-vertical-style') === 'doublemenu') {
                applyDoubleMenuMorph();
            }
        });
    </script>

    @include('layouts.components.nav-footer-link')

    <script src="/js/toast.js"></script>
    @include('components.new-update-manager')

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('SW registered:', registration.scope);
                    })
                    .catch((error) => {
                        console.log('SW registration failed:', error);
                    });
            });
        }
    </script>

    @include('layouts.components.nav-switcher')
    @stack('scripts')
    @stack('modals')

</body>

</html>
