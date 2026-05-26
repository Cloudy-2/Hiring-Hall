<!DOCTYPE html>
<html lang="en" style="height:100%;overflow:hidden;">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register — Hill Business Consulting Services</title>
    <link rel="icon" href="/assets/raw/new.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@800;900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');

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

        /* Custom scrollbar */
        .form-scroll::-webkit-scrollbar { width: 3px; }
        .form-scroll::-webkit-scrollbar-track { background: transparent; }
        .form-scroll::-webkit-scrollbar-thumb { background: rgba(14,165,233,0.3); border-radius: 2px; }

        /* Autofill overrides */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #040712 inset !important;
            -webkit-text-fill-color: white !important;
        }
    </style>
</head>

<body class="text-white relative h-screen overflow-hidden bg-[#050510]">
    <!-- Darkveil WebGL Background -->
    <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 0; pointer-events: none; background: #050510;">
        <canvas id="darkveil-canvas" style="width: 100%; height: 100%; display: block;"></canvas>
    </div>

    <!-- Split Layout -->
    <div class="h-screen flex items-center relative z-10 w-full">

        <!-- Back to Landing Button -->
        <a href="/" class="fixed top-5 left-5 z-50 flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 rounded-full backdrop-blur-sm transition-all group">
            <i class="fa-solid fa-arrow-left text-xs group-hover:-translate-x-0.5 transition-transform"></i>
            Back to Home
        </a>

        <!-- LEFT: Branding Panel -->
        <div class="hidden md:flex flex-col items-center justify-center w-1/2 h-full px-16 text-center">
            <div class="animate-float">
                <div class="flex flex-col items-center mb-10">
                    <!-- Modernized Branding Stack -->
                    <div class="mb-4">
                        <img class="h-32 w-auto object-contain drop-shadow-[0_0_25px_rgba(239,68,68,0.2)] animate-breathe" src="/assets/logos/hiring-hall-full.png" alt="Logo" />
                    </div>
                    <div class="flex flex-col items-center">
                        <h1 class="text-[52px] font-black tracking-tighter text-white uppercase leading-none" style="font-family: 'Montserrat', sans-serif;">
                            Hiring Hall
                        </h1>
                        <div class="flex items-center gap-3 mt-4">
                            <div class="h-[1px] w-8 bg-gradient-to-r from-transparent to-sky-500/50"></div>
                            <span class="text-[10px] font-black text-sky-400 uppercase tracking-[0.5em] opacity-80" style="font-family: 'Montserrat', sans-serif;">Hillbcs Powered</span>
                            <div class="h-[1px] w-8 bg-gradient-to-l from-transparent to-sky-500/50"></div>
                        </div>
                    </div>
                </div>
                <p class="mt-4 text-gray-300 text-base max-w-xs mx-auto leading-relaxed">
                    Create your account and start your journey with us today.
                </p>
                <div class="mt-8 flex items-center justify-center gap-2 text-sky-400 text-sm font-medium">
                    <i class="fa-solid fa-circle text-[6px]"></i>
                    <span>Secure</span>
                    <i class="fa-solid fa-circle text-[6px]"></i>
                    <span>Trusted</span>
                    <i class="fa-solid fa-circle text-[6px]"></i>
                    <span>Professional</span>
                </div>
            </div>
        </div>

        <!-- RIGHT: Register Form (scrollable) -->
        <div class="flex items-center justify-center w-full md:w-1/2 h-full px-6 overflow-y-auto form-scroll py-8">
            <div class="w-full max-w-md my-auto">

                <!-- Mobile logo -->
                <div class="md:hidden text-center mb-10 flex flex-col items-center">
                    <img class="h-24 w-auto object-contain drop-shadow-lg animate-breathe mb-4" src="/assets/logos/hiring-hall-full.png" alt="Logo" />
                    <div class="flex flex-col items-center">
                        <span class="text-3xl font-black tracking-tighter text-white uppercase leading-none" style="font-family: 'Montserrat', sans-serif;">Hiring Hall</span>
                        <span class="text-[8px] font-black text-sky-400 uppercase tracking-[0.4em] mt-2 opacity-70" style="font-family: 'Montserrat', sans-serif;">Hillbcs Powered</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}" autocomplete="on"
                    class="w-full space-y-4 bg-[#0a0f1c]/70 backdrop-blur-2xl border border-white/5 p-8 rounded-2xl shadow-[0_8px_32px_0_rgba(0,0,0,0.6)] animate-form-breathe relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/8 to-transparent pointer-events-none"></div>
                    @csrf

                    <div class="relative z-10">
                        <h2 class="text-2xl font-extrabold text-white tracking-tight drop-shadow-md">Create an Account</h2>
                        <p class="mt-1 text-gray-300 text-sm">
                            Join Hiring Hall and start your journey with us today.
                        </p>
                        <hr class="border-white/10 mt-4 mb-1">
                    </div>

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="flex items-start p-3 text-sm text-red-300 bg-red-900/40 border border-red-500/30 rounded-lg relative z-10" role="alert">
                            <svg class="w-4 h-4 mr-2 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <div>@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
                        </div>
                    @endif

                    @php
                        $defaultRole = old('role');
                        if ($defaultRole === null) {
                            $defaultRole = request('role') === 'employer' ? 'employer' : 'applicant';
                        }
                        $roleParam = request('role');
                        $showBothRoles = $roleParam === null || ($roleParam !== 'applicant' && $roleParam !== 'employer');
                    @endphp

                    {{-- Account Type --}}
                    <div class="grid {{ $showBothRoles ? 'grid-cols-2' : 'grid-cols-1' }} gap-3 relative z-10" role="radiogroup" aria-label="Account type">
                        @if ($showBothRoles || $roleParam === 'applicant')
                            <label for="role_candidate" class="cursor-pointer block">
                                <input type="radio" id="role_candidate" name="role" value="applicant" class="peer sr-only" {{ $defaultRole === 'applicant' ? 'checked' : '' }}>
                                <div class="rounded-xl border border-white/10 bg-black/20 p-3 hover:bg-white/5 peer-checked:border-sky-500 peer-checked:bg-sky-500/10 peer-checked:ring-1 peer-checked:ring-sky-500/40 transition-all text-center backdrop-blur-sm">
                                    <i class="fa-solid fa-user-tie text-xl mb-1 text-gray-400 peer-checked:text-sky-400"></i>
                                    <div class="font-semibold text-sm text-gray-300 peer-checked:text-sky-400">Applicant</div>
                                </div>
                            </label>
                        @endif
                        @if ($showBothRoles || $roleParam === 'employer')
                            <label for="role_employer" class="cursor-pointer block">
                                <input type="radio" id="role_employer" name="role" value="employer" class="peer sr-only" {{ $defaultRole === 'employer' ? 'checked' : '' }}>
                                <div class="rounded-xl border border-white/10 bg-black/20 p-3 hover:bg-white/5 peer-checked:border-sky-500 peer-checked:bg-sky-500/10 peer-checked:ring-1 peer-checked:ring-sky-500/40 transition-all text-center backdrop-blur-sm">
                                    <i class="fa-solid fa-briefcase text-xl mb-1 text-gray-400 peer-checked:text-sky-400"></i>
                                    <div class="font-semibold text-sm text-gray-300 peer-checked:text-sky-400">Employer</div>
                                </div>
                            </label>
                        @endif
                    </div>

                    {{-- Name --}}
                    <div class="relative z-10">
                        <label for="name" class="block text-sm font-medium text-gray-200 mb-1">Full Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            autocomplete="name" placeholder="John Doe"
                            class="block w-full px-4 py-2 h-11 border border-white/20 bg-black/20 text-white rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-sky-400/60 focus:border-transparent text-sm placeholder-gray-500 transition-all backdrop-blur-sm">
                    </div>

                    {{-- Email --}}
                    <div class="relative z-10">
                        <label for="email" class="block text-sm font-medium text-gray-200 mb-1">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autocomplete="username" placeholder="name@company.com"
                            class="block w-full px-4 py-2 h-11 border border-white/20 bg-black/20 text-white rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-sky-400/60 focus:border-transparent text-sm placeholder-gray-500 transition-all backdrop-blur-sm">
                    </div>

                    {{-- Password --}}
                    <div class="relative z-10">
                        <label for="password" class="block text-sm font-medium text-gray-200 mb-1">Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                placeholder="*************"
                                class="block w-full px-4 py-2 pr-10 h-11 border border-white/20 bg-black/20 text-white rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-sky-400/60 focus:border-transparent text-sm placeholder-gray-500 transition-all backdrop-blur-sm">
                            <span onclick="togglePassword('password', 'togglePasswordIcon')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <i id="togglePasswordIcon" class="fa-regular fa-eye text-gray-400 hover:text-white transition-colors"></i>
                            </span>
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="relative z-10">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-200 mb-1">Confirm Password</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password" placeholder="*************"
                                class="block w-full px-4 py-2 pr-10 h-11 border border-white/20 bg-black/20 text-white rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-sky-400/60 focus:border-transparent text-sm placeholder-gray-500 transition-all backdrop-blur-sm">
                            <span onclick="togglePassword('password_confirmation', 'toggleConfirmPasswordIcon')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                                <i id="toggleConfirmPasswordIcon" class="fa-regular fa-eye text-gray-400 hover:text-white transition-colors"></i>
                            </span>
                        </div>
                    </div>

                    {{-- Terms & Privacy --}}
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="relative z-10">
                            <label for="terms" class="flex items-start cursor-pointer">
                                <input id="terms" name="terms" type="checkbox" required
                                    class="mt-0.5 h-4 w-4 text-sky-500 focus:ring-sky-400/50 bg-black/20 border-white/20 rounded">
                                <span class="ml-2 text-sm text-gray-300">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="' . route('terms.show') . '" class="underline text-sky-400 hover:text-sky-300">' . __('Terms of Service') . '</a>',
                                        'privacy_policy' => '<a target="_blank" href="' . route('policy.show') . '" class="underline text-sky-400 hover:text-sky-300">' . __('Privacy Policy') . '</a>',
                                    ]) !!}
                                </span>
                            </label>
                        </div>
                    @endif

                    {{-- Submit --}}
                    <div class="pt-1 relative z-10">
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-sky-500 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400 transition-all transform active:scale-[0.98]"
                            style="letter-spacing: 0.5px;">
                            Create your account
                        </button>
                    </div>

                    {{-- Already Registered --}}
                    <p class="text-center text-sm text-gray-300 relative z-10">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-medium text-sky-400 hover:text-sky-300 transition-all">
                            Sign in
                        </a>
                    </p>
                </form>
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
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
</body>

</html>
