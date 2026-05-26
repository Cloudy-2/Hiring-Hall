@extends('applicants.onboarding.layout')

@section('step-content')
    <div class="onboard-card">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:6px;">
            <div
                style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;background:linear-gradient(135deg,rgba(229,161,0,0.1),rgba(229,161,0,0.05));border-radius:14px;flex-shrink:0;">
                <i class="ri-briefcase-4-fill" style="font-size:24px;color:var(--gold);"></i>
            </div>
            <div>
                <h2>Experience & Resume</h2>
                <p class="subtitle" style="margin-bottom:0;">Share your background so employers know what you bring to the
                    table.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('applicant.onboarding.store', ['step' => 3]) }}" enctype="multipart/form-data">
            @csrf

            <div class="section-divider"><i class="ri-bar-chart-box-line"></i> Overview</div>

            <div class="form-row">
                <div class="form-group">
                    <label>Years of Experience <span class="required">*</span></label>
                    <input type="number" name="years_experience" min="0" step="1"
                        value="{{ old('years_experience', $profile->years_experience) }}" required placeholder="e.g. 3">
                    <div class="hint"><i class="ri-information-line"></i> Enter 0 if you have no experience yet</div>
                </div>
                <div class="form-group">
                    <label>Highest Degree</label>
                    <input type="text" name="degree" value="{{ old('degree', $profile->degree) }}"
                        placeholder="e.g. Bachelor's in Business Admin">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Availability <span class="required">*</span></label>
                    <select name="availability" required>
                        <option value="" disabled {{ !old('availability', $profile->availability) ? 'selected' : '' }}>
                            Select availability</option>
                        @foreach(($dropdownOptions['availabilities'] ?? []) as $value => $label)
                            <option value="{{ $value }}" {{ old('availability', $profile->availability) === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Preferred Job Type <span class="required">*</span></label>
                    <select name="job_type" required>
                        <option value="" disabled {{ !old('job_type', $profile->job_type) ? 'selected' : '' }}>Select job
                            type</option>
                        @foreach(($dropdownOptions['jobTypes'] ?? []) as $value => $label)
                            <option value="{{ $value }}" {{ old('job_type', $profile->job_type) === $value ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="section-divider"><i class="ri-money-dollar-circle-line"></i> Salary</div>

            <div style="padding: 1rem; background: #f3f4f6; border-radius: 8px; border-left: 4px solid #4f46e5; margin-bottom: 1.5rem;">
                <p style="margin: 0; color: #1f2937; font-weight: 500;">Negotiable with Agency</p>
                <p style="margin: 0.5rem 0 0; color: #6b7280; font-size: 0.875rem;">Your salary will be discussed and negotiated directly with our agency based on your experience and qualifications.</p>
            </div>

            {{-- Recent Experience --}}
            <div class="section-divider"><i class="ri-building-2-line"></i> Most Recent Role <span
                    style="font-weight:400;color:#94a3b8;font-size:11px;text-transform:none;letter-spacing:0;">(optional)</span>
            </div>

            @php
                $exp = $profile->experience_overview ? json_decode($profile->experience_overview, true) : [];
                if (!is_array($exp))
                    $exp = [];
            @endphp
            <div class="form-row">
                <div class="form-group">
                    <label>Position / Title</label>
                    <select name="experience_position" id="experience_position" class="tomselect-basic" placeholder="Search or select job role...">
                        <option value="">-- Type to search --</option>
                        @forelse($dropdownOptions['expertiseCategories'] ?? [] as $value => $label)
                            <option value="{{ $label }}" {{ old('experience_position', $exp['position'] ?? '') === $label ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @empty
                            <option value="">No roles available</option>
                        @endforelse
                    </select>
                    <div class="hint"><i class="ri-briefcase-fill"></i> Type to search or select from the list</div>
                </div>
                <div class="form-group">
                    <label>Company</label>
                    <input type="text" name="experience_company"
                        value="{{ old('experience_company', $exp['company'] ?? '') }}" placeholder="e.g. ABC Consulting">
                </div>
            </div>
            <div class="form-row" style="grid-template-columns: 1fr 1fr 1fr;">
                <div class="form-group">
                    <label>Company Address</label>
                    <input type="text" name="experience_location"
                        value="{{ old('experience_location', $exp['location'] ?? '') }}" placeholder="Company Full Address">
                </div>
                @php
                    $startDateVal = old('experience_start');
                    if ($startDateVal === null && isset($exp['start_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $exp['start_date'])) {
                        $startDateVal = $exp['start_date'];
                    }
                @endphp
                <div class="form-group">
                    <label>Start Date</label>
                    <div class="date-field">
                        <input type="date" name="experience_start" id="experience_start"
                            value="{{ $startDateVal ?? '' }}"
                            max="{{ date('Y-m-d') }}" title="Select start date">
                        <i class="ri-calendar-line date-field-icon" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    @php
                        $endDateVal = old('experience_end');
                        if ($endDateVal === null && isset($exp['end_date']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $exp['end_date'])) {
                            $endDateVal = $exp['end_date'];
                        }
                        $isCurrent = old('experience_current', isset($exp['end_date']) && (empty($exp['end_date']) || strtolower((string)$exp['end_date']) === 'present'));
                    @endphp
                    <div class="date-field">
                        <input type="date" name="experience_end" id="experience_end"
                            value="{{ $endDateVal ?? '' }}"
                            max="{{ date('Y-m-d') }}" title="Select end date">
                        <i class="ri-calendar-line date-field-icon" aria-hidden="true"></i>
                    </div>
                    <label class="inline-checkbox-label" style="margin-top:8px;display:inline-flex;align-items:center;gap:6px;cursor:pointer;">
                        <input type="checkbox" name="experience_current" id="experience_current" value="1"
                            {{ $isCurrent ? 'checked' : '' }}>
                        <span>I currently work here (Present)</span>
                    </label>
                </div>
            </div>

            {{-- Resume Upload --}}
            <div class="section-divider"><i class="ri-file-upload-line"></i> Resume</div>
            <div class="form-group">
                <div class="cv-upload-zone {{ $profile->cv_path ? 'has-file' : '' }}" id="cv-zone"
                    onclick="document.getElementById('cv_file').click()">
                    @if($profile->cv_path)
                        <i class="ri-file-check-line"></i>
                        <div class="upload-text">Resume uploaded successfully ✓</div>
                        <div class="upload-hint">Click to replace</div>
                    @else
                        <i class="ri-upload-cloud-2-line"></i>
                        <div class="upload-text">Click to upload your resume</div>
                        <div class="upload-hint">PDF, DOC, DOCX — Max 5MB</div>
                    @endif
                </div>
                <input type="file" name="cv_file" id="cv_file" accept=".pdf,.doc,.docx" style="display:none"
                    onchange="handleCVSelect(this)">
            </div>

            <div class="onboard-actions">
                <a href="{{ route('applicant.onboarding.show', ['step' => 2]) }}" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Back
                </a>
                <button type="submit" class="btn-next">
                    Continue <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        </form>
    </div>

    <script>
        const startDateInput = document.getElementById('experience_start');
        const endDateInput = document.getElementById('experience_end');
        const step3Form = document.querySelector('form');

        function syncDateConstraints() {
            if (!startDateInput || !endDateInput) return;

            if (startDateInput.value) {
                endDateInput.min = startDateInput.value;
            } else {
                endDateInput.removeAttribute('min');
            }

            if (endDateInput.value) {
                startDateInput.max = endDateInput.value;
            } else {
                startDateInput.removeAttribute('max');
            }

            if (startDateInput.value && endDateInput.value && endDateInput.value < startDateInput.value) {
                const message = 'End date cannot be earlier than start date.';
                endDateInput.setCustomValidity(message);
                startDateInput.setCustomValidity('Start date cannot be later than end date.');
            } else {
                endDateInput.setCustomValidity('');
                startDateInput.setCustomValidity('');
            }
        }

        startDateInput?.addEventListener('change', syncDateConstraints);
        endDateInput?.addEventListener('change', syncDateConstraints);

        step3Form?.addEventListener('submit', function (event) {
            syncSalaryConstraints();
            syncDateConstraints();

            if (!this.checkValidity()) {
                event.preventDefault();
                this.reportValidity();
            }
        });

        syncSalaryConstraints();
        syncDateConstraints();

        document.getElementById('experience_current')?.addEventListener('change', function() {
            var endInput = document.getElementById('experience_end');
            if (endInput) {
                endInput.disabled = this.checked;
                if (this.checked) endInput.value = '';
            }
        });
        if (document.getElementById('experience_current')?.checked) {
            document.getElementById('experience_end').disabled = true;
        }

        function handleCVSelect(input) {
            const zone = document.getElementById('cv-zone');
            if (input.files.length > 0) {
                zone.classList.add('has-file');
                zone.style.transition = 'all 0.3s ease';
                zone.innerHTML = `
                    <i class="ri-file-check-line"></i>
                    <div class="upload-text" style="color:#059669;font-weight:600;">${input.files[0].name}</div>
                    <div class="upload-hint">Click to change</div>
                `;
            }
        }

        // Drag-and-drop support
        const zone = document.getElementById('cv-zone');
        ['dragenter', 'dragover'].forEach(e => {
            zone.addEventListener(e, (ev) => {
                ev.preventDefault();
                zone.style.borderColor = 'var(--gold)';
                zone.style.background = '#fffdf7';
                zone.style.transform = 'scale(1.01)';
            });
        });
        ['dragleave', 'drop'].forEach(e => {
            zone.addEventListener(e, (ev) => {
                ev.preventDefault();
                zone.style.borderColor = '';
                zone.style.background = '';
                zone.style.transform = '';
            });
        });
        zone.addEventListener('drop', (ev) => {
            const file = ev.dataTransfer.files[0];
            if (file) {
                const input = document.getElementById('cv_file');
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
                handleCVSelect(input);
            }
        });
    </script>
@endsection
