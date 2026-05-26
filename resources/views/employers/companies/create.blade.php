<x-app-layout>

    <x-slot name="pageTitle">Register Company</x-slot>
    <x-slot name="url_1">{"link": "/employer/companies", "text": "Companies"}</x-slot>
    <x-slot name="active">Register Company</x-slot>

    @include('employers.partials.employer-styles')

    <div class="grid grid-cols-12 gap-x-5 gap-y-4">

        {{-- ═══ Page Header ═══ --}}
        <x-modern-header
            chip="Registration"
            title="Register a Company"
            desc="Add your company profile to post jobs and attract candidates. Verified companies get 70% higher engagement."
            :container="false"
            headerClass="col-span-12"
        >
            <x-slot name="actions">
                <a href="{{ route('employer.companies.index') }}" class="cd-btn cd-btn-outline"><i class="ri-arrow-left-line"></i> Back</a>
            </x-slot>
        </x-modern-header>

        {{-- ═══ Form ═══ --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-building-2-fill"></i> Company Details</span>
                </div>

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

                <form id="company-register-form" method="POST" action="{{ route('employer.companies.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="terms_agreed" value="">

                    <div class="grid grid-cols-12 gap-4">

                        {{-- Company Name --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="cd-form-input @error('name') border-red-400 @enderror"
                                value="{{ old('name') }}" placeholder="e.g. Acme Corporation" autocomplete="organization" aria-required="true" required>
                            @error('name') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Industry --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Industry</label>
                            <input type="text" name="industry" class="cd-form-input @error('industry') border-red-400 @enderror"
                                   value="{{ old('industry') }}" placeholder="e.g. Technology, Healthcare">
                            @error('industry') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        <!-- Location is not needed for now -->
                        <!-- <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Location</label>
                            <input type="text" name="location" class="cd-form-input @error('location') border-red-400 @enderror"
                                   value="{{ old('location') }}" placeholder="e.g. Manila, Philippines">
                            @error('location') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div> -->

                        {{-- Website --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Website</label>
                            <input type="url" name="website" class="cd-form-input @error('website') border-red-400 @enderror"
                                   value="{{ old('website') }}" placeholder="https://example.com" autocomplete="url" aria-describedby="company-website-hint">
                            <div id="company-website-hint" class="text-xs text-gray-400 mt-1">Include the full URL, e.g. https://company.com</div>
                            @error('website') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Company Email</label>
                            <input type="email" name="email" class="cd-form-input @error('email') border-red-400 @enderror"
                                   value="{{ old('email') }}" placeholder="hr@company.com" autocomplete="email">
                            @error('email') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Phone</label>
                            <input type="text" name="phone" class="cd-form-input @error('phone') border-red-400 @enderror"
                                   value="{{ old('phone') }}" placeholder="+63 912 345 6789" autocomplete="tel">
                            @error('phone') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Established Year --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Established Year</label>
                            <input type="number" name="established_year" class="cd-form-input @error('established_year') border-red-400 @enderror"
                                   value="{{ old('established_year') }}" placeholder="{{ date('Y') }}" min="1800" max="{{ date('Y') }}">
                            @error('established_year') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Employees Count --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Number of Employees</label>
                            <input type="number" name="employees_count" class="cd-form-input @error('employees_count') border-red-400 @enderror"
                                   value="{{ old('employees_count') }}" placeholder="e.g. 50" min="1">
                            @error('employees_count') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="col-span-12">
                            <label class="cd-form-label">Company Description</label>
                            <textarea name="description" class="cd-form-input @error('description') border-red-400 @enderror"
                                      rows="4" placeholder="Tell candidates about your company, culture, and mission...">{{ old('description') }}</textarea>
                            @error('description') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Logo --}}
                        <div class="col-span-12">
                            <label class="cd-form-label">Company Logo</label>
                            <input type="file" name="logo" class="cd-form-input @error('logo') border-red-400 @enderror"
                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                            <div class="text-xs text-gray-400 mt-1">JPG, PNG, GIF up to 4MB</div>
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
                                <option value="SEC" {{ old('registration_type') === 'SEC' ? 'selected' : '' }}>SEC (Securities and Exchange Commission)</option>
                                <option value="DTI" {{ old('registration_type') === 'DTI' ? 'selected' : '' }}>DTI (Department of Trade and Industry)</option>
                                <option value="BIR" {{ old('registration_type') === 'BIR' ? 'selected' : '' }}>BIR TIN (Bureau of Internal Revenue)</option>
                                <option value="Other" {{ old('registration_type') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('registration_type') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Registration Number --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Registration Number <span class="text-danger">*</span></label>
                            <input type="text" name="registration_number" class="cd-form-input @error('registration_number') border-red-400 @enderror"
                                value="{{ old('registration_number') }}" placeholder="e.g. CS202012345" aria-required="true" required>
                            @error('registration_number') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Registration Document --}}
                        <div class="col-span-12">
                            <label class="cd-form-label">Registration Document <span class="text-danger">*</span></label>
                            <input type="file" name="registration_document" class="cd-form-input @error('registration_document') border-red-400 @enderror"
                                accept=".pdf,.jpg,.jpeg,.png" aria-required="true" required>
                            <div class="text-xs text-gray-400 mt-1">Upload your business registration certificate (PDF, JPG, PNG up to 10MB)</div>
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
                                value="{{ old('business_address') }}" placeholder="e.g. 123 Business Center, Ayala Ave." autocomplete="street-address" aria-required="true" required>
                            @error('business_address') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- City --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="cd-form-input @error('city') border-red-400 @enderror"
                                value="{{ old('city') }}" placeholder="e.g. Makati City" autocomplete="address-level2" aria-required="true" required>
                            @error('city') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Province --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Province <span class="text-danger">*</span></label>
                            <input type="text" name="province" class="cd-form-input @error('province') border-red-400 @enderror"
                                value="{{ old('province') }}" placeholder="e.g. Metro Manila" autocomplete="address-level1" aria-required="true" required>
                            @error('province') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Postal Code --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="cd-form-input @error('postal_code') border-red-400 @enderror"
                                   value="{{ old('postal_code') }}" placeholder="e.g. 1200" autocomplete="postal-code">
                            @error('postal_code') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Country --}}
                        <div class="col-span-12 md:col-span-6">
                            <label class="cd-form-label">Country <span class="text-danger">*</span></label>
                            <input type="text" name="country" class="cd-form-input @error('country') border-red-400 @enderror"
                                value="{{ old('country', 'Philippines') }}" placeholder="e.g. Philippines" autocomplete="country-name" aria-required="true" required>
                            @error('country') <div class="cd-form-error">{{ $message }}</div> @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="col-span-12 flex gap-2 pt-2">
                            <button type="button" class="cd-btn cd-btn-primary" onclick="if (typeof openCompanyAgreementModal === 'function') openCompanyAgreementModal(); else document.getElementById('agreement-modal-company').classList.remove('hidden');"><i class="ri-save-line me-1"></i> Register Company</button>
                            <a href="{{ route('employer.companies.index') }}" class="cd-btn cd-btn-outline">Cancel</a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- ═══ Info Sidebar ═══ --}}
        <div class="col-span-12 lg:col-span-4">
            <div class="cd-section">
                <div class="cd-section-head">
                    <span class="cd-section-label"><i class="ri-information-fill"></i> What happens next?</span>
                </div>
                <div class="flex flex-col gap-3 text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-start gap-2.5">
                        <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-indigo-50 text-xs font-bold text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300">1</div>
                        <div><strong class="text-gray-700 dark:text-gray-200">Submit your company</strong><br>Fill in the details and submit for review.</div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-indigo-50 text-xs font-bold text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300">2</div>
                        <div><strong class="text-gray-700 dark:text-gray-200">Moderator review</strong><br>Our team will verify your company details.</div>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-indigo-50 text-xs font-bold text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300">3</div>
                        <div><strong class="text-gray-700 dark:text-gray-200">Post jobs</strong><br>Once verified, you can post jobs under this company.</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('partials.agreement-modal-company', [
        'modalId' => 'agreement-modal-company',
        'formId' => 'company-register-form',
        'acceptButtonText' => 'Accept & Register Company',
    ])

</x-app-layout>
