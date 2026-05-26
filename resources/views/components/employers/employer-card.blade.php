@props(['employer'])

@php
    $companyName = $employer['company_name'] ?? 'Company';
    $initials = collect(explode(' ', $companyName))
        ->filter()
        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
        ->take(2)
        ->join('');
@endphp

<article class="ef-card">
    {{-- Card Header with Logo --}}
    <div class="ef-card-head">
        <div class="ef-logo-wrap">
            @if(!empty($employer['logo']))
                <img src="{{ $employer['logo'] }}" alt="{{ $companyName }} logo" class="ef-logo-img">
            @else
                <span class="ef-logo-fallback">{{ $initials }}</span>
            @endif
        </div>
        @if(!empty($employer['verified']))
            <span class="ef-verified-badge" title="Verified company">
                <i class="ri-verified-badge-fill"></i>
            </span>
        @endif
    </div>

    {{-- Company Info --}}
    <div class="ef-content">
        <a href="{{ $employer['detail_url'] ?? '#' }}" class="ef-company-name">
            {{ $companyName }}
        </a>

        @if(!empty($employer['featured_role']))
            <p class="ef-meta-item">
                <i class="ri-briefcase-line"></i> {{ $employer['featured_role'] }}
            </p>
        @endif

        @if(!empty($employer['industry']))
            <p class="ef-meta-item">
                <i class="ri-building-2-line"></i> {{ $employer['industry'] }}
            </p>
        @endif

        @if(!empty($employer['location']))
            <p class="ef-meta-item ef-location">
                <i class="ri-map-pin-line"></i> {{ $employer['location'] }}
            </p>
        @endif
    </div>

    {{-- Description --}}
    @if(!empty($employer['description']))
        <p class="ef-description">
            {{ \Illuminate\Support\Str::limit($employer['description'] ?? '', 140) }}
        </p>
    @endif

    {{-- Jobs Badge --}}
    <div class="ef-jobs-badge">
        <i class="ri-briefcase-line"></i>
        <span>{{ $employer['open_jobs_count'] ?? 0 }} Open Job{{ $employer['open_jobs_count'] != 1 ? 's' : '' }}</span>
    </div>

    {{-- Card Footer --}}
    <div class="ef-card-footer">
        <div class="ef-footer-info">
            @if(!empty($employer['salary_display']))
                <span class="ef-salary">
                    <i class="ri-money-dollar-circle-line"></i>
                    {{ $employer['salary_display'] }}
                </span>
            @elseif(!empty($employer['website']))
                <a href="{{ $employer['website'] }}" target="_blank" rel="noopener noreferrer" class="ef-website-link">
                    <i class="ri-global-line"></i> Website
                </a>
            @endif
        </div>

        <a href="{{ $employer['detail_url'] ?? '#' }}" class="ef-view-btn">
            <span>View Details</span>
            <i class="ri-arrow-right-line"></i>
        </a>
    </div>
</article>

