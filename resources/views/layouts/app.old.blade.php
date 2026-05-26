<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/assets/raw/new.png" type="image/x-icon">
    <title>{{ config('app.name', 'Hill Business Consulting Services') }}</title>
    
    <!-- PWA Meta Tags -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="HILLBCS">
    <link rel="manifest" href="/dist/manifest.json">
    <meta name="theme-color" content="#007bff">
    <meta name="msapplication-navbutton-color" content="#007bff">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="Description"
        content="The history of Hill Business Consulting Services began with two business owners, Joe and Douglas, who both worked in the janitorial industry and were challenged by rising labor costs and the pressure these costs placed on profitability. Recognizing the need for a fresh approach, they sought a better solution. Drawing from Mr. Hill's extensive background in the high-tech industry, they introduced processes, methods, and technologies typically used by world-class Silicon Valley companies and applied these innovations to the janitorial sector. This innovative shift proved highly successful, demonstrating that advanced technology isn’t just for billion-dollar enterprises but can be effectively implemented at any scale when supported by experienced professionals. This laid the groundwork for outsourcing high-quality talent, utilizing internet technologies and software tools to provide cost-effective, top-notch support services. Today, IOS works closely with clients, ensuring they benefit from continuous advancements and the efficiencies of an ever-changing global market.">
    <meta name="Author" content="Hill Business Consulting Services">
    <meta name="keywords" content="iosbiz.com, iosbiz, Hill Business Consulting Services">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    @include('layouts.components.nav-link')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Mogra&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">
    @livewireStyles
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');

        body {
            font-family: "Poppins", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }
    </style>
</head>

<body>
    
     @include('layouts.components.nav-switcher')

    <!-- Desktop Loader -->
    <div id="loader" class="desktop-response">
        <center class="flex flex-col items-center space-y-4">
            <div class="relative w-24 h-24 flex items-center justify-center">
                <div
                    class="absolute w-full h-full border-2 !border-yellow-400 border-t-transparent rounded-full animate-spin">
                </div>
                <img src="/assets/logo.png" alt="Logo" class="h-16 z-10">
            </div>

            <div class="text-center text-gray-700 text-sm">
                Please wait a moment... <br />
                We are securely processing your request. <br />
                Retrieving large files may take a few minutes.
            </div>
        </center>
    </div>



    <div class="page">


        <!-- ession(?impersonator_email-->

        <div class="desktop-response">
            @include('layouts.components.nav-top')
            @include('layouts.components.nav-sidemenu')
        </div>

        <div class="main-content app-content pt-0 mt-4">


           {{ $slot }}
        </div>

        {{-- @include('layouts.components.welcome_message') --}}


        @include('layouts.components.nav-footer')

        {{-- <script>
            document.addEventListener("DOMContentLoaded", function() {
                const mobileNavToggleBtn = document.querySelector('.sidemenu-toggle');

                if (mobileNavToggleBtn) {
                    // Trigger the click once on page load
                    mobileNavToggleBtn.click();

                    // Add the normal event listener (if needed)
                    mobileNavToggleBtn.addEventListener('click', function() {
                        document.body.classList.toggle('mobile-nav-active');

                        const sidebar = document.getElementById('sidebar');
                        const page = document.querySelector('.page');

                        if (sidebar) sidebar.classList.toggle('collapsed');
                        if (page) page.classList.toggle('sidebar-collapsed');
                    });
                }
            });
        </script> --}}


    </div>

    @include('layouts.components.nav-footer-link')


</body>

</html>
