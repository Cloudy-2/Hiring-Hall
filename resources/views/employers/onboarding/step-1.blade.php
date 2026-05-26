@extends('employers.onboarding.layout')

@section('content')
<div class="onboard-card">
    <h2>Company information</h2>
    <p class="subtitle">Tell us about your organization to help applicants find you.</p>

    <form method="POST" action="{{ route('employer.onboarding.store', ['step' => 1]) }}">
        @csrf

        <div class="form-group">
            <label for="name">Company Name <span class="required">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $company->name ?? '') }}" required placeholder="e.g. Acme Corp">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="industry">Industry <span class="required">*</span></label>
                <select id="industry" name="industry" required>
                    <option value="">Select Industry</option>
                    @if(isset($industryTypes))
                        @foreach($industryTypes as $value => $label)
                            <option value="{{ $value }}" {{ (old('industry', $company->industry ?? '') == $value) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    @endif
                </select>
                @error('industry') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="organization_type">Organization Type</label>
                <input type="text" id="organization_type" name="organization_type" value="{{ old('organization_type', $company->recruiter_type ?? '') }}" placeholder="e.g. Agency, Direct Employer">
                @error('organization_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="website">Website</label>
            <input type="url" id="website" name="website" value="{{ old('website', $company->website ?? '') }}" placeholder="https://example.com">
            @error('website') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="established_year">Established Year</label>
                <input type="number" id="established_year" name="established_year" value="{{ old('established_year', $company->established_year ?? '') }}" min="1800" max="{{ date('Y') + 1 }}" placeholder="YYYY">
                @error('established_year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="employees_count">Employees</label>
                <select id="employees_count" name="employees_count">
                    <option value="">Select Range</option>
                    @foreach(['1-10', '11-50', '51-200', '201-500', '501-1000', '1000+'] as $range)
                        <option value="{{ $range }}" {{ (old('employees_count', $company->employees_count ?? '') == $range) ? 'selected' : '' }}>{{ $range }}</option>
                    @endforeach
                </select>
                @error('employees_count') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="onboard-actions">
            <div></div> {{-- Spacer --}}
            <button type="submit" class="btn-next">
                Next Step <i class="ri-arrow-right-line"></i>
            </button>
        </div>
    </form>
</div>
@endsection
