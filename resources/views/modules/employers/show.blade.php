<x-app-layout>
    <x-slot name="url_1">{"link": "/employers", "text": "Employers"}</x-slot>
    <x-slot name="active">Employer Details</x-slot>

    @include('candidates.partials.candidate-styles')

    @php
        $initials = collect(explode(' ', $employer['company_name']))
            ->filter()
            ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->join('');

        $rating = (float) ($employer['rating'] ?? 0);
        $ratingCount = (int) ($employer['rating_count'] ?? 0);
        $roundedRating = round($rating, 1);
        $safeDescription = trim((string) ($employer['description'] ?? ''));
        $postedJobs = $jobs ?? collect();
    @endphp

    <style>
        .jf-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        @media (max-width: 1024px) {
            .jf-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        .jf-card {
            background: #fff;
            border: 1.5px solid var(--cd-border);
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            transition: box-shadow 0.2s, border-color 0.2s, transform 0.2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            min-width: 0;
            height: 100%;
        }

        .jf-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09);
            border-color: #c7d2fe;
            transform: translateY(-2px);
        }

        .jf-card-identity {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-width: 0;
        }

        .jf-logo {
            width: 48px;
            height: 48px;
            flex-shrink: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.95rem;
            color: #fff;
            overflow: hidden;
            border: 2px solid #e0e7ff;
            background: #4f46e5;
        }

        .jf-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .jf-card-info {
            min-width: 0;
            flex: 1;
        }

        .jf-company-name {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--cd-text);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .jf-company-name:hover {
            color: var(--cd-accent);
        }

        .jf-role {
            font-size: 0.9rem;
            color: var(--cd-text-secondary);
            margin-top: 2px;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .jf-category {
            font-size: 0.8125rem;
            color: var(--cd-text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 2px;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .jf-category i {
            flex-shrink: 0;
        }

        .jf-summary {
            font-size: 0.84rem;
            line-height: 1.55;
            color: var(--cd-text-secondary);
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .jf-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .jf-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .jf-card-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #f3f4f6;
            margin-top: auto;
        }

        .jf-pay {
            font-size: 0.84rem;
            color: var(--cd-text-secondary);
            line-height: 1.6;
        }

        .jf-pay strong {
            color: var(--cd-text);
            font-weight: 700;
        }

        .jf-view-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 1.1rem;
            border-radius: 10px;
            background: var(--cd-accent);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
            box-shadow: 0 3px 8px -2px rgba(79, 70, 229, 0.3);
        }

        .jf-view-btn:hover {
            background: var(--cd-accent-hover);
            color: #fff;
            transform: translateY(-1px);
        }

        [data-theme-mode="dark"] .jf-card,
        .dark .jf-card {
            background: var(--bodybg, #1a1c2e);
            border-color: rgba(255, 255, 255, 0.08);
        }

        [data-theme-mode="dark"] .jf-card:hover,
        .dark .jf-card:hover {
            border-color: #818cf8;
        }

        [data-theme-mode="dark"] .jf-company-name,
        .dark .jf-company-name {
            color: #f1f5f9;
        }

        [data-theme-mode="dark"] .jf-role,
        [data-theme-mode="dark"] .jf-summary,
        .dark .jf-role,
        .dark .jf-summary {
            color: #cbd5e1;
        }

        [data-theme-mode="dark"] .jf-category,
        .dark .jf-category {
            color: #9ca3af;
        }

        [data-theme-mode="dark"] .jf-card-footer,
        .dark .jf-card-footer {
            border-top-color: rgba(255, 255, 255, 0.08);
        }

        [data-theme-mode="dark"] .jf-pay,
        .dark .jf-pay {
            color: #d1d5db;
        }

        [data-theme-mode="dark"] .jf-pay strong,
        .dark .jf-pay strong {
            color: #e5e7eb;
        }
    </style>

    <main class="mx-auto w-full max-w-7xl px-4 pb-8 pt-2 sm:px-6 lg:px-8" aria-labelledby="employer-profile-title">
        <header class="mb-6 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-sm backdrop-blur-sm dark:border-white/10 dark:bg-slate-900/70 sm:p-5 lg:p-6">
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-[1fr_auto] lg:items-center">
                <div class="min-w-0">
                    <p class="mb-3 inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-indigo-700 dark:border-indigo-400/30 dark:bg-indigo-500/10 dark:text-indigo-300">
                        <i class="ri-shield-check-line" aria-hidden="true"></i>
                        Employer Profile
                    </p>

                    <div class="flex min-w-0 items-start gap-3">
                        <div class="h-14 w-14 shrink-0 overflow-hidden rounded-xl border border-indigo-300 dark:border-indigo-300/40" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
                            @if(!empty($employer['logo']))
                                <img
                                    src="{{ $employer['logo'] }}"
                                    alt="{{ $employer['company_name'] }} logo"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                    onerror="this.style.display='none'; this.parentElement.querySelector('.hero-logo-fallback')?.classList.remove('hidden');"
                                >
                                <div class="hero-logo-fallback hidden flex h-full w-full items-center justify-center text-base font-bold text-white" aria-hidden="true">
                                    {{ $initials }}
                                </div>
                            @else
                                <div class="hero-logo-fallback flex h-full w-full items-center justify-center text-base font-bold text-white" aria-hidden="true">
                                    {{ $initials }}
                                </div>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <h1 id="employer-profile-title" class="truncate text-2xl font-extrabold text-slate-900 dark:text-slate-100">
                                {{ $employer['company_name'] }}
                            </h1>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                @if(!empty($employer['verified']))
                                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:border-emerald-400/30 dark:bg-emerald-500/10 dark:text-emerald-300">
                                        <i class="ri-verified-badge-fill" aria-hidden="true"></i>
                                        Verified
                                    </span>
                                @endif
                                <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-700 dark:border-white/15 dark:bg-white/5 dark:text-slate-200">
                                    <i class="ri-building-2-line" aria-hidden="true"></i>
                                    <span class="break-words" style="overflow-wrap:anywhere;word-break:break-word;">{{ $employer['industry'] }}</span>
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-700 dark:border-white/15 dark:bg-white/5 dark:text-slate-200">
                                    <i class="ri-map-pin-line" aria-hidden="true"></i>
                                    <span class="break-words" style="overflow-wrap:anywhere;word-break:break-word;">{{ $employer['location'] }}</span>
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 dark:border-amber-400/30 dark:bg-amber-500/10 dark:text-amber-300">
                                    <i class="ri-star-fill" aria-hidden="true"></i>
                                    {{ number_format($roundedRating, 1) }} ({{ number_format($ratingCount) }})
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:border-indigo-400/30 dark:bg-indigo-500/10 dark:text-indigo-300">
                                    <i class="ri-briefcase-line" aria-hidden="true"></i>
                                    {{ $postedJobs->count() }} Open Job{{ $postedJobs->count() === 1 ? '' : 's' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600 dark:text-slate-300 sm:text-base">
                        Review this employer's profile, trust indicators, and posted jobs before deciding where to apply.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                    <a
                        href="{{ route('employers.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:border-white/15 dark:bg-transparent dark:text-slate-200 dark:hover:bg-white/5"
                        aria-label="Back to search employers"
                    >
                        <i class="ri-arrow-left-line" aria-hidden="true"></i>
                        Back to Search Employers
                    </a>

                    <a
                        href="{{ route('jobs') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2"
                        aria-label="Browse all available jobs"
                    >
                        <i class="ri-briefcase-line" aria-hidden="true"></i>
                        Browse Jobs
                    </a>
                </div>
            </div>
        </header>

        <div class="space-y-6">
            <section aria-label="Employer overview">
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-slate-900/70">
                    <div class="border-b border-slate-200 px-5 py-5 dark:border-white/10 sm:px-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl border border-indigo-300 dark:border-indigo-300/40" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
                                @if(!empty($employer['logo']))
                                    <img
                                        src="{{ $employer['logo'] }}"
                                        alt="{{ $employer['company_name'] }} logo"
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                        onerror="this.style.display='none'; this.parentElement.querySelector('.employer-logo-fallback')?.classList.remove('hidden');"
                                    >
                                    <div class="employer-logo-fallback hidden flex h-full w-full items-center justify-center text-xl font-bold text-white" aria-hidden="true">
                                        {{ $initials }}
                                    </div>
                                @else
                                    <div class="employer-logo-fallback flex h-full w-full items-center justify-center text-xl font-bold text-white" aria-hidden="true">
                                        {{ $initials }}
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 sm:text-2xl">
                                        {{ $employer['company_name'] }}
                                    </h2>
                                    @if(!empty($employer['verified']))
                                        <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:border-emerald-400/30 dark:bg-emerald-500/10 dark:text-emerald-300" aria-label="Verified employer">
                                            <i class="ri-verified-badge-fill" aria-hidden="true"></i>
                                            Verified
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-600 dark:text-slate-300">
                                    <p class="inline-flex items-center gap-1.5">
                                        <i class="ri-building-2-line text-indigo-600 dark:text-indigo-300" aria-hidden="true"></i>
                                        <span class="break-words" style="overflow-wrap:anywhere;word-break:break-word;">{{ $employer['industry'] }}</span>
                                    </p>
                                    <p class="inline-flex items-center gap-1.5">
                                        <i class="ri-map-pin-line text-indigo-600 dark:text-indigo-300" aria-hidden="true"></i>
                                        <span class="break-words" style="overflow-wrap:anywhere;word-break:break-word;">{{ $employer['location'] }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 px-5 py-5 sm:px-6">
                        <section aria-labelledby="about-company-title">
                            <h3 id="about-company-title" class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                About This Company
                            </h3>
                            <p class="mt-3 whitespace-pre-line break-words text-[15px] leading-7 text-slate-700 dark:text-slate-200" style="overflow-wrap:anywhere;word-break:break-word;">
                                {{ $safeDescription !== '' ? $safeDescription : 'No company overview is available yet.' }}
                            </p>
                        </section>

                        <section aria-labelledby="company-website-title" class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-white/5">
                            <h3 id="company-website-title" class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                Company Website
                            </h3>
                            @if(!empty($employer['website']))
                                <a
                                    href="{{ $employer['website'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-2 inline-flex items-center gap-2 text-sm font-semibold text-indigo-700 underline decoration-indigo-300 underline-offset-4 transition hover:text-indigo-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:text-indigo-300"
                                    aria-label="Visit {{ $employer['company_name'] }} website in a new tab"
                                >
                                    <i class="ri-global-line" aria-hidden="true"></i>
                                    Visit official website
                                    <i class="ri-external-link-line" aria-hidden="true"></i>
                                </a>
                            @else
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                                    This employer has not added a public website yet.
                                </p>
                            @endif
                        </section>
                    </div>
                </article>

            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-slate-900/70" aria-label="Employer key facts">
                <h2 class="text-base font-bold text-slate-900 dark:text-slate-100">Key Facts</h2>

                <dl class="mt-4 divide-y divide-slate-200 text-sm dark:divide-white/10">
                    <div class="flex items-start justify-between gap-4 py-3">
                        <dt class="text-slate-600 dark:text-slate-300">Company Name</dt>
                        <dd class="text-right font-semibold text-slate-900 dark:text-slate-100">{{ $employer['company_name'] }}</dd>
                    </div>

                    <div class="flex items-start justify-between gap-4 py-3">
                        <dt class="text-slate-600 dark:text-slate-300">Industry</dt>
                        <dd class="text-right font-semibold text-slate-900 dark:text-slate-100">{{ $employer['industry'] }}</dd>
                    </div>

                    <div class="flex items-start justify-between gap-4 py-3">
                        <dt class="text-slate-600 dark:text-slate-300">Location</dt>
                        <dd class="text-right font-semibold text-slate-900 dark:text-slate-100">{{ $employer['location'] }}</dd>
                    </div>

                    <div class="flex items-start justify-between gap-4 py-3">
                        <dt class="text-slate-600 dark:text-slate-300">Average Rating</dt>
                        <dd class="text-right font-semibold text-slate-900 dark:text-slate-100">{{ number_format($roundedRating, 1) }} / 5</dd>
                    </div>

                    <div class="flex items-start justify-between gap-4 py-3">
                        <dt class="text-slate-600 dark:text-slate-300">Community Reviews</dt>
                        <dd class="text-right font-semibold text-slate-900 dark:text-slate-100">{{ number_format($ratingCount) }}</dd>
                    </div>
                </dl>

                <div class="mt-4 rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 dark:border-indigo-400/30 dark:bg-indigo-500/10">
                    <p class="text-sm font-semibold text-indigo-800 dark:text-indigo-200">
                        Profile trust level: {{ !empty($employer['verified']) ? 'Verified Employer' : 'Standard Employer' }}
                    </p>
                </div>
            </section>
        </div>

        <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-slate-900/70" aria-labelledby="posted-jobs-title">
            <div class="mb-4 flex items-center justify-between gap-3">
                <h2 id="posted-jobs-title" class="text-base font-bold text-slate-900 dark:text-slate-100">
                    Posted Jobs
                </h2>
                <span class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 dark:border-indigo-400/30 dark:bg-indigo-500/10 dark:text-indigo-300">
                    {{ $postedJobs->count() }} Open
                </span>
            </div>

            @if($postedJobs->isNotEmpty())
                <div class="jf-cards-grid">
                    @foreach($postedJobs as $job)
                        <article class="jf-card">
                            <div class="jf-card-identity">
                                <div class="jf-logo">
                                    @if(!empty($employer['logo']))
                                        <img src="{{ $employer['logo'] }}" alt="{{ $employer['company_name'] }} logo" loading="lazy" onerror="this.style.display='none'; this.parentElement.querySelector('.job-logo-fallback')?.classList.remove('hidden');">
                                        <span class="job-logo-fallback hidden">{{ $initials }}</span>
                                    @else
                                        <span class="job-logo-fallback">{{ $initials }}</span>
                                    @endif
                                </div>

                                <div class="jf-card-info">
                                    <a href="{{ $job['url'] }}" class="jf-company-name">
                                        {{ $employer['company_name'] }}
                                        @if(!empty($employer['verified']))
                                            <span style="color:#4f46e5;font-size:0.85rem" title="Verified"><i class="ri-verified-badge-fill"></i></span>
                                        @endif
                                    </a>
                                    <div class="jf-role">{{ $job['title'] }}</div>
                                    @if(!empty($job['location']))
                                        <div class="jf-category"><i class="ri-map-pin-line"></i>{{ $job['location'] }}</div>
                                    @endif
                                </div>
                            </div>

                            @if(!empty($job['summary']))
                                <p class="jf-summary">{{ \Illuminate\Support\Str::limit($job['summary'], 130) }}</p>
                            @endif

                            <div class="jf-tags">
                                @if(!empty($job['employment_type']))
                                    <span class="jf-tag" style="background:#eef2ff;color:#4f46e5">
                                        <i class="ri-briefcase-line"></i>
                                        {{ str_replace('_', ' ', ucfirst($job['employment_type'])) }}
                                    </span>
                                @endif

                                @if(!empty($job['posted_at']))
                                    <span class="jf-tag" style="background:#ecfdf5;color:#059669">
                                        <i class="ri-time-line"></i>
                                        {{ \Carbon\Carbon::parse($job['posted_at'])->diffForHumans() }}
                                    </span>
                                @endif
                            </div>

                            <div class="jf-card-footer">
                                <div class="jf-pay">
                                    Salary:
                                    <strong>
                                        @if(!empty($job['salary_min']) || !empty($job['salary_max']))
                                            {{ $job['salary_currency'] ?? 'PHP' }}
                                            @if(!empty($job['salary_min'])){{ number_format((float) $job['salary_min']) }}@endif
                                            @if(!empty($job['salary_min']) && !empty($job['salary_max'])) - @endif
                                            @if(!empty($job['salary_max'])){{ number_format((float) $job['salary_max']) }}@endif
                                        @else
                                            Not specified
                                        @endif
                                    </strong>
                                </div>

                                <a href="{{ $job['url'] }}" class="jf-view-btn">
                                    View Details <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-5 text-sm text-slate-600 dark:border-white/15 dark:bg-white/5 dark:text-slate-300">
                    No open jobs are posted by this employer yet.
                </div>
            @endif
        </section>
    </main>
</x-app-layout>
