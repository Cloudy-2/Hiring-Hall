{{--
    Profile Edit Modal — full-width right slide-over drawer
    Requires: $profile, $dropdownOptions  (passed from the parent view)
    Open:  window.openProfileEditModal()
    Close: window.closeProfileEditModal()
--}}

{{-- ── Backdrop ── --}}
<div id="pem-backdrop"
    onclick="closeProfileEditModal()"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(2px);z-index:1040;transition:opacity .25s"></div>

{{-- ── Drawer ── --}}
<div id="pem-drawer"
    style="display:none;position:fixed;top:0;right:0;width:min(680px,100vw);height:100vh;
            background:#fff;z-index:1050;
            box-shadow:-8px 0 40px rgba(0,0,0,.15);
            display:flex;flex-direction:column;
            transform:translateX(100%);transition:transform .3s cubic-bezier(.4,0,.2,1)">

    {{-- Header --}}
    <div id="pem-header">
        <div>
            <div id="pem-step-title">
                <i class="ri-edit-box-line"></i>
                <span>Edit Profile — Step 1 of 3</span>
            </div>
            <div id="pem-step-desc">Basic info, summary and Resume</div>
        </div>
        <button type="button" onclick="closeProfileEditModal()">
            <i class="ri-close-line"></i>
        </button>
    </div>

    {{-- Step progress --}}
    <div id="pem-progress-container">
        <div class="pem-progress-inner">
            <div id="pem-progress-track">
                <div id="pem-progress-bar"></div>
            </div>
            <div class="pem-tabs-row">
                @foreach(['Overview','Profile Info','Experience'] as $si => $sl)
                <button type="button" class="pem-tab pem-tab-{{ $si + 1 }}" onclick="pemGoToStep({{ $si + 1 }})">
                    <span class="pem-circle pem-circle-{{ $si + 1 }}">{{ $si + 1 }}</span>
                    <span class="pem-tab-label pem-tab-label-{{ $si + 1 }}">{{ $sl }}</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Scrollable body --}}
    <div id="pem-body" style="flex:1;overflow-y:auto;padding:1.25rem 1.5rem">

        <form id="pem-form"
            method="POST"
            action="{{ route('applicant.profile.update') }}"
            enctype="multipart/form-data"
            novalidate>
            @csrf
            <input type="hidden" name="_redirect" value="applicant.profile.show">

            {{-- ══════════════ STEP 1: Overview ══════════════ --}}
            <div class="pem-step pem-step-1">

                {{-- Basic Info --}}
                <div class="pem-section">
                    <div class="pem-section-title"><i class="ri-user-smile-line text-primary"></i> Basic Information</div>
                    <div class="grid grid-cols-12 gap-x-4 gap-y-4 mb-4">
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Display Name <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="ri-user-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                <input type="text" name="display_name" class="form-control ps-10"
                                    value="{{ old('display_name', $profile->display_name) }}" required placeholder="e.g. John Doe">
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Job Title <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="ri-briefcase-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                <input type="text" name="job_title" class="form-control ps-10"
                                    list="pem_job_title_list"
                                    value="{{ old('job_title', $profile->job_title ?? $profile->title) }}"
                                    required placeholder="Select or type your job title">
                            </div>
                            <datalist id="pem_job_title_list">
                                <option value="Executive Virtual Assistant">
                                <option value="Virtual Assistant">
                                <option value="Administrative Assistant">
                                <option value="Executive Assistant">
                                <option value="Personal Assistant">
                                <option value="Office Administrator">
                                <option value="Data Entry Specialist">
                                <option value="Customer Service Representative">
                                <option value="Social Media Manager">
                                <option value="Bookkeeper">
                                <option value="Project Coordinator">
                                <option value="HR Assistant">
                                <option value="Research Assistant">
                                <option value="Content Writer">
                            </datalist>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-x-4 gap-y-4">
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="ri-map-pin-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                <input type="text" name="location" class="form-control ps-10"
                                    value="{{ old('location', $profile->location) }}" required placeholder="e.g. Manila, Philippines">
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Work Mode <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="ri-home-4-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                <select name="work_mode" class="form-control ps-10" required>
                                    @php $workMode = old('work_mode', $profile->work_mode); @endphp
                                    <option value="" @if(!$workMode) selected @endif disabled>Select work mode</option>
                                    @foreach(($dropdownOptions['workModes'] ?? []) as $value => $label)
                                    <option value="{{ $value }}" @if($workMode===$value) selected @endif>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Qualifications --}}
                <div class="pem-section">
                    <div class="pem-section-title"><i class="ri-graduation-cap-line text-primary"></i> Qualifications &amp; Preferences</div>
                    <div class="grid grid-cols-12 gap-x-4 gap-y-4 mb-4">
                        <div class="col-span-12 md:col-span-8">
                            <label class="form-label">Degree / Qualification <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="ri-book-read-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                <input type="text" name="degree" class="form-control ps-10"
                                    value="{{ old('degree', $profile->degree) }}" required placeholder="e.g. BS in Information Systems">
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="form-label">Years of Exp. <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="ri-time-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                <input type="number" name="years_experience" class="form-control ps-10" min="0"
                                    value="{{ old('years_experience', $profile->years_experience) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-x-4 gap-y-4 mb-4">
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Availability <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="ri-checkbox-circle-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                <select name="availability" class="form-control ps-10" required>
                                    @php $availability = old('availability', $profile->availability); @endphp
                                    <option value="" @if(!$availability) selected @endif disabled>Select availability</option>
                                    @foreach(($dropdownOptions['availabilities'] ?? []) as $value => $label)
                                    <option value="{{ $value }}" @if($availability===$value) selected @endif>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Job Type <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="ri-briefcase-4-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                <select name="job_type" class="form-control ps-10" required>
                                    @php $jobType = old('job_type', $profile->job_type); @endphp
                                    <option value="" @if(!$jobType) selected @endif disabled>Select job type</option>
                                    @foreach(($dropdownOptions['jobTypes'] ?? []) as $value => $label)
                                    <option value="{{ $value }}" @if($jobType===$value) selected @endif>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-span-12">
                        <label class="form-label">Expected Salary <span class="text-danger">*</span></label>
                        <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center">
                            <div class="w-full sm:w-24">
                                <select name="salary_currency" class="form-control" required>
                                    @php $currencyValue = old('salary_currency', $profile->salary_currency ?? 'USD'); @endphp
                                    @foreach(($dropdownOptions['currencies'] ?? []) as $value => $label)
                                    <option value="{{ $value }}" @if($currencyValue===$value) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative flex-1 min-w-[100px]">
                                <input type="number" step="0.01" name="expected_salary_min" class="peer form-control ps-11"
                                    placeholder="Minimum" value="{{ old('expected_salary_min', $profile->expected_salary_min) }}" required>
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] uppercase font-bold text-textmuted/40 pointer-events-none transition-opacity duration-200 peer-placeholder-shown:opacity-100 opacity-0">Min</span>
                            </div>
                            <span class="hidden sm:block text-[#94a3b8] font-bold">—</span>
                            <div class="relative flex-1 min-w-[100px]">
                                <input type="number" step="0.01" name="expected_salary_max" class="peer form-control ps-11"
                                    placeholder="Maximum" value="{{ old('expected_salary_max', $profile->expected_salary_max) }}" required>
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] uppercase font-bold text-textmuted/40 pointer-events-none transition-opacity duration-200 peer-placeholder-shown:opacity-100 opacity-0">Max</span>
                            </div>
                        </div>
                    </div> -->
                </div>

                {{-- Summary --}}
                <div class="pem-section">
                    <div class="pem-section-title"><i class="ri-quote-text text-primary"></i> Summary</div>
                    <div class="mb-4">
                        <label class="form-label">Headline <span class="text-danger">*</span></label>
                        <div class="relative">
                            <i class="ri-text-spacing absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                            <input type="text" name="headline" class="form-control ps-10"
                                value="{{ old('headline', $profile->headline) }}" required placeholder="e.g. Experienced Executive Assistant">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">About Me <span class="text-danger">*</span></label>
                        <textarea name="about" class="form-control" rows="4"
                            placeholder="Tell recruiters about your background, strengths, and career goals…" required>{{ old('about', $profile->about) }}</textarea>
                    </div>
                </div>

                {{-- Resume --}}
                <div class="pem-section" style="margin-bottom:0">
                    <div class="pem-section-title"><i class="ri-file-upload-line"></i> Profile Resume</div>
                    <div class="pem-resume-upload-wrapper">
                        <div class="pem-resume-left">
                            <input type="file" name="cv_file" id="pem_cv_file" class="hidden" accept=".pdf,.doc,.docx">
                            <button type="button" id="pem_cv_trigger" class="pem-cv-dropzone @if($profile->cv_path) has-file @endif">
                                <i class="@if($profile->cv_path) ri-checkbox-circle-fill text-success @else ri-file-add-line @endif" id="pem_cv_icon"></i>
                                <span id="pem_cv_text">
                                    @if($profile->cv_path) 
                                        Resume currently uploaded 
                                    @else 
                                        Click to upload new resume 
                                    @endif
                                </span>
                                <p id="pem_cv_subtext">PDF, DOC, DOCX — Max 5 MB</p>
                                <p id="pem_cv_name" style="@if($profile->cv_path) display: block; @else display: none; @endif">
                                    @if($profile->cv_path)
                                        ✓ {{ basename($profile->cv_path) }}
                                    @endif
                                </p>
                            </button>
                        </div>
                        @if($profile->cv_path)
                        <div class="pem-resume-actions">
                            <span class="pem-status-badge success">
                                <i class="ri-file-text-line"></i> Uploaded
                            </span>
                            <a href="{{ route('applicant.cv.view') }}" target="_blank" class="pem-action-link">
                                <i class="ri-eye-line"></i> View
                            </a>
                            <button type="button" class="pem-action-link danger" onclick="pemConfirmRemoveCV()">
                                <i class="ri-delete-bin-line"></i> Remove
                            </button>
                        </div>
                        @endif
                    </div>
                    <input type="hidden" name="remove_cv" id="pem_remove_cv" value="0">
                </div>

            </div>{{-- /step 1 --}}

            {{-- ══════════════ STEP 2: Profile Info ══════════════ --}}
            <div class="pem-step pem-step-2" style="display:none">

                @php
                $currentYear = date('Y');
                $yearRange = range($currentYear + 5, 1970);

                $pemEducation = $profile->education_details ? json_decode($profile->education_details, true) : [];
                if (!is_array($pemEducation) || !count($pemEducation)) {
                $pemEducation = [['course' => '', 'school' => '', 'location' => '', 'dates' => '']];
                }
                if (old('education')) { $pemEducation = old('education'); }

                $pemCerts = $profile->certifications ? json_decode($profile->certifications, true) : [];
                if (!is_array($pemCerts) || !count($pemCerts)) {
                $pemCerts = [['title' => '', 'provider' => '']];
                }
                if (old('certifications')) { $pemCerts = old('certifications'); }

                $pemAchievements = $profile->key_achievements ? json_decode($profile->key_achievements, true) : [];
                if (!is_array($pemAchievements) || !count($pemAchievements)) { $pemAchievements = ['']; }
                if (old('achievements')) { $pemAchievements = old('achievements'); }

                $pemActivities = $profile->activities_interests ? json_decode($profile->activities_interests, true) : [];
                if (!is_array($pemActivities) || !count($pemActivities)) { $pemActivities = ['']; }
                if (old('activities')) { $pemActivities = old('activities'); }

                $pemSkills = $profile->skills ? json_decode($profile->skills, true) : [];
                if (!is_array($pemSkills) || !count($pemSkills)) { $pemSkills = ['']; }
                if (old('skills')) { $pemSkills = old('skills'); }

                $pemTools = $profile->tools_used ? json_decode($profile->tools_used, true) : [];
                if (!is_array($pemTools) || !count($pemTools)) { $pemTools = ['']; }
                if (old('tools_used')) { $pemTools = old('tools_used'); }

                $pemLanguages = $profile->languages ? json_decode($profile->languages, true) : [];
                if (!is_array($pemLanguages) || !count($pemLanguages)) { $pemLanguages = ['']; }
                if (old('languages')) { $pemLanguages = old('languages'); }

                $pemExpertise = $profile->expertise_categories ? json_decode($profile->expertise_categories, true) : [];
                if (!is_array($pemExpertise)) { $pemExpertise = []; }
                if (old('expertise_categories')) { $pemExpertise = old('expertise_categories'); }

                $pemRefs = $profile->references_block ? json_decode($profile->references_block, true) : [];
                if (!is_array($pemRefs) || !count($pemRefs)) {
                $pemRefs = [['name' => '', 'designation' => '', 'company' => '', 'mobile' => '', 'email' => '', 'location' => '']];
                }
                if (old('references')) { $pemRefs = old('references'); }
                @endphp

                {{-- Career Objective --}}
                <div class="pem-section">
                    <div class="pem-section-title"><i class="ri-target-line text-primary"></i> Career Objective</div>
                    <textarea name="career_objective" class="form-control" rows="4"
                        placeholder="What are your career goals?">{{ old('career_objective', $profile->career_objective) }}</textarea>
                </div>

                {{-- Education --}}
                <div class="pem-section">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem">
                        <div class="pem-section-title" style="margin-bottom:0"><i class="ri-graduation-cap-line text-primary"></i> Education</div>
                        <button type="button" class="pem-btn-add" id="pem-add-education">
                            <i class="ri-add-line"></i> Add Entry
                        </button>
                    </div>
                    <div id="pem-education-list" class="space-y-4">
                        @foreach($pemEducation as $idx => $edu)
                        <div class="pem-edu-row" data-index="{{ $idx }}">
                            @if($idx > 0)
                            <button type="button" class="pem-row-remove pem-remove-edu">
                                <i class="ri-close-line"></i>
                                <span>Remove</span>
                            </button>
                            @endif
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label text-xs">Course &amp; Major</label>
                                    <div class="relative">
                                        <i class="ri-book-2-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                        <input type="text" name="education[{{ $idx }}][course]" class="form-control ps-8 h-9! text-sm" value="{{ $edu['course'] ?? '' }}" placeholder="e.g. BS Computer Science">
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label text-xs">School / University</label>
                                    <div class="relative">
                                        <i class="ri-community-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                        <input type="text" name="education[{{ $idx }}][school]" class="form-control ps-8 h-9! text-sm" value="{{ $edu['school'] ?? '' }}" placeholder="e.g. Harvard University">
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label text-xs">Address</label>
                                    <div class="relative">
                                        <i class="ri-map-pin-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                        <input type="text" name="education[{{ $idx }}][location]" class="form-control ps-8 h-9! text-sm" value="{{ $edu['location'] ?? '' }}" placeholder="e.g. Cambridge, MA">
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label text-xs">Education Period</label>
                                    <div class="flex gap-2">
                                        <div class="relative flex-1">
                                            <i class="ri-calendar-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                            <select name="education[{{ $idx }}][start_year]" class="form-control ps-8 h-9! text-sm">
                                                <option value="">From</option>
                                                @foreach($yearRange as $y)
                                                    <option value="{{ $y }}" @if(($edu['start_year'] ?? '') == $y) selected @endif>{{ $y }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="relative flex-1">
                                            <i class="ri-calendar-check-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                            <select name="education[{{ $idx }}][end_year]" class="form-control ps-8 h-9! text-sm">
                                                <option value="">To</option>
                                                @foreach($yearRange as $y)
                                                    <option value="{{ $y }}" @if(($edu['end_year'] ?? '') == $y) selected @endif>{{ $y }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Expertise / Categories --}}
                <div class="pem-section">
                    <div class="pem-section-title"><i class="ri-folder-settings-line text-primary"></i> Expertise / Categories</div>
                    <div class="grid grid-cols-12 gap-2">
                        @php $selectedExpertise = is_array($pemExpertise) ? $pemExpertise : []; @endphp
                        @foreach(($dropdownOptions['expertiseCategories'] ?? []) as $value => $label)
                        <div class="col-span-12 sm:col-span-6">
                            <label class="form-check inline-flex items-center gap-2 text-sm cursor-pointer hover:bg-primary/5 p-1 rounded transition-colors w-full">
                                <input class="form-check-input" type="checkbox"
                                    name="expertise_categories[]" value="{{ $value }}"
                                    @if(in_array($value, $selectedExpertise)) checked @endif>
                                <span class="text-xs font-medium">{{ $label }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Skills --}}
                <div class="pem-section">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
                        <div class="pem-section-title" style="margin-bottom:0"><i class="ri-lightbulb-line text-primary"></i> Skills</div>
                        <button type="button" class="pem-btn-add" id="pem-add-skill">
                            <i class="ri-add-line"></i> Add Skill
                        </button>
                    </div>
                    <p style="font-size:.7rem;color:#94a3b8;margin-bottom:.75rem">e.g., Schedule Management, Client Communication, CRM Tools</p>
                    <div id="pem-skills-list" class="space-y-2">
                        @foreach($pemSkills as $idx => $line)
                        <div class="flex gap-2 items-center pem-skill-row" data-index="{{ $idx }}">
                            <div class="relative flex-1">
                                <i class="ri-checkbox-circle-fill absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-primary"></i>
                                <input type="text" name="skills[{{ $idx }}]" class="form-control ps-8 h-9! text-sm" value="{{ $line }}" placeholder="Skill name" required>
                            </div>
                            <button type="button" class="pem-remove-skill inline-flex items-center justify-center h-8 w-8 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Tools --}}
                <div class="pem-section">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
                        <div class="pem-section-title" style="margin-bottom:0"><i class="ri-tools-line text-primary"></i> Tools Used</div>
                        <button type="button" class="pem-btn-add" id="pem-add-tool">
                            <i class="ri-add-line"></i> Add Tool
                        </button>
                    </div>
                    <p style="font-size:.7rem;color:#94a3b8;margin-bottom:.75rem">e.g., Slack, Trello, QuickBooks, GoHighLevel</p>
                    <div id="pem-tools-list" class="space-y-2">
                        @foreach($pemTools as $idx => $line)
                        <div class="flex gap-2 items-center pem-tool-row" data-index="{{ $idx }}">
                            <div class="relative flex-1">
                                <i class="ri-settings-4-fill absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-primary"></i>
                                <input type="text" name="tools_used[{{ $idx }}]" class="form-control ps-8 h-9! text-sm" value="{{ $line }}" placeholder="Tool / Software" required>
                            </div>
                            <button type="button" class="pem-remove-tool inline-flex items-center justify-center h-8 w-8 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Languages --}}
                <div class="pem-section">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
                        <div class="pem-section-title" style="margin-bottom:0"><i class="ri-global-line"></i> Languages</div>
                        <button type="button" class="pem-btn-add" id="pem-add-language">
                            <i class="ri-add-line"></i> Add Language
                        </button>
                    </div>
                    <div id="pem-languages-list" class="space-y-2">
                        @foreach($pemLanguages as $idx => $line)
                        <div class="flex gap-2 items-end pem-lang-row" data-index="{{ $idx }}">
                            <input type="text" name="languages[{{ $idx }}]" class="form-control" value="{{ $line }}" placeholder="e.g. English — Fluent">
                            <button type="button" class="pem-remove-lang inline-flex items-center justify-center h-9 w-9 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Certifications --}}
                <div class="pem-section">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem">
                        <div class="pem-section-title" style="margin-bottom:0"><i class="ri-award-line"></i> Certifications</div>
                        <button type="button" class="pem-btn-add" id="pem-add-cert">
                            <i class="ri-add-line"></i> Add Certification
                        </button>
                    </div>
                    <div id="pem-certs-list" class="space-y-3">
                        @foreach($pemCerts as $idx => $cert)
                        <div class="pem-cert-row" data-index="{{ $idx }}">
                            @if($idx > 0)
                            <button type="button" class="pem-row-remove pem-remove-cert">
                                <i class="ri-close-line"></i>
                                <span>Remove</span>
                            </button>
                            @endif
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label text-xs">Certificate / Course</label>
                                    <div class="relative">
                                        <i class="ri-medal-2-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                        <input type="text" name="certifications[{{ $idx }}][title]" class="form-control ps-8 h-9! text-sm" value="{{ $cert['title'] ?? '' }}" placeholder="e.g. Google Project Management">
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label text-xs">Provider / School</label>
                                    <div class="relative">
                                        <i class="ri-building-4-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                        <input type="text" name="certifications[{{ $idx }}][provider]" class="form-control ps-8 h-9! text-sm" value="{{ $cert['provider'] ?? '' }}" placeholder="e.g. Coursera">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Key Achievements --}}
                <div class="pem-section">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
                        <div class="pem-section-title" style="margin-bottom:0"><i class="ri-trophy-line"></i> Key Achievements</div>
                        <button type="button" class="pem-btn-add" id="pem-add-achievement">
                            <i class="ri-add-line"></i> Add Achievement
                        </button>
                    </div>
                    <div id="pem-achievements-list" class="space-y-2">
                        @foreach($pemAchievements as $idx => $line)
                        <div class="flex gap-2 items-end pem-ach-row" data-index="{{ $idx }}">
                            <input type="text" name="achievements[{{ $idx }}]" class="form-control" value="{{ $line }}" placeholder="Achievement">
                            <button type="button" class="pem-remove-ach inline-flex items-center justify-center h-9 w-9 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Activities --}}
                <div class="pem-section">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.5rem">
                        <div class="pem-section-title" style="margin-bottom:0"><i class="ri-heart-line"></i> Activities &amp; Interests</div>
                        <button type="button" class="pem-btn-add" id="pem-add-activity">
                            <i class="ri-add-line"></i> Add Activity
                        </button>
                    </div>
                    <div id="pem-activities-list" class="space-y-2">
                        @foreach($pemActivities as $idx => $line)
                        <div class="flex gap-2 items-end pem-act-row" data-index="{{ $idx }}">
                            <input type="text" name="activities[{{ $idx }}]" class="form-control" value="{{ $line }}" placeholder="Activity or interest">
                            <button type="button" class="pem-remove-act inline-flex items-center justify-center h-9 w-9 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- References --}}
                <div class="pem-section" style="margin-bottom:0">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem">
                        <div class="pem-section-title" style="margin-bottom:0"><i class="ri-user-shared-line"></i> References</div>
                        <button type="button" class="pem-btn-add" id="pem-add-ref">
                            <i class="ri-add-line"></i> Add Reference
                        </button>
                    </div>
                    <div id="pem-refs-list" class="space-y-4">
                        @foreach($pemRefs as $idx => $ref)
                        <div class="pem-ref-row" data-index="{{ $idx }}">
                            @if($idx > 0)
                            <button type="button" class="pem-row-remove pem-remove-ref">
                                <i class="ri-close-line"></i>
                                <span>Remove</span>
                            </button>
                            @endif
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label text-xs">Name</label>
                                    <input type="text" name="references[{{ $idx }}][name]" class="form-control" value="{{ $ref['name'] ?? '' }}" placeholder="Full Name">
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label text-xs">Designation</label>
                                    <input type="text" name="references[{{ $idx }}][designation]" class="form-control" value="{{ $ref['designation'] ?? '' }}" placeholder="Position / Job Title">
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label text-xs">Company Name</label>
                                    <input type="text" name="references[{{ $idx }}][company]" class="form-control" value="{{ $ref['company'] ?? '' }}" placeholder="Company">
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label text-xs">Mobile</label>
                                    <input type="text" name="references[{{ $idx }}][mobile]" class="form-control" value="{{ $ref['mobile'] ?? '' }}" placeholder="Contact Number">
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label text-xs">Email</label>
                                    <input type="email" name="references[{{ $idx }}][email]" class="form-control" value="{{ $ref['email'] ?? '' }}" placeholder="Email Address">
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label text-xs">Location</label>
                                    <input type="text" name="references[{{ $idx }}][location]" class="form-control" value="{{ $ref['location'] ?? '' }}" placeholder="City, Country">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>{{-- /step 2 --}}

            {{-- ══════════════ STEP 3: Experience ══════════════ --}}
            <div class="pem-step pem-step-3" style="display:none">

                @php
                $pemExp = $profile->experience_overview ? json_decode($profile->experience_overview, true) : [];
                if (!is_array($pemExp)) { $pemExp = []; }
                $pemExpResp = $pemExp['responsibilities'] ?? [];
                if (!is_array($pemExpResp) || !count($pemExpResp)) { $pemExpResp = ['']; }
                $pemExpResp = old('experience_responsibilities', $pemExpResp);
                $pemExpStart = old('experience_start', $pemExp['start_date'] ?? '');
                $pemExpEnd = old('experience_end', $pemExp['end_date'] ?? '');
                if ($pemExpStart && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $pemExpStart)) { $pemExpStart = ''; }
                if ($pemExpEnd && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $pemExpEnd)) { $pemExpEnd = ''; }
                $pemExpCurrent = old('experience_current',
                    isset($pemExp['end_date']) && (empty($pemExp['end_date']) || strtolower((string)$pemExp['end_date']) === 'present')
                );
                @endphp

                <div class="pem-section" style="margin-bottom:0">
                    <div class="pem-section-title"><i class="ri-briefcase-4-line text-primary"></i> Experience Overview</div>

                    <div class="grid grid-cols-12 gap-x-4 gap-y-4 mb-4">
                        <div class="col-span-12">
                            <label class="form-label">Position / Title <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="ri-user-settings-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                <input type="text" name="experience_position" class="form-control ps-10"
                                    list="pem_position_list"
                                    value="{{ old('experience_position', $pemExp['position'] ?? '') }}"
                                    placeholder="Select or type your job title" required>
                            </div>
                            <datalist id="pem_position_list">
                                <option value="Executive Virtual Assistant">
                                <option value="Virtual Assistant">
                                <option value="Administrative Assistant">
                                <option value="Executive Assistant">
                                <option value="Personal Assistant">
                                <option value="Office Administrator">
                                <option value="Data Entry Specialist">
                                <option value="Customer Service Representative">
                                <option value="Social Media Manager">
                                <option value="Bookkeeper">
                                <option value="Project Coordinator">
                                <option value="HR Assistant">
                                <option value="Research Assistant">
                                <option value="Content Writer">
                            </datalist>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Company Name <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="ri-building-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                <input type="text" name="experience_company" class="form-control ps-10"
                                    value="{{ old('experience_company', $pemExp['company'] ?? '') }}" required placeholder="e.g. Acme Corp">
                            </div>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="form-label">Location <span class="text-danger">*</span></label>
                            <div class="relative">
                                <i class="ri-map-pin-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                <input type="text" name="experience_location" class="form-control ps-10"
                                    value="{{ old('experience_location', $pemExp['location'] ?? '') }}" required placeholder="e.g. Remote / City">
                            </div>
                        </div>
                        <div class="col-span-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="experience_start" id="pem_exp_start"
                                class="form-control" value="{{ $pemExpStart }}" max="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-span-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="experience_end" id="pem_exp_end"
                                class="form-control"
                                value="{{ $pemExpCurrent ? '' : $pemExpEnd }}"
                                max="{{ date('Y-m-d') }}"
                                {{ $pemExpCurrent ? 'disabled' : '' }}>
                        </div>
                        <div class="col-span-12">
                            <label class="pem-check-label" style="display:inline-flex;align-items:center;gap:6px;cursor:pointer;font-size:.8125rem;color:#64748b">
                                <input type="checkbox" name="experience_current" id="pem_exp_current"
                                    value="1" {{ $pemExpCurrent ? 'checked' : '' }}>
                                I currently work here
                            </label>
                        </div>
                    </div>

                    <div class="pt-2 border-top border-defaultborder/30 mt-3">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem">
                            <label class="form-label mb-0">Key Responsibilities <span class="text-danger">*</span></label>
                            <button type="button" class="ti-btn ti-btn-sm ti-btn-outline-primary" id="pem-add-resp">
                                <i class="ri-add-line me-1"></i> Add Bullet
                            </button>
                        </div>
                        <div id="pem-resp-list" class="space-y-2">
                            @foreach($pemExpResp as $idx => $resp)
                            <div class="flex gap-2 items-center pem-resp-row" data-index="{{ $idx }}">
                                <div class="relative flex-1">
                                    <i class="ri-checkbox-blank-circle-fill absolute left-3 top-1/2 -translate-y-1/2 text-[6px] text-primary"></i>
                                    <input type="text" name="experience_responsibilities[{{ $idx }}]"
                                        class="form-control ps-8" value="{{ $resp }}" placeholder="e.g. Managed team of 5 and improved efficiency by 20%" required>
                                </div>
                                <button type="button" class="pem-remove-resp inline-flex items-center justify-center h-9 w-9 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>{{-- /step 3 --}}

        </form>
    </div>{{-- /pem-body --}}

    {{-- Footer --}}
    <div id="pem-footer">
        <button type="button" id="pem-prev" class="ti-btn ti-btn-outline-primary" style="display:none">
            <i class="ri-arrow-left-line me-1"></i> Previous
        </button>
        <div style="flex-grow:1"></div>
        <button type="button" id="pem-next" class="ti-btn ti-btn-primary" style="display:none">
            Next Step <i class="ri-arrow-right-line ms-1"></i>
        </button>
        <button type="submit" form="pem-form" id="pem-submit" class="ti-btn ti-btn-primary" style="display:none">
            <i class="ri-check-line me-1"></i> Save Profile
        </button>
    </div>

