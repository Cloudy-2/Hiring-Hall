<x-app-layout
    :pageTitle="$profile->display_name ?? $user->name"
    :active="$profile->display_name ?? $user->name"
    :breadcrumbs="[['label' => 'Search Applicants', 'url' => route('applicants')]]"
>

    @include('employers.partials.employer-styles')

    @php
        $experience     = $profile->experience_overview ? json_decode($profile->experience_overview, true) : null;
        $education       = $profile->education_details  ? json_decode($profile->education_details, true)  : [];
        $certifications  = $profile->certifications     ? json_decode($profile->certifications, true)     : [];
        $skills          = $profile->skills             ? json_decode($profile->skills, true)             : [];
        $tools           = $profile->tools_used         ? json_decode($profile->tools_used, true)         : [];
        $languages       = $profile->languages          ? json_decode($profile->languages, true)          : [];
        $achievements    = $profile->key_achievements   ? json_decode($profile->key_achievements, true)   : [];
        $references      = $profile->references_block   ? json_decode($profile->references_block, true)   : [];
    @endphp

    {{-- ═══ Page-level scoped styles ═══ --}}
    <style>
        .cv-profile-card {
            background: #fff; border: 1px solid var(--cd-border); border-radius: var(--cd-r);
            box-shadow: var(--cd-s); font-family: var(--cd-font); overflow: hidden;
            transition: box-shadow var(--cd-t);
        }
        .cv-profile-card:hover { box-shadow: var(--cd-sh); }

        .cv-avatar-wrap {
            position: relative; width: 110px; height: 110px; border-radius: 16px;
            overflow: hidden; border: 3px solid #fff; box-shadow: 0 4px 14px rgba(0,0,0,0.15);
            margin: -55px auto 0; z-index: 2;
        }
        .cv-avatar-wrap img { width: 100%; height: 100%; object-fit: cover; }

        .cv-detail-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.6rem 0; border-bottom: 1px solid var(--cd-bg-stripe);
            font-size: var(--cd-font-sm);
        }
        .cv-detail-row:last-child { border-bottom: none; }
        .cv-detail-label { color: var(--cd-text-muted); display: flex; align-items: center; gap: 6px; }
        .cv-detail-label i { color: var(--cd-accent); font-size: 1rem; }
        .cv-detail-value { font-weight: 600; color: var(--cd-text); text-align: right; }

        .cv-skill-tag {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 12px; border-radius: 20px; font-size: var(--cd-font-xs);
            font-weight: 600; transition: transform 0.15s ease;
        }
        .cv-skill-tag:hover { transform: translateY(-1px); }
        .cv-skill-tag--primary { background: var(--cd-accent-light); color: var(--cd-accent); }
        .cv-skill-tag--teal    { background: #f0fdfa; color: #0d9488; }

        .cv-timeline { position: relative; padding-left: 1.5rem; }
        .cv-timeline::before {
            content: ''; position: absolute; left: 5px; top: 8px; bottom: 8px;
            width: 2px; background: linear-gradient(180deg, var(--cd-accent) 0%, var(--cd-accent-light) 100%);
            border-radius: 2px;
        }
        .cv-timeline-dot {
            position: absolute; left: -1.5rem; top: 4px;
            width: 12px; height: 12px; border-radius: 50%;
            background: var(--cd-accent); border: 3px solid var(--cd-accent-light);
        }
        .cv-timeline-item { position: relative; padding-bottom: 1.25rem; }
        .cv-timeline-item:last-child { padding-bottom: 0; }

        .cv-ref-card {
            padding: 1rem; border: 1px solid var(--cd-bg-stripe); border-radius: 12px;
            background: var(--cd-bg-alt); transition: box-shadow 0.2s, transform 0.2s;
        }
        .cv-ref-card:hover { box-shadow: var(--cd-sh); transform: translateY(-2px); }

        .cv-related-card {
            display: flex; align-items: center; gap: 12px;
            padding: 0.85rem; border: 1px solid var(--cd-bg-stripe); border-radius: 12px;
            background: #fff; text-decoration: none; color: inherit;
            transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
        }
        .cv-related-card:hover { box-shadow: var(--cd-sh); transform: translateY(-2px); border-color: var(--cd-accent-border); color: inherit; }

        .cv-resume-banner {
            display: flex; align-items: center; gap: 12px;
            padding: 0.75rem 1rem; border-radius: 10px;
            background: var(--cd-accent-light); border: 1px solid var(--cd-accent-border);
            margin-top: 1rem;
        }

        .cv-resume-banner-empty {
            background: #fef2f2; border-color: #fecaca;
        }

        .cv-empty { color: var(--cd-text-muted); font-size: var(--cd-font-sm); font-style: italic; }

        /* Dark mode */
        [data-theme-mode="dark"] .cv-profile-card, .dark .cv-profile-card { background: var(--bodybg, #1a1c2e); border-color: rgba(255,255,255,0.08); }
        [data-theme-mode="dark"] .cv-detail-row, .dark .cv-detail-row { border-color: rgba(255,255,255,0.06); }
        [data-theme-mode="dark"] .cv-detail-value, .dark .cv-detail-value { color: #e5e7eb; }
        [data-theme-mode="dark"] .cv-ref-card, .dark .cv-ref-card { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
        [data-theme-mode="dark"] .cv-related-card, .dark .cv-related-card { background: var(--bodybg, #1a1c2e); border-color: rgba(255,255,255,0.08); }
        [data-theme-mode="dark"] .cv-resume-banner, .dark .cv-resume-banner { background: rgba(79,70,229,0.12); border-color: rgba(79,70,229,0.25); }
        [data-theme-mode="dark"] .cv-resume-banner-empty, .dark .cv-resume-banner-empty { background: rgba(220,38,38,0.15); border-color: rgba(220,38,38,0.3); }
        [data-theme-mode="dark"] .cv-avatar-wrap, .dark .cv-avatar-wrap { border-color: var(--bodybg, #1a1c2e); }
    </style>

    <x-modern-header chip="Candidate Details" title="{{ $profile->display_name ?? $user->name }}."
        desc='Applicant Profile'>
    </x-modern-header>

    <div class="grid grid-cols-12 gap-x-5 gap-y-4 mx-auto pb-6 sm:px-6 lg:px-8">

        {{-- ═══ SIDEBAR — Profile Card ═══ --}}
        <div class="col-span-12 xl:col-span-4">
            <div class="cv-profile-card">
                {{-- Banner --}}
                <div style="height:80px;background:linear-gradient(135deg,#4338ca 0%,#6366f1 50%,#818cf8 100%);border-radius:var(--cd-r) var(--cd-r) 0 0"></div>

                {{-- Avatar --}}
                <div style="text-align:center;padding:0 1.25rem">
                    <div class="cv-avatar-wrap">
                        @if ($user->profile_photo_path)
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                        @else
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->name) }}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981" alt="{{ $user->name }}">
                        @endif
                    </div>

                    <h4 style="font-family:var(--cd-font);font-weight:800;font-size:1.15rem;margin:0.75rem 0 0.15rem;color:var(--cd-text)">{{ $profile->display_name ?? $user->name }}</h4>
                    <p style="font-family:var(--cd-font);font-size:var(--cd-font-sm);color:var(--cd-accent);font-weight:600;margin-bottom:0.25rem">{{ $profile->job_title ?? $profile->title ?? 'Title not set' }}</p>
                    @if($profile->location)
                        <p style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-text-muted);margin-bottom:0"><i class="ri-map-pin-2-fill me-1"></i>{{ $profile->location }}</p>
                    @endif

                    {{-- Verification Status Badge --}}
                    @if($profile->verification_status)
                        <div style="margin-top:0.5rem;display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;
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

                    {{-- View Resume Button --}}
                    <div style="margin-top:0.85rem;display:flex;gap:0.5rem;justify-content:center">
                        @if($profile->cv_path)
                            <a href="{{ route('applicants.download-cv', $profile) }}" class="cd-btn cd-btn-primary" style="width:100%;justify-content:center;padding:0.55rem 1rem">
                                <i class="ri-file-text-line"></i> View Resume
                            </a>
                        @else
                            <button type="button" class="cd-btn cd-btn-outline" style="width:100%;justify-content:center;padding:0.55rem 1rem" onclick="Swal.fire({icon:'info',title:'No Resume Available',text:'This candidate hasn\'t uploaded a resume yet.',confirmButtonColor:'#4f46e5'})">
                                <i class="ri-file-text-line"></i> View Resume
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Quick Details --}}
                <div style="padding:1rem 1.25rem 0">
                    <div class="cv-detail-row">
                        <span class="cv-detail-label"><i class="ri-briefcase-4-line"></i> Role</span>
                        <span class="cv-detail-value">{{ $profile->job_title ?? $profile->title ?? '-' }}</span>
                    </div>
                    <div class="cv-detail-row">
                        <span class="cv-detail-label"><i class="ri-computer-line"></i> Work Mode</span>
                        <span class="cv-detail-value">{{ $profile->work_mode ?? '-' }}</span>
                    </div>
                    <div class="cv-detail-row">
                        <span class="cv-detail-label"><i class="ri-timer-line"></i> Experience</span>
                        <span class="cv-detail-value">{{ $profile->years_experience ? $profile->years_experience . ' Years' : '-' }}</span>
                    </div>
                    <div class="cv-detail-row">
                        <span class="cv-detail-label"><i class="ri-time-line"></i> Availability</span>
                        <span class="cv-detail-value">{{ $profile->availability ?? '-' }}</span>
                    </div>
                    <div class="cv-detail-row">
                        <span class="cv-detail-label"><i class="ri-briefcase-4-line"></i> Job Type</span>
                        <span class="cv-detail-value">{{ $profile->job_type ?? '-' }}</span>
                    </div>
                    <div class="cv-detail-row">
                        <span class="cv-detail-label"><i class="ri-money-dollar-circle-line"></i> Expected Monthly Salary</span>
                        <span class="cv-detail-value">
                            @if($profile->expected_salary_min || $profile->expected_salary_max)
                                {{ $profile->salary_currency ?? 'USD' }} {{ number_format($profile->expected_salary_min ?? 0) }} – {{ number_format($profile->expected_salary_max ?? 0) }}
                            @else - @endif
                        </span>
                    </div>
                </div>

                {{-- Rating (employers can rate this candidate) --}}
                @auth
                <div style="padding:0 1.25rem;margin-top:0.5rem;padding-top:1rem;border-top:1px solid var(--cd-bg-stripe)">
                    <div style="font-size:var(--cd-font-sm);font-weight:600;color:var(--cd-text);margin-bottom:0.5rem;display:flex;align-items:center;gap:6px">
                        <i class="ri-star-line" style="color:#f59e0b"></i> Rating
                    </div>
                    <div class="applicant-rating-stars" style="display:inline-flex;gap:2px;cursor:pointer;align-items:center" data-applicant-id="{{ $profile->id }}" data-rating="{{ $profile->rating ?? 0 }}" data-rating-count="{{ $profile->rating_count ?? 0 }}">
                        @php $avgRating = $profile->rating ?? 0; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($avgRating))
                                <span class="applicant-star-btn" data-star="{{ $i }}" style="color:#f59e0b"><i class="bi bi-star-fill"></i></span>
                            @elseif($i - 0.5 <= $avgRating)
                                <span class="applicant-star-btn" data-star="{{ $i }}" style="color:#f59e0b"><i class="bi bi-star-half"></i></span>
                            @else
                                <span class="applicant-star-btn" data-star="{{ $i }}" style="color:#f59e0b"><i class="bi bi-star"></i></span>
                            @endif
                        @endfor
                        <span class="applicant-rating-count" style="color:var(--cd-text-muted);font-size:var(--cd-font-xs);margin-left:6px">
                            (<span class="applicant-rating-count-value">{{ $profile->rating_count ?? 0 }}</span>)
                        </span>
                    </div>
                    <p style="font-size:var(--cd-font-xs);color:var(--cd-text-muted);margin-top:0.35rem;margin-bottom:0">Click a star to rate</p>
                </div>
                @endauth

                {{-- Resume --}}
                <div style="padding:0 1.25rem 1.25rem">
                    @if($profile->cv_path)
                        <div class="cv-resume-banner">
                            <i class="ri-file-text-fill" style="font-size:1.5rem;color:var(--cd-accent)"></i>
                            <div style="flex:1;min-width:0">
                                <p style="font-family:var(--cd-font);font-size:var(--cd-font-sm);font-weight:600;color:var(--cd-text);margin:0">Resume Uploaded</p>
                                <p style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-text-muted);margin:0">Click to view the applicant's resume</p>
                            </div>
                            <a href="{{ route('applicants.download-cv', $profile) }}" class="cd-btn cd-btn-primary cd-btn-sm"><i class="ri-eye-line"></i> View</a>
                        </div>
                    @else
                        <div class="cv-resume-banner cv-resume-banner-empty">
                            <i class="ri-file-close-line" style="font-size:1.5rem;color:#dc2626"></i>
                            <div style="flex:1;min-width:0">
                                <p style="font-family:var(--cd-font);font-size:var(--cd-font-sm);font-weight:600;color:var(--cd-text);margin:0">No Resume</p>
                                <p style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-text-muted);margin:0">This applicant hasn't uploaded a resume yet</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Languages Card --}}
            @if(count($languages) > 0)
            <div class="cd-section" style="margin-top:1rem">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-translate-2"></i> Languages</span>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:6px">
                    @foreach($languages as $lang)
                        @if(!empty($lang))
                            <span class="cv-skill-tag cv-skill-tag--teal"><i class="ri-global-line" style="font-size:0.7rem"></i> {{ $lang }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- ═══ MAIN CONTENT ═══ --}}
        <div class="col-span-12 xl:col-span-8" x-data="{ activeTab: 'about' }">

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
                    <button @click="activeTab = 'references'"
                        :class="activeTab === 'references' ? 'border-primary text-primary' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-700'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                        <i class="ri-contacts-book-line me-2"></i>References
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}
            <div class="mt-2">
                {{-- About Tab --}}
                <div x-show="activeTab === 'about'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    style="display:flex;flex-direction:column;gap:1rem">
                    
                    {{-- About Me --}}
                    <div class="cd-section" style="margin-bottom:1rem">
                        <div class="cd-section-head">
                            <span class="cd-section-label"><i class="ri-user-heart-line"></i> About Me</span>
                        </div>
                        <p style="font-family:var(--cd-font);font-size:var(--cd-font-sm);color:var(--cd-text-secondary);line-height:1.7;margin:0">
                            {{ $profile->about ?? 'No information provided.' }}
                        </p>
                    </div>

                    {{-- Career Objective --}}
                    <div class="cd-section">
                        <div class="cd-section-head">
                            <span class="cd-section-label"><i class="ri-focus-3-line"></i> Career Objective</span>
                        </div>
                        <p style="font-family:var(--cd-font);font-size:var(--cd-font-sm);color:var(--cd-text-secondary);line-height:1.7;margin:0">
                            {{ $profile->career_objective ?? 'No information provided.' }}
                        </p>
                    </div>
                </div>

                {{-- Experience Tab --}}
                <div x-show="activeTab === 'experience'" x-cloak
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0">
                    
                    {{-- Experience --}}
                    <div class="cd-section">
                        <div class="cd-section-head">
                            <span class="cd-section-label"><i class="ri-briefcase-fill"></i> Experience</span>
                        </div>
                        @if($experience && !empty($experience['position']))
                            <div class="cv-timeline">
                                <div class="cv-timeline-item">
                                    <div class="cv-timeline-dot"></div>
                                    <div style="margin-bottom:0.25rem">
                                        <strong style="font-family:var(--cd-font);font-size:var(--cd-font-base);color:var(--cd-text)">{{ $experience['position'] }}</strong>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:0.35rem">
                                        <span style="font-family:var(--cd-font);font-size:var(--cd-font-sm);color:var(--cd-accent);font-weight:600">{{ $experience['company'] ?? '' }}</span>
                                        @if(!empty($experience['start_date']) || !empty($experience['end_date']))
                                            <span style="font-size:var(--cd-font-xs);color:var(--cd-text-muted)">•</span>
                                            <span style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-text-muted)">{{ $experience['start_date'] ?? '' }} – {{ $experience['end_date'] ?? '' }}</span>
                                        @endif
                                    </div>
                                    @if(!empty($experience['location']))
                                        <p style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-text-muted);margin-bottom:0.5rem"><i class="ri-map-pin-line me-1"></i>{{ $experience['location'] }}</p>
                                    @endif
                                    @if(!empty($experience['responsibilities']))
                                        <ul style="margin:0;padding-left:1.25rem;list-style:disc">
                                            @foreach($experience['responsibilities'] as $resp)
                                                @if(!empty($resp))
                                                    <li style="font-family:var(--cd-font);font-size:var(--cd-font-sm);color:var(--cd-text-secondary);line-height:1.6;margin-bottom:0.15rem">{{ $resp }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="cv-empty">No experience added.</p>
                        @endif
                    </div>
                </div>

                {{-- Education Tab --}}
                <div x-show="activeTab === 'education'" x-cloak
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    style="display:flex;flex-direction:column;gap:1rem">
                    
                    {{-- Education & Certifications — side by side --}}
                    <div class="grid grid-cols-12 gap-4">
                        {{-- Education --}}
                        <div class="col-span-12 md:col-span-6 mb-4">
                            <div class="cd-section" style="height:100%">
                                <div class="cd-section-head">
                                    <span class="cd-section-label"><i class="ri-graduation-cap-fill"></i> Education</span>
                                </div>
                                @if(count($education) > 0)
                                    <div class="cv-timeline">
                                        @foreach($education as $edu)
                                            <div class="cv-timeline-item">
                                                <div class="cv-timeline-dot" style="background:#0d9488;border-color:#f0fdfa"></div>
                                                <strong style="font-family:var(--cd-font);font-size:var(--cd-font-sm);color:var(--cd-text)">{{ $edu['school'] ?? '' }}</strong>
                                                <p style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-accent);font-weight:500;margin:0.1rem 0">{{ $edu['course'] ?? '' }}</p>
                                                <p style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-text-muted);margin:0">{{ $edu['dates'] ?? '' }}
                                                    @if(!empty($edu['location'])) · <i class="ri-map-pin-line"></i> {{ $edu['location'] }} @endif
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="cv-empty">No education details added.</p>
                                @endif
                            </div>
                        </div>
                        {{-- Certifications --}}
                        <div class="col-span-12 md:col-span-6 mb-4">
                            <div class="cd-section" style="height:100%">
                                <div class="cd-section-head">
                                    <span class="cd-section-label"><i class="ri-medal-fill"></i> Certifications</span>
                                </div>
                                @if(count($certifications) > 0)
                                    <div style="display:flex;flex-direction:column;gap:0.65rem">
                                        @foreach($certifications as $cert)
                                            <div style="display:flex;align-items:flex-start;gap:10px">
                                                <div style="width:32px;height:32px;border-radius:8px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                                    <i class="ri-medal-line" style="color:#d97706;font-size:1rem"></i>
                                                </div>
                                                <div>
                                                    <p style="font-family:var(--cd-font);font-size:var(--cd-font-sm);font-weight:600;color:var(--cd-text);margin:0">{{ $cert['title'] ?? '' }}</p>
                                                    <p style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-text-muted);margin:0">{{ $cert['provider'] ?? '' }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="cv-empty">No certifications added.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Skills & Tools --}}
                    <div class="cd-section">
                        <div class="cd-section-head">
                            <span class="cd-section-label"><i class="ri-tools-fill"></i> Skills & Tools</span>
                        </div>
                        <div style="margin-bottom:1rem">
                            <h6 style="font-family:var(--cd-font);font-size:var(--cd-font-xs);font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--cd-text-muted);margin-bottom:0.5rem">Skills</h6>
                            <div style="display:flex;flex-wrap:wrap;gap:6px">
                                @if(count($skills) > 0)
                                    @foreach($skills as $skill)
                                        @if(!empty($skill))
                                            <span class="cv-skill-tag cv-skill-tag--primary">{{ $skill }}</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="cv-empty">—</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <h6 style="font-family:var(--cd-font);font-size:var(--cd-font-xs);font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--cd-text-muted);margin-bottom:0.5rem">Tools Used</h6>
                            <div style="display:flex;flex-wrap:wrap;gap:6px">
                                @if(count($tools) > 0)
                                    @foreach($tools as $tool)
                                        @if(!empty($tool))
                                            <span class="cv-skill-tag cv-skill-tag--teal">{{ $tool }}</span>
                                        @endif
                                    @endforeach
                                @else
                                    <span class="cv-empty">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Achievements Tab --}}
                <div x-show="activeTab === 'achievements'" x-cloak
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0">
                    
                    {{-- Key Achievements --}}
                    @if(count($achievements) > 0)
                    <div class="cd-section">
                        <div class="cd-section-head">
                            <span class="cd-section-label"><i class="ri-trophy-fill"></i> Key Achievements</span>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:0.5rem">
                            @foreach($achievements as $ach)
                                @if(!empty($ach))
                                    <div style="display:flex;align-items:flex-start;gap:8px">
                                        <i class="ri-star-fill" style="color:#f59e0b;font-size:0.85rem;margin-top:3px;flex-shrink:0"></i>
                                        <span style="font-family:var(--cd-font);font-size:var(--cd-font-sm);color:var(--cd-text-secondary);line-height:1.5">{{ $ach }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="cd-section">
                        <div class="cd-section-head">
                            <span class="cd-section-label"><i class="ri-trophy-fill"></i> Key Achievements</span>
                        </div>
                        <p class="cv-empty">No achievements added.</p>
                    </div>
                    @endif
                </div>

                {{-- References Tab --}}
                <div x-show="activeTab === 'references'" x-cloak
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0">
                    
                    {{-- References --}}
                    <div class="cd-section">
                        <div class="cd-section-head">
                            <span class="cd-section-label"><i class="ri-contacts-book-fill"></i> References</span>
                        </div>
                        @if(count($references) > 0)
                            <div class="grid grid-cols-12 gap-3">
                                @foreach($references as $ref)
                                    <div class="col-span-12 md:col-span-6">
                                        <div class="cv-ref-card">
                                            <strong style="font-family:var(--cd-font);font-size:var(--cd-font-sm);color:var(--cd-text)">{{ $ref['name'] ?? '' }}</strong>
                                            <p style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-accent);font-weight:500;margin:0.15rem 0 0.5rem">
                                                {{ $ref['designation'] ?? '' }} @if(!empty($ref['company'])) at {{ $ref['company'] }} @endif
                                            </p>
                                            <div style="display:flex;flex-direction:column;gap:3px">
                                                @if(!empty($ref['email']))
                                                    <span style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-text-muted)"><i class="ri-mail-line me-1"></i>{{ $ref['email'] }}</span>
                                                @endif
                                                @if(!empty($ref['mobile']))
                                                    <span style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-text-muted)"><i class="ri-phone-line me-1"></i>{{ $ref['mobile'] }}</span>
                                                @endif
                                                @if(!empty($ref['location']))
                                                    <span style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-text-muted)"><i class="ri-map-pin-line me-1"></i>{{ $ref['location'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="cv-empty">No references added.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Other Applicants --}}
            @if($relatedProfiles->isNotEmpty())
            <div class="cd-section mt-4">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-group-fill"></i> Other Applicants</span>
                    <a href="{{ route('applicants') }}" class="cd-section-link">Search All <i class="ri-arrow-right-s-line"></i></a>
                </div>
                <div class="grid grid-cols-12 gap-3">
                    @foreach($relatedProfiles->take(6) as $related)
                        <div class="col-span-12 md:col-span-6 xl:col-span-4">
                            <a href="{{ route('applicants.details', ['applicant' => $related->id]) }}" class="cv-related-card">
                                @if($related->user?->profile_photo_path)
                                    <img src="{{ $related->user->profile_photo_url }}" alt="" style="width:40px;height:40px;border-radius:10px;object-fit:cover;flex-shrink:0">
                                @else
                                    <span style="width:40px;height:40px;border-radius:10px;background:var(--cd-accent-light);color:var(--cd-accent);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:var(--cd-font-xs);flex-shrink:0">
                                        {{ strtoupper(substr($related->display_name ?? $related->user?->name ?? 'C', 0, 2)) }}
                                    </span>
                                @endif
                                <div style="min-width:0;flex:1">
                                    <div style="font-family:var(--cd-font);font-size:var(--cd-font-sm);font-weight:600;color:var(--cd-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $related->display_name ?? $related->user?->name }}</div>
                                    <div style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-text-muted)">{{ $related->title ?? '—' }}</div>
                                    @if($related->location)
                                        <div style="font-family:var(--cd-font);font-size:var(--cd-font-xs);color:var(--cd-text-muted)"><i class="ri-map-pin-line"></i> {{ $related->location }}</div>
                                    @endif
                                </div>
                                <i class="ri-arrow-right-s-line" style="color:var(--cd-accent);font-size:1.1rem;flex-shrink:0"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

    @auth
    {{-- Applicant rating: POST to rate, update display --}}
    <script>
        (function() {
            var container = document.querySelector('.applicant-rating-stars');
            if (!container) return;

            var applicantId = container.dataset.applicantId;
            var stars = container.querySelectorAll('.applicant-star-btn');
            var countEl = container.querySelector('.applicant-rating-count-value');
            var csrfToken = document.querySelector('meta[name="csrf-token"]');
            csrfToken = csrfToken ? csrfToken.getAttribute('content') : '';

            function updateStarDisplay(avgRating) {
                var r = parseFloat(avgRating) || 0;
                stars.forEach(function(span, i) {
                    var icon = span.querySelector('i');
                    var starNum = i + 1;
                    if (starNum <= Math.floor(r)) icon.className = 'bi bi-star-fill';
                    else if (starNum - 0.5 <= r) icon.className = 'bi bi-star-half';
                    else icon.className = 'bi bi-star';
                });
            }

            stars.forEach(function(star) {
                star.addEventListener('mouseenter', function() {
                    var index = parseInt(star.dataset.star, 10);
                    stars.forEach(function(s, i) {
                        var icon = s.querySelector('i');
                        icon.className = i < index ? 'bi bi-star-fill' : 'bi bi-star';
                    });
                });
            });

            container.addEventListener('mouseleave', function() {
                var currentRating = parseFloat(container.dataset.rating) || 0;
                updateStarDisplay(currentRating);
            });

            stars.forEach(function(star) {
                star.addEventListener('click', function() {
                    var rating = parseInt(star.dataset.star, 10);
                    fetch('/applicants/' + applicantId + '/rate', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ rating: rating })
                    })
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (data.success) {
                            container.dataset.rating = data.average_rating;
                            container.dataset.ratingCount = data.rating_count;
                            updateStarDisplay(data.average_rating);
                            if (countEl) countEl.textContent = data.rating_count;
                            if (window.Swal) {
                                window.Swal.fire({ icon: 'success', title: 'Rating submitted', text: 'You rated this candidate ' + rating + ' star' + (rating > 1 ? 's' : ''), timer: 1500, showConfirmButton: false });
                            }
                        }
                    })
                    .catch(function(err) { console.error('Applicant rating error:', err); });
                });
            });
        })();
    </script>
    @endauth

    @livewireScripts
</x-app-layout>
