<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/assets/raw/new.png" type="image/x-icon">
    <title>{{ config('app.name', 'Hill Business Consulting Services') }} - Chat</title>

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#0ea5e9">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="HillBCS">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/assets/pwa/ios/180.png">

    @vite(['resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }

        body {
            background: #36393f;
            color: #dcddde;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #202225;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2c2f33;
        }

        * {
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        button {
            cursor: pointer;
            border: none;
            background: none;
            color: inherit;
        }

        input, textarea {
            font-family: inherit;
            color: inherit;
        }
    </style>
</head>

<body>
    {{ $slot }}

    <!-- PWA Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        }
    </script>
</body>

</html>
