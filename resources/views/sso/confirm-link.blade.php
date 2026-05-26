<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-width="fullwidth">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/assets/raw/new.png" type="image/x-icon">
    <title>Link Account - {{ config('app.name', 'Hill Hire') }}</title>
    @include('layouts.components.nav-link')
    @vite(['resources/js/app.js'])
</head>

<body class="bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full p-8">
            {{-- Logo --}}
            <div class="text-center mb-6">
                <img src="/assets/logo.png" alt="Hill Hire" class="h-12 mx-auto mb-4">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Link Your Account</h1>
            </div>

            @if($already_linked)
                {{-- Already linked to different account --}}
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Account Already Linked</h3>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">
                                Your Hill Hire account is already linked to a different Workspace account.
                                You need to unlink it first before linking to a new account.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center">
                    <a href="{{ route('sso.link.cancel') }}" 
                       class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                        Go Back
                    </a>
                </div>
            @else
                {{-- Confirm linking --}}
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>{{ $workspace_name }}</strong> ({{ $workspace_email }}) wants to link their Workspace account with your Hill Hire account.
                    </p>
                </div>

                <div class="space-y-4 mb-6">
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Your Hill Hire Account</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                    </div>

                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/50 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Workspace Account</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $workspace_email }}</p>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400 text-center mb-6">
                    Once linked, you can seamlessly switch between Workspace and Hill Hire without logging in again.
                </p>

                <div class="flex gap-3">
                    <a href="{{ route('sso.link.cancel') }}" 
                       class="flex-1 px-4 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-center text-sm font-medium">
                        Cancel
                    </a>
                    <form action="{{ route('sso.link.process') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                class="w-full px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                            Link Accounts
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
