<x-app-layout>
    <x-slot name="url_1">{"link": "/employers", "text": "Employers"}</x-slot>
    <x-slot name="title">Search Employers</x-slot>
    <x-slot name="active">Search Employers</x-slot>

    @include('candidates.partials.candidate-styles')

    <style>
        /* ── Modern Elite Design Tokens ── */
        :root {
            --jf-primary: #6366f1;
            --jf-primary-hover: #4f46e5;
            --jf-bg-card: #ffffff;
            --jf-border: #e2e8f0;
            --jf-text-main: #1e293b;
            --jf-text-sub: #64748b;
        }

        :is([data-theme-mode="dark"], .dark) {
            --jf-bg-card: #1e293b;
            --jf-border: rgba(255, 255, 255, 0.08);
            --jf-text-main: #f8fafc;
            --jf-text-sub: #94a3b8;
        }

        /* ── Modern Minimalist Header ── */
        .jf-header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 2rem;
            padding: 0.5rem 0 1.5rem 0;
            border-bottom: 2px solid var(--jf-border);
            position: relative;
            padding-left: 1.4rem;
        }

        .jf-header-content { flex: 1; padding-left: 0.75rem; }

        .jf-context-row {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 0.75rem;
        }

        .jf-v-bar {
            width: 4px;
            height: 20px;
            background: var(--jf-primary);
            border-radius: 4px;
        }

        .jf-context-label {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--jf-primary);
            background: rgba(99, 102, 241, 0.1);
            padding: 2px 10px;
            border-radius: 20px;
        }

        .jf-header-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--jf-text-main);
            letter-spacing: -0.02em;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .jf-header-desc {
            font-size: 1rem;
            color: var(--jf-text-sub);
            max-width: 700px;
            line-height: 1.5;
        }

        .jf-header-desc b { color: var(--jf-primary); font-weight: 700; }

        .jf-header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
        }

        /* UI/UX Search Pill Modernization */
        .jf-search-pill-container { width: 100%; margin-bottom: 2rem; position: relative; z-index: 100; }
        .jf-search-pill { display: flex; align-items: stretch; background: #fff; border-radius: 300px; padding: 0.4rem; height: 60px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1.5px solid var(--jf-border); transition: all 0.3s; }
        .jf-search-pill:focus-within, .jf-search-pill:hover { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08); border-color: #6366f1; transform: translateY(-1px); }
        :is([data-theme-mode="dark"], .dark) .jf-search-pill { background: rgba(30, 41, 59, 0.72) !important; border-color: rgba(255,255,255,0.08) !important; backdrop-filter: blur(8px) !important; }

        .jf-pill-field { flex: 1; display: flex; align-items: center; gap: 0.85rem; padding: 0 1.25rem; min-width: 0; }
        .jf-pill-field i { font-size: 1.15rem; color: #94a3b8; }
        .jf-pill-input { width: 100%; border: none !important; background: transparent !important; font-size: 0.9rem; font-weight: 600; color: var(--jf-text-main); outline: none !important; box-shadow: none !important; }
        :is([data-theme-mode="dark"], .dark) .jf-pill-input { color: #f8fafc !important; }

        .jf-pill-submit { height: 48px; padding: 0 1.75rem; border-radius: 24px; background: #6366f1; color: #fff; font-size: 0.875rem; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; white-space: nowrap; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3); }
        .jf-pill-submit:hover { background: #4f46e5; transform: scale(1.02); box-shadow: 0 6px 14px rgba(99, 102, 241, 0.4); }

        /* Premium Custom Dropdowns ("Pill Selects") */
        .ef-filter-row { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; margin-bottom: 2rem; width: 100%; position: relative; z-index: 50; }

        .jf-modern-filter { position: relative; width: 100%; border-radius: 14px; background: #fff; border: 1.5px solid var(--jf-border); transition: all 0.2s; cursor: pointer; user-select: none; }
        .jf-modern-filter:hover { border-color: #6366f1; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08); }
        :is([data-theme-mode="dark"], .dark) .jf-modern-filter { background: rgba(30, 41, 59, 0.45); border-color: rgba(255, 255, 255, 0.08); backdrop-filter: blur(8px); }

        .jf-filter-trigger { display: flex; align-items: center; justify-content: space-between; height: 48px; padding: 0 1rem; gap: 0.5rem; }
        .jf-filter-label-wrap { display: flex; align-items: center; gap: 0.6rem; min-width: 0; }
        .jf-filter-label-wrap i { color: #6366f1; font-size: 1rem; flex-shrink: 0; }
        .jf-filter-current-text { font-size: 0.8rem; font-weight: 800; color: var(--jf-text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding-bottom: 1px; }
        :is([data-theme-mode="dark"], .dark) .jf-filter-current-text { color: #cbd5e1; }
        .jf-filter-arrow { font-size: 1rem; color: #94a3b8; transition: transform 0.2s; }
        .jf-modern-filter.active .jf-filter-arrow { transform: rotate(180deg); color: #ffffff !important; }

        /* Premium Active Trigger Style */
        .jf-modern-filter.active {
            background: #6366f1 !important;
            border-color: #6366f1 !important;
            box-shadow: 0 8px 16px -4px rgba(99,102,241,0.3) !important;
        }
        .jf-modern-filter.active .jf-filter-current-text,
        .jf-modern-filter.active .jf-filter-label-wrap i {
            color: #ffffff !important;
        }

        .jf-filter-dropdown { position: absolute; top: calc(100% + 8px); left: 0; right: 0; background: #fff; border: 1px solid var(--jf-border); border-radius: 14px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); z-index: 1000; overflow: hidden; display: none; opacity: 0; transform: translateY(10px); transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); min-width: 200px; }
        .jf-modern-filter.active .jf-filter-dropdown { display: block; opacity: 1; transform: translateY(0); }
        :is([data-theme-mode="dark"], .dark) .jf-filter-dropdown { background: #1e293b; border-color: rgba(255, 255, 255, 0.1); box-shadow: 0 20px 40px rgba(0,0,0,0.4); }

        .jf-filter-options-scroll { max-height: 280px; overflow-y: auto; padding: 0.4rem; scrollbar-width: thin; scrollbar-color: #6366f1 transparent; }
        .jf-filter-option { padding: 0.65rem 0.85rem; border-radius: 9px; font-size: 0.82rem; font-weight: 700; color: var(--jf-text-main); transition: all 0.2s; display: flex; align-items: center; justify-content: space-between; margin-bottom: 2px; }
        :is([data-theme-mode="dark"], .dark) .jf-filter-option { color: #d1d5db; }
        .jf-filter-option:hover { background: rgba(99, 102, 241, 0.08); color: #6366f1; padding-left: 1.15rem; }
        .jf-filter-option.selected { background: #6366f1; color: #fff; }

        .ef-reset-btn-modern {
            padding: 0 1rem; height: 48px; font-size: 0.813rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.05em; color: #64748b; border: none; background: transparent; cursor: pointer; transition: all 0.2s;
        }
        .ef-reset-btn-modern:hover { color: #ef4444; }

        .ef-result-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding: 0 0.5rem; }
        .ef-result-count { color: var(--jf-text-main); font-size: 1rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; }
        .ef-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.5rem; margin-bottom: 3rem; }

        @media (max-width: 992px) {
            .jf-header-section.cd-section {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 1.5rem !important;
                padding: 3rem 1rem 1.5rem !important;
                height: auto !important;
            }
            .jf-header-content {
                padding-left: 0 !important;
                margin-top: 0 !important;
            }
            .jf-header-title {
                font-size: 1.875rem !important;
            }
            .jf-header-actions {
                width: 100% !important;
                flex-direction: row !important;
                align-items: stretch !important;
                gap: 0.5rem !important;
            }
            .jf-header-actions a {
                flex: 1 !important;
                justify-content: center !important;
                padding: 0.625rem !important;
            }

            .jf-search-pill {
                height: auto !important;
                flex-direction: column !important;
                border-radius: 1.5rem !important;
                padding: 1rem !important;
                gap: 0.75rem !important;
                align-items: stretch !important;
            }
            .jf-pill-field {
                padding: 0.875rem 1rem !important;
                background: rgba(99, 102, 241, 0.03);
                border-radius: 1rem !important;
            }
            .jf-pill-submit {
                width: 100% !important;
                height: 52px !important;
                border-radius: 1rem !important;
            }
            .ef-reset-btn-modern {
                width: 100% !important;
                height: 48px !important;
                justify-content: center !important;
            }

            .ef-filter-row {
                grid-template-columns: 1fr !important;
                gap: 0.75rem !important;
            }
            .jf-modern-filter {
                z-index: 10 !important;
            }
            .jf-modern-filter.active {
                z-index: 1002 !important; /* Force above following filters */
            }
        }

        @media (max-width: 640px) {
            .ef-grid { grid-template-columns: 1fr; }
        }

        .ef-load-more-wrap {
            text-align: center;
            padding: 2.5rem 0;
        }

        .ef-empty {
            display: none;
        }

        .ef-empty.show {
            display: block;
        }

        :is([data-theme-mode="dark"], .dark) .ef-result-count { color: #f1f5f9; }
        :is([data-theme-mode="dark"], .dark) .jf-header-section.cd-section { border-color: rgba(255, 255, 255, 0.08) !important; background: rgb(30, 32, 35) !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-title { color: #f8fafc !important; }
        :is([data-theme-mode="dark"], .dark) .jf-header-desc { color: #94a3b8 !important; }
        :is([data-theme-mode="dark"], .dark) .jf-context-label { color: #ffffff !important; }

        /* Unified Header refined combo */
        .jf-header-section.cd-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 1rem 1.5rem 1.5rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            position: relative;
        }

        .jf-header-section.cd-section .jf-header-content {
            margin-top: 1.5rem;
            padding-top: 0.5rem;
        }

        .jf-header-section.cd-section .jf-header-title {
            margin-bottom: 0.5rem;
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--cd-text);
        }

        :is([data-theme-mode="dark"], .dark) .jf-header-section.cd-section { border-color: rgba(255, 255, 255, 0.08) !important; background: rgb(30, 32, 35) !important; }

        .jf-header-section.cd-section .jf-header-desc b {
            color: #6366f1;
        }

        :is([data-theme-mode="dark"], .dark) .jf-modern-filter {
            background: rgba(30, 41, 59, 0.45) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-modern-filter:hover {
            background: rgba(30, 41, 59, 0.6) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-filter-trigger {
            color: #f8fafc !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-filter-arrow {
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-filter-dropdown {
            background: #1e293b !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4) !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-filter-option {
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-filter-option:hover {
            background: rgba(99, 102, 241, 0.1) !important;
            color: #818cf8 !important;
        }

        :is([data-theme-mode="dark"], .dark) .jf-filter-option.selected {
            background: #6366f1 !important;
            color: #ffffff !important;
        }

        :is([data-theme-mode="dark"], .dark) .ef-empty {
            background: rgba(30, 41, 59, 0.45) !important;
            color: #94a3b8 !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        :is([data-theme-mode="dark"], .dark) .ef-empty i {
            color: #818cf8 !important;
        }
    </style>

    <div class="max-w-7xl mx-auto pt-6 pb-4 px-4 sm:px-6 lg:px-8">
        {{-- Modern Minimalist Header (Interactive Board Style) --}}
        <x-modern-header :container="false" chip="Employer Network">
            <x-slot name="titleContent"><strong>Search Employers</strong></x-slot>
            <x-slot name="description">
                Discover verified employers and explore company profiles within the <b>Hiring Hall</b> ecosystem.
            </x-slot>
            <x-slot name="actions">
                <a href="{{ route('jobs') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-white font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm hover:shadow-md border border-slate-200 dark:border-slate-700 text-sm">
                    <i class="ri-search-line me-2 text-indigo-500"></i> Search Jobs
                </a>
            </x-slot>
        </x-modern-header>
    </div>

    {{-- Modernized Search Pill Section --}}
    <div class="max-w-5xl mx-auto px-4">
        <div class="jf-search-pill-container">
            <div class="jf-search-pill">
                <div class="jf-pill-field">
                    <i class="ri-search-line"></i>
                    <input id="ef-keyword" class="jf-pill-input" type="text" placeholder="Search by name, industry, or keyword..." value="{{ $keyword }}">
                </div>
                <button id="ef-search-btn" class="jf-pill-submit">Search</button>
                <button id="ef-reset-btn" class="ef-reset-btn-modern" title="Clear search filters">Reset</button>
            </div>
        </div>

        <div class="ef-filter-row">
            <!-- Industry Filter -->
            <div class="jf-modern-filter" id="jf-industry-select" data-id="ef-industry">
                <div class="jf-filter-trigger">
                    <div class="jf-filter-label-wrap">
                        <i class="ri-building-2-line"></i>
                        <span class="jf-filter-current-text" id="jf-industry-text">All Industries</span>
                    </div>
                    <i class="ri-arrow-down-s-line jf-filter-arrow"></i>
                </div>
                <div class="jf-filter-dropdown">
                    <div class="jf-filter-options-scroll">
                        <div class="jf-filter-option selected" data-value="">All Industries</div>
                        @foreach(($industryOptions ?? collect()) as $industry)
                            <div class="jf-filter-option" data-value="{{ $industry }}">{{ $industry }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Location Filter -->
            <div class="jf-modern-filter" id="jf-location-select" data-id="ef-location">
                <div class="jf-filter-trigger">
                    <div class="jf-filter-label-wrap">
                        <i class="ri-map-pin-line"></i>
                        <span class="jf-filter-current-text" id="jf-location-text">All Locations</span>
                    </div>
                    <i class="ri-arrow-down-s-line jf-filter-arrow"></i>
                </div>
                <div class="jf-filter-dropdown">
                    <div class="jf-filter-options-scroll">
                        <div class="jf-filter-option selected" data-value="">All Locations</div>
                        @foreach(($locationOptions ?? collect()) as $location)
                            <div class="jf-filter-option" data-value="{{ $location }}">{{ $location }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Verification Filter -->
            <div class="jf-modern-filter" id="jf-verified-select" data-id="ef-verified">
                <div class="jf-filter-trigger">
                    <div class="jf-filter-label-wrap">
                        <i class="ri-verified-badge-line"></i>
                        <span class="jf-filter-current-text" id="jf-verified-text">All Verification</span>
                    </div>
                    <i class="ri-arrow-down-s-line jf-filter-arrow"></i>
                </div>
                <div class="jf-filter-dropdown">
                    <div class="jf-filter-options-scroll">
                        <div class="jf-filter-option selected" data-value="all">All Verification</div>
                        <div class="jf-filter-option" data-value="yes">Verified only</div>
                        <div class="jf-filter-option" data-value="no">Unverified only</div>
                    </div>
                </div>
            </div>

            <!-- Jobs Filter -->
            <div class="jf-modern-filter" id="jf-has-jobs-select" data-id="ef-has-jobs">
                <div class="jf-filter-trigger">
                    <div class="jf-filter-label-wrap">
                        <i class="ri-briefcase-line"></i>
                        <span class="jf-filter-current-text" id="jf-has-jobs-text">All job availability</span>
                    </div>
                    <i class="ri-arrow-down-s-line jf-filter-arrow"></i>
                </div>
                <div class="jf-filter-dropdown">
                    <div class="jf-filter-options-scroll">
                        <div class="jf-filter-option selected" data-value="all">All job availability</div>
                        <div class="jf-filter-option" data-value="yes">With open jobs</div>
                        <div class="jf-filter-option" data-value="no">Without open jobs</div>
                    </div>
                </div>
            </div>

            {{-- Hidden inputs to sync with JS --}}
            <input type="hidden" id="ef-industry" value="">
            <input type="hidden" id="ef-location" value="">
            <input type="hidden" id="ef-verified" value="all">
            <input type="hidden" id="ef-has-jobs" value="all">
        </div>
    </div>

    <div class="ef-result-bar mx-auto pt-6 pb-4 px-4 sm:px-6 lg:px-8">
        <div class="ef-result-count"><i class="ri-building-line me-1" style="color:var(--cd-accent)"></i><span id="ef-total">0</span> Employer(s)</div>
    </div>

    <div id="ef-grid" class="ef-grid mx-auto pt-6 pb-4 px-4 sm:px-6 lg:px-8"></div>

    <div id="ef-empty" class="cd-section cd-empty ef-empty mx-auto pt-6 pb-4 px-4 sm:px-6 lg:px-8">
        <i class="ri-building-line"></i>
        <p>No employers found for your search.</p>
    </div>

    <div id="ef-load-more-wrap" class="ef-load-more-wrap mx-auto pt-6 pb-4 px-4 sm:px-6 lg:px-8" style="display:none">
        <button id="ef-load-more" type="button" class="ef-search-btn">Load More Employers</button>
    </div>

    <script>
        (function () {
            let currentPage = 1;
            let hasMore = false;
            let loading = false;

            const apiUrl = @json(route('employers.api'));
            const grid = document.getElementById('ef-grid');
            const keywordInput = document.getElementById('ef-keyword');
            const totalEl = document.getElementById('ef-total');
            const emptyEl = document.getElementById('ef-empty');
            const loadMoreWrap = document.getElementById('ef-load-more-wrap');
            const loadMoreBtn = document.getElementById('ef-load-more');
            const searchBtn = document.getElementById('ef-search-btn');
            const resetBtn = document.getElementById('ef-reset-btn');
            const industrySelect = document.getElementById('ef-industry');
            const locationSelect = document.getElementById('ef-location');
            const verifiedSelect = document.getElementById('ef-verified');
            const hasJobsSelect = document.getElementById('ef-has-jobs');

            // --- Custom Dropdown Logic ---
            document.querySelectorAll('.jf-modern-filter').forEach(container => {
                const trigger = container.querySelector('.jf-filter-trigger');
                const options = container.querySelectorAll('.jf-filter-option');
                const displayText = container.querySelector('.jf-filter-current-text');
                const targetInputId = container.getAttribute('data-id');
                const targetInput = document.getElementById(targetInputId);

                trigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isActive = container.classList.contains('active');
                    // Close all others
                    document.querySelectorAll('.jf-modern-filter.active').forEach(other => {
                        other.classList.remove('active');
                    });
                    if (!isActive) container.classList.add('active');
                });

                options.forEach(option => {
                    option.addEventListener('click', (e) => {
                        e.stopPropagation();
                        const val = option.getAttribute('data-value');

                        // Update Active UI
                        options.forEach(o => o.classList.remove('selected'));
                        option.classList.add('selected');
                        displayText.textContent = option.textContent.trim();
                        container.classList.remove('active');

                        // Sync with Hidden Input
                        if (targetInput) {
                            targetInput.value = val;
                        }

                        runSearch();
                    });
                });
            });

            // Close on outside click
            document.addEventListener('click', () => {
                document.querySelectorAll('.jf-modern-filter.active').forEach(c => c.classList.remove('active'));
            });

            function buildQuery(page) {
                const params = new URLSearchParams();
                const keyword = keywordInput.value.trim();
                if (keyword) {
                    params.set('keyword', keyword);
                }

                if (industrySelect && industrySelect.value) {
                    params.set('industry', industrySelect.value);
                }

                if (locationSelect && locationSelect.value) {
                    params.set('location', locationSelect.value);
                }

                if (verifiedSelect && verifiedSelect.value && verifiedSelect.value !== 'all') {
                    params.set('verified', verifiedSelect.value);
                }

                if (hasJobsSelect && hasJobsSelect.value && hasJobsSelect.value !== 'all') {
                    params.set('has_open_jobs', hasJobsSelect.value);
                }

                params.set('page', String(page));
                return params.toString();
            }

            async function fetchEmployers(page, append) {
                if (loading) return;
                loading = true;
                if (!append) {
                    grid.innerHTML = '';
                    emptyEl.classList.remove('show');
                }

                const response = await fetch(`${apiUrl}?${buildQuery(page)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    loading = false;
                    return;
                }

                const payload = await response.json();
                currentPage = payload.meta.page;
                hasMore = payload.meta.has_more;
                totalEl.textContent = payload.meta.total;

                if (payload.cards.length === 0 && currentPage === 1) {
                    emptyEl.classList.add('show');
                } else {
                    grid.insertAdjacentHTML('beforeend', payload.cards.join(''));
                }

                loadMoreWrap.style.display = hasMore ? 'block' : 'none';
                loading = false;
            }

            function runSearch() {
                fetchEmployers(1, false);
            }

            searchBtn.addEventListener('click', runSearch);
            keywordInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    runSearch();
                }
            });

            resetBtn.addEventListener('click', function () {
                keywordInput.value = '';
                if (industrySelect) industrySelect.value = '';
                if (locationSelect) locationSelect.value = '';
                if (verifiedSelect) verifiedSelect.value = 'all';
                if (hasJobsSelect) hasJobsSelect.value = 'all';

                // Reset Custom UI
                document.querySelectorAll('.jf-modern-filter').forEach(container => {
                    const options = container.querySelectorAll('.jf-filter-option');
                    const displayText = container.querySelector('.jf-filter-current-text');

                    options.forEach(o => o.classList.remove('selected'));
                    const firstOption = options[0];
                    if (firstOption) {
                        firstOption.classList.add('selected');
                        displayText.textContent = firstOption.textContent.trim();
                    }
                });

                runSearch();
            });

            [industrySelect, locationSelect, verifiedSelect, hasJobsSelect].forEach(function (select) {
                select.addEventListener('change', runSearch);
            });

            loadMoreBtn.addEventListener('click', function () {
                if (hasMore) {
                    fetchEmployers(currentPage + 1, true);
                }
            });

            runSearch();
        })();
    </script>
</x-app-layout>