</div>{{-- /pem-drawer --}}

{{-- Theme-aware overrides for Profile Edit Modal --}}
<style>
    /* Main Drawer Background */
    #pem-drawer {
        position: fixed;
        right: 0;
        top: 0;
        bottom: 0;
        width: 100%;
        max-width: 800px;
        background: #fff;
        z-index: 50;
        display: flex;
        flex-direction: column;
        box-shadow: -10px 0 50px rgba(0,0,0,0.1);
        border-left: 1px solid #e2e8f0;
        transform: translateX(100%);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    #pem-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.08) 0%, rgba(var(--primary-rgb), 0.03) 100%);
        border-bottom: 1px solid var(--cd-border);
        flex-shrink: 0;
    }
    #pem-step-title {
        font-size: 1.05rem;
        font-weight: 850;
        color: var(--cd-text);
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.01em;
    }
    #pem-step-title i { color: var(--cd-accent); font-size: 1.2rem; }
    #pem-step-desc { font-size: 0.8125rem; color: var(--cd-text-secondary); margin-top: 2px; font-weight: 500; }

    #pem-header button {
        width: 36px; height: 36px; border-radius: 50%; border: none;
        background: rgba(var(--cd-text-muted-rgb), 0.1); color: var(--cd-text-secondary);
        font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #pem-header button:hover { background: rgba(var(--cd-text-muted-rgb), 0.2); color: var(--cd-text); transform: rotate(90deg); }

    #pem-progress-container {
        padding: 1.25rem 1.5rem; flex-shrink: 0; display: flex; justify-content: center;
        border-bottom: 1.5px solid var(--cd-border); background: var(--cd-bg-stripe);
    }
    .pem-progress-inner { position: relative; width: 100%; max-width: 600px; }
    #pem-progress-track { position: absolute; left: 0; right: 0; top: 20px; height: 3px; background: var(--cd-border); border-radius: 4px; overflow: hidden; z-index: 0; }
    #pem-progress-bar { height: 100%; background: var(--cd-accent); border-radius: 4px; transition: width 0.4s ease; }
    .pem-tabs-row { position: relative; display: flex; justify-content: space-between; z-index: 1; }
    .pem-tab { display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1; background: none; border: none; cursor: pointer; padding: 4px 0; }
    .pem-circle {
        display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; 
        border: 2.5px solid var(--cd-border); background: #fff; color: var(--cd-text-muted);
        font-size: 0.9rem; font-weight: 800; position: relative; z-index: 2; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        outline: 4px solid #fff; /* Masks the progress line behind the circle */
    }
    .pem-tab-label { font-size: 0.725rem; font-weight: 600; color: var(--cd-text-muted); white-space: nowrap; transition: color 0.3s; }

    .pem-section {
        border: 1.5px solid var(--cd-border); border-radius: 14px; padding: 1.25rem; margin-bottom: 1.25rem;
        background: var(--cd-bg-card, #fff); transition: all 0.2s;
    }
    .pem-section-title {
        font-size: 0.85rem; font-weight: 800; color: var(--cd-text); margin-bottom: 1.15rem;
        display: flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: 0.04em;
    }
    .pem-section-title i { color: var(--cd-accent); font-size: 1.1rem; }

    /* Resume Area Refinement */
    .pem-resume-upload-wrapper { display: flex; flex-wrap: wrap; gap: 1.25rem; align-items: stretch; }
    .pem-resume-left { flex: 1; min-width: 240px; }
    .pem-cv-dropzone {
        width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;
        border-radius: 14px; border: 2px dashed var(--cd-border); padding: 1.5rem; text-align: center; cursor: pointer;
        background: var(--cd-bg-stripe); transition: all 0.2s;
    }
    .pem-cv-dropzone:hover { border-color: var(--cd-accent); background: rgba(var(--primary-rgb), 0.04); }
    .pem-cv-dropzone.has-file { border-color: #10b981; border-style: solid; background: rgba(16, 185, 129, 0.03); }
    .pem-cv-dropzone.has-file:hover { background: rgba(16, 185, 129, 0.08); }
    .pem-cv-dropzone i { font-size: 2rem; color: var(--cd-text-muted); margin-bottom: 0.5rem; }
    .pem-cv-dropzone.has-file i { color: #10b981; }
    .pem-cv-dropzone span { font-size: 0.9rem; font-weight: 700; color: var(--cd-text); margin-bottom: 4px; }
    .pem-cv-dropzone p { font-size: 0.75rem; color: var(--cd-text-muted); margin: 0; }
    #pem_cv_name { font-size: 0.75rem; font-weight: 800; color: var(--cd-accent); margin-top: 8px; display: none; }

    .pem-resume-actions { display: flex; flex-direction: column; gap: 10px; justify-content: center; min-width: 140px; }
    .pem-status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 800; background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .pem-action-link { 
        display: inline-flex; align-items: center; gap: 6px; padding: 8px 12px; border-radius: 8px; 
        font-size: 0.8125rem; font-weight: 700; color: var(--cd-text-secondary); background: var(--cd-bg-stripe);
        border: 1.5px solid var(--cd-border); text-decoration: none !important; transition: all 0.2s;
    }
    .pem-action-link:hover { color: var(--cd-accent); border-color: var(--cd-accent); background: #fff; }
    .pem-action-link.danger { color: #ef4444; }
    .pem-action-link.danger:hover { color: #fff; background: #ef4444; border-color: #ef4444; }

    /* Form Controls Theme Awareness — Scoped to Modal Only */
    #pem-drawer .form-label {
        color: var(--cd-text-secondary) !important;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        margin-bottom: 0.5rem;
        display: block;
    }

    #pem-drawer .form-control,
    #pem-drawer .form-select {
        background-color: var(--cd-bg-alt) !important;
        border: 1.5px solid var(--cd-border) !important;
        color: var(--cd-text) !important;
        border-radius: 10px !important;
        padding: 0.5rem 1rem;
        height: 42px !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        width: 100% !important;
        box-shadow: none !important;
    }

    /* Padding-left specifically for inputs with icons */
    #pem-drawer .relative i + .form-control,
    #pem-drawer .relative i + .form-select {
        padding-left: 2.75rem !important;
    }

    #pem-drawer .form-control::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    /* Standard style for select elements */
    #pem-drawer select.form-control,
    #pem-drawer .form-select {
        appearance: none !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 0.75rem center !important;
        background-size: 1rem !important;
        padding-right: 2.5rem !important;
        cursor: pointer !important;
    }

    /* Standard height for date/number inputs */
    #pem-drawer input[type="date"].form-control,
    #pem-drawer input[type="number"].form-control {
        height: 42px !important;
    }

    /* Handle textareas separately */
    #pem-drawer textarea.form-control {
        height: auto !important;
        min-height: 100px !important;
        line-height: 1.5 !important;
        padding-top: 0.75rem !important;
    }

    #pem-drawer .form-control:focus {
        background-color: #fff !important;
        border-color: var(--cd-accent) !important;
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.1) !important;
    }

    /* Absolute icon positioning within input groups — SCOPED TO MODAL ONLY */
    #pem-drawer .relative i {
        font-size: 1.1rem;
        transition: color 0.2s;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 100%;
        position: absolute;
        left: 2px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: var(--cd-text-muted);
    }
    #pem-drawer .form-control:focus + i,
    #pem-drawer .form-control:focus ~ i {
        color: var(--cd-accent) !important;
    }

    /* Dark Mode specific Variable adjustments if needed */
    html.dark #pem-drawer .form-control:focus {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }

    /* Progress Circles & Tabs */
    .pem-circle {
        background: var(--cd-bg-stripe) !important;
        border-color: var(--cd-border) !important;
        color: var(--cd-text-muted) !important;
        transition: all 0.3s ease;
    }

    .pem-circle[style*="background: rgb(79, 70, 229)"],
    .pem-circle[style*="background: #4f46e5"],
    .pem-circle.active {
        background: var(--circle-active) !important;
        border-color: var(--circle-active) !important;
        color: #fff !important;
        box-shadow: 0 0 15px rgba(var(--primary-rgb), 0.3);
    }

    .pem-tab-label {
        color: var(--cd-text-muted) !important;
        font-weight: 500 !important;
    }

    .pem-tab-label.active,
    .pem-tab-label[style*="font-weight: 700"] {
        color: var(--circle-active) !important;
        font-weight: 700 !important;
    }

    /* CV Trigger / Upload Area */
    #pem-cv_trigger {
        background: var(--cd-bg-alt) !important;
        border: 2px dashed var(--cd-border) !important;
        transition: all 0.2s ease;
    }

    #pem-cv_trigger:hover {
        border-color: var(--cd-accent) !important;
        background: rgba(var(--primary-rgb), 0.05) !important;
    }

    /* Modal Footer */
    #pem-footer {
        padding: 1.25rem 1.5rem;
        background: var(--cd-bg-stripe);
        border-top: 1.5px solid var(--cd-border);
        flex-shrink: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        backdrop-filter: blur(12px);
    }

    #pem-footer .ti-btn {
        min-width: 120px;
        font-weight: 700;
        letter-spacing: 0.01em;
    }

    /* Step circles dynamic variables */
    :root {
        --circle-active: var(--cd-accent);
        --circle-done: var(--cd-accent);
        --circle-pending: var(--cd-border);
    }

    #pem-drawer > div:first-child button[data-hs-overlay],
    #pem-drawer > div:first-child button[onclick="closeProfileEditModal()"],
    .ti-btn {
        height: 44px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding-left: 1.25rem !important;
        padding-right: 1.25rem !important;
        border-radius: 8px !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    /* Dynamic Row Styling Refinement */
    .pem-edu-row, .pem-cert-row, .pem-ref-row {
        padding: 2.25rem 1.25rem 1.25rem !important;
        position: relative;
        background: var(--cd-bg-stripe) !important;
        border: 1.5px solid var(--cd-border) !important;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .pem-edu-row:hover, .pem-cert-row:hover, .pem-ref-row:hover {
        border-color: rgba(var(--primary-rgb), 0.3) !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .pem-row-remove {
        position: absolute;
        top: 12px;
        right: 12px;
        height: 28px;
        padding: 0 10px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(239, 68, 68, 0.08);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.1);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 30;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    .pem-row-remove i { font-size: 0.9rem; }
    .pem-row-remove:hover {
        background: #ef4444;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.2);
    }

    /* Premium Add Button */
    .pem-btn-add {
        padding: 0.5rem 1rem !important;
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        border-radius: 10px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        border: 1.5px solid var(--cd-accent) !important;
        background: transparent !important;
        color: var(--cd-accent) !important;
        cursor: pointer !important;
        margin-left: auto !important;
    }
    .pem-btn-add i { font-size: 1rem; }
    .pem-btn-add:hover {
        background: var(--cd-accent) !important;
        color: #fff !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 15px rgba(var(--primary-rgb), 0.25) !important;
    }
    .pem-btn-add:active { transform: translateY(0); }

    /* Error State — specificity matches #pem-drawer .form-control */
    #pem-drawer .pem-error,
    #pem-drawer .pem-error.form-control,
    #pem-drawer .pem-error.form-select {
        border-color: #ef4444 !important;
        background-color: rgba(239, 68, 68, 0.05) !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
    }
    .pem-error-msg {
        color: #ef4444;
        font-size: 0.7rem;
        font-weight: 600;
        margin-top: 4px;
    }

    /* Small impact buttons (remove buttons) */
    .pem-remove-resp, .pem-remove-ref, .pem-remove-ach, .pem-remove-act {
        width: 44px !important;
        height: 44px !important;
        padding: 0 !important;
    }

    #pem-drawer > div:first-child button[data-hs-overlay],
    #pem-drawer > div:first-child button[onclick="closeProfileEditModal()"] {
        background: rgba(var(--cd-text-muted-rgb), 0.1) !important;
        color: var(--cd-text) !important;
        border-radius: 8px;
        border: none !important;
        width: 44px !important;
        padding: 0 !important;
        transition: all 0.2s ease;
    }

    #pem-drawer > div:first-child button[data-hs-overlay]:hover,
    #pem-drawer > div:first-child button[onclick="closeProfileEditModal()"]:hover {
        background: rgba(var(--cd-text-muted-rgb), 0.2) !important;
        transform: rotate(90deg);
    }

    /* Dark Mode specific Variable adjustments */
    :is([data-theme-mode="dark"], .dark) .pem-section {
        background: rgba(255, 255, 255, 0.03) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    :is([data-theme-mode="dark"], .dark) .pem-edu-row,
    :is([data-theme-mode="dark"], .dark) .pem-cert-row,
    :is([data-theme-mode="dark"], .dark) .pem-ref-row,
    :is([data-theme-mode="dark"], .dark) .pem-skill-row,
    :is([data-theme-mode="dark"], .dark) .pem-tool-row,
    :is([data-theme-mode="dark"], .dark) .pem-lang-row,
    :is([data-theme-mode="dark"], .dark) .pem-resp-row {
        background: rgba(255, 255, 255, 0.04) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    :is([data-theme-mode="dark"], .dark) .pem-edu-row:hover,
    :is([data-theme-mode="dark"], .dark) .pem-cert-row:hover,
    :is([data-theme-mode="dark"], .dark) .pem-ref-row:hover {
        border-color: rgba(99, 102, 241, 0.3) !important;
        background: rgba(255, 255, 255, 0.06) !important;
    }

    :is([data-theme-mode="dark"], .dark) #pem-drawer .form-label,
    :is([data-theme-mode="dark"], .dark) .pem-check-label,
    :is([data-theme-mode="dark"], .dark) #pem-step-title span,
    :is([data-theme-mode="dark"], .dark) #pem-step-desc {
        color: #e5e7eb !important;
    }

    :is([data-theme-mode="dark"], .dark) .pem-section-title {
        color: #f8fafc !important;
    }

    :is([data-theme-mode="dark"], .dark) #pem-drawer .form-control,
    :is([data-theme-mode="dark"], .dark) #pem-drawer .form-select {
        background-color: rgba(30, 41, 59, 0.8) !important;
        color: #f1f5f9 !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
    }

    :is([data-theme-mode="dark"], .dark) #pem-drawer .form-control::placeholder {
        color: #64748b !important;
    }

    :is([data-theme-mode="dark"], .dark) #pem-drawer .form-control:focus,
    :is([data-theme-mode="dark"], .dark) #pem-drawer .form-select:focus {
        background-color: rgba(255, 255, 255, 0.08) !important;
        border-color: var(--cd-accent) !important;
        box-shadow: 0 0 0 4px rgba(var(--primary-rgb), 0.15) !important;
    }

    :is([data-theme-mode="dark"], .dark) #pem-drawer .relative i {
        color: #64748b !important;
    }

    :is([data-theme-mode="dark"], .dark) #pem-drawer {
        background-color: #0f172a !important;
        border-left-color: rgba(255, 255, 255, 0.08) !important;
    }

    :is([data-theme-mode="dark"], .dark) #pem-header,
    :is([data-theme-mode="dark"], .dark) #pem-progress-container,
    :is([data-theme-mode="dark"], .dark) #pem-footer {
        background: transparent !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }

    :is([data-theme-mode="dark"], .dark) #pem-progress-track {
        background: rgba(255, 255, 255, 0.1) !important;
    }

    :is([data-theme-mode="dark"], .dark) .pem-circle:not(.active) {
        background-color: #0f172a !important;
        border-color: rgba(255, 255, 255, 0.15) !important;
        color: #94a3b8 !important;
        outline-color: #0f172a !important;
    }

    :is([data-theme-mode="dark"], .dark) .pem-tab-label {
        color: #94a3b8 !important;
    }
    /* ── Browser Autofill Dark Mode Fix ── */
    /* Chrome applies its own white bg on autofill — override with inset shadow trick */
    :is([data-theme-mode="dark"], .dark) #pem-drawer .form-control:-webkit-autofill,
    :is([data-theme-mode="dark"], .dark) #pem-drawer .form-control:-webkit-autofill:hover,
    :is([data-theme-mode="dark"], .dark) #pem-drawer .form-control:-webkit-autofill:focus,
    :is([data-theme-mode="dark"], .dark) #pem-drawer .form-control:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 1000px rgba(30, 41, 59, 0.95) inset !important;
        box-shadow: 0 0 0 1000px rgba(30, 41, 59, 0.95) inset !important;
        -webkit-text-fill-color: #f1f5f9 !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        caret-color: #f1f5f9 !important;
    }

    /* Also prevent white bg when user has typed a value (non-autofill) */
    :is([data-theme-mode="dark"], .dark) #pem-drawer .form-control:not(:placeholder-shown) {
        background-color: rgba(30, 41, 59, 0.8) !important;
        color: #f1f5f9 !important;
    }

    /* Hide scroll to top & help button when modal is open */
    body.pem-open .scrollToTop,
    body.pem-open #wt-float-btn {
        display: none !important;
    }
