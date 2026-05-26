<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/assets/raw/new.png" type="image/x-icon">
    <title>{{ config('app.name', 'Hill Business Consulting Services') }} - Chat</title>

    @vite(['resources/js/app.js'])
    <script>
        // Initialize theme from localStorage before page renders to prevent flash
        (function() {
            const theme = localStorage.getItem('chat-theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
                if (!theme) localStorage.setItem('chat-theme', 'light');
            }
        })();
    </script>
    <script>
        tailwind = window.tailwind || {};
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#1d9bd1",
                        "background-light": "#ffffff",
                        "background-dark": "#222529",
                        "sidebar-light": "#3F0E40",
                        "sidebar-dark": "#2d2d30",
                        "sidemenu-light": "#f1f5f9",
                        "sidemenu-dark": "#1a1d21",
                        "chat-light": "#ffffff",
                        "chat-dark": "#222529",
                        "input-light": "#ffffff",
                        "input-dark": "#383838",
                    },
                    fontFamily: {
                        display: ["Poppins", "sans-serif"],
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Shepherd.js Tour --}}
    <link rel="stylesheet" href="/assets/libs/shepherd.js/css/shepherd.css">
    <script src="/assets/libs/shepherd.js/js/shepherd.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        html,
        body {
            height: 100%;
            width: 100%;
            overflow: hidden;
            overflow-x: clip;
        }
        
        .h-dvh {
            height: 100vh; 
            height: 100dvh;
        }
        
        @supports not (height: 100dvh) {
            :root {
                --vh: 1vh;
            }
            .h-dvh {
                height: calc(var(--vh, 1vh) * 100);
            }
        }
        
        /* Allow fixed elements to extend beyond viewport */
        body {
            position: relative;
        }

        body {
            font-family: "Poppins", sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        /* Dark mode scrollbar */
        .dark ::-webkit-scrollbar {
            width: 6px;
        }

        /* Message body - remove any default margins for Slack-like tight spacing */
        .chat-message-body {
            margin: 0 !important;
            padding: 0 !important;
        }
        /* Remove default p tag margins in messages */
        #messages-container p {
            margin: 0 !important;
        }
        #messages-container .flex.flex-col > div:first-child {
            margin-bottom: 0 !important;
        }
        
        /* Compact chat messages - Slack-like density */
        #messages-container > .group {
            padding: 0.25rem 0.75rem !important;
        }
        #messages-container > .group img.rounded-full {
            width: 2rem !important;
            height: 2rem !important;
        }
        #messages-container .chat-message-body {
            font-size: 0.875rem !important;
        }
        #messages-container .font-bold {
            font-size: 0.875rem !important;
        }
        #messages-container .msg-timestamp {
            font-size: 0.7rem !important;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 9999px;
        }

        .dark ::-webkit-scrollbar-track {
            background: transparent;
        }

        /* Light mode scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 9999px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        /* ===== DARK MODE MODALS - CONSISTENT STYLING ===== */
        /* All chat modals base */
        html.dark [data-chat-modal] > div {
            background-color: #222222 !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
        }
        
        /* Modal inputs and selects */
        html.dark [data-chat-modal] input,
        html.dark [data-chat-modal] select,
        html.dark [data-chat-modal] textarea {
            background-color: #2d2d2d !important;
            border-color: rgba(255,255,255,0.15) !important;
            color: #ffffff !important;
        }
        html.dark [data-chat-modal] input::placeholder,
        html.dark [data-chat-modal] textarea::placeholder {
            color: rgba(255,255,255,0.4) !important;
        }
        html.dark [data-chat-modal] input:focus,
        html.dark [data-chat-modal] select:focus,
        html.dark [data-chat-modal] textarea:focus {
            border-color: #1d9bd1 !important;
            background-color: #333333 !important;
        }
        
        /* Modal select dropdown options */
        html.dark [data-chat-modal] select option {
            background-color: #222222 !important;
            color: #ffffff !important;
        }
        
        /* Modal close button hover */
        html.dark [data-chat-modal] [data-chat-modal-close]:hover {
            background-color: rgba(255,255,255,0.1) !important;
        }
        
        /* Modal borders */
        html.dark [data-chat-modal] .border-gray-200,
        html.dark [data-chat-modal] .border-b {
            border-color: rgba(255,255,255,0.1) !important;
        }
        
        /* Modal text colors */
        html.dark [data-chat-modal] .text-gray-900 {
            color: #ffffff !important;
        }
        html.dark [data-chat-modal] .text-gray-700,
        html.dark [data-chat-modal] .text-gray-600 {
            color: rgba(255,255,255,0.8) !important;
        }
        html.dark [data-chat-modal] .text-gray-500,
        html.dark [data-chat-modal] .text-gray-400 {
            color: rgba(255,255,255,0.5) !important;
        }
        
        /* Modal hover states */
        html.dark [data-chat-modal] .hover\:bg-gray-100:hover,
        html.dark [data-chat-modal] .hover\:bg-gray-50:hover {
            background-color: rgba(255,255,255,0.1) !important;
        }
        
        /* Search modal specific */
        html.dark [data-chat-modal="search-messages"] #search-results-container {
            background-color: #222222 !important;
        }
        html.dark [data-chat-modal="search-messages"] .search-result-item {
            background-color: #2a2a2a !important;
            border-color: rgba(255,255,255,0.08) !important;
        }
        html.dark [data-chat-modal="search-messages"] .search-result-item:hover {
            background-color: #333333 !important;
        }
        
        /* Add member modal specific */
        html.dark [data-chat-modal="add-member-quick"] .add-member-user:hover {
            background-color: #2d2d2d !important;
        }
        html.dark [data-chat-modal="add-member-quick"] .add-member-user span {
            color: rgba(255,255,255,0.9) !important;
        }

        /* ===== HEADER SEARCH BAR DARK MODE HOVER ===== */
        html.dark .chat-v2-header button[data-open-chat-modal="search-messages"]:hover {
            background-color: rgba(255,255,255,0.1) !important;
        }

        /* ===== SWEETALERT2 DARK MODE STYLES ===== */
        html.dark .swal2-popup {
            background-color: #2d2d30 !important;
            color: #d1d2d3 !important;
        }
        html.dark .swal2-title {
            color: #ffffff !important;
        }
        html.dark .swal2-html-container {
            color: #d1d2d3 !important;
        }
        html.dark .swal2-html-container p {
            color: #9ca3af !important;
        }
        html.dark .swal2-html-container label {
            color: #d1d2d3 !important;
        }
        html.dark .swal2-html-container .text-gray-700 {
            color: #d1d2d3 !important;
        }
        html.dark .swal2-html-container .text-gray-500 {
            color: #9ca3af !important;
        }
        html.dark .swal2-html-container .text-gray-400 {
            color: #6b7280 !important;
        }
        html.dark .swal2-input, html.dark .swal2-textarea, html.dark .swal2-select {
            background-color: #383838 !important;
            color: #ffffff !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        html.dark .swal2-input::placeholder, html.dark .swal2-textarea::placeholder {
            color: #6b7280 !important;
        }
        html.dark .swal2-input:focus, html.dark .swal2-textarea:focus {
            border-color: #1d9bd1 !important;
            box-shadow: 0 0 0 3px rgba(29, 155, 209, 0.2) !important;
        }
        html.dark .swal2-validation-message {
            background-color: #383838 !important;
            color: #f87171 !important;
        }
        /* SweetAlert modal buttons and chips */
        html.dark .swal2-html-container .border-gray-200 {
            border-color: rgba(255,255,255,0.1) !important;
        }
        html.dark .swal2-html-container .bg-gray-50 {
            background-color: #383838 !important;
        }
        html.dark .swal2-html-container .text-gray-600 {
            color: #9ca3af !important;
        }
        html.dark .swal2-html-container .text-gray-900 {
            color: #ffffff !important;
        }
        html.dark .swal2-html-container .hover\:border-indigo-400:hover {
            border-color: #1d9bd1 !important;
        }
        html.dark .swal2-html-container .hover\:bg-indigo-50:hover {
            background-color: rgba(29, 155, 209, 0.15) !important;
        }
        html.dark .swal2-html-container .bg-indigo-50 {
            background-color: rgba(29, 155, 209, 0.15) !important;
        }
        html.dark .swal2-html-container .border-indigo-300 {
            border-color: rgba(29, 155, 209, 0.4) !important;
        }
        html.dark .swal2-html-container .text-indigo-700 {
            color: #60a5fa !important;
        }
        html.dark .swal2-html-container .text-indigo-400 {
            color: #93c5fd !important;
        }
        /* Search results in modals */
        html.dark #swal-search-results {
            background-color: #383838 !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        html.dark .swal-search-result:hover, html.dark .swal-contact-btn:hover {
            background-color: rgba(29, 155, 209, 0.15) !important;
        }
        /* Selected state */
        html.dark .swal2-html-container .selected {
            background-color: rgba(29, 155, 209, 0.2) !important;
            border-color: #1d9bd1 !important;
            color: #60a5fa !important;
        }

        /* ===== LIGHT/DARK THEME OVERRIDES - Slack-like Color Scheme ===== */
        /* Light mode - Light sidebar for hillbcs-hire */
        html:not(.dark) body { background-color: #ffffff !important; color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-wrapper { background-color: #ffffff !important; color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-sidemenu { background-color: #f8fafc !important; border-right: 1px solid #e2e8f0 !important; }
        html:not(.dark) .chat-v2-sidebar { background-color: #f8fafc !important; border-right: 1px solid #e2e8f0 !important; }
        html:not(.dark) .chat-v2-main { background-color: #ffffff !important; }
        html:not(.dark) .chat-v2-header { background-color: #ffffff !important; border-color: #e8e8e8 !important; box-shadow: 0 1px 0 rgba(0,0,0,0.05) !important; }
        html:not(.dark) .chat-v2-right { background-color: #ffffff !important; border-left: 1px solid #e8e8e8 !important; }
        html:not(.dark) .chat-v2-composer { background-color: #ffffff !important; border-color: #e8e8e8 !important; }
        html:not(.dark) .chat-v2-input { background-color: #ffffff !important; color: #1d1c1d !important; border: 1px solid #868686 !important; }
        html:not(.dark) .chat-v2-footer { background-color: #ffffff !important; border-top: 1px solid #e8e8e8 !important; }
        html:not(.dark) .chat-v2-text { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-text-muted { color: #616061 !important; }
        
        /* Light mode text colors for all elements */
        html:not(.dark) h1, html:not(.dark) h2, html:not(.dark) h3, html:not(.dark) h4, html:not(.dark) h5, html:not(.dark) h6 { color: #1f2937 !important; }
        html:not(.dark) p { color: #374151 !important; }
        html:not(.dark) .text-white { color: #1f2937 !important; }
        html:not(.dark) .bg-primary .text-white, html:not(.dark) .bg-primary.text-white, html:not(.dark) button.bg-primary { color: #ffffff !important; }
        html:not(.dark) .text-white\/90 { color: #374151 !important; }
        html:not(.dark) .text-white\/80 { color: #4b5563 !important; }
        html:not(.dark) .text-white\/70 { color: #6b7280 !important; }
        html:not(.dark) .text-white\/60 { color: #9ca3af !important; }
        html:not(.dark) .text-white\/50 { color: #9ca3af !important; }
        html:not(.dark) .text-white\/40 { color: #d1d5db !important; }
        html:not(.dark) .chat-v2-sidebar h1, html:not(.dark) .chat-v2-sidebar p { color: inherit !important; }
        html:not(.dark) .chat-v2-sidebar .text-sm.font-medium { color: #374151 !important; }
        html:not(.dark) .chat-v2-sidebar .text-xs { color: #6b7280 !important; }
        html:not(.dark) .chat-v2-right h3, html:not(.dark) .chat-v2-right p { color: inherit !important; }
        html:not(.dark) .chat-v2-right .text-sm.font-medium { color: #374151 !important; }
        /* Light mode text colors - Slack style */
        html:not(.dark) h1, html:not(.dark) h2, html:not(.dark) h3, html:not(.dark) h4, html:not(.dark) h5, html:not(.dark) h6 { color: #1d1c1d !important; }
        html:not(.dark) p { color: #1d1c1d !important; }
        
        /* Light mode main content area - dark text on white background */
        html:not(.dark) .chat-v2-main { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-main h1, html:not(.dark) .chat-v2-main h2, html:not(.dark) .chat-v2-main h3 { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-main p { color: #616061 !important; }
        html:not(.dark) .chat-v2-main .text-gray-800 { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-main .text-gray-500 { color: #616061 !important; }
        html:not(.dark) .chat-v2-main .font-semibold { color: #1d1c1d !important; }
        
        /* Light mode - override text-white classes in main area only */
        html:not(.dark) .chat-v2-main .text-white { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-main .text-white\/90 { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-main .text-white\/80 { color: #616061 !important; }
        html:not(.dark) .chat-v2-main .text-white\/70 { color: #616061 !important; }
        html:not(.dark) .chat-v2-main .text-white\/60 { color: #868686 !important; }
        html:not(.dark) .chat-v2-main .text-white\/50 { color: #868686 !important; }
        html:not(.dark) .chat-v2-main .text-white\/40 { color: #ababad !important; }
        
        /* Light mode header */
        html:not(.dark) .chat-v2-header h2 { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-header .text-gray-500 { color: #616061 !important; }
        html:not(.dark) .chat-v2-header .text-gray-600 { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-header .bi { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-header button { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-header button .bi { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-header button:hover { background-color: rgba(0,0,0,0.05) !important; }
        
        /* Dark mode header buttons hover */
        html.dark .chat-v2-header button:hover { background-color: #383838 !important; }
        
        /* Dark mode right sidebar member dropdown */
        html.dark .member-action-dropdown,
        html.dark .own-status-dropdown {
            background-color: #222222 !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        html.dark .member-action-dropdown button,
        html.dark .own-status-dropdown button {
            color: rgba(255,255,255,0.8) !important;
        }
        html.dark .member-action-dropdown button:hover,
        html.dark .own-status-dropdown button:hover {
            background-color: rgba(255,255,255,0.1) !important;
        }
        
        /* Light mode right sidebar member dropdown */
        html:not(.dark) .member-action-dropdown,
        html:not(.dark) .own-status-dropdown {
            background-color: #ffffff !important;
            border-color: rgba(0,0,0,0.1) !important;
        }
        html:not(.dark) .member-action-dropdown button,
        html:not(.dark) .own-status-dropdown button {
            color: #374151 !important;
        }
        html:not(.dark) .member-action-dropdown button:hover,
        html:not(.dark) .own-status-dropdown button:hover {
            background-color: rgba(0,0,0,0.05) !important;
        }
        
        /* Dark mode add member button hover in members panel */
        html.dark .chat-v2-right button[data-open-chat-modal="add-member-quick"]:hover,
        html.dark [data-open-chat-modal="add-member-quick"]:hover {
            background-color: rgba(255,255,255,0.1) !important;
        }
        
        /* Dark mode right panel collapse button hover */
        html.dark .chat-v2-right button:hover {
            background-color: #383838 !important;
        }
        
        /* Dark mode right panel expand button hover */
        html.dark .right-panel-toggle-btn:hover {
            background-color: #383838 !important;
        }
        
        /* Light mode sidebar text - dark text on light background */
        html:not(.dark) .chat-v2-sidebar h1, html:not(.dark) .chat-v2-sidebar p { color: #1f2937 !important; }
        html:not(.dark) .chat-v2-sidebar .text-sm.font-medium { color: #374151 !important; }
        html:not(.dark) .chat-v2-sidebar .text-xs { color: #6b7280 !important; }
        html:not(.dark) .chat-v2-sidebar .text-gray-900 { color: #1f2937 !important; }
        html:not(.dark) .chat-v2-sidebar .text-gray-700 { color: #374151 !important; }
        html:not(.dark) .chat-v2-sidebar .text-gray-500 { color: #6b7280 !important; }
        html:not(.dark) .chat-v2-right h3, html:not(.dark) .chat-v2-right p { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-right .text-sm.font-medium { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-right .text-gray-500 { color: #616061 !important; }
        html:not(.dark) .chat-v2-right .text-gray-400 { color: #868686 !important; }
        html:not(.dark) .chat-v2-right .bi { color: #616061 !important; }
        
        /* Light mode message bubbles */
        html:not(.dark) .message-bubble { background-color: #f8f8f8 !important; color: #1d1c1d !important; }
        html:not(.dark) .message-bubble.own { background-color: #1264a3 !important; color: #ffffff !important; }
        html:not(.dark) .message-bubble p { color: inherit !important; }
        
        /* Light mode icons - sidemenu icons */
        html:not(.dark) .bi { color: inherit; }
        html:not(.dark) .chat-v2-sidemenu .bi { color: #6b7280 !important; }
        html:not(.dark) .chat-v2-sidemenu button:hover .bi { color: #2b2bee !important; }
        html:not(.dark) .chat-v2-sidebar .bi { color: #6b7280 !important; }

        /* Dark mode (.dark class on html) */
        html.dark body { background-color: #101022 !important; color: #DCDDDE !important; }
        html.dark .chat-v2-wrapper { background-color: #101022 !important; color: #DCDDDE !important; }
        html.dark .chat-v2-sidemenu { background-color: #090911 !important; }
        html.dark .chat-v2-sidebar { background-color: #111118 !important; }
        html.dark .chat-v2-main { background-color: #101022 !important; }
        html.dark .chat-v2-header { background-color: transparent !important; border-color: rgba(255,255,255,0.1) !important; }
        html.dark .chat-v2-right { background-color: #111118 !important; }
        html.dark .chat-v2-right h2 { color: #ffffff !important; }
        html.dark .chat-v2-right .text-base.font-semibold { color: #ffffff !important; }
        html:not(.dark) .chat-v2-sidemenu .bi { color: rgba(255,255,255,0.8) !important; }
        html:not(.dark) .chat-v2-sidemenu button:hover .bi { color: #ffffff !important; }
        
        /* Light mode main area icons - dark on white */
        html:not(.dark) .chat-v2-main .bi { color: #616061 !important; }
        html:not(.dark) .chat-v2-main .text-primary .bi { color: inherit !important; }
        html:not(.dark) .chat-v2-main button .bi { color: #616061 !important; }
        html:not(.dark) .chat-v2-main .bi-list { color: #1d1c1d !important; }
        
        /* ===== LIGHT MODE - COMPREHENSIVE TEXT VISIBILITY FIXES ===== */
        /* Messages container - all text dark on white */
        html:not(.dark) #messages-container { color: #1d1c1d !important; }
        html:not(.dark) #messages-container * { color: #1d1c1d !important; }
        html:not(.dark) #messages-container .text-gray-900 { color: #1d1c1d !important; }
        html:not(.dark) #messages-container .text-gray-800 { color: #1d1c1d !important; }
        html:not(.dark) #messages-container .text-gray-700 { color: #374151 !important; }
        html:not(.dark) #messages-container .text-gray-600 { color: #4b5563 !important; }
        html:not(.dark) #messages-container .text-gray-500 { color: #616061 !important; }
        html:not(.dark) #messages-container .text-gray-400 { color: #868686 !important; }
        html:not(.dark) #messages-container .font-bold { color: #1d1c1d !important; }
        html:not(.dark) #messages-container .font-semibold { color: #1d1c1d !important; }
        html:not(.dark) #messages-container p { color: #374151 !important; }
        html:not(.dark) #messages-container .chat-message-body { color: #374151 !important; }
        html:not(.dark) #messages-container .msg-timestamp { color: #868686 !important; }
        
        /* Message hover and action buttons */
        html:not(.dark) #messages-container .group:hover { background-color: #f5f5f5 !important; }
        html:not(.dark) #messages-container .msg-action-btn { color: #616061 !important; background-color: #ffffff !important; }
        html:not(.dark) #messages-container .msg-action-btn:hover { background-color: #f0f0f0 !important; }
        html:not(.dark) #messages-container .msg-action-btn .bi { color: #616061 !important; }
        html:not(.dark) #messages-container .msg-more-dropdown { background-color: #ffffff !important; border-color: #e5e7eb !important; }
        html:not(.dark) #messages-container .msg-more-dropdown button { color: #374151 !important; }
        html:not(.dark) #messages-container .msg-more-dropdown button:hover { background-color: #f5f5f5 !important; }
        html:not(.dark) #messages-container .bg-white { background-color: #ffffff !important; }
        
        /* Welcome/empty state */
        html:not(.dark) .chat-v2-main .text-gray-800 { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-main .text-gray-600 { color: #4b5563 !important; }
        html:not(.dark) .chat-v2-main .text-gray-500 { color: #616061 !important; }
        html:not(.dark) .chat-v2-main .text-gray-400 { color: #868686 !important; }
        
        /* Composer area */
        html:not(.dark) .chat-v2-composer { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-composer .bi { color: #616061 !important; }
        html:not(.dark) .chat-v2-composer button { color: #616061 !important; }
        html:not(.dark) .chat-v2-composer input { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-composer textarea { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-composer ::placeholder { color: #868686 !important; }
        
        /* Dropdowns and menus in light mode */
        html:not(.dark) #attachment-menu { background-color: #ffffff !important; border-color: #e5e7eb !important; }
        html:not(.dark) #attachment-menu button { color: #374151 !important; }
        html:not(.dark) #attachment-menu button:hover { background-color: #f5f5f5 !important; }
        html:not(.dark) #attachment-menu .bi { color: #616061 !important; }
        html:not(.dark) #attachment-menu p { color: #374151 !important; }
        
        html:not(.dark) #editor-selector-menu { background-color: #ffffff !important; border-color: #e5e7eb !important; }
        html:not(.dark) #editor-selector-menu button { color: #374151 !important; }
        html:not(.dark) #editor-selector-menu button:hover { background-color: #f5f5f5 !important; }
        
        /* Pinned messages */
        html:not(.dark) #pinned-message-bar { background-color: #ffffff !important; }
        html:not(.dark) #pinned-message-bar * { color: #374151 !important; }
        html:not(.dark) #pinned-message-bar .text-primary { color: #1264a3 !important; }
        html:not(.dark) #pinned-messages-panel { background-color: #ffffff !important; }
        html:not(.dark) #pinned-messages-panel * { color: #374151 !important; }
        html:not(.dark) #pinned-messages-panel h3 { color: #1d1c1d !important; }
        
        /* Right panel members list */
        html:not(.dark) .chat-v2-right { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-right h3 { color: #616061 !important; }
        html:not(.dark) .chat-v2-right .font-medium { color: #1d1c1d !important; }
        html:not(.dark) .chat-v2-right .text-xs { color: #868686 !important; }
        html:not(.dark) .chat-v2-right .member-item { color: #374151 !important; }
        html:not(.dark) .chat-v2-right .member-item:hover { background-color: #f5f5f5 !important; }
        html:not(.dark) .chat-v2-right .member-action-dropdown { background-color: #ffffff !important; }
        html:not(.dark) .chat-v2-right .member-action-dropdown button { color: #374151 !important; }
        html:not(.dark) .chat-v2-right .member-action-dropdown button:hover { background-color: #f5f5f5 !important; }
        
        /* Cards and boxes in main area */
        html:not(.dark) .chat-v2-main .bg-white { background-color: #ffffff !important; border-color: #e5e7eb !important; }
        html:not(.dark) .chat-v2-main .rounded-xl { border-color: #e5e7eb !important; }
        html:not(.dark) .chat-v2-main .bg-blue-100 { background-color: #dbeafe !important; }
        html:not(.dark) .chat-v2-main .bg-green-100 { background-color: #dcfce7 !important; }
        html:not(.dark) .chat-v2-main .bg-indigo-100 { background-color: #e0e7ff !important; }
        
        /* Light mode sidebar hover states - proper colors for light background */
        html:not(.dark) .chat-v2-sidebar .bg-slate-100 { background-color: #e2e8f0 !important; }
        html:not(.dark) .chat-v2-sidebar .bg-slate-100:hover { background-color: #cbd5e1 !important; }
        html:not(.dark) .chat-v2-sidebar .hover\:bg-slate-100:hover { background-color: #e2e8f0 !important; }
        html:not(.dark) .chat-v2-sidemenu .bg-slate-100 { background-color: #e2e8f0 !important; }
        html:not(.dark) .chat-v2-sidemenu .bg-indigo-100 { background-color: #e0e7ff !important; }
        html:not(.dark) .chat-v2-sidemenu .bg-amber-100 { background-color: #fef3c7 !important; }
        
        /* Light mode sidebar - ALL text must be white/light on olive background */
        /* REMOVED: These rules were causing white text on light background
        html:not(.dark) .chat-v2-sidebar { color: #ffffff !important; }
        html:not(.dark) .chat-v2-sidebar * { color: rgba(255,255,255,0.9) !important; }
        html:not(.dark) .chat-v2-sidebar .text-gray-400 { color: rgba(255,255,255,0.5) !important; }
        html:not(.dark) .chat-v2-sidebar .text-gray-600 { color: rgba(255,255,255,0.7) !important; }
        html:not(.dark) .chat-v2-sidebar .font-semibold { color: #ffffff !important; }
        html:not(.dark) .chat-v2-sidebar .font-medium { color: rgba(255,255,255,0.9) !important; }
        html:not(.dark) .chat-v2-sidebar .text-xs { color: rgba(255,255,255,0.6) !important; }
        html:not(.dark) .chat-v2-sidebar a { color: rgba(255,255,255,0.9) !important; }
        html:not(.dark) .chat-v2-sidebar a:hover { color: #ffffff !important; }
        html:not(.dark) .chat-v2-sidebar .bi { color: rgba(255,255,255,0.7) !important; }
        html:not(.dark) .chat-v2-sidebar button { color: rgba(255,255,255,0.9) !important; }
        */
        
        /* Light mode sidebar - dark text for light background */
        html:not(.dark) .chat-v2-sidebar { color: #374151 !important; background-color: #f8fafc !important; }
        html:not(.dark) .chat-v2-sidebar .text-gray-400 { color: #9ca3af !important; }
        html:not(.dark) .chat-v2-sidebar .text-gray-600 { color: #4b5563 !important; }
        html:not(.dark) .chat-v2-sidebar .font-semibold { color: #1f2937 !important; }
        html:not(.dark) .chat-v2-sidebar .font-medium { color: #374151 !important; }
        html:not(.dark) .chat-v2-sidebar .text-xs { color: #6b7280 !important; }
        html:not(.dark) .chat-v2-sidebar a { color: #374151 !important; }
        html:not(.dark) .chat-v2-sidebar a:hover { color: #1f2937 !important; }
        html:not(.dark) .chat-v2-sidebar .bi { color: #6b7280 !important; }
        html:not(.dark) .chat-v2-sidebar button { color: #374151 !important; }
        html:not(.dark) .chat-v2-sidebar .hover\:bg-slate-100:hover { background-color: #e2e8f0 !important; }
        html:not(.dark) .chat-v2-sidebar .hover\:bg-white\/5:hover { background-color: #e2e8f0 !important; }
        
        /* Light mode sidebar footer (user profile area) - dark text on light background */
        html:not(.dark) .chat-v2-footer { background-color: #f1f5f9 !important; border-top: 1px solid #e2e8f0 !important; }
        html:not(.dark) .chat-v2-sidebar .chat-v2-footer { background-color: #f1f5f9 !important; }
        html:not(.dark) .chat-v2-sidebar .chat-v2-footer * { color: #374151 !important; }
        html:not(.dark) .chat-v2-sidebar .chat-v2-footer .chat-v2-text { color: #1f2937 !important; }
        html:not(.dark) .chat-v2-sidebar .chat-v2-footer .chat-v2-text-muted { color: #6b7280 !important; }
        html:not(.dark) .chat-v2-sidebar .chat-v2-footer .bi { color: #6b7280 !important; }
        html:not(.dark) .chat-v2-sidebar .chat-v2-footer:hover { background-color: #e2e8f0 !important; }
        
        /* Light mode sidebar section headers - dark text on light background */
        html:not(.dark) .chat-v2-sidebar .uppercase { color: #6b7280 !important; }
        html:not(.dark) .chat-v2-sidebar .section-chevron { color: #9ca3af !important; }
        html:not(.dark) .chat-v2-sidebar .sidebar-section-header { color: #374151 !important; }
        html:not(.dark) .chat-v2-sidebar .sidebar-section-header span { color: #6b7280 !important; }
        html:not(.dark) .chat-v2-sidebar .sidebar-section-header .bi { color: #9ca3af !important; }
        html:not(.dark) .chat-v2-sidebar .sidebar-section-header:hover span { color: #374151 !important; }

        /* Dark mode - Slack-inspired: Dark sidebar (#2d2d30), Dark main (#222529), Teal accent (#1d9bd1) */
        html.dark body { background-color: #222529 !important; color: #d1d2d3 !important; }
        html.dark .chat-v2-wrapper { background-color: #222529 !important; color: #d1d2d3 !important; }
        html.dark .chat-v2-sidemenu { background-color: #1a1d21 !important; }
        html.dark .chat-v2-sidebar { background-color: #2d2d30 !important; }
        html.dark .chat-v2-main { background-color: #222529 !important; }
        html.dark .chat-v2-header { background-color: #222529 !important; border-color: rgba(255,255,255,0.1) !important; }
        html.dark .chat-v2-right { background-color: #2d2d30 !important; }
        html.dark .chat-v2-right .text-sm.font-medium { color: #ffffff !important; }
        html.dark .chat-v2-right .member-status { color: rgba(255,255,255,0.6) !important; }
        html.dark .chat-v2-right h3 { color: rgba(255,255,255,0.5) !important; }
        html.dark .chat-v2-right .member-item:hover { background-color: #383838 !important; }
        html.dark .chat-v2-right .member-action-dropdown { background-color: #2d2d30 !important; }
        html.dark .chat-v2-right .member-action-dropdown button:hover { background-color: #383838 !important; }
        html.dark #user-hover-card > div { background-color: #2d2d30 !important; border-color: rgba(255,255,255,0.1) !important; }
        html.dark .chat-v2-main .group:hover { background-color: #2a2d31 !important; }
        html.dark #pinned-message-bar button:hover { background-color: #2a2d31 !important; }
        html.dark #pinned-messages-panel { background-color: #2d2d30 !important; }
        html.dark .chat-v2-header { background-color: #2d2d30 !important; }
        html.dark .chat-v2-header h2 { color: #ffffff !important; }
        html.dark .chat-v2-header .text-gray-900 { color: #ffffff !important; }
        html.dark .chat-v2-header .text-gray-500 { color: rgba(255,255,255,0.5) !important; }
        html.dark #messages-container .text-gray-900 { color: #ffffff !important; }
        html.dark #messages-container .text-gray-700 { color: rgba(255,255,255,0.9) !important; }
        html.dark #messages-container .text-gray-800 { color: #ffffff !important; }
        html.dark #pinned-message-bar > div { background-color: #2d2d30 !important; }
        html.dark #pinned-message-bar .text-gray-700 { color: rgba(255,255,255,0.9) !important; }
        html.dark #pinned-message-bar .text-gray-400 { color: rgba(255,255,255,0.4) !important; }
        html.dark #pinned-message-bar #pinned-message-text { color: #ffffff !important; }
        html.dark .chat-v2-sidebar { background-color: #2d2d30 !important; }
        html.dark .chat-v2-sidebar h1 { color: #ffffff !important; }
        html.dark .chat-v2-sidebar .text-gray-900 { color: #ffffff !important; }
        html.dark .chat-v2-sidebar .text-gray-700 { color: rgba(255,255,255,0.8) !important; }
        html.dark .chat-v2-sidebar .text-gray-500 { color: rgba(255,255,255,0.5) !important; }
        html.dark .chat-v2-sidebar .text-sm.font-medium { color: rgba(255,255,255,0.8) !important; }
        html.dark .chat-v2-sidebar .text-xs.font-bold { color: rgba(255,255,255,0.5) !important; }
        html.dark .chat-v2-sidebar .bg-slate-100 { background-color: #383838 !important; }
        html.dark .chat-v2-sidebar .bg-slate-100:hover { background-color: #424242 !important; }
        html.dark .chat-v2-right .bg-gray-200 { background-color: #383838 !important; }
        html.dark .chat-v2-right .bg-gray-200:hover { background-color: #424242 !important; }
        html.dark .bg-green-100 { background-color: rgba(34, 197, 94, 0.15) !important; }
        html.dark .bg-red-100 { background-color: rgba(239, 68, 68, 0.15) !important; }
        html.dark #messages-container .bg-white { background-color: #2d2d30 !important; }
        html.dark #messages-container .msg-more-dropdown { background-color: #2d2d30 !important; }
        html.dark #messages-container .msg-more-dropdown button { color: rgba(255,255,255,0.8) !important; }
        html.dark #messages-container .msg-more-dropdown button:hover { background-color: #383838 !important; }
        
        /* Fix message action button hover in dark mode */
        html.dark .msg-action-btn:hover { background-color: #383838 !important; }
        
        /* Fix sidebar filter links hover in dark mode */
        html.dark .chat-v2-sidebar a:hover { background-color: #383838 !important; }
        html.dark .chat-v2-sidebar a.bg-primary\/20:hover { background-color: rgba(43, 43, 238, 0.3) !important; }
        html.dark .chat-v2-sidebar button:hover { background-color: #383838 !important; }
        html.dark .chat-v2-sidebar .sidebar-section-header:hover { background-color: #383838 !important; }
        html.dark .chat-v2-sidebar .hover\:bg-slate-100:hover { background-color: #383838 !important; }
        
        html.dark .chat-v2-sidemenu .bg-indigo-100 { background-color: rgba(99, 102, 241, 0.15) !important; }
        html.dark .chat-v2-sidemenu .bg-indigo-100:hover { background-color: rgba(99, 102, 241, 0.25) !important; }
        html.dark .chat-v2-sidemenu .bg-amber-100 { background-color: rgba(245, 158, 11, 0.15) !important; }
        html.dark .chat-v2-sidemenu .bg-amber-100:hover { background-color: rgba(245, 158, 11, 0.25) !important; }
        html.dark .chat-v2-sidemenu .bg-slate-100 { background-color: #383838 !important; }
        html.dark .chat-v2-sidemenu .bg-slate-100:hover { background-color: #424242 !important; }
        
        /* Compose mode dropdown dark mode */
        html.dark #editor-selector-menu { background-color: #2d2d30 !important; border-color: rgba(255,255,255,0.1) !important; }
        html.dark #editor-selector-menu button { color: rgba(255,255,255,0.8) !important; }
        html.dark #editor-selector-menu button:hover { background-color: #383838 !important; }
        
        /* Attachment menu dark mode */
        html.dark #attachment-menu { background-color: #2d2d30 !important; border-color: rgba(255,255,255,0.1) !important; }
        html.dark #attachment-menu button { background-color: transparent !important; }
        html.dark #attachment-menu button:hover { background-color: #383838 !important; }
        html.dark #attachment-menu .text-gray-800 { color: #ffffff !important; }
        html.dark #attachment-menu .text-gray-500 { color: rgba(255,255,255,0.5) !important; }
        html.dark #attachment-menu p { color: inherit !important; }
        
        /* Video preview container dark mode (upload preview in composer) */
        html.dark #video-preview-container { background-color: rgba(255,255,255,0.05) !important; border-color: rgba(255,255,255,0.1) !important; }
        html.dark #video-preview-container .text-gray-800 { color: #ffffff !important; }
        html.dark #video-preview-container .text-gray-500 { color: rgba(255,255,255,0.5) !important; }
        html.dark #video-preview-container .bg-gray-200 { background-color: rgba(255,255,255,0.1) !important; }
        html.dark #video-preview-container p { color: #ffffff !important; }
        html.dark #video-preview-container #video-filename { color: #ffffff !important; }
        html.dark #video-preview-container #video-filesize { color: rgba(255,255,255,0.5) !important; }
        html.dark #video-preview-container #video-progress-text { color: rgba(255,255,255,0.5) !important; }
        html.dark #video-preview-container button:hover { background-color: rgba(239, 68, 68, 0.2) !important; }
        html.dark .bg-gray-100 { background-color: rgba(255,255,255,0.05) !important; }
        
        /* Video preview modal dark mode */
        html.dark #video-preview-modal { background-color: rgba(0,0,0,0.95) !important; }
        html.dark #video-preview-modal .text-white { color: #ffffff !important; }
        html.dark #video-preview-modal .text-white\/70 { color: rgba(255,255,255,0.7) !important; }
        html.dark #video-preview-modal .text-white\/60 { color: rgba(255,255,255,0.6) !important; }
        html.dark #video-preview-modal .text-white\/40 { color: rgba(255,255,255,0.4) !important; }
        html.dark #video-preview-modal #video-preview-filename { color: rgba(255,255,255,0.6) !important; }
        html.dark #video-preview-modal #video-preview-size { color: rgba(255,255,255,0.6) !important; }
        html.dark #video-modal-player-container { background-color: #000000 !important; }
        
        /* Fix white borders/lines in dark mode */
        html.dark .border-gray-200 { border-color: rgba(255,255,255,0.1) !important; }
        html.dark .border-gray-100 { border-color: rgba(255,255,255,0.05) !important; }
        html.dark .divide-gray-200 > * + * { border-color: rgba(255,255,255,0.1) !important; }
        html.dark .divide-gray-100 > * + * { border-color: rgba(255,255,255,0.05) !important; }
        html.dark .border-b { border-color: rgba(255,255,255,0.1) !important; }
        html.dark .border-t { border-color: rgba(255,255,255,0.1) !important; }
        html.dark .border-l { border-color: rgba(255,255,255,0.1) !important; }
        html.dark .border-r { border-color: rgba(255,255,255,0.1) !important; }
        
        /* Pinned messages panel dark mode */
        html.dark #pinned-messages-panel { background-color: #2d2d30 !important; }
        html.dark #pinned-messages-panel .bg-white { background-color: #2d2d30 !important; }
        html.dark #pinned-messages-panel h3 { color: #ffffff !important; }
        html.dark #pinned-messages-panel h3.font-semibold { color: #ffffff !important; }
        html.dark #pinned-messages-panel .font-semibold { color: #ffffff !important; }
        html.dark #pinned-messages-panel button { background-color: transparent !important; }
        html.dark #pinned-messages-panel button:hover { background-color: #383838 !important; }
        html.dark #pinned-messages-list button:hover { background-color: #383838 !important; }
        html.dark #pinned-messages-panel .text-gray-700 { color: rgba(255,255,255,0.9) !important; }
        html.dark #pinned-messages-panel .text-gray-500 { color: rgba(255,255,255,0.5) !important; }
        html.dark #pinned-messages-panel .text-gray-900 { color: #ffffff !important; }
        html.dark #pinned-messages-panel .text-gray-800 { color: #ffffff !important; }
        html.dark #pinned-messages-list .text-gray-700 { color: rgba(255,255,255,0.9) !important; }
        html.dark #pinned-messages-list .text-gray-500 { color: rgba(255,255,255,0.5) !important; }
        html.dark .bg-gray-50 { background-color: rgba(255,255,255,0.03) !important; }
        html.dark .hover\:bg-gray-50:hover { background-color: #383838 !important; }
        
        /* No chat selected / Welcome view dark mode */
        html.dark .chat-v2-main .bg-white { background-color: rgba(255,255,255,0.05) !important; }
        html.dark .chat-v2-main .bg-white.dark\:bg-white\/5 { background-color: rgba(255,255,255,0.05) !important; }
        html.dark .chat-v2-main .border-gray-200 { border-color: rgba(255,255,255,0.1) !important; }
        html.dark .chat-v2-main .text-gray-800 { color: #ffffff !important; }
        html.dark .chat-v2-main .text-gray-500 { color: rgba(255,255,255,0.5) !important; }
        html.dark .chat-v2-main .text-gray-400 { color: rgba(255,255,255,0.4) !important; }
        html.dark .chat-v2-main .bg-blue-100 { background-color: rgba(59, 130, 246, 0.2) !important; }
        html.dark .chat-v2-main .bg-green-100 { background-color: rgba(34, 197, 94, 0.2) !important; }
        html.dark .chat-v2-main .bg-indigo-100 { background-color: rgba(99, 102, 241, 0.2) !important; }
        html.dark .chat-v2-main .divide-gray-100 > * + * { border-color: rgba(255,255,255,0.05) !important; }
        
        /* Tasks widget dark mode */
        html.dark #main-tasks-widget { background-color: rgba(255,255,255,0.05) !important; border-color: rgba(255,255,255,0.1) !important; }
        html.dark #main-tasks-widget .border-b { border-color: rgba(255,255,255,0.1) !important; }
        html.dark #main-tasks-widget .text-gray-800 { color: #ffffff !important; }
        html.dark #main-tasks-widget .text-gray-500 { color: rgba(255,255,255,0.5) !important; }
        html.dark #main-tasks-widget .text-gray-400 { color: rgba(255,255,255,0.4) !important; }
        html.dark #main-tasks-widget .bg-gray-100 { background-color: rgba(255,255,255,0.1) !important; }
        html.dark #main-tasks-widget .hover\:bg-gray-50:hover { background-color: rgba(255,255,255,0.05) !important; }
        html.dark #main-tasks-list { border-color: rgba(255,255,255,0.05) !important; }
        html.dark #main-tasks-list .divide-gray-100 > * + * { border-color: rgba(255,255,255,0.05) !important; }
        
        /* Waiting for Assignment notice dark mode */
        html.dark .chat-v2-sidebar .bg-amber-50 { background-color: rgba(245, 158, 11, 0.15) !important; }
        html.dark .chat-v2-sidebar .border-amber-300 { border-color: rgba(245, 158, 11, 0.3) !important; }
        html.dark .chat-v2-sidebar .text-amber-700 { color: #fbbf24 !important; }
        html.dark .chat-v2-sidebar .text-amber-600 { color: rgba(251, 191, 36, 0.8) !important; }
        
        /* Waiting for Assignment notice light mode - ensure visibility */
        html:not(.dark) .chat-v2-sidebar .bg-amber-50 { background-color: #fffbeb !important; }
        html:not(.dark) .chat-v2-sidebar .border-amber-300 { border-color: #fcd34d !important; }
        html:not(.dark) .chat-v2-sidebar .text-amber-700 { color: #b45309 !important; }
        html:not(.dark) .chat-v2-sidebar .text-amber-600 { color: #d97706 !important; }
        html:not(.dark) .chat-v2-sidebar .text-amber-400 { color: #f59e0b !important; }
        
        /* Sidebar empty state boxes dark mode (My Tags, Direct Messages, etc.) */
        html.dark .chat-v2-sidebar .bg-white { background-color: rgba(255,255,255,0.05) !important; }
        html.dark .chat-v2-sidebar .bg-slate-50 { background-color: rgba(255,255,255,0.05) !important; }
        html.dark .chat-v2-sidebar div.bg-slate-50 { background-color: rgba(255,255,255,0.05) !important; }
        html.dark .bg-slate-50 { background-color: rgba(255,255,255,0.05) !important; }
        html.dark .chat-v2-sidebar .rounded-lg.bg-slate-50 { background-color: rgba(255,255,255,0.05) !important; }
        html.dark .chat-v2-sidebar div.rounded-lg.bg-slate-50 { background-color: rgba(255,255,255,0.05) !important; }
        html.dark .chat-v2-sidebar .rounded-lg.border { border-color: rgba(255,255,255,0.1) !important; }
        html.dark .chat-v2-sidebar .text-slate-500 { color: rgba(255,255,255,0.6) !important; }
        html.dark .text-slate-500 { color: rgba(255,255,255,0.6) !important; }
        html.dark .chat-v2-sidebar .text-gray-600 { color: rgba(255,255,255,0.6) !important; }
        html.dark .chat-v2-sidebar .text-gray-700 { color: rgba(255,255,255,0.7) !important; }
        html.dark .chat-v2-sidebar .text-gray-800 { color: rgba(255,255,255,0.8) !important; }
        
        /* Sidebar section headers light mode - make visible */
        html:not(.dark) .chat-v2-sidebar .text-gray-500 { color: #6b7280 !important; }
        html:not(.dark) .chat-v2-sidebar .text-gray-400 { color: #9ca3af !important; }
        html:not(.dark) .chat-v2-sidebar .text-gray-600 { color: #4b5563 !important; }
        
        /* Dark mode h3 elements should be white */
        html.dark h3 { color: #ffffff !important; }
        
        /* Server hover card dark mode */
        html.dark #server-hover-card > div { background-color: #1a1a2e !important; border-color: rgba(255,255,255,0.1) !important; }
        html.dark #server-hover-card h3 { color: #ffffff !important; }
        html.dark #server-hover-card .text-gray-900 { color: #ffffff !important; }
        html.dark #server-hover-card .text-gray-700 { color: rgba(255,255,255,0.8) !important; }
        html.dark #server-hover-card .text-gray-500 { color: rgba(255,255,255,0.5) !important; }
        html.dark #server-hover-card .text-gray-400 { color: rgba(255,255,255,0.4) !important; }
        html.dark #server-hover-card .border-gray-200 { border-color: rgba(255,255,255,0.1) !important; }
        html.dark #server-hover-card .border-gray-100 { border-color: rgba(255,255,255,0.1) !important; }
        
        /* Light mode server hover card */
        html:not(.dark) #server-hover-card > div { background-color: #ffffff !important; border-color: #e5e7eb !important; }
        html:not(.dark) #server-hover-card h3 { color: #111827 !important; }
        html:not(.dark) #server-hover-card .text-gray-900 { color: #111827 !important; }
        html:not(.dark) #server-hover-card .text-gray-700 { color: #374151 !important; }
        html:not(.dark) #server-hover-card .text-gray-500 { color: #6b7280 !important; }
        html:not(.dark) #server-hover-card .text-gray-400 { color: #9ca3af !important; }
        
        /* User hover card dark mode */
        html.dark #user-hover-card > div { background-color: #1a1a2e !important; border-color: rgba(255,255,255,0.1) !important; }
        html.dark #user-hover-card h3 { color: #ffffff !important; }
        html.dark #user-hover-card .text-gray-900 { color: #ffffff !important; }
        html.dark #user-hover-card .text-gray-600 { color: rgba(255,255,255,0.7) !important; }
        html.dark #user-hover-card .text-gray-500 { color: rgba(255,255,255,0.5) !important; }
        html.dark #user-hover-card .text-gray-400 { color: rgba(255,255,255,0.4) !important; }
        html.dark #user-hover-card .border-gray-200 { border-color: rgba(255,255,255,0.1) !important; }
        html.dark #user-hover-card #user-card-avatar { border-color: #1a1a2e !important; }
        html.dark #user-hover-card #user-card-status { border-color: #1a1a2e !important; }
        
        /* Light mode user hover card */
        html:not(.dark) #user-hover-card > div { background-color: #ffffff !important; border-color: #e5e7eb !important; }
        html:not(.dark) #user-hover-card h3 { color: #111827 !important; }
        html:not(.dark) #user-hover-card .text-gray-900 { color: #111827 !important; }
        html:not(.dark) #user-hover-card .text-gray-600 { color: #4b5563 !important; }
        html:not(.dark) #user-hover-card .text-gray-500 { color: #6b7280 !important; }
        html:not(.dark) #user-hover-card .text-gray-400 { color: #9ca3af !important; }
        html:not(.dark) #user-hover-card #user-card-avatar { border-color: #ffffff !important; }
        html:not(.dark) #user-hover-card #user-card-status { border-color: #ffffff !important; }
        
        html.dark .chat-v2-composer { background-color: rgba(12,12,20,0.8) !important; border-color: rgba(255,255,255,0.05) !important; }
        html.dark .chat-v2-input { background-color: #1c1c2b !important; color: #ffffff !important; }
        html.dark .chat-v2-footer { background-color: #101022 !important; }
        html.dark .chat-v2-composer { background-color: #2d2d30 !important; border-color: rgba(255,255,255,0.05) !important; }
        html.dark .chat-v2-input { background-color: #383838 !important; color: #ffffff !important; }
        html.dark .chat-v2-footer { background-color: #222529 !important; }
        html.dark .chat-v2-text { color: #ffffff !important; }
        html.dark .chat-v2-text-muted { color: rgba(255,255,255,0.5) !important; }
        html.dark .chat-v2-sidemenu .bi { color: rgba(255,255,255,0.7) !important; }
        html.dark .chat-v2-sidemenu button:hover .bi { color: #1d9bd1 !important; }

        /* Theme toggle icon visibility */
        html:not(.dark) .theme-icon-light { display: inline !important; }
        html:not(.dark) .theme-icon-dark { display: none !important; }
        html.dark .theme-icon-light { display: none !important; }
        html.dark .theme-icon-dark { display: inline !important; }

        /* ===== ANIMATED THEME TOGGLE BUTTON ===== */
        .theme-toggle {
            --theme-toggle__expand--duration: 500ms;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .theme-toggle svg {
            color: currentColor;
        }
        
        /* Theme toggle visibility - ensure it's always visible */
        .theme-toggle,
        .theme-toggle svg,
        .theme-toggle__expand {
            color: inherit;
        }
        
        /* Target only the visible elements, NOT the clipPath */
        .theme-toggle__expand g circle,
        .theme-toggle__expand g > path {
            fill: currentColor;
        }
        
        /* FORCE OVERRIDE - Dark mode theme toggle white icon */
        html.dark .theme-toggle {
            color: #ffffff !important;
        }
        html.dark .theme-toggle__expand g circle,
        html.dark .theme-toggle__expand g > path {
            fill: #ffffff !important;
        }
        
        /* Light mode sidebar - white icon on dark background */
        html:not(.dark) .chat-v2-sidebar .theme-toggle {
            color: rgba(255,255,255,0.9) !important;
        }
        html:not(.dark) .chat-v2-sidebar .theme-toggle__expand g circle,
        html:not(.dark) .chat-v2-sidebar .theme-toggle__expand g > path {
            fill: rgba(255,255,255,0.9) !important;
        }
        
        .theme-toggle__expand {
            transition: transform var(--theme-toggle__expand--duration) ease-out;
        }
        
        .theme-toggle__expand g {
            transform-origin: center;
            transition: transform calc(var(--theme-toggle__expand--duration) * 0.65) cubic-bezier(0, 0, 0, 1.25);
        }
        
        .theme-toggle__expand g circle {
            transition: transform calc(var(--theme-toggle__expand--duration) * 0.65) cubic-bezier(0, 0, 0, 1.25);
            transform-origin: center;
        }
        
        .theme-toggle__expand g path {
            transition: transform calc(var(--theme-toggle__expand--duration) * 0.65) cubic-bezier(0, 0, 0, 1.25);
            transform-origin: center;
        }
        
        /* Dark mode - moon state */
        html.dark .theme-toggle__expand {
            transform: rotate(-90deg);
        }
        
        html.dark .theme-toggle__expand g {
            transform: rotate(40deg);
        }
        
        html.dark .theme-toggle__expand g circle {
            transform: scale(1.5) translateX(-3px);
        }
        
        html.dark .theme-toggle__expand g path {
            transform: scale(0);
        }
        
        /* Light mode - sun state */
        html:not(.dark) .theme-toggle__expand {
            transform: rotate(0deg);
        }
        
        html:not(.dark) .theme-toggle__expand g {
            transform: rotate(0deg);
        }
        
        html:not(.dark) .theme-toggle__expand g circle {
            transform: scale(1) translateX(0);
        }
        
        html:not(.dark) .theme-toggle__expand g path {
            transform: scale(1);
        }

        /* ===== THEME SWITCH CIRCLE ANIMATION ===== */
        .theme-circle {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 99999;
            clip-path: circle(0% at 50% 0%);
            opacity: 0;
            transition: opacity 0.1s ease-out;
        }
        
        .theme-dark-circle {
            background-color: rgba(16, 16, 34, 0.5);
        }
        
        .theme-light-circle {
            background-color: rgba(255, 255, 255, 0.5);
        }
        
        .theme-circle.grow {
            opacity: 1;
            animation: theme-circle-grow 0.7s ease-in-out forwards;
        }
        
        .theme-circle.shrink {
            animation: theme-circle-shrink 0.3s ease-in-out forwards;
        }
        
        @keyframes theme-circle-grow {
            0% {
                clip-path: circle(0% at 50% 0%);
            }
            100% {
                clip-path: circle(150% at 50% 0%);
            }
        }
        
        @keyframes theme-circle-shrink {
            0% {
                clip-path: circle(150% at 50% 0%);
                opacity: 1;
            }
            100% {
                clip-path: circle(150% at 50% 0%);
                opacity: 0;
            }
        }

        /* ===== MOBILE RESPONSIVE STYLES ===== */
        @media (max-width: 767px) {
            /* Sidebars slide in from left on mobile */
            .chat-v2-sidemenu {
                position: fixed !important;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 60;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                width: 72px !important;
                min-width: 72px !important;
                flex-shrink: 0;
                padding: 8px 10px !important;
                overflow: visible !important;
                box-sizing: border-box !important;
                display: flex !important;
                background-color: #3f4a3c !important;
            }
            html.dark .chat-v2-sidemenu {
                background-color: #1a1d21 !important;
            }
            .chat-v2-sidemenu.mobile-open {
                transform: translateX(0) !important;
            }
            
            /* Ensure sidemenu items are properly sized on mobile */
            .chat-v2-sidemenu .size-12 {
                width: 44px !important;
                height: 44px !important;
            }
            .chat-v2-sidemenu .size-10 {
                width: 36px !important;
                height: 36px !important;
            }

            .chat-v2-sidebar {
                position: fixed !important;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 59;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                width: 280px !important;
                max-width: calc(100vw - 72px);
                flex-shrink: 0;
            }
            .chat-v2-sidebar.mobile-open {
                transform: translateX(72px) !important;
            }

            /* Mobile overlay backdrop */
            .mobile-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                z-index: 58;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }
            .mobile-overlay.active {
                opacity: 1;
                pointer-events: auto;
            }

            /* Main chat takes full width on mobile */
            .chat-v2-main {
                width: 100% !important;
                min-width: 100% !important;
                flex: 1 1 100% !important;
            }

            /* Hide right column on mobile */
            .chat-v2-right {
                display: none !important;
            }

            /* Mobile header adjustments */
            .chat-v2-header {
                padding: 0.75rem 0.75rem !important;
            }
            .chat-v2-header h2 {
                font-size: 1rem !important;
            }

            /* Mobile composer adjustments */
            .chat-v2-composer {
                padding: 0.75rem !important;
            }
            .chat-v2-input {
                font-size: 16px !important; /* Prevents iOS zoom */
                padding-left: 2.75rem !important;
                padding-right: 6rem !important;
            }

            /* Messages container mobile */
            .chat-v2-main .flex-1.space-y-4 {
                padding: 0.75rem !important;
            }
            .chat-v2-main .flex.gap-3.rounded-lg.p-3 {
                padding: 0.5rem !important;
            }
            .chat-v2-main .flex.gap-3 img.size-10 {
                width: 2rem !important;
                height: 2rem !important;
            }
            .chat-v2-main .text-base.font-bold {
                font-size: 0.875rem !important;
            }
            .chat-v2-main .text-base.leading-relaxed {
                font-size: 0.875rem !important;
            }
        }

        /* Tablet adjustments */
        @media (min-width: 769px) and (max-width: 1024px) {
            .chat-v2-sidebar {
                width: 220px !important;
            }
            .chat-v2-right {
                width: 200px !important;
            }
        }

        /* ===== BOTTOM SHEET MODAL STYLES (Mobile) ===== */
        @media (max-width: 767px) {
            [data-chat-modal] > div {
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                top: auto !important;
                max-width: 100% !important;
                width: 100% !important;
                max-height: 85vh;
                border-radius: 1.5rem 1.5rem 0 0 !important;
                transform: translateY(100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                margin: 0 !important;
                display: flex !important;
                flex-direction: column !important;
            }
            [data-chat-modal].flex > div {
                transform: translateY(0);
            }
            [data-chat-modal] > div::before {
                content: '';
                display: block;
                width: 40px;
                height: 4px;
                background: rgba(0, 0, 0, 0.2);
                border-radius: 2px;
                margin: 8px auto 4px;
                flex-shrink: 0;
            }
            .dark [data-chat-modal] > div::before {
                background: rgba(255, 255, 255, 0.2);
            }
            /* Ensure modal content scrolls properly and buttons stay visible */
            [data-chat-modal] > div > form,
            [data-chat-modal] > div > .flex-1 {
                overflow-y: auto;
                flex: 1;
                min-height: 0;
            }
            /* Safe area padding for iOS */
            [data-chat-modal] > div {
                padding-bottom: env(safe-area-inset-bottom, 0) !important;
            }
        }

        /* Scrollbar hide utility */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Reaction tooltip styles */
        .reaction-tooltip {
            min-width: 80px;
            max-width: 200px;
        }
        .reaction-tooltip::before {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            border-width: 4px;
            border-style: solid;
            border-color: #111827 transparent transparent transparent;
        }
        .dark .reaction-tooltip::before {
            border-color: #1f2937 transparent transparent transparent;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* Toggle switch styles - ensure proper colors */
        input[type="checkbox"].sr-only:checked + div {
            background-color: #2b2bee !important;
        }
        input[type="checkbox"].sr-only:checked + div.peer-checked\:bg-green-500,
        input#group-is-public:checked + div {
            background-color: #22c55e !important;
        }

        /* Safe area for iOS notch */
        @supports (padding-top: env(safe-area-inset-top)) {
            .chat-v2-header {
                padding-top: max(0.75rem, env(safe-area-inset-top));
            }
            .chat-v2-composer {
                padding-bottom: max(1rem, env(safe-area-inset-bottom));
            }
        }

        /* Skeleton loading animation */
        @keyframes skeleton-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        @keyframes skeleton-shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Dark mode skeleton */
        .skeleton {
            background: linear-gradient(90deg, #2d2d30 25%, #383838 50%, #2d2d30 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s infinite;
        }
        
        /* Light mode skeleton */
        html:not(.dark) .skeleton {
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s infinite;
        }
        
        .img-skeleton {
            position: relative;
            display: inline-block;
            min-width: 100px;
            min-height: 100px;
        }
        
        /* Dark mode image skeleton */
        .img-skeleton:not(.loaded)::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, #2d2d30 25%, #383838 50%, #2d2d30 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s infinite;
            border-radius: 0.5rem;
            min-width: 100px;
            min-height: 100px;
        }
        
        /* Light mode image skeleton */
        html:not(.dark) .img-skeleton:not(.loaded)::before {
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s infinite;
        }
        
        .img-skeleton:not(.loaded) img {
            opacity: 0;
        }
        .img-skeleton.loaded img {
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        .img-skeleton.loaded::before {
            display: none;
        }
        
        /* Create Group Modal - Primary button */
        .create-group-btn {
            background-color: #2b2bee !important;
            color: #ffffff !important;
        }
        .create-group-btn:hover {
            background-color: #2424cc !important;
        }
        .create-group-btn:disabled {
            background-color: #6b7280 !important;
            cursor: not-allowed;
        }
        
        /* Dark mode modal styles */
        html.dark [data-chat-modal] .modal-content {
            background-color: #1a1a2e !important;
            color: #ffffff !important;
        }
        html.dark [data-chat-modal] .modal-header {
            border-color: rgba(255,255,255,0.1) !important;
        }
        html.dark [data-chat-modal] .modal-footer {
            background-color: rgba(255,255,255,0.05) !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        html.dark [data-chat-modal] input,
        html.dark [data-chat-modal] textarea {
            background-color: #252540 !important;
            border-color: rgba(255,255,255,0.2) !important;
            color: #ffffff !important;
        }
        html.dark [data-chat-modal] input::placeholder,
        html.dark [data-chat-modal] textarea::placeholder {
            color: rgba(255,255,255,0.4) !important;
        }
        html.dark [data-chat-modal] label {
            color: rgba(255,255,255,0.7) !important;
        }
        html.dark [data-chat-modal] .prebuilt-section {
            background-color: rgba(255,255,255,0.05) !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        html.dark [data-chat-modal] .text-gray-700,
        html.dark [data-chat-modal] .text-gray-600 {
            color: rgba(255,255,255,0.8) !important;
        }
        html.dark [data-chat-modal] .text-gray-500,
        html.dark [data-chat-modal] .text-gray-400 {
            color: rgba(255,255,255,0.5) !important;
        }
        html.dark [data-chat-modal] h2 {
            color: #ffffff !important;
        }
        html.dark [data-chat-modal] p {
            color: rgba(255,255,255,0.6) !important;
        }
        
        .mention {
            color: #eab308 !important;
        }
        #messages-container .mention,
        html.dark #messages-container .mention,
        html:not(.dark) #messages-container .mention {
            color: #eab308 !important;
        }
        html.dark #messages-container .chat-message-body,
        html.dark #messages-container .chat-message-body *,
        html.dark #messages-container .rich-text-content,
        html.dark #messages-container .rich-text-content * {
            color: rgba(255,255,255,0.9) !important;
        }
        html.dark #messages-container .chat-message-body strong,
        html.dark #messages-container .chat-message-body b,
        html.dark #messages-container .chat-message-body em,
        html.dark #messages-container .chat-message-body i,
        html.dark #messages-container .rich-text-content strong,
        html.dark #messages-container .rich-text-content b,
        html.dark #messages-container .rich-text-content em,
        html.dark #messages-container .rich-text-content i {
            color: #ffffff !important;
        }
        
        .mention-badge {
            display: none;
        }
        .mention-badge.flex {
            display: flex !important;
            background-color: #eab308 !important;
            color: #000000 !important;
        }
        
        #tour-servers {
            overflow: visible !important;
        }
        .chat-v2-sidemenu {
            overflow: visible !important;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-[#DCDDDE] transition-colors duration-200">
    {{-- Theme switch animation circles --}}
    <div id="theme-dark-circle" class="theme-circle theme-dark-circle"></div>
    <div id="theme-light-circle" class="theme-circle theme-light-circle"></div>
    
    {{ $slot }}

    <script>
        // Theme toggle functionality with circle animation
        window.toggleChatTheme = function() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            const darkCircle = document.getElementById('theme-dark-circle');
            const lightCircle = document.getElementById('theme-light-circle');
            
            if (isDark) {
                // Switching to light mode
                lightCircle.classList.remove('shrink');
                lightCircle.classList.add('grow');
                
                setTimeout(() => {
                    html.classList.remove('dark');
                    localStorage.setItem('chat-theme', 'light');
                }, 350);
                
                setTimeout(() => {
                    lightCircle.classList.remove('grow');
                    lightCircle.classList.add('shrink');
                    setTimeout(() => {
                        lightCircle.classList.remove('shrink');
                    }, 300);
                }, 700);
            } else {
                // Switching to dark mode
                darkCircle.classList.remove('shrink');
                darkCircle.classList.add('grow');
                
                setTimeout(() => {
                    html.classList.add('dark');
                    localStorage.setItem('chat-theme', 'dark');
                }, 350);
                
                setTimeout(() => {
                    darkCircle.classList.remove('grow');
                    darkCircle.classList.add('shrink');
                    setTimeout(() => {
                        darkCircle.classList.remove('shrink');
                    }, 300);
                }, 700);
            }
        };
        
        function setMobileVH() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }
        
        setMobileVH();
        window.addEventListener('resize', setMobileVH);
        window.addEventListener('orientationchange', () => {
            setTimeout(setMobileVH, 100);
        });
    </script>

    {{-- Chat Tour Script --}}
    <script>
        let chatTour = null;
        
        function startChatTour() {
            if (chatTour) {
                chatTour.cancel();
            }
            
            chatTour = new Shepherd.Tour({
                defaultStepOptions: {
                    cancelIcon: { enabled: true },
                    classes: 'shepherd-theme-custom',
                    scrollTo: { behavior: 'smooth', block: 'center' }
                },
                useModalOverlay: true
            });

            // Step 1: Logo/Dashboard
            chatTour.addStep({
                id: 'tour-logo',
                title: '<i class="bi bi-house-door mr-2"></i> Dashboard',
                text: 'Click the logo to return to your main dashboard anytime.',
                attachTo: { element: '#tour-logo', on: 'right' },
                buttons: [
                    { text: 'Skip', action: chatTour.cancel, secondary: true },
                    { text: 'Next', action: chatTour.next }
                ]
            });

            // Step 2: Servers List
            chatTour.addStep({
                id: 'tour-servers',
                title: '<i class="bi bi-collection mr-2"></i> Your Servers',
                text: 'All your group chats and servers appear here. Click any server icon to switch between conversations.',
                attachTo: { element: '#tour-servers', on: 'right' },
                buttons: [
                    { text: 'Back', action: chatTour.back, secondary: true },
                    { text: 'Next', action: chatTour.next }
                ]
            });

            // Step 3: Global Feed (Moderator only)
            if (document.getElementById('tour-global-feed')) {
                chatTour.addStep({
                    id: 'tour-global-feed',
                    title: '<i class="bi bi-broadcast mr-2"></i> Global Feed',
                    text: 'As a moderator, view all messages across all channels in one place. Great for monitoring activity.',
                    attachTo: { element: '#tour-global-feed', on: 'right' },
                    buttons: [
                        { text: 'Back', action: chatTour.back, secondary: true },
                        { text: 'Next', action: chatTour.next }
                    ]
                });
            }

            // Step 4: Manage Members (Moderator only)
            if (document.getElementById('tour-manage-members')) {
                chatTour.addStep({
                    id: 'tour-manage-members',
                    title: '<i class="bi bi-people-fill mr-2"></i> Manage Members',
                    text: 'Add, remove, or manage member roles across all your servers from this central panel.',
                    attachTo: { element: '#tour-manage-members', on: 'right' },
                    buttons: [
                        { text: 'Back', action: chatTour.back, secondary: true },
                        { text: 'Next', action: chatTour.next }
                    ]
                });
            }

            // Step 5: Explore
            chatTour.addStep({
                id: 'tour-explore',
                title: '<i class="bi bi-globe2 mr-2"></i> Explore',
                text: 'Discover public servers and communities you can join.',
                attachTo: { element: '#tour-explore', on: 'right' },
                buttons: [
                    { text: 'Back', action: chatTour.back, secondary: true },
                    { text: 'Next', action: chatTour.next }
                ]
            });

            // Step 6: Channel Header (Right Sidebar)
            if (document.getElementById('tour-channel-header')) {
                chatTour.addStep({
                    id: 'tour-channel-header',
                    title: '<i class="bi bi-hash mr-2"></i> Channel Info',
                    text: 'View the current channel name and status. Starred channels are pinned for quick access.',
                    attachTo: { element: '#tour-channel-header', on: 'left' },
                    buttons: [
                        { text: 'Back', action: chatTour.back, secondary: true },
                        { text: 'Next', action: chatTour.next }
                    ]
                });
            }

            // Step 7: Right Sidebar Tabs
            if (document.getElementById('tour-right-tabs')) {
                chatTour.addStep({
                    id: 'tour-right-tabs',
                    title: '<i class="bi bi-layout-sidebar-inset-reverse mr-2"></i> Panel Tabs',
                    text: '<strong>Info:</strong> View channel details, bot status, and online members.<br><strong>Moderator Area:</strong> Access moderation tools and channel settings.',
                    attachTo: { element: '#tour-right-tabs', on: 'left' },
                    buttons: [
                        { text: 'Back', action: chatTour.back, secondary: true },
                        { text: 'Next', action: chatTour.next }
                    ]
                });
            }

            // Step 8: About Section
            if (document.getElementById('tour-about-section')) {
                chatTour.addStep({
                    id: 'tour-about-section',
                    title: '<i class="bi bi-info-circle mr-2"></i> About Section',
                    text: 'See channel description, creation date, and other details. Moderators can edit this information.',
                    attachTo: { element: '#tour-about-section', on: 'left' },
                    buttons: [
                        { text: 'Back', action: chatTour.back, secondary: true },
                        { text: 'Finish', action: chatTour.complete }
                    ]
                });
            } else {
                // If no right sidebar, end tour earlier
                chatTour.steps[chatTour.steps.length - 1].options.buttons = [
                    { text: 'Back', action: chatTour.back, secondary: true },
                    { text: 'Finish', action: chatTour.complete }
                ];
            }

            chatTour.on('complete', () => {
                if (typeof showChatToast === 'function') {
                    showChatToast('Tour completed! Click the ? button anytime to restart.', 'success');
                }
            });

            chatTour.start();
        }

        // Right Sidebar Tour
        let rightSidebarTour = null;
        
        function startRightSidebarTour() {
            if (rightSidebarTour) {
                rightSidebarTour.cancel();
            }
            
            rightSidebarTour = new Shepherd.Tour({
                defaultStepOptions: {
                    cancelIcon: { enabled: true },
                    classes: 'shepherd-theme-custom',
                    scrollTo: { behavior: 'smooth', block: 'center' }
                },
                useModalOverlay: true
            });

            // Step 1: Channel Header
            rightSidebarTour.addStep({
                id: 'tour-rs-channel',
                title: '<i class="bi bi-hash mr-2"></i> Current Channel',
                text: 'This shows the channel you\'re currently viewing. Starred channels have a special badge.',
                attachTo: { element: '#tour-channel-header', on: 'left' },
                buttons: [
                    { text: 'Skip', action: rightSidebarTour.cancel, secondary: true },
                    { text: 'Next', action: rightSidebarTour.next }
                ]
            });

            // Step 2: Tabs
            rightSidebarTour.addStep({
                id: 'tour-rs-tabs',
                title: '<i class="bi bi-layout-sidebar mr-2"></i> Panel Tabs',
                text: '<strong>Info:</strong> View channel details, HillBot status, and online members.<br><br><strong>Moderator Area:</strong> Access channel settings, content moderation, and danger zone options.',
                attachTo: { element: '#tour-right-tabs', on: 'left' },
                buttons: [
                    { text: 'Back', action: rightSidebarTour.back, secondary: true },
                    { text: 'Next', action: rightSidebarTour.next }
                ]
            });

            // Step 3: About Section
            if (document.getElementById('tour-about-section')) {
                rightSidebarTour.addStep({
                    id: 'tour-rs-about',
                    title: '<i class="bi bi-info-circle mr-2"></i> About',
                    text: 'View channel description and creation date. Click to expand or collapse this section.',
                    attachTo: { element: '#tour-about-section', on: 'left' },
                    buttons: [
                        { text: 'Back', action: rightSidebarTour.back, secondary: true },
                        { text: 'Next', action: rightSidebarTour.next }
                    ]
                });
            }

            // Step 4: Bot Section (if enabled)
            if (document.getElementById('tour-bot-section')) {
                rightSidebarTour.addStep({
                    id: 'tour-rs-bot',
                    title: '<i class="bi bi-cpu mr-2"></i> HillBot',
                    text: 'HillBot is your moderation assistant. It can filter profanity, block spam, and enforce channel rules automatically.',
                    attachTo: { element: '#tour-bot-section', on: 'left' },
                    buttons: [
                        { text: 'Back', action: rightSidebarTour.back, secondary: true },
                        { text: 'Next', action: rightSidebarTour.next }
                    ]
                });
            }

            // Step 5: Online Members
            if (document.getElementById('tour-online-members')) {
                rightSidebarTour.addStep({
                    id: 'tour-rs-members',
                    title: '<i class="bi bi-people mr-2"></i> Online Members',
                    text: 'See who\'s currently online in this channel. Click on a member to view their profile or start a direct message.',
                    attachTo: { element: '#tour-online-members', on: 'left' },
                    buttons: [
                        { text: 'Back', action: rightSidebarTour.back, secondary: true },
                        { text: 'Finish', action: rightSidebarTour.complete }
                    ]
                });
            } else {
                // End tour at last available step
                const lastStep = rightSidebarTour.steps[rightSidebarTour.steps.length - 1];
                if (lastStep) {
                    lastStep.options.buttons = [
                        { text: 'Back', action: rightSidebarTour.back, secondary: true },
                        { text: 'Finish', action: rightSidebarTour.complete }
                    ];
                }
            }

            rightSidebarTour.on('complete', () => {
                if (typeof showChatToast === 'function') {
                    showChatToast('Right panel tour completed!', 'success');
                }
            });

            rightSidebarTour.start();
        }
    </script>

    <style>
        /* Custom Shepherd Tour Styles */
        .shepherd-theme-custom {
            max-width: 340px;
        }
        .shepherd-theme-custom .shepherd-content {
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .shepherd-theme-custom .shepherd-header {
            background: linear-gradient(135deg, #1d9bd1 0%, #0d7ab5 100%);
            border-radius: 12px 12px 0 0;
            padding: 12px 16px;
        }
        .shepherd-theme-custom .shepherd-title {
            color: white;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .shepherd-theme-custom .shepherd-cancel-icon {
            color: white;
        }
        .shepherd-theme-custom .shepherd-cancel-icon:hover {
            color: rgba(255,255,255,0.8);
        }
        .shepherd-theme-custom .shepherd-text {
            padding: 16px;
            font-size: 13px;
            line-height: 1.5;
            color: #374151;
        }
        .dark .shepherd-theme-custom .shepherd-text {
            color: #e5e7eb;
            background: #1f2937;
        }
        .dark .shepherd-theme-custom .shepherd-content {
            background: #1f2937;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .shepherd-theme-custom .shepherd-footer {
            padding: 12px 16px;
            border-top: 1px solid #e5e7eb;
        }
        .dark .shepherd-theme-custom .shepherd-footer {
            border-top-color: rgba(255,255,255,0.1);
            background: #1f2937;
            border-radius: 0 0 12px 12px;
        }
        .shepherd-theme-custom .shepherd-button {
            background: #1d9bd1;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .shepherd-theme-custom .shepherd-button:hover {
            background: #0d7ab5;
        }
        .shepherd-theme-custom .shepherd-button-secondary {
            background: transparent;
            color: #6b7280;
            border: 1px solid #d1d5db;
        }
        .dark .shepherd-theme-custom .shepherd-button-secondary {
            color: #9ca3af;
            border-color: rgba(255,255,255,0.2);
        }
        .shepherd-theme-custom .shepherd-button-secondary:hover {
            background: #f3f4f6;
        }
        .dark .shepherd-theme-custom .shepherd-button-secondary:hover {
            background: rgba(255,255,255,0.1);
        }
        .shepherd-modal-overlay-container {
            z-index: 9998;
        }
        .shepherd-element {
            z-index: 9999;
        }
    </style>

    <script>
        // Helper function to generate initials
        function getInitials(name) {
            if (!name) return 'U';
            const words = name.trim().split(' ');
            if (words.length >= 2) {
                return (words[0][0] + words[1][0]).toUpperCase();
            }
            return name.substring(0, 2).toUpperCase();
        }

        // Helper function to get color from name
        function getColorFromName(name) {
            const colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f97316', '#10b981', '#3b82f6', '#ef4444', '#f59e0b'];
            let hash = 0;
            for (let i = 0; i < name.length; i++) {
                hash = name.charCodeAt(i) + ((hash << 5) - hash);
            }
            return colors[Math.abs(hash) % colors.length];
        }

        // Helper function to create initials avatar as data URL
        function createInitialsAvatar(name, size = 100) {
            const initials = getInitials(name);
            const bgColor = getColorFromName(name);
            
            const canvas = document.createElement('canvas');
            canvas.width = size;
            canvas.height = size;
            const ctx = canvas.getContext('2d');
            
            // Draw background
            ctx.fillStyle = bgColor;
            ctx.fillRect(0, 0, size, size);
            
            // Draw text
            ctx.fillStyle = '#ffffff';
            ctx.font = `bold ${size * 0.4}px sans-serif`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(initials, size / 2, size / 2);
            
            return canvas.toDataURL();
        }

        // Global image error handler - fallback to initials avatar
        document.addEventListener('error', function(e) {
            if (e.target.tagName === 'IMG' && !e.target.dataset.fallbackApplied) {
                e.target.dataset.fallbackApplied = 'true';
                
                // Generate a seed from the alt text or data attributes
                const name = e.target.alt || e.target.dataset.userName || e.target.dataset.seed || 'User';
                
                // Check if it's likely a profile/avatar image
                const src = e.target.src || '';
                const isAvatar = e.target.classList.contains('avatar') || 
                                e.target.closest('.avatar') ||
                                e.target.classList.contains('rounded-full') ||
                                src.includes('profile') ||
                                src.includes('storage') ||
                                src.includes('photo');
                
                if (isAvatar) {
                    e.target.src = createInitialsAvatar(name);
                }
            }
        }, true);
    </script>
    
    {{-- New Update Manager (Ctrl+Right-Click to mark as new/bug) --}}
    @include('components.new-update-manager')
</body>

</html>
