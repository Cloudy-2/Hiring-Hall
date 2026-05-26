<x-app-layout>
    <x-slot name="url_1">{"link": "/candidate/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">My Profile</x-slot>

    @include('candidates.partials.candidate-styles')

    {{-- ═══════════════ PROFILE STYLES ═══════════════ --}}
    <style>
        :root { --pf-primary: #1e293b; --pf-accent: #4f46e5; --pf-muted: #64748b; --pf-border: #e2e8f0; --pf-bg: #f8fafc; }

        .pf-card {
            background: #fff; border-radius: 12px;
            border: 1px solid var(--pf-border);
            overflow: hidden;
        }
        .pf-card-body { padding: 1.25rem; }

        /* ── Sidebar ── */
        .pf-sidebar-body { padding: 1.5rem 1.25rem 1.25rem; text-align: center; }
        .pf-avatar-wrap { position: relative; display: inline-block; margin-bottom: 0.85rem; }
        .pf-avatar {
            width: 100px; height: 100px; border-radius: 50%;
            overflow: hidden; border: 3px solid #fff;
            box-shadow: 0 2px 12px rgba(0,0,0,.1);
        }
        .pf-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .pf-online-dot {
            position: absolute; bottom: 6px; left: 6px;
            width: 14px; height: 14px; border-radius: 50%;
            background: #22c55e; border: 3px solid #fff;
        }
        .pf-name { font-size: 1.125rem; font-weight: 800; color: var(--pf-primary); margin-bottom: 2px; }
        .pf-role { font-size: 0.8125rem; color: var(--pf-muted); margin-bottom: 2px; }
        .pf-location { font-size: 0.78rem; color: var(--pf-accent); display: flex; align-items: center; justify-content: center; gap: 4px; margin-bottom: 1rem; }

        .pf-info-table { width: 100%; border-collapse: collapse; }
        .pf-info-table td {
            padding: 0.5rem 0; font-size: 0.8rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .pf-info-table tr:last-child td { border-bottom: none; }
        .pf-info-label { color: var(--pf-muted); font-weight: 500; text-align: left; }
        .pf-info-value { color: var(--pf-primary); font-weight: 700; text-align: right; }
        .pf-info-value .pf-badge-green {
            background: #dcfce7; color: #16a34a; padding: 2px 8px;
            border-radius: 4px; font-size: 0.72rem; font-weight: 700;
        }

        .pf-btn-primary {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%; padding: 0.55rem; border-radius: 8px;
            background: var(--pf-primary); color: #fff;
            border: none; font-weight: 700; font-size: 0.8125rem;
            cursor: pointer; transition: all 0.2s; text-decoration: none;
            margin-bottom: 0.4rem;
        }
        .pf-btn-primary:hover { background: #334155; color: #fff; }
        .pf-btn-secondary {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            width: 100%; padding: 0.55rem; border-radius: 8px;
            background: #fff; color: var(--pf-primary);
            border: 1px solid var(--pf-border); font-weight: 600; font-size: 0.8125rem;
            cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .pf-btn-secondary:hover { background: var(--pf-bg); color: var(--pf-primary); }

        .pf-social-label {
            font-size: 0.7rem; font-weight: 700; color: var(--pf-muted);
            text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 0.6rem; padding-bottom: 0.4rem;
            border-bottom: 1px solid var(--pf-border);
            text-align: left;
        }
        .pf-social-link {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.8125rem; color: var(--pf-primary);
            text-decoration: none; padding: 0.35rem 0;
            transition: color 0.2s;
        }
        .pf-social-link:hover { color: var(--pf-accent); }
        .pf-social-link i { color: var(--pf-muted); font-size: 0.9rem; width: 18px; text-align: center; }

        /* ── Section Headers ── */
        .pf-sh {
            display: flex; align-items: center; gap: 8px;
            font-size: 1rem; font-weight: 800; color: var(--pf-primary);
            margin-bottom: 0.85rem;
        }
        .pf-sh i { font-size: 1.1rem; color: var(--pf-muted); }

        .pf-text { font-size: 0.8125rem; color: var(--pf-muted); line-height: 1.7; }
        .pf-empty { text-align: center; padding: 1rem 0; color: #94a3b8; font-size: 0.8125rem; font-style: italic; }

        /* ── Experience Timeline ── */
        .pf-exp-timeline { position: relative; }
        .pf-exp-center-line {
            position: absolute; left: 50%; top: 0; bottom: 0;
            width: 2px; background: var(--pf-border);
            transform: translateX(-50%);
        }
        .pf-exp-item {
            display: flex; align-items: flex-start;
            position: relative; margin-bottom: 2rem;
        }
        .pf-exp-item:last-child { margin-bottom: 0; }
        .pf-exp-dot {
            position: absolute; left: 50%; top: 8px;
            width: 12px; height: 12px; border-radius: 50%;
            background: var(--pf-accent); border: 3px solid #e0e7ff;
            transform: translateX(-50%); z-index: 2;
        }
        .pf-exp-left { width: 47%; padding-right: 2rem; text-align: right; }
        .pf-exp-right { width: 47%; padding-left: 2rem; margin-left: auto; }
        .pf-exp-item.left .pf-exp-content { }
        .pf-exp-item.right .pf-exp-content { }

        .pf-exp-role { font-weight: 800; font-size: 0.875rem; color: var(--pf-primary); }
        .pf-exp-date {
            display: inline-block; background: var(--pf-bg);
            border: 1px solid var(--pf-border); border-radius: 4px;
            padding: 2px 8px; font-size: 0.72rem; font-weight: 700;
            color: var(--pf-muted); margin-bottom: 4px;
        }
        .pf-exp-company { font-size: 0.8125rem; color: var(--pf-accent); font-weight: 600; margin-bottom: 4px; }
        .pf-exp-resp {
            list-style: disc; padding-left: 1rem; margin-top: 0.4rem;
            font-size: 0.78rem; color: var(--pf-muted); line-height: 1.6;
        }
        .pf-exp-resp li { margin-bottom: 3px; }

        /* Responsive: stack timeline on mobile */
        @media (max-width: 768px) {
            .pf-exp-center-line { left: 8px; }
            .pf-exp-dot { left: 8px; }
            .pf-exp-left, .pf-exp-right {
                width: 100%; padding-left: 2rem; padding-right: 0;
                text-align: left; margin-left: 0;
            }
        }

        /* ── Education ── */
        .pf-edu-item {
            display: flex; align-items: flex-start; gap: 0.6rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .pf-edu-item:last-child { border-bottom: none; }
        .pf-edu-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--pf-primary); margin-top: 6px; flex-shrink: 0;
        }
        .pf-edu-title { font-weight: 700; font-size: 0.8125rem; color: var(--pf-primary); }
        .pf-edu-sub { font-size: 0.75rem; color: var(--pf-muted); }

        /* ── Skills Pills ── */
        .pf-pill {
            display: inline-block; padding: 0.3rem 0.7rem;
            border: 1px solid var(--pf-border); border-radius: 6px;
            font-size: 0.78rem; font-weight: 600; color: var(--pf-primary);
            background: #fff; transition: all 0.2s;
        }
        .pf-pill:hover { background: var(--pf-bg); border-color: var(--pf-accent); color: var(--pf-accent); }

        /* ── Languages ── */
        .pf-lang-row {
            display: flex; align-items: center; gap: 0.75rem;
            margin-bottom: 0.6rem;
        }
        .pf-lang-name { font-size: 0.8125rem; font-weight: 700; color: var(--pf-primary); min-width: 70px; }
        .pf-lang-bar-bg {
            flex: 1; height: 6px; background: #e2e8f0;
            border-radius: 3px; overflow: hidden;
        }
        .pf-lang-bar {
            height: 100%; background: var(--pf-primary); border-radius: 3px;
            transition: width 0.5s ease;
        }
        .pf-lang-level { font-size: 0.72rem; color: var(--pf-muted); font-weight: 600; min-width: 90px; text-align: right; }

        /* ── Achievements ── */
        .pf-ach-item {
            display: flex; align-items: flex-start; gap: 0.5rem;
            padding: 0.4rem 0; font-size: 0.8125rem; color: var(--pf-muted); line-height: 1.5;
        }
        .pf-ach-item i { color: #f59e0b; margin-top: 3px; font-size: 0.85rem; flex-shrink: 0; }

        /* ── Certifications ── */
        .pf-cert-item {
            display: flex; align-items: flex-start; gap: 0.6rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .pf-cert-item:last-child { border-bottom: none; }
        .pf-cert-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #f59e0b; margin-top: 6px; flex-shrink: 0;
        }
        .pf-cert-title { font-weight: 700; font-size: 0.8125rem; color: var(--pf-primary); }
        .pf-cert-sub { font-size: 0.75rem; color: var(--pf-muted); }

        /* ── References ── */
        .pf-ref-card {
            padding: 0.85rem; border-radius: 8px;
            border: 1px solid var(--pf-border); transition: all 0.2s;
        }
        .pf-ref-card:hover { border-color: #c7d2fe; background: #fafaff; }
        .pf-ref-name { font-weight: 800; font-size: 0.8125rem; color: var(--pf-primary); }
        .pf-ref-title { font-size: 0.75rem; color: var(--pf-accent); font-weight: 600; margin-bottom: 6px; }
        .pf-ref-meta {
            display: flex; align-items: center; gap: 4px;
            font-size: 0.75rem; color: var(--pf-muted); padding: 1px 0;
        }
        .pf-ref-meta i { font-size: 0.8rem; color: var(--pf-muted); width: 14px; }
    </style>

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
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->name) }}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981" alt="{{ $user->name }}">
                            @endif
                        </div>
                        <div class="pf-online-dot"></div>
                    </div>

                    <h2 class="pf-name">{{ $profile->display_name ?? $user->name }}</h2>
                    <p class="pf-role">{{ $profile->job_title ?? $profile->title ?? 'Job title not set' }}</p>
                    <p class="pf-location"><i class="ri-map-pin-2-fill"></i> {{ $profile->location ?? 'Location not set' }}</p>

                    {{-- Info Table --}}
                    <table class="pf-info-table">
                        <tr>
                            <td class="pf-info-label">Work Mode</td>
                            <td class="pf-info-value">{{ $profile->work_mode ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="pf-info-label">Experience</td>
                            <td class="pf-info-value">{{ $profile->years_experience ? $profile->years_experience . ' Years' : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="pf-info-label">Availability</td>
                            <td class="pf-info-value">
                                @if($profile->availability)
                                    <span class="pf-badge-green">{{ $profile->availability }}</span>
                                @else - @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="pf-info-label">Job Type</td>
                            <td class="pf-info-value">{{ $profile->job_type ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="pf-info-label">Expected Salary</td>
                            <td class="pf-info-value">
                                @if($profile->expected_salary_min || $profile->expected_salary_max)
                                    {{ $profile->salary_currency }} {{ number_format($profile->expected_salary_min) }} - {{ number_format($profile->expected_salary_max) }}
                                @else - @endif
                            </td>
                        </tr>
                    </table>

                    {{-- Buttons --}}
                    <div style="margin-top:1rem">
                        <a href="{{ route('candidate.profile.edit') }}" class="pf-btn-primary">
                            <i class="ri-edit-line"></i> Edit Profile
                        </a>
                        @if($profile->cv_path)
                        <a href="{{ route('candidate.cv.view') }}" target="_blank" class="pf-btn-secondary">
                            <i class="ri-download-2-line"></i> Download Resume
                        </a>
                        @else
                        <button type="button" class="pf-btn-secondary" onclick="Swal.fire({icon:'info',title:'No Resume Uploaded',text:'You haven\'t uploaded a resume yet. Go to Edit Profile to upload one.',confirmButtonColor:'#4f46e5'})">
                            <i class="ri-file-text-line"></i> View Resume
                        </button>
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
                <button type="button" onclick="startWalkthrough()" class="cd-hero-btn cd-tour-btn" style="width:100%;justify-content:center;border-radius:10px">
                    <i class="ri-rocket-2-fill"></i> Take a Tour
                </button>
            </div>
        </div>

        {{-- ═══════════════ MAIN CONTENT ═══════════════ --}}
        <div class="col-span-12 xl:col-span-9">

            {{-- ── About Me & Career Objective (side by side) ── --}}
            <div class="grid grid-cols-12 gap-4" style="margin-bottom:1rem" id="wt-about">
                <div class="col-span-12 md:col-span-6">
                    <div class="pf-card" style="height:100%">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-user-heart-line"></i> About Me</div>
                            <p class="pf-text">{{ $profile->about ?? 'No information provided.' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6">
                    <div class="pf-card" style="height:100%">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-focus-3-line"></i> Career Objective</div>
                            <p class="pf-text">{{ $profile->career_objective ?? 'No information provided.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

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
                                <div class="pf-exp-right" style="display:flex;align-items:flex-start;justify-content:flex-start">
                                    <div>
                                        <span class="pf-exp-date">{{ $experience['start_date'] ?? '' }} - {{ $experience['end_date'] ?? 'Present' }}</span>
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

            {{-- ── Education & Certs + Skills & Tools (side by side) ── --}}
            <div class="grid grid-cols-12 gap-4" style="margin-bottom:1rem" id="wt-edu-certs">
                {{-- Education & Certs --}}
                <div class="col-span-12 md:col-span-6">
                    <div class="pf-card" style="height:100%">
                        <div class="pf-card-body">
                            <div class="pf-sh"><i class="ri-graduation-cap-line"></i> Education & Certs</div>

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
                                            <div class="pf-edu-sub">{{ $edu['school'] ?? '' }}@if(!empty($edu['dates'])) • {{ $edu['dates'] }}@endif</div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="pf-empty" style="padding:0.5rem 0">No education details added.</div>
                            @endif

                            {{-- Certifications --}}
                            @if(count($certifications) > 0)
                                <div style="margin-top:0.75rem;padding-top:0.5rem;border-top:1px solid var(--pf-border)">
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
                <div class="col-span-12 md:col-span-6" id="wt-skills">
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

            {{-- ── Key Achievements & Languages (side by side) ── --}}
            <div class="grid grid-cols-12 gap-4" style="margin-bottom:1rem">
                {{-- Key Achievements --}}
                <div class="col-span-12 md:col-span-6">
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
                <div class="col-span-12 md:col-span-6">
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

            {{-- ── References ── --}}
            <div class="pf-card" id="wt-references">
                <div class="pf-card-body">
                    <div class="pf-sh" style="justify-content:center"><i class="ri-contacts-book-line"></i> References</div>
                    @php
                        $references = $profile->references_block ? json_decode($profile->references_block, true) : [];
                    @endphp
                    @if(count($references) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($references as $ref)
                            <div class="pf-ref-card">
                                <div class="pf-ref-name">{{ $ref['name'] ?? '' }}</div>
                                <div class="pf-ref-title">{{ $ref['designation'] ?? '' }}@if(!empty($ref['company'])) at {{ $ref['company'] }}@endif</div>
                                @if(!empty($ref['email']))
                                    <div class="pf-ref-meta"><i class="ri-mail-line"></i> {{ $ref['email'] }}</div>
                                @endif
                                @if(!empty($ref['mobile']))
                                    <div class="pf-ref-meta"><i class="ri-phone-line"></i> {{ $ref['mobile'] }}</div>
                                @endif
                                @if(!empty($ref['location']))
                                    <div class="pf-ref-meta"><i class="ri-map-pin-line"></i> {{ $ref['location'] }}</div>
                                @endif
                            </div>
                        @endforeach
                        </div>
                    @else
                        <div class="pf-empty">No references added.</div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    @include('candidates.partials.walkthrough', [
        'wtKey' => 'profile',
        'wtSteps' => [
            ['target' => 'wt-sidebar', 'icon' => 'ri-user-3-fill', 'title' => 'Your Profile Card', 'body' => 'This is your public-facing profile summary. It shows your photo, title, location, experience, salary expectations, and resume. Click "Edit Profile" to update any details.', 'position' => 'bottom'],
            ['target' => 'wt-about', 'icon' => 'ri-file-text-fill', 'title' => 'About & Career Objective', 'body' => 'Your personal summary and career goals. A well-written bio significantly increases recruiter interest — make sure to fill this in!', 'position' => 'bottom'],
            ['target' => 'wt-experience', 'icon' => 'ri-briefcase-fill', 'title' => 'Work Experience', 'body' => 'Showcase your professional journey. Add your positions, companies, dates, and key responsibilities to help employers understand your background.', 'position' => 'bottom'],
            ['target' => 'wt-edu-certs', 'icon' => 'ri-graduation-cap-fill', 'title' => 'Education & Certifications', 'body' => 'Your educational background and professional certifications. These help verify your qualifications and stand out to employers.', 'position' => 'top'],
            ['target' => 'wt-skills', 'icon' => 'ri-tools-fill', 'title' => 'Skills & Tools', 'body' => 'List your technical skills and tools you\'re proficient with. Employers search by skills, so keep this section comprehensive and up to date.', 'position' => 'top'],
            ['target' => 'wt-references', 'icon' => 'ri-contacts-fill', 'title' => 'References', 'body' => 'Professional references who can vouch for your work. Adding references builds trust and can speed up the hiring process.', 'position' => 'top'],
        ]
    ])

</x-app-layout>
