<x-app-layout>

    <x-slot name="pageTitle">Edit Job Template</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="url_2">{"link": "/employer/templates", "text": "Job Templates"}</x-slot>
    <x-slot name="active">Edit Template</x-slot>

    @include('employers.partials.employer-styles')

    <x-modern-header chip="Update" title="Edit Job Template"
        desc='Update your job posting template'>
        <x-slot name="actions">
            <a href="{{ route('employer.templates.index') }}" class="cd-btn cd-btn-secondary">
                <i class="ri-arrow-left-line"></i>
                <span>Back to Templates</span>
            </a>
        </x-slot>
    </x-modern-header>

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Form ═══ --}}
        <div class="col-span-12">
            <div class="cd-section">
                <form method="POST" action="{{ route('employer.templates.update', $template) }}">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="list-disc ms-4">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Template Info --}}
                    <div class="cd-section-head">
                        <span class="cd-section-label"><i class="ri-information-line"></i> Template Information</span>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Template Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="cd-form-input" value="{{ old('name', $template->name) }}" placeholder="e.g., Senior Developer Position" required>
                            <p class="text-xs text-gray-500 mt-1">A descriptive name to identify this template</p>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Company (Optional)</label>
                            <select name="company_id" class="cd-form-select">
                                <option value="">Personal Template (No Company)</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" @selected(old('company_id', $template->company_id) == $company->id)>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Link to a specific company or keep as personal</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12">
                            <label class="cd-form-label flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_default" value="1" class="cd-form-checkbox" {{ old('is_default', $template->is_default) ? 'checked' : '' }}>
                                <span>Set as default template</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1 ml-6">Default templates are shown first when creating a new job</p>
                        </div>
                    </div>

                    {{-- Job Basics --}}
                    <div class="cd-section-head mt-8">
                        <span class="cd-section-label"><i class="ri-briefcase-line"></i> Job Basics</span>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Job Title</label>
                            <select name="title" class="cd-form-select">
                                <option value="">-- Select a job role --</option>
                                
                                <optgroup label="Administrative &amp; Operations">
                                    <option value="Administrative Assistant" {{ old('title', $template->title) === 'Administrative Assistant' ? 'selected' : '' }}>Administrative Assistant</option>
                                    <option value="Executive Assistant" {{ old('title', $template->title) === 'Executive Assistant' ? 'selected' : '' }}>Executive Assistant</option>
                                    <option value="Operations Manager" {{ old('title', $template->title) === 'Operations Manager' ? 'selected' : '' }}>Operations Manager</option>
                                    <option value="Operations Coordinator" {{ old('title', $template->title) === 'Operations Coordinator' ? 'selected' : '' }}>Operations Coordinator</option>
                                    <option value="Office Manager" {{ old('title', $template->title) === 'Office Manager' ? 'selected' : '' }}>Office Manager</option>
                                    <option value="Virtual Assistant" {{ old('title', $template->title) === 'Virtual Assistant' ? 'selected' : '' }}>Virtual Assistant</option>
                                    <option value="Document Controller" {{ old('title', $template->title) === 'Document Controller' ? 'selected' : '' }}>Document Controller</option>
                                </optgroup>
                                
                                <optgroup label="Finance &amp; Accounting">
                                    <option value="Accountant" {{ old('title', $template->title) === 'Accountant' ? 'selected' : '' }}>Accountant</option>
                                    <option value="Bookkeeper" {{ old('title', $template->title) === 'Bookkeeper' ? 'selected' : '' }}>Bookkeeper</option>
                                    <option value="Financial Analyst" {{ old('title', $template->title) === 'Financial Analyst' ? 'selected' : '' }}>Financial Analyst</option>
                                    <option value="Accounts Payable Specialist" {{ old('title', $template->title) === 'Accounts Payable Specialist' ? 'selected' : '' }}>Accounts Payable Specialist</option>
                                    <option value="Accounts Receivable Specialist" {{ old('title', $template->title) === 'Accounts Receivable Specialist' ? 'selected' : '' }}>Accounts Receivable Specialist</option>
                                    <option value="Payroll Specialist" {{ old('title', $template->title) === 'Payroll Specialist' ? 'selected' : '' }}>Payroll Specialist</option>
                                    <option value="Audit Assistant" {{ old('title', $template->title) === 'Audit Assistant' ? 'selected' : '' }}>Audit Assistant</option>
                                    <option value="Tax Specialist" {{ old('title', $template->title) === 'Tax Specialist' ? 'selected' : '' }}>Tax Specialist</option>
                                </optgroup>
                                
                                <optgroup label="Customer Support">
                                    <option value="Customer Service Representative" {{ old('title', $template->title) === 'Customer Service Representative' ? 'selected' : '' }}>Customer Service Representative</option>
                                    <option value="Customer Support Specialist" {{ old('title', $template->title) === 'Customer Support Specialist' ? 'selected' : '' }}>Customer Support Specialist</option>
                                    <option value="Technical Support Representative" {{ old('title', $template->title) === 'Technical Support Representative' ? 'selected' : '' }}>Technical Support Representative</option>
                                    <option value="Help Desk Agent" {{ old('title', $template->title) === 'Help Desk Agent' ? 'selected' : '' }}>Help Desk Agent</option>
                                    <option value="Client Success Manager" {{ old('title', $template->title) === 'Client Success Manager' ? 'selected' : '' }}>Client Success Manager</option>
                                    <option value="Call Center Agent" {{ old('title', $template->title) === 'Call Center Agent' ? 'selected' : '' }}>Call Center Agent</option>
                                </optgroup>
                                
                                <optgroup label="Sales &amp; Marketing">
                                    <option value="Sales Representative" {{ old('title', $template->title) === 'Sales Representative' ? 'selected' : '' }}>Sales Representative</option>
                                    <option value="Sales Executive" {{ old('title', $template->title) === 'Sales Executive' ? 'selected' : '' }}>Sales Executive</option>
                                    <option value="Account Manager" {{ old('title', $template->title) === 'Account Manager' ? 'selected' : '' }}>Account Manager</option>
                                    <option value="Business Development Representative" {{ old('title', $template->title) === 'Business Development Representative' ? 'selected' : '' }}>Business Development Representative</option>
                                    <option value="Digital Marketing Specialist" {{ old('title', $template->title) === 'Digital Marketing Specialist' ? 'selected' : '' }}>Digital Marketing Specialist</option>
                                    <option value="Social Media Manager" {{ old('title', $template->title) === 'Social Media Manager' ? 'selected' : '' }}>Social Media Manager</option>
                                    <option value="Content Writer" {{ old('title', $template->title) === 'Content Writer' ? 'selected' : '' }}>Content Writer</option>
                                    <option value="SEO Specialist" {{ old('title', $template->title) === 'SEO Specialist' ? 'selected' : '' }}>SEO Specialist</option>
                                </optgroup>
                                
                                <optgroup label="Human Resources">
                                    <option value="HR Assistant" {{ old('title', $template->title) === 'HR Assistant' ? 'selected' : '' }}>HR Assistant</option>
                                    <option value="HR Generalist" {{ old('title', $template->title) === 'HR Generalist' ? 'selected' : '' }}>HR Generalist</option>
                                    <option value="Recruitment Specialist" {{ old('title', $template->title) === 'Recruitment Specialist' ? 'selected' : '' }}>Recruitment Specialist</option>
                                    <option value="Talent Acquisition Specialist" {{ old('title', $template->title) === 'Talent Acquisition Specialist' ? 'selected' : '' }}>Talent Acquisition Specialist</option>
                                    <option value="Training Coordinator" {{ old('title', $template->title) === 'Training Coordinator' ? 'selected' : '' }}>Training Coordinator</option>
                                </optgroup>
                                
                                <optgroup label="IT &amp; Technical">
                                    <option value="Software Developer" {{ old('title', $template->title) === 'Software Developer' ? 'selected' : '' }}>Software Developer</option>
                                    <option value="Web Developer" {{ old('title', $template->title) === 'Web Developer' ? 'selected' : '' }}>Web Developer</option>
                                    <option value="Frontend Developer" {{ old('title', $template->title) === 'Frontend Developer' ? 'selected' : '' }}>Frontend Developer</option>
                                    <option value="Backend Developer" {{ old('title', $template->title) === 'Backend Developer' ? 'selected' : '' }}>Backend Developer</option>
                                    <option value="Full Stack Developer" {{ old('title', $template->title) === 'Full Stack Developer' ? 'selected' : '' }}>Full Stack Developer</option>
                                    <option value="QA Tester" {{ old('title', $template->title) === 'QA Tester' ? 'selected' : '' }}>QA Tester</option>
                                    <option value="IT Support Specialist" {{ old('title', $template->title) === 'IT Support Specialist' ? 'selected' : '' }}>IT Support Specialist</option>
                                    <option value="System Administrator" {{ old('title', $template->title) === 'System Administrator' ? 'selected' : '' }}>System Administrator</option>
                                </optgroup>
                                
                                <optgroup label="Project &amp; Coordination">
                                    <option value="Project Coordinator" {{ old('title', $template->title) === 'Project Coordinator' ? 'selected' : '' }}>Project Coordinator</option>
                                    <option value="Project Manager" {{ old('title', $template->title) === 'Project Manager' ? 'selected' : '' }}>Project Manager</option>
                                    <option value="Scrum Master" {{ old('title', $template->title) === 'Scrum Master' ? 'selected' : '' }}>Scrum Master</option>
                                    <option value="Product Manager" {{ old('title', $template->title) === 'Product Manager' ? 'selected' : '' }}>Product Manager</option>
                                </optgroup>
                                
                                <optgroup label="Creative &amp; Design">
                                    <option value="Graphic Designer" {{ old('title', $template->title) === 'Graphic Designer' ? 'selected' : '' }}>Graphic Designer</option>
                                    <option value="UI/UX Designer" {{ old('title', $template->title) === 'UI/UX Designer' ? 'selected' : '' }}>UI/UX Designer</option>
                                    <option value="Video Editor" {{ old('title', $template->title) === 'Video Editor' ? 'selected' : '' }}>Video Editor</option>
                                    <option value="Multimedia Specialist" {{ old('title', $template->title) === 'Multimedia Specialist' ? 'selected' : '' }}>Multimedia Specialist</option>
                                </optgroup>
                                
                                <optgroup label="E-commerce &amp; Admin">
                                    <option value="E-commerce Specialist" {{ old('title', $template->title) === 'E-commerce Specialist' ? 'selected' : '' }}>E-commerce Specialist</option>
                                    <option value="Shopify Manager" {{ old('title', $template->title) === 'Shopify Manager' ? 'selected' : '' }}>Shopify Manager</option>
                                    <option value="Product Listing Specialist" {{ old('title', $template->title) === 'Product Listing Specialist' ? 'selected' : '' }}>Product Listing Specialist</option>
                                    <option value="Inventory Coordinator" {{ old('title', $template->title) === 'Inventory Coordinator' ? 'selected' : '' }}>Inventory Coordinator</option>
                                </optgroup>
                                
                                <optgroup label="Specialized Virtual Roles">
                                    <option value="Real Estate Virtual Assistant" {{ old('title', $template->title) === 'Real Estate Virtual Assistant' ? 'selected' : '' }}>Real Estate Virtual Assistant</option>
                                    <option value="Medical Virtual Assistant" {{ old('title', $template->title) === 'Medical Virtual Assistant' ? 'selected' : '' }}>Medical Virtual Assistant</option>
                                    <option value="Legal Virtual Assistant" {{ old('title', $template->title) === 'Legal Virtual Assistant' ? 'selected' : '' }}>Legal Virtual Assistant</option>
                                    <option value="Executive Virtual Assistant" {{ old('title', $template->title) === 'Executive Virtual Assistant' ? 'selected' : '' }}>Executive Virtual Assistant</option>
                                    <option value="Marketing Virtual Assistant" {{ old('title', $template->title) === 'Marketing Virtual Assistant' ? 'selected' : '' }}>Marketing Virtual Assistant</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Location</label>
                            <select name="location" class="cd-form-select">
                                <option value="">Select location</option>
                                @foreach(($dropdownOptions['locations'] ?? []) as $value => $label)
                                    <option value="{{ $value }}" @selected(old('location', $template->location) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Category</label>
                            <select name="category" class="cd-form-select">
                                <option value="">Select category</option>
                                @foreach(($dropdownOptions['categories'] ?? []) as $value => $label)
                                    <option value="{{ $value }}" @selected(old('category', $template->category) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Industry Type</label>
                            <select name="industry_type" class="cd-form-select">
                                <option value="">Select industry</option>
                                @foreach(($dropdownOptions['industryTypes'] ?? []) as $value => $label)
                                    <option value="{{ $value }}" @selected(old('industry_type', $template->industry_type) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Recruiter Type</label>
                            <select name="recruiter_type" class="cd-form-select">
                                <option value="">Select recruiter type</option>
                                @foreach(($dropdownOptions['recruiterTypes'] ?? []) as $value => $label)
                                    <option value="{{ $value }}" @selected(old('recruiter_type', $template->recruiter_type) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Job Settings --}}
                    <div class="cd-section-head mt-8">
                        <span class="cd-section-label"><i class="ri-settings-3-line"></i> Job Settings</span>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12 md:col-span-4">
                            <label class="cd-form-label">Employment Type</label>
                            <select name="employment_type" class="cd-form-select">
                                <option value="">Select type</option>
                                @foreach(($dropdownOptions['employmentTypes'] ?? []) as $value => $label)
                                    <option value="{{ $value }}" @selected(old('employment_type', $template->employment_type) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="cd-form-label">Remote Type</label>
                            <select name="remote_type" class="cd-form-select">
                                <option value="">Select type</option>
                                @foreach(($dropdownOptions['remoteTypes'] ?? []) as $value => $label)
                                    <option value="{{ $value }}" @selected(old('remote_type', $template->remote_type) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="cd-form-label">Vacancies</label>
                            <input type="number" name="vacancies" class="cd-form-input" value="{{ old('vacancies', $template->vacancies ?? 1) }}" min="1">
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Min Experience (years)</label>
                            <input type="number" name="experience_min_years" class="cd-form-input" value="{{ old('experience_min_years', $template->experience_min_years ?? 0) }}" min="0">
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Max Experience (years)</label>
                            <input type="number" name="experience_max_years" class="cd-form-input" value="{{ old('experience_max_years', $template->experience_max_years) }}" min="0">
                        </div>
                    </div>

                    {{-- Salary --}}
                    <div class="cd-section-head mt-8">
                        <span class="cd-section-label"><i class="ri-money-dollar-circle-line"></i> Salary Information</span>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12 md:col-span-4">
                            <label class="cd-form-label">Minimum Salary</label>
                            <input type="number" name="salary_min" class="cd-form-input" value="{{ old('salary_min', $template->salary_min) }}" placeholder="e.g., 30000" min="0" step="0.01">
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="cd-form-label">Maximum Salary</label>
                            <input type="number" name="salary_max" class="cd-form-input" value="{{ old('salary_max', $template->salary_max) }}" placeholder="e.g., 50000" min="0" step="0.01">
                        </div>
                        <div class="col-span-12 md:col-span-4">
                            <label class="cd-form-label">Currency</label>
                            <select name="salary_currency" class="cd-form-select">
                                @foreach(($dropdownOptions['currencies'] ?? ['PHP' => 'PHP', 'USD' => 'USD']) as $value => $label)
                                    <option value="{{ $value }}" @selected(old('salary_currency', $template->salary_currency ?? 'PHP') === $value)>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Job Highlights --}}
                    <div class="cd-section-head mt-8">
                        <span class="cd-section-label"><i class="ri-star-line"></i> Job Highlights</span>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Work Setup</label>
                            <select name="highlight_work_setup" class="cd-form-select">
                                <option value="">Select work setup</option>
                                @foreach(($dropdownOptions['workSetups'] ?? []) as $value => $label)
                                    <option value="{{ $value }}" @selected(old('highlight_work_setup', $template->highlight_work_setup) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Shift Schedule</label>
                            <select name="highlight_shift_schedule" class="cd-form-select">
                                <option value="">Select shift schedule</option>
                                @foreach(($dropdownOptions['shiftSchedules'] ?? []) as $value => $label)
                                    <option value="{{ $value }}" @selected(old('highlight_shift_schedule', $template->highlight_shift_schedule) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Description & Content --}}
                    <div class="cd-section-head mt-8">
                        <span class="cd-section-label"><i class="ri-file-text-line"></i> Job Description & Content</span>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12">
                            <label class="cd-form-label">Description</label>
                            <textarea name="description" class="cd-form-textarea" rows="5" placeholder="Enter job description...">{{ old('description', $template->description) }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Key Responsibilities</label>
                            <textarea name="responsibilities" class="cd-form-textarea" rows="5" placeholder="Enter responsibilities (one per line)...">{{ old('responsibilities', $template->responsibilities) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Enter each responsibility on a new line</p>
                        </div>
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Requirements</label>
                            <textarea name="requirements" class="cd-form-textarea" rows="5" placeholder="Enter requirements (one per line)...">{{ old('requirements', $template->requirements) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Enter each requirement on a new line</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4 mb-6">
                        <div class="col-span-12">
                            <label class="cd-form-label">Benefits</label>
                            <textarea name="benefits" class="cd-form-textarea" rows="4" placeholder="Enter benefits (one per line)...">{{ old('benefits', $template->benefits) }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Enter each benefit on a new line</p>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('employer.templates.index') }}" class="cd-btn cd-btn-outline">Cancel</a>
                        <button type="submit" class="cd-btn cd-btn-primary"><i class="ri-save-line me-1"></i> Update Template</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-app-layout>