</style>

<script>
    (function() {
        // ── Helpers ──────────────────────────────────────────────────────────────
        function pemNextIdx(containerSel, rowSel) {
            const c = document.querySelector(containerSel);
            if (!c) {
                return 0;
            }
            let max = -1;
            c.querySelectorAll(rowSel).forEach(function(r) {
                const n = parseInt(r.dataset.index || '0', 10);
                if (!isNaN(n) && n > max) {
                    max = n;
                }
            });
            return max + 1;
        }

        function pemRefresh(containerSel, rowSel, removeSel) {
            const c = document.querySelector(containerSel);
            if (!c) {
                return;
            }
            c.querySelectorAll(rowSel).forEach(function(r, i) {
                const btn = r.querySelector(removeSel);
                if (!btn) {
                    return;
                }
                btn.classList.toggle('hidden', i === 0);
            });
        }

        function pemBindRemove(containerSel, rowSel, removeSel, minRows) {
            const c = document.querySelector(containerSel);
            if (!c) {
                return;
            }
            c.addEventListener('click', function(e) {
                if (!e.target.closest(removeSel)) {
                    return;
                }
                const row = e.target.closest(rowSel);
                if (row && c.querySelectorAll(rowSel).length > (minRows ?? 1)) {
                    row.remove();
                    pemRefresh(containerSel, rowSel, removeSel);
                }
            });
        }

        function pemBindAdd(btnId, containerSel, rowSel, removeSel, makeRowFn) {
            const btn = document.getElementById(btnId);
            const c = document.querySelector(containerSel);
            if (!btn || !c) {
                return;
            }
            btn.addEventListener('click', function() {
                const idx = pemNextIdx(containerSel, rowSel);
                const wrapper = document.createElement('div');
                wrapper.innerHTML = makeRowFn(idx).trim();
                c.appendChild(wrapper.firstElementChild);
                pemRefresh(containerSel, rowSel, removeSel);
            });
        }

        // ── Step logic ────────────────────────────────────────────────────────────
        let pemStep = 1;
        const PEM_STEPS = 3;
        const stepTitles = {
            1: 'Edit Profile — Step 1 of 3',
            2: 'Edit Profile — Step 2 of 3',
            3: 'Edit Profile — Step 3 of 3',
        };
        const stepDescs = {
            1: 'Basic info, summary and Resume',
            2: 'Education, skills, certifications and references',
            3: 'Work experience and responsibilities',
        };

        function pemValidateStep(step) {
            const stepEl = document.querySelector('.pem-step-' + step);
            if (!stepEl) return true;

            const requiredFields = stepEl.querySelectorAll('[required]');
            const emptyFields = [];

            requiredFields.forEach(field => {
                let isEmpty = false;
                if (field.tagName === 'SELECT') {
                    isEmpty = !field.value || field.value === '';
                } else {
                    isEmpty = !field.value || field.value.trim() === '';
                }

                if (isEmpty) {
                    field.classList.add('pem-error');
                    
                    // Human-readable field name lookup
                    const container = field.closest('.col-span-12, .col-span-8, .col-span-6, .col-span-4, .mb-4, .pem-skill-row, .pem-tool-row, .pem-lang-row, .pem-resp-row');
                    const label = container?.querySelector('.form-label');
                    let fieldName = label ? label.textContent.replace('*', '').replace(/\s+/g, ' ').trim() : '';
                    
                    if (!fieldName && field.placeholder) {
                        fieldName = field.placeholder.replace('e.g.', '').replace('Skill name', 'Skill').trim();
                    }
                    
                    if (!fieldName) {
                        fieldName = field.name.replace(/\[\d+\]/g, '').replace(/_/g, ' ');
                        fieldName = fieldName.charAt(0).toUpperCase() + fieldName.slice(1);
                    }

                    if (!emptyFields.includes(fieldName)) {
                        emptyFields.push(fieldName);
                    }
                } else {
                    field.classList.remove('pem-error');
                }
            });

            // Salary validation
            const minSal = stepEl.querySelector('[name="expected_salary_min"]');
            const maxSal = stepEl.querySelector('[name="expected_salary_max"]');
            if (minSal && maxSal && minSal.value && maxSal.value) {
                const min = parseFloat(minSal.value);
                const max = parseFloat(maxSal.value);
                if (min > max) {
                    minSal.classList.add('border-danger');
                    maxSal.classList.add('border-danger');
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Salary Range',
                            text: 'Minimum salary cannot be greater than maximum salary.',
                            confirmButtonColor: '#4f46e5',
                        });
                    }
                    return false;
                }
            }

            // Date range validation
            const startExp = stepEl.querySelector('[name="experience_start"]');
            const endExp = stepEl.querySelector('[name="experience_end"]');
            if (startExp && endExp && startExp.value && endExp.value) {
                if (startExp.value > endExp.value) {
                    startExp.classList.add('border-danger');
                    endExp.classList.add('border-danger');
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Date Range',
                            text: 'Start date cannot be later than end date.',
                            confirmButtonColor: '#4f46e5',
                        });
                    }
                    return false;
                }
            }

            if (emptyFields.length > 0) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Information',
                        html: `
                            <div class="text-left">
                                <p class="mb-3 text-sm text-slate-600 dark:text-slate-400">To proceed, please fill in the following required items in this step:</p>
                                <div class="space-y-2">
                                    ${emptyFields.map(f => `
                                        <div class="flex items-center gap-2 text-xs font-bold text-danger bg-danger/5 p-2 rounded-lg border border-danger/10">
                                            <i class="ri-error-warning-line"></i>
                                            ${f}
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Got it',
                        confirmButtonColor: 'var(--cd-accent)',
                        customClass: {
                            popup: 'premium-swal-popup',
                            title: 'premium-swal-title'
                        }
                    });
                }
                return false;
            }
            return true;
        }

        function pemRender() {
            for (let i = 1; i <= PEM_STEPS; i++) {
                const el = document.querySelector('.pem-step-' + i);
                if (el) {
                    el.style.display = i === pemStep ? '' : 'none';
                }

                // circle & label updates using the new tokens
                const circle = document.querySelector('.pem-circle-' + i);
                const label = document.querySelector('.pem-tab-label-' + i);
                const isCurrent = i === pemStep;
                const isDone = i < pemStep;

                if (circle) {
                    if (isCurrent || isDone) {
                        circle.style.background = 'var(--cd-accent)';
                        circle.style.color = '#fff';
                        circle.style.borderColor = 'var(--cd-accent)';
                    } else {
                        circle.style.background = 'var(--cd-bg-stripe)';
                        circle.style.color = 'var(--cd-text-muted)';
                        circle.style.borderColor = 'var(--cd-border)';
                    }
                }
                if (label) {
                    label.style.color = (isCurrent || isDone) ? 'var(--cd-accent)' : 'var(--cd-text-muted)';
                    label.style.fontWeight = (isCurrent || isDone) ? '800' : '500';
                }
            }

            const bar = document.getElementById('pem-progress-bar');
            if (bar) {
                bar.style.width = (pemStep / PEM_STEPS * 100) + '%';
            }

            const prev = document.getElementById('pem-prev');
            const next = document.getElementById('pem-next');
            const submit = document.getElementById('pem-submit');
            const titleEl = document.getElementById('pem-step-title');
            const descEl = document.getElementById('pem-step-desc');
            if (prev) {
                prev.style.setProperty('display', pemStep > 1 ? 'inline-flex' : 'none', 'important');
            }
            if (next) {
                next.style.setProperty('display', pemStep < PEM_STEPS ? 'inline-flex' : 'none', 'important');
            }
            if (submit) {
                submit.style.setProperty('display', pemStep === PEM_STEPS ? 'inline-flex' : 'none', 'important');
            }
            if (titleEl) {
                titleEl.querySelector('span').textContent = stepTitles[pemStep];
            }
            if (descEl) {
                descEl.textContent = stepDescs[pemStep];
            }

            // Scroll to top of body
            const body = document.getElementById('pem-body');
            if (body) {
                body.scrollTop = 0;
            }
        }

        window.pemGoToStep = function(n) {
            if (n < pemStep) {
                pemStep = n;
                pemRender();
            } else if (n > pemStep) {
                for (let s = pemStep; s < n; s++) {
                    if (!pemValidateStep(s)) return;
                }
                pemStep = n;
                pemRender();
            }
        };

        // ── Open / Close ──────────────────────────────────────────────────────────
        window.openProfileEditModal = function(step) {
            pemStep = step || 1;
            const backdrop = document.getElementById('pem-backdrop');
            const drawer = document.getElementById('pem-drawer');
            if (!drawer) {
                return;
            }

            document.body.classList.add('pem-open');

            backdrop.style.display = 'block';
            drawer.style.display = 'flex';
            // Force reflow so transition plays
            void drawer.offsetWidth;
            drawer.style.transform = 'translateX(0)';
            document.body.style.overflow = 'hidden';
            pemRender();
        };

        window.closeProfileEditModal = function() {
            const drawer = document.getElementById('pem-drawer');
            const backdrop = document.getElementById('pem-backdrop');
            if (!drawer) {
                return;
            }
            drawer.style.transform = 'translateX(100%)';
            setTimeout(function() {
                drawer.style.display = 'none';
                backdrop.style.display = 'none';
                document.body.style.overflow = '';
                document.body.classList.remove('pem-open');
            }, 300);
        };

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeProfileEditModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {

            // ── Prev / Next buttons ──
            const prevBtn = document.getElementById('pem-prev');
            const nextBtn = document.getElementById('pem-next');
            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    if (pemStep > 1) {
                        pemStep--;
                        pemRender();
                    }
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    if (pemValidateStep(pemStep)) {
                        if (pemStep < PEM_STEPS) {
                            pemStep++;
                            pemRender();
                        }
                    }
                });
            }

            // ── CV upload ──
            const cvInput = document.getElementById('pem_cv_file');
            const cvTrigger = document.getElementById('pem_cv_trigger');
            const cvName = document.getElementById('pem_cv_name');
            if (cvTrigger && cvInput) {
                cvTrigger.addEventListener('click', function() {
                    cvInput.click();
                });
            }
            if (cvInput && cvName) {
                cvInput.addEventListener('change', function() {
                    const n = this.files && this.files[0] ? this.files[0].name : '';
                    cvName.textContent = n ? '✓ ' + n : '';
                    cvName.style.display = n ? '' : 'none';

                    if (n) {
                        cvTrigger.classList.add('has-file');
                        const icon = document.getElementById('pem_cv_icon');
                        if (icon) {
                            icon.className = 'ri-checkbox-circle-fill text-success';
                        }
                        const text = document.getElementById('pem_cv_text');
                        if (text) {
                            text.textContent = 'New resume selected';
                        }
                    }
                });
            }

            // ── Experience current checkbox ──
            const expCurrent = document.getElementById('pem_exp_current');
            const expEnd = document.getElementById('pem_exp_end');
            if (expCurrent && expEnd) {
                expCurrent.addEventListener('change', function() {
                    expEnd.disabled = this.checked;
                    if (this.checked) {
                        expEnd.value = '';
                    }
                });
            }

            // ── Salary range validation ──
            const pemMinSal = document.querySelector('[name="expected_salary_min"]');
            const pemMaxSal = document.querySelector('[name="expected_salary_max"]');
            if (pemMinSal && pemMaxSal) {
                const validateSalary = () => {
                    const min = parseFloat(pemMinSal.value);
                    const max = parseFloat(pemMaxSal.value);
                    if (!isNaN(min) && !isNaN(max) && min > max) {
                        pemMinSal.classList.add('border-danger');
                        pemMaxSal.classList.add('border-danger');
                    } else {
                        pemMinSal.classList.remove('border-danger');
                        pemMaxSal.classList.remove('border-danger');
                    }
                };
                pemMinSal.addEventListener('input', validateSalary);
                pemMaxSal.addEventListener('input', validateSalary);
            }

            // ── Experience date range validation ──
            const pemExpStart = document.getElementById('pem_exp_start');
            const pemExpEnd = document.getElementById('pem_exp_end');
            if (pemExpStart && pemExpEnd) {
                const validateExpDates = () => {
                    const s = pemExpStart.value;
                    const e = pemExpEnd.value;
                    if (s && e && s > e) {
                        pemExpStart.classList.add('border-danger');
                        pemExpEnd.classList.add('border-danger');
                    } else {
                        pemExpStart.classList.remove('border-danger');
                        pemExpEnd.classList.remove('border-danger');
                    }
                };
                pemExpStart.addEventListener('change', validateExpDates);
                pemExpEnd.addEventListener('change', validateExpDates);
            }

            // ── Dynamic rows ──

            // Education
            pemBindAdd('pem-add-education', '#pem-education-list', '.pem-edu-row', '.pem-remove-edu',
                function(idx) {
                    return `<div class="pem-edu-row" data-index="${idx}">
                        <button type="button" class="pem-row-remove pem-remove-edu">
                            <i class="ri-close-line"></i>
                            <span>Remove</span>
                        </button>
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-xs">Course &amp; Major</label>
                                <div class="relative">
                                    <i class="ri-book-2-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                    <input type="text" name="education[${idx}][course]" class="form-control ps-8 h-9! text-sm" placeholder="e.g. BS Computer Science">
                                </div>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-xs">School / University</label>
                                <div class="relative">
                                    <i class="ri-community-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                    <input type="text" name="education[${idx}][school]" class="form-control ps-8 h-9! text-sm" placeholder="e.g. Harvard University">
                                </div>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-xs">Location</label>
                                <div class="relative">
                                    <i class="ri-map-pin-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                    <input type="text" name="education[${idx}][location]" class="form-control ps-8 h-9! text-sm" placeholder="e.g. Cambridge, MA">
                                </div>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-xs">Education Period</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <i class="ri-calendar-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                        <select name="education[${idx}][start_year]" class="form-control ps-8 h-9! text-sm">
                                            <option value="">From</option>
                                            ${(function() {
                                                let options = '';
                                                const currentYear = new Date().getFullYear();
                                                for (let y = currentYear + 5; y >= 1970; y--) {
                                                    options += `<option value="${y}">${y}</option>`;
                                                }
                                                return options;
                                            })()}
                                        </select>
                                    </div>
                                    <div class="relative flex-1">
                                        <i class="ri-calendar-check-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                        <select name="education[${idx}][end_year]" class="form-control ps-8 h-9! text-sm">
                                            <option value="">To</option>
                                            ${(function() {
                                                let options = '';
                                                const currentYear = new Date().getFullYear();
                                                for (let y = currentYear + 5; y >= 1970; y--) {
                                                    options += `<option value="${y}">${y}</option>`;
                                                }
                                                return options;
                                            })()}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
            pemBindRemove('#pem-education-list', '.pem-edu-row', '.pem-remove-edu');
            pemRefresh('#pem-education-list', '.pem-edu-row', '.pem-remove-edu');

            // Skills
            pemBindAdd('pem-add-skill', '#pem-skills-list', '.pem-skill-row', '.pem-remove-skill',
                function(idx) {
                    return `<div class="flex gap-2 items-center pem-skill-row" data-index="${idx}">
                    <div class="relative flex-1">
                        <i class="ri-checkbox-circle-fill absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-primary"></i>
                        <input type="text" name="skills[${idx}]" class="form-control ps-8 h-9! text-sm" placeholder="Skill name" required>
                    </div>
                    <button type="button" class="pem-remove-skill inline-flex items-center justify-center h-8 w-8 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0"><i class="ri-close-line"></i></button>
                </div>`;
                });
            pemBindRemove('#pem-skills-list', '.pem-skill-row', '.pem-remove-skill');
            pemRefresh('#pem-skills-list', '.pem-skill-row', '.pem-remove-skill');

            // Tools
            pemBindAdd('pem-add-tool', '#pem-tools-list', '.pem-tool-row', '.pem-remove-tool',
                function(idx) {
                    return `<div class="flex gap-2 items-center pem-tool-row" data-index="${idx}">
                    <div class="relative flex-1">
                        <i class="ri-settings-4-fill absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-primary"></i>
                        <input type="text" name="tools_used[${idx}]" class="form-control ps-8 h-9! text-sm" placeholder="Tool / Software" required>
                    </div>
                    <button type="button" class="pem-remove-tool inline-flex items-center justify-center h-8 w-8 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0"><i class="ri-close-line"></i></button>
                </div>`;
                });
            pemBindRemove('#pem-tools-list', '.pem-tool-row', '.pem-remove-tool');
            pemRefresh('#pem-tools-list', '.pem-tool-row', '.pem-remove-tool');

            // Languages
            pemBindAdd('pem-add-language', '#pem-languages-list', '.pem-lang-row', '.pem-remove-lang',
                function(idx) {
                    return `<div class="flex gap-2 items-center pem-lang-row" data-index="${idx}">
                    <div class="relative flex-1">
                        <i class="ri-global-line absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-primary"></i>
                        <input type="text" name="languages[${idx}]" class="form-control ps-8 h-9! text-sm" placeholder="e.g. English — Fluent" required>
                    </div>
                    <button type="button" class="pem-remove-lang inline-flex items-center justify-center h-8 w-8 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0"><i class="ri-close-line"></i></button>
                </div>`;
                });
            pemBindRemove('#pem-languages-list', '.pem-lang-row', '.pem-remove-lang');
            pemRefresh('#pem-languages-list', '.pem-lang-row', '.pem-remove-lang');

            // Certifications
            pemBindAdd('pem-add-cert', '#pem-certs-list', '.pem-cert-row', '.pem-remove-cert',
                function(idx) {
                    return `<div class="pem-cert-row" data-index="${idx}">
                        <button type="button" class="pem-row-remove pem-remove-cert">
                            <i class="ri-close-line"></i>
                            <span>Remove</span>
                        </button>
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-xs">Certificate / Course</label>
                                <div class="relative">
                                    <i class="ri-medal-2-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                    <input type="text" name="certifications[${idx}][title]" class="form-control ps-8 h-9! text-sm">
                                </div>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-xs">Provider / School</label>
                                <div class="relative">
                                    <i class="ri-building-4-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                    <input type="text" name="certifications[${idx}][provider]" class="form-control ps-8 h-9! text-sm">
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
            pemBindRemove('#pem-certs-list', '.pem-cert-row', '.pem-remove-cert');
            pemRefresh('#pem-certs-list', '.pem-cert-row', '.pem-remove-cert');

            // Achievements
            pemBindAdd('pem-add-achievement', '#pem-achievements-list', '.pem-ach-row', '.pem-remove-ach',
                function(idx) {
                    return `<div class="flex gap-2 items-center pem-ach-row" data-index="${idx}">
                    <div class="relative flex-1">
                        <i class="ri-award-fill absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-primary"></i>
                        <input type="text" name="achievements[${idx}]" class="form-control ps-8 h-9! text-sm" placeholder="Achievement" required>
                    </div>
                    <button type="button" class="pem-remove-ach inline-flex items-center justify-center h-8 w-8 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0"><i class="ri-close-line"></i></button>
                </div>`;
                });
            pemBindRemove('#pem-achievements-list', '.pem-ach-row', '.pem-remove-ach');
            pemRefresh('#pem-achievements-list', '.pem-ach-row', '.pem-remove-ach');

            // Activities
            pemBindAdd('pem-add-activity', '#pem-activities-list', '.pem-act-row', '.pem-remove-act',
                function(idx) {
                    return `<div class="flex gap-2 items-center pem-act-row" data-index="${idx}">
                    <div class="relative flex-1">
                        <i class="ri-heart-fill absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-primary"></i>
                        <input type="text" name="activities[${idx}]" class="form-control ps-8 h-9! text-sm" placeholder="Activity or interest" required>
                    </div>
                    <button type="button" class="pem-remove-act inline-flex items-center justify-center h-8 w-8 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0"><i class="ri-close-line"></i></button>
                </div>`;
                });
            pemBindRemove('#pem-activities-list', '.pem-act-row', '.pem-remove-act');
            pemRefresh('#pem-activities-list', '.pem-act-row', '.pem-remove-act');

            // References
            pemBindAdd('pem-add-ref', '#pem-refs-list', '.pem-ref-row', '.pem-remove-ref',
                function(idx) {
                    return `<div class="pem-ref-row" data-index="${idx}">
                        <button type="button" class="pem-row-remove pem-remove-ref">
                            <i class="ri-close-line"></i>
                            <span>Remove</span>
                        </button>
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-xs">Name</label>
                                <div class="relative"><i class="ri-user-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i><input type="text" name="references[${idx}][name]" class="form-control ps-8 h-9! text-sm"></div>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-xs">Designation</label>
                                <div class="relative"><i class="ri-id-card-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i><input type="text" name="references[${idx}][designation]" class="form-control ps-8 h-9! text-sm"></div>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-xs">Company</label>
                                <div class="relative"><i class="ri-building-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i><input type="text" name="references[${idx}][company]" class="form-control ps-8 h-9! text-sm"></div>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-xs">Mobile</label>
                                <div class="relative"><i class="ri-phone-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i><input type="text" name="references[${idx}][mobile]" class="form-control ps-8 h-9! text-sm"></div>
                            </div>
                            <div class="col-span-12">
                                <label class="form-label text-xs">Email</label>
                                <div class="relative"><i class="ri-mail-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i><input type="email" name="references[${idx}][email]" class="form-control ps-8 h-9! text-sm"></div>
                            </div>
                        </div>
                    </div>`;
                });
            pemBindRemove('#pem-refs-list', '.pem-ref-row', '.pem-remove-ref');
            pemRefresh('#pem-refs-list', '.pem-ref-row', '.pem-remove-ref');

            // Responsibilities
            pemBindAdd('pem-add-resp', '#pem-resp-list', '.pem-resp-row', '.pem-remove-resp',
                function(idx) {
                    return `<div class="flex gap-2 items-center pem-resp-row" data-index="${idx}">
                    <div class="relative flex-1">
                        <i class="ri-checkbox-blank-circle-fill absolute left-3 top-1/2 -translate-y-1/2 text-[6px] text-primary"></i>
                        <input type="text" name="experience_responsibilities[${idx}]" class="form-control ps-8" placeholder="Responsibility" required>
                    </div>
                    <button type="button" class="pem-remove-resp inline-flex items-center justify-center h-9 w-9 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0"><i class="ri-close-line"></i></button>
                </div>`;
                });
            pemBindRemove('#pem-resp-list', '.pem-resp-row', '.pem-remove-resp');
            pemRefresh('#pem-resp-list', '.pem-resp-row', '.pem-remove-resp');

            // ── Form validation on submit ──
            const form = document.getElementById('pem-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const required = form.querySelectorAll('[required]');
                    const empty = [];
                    required.forEach(function(f) {
                        const isEmpty = f.tagName === 'SELECT' ? !f.value : !f.value.trim();
                        if (isEmpty) {
                            const lbl = f.closest('.col-span-12, .col-span-6, .col-span-4, .mb-4')
                                ?.querySelector('.form-label');
                            const name = lbl ? lbl.textContent.replace('*', '').trim() : f.name;
                            if (!empty.includes(name)) {
                                empty.push(name);
                            }
                        }
                    });

                    if (empty.length > 0) {
                        e.preventDefault();
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Required Fields Missing',
                                html: '<p class="mb-2">Please fill in the following fields:</p><ul class="text-left list-disc pl-5">' +
                                    empty.map(function(f) {
                                        return '<li>' + f + '</li>';
                                    }).join('') +
                                    '</ul>',
                                confirmButtonColor: '#4f46e5',
                            }).then(function() {
                                // Jump to the step that contains the first empty field
                                const first = Array.from(required).find(function(f) {
                                    return f.tagName === 'SELECT' ? !f.value : !f.value.trim();
                                });
                                if (first) {
                                    const stepEl = first.closest('.pem-step');
                                    if (stepEl) {
                                        const m = stepEl.className.match(/pem-step-(\d+)/);
                                        if (m) {
                                            pemGoToStep(parseInt(m[1], 10));
                                        }
                                    }
                                    setTimeout(function() {
                                        first.focus();
                                        first.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center'
                                        });
                                    }, 200);
                                }
                            });
                        } else {
                            alert('Please fill in all required fields: ' + empty.join(', '));
                        }
                    }
                });
            }

        }); // DOMContentLoaded

        // ── CV remove confirmation ──────────────────────────────────────────────
        window.pemConfirmRemoveCV = function() {
            const confirmFn = function() {
                document.getElementById('pem_remove_cv').value = '1';
                document.getElementById('pem-form').submit();
            };
            if (window.Swal) {
                Swal.fire({
                    title: 'Remove Resume?',
                    text: 'Are you sure you want to remove your uploaded resume?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Yes, remove it',
                }).then(function(r) {
                    if (r.isConfirmed) {
                        confirmFn();
                    }
                });
            } else {
                if (confirm('Remove your uploaded resume?')) {
                    confirmFn();
                }
            }
        };

        // ── Auto-clear error state on interaction ──────────────────────────────
        const drawer = document.getElementById('pem-drawer');
        if (drawer) {
            drawer.addEventListener('input', function(e) {
                if (e.target.classList.contains('pem-error')) {
                    e.target.classList.remove('pem-error');
                }
            });
            drawer.addEventListener('focusin', function(e) {
                if (e.target.classList.contains('pem-error')) {
                    e.target.classList.remove('pem-error');
                }
            });
        }

    })();
</script>
