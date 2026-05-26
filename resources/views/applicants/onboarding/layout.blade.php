<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Get Started — Hiring Hall</title>

    <link rel="icon" href="/assets/raw/new.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
    {{-- TomSelect for searchable dropdowns --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css">

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

        /* ── Dark Mode - Onboarding Card ── */
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .onboard-card {
            background: rgba(15, 23, 42, 0.92);
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.3), 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .onboard-card h2 {
            color: #f1f5f9;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .onboard-card .subtitle {
            color: #94a3b8;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .section-divider {
            color: #cbd5e1;
        }

        /* ── Section label ── */
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

        /* ── Form elements ── */
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
        .form-group input[type="date"],
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

        /* Modern date field: wrapper with icon, clean input */
        .form-group .date-field {
            position: relative;
            display: block;
        }
        .form-group .date-field input[type="date"] {
            width: 100%;
            padding: 11px 44px 11px 15px;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }
        .form-group .date-field input[type="date"]::-webkit-calendar-picker-indicator {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 22px;
            height: 22px;
            padding: 0;
            margin: 0;
            opacity: 0.6;
            cursor: pointer;
        }
        .form-group .date-field .date-field-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #94a3b8;
            font-size: 18px;
            transition: color 0.2s;
        }
        .form-group .date-field:focus-within .date-field-icon {
            color: var(--gold);
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

        .form-group .hint i {
            font-size: 13px;
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

        /* ── Tag / chip input ── */
        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
            min-height: 40px;
        }

        .tag-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 24px;
            background: #f1f5f9;
            border: 1.5px solid #e2e8f0;
            font-size: 13px;
            color: #334155;
            transition: all 0.25s ease;
            animation: chipPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes chipPop {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .tag-item:focus-within {
            border-color: var(--gold);
            background: #fff;
            box-shadow: 0 0 0 3px var(--gold-glow);
        }

        .tag-item input[type="text"] {
            border: none;
            background: transparent;
            outline: none;
            font-size: 13px;
            font-family: inherit;
            color: #334155;
            width: 150px;
            padding: 0;
        }

        .tag-item .remove-tag {
            cursor: pointer;
            color: #b0b8c9;
            font-size: 15px;
            line-height: 1;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }

        .tag-item .remove-tag:hover {
            color: #fff;
            background: #ef4444;
            transform: scale(1.1);
        }

        .add-tag-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 16px;
            border-radius: 24px;
            border: 1.5px dashed #cbd5e1;
            background: transparent;
            color: #64748b;
            font-size: 13px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .add-tag-btn:hover {
            border-color: var(--gold);
            color: var(--gold);
            background: rgba(229, 161, 0, 0.03);
            transform: translateY(-1px);
        }

        .add-tag-btn:active {
            transform: scale(0.96);
        }

        /* ── Checkbox grid ── */
        .checkbox-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        @media (max-width: 480px) {
            .checkbox-grid {
                grid-template-columns: 1fr;
            }
        }

        .checkbox-grid label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #e8ecf1;
            background: #fafbfe;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 13px;
            color: #475569;
            position: relative;
            overflow: hidden;
        }

        .checkbox-grid label::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(229, 161, 0, 0.04) 0%, rgba(229, 161, 0, 0.01) 100%);
            opacity: 0;
            transition: opacity 0.25s;
        }

        .checkbox-grid label:hover {
            border-color: var(--gold);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .checkbox-grid label:hover::before {
            opacity: 1;
        }

        .checkbox-grid input[type="checkbox"] {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: all 0.25s;
        }

        .checkbox-grid input[type="checkbox"]:checked {
            background: var(--gold);
            border-color: var(--gold);
        }

        .checkbox-grid input[type="checkbox"]:checked::after {
            content: '✓';
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--navy);
            font-size: 13px;
            font-weight: 700;
        }

        .checkbox-grid label:has(input:checked) {
            border-color: var(--gold);
            background: rgba(229, 161, 0, 0.04);
            color: var(--navy);
            font-weight: 600;
            box-shadow: 0 0 0 3px var(--gold-glow);
        }

        .checkbox-grid label:has(input:checked)::before {
            opacity: 1;
        }

        /* ── Buttons ── */
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

        .btn-next:active {
            transform: translateY(0) scale(0.97);
        }

        .btn-next i {
            transition: transform 0.25s;
        }

        .btn-next:hover i {
            transform: translateX(3px);
        }

        .btn-launch {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            color: #fff;
            box-shadow: 0 4px 14px rgba(11, 26, 46, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.06);
            padding: 14px 36px;
            font-size: 15px;
        }

        .btn-launch:hover {
            box-shadow: 0 6px 24px rgba(11, 26, 46, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.06);
        }

        .btn-launch i {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .btn-launch:hover i {
            transform: translateY(-3px) rotate(-15deg);
        }

        /* ── Validation errors ── */
        .validation-errors {
            background: linear-gradient(135deg, #fef2f2 0%, #fff1f0 100%);
            border: 1px solid #fecaca;
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #dc2626;
            animation: shake 0.4s ease-out;
        }

        .validation-errors ul {
            margin: 6px 0 0 18px;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            15% {
                transform: translateX(-6px);
            }

            30% {
                transform: translateX(5px);
            }

            45% {
                transform: translateX(-4px);
            }

            60% {
                transform: translateX(3px);
            }

            75% {
                transform: translateX(-1px);
            }
        }

        /* ── Review card ── */
        .review-section {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e8ecf1;
            border-radius: 14px;
            padding: 22px 26px;
            margin-bottom: 16px;
            transition: all 0.25s ease;
            animation: reviewSlide 0.5s ease-out both;
        }

        .review-section:nth-child(1) {
            animation-delay: 0.1s;
        }

        .review-section:nth-child(2) {
            animation-delay: 0.2s;
        }

        .review-section:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes reviewSlide {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .review-section:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            transform: translateY(-1px);
        }

        .review-section h3 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .review-section h3 i {
            color: var(--gold);
            font-size: 17px;
        }

        .review-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 13px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        }

        .review-row:last-child {
            border-bottom: none;
        }

        .review-row .label {
            color: #64748b;
        }

        .review-row .value {
            color: #1e293b;
            font-weight: 600;
            text-align: right;
            max-width: 60%;
        }

        .review-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }

        .review-tag {
            display: inline-block;
            padding: 5px 12px;
            background: rgba(229, 161, 0, 0.06);
            border: 1px solid rgba(229, 161, 0, 0.16);
            border-radius: 20px;
            font-size: 12px;
            color: var(--navy);
            font-weight: 600;
            transition: all 0.2s;
        }

        .review-tag:hover {
            background: rgba(229, 161, 0, 0.1);
            transform: translateY(-1px);
        }

        /* ── Resume upload ── */
        .cv-upload-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 36px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fafbfc;
            position: relative;
            overflow: hidden;
        }

        .cv-upload-zone::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(229, 161, 0, 0.02) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .cv-upload-zone:hover {
            border-color: var(--gold);
            background: #fffdf7;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
        }

        .cv-upload-zone:hover::before {
            opacity: 1;
        }

        .cv-upload-zone i {
            font-size: 36px;
            color: #94a3b8;
            display: block;
            margin-bottom: 10px;
            transition: all 0.3s;
        }

        .cv-upload-zone:hover i {
            color: var(--gold);
            transform: translateY(-4px);
        }

        .cv-upload-zone .upload-text {
            font-size: 14px;
            color: #475569;
            font-weight: 600;
        }

        .cv-upload-zone .upload-hint {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .cv-upload-zone.has-file {
            border-color: var(--emerald);
            background: #f0fdf4;
            border-style: solid;
        }

        .cv-upload-zone.has-file i {
            color: var(--emerald);
        }

        .cv-upload-zone.has-file:hover i {
            transform: none;
            color: var(--emerald);
        }

        /* ── Page footer ── */
        .onboard-footer {
            text-align: center;
            padding: 12px 20px 24px;
            color: #b0b8c9;
            font-size: 12px;
        }

        /* ── Loading state for buttons ── */
        .btn-next.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-next.loading i {
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="bg-pattern"></div>

    <div class="page-wrapper">
        {{-- Top bar --}}
        <div class="onboard-topbar">
            <a href="/" class="brand">
                <img src="{{ asset('assets/logo.png') }}" alt="HillBCS">
                <div class="brand-text">
                    <span>Hiring Hall</span>
                    <small>by Hill BCS</small>
                </div>
            </a>
            <div class="user-pill">
                <div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                {{ $user->name }}
            </div>
        </div>

        {{-- Stepper --}}
        <div class="stepper-wrapper">
            <div class="stepper">
                @for ($i = 1; $i <= $totalSteps; $i++)
                    <div class="step-bubble {{ $i < $step ? 'done' : ($i === $step ? 'active' : '') }}">
                        @if ($i < $step)
                            <i class="ri-check-line"></i>
                        @else
                            {{ $i }}
                        @endif
                    </div>
                    @if ($i < $totalSteps)
                        <div class="step-connector {{ $i < $step ? 'done' : '' }}">
                            <div class="fill"></div>
                        </div>
                    @endif
                @endfor
            </div>
        </div>
        <div class="step-labels">
            @foreach ($stepTitles as $i => $title)
                <div class="step-label {{ $i < $step ? 'done' : ($i === $step ? 'active' : '') }}">
                    {{ $title }}
                </div>
            @endforeach
        </div>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div style="max-width:700px; margin:0 auto; padding:0 16px;">
                <div class="validation-errors">
                    <strong><i class="ri-error-warning-line"></i> Please fix the following:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Step content --}}
        @yield('step-content')

        <div class="onboard-footer">
            Step {{ $step }} of {{ $totalSteps }} &middot; Your progress is saved automatically
        </div>
    </div>

    <script>
        // Add loading state to form submits
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                const btn = this.querySelector('.btn-next');
                if (btn) {
                    btn.classList.add('loading');
                    const icon = btn.querySelector('i');
                    if (icon) icon.className = 'ri-loader-4-line';
                    btn.innerHTML = '<i class="ri-loader-4-line" style="animation:spin 0.8s linear infinite"></i> Saving...';
                }
            });
        });
    </script>

    {{-- TomSelect for searchable dropdowns --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize TomSelect on all .tomselect-basic elements
            document.querySelectorAll('.tomselect-basic').forEach(select => {
                new TomSelect(select, {
                    create: false,
                    placeholder: select.getAttribute('placeholder') || 'Select an option...',
                    searchField: ['text', 'value'],
                    maxOptions: null,
                    dropdownParent: 'body',
                    allowEmptyOption: true
                });
            });
        });
    </script>
    <style>
        /* Custom TomSelect styling to match form theme */
        .ts-wrapper.single .ts-control {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 12px;
            background: white;
            font-size: 14px;
            color: #1f2937;
        }
        
        .ts-wrapper.single .ts-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .ts-dropdown {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            background: white;
            max-height: 300px;
        }
        
        .ts-dropdown .ts-dropdown-content {
            padding: 4px 0;
        }
        
        .ts-dropdown .option {
            padding: 10px 12px;
            font-size: 14px;
            color: #334155;
        }
        
        .ts-dropdown .option.selected {
            background: #eef2ff;
            color: #6366f1;
            font-weight: 500;
        }
        
        .ts-dropdown .option:hover {
            background: #f1f5f9;
        }
        
        .ts-control input::placeholder {
            color: #94a3b8;
        }

        /* Dark Mode - TomSelect */
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-wrapper.single .ts-control {
            background: #1e293b;
            border-color: rgba(255, 255, 255, 0.12);
            color: #e2e8f0;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-wrapper.single .ts-control:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-dropdown {
            background: #1e293b;
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-dropdown .option {
            color: #cbd5e1;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-dropdown .option:hover {
            background: #334155;
            color: #f1f5f9;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-dropdown .option.selected {
            background: #312e81;
            color: #a5b4fc;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-control input::placeholder {
            color: #64748b;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-wrapper .ts-clear-button {
            color: #94a3b8;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-wrapper .ts-clear-button:hover {
            color: #cbd5e1;
        }

        /* Dark Mode - General Form Elements */
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) input[type="text"],
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) input[type="email"],
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) input[type="number"],
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) input[type="date"],
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) select,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) textarea {
            background: #1e293b;
            border-color: rgba(255, 255, 255, 0.12);
            color: #e2e8f0;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) input[type="text"]::placeholder,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) input[type="email"]::placeholder,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) input[type="number"]::placeholder,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) textarea::placeholder {
            color: #64748b;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) input:focus,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) select:focus,
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) textarea:focus {
            border-color: #818cf8;
            background: #0f172a;
        }

        /* Dark Mode - Checkboxes */
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) input[type="checkbox"] {
            background: #1e293b;
            border-color: rgba(255, 255, 255, 0.12);
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) input[type="checkbox"]:checked {
            background: #6366f1;
            border-color: #6366f1;
        }

        /* Dark Mode - Labels */
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .form-group label {
            color: #e2e8f0;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .form-group .hint {
            color: #64748b;
        }
    </style>
</body>

</html>
