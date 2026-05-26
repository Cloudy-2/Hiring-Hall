<x-app-layout page-title="Recommended Jobs">

    <x-slot name="url_1">{"link": "/candidate/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">Recommended Jobs</x-slot>

    @include('candidates.partials.candidate-styles')

    {{-- ═══ Page-specific Styles ═══ --}}
    <style>
        .rj-hero {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 14px;
            padding: 2rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .rj-hero-title { font-size: 1.5rem; font-weight: 800; color: #fff; margin-bottom: 0.25rem; }
        .rj-hero-sub   { font-size: 0.875rem; color: rgba(255,255,255,.8); }
        .rj-hero-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0.55rem 1.25rem; border-radius: 8px;
            background: rgba(255,255,255,.18); color: #fff;
            font-weight: 600; font-size: 0.8125rem;
            border: 1px solid rgba(255,255,255,.3);
            text-decoration: none; transition: all 0.2s;
        }
        .rj-hero-btn:hover { background: rgba(255,255,255,.3); }
        .rj-hero-btn i.arrow { transition: transform 0.2s; }
        .rj-hero-btn:hover i.arrow { transform: translateX(3px); }

        /* Filter bar */
        .rj-filters {
            display: flex; flex-wrap: wrap; gap: 0.5rem;
            padding: 0.75rem 1rem; border-radius: 10px;
            background: #f8fafc; border: 1px solid #f1f5f9;
        }
        .rj-filter-input {
            flex: 1; min-width: 160px; padding: 0.4rem 0.75rem 0.4rem 2rem;
            border-radius: 8px; border: 1px solid #e5e7eb;
            font-size: 0.8125rem; background: #fff; outline: none;
            transition: border-color 0.2s;
        }
        .rj-filter-input:focus { border-color: #818cf8; }
        .rj-filter-select {
            padding: 0.4rem 0.75rem; border-radius: 8px;
            border: 1px solid #e5e7eb; font-size: 0.8125rem;
            background: #fff; color: #6b7280; outline: none;
            cursor: pointer; min-width: 130px;
        }

        /* Job cards */
        .rj-card {
            background: #fff; border: 1px solid #f3f4f6;
            border-radius: 12px; padding: 1.25rem;
            display: flex; flex-direction: column; height: 100%;
            transition: all 0.25s ease;
            position: relative;
        }
        .rj-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0,0,0,.08);
            border-color: #e0e7ff;
        }

        /* Badge ribbon */
        .rj-badge-ribbon {
            position: absolute; top: 0.75rem; right: 0.75rem;
            font-size: 0.6875rem; font-weight: 700;
            padding: 3px 8px; border-radius: 6px;
            display: inline-flex; align-items: center; gap: 3px;
        }
        .rj-badge-hot       { background: #fef3c7; color: #d97706; }
        .rj-badge-new       { background: #dcfce7; color: #16a34a; }
        .rj-badge-match     { background: #ede9fe; color: #7c3aed; }

        .rj-card-avatar {
            width: 44px; height: 44px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 0.8125rem; flex-shrink: 0;
            background: #e5e7eb;
        }
        .rj-card-title a {
            font-weight: 700; font-size: 0.9375rem; color: #1f2937;
            text-decoration: none; transition: color 0.2s;
        }
        .rj-card-title a:hover { color: #4f46e5; }

        .rj-card-meta {
            font-size: 0.75rem; color: #9ca3af;
            display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem;
        }

        .rj-pill {
            font-size: 0.6875rem; font-weight: 600;
            padding: 3px 10px; border-radius: 20px;
            display: inline-flex; align-items: center; gap: 3px;
        }
        .rj-pill-type     { background: #eef2ff; color: #4f46e5; }
        .rj-pill-category { background: #ecfdf5; color: #059669; }
        .rj-pill-location { background: #f0f9ff; color: #0284c7; }

        .rj-card-cta {
            display: inline-flex; align-items: center; justify-content: center;
            gap: 6px; width: 100%; padding: 0.5rem;
            border-radius: 8px; font-weight: 600; font-size: 0.8125rem;
            border: none; cursor: pointer; text-decoration: none;
            transition: all 0.25s; color: #fff;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
        }
        .rj-card-cta:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            box-shadow: 0 4px 12px rgba(79,70,229,.3);
        }
        .rj-card-cta i.arrow { transition: transform 0.2s; }
        .rj-card-cta:hover i.arrow { transform: translateX(3px); }

        /* Saved section */
        .rj-saved-section {
            background: #fafbfd; border: 1px solid #e5e7eb;
            border-radius: 14px; padding: 1.5rem;
        }
        .rj-saved-head {
            font-size: 1.1rem; font-weight: 800; color: #1f2937;
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 0.25rem;
        }
        .rj-saved-head i { color: #d97706; }

        .rj-saved-row {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.75rem 1rem; border: 1px solid #f3f4f6;
            border-radius: 10px; background: #fff;
            transition: all 0.2s;
        }
        .rj-saved-row:hover {
            border-color: #e0e7ff; background: #fafbff;
            box-shadow: 0 2px 8px rgba(0,0,0,.04);
        }

        .rj-bulk-bar {
            display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1rem; border-radius: 10px;
            background: #f8fafc; border: 1px solid #f1f5f9;
        }
        .rj-bulk-btn {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 0.45rem 1rem; border-radius: 8px;
            font-weight: 600; font-size: 0.8125rem; border: none; cursor: pointer;
            background: #4f46e5; color: #fff; transition: all 0.2s;
        }
        .rj-bulk-btn:hover { background: #4338ca; box-shadow: 0 2px 8px rgba(79,70,229,.3); }

        /* Floating helper */
        .rj-float-helper {
            position: fixed; bottom: 1.5rem; right: 1.5rem;
            background: #1f2937; color: #fff; padding: 0.75rem 1.25rem;
            border-radius: 12px; font-size: 0.8125rem; font-weight: 600;
            display: none; align-items: center; gap: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,.18); z-index: 999;
            animation: rjSlideUp 0.4s ease;
            cursor: pointer;
        }
        .rj-float-helper:hover { background: #374151; }
        @keyframes rjSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="grid grid-cols-12 gap-x-5 gap-y-5">

        {{-- ═══ Hero ═══ --}}
        <div class="col-span-12" id="wt-hero">
            <div class="rj-hero">
                <div>
                    <h1 class="rj-hero-title"><i class="bi bi-stars me-2"></i>Recommended For You</h1>
                    <p class="rj-hero-sub">Based on your profile — apply before positions fill up.</p>
                </div>
                <div style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center">
                    <a href="{{ route('jobs') }}" class="rj-hero-btn">
                        Explore More Jobs <i class="ri-arrow-right-line arrow"></i>
                    </a>
                    <button type="button" onclick="startWalkthrough()" class="cd-hero-btn cd-tour-btn" style="padding:0.5rem 1rem;font-size:0.8125rem;border-radius:10px">
                        <i class="ri-rocket-2-fill"></i> Take a Tour
                    </button>
                </div>
            </div>
        </div>

        @if (session('status'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved Jobs',
                            text: @json(session('status')),
                            timer: 2200,
                            showConfirmButton: false
                        });
                    }
                });
            </script>
        @endif

        {{-- ═══ Filter Bar ═══ --}}
        <div class="col-span-12" id="wt-filters">
            <div class="rj-filters" id="rj-filter-bar">
                <div style="position:relative;flex:2;min-width:180px">
                    <i class="ri-search-line" style="position:absolute;left:0.65rem;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:0.875rem"></i>
                    <input type="text" class="rj-filter-input" id="rj-search" placeholder="Search jobs...">
                </div>
                <select class="rj-filter-select" id="rj-filter-location">
                    <option value="">All Locations</option>
                    @php
                        $locations = $recommendedJobs->pluck('location')->filter()->unique()->sort();
                    @endphp
                    @foreach($locations as $loc)
                        <option value="{{ Str::lower($loc) }}">{{ $loc }}</option>
                    @endforeach
                </select>
                <select class="rj-filter-select" id="rj-filter-type">
                    <option value="">All Job Types</option>
                    @php
                        $types = $recommendedJobs->pluck('employment_type')->filter()->unique()->sort();
                    @endphp
                    @foreach($types as $type)
                        <option value="{{ Str::lower($type) }}">{{ Str::headline($type) }}</option>
                    @endforeach
                </select>
                <select class="rj-filter-select" id="rj-filter-sort">
                    <option value="default">Sort: Recommended</option>
                    <option value="newest">Sort: Newest</option>
                    <option value="applicants">Sort: Most Applicants</option>
                </select>
            </div>
        </div>

        {{-- ═══ Job Cards Grid ═══ --}}
        <div class="col-span-12" id="wt-grid">
            @if($recommendedJobs->isEmpty())
                <div class="cd-section">
                    <div class="cd-empty">
                        <i class="bi bi-stars"></i>
                        <p>No recommended jobs available at the moment.</p>
                        <span style="font-size:0.8125rem;color:#9ca3af;margin-bottom:0.75rem;display:block">Apply to some jobs to get personalized recommendations.</span>
                        <a href="{{ route('jobs') }}" class="cd-btn cd-btn-primary"><i class="ri-search-line me-1"></i> Search Jobs</a>
                    </div>
                </div>
            @else
                <div id="rj-grid" class="grid grid-cols-12 gap-5">
                    @foreach($recommendedJobs as $index => $job)
                        @php
                            $colors = ['#4f46e5','#0891b2','#059669','#d97706','#dc2626','#7c3aed','#db2777'];
                            $avatarBg = $colors[$index % count($colors)];
                            $words = explode(' ', $job->company?->name ?? 'C');
                            $initials = strtoupper(substr($words[0] ?? 'C', 0, 1)) . strtoupper(substr($words[1] ?? '', 0, 1));
                            $applicantCount = $job->applications_count ?? 0;
                            $postedAt = $job->posted_at;
                            $isNew = $postedAt && $postedAt->gt(now()->subDays(3));
                            $isHot = $index < 3;
                            $isHighMatch = !$isHot && !$isNew && $applicantCount > 5;
                        @endphp
                        <div
                            class="xl:col-span-3 md:col-span-6 col-span-12 rj-card-wrap"
                            data-title="{{ Str::lower($job->title) }}"
                            data-company="{{ Str::lower($job->company?->name ?? '') }}"
                            data-location="{{ Str::lower($job->location ?? '') }}"
                            data-type="{{ Str::lower($job->employment_type ?? '') }}"
                            data-posted="{{ $postedAt ? $postedAt->timestamp : 0 }}"
                            data-applicants="{{ $applicantCount }}"
                        >
                            <div class="rj-card">

                                {{-- Badge ribbon --}}
                                @if($isHot)
                                    <span class="rj-badge-ribbon rj-badge-hot">🔥 Recommended</span>
                                @elseif($isNew)
                                    <span class="rj-badge-ribbon rj-badge-new">✨ New</span>
                                @elseif($isHighMatch)
                                    <span class="rj-badge-ribbon rj-badge-match">⚡ High Match</span>
                                @endif

                                {{-- Avatar + Company --}}
                                <div style="display:flex;align-items:flex-start;gap:0.75rem;margin-bottom:0.75rem">
                                    <div class="rj-card-avatar" style="background:{{ $avatarBg }}">
                                        @if($job->company?->logo_url)
                                            <img src="{{ $job->company->logo_url }}" alt="" style="width:100%;height:100%;border-radius:10px;object-fit:cover">
                                        @else
                                            <span style="color:#fff">{{ $initials }}</span>
                                        @endif
                                    </div>
                                    <div style="min-width:0;flex:1">
                                        <p style="font-size:0.8125rem;font-weight:600;color:#374151;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                            {{ $job->company?->name ?? 'Company' }}
                                        </p>
                                        <div class="rj-card-meta">
                                            <span><i class="ri-map-pin-2-line me-1"></i>{{ $job->location ?? 'Remote' }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Title --}}
                                <h5 class="rj-card-title" style="margin-bottom:0.5rem;line-height:1.35">
                                    <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a>
                                </h5>

                                {{-- Social proof --}}
                                <div class="rj-card-meta" style="margin-bottom:0.75rem">
                                    @if($applicantCount > 0)
                                        <span><i class="ri-group-line me-1"></i>{{ $applicantCount }} applicant{{ $applicantCount !== 1 ? 's' : '' }}</span>
                                        <span>·</span>
                                    @endif
                                    @if($postedAt)
                                        <span>Posted {{ $postedAt->diffForHumans() }}</span>
                                    @endif
                                </div>

                                {{-- Pills --}}
                                <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:0.75rem">
                                    @if($job->employment_type)
                                        <span class="rj-pill rj-pill-type"><i class="ri-briefcase-line me-1"></i>{{ Str::headline($job->employment_type) }}</span>
                                    @endif
                                    @if($job->category)
                                        <span class="rj-pill rj-pill-category">{{ Str::headline($job->category) }}</span>
                                    @endif
                                </div>

                                {{-- CTA --}}
                                <div style="margin-top:auto;padding-top:0.75rem;border-top:1px solid #f3f4f6">
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="rj-card-cta">
                                        View & Apply <i class="ri-arrow-right-line arrow"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- No results message --}}
                <div id="rj-no-results" style="display:none;text-align:center;padding:3rem 1rem">
                    <i class="ri-search-line" style="font-size:2rem;color:#d1d5db;display:block;margin-bottom:0.5rem"></i>
                    <p style="font-weight:600;color:#6b7280">No jobs match your filters.</p>
                    <button onclick="document.getElementById('rj-search').value='';document.getElementById('rj-filter-location').value='';document.getElementById('rj-filter-type').value='';filterCards();"
                            style="margin-top:0.5rem;background:none;border:none;color:#4f46e5;font-weight:600;cursor:pointer;font-size:0.875rem">
                        Clear all filters
                    </button>
                </div>
            @endif
        </div>

        {{-- ═══ Section Divider ═══ --}}
        <div class="col-span-12" style="margin-top:1rem">
            <div style="display:flex;align-items:center;gap:1rem">
                <div style="flex:1;height:2px;background:linear-gradient(90deg,#e5e7eb,transparent)"></div>
                <span style="font-size:0.75rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:1.5px;white-space:nowrap">Your Saved Collection</span>
                <div style="flex:1;height:2px;background:linear-gradient(270deg,#e5e7eb,transparent)"></div>
            </div>
        </div>

        {{-- ═══ Saved Jobs Section ═══ --}}
        <div class="col-span-12" id="wt-saved">
            <div class="rj-saved-section" style="border-left:4px solid #d97706">
                <div class="rj-saved-head" style="font-size:1.25rem"><i class="ri-bookmark-fill"></i> Saved Jobs</div>
                <p style="font-size:0.8125rem;color:#9ca3af;margin-bottom:1rem">Jobs you've bookmarked for later — don't let them slip away.</p>

                @if($savedJobs->isEmpty())
                    <div style="text-align:center;padding:2rem 1rem">
                        <i class="ri-bookmark-line" style="font-size:2rem;color:#d1d5db;display:block;margin-bottom:0.5rem"></i>
                        <p style="font-weight:600;color:#6b7280;margin-bottom:0.25rem">No saved jobs yet.</p>
                        <span style="font-size:0.8125rem;color:#9ca3af;display:block;margin-bottom:1rem">Browse jobs and click the heart icon to save them here.</span>
                        <a href="{{ route('jobs') }}" class="cd-btn cd-btn-outline" style="display:inline-flex"><i class="ri-search-line me-1"></i> Browse Jobs</a>
                    </div>
                @else
                    {{-- Bulk actions --}}
                    <div class="rj-bulk-bar" style="margin-bottom:1rem">
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer">
                            <input type="checkbox" id="select-all" class="form-check-input" style="margin:0;width:16px;height:16px">
                            <span style="font-size:0.8125rem;font-weight:600;color:#374151">Select All</span>
                        </label>
                        <select id="bulk-action-select" class="rj-filter-select" style="min-width:150px">
                            <option value="">Bulk action</option>
                            <option value="remove">Remove Selected</option>
                            <option value="apply">Apply to Selected</option>
                        </select>
                        <button type="button" id="apply-bulk-action" class="rj-bulk-btn">
                            <i class="ri-check-double-line"></i> Apply Action
                        </button>
                        <div style="position:relative;flex:1;min-width:180px;max-width:300px">
                            <i class="ri-search-line" style="position:absolute;left:0.65rem;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:0.875rem"></i>
                            <input type="text" id="search-input" class="rj-filter-input" placeholder="Search saved jobs...">
                        </div>
                        <span id="selected-count" style="font-size:0.75rem;font-weight:600;color:#4f46e5;display:none">
                            <span id="selected-count-num">0</span> selected
                        </span>
                    </div>

                    {{-- Saved Jobs List --}}
                    <div id="saved-jobs-list" style="display:flex;flex-direction:column;gap:0.5rem">
                        @foreach($savedJobs as $saved)
                            @php
                                $job = $saved->jobPosting;
                                if (!$job) { continue; }
                                $status = $applicationsByJobId[$job->id] ?? null;
                                $savedWords = explode(' ', $job->company?->name ?? 'C');
                                $savedInitials = strtoupper(substr($savedWords[0] ?? 'C', 0, 1)) . strtoupper(substr($savedWords[1] ?? '', 0, 1));
                            @endphp
                            <div
                                class="rj-saved-row saved-job-item"
                                data-title="{{ Str::lower($job->title) }}"
                                data-company="{{ Str::lower($job->company?->name ?? '') }}"
                                data-date="{{ optional($saved->saved_at)->timestamp ?? 0 }}"
                            >
                                <input type="checkbox" class="form-check-input row-checkbox" value="{{ $job->id }}" data-title="{{ $job->title }}" data-applied="{{ $status ? '1' : '0' }}" style="margin:0;flex-shrink:0;width:16px;height:16px">

                                <div style="width:36px;height:36px;border-radius:8px;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;color:#6b7280;flex-shrink:0;overflow:hidden">
                                    @if($job->company?->logo_url)
                                        <img src="{{ $job->company->logo_url }}" alt="" style="width:100%;height:100%;object-fit:cover">
                                    @else
                                        {{ $savedInitials }}
                                    @endif
                                </div>

                                <div style="flex:1;min-width:0">
                                    <h6 style="font-weight:600;font-size:0.875rem;margin-bottom:2px">
                                        <a href="{{ route('jobs.show', $job->slug) }}" style="color:#1f2937;text-decoration:none">{{ $job->title }}</a>
                                    </h6>
                                    <div style="display:flex;flex-wrap:wrap;align-items:center;gap:0.5rem;font-size:0.75rem;color:#9ca3af">
                                        <span>{{ $job->company?->name ?? 'Company' }}</span>
                                        @if($job->location)
                                            <span>·</span>
                                            <span><i class="ri-map-pin-2-line me-1"></i>{{ $job->location }}</span>
                                        @endif
                                        @if($status)
                                            <span style="background:#dcfce7;color:#16a34a;font-size:0.6875rem;padding:2px 8px;border-radius:20px;font-weight:600">
                                                <i class="ri-check-line me-1"></i>{{ Str::headline($status) }}
                                            </span>
                                        @endif
                                        @if($saved->saved_at)
                                            <span>· Saved {{ $saved->saved_at->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div style="display:flex;gap:6px;flex-shrink:0">
                                    @if($status)
                                        <a href="{{ route('candidate.applications.index') }}" title="View application"
                                           style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;color:#4f46e5;font-size:0.875rem;transition:all 0.2s">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('jobs.show', $job->slug) }}" title="Apply now"
                                           style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;background:#4f46e5;color:#fff;font-size:0.875rem;transition:all 0.2s">
                                            <i class="ri-briefcase-line"></i>
                                        </a>
                                    @endif
                                    <button type="button"
                                            class="saved-job-remove-btn"
                                            data-remove-form-id="remove-saved-job-{{ $job->id }}"
                                            data-job-title="{{ $job->title }}"
                                            title="Remove"
                                            style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:1px solid #fecaca;background:#fef2f2;color:#dc2626;font-size:0.875rem;cursor:pointer;transition:all 0.2s">
                                        <i class="ri-delete-bin-6-line"></i>
                                    </button>
                                    <form id="remove-saved-job-{{ $job->id }}" method="POST" action="{{ route('jobs.save', $job->slug) }}" class="hidden">
                                        @csrf
                                        <input type="hidden" name="redirect" value="recommended-jobs">
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div style="margin-top:1rem">
                        {{ $savedJobs->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Floating Helper --}}
    <div class="rj-float-helper" id="rj-float-helper" onclick="document.getElementById('rj-filter-bar').scrollIntoView({behavior:'smooth'})">
        <i class="ri-filter-3-line"></i> Need help choosing? Refine your filters.
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ── Recommended jobs filtering ──
            var searchEl = document.getElementById('rj-search');
            var locEl    = document.getElementById('rj-filter-location');
            var typeEl   = document.getElementById('rj-filter-type');
            var sortEl   = document.getElementById('rj-filter-sort');
            var grid     = document.getElementById('rj-grid');
            var noRes    = document.getElementById('rj-no-results');
            var cards    = grid ? Array.from(grid.querySelectorAll('.rj-card-wrap')) : [];

            function filterCards() {
                var q = (searchEl ? searchEl.value : '').toLowerCase();
                var loc = locEl ? locEl.value : '';
                var type = typeEl ? typeEl.value : '';
                var visible = 0;

                cards.forEach(function(c) {
                    var title = c.getAttribute('data-title') || '';
                    var company = c.getAttribute('data-company') || '';
                    var cLoc = c.getAttribute('data-location') || '';
                    var cType = c.getAttribute('data-type') || '';

                    var matchText = !q || title.indexOf(q) !== -1 || company.indexOf(q) !== -1;
                    var matchLoc = !loc || cLoc === loc;
                    var matchType = !type || cType === type;

                    if (matchText && matchLoc && matchType) {
                        c.style.display = '';
                        visible++;
                    } else {
                        c.style.display = 'none';
                    }
                });

                if (noRes) noRes.style.display = visible === 0 ? '' : 'none';
            }
            window.filterCards = filterCards;

            if (searchEl) searchEl.addEventListener('input', filterCards);
            if (locEl) locEl.addEventListener('change', filterCards);
            if (typeEl) typeEl.addEventListener('change', filterCards);

            if (sortEl) {
                sortEl.addEventListener('change', function() {
                    var val = sortEl.value;
                    cards.sort(function(a, b) {
                        if (val === 'newest') {
                            return (parseInt(b.getAttribute('data-posted')) || 0) - (parseInt(a.getAttribute('data-posted')) || 0);
                        } else if (val === 'applicants') {
                            return (parseInt(b.getAttribute('data-applicants')) || 0) - (parseInt(a.getAttribute('data-applicants')) || 0);
                        }
                        return 0;
                    });
                    cards.forEach(function(c) { grid.appendChild(c); });
                });
            }

            // ── Floating helper after scrolling past 12 cards ──
            var helper = document.getElementById('rj-float-helper');
            var helperShown = false;
            if (helper && cards.length > 12) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (!entry.isIntersecting && !helperShown) {
                            helperShown = true;
                            helper.style.display = 'flex';
                        }
                    });
                }, { threshold: 0 });
                if (cards[11]) observer.observe(cards[11]);
            }

            // ── Saved jobs: select-all, bulk actions, search ──
            var selectAllCheckbox = document.getElementById('select-all');
            var rowCheckboxes = document.querySelectorAll('.row-checkbox');
            var bulkActionSelect = document.getElementById('bulk-action-select');
            var applyBulkAction = document.getElementById('apply-bulk-action');
            var selectedCountEl = document.getElementById('selected-count');
            var selectedCountNum = document.getElementById('selected-count-num');
            var searchInput = document.getElementById('search-input');
            var items = document.querySelectorAll('#saved-jobs-list .saved-job-item');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    var q = searchInput.value.toLowerCase();
                    items.forEach(function (el) {
                        var title = el.getAttribute('data-title') || '';
                        var company = el.getAttribute('data-company') || '';
                        if (!q || title.indexOf(q) !== -1 || company.indexOf(q) !== -1) {
                            el.style.display = '';
                        } else {
                            el.style.display = 'none';
                        }
                    });
                });
            }

            function updateSelectedCount() {
                var checked = document.querySelectorAll('.row-checkbox:checked');
                var count = checked.length;
                if (count > 0) {
                    if (selectedCountEl) { selectedCountEl.style.display = ''; }
                    if (selectedCountNum) { selectedCountNum.textContent = count; }
                } else {
                    if (selectedCountEl) { selectedCountEl.style.display = 'none'; }
                }
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    rowCheckboxes.forEach(function(cb) {
                        cb.checked = selectAllCheckbox.checked;
                    });
                    updateSelectedCount();
                });
            }

            rowCheckboxes.forEach(function(cb) {
                cb.addEventListener('change', function() {
                    var allChecked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length;
                    if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
                    updateSelectedCount();
                });
            });

            if (applyBulkAction) {
                applyBulkAction.addEventListener('click', function() {
                    var action = bulkActionSelect.value;
                    var checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
                    var ids = Array.from(checkedBoxes).map(function(cb) { return cb.value; });

                    if (!action) {
                        Swal.fire({ icon: 'warning', title: 'Select an action', text: 'Please select a bulk action first.' });
                        return;
                    }
                    if (ids.length === 0) {
                        Swal.fire({ icon: 'warning', title: 'No jobs selected', text: 'Please select at least one job.' });
                        return;
                    }

                    if (action === 'remove') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Remove ' + ids.length + ' saved job(s)?',
                            text: 'This will remove the selected jobs from your saved list.',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, remove',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#dc2626'
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                fetch('{{ route("candidate.saved-jobs.bulk-remove") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ ids: ids })
                                }).then(function(r) { return r.json(); })
                                .then(function(data) {
                                    Swal.fire({ icon: 'success', title: 'Removed', text: ids.length + ' job(s) removed from saved list.', timer: 1800, showConfirmButton: false })
                                    .then(function() { window.location.reload(); });
                                }).catch(function() {
                                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to remove jobs.' });
                                });
                            }
                        });
                    } else if (action === 'apply') {
                        var applyableIds = Array.from(checkedBoxes).filter(function(cb) {
                            return cb.getAttribute('data-applied') !== '1';
                        }).map(function(cb) { return cb.value; });

                        if (applyableIds.length === 0) {
                            Swal.fire({ icon: 'info', title: 'Already Applied', text: 'You have already applied to all selected jobs.' });
                            return;
                        }

                        Swal.fire({
                            icon: 'question',
                            title: 'Apply to ' + applyableIds.length + ' job(s)?',
                            text: 'This will submit applications to the selected jobs.',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, apply',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#3b82f6'
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                fetch('{{ route("candidate.saved-jobs.bulk-apply") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ ids: applyableIds })
                                }).then(function(r) { return r.json(); })
                                .then(function(data) {
                                    Swal.fire({ icon: 'success', title: 'Applied', text: 'Applications submitted successfully.', timer: 1800, showConfirmButton: false })
                                    .then(function() { window.location.reload(); });
                                }).catch(function() {
                                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to submit applications.' });
                                });
                            }
                        });
                    }
                });
            }

            var removeButtons = document.querySelectorAll('.saved-job-remove-btn');
            removeButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var formId = btn.getAttribute('data-remove-form-id');
                    var title = btn.getAttribute('data-job-title') || 'this job';

                    if (window.Swal) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Remove saved job?',
                            text: 'This will remove "' + title + '" from your saved jobs.',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, remove it',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#dc2626',
                        }).then(function (result) {
                            if (result.isConfirmed && formId) {
                                var form = document.getElementById(formId);
                                if (form) form.submit();
                            }
                        });
                    } else if (formId) {
                        var form = document.getElementById(formId);
                        if (form) form.submit();
                    }
                });
            });
        });
    </script>

    @include('candidates.partials.walkthrough', [
        'wtKey' => 'recommended_jobs',
        'wtSteps' => [
            ['target' => 'wt-hero', 'icon' => 'bi bi-stars', 'title' => 'Recommended For You', 'body' => 'Jobs are recommended based on your profile, skills, and past applications. The more you apply, the smarter your recommendations get.', 'position' => 'bottom'],
            ['target' => 'wt-filters', 'icon' => 'ri-filter-3-line', 'title' => 'Filter & Sort', 'body' => 'Use the search bar to find specific jobs, filter by location or type, and sort by newest or most popular. Filters work instantly.', 'position' => 'bottom'],
            ['target' => 'wt-grid', 'icon' => 'ri-briefcase-fill', 'title' => 'Job Cards', 'body' => 'Browse recommended positions. Look for badges like \"Recommended\", \"New\", and \"High Match\" to prioritize your applications. Click \"View & Apply\" to get started.', 'position' => 'top'],
            ['target' => 'wt-saved', 'icon' => 'ri-bookmark-fill', 'title' => 'Saved Jobs', 'body' => 'Jobs you have bookmarked appear here. You can quickly apply to them or remove them from your saved collection.', 'position' => 'top'],
        ]
    ])

</x-app-layout>
