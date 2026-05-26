<x-app-layout 
    page-title="Applicant Details"
    :breadcrumbs="[
        ['label' => 'Applicant Verification', 'url' => route('moderator.applicants.index')],
    ]"
    active="{{ $applicant->display_name ?? $applicant->user->name ?? 'Profile' }}"
>


    <div class="grid grid-cols-12 gap-6">
        <div class="xl:col-span-8 col-span-12">
            <div class="box border">
                <div class="box-header flex items-center justify-between">
                    <h2 class="box-title m-0">Applicant Profile</h2>
                    <div>
                        @if($applicant->verification_status === 'verified')
                            <span class="badge bg-success/10 text-success" aria-label="Status: Verified">
                                <i class="ri-checkbox-circle-line me-1" aria-hidden="true"></i> Verified
                            </span>
                        @elseif($applicant->verification_status === 'pending')
                            <span class="badge bg-warning/10 text-warning" aria-label="Status: Pending Review">
                                <i class="ri-time-line me-1" aria-hidden="true"></i> Pending Review
                            </span>
                        @else
                            <span class="badge bg-danger/10 text-danger" aria-label="Status: Rejected">
                                <i class="ri-close-circle-line me-1" aria-hidden="true"></i> Rejected
                            </span>
                        @endif
                    </div>
                </div>
                <div class="box-body">
                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="status" aria-live="polite">{{ session('status') }}</div>
                    @endif

                    @error('notes')
                        <div class="alert alert-danger mb-4" role="alert">{{ $message }}</div>
                    @enderror

                    @if($applicant->verification_notes && $applicant->verification_status === 'rejected')
                        <div class="alert alert-danger mb-4" role="alert">
                            <strong>Rejection reason:</strong> {{ $applicant->verification_notes }}
                        </div>
                    @endif

                    <div class="flex items-start gap-4 mb-6">
                        <span class="avatar avatar-xxl avatar-rounded border overflow-hidden" aria-hidden="true">
                            @if(!empty($applicant->user?->profile_photo_url))
                                <img src="{{ $applicant->user->profile_photo_url }}"
                                    alt="{{ $applicant->display_name ?? $applicant->user->name ?? 'Applicant' }} profile photo"
                                    class="object-cover w-full h-full" loading="lazy">
                            @else
                                <span
                                    class="bg-primary/10 text-primary flex items-center justify-center w-full h-full text-2xl">
                                    {{ strtoupper(substr($applicant->user->name ?? 'N', 0, 2)) }}
                                </span>
                            @endif
                        </span>
                        <div>
                            <h2 class="text-xl font-bold">
                                {{ $applicant->display_name ?? $applicant->user->name ?? 'Unknown' }}</h2>
                            <p class="text-primary">
                                {{ $applicant->job_title ?? $applicant->title ?? 'No title specified' }}</p>
                            @if($applicant->headline)
                                <p class="text-textmuted mt-2">{{ $applicant->headline }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="text-xs text-textmuted">Location</label>
                            <p class="font-medium">{{ $applicant->location ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Work Mode</label>
                            <p class="font-medium">{{ $applicant->work_mode ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Availability</label>
                            <p class="font-medium">{{ $applicant->availability ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Experience</label>
                            <p class="font-medium">
                                {{ $applicant->years_experience !== null ? $applicant->years_experience . ' years' : '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Job Type</label>
                            <p class="font-medium">{{ $applicant->job_type ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-textmuted">Expected Salary</label>
                            <p class="font-medium">
                                @if($applicant->expected_salary_min || $applicant->expected_salary_max)
                                    {{ $applicant->salary_currency ?? 'PHP' }}
                                    {{ number_format($applicant->expected_salary_min ?? 0) }} -
                                    {{ number_format($applicant->expected_salary_max ?? 0) }}
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($applicant->about)
                        <div class="mb-4">
                            <h4 class="font-semibold mb-2">About</h4>
                            <p class="text-textmuted">{{ $applicant->about }}</p>
                        </div>
                    @endif

                    @php
                        // Safely parse JSON properties (with fallbacks if they're plain text)
                        $skills_arr = $applicant->skills ? json_decode($applicant->skills, true) : null;
                        $skills = is_array($skills_arr) ? collect($skills_arr)->filter()->toArray() : [];
                        if (empty($skills) && is_string($applicant->skills))
                            $skills = [$applicant->skills];

                        $experience = $applicant->experience_overview ? json_decode($applicant->experience_overview, true) : null;
                        if (!is_array($experience) && is_string($applicant->experience_overview)) {
                            $experience = false;
                        } elseif (is_array($experience) && isset($experience['position'])) {
                            $experience = [$experience]; // Wrap single object into array
                        }

                        $education = $applicant->education_details ? json_decode($applicant->education_details, true) : null;
                        if (!is_array($education) && is_string($applicant->education_details)) {
                            $education = false;
                        } elseif (is_array($education) && isset($education['school'])) {
                            $education = [$education];
                        }

                        $certifications = $applicant->certifications ? json_decode($applicant->certifications, true) : null;
                        if (!is_array($certifications) && is_string($applicant->certifications)) {
                            $certifications = false;
                        } elseif (is_array($certifications) && isset($certifications['title'])) {
                            $certifications = [$certifications];
                        }
                    @endphp

                    @if(!empty($skills) || $applicant->skills)
                        <div class="mb-4">
                            <h4 class="font-semibold mb-2">Skills</h4>
                            @if(!empty($skills) && is_array($skills_arr))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($skills as $skill)
                                        <span class="badge bg-primary/10 text-primary">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-textmuted">{{ $applicant->skills }}</p>
                            @endif
                        </div>
                    @endif

                    @if($applicant->experience_overview)
                        <div class="mb-4">
                            <h4 class="font-semibold mb-2">Experience</h4>
                            @if(is_array($experience))
                                <div class="space-y-4">
                                    @foreach($experience as $exp)
                                        <div class="border-l-2 border-primary/30 pl-4 py-1">
                                            <div class="font-semibold text-base">{{ $exp['position'] ?? '' }}</div>
                                            <div class="text-primary text-sm font-medium">{{ $exp['company'] ?? '' }}</div>
                                            @if(!empty($exp['start_date']) || !empty($exp['end_date']))
                                                <div class="text-xs text-textmuted mt-1">
                                                    {{ !empty($exp['start_date']) ? \Carbon\Carbon::parse($exp['start_date'])->format('M Y') : '' }}
                                                    -
                                                    {{ !empty($exp['end_date']) ? \Carbon\Carbon::parse($exp['end_date'])->format('M Y') : 'Present' }}
                                                    @if(!empty($exp['location']))
                                                        | {{ $exp['location'] }}
                                                    @endif
                                                </div>
                                            @endif
                                            @if(!empty($exp['responsibilities']) && is_array($exp['responsibilities']))
                                                <ul class="list-disc ms-5 mt-2 text-sm text-textmuted space-y-1">
                                                    @foreach($exp['responsibilities'] as $resp)
                                                        @if(!empty($resp))
                                                            <li>{{ $resp }}</li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="prose dark:prose-invert max-w-none text-sm">
                                    {!! nl2br(e($applicant->experience_overview)) !!}
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($applicant->education_details)
                        <div class="mb-4">
                            <h4 class="font-semibold mb-2">Education</h4>
                            @if(is_array($education))
                                <div class="space-y-3">
                                    @foreach($education as $edu)
                                        <div class="border-l-2 border-primary/30 pl-4 py-1">
                                            <div class="font-semibold text-sm">{{ $edu['school'] ?? '' }}</div>
                                            <div class="text-primary text-xs font-medium">{{ $edu['course'] ?? '' }}</div>
                                            @if(!empty($edu['dates']))
                                                <div class="text-xs text-textmuted mt-1">{{ $edu['dates'] ?? '' }}
                                                    @if(!empty($edu['location'])) | {{ $edu['location'] }} @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-textmuted">{{ $applicant->education_details }}</p>
                            @endif
                        </div>
                    @endif

                    @if($applicant->certifications)
                        <div class="mb-4">
                            <h4 class="font-semibold mb-2">Certifications</h4>
                            @if(is_array($certifications))
                                <div class="space-y-3">
                                    @foreach($certifications as $cert)
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 text-warning">
                                                <i class="ri-medal-fill text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-sm">{{ $cert['title'] ?? '' }}</div>
                                                <div class="text-xs text-textmuted">{{ $cert['provider'] ?? '' }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-textmuted">{{ $applicant->certifications }}</p>
                            @endif
                        </div>
                    @endif

                    {{-- Verification Actions --}}
                    <section class="border-t pt-4 mt-6" aria-labelledby="verification-actions-heading">
                        <h3 id="verification-actions-heading" class="font-semibold mb-4">Verification Actions</h3>
                        <div class="flex flex-wrap gap-2">
                            @if($applicant->verification_status === 'pending')
                                <form action="{{ route('moderator.applicants.verify', $applicant) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit" class="ti-btn ti-btn-success"
                                        aria-label="Verify this applicant profile">
                                        <i class="ri-check-line me-1" aria-hidden="true"></i> Verify Profile
                                    </button>
                                </form>
                                <button type="button" class="ti-btn ti-btn-danger reject-toggle-btn" aria-expanded="false"
                                    aria-controls="reject-section" aria-label="Show rejection form"
                                    data-reject-section="reject-section">
                                    <i class="ri-close-line me-1" aria-hidden="true"></i> Reject Profile
                                </button>
                            @elseif($applicant->verification_status === 'verified' || $applicant->verification_status === 'rejected')
                                <form action="{{ route('moderator.applicants.reset', $applicant) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit" class="ti-btn ti-btn-warning"
                                        aria-label="Reset verification to pending">
                                        <i class="ri-refresh-line me-1" aria-hidden="true"></i> Reset to Pending
                                    </button>
                                </form>
                            @endif
                        </div>

                        {{-- Reject Form --}}
                        <div id="reject-section" class="hidden mt-4 p-4 border rounded-lg bg-danger/5 border-danger/20"
                            role="region" aria-labelledby="reject-form-heading">
                            <h4 id="reject-form-heading" class="font-semibold mb-3 text-danger">Provide rejection reason
                            </h4>
                            <form action="{{ route('moderator.applicants.reject', $applicant) }}" method="POST"
                                class="applicant-reject-form">
                                @csrf
                                <div class="mb-3">
                                    <label for="applicant-reject-notes" class="form-label">Rejection Reason <span
                                            class="text-danger">*</span></label>
                                    <textarea name="notes" id="applicant-reject-notes" class="form-control" rows="3"
                                        required maxlength="1000"
                                        placeholder="e.g. Profile information is incomplete or could not be verified"
                                        aria-describedby="applicant-reject-notes-hint"></textarea>
                                    <p id="applicant-reject-notes-hint" class="text-sm text-textmuted mt-1">This may be
                                        shared with the applicant. Max 1000 characters.</p>
                                </div>
                                <button type="submit" class="ti-btn ti-btn-danger applicant-reject-submit">Confirm
                                    Rejection</button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            {{-- User Information --}}
            <div class="box border">
                <div class="box-header">
                    <div class="box-title">Account Information</div>
                </div>
                <div class="box-body">
                    @if($applicant->user)
                        <p class="text-sm"><strong>Name:</strong> {{ $applicant->user->name }}</p>
                        <p class="text-sm"><strong>Email:</strong> {{ $applicant->user->email }}</p>
                        <p class="text-sm"><strong>Registered:</strong> {{ $applicant->user->created_at->format('M d, Y') }}
                        </p>
                        <p class="text-sm"><strong>Email Verified:</strong>
                            @if($applicant->user->email_verified_at)
                                <span class="text-success">Yes</span>
                            @else
                                <span class="text-danger">No</span>
                            @endif
                        </p>
                    @else
                        <p class="text-textmuted">No user account linked</p>
                    @endif
                </div>
            </div>

            {{-- CV/Resume --}}
            @if($applicant->cv_path)
                <div class="box border mt-4">
                    <div class="box-header">
                        <div class="box-title">Resume/CV</div>
                    </div>
                    <div class="box-body">
                        <a href="{{ Storage::url($applicant->cv_path) }}" target="_blank" rel="noopener noreferrer"
                            class="ti-btn ti-btn-primary w-full" aria-label="View CV (opens in new tab)">
                            <i class="ri-file-text-line me-1" aria-hidden="true"></i> View CV
                        </a>
                    </div>
                </div>
            @endif

            {{-- Verification History --}}
            @if($applicant->verified_at)
                <div class="box border mt-4">
                    <div class="box-header">
                        <div class="box-title">Verification History</div>
                    </div>
                    <div class="box-body">
                        <p class="text-sm"><strong>Last Action:</strong> {{ ucfirst($applicant->verification_status) }}</p>
                        <p class="text-sm"><strong>Date:</strong> {{ $applicant->verified_at->format('M d, Y H:i') }}</p>
                        @if($applicant->verifiedByUser)
                            <p class="text-sm"><strong>By:</strong> {{ $applicant->verifiedByUser->name }}</p>
                        @endif
                        @if($applicant->verification_notes)
                            <p class="text-sm mt-2"><strong>Notes:</strong></p>
                            <p class="text-sm text-textmuted">{{ $applicant->verification_notes }}</p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Applications --}}
            <div class="box border mt-4">
                <div class="box-header">
                    <div class="box-title">Job Applications</div>
                </div>
                <div class="box-body">
                    <p class="text-2xl font-bold">{{ $applicant->applications->count() }}</p>
                    <p class="text-xs text-textmuted">Total applications submitted</p>
                </div>
            </div>

            {{-- Rating --}}
            @if($applicant->rating_count > 0)
                <div class="box border mt-4">
                    <div class="box-header">
                        <div class="box-title">Rating</div>
                    </div>
                    <div class="box-body">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-bold">{{ number_format($applicant->rating, 1) }}</span>
                            <i class="ri-star-fill text-warning text-xl"></i>
                        </div>
                        <p class="text-xs text-textmuted">Based on {{ $applicant->rating_count }} reviews</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toggleBtn = document.querySelector('.reject-toggle-btn');
            var rejectSection = document.getElementById('reject-section');
            if (toggleBtn && rejectSection) {
                toggleBtn.addEventListener('click', function () {
                    var isHidden = rejectSection.classList.toggle('hidden');
                    toggleBtn.setAttribute('aria-expanded', isHidden ? 'false' : 'true');
                    toggleBtn.setAttribute('aria-label', isHidden ? 'Show rejection form' : 'Hide rejection form');
                });
            }
            var rejectForm = document.querySelector('.applicant-reject-form');
            var rejectSubmit = document.querySelector('.applicant-reject-submit');
            if (rejectForm && rejectSubmit) {
                rejectForm.addEventListener('submit', function () {
                    rejectSubmit.disabled = true;
                    rejectSubmit.innerHTML = '<span class="inline-block animate-spin shrink-0 size-4 border-2 border-current border-transparent rounded-full me-1.5 align-middle" role="status" aria-hidden="true"></span> Submitting…';
                });
            }
        });
    </script>

</x-app-layout>