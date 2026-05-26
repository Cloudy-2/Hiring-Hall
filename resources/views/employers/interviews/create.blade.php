<x-app-layout>

    <x-slot name="pageTitle">Schedule Interview</x-slot>
    <x-slot name="url_1">{"link": "/employer/dashboard", "text": "Dashboard"}</x-slot>
    <x-slot name="url_2">{"link": "/employer/interviews", "text": "Interviews"}</x-slot>
    <x-slot name="active">Schedule Interview</x-slot>

    @include('employers.partials.employer-styles')

    <x-modern-header chip="Schedule Interview" title="Schedule Interview"
        desc='Schedule an interview with an applicant'>
        <x-slot name="actions">
            <a href="{{ route('employer.interviews.index') }}" class="cd-hero-btn cd-hero-btn-ghost"><i
                    class="ri-arrow-left-line"></i> Back to Interviews</a>
        </x-slot>
    </x-modern-header>

    <div class="grid grid-cols-12 gap-x-5 gap-y-4 pb-6 sm:px-6 lg:px-8">
        {{-- ═══ Form ═══ --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="cd-section">
                <form method="POST" action="{{ route('employer.interviews.store') }}">
                    @csrf

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="list-disc ms-4">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($application)
                        <input type="hidden" name="job_application_id" value="{{ $application->id }}">

                        <div
                            class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($application->applicantProfile?->user?->name ?? 'A', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-semibold dark:text-white">
                                        {{ $application->applicantProfile?->user?->name ?? 'Unknown Applicant' }}</div>
                                    <div class="text-sm text-gray-500">
                                        Applied for: {{ $application->jobPosting?->title ?? 'Unknown Position' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-6">
                            <label class="form-label">Select Application <span class="text-danger">*</span></label>
                            <p class="text-sm text-gray-500 mb-2">To schedule an interview, please select an application
                                from your applicants list.</p>
                            <a href="{{ route('employer.applications.index') }}" class="cd-btn cd-btn-primary">
                                <i class="ri-user-search-line me-1"></i> View Applications
                            </a>
                        </div>
                    @endif

                    @if($application)
                        {{-- Interview Details --}}
                        <div class="cd-section-head">
                            <span class="cd-section-label"><i class="ri-information-line"></i> Interview Details</span>
                        </div>

                        <div class="grid grid-cols-12 gap-4 mb-6">
                            <div class="col-span-12">
                                <label class="form-label">Interview Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', 'Interview with ' . ($application->applicantProfile?->user?->name ?? 'Applicant')) }}"
                                    required>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 gap-4 mb-6">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">Interview Type <span class="text-danger">*</span></label>
                                <select name="interview_type" class="form-select" required>
                                    @foreach($types as $value => $label)
                                        <option value="{{ $value }}" @selected(old('interview_type', 'video') === $value)>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                                <select name="duration_minutes" class="form-select" required>
                                    <option value="15" @selected(old('duration_minutes') == 15)>15 minutes</option>
                                    <option value="30" @selected(old('duration_minutes', 30) == 30)>30 minutes</option>
                                    <option value="45" @selected(old('duration_minutes') == 45)>45 minutes</option>
                                    <option value="60" @selected(old('duration_minutes') == 60)>1 hour</option>
                                    <option value="90" @selected(old('duration_minutes') == 90)>1.5 hours</option>
                                    <option value="120" @selected(old('duration_minutes') == 120)>2 hours</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 gap-4 mb-6">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">Date & Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="scheduled_at" class="form-control"
                                    value="{{ old('scheduled_at') }}" required
                                    min="{{ now()->addHour()->format('Y-m-d\TH:i') }}">
                            </div>
                        </div>

                        {{-- Location/Meeting --}}
                        <div class="cd-section-head mt-8">
                            <span class="cd-section-label"><i class="ri-map-pin-line"></i> Location / Meeting</span>
                        </div>

                        <div class="grid grid-cols-12 gap-4 mb-6">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">Physical Location</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location') }}"
                                    placeholder="e.g., Office Address, Meeting Room">
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label">Meeting Link</label>
                                <input type="url" name="meeting_link" class="form-control" value="{{ old('meeting_link') }}"
                                    placeholder="e.g., Zoom, Google Meet, Teams link">
                            </div>
                        </div>

                        {{-- Additional Notes --}}
                        <div class="cd-section-head mt-8">
                            <span class="cd-section-label"><i class="ri-file-text-line"></i> Additional Information</span>
                        </div>

                        <div class="grid grid-cols-12 gap-4 mb-6">
                            <div class="col-span-12">
                                <label class="form-label">Description / Instructions</label>
                                <textarea name="description" class="form-control" rows="3"
                                    placeholder="Any special instructions or information for the applicant...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-12 gap-4 mb-6">
                            <div class="col-span-12">
                                <label class="form-label">Internal Notes</label>
                                <textarea name="notes" class="form-control" rows="3"
                                    placeholder="Notes for yourself (not visible to applicant)...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('employer.interviews.index') }}" class="cd-btn cd-btn-outline">Cancel</a>
                            <button type="submit" class="cd-btn cd-btn-primary"><i class="ri-calendar-check-line me-1"></i>
                                Schedule Interview</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        @if($application)
            {{-- Applicant Info Sidebar --}}
            <div class="col-span-12 lg:col-span-4">
                <div class="cd-section sticky top-4">
                    <div class="cd-section-head">
                        <span class="cd-section-label"><i class="ri-user-line"></i> Applicant Info</span>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Name</label>
                            <p class="dark:text-white">{{ $application->applicantProfile?->user?->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Email</label>
                            <p class="dark:text-white">{{ $application->applicantProfile?->user?->email ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Applied For</label>
                            <p class="dark:text-white">{{ $application->jobPosting?->title ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Applied On</label>
                            <p class="dark:text-white">{{ $application->applied_at?->format('M j, Y') ?? 'Unknown' }}</p>
                        </div>
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('applicants.details', ['applicant' => $application->applicantProfile?->id]) }}"
                                class="cd-btn cd-btn-outline cd-btn-sm w-full" target="_blank">
                                <i class="ri-external-link-line me-1"></i> View Full Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

</x-app-layout>