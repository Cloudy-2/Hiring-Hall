<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Two-Factor Authentication — Hill Business Consulting Services</title>
    <link rel="icon" href="/assets/raw/new.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');

        body {
            font-family: "Rubik", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        /* Override action-section grid to be single column */
        .two-factor-compact .md\:grid-cols-3 {
            grid-template-columns: 1fr !important;
        }
        .two-factor-compact .md\:col-span-2 {
            grid-column: span 1 !important;
        }
        /* Hide the duplicate title/description section */
        .two-factor-compact [class*="section-title"],
        .two-factor-compact > div:first-child > div:first-child {
            display: none !important;
        }
        /* Remove extra padding */
        .two-factor-compact .sm\:p-6 {
            padding: 0 !important;
        }
        .two-factor-compact .shadow {
            box-shadow: none !important;
        }
    </style>
    @vite(['resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-6 px-4">
        <div class="max-w-2xl w-full">
            <div class="text-center mb-6">
                <img class="mx-auto h-16 w-auto" src="/assets/logo.png" alt="Logo" />
            </div>

            <div class="bg-white shadow-lg rounded-xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Two-Factor Authentication Required</h2>
                        <p class="text-xs text-gray-500">Secure your account before continuing</p>
                    </div>
                </div>

                @if (session('warning'))
                    <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5 text-sm"></i>
                        <p class="text-sm text-amber-700">{{ session('warning') }}</p>
                    </div>
                @endif

                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        Set up 2FA using an authenticator app (Google Authenticator, Authy, or Microsoft Authenticator).
                    </p>
                </div>

                <div class="two-factor-compact">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i>
                            Log out and return later
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>
