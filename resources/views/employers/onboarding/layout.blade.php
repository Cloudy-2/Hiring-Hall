<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employer Onboarding — Hiring Hall</title>

    <link rel="icon" href="/assets/raw/new.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        :root {
            --navy: #0B1A2E;
            --navy-light: #132D4F;
            --navy-mid: #16253d;
            --gold: #E5A100;
            --gold-light: #F5B800;
            --gold-dark: #C48B00;
            --gold-glow: rgba(229, 161, 0, 0.15);
            --emerald: #10b981;
            --emerald-glow: rgba(16, 185, 129, 0.12);
            --radius: 14px;
            --card-shadow: 0 8px 40px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: #ffffff;
            overflow-x: hidden;
        }

        /* ── Animated background ── */
        .bg-pattern {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(229, 161, 0, 0.04) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 85% 80%, rgba(99, 102, 241, 0.03) 0%, transparent 60%),
                linear-gradient(180deg, #f7f8fc 0%, #f0f2fa 40%, #fafaf7 100%);
        }

        .bg-pattern::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--gold-glow) 0%, transparent 70%);
            animation: float-orb 18s ease-in-out infinite;
        }

        .bg-pattern::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.06) 0%, transparent 70%);
            animation: float-orb 22s ease-in-out infinite reverse;
        }

        @keyframes float-orb {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            25% {
                transform: translate(30px, -20px) scale(1.08);
            }

            50% {
                transform: translate(-15px, 25px) scale(0.95);
            }

            75% {
                transform: translate(20px, 15px) scale(1.05);
            }
        }

        .page-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Top bar ── */
        .onboard-topbar {
            background: #ffffff;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .onboard-topbar .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .onboard-topbar .brand img {
            height: 38px;
            width: 38px;
            object-fit: contain;
        }

        .onboard-topbar .brand-text span {
            color: var(--navy);
            font-weight: 700;
            font-size: 17px;
            letter-spacing: -0.2px;
        }

        .onboard-topbar .brand-text small {
            color: var(--gold-dark);
            font-size: 10px;
            display: block;
            margin-top: -1px;
            font-weight: 500;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .onboard-topbar .user-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--navy);
            padding: 6px 14px 6px 10px;
            border-radius: 24px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
            font-weight: 500;
            border: 1px solid rgba(11, 26, 46, 0.08);
        }

        .onboard-topbar .user-pill .avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--gold);
            color: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
        }

        /* ── Progress stepper ── */
        .stepper-wrapper {
            padding: 28px 20px 8px;
            max-width: 560px;
            margin: 0 auto;
            animation: fadeSlideDown 0.5s ease-out;
        }

        .stepper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
        }

        .step-bubble {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
            border: 2.5px solid #dce1ea;
            background: #fff;
            color: #b0b8c9;
            position: relative;
            z-index: 2;
            flex-shrink: 0;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .step-bubble.active {
            border-color: var(--gold);
            background: var(--gold);
            color: var(--navy);
            box-shadow: 0 0 0 5px var(--gold-glow), 0 4px 12px rgba(229, 161, 0, 0.2);
            transform: scale(1.08);
            animation: pulse-glow 2.5s ease-in-out infinite;
        }

        .step-bubble.done {
            border-color: var(--emerald);
            background: var(--emerald);
            color: #fff;
            box-shadow: 0 0 0 4px var(--emerald-glow);
        }

        .step-connector {
            width: 60px;
            height: 3px;
            background: #e2e8f0;
            position: relative;
            overflow: hidden;
            border-radius: 2px;
        }

        .step-connector .fill {
            position: absolute;
            inset: 0;
            background: var(--emerald);
            transform-origin: left;
            transform: scaleX(0);
            transition: transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
            border-radius: 2px;
        }

        .step-connector.done .fill {
            transform: scaleX(1);
        }

        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 0 5px var(--gold-glow), 0 4px 12px rgba(229, 161, 0, 0.2);
            }

            50% {
                box-shadow: 0 0 0 8px rgba(229, 161, 0, 0.08), 0 4px 16px rgba(229, 161, 0, 0.25);
            }
        }

        .step-labels {
            display: flex;
            justify-content: center;
            gap: 48px;
            padding: 10px 20px 24px;
            max-width: 600px;
            margin: 0 auto;
        }

        .step-label {
            font-size: 11px;
            color: #a0aec0;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            transition: all 0.3s ease;
            width: 76px;
        }

        .step-label.active {
            color: var(--navy);
            font-weight: 700;
        }

        .step-label.done {
            color: var(--emerald);
        }

        @media (max-width: 500px) {
            .step-connector {
                width: 32px;
            }

            .step-bubble {
                width: 36px;
                height: 36px;
                font-size: 13px;
            }

            .step-labels {
                gap: 20px;
            }

            .step-label {
                font-size: 10px;
                width: 60px;
            }
        }

        /* ── Main content card ── */
        .onboard-card {
            max-width: 700px;
            margin: 0 auto 48px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            padding: 40px 44px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            animation: cardEntrance 0.6s cubic-bezier(0.22, 1, 0.36, 1);
            position: relative;
            overflow: hidden;
        }

        .onboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 50%, var(--gold) 100%);
            background-size: 200% 100%;
            animation: shimmer-bar 3s ease-in-out infinite;
        }

        @keyframes shimmer-bar {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(24px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes fadeSlideDown {
            from {
                opacity: 0;
                transform: translateY(-12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 640px) {
            .onboard-card {
                margin: 0 12px 32px;
                padding: 28px 20px;
                border-radius: 16px;
            }
        }

        .onboard-card h2 {
            font-size: 24px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .onboard-card .subtitle {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 28px;
            line-height: 1.6;
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            margin-top: 28px;
            margin-bottom: 4px;
        }

        .form-section-subtitle {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .section-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 28px 0 18px;
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, #e2e8f0 0%, transparent 100%);
        }

        .section-divider i {
            color: var(--gold);
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 20px;
            animation: fieldFadeIn 0.4s ease-out both;
        }

        @keyframes fieldFadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group:nth-child(1) {
            animation-delay: 0.05s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.1s;
        }

        .form-group:nth-child(3) {
            animation-delay: 0.15s;
        }

        .form-group:nth-child(4) {
            animation-delay: 0.2s;
        }

        .form-group:nth-child(5) {
            animation-delay: 0.25s;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 7px;
        }

        .form-group label .required {
            color: #ef4444;
            font-size: 11px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="email"],
        .form-group input[type="url"],
        .form-group input[type="tel"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 11px 15px;
            border: 1.5px solid #e2e8f0;
            border-radius: var(--radius);
            font-size: 14px;
            font-family: inherit;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        .form-group input:hover,
        .form-group select:hover,
        .form-group textarea:hover {
            border-color: #cbd5e1;
            background: #fff;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 4px var(--gold-glow);
            background: #fff;
            transform: translateY(-1px);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 90px;
        }

        .form-group .hint {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 560px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .onboard-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 36px;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 11px 22px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            color: #475569;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .btn-back:hover {
            border-color: #94a3b8;
            transform: translateX(-2px);
        }

        .btn-back:active {
            transform: scale(0.97);
        }

        .btn-next {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 30px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-light) 100%);
            color: var(--navy);
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(229, 161, 0, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.2);
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-next::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.15) 50%, transparent 100%);
            transform: translateX(-100%);
            transition: transform 0.5s;
        }

        .btn-next:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(229, 161, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .btn-next:hover::after {
            transform: translateX(100%);
        }
    </style>
</head>

<body>
    <div class="bg-pattern"></div>
    <div class="page-wrapper">
        <header class="onboard-topbar">
            <a href="#" class="brand">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo">
                <div class="brand-text">
                    <span>Hiring Hall</span>
                    <small>Employer Onboarding</small>
                </div>
            </a>
            <div class="user-pill">
                <div class="avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <span>{{ auth()->user()->name }}</span>
            </div>
        </header>

        <div class="stepper-wrapper">
            <div class="stepper">
                <div class="step-bubble {{ $step >= 1 ? 'active' : '' }} {{ $step > 1 ? 'done' : '' }}">
                    {!! $step > 1 ? '<i class="ri-check-line"></i>' : '1' !!}
                </div>
                <div class="step-connector {{ $step > 1 ? 'done' : '' }}">
                    <div class="fill"></div>
                </div>
                <div class="step-bubble {{ $step >= 2 ? 'active' : '' }} {{ $step > 2 ? 'done' : '' }}">
                    {!! $step > 2 ? '<i class="ri-check-line"></i>' : '2' !!}
                </div>
                <div class="step-connector {{ $step > 2 ? 'done' : '' }}">
                    <div class="fill"></div>
                </div>
                <div class="step-bubble {{ $step >= 3 ? 'active' : '' }} {{ $step > 3 ? 'done' : '' }}">
                    {!! $step > 3 ? '<i class="ri-check-line"></i>' : '3' !!}
                </div>
                <div class="step-connector {{ $step > 3 ? 'done' : '' }}">
                    <div class="fill"></div>
                </div>
                <div class="step-bubble {{ $step >= 4 ? 'active' : '' }} {{ $step > 4 ? 'done' : '' }}">
                    {!! $step > 4 ? '<i class="ri-check-line"></i>' : '4' !!}
                </div>
            </div>
            <div class="step-labels">
                <div class="step-label {{ $step >= 1 ? 'active' : '' }} {{ $step > 1 ? 'done' : '' }}">Company</div>
                <div class="step-label {{ $step >= 2 ? 'active' : '' }} {{ $step > 2 ? 'done' : '' }}">Contact</div>
                <div class="step-label {{ $step >= 3 ? 'active' : '' }} {{ $step > 3 ? 'done' : '' }}">Media</div>
                <div class="step-label {{ $step >= 4 ? 'active' : '' }} {{ $step > 4 ? 'done' : '' }}">Review</div>
            </div>
        </div>

        {{-- Main content slot --}}
        @yield('content')

        <footer class="mt-auto pb-6 text-center text-[13px] text-slate-400">
            &copy; {{ date('Y') }} Hill Business Consulting Services. All rights reserved.
        </footer>
    </div>
</body>

</html>
