{{-- Sidemenu V2 - Premium Dark Theme Styles --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

    :root {
        --sb-item-hover: rgba(0, 0, 0, 0.05);
        --sb-item-active: rgba(99, 102, 241, 0.1);
        --sb-accent: #6366f1;
        --sb-text: #000000;
        /* Force black for visibility */
        --sb-text-muted: #475569;
        --sb-text-active: #6366f1;
        /* Accent for active/hover in light mode */
        --sb-glow: 0 0 15px rgba(99, 102, 241, 0.2);
        --sb-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --sb-font: 'Poppins', sans-serif;
    }

    html.dark,
    body.dark-theme,
    html[data-theme-mode="dark"] {
        --sb-bg-gradient: linear-gradient(180deg, #1a1c2e 0%, #11131f 100%);
        --sb-item-hover: rgba(255, 255, 255, 0.05);
        --sb-item-active: rgba(99, 102, 241, 0.15);
        --sb-accent: #6366f1;
        --sb-text: #d1d5db;
        --sb-text-muted: #9ca3af;
        --sb-text-active: #ffffff;
        --sb-glow: 0 0 15px rgba(99, 102, 241, 0.4);
    }

    /* Structural overhauls */
    /* Hide sidebar toggle in icon-hover mode */
    [data-nav-style="icon-hover"] .header-content-left {
        display: none !important;
    }

    .app-sidebar {
        background: rgb(var(--body-bg)) !important;
        background-color: rgb(var(--body-bg)) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
        width: 260px;
        /* Removed !important to allow template modes to override */
        transition: var(--sb-transition) !important;
        box-shadow: 10px 0 30px rgba(0, 0, 0, 0.05) !important;
        font-family: var(--sb-font) !important;
    }

    html.dark .app-sidebar,
    body.dark-theme .app-sidebar,
    html[data-theme-mode="dark"] .app-sidebar {
        background: #1a1c1e !important;
        background-color: #1a1c1e !important;
        border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
        box-shadow: 10px 0 30px rgba(0, 0, 0, 0.2) !important;
    }

    html.dark[data-vertical-style="closed"] .app-sidebar,
    body.dark-theme[data-vertical-style="closed"] .app-sidebar,
    html[data-theme-mode="dark"][data-vertical-style="closed"] .app-sidebar,
    html.dark[data-vertical-style="closed"] .app-sidebar .main-sidebar,
    body.dark-theme[data-vertical-style="closed"] .app-sidebar .main-sidebar,
    html[data-theme-mode="dark"][data-vertical-style="closed"] .app-sidebar .main-sidebar,
    html.dark[data-vertical-style="closed"] .app-sidebar .main-menu-container,
    body.dark-theme[data-vertical-style="closed"] .app-sidebar .main-menu-container,
    html[data-theme-mode="dark"][data-vertical-style="closed"] .app-sidebar .main-menu-container {
        background: #1a1c1e !important;
        background-color: #1a1c1e !important;
    }

    html.dark .app-sidebar .main-sidebar,
    body.dark-theme .app-sidebar .main-sidebar,
    html[data-theme-mode="dark"] .app-sidebar .main-sidebar,
    html.dark .app-sidebar .main-menu-container,
    body.dark-theme .app-sidebar .main-menu-container,
    html[data-theme-mode="dark"] .app-sidebar .main-menu-container {
        background: #1a1c1e !important;
        background-color: #1a1c1e !important;
    }



    .app-sidebar .main-sidebar {
        margin-top: 0 !important;
        padding: 0 0.75rem 0.75rem !important;
        background: transparent !important;
    }

    /* Profile Section */
    .sb-profile {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0 1rem;
        height: 84px !important;
        /* Fixed height for HCI continuity */
        margin-bottom: 0.25rem;
        transition: var(--sb-transition);
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
    }

    html.dark .sb-profile {
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .sb-hillbcs-logo {
        width: 60px;
        height: 60px;
        object-fit: cover;
        /* margin-left: -15px; */
    }

    .sb-hillbcs-info {
        display: flex;
        flex-direction: column;
        overflow: hidden;
        margin-top: 16px;
    }

    .sb-hillbcs-name {
        color: var(--sb-text);
        font-weight: 1000;
        font-size: 1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sb-hillbcs-role {
        color: var(--sb-text-muted);
        font-size: 0.8rem;
    }


    .sb-profile-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid rgba(0, 0, 0, 0.05);
    }

    html.dark .sb-profile-avatar {
        border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .sb-profile-info {
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .sb-profile-name {
        color: var(--sb-text);
        font-weight: 600;
        font-size: 0.95rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    html.dark .sb-profile-name {
        color: #fff;
    }

    .sb-profile-role {
        color: var(--sb-text-muted);
        font-size: 0.8rem;
    }

    /* --- Sidebar Search --- */
    .sb-search-wrapper {
        position: relative;
        padding: 0 16px 16px;
        display: flex;
        align-items: center;
        transition: var(--sb-transition);
    }

    .sb-search-icon {
        position: absolute;
        left: 28px;
        top: 0;
        bottom: 16px; /* Match padding-bottom of wrapper */
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--sb-text-muted) !important;
        font-size: 1.1rem !important;
        z-index: 10;
        pointer-events: none;
    }

    .sb-search-input {
        width: 100%;
        background: rgba(var(--sb-accent-rgb), 0.03) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 12px !important;
        padding: 10px 15px 10px 42px !important;
        color: var(--sb-text) !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        transition: all 0.2s ease;
    }

    .dark .sb-search-input {
        background: rgba(255, 255, 255, 0.04) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
    }

    .sb-search-input:focus {
        background: transparent !important;
        border-color: var(--sb-accent) !important;
        box-shadow: 0 0 0 3px rgba(var(--sb-accent-rgb), 0.1) !important;
        outline: none !important;
    }

    .sb-search-input::placeholder {
        color: var(--sb-text-muted) !important;
        opacity: 0.6 !important;
    }

    /* Menu Items */
    .app-sidebar .main-menu {
        padding: 0 !important;
        margin: 0 !important;
        list-style: none !important;
    }

    .app-sidebar .slide__category {
        padding: 0.75rem 0.75rem 0.25rem !important;
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 1.2px !important;
        color: var(--sb-text-muted) !important;
        opacity: 0.6;
        height: 32px;
        display: flex;
        align-items: center;
        transition: var(--sb-transition);
    }

    .app-sidebar .side-menu__item {
        display: flex;
        align-items: center;
        padding: 0 1rem !important;
        /* Fixed padding for alignment */
        height: 50px !important;
        /* Fixed height for HCI continuity */
        margin: 0 !important;
        border-radius: 12px !important;
        color: var(--sb-text) !important;
        text-decoration: none !important;
        transition: var(--sb-transition) !important;
        position: relative !important;
        border: 1px solid transparent !important;
    }

    .app-sidebar .side-menu__item:hover>div:first-child {
        opacity: 1 !important;
    }

    .app-sidebar .side-menu__item:hover .side-menu__icon,
    .app-sidebar .side-menu__item:hover .side-menu__label,
    .app-sidebar .side-menu__item:hover .side-menu__angle {
        color: var(--sb-text-active) !important;
    }

    .app-sidebar .side-menu__item.active-menu {
        color: var(--sb-text-active) !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05), 0 0 15px rgba(99, 102, 241, 0.1) !important;
        border-right: 3px solid var(--sb-accent) !important;
    }

    .app-sidebar .side-menu__item.active-menu>div:first-child {
        opacity: 1 !important;
    }

    html.dark .app-sidebar .side-menu__item.active-menu {
        /* background: rgba(99, 102, 241, 0.12) !important; */
        /* background-color: rgba(99, 102, 241, 0.12) !important; */
        color: var(--sb-text-active) !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1), 0 0 15px rgba(99, 102, 241, 0.2) !important;
        border-left: 1px solid rgba(255, 255, 255, 0.05) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
        backdrop-filter: blur(4px);
    }

    .app-sidebar .side-menu__item.active-menu .side-menu__icon,
    .app-sidebar .side-menu__item.active-menu .side-menu__label {
        color: var(--sb-text-active) !important;
    }

    html.dark .app-sidebar .side-menu__item.active-menu .side-menu__icon,
    html.dark .app-sidebar .side-menu__item.active-menu .side-menu__label {
        text-shadow: 0 0 12px rgba(99, 102, 241, 0.5);
    }

    .app-sidebar .side-menu__icon {
        font-size: 1.2rem !important;
        margin-right: 0.85rem !important;
        transition: var(--sb-transition) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 24px !important;
        height: 24px !important;
    }

    html body .app-sidebar .side-menu__label {
        font-size: 0.9rem !important;
        font-weight: 500 !important;
        flex: 1 !important;
        background: none !important;
        background-color: transparent !important;
    }

    html body .app-sidebar .side-menu__item span {
        background: none !important;
        background-color: transparent !important;
    }

    /* Accordion / Nested Menus */
    .app-sidebar .side-menu__item .side-menu__angle {
        font-size: 0.8rem !important;
        transition: transform 0.3s ease !important;
    }

    .app-sidebar .slide.show>.side-menu__item .side-menu__angle {
        transform: rotate(90deg) !important;
    }

    .app-sidebar .slide-menu {
        padding-left: 1.5rem !important;
        margin: 0.1rem 0 !important;
        display: none;
        list-style: none !important;
    }

    .app-sidebar .slide.show .slide-menu {
        display: block !important;
    }

    .app-sidebar .slide-menu .side-menu__item {
        padding: 0.5rem 0.8rem !important;
        font-size: 0.85rem !important;
        border: none !important;
    }

    /* Submenu Active Items should be subtle and not look like detached bulky cards */
    .app-sidebar .slide-menu .side-menu__item.active-menu,
    html.dark .app-sidebar .slide-menu .side-menu__item.active-menu {
        background: transparent !important;
        background-color: transparent !important;
        box-shadow: none !important;
        border: none !important;
        backdrop-filter: none !important;
    }

    .app-sidebar .slide-menu .side-menu__item.active-menu .side-menu__label,
    html.dark .app-sidebar .slide-menu .side-menu__item.active-menu .side-menu__label {
        color: var(--sb-accent) !important;
        text-shadow: none !important;
        font-weight: 600 !important;
    }

    .app-sidebar .slide-menu .side-menu__item.active-menu::before {
        background: var(--sb-accent) !important;
        width: 10px;
        border-radius: 4px;
        left: -0.85rem;
    }

    .app-sidebar .slide-menu .side-menu__item::before {
        content: '';
        position: absolute;
        left: -0.75rem;
        top: 48%;
        /* Adjust for Montserrat alignment */
        width: 6px;
        height: 1px;
        background: rgba(0, 0, 0, 0.12);
    }

    /* Remove circle icon on submenus */
    .app-sidebar .slide-menu .side-menu__icon {
        display: none !important;
    }

    .app-sidebar .slide-menu .side-menu__label {
        margin-left: -0.25rem !important;
    }

    html.dark .app-sidebar .slide-menu .side-menu__item::before {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Badges */
    .side-menu__item .badge {
        font-size: 0.7rem !important;
        font-weight: 700 !important;
        padding: 2px 6px !important;
        border-radius: 6px !important;
    }

    /* Collapsed Mode Improvements */
    [data-toggled="icon-overlay-close"] .app-sidebar,
    [data-toggled="close"] .app-sidebar,
    [data-toggled="icon-text-close"] .app-sidebar,
    [data-toggled="detached-close"] .app-sidebar,
    [data-toggled="menu-click-closed"] .app-sidebar,
    [data-toggled="menu-hover-closed"] .app-sidebar,
    [data-toggled="icon-click-closed"] .app-sidebar,
    [data-toggled="icon-hover-closed"] .app-sidebar,
    [data-toggled="double-menu-close"] .app-sidebar,
    [data-toggled*="closed"] .app-sidebar,
    [data-toggled="closed"] .app-sidebar {
        width: 80px !important;
    }

    /* Center profile and hide info in collapsed mode */
    [data-toggled*="-close"] .app-sidebar .sb-profile,
    [data-toggled="close"] .app-sidebar .sb-profile {
        justify-content: center !important;
        padding: 0 !important;
        height: 84px !important;
    }

    [data-toggled*="-close"] .app-sidebar .sb-profile-info,
    [data-toggled="close"] .app-sidebar .sb-profile-info {
        display: none !important;
    }

    /* Center Menu Icons and hide labels */
    [data-toggled*="-close"] .app-sidebar .side-menu__item,
    [data-toggled="close"] .app-sidebar .side-menu__item {
        justify-content: center !important;
        padding: 0 !important;
        height: 50px !important;
    }

    [data-toggled*="-close"] .app-sidebar .side-menu__icon,
    [data-toggled="close"] .app-sidebar .side-menu__icon {
        margin: 0 !important;
    }

    /* Icon-only sidemenu: keep clean centered icon buttons */
    [data-vertical-style="icontext"][data-toggled="icon-text-close"] .app-sidebar .main-menu>li,
    [data-nav-style="icon-click"][data-toggled="icon-click-closed"] .app-sidebar .main-menu>li,
    [data-nav-style="icon-hover"][data-toggled="icon-hover-closed"] .app-sidebar .main-menu>li {
        display: flex !important;
        justify-content: center !important;
    }

    [data-vertical-style="icontext"][data-toggled="icon-text-close"] .app-sidebar .side-menu__item,
    [data-nav-style="icon-click"][data-toggled="icon-click-closed"] .app-sidebar .side-menu__item,
    [data-nav-style="icon-hover"][data-toggled="icon-hover-closed"] .app-sidebar .side-menu__item {
        height: 52px !important;
        /* min-height: 48px !important; */
        margin: 0.35rem auto !important;
        padding: 0 16px 16px;
        border-radius: 14px !important;
        justify-content: center !important;
        align-items: center !important;
        overflow: hidden !important;
    }

    [data-vertical-style="icontext"][data-toggled="icon-text-close"] .app-sidebar .side-menu__item .side-menu__icon,
    [data-nav-style="icon-click"][data-toggled="icon-click-closed"] .app-sidebar .side-menu__item .side-menu__icon,
    [data-nav-style="icon-hover"][data-toggled="icon-hover-closed"] .app-sidebar .side-menu__item .side-menu__icon {
        margin: 0 !important;
        width: 22px !important;
        height: 22px !important;
    }

    [data-vertical-style="icontext"][data-toggled="icon-text-close"] .app-sidebar .side-menu__item>div:first-child,
    [data-nav-style="icon-click"][data-toggled="icon-click-closed"] .app-sidebar .side-menu__item>div:first-child,
    [data-nav-style="icon-hover"][data-toggled="icon-hover-closed"] .app-sidebar .side-menu__item>div:first-child {
        display: none !important;
    }

    [data-vertical-style="icontext"][data-toggled="icon-text-close"] .app-sidebar .side-menu__item.active-menu,
    [data-nav-style="icon-click"][data-toggled="icon-click-closed"] .app-sidebar .side-menu__item.active-menu,
    [data-nav-style="icon-hover"][data-toggled="icon-hover-closed"] .app-sidebar .side-menu__item.active-menu {
        border-right: none !important;
        border-left: none !important;
        box-shadow: none !important;
        /* background: rgba(99, 102, 241, 0.14) !important; */
        /* background-color: rgba(99, 102, 241, 0.14) !important; */
        color: var(--sb-accent) !important;
    }

    [data-vertical-style="icontext"][data-toggled="icon-text-close"] .app-sidebar .side-menu__item:hover,
    [data-nav-style="icon-click"][data-toggled="icon-click-closed"] .app-sidebar .side-menu__item:hover,
    [data-nav-style="icon-hover"][data-toggled="icon-hover-closed"] .app-sidebar .side-menu__item:hover {
        transform: none !important;
    }

    [data-vertical-style="icontext"][data-toggled="icon-text-close"] .app-sidebar .sb-search-wrapper,
    [data-nav-style="icon-click"][data-toggled="icon-click-closed"] .app-sidebar .sb-search-wrapper,
    [data-nav-style="icon-hover"][data-toggled="icon-hover-closed"] .app-sidebar .sb-search-wrapper {
        display: none !important;
    }

    /* Hide specific elements in collapsed mode */
    [data-toggled*="-close"] .app-sidebar .side-menu__label,
    [data-toggled*="closed"] .app-sidebar .side-menu__label,
    [data-toggled="close"] .app-sidebar .side-menu__label,
    [data-toggled="closed"] .app-sidebar .side-menu__label,
    [data-toggled*="-close"] .app-sidebar .slide__category,
    [data-toggled*="closed"] .app-sidebar .slide__category,
    [data-toggled="close"] .app-sidebar .slide__category,
    [data-toggled*="-close"] .app-sidebar .side-menu__angle,
    [data-toggled*="closed"] .app-sidebar .side-menu__angle,
    [data-toggled="close"] .app-sidebar .side-menu__angle {
        display: none !important;
        height: 0 !important;
        width: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: hidden !important;
    }

    /* Center search icon in collapsed mode */
    [data-toggled*="-close"] .app-sidebar .sb-search-wrapper,
    [data-toggled="close"] .app-sidebar .sb-search-wrapper {
        justify-content: center !important;
        padding: 0 !important;
        height: 52px !important;
        background: transparent !important;
        border: none !important;
    }

    [data-toggled*="-close"] .app-sidebar .sb-search-input,
    [data-toggled="close"] .app-sidebar .sb-search-input {
        display: none !important;
        width: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        background: transparent !important;
    }

    [data-toggled*="-close"] .app-sidebar .sb-search-icon,
    [data-toggled="close"] .app-sidebar .sb-search-icon {
        position: static !important;
        transform: none !important;
        margin: 0 !important;
        padding: 0 !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
        color: var(--sb-text-muted) !important;
    }

    /* Double menu: keep logo only, hide search and company text */
    html[data-vertical-style="doublemenu"] .app-sidebar .sb-search-wrapper {
        display: none !important;
    }

    html[data-vertical-style="doublemenu"] .app-sidebar .sb-hillbcs-info {
        display: none !important;
    }

    html[data-vertical-style="doublemenu"] .app-sidebar .sb-profile .sb-hillbcs-logo {
        justify-content: left !important;
        width: 40px !important;
        height: 40px !important;
    }

    html[data-vertical-style="doublemenu"] .app-sidebar .slide-menu.child1 {
        border-inline-start: 1px solid rgba(100, 116, 139, 0.22) !important;
        margin-inline-start: 0.35rem !important;
        padding-inline-start: 0.75rem !important;
    }

    html.dark[data-vertical-style="doublemenu"] .app-sidebar .slide-menu.child1 {
        border-inline-start-color: rgba(148, 163, 184, 0.3) !important;
    }

    html.dark[data-vertical-style="doublemenu"] .app-sidebar .slide-menu.child1,
    body.dark-theme[data-vertical-style="doublemenu"] .app-sidebar .slide-menu.child1,
    html[data-theme-mode="dark"][data-vertical-style="doublemenu"] .app-sidebar .slide-menu.child1,
    html.dark[data-vertical-style="doublemenu"] .app-sidebar .double-menu-active>.slide-menu.child1,
    html[data-theme-mode="dark"][data-vertical-style="doublemenu"] .app-sidebar .double-menu-active>.slide-menu.child1 {
        background: #1a1c1e !important;
        background-color: #1a1c1e !important;
    }

    html.dark[data-vertical-style="doublemenu"] .app-sidebar,
    body.dark-theme[data-vertical-style="doublemenu"] .app-sidebar,
    html[data-theme-mode="dark"][data-vertical-style="doublemenu"] .app-sidebar,
    html.dark[data-vertical-style="doublemenu"] .app-sidebar .main-sidebar,
    body.dark-theme[data-vertical-style="doublemenu"] .app-sidebar .main-sidebar,
    html[data-theme-mode="dark"][data-vertical-style="doublemenu"] .app-sidebar .main-sidebar,
    html.dark[data-vertical-style="doublemenu"] .app-sidebar .main-menu-container,
    body.dark-theme[data-vertical-style="doublemenu"] .app-sidebar .main-menu-container,
    html[data-theme-mode="dark"][data-vertical-style="doublemenu"] .app-sidebar .main-menu-container,
    html.dark[data-vertical-style="doublemenu"][data-menu-styles="light"] .app-sidebar,
    html.dark[data-vertical-style="doublemenu"][data-menu-styles="light"] .app-sidebar .main-sidebar,
    html.dark[data-vertical-style="doublemenu"][data-menu-styles="light"] .app-sidebar .main-menu-container,
    html[data-theme-mode="dark"][data-vertical-style="doublemenu"][data-menu-styles="light"] .app-sidebar,
    html[data-theme-mode="dark"][data-vertical-style="doublemenu"][data-menu-styles="light"] .app-sidebar .main-sidebar,
    html[data-theme-mode="dark"][data-vertical-style="doublemenu"][data-menu-styles="light"] .app-sidebar .main-menu-container {
        background: #1a1c1e !important;
        background-color: #1a1c1e !important;
    }

    html.dark[data-vertical-style="doublemenu"] .app-sidebar .slide-menu.child1 .side-menu__item,
    html[data-theme-mode="dark"][data-vertical-style="doublemenu"] .app-sidebar .slide-menu.child1 .side-menu__item,
    html.dark[data-vertical-style="doublemenu"] .app-sidebar .slide.side-menu__label1 a,
    html[data-theme-mode="dark"][data-vertical-style="doublemenu"] .app-sidebar .slide.side-menu__label1 a {
        color: rgba(226, 232, 240, 0.92) !important;
    }


    html.dark .app-sidebar:not(.custom-theme-active) .slide-left,
    html.dark[data-menu-styles="light"] .app-sidebar:not(.custom-theme-active) .slide-left,
    html.dark .app-sidebar:not(.custom-theme-active) .slide-right,
    html.dark[data-menu-styles="light"] .app-sidebar:not(.custom-theme-active) .slide-right {
        background: rgb(var(--body-bg)) !important;
    }

    /* Sidebar Glassmorphism & Animations */
    html.dark .app-sidebar {
        background: rgba(26, 28, 30, 0.92) !important;
        backdrop-filter: blur(12px) !important;
    }

    .side-menu__item {
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), background 0.2s ease !important;
    }


    .side-menu__item:hover {
        transform: translateX(4px);
    }


    /* Pulsing Glow for Active Icons */
    .side-menu__item.active-menu .side-menu__icon {
        animation: active-glow 2.5s infinite ease-in-out;
    }

    @keyframes active-glow {

        0%,
        100% {
            filter: drop-shadow(0 0 2px var(--sb-accent));
        }

        50% {
            filter: drop-shadow(0 0 8px var(--sb-accent));
        }
    }

    /* Hard-Lock Sidebar to Collapsed Mode at all times */
    [data-icon-overlay="open"] .app-sidebar {
        width: 80px !important;
    }

    [data-icon-overlay="open"] .app-sidebar .main-sidebar-header,
    [data-icon-overlay="open"] .app-sidebar .main-sidebar,
    [data-icon-overlay="open"] .app-sidebar .sidebar-nav {
        width: 80px !important;
    }

    [data-icon-overlay="open"] .app-sidebar .side-menu__label,
    [data-icon-overlay="open"] .app-sidebar .sb-profile-info,
    [data-icon-overlay="open"] .app-sidebar .slide__category,
    [data-icon-overlay="open"] .app-sidebar .side-menu__angle,
    [data-icon-overlay="open"] .app-sidebar .sb-search-input {
        display: none !important;
    }

    [data-icon-overlay="open"] .app-sidebar .sb-profile {
        justify-content: center !important;
        padding: 0 !important;
    }

    [data-icon-overlay="open"] .app-sidebar .side-menu__item {
        justify-content: center !important;
        padding: 0 !important;
    }

    [data-icon-overlay="open"] .app-sidebar .side-menu__item:hover {
        padding: 0 !important;
    }

    [data-icon-overlay="open"] .app-sidebar .side-menu__icon {
        margin: 0 !important;
    }

    /* Final Branding Visibility Styling */
    html body .app-sidebar .sidebar-branding-block .brand-main {
        color: #ffffff !important;
        white-space: nowrap !important;
    }

    html body .app-sidebar .sidebar-branding-block .brand-sub {
        color: rgba(255, 255, 255, 0.5) !important;
        white-space: nowrap !important;
    }

    /* Standard Responsive Toggling */
    :is([data-toggled="close"],
        [data-toggled*="-close"],
        [data-toggled*="closed"]) .sidebar-brand-text {
        display: none !important;
    }

    :is([data-toggled="close"],
        [data-toggled*="-close"],
        [data-toggled*="closed"]) .sidebar-branding-block {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    /* High-Impact Interactive States (Indigo Accents) */
    .app-sidebar .side-menu__item:hover {
        background: var(--sb-item-hover) !important;
        background-color: var(--sb-item-hover) !important;
        color: var(--sb-text-active) !important;
    }

    .app-sidebar .side-menu__item.active-menu {
        background: var(--sb-item-active) !important;
        background-color: var(--sb-item-active) !important;
        color: var(--sb-text-active) !important;
        border-right: 3px solid var(--sb-accent) !important;
    }

    /* Absolute Label Transparency (No Gray Box) */
    .app-sidebar .side-menu__label,
    .app-sidebar .side-menu__item span,
    .app-sidebar .side-menu__item i {
        background: transparent !important;
        background-color: transparent !important;
        box-shadow: none !important;
    }

    /* Branding Colors Visibility Fix */
    .brand-main {
        color: var(--sb-text) !important;
    }

    .brand-sub {
        color: var(--sb-text-muted) !important;
    }
</style>
