@extends('employers.onboarding.layout')

@section('content')
    <div class="onboard-card">
        <h2>Company Profile</h2>
        <p class="subtitle">Add a description and logo to make your job postings stand out.</p>

        <form method="POST" action="{{ route('employer.onboarding.store', ['step' => 3]) }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="description">About Company <span class="required">*</span></label>
                <textarea id="description" name="description" rows="5" required
                    placeholder="Describe your company culture, mission, and what you do..."
                    minlength="20">{{ old('description', $company->description ?? '') }}</textarea>
                <div class="hint">Minimum 20 characters. This will be shown on your public profile.</div>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="logo">Company Logo</label>
                <div class="flex items-center gap-4">
                    @if($company?->logo_url)
                        <div class="shrink-0">
                            <img src="{{ $company?->logo_url }}" alt="Logo"
                                class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif
                    <div class="w-full">
                        <input type="file" id="logo" name="logo" accept="image/*" class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-violet-50 file:text-violet-700
                            hover:file:bg-violet-100
                        " />
                    </div>
                </div>
                <div class="hint">Recommended size: 200x200px. Max size: 5MB.</div>
                @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="onboard-actions">
                <a href="{{ route('employer.onboarding.show', ['step' => 2]) }}" class="btn-back">
                    <i class="ri-arrow-left-line"></i> Back
                </a>
                <button type="submit" class="btn-next">
                    Next Step <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        </form>
    </div>
@endsection
