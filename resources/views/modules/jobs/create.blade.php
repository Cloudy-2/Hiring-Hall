<x-app-layout>

    <x-slot name="pageTitle">Post a Job</x-slot>
    <x-slot name="url_1">{"link": "/jobs", "text": "Job Listing"}</x-slot>
    <x-slot name="active"> Post a Job</x-slot>

    @include('employers.partials.employer-styles')

    <style>
        .pj-wizard-wrap {
            max-width: 860px;
            margin: 0 auto;
        }

        /* Autofill-locked select */
        .pj-af-locked {
            position: relative;
        }

        .pj-af-locked .form-select {
            padding-right: 2.6rem !important;
            background-color: #f5f7ff !important;
            border-color: #c7d2fe !important;
            color: #4338ca !important;
            cursor: default;
        }

        [data-theme-mode="dark"] .pj-af-locked .form-select,
        .dark .pj-af-locked .form-select {
            background-color: rgba(99, 102, 241, 0.1) !important;
            border-color: rgba(99, 102, 241, 0.35) !important;
            color: #a5b4fc !important;
        }

        .pj-af-lock-badge {
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 11px;
            color: #6366f1;
            pointer-events: none;
            display: none;
        }

        .pj-af-locked .pj-af-lock-badge {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .pj-af-lock-badge i {
            font-size: 12px;
        }

        /* Step indicator */
        .pj-steps {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 0;
            position: relative;
            margin-bottom: 2rem;
        }

        .pj-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            max-width: 160px;
        }

        .pj-step-line {
            flex: 1;
            height: 2px;
            margin-top: 19px;
            background: #e5e7eb;
            transition: background .3s;
        }

        .pj-step-line.done {
            background: var(--primary-color, #6366f1);
        }

        .pj-step-dot {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
            background: #fff;
            color: #9ca3af;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            transition: all .3s;
            position: relative;
            z-index: 1;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
        }

        .pj-step-dot.active {
            border-color: var(--primary-color, #6366f1);
            background: var(--primary-color, #6366f1);
            color: #fff;
            box-shadow: 0 0 0 6px rgba(99, 102, 241, 0.12);
            transform: scale(1.1);
        }

        .pj-step-dot.done {
            border-color: var(--primary-color, #6366f1);
            background: var(--primary-color, #6366f1);
            color: #fff;
        }

        .pj-step-lbl {
            font-size: 11px;
            font-weight: 600;
            margin-top: 8px;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #9ca3af;
            transition: color .3s;
        }

        .pj-step-lbl.active,
        .pj-step-lbl.done {
            color: var(--primary-color, #6366f1);
        }

        [data-theme-mode="dark"] .pj-step-dot,
        .dark .pj-step-dot {
            background: #1e293b;
            border-color: rgba(255, 255, 255, 0.12);
        }

        [data-theme-mode="dark"] .pj-step-line,
        .dark .pj-step-line {
            background: rgba(255, 255, 255, 0.08);
        }

        /* Section cards */
        .pj-card {
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            margin-bottom: 1.25rem;
            overflow: hidden;
        }

        .pj-card-head {
            padding: 1rem 1.25rem 0.75rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pj-card-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .pj-card-title {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }

        .pj-card-sub {
            font-size: 12px;
            color: #6b7280;
            margin-top: 1px;
        }

        .pj-card-body {
            padding: 1.25rem;
        }

        [data-theme-mode="dark"] .pj-card,
        .dark .pj-card {
            background: #1E2023;
            border-color: rgba(255, 255, 255, 0.07);
        }

        [data-theme-mode="dark"] .pj-card-head,
        .dark .pj-card-head {
            border-color: rgba(255, 255, 255, 0.06);
        }

        [data-theme-mode="dark"] .pj-card-title,
        .dark .pj-card-title {
            color: #e5e7eb;
        }

        /* Logo upload */
        .pj-logo-card {
            border: 2px dashed #e5e7eb;
            border-radius: 14px;
            padding: 1.5rem;
            text-align: center;
            transition: border-color .2s;
        }

        .pj-logo-card:hover {
            border-color: var(--primary-color, #6366f1);
        }

        [data-theme-mode="dark"] .pj-logo-card,
        .dark .pj-logo-card {
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* Uniform input height */
        .pj-wizard-wrap .form-control,
        .pj-wizard-wrap .form-select {
            height: 42px !important;
            min-height: 42px !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            line-height: 42px !important;
            font-size: 13.5px !important;
        }

        .pj-wizard-wrap textarea.form-control {
            height: auto !important;
            min-height: 120px !important;
            line-height: 1.6 !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }

        .pj-wizard-wrap .form-control-sm,
        .pj-wizard-wrap .form-select-sm {
            height: 36px !important;
            min-height: 36px !important;
            line-height: 36px !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            font-size: 13px !important;
        }

        /* Highlight row */
        .pj-hl-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .pj-hl-row:last-child {
            border-bottom: none;
        }

        [data-theme-mode="dark"] .pj-hl-row,
        .dark .pj-hl-row {
            border-color: rgba(255, 255, 255, 0.06);
        }

        .pj-hl-lbl {
            min-width: 130px;
            font-size: 13px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pj-hl-controls {
            margin-left: auto;
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Step transition animation */
        @keyframes pj-step-in {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pj-step-enter {
            animation: pj-step-in 0.32s cubic-bezier(.25, .8, .25, 1) both;
        }

        /* Disabled navigation button */
        .ti-btn:disabled,
        .ti-btn[disabled] {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            pointer-events: none !important;
        }

        /* TomSelect Styling - Light Mode */
        .ts-wrapper.single .ts-control {
            border: 1px solid #d1d5db !important;
            border-radius: 8px !important;
            padding: 10px 12px !important;
            background: white !important;
            font-size: 14px !important;
            color: #1f2937 !important;
            min-height: 40px !important;
        }

        .ts-wrapper.single .ts-control input {
            color: #1f2937 !important;
        }

        .ts-wrapper.single .ts-control:focus-within {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
        }

        .ts-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 8px !important;
            background: white !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }

        .ts-dropdown .ts-dropdown-content {
            max-height: 300px;
        }

        .ts-dropdown .option {
            color: #1f2937 !important;
            padding: 8px 10px !important;
        }

        .ts-dropdown .option.selected {
            background: #e0e7ff !important;
            color: #312e81 !important;
        }

        .ts-dropdown .option:hover {
            background: #f3f4f6 !important;
            color: #1f2937 !important;
        }

        /* TomSelect Styling - Dark Mode */
        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-wrapper.single .ts-control {
            background: #1e293b !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            color: #e2e8f0 !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-wrapper.single .ts-control input {
            color: #e2e8f0 !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-wrapper.single .ts-control:focus-within {
            border-color: #818cf8 !important;
            box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.1) !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-dropdown {
            background: #0f172a !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-dropdown .option {
            color: #cbd5e1 !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-dropdown .option.selected {
            background: #312e81 !important;
            color: #a5b4fc !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-dropdown .option:hover {
            background: #1e293b !important;
            color: #e2e8f0 !important;
        }

        .ts-dropdown .option.disabled {
            opacity: 0.4;
            pointer-events: none;
        }

        .ts-dropdown input::placeholder {
            color: #9ca3af !important;
        }

        :is([data-theme-mode="dark"], [data-bs-theme="dark"], .dark, html.dark) .ts-dropdown input::placeholder {
            color: #64748b !important;
        }
    </style>

    <div class="grid grid-cols-12 gap-x-6">
        {{-- ═══ Page Header ═══ --}}
        <x-modern-header
            chip="Job Creation"
            title="Post a New Job"
            desc="Create a compelling job posting to find your next top talent. Our <b>automated matching</b> helps you connect with the right applicants faster."
            :container="false"
            headerClass="col-span-12"
        >
            <x-slot name="actions">
                <a href="{{ route('employer.jobs.index') }}" class="cd-btn cd-btn-primary"><i class="ri-list-check"></i> My Job Posts</a>
                <a href="{{ route('employer.dashboard') }}" class="cd-btn cd-btn-outline"><i class="ri-dashboard-line"></i> Dashboard</a>
            </x-slot>
        </x-modern-header>

        <div class="xxl:col-span-12 col-span-12">
            <div class="box" id="job-post-wizard">
                <div class="box-body">
                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="list-disc ms-4">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php
                        $template = $templateJob ?? null;
                        $employmentValue = old('employment_type', $template?->employment_type);
                        $remoteValue = old('remote_type', $template?->remote_type);
                        $salaryMinValue = old('salary_min', $template?->salary_min);
                        $salaryMaxValue = old('salary_max', $template?->salary_max);
                        $postedAtValue = old('posted_at', now()->format('Y-m-d'));
                        $closesAtValue = old('closes_at');
                    @endphp

                    @if(!($canPostJobs ?? true))
                        <div class="alert alert-warning mb-4 text-sm">
                            <i class="ri-alert-line me-1"></i>
                            <strong>Company verification required.</strong>
                            @if(($hasAnyCompany ?? false) === false)
                                You need to register a company before you can post a job.
                            @elseif(($hasPendingCompanies ?? false) === true)
                                Your company verification is currently pending. You can’t post jobs until it’s approved.
                            @elseif(($hasRejectedCompanies ?? false) === true)
                                Your company verification was rejected. Please update your company details and resubmit for
                                review.
                            @else
                                You can’t post jobs until at least one company is verified.
                            @endif
                            <div class="mt-3 flex flex-wrap gap-2">
                                <a href="{{ route('employer.companies.index') }}" class="ti-btn ti-btn-primary">
                                    <i class="ri-building-2-line me-1"></i> Go to Companies
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($canPostJobs ?? true)
                        <form method="POST" action="{{ route('jobs.store') }}" enctype="multipart/form-data" novalidate>
                            @csrf
                            <input type="hidden" name="job_template_id" value="{{ $selectedTemplateId ?? '' }}">

                            {{-- Company data for autofill --}}
                            <script id="company-data-json" type="application/json">
                                {!! json_encode($companies->keyBy('id')->map(fn($c) => [
                                    'id' => $c->id,
                                    'name' => $c->name,
                                    'industry' => $c->industry,
                                    'location' => $c->location,
                                    'logo_url' => $c->logo_url,
                                    'city' => $c->city,
                                    'province' => $c->province,
                                    'postal_code' => $c->postal_code,
                                    'country' => $c->country,
                                    'business_address' => $c->business_address,
                                ])) !!}
                            </script>

                            <div class="pj-wizard-wrap">

                                                {{-- Premium Step Indicator --}}
                                                <div class="mb-8" id="wt-progress">
                                                    <div class="pj-steps">
                                                        <div class="pj-step">
                                                            <button type="button"
                                                                class="job-wizard-tab job-wizard-tab-1 pj-step-dot active focus:outline-none">1</button>
                                                            <span class="pj-step-lbl active">Basics</span>
                                                        </div>
                                                        <div class="pj-step-line" id="pj-line-1"></div>
                                                        <div class="pj-step">
                                                            <button type="button"
                                                                class="job-wizard-tab job-wizard-tab-2 pj-step-dot focus:outline-none">2</button>
                                                            <span class="pj-step-lbl">Settings</span>
                                                        </div>
                                                        <div class="pj-step-line" id="pj-line-2"></div>
                                                        <div class="pj-step">
                                                            <button type="button"
                                                                class="job-wizard-tab job-wizard-tab-3 pj-step-dot focus:outline-none">3</button>
                                                            <span class="pj-step-lbl">Content</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- STEP 1: Job basics + logo --}}
                                                <div class="job-wizard-step job-wizard-step-1" id="wt-step1">

                                                    <div class="pj-card">
                                                        <div class="pj-card-head">
                                                            <div class="pj-card-icon" style="background:rgba(99,102,241,0.1);color:#6366f1">
                                                                <i class="ri-building-2-line"></i>
                                                            </div>
                                                            <div>
                                                                <div class="pj-card-title">Company & Role</div>
                                                                <div class="pj-card-sub">Select a company and define the position</div>
                                                            </div>
                                                        </div>
                                                        <div class="pj-card-body">
                                                            <div class="grid grid-cols-12 gap-4 mb-4">
                                                                <div class="col-span-12 md:col-span-6">
                                                                    <label class="form-label">Company <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="company_id" id="pj-company-select" class="form-select"
                                                                        required>
                                                                        <option value="" disabled {{ old('company_id', optional($template)->company_id) ? '' : 'selected' }}>Select
                                                                            company
                                                                        </option>
                                                                        @foreach($companies as $company)
                                                                            <option value="{{ $company->id }}" @selected(old('company_id', optional($template)->company_id) == $company->id)>
                                                                                {{ $company->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-span-12 md:col-span-6">
                                                                    <label class="form-label">Job Title <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="title" id="job_title" required class="form-control tomselect-basic" placeholder="Search or select a job role...">
                                                                        <option value="">-- Type to search --</option>
                                                                        
                                                                        <optgroup label="Administrative &amp; Operations">
                                                                            <option value="Administrative Assistant" {{ old('title', optional($template)->title) === 'Administrative Assistant' ? 'selected' : '' }} data-display="Administrative Assistant">Administrative Assistant</option>
                                                                            <option value="Executive Assistant" {{ old('title', optional($template)->title) === 'Executive Assistant' ? 'selected' : '' }} data-display="Executive Assistant">Executive Assistant</option>
                                                                            <option value="Operations Manager" {{ old('title', optional($template)->title) === 'Operations Manager' ? 'selected' : '' }} data-display="Operations Manager">Operations Manager</option>
                                                                            <option value="Operations Coordinator" {{ old('title', optional($template)->title) === 'Operations Coordinator' ? 'selected' : '' }} data-display="Operations Coordinator">Operations Coordinator</option>
                                                                            <option value="Office Manager" {{ old('title', optional($template)->title) === 'Office Manager' ? 'selected' : '' }} data-display="Office Manager">Office Manager</option>
                                                                            <option value="Virtual Assistant" {{ old('title', optional($template)->title) === 'Virtual Assistant' ? 'selected' : '' }} data-display="Virtual Assistant">Virtual Assistant</option>
                                                                            <option value="Document Controller" {{ old('title', optional($template)->title) === 'Document Controller' ? 'selected' : '' }} data-display="Document Controller">Document Controller</option>
                                                                        </optgroup>
                                                                        
                                                                        <optgroup label="Finance &amp; Accounting">
                                                                            <option value="Accountant" {{ old('title', optional($template)->title) === 'Accountant' ? 'selected' : '' }} data-display="Accountant">Accountant</option>
                                                                            <option value="Bookkeeper" {{ old('title', optional($template)->title) === 'Bookkeeper' ? 'selected' : '' }} data-display="Bookkeeper">Bookkeeper</option>
                                                                            <option value="Financial Analyst" {{ old('title', optional($template)->title) === 'Financial Analyst' ? 'selected' : '' }} data-display="Financial Analyst">Financial Analyst</option>
                                                                            <option value="Accounts Payable Specialist" {{ old('title', optional($template)->title) === 'Accounts Payable Specialist' ? 'selected' : '' }} data-display="Accounts Payable Specialist">Accounts Payable Specialist</option>
                                                                            <option value="Accounts Receivable Specialist" {{ old('title', optional($template)->title) === 'Accounts Receivable Specialist' ? 'selected' : '' }} data-display="Accounts Receivable Specialist">Accounts Receivable Specialist</option>
                                                                            <option value="Payroll Specialist" {{ old('title', optional($template)->title) === 'Payroll Specialist' ? 'selected' : '' }} data-display="Payroll Specialist">Payroll Specialist</option>
                                                                            <option value="Audit Assistant" {{ old('title', optional($template)->title) === 'Audit Assistant' ? 'selected' : '' }} data-display="Audit Assistant">Audit Assistant</option>
                                                                            <option value="Tax Specialist" {{ old('title', optional($template)->title) === 'Tax Specialist' ? 'selected' : '' }} data-display="Tax Specialist">Tax Specialist</option>
                                                                        </optgroup>
                                                                        
                                                                        <optgroup label="Customer Support">
                                                                            <option value="Customer Service Representative" {{ old('title', optional($template)->title) === 'Customer Service Representative' ? 'selected' : '' }} data-display="Customer Service Representative">Customer Service Representative</option>
                                                                            <option value="Customer Support Specialist" {{ old('title', optional($template)->title) === 'Customer Support Specialist' ? 'selected' : '' }} data-display="Customer Support Specialist">Customer Support Specialist</option>
                                                                            <option value="Technical Support Representative" {{ old('title', optional($template)->title) === 'Technical Support Representative' ? 'selected' : '' }} data-display="Technical Support Representative">Technical Support Representative</option>
                                                                            <option value="Help Desk Agent" {{ old('title', optional($template)->title) === 'Help Desk Agent' ? 'selected' : '' }} data-display="Help Desk Agent">Help Desk Agent</option>
                                                                            <option value="Client Success Manager" {{ old('title', optional($template)->title) === 'Client Success Manager' ? 'selected' : '' }} data-display="Client Success Manager">Client Success Manager</option>
                                                                            <option value="Call Center Agent" {{ old('title', optional($template)->title) === 'Call Center Agent' ? 'selected' : '' }} data-display="Call Center Agent">Call Center Agent</option>
                                                                        </optgroup>
                                                                        
                                                                        <optgroup label="Sales &amp; Marketing">
                                                                            <option value="Sales Representative" {{ old('title', optional($template)->title) === 'Sales Representative' ? 'selected' : '' }} data-display="Sales Representative">Sales Representative</option>
                                                                            <option value="Sales Executive" {{ old('title', optional($template)->title) === 'Sales Executive' ? 'selected' : '' }} data-display="Sales Executive">Sales Executive</option>
                                                                            <option value="Account Manager" {{ old('title', optional($template)->title) === 'Account Manager' ? 'selected' : '' }} data-display="Account Manager">Account Manager</option>
                                                                            <option value="Business Development Representative" {{ old('title', optional($template)->title) === 'Business Development Representative' ? 'selected' : '' }} data-display="Business Development Representative">Business Development Representative</option>
                                                                            <option value="Digital Marketing Specialist" {{ old('title', optional($template)->title) === 'Digital Marketing Specialist' ? 'selected' : '' }} data-display="Digital Marketing Specialist">Digital Marketing Specialist</option>
                                                                            <option value="Social Media Manager" {{ old('title', optional($template)->title) === 'Social Media Manager' ? 'selected' : '' }} data-display="Social Media Manager">Social Media Manager</option>
                                                                            <option value="Content Writer" {{ old('title', optional($template)->title) === 'Content Writer' ? 'selected' : '' }} data-display="Content Writer">Content Writer</option>
                                                                            <option value="SEO Specialist" {{ old('title', optional($template)->title) === 'SEO Specialist' ? 'selected' : '' }} data-display="SEO Specialist">SEO Specialist</option>
                                                                        </optgroup>
                                                                        
                                                                        <optgroup label="Human Resources">
                                                                            <option value="HR Assistant" {{ old('title', optional($template)->title) === 'HR Assistant' ? 'selected' : '' }} data-display="HR Assistant">HR Assistant</option>
                                                                            <option value="HR Generalist" {{ old('title', optional($template)->title) === 'HR Generalist' ? 'selected' : '' }} data-display="HR Generalist">HR Generalist</option>
                                                                            <option value="Recruitment Specialist" {{ old('title', optional($template)->title) === 'Recruitment Specialist' ? 'selected' : '' }} data-display="Recruitment Specialist">Recruitment Specialist</option>
                                                                            <option value="Talent Acquisition Specialist" {{ old('title', optional($template)->title) === 'Talent Acquisition Specialist' ? 'selected' : '' }} data-display="Talent Acquisition Specialist">Talent Acquisition Specialist</option>
                                                                            <option value="Training Coordinator" {{ old('title', optional($template)->title) === 'Training Coordinator' ? 'selected' : '' }} data-display="Training Coordinator">Training Coordinator</option>
                                                                        </optgroup>
                                                                        
                                                                        <optgroup label="IT &amp; Technical">
                                                                            <option value="Software Developer" {{ old('title', optional($template)->title) === 'Software Developer' ? 'selected' : '' }} data-display="Software Developer">Software Developer</option>
                                                                            <option value="Web Developer" {{ old('title', optional($template)->title) === 'Web Developer' ? 'selected' : '' }} data-display="Web Developer">Web Developer</option>
                                                                            <option value="Frontend Developer" {{ old('title', optional($template)->title) === 'Frontend Developer' ? 'selected' : '' }} data-display="Frontend Developer">Frontend Developer</option>
                                                                            <option value="Backend Developer" {{ old('title', optional($template)->title) === 'Backend Developer' ? 'selected' : '' }} data-display="Backend Developer">Backend Developer</option>
                                                                            <option value="Full Stack Developer" {{ old('title', optional($template)->title) === 'Full Stack Developer' ? 'selected' : '' }} data-display="Full Stack Developer">Full Stack Developer</option>
                                                                            <option value="QA Tester" {{ old('title', optional($template)->title) === 'QA Tester' ? 'selected' : '' }} data-display="QA Tester">QA Tester</option>
                                                                            <option value="IT Support Specialist" {{ old('title', optional($template)->title) === 'IT Support Specialist' ? 'selected' : '' }} data-display="IT Support Specialist">IT Support Specialist</option>
                                                                            <option value="System Administrator" {{ old('title', optional($template)->title) === 'System Administrator' ? 'selected' : '' }} data-display="System Administrator">System Administrator</option>
                                                                        </optgroup>
                                                                        
                                                                        <optgroup label="Project &amp; Coordination">
                                                                            <option value="Project Coordinator" {{ old('title', optional($template)->title) === 'Project Coordinator' ? 'selected' : '' }} data-display="Project Coordinator">Project Coordinator</option>
                                                                            <option value="Project Manager" {{ old('title', optional($template)->title) === 'Project Manager' ? 'selected' : '' }} data-display="Project Manager">Project Manager</option>
                                                                            <option value="Scrum Master" {{ old('title', optional($template)->title) === 'Scrum Master' ? 'selected' : '' }} data-display="Scrum Master">Scrum Master</option>
                                                                            <option value="Product Manager" {{ old('title', optional($template)->title) === 'Product Manager' ? 'selected' : '' }} data-display="Product Manager">Product Manager</option>
                                                                        </optgroup>
                                                                        
                                                                        <optgroup label="Creative &amp; Design">
                                                                            <option value="Graphic Designer" {{ old('title', optional($template)->title) === 'Graphic Designer' ? 'selected' : '' }} data-display="Graphic Designer">Graphic Designer</option>
                                                                            <option value="UI/UX Designer" {{ old('title', optional($template)->title) === 'UI/UX Designer' ? 'selected' : '' }} data-display="UI/UX Designer">UI/UX Designer</option>
                                                                            <option value="Video Editor" {{ old('title', optional($template)->title) === 'Video Editor' ? 'selected' : '' }} data-display="Video Editor">Video Editor</option>
                                                                            <option value="Multimedia Specialist" {{ old('title', optional($template)->title) === 'Multimedia Specialist' ? 'selected' : '' }} data-display="Multimedia Specialist">Multimedia Specialist</option>
                                                                        </optgroup>
                                                                        
                                                                        <optgroup label="E-commerce &amp; Admin">
                                                                            <option value="E-commerce Specialist" {{ old('title', optional($template)->title) === 'E-commerce Specialist' ? 'selected' : '' }} data-display="E-commerce Specialist">E-commerce Specialist</option>
                                                                            <option value="Shopify Manager" {{ old('title', optional($template)->title) === 'Shopify Manager' ? 'selected' : '' }} data-display="Shopify Manager">Shopify Manager</option>
                                                                            <option value="Product Listing Specialist" {{ old('title', optional($template)->title) === 'Product Listing Specialist' ? 'selected' : '' }} data-display="Product Listing Specialist">Product Listing Specialist</option>
                                                                            <option value="Inventory Coordinator" {{ old('title', optional($template)->title) === 'Inventory Coordinator' ? 'selected' : '' }} data-display="Inventory Coordinator">Inventory Coordinator</option>
                                                                        </optgroup>
                                                                        
                                                                        <optgroup label="Specialized Virtual Roles">
                                                                            <option value="Real Estate Virtual Assistant" {{ old('title', optional($template)->title) === 'Real Estate Virtual Assistant' ? 'selected' : '' }} data-display="Real Estate Virtual Assistant">Real Estate Virtual Assistant</option>
                                                                            <option value="Medical Virtual Assistant" {{ old('title', optional($template)->title) === 'Medical Virtual Assistant' ? 'selected' : '' }} data-display="Medical Virtual Assistant">Medical Virtual Assistant</option>
                                                                            <option value="Legal Virtual Assistant" {{ old('title', optional($template)->title) === 'Legal Virtual Assistant' ? 'selected' : '' }} data-display="Legal Virtual Assistant">Legal Virtual Assistant</option>
                                                                            <option value="Executive Virtual Assistant" {{ old('title', optional($template)->title) === 'Executive Virtual Assistant' ? 'selected' : '' }} data-display="Executive Virtual Assistant">Executive Virtual Assistant</option>
                                                                            <option value="Marketing Virtual Assistant" {{ old('title', optional($template)->title) === 'Marketing Virtual Assistant' ? 'selected' : '' }} data-display="Marketing Virtual Assistant">Marketing Virtual Assistant</option>
                                                                        </optgroup>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            {{-- Company autofill info panel --}}
                                                            <div id="pj-company-info" class="mb-4" style="display:none;">
                                                                <div
                                                                    class="rounded-xl border border-indigo-100 dark:border-indigo-900/40 bg-indigo-50/60 dark:bg-indigo-950/20 px-4 py-3">
                                                                    <div class="flex items-center gap-2 mb-3">
                                                                        <i class="ri-information-line text-indigo-500"></i>
                                                                        <span
                                                                            class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wide">Company
                                                                            Details Auto-filled</span>
                                                                    </div>
                                                                    <div class="grid grid-cols-12 gap-3">
                                                                        <div class="col-span-12 md:col-span-6">
                                                                            <label class="form-label text-xs">City</label>
                                                                            <input type="text" id="pj-af-city"
                                                                                class="form-control bg-gray-50 dark:bg-white/5" readonly
                                                                                placeholder="—">
                                                                        </div>
                                                                        <div class="col-span-12 md:col-span-6">
                                                                            <label class="form-label text-xs">Province</label>
                                                                            <input type="text" id="pj-af-province"
                                                                                class="form-control bg-gray-50 dark:bg-white/5" readonly
                                                                                placeholder="—">
                                                                        </div>
                                                                        <div class="col-span-12 md:col-span-6">
                                                                            <label class="form-label text-xs">Postal Code</label>
                                                                            <input type="text" id="pj-af-postal"
                                                                                class="form-control bg-gray-50 dark:bg-white/5" readonly
                                                                                placeholder="—">
                                                                        </div>
                                                                        <div class="col-span-12 md:col-span-6">
                                                                            <label class="form-label text-xs">Country</label>
                                                                            <input type="text" id="pj-af-country"
                                                                                class="form-control bg-gray-50 dark:bg-white/5" readonly
                                                                                placeholder="—">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="grid grid-cols-12 gap-4 mb-4">
                                                                <div class="col-span-12 md:col-span-6">
                                                                    <label class="form-label">Location <span
                                                                            class="text-danger">*</span></label>
                                                                    <div class="relative" id="pj-location-wrap">
                                                                        <select name="location" id="pj-location-select" class="form-select"
                                                                            required>
                                                                            <option value="" disabled {{ old('location', optional($template)->location) ? '' : 'selected' }}>Select
                                                                                location
                                                                            </option>
                                                                            @foreach(($dropdownOptions['locations'] ?? []) as $value => $label)
                                                                                <option value="{{ $value }}" @selected(old('location', optional($template)->location) === $value)>{{ $label }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span class="pj-af-lock-badge"
                                                                            title="Filled from company profile"><i
                                                                                class="ri-building-2-line"></i>From company</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-span-12 md:col-span-6">
                                                                    <label class="form-label">Category <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="category" class="form-select" required>
                                                                        <option value="" disabled {{ old('category', optional($template)->category) ? '' : 'selected' }}>Select
                                                                            category</option>
                                                                        @foreach(($dropdownOptions['categories'] ?? []) as $value => $label)
                                                                            <option value="{{ $value }}" @selected(old('category', optional($template)->category) === $value)>{{ $label }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="grid grid-cols-12 gap-4">
                                                                <div class="col-span-12 md:col-span-6">
                                                                    <label class="form-label">Industry Type <span
                                                                            class="text-danger">*</span></label>
                                                                    <div class="relative" id="pj-industry-wrap">
                                                                        <select name="industry_type" id="pj-industry-select"
                                                                            class="form-select" required>
                                                                            <option value="" disabled {{ old('industry_type', optional($template)->industry_type) ? '' : 'selected' }}>
                                                                                Select
                                                                                industry</option>
                                                                            @foreach(($dropdownOptions['industryTypes'] ?? []) as $value => $label)
                                                                                <option value="{{ $value }}" @selected(old('industry_type', optional($template)->industry_type) === $value)>{{ $label }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span class="pj-af-lock-badge"
                                                                            title="Filled from company profile"><i
                                                                                class="ri-building-2-line"></i>From company</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-span-12 md:col-span-6">
                                                                    <label class="form-label">Recruiter Type <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="recruiter_type" class="form-select" required>
                                                                        <option value="" disabled {{ old('recruiter_type', optional($template)->recruiter_type) ? '' : 'selected' }}>Select
                                                                            recruiter type</option>
                                                                        @foreach(($dropdownOptions['recruiterTypes'] ?? []) as $value => $label)
                                                                            <option value="{{ $value }}" @selected(old('recruiter_type', optional($template)->recruiter_type) === $value)>{{ $label }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="pj-card" id="wt-logo-upload">
                                                        <div class="pj-card-head">
                                                            <div class="pj-card-icon" style="background:rgba(16,185,129,0.1);color:#10b981">
                                                                <i class="ri-image-add-line"></i>
                                                            </div>
                                                            <div>
                                                                <div class="pj-card-title">Job Logo</div>
                                                                <div class="pj-card-sub">Upload a logo for this job listing (optional)</div>
                                                            </div>
                                                        </div>
                                                        <div class="pj-card-body">
                                                            <div class="pj-logo-card">
                                                                @php
                                                                    $firstCompany = $companies->first();
                                                                    $fallbackLogo = $firstCompany?->logo_url ?: 'https://api.dicebear.com/7.x/shapes/svg?seed=' . urlencode($firstCompany?->name ?? 'Company');
                                                                @endphp
                                                                <img id="job-logo-preview" src="{{ old('logo_preview', $fallbackLogo) }}"
                                                                    alt="Job logo"
                                                                    class="w-20 h-20 rounded-xl object-cover mx-auto mb-3 border border-gray-100 dark:border-white/10">
                                                                <label class="ti-btn ti-btn-outline-primary ti-btn-sm cursor-pointer mb-1">
                                                                    <i class="ri-upload-cloud-line me-1"></i> Upload Logo
                                                                    <input type="file" name="logo" id="job-logo-input" class="sr-only"
                                                                        accept="image/*">
                                                                </label>
                                                                <p class="text-[11px] text-textmuted mt-2 mb-0">Square image, max 4MB · PNG,
                                                                    JPG</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                {{-- STEP 2: Job settings --}}
                                                <div class="job-wizard-step job-wizard-step-2" style="display: none;">

                                                    <div class="pj-card">
                                                        <div class="pj-card-head">
                                                            <div class="pj-card-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b">
                                                                <i class="ri-suitcase-line"></i>
                                                            </div>
                                                            <div>
                                                                <div class="pj-card-title">Employment Details</div>
                                                                <div class="pj-card-sub">Define the employment type, work arrangement, and
                                                                    openings</div>
                                                            </div>
                                                        </div>
                                                        <div class="pj-card-body">
                                                            <div class="grid grid-cols-12 gap-4">
                                                                <div class="col-span-12 md:col-span-4">
                                                                    <label class="form-label">Employment Type <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="employment_type" class="form-select" required>
                                                                        <option value="" disabled {{ $employmentValue ? '' : 'selected' }}>
                                                                            Select</option>
                                                                        @foreach(($dropdownOptions['employmentTypes'] ?? []) as $value => $label)
                                                                            <option value="{{ $value }}" @selected($employmentValue === $value)>
                                                                                {{ $label }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-span-12 md:col-span-4">
                                                                    <label class="form-label">Remote Type <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="remote_type" class="form-select" required>
                                                                        <option value="" disabled {{ $remoteValue ? '' : 'selected' }}>
                                                                            Select</option>
                                                                        @foreach(($dropdownOptions['remoteTypes'] ?? []) as $value => $label)
                                                                            <option value="{{ $value }}" @selected($remoteValue === $value)>
                                                                                {{ $label }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-span-12 md:col-span-4">
                                                                    <label class="form-label">Vacancies <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number" name="vacancies" class="form-control" min="1"
                                                                        value="{{ old('vacancies', optional($template)->vacancies ?? 1) }}"
                                                                        required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="pj-card">
                                                        <div class="pj-card-head">
                                                            <div class="pj-card-icon" style="background:rgba(16,185,129,0.1);color:#10b981">
                                                                <i class="ri-money-dollar-circle-line"></i>
                                                            </div>
                                                            <div>
                                                                <div class="pj-card-title">Experience & Compensation</div>
                                                                <div class="pj-card-sub">Set the salary range and experience requirements
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="pj-card-body">
                                                            <div class="grid grid-cols-12 gap-4 mb-4">
                                                                <div class="col-span-12 md:col-span-3">
                                                                    <label class="form-label">Min Experience (yrs) <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number" name="experience_min_years" class="form-control"
                                                                        min="0"
                                                                        value="{{ old('experience_min_years', optional($template)->experience_min_years) }}"
                                                                        placeholder="0" required>
                                                                </div>
                                                                <div class="col-span-12 md:col-span-3">
                                                                    <label class="form-label">Max Experience (yrs) <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number" name="experience_max_years" class="form-control"
                                                                        min="0"
                                                                        value="{{ old('experience_max_years', optional($template)->experience_max_years) }}"
                                                                        placeholder="5" required>
                                                                </div>
                                                                <div class="col-span-12 md:col-span-3">
                                                                    <label class="form-label">Salary Min <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number" step="0.01" name="salary_min" class="form-control"
                                                                        value="{{ $salaryMinValue }}" placeholder="e.g. 30000" required>
                                                                </div>
                                                                <div class="col-span-12 md:col-span-3">
                                                                    <label class="form-label">Salary Max <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number" step="0.01" name="salary_max" class="form-control"
                                                                        value="{{ $salaryMaxValue }}" placeholder="e.g. 60000" required>
                                                                </div>
                                                            </div>
                                                            <div class="grid grid-cols-12 gap-4">
                                                                <div class="col-span-12 md:col-span-4">
                                                                    <label class="form-label">Currency <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="salary_currency" class="form-select" required>
                                                                        @php $currencyValue = old('salary_currency', optional($template)->salary_currency ?? 'USD'); @endphp
                                                                        @foreach(($dropdownOptions['currencies'] ?? []) as $value => $label)
                                                                            <option value="{{ $value }}" @selected($currencyValue === $value)>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-span-12 md:col-span-4">
                                                                    <label class="form-label">Posted At <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="date" name="posted_at" class="form-control"
                                                                        value="{{ $postedAtValue }}" required>
                                                                </div>
                                                                <div class="col-span-12 md:col-span-4">
                                                                    <label class="form-label">Closes At <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="date" name="closes_at" class="form-control"
                                                                        value="{{ $closesAtValue }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                {{-- STEP 3: Job content, highlights, responsibilities & requirements --}}
                                                <div class="job-wizard-step job-wizard-step-3" style="display: none;">
                                                    @php
                                                        $responsibilitiesForm = old('responsibilities');
                                                        if (!is_array($responsibilitiesForm)) {
                                                            $responsibilitiesForm = $responsibilitiesForm ? preg_split('/\r?\n/', (string) $responsibilitiesForm) : [];
                                                        }
                                                        if ((!is_array($responsibilitiesForm) || !count($responsibilitiesForm)) && optional($template)->responsibilities) {
                                                            $responsibilitiesForm = preg_split('/\r?\n/', (string) optional($template)->responsibilities);
                                                        }
                                                        if (!is_array($responsibilitiesForm) || !count($responsibilitiesForm)) {
                                                            $responsibilitiesForm = [''];
                                                        }

                                                        $requirementsForm = old('requirements');
                                                        if (!is_array($requirementsForm)) {
                                                            $requirementsForm = $requirementsForm ? preg_split('/\r?\n/', (string) $requirementsForm) : [];
                                                        }
                                                        if ((!is_array($requirementsForm) || !count($requirementsForm)) && optional($template)->requirements) {
                                                            $requirementsForm = preg_split('/\r?\n/', (string) optional($template)->requirements);
                                                        }
                                                        if (!is_array($requirementsForm) || !count($requirementsForm)) {
                                                            $requirementsForm = [''];
                                                        }

                                                        $highlightBenefitsForm = old('highlight_benefits');
                                                        if (!is_array($highlightBenefitsForm)) {
                                                            $highlightBenefitsForm = $highlightBenefitsForm ? preg_split('/\r?\n/', (string) $highlightBenefitsForm) : [];
                                                        }
                                                        if ((!is_array($highlightBenefitsForm) || !count($highlightBenefitsForm)) && optional($template)->highlight_benefits) {
                                                            $highlightBenefitsForm = preg_split('/\r?\n/', (string) optional($template)->highlight_benefits);
                                                        }
                                                        if (!is_array($highlightBenefitsForm) || !count($highlightBenefitsForm)) {
                                                            $highlightBenefitsForm = ['Growth culture'];
                                                        }

                                                        $workSetupValue = old('highlight_work_setup', optional($template)->highlight_work_setup);
                                                        if ($workSetupValue === null || $workSetupValue === '') {
                                                            $workSetupValue = $remoteValue ? Str::headline($remoteValue) : 'Flexible';
                                                        }

                                                        $shiftScheduleValue = old('highlight_shift_schedule', optional($template)->highlight_shift_schedule);
                                                        if ($shiftScheduleValue === null || $shiftScheduleValue === '') {
                                                            $shiftScheduleValue = $employmentValue ? Str::headline(str_replace('_', ' ', $employmentValue)) : 'Standard';
                                                        }

                                                        $minDefault = old('highlight_monthly_rate_min', $salaryMinValue);
                                                        $maxDefault = old('highlight_monthly_rate_max', $salaryMaxValue);
                                                    @endphp

                                                    {{-- Description card --}}
                                                    <div class="pj-card">
                                                        <div class="pj-card-head">
                                                            <div class="pj-card-icon" style="background:rgba(99,102,241,0.1);color:#6366f1">
                                                                <i class="ri-file-text-line"></i>
                                                            </div>
                                                            <div>
                                                                <div class="pj-card-title">Job Description</div>
                                                                <div class="pj-card-sub">Describe the role, team, and impact</div>
                                                            </div>
                                                        </div>
                                                        <div class="pj-card-body">
                                                            <textarea name="description" class="form-control" rows="5" required
                                                                placeholder="Describe the role, responsibilities, team environment, and any other relevant details...">{{ old('description', optional($template)->description) }}</textarea>
                                                        </div>
                                                    </div>

                                                    {{-- Responsibilities & requirements --}}
                                                    <div class="pj-card">
                                                        <div class="pj-card-head">
                                                            <div class="pj-card-icon" style="background:rgba(139,92,246,0.1);color:#8b5cf6">
                                                                <i class="ri-list-check-2"></i>
                                                            </div>
                                                            <div>
                                                                <div class="pj-card-title">What You'll Do & Need</div>
                                                                <div class="pj-card-sub">List key responsibilities and required
                                                                    qualifications</div>
                                                            </div>
                                                        </div>
                                                        <div class="pj-card-body">
                                                            <div class="grid grid-cols-12 gap-6">
                                                                <div class="col-span-12 lg:col-span-6">
                                                                    <div class="flex items-center justify-between mb-2">
                                                                        <label class="form-label mb-0 font-semibold">Key
                                                                            Responsibilities</label>
                                                                        <button type="button"
                                                                            class="ti-btn ti-btn-sm ti-btn-outline-primary"
                                                                            id="add-job-responsibility"><i
                                                                                class="ri-add-line me-1"></i>Add</button>
                                                                    </div>
                                                                    <div id="job-responsibilities-list" class="space-y-2">
                                                                        @foreach($responsibilitiesForm as $idx => $line)
                                                                            <div class="flex gap-2 items-center job-resp-row"
                                                                                data-index="{{ $idx }}">
                                                                                <span class="text-primary text-xs shrink-0"><i
                                                                                        class="ri-arrow-right-s-line"></i></span>
                                                                                <input type="text" name="responsibilities[{{ $idx }}]"
                                                                                    class="form-control form-control-sm" value="{{ $line }}"
                                                                                    placeholder="What will they do?">
                                                                                <button type="button"
                                                                                    class="remove-job-resp-row shrink-0 inline-flex items-center justify-center h-7 w-7 border border-red-300 text-red-400 rounded-md text-xs hover:bg-red-50"
                                                                                    title="Remove"><i class="ri-close-line"></i></button>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="col-span-12 lg:col-span-6">
                                                                    <div class="flex items-center justify-between mb-2">
                                                                        <label class="form-label mb-0 font-semibold">Requirements</label>
                                                                        <button type="button"
                                                                            class="ti-btn ti-btn-sm ti-btn-outline-primary"
                                                                            id="add-job-requirement"><i
                                                                                class="ri-add-line me-1"></i>Add</button>
                                                                    </div>
                                                                    <div id="job-requirements-list" class="space-y-2">
                                                                        @foreach($requirementsForm as $idx => $line)
                                                                            <div class="flex gap-2 items-center job-req-row"
                                                                                data-index="{{ $idx }}">
                                                                                <span class="text-purple-500 text-xs shrink-0"><i
                                                                                        class="ri-checkbox-circle-line"></i></span>
                                                                                <input type="text" name="requirements[{{ $idx }}]"
                                                                                    class="form-control form-control-sm" value="{{ $line }}"
                                                                                    placeholder="Skill or qualification">
                                                                                <button type="button"
                                                                                    class="remove-job-req-row shrink-0 inline-flex items-center justify-center h-7 w-7 border border-red-300 text-red-400 rounded-md text-xs hover:bg-red-50"
                                                                                    title="Remove"><i class="ri-close-line"></i></button>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Job Highlights card --}}
                                                    <div class="pj-card">
                                                        <div class="pj-card-head">
                                                            <div class="pj-card-icon" style="background:rgba(245,158,11,0.1);color:#f59e0b">
                                                                <i class="ri-star-line"></i>
                                                            </div>
                                                            <div>
                                                                <div class="pj-card-title">Job Highlights</div>
                                                                <div class="pj-card-sub">Key perks shown on the job listing card</div>
                                                            </div>
                                                        </div>
                                                        <div class="pj-card-body">
                                                            <div class="pj-hl-row">
                                                                <div class="pj-hl-lbl">
                                                                    <span class="avatar avatar-xs avatar-rounded bg-info/10 !text-info"><i
                                                                            class="ri-map-pin-line"></i></span>
                                                                    Work Setup
                                                                </div>
                                                                <div class="pj-hl-controls">
                                                                    <select name="highlight_work_setup" class="form-select form-select-sm"
                                                                        style="min-width:160px">
                                                                        <option value="">Select</option>
                                                                        @foreach(($dropdownOptions['workSetups'] ?? []) as $value => $label)
                                                                            <option value="{{ $value }}" @selected($workSetupValue === $value)>
                                                                                {{ $label }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="pj-hl-row">
                                                                <div class="pj-hl-lbl">
                                                                    <span
                                                                        class="avatar avatar-xs avatar-rounded bg-danger/10 !text-danger"><i
                                                                            class="ri-time-line"></i></span>
                                                                    Shift Schedule
                                                                </div>
                                                                <div class="pj-hl-controls">
                                                                    <select name="highlight_shift_schedule"
                                                                        class="form-select form-select-sm" style="min-width:160px">
                                                                        <option value="">Select</option>
                                                                        @foreach(($dropdownOptions['shiftSchedules'] ?? []) as $value => $label)
                                                                            <option value="{{ $value }}"
                                                                                @selected($shiftScheduleValue === $value)>{{ $label }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="pj-hl-row">
                                                                <div class="pj-hl-lbl">
                                                                    <span
                                                                        class="avatar avatar-xs avatar-rounded bg-success/10 !text-success"><i
                                                                            class="ri-cash-line"></i></span>
                                                                    Monthly Rate
                                                                </div>
                                                                <div class="pj-hl-controls">
                                                                    <input type="text" name="highlight_monthly_rate_min"
                                                                        class="form-control form-control-sm" style="width:80px"
                                                                        value="{{ $minDefault }}" placeholder="Min">
                                                                    <span class="text-textmuted text-xs">–</span>
                                                                    <input type="text" name="highlight_monthly_rate_max"
                                                                        class="form-control form-control-sm" style="width:80px"
                                                                        value="{{ $maxDefault }}" placeholder="Max">
                                                                </div>
                                                            </div>
                                                            <div class="pj-hl-row" style="align-items:flex-start;padding-top:14px">
                                                                <div class="pj-hl-lbl" style="padding-top:4px">
                                                                    <span
                                                                        class="avatar avatar-xs avatar-rounded bg-warning/10 !text-warning"><i
                                                                            class="ri-award-line"></i></span>
                                                                    Benefits
                                                                </div>
                                                                <div class="pj-hl-controls flex-col items-end"
                                                                    style="gap:6px;min-width:200px">
                                                                    <div id="highlight-benefits-list" class="flex flex-col gap-2 w-full">
                                                                        @foreach($highlightBenefitsForm as $idx => $line)
                                                                            <div class="flex items-center gap-2 highlight-benefit-row"
                                                                                data-index="{{ $idx }}">
                                                                                <input type="text" name="highlight_benefits[{{ $idx }}]"
                                                                                    class="form-control form-control-sm flex-1"
                                                                                    value="{{ $line }}" placeholder="e.g. Health Insurance">
                                                                                <button type="button"
                                                                                    class="remove-highlight-benefit-row shrink-0 inline-flex items-center justify-center h-7 w-7 border border-red-300 text-red-400 rounded-md text-[10px] hover:bg-red-50"
                                                                                    title="Remove"><i class="ri-close-line"></i></button>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <button type="button"
                                                                        class="ti-btn ti-btn-xs ti-btn-outline-primary mt-1"
                                                                        id="add-highlight-benefit"><i class="ri-add-line me-1"></i>Add
                                                                        Benefit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Navigation footer --}}
                                                <div
                                                    class="flex justify-between items-center gap-3 pt-4 mt-2 border-t border-gray-100 dark:border-white/5">
                                                    <div>
                                                        <button type="button" class="ti-btn ti-btn-outline-secondary" id="job-wizard-prev"
                                                            style="display: none;">
                                                            <i class="ri-arrow-left-line me-1"></i> Previous
                                                        </button>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('employer.jobs.index') }}" class="ti-btn ti-btn-light">Cancel</a>
                                                        <button type="button" class="ti-btn ti-btn-primary px-6" id="job-wizard-next">
                                                            Next Step <i class="ri-arrow-right-line ms-1"></i>
                                                        </button>
                                                        <button type="submit" class="ti-btn text-white px-6" id="job-wizard-submit"
                                                            style="display:none;background:linear-gradient(135deg,#10b981,#059669);border:none;">
                                                            <i class="ri-rocket-2-line me-1"></i> Post Job
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>{{-- end pj-wizard-wrap --}}
                                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wizard = document.getElementById('job-post-wizard');
            if (!wizard) return;

            let currentStep = 1;
            const totalSteps = 3;

            const stepEls = {};
            const dotEls = {};
            const lblEls = {};
            for (let i = 1; i <= totalSteps; i++) {
                stepEls[i] = wizard.querySelector('.job-wizard-step-' + i);
                dotEls[i] = wizard.querySelector('.job-wizard-tab-' + i);
                if (dotEls[i]) {
                    lblEls[i] = dotEls[i].parentElement.querySelector('.pj-step-lbl');
                }
            }

            const prevBtn = document.getElementById('job-wizard-prev');
            const nextBtn = document.getElementById('job-wizard-next');
            const submitBtn = document.getElementById('job-wizard-submit');
            const stepTitleEl = document.getElementById('job-wizard-step-title');

            const stepTitles = {
                1: '1. Job Basics',
                2: '2. Job Settings',
                3: '3. Job Content',
            };

            function updateWizard() {
                for (let i = 1; i <= totalSteps; i++) {
                    // Show/hide steps
                    if (stepEls[i]) {
                        if (i === currentStep) {
                            stepEls[i].style.display = '';
                            // Trigger animation
                            stepEls[i].classList.remove('pj-step-enter');
                            void stepEls[i].offsetWidth; // reflow
                            stepEls[i].classList.add('pj-step-enter');
                        } else {
                            stepEls[i].style.display = 'none';
                            stepEls[i].classList.remove('pj-step-enter');
                        }
                    }

                    // Step dots
                    if (dotEls[i]) {
                        dotEls[i].classList.remove('active', 'done');
                        if (i < currentStep) dotEls[i].classList.add('done');
                        else if (i === currentStep) dotEls[i].classList.add('active');
                    }

                    // Step labels
                    if (lblEls[i]) {
                        lblEls[i].classList.remove('active', 'done');
                        if (i < currentStep) lblEls[i].classList.add('done');
                        else if (i === currentStep) lblEls[i].classList.add('active');
                    }

                    // Connector lines
                    const line = document.getElementById('pj-line-' + i);
                    if (line) {
                        if (i < currentStep) line.classList.add('done');
                        else line.classList.remove('done');
                    }
                } // end for

                if (prevBtn) prevBtn.style.display = currentStep > 1 ? '' : 'none';
                if (nextBtn) nextBtn.style.display = currentStep < totalSteps ? '' : 'none';
                if (submitBtn) submitBtn.style.display = currentStep === totalSteps ? '' : 'none';

                if (stepTitleEl && stepTitles[currentStep]) {
                    stepTitleEl.textContent = stepTitles[currentStep];
                }

                validateCurrentStep();
            }

            function validateCurrentStep(showErrors = false) {
                const stepEl = stepEls[currentStep];
                if (!stepEl) return true;

                const requiredFields = stepEl.querySelectorAll('[required]');
                let allFilled = true;
                const emptyFields = [];

                requiredFields.forEach(field => {
                    let isEmpty = false;
                    if (field.tagName === 'SELECT') {
                        if (!field.value || field.value === '') isEmpty = true;
                    } else if (field.type === 'checkbox' || field.type === 'radio') {
                        if (!field.checked) isEmpty = true;
                    } else {
                        if (!field.value || field.value.trim() === '') isEmpty = true;
                    }

                    if (isEmpty) {
                        allFilled = false;
                        if (showErrors) {
                            field.classList.add('border-danger');
                            const container = field.closest('.col-span-12, .col-span-6, .col-span-4, .col-span-3, .mb-4, .mb-6');
                            const label = container?.querySelector('.form-label');
                            const fieldName = label ? label.textContent.replace('*', '').replace(/\s+/g, ' ').trim() : field.name;
                            if (!emptyFields.includes(fieldName)) {
                                emptyFields.push(fieldName);
                            }
                        }
                    } else if (showErrors) {
                        field.classList.remove('border-danger');
                    }
                });

                if (showErrors && emptyFields.length > 0) {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Incomplete Step',
                            html: '<p class="mb-2">Please fill in the required fields to continue:</p><ul class="text-left list-disc pl-5">' +
                                emptyFields.map(f => '<li>' + f + '</li>').join('') + '</ul>',
                            confirmButtonColor: '#4f46e5',
                        });
                    } else {
                        alert('Please fill in required fields: ' + emptyFields.join(', '));
                    }
                    return false;
                }

                // Step 2 specific validations: Ranges
                if (currentStep === 2 && showErrors) {
                    const sMin = stepEl.querySelector('[name="salary_min"]');
                    const sMax = stepEl.querySelector('[name="salary_max"]');
                    if (sMin && sMax && sMin.value && sMax.value && parseFloat(sMin.value) > parseFloat(sMax.value)) {
                        if (window.Swal) Swal.fire({ icon: 'error', title: 'Invalid Salary Range', text: 'Minimum salary cannot be greater than maximum salary.' });
                        sMin.classList.add('border-danger');
                        sMax.classList.add('border-danger');
                        return false;
                    }

                    const eMin = stepEl.querySelector('[name="experience_min_years"]');
                    const eMax = stepEl.querySelector('[name="experience_max_years"]');
                    if (eMin && eMax && eMin.value && eMax.value && parseInt(eMin.value) > parseInt(eMax.value)) {
                        if (window.Swal) Swal.fire({ icon: 'error', title: 'Invalid Experience Range', text: 'Minimum experience cannot be greater than maximum experience.' });
                        eMin.classList.add('border-danger');
                        eMax.classList.add('border-danger');
                        return false;
                    }

                    const dStart = stepEl.querySelector('[name="posted_at"]');
                    const dEnd = stepEl.querySelector('[name="closes_at"]');
                    if (dStart && dEnd && dStart.value && dEnd.value && dStart.value > dEnd.value) {
                        if (window.Swal) Swal.fire({ icon: 'error', title: 'Invalid Date Range', text: 'Posting date cannot be later than closing date.' });
                        dStart.classList.add('border-danger');
                        dEnd.classList.add('border-danger');
                        return false;
                    }
                }

                return allFilled;
            }

            // Wire up real-time validation (no errors shown, just border cleaning)
            wizard.addEventListener('input', function (e) {
                if (e.target.hasAttribute('required')) {
                    if (e.target.value && e.target.value.trim() !== '') {
                        e.target.classList.remove('border-danger');
                    }
                }
            });
            wizard.addEventListener('change', function (e) {
                if (e.target.hasAttribute('required') && e.target.value !== '') {
                    e.target.classList.remove('border-danger');
                }
            });

            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    if (currentStep > 1) {
                        currentStep--;
                        updateWizard();
                    }
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    if (validateCurrentStep(true)) {
                        if (currentStep < totalSteps) {
                            currentStep++;
                            updateWizard();
                        }
                    }
                });
            }

            for (let i = 1; i <= totalSteps; i++) {
                if (dotEls[i]) {
                    dotEls[i].addEventListener('click', (function (s) {
                        return function () { currentStep = s; updateWizard(); };
                    })(i));
                }
            }

            updateWizard();

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

            const respList = document.getElementById('job-responsibilities-list');
            const addRespBtn = document.getElementById('add-job-responsibility');
            if (respList && addRespBtn) {
                addRespBtn.addEventListener('click', function () {
                    const idx = nextIndex('#job-responsibilities-list', '.job-resp-row');
                    const row = document.createElement('div');
                    row.className = 'flex gap-2 items-end job-resp-row';
                    row.setAttribute('data-index', idx);
                    row.innerHTML = `
                        <input type="text" name="responsibilities[${idx}]" class="form-control" placeholder="Responsibility">
                        <button type="button" class="remove-job-resp-row inline-flex items-center justify-center h-8 w-8 border border-red-400 text-red-500 rounded-md text-xs hover:bg-red-50" title="Remove">
                            <i class="ri-close-line"></i>
                        </button>
                    `;
                    respList.appendChild(row);
                    refreshRemoveButtons('#job-responsibilities-list', '.job-resp-row', '.remove-job-resp-row');
                });

                respList.addEventListener('click', function (e) {
                    if (e.target.closest('.remove-job-resp-row')) {
                        const row = e.target.closest('.job-resp-row');
                        if (row && respList.children.length > 1) {
                            row.remove();
                            refreshRemoveButtons('#job-responsibilities-list', '.job-resp-row', '.remove-job-resp-row');
                        }
                    }
                });
            }

            const reqList = document.getElementById('job-requirements-list');
            const addReqBtn = document.getElementById('add-job-requirement');
            if (reqList && addReqBtn) {
                addReqBtn.addEventListener('click', function () {
                    const idx = nextIndex('#job-requirements-list', '.job-req-row');
                    const row = document.createElement('div');
                    row.className = 'flex gap-2 items-end job-req-row';
                    row.setAttribute('data-index', idx);
                    row.innerHTML = `
                        <input type="text" name="requirements[${idx}]" class="form-control" placeholder="Requirement">
                        <button type="button" class="remove-job-req-row inline-flex items-center justify-center h-8 w-8 border border-red-400 text-red-500 rounded-md text-xs hover:bg-red-50" title="Remove">
                            <i class="ri-close-line"></i>
                        </button>
                    `;
                    reqList.appendChild(row);
                    refreshRemoveButtons('#job-requirements-list', '.job-req-row', '.remove-job-req-row');
                });

                reqList.addEventListener('click', function (e) {
                    if (e.target.closest('.remove-job-req-row')) {
                        const row = e.target.closest('.job-req-row');
                        if (row && reqList.children.length > 1) {
                            row.remove();
                            refreshRemoveButtons('#job-requirements-list', '.job-req-row', '.remove-job-req-row');
                        }
                    }
                });
            }

            const benefitsList = document.getElementById('highlight-benefits-list');
            const addBenefitBtn = document.getElementById('add-highlight-benefit');
            if (benefitsList && addBenefitBtn) {
                addBenefitBtn.addEventListener('click', function () {
                    const idx = nextIndex('#highlight-benefits-list', '.highlight-benefit-row');
                    const row = document.createElement('div');
                    row.className = 'flex items-center gap-2 highlight-benefit-row w-full';
                    row.setAttribute('data-index', idx);
                    row.innerHTML = `
                        <input type="text"
                               name="highlight_benefits[${idx}]"
                               class="form-control form-control-sm text-end flex-1"
                               placeholder="Benefit" />
                        <button type="button"
                                class="remove-highlight-benefit-row inline-flex items-center justify-center h-7 w-7 border border-red-400 text-red-500 rounded-md text-[10px] hover:bg-red-50"
                                title="Remove">
                            <i class="ri-close-line"></i>
                        </button>
                    `;
                    benefitsList.appendChild(row);
                    refreshRemoveButtons('#highlight-benefits-list', '.highlight-benefit-row', '.remove-highlight-benefit-row');
                });

                benefitsList.addEventListener('click', function (e) {
                    if (e.target.closest('.remove-highlight-benefit-row')) {
                        const row = e.target.closest('.highlight-benefit-row');
                        if (row && benefitsList.children.length > 1) {
                            row.remove();
                            refreshRemoveButtons('#highlight-benefits-list', '.highlight-benefit-row', '.remove-highlight-benefit-row');
                        }
                    }
                });
            }

            refreshRemoveButtons('#job-responsibilities-list', '.job-resp-row', '.remove-job-resp-row');
            refreshRemoveButtons('#job-requirements-list', '.job-req-row', '.remove-job-req-row');
            refreshRemoveButtons('#highlight-benefits-list', '.highlight-benefit-row', '.remove-highlight-benefit-row');

            const logoInput = document.getElementById('job-logo-input');
            const logoPreview = document.getElementById('job-logo-preview');
            if (logoInput && logoPreview) {
                logoInput.addEventListener('change', function () {
                    const file = logoInput.files && logoInput.files[0];
                    if (!file) return;
                    const url = URL.createObjectURL(file);
                    logoPreview.src = url;
                });
            }

            // ── Company autofill ──────────────────────────────────────────
            (function () {
                const companySelect = document.getElementById('pj-company-select');
                const infoPanel = document.getElementById('pj-company-info');
                const afCity = document.getElementById('pj-af-city');
                const afProvince = document.getElementById('pj-af-province');
                const afPostal = document.getElementById('pj-af-postal');
                const afCountry = document.getElementById('pj-af-country');
                const locationSel = document.getElementById('pj-location-select');
                const locationWrap = document.getElementById('pj-location-wrap');
                const industrySel = document.getElementById('pj-industry-select');
                const industryWrap = document.getElementById('pj-industry-wrap');

                const dataEl = document.getElementById('company-data-json');
                if (!companySelect || !dataEl) return;

                let companyMap = {};
                try { companyMap = JSON.parse(dataEl.textContent); } catch (e) { return; }

                function lockSelect(wrap, sel, value) {
                    if (!wrap || !sel) return;
                    if (value) {
                        sel.value = value;
                        wrap.classList.add('pj-af-locked');
                        sel.dispatchEvent(new Event('change', { bubbles: true }));
                    } else {
                        wrap.classList.remove('pj-af-locked');
                    }
                }
                function clearLock(wrap) { if (wrap) wrap.classList.remove('pj-af-locked'); }

                // Find the best matching option value given a raw string.
                // Matches case-insensitively against option value AND text label.
                function findOption(sel, raw) {
                    if (!sel || !raw) return '';
                    const needle = raw.toLowerCase().replace(/_/g, ' ').trim();
                    let match = '';
                    Array.from(sel.options).forEach(function (opt) {
                        if (!match && opt.value) {
                            const v = opt.value.toLowerCase().replace(/_/g, ' ');
                            const t = opt.text.toLowerCase().trim();
                            if (v === needle || t === needle) match = opt.value;
                        }
                    });
                    return match;
                }

                // Map a country string to a location dropdown slug.
                const countryMap = {
                    'philippines': 'philippines', 'ph': 'philippines',
                    'united states': 'usa', 'usa': 'usa', 'us': 'usa', 'america': 'usa',
                    'united kingdom': 'uk', 'uk': 'uk', 'britain': 'uk', 'england': 'uk',
                    'australia': 'australia', 'au': 'australia',
                    'canada': 'canada', 'ca': 'canada',
                };
                function countryToLocation(country) {
                    if (!country) return '';
                    const c = country.toLowerCase().trim();
                    for (const kw in countryMap) {
                        if (c.includes(kw)) return countryMap[kw];
                    }
                    return '';
                }

                function applyAutofill(id) {
                    const c = companyMap[id];
                    if (!c) {
                        if (infoPanel) infoPanel.style.display = 'none';
                        clearLock(locationWrap);
                        clearLock(industryWrap);
                        return;
                    }

                    // ── Address panel ──
                    if (infoPanel) infoPanel.style.display = '';
                    if (afCity) afCity.value = c.city || '';
                    if (afProvince) afProvince.value = c.province || '';
                    if (afPostal) afPostal.value = c.postal_code || '';
                    if (afCountry) afCountry.value = c.country || '';

                    // ── Industry Type: find by slug or label ──
                    const industryVal = findOption(industrySel, c.industry);
                    lockSelect(industryWrap, industrySel, industryVal);
                    if (!industryVal) clearLock(industryWrap);

                    // ── Location: try slug first, then country mapping ──
                    let locationVal = findOption(locationSel, c.location);
                    if (!locationVal) locationVal = countryToLocation(c.country);
                    // Also try matching against option text with country
                    if (!locationVal && c.country && locationSel) {
                        const cn = c.country.toLowerCase().trim();
                        Array.from(locationSel.options).forEach(function (opt) {
                            if (!locationVal && opt.value && opt.text.toLowerCase().includes(cn)) {
                                locationVal = opt.value;
                            }
                        });
                    }
                    lockSelect(locationWrap, locationSel, locationVal);
                    if (!locationVal) clearLock(locationWrap);

                    // ── Logo preview ──
                    if (logoPreview && c.logo_url) {
                        logoPreview.src = c.logo_url;
                    } else if (logoPreview && c.name) {
                        logoPreview.src = 'https://api.dicebear.com/7.x/shapes/svg?seed=' + encodeURIComponent(c.name);
                    }

                    validateCurrentStep();
                }

                companySelect.addEventListener('change', function () {
                    applyAutofill(this.value);
                });

                // Allow manual override — clicking a locked select removes the lock
                [locationSel, industrySel].forEach(function (sel) {
                    if (!sel) return;
                    sel.addEventListener('mousedown', function () {
                        const wrap = sel.closest('.pj-af-locked');
                        if (wrap) wrap.classList.remove('pj-af-locked');
                    });
                });

                // On page load — if a company is already pre-selected, autofill immediately
                if (companySelect.value) applyAutofill(companySelect.value);
            })();
            // ─────────────────────────────────────────────────────────────

            // Form validation with SweetAlert notification
            const form = document.querySelector('#job-post-wizard form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    // Pre-check ranges before submit
                    const sMin = form.querySelector('[name="salary_min"]');
                    const sMax = form.querySelector('[name="salary_max"]');
                    if (sMin && sMax && sMin.value && sMax.value && parseFloat(sMin.value) > parseFloat(sMax.value)) {
                        e.preventDefault();
                        if (window.Swal) Swal.fire({ icon: 'error', title: 'Invalid Salary Range', text: 'Minimum salary cannot be greater than maximum salary.' });
                        return false;
                    }

                    const eMin = form.querySelector('[name="experience_min_years"]');
                    const eMax = form.querySelector('[name="experience_max_years"]');
                    if (eMin && eMax && eMin.value && eMax.value && parseInt(eMin.value) > parseInt(eMax.value)) {
                        e.preventDefault();
                        if (window.Swal) Swal.fire({ icon: 'error', title: 'Invalid Experience Range', text: 'Minimum experience cannot be greater than maximum experience.' });
                        return false;
                    }

                    const dStart = form.querySelector('[name="posted_at"]');
                    const dEnd = form.querySelector('[name="closes_at"]');
                    if (dStart && dEnd && dStart.value && dEnd.value && dStart.value > dEnd.value) {
                        e.preventDefault();
                        if (window.Swal) Swal.fire({ icon: 'error', title: 'Invalid Date Range', text: 'Posting date cannot be later than closing date.' });
                        return false;
                    }

                    const requiredFields = form.querySelectorAll('[required]');
                    const emptyFields = [];

                    requiredFields.forEach(function (field) {
                        let isEmpty = false;

                        if (field.tagName === 'SELECT') {
                            isEmpty = !field.value || field.value === '';
                        } else {
                            isEmpty = !field.value || field.value.trim() === '';
                        }

                        if (isEmpty) {
                            const container = field.closest('.col-span-12, .col-span-6, .col-span-4, .col-span-3, .mb-4, .mb-6');
                            const label = container?.querySelector('.form-label');
                            const fieldName = label ? label.textContent.replace('*', '').replace(/\s+/g, ' ').trim() : field.name;
                            if (!emptyFields.includes(fieldName)) {
                                emptyFields.push(fieldName);
                            }
                        }
                    });

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
                                    const step = firstEmpty.closest('.job-wizard-step');
                                    if (step) {
                                        const stepMatch = step.className.match(/job-wizard-step-(\d+)/);
                                        if (stepMatch) {
                                            const tabBtn = document.querySelector('.job-wizard-tab-' + stepMatch[1]);
                                            if (tabBtn) tabBtn.click();
                                        }
                                    }
                                    setTimeout(() => {
                                        firstEmpty.focus();
                                        firstEmpty.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    }, 300);
                                }
                            });
                        } else {
                            alert('Please fill in all required fields: ' + emptyFields.join(', '));
                        }
                        return false;
                    }
                });
            }
        });
    </script>

    {{-- TomSelect initialization for Job Title dropdown --}}
    @if (!App\Models\DropdownOption::find(1))
        {{-- TomSelect library already loaded globally --}}
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize TomSelect on Job Title field
            const jobTitleSelect = document.getElementById('job_title');
            if (jobTitleSelect && typeof TomSelect !== 'undefined') {
                new TomSelect(jobTitleSelect, {
                    create: false,
                    placeholder: 'Search or select a job role...',
                    searchField: ['text', 'value'],
                    maxOptions: null,
                    dropdownParent: 'body',
                    allowEmptyOption: true
                });
            }
        });
    </script>

    @include('candidates.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'job-post-wizard', 'title' => 'Post a Job', 'icon' => 'ri-file-add-line', 'body' => 'Welcome to the Job Posting wizard! Create your job listing in three easy steps: Job Basics, Job Settings, and Job Content.', 'position' => 'bottom'],
            ['target' => 'wt-progress', 'title' => 'Step Progress', 'icon' => 'ri-progress-3-line', 'body' => 'Track your progress with the step indicators. Click any step number to navigate between steps. The progress bar fills as you advance.', 'position' => 'bottom'],
            ['target' => 'wt-step1', 'title' => 'Step 1: Job Basics', 'icon' => 'ri-briefcase-line', 'body' => 'Start by selecting your company, entering the job title, location, category, industry, and recruiter type. All starred fields are required.', 'position' => 'top'],
            ['target' => 'wt-logo-upload', 'title' => 'Job Logo', 'icon' => 'ri-image-add-line', 'body' => 'Upload a custom logo for this job posting, or leave it to use your company logo automatically. A good logo helps your listing stand out!', 'position' => 'left'],
        ],
        'wtKey' => 'employer_post_job',
    ])

</x-app-layout>
