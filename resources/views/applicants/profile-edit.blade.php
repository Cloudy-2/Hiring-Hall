    <x-app-layout
        :breadcrumbs="[
            ['label' => 'My Profile', 'url' => route('applicant.profile.show')],
        ]"
        active="Edit Profile"
    >

        <div class="box border-0 shadow-sm dark:shadow-none">
            <div class="box-body max-w-4xl mx-auto px-4 sm:px-6">
                @if (session('status'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Profile updated',
                                text: @json(session('status')),
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }
                    });
                </script>
                @endif

                <form id="candidate-profile-wizard" method="POST" action="{{ route('applicant.profile.update') }}" enctype="multipart/form-data" novalidate>
                    @csrf

                    {{-- Step indicator --}}
                    <div class="mb-2">
                        <h2 id="wizard-step-title" class="text-xl font-semibold text-gray-900 dark:text-white tracking-tight">
                            Step 1 of 3 — Overview
                        </h2>
                        <p id="wizard-step-desc" class="text-sm text-textmuted mt-0.5">Basic info, summary, and Resume</p>
                    </div>

                    {{-- Step Tabs / Progress bar --}}
                    <div class="mb-10">
                        <div class="relative w-full max-w-md mx-auto">
                            <div class="absolute left-0 right-0 top-5 h-0.5 rounded-full bg-defaultborder/50 overflow-hidden" aria-hidden="true">
                                <div id="wizard-progress-bar" class="absolute left-0 top-0 h-full bg-primary rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                            </div>
                            <div class="relative flex justify-between gap-2">
                                <button type="button" class="wizard-tab wizard-tab-1 flex flex-col items-center gap-2 flex-1 min-w-0 focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 rounded-xl py-2 transition-all duration-200 hover:opacity-90" aria-label="Go to step 1">
                                    <span class="wizard-circle flex items-center justify-center w-10 h-10 rounded-full border-2 border-primary bg-white dark:bg-bodybg text-sm font-semibold text-primary shadow-sm ring-2 ring-white dark:ring-bodybg">1</span>
                                    <span class="text-xs font-medium text-primary truncate w-full text-center">Overview</span>
                                </button>
                                <button type="button" class="wizard-tab wizard-tab-2 flex flex-col items-center gap-2 flex-1 min-w-0 focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 rounded-xl py-2 transition-all duration-200 hover:opacity-90" aria-label="Go to step 2">
                                    <span class="wizard-circle flex items-center justify-center w-10 h-10 rounded-full border-2 border-defaultborder bg-white dark:bg-bodybg text-sm font-semibold text-textmuted shadow-sm ring-2 ring-white dark:ring-bodybg">2</span>
                                    <span class="text-xs font-medium text-textmuted truncate w-full text-center">Profile Info</span>
                                </button>
                                <button type="button" class="wizard-tab wizard-tab-3 flex flex-col items-center gap-2 flex-1 min-w-0 focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 rounded-xl py-2 transition-all duration-200 hover:opacity-90" aria-label="Go to step 3">
                                    <span class="wizard-circle flex items-center justify-center w-10 h-10 rounded-full border-2 border-defaultborder bg-white dark:bg-bodybg text-sm font-semibold text-textmuted shadow-sm ring-2 ring-white dark:ring-bodybg">3</span>
                                    <span class="text-xs font-medium text-textmuted truncate w-full text-center">Experience</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 1: Overview / Header info --}}
                    <div class="wizard-step wizard-step-1 transition-opacity duration-200" id="wizard-step-content">
                        <div class="rounded-xl border border-defaultborder/50 bg-light/40 dark:bg-white/5 p-6 mb-6 shadow-sm dark:shadow-none">
                            <h5 class="font-semibold text-sm text-gray-700 dark:text-white mb-4 flex items-center gap-2">
                                <i class="ri-user-smile-line text-primary"></i> Basic Information
                            </h5>
                            <div class="grid grid-cols-12 gap-x-5 gap-y-4">
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">Display Name <span class="text-danger">*</span></label>
                                    <div class="relative">
                                        <i class="ri-user-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                        <input type="text" name="display_name" class="form-control ps-10" value="{{ old('display_name', $profile->display_name) }}" required placeholder="e.g. John Doe">
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">Job Title <span class="text-danger">*</span></label>
                                    <div class="relative">
                                        <i class="ri-briefcase-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                        <input type="text" name="job_title" class="form-control ps-10" list="candidate_job_title_list" value="{{ old('job_title', $profile->job_title ?? $profile->title) }}" required placeholder="Select or type your job title">
                                    </div>
                                    <datalist id="candidate_job_title_list">
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
                                    <label class="form-label">Location <span class="text-danger">*</span></label>
                                    <div class="relative">
                                        <i class="ri-map-pin-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                        <input type="text" name="location" class="form-control ps-10" value="{{ old('location', $profile->location) }}" required placeholder="e.g. Manila, Philippines">
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

                        {{-- Section 2: Qualifications & Preferences --}}
                        <div class="rounded-xl border border-defaultborder/50 bg-light/40 dark:bg-white/5 p-6 mb-6 shadow-sm dark:shadow-none">
                            <h5 class="font-semibold text-sm text-gray-700 dark:text-white mb-4 flex items-center gap-2">
                                <i class="ri-briefcase-line text-primary"></i> Qualifications &amp; Preferences
                            </h5>
                            
                            <div class="grid grid-cols-12 gap-x-5 gap-y-4">
                                {{-- Degree & Experience Row --}}
                                <div class="col-span-12 md:col-span-8">
                                    <label class="form-label">Degree / Qualification <span class="text-danger">*</span></label>
                                    <div class="relative">
                                        <i class="ri-graduation-cap-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                        <input type="text" name="degree" class="form-control ps-10" placeholder="e.g. BS in Computer Science" value="{{ old('degree', $profile->degree) }}" required>
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-4">
                                    <label class="form-label">Years of Exp. <span class="text-danger">*</span></label>
                                    <div class="relative">
                                        <i class="ri-calendar-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                        <input type="number" name="years_experience" class="form-control ps-10" min="0" value="{{ old('years_experience', $profile->years_experience) }}" required>
                                    </div>
                                </div>

                                {{-- Availability & Job Type Row --}}
                                <div class="col-span-12 md:col-span-6">
                                    <label class="form-label">Availability <span class="text-danger">*</span></label>
                                    <div class="relative">
                                        <i class="ri-checkbox-circle-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                        <select name="availability" class="form-control ps-10" required>
                                            @php $availability = old('availability', $profile->availability); @endphp
                                            <option value="" @if(!$availability) selected @endif disabled>When can you start?</option>
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
                                            <option value="" @if(!$jobType) selected @endif disabled>Preferred job type</option>
                                            @foreach(($dropdownOptions['jobTypes'] ?? []) as $value => $label)
                                            <option value="{{ $value }}" @if($jobType===$value) selected @endif>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Expected Salary Group --}}
                                <div class="col-span-12">
                                    <label class="form-label">Expected Salary Scale <span class="text-danger">*</span></label>
                                    <div class="flex flex-wrap sm:flex-nowrap gap-x-2 gap-y-2 items-center">
                                        <div class="w-full sm:w-32 flex-shrink-0">
                                            <select name="salary_currency" class="form-control" required>
                                                @php $currencyValue = old('salary_currency', $profile->salary_currency ?? 'USD'); @endphp
                                                @foreach(($dropdownOptions['currencies'] ?? []) as $value => $label)
                                                <option value="{{ $value }}" @if($currencyValue===$value) selected @endif>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="relative flex-1 min-w-[120px]">
                                            <input type="number" step="0.01" name="expected_salary_min" class="peer form-control ps-11" placeholder="Minimum" value="{{ old('expected_salary_min', $profile->expected_salary_min) }}" required>
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] uppercase font-bold text-textmuted/40 pointer-events-none transition-opacity duration-200 peer-placeholder-shown:opacity-100 opacity-0">Min</span>
                                        </div>
                                        <div class="hidden sm:block text-textmuted font-medium px-1">—</div>
                                        <div class="relative flex-1 min-w-[120px]">
                                            <input type="number" step="0.01" name="expected_salary_max" class="peer form-control ps-11" placeholder="Maximum" value="{{ old('expected_salary_max', $profile->expected_salary_max) }}" required>
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] uppercase font-bold text-textmuted/40 pointer-events-none transition-opacity duration-200 peer-placeholder-shown:opacity-100 opacity-0">Max</span>
                                        </div>
                                    </div>
                                    <p class="mt-1.5 text-[11px] text-textmuted flex items-center gap-1">
                                        <i class="ri-information-line text-primary"></i>
                                        Specify your desired monthly or yearly salary range in selected currency.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-defaultborder/50 bg-light/40 dark:bg-white/5 p-6 mb-6 shadow-sm dark:shadow-none">
                            <h5 class="font-semibold text-sm text-gray-700 dark:text-white mb-4 flex items-center gap-2">
                                <i class="ri-quote-text text-primary"></i> Summary
                            </h5>
                            <div class="mb-4">
                                <label class="form-label">Headline <span class="text-danger">*</span></label>
                                <div class="relative">
                                    <i class="ri-text-spacing absolute left-3 top-1/2 -translate-y-1/2 text-textmuted pointer-events-none"></i>
                                    <input type="text" name="headline" class="form-control ps-10" value="{{ old('headline', $profile->headline) }}" required placeholder="e.g., Experienced Executive Assistant specialized in Operations">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">About Me <span class="text-danger">*</span></label>
                                <textarea name="about" class="form-control" rows="4" placeholder="Tell recruiters about your professional background, key strengths, and what value you bring..." required>{{ old('about', $profile->about) }}</textarea>
                            </div>
                        </div>

                        {{-- Resume Upload Section --}}
                        <div class="rounded-xl border border-defaultborder/50 bg-light/40 dark:bg-white/5 p-6 mb-6 shadow-sm dark:shadow-none">
                            <h5 class="font-semibold text-sm text-gray-700 dark:text-white mb-4 flex items-center gap-2">
                                <i class="ri-file-upload-line text-primary"></i> Resume
                            </h5>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="flex-1 min-w-0">
                                    <input type="file" name="cv_file" id="cv_file" class="hidden" accept=".pdf,.doc,.docx">
                                    <button type="button" id="cv_upload_trigger" class="cv-upload-zone w-full block rounded-xl border-2 border-dashed border-defaultborder/60 dark:border-defaultborder/40 p-6 text-center cursor-pointer hover:border-primary hover:bg-primary/5 dark:hover:bg-primary/10 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2">
                                        <i class="ri-file-add-line text-3xl text-textmuted mb-2 block cv-upload-icon"></i>
                                        <span class="text-sm font-medium text-textmuted block cv-upload-text">Click to upload or drag and drop</span>
                                        <p class="text-xs text-textmuted mt-1">PDF, DOC, DOCX — max 5MB</p>
                                        <p id="cv_file_name" class="text-xs font-medium text-primary mt-2 hidden truncate"></p>
                                    </button>
                                </div>
                                @if($profile->cv_path)
                                <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
                                    <span class="badge bg-success/10 text-success py-2 px-3">
                                        <i class="ri-file-text-line me-1"></i> Uploaded
                                    </span>
                                    <a href="{{ route('applicant.cv.view') }}" target="_blank" class="ti-btn ti-btn-sm ti-btn-outline-primary">
                                        <i class="ri-eye-line me-1"></i> View
                                    </a>
                                    <button type="button" class="ti-btn ti-btn-sm ti-btn-outline-danger" onclick="confirmRemoveCV()">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                            <input type="hidden" name="remove_cv" id="remove_cv" value="0">
                        </div>
                    </div>

                    {{-- STEP 2: Profile information (education, certifications, achievements, interests, references) --}}
                    <div class="wizard-step wizard-step-2 transition-opacity duration-200" style="display: none;">
                        <div class="rounded-xl border border-defaultborder/50 bg-light/40 dark:bg-white/5 p-6 mb-6 shadow-sm dark:shadow-none">
                            <h5 class="font-semibold text-[15px] mb-3 flex items-center gap-2">
                                <i class="ri-target-line text-primary"></i> Career Objective
                            </h5>
                            <textarea name="career_objective" class="form-control" rows="4" placeholder="What are your career goals?">{{ old('career_objective', $profile->career_objective) }}</textarea>
                        </div>

                        @php
                        $educationForm = $profile->education_details ? json_decode($profile->education_details, true) : [];
                        if (!is_array($educationForm)) {
                        $educationForm = [];
                        }
                        if (old('education')) {
                        $educationForm = old('education');
                        }
                        if (!count($educationForm)) {
                        $educationForm = [
                        ['course' => '', 'school' => '', 'location' => '', 'dates' => ''],
                        ];
                        }

                        $certificationsForm = $profile->certifications ? json_decode($profile->certifications, true) : [];
                        if (!is_array($certificationsForm)) {
                        $certificationsForm = [];
                        }
                        if (old('certifications')) {
                        $certificationsForm = old('certifications');
                        }
                        if (!count($certificationsForm)) {
                        $certificationsForm = [
                        ['title' => '', 'provider' => ''],
                        ];
                        }

                        $achievementsForm = $profile->key_achievements ? json_decode($profile->key_achievements, true) : [];
                        if (!is_array($achievementsForm)) {
                        $achievementsForm = [];
                        }
                        if (old('achievements')) {
                        $achievementsForm = old('achievements');
                        }
                        if (!count($achievementsForm)) {
                        $achievementsForm = [''];
                        }

                        $activitiesForm = $profile->activities_interests ? json_decode($profile->activities_interests, true) : [];
                        if (!is_array($activitiesForm)) {
                        $activitiesForm = [];
                        }
                        if (old('activities')) {
                        $activitiesForm = old('activities');
                        }
                        if (!count($activitiesForm)) {
                        $activitiesForm = [''];
                        }

                        $skillsForm = $profile->skills ? json_decode($profile->skills, true) : [];
                        if (!is_array($skillsForm)) {
                        $skillsForm = [];
                        }
                        if (old('skills')) {
                        $skillsForm = old('skills');
                        }
                        if (!count($skillsForm)) {
                        $skillsForm = [''];
                        }

                        $toolsUsedForm = $profile->tools_used ? json_decode($profile->tools_used, true) : [];
                        if (!is_array($toolsUsedForm)) {
                        $toolsUsedForm = [];
                        }
                        if (old('tools_used')) {
                        $toolsUsedForm = old('tools_used');
                        }
                        if (!count($toolsUsedForm)) {
                        $toolsUsedForm = [''];
                        }

                        $languagesForm = $profile->languages ? json_decode($profile->languages, true) : [];
                        if (!is_array($languagesForm)) {
                        $languagesForm = [];
                        }
                        if (old('languages')) {
                        $languagesForm = old('languages');
                        }
                        if (!count($languagesForm)) {
                        $languagesForm = [''];
                        }

                        $expertiseForm = $profile->expertise_categories ? json_decode($profile->expertise_categories, true) : [];
                        if (!is_array($expertiseForm)) {
                        $expertiseForm = [];
                        }
                        if (old('expertise_categories')) {
                        $expertiseForm = old('expertise_categories');
                        }

                        $referencesForm = $profile->references_block ? json_decode($profile->references_block, true) : [];
                        if (!is_array($referencesForm)) {
                        $referencesForm = [];
                        }
                        if (old('references')) {
                        $referencesForm = old('references');
                        }
                        if (!count($referencesForm)) {
                        $referencesForm = [
                        ['name' => '', 'designation' => '', 'company' => '', 'mobile' => '', 'email' => '', 'location' => ''],
                        ];
                        }
                        @endphp

                        {{-- Career Objective --}}
                        <div class="rounded-xl border border-defaultborder/40 bg-light/30 dark:bg-black/10 p-5 mb-6">
                            <h5 class="font-semibold text-[15px] mb-3 flex items-center gap-2">
                                <i class="ri-target-line text-primary"></i> Career Objective
                            </h5>
                            <textarea name="career_objective" class="form-control" rows="4" placeholder="What are your career goals?">{{ old('career_objective', $profile->career_objective) }}</textarea>
                        </div>

                        {{-- Education rows --}}
                        <div class="rounded-xl border border-defaultborder/40 bg-light/30 dark:bg-black/10 p-5 mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="font-semibold text-[15px] mb-0 flex items-center gap-2">
                                    <i class="ri-graduation-cap-line text-primary"></i> Education
                                </h5>
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-outline-primary" id="add-education-row">
                                    <i class="ri-add-line me-1"></i> Add Degree
                                </button>
                            </div>
                            <div id="education-list" class="space-y-4">
                                @foreach($educationForm as $idx => $edu)
                                <div class="p-4 border border-defaultborder/30 rounded-lg education-row relative bg-white/50 dark:bg-white/5" data-index="{{ $idx }}">
                                    <button type="button" class="remove-education-row absolute top-3 right-3 inline-flex items-center justify-center h-8 w-8 rounded-full text-textmuted hover:bg-danger/10 hover:text-danger transition-colors">
                                        <i class="ri-close-line"></i>
                                    </button>
                                    <div class="grid grid-cols-12 gap-x-4 gap-y-3">
                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">Course & Major</label>
                                            <div class="relative">
                                                <i class="ri-book-2-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted/60 text-sm"></i>
                                                <input type="text" name="education[{{ $idx }}][course]" class="form-control ps-9" value="{{ $edu['course'] ?? '' }}" placeholder="e.g. BS Computer Science">
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">School / University</label>
                                            <div class="relative">
                                                <i class="ri-community-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted/60 text-sm"></i>
                                                <input type="text" name="education[{{ $idx }}][school]" class="form-control ps-9" value="{{ $edu['school'] ?? '' }}" placeholder="e.g. Harvard University">
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-7">
                                            <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">Location</label>
                                            <div class="relative">
                                                <i class="ri-map-pin-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted/60 text-sm"></i>
                                                <input type="text" name="education[{{ $idx }}][location]" class="form-control ps-9" value="{{ $edu['location'] ?? '' }}" placeholder="e.g. Cambridge, MA">
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-5">
                                            <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">Date Graduated</label>
                                            <div class="relative">
                                                <i class="ri-calendar-event-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted/60 text-sm"></i>
                                                <input type="text" name="education[{{ $idx }}][dates]" class="form-control ps-9" placeholder="e.g. 2016 – 2020" value="{{ $edu['dates'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded-xl border border-defaultborder/40 bg-light/30 dark:bg-black/10 p-5 mb-6">
                            @php $selectedExpertise = is_array($expertiseForm) ? $expertiseForm : []; @endphp
                            <h5 class="font-semibold text-[15px] mb-4 flex items-center gap-2 border-bottom border-defaultborder/20 pb-2">
                                <i class="ri-folder-settings-line text-primary"></i> Expertise / Categories
                            </h5>
                            <div class="grid grid-cols-12 gap-x-4 gap-y-2">
                                @foreach(($dropdownOptions['expertiseCategories'] ?? []) as $value => $label)
                                <div class="col-span-12 sm:col-span-6 lg:col-span-4">
                                    <label class="form-check inline-flex items-center gap-2 p-2 rounded-lg hover:bg-primary/5 cursor-pointer transition-colors w-full border border-transparent hover:border-primary/20">
                                        <input class="form-check-input" type="checkbox" name="expertise_categories[]" value="{{ $value }}" @if(in_array($value, $selectedExpertise)) checked @endif>
                                        <span class="text-sm font-medium">{{ $label }}</span>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Skills --}}
                        <div class="rounded-xl border border-defaultborder/40 bg-light/30 dark:bg-black/10 p-5 mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-semibold text-[15px] mb-0 flex items-center gap-2">
                                    <i class="ri-lightbulb-line text-primary"></i> Skills
                                </h5>
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-outline-primary" id="add-skill-row">
                                    <i class="ri-add-line me-1"></i> Add Skill
                                </button>
                            </div>
                            <p class="text-[11px] text-textmuted mb-3 italic">Add your professional skills (e.g., Schedule Management, Client Communication, CRM Tools)</p>
                            <div id="skills-list" class="space-y-2">
                                @foreach($skillsForm as $idx => $line)
                                <div class="flex gap-2 items-center skill-row" data-index="{{ $idx }}">
                                    <div class="relative flex-1">
                                        <i class="ri-checkbox-circle-fill absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-primary"></i>
                                        <input type="text" name="skills[{{ $idx }}]" class="form-control ps-8" value="{{ $line }}" placeholder="Skill name" required>
                                    </div>
                                    <button type="button" class="remove-skill-row inline-flex items-center justify-center h-9 w-9 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0" title="Remove">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Tools Used --}}
                        <div class="rounded-xl border border-defaultborder/40 bg-light/30 dark:bg-black/10 p-5 mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-semibold text-[15px] mb-0 flex items-center gap-2">
                                    <i class="ri-tools-line text-primary"></i> Tools Used
                                </h5>
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-outline-primary" id="add-tool-row">
                                    <i class="ri-add-line me-1"></i> Add Tool
                                </button>
                            </div>
                            <p class="text-[11px] text-textmuted mb-3 italic">Add tools and software you're proficient with (e.g., Slack, Trello, QuickBooks, GoHighLevel)</p>
                            <div id="tools-list" class="space-y-2">
                                @foreach($toolsUsedForm as $idx => $line)
                                <div class="flex gap-2 items-center tool-row" data-index="{{ $idx }}">
                                    <div class="relative flex-1">
                                        <i class="ri-settings-4-fill absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-primary focus-within:text-primary transition-colors"></i>
                                        <input type="text" name="tools_used[{{ $idx }}]" class="form-control ps-8" value="{{ $line }}" placeholder="Tool / Software" required>
                                    </div>
                                    <button type="button" class="remove-tool-row inline-flex items-center justify-center h-9 w-9 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0" title="Remove">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Languages bullets --}}
                        <div class="rounded-xl border border-defaultborder/40 bg-light/30 dark:bg-black/10 p-5 mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-semibold text-[15px] mb-0 flex items-center gap-2">
                                    <i class="ri-global-line text-primary"></i> Languages
                                </h5>
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-outline-primary" id="add-language-row">
                                    <i class="ri-add-line me-1"></i> Add Language
                                </button>
                            </div>
                            <div id="languages-list" class="space-y-2">
                                @foreach($languagesForm as $idx => $line)
                                <div class="flex gap-2 items-center language-row" data-index="{{ $idx }}">
                                    <div class="relative flex-1">
                                        <i class="ri-user-voice-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-sm pb-px"></i>
                                        <input type="text" name="languages[{{ $idx }}]" class="form-control ps-8" value="{{ $line }}" placeholder="e.g. English - Fluent" required>
                                    </div>
                                    <button type="button" class="remove-language-row inline-flex items-center justify-center h-9 w-9 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0" title="Remove">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Certifications rows --}}
                        <div class="rounded-xl border border-defaultborder/40 bg-light/30 dark:bg-black/10 p-5 mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-semibold text-[15px] mb-0 flex items-center gap-2">
                                    <i class="ri-award-line text-primary"></i> Certifications
                                </h5>
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-outline-primary" id="add-certification-row">
                                    <i class="ri-add-line me-1"></i> Add Certification
                                </button>
                            </div>
                            <div id="certifications-list" class="space-y-3">
                                @foreach($certificationsForm as $idx => $cert)
                                <div class="p-3 border border-dashed border-defaultborder/50 rounded-lg certification-row relative" data-index="{{ $idx }}">
                                    <button type="button" class="remove-certification-row absolute top-2 right-2 inline-flex items-center justify-center h-7 w-7 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors">
                                        <i class="ri-close-line"></i>
                                    </button>
                                    <div class="grid grid-cols-12 gap-3">
                                        <div class="col-span-12 md:col-span-7">
                                            <label class="form-label text-xs">Certificate / Course</label>
                                            <div class="relative">
                                                <i class="ri-medal-2-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                                <input type="text" name="certifications[{{ $idx }}][title]" class="form-control ps-8" value="{{ $cert['title'] ?? '' }}" placeholder="Certificate Name">
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-5">
                                            <label class="form-label text-xs">Provider / School</label>
                                            <div class="relative">
                                                <i class="ri-building-4-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-xs"></i>
                                                <input type="text" name="certifications[{{ $idx }}][provider]" class="form-control ps-8" value="{{ $cert['provider'] ?? '' }}" placeholder="Provider">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Achievements bullets --}}
                        <div class="rounded-xl border border-defaultborder/50 bg-light/40 dark:bg-white/5 p-6 mb-6 shadow-sm dark:shadow-none">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-semibold text-[15px] mb-0 flex items-center gap-2">
                                    <i class="ri-trophy-line text-primary"></i> Key Achievements
                                </h5>
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-outline-primary" id="add-achievement-row"><i class="ri-add-line me-1"></i> Add Bullet</button>
                            </div>
                            <div id="achievements-list" class="space-y-2">
                                @foreach($achievementsForm as $idx => $line)
                                <div class="flex gap-2 items-center achievement-row" data-index="{{ $idx }}">
                                    <div class="relative flex-1">
                                        <i class="ri-award-fill absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-primary"></i>
                                        <input type="text" name="achievements[{{ $idx }}]" class="form-control ps-8" value="{{ $line }}" placeholder="Your achievement here..." required>
                                    </div>
                                    <button type="button" class="remove-achievement-row inline-flex items-center justify-center h-9 w-9 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0" title="Remove">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Activities bullets --}}
                        <div class="rounded-xl border border-defaultborder/40 bg-light/30 dark:bg-black/10 p-5 mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-semibold text-[15px] mb-0 flex items-center gap-2">
                                    <i class="ri-heart-line text-primary"></i> Activities &amp; Interests
                                </h5>
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-outline-primary" id="add-activity-row">
                                    <i class="ri-add-line me-1"></i> Add Bullet
                                </button>
                            </div>
                            <div id="activities-list" class="space-y-2">
                                @foreach($activitiesForm as $idx => $line)
                                <div class="flex gap-2 items-center activity-row" data-index="{{ $idx }}">
                                    <div class="relative flex-1">
                                        <i class="ri-gamepad-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-sm"></i>
                                        <input type="text" name="activities[{{ $idx }}]" class="form-control ps-8" value="{{ $line }}" placeholder="e.g. Traveling, Photography" required>
                                    </div>
                                    <button type="button" class="remove-activity-row inline-flex items-center justify-center h-9 w-9 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0" title="Remove">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- References rows --}}
                        <div class="rounded-xl border border-defaultborder/40 bg-light/30 dark:bg-black/10 p-5 mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="font-semibold text-[15px] mb-0 flex items-center gap-2">
                                    <i class="ri-user-shared-line text-primary"></i> References
                                </h5>
                                <button type="button" class="ti-btn ti-btn-sm ti-btn-outline-primary" id="add-reference-row">
                                    <i class="ri-add-line me-1"></i> Add Reference
                                </button>
                            </div>
                            <div id="references-list" class="space-y-4">
                                @foreach($referencesForm as $idx => $ref)
                                <div class="p-4 border border-defaultborder/30 rounded-lg reference-row relative bg-white/50 dark:bg-white/5" data-index="{{ $idx }}">
                                    <button type="button" class="remove-reference-row absolute top-3 right-3 inline-flex items-center justify-center h-8 w-8 rounded-full text-textmuted hover:bg-danger/10 hover:text-danger transition-colors">
                                        <i class="ri-close-line"></i>
                                    </button>
                                    <div class="grid grid-cols-12 gap-x-4 gap-y-3">
                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">Name</label>
                                            <div class="relative"><i class="ri-user-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-sm"></i><input type="text" name="references[{{ $idx }}][name]" class="form-control ps-9" value="{{ $ref['name'] ?? '' }}"></div>
                                        </div>
                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">Designation</label>
                                            <div class="relative"><i class="ri-id-card-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-sm"></i><input type="text" name="references[{{ $idx }}][designation]" class="form-control ps-9" value="{{ $ref['designation'] ?? '' }}"></div>
                                        </div>
                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">Company</label>
                                            <div class="relative"><i class="ri-building-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-sm"></i><input type="text" name="references[{{ $idx }}][company]" class="form-control ps-9" value="{{ $ref['company'] ?? '' }}"></div>
                                        </div>
                                        <div class="col-span-12 md:col-span-6">
                                            <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">Mobile</label>
                                            <div class="relative"><i class="ri-phone-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-sm"></i><input type="text" name="references[{{ $idx }}][mobile]" class="form-control ps-9" value="{{ $ref['mobile'] ?? '' }}"></div>
                                        </div>
                                        <div class="col-span-12">
                                            <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">Email</label>
                                            <div class="relative"><i class="ri-mail-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted text-sm"></i><input type="email" name="references[{{ $idx }}][email]" class="form-control ps-9" value="{{ $ref['email'] ?? '' }}"></div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- STEP 3: Experience overview --}}
                    <div class="wizard-step wizard-step-3 transition-opacity duration-200" style="display: none;">
                        @php
                        $experience = $profile->experience_overview ? json_decode($profile->experience_overview, true) : null;
                        if (!is_array($experience)) {
                        $experience = [];
                        }
                        $expResponsibilities = $experience['responsibilities'] ?? [];
                        if (!is_array($expResponsibilities)) {
                        $expResponsibilities = [];
                        }
                        $expResponsibilities = old('experience_responsibilities', $expResponsibilities);
                        if (!is_array($expResponsibilities) || !count($expResponsibilities)) {
                        $expResponsibilities = [''];
                        }
                        @endphp

                        <div class="rounded-xl border border-defaultborder/50 bg-light/40 dark:bg-white/5 p-6 mb-6 shadow-sm dark:shadow-none">
                            <h5 class="font-semibold text-[15px] mb-4 flex items-center gap-2 border-bottom border-defaultborder/20 pb-2">
                                <i class="ri-briefcase-4-line text-primary"></i> Experience Overview
                            </h5>

                        @php
                            $expStartVal = old('experience_start', $experience['start_date'] ?? '');
                            $expEndVal = old('experience_end', $experience['end_date'] ?? '');
                            if ($expStartVal && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $expStartVal)) { $expStartVal = ''; }
                            if ($expEndVal && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $expEndVal)) { $expEndVal = ''; }
                            $expCurrent = old('experience_current', isset($experience['end_date']) && (empty($experience['end_date']) || strtolower((string)$experience['end_date']) === 'present'));
                        @endphp
                        <div class="grid grid-cols-12 gap-x-4 gap-y-4 mb-4">
                            <div class="col-span-12">
                                <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">Position / Title <span class="text-danger">*</span></label>
                                <div class="relative">
                                    <i class="ri-user-settings-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted/60 text-sm"></i>
                                    <input type="text" name="experience_position" class="form-control ps-9" list="candidate_position_list" value="{{ old('experience_position', $experience['position'] ?? '') }}" placeholder="Select or type your job title" required>
                                </div>
                                <datalist id="candidate_position_list">
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
                                <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">Company Name <span class="text-danger">*</span></label>
                                <div class="relative">
                                    <i class="ri-building-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted/60 text-sm"></i>
                                    <input type="text" name="experience_company" class="form-control ps-9" value="{{ old('experience_company', $experience['company'] ?? '') }}" placeholder="e.g. Acme Corp" required>
                                </div>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">Location <span class="text-danger">*</span></label>
                                <div class="relative">
                                    <i class="ri-map-pin-line absolute left-3 top-1/2 -translate-y-1/2 text-textmuted/60 text-sm"></i>
                                    <input type="text" name="experience_location" class="form-control ps-9" value="{{ old('experience_location', $experience['location'] ?? '') }}" placeholder="e.g. Remote / City" required>
                                </div>
                            </div>
                            <div class="col-span-6">
                                <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="experience_start" id="profile_experience_start" class="form-control" value="{{ $expStartVal }}" max="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-span-6">
                                <label class="form-label text-xs uppercase font-bold text-textmuted mb-1">End Date</label>
                                <input type="date" name="experience_end" id="profile_experience_end" class="form-control" value="{{ $expCurrent ? '' : $expEndVal }}" max="{{ date('Y-m-d') }}" {{ $expCurrent ? 'disabled' : '' }}>
                            </div>
                            <div class="col-span-12">
                                <label class="inline-flex items-center gap-2 mt-1 cursor-pointer text-sm text-textmuted">
                                    <input type="checkbox" name="experience_current" id="profile_experience_current" value="1" {{ $expCurrent ? 'checked' : '' }}>
                                    <span class="text-xs">I currently work here</span>
                                </label>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-defaultborder/20 mt-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="form-label text-xs uppercase font-bold text-textmuted mb-0">Key Responsibilities <span class="text-danger">*</span></label>
                                <button type="button" class="ti-btn ti-btn-xs ti-btn-outline-primary" id="add-experience-responsibility">
                                    <i class="ri-add-line me-1"></i> Add Bullet
                                </button>
                            </div>
                            <div id="experience-responsibilities-list" class="space-y-2">
                                @foreach($expResponsibilities as $idx => $resp)
                                <div class="flex gap-2 items-center experience-resp-row" data-index="{{ $idx }}">
                                    <div class="relative flex-1">
                                        <i class="ri-checkbox-blank-circle-fill absolute left-3 top-1/2 -translate-y-1/2 text-[6px] text-primary"></i>
                                        <input type="text" name="experience_responsibilities[{{ $idx }}]" class="form-control ps-8" value="{{ $resp }}" placeholder="e.g. Managed team of 5 and improved efficiency by 20%" required>
                                    </div>
                                    <button type="button" class="remove-experience-resp-row inline-flex items-center justify-center h-9 w-9 rounded-lg text-textmuted hover:bg-danger/10 hover:text-danger transition-colors shrink-0" title="Remove">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        </div>
                    </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-between items-center gap-4 mt-10 pt-8 border-t border-defaultborder/50 bg-light/20 dark:bg-transparent -mx-4 sm:-mx-6 px-4 sm:px-6 py-4 rounded-b-xl">
                <div>
                    <button type="button" class="ti-btn ti-btn-outline-light" id="wizard-prev" style="display: none;">
                        <i class="ri-arrow-left-line me-1"></i> Previous
                    </button>
                </div>
                <div class="flex flex-wrap gap-3 justify-end">
                    <a href="{{ route('applicant.dashboard') }}" class="ti-btn ti-btn-outline-light order-3 sm:order-1">
                        Cancel
                    </a>
                    <button type="button" class="ti-btn ti-btn-primary shadow-sm" id="wizard-next">
                        Next <i class="ri-arrow-right-line ms-1"></i>
                    </button>
                    <button type="submit" class="ti-btn ti-btn-primary shadow-sm" id="wizard-submit" style="display: none;">
                        <i class="ri-check-line me-1"></i> Save Profile
                    </button>
                </div>
            </div>
            </form>
        </div>
        </div>
        </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const wizard = document.getElementById('candidate-profile-wizard');
                if (!wizard) return;

                let currentStep = 1;
                const totalSteps = 3;

                const stepEls = {};
                const tabEls = {};
                const circleEls = {};
                const labelEls = {};
                const progressBar = document.getElementById('wizard-progress-bar');
                for (let i = 1; i <= totalSteps; i++) {
                    stepEls[i] = wizard.querySelector('.wizard-step-' + i);
                    tabEls[i] = wizard.querySelector('.wizard-tab-' + i);
                    if (tabEls[i]) {
                        circleEls[i] = tabEls[i].querySelector('.wizard-circle');
                        labelEls[i] = tabEls[i].querySelector('span:last-child');
                    }
                }

                const prevBtn = document.getElementById('wizard-prev');
                const nextBtn = document.getElementById('wizard-next');
                const submitBtn = document.getElementById('wizard-submit');
                const stepTitleEl = document.getElementById('wizard-step-title');
                const stepDescEl = document.getElementById('wizard-step-desc');
                const stepTitles = {
                    1: 'Step 1 of 3 — Overview',
                    2: 'Step 2 of 3 — Profile Info',
                    3: 'Step 3 of 3 — Experience',
                };
                const stepDescriptions = {
                    1: 'Basic info, summary, and Resume',
                    2: 'Education, skills, certifications, and references',
                    3: 'Work experience and responsibilities',
                };

                function updateWizard() {
                    for (let i = 1; i <= totalSteps; i++) {
                        if (stepEls[i]) {
                            stepEls[i].style.display = i === currentStep ? '' : 'none';
                        }

                        // Tab label and circle styling
                        if (tabEls[i]) {
                            const isActive = i === currentStep;
                            const isCompleted = i < currentStep;
                            tabEls[i].classList.toggle('text-primary', isActive || isCompleted);
                            tabEls[i].classList.toggle('text-textmuted', !isActive && !isCompleted);
                            if (labelEls[i]) {
                                labelEls[i].classList.toggle('text-primary', isActive || isCompleted);
                                labelEls[i].classList.toggle('text-textmuted', !isActive && !isCompleted);
                            }
                        }
                        if (circleEls[i]) {
                            const isFilled = i <= currentStep;
                            circleEls[i].classList.toggle('bg-primary', isFilled);
                            circleEls[i].classList.toggle('text-white', isFilled);
                            circleEls[i].classList.toggle('border-primary', isFilled);
                            circleEls[i].classList.toggle('border-defaultborder', !isFilled);
                            circleEls[i].classList.toggle('text-textmuted', !isFilled);
                        }
                    }

                    if (prevBtn) prevBtn.style.display = currentStep > 1 ? '' : 'none';
                    if (nextBtn) nextBtn.style.display = currentStep < totalSteps ? '' : 'none';
                    if (submitBtn) submitBtn.style.display = currentStep === totalSteps ? '' : 'none';

                    if (stepTitleEl && stepTitles[currentStep]) {
                        stepTitleEl.textContent = stepTitles[currentStep];
                    }
                    if (stepDescEl && stepDescriptions[currentStep]) {
                        stepDescEl.textContent = stepDescriptions[currentStep];
                    }

                    var activeStepEl = stepEls[currentStep];
                    if (activeStepEl) {
                        activeStepEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }

                    if (progressBar) {
                        // 1 step = 1/3, 2 steps = 2/3, 3 steps = full line
                        const progress = currentStep / totalSteps; // 1/3 to 1
                        progressBar.style.width = (progress * 100) + '%';
                    }
                }

                if (prevBtn) {
                    prevBtn.addEventListener('click', function() {
                        if (currentStep > 1) {
                            currentStep--;
                            updateWizard();
                        }
                    });
                }

                // Skills bullets
                const skillsList = document.getElementById('skills-list');
                const addSkillBtn = document.getElementById('add-skill-row');
                if (skillsList && addSkillBtn) {
                    addSkillBtn.addEventListener('click', function() {
                        const idx = nextIndex('#skills-list', '.skill-row');
                        const row = document.createElement('div');
                        row.innerHTML = createSkillRow(idx);
                        skillsList.appendChild(row);
                        refreshRemoveButtons('#skills-list', '.skill-row', '.remove-skill-row');
                    });

                    skillsList.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-skill-row')) {
                            const row = e.target.closest('.skill-row');
                            if (row && skillsList.children.length > 1) {
                                row.remove();
                                refreshRemoveButtons('#skills-list', '.skill-row', '.remove-skill-row');
                            }
                        }
                    });
                }

                // Tools Used bullets
                const toolsList = document.getElementById('tools-list');
                const addToolBtn = document.getElementById('add-tool-row');
                if (toolsList && addToolBtn) {
                    addToolBtn.addEventListener('click', function() {
                        const idx = nextIndex('#tools-list', '.tool-row');
                        const row = document.createElement('div');
                        row.innerHTML = createToolRow(idx);
                        toolsList.appendChild(row);
                        refreshRemoveButtons('#tools-list', '.tool-row', '.remove-tool-row');
                    });

                    toolsList.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-tool-row')) {
                            const row = e.target.closest('.tool-row');
                            if (row && toolsList.children.length > 1) {
                                row.remove();
                                refreshRemoveButtons('#tools-list', '.tool-row', '.remove-tool-row');
                            }
                        }
                    });
                }

                // Languages bullets
                const languagesList = document.getElementById('languages-list');
                const addLanguageBtn = document.getElementById('add-language-row');
                if (languagesList && addLanguageBtn) {
                    addLanguageBtn.addEventListener('click', function() {
                        const idx = nextIndex('#languages-list', '.language-row');
                        const row = document.createElement('div');
                        row.innerHTML = createLanguageRow(idx);
                        languagesList.appendChild(row);
                        refreshRemoveButtons('#languages-list', '.language-row', '.remove-language-row');
                    });

                    languagesList.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-language-row')) {
                            const row = e.target.closest('.language-row');
                            if (row && languagesList.children.length > 1) {
                                row.remove();
                                refreshRemoveButtons('#languages-list', '.language-row', '.remove-language-row');
                            }
                        }
                    });
                }

                function validateStep(step) {
                    const stepEl = stepEls[step];
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
                            const container = field.closest('.col-span-12, .col-span-6, .col-span-4, .mb-4');
                            const label = container?.querySelector('.form-label');
                            const fieldName = label ? label.textContent.replace('*', '').replace(/\s+/g, ' ').trim() : field.name;
                            if (!emptyFields.includes(fieldName)) {
                                emptyFields.push(fieldName);
                            }
                            field.classList.add('border-danger');
                        } else {
                            field.classList.remove('border-danger');
                        }
                    });

                    if (emptyFields.length > 0) {
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Required Fields',
                                html: '<p class="mb-2 text-sm text-textmuted">Please complete the following fields before proceeding:</p><ul class="text-left list-disc pl-5 text-xs text-danger font-medium">' +
                                    emptyFields.map(f => '<li>' + f + '</li>').join('') + '</ul>',
                                confirmButtonColor: '#4f46e5',
                                customClass: {
                                    popup: 'premium-swal-popup'
                                }
                            });
                        }
                        return false;
                    }
                    return true;
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', function() {
                        if (validateStep(currentStep)) {
                            if (currentStep < totalSteps) {
                                currentStep++;
                                updateWizard();
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            }
                        }
                    });
                }

                for (let i = 1; i <= totalSteps; i++) {
                    if (tabEls[i]) {
                        tabEls[i].addEventListener('click', function() {
                            if (i < currentStep) {
                                // Going back is always allowed
                                currentStep = i;
                                updateWizard();
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            } else if (i > currentStep) {
                                // Going forward requires validation of all steps in between
                                for (let s = currentStep; s < i; s++) {
                                    if (!validateStep(s)) return;
                                }
                                currentStep = i;
                                updateWizard();
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            }
                        });
                    }
                }

                updateWizard();

                // ---------- Dynamic rows helpers ----------
                function nextIndex(containerSelector, rowSelector) {
                    const container = document.querySelector(containerSelector);
                    if (!container) return 0;
                    const rows = container.querySelectorAll(rowSelector);
                    let maxIndex = -1;
                    rows.forEach(row => {
                        const idx = parseInt(row.getAttribute('data-index') || '0', 10);
                        if (!isNaN(idx) && idx > maxIndex) maxIndex = idx;
                    });
                    return maxIndex + 1;
                }

                function refreshRemoveButtons(containerSelector, rowSelector, removeBtnSelector) {
                    const container = document.querySelector(containerSelector);
                    if (!container) return;
                    const rows = container.querySelectorAll(rowSelector);
                    rows.forEach((row, index) => {
                        const btn = row.querySelector(removeBtnSelector);
                        if (!btn) return;
                        if (index === 0) {
                            btn.classList.add('hidden');
                        } else {
                            btn.classList.remove('hidden');
                        }
                    });
                }

                // Education rows
                const educationList = document.getElementById('education-list');
                const addEducationBtn = document.getElementById('add-education-row');
                if (educationList && addEducationBtn) {
                    addEducationBtn.addEventListener('click', function() {
                        const idx = nextIndex('#education-list', '.education-row');
                        const row = document.createElement('div');
                        row.innerHTML = createEducationRow(idx);
                        educationList.appendChild(row);
                        refreshRemoveButtons('#education-list', '.education-row', '.remove-education-row');
                    });

                    educationList.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-education-row')) {
                            const row = e.target.closest('.education-row');
                            if (row && educationList.children.length > 1) {
                                row.remove();
                                refreshRemoveButtons('#education-list', '.education-row', '.remove-education-row');
                            }
                        }
                    });
                }

                // Certifications rows
                const certList = document.getElementById('certifications-list');
                const addCertBtn = document.getElementById('add-certification-row');
                if (certList && addCertBtn) {
                    addCertBtn.addEventListener('click', function() {
                        const idx = nextIndex('#certifications-list', '.certification-row');
                        const row = document.createElement('div');
                        row.innerHTML = createCertificationRow(idx);
                        certList.appendChild(row);
                        refreshRemoveButtons('#certifications-list', '.certification-row', '.remove-certification-row');
                    });

                    certList.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-certification-row')) {
                            const row = e.target.closest('.certification-row');
                            if (row && certList.children.length > 1) {
                                row.remove();
                                refreshRemoveButtons('#certifications-list', '.certification-row', '.remove-certification-row');
                            }
                        }
                    });
                }

                // Achievements bullets
                const achievementsList = document.getElementById('achievements-list');
                const addAchievementBtn = document.getElementById('add-achievement-row');
                if (achievementsList && addAchievementBtn) {
                    addAchievementBtn.addEventListener('click', function() {
                        const idx = nextIndex('#achievements-list', '.achievement-row');
                        const row = document.createElement('div');
                        row.innerHTML = createAchievementRow(idx);
                        achievementsList.appendChild(row);
                        refreshRemoveButtons('#achievements-list', '.achievement-row', '.remove-achievement-row');
                    });

                    achievementsList.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-achievement-row')) {
                            const row = e.target.closest('.achievement-row');
                            if (row && achievementsList.children.length > 1) {
                                row.remove();
                                refreshRemoveButtons('#achievements-list', '.achievement-row', '.remove-achievement-row');
                            }
                        }
                    });
                }

                // Activities bullets
                const activitiesList = document.getElementById('activities-list');
                const addActivityBtn = document.getElementById('add-activity-row');
                if (activitiesList && addActivityBtn) {
                    addActivityBtn.addEventListener('click', function() {
                        const idx = nextIndex('#activities-list', '.activity-row');
                        const row = document.createElement('div');
                        row.innerHTML = createActivityRow(idx);
                        activitiesList.appendChild(row);
                        refreshRemoveButtons('#activities-list', '.activity-row', '.remove-activity-row');
                    });

                    activitiesList.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-activity-row')) {
                            const row = e.target.closest('.activity-row');
                            if (row && activitiesList.children.length > 1) {
                                row.remove();
                                refreshRemoveButtons('#activities-list', '.activity-row', '.remove-activity-row');
                            }
                        }
                    });
                }

                // References rows
                const referencesList = document.getElementById('references-list');
                const addReferenceBtn = document.getElementById('add-reference-row');
                if (referencesList && addReferenceBtn) {
                    addReferenceBtn.addEventListener('click', function() {
                        const idx = nextIndex('#references-list', '.reference-row');
                        const row = document.createElement('div');
                        row.innerHTML = createReferenceRow(idx);
                        referencesList.appendChild(row);
                        refreshRemoveButtons('#references-list', '.reference-row', '.remove-reference-row');
                    });

                    referencesList.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-reference-row')) {
                            const row = e.target.closest('.reference-row');
                            if (row && referencesList.children.length > 1) {
                                row.remove();
                                refreshRemoveButtons('#references-list', '.reference-row', '.remove-reference-row');
                            }
                        }
                    });
                }

                // Experience responsibilities bullets
                const expRespList = document.getElementById('experience-responsibilities-list');
                const addExpRespBtn = document.getElementById('add-experience-responsibility');
                if (expRespList && addExpRespBtn) {
                    addExpRespBtn.addEventListener('click', function() {
                        const idx = nextIndex('#experience-responsibilities-list', '.experience-resp-row');
                        const row = document.createElement('div');
                        row.innerHTML = createExperienceRespRow(idx);
                        expRespList.appendChild(row);
                        refreshRemoveButtons('#experience-responsibilities-list', '.experience-resp-row', '.remove-experience-resp-row');
                    });

                    expRespList.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-experience-resp-row')) {
                            const row = e.target.closest('.experience-resp-row');
                            if (row && expRespList.children.length > 1) {
                                row.remove();
                                refreshRemoveButtons('#experience-responsibilities-list', '.experience-resp-row', '.remove-experience-resp-row');
                            }
                        }
                    });
                }

                // Ensure initial rows do not show remove buttons when they are the only row
                refreshRemoveButtons('#education-list', '.education-row', '.remove-education-row');
                refreshRemoveButtons('#certifications-list', '.certification-row', '.remove-certification-row');
                refreshRemoveButtons('#achievements-list', '.achievement-row', '.remove-achievement-row');
                refreshRemoveButtons('#activities-list', '.activity-row', '.remove-activity-row');
                refreshRemoveButtons('#references-list', '.reference-row', '.remove-reference-row');
                refreshRemoveButtons('#experience-responsibilities-list', '.experience-resp-row', '.remove-experience-resp-row');
                refreshRemoveButtons('#skills-list', '.skill-row', '.remove-skill-row');
                refreshRemoveButtons('#languages-list', '.language-row', '.remove-language-row');
            });

            // CV upload: trigger button and show selected file name
            document.addEventListener('DOMContentLoaded', function() {
                var cvInput = document.getElementById('cv_file');
                var cvTrigger = document.getElementById('cv_upload_trigger');
                var cvFileName = document.getElementById('cv_file_name');
                if (cvTrigger && cvInput) {
                    cvTrigger.addEventListener('click', function() { cvInput.click(); });
                }
                if (cvInput && cvFileName) {
                    cvInput.addEventListener('change', function() {
                        var name = this.files && this.files[0] ? this.files[0].name : '';
                        cvFileName.textContent = name ? '\u2713 ' + name : '';
                        cvFileName.classList.toggle('hidden', !name);
                    });
                }

                var expCurrent = document.getElementById('profile_experience_current');
                var expEnd = document.getElementById('profile_experience_end');
                if (expCurrent && expEnd) {
                    expCurrent.addEventListener('change', function() {
                        expEnd.disabled = this.checked;
                        if (this.checked) expEnd.value = '';
                    });
                }
            });

            // Resume remove confirmation
            function confirmRemoveCV() {
                if (window.Swal) {
                    Swal.fire({
                        title: 'Remove Resume?',
                        text: 'Are you sure you want to remove your uploaded resume?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, remove it'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('remove_cv').value = '1';
                            document.querySelector('form').submit();
                        }
                    });
                } else {
                    if (confirm('Are you sure you want to remove your uploaded resume?')) {
                        document.getElementById('remove_cv').value = '1';
                        document.querySelector('form').submit();
                    }
                }
            }

            // Form validation with SweetAlert notification
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('candidate-profile-wizard');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const requiredFields = form.querySelectorAll('[required]');
                        const emptyFields = [];

                        requiredFields.forEach(function(field) {
                            let isEmpty = false;

                            if (field.tagName === 'SELECT') {
                                isEmpty = !field.value || field.value === '';
                            } else {
                                isEmpty = !field.value || field.value.trim() === '';
                            }

                            if (isEmpty) {
                                const container = field.closest('.col-span-12, .col-span-6, .col-span-4, .mb-4');
                                const label = container?.querySelector('.form-label');
                                const fieldName = label ? label.textContent.replace('*', '').replace(/\s+/g, ' ').trim() : field.name;
                                if (!emptyFields.includes(fieldName)) {
                                    emptyFields.push(fieldName);
                                }
                            }
                        });

                        // Salary scale range validation
                        const minSal = form.querySelector('[name="expected_salary_min"]');
                        const maxSal = form.querySelector('[name="expected_salary_max"]');
                        if (minSal && maxSal && minSal.value && maxSal.value) {
                            const minVal = parseFloat(minSal.value);
                            const maxVal = parseFloat(maxSal.value);
                            if (minVal > maxVal) {
                                e.preventDefault();
                                e.stopPropagation();
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

                        // Experience date range validation
                        const expS = form.querySelector('[name="experience_start"]');
                        const expE = form.querySelector('[name="experience_end"]');
                        const expC = form.querySelector('[name="experience_current"]');
                        if (expS && expE && expS.value && expE.value && (!expC || !expC.checked)) {
                            if (expS.value > expE.value) {
                                e.preventDefault();
                                e.stopPropagation();
                                expS.classList.add('border-danger');
                                expE.classList.add('border-danger');
                                if (window.Swal) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Invalid Experience Dates',
                                        text: 'Start date cannot be later than end date.',
                                        confirmButtonColor: '#4f46e5',
                                    });
                                }
                                return false;
                            }
                        }

                        if (emptyFields.length > 0) {
                            e.preventDefault();
                            e.stopPropagation();

                            if (window.Swal) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Required Fields Missing',
                                    html: '<p class="mb-2">Please fill in the following fields:</p><ul class="text-left list-disc pl-5">' +
                                        emptyFields.map(f => '<li>' + f + '</li>').join('') + '</ul>',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Find first empty required field
                                    const firstEmpty = Array.from(requiredFields).find(f => {
                                        if (f.tagName === 'SELECT') return !f.value || f.value === '';
                                        return !f.value || f.value.trim() === '';
                                    });

                                    if (firstEmpty) {
                                        // Find which step the field is in
                                        const step = firstEmpty.closest('.wizard-step');
                                        if (step) {
                                            const stepMatch = step.className.match(/wizard-step-(\d+)/);
                                            if (stepMatch) {
                                                const tabBtn = document.querySelector('.wizard-tab-' + stepMatch[1]);
                                                if (tabBtn) tabBtn.click();
                                            }
                                        }
                                        setTimeout(() => {
                                            firstEmpty.focus();
                                            firstEmpty.scrollIntoView({
                                                behavior: 'smooth',
                                                block: 'center'
                                            });
                                        }, 300);
                                    }
                                });
                            } else {
                                alert('Please fill in all required fields: ' + emptyFields.join(', '));
                            }
                            return false;
                        }
                    });
 
                    // Real-time salary validation
                    const minScaleSal = form.querySelector('[name="expected_salary_min"]');
                    const maxScaleSal = form.querySelector('[name="expected_salary_max"]');
                    if (minScaleSal && maxScaleSal) {
                        const validateSalaryRange = () => {
                            const minVal = parseFloat(minScaleSal.value);
                            const maxVal = parseFloat(maxScaleSal.value);
                            if (!isNaN(minVal) && !isNaN(maxVal) && minVal > maxVal) {
                                minScaleSal.classList.add('border-danger');
                                maxScaleSal.classList.add('border-danger');
                            } else {
                                minScaleSal.classList.remove('border-danger');
                                maxScaleSal.classList.remove('border-danger');
                            }
                        };
                        minScaleSal.addEventListener('input', validateSalaryRange);
                        maxScaleSal.addEventListener('input', validateSalaryRange);
                    }

                    // Real-time experience date validation
                    const expStart = form.querySelector('[name="experience_start"]');
                    const expEnd = form.querySelector('[name="experience_end"]');
                    const expCurrentCheck = form.querySelector('[name="experience_current"]');

                    if (expStart && expEnd) {
                        const validateExpDates = () => {
                            const s = expStart.value;
                            const e = expEnd.value;
                            const current = expCurrentCheck ? expCurrentCheck.checked : false;
                            if (s && e && s > e && !current) {
                                expStart.classList.add('border-danger');
                                expEnd.classList.add('border-danger');
                            } else {
                                expStart.classList.remove('border-danger');
                                expEnd.classList.remove('border-danger');
                            }
                        };
                        expStart.addEventListener('change', validateExpDates);
                        expEnd.addEventListener('change', validateExpDates);
                        if (expCurrentCheck) expCurrentCheck.addEventListener('change', validateExpDates);
                    }
                }
            });
        </script>

    </x-app-layout>
