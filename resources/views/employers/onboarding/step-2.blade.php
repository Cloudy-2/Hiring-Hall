@extends('employers.onboarding.layout')

@section('content')
    <div class="onboard-card">
        <h2>Company information & Contact Person</h2>
        <p class="subtitle">Company contact details and the primary contact for this account.</p>

        <form method="POST" action="{{ route('employer.onboarding.store', ['step' => 2]) }}">
            @csrf

            <h3 class="form-section-title">Company information</h3>
            <p class="form-section-subtitle">How can applicants reach your company? This info will be visible on your profile.</p>

            <div class="form-group">
                <label for="location">Primary Location <span class="required">*</span></label>
                <input type="text" id="location" name="location" value="{{ old('location', $company->location ?? '') }}"
                    required placeholder="e.g. New York, NY">
                <div class="hint"><i class="ri-map-pin-line"></i> Main office or headquarters location</div>
                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Company Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $company->email ?? '') }}" required
                        placeholder="careers@company.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Company Phone <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $company->phone ?? '') }}" required
                        placeholder="+1 (555) 000-0000">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <h3 class="form-section-title">Contact Person</h3>
            <p class="form-section-subtitle">Primary contact person for this account.</p>

            <div class="form-group">
                <label for="contact_name">Contact Name <span class="required">*</span></label>
                <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name', $company->contact_name ?? '') }}"
                    required placeholder="e.g. Jane Smith">
                @error('contact_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="contact_position">Contact Position <span class="required">*</span></label>
                    <input type="text" id="contact_position" name="contact_position" value="{{ old('contact_position', $company->contact_position ?? '') }}"
                        required placeholder="e.g. HR Manager">
                    @error('contact_position') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="contact_availability_time">Availability <span class="required">*</span></label>
                    <select id="contact_availability_time" name="contact_availability_time" required>
                        <option value="">Select availability</option>
                        <option value="Mon–Fri 9am–5pm EST" {{ (old('contact_availability_time', $company->contact_availability_time ?? '') == 'Mon–Fri 9am–5pm EST') ? 'selected' : '' }}>Mon–Fri 9am–5pm EST</option>
                        <option value="Mon–Fri 8am–4pm CST" {{ (old('contact_availability_time', $company->contact_availability_time ?? '') == 'Mon–Fri 8am–4pm CST') ? 'selected' : '' }}>Mon–Fri 8am–4pm CST</option>
                        <option value="Mon–Fri 9am–6pm" {{ (old('contact_availability_time', $company->contact_availability_time ?? '') == 'Mon–Fri 9am–6pm') ? 'selected' : '' }}>Mon–Fri 9am–6pm</option>
                        <option value="Weekdays 8am–5pm" {{ (old('contact_availability_time', $company->contact_availability_time ?? '') == 'Weekdays 8am–5pm') ? 'selected' : '' }}>Weekdays 8am–5pm</option>
                        <option value="Flexible" {{ (old('contact_availability_time', $company->contact_availability_time ?? '') == 'Flexible') ? 'selected' : '' }}>Flexible</option>
                        <option value="By appointment" {{ (old('contact_availability_time', $company->contact_availability_time ?? '') == 'By appointment') ? 'selected' : '' }}>By appointment</option>
                    </select>
                    <div class="hint"><i class="ri-time-line"></i> When this contact is typically available</div>
                    @error('contact_availability_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="contact_person_email">Contact Person Email <span class="required">*</span></label>
                    <input type="email" id="contact_person_email" name="contact_person_email" value="{{ old('contact_person_email', $company->contact_person_email ?? '') }}"
                        required placeholder="jane.smith@company.com">
                    @error('contact_person_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="contact_person_phone">Contact Person Phone <span class="required">*</span></label>
                    <input type="tel" id="contact_person_phone" name="contact_person_phone" value="{{ old('contact_person_phone', $company->contact_person_phone ?? '') }}"
                        required placeholder="+1 (555) 000-0000">
                    @error('contact_person_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="onboard-actions">
                <a href="{{ route('employer.onboarding.show', ['step' => 1]) }}" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Back
                </a>
                <button type="submit" class="btn-next">
                    Next Step <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        </form>
    </div>
@endsection
