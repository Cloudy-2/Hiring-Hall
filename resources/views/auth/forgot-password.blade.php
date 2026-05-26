<!DOCTYPE html>
<html lang="en" style="height:100%;overflow:hidden;">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password — Hill Business Consulting Services</title>
    <link rel="icon" href="/assets/raw/new.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        <!-- Back Button -->
        <a href="{{ route('login') }}" class="fixed top-5 left-5 z-50 flex items-center gap-2 px-4 py-2 text-sm text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 rounded-full backdrop-blur-sm transition-all group">
            <i class="fa-solid fa-arrow-left text-xs group-hover:-translate-x-0.5 transition-transform"></i>
            Back to Login
        </a>



        <!-- Centered: Form Panel -->
        <div class="flex items-center justify-center w-full h-full px-6">
            <div class="w-full max-w-md">



                <div class="w-full bg-[#0a0f1c]/70 backdrop-blur-2xl border border-white/5 p-8 rounded-2xl shadow-[0_8px_32px_0_rgba(0,0,0,0.6)] animate-form-breathe relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/8 to-transparent pointer-events-none"></div>

                    <div class="relative z-10 mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-sky-500/20 border border-sky-500/30 flex items-center justify-center">
                                <i class="fa-solid fa-lock text-sky-400 text-sm"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-extrabold text-white tracking-tight">Forgot Password?</h2>
                            </div>
                        </div>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            No problem. Just enter your email address and we'll send you a password reset link to choose a new one.
                        </p>
                        <hr class="border-white/10 mt-4">
                    </div>

                    @session('status')
                        <div class="mb-4 p-3 rounded-lg bg-green-900/40 border border-green-500/30 text-sm text-green-300 flex items-center gap-2 relative z-10">
                            <i class="fa-solid fa-circle-check text-green-400"></i>
                            {{ $value }}
                        </div>
                    @endsession

                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded-lg bg-red-900/40 border border-red-500/30 text-sm text-red-300 flex items-center gap-2 relative z-10">
                            <i class="fa-solid fa-circle-xmark text-red-400 shrink-0"></i>
                            <div>@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                        @csrf

                        <div class="relative z-10">
                            <label for="email" class="block text-sm font-medium text-gray-200 mb-2">Email Address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                autocomplete="username" placeholder="name@company.com"
                                class="block w-full px-4 py-2 h-11 border border-white/20 bg-black/20 text-white rounded-lg shadow-inner focus:outline-none focus:ring-2 focus:ring-sky-400/60 focus:border-transparent text-sm placeholder-gray-500 transition-all backdrop-blur-sm">
                        </div>

                        <div class="relative z-10">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-3 px-4 border border-transparent rounded-lg shadow-lg text-sm font-semibold text-white bg-sky-500 hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400 transition-all transform active:scale-[0.98]"
                                style="letter-spacing: 0.5px;">
                                <i class="fa-solid fa-paper-plane text-xs"></i>
                                Send Password Reset Link
                            </button>
                        </div>
                    </form>

                    <p class="mt-5 text-center text-sm text-gray-400 relative z-10">
                        Remembered your password?
                        <a href="{{ route('login') }}" class="text-sky-400 hover:text-sky-300 font-medium transition-all">Sign in</a>
                    </p>
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
