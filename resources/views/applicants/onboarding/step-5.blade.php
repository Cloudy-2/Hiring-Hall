@extends('applicants.onboarding.layout')

@section('step-content')
    <div class="onboard-card">
        <div style="text-align:center; margin-bottom: 28px;">
            <div
                style="display:inline-flex; align-items:center; justify-content:center; width:72px; height:72px; background:linear-gradient(135deg,rgba(229,161,0,0.12),rgba(229,161,0,0.04)); border-radius:22px; margin-bottom:14px; animation: launchPulse 2s ease-in-out infinite;">
                <i class="ri-rocket-2-fill" style="font-size:34px; color:var(--gold);"></i>
            </div>
            <h2>You're All Set! 🎉</h2>
            <p class="subtitle">Review your profile below, then launch it to start getting discovered.</p>
        </div>

        <style>
            @keyframes launchPulse {

                0%,
                100% {
                    transform: scale(1);
                    box-shadow: 0 0 0 0 rgba(229, 161, 0, 0.1);
                }

                50% {
                    transform: scale(1.04);
                    box-shadow: 0 0 0 12px rgba(229, 161, 0, 0);
                }
            }

            .edit-link {
                font-size: 12px;
                color: var(--gold);
                text-decoration: none;
                font-weight: 600;
                opacity: 0;
                transition: all 0.2s;
                margin-left: auto;
            }

            .review-section:hover .edit-link {
                opacity: 1;
            }

            .edit-link:hover {
                text-decoration: underline;
            }

            .profile-completeness {
                background: linear-gradient(135deg, #f8fafc, #f1f5f9);
                border: 1px solid #e8ecf1;
                border-radius: 14px;
                padding: 18px 22px;
                margin-bottom: 24px;
                text-align: center;
            }

            .progress-bar-outer {
                width: 100%;
                height: 8px;
                background: #e2e8f0;
                border-radius: 4px;
                margin-top: 10px;
                overflow: hidden;
            }

            .progress-bar-fill {
                height: 100%;
                background: linear-gradient(90deg, var(--gold), var(--gold-light));
                border-radius: 4px;
                transition: width 1.2s cubic-bezier(0.25, 0.8, 0.25, 1);
                animation: progressGlow 2s ease-in-out infinite;
            }

            @keyframes progressGlow {

                0%,
                100% {
                    box-shadow: 0 0 4px rgba(229, 161, 0, 0.2);
                }

                50% {
                    box-shadow: 0 0 10px rgba(229, 161, 0, 0.4);
                }
            }
        </style>

        {{-- Profile Completeness --}}
        @php
            $fields = 0;
            $filled = 0;
            $checks = ['display_name', 'title', 'location', 'work_mode', 'headline', 'about', 'skills', 'tools_used', 'languages', 'expertise_categories', 'years_experience', 'degree', 'availability', 'job_type', 'cv_path'];
            foreach ($checks as $f) {
                $fields++;
                $val = $profile->$f;
                if (!empty($val) && $val !== '[]' && $val !== 'null')
                    $filled++;
            }
            $pct = $fields > 0 ? round(($filled / $fields) * 100) : 0;
        @endphp
        <div class="profile-completeness">
            <div style="font-size:14px;font-weight:700;color:var(--navy);">
                Profile Completeness: <span style="color:var(--gold);">{{ $pct }}%</span>
            </div>
            <div class="progress-bar-outer">
                <div class="progress-bar-fill" style="width: {{ $pct }}%;"></div>
            </div>
        </div>

        {{-- Basic Info --}}
        <div class="review-section">
            <h3>
                <i class="ri-user-star-line"></i> Basic Info
                <a href="{{ route('applicant.onboarding.show', ['step' => 1]) }}" class="edit-link"><i
                        class="ri-pencil-line"></i> Edit</a>
            </h3>
            <div class="review-row">
                <span class="label">Display Name</span>
                <span class="value">{{ $profile->display_name ?? '—' }}</span>
            </div>
            <div class="review-row">
                <span class="label">Job Title</span>
                <span class="value">{{ $profile->job_title ?? $profile->title ?? '—' }}</span>
            </div>
            <div class="review-row">
                <span class="label">Location</span>
                <span class="value">{{ $profile->location ?? '—' }}</span>
            </div>
            <div class="review-row">
                <span class="label">Work Mode</span>
                <span class="value">{{ $profile->work_mode ?? '—' }}</span>
            </div>
            <div class="review-row">
                <span class="label">Headline</span>
                <span class="value">{{ $profile->headline ?? '—' }}</span>
            </div>
        </div>

        {{-- Skills --}}
        <div class="review-section">
            <h3>
                <i class="ri-tools-line"></i> Skills & Expertise
                <a href="{{ route('applicant.onboarding.show', ['step' => 2]) }}" class="edit-link"><i
                        class="ri-pencil-line"></i> Edit</a>
            </h3>
            @php
                $expertise = $profile->expertise_categories ? json_decode($profile->expertise_categories, true) : [];
                $skills = $profile->skills ? json_decode($profile->skills, true) : [];
                $tools = $profile->tools_used ? json_decode($profile->tools_used, true) : [];
                $languages = $profile->languages ? json_decode($profile->languages, true) : [];
            @endphp

            @if(!empty($expertise))
                <div
                    style="margin-bottom:6px; font-size:12px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">
                    Expertise</div>
                <div class="review-tags">
                    @foreach($expertise as $cat)
                        <span class="review-tag">{{ $cat }}</span>
                    @endforeach
                </div>
            @endif

            @if(!empty($skills))
                <div
                    style="margin-top:14px; margin-bottom:6px; font-size:12px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">
                    Skills</div>
                <div class="review-tags">
                    @foreach($skills as $skill)
                        <span class="review-tag">{{ $skill }}</span>
                    @endforeach
                </div>
            @endif

            @if(!empty($tools))
                <div
                    style="margin-top:14px; margin-bottom:6px; font-size:12px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">
                    Tools</div>
                <div class="review-tags">
                    @foreach($tools as $tool)
                        <span class="review-tag">{{ $tool }}</span>
                    @endforeach
                </div>
            @endif

            @if(!empty($languages))
                <div
                    style="margin-top:14px; margin-bottom:6px; font-size:12px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.4px;">
                    Languages</div>
                <div class="review-tags">
                    @foreach($languages as $lang)
                        <span class="review-tag">{{ $lang }}</span>
                    @endforeach
                </div>
            @endif

            @if(empty($expertise) && empty($skills) && empty($tools) && empty($languages))
                <div style="font-size:13px; color:#94a3b8; font-style:italic;">No skills added yet.</div>
            @endif
        </div>

        {{-- Experience --}}
        <div class="review-section">
            <h3>
                <i class="ri-briefcase-4-line"></i> Experience
                <a href="{{ route('applicant.onboarding.show', ['step' => 3]) }}" class="edit-link"><i
                        class="ri-pencil-line"></i> Edit</a>
            </h3>
            <div class="review-row">
                <span class="label">Years of Experience</span>
                <span class="value">{{ $profile->years_experience !== null ? $profile->years_experience . ' years' : '—' }}</span>
            </div>
            <div class="review-row">
                <span class="label">Degree</span>
                <span class="value">{{ $profile->degree ?? '—' }}</span>
            </div>
            <div class="review-row">
                <span class="label">Availability</span>
                <span class="value">{{ $profile->availability ?? '—' }}</span>
            </div>
            <div class="review-row">
                <span class="label">Job Type</span>
                <span class="value">{{ $profile->job_type ?? '—' }}</span>
            </div>
            @if($profile->expected_salary_min || $profile->expected_salary_max)
                <div class="review-row">
                    <span class="label">Expected Salary</span>
                    <span class="value">{{ $profile->salary_currency ?? 'USD' }}
                        {{ number_format($profile->expected_salary_min ?? 0) }} –
                        {{ number_format($profile->expected_salary_max ?? 0) }}</span>
                </div>
            @endif
            <div class="review-row">
                <span class="label">Resume</span>
                <span class="value">
                    @if($profile->cv_path)
                        <span style="color:#059669;font-weight:600;"><i class="ri-file-check-line"></i> Uploaded</span>
                    @else
                        <span style="color:#94a3b8;">Not uploaded</span>
                    @endif
                </span>
            </div>
        </div>

        {{-- Career & Details --}}
        <div class="review-section">
            <h3>
                <i class="ri-award-line"></i> Career & Details
                <a href="{{ route('applicant.onboarding.show', ['step' => 4]) }}" class="edit-link"><i
                        class="ri-pencil-line"></i> Edit</a>
            </h3>
            @if($profile->career_objective)
                <div class="review-row">
                    <span class="label">Career Objective</span>
                    <span class="value">{{ Str::limit($profile->career_objective, 80) }}</span>
                </div>
            @endif

            @php
                $education = $profile->education_details ? json_decode($profile->education_details, true) : [];
                $certs = $profile->certifications ? json_decode($profile->certifications, true) : [];
                $achievements = $profile->key_achievements ? json_decode($profile->key_achievements, true) : [];
                $socials = $profile->social_links ? (is_array($profile->social_links) ? $profile->social_links : json_decode($profile->social_links, true)) : [];
            @endphp

            <div class="review-row">
                <span class="label">Education</span>
                <span class="value">{{ count($education) > 0 ? count($education) . ' entries' : '—' }}</span>
            </div>
            <div class="review-row">
                <span class="label">Certifications</span>
                <span class="value">{{ count($certs) > 0 ? count($certs) . ' entries' : '—' }}</span>
            </div>
            <div class="review-row">
                <span class="label">Achievements</span>
                <span class="value">{{ count($achievements) > 0 ? count($achievements) . ' entries' : '—' }}</span>
            </div>
            <div class="review-row">
                <span class="label">Social Links</span>
                <span class="value">{{ count(array_filter($socials)) > 0 ? count(array_filter($socials)) . ' links' : '—' }}</span>
            </div>
        </div>

        {{-- Launch action --}}
        <form id="applicant-onboarding-finish-form" method="POST" action="{{ route('applicant.onboarding.store', ['step' => 5]) }}">
            @csrf
            <input type="hidden" name="terms_agreed" value="">
            <div class="onboard-actions" style="border-top:none;margin-top:20px;padding-top:0;">
                <a href="{{ route('applicant.onboarding.show', ['step' => 4]) }}" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Back
                </a>
                <button type="button" class="btn-next btn-launch" onclick="openOnboardingAgreementModal()">
                    <i class="ri-rocket-2-line"></i> Launch My Profile
                </button>
            </div>
        </form>

        @include('partials.agreement-modal-onboarding', [
            'modalId' => 'applicant-onboarding-agreement-modal',
            'formId' => 'applicant-onboarding-finish-form',
            'acceptButtonText' => 'I Agree & Launch My Profile',
        ])

        <div style="text-align:center; margin-top:16px; font-size:12px; color:#94a3b8;">
            <i class="ri-edit-2-line"></i> You can always update your profile later from the dashboard.
        </div>
    </div>
@endsection
