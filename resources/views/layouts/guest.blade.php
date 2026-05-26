<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<script>
    (function () {
        var isDark = localStorage.getItem('dark-mode') === 'true' || 
                     localStorage.getItem('xyntradarktheme') === 'true' || 
                     localStorage.getItem('layout-theme') === 'dark';
        
        if (isDark) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    })();
</script>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HillBCS Hire') }}</title>
        <link rel="icon" href="/assets/raw/new.png" type="image/x-icon">

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#0ea5e9">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="HillBCS">
        <link rel="manifest" href="/manifest.json">
        <link rel="apple-touch-icon" href="/assets/pwa/ios/180.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="{{ (isset($_COOKIE['dark-mode']) && $_COOKIE['dark-mode'] === 'true') ? 'dark-theme' : '' }}">
    <script>
        if (localStorage.getItem('dark-mode') === 'true' || 
            localStorage.getItem('xyntradarktheme') === 'true' || 
            localStorage.getItem('layout-theme') === 'dark') {
            document.body.classList.add('dark-theme');
        }

        function toggleDarkMode() {
            const isDark = document.body.classList.toggle('dark-theme');
            document.documentElement.classList.toggle('dark', isDark);
            localStorage.setItem('dark-mode', isDark);
            
            // Sync with existing theme keys
            localStorage.setItem('xyntradarktheme', isDark);
            localStorage.setItem('layout-theme', isDark ? 'dark' : 'light');
            
            // Sync with server-side via cookie
            document.cookie = "dark-mode=" + isDark + "; path=/; max-age=" + (365 * 24 * 60 * 60);
        }
    </script>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts

        <!-- PWA Service Worker -->
        <script>
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            }
        </script>
    </body>
</html>
