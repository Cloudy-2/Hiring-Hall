<!DOCTYPE html>
<html lang="en" style="height:100%;overflow:hidden;">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Two-Factor Authentication — Hill Business Consulting Services</title>
    <link rel="icon" href="/assets/raw/new.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Alpine.js is required to handle the toggling of recovery code inputs -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');

        body {
            font-family: "Rubik", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        @keyframes breathe {
            0%, 100% { transform: scale(1); filter: drop-shadow(0 0 8px rgba(14,165,233,0.3)); }
            50% { transform: scale(1.04); filter: drop-shadow(0 0 28px rgba(14,165,233,0.7)); }
        }
        .animate-breathe { animation: breathe 5s ease-in-out infinite; }

        @keyframes form-breathe {
            0%, 100% { box-shadow: 0 8px 32px 0 rgba(0,0,0,0.6), 0 0 0px rgba(14,165,233,0); border-color: rgba(255,255,255,0.05); }
            50% { box-shadow: 0 8px 32px 0 rgba(0,0,0,0.6), 0 0 30px rgba(14,165,233,0.15); border-color: rgba(14,165,233,0.25); }
        }
        .animate-form-breathe { animation: form-breathe 6s ease-in-out infinite; }

        @keyframes float-text {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        .animate-float { animation: float-text 7s ease-in-out infinite; }
        
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="text-white relative h-screen overflow-hidden bg-[#050510]">
    <!-- Darkveil WebGL Background -->
    <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 0; pointer-events: none; background: #050510;">
        <canvas id="darkveil-canvas" style="width: 100%; height: 100%; display: block;"></canvas>
    </div>

    <!-- Split Layout -->
    <div class="h-screen flex items-center relative z-10 w-full">

        <!-- Back to Login Button -->
        <form method="POST" action="{{ route('logout') }}" class="fixed top-5 left-5 z-50">
            @csrf
            <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 rounded-full backdrop-blur-sm transition-all group cursor-pointer">
                <i class="fa-solid fa-arrow-left text-xs group-hover:-translate-x-0.5 transition-transform"></i>
                Cancel Login
            </button>
        </form>

        <!-- CENTER: Form -->
        <div class="flex items-center justify-center w-full h-full px-6">
            <div class="w-full max-w-md">

                <div x-data="{ recovery: false }" class="w-full bg-[#0a0f1c]/70 backdrop-blur-2xl border border-white/5 p-8 rounded-2xl shadow-[0_8px_32px_0_rgba(0,0,0,0.6)] animate-form-breathe relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/8 to-transparent pointer-events-none"></div>

                    <div class="relative z-10" x-show="!recovery">
                        <h2 class="text-2xl font-extrabold text-white tracking-tight drop-shadow-md">Two-Factor Authentication</h2>
                        <p class="mt-1 text-gray-300 text-sm drop-shadow">Please confirm access to your account by entering the authentication code provided by your authenticator application.</p>
                        <hr class="border-white/10 mt-4 mb-4">
                    </div>

                    <div class="relative z-10" x-cloak x-show="recovery">
                        <h2 class="text-2xl font-extrabold text-white tracking-tight drop-shadow-md">Recovery Code</h2>
                        <p class="mt-1 text-gray-300 text-sm drop-shadow">Please confirm access to your account by entering one of your emergency recovery codes.</p>
                        <hr class="border-white/10 mt-4 mb-4">
                    </div>

                    @if ($errors->any())
                        <div class="flex items-center p-3 text-sm text-red-300 bg-red-900/40 border border-red-500/30 rounded-lg relative z-10 mb-5" role="alert">
                            <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <div>
                                @foreach ($errors->all() as $error){{ $error }}<br>@endforeach
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('two-factor.login') }}" autocomplete="on" class="space-y-5 relative z-10">
                        @csrf

                        <div x-show="!recovery">
                            <label for="code" class="block text-sm font-medium text-gray-200 mb-2">Code</label>
                            <input type="text" inputmode="numeric" name="code" id="code" autofocus x-ref="code" autocomplete="one-time-code"
                                class="block w-full px-4 py-2 h-11 border border-white/20 bg-black/20 text-white rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-sky-400/60 focus:border-transparent text-sm placeholder-gray-500 transition-all backdrop-blur-sm">
                        </div>

                        <div x-cloak x-show="recovery">
                            <label for="recovery_code" class="block text-sm font-medium text-gray-200 mb-2">Recovery Code</label>
                            <input type="text" name="recovery_code" id="recovery_code" x-ref="recovery_code" autocomplete="one-time-code"
                                class="block w-full px-4 py-2 h-11 border border-white/20 bg-black/20 text-white rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-sky-400/60 focus:border-transparent text-sm placeholder-gray-500 transition-all backdrop-blur-sm">
                        </div>

                        <div class="flex items-center justify-between relative z-10 mt-2">
                            <button type="button" class="text-sm text-sky-400 hover:text-sky-300 font-medium transition-all cursor-pointer"
                                x-show="!recovery"
                                x-on:click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })">
                                Use a recovery code
                            </button>
                            <button type="button" class="text-sm text-sky-400 hover:text-sky-300 font-medium transition-all cursor-pointer"
                                x-cloak
                                x-show="recovery"
                                x-on:click="recovery = false; $nextTick(() => { $refs.code.focus() })">
                                Use an authentication code
                            </button>
                        </div>

                        <div class="relative z-10 pt-1">
                            <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-sky-500 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400 transition-all transform active:scale-[0.98] cursor-pointer"
                                style="letter-spacing: 0.5px;">
                                Log in
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        import { initDarkVeil } from '/assets/js/darkveil.js';
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('darkveil-canvas');
            if (canvas) {
                initDarkVeil(canvas, {
                    hueShift: 200,
                    noiseIntensity: 0.04,
                    scanlineIntensity: 0.1,
                    speed: 0.15,
                    scanlineFrequency: 80,
                    warpAmount: 0.07
                });
            }
        });
    </script>
</body>

</html>
