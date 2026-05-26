<x-app-layout>
    <x-slot name="pageTitle">My Profile</x-slot>
    <x-slot name="url_1">{"link": "/applicant/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="title">My Profile</x-slot>
    <x-slot name="active">My Profile</x-slot>

    @include('applicants.partials.candidate-styles')

    @if(session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Profile updated',
                        text: @json(session('status')),
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            });
        </script>
    @endif

    {{-- ═══════════════ PROFILE STYLES ═══════════════ --}}
    <style>
        :root {
            --pf-primary: var(--cd-text);
            --pf-accent: var(--cd-accent);
            --pf-muted: var(--cd-text-muted);
            --pf-border: var(--cd-border);
            --pf-bg: var(--cd-bg-alt);
        }

        :is([data-theme-mode="dark"], .dark, [data-bs-theme="dark"], html.dark) body {
            background: #1f1f1f !important;
        }

        .pf-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid var(--cd-border);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        :is([data-theme-mode="dark"], .dark) .pf-card {
            --pf-primary: #f8fafc !important;
            --pf-muted: #94a3b8 !important;
            --pf-secondary: #cbd5e1 !important;
            background: #202124;
            border-color: #303134;
            backdrop-filter: none;
        }

        .pf-card-body {
            padding: 1.25rem;
        }

        /* ── Sidebar ── */
        .pf-sidebar-body {
            padding: 1.5rem 1.25rem 1.25rem;
            text-align: center;
        }

        .pf-avatar-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 0.85rem;
        }

        .pf-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #fff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .1);
        }

        :is([data-theme-mode="dark"], .dark) .pf-avatar {
            border-color: #1e293b;
        }

        .pf-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pf-online-dot {
            position: absolute;
            bottom: 6px;
            left: 6px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #22c55e;
            border: 3px solid #fff;
        }

        :is([data-theme-mode="dark"], .dark) .pf-online-dot {
            border-color: #1e293b;
        }

        .pf-name {
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--cd-text);
            margin-bottom: 2px;
        }

        :is([data-theme-mode="dark"], .dark) .pf-name {
            color: var(--pf-primary);
        }

        .pf-role {
            font-size: 0.8125rem;
            color: var(--cd-text-secondary);
            margin-bottom: 2px;
        }

        :is([data-theme-mode="dark"], .dark) .pf-role {
            color: var(--pf-secondary);
        }

        .pf-location {
            font-size: 0.78rem;
            color: var(--cd-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            margin-bottom: 1rem;
        }

        .pf-info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pf-info-table td {
            padding: 0.5rem 0;
            font-size: 0.8rem;
            border-bottom: 1px solid var(--cd-border);
        }

        :is([data-theme-mode="dark"], .dark) .pf-info-table td {
            border-bottom-color: rgba(255, 255, 255, 0.05);
        }

        .pf-info-table tr:last-child td {
            border-bottom: none;
        }

        .pf-info-label {
            color: var(--cd-text-muted);
            font-weight: 500;
            text-align: left;
        }

        :is([data-theme-mode="dark"], .dark) .pf-info-label {
            color: var(--pf-muted);
        }

        .pf-info-value {
            color: var(--cd-text);
            font-weight: 700;
            text-align: right;
        }

        :is([data-theme-mode="dark"], .dark) .pf-info-value {
            color: var(--pf-primary);
        }

        .pf-info-value .pf-badge-green {
            background: #dcfce7;
            color: #16a34a;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.72rem;
            font-weight: 700;
        }

        :is([data-theme-mode="dark"], .dark) .pf-badge-green {
            background: rgba(34, 197, 94, 0.15) !important;
            color: #4ade80 !important;
        }

        .pf-btn-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 0.55rem;
            border-radius: 8px;
            background: var(--cd-accent);
            color: #fff;
            border: none;
            font-weight: 700;
            font-size: 0.8125rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            margin-bottom: 0.4rem;
        }

        .pf-btn-primary:hover {
            background: #4338ca;
            color: #fff;
            transform: translateY(-1px);
        }

        .pf-btn-secondary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 0.55rem;
            border-radius: 8px;
            background: var(--cd-bg-alt);
            color: var(--cd-text);
            border: 1px solid var(--cd-border);
            font-weight: 600;
            font-size: 0.8125rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .pf-btn-secondary:hover {
            background: var(--cd-border);
            color: var(--cd-text);
        }

        .pf-social-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--cd-text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.6rem;
            padding-bottom: 0.4rem;
            border-bottom: 1px solid var(--cd-border);
            text-align: left;
        }

        .pf-social-link {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8125rem;
            color: var(--cd-text);
            text-decoration: none;
            padding: 0.35rem 0;
            transition: color 0.2s;
        }

        .pf-social-link:hover {
            color: var(--cd-accent);
        }

        .pf-social-link i {
            color: var(--cd-text-muted);
            font-size: 0.9rem;
            width: 18px;
            text-align: center;
        }

        /* ── Section Headers ── */
        .pf-sh {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
            font-weight: 800;
            color: var(--cd-text);
            margin-bottom: 0.85rem;
        }

        .pf-sh i {
            font-size: 1.1rem;
            color: var(--cd-text-muted);
        }

        .pf-text {
            font-size: 0.8125rem;
            color: var(--cd-text-secondary);
            line-height: 1.7;
        }

        .pf-empty {
            text-align: center;
            padding: 1rem 0;
            color: var(--cd-text-muted);
            font-size: 0.8125rem;
            font-style: italic;
        }

        /* ── Experience Timeline ── */
        .pf-exp-timeline {
            position: relative;
        }

        .pf-exp-center-line {
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--cd-border);
            transform: translateX(-50%);
        }

        .pf-exp-item {
            display: flex;
            align-items: flex-start;
            position: relative;
            margin-bottom: 2rem;
        }

        .pf-exp-item:last-child {
            margin-bottom: 0;
        }

        .pf-exp-dot {
            position: absolute;
            left: 50%;
            top: 8px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--cd-accent);
            border: 3px solid #fff;
            transform: translateX(-50%);
            z-index: 2;
        }

        :is([data-theme-mode="dark"], .dark) .pf-exp-dot {
            border-color: #1e293b;
        }

        .pf-exp-left {
            width: 47%;
            padding-right: 2rem;
            text-align: right;
        }

        .pf-exp-right {
            width: 47%;
            padding-left: 2rem;
            margin-left: auto;
        }

        .pf-exp-role {
            font-weight: 800;
            font-size: 0.875rem;
            color: var(--cd-text);
        }

        .pf-exp-date {
            display: inline-block;
            background: var(--cd-bg-alt);
            border: 1px solid var(--cd-border);
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--cd-text-muted);
            margin-bottom: 4px;
        }

        .pf-exp-company {
            font-size: 0.8125rem;
            color: var(--cd-accent);
            font-weight: 600;
            margin-bottom: 4px;
        }

        .pf-exp-resp {
            list-style: disc;
            padding-left: 1rem;
            margin-top: 0.4rem;
            font-size: 0.78rem;
            color: var(--cd-text-secondary);
            line-height: 1.6;
        }

        .pf-exp-resp li {
            margin-bottom: 3px;
        }

        /* ── Education ── */
        .pf-edu-item {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--cd-border);
        }

        .pf-edu-item:last-child {
            border-bottom: none;
        }

        .pf-edu-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--cd-text-muted);
            margin-top: 6px;
            flex-shrink: 0;
        }

        .pf-edu-title {
            font-weight: 700;
            font-size: 0.8125rem;
            color: var(--cd-text);
        }

        .pf-edu-sub {
            font-size: 0.75rem;
            color: var(--cd-text-muted);
        }

        /* ── Skills Pills ── */
        .pf-pill {
            display: inline-block;
            padding: 0.3rem 0.7rem;
            border: 1px solid var(--cd-border);
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--cd-text);
            background: var(--cd-bg-alt);
            transition: all 0.2s;
        }

        .pf-pill:hover {
            background: var(--cd-border);
            border-color: var(--cd-accent);
            color: var(--cd-accent);
        }

        /* ── Languages ── */
        .pf-lang-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.6rem;
        }

        .pf-lang-name {
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--cd-text);
            min-width: 70px;
        }

        .pf-lang-bar-bg {
            flex: 1;
            height: 6px;
            background: var(--cd-border);
            border-radius: 3px;
            overflow: hidden;
        }

        .pf-lang-bar {
            height: 100%;
            background: var(--cd-accent);
            border-radius: 3px;
            transition: width 0.5s ease;
        }

        .pf-lang-level {
            font-size: 0.72rem;
            color: var(--cd-text-muted);
            font-weight: 600;
            min-width: 90px;
            text-align: right;
        }

        /* ── Achievements ── */
        .pf-ach-item {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            padding: 0.4rem 0;
            font-size: 0.8125rem;
            color: var(--cd-text-secondary);
            line-height: 1.5;
        }

        .pf-ach-item i {
            color: #f59e0b;
            margin-top: 3px;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        /* ── Certifications ── */
        .pf-cert-item {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--cd-border);
        }

        .pf-cert-item:last-child {
            border-bottom: none;
        }

        .pf-cert-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #f59e0b;
            margin-top: 6px;
            flex-shrink: 0;
        }

        .pf-cert-title {
            font-weight: 700;
            font-size: 0.8125rem;
            color: var(--cd-text);
        }

        .pf-cert-sub {
            font-size: 0.75rem;
            color: var(--cd-text-muted);
        }

        /* ── References ── */
        .pf-ref-card {
            padding: 0.85rem;
            border-radius: 8px;
            border: 1px solid var(--cd-border);
            transition: all 0.2s;
        }

        .pf-ref-card:hover {
            border-color: var(--cd-accent);
            background: var(--cd-bg-alt);
        }

        .pf-ref-name {
            font-weight: 800;
            font-size: 0.8125rem;
            color: var(--cd-text);
        }

        .pf-ref-title {
            font-size: 0.75rem;
            color: var(--cd-accent);
            font-weight: 600;
            margin-bottom: 6px;
        }

        .pf-ref-meta {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem;
            color: var(--cd-text-muted);
            padding: 1px 0;
        }

        .pf-ref-meta i {
            font-size: 0.8rem;
            color: var(--cd-text-muted);
            width: 14px;
        }

        :is([data-theme-mode="dark"], .dark) hr {
            border-top-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* ── Surgical Dark Mode UI Fix ── */
        :is([data-theme-mode="dark"], .dark) .pf-name,
        :is([data-theme-mode="dark"], .dark) .pf-sh,
        :is([data-theme-mode="dark"], .dark) .pf-info-value,
        :is([data-theme-mode="dark"], .dark) .pf-exp-role,
        :is([data-theme-mode="dark"], .dark) .pf-edu-title,
        :is([data-theme-mode="dark"], .dark) .pf-cert-title,
        :is([data-theme-mode="dark"], .dark) .pf-ach-item span,
        :is([data-theme-mode="dark"], .dark) .pf-lang-name,
        :is([data-theme-mode="dark"], .dark) .pf-ref-name {
            color: #ffffff !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-sh i {
            color: #818cf8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-role,
        :is([data-theme-mode="dark"], .dark) .pf-text,
        :is([data-theme-mode="dark"], .dark) .pf-exp-resp,
        :is([data-theme-mode="dark"], .dark) .pf-edu-sub,
        :is([data-theme-mode="dark"], .dark) .pf-cert-sub,
        :is([data-theme-mode="dark"], .dark) .pf-ach-item {
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-info-label,
        :is([data-theme-mode="dark"], .dark) .pf-social-label,
        :is([data-theme-mode="dark"], .dark) .pf-empty,
        :is([data-theme-mode="dark"], .dark) .pf-exp-date,
        :is([data-theme-mode="dark"], .dark) .pf-lang-level,
        :is([data-theme-mode="dark"], .dark) .pf-ref-meta {
            color: #94a3b8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-pill {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-pill:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: #818cf8 !important;
            color: #818cf8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-exp-center-line {
            background: rgba(255, 255, 255, 0.1) !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-exp-date {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-edu-dot {
            background: #4b5563 !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-social-link {
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-social-link:hover {
            color: #818cf8 !important;
        }

        /* Verification Status Badge - Dark Mode */
        :is([data-theme-mode="dark"], .dark) [style*="background:#d1fae5"] {
            background: rgba(16, 185, 129, 0.15) !important;
            color: #6ee7b7 !important;
        }

        :is([data-theme-mode="dark"], .dark) [style*="background:#fee2e2"] {
            background: rgba(239, 68, 68, 0.15) !important;
            color: #fca5a5 !important;
        }

        :is([data-theme-mode="dark"], .dark) [style*="background:#fef3c7"] {
            background: rgba(217, 119, 6, 0.15) !important;
            color: #fcd34d !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-card {
            --pf-primary: #f8fafc !important;
            --pf-muted: #94a3b8 !important;
            --pf-secondary: #cbd5e1 !important;
            background: #202124 !important;
            border-color: #303134 !important;
            backdrop-filter: none;
        }

        :is([data-theme-mode="dark"], .dark) .pf-card:hover {
            background: #26272b !important;
            border-color: #818cf8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-card-body {
            color: #f8fafc !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-info-table tr,
        :is([data-theme-mode="dark"], .dark) .pf-info-table td {
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-label {
            color: #94a3b8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .pf-value {
            color: #f8fafc !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-header-section { border-bottom-color: rgba(255,255,255,0.08) !important; background: rgb(30, 32, 35) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-context-label { color: #ffffff !important; }
    </style>

    <div class="pf-page max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Modern Minimalist Header (Interactive Board Style) --}}
        <x-modern-header :container="false" chip="Professional Identity">
            <x-slot name="titleContent"><strong>My Profile</strong></x-slot>
            <x-slot name="description">
                Welcome to your profile, <b>{{ $user->name }}</b>! This is your professional bio where you can
                manage your career history, skills, and certifications to stand out to employers.
            </x-slot>
            <x-slot name="actions">
                <button type="button" onclick="openProfileEditModal(1)"
                    class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-white font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm hover:shadow-md border border-slate-200 dark:border-slate-700 text-sm">
                    <i class="ri-edit-line me-2 text-indigo-500"></i> Edit Profile
                </button>
            </x-slot>
        </x-modern-header>

        <div class="grid grid-cols-12 gap-x-5 gap-y-4">

            {{-- ═══════════════ SIDEBAR ═══════════════ --}}
            <div class="col-span-12 xl:col-span-3" id="wt-sidebar">
                <div class="pf-card">
                    <div class="pf-sidebar-body">
                        {{-- Avatar --}}
                        <div class="pf-avatar-wrap">
                            <div class="pf-avatar">
                                @if ($user->profile_photo_path)
                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                @else
                                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->name) }}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981"
                                        alt="{{ $user->name }}">
                                @endif
                            </div>
                            <div class="pf-online-dot"></div>
                        </div>

                        <h2 class="pf-name">{{ $profile->display_name ?? $user->name }}</h2>
                        <p class="pf-role">{{ $profile->job_title ?? $profile->title ?? 'Job title not set' }}</p>
                        <p class="pf-location" style="{{ $profile->rating ? 'margin-bottom: 0.25rem;' : '' }}"><i
                                class="ri-map-pin-2-fill"></i> {{ $profile->location ?? 'Location not set' }}</p>

                        {{-- Verification Status Badge --}}
                        @if($profile->verification_status)
                            <div style="margin-bottom: 1rem; display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;
                            @if($profile->verification_status === 'verified')
                                background:#d1fae5;color:#065f46;
                            @elseif($profile->verification_status === 'rejected')
                                background:#fee2e2;color:#991b1b;
                            @else
                                background:#fef3c7;color:#92400e;
                            @endif
                            ">
                                @if($profile->verification_status === 'verified')
                                    <i class="ri-check-line"></i> Verified
                                @elseif($profile->verification_status === 'rejected')
                                    <i class="ri-close-line"></i> Rejected
                                @else
                                    <i class="ri-time-line"></i> Pending
                                @endif
                            </div>
                        @endif

                        @if($profile->rating)
                            <div
                                style="display:flex; align-items:center; justify-content:center; gap:4px; margin-bottom: 1rem;">
                                <i class="ri-star-fill" style="color: #f59e0b; font-size: 1rem;"></i>
                                <span
                                    style="font-weight: 700; color: var(--pf-primary); font-size: 0.9rem;">{{ number_format($profile->rating, 1) }}</span>
                                @if($profile->rating_count)
                                    <span
                                        style="font-size: 0.75rem; color: var(--pf-muted);">({{ $profile->rating_count }})</span>
                                @endif
                            </div>
                        @endif

                        {{-- Info Table --}}
                        <table class="pf-info-table">
                            <tr>
                                <td class="pf-info-label">Work Mode</td>
                                <td class="pf-info-value">
                                    {{ $profile->work_mode ? Str::headline($profile->work_mode) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pf-info-label">Experience</td>
                                <td class="pf-info-value">
                                    {{ $profile->years_experience !== null ? $profile->years_experience . ' Years' : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pf-info-label">Availability</td>
                                <td class="pf-info-value">
                                    @if($profile->availability)
                                        <span class="pf-badge-green">{{ Str::headline($profile->availability) }}</span>
                                    @else - @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="pf-info-label">Job Type</td>
                                <td class="pf-info-value">
                                    {{ $profile->job_type ? Str::headline($profile->job_type) : '-' }}
                                </td>
                            </tr>
                        </table>

                        {{-- Buttons --}}
                        <div style="margin-top:1rem">
                            <button type="button" onclick="openProfileEditModal(1)" class="pf-btn-primary">
                                <i class="ri-edit-line"></i> Edit Profile
                            </button>
                            @if($profile->cv_path)
                                <a href="{{ route('applicant.cv.view') }}" target="_blank" class="pf-btn-secondary">
                                    <i class="ri-download-2-line"></i> Download Resume
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Portfolio & Social --}}
                @if($profile->social_links)
                    <div class="pf-card" style="margin-top:0.75rem">
                        <div class="pf-card-body">
                            <div class="pf-social-label">Portfolio & Social</div>
                            {{-- Example social links - adjust based on actual data structure --}}
                            <div>
                                @php $socials = is_array($profile->social_links) ? $profile->social_links : json_decode($profile->social_links, true); @endphp
                                @if(is_array($socials))
                                    @foreach($socials as $key => $url)
                                        @if(!empty($url))
                                            <a href="{{ $url }}" target="_blank" class="pf-social-link">
                                                @if(str_contains(strtolower($key), 'linkedin'))
                                                    <i class="ri-linkedin-fill"></i>
                                                @elseif(str_contains(strtolower($key), 'github'))
                                                    <i class="ri-github-fill"></i>
                                                @elseif(str_contains(strtolower($key), 'twitter') || str_contains(strtolower($key), 'x'))
                                                    <i class="ri-twitter-x-fill"></i>
                                                @elseif(str_contains(strtolower($key), 'dribbble'))
                                                    <i class="ri-dribbble-fill"></i>
                                                @elseif(str_contains(strtolower($key), 'website') || str_contains(strtolower($key), 'portfolio'))
                                                    <i class="ri-global-fill"></i>
                                                @else
                                                    <i class="ri-link"></i>
                                                @endif
                                                {{ is_string($key) ? ucfirst($key) : $url }}
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Tour button --}}
                <div style="margin-top:0.75rem; text-align:center">
                    <button type="button" onclick="startWalkthrough()" class="cd-hero-btn cd-tour-btn"
                        style="width:100%;justify-content:center;border-radius:10px">
                        <i class="ri-rocket-2-fill"></i> Take a Tour
                    </button>
                </div>
            </div>

            {{-- ═══════════════ MAIN CONTENT ═══════════════ --}}
            <div class="col-span-12 xl:col-span-9" x-data="{ activeTab: 'about' }">

                {{-- Navigation Tabs --}}
                <div class="mb-6 border-b border-gray-200 dark:border-white/10">
                    <nav class="flex flex-wrap -mb-px gap-6" aria-label="Tabs">
                        <button @click="activeTab = 'about'"
                            :class="activeTab === 'about' ? 'border-primary text-primary' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-700'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <i class="ri-user-heart-line me-2"></i>About
                        </button>
                        <button @click="activeTab = 'experience'"
                            :class="activeTab === 'experience' ? 'border-primary text-primary' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-700'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <i class="ri-briefcase-line me-2"></i>Experience
                        </button>
                        <button @click="activeTab = 'education'"
                            :class="activeTab === 'education' ? 'border-primary text-primary' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-700'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <i class="ri-graduation-cap-line me-2"></i>Education & Skills
                        </button>
                        <button @click="activeTab = 'achievements'"
                            :class="activeTab === 'achievements' ? 'border-primary text-primary' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-700'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                            <i class="ri-trophy-line me-2"></i>Achievements
                        </button>
                    </nav>
                </div>

                {{-- Tab Content --}}
                <div class="mt-2">
                    {{-- About Tab --}}
                    <div x-show="activeTab === 'about'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0">
                        {{-- ── About Me & Career Objective (side by side) ── --}}
                        <div class="grid grid-rows-12 gap-4" style="margin-bottom:1rem" id="wt-about">
                            <div class="row-span-12 md:row-span-6">
                                <div class="pf-card" style="height:100%">
                                    <div class="pf-card-body">
                                        <div class="pf-sh"><i class="ri-user-heart-line"></i> About Me</div>
                                        <p class="pf-text">{{ $profile->about ?? 'No information provided.' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row-span-12 md:row-span-6">
                                <div class="pf-card" style="height:100%">
                                    <div class="pf-card-body">
                                        <div class="pf-sh"><i class="ri-focus-3-line"></i> Career Objective</div>
                                        <p class="pf-text">
                                            {{ $profile->career_objective ?? 'No information provided.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Experience Tab --}}
                    <div x-show="activeTab === 'experience'" x-cloak
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0">
                        {{-- ── Experience (Zigzag Timeline) ── --}}
                        <div class="pf-card" style="margin-bottom:1rem" id="wt-experience">
                            <div class="pf-card-body">
                                <div class="pf-sh"><i class="ri-briefcase-line"></i> Experience</div>
                                @php
                                    $experience = $profile->experience_overview ? json_decode($profile->experience_overview, true) : null;
                                @endphp
                                @if($experience && !empty($experience['position']))
                                    <div class="pf-exp-timeline">
                                        <div class="pf-exp-center-line"></div>
                                        <div class="pf-exp-item left">
                                            <div class="pf-exp-dot"></div>
                                            <div class="pf-exp-left">
                                                <div class="pf-exp-content">
                                                    <div class="pf-exp-role">{{ $experience['position'] }}</div>
                                                    <div class="pf-exp-company">{{ $experience['company'] ?? '' }}</div>
                                                    @if(!empty($experience['responsibilities']))
                                                        <ul class="pf-exp-resp">
                                                            @foreach($experience['responsibilities'] as $resp)
                                                                @if(!empty($resp))
                                                                    <li>{{ $resp }}</li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="pf-exp-right"
                                                style="display:flex;align-items:flex-start;justify-content:flex-start">
                                                <div>
                                                    <span class="pf-exp-date">{{ $experience['start_date'] ?? '' }} -
                                                        {{ $experience['end_date'] ?? 'Present' }}</span>
                                                    @if(!empty($experience['location']))
                                                        <div style="font-size:0.75rem;color:var(--pf-muted);margin-top:4px">
                                                            <i class="ri-map-pin-line me-1"></i>{{ $experience['location'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="pf-empty">No experience added yet.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Education Tab --}}
                    <div x-show="activeTab === 'education'" x-cloak
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0">
                        {{-- ── Education & Certs + Skills & Tools (side by side) ── --}}
                        <div class="grid grid-rows-12 gap-4" style="margin-bottom:1rem" id="wt-edu-certs">
                            {{-- Education & Certs --}}
                            <div class="row-span-12 md:row-span-6">
                                <div class="pf-card" style="height:100%">
                                    <div class="pf-card-body">
                                        <div class="pf-sh"><i class="ri-graduation-cap-line"></i> Education & Certs
                                        </div>

                                        @php
                                            $education = $profile->education_details ? json_decode($profile->education_details, true) : [];
                                            $certifications = $profile->certifications ? json_decode($profile->certifications, true) : [];
                                        @endphp

                                        {{-- Education --}}
                                        @if(count($education) > 0)
                                            @foreach($education as $edu)
                                                <div class="pf-edu-item">
                                                    <div class="pf-edu-dot"></div>
                                                    <div>
                                                        <div class="pf-edu-title">{{ $edu['course'] ?? '' }}</div>
                                                        <div class="pf-edu-sub">
                                                            {{ $edu['school'] ?? '' }}
                                                            @if(!empty($edu['start_year']) || !empty($edu['end_year']))
                                                                • {{ $edu['start_year'] ?? '' }}@if(!empty($edu['start_year']) && !empty($edu['end_year'])) – @endif{{ $edu['end_year'] ?? '' }}
                                                            @elseif(!empty($edu['dates']))
                                                                • {{ $edu['dates'] }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="pf-empty" style="padding:0.5rem 0">No education details added.</div>
                                        @endif

                                        {{-- Certifications --}}
                                        @if(count($certifications) > 0)
                                            <div
                                                style="margin-top:0.75rem;padding-top:0.5rem;border-top:1px solid var(--pf-border)">
                                                @foreach($certifications as $cert)
                                                    <div class="pf-cert-item">
                                                        <div class="pf-cert-dot"></div>
                                                        <div>
                                                            <div class="pf-cert-title">{{ $cert['title'] ?? '' }}</div>
                                                            <div class="pf-cert-sub">{{ $cert['provider'] ?? '' }}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Skills & Tools --}}
                            <div class="row-span-12 md:row-span-6" id="wt-skills">
                                <div class="pf-card" style="height:100%">
                                    <div class="pf-card-body">
                                        <div class="pf-sh"><i class="ri-tools-line"></i> Skills & Tools</div>
                                        @php
                                            $skills = $profile->skills ? json_decode($profile->skills, true) : [];
                                            $tools = $profile->tools_used ? json_decode($profile->tools_used, true) : [];
                                            $allSkills = array_merge(
                                                array_filter($skills, fn($s) => !empty($s)),
                                                array_filter($tools, fn($t) => !empty($t))
                                            );
                                        @endphp
                                        @if(count($allSkills) > 0)
                                            <div style="display:flex;flex-wrap:wrap;gap:6px">
                                                @foreach($allSkills as $item)
                                                    <span class="pf-pill">{{ $item }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="pf-empty">No skills or tools added yet.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Achievements Tab --}}
                    <div x-show="activeTab === 'achievements'" x-cloak
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0">
                        {{-- ── Key Achievements & Languages (side by side) ── --}}
                        <div class="grid grid-rows-12 gap-4" style="margin-bottom:1rem">
                            {{-- Key Achievements --}}
                            <div class="row-span-12 md:row-span-6">
                                <div class="pf-card" style="height:100%">
                                    <div class="pf-card-body">
                                        <div class="pf-sh"><i class="ri-trophy-line"></i> Key Achievements</div>
                                        @php $achievements = $profile->key_achievements ? json_decode($profile->key_achievements, true) : []; @endphp
                                        @if(count($achievements) > 0)
                                            @foreach($achievements as $ach)
                                                @if(!empty($ach))
                                                    <div class="pf-ach-item">
                                                        <i class="ri-star-fill"></i>
                                                        <span>{{ $ach }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="pf-empty">No achievements added.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Languages --}}
                            <div class="row-span-12 md:row-span-6">
                                <div class="pf-card" style="height:100%">
                                    <div class="pf-card-body">
                                        <div class="pf-sh"><i class="ri-translate-2"></i> Languages</div>
                                        @php $languages = $profile->languages ? json_decode($profile->languages, true) : []; @endphp
                                        @if(count($languages) > 0)
                                            @foreach($languages as $idx => $lang)
                                                @if(!empty($lang))
                                                    @php
                                                        // Estimate proficiency bar width
                                                        $barWidths = [100, 75, 55, 40, 30];
                                                        $barLabels = ['Native', 'Professional', 'Conversational', 'Basic', 'Beginner'];
                                                        $bIdx = min($idx, count($barWidths) - 1);
                                                    @endphp
                                                    <div class="pf-lang-row">
                                                        <span class="pf-lang-name">{{ $lang }}</span>
                                                        <div class="pf-lang-bar-bg">
                                                            <div class="pf-lang-bar" style="width:{{ $barWidths[$bIdx] }}%"></div>
                                                        </div>
                                                        <span class="pf-lang-level">{{ $barLabels[$bIdx] }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="pf-empty">No languages added.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> {{-- Close Tab Content --}}

            </div>
        </div>
    </div> {{-- Close max-w-7xl mx-auto --}}

    {{-- ── Profile Edit Modal ── --}}
    @include('applicants.partials.profile-edit-modal', [
        'profile' => $profile,
        'dropdownOptions' => \App\Services\DropdownService::getApplicantProfileOptions(),
    ])

    @include('applicants.partials.walkthrough', [
        'wtKey' => 'profile',
        'wtSteps' => [
            ['target' => 'wt-sidebar', 'icon' => 'ri-user-3-fill', 'title' => 'Your Profile Card', 'body' => 'This is your public-facing profile summary. It shows your photo, title, location, experience, salary expectations, and resume. Click "Edit Profile" to update any details.', 'position' => 'bottom'],
            ['target' => 'wt-about', 'icon' => 'ri-file-text-fill', 'title' => 'About & Career Objective', 'body' => 'Your personal summary and career goals. A well-written bio significantly increases recruiter interest — make sure to fill this in!', 'position' => 'bottom'],
            ['target' => 'wt-experience', 'icon' => 'ri-briefcase-fill', 'title' => 'Work Experience', 'body' => 'Showcase your professional journey. Add your positions, companies, dates, and key responsibilities to help employers understand your background.', 'position' => 'bottom'],
            ['target' => 'wt-edu-certs', 'icon' => 'ri-graduation-cap-fill', 'title' => 'Education & Certifications', 'body' => 'Your educational background and professional certifications. These help verify your qualifications and stand out to employers.', 'position' => 'top'],
            ['target' => 'wt-skills', 'icon' => 'ri-tools-fill', 'title' => 'Skills & Tools', 'body' => 'List your technical skills and tools you\'re proficient with. Employers search by skills, so keep this section comprehensive and up to date.', 'position' => 'top'],
        ]
    ])

    @livewireScripts
</x-app-layout>
