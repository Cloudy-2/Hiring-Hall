<!DOCTYPE html>
<html lang="en" style="height:100%;overflow:hidden;">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email — Hill Business Consulting Services</title>
    <link rel="icon" href="/assets/raw/new.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap');
        body { font-family: "Rubik", sans-serif; font-optical-sizing: auto; font-style: normal; }

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

        @keyframes pulse-ring {
            0%, 100% { box-shadow: 0 0 0 0 rgba(14,165,233,0.4); }
            50% { box-shadow: 0 0 0 12px rgba(14,165,233,0); }
        }
        .animate-pulse-ring { animation: pulse-ring 3s ease-in-out infinite; }
    </style>
</head>

<body class="text-white relative h-screen overflow-hidden bg-[#050510]">
    <!-- Darkveil WebGL Background -->
    <div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 0; pointer-events: none; background: #050510;">
        <canvas id="darkveil-canvas" style="width: 100%; height: 100%; display: block;"></canvas>
    </div>

    <!-- Split Layout -->
    <div class="h-screen flex items-center relative z-10 w-full">

        <!-- LEFT: Branding Panel -->
        <div class="hidden md:flex flex-col items-center justify-center w-1/2 h-full px-16 text-center">
            <div class="animate-float">
                <img class="mx-auto h-36 w-auto drop-shadow-2xl animate-breathe mb-8" src="/assets/logo.png" alt="Logo" />
                <h1 class="text-4xl font-extrabold text-white tracking-tight leading-tight drop-shadow-lg">
                    Hill Business<br>Consulting Services
                </h1>
                <p class="mt-4 text-gray-300 text-base max-w-xs mx-auto leading-relaxed">
                    Almost there! Verify your email to activate your account.
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

        <!-- RIGHT: Card Panel -->
        <div class="flex items-center justify-center w-full md:w-1/2 h-full px-6">
            <div class="w-full max-w-md">

                <!-- Mobile logo -->
                <div class="md:hidden text-center mb-6">
                    <img class="mx-auto h-20 w-auto drop-shadow-lg animate-breathe" src="/assets/logo.png" alt="Logo" />
                </div>

                <div class="w-full bg-[#0a0f1c]/70 backdrop-blur-2xl border border-white/5 p-8 rounded-2xl shadow-[0_8px_32px_0_rgba(0,0,0,0.6)] animate-form-breathe relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/8 to-transparent pointer-events-none"></div>

                    <!-- Header -->
                    <div class="relative z-10 flex flex-col items-center text-center mb-6">
                        <div class="w-16 h-16 rounded-full bg-sky-500/20 border border-sky-500/30 flex items-center justify-center mb-4 animate-pulse-ring">
                            <i class="fa-solid fa-envelope-circle-check text-sky-400 text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-extrabold text-white tracking-tight">Verify Your Email</h2>
                        <p class="text-gray-400 text-sm mt-1">One more step to get started</p>
                        <hr class="border-white/10 mt-4 w-full">
                    </div>

                    <!-- Info box -->
                    <div class="mb-5 p-4 bg-sky-500/8 border border-sky-500/20 rounded-xl relative z-10">
                        <p class="text-sm text-sky-200 leading-relaxed">
                            <i class="fa-solid fa-info-circle mr-2 text-sky-400"></i>
                            Before continuing, please verify your email address by clicking on the link we just emailed to you.
                        </p>
                    </div>

                    <!-- Success message -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-5 p-4 bg-green-900/40 border border-green-500/30 rounded-xl flex items-start gap-3 relative z-10">
                            <i class="fa-solid fa-circle-check text-green-400 mt-0.5 shrink-0"></i>
                            <p class="text-sm text-green-300">A new verification link has been sent to your email address.</p>
                        </div>
                    @endif

                    <!-- Resend button -->
                    <form method="POST" action="{{ route('verification.send') }}" class="mb-4 relative z-10">
                        @csrf
                        <button type="submit"
                            class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-sky-500 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400 transition-all transform active:scale-[0.98]"
                            style="letter-spacing: 0.5px;">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            Resend Verification Email
                        </button>
                    </form>

                    <!-- Log out -->
                    <div class="pt-4 border-t border-white/10 flex items-center justify-end relative z-10">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 text-sm text-gray-400 hover:text-gray-200 transition-colors">
                                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Hint -->
                <p class="mt-5 text-center text-xs text-gray-500">
                    Didn't receive the email? Check your spam folder or try resending the verification link above.
                </p>
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
