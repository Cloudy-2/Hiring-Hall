<x-app-layout>

    <x-slot name="pageTitle">Post a Job</x-slot>
    <x-slot name="url_1">{"link": "/employer/jobs", "text": "Job Postings"}</x-slot>
    <x-slot name="active">Post a Job</x-slot>

    @include('employers.partials.employer-styles')

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Page Hero ═══ --}}
        <div class="col-span-12">
            <div class="cd-page-hero">
                <div>
                    <h1 class="cd-page-hero-title"><i class="ri-briefcase-line me-2"></i>Post a New Job</h1>
                    <p class="cd-page-hero-sub">Create a new job listing to find the perfect candidate</p>
                </div>
                <div style="display:flex;gap:0.5rem">
                    <a href="{{ route('employer.jobs.index') }}" class="cd-hero-btn cd-hero-btn-ghost"><i class="ri-arrow-left-line"></i> Back to Jobs</a>
                </div>
            </div>
        </div>

        {{-- ═══ Form ═══ --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-file-list-3-fill"></i> Job Details</span>
                </div>

                @if($errors->any())
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:0.75rem 1rem;margin-bottom:1rem;font-size:0.875rem;color:#dc2626">
                        <i class="ri-error-warning-line me-1"></i>
                        <ul style="margin:0.25rem 0 0 1rem;padding:0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('jobs.store') }}">
                    @csrf

                    <div class="grid grid-cols-12 gap-4">

                        {{-- Company Selection --}}
                        <div class="col-span-12">
                            <label class="cd-form-label">Company <span style="color:#dc2626">*</span></label>
                            <select name="company_id" class="cd-form-select @error('company_id') border-red-400 @enderror" required>
                                <option value="">Select a Company...</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ (old('company_id', $templateJob->company_id ?? '') == $company->id) || ($companies->count() == 1 && $loop->first) ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-xs text-gray-400 mt-1">
                                Don't see your company? <a href="{{ route('employer.companies.create') }}" class="text-indigo-600 hover:underline">Register a new one</a>.
                            </div>
                            @error('company_id') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Job Title --}}
                        <div class="col-span-12">
                            <label class="cd-form-label">Job Title <span style="color:#dc2626">*</span></label>
                            <input type="text" name="title" class="cd-form-input @error('title') border-red-400 @enderror"
                                   value="{{ old('title', $templateJob->title ?? '') }}" placeholder="e.g. Senior Operations Manager" required>
                            @error('title') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Job Category --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Category</label>
                            <select name="category" class="cd-form-select @error('category') border-red-400 @enderror">
                                <option value="">Select Category...</option>
                                @foreach($dropdownOptions['job_categories'] ?? [] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $templateJob->category ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Employment Type --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Employment Type</label>
                            <select name="employment_type" class="cd-form-select @error('employment_type') border-red-400 @enderror">
                                <option value="">Select Type...</option>
                                @foreach($dropdownOptions['employment_types'] ?? [] as $type)
                                    <option value="{{ $type }}" {{ old('employment_type', $templateJob->employment_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('employment_type') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                         {{-- Location --}}
                         <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Location</label>
                            <input type="text" name="location" class="cd-form-input @error('location') border-red-400 @enderror"
                                   value="{{ old('location', $templateJob->location ?? '') }}" placeholder="e.g. Remote, Manila, etc.">
                            @error('location') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Remote Type --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Work Setup</label>
                            <select name="remote_type" class="cd-form-select @error('remote_type') border-red-400 @enderror">
                                <option value="">Select Setup...</option>
                                @foreach($dropdownOptions['remote_types'] ?? ['Remote', 'On-site', 'Hybrid'] as $rt)
                                    <option value="{{ $rt }}" {{ old('remote_type', $templateJob->remote_type ?? '') == $rt ? 'selected' : '' }}>{{ $rt }}</option>
                                @endforeach
                            </select>
                            @error('remote_type') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Salary Range --}}
                        <div class="col-span-12">
                            <label class="cd-form-label">Salary Range (Monthly)</label>
                            <div class="grid grid-cols-12 gap-2">
                                <div class="col-span-4">
                                    <select name="salary_currency" class="cd-form-select">
                                        <option value="USD" {{ old('salary_currency', $templateJob->salary_currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="PHP" {{ old('salary_currency', $templateJob->salary_currency ?? '') == 'PHP' ? 'selected' : '' }}>PHP</option>
                                        <option value="AUD" {{ old('salary_currency', $templateJob->salary_currency ?? '') == 'AUD' ? 'selected' : '' }}>AUD</option>
                                    </select>
                                </div>
                                <div class="col-span-4">
                                    <input type="number" name="salary_min" class="cd-form-input" placeholder="Min" value="{{ old('salary_min', $templateJob->salary_min ?? '') }}">
                                </div>
                                <div class="col-span-4">
                                    <input type="number" name="salary_max" class="cd-form-input" placeholder="Max" value="{{ old('salary_max', $templateJob->salary_max ?? '') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="col-span-12">
                            <label class="cd-form-label">Job Description <span style="color:#dc2626">*</span></label>
                            <textarea name="description" class="cd-form-input @error('description') border-red-400 @enderror"
                                      rows="6" placeholder="Describe the role, responsibilities, and ideal candidate..." required>{{ old('description', $templateJob->description ?? '') }}</textarea>
                            @error('description') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                         {{-- Requirements --}}
                         <div class="col-span-12">
                            <label class="cd-form-label">Requirements (Optional)</label>
                            <textarea name="requirements" class="cd-form-input"
                                      rows="4" placeholder="List requirements, one per line...">{{ is_array(old('requirements', $templateJob->requirements ?? [])) ? implode("\n", old('requirements', $templateJob->requirements ?? [])) : old('requirements', $templateJob->requirements ?? '') }}</textarea>
                            <div class="text-xs text-gray-400 mt-1">Enter each requirement on a new line.</div>
                        </div>

                         {{-- Benefits --}}
                         <div class="col-span-12">
                            <label class="cd-form-label">Benefits (Optional)</label>
                            <textarea name="benefits" class="cd-form-input"
                                      rows="4" placeholder="List benefits, one per line...">{{ is_array(old('benefits', $templateJob->benefits ?? [])) ? implode("\n", old('benefits', $templateJob->benefits ?? [])) : old('benefits', $templateJob->benefits ?? '') }}</textarea>
                             <div class="text-xs text-gray-400 mt-1">Enter each benefit on a new line.</div>
                        </div>

                        {{-- Vacancies --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Vacancies</label>
                            <input type="number" name="vacancies" class="cd-form-input"
                                   value="{{ old('vacancies', $templateJob->vacancies ?? '1') }}" min="1">
                        </div>

                         {{-- Expire Date --}}
                         <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Closing Date</label>
                            <input type="date" name="closes_at" class="cd-form-input"
                                   value="{{ old('closes_at', isset($templateJob->closes_at) ? $templateJob->closes_at->format('Y-m-d') : '') }}">
                        </div>

                        {{-- Submit --}}
                        <div class="col-span-12" style="padding-top:1rem;border-top:1px solid #e5e7eb;margin-top:1rem">
                            <div style="display:flex;gap:0.5rem">
                                <button type="submit" class="cd-btn cd-btn-primary"><i class="ri-briefcase-line me-1"></i> Post Job</button>
                                <a href="{{ route('employer.jobs.index') }}" class="cd-btn cd-btn-outline">Cancel</a>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- ═══ Tips Sidebar ═══ --}}
        <div class="col-span-12 lg:col-span-4">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-lightbulb-flash-fill"></i> Tips for a great job post</span>
                </div>
                <div style="font-size:0.875rem;color:#6b7280;display:flex;flex-direction:column;gap:0.75rem">
                    <p><strong>Be specific about the role.</strong> Clear titles like "Senior React Developer" attract better candidates than generic ones like "Developer".</p>
                    <p><strong>Highlight your company culture.</strong> Candidates care about where they work. Mention perks, values, and team environment.</p>
                    <p><strong>Set realistic requirements.</strong> Focus on must-haves. A long list of nice-to-haves can discourage qualified candidates.</p>
                    <p><strong>Be transparent about pay.</strong> Job posts with salary ranges get significantly more applications.</p>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
