{{--
Sidemenu V2 - Modular Version for HillBCS Hire
Split into separate files for easier maintenance

Usage: @include('components.navigation.sidemenu-v2.index')
--}}

@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

@php
    // Soft-deleted: Force false to disable legacy theme styles
    $hasCustomTheme = false;
@endphp

{{--
{{-- Force White Text and Apply Solid Brown for Brass Monkey --}}
@if(auth()->check() && method_exists(auth()->user(), 'getTheme') && auth()->user()->getTheme() == 'theme-brass-monkey')
    <style>
        /* Force ALL Sidebar Text & Icons to be Cream (Match Header) */
        html body .app-sidebar .side-menu__item,
        html body .app-sidebar .side-menu__label,
        html body .app-sidebar .side-menu__icon,
        html body .app-sidebar .side-menu__item *,
        html body .app-sidebar .side-menu__category,
        html body .app-sidebar .nav-link,
        html body .app-sidebar span,
        html body .app-sidebar i,
        html body .app-sidebar svg {
            color: #F5F5DC !important;
            /* Cream */
            fill: #F5F5DC !important;
            -webkit-text-fill-color: #F5F5DC !important;
        }

        /* Keep Active State White */
        html body .app-sidebar .side-menu__item.active,
        html body .app-sidebar .side-menu__item.active *,
        html body .active-menu,
        html body .active-menu * {
            color: #FFFFFF !important;
            fill: #FFFFFF !important;
            -webkit-text-fill-color: #FFFFFF !important;
        }

        /* Hover State - Theme Matching (Cream Overlay) */
        html body .app-sidebar .side-menu__item:hover,
        html body .app-sidebar .side-menu__item:focus,
        html body .app-sidebar .slide.has-sub.open>.side-menu__item {
            background-color: rgba(245, 245, 220, 0.15) !important;
            /* Cream 15% */
            border-radius: 5px !important;
        }

        /* Ensure text/icons are white on hover */
        html body .app-sidebar .side-menu__item:hover *,
        html body .app-sidebar .side-menu__item:focus *,
        html body .app-sidebar .slide.has-sub.open>.side-menu__item * {
            color: #FFFFFF !important;
            fill: #FFFFFF !important;
            background-color: transparent !important;
            /* Prevent text background */
        }

        /* Sidebar Background - Solid Brown */
        .app-sidebar {
            background: #5D4037 !important;
            /* Solid Brown */
            background-color: #5D4037 !important;
        }

        .app-sidebar .main-sidebar {
            background: transparent !important;
        }

        /* Sidebar Header - Solid Brown */
        html body .app-sidebar .main-sidebar-header,
        #sidebar .main-sidebar-header,
        .app-sidebar .main-sidebar-header {
            background: #5D4037 !important;
            background-color: #5D4037 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Hover/Active States */
        html body .app-sidebar .side-menu__item:hover,
        html body .app-sidebar .side-menu__item:focus,
        html body .app-sidebar .side-menu__item.active,
        html body .app-sidebar .slide-menu a:hover,
        html body .app-sidebar .slide-menu a.active {
            color: #FFD700 !important;
            /* Gold */
            background-color: transparent !important;
        }

        /* Specific overrides for icons on hover */
        html body .app-sidebar .side-menu__item:hover .side-menu__icon,
        html body .app-sidebar .side-menu__item.active .side-menu__icon {
            color: #FFD700 !important;
            fill: #FFD700 !important;
        }
    </style>
@endif

{{-- Hillbc Theme (Blue/Yellow/White) --}}
@if(auth()->check() && method_exists(auth()->user(), 'getTheme') && auth()->user()->getTheme() == 'theme-hillbc')
    <style>
        /* Sidebar Background - Deep Blue Gradient */
        .app-sidebar {
            background: linear-gradient(to bottom, #000428, #004e92) !important;
            /* Deep Blue Vertical Gradient */
        }

        .app-sidebar .main-sidebar {
            background: transparent !important;
            /* Let parent gradient show */
        }

        /* Sidebar Header - Deep Blue Gradient (Match Sidebar) */
        html body .app-sidebar .main-sidebar-header,
        #sidebar .main-sidebar-header,
        .app-sidebar .main-sidebar-header {
            background: transparent !important;
            /* Seamless with sidebar */
            background-color: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-right: 0 !important;
        }

        /* Ensure logo link is transparent */
        .app-sidebar .main-sidebar-header .header-logo {
            background: transparent !important;
        }

        /* Make Logo (IDS/IOS) Bright Yellow */
        .app-sidebar .main-sidebar-header .header-logo img {
            filter: brightness(0) saturate(100%) invert(87%) sepia(61%) saturate(3620%) hue-rotate(357deg) brightness(104%) contrast(104%) !important;
            /* Bright Yellow #FFFF00 */
        }

        /* Force ALL Sidebar Text & Icons to be White/Yellow */
        html body .app-sidebar .side-menu__item,
        html body .app-sidebar .side-menu__label,
        html body .app-sidebar .side-menu__icon,
        html body .app-sidebar .side-menu__item *,
        html body .app-sidebar .side-menu__category,
        html body .app-sidebar .nav-link,
        html body .app-sidebar span,
        html body .app-sidebar i,
        html body .app-sidebar svg {
            color: #FFFFFF !important;
            /* White */
            fill: #FFFFFF !important;
            -webkit-text-fill-color: #FFFFFF !important;
        }

        /* Keep Active State Yellow */
        html body .app-sidebar .side-menu__item.active,
        html body .app-sidebar .side-menu__item.active *,
        html body .active-menu,
        html body .active-menu * {
            color: #FFFF00 !important;
            /* Yellow */
            fill: #FFFF00 !important;
            -webkit-text-fill-color: #FFFF00 !important;
        }

        /* Hover State - Yellow Text Only (No Background) */
        html body .app-sidebar .side-menu__item:hover,
        html body .app-sidebar .side-menu__item:focus,
        html body .app-sidebar .slide.has-sub.open>.side-menu__item {
            background-color: transparent !important;
            /* No Box */
            border-radius: 5px !important;
        }

        /* Ensure text/icons are yellow on hover */
        html body .app-sidebar .side-menu__item:hover *,
        html body .app-sidebar .side-menu__item:focus *,
        html body .app-sidebar .slide.has-sub.open>.side-menu__item * {
            color: #FFFF00 !important;
            /* Yellow */
            fill: #FFFF00 !important;
            background-color: transparent !important;
            /* Prevent text background */
        }
    </style>
@endif

<style>
    /* Adjust Sidebar Width (Applied to Active Sidemenu V2) */
    @media (min-width: 992px) {
        .app-sidebar {
            width: 280px !important;
        }

        .app-content {
            margin-left: 280px !important;
        }

        /*
           Restore default for ALL collapsed states
        */
        [data-toggled="icon-overlay-close"] .app-sidebar,
        [data-toggled="close"] .app-sidebar,
        [data-toggled="icon-text-close"] .app-sidebar,
        [data-toggled="detached-close"] .app-sidebar,
        [data-toggled="menu-click-closed"] .app-sidebar,
        [data-toggled="menu-hover-closed"] .app-sidebar,
        [data-toggled="icon-click-closed"] .app-sidebar,
        [data-toggled="icon-hover-closed"] .app-sidebar,
        [data-toggled="double-menu-close"] .app-sidebar {
            width: 5rem !important;
        }

        [data-toggled="icon-overlay-close"] .app-content,
        [data-toggled="close"] .app-content,
        [data-toggled="icon-text-close"] .app-content,
        [data-toggled="detached-close"] .app-content,
        [data-toggled="menu-click-closed"] .app-content,
        [data-toggled="menu-hover-closed"] .app-content,
        [data-toggled="icon-click-closed"] .app-content,
        [data-toggled="icon-hover-closed"] .app-content,
        [data-toggled="double-menu-close"] .app-content {
            margin-left: 5rem !important;
        }

        /* Hover Expansion - REMOVED for manual click logic */
        /* Ensure text doesn't wrap or cut off prematurely */
        .app-sidebar .side-menu__label {
            white-space: nowrap !important;
            overflow: visible !important;
            text-overflow: clip !important;
        }

        /* Adjust Header Padding to match Sidebar Width so "X" toggle is visible */
        .app-header {
            margin-left: 260px !important;
            width: calc(100% - 260px) !important;
            padding-inline-start: 0 !important;
        }

        /* Reset header margin/width on collapse */
        [data-toggled="icon-overlay-close"] .app-header,
        [data-toggled="close"] .app-header,
        [data-toggled="icon-text-close"] .app-header,
        [data-toggled="detached-close"] .app-header,
        [data-toggled="menu-click-closed"] .app-header,
        [data-toggled="menu-hover-closed"] .app-header,
        [data-toggled="icon-click-closed"] .app-header,
        [data-toggled="icon-hover-closed"] .app-header,
        [data-toggled="double-menu-close"] .app-header {
            margin-left: 80px !important;
            width: calc(100% - 80px) !important;
            padding-inline-start: 0 !important;
        }
    }
</style>

<aside class="app-sidebar {{ $hasCustomTheme ? 'custom-theme-active' : '' }}" id="sidebar">
    <div class="main-sidebar" id="sidebar-scroll">
        @include('components.navigation.sidemenu-v2.partials.styles')
        @include('components.navigation.sidemenu-v2.partials.profile-search')

        <nav class="main-menu-container nav nav-pills flex-col sub-open">
            @include('components.navigation.sidemenu-v2.partials.slide-left')

            <ul class="main-menu">
                @if ($user && $user->role === 'applicant')
                    @include('components.navigation.sidemenu-v2.menus.candidate-menu')
                @elseif ($user && $user->role === 'employer')
                    @include('components.navigation.sidemenu-v2.menus.employer-menu')
                @elseif ($user && in_array($user->role, ['moderator', 'admin', 'super_admin']))
                    @include('components.navigation.sidemenu-v2.menus.moderator-menu')
                @else
                    @include('components.navigation.sidemenu-v2.menus.candidate-menu')
                @endif
            </ul>

            @include('components.navigation.sidemenu-v2.partials.slide-right')
        </nav>
    </div>
</aside>

<!-- Inline Style for Toggle Button Logic -->
<style>
    /* Show inner toggle only in overlay mode */
    html[data-vertical-style="overlay"] .sidemenu-toggle-slide {
        display: flex !important;
    }

    /* Keep it centered when collapsed */
    html[data-vertical-style="overlay"][data-toggled="icon-overlay-close"] .sidemenu-toggle-slide,
    html[data-vertical-style="overlay"][data-toggled="close"] .sidemenu-toggle-slide {
        justify-content: center !important;
    }

    /* Adjust icon when collapsed */
    html[data-vertical-style="overlay"][data-toggled="icon-overlay-close"] .sidemenu-toggle-slide i::before,
    html[data-vertical-style="overlay"][data-toggled="close"] .sidemenu-toggle-slide i::before {
        content: "\eee1" !important; /* ri-indent-increase */
    }
</style>

@include('components.navigation.sidemenu-v2.partials.scripts')