<style>
    .ef-card {
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .ef-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 10px 28px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .ef-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1rem;
        gap: 0.75rem;
    }

    .ef-logo-wrap {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f0f4ff 0%, #f5f3ff 100%);
        border: 1px solid #e0e7ff;
    }

    .ef-logo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .ef-logo-fallback {
        font-size: 1.125rem;
        font-weight: 700;
        color: #4f46e5;
    }

    .ef-verified-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: #f0fdf4;
        border-radius: 50%;
        border: 1px solid #bbf7d0;
        color: #16a34a;
        font-size: 0.875rem;
        flex-shrink: 0;
    }

    .ef-content {
        flex-grow: 1;
        margin-bottom: 0.75rem;
    }

    .ef-company-name {
        display: block;
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        text-decoration: none;
        margin-bottom: 0.5rem;
        transition: color 0.2s ease;
        word-break: break-word;
    }

    .ef-company-name:hover {
        color: #4f46e5;
    }

    .ef-meta-item {
        font-size: 0.8125rem;
        color: #6b7280;
        margin: 0.35rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ef-meta-item i {
        color: #4f46e5;
        font-size: 0.875rem;
        flex-shrink: 0;
    }

    .ef-location {
        color: #9ca3af;
        font-weight: 500;
    }

    .ef-description {
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 1.25rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .ef-jobs-badge {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        gap: 0.5rem;
        background: #eef2ff;
        border: 1px solid #c7d2fe;
        padding: 0.5rem 0.85rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #4f46e5;
        margin-bottom: auto;
    }

    .ef-jobs-badge i {
        font-size: 0.925rem;
    }

    .ef-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding-top: 1rem;
        border-top: 1px solid #f3f4f6;
        margin-top: auto;
    }

    .ef-footer-info {
        font-size: 0.75rem;
        flex: 1;
    }

    .ef-salary,
    .ef-website-link {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: #6b7280;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .ef-salary i,
    .ef-website-link i {
        font-size: 0.875rem;
    }

    .ef-website-link:hover {
        color: #4f46e5;
    }

    .ef-view-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.9rem;
        background: #4f46e5;
        color: #fff;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .ef-view-btn:hover {
        background: #4335cc;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        transform: translateX(2px);
    }

    .ef-view-btn i {
        font-size: 0.875rem;
        transition: transform 0.2s ease;
    }

    .ef-view-btn:hover i {
        transform: translateX(2px);
    }

    /* Dark mode support (Standard UI Selector) */
    :is([data-theme-mode="dark"], .dark) .ef-card {
        background: rgba(30, 41, 59, 0.72);
        border-color: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(8px);
    }

    :is([data-theme-mode="dark"], .dark) .ef-card:hover {
        border-color: rgba(99, 102, 241, 0.4);
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.3);
    }

    :is([data-theme-mode="dark"], .dark) .ef-company-name {
        color: #f8fafc;
    }

    :is([data-theme-mode="dark"], .dark) .ef-company-name:hover {
        color: #818cf8;
    }

    :is([data-theme-mode="dark"], .dark) .ef-meta-item {
        color: #94a3b8;
    }

    :is([data-theme-mode="dark"], .dark) .ef-meta-item i {
        color: #818cf8;
    }

    :is([data-theme-mode="dark"], .dark) .ef-description {
        color: #cbd5e1;
    }

    :is([data-theme-mode="dark"], .dark) .ef-logo-wrap {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
    }

    :is([data-theme-mode="dark"], .dark) .ef-logo-fallback {
        color: #818cf8;
    }

    :is([data-theme-mode="dark"], .dark) .ef-card-footer {
        border-top-color: rgba(255, 255, 255, 0.05);
    }

    :is([data-theme-mode="dark"], .dark) .ef-footer-info {
        color: #94a3b8;
    }

    :is([data-theme-mode="dark"], .dark) .ef-salary,
    :is([data-theme-mode="dark"], .dark) .ef-website-link {
        color: #94a3b8;
    }

    :is([data-theme-mode="dark"], .dark) .ef-view-btn {
        background: #6366f1;
    }

    :is([data-theme-mode="dark"], .dark) .ef-view-btn:hover {
        background: #4f46e5;
    }

    /* Dark Mode Styling */
    :is([data-theme-mode="dark"], .dark) .ef-card {
        background: rgb(30, 32, 35) !important;
        border-color: rgba(255, 255, 255, 0.06) !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-card:hover {
        border-color: #818cf8 !important;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.3) !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-logo-wrap {
        background: rgba(79, 70, 229, 0.1) !important;
        border-color: rgba(99, 102, 241, 0.2) !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-verified-badge {
        background: rgba(16, 185, 129, 0.1) !important;
        border-color: rgba(16, 185, 129, 0.2) !important;
        color: #10b981 !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-company-name {
        color: #f8fafc !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-company-name:hover {
        color: #818cf8 !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-meta-item {
        color: #cbd5e1 !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-meta-item i {
        color: #818cf8 !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-location {
        color: #94a3b8 !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-description {
        color: #94a3b8 !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-jobs-badge {
        background: rgba(99, 102, 241, 0.1) !important;
        border-color: rgba(99, 102, 241, 0.2) !important;
        color: #a5b4fc !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-jobs-badge i {
        color: #a5b4fc !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-card-footer {
        border-top-color: rgba(255, 255, 255, 0.06) !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-salary,
    :is([data-theme-mode="dark"], .dark) .ef-website-link {
        color: #cbd5e1 !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-salary:hover,
    :is([data-theme-mode="dark"], .dark) .ef-website-link:hover {
        color: #818cf8 !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-view-btn {
        background: #6366f1 !important;
        color: #ffffff !important;
        border-color: #4f46e5 !important;
    }

    :is([data-theme-mode="dark"], .dark) .ef-view-btn:hover {
        background: #4f46e5 !important;
        border-color: #4338ca !important;
    }

    /* Mobile responsiveness */
    @media (max-width: 640px) {
        .ef-card {
            padding: 1.25rem;
        }

        .ef-card-footer {
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
        }

        .ef-view-btn {
            justify-content: center;
            width: 100%;
        }
    }
</style>
