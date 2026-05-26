@extends('applicants.onboarding.layout')

@section('step-content')
    <div class="onboard-card">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:6px;">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;background:linear-gradient(135deg,rgba(229,161,0,0.1),rgba(229,161,0,0.05));border-radius:14px;flex-shrink:0;">
                <i class="ri-award-fill" style="font-size:24px;color:var(--gold);"></i>
            </div>
            <div>
                <h2>Career & Details</h2>
                <p class="subtitle" style="margin-bottom:0;">Add your career goals, achievements, education, and references to complete your profile.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('applicant.onboarding.store', ['step' => 4]) }}">
            @csrf

            {{-- Career Objective --}}
            <div class="section-divider"><i class="ri-focus-3-line"></i> Career Objective</div>
            <div class="form-group">
                <textarea name="career_objective" rows="3" placeholder="I aim to leverage my 3+ years of experience in digital marketing to secure a senior role where I can drive brand growth and lead cross-functional campaigns.">{{ old('career_objective', $profile->career_objective) }}</textarea>
                <div class="hint"><i class="ri-lightbulb-line"></i> Help employers understand what drives you</div>
            </div>

            {{-- Education --}}
            <div class="section-divider"><i class="ri-graduation-cap-line"></i> Education</div>
            <div class="form-group">
                @php
                    $education = old('education', $profile->education_details ? json_decode($profile->education_details, true) : []);
                    if (!is_array($education) || empty($education)) $education = [['course' => '', 'school' => '', 'start_year' => '', 'end_year' => '']];
                @endphp
                <div id="education-container">
                    @foreach($education as $idx => $edu)
                        <div class="edu-entry" data-index="{{ $idx }}" style="padding:12px;border:1.5px solid #e2e8f0;border-radius:12px;margin-bottom:10px;background:#fafbfe;">
                            <div class="form-row" style="margin-bottom:8px;">
                                <div class="form-group" style="margin-bottom:0;">
                                    <input type="text" name="education[{{ $idx }}][course]" value="{{ $edu['course'] ?? '' }}" placeholder="BS Information Technology">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <input type="text" name="education[{{ $idx }}][school]" value="{{ $edu['school'] ?? '' }}" placeholder="University of the Philippines">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group" style="margin-bottom:0;">
                                    <select name="education[{{ $idx }}][start_year]">
                                        <option value="">Start Year</option>
                                        @for($y = date('Y'); $y >= 1970; $y--)
                                            <option value="{{ $y }}" {{ ($edu['start_year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group" style="margin-bottom:0;display:flex;gap:8px;align-items:center;">
                                    <select name="education[{{ $idx }}][end_year]" style="flex:1;">
                                        <option value="">End Year</option>
                                        <option value="Present" {{ ($edu['end_year'] ?? '') == 'Present' ? 'selected' : '' }}>Present</option>
                                        @for($y = date('Y'); $y >= 1970; $y--)
                                            <option value="{{ $y }}" {{ ($edu['end_year'] ?? '') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                    @if($idx > 0)
                                        <span class="remove-tag" onclick="this.closest('.edu-entry').remove()" title="Remove" style="cursor:pointer;color:#ef4444;font-size:18px;"><i class="ri-delete-bin-line"></i></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="add-tag-btn" onclick="addEducation()">
                    <i class="ri-add-line"></i> Add education
                </button>
            </div>

            {{-- Certifications --}}
            <div class="section-divider"><i class="ri-medal-line"></i> Certifications</div>
            <div class="form-group">
                @php
                    $certs = old('certifications', $profile->certifications ? json_decode($profile->certifications, true) : []);
                    if (!is_array($certs) || empty($certs)) $certs = [['title' => '', 'provider' => '']];
                @endphp
                <div id="certs-container">
                    @foreach($certs as $idx => $cert)
                        <div class="cert-entry" data-index="{{ $idx }}" style="display:flex;gap:8px;margin-bottom:8px;align-items:center;">
                            <input type="text" name="certifications[{{ $idx }}][title]" value="{{ $cert['title'] ?? '' }}" placeholder="Google Analytics Certified" style="flex:1;">
                            <input type="text" name="certifications[{{ $idx }}][provider]" value="{{ $cert['provider'] ?? '' }}" placeholder="Google" style="flex:1;">
                            @if($idx > 0)
                                <span class="remove-tag" onclick="this.closest('.cert-entry').remove()" title="Remove" style="cursor:pointer;color:#ef4444;font-size:18px;"><i class="ri-delete-bin-line"></i></span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <button type="button" class="add-tag-btn" onclick="addCert()">
                    <i class="ri-add-line"></i> Add certification
                </button>
            </div>

            {{-- Key Achievements --}}
            <div class="section-divider"><i class="ri-trophy-line"></i> Key Achievements</div>
            <div class="form-group">
                @php
                    $achievements = old('key_achievements', $profile->key_achievements ? json_decode($profile->key_achievements, true) : []);
                    if (!is_array($achievements) || empty($achievements)) $achievements = [''];
                @endphp
                <div id="achievements-container" class="tag-list">
                    @foreach($achievements as $idx => $ach)
                        <div class="tag-item" data-index="{{ $idx }}">
                            <input type="text" name="key_achievements[{{ $idx }}]" value="{{ $ach }}" placeholder="Reduced customer churn by 25% through improved onboarding">
                            <span class="remove-tag" onclick="removeTag(this, 'achievements-container')" title="Remove"><i class="ri-close-line"></i></span>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="add-tag-btn" onclick="addTag('achievements-container', 'key_achievements', 'Managed a $50K project budget successfully')">
                    <i class="ri-add-line"></i> Add achievement
                </button>
            </div>

            {{-- Social Links --}}
            <div class="section-divider"><i class="ri-links-line"></i> Portfolio & Social Links</div>
            <div class="form-group">
                @php
                    $socials = old('social_links', $profile->social_links ? (is_array($profile->social_links) ? $profile->social_links : json_decode($profile->social_links, true)) : []);
                    if (!is_array($socials)) $socials = [];
                @endphp
                <div class="form-row" style="margin-bottom:8px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label><i class="ri-linkedin-fill" style="color:#0077b5;"></i> LinkedIn</label>
                        <input type="url" name="social_links[linkedin]" value="{{ $socials['linkedin'] ?? '' }}" placeholder="https://linkedin.com/in/juan-delacruz">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label><i class="ri-github-fill"></i> GitHub</label>
                        <input type="url" name="social_links[github]" value="{{ $socials['github'] ?? '' }}" placeholder="https://github.com/juandev">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="margin-bottom:0;">
                        <label><i class="ri-global-line" style="color:var(--gold);"></i> Portfolio / Website</label>
                        <input type="url" name="social_links[portfolio]" value="{{ $socials['portfolio'] ?? '' }}" placeholder="https://juandelacruz.dev">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label><i class="ri-twitter-x-fill"></i> Twitter / X</label>
                        <input type="url" name="social_links[twitter]" value="{{ $socials['twitter'] ?? '' }}" placeholder="https://x.com/juandev">
                    </div>
                </div>
            </div>

            <div class="onboard-actions">
                <a href="{{ route('applicant.onboarding.show', ['step' => 3]) }}" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Back
                </a>
                <button type="submit" class="btn-next">
                    Continue <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        </form>
    </div>

    <script>
        function addTag(containerId, fieldName, placeholder) {
            const container = document.getElementById(containerId);
            const items = container.querySelectorAll('.tag-item');
            const idx = items.length > 0 ? Math.max(...Array.from(items).map(el => parseInt(el.dataset.index || 0))) + 1 : 0;
            const tag = document.createElement('div');
            tag.className = 'tag-item';
            tag.dataset.index = idx;
            tag.innerHTML = `<input type="text" name="${fieldName}[${idx}]" value="" placeholder="${placeholder}"><span class="remove-tag" onclick="removeTag(this, '${containerId}')" title="Remove"><i class="ri-close-line"></i></span>`;
            container.appendChild(tag);
            tag.querySelector('input').focus();
        }

        function removeTag(el, containerId) {
            const container = document.getElementById(containerId);
            const item = el.closest('.tag-item');
            if (container.querySelectorAll('.tag-item').length > 1) {
                item.style.transition = 'all 0.25s ease';
                item.style.opacity = '0';
                item.style.transform = 'scale(0.8)';
                setTimeout(() => item.remove(), 250);
            }
        }

        function addEducation() {
            const container = document.getElementById('education-container');
            const idx = container.querySelectorAll('.edu-entry').length;
            const currentYear = new Date().getFullYear();
            let yearOptions = '<option value="">Start Year</option>';
            let endYearOptions = '<option value="">End Year</option><option value="Present">Present</option>';
            for (let y = currentYear; y >= 1970; y--) {
                yearOptions += `<option value="${y}">${y}</option>`;
                endYearOptions += `<option value="${y}">${y}</option>`;
            }
            const div = document.createElement('div');
            div.className = 'edu-entry';
            div.dataset.index = idx;
            div.style.cssText = 'padding:12px;border:1.5px solid #e2e8f0;border-radius:12px;margin-bottom:10px;background:#fafbfe;';
            div.innerHTML = `
                <div class="form-row" style="margin-bottom:8px;">
                    <div class="form-group" style="margin-bottom:0;"><input type="text" name="education[${idx}][course]" placeholder="BS Information Technology"></div>
                    <div class="form-group" style="margin-bottom:0;"><input type="text" name="education[${idx}][school]" placeholder="University of the Philippines"></div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="margin-bottom:0;"><select name="education[${idx}][start_year]">${yearOptions}</select></div>
                    <div class="form-group" style="margin-bottom:0;display:flex;gap:8px;align-items:center;">
                        <select name="education[${idx}][end_year]" style="flex:1;">${endYearOptions}</select>
                        <span class="remove-tag" onclick="this.closest('.edu-entry').remove()" title="Remove" style="cursor:pointer;color:#ef4444;font-size:18px;"><i class="ri-delete-bin-line"></i></span>
                    </div>
                </div>`;
            container.appendChild(div);
        }

        function addCert() {
            const container = document.getElementById('certs-container');
            const idx = container.querySelectorAll('.cert-entry').length;
            const div = document.createElement('div');
            div.className = 'cert-entry';
            div.dataset.index = idx;
            div.style.cssText = 'display:flex;gap:8px;margin-bottom:8px;align-items:center;';
            div.innerHTML = `
                <input type="text" name="certifications[${idx}][title]" placeholder="AWS Cloud Practitioner" style="flex:1;">
                <input type="text" name="certifications[${idx}][provider]" placeholder="Amazon Web Services" style="flex:1;">
                <span class="remove-tag" onclick="this.closest('.cert-entry').remove()" title="Remove" style="cursor:pointer;color:#ef4444;font-size:18px;"><i class="ri-delete-bin-line"></i></span>`;
            container.appendChild(div);
        }
    </script>
@endsection