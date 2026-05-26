<x-app-layout>
    <x-slot name="url_1">{"link": "/candidate/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="active">My Profile</x-slot>

    @include('candidates.partials.candidate-styles')

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Page Hero ═══ --}}
        <div class="col-span-12">
            <div class="cd-page-hero">
                <div>
                    <h1 class="cd-page-hero-title"><i class="ri-user-3-fill me-2"></i>My Profile</h1>
                    <p class="cd-page-hero-sub">Your professional profile visible to employers</p>
                </div>
            </div>
        </div>

        {{-- ═══ Sidebar: Profile Card ═══ --}}
        <div class="col-span-12 xl:col-span-3">
            <div class="cd-section" style="text-align:center">
                <div style="margin-bottom:1rem">
                    <span style="display:inline-flex;width:96px;height:96px;border-radius:50%;overflow:hidden;border:3px solid #eef2ff">
                        @if ($user->profile_photo_path)
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover">
                        @else
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->name) }}&backgroundColor=6366f1,8b5cf6,ec4899,f97316,10b981" alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover">
                        @endif
                    </span>
                </div>
                <h5 style="font-weight:700;font-size:1.1rem;margin-bottom:2px">{{ $profile->display_name ?? $user->name }}</h5>
                <p style="font-size:0.82rem;color:#6b7280;margin-bottom:1rem">{{ $profile->title ?? 'Title not set' }}</p>

                <a href="{{ route('candidate.profile.edit') }}" class="cd-btn cd-btn-primary" style="width:100%;justify-content:center;margin-bottom:1.25rem">
                    <i class="ri-edit-line me-1"></i> Edit Profile
                </a>

                <div style="text-align:left">
                    <div class="cd-info-row"><span class="cd-info-label">Location</span><span class="cd-info-value">{{ $profile->location ?? '-' }}</span></div>
                    <div class="cd-info-row"><span class="cd-info-label">Work Mode</span><span class="cd-info-value">{{ $profile->work_mode ?? '-' }}</span></div>
                    <div class="cd-info-row"><span class="cd-info-label">Experience</span><span class="cd-info-value">{{ $profile->years_experience ? $profile->years_experience . ' Years' : '-' }}</span></div>
                    <div class="cd-info-row"><span class="cd-info-label">Availability</span><span class="cd-info-value">{{ $profile->availability ?? '-' }}</span></div>
                    <div class="cd-info-row"><span class="cd-info-label">Job Type</span><span class="cd-info-value">{{ $profile->job_type ?? '-' }}</span></div>
                    <div class="cd-info-row">
                        <span class="cd-info-label">Expected Salary</span>
                        <span class="cd-info-value">
                            @if($profile->expected_salary_min || $profile->expected_salary_max)
                                {{ $profile->salary_currency }} {{ number_format($profile->expected_salary_min) }} - {{ number_format($profile->expected_salary_max) }}
                            @else - @endif
                        </span>
                    </div>
                </div>

                @if($profile->cv_path)
                    <div style="margin-top:1.25rem;text-align:left">
                        <div class="cd-section-head" style="margin-bottom:0.5rem"><span class="cd-section-label"><i class="ri-file-text-fill"></i> Resume / CV</span></div>
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:0.6rem 0.75rem;border:1px solid #f3f4f6;border-radius:8px">
                            <div style="display:flex;align-items:center;gap:8px">
                                <i class="ri-file-text-line" style="font-size:1.2rem;color:#4f46e5"></i>
                                <div><p style="font-size:0.82rem;font-weight:600;margin:0">Resume</p><p style="font-size:0.75rem;color:#9ca3af;margin:0">Uploaded</p></div>
                            </div>
                            <a href="{{ route('candidate.cv.view') }}" target="_blank" class="cd-btn cd-btn-outline cd-btn-sm"><i class="ri-eye-line"></i></a>
                        </div>
                    </div>
                @endif
            </div>

            @if($profile->social_links)
            <div class="cd-section" style="margin-top:1rem">
                <div class="cd-section-head"><span class="cd-section-label"><i class="ri-links-fill"></i> Social Links</span></div>
                <div class="flex gap-2">{{-- Social links logic --}}</div>
            </div>
            @endif
        </div>

        {{-- ═══ Main Content ═══ --}}
        <div class="col-span-12 xl:col-span-9 space-y-4">

            {{-- About Me --}}
            <div class="cd-section">
                <div class="cd-section-head"><span class="cd-section-label"><i class="ri-user-heart-fill"></i> About Me</span></div>
                <p style="font-size:0.88rem;color:#4b5563;line-height:1.7">{{ $profile->about ?? 'No information provided.' }}</p>
            </div>

            {{-- Career Objective --}}
            <div class="cd-section">
                <div class="cd-section-head"><span class="cd-section-label"><i class="ri-focus-3-fill"></i> Career Objective</span></div>
                <p style="font-size:0.88rem;color:#4b5563;line-height:1.7">{{ $profile->career_objective ?? 'No information provided.' }}</p>
            </div>

            {{-- Experience --}}
            <div class="cd-section">
                <div class="cd-section-head"><span class="cd-section-label"><i class="ri-briefcase-fill"></i> Experience</span></div>
                @php $experience = $profile->experience_overview ? json_decode($profile->experience_overview, true) : null; @endphp
                @if($experience && !empty($experience['position']))
                    <div style="padding-left:1.25rem;border-left:2px solid #e0e7ff;position:relative">
                        <div style="position:absolute;left:-7px;top:0;width:12px;height:12px;border-radius:50%;background:#4f46e5;border:3px solid #eef2ff"></div>
                        <h6 style="font-weight:700;font-size:0.95rem;margin-bottom:2px">{{ $experience['position'] }}</h6>
                        <div style="font-size:0.82rem;color:#4f46e5;font-weight:600">{{ $experience['company'] ?? '' }}</div>
                        <div style="font-size:0.75rem;color:#9ca3af;margin-bottom:0.5rem">{{ $experience['start_date'] ?? '' }} – {{ $experience['end_date'] ?? '' }} @if(!empty($experience['location'])) · <i class="ri-map-pin-line"></i> {{ $experience['location'] }} @endif</div>
                        @if(!empty($experience['responsibilities']))
                            <ul style="list-style:disc;padding-left:1.25rem;font-size:0.82rem;color:#4b5563">
                                @foreach($experience['responsibilities'] as $resp)
                                    @if(!empty($resp)) <li style="margin-bottom:0.25rem">{{ $resp }}</li> @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @else
                    <p style="color:#9ca3af;font-size:0.85rem">No experience added.</p>
                @endif
            </div>

            {{-- Education + Certifications --}}
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6">
                    <div class="cd-section" style="height:100%">
                        <div class="cd-section-head"><span class="cd-section-label"><i class="ri-graduation-cap-fill"></i> Education</span></div>
                        @php $education = $profile->education_details ? json_decode($profile->education_details, true) : []; @endphp
                        @if(count($education) > 0)
                            <div class="space-y-3">
                            @foreach($education as $edu)
                                <div style="padding-left:0.75rem;border-left:2px solid #c7d2fe">
                                    <h6 style="font-weight:600;font-size:0.85rem">{{ $edu['school'] ?? '' }}</h6>
                                    <p style="font-size:0.78rem;color:#4f46e5;margin:0">{{ $edu['course'] ?? '' }}</p>
                                    <p style="font-size:0.75rem;color:#9ca3af;margin:0">{{ $edu['dates'] ?? '' }}</p>
                                    @if(!empty($edu['location'])) <p style="font-size:0.75rem;color:#9ca3af;margin:0"><i class="ri-map-pin-line me-1"></i>{{ $edu['location'] }}</p> @endif
                                </div>
                            @endforeach
                            </div>
                        @else
                            <p style="color:#9ca3af;font-size:0.85rem">No education details added.</p>
                        @endif
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6">
                    <div class="cd-section" style="height:100%">
                        <div class="cd-section-head"><span class="cd-section-label"><i class="ri-medal-fill"></i> Certifications</span></div>
                        @php $certifications = $profile->certifications ? json_decode($profile->certifications, true) : []; @endphp
                        @if(count($certifications) > 0)
                            <ul class="space-y-2" style="list-style:none;padding:0;margin:0">
                            @foreach($certifications as $cert)
                                <li style="display:flex;align-items:flex-start;gap:8px">
                                    <i class="ri-medal-line" style="color:#ca8a04;margin-top:2px"></i>
                                    <div>
                                        <p style="font-size:0.85rem;font-weight:600;margin:0">{{ $cert['title'] ?? '' }}</p>
                                        <p style="font-size:0.75rem;color:#9ca3af;margin:0">{{ $cert['provider'] ?? '' }}</p>
                                    </div>
                                </li>
                            @endforeach
                            </ul>
                        @else
                            <p style="color:#9ca3af;font-size:0.85rem">No certifications added.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Skills & Tools --}}
            <div class="cd-section">
                <div class="cd-section-head"><span class="cd-section-label"><i class="ri-tools-fill"></i> Skills & Tools</span></div>
                @php
                    $skills = $profile->skills ? json_decode($profile->skills, true) : [];
                    $tools = $profile->tools_used ? json_decode($profile->tools_used, true) : [];
                @endphp
                <div style="margin-bottom:1rem">
                    <h6 style="font-weight:600;font-size:0.82rem;margin-bottom:0.5rem">Skills</h6>
                    <div class="flex flex-wrap gap-2">
                        @if(count($skills) > 0)
                            @foreach($skills as $skill)
                                @if(!empty($skill)) <span style="font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:20px;background:#eef2ff;color:#4f46e5">{{ $skill }}</span> @endif
                            @endforeach
                        @else <span style="color:#9ca3af;font-size:0.85rem">-</span> @endif
                    </div>
                </div>
                <div>
                    <h6 style="font-weight:600;font-size:0.82rem;margin-bottom:0.5rem">Tools Used</h6>
                    <div class="flex flex-wrap gap-2">
                        @if(count($tools) > 0)
                            @foreach($tools as $tool)
                                @if(!empty($tool)) <span style="font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:20px;background:#f0fdf4;color:#16a34a">{{ $tool }}</span> @endif
                            @endforeach
                        @else <span style="color:#9ca3af;font-size:0.85rem">-</span> @endif
                    </div>
                </div>
            </div>

            {{-- Languages + Achievements --}}
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6">
                    <div class="cd-section" style="height:100%">
                        <div class="cd-section-head"><span class="cd-section-label"><i class="ri-translate-2"></i> Languages</span></div>
                        @php $languages = $profile->languages ? json_decode($profile->languages, true) : []; @endphp
                        @if(count($languages) > 0)
                            <ul style="list-style:disc;padding-left:1.25rem;margin:0">
                                @foreach($languages as $lang)
                                    @if(!empty($lang)) <li style="font-size:0.85rem;color:#4b5563;margin-bottom:0.25rem">{{ $lang }}</li> @endif
                                @endforeach
                            </ul>
                        @else <p style="color:#9ca3af;font-size:0.85rem">No languages added.</p> @endif
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6">
                    <div class="cd-section" style="height:100%">
                        <div class="cd-section-head"><span class="cd-section-label"><i class="ri-trophy-fill"></i> Key Achievements</span></div>
                        @php $achievements = $profile->key_achievements ? json_decode($profile->key_achievements, true) : []; @endphp
                        @if(count($achievements) > 0)
                            <ul style="list-style:disc;padding-left:1.25rem;margin:0">
                                @foreach($achievements as $ach)
                                    @if(!empty($ach)) <li style="font-size:0.85rem;color:#4b5563;margin-bottom:0.25rem">{{ $ach }}</li> @endif
                                @endforeach
                            </ul>
                        @else <p style="color:#9ca3af;font-size:0.85rem">No achievements added.</p> @endif
                    </div>
                </div>
            </div>

            {{-- References --}}
            <div class="cd-section">
                <div class="cd-section-head"><span class="cd-section-label"><i class="ri-contacts-fill"></i> References</span></div>
                @php $references = $profile->references_block ? json_decode($profile->references_block, true) : []; @endphp
                @if(count($references) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($references as $ref)
                        <div style="padding:0.75rem;border:1px solid #f3f4f6;border-radius:10px;background:#f9fafb">
                            <h6 style="font-weight:700;font-size:0.88rem;margin-bottom:2px">{{ $ref['name'] ?? '' }}</h6>
                            <p style="font-size:0.78rem;color:#4f46e5;margin-bottom:0.35rem">{{ $ref['designation'] ?? '' }} @if(!empty($ref['company'])) at {{ $ref['company'] }} @endif</p>
                            @if(!empty($ref['email'])) <p style="font-size:0.75rem;color:#9ca3af;margin:0"><i class="ri-mail-line me-1"></i>{{ $ref['email'] }}</p> @endif
                            @if(!empty($ref['mobile'])) <p style="font-size:0.75rem;color:#9ca3af;margin:0"><i class="ri-phone-line me-1"></i>{{ $ref['mobile'] }}</p> @endif
                            @if(!empty($ref['location'])) <p style="font-size:0.75rem;color:#9ca3af;margin:0"><i class="ri-map-pin-line me-1"></i>{{ $ref['location'] }}</p> @endif
                        </div>
                    @endforeach
                    </div>
                @else
                    <p style="color:#9ca3af;font-size:0.85rem">No references added.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
