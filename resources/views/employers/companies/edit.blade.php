@use('Illuminate\Support\Facades\Storage')
<x-app-layout>

    <x-slot name="pageTitle">Edit Company</x-slot>
    <x-slot name="url_1">{"link": "/employer/companies", "text": "Companies"}</x-slot>
    <x-slot name="active">Edit Company</x-slot>

    @include('employers.partials.employer-styles')

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Page Header ═══ --}}
        <x-modern-header
            chip="Profile Editing"
            title="Edit Company"
            desc="Update your company profile: <b>{{ $company->name }}</b>. Keep your details current to build trust with applicants."
            :container="false"
            headerClass="col-span-12"
        >
            <x-slot name="actions">
                <a href="{{ route('employer.companies.index') }}" class="cd-btn cd-btn-outline"><i class="ri-arrow-left-line"></i> Back</a>
            </x-slot>
        </x-modern-header>

        {{-- ═══ Form ═══ --}}
        <div class="col-span-12 lg:col-span-8" id="wt-form">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-building-2-fill"></i> Company Details</span>
                    {{-- Verification status --}}
                    @if($company->verified)
                        <span class="cd-status-pill cd-status-success"><i class="ri-verified-badge-fill me-1"></i>Verified</span>
                    @elseif($company->verification_status === 'pending')
                        <span class="cd-status-pill cd-status-warning"><i class="ri-time-line me-1"></i>Pending Review</span>
                    @elseif($company->verification_status === 'rejected')
                        <span class="cd-status-pill cd-status-danger"><i class="ri-close-circle-line me-1"></i>Rejected</span>
                    @endif
                </div>

                @if($company->rejection_reason)
                    <div class="cd-alert cd-alert-danger mb-4">
                        <i class="ri-error-warning-line me-1"></i><strong>Rejection reason:</strong> {{ $company->rejection_reason }}
                        <div class="text-xs mt-1 text-gray-500">Updating your details will re-submit for review.</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="cd-alert cd-alert-danger mb-4">
                        <i class="ri-error-warning-line me-1"></i>
                        <ul class="mb-0 mt-1 list-disc ps-4">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('employer.companies.update', $company) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-12 gap-4">

                        {{-- Company Name --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="cd-form-input @error('name') border-red-400 @enderror"
                                value="{{ old('name', $company->name) }}" autocomplete="organization" aria-required="true" required>
                            @error('name') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Industry --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Industry</label>
                            <input type="text" name="industry" class="cd-form-input @error('industry') border-red-400 @enderror"
                                   value="{{ old('industry', $company->industry) }}">
                            @error('industry') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        <!-- Location is not needed for now -->
                        <!-- <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Location</label>
                            <input type="text" name="location" class="cd-form-input @error('location') border-red-400 @enderror"
                                   value="{{ old('location', $company->location) }}">
                            @error('location') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div> -->

                        {{-- Website --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Website</label>
                            <input type="url" name="website" class="cd-form-input @error('website') border-red-400 @enderror"
                                   value="{{ old('website', $company->website) }}" autocomplete="url" aria-describedby="company-website-edit-hint">
                            <div id="company-website-edit-hint" class="text-xs text-gray-400 mt-1">Include the full URL, e.g. https://company.com</div>
                            @error('website') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Company Email</label>
                            <input type="email" name="email" class="cd-form-input @error('email') border-red-400 @enderror"
                                   value="{{ old('email', $company->email) }}" autocomplete="email">
                            @error('email') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Phone</label>
                            <input type="text" name="phone" class="cd-form-input @error('phone') border-red-400 @enderror"
                                   value="{{ old('phone', $company->phone) }}" autocomplete="tel">
                            @error('phone') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Established Year --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Established Year</label>
                            <input type="number" name="established_year" class="cd-form-input @error('established_year') border-red-400 @enderror"
                                   value="{{ old('established_year', $company->established_year) }}" min="1800" max="{{ date('Y') }}">
                            @error('established_year') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Employees Count --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Number of Employees</label>
                            <input type="number" name="employees_count" class="cd-form-input @error('employees_count') border-red-400 @enderror"
                                   value="{{ old('employees_count', $company->employees_count) }}" min="1">
                            @error('employees_count') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-span-12">
                            <label class="cd-form-label">Company Description</label>
                            <textarea name="description" class="cd-form-input @error('description') border-red-400 @enderror"
                                      rows="4">{{ old('description', $company->description) }}</textarea>
                            @error('description') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Logo --}}
                        <div class="col-span-12">
                            <label class="cd-form-label">Company Logo</label>
                            @if($company->logo_url)
                                <div class="mb-2 flex items-center gap-2.5">
                                    <img src="{{ $company->logo_url }}" alt="Current logo" class="h-11 w-11 rounded-lg border border-gray-200 object-cover">
                                    <span class="text-xs text-gray-400">Current logo</span>
                                </div>
                            @endif
                            <input type="file" name="logo" class="cd-form-input @error('logo') border-red-400 @enderror"
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                            <div class="text-xs text-gray-400 mt-1">Leave blank to keep current logo. JPG, PNG, GIF up to 4MB.</div>
                            @error('logo') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>

                {{-- ═══ Verification Information ═══ --}}
                <div class="cd-section mt-4">
                    <div class="cd-section-head">
                        <span class="cd-section-label"><i class="ri-verified-badge-fill"></i> Verification Information</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Required for company verification. Provide your official business registration details.</p>

                    <div class="grid grid-cols-12 gap-4">

                        {{-- Registration Type --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Registration Type <span class="text-danger">*</span></label>
                            <select name="registration_type" class="cd-form-input @error('registration_type') border-red-400 @enderror" aria-required="true" required>
                                <option value="">Select registration type...</option>
                                <option value="SEC" {{ old('registration_type', $company->registration_type) === 'SEC' ? 'selected' : '' }}>SEC (Securities and Exchange Commission)</option>
                                <option value="DTI" {{ old('registration_type', $company->registration_type) === 'DTI' ? 'selected' : '' }}>DTI (Department of Trade and Industry)</option>
                                <option value="BIR" {{ old('registration_type', $company->registration_type) === 'BIR' ? 'selected' : '' }}>BIR TIN (Bureau of Internal Revenue)</option>
                                <option value="Other" {{ old('registration_type', $company->registration_type) === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('registration_type') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Registration Number --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Registration Number <span class="text-danger">*</span></label>
                            <input type="text" name="registration_number" class="cd-form-input @error('registration_number') border-red-400 @enderror"
                                value="{{ old('registration_number', $company->registration_number) }}" placeholder="e.g. CS202012345" aria-required="true" required>
                            @error('registration_number') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Registration Document --}}
                        <div class="col-span-12">
                            <label class="cd-form-label">Registration Document <span class="text-danger">*</span></label>
                            @if($company->registration_document_url)
                                <div class="mb-2 flex items-center gap-2.5">
                                    <a href="{{ Storage::url($company->registration_document_url) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm flex items-center gap-1 cd-focus-ring">
                                        <i class="ri-file-text-line"></i> View current document
                                    </a>
                                    <span class="text-xs text-gray-400">({{ pathinfo($company->registration_document_url, PATHINFO_EXTENSION) }})</span>
                                </div>
                            @endif
                            <input type="file" name="registration_document" class="cd-form-input @error('registration_document') border-red-400 @enderror"
                                   accept=".pdf,.jpg,.jpeg,.png" {{ $company->registration_document_url ? '' : 'required' }}>
                            <div class="text-xs text-gray-400 mt-1">
                                @if($company->registration_document_url)
                                    Leave blank to keep current document. Upload new to replace (PDF, JPG, PNG up to 10MB)
                                @else
                                    Upload your business registration certificate (PDF, JPG, PNG up to 10MB)
                                @endif
                            </div>
                            @error('registration_document') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>

                {{-- ═══ Business Address ═══ --}}
                <div class="cd-section mt-4">
                    <div class="cd-section-head">
                        <span class="cd-section-label"><i class="ri-map-pin-fill"></i> Business Address</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Provide your official registered business address.</p>

                    <div class="grid grid-cols-12 gap-4">

                        {{-- Street Address --}}
                        <div class="col-span-12">
                            <label class="cd-form-label">Street Address <span class="text-danger">*</span></label>
                            <input type="text" name="business_address" class="cd-form-input @error('business_address') border-red-400 @enderror"
                                value="{{ old('business_address', $company->business_address) }}" placeholder="e.g. 123 Business Center, Ayala Ave." autocomplete="street-address" aria-required="true" required>
                            @error('business_address') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- City --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="cd-form-input @error('city') border-red-400 @enderror"
                                value="{{ old('city', $company->city) }}" placeholder="e.g. Makati City" autocomplete="address-level2" aria-required="true" required>
                            @error('city') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Province --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Province <span class="text-danger">*</span></label>
                            <input type="text" name="province" class="cd-form-input @error('province') border-red-400 @enderror"
                                value="{{ old('province', $company->province) }}" placeholder="e.g. Metro Manila" autocomplete="address-level1" aria-required="true" required>
                            @error('province') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Postal Code --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="cd-form-input @error('postal_code') border-red-400 @enderror"
                                   value="{{ old('postal_code', $company->postal_code) }}" placeholder="e.g. 1200" autocomplete="postal-code">
                            @error('postal_code') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Country --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Country <span class="text-danger">*</span></label>
                            <input type="text" name="country" class="cd-form-input @error('country') border-red-400 @enderror"
                                value="{{ old('country', $company->country ?? 'Philippines') }}" placeholder="e.g. Philippines" autocomplete="country-name" aria-required="true" required>
                            @error('country') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="col-span-12 flex gap-2 pt-2">
                            <button type="submit" class="cd-btn cd-btn-primary"><i class="ri-save-line me-1"></i> Save Changes</button>
                            <a href="{{ route('employer.companies.index') }}" class="cd-btn cd-btn-outline">Cancel</a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- ═══ Info Sidebar ═══ --}}
        <div class="col-span-12 lg:col-span-4" id="wt-sidebar">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-building-2-fill"></i> {{ $company->name }}</span>
                </div>
                <div class="flex flex-col gap-2 text-sm text-gray-500 dark:text-gray-400">
                    @if($company->location)
                        <div><i class="ri-map-pin-line me-1 text-indigo-500"></i>{{ $company->location }}</div>
                    @endif
                    @if($company->industry)
                        <div><i class="ri-building-2-line me-1 text-indigo-500"></i>{{ $company->industry }}</div>
                    @endif
                    @if($company->employees_count)
                        <div><i class="ri-team-line me-1 text-indigo-500"></i>{{ $company->employees_count }} employees</div>
                    @endif
                    @if($company->established_year)
                        <div><i class="ri-calendar-line me-1 text-indigo-500"></i>Est. {{ $company->established_year }}</div>
                    @endif
                    @if($company->website)
                        <div><i class="ri-global-line me-1 text-indigo-500"></i><a href="{{ $company->website }}" target="_blank" class="cd-focus-ring text-indigo-600 hover:text-indigo-800">{{ parse_url($company->website, PHP_URL_HOST) }}</a></div>
                    @endif
                </div>
                @if($company->isRejected())
                    <div class="mt-4 rounded-lg bg-red-50 p-3 text-xs text-red-600 dark:bg-red-900/20 dark:text-red-300">
                        <i class="ri-information-line me-1"></i>Updating and saving will re-submit this company for moderator review.
                    </div>
                @endif
            </div>
        </div>

    </div>

    @include('candidates.partials.walkthrough', [
        'wtSteps' => [
            ['target' => 'wt-hero',    'title' => 'Edit Company',         'icon' => 'ri-edit-line',         'body' => 'This page lets you update your company profile. Keep your details current to attract the best applicants and maintain your verification status.', 'position' => 'bottom'],
            ['target' => 'wt-form',    'title' => 'Company Details Form',  'icon' => 'ri-building-2-fill',   'body' => 'Fill in or update your company name, industry, location, website, contact info, and description. Upload a logo to make your listing stand out. Click "Save Changes" when you\'re done.', 'position' => 'right'],
            ['target' => 'wt-sidebar', 'title' => 'Company Summary',       'icon' => 'ri-information-line',  'body' => 'A quick snapshot of your company\'s current details. If your company was rejected, updating and saving here will automatically re-submit it for review.', 'position' => 'left'],
        ],
        'wtKey' => 'employer_company_edit',
    ])

</x-app-layout>